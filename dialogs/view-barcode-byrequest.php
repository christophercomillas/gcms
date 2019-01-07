<?php
	include '../function.php';

	if(isset($_GET['id'])){
		$id = $_GET['id'];
	}
	else 
	{
		exit();
	}

	$gc = getGCReleasedItemsById($link,$id);
?>

<div class="row">
	<div class="col-12-sm">
		<table class="table" id="allocated-gc">
			<thead>
				<tr>
					<th>GC Barcode No.</th>
					<th>Denomination</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($gc as $key): ?>
					<tr>
						<td><?php echo $key->barcode_no; ?></td>
						<td>&#8369 <?php echo number_format($key->denomination,2); ?></td>							
					</tr>					
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>




