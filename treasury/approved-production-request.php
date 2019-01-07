<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  $approved = approvedProductionRequest($link);

?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
    <div class="row">
      <div class="col-sm-12">
        <div class="box box-bot">
          <div class="box-header"><h4><i class="fa fa-inbox"></i> Approved Production Request</div>
          <div class="box-content">
            <?php if(is_array($approved)): ?>
              <table class="table table-adjust" id="appprodreq">
                <thead>
                  <tr>
                    <th>PR No.</th>
                    <th>Date Request</th>         
                    <th>Date Needed</th>
                    <th>Requested By</th>
                    <th>Date Approved</th>
                    <th>Approved By</th>
                    <th></th>   
                  </tr>
                </thead>
                <tbody class="store-request-list">
                  <?php foreach ($approved as $key): ?>                
                      <tr>
                        <td><?= $key->pe_num; ?></td>
                        <td><?= _dateFormat($key->pe_date_request); ?></td>
                        <td><?= _dateFormat($key->pe_date_needed); ?></td>
                        <td><?= ucwords($key->firstname.' '.$key->lastname); ?></td>
                        <td><?= _dateFormat($key->ape_approved_at); ?></td>
                        <td><?= ucwords($key->ape_approved_by); ?></td>
                        <td><button type="button" onclick="approvedProductionRequest(<?php echo $key->pe_id; ?>);" class="btn btn-warning btn-warning-o app-pro"><span class="glyphicon glyphicon-search"></span> View</button>
                      </tr>
                  <?php endforeach; ?>                  
                </tbody>                
              </table>
            <?php else:?>   
              <?php echo var_dump($approved); ?>
            <?php endif; ?>
          </div>            
        </div>
    </div>
  </div>


<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/main.js"></script>
<?php include 'footer.php' ?>