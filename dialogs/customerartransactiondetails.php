<?php
	session_start();
	include '../function.php';

	if(isset($_GET['trid']))
		$trid = $_GET['trid'];
	else 
		exit();

	$select = 'transaction_stores.trans_datetime,
		transaction_stores.trans_sid,
		transaction_stores.trans_number,
		transaction_payment.payment_amountdue,
		transaction_payment.payment_docdisc,
		transaction_payment.payment_linediscount,
		transaction_payment.payment_internal_discount,
		transaction_payment.payment_stotal,
		stores.store_name,
		store_staff.ss_firstname,
		store_staff.ss_lastname';
	$where = "transaction_stores.trans_sid='".$trid."' AND transaction_stores.trans_type='3'";

	$join = 'INNER JOIN
		transaction_payment
	ON
		transaction_payment.payment_trans_num = transaction_stores.trans_sid
	INNER JOIN
		stores
	ON
		stores.store_id = transaction_stores.trans_store
	INNER JOIN
		store_staff
	ON
		store_staff.ss_id = transaction_stores.trans_store
	';
	$cc = getSelectedData($link,'transaction_stores',$select,$where,$join,'');

	$select = "transaction_sales.sales_barcode,
		denomination.denomination";
	$where = "transaction_sales.sales_transaction_id=".$trid;
	$join = "INNER JOIN
		denomination
	ON
		denomination.denom_id = transaction_sales.sales_denomination
	LEFT JOIN
		transaction_linediscount
	ON
		transaction_linediscount.trlinedis_sid = transaction_sales.sales_transaction_id";

	$bb = getAllData($link,'transaction_sales',$select,$where,$join,'');
?>
<div class="row">
	<div class="col-xs-6">
		<div class="form-horizontal">
			<div class="form-group">
				<label class="control-label col-xs-6 tleft" >Transaction #:</label>
				<div class="col-xs-6">
					<input type="text" class="form-control input-sm inptxt" value="<?php echo addZeroToStringZ($cc->trans_number,10)
?>" disabled="disabled" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-xs-6 tleft" >Subtotal:</label>
				<div class="col-xs-6">
					<input type="text" class="form-control input-sm inptxt tright" value="<?php echo number_format($cc->payment_stotal,2); ?>" disabled="disabled" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-xs-6 tleft" >Total Line Disc:</label>
				<div class="col-xs-6">
					<input type="text" class="form-control input-sm inptxt tright" value="- <?php echo number_format($cc->payment_linediscount,2); ?>" disabled="disabled" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-xs-6 tleft" >Transaction Disc:</label>
				<div class="col-xs-6">
					<input type="text" class="form-control input-sm inptxt tright" value="- <?php echo number_format($cc->payment_docdisc,2); ?>" disabled="disabled" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-xs-6 tleft" >Customer Discount:</label>
				<div class="col-xs-6">
					<input type="text" class="form-control input-sm inptxt tright" value="- <?php echo number_format($cc->payment_internal_discount,2); ?>" disabled="disabled" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-xs-6 tleft" >Total Payment:</label>
				<div class="col-xs-6">
					<input type="text" class="form-control input-sm inptxt tright" value="<?php echo number_format($cc->payment_amountdue,2); ?>" disabled="disabled" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-xs-6 tleft" >Cashier:</label>
				<div class="col-xs-6">
					<input type="text" class="form-control input-sm inptxt" value="<?php echo ucwords($cc->ss_firstname.' '.$cc->ss_lastname); ?>" disabled="disabled" />
				</div>
			</div>			
		</div>
	</div>
	<div class="col-xs-6">
		<table class="table" id="transbarcode">
			<thead>
				<tr>
					<th>Barcode No.</th>
					<th>Denom</th>
					<th>Discount</th>
				</tr>
				<tbody>
					<?php foreach ($bb as $b): ?>
						<tr>
							<td><?php echo $b->sales_barcode; ?></td>
							<td><?php echo number_format($b->denomination,2); ?></td>
							<td>
								<?php 
									$select = 'IFNULL(SUM(transaction_linediscount.trlinedis_discamt),0.00) as linedisc';
									$where = "transaction_linediscount.trlinedis_sid = '".$trid."' 
										AND
											transaction_linediscount.trlinedis_barcode='".$b->sales_barcode."'";
									$ld = getSelectedData($link,'transaction_linediscount',$select,$where,'','');

									echo $ld->linedisc;
								?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</thead>
		</table>
	</div>
</div>
<script type="text/javascript">
    $('#transbarcode').dataTable( {
        "pagingType": "full_numbers",
        "ordering": false,
        "processing": true
    });
</script>