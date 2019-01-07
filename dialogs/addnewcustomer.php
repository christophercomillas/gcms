<?php 
	include '../config.php';
?>
<div class="row no-bot form-container">
	<form class="form-horizontal" action="../ajax.php?action=addnewcustomer" id="customer-info">
		<div class="col-sm-6">
			<input type="hidden" name="exist" value="0">		
			<div class="form-group">
				<label class="col-sm-5 control-label">First Name
				</label>
					<div class="col-sm-7">
						<input name="fname" autocomplete="off" id="cusfname" class="form-control formbot reqfield input-sm" type="text" onkeyup="checkCustomerDetails()">
					</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Last Name
				</label>  
				<div class="col-sm-7">
					<input name="lname" autocomplete="off" id="lname" class="form-control formbot reqfield input-sm" type="text" onkeyup="checkCustomerDetails()"> 
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Middle Name
				</label>  
				<div class="col-sm-7">
					<input name="mname" autocomplete="off" id="mname" class="form-control formbot input-sm" type="text" onkeyup="checkCustomerDetails()"> 
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Name Ext.
				</label>  
				<div class="col-sm-7">
					<input name="extname" autocomplete="off" id="extname" class="form-control formbot input-sm" type="text" onkeyup="checkCustomerDetails()"> 
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Date of Birth
				</label>  
				<div class="col-sm-7">
					<input name="dob" id="dob" class="form-control formbot input-sm" type="text"> 
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Sex
				</label>  
				<div class="col-sm-7">
					<select name="sex" id="sex" class="form-control formbot input-sm">
						<option value="">-Select-</option>
						<option value="1">Male</option>
						<option value="2">Female</option>
					</select> 
				</div>
			</div>
			<div class="response">
			</div>
		</div>
		<div class="col-sm-6 form-container">
			<div class="form-group">
				<label class="col-sm-5 control-label">Civil Status
				</label>  
				<div class="col-sm-7">
					<select name="cstatus" id="sex" class="form-control formbot input-sm">
						<option value="">-Select-</option>
						<option value="1">Single/Unmarried</option>
						<option value="2">Married</option>
						<option value="3">Widow/er</option>
						<option value="3">Annuled</option>
						<option value="4">Legally Separated</option>
					</select> 
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Valid ID Number</label>  
				<div class="col-sm-7">
					<input name="valid" class="form-control formbot input-sm" type="text"> 
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Address
				</label>  
				<div class="col-sm-7">
					<textarea name="address" class="form-control formbot input-sm"></textarea> 
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Mobile Number
				</label>  
				<div class="col-sm-7">
					<input name="mobnum" class="form-control formbot input-sm" type="text"> 
				</div>
			</div>
		</div>
	</form>
</div>
<script>
	$('#cusfname').focus();
	$("#dob").inputmask("d/m/y",{ "placeholder": "dd/mm/yyyy" }); 
</script>