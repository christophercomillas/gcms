<?php 
	
	session_start();
	include '../function.php';
?>
<div class="row rownobot">
	<div class="col-xs-12">
		<form class="form-horizontal" action="../ajax.php?action=managerkeydept" id="managerkey">
			<div class="form-group">
				<label class="col-xs-4 control-label">Username:</label>
				<div class="col-xs-6">
					<input type="text" class="form-control input-xs reqfieldmk fontsizexs" name="username" required autocomplete="off">
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-4 control-label">Password:</label>
				<div class="col-xs-6">
					<input type="password" class="form-control input-xs reqfieldmk fontsizexs" name="password" required autocomplete="off">
				</div>
			</div>
			<div class="responsemanager">
			</div>
		</form>
	</div>
</div>
<script>
	$('input[name=username]').focus();
</script>
