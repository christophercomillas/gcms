<?php 
	session_start();
	include '../function.php';
	require 'header.php';
	require '../menu.php'; 

	$denom = getDenominations($link);
	$promoNum = getLastnumberOneWhere($link,'promo','promo_num','promo_id');
?>

<div class="main fluid">    
	<div class="row form-container">
		<div class="col-sm-6">
			<div class="box box-bot">
				<div class="box-header">
					<span class="box-title-with-btn"><i class="fa fa-inbox">
					  </i> Released Promo GC
					</span>				
				</div>
				<div class="box-content">
					<form class="form-horizontal" method="POST" action="../ajax.php?action=gcpromoreleased" id="gcpromoreleased">
						<div class="form-group">
							<label class="col-xs-4 control-label">Date Released: </label>
							<div class="col-xs-5">
								<input value="<?php echo _dateFormat($todays_date); ?>" type="text" class="form-control inptxt input-sm" readonly="readonly">                
							</div>						
						</div>	
						<div class="form-group">
							<label class="col-xs-4 control-label">Received by: </label>
							<div class="col-xs-8">
								<input type="text" class="form-control inptxt input-sm" name="claimant" id="claimant" autocomplete="off">                
							</div>					
						</div>	
						<div class="form-group">
							<label class="col-xs-4 control-label">Address: </label>
							<div class="col-xs-8">
								<textarea class="form-control inptxt input-sm" name="address" id="address"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-4 control-label lpromorel">GC Barcode #: </label>
							<div class="col-xs-8">
								<input type="text" class="form-control inptxt input-sm promorel" data-inputmask="'alias': 'numeric', 'groupSeparator': '', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': ''" id="gcbarcodever" autocomplete="off" name="gcbarcode" maxlength="13" autofocus>                
							</div>					
						</div>	
						<div class="form-group">
							<label class="col-xs-4 control-label">Released By:</label>
							<div class="col-xs-6">
								<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>"> 
							</div>					
						</div>	
						<div class="form-group">
							<div class="col-xs-offset-4 col-xs-8">
								<div class="response">
								</div>
							</div>						
						</div>
						<div class="form-group">
							<div class="col-xs-offset-8 col-xs-4">
								<button type="submit" class="btn btn-block btn-primary releasedbtn">
	                        		<span class="glyphicon glyphicon-share" aria-hidden="true"></span>
	                         		Submit
	                      		</button>             
							</div>						
						</div>
					</form>
				</div>
			</div>
	  	</div>
	</div>
</div>


<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/marketing.js"></script>
<script type="text/javascript">
	$.ajax({
		url:'../ajax.php?action=deleteByIdTempPromo'
	});
</script>
<?php include 'footer.php' ?>