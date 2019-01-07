<?php 
  session_start();
  include '../function.php';
  require 'header.php';  

  $gc = getVerifiedGCWithTransaction($link,$storeid);



?>

<?php require '../menu.php'; ?>
  
  <div class="main fluid">
	<div class="row">
    <div class="col-xs-12">
      <div class="box box-bot">
        <div class="box-header"><h4><i class="fa fa-inbox"></i> Transacted GC List</h4></div>
        <div class="box-content">
<!--         `store_verification`.`vs_barcode`,
        `store_verification`.`vs_date`,
        `store_verification`.`vs_time`,
        `store_verification`.`vs_tf_balance`,
        `store_verification`.`vs_tf_eod`,
        `customers`.`cus_fname`,
        `customers`.`cus_lname`,
        `stores`.`store_name`,
        `users`.`firstname`,
        `users`.`lastname` -->
          <table class="table" id="storeod">
            <thead>
              <tr>
                <th>Barcode</th>
                <th>Date</th>
                <th>Time</th>
                <th>Balance</th>
                <th>Customer Name</th>
                <th>Verified By</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($gc as $g): ?>
                <tr>
                  <td><?php echo $g->vs_barcode; ?></td>
                  <td><?php echo _dateFormat($g->vs_date); ?></td>
                  <td><?php echo _timeFormat($g->vs_time); ?></td>
                  <td><?php echo number_format($g->vs_tf_balance,2); ?></td>
                  <td><?php echo ucwords($g->cus_fname.' '.$g->cus_lname); ?></td>
                  <td><?php echo ucwords($g->firstname.' '.$g->lastname); ?></td>
                  <td><i class="fa fa fa-search falink sstaff" onclick="textfiletranx(<?php echo $g->vs_barcode; ?>)"></i></td>
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

<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/store.js"></script>
<?php include 'footer.php' ?>