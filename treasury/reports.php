<?php
	session_start();
	require_once('../config.php');
	require('../fpdf.php');
	require_once('../function.php');
 	require_once('../function-cashier.php');
	class REPORTS extends FPDF
	{
		function setReportName($rname)
		{
			$this->reportname = $rname;
		}

		function setFooter($flag)
		{
			$this->setFlag = $flag;
		}

		function setDate($daterel)
		{
			$this->datereleased = $daterel;
		}

		function setRelNum($rel)
		{
			$this->relnum = $rel;
		}

		function setFooterText($text)
		{
			$this->footertext = $text;
		}

		function Footer()
		{
			if($this->setFlag)
			{
				$this->SetY(-15);
				$this->SetTextColor(74, 74, 74);
				$this->SetFont("Arial", "", 7);
				// $this->SetDrawColor(74, 74, 74);
				// $this->SetLineWidth(0.2);
				// $this->Line(10, 265, 205, 265);
				$this->Cell(0, 10, "Page ".$this->PageNo()." - {nb}", 0, 0, "C");
				$this->Cell(0, 10, $this->footertext, 0, 0, "R");				
			}

		}

		function docHeader($usertype)
		{
			// get user department

			$usertype = str_replace('Corporate', '', $usertype);
			$this->SetFont("Helvetica", "B", 12);
			$this->SetTextColor(28, 28, 28);
			$this->Cell(0, 8, 'ALTURAS GROUP OF COMPANIES', 0, 0, "C");			
			$this->Ln(6);
			$this->SetFont("times", "B",11);
			$this->SetTextColor(28, 28, 28);
			$this->Cell(0, 8, 'Head Office - '.ucwords($usertype).' Department', 0, 0, "C");
			$this->Ln(1);
			$this->SetFont("times", "B",11);
			$this->SetTextColor(28, 28, 28);
			$this->Ln();
			$this->Cell(0, 1, $this->reportname, 0, 0, "C");	
			$this->Ln(10);
		}

		function detailspromo($link,$denomgroup,$relid)
		{
			$this->SetFont("Arial", "", 10);
			foreach ($denomgroup as $d) 
			{
				$x=1;	
				$this->Cell(190,8,'Denomination: '.number_format($d->denomination,2),1,0,'L');
				$this->Ln(8);
				$gcs = getDenomReleasingReportByIdPromo($link,$relid,$d->denom_id);	
				foreach ($gcs as $g) 
				{
					if($x<=5)
					{
						$this->Cell(40,	8,$g->prreltoi_barcode,0,0,'L');
						$x++;
					}
					else 
					{	
						$x=1;										
						$this->Ln(5);
						$this->Cell(40,	8,$g->prreltoi_barcode,0,0,'L');	
						$x++;											
					}
				}
				$pcs = $d->cnt > 1 ? 'pcs' : 'pc';
				$this->Ln(8);
				$this->Cell(140,6,'No of GC:','TB',0,true);
				$this->Cell(50,6,$d->cnt.' '.$pcs,'TB',0);
				$this->Ln(9);
			}
		}

		function detailsIns($link,$denoms,$id)
		{
			$this->SetFont("Arial", "", 10);
			foreach ($denoms as $d) 
			{
				$x=1;	
				$this->Cell(190,8,'Denomination: '.number_format($d->denomination,2),1,0,'L');
				$this->Ln(8);
				$gcs = getDenomReleasingReportByIdIns($link,$id,$d->denom_id);	
				foreach ($gcs as $g) 
				{
					if($x<=5)
					{
						$this->Cell(40,	8,$g->instituttritems_barcode,0,0,'L');
						$x++;
					}
					else 
					{	
						$x=1;										
						$this->Ln(5);
						$this->Cell(40,	8,$g->instituttritems_barcode,0,0,'L');	
						$x++;											
					}
				}
				$pcs = $d->cnt > 1 ? 'pcs' : 'pc';
				$this->Ln(8);
				$this->Cell(140,6,'No of GC:','TB',0,true);
				$this->Cell(50,6,$d->cnt.' '.$pcs,'TB',0);
				$this->Ln(9);
			}
		}

		function subheaderpromo($id,$data)
		{
			$this->Ln();
			$this->SetFont("Arial", "B", 10);
			$this->Cell(28,5,'GC Rel. No.: ',0,0,'R');
			$this->SetFont("Arial", "", 10);
			$this->Cell(86,5,sprintf("%03d",$id),0,0,'L');
			$this->SetFont("Arial", "B", 10);
			$this->Cell(40,5,'Date Released:',0,0,'R');
			$this->SetFont("Arial", "", 10);
			$this->Cell(50,5,_dateFormat($data->prrelto_date),0,0,'L');
			$this->Ln(12);
		}

		function displaySpecialDenoms($id,$data,$datadet)
		{
			$totcnt = 0;
			$totdenom = 0;
			$this->SetFont("Arial", "", 10);
			$this->Cell(50,8,'',0,0,'L');
			$this->Cell(50,8,'Denomination',1,0,'C');
			$this->Cell(50,8,'Quantity',1,0,'C');
			$this->Ln(8);
			foreach ($data as $d) 
			{
				$sub = 0;
				$this->Cell(50,8,'',0,0,'L');
				$this->Cell(50,8,$d->specit_denoms,1,0,'C');
				$this->Cell(50,8,$d->specit_qty,1,0,'C');
				$this->Ln(8);
				$totcnt += $d->specit_qty;
				$sub = $d->specit_qty * $d->specit_denoms;
				$totdenom += $sub;
			}
			$this->Ln(4);
			$pc = $totcnt > 1 ? 'pcs' : 'pc';
			$this->SetFont("Arial", "", 10);
			$this->Cell(50,	8,'Total No. of GC:',0,0,'R');
			$this->SetFont("Arial", "B", 10);
			$this->Cell(60,	8,$totcnt.' '.$pc,0,0,'L');
			$this->Ln(5);
			$this->SetFont("Arial", "", 10);
			$this->Cell(50,	8,'Total GC Amount:',0,0,'R');
			$this->SetFont("Arial", "B", 10);
			$this->Cell(60,	8,number_format($totdenom,2),0,0,'L');
			
			$this->Ln(5);
			$this->SetFont("Arial", "", 10);

			$type = $datadet->spexgc_paymentype == 1 ? 'cash' : 'check';
			$this->Cell(50,	8,'Payment Type:',0,0,'R');
			$this->SetFont("Arial", "B", 10);
			$this->Cell(60,	8,ucwords($type),0,0,'L');
			$this->Ln(5);

			if($datadet->spexgc_paymentype==1)
			{
				$this->SetFont("Arial", "", 10);			
				$this->Cell(50,	8,'Cash Paid:',0,0,'R');
				$this->SetFont("Arial", "B", 10);			
				$this->Cell(60,	8,number_format($datadet->institut_amountrec,2),0,0,'L');				
			}
			else 
			{
				$this->SetFont("Arial", "", 10);			
				$this->Cell(50,	8,'Bank Name:',0,0,'R');
				$this->SetFont("Arial", "B", 10);			
				$this->Cell(60,	8,$datadet->institut_bankname,0,0,'L');
				$this->Ln(5);
				$this->SetFont("Arial", "", 10);			
				$this->Cell(50,	8,'Bank Account Number:',0,0,'R');
				$this->SetFont("Arial", "B", 10);			
				$this->Cell(60,	8,$datadet->institut_bankaccountnum,0,0,'L');
				$this->Ln(5);
				$this->SetFont("Arial", "", 10);			
				$this->Cell(50,	8,'Check Number:',0,0,'R');
				$this->SetFont("Arial", "B", 10);			
				$this->Cell(60,	8,$datadet->institut_checknumber,0,0,'L');
				$this->Ln(5);
				$this->SetFont("Arial", "", 10);			
				$this->Cell(50,	8,'Check Amount:',0,0,'R');
				$this->SetFont("Arial", "B", 10);			
				$this->Cell(60,	8,number_format($datadet->institut_amountrec,2),0,0,'L');
			}	
			$this->Ln(5);
			$this->SetFont("Arial", "", 10);

			$this->Cell(50,	8,'AR #:',0,0,'R');
			$this->SetFont("Arial", "B", 10);
			$this->Cell(60,	8,$datadet->spexgc_payment_arnum,0,0,'L');

			$this->Ln(8);
			$this->SetFont("Arial", "", 10);
			$this->Cell(105,8,'',0,0,'L');
			$this->Cell(80,8,'Received by:',0,0,'L');
			$this->Ln(8);
			$this->SetFont("Arial", "B", 10);
			$this->Cell(80,	8,'',0,0,'C');
			$this->Cell(34,8,'',0,0,'C');
			$this->Cell(60,8,ucwords($datadet->recby),0,0,'C');
			$this->Ln(4);
			$this->SetFont("Arial", "", 10);
			$this->Cell(18,	1,'',0,0,'R');
			$this->Cell(50,	1,'',0,0,'C');
			$this->Cell(36,	1,'',0,0,'C');
			$this->Cell(80,	1,'______________________________',0,0,'C');
			$this->Ln(5);
			$this->SetFont("Arial", "B", 7);
			$this->Cell(13,	1,'',0,0,'C');
			$this->Cell(60,	1,'',0,0,'C');
			$this->Cell(41,	1,'',0,0,'C');
			$this->Cell(60,	1,'(Signature over Printed name)',0,0,'C');

		}

		function eodCustomer($link,$data)
		{
			$total = 0;
			$items = 0;
			$cash = 0;
			$check = 0;
			$jv = 0;
			$gad = 0;
			foreach ($data as $p) 
			{
				$totgccnt = '';
				$totdenom = '';
				$customer = '';
				$paymenttype = '';

				if($p->insp_paymentcustomer=='institution')
				{

					$query = $link->query(
						"SELECT 
							institut_transactions.institutr_id,
						    institut_transactions.institutr_trnum,
						    institut_transactions.institutr_paymenttype,
						    institut_transactions.institutr_date,
						    institut_transactions.institutr_checkamt,
						    institut_transactions.institutr_cashamt,
						    institut_transactions.institutr_totamtrec,
						    institut_customer.ins_name
						FROM 
							institut_transactions 
						INNER JOIN
							institut_customer
						ON
							institut_customer.ins_id = institut_transactions.institutr_cusid
						WHERE 
							institut_transactions.institutr_id = '$p->insp_trid'
					");

					if($query)
					{
						$row = $query->fetch_object();

						$paymenttype = $row->institutr_paymenttype;

						$customer = $row->ins_name;
						$datetr = $row->institutr_date;

						$query_gcs = $link->query(
							"SELECT 
								IFNULL(COUNT(institut_transactions_items.instituttritems_barcode),0) as cnt,
							    IFNULL(SUM(denomination.denomination),0) as totamt   
							    
							FROM 
								institut_transactions_items
							INNER JOIN
								gc
							ON
								gc.barcode_no = institut_transactions_items.instituttritems_barcode
							INNER JOIN
								denomination
							ON
								denomination.denom_id = gc.denom_id
							WHERE 
								instituttritems_trid = '$p->insp_trid'
						");

						if($query_gcs)
						{
							$row_gcs = $query_gcs->fetch_object();

							$totgccnt = $row_gcs->cnt;
							$totdenom = $row_gcs->totamt;

							if($row->institutr_paymenttype=='cash')
							{
								$cash += $totdenom;
							}
							elseif($row->institutr_paymenttype=='cash')
							{
								$check += $totdenom;
							}
							elseif($row->institutr_paymenttype=='cashcheck')
							{
								$cash += $row->institutr_cashamt;
								$check += $row->institutr_checkamt;
								//echo 'sulod';
							}
							elseif ($row->institutr_paymenttype=='gad') 
							{
								$gad += $totdenom;
							}
						}
					}

				}
				elseif($p->insp_paymentcustomer=='stores') 
				{
					$query = $link->query(
						"SELECT 
							approved_gcrequest.agcr_request_id,
							approved_gcrequest.agcr_request_relnum,
						    approved_gcrequest.agcr_approved_at,
						    approved_gcrequest.agcr_paymenttype,
						    stores.store_name
						FROM 
							approved_gcrequest
						INNER JOIN
							store_gcrequest
						ON
							store_gcrequest.sgc_id = approved_gcrequest.agcr_request_id
						INNER JOIN
							stores
						ON
							stores.store_id = store_gcrequest.sgc_store
						WHERE 
							approved_gcrequest.agcr_id = '$p->insp_trid'	
					");

					if($query)
					{
						$row = $query->fetch_object();
						$customer = $row->store_name;
						$datetr = $row->agcr_approved_at;

						$paymenttype = $row->agcr_paymenttype;

						$query_gcs = $link->query(
							"SELECT		
								IFNULL(COUNT(gc_release.re_barcode_no),0) as cnt,																		  		
								IFNULL(SUM(denomination.denomination),0) as totamt  
							FROM 
								gc_release 
							INNER JOIN
								gc
							ON
								gc.barcode_no = gc_release.re_barcode_no
							INNER JOIN
								denomination
							ON
								denomination.denom_id = gc.denom_id
							WHERE 
								rel_num='$row->agcr_request_relnum'
						");

						if($query_gcs)
						{
							$row_gcs = $query_gcs->fetch_object();

							$totgccnt = $row_gcs->cnt;
							$totdenom = $row_gcs->totamt;

							if($row->agcr_paymenttype == 'cash')
							{
								$cash += $totdenom;
							}
							elseif($row->agcr_paymenttype == 'check')							
							{
								$check += $totdenom;
							}
							elseif($row->agcr_paymenttype == 'jv')
							{
								$jv += $totdenom;
							}
						}
					}


				}
				elseif ($p->insp_paymentcustomer=='special external') 
				{
					$query = $link->query(
						"SELECT 
							special_external_gcrequest.spexgc_id,
						    special_external_gcrequest.spexgc_datereq,
						    special_external_customer.spcus_companyname,
						    special_external_gcrequest.spexgc_paymentype
						FROM 
							special_external_gcrequest
						INNER JOIN
							special_external_customer
						ON
							special_external_customer.spcus_id = special_external_gcrequest.spexgc_company
						WHERE 
							special_external_gcrequest.spexgc_id='$p->insp_trid'	
					");

					if($query)
					{
						$row = $query->fetch_object();

						$customer = $row->spcus_companyname;
						$datetr = $row->spexgc_datereq;

						if($row->spexgc_paymentype=='1')
						{
							$paymenttype = 'cash';
						}
						else 
						{
							$paymenttype = 'check';
						}

						$query_gcs = $link->query(
							"SELECT 
								IFNULL(SUM(special_external_gcrequest_items.specit_qty),0) as cnt,
    							IFNULL(SUM(special_external_gcrequest_items.specit_denoms * special_external_gcrequest_items.specit_qty),0) as totamt
							FROM 
								special_external_gcrequest_items
							WHERE 
								specit_trid='$p->insp_trid'

						");

						if($query_gcs)
						{
							$row_gcs = $query_gcs->fetch_object();

							$totgccnt = $row_gcs->cnt;
							$totdenom = $row_gcs->totamt;	 
							if($row->spexgc_paymentype == '1')
							{
								$cash += $totdenom;
							}
							else 
							{
								$check += $totdenom;
							}                       								
						}

					}
				}

				$this->SetFont("Arial", "", 10);
				$this->Cell(28,5,'Customer: ',0,0,'R');
				$this->SetFont("Arial","B", 10);
				$this->Cell(86,5,ucwords($customer),0,0,'L');
				$this->SetFont("Arial", "", 10);
				$this->Cell(40,5,'Payment #:',0,0,'R');
				$this->SetFont("Arial","B", 10);
				$this->Cell(50,5,sprintf("%03d",$p->insp_paymentnum),0,0,'L');
				$this->Ln(6);

				$total += $totdenom;
				$items += $totgccnt;
				$this->Ln(1);
				$this->Cell(10,5,'',0,0,'L');		
				$this->SetFont("Arial","", 10);		
				$this->Cell(28,5,'Payment Type:',0,0,'R');
				$this->SetFont("Arial","B", 10);
				if($paymenttype=='cashcheck')
				{
					$this->Cell(28,5,ucwords("Check And Cash"),0,0,'L');
				}
				elseif ($paymenttype=='gad') 
				{
					$this->Cell(28,5,ucwords("Giveaways and Donations"),0,0,'L');
				}
				else
				{
					$this->Cell(28,5,strtoupper($paymenttype),0,0,'L');
				}
				
				$this->Ln();
				if($paymenttype=='check')
				{
					$this->Cell(10,5,'',0,0,'L');
					$this->SetFont("Arial","", 10);
					$this->Cell(28,5,'Bank Name:',0,0,'R');
					$this->SetFont("Arial","B", 10);
					$this->Cell(28,5,$p->institut_bankname,0,0,'L');
					$this->Ln();	
					$this->Cell(10,5,'',0,0,'L');
					$this->SetFont("Arial","", 10);
					$this->Cell(28,5,'Bank Account #:',0,0,'R');
					$this->SetFont("Arial","B", 10);
					$this->Cell(28,5,$p->institut_bankaccountnum,0,0,'L');
					$this->Ln();		
					$this->Cell(10,5,'',0,0,'L');
					$this->SetFont("Arial","", 10);
					$this->Cell(28,5,'Check #:',0,0,'R');
					$this->SetFont("Arial","B", 10);
					$this->Cell(28,5,$p->institut_checknumber,0,0,'L');
					$this->Ln();			
				}
				elseif($paymenttype=='cashcheck')
				{
					$this->Cell(10,5,'',0,0,'L');
					$this->SetFont("Arial","", 10);
					$this->Cell(28,5,'Bank Name:',0,0,'R');
					$this->SetFont("Arial","B", 10);
					$this->Cell(28,5,$p->institut_bankname,0,0,'L');
					$this->Ln();	
					$this->Cell(10,5,'',0,0,'L');
					$this->SetFont("Arial","", 10);
					$this->Cell(28,5,'Bank Account #:',0,0,'R');
					$this->SetFont("Arial","B", 10);
					$this->Cell(28,5,$p->institut_bankaccountnum,0,0,'L');
					$this->Ln();		
					$this->Cell(10,5,'',0,0,'L');
					$this->SetFont("Arial","", 10);
					$this->Cell(28,5,'Check #:',0,0,'R');
					$this->SetFont("Arial","B", 10);
					$this->Cell(28,5,$p->institut_checknumber,0,0,'L');
					$this->Ln();	
					$this->Cell(10,5,'',0,0,'L');
					$this->SetFont("Arial","", 10);
					$this->Cell(28,5,'Check Amount:',0,0,'R');
					$this->SetFont("Arial","B", 10);
					$this->Cell(28,5,number_format($row->institutr_checkamt,2),0,0,'L');
					$this->Ln();	
					$this->Cell(10,5,'',0,0,'L');
					$this->SetFont("Arial","", 10);
					$this->Cell(28,5,'Cash:',0,0,'R');
					$this->SetFont("Arial","B", 10);
					$this->Cell(28,5,number_format($row->institutr_cashamt,2),0,0,'L');
					$this->Ln();				
				}
				elseif($paymenttype=='jv')
				{
					$this->Cell(10,5,'',0,0,'L');
					$this->SetFont("Arial","", 10);
					$this->Cell(28,5,'Customer:',0,0,'R');
					$this->SetFont("Arial","B", 10);
					$this->Cell(28,5,strtoupper($p->institut_jvcustomer),0,0,'L');
					$this->Ln();	
				}

				$this->Cell(10,5,'',0,0,'L');
				$this->SetFont("Arial","", 10);
				$this->Cell(28,5,'GC pc(s):',0,0,'R');
				$this->SetFont("Arial","B", 10);
				$this->Cell(28,5,number_format($totgccnt),0,0,'L');
				$this->Ln();
				$this->Cell(10,5,'',0,0,'L');
				$this->SetFont("Arial","", 10);
				$this->Cell(28,5,'Total GC Amount:',0,0,'R');
				$this->SetFont("Arial","B", 10);
				$this->Cell(28,5,number_format($totdenom,2),0,0,'L');
				$this->Ln(10);				
			}
			$this->footerCashierAccountability($link,$total,$data,$items,$cash,$check,$jv,$gad);


		}

		function footerCashierAccountability($link,$total,$data,$items,$cash,$check,$jv,$gad)
		{
			$this->Cell(194,0,'','B',0,'R');
			$this->Ln(4);
			$this->SetFont("Arial","", 10);
			$this->Cell(55,5,'Total GC Sold:',0,0,'R');
			$this->SetFont("Arial","B", 10);
			$this->Cell(55,5,$items,0,0,'L');
			$this->Ln();
			$this->SetFont("Arial","", 10);
			$this->Cell(55,5,'No. of Transactions:',0,0,'R');
			$this->SetFont("Arial","B", 10);
			$this->Cell(55,5,count($data),0,0,'L');
			$this->Ln();
			$this->SetFont("Arial","", 10);
			$this->Cell(55,5,'Total Cash:',0,0,'R');
			$this->SetFont("Arial","B", 10);
			$this->Cell(55,5,number_format($cash,2),0,0,'L');
			$this->Ln();
			$this->SetFont("Arial","", 10);
			$this->Cell(55,5,'Total Check:',0,0,'R');
			$this->SetFont("Arial","B", 10);
			$this->Cell(55,5,number_format($check,2),0,0,'L');
			$this->Ln();
			$this->SetFont("Arial","", 10);
			$this->Cell(55,5,'Total JV:',0,0,'R');
			$this->SetFont("Arial","B", 10);
			$this->Cell(55,5,number_format($jv,2),0,0,'L');
			$this->Ln();
			$this->SetFont("Arial","", 10);
			$this->Cell(55,5,'Total Giveaways and Donations:',0,0,'R');
			$this->SetFont("Arial","B", 10);
			$this->Cell(55,5,number_format($gad,2),0,0,'L');
			$this->Ln();
			$this->SetFont("Arial","", 10);
			$this->Cell(55,5,'Total Sales / Released:',0,0,'R');
			$this->SetFont("Arial","B", 10);
			$this->Cell(55,5,number_format($total,2),0,0,'L');
			$this->Ln(10);
			$this->SetFont("Arial","", 10);
			$this->Cell(125,5,'Prepared by:',0,0,'R');
			$this->Ln(8);
			$this->Cell(125,5,'',0,0,'R');
			$this->SetFont("Arial","B", 10);
			$this->Cell(60,5,ucwords($_SESSION['gc_fullname']),0,0,'C');
			$this->Ln(1);
			$this->Cell(125,5,'',0,0,'R');
			$this->SetFont("Arial","", 10);
			$this->Cell(60,5,'____________________________',0,0,'L');
			$this->Ln();
			$this->Cell(125,5,'',0,0,'R');
			$this->SetFont("Arial", "", 7);
			$this->Cell(60,5,'( Signature over Printed name )',0,0,'C');

		}

		function subheaderCashierAccountability($data)
		{
			$this->SetFont("Arial", "", 10);
			$this->Cell(28,5,'EOD No.: ',0,0,'R');
			$this->SetFont("Arial", "B", 10);
			$this->Cell(86,5,sprintf("%03d",$data->ieod_num),0,0,'L');
			$this->SetFont("Arial", "", 10);
			$this->Cell(40,5,'Date:',0,0,'R');
			$this->SetFont("Arial", "B", 10);
			$this->Cell(50,5,_dateFormat($data->ieod_date),0,0,'L');
			$this->Ln();
			$this->SetFont("Arial", "", 10);
			$this->Cell(154,5,'Time:',0,0,'R');
			$this->SetFont("Arial", "B", 10);
			$this->Cell(50,5,_timeFormat($data->ieod_date),0,0,'L');
			$this->Ln(12);		
		}

		function subheaderInst($id,$data)														
		{
			$this->Ln();
			$this->SetFont("Arial", "", 10);
			$this->Cell(28,5,'GC Rel. No.: ',0,0,'R');
			$this->SetFont("Arial", "B", 10);
			$this->Cell(86,5,sprintf("%03d",$data->institutr_trnum),0,0,'L');
			$this->SetFont("Arial", "", 10);
			$this->Cell(40,5,'Date Released:',0,0,'R');
			$this->SetFont("Arial", "B", 10);
			$this->Cell(50,5,_dateFormat($data->institutr_date),0,0,'L');
			$this->Ln();
			$this->SetFont("Arial", "", 10);
			$this->Cell(28,5,'Customer: ',0,0,'R');
			$this->SetFont("Arial", "B", 10);
			$this->Cell(86,5,ucwords($data->ins_name),0,0,'L');
			$this->Ln(12);			
		}

		function subheaderSpecial($id,$data)
		{
			$this->Ln();
			$this->SetFont("Arial", "", 10);
			$this->Cell(28,5,'SGC Req #: ',0,0,'R');
			$this->SetFont("Arial", "B", 10);
			$this->Cell(86,5,sprintf("%03d",$data->spexgc_num),0,0,'L');
			$this->SetFont("Arial", "", 10);
			$this->Cell(40,5,'Date Received:',0,0,'R');
			$this->SetFont("Arial", "B", 10);
			$this->Cell(50,5,_dateFormat($data->spexgc_datereq),0,0,'L');
			$this->Ln();
			$this->SetFont("Arial", "", 10);
			$this->Cell(28,5,'Customer: ',0,0,'R');
			$this->SetFont("Arial", "B", 10);
			$this->Cell(86,5,ucwords($data->spcus_companyname),0,0,'L');
			$this->SetFont("Arial", "", 10);
			$this->Cell(40,5,'Account Name:',0,0,'R');
			$this->SetFont("Arial", "B", 10);
			$this->Cell(50,5,$data->spcus_acctname,0,0,'L');
			$this->Ln(12);			
		}

		function subfooterpromo($data,$count)
		{
			$this->SetFont("Arial", "", 10);
			$this->Cell(30,	8,'Releasing Type:',0,0,'R');
			$this->Cell(60,	8,ucwords($data->prrelto_status),0,0,'L');
			$this->Ln(5);
			$this->Cell(30,	8,'Total No. of GC:',0,0,'R');
			$this->Cell(60,	8,$count->cnt.' pcs',0,0,'L');
			$this->Ln(5);
			$this->Cell(30,	8,'Total GC Amount:',0,0,'R');
			$this->Cell(60,	8,number_format($count->total,2),0,0,'L');
			$this->SetFont("Arial", "", 10);
			$this->Ln(8);
			$this->Cell(105,8,'Prepared by:',0,0,'L');
			$this->Cell(80,8,'Checked by:',0,0,'L');
			$this->Ln(8);
			$this->SetFont("Arial", "B", 10);
			$this->Cell(80,	8,ucwords($data->user),0,0,'C');
			$this->Cell(34,8,'',0,0,'C');
			$this->Cell(60,8,$data->prrelto_checkedby,0,0,'C');
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
			$this->SetFont("Arial", "", 10);
			$this->Cell(105,8,'',0,0,'L');
			$this->Cell(80,8,'Approved by:',0,0,'L');
			$this->Ln(8);
			$this->SetFont("Arial", "B", 10);
			$this->Cell(80,	8,'',0,0,'C');
			$this->Cell(34,8,'',0,0,'C');
			$this->Cell(60,8,$data->prrelto_approvedby,0,0,'C');
			$this->Ln(4);
			$this->SetFont("Arial", "", 10);
			$this->Cell(18,	1,'',0,0,'R');
			$this->Cell(50,	1,'',0,0,'C');
			$this->Cell(36,	1,'',0,0,'C');
			$this->Cell(80,	1,'______________________________',0,0,'C');
			$this->Ln(5);
			$this->SetFont("Arial", "B", 7);
			$this->Cell(13,	1,'',0,0,'C');
			$this->Cell(60,	1,'',0,0,'C');
			$this->Cell(41,	1,'',0,0,'C');
			$this->Cell(60,	1,'(Signature over Printed name)',0,0,'C');
		}

		function subfooterIns($data,$count)
		{
			$pc = $count->cnt > 1 ? 'pcs' : 'pc';

			$this->SetFont("Arial", "", 10);
			$this->Cell(30,	8,'Total No. of GC:',0,0,'R');
			$this->Cell(60,	8,$count->cnt.' '.$pc,0,0,'L');
			$this->Ln(5);
			$this->Cell(30,	8,'Payment Type:',0,0,'R');
			if($data->institutr_paymenttype=='cashcheck')
			{
				$this->Cell(60,	8,'Check and Cash',0,0,'L');
			}
			elseif($data->institutr_paymenttype=='gad') 
			{
				$this->Cell(60,	8,'Giveaways and Donations',0,0,'L');
			}
			else 
			{
				$this->Cell(60,	8,ucwords($data->institutr_paymenttype),0,0,'L');
			}

			if($data->institutr_paymenttype=='gad')
			{
				$this->Ln(5);
				$this->Cell(30,	8,'Total GC Amount:',0,0,'R');
				$this->Cell(60,	8,number_format($count->total,2),0,0,'L');
				$this->Ln(5);
				$this->Cell(30,	8,'Document:',0,0,'R');
				$this->Cell(60,	8,strtoupper($data->institutr_docname),0,0,'L');
			}

			if($data->institutr_paymenttype=='cash')
			{
				$this->Ln(5);
				$this->Cell(30,	8,'Cash Received:',0,0,'R');
				$this->Cell(60,	8,number_format($data->institutr_totamtrec,2),0,0,'L');
				$this->Ln(5);
				$this->Cell(30,	8,'Total GC Amount:',0,0,'R');
				$this->Cell(60,	8,number_format($count->total,2),0,0,'L');
				$this->Ln(5);
				$change = floatval($data->institutr_totamtrec) - floatval($count->total);
				$this->Cell(30,	8,'Change:',0,0,'R');
				$this->Cell(60,	8,number_format($change,2),0,0,'L');
			}

			if($data->institutr_paymenttype=='check')
			{
				$this->Ln(5);
				$this->Cell(30,	8,'Bank Name:',0,0,'R');
				$this->Cell(60,	8,ucwords($data->institut_bankname),0,0,'L');
				$this->Ln(5);
				$this->Cell(30,	8,'Bank Account #:',0,0,'R');
				$this->Cell(60,	8,ucwords($data->institut_bankaccountnum),0,0,'L');
				$this->Ln(5);
				$this->Cell(30,	8,'Check #:',0,0,'R');
				$this->Cell(60,	8,ucwords($data->institut_checknumber),0,0,'L');
				$this->Ln(5);
				$this->Cell(30,	8,'Check Amount:',0,0,'R');
				$this->Cell(60,	8,number_format($data->institut_amountrec,2),0,0,'L');
				$this->Ln(5);
				$this->Cell(30,	8,'Total GC Amount:',0,0,'R');
				$this->Cell(60,	8,number_format($count->total,2),0,0,'L');
				$this->Ln(5);
				$change = floatval($data->institut_amountrec) - floatval($count->total);
				$this->Cell(30,	8,'Change:',0,0,'R');
				$this->Cell(60,	8,number_format($change,2),0,0,'L');			
			}

			if($data->institutr_paymenttype=='cashcheck')
			{
				$this->Ln(5);
				$this->Cell(30,	8,'Bank Name:',0,0,'R');
				$this->Cell(60,	8,ucwords($data->institut_bankname),0,0,'L');
				$this->Ln(5);
				$this->Cell(30,	8,'Bank Account #:',0,0,'R');
				$this->Cell(60,	8,ucwords($data->institut_bankaccountnum),0,0,'L');
				$this->Ln(5);
				$this->Cell(30,	8,'Check #:',0,0,'R');
				$this->Cell(60,	8,ucwords($data->institut_checknumber),0,0,'L');
				$this->Ln(5);
				$this->Cell(30,	8,'Check Amount:',0,0,'R');
				$this->Cell(60,	8,number_format($data->institutr_checkamt,2),0,0,'L');
				$this->Ln(5);
				$this->Cell(30,	8,'Cash:',0,0,'R');
				$this->Cell(60,	8,number_format($data->institutr_cashamt,2),0,0,'L');
				$this->Ln(5);
				$this->Cell(30,	8,'Total Received:',0,0,'R');
				$this->Cell(60,	8,number_format($data->institutr_totamtrec,2),0,0,'L');
				$this->Ln(5);
				$this->Cell(30,	8,'Total GC Amount:',0,0,'R');
				$this->Cell(60,	8,number_format($count->total,2),0,0,'L');
				$this->Ln(5);
				$change = floatval($data->institutr_totamtrec) - floatval($count->total);
				$this->Cell(30,	8,'Change:',0,0,'R');
				$this->Cell(60,	8,number_format($change,2),0,0,'L');			
			}


			$this->Ln(5);
			$this->Cell(30,	8,'Payment Fund:',0,0,'R');
			$this->Cell(60,	8,ucwords($data->pay_desc),0,0,'L');
			$this->SetFont("Arial", "", 10);
			$this->Ln(8);

			$this->Ln(8);
			$this->Cell(65,8,'Received by:',0,0,'L');
			$this->Cell(65,8,'Released by:',0,0,'L');
			$this->Cell(65,8,'Checked by:',0,0,'L');
			$this->Ln(8);
			$this->SetFont("Arial", "B", 10);
			$this->Cell(65,	8,ucwords($data->institutr_receivedby),0,0,'C');
			$this->Cell(65,8,ucwords($data->relby),0,0,'C');
			$this->Cell(60,8,ucwords($data->institutr_checkedby),0,0,'C');
			$this->Ln(4);
			$this->SetFont("Arial", "", 10);
			$this->Cell(65,	1,'__________________________',0,0,'C');
			$this->Cell(65,	1,'__________________________',0,0,'C');
			$this->Cell(65,	1,'__________________________',0,0,'C');
			$this->Ln(5);
			$this->SetFont("Arial", "B", 7);
			$this->Cell(65,	1,'(Signature over Printed name)',0,0,'C');
			$this->Cell(65,	1,'(Signature over Printed name)',0,0,'C');
			$this->Cell(65,	1,'(Signature over Printed name)',0,0,'C');
			$this->Ln(8);
			// $this->Cell(105,8,'Received by:',0,0,'L');
			// $this->Cell(80,8,'Released by:',0,0,'L');
			// $this->Ln(8);
			// $this->SetFont("Arial", "B", 10);
			// $this->Cell(80,	8,ucwords($data->institutr_receivedby),0,0,'C');
			// $this->Cell(34,8,'',0,0,'C');
			// $this->Cell(60,8,ucwords($data->relby),0,0,'C');
			// $this->Ln(4);
			// $this->SetFont("Arial", "", 10);
			// $this->Cell(18,	1,'',0,0,'R');
			// $this->Cell(50,	1,'______________________________',0,0,'C');
			// $this->Cell(36,	1,'',0,0,'C');
			// $this->Cell(80,	1,'______________________________',0,0,'C');
			// $this->Ln(5);
			// $this->SetFont("Arial", "B", 7);
			// $this->Cell(13,	1,'',0,0,'C');
			// $this->Cell(60,	1,'(Signature over Printed name)',0,0,'C');
			// $this->Cell(41,	1,'',0,0,'C');
			// $this->Cell(60,	1,'(Signature over Printed name)',0,0,'C');
			// $this->Ln(8);
			// $this->SetFont("Arial", "", 10);
			// $this->Cell(105,8,'',0,0,'L');
			// $this->Cell(80,8,'Checked by:',0,0,'L');
			// $this->Ln(8);
			// $this->SetFont("Arial", "B", 10);
			// $this->Cell(80,	8,'',0,0,'C');
			// $this->Cell(34,8,'',0,0,'C');
			// $this->Cell(60,8,ucwords($data->institutr_checkedby),0,0,'C');
			// $this->Ln(4);
			// $this->SetFont("Arial", "", 10);
			// $this->Cell(18,	1,'',0,0,'R');
			// $this->Cell(50,	1,'',0,0,'C');
			// $this->Cell(36,	1,'',0,0,'C');
			// $this->Cell(80,	1,'______________________________',0,0,'C');
			// $this->Ln(5);
			// $this->SetFont("Arial", "B", 7);
			// $this->Cell(13,	1,'',0,0,'C');
			// $this->Cell(60,	1,'',0,0,'C');
			// $this->Cell(41,	1,'',0,0,'C');
			// $this->Cell(60,	1,'(Signature over Printed name)',0,0,'C');

		}
	
	}

