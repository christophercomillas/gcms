<?php 
	
	session_start();
	include '../function.php';

	$stores = getStores($link);

?>
<div class="row row-nobot">
	<div class="col-md-12 form-container">
		<form class="form-horizontal" action="../ajax.php?action=addstoreuser" id="_store-staff">
			<div class="form-group">
				<label class="col-sm-5 control-label">Username:</label>
				<div class="col-sm-7">
					<input type="text" class="form-control formbot reqfield input-sm" name="uname" onkeyup="checkstoreusername(this.value)" autofocus>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Firstname:</label>
				<div class="col-sm-7">
					<input type="text" class="form-control formbot reqfield input-sm" name="fname">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Lastname:</label>
				<div class="col-sm-7">
					<input type="text" class="form-control formbot reqfield input-sm" name="lname">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Employee ID:</label>
				<div class="col-sm-7">
					<input type="text" class="form-control formbot reqfield input-sm" name="eid">
				</div>
			</div>
			<div class="form-group">
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
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Store Assigned:</label>
				<div class="col-sm-7">
					<select class="form-control formbot reqfield input-sm" name="uassigned">
						<option value="">- Select -</option>
						<?php foreach ($stores as $s): ?>
							<option value="<?php echo $s->store_id; ?>"><?php echo ucwords($s->store_name); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">User Type:</label>
				<div class="col-sm-7">
					<select class="form-control formbot reqfield input-sm" name="utype">
						<option value="">- Select -</option>
						<option value="cashier">Cashier</option>
						<option value="manager">Manager</option>
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