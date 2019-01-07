<?php
	session_start();
	include '../function.php';

	if(isset($_GET['id']))
		$id = $_GET['id'];
	else 
		exit();

	if(!checkIfExist($link,$id,'promo','promo_id',$id))
		exit();

	$promo = getPromoByID($link,$id);
	$gc = getPromoGCByID($link,$id);	
?>
<?php foreach ($promo as $p): ?>
<div class="row no-bot form-horizontal">
	<div class="col-xs-5">
		<div class="form-group">				
			<label class="col-xs-4 control-label">Promo No:</label>
			<div class="col-xs-5">
		    	<input type="text" class="form-control formbot" name="promono" readonly="readonly" value="<?php echo threedigits($id); ?>">
			</div>
		</div>	
		<div class="form-group">
			<label class="col-xs-4 control-label">Date Created</label>
			<div class="col-xs-6">
				<input value="<?php echo _dateFormat($p->promo_date); ?>" type="text" class="form-control formbot input-sm" readonly="readonly">                
			</div>
		</div>	
		<div class="form-group">
			<label class="col-xs-4 control-label">Date Drawn</label>
			<div class="col-xs-6">
				<input value="<?php echo _dateFormat($p->promo_drawdate); ?>" type="text" class="form-control formbot input-sm" readonly="readonly">                
			</div>
		</div>	
		<div class="form-group">
			<label class="col-xs-4 control-label">Date Notified</label>
			<div class="col-xs-6">
				<input value="<?php echo _dateFormat($p->promo_datenotified); ?>" type="text" class="form-control formbot input-sm" readonly="readonly">                
			</div>
		</div>	
		<div class="form-group">
			<label class="col-xs-4 control-label">Expiration Date</label>
			<div class="col-xs-6">
				<input value="<?php echo _dateFormat($p->promo_dateexpire); ?>" type="text" class="form-control formbot input-sm" readonly="readonly">                
			</div>
		</div>	
		<div class="form-group">
			<label class="col-xs-4 control-label">Group</label>
			<div class="col-xs-6">
				<input value="<?php echo $p->promo_group; ?>" type="text" class="form-control formbot input-sm" readonly="readonly">                
			</div>
		</div>	
		<div class="form-group">
			<label class="col-xs-4 control-label">Promo Name</label>
			<div class="col-xs-8">
				<input type="text" class="form-control formbot reqfield" name="promoname" value="<?php echo ucwords($p->promo_name); ?>" readonly="readonly">
			</div>
		</div>
		<div class="form-group">
			<label class="col-xs-4 control-label">Details</label>
			<div class="col-xs-8">
				<textarea class="form form-control formbot reqfield textareah" name="remarks" readonly="readonly"><?php echo ucfirst($p->promo_remarks); ?></textarea>
			</div>
		</div>	
		<div class="form-group">
			<label class="col-xs-4 control-label">Created By</label>
			<div class="col-xs-6">
				<input type="text" class="form-control formbot" value="<?php echo ucwords($p->firstname.' '.$p->lastname); ?>" readonly="readonly">
			</div>
		</div>
		<div class="response">
		</div>
	</div>
	<div class="col-xs-7">
		
		<table class="table tnewpromo" id="promod">
			<thead>
				<tr>
					<th>GC Barcode</th>
					<th>Denomination</th>
					<th>GC Type</th>
					<th class="center">Verified Info</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($gc as $gc): ?>
					<tr>
						<td><?php echo $gc->prom_barcode; ?></td>
						<td><?php echo number_format($gc->denomination,2); ?></td>
						<td><?php echo ucwords($gc->gctype); ?></td>
						<td class="center"><?php echo is_null($gc->vs_barcode) ? '' : '<i class="fa fa-fa fa-info faeye" title="View" onclick="verifyDetails('.$gc->vs_barcode.')"></i>'; ?></td>
					</tr>
				<?php endforeach ?>

			</tbody>
		</table>
	</div>
</div>
<?php endforeach; ?>

<script>
	$.extend( $.fn.dataTableExt.oStdClasses, {	  
	    "sLengthSelect": "selectsup"
	});

    $('#promod').dataTable( {
        "pagingType": "full_numbers",
        "ordering": false,
        "processing": true,				       
		"pageLength": 5,
		"bLengthChange": false
    });
</script>