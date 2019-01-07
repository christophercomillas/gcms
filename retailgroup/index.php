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

                <div class="box box-bot">
                    <div class="box-header"><h4><i class="fa fa-inbox"></i> Promo GC Request</h4></div>
                    <div class="box-content budbox">
                        <?php 
                            // get user group
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
                            $budAppReq = promoRequestCount($link,'approved'); 
                            echo $budAppReq>0 ?'<a href="#/promo-request-approved/">':''; 
                        ?>

                            <div class="slate-colorbox blue blue-x bot">
                                <i class="fa fa-check-square-o fa-pad"></i>
                                <div class="slate-colorbox-label">Approved Request</div>
                                <span class="badge badge-count blue-b"><?php echo $budAppReq; ?></span>
                            </div>

                        <?php echo $budAppReq>0 ?'</a>':''; ?>

                        <?php 
                            $budAppReq = checkRequest($link,'promo_gc_request','pgcreq_status','cancel'); 
                            echo $budAppReq>0 ?'<a href="promo-gc-request-list-cancelled.php">':'';
                        ?>
                            <div class="slate-colorbox gray gray-x">
                                <i class="fa fa-times fa-pad"></i>
                                <div class="slate-colorbox-label">Cancelled Request</div>
                                <span class="badge badge-count black-b"><?php echo $budAppReq; ?></span>  
                            </div>
                        <?php echo $budAppReq>0 ?'</a>':''; ?>
                    </div>                
                </div>

            </div>

            <div class="col-sm-4">
                <div class="box">
                    <div class="box-header"><h4><i class="fa fa-inbox"></i> Promo GC Approved (Total Amount)</h4></div>
                    <div class="box-content">
                        <?php 
                            $promotag = getField($link,'usergroup','users','user_id',$_SESSION['gc_id']); 



                        ?>
                        <h3 class="current-budget mbot">&#8369 
                            <?php  
                                $query_bud = $link->query(
                                    "SELECT 
                                        IFNULL(SUM(promo_ledger.promled_debit - promo_ledger.promled_credit),0.00) as sum
                                    FROM 
                                        promo_ledger 
                                    INNER JOIN
                                        promo_gc_request
                                    ON
                                        promo_gc_request.pgcreq_id = promo_ledger.promled_trid
                                    WHERE 
                                        promo_gc_request.pgcreq_group = '".$promotag."' 
                                ");

                                if($query_bud)
                                {
                                    $bud = $query_bud->fetch_object();
                                    echo number_format($bud->sum,2);
                                }
                                else 
                                {
                                    echo $link->error;
                                }

                            ?>
                        </h3>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php include 'jscripts.php'; ?>
<?php include 'footer.php' ?>