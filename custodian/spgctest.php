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

	if(isset($_GET['type']))
	{
		$type = $_GET['type'];
		if(trim($type)==='')
		{
			exit();
		}

		switch ($type) {
			case 'byrequestid':
				if(isset($_GET['id']) && trim($_GET['id'])!='')
				{
					//check if request id exist
					$id = $_GET['id'];

					if(checkifExist2($link,'spexgc_num','special_external_gcrequest','spexgc_num','spexgc_status',$id,'approved'))
					{
						$gcs = getSpecialExteranalByRequestNumber($link,$id);
					}
					else 
					{
						echo 'Request # dont exist or Request needs approval <a href="index.php">Back</a>';
						exit();						
					}		

				}
				else 
				{
					echo 'Please input Special External Gift Check Request #. <a href="index.php">Back</a>';
					exit();
				}
				break;
			case 'byrange':
				if(isset($_GET['bstart']) && isset($_GET['bend']) && trim($_GET['bstart']!='' && trim($_GET['bend'])))
				{
					//check if barcode exist
					$bstart = $_GET['bstart'];
					$bend = $_GET['bend'];

					if($bstart<$bend)
					{
						//check if barcode exist
						$bstartexist = checkIfExist($link,'spexgcemp_barcode','special_external_gcrequest_emp_assign','spexgcemp_barcode',$bstart);
						$bendexist = checkIfExist($link,'spexgcemp_barcode','special_external_gcrequest_emp_assign','spexgcemp_barcode',$bend);
						if(!$bstartexist || !$bendexist)
						{
							echo 'Barcode dont exist.<a href="index.php">Back</a>';
							exit();								
						}
						else 
						{
							$gcs = getSpecialExteranalByRange($link,$bstart,$bend);
						}

					}
					else
					{
						echo 'Barcode start must lesser than Barcode end.<a href="index.php">Back</a>';
						exit();							
					}
				}
				else 
				{
					echo 'Please input barcode start and barcode end numbers.<a href="index.php">Back</a>';
					exit();		
				}
				break;
			case 'bybarcode':
				if(isset($_GET['barcode']) && trim($_GET['barcode'])!='')
				{			

					$barcode = $_GET['barcode'];
					$barcodeExist = checkIfExist($link,'spexgcemp_barcode','special_external_gcrequest_emp_assign','spexgcemp_barcode',$barcode);
					if(!$barcodeExist)
					{
						echo 'Barcode dont exist.<a href="index.php">Back</a>';
						exit();								
					}
					else 
					{
						$gcs = getSpecialExteranalByBarcode($link,$barcode);
					}
				}
				else 
				{
					echo 'Please input barcode.<a href="index.php">Back</a>';
					exit();	
				}
				break;
			default:
				exit();
				break;
		}
	}
	else 
	{
		exit();
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
			height: 278px !important;
			width: 720px !important;
			/*background-size: 555px !important;*/
			background-repeat: no-repeat !important ;
			position: relative;
			/*border:#000 solid 1px;*/
			margin-bottom: 25px;
		}

		div.gcbg1{
			border: 1px solid #000;
			/*background-image: url("gc/spgc.jpg") !important;*/
			background-size: contain;
			height: 278px !important;
			width: 720px !important;
			/*background-size: 555px !important;*/
			background-repeat: no-repeat !important ;
			position: relative;
			/*border:#000 solid 1px;*/
			margin-bottom: 25px;
		}

		div.gcbg2{
			border: 1px solid #000;
			/*background-image: url("gc/spgc.jpg") !important;*/
			background-size: contain;
			height: 278px !important;
			width: 720px !important;
			/*background-size: 555px !important;*/
			background-repeat: no-repeat !important ;
			position: relative;
			/*border:#000 solid 1px;*/
			margin-bottom: 25px;
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
		    top: 20px;
		    font-size: 11px;
		    font-weight: bold;
		    left: 16px;
		    font-family: tahoma;
		}

	</style>	
</head>
<body>
	<div class="container">
		<div class="row">

			<?php
				$trd = 0;
				foreach ($gcs as $gc): 
					if($trd==4)
					{
						echo '<div style="margin-bottom:180px;"></div>';
						$trd = 0;
					}
				?>
				<div class="gcbg">		

				</div>
			<?php
				$trd++; 
				endforeach; ?>
<!-- 			<div class="gcbg">		
				<div class="barcode"><?php echo getBarcode(904); ?></div>
				<div class="date">September 14, 2016</div>
				<div class="customer">
					Sanders, Ben 
				</div>
				<div class="amount">
					**121.00
				</div>
				<div class="amountwords">
					One Hundred Twenty One Pesos Only
				</div>
			</div> -->

		</div>
	</div>

<!-- <script type="text/javascript" src="../assets/js/jquery-2.1.3.min.js"></script>
<script type="text/javascript" src="../assets/js/bootstrap.min.js"></script> -->
<script type="text/javascript">

</script>
</body>
</html>