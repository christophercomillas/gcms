<?php
	session_start();
	include '../function.php';
	if(!isset($_GET['id']) || !isset($_GET['action']))
		exit();

	$id = $_GET['id'];
	$action = $_GET['action'];
    if($action=='promorelease'):

	//check id

    $table = 'promo_gc_request';
    $select = "promo_gc_request.pgcreq_reqnum,
        promo_gc_request.pgcreq_datereq,
        promo_gc_request.pgcreq_id,
        promo_gc_request.pgcreq_dateneeded,
        promo_gc_request.pgcreq_total,
        promo_gc_request.pgcreq_doc,
        promo_gc_request.pgcreq_remarks,
        CONCAT(users.firstname,' ',users.lastname) as prep,
        CONCAT(recom.firstname,' ',recom.lastname) as recby,
        promo_gc_request.pgcreq_relstatus";
    $where = "promo_gc_request.pgcreq_status='approved'
        AND
        	promo_gc_request.pgcreq_id='$id'
        AND 
        	(promo_gc_request.pgcreq_relstatus=''
        OR
        	promo_gc_request.pgcreq_relstatus='partial')";
    $join = 'INNER JOIN
            users
        ON
            users.user_id = promo_gc_request.pgcreq_reqby
        LEFT JOIN
            approved_request    
        ON
            approved_request.reqap_id = promo_gc_request.pgcreq_id
        LEFT JOIN
            users as recom
        ON
            recom.user_id = approved_request.reqap_preparedby';
    $limit = 'ORDER BY pgcreq_id ASC';

    $request = getSelectedData($link,$table,$select,$where,$join,$limit);
    if(count($request)==0)
    	exit();
?>
<form class="form-horizontal" action="../ajax.php?action=releaseGCpromo" id="gc_srrpromo" enctype="multipart/form-data">
	<div class="row form-container">
		<div class="col-sm-5">
			<input type="hidden" id="trid" name="trid" value="<?php echo $id; ?>">
			<input type="hidden" id="relid" name="relid" value="<?php echo getRequestNo($link,'promo_gc_release_to_details','prrelto_relnumber'); ?>">
			<div class="form-group">
				<label class="col-sm-6 control-label">Promo GC Releasing No.</label>
				<div class="col-sm-3">
					<input type="text" class="form-control input-sm inptxt reqfield" name="relno" value="<?php echo sprintf("%04d",getRequestNo($link,'promo_gc_release_to_details','prrelto_relnumber'));?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Date Released:</label>
				<div class="col-sm-6">
				  <input name="proc" type="text" class="form form-control inptxt input-sm" value="<?php echo _dateFormat($todays_date); ?>" readonly="readonly">
				</div>
			</div><!-- end of form-group -->
			<div class="form-group">
				<label class="col-sm-6 control-label">Upload Document:</label>
				<div class="col-sm-6">
				  <input id="upload" type="file" class="form-control inptxt input-sm" name="docs[]" />
				</div>
			</div><!-- end of form-group -->
			<div class="form-group">
				<label id="remark" class="col-sm-6 control-label"><span class="requiredf">*</span>Remarks:</label>
				<div class="col-sm-6">
				  <input type="text" class="form-control input-sm inptxt reqfield" name="remark" id="remark" required />
				</div>
			</div><!-- end of form-group -->
			<div class="form-group">
				<label class="col-sm-6 control-label"><span class="requiredf">*</span>Checked by:</label>
				<div class="col-sm-6">
					<div class="input-group">
						<input name="checked" id="app-checkby" type="text" class="form-control input-sm inptxt reqfield" readonly="readonly" required="required">
						<span class="input-group-btn">
							<button class="btn btn-info input-sm" id="checkbud" onclick="requestAssig(<?php echo $_SESSION['gc_usertype']; ?>,1);" type="button">
							<span class="glyphicon glyphicon-search"></span>
							</button>
						</span>
					</div><!-- input group -->
				</div>
			</div><!-- end form group -->
			<div class="form-group">
				<label class="col-sm-6 control-label"><span class="requiredf">*</span>Approved by:</label>
				<div class="col-sm-6">
					<div class="input-group">
						<input name="approved" id="app-apprby" type="text" class="form-control input-sm inptxt reqfield" readonly="readonly" required="required">
						<span class="input-group-btn">
							<button class="btn btn-info input-sm" id="approvedbud" onclick="requestAssig(<?php echo $_SESSION['gc_usertype']; ?>,2);" type="button">
							  <span class="glyphicon glyphicon-search"></span>
							  </button>
						</span>
					</div><!-- input group -->
				</div>
			</div><!-- end form group -->
			<div class="form-group">
				<label class="col-sm-6 control-label label-prepared">Released by:</label>
				<div class="col-sm-6">
					<input name="released" type="text" readonly="readonly" class="form form-control inptxt input-sm reqfield" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>" />
				</div>
			</div><!-- end form group -->
			<div class="form-group">
				<label class="col-sm-6 control-label label-prepared"><span class="requiredf">*</span>Received by:</label>
				<div class="col-sm-6">
					<input name="received" type="text" class="form form-control input-sm inptxt reqfield" />
				</div>
			</div><!-- end form group -->
			<div class="response">
			</div>
		</div>
		<div class="col-sm-7">
			<div class="form-group">
				<label class="col-sm-3 control-label">Promo GC Req #:</label>
				<div class="col-sm-3">
				  <input type="text" class="form-control input-sm inptxt store-name" readonly="readonly" value="<?php echo $request->pgcreq_reqnum; ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Date Requested:</label>
				<div class="col-sm-3">
				  <input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo _dateFormat($request->pgcreq_datereq); ?>">
				</div>
				<label class="col-sm-3 control-label">Time Requested:</label>
				<div class="col-sm-3">
				  <input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo _timeFormat($request->pgcreq_datereq); ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Date Needed:</label>
				<div class="col-sm-3">
				  <input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo _dateFormat($request->pgcreq_dateneeded); ?>">
				</div>
				<?php if(!empty($request->pgcreq_doc)): ?>
				<label class="col-sm-3 control-label">Document:</label>
				<div class="col-sm-3">
				  	<a class="btn btn-block btn-default" href='../assets/images/gcRequestStore/download.php?file=<?php echo $request->pgcreq_doc; ?>'><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download</a>
				</div>
				<?php endif; ?>
			</div><!-- end of form-group -->
			<div class="form-group">
				<label class="col-sm-3 control-label">Remarks:</label>
				<div class="col-sm-5">
				  <input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo ucwords($request->pgcreq_remarks); ?>">
				</div>
			</div><!-- end of form-group -->  
			<div class="form-group">
				<label class="col-sm-3 control-label">Requested by:</label>
				<div class="col-sm-5">
				  <input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo ucwords($request->prep); ?>">
				</div>
			</div><!-- end of form-group -->
			<table class="table table-req">
				<thead>
				  <tr>
				    <th>Denomination</th>
				    <th>Requested GC</th>
				    <th>Subtotal</th>
				    <th><span class="requiredf">*</span>Scanned GC</th>
				  </tr>                      
				</thead>
				<tbody>
					<?php 
						$table ='promo_gc_request_items';
						$select = 'promo_gc_request_items.pgcreqi_qty,
							promo_gc_request_items.pgcreqi_remaining,
							promo_gc_request_items.pgcreqi_denom,
							denomination.denomination';
						$where = "promo_gc_request_items.pgcreqi_trid = '".$id."'
							AND
								promo_gc_request_items.pgcreqi_remaining > 0";
						$join = 'INNER JOIN
								denomination
							ON
								denomination.denom_id = promo_gc_request_items.pgcreqi_denom';
						$limit = '';
						$gcs = getAllData($link,$table,$select,$where,$join,$limit);
						$total = 0;
						foreach ($gcs as $key):
						$subtotal = 0;
					?> 
						<tr>
							<td class="td-den" style="padding:6px;"><span class="pesosign">&#8369</span> <?php echo number_format($key->denomination,2); ?></td>
							<td class="inptxt"><span class="<?php echo 'remain'.$key->pgcreqi_denom; ?>"><?php echo $key->pgcreqi_remaining; ?></span> pc(s)</td>
							<td class="inptxt"><span class="pesosign">&#8369</span>
								<?php 
									$subtotal = $key->denomination * $key->pgcreqi_remaining; 
									echo number_format($subtotal,2);
								?>
							</td>
							<td class="scangcx<?php echo $key->pgcreqi_denom; ?> inptxt">0</td>
						</tr>

					<?php endforeach; ?>
						<tr>
							<td></td>
							<td><button class="btn btn-block btn-primary" type="button" onclick="requestPromoReleasedScanGCByRange()"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Scan GC (Range)</button></td>
							<td><button class="btn btn-block btn-primary" type="button" onclick="requestPromoReleasedScanGC()"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Scan GC (Barcode) </button></td>
							<td><button class="btn btn-primary" type="button" onclick="viewscannedgcstorereceivedPromo()"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> View Scanned GC</button></td>
						</tr>

				</tbody>
			</table>			
		</div>
	</div>
</form>
<?php elseif($action=='recomdetails'):     
    $table='approved_request';
    $select = "approved_request.reqap_remarks,
        approved_request.reqap_doc,
        approved_request.reqap_date,
        CONCAT(users.firstname,' ',users.lastname) as preparedby";
    $where = "approved_request.reqap_trid='$id'
        AND
            approved_request.reqap_approvedtype ='promo gc preapproved'";
    $join = 'INNER JOIN
            users
        ON
            users.user_id = approved_request.reqap_preparedby';
    $limit ='LIMIT 1';
    $requestapp = getSelectedData($link,$table,$select,$where,$join,$limit);
?>

<div class="row form-horizontal">
    <div class="form-group">
        <label class="col-sm-5 control-label">Date Approved:</label>
        <div class="col-sm-6">
            <input type="text" class="form inptxt form-control" value="<?php echo _dateFormat($requestapp->reqap_date)?>" disabled>
        </div>                                      
    </div>
    <div class="form-group">
        <label class="col-sm-5 control-label">Time Approved:</label>
        <div class="col-sm-6">
            <input type="text" class="form inptxt form-control" value="<?php echo _timeFormat($requestapp->reqap_date)?>" disabled>
        </div>                                      
    </div>
    <?php if($requestapp->reqap_doc!=''): ?>
    <div class="form-group">
        <label class="col-sm-5 control-label">Document:</label>
        <div class="col-sm-6">
            <a class="btn btn-block btn-default" href='../assets/images/budgetRecommendation/download.php?file=<?php echo $requestapp->reqap_doc; ?>'><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download</a>
        </div>                                      
    </div>
    <?php endif; ?>
    <div class="form-group">
        <label class="col-sm-5 control-label">Remarks:</label>
        <div class="col-sm-6">
            <textarea class="form inptxt form-control inptxt tarea" disabled><?php echo $requestapp->reqap_remarks; ?></textarea>
        </div>                                      
    </div>
</div>	
<?php endif; ?>

