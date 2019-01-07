<?php
	
	include '../function.php';
	if(isset($_GET['id'])){
		$id = $_GET['id'];
	} else {
		exit();
	}

	// $cancelled = getAllCancelledGCRequest(
	// 				$link,
	// 				'production_request',
	// 				'cancelled_production_request',
	// 				'production_request.pe_id',
	// 				'cancelled_production_request.cpr_pro_id',
	// 				'users',
	// 				'cancelled_production_request.cpr_by',
	// 				'users.user_id',
	// 				'production_request.pe_id',
	// 				$id
	// 			);

	$can = getAllCancelledProductionRequestByID($link,$id);

	if(is_null($can))
	{
		exit();
	}

    $items = getAllCancelledProductionItems($link,$id);
?>

<div class="row">	
	<div class="col-sm-5">
		<div class="form-horizontal">
			<div class="form-group">
				<label class="col-sm-6 control-label">PR No.:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo $can->pe_num;?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Date Requested:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo _dateFormat($can->pe_date_request); ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Time Requested:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo _timeFormat($can->pe_date_request); ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Date Needed:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo _dateFormat($can->pe_date_needed);?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Request Remarks:</label>
				<div class="col-sm-6">
					<textarea class="form-control inptxt input-sm" readonly="readonly"><?php echo $can->pe_remarks; ?></textarea>
				</div>
			</div>
			<?php if(!empty($can->pe_file_docno)): ?>
			<div class="form-group">
				<label class="col-sm-6 control-label">Request Document:</label>
				<div class="col-sm-6">
					<a class="btn btn-default" href='../assets/images/productionRequestFile/download.php?file=<?php echo $can->pe_file_docno; ?>'><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download</a></td> 
				</div>
			</div>
			<?php endif; ?>
			<div class="form-group">
				<label class="col-sm-6 control-label">Request Prepared by:</label>
				<div class="col-sm-6">
				<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo ucwords($can->lreqfname.' '.$can->lreqlname); ?>">
				</div>
			</div>
			<hr></hr>
			<div class="form-group">
				<label class="col-sm-6 control-label">Date Cancelled:</label>
				<div class="col-sm-6">
				<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo _dateFormat($can->cpr_at); ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Cancelled by:</label>
				<div class="col-sm-6">
				<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo ucwords($can->lcanfname.' '.$can->lcanlname);?>">
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-7">
		<div class="row">
			<?php if($can->cpr_isrequis_cancel): ?>
				<div class="col-sm-12">
					<table class="table">
						<thead>
							<tr>
								<th>Denomination</th>
								<th>Barcode Start</th>
								<th>Barcode End</th>
								<th>Quantity</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$total = 0;
								foreach ($items as $key):
							?>							
							<tr>
							<?php 
								$sub = $key->denomination * $key->pe_items_quantity;
								$total = $total + $sub; 
							?>
								<td>&#8369 <?php echo number_format($key->denomination,2); ?></td>
								<td><?php echo getBarcodeNumberReq($link,$key->denom_id,$id,'ASC') ?></td>
								<td><?php echo getBarcodeNumberReq($link,$key->denom_id,$id,'DESC') ?></td>
								<td><?php echo $key->pe_items_quantity;?></td>
								<td>&#8369 <?php echo number_format($sub); ?></td>
							</tr>
							<?php endforeach; ?>			           			               
						</tbody>
						<tfoot>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td>Total:</td>
								<td>&#8369 <?php echo number_format($total,2); ?></td>
							</tr>
						</tfoot>
					</table>
				</div>
			<?php else: ?>
				<div class="col-sm-8">
					<table class="table">
						<thead>
							<tr>
								<th>Denomination</th>
								<th>Quantity</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$total = 0;
								foreach ($items as $key):
							?>							
							<tr>
							<?php 
								$sub = $key->denomination * $key->pe_items_quantity;
								$total = $total + $sub; 
							?>
								<td>&#8369 <?php echo number_format($key->denomination,2); ?></td>
								<td><?php echo $key->pe_items_quantity;?></td>
								<td>&#8369 <?php echo number_format($sub); ?></td>
							</tr>
							<?php endforeach; ?>			           			               
						</tbody>
						<tfoot>
							<tr>
								<td></td>	
								<td>Total:</td>
								<td>&#8369 <?php echo number_format($total,2); ?></td>
							</tr>
						</tfoot>
					</table>
				</div>				
			<?php endif; ?>
		</div>
	</div>
</div>


