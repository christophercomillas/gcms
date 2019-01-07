<?php 
  session_start();
  include '../function.php';
  require 'header.php';

 $customers = getCustomersInternal($link);

 $group = array('','Head Office','Subs. Admin');
 $type = array('','Supplier','Customer','V.I.P.');

?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
  	<div class="row row-nobot">
  		<div class="col-sm-12">
    		<div class="box box-bot">
      			<div class="box-header">
              <span class="box-title-with-btn"><i class="fa fa-inbox">
                  </i> Customer Set Up
              </span>
              <div class="col-sm-8 form-horizontal pull-right p-right">
                  <div class="col-sm-offset-8 col-sm-4">
                      <button class="btn btn-block btn-info" type="button" onclick="addnewcustomer();"><i class="fa fa-user-plus"></i> Add New Customer</button>
                  </div>
              </div>   
            </div>
     			<div class="box-content form-container">
     				<table class="table dataTable" id="userlist">
     					<thead>
     						<tr>
     							<th>Code</th>
     							<th>Name</th>
     							<th>Address</th>
     							<th>Group</th>
                  <th>Type</th>
                  <th>Action</th>
     						</tr>
     					</thead>
              <tbody class="tbody-userlist">
                <?php foreach ($customers as $c): ?>
                  <tr>
                    <td><?php echo $c->ci_code; ?></td>
                    <td><?php echo ucwords($c->ci_name); ?></td>
                    <td><?php echo $c->ci_address; ?></td>
                    <td><?php echo $group[$c->ci_group]; ?></td>
                    <td><?php echo $type[$c->ci_type]; ?></td>
                    <td>
                      <i class="fa fa-fa fa-eye faeye" title="View"></i>
                      <i class="fa fa-pencil-square-o falink uusers" title="Update"></i>
                      <i class="fa fa-credit-card fadis" onclick="discount(<?php echo $c->ci_code; ?>);"></i>
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