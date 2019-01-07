<?php 
	include '../config.php';
?>
<div class="row">
	<form method="POST" id="checkbyform" class="form-horizontal" action="../ajax-cashier.php"> 
	<div class="col-md-12">		
		<div class="form-group">
			<div class="col-md-12">
				<select class="form-control input-md" name="auth" id="auth">
					<option>Treasury Employee</option>
					<option>Treasury Head</option>
				</select>
			</div>
		</div>		
	</div>
	</form>
</div>