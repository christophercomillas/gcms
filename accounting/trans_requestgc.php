<?php 
  session_start();
  include '../function.php';
  require 'header.php';
  if(isset($_SESSION['empAssign']))
  {
    unset($_SESSION['empAssign']);
  }
?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="col-md-12 pad0">
        <div class="panel with-nav-tabs panel-info">
            <div class="panel-heading">
              <ul class="nav nav-tabs">
                <li class="active" style="font-weight:bold">
                    <a href="#tab1default" data-toggle="tab">Special External GC Request</a>
                </li>
              </ul>
            </div>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="tab1default">
                      <div class="row form-container">
                        <form action="../ajax.php?action=specialExternalGCRequest" method="POST" id="specialExternalGCRequest" enctype="multipart/form-data">                  
                          <div class="col-sm-12">
                            <div class=""> 
                              <div class="col-sm-3">
                                <div class="form-group">
                                  <input type="hidden" name="reqtype" value="2">
                                  <label class="nobot">GC Request #</label>   
                                  <input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo getRequestNoByExternal($link); ?>" name="reqnum" id="reqnum">  
                                </div>

                                <div class="form-group">
                                  <label class="nobot">Date Requested</label> 
                                  <input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo _dateFormat($todays_date); ?>">                                 
                                </div>

                                <div class="form-group">
                                  <label class="nobot"><span class="requiredf">*</span>Date Needed:</label>
                                  <input type="text" class="form form-control inptxt input-sm ro bot-6" id="dp1" data-date-format="MM dd, yyyy" name="date_needed" readonly="readonly" required>
                                </div>

                                <div class="form-group">
                                  <label class="nobot">Upload Document</label> 
                                  <input id="input-file" class="file" type="file" name="docs[]" multiple>
                                </div>
                              </div>
                              <div class="col-sm-4">
                                <div class="form-group">
                                  <input type="hidden" name="companyid" id="companyid" value="">
                                  <label class="nobot"><span class="requiredf">*</span>Company Name</label>   
                                  <textarea class="form form-control input-sm inptxt" readonly="readonly" name="compname" id="compname"></textarea>
                                </div>  
                                <div class="form-group">
                                  <label class="nobot"><span class="requiredf">*</span>Account Name</label>   
                                  <input type="text" class="form form-control inptxt" readonly="readonly" name="accname" id="accname">
                                </div>       
                                <div class="form-group">
                                  <button type="button" class="btn btn-default" onclick="lookupCustomerExternal();"><i class="fa fa-search-plus" aria-hidden="true"></i>
                                    Lookup Customer</button>
                                </div>                         
                                <div class="form-group">
                                  <label class="nobot"><span class="requiredf">*</span>Payment Type</label>   
                                  <select class="form form-control input-sm inptxt" name="paymenttype" id="paymenttype" required>
                                    <option value="">- Select -</option>
                                    <option value="1">Cash</option>
                                    <option value="2">Check</option>
                                  </select>
                                </div>
                                <div class="paymenttypediv" style="display:none;">
                                  <div class="checkPayment">
                                    <div class="form-group">
                                      <label class="nobot"><span class="requiredf">*</span>Bank Name</label>
                                      <input type="text" class="form form-control inptxt input-sm bot-6" name="bankname" id="bankname">
                                    </div>
                                    <div class="form-group">
                                      <label class="nobot"><span class="requiredf">*</span>Check Number</label>
                                      <input type="text" class="form form-control inptxt input-sm bot-6" name="cnumber" id="cnumber">
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label class="nobot"><span class="requiredf">*</span><span class="cashcheck"></span> Amount</label>
                                    <input type="text" class="form form-control inptxt input-sm bot-6 amount-external" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" name="amount" id="amount" required>
                                  </div>
                                  <div class="form-group">
                                    <label class="nobot"><span class="requiredf">*</span>Amount in words</label>
                                    <textarea class="form form-control input-sm inptxt amtinwords" id="amtinwords" readonly="readonly"></textarea>
                                  </div>
                                </div>
                              </div><!-- end of col-sm-4 -->
                              <div class="col-sm-5">
                                <div class="form-group">
                                  <label class="nobot"><span class="requiredf">*</span>Remarks</label> 
                                  <input type="text" class="form-control inptxt input-sm" name="remarks" autocomplete="off" required>
                                </div>
                                <div class="form-horizontal">
                                  <div class="form-group">
                                      <label class="col-sm-6"><span class="requiredf">*</span>Denomination</label>
                                      <label class="col-sm-6"><span class="requiredf">*</span>Qty</label>
                                  </div>
                                  <div class="optionBox">
                                        <button class="btn btn-default" type="button" id="addenombut"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                                          Add Denomination</button>
                                  </div>
                                </div>
                                
                                <!-- end form horizontal -->

                                <div class="labelinternaltot">
                                  <input type="hidden" name="totolrequestinternal" id="totolrequestinternal" value="0">                        
                                  <label>Total: <span id="internaltot">0.00</span></label>
                                </div>
                                <div class="form-group">
                                  <label class="nobot">Prepared By:</label> 
                                  <input type="text" readonly="readonly" class="form-control inptxt input-sm" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>"> 
                                </div>
                                
                                <div class="response">
                                </div>

                                <div class="form-group">
                                  <div class="col-sm-offset-5 col-sm-7">
                                    <button type="submit" class="btn btn-block btn-primary" id="externalbtn"> <span class="glyphicon glyphicon-share" aria-hidden="true"></span>Submit</button>
                                  </div>
                                </div>
                              </div>
                            </div>                       
                          </div>   
                        </form>                     
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
  </div>
<div class="modal modal-static fade loadingstyle" id="processing-modal" role="dialog" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog loadingstyle">
      <div class="text-center">
          <img src="../assets/images/ring-alt.svg" class="icon" />
          <h4 class="loading">Saving Data...</h4>
      </div>
    </div>
</div>

<?php include 'jscripts.php'; ?>
<script type="text/javascript">
    $('input#input-file').fileinput({
      'allowedFileExtensions' : ['jpg','png','jpeg']
    });
</script>
<script type="text/javascript" src="../assets/js/store.js"></script>
<?php include 'footer.php' ?>