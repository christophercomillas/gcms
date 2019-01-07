<?php 
session_start();
include_once '../function-cashier.php';
	
if(isset($_GET['cusgroup']))
{
	$group = $_GET['cusgroup'];
	if(is_null($group))
	{
		exit();
	}
	else 
	{
		//$subtotal = checkTotal($link);
		$subtotal = checkTotalwithoutLineDiscount($link);
		$linedisc = linediscountTotal($link);
		$docdisc = docdiscount($link);
		$amtdue = $subtotal - ($docdisc + $linedisc);
	}
}
else 
{
	exit();
}
?>
<div class="row">
	<form class="form-horizontal" id="headoffice">
		<input type="hidden" name="artype" value="<?php echo $group; ?>">
		<input type="hidden" name="customercodehide" id="customercodehide" value="">
		<input type="hidden" name="amtduehid" value="<?php echo $amtdue; ?>">
		<div class="col-xs-12">
			<div class="col-xs-6">
				<div class="form-group form-group3">
					<label class="control-label col-xs-4 lbl-c">Subtotal </label>
					<div class="col-xs-7">
						<input type="text" class="form form-control inpmed" readonly="readonly" name="amtdue" id="hostotal" value="<?php echo number_format($subtotal,2); ?>">
					</div>
				</div>
				<div class="form-group form-group3">
					<label class="control-label col-xs-4 lbl-c">Line Disc: </label>
					<div class="col-xs-7">
						<input type="text" class="form form-control inpmed" readonly="readonly" name="discount" id="holinedisc" value="<?php echo number_format($linedisc,2); ?>">
					</div>
				</div>
				<div class="form-group form-group3">
					<label class="control-label col-xs-4 lbl-c">Sub Disc: </label>
					<div class="col-xs-7">
						<input type="text" class="form form-control inpmed" readonly="readonly" name="docdis" id="hoamtdue" value="<?php echo number_format($docdisc,2); ?>">
					</div>
				</div>
			</div>
			<div class="col-xs-6">
				<div class="form-group form-group3">
					<label class="control-label col-xs-4 lbl-c">Discount: </label>
					<div class="col-xs-7">
						<input type="text" class="form form-control inpmed discount" readonly="readonly" name="discount" id="discount" value="0.00">
					</div>
				</div>
				<div class="form-group form-group3">
					<label class="control-label col-xs-4 lbl-c">Amt. Due: </label>
					<div class="col-xs-7">
						<input type="text" class="form form-control inpmed inpred totaldis" readonly="readonly" name="totaldis" id="totaldis" value="<?php echo number_format($amtdue,2); ?>">
					</div>
				</div>				
			</div>
		</div>
		<div class="col-xs-12">
			<div class="form-group form-group3">
				<label class="control-label col-xs-4 lbl-c">Code: </label>
				<div class="col-xs-5">
					<input type="text" class="form form-control inpmed" name="customercode" id="customercode" onkeyup="getCustomerInfo(this.value,<?php echo $group; ?>);">
				</div>
				<label class="control-label col-xs-3 f1lookuptxt">[F1] Lookup </label>
			</div>
			<div class="form-group form-group3">
				<label class="control-label col-xs-4 lbl-c">Customer: </label>
				<div class="col-xs-8">
					<input type="text" class="form form-control inpmed alignleft" readonly="readonly" name="customername" id="customername">
				</div>
			</div>
			<div class="form-group form-group3">
				<label class="control-label col-xs-4 lbl-c">Address: </label>
				<div class="col-xs-8">
					<textarea class="form form-control inpmed alignleft" readonly="readonly" name="customeraddress" id="customeraddress"></textarea>
				</div>
			</div>
			<div class="form-group form-group3">
				<label class="control-label col-xs-4 lbl-c">Type: </label>
				<div class="col-xs-8">
					<input type="text" class="form form-control inpmed alignleft" readonly="readonly" name="customertype" id="customertype">
				</div>
			</div>
			<div class="form-group form-group3">
				<label class="control-label col-xs-4 lbl-c">Balance: </label>
				<div class="col-xs-8">
					<input type="text" class="form form-control inpmed alignleft" readonly="readonly" name="arbalance" id="arbalance">
				</div>
			</div>
			<div class="form-group form-group3">
				<label class="control-label col-xs-4 lbl-c">Remarks: </label>
				<div class="col-xs-8">
					<textarea class="form form-control inpmed alignleft" disabled="true" name="remarks" id="remarks" style="font-size:12px; height:60px;"></textarea>
				</div>
			</div>

			<div class="response">
			</div>
		</div>		
	</form>
</div>
<script>
	$('#customercode').focus();
	$('#customercode').inputmask("integer", { allowMinus: false});
</script>