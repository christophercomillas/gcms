<?php 
  session_start();
  include '../function.php';
  require 'header.php';
  $data = getResults($link,'supplier','gcs_id');
?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="box box-bot">
      	<div class="box-header">
          <span class="box-title-with-btn"><i class="fa fa-inbox">
              </i> Manage Supplier
          </span>
          <div class="col-sm-8 form-horizontal pull-right p-right">
              <div class="col-sm-offset-9 col-sm-3">
                  <button class="btn btn-block btn-info" id="sup-add" onclick="addnewsupplier()"><i class="fa fa-user-plus"></i> Add New Supplier</button>
              </div>
          </div>  
        </div>
      	<div class="box-content">
          <div class="row">
            <div class="col-sm-12">
              <table class="table dataTable no-footer" id="customer">
                <thead>
                  <tr>
                    <th>Company Name</th>
                    <th>Account Name</th>
                    <th>Contact Person</th>
                    <th>Company Number</th>
                    <th>Address</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody class="body-supplier">
                  <?php foreach ($data as $key => $value): ?>
                  <tr>
                    <td><?php echo $value['gcs_companyname'] ?></td>
                    <td><?php echo ucwords($value['gcs_accountname']); ?></td>
                    <td><?php echo $value['gcs_contactperson']; ?></td>
                    <td><?php echo $value['gcs_contactnumber']; ?></td>
                    <td><?php echo $value['gcs_address']; ?></td>
                    <td>
                        <i class="fa fa-pencil-square-o falink uusers" title="Update" onclick="updateSupplierDetails(<?php echo $value['gcs_id']; ?>)"></i>
                      <!-- <a href="<?php echo $value['gcs_id']; ?>" class="cus-update" alt="update"><i class="fa fa-cogs"></i>
                       </a>      -->                  
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
<script type="text/javascript" src="../assets/js/marketing.js"></script>
<?php include 'footer.php' ?>