<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  $select = "transaction_stores.trans_number,
    SUM(customer_internal_ar.ar_dbamt) as db,
    SUM(customer_internal_ar.ar_cramt) as cr,
    customer_internal.ci_group,
    customer_internal.ci_type,
    customer_internal.ci_name,
    customer_internal.ci_code";
  $where =" transaction_stores.trans_type='3'
    AND
      customer_internal_ar.ar_type='1'
    AND
      transaction_stores.trans_store='".$_SESSION['gc_store']."'
    GROUP BY 
      customer_internal_ar.ar_cuscode 
    ORDER BY customer_internal_ar.ar_cuscode ASC";
  $join = 'INNER JOIN
      customer_internal_ar
    ON
      customer_internal_ar.ar_trans_id = transaction_stores.trans_sid
    INNER JOIN
      customer_internal
    ON
      customer_internal.ci_code=customer_internal_ar.ar_cuscode';

  $arlist = getAllData($link,'transaction_stores',$select,$where,$join,'');

  $group = array('','Head Office','Subs. Admin');
  $type = array('','Supplier','Customer','V.I.P.');
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
                    <a href="#tab1default" data-toggle="tab">AR List</a>
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
                                  <th>Code</th>
                                  <th>Customer Name</th>
                                  <th>Group</th>
                                  <th>Type</th>
                                  <th>Last Transaction</th>
                                  <th style="text-align:right; padding-right:40px;">Balance</th>
                                  <th>Ledger</th>
                                </tr>                              
                              </thead>
                              <tbody>
                                <?php 
                                  $totsales = 0;
                                  foreach ($arlist as $ar): 
                                ?>
                                  <tr>
                                    <td><?php echo $ar->ci_code; ?></td>
                                    <td><?php echo ucwords($ar->ci_name); ?></td>
                                    <td><?php echo ucwords($group[$ar->ci_group]); ?></td>
                                    <td><?php echo ucwords($type[$ar->ci_type]); ?></td>
                                    <td><?php ?></td>
                                    <td style="text-align:right; padding-right:40px;">
                                      <?php 
                                        $stotal =  $ar->db - $ar->cr;
                                        echo '&#8369 '.number_format($stotal,2); 
                                        $totsales += $stotal;
                                      ?>
                                    </td>
                                    <td><a href="internalcustomerledger.php?customer=<?php echo $ar->ci_code; ?>"><i class="fa fa-fa fa-eye faeye" title="View"></i></a></td>
                                  </tr>
                                <?php endforeach ?>
                              </tbody>
                              <tfoot>
                                <tr>
                                  <th colspan="5" style="text-align:right;">Total Balance:</th>
                                  <th style="text-align:right; padding-right:40px;">&#8369 <?php echo number_format($totsales,2); ?></th>
                                  <th ></th>
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