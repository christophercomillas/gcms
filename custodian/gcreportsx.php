<?php

session_start();
require_once('../config.php');
require_once('../function.php');

require('../fpdf.php');

class Xreport extends FPDF
{
	var $storename;

	function setStoreName($storen){
		$this->storename = $storen;
	}

	function Header()
	{		
		$this->SetFont("Helvetica", "", 6);
		$this->SetTextColor(28, 28, 28);
		$this->Cell(0, 4, date("F j, Y"), 0, 0, "R");
		$this->Ln(4);
		$this->Cell(0, 4, date("h:i:s a"), 0, 0, "R");
		$this->SetDrawColor(74, 74, 74);
		$this->SetLineWidth(0.5);
		$this->Line(10, 19, 205, 19);
		$this->Ln(6);

	}

	function Footer()
	{
		$this->SetY(-15);
		$this->SetTextColor(74, 74, 74);
		$this->SetFont("Arial", "", 7);
		$this->SetDrawColor(74, 74, 74);
		$this->SetLineWidth(0.2);
		$this->Line(10, 265, 205, 265);
		$this->Cell(0, 10, "Page ".$this->PageNo()." - {nb}", 0, 0, "C");
	}
}

$pdf = new Xreport();
$storename = 'sample';
$pdf->AliasNbPages();
$pdf->AddPage("P","Letter");
$pdf->Output();
?>