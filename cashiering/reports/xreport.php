<?php

  session_start();
  require_once('../../config.php');
  require_once('../../function.php');
  if(!isLoggedInCashier()){
    header('location:../login.php');
  }

require('../../fpdf.php');

class Xreport extends FPDF
{
	var $storename;
	var $supervisor;

	function setStoreName($storen){
		$this->storename = $storen;
	}

	function setSupervisorName($sname){
		$this->supervisor = $sname;
	}


	function Header()
	{
		$this->SetY(4);		
		$this->SetFont("Helvetica", "B", 10);
		$this->SetTextColor(28, 28, 28);
		$this->Cell(0, 4,$this->storename, 0, 0, "R");
		$this->Ln(5);
		$this->SetFont("Helvetica", "B", 8);
		$this->SetTextColor(28, 28, 28);
		$this->Cell(0, 4,'GC Sales', 0, 0, "R");
		$this->SetFont("Helvetica", "", 7);
		$this->SetTextColor(28, 28, 28);
		$this->Ln(5);
		$this->Cell(0, 4,date("F j, Y").' '.date("h:i:s a"), 0, 0, "R");
		$this->Ln(3);
		$this->Cell(0, 4,'Cashier: '.ucwords($_SESSION['gc_cashier_fullname']), 0, 0, "R");
		$this->Ln(3);
		$this->Cell(0, 4,'Supervisor: '.ucwords($this->supervisor), 0, 0, "R");
		$this->SetDrawColor(74, 74, 74);
		$this->SetLineWidth(0.1);
		$this->Line(10, 26, 205, 26);
		$this->Ln(1);

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

	function Sales($link,$todays_date){

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
	   	$x=1;

	   	if($n>0){
	   		while($row = $query->fetch_object()){
	   			if($x!=1)
	   			{
	   				$this->Ln(6);
	   			}
	   			else 
	   			$x++;
				$this->Ln(8);
				$this->SetFont("Helvetica", "B", 6);
				$this->SetTextColor(28, 28, 28);
				$this->Cell(70);
				$this->Cell(0, 0, 'Trans No.: '.$row->trans_number, 120, 0, "L");
				$this->Ln(5);
				$this->Cell(80);
				$this->Cell(30, 2, 'GC Barcode Number', 0, 0, "L");
				$this->Cell(10, 2, 'Price', 0, 0, "R");
				$this->Ln(4);

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

		   		$n_items = $query_items->num_rows;

		   		while($row_items = $query_items->fetch_object()){
					$this->Cell(80);
					$this->SetFont("Helvetica", "", 6);
					$this->SetTextColor(28, 28, 28);
					$this->Cell(30, 2, $row_items->sales_barcode, 0, 0, "L");
					$this->Cell(10, 2, number_format($row_items->denomination,2), 0, 0, "R");
					$this->Ln(3);
		   		}

		   		if($row->payment_tender=='1'){
		   			$tender = 'Cash';
		   		} else {
		   			$tender = 'Credit Card';
		   		}
				$this->Ln(5);
				$this->Cell(102);		
				$this->Cell(5, 2, 'Tender Type: ', 0, 0, "R");
				$this->Cell(5, 2, $tender, 0, 0, "L");
				$this->Ln(3);
				$this->Cell(102);
				$this->Cell(5, 2, 'Number of Items: ', 0, 0, "R");	
				$this->Cell(5, 2,$n_items, 0, 0, "L");
				$this->Ln(3);
				$this->Cell(102);
				$this->Cell(5, 2, 'Discount: ', 0, 0, "R");	
				$this->Cell(5, 2, '0.0', 0, 0, "L");
				$this->Ln(3);
				$this->Cell(102);
				$this->Cell(5, 2, 'Tax: ', 0, 0, "R");	
				$this->Cell(5, 2, '0.0', 0, 0, "L");
		   		$query_total = $link->query(
		   			"SELECT
		   				SUM(`denomination`.`denomination`) as totalsales
		   			FROM 
		   				`transaction_sales`
		   			INNER JOIN
		   				`denomination`
		   			ON
		   				`transaction_sales`.`sales_denomination` = `denomination`.`denom_id`		
		   			WHERE 
		   				`sales_transaction_id`='$row->trans_sid' 
		   		");

		   		$row_total = $query_total->fetch_object();
				$this->Ln(3);
				$this->Cell(102);
				$this->Cell(5, 2, 'Amount Due: ', 0, 0, "R");		
				$this->Cell(5, 2, number_format($row_total->totalsales,2), 0, 0, "L");				
			}


			$this->Ln(5);
	   	} else {
			$this->Ln(8);
			$this->Cell(160, 0, 'No Transaction to display.', 0, 0, "C");
	   	}
	}

	function returnItems($gc){
		if(count($gc)>0){
			$this->Ln(3);
			$this->SetFont("Arial", "B", 6);
			$this->SetTextColor(28, 28, 28);
			$this->Cell(70);
			$this->Cell(30, 2, 'Refund GC', 0, 0, "L");
			$this->Ln(5);
			$this->Cell(80);
			$this->Cell(30, 2, 'GC Barcode Number', 0, 0, "L");
			$this->Cell(10, 2, 'Price', 0, 0, "R");
			$this->Ln(4);
			foreach($gc as $gc){
				$this->Cell(80);
				$this->Cell(30, 2, $gc->rr_barcode_no, 0, 0, "L");
				$this->Cell(10, 2, number_format($gc->denomination,2), 0, 0, "R");
				$this->Ln(3);
			}	
		}
	}

	function TransactionDetails($link,$todays_date,$salescredit,$salescash,$returnedTotal){
	   	$query = $link->query(
	   		"SELECT 
	   			`trans_number` 
	   		FROM 
	   			`transaction_stores`
	   		WHERE 
	   			`trans_datetime` LIKE '%$todays_date%'
	   		AND 
	   			`trans_store`='".$_SESSION['gc_store']."'
	   		AND 
	   			`trans_cashier`='".$_SESSION['gc_id']."'
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
				AND
				`transaction_stores`.`trans_cashier`='".$_SESSION['gc_id']."'
	   	");

	   	$row_sales = $query_sales->fetch_object();
		$this->Ln(5);
		$this->SetFont("Helvetica", "B", 6);
		$this->SetTextColor(28, 28, 28);
		$this->Cell(100);
		$this->Cell(10, 4, 'Total Sales: ', 0, 0, "R");
		$this->Cell(15, 4,number_format($row_sales->storesales,2), 0, 0, "L");
		$this->Ln(3);
		$this->Cell(100);
		$this->Cell(10, 4, 'Transaction Count: ', 0, 0, "R");		
		$this->Cell(10, 4,$n, 0, 0, "L");
		$this->Ln(3);
		$this->Cell(100);
		$this->Cell(10, 4, 'GC Refund: ', 0, 0, "R");
		$this->Cell(10, 4,number_format($returnedTotal,2), 0, 0, "L");
		$this->Ln(3);
		$this->Cell(100);
		$this->Cell(10, 4, 'Total Credit: ', 0, 0, "R");
		$this->Cell(10, 4,number_format($salescredit), 0, 0, "L");	
		$this->Ln(3);
		$this->Cell(100);
		$this->Cell(10, 4, 'Total Cash: ', 0, 0, "R");
		$this->Cell(10, 4,number_format($salescash,2), 0, 0, "L");		
		$this->Ln();	
	}

}
$supervisor = getSupervisorFullname($link);
$storename = getField($link,'store_name','stores','store_id',$_SESSION['gc_store']);
$getGCReturned = getGCReturned($link,$todays_date,$_SESSION['gc_store'],$_SESSION['gc_id']);
$returnedTotal = 0;
foreach ($getGCReturned as $denom) {
	$returnedTotal += $denom->denomination;
}
// $gcRefund = refundGC($link,$todays_date);
$salescredit = getonSales($link,$todays_date,'2');
$salescash = getonSales($link,$todays_date,'1');
$pdf = new Xreport();
$pdf->setStoreName($storename);
$pdf->setSupervisorName($supervisor);
$pdf->AliasNbPages();
$pdf->AddPage("P","Letter");
$pdf->Sales($link,$todays_date);
$pdf->returnItems($getGCReturned);
$pdf->TransactionDetails($link,$todays_date,$salescredit,$salescash,$returnedTotal);
$pdf->Output();
?>
