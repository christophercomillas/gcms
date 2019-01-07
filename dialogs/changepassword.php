<?php 
	session_start();
	include '../function.php';
	if(isset($_GET['userid']))
	{
		$id = $_GET['userid'];
	}
?>
<div class="row">
	<div class="col-sm-12">
		<form class="form-horizontal" id="changepassword">
			<input type="hidden" name="userid" value="<?php echo $_SESSION['gc_id']; ?>">
			<div class="form-group">
				<label class="control-label col-sm-6">Old Password</label>
				<div class="col-sm-6">
					<input type="password" class="form inptxt form-control reqfield" name="opass">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-6">New Password</label>
				<div class="col-sm-6">
					<input type="password" class="form inptxt form-control reqfield" name="npass">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-6">Confirm New Password</label>
				<div class="col-sm-6	">
					<input type="password" class="form inptxt form-control reqfield" name="rnpass">
				</div>
			</div>
			<div class="responsechangepass">				
			</div>
		</form>
	</div>
</div>
<script>
	$('input[name=opass]').focus();
</script>