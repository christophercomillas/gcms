<?php 
	
	if(!isset($_GET['id']))
	{
		exit();
	}
	else
	{
		$id = $_GET['id'];
?>
<center><embed src='../reports/custodian_receiving/csr<?php echo $id; ?>.pdf' width="940" height="520" type='application/pdf'></center>

<?php 
	}
?>