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


	class CREATEOSREPORT extends FPDF
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
			$this->Cell(0,6,'GC Cashier End of Shift Report',0,0,'C');
			$this->Ln(4);
			$this->SetFont("Arial", "", 5);
			$this->Cell(0,6,'----------------------------------------------------------------------------------------------------------------------------------------',0,0,'C');
		}

		function transactions($cash,$reval,$ccard,$ar,$docdisc,$linedisc,$ardisc,$gcrefund,$itemsold,$start,$end,$payingcustomers,$numTransactions,$voidItems)
		{
			if(is_null($cash->cash))
			{	
				$cashtotal = 0.00;
				$cashcount = 0; 
			}
			else 
			{
				$cashtotal = $cash->cash;
				$cashcount = $cash->cnt;
			}
			$this->Ln(5);
			$this->SetFont("Arial", "B", 6);
			$this->Cell(30,4,'Cash',0,0,'L');
			$this->Ln(3);
			$this->Cell(4,4,'',0,0,'L');
			$this->Cell(30,4,'GC Sales',0,0,'L');
			$this->Cell(12,4,$cashcount,0,0,'R');
			$this->Cell(36,4,number_format($cashtotal,2),0,0,'R');
			$this->Ln(3);

			if(is_null($reval->cash))
			{
				$revaltotal = 0.00;
				$revalcount = 0;
			}
			else 
			{
				$revaltotal = $reval->cash;
				$revalcount = $reval->cnt;
			}

				$this->Cell(4,4,'',0,0,'L');
				$this->Cell(30,4,'GC Revalidation Payment',0,0,'L');
				$this->Cell(12,4,$revalcount,0,0,'R');
				$this->Cell(36,4,number_format($revaltotal,2),0,0,'R');
				$this->Ln(3);
				$this->Cell(30,4,'Total',0,0,'L');
				$this->Cell(16,4,$cashcount + $revalcount,0,0,'R');
				$this->Cell(36,4,number_format($cashtotal + $revaltotal,2),0,0,'R');
				$this->Ln(4);			

			if(count($ccard) > 0)
			{
				$this->Cell(30,4,'Cards',0,0,'L');
				$this->Ln(3);
				$totalcard = 0;
				$countcard = 0;
				foreach ($ccard as $c) 
				{
					$countcard +=$c->n;
					$totalcard +=$c->amount;
					$this->Cell(4,4,'',0,0,'L');
					$this->Cell(30,4,ucwords($c->ccard_name),0,0,'L');
					$this->Cell(12,4,$c->n,0,0,'R');
					$this->Cell(36,4,number_format($c->amount,2),0,0,'R');
					$this->Ln(3);				
				}
				$this->Cell(30,4,'Total',0,0,'L');	
				$this->Cell(16,4,number_format($countcard),0,0,'R');
				$this->Cell(36,4,number_format($totalcard,2),0,0,'R');
				$this->Ln(4);
			}		
			else
			{
				$totalcard = 0.00;
				$this->Cell(30,4,'Cards',0,0,'L');
				$this->Ln(3);
				$this->Cell(30,4,'Total',0,0,'L');	
				$this->Cell(16,4,'0',0,0,'R');
				$this->Cell(36,4,'0.00',0,0,'R');
				$this->Ln(4);
			}

			$artotal = 0;
			$arcount = 0;
			if(count($ar)>0)
			{
				$groupname = array('','Head Office','Subs Admin');
				$this->Cell(30,4,'AR',0,0,'L');
				$this->Ln(3);
				foreach ($ar as $a) 
				{
					$artotal += $a->amount;
					$arcount += $a->c;
					$this->Cell(4,4,'',0,0,'L');
					$this->Cell(30,4,$groupname[$a->ci_group],0,0,'L');
					$this->Cell(12,4,$a->c,0,0,'R');
					$this->Cell(36,4,number_format($a->amount,2),0,0,'R');
					$this->Ln(3);						
				}	
				$this->Cell(30,4,'Total',0,0,'L');	
				$this->Cell(16,4,number_format($arcount),0,0,'R');
				$this->Cell(36,4,number_format($artotal,2),0,0,'R');
				$this->Ln(4);			
			}
			else 
			{
				$this->Cell(30,4,'AR',0,0,'L');
				$this->Ln(3);
				$this->Cell(30,4,'Total',0,0,'L');	
				$this->Cell(16,4,'0',0,0,'R');
				$this->Cell(36,4,'0.00',0,0,'R');
				$this->Ln(4);				
			}			

			$gross = $cashtotal + $revaltotal + $totalcard + $artotal;

			$this->SetFont("Arial", "", 5);
			$this->Cell(0,6,'----------------------------------------------------------------------------------------------------------------------------------------',0,0,'C');
			$this->Ln(3);
			$this->SetFont("Arial", "B", 6);
			$this->Cell(42,6,'Total',0,0,'L');
			$this->Cell(40,6,number_format($gross,2),0,0,'R');
			$this->Ln(3);
			$this->SetFont("Arial", "", 5);
			$this->Cell(0,6,'----------------------------------------------------------------------------------------------------------------------------------------',0,0,'C');

			$this->Ln(6);
			$hasdiscount = 0;
			$arTotal = 0.00;
			$arTotalCount = 0;
			$this->SetFont("Arial", "B", 6);
			$this->Cell(30,4,'Discount',0,0,'L');
			$this->Ln(3);

			if(count($docdisc)>0)
			{
				$hasdiscount =1;
				$this->Cell(4,4,'',0,0,'L');
				$this->Cell(30,4,'Document Discount',0,0,'L');
				$this->Cell(12,4,$docdisc->cnt,0,0,'R');
				$this->Cell(36,4,number_format($docdisc->totaldocdisc,2),0,0,'R');
				$this->Ln(3);
				$arTotal += $docdisc->totaldocdisc;
				$arTotalCount += $docdisc->cnt;
			}

			if(count($linedisc) > 0)
			{
				$hasdiscount = 1;
				$this->Cell(4,4,'',0,0,'L');
				$this->Cell(30,4,'Line Discount',0,0,'L');
				$this->Cell(12,4,$linedisc->cnt,0,0,'R');
				$this->Cell(36,4,number_format($linedisc->totallinedisc,2),0,0,'R');
				$this->Ln(3);	
				$arTotal += $linedisc->totallinedisc;
				$arTotalCount += $linedisc->cnt;
			}

			if(count($ardisc) > 0)
			{
				$hasdiscount = 1;
				$this->Cell(4,4,'',0,0,'L');
				$this->Cell(30,4,'AR Discount',0,0,'L');
				$this->Cell(12,4,$ardisc->cnt,0,0,'R');
				$this->Cell(36,4,number_format($ardisc->totalcusdisc,2),0,0,'R');
				$this->Ln(3);
				$arTotal += $ardisc->totalcusdisc;
				$arTotalCount += $ardisc->cnt;
			}

			$this->Cell(30,4,'Total',0,0,'L');	
			$this->Cell(16,4,$arTotalCount,0,0,'R');
			$this->Cell(36,4,number_format($arTotal,2),0,0,'R');
			$this->Ln(4);

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
			$this->Cell(36,4,$start->trans_number,0,0,'R');
			$this->Ln(3);
			$this->Cell(46,4,'Ending Txnno',0,0,'L');
			$this->Cell(36,4,$end->trans_number,0,0,'R');
			$this->Ln(3);
		}

	}

	$pdf = new CREATEOSREPORT();
	$pdf->AliasNbPages();
	$pdf->setMargins(4, 4, 4);
	$pdf->AddPage("P",array(90,164));

	$storename = strtoupper(getStoreName($link,$_SESSION['gccashier_store']));
	$cashier = getFullnameStoreStaff($link, $_SESSION['gccashier_id']);
	$manager = getFullnameStoreStaff($link, $_SESSION['gc_super_id']);

	$cash = getTransactionsByModeAndStoreTotalEOS($link,$_SESSION['gccashier_store'], $_SESSION['gccashier_id'],1);
	$reval = getTransactionsByModeAndStoreTotalEOS($link,$_SESSION['gccashier_store'], $_SESSION['gccashier_id'],6);
	$ccard = getTransactionsGroupByCreditCardEOS($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id'],2);
	$ar = getAREOS($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id'],3);
	
	$docdisc = getDocDiscountBYStoreAndDateEOS($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id']);
	$linedisc = totalLineDiscountEOS($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id']);
	$ardisc = customerARDiscountEOS($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id']);

	$gcrefund = getGCRefundByDateAndCashierEOS($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id'],5);

	$itemsold = itemsSoldEOS($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id']);

	$payingcustomers = numPayingCustomersEOS($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id']);
	$numTransactions = numTransactionsEOS($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id']);
	$voidItems = voidItemsEOS($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id']);
	// var_dump($voidItems);

	$start = transactionStartEnd($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id'],'ASC');
	$end = transactionStartEnd($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id'],'DESC');

	$pdf->report_header(_dateFromSql($todays_date),_timeFormat($todays_time),$storename,$cashier,$manager);
	$pdf->transactions($cash,$reval,$ccard,$ar,$docdisc,$linedisc,$ardisc,$gcrefund,$itemsold,$start,$end,$payingcustomers,$numTransactions,$voidItems);

	//insert cashier end of shift details to database
	$link->autocommit(FALSE);
	$query = $link->query(
		"INSERT INTO 
			`end_of_shift_pos_details`
		(
			`eosdatetime`, 
			`eoscashier`, 
			`eosmanager`, 
			`eosstore`, 
			`eostrans_id_start`, 
			`eostrans_id_end`
		) 
		VALUES 
		(
			NOW(),
			'".$_SESSION['gccashier_id']."',
			'".$_SESSION['gc_super_id']."',
			'".$_SESSION['gccashier_store']."',
			'$start->trans_sid',
			'$end->trans_sid'
		)
	");

	$lastid = $link->insert_id;

	if($query)
	{
		// $pdf->grossales();
		// $pdf->Output();
		$query_update = $link->query(
			"UPDATE 
				`transaction_stores` 
			SET 
				`trans_eos`='$lastid'
			WHERE 
				`trans_cashier`='".$_SESSION['gccashier_id']."'
			AND
				`trans_store` ='".$_SESSION['gccashier_store']."'
			AND
				DATE(`trans_datetime`) = CURDATE()	
		");

		if($query_update)
		{
			$link->commit();
			// $pdf->Output();
			$pdf->Output('../reports/pos/eos_report'.$_SESSION['gccashier_store'].'.pdf','F');
		}
		else 
		{
			exit();
		}
	}
	else 
	{
		exit();
	}
?>

<script>
window.location = "<?php echo 'index.php?eosreport='.$_SESSION['gccashier_store']; ?>";
</script>