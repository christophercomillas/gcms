<?php
	session_start();
	include '../function.php';
	if(isset($_GET['recid']))
		$id = $_GET['recid'];

	if(isset($_GET['storeid']))
		$storeid = $_GET['storeid'];

	if(isset($_GET['denid']))
		$denid = $_GET['denid'];

	$denom = getField($link,'denomination','denomination','denom_id',$denid);
	$storename = getField($link,'store_name','stores','store_id',$storeid);
?>

<div class="row">
	<div class="col-sm-12">
		<form method="post" class="form-horizontal" action="../ajax.php?action=validatereceivegc" id="recvalidate">
			<input type="hidden" name="storeid" value="<?php echo $storeid; ?>">
			<input type="hidden" name="denid" value="<?php echo $denid; ?>">
			<div class="form-group">
				<label class="col-sm-3 control-label">Receiving No:</label>
				<div class="col-sm-3">
					<input type="text" class="form-control inptxt input-sm" name="recnum" value="<?php echo $id; ?>" readonly="readonly">
				</div>
				<label class="col-sm-2 control-label">Date:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control inptxt input-sm" value="<?php echo _dateFormat($todays_date); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Store:</label>
				<div class="col-sm-3">
					<input type="text" class="form-control inptxt input-sm" value="<?php echo $storename; ?>" readonly="readonly">
				</div>
				<label class="col-sm-2 control-label">Denomination:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control inptxt input-sm" value="<?php echo number_format($denom,2); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group inputGcbarcode">
				<div class="col-sm-12">
					<input data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': ''" class="form-control input-lg input-validation" id="gcbarcode" name="gcbarcode" autocomplete="off" maxlength="13" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-offset-4 col-sm-3 control-label">Validated by:</label>
				<div class="col-sm-5">
					<input type="text" class="form-control inptxt input-sm" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>" readonly="readonly">
				</div>
			</div>
		</form>
		<div class="response-validate">
		</div>
	</div>
</div>
<script>
	$('#gcbarcode').inputmask();
	$('#gcbarcode').focus();
</script>

