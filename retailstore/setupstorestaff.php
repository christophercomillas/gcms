<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  $staff = getStoreStaff($link,$storeid);

?>

<?php require '../menu.php'; ?>
  <div id="print-receipt">       

  </div>
  <div class="main fluid">
    <div class="row form-container">
        <div class="col-sm-12">
          <div class="box box-bot">
            <div class="box-header">
              <span class="box-title-with-btn"><i class="fa fa-inbox">
                  </i> Store Staff Set Up
              </span>
              <div class="col-sm-8 form-horizontal pull-right">
                  <div class="col-sm-offset-9 col-sm-3">
                      <button class="btn btn-block btn-info" id="addstaff" store="<?php echo $storeid; ?>"><i class="fa fa-user-plus"></i> Add New User</button>
                  </div>
              </div>
            </div>
            <div class="box-content">
              <div class="row">
                <div class="col-sm-12">
                  <table class="table" id="staff">
                    <thead>
                      <tr>
                        <th>Username</th>                    
                        <th>Firstname</th>
                        <th>Lastname</td>
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
                          <td><?php echo ucfirst($key->ss_firstname); ?></td>
                          <td><?php echo ucfirst($key->ss_lastname); ?></td>
                          <td><?php echo ucfirst($key->ss_idnumber); ?></td>
                          <td><?php echo ucfirst($key->ss_usertype); ?></td>
                          <td><?php echo _dateFormat($key->ss_date_created); ?></td>
                          <td><?php echo ucfirst($key->ss_status); ?></td>
                          <td>
                            <i class="fa fa-pencil-square-o falink sstaff" staffid="<?php echo $key->ss_id; ?>" title="Update"></i><i class="fa fa-undo fa-active ssreset"title="Reset Password"></i>
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
    </div>
  </div>
<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/staff.js"></script>
<?php include 'footer.php' ?>