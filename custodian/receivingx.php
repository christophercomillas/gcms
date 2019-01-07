<?php 
  session_start();
  include '../function.php';
  require 'header.php';
?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
    <div class="row">
      <div class="col-sm-6">
          <div class="box box-bot">
            <div class="box-header"><h4><i class="fa fa-inbox"></i> GC Receiving</h4></div>
              <div class="box-content form-container">
                <form class="form-horizontal" action="../ajax.php?action=receivegc" method="POST">             
                  <div class="form-group">
                    <label class="col-sm-5 control-label">Date Received:</label>
                    <div class="col-sm-5">
                      <input type="text" class="form form-control" value="<?php echo _dateFormat($todays_date); ?>" disabled="disabled">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-5 control-label">Ref No:</label>
                    <div class="col-sm-5">
                      <input type="text" class="form form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-5 control-label">Supplier:</label>
                    <div class="col-sm-7">
                      <input type="text" class="form form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-5 control-label">Remarks:</label>
                    <div class="col-sm-7">
                      <input type="text" class="form form-control">
                    </div>
                  </div>
                </form>
              </div>
          </div>
      </div> <!-- end of col -->
      <div class="col-sm-4">
          <div class="box box-bot">
            <div class="box-header"><h4><i class="fa fa-inbox"></i> Available GC for validation</h4></div>
              <div class="box-content form-container">
                <table class="table table-responsive gcforvalidation-heading">
                  <thead>
                      <tr>
                          <th>Denomination</th>
                          <th>pc(s) left</th>
                      </tr>
                  </thead>
                  <tbody class="gcforvalidation">
                    <tr>
                        <td>&#8369 100.00</td>
                        <td><?php echo numRowsForValidation($link,'gc','gc_validated','','denom_id','1'); ?></td>
                    </tr>
                    <tr>
                        <td>&#8369 200.00</td>
                        <td><?php echo numRowsForValidation($link,'gc','gc_validated','','denom_id','2'); ?></td>
                    </tr>
                    <tr>
                        <td>&#8369 500.00</td>
                        <td><?php echo numRowsForValidation($link,'gc','gc_validated','','denom_id','3'); ?></td>
                    </tr>
                    <tr>
                        <td>&#8369 1000.00</td>
                        <td><?php echo numRowsForValidation($link,'gc','gc_validated','','denom_id','4'); ?></td>
                    </tr>
                    <tr>
                        <td>&#8369 2000.00</td>
                        <td><?php echo numRowsForValidation($link,'gc','gc_validated','','denom_id','5'); ?></td>
                    </tr>
                    <tr>
                        <td>&#8369 5000.00</td>
                        <td><?php echo numRowsForValidation($link,'gc','gc_validated','','denom_id','6'); ?></td>
                    </tr>                    
                  </tbody>
                </table>
              </div>
          </div>    
      </div>
    </div>
  </div>

<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/cus.js"></script>
<script type="text/javascript" src="../assets/js/logout.js"></script>
<?php include 'footer.php' ?>