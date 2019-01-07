<?php 
	include '../config.php';
	include '../function.php';

	$dept = array('administrator','marketing','treasurydept','custodian','finance','retailstore');
	$st = getResults($link,'stores','store_id');

?>
<div class="row">
	<div class="col-md-12 form-container">
		<form class="form-horizontal" id="addnewUser" action="../ajax.php?action=addnewuser">			
			<div class="col-sm-6">
				<div class="row">
					<div class="col-sm-12">
						<div class="form-group">
							<label class="col-md-4 control-label-sm">Username:</label>
							<div class="col-sm-8">
								<input name="username" id="uname" type="text" class="form-control inptxt input-sm">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label-sm">Firstname:</label>
							<div class="col-sm-8">
								<input name="firstname" type="text" class="form-control input-sm">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label-sm">Lastname:</label>
							<div class="col-sm-8">
								<input name="lastname" type="text" class="form-control input-sm">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label-sm">Emp ID:</label>
							<div class="col-sm-8">
								<input name="empid" type="text" class="form-control input-sm">
							</div>
						</div>		
					</div>
				</div>			
			</div>

			<div class="col-sm-6">
				<div class="row u-add">
					<div class="col-sm-12">
						<div class="form-group">
							<label class="col-md-4 control-label-sm">Department:</label>
							<div class="col-sm-8">
								<select id="utype" name="department" class="form-control input-sm">
									<option value="0">-Select-</option>
									<?php foreach ($dept as $key): ?>		
											<option value="<?php echo $key; ?>"><?php echo ucwords($key); ?></option>						
									<?php endforeach; ?>
								</select>
							</div>
						</div>					
					</div>
					<div id="stores-a" class="col-sm-12 hide">
						<div class="form-group">
							<label class="col-md-4 control-label-sm">Store</label>
							<div class="col-sm-8">
								<select id="uassigned" name="assigned" class="form-control input-sm">
									<option value="0">-Select-</option>
									<?php foreach ($st as $key => $value): ?>
										<option value="<?php echo $value['store_id']; ?>"><?php echo $value['store_name']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>					
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="response"></div>
	</div>
</div>