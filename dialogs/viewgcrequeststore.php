<?php

	include '../function.php';
	if(isset($_GET['reqid']) && trim($_GET['reqid'])!='')
	{
		$reqid = $_GET['reqid'];
	}
	else 
	exit();

	$info = getRequestDetailsPending($link,$reqid);
	$barcodes = getStoreGCRequest($link,$reqid);

?>
<div class="row">
	<div class="col-xs-6">
		<div class="form-horizontal">
			<div class="form-group">
				<label class="col-xs-6 control-label">GC Request No.:</label>
				<div class="col-xs-4">
					<input type="text" class="form form-control input-sm inptxt" readonly="readonly" value="<?php echo $info->sgc_num; ?>" name="penum" readonly="readonly">      
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-6 control-label">Retail Store:</label>
				<div class="col-xs-6">
					<input type="text" class="form form-control input-sm inptxt" name="storename" readonly="readonly" value="<?php echo $info->store_name; ?>">
				</div>
			</div><!-- end of form-group -->
			<div class="form-group">
				<label class="col-xs-6 control-label">Date Requested:</label>
				<div class="col-xs-6">
					<input type="text" class="form form-control input-sm inptxt" readonly="readonly" value="<?php echo _dateFormat($info->sgc_date_request); ?>">
				</div>
			</div><!-- end of form-group -->
			<div class="form-group">
				<label class="col-sm-6 control-label">Date Needed:</label>
				<div class="col-sm-6">
					<input type="text" class="form form-control input-sm inptxt" value="<?php echo _dateFormat($info->sgc_date_needed); ?>" readonly="readonly">
				</div>
			</div><!-- end of form-group -->
			<?php if(is_null($info->sgc_file_docno)): ?>
            <div class="form-group">
				<label class="col-sm-6 control-label">Uploaded Document:</label>
				<div class="col-sm-5">
					<a class="btn btn-block btn-default" href='../assets/images/gcRequestStore/download.php?file=<?php echo $info->sgc_file_docno; ?>.jpg'><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download</a>
				</div>
            </div><!-- end of form-group -->
        	<?php endif; ?>
			<div class="form-group">
				<label class="col-sm-6 control-label">Remarks:</label>
				<div class="col-sm-6">
					<textarea class="form-control inptxt"><?php echo $info->sgc_remarks; ?></textarea>
				</div>
			</div><!-- end of form-group -->	 
			<div class="form-group">
				<label class="col-sm-6 control-label">Prepared by:</label>
				<div class="col-sm-6">
					<input type="text" readonly="readonly" class="form-control input-sm inptxt" value="<?php echo ucwords($info->firstname.' '.$info->lastname); ?>">                     
				</div>
			</div><!-- end of form-group --> 
		</div>
	</div>
	<div class="col-xs-6">
		<table class="table" >
			<thead>
				<tr>
					<th>Denomination</th>					
					<th>Requested Qty</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($barcodes as $b): ?>
				<tr>
					<td>
						<?php 
							if($b->denom_id==0)
								echo number_format($b->fds_denom,2);
							else 
								echo number_format($b->denomination,2); 
						?>
					</td>
					<td><?php echo number_format($b->sri_items_quantity); ?></td>
					<td>
						<?php 
							if($b->denom_id==0)
								echo '<span class="label label-danger">For Setup</span>';
						?>
					</td>
				</tr>					
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>