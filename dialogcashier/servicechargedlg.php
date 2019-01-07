<?php 
	session_start();
	include_once '../function-cashier.php';
?>

<div class="row">
	<div class="col-xs-12 form-horizontal">
		<div class="form-group">
			<label class="col-xs-5 control-label lbl-c lblsm scharge">Service Charge:</label>
			<div class="col-xs-7">
				<input type="text" id="paymentcash" class="form form-control paycash" value="0" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false">
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