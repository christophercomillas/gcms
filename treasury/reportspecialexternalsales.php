<?php 	

	require('reports.php');
	if(isset($_GET['id']))
	{
		$id = trim($_GET['id']);
		if(trim($id)===''|| !is_numeric($id)||!checkIfExist($link,'spexgc_id','special_external_gcrequest','spexgc_id',$id))
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
	$pdf->setReportName('Special External GC Payment');
	$pdf->setRelNum($id);
	$pdf->setFooter(false);
	$deptid = getField($link,'usertype','users','user_id',$_SESSION['gc_id']);
	$usertype = getField($link,'title','access_page','access_no',$deptid);
	$pdf->docHeader($usertype);

	$table = 'special_external_gcrequest';
	$select = " special_external_gcrequest.spexgc_id,
	    special_external_gcrequest.spexgc_num,
	    special_external_gcrequest.spexgc_datereq,
	    special_external_gcrequest.spexgc_dateneed,
	    special_external_gcrequest.spexgc_remarks,
	    special_external_gcrequest.spexgc_paymentype,
	    special_external_gcrequest.spexgc_payment,
	    special_external_gcrequest.spexgc_payment_arnum,
	    CONCAT(users.firstname,' ',users.lastname) as recby,
	    institut_payment.institut_bankname,
	    institut_payment.institut_bankaccountnum,
	    institut_payment.institut_checknumber,
	    institut_payment.institut_amountrec,
	    special_external_customer.spcus_companyname,
	    special_external_customer.spcus_acctname";
	$where = "special_external_gcrequest.spexgc_id='$id'
		AND
			institut_payment.insp_paymentcustomer='special external'";
	$join = 'INNER JOIN
			users
		ON
			users.user_id = special_external_gcrequest.spexgc_reqby
		INNER JOIN
			institut_payment
		ON
			institut_payment.insp_trid = special_external_gcrequest.spexgc_id
		INNER JOIN
			special_external_customer
		ON
			special_external_customer.spcus_id = special_external_gcrequest.spexgc_company';
	$limit = '';
	$data = getSelectedData($link,$table,$select,$where,$join,$limit);
	
	$pdf->subheaderSpecial($id,$data);

	// get all denom and qty

	$table = 'special_external_gcrequest_items';
	$select = "*";
	$where = "specit_trid='$id'";
	$join = '';
	$limit = '';
	$datadenoms = getAllData($link,$table,$select,$where,$join,$limit);

	$pdf->displaySpecialDenoms($id,$datadenoms,$data);

	//$pdf->Output();

	$pdf->Output('../reports/externalReport/specialgcpayment'.$id.'.pdf','F');

?>
<script>
	window.location = "<?php echo 'index.php?specialgcpayment='.$id; ?>";
</script>

