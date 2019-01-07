<?php 
	session_start();
	include '../function.php';
	require 'header.php';
	require '../menu.php';

    $table = 'special_external_gcrequest';
    $select = " special_external_gcrequest.spexgc_num,
        special_external_gcrequest.spexgc_dateneed,
        special_external_gcrequest.spexgc_id,
        special_external_gcrequest.spexgc_datereq,
        CONCAT(users.firstname,' ',users.lastname) as prep,
        special_external_customer.spcus_companyname";
    $where = "special_external_gcrequest.spexgc_status='pending' AND spexgc_addemp='done'";
    $join = 'INNER JOIN
            users
        ON
            users.user_id = special_external_gcrequest.spexgc_reqby
        INNER JOIN
            special_external_customer
        ON
            special_external_customer.spcus_id = special_external_gcrequest.spexgc_company';
    $limit = 'ORDER BY special_external_gcrequest.spexgc_id ASC';

    $request = getAllData($link,$table,$select,$where,$join,$limit);
    
?>

<div class="main fluid">    
	<div class="row form-container">
    	<div class="col-md-12">
            <div class="panel with-nav-tabs panel-info">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Pending Special External GC Request</a></li>
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
                                                <th>Total Denomination</th>
                                                <th>Customer</th>
                                                <th>Requested by</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($request as $r): ?>
                                                <tr onclick="pendingSpecialExternalGC(<?php echo $r->spexgc_id; ?>);">
                                                    <td><?php echo $r->spexgc_num; ?></td>
                                                    <td><?php echo _dateFormat($r->spexgc_datereq); ?></td>
                                                    <td><?php echo _dateFormat($r->spexgc_dateneed); ?></td>
                                                    <td><?php echo number_format(totalExternalRequest($link,$r->spexgc_id)[0],2); ?></td>
                                                    <td><?php echo ucwords($r->spcus_companyname); ?></td>
                                                    <td><?php echo ucwords($r->prep); ?></td>
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
<script type="text/javascript" src="../assets/js/fin.js"></script>
<?php include 'footer.php' ?>