<?php 
	session_start();
	include '../function.php';
	if(isset($_GET['trid']) && isset($_GET['trtype'])){
		$trid = $_GET['trid'];
		$trtype = $_GET['trtype'];
	}
	else 
	{
		exit();
	}
?>
<div class="row">
<?php if($trtype==1): ?>
	<?php
		$select = 'store_received.srec_recid,
			store_received.srec_at,
			store_received.srec_checkedby,
			store_received.srec_rel_id,
			users.firstname,
			users.lastname';
		$join = 'INNER JOIN 
				users
			ON
				users.user_id = store_received.srec_by';
		$where = "store_received.srec_id = '".$trid."'
			AND
				store_received.srec_store_id = '".$_SESSION['gc_store']."'";
		$dd = getSelectedData($link,'store_received',$select,$where,$join,''); 
		if(count($dd)==0)
		exit();


		$select = "COUNT(store_received_gc.strec_barcode) as cnt,
			denomination.denomination,
			store_received_gc.strec_denom";
		$join = "INNER JOIN
			denomination
		ON
			denomination.denom_id = store_received_gc.strec_denom";
		$where = "store_received_gc.strec_recnum='".$dd->srec_recid."'
			AND
				store_received_gc.strec_storeid='".$_SESSION['gc_store']."'
			GROUP BY
				store_received_gc.strec_denom";
		
		$dgc = getAllData($link,'store_received_gc',$select,$where,$join,'');

	?>
	<div class="col-xs-6 form-horizontal">
		<div class="form-groupwrap">
			<div class="form-group">
				<label class="col-sm-5 control-label">GC Receiving No.: </label>
				<div class="col-sm-7">
					<input type="text" class="form-control input-sm inptxt reqfield" value="<?php echo addZeroToStringZ($dd->srec_recid,5); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Date Received: </label>
				<div class="col-sm-7">
					<input type="text" class="form-control input-sm inptxt reqfield" value="<?php echo _dateFormat($dd->srec_at);?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Received By: </label>
				<div class="col-sm-7">
					<input type="text" class="form-control input-sm inptxt reqfield" value="<?php echo ucwords($dd->firstname.' '.$dd->lastname); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Checked By: </label>
				<div class="col-sm-7">
					<input type="text" class="form-control input-sm inptxt reqfield" value="<?php echo ucwords($dd->srec_checkedby); ?>" readonly="readonly">
				</div>
			</div>
		</div>
	</div>
	<div class="col-xs-6">
		<table class="table">
			<thead>
				<tr>
					<th>Denomination</th>
					<th>Qty</th>
					<th>Total</th>
					<th>View</th>
				</tr>
				<tbody>
					<?php foreach ($dgc as $d): ?>
						<tr>
							<td><?php echo number_format($d->denomination,2); ?></td>
							<td><?php echo number_format($d->cnt); ?></td>
							<td><?php echo number_format($d->cnt * $d->denomination,2); ?></td>
							<td><i class="fa fa-fa fa-eye faeye" title="View" onclick="gcreleasedperdenom(<?php echo $d->strec_denom.','.$dd->srec_rel_id.','.$d->denomination; ?>)"></i></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</thead>
		</table>
	</div>
<?php 
	elseif($trtype==2): 

	// get transaction type
	$select = "transaction_stores.trans_type,
		transaction_stores.trans_number,
		transaction_stores.trans_datetime,
		transaction_stores.trans_type,
		stores.store_name,
		store_staff.ss_firstname,
		store_staff.ss_lastname,
		IFNULL(transaction_docdiscount.trdocdisc_amnt,0.00) as docdisc
		";
	$where = "transaction_stores.trans_sid='".$trid."' 
		AND
			transaction_stores.trans_store='".$_SESSION['gc_store']."'";
	$join = "INNER JOIN
			stores
		ON
			stores.store_id = transaction_stores.trans_store
		INNER JOIN
			store_staff
		ON
			store_staff.ss_id = transaction_stores.trans_cashier
		LEFT JOIN
			transaction_docdiscount
		ON
			transaction_docdiscount.trdocdisc_trid = transaction_stores.trans_sid
		";
		
	$dd = getSelectedData($link,'transaction_stores',$select,$where,$join,'');
	if(count($dd)==0)
		exit();

	$select = 'IFNULL(SUM(trlinedis_discamt),0.00) as totline';
	$where = 'trlinedis_sid='.$trid;
	$totlinedisc = getSelectedData($link,'transaction_linediscount',$select,$where,'','');

	$select ='IFNULL(SUM(denomination.denomination),0.00) as payment';
	$where ='transaction_sales.sales_transaction_id='.$trid;
	$join = "INNER JOIN
			denomination
		ON
			denomination.denom_id = transaction_sales.sales_denomination";
	$tpayment = getSelectedData($link,'transaction_sales',$select,$where,$join,'');

	$amtdue = $tpayment->payment - ($totlinedisc->totline + $dd->docdisc);

	$select = '	transaction_sales.sales_barcode,
		denomination.denomination,
		transaction_sales.sales_transaction_id,
		transaction_sales.sales_denomination';
	$where = 'transaction_sales.sales_transaction_id='.$trid;
	$join = 'INNER JOIN
			denomination
		ON
			denomination.denom_id = transaction_sales.sales_denomination';
	$ad = getAllData($link,'transaction_sales',$select,$where,$join,'');

	if($dd->trans_type==1)
	{
		$trtype = 'Cash';

		$select = "payment_cash, 
			payment_change";
		$where = 'payment_trans_num='.$trid;
		$cash = getSelectedData($link,'transaction_payment',$select,$where,'','');

	}
	elseif($dd->trans_type==2)
	{
		$trtype = 'Card';

		$select = "creditcard_payment.cc_cardnumber,
			creditcard_payment.cc_cardexpired,
			credit_cards.ccard_name";
		$where = "creditcard_payment.cctrans_transid=".$trid;
		$join = "INNER JOIN
				credit_cards
			ON
				credit_cards.ccard_id = creditcard_payment.cc_creaditcard";
		$card = getSelectedData($link,'creditcard_payment',$select,$where,$join,'');

	}
	elseif($dd->trans_type==3)
	{
		$type = array('','Supplier','Customer','V.I.P.');
		$select = "SUM(customer_internal_ar.ar_dbamt) - SUM(customer_internal_ar.ar_cramt) as tot,
			customer_internal_ar.ar_cuscode,
			customer_internal_ar.ar_adj,
			customer_internal.ci_group,
			customer_internal.ci_type,
			customer_internal.ci_name,
			transaction_payment.payment_internal_discount";

		$where = "customer_internal_ar.ar_trans_id = '".$trid."' AND ar_type='1'";

		$join = "INNER JOIN
				customer_internal
			ON
				customer_internal.ci_code = customer_internal_ar.ar_cuscode
			INNER JOIN
				transaction_payment
			ON
				transaction_payment.payment_trans_num = customer_internal_ar.ar_trans_id";

		$ar = getSelectedData($link,'customer_internal_ar',$select,$where,$join,'');

		if(count($ar)==0)
			exit();

		if($ar->ci_group==1)
			$trtype = 'Head Office';
		else 
			$trtype = 'Subsidiary Admin';

		$amtdue = $tpayment->payment - $ar->ar_adj ;
	}
?>
	<div class="col-xs-6 form-horizontal">
		<div class="header"><?php echo $trtype; ?></div>
		<div class="form-groupwrap">
			<div class="form-group">
				<label class="col-sm-5 control-label">Transaction #: </label>
				<div class="col-sm-7">
					<input type="text" class="form-control input-sm inptxt reqfield" value="<?php echo $dd->trans_number; ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label ">Subtotal: </label>
				<div class="col-sm-7">
					<input type="text" class="form-control input-sm inptxt tright " value="<?php echo number_format($tpayment->payment,2); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Total Line Disc: </label>
				<div class="col-sm-7">
					<input type="text" class="form-control input-sm inptxt tright" value="<?php echo '- '.number_format($totlinedisc->totline,2); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Subtotal Disc: </label>
				<div class="col-sm-7">
					<input type="text" class="form-control input-sm inptxt tright" value="<?php echo is_null($dd->docdisc) ? '- 0.00' : '- '.$dd->docdisc; ?>" readonly="readonly">
				</div>
			</div>
			<?php if($dd->trans_type =='3'): ?>
				<div class="form-group">
					<label class="col-sm-5 control-label">Customer Disc: </label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm inptxt tright" value="- <?php echo number_format($ar->payment_internal_discount,2); ?>" readonly="readonly">
					</div>
				</div>				
			<?php endif;?>
			<div class="form-group">
				<label class="col-sm-5 control-label">Total Payment: </label>
				<div class="col-sm-7">
					<input type="text" class="form-control input-sm inptxt tright" value="<?php echo number_format($amtdue,2); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Cashier: </label>
				<div class="col-sm-7">
					<input type="text" class="form-control input-sm inptxt" value="<?php echo ucwords($dd->ss_firstname.' '.$dd->ss_lastname); ?>" readonly="readonly">
				</div>
			</div>

			<?php if($dd->trans_type==1): ?>
				<div class="form-group">
					<label class="col-sm-5 control-label">Amount Tender: </label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm inptxt tright" value="<?php echo number_format($cash->payment_cash,2); ?>" readonly="readonly">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-5 control-label">Change: </label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm inptxt tright" value="<?php echo number_format($cash->payment_change,2); ?>" readonly="readonly">
					</div>
				</div>
			<?php elseif($dd->trans_type==2): ?>
				<div class="form-group">
					<label class="col-sm-5 control-label">Credit Card: </label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm inptxt tright" value="<?php echo $card->ccard_name; ?>" readonly="readonly">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-5 control-label">Card Number: </label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm inptxt tright" value="<?php echo $card->cc_cardnumber; ?>" readonly="readonly">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-5 control-label">Card Expired: </label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm inptxt tright" value="<?php echo _dateFormat($card->cc_cardexpired); ?>" readonly="readonly">
					</div>
				</div>
			<?php elseif($dd->trans_type==3): ?>
				<div class="form-group">
					<label class="col-sm-5 control-label">Customer: </label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm inptxt" value="<?php echo ucwords($ar->ci_name); ?>" readonly="readonly">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-5 control-label">Type: </label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm inptxt" value="<?php echo ucwords($type[$ar->ci_type]); ?>" readonly="readonly">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-5 control-label">Balance: </label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm inptxt tright" value="<?php echo number_format(get_ar_balance($link,$trid),2); ?>" readonly="readonly">
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<div class="col-xs-6">
		<table class="table" id="gcsold">
			<thead>
				<tr>
					<td>Barcode #</td>
					<td>Denomination</td>
					<td>Discount</td>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($ad as $ads): ?>
					<tr>
						<td><?php echo $ads->sales_barcode; ?></td>
						<td><?php echo number_format($ads->denomination,2); ?></td>
						<td>
								<?php 
									$select = 'IFNULL(SUM(transaction_linediscount.trlinedis_discamt),0.00) as linedisc';
									$where = "transaction_linediscount.trlinedis_sid = '".$trid."' 
										AND
											transaction_linediscount.trlinedis_barcode='".$ads->sales_barcode."'";
									$ld = getSelectedData($link,'transaction_linediscount',$select,$where,'','');

									echo $ld->linedisc;
								?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php elseif ($trtype==3): ?>
	<?php 
 
		$select = "transaction_payment.payment_amountdue,
			transaction_payment.payment_cash,
			transaction_payment.payment_change";
		$where = "transaction_payment.payment_trans_num = ".$trid;
		$p = getSelectedData($link,'transaction_payment',$select,$where,'','');

		$select = "reval_barcode,
			reval_denom";
		$where = "reval_trans_id=".$trid;
		$join = "";
		$revalgc = getAllData($link,'transaction_revalidation',$select,$where,$join,'');

	?>
	<div class="col-xs-6 form-horizontal" id="gcreval">
		<div class="form-group">
			<label class="col-sm-7 control-label">Payment: </label>
			<div class="col-sm-5">
				<input type="text" class="form-control input-sm inptxt tright" value="<?php echo number_format($p->payment_amountdue,2); ?>" readonly="readonly">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-7 control-label">Amount Tender: </label>
			<div class="col-sm-5">
				<input type="text" class="form-control input-sm inptxt tright" value="<?php echo number_format($p->payment_cash,2); ?>" readonly="readonly">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-7 control-label">Change: </label>
			<div class="col-sm-5">
				<input type="text" class="form-control input-sm inptxt tright" value="<?php echo number_format($p->payment_change,2); ?>" readonly="readonly">
			</div>
		</div>
	</div>
	<div class="col-xs-6">
		<table class="table" id="gcsold">
			<thead>
				<tr>
					<th>Barcode</th>
					<th>Denomination</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($revalgc as $rgc): ?>
					<tr>
						<td><?php echo $rgc->reval_barcode; ?></td>
						<td><?php echo number_format($rgc->reval_denom,2); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

<?php elseif ($trtype==4): ?>
	<?php 
		$select = "	transaction_stores.trans_number,
			CONCAT(store_staff.ss_firstname,' ',store_staff.ss_lastname) as cashier,
			transaction_refund_details.trefundd_totgcrefund,
			transaction_refund_details.trefundd_total_linedisc,
			transaction_refund_details.trefundd_subtotal_disc,
			transaction_refund_details.trefundd_servicecharge,
			transaction_refund_details.trefundd_refundamt";
		$where = "transaction_stores.trans_sid = ".$trid;
		$join = "INNER JOIN
				store_staff
			ON
				store_staff.ss_id = transaction_stores.trans_cashier
			INNER JOIN
				transaction_refund_details
			ON
				transaction_refund_details.trefundd_trstoresid = transaction_stores.trans_sid";
		$ref = getSelectedData($link,'transaction_stores',$select,$where,$join,'');

		$select = '	transaction_refund.refund_barcode,
			transaction_refund.refund_linedisc,
			transaction_refund.refund_sdisc,
			denomination.denomination';
		$where = 'transaction_refund.refund_trans_id='.$trid;
		$join = 'INNER JOIN
				denomination
			ON
				denomination.denom_id = transaction_refund.refund_denom';
		$refgc = getAllData($link,'transaction_refund',$select,$where,$join,'');
	?>
	<div class="col-xs-5 form-horizontal" id="gcreval">
		<div class="form-groupwrap">
			<div class="form-group">
				<label class="col-sm-6 control-label">Transaction #: </label>
				<div class="col-sm-6">
					<input type="text" class="form-control input-sm inptxt reqfield" value="<?php echo $ref->trans_number; ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Subtotal</label>
				<div class="col-sm-6">
					<input type="text" class="form-control input-sm inptxt reqfield tright" value="<?php echo number_format($ref->trefundd_totgcrefund,2); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Total Line Disc</label>
				<div class="col-sm-6">
					<input type="text" class="form-control input-sm inptxt reqfield tright" value="- <?php echo number_format($ref->trefundd_total_linedisc,2); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Transaction Disc</label>
				<div class="col-sm-6">
					<input type="text" class="form-control input-sm inptxt reqfield tright" value="- <?php echo number_format($ref->trefundd_subtotal_disc,2); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Service Charge</label>
				<div class="col-sm-6">
					<input type="text" class="form-control input-sm inptxt reqfield tright" value="- <?php echo number_format($ref->trefundd_servicecharge,2); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Refund Amount</label>
				<div class="col-sm-6">
					<input type="text" class="form-control input-sm inptxt reqfield tright" value="<?php echo number_format($ref->trefundd_refundamt,2); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Cashier</label>
				<div class="col-sm-6">
					<input type="text" class="form-control input-sm inptxt reqfield" value="<?php echo ucwords($ref->cashier); ?>" readonly="readonly">
				</div>
			</div>
		</div>
	</div>
	<div class="col-xs-7">
		<table class="table" id="gcsold">
			<thead>
				<tr>
					<th>Barcode</th>
					<th>Denom</th>
					<th>Line Disc</th>
					<th>Trans Disc</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($refgc as $rgc): ?>
					<tr>
						<td><?php echo $rgc->refund_barcode; ?></td>
						<td><?php echo number_format($rgc->denomination,2); ?></td>
						<td><?php echo number_format($rgc->refund_linedisc,2); ?></td>
						<td><?php echo number_format($rgc->refund_sdisc,2); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php endif; ?>
</div>
<script type="text/javascript">
    $('#gcsold').dataTable({
        "pagingType": "full_numbers",
        "ordering": false,
        "processing": true
    });
    $("#allocated-gc_length").css("display", "none");
</script>