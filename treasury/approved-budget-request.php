<?php 
  session_start();
  include '../function.php';
  require 'header.php';

    //$gc = getAllApprovedBudgetRequest($link);

    $table = 'budget_request';
    $select = "budget_request.br_id,
        budget_request.br_request,
        budget_request.br_requested_at,
        budget_request.br_no,
        CONCAT(brequest.firstname,' ',brequest.lastname) as breq,
        approved_budget_request.abr_approved_by,
        approved_budget_request.abr_approved_at";
    $where = "br_request_status = '1'";
    $join = 'INNER JOIN
            users as brequest
        ON
            brequest.user_id = budget_request.br_requested_by
        LEFT JOIN
            approved_budget_request
        ON
            approved_budget_request.abr_budget_request_id  = budget_request.br_id';
    $limit = '';
    $data = getAllData($link,$table,$select,$where,$join,$limit);
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
                <?php if(is_array($data)): ?>      
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
                        <?php foreach ($data as $d): ?> 
                            <tr>
                                <td><?php echo $d->br_no; ?></td>
                                <td><?php echo _dateFormat($d->br_requested_at);?></td>
                                <td><?php echo number_format($d->br_request,2); ?></td>
                                <td><?php echo ucwords($d->breq); ?></td>
                                <td><?php echo _dateFormat($d->abr_approved_at); ?></td>
                                <td><?php echo ucwords($d->abr_approved_by); ?></td>
                                <td><button type="button" onclick="approvedBudgetRequestDetails(<?php echo $d->br_id; ?>)" class="btn btn-warning btn-warning-o app-req"><span class="glyphicon glyphicon-search"></span> View</button></td>
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