<?php 
	if(!isset($_GET['id']) || !isset($_GET['type']))
	{
		exit();
	}

	else
	{
	$id = $_GET['id'];
	$type = $_GET['type'];
	}
	if($type=='regular'): 
?>
	<center><embed src='../reports/treasury_releasing/gcr<?php echo $id; ?>.pdf' width="940" height="520" type='application/pdf'></center>
<?php elseif($type=='promo'): ?>
	<center><embed src='../reports/treasury_releasingpromo/gcrprom<?php echo $id; ?>.pdf' width="940" height="520" type='application/pdf'></center>
<?php elseif ($type=='special'): ?>
	<center><embed src='../reports/externalReport/gcrspecial<?php echo $id; ?>.pdf' width="940" height="520" type='application/pdf'></center>
<?php elseif ($type=='specialexternalreleasing'): ?>
	<center><embed src='../reports/externalReport/special<?php echo $id; ?>.pdf' width="940" height="520" type='application/pdf'></center>
<?php elseif ($type=='inst'): ?>
	<center><embed src='../reports/treasury_releasing_institutions/gcinst<?php echo $id; ?>.pdf' width="940" height="520" type='application/pdf'></center>
<?php elseif($type=='treseod'): ?>
	<center><embed src='../reports/treasury_eod/eod<?php echo $id; ?>.pdf' width="940" height="520" type='application/pdf'></center>
<?php elseif ($type=='transferReleasing'): ?>
	<center><embed src='../reports/store-transfer/gctrrel<?php echo $id; ?>.pdf' width="940" height="520" type='application/pdf'></center>
<?php elseif ($type=='spgcpayment'): ?>
	<center><embed src='../reports/externalReport/specialgcpayment	<?php echo $id; ?>.pdf' width="940" height="520" type='application/pdf'></center>
<?php elseif ($type=='gcreport'): ?>
	<center><embed src='../reports/gcsales/gcsales<?php echo $id; ?>.pdf' width="940" height="520" type='application/pdf'></center>
<?php elseif ($type=='specialexternalgcreport'): ?>
    <center><embed src='../reports/externalReport/gcrspecialpdf.pdf' width="940" height="520" type='application/pdf'></center>
<?php endif; ?>




