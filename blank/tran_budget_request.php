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
          <div class="box-header"><i class="fa fa-inbox"></i> Budget Entry Form</div>
            <div class="box-content">
              <form class="form-horizontal" id="budgetRequestForm" method='POST' action="../ajax.php?action=requestBudget">              
                <div class="form-group">
                  <label class="col-md-3 control-label">BR. No.</label>  
                  <div class="col-md-3">
                  <input value="<?php echo getRequestNo($link,'budget_request','br_no'); ?>" name="br_req_num" type="text" class="form-control input-md" readonly="readonly">                
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Date Requested:</label>  
                  <div class="col-sm-3">
                  <input value="<?php echo _dateFormat($todays_date); ?>" type="text" class="form-control input-md" readonly="readonly">                
                  </div>
                  <label class="col-sm-2 control-label">Date Needed:</label>  
                  <div class="col-md-3">                  
                  <input type="text" class="form form-control input-md" id="dp1" data-date-format="MM dd, yyyy" name="date_needed">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Budget:</label>  
                  <div class="col-md-5">
                  <input data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '&#8369 ', 'placeholder': '0'" class="form-control input-lg" id="amount" name="requestBudget" required />
                  </div> 
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Upload Scan Copy:</label>  
                  <div class="col-sm-4">
                  <input id="pics" type="file" name="pic[]" accept="image/*" class="form-control input-md" required />
                  </div> 
                </div> 
                <div class="form-group">
                  <label class="col-sm-3 control-label">Remarks:</label>  
                  <div class="col-sm-6">
                  <input name="textinput" type="text" class="form-control input-md">                
                  </div> 
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Prepared by:</label>  
                  <div class="col-sm-4">
                  <input type="text" readonly="readonly" class="form-control" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>">                                       
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
            <div class="box-header"><i class="fa fa-inbox"></i> Current Budget</div>
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