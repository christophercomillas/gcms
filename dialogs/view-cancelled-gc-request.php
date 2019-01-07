<?php
	
	include '../function.php';
	if(isset($_GET['id'])){
		$id = $_GET['id'];
	} else {
		exit();
	}

	$cancelled = getAllCancelledGCRequestStoreById($link,$id);

	$items = getAllCancelledGCRequestStoreItemsById($link,$id);
?>

<div class="row">	
	<div class="col-sm-6">
		<div class="form-horizontal">
		<?php foreach($cancelled as $key): ?>
			<div class="form-group">
				<label class="col-sm-6 control-label">Request No.:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control input-sm" readonly="readonly" value="<?php echo $key->sgc_num;?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Date Requested:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control input-sm" readonly="readonly" value="<?php echo _dateFormat($key->sgc_date_request); ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Retail Store:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control input-sm" readonly="readonly" value="<?php echo $key->store_name; ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Date Needed:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control input-sm" readonly="readonly" value="<?php echo _dateFormat($key->sgc_date_needed);?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Request Remarks:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control input-sm" readonly="readonly" value="<?php echo $key->sgc_remarks; ?>">
				</div>
			</div>
			<?php if(!empty($key->sgc_file_docno)): ?>
			<div class="form-group">
				<label class="col-sm-6 control-label">Request Document:</label>
				<div class="col-sm-6">
					<a class="btn btn-default" href='../assets/images/productionRequestFile/download.php?file=<?php echo $key->sgc_file_docno; ?>.jpg'><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download</a></td> 
				</div>
			</div>
			<?php endif; ?>
			<div class="form-group">
				<label class="col-sm-6 control-label">Request Prepared by:</label>
				<div class="col-sm-6">
				<input type="text" class="form-control input-sm" readonly="readonly" value="<?php echo ucwords($key->firstname.' '.$key->lastname);?>">
				
				</div>
			</div>
			<hr></hr>
			<div class="form-group">
				<label class="col-sm-6 control-label">Date Cancelled:</label>
				<div class="col-sm-6">
				<input type="text" class="form-control input-sm" readonly="readonly" value="<?php echo _dateFormat($key->csgr_at); ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Cancelled by:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control input-sm" readonly="readonly" value="<?php echo getUserFirstnameAndLastnameById($link,$key->csgr_by); ?>">
				</div>
			</div>
		<?php endforeach; ?>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="row">
			<div class="col-sm-12">
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
							foreach ($items as $key): ?>
							<tr>
								<?php 								
									$sub = $key->denomination * $key->sri_items_quantity;
									$total+=$sub;

								?>
			                    <td>&#8369 <?php echo  number_format($key->denomination,2); ?></td>
			                    <td><?php echo number_format($key->sri_items_quantity); ?></td>
			                    <td>&#8369 <?php echo number_format($sub,2); ?></td>
			                </tr>
			          	<?php endforeach; ?>
			                <tr>
			                    <td></td>
			                    <td>Total: </td>
			                    <td>&#8369 <?php echo number_format($total,2); ?></td>
			                </tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
