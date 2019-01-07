<?php 
	require('reports.php');
	if(isset($_GET['daterange']))
	{
		$daterange = $_GET['daterange'];
		$drange = explode('-', $daterange);
		$drange1 = $drange[0];
		$drange2 = $drange[1];

		$drange1 = _dateFormatoSql($drange1);
		$drange2 = _dateFormatoSql($drange2);

	}
	else 
	{
		exit();
	}

	$pdf = new REPORTS();
	$pdf->AliasNbPages();
	$pdf->AddPage("P","Letter");
	$pdf->SetFont('Arial','B',16);

	//$pdf->setFooter(true);
	//var_dump($gc);
    $pdf->setFooterText("Verified GC -".$daterange);
	$pdf->setReportName('Verified Gift Check Report');
	$pdf->setFooter(true);
	$pdf->docHeaderReport($link,$drange1,$drange2);
	$pdf->Ln();
	$pdf->subheaderVerification($todays_date);
	$userid = $_SESSION['gc_id'];
    $store_id = getField($link,'store_assigned','users','user_id',$userid);
    $storename = getField($link,'store_name','stores','store_id',$store_id); 
    $storename = strtolower(str_replace(" ", "", $storename));

	$pdf->displayVerifiedGCByRange($link,$drange1,$drange2,$store_id);


    if($drange1==$drange2)
    {
    	$storename = $storename.$drange1;
    }
    else 
    {
    	$storename = $storename.$drange1.'to'.$drange2;
    }	
    $pdf->signaturesVerification();
	$pdf->Output($storename.'GC.pdf','D');
	//$pdf->Output();	

?>