<?php
	session_start();
	include '../function.php';
	if(isset($_GET['barcode']))
		$barcode = $_GET['barcode'];
	else 
		exit();

	// verification info
// SELECT 
// store_verification.vs_date,
// store_verification.vs_time,
// stores.store_name,
// customers.cus_fname,
// customers.cus_lname,
// customers.cus_mname,
// customers.cus_namext,
// users.firstname,
// users.lastname
// FROM 
// store_verification 
// INNER JOIN
// customers
// ON
// customers.cus_id = store_verification.vs_cn
// INNER JOIN
// stores
// ON
// stores.store_id = store_verification.vs_store
// INNER JOIN
// users
// ON
// users.user_id = store_verification.vs_by
// WHERE 
// store_verification.vs_barcode= '1010000000001'
// ORDER BY
// store_verification.vs_id
// DESC
// LIMIT 1
	$select = 'store_verification.vs_date,
		store_verification.vs_time,
		stores.store_name,
		customers.cus_fname,
		customers.cus_lname,
		customers.cus_mname,
		customers.cus_namext,
		customers.cus_mobile,
		customers.cus_address,
		users.firstname,
		users.lastname';
	$where = 'store_verification.vs_barcode= '.$barcode;
	$join = 'INNER JOIN
		customers
		ON
		customers.cus_id = store_verification.vs_cn
		INNER JOIN
		stores
		ON
		stores.store_id = store_verification.vs_store
		INNER JOIN
		users
		ON
		users.user_id = store_verification.vs_by';
	$limit ='ORDER BY
		store_verification.vs_id
		DESC
		LIMIT 1';
	$info = getSelectedData($link,'store_verification',$select,$where,$join,$limit);
	

?>
<div class="row form-horizontal">
	<div class="col-xs-12">
		<div class="form-group">
			<label class="col-xs-3 control-label">Date: </label>
			<div class="col-xs-4">
				<input type="text" class="form-control formbot input-sm" readonly="readonly" value="<?php echo _dateFormat($info->vs_date); ?>">
			</div>
			<label class="col-xs-2 control-label">Time: </label>
			<div class="col-xs-3">
				<input type="text" class="form-control formbot input-sm" readonly="readonly" value="<?php echo _timeFormat($info->vs_time); ?>">
			</div>
		</div>	
		<div class="form-group">
			<label class="col-xs-3 control-label">Store: </label>
			<div class="col-xs-5">
				<input type="text" class="form-control formbot input-sm" readonly="readonly" value="<?php echo $info->store_name; ?>">
			</div>
		</div>	
		<div class="form-group">
			<label class="col-xs-3 control-label">Verified By: </label>
			<div class="col-xs-5">
				<input type="text" class="form-control formbot input-sm" readonly="readonly" value="<?php echo ucwords($info->firstname.' '.$info->lastname); ?>">
			</div>
		</div>		
		<hr></hr>
		<div class="form-group">
			<label class="col-xs-3 control-label">Customer Name: </label>
			<div class="col-xs-9">
				<input type="text" class="form-control formbot input-sm" readonly="readonly" value="<?php echo ucwords($info->cus_fname.' '.$info->cus_mname.' '.$info->cus_lname).' '.strtoupper($info->cus_namext).'.'; ?>">
			</div>
		</div>	
		<div class="form-group">
			<label class="col-xs-3 control-label">Mobile #: </label>
			<div class="col-xs-4">
				<input type="text" class="form-control formbot input-sm" readonly="readonly" value="<?php echo $info->cus_mobile; ?>">
			</div>
		</div>	
		<div class="form-group">
			<label class="col-xs-3 control-label">Address:  </label>
			<div class="col-xs-6">
				<textarea class="form form-control formbot textareah1" readonly="readonly"><?php echo $info->cus_address; ?></textarea>
			</div>
		</div>	
	</div>
	<div class="col-xs-6">
</div>