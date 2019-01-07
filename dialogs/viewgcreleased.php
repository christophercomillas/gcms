<?php 
	include '../function.php';
	if(isset($_GET['relid'])){
		$id = $_GET['relid'];
	}
	else 
	{
		exit();
	}
	$rel = getAllReleasedGCDetails($link,$id);
	$rec = storeReceivedDetails($link,$id);
	$stat = array('Pending','Received');
	$reltype = array('none','partial','whole','final');	
?>
<div class="form-horizontal">
<div class="row">
	<?php foreach ($rel as $r): ?>
	<div class="col-xs-6">		
		<div class="header">GC Released Details</div>
		<div class="form-groupwrap">
			<div class="form-group">
				<label class="col-sm-6 control-label">GC Releasing No.: </label>
				<div class="col-sm-3">
					<input type="text" class="form-control input-sm inptxt reqfield" value="<?php echo threedigits($r->agcr_id); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Date Released: </label>
				<div class="col-sm-6">
					<input type="text" class="form-control input-sm inptxt reqfield" value="<?php echo _dateFormat($r->agcr_approved_at); ?>" readonly="readonly">
				</div>
			</div>
			<?php if($r->agcr_file_docno!=''):?>
			<div class="form-group">
				<label class="col-sm-6 control-label">Uploaded Document: </label>
				<div class="col-sm-4">
					<a class="btn btn-block btn-default" href="../assets/images/approvedGCRequest/download.php?file=<?php echo $r->agcr_file_docno; ?>.jpg"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download</a>
				</div>
			</div>
			<?php endif; ?>
			<div class="form-group">
				<label class="col-sm-6 control-label">Remarks: </label>
				<div class="col-sm-6">
					<textarea class="form-control input-sm inptxt reqfield" readonly="readonly"><?php echo $r->agcr_remarks; ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Checked By: </label>
				<div class="col-sm-6">
					<input type="text" class="form-control input-sm inptxt reqfield" value="<?php echo $r->agcr_checkedby; ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Approved By: </label>
				<div class="col-sm-6">
					<input type="text" class="form-control input-sm inptxt reqfield" value="<?php echo $r->agcr_approvedby; ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Released By: </label>
				<div class="col-sm-6">
					<input type="text" class="form-control input-sm inptxt reqfield" value="<?php echo ucwords($r->firstname.' '.$r->lastname); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Received By: </label>
				<div class="col-sm-6">
					<input type="text" class="form-control input-sm inptxt reqfield" value="<?php echo ucwords($r->agcr_recby); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Released Type: </label>
				<div class="col-sm-6">
					<input type="text" class="form-control input-sm inptxt reqfield" value="<?php echo ucwords($reltype[$r->agcr_stat]); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Status: </label>
				<div class="col-sm-6">
					<input type="text" class="form-control input-sm inptxt reqfield <?php echo $r->agcr_rec==0 ? 'pendingstatus' : ''; ?>" value="<?php echo $stat[$r->agcr_rec]; ?>" readonly="readonly">
				</div>
			</div>
		</div>
	</div>
	<div class="col-xs-6">
		<div class="header">Store GC Request Details</div>
		<div class="form-groupwrap">
			<div class="form-group">
				<label class="col-sm-5 control-label">GC Request No.: </label>
				<div class="col-sm-7">
					<input type="text" class="form-control input-sm inptxt reqfield" value="<?php echo $r->sgc_num; ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Store: </label>
				<div class="col-sm-7">
					<input type="text" class="form-control input-sm inptxt reqfield" value="<?php echo $r->store_name; ?>" readonly="readonly">
				</div>
			</div> 
			<div class="form-group">
				<label class="col-sm-5 control-label">Date Requested: </label>
				<div class="col-sm-7">
					<input type="text" class="form-control input-sm inptxt reqfield" value="<?php echo _dateFormat($r->sgc_date_request); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Time Requested: </label>
				<div class="col-sm-7">
					<input type="text" class="form-control input-sm inptxt reqfield" value="<?php echo _timeFormat($r->sgc_date_request); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Date Needed: </label>
				<div class="col-sm-7">
					<input type="text" class="form-control input-sm inptxt reqfield" value="<?php echo _dateFormat($r->sgc_date_needed); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Remarks: </label>
				<div class="col-sm-7">
					<textarea class="form-control input-sm inptxt reqfield" readonly="readonly"><?php echo $r->sgc_remarks; ?> </textarea>
				</div>
			</div>
			<?php if($r->sgc_file_docno!=''): ?>
			<div class="form-group">
				<label class="col-sm-5 control-label">Document: </label>
				<div class="col-sm-4">
					<a class="btn btn-block btn-default" href="../assets/images/approvedGCRequest/download.php?file=<?php echo $r->sgc_file_docno; ?>.jpg"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download</a>
				</div>
			</div>
			<?php endif; ?>
			<div class="form-group">
				<label class="col-sm-5 control-label">Requested by: </label>
				<div class="col-sm-7">
					<?php 
						$fname = getFullname($link,$r->sgc_requested_by);							
					?>
					<input type="text" class="form-control input-sm inptxt reqfield" value="<?php echo ucwords($fname); ?>" readonly="readonly">

				</div>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
</div>
<div class="row">
	<div class="col-xs-6">
		<?php if(count($rec)> 0 ): ?>
			<div class="header">Store Receiving Details</div>
			<div class="form-groupwrap">
				<div class="form-group">
					<label class="col-sm-5 control-label">GC Receiving No.: </label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm inptxt reqfield" value="<?php echo addZeroToStringZ($rec->srec_recid,5); ?>" readonly="readonly">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-5 control-label">Date Received: </label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm inptxt reqfield" value="<?php echo _dateFormat($rec->srec_at); ?>" readonly="readonly">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-5 control-label">Received By: </label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm inptxt reqfield" value="<?php echo ucwords($rec->firstname.' '.$rec->lastname); ?>" readonly="readonly">
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<div class="col-xs-6">
		<?php 
			$barcodes = getReleasedGCByGCRequestID2($link,$id,$r->agcr_request_id);

		?>
		<table class="table" id="gcrequestdisplay">			
			<thead>
				<tr>
					<th>Denomination</th>
					<th>Qty</th>
					<th>Total</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($barcodes as $b): ?>
				<tr>
					<td class="cnt"><?php echo number_format($b->denomination,2); ?></td>
					<td><?php echo $b->c; ?></td>
					<td><?php echo number_format($b->tot,2); ?></td>
					<td><button class="form-control" onclick="gcreleasedperdenom(<?php echo $b->denom_id; ?>,<?php echo $id; ?>,<?php echo $b->denomination; ?>);">View</button></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
</div>