 <?php
    session_start();
    include '../function.php';
    require 'header.php';
    $denoms = getAllDenomination($link);
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
                <div class="box">
                    <div class="box-header"><h4><i class="fa fa-inbox"></i> Special External GC Request</h4></div>
                    <div class="box-content storeqbox">
                        <!-- 
                        <?php 
                            $segcpending  = numRows($link,'special_external_gcrequest','spexgc_status','pending');
                            echo $segcpending > 0 ? '<a href="#/special-external-request/">' : '';
                        ?>
                        <div class="slate-colorbox red red-x bot">
                            <i class="fa fa-exclamation-triangle fa-pad"></i>
                            <div class="slate-colorbox-label">Pending Request</div>
                            <span class="badge badge-count red-b"><?php echo $segcpending; ?></span>
                        </div>
                        <?php echo $segcpending > 0 ? '</a>' : ''; ?> -->

                        <?php 
                            $segcuspending  = getNumRowsStoreRequest($link,'special_external_gcrequest','spexgc_status','pending','spexgc_addemp','pending');
                            echo $segcuspending > 0 ? '<a href="#/special-external-gcholderentrylist/">' : '';
                        ?>
                        <div class="slate-colorbox red red-x bot">
                            <i class="fa fa-exclamation-triangle fa-pad"></i>
                            <div class="slate-colorbox-label">Pending (GC Holder Entry)</div>
                            <span class="badge badge-count red-b"><?php echo $segcuspending; ?></span>
                        </div>
                         <?php echo $segcuspending > 0 ? '</a>' : ''; ?>

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
                            $segcapproved  = numRows($link,'special_external_gcrequest','spexgc_released','released');
                            echo $segcapproved > 0 ? '<a href="#/released-special-external-request/">' : '';
                        ?>
                        <div class="slate-colorbox blue blue-x bot">
                            <i class="fa fa-check-square-o fa-pad"></i>
                            <div class="slate-colorbox-label">Released GC</div>
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
                <div class="box box-bot">
                    <div class="box-header"><h4><i class="fa fa-inbox"></i> Available GC for Allocation / Sales</h4></div>
                    <div class="box-content budbox">
                    <ul class="list-group bld">
                        <?php foreach ($denoms as $denom): ?>
                            <input type="hidden" id="nx<?php echo $denom->denom_id; ?>"  value="<?php echo countGCNotYetAllocatedandNotPromo($link,$denom->denom_id); ?>"/>                         
                            <li class="list-group-item"><span class="badge" id="n<?php echo $denom->denom_id; ?>"><?php echo countGCNotYetAllocatedandNotPromo($link,$denom->denom_id); ?></span> &#8369 <?php echo number_format($denom->denomination,2); ?></li>          
                        <?php endforeach ?>   
                    </ul> 
                    <button type="button" class="btn btn-info pull-right" id="view-allocated-gc" onclick="showGCforAllocation()">View GC</button>
                    </div>
                </div>
            </div>

        </div>


    </div>
<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/cus.js"></script>
<?php include 'footer.php' ?>