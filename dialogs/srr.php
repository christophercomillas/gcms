<?php
	include '../function.php';
	if(isset($_GET['id']))
		$id = $_GET['id'];
	echo $id;

	$ereq = getEReq($link,$id);
	$ereqtrim = ltrim($ereq->requis_erno, '0');
	$recNum = getReceivingNumber($link,'csrr_id','custodian_srr');
	$denom = getDenominations($link);
?>

<div class="row">
	<form class="form-horizontal" action="../ajax.php?action=custodianrec" id="gc_srr">
		<div class="col-sm-6">
<!-- 			<input type="hidden" name="uid" value="<?php echo $id;?>">	
			<input type="hidden" name="requisid" value="<?php echo $ereq->requis_id;?>">
			<input type="hidden" name="prid" value="<?php echo $ereq->repuis_pro_id; ?>">	 -->
			<div class="form-group">
				<label class="col-sm-5 control-label">GC Receiving No.</label>
				<div class="col-sm-3">
					<input type="text" class="form-control input-sm reqfield inptxt" name="gcrecno" value="<?php echo $recNum; ?>" readonly="readonly">
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-5 control-label">E-Requisition No.</label>
				<div class="col-sm-3">
					<input type="text" class="form-control input-sm reqfield inptxt" value="<?php echo $ereq->requis_erno; ?>" readonly="readonly">
				</div>
			</div>		
			<div class="form-group">
				<label class="col-sm-5 control-label">Upload FAD P.O.</label>
				<div class="col-sm-7">
					<input type="file" name="po[]" accept="text/plain" class="form-control input-sm inptxt" id="poupload">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Receiving Type</label>
				<div class="col-sm-5">
					<input type="text" class="form-control input-sm reqfield srrtype inptxt" name="rectype" readonly="readonly">
				</div>
			</div>
			<div class="group-wrap">
				<table class="table" id="table-FAD">
					<thead>
						<th>Denomination</th>
						<th class="cntrtxt">Qty Received</th>
						<th class="cntrtxt">No. GC Validated</th>
					</thead>
					<tbody>
						<?php foreach ($denom as $d): ?>
							<tr>
								<td class="denlist"><?php echo number_format($d->denomination,2); ?></td>
								<td><input type="text" id="rnumgc" class="form-control input-sm inptxt den<?php echo $d->denom_id; ?>" value="0" name="den<?php echo $d->denom_id; ?>" readonly="readonly"></td>
								<td><input type="text" class="form-control input-sm inptxt n<?php echo $d->denom_id; ?>" value="0" readonly="readonly"></td>
							</tr>
						<?php endforeach; ?>
						<tr>
							<td></td>
							<td><button type="button" class="btn btn-default btn-block" onclick="validateGCusByRange(<?php echo $recNum; ?>)"><i class="fa fa-barcode"></i>
 Validate By Range</button></td>
							<td><button type="button" class="btn btn-default btn-block" onclick="validateGCus(<?php echo $recNum; ?>)"><i class="fa fa-barcode"></i>
 Validate By Barcode</button></td>
						</tr>						
					</tbody>
				</table>
			</div>	
		</div>
		<div class="col-sm-6">
			<div class="poheader">
				FAD P.O. Details
			</div>	
			<div class="group-wrap group-wrapdetails">
				<div class="form-group sxs-frm">
					<label class="col-sm-5 control-label sxs-lbl">FAD Receiving No:</label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm sxs-int fadrec" name="fadrec" readonly="readonly">
					</div>
				</div>
				<div class="form-group sxs-frm">
					<label class="col-sm-5 control-label sxs-lbl">Transaction Date:</label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm sxs-int trandate" name="trandate" readonly="readonly">
					</div>
				</div>
				<div class="form-group sxs-frm">
					<label class="col-sm-5 control-label sxs-lbl">Reference No:</label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm sxs-int refno" name="refno" readonly="readonly">
					</div>
				</div>
				<div class="form-group sxs-frm">
					<label class="col-sm-5 control-label sxs-lbl">Purchase Order No:</label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm sxs-int purono" name="purono" readonly="readonly">
					</div>
				</div>
				<div class="form-group sxs-frm">
					<label class="col-sm-5 control-label sxs-lbl">Purchase Date:</label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm sxs-int purdate" name="purdate" readonly="readonly">
					</div>
				</div>
				<div class="form-group sxs-frm">
					<label class="col-sm-5 control-label sxs-lbl">Reference PO No:</label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm sxs-int refpono" name="refpono" readonly="readonly">
					</div>
				</div>
				<div class="form-group sxs-frm">
					<label class="col-sm-5 control-label sxs-lbl">Payment Terms:</label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm sxs-int payterms" name="payterms" readonly="readonly">
					</div>
				</div>
				<div class="form-group sxs-frm">
					<label class="col-sm-5 control-label sxs-lbl">Location Code:</label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm sxs-int locode" name="locode" readonly="readonly">
					</div>
				</div>
				<div class="form-group sxs-frm">
					<label class="col-sm-5 control-label sxs-lbl">Department Code:</label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm sxs-int deptcode" name="deptcode" readonly="readonly">
					</div>
				</div>
				<div class="form-group sxs-frm">
					<label class="col-sm-5 control-label sxs-lbl">Supplier Name:</label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm sxs-int supname" name="supname" readonly="readonly">
					</div>
				</div>
				<div class="form-group sxs-frm">
					<label class="col-sm-5 control-label sxs-lbl">Mode of Payment:</label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm sxs-int modpay" name="modpay" readonly="readonly">
					</div>
				</div>
				<div class="form-group sxs-frm">
					<label class="col-sm-5 control-label sxs-lbl">Remarks:</label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm sxs-int remarks" name="remarks" readonly="readonly">
					</div>
				</div>
				<div class="form-group sxs-frm">
					<label class="col-sm-5 control-label sxs-lbl">Checked By:</label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm sxs-int prepby" name="prepby" readonly="readonly">
					</div>
				</div>
				<div class="form-group sxs-frm">
					<label class="col-sm-5 control-label sxs-lbl">Prepared By:</label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm sxs-int checkby" name="checkby" readonly="readonly">
					</div>
				</div>	
			</div>
			<div class="response">
			</div>	
		</div>

	</form>
</div>	