<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  $denom = getResults($link,'denomination','denom_id');

?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
    <div class="col-sm-12">
    	<div class="row">
        <div class="col-sm-6">
          <div class="box box-bot">
            <div class="box-header"><h4><i class="fa fa-inbox"></i> GC Adjustment Entry</h4></div>
            <div class="box-content">
              <div class="col-sm-12 form-container">
                <form class="form-horizontal" action="../ajax.php?action=adjustGCEntry" id="gcAdjustEntry">
                  <!-- begin form-group -->
                  <div class="form-group">
                    <label class="col-sm-5 control-label label-lg">Denomination: </label>
                    <div class="col-sm-7">
                      <select class="form form-control input-lg" id="_denomAdj" name="den_id" autofocus required>
                        <option value="">- SELECT -</option>
                        <?php foreach ($denom as $key): ?>
                          <option value="<?php echo $key['denom_id']; ?>"><?php echo number_format($key['denomination'],2); ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                  <!-- end form-group -->
                  <!-- begin form-group -->
                  <div class="form-group">
                    <label class="col-sm-5 control-label label-lg">Type of Adjustment: </label>
                    <div class="col-sm-7">
                      <select class="form form-control input-lg" id="_adj_type" name="adj_type" required>
                        <option value="n">Negative</option>
                        <option value="p">Positive</option>                        
                      </select>
                    </div>
                  </div>
                  <!-- end form-group -->
                  <!-- begin form-group -->
                  <div class="form-group">
                    <label class="col-sm-5 control-label label-lg">Number of GC: </label>
                    <div class="col-sm-7">
                      <input data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': ''" class="form-control input-lg" id="cbdis" name="gcno" required disabled />
                    </div>
                  </div>
                  <!-- end form-group -->
                  <!-- begin form-group -->
                  <div class="form-group">
                    <label class="col-sm-5 control-label label-lg">Remarks: </label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control input-lg" name="remarks" id="remarks" required disabled>
                    </div>
                  </div>
                  <!-- end form-group -->
                  <!-- begin form-group -->
                  <div class="form-group">
                    <label class="col-sm-5 control-label label-lg">Prepared by: </label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control input-lg" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>" readonly="readonly">
                    </div>
                  </div>
                  <!-- end form-group -->
                  <!-- begin form-group -->
                  <div class="form-group">                  
                    <div class="col-sm-offset-8 col-sm-4">
                      <button class="btn btn-block btn-primary input-md" type="submit"><span class="glyphicon glyphicon-log-in"></span> Submit</button>
                    </div>
                  </div>
                  <!-- end form-group -->
                </form>
                <div class="response">                  
                </div>
              </div>              
            </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-5 denom-details">
        </div>
      </div>
    </div>
  </div>

<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/adj.js"></script>
<?php include 'footer.php' ?>