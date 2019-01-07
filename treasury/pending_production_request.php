<?php 
  session_start();
  include '../function.php';
  include 'header.php';

  $dept = getField($link,'usertype','users','user_id',$_SESSION['gc_id']);

  $pr = getPendingProductionRequestByDept($link,$dept);

  $denoms = getAllDenomination($link);

?>
<?php require '../menu.php'; ?>

    <div class="main fluid">
    
     <div class="row">
      <?php if(!is_null($pr)): ?>
        <div class="col-sm-8">
          <div class="box">
          <div class="box-header"><h4><i class="fa fa-inbox"></i> Update Pending Production Entry Form</h4></div>
            <div class="box-content form-container">
              <form action="../ajax.php?action=updateProductionRequest" method="POST" id="updateProdEntryForm" class="form-horizontal">
                <input type="hidden" name="reqid" value="<?php echo $pr->pe_id; ?>">
                <input type="hidden" name="imgname" value="<?php echo $pr->pe_file_docno; ?>">
               <div class="form-group">
                  <label class="col-sm-3 control-label">PE. No.</label>  
                  <div class="col-sm-3">
                  <input class="form form-control inptxt input-sm" readonly="readonly" name="penum" value="<?php echo $pr->pe_num; ?>">        
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
                    <input type="text" class="form form-control inptxt input-sm" id="dp1" data-date-format="MM dd, yyyy" name="date_needed" value="<?php echo _dateFormat($pr->pe_date_needed); ?>">
                  </div>
                </div>                
                <div class="form-group">
                  <label class="col-sm-3 control-label">Upload Scan Copy:</label>  
                  <div class="col-sm-4">
                    <input id="pics" type="file" name="pic[]" accept="image/*" class="form form-control input-sm" />
                  </div>
                  <?php if($pr->pe_file_docno!=''):?>
                  <div class="col-sm-4">
                    <a class="btn btn-default" href='../assets/images/productionRequestFile/download.php?file=<?php echo $pr->pe_file_docno; ?>'><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download</a>
                  </div> 
                  <?php endif; ?>
                </div> 
                <div class="form-group">
                  <label class="col-sm-3 control-label"><span class="requiredf">*</span>Remarks:</label>  
                  <div class="col-sm-6">
                    <input class="form form-control inptxt input-sm" name="remarks" value="<?php echo $pr->pe_remarks; ?>" required />       
                  </div> 
                </div>
               <div class="form-group">
                  <label class="col-sm-3 control-label">Denomination</label> 
                 <label class="col-sm-3 control-label"><span class="requiredf">*</span>Quantity</label>
                 <label class="col-sm-2 control-label c-label">pc(s) left</label>                 
                </div>
                
                <!-- denomination -->
                <?php foreach ($denoms as $key): ?>
                 <div class="form-group">
                    <label class="col-sm-3 control-label">&#8369 <?php echo number_format($key->denomination,2); ?></label>  
                    <div class="col-sm-3">
                      <input type="hidden" class="denval" id="m<?php echo $key->denom_id; ?>" value="<?php echo $key->denomination; ?>"/>
                      <input class="form form-control inptxt qty denfield" id="num<?php echo $key->denom_id; ?>" value="<?php echo getGCrequestItems($link,'pe_items_quantity','production_request_items','pe_items_denomination',$key->denom_id,'pe_items_request_id',$pr->pe_id); ?>" name="denoms<?php echo $key->denom_id; ?>" autocomplete="off" />       
                      </div>
                      <div class="col-sm-3"><span id="n<?php echo $key->denom_id; ?>" class="prod-pcs-left inptxt "></span></div>
                  </div>
                <?php endforeach ?>
                  
                <!-- end denomination -->

                <div class="form-group">
                  <label class="col-sm-3 control-label">Updated by:</label>  
                  <div class="col-sm-4">
                      <input type="text" class="form-control input-sm" readonly value="<?php echo ucwords($_SESSION['gc_fullname']); ?>" />
                  </div>
                  <div class="col-sm-4"> 
                      <button id="btn" type="submit" class="btn btn-block btn-primary"><span class="glyphicon glyphicon-log-in"></span> &nbsp;Submit </button>
                  </div>
                </div>
                <div class="response">
                </div>
              </form>
            </div>            
 
          </div>
        </div>
        <div class="col-sm-4">
          <div class="box">
            <div class="box-header"><h4><i class="fa fa-inbox"></i> Current Budget</h4></div>
            <div class="box-content">
              <input type="hidden" value="<?php echo currentBudget($link); ?>" id="_budget"/>
              <h3 class="current-budget">&#8369 <span class="cBudget" id="n"><?php echo number_format(currentBudget($link),2); ?>></span></h3>
            </div>
          </div>
        </div>
      <?php endif; ?>
      </div>

        </div>
      </div>

    </div>

<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/main.js"></script>
<?php include 'footer.php' ?>