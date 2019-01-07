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
          <div class="box-header"><h4><i class="fa fa-inbox"></i> Promo GC Request Form</h4></div>
            <div class="box-content form-container">
              <form class="form-horizontal" id="promoreqForm" method='POST' action="../ajax.php?action=promoRequest">
                <input type="hidden" name="totpromoreq" id="totpromoreq" value="0"> 
                <div class="form-group">
                  <label class="col-sm-3 control-label">RFPROM No.</label>  
                  <div class="col-sm-3">
                  <input value="<?php echo getPromoGCRequestNo($link); ?>" name="preqnum" type="text" class="form-control inptxt input-sm" readonly="readonly">                
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
                  <label class="col-sm-3 control-label">PWP/ Approved Budget Doc:</label>  
                  <div class="col-sm-4">
                  <input id="pics" type="file" name="docs[]" accept="image/*" class="form-control inptxt input-sm" />
                  </div> 
                </div> 
                <div class="form-group">
                  <label class="col-sm-3 control-label"><span class="requiredf">*</span>Remarks:</label>  
                  <div class="col-sm-6">
                  <input name="remarks" type="text" class="form-control inptxt input-sm" required autocomplete="off" autofocus>                
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
                </div>
                <?php foreach ($denoms as $d): ?>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">&#8369 <?php echo number_format($d->denomination,2); ?></label>  
                    <div class="col-sm-3">
                      <input type="hidden" id="m<?php echo $d->denom_id; ?>" value="<?php echo $d->denomination; ?>"/>
                      <input class="form form-control inptxt denfield" id="num<?php echo $d->denom_id; ?>" value="0" name="denoms<?php echo $d->denom_id; ?>" autocomplete="off" />
                      </div>                   
                  </div>                  
                <?php endforeach ?>
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
            <div class="box-header"><h4><i class="fa fa-inbox"></i> Total Promo GC Request</h4></div>
            <div class="box-content">
              <h3 class="current-budget mbot">&#8369 <span id="totpromo">0.00</span></h3>              
            </div>
          </div>
        </div>
      </div>
        </div>
      </div>
    </div>

<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/marketing.js"></script>
<?php include 'footer.php' ?>