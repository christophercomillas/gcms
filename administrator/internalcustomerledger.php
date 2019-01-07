<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  if(isset($_GET['cusid']))
  {
    $cusid = (int)$_GET['cusid'];
    if(!numRows($link,'customer_internal','ci_code',$cusid)>0)
    {
      header('location:arlist.php');
    }

  }
  else 
  {
    header('location:arlist.php');
  }

  // get customer name
  $select = 'ci_name';
  $where = "ci_code=$cusid";

  $cusname = getSelectedData($link,'customer_internal',$select,$where,'','');

  $select = 'customer_internal_ar.ar_dbamt,
    stores.store_name,
    customer_internal_ar.ar_cramt,
    customer_internal_ar.ar_datetime,
    customer_internal_ar.ar_trans_id,
    customer_internal_ar.ar_type';
  $where = "customer_internal_ar.ar_cuscode=$cusid AND customer_internal_ar.ar_type=1";
  $join = 'INNER JOIN
      transaction_stores 
    ON
      transaction_stores.trans_sid = customer_internal_ar.ar_trans_id
    INNER JOIN
      stores
    ON 
      stores.store_id = transaction_stores.trans_store';
  $trans = getAllData($link,'customer_internal_ar',$select,$where,$join,'');


?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
    <div class="row row-nobot">
      <div class="col-sm-12">
        <div class="box box-bot">
            <div class="box-header">
              <span class="box-title-with-btn"><i class="fa fa-user"></i> <?php echo ucwords($cusname->ci_name); ?>
              </span>  
            </div>
          <div class="box-content form-container">
          <div class="row">
          <div class="col-xs-12">
            <table class="table" id="userlist">
              <thead>
                <th>Transaction Date</th>
                <th>Transaction Description</th>
                <th>Store</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
                <th>View</th>
              </thead>
              <tbody>
                <?php
                  $bal = 0; 
                  foreach ($trans as $t): 
                ?>
                  <tr>
                    <td><?php echo _dateFormat($t->ar_datetime); ?></td>
                    <td>
                      <?php 
                        if($t->ar_type==0): 
                      ?>
                        GC Sales
                      <?php else: ?>
                        
                      <?php endif; ?>
                    </td>
                    <td><?php echo ucwords($t->store_name); ?></td>
                    <td>
                        <?php
                          $bal = $bal +  $t->ar_dbamt;
                          echo number_format($t->ar_dbamt,2); 
                        ?>
                    </td>
                    <td>
                        <?php 
                          echo number_format($t->ar_cramt,2);
                          $bal = $bal - $t->ar_cramt; 
                        ?>
                    </td>
                    <td><?php echo number_format($bal,2); ?></td>   
                    <td><i class="fa fa-fa fa-eye faeye" title="View" onclick="viewARtransactionDetails(<?php echo $t->ar_trans_id; ?>)"></i></td>
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