<?php 
	
	if(!isset($_GET['id']))
	{
		exit();
	}
	else
	{
		$id = $_GET['id'];
?>
<div class="row" style="margin-bottom:12px;">
	<div class="col-xs-12">
		<div class="btn btn-info btn-info pull-right">
            <a href="../marketing/reqexcel.php?requis=<?php echo $id; ?>"><i class="fa fa-download"></i> Export (Excel)</a>
        </div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<center><embed src='../reports/marketing/requis<?php echo $id; ?>.pdf' width="940" height="520" type='application/pdf'></center>
	</div>
</div>
<?php 
	}
?>