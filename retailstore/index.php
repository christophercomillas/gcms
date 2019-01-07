<?php 
  ini_set('session.gc_maxlifetime',24*60*60);
  session_start();
  include '../function.php';
  require 'header.php';

  $denom = getAllDenomination($link);

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
        <div class="box-header"><h4><i class="fa fa-inbox"></i> Store GC Request</h4></div>
        <div class="box-content storereqbox">
          <?php 
            $penReq = count(getPendingGCRequestStore($link,$storeid));
          ?>
          <?php echo $penReq > 0 ? '<a href="pending-store-gcrequest.php">':''; ?>
          <div class="slate-colorbox red red-x bot">
              <i class="fa fa-exclamation-triangle fa-pad"></i>
              <div class="slate-colorbox-label">Pending GC Request</div>
              <span class="badge badge-count red-b"><?php echo $penReq; ?></span>
          </div>
           <?php echo $penReq > 0 ? '</a>':''; ?>

          <?php 
            $appReq = gcStoreGCReleasedNumRows($link,$storeid);
          ?>
          <?php echo $appReq > 0 ? '<a href="approved-gc-request.php">':''; ?>
            <div class="slate-colorbox blue blue-x bot">
              <i class="fa fa-check-square-o fa-pad"></i>
              <div class="slate-colorbox-label">Released GC</div>
              <span class="badge badge-count blue-b"><?php echo $appReq; ?></span>
            </div>   
           <?php echo $appReq > 0 ? '</a>':''; ?>

          <?php 
            $canReq = countStoreGCRequestCancelled($link,$storeid);
          ?>
          <?php echo $canReq > 0 ? '<a href="cancelled-gc-request.php">':''; ?>
          <div class="slate-colorbox gray gray-x">            
              <i class="fa fa-times fa-pad"></i>
              <div class="slate-colorbox-label">Cancelled Request</div>
              <span class="badge badge-count black-b"><?php echo $canReq; ?></span>        
          </div>
          <?php echo $canReq > 0 ? '</a>':''; ?>
        </div>
      </div>      
    </div>
    <div class="col-sm-4">
      <div class="box box-bot">
        <div class="box-header"><h4><i class="fa fa-list-alt"></i> Available GC</h4></div>
        <div class="box-content">
          <ul class="list-group storeavailgcstatus">
            <?php foreach ($denom as $key): ?>
              <li class="list-group-item">
                <span class="badge badge-sold" did="<?php echo $key->denom_id; ?>" dst="<?php echo $storeid; ?>"> 
                  <?php echo getCurrentAvailableGCByStore($link,$storeid,$key->denom_id); ?>
                </span>
                &#8369 <?php echo number_format($key->denomination,2); ?></li>      
            <?php endforeach ?>
          </ul>
          <button class="btn btn-info pull-right" onclick="availableGC(<?php echo $storeid; ?>)"><i class="fa fa-search-plus"></i> View Available GC</button>   
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="box box-bot">
        <div class="box-header"><h4><i class="fa fa-inbox"></i> Sold GC</h4></div>
        <div class="box-content">
          <ul class="list-group storesoldgcstatus">
            <?php foreach ($denom as $key): ?>
              <li class="list-group-item">
                <span class="badge badge-sold" did="<?php echo $key->denom_id; ?>" dst="<?php echo $storeid; ?>"> 
                  <?php echo getSoldGCPerStore($link,$storeid,$key->denom_id); ?>
                </span>
                &#8369 <?php echo number_format($key->denomination,2); ?></li>      
            <?php endforeach ?>
          </ul>
          <a href="soldgclist.php" class="btn btn-info pull-right"><i class="fa fa-search-plus"></i> View Sold GC</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/store.js"></script>
<?php include 'footer.php' ?>