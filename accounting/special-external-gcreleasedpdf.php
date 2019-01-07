<?php

	require('reports.php');
	if(isset($_GET['id']))
	{
		$id = trim($_GET['id']);
		if(trim($id)===''|| !is_numeric($id)||!checkifExist2($link,'spexgc_id','special_external_gcrequest','spexgc_id','spexgc_released',$id,'released'))
		{
			exit();
		}
	}
	else 
	{
		exit();
	}

	function setFooterText($text)
	{
		$this->footertext = $text;
	}


	$pdf = new REPORTS();
	$pdf->AliasNbPages();
	$pdf->AddPage("P","Letter");
	$pdf->SetFont('Arial','B',16);

	$table = 'special_external_gcrequest';
	$select = "special_external_gcrequest.spexgc_num,
		special_external_gcrequest.spexgc_datereq,
		special_external_gcrequest.spexgc_dateneed,
		special_external_gcrequest.spexgc_type,
		special_external_customer.spcus_companyname,
		special_external_gcrequest.spexgc_receviedby,
		approved_request.reqap_date,
		approved_request.reqap_trnum,
		CONCAT(releasedby.firstname,' ',releasedby.lastname) as rby";
	$where = "special_external_gcrequest.spexgc_id = '".$id."'
		AND
			special_external_gcrequest.spexgc_released='released'
		AND
			approved_request.reqap_trid = '".$id."'
		AND
			approved_request.reqap_approvedtype='special external releasing'";
	$join = 'INNER JOIN
			approved_request
		ON
			approved_request.reqap_trid = special_external_gcrequest.spexgc_id
		INNER JOIN
			users as releasedby
		ON
			releasedby.user_id = approved_request.reqap_preparedby
		INNER JOIN
			special_external_customer
		ON
			special_external_customer.spcus_id = special_external_gcrequest.spexgc_company
		';
	$limit = '';

	$data = getSelectedData($link,$table,$select,$where,$join,$limit);

	$pdf->setReportName('Special External GC Releasing Report');
	$text = 'Released #'.sprintf("%03d",$data->reqap_trnum).' Date:'._dateFormat($data->reqap_date).' Time:'._timeFormat($data->reqap_date);
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
	$pdf->subfooterpromo($data,$totcount);

	//$pdf->Output();

	$datenow = strtotime($todays_date);

	$pdf->Output('../reports/externalReport/special'.$id.'.pdf','F');
?>
<script>
	window.location = "<?php echo 'index.php?specialexternalreleasing='.$id; ?>";
</script>