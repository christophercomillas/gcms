<?php
	session_start();
	include '../function.php';
	if(isset($_GET['recid']))
		$id = $_GET['recid'];	
?>

<div class="row">
	<div class="col-xs-12">
		<form method="post" class="form-horizontal" action="../ajax.php?action=validategccustodianrange" id="srrvalidaterange">
			<input type="hidden" name="recnum" value="<?php echo $id; ?>">
			<input type="hidden" name="flag" value="1">
			<input type="hidden" name="dens" value="">
			<div class="form-group">
				<label class="col-xs-3 control-label">Receive No:</label>
				<div class="col-xs-3">
					<input type="text" class="form-control input-md" value="<?php echo $id; ?>" readonly="readonly">
				</div>
				<label class="col-xs-2 control-label">Date:</label>
				<div class="col-xs-4">
					<input type="text" class="form-control input-md" value="<?php echo _dateFormat($todays_date); ?>" readonly="readonly">
				</div>
			</div>
			<div class="rangeWrapper">
				<div class="form-group">
					<label class="col-xs-6 control-label cnt-right">Barcode Start</label>
					<label class="col-xs-6 control-label cnt-right">Barcode End</label>
				</div>
				<div class="form-group inputGcbarcode">
	<!-- 				<div class="col-xs-12">
						<input data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': ''" class="form-control input-lg input-validation" id="gcbarcode" name="gcbarcode" autocomplete="off" maxlength="13" />
					</div> -->
					<div class="col-xs-6">
						<input type="text" name="gcStart" class="form form-control validateGCstart" maxlength="13"> 
					</div>
					<div class="col-xs-6">
						<input type="text" name="gcEnd" class="form form-control validateGCend" maxlength="13" disabled>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-offset-4 col-xs-3 control-label">Validated By:</label>
				<div class="col-xs-5">
					<input type="text" readonly="readonly" class="form-control input-md" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>">
				</div>
			</div>
		</form>
		<div class="responserange">
		</div>
	</div>
</div>

