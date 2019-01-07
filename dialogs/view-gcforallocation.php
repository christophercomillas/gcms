<?php	
	include '../function.php';

	$table ='gc';
	$select = '	gc.barcode_no,
		denomination.denomination,
		custodian_srr.csrr_prepared_by,
		custodian_srr.csrr_datetime,
		users.firstname,
		users.lastname';
	$where = "gc.gc_validated='*'
			AND
				gc.gc_allocated=''
			AND
				gc.gc_ispromo=''
			AND
				gc.gc_treasury_release=''";
	$join = 'INNER JOIN
				denomination
			ON
				denomination.denom_id = gc.denom_id
			INNER JOIN
				custodian_srr_items
			ON
				custodian_srr_items.cssitem_barcode = gc.barcode_no
			INNER JOIN
				custodian_srr
			ON
				custodian_srr.csrr_id = custodian_srr_items.cssitem_recnum
			INNER JOIN
				users
			ON
				users.user_id = custodian_srr.csrr_prepared_by';
	$limit = '';
	$gc = getAllData($link,$table,$select,$where,$join,$limit);
?>
<div class="row">
	<div class="col-xs-12">
		<table class="table" id="allocated-gc">
			<thead>
				<tr>
					<th>GC Barcode #</th>
					<th>Denom</th>
					<th>Date Validated</th>
					<th>Validate By</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($gc as $g): ?>
					<tr>
						<td><?php echo $g->barcode_no; ?></td>
						<td><?php echo $g->denomination; ?></td>
						<td><?php echo _dateFormat($g->csrr_datetime); ?></td>
						<td><?php echo ucwords($g->firstname.' '.$g->lastname); ?></td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>
<script>
    $('#allocated-gc').dataTable({
        "pagingType": "full_numbers",
        "ordering": false,
        "processing": true
    });

    $("#allocated-gc_length").css("display", "none");
</script>