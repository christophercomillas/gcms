<?php 
  session_start();
  include '../function.php';
  include 'header.php';
  // $query_store = $link->query("SELECT `store_id`,`store_name` FROM `stores`");
  // $query_gc_type = $link->query("SELECT `gc_type_id`,`gctype`,`gc_status` FROM `gc_type` WHERE `gc_status`='1'"); 
  $storerequestlist = gcstorerequestList($link);
?>

<?php require '../menu.php'; ?>

    <div class="main fluid">    
      <div class="row">
        <div class="col-sm-12">
          <ol class="breadcrumb">
            <li><a href="index.php">Home</a></li>
            <li class="active"></li>
          </ol>
          <div class="box">
            <div class="box-header"><h4><i class="fa fa-inbox"></i> GC Request List</h4></div>
              <div class="box-content form-container">
                  <table class="table table-adjust" id="pendgc">
                      <thead>
                          <tr>
                              <th>GC Request No.</th>
                              <th>Requested By</th>         
                              <th>Date Needed</th>
                              <th>Prepared By</th>
                              <th>Date Requested</th>                                    
                              <th>Request Status</th>                
                          </tr>
                      </thead>
                      <tbody class="store-request-list">
                        <?php foreach ($storerequestlist as $st): ?>
                          <tr strequest="<?php echo $st->sgc_id; ?>" streqstat = "<?php echo $st->sgc_status; ?>" class="<?php echo $st->sgc_status==2?'closed':'notclosed'; ?>">
                            <td><?php echo $st->sgc_num; ?></td>
                            <td><?php echo $st->store_name; ?></td>
                            <td><?php echo _dateFormat($st->sgc_date_needed); ?></td>
                            <td><?php echo ucwords($st->firstname.' '.$st->lastname); ?></td>
                            <td><?php echo _dateFormat($st->sgc_date_request); ?></td>
                            <td><?php 
                                    if($st->sgc_status=='1')
                                      echo 'Partial';
                                    elseif ($st->sgc_status=='2') 
                                      echo 'Closed';
                                ?>
                            </td>                            
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                  </table>
              </div>
          </div>          
        </div>   
      </div><!-- end row -->      
    </div><!-- end fluid div -->

<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/main.js"></script>
<?php include 'footer.php' ?>