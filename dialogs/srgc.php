<?php
	session_start();
	include '../function.php';
	if(isset($_GET['id']))
		$id = $_GET['id'];

	if(isset($_GET['storeid']))
		$storeid = $_GET['storeid'];


	$recnum = getReceivedNumByStore($link,$storeid);
	$details = getRequestDetails($link,$id);
	$denom = getGCReceivablesByReleasedAndStore($link,$id);
	$rel_stat = array('none','partial','whole','final');

?>

<div class="row">
	<form class="form-horizontal" action="../ajax.php?action=recGCStore" id="recGCStore">
		<div class="col-sm-5">
			<input type="hidden" class="reqfield" name="receivednum" value="<?php echo $recnum; ?>">
			<input type="hidden" class="reqfield" name="storeid" value="<?php echo $storeid; ?>">
			<input type="hidden" class="reqfield" name="relnum" value="<?php echo $details->agcr_id; ?>">
			<div class="form-group">
				<label class="col-sm-5 control-label">GC Receiving No.</label>
				<div class="col-sm-3">
					<input type="text" class="form-control input-sm inptxt" name="gcrecno" value="<?php echo threedigits($recnum); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Date Received:</label>
				<div class="col-sm-7">
					<input type="text" class="form-control input-sm inptxt" value="<?php echo _dateFormat($todays_date); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">GC Request No:</label>
				<div class="col-sm-3">
					<input type="text" class="form-control input-sm inptxt" value="<?php echo $details->sgc_num; ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Date Requested:</label>
				<div class="col-sm-7">
					<input type="text" class="form-control input-sm inptxt" value="<?php echo _dateFormat($details->sgc_date_request); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">GC Released No:</label>
				<div class="col-sm-3">
					<input type="text" class="form-control input-sm inptxt" value="<?php echo threedigits($details->agcr_id); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Date Released:</label>
				<div class="col-sm-7">
					<input type="text" class="form-control input-sm inptxt" value="<?php echo _dateFormat($details->agcr_approved_at); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Released By:</label>
				<div class="col-sm-7">
					<input type="text" class="form-control input-sm inptxt" value="<?php echo ucwords($details->firstname.' '.$details->lastname); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Received Type:</label>
				<div class="col-sm-7">
					<input type="text" class="form-control input-sm inptxt" value="<?php echo ucwords($rel_stat[$details->agcr_stat]); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Checked By:</label>
				<div class="col-sm-7">
					<input type="text" class="form-control input-sm reqfield inptxt" name="checkedby">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Received By:</label>
				<div class="col-sm-7">
					<input type="text" class="form-control input-sm inptxt" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>" readonly="readonly">
				</div>
			</div>
		</div>
		<div class="col-sm-7">
			<div class="row">
				<div class="col-xs-12">
					<button class="btn btn-default pull-right" type="button" onclick="viewscannedgcstorereceived()"><span class="glyphicon glyphicon-search"></span> Scanned GC</button>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="group-wrap">
						<table class="table" id="tablestyle">
							<thead>
								<tr>
									<th>Denomination</th>
									<th>Qty</th>
									<th></th>
									<th></th>
									<th>Scanned GC</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$totalpay = 0;
									$totalqty = 0; 
									foreach ($denom as $d): 
									$qty = getNumReceived($link,$id,$d->denom_id);
									$sub = 0;
									$sub = $qty * $d->denomination;
									$totalqty +=$qty;
									$totalpay +=$sub; 
								?>
								<tr>												
									<td>&#8369 <?php echo number_format($d->denomination,2); ?></td>							
									<td class="qty<?php echo $d->denom_id;?>"><?php echo $qty; ?></td>
									<td>&#8369 <?php echo number_format($sub,2); ?></td>
									<td><button class="btn" type="button" onclick="receivingGCStore(<?php echo $recnum.','.$storeid.','.$d->denom_id;?>)">Scan GC</button></td>
									<td class="scangc<?php echo $d->denom_id; ?>">0</td>
								</tr>
								<?php endforeach ?>				
							</tbody>
							<tfoot>
								<tr>
									<td>Total:</td>
									<td><?php echo $totalqty; ?></td>
									<td>&#8369 <?php echo number_format($totalpay,2); ?></td>
									<td></td>
									<td class="totalscan">0</td>							
								</tr>
							</tfoot>
						</table>
					</div>
					<div class="response">
					</div>
				</div>
			</div>
		</div>
	</form>
</div>