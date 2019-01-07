<?php 
  session_start();
  include '../function.php';
  require 'header.php';

    $select = 'allocation_adjustment.aadj_id,
        allocation_adjustment.aadj_datetime,
        allocation_adjustment.aadj_type,
        stores.store_name,
        gc_type.gctype,
        users.firstname,
        users.lastname,
        allocation_adjustment.aadj_remark';
    $join = 'INNER JOIN
            stores
        ON
            stores.store_id =  allocation_adjustment.aadj_loc
        INNER JOIN
            gc_type
        ON
            gc_type.gc_type_id = allocation_adjustment.aadj_gctype
        INNER JOIN
            users
        ON
            users.user_id = allocation_adjustment.aadj_by';
    $where = '1';
  $adj = getAllData($link,'allocation_adjustment',$select,$where,$join,'');

?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-bot">
            <div class="box-header"><h4><i class="fa fa-inbox"></i> Allocation Adjustment</div>
            <div class="box-content">
            <div class="row">
              <div class="col-sm-12">
                <table id="appprodreq" class="table table-adjust">
                    <thead>
                        <tr>
                            <th>Location Adjusted</th>
                            <th>GC Type</th>
                            <th>Adjustment Type</th>
                            <th>Date</th>         
                            <th>Remarks</th>
                            <th>Adjusted By</th>
                            <th></th>                  
                        </tr>
                    </thead>                    
                    <tbody class="store-request-list">
                        <?php foreach ($adj as $a): ?>
                            <tr>
                                <td><?php echo $a->store_name; ?></td>
                                <td><?php echo $a->gctype; ?></td>
                                <td>
                                    <?php
                                        if($a->aadj_type=='n')
                                            echo $adj = "Negative";
                                        else 
                                            echo $adj = "Positive";
                                    ?>
                                </td>
                                <td><?php echo _dateFormat($a->aadj_datetime); ?></td>
                                <td><?php echo $a->aadj_remark; ?></td>
                                <td><?php echo ucwords($a->firstname.' '.$a->lastname); ?></td>
                                <td><button type="button" onclick="allocationadjustment(<?php echo $a->aadj_id; ?>);" class="btn btn-warning btn-warning-o app-pro"><span class="glyphicon glyphicon-search"></span> View</button></td>
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
<script type="text/javascript" src="../assets/js/main.js"></script>
<?php include 'footer.php' ?>