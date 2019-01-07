<?php
	
	include '../function.php';
	if(isset($_GET['id'])){
		$id = $_GET['id'];
	}

	//$budget = getApprovedBudgetRequestByIDDetails($link,$id);

	$table = 'budget_request';
	$select = "budget_request.br_id,
	    budget_request.br_request,
	    budget_request.br_requested_at,
	    budget_request.br_no,
	    budget_request.br_file_docno,
	    budget_request.br_remarks,
	    budget_request.br_requested_needed,
	    CONCAT(brequest.firstname,' ',brequest.lastname) as breq,
	    CONCAT(prepby.firstname,' ',prepby.lastname) as preq,
	    approved_budget_request.abr_approved_by,
	    approved_budget_request.abr_approved_at,
	    approved_budget_request.abr_file_doc_no,
	    approved_budget_request.abr_checked_by,	    
	    approved_budget_request.approved_budget_remark";

	$where = "budget_request.br_request_status = '1'
		AND
			budget_request.br_id='".$id."'";
	$join = 'INNER JOIN
			users as brequest
		ON
			brequest.user_id = budget_request.br_requested_by
		LEFT JOIN
			approved_budget_request
		ON
			approved_budget_request.abr_budget_request_id  = budget_request.br_id
		LEFT JOIN
			users as prepby
		ON
			prepby.user_id = approved_budget_request.abr_prepared_by';
	$limit = '';

	$data = getSelectedData($link,$table,$select,$where,$join,$limit);
	if(count($data)==0)
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
					<input type="text" class="form-control inptxt input-sm" value="<?php echo $data->br_no; ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Date Requested:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" value="<?php echo _dateFormat($data->br_requested_at); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Time Requested:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" value="<?php echo _timeFormat($data->br_requested_at); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Date Needed</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" value="<?php echo _dateFormat($data->br_requested_needed); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Budget Requested:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" value="&#8369 <?php echo number_format($data->br_request,2); ?>" readonly="readonly">
				</div>
			</div>
			<?php if($data->br_file_docno!=''): ?>
			<div class="form-group">
				<label class="col-sm-6 control-label">Request Document:</label>
				<div class="col-sm-6">
					<a class="btn btn-default" href='../assets/images/budgetRequestScanCopy/download.php?file=<?php echo $data->br_file_docno; ?>'><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download</a>
				</div>
			</div>
			<?php endif; ?>
			<div class="form-group">
				<label class="col-sm-6 control-label">Request Remarks:</label>
				<div class="col-sm-6">
					<textarea class="form-control inptxt input-sm" readonly="readonly"><?= $data->br_remarks; ?></textarea>					
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Prepared by:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" value="<?= ucwords($data->breq); ?>" readonly="readonly">
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-horizontal">
			<div class="form-group">
				<label class="col-sm-6 control-label">Date Approved:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" value="<?php echo _dateFormat($data->abr_approved_at); ?>" readonly="readonly">
				</div>
			</div>
			<?php if($data->abr_file_doc_no != ''): ?>
			<div class="form-group">
				<label class="col-sm-6 control-label">Approved Document:</label>
				<div class="col-sm-6">
					<a class="btn btn-default" href='../assets/images/approvedBudgetRequest/download.php?file=<?php echo $data->abr_file_doc_no; ?>'><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download</a>
				</div>
			</div>
			<?php endif; ?>
			<div class="form-group">
				<label class="col-sm-6 control-label">Appoved Remarks:</label>
				<div class="col-sm-6">
					<textarea class="form-control inptxt input-sm" readonly="readonly"><?= $data->approved_budget_remark; ?></textarea>					
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Approved by:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" value="<?= ucwords($data->abr_approved_by); ?>" readonly="readonly">					
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Checked by:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" value="<?= ucwords($data->abr_checked_by); ?>" readonly="readonly">					
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Prepared by:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" value="<?= ucwords($data->preq); ?>" readonly="readonly">
				</div>
			</div>
		</div>
	</div>
</div>
