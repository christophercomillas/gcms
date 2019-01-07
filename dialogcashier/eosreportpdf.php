<?php
	session_start();
	$storeid = $_SESSION['gccashier_store'];
?>
<center><iframe id="iframeId" src="../reports/pos/eos_report<?php echo $storeid; ?>.pdf" width="400" height="400" type='application/pdf'>
</iframe></center>
<script>

	callPrint('iframeId');

	// function print(url)
	// {
	// 	alert(url);
	//     var _this = this,
	//         iframeId = 'iframeprint',
	//         $iframe = $('iframe#iframeprint');
	//     $iframe.attr('src', url);

	//     $iframe.load(function() {
	//         _this.callPrint(iframeId);
	//     });
	// }

	function callPrint(iframeId) {
		var PDF = document.getElementById(iframeId);
		PDF.focus();
		PDF.contentWindow.print();
	}

</script>