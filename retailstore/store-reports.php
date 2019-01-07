<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  $denom = getDenomination($link);
?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
  	<div class="row">
  		<div class="col-sm-5">
  			<div class="box box-bot">
        	<div class="box-header"><h4><i class="fa fa-inbox"></i> Reports (Verified GC)</h4></div>
        	<div class="box-content">
            <div class="col-sm-12 form-container">
              <form class="form-horizontal" id="gcSalesReportMarketing" method="POST" action="../ajax.php?action=gcSalesReportMarketing">
                <input type="hidden" value="<?php echo $store_id; ?>" name="storeid">
                <div class="form-group">
                  <label class="col-sm-5 control-label label-lg">Denomination: </label>
                  <div class="col-sm-7">
                    <select class="form form-control input-lg" name="denom">
                      <option value="0">All</option>
                      <?php foreach ($denom as $d ): ?>
                        <option value="<?php echo $d->denom_id; ?>"><?php echo number_format($d->denomination,2); ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>                  
                </div>
                <div class="form-group">
                  <label class="col-sm-5 control-label label-lg">Start: </label>
                  <div class="col-sm-7">
                    <input type="text" name="datestart" class="form-control input-lg" id="dp1" readonly="readonly">
                  </div>                  
                </div>
                <div class="form-group">
                  <label class="col-sm-5 control-label label-lg">End: </label>
                  <div class="col-sm-7">
                    <input type="text" name="dateend" class="form-control input-lg" id="dp2" readonly="readonly">
                  </div>                  
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-6 col-sm-6">
                    <button class="btn btn-primary btn-md btn-block"><i class="fa fa-file-pdf-o pdf-generate"></i>  Generate PDF</button>
                  </div>                 
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-6 col-sm-6">
                    <button class="btn btn-primary btn-md btn-block"><i class="fa fa-file-excel-o pdf-generate"></i> Generate Excel</button>
                  </div>                 
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-6 col-sm-6">
                    <button class="btn btn-primary btn-md btn-block" id="strepview"><i class="fa fa fa-eye pdf-generate"></i> View</button>
                  </div>                 
                </div>
                <div class="response"></div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/reports.js"></script>
<?php include 'footer.php' ?>