<?php 

	require('reports.php');
	if(!isset($_SESSION['gccashier_id']) || !isset($_SESSION['gc_super_id']))
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

	$ip = get_ip_address();
	// check if transaction exist.
	$query = $link->query(
		"SELECT 
			trans_eos 
		FROM 
			transaction_stores 
		WHERE 
			trans_ip_address = '".$ip."'
		AND
			trans_store='".$_SESSION['gccashier_store']."'
		AND
			trans_eos!=''
		ORDER BY 
			trans_sid 
		DESC
		LIMIT 1
	");

	if(!$query){
		echo $link->error;
		exit();
	}

	$pdf->setReportType(1);
	$storename = getStoreName($link,$_SESSION['gccashier_store']);
	$pdf->docHeaderStoreSalesReport($storename);
	if($query->num_rows > 0)
	{
		$row = $query->fetch_object();
		//get Cashier and Date 

		$query_sel = $link->query(
			"SELECT 
				end_of_shift_pos_details.eosdatetime,
				CONCAT(store_staff.ss_firstname,' ',store_staff.ss_lastname) as cashier				
			FROM 
				end_of_shift_pos_details 
			INNER JOIN
				store_staff
			ON
				store_staff.ss_id = end_of_shift_pos_details.eoscashier
			WHERE 
				end_of_shift_pos_details.eos_id = '$row->trans_eos'
		");

		if($query_sel)
		{

			$row_info = $query_sel->fetch_object();
			$pdf->subheaderStoreSalesReport($row_info->cashier, $row_info->eosdatetime);
			$query_tr = $link->query(
				"SELECT
					transaction_stores.trans_sid,
					transaction_stores.trans_number,
					transaction_stores.trans_datetime,
					transaction_stores.trans_type
				FROM 
					transaction_stores 
				WHERE 
					transaction_stores.trans_eos='".$row->trans_eos."'	
			");	

			if($query_tr)
			{
				while($row_tr = $query_tr->fetch_object())
				{
					switch ($row_tr->trans_type) 
					{
						case 1:
							$trtype = 'GC Sales (Cash)';
							break;
						case 2:
							$trtype = 'GC Sales (Credit Card)';
							break;
						case 3:
							// get ar type and discount
							$query_ar = $link->query(
								"SELECT 
									transaction_stores.trans_sid,
									transaction_payment.payment_internal_discount,
									customer_internal.ci_group
								FROM
									transaction_stores
								INNER JOIN
									transaction_payment
								ON
									transaction_payment.payment_trans_num = transaction_stores.trans_sid
								INNER JOIN
									customer_internal_ar
								ON
									customer_internal_ar.ar_trans_id = transaction_stores.trans_sid
								INNER JOIN
									customer_internal
								ON
									customer_internal.ci_code = customer_internal_ar.ar_cuscode
								WHERE
									transaction_stores.trans_sid='".$row_tr->trans_sid."'
							");
							if($query)
							{
								$row_ar = $query_ar->fetch_object();
								$cusdisc = $row_ar->payment_internal_discount;
								if($row_ar->ci_group==1)
								{
									$trtype = 'GC Sales (Head Office)';
								}
								else 
								{
									$trtype = 'GC Sales (Subsidiary Admin)';
								}
							}
							else 
							{
								echo $link->error;
							}
							
							break;
						case 5: 
							$trtype = 'GC Refund';
						break;
						case 6:
							$trtype = 'GC Revalidation';
						break;
						default:
							$trtype = 'Unknown';
							break;
					}

					if($row_tr->trans_type==1 || $row_tr->trans_type==2 || $row_tr->trans_type==3)
					{
						$pdf->itemSalesReportCashHeader($row_tr->trans_number,$row_tr->trans_datetime,$trtype);
						$query_items = $link->query(
							"SELECT 
								transaction_sales.sales_barcode,
								denomination.denomination,
								gc_type.gctype
							FROM 
								transaction_sales
							INNER JOIN
								denomination
							ON
								denomination.denom_id = transaction_sales.sales_denomination
							INNER JOIN
								gc_type
							ON
								gc_type.gc_type_id = transaction_sales.sales_gc_type
							WHERE 
								transaction_sales.sales_transaction_id='".$row_tr->trans_sid."'	
						");

						if($query_items)
						{
							$stotal = 0;
							$linedisc = 0;
							$netamt = 0;
							while ($row_items = $query_items->fetch_object()) 
							{				
								$stotal += $row_items->denomination;
								$query_linedisc = $link->query(
									"SELECT 
										IFNULL(SUM(trlinedis_discamt),0) as linedisc 
									FROM 
										transaction_linediscount 
									WHERE 
										trlinedis_barcode='".$row_items->sales_barcode."' 
									AND 
										trlinedis_sid='".$row_tr->trans_sid."'
								");

								if($query_linedisc)
								{									
									$row_linedisc = $query_linedisc->fetch_object();
									$linedisc += $row_linedisc->linedisc;
									$netamt = $row_items->denomination - $row_linedisc->linedisc;
									$pdf->itemSalesReportItems($row_items->sales_barcode,$row_items->denomination,$row_linedisc->linedisc,$netamt);						
								}
								else 
								{
									echo $link->error;
									exit();
								}
							}
							// get transaction discount
							$query_trdisc = $link->query(
								"SELECT 
									IFNULL(SUM(trdocdisc_amnt),0) as trdisct
								FROM 
									transaction_docdiscount 
								WHERE 
									trdocdisc_trid='".$row_tr->trans_sid."'
							");
							if($query_trdisc)
							{
								$row_trdisc = $query_trdisc->fetch_object();
								$pdf->tranDiscount($row_trdisc->trdisct);	
								$stotal = $stotal - ($row_trdisc->trdisct + $linedisc);
								if($row_tr->trans_type==3)
								{
									// get ar discount 
									$pdf->customerDiscount($cusdisc);
									$stotal = $stotal - $cusdisc;
								}
								$pdf->transAmountDue($stotal);
							}
							else 
							{
								echo $link->error;
								exit();
							}
						}
						else 
						{
							echo $link->error;
							exit();						
						}
					}
					elseif ($row_tr->trans_type==6) 
					{
						$pdf->itemRevalidationPaymentHeader($row_tr->trans_number,$row_tr->trans_datetime,$trtype);

						$query_trreval = $link->query(
							"SELECT
								transaction_revalidation.reval_barcode,
								denomination.denomination,
								gc.gc_ispromo,
								gc_type.gctype,
								store_verification.vs_date
							FROM 
								transaction_revalidation
							INNER JOIN
								gc
							ON
								gc.barcode_no = transaction_revalidation.reval_barcode
							INNER JOIN
								denomination
							ON
								denomination.denom_id = transaction_revalidation.reval_denom
							INNER JOIN
								store_verification
							ON
								store_verification.vs_barcode = transaction_revalidation.reval_barcode
							LEFT JOIN
								gc_location
							ON
								gc_location.loc_barcode_no = transaction_revalidation.reval_barcode
							LEFT JOIN
								gc_type
							ON
								gc_type.gc_type_id = gc_location.loc_gc_type
							WHERE 
								transaction_revalidation.reval_trans_id = '".$row_tr->trans_sid."'
						");

						if($query_trreval)
						{
							$query_revalpay = $link->query(
								"SELECT
									payment_amountdue
								FROM 
									transaction_payment 
								WHERE 
									payment_trans_num='".$row_tr->trans_sid."'
							");

							if($query_revalpay)
							{
								$row_rpay = $query_revalpay->fetch_object();
								$payment = $row_rpay->payment_amountdue / $query_trreval->num_rows;
							}
							else 
							{
								echo $link->error;
								exit();
							}

							while ($row_trreval = $query_trreval->fetch_object()) 
							{								
								$pdf->itemRevalidationItems($row_trreval->reval_barcode,$row_trreval->vs_date,$row_trreval->denomination,$row_trreval->gc_ispromo,$row_trreval->gctype,$payment);
							}
							$pdf->transAmountDue($row_rpay->payment_amountdue);							
						}
						else 
						{
							echo $link->error;
							exit();
						}
					}
					elseif ($row_tr->trans_type==5)
					{
						$reftotalamt = 0;
						$pdf->itemRefundHeader($row_tr->trans_number,$row_tr->trans_datetime,$trtype);

						$query_refund = $link->query(
							"SELECT 
								transaction_refund.refund_barcode,
								denomination.denomination,
								transaction_refund.refund_linedisc,
								transaction_refund.refund_sdisc
							FROM 
								transaction_refund 
							INNER JOIN
								denomination
							ON
								denomination.denom_id = transaction_refund.refund_denom
							WHERE 
								transaction_refund.refund_trans_id='".$row_tr->trans_sid."'
						");

						if($query_refund)
						{							
							while ($row_refitem = $query_refund->fetch_object()) 
							{
								$refamt = $row_refitem->denomination - ($row_refitem->refund_linedisc + $row_refitem->refund_sdisc);
								$pdf->itemRefundItems($row_refitem->refund_barcode,$row_refitem->denomination,$row_refitem->refund_linedisc,$row_refitem->refund_sdisc,$refamt);
								$reftotalamt += $refamt;
							}
						}
						else
						{
							echo $link->error;
							exit();
						}
						$query_scharge = $link->query(
							"SELECT
								transaction_refund_details.trefundd_servicecharge
							FROM 
								transaction_refund_details 
							WHERE 
								trefundd_trstoresid='".$row_tr->trans_sid."'
						");

						if($query_scharge)
						{
							$row_srcharge = $query_scharge->fetch_object();
							$reftotalamt = $reftotalamt - $row_srcharge->trefundd_servicecharge;
							$pdf->refundServiceCharge($row_srcharge->trefundd_servicecharge);
						}
						else 
						{
							echo $link->error;
							exit();
						}

						$pdf->refundAmount($reftotalamt);			
					}
				}				
			}
			else 
			{
				echo $link->error;
				exit();
			}			
		}
		else 
		{
			echo $link->error;
			exit();
		}

	}
	else 
	{
		$pdf->noTransaction();
	}


	// $pdf->SetAutoPageBreak(false);
	// $height_of_cell = 60; // mm
	// $page_height = 286.93; // mm (portrait letter)
	// $bottom_margin = 0; // mm
	//   for($i=0;$i<=100;$i++) :
	//     $block=floor($i/6);
	//     $space_left=$page_height-($pdf->GetY()+$bottom_margin); // space left on page
	//       if ($i/6==floor($i/6) && $height_of_cell > $space_left) {
	//         $pdf->AddPage(); // page break
	//       }
	//     $pdf->Cell(100,10,'This is a text line - Group '.$block,'B',2);
	//   endfor;	
	// $pdf->Cell(100,10,'This is a text line - Group '.$pdf->GetY(),'B',2);
	// $pdf->Cell(100,10,'This is a text line - Group '.$pdf->GetY(),'B',2);
	// $pdf->Cell(100,10,'This is a text line - Group '.$pdf->GetY(),'B',2);
	// $pdf->Output();
	$pdf->Output('../reports/pos/gc_report'.$_SESSION['gccashier_store'].'.pdf','F');
?>

<script>
window.location = "<?php echo 'index.php?gcreport='.$_SESSION['gccashier_store']; ?>";
</script>