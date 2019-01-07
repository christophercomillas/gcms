<?php 
	
	session_start();
	include '../function.php';
?>
<div class="row row-nobot">
	<div class="col-md-12 form-container">
		<form class="form-horizontal" action="../ajax.php?action=addccard" id="ccardform">
			<div class="form-group">
				<label class="col-sm-5 control-label">Card Name:</label>
				<div class="col-sm-7">
					<input type="text" class="form form-control inptxt reqfield" name="ccardname" autofocus>
				</div>
			</div>
			<div class="response"></div>
			<div class="form-group" style="display:none">
				<label class="col-sm-5 control-label">Card Name:</label>
				<div class="col-sm-7">
					<input type="text" class="form form-control inptxt reqfield" name="ccardnamex" autofocus>
				</div>
			</div>			
		</form>
	</div>
</div>
<script>
$('input[name=ccardname]').focus();
</script>