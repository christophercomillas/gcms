<?php
	
	include '../function.php';
	if(isset($_GET['id'])){
		$id = $_GET['id'];

	}
	else 
	{
		exit();
	}

	$gc = getGCBarcodeForValidationDenomination($link,$id);
	$gcv = getBarcodeValidatedDenom($link,$id);
	
?>
<div class="row">
	<div class="col-xs-12">
	    <ul class="nav nav-tabs" role="tablist">
	      <li role="presentation" class="active"><a href="#gcforverify" aria-controls="gcforverify" role="tab" data-toggle="tab">GC For Validation</a></li>
	      <li role="presentation"><a href="#verified" aria-controls="verified" role="tab" data-toggle="tab">Validated GC</a></li>
	    </ul>
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane active" id="gcforverify">
			<div class="tabbable-panel">
				<div class="tabbable-line">
					<ul class="nav nav-tabs ">

						<?php 
							$x = 0;
							foreach ($gc as $key): 
						?>
							<li <?php echo $x==0 ? 'class="active"':''; ?>>
								<a href="#<?php echo $key->denomination; ?>" data-toggle="tab">
									<?php echo number_format($key->denomination,2);?>
								</a>
							</li>

						<?php
							$x+=1; 
							endforeach ?>
					</ul>
					<div class="tab-content tab-content-x">				
						<?php 
							$x = 0;
							foreach ($gc as $key): 
						?>
							<div class="tab-pane <?php echo $x==0 ? 'active':''; ?>" id="<?php echo $key->denomination; ?>">
								<?php $gc  = getGCBarcodeForValidation($link,$id,$key->denom_id); ?>
								<table class="table" id="gcs">
									<thead>
										<tr>
											<th>GC Barcode #</th>
											<th>Denomination</th>									
										</tr>								
									</thead>
									<tbody>
										<?php foreach ($gc as $gcs): ?>
											<tr>
												<td><?php echo $gcs->barcode_no; ?></td>
												<td><?php echo number_format($gcs->denomination,2); ?></td>
											</tr>
										<?php endforeach ?>
									</tbody>
								</table>
							</div>
						<?php 
							$x+=1; 
							endforeach; 
						?>
					</div>
				</div>
			</div>
          </div>
          <div role="tabpanel" class="tab-pane" id="verified">
			<div class="tabbable-panel">
				<div class="tabbable-line">
					<ul class="nav nav-tabs ">

						<?php 
							$x = 0;
							foreach ($gcv as $key): 
						?>
							<li <?php echo $x==0 ? 'class="active"':''; ?>>
								<a href="#<?php echo 't'.$key->denomination; ?>" data-toggle="tab">
									<?php echo number_format($key->denomination,2);?>
								</a>
							</li>

						<?php
							$x+=1; 
							endforeach ?>
					</ul>
					<div class="tab-content tab-content-x">				
						<?php 
							$x = 0;
							foreach ($gcv as $key): 
						?>
							<div class="tab-pane <?php echo $x==0 ? 'active':''; ?>" id="t<?php echo $key->denomination; ?>">
								<?php $gc  = getGCBarcodeValidated($link,$id,$key->denom_id); ?>
								<table class="table" id="gcs">
									<thead>
										<tr>
											<th>GC Barcode #</th>
											<th>Denomination</th>
											<th>Date Validated</th>									
										</tr>								
									</thead>
									<tbody>
										<?php foreach ($gc as $gcs): ?>
											<tr>
												<td><?php echo $gcs->barcode_no; ?></td>
												<td><?php echo number_format($gcs->denomination,2); ?></td>
												<td><?php echo _dateFormat($gcs->csrr_datetime); ?></td>
											</tr>
										<?php endforeach ?>
									</tbody>
								</table>
							</div>
						<?php 
							$x+=1; 
							endforeach; 
						?>
					</div>
				</div>
			</div>
          </div>
        </div>
	</div>
</div>
<script>
	$.extend( $.fn.dataTableExt.oStdClasses, {	  
	    "sLengthSelect": "selectsup"
	});
    $('table[id^=gc]').dataTable( {
        "pagingType": "full_numbers",
        "ordering": false,
        "processing": true
    });
</script>
