<?php 
  session_start();
  include '../function.php';
  require 'header.php';
  require '../menu.php';
  $cgc = custodianreceivedgc($link); 
?>

  <div class="main fluid">
  	<div class="row">
  		<div class="col-sm-12">
  			<div class="box box-bot">
        	<div class="box-header"><h4><i class="fa fa-inbox"></i> Received GC </div>
        	<div class="box-content">
            <div class="row">
              <div class="col-sm-12">
                  <table class="table table-adjust" id="cusRec">
                      <thead>
                          <tr>
                              <th>Receiving #</th>
                              <th>Date Received</th>         
                              <th>E-Requisition #</th>
                              <th>Supplier Name</th>
                              <th>Received By</th>
                              <th>Received Type</th>              
                          </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($cgc as $gc): ?>
                          <tr onclick="custodianreceivedgc(<?php echo $gc->csrr_id; ?>)">
                            <td><?php echo threedigits($gc->csrr_id) ?></td>
                            <td><?php echo _dateFormat($gc->csrr_datetime); ?></td>
                            <td><?php echo $gc->requis_erno; ?></td>
                            <td><?php echo ucwords($gc->gcs_companyname); ?></td>
                            <td><?php echo ucwords($gc->firstname.' '. $gc->lastname); ?></td>
                            <td><?php echo $gc->csrr_receivetype; ?></td>
                          </tr>                          
                        <?php endforeach ?>
                      </tbody>
                  </table>
              </div>
            </div>
        </div>
    </div>
  </div>

<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/cus.js"></script>
<?php include 'footer.php' ?>