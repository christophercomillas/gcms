<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  $gcRequest= getPendingGCRequestStore($link,$storeid);
?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
  	<div class="row">
  		<div class="col-sm-12">
  			<div class="box box-bot">
        	<div class="box-header"><h4><i class="fa fa-inbox"></i> Pending GC Request</div>
        	<div class="box-content">
            <div class="row">
              <div class="col-sm-12">          
                <?php if(count($gcRequest) > 0): ?>
                    <table class="table table-adjust" id="storePendingRequest">
                        <thead>
                            <tr>
                                <th>Request No.</th>
                                <th>Date Requested</th>         
                                <th>Retail Store</th>
                                <th>Requested By</th>
                                <th>Date Needed</th>                                
                                <th></th>              
                            </tr>
                        </thead>
                        <tbody class="store-request-list">
                            <?php foreach ($gcRequest as $key): ?>
                                <tr requestid='<?php echo $key->sgc_id; ?>' storeid="<?php echo $storeid; ?>">
                                  <td><?php echo $key->sgc_num; ?></td>
                                  <td><?php echo _dateFormat($key->sgc_date_request); ?></td>
                                  <td><?php echo $key->store_name; ?></td>
                                  <td><?php echo ucwords($key->firstname.' '.$key->lastname); ?></td>
                                  <td><?php echo _dateFormat($key->sgc_date_needed); ?></td>
                                  <td><?php echo $key->sgc_status=='0' ? '<button class="btn btn-left btn-view" onclick="viewStoreRequest('.$key->sgc_id.')"><i class="fa fa-share"></i>
 View</button><button class="btn btn-update btn-left" onclick="updateStoreRequest('.$key->sgc_id.','.$storeid.');"><i class="fa fa-pencil-square-o"></i>
Edit</button><button class="btn btn-cancel"><i class="fa fa-times"></i>
Cancel</button>' : '<button class="btn btn-left btn-view" onclick="viewRemainingGC('.$key->sgc_id.');"><i class="fa fa-share"></i>
 View</button>'; ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>                    
                <?php else: ?>
                    <div class="alert alert-info"> Threre is no pending GC Request yet.</div>
                <?php endif; ?>
              </div>
            </div>
        </div>
    </div>
  </div>

<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/store.js"></script>
<?php include 'footer.php' ?>