<?php 
	session_start();
	require_once('../config.php');
	require('../fpdf.php');
	require_once('../function.php');
	require_once('../function-cashier.php');

	if(!isset($_SESSION['gccashier_id']) || !isset($_SESSION['gc_super_id']))
	{
		exit();
	}


	class CREATEODREPORT extends FPDF
	{
		function report_header($td,$tt,$storename,$cashier,$manager)
		{
			$this->SetFont("Arial", "B", 8);
			$this->Cell(0,6,$storename,0,0,'C');
			$this->SetFont("Arial", "", 10);
			$this->Ln(3);	
			$this->SetFont("Arial", "B", 6);
			$this->Cell(0,6,'Owned & Managed by ASC',0,0,'C');
			$this->Ln(5);
			$this->Cell(0,6,'Date Print: '.$td.' - '.$tt,0,0,'L');
			$this->Ln(3);		
			$this->Cell(0,6,'Cashier: '.$cashier);
			$this->Ln(3);		
			$this->Cell(0,6,'Manager: '.$manager);
			$this->Ln(4);
			$this->SetFont("Arial", "", 5);
			$this->Cell(0,6,'----------------------------------------------------------------------------------------------------------------------------------------',0,0,'C');
			$this->Ln(4);
			$this->SetFont("Arial", "B", 9);
			$this->Cell(0,6,'GC End of Day Report',0,0,'C');
			$this->Ln(4);
			$this->SetFont("Arial", "", 5);
			$this->Cell(0,6,'----------------------------------------------------------------------------------------------------------------------------------------',0,0,'C');
		}

		function transactions($grossSales,$docdisc,$linedisc,$ardisc,$gcrefund,$payingcustomers,$numTransactions,$itemsold,$voidItems,$first,$last)
		{
			$this->Ln(5);
			$this->SetFont("Arial", "B", 6);
			$this->Cell(42,4,'Gross Sales',0,0,'L');
			$this->Cell(40,4,number_format($grossSales,2),0,0,'R');
			$this->Ln(3);
			$this->Cell(42,4,'Discount',0,0,'L');
			$this->Ln(3);
			$this->Cell(4,4,'',0,0,'L');
			$this->Cell(30,4,'Line Discount',0,0,'L');
			$this->Cell(12,4,$linedisc->cnt,0,0,'R');
			$this->Cell(36,4,$linedisc->totallinedisc,0,0,'R');
			$this->Ln(3);
			$this->Cell(4,4,'',0,0,'L');
			$this->Cell(30,4,'Document Discount',0,0,'L');
			$this->Cell(12,4,$docdisc->cnt,0,0,'R');
			$this->Cell(36,4,$docdisc->totaldocdisc,0,0,'R');
			$this->Ln(3);
			$this->Cell(4,4,'',0,0,'L');
			$this->Cell(30,4,'Internal Customer Discount',0,0,'L');
			$this->Cell(12,4,$ardisc->cnt,0,0,'R');
			$this->Cell(36,4,$ardisc->totalardisc,0,0,'R');
			$this->Ln(3);
			$this->Cell(42,4,'Total Discount',0,0,'L');
			$this->Cell(40,4,$linedisc->totallinedisc + $docdisc->totaldocdisc + $ardisc->totalardisc,0,0,'R');
			$this->Ln(3);

			$refcount = 0;
			$reftotal = 0;
			if(count($gcrefund)>0)
			{
				$this->Cell(30,4,'GC Refund',0,0,'L');
				$this->Ln(3);
				foreach ($gcrefund as $ref) 
				{
					$refcount += $ref->cnt;
					$reftotal += $ref->totalrefund;
					$this->Cell(4,4,'',0,0,'L');
					$this->Cell(30,4,number_format($ref->denomination,2),0,0,'L');
					$this->Cell(12,4,$ref->cnt,0,0,'R');
					$this->Cell(36,4,number_format($ref->totalrefund,2),0,0,'R');
					$this->Ln(3);					
				}	
				$this->Cell(30,4,'Total',0,0,'L');	
				$this->Cell(16,4,$refcount,0,0,'R');
				$this->Cell(36,4,number_format($reftotal,2),0,0,'R');
				$this->Ln(4);
			}
			else 
			{
				$this->Cell(30,4,'GC Refund',0,0,'L');
				$this->Cell(16,4,'0',0,0,'R');
				$this->Cell(36,4,'0.00',0,0,'R');
				$this->Ln(4);							
			}

			$netsales = 0;
			$netsales = $grossSales - ($reftotal+$linedisc->totallinedisc + $docdisc->totaldocdisc + $ardisc->totalardisc);
			$this->SetFont("Arial", "", 5);
			$this->Cell(0,6,'----------------------------------------------------------------------------------------------------------------------------------------',0,0,'C');
			$this->Ln(3);
			$this->SetFont("Arial", "B", 6);
			$this->Cell(42,6,'Total Net Sales',0,0,'L');
			$this->Cell(40,6,number_format($netsales,2),0,0,'R');
			$this->Ln(3);
			$this->SetFont("Arial", "", 5);
			$this->Cell(0,6,'----------------------------------------------------------------------------------------------------------------------------------------',0,0,'C');

			$this->Ln(5);
			$this->SetFont("Arial", "B", 6);
			$this->Cell(46,4,'No of Paying Customers',0,0,'L');
			$this->Cell(36,4,$payingcustomers,0,0,'R');
			$this->Ln(3);
			$this->Cell(46,4,'No of Transactions',0,0,'L');
			$this->Cell(36,4,$numTransactions,0,0,'R');
			$this->Ln(3);
			$this->Cell(46,4,'Items Sold',0,0,'L');
			$this->Cell(36,4,$itemsold,0,0,'R');
			$this->Ln(3);
			$this->Cell(46,4,'Total No. of Refund',0,0,'L');
			$this->Cell(36,4,$refcount,0,0,'R');
			$this->Ln(3);
			$this->Cell(46,4,'Total Refund Amount',0,0,'L');
			$this->Cell(36,4,number_format($reftotal,2),0,0,'R');
			$this->Ln(3);
			$this->Cell(46,4,'Total Number of Voided',0,0,'L');
			$this->Cell(36,4,$voidItems->cnt,0,0,'R');
			$this->Ln(3);
			$this->Cell(46,4,'Total Voided Amount',0,0,'L');
			$this->Cell(36,4,number_format($voidItems->total,2),0,0,'R');
			$this->Ln(3);
			$this->Cell(46,4,'Beginning Txnno',0,0,'L');
			$this->Cell(36,4,$first,0,0,'R');
			$this->Ln(3);
			$this->Cell(46,4,'Ending Txnno',0,0,'L');
			$this->Cell(36,4,$last,0,0,'R');
			$this->Ln(3);
		}
	}

	$pdf = new CREATEODREPORT();
	$pdf->AliasNbPages();
	$pdf->setMargins(4, 4, 4);
	$pdf->AddPage("P",array(90,164));

	$storename = strtoupper(getStoreName($link,$_SESSION['gccashier_store']));
	$cashier = getFullnameStoreStaff($link, $_SESSION['gccashier_id']);
	$manager = getFullnameStoreStaff($link, $_SESSION['gc_super_id']);

	$grossSales = getGrossSalesEOD($link,$_SESSION['gccashier_store']);

	$docdisc = getDocDiscountEOD($link,$_SESSION['gccashier_store']);
	$linedisc = getLineDiscountEOD($link,$_SESSION['gccashier_store']);
	$ardisc = getARDiscountEOD($link,$_SESSION['gccashier_store']);

	$gcrefund = getGCRefundByDateAndCashierEOD($link,$_SESSION['gccashier_store'],5);

	$payingcustomers = numPayingCustomersEOD($link,$_SESSION['gccashier_store']);
	$numTransactions = numTransactionsEOD($link,$_SESSION['gccashier_store']);

	$voidItems = voidItemsEOD($link,$_SESSION['gccashier_store']);

	$itemsold = itemsSoldEOD($link,$_SESSION['gccashier_store']);

	$first = getStartEndTransactionsEOD($link,$_SESSION['gccashier_store'],'ASC');
	$last = getStartEndTransactionsEOD($link,$_SESSION['gccashier_store'],'DESC');

	$first = !is_null($first) ? $first->trans_number : 0;
	$last = !is_null($last) ? $last->trans_number : 0;
 
	// echo _dateFromSql($todays_date);

	$pdf->report_header(_dateFromSql($todays_date),_timeFormat($todays_time),$storename,$cashier,$manager);
	$pdf->transactions($grossSales,$docdisc,$linedisc,$ardisc,$gcrefund,$payingcustomers,$numTransactions,$itemsold,$voidItems,$first,$last);

	if(count(getTransactionsEOD($link,$_SESSION['gccashier_store']))> 0)
	{
		$link->autocommit(FALSE);
		$query_ins = $link->query(
			"INSERT INTO 
				`transaction_endofday`
			(
			    `eod_store`, 
			    `eod_supervisor_id`, 
			    `eod_transtart`, 
			    `eod_transend`, 
			    `eod_datetime`
			) 
			VALUES 
			(
			    '".$_SESSION['gccashier_store']."',
			    '".$_SESSION['gc_super_id']."',
			    '$first',
			    '$last',
			    NOW()
			)
		");

		$lastid = $link->insert_id;

		if($query_ins)
		{
			$query_upVoid = $link->query(
				"UPDATE 
					`store_void_items` 
				SET 
					`svi_eod`='$lastid' 
				WHERE 
					`svi_store`='".$_SESSION['gccashier_store']."'
				AND
					DATE(`svi_datetime`)= CURDATE()
				AND 
					`svi_eod`='0'
			");

			if($query_upVoid)
			{
				$query_update = $link->query(
					"UPDATE 
						`transaction_stores` 
					SET 
						`trans_yreport`='$lastid' 
					WHERE 
						`transaction_stores`.`trans_store`='".$_SESSION['gccashier_store']."'
					AND
						DATE(`transaction_stores`.`trans_datetime`) = CURDATE()
					AND
						`trans_yreport`='0'
					AND
						`trans_eos`!=''
				");

				if($query_update)
				{
					$link->commit();
					$pdf->Output();
					//$pdf->Output('../reports/pos/eod_report'.$_SESSION['gccashier_store'].'.pdf','F');
				}
				else 
				{
					echo $link->error;
				}
			} 
			else 
			{
				echo $link->error;
			}
			
			
		}
		else 
		{
			echo $link->error;
		}

	}
	else 
	{
		$pdf->Output('../reports/pos/eod_report'.$_SESSION['gccashier_store'].'.pdf','F');
	}

	// $pdf->Output('../reports/pos/eos_report'.$_SESSION['gccashier_store'].'.pdf','F');

?>

<script>
//window.location = "<?php echo 'index.php?eodreport='.$_SESSION['gccashier_store']; ?>";
</script>