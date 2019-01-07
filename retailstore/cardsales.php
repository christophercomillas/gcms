<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  $select = "ccard_name, ccard_id";
  $where ="1";
  $cards = getAllData($link,'credit_cards',$select,$where,'','');
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
                    <a href="#tab1default" data-toggle="tab">Card Sales</a>
                </li>
              </ul>
            </div>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="tab1default">
                      <div class="row">
                        <div class="col-xs-12 cardsalesload">
                          <?php 
                          $select = "ccard_name, ccard_id";
                          $where ="1";
                          $cards = getAllData($link,'credit_cards',$select,$where,'','');
                          ?>
                            <table class="table table-adj" id="stores">
                              <thead>
                                  <tr>
                                    <th>Card Name</th>
                                    <th style="text-align:right;">Total Sales</th>
                                    <th style="text-align:center">View Transactions</th>
                                  </tr>                              
                              </thead>
                              <tbody>
                                <?php 
                                  $gtotal = 0;
                                  foreach ($cards as $c): 
                                ?>
                                  <tr>
                                    <td><?php echo ucwords($c->ccard_name); ?></td>
                                    <td style="text-align:right;">
                                      <span>
                                      <?php 
                                        $where = 'creditcard_payment.cc_creaditcard='.$c->ccard_id.' AND ar_type =2 AND 
                                          transaction_stores.trans_store='.$_SESSION['gc_store'];
                                        $select = 'IFNULL(SUM(customer_internal_ar.ar_dbamt),0.00) as totdb';
                                        $join = 'INNER JOIN
                                            customer_internal_ar
                                          ON
                                            creditcard_payment.cctrans_transid = customer_internal_ar.ar_trans_id
                                          INNER JOIN
                                            transaction_stores
                                          ON
                                            creditcard_payment.cctrans_transid = transaction_stores.trans_sid
                                          ';

                                        $db = getSelectedData($link,'creditcard_payment',$select,$where,$join,'');                      
                                        echo '&#8369 '.number_format($db->totdb,2);
                                        $gtotal += $db->totdb; 
                                      ?>
                                    </td>
                                    </span>
                                    <td style="text-align:center"><a href="cardsalestransactions.php?card=<?php echo $c->ccard_id; ?>"><i class="fa fa-fa fa-eye faeye" title="View"></i></a></td>
                                  </tr>
                                <?php endforeach; ?>
                              </tbody>
                              <tfoot>
                                <tr>
                                  <th></th>
                                  <th style="text-align:right;"><span style="margin-right:16px;">Grand Total:</span> &#8369 <?php echo number_format($gtotal,2); ?></th>
                                  <th></th>
                                </tr>
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
<script type="text/javascript" src="../assets/js/store.js"></script>
<?php include 'footer.php' ?>