<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  if(getFADIPConnectionStatus($link))
  {
      $fadnew = getField($link,'app_settingvalue','app_settings','app_tablename','fad_server_ip_received_new');
  }
  else 
  {
      $fadnew = $dir.getField($link,'app_settingvalue','app_settings','app_tablename','localhost_received_new');
  }

  $arr_l = [];

  $toreceived = 0;

  if(!file_exists($fadnew))
  {
    $toreceived = 0;
  }
  else 
  {
    if (is_dir($fadnew)) 
    {
        if ($dh = opendir($fadnew)) 
        {
            $files = array();

            while (($file = readdir($dh)) !== false) 
            {
                if (!is_dir($fadnew.'/'.$file)) 
                {
                  $allowedExts = array("txt");
                  $temp = explode(".", $file);
                  $extension = end($temp);
                  if(in_array($extension, $allowedExts))
                    $files[] = $file;
                }
            }
        }
    }

  }


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
              $segcapproved  = numRowsWhereTwo($link,'special_external_gcrequest','spexgc_id','spexgc_status','spexgc_reviewed','approved','');
              echo $segcapproved > 0 ? '<a href="approvedlist.php">':'';
            ?>
            <div class="slate-colorbox blue blue-x bot">
                <i class="fa fa-check-square-o fa-pad"></i>
                <div class="slate-colorbox-label">Approved GC For Review</div>
                <span class="badge badge-count blue-b"><?php echo $segcapproved; ?></span>
            </div>
            <?php echo $segcapproved > 0 ? '</a>':''?>

            <?php 
              $segcapproved  = numRowsWhereTwo($link,'special_external_gcrequest','spexgc_id','spexgc_status','spexgc_reviewed','approved','reviewed');
              echo $segcapproved > 0 ? '<a href="#/reviewed-special-external-request/">':'';
            ?>
            <div class="slate-colorbox blue blue-x bot">
                <i class="fa fa-check-square-o fa-pad"></i>
                <div class="slate-colorbox-label">Reviewed GC</div>
                <span class="badge badge-count blue-b"><?php echo $segcapproved; ?></span>
            </div>
            <?php echo $segcapproved > 0 ? '</a>':''?>         
          </div>
        </div>
      </div>    
      <div class="col-sm-4">
        <div class="box">
          <div class="box-header"><h4><i class="fa fa-inbox"></i> Internal GC</h4></div>
          <div class="box-content storeqbox">
            <?php 
              echo count($files)> 0 ? '<a href="gcreceiving.php">':'';
            ?>
            <div class="slate-colorbox blue blue-x bot">
                <i class="fa fa-check-square-o fa-pad"></i>
                <div class="slate-colorbox-label">GC Receiving</div>
                <span class="badge badge-count blue-b"><?php echo count($files); ?></span>
            </div>
            <?php echo $segcapproved > 0 ? '</a>':''?>

            <?php 
              $cgc = custodianreceivedgc($link); 
              echo count($cgc) > 0 ? '<a href="gcreceived.php">':'';
            ?>
            <div class="slate-colorbox blue blue-x bot">
                <i class="fa fa-check-square-o fa-pad"></i>
                <div class="slate-colorbox-label">GC Received</div>
                <span class="badge badge-count blue-b"><?php echo count($cgc); ?></span>
            </div>
            <?php echo count($cgc) > 0 ? '</a>':''?>         
          </div>
        </div>
      </div>     
    </div>    
  </div>

<?php include 'jscripts.php'; ?>
<script src="../assets/js/cus.js"></script>
<?php include 'footer.php' ?>