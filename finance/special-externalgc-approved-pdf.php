<?php
	require('reports.php');
	if(isset($_GET['id']))
	{
		$id = trim($_GET['id']);
		if(trim($id)===''|| !is_numeric($id)||!checkifExist2($link,'spexgc_id','special_external_gcrequest','spexgc_id','spexgc_status',$id,'approved'))
		{
			exit();
		}
	}
	else 
	{
		exit();
	}

	$pdf = new REPORTS();
	
	$pdf->AliasNbPages();
	$pdf->AddPage("P","Letter");
	$pdf->SetFont('Arial','B',16);

	$pdf->setReportName('Special External GC Approval Report');

	// get request type
	$reqtype = getField($link,'spexgc_type','special_external_gcrequest','spexgc_id',$id);

	$table = 'special_external_gcrequest';
	$select = "special_external_gcrequest.spexgc_num,
		CONCAT(approvedby.firstname,' ',approvedby.lastname) as approved,
		approved_request.reqap_checkedby,
		approved_request.reqap_approvedby,
		special_external_gcrequest.spexgc_type,
		special_external_gcrequest.spexgc_datereq,
		special_external_gcrequest.spexgc_dateneed,
		special_external_gcrequest.spexgc_payment_arnum,
		special_external_customer.spcus_companyname,
		approved_request.reqap_date,
		access_page.title";
	$where = "special_external_gcrequest.spexgc_id = '".$id."'
		AND
			special_external_gcrequest.spexgc_status='approved'
		AND
			approved_request.reqap_trid = '".$id."'
		AND
			approved_request.reqap_approvedtype='Special External GC Approved'";
	$join = 'INNER JOIN
			approved_request
		ON
			approved_request.reqap_trid = special_external_gcrequest.spexgc_id
		INNER JOIN
			users as approvedby
		ON 
			approvedby.user_id = approved_request.reqap_preparedby
		INNER JOIN
			special_external_customer
		ON
			special_external_customer.spcus_id = special_external_gcrequest.spexgc_company
		INNER JOIN
			access_page
		ON
			access_page.access_no = approvedby.usertype';
	$limit = '';

	$data = getSelectedData($link,$table,$select,$where,$join,$limit);

	$text = 'Request #'.sprintf("%03d",$data->spexgc_num).' Date:'._dateFormat($data->reqap_date).' Time:'._timeFormat($data->reqap_date);
	$pdf->setFooterText($text);

	$pdf->docHeader($data);
	$pdf->subheaderpromo($id,$data);
	// get details

	$table = 'special_external_gcrequest_emp_assign';
	$select = 'spexgcemp_denom,
		spexgcemp_fname,
		spexgcemp_lname,
		spexgcemp_mname,
		spexgcemp_extname,
		spexgcemp_barcode';
	$where = "spexgcemp_trid='".$id."'";
	$join = '';
	$limit = '';

	$gcs = getAllData($link,$table,$select,$where,$join,$limit);

	if($data->spexgc_type=='1')
	{
		$pdf->detailsspecialexternalgcNonames($link,$data,$gcs);
	}
	else 
	{
		$pdf->detailsspecialexternalgc($link,$data,$gcs);
	}

	$totcount = totalExternalRequest($link,$id);
	$pdf->footerSpecialExternalGC($data,$totcount);

	

	//$pdf->Output();

	$pdf->Output('../reports/externalReport/gcrspecial'.$id.'.pdf','F');
?>
<script>
	window.location = "<?php echo 'index.php?specialexternal='.$id; ?>";
</script>