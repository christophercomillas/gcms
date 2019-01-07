<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  $ledgerData = getLedgerData($link,$storeid);

  $select ='ledger_store.sledger_credit,
    ledger_store.sledger_date,
    transaction_stores.trans_number,
    ledger_store.sledger_ref,
    store_staff.ss_firstname,
    store_staff.ss_lastname';
  $where = "ledger_store.sledger_store='".$storeid."'
    AND
      ledger_store.sledger_trans='GCR'";
  $join = 'INNER JOIN
      transaction_stores
    ON
      transaction_stores.trans_sid = ledger_store.sledger_ref
    INNER JOIN
      store_staff
    ON
      store_staff.ss_id = transaction_stores.trans_cashier
    ';

  $revalData = getAllData($link,'ledger_store',$select,$where,$join,'');

?>

<?php require '../menu.php'; ?>

  <div class="main fluid">    

	<div class="row">
    <div class="col-sm-12">
      <div class="col-md-12 pad0">
        <div class="panel with-nav-tabs panel-info">
            <div class="panel-heading">
              <ul class="nav nav-tabs">
                <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Store GC Ledger</a></li>
                <li><a href="#tab2default" data-toggle="tab">Store Revalidation</a></li>
              </ul>
            </div>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="tab1default">
                      <div class="row">
                        <div class="col-xs-12">
                          <table class="table table-adj" id="ledgertable">
                            <thead>
                              <tr>
                                <th>Document #</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Description</th>
                                <th class="tcenter">Debit</th>
                                <th class="tcenter">Credit</th>
                                <th class="tcenter">Sales Discount</th>
                                <th class="tcenter">Balance</th>
                                <th>View</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                                $doc = 0; 
                                $bal = 0;
                                foreach($ledgerData as $ld):
                                $doc++;              
                                if($ld->sledger_trans==='GCE')
                                {
                                  $bal += $ld->sledger_debit;
                                  $entry = 1;
                                }           
                                else if($ld->sledger_trans==='GCS')
                                {
                                  $totpay = $ld->sledger_credit+$ld->sledger_trans_disc;
                                  $bal -= $totpay;
                                  $entry = 2;
                                }
                                else if($ld->sledger_trans==='GCREF')
                                {
                                  $bal += $ld->sledger_debit;
                                  $entry = 4;
                                }
                                else if($ld->sledger_trans==='GCTOUT')
                                {
                                  $totpay = $ld->sledger_credit+$ld->sledger_trans_disc;
                                  $bal -= $totpay;
                                  $entry = 5;
                                }
                              ?>
                                <tr>
                                  <td><?php echo addZeroToStringZ($doc,5)?></td>
                                  <td><?php echo _dateFormat($ld->sledger_date); ?></td>
                                  <td><?php echo _timeFormat($ld->sledger_date); ?></td>
                                  <td><?php echo $ld->sledger_desc; ?></td>
                                  <td class="tright"><?php echo number_format($ld->sledger_debit,2); ?></td>
                                  <td class="tright"><?php echo number_format($ld->sledger_credit,2); ?></td>
                                  <td class="tright"><?php echo number_format($ld->sledger_trans_disc,2); ?></td>
                                  <td class="tright"><?php echo number_format($bal,2); ?></td>
                                  <td><i class="fa fa-fa fa-eye faeye" title="View" onclick="storeLedgerDialog(<?php echo $ld->sledger_ref.','.$entry?>);"></i></td>
                                </tr>
                              <?php endforeach; ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="tab2default">
                      <table class="table" id="ledgertable">
                        <thead>
                          <tr>
                            <th>Transaction #</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th># of GC</th>
                            <th>Amount</th>
                            <th>Cashier</th>
                            <th>View</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
                            foreach ($revalData as $r):
                            $entry = 3; 
                          ?>
                            <tr>
                              <td><?php echo $r->trans_number; ?></td>
                              <td><?php echo _dateFormat($r->sledger_date); ?></td>
                              <td><?php echo _timeFormat($r->sledger_date); ?></td>
                              <td>
                                  <?php 
                                    $select = "COUNT(transaction_revalidation.reval_barcode) as cnt";
                                    $where = "transaction_revalidation.reval_trans_id=".$r->sledger_ref;
                                    $c = getSelectedData($link,'transaction_revalidation',$select,$where,'','');
                                    echo $c->cnt;
                                  ?>
                              </td>
                              <td><?php echo number_format($r->sledger_credit,2); ?></td>
                              <td><?php echo ucwords($r->ss_firstname.' '.$r->ss_lastname); ?></td>
                              <td><i class="fa fa-fa fa-eye faeye" title="View" onclick="storeLedgerDialog(<?php echo $r->sledger_ref.','.$entry?>);"></i></td>
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
<script type="text/javascript" src="../assets/js/store.js"></script>
<?php include 'footer.php' ?>