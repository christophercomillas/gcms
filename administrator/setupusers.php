<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  $users = getUsers($link);
?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
  	<div class="row row-nobot">
  		<div class="col-sm-12">
    		<div class="box box-bot">
      			<div class="box-header">
              <span class="box-title-with-btn"><i class="fa fa-inbox">
                  </i> Users Set Up
              </span>
              <div class="col-sm-8 form-horizontal pull-right p-right">
                  <div class="col-sm-offset-9 col-sm-3">
                      <button class="btn btn-block btn-info" id="addnew"><i class="fa fa-user-plus"></i> Add New User</button>
                  </div>
              </div>   
            </div>
     			<div class="box-content form-container">
     				<table class="table" id="userlist">
     					<thead>
     						<tr>
     							<th>Username</th>
     							<th>Employee ID</th>
     							<th>Firstname</th>
     							<th>Lastname</th>
                  <th>User Group</th>
                  <th>Store Assigned</th>
                  <th>Status</th>
                  <th>Date Created</th>
                  <th>Action</th>
     						</tr>
     					</thead>
              <tbody class="tbody-userlist">
                <?php foreach($users as $u): ?>
                  <tr staffid="<?php echo $u->user_id; ?>">
                    <td><?php echo $u->username; ?></td>
                    <td><?php echo ucwords($u->emp_id); ?></td>
                    <td><?php echo ucwords($u->firstname); ?></td>
                    <td><?php echo ucwords($u->lastname); ?></td>
                    <td><?php echo $u->title; ?></td>
                    <td><?php echo $u->store_name; ?></td>
                    <td><?php echo ucwords($u->user_status); ?></td>
                    <td><?php echo _dateFormatShort($u->date_created); ?></td>
                    <td>
                      <i class="fa fa-fa fa-eye faeye" title="View"></i>
                      <i class="fa fa-pencil-square-o falink uusers" title="Update"></i>
                      <i class="fa fa-undo fa-active ssreset" title="Reset Password"></i>
                      <i class="fa fa-circle <?php echo $u->user_status=='active'? 'fa-log1':'fa-log2'?> sstatus" title="Deactivate user" status="<?php echo $u->user_status=='active'? '1':'2'?>"></i>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>    					
     				</table>
     			</div>
     		</div>
  		</div>
    </div>
  </div>
<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/admin.js"></script>
<?php include 'footer.php' ?>