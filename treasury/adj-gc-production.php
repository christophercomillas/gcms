<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  $denom = getResults($link,'denomination','denom_id');

?>

<?php require '../menu.php'; ?>
  <div class="main fluid">
    <div class="row">
      <div class="col-sm-8">
        <div class="box box-bot">
          <div class="box-header"><h4><i class="fa fa-inbox"></i> GC Production Adjustment</h4></div>
          <div class="box-content">
            <div class="col-sm-12 form-container">
              <form class="form-horizontal" action="../ajax.php?action=adjustGCEntry" id="gcAdjustEntry">
                <!-- begin form-group -->
                <div class="form-group">
                  <label class="col-sm-4 control-label control-label">Type of Adjustment: </label>
                  <div class="col-sm-4">
                    <select class="form form-control input-sm" id="proadj" name="adj_type" autofocus required>
                      <option value="">- Select -</option>
                      <option value="n">Negative</option>
                      <option value="p">Positive</option>                        
                    </select>
                  </div>
                </div>
                <!-- end form-group -->
                <!-- begin form-group -->
                <div class="form-group">
                  <label class="col-sm-3 control-label control-label">Denomination </label>
                  <label class="col-sm-2 control-label control-label">Quantity </label>
                </div>
              <!-- end form-group -->
              <?php foreach ($denom as $key): ?>
                <!-- begin form-group -->
                <div class="form-group">
                  <label class="col-sm-3 control-label control-label">&#8369 <?php echo number_format($key['denomination'],2); ?></label>
                  <div class="col-sm-3">
                    <input data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0'" class="form-control input-sm num" name="qty<?php echo $key['denom_id']?>" id="num<?php echo $key['denom_id']?>" disabled />                  
                  </div>
                  <div class="col-sm-3">
                    <input type="hidden" id="x<?php echo $key['denom_id'];?>" value="<?php echo getProductionByDenom($link,$key['denom_id']); ?>">
                    <input type="text" class="form form-control input-sm" id="n<?php echo $key['denom_id'];?>" readonly="readonly" value="<?php echo getProductionByDenom($link,$key['denom_id']); ?>">
                  </div>
                  <div class="col-sm-2">
                    <input type="hidden" id="denom<?php echo $key['denom_id'];?>" value="<?php echo $key['denomination'];?>">
                    <span></span><span id="p<?php echo $key['denom_id'];?>"></span>
                  </div>
                </div>
                <!-- end form-group -->                  
              <?php endforeach ?>
                <!-- begin form-group -->
                <div class="form-group">
                  <label class="col-sm-3 control-label control-label">Remarks: </label>
                  <div class="col-sm-6">
                     <input type="text" class="form form-control input-sm" id="_remarks" name="remarks" required disabled >
                  </div>
                </div>
                <!-- end form-group -->
                <!-- begin form-group -->
                <div class="form-group">
                  <label class="col-sm-3 control-label control-label">Prepared by: </label>
                  <div class="col-sm-4">
                     <input type="text" class="form form-control input-sm" readonly="readonly" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>">
                  </div>
                </div>
                <!-- end form-group -->
                <!-- begin form-group -->
                <div class="form-group">
                  <div class="col-sm-9 response">                      
                  </div>
                  <div class="col-sm-3">
                    <button class="btn btn-block btn-primary" id="btn" type="submit">Submit</button>
                  </div>
                </div>
                <!-- end form-group -->
            </form>
            </div>              
          </div>
      </div>
      </div>
      <div class="col-sm-4">
        <div class="box box-bot">
          <div class="box-header"><h4><i class="fa fa-inbox"></i> Current Budget</h4></div>
          <div class="box-content">
            <input type="hidden" id="current-budget" value="<?php echo currentBudget($link); ?>">
            <h3 class="current-budget">&#8369 <?php echo number_format(currentBudget($link),2); ?></h3>
          </div>
        </div>
        <div class="box">
          <div class="box-header"><h4><i class="fa fa-inbox"></i> Budget Adjustment</h4></div>
          <div class="box-content">
            <h3 class="current-adjustment"><span id="adj-sign"> </span><span id="adj-peso">&#8369 </span><span id="adj-budget">0.00</span></h3>
          </div>
        </div>
      </div>
    </div>
  </div>


<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/adj-production.js"></script>
<?php include 'footer.php' ?>