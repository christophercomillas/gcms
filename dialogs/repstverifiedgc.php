<?php 

include '../function.php';
if(isset($_GET['storeid'])&&
	isset($_GET['den'])&&
	isset($_GET['start'])&&
	isset($_GET['end'])){
	$storeid = $_GET['storeid'];
	$denom = $_GET['den'];
	$start = $_GET['start'];
	$end = $_GET['end'];

	$gcverified = getVerifiedGC($link,$storeid,$denom,$start,$end);
}
else 
{
	exit();
}
?>

<div class="row">
	<div class="col-sm-12">
		<table class="table" id="verifiedgc-view">
			<thead>
				<tr>
					<th>Barcode</th>
					<th>Denomination</th>
					<th>Customer Name</th>
					<th>Date/Time Verified</th>
					<th>Verified By:</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($gcverified as $gc): ?>
					<tr>
						<td><?php echo $gc->vs_barcode; ?></td>
						<td><?php echo number_format($gc->denomination,2); ?></td>
						<td><?php echo ucwords($gc->cus_fname.' '.$gc->cus_lname); ?></td>
						<td><?php echo _dateFormat($gc->vs_date).' / '._timeFormat($gc->vs_time); ?></td>
						<td><?php echo ucwords($gc->firstname.' '.$gc->lastname)?></td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>