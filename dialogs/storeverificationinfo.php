<?php 
	session_start();
	include '../function.php';

	if(!isset($_GET['action']))
		exit();

	$action =$_GET['action'];

	if(isset($_GET['barcode']))
		$barcode = $_GET['barcode'];
	else 
		exit();

	if($action=='dateverified'):
		$select = "store_verification.vs_date,
			CONCAT(users.firstname,' ',users.lastname) as ver,
			store_verification.vs_time ";
		$where = "store_verification.vs_barcode='$barcode'";
		$join = 'INNER JOIN
				users
			ON
				users.user_id = store_verification.vs_by';
		$data = getSelectedData($link,'store_verification',$select,$where,$join,'');

?>
<div class="row">
	<div class="col-md-12">
		<div class="form-group">
			<label class="nobot">Date / Time Verified:</label>   
			<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo _dateFormat($data->vs_date).' / '._timeFormat($data->vs_time); ?>">                        
		</div>
		<div class="form-group">
			<label class="nobot">Verified By:</label>   
			<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo ucwords($data->ver); ?>">                        
		</div>
	</div>
</div>

<?php 
	elseif($action=='daterevalidated'):

	$select = "transaction_revalidation.reval_barcode,
		transaction_stores.trans_datetime,
		CONCAT(store_staff.ss_firstname,' ',store_staff.ss_lastname) as reval";
	$where = "transaction_revalidation.reval_barcode='".$barcode."'";
	$join = 'INNER JOIN
			transaction_stores
		ON
			transaction_stores.trans_sid = transaction_revalidation.reval_trans_id
		INNER JOIN
			store_staff
		ON
			store_staff.ss_id = transaction_stores.trans_cashier';

	$limit = " ORDER BY transaction_stores.trans_datetime DESC";

	$data = getalldata($link,'transaction_revalidation',$select,$where,$join,$limit);

	if(count($data)==0)
		exit();

?>
<div class="row">
	<div class="col-md-12">
		<table class="table">
			<thead>
				<tr>
					<th>Date / Time Revalidated</th>
					<th>Revalidated By</th>
				</tr>
				<tbody>
					<?php foreach ($data as $d): ?>
						<tr>
							<td><?php echo _dateFormat($d->trans_datetime).' / '._timeFormat($d->trans_datetime); ?></td>
							<td><?php echo ucwords($d->reval); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</thead>
		</table>
	</div>
</div>

<?php 
	elseif ($action=='reverification'):
	$select = "store_verification.vs_reverifydate,
		CONCAT(users.firstname,' ',users.lastname) as rever";
	$where = "store_verification.vs_barcode='".$barcode."'";
	$join = 'INNER JOIN
			users
		ON
			users.user_id = store_verification.vs_reverifyby';
	$data = getalldata($link,'store_verification',$select,$where,$join,'');

	if(count($data)==0)
		exit();

?>
<div class="row">
	<div class="col-md-12">
		<table class="table">
			<thead>
				<tr>
					<th>Date / Time Reverified:</th>
					<th>Reverified By</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data as $d): ?>
						<tr>
							<td><?php echo $d->vs_reverifydate; ?></td>
							<td><?php echo $d->rever; ?></td>
						</tr>
				<?php endforeach; ?>
			</tbody>			
		</table>
	</div>
</div>
<?php endif; ?>

<!-- 
SELECT
	store_verification.vs_date,
	CONCAT(users.firstname,' ',users.lastname)
FROM 
	store_verification 
INNER JOIN
	users
ON
	users.user_id = store_verification.vs_by
WHERE 
	store_verification.vs_barcode=''
AND
	store_verification.vs_store='' -->
