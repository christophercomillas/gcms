<?php 
	session_start();
	include_once '../function-cashier.php';

	$refund = getTotalRefund($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id']);

?>
<div class="row">
	<div class="col-xs-12 form-horizontal">
		<input type="hidden" class="flag" value="1">
		<div class="form-group">
			<label class="col-xs-5 control-label lbl-c lblsm">Total GC Amount</label>
			<div class="col-xs-7">
				<input class="form form-control input-xs inpmed inpmed" type="text" readonly="readonly" id="tdenom" name="tdenom" value="<?php echo number_format($refund->denom,2); ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-xs-5 control-label lbl-c lblsm">Total Line Disc</label>
			<div class="col-xs-7">
				<input class="form form-control input-xs inpmed inpmed" type="text" readonly="readonly" id="tldisc" name="tldisc" value="<?php echo number_format($refund->totlinedisc,2); ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-xs-5 control-label lbl-c lblsm">Total Sub Disc</label>
			<div class="col-xs-7">
				<input class="form form-control input-xs inpmed inpmed" type="text" readonly="readonly" id="tsdisc" name="tsdisc" value="<?php echo number_format($refund->subdisc,2); ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-xs-5 control-label lbl-c lblsm">Total Refund Amount</label>
			<div class="col-xs-7">
				<input class="form form-control input-xs inpmed inpmed" type="text" readonly="readonly" id="tramt" name="tramt" value="<?php echo number_format($refund->rfundtot,2); ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-xs-5 control-label lbl-c lblsm">Service Charge:</label>
			<div class="col-xs-7">
				<input type="text" id="paymentcash" class="form form-control paycash"  data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false">
			</div>
		</div>
		<div class="response-sc">

		</div>
	</div>
</div>
<script>
  $('#paymentcash').inputmask();
  $('#paymentcash').select();     
</script>