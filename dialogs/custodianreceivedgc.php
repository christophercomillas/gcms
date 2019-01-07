<?php 
	session_start();
	include '../config.php';
	include '../function.php';

	if(isset($_GET['id']))
	{
		$id = $_GET['id'];
	}
	else 
	{
		exit();
	}

	$table = 'custodian_srr_items';
	$select = 'custodian_srr_items.cssitem_barcode,
				denomination.denomination';
	$where = 'custodian_srr_items.cssitem_recnum='.$id;
	$join = 'INNER JOIN
				gc
			ON
				gc.barcode_no = custodian_srr_items.cssitem_barcode
			INNER JOIN
				denomination
			ON
				denomination.denom_id = gc.denom_id';
	$limit = '';
	$gc = getAllData($link,$table,$select,$where,$join,$limit);

	$select = 'COUNT(custodian_srr_items.cssitem_barcode) as cnt,
			denomination.denomination';
	$where = 'custodian_srr_items.cssitem_recnum='.$id.'
				GROUP BY
					denomination.denomination';
	$join = 'INNER JOIN
				gc
			ON
				gc.barcode_no = custodian_srr_items.cssitem_barcode
			INNER JOIN
				denomination
			ON
				denomination.denom_id = gc.denom_id';
	$limit = '';
	$ngc = getAllData($link,$table,$select,$where,$join,$limit);


?>
<div class="row">
	<div class="col-xs-4">
		<table class="table">
			<thead>
				<tr>
					<th>Denomination</th>
					<th>Qty</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$total = 0; 
					foreach ($ngc as $n): 
				?>
					<tr>
						<td><?php echo number_format($n->denomination,2); ?></td>
						<td><?php 
								echo $n->cnt;
								$total +=$n->cnt; 
							?></td>
					</tr>
				<?php endforeach ?>				
			</tbody>
			<tfoot>
				<tr>
					<th>Total</th>
					<th><?php echo number_format($total); ?></th>
				</tr>
			</tfoot>
		</table>
	</div>
	<div class="col-xs-8">
		<table class="table" id="cusrecgc">
			<thead>
				<tr>
					<th>GC Barcode #</th>
					<th>Denomination</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($gc as $g): ?>
					<tr>
						<td><?php echo $g->cssitem_barcode; ?></td>
						<td><?php echo $g->denomination; ?></td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>

<script>
    $('#cusrecgc').dataTable( {
        "pagingType": "full_numbers",
        "ordering": false,
        "processing": true
    });
</script>