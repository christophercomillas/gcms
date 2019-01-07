<?php
  session_start();
  include '../function.php';
  require 'header.php';

 if(isset($_GET['proid']))
 {
    $proid = $_GET['proid'];
    if(trim($proid)!='' && is_numeric($proid)){
	    if(checkIfSRReceived($link,$proid))
	    {
	     header('location:index.php');
	    }
	} 
	else 
	{
		header('location:index.php');
	}
 }
 else 
 {
    header('location:index.php');
 }

 $prodDetails = getReceivedDetails($link,$proid);

?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
    <div class="row">      
      <div class="col-sm-6">
          <div class="box box-bot">
            <div class="box-header"><h4><i class="fa fa-inbox"></i> GC Validation</h4></div>
              <div class="box-content form-container">
                <?php if(!checkIfSRReceived($link,$proid)): ?>
                <form class="form-horizontal" id="gcvalidation" action="../ajax.php?action=validategc" method="POST">
                	<?php foreach ($prodDetails as $key): ?>
                		<input type="hidden" name="prod_id" value="<?php echo $proid; ?>">
	                   	<div class="form-group">
	                      <label class="col-sm-5 control-label">Date Received:</label>
	                      <div class="col-sm-5">
	                        <input type="text" class="form form-control" value="<?php echo _dateFormat($key->csrr_datetime); ?>" disabled="disabled">
	                      </div>
	                    </div>
	                    <div class="form-group">
	                      <label class="col-sm-5 control-label">Production Request No:</label>
	                      <div class="col-sm-5">
	                        <input type="text" class="form form-control" value="<?php echo $key->pe_num; ?>" disabled="disabled">
	                      </div>
	                    </div>
						<div class="form-group">
						<label class="col-sm-5 control-label control-validation">GC Barcode: </label>
							<div class="col-sm-7">                      
							  <input data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': ''" class="form-control input-lg input-validation" id="gcbarcode" name="gcbarcode" autocomplete="off" maxlength="13" autofocus/>
							</div>
						</div>
						<div class="form-group">                    
							<div class="col-sm-offset-8 col-sm-4">
							  <button type="submit" class="btn btn-block btn-primary"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Validate</button>
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
      <?php if(!checkIfSRReceived($link,$proid)): ?>
      <div class="col-sm-4">
          <div class="box box-bot">
            <div class="box-header"><h4><i class="fa fa-inbox"></i> GC for Validation</h4></div>
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
                        <td><?php echo numRowsForGCValidation($link,'1',$proid); ?></td>
                    </tr>
                    <tr>
                        <td>&#8369 200.00</td>
                        <td><?php echo numRowsForGCValidation($link,'2',$proid); ?></td>
                    </tr>
                    <tr>
                        <td>&#8369 500.00</td>
                        <td><?php echo numRowsForGCValidation($link,'3',$proid); ?></td>
                    </tr>
                    <tr>
                        <td>&#8369 1000.00</td>
                        <td><?php echo numRowsForGCValidation($link,'4',$proid); ?></td>
                    </tr>
                    <tr>
                        <td>&#8369 2000.00</td>
                        <td><?php echo numRowsForGCValidation($link,'5',$proid); ?></td>
                    </tr>
                    <tr>
                        <td>&#8369 5000.00</td>
                        <td><?php echo numRowsForGCValidation($link,'6',$proid); ?></td>
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