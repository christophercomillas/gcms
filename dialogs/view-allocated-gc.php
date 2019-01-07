<?php
	
	include '../function.php';
	if(isset($_GET['store']) && isset($_GET['gctype'])){
		$store = $_GET['store'];
		$gctype = $_GET['gctype'];
	} 
	else 
	{
		exit();
	}

	$allocated = getAllocatedGC($link,$store,$gctype);	
?>
<div class="row">
	<div class="col-xs-12">
		<table class="table" id="allocated-gc">
			<thead>
				<tr>
					<th>GC Barcode No.</th>
					<th>Date Allocated</th>				
					<th>Allocated By</th>
					<th>GC Type</th>
					<th>Production #</th>
					<th>Denom</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($allocated as $key): ?>
					<tr>
						<td><?php echo $key->loc_barcode_no; ?></td>
						<td><?php echo _dateFormat($key->loc_date)?></td>
						<td><?php echo ucwords($key->firstname.' '.$key->lastname); ?></td>
						<td><?php echo ucwords($key->gctype); ?></td>
						<td><?php echo $key->pe_num; ?></td>
						<td><?php echo number_format($key->denomination,2); ?></td>
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