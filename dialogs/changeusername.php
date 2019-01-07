<?php 
	
	include '../function.php';
	if(isset($_GET['userid']) && isset($_GET['username']))
	{
		$id = $_GET['userid'];
		$username = $_GET['username'];
	}
?>
<div class="row">
	<div class="col-sm-12">
		<form class="form-horizontal" id="changeusername">
			<input type="hidden" name="userid" value="<?php echo $id; ?>">
			<input type="hidden" name="username" value="<?php echo $username; ?>">
			<div class="form-group">
				<label class="control-label col-sm-6">Username</label>
				<div class="col-sm-6">
					<input type="text" class="form inptxt form-control reqfield" name="usernamed" value="<?php echo $username; ?>" autocomplete="off" disabled>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-6">New Username</label>
				<div class="col-sm-6">
					<input type="text" class="form inptxt form-control reqfield" name="nusername" autocomplete="off">
				</div>
			</div>
			<div class="responseusername">				
			</div>
		</form>
	</div>
</div>
<script>
	$('input[name=nusername]').focus();
</script>