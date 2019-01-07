<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  if(isset($_GET['customer']))
  {
    $customer = cleanURL($_GET['customer']);
    if(!checkIfExist($link,'ci_code','customer_internal','ci_code',$customer))
    {
      die('customer not found.');
    }
  }
  else 
  {
    die('Please set customer id.');
  }

?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="col-md-12 pad0">
        <div class="panel with-nav-tabs panel-info">
            <div class="panel-heading">
              <ul class="nav nav-tabs">
                <li class="active" style="font-weight:bold">
                  <a href="#tab1default" data-toggle="tab"><?php echo ucwords(getField($link,'ci_name','customer_internal','ci_code',$customer)); ?></a>
                </li>
                <a href="arlist.php"><span class="btn pull-right"><i class="fa fa-backward" aria-hidden="true"></i>
Back</span></a>
              </ul>
            </div>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="tab1default">
                      <div class="row">
                        <div class="col-xs-12 cardsalesload">
                          <table class="table table-adj" id="stores">
                            <thead>
                              <tr>
                                <th>Date</th>
                                <th>Transaction Description</th>
                                <th style="text-align:right;">Debit</th>
                                <th style="text-align:right;">Credit</th>
                                <th style="text-align:right; padding-right:20px;">Balance</th>
                                <th>View</th>
                              </tr>                              
                            </thead>
                            <tbody>
                              <?php 
                                $select = 'customer_internal_ar.ar_datetime,
                                  customer_internal_ar.ar_dbamt,
                                  customer_internal_ar.ar_cramt';
                                $where = "customer_internal_ar.ar_cuscode='$customer'
                                  AND
                                    customer_internal_ar.ar_type = '1'
                                  AND
                                    transaction_stores.trans_store=".$_SESSION['gc_store'];
                                $join = 'INNER JOIN
                                    transaction_stores
                                  ON
                                    transaction_stores.trans_sid = customer_internal_ar.ar_trans_id';
                                $order = 'ORDER BY ar_datetime ASC';
                                $ar = getAllData($link,'customer_internal_ar',$select,$where,$join,'');
                                $bal = 0;
                                foreach ($ar as $a):
                              ?>
                                <tr>
                                  <td><?php echo _dateFormat($a->ar_datetime); ?></td>
                                  <td></td>                                  
                                  <td style="text-align:right;">&#8369 
                                    <?php 
                                      $bal += $a->ar_dbamt;
                                      echo number_format($a->ar_dbamt,2); 
                                    ?>
                                  </td>
                                  <td style="text-align:right;">&#8369 
                                    <?php 
                                      $bal -= $a->ar_cramt;
                                      echo number_format($a->ar_cramt,2); 
                                    ?>
                                  </td>
                                  <td style="text-align:right; padding-right:20px;">&#8369 <?php echo number_format($bal,2);?></td>
                                  <td><i class="fa fa-fa fa-eye faeye" title="View"></i></td>
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
  </div>
</div>
  </div>
<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/store.js"></script>
<?php include 'footer.php' ?>