<?php
session_start();
include_once "../function-cashier.php";	
?>
<?php 
	// if(isset($_SESSION['gc_super_id'])): 
?>
<div class="row">
	<div class="col-xs-12 form-horizontal">
		<input type="hidden" name="distypec" id="distypec" value="0">
		<input type="hidden" name="den" id="den" value="0">
		<input type="hidden" name="totval" id="totval" value="0">
		<input type="hidden" name="flaglinedis" id="flaglinedis" value="0">
		<input type="hidden" name="conpercent" id="conpercent" value="0">
		<div class="form-group fg-nobot">
			<label class="control-label col-xs-4 lbl-c normalabel">Barcode</label>
			<div class="col-xs-8">
				<input type="text" data-inputmask="'alias': 'numeric', 'groupSeparator': '', 'autoGroup': false, 'digits': 0, 'digitsOptional': false,'placeholder': '','allowMinus':false" class="form-control normal inpmed" maxlength="13" name="barcodefordis" id="barcodefordis">
			</div>				
		</div>
		<div class="form-group fg-nobot">
			<label class="control-label col-xs-4 lbl-c normalabel">Denomination</label>
			<div class="col-xs-5">
				<input type="text" name="denom" class="form-control inpmed normal" readonly="readonly" value="0" id="denom">
			</div>				
		</div>
		<div class="form-group fg-nobot">
			<label class="control-label col-xs-4 lbl-c normalabel">Discount Type</label>
			<div class="col-xs-5">
				<select class="form-control inpmed normal" name="discountype" id="discountype" onchange="discountype(this.value)" disabled="true">
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
				<input type="text" data-inputmask="'alias': 'integer', 'groupSeparator': ',', 'autoGroup': true, 'placeholder': '0','allowMinus':false" class="form-control inpmed normal" name="percent" id="percent" readonly="readonly" onkeyup="linedispercent(this.value);">
			</div>				
		</div>
		<div class="form-group fg-nobot">
			<label class="control-label col-xs-4 lbl-c normalabel">Amount</label>
			<div class="col-xs-5">
				<!-- <input type="text" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false,'placeholder': '0.00'" class="form-control normal" name="amount" id="amount" readonly="readonly"> -->
				<input type="text" data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true, 'placeholder': '0.00','allowMinus':false" class="form-control inpmed normal" name="amount" id="amount" readonly="readonly" onkeyup="lineamount(this.value);"``>
			</div>				
		</div>
		<div class="form-group fg-nobot">
			<label class="control-label col-xs-4 lbl-c normalabel"></label>
			<div class="col-xs-5">
				<input type="text" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false,'placeholder': '0.00','allowMinus':false" class="form-control inpmed normal" name="tot" id="tot" readonly="readonly">
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
  $('input[name=percent],input[name=amount],input[name=barcodefordis]').inputmask();
  $('input[name=barcodefordis]').focus();
</script>