<?php
	include '../function.php';

	if(isset($_GET['den']) &&  $_GET['den'] != '' && isset($_GET['relid']) && $_GET['relid']!='')
	{
		$den = $_GET['den'];
		$relid = $_GET['relid'];			
	}
	else 
	{
		exit();
	}

	$barcodes = getBarcodesByDenomByRelId($link,$den,$relid);

?>
<div class="row">
	<div class="col-xs-12">
		<table class="table" id="barcodesbyrelid">
			<thead>
				<tr>
					<th>Barcodes</th>					
				</tr>
			</thead>
			<tbody>
				<?php foreach ($barcodes as $key): ?>
					<tr>
						<td><?php echo $key->re_barcode_no?></td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>
<script>
    $('#barcodesbyrelid').dataTable( {
        "pagingType": "full_numbers",
        "ordering": false,
        "processing": true,
        "iDisplayLength": 5,
        "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
    });
</script>