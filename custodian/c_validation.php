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
            <div class="box-header"><h4><i class="fa fa-inbox"></i> GC Validation</h4></div>
              <div class="box-content form-container">              
                <form class="form-horizontal" id="gcvalidation" action="../ajax.php?action=validategc" method="POST">
                  <div class="form-group">
                    <label class="col-sm-5 control-label control-validation">GC Barcode Number: </label>
                    <div class="col-sm-7">                      
                      <input data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': ''" class="form-control input-lg input-validation" id="gcbarcode" name="gcbarcode" autocomplete="off" maxlength="13" />
                    </div>
                  </div>
                  <div class="form-group">                    
                    <div class="col-sm-offset-8 col-sm-4">
                      <button type="submit" class="btn btn-block btn-primary"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Submit</button>
                    </div>
                  </div>
                </form>
                <div class="response gcvalidation">
                </div>
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