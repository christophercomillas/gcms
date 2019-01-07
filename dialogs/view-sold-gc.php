<?php
	
	include '../function.php';
	if(isset($_GET['stid'])){
		$id = $_GET['stid'];
	}
	else 
	{
		exit();
	}
	$soldGC = soldGC($link,$id);	
	$ttype = array('','Cash','Credit Card','Head Office','Subs. Admin');
?>

<div class="row">
	<div class="col-xs-12">
		<table class="table" id="allocated-gc">
			<thead>
				<tr>
					<th>GC Barcode No.</th>
					<th>Denomination</th>
					<th>GC Request No.</th>
					<th>Transaction #</th>
					<th>Trans. Type</th>
					<th>Date Verified</th>					
				</tr>
			</thead>
			<tbody>
				<?php foreach ($soldGC as $key): ?>
					<tr>
						<td><?php echo $key->strec_barcode; ?></td>
						<td>&#8369 <?php echo number_format($key->denomination,2); ?></td>
						<td><?php echo threedigits($key->strec_recnum); ?></td>
						<td><?php echo $key->trans_number; ?></td>
						<td><?php echo $ttype[$key->trans_type]; ?></td>
						<td><?php echo $key->vs_barcode!=NULL? _dateFormat($key->vs_date) :''; ?></td>
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


