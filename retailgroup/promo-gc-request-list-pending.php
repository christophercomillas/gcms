<?php 
	session_start();
	include '../function.php';
	require 'header.php';
	require '../menu.php';

    $group = getField($link,'usergroup','users','user_id',$_SESSION['gc_id']);
    
    $table = 'promo_gc_request';
    $select = "promo_gc_request.pgcreq_reqnum,
        promo_gc_request.pgcreq_datereq,
        promo_gc_request.pgcreq_id,
        promo_gc_request.pgcreq_dateneeded,
        promo_gc_request.pgcreq_total,
        CONCAT(users.firstname,' ',users.lastname) as user";
    $where = "promo_gc_request.pgcreq_group='".$group."'
        AND
            (promo_gc_request.pgcreq_group_status=''
        AND
            promo_gc_request.pgcreq_status='pending')
        OR 
            (promo_gc_request.pgcreq_group_status='approved'
        AND
            promo_gc_request.pgcreq_status='pending')
        ";
    $join = 'INNER JOIN
            users
        ON
            users.user_id = promo_gc_request.pgcreq_reqby';
    $limit = 'ORDER BY pgcreq_id ASC';

    $request = getAllData($link,$table,$select,$where,$join,$limit);
?>

<div class="main fluid">    
	<div class="row form-container">
    	<div class="col-md-12">
            <div class="panel with-nav-tabs panel-info">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Pending Promo GC Request</a></li>
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
                                                <th>RFPROM #</th>
                                                <th>Date Requested</th>
                                                <th>Date Needed</th>
                                                <th>Total GC</th>
                                                <th>Requested by</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($request as $key): ?>
                                                <tr onclick="pendingPromoGCRequest(<?php echo $key->pgcreq_id; ?>)">
                                                    <td><?php echo $key->pgcreq_reqnum; ?></td>
                                                    <td><?php echo _dateFormat($key->pgcreq_datereq); ?></td>
                                                    <td><?php echo _dateFormat($key->pgcreq_dateneeded); ?></td>
                                                    <td><?php echo number_format($key->pgcreq_total,2); ?></td>
                                                    <td><?php echo ucwords($key->user); ?></td>
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
<script type="text/javascript" src="../assets/js/marketing.js"></script>
<?php include 'footer.php' ?>