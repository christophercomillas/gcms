<?php 
  session_start();
  include '../function.php';
  require 'header.php'; 

  $select ='denom_id,denomination.denomination,denomination.denom_fad_item_number,denomination.denom_barcode_start';
  $where = "denomination.denom_status='active'";
  $join = '';
  $limit = 'ORDER BY denom_id ASC';
  $denom = getAllData($link,'denomination',$select,$where,$join,$limit);

  $select = 'fds_denom';
  $where = "fds_status='pending'";
  $limit = "GROUP BY fds_denom";

  $new = getAllData($link,'for_denom_set_up',$select,$where,'',$limit);

?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
  	<div class="row row-nobot">
  		<div class="col-sm-12">
    		<div class="box box-bot">
      			<div class="box-header">
              <span class="box-title-with-btn"><i class="fa fa-inbox">
                  </i> Denomination Setup
              </span>
              <div class="col-sm-8 form-horizontal pull-right p-right">
                  <div class="col-sm-offset-8 col-sm-4">
                      <button class="btn btn-block btn-info" type="button" onclick="addDenomination();"><i class="fa fa-credit-card"></i> Add New Denomination</button>
                  </div>
              </div>   
            </div>
     			<div class="box-content form-container">
            <div class="row">
              <div class="col-xs-12">
                <?php if(count($new)>0): ?>

                  <table class="table">
                    <thead>
                      <tr>
                        <th>Denomination for Set-up</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($new as $n): ?>
                        <tr>
                          <td>
                            <button type="button" class="btn btn-default" onclick="setupdenom(<?php echo $n->fds_denom; ?>);"><i class="fa fa-pencil-square-o"> </i><?php echo ' '.$n->fds_denom ?></button><br />   
                          </td>
                        </tr>                    
                      <?php endforeach; ?>                      
                    </tbody>
                  </table>

                <?php endif; ?>
                <table class="table" id="userlist">
                  <thead>
                    <tr>
                      <th>Denomination</th>
                      <th>FAD Item Number</th>
                      <th>Barcode # Start</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody class="tbody-userlist">
                    <?php foreach ($denom as $d): ?>
                      <tr>
                        <td><?php echo number_format($d->denomination,2); ?></td>
                        <td><?php echo $d->denom_fad_item_number; ?></td>
                        <td><?php echo $d->denom_barcode_start; ?></td>
                        <td><i class="fa fa-pencil-square-o falink" title="Update" onclick="updateDenom(<?php echo $d->denom_id; ?>)"></i></td>
                      </tr>                    
                    <?php endforeach; ?>
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