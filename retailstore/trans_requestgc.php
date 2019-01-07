  <?php 
  session_start();
  include '../function.php';
  require 'header.php';

  $denoms = getAllDenomination($link);
?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
    <div class="row">
      <div class="col-sm-12">
        <div class="box box-bot">
          <div class="box-header"><h4><i class="fa fa-inbox"></i> Store GC Request Form</h4></div>
          <div class="box-content">
            <div class="row form-container">
              <form class="form-horizontal" action="../ajax.php?action=gcrequest" method="POST" id="storeRequest">
              <div class="col-sm-3">                
                <div class="form-group">
                  <label class="col-sm-6 control-label">Denomination</label>
                  <label class="col-sm-4 control-label"><span class="requiredf">*</span>Quantity</label>
                </div><!-- end of form-group -->
                  <?php foreach ($denoms as $d): ?>
                    <div class="form-group">
                      <label class="col-sm-6 control-label">&#8369 <?php echo number_format($d->denomination,2); ?></label>
                      <div class="col-sm-5">
                        <input type="hidden" id="m<?php echo $d->denom_id; ?>" value="<?php echo $d->denomination; ?>"/>
                        <input class="form form-control inptxt input-sm reqfield" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" id="num<?php echo $d->denom_id; ?>" name="denoms<?php echo $d->denom_id; ?>" autocomplete="off" autofocus />
                      </div>
                    </div><!-- end of form-group -->
                  <?php endforeach; ?>
                <div class="form-group">
                  <label class="col-sm-6 control-label">Total</label>
                  <div class="col-sm-6">
                    <input type="hidden" value="0" id="_totalReq"/>
                    <input type="text" class="form form-control inptxt" id="totalReq" readonly="readonly" value="0.00">
                  </div>
                </div><!-- end of form-group -->

              </div>              
              <div class="col-sm-5">
                <div class="form-group">
                  <label class="col-sm-4 control-label">GC Request No.:</label>
                  <div class="col-sm-4">
                    <input type="text" class="form form-control inptxt input-sm" readonly="readonly" value="<?php echo getRequestNoByStore($link,'store_gcrequest','sgc_num',$storeid); ?>" name="penum">
                  </div>
                </div><!-- end of form-group -->
                <div class="form-group">
                  <label class="col-sm-4 control-label">Retail Store:</label>
                  <div class="col-sm-6">
                      <input type="text" class="form form-control inptxt input-sm" name="storename" readonly="readonly" value="<?php echo getField($link,'store_name','stores','store_id',$storeid); ?>">
                      <input type="hidden" name="storeid" value="<?php echo $storeid; ?>">
                  </div>
                </div><!-- end of form-group -->
                <div class="form-group">
                  <label class="col-sm-4 control-label">Date Requested:</label>
                  <div class="col-sm-6">
                      <input type="text" class="form form-control inptxt input-sm" readonly="readonly" value="<?php echo _dateFormat($todays_date); ?>">
                  </div>
                </div><!-- end of form-group -->
                <div class="form-group">
                  <label class="col-sm-4 control-label"><span class="requiredf">*</span>Date Needed:</label>
                  <div class="col-sm-6">
                      <input type="text" class="form form-control inptxt input-sm ro" id="dp1" data-date-format="MM dd, yyyy" name="date_needed" readonly="readonly" required>
                  </div>
                </div><!-- end of form-group -->
                <div class="form-group">
                  <label class="col-sm-4 control-label">Upload Doc:</label>
                  <div class="col-sm-6">
                      <input id="pics" type="file" name="pic[]" accept="image/*" class="form form-control inptxt" />
                  </div>
                </div><!-- end of form-group -->
                <div class="form-group">
                  <label class="col-sm-4 control-label"><span class="requiredf">*</span>Remarks:</label>
                  <div class="col-sm-6">
                      <input type="text" class="form-control inptxt input-sm" name="remarks" autocomplete="off" required>
                  </div>
                </div><!-- end of form-group -->
                <div class="form-group">
                  <label class="col-sm-4 control-label">Prepared by:</label>
                  <div class="col-sm-6">
                    <input type="text" readonly="readonly" class="form-control inptxt input-sm" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>">                     
                  </div>
                </div><!-- end of form-group -->
                <div class="form-group">
                  <div class="col-sm-offset-5 col-sm-5">
                    <button class="btn btn-block btn-primary" id="btnSubmit"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Submit</button>                    
                  </div>
                </div><!-- end of form-group -->
                <div class="response">
                </div>
              </div><!-- end of col-7 -->
              </form>
              <div class="col-sm-4">
                <div class="box">
                  <div class="box-header"><h4><i class="fa fa-inbox"></i> Allocated GC</h4></div>
                    <div class="box-content form-container">
                      <ul class="list-group">   
                        <?php foreach ($denoms as $key): ?>
                          <li class="list-group-item"><span class="badge" id="x<?php echo $key->denom_id; ?>"><?php echo getValidationNumRowsByStore($link,$storeid,$key->denom_id); ?></span> &#8369 <?php echo number_format($key->denomination,2); ?></li>          
                        <?php endforeach; ?>                                 
                      </ul>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/store.js"></script>
<?php include 'footer.php' ?>