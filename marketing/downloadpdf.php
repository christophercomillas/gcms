<?php

  session_start();
  require_once('../config.php');
  require_once('../function.php');

  $store = $_GET['store'];
  $den = $_GET['den'];
  $start = $_GET['start'];
  $end = $_GET['end'];

require('../fpdf.php');

class Xreport extends FPDF
{
	var $storename;
	var $datestart;
	var $dateend;
	var $denom;
	var $todaysDate;

	function setStoreName($storen)
	{
		$this->storename = $storen;
	}

	function setDenomination($den)
	{
		$this->denom = $den;
	}

	function setDateStart($start)
	{
		$this->datestart = $start;
	}

	function setDateEnd($end)
	{
		$this->dateend = $end;
	}

	function setDateToday($tDate)
	{
		$this->todaysDate = $tDate;
	}

	function Header()
	{
		$this->SetY(4);		
		$this->SetFont("Helvetica", "B", 10);
		$this->SetTextColor(28, 28, 28);
		$this->Cell(0, 4,$this->storename, 0, 0, "R");
		$this->Ln(4);
		$this->SetFont("Helvetica", "B", 8);
		$this->SetTextColor(28, 28, 28);
		$this->Cell(0, 4,'GC Sales Report', 0, 0, "R");
		$this->SetFont("Helvetica", "", 7);
		$this->SetTextColor(28, 28, 28);
		$this->Ln(4);
		$this->Cell(0, 4,$this->datestart.' - '.$this->dateend, 0, 0, "R");
		$this->Ln(4);
		$this->Cell(0, 4,$this->denom, 0, 0, "R");
		$this->SetDrawColor(74, 74, 74);
		$this->Ln(4);
		$this->Cell(0, 4,'Date Generated: '.$this->todaysDate, 0, 0, "R");
		$this->SetDrawColor(74, 74, 74);
		$this->SetLineWidth(0.1);
		$this->Line(10, 26, 205, 26);
		$this->Ln(10);
	}

	function reportContentx($data,$link,$denom)
	{
		foreach ($data as $d) {		
			$this->SetFont("Helvetica", "B", 6);
			$this->SetTextColor(28, 28, 28);
			$this->Ln(3);
			$this->Cell(70);
			$this->Cell(0, 0, 'Trans No.: '.$d->trans_number, 120, 0, "L");
			$this->Ln(3);
			$this->Cell(80);
			$this->Cell(30, 2, 'GC Barcode Number', 0, 0, "L");
			$this->Cell(10, 2, 'Price', 0, 0, "R");
			$this->Ln(4);
			$sales = $this->getTransactionSales($link,$d->trans_sid);
			foreach ($sales as $s) 
			{
				$this->Ln(3);
				$this->Cell(80);
				$this->Cell(30, 2, 'GC Barcode '.$s->sales_barcode, 0, 0, "L");
			}
		}
	}

	function Footer()
	{
		$this->SetY(-11);
		$this->SetTextColor(74, 74, 74);
		$this->SetFont("Arial", "", 7);
		$this->SetDrawColor(74, 74, 74);
		$this->SetLineWidth(0.2);
		$this->Line(10, 265, 205, 265);
		$this->Cell(0, 10, "Page ".$this->PageNo()." - {nb}", 0, 0, "C");
	}

	function reportContent($data,$link,$denom)
	{
		$this->SetFont("Helvetica", "B", 8);
		$this->SetTextColor(28, 28, 28);
		$this->Cell(38,6,'Store',1,0,"C");
		$this->Cell(38,6,'Barcode Number',1,0,"C");
		$this->Cell(38,6,'Denomination',1,0,"C");
		$this->Cell(38,6,'Cashier',1,0,"C");
		$this->Cell(38,6,'Date Sold',1,0,"C");
		$this->Ln();
		$this->SetFont("Helvetica", "B", 6);
		$this->SetTextColor(28, 28, 28);
		foreach ($data as $d) {
			$this->Cell(38,6,$d->store_name,1,0,"C");
			$this->Cell(38,6,$d->sales_barcode,1,0,"C");
			$this->Cell(38,6,number_format($d->denomination,2),1,0,"C");
			$this->Cell(38,6,ucwords($d->ss_firstname.' '.$d->ss_lastname),1,0,"C");			
			$this->Cell(38,6,_dateFormat($d->trans_datetime),1,0,"C");
			$this->Ln();
		}

	}
}

$storename = getStorename($link,$store);
$denom = getDenominationForReports($link,$den);
$data = getSalesFromStores($link,$store,$den,$start,$end);
$pdf = new Xreport();
$pdf->setStoreName($storename);
$pdf->setDateToday(_dateFormat($todays_date));
$pdf->setDateStart($start);
$pdf->setDateEnd($end);
$pdf->setDenomination($denom);
$pdf->AliasNbPages();
$pdf->AddPage("P","Letter");
$pdf->reportContent($data,$link,$den);
$pdf->Output('GCSalesReport'._dateFormatoSql($start).'to'._dateFormatoSql($end).'.pdf','D');
header('Content-type: application/pdf');
header('Content-Disposition: attachment; filename="downloadpdf.pdf"');
?>
