<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  // $approved = approvedProductionRequest($link);
    $select = 'production_request.pe_id,
        production_request.pe_num,
        users.firstname,
        users.lastname,
        access_page.title,
        production_request.pe_date_request,
        production_request.pe_date_needed
    ';
    $where = 'production_request.pe_status=0';
    $join = 'INNER JOIN
              users
            ON
              users.user_id = production_request.pe_requested_by
            INNER JOIN
              access_page
            ON
              access_page.access_no = users.usertype';
    $limit = '';
    $gc = getAllData($link,'production_request',$select,$where,$join,$limit);
?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
    <div class="row">
      <div class="col-sm-12">
        <div class="box box-bot">
          <div class="box-header"><h4><i class="fa fa-inbox"></i> Pending Production Request</div>
          <div class="box-content">
            <?php if(is_array($gc)): ?>
              <table class="table table-adjust" id="storeRequestList">
                <thead>
                  <tr>
                    <th>PR No.</th>
                    <th>Date Request</th>  
                    <th>Total Amount</th>       
                    <th>Date Needed</th>
                    <th>Requested By</th>
                    <th>Department</th>
                  </tr>
                </thead>
                <tbody class="store-request-list">
                  <?php foreach ($gc as $key): ?>                
                      <tr onclick="pendingproduction(<?php echo $key->pe_id; ?>); ">
                          <td><?= $key->pe_num; ?></td>
                          <td><?= _dateFormat($key->pe_date_request); ?></td>
                          <td>
                            <?php
                              $select = 'SUM(denomination.denomination * production_request_items.pe_items_quantity) as total';
                              $where = 'production_request_items.pe_items_request_id='.$key->pe_id;
                              $join = 'INNER JOIN
                                        denomination
                                      ON
                                        denomination.denom_id = production_request_items.pe_items_denomination';
                              $tot = getSelectedData($link,'production_request_items',$select,$where,$join,'');

                              echo number_format($tot->total,2);
                            ?>
                          </td>   
                          <td><?= _dateFormat($key->pe_date_needed); ?></td>
                          <td><?= ucwords($key->firstname.' '.$key->lastname); ?></td>
                          <td><?= $key->title; ?></td>
                      </tr>
                  <?php endforeach; ?>                  
                </tbody>                
              </table>
            <?php else:?>   
              <?php echo var_dump($gc); ?>
            <?php endif; ?>
          </div>            
        </div>
    </div>
  </div>


<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/main.js"></script>
<?php include 'footer.php' ?>