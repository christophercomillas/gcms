<?php 
  session_start();
  include '../function.php';
  require 'header.php';

    // $gc = getAllApprovedBudgetRequest($link)
    $select = 'budget_request.br_id,
        budget_request.br_request,
        budget_request.br_no,
        budget_request.br_requested_at,
        budget_request.br_requested_needed,
        budget_request.br_file_docno,
        budget_request.br_remarks,
        budget_request.br_type,
        users.firstname,
        users.lastname
    ';
    $where = 'budget_request.br_request_status=0';
    $join = 'INNER JOIN
            users
        ON
            users.user_id = budget_request.br_requested_by
        INNER JOIN
            access_page
        ON
            access_page.access_no = users.usertype';
    $limit = '';
    $gc = getAllData($link,'budget_request',$select,$where,$join,$limit);
?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-bot">
            <div class="box-header"><h4><i class="fa fa-inbox"></i> Pending Budget Request</div>
            <div class="box-content">
            <div class="row">
              <div class="col-sm-12">          
                <?php if(is_array($gc)): ?>      
                    <table class="table table-adjust" id="storeRequestList">
                        <thead>
                            <tr>
                                <th>BR No.</th>
                                <th>Date Requested</th>         
                                <th>Budget Requested</th>
                                <th>Date Needed</th>
                                <th>Prepared By</th>         
                            </tr>
                        </thead>
                        <tbody class="store-request-list">
                        <?php foreach ($gc as $g): ?> 
                            <tr onclick="pendingbudget(<?php echo $g->br_id; ?>)">
                                <td><?php echo $g->br_no; ?></td>
                                <td><?php echo _dateFormat($g->br_requested_at);?></td>
                                <td><?php echo number_format($g->br_request,2); ?></td>                               
                                <td><?php echo _dateFormat($g->br_requested_needed); ?></td>
                                <td><?php echo ucwords($g->firstname.' '.$g->lastname); ?></td>
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