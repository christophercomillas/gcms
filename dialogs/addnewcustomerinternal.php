<?php
	include '../function.php';
	$code = getCode($link);
?>
<div class="row no-bot">
	<form class="form-horizontal" action="../ajax.php?action=addnewcustomerinternal" id="customer-internal">
		<div class="col-xs-12">
			<div class="form-group">
				<label class="col-xs-3 control-label">Code</label>
				<div class="col-xs-3">
					<input name="code" class="form-control formbot reqfield tl input-sm" type="text" value="<?php echo $code; ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-3 control-label">Name</label>
				<div class="col-xs-9">
					<input name="name" id="cusfname" class="form-control formbot reqfield input-sm" type="text">
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-3 control-label">Civil Status</label>
				<div class="col-xs-5">
					<select class="form form-control formbot input-sm" name="cstatus">
						<option value=" ">-Select-</option>
						<option value="single">Single</option>
						<option value="married">Married</option>
						<option value="widow">Widow</option>
						<option value="separated">Separated</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-3 control-label">Address</label>
				<div class="col-xs-9">
					<textarea class="form-control formbot reqfield input-sm" name="address"></textarea> 
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-3 control-label">Group</label>
				<div class="col-xs-5">
					<select class="form form-control formbot reqfield input-sm" name="group">
						<option value="">-Select-</option>
						<option value="1">Head Office</option>
						<option value="2">Subs. Admin</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-3 control-label">Type</label>
				<div class="col-xs-5">
					<select class="form form-control formbot reqfield input-sm" name="type">
						<option value="">-Select-</option>
						<option value="1">Supplier</option>
						<option value="2">Customer</option>
						<option value="3">V.I.P.</option>
					</select>
				</div>
			</div>
		<div class="response"></div>
		</div>
	</form>
</div>
