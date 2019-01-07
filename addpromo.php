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
		<div class="col-sm-10">
			<div class="box box-bot">
				<div class="box-header">
					<span class="box-title-with-btn"><i class="fa fa-inbox">
					  </i> Add New Promo
					</span>				
				</div>
				<div class="box-content">
					<form class="form-horizontal" action="../ajax.php?action=newpromo" id="newpromo">
						<div class="col-xs-7">
							<div class="form-group">
								<label class="col-xs-4 control-label">Promo No:</label>
								<div class="col-xs-5">
							    	<input type="text" class="form-control formbot" name="promono" readonly="readonly" value="<?php echo threedigits($promoNum); ?>">
								</div>
							</div>	
							<div class="form-group">
								<label class="col-xs-4 control-label">Date Created</label>
								<div class="col-xs-6">
									<input value="<?php echo _dateFormat($todays_date); ?>" type="text" class="form-control formbot input-sm" readonly="readonly">                
								</div>
							</div>
							<div class="form-group">
								<label class="col-xs-4 control-label"><span class="requiredf">*</span>Promo Group</label>
								<div class="col-xs-4">
									<select class="form form-control inptxt input-sm promog" name="group" required autofocus>
										<option value="">-Select-</option>
										<option value="1">Group 1</option>
										<option value="2">Group 2</option>
									</select>									
								</div>
							</div>
							<div class="form-group">
								<label class="col-xs-4 control-label"><span class="requiredf">*</span>Promo Name</label>
								<div class="col-xs-8">
									<input type="text" class="form-control formbot reqfield" name="promoname" id="promoname"  required autocomplete="off">
								</div>
							</div>
							<div class="form-group">
								<label class="col-xs-4 control-label"><span class="requiredf">*</span>Details</label>
								<div class="col-xs-8">
									<textarea class="form form-control formbot reqfield textareah" name="notes" required></textarea>
								</div>
							</div>	
							<div class="form-group">
								<label class="col-xs-4 control-label">Prepared By</label>
								<div class="col-xs-6">
									<input type="text" class="form-control formbot" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>" readonly="readonly">
								</div>
							</div>
							<div class="form-group">								
								<div class="col-xs-offset-8 col-xs-4">
								<button type="submit" class="btn btn-block btn-primary submitbut">
									<span class="glyphicon glyphicon-log-in"></span> &nbsp; Submit
								</button>
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
										<th><span class="requiredf">*</span>Scanned GC</th>
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
						<div class="col-xs-12">
							<div class="row">
								<div class="col-xs-6">
									<button class="btn btn-default btn-block btn-info" type="button" onclick="addPromoGC(<?php echo $promoNum; ?>)"><span class="glyphicon glyphicon-plus"></span> &nbsp; Add GC</button>
								</div>
								<div class="col-xs-6">
									<button class="btn btn-default btn-block btn-danger" type="button" onclick="viewScannedGCForPromo()"><span class="fa fa-barcode"></span> &nbsp; Scanned GC</button>
								</div>
							</div>
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