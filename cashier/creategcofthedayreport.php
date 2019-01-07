<?php 
	require('reports.php');

	//var_dump($_SESSION);

	if(!isset($_SESSION['gccashier_id']))
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

	$pdf->setReportType(3);
	$pdf->datePrint($todays_date);
	$storename = getStoreName($link,$_SESSION['gccashier_store']);
	$pdf->docHeaderGCoftheday($storename);
	$cashier = getFullnameStoreStaff($link, $_SESSION['gccashier_id']);
	$pdf->subheaderGCofTheDay($cashier,$todays_date);

	$hasTrans = false;
	$hasSales = false;
	$hasReval = false;
	$hasRefund = false;

	$query_sales = $link->query(
		"SELECT 
		    transaction_stores.trans_cashier,
		    CONCAT(store_staff.ss_firstname,' ',store_staff.ss_lastname) as cashier
		FROM 
		    transaction_stores 
		INNER JOIN
		    store_staff
		ON
		    store_staff.ss_id = transaction_stores.trans_cashier
		WHERE 
		    transaction_stores.trans_store = '".$_SESSION['gccashier_store']."'
		AND
			DATE(transaction_stores.trans_datetime) = CURDATE()
		AND
		(
		    transaction_stores.trans_type='1'
		OR
		    transaction_stores.trans_type='2'
		OR
		    transaction_stores.trans_type='3'
		)
		GROUP BY
		    transaction_stores.trans_cashier
		ORDER BY 
		    transaction_stores.trans_datetime='DESC'
	");

	if(!$query_sales)
	{
		echo $link->error;
		die();
	}

	if($query_sales->num_rows > 0)
	{
		$rows = [];

		$hasTrans = true;
		$hasSales = true;

		while ($row = $query_sales->fetch_object()) 
		{
			$rows[] = $row;
		}

		$pdf->itemSalesGCoftheday($link,$rows,$_SESSION['gccashier_store']);
	}

	$query_reval = $link->query(
		"SELECT 
		    transaction_stores.trans_cashier,
		    CONCAT(store_staff.ss_firstname,' ',store_staff.ss_lastname) as cashier
		FROM 
		    transaction_stores 
		INNER JOIN
		    store_staff
		ON
		    store_staff.ss_id = transaction_stores.trans_cashier
		WHERE 
		    transaction_stores.trans_store = '".$_SESSION['gccashier_store']."'
		AND
			DATE(transaction_stores.trans_datetime) = CURDATE()
		AND
		    transaction_stores.trans_type='6'
		GROUP BY
		    transaction_stores.trans_cashier
		ORDER BY 
		    transaction_stores.trans_datetime='DESC'
	");

	if(!$query_reval)
	{
		echo $link->error;
		die();
	}	

	if($query_reval->num_rows > 0)
	{
		$rows = [];

		$hasTrans = true;
		$hasReval = true;

		while ($row = $query_reval->fetch_object()) 
		{
			$rows[] = $row;
		}

		$pdf->gcRevaloftheday($link,$rows,$_SESSION['gccashier_store']);
	}

	if($hasTrans)
	{
		$pdf->gcOfTheDayFooter($link,$rows,$_SESSION['gccashier_store']);
	}


	//$pdf->Output();
	$pdf->Output('../reports/pos/gcoftheday'.$_SESSION['gccashier_store'].'.pdf','F');
?>

<script>
	window.location = "<?php echo 'index.php?gcoftheday='.$_SESSION['gccashier_store']; ?>";
</script>