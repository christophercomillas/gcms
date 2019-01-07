<?php 
  session_start();
  include '../function.php';
  include 'header.php';

   $dept = getField($link,'usertype','users','user_id',$_SESSION['gc_id']);
  // $request = getBudgetRequestForUpdate($link,$dept);
  $request = getBudgetRequestForUpdateByDept($link,$dept);

?>

<?php require '../menu.php'; ?>

    <div class="main fluid">
     <div class="row">
        <?php if(!is_null($request)): ?>
        <div class="col-xs-8">          
          <div class="box">
          <div class="box-header"><h4><i class="fa fa-inbox"></i> Update Budget Entry Form</h4></div>
            <div class="box-content form-container">
              <form id="updateRequestForm" method='POST' action="../ajax.php?action=updateRequestBudget" class="form-horizontal">
                <input type="hidden" name="reqid" value="<?php echo $request->br_id; ?>">
                <input type="hidden" name="imgname" value="<?php echo $request->br_file_docno; ?>">
                <div class="form-group">
                  <label class="col-xs-3 control-label">BR. No.</label>  
                  <div class="col-xs-3">
                  <input name="br_req_num" class="form form-control inptxt input-sm" readonly="readonly" value="<?php echo $request->br_no; ?>">         
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-xs-3 control-label">Date Requested:</label>  
                  <div class="col-xs-4">
                  <input class="form form-control inptxt input-sm" readonly="readonly" value="<?php echo _dateFormat($request->br_requested_at); ?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-xs-3 control-label">Date Needed:</label>  
                  <div class="col-xs-4">                  
                  <input type="text" class="form form-control inptxt input-sm" id="dp1" data-date-format="MM dd, yyyy" name="date_needed" value="<?php echo _dateFormat($request->br_requested_needed); ?>" required readonly="readonly">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-xs-3 control-label form-budget">Budget:</label>  
                  <div class="col-xs-5">
                  <input type="text" id="amount" name="requestBudget" class="form form-control input-lg input-budget"  data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" required autocomplete="off" value="<?php echo substr($request->br_request, 0, -3); ?>">
                  <!-- <input data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '&#8369 ', 'placeholder': '0'" class="form-control input-lg" id="amount" name="requestBudget" value="<?php echo substr($request->br_request, 0, -3); ?>" required /> -->
                  </div> 
                </div>                
                <div class="form-group">
                  <label class="col-xs-3 control-label">Uploaded Document:</label>  
                  <div class="col-xs-3">
                    <?php if(trim($request->br_file_docno)!=''): ?>
                      <a class="btn btn-block btn-default" href='../assets/images/budgetRequestScanCopy/download.php?file=<?php echo $request->br_file_docno; ?>'>Download</a>
                    <?php else: ?>
                      None
                    <?php endif; ?>
                  </div> 
                </div> 
                <div class="form-group">
                  <label class="col-xs-3 control-label">Upload Scan Copy:</label>  
                  <div class="col-xs-4">
                  <input id="pics" type="file" name="pic[]" accept="image/*" class="form-control input-sm" />
                  </div> 
                </div> 
                <div class="form-group">
                  <label class="col-xs-3 control-label">Remarks:</label>  
                  <div class="col-xs-6">
                  <input name="remarks" type="text" class="form-control inptxt input-sm" value="<?php echo $request->br_remarks; ?>">                
                  </div> 
                </div>
                <?php if($request->br_requested_by!=$_SESSION['gc_id']): ?>
                <div class="form-group">
                  <label class="col-xs-3 control-label">Created By:</label>  
                  <div class="col-xs-4">
                  <input type="text" readonly="readonly" class="form-control inptxt" value="<?php echo ucwords($request->firstname.' '.$request->lastname); ?>">                                       
                  </div> 
                </div>                 
                <?php endif; ?>
                <div class="form-group">
                  <label class="col-xs-3 control-label">Updated By:</label>  
                  <div class="col-xs-4">
                  <input type="text" readonly="readonly" class="form-control inptxt" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>">                                       
                  </div> 
                </div>
                <div class="form-group">                  
                  <div class="col-xs-offset-8 col-xs-4">
                    <button type="submit" class="btn btn-block btn-primary"><span class="glyphicon glyphicon-log-in"></span> &nbsp;Save </button>                    
                  </div> 
                </div>
              </form>
              <div class="response">
              </div>
            </div>
          </div>
        </div>
        <div class="col-xs-4">
          <div class="box">
                  <div class="box-header"><h4><i class="fa fa-inbox"></i> Current Budget</h4></div>
                  <div class="box-content">
                    <h3 class="current-budget">&#8369 <?php echo number_format(currentBudget($link),2); ?></h3>
            </div>
          </div>
        </div>
        <?php endif; ?>
     </div>
    </div>

<?php include 'jscripts.php'; ?>
<script src="../assets/js/main.js"></script>
<?php include 'footer.php' ?>