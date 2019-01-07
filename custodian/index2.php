 <?php 
  session_start();
  include '../function.php';
  require 'header.php';

  $received = getRequis($link);

?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
    <div class="row">
      <div class="col-sm-10">
          <div class="box box-bot">
            <div class="box-header"><h4><i class="fa fa-inbox"></i> GC Receiving </h4></div>
              <div class="box-content form-container">
                <div class="row">
                  <div class="col-sm-12">
                      <table class="table dataTable no-footer" id="gcrec">
                        <thead>
                          <tr>
                            <th>E-Requisition No.</th>
                            <th>Transaction Date</th>
                            <th>Production No.</th>
                            <th>Supplier Name</th>                            
                            <th>Receiving Stat</th> 
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($received as $key): ?>
                            <!-- <tr prid="<?php echo $key->pe_num; ?>" req-id="<?php echo $key->requis_id; ?>" ereqnum="<?php echo ltrim($key->requis_erno, '0'); ?>" req-stat="<?php echo $key->requis_status; ?>" <?php echo $key->requis_status == 2 ? 'class="closed" ' : 'class="notclosed"'; ?>> -->
                            <tr onclick="receivingEntry(<?php echo $key->pe_num; ?>,<?php echo $key->requis_id; ?>,<?php echo $key->requis_status; ?>);" <?php echo $key->requis_status == 2 ? 'class="closed" ' : 'class="notclosed"'; ?>>
                              <td><?php echo $key->requis_erno; ?></td>
                              <td><?php echo _dateFormat($key->pe_date_request); ?></td>
                              <td><?php echo $key->pe_num; ?></td>
                              <td><?php echo ucwords($key->gcs_companyname); ?></td>
                              <td>
                                <?php if($key->requis_status==1): ?>
                                  Partial
                                <?php elseif($key->requis_status==2): ?>
                                  Closed
                                <?php endif; ?>
                              </td>
                            </tr>
                          <?php endforeach ?>
                        </tbody>
                      </table>
                  </div>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
                </div>
              </div>
            </div>
          </div>
      </div> <!-- end of col -->
    </div>
  </div>

<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/cus.js"></script>
<?php include 'footer.php' ?>