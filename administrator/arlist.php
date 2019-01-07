<?php 
  session_start();
  include '../function.php';
  require 'header.php';
  $select = 'customer_internal.ci_name,
      customer_internal.ci_code,
      customer_internal.ci_address,
      customer_internal.ci_type,
      customer_internal.ci_group';
  $where = '1';
  $join = '';
  $limit = 'ORDER BY customer_internal.ci_code DESC';
  $cus = getAllData($link,'customer_internal',$select,$where,$join,$limit);

  $group = array('','Head Office','Subs. Admin');
  $type = array('','Supplier','Customer','V.I.P.');

  //get customer last transaction
  //get 
?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
    <div class="row row-nobot">
      <div class="col-sm-12">
        <div class="box box-bot">
            <div class="box-header">
              <span class="box-title-with-btn"><i class="fa fa-inbox">
                  </i> AR List
              </span>  
            </div>
          <div class="box-content form-container">
          <div class="row">
          <div class="col-xs-11">
            <table class="table" id="userlist">
              <thead>
                <th>Code</th>
                <th>Customer Name</th>
                <th>Group</th>
                <th>Type</th>
                <th>Last Transaction</th>
                <th>Balance</th>
                <th>Ledger</th>
              </thead>
              <tbody>
                <?php foreach ($cus as $c): ?>
                  <tr>
                    <td><?php echo $c->ci_code; ?></td>
                    <td><?php echo ucwords($c->ci_name); ?></td>
                    <td><?php echo $group[$c->ci_group]; ?></td>
                    <td><?php echo $type[$c->ci_type]; ?></td>
                    <td>
                      <?php 
                        $select = 'customer_internal_ar.ar_datetime';
                        $where = 'customer_internal_ar.ar_cuscode='.$c->ci_code.' AND customer_internal_ar.ar_type=1';
                        $limit = 'ORDER BY customer_internal_ar.ar_cuscode DESC';
                        $date = getSelectedData($link,'customer_internal_ar',$select,$where,'',$limit);
                        echo is_null($date) ? 'No Transaction' : _dateFormat($date->ar_datetime);
                      ?>
                    </td>
                    <td>
                      <?php 
                        $select = 'IFNULL(SUM(customer_internal_ar.ar_dbamt),0.00) - IFNULL(SUM(customer_internal_ar.ar_cramt),0.00) as SUM';
                        $where = 'customer_internal_ar.ar_cuscode='.$c->ci_code.' AND customer_internal_ar.ar_type=1';
                        $limit = '';
                        $s = getSelectedData($link,'customer_internal_ar',$select,$where,'',$limit);
                        echo '&#8369 '.number_format($s->SUM,2);                        
                      ?>
                    </td>
                    <td><a href="internalcustomerledger.php?cusid=<?php echo $c->ci_code; ?>"><i class="fa fa-fa fa-eye faeye" title="View"></i></a></td>
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