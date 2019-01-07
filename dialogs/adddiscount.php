<?php
	include '../function.php';

	if(isset($_GET['customerid']))
		$customerid = $_GET['customerid'];
	else 
		exit();

	$details = getCustomerDiscountInfo($link,$customerid);
	$denom = getCustomerDiscounts($link,$customerid);
?>
<div class="row no-bot">
	<form class="form-horizontal" action="../ajax.php?action=updateCustomerDiscount" id="customer-internal">
		<div class="col-xs-12">
			<input type="hidden" name="cusid" value="<?php echo $customerid; ?>">
			<div class="form-group">
				<label class="col-xs-5 control-label">Customer Name</label>
				<div class="col-xs-7">
					<input name="code" class="form-control formbot reqfield input-sm" type="text" value="<?php echo ucwords($details->ci_name); ?>" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-5 control-label">Discount Type</label>
				<div class="col-xs-5">
					<select class="form form-control formbot input-sm" name="disctype" onchange="discountchange(this.value)">
						<?php if($details->ci_distype =='0'): ?>
							<option value="0">No Discount</option>
							<option value="1">Amount</option>
							<option value="2">Percentage</option>
						<?php endif; ?>
						<?php if($details->ci_distype =='1'): ?>
							<option value="1">Amount</option>
							<option value="0">No Discount</option>
							<option value="2">Percentage</option>
						<?php endif; ?>
						<?php if($details->ci_distype =='2'): ?>
							<option value="2">Percentage</option>
							<option value="0">No Discount</option>
							<option value="1">Amount</option>
						<?php endif; ?>
					</select>
				</div>
			</div>
			<div class="denom-wrapper">
				<div class="form-group">
					<label class="col-xs-5 control-label">Denomination</label>
				</div>
				<?php foreach ($denom as $d ): ?>
					<div class="form-group">
						<label class="col-xs-5 control-label"><?php echo number_format($d->denomination,2); ?></label>
						<div class="col-xs-5">
							<input data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false"  name="d<?php echo $d->cdis_denom_id; ?>" class="form-control formbot tl denoms input-sm" value="<?php echo $d->cdis_dis; ?>" <?php echo $details->ci_distype==0?'disabled':''; ?> />
						</div>
					</div>					
				<?php endforeach ?>

			</div>
		<div class="response"></div>
		</div>
	</form>
</div>
