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
?>
<div class="row row-nobot">
	<div class="col-md-12 form-container">
		<form class="form-horizontal" action="../ajax.php?action=changestorestaffpassword" id="_store-staff">
			<input type="hidden" value="<?php echo $uid; ?>" name="userid">
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
			<div class="response">
			</div>
		</form>
	</div>
</div>
<script>
	$('input[name=password]').focus();
</script>