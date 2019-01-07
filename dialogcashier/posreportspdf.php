<?php 
session_start();

if(isset($_GET['d1']) && isset($_GET['d2']))
{
	$d1 = $_GET['d1'];
	$d2 = $_GET['d2'];
	echo $d1;
}
else 
{
	exit();
}
?>

<script>
// window.location = "<?php echo 'index.php?id='.$id; ?>";
</script>
