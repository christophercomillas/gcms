<?php 
	session_start();
	include '../config.php';
	include '../function.php';

	$denom = getDenominations($link);
	$promoNum = getReceivingNumber($link,'promo_id','promo');	
?>
<div class="row no-bot form-container">
	<form class="form-horizontal" action="../ajax.php?action=newpromo" id="newpromo">
		<div class="col-xs-7">
			<div class="form-group">
				<input type="hidden" class="reqfield" name="prn" value="<?php echo $promoNum; ?>">
				<label class="col-xs-4 control-label">Promo No:</label>
				<div class="col-xs-5">
			    	<input type="text" class="form-control formbot" name="promono" readonly="readonly" value="<?php echo threedigits($promoNum); ?>">
				</div>
			</div>	
			<div class="form-group">
				<label class="col-xs-4 control-label">Date Validated</label>
				<div class="col-xs-6">
					<input value="<?php echo _dateFormat($todays_date); ?>" type="text" class="form-control formbot input-sm" readonly="readonly">                
				</div>
			</div>	
			<div class="form-group">
				<label class="col-xs-4 control-label">Date Expire</label>
				<div class="col-xs-6">
					<input type="text" class="form form-control formbot input-sm reqfield" id="dp1" data-date-format="MM dd, yyyy" name="date_expired" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-4 control-label">Promo Name</label>
				<div class="col-xs-8">
					<input type="text" class="form-control formbot reqfield" name="promoname">
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-4 control-label">Remarks</label>
				<div class="col-xs-8">
					<textarea class="form form-control formbot reqfield" name="remarks"></textarea>
				</div>
			</div>	
			<div class="form-group">
				<label class="col-xs-4 control-label">Validated By</label>
				<div class="col-xs-6">
					<input type="text" class="form-control formbot" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>" readonly="readonly">
				</div>
			</div>
			<div class="response">
			</div>
		</div>
		<div class="col-xs-5">
			<table class="table tnewpromo" id="tablestyle">
				<thead>
					<tr>
						<th>Denomination</th>
						<th>Scanned GC</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($denom as $d): ?>
						<tr>
							<td><span class="dentd">&#8369 <?php echo number_format($d->denomination,2); ?></span></td>
							<td><input type="text" class="form-control formbot input-sm width100 sc<?php echo $d->denom_id; ?>" value="0" readonly="readonly"></td>
						</tr>						
					<?php endforeach ?>
				</tbody>
			</table>
		<div class="col-xs-offset-5 col-xs-7">
			<button class="btn btn-default btn-block" type="button" onclick="scangcPromo(<?php echo $promoNum; ?>)">Scan GC</button>
		</div>
		</div>
	</form>
</div>