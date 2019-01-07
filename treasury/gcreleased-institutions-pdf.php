<?php 

	require('reports.php');
	if(isset($_GET['id']))
	{
		$id = trim($_GET['id']);
		if(trim($id)===''|| !is_numeric($id)||!checkIfExist($link,'institutr_id','institut_transactions','institutr_id',$id))
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

	$pdf->setReportName('Institution GC Releasing Report');
	$pdf->setRelNum($id);
	$pdf->setFooter(true);
	$deptid = getField($link,'usertype','users','user_id',$_SESSION['gc_id']);
	$usertype = getField($link,'title','access_page','access_no',$deptid);
	$pdf->docHeader($usertype);
	
	$table = 'institut_transactions';
	$select = "	institut_transactions.institutr_trnum,
		institut_transactions.institutr_paymenttype,
		institut_transactions.institutr_receivedby,
		institut_transactions.institutr_checkedby,
		institut_transactions.institutr_date,
		institut_transactions.institutr_totamtpayable,
		institut_transactions.institutr_checkamt,
		institut_transactions.institutr_cashamt,
		institut_transactions.institutr_amtchange,
		institut_transactions.institutr_totamtrec,
		institut_transactions.institutr_docname,
		institut_customer.ins_name,
		CONCAT(firstname,' ',lastname) as relby,
        payment_fund.pay_desc,
        institut_transactions.institutr_paymenttype,
	    institut_payment.institut_bankname,
	    institut_payment.institut_bankaccountnum,
	    institut_payment.institut_checknumber,
	    institut_payment.institut_amountrec";
	$where = "institut_transactions.institutr_id='".$id."'
		AND
			institut_transactions.institutr_trtype='sales'
		AND
			institut_payment.insp_paymentcustomer='institution'";
	$join = 'INNER JOIN
			institut_payment
		ON
			institut_payment.insp_trid = institut_transactions.institutr_id
		INNER JOIN
			institut_customer
		ON
			institut_customer.ins_id = institut_transactions.institutr_cusid
		INNER JOIN
			users
		ON
			users.user_id = institut_transactions.institutr_trby
		LEFT JOIN
			payment_fund
		ON
			payment_fund.pay_id = institut_transactions.institutr_payfundid';
	$limit = '';
	$data = getSelectedData($link,$table,$select,$where,$join,$limit);

	$text = 'Rel #'.sprintf("%03d",$data->institutr_trnum).' Date Printed:'._dateFormat($todays_date).' Time:'._timeFormat($todays_time);
	$pdf->setFooterText($text);

	$pdf->subheaderInst($id,$data);

	$denoms = getAllInsRel($link,$id);

	$pdf->detailsIns($link,$denoms,$id);

	$totcount = $link->query(
		"SELECT 
		    IFNULL(SUM(denomination.denomination),0.00) as total,
		    IFNULL(COUNT(institut_transactions_items.instituttritems_trid),0) as cnt
		FROM 
			institut_transactions_items 
		INNER JOIN
			gc
		ON
			gc.barcode_no = institut_transactions_items.instituttritems_barcode
		INNER JOIN
			denomination
		ON
			denomination.denom_id = gc.denom_id
		WHERE 
			institut_transactions_items.instituttritems_trid='".$id."'
	");

	if($totcount)
	{
		$totcount = $totcount->fetch_object();
	}

	$pdf->setDate($data->institutr_date);

	$pdf->subfooterIns($data,$totcount);

	//$pdf->Output();

	$pdf->Output('../reports/treasury_releasing_institutions/gcinst'.$id.'.pdf','F');

?>
<script>
	window.location = "<?php echo 'index.php?gcreleasedinst='.$id; ?>";
</script>

