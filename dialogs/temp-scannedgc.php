<?php
	include '../function.php';
	if(isset($_GET['id'])){
		$id = $_GET['id'];
	} 
	else 
	{
		exit();
	}

	$gc = getTempScannedGCByLocation($link,$id);
?>

<table class="table" id="allocatedgc">
	<thead>
		<tr>
			<td>Barcode No.</td>
			<td>Pro. No.</td>
			<td>Type</td>
			<td>Denomination</td>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($gc as $gc): 
			if($gc->loc_gc_type==1)
				$type = 'Regular';
			else 
				$type = 'Special';
		?>
			<tr>
				<td><?php echo $gc->temp_rbarcode; ?></td>
				<td><?php echo $gc->pe_entry_gc; ?></td>
				<td><?php echo $type; ?></td>
				<td><?php echo number_format($gc->denomination,2); ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<script>
	$.extend( $.fn.dataTableExt.oStdClasses, {	  
	    "sLengthSelect": "selectsup"
	});
    $('#allocatedgc').dataTable( {
        "pagingType": "full_numbers",
        "ordering": false,
        "processing": true,
        "iDisplayLength": 5,
    });
    $("#allocatedgc_length").css("display", "none");

    $('div#allocatedgc_filter input').focus();
</script>