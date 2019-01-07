<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  $stores = getStores($link);
?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
  	<div class="row">
  		<div class="col-sm-12">
    		<div class="box box-bot">
      			<div class="box-header">
              <span class="box-title-with-btn"><i class="fa fa-inbox">
                  </i> Store Set Up
              </span>
              <div class="col-sm-8 form-horizontal pull-right p-right">
                  <div class="col-sm-offset-9 col-sm-3">
                      <button class="btn btn-block btn-info" id="addnewstore"><i class="fa fa-user-plus"></i> Add New Store</button>
                  </div>
              </div> 
      			</div>
     			<div class="box-content form-container">
     				<table class="table" id="stores">
     					<thead>
     						<tr>
     							<th>Store Name</th>
     							<th>Store Code</th>
     							<th>Company Code</th> 
                  <th>Default Password</th> 
                  <th>Issue Receipt?</th>   							
     						</tr>
     					</thead>
     					<tbody class="storelist">
                <?php foreach ($stores as $key): ?>
                  <tr>
                    <td><?php echo $key->store_name; ?></td>
                    <td><?php echo $key->store_name; ?></td>
                    <td><?php echo $key->store_name; ?></td>
                    <td><?php echo $key->default_password; ?></td>
                    <td>
                      <div class="onoffswitch">
                          <input type="checkbox" name="<?php echo $key->store_id; ?>" class="onoffswitch-checkbox" id="<?php echo $key->store_id; ?>myonoffswitch" <?php echo $key->issuereceipt=='yes' ? "checked" : "" ?>>
                          <label class="onoffswitch-label" for="<?php echo $key->store_id; ?>myonoffswitch">
                              <span class="onoffswitch-inner"></span>
                              <span class="onoffswitch-switch"></span>
                          </label>
                      </div>              
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