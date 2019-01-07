<?php
	if(isset($_GET['msg']))
	{
		$msgtype = $_GET['msg'];
		if(isset($_GET['barcodenum']))
			$barcodenum = $_GET['barcodenum'];
		switch ($msgtype) {
			case '1':
				$msg = 'Are you sure you want to remove transaction discount?';
				break;

			case '2':
				$msg = 'Are you sure you want to remove all line discount?';
				break;

			case '3':
				$msg = 'Are you sure you want to void Barcode # '.$barcodenum.' ?';
				break;
			
			default:
				# code...
				break;
		}
	}
?>
<div class="row">
	<div class="col-md-12">	
		<input type="hidden" name="flag" class="flag" value="1">
        <?php echo $msg; ?>
    </div>                                                                       
</div>
