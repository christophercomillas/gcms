<?php 
	
	session_start();
	include '../function.php';
	if(isset($_GET['action']))
	{
		$action = $_GET['action'];
	}
	else 
	{
		exit();
	}
	if($action=='add'):
?>
<div class="row row-nobot">
	<div class="col-md-12 form-container">
		<form class="form-horizontal" action="../ajax.php?action=adddenom" id="denomform">
			<div class="form-group">
				<label class="col-sm-5 control-label">Denomination:</label>
				<div class="col-sm-7">
					<input type="text" data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '','allowMinus':false" class="form form-control inptxt reqfield" name="denom" autofocus maxlength="13">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-5 control-label">Barcode # start:</label>
				<div class="col-sm-7">
					<input type="text" data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '','allowMinus':false" class="form form-control inptxt reqfield" name="bstart" maxlength="13">
				</div>
			</div>	
			<div class="response"></div>		
		</form>
	</div>
</div>
<div class="row row-nobot">
<?php 
	elseif($action=='update'):

	if(isset($_GET['denomid']))
	{
		$denomid = $_GET['denomid'];
	} 
	else 
	{
		exit();
	}

	$hastrans = false;

	//check if denomination already generate barcode
	if(checkIfExist($link,'pe_items_denomination','production_request_items','pe_items_denomination',$denomid))
	{
		$hastrans = true;
	}

	$select = 'denomination, denom_fad_item_number, denom_barcode_start';
	$where = 'denom_id='.$denomid;
	$join = '';
	$limit = '';
	$denominfo= getSelectedData($link,'denomination',$select,$where,$join,$limit);
?>
<div class="row row-nobot">
	<div class="col-md-12 form-container">
		<form class="form-horizontal" action="../ajax.php?action=updatedenom" id="denomform">
			<input type="hidden" name="denomid" value="<?php echo $denomid; ?>">
			<input type="hidden" name="hastrans" value="<?php echo $hastrans ? '1' : '0'; ?>">
			<input type="hidden" name="denoms" value="<?php echo $denominfo->denomination;?>">
			<div class="form-group">
				<label class="col-sm-5 control-label">Denomination:</label>
				<div class="col-sm-7">
					<input type="text" data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '','allowMinus':false" class="form form-control inptxt reqfield" name="denom" value="<?php echo $denominfo->denomination; ?>" autofocus maxlength="13" <?php echo $hastrans ? 'readonly':'';?>>
					<?php if($hastrans==true): ?>
						<span class="spandenom">Denomination already have transaction.</span>
					<?php endif; ?>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-5 control-label">Barcode # start:</label>
				<div class="col-sm-7">
					<input type="text" data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '','allowMinus':false" class="form form-control inptxt reqfield" name="bstart" value="<?php echo $denominfo->denom_barcode_start ?>" maxlength="13">
				</div>
			</div>	

			<div class="form-group">
				<label class="col-sm-5 control-label">FAD Item #:</label>
				<div class="col-sm-7">
					<input type="text" class="form form-control inptxt reqfield" name="faditem" maxlength="13" value="<?php echo $denominfo->denom_fad_item_number; ?>">
				</div>
			</div>	
			<div class="response"></div>		
		</form>
	</div>
</div>
<?php 
	elseif($action=='setup'):
	$den = $_GET['denom'];
	$barcode = barcodeStartSuggestion($link);
?>
	<div class="row row-nobot">
		<div class="col-md-12 form-container">
			<form class="form-horizontal" action="../ajax.php?action=setupdenom" id="setupdenomform">
				<input type="hidden" name="denom" id="denom" value="<?php echo $den; ?>">
				<div class="form-group">
					<label class="col-sm-5 control-label">Denomination:</label>
					<div class="col-sm-7">
						<input type="text" data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '','allowMinus':false" class="form form-control inptxt reqfield" name="denoms" value="<?php echo $den; ?>" maxlength="13" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-5 control-label">Barcode # start:</label>
					<div class="col-sm-7">
						<input type="text" data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '','allowMinus':false" class="form form-control inptxt reqfield" name="bstart" id="bstart" value="<?php echo $barcode; ?>" maxlength="13" autofocus>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-5 control-label">FAD Item #:</label>
					<div class="col-sm-7">
						<input type="text" class="form form-control inptxt reqfield" name="faditem" id="faditem" maxlength="13" value="" autocomplete="off">
					</div>
				</div>	
				<div class="response"></div>						
			</form>
		</div>
	</div>
<?php endif; ?>
<script>
$('input[name=denom], input[name=bstart]').inputmask();
$('input[name=denom]').focus();
</script>