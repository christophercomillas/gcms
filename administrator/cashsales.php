<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  $select = "store_name,store_id";
  $where ="1";
  $stores = getAllData($link,'stores',$select,$where,'','');
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
                  <a href="#tab1default" data-toggle="tab">Cash Sales</a>
                </li>
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
                                <th>Store Name</th>
                                <th style="text-align:center;">Cash Sales</th>
                                <th style="text-align:center">View Transactions</th>
                              </tr>                              
                            </thead>
                            <tbody>
                              <?php
                              $cashsales = 0; 
                              foreach ($stores as $s): ?>
                                <tr>
                                  <td><?php echo $s->store_name?></td>
                                  <td style="text-align:right; padding-right:120px;">
                                    <?php                                      
                                      $where = 'transaction_stores.trans_store='.$s->store_id.' AND transaction_stores.trans_type=1';
                                      $select = 'IFNULL(SUM(transaction_payment.payment_amountdue),0.00) as cashsales';
                                      $join = 'INNER JOIN
                                          transaction_payment
                                        ON
                                          transaction_payment.payment_trans_num = transaction_stores.trans_sid';

                                      $db = getSelectedData($link,'transaction_stores',$select,$where,$join,'');
                                                                           
                                      echo '&#8369 '.number_format($db->cashsales,2);
                                      $cashsales += $db->cashsales;
                                    ?>                                      
                                  </td>
                                  <td style="text-align:center"><a href="cashsalestransactions.php?storeid=<?php echo $s->store_id; ?>"><i class="fa fa-fa fa-eye faeye" title="View"></i></a></td>
                                </tr>
                              <?php endforeach ?>
                            </tbody>
                            <tfoot>
                              <th></th>
                              <th style="text-align:right; padding-right:120px;"><span style="margin-right:20px;">Total Cash Sales:</span> &#8369 <?php echo number_format($cashsales,2); ?></th>
                              <th></th>
                            </tfoot>
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
<script type="text/javascript" src="../assets/js/admin.js"></script>
<?php include 'footer.php' ?>