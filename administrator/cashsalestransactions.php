<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  if(isset($_GET['storeid']))
  {
    $storeid = cleanURL($_GET['storeid']);
    if(!checkIfExist($link,'store_id','stores','store_id',$storeid))
    {
      die('store id not found.');
    }
  }
  else 
  {
    die('Please set store id.');
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
                  <a href="#tab1default" data-toggle="tab"><?php echo getField($link,'store_name','stores','store_id',$storeid); ?> (Cash Sales)</a>
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
                                <th>Date</th>
                                <th>Transaction #</th>
                                <th style="text-align:right;">Total GC</th>
                                <th style="text-align:right;">Total Line Discount</th>
                                <th style="text-align:right;">Subtotal Discount</th>
                                <th style="text-align:right;">Amount Due</th>
                                <th>View</th>
                              </tr>                              
                            </thead>
                            <tbody>
                              <?php
                                $where = 'transaction_stores.trans_store='.$link->real_escape_string($storeid).' AND transaction_stores.trans_type=1';
                                $select = ' transaction_stores.trans_sid,
                                          transaction_stores.trans_number,
                                          transaction_stores.trans_datetime,
                                          transaction_payment.payment_docdisc,
                                          transaction_payment.payment_linediscount,
                                          transaction_payment.payment_amountdue,
                                          transaction_payment.payment_stotal';
                                $join = 'INNER JOIN
                                      transaction_payment
                                    ON
                                      transaction_payment.payment_trans_num = transaction_stores.trans_sid';

                                $db = getalldata($link,'transaction_stores',$select,$where,$join,'');
                                
                                foreach ($db as $d):
                              ?>
                              <tr>
                                <td><?php echo _dateFormat($d->trans_datetime); ?></td>
                                <td><?php echo $d->trans_number; ?></td>
                                <td style="text-align:right;"><?php echo '&#8369 '.number_format($d->payment_stotal,2); ?></td>
                                <td style="text-align:right;"><?php echo '&#8369 '.number_format($d->payment_linediscount,2); ?></td>
                                <td style="text-align:right;"><?php echo '&#8369 '.number_format($d->payment_docdisc,2); ?></td>
                                <td style="text-align:right;"><?php echo '&#8369 '.number_format($d->payment_amountdue,2); ?></td>
                                <td><i class="fa fa-fa fa-eye faeye" title="View"></i></td>
                              </tr>
                              <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                              <tr>
                                <th></th>
                                <th style="text-align:right;">Total:</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
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
<script type="text/javascript" src="../assets/js/admin.js"></script>
<?php include 'footer.php' ?>