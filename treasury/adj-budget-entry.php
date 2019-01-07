<?php 
  session_start();
  include '../function.php';
  require 'header.php';
?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
  	<div class="row">
      <div class="col-sm-6">
        <div class="box box-bot">
          <div class="box-header"><h4><i class="fa fa-inbox"></i> Budget Entry Adjustment</h4></div>
          <div class="box-content">
            <div class="col-sm-12 form-container">
              <form class="form-horizontal" id="_budgetEntryAdj" type="post" action="../ajax.php?action=budget_adjustment">
                <input type="hidden" name="typeid" value="1">   
                <div class="form-group">
                  <label class="col-sm-5 control-label label-lg">Current Budget: </label>
                  <div class="col-sm-7">
                    <input type="hidden" id="_cb" value="<?php echo number_format(currentBudget($link),2); ?>">     
                    <input type="text" data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true, 'placeholder': '0.00','allowMinus':false" class="form-control input-lg" id="cbdis" value="<?php echo number_format(currentBudget($link),2); ?>" readonly="readonly"/>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-5 control-label label-lg">Adjustment Type: </label>
                  <div class="col-sm-7">
                    <select class="form form-control input-lg" id="_adj_type" name="adj_type">
                      <option value="neg">Negative Entry</option>
                      <option value="pos">Positive Entry</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-5 control-label label-lg">Budget Adjustment: </label>
                  <div class="col-sm-7">
                    <input data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true, 'placeholder': '0.00','allowMinus':false" class="form-control input-lg" id="amount" name="adj" required autocomplete="off" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-5 control-label">Remarks:</label>
                  <div class="col-sm-7">
                    <textarea class="form-control input-md" name="remarks" required></textarea>                    
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-5 control-label">Prepared by:</label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control input-md" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>" readonly="readonly">
                  </div>
                </div>
                <div class="form-group">                  
                  <div class="col-sm-offset-8 col-sm-4">
                    <button class="btn btn-block btn-primary input-md" type="submit" id="btn"><span class="glyphicon glyphicon-log-in"></span> Submit</button>
                  </div>
                </div>
                <div class="response">
                </div>
              </form>
            </div>              
          </div>
      </div>
    </div>
  </div>

<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/adj.js"></script>
<?php include 'footer.php' ?>