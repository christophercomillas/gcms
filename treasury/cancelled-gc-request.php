<?php 
  session_start();
  include '../function.php';
  require 'header.php';

    $cancelled = getAllCancelledGCRequestStore($link);

  require '../menu.php'; ?>

  <div class="main fluid">
  	<div class="row">
  		<div class="col-sm-12">
  			<div class="box box-bot">
        	<div class="box-header"><h4><i class="fa fa-inbox"></i> Cancelled GC Request</div>
        	<div class="box-content">
            <div class="row">
              <div class="col-sm-12">
                <?php if(count($cancelled) > 0): ?>     
                <table class="table table-adjust" id="cancelledgcreq">
                    <thead>
                        <tr>
                            <th>Req No.</th>
                            <th>Date Requested</th>         
                            <th>Retail Store</th>
                            <th>Prepared By</th>
                            <th>Date Cancelled</th>
                            <th>Cancelled By</th>
                            <th></th>                
                        </tr>
                    </thead>
                    <tbody class="store-request-list">
                      <?php foreach ($cancelled as $key): ?>
                        <tr>
                          <td><?php echo $key->sgc_num; ?></td>
                          <td><?php echo _dateFormat($key->sgc_date_request); ?></td>
                          <td><?php echo ucwords($key->store_name); ?></td>
                          <td><?php echo ucwords($key->firstname.' '.$key->lastname); ?></td>
                          <td><?php echo _dateFormat($key->csgr_at); ?></td>
                          <?php $user = getUserFirstnameAndLastnameById($link,$key->csgr_by); ?>
                          <td><?php echo $user; ?></td>
                          <td><button app-id="<?php echo $key->sgc_id; ?>" class="btn btn-warning btn-warning-o app-gc-can">View</button></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <div class="alert alert-info"> Threre is no cancelled gc request yet.</div>
                <?php endif; ?>
              </div>
            </div>
        </div>
    </div>
  </div>


<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/main.js"></script>
<?php include 'footer.php' ?>