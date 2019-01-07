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
        <div class="box">
          <div class="box-header"><h4><i class="fa fa-inbox"></i> Special External GC Request</h4></div>
          <div class="box-content storeqbox">

            <?php 
              $segcpending  = numRows($link,'special_external_gcrequest','spexgc_status','pending');
              echo $segcpending > 0 ? '<a href="#/special-external-request/">' : '';
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
              $segcreviewed  = numRowsWhereTwo($link,'special_external_gcrequest','spexgc_id','spexgc_reviewed','spexgc_released','reviewed','');
              echo $segcreviewed > 0 ? '<a href="#/special-external-gc-reviewed">':'';
            ?>
            <div class="slate-colorbox blue blue-x bot">
                <i class="fa fa-check-square-o fa-pad"></i>
                <div class="slate-colorbox-label">Reviewed GC For Releasing</div>
                <span class="badge badge-count blue-b"><?php echo $segcreviewed; ?></span>
            </div>
            <?php echo $segcreviewed > 0 ? '</a>':''?>

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
    </div>   
  </div>

<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/store.js"></script>
<?php include 'footer.php' ?>