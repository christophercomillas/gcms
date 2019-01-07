<?php 
  session_start();
  include '../function.php';
  include 'header.php';

  $denoms = getAllDenomination($link);
?>

<?php require '../menu.php'; ?>

    <div class="main fluid">
    
     <div class="row">
        <div class="col-sm-8">
          <div class="box">
          <div class="box-header"><h4><i class="fa fa-inbox"></i> Production Request Form</h4></div>
            <div class="box-content form-container">
              <form class="form-horizontal" id="prodEntryForm" method='POST' action="../ajax.php?action=productionRequest">
               <div class="form-group">
                  <label class="col-sm-3 control-label">P.R. No.</label>  
                  <div class="col-sm-3">
                  <input value="<?php echo getRequestNo($link,'production_request','pe_num'); ?>" name="penum" type="text" class="form-control inptxt input-sm" readonly="readonly">                
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Date Requested:</label>  
                  <div class="col-sm-4">
                  <input value="<?php echo _dateformat($todays_date); ?>" type="text" class="form-control inptxt input-sm" readonly="readonly">         
                  </div>
                </div>          
                <div class="form-group">
                  <label class="col-sm-3 control-label"><span class="requiredf">*</span>Date Needed:</label>  
                  <div class="col-sm-4">                  
                  <input type="text" class="form form-control inptxt input-sm ro" id="dp1" data-date-format="MM dd, yyyy" name="date_needed" readonly="readonly" required>
                  </div>
                </div>      
                <div class="form-group">
                  <label class="col-sm-3 control-label">Upload Scan Copy:</label>  
                  <div class="col-sm-4">
                  <input id="pics" type="file" name="pic[]" accept="image/*" class="form-control inptxt input-sm" />
                  </div> 
                </div> 
                <div class="form-group">
                  <label class="col-sm-3 control-label"><span class="requiredf">*</span>Remarks:</label>  
                  <div class="col-sm-6">
                  <input name="remarks" type="text" class="form-control inptxt input-sm" autocomplete="off" required>                
                  </div> 
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Denomination</label> 
                  <label class="col-sm-3 control-label"><span class="requiredf">*</span>Quantity</label>
                  <label class="col-sm-2 control-label c-label">pc(s) left</label>                 
                </div>
                
                <!-- denomination loop -->
                
                <?php foreach ($denoms as $key): ?>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">&#8369 <?php echo number_format($key->denomination,2); ?></label>  
                    <div class="col-sm-3">
                      <input type="hidden" class="denval" id="m<?php echo $key->denom_id; ?>" value="<?php echo $key->denomination; ?>"/>
                      <input class="form form-control inptxt qty denfield" id="num<?php echo $key->denom_id; ?>" value="0" name="denoms<?php echo $key->denom_id; ?>" autocomplete="off" />
                    </div>
                    <div class="col-sm-3"><span id="n<?php echo $key->denom_id; ?>" class="prod-pcs-left inptxt "></span></div>
                  </div>
                <?php endforeach ?>              

                <!-- end denomination loop -->

                <div class="form-group">
                  <label class="col-sm-3 control-label">Prepared by:</label>  
                  <div class="col-sm-4">
                  <input name="textinput" type="text" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>" class="form-control input-sm inptxt" readonly="readonly">                
                  </div>                    
                  <div class="col-sm-4">
                    <button id="btn" type="submit" class="btn btn-block btn-primary"><span class="glyphicon glyphicon-log-in"></span> &nbsp;Submit </button>
                  </div> 
                </div>
              </form>
              <div class="response">
              </div>
            </div>
          </div>

        </div>
        <div class="col-sm-4">
          <div class="box">
            <div class="box-header"><h4><i class="fa fa-inbox"></i> Current Budget</h4></div>
            <div class="box-content">
              <input type="hidden" value="<?php echo currentBudget($link); ?>" id="_budget"/>
              <h3 class="current-budget">&#8369 <span class="cBudget" id="n"><?php echo number_format(currentBudget($link),2); ?></span></h3>
            </div>
          </div>
        </div>
      </div>
        </div>
      </div>
    </div>

<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/main.js"></script>
<?php include 'footer.php' ?>