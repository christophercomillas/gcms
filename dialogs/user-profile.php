<?php 
	
	session_start();
	include '../function.php';
	if(isset($_GET['userid']))
	{
		$id = $_GET['userid'];
	}
	$userdata = getUserData($link,$_SESSION['gc_id']);
?>

<div class="row">
	<div class="col-sm-12">
		<form class="form-horizontal">
			<div class="form-group">
				<label class="control-label col-sm-5">Username</label>
				<div class="col-sm-7">
					<input type="text" class="form inptxt form-control" name="uname" value="<?php echo $userdata->username; ?>" readonly="readonly">
				</div>
			</div>	
			<div class="form-group">
				<label class="control-label col-sm-5">ID Number</label>
				<div class="col-sm-7">
					<input type="text" class="form inptxt form-control" name="uname" value="<?php echo $userdata->emp_id; ?>" readonly="readonly">
				</div>
			</div>	
			<div class="form-group">
				<label class="control-label col-sm-5">Firstname</label>
				<div class="col-sm-7">
					<input type="text" class="form inptxt form-control" name="uname" value="<?php echo ucwords($userdata->firstname); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-5">Lastname</label>
				<div class="col-sm-7">
					<input type="text" class="form inptxt form-control" name="uname" value="<?php echo ucwords($userdata->lastname); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-5">User Group</label>
				<div class="col-sm-7">
					<input type="text" class="form inptxt form-control" name="uname" value="<?php echo ucwords($userdata->title); ?>" readonly="readonly">
				</div>
			</div>
			<?php if($userdata->usertype=='7'): ?>
			<div class="form-group">
				<label class="control-label col-sm-5">Store Assigned</label>
				<div class="col-sm-7">
					<input type="text" class="form inptxt form-control" name="uname" value="<?php echo ucwords($userdata->store_name); ?>" readonly="readonly">
				</div>
			</div>				
			<?php endif; ?>
			<div class="form-group">
				<label class="control-label col-sm-5">Date Created</label>
				<div class="col-sm-7">
					<input type="text" class="form inptxt form-control" name="uname" value="<?php echo _dateFormat($userdata->date_created); ?>" readonly="readonly">
				</div>
			</div>
		</form>
	</div>
</div>