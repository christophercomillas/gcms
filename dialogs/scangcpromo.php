<?php
	session_start();
	include '../function.php';
	if(isset($_GET['id']))
		$id = $_GET['id'];
	else 
		exit();
	if(isset($_GET['group']))
		$group = $_GET['group'];
	else 
		exit();
?>
<div class="row">
	<div class="col-xs-12">
		<form method="post" class="form-horizontal" action="../ajax.php?action=gcpromovalidation" id="promovalidate">
			<input type="hidden" name="promoid" value="<?php echo $id; ?>">
			<div class="form-group">
				<label class="col-xs-3 control-label">Promo No:</label>
				<div class="col-xs-3">
					<input type="text" class="form-control formbot input-sm" name="relnum" value="<?php echo threedigits($id); ?>" readonly="readonly">
				</div>
				<label class="col-xs-2 control-label">Date:</label>
				<div class="col-xs-4">
					<input type="text" class="form-control formbot input-sm" value="<?php echo _dateFormat($todays_date); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-3 control-label">Group:</label>
				<div class="col-xs-3">
					<?php if($_SESSION['gc_usertype']=='8'): ?>
						<input name="group" type="text" value="<?php $group = getField($link,'usergroup','users','user_id',$_SESSION['gc_id']);
                            echo ' '.$group; ?>" class="form-control input-sm inptxt" readonly="readonly">  												
					<?php else: ?>
						<input type="text" class="form-control formbot input-sm" name="group" value="<?php echo $group; ?>" readonly="readonly">
					<?php endif; ?>					
				</div>
			</div>
			<div class="form-group inputGcbarcode">
				<div class="col-xs-12">
					<input data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': ''" class="form-control input-lg input-validation" id="gcbarcode" name="gcbarcode" autocomplete="off" maxlength="13" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-offset-4 col-sm-3 control-label">Scanned by:</label>
				<div class="col-sm-5">
					<input type="text" class="form-control formbot input-sm" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>" readonly="readonly">
				</div>
			</div>
		</form>
		<div class="response-validate">
		</div>
	</div>
</div>
<script>
	$('#gcbarcode').inputmask("integer", { allowMinus: false,rightAlign:true});
	$('#gcbarcode').focus();
</script>
