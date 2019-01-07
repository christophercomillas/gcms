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
		</ol>
	</div>      

	<div class="row">
		<div class="col-sm-4">
			<div class="box box-bot">
            	<div class="box-header"><i class="fa fa-inbox"></i> Budget Request</div>
            	<div class="box-content">
            		<div class="slate-colorbox red bot">
            			<?php echo checkRequest($link,'budget_request','br_request_status','0') > 0 ? '<a href="pending_budget_request.php">' : ''; ?>
            				<i class="fa fa-exclamation-triangle"></i>
            				<div class="slate-colorbox-label">Pending Request</div>
            				<div class="label pull-right"><?php echo checkRequest($link,'budget_request','br_request_status','0'); ?></div>
            		  <?php echo checkRequest($link,'budget_request','br_request_status','0') > 0 ? '</a>' : ''; ?>
          			</div>
          			<div class="slate-colorbox blue">
          				<?php echo checkRequest($link,'budget_request','br_request_status','1') > 0 ? '<a href="approved-budget-request.php">' : ''; ?>
	            			<i class="fa fa-check-square-o"></i>
	            			<div class="slate-colorbox-label">Approved Request</div>
	           				<div class="label pull-right"><?php echo checkRequest($link,'budget_request','br_request_status','1'); ?></div>
           				<?php echo checkRequest($link,'budget_request','br_request_status','1') > 0 ? '</a>' : ''; ?>
          			</div>              	
				</div>
			</div>
			<div class="box">
            	<div class="box-header"><i class="fa fa-inbox"></i> Store GC Request</div>
            	<div class="box-content">
            		<div class="slate-colorbox red bot">
            			<?php echo checkRequest($link,'store_gcrequest','sgc_status','0') > 0 ? '<a href="pending_store_gc_request.php">' : ''; ?>
            				<i class="fa fa-exclamation-triangle"></i>
            				<div class="slate-colorbox-label">Pending Request</div>
            				<div class="label pull-right"><?php echo checkRequest($link,'store_gcrequest','sgc_status','0'); ?></div>
            			<?php echo checkRequest($link,'store_gcrequest','sgc_status','0') > 0 ? '</a>' : ''; ?>                                            
          			</div>
          			<div class="slate-colorbox blue">
          				<?php echo checkRequest($link,'store_gcrequest','sgc_status','1') > 0 ? '<a href="approved_gcrequest.php">' : ''; ?>
	            			<i class="fa fa-check-square-o"></i>
	            			<div class="slate-colorbox-label">Approved Request</div>
	           				<div class="label pull-right"><?php echo checkRequest($link,'store_gcrequest','sgc_status','1'); ?></div>
           				<?php echo checkRequest($link,'store_gcrequest','sgc_status','1') > 0 ? '</a>' : ''; ?>                                            
          			</div>              	
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="box">
            	<div class="box-header"><i class="fa fa-inbox"></i> GC Production Request</div>
            	<div class="box-content">
            		<div class="slate-colorbox red bot">
            			<?php echo checkRequest($link,'production_request','pe_status','0') > 0 ? '<a href="pending_production_request.php">' : ''; ?>
            				<i class="fa fa-exclamation-triangle"></i>
            				<div class="slate-colorbox-label">Pending Request</div>
            				<div class="label pull-right"><?php echo checkRequest($link,'production_request','pe_status','0') ?></div>
            			<?php echo checkRequest($link,'production_request','pe_status','0') > 0 ? '</a>' : ''; ?>
          			</div>
          			<div class="slate-colorbox blue">
          				<?php echo checkRequest($link,'production_request','pe_status','1') > 0 ? '<a href="approved_production_request.php">' : ''; ?>
	            			<i class="fa fa-check-square-o"></i>
	            			<div class="slate-colorbox-label">Approved Request</div>
	           				<div class="label pull-right"><?php echo checkRequest($link,'production_request','pe_status','1') ?></div>
           				<?php echo checkRequest($link,'production_request','pe_status','1') > 0 ? '</a>' : ''; ?>
          			</div>              					
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="box">
            	<div class="box-header"><i class="fa fa-inbox"></i> Current Budget</div>
            	<div class="box-content">
              	<h3 class="current-budget">&#8369 <?php echo number_format(currentBudget($link),2); ?></h3>
				</div>
			</div>
		</div>
  	</div>

    <div class="modal fade" id="logout-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Are you sure you want to log out?</h4>
          </div>
          <div class="modal-body">
            All your changes will be saved.
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">No Thanks</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal">Yes, Please</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
<?php require 'footer.php' ?>