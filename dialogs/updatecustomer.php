<?php 
	include '../function.php';

	$id = $_GET['id'];
	$table='customers';
	$select='customers.cus_id,
		customers.cus_fname,
		customers.cus_lname,
		customers.cus_mname,
		customers.cus_namext,
		customers.cus_dob,
		customers.cus_sex,
		customers.cus_cstatus,
		customers.cus_idnumber,
		customers.cus_address,
		customers.cus_mobile';
	$where='customers.cus_id = '.$id;
	$join='';
	$limit='';
	$c = getSelectedData($link,$table,$select,$where,$join,$limit);

	$csex = array('','Male','Female');
	$cstatus = array('','Single/Unmarried','Married','Widow/er','Annuled','Legally Separated');

	if($c->cus_dob=='0000-00-00')
		$dob = '';
	else 
		$dob = _dateFromSqltoDOB($c->cus_dob);

?>
<div class="row no-bot form-container">
	<form class="form-horizontal" action="../ajax.php?action=updatecustomer" id="customer-info">
		<div class="col-sm-6">
			<input type="hidden" name="exist" value="0">
			<input type="hidden" name="cusid" value="<?php echo $c->cus_id; ?>">		
			<div class="form-group">
				<label class="col-sm-5 control-label">First Name
				</label>
					<div class="col-sm-7">
						<input name="fname" id="cusfname" class="form-control formbot reqfield input-sm" type="text" onkeyup="checkCustomerDetailsUpdate(<?php echo $c->cus_id; ?>)" value="<?php echo $c->cus_fname; ?>" autocomplete="off">
					</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Last Name
				</label>  
				<div class="col-sm-7">
					<input name="lname" id="lname" class="form-control formbot reqfield input-sm" type="text" onkeyup="checkCustomerDetailsUpdate(<?php echo $c->cus_id; ?>)" value="<?php echo $c->cus_lname; ?>" autocomplete="off"> 
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Middle Name
				</label>  
				<div class="col-sm-7">
					<input name="mname" id="mname" class="form-control formbot input-sm" type="text" onkeyup="checkCustomerDetailsUpdate(<?php echo $c->cus_id; ?>)" value="<?php echo $c->cus_mname; ?>" autocomplete="off"> 
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Name Ext.
				</label>  
				<div class="col-sm-7">
					<input name="extname" id="extname" class="form-control formbot input-sm" type="text" onkeyup="checkCustomerDetailsUpdate(<?php echo $c->cus_id; ?>)" value="<?php echo $c->cus_namext; ?>" autocomplete="off"> 
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Date of Birth	
				</label>  
				<div class="col-sm-7">
					<input name="dob" id="dob" class="form-control formbot input-sm" type="text" value="<?php echo $dob; ?>" autocomplete="off"> 
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Sex
				</label>  
				<div class="col-sm-7">
					<select name="sex" id="sex" class="form-control formbot input-sm">
						<option value="<?php echo $c->cus_sex; ?>"><?php echo $csex[$c->cus_sex]; ?></option>
						<?php for($i=1; $i<=2;$i++): ?>
							<?php if($i!=$c->cus_sex): ?>
								<option value="<?php echo $i; ?>"><?php echo $csex[$i]; ?></option>
							<?php endif; ?>
						<?php endfor; ?>
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
						<option value="<?php echo $c->cus_cstatus; ?>"><?php echo $cstatus[$c->cus_cstatus]; ?></option>
						<?php for($i=1; $i<=5;$i++): ?>
							<?php if($i!=$c->cus_cstatus): ?>
								<option value="<?php echo $i; ?>"><?php echo $cstatus[$i]; ?></option>
							<?php endif; ?>
						<?php endfor; ?>
					</select> 
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Valid ID Number</label>  
				<div class="col-sm-7">
					<input name="valid" class="form-control formbot input-sm" type="text" value="<?php echo $c->cus_idnumber; ?>" autocomplete="off"> 
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Address
				</label>  
				<div class="col-sm-7">
					<textarea name="address" class="form-control formbot input-sm" autocomplete="off"><?php echo $c->cus_address; ?></textarea> 
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Mobile Number
				</label>  
				<div class="col-sm-7">
					<input name="mobnum" class="form-control formbot input-sm" type="text" value="<?php echo $c->cus_mobile; ?>" autocomplete="off"> 
				</div>
			</div>
		</div>
	</form>
</div>
<script>
	$('#cusfname').focus();
	$("#dob").inputmask("d/m/y",{ "placeholder": "dd/mm/yyyy" }); 
</script>