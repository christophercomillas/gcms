<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  if(isset($_GET['card']))
  {
    $card = cleanURL($_GET['card']);
    if(!checkIfExist($link,'ccard_id','credit_cards  ','ccard_id',$card))
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
                  <a href="#tab1default" data-toggle="tab"><?php echo ucwords(getField($link,'ccard_name','credit_cards','ccard_id',$card)); ?> Sales</a>
                </li>
                  <span class="pull-right">
                    <select class="form-control">
                      <option>-Select Store-</option>
                    </select>
                  </span>
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
                                <th>Store</th>
                                <th>Transaction #</th>
                                <th>Card #</th>
                                <th>Card Exp. Date</th>
                                <th>Amount Due</th>
                                <th>View</th>
                              </tr>                              
                            </thead>
                            <tbody>
                              <?php
                                $select = "creditcard_payment.ccpayment_id,
                                          creditcard_payment.cc_cardnumber,
                                          creditcard_payment.cc_cardexpired,
                                          customer_internal_ar.ar_datetime,
                                          transaction_stores.trans_number,
                                          customer_internal_ar.ar_dbamt,
                                          stores.store_name";
                                $join ="INNER JOIN
                                          customer_internal_ar
                                        ON
                                          customer_internal_ar.ar_trans_id = creditcard_payment.cctrans_transid
                                        INNER JOIN
                                          transaction_stores
                                        ON
                                          transaction_stores.trans_sid = creditcard_payment.cctrans_transid
                                        INNER JOIN
                                          stores
                                        ON
                                          stores.store_id = transaction_stores.trans_store";
                                $where = "creditcard_payment.cc_creaditcard='".$card." '
                                  AND customer_internal_ar.ar_type='2'";
                                $limit ="ORDER BY transaction_stores.trans_sid DESC";
                                $cd = getAllData($link,'creditcard_payment',$select,$where,$join,$limit);
                                foreach ($cd as $c):
                              ?>
                              <tr>
                                <td><?php echo _dateFormat($c->ar_datetime); ?></td>
                                <td><?php echo ucwords($c->store_name); ?></td>
                                <td><?php echo $c->trans_number; ?></td>
                                <td><?php echo $c->cc_cardnumber; ?></td>
                                <td><?php echo _dateFormat($c->cc_cardexpired); ?></td>
                                <td><?php echo '&#8369 '.number_format($c->ar_dbamt,2); ?></td>
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
<script type="text/javascript" src="../assets/js/admin.js"></script>
<?php include 'footer.php' ?>