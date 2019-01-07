<?php 
  session_start();
  include '../function.php';
  include 'header.php';
  $query_store = $link->query("SELECT store_id,store_name FROM stores WHERE store_status='active'");
  $query_gc_type = $link->query(
    "SELECT 
      gc_type_id,
      gctype,
      gc_status 
    FROM 
      gc_type 
    WHERE 
      gc_status='1'
    AND
      gc_forallocation='1'
  "); 
  $denoms = getAllDenomination($link);
?>

<?php require '../menu.php'; ?>

    <div class="main fluid">    
      <div class="row">
        <div class="col-sm-12">
          <div class="box">
            <div class="box-header"><h4><i class="fa fa-inbox"></i> GC Allocation</h4></div>
              <div class="box-content form-container">
                <div class="row">
                  <div class="col-sm-4">
                    <form class="form-horizontal" method="POST" action="../ajax.php?action=allocate" id="allocateForm">
                      <div class="form-group">
                        <label class="col-sm-5 control-label">Date Allocated:</label>  
                        <div class="col-sm-7">
                          <input class="form-control input-sm inptxt" readonly="readonly" value="<?php echo _dateFormat($todays_date); ?>" />
                        </div>
                      </div><!-- end form group -->
                       <div class="form-group">
                          <label class="col-sm-5 control-label">Store</label>  
                          <div class="col-sm-7">
                            <select class="form-control input-sm inptxt" id="store-selected" name="storeallo" autofocus required>
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
                            <select class="form form-control input-sm inptxt" name="gctype" id="gctype" onchange="changeGCType(this.value)" required>
                                <?php  while($row = $query_gc_type->fetch_assoc()): ?>
                                    <option value="<?php echo $row['gc_type_id']?>"><?php echo ucfirst($row['gctype']);?></option>
                                <?php endwhile; ?>
                            </select>                    
                          </div>
                        </div><!-- end form group --> 
                      <div class="form-group">
                        <label class="col-sm-5 control-label">Denomination</label>
                        <label class="col-sm-4 control-label">Quantity</label>
                      </div><!-- end form group -->
                      
                      <!-- denoms input display --> 
                      <?php foreach ($denoms as $denom): ?>                     
                        <div class="form-group">
                          <label class="col-sm-5 control-label">&#8369 <?php echo number_format($denom->denomination,2); ?></label>  
                          <div class="col-sm-7">
                            <input data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" class="form-control input-sm inptxt qty_a denfield" id="num<?php echo $denom->denom_id; ?>" name="qty_<?php echo $denom->denom_id; ?>" autocomplete="off" />
                          </div>
                        </div><!-- end form group --> 
                       <?php endforeach ?>                     
                       <div class="form-group">
                          <div class="col-sm-offset-7 col-sm-5">
                            <button type="submit" class="btn btn-block btn-primary" id="btn"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Submit</button>
                          </div>
                        </div><!-- end form group -->
                      <!-- end denoms input display -->                                                                                                              
                  </form>
                  </div><!-- end col store -->
                  <div class="col-sm-4 validated-for-alloc">
                    <div class="box">
                      <div class="box-header"><h4><i class="fa fa-inbox"></i> Validated GC for Allocation</h4></div>
                        <div class="box-content form-container">
                          <ul class="list-group bld">
                              <?php foreach ($denoms as $denom): ?>
                                <input type="hidden" id="nx<?php echo $denom->denom_id; ?>"  value="<?php echo countGCNotYetAllocatedandNotPromo($link,$denom->denom_id); ?>"/>                         
                                <li class="list-group-item"><span class="badge" id="n<?php echo $denom->denom_id; ?>"><?php echo countGCNotYetAllocatedandNotPromo($link,$denom->denom_id); ?></span> &#8369 <?php echo number_format($denom->denomination,2); ?></li>          
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
<script type="text/javascript" src="../assets/js/allocate.js"></script>
<?php include 'footer.php' ?>