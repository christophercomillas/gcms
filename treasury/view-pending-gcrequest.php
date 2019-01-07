<?php 
  session_start();
  include '../function.php';
  include 'header.php';

    if(isset($_GET['page'])){
        $page = $_GET['page'];

        if(checkIfExist($link,'sgc_id','store_gcrequest','sgc_id',$page)){

        } else {
            header('location:../index.php?action=logout');
        }

    } else {
        header('location:../index.php?action=logout');
    }

?>

<?php require '../menu.php'; ?>

    <div class="main fluid">
      <?php

              $query = $link->query(
                  "SELECT 
                      `store_gcrequest`.`sgc_id`,
                      `store_gcrequest`.`sgc_num`,
                      `store_gcrequest`.`sgc_requested_by`,
                      `store_gcrequest`.`sgc_date_request`,
                      `store_gcrequest`.`sgc_date_needed`,
                      `store_gcrequest`.`sgc_file_docno`,
                      `store_gcrequest`.`sgc_remarks`,
                      `store_gcrequest`.`sgc_store`,
                      `stores`.`store_name`
                  FROM
                      `store_gcrequest`
                  INNER JOIN 
                      `stores`
                  ON
                      `store_gcrequest`.`sgc_store` = `stores`.`store_id`
                  WHERE
                      `store_gcrequest`.`sgc_id`='$page'
                  ");
              
                  if($query){
                      $row = $query->fetch_assoc();
                  } else {
                      echo $link->error;
                  }

      ?> 
          <ol class="breadcrumb">
            <li><a href="index.php">Dashboard</a></li>
            <li> <a href="tran_release_gc.php">Pending GC Request List</a></li>
            <li class="active">Pending GC Request</li>
          </ol>    
      <div class="row">
        <div class="col-sm-5">
          <div class="box">
            <div class="box-header"><h4><i class="fa fa-inbox"></i> Store GC Request Releasing Form </h4></div>
              <div class="box-content form-container">
                <form class="form-horizontal" action="../ajax.php?action=gcRequestStat" method="POST" id="gcRequestStat">
                  <input type="hidden" id="reqid" name="reqid" value="<?php echo $row['sgc_id']; ?>">
                  <input type="hidden" name="store_id" value="<?php echo $row['sgc_store']; ?>">
                  <?php $storeid = $row['sgc_store']; ?> 
                  <div class="form-group">
                    <label class="col-sm-6 control-label">GC Releasing No.:</label>
                    <div class="col-sm-6">
                      
                    </div>
                  </div><!-- end of form-group -->
                  <div class="form-group">
                    <label class="col-sm-6 control-label">GC Request Status:</label>
                    <div class="col-sm-6">
                      <select id="status" class="form form-control input-sm" name="status" required autofocus>
                          <option value="">-Select-</option>    
                          <option value="1">Approved</option>
                          <option value="2">Cancel</option>
                      </select>
                    </div>
                  </div><!-- end of form-group -->
                  <div class="hide-cancel">
                  <div class="form-group">
                    <label class="col-sm-6 control-label">Date Approved/Cancelled:</label>
                    <div class="col-sm-6">
                      <input name="proc" type="text" class="form form-control input-sm" value="<?php echo _dateFormat($todays_date); ?>" readonly="readonly">
                    </div>
                  </div><!-- end of form-group -->
                  <div class="form-group">
                    <label class="col-sm-6 control-label">Upload Document:</label>
                    <div class="col-sm-6">
                      <input id="upload" type="file" class="form-control input-sm" name="pic[]" accept="image/*" required />
                    </div>
                  </div><!-- end of form-group -->
                  <div class="form-group">
                    <label id="remark" class="col-sm-6 control-label">Remarks:</label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control input-sm" name="remark" id="remark" required />
                    </div>
                  </div><!-- end of form-group -->
                  <div class="form-group">
                    <label class="col-sm-6 control-label">Checked by:</label>
                    <div class="col-sm-6">
                      <div class="input-group">
                        <input name="checked" id="app-checkby" type="text" class="form-control input-sm" readonly="readonly" required="required">
                                          <span class="input-group-btn">
                                            <button class="btn btn-info input-sm" id="checkbud" type="button">
                                              <span class="glyphicon glyphicon-search"></span>
                                              </button>
                                          </span>
                      </div><!-- input group -->
                    </div>
                  </div><!-- end form group -->
                  <div class="form-group">
                    <label class="col-sm-6 control-label">Approved by:</label>
                    <div class="col-sm-6">
                      <div class="input-group">
                        <input name="approved" id="app-apprby" type="text" class="form-control input-sm" readonly="readonly" required="required">
                                          <span class="input-group-btn">
                                            <button class="btn btn-info input-sm" id="approvedbud" type="button">
                                              <span class="glyphicon glyphicon-search"></span>
                                              </button>
                                          </span>
                      </div><!-- input group -->
                    </div>
                  </div><!-- end form group -->
                  </div>  
                  <div class="form-group">
                    <label class="col-sm-6 control-label label-prepared">Prepared by:</label>
                    <div class="col-sm-6">
                      <input name="prepared" type="text" readonly="readonly" class="form form-control input-sm" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>" />
                    </div>
                  </div><!-- end form group -->

                  <div class="form-group">
                    <div class="col-sm-offset-7 col-sm-5">
                      <button type="submit" class="btn btn-block btn-primary pull-right"><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span> Submit</button>
                    </div>
                  </div><!-- end form group -->
                  <div class="response">
                  </div>
                </form>
              </div>
          </div>
        </div>   
        <div class="col-sm-7">
          <div class="box">
            <div class="box-header"><h4><i class="fa fa-inbox"></i> Pending GC Request (Details) </h4></div>
              <div class="box-content form-container">
                <form class="form-horizontal">
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Store:</label>
                    <div class="col-sm-3">
                      <input type="text" class="form-control input-sm" readonly="readonly" value="<?php echo $row['store_name']; ?>">
                    </div>
                    <label class="col-sm-3 control-label">GC Request No.:</label>
                    <div class="col-sm-3">
                      <input type="hidden" name="reqid" value="<?php echo $row['sgc_id']; ?>">
                      <input type="hidden" name="store_id" value="<?php echo $row['sgc_store']; ?>"> 
                      <input type="text" class="form-control input-sm" readonly="readonly" value="<?php echo $row['sgc_num']; ?>">
                    </div>
                  </div><!-- end of form-group -->
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Date Requested:</label>
                    <div class="col-sm-3">
                      <input type="text" class="form-control input-sm" readonly="readonly" value="<?php echo _dateFormat($row['sgc_date_request']); ?>">
                    </div>
                    <label class="col-sm-3 control-label">Time Requested:</label>
                    <div class="col-sm-3">
                      <input type="text" class="form-control input-sm" readonly="readonly" value="<?php echo _timeFormat($row['sgc_date_request']); ?>">
                    </div>
                  </div><!-- end of form-group -->
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Date Neededed:</label>
                    <div class="col-sm-3">
                      <input type="text" class="form-control input-sm" readonly="readonly" value="<?php echo _dateFormat($row['sgc_date_needed']); ?>">
                    </div>
                    <label class="col-sm-3 control-label">Document:</label>
                    <div class="col-sm-3">
                      <a class="btn btn-block btn-default" href='../assets/images/gcRequestStore/download.php?file=<?php echo $row['sgc_file_docno']; ?>.jpg'><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download</a>
                    </div>
                  </div><!-- end of form-group -->
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Remarks:</label>
                    <div class="col-sm-5">
                      <input type="text" class="form-control input-sm" readonly="readonly" value="<?php echo ucwords($row['sgc_remarks']); ?>">
                    </div>
                  </div><!-- end of form-group -->  
                  <div class="form-group">
                    <label class="col-sm-3 control-label">Requested by:</label>
                    <div class="col-sm-3">
                      <input type="text" class="form-control input-sm" readonly="readonly" value="<?php echo ucwords($row['sgc_requested_by']); ?>">
                    </div>
                  </div><!-- end of form-group -->
                  <table class="table table-req">
                    <thead>
                      <tr>
                        <th>Denomination</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Allocated GC</th>
                      </tr>                      
                    </thead>
                    <tbody>
                      <?php 

                          $query = $link->query(
                                  "SELECT 
                                      `store_request_items`.`sri_items_quantity`,
                                      `store_request_items`.`sri_items_denomination`,
                                      `denomination`.`denomination`
                                  FROM 
                                      `store_request_items`
                                  INNER JOIN 
                                      `denomination`
                                  ON
                                      `store_request_items`.`sri_items_denomination`=`denomination`.`denom_id`
                                  WHERE      
                                      `sri_items_requestid`='$page'
                          ");

                          if(!$query){
                              echo $link->error;
                          }

                      ?>
                      <?php 
                          $total = 0;
                          while($row = $query->fetch_assoc()): 
                      ?>
                          <tr>
                              <?php $subtotal = $row['denomination'] * $row['sri_items_quantity']; 

                                  $total = $total + $subtotal;

                              ?>
                              <td class="td-den">&#8369 <?php echo number_format($row['denomination'],2); ?></td>
                              <td><?php echo $row['sri_items_quantity']; ?> pc(s)</td>
                              <td>&#8369 <?php echo number_format($subtotal,2); ?></td>
                              <?php $denom = $row['sri_items_denomination']; ?>
                              <td><?php echo getValidationNumRowsByStore($link,$storeid,$denom).' pc(s)'; ?></td>
                          </tr>
                      <?php endwhile; ?>
                      <tr class="td-total">
                          <td></td>
                          <td><label>Total</label></td>
                          <td>&#8369 <?php echo  number_format($total,2); ?></td>
                          <td></td>
                      </tr>  
                    </tbody>
                  </table>                           
                </form>
              </div>
          </div>
        </div>  
      </div><!-- end row -->      
    </div><!-- end fluid div -->

<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/main.js"></script>
<?php include 'footer.php' ?>