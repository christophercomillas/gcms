<?php
	session_start();
	require_once('../config.php');
	require('../fpdf.php');
	require_once('../function.php');
 	require_once('../function-cashier.php');
	class REPORTS extends FPDF
	{
		function Footer()
		{
			$this->SetY(-15);
			$this->SetTextColor(74, 74, 74);
			$this->SetFont("Arial", "", 7);
			// $this->SetDrawColor(74, 74, 74);
			// $this->SetLineWidth(0.2);
			// $this->Line(10, 265, 205, 265);
			$this->Cell(0, 10, "Page ".$this->PageNo()." - {nb}", 0, 0, "C");
			$this->Cell(0, 10, 'GC Sales Report ', 0, 0, "R");
		}

		function docHeaderStoreSalesReport($storename)
		{
			$this->SetFont("Helvetica", "B", 12);
			$this->SetTextColor(28, 28, 28);
			$this->Cell(0, 8, ucwords($storename), 0, 0, "C");
			$this->Ln(6);
			$this->Cell(0, 8, 'ALTURAS GROUP OF COMPANIES', 0, 0, "C");
			$this->Ln(1);
			$this->SetFont("times", "B",11);
			$this->SetTextColor(28, 28, 28);
			$this->Ln();
			$this->Cell(0, 1, 'GC Sales Report', 0, 0, "C");	
			$this->Ln(6);
		}

		function subheaderStoreSalesReport($tdate,$date)
		{
			$this->Ln();
			$this->SetFont("Arial", "B", 10);
			$this->Cell(30,5,'Transaction Date: ',0,0,'R');
			$this->SetFont("Arial", '', 10);
			$this->Cell(102,5,$date,0,0,'L');
			$this->SetFont("Arial", "B", 10);
			$this->Cell(40,5,'Report Created:',0,0,'R');
			$this->SetFont("Arial", "", 10);
			$this->Cell(50,5,$tdate,0,0,'L');
			$this->Ln(2);
		}

		function gcTransactions($transdate,$d1,$d2,$storeid,$gcsales,$reval,$refund,$link)
		{
			$flag = 0;
			// check if has transactions
			$select = 'transaction_stores.trans_number,
				ledger_store.sledger_desc,
				transaction_stores.trans_sid,
				transaction_stores.trans_type,
				transaction_stores.trans_datetime';

			$where = "transaction_stores.trans_store='".$storeid."'";

			$join = 'INNER JOIN 
					ledger_store 
				ON 
					ledger_store.sledger_ref = transaction_stores.trans_sid';

			if($gcsales=='true')
			{
				$flag = 1;
				$where.=" AND ( transaction_stores.trans_type='1'
				OR transaction_stores.trans_type='2'
				OR transaction_stores.trans_type='3'";
			}

			if($reval=='true')
			{
				if($flag)
				{
					$where.=" OR transaction_stores.trans_type='6'";
				}
				else
				{
					$where.=" AND ( transaction_stores.trans_type='6'";
				}
			}

			if($refund=='true')
			{
				if($flag)
				{
					$where.=" OR transaction_stores.trans_type='5'";
				}
				else
				{
					$where.=" AND ( transaction_stores.trans_type='5'";
				}
			}

			$where.=") AND ";

			if($transdate=='today')
			{
				$where.="DATE(transaction_stores.trans_datetime) = CURDATE()";
			}
			elseif($transdate=='yesterday')
			{
				$where.="DATE(transaction_stores.trans_datetime) = CURDATE() - INTERVAL 1 DAY";
			}
			elseif ($transdate=='thisweek') 
			{
				$where.="WEEKOFYEAR(transaction_stores.trans_datetime) = WEEKOFYEAR(NOW())";
			}
			elseif ($transdate=='curmonth')
			{
				$where.="MONTH(transaction_stores.trans_datetime) = MONTH(NOW()) AND YEAR(transaction_stores.trans_datetime) = YEAR(NOW())";
			}
			elseif ($transdate=='range') 
			{
				$where.="DATE(transaction_stores.trans_datetime) >= '"._dateFormatoSql($d1)."'
				AND  DATE(transaction_stores.trans_datetime) <= '"._dateFormatoSql($d2)."'";
			}
			else 
			{
				$where.=" 1";
			}

			$limit = 'GROUP BY transaction_stores.trans_sid ORDER BY transaction_stores.trans_sid ASC';
			$gc = getAllData($link,'transaction_stores',$select,$where,$join,$limit); 
			
			if(count($gc)>0)
			{
				if($gcsales=='true')
				{

					// if store has cash sales
					$this->Ln(8);
					$this->Cell(14,5,'',0,0,'L');
					$this->Cell(166,5,'Cash Sales',1,0,'L');
					$this->Ln();
					$this->SetFont("Arial", "B", 10);
					$this->Cell(14,5,'',0,0,'L');
					$this->Cell(40,5,'GC Denomination',1,0,'L');
					$this->Cell(30,5,'GC Sold',1,0,'L');	
					$this->Cell(30,5,'Sub Total',1,0,'L');	
					$this->Cell(30,5,'Line Disc.',1,0,'L');
					$this->Cell(36,5,'Net',1,0,'L');
					$gdenom = getAllDenomination($link);
					$gtot = 0;
					$tot = 0;
					foreach ($gdenom as $gd) 
					{
						$this->Ln();
						$this->SetFont("Arial", "", 10);
						$this->Cell(14,5,'',0,0,'L');
						$this->Cell(40,5,number_format($gd->denomination,2),1,0,'R');
						$gcdata = $this->numberofGCSold($transdate,$d1,$d2,$storeid,$gd->denom_id,$link,1);
						$this->Cell(30,5,$gcdata->cnt,1,0,'R');	
						$this->Cell(30,5,number_format($gcdata->densum,2),1,0,'R');								
						$linediscdata = $this->lineDiscount($transdate,$d1,$d2,$storeid,$gd->denom_id,$link,1);
						$this->Cell(30,5,'- '.$linediscdata->linedisum,1,0,'R');
						$ntotal = $gcdata->densum - $linediscdata->linedisum;
						$tot += $ntotal;
						$this->Cell(36,5,number_format($ntotal,2),1,0,'R');
					}
					$this->Ln();
					$this->SetFont("Arial", "B", 10);
					$this->Cell(14,5,'',0,0,'L');
					$this->Cell(130,5,'Total Net: ',1,0,'R');
					$gtot += $tot;
					$this->Cell(36,5,number_format($tot,2),1,0,'R');

					$this->Ln(10);
					$this->Cell(14,5,'',0,0,'L');
					$this->Cell(166,5,'Card Sales',1,0,'L');
					$this->Ln();
					$this->SetFont("Arial", "B", 10);
					$this->Cell(14,5,'',0,0,'L');
					$this->Cell(40,5,'GC Denomination',1,0,'L');
					$this->Cell(30,5,'GC Sold',1,0,'L');	
					$this->Cell(30,5,'Sub Total',1,0,'L');	
					$this->Cell(30,5,'Line Disc.',1,0,'L');
					$this->Cell(36,5,'Net',1,0,'L');
					$tot = 0;
					foreach ($gdenom as $gd) 
					{
						$this->Ln();
						$this->SetFont("Arial", "", 10);
						$this->Cell(14,5,'',0,0,'L');
						$this->Cell(40,5,number_format($gd->denomination,2),1,0,'R');
						$gcdata = $this->numberofGCSold($transdate,$d1,$d2,$storeid,$gd->denom_id,$link,2);
						$this->Cell(30,5,$gcdata->cnt,1,0,'R');	
						$this->Cell(30,5,number_format($gcdata->densum,2),1,0,'R');								
						$linediscdata = $this->lineDiscount($transdate,$d1,$d2,$storeid,$gd->denom_id,$link,2);
						$this->Cell(30,5,'- '.$linediscdata->linedisum,1,0,'R');
						$ntotal = $gcdata->densum - $linediscdata->linedisum;
						$tot += $ntotal;
						$this->Cell(36,5,number_format($ntotal,2),1,0,'R');							
					}
					$this->Ln();
					$this->SetFont("Arial", "B", 10);
					$this->Cell(14,5,'',0,0,'L');
					$this->Cell(130,5,'Total Net: ',1,0,'R');
					$gtot += $tot;

					$this->Cell(36,5,number_format($tot,2),1,0,'R');

					$this->Ln(10);
					$this->Cell(14,5,'',0,0,'L');
					$this->Cell(166,5,'AR',1,0,'L');
					$this->Ln();
					$this->SetFont("Arial", "B", 10);
					$this->Cell(14,5,'',0,0,'L');
					$this->Cell(40,5,'GC Denomination',1,0,'L');
					$this->Cell(30,5,'GC Sold',1,0,'L');	
					$this->Cell(30,5,'Sub Total',1,0,'L');	
					$this->Cell(30,5,'Line Disc.',1,0,'L');
					$this->Cell(36,5,'Net',1,0,'L');
					$tot = 0;
					foreach ($gdenom as $gd) 
					{
						$this->Ln();
						$this->SetFont("Arial", "", 10);
						$this->Cell(14,5,'',0,0,'L');
						$this->Cell(40,5,number_format($gd->denomination,2),1,0,'R');
						$gcdata = $this->numberofGCSold($transdate,$d1,$d2,$storeid,$gd->denom_id,$link,3);
						$this->Cell(30,5,$gcdata->cnt,1,0,'R');	
						$this->Cell(30,5,number_format($gcdata->densum,2),1,0,'R');								
						$linediscdata = $this->lineDiscount($transdate,$d1,$d2,$storeid,$gd->denom_id,$link,3);
						$this->Cell(30,5,'- '.$linediscdata->linedisum,1,0,'R');
						$ntotal = $gcdata->densum - $linediscdata->linedisum;
						$tot += $ntotal;
						$this->Cell(36,5,number_format($ntotal,2),1,0,'R');							
					}
					$this->Ln();
					$this->SetFont("Arial", "B", 10);
					$this->Cell(14,5,'',0,0,'L');
					$this->Cell(130,5,'Customer Total Discount: ',1,0,'R');
					$cusdisc = $this->customerDiscount($transdate,$d1,$d2,$storeid,$link);
					$this->Cell(36,5,'- '.number_format($cusdisc->cusdisc,2),1,0,'R');
					$this->Ln();
					$this->SetFont("Arial", "B", 10);
					$this->Cell(14,5,'',0,0,'L');
					$this->Cell(130,5,'Total Net: ',1,0,'R');
					$tot = $tot - $cusdisc->cusdisc;
					$gtot += $tot;
					$this->Cell(36,5,number_format($tot,2),1,0,'R');

					$this->Ln(10);
					$this->SetFont("Arial", "B", 10);
					$this->Cell(14,5,'',0,0,'L');
					$this->Cell(130,5,'Total Transaction Discount: ',1,0,'R');
					$trdisc = $this->getTransactionDiscount($transdate,$d1,$d2,$storeid,$link);
					$this->Cell(36,5,number_format($trdisc,2),1,0,'R');
					$this->Ln();
					$this->SetFont("Arial", "B", 10);
					$this->Cell(14,5,'',0,0,'L');
					$this->Cell(130,5,'Grand Total Net: ',1,0,'R');
					$gtot = $gtot - $trdisc;
					$this->Cell(36,5,number_format($gtot,2),1,0,'R');
				}

				if($refund=='true')
				{
					$totalref = 0;
					$refund = $this->getRefundTransaction($transdate,$d1,$d2,$storeid,$link);
					$this->Ln(8);
					$this->Cell(14,5,'',0,0,'L');
					$this->Cell(166,5,'GC Refund',1,0,'L');
					if($refund->den > 0)
					{
						$totalref = $refund->den;
						$totalref = $totalref - $refund->lindisc;
						$totalref = $totalref - $refund->trdisc;

						$this->Ln();
						$this->SetFont("Arial", "", 10);
						$this->Cell(14,5,'',0,0,'L');
						$this->Cell(130,5,'Refund: ',1,0,'R');
						$this->Cell(36,5,number_format($refund->den,2),1,0,'R');
						$this->Ln();
						$this->Cell(14,5,'',0,0,'L');
						$this->Cell(130,5,'Total Line Discount: ',1,0,'R');
						$this->Cell(36,5,'- '.number_format($refund->lindisc,2),1,0,'R');
						$this->Ln();
						$this->Cell(14,5,'',0,0,'L');
						$this->Cell(130,5,'Total Transaction Discount: ',1,0,'R');
						$this->Cell(36,5,'- '.number_format($refund->trdisc,2),1,0,'R');
						$this->Ln();
						$this->Cell(14,5,'',0,0,'L');
						$refscharge = $this->getTotalServiceCharge($transdate,$d1,$d2,$storeid,$link);
						$totalref = $totalref - $refscharge->scharge;
						$this->Cell(130,5,'Total Service Charge: ',1,0,'R');
						$this->Cell(36,5,'- '.number_format($refscharge->scharge,2),1,0,'R');
						$this->Ln();
						$this->SetFont("Arial", "B", 10);
						$this->Cell(14,5,'',0,0,'L');
						$this->Cell(130,5,'Total Refund: ',1,0,'R');
						$this->Cell(36,5,number_format($totalref,2),1,0,'R');

					}
					else 
					{
						$this->Ln();
						$this->Cell(14,5,'',0,0,'L');
						$this->Cell(166,5,'No Refund Transaction',1,0,'L');						
					}
				}

				if($reval=='true')
				{
					// check if refund transaction exist
					$this->Ln(8);
					$this->Cell(14,5,'',0,0,'L');
					$this->Cell(166,5,'GC Revalidation',1,0,'L');
					$gcreval = $this->getGCRevalidation($transdate,$d1,$d2,$storeid,$link);
					if($gcreval->reval > 0 )
					{
						$this->Ln();
						$this->SetFont("Arial", "B", 10);
						$this->Cell(14,5,'',0,0,'L');
						$this->Cell(130,5,'Total Revalidation Payment: ',1,0,'R');
						$this->Cell(36,5,number_format($gcreval->reval,2),1,0,'R');
					}
					else 
					{
						$this->Ln();
						$this->Cell(14,5,'',0,0,'L');
						$this->Cell(166,5,'No Revalidation Transaction',1,0,'L');
					}

				}

			}
			else 
			{
				$this->Ln();
				$this->SetFont("Arial", "B", 10);
				$this->Cell(30,5,'No Transaction',0,0,'R');				
			}
		}

		function getGCRevalidation($transdate,$d1,$d2,$storeid,$link)
		{
			$where = getWhereTransaction($transdate,$d1,$d2);

			$query = $link->query(
				"SELECT
					IFNULL(SUM(transaction_payment.payment_amountdue),0) as reval
				FROM 
					transaction_payment
				INNER JOIN
					transaction_stores
				ON
					transaction_stores.trans_sid = transaction_payment.payment_trans_num
				WHERE 
					transaction_stores.trans_store='$storeid'
				AND
					transaction_stores.trans_type='6'
				$where
			");

			if($query)
			{
				return $query->fetch_object();
			}
			else 
			{
				return $link->error;
			}


		}

		function getTotalServiceCharge($transdate,$d1,$d2,$storeid,$link)
		{
			$where = getWhereTransaction($transdate,$d1,$d2);

			$query = $link->query(
				"SELECT
					IFNULL(SUM(transaction_refund_details.trefundd_servicecharge),0) as scharge
				FROM 
					transaction_refund_details 
				INNER JOIN
					transaction_stores
				ON
					transaction_stores.trans_sid = transaction_refund_details.trefundd_trstoresid
				WHERE 
					transaction_stores.trans_store='$storeid'
				$where
			");

			if($query)
			{
				return $query->fetch_object();
			}
			else 
			{
				return $link->error;
			}

		}

		function getRefundTransaction($transdate,$d1,$d2,$storeid,$link)
		{

			$where = getWhereTransaction($transdate,$d1,$d2);

			$query = $link->query(
				"SELECT
					IFNULL(SUM(denomination.denomination),0) as den,
					IFNULL(SUM(transaction_refund.refund_linedisc),0) as lindisc,
					IFNULL(SUM(transaction_refund.refund_sdisc),0) as trdisc
				FROM 
					transaction_refund 
				INNER JOIN
					denomination
				ON
					denomination.denom_id = transaction_refund.refund_denom
				INNER JOIN
					transaction_stores
				ON
					transaction_stores.trans_sid = transaction_refund.refund_trans_id
				WHERE 
					transaction_stores.trans_store='$storeid'
				$where
			");

			if($query)
			{
				return $query->fetch_object();
			}
			else 
			{
				return $link->error;
			}

		}

		function numberofGCSold($transdate,$d1,$d2,$storeid,$denomid,$link,$trtype)
		{

			$where = getWhereTransaction($transdate,$d1,$d2);

			$query = $link->query(
				"SELECT 
					COUNT(sales_transaction_id) as cnt,
					IFNULL(SUM(denomination.denomination),0) as densum
				FROM 
					transaction_sales 
				INNER JOIN
					transaction_stores
				ON
					transaction_stores.trans_sid = transaction_sales.sales_transaction_id
				INNER JOIN
					denomination
				ON
					denomination.denom_id = transaction_sales.sales_denomination
				WHERE
					transaction_sales.sales_denomination='$denomid'
				AND
					transaction_stores.trans_store='$storeid'
				AND 
					transaction_stores.trans_type='$trtype'
				$where	
			");

			if($query)
			{
				return $query->fetch_object();
			}
			else 
			{
				return $link->error;
			}
		}

		function lineDiscount($transdate,$d1,$d2,$storeid,$denomid,$link,$trtype)
		{
			
			$where = getWhereTransaction($transdate,$d1,$d2);

			$query = $link->query(
				"SELECT 
					IFNULL(SUM(trlinedis_discamt),0) as linedisum 
				FROM 
					transaction_linediscount 
				INNER JOIN 
					gc 
				ON 
					gc.barcode_no = transaction_linediscount.trlinedis_barcode 
				INNER JOIN 
					denomination 
				ON 
					denomination.denom_id = gc.denom_id 
				INNER JOIN 
					transaction_stores 
				ON 
					transaction_stores.trans_sid = transaction_linediscount.trlinedis_sid 
				WHERE 
					denomination.denom_id='$denomid' 
				AND 
					transaction_stores.trans_store='$storeid' 
				AND 
					transaction_stores.trans_type='$trtype'
				$where
			");

			if($query)
			{
				return $query->fetch_object();
			}
			else 
			{
				return $link->error;
			}
		}

		function customerDiscount($transdate,$d1,$d2,$storeid,$link)
		{
			$where = getWhereTransaction($transdate,$d1,$d2);

			$query = $link->query(
				"SELECT 
					IFNULL(SUM(transaction_payment.payment_internal_discount),0) as cusdisc
				FROM 
					transaction_stores 
				INNER JOIN
					transaction_payment
				ON
					transaction_payment.payment_trans_num = transaction_stores.trans_sid					
				WHERE 
					transaction_stores.trans_type='3'
				AND
					transaction_stores.trans_store='$storeid'
				$where
			");

			if($query)
			{	
				return $query->fetch_object();
			}
			else 
			{
				return $link->error;
			}
		}

		function getTransactionDiscount($transdate,$d1,$d2,$storeid,$link)
		{
			$where = getWhereTransaction($transdate,$d1,$d2);

			$query = $link->query(
				"SELECT 
					IFNULL(SUM(transaction_docdiscount.trdocdisc_amnt),0) as docdisc
				FROM 	
					transaction_stores
				INNER JOIN
					transaction_docdiscount
				ON
					transaction_docdiscount.trdocdisc_trid = transaction_stores.trans_sid
				WHERE 
					transaction_stores.trans_store='$storeid'
				$where
			");

			if($query)
			{
				$row = $query->fetch_object();
				return $row->docdisc;
			}
			else 
			{
				return $link->error;
			}
		}
	}

