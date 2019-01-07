<?php 
  session_start();
  include '../function.php';
  include 'header.php';
  $query_store = $link->query("SELECT `store_id`,`store_name` FROM `stores`");
  $query_gc_type = $link->query("SELECT `gc_type_id`,`gctype`,`gc_status` FROM `gc_type` WHERE `gc_status`='1'"); 
  $denom = getAllDenomination($link);
?>

<?php require '../menu.php'; ?>

    <div class="main fluid">    
      <div class="row">
        <div class="col-sm-12">
          <div class="box">
            <div class="box-header"><h4><i class="fa fa-inbox"></i> GC Allocation Adjustment</h4></div>
              <div class="box-content form-container">
                <div class="row">
                  <div class="col-sm-4">
                    <form class="form-horizontal" method="POST" action="../ajax.php?action=allocateadj" id="adjallocateForm">
                       <div class="form-group">
                          <label class="col-sm-5 control-label">Store</label>  
                          <div class="col-sm-7">
                            <select class="form-control inptxt input-sm" id="store-selected" name="storeallo" autofocus required>
                                <option value=''>--Select--</option>
                                <?php while($row = $query_store->fetch_assoc()): ?>
                                    <option value="<?php echo $row['store_id']?>"><?php echo $row['store_name'];?></option>
                                <?php endwhile; ?>
                            </select>    
                          </div>
                        </div><!-- end form group -->                      
                       <div class="form-group">
                          <label class="col-sm-5 control-label">GC Type</label>  
                          <div class="col-sm-7">
                            <select class="form-control inptxt input-sm" name="gctype" id="gctype">
                                <?php  while($row = $query_gc_type->fetch_assoc()): ?>
                                    <option value="<?php echo $row['gc_type_id']?>"><?php echo ucfirst($row['gctype']);?></option>
                                <?php endwhile; ?>
                            </select>                    
                          </div>
                        </div><!-- end form group -->
                       <div class="form-group">
                          <label class="col-sm-5 control-label">Adj Type</label>  
                          <div class="col-sm-7">
                            <select class="form form-control inptxt input-sm" name="adjtype" id="adjtype">
                              <option value="n">Negative</option>
                              <option value="p">Positive</option>
                            </select>                    
                          </div>
                        </div><!-- end form group --> 
                       <div class="form-group">
                          <label class="col-sm-5 control-label">Remarks</label>  
                          <div class="col-sm-7">
                            <textarea class="form form-control inptxt input-sm" name="remarks" required></textarea>        
                          </div>
                        </div><!-- end form group --> 
                        <div class="form-group">
                          <label class="col-sm-5 control-label">Denomination</label>
                          <label class="col-sm-4 control-label">Quantity</label>
                        </div><!-- end form group -->
                        <?php foreach ($denom as $den): ?> 
                          <div class="form-group">
                            <label class="col-sm-5 control-label">&#8369 <?php echo number_format($den->denomination,2); ?></label>  
                            <div class="col-sm-7">
                            <input data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" class="form-control inptxt input-sm num denfield" id="num<?php echo $den->denom_id; ?>" name="qty_<?php echo $den->denom_id; ?>" autocomplete="off" disabled="true" />
                            </div>
                          </div><!-- end form group --> 
                        <?php endforeach; ?>
                        <div class="form-group">
                          <div class="col-sm-offset-7 col-sm-5">
                            <button type="submit" class="btn btn-block btn-primary" id="btn"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Submit</button>
                          </div>
                        </div><!-- end form group -->
                                                                                                              
                    </form>
                  </div><!-- end col store -->
                  <div class="col-sm-4 valgc-alloc">
                    <div class="box">
                      <div class="box-header"><h4><i class="fa fa-inbox"></i> Validated GC for Allocation</h4></div>
                        <div class="box-content form-container">
                          <ul class="list-group bld">           
                            <?php foreach ($denom as $d): ?>
                              <?php $n = countCustodianValidatedGCForStoreAllocation($link,$d->denom_id); ?>
                              <input type="hidden" id="n<?php echo $d->denom_id; ?>" class="nhid<?php echo $d->denom_id; ?>"  value="<?php echo $n; ?>"/>
                              <li class="list-group-item"><span class="badge" id="n<?php echo $d->denom_id; ?>"><?php echo $n; ?></span> &#8369 <?php echo number_format($d->denomination,2); ?></li>          
                            <?php endforeach ?>
                          </ul> 
                          <button type="button" class="btn btn-info pull-right" id="view-allocated-gc" onclick="showGCforAllocation()">View GC For Allocation</button>
                        </div>
                    </div>
                  </div><!-- Validated GC -->
                  <div class="col-sm-4 storesele">
                  </div>
                </div><!-- end row -->
                <div class="response">
                </div>                   
              </div>
          </div>          
        </div>   
      </div><!-- end row -->      
    </div><!-- end fluid div -->

<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/adj.js"></script>
<?php include 'footer.php' ?>