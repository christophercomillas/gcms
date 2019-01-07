<?php 
	session_start();
	include '../function.php';

	$table = 'temp_receivestore';
	$select = '	temp_receivestore.trec_barcode,
		denomination.denomination';
	$where = "temp_receivestore.trec_store='".$_SESSION['gc_store']."'
			AND
		temp_receivestore.trec_by='".$_SESSION['gc_id']."'";

	$join = 'INNER JOIN
			denomination
		ON
			denomination.denom_id = temp_receivestore.trec_denid';

	$gc = getAllData($link,$table,$select,$where,$join,'');
	
?>
<div class="row">
	<div class="col-xs-12">
		<table class="table" id="cusscanned">
			<thead>
				<tr>
					<th>GC Barcode #</th>
					<th>Denomination</th>					
				</tr>
			</thead>			
			<tbody>
					<?php foreach ($gc as $g): ?>
					<tr>
						<td><?php echo $g->trec_barcode; ?></td>
						<td><?php echo number_format($g->denomination,2); ?></td>						
					</tr>		
					<?php endforeach; ?>				
			</tbody>
		</table>
	</div>
</div>
<script>
    $('#cusscanned').dataTable( {
        "pagingType": "full_numbers",
        "ordering": false,
        "processing": true
    });
</script>