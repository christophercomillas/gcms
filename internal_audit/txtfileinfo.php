 <?php 
  session_start();
  include '../function.php';
  require 'header.php';
  truncateTB($link,'temp_validation');
  if(isset($_GET['txtfile']) && trim($_GET['txtfile'])!='')
  {
    $txtfile = $_GET['txtfile'];
  }
  else 
  {
    exit();
  }

  //check if textfile exist

?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
    <div class="row">
      <div class="col-sm-11">
      <!-- begin box -->
      <?php
        if(getFADIPConnectionStatus($link))
        {
          $fadnew = getField($link,'app_settingvalue','app_settings','app_tablename','fad_server_ip_received_new');
        }
        else 
        {
          $fadnew = $dir.getField($link,'app_settingvalue','app_settings','app_tablename','localhost_received_new');
        }
        if(checkIfTextfileExist($fadnew.'/'.$txtfile)):
        $recNum = getReceivingNumber($link,'csrr_id','custodian_srr');
        $denom = getAllDenomination($link);
        $hasError = false;

        // read textfile
        $r_f = fopen($fadnew.'/'.$txtfile,'r');                  
        while(!feof($r_f)) 
        {
          $arr_f[] = fgets($r_f);
        }
        fclose($r_f);
        $arr_deno = [];
        $sulod = 1;
        for ($i=0; $i < count($arr_f); $i++) 
        { 
          if($i==0)
          {
            if(trim($arr_f[$i])!='FAD Purchase Order Details')
            {
              $hasError=true;
              break;
            }
          }

          $sulod = 2;

          $c = explode("|",$arr_f[$i]);
          if(trim($c[0])=='GC E-REQUISITION NO')
          {
            $reqnum = $c[1];
          }

          if(trim($c[0])=='Receiving No')
          {
            $fadrec = $c[1];
          }
          if(trim($c[0])=='Transaction Date')
          {
            $transdate = $c[1];
          }
          if(trim($c[0])=='Reference No')
          {
            $refno = $c[1];
          } 
          if(trim($c[0])=='Purchase Order No')
          {
            $purno = $c[1];
          } 
          if(trim($c[0])=='Purchase Date')
          {
            $purdate = $c[1];
          } 
          if(trim($c[0])=='Reference PO No')
          {
            $refpono = $c[1];
          } 
          if(trim($c[0])=='Payment Terms')
          {
            $payterms = $c[1];
          } 
          if(trim($c[0])=='Location Code')
          {
            $locode = $c[1];
          } 
          if(trim($c[0])=='Department Code')
          {
            $depcode = $c[1];
          } 
          if(trim($c[0])=='Supplier Name')
          {
            $supplier = $c[1];
          }
          if(trim($c[0])=='Mode of Payment')
          {
            $mop = $c[1];
          }
          if(trim($c[0])=='Remarks')
          {
            $remarks = $c[1];
          }
          if(trim($c[0])=='Prepared By')
          {
            $prepby = $c[1];
          }
          if(trim($c[0])=='Checked By')
          {
            $checkby = $c[1];
          }
          if(trim($c[0])=='SRR Type')
          {
            $srr = $c[1];
          }
          if($i>16)
          {
            if(trim($arr_f[$i])!='')
            {
              $arr_deno[] =  array(
                'code' => $c[0],
                'qty' => $c[1]
              );
            }
          }
        }
        
        $ereq = getEreqID($link,$reqnum);

        if(!is_null($ereq)):

      ?>
        <div class="box box-bot">
          <div class="box-header">
            <span class="box-title-with-btn"><i class="fa fa-inbox">
                </i> FAD Textfile Details
            </span>
            <div class="col-sm-8 form-horizontal pull-right p-right">
                <div class="col-sm-offset-10 col-sm-2">
                  <a href="index.php" class="btn btn-block btn-info"><i class="fa fa-chevron-left"></i></a>
<!--                     <button class="btn btn-block btn-info"><i class="fa fa-chevron-left" onclick="document.location = 'index.php'"></i>
 </button> -->
                </div>
            </div> 
          </div>
            <div class="box-content form-container">
              <div class="row">

                <form class="form-horizontal" action="../ajax.php?action=custodianrec" id="gc_srr" method="post">
                  <input type="hidden" name="tfile" value="<?php echo $txtfile; ?>">                    
                  <input type="hidden" name="uid" value="<?php echo $ereq->requis_id;?>">  
                  <input type="hidden" name="requisid" value="<?php echo $ereq->requis_id;?>">
                  <input type="hidden" name="prid" value="<?php echo $ereq->repuis_pro_id; ?>">
                  <div class="col-xs-6">                    
                    <div class="form-group">
                      <label class="col-sm-5 control-label">GC Receiving No.</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control input-sm reqfield inptxt" name="gcrecno" value="<?php echo $recNum; ?>" readonly="readonly">
                      </div>
                    </div>  
                    <div class="form-group">
                      <label class="col-sm-5 control-label">E-Requisition No.</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control input-sm reqfield inptxt" value="<?php echo addZeroToStringZ($reqnum,3); ?>" readonly="readonly">
                      </div>
                    </div>    
                    <div class="form-group">
                      <label class="col-sm-5 control-label">FAD Receiving Type</label>
                      <div class="col-sm-5">
                        <input type="text" class="form-control input-sm reqfield srrtype inptxt" name="rectype" readonly="readonly" value="<?php echo $srr; ?>">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-5 control-label">Received As</label>
                      <div class="col-sm-5">
                        <select class="form-control input-sm" required name="recas">
                          <option value="">- Select -</option>
                          <option value="whole">Whole</option>
                          <option value="partial"> Partial</option>
                          <option value="final">Final</option>
                        </select>
                      </div>
                    </div>
                    <div class="group-wrap">
                      <table class="table" id="table-FAD">
                        <thead>
                          <th>Denomination</th>
                          <th class="cntrtxt">Qty Received</th>
                          <th class="cntrtxt">Validated GC</th>
                        </thead>
                        <tbody>
                          <?php foreach ($denom as $d): ?>
                            <?php 

                              $qty = 0;

                              foreach ($arr_deno as $k => $value) 
                              {
                                  if($value['code']==$d->denom_fad_item_number)
                                  {
                                    $qty = $value['qty'];
                                    break;
                                  }
                              }              
                            ?>
                            <tr>
                              <td class="denlist"><?php echo number_format($d->denomination,2); ?></td>
                              <td><input type="text" id="rnumgc" class="form-control input-sm inptxt den<?php echo $d->denom_id; ?>" name="den<?php echo $d->denom_id; ?>" readonly="readonly" value="<?php echo $qty; ?>"></td>
                              <td><input type="text" class="form-control input-sm inptxt n<?php echo $d->denom_id; ?>" name="scan<?php echo $d->denom_id; ?>" value="0" readonly="readonly"></td>
                            </tr>
                          <?php endforeach; ?>
                          <tr>
                            <td></td>
                            <td><button type="button" class="btn btn-default btn-block" onclick="managerKeyValidateRange()"><i class="fa fa-barcode"></i>
               Validate By Range</button></td>
                            <td><button type="button" class="btn btn-default btn-block" onclick="validateGCus(<?php echo $recNum; ?>)"><i class="fa fa-barcode"></i>
               Validate By Barcode</button></td>
                          </tr>
                          <tr>
                            <td></td>
                            <td></td>
                            <td><button type="button" class="btn btn-default btn-block" onclick="scannedGCCus(<?php echo $recNum; ?>)"><i class="fa fa-barcode"></i>
               Scanned GC</button></td>
                          </tr>           
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="col-xs-6">
                    <div class="poheader">
                      P.O. Details
                    </div>  
                    <div class="group-wrap group-wrapdetails">
                      <div class="form-group sxs-frm">
                        <label class="col-sm-5 control-label sxs-lbl">FAD Receiving No:</label>
                        <div class="col-sm-7">
                          <input type="text" value="<?php echo $fadrec; ?>"class="form-control input-sm sxs-int fadrec" name="fadrec" readonly="readonly">
                        </div>
                      </div>
                      <div class="form-group sxs-frm">
                        <label class="col-sm-5 control-label sxs-lbl">Transaction Date:</label>
                        <div class="col-sm-7">
                          <input type="text" value="<?php echo $transdate; ?>" class="form-control input-sm sxs-int trandate" name="trandate" readonly="readonly">
                        </div>
                      </div>
                      <div class="form-group sxs-frm">
                        <label class="col-sm-5 control-label sxs-lbl">Reference No:</label>
                        <div class="col-sm-7">
                          <input type="text" value="<?php echo $refno; ?>" class="form-control input-sm sxs-int refno" name="refno" readonly="readonly">
                        </div>
                      </div>
                      <div class="form-group sxs-frm">
                        <label class="col-sm-5 control-label sxs-lbl">Purchase Order No:</label>
                        <div class="col-sm-7">
                          <input type="text" value="<?php echo $purno; ?>" class="form-control input-sm sxs-int purono" name="purono" readonly="readonly">
                        </div>
                      </div>
                      <div class="form-group sxs-frm">
                        <label class="col-sm-5 control-label sxs-lbl">Purchase Date:</label>
                        <div class="col-sm-7">
                          <input type="text" value="<?php echo $purdate; ?>" class="form-control input-sm sxs-int purdate" name="purdate" readonly="readonly">
                        </div>
                      </div>
                      <div class="form-group sxs-frm">
                        <label class="col-sm-5 control-label sxs-lbl">Reference PO No:</label>
                        <div class="col-sm-7">
                          <input type="text" value="<?php echo $refpono; ?>" class="form-control input-sm sxs-int refpono" name="refpono" readonly="readonly">
                        </div>
                      </div>
                      <div class="form-group sxs-frm">
                        <label class="col-sm-5 control-label sxs-lbl">Payment Terms:</label>
                        <div class="col-sm-7">
                          <input type="text" value="<?php echo $payterms; ?>" class="form-control input-sm sxs-int payterms" name="payterms" readonly="readonly">
                        </div>
                      </div>
                      <div class="form-group sxs-frm">
                        <label class="col-sm-5 control-label sxs-lbl">Location Code:</label>
                        <div class="col-sm-7">
                          <input type="text" value="<?php echo $locode; ?>" class="form-control input-sm sxs-int locode" name="locode" readonly="readonly">
                        </div>
                      </div>
                      <div class="form-group sxs-frm">
                        <label class="col-sm-5 control-label sxs-lbl">Department Code:</label>
                        <div class="col-sm-7">
                          <input type="text" value="<?php echo $depcode; ?>" class="form-control input-sm sxs-int deptcode" name="deptcode" readonly="readonly">
                        </div>
                      </div>
                      <div class="form-group sxs-frm">
                        <label class="col-sm-5 control-label sxs-lbl">Supplier Name:</label>
                        <div class="col-sm-7">
                          <input type="text" value="<?php echo $supplier; ?>" class="form-control input-sm sxs-int supname" name="supname" readonly="readonly">
                        </div>
                      </div>
                      <div class="form-group sxs-frm">
                        <label class="col-sm-5 control-label sxs-lbl">Mode of Payment:</label>
                        <div class="col-sm-7">
                          <input type="text" value="<?php echo $mop; ?>" class="form-control input-sm sxs-int modpay" name="modpay" readonly="readonly">
                        </div>
                      </div>
                      <div class="form-group sxs-frm">
                        <label class="col-sm-5 control-label sxs-lbl">Remarks:</label>
                        <div class="col-sm-7">
                          <input type="text" value="<?php echo $remarks; ?>" class="form-control input-sm sxs-int remarks" name="remarks" readonly="readonly">
                        </div>
                      </div>
                      <div class="form-group sxs-frm">
                        <label class="col-sm-5 control-label sxs-lbl">Checked By:</label>
                        <div class="col-sm-7">
                          <input type="text" value="<?php echo $checkby; ?>" class="form-control input-sm sxs-int prepby" name="prepby" readonly="readonly">
                        </div>
                      </div>
                      <div class="form-group sxs-frm">
                        <label class="col-sm-5 control-label sxs-lbl">Prepared By:</label>
                        <div class="col-sm-7">
                          <input type="text" value="<?php echo $prepby; ?>" class="form-control input-sm sxs-int checkby" name="checkby" readonly="readonly">
                        </div>
                      </div>  
                    </div>
                    <div class="response">
                    </div>
                    <div class="form-group sxs-frm">
                      <div class="col-xs-offset-2 col-xs-5">
                        <button class="btn btn-block btn-danger" type="button" onclick="managerKey(<?php echo $_SESSION['gc_id']; ?>)"><i class="fa fa-key"></i> Manager Key</button>
                      </div>
                      <div class="col-xs-5">
                        <button class="btn btn-block btn-primary" type="submit" id="srrbut"><i class="fa fa-floppy-o"></i> Submit</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- end box -->
        <?php else: ?>
          <div class="alert alert-danger">Requisition # <?php echo $reqnum; ?> dont existx.</div>
        <?php endif; ?>
      <?php else: ?>
        <div class="alert alert-danger">Textfile not found.</div>
      <?php endif; ?>
      </div> <!-- end of col -->
    </div>
  </div>
  <div class="modal modal-static fade loadingstyle" id="processing-modal" role="dialog" aria-hidden="true" data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog loadingstyle">
        <div class="text-center">
            <img src="../assets/images/ring-alt.svg" class="icon" />
            <h4 class="loading">Saving Data Please Wait...</h4>
        </div>
      </div>
  </div>


<?php include 'jscripts.php'; ?>
<script type="text/javascript">
  restrictback = 1;
  window.onbeforeunload = function() { if(restrictback==1){
    return "You work will be lost.";
  } };
</script>
<script type="text/javascript" src="../assets/js/cus.js"></script>
<?php include 'footer.php' ?>