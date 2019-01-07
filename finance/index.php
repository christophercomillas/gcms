<?php 
    session_start();
    include '../function.php';
    require 'header.php';
?>

<?php require '../menu.php'; ?>
    
    <div class="main fluid">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-home"></i></a></li>
                <li><a href="#">Dashboard</a></li>   
                <span class="fa fa-refresh pull-right _refreshpage" title="Reload Page"></span>              
            </ol>
        </div>  

        <div class="row">
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="box box-bot">
                            <div class="box-header"><h4><i class="fa fa-inbox"></i> Promo GC Request</h4></div>
                            <div class="box-content budbox">
                                <?php 
                                    $table = 'promo_gc_request';
                                    $select = "promo_gc_request.pgcreq_reqnum,
                                        promo_gc_request.pgcreq_datereq,
                                        promo_gc_request.pgcreq_id,
                                        promo_gc_request.pgcreq_dateneeded,
                                        promo_gc_request.pgcreq_total,
                                        CONCAT(users.firstname,' ',users.lastname) as user";
                                    $where = "promo_gc_request.pgcreq_group!=''
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

                                    $budAppReq = count(getAllData($link,$table,$select,$where,$join,$limit));
                                    echo $budAppReq>0 ?'<a href="promo-gc-request-list-pending.php">':''; 
                                ?>

                                    <div class="slate-colorbox red red-x bot">            
                                        <i class="fa fa-exclamation-triangle fa-pad"></i>
                                        <div class="slate-colorbox-label">Pending Request</div>
                                        <span class="badge badge-count red-b"><?php echo $budAppReq; ?></span>                     
                                    </div>
                                <?php echo $budAppReq>0 ? '</a>':''; ?>

                                <?php 
                                    $budAppReq = checkRequest($link,'promo_gc_request','pgcreq_status','approved'); 
                                ?>
                                <?php echo $budAppReq>0 ?'<a href="approved-budget-request.php">':''; ?>
                                    <div class="slate-colorbox blue blue-x bot">
                                        <i class="fa fa-check-square-o fa-pad"></i>
                                        <div class="slate-colorbox-label">Approved Request</div>
                                        <span class="badge badge-count blue-b"><?php echo $budAppReq; ?></span>
                                    </div>
                                <?php echo $budAppReq>0 ?'</a>':''; ?> 

                                <?php 
                                    $budAppReq = checkRequest($link,'promo_gc_request','pgcreq_status','cancel'); 
                                ?>
                                <?php echo $budAppReq>0 ?'<a href="promo-gc-request-list-cancelled.php">':''; ?>
                                    <div class="slate-colorbox gray gray-x">
                                        <i class="fa fa-times fa-pad"></i>
                                        <div class="slate-colorbox-label">Cancelled Request</div>
                                        <span class="badge badge-count black-b"><?php echo $budAppReq; ?></span>  
                                    </div>
                                <?php echo $budAppReq>0 ?'</a>':''; ?> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="box box-bot">
                            <div class="box-header"><h4><i class="fa fa-inbox"></i> Budget Request</h4></div>
                            <div class="box-content budbox">
                                <?php 
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
                                    $budcnt = count(getAllData($link,'budget_request',$select,$where,$join,$limit));
                                    echo $budcnt>0 ?'<a href="pending_gcrequest.php">':''; 
                                ?>

                                    <div class="slate-colorbox red red-x bot">            
                                        <i class="fa fa-exclamation-triangle fa-pad"></i>
                                        <div class="slate-colorbox-label">Pending Request</div>
                                        <span class="badge badge-count red-b"><?php echo $budcnt; ?></span>                     
                                    </div>
                                <?php echo $budcnt>0 ? '</a>':''; ?>

                                <?php 
                                    $budAppReq = checkRequest($link,'budget_request','br_request_status','1'); 
                                ?>
                                <?php echo $budAppReq>0 ?'<a href="approved-budget-request.php">':''; ?>
                                    <div class="slate-colorbox blue blue-x bot">
                                        <i class="fa fa-check-square-o fa-pad"></i>
                                        <div class="slate-colorbox-label">Approved Request</div>
                                        <span class="badge badge-count blue-b"><?php echo $budAppReq; ?></span>
                                    </div>
                                <?php echo $budAppReq>0 ?'</a>':''; ?>  

                                <?php 
                                    $budAppReq = checkRequest($link,'promo_gc_request','pgcreq_status','cancel'); 
                                ?>
                                <?php echo $budAppReq>0 ?'<a href="promo-gc-request-list-cancelled.php">':''; ?>
                                    <div class="slate-colorbox gray gray-x">
                                        <i class="fa fa-times fa-pad"></i>
                                        <div class="slate-colorbox-label">Cancelled Request</div>
                                        <span class="badge badge-count black-b"><?php echo $budAppReq; ?></span>  
                                    </div>
                                <?php echo $budAppReq>0 ?'</a>':''; ?> 
                            </div>
                        </div>                    
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="box">
                    <div class="box-header"><h4><i class="fa fa-inbox"></i> Special External GC Request</h4></div>
                    <div class="box-content storeqbox">
                        <?php 

                        $segcpending = getNumRowsStoreRequest($link,'special_external_gcrequest','spexgc_status','pending','spexgc_addemp','done');
                            echo $segcpending > 0 ? '<a href="special-external-request-pending.php">' : '';
                        ?>
                            <div class="slate-colorbox red red-x bot">
                                <i class="fa fa-exclamation-triangle fa-pad"></i>
                                <div class="slate-colorbox-label">Pending Request</div>
                                <span class="badge badge-count red-b"><?php echo $segcpending; ?></span>
                            </div>
                        <?php echo $segcpending > 0 ? '</a>' : ''; ?>

                        <?php 
                            $segcapproved  = numRows($link,'special_external_gcrequest','spexgc_status','approved');
                            echo $segcapproved > 0 ? '<a href="#/special-external-request-approved/">':'';
                        ?>
                            <div class="slate-colorbox blue blue-x bot">
                                <i class="fa fa-check-square-o fa-pad"></i>
                                <div class="slate-colorbox-label">Approved GC</div>
                                <span class="badge badge-count blue-b"><?php echo $segcapproved; ?></span>
                            </div>
                        <?php echo $segcapproved > 0 ? '</a>':''?>

                        <?php 
                            $segccancelled  = numRows($link,'special_external_gcrequest','spexgc_status','cancelled');
                            echo $segccancelled > 0 ? '<a href="cancelled-gc-request.php">':''; 
                        ?>
                            <div class="slate-colorbox gray gray-x">
                                <i class="fa fa-times fa-pad"></i>
                                <div class="slate-colorbox-label">Cancelled Request</div>
                                <span class="badge badge-count black-b"><?php echo $segccancelled; ?></span>
                            </div>    
                        <?php echo $segccancelled > 0 ? '</a>':''; ?>   
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="box bot-margin">
                    <div class="box-header"><h4><i class="fa fa-inbox"></i> Current Budget</h4></div>
                    <div class="box-content">
                        <h3 class="current-budget">&#8369 <?php echo number_format(currentBudget($link),2); ?></h3>
                    </div>
                </div>
            </div>
        </div>

    </div>



<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/fin.js"></script>
<?php include 'footer.php' ?>