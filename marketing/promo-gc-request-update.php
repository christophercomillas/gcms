<?php 
  session_start();
  include '../function.php';
  include 'header.php';

  $hasError = false;
  if(!isset($_GET['id']))
  {
      $hasError = true;
  }
  $id = (int)$_GET['id'];

  $table = 'promo_gc_request';
  $select = "promo_gc_request.pgcreq_reqnum,
      promo_gc_request.pgcreq_datereq,
      promo_gc_request.pgcreq_dateneeded,
      promo_gc_request.pgcreq_doc,
      promo_gc_request.pgcreq_status,
      promo_gc_request.pgcreq_group_status,
      promo_gc_request.pgcreq_total,
      promo_gc_request.pgcreq_group,
      promo_gc_request.pgcreq_remarks,
      promo_gc_request.pgcreq_id,
      CONCAT(users.firstname,' ',users.lastname) as prep";
  $where = "promo_gc_request.pgcreq_id = '".$id."'
      AND
        promo_gc_request.pgcreq_status='pending'
      AND
        promo_gc_request.pgcreq_group_status=''
      OR
        promo_gc_request.pgcreq_group_status='approved'";
  $join = 'INNER JOIN
        users
      ON
        users.user_id = promo_gc_request.pgcreq_reqby';
  $limit = '';

  $promo = getSelectedData($link,$table,$select,$where,$join,$limit);

  $denoms = getAllDenomination($link);
  
  if(count($promo)==0)
  {
    $hasError = true;
  } 

?>
<?php require '../menu.php'; ?>

    <div class="main fluid">
    
     <div class="row">
        <?php if(!$hasError): ?>
          <div class="col-sm-8">
            <div class="box">
            <div class="box-header">
              <h4><i class="fa fa-inbox"></i> 
                <?php if($promo->pgcreq_group_status==''): ?>
                  Promo GC Request Update Form
                <?php else: ?>
                  Promo GC Request
                <?php endif; ?>
              </h4></div>
              <?php if($promo->pgcreq_group_status==''): ?>
                <div class="box-content form-container">
                  <form class="form-horizontal" id="promoreqFormupdate" method='POST' action="../ajax.php?action=promoRequestupdate">
                    <input type="hidden" name="reqid" id="reqid" value="<?php echo $promo->pgcreq_id; ?>"> 
                    <input type="hidden" name="totpromoreq" id="totpromoreq" value="<?php echo $promo->pgcreq_total; ?>"> 
                    <div class="form-group">
                      <label class="col-sm-3 control-label">RFPROM No.</label>  
                      <div class="col-sm-3">
                      <input value="<?php echo  $promo->pgcreq_reqnum; ?>" name="preqnum" type="text" class="form-control inptxt input-sm" readonly="readonly">                
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Date Requested:</label>  
                      <div class="col-sm-4">
                      <input value="<?php echo _dateformat($promo->pgcreq_datereq); ?>" type="text" class="form-control inptxt input-sm" readonly="readonly">         
                      </div>
                    </div>          
                    <div class="form-group">
                      <label class="col-sm-3 control-label"><span class="requiredf">*</span>Date Needed:</label>  
                      <div class="col-sm-4">                  
                      <input type="text" class="form form-control inptxt input-sm ro" id="dp1" data-date-format="MM dd, yyyy" name="date_needed" readonly="readonly" value="<?php echo _dateformat($promo->pgcreq_dateneeded); ?>" required>
                      </div>
                    </div>
                    <?php if(trim($promo->pgcreq_doc !='')): ?>
                      <div class="form-group">
                        <label class="col-sm-3 control-label">Uploaded Copy:</label>  
                        <div class="col-sm-4">
                          <a class="btn btn-block btn-default" href='../assets/images/promoRequestFile/download.php?file=<?php echo $promo->pgcreq_doc; ?>'>Download</a>                 
                        </div>                    
                      </div>                  
                    <?php endif; ?> 
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Upload Scan Copy:</label>  
                      <div class="col-sm-4">
                      <input id="pics" type="file" name="docs[]" accept="image/*" class="form-control inptxt input-sm" />
                      </div> 
                    </div> 
                    <div class="form-group">
                      <label class="col-sm-3 control-label"><span class="requiredf">*</span>Remarks:</label>  
                      <div class="col-sm-6">
                      <input name="remarks" value="<?php echo $promo->pgcreq_remarks ?>" type="text" class="form-control inptxt input-sm" required autocomplete="off" autofocus>                
                      </div> 
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label"><span class="requiredf">*</span>Promo Group:</label>  
                      <div class="col-sm-4">                  
                        <select class="form form-control inptxt input-sm promog" name="group" required>
                          <?php if($promo->pgcreq_group == 1): ?>
                            <option value="1">Group 1</option>
                            <option value="2">Group 2</option>
                          <?php else: ?>
                            <option value="2">Group 2</option>
                            <option value="1">Group 1</option>
                          <?php endif; ?>

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
                          <input class="form form-control inptxt denfield" id="num<?php echo $d->denom_id; ?>" value="<?php echo getRequestedQtyforPromoRequest($link,$promo->pgcreq_id,$d->denom_id); ?>" name="denoms<?php echo $d->denom_id; ?>" autocomplete="off" />
                          </div>                   
                      </div>                  
                    <?php endforeach ?>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Updated by:</label>  
                      <div class="col-sm-4">
                      <input name="textinput" type="text" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>" class="form-control input-sm inptxt" readonly="readonly">                
                      </div>                    
                      <div class="col-sm-4">
                        <button id="btn" type="submit" class="btn btn-block btn-primary"><span class="glyphicon glyphicon-log-in"></span> &nbsp;Update </button>
                      </div> 
                    </div>
                  </form>
                  <div class="response">
                  </div>
                </div>
              <?php else: ?>
                <div class="box-content form-container form-horizontal">
                  <div class="form-group">
                    <label class="col-sm-3 control-label">RFPROM No.</label>  
                    <div class="col-sm-3">
                    <input value="<?php echo  $promo->pgcreq_reqnum; ?>" name="preqnum" type="text" class="form-control inptxt input-sm" readonly="readonly">                
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Date Requested:</label>  
                    <div class="col-sm-4">
                    <input value="<?php echo _dateformat($promo->pgcreq_datereq); ?>" type="text" class="form-control inptxt input-sm" readonly="readonly">         
                    </div>
                  </div>          
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Date Needed:</label>  
                    <div class="col-sm-4">                  
                    <input type="text" class="form form-control inptxt input-sm ro" readonly="readonly" value="<?php echo _dateformat($promo->pgcreq_dateneeded); ?>" required>
                    </div>
                  </div>
                  <?php if(trim($promo->pgcreq_doc !='')): ?>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Uploaded Copy:</label>  
                      <div class="col-sm-4">
                        <a class="btn btn-block btn-default" href='../assets/images/promoRequestFile/download.php?file=<?php echo $promo->pgcreq_doc; ?>'>Download</a>                 
                      </div>                    
                    </div>                  
                  <?php endif; ?> 
                  <div class="form-group">
                    <label class="col-sm-3 control-label"><span class="requiredf">*</span>Remarks:</label>  
                    <div class="col-sm-6">
                    <input name="remarks" value="<?php echo $promo->pgcreq_remarks ?>" readonly="readonly" type="text" class="form-control inptxt input-sm" required autocomplete="off" autofocus>                
                    </div> 
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label"><span class="requiredf">*</span>Promo Group:</label>  
                    <div class="col-sm-4">                  
                      <input name="remarks" value="Group <?php echo $promo->pgcreq_group ?>" readonly="readonly" type="text" class="form-control inptxt input-sm">                       
                    </div>                  
                  </div>
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Denomination</th>
                        <th>Quantity</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($denoms as $d): ?>
                        <tr>
                          <td>&#8369 <?php echo number_format($d->denomination,2); ?></td>
                          <td><?php echo getRequestedQtyforPromoRequest($link,$promo->pgcreq_id,$d->denom_id); ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Prepared by:</label>  
                    <div class="col-sm-4">
                    <input name="textinput" type="text" value="<?php echo ucwords($promo->prep); ?>" class="form-control input-sm inptxt" readonly="readonly">                
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="box bot-margin">
              <div class="box-header"><h4><i class="fa fa-inbox"></i> Total Promo GC Request</h4></div>
              <div class="box-content">
                <h3 class="current-budget mbot">&#8369 <span id="totpromo"><?php echo number_format($promo->pgcreq_total,2); ?></span></h3>              
              </div>
            </div>
            <?php if($promo->pgcreq_group_status!=''): ?>
              <div class="alert alert-info">              
                Promo GC already recommended and waiting for Finance Department approval.
              </div>
            <?php endif; ?>
          </div>
        <?php else: ?>
          <div class="col-sm-5">
            Something wen't wrong.
          </div>
        <?php endif; ?>
    </div>

<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/marketing.js"></script>
<?php include 'footer.php' ?>