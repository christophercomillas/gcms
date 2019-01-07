<?php 
	require('reportpdf.php');
	if(!isset($_SESSION['gc_id']))
	{
		exit();
	}

	$pdf = new REPORTS();
	$pdf->AliasNbPages();
	//$pdf->AddPage("P","Letter");

	$stores = $_GET['stores'];
	$trans = $_GET['trans'];
	$d1 = $_GET['dstart'];
	$d2 = $_GET['dend'];
	$gcsales = $_GET['gcsales'];
	$reval = $_GET['reval'];
	$refund = $_GET['refund'];	

	if($_GET['stores']!='all')
	{
		$pdf->AddPage("P","Letter");
		$pdf->SetFont('Arial','B',16);
		$storeid = $_GET['stores'];
		$storename = getField($link,'store_name','stores','store_id',$storeid);
		$pdf->docHeaderStoreSalesReport($storename);
		$tdate = _dateFormat($todays_date);
		$trdate = getReportTransactionDate($trans,$d1,$d2,$stores,$gcsales,$reval,$refund,$link);
		$pdf->subheaderStoreSalesReport($tdate,$trdate);

		$pdf->gcTransactions($trans,$d1,$d2,$storeid,$gcsales,$reval,$refund,$link);
	}
	else 
	{
		$select = "store_name, store_id";
		$where = "store_status='active' ORDER BY store_id ASC";

		$stores = getAllData($link,'stores',$select,$where,'','');
		foreach ($stores as $st) 
		{
			$pdf->AddPage("P","Letter");
			$pdf->SetFont('Arial','B',16);
			$pdf->docHeaderStoreSalesReport($st->store_name);
			$tdate = _dateFormat($todays_date);
			$trdate = getReportTransactionDate($trans,$d1,$d2,$st->store_id,$gcsales,$reval,$refund,$link);
			$pdf->subheaderStoreSalesReport($tdate,$trdate);

			$pdf->gcTransactions($trans,$d1,$d2,$st->store_id,$gcsales,$reval,$refund,$link);		

		}
	}

	//$pdf->Output();
	$name = $_SESSION['gc_id'].'-'.getTimestamp();
	$pdf->Output('../reports/treasury/gc_sales'.$name.'.pdf','F');

	header('location:../reports/treasury/download.php?file=gc_sales'.$name.'.pdf');
?>
