<?php 

	require('reports.php');
	if(isset($_GET['id']))
	{
		$id = trim($_GET['id']);
		if(trim($id)===''|| !is_numeric($id)||!checkIfExist($link,'ieod_id','institut_eod','ieod_id',$id))
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

	$pdf->setFooter(true);

	$pdf->setReportName('Treasury Accountability Report');
	$pdf->setRelNum($id);

	$deptid = getField($link,'usertype','users','user_id',$_SESSION['gc_id']);
	$usertype = getField($link,'title','access_page','access_no',$deptid);
	$pdf->docHeader($usertype);

	$table = 'institut_eod';
	$select = "institut_eod.ieod_num,
		institut_eod.ieod_date,
		CONCAT(users.firstname,' ',users.lastname) as prepby";
	$where = "institut_eod.ieod_id='".$id."'";
	$join = 'LEFT JOIN
			users
		ON
			users.user_id = institut_eod.ieod_by';
	$limit = '';

	$info = getSelectedData($link,$table,$select,$where,$join,$limit);

	$text = 'EOD #'.$info->ieod_num.' Date Printed: '._dateFormat($todays_date).' Time: '._timeFormat($todays_time);
	//$text = 'Request #'.sprintf("%03d",$data->spexgc_num).' Date:'._dateFormat($data->reqap_date).' Time:'._timeFormat($data->reqap_date);
	$pdf->setFooterText($text);

	$table = 'institut_payment';
	$select = 'insp_id,
	    insp_trid,
	    insp_paymentcustomer,
	    institut_bankname,
	    institut_bankaccountnum,
	    institut_checknumber,
	    institut_amountrec,
	    insp_paymentnum,
	    institut_jvcustomer,
	    institut_eodid';
	$where = "institut_eodid='".$id."'";
	$join = '';
	$limit = 'ORDER BY insp_paymentnum DESC';

	$data = getAllData($link,$table,$select,$where,$join,$limit);	

	$pdf->subheaderCashierAccountability($info);

	$pdf->eodCustomer($link,$data);

	//$pdf->Output();

	$pdf->Output('../reports/treasury_eod/eod'.$id.'.pdf','F');

?>
<script>
	window.location = "<?php echo 'index.php?gceod='.$id; ?>";
</script>

