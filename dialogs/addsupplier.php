<?php 
	include '../config.php';
?>
<div class="row row-nobot">
	<div class="col-md-12 form-container">
		<form class="form-horizontal" id="supplierinfoform" action="../ajax.php?action=addsupplier">
			<div class="form-group">
				<label class="col-md-4 control-label">Company Name
				</label>
					<div class="col-md-8">
						<input id="compname" name="cname" class="form-control inptxt input-md" type="text">
					</div>
			</div>
			<div class="form-group">
				<label class="col-md-4 control-label">Account Name
				</label>  
				<div class="col-md-8">
					<input id="textinput" name="aname" class="form-control inptxt input-md" type="text"> 
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-4 control-label">Contact Person
				</label>  
				<div class="col-md-8">
					<input id="textinput" name="cperson" class="form-control inptxt input-md" type="text"> 
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-4 control-label">Contact Number
				</label>  
				<div class="col-md-8">
					<input id="textinput" name="cnumber" class="form-control inptxt input-md" type="text"> 
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-4 control-label">Address
				</label>  
				<div class="col-md-8">
					<input id="textinput" name="caddress" class="form-control inptxt input-md" type="text"> 
				</div>
			</div>
			<div class="response">
			</div>
		</form>
	</div>
</div>
<script>
	$('input[name=cname]').focus();
</script>