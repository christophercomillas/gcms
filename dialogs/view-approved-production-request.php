<?php
	
	include '../function.php';
	if(isset($_GET['id']))
	{
		$id = $_GET['id'];
	}
	else 
	{
		exit();
	}
	$ap = approvedProductionRequestDetails($link,$id);
	$totalprodReq = getTotalProductionRequest($link,$id);
    $items = getAllItemsWithBarcode($link,$id);
    $table = "production_request_items";
    $select = "denomination.denomination,
        production_request_items.pe_items_quantity";
    $where = "production_request_items.pe_items_request_id='".$id."'";
    $join = "INNER JOIN
            denomination
        ON
            denomination.denom_id = production_request_items.pe_items_denomination";
    $limit = "";
    $proreq = getAllData($link,$table,$select,$where,$join,$limit);
?>
<div class="row">	
	<div class="col-sm-6">
		<div class="form-horizontal">
			<div class="form-group">
				<label class="col-sm-6 control-label">PR No.:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo $ap->pe_num; ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Date Requested:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo _dateFormat($ap->pe_date_request); ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Time Requested:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo _timeFormat($ap->pe_date_request); ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Date Needed:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?= _dateFormat($ap->pe_date_needed); ?>">
				</div>
			</div>
			<?php if($ap->pe_type == 2): ?>
			<div class="form-group">
				<label class="col-sm-6 control-label">Promo Group: </label>
				<div class="col-sm-6">
					<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="Group <?php echo $ap->pe_group; ?>">			
				</div>
			</div>				
			<?php endif; ?>
			<div class="form-group">
				<label class="col-sm-6 control-label">Request Remarks:</label>
				<div class="col-sm-6">
					<textarea class="form-control inptxt input-sm" readonly="readonly"><?= ucwords($ap->pe_remarks); ?></textarea>					
				</div>
			</div>
			<?php if(!empty($ap->pe_file_docno)): ?>
			<div class="form-group">
				<label class="col-sm-6 control-label">Request Document:</label>
				<div class="col-sm-6">
					<a class="btn btn-default" href='../assets/images/productionRequestFile/download.php?file=<?php echo $ap->pe_file_docno; ?>'><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download</a></td> 
				</div>
			</div>
			<?php endif; ?>
			<div class="form-group">
				<label class="col-sm-6 control-label">Request Prepared by:</label>
				<div class="col-sm-6">
				<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?= ucwords($ap->frequest.' '.$ap->lrequest); ?>">
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-horizontal">
			<div class="form-group">
				<label class="col-sm-6 control-label">Date Approved:</label>
				<div class="col-sm-6">
				<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?= _dateFormat($ap->ape_approved_at); ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Approved Remarks:</label>
				<div class="col-sm-6">
				<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?= ucwords($ap->ape_remarks); ?>">
				</div>
			</div>
			<?php if(!empty($ap->pe_file_docno)): ?>
			<div class="form-group">
				<label class="col-sm-6 control-label">Approved Document:</label>
				<div class="col-sm-6">
				<a class="btn btn-default" href='../assets/images/approvedProductionRequest/download.php?file=<?php echo $ap->pe_file_docno; ?>'><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download</a>
				</div>
			</div>
			<?php endif;?>
			<div class="form-group">
				<label class="col-sm-6 control-label">Approved by:</label>
				<div class="col-sm-6">
				<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?= ucwords($ap->ape_approved_by); ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Checked by:</label>
				<div class="col-sm-6">
				<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?= ucwords($ap->ape_checked_by); ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-6 control-label">Prepared by:</label>
				<div class="col-sm-6">
				<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?= ucwords($ap->fapproved.' '.$ap->lapproved); ?>">
				</div>
			</div>
			<?php if(!$ap->pe_generate_code): ?>
			<div class="form-group">
				<label class="col-sm-6 control-label">Total GC</label>
				<div class="col-sm-6">
				<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo number_format($totalprodReq,2); ?>">
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php if($ap->pe_generate_code): ?>
<div class="row">
	<div class="col-sm-12">
		<table class="table">
			<thead>
				<tr>
                    <th>Denomination</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Barcode No. Start</th>
                    <th>Barcode No. End</th>
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
							$sub = $key->pe_items_quantity * $key->denomination;
							$total = $total+$sub;
						?>								
						<td><?php echo '&#8369 '.number_format($key->denomination,2); ?></td>
						<td><?php echo $key->pe_items_quantity; ?></td>
						<td><?php echo 'pc(s)'?></td>
						<td><?php echo getBarcodeNumberReq($link,$key->pe_items_denomination,$id,'ASC'); ?></td>
						<td><?php echo getBarcodeNumberReq($link,$key->pe_items_denomination,$id,'DESC'); ?></td>
						<td><?php echo '&#8369 '.number_format($sub,2); ?></td>
					</tr>
				<?php endforeach ?>
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>Total: </td>
					<td><?php echo '&#8369 '.number_format($total,2)?></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
<?php else: ?>
<div class="row">
	<div class="col-sm-12">
    <table class="table">
        <thead>
            <tr>
                <th>Denomination</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($proreq as $pr): ?>
                <tr>
                    <td><?php echo number_format($pr->denomination,2); ?></td>
                    <td><?php echo number_format($pr->pe_items_quantity); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
            
    </div>
</div>
<?php endif; ?>

<div class="row">
	<div class="col-sm-6">
		<div class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-6 control-label">Barcode Generated:</label>
            <?php if($ap->pe_generate_code): ?>
            <div class="col-sm-5">                    	
				<div class="input-group">
				  <span class="input-group-btn">
				    <button class="btn btn-info input-sm" id="viewbarcodepro" onclick="viewbarcodegen(<?php echo $ap->pe_id; ?>)" type="button">
				      <span class="glyphicon glyphicon-search"></span>
				      </button>
				  </span>
				</div><!-- input group -->
            </div>
        	<?php else: ?>
        	<div class="col-sm-6">
        		<input type="type"type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo $ap->pe_generate_code ? 'Yes' : 'pending'; ?>">
        	</div>
        	<?php endif; ?>
          </div><!-- end form group -->
			<div class="form-group">
				<label class="col-sm-6 control-label">Requisiton Created:</label>
                <?php if($ap->pe_requisition): ?>
                <div class="col-sm-5">                    	
					<div class="input-group">
					  <span class="input-group-btn">
					    <button class="btn btn-info input-sm" id="viewrequisition" onclick="viewrequisition(<?php echo $ap->pe_id; ?>);" type="button">
					      <span class="glyphicon glyphicon-search"></span>
					      </button>
					  </span>
					</div><!-- input group -->
                </div>
            	<?php else: ?>
            	<div class="col-sm-6">
            		<input type="type"type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo $ap->pe_requisition ? 'Yes' : 'pending'; ?>">
            	</div>
            	<?php endif; ?>
			</div>
		</div>				
	</div>
</div>