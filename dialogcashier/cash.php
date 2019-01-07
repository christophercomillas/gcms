<?php 
session_start();
include_once '../function-cashier.php';
//$subtotal = checkTotal($link);
$subtotal = checkTotalwithoutLineDiscount($link);
$linedisc = linediscountTotal($link);
$docdisc = docdiscount($link);
$amtdue = $subtotal - ($docdisc + $linedisc);
?>

<div class="row">
	<div class="col-xs-12">
		<form class="form-horizontal" action="../ajax-cashier.php?request=cashpayment" id="fpaymentcash">
			<div class="col-xs-6"> 
				<input type="hidden" name="store" value="1">
				<div class="form-group fg-nobot">
					<label class="control-label col-xs-5 lbl-c amtduelbl">Subtotal</label>
					<div class="col-xs-7">
						<input type="text" class="form form-control inpmed stotal" tabindex="-1" readonly="readonly" value="<?php echo number_format($subtotal,2); ?>">
					</div>
				</div>
				<div class="form-group fg-nobot">
					<label class="control-label col-xs-5 lbl-c amtduelbl">Line Disc</label>
					<div class="col-xs-7">
						<input type="text" class="form form-control inpmed ldisc" tabindex="-1" readonly="readonly" value="<?php echo number_format($linedisc,2); ?>">
					</div>
				</div>
			</div>
			<div class="col-xs-6">
				<div class="form-group fg-nobot">
					<label class="control-label col-xs-5 lbl-c amtduelbl">Subtotal Disc</label>
					<div class="col-xs-7">
						<input type="text" class="form form-control inpmed docdisc" tabindex="-1" readonly="readonly" value="<?php echo number_format($docdisc,2); ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-xs-5 lbl-c amtduelbl">Amt Due</label>
					<div class="col-xs-7">
						<input type="text" class="form form-control inpmed inpred amtdue" tabindex="-1" readonly="readonly" value="<?php echo number_format($amtdue,2); ?>">
					</div>
				</div>
			</div>
			<div class="form-group frmcash">
				<label class="control-label col-xs-6 lbl-c lblamtnder">Amount Tender</label>
				<div class="col-xs-6">
					<!-- <input data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false,'placeholder': '0.00'" type="text" name="cashpayment" class="form form-control" id="paymentcash" maxlength="14" value="0"> -->
					<input type="text" id="paymentcash" class="form form-control paycash"  data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false">
				</div>
			</div>
		</form>
	<div class="responsecash">
	</div>		
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="col-xs-4">
			<button class="btn btn-default btn-block btn-d" onclick="d(100.00)">100.00</button>
		</div>
		<div class="col-xs-4">
			<button class="btn btn-default btn-block btn-d" onclick="d(200.00)">200.00</button>
		</div>
		<div class="col-xs-4">
			<button class="btn btn-default btn-block btn-d" onclick="d(500.00)">500.00</button>
		</div>
	</div>
</div>
<div class="row adjustop">
	<div class="col-xs-12">
		<div class="col-xs-4">
			<button class="btn btn-default btn-block btn-d" onclick="d(1000.00)">1,000.00</button>
		</div>
		<div class="col-xs-4">
			<button class="btn btn-default btn-block btn-d" onclick="d(5000.00)">5,000.00</button>
		</div>
		<div class="col-xs-4">
			<button class="btn btn-default btn-block btn-d" onclick="d(10000.00)">10,000.00</button>
		</div>
	</div>
</div>
<div class="row adjustop">
	<div class="col-xs-12">
		<div class="col-xs-4">
			
		</div>
		<div class="col-xs-4">
			
		</div>
		<div class="col-xs-4">
			<button class="btn btn-danger btn-block btn-d" onclick="clearcash()">Clear</button>
		</div>
	</div>
</div>

<script>              
  $('#paymentcash').inputmask();
  $('#paymentcash').select();     
</script>