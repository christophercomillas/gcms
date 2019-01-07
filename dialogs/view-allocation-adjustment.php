<?php 
	include '../function.php';
	if(isset($_GET['id']))
		$id = $_GET['id'];
	else 
		exit();

	$select = '	allocation_adjustment_items.aadji_barcode,
		denomination.denomination';
	$where = 'allocation_adjustment_items.aadji_aadj_id = '.$id;
	$join = 'INNER JOIN
				gc
			ON
				gc.barcode_no = allocation_adjustment_items.aadji_barcode
			INNER JOIN
				denomination
			ON
				denomination.denom_id = gc.denom_id';
	$limit = 'ORDER BY allocation_adjustment_items.aadji_barcode ASC';
	$barcodes = getAllData($link,'allocation_adjustment_items',$select,$where,$join,$limit);

?>
<table class="table responsive" id="adjallocated">
	<thead>
		<tr>
			<th>Barcode</th>
			<th>Denomination</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($barcodes as $b): ?>
			<tr>
				<td><?php echo $b->aadji_barcode; ?></td>
				<td><?php echo number_format($b->denomination,2); ?></td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>

<script>
	$('#adjallocated').dataTable( {
        "pagingType": "full_numbers",
        "ordering": false,
        "processing": true
    });
</script>