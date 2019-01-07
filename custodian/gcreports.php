<?php

session_start();
require_once('../config.php');
require_once('../function.php');
if(isset($_GET['id']))
{
	$id = $_GET['id'];
	if(trim($id)===''|| !is_numeric($id)||!checkIfReceived($link,$id))
	{
		exit();
	}
}
else 
{
	exit();
}


require('../fpdf.php');

class Xreport extends FPDF
{
	var $storename = '';

	function setStoreName($storen){
		$this->storename = $storen;
	}

	function Header()
	{		
		$this->SetFont("Helvetica", "", 18);
		$this->SetTextColor(28, 28, 28);
		$this->Cell(0, 15, 'Fixed Asset Department', 0, 0, "C");
		$this->Ln(1);
		$this->SetFont("times", "B", 12);
		$this->SetTextColor(28, 28, 28);
		$this->Ln();
		$this->Cell(0, 1, 'GC Receiving Report', 0, 0, "C");	
		$this->Ln(10);
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

	function Items($link,$id,$pe_num,$ref,$supplierName,$location,$supplierAdd,$remarks,$preparedby,$checkedby)
	{
		$this->Ln();
		$this->SetFont("times", "B", 12);
		$this->SetTextColor(28, 28, 28);
		$this->Cell(0, 4, 'Location    :   '.$location, 0, 0, "L");
		$this->Ln(8);
		$this->Cell(117, 1, 'Supplier    :   '.$supplierName, 0, 0, "L");		
		$this->Ln(5);
		$this->Cell(121, 1, 'Address     :   '.$supplierAdd, 0, 0, "L");
		$this->Cell(0, 0, 'Prod. No.  :   '.$pe_num, 0, 0, "C");
		$this->Ln(5);
		$this->Cell(146, 1, 'Ref No.      :   '.$ref, 0, 0, "L");
		$this->Cell(0, 1, 'Date          :   '.date("F j, Y"), 0, 0, "L");
		$this->Ln(5);
		$denom = getValidatedDenom($link,$id);
		if(count($denom)>0)
		{
			foreach ($denom as $den) {
				$this->Ln();
				$this->setx(12);
				$this->SetFont("times", "B", 12);
				$this->SetTextColor(28, 28, 28);
				$this->Cell(190,8,'GC Denomination  :   '.number_format($den->denomination,2),1,0,"L");
				$gc = getValidatedGC($link,$den->denom_id,$id);
				$this->Ln();
				$this->setx(12);
				$this->SetFont("times", "", 12);
				$this->SetTextColor(28, 28, 28);
				$x = 1;
				foreach ($gc as $gc) {
					$this->Cell(38,8,$gc->barcode_no,1,0,"C");
					if($x>4)
					{
						$x=0;
						$this->Ln();
						$this->setx(12);
					}
					$x++;
				}
				$this->Ln(5);
			}
			$this->Ln(8);
			$this->Cell(0, 1, 'Remarks  : '.$remarks, 0, 0, "L");
			$this->Ln(10);

			$this->Cell(64, 1, 'Prepared By:',0, 0, "L");
			$this->Cell(64, 1, 'Checked By:', 0, 0, "L");
			$this->Cell(0, 1, 'Noted By:', 0, 0, "L");
			$this->Ln(12);
			$this->setx(14);
			$this->Cell(64, 1, ucwords($preparedby), 0, 0, "C");
			$this->Cell(64, 1, ucwords($checkedby), 0, 0, "C");
			$this->Ln(4);
			$this->setx(14);
			$this->SetFont("times", "", 10);
			$this->Cell(64, 1, '(Signature over Printed name)', 0, 0, "C");
			$this->Cell(64, 1, '(Signature over Printed name)', 0, 0, "C");
			$this->Cell(64, 1, '(Signature over Printed name)', 0, 0, "C");


		}
	}
}
$pdf = new Xreport();
$pdf->setStoreName($id);
$pdf->AliasNbPages();
$pdf->AddPage("P","Letter");
$details = getReportDetails($link,$id);
foreach ($details as $key) {
	$pe_id = $key->pe_id;
	$pe_num = $key->pe_num;
	$ref = $key->requis_rmno;
	$supplierName = ucwords($key->gcs_companyname);
	$supplierAdd = ucwords($key->gcs_address);
	$location = $key->requis_loc;
	$remarks = $key->csrr_remaks;
	$preparedby = $key->firstname.' '.$key->lastname;
	$checkedby = $key->csrr_checked_by;
}
$pdf->Items($link,$pe_id,$pe_num,$ref,$supplierName,$location,$supplierAdd,$remarks,$preparedby,$checkedby);
$pdf->Output();
?>