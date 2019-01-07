
<?php 
	session_start();
	include '../function.php';
// SELECT 
// 	temp_promo.tp_barcode,
// 	denomination.denomination,
// 	gc_type.gctype
// FROM 
// 	temp_promo 
// INNER JOIN
// 	denomination
// ON
// 	denomination.denom_id = temp_promo.tp_den
// INNER JOIN
// 	gc_type
// ON
// 	gc_type.gc_type_id = temp_promo.tp_gctype
	
	$table = 'temp_promo';
	$select = '	temp_promo.tp_barcode,
		denomination.denomination,
		gc_type.gctype';
	$where = "denomination.denomination!=''";
	$join = 'INNER JOIN
			denomination
		ON
			denomination.denom_id = temp_promo.tp_den
		INNER JOIN
			gc_type
		ON
			gc_type.gc_type_id = temp_promo.tp_gctype';
	$limit = '';
	$gc = getAllData($link,$table,$select,$where,$join,$limit);
?>
<div class="row">
	<div class="col-xs-12">
		<table class="table" id="gcscanned">
			<thead>
				<tr>
					<th>Barcode</th>
					<th>Denomination</th>
					<th>GC Type</th>
					<th class="center">Action</th>
				</tr>
			</thead>		
			<tbody>
				<?php foreach ($gc as $g): ?>
				<tr>
					<td><?php echo $g->tp_barcode; ?></td>
					<td><?php echo number_format($g->denomination,2); ?></td>
					<td><?php echo ucwords($g->gctype); ?></td>
					<td class="center"><i class="fa fa-times faremove" title="Remove" onclick="removescanpromogc(<?php echo $g->tp_barcode; ?>)"></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
<script>
	$.extend( $.fn.dataTableExt.oStdClasses, {	  
	    "sLengthSelect": "selectsup"
	});

    $('#gcscanned').dataTable( {
        "pagingType": "full_numbers",
        "ordering": false,
        "processing": true
    });
</script>
