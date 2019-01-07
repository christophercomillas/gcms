<?php 
    session_start();
    include '../function.php';
    require 'header.php';
    require '../menu.php';

    $table = 'special_external_gcrequest';
    $select = "special_external_gcrequest.spexgc_num,
        special_external_gcrequest.spexgc_dateneed,
        special_external_gcrequest.spexgc_id,
        special_external_gcrequest.spexgc_datereq,
        CONCAT(users.firstname,' ',users.lastname) as prep,
        special_external_customer.spcus_companyname,
        special_external_gcrequest.spexgc_id,
        approved_request.reqap_approvedby";
    $where = "special_external_gcrequest.spexgc_status='approved'
        AND
            special_external_gcrequest.spexgc_reviewed='reviewed'
        AND
            approved_request.reqap_approvedtype='Special External GC Approved'
        AND
            special_external_gcrequest.spexgc_released=''";
    $join = 'INNER JOIN
            users
        ON
            users.user_id = special_external_gcrequest.spexgc_reqby
        INNER JOIN
            special_external_customer
        ON
            special_external_customer.spcus_id = special_external_gcrequest.spexgc_company
        INNER JOIN
            approved_request
        ON
            approved_request.reqap_trid = special_external_gcrequest.spexgc_id';
    $limit = 'ORDER BY special_external_gcrequest.spexgc_id ASC';

    $request = getAllData($link,$table,$select,$where,$join,$limit);
    
?>

<div class="main fluid">    
    <div class="row form-container">
        <div class="col-md-12">
            <div class="panel with-nav-tabs panel-info">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Reviewed Special External GC</a></li>
                        <!-- <li><a href="#tab2default" data-toggle="tab">Default 2</a></li> -->
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1default">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table" id="storeRequestList">
                                        <thead>
                                            <tr>
                                                <th>RFSEGC #</th>
                                                <th>Date Requested</th>
                                                <th>Date Needed</th>
                                                <th>Total Denom</th>
                                                <th>Customer</th>
                                                <th>Requested by</th>
                                                <th>Approved By</th>
                                                <th>Reviewed By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($request as $r): ?>
                                                <tr onclick="pendingSpecialExternalGCforRelease(<?php echo $r->spexgc_id; ?>);">
                                                    <td><?php echo $r->spexgc_num; ?></td>
                                                    <td><?php echo _dateFormat($r->spexgc_datereq); ?></td>
                                                    <td><?php echo _dateFormat($r->spexgc_dateneed); ?></td>
                                                    <td><?php echo number_format(totalExternalRequest($link,$r->spexgc_id)[0],2); ?></td>
                                                    <td><?php echo ucwords($r->spcus_companyname); ?></td>
                                                    <td><?php echo ucwords($r->prep); ?></td>
                                                    <td><?php echo ucwords($r->reqap_approvedby); ?></td>
                                                    <td>
                                                        <?php 
                                                            $table = 'approved_request';
                                                            $select ="CONCAT(users.firstname,' ',users.lastname) as reviewee";
                                                            $where = '';
                                                            $join = 'INNER JOIN
                                                                    users
                                                                ON
                                                                    users.user_id = approved_request.reqap_preparedby';
                                                            $limit = "approved_request.reqap_trid='".$r->spexgc_id."'
                                                                AND
                                                                    approved_request.reqap_approvedtype='special external gc review'";
                                                            $gc = getSelectedData($link,$table,$select,$where,$join,$limit);
                                                            echo ucwords($gc->reviewee);
                                                        ?>
                                                    </td>
                                                </tr>                                                
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="tab-pane fade" id="tab2default">Default 2</div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/store.js"></script>
<?php include 'footer.php' ?>