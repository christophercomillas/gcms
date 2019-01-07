<?php 
  session_start();
  include '../function.php';
  require 'header.php';
  if(isset($_GET['request']))
  {
      $request = $_GET['request'];
      if(checkIfHasRows($link,'pe_id','production_request','pe_id',$request,'pe_requisition','0'))
      {

          $rows = [];
          $query_pe = $link->query(
            "SELECT 
              * 
            FROM 
              `production_request`            
            WHERE 
              `pe_id`='$request'
            LIMIT 1
          ");           

          if($query_pe)
          {
              while($row = $query_pe->fetch_assoc())
              {
                $rows[] = $row;
              }
          } 
          else 
          {
              echo $link->error;
          }

      } 
      else 
      {
          header('location:index.php');
      }
  } 
  else 
  {
      header('location:../index.php?action=logout');
  }
?>

<?php require '../menu.php'; ?>
  
  <div class="main fluid">
    <?php foreach ($rows as $key): ?>
    <div class="form-container">
      <form class="form-horizontal" method="POST" id="requisitionForm" action="../ajax.php?action=requisition">
        <div class="row">
          <div class="col-sm-12">
          <div class="box">
            <div class="box-header"><h4><i class="fa fa-inbox"></i> Suggested E-Requisition Entry</h4></div>
            <div class="box-content">
              <div class="row">
                <div class="col-sm-5">
                  <div class="form-group">
                    <input type="hidden" name="id" value="<?php echo $request; ?>">
                    <label class="col-sm-5 control-label">E-Request No.:</label>
                    <div class="col-sm-7">
                      <input name='erquestno'type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo getRequestNo($link,'requisition_entry','requis_erno'); ?>"/>
                    </div>
                  </div><!-- end of form-group -->
                  <div class="form-group">
                    <label class="col-sm-5 control-label">Finalize:</label>
                    <div class="col-sm-7">
                      <select id="req-status" class="form form-control inptxt input-sm" name="status" required autofocus>
                          <option value="">-Select-</option>    
                          <option value="1">Approved</option>
                          <option value="3">Cancel</option>
                      </select>
                    </div>
                  </div><!-- end of form-group --> 
                  <div class="request-info">
<!--                     <div class="form-group">
                      <label class="col-sm-5 control-label">Manual Request No.:</label>
                      <div class="col-sm-7">
                        <input name="manualno" id="rmanual" type="text" class="form-control input-sm" autofocus required>
                      </div>
                    </div>end of form-group --> 
                    <div class="form-group">
                      <label class="col-sm-5 control-label">Prod. Req. #:</label>
                      <div class="col-sm-7">
                        <input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo $key['pe_num']; ?>">
                      </div>
                    </div><!-- end of form-group --> 
                    <div class="form-group">
                      <label class="col-sm-5 control-label">Date Requested:</label>
                      <div class="col-sm-7">
                        <input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo _dateFormat($key['pe_date_request']);?>">
                      </div>
                    </div><!-- end of form-group --> 
                    <div class="form-group">
                      <label class="col-sm-5 control-label">Date Needed:</label>
                      <div class="col-sm-7">
                        <input name="date_needed" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo _dateFormat($key['pe_date_needed']); ?>">
                      </div>
                    </div><!-- end of form-group --> 
                    <div class="form-group">
                      <label class="col-sm-5 control-label">Location:</label>
                      <div class="col-sm-7">
                        <input name="loc" type="text" class="form-control inptxt input-sm" readonly="readonly" value="AGC Head Office">
                      </div>
                    </div><!-- end of form-group --> 
                    <div class="form-group">
                      <label class="col-sm-5 control-label">Department:</label>
                      <div class="col-sm-7">
                        <input name="dept" type="text" class="form-control inptxt input-sm" readonly="readonly" value="Marketing">
                      </div>
                    </div><!-- end of form-group --> 
                    <div class="form-group">
                      <label class="col-sm-5 control-label">Remarks:</label>
                      <div class="col-sm-7">
                        <input type="text" id="rremarks" name="remarks" class="form-control inptxt input-sm" autocomplete="off" required>
                      </div>
                    </div><!-- end of form-group -->
                    <div class="form-group">
                      <label class="col-sm-5 control-label">Checked by:</label>
                      <div class="col-sm-7">
                        <div class="input-group">
                          <input name="checked" id="app-checkby" type="text" class="form-control inptxt input-sm" readonly="readonly" required="required">
                                            <span class="input-group-btn">
                                              <button class="btn btn-info input-sm" id="checkbud" onclick="requestAssig(<?php echo $_SESSION['gc_usertype']; ?>,1)" type="button">
                                                <span class="glyphicon glyphicon-search"></span>
                                                </button>
                                            </span>
                        </div><!-- input group -->
                      </div>
                    </div><!-- end form group -->
<!--                     <div class="form-group">
                      <label class="col-sm-5 control-label">Approved by:</label>
                      <div class="col-sm-7">
                        <div class="input-group">
                          <input name="approved" id="app-apprby" type="text" class="form-control inptxt input-sm" readonly="readonly" required="required">
                            <span class="input-group-btn">
                              <button class="btn btn-info input-sm" id="approvedbud" onclick="requestAssig(6,2)" type="button">
                                <span class="glyphicon glyphicon-search"></span>
                                </button>
                            </span>
                        </div>
                      </div>
                    </div> -->
                  </div> 
                    <div class="form-group">
                      <label class="col-sm-5 control-label label-prepared">Approved by:</label>
                      <div class="col-sm-7">
                        <input type="text" readonly="readonly" class="form-control inptxt input-sm" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>">
                      </div>
                    </div><!-- end of form-group -->
                  <div class="alert alert-warning req-disapproved">
                    Something here disapproved.....
                  </div>
                  <div class="alert alert-warning req-cancelled">
                    GC Barcode # of this requisition will be tag cancelled and cannot be use again.
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-8 col-sm-4">
                      <button id="btn" class="btn btn-block btn-primary"><span class="glyphicon glyphicon-log-in"></span> Confirm</button>
                    </div>
                  </div><!-- end of form-group -->
                  <div class="response">
                  </div> 
                </div>
                <div class="col-sm-6">
                  <div class="request-info">
                    <div class="form-group">        
                      <label class="col-sm-5 control-label">Select Supplier:</label>
                      <div class="col-sm-7">
                        <select class="form form-control inptxt input-sm" name="supplier" id="selectsupplier" required>
                            <option value=''>--Select--</option>
                            <?php 
                                $query = $link->query("SELECT `gcs_companyname`,`gcs_id` FROM `supplier`");
                                while($row = $query->fetch_object()):        
                            ?>
                            <option value="<?php echo $row->gcs_id; ?>"><?php echo $row->gcs_companyname?></option>
                            <?php endwhile; ?>
                        </select>  
                      </div>
                    </div><!-- end of form-group -->
                    <div class="form-group">
                      <label class="col-sm-5 control-label">Contact Person:</label>
                      <div class="col-sm-7">
                        <input type="text" class="form form-control inptxt input-sm" id="sup-cp" readonly="readonly" required>
                      </div>
                    </div><!-- end of form-group -->
                    <div class="form-group">
                      <label class="col-sm-5 control-label">Contact No.:</label>
                      <div class="col-sm-7">
                        <input type="text" id="sup-con" class="form form-control inptxt input-sm" readonly="readonly" required>
                      </div>
                    </div><!-- end of form-group -->
                    <div class="form-group">
                      <label class="col-sm-5 control-label">Address:</label>
                      <div class="col-sm-7">
                        <!-- <input type="text" id="sup-adds" class="form form-control inptxt input-sm" readonly="readonly" required> -->
                        <textarea id="sup-adds" class="form form-control inptxt input-sm" readonly="readonly" required>
                          
                        </textarea>
                      </div>
                    </div><!-- end of form-group -->
                  </div>
                  <h5 class="h5-requisition">Request for gift cheque printing as per breakdown provided below.</h5>
                  <table class="table" >
                    <thead>
                      <tr>
                        <th>Denomination</th>
                        <th>Qty</th>
                        <th>Unit</th>
                        <th>Barcode No. Start</th>
                        <th>Barcode No. End</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                          $query = $link->query(
                              "SELECT 
                                  * 
                              FROM
                                  `production_request_items`
                              INNER JOIN 
                                  `denomination`
                              ON 
                                  `production_request_items`.`pe_items_denomination` = `denomination`.`denom_id`
                              WHERE 
                                  `pe_items_request_id`='$request'
                          ");

                          while($row = $query->fetch_object()):
                      ?>
                      <tr>
                          <td>&#8369 <?php echo number_format($row->denomination,2); ?></td>
                          <td><?php echo $row->pe_items_quantity; ?></td>
                          <td>pc(s)</td>
                          <td>
                          <?php 
                              $query_first = $link->query(
                                  "SELECT 
                                      `barcode_no`
                                  FROM 
                                      `gc`
                                  WHERE 
                                      `denom_id`='$row->pe_items_denomination'
                                  AND
                                     `pe_entry_gc`='$request' 
                                  ORDER BY 
                                  `barcode_no`
                                  ASC 
                                  LIMIT 1"     
                              );

                              $row_f = $query_first->fetch_object();

                              echo $row_f->barcode_no;
                          ?>
                          
                          <td>
                          <?php 
                              $query_last = $link->query(
                                  "SELECT 
                                      `barcode_no`
                                  FROM 
                                      `gc`
                                  WHERE 
                                      `denom_id`='$row->pe_items_denomination'
                                  AND
                                     `pe_entry_gc`='$request' 
                                  ORDER BY 
                                  `barcode_no`
                                  DESC 
                                  LIMIT 1"     
                              );

                              $row_l = $query_last->fetch_object();

                              echo $row_l->barcode_no;
                          ?>
                          </td>
                      </tr>                            
                      <?php endwhile; ?>
                    </tbody>
                  </table>
                </div>
            </div>
          </div>
          </div>
        </div>
      </form>
      <?php endforeach; ?>
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
<script type="text/javascript" src="../assets/js/marketing.js"></script>
<?php include 'footer.php' ?>