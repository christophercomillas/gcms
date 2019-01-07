<?php

session_start();

//unset($_SESSION['empAssign']);
//var_dump($_SESSION['empAssign']);
//print_r($_SESSION['empAssign'][36]);

if(isset($_GET['type']))
{
	$type = $_GET['type'];
}
else 
{
	exit();
}

if($type=='gcoftheday')
{
	$id = $_GET['id'];

?>
<center><iframe id="iframeId" src="../reports/pos/gcoftheday<?php echo $id; ?>.pdf" width="400" height="400" type='application/pdf'>
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


<?php


}


