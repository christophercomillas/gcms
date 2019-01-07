<?php 
	include '../config.php';
?>
<div class="row">
	<form method="POST" id="checkbyform" class="form-horizontal" action="../ajax-cashier.php"> 
	<div class="col-md-12">		
		<div class="form-group">
			<div class="col-md-6">
			  	<label for="radios-0">
	      			<input name="status" id="radios-0" value="1" checked="checked" type="radio">
	      			Checked
	    		</label>
    		</div>	       
		</div>
		<div class="form-group">
			<div class="col-md-6">
			  	<label for="radios-1">
	      			<input name="status" id="radios-1" value="2" type="radio">
	      			Cancel
	    		</label>
    		</div>
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<select class="form-control input-md" name="auth" id="auth">
					<option>Finance Employee</option>
					<option>Finance Secretary</option>
				</select>
			</div>
		</div>		
	</div>
	</form>
</div>