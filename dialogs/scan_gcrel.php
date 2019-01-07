<?php
	session_start();
	include '../function.php';

	if(!isset($_GET['action']))
		exit();

	$action = $_GET['action'];

	if(empty($action))
		exit();

	if($action=='releasestore'):

	if(isset($_GET['id']))
		$id = $_GET['id'];
	else 
		exit();

	if(isset($_GET['denid']))
		$denid = $_GET['denid'];
	else 
		exit();

	if(isset($_GET['storeid']))
		$storeid = $_GET['storeid'];
	else 
		exit();

	// get denomination
	$den = getField($link,'denomination','denomination','denom_id',$denid);	
	$store_name = getField($link,'store_name','stores','store_id',$storeid);	
?>

<div class="row">
	<div class="col-xs-12">
		<form method="post" class="form-horizontal" action="../ajax.php?action=gcreleasevalidation" id="srsvalidate">
			<input type="hidden" name="denid" value="<?php echo $denid; ?>">
			<div class="form-group">
				<label class="col-xs-3 control-label">Release No:</label>
				<div class="col-xs-3">
					<input type="text" class="form-control inptxt input-sm" name="relnum" value="<?php echo threedigits($id); ?>" readonly="readonly">
				</div>
				<label class="col-xs-2 control-label">Date:</label>
				<div class="col-xs-4">
					<input type="text" class="form-control inptxt input-sm" value="<?php echo _dateFormat($todays_date); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-3 control-label">Store:</label>
				<div class="col-xs-3">
					<input type="text" class="form-control inptxt input-sm" name="relnum" value="<?php echo $store_name; ?>">
				</div>
				<label class="col-xs-2 control-label">Denomination:</label>
				<div class="col-xs-4">
					<input type="text" class="form-control inptxt input-sm" value="<?php echo '&#8369 '.number_format($den,2); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group inputGcbarcode">
				<div class="col-xs-12">
					<input data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': ''" class="form-control input-lg input-validation" id="gcbarcode" name="gcbarcode" autocomplete="off" maxlength="13" />
				</div>
			</div>
			<div class="form-group" style="display:none;">
				<div class="col-xs-12">
					<input data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': ''" class="form-control input-lg input-validation" id="gcbarcodexx" name="gcbarcodexxxx" autocomplete="off" maxlength="13" />
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


<?php elseif($action=='releasepromo'):
	if(!isset($_GET['relnum']) || !isset($_GET['trid']))
		exit();

	$relnum = $_GET['relnum'];
	$trid = $_GET['trid'];
	// unset($_SESSION['scannedPromo']);
	// if(isset($_SESSION['scannedPromo']))
	// var_dump($_SESSION['scannedPromo']);
?>
<div class="row">
	<div class="col-xs-12">
		<form method="post" class="form-horizontal" action="../ajax.php?action=gcreleasevalidationpromo" id="srsvalidatepromo">
			<input type="hidden" name="relnum" value="<?php echo $relnum; ?>">
			<input type="hidden" name="trid" value="<?php echo $trid; ?>">
			<div class="form-group">
				<label class="col-xs-3 control-label">Release No:</label>
				<div class="col-xs-3">
					<input type="text" class="form-control inptxt input-sm" name="relnum" value="<?php echo threedigits($relnum); ?>" readonly="readonly">
				</div>
				<label class="col-xs-2 control-label">Date:</label>
				<div class="col-xs-4">
					<input type="text" class="form-control inptxt input-sm" value="<?php echo _dateFormat($todays_date); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group inputGcbarcode">
				<div class="col-xs-12">
					<input data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': ''" class="form-control input-lg input-validation" id="gcbarcode" name="gcbarcode" autocomplete="off" maxlength="13" />
				</div>
			</div>
			<div class="form-group" style="display:none;">
				<div class="col-xs-12">
					<input data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': ''" class="form-control input-lg input-validation" id="gcbarcodexx" name="gcbarcodexxxx" autocomplete="off" maxlength="13" />
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
<?php elseif($action=='scannedpromogc'):
	//var_dump($_SESSION['scannedPromo']);
?>
<form id="scannedGCForm" method="POST">
	<table class="table" id="scannedgc">
		<thead>
			<tr>
				<th>Barcode</th>
				<th>Pro #</th>
				<th>Type</th>
				<th>Denomination</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php if(isset($_SESSION['scannedPromo'])): ?>
				<?php foreach ($_SESSION['scannedPromo'] as $key => $value): ?>
					<tr>
						<td><?php echo $value['barcode']; ?></td>
						<td><?php echo $value['productionnum']; ?></td>
						<td><?php echo $value['promo']; ?></td>
						<td><?php echo number_format($value['denomination'],2); ?></td>
						<td><input type="checkbox" name="checkboxpromo[]" value="<?php echo $value['barcode']; ?>" /></td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>	
	</table>
</form>
<div class="response-checkbox"></div>
<script>
	$('#scannedgc').dataTable( {
	    "pagingType": "full_numbers",
	    "ordering": false,
	    "processing": true
	});
</script>

<?php elseif ($action=='scannedGCForTransfer'): ?>

	<form id="scannedGCForm" method="POST">
		<table class="table" id="scannedgc">
			<thead>
				<tr>
					<th>Barcode</th>
					<th>Denomination</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php if(isset($_SESSION['scanGCForTransfer'])): ?>
					<?php foreach ($_SESSION['scanGCForTransfer'] as $key => $value): ?>
						<tr>
							<td><?php echo $value['barcode']; ?></td>
							<td><?php echo number_format($value['denomination'],2); ?></td>
							<td><input type="checkbox" name="checkboxtransfer[]" value="<?php echo $value['barcode']; ?>" /></td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>	
		</table>
	</form>
	<div class="response-checkbox"></div>
	<script>
		$('#scannedgc').dataTable( {
		    "pagingType": "full_numbers",
		    "ordering": false,
		    "processing": true
		});
	</script>

<?php elseif ($action=='scannedGCForTransferReceiving'): ?>

	<form id="scannedGCForm" method="POST">
		<table class="table" id="scannedgc">
			<thead>
				<tr>
					<th>Barcode</th>
					<th>Denomination</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php if(isset($_SESSION['scanGCForTransferReceiving'])): ?>
					<?php foreach ($_SESSION['scanGCForTransferReceiving'] as $key => $value): ?>
						<tr>
							<td><?php echo $value['barcode']; ?></td>
							<td><?php echo number_format($value['denomination'],2); ?></td>
							<td><input type="checkbox" name="checkboxtransfer[]" value="<?php echo $value['barcode']; ?>" /></td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>	
		</table>
	</form>
	<div class="response-checkbox"></div>
	<script>
		$('#scannedgc').dataTable( {
		    "pagingType": "full_numbers",
		    "ordering": false,
		    "processing": true
		});
	</script>

<?php elseif ($action=='scanspecialGC'): 
	if(!isset($_GET['id']))
		exit();

	$trid = $_GET['id'];

	if(empty($trid))
		exit();

	if(!checkifExist2($link,'spexgc_num','special_external_gcrequest','spexgc_id','spexgc_status',$trid,'approved'))
		exit();

	//if(isset($_SESSION['scanReviewGC']))
	//	var_dump($_SESSION['scanReviewGC']);
?>

<div class="row">
	<div class="col-xs-12 form-horizontal">
		<form method="post" action="../ajax.php?action=gcreviewscangc" id="gcreviewscangc">
			<input type="hidden" name="trid" value="<?php echo $trid; ?>">
			<div class="form-group">
				<label class="col-xs-3 control-label">Date:</label>
				<div class="col-xs-6">
					<input type="text" class="form-control inptxt input-sm" value="<?php echo _dateFormat($todays_date); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group inputGcbarcode">
				<div class="col-xs-12">
					<input data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': ''" class="form-control input-lg input-validation" id="gcbarcode" name="gcbarcode" autocomplete="off" maxlength="13" />
				</div>
			</div>
			<div class="form-group" style="display:none;">
				<div class="col-xs-12">
					<input data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': ''" class="form-control input-lg input-validation" id="gcbarcodexx" name="gcbarcodexxxx" autocomplete="off" maxlength="13" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Scanned by:</label>
				<div class="col-sm-7">
					<input type="text" class="form-control inptxt input-sm" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>" readonly="readonly">
				</div>
			</div>
		</form>
		<div class="response-validate">
		</div>
	</div>
</div>

<?php endif; ?>
<script>
	// $('#scannedgc').dataTable( {
 //        "pagingType": "full_numbers",
 //        "ordering": false,
 //        "processing": true
 //    });
	$('#gcbarcode').inputmask();
	$('#gcbarcode').focus();
</script>