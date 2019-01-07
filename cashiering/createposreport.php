<?php 
	session_start();
	require_once('../config.php');
	require('../fpdf.php');
	require_once('../function.php');
	require_once('../function-cashier.php');

	$d1 = $_GET['d1'];
	$d2 = $_GET['d2'];
	$trans = $_GET['trans'];
	if($trans > 2)
	{
		exit();
	}

	if(isset($_GET['d1']) && isset($_GET['d2']))
	{
		$d1sql = _dateFormatoSql($_GET['d1']);
		$d2sql = _dateFormatoSql($_GET['d2']);
		if(checkIsAValidDate($d1sql) && checkIsAValidDate($d2sql))
		{
			if(strtotime($d2sql) < strtotime($d1sql))
			{	
				exit();
			}
		}
		else 
		{
			exit();
		}

		if(!isset($_SESSION['gccashier_store']) || !isset($_SESSION['gccashier_id'])|| !isset($_SESSION['gc_super']))
		{
			exit();
		}
	}
	else 
	{
		exit();
	}

	class CREATEPOSREPORT extends FPDF
	{
		function report_header($td,$tt,$storename,$cashier,$manager,$d1,$d2,$trans)
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
			if($trans==1)
				$txt = 'Cashier:'.$cashier;
			else 
				$txt = 'All Transactions';		
			$this->Cell(46,6,$txt,0,0,'L');
			$this->Cell(0,6,'From '.$d1.' to '.$d2,0,0,'L');
			$this->Ln(3);		
			$this->Cell(0,6,'Manager: '.$manager,0,0,'L');
			$this->Ln(4);
			$this->SetFont("Arial", "", 5);
			$this->Cell(0,6,'----------------------------------------------------------------------------------------------------------------------------------------',0,0,'C');
			$this->Ln(4);
			$this->SetFont("Arial", "B", 9);
			$this->Cell(0,6,'GC POS Report',0,0,'C');
			$this->Ln(4);
			$this->SetFont("Arial", "", 5);
			$this->Cell(0,6,'----------------------------------------------------------------------------------------------------------------------------------------',0,0,'C');
		}

		function transactions($cash,$reval,$ccard,$ar,$docdisc,$linedisc,$ardisc,$gcrefund,$itemsold,$start,$end,$payingcustomers,$numTransactions,$voidItems)
		{
			$this->Ln(5);
			$this->SetFont("Arial", "B", 6);
			$this->Cell(30,4,'Cash',0,0,'L');
			$this->Ln(3);
			$this->Cell(4,4,'',0,0,'L');
			$this->Cell(30,4,'GC Sales',0,0,'L');
			$this->Cell(12,4,$cash->cnt,0,0,'R');
			$this->Cell(36,4,number_format($cash->cash,2),0,0,'R');
			$this->Ln(3);
			$this->Cell(4,4,'',0,0,'L');
			$this->Cell(30,4,'GC Revalidation Payment',0,0,'L');
			$this->Cell(12,4,$reval->cnt,0,0,'R');
			$this->Cell(36,4,number_format($reval->cash,2),0,0,'R');
			$this->Ln(3);
			$this->Cell(30,4,'Total',0,0,'L');
			$this->Cell(16,4,$cash->cnt + $reval->cnt,0,0,'R');
			$this->Cell(36,4,number_format($cash->cash + $reval->cash,2),0,0,'R');
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

			$gross = $cash->cash + $reval->cash + $totalcard + $artotal;

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
			$reftotal = 0.00;
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
				$this->Cell(16,4,$refcount,0,0,'R');
				$this->Cell(36,4,$reftotal,0,0,'R');
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
			$this->Cell(36,4,'xxxx',0,0,'R');
		}
	}

	$pdf = new CREATEPOSREPORT();
	$pdf->AliasNbPages();
	$pdf->setMargins(4, 4, 4);
	$pdf->AddPage("P",array(90,165));

	$d1 = _dateFormatoSql($d1);
	$d2 = _dateFormatoSql($d2);

	$storename = strtoupper(getStoreName($link,$_SESSION['gccashier_store']));
	$cashier = getFullnameStoreStaff($link, $_SESSION['gccashier_id']);
	$manager = getFullnameStoreStaff($link, $_SESSION['gc_super_id']);

	$d1t = $d1.' 01:00:00';
	$d2t = $d2.' 24:00:00';

	$cash = getTransactionsByModeAndStoreTotal($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id'],1,$d1t,$d2t,$trans);
	$reval = getTransactionsByModeAndStoreTotal($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id'],6,$d1t,$d2t,$trans);
	$ccard = getTransactionsGroupByCreditCard($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id'],2,$d1t,$d2t,$trans);
	$ar = getAR($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id'],3,$d1t,$d2t,$trans);

	$docdisc = getDocDiscountBYStoreAndDate($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id'],$d1t,$d2t,$trans);
	$linedisc = totalLineDiscount($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id'],$d1t,$d2t,$trans);
	$ardisc = customerARDiscount($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id'],$d1t,$d2t,$trans);

	$gcrefund = getGCRefundByDateAndCashier($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id'],5,$d1t,$d2t,$trans);

	$itemsold = itemsSoldPOS($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id'],$d1t,$d2t,$trans);

	$payingcustomers = numPayingCustomersPOS($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id'],$d1t,$d2t,$trans);
	$numTransactions = numTransactionsPOS($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id'],$d1t,$d2t,$trans);
	$voidItems = voidItemsPOS($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id'],$d1t,$d2t,$trans);
	$start = transactionStartEndPOS($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id'],$d1t,$d2t,$trans,'ASC');
	$end = transactionStartEndPOS($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id'],$d1t,$d2t,$trans,'DESC');

	$pdf->report_header(_dateFromSql($todays_date),_timeFormat($todays_time),$storename,$cashier,$manager,$d1,$d2,$trans);
	$pdf->transactions($cash,$reval,$ccard,$ar,$docdisc,$linedisc,$ardisc,$gcrefund,$itemsold,$start,$end,$payingcustomers,$numTransactions,$voidItems);

	// var_dump($start);

	// $pdf->Output();
	$pdf->Output('../reports/pos/pos_report.pdf','F');


?>

<script>
window.location = "<?php echo 'index.php?posreport='.$_SESSION['gccashier_store']; ?>";
</script>