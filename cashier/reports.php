<?php
	session_start();
	require_once('../config.php');
	require('../fpdf.php');
	require_once('../function.php');
 	require_once('../function-cashier.php');
	class REPORTS extends FPDF
	{

		function setReportType($rtype)
		{
			$this->reporttype = $rtype;
		}

		function setDate($daterel)
		{
			$this->datereleased = $daterel;
		}

		function datePrint($datep)
		{
			$this->dateprint = $datep;
		}


		function setRelNum($rel)
		{
			$this->relnum = $rel;
		}

		function Footer()
		{
			if($this->reporttype==1)
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
			elseif($this->reporttype==2)
			{
				$this->SetY(-15);
				$this->SetTextColor(74, 74, 74);
				$this->SetFont("Arial", "", 7);
				// $this->SetDrawColor(74, 74, 74);
				// $this->SetLineWidth(0.2);
				// $this->Line(10, 265, 205, 265);
				$this->Cell(0, 10, "Page ".$this->PageNo()." - {nb}", 0, 0, "C");
				$this->Cell(0, 10, 'Shortage / Overage of Settlement Report ', 0, 0, "R");				
			}
			elseif ($this->reporttype==3) 
			{
				$this->SetY(-15);
				$this->SetTextColor(74, 74, 74);
				$this->SetFont("Arial", "", 7);
				// $this->SetDrawColor(74, 74, 74);
				// $this->SetLineWidth(0.2);
				// $this->Line(10, 265, 205, 265);
				$this->Cell(0, 10, "Page ".$this->PageNo()." - {nb}", 0, 0, "C");
				$this->Cell(0, 10, 'Gift Check of the day '._dateFormat($this->dateprint), 0, 0, "R");	
			}
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


		function docHeaderGCoftheday($storename)
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
			$this->Cell(0, 1, 'Gift Check of the day', 0, 0, "C");	
			$this->Ln(6);			
		}

		function notransGCoftheDay()
		{

		}

		function docHeaderShortageOverageReport($storename)
		{
			$this->SetFont("Helvetica", "B", 12);
			$this->SetTextColor(28, 28, 28);
			$this->Cell(0, 8, 'ALTURAS GROUP OF COMPANIES', 0, 0, "C");
			$this->Ln(6);
			$this->Cell(0, 8,ucwords($storename), 0, 0, "C");
			$this->Ln(1);
			$this->SetFont("times", "B",11);
			$this->SetTextColor(28, 28, 28);
			$this->Ln();
			$this->Cell(0, 1, ' Shortage / Overage of Settlement Report', 0, 0, "C");	
			$this->Ln(6);			
		}

		function subheaderGCofTheDay($cashier,$date)
		{
			$this->Ln();
			$this->SetFont("Arial", "B", 10);
			$this->Cell(22,5,'',0,0,'R');
			$this->SetFont("Arial", "", 10);
			$this->Cell(102,5,'',0,0,'L');
			$this->SetFont("Arial", "B", 10);
			$this->Cell(40,5,'Date Generated:',0,0,'R');
			$this->SetFont("Arial", "", 10);
			$this->Cell(50,5,_dateFormat($date),0,0,'L');
			$this->Ln(2);
		}

		function subheaderStoreSalesReport($cashier,$date)
		{
			$this->Ln();
			$this->SetFont("Arial", "B", 10);
			$this->Cell(16,5,'Cashier: ',0,0,'R');
			$this->SetFont("Arial", "", 10);
			$this->Cell(102,5,ucwords($cashier),0,0,'L');
			$this->SetFont("Arial", "B", 10);
			$this->Cell(40,5,'EOS Date:',0,0,'R');
			$this->SetFont("Arial", "", 10);
			$this->Cell(50,5,_dateFormat($date),0,0,'L');
			$this->Ln(2);
		}

		function subheaderShortageOverageReport($cashier,$date,$time)
		{
			$this->Ln();
			$this->SetFont("Arial", "B", 10);
			$this->Cell(16,5,'Cashier: ',0,0,'R');
			$this->SetFont("Arial", "", 10);
			$this->Cell(102,5,ucwords($cashier),0,0,'L');
			$this->SetFont("Arial", "B", 10);
			$this->Cell(40,5,'Date:',0,0,'R');
			$this->SetFont("Arial", "", 10);
			$this->Cell(50,5,_dateFormat($date),0,0,'L');
			$this->Ln(4);
			$this->SetFont("Arial", "B", 10);
			$this->Cell(16,5,'',0,0,'R');
			$this->SetFont("Arial", "", 10);
			$this->Cell(102,5,'',0,0,'L');
			$this->SetFont("Arial", "B", 10);
			$this->Cell(40,5,'Time:',0,0,'R');
			$this->SetFont("Arial", "", 10);
			$this->Cell(50,5,_timeFormat($time),0,0,'L');
			$this->Ln(2);
		}

		function gcRevaloftheday($link,$data,$storeid)
		{
			$this->Ln();
			$this->SetFont("Arial", "B", 11);
			$this->Cell(195,8,'GC Revalidation ','B',0,'L');
			$this->SetFont("Arial", "", 10);
			$this->Ln(10);	
			$this->Cell(3,8,'',0,0,'L');
			$this->Cell(40,8,'Cashier',0,0,'L');
			$this->Cell(30,8,'Barcode',0,0,'L');
			$this->Cell(22,8,'Transaction #',0,0,'L');
			$this->Cell(20,8,'Time',0,0,'R');
			$this->Cell(30,8,'Denomination',0,0,'R');
			$this->Cell(40,8,'Revalidation Charge',0,0,'R');
							$this->Ln(6);
			foreach ($data as $d) 
			{
				$this->SetFont("Arial", "", 10);
				$this->Cell(3,8,'',0,0,'L');
				$this->Cell(40,8,$d->cashier,0,0,'L');

				$query_trans = $link->query(
					"SELECT 
						transaction_revalidation.reval_barcode,
					    transaction_revalidation.reval_denom,
					    transaction_revalidation.reval_charge,
					    transaction_stores.trans_number,
					    transaction_stores.trans_datetime
					FROM 
						transaction_revalidation 
					INNER JOIN
						transaction_stores
					ON
						transaction_stores.trans_sid = transaction_revalidation.reval_trans_id
					WHERE 
						transaction_stores.trans_store = '$storeid'
					AND
						transaction_stores.trans_cashier = '$d->trans_cashier'
					AND
						DATE(transaction_stores.trans_datetime) = CURDATE()
					AND
					    transaction_stores.trans_type='6'
					ORDER BY 
					    transaction_stores.trans_datetime='DESC'
				");

				if(!$query_trans)
				{
					echo $link->error;
				}

				$line = 0;

				if($query_trans->num_rows > 0)
				{
					while ($row = $query_trans->fetch_object()) 
					{
						if($line==0)
						{
							$this->SetFont("Arial", "", 10);
							$this->Cell(30,8,$row->reval_barcode,0,0,'L');
							$this->Cell(22,8,$row->trans_number,0,0,'L');
							$this->Cell(20,8,_timeFormat($row->trans_datetime),0,0,'R');
							$this->Cell(30,8,number_format($row->reval_denom,2),0,0,'R');
							$this->Cell(40,8,number_format($row->reval_charge,2),0,0,'R');
							$this->Ln(6);
							$line++;
							continue;
						}

						if($line==1)
						{
							$this->SetFont("Arial", "", 10);
							$this->Cell(3,8,'',0,0,'L');
							$this->Cell(40,8,'',0,0,'L');
							$this->Cell(30,8,$row->reval_barcode,0,0,'L');
							$this->Cell(22,8,$row->trans_number,0,0,'L');
							$this->Cell(20,8,_timeFormat($row->trans_datetime),0,0,'R');
							$this->Cell(30,8,number_format($row->reval_denom,2),0,0,'R');
							$this->Cell(40,8,number_format($row->reval_charge,2),0,0,'R');
							$this->Ln(6);
						}
					}
				}

				$this->Ln(6);
			}		
		}

		function gcOfTheDayFooter()
		{
			$this->Ln(8);
			$this->Cell(105,8,'Prepared by:',0,0,'L');
			$this->Cell(80,8,'Checked by:',0,0,'L');
			$this->Ln(8);
			$this->SetFont("Arial", "B", 10);
			$this->Cell(80,	8,'',0,0,'C');
			$this->Cell(34,8,'',0,0,'C');
			$this->Cell(60,8,'',0,0,'C');
			$this->Ln(4);
			$this->SetFont("Arial", "", 10);
			$this->Cell(18,	1,'',0,0,'R');
			$this->Cell(50,	1,'______________________________',0,0,'C');
			$this->Cell(36,	1,'',0,0,'C');
			$this->Cell(80,	1,'______________________________',0,0,'C');
			$this->Ln(5);
			$this->SetFont("Arial", "B", 7);
			$this->Cell(13,	1,'',0,0,'C');
			$this->Cell(60,	1,'(Signature over Printed name)',0,0,'C');
			$this->Cell(41,	1,'',0,0,'C');
			$this->Cell(60,	1,'(Signature over Printed name)',0,0,'C');
			$this->Ln(8);
		}

		function itemSalesGCoftheday($link,$data,$store)
		{
			$this->Ln();
			$this->SetFont("Arial", "B", 11);
			$this->Cell(195,8,'GC Sales ','B',0,'L');
			$this->SetFont("Arial", "", 10);
			$this->Ln(10);	
			$this->Cell(3,8,'',0,0,'L');
			$this->Cell(40,8,'Cashier',0,0,'L');
			$this->Cell(30,8,'Barcode',0,0,'L');
			$this->Cell(22,8,'Trans #',0,0,'L');
			$this->Cell(20,8,'Time',0,0,'R');
			$this->Cell(20,8,'Denom',0,0,'R');
			$this->Cell(18,8,'Line Disc',0,0,'R');
			$this->Cell(18,8,'Total Disc',0,0,'R');
			$this->Cell(20,8,'Subtotal',0,0,'R');
			$this->Ln(10);

			foreach ($data as $d) 
			{
				$this->SetFont("Arial", "", 10);
				$this->Cell(3,8,'',0,0,'L');
				$this->Cell(40,8,$d->cashier,0,0,'L');

				$query_trans = $link->query(
					"SELECT 
					    transaction_sales.sales_barcode,
					    transaction_stores.trans_number,
					    transaction_stores.trans_datetime,
					    denomination.denomination
					FROM 
					    transaction_sales 
					INNER JOIN
					    transaction_stores
					ON
					    transaction_sales.sales_transaction_id = transaction_stores.trans_sid
					INNER JOIN
					    gc
					ON
					    gc.barcode_no = transaction_sales.sales_barcode
					INNER JOIN
					    denomination
					ON
					    denomination.denom_id = gc.denom_id
					WHERE 
					    DATE(transaction_stores.trans_datetime) = CURDATE()
					AND
					    transaction_stores.trans_store = '".$store."'
					AND
					    transaction_stores.trans_cashier = '".$d->trans_cashier."'
					AND
					(
					    transaction_stores.trans_type='1'
					OR
					    transaction_stores.trans_type='2'
					OR
					    transaction_stores.trans_type='3'
					)
					ORDER BY 
					    transaction_stores.trans_number DESC
				");

				if(!$query_trans)
				{
					echo $link->error;
				}
				$line = 0;
				if($query_trans->num_rows > 0)
				{
					while ($row = $query_trans->fetch_object()) 
					{
						if($line==0)
						{
							$this->SetFont("Arial", "", 10);
							$this->Cell(30,8,$row->sales_barcode,0,0,'L');
							$this->Cell(22,8,$row->trans_number,0,0,'L');
							$this->Cell(20,8,_timeFormat($row->trans_datetime),0,0,'R');
							$this->Cell(20,8,number_format($row->denomination,2),0,0,'R');
							$this->Cell(18,8,'- 0.00',0,0,'R');
							$this->Cell(18,8,'- 0.00',0,0,'R');
							$this->Cell(20,8,number_format($row->denomination,2),0,0,'R');
							$this->Ln(6);
							$line++;
							continue;
						}

						if($line==1)
						{
							$this->SetFont("Arial", "", 10);
							$this->Cell(3,8,'',0,0,'L');
							$this->Cell(40,8,'',0,0,'L');
							$this->Cell(30,8,$row->sales_barcode,0,0,'L');
							$this->Cell(22,8,$row->trans_number,0,0,'L');
							$this->Cell(20,8,_timeFormat($row->trans_datetime),0,0,'R');
							$this->Cell(20,8,number_format($row->denomination,2),0,0,'R');
							$this->Cell(18,8,'- 0.00',0,0,'R');
							$this->Cell(18,8,'- 0.00',0,0,'R');
							$this->Cell(20,8,number_format($row->denomination,2),0,0,'R');
							$this->Ln(6);
						}
					}
				}

				$this->Ln(6);				
			}


		}
		
		function itemSalesReportCashHeader($trnum,$time,$trtype)
		{
			$this->Ln();
			$this->Cell(116,6,'Transaction # '. $trnum,'LTB',0,'L');
			$this->Cell(40,6,$trtype,'RTB',0,'R');
			$this->Cell(40,6,'Time: '._timeFormat($time),1,0,'R');
			$this->Ln();
			$this->Cell(54,6,'GC Barcode #',1,0,'L');
			$this->Cell(52,6,'Denomination',1,0,'L');
			$this->Cell(50,6,'Line Discount',1,0,'L');
			$this->Cell(40,6,'Net Amount',1,0,'L');
			$this->Ln();
		}

		function itemRevalidationPaymentHeader($trnum,$time,$trtype)
		{
			$this->Ln();
			$this->Cell(116,6,'Transaction # '. $trnum,'LTB',0,'L');
			$this->Cell(40,6,$trtype,'RTB',0,'R');
			$this->Cell(40,6,'Time: '._timeFormat($time),1,0,'R');
			$this->Ln();
			$this->Cell(36,6,'GC Barcode #',1,0,'L');
			$this->Cell(36,6,'Date Verified',1,0,'L');
			$this->Cell(36,6,'Denomination',1,0,'L');
			$this->Cell(48,6,'GC Type',1,0,'L');
			$this->Cell(40,6,'Payment',1,0,'L');
			$this->Ln();
		}

		function itemRefundHeader($trnum,$time,$trtype)
		{
			$this->Ln();
			$this->Cell(116,6,'Transaction # '. $trnum,'LTB',0,'L');
			$this->Cell(40,6,$trtype,'RTB',0,'R');
			$this->Cell(40,6,'Time: '._timeFormat($time),1,0,'R');
			$this->Ln();
			$this->Cell(36,6,'GC Barcode #',1,0,'L');
			$this->Cell(36,6,'Denomination',1,0,'L');
			$this->Cell(36,6,'Line Discount',1,0,'L');
			$this->Cell(48,6,'Transaction Discount',1,0,'L');
			$this->Cell(40,6,'Refund',1,0,'L');
			$this->Ln();
		}

		function itemRefundItems($barcode,$denom,$linedisc,$transdisc,$refamt)
		{
			$this->Cell(36,6,$barcode,1,0,'L');
			$this->Cell(36,6,number_format($denom,2),1,0,'R');
			$this->Cell(36,6,number_format($linedisc,2),1,0,'R');
			$this->Cell(48,6,number_format($transdisc,2),1,0,'R');
			$this->Cell(40,6,number_format($refamt,2),1,0,'R');
			$this->Ln();
		}

		function refundServiceCharge($scharge)
		{
			$this->Cell(156,6,'Service Charge',"LTB",0,'R');
			$this->Cell(40,6,'- '.number_format($scharge,2),'RTB',0,'R');
			$this->ln();				
		}

		function refundAmount($refAmount)
		{
			$this->Cell(156,6,'Total Refund',"LTB",0,'R');
			$this->Cell(40,6,'- '.number_format($refAmount,2),'RTB',0,'R');
			$this->ln();				
		}

		function itemRevalidationItems($barcode,$date,$den,$promo,$type,$payment)
		{
			$ty = '';
			$this->Cell(36,6,$barcode,1,0,'L');
			$this->Cell(36,6,_dateFormat($date),1,0,'L');
			$this->Cell(36,6,number_format($den,2),1,0,'L');
			if($promo=='*')
			{
				$ty = 'promo';
			}
			else 
			{
				$ty = $type;
			}
			$this->Cell(48,6,ucwords($ty),1,0,'L');
			$this->Cell(40,6,number_format($payment,2),1,0,'R');
			$this->Ln();
		}

		function itemSalesReportItems($barcode,$denom,$linedisc,$netamt)
		{
			$this->Cell(54,6,$barcode,1,0,'C');
			$this->Cell(52,6,number_format($denom,2),1,0,'R');
			$this->Cell(50,6,'- '.$linedisc,1,0,'R');
			$this->Cell(40,6,number_format($netamt,2),1,0,'R');
			$this->ln();
		}

		function noTransaction()
		{
			$this->Ln();
			$this->SetFont("Arial", "", 10);
			$this->Cell(200,6,'No Transaction Exist	.',0,0,'C');
		}

		function tranDiscount($trdiscount)
		{
			$this->Cell(156,6,'Transaction Discount',"LTB",0,'R');
			$this->Cell(40,6,'- '.number_format($trdiscount,2),'RTB',0,'R');
			$this->ln();	
		}

		function customerDiscount($cusdiscount)
		{
			$this->Cell(156,6,'Customer Discount',"LTB",0,'R');
			$this->Cell(40,6,'- '.$cusdiscount,'RTB',0,'R');
			$this->ln();	
		}

		function transAmountDue($amtdue)
		{
			$this->Cell(156,6,'Amount Due',"LTB",0,'R');
			$this->Cell(40,6, number_format($amtdue,2),'RTB',0,'R');	
			$this->ln();		
		}
	}

