<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  $gcRequest= getApprovedBudgetRequest($link);
?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
  	<div class="row">
  		<div class="col-sm-12">
  			<div class="box box-bot">
        	<div class="box-header"><h4><i class="fa fa-inbox"></i> Approved GC Request</div>
        	<div class="box-content">
            <div class="row">
              <div class="col-sm-12">          
                <?php if(count($gcRequest) > 0): ?>
                    <table class="table table-adjust" id="">
                        <thead>
                            <tr>
                                <th>GCR No.</th>
                                <th>GCR Date Requested</th>         
                                <th>Retail Store</th>
                                <th>GCR Prepared By</th>
                                <th>GCR Date Approved</th>
                                <th>GCR Approved By</th>
                                <th></th>                
                            </tr>
                        </thead>
                        <tbody class="store-request-list">
                            <?php foreach ($gcRequest as $key): ?>
                                <tr>
                                    <td><?php echo $key['sgc_num'];?></td>
                                    <td><?php echo _dateFormat($key['sgc_date_request']);?></td>
                                    <td><?php echo $key['store_name'];?></td>
                                    <td><?php echo ucwords($key['sgc_requested_by']);?></td>
                                    <td><?php echo _dateFormat($key['agcr_approved_at']);?></td>
                                    <td><?php echo $key['agcr_approvedby'];?></td>
                                    <td><button app-id="<?php echo $key['sgc_id']; ?>" class="btn btn-warning btn-warning-o app-gcreq">View</button></td>                                   
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>                    
                <?php else: ?>
                    <div class="alert alert-info"> Threre is no approved gc request yet.</div>
                <?php endif; ?>
              </div>
            </div>
        </div>
    </div>
  </div>


<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/main.js"></script>
<?php include 'footer.php' ?>