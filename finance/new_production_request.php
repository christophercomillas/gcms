<?php 
	session_start();
	include '../function.php';
	require 'header.php';

	if(!isset($_GET['id']))
	{
		exit();
	}
	else 
	{
		$id = (int)$_GET['id'];
	}

// SELECT
// production_request.pe_id,
// users.firstname,
// users.lastname,
// production_request.pe_file_docno,
// production_request.pe_date_needed,
// production_request.pe_remarks,
// production_request.pe_num,
// production_request.pe_date_request
// FROM 
// production_request
// INNER JOIN
// users
// ON
// users.user_id = production_request.pe_requested_by
// WHERE 
// production_request.pe_status='0'
// AND
// production_request.pe_id='9'
// ORDER BY 
// pe_id
// DESC
// LIMIT 1

	$select = 'production_request.pe_id,
		users.firstname,
		users.lastname,
		production_request.pe_file_docno,
		production_request.pe_date_needed,
		production_request.pe_remarks,
		production_request.pe_num,
		production_request.pe_date_request,
		production_request.pe_type,
		production_request.pe_group,
		access_page.title';
	$where = 'production_request.pe_id='.$id.'
		AND
		production_request.pe_status=0';
	$join = 'INNER JOIN
		users
		ON
		users.user_id = production_request.pe_requested_by
		INNER JOIN
		access_page
		ON
		access_page.access_no = users.usertype';
	$limit = 'ORDER BY 
		production_request.pe_id
		DESC
		LIMIT 1';
	$pr = getSelectedData($link,'production_request',$select,$where,$join,$limit);
	if(!count($pr) > 0)
	{
		exit();
	}
	$ngc = getNumofGCRequestBYProdID($link,$pr->pe_id);
?>

<?php require '../menu.php'; ?>
  <div class="main fluid">
    <div class="row">
    	<div class="col-sm-5">
	      	<div class="box box-bot">
				<div class="box-header"><h4><i class="fa fa-inbox"></i> Production Request Approval Form</h4></div>
				<div class="box-content form-container">
					<form action="../ajax.php?action=productionStat" method="POST" id="prodRequestFin" class="form-horizontal">
						<input type="hidden" value="<?php echo $pr->pe_id; ?>" name="prodId" id="prodId">
						<input type="hidden" value="<?php echo $pr->pe_type; ?>" name="protype" id="protype">
						<input type="hidden" value="<?php echo $pr->pe_group; ?>" name="progroup" id="progroup">
						<div class="form-group">
							<label class="col-sm-5 control-label"><span class="requiredf">*</span>Request Status:</label>
							<div class="col-sm-7">
	                            <select id="status" class="form form-control input-sm reqfield inptxt" name="status" required autofocus >
	                                <option value="">-Select-</option>
	                                <option value="1">Approved</option>
	                                <option value="2">Cancel</option>
	                            </select>  
							</div>
						</div><!-- end form group -->
						
						<div class="form-group">
							<label class="col-sm-5 control-label newProdStatus">Date Appr./Cancel:</label>
							<div class="col-sm-7">
								<input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo _dateFormat($todays_date); ?>">
							</div>
						</div><!-- end form group -->
						<div class="hide-cancel">
							<div class="form-group">
								<label class="col-sm-5 control-label">Upload Document:</label>
								<div class="col-sm-7">
									<input type="file" id="upload" class="form-control input-sm" name="pic[]" accept="image/*" />
								</div>
							</div><!-- end form group -->	
							<div class="form-group">
								<label class="col-sm-5 control-label"><span class="requiredf">*</span>Remarks:</label>
								<div class="col-sm-7">									
									<textarea class="form form-control input-sm inptxt" name="remark" id="remark" required></textarea> 
								</div>
							</div><!-- end form group -->
							<div class="form-group">
								<label class="col-sm-5 control-label"><span class="requiredf">*</span>Checked by:</label>
								<div class="col-sm-7">
									<div class="input-group">
										<input name="checked" id="app-checkby" type="text" class="form-control input-sm reqfield inptxt" readonly="readonly" required="required">
	                                    <span class="input-group-btn">
	                                    	<button class="btn btn-info input-sm" id="checkbud" type="button" onclick="requestAssig(3,1);">
	                                    		<span class="glyphicon glyphicon-search"></span>
	                                        </button>
	                                    </span>
									</div><!-- input group -->
								</div>
							</div><!-- end form group -->	
							<div class="form-group">
								<label class="col-sm-5 control-label"><span class="requiredf">*</span>Approved by:</label>
								<div class="col-sm-7">
									<div class="input-group">
										<input name="approved" id="app-apprby" type="text" class="form-control input-sm reqfield inptxt" readonly="readonly" required="required">
	                                    <span class="input-group-btn">
	                                    	<button class="btn btn-info input-sm" id="approvedbud" type="button" onclick="requestAssig(3,2);">
	                                    		<span class="glyphicon glyphicon-search"></span>
	                                        </button>
	                                    </span>
									</div><!-- input group -->
								</div>
							</div><!-- end form group -->
						</div>
							<div class="form-group">
								<label class="col-sm-5 control-label label-prepared">Prepared by:</label>
								<div class="col-sm-7">
									<input typ="text" readonly="readonly" class="form form-control input-sm inptxt" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>" />
								</div>
							</div><!-- end form group -->
						<div class="form-group">
							<div class="col-sm-offset-8 col-sm-4">
								<button id="btn" class="btn btn-block btn-primary"><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span> Submit</button>
							</div>
						</div><!-- end form group -->			
					</form>
					<div class="response">
					</div>
				</div>
			</div>
		</div>
    	<div class="col-sm-7">
	      	<div class="box box-bot">
				<div class="box-header"><h4><i class="fa fa-inbox"></i> Production Request Details</h4></div>
				<div class="box-content form-container">
					<div class="row">
						<div class="col-sm-9">
							<div class="form-horizontal">
								<div class="form-group">
									<label class="col-sm-6 control-label">PE no.:</label>
									<div class="col-sm-6">
										<input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo $pr->pe_num; ?>">
									</div>
								</div><!-- end form group -->
								<div class="form-group">
									<label class="col-sm-6 control-label">Department:</label>
									<div class="col-sm-6">
										<input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo $pr->title; ?>">
									</div>
								</div><!-- end form group -->
								<?php if($pr->pe_group !=0):?>
								<div class="form-group">
									<label class="col-sm-6 control-label">Promo Group:</label>
									<div class="col-sm-6">
										<input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo 'Group '.$pr->pe_group; ?>" readonly="readonly">
									</div>
								</div><!-- end form group -->
								<?php endif;?>
								<div class="form-group">
									<label class="col-sm-6 control-label">Date Requested:</label>
									<div class="col-sm-6">
										<input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo _dateFormat($pr->pe_date_request); ?>">
									</div>
								</div><!-- end form group -->
								<div class="form-group">
									<label class="col-sm-6 control-label">Time Requested:</label>
									<div class="col-sm-6">
										<input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo _timeFormat($pr->pe_date_request); ?>">
									</div>
								</div><!-- end form group -->
								<div class="form-group">
									<label class="col-sm-6 control-label">Date Needed:</label>
									<div class="col-sm-6">
										<input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo _dateFormat($pr->pe_date_needed); ?>">
									</div>
								</div><!-- end form group -->
								<?php if(!empty($pr->pe_file_docno)): ?>
								<div class="form-group">
									<label class="col-sm-6 control-label">Request Document:</label>
									<div class="col-sm-6">
										<a class="btn btn-block btn-default" href='../assets/images/productionRequestFile/download.php?file=<?php echo $pr->pe_file_docno; ?>'><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download</a>
									</div>
								</div><!-- end form group -->
								<?php endif; ?>
								<div class="form-group">
									<label class="col-sm-6 control-label">Remarks:</label>
									<div class="col-sm-6">
										<input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo ucwords($pr->pe_remarks); ?>">
									</div>
								</div><!-- end form group -->
								<div class="form-group">
									<label class="col-sm-6 control-label">Requested by:</label>
									<div class="col-sm-6">
										<input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo ucwords($pr->firstname.' '.$pr->lastname); ?>">
									</div>
								</div><!-- end form group -->
								<div class="col-sm-offset-2 col-sm-10">

				                    <table class="table table-responsive table-production-request">
				                    	<thead>
				                    		<th>Denomination</th>
				                    		<th>Quantity</th>
				                    		<th></th>
				                    	</thead>
				                    	<tbody>
			                            <?php
			                                $total = 0; 
			                                foreach ($ngc as $gc):?>
			                                <tr>
			                                    <?php
			                                        $subtotal = $gc->denomination * $gc->pe_items_quantity; 
			                                        $total = $total + $subtotal;
			                                    ?>
			                                    <td><label>&#8369 <?php echo number_format($gc->denomination,2); ?></label></td>
			                                    <td><?php echo $gc->pe_items_quantity; ?></td>
			                                    <td>&#8369 <?php echo number_format($subtotal,2); ?></td>
			                                </tr>
			                            <?php endforeach; ?>
			                                <tr>
			                                    <td></td>
			                                    <td><label>Total</label></td>
			                                    <td>&#8369 <?php echo  number_format($total,2); ?></td>
			                                </tr>
				                    	</tbody>
				                    </table>   

								</div>
							</div>
						</div><!-- end of details -->
					</div>
				</div>
			</div>
		</div>
    </div><!-- end row -->
  </div><!-- end main fluid -->

<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/fin.js"></script>
<?php include 'footer.php' ?>