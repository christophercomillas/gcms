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
      	<div class="box-header"><h4><i class="fa fa-inbox"></i> Manage Supplier</h4></div>
      	<div class="box-content">
          <div class="row">
            <div class="col-sm-12">
              <button class="btn btn-primary pull-right" id="sup-add"><i class="fa fa-user"></i> Add New Supplier</button>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <table class="table customer">
                <thead>
                  <tr>
                    <th>Company Name</th>
                    <th>Contact Person</th>
                    <th>Company Number</th>
                    <th>Address</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody class="body-supplier">
                <?php foreach ($data as $key => $value): ?>
                    <tr>
                        <td><?php echo $value['gcs_companyname'] ?></td>
                        <td><?php echo $value['gcs_contactperson']; ?></td>
                        <td><?php echo $value['gcs_contactnumber']; ?></td>
                        <td><?php echo $value['gcs_address']; ?></td>
                        <td>
                            <a href="<?php echo $value['gcs_id']; ?>" class="cus-update" alt="update"><i class="fa fa-cogs"></i>
                            </a>
                            <a href="<?php echo $value['gcs_id']; ?>" class="cus-delete" alt="update"><i class="fa fa-minus-square"></i>
                            </a>
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