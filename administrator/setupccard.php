<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  $select = 'users.firstname, 
    users.lastname, 
    credit_cards.ccard_name, 
    credit_cards.ccard_status, 
    credit_cards.ccard_created';
  $where = "1";
  $join = 'INNER JOIN users ON users.user_id = credit_cards.ccard_by';

  $ccard = getAllData($link,'credit_cards',$select,$where,$join,'');  
?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
  	<div class="row row-nobot">
  		<div class="col-sm-12">
    		<div class="box box-bot">
      			<div class="box-header">
              <span class="box-title-with-btn"><i class="fa fa-inbox">
                  </i> Credit Card Setup
              </span>
              <div class="col-sm-8 form-horizontal pull-right p-right">
                  <div class="col-sm-offset-8 col-sm-4">
                      <button class="btn btn-block btn-info" type="button" onclick="addccard();"><i class="fa fa-credit-card"></i> Add New Credit Card</button>
                  </div>
              </div>   
            </div>
     			<div class="box-content form-container">
            <div class="row">
              <div class="col-xs-9">
         				<table class="table dataTable" id="userlist">
         					<thead>
         						<tr>
         							<th>Credit Card</th>
         							<th>Date Created</th>
                      <th>Created By</th>
                      <th>Status</th>
                      <th></th>
         						</tr>
         					</thead>
                  <tbody class="tbody-userlist">
                    <?php foreach ($ccard as $c): ?>
                      <tr>
                        <td><?= ucwords($c->ccard_name); ?></td>
                        <td><?= _dateFormat($c->ccard_created); ?></td>
                        <td><?= ucwords($c->firstname.' '.$c->lastname); ?></td>
                        <td>
                          <?php if ($c->ccard_status): ?>
                            <span class="label label-success">Active</span>
                          <?php else: ?>                            
                            <span class="label label-danger">Inactive</span>
                          <?php endif ?>
                        </td>
                        <td></td>
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
<script type="text/javascript" src="../assets/js/admin.js"></script>
<?php include 'footer.php' ?>