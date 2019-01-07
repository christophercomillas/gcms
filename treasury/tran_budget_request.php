<?php 
  session_start();
  include '../function.php';
  include 'header.php';
?>

<?php require '../menu.php'; ?>

    <div class="main fluid">    
     <div class="row">
        <div class="col-sm-8">
          <div class="box">
          <div class="box-header"><h4><i class="fa fa-inbox"></i> Revolving Budget Entry Form</h4></div>
            <div class="box-content form-container">
              <form class="form-horizontal" id="budgetRequestForm" method='POST' action="../ajax.php?action=requestBudget">              
                <div class="form-group">
                  <label class="col-sm-3 control-label">BR. No.</label>  
                  <div class="col-sm-3">
                  <input value="<?php echo getRequestNo($link,'budget_request','br_no'); ?>" name="br_req_num" type="text" class="form-control inptxt input-sm" readonly="readonly">                
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Date Requested:</label>  
                  <div class="col-sm-4">
                  <input value="<?php echo _dateFormat($todays_date); ?>" type="text" class="form-control inptxt input-sm" readonly="readonly">                
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label"><span class="requiredf">*</span>Date Needed:</label>  
                  <div class="col-sm-4">                  
                  <input type="text" class="form form-control inptxt input-sm ro" id="dp1" data-date-format="MM dd, yyyy" name="date_needed" readonly="readonly">
                  </div>                  
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label form-budget"><span class="requiredf">*</span>Budget:</label>  
                  <div class="col-sm-5">
                    <input type="text" id="amount" name="requestBudget" class="form form-control input-lg input-budget"  data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" required autocomplete="off">
                  <!-- <input data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '&#8369 ', 'placeholder': '0'" class="form-control input-lg" id="amount" name="requestBudget" required /> -->
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
                  <label class="col-sm-3 control-label">Prepared by:</label>  
                  <div class="col-sm-4">
                  <input type="text" readonly="readonly" class="form-control inptxt" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>">                                       
                  </div> 
                </div>
                <div class="form-group">                  
                  <div class="col-sm-offset-8 col-sm-4">
                    <button type="submit" id="btn" class="btn btn-block btn-primary"><span class="glyphicon glyphicon-log-in"></span> &nbsp;Submit </button>                    
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
              <h3 class="current-budget">&#8369 <?php echo number_format(currentBudget($link),2); ?></h3>
            </div>
          </div>
        </div>
      </div>
        </div>
      </div>
    </div>

<?php include 'jscripts.php'; ?>
<script src="../assets/js/main.js"></script>
<?php include 'footer.php' ?>