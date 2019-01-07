<?php 

	require('reports.php');
	if(!isset($_SESSION['gccashier_id']) || !isset($_SESSION['gc_super_id']))
	{
		exit();
	}

	if(!isset($_GET['id']))
	{
		exit();
	}

	$id = (int)$_GET['id'];

	if(trim($id)=='')
	{
		exit();
	}

	//check if id exist
	if(!checkIfExist($link,'eos_id','end_of_shift_pos_details','eos_id',$id))
	{
		exit();
	}


	// class CREATEITEMREPORT extends FPDF
	// {
	// 	function Footer()
	// 	{
	// 		$this->SetY(-15);
	// 		$this->SetTextColor(74, 74, 74);
	// 		$this->SetFont("Arial", "", 7);
	// 		$this->SetDrawColor(74, 74, 74);
	// 		$this->SetLineWidth(0.2);
	// 		$this->Line(10, 265, 205, 265);
	// 		$this->Cell(0, 10, "Page ".$this->PageNo()." - {nb}", 0, 0, "C");
	// 		$this->Cell(0, 10, 'GC Sales Report ', 0, 0, "R");
	// 	}
	// }

	$pdf = new REPORTS();
	$pdf->AliasNbPages();
	$pdf->AddPage("P","Letter");
	$pdf->SetFont('Arial','B',16);

	$pdf->setReportType(2);
	$storename = getStoreName($link,$_SESSION['gccashier_store']);
	$pdf->docHeaderShortageOverageReport($storename);
	$cashier = getFullnameStoreStaff($link, $_SESSION['gccashier_id']);
	$pdf->subheaderShortageOverageReport($cashier,$todays_date, $todays_time);

	//get all denoms

	$table = 'pos_shortageoverage';
	$select = 'pos_shortageoverage.stover_qty,
		pos_denoms.pos_ddenom';
	$where = "stover_eosid='".$id."'";
	$join = 'LEFT JOIN
			pos_denoms
		ON
			pos_denoms.pos_did = pos_shortageoverage.stover_denomid';
	$limit = '';

	$data = getAllData($link,$table,$select,$where,$join,$limit);

	$pdf->Ln();
	$pdf->SetFont("Arial", "B", 10);
	//$pdf->Cell(194,8,'',1,1,'R');
	$pdf->Cell(24,8,'',0,0,'R');
	$pdf->Cell(50,8,'Denomination',1,0,'C');
	$pdf->Cell(50,8,'Quantity',1,0,'C');
	$pdf->Cell(50,8,'Subtotal',1,0,'C');
	$pdf->SetFont("Arial", "", 10);
	$pdf->Ln();
	$total = 0;
	foreach ($data as $d) {
		$sub = 0;
		$pdf->Cell(24,8,'',0,0,'R');
		$pdf->Cell(50,8,number_format($d->pos_ddenom,2),1,0,'C');
		$pdf->Cell(50,8,number_format($d->stover_qty,2),1,0,'C');
		$sub = $d->pos_ddenom * $d->stover_qty;
		$total +=$sub;
		$pdf->Cell(50,8,number_format($sub,2),1,0,'C');
		$pdf->Ln();
	}
	$pdf->Cell(24,8,'',0,0,'R');
	$pdf->Cell(100,8,'Total:',1,0,'R');
	$pdf->Cell(50,8,number_format($total,2),1,0,'C');
	//$pdf->Output();
	$pdf->Output('../reports/pos/gc_shortageoverage'.$_SESSION['gccashier_store'].'.pdf','F');
?>

<script>
	window.location = "<?php echo 'index.php?shortageoveragereport='.$_SESSION['gccashier_store']; ?>";
</script>