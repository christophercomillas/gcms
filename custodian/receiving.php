<?php 
  session_start();
  include '../function.php';
  require 'header.php';

 if(isset($_GET['proid']))
 {
    $proid = $_GET['proid'];
    if(!checkProductionNo($link,$proid))
    {
     header('location:index.php');
    }
 }
 else 
 {
    header('location:index.php');
 }

 $details = getProductionDetails($link,$proid);

?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
    <div class="row">      
      <div class="col-sm-6">
          <div class="box box-bot">
            <div class="box-header"><h4><i class="fa fa-inbox"></i> GC Receiving</h4></div>
              <div class="box-content form-container">
                <?php if(checkIfSRReceived($link,$proid)): ?>
                <form class="form-horizontal" id="receivegc" action="../ajax.php?action=receivegc" method="POST">
                  <?php foreach ($details as $key): ?>        
                    <div class="form-group">
                      <label class="col-sm-5 control-label">Date Received:</label>
                      <div class="col-sm-5">
                        <input type="hidden" value="<?php echo $proid; ?>" name="prod_id">
                        <input type="text" class="form form-control" value="<?php echo _dateFormat($todays_date); ?>" disabled="disabled">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-5 control-label">Production Request:</label>
                      <div class="col-sm-5">
                        <input type="text" class="form form-control" id="p_num" value="<?php echo $key->pe_num; ?>" disabled="disabled">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-5 control-label">Ref No:</label>
                      <div class="col-sm-5">
                        <input type="text" class="form form-control" value="<?php echo $key->requis_rmno; ?>" disabled="disabled">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-5 control-label">Supplier:</label>
                      <div class="col-sm-7">
                        <input type="text" class="form form-control" value="<?php echo ucwords($key->gcs_companyname); ?>" disabled="disabled">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-5 control-label">Remarks/Comments:</label>
                      <div class="col-sm-7">
                        <textarea class="form form-control num" name="remarks" required></textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-5 control-label">Checked by:</label>
                      <div class="col-sm-7">
                        <input type="text" class="form form-control num" name="checked" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-5 control-label">Prepared by:</label>
                      <div class="col-sm-7">
                        <input type="text" type="submit" class="form form-control" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>">
                      </div>
                    </div>
                    <div class="form-group">                      
                      <div class="col-sm-offset-8 col-sm-4">
                        <button class="btn btn-block btn-info"><i class="fa fa-floppy-o"></i>
Save</button>
                      </div>
                    </div>
                    <div class="response">
                    </div>
                  <?php endforeach; ?>
                </form>
                <?php else: ?>
                  Yowwwww
                <?php endif; ?>
              </div>
          </div>
      </div> <!-- end of col -->
      <?php if(checkIfSRReceived($link,$proid)): ?>
      <div class="col-sm-4">
          <div class="box box-bot">
            <div class="box-header"><h4><i class="fa fa-inbox"></i> GC</h4></div>
              <div class="box-content form-container">
                <table class="table table-responsive gcforvalidation-heading">
                  <thead>
                      <tr>
                          <th>Denomination</th>
                          <th>pc(s)</th>
                      </tr>
                  </thead>
                  <tbody class="gcforvalidation">
                    <tr>
                        <td>&#8369 100.00</td>
                        <td><?php echo numRowsForValidationReceiving($link,'1',$proid); ?></td>
                    </tr>
                    <tr>
                        <td>&#8369 200.00</td>
                        <td><?php echo numRowsForValidationReceiving($link,'2',$proid); ?></td>
                    </tr>
                    <tr>
                        <td>&#8369 500.00</td>
                        <td><?php echo numRowsForValidationReceiving($link,'3',$proid); ?></td>
                    </tr>
                    <tr>
                        <td>&#8369 1000.00</td>
                        <td><?php echo numRowsForValidationReceiving($link,'4',$proid); ?></td>
                    </tr>
                    <tr>
                        <td>&#8369 2000.00</td>
                        <td><?php echo numRowsForValidationReceiving($link,'5',$proid); ?></td>
                    </tr>
                    <tr>
                        <td>&#8369 5000.00</td>
                        <td><?php echo numRowsForValidationReceiving($link,'6',$proid); ?></td>
                    </tr>                    
                  </tbody>
                </table>
              </div>
          </div>    
      </div>
      <?php endif; ?>
    </div>
  </div>

<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/cus.js"></script>
<?php include 'footer.php' ?>