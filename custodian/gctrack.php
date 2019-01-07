<?php 
  session_start();
  include '../function.php';
  require 'header.php';
  require '../menu.php';
?>

  <div class="main fluid">
  	<div class="row">
  		<div class="col-sm-5">
  			<div class="box box-bot">
        	<div class="box-header"><h4><i class="fa fa-inbox"></i> GC Tracking</div>
        	<div class="box-content">
            <div class="row zmarginbot">
              <div class="col-xs-12">
                <form class="form-horizontal" action="../ajax.php?action=gctrack" method="POST" id="gctrack" autocomplete="off">   
                  <div class="form-group">           
                    <label class="col-xs-5 control-label">GC Barcode #</label>
                    <div class="col-xs-7">
                      <input type="text" class="form-control inptxt input-sm" maxlength="13" name="barcode" id="trackbarcode" autofocus>
                    </div>
                  </div>
                  <div class="form-group">                   
                    <div class="col-xs-offset-7 col-xs-5">
                      <input type="submit" class="btn btn-block btn-primary" value="Submit">
                    </div>
                  </div>
                  <div class="response">
                  </div>
                </form>
              </div>
            </div>
        </div>
    </div>
  </div>

<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/cus.js"></script>
<?php include 'footer.php' ?>