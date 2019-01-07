<div class="row">
	<form class="form-horizontal" id="lookupf">
	<div class="col-xs-12">
		<div class="form-group">
			<label class="col-xs-5 lbl-c alignright">GC Barcode #</label>
			<div class="col-xs-7">
				<input type="text" class="form-control inpmed gclookup" name="gclookup" maxlength="13" autocomplete="off">
				<input type="text" class="hid" name="hidlook" value="0">
			</div>
		</div>
		<div class="response-lookup">		
		</div>
	</div>
	</form>
</div>
<script>
	$('.gclookup').focus();
</script>
