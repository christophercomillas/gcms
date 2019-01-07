<?php 
	session_start();
	include_once '../function.php';
	include_once '../function-cashier.php';

	$ref = getTotalRefund($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id']);

	$scharge = getField($link,'app_settingvalue','app_settings','app_tablename','refund_charge');

	//count number of items
	$scharge = $scharge * $ref->cnt;

	$sc_setting = getField($link,'app_settingvalue','app_settings','app_tablename','pos_show_service_charge');

	if($sc_setting=='off')
	{
		$totamt = $ref->rfundtot;
	}
	else 
	{
		$totamt = $ref->rfundtot - $scharge;
	}
	

?>
<div class="row">
	<div class="col-xs-6">
		<h3 class="totref">
			Total Refund: 
		</h3>
	</div>
	<div class="col-xs-6">
		<h3 class="totrefs">
			<?php echo number_format($totamt,2); ?>
		</h3>
	</div>
	<div class="response-ref">
	</div>
</div>