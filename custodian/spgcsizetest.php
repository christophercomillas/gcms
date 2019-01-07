<?php
	session_start();
	include '../function.php';
    if($_SESSION['gc_usertype']!='4'){
        header('location:../index.php?action=logout');
    } 

	function getBarcode($bnumber)
	{
		$barcode = "";
		$attributes = "?filetype=PNG&dpi=72&scale=2&rotation=0&font_family=Arial.ttf&font_size=14&text=" . $bnumber . "&thickness=30&code=BCGcode128";
		$barcode = "<img src='html/image.php". $attributes ."' />";
		return $barcode;		
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Special External GC Printing</title>
	<!-- <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css"> -->
	<style type="text/css">
/*		@page {
		  size: A4;
		  margin: 0;
		}*/
		@media print and (color) {
		   * {
		      	-webkit-print-color-adjust: exact;
		      	print-color-adjust: exact;
				margin: 0px !important;
				padding: 0px !important;
		   	}
			.printEl{
		        width: 8.5in;
		        height: 14in;
			}
		}
		body{
			margin: 0px !important;
			padding: 0px !important;
		}
		
		.container{
			margin: 5px;
		}

		div.gcbg{
			border: 1px solid #000;
			/*background-image: url("gc/spgc.jpg") !important;*/
			background-size: contain;
			height: 50px !important;
			width: 50px !important;
			/*background-size: 555px !important;*/
			background-repeat: no-repeat !important ;
			position: relative;
			/*border:#000 solid 1px;*/
			margin-bottom: 10px;
		}

		div.gcbg1{
			border: 1px solid #000;
			/*background-image: url("gc/spgc.jpg") !important;*/
			background-size: contain;
			height: 100px !important;
			width: 100px !important;
			/*background-size: 555px !important;*/
			background-repeat: no-repeat !important ;
			position: relative;
			/*border:#000 solid 1px;*/
			margin-bottom: 10px;
		}

		div.gcbg2{
			border: 1px solid #000;
			/*background-image: url("gc/spgc.jpg") !important;*/
			background-size: contain;
			height: 200px !important;
			width: 200px !important;
			/*background-size: 555px !important;*/
			background-repeat: no-repeat !important ;
			position: relative;
			/*border:#000 solid 1px;*/
			margin-bottom: 10px;
		}

		div.gcbg3{
			border: 1px solid #000;
			/*background-image: url("gc/spgc.jpg") !important;*/
			background-size: contain;
			height: 20px !important;
			width: 20px !important;
			/*background-size: 555px !important;*/
			background-repeat: no-repeat !important ;
			position: relative;
			/*border:#000 solid 1px;*/
			margin-bottom: 10px;
		}

		div.gcbg4{
			border: 1px solid #000;
			/*background-image: url("gc/spgc.jpg") !important;*/
			background-size: contain;
			height: 300px !important;
			width: 300px !important;
			/*background-size: 555px !important;*/
			background-repeat: no-repeat !important ;
			position: relative;
			/*border:#000 solid 1px;*/
			margin-bottom: 10px;
		}

		div.gcbg5{
			border: 1px solid #000;
			/*background-image: url("gc/spgc.jpg") !important;*/
			background-size: contain;
			height: 10px !important;
			width: 10px !important;
			/*background-size: 555px !important;*/
			background-repeat: no-repeat !important ;
			position: relative;
			/*border:#000 solid 1px;*/
			margin-bottom: 10px;
		}

		div.row{
			margin-bottom: 200px !important;
		}

		div.barcode{
			position: absolute;
			left: 594px;
			top: 100px;
			font-family: tahoma;
			font-size: 12px;
			font-weight: bold;
		}

		div.barcode img{
			height: 50px;
			-webkit-transform: rotate(-90deg);
			-moz-transform: rotate(-90deg);
			-o-transform: rotate(-90deg);
			-ms-transform: rotate(-90deg);
			transform: rotate(-90deg);
		}

		div.date{
			position: absolute;
			left: 500px;
			top: 20px;
			font-family: tahoma;
			font-size: 10px;
			font-weight: bold;
		}

		div.customer{
			position: absolute;
			left: 76px;
			top: 80px;
			font-family: tahoma;
			font-size: 14px;
			font-weight: bold;
			width: 360px;
			text-align: center;
		}

		div.amount{
			position: absolute;
			left: 510px;
			top: 50px;
			font-family: tahoma;
			font-size: 12px;
			font-weight: bold;
			width: 110px;
			text-align: center;
		}

		div.amountwords{
			position: absolute;
			left: 66px;
			top: 114px;
			font-family: tahoma;
			font-size: 12px;
			font-weight: bold;
			width: 370px;
			text-align: center;
		}

		div.accountname{
			position: absolute;
		    top: 16px;
		    font-size: 10px;
		    font-weight: bold;
		    left: 16px;
		    font-family: tahoma;
		}

	</style>	
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="printEl">

				10px
				<div class="gcbg5">	
				</div>
				20px
				<div class="gcbg3">	
				</div>
				50px
				<div class="gcbg">		
				</div>
				100px
				<div class="gcbg1">	
				</div>
				200px
				<div class="gcbg2">	
				</div>
				300px
				<div class="gcbg4">	
				</div>
			</div>
		</div>
	</div>

<!-- <script type="text/javascript" src="../assets/js/jquery-2.1.3.min.js"></script>
<script type="text/javascript" src="../assets/js/bootstrap.min.js"></script> -->
<script type="text/javascript">

</script>
</body>
</html>