<?php 
	session_start();
	include '../function.php';
	require 'header.php';
	require '../menu.php'; 
// SELECT barcode_no FROM gc WHERE NOT EXISTS (SELECT prom_barcode FROM promo_gc WHERE promo_gc.prom_barcode = gc.barcode_no)
	$gc = getAvailablePromoGC($link);

?>

<div class="main fluid">    
	<div class="row">
		<div class="col-sm-10">
			<div class="box box-bot">
				<div class="box-header">
					<span class="box-title-with-btn"><i class="fa fa-inbox">
					  </i> Available GC for Promo
					</span>			
				</div>
				<div class="box-content">
					<table class="table dtablest" id="gclistavail">
						<thead>
							<tr>
								<th>GC Barcode #</th>
								<th>Group</th>								
							</tr>							
						</thead>
						<tfoot>
							<tr>
								<th>GC Barcode #</th>
								<th>Group</th>
							</tr>
						</tfoot>
						<tbody>
							<?php foreach ($gc as $g): ?>
								<tr>
									<td><?php echo $g->barcode_no; ?></td>
									<td>Group <?php echo $g->pe_group; ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
	  	</div>
	</div>
</div>


<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/marketing.js"></script>
<?php include 'footer.php' ?>