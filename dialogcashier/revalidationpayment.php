<?php 
session_start();
include_once '../function-cashier.php';
	$query_tot = $link->query(
		"SELECT 
			SUM(temp_reval.treval_charge) as totcharge,
			COUNT(temp_reval.treval_charge) as gccount
		FROM 
		 	temp_reval 
		WHERE 
			temp_reval.treval_by='".$_SESSION['gccashier_id']."'
		AND
			temp_reval.treval_store='".$_SESSION['gccashier_store']."'
	");

	if($query_tot)
	{
		$row = $query_tot->fetch_object();
		$total = $row->totcharge;
		$gccount = $row->gccount;
	}
?>

<div class="row">
	<div class="col-xs-12">
		<form class="form-horizontal" action="../ajax-cashier.php?request=payment" id="fpaymentcash">
<!-- 			<div class="col-xs-6">			
			</div>
			<div class="col-xs-6">
				<div class="form-group">
					<label class="control-label col-xs-5 lbl-c amtduelbl">Total Charge</label>
					<div class="col-xs-7">
						<input type="text" class="form form-control inpmed amtdue red" readonly="readonly" value="<?php echo $total; ?>">
					</div>
				</div>
			</div> -->
			<div class="form-group frmcash">
				<label class="control-label col-xs-5 lbl-c amtduelbl">Total Charge</label>
				<div class="col-xs-6">
					<!-- <input data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false,'placeholder': '0.00'" type="text" name="cashpayment" class="form form-control" id="paymentcash" maxlength="14" value="0"> -->
					<input type="text" class="form form-control inpmed amtdue red" readonly="readonly" value="<?php echo $total; ?>">
				</div>
			</div>
			<div class="form-group frmcash">
				<label class="control-label col-xs-5 lbl-c lblamtnder">Amount Tender</label>
				<div class="col-xs-6">
					<!-- <input data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false,'placeholder': '0.00'" type="text" name="cashpayment" class="form form-control" id="paymentcash" maxlength="14" value="0"> -->
					<input type="text" id="paymentcash" class="form form-control paycash"  data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0'">
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
  //$('.amtdue').val(amt);      
</script>