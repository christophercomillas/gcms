<?php
	
	include '../function.php';
	if(isset($_GET['stid'])){
		$id = $_GET['stid'];
	}
	else 
	{
		exit();
	}

	$availableGC = availableGC($link,$id);	
?>

<div class="row">
	<div class="col-xs-12">
		<table class="table" id="allocated-gc">
			<thead>
				<tr>
					<th>Barcode No.</th>
					<th>Denomination</th>
					<th>Request No.</th>					
					<th>Returned</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($availableGC as $key): ?>
					<tr>
						<td><?php echo $key->strec_barcode; ?></td>
						<td>&#8369 <?php echo number_format($key->denomination,2); ?></td>
						<td><?php echo threedigits($key->sgc_num); ?></td>
						<td><?php echo $key->strec_return=='*' ? _dateFormat($key->trans_datetime):''; ?></td></td>
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
        "processing": true,
        "iDisplayLength": 5
    });

	$("#allocated-gc_length").css("display", "none");
</script>

