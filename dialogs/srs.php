<?php
	session_start();
	include '../function.php';
	if(isset($_GET['id']))
		$id = $_GET['id'];

	$relnum = getReceivingNumber($link,'agcr_request_relnum','approved_gcrequest');
	$details = getStoreRequestDetails($link,$id);
	$rgc = getRemainingGCtoRelease($link,$id);

?>
<form class="form-horizontal" action="../ajax.php?action=releaseGC" id="gc_srr" enctype="multipart/form-data">
<div class="row form-container">
	<div class="col-sm-5">	
			<input type="hidden" name="rid" value="<?php echo $id; ?>">
			<input type="hidden" name="releasenumber" value="<?php echo $relnum; ?>">	
			<div class="form-group">
				<label class="col-sm-6 control-label">GC Releasing No.</label>
				<div class="col-sm-3">
					<input type="text" class="form-control input-sm inptxt reqfield" name="relno" value="<?php echo threedigits($relnum); ?>" readonly="readonly">
				</div>
			</div>
<!-- 			<div class="form-group">
				<label class="col-sm-6 control-label">GC Request Status:</label>
				<div class="col-sm-6">
				  <select id="status" class="form form-control input-sm reqfield" name="status" required autofocus>
				      <option value="">-Select-</option>    
				      <option value="1">Approved</option>
				      <option value="2">Cancel</option>
				  </select>
				</div>
			</div> --><!-- end of form-group -->
			<div class="hide-cancel">
				<div class="form-group">
					<label class="col-sm-6 control-label">Date Released:</label>
					<div class="col-sm-6">
					  <input name="proc" type="text" class="form form-control inptxt input-sm" value="<?php echo _dateFormat($todays_date); ?>" readonly="readonly">
					</div>
				</div><!-- end of form-group -->
				<div class="form-group">
					<label class="col-sm-6 control-label">Upload Document:</label>
					<div class="col-sm-6">
					  <input id="upload" type="file" class="form-control inptxt input-sm" name="pic[]" />
					</div>
				</div><!-- end of form-group -->
				<div class="form-group">
					<label id="remark" class="col-sm-6 control-label"><span class="requiredf">*</span>Remarks:</label>
					<div class="col-sm-6">
					  <input type="text" class="form-control input-sm inptxt reqfield" name="remark" id="remark" required />
					</div>
				</div><!-- end of form-group -->
				<div class="form-group">
					<label class="col-sm-6 control-label"><span class="requiredf">*</span>Checked by:</label>
					<div class="col-sm-6">
						<div class="input-group">
							<input name="checked" id="app-checkby" type="text" class="form-control input-sm inptxt reqfield" readonly="readonly" required="required">
							<span class="input-group-btn">
								<button class="btn btn-info input-sm" id="checkbud" onclick="requestAssig(<?php echo $_SESSION['gc_usertype']; ?>,1);" type="button">
								<span class="glyphicon glyphicon-search"></span>
								</button>
							</span>
						</div><!-- input group -->
					</div>
				</div><!-- end form group -->
			</div><!-- end of hide-cancel div -->
			<div class="form-group">
				<label class="col-sm-6 control-label label-prepared">Released by:</label>
				<div class="col-sm-6">
					<input name="released" type="text" readonly="readonly" class="form form-control inptxt input-sm reqfield" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>" />
				</div>
			</div><!-- end form group -->
			<div class="form-group">
				<label class="col-sm-6 control-label label-prepared"><span class="requiredf">*</span>Received by:</label>
				<div class="col-sm-6">
					<input name="received" type="text" class="form form-control input-sm inptxt reqfield" />
				</div>
			</div><!-- end form group -->

			<div class="form-group">
				<label class="col-sm-6 control-label label-prepared">Payment Type</label>   
				<div class="col-sm-6">
					<select class="form form-control input-sm inptxt" name="paymenttypeStores" id="paymenttypeStores" required>
						<option value="">- Select -</option>
						<option value="cash">Cash</option>
						<option value="check">Check</option>
						<option value="jv">JV</option>
					</select>
				</div>
			</div>

			<div class="paymenttypediv" style="display:none">
				<div class="jvpayment">		
					<div class="form-group">
						<label class="col-sm-6 control-label label-prepared"><span class="requiredf">*</span>Customer:</label>										
						<div class="col-sm-6">
							<select class="form form-control input-sm inptxt" name="jvcust" id="jvcust" required>
								<option value="">- Select -</option>
								<option value="beam and go">Beam and Go</option>
							</select>
						</div>	
					</div>			
				</div>
				<div class="cashpayment">												
					<div class="form-group">
						<label class="col-sm-6 control-label label-prepared"><span class="requiredf">*</span>Amount Received</label>
						<div class="col-sm-6">
							<input type="text" class="form form-control inptxt input-sm bot-6 amts" name="amountrec" id="amountrec" autocomplete="off" data-inputmask="'alias': 'numeric','groupSeparator': ',','autoGroup': true,'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" required>
						</div>
					</div>				
				</div>
				<div class="checkpayment">
					<div class="form-group">
						<label class="col-sm-6 control-label label-prepared"><span class="requiredf">*</span>Bank Name</label>
						<div class="col-sm-6">
							<input type="text" class="form form-control inptxt input-sm bot-6 amts" name="bankname" id="bankname" autocomplete="off">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-6 control-label label-prepared"><span class="requiredf">*</span>Bank Account Number</label>
						<div class="col-sm-6">
							<input type="text" class="form form-control inptxt input-sm bot-6 amts" name="baccountnum" id="baccountnum" autocomplete="off">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-6 control-label label-prepared"><span class="requiredf">*</span>Check Number</label>
						<div class="col-sm-6">
							<input type="text" class="form form-control inptxt input-sm bot-6 amts" name="cnumber" id="cnumber" autocomplete="off">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-6 control-label label-prepared"><span class="requiredf">*</span>Check Amount</label>
						<div class="col-sm-6">
							<input type="text" class="form form-control inptxt input-sm bot-6 amts" name="camountrec" id="camountrec" autocomplete="off" data-inputmask="'alias': 'numeric','groupSeparator': ',','autoGroup': true,'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" required>
						</div>
					</div>									
				</div>	
			</div>

			<div class="response">
			</div>
	</div>
	<div class="col-sm-7">
			<div class="form-group">
				<label class="col-sm-3 control-label">Store:</label>
				<div class="col-sm-3">
				  <input type="text" class="form-control input-sm inptxt store-name" readonly="readonly" value="<?php echo $details->store_name; ?>">
				</div>
				<label class="col-sm-3 control-label">GC Request No.:</label>
				<div class="col-sm-3">
				  <input type="hidden" name="reqid" value="<?php echo $details->sgc_id; ?>">
				  <input type="hidden" name="store_id" value="<?php echo $details->sgc_store; ?>"> 
				  <input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo $details->sgc_num; ?>">
				</div>
			</div><!-- end of form-group -->
			<div class="form-group">
			<label class="col-sm-3 control-label">Date Requested:</label>
			<div class="col-sm-3">
			  <input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo _dateFormat($details->sgc_date_request); ?>">
			</div>
			<label class="col-sm-3 control-label">Time Requested:</label>
			<div class="col-sm-3">
			  <input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo _timeFormat($details->sgc_date_request); ?>">
			</div>
			</div><!-- end of form-group -->
			<div class="form-group">
				<label class="col-sm-3 control-label">Date Needed:</label>
				<div class="col-sm-3">
				  <input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo _dateFormat($details->sgc_date_needed); ?>">
				</div>
				<?php if(!empty($details->sgc_file_docno)): ?>
				<label class="col-sm-3 control-label">Document:</label>
				<div class="col-sm-3">
				  	<a class="btn btn-block btn-default" href='../assets/images/gcRequestStore/download.php?file=<?php echo $details->sgc_file_docno; ?>'><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download</a>
				</div>
				<?php endif; ?>
			</div><!-- end of form-group -->
			<div class="form-group">
				<label class="col-sm-3 control-label">Remarks:</label>
				<div class="col-sm-5">
				  <input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo ucwords($details->sgc_remarks); ?>">
				</div>
			</div><!-- end of form-group -->  
			<div class="form-group">
				<label class="col-sm-3 control-label">Requested by:</label>
				<div class="col-sm-3">
				  <input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo ucwords($details->firstname.' '.$details->lastname); ?>">
				</div>
				<div class="col-sm-offset-2 col-sm-4">
					<button type="button" class="btn btn-block btn-primary" id="viewalloc" onclick="requestReleasedAllocatedGC();"> <span class="glyphicon glyphicon-search" aria-hidden="true"></span> View Allocated GC</button>
				</div>
			</div><!-- end of form-group -->
			<?php if($details->sgc_type=='special internal'): ?>
				<div class="form-group">
					<label class="col-sm-3 control-label">Company Req.:</label>
					<div class="col-sm-7">
					  <input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo ucwords($details->spcus_customername); ?>">
					</div>
				</div><!-- end of form-group -->
			<?php endif; ?>
			<table class="table table-req">
				<thead>
				  <tr>
				    <th>Denomination</th>
				    <th>Requested GC</th>
				    <th>Subtotal</th>
				    <th>Allocated GC</th>
				    <th></th>
				    <th><span class="requiredf">*</span>Scanned GC</th>
				  </tr>                      
				</thead>
				<tbody> 
				  <?php 
				      $total = 0;
				      foreach ($rgc as $gc): 
				  ?>
				      <tr>
				          <?php 
				          		$subtotal = $gc->denomination * $gc->sri_items_remain;
				             	$total = $total + $subtotal;
				          ?>
				          <td class="td-den"><span class="pesosign">&#8369</span> 
				          	<span class="denrel">
				          		<?php if($gc->sri_items_denomination=='0'): ?>
				          			<?php echo number_format($gc->fds_denom,2); ?>
				          		<?php else: ?>
				          			<?php echo number_format($gc->denomination,2); ?>
				          		<?php endif; ?>
				          	</span>
				          </td>                             
				          <td class="inptxt"><span class="remain<?php echo $gc->sri_items_denomination; ?>"><?php echo $gc->sri_items_remain; ?></span> pc(s)</td>
				          <td class="inptxt">&#8369 <?php echo number_format($subtotal,2); ?></td>
				          <?php $denom = $gc->sri_items_denomination; ?>
				          <td class="inptxt"><?php echo getAllocatedGCNotReleasedByDenom($link,$details->sgc_store,$gc->sri_items_denomination).' pc(s)'; ?></td>
				          <?php if($gc->sri_items_denomination=='0'): ?>
				          	<td class="inptxt padd" colspan="2"><span class="label label-danger">Pending Denomination Setup</span></td>
				          <?php else: ?>
					        <td class="inptxt"><button type="button" class="btn scangc" onclick="requestReleasedScanGC(<?php echo $gc->sri_items_denomination; ?>);" denid="<?php echo $gc->sri_items_denomination; ?>">Scan GC</button></td>
					        <td class="scangcx<?php echo $gc->sri_items_denomination; ?> inptxt"><?php echo getScannedGC($link,$id,$denom); ?></td>
					      <?php endif; ?>
				      </tr>
				  <?php endforeach; ?>
				  <tr class="td-total">
				      <td></td>
				      <td><label class="inptxt">Total</label></td>
				      <td class="inptxt">&#8369 <?php echo  number_format($total,2); ?></td>
				      <td></td>
				      <td><button type="button" class="btn btn-block btn-primary" id="scanRangeRS" onclick="scanRangeReleasedStore();"> <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Scan Range</button></td>
				      <td><button type="button" class="btn btn-block btn-primary" id="viewscanned" onclick="requestReleasedScannedGC();"> <span class="glyphicon glyphicon-search" aria-hidden="true"></span> View Scanned GC</button></td>
				  </tr>  
				</tbody>
			</table>
	</div>
</div>
</form>	

<script type="text/javascript">
	$('#amountrec,#camountrec').inputmask();

	$('#paymenttypeStores').change(function(){
		var type = $(this).val();
		$('#bankname').val('');
		$('#baccountnum').val('');
		$('#cnumber').val('');
		$('#amountrec').val(0.00);
		$('#camountrec').val(0.00);


		if(type=='')
		{
			$('.paymenttypediv').hide();
		}
		else if(type=='cash')
		{
			$('.paymenttypediv').show();
			$('.checkpayment').hide();
			$('.jvpayment').hide();
			$('.cashpayment').fadeIn(500).show(600);

			$('#jvcust').prop('required',false);
			$('#bankname').prop('required',false);
			$('#baccountnum').prop('required',false);
			$('#cnumber').prop('required',false);
			$('#amountrec').prop('required',true);
			$('#camountrec').prop('required',false);
		}
		else if(type=='check')
		{
			$('.paymenttypediv').show();
			$('.cashpayment').hide();
			$('.jvpayment').hide();
			$('.checkpayment').fadeIn(500).show(600);

			$('#jvcust').prop('required',false);
			$('#bankname').prop('required',true);
			$('#baccountnum').prop('required',true);
			$('#cnumber').prop('required',true);
			$('#camountrec').prop('required',true);
			$('#amountrec').prop('required',false);
		}
		else if(type=='jv')
		{
			$('.paymenttypediv').show();
			$('.checkpayment').hide();
			$('.cashpayment').hide();
			$('.jvpayment').fadeIn(500).show(600);

			$('#jvcust').prop('required',true);
			$('#bankname').prop('required',false);
			$('#baccountnum').prop('required',false);
			$('#cnumber').prop('required',false);
			$('#camountrec').prop('required',false);
			$('#amountrec').prop('required',false);
		}
	});

</script>