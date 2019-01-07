 <?php
    session_start();
    include '../function.php';
    require 'header.php';
?>

<?php require '../menu.php'; ?>
    <div class="main fluid">


    </div>
<?php include 'jscripts.php'; ?>
<!--<script type="text/javascript" src="../assets/js/cus.js"></script>-->
<script type="text/javascript">
    // Create date from input value
	var inputDate = new Date("12/4/2017");

	// Get today's date
	var todaysDate = new Date();

	// call setHours to take the time out of the comparison
	if(inputDate.setHours(0,0,0,0) == todaysDate.setHours(0,0,0,0)) 
	{
	    // Date equals today's date
	    swal({
			title: "Good Eve Maam!",
			text: "Ok na maam palihug na lang ko EOD iusa na lang na EOD kay diha man gud problema sa server pagka sabado mao to ang textfile didto sa lain nga folder na save.",
			html: true
		});
	}
    $('div.main.fluid').load('../templates/it.php?page=indexpage');
</script>
<?php include 'footer.php' ?>