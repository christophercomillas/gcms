<?php 
	session_start();
?>
<!-- <center><embed id="iframeId" src='../reports/pos/pos_report.pdf' width="400" height="500" type='application/pdf'></center> -->
<center><iframe id="iframeId" src="../reports/pos/gc_report<?php echo $_SESSION['gccashier_store']; ?>.pdf" width="400" height="400" type='application/pdf'>
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
		$('.printbut').focus();
	}
</script>