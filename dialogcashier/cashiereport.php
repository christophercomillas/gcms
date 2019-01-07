<?php
	session_start();
	include '../function.php';
	include '../function-cashier.php';
	$datetime = getuserlogs($link,$_SESSION['gccashier_id']);
?>
<div class="row">
	<div class="col-xs-12 form-horizontal">
		<div class="form-group fg-nobot">
			<label class="control-label col-xs-4 lbl-c normalabel">Cashier: </label>
			<div class="col-xs-8">
				<input type="text" class="form-control inpmed normal" readonly="readonly" tabIndex="-1" value="<?php echo ucwords($_SESSION['gccashier_fullname']); ?>">
			</div>
		</div>
		<div class="form-group fg-nobot">
			<label class="control-label col-xs-4 lbl-c normalabel">Login Date: </label>
			<div class="col-xs-8">
				<input type="text" class="form-control inpmed normal" readonly="readonly" tabIndex="-1" value="<?php echo _dateFormat($datetime); ?>">
			</div>			
		</div>
		<div class="form-group fg-nobot">
			<label class="control-label col-xs-4 lbl-c normalabel">Login Time: </label>
			<div class="col-xs-8">
				<input type="text" class="form-control inpmed normal" readonly="readonly" tabIndex="-1" value="<?php echo _timeFormat($datetime); ?>">
			</div>			
		</div>
		<div class="response">
		</div>
	</div>
</div>
<script>

</script>