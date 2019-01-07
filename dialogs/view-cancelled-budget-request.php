<?php
	
	include '../function.php';
	if(isset($_GET['id'])){
		$id = $_GET['id'];
	} else {
		exit();
	}

	$cancelled = getCancelledBudgetRequestByID($link,$id);

	if(is_null($cancelled))
	{
		exit();
	}
?>
<div class="row">	
	<div class="col-sm-6">
		<div class="form-horizontal">
			<div class="form-group">
				<label class="col-sm-6 control-label">BR No.:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" value="<?php echo $cancelled->br_no; ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Date Requested:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" value="<?php echo _dateFormat($cancelled->br_requested_at); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Time Requested:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" value="<?php echo _timeFormat($cancelled->br_requested_at); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Budget Requested:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" value="&#8369 <?php echo number_format($cancelled->br_request,2); ?>" readonly="readonly">
				</div>
			</div>
			<?php if($cancelled->br_file_docno!=''): ?>
			<div class="form-group">
				<label class="col-sm-6 control-label">Request Document:</label>
				<div class="col-sm-6">
					<a class="btn btn-default" href='../assets/images/budgetRequestScanCopy/download.php?file=<?php echo $cancelled->br_file_docno; ?>'><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download</a>
				</div>
			</div>
			<?php endif; ?>
			<div class="form-group">
				<label class="col-sm-6 control-label">Request Remarks:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" value="<?= $cancelled->br_remarks; ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Prepared by:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" value="<?= ucwords($cancelled->fnamerequest.' '.$cancelled->lnamerequest); ?>" readonly="readonly">
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-horizontal">
			<div class="form-group">
				<label class="col-sm-6 control-label">Date Cancelled:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" value="<?php echo _dateFormat($cancelled->cdreq_at); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Cancelled By:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" value="<?php echo ucwords($cancelled->fnamecancelled.' '.$cancelled->lnamecancelled); ?>" readonly="readonly">
				</div>
			</div>
		</div>
	</div>
</div>
