<?php
session_start();
include_once "../function-cashier.php";	
?>
<div class="row">
	<div class="col-xs-12 form-horizontal">
		<button class="btn btn-block btn-success" id="linedis" onclick="linediscount()">Line Discount</button>
		<button class="btn btn-block btn-success" id="docdis" onclick="trandis()">Transaction Discount</button>
		<button class="btn btn-block btn-success" id="removedisline" onclick="removealldiscline()">Remove All Discount (Line)</button>
		<button class="btn btn-block btn-success" id="removedisline" onclick="removedocdisc()">Remove Document Discount</button>
	</div>
</div>