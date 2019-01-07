<?php 
	
	session_start();
	include '../function.php';

	$id = $_GET['id'];

	$user = getStoreUser($link,$id);
?>
<div class="row row-nobot">
	<div class="col-md-12 form-container">
		<form class="form-horizontal" action="../ajax.php?action=updatestoreuser" id="_store-staff">
			<?php foreach ($user as $key): ?>
			<input type="hidden" name="uid" value="<?php echo $key->ss_id; ?>">
			<div class="form-group">
				<label class="col-sm-5 control-label">Username:</label>
				<div class="col-sm-7">
					<input type="text" class="form form-control" name="uname" value="<?php echo $key->ss_username; ?>" autofocus>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Firstname:</label>
				<div class="col-sm-7">
					<input type="text" class="form form-control" name="fname" value="<?php echo $key->ss_firstname; ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Lastname:</label>
				<div class="col-sm-7">
					<input type="text" class="form form-control" name="lname" value="<?php echo $key->ss_lastname; ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Employee ID:</label>
				<div class="col-sm-7">
					<input type="text" class="form form-control" name="eid" value="<?php echo $key->ss_idnumber; ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">User Type:</label>
				<div class="col-sm-7">
					<select class="form form-control input-md" name="utype">
						<option value="<?php echo $key->ss_usertype; ?>"><?php echo ucfirst($key->ss_usertype); ?></option>
						<?php if($key->ss_usertype=='cashier'):?>
							<option value="manager">Manager</option>
						<?php else: ?>
							<option value="cashier">Cashier</option>
						<?php endif; ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Status:</label>
				<div class="col-sm-7">
					<select class="form form-control input-md" name="ustat">
						<option value="<?php echo $key->ss_status; ?>"><?php echo ucfirst($key->ss_status); ?></option>
						<?php if($key->ss_status=='active'):?>
							<option value="inactive">Inactive</option>
						<?php else: ?>
							<option value="active">Active</option>
						<?php endif; ?>						
					</select>
				</div>
			</div>
			<div class="response">
			</div>
			<?php endforeach ?>
		</form>
	</div>
</div>