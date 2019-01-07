<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  $srr = getSRRForReport($link);

?>


<?php require '../menu.php'; ?>

  <div class="main fluid">
    <div class="row">
      <div class="col-sm-8">
          <div class="box box-bot">
            <div class="box-header"><h4><i class="fa fa-inbox"></i> Reports</h4></div>
              <div class="box-content form-container">
                <div class="row">
                  <div class="col-sm-12">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>Production No.</th>
                          <th>Date Received</th>
                          <th>Prepared by</th>
                          <th>Checked by</th>
                          <th>Report</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($srr as $key): ?>
                        <tr>
                          <td><?php echo $key->pe_num; ?></td>
                          <td><?php echo _dateFormat($key->csrr_datetime); ?></td>
                          <td><?php echo ucwords($key->firstname.' '.$key->lastname); ?></td>
                          <td><?php echo ucwords($key->csrr_checked_by); ?></td>
                          <td><i class="fa fa-file-text fa-2 gen_report" report_id="<?php echo $key->csrr_id; ?>"></i>
</td>
                        </tr>                          
                        <?php endforeach ?>
                      </tbody>                      
                    </table>
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