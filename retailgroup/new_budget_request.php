<?php 
	session_start();
	include '../function.php';
	require 'header.php';
	//get id 

	//
	$select = 'budget_request.br_request,
		budget_request.br_no,
		budget_request.br_requested_by,
		users.firstname,
		users.lastname,
		budget_request.br_remarks,
		budget_request.br_file_docno,
		budget_request.br_id,
		budget_request.br_requested_at,
		budget_request.br_requested_needed,
		access_page.title,
		budget_request.br_type,
		budget_request.br_group,
		budget_request.br_preapprovedby';
	$where = 'budget_request.br_request_status=0
			AND
		budget_request.br_group=1';
	$join = 'INNER JOIN
			users
		ON
			users.user_id = budget_request.br_requested_by
		INNER JOIN
			access_page
		ON
			access_page.access_no = users.usertype';
	$limit = 'ORDER BY
		budget_request.br_id
		LIMIT 1';
	$request = getSelectedData($link,'budget_request',$select,$where,$join,$limit);
	if(!count($request) > 0)
	{
		exit();
	}
	// $request = getBudgetRequestForUpdate($link);

?>

<?php require '../menu.php'; ?>
  <div class="main fluid">
    <div class="row">
    <?php if(!is_null($request)): ?>
    	<div class="col-sm-6">
	      	<div class="box box-bot">
				<div class="box-header"><h4><i class="fa fa-inbox"></i> Budget Recommendation Approval</h4></div>
				<div class="box-content form-container">
					<?php if($request->br_preapprovedby==0): ?>
						<form action="../ajax.php?action=retailgbudgetreq" method="POST" id="retailgbudgetreq" class="form-horizontal">
							<input type="hidden" value="<?php echo $request->br_id; ?>" id="budgetid" name="budgetid">
							<input type="hidden" value="<?php echo $request->br_request; ?>" id="budgetrequested" name="budgetrequested">
							<input type="hidden" value="<?php echo $request->br_type; ?>" id="budgettype" name="budgettype">
							<input type="hidden" value="<?php echo $request->br_group; ?>" id="bgroup" name="bgroup">
							<div class="form-group">
								<label class="col-sm-5 control-label"><span class="requiredf">*</span>Request Status:</label>
								<div class="col-sm-6">
	                                <select class="form form-control input-sm inptxt" id="statusretail" name="status" required autofocus />
	                                    <option value="">-Select-</option>
	                                    <option value="1">Approved</option>
	                                    <option value="2">Cancel</option>
	                                </select>
								</div>						
							</div><!-- end form group -->
							<div class="form-group">
								<label class="col-sm-5 control-label newProdStatus">Date Approved/Cancel:</label>
								<div class="col-sm-6">
	                                <input type="text" class="form-control input-sm inptxt" value="<?php echo _dateFormat($todays_date); ?>" readonly="readonly">  
								</div>
							</div><!-- end form group -->
							<div class="hide-cancel">
								<div class="form-group">
									<label class="col-sm-5 control-label">Upload Document:</label>
									<div class="col-sm-6">
		                                <input type="file" id='upload' class="form-control input-sm" name="pic[]" accept="image/*" />	                                
									</div>
								</div><!-- end form group -->
								<div class="form-group">
									<label class="col-sm-5 control-label"><span class="requiredf">*</span>Remarks	:</label>
									<div class="col-sm-6">
		                                <textarea class="form form-control input-sm inptxt tarea" name="remark" id="remark" required></textarea>
									</div>
								</div><!-- end form group -->
							</div><!-- end hide-cancel -->
								<div class="form-group">
									<label class="col-sm-5 control-label label-prepared"></label>
									<div class="col-sm-6">
		                                <input type="text" readonly="readonly" class="form form-control input-sm inptxt" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>" />
									</div>
								</div><!-- end form group -->
							<div class="form-group">
								<div class="col-sm-offset-8 col-sm-4">
									<button type="submit" class="btn btn-block btn-primary"><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span> Submit</button>
								</div>
							</div><!-- end form group -->
							<div class="response">
							</div>
						</form>
					<?php else: ?>
						<?php 
							$query_app = $link->query(
								"SELECT 
									promogc_preapproved.prapp_doc,
									promogc_preapproved.prapp_at, 
									promogc_preapproved.prapp_remarks,
									users.firstname, 
									users.lastname 
								FROM 
									promogc_preapproved 
								INNER JOIN 
									users 
								ON 
									users.user_id = promogc_preapproved.prapp_by 
								WHERE 
									promogc_preapproved.prapp_reqid='$request->br_id'");
							if($query_app)
							{
								$row = $query_app->fetch_object();
							}
						?>
						<form id="retailgbudgetreq" class="form-horizontal">
							<div class="form-group">
								<label class="col-sm-5 control-label">Budget Status:</label>
								<div class="col-sm-6">
									<input type="text" class="form inptxt form-control" value="Approved" disabled>
									<div class="alert alert-warning alertrecommedation">
										<span class="requiredf">*</span> Budget already recommended and waiting for Finance Department approval.
									</div>					
								</div>										
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label">Date Approved:</label>
								<div class="col-sm-6">
									<input type="text" class="form inptxt form-control" value="<?php echo _dateFormat($row->prapp_at)?>" disabled>
								</div>										
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label">Time Approved:</label>
								<div class="col-sm-6">
									<input type="text" class="form inptxt form-control" value="<?php echo _timeFormat($row->prapp_at)?>" disabled>
								</div>										
							</div>
							<?php if($row->prapp_doc!=''): ?>
							<div class="form-group">
								<label class="col-sm-5 control-label">Recommendation Doc:</label>
								<div class="col-sm-6">
									<a class="btn btn-block btn-default" href='../assets/images/budgetRecommendation/download.php?file=<?php echo $row->prapp_doc; ?>'><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download</a>
								</div>										
							</div>
							<?php endif; ?>
							<div class="form-group">
								<label class="col-sm-5 control-label">Remarks:</label>
								<div class="col-sm-6">
									<textarea class="form inptxt form-control inptxt tarea" disabled><?php echo $row->prapp_remarks; ?></textarea>
								</div>										
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label">Approved By:</label>
								<div class="col-sm-6">
									<input type="text" class="form inptxt form-control" value="<?php echo ucwords($row->firstname.' '.$row->lastname);?>" disabled>
								</div>										
							</div>
						</form>
					<?php endif; ?>
				</div>               
	        </div>
    	</div><!-- end col-sm -->    
    	<div class="col-sm-6">
	      	<div class="box box-bot">
				<div class="box-header"><h4><i class="fa fa-inbox"></i> Promo GC Budget Request (Details)</h4></div>
				<div class="form-horizontal">
					<div class="box-content">
						<div class="form-group">
							<label class="col-sm-5 control-label">BR. No.</label>
							<div class="col-sm-6">
								<input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo $request->br_no; ?>">
							</div>
						</div><!-- end form group -->
						<div class="form-group">
							<label class="col-sm-5 control-label">Department:</label>
							<div class="col-sm-6">
								<input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo $request->title; ?>">
							</div>
						</div><!-- end form group -->
						<?php if($request->br_group !=0):?>
						<div class="form-group">
							<label class="col-sm-5 control-label">Promo Group:</label>
							<div class="col-sm-6">
								<input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo 'Group '.$request->br_group; ?>" readonly="readonly">
							</div>
						</div><!-- end form group -->
						<?php endif;?>
						<div class="form-group">
							<label class="col-sm-5 control-label">Date Requested:</label>
							<div class="col-sm-6">
								<input type="text" class="form-control input-sm input-display inptxt" readonly="readonly" value="<?php echo _dateFormat($request->br_requested_at); ?>">
							</div>
						</div><!-- end form group -->
						<div class="form-group">
							<label class="col-sm-5 control-label">Time Requested:</label>
							<div class="col-sm-6">
								<input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo _timeFormat($request->br_requested_at); ?>">
							</div>
						</div><!-- end form group -->
						<div class="form-group">
							<label class="col-sm-5 control-label">Date Needed:</label>
							<div class="col-sm-6">
								<input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo _dateFormat($request->br_requested_needed); ?>" readonly="readonly">
							</div>
						</div><!-- end form group -->					
						<div class="form-group">
							<label class="col-sm-5 control-label">Budget Requested:</label>
							<div class="col-sm-6">
								<input type="text" class="form-control input-sm inptxt" readonly="readonly" value="&#8369 <?php echo number_format($request->br_request,2); ?>" readonly="readonly">
							</div>
						</div><!-- end form group -->
						<?php if($request->br_file_docno !=''): ?>
						<div class="form-group">
							<label class="col-sm-5 control-label">Request Document:</label>
							<div class="col-sm-6">
								<a class="btn btn-block btn-default" href='../assets/images/budgetRequestScanCopy/download.php?file=<?php echo $request->br_file_docno; ?>'><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download</a>
							</div>
						</div><!-- end form group -->
						<?php endif; ?>
						<div class="form-group">
							<label class="col-sm-5 control-label">Remarks:</label>
							<div class="col-sm-6">
								<textarea class="form-control input-sm inptxt" readonly="readonly"><?php echo $request->br_remarks; ?></textarea>
							</div>
						</div><!-- end form group -->
						<div class="form-group">
							<label class="col-sm-5 control-label">Requested by:</label>
							<div class="col-sm-6">
								<input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo ucwords($request->firstname.' '.$request->lastname); ?>" readonly="readonly">
							</div>
						</div><!-- end form group -->
					</div>
				</div>               
	        </div>
    	</div><!-- end col-sm -->

   	<?php endif; ?>      
    </div><!-- end row -->
  </div><!-- end main fluid -->

<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/fin.js"></script>
<?php include 'footer.php' ?>