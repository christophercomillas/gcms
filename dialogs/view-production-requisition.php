<?php
	
	include '../function.php';
	if(isset($_GET['id'])){
		$id = $_GET['id'];
	}

	$requisition = getRequisitionDetails($link,$id);
?>
	<div class="row">
		<?php foreach ($requisition as $key): ?>
		<div class="col-sm-6 form-horizontal">
			<div class="form-group">
				<label for="" class="col-sm-6 control-label">Request No.:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo $key->requis_erno; ?>">
				</div>
			</div>
			<div class="form-group">
				<label for="" class="col-sm-6 control-label">Date Request:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo _dateFormat($key->requis_req); ?>">
				</div>
			</div>
			<div class="form-group">
				<label for="" class="col-sm-6 control-label">Date Needed:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo _dateFormat($key->pe_date_needed); ?>">
				</div>
			</div>
			<div class="form-group">
				<label for="" class="col-sm-6 control-label">Location:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo $key->requis_loc; ?>">
				</div>
			</div>
			<div class="form-group">
				<label for="" class="col-sm-6 control-label">Department:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo $key->requis_dept; ?>">
				</div>
			</div>
			<div class="form-group">
				<label for="" class="col-sm-6 control-label">Remarks:</label>
				<div class="col-sm-6">
					<textarea class="form-control inptxt inptxt input-sm" readonly="readonly"><?php echo $key->requis_rem; ?></textarea>					
				</div>
			</div>
			<div class="form-group">
				<label for="" class="col-sm-6 control-label">Checked By:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt inptxt input-sm" readonly="readonly" value="<?php echo $key->requis_checked; ?>">
				</div>
			</div>
			<div class="form-group">
				<label for="" class="col-sm-6 control-label">Approved By:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo $key->requis_approved; ?>">
				</div>
			</div>
			<div class="form-group">
				<label for="" class="col-sm-6 control-label">Prepared By:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo ucwords($key->firstname.' '.$key->lastname); ?>">
				</div>
			</div>
		</div>		
		<?php endforeach ?>
		<div class="col-sm-6 form-horizontal">
			<h4>Supplier Information</h4>
			<div class="form-group">
				<label for="" class="col-sm-5 control-label">Company Name:</label>
				<div class="col-sm-7">
					<textarea class="form-control inptxt inptxt input-sm" readonly="readonly"><?php echo ucwords($key->gcs_companyname); ?></textarea>
				</div>
			</div>	
			<div class="form-group">
				<label for="" class="col-sm-5 control-label">Contact Person:</label>
				<div class="col-sm-7">
					<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo ucwords($key->gcs_contactperson); ?>">
				</div>
			</div>
			<div class="form-group">
				<label for="" class="col-sm-5 control-label">Contact Number:</label>
				<div class="col-sm-7">
					<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo $key->gcs_contactnumber; ?>">
				</div>
			</div>
			<div class="form-group">
				<label for="" class="col-sm-5 control-label">Company Address:</label>
				<div class="col-sm-7">
					<textarea class="form-control inptxt inptxt input-sm" readonly="readonly"><?php echo ucwords($key->gcs_address); ?></textarea>
				</div>
			</div>
		</div>
	</div>


