<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  // $cancelled = getAllCancelledGCRequest($link,'production_request','cancelled_production_request','production_request.pe_id','cancelled_production_request.cpr_pro_id','users','cancelled_production_request.cpr_by','users.user_id','production_request.pe_status',2);
  $gcan = getAllCancelledProductionRequest($link);

  require '../menu.php'; ?>

  <div class="main fluid">
  	<div class="row">
  		<div class="col-sm-12">
  			<div class="box box-bot">
        	<div class="box-header"><h4><i class="fa fa-inbox"></i> Cancelled Production Request</div>
        	<div class="box-content">
            <div class="row">
              <div class="col-sm-12">          
                  <?php if(is_array($gcan)): ?>
                    <table class="table table-adjust" id="appbudgetreq">
                        <thead>
                            <tr>
                                <th>PR No.</th>
                                <th>Date Requested</th>
                                <th>Date Needed</th>                                         
                                <th>Prepared By</th>
                                <th>Date Cancelled</th>
                                <th>Cancelled By</th>
                                <th></th>                
                            </tr>
                        </thead>
                        <tbody class="store-request-list">
                          <?php foreach ($gcan as $key): ?>             
                            <tr>
                              <td><?php echo $key->pe_num; ?></td>
                              <td><?php echo _dateFormat($key->pe_date_request); ?></td>
                              <td><?php echo _dateFormat($key->pe_date_needed); ?></td>
                              <td><?php echo ucwords($key->lreqfname.' '.$key->lreqlname); ?></td>
                              <td><?php echo _dateFormat($key->cpr_at); ?></td>
                              <td><?php echo ucwords($key->lcanfname.' '.$key->lcanlname); ?></td>
                              <td><button type="button" onclick="viewCancelledProductionRequest(<?php echo $key->pe_id; ?>)" class="btn btn-warning btn-warning-o app-pro-can"><span class="glyphicon glyphicon-search"></span> View</button></td>
                            </tr>
                          <?php endforeach ?>
                        </tbody>
                    </table>
                  <?php else: ?>
                    <?php var_dump($gcan)?>
                  <?php endif; ?>
              </div>
            </div>
        </div>
    </div>
  </div>


<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/main.js"></script>
<?php include 'footer.php' ?>