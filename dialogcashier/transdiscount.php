<?php
session_start();
include_once "../function-cashier.php";
//check if has document discount
$n = checkifhasdocdiscount($link);

// $amtdue = totalwithlinedisc($link);
$docdisc = docdiscount($link);
$subtotal = checkTotal($link);
$amtdue = $subtotal - $docdisc;

?>
<?php 
	// if(isset($_SESSION['gc_super_id'])): 
?>
<div class="row">
	<div class="col-xs-12 form-horizontal">
		<input type="hidden" name="amtdue" id="amtdue" value="<?php echo $subtotal; ?>">
		<input type="hidden" name="totval" id="totval" value="<?php echo $subtotal; ?>">
		<input type="hidden" name="conprnt" id="conprnt" value="0">
		<div class="form-group fg-nobot">
			<label class="control-label col-xs-4 lbl-c normalabel">Amt Due</label>
			<div class="col-xs-8">
				<input type="text" class="form-control inpmed normal" id="" value="<?php echo number_format($subtotal,2); ?>" disabled=true>
			</div>
		</div>
		<div class="form-group fg-nobot">
			<label class="control-label col-xs-4 lbl-c normalabel">Discount Type</label>
			<div class="col-xs-5">
				<select class="form-control inpmed normal" name="discountype" id="discountype" onchange="discountypedoc(this.value)">
					<option value="">-Select-</option>
					<option value="1">Percent</option>
					<option value="2">Amount</option>
				</select>
			</div>
		</div>
		<div class="form-group fg-nobot">
			<label class="control-label col-xs-4 lbl-c normalabel">Percent</label>
			<div class="col-xs-5">
				<!-- <input type="text" data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false,'placeholder': '0.00'" class="form-control normal" name="percent" readonly="readonly" onkeyup="linedispercent(this.value);"> -->
				<input type="text" data-inputmask="'alias': 'integer', 'groupSeparator': ',', 'autoGroup': true, 'placeholder': '0','allowMinus':false" class="form-control inpmed normal" name="percent" id="percent" readonly="readonly" onkeyup="docdispercent(this.value);">
			</div>				
		</div>
		<div class="form-group fg-nobot">
			<label class="control-label col-xs-4 lbl-c normalabel">Amount</label>
			<div class="col-xs-5">
				<!-- <input type="text" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false,'placeholder': '0.00'" class="form-control normal" name="amount" id="amount" readonly="readonly"> -->
				<input type="text" data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true, 'placeholder': '0.00','allowMinus':false" class="form-control inpmed normal" name="amount" id="amount" readonly="readonly" onkeyup="docamount(this.value);"``>
			</div>				
		</div>
		<div class="form-group fg-nobot">
			<label class="control-label col-xs-4 lbl-c normalabel"></label>
			<div class="col-xs-5">
				<input type="text" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false,'placeholder': '0.00','allowMinus':false" value="<?php echo number_format($amtdue,2); ?>" class="form-control inpmed normal" name="tot" id="tot" readonly="readonly">
			</div>				
		</div>
		<div class="response">
		</div>
	</div>
</div>
<?php 
	// endif; 
?>
<script>
  $('input[name=percent],input[name=amount]').inputmask();
  $('#discountype').focus();
</script>
