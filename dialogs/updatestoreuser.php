<?php 
	
	session_start();
	include '../function.php';
	if(isset($_GET['id']) && $_GET['id']!='')
		$uid = $_GET['id'];
	else 
		exit();

	// get user details by id
	$select = 'store_staff.ss_firstname,
		store_staff.ss_lastname,
		store_staff.ss_username,
		store_staff.ss_usertype,
		stores.store_name,
		store_staff.ss_idnumber,
		store_staff.ss_store';
	$where = 'store_staff.ss_id='.$uid;
	$join = 'INNER JOIN
			stores
		ON
			stores.store_id = store_staff.ss_store';
	$user = getSelectedData($link,'store_staff',$select,$where,$join,'LIMIT 1');

	$stores = getStores($link);
?>
<div class="row row-nobot">
	<div class="col-md-12 form-container">
		<form class="form-horizontal" action="../ajax.php?action=updatestoreuser" id="_store-staff">
			<input type="hidden" value="<?php echo $uid; ?>" name="userid">
			<div class="form-group">
				<label class="col-sm-5 control-label">Username:</label>
				<div class="col-sm-7">
					<input type="text" class="form-control formbot reqfield input-sm" name="uname" value="<?php echo $user->ss_username; ?>" onkeyup="checkstoreusername(this.value,)" autofocus>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Firstname:</label>
				<div class="col-sm-7">
					<input type="text" class="form-control formbot reqfield input-sm" name="fname" value="<?php echo $user->ss_firstname; ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Lastname:</label>
				<div class="col-sm-7">
					<input type="text" class="form-control formbot reqfield input-sm" name="lname" value="<?php echo $user->ss_lastname; ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Employee ID:</label>
				<div class="col-sm-7">
					<input type="text" class="form-control formbot reqfield input-sm" name="eid" value="<?php echo $user->ss_idnumber; ?>">
				</div>
			</div>
<!-- 			<div class="form-group">
				<label class="col-sm-5 control-label">Password:</label>
				<div class="col-sm-7">
					<div class="input-group">
						<input type="text" class="form-control formbot reqfield input-sm" name="password">
				  		<span class="input-group-btn">
					    	<button class="btn btn-info input-sm" id="viewbarcodepro" onclick="randompass()" type="button">
					      	<span class="glyphicon glyphicon-search"></span>
					     	</button>
				  		</span>
				  	</div>
				</div>
			</div> -->
			<div class="form-group">
				<label class="col-sm-5 control-label">Store Assigned:</label>
				<div class="col-sm-7">
					<select class="form-control formbot reqfield input-sm" name="uassigned">
						<option value="<?php echo $user->ss_store; ?>"><?php echo $user->store_name; ?></option>
						<?php foreach ($stores as $s): ?>
							<?php if($s->store_id != $user->ss_store): ?>
								<option value="<?php echo $s->store_id; ?>"><?php echo ucwords($s->store_name); ?></option>
							<?php endif; ?>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">User Type:</label>
				<div class="col-sm-7">
					<select class="form-control formbot reqfield input-sm" name="utype">
						<option value="<?php echo $user->ss_usertype; ?>"><?php echo ucwords($user->ss_usertype); ?></option>
						<?php if($user->ss_usertype=='cashier'): ?>
							<option value="manager">Manager</option>
						<?php else: ?>
							<option value="cashier">Cashier</option>
						<?php endif; ?>
					</select>
				</div>
			</div>
			<div class="response">
			</div>
		</form>
	</div>
</div>
<script>
	$('input[name=uname]').focus();
</script>