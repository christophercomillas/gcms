<?php 
  session_start();
  include '../function.php';
  require 'header.php';

    $cancelled = getAllCancelledBudgetRequest($link);

  require '../menu.php'; ?>

  <div class="main fluid">
  	<div class="row">
  		<div class="col-sm-12">
  			<div class="box box-bot">
        	<div class="box-header"><h4><i class="fa fa-inbox"></i> Cancelled Budget Request</div>
        	<div class="box-content">
            <div class="row">
              <div class="col-sm-12">
                <?php if(is_array($cancelled)): ?>                          
                    <table class="table table-adjust" id="appbudgetreq">
                        <thead>
                            <tr>
                                <th>BR No.</th>
                                <th>Date Requested</th>         
                                <th>Budget Requested</th>
                                <th>Prepared By</th>
                                <th>Date Cancelled</th>
                                <th>Cancelled By</th>
                                <th></th>                
                            </tr>
                        </thead>
                        <tbody class="store-request-list">
                            <?php foreach ($cancelled as $key): ?> 
                            <tr>
                                <td><?php echo $key->br_no; ?></td>
                                <td><?php echo _dateFormat($key->br_requested_at); ?></td>
                                <td>&#8369 <?php echo number_format($key->br_request,2); ?></td>
                                <td><?php echo ucwords($key->fnamerequest.' '.$key->lnamerequest); ?></td>
                                <td><?php echo _dateFormat($key->cdreq_at); ?></td>
                                <td><?php echo ucwords($key->fnamecancelled.' '.$key->lnamecancelled); ?></td>
                                <td><button type="button" onclick="cancelledBudgetRequestDetails(<?php echo $key->br_id; ?>)" class="btn btn-warning btn-warning-o app-req-can"><span class="glyphicon glyphicon-search"></span> View</button></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <?php var_dump($cancelled); ?>
                <?php endif; ?>
              </div>
            </div>
        </div>
    </div>
  </div>


<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/main.js"></script>
<?php include 'footer.php' ?>