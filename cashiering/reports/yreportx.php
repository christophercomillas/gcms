<?php

  session_start();
  require_once('../../config.php');
  require_once('../../function.php');
  if(!isLoggedInCashier()){
    header('location:../login.php');
  }

require('../../fpdf.php');

class Yreport extends FPDF
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
		$this->Cell(179);
		$this->Cell(0, 4, date("h:i:s a"), 0, 0, "R");

		$this->SetDrawColor(74, 74, 74);
		$this->SetLineWidth(0.1);
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

	function ReportTitle($storename,$supervisor){
		$this->SetFont("Helvetica", "", 9);
		$this->SetTextColor(28, 28, 28);
		$this->Cell(0, 4, $storename, 0, 0, "C");
		$this->Ln(3);
		$this->SetFont("Helvetica", "", 6);
		$this->SetTextColor(28, 28, 28);
		$this->Cell(0, 4, 'End of Shift Sales Report', 0, 0, "C");
		$this->Ln(3);
		$this->SetFont("Helvetica", "", 6);
		$this->SetTextColor(28, 28, 28);
		$this->Cell(0, 4, 'Cashier: '.ucwords($_SESSION['gc_cashier_fullname']), 0, 0, "C");
		$this->Ln(3);
		$this->SetFont("Helvetica", "", 6);
		$this->SetTextColor(28, 28, 28);
		$this->Cell(0, 4, 'Supervisor: '.ucwords($supervisor), 0, 0, "C");
	}

	function Sales($link,$todays_date){
		$this->Ln(4);
	    $this->SetFont('','B','6');
	    $this->SetFillColor(255,255,255);
	    $this->SetTextColor(0,0,0);
	    $this->SetDrawColor(92,92,92);
	    $this->SetLineWidth(.3);

	    $this->Ln(3);
	    $this->Cell(30);
	    $this->Cell(30,5,"Transaction Number",1,0,'C',true);
	    $this->Cell(20,5,"Time",1,0,'C',true);
	    $this->Cell(30,5,"Barcode Number",1,0,'C',true);
	    $this->Cell(26,5,"Denomination",1,0,'C',true);
	    $this->Cell(26,5,"Tender Type",1,0,'C',true);
	    $this->Ln();
	   	
	   	$query = $link->query(
	   		"SELECT 
	   			* 
	   		FROM 
	   			`transaction_stores`
	   		INNER JOIN
	   			`transaction_payment`
	   		ON 
	   			`transaction_stores`.`trans_sid` = `transaction_payment`.`payment_trans_num`
	   		WHERE 
	   			`trans_datetime` LIKE '%$todays_date%'
	   		AND 
	   			`trans_store`='".$_SESSION['gc_store']."'
	   		AND 
	   			`trans_cashier`='".$_SESSION['gc_id']."'
	   	");

	   	$n = $query->num_rows;

	   	if($n>0){

		   	while($row = $query->fetch_object()){

		   		$query_items = $link->query(
		   			"SELECT
		   				*
		   			FROM 
		   				`transaction_sales`
		   			INNER JOIN
		   				`denomination`
		   			ON
		   				`transaction_sales`.`sales_denomination` = `denomination`.`denom_id`		
		   			WHERE 
		   				`sales_transaction_id`='$row->trans_sid' 
		   		");

		   		while($row_items = $query_items->fetch_object()){
				    $this->Cell(30);
				    $this->Cell(30,5,$row->trans_number,1,0,'C',true);
				    $convertingtime = strtotime($row->trans_datetime);					 
				    $this->Cell(20,5,date("g:i a", $convertingtime),1,0,'C',true);
				    $this->Cell(30,5,$row_items->sales_barcode,1,0,'C',true);
				    $this->Cell(26,5,number_format($row_items->denomination,2),1,0,'C',true);
				    if($row->payment_tender == 1){
				    	$tender = 'Cash';
				    } else {
				    	$tender = 'Credit';
				    }
				    $this->Cell(26,5,$tender,1,0,'C',true);

				    $this->Ln();
		   		}
		   	}
		} else {
	    $this->Cell(30);
	    $this->Cell(132,5,"No transaction exist.",1,0,'L',true);
	    $this->Ln();
		}
	}

	function TransactionDetails($link,$todays_date){
	   	$query = $link->query(
	   		"SELECT 
	   			* 
	   		FROM 
	   			`transaction_stores`
	   		WHERE 
	   			`trans_datetime` LIKE '%$todays_date%'
	   	");

	   	$n = $query->num_rows;                                                                                    
	   	$query_sales = $link->query(
	   		"SELECT
				SUM(`denomination`.`denomination`) as storesales,
				`transaction_stores`.`trans_datetime`

				FROM 
				`transaction_sales`
				INNER JOIN
				`transaction_stores`
				ON
				`transaction_sales`.`sales_transaction_id` = `transaction_stores`.`trans_sid`
				INNER JOIN
				`denomination`
				ON
				`transaction_sales`.`sales_denomination` = `denomination`.`denom_id`  
				WHERE 
				`transaction_stores`.`trans_datetime` LIKE '%$todays_date%'
	   	");

	   	$row_sales = $query_sales->fetch_object();
		$this->Ln(2);
		$this->SetFont("Helvetica", "", 6);
		$this->SetTextColor(28, 28, 28);
		$this->Cell(160, 4, 'Total Sales: '.number_format($row_sales->storesales,2), 0, 0, "R");
		$this->Ln();
		$this->Cell(160, 4, 'Transaction Count: '.$n, 0, 0, "R");
		$this->Ln();
	
	}
}


$supervisor = getSupervisorFullname($link);
$storename = getField($link,'store_name','stores','store_id',$_SESSION['gc_store']);

$pdf = new Yreport();
$storename = getField($link,'store_name','stores','store_id',$_SESSION['gc_store']);
$pdf->AliasNbPages();
$pdf->AddPage("P","Letter");
$pdf->setStoreName($storename);

$pdf->ReportTitle($storename, $supervisor);
$pdf->Sales($link,$todays_date);
$pdf->TransactionDetails($link,$todays_date);

salesyreport($link,$todays_date);

$pdf->Output();
?>
