<?php
session_start();
include_once "../function-cashier.php";
$payment = getOneField($link,'revalidate_price','cashiering_options');
?>
<div class="row">
	<form action="../ajax-cashier.php?request=gcrevalidate" id="gcrevalidate" class="form-horizonal">
		<div class="col-xs-5">
			<div class="form-group">
				<input type="hidden" name="validateprice" value="<?php echo $payment; ?>">
				<label class="control-label lbl-c adjtop col-xs-7 translbl ar">Total Payment:</label>
				<div class="col-xs-5">
					<input type="text" name="payment" readonly="readonly" class="form-control inpmed" id="payment" value="0.00">
				</div>
			</div>					
			<div class="form-group fg-nobot">
				<label class="control-label lbl-c col-xs-12 translbl">GC Barcode #: </label>
			</div>		
			<div class="form-group">
				<input type="text" name="rbarcode" class="form-control inplar" id="rbarcode" maxlength="13" autocomplete="off">
			</div>	
			<div class="response">
			</div>		
		</div>

		<div class="col-xs-7">
			<table class="table revalTable">
				<thead>
					<tr>
						<th>Barcode</th>
						<th>Type</th>
						<th>Denomination</th>
						<th>Sold</th>
						<th>Verified</th>
					</tr>
				</thead>
				<tbody class="tbodyreval">
					
				</tbody>
			</table>
			<div class="note">
				Note: Revalidation fee is 20.00 per GC.
			</div>
			<div class="row">
				<div class="col-xs-4">
					<button class="btn btn-success" type="button" onclick="f1();">F1 Cash</button>
				</div>
			</div>
		</div>
	</form>
</div>		