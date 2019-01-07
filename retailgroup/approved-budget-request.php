<?php 
  session_start();
  include '../function.php';
  require 'header.php';

    $gc = getAllApprovedBudgetRequest($link);

?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-bot">
            <div class="box-header"><h4><i class="fa fa-inbox"></i> Approved Budget Request</div>
            <div class="box-content">
            <div class="row">
              <div class="col-sm-12">          
                <?php if(is_array($gc)): ?>      
                    <table class="table table-adjust" id="appbudgetreq">
                        <thead>
                            <tr>
                                <th>BR No.</th>
                                <th>Date Requested</th>         
                                <th>Budget Requested</th>
                                <th>Prepared By</th>
                                <th>Date Approved</th>
                                <th>Approved By</th>
                                <th></th>                
                            </tr>
                        </thead>
                        <tbody class="store-request-list">
                        <?php foreach ($gc as $g): ?> 
                            <tr>
                                <td><?php echo $g->br_no; ?></td>
                                <td><?php echo _dateFormat($g->br_requested_at);?></td>
                                <td><?php echo number_format($g->br_request,2); ?></td>
                                <td><?php echo ucwords($g->fnamerequest.' '.$g->lnamerequest); ?></td>
                                <td><?php echo _dateFormat($g->abr_approved_at); ?></td>
                                <td><?php echo ucwords($g->abr_approved_by); ?></td>
                                <td><button type="button" onclick="approvedBudgetRequestDetails(<?php echo $g->br_id; ?>)" class="btn btn-warning btn-warning-o app-req"><span class="glyphicon glyphicon-search"></span> View</button></td>
                            </tr>                        
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <?php var_dump($gc); ?>
                <?php endif; ?>
              </div>
            </div>
        </div>
    </div>
  </div>


<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/main.js"></script>
<?php include 'footer.php' ?>