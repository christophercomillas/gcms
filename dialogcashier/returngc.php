	<?php
session_start();
include_once "../function-cashier.php";
?>
<div class="row">
	<form action="../ajax-cashier.php?request=gcreturn" id="gcreturn">	
		<div class="col-xs-4">			
				<input type="hidden" name="modes" value="1">
				<div class="form-group fg-nobot">
					<label class="control-label lbl-c col-xs-12 translbl">Enter Transaction No.</label>
					<input type="hidden" name="transid" id="transid" value="0">
				</div>		 
				<div class="form-group">
					<input type="text" name="transno" class="form-control inpmed" id="transno" maxlength="15">
				</div>
				<div class="fgroup showhide">
					<div class="form-group fg-nobot">
						<label class="control-label lbl-c col-xs-12">Enter Barcode Number:</label>
					</div>	
					<div class="form-group">
						<input type="text" name="rbarcode" class="form-control inpmed" id="rbarcode" maxlength="13">
					</div>
				</div>
				<div class="response">
				</div>
			</form>
		</div>
		<div class="col-xs-8 form-horizontal">
			<div class="refdetail">
				<div class="refheader">
					Transaction Details
				</div>
				<div class="form-group refgroup">
					<label class="col-xs-5 control-label lbl-c lblsm">Trans Date</label>
					<div class="col-xs-7">
						<input class="form form-control input-xs inpmed inptxtmed" type="text" readonly="readonly" name="dot">
					</div>
				</div>
				<div class="form-group refgroup">
					<label class="col-xs-5 control-label lbl-c lblsm">Cashier</label>
					<div class="col-xs-7">
						<input class="form form-control input-xs inpmed inptxtmed" type="text" readonly="readonly" name="cashier">
					</div>
				</div>
				<div class="form-group refgroup">
					<label class="col-xs-5 control-label lbl-c lblsm">Store</label>
					<div class="col-xs-7">
						<input class="form form-control input-xs inpmed inptxtmed" type="text" readonly="readonly" name="store">
					</div>
				</div>
			</div>
			<div class="gctoreturns">
			</div>
		</div>
	</form>
</div>
<script>
	$('#transno,#rbarcode').inputmask("integer", { allowMinus: false});          
	$('#transno').focus(); 
</script>