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
                  <input name="remarks" type="text" class="form-control inptxt input-sm" required autocomplete="off">                
                  </div> 
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label"><span class="requiredf">*</span>Promo Group:</label>  
                  <div class="col-sm-4">                  
                    <select class="form form-control inptxt input-sm promog" name="group" required>
                      <option value="">-Select-</option>
                      <option value="1">Group 1</option>
                      <option value="2">Group 2</option>
                    </select>
                  </div>                  
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Denomination</label> 
                 <label class="col-sm-3 control-label"><span class="requiredf">*</span>Quantity</label>
                 <label class="col-sm-2 control-label c-label">pc(s) left</label>                 
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">&#8369 100.00</label>  
                  <div class="col-sm-3">
                    <input type="hidden" id="m1" value="100"/>
                    <input class="form form-control inptxt denfield" id="num1" value="0" name="denoms1" autocomplete="off" disabled="disabled"/>
                    </div>
                    <div class="col-sm-3"><span id="n1" class="prod-pcs-left inptxt "></span></div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">&#8369 200.00</label>  
                  <div class="col-sm-3">
                  <input type="hidden" id="m2" value="200"/>
                  <input class="form form-control inptxt denfield" id="num2" value="0" name="denoms2" autocomplete="off" disabled="disabled" />  
                  </div> 
                  <div class="col-sm-3"><span id="n2" class="prod-pcs-left inptxt"></span></div>
                </div>                
                <div class="form-group">
                  <label class="col-sm-3 control-label">&#8369 500.00</label>  
                  <div class="col-sm-3">
                  <input type="hidden" id="m3" value="500"/>
                  <input class="form form-control inptxt denfield" id="num3" value="0" name="denoms3" autocomplete="off" disabled="disabled" />
                  </div>
                  <div class="col-sm-3"><span id="n3" class="prod-pcs-left inptxt"></span></div> 
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">&#8369 1000.00</label>  
                  <div class="col-sm-3">
                  <input type="hidden" id="m4" value="1000"/>
                  <input class="form form-control inptxt denfield" id="num4" value="0" name="denoms4" autocomplete="off" disabled="disabled" />        
                  </div>
                  <div class="col-sm-3"><span id="n4" class="prod-pcs-left inptxt"></span></div>  
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">&#8369 2000.00</label>  
                  <div class="col-sm-3">
                  <input type="hidden" id="m5" value="2000"/>
                  <input class="form form-control inptxt denfield" id="num5" value="0" name="denoms5" autocomplete="off" disabled="disabled" />       
                  </div>
                  <div class="col-sm-3"><span id="n5" class="prod-pcs-left inptxt"></span></div>   
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">&#8369 5000.00</label>  
                  <div class="col-sm-3">
                  <input type="hidden" id="m6" value="5000"/>
                  <input class="form form-control inptxt denfield" id="num6" value="0" name="denoms6" autocomplete="off" disabled="disabled" />              
                  </div>
                  <div class="col-sm-3"><span id="n6" class="prod-pcs-left inptxt"></span></div>    
                </div>
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
          <div class="box bot-margin">
            <div class="box-header"><h4><i class="fa fa-inbox"></i> Current Budget(For Promo)</h4></div>
            <div class="box-content">
              <h3 class="current-budget mbot">&#8369 <?php echo number_format(currentBudgetByDept($link,2),2); ?></h3>
                <p>Group 1: <b><?php echo '&#8369 '.number_format(currentBudgetByDeptByPromoGroup($link,1),2); ?></b></p>
                <p>Group 2: <b><?php echo '&#8369 '.number_format(currentBudgetByDeptByPromoGroup($link,2),2); ?></b></p>
            </div>
          </div>
<!--           <div class="box">
            <div class="box-header"><h4><i class="fa fa-inbox"></i> Group 1</h4></div>
            <div class="box-content">
              <input type="hidden" value="<?php echo currentBudgetByDeptByPromoGroup($link,1); ?>" id="_budget"/>
              <h3 class="current-budget">&#8369 <span class="cBudget" id="n"><?php echo number_format(currentBudgetByDeptByPromoGroup($link,1),2); ?></span></h3>
            </div>
          </div>  -->
            <div class="box grouppromo">
              <div class="box-header"><h4><i class="fa fa-inbox"></i> <span class="groupname"></span></h4></div>
              <div class="box-content">
                <input type="hidden" value="0" id="_budget"/>
                <h3 class="current-budget">&#8369 <span class="cb" id="n">0</span></h3>
              </div>
            </div> 
<!--             <div class="box group2">
              <div class="box-header"><h4><i class="fa fa-inbox"></i> Group 2</h4></div>
              <div class="box-content">
                <input type="hidden" value="<?php echo currentBudgetByDeptByPromoGroup($link,2); ?>" id="g2"/>
                <h3 class="current-budget">&#8369 <span class="cg2" id="n2"><?php echo number_format(currentBudgetByDeptByPromoGroup($link,2),2); ?></span></h3>
              </div>
            </div> -->

        </div>
      </div>
        </div>
      </div>
    </div>

<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/fin.js"></script>
<?php include 'footer.php' ?>