<?php
	include '../function.php';
	if(isset($_GET['barcode']))
		$barcode = $_GET['barcode'];
	else 
		exit();

	$tr = getAllNavPOSTranx($link,$barcode);
?>
<div class="row">
	<div class="col-xs-12">
		<table class="table" id="navtrax">
			<thead>
				<tr>
					<th>Textfile Line</th>
					<th>Credit Limit</th>
					<th>Cred. Pur. Amt + Add-on</th>
					<th>Add-on Amt</th>
					<th>Remaining Balance</th>
					<th>Transaction #</th>
					<th>Time of Cred Tranx</th>
					<th>Bus. Unit</th>
					<th>Terminal #</th>
					<th>Ackslip #</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($tr as $t): ?>
					<tr>
						<td><?php echo $t->seodtt_line; ?></td>
						<td><?php echo $t->seodtt_creditlimit; ?></td>
						<td><?php echo $t->seodtt_credpuramt; ?></td>
						<td><?php echo $t->seodtt_addonamt; ?></td>
						<td><?php echo $t->seodtt_balance; ?></td>
						<td><?php echo $t->seodtt_transno; ?></td>
						<td>
							<?php 
                                $ti = $t->seodtt_timetrnx;
                                if(strlen($t->seodtt_timetrnx)===3)
                                {
                                    $ti = "0".$ti;
                                }
								$date = new DateTime($ti);
								echo $date->format('h:i a');
							?>
						</td>
						<td><?php echo $t->seodtt_bu; ?></td>
						<td><?php echo $t->seodtt_terminalno; ?></td>
						<td><?php echo $t->seodtt_ackslipno; ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

	</div>
</div>

<script>
    $('#navtrax').dataTable( {
        "pagingType": "full_numbers",
        "ordering": false,
        "processing": true
    });
</script>