<?php 
  session_start();
  include '../function.php';
  require 'header.php';
?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
    <div class="row">
      <div class="col-sm-12">
        <?php if(checkIfHasRows($link,'*','store_gcrequest','sgc_store',$store_id,'sgc_status',0)): ?>
          <?php $pendreqID =  getFieldGCItems($link,'sgc_id','store_gcrequest','sgc_store',$store_id,'sgc_status','0'); ?>
        <div class="box box-bot">
          <div class="box-header"><h4><i class="fa fa-inbox"></i> Update GC Request</h4></div>
          <div class="box-content">
            <div class="row form-container">
              <form class="form-horizontal" action="../ajax.php?action=updategcrequest" method="POST" id="storeRequestUpdate">
              <input type="hidden" name="reqID" value="<?php echo $pendreqID; ?>">
              <div class="col-sm-3">                
                <div class="form-group">
                  <label class="col-sm-6 control-label">Denomination</label>
                  <label class="col-sm-4 control-label">Quantity</label>
                </div><!-- end of form-group -->
                <div class="form-group">
                  <label class="col-sm-6 control-label">&#8369 100.00</label>
                  <div class="col-sm-5">
                    <?php 
                            $d1 = getGCrequestItems(
                            $link,
                            'sri_items_quantity',
                            'store_request_items',                                            
                            'sri_items_denomination',
                            '1',
                            'sri_items_requestid',
                            $pendreqID
                            );

                            $sub1 = $d1 * 100;
                    ?>
                    <input type="hidden" id="m1" value="100"/>
                    <input data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0'" class="form form-control input-sm" id="num1" name="d100" autocomplete="off" autofocus value="<?php echo $d1; ?>"/>
                  </div>
                </div><!-- end of form-group -->
                <div class="form-group">
                  <label class="col-sm-6 control-label">&#8369 200.00</label>
                  <div class="col-sm-5">
                    <?php 
                            $d2 = getGCrequestItems(
                            $link,
                            'sri_items_quantity',
                            'store_request_items',                                            
                            'sri_items_denomination',
                            '2',
                            'sri_items_requestid',
                            $pendreqID
                            );

                            $sub2 = $d2 * 200; 
                    ?>
                 <input type="hidden" id="m2" value="200"/>
                  <input data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0'" class="form form-control input-sm" id="num2" name="d200" autocomplete="off" value="<?php echo $d2; ?>" />
                  </div>
                </div><!-- end of form-group -->
                <div class="form-group">
                  <label class="col-sm-6 control-label">&#8369 500.00</label>
                  <div class="col-sm-5">
                    <?php 
                            $d3 = getGCrequestItems(
                            $link,
                            'sri_items_quantity',
                            'store_request_items',                                            
                            'sri_items_denomination',
                            '3',
                            'sri_items_requestid',
                            $pendreqID
                            );

                            $sub3 = $d3 * 500; 
                    ?>
                   <input type="hidden" id="m3" value="500"/>
                    <input data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0'" class="form form-control input-sm" id="num3" name="d500" autocomplete="off" value="<?php echo $d3; ?>"/>
                  </div>
                </div><!-- end of form-group -->
                <div class="form-group">
                  <label class="col-sm-6 control-label">&#8369 1000.00</label>
                  <div class="col-sm-5">
                    <?php 
                            $d4 = getGCrequestItems(
                            $link,
                            'sri_items_quantity',
                            'store_request_items',                                            
                            'sri_items_denomination',
                            '4',
                            'sri_items_requestid',
                            $pendreqID
                            );

                            $sub4 = $d4 * 1000; 
                    ?>
                    <input type="hidden" id="m4" value="1000"/>
                    <input data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0'" class="form form-control input-sm" id="num4" name="d1000" autocomplete="off" value="<?php echo $d4 ?>"/>
                  </div>
                </div><!-- end of form-group -->
                <div class="form-group">
                  <label class="col-sm-6 control-label">&#8369 2000.00</label>
                  <div class="col-sm-5">
                    <?php 
                            $d5 = getGCrequestItems(
                            $link,
                            'sri_items_quantity',
                            'store_request_items',                                            
                            'sri_items_denomination',
                            '5',
                            'sri_items_requestid',
                            $pendreqID
                            );

                            $sub5 = $d5 * 2000; 
                    ?>
                    <input type="hidden" id="m5" value="2000"/>
                    <input data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0'" class="form form-control input-sm" id="num5" name="d2000" autocomplete="off" value="<?php echo $d5; ?>"/>
                  </div>
                </div><!-- end of form-group -->
                <div class="form-group">
                  <label class="col-sm-6 control-label">&#8369 5000.00</label>
                  <div class="col-sm-5">
                    <?php 
                            $d6 = getGCrequestItems(
                            $link,
                            'sri_items_quantity',
                            'store_request_items',                                            
                            'sri_items_denomination',
                            '6',
                            'sri_items_requestid',
                            $pendreqID
                            );

                            $sub6 = $d6 * 5000; 
                    ?>
                    <input type="hidden" id="m6" value="5000"/>
                    <input data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0'" class="form form-control input-sm" id="num6" name="d5000" autocomplete="off" value="<?php echo $d6; ?>"/>
                  </div>
                </div><!-- end of form-group -->
                <div class="form-group">
                  <label class="col-sm-6 control-label">Total</label>
                  <div class="col-sm-6">
                    <?php $total = $sub1 + $sub2 + $sub3 + $sub4 + $sub5 + $sub6; ?>
                    <input type="hidden" value="0" id="_totalReq"/>
                    <input type="text" class="form form-control input-sm" id="totalReq" readonly="readonly" value="<?php echo number_format($total,2); ?>">
                  </div>
                </div><!-- end of form-group -->
              </div>
                <?php 
                    $query_r = $link->query("SELECT * FROM `store_gcrequest` WHERE `sgc_status` = '0' AND `sgc_store`='$store_id'");

                    if(!$query_r){
                        echo $link->error;
                    } 

                    $row_r = $query_r->fetch_assoc();
                ?>              
              <div class="col-sm-7">
                <div class="form-group">
                  <label class="col-sm-4 control-label">GC Request No.:</label>
                  <div class="col-sm-2">
                    <input type="text" class="form form-control input-sm" readonly="readonly" value="<?php echo $row_r['sgc_num'];  ?>" name="penum">      
                  </div>
                </div><!-- end of form-group -->
                <div class="form-group">
                  <label class="col-sm-4 control-label">Retail Store:</label>
                  <div class="col-sm-4">
                      <input type="text" class="form form-control input-sm" name="storename" readonly="readonly" value="<?php echo getField($link,'store_name','stores','store_id',$storeid); ?>">
                      <input type="hidden" name="storeid" value="<?php echo $storeid; ?>">
                  </div>
                </div><!-- end of form-group -->
                <div class="form-group">
                  <label class="col-sm-4 control-label">Date Requested:</label>
                  <div class="col-sm-4">
                      <input type="text" class="form form-control input-sm" readonly="readonly" value="<?php echo _dateFormat($row_r['sgc_date_request']); ?>">
                  </div>
                </div><!-- end of form-group -->
                <div class="form-group">
                  <label class="col-sm-4 control-label">Date Needed:</label>
                  <div class="col-sm-4">
                      <input type="text" class="form form-control input-sm" id="dp1" data-date-format="MM dd, yyyy" name="date_needed" value="<?php echo _dateFormat($row_r['sgc_date_needed']); ?>" required>
                  </div>
                </div><!-- end of form-group -->
                <div class="form-group">
                  <label class="col-sm-4 control-label">Upload Document:</label>
                  <div class="col-sm-4">
                      <input type="hidden" value="<?php echo $row_r['sgc_file_docno']; ?>" name="docu">
                      <input id="pics" type="file" name="pic[]" accept="image/*" class="form form-control input-sm" />
                  </div>
                </div><!-- end of form-group -->
                <div class="form-group">
                  <label class="col-sm-4 control-label">Remarks:</label>
                  <div class="col-sm-6">
                      <input type="text" class="form-control input-sm" name="remarks" autocomplete="off" value="<?php echo $row_r['sgc_remarks']; ?>">
                  </div>
                </div><!-- end of form-group -->
                <div class="form-group">
                  <label class="col-sm-4 control-label">Prepared by:</label>
                  <div class="col-sm-4">
                    <input type="text" readonly="readonly" class="form-control input-sm" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>">                     
                  </div>
                </div><!-- end of form-group -->
                <div class="form-group">
                  <div class="col-sm-offset-6 col-sm-3">
                    <button class="btn btn-block btn-primary"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Submit</button>                    
                  </div>
                </div><!-- end of form-group -->
                <div class="response">
                </div>
              </div><!-- end of col-7 -->
              </form>
            </div>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/store.js"></script>
<?php include 'footer.php' ?>