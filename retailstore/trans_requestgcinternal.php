<?php 
  session_start();
  include '../function.php';
  require 'header.php';
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
                    <a href="#tab1default" data-toggle="tab">Store GC Request Form (Special Internal GC)</a>
                </li>
              </ul>
            </div>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="tab1default">
                      <div class="row form-container">
                        <form action="../ajax.php?action=gcrequstinternal" method="POST" id="storeRequestInternal">                  
                          <div class="col-sm-12">
                            <div class="form-horizontal"> 
                              <div class="col-sm-4">
                                <div class="form-group">
                                  <label class="nobot">GC Request #</label>   
                                  <input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo getRequestNoByStore($link,'store_gcrequest','sgc_num',$storeid); ?>" name="penum">  
                                </div>

                                <div class="form-group">
                                  <label class="nobot">Retail Store</label>
                                  <input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo getField($link,'store_name','stores','store_id',$storeid); ?>">               
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
                                  <label class="nobot"><span class="requiredf">*</span>Company / Person Requested</label> 
                                  <textarea class="form form-control inptxt input-sm bot-6" name="requestedby" required></textarea>                              
                                </div>

                                <div class="form-group">
                                  <label class="nobot">Upload Document</label> 
                                  <input id="pics" type="file" name="pic[]" accept="image/*" class="form form-control inptxt bot-6" />
                                </div>

                                <div class="form-group">
                                  <label class="nobot"><span class="requiredf">*</span>Remarks</label> 
                                  <input type="text" class="form-control inptxt input-sm" name="remarks" autocomplete="off" required>
                                </div>
                              </div>
                              <div class="col-sm-4">
                                  <div class="form-group">
                                      <label class="col-sm-6"><span class="requiredf">*</span>Denomination</label>
                                      <label class="col-sm-6"><span class="requiredf">*</span>Qty</label>
                                  </div>

                                  <div class="optionBox">
                                    <div class="form-group">
                                      <div class="col-sm-5">
                                        <input class="form form-control inptxt input-sm reqfield ninternalcusd1" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0'" id="ninternalcusd" autocomplete="off" placeholder="0" name="ninternalcusd[]" />
                                      </div>
                                      <div class="col-sm-5">
                                        <input class="form form-control inptxt input-sm reqfield ninternalcusq1" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0'" id="ninternalcusq" autocomplete="off" placeholder="0" name="ninternalcusq[]" />
                                      </div>
                                    </div>
                                        <button class="btn btn-default" type="button" id="addenombut">Add Denomination</button>
                                  </div>
                              </div>
                              <div class="col-sm-4">
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
                                    <button type="submit" class="btn btn-block btn-primary" id="internalbtn"> <span class="glyphicon glyphicon-share" aria-hidden="true"></span>Submit</button>
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
<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/store.js"></script>
<?php include 'footer.php' ?>