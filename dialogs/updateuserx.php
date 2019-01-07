<?php
	include '../config.php';
	include '../function.php';

	$id = $_GET['id'];

	$dept = array('administrator','marketing','treasurydept','custodian','finance','retailstore');
	$st = getResults($link,'stores','store_id');

    $query = $link->query(
        "SELECT 
            `users`.`user_id`,
            `users`.`username`,
            `users`.`firstname`,
            `users`.`lastname`,
            `users`.`usertype`,
            `users`.`user_status`,
            `users`.`login`,
            `users`.`emp_id`,
            `users`.`date_created`,
            `users`.`date_updated`,
            `users`.`store_assigned`,
            `stores`.`store_name`            
        FROM 
            `users`
        LEFT JOIN
            `stores`
        ON
            `users`.`store_assigned` = `stores`.`store_id`
        WHERE
        	`users`.`user_id`='$id'
    ");


	$row = $query->fetch_object(); 
?>
<div class="row">
	<div class="col-md-12 form-container">
		<form class="form-horizontal" action="" id="updateUser">
			<input type="hidden" name="user_id" value="<?php echo $id; ?>" />
			<div class="col-sm-6">
				<div class="row">
					<div class="col-sm-12">
						<div class="form-group">
							<label class="col-md-4 control-label-sm">Username:</label>
							<div class="col-sm-8">
								<input name="username" id="uname" type="text" class="form-control input-sm" value="<?php echo $row->username; ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label-sm">Firstname:</label>
							<div class="col-sm-8">
								<input name="firstname" type="text" class="form-control input-sm" value="<?php echo $row->firstname; ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label-sm">Lastname:</label>
							<div class="col-sm-8">
								<input name="lastname" type="text" class="form-control input-sm" value="<?php echo $row->lastname; ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label-sm">Emp ID:</label>
							<div class="col-sm-8">
								<input name="empid" type="text" class="form-control input-sm" value="<?php echo $row->emp_id; ?>">
							</div>
						</div>		
					</div>
				</div>			
			</div>

			<div class="col-sm-6">
				<div class="row">
					<div class="col-sm-12">
						<div class="form-group">
							<label class="col-md-4 control-label-sm">Department:</label>
							<div class="col-sm-8">
								<select id="utype" name="department" class="form-control input-sm">
									<option value="<?php echo $row->usertype; ?>">
										<?php echo ucwords($row->usertype); ?>
									</option>
									<?php foreach ($dept as $key): ?>
										<?php if($key != $row->usertype): ?>
											<option value="<?php echo $key; ?>"><?php echo ucwords($key); ?></option>
										<?php endif; ?>
									<?php endforeach; ?>
								</select>
							</div>
						</div>					
					</div>					
					<div id="stores-a" class="col-sm-12 <?php if($row->store_assigned=='0') echo 'hide'; ?>">
						<div class="form-group">
							<label class="col-md-4 control-label-sm">Store</label>
							<div class="col-sm-8">
								<select id="uassigned" name="assigned" class="form-control input-sm">
									<option value="<?php echo $row->store_assigned; ?>"><?php echo $row->store_name; ?></option>
									<?php foreach ($st as $key => $value): ?>
										<?php 
										if($row->store_assigned!=$value['store_id']): ?>									
										<option value="<?php echo $value['store_id']; ?>"><?php echo $value['store_name']; ?></option>
										<?php endif; ?>
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

