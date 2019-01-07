<?php
	
	include '../function.php';
	if(isset($_GET['stid'])){
		$id = $_GET['stid'];
	}
	else 
	{
		exit();
	}
	$relGC = releasedGC($link,$id);	
?>
<div class="row">
	<div class="col-12-sm">
		<table class="table" id="allocated-gc">
			<thead>
				<tr>
					<th>GC Barcode No.</th>
					<th>Denomination</th>
					<th>GC Request No.</th>					
				</tr>
			</thead>
			<tbody>
				<?php foreach ($relGC as $key): ?>
					<tr>
						<td><?php echo $key->barcode_no; ?></td>
						<td>&#8369 <?php echo number_format($key->denomination,2); ?></td>
						<th><?php echo $key->sgc_num; ?></th>
					</tr>					
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>


