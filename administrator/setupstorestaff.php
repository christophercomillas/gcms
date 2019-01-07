<?php 
  session_start();
  include '../function.php';
  require 'header.php';
  $table = 'store_staff';
  $select = ' store_staff.ss_firstname,
        store_staff.ss_lastname,
        stores.store_name,
        store_staff.ss_username,
        store_staff.ss_status,
        store_staff.ss_idnumber,
        store_staff.ss_usertype,
        store_staff.ss_date_created,
        store_staff.ss_by,
        store_staff.ss_id';
  $join = 'INNER JOIN
          stores
        ON
          stores.store_id = store_staff.ss_store';
  $limit = 'ORDER BY ss_id DESC';
  $staff = getAllData($link,$table,$select,'1',$join,$limit);
?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
  	<div class="row row-nobot">
  		<div class="col-sm-12">
    		<div class="box box-bot">
      			<div class="box-header">
              <span class="box-title-with-btn"><i class="fa fa-inbox">
                  </i> Store Staff Setup
              </span>
              <div class="col-sm-8 form-horizontal pull-right p-right">
                  <div class="col-sm-offset-9 col-sm-3">
                      <button class="btn btn-block btn-info" onclick="addStoreStaff()"><i class="fa fa-user-plus"></i> Add New User</button>
                  </div>
              </div>   
            </div>
     			<div class="box-content form-container">
            <table class="table" id="userlist">
              <thead>
                <tr>
                  <th>Username</th>                    
                  <th>Firstname</th>
                  <th>Lastname</td>
                  <th>Assigned</th>
                  <th>Emp ID No.</th>
                  <th>User Type</th>
                  <th>Date Created</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>         
              </thead>
              <tbody>
                <?php foreach ($staff as $key): ?>
                  <tr>
                    <td><?php echo $key->ss_username; ?></td>
                    <td><?php echo $key->ss_firstname; ?></td>
                    <td><?php echo $key->ss_lastname; ?></td>
                    <td><?php echo ucwords($key->store_name); ?></td>
                    <td><?php echo $key->ss_idnumber; ?></td>
                    <td><?php echo $key->ss_usertype; ?></td>
                    <td><?php echo _dateFormat($key->ss_date_created); ?></td>
                    <td><?php echo $key->ss_status; ?></td>
                    <td>
                      <i class="fa fa-pencil-square-o falink sstaff" staffid="" title="Update" onclick="updateStoreStaff(<?php echo $key->ss_id; ?>)"></i><i class="fa fa-undo fa-active ssreset" title="Change Password" onclick="changestorestaffpassword(<?php echo $key->ss_id; ?>,'<?php echo $key->ss_username; ?>')"></i>
                      <i class="fa fa-circle <?php echo $key->ss_status=='active' ? 'fa-log1' : 'fa-log2';?> sstatus" title="<?php echo $key->ss_status=='active' ? 'Deactivate' : 'Activate';?> <?php echo $key->ss_username; ?>" onclick="storeuserstatus(<?php echo $key->ss_id; ?>,'<?php echo $key->ss_username; ?>','<?php echo $key->ss_status; ?>')"></i>
                    </td>
                  </tr>                      
                <?php endforeach ?>                    
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