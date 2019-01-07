<?php 
  session_start();
  include '../function.php';
  include 'header.php';

  $ledger = getLedgerCheck($link);

?>

<?php require '../menu.php'; ?>

    <div class="main fluid">    
      <div class="row">
        <div class="col-sm-12">
          <div class="box box-bot">
            <div class="box-header">
              <span class="box-title-with-btn"><i class="fa fa-inbox"></i> Check Ledger</span>
              <div class="col-sm-8 form-horizontal pull-right">
                <label class="col-sm-2 control-label">Start Date</label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" id="dp1" readonly="readonly">
                </div>
                <label class="col-sm-2 control-label">End Date</label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" id="dp2" readonly="readonly">
                </div>
                <div class="col-sm-2">
                  <button class="btn btn-block btn-info">Submit</button>
                </div>
              </div>
            </div>
            <div class="box-content">
              <table class="table">
                <thead>
                  <tr>
                    <th>Ledger No.</th>
                    <th>Date</th>
                    <th>Trans. Desc.</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Posted By</th>
                  </tr>                
                </thead>
                <tbody>
                  <?php foreach ($ledger as $key): ?>
                    <tr>
                      <td><?php echo $key->cledger_no; ?></td>
                      <td><?php echo _dateFormat($key->cledger_datetime); ?></td>
                      <td><?php echo $key->cledger_desc; ?></td>
                      <td><?php echo $key->cdebit_amt == 0 ? '' : '&#8369 '.number_format($key->cdebit_amt,2); ?></td>
                      <td><?php echo $key->ccredit_amt == 0 ? '' : '&#8369 '.number_format($key->ccredit_amt,2); ?></td> 
                      <td><?php echo ucwords($key->firstname.' '.$key->lastname); ?></td>            
                    </tr>
                  <?php endforeach ?>                  
                </tbody>               
              </table>
              <div class="row">
                <div class="pull-right">
                  <div class="col-sm-2">
                    <div class="btn btn-info">
                      <a href="ledger_excel.php">Export (Excel)</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div><!-- end fluid div -->
<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/ledger.js"></script>
<?php include 'footer.php' ?>

