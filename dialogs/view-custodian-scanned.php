<?php 
	include '../function.php';
	if(isset($_GET['id']))
	{
		$id = $_GET['id'];
// 		SELECT 
// 	temp_validation.tval_barcode,
// 	denomination.denomination
// FROM 
// 	temp_validation
// INNER JOIN
// 	denomination
// ON
// 	denomination.denom_id = temp_validation.tval_denom
// WHERE
// 	temp_validation.tval_recnum='5'
		$select = 'temp_validation.tval_barcode,
			denomination.denomination';
		$join = 'INNER JOIN
				denomination
			ON
				denomination.denom_id = temp_validation.tval_denom';
		$limit='';
		$where = 'temp_validation.tval_recnum='.$id;
		$gc = getAllData($link,'temp_validation',$select,$where,$join,$limit);
		}
	else 
	{
		exit();
	}
?>
<div class="row">
	<div class="col-xs-12">
		<table class="table" id="cusscanned">
			<thead>
				<tr>
					<th>GC Barcode #</th>
					<th>Denomination</th>
					<th class="center">Action</th>
				</tr>
			</thead>			
			<tbody>
				<?php foreach ($gc as $g): ?>
					<tr>
						<td><?php echo $g->tval_barcode; ?></td>
						<td><?php echo number_format($g->denomination,2); ?></td>
						<td class="center"><i class="fa fa-times faremove" title="Remove" onclick="removescannedgc(<?php echo $g->tval_barcode; ?>)"></td>
					</tr>						
				<?php endforeach ?>
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