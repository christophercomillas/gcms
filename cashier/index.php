<?php 
	session_start();
	$tablerows = 10;
	require_once('../function.php');
	require_once('../function-cashier.php');
	if(!isLoggedInCashier()){
		header('location:login.php');
	}

	$gctemp = getTempBarcodesByCashier($link,$_SESSION['gccashier_id']);
	$gctemp_numrows = count($gctemp);
	if($gctemp_numrows > 10)
	{
		$tablerows = 0;
	}
	else
	{
		$tablerows = $tablerows - $gctemp_numrows;
	}

	$stotal = checkTotal($link);
	$subtot	= checkTotalwithoutLineDiscount($link);
	$line = linediscountTotal($link);
	$docdisc = docdiscount($link);
	$docdiscount = is_null($docdisc) ? 0.00 : number_format($docdisc,2);
	$total = $stotal - $docdisc;  		
	$amtdue = is_null($total) ? 0.00 : number_format($total,2);
	$linedisc = is_null($line) ? 0.00 : number_format($line,2);
	$noitems = numRows($link,'temp_sales','ts_cashier_id',$_SESSION["gccashier_id"]);
  	$subtotal = is_null($stotal) ? 0.00 : number_format($stotal,2);

  	$rgc = receiptItemsByCashier($link,$_SESSION['gccashier_id']);

  	//get reval payment
  	// $select = 'revalidate_price';
  	// $revalpayment = getSelectedData($link,'cashiering_options',$select,1,'','');

  	// $rpayment = $revalpayment->revalidate_price;
  	$rpayment = getField($link,'app_settingvalue','app_settings','app_tablename','revalidation_charge_percent');

  	$sc_setting = getField($link,'app_settingvalue','app_settings','app_tablename','pos_show_service_charge');

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>GC POS</title>	
	<link rel="shortcut icon" href="../assets/images/favicon.ico" type="image/icon">
	<link rel="stylesheet" type="text/css" href="../assets/css/bootstrap-yeti.css">
	<link href="../assets/css/jquery.dataTables.css" rel="stylesheet">
	<link href="../assets/css/bootstrap-datepicker.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../assets/css/override.css">	
	<style media="print" type="text/css">
		@media print
		{
			body * { visibility: hidden; }
			#print-receipt * { visibility: visible; }
			#print-receipt { display:block; position: absolute; top: 40px; left: 30px; }
			#xprintreports * { visibility: visible; }
		}
	</style> 
</head>
<body>
<?php echo @$display_message; ?>
<div id="print-receipt">
<h3 class="store-name">
  <?php 
    echo getField($link,'store_name','stores','store_id',$_SESSION["gccashier_store"]);
  ?>
 </h3>
 <p class="receipt-slo">Owned and Managed by ASC</p>
<p class="gctitle">Gift Check</p>
<h4 class="official">Received Document</h4>
<h5>Date: <?php echo _dateFormat($todays_date); ?></h5>
<h5>Time: <?php echo _timeFormat($todays_time); ?></h5>
<h3 class="transactnum">Transaction No. <span></span></h3>
<div class="row items">
  <div class="col-xs-12 receipt-items">
  	<table class="table resibo">
	  	<thead>
	  		<th>Barcode No</th>
	  		<th class="receipt_items">Price</th>
	  	</thead>
	  	<tbody>
	  		<?php foreach($rgc as $r): ?>
	  			<tr>
		  			<td><?php echo $r->ts_barcode_no; ?></td>
		  			<td class="receipt_items"><?php echo number_format($r->denomination,2); ?></td>
	  			</tr>
	  		<?php endforeach; ?>
	  	</tbody>
  	</table>
  </div>
  <div class="col-xs-12 receipt-footer">
  </div>
  <div class="col-xs-12 cashiername">
    <?php echo ucwords($_SESSION['gccashier_fullname']); ?>
  </div>
  <div class="col-xs-12 cashiersig">
    <span class="cashiersigspan">Cashier's Signature</span>
  </div>
  <div class="col-xs-12 receipt-msg">
    Thank You For Shopping!!!<br />
    Please Come Again
  </div>
</div>
</div>
<div id="xprintreports">    
</div>
<div class="container86">
	<div class="content-right">
		<div class="cashier-mode <?php echo isset($_SESSION['gc_super_id']) ? 'hidediv' : ''; ?>">
			<button class="btns btnborderbot f1" onclick="f1();">
				<span class="btnkey">[F1]</span> <span class="btnames">Payment Mode</span>
				<span class="nextrow">></span>
			</button>
			<button class="btns btnborderbot f2" onclick="f2();">
				<span class="btnkey">[F2]</span> <span class="btnames">Void Line</span>
			</button>
			<button class="btns btnborderbot f3" onclick="f3();">
				<span class="btnkey">[F3]</span> <span class="btnames">Supervisor Menu</span>
				<span class="nextrow">></span>
			<button class="btns btnborderbot f4" onclick="f4();">
				<span class="btnkey">[F4]</span> <span class="btnames">Discount</span>
				<span class="nextrow">></span>
			</button>	
			<button class="btns btnborderbot f5" onclick="f5();">
				<span class="btnkey">[F5]</span> <span class="btnames">Other Income</span>
				<span class="nextrow">></span>
			</button>
			<button class="btns btnborderbot f6" onclick="f6();">
				<span class="btnkey">[F6]</span> <span class="btnames">Reports</span>
				<span class="nextrow">></span>
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns f8" onclick="f8();">
				<span class="btnkey">[F8]</span> <span class="btnames">Logout</span>
			</button>
		</div>
		<div class="manager-mode <?php echo isset($_SESSION['gc_super_id']) ? '' : 'hidediv'; ?>">
			<button class="btns btnborderbot f1" onclick="f1();">
				<span class="btnkey">[F1]</span> <span class="btnames">Lookup</span>
			</button>	
			<button class="btns btnborderbot f2" onclick="f2();">
				<span class="btnkey">[F2]</span> <span class="btnames">Void All</span>
			</button>	
			<button class="btns btnborderbot f3" onclick="f3();">
				<span class="btnkey">[F3]</span> <span class="btnames">GC Refund</span>
				<span class="nextrow">></span>
			</button>
			<button class="btns btnborderbot f4" onclick="f4();">
				<span class="btnkey">[F4]</span> <span class="btnames">Discount</span>
				<span class="nextrow">></span>
			</button>			
			<button class="btns btnborderbot f5" onclick="f5();">
				<span class="btnkey">[F5]</span> <span class="btnames">Reports</span>
				<span class="nextrow">></span>
			</button>	
			<button class="btns btnborderbot f6" onclick="f6();">
				<span class="btnkey">[F6]</span> <span class="btnames">End of Day</span>
			</button>	
			<button class="btns btnborderbot f7" onclick="f7();">
				<span class="btnkey">[F7]</span> <span class="btnames">Shortage / Overage </span>
			</button>
			<button class="btns btnborderbot f8" onclick="f8();">
				<span class="btnkey">[F8]</span> <span class="btnames">Supervisor Logout</span>
			</button>	
		</div>
		<div class="payment-mode">
			<button class="btns btnborderbot f1" onclick="f1();">
				<span class="btnkey">[F1]</span> <span class="btnames">Cash</span>
			</button>	
			<button class="btns btnborderbot f2" onclick="f2();">
				<span class="btnkey">[F2]</span> <span class="btnames">Credit Card</span>
			</button>	
			<button class="btns btnborderbot f3" onclick="f3();">
				<span class="btnkey">[F3]</span> <span class="btnames">H.O. (JV)</span>
			</button>	
			<button class="btns btnborderbot f4" onclick="f4();">
				<span class="btnkey">[F4]</span> <span class="btnames">Subs. Admin</span>
			</button>	
			<button class="btns btnborderbot f5" onclick="f5();">
				<span class="btnkey">[F5]</span> <span class="btnames">Back</span>
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
		</div>
		<div class="otherincome-mode">
			<button class="btns btnborderbot f1" onclick="f1();">
				<span class="btnkey">[F1]</span> <span class="btnames">GC Revalidation</span>
			</button>	
			<button class="btns btnborderbot f2" onclick="f2();">
				<span class="btnkey">[F2]</span> <span class="btnames">Back</span>
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>			
			<button class="btns btnborderbot">
			</button>
		</div>
		<div class="reports">
			<button class="btns btnborderbot f1" onclick="f1();">
				<span class="btnkey">[F1]</span> <span class="btnames">Terminal Report</span>
			</button>		
			<button class="btns btnborderbot f2" onclick="f2();">
				<span class="btnkey">[F2]</span> <span class="btnames">Cashier Report</span>
			</button>	
			<button class="btns btnborderbot f3" onclick="f3();">
				<span class="btnkey">[F3]</span> <span class="btnames">GC Sales Report</span>
			</button>	
			<button class="btns btnborderbot f4" onclick="f4();">
				<span class="btnkey">[F4]</span> <span class="btnames">Back</span>
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
		</div>
		<div class="discounts">
			<button class="btns btnborderbot f1" onclick="f1();">
				<span class="btnkey">[F1]</span> <span class="btnames">Line Disc</span>
			</button>
			<button class="btns btnborderbot f2" onclick="f2();">
				<span class="btnkey">[F2]</span> <span class="btnames">Subtotal <br />Discount</span>
			</button>	
			<button class="btns btnborderbot f3" onclick="f3();">
				<span class="btnkey">[F3]</span> <span class="btnames">Remove <br />Line Discount</span>
			</button>	
			<button class="btns btnborderbot f4" onclick="f4();">
				<span class="btnkey">[F4]</span> <span class="btnames">Remove <br />SubDiscount</span>
			</button>	
			<button class="btns btnborderbot f5" onclick="f5();">
				<span class="btnkey">[F5]</span> <span class="btnames">Back</span>
			</button>	
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>		
		</div>
		<div class="revalidation">
			<button class="btns btnborderbot f1" onclick="f1();">
				<span class="btnkey">[F1]</span> <span class="btnames">Cash</span>
			</button>	
			<button class="btns btnborderbot f2" onclick="f2();">
				<span class="btnkey">[F2]</span> <span class="btnames">Void Line</span>
			</button>
			<button class="btns btnborderbot f3" onclick="f3();">
				<span class="btnkey">[F3]</span> <span class="btnames">Back</span>
			</button>	
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>			
			<button class="btns btnborderbot">
			</button>			
		</div>
		<div class="returngc">
			<button class="btns btnborderbot f1" onclick="f1();">
				<span class="btnkey">[F1]</span> <span class="btnames">Refund</span>
			</button>	
<!-- 			<button class="btns btnborderbot f2" onclick="f2();">
				<span class="btnkey">[F2]</span> <span class="btnames">Service Fee</span>
			</button>	 -->
			<button class="btns btnborderbot f2" onclick="f2();">
				<span class="btnkey">[F2]</span> <span class="btnames">Void Line</span>
			</button>
			<button class="btns btnborderbot f3" onclick="f3();">
				<span class="btnkey">[F3]</span> <span class="btnames">Back</span>
			</button>
			<button class="btns btnborderbot">
			</button>	
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>			
			<button class="btns btnborderbot">
			</button>
		</div>
		<div class="reports-cahshier">
			<button class="btns btnborderbot f1" onclick="f1();">
				<span class="btnkey">[F1]</span> <span class="btnames">Terminal Report</span>
			</button>		
			<button class="btns btnborderbot f2" onclick="f2();">
				<span class="btnkey">[F2]</span> <span class="btnames">Cashier Report</span>
			</button>	
			<button class="btns btnborderbot f3" onclick="f3();">
				<span class="btnkey">[F3]</span> <span class="btnames">GC Sales Report</span>
			</button>	
			<button class="btns btnborderbot f4" onclick="f4();">
				<span class="btnkey">[F4]</span> <span class="btnames">Shortage / Overage</span>
			</button>
			<button class="btns btnborderbot f5" onclick="f5();">
				<span class="btnkey">[F5]</span> <span class="btnames">End of Day</span>
			</button>
			<button class="btns btnborderbot f6" onclick="f6();">
				<span class="btnkey">[F6]</span> <span class="btnames">Back</span>
			</button>
			<button class="btns btnborderbot">
			</button>
			<button class="btns btnborderbot">
			</button>			
		</div>		
	</div>
	<div class="content-left">
		<div class="content-sales">
			<div class="containerscan">
			<label class="labelscan">GC Barcode #</label><input type="text" data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '','allowMinus':false" class="input-lg scan" name="data" id="numOnly" autocomplete="off" maxlength="13" autofocus <?php echo isset($_SESSION['gc_super_id']) ? 'disabled="disabled"' : ''; ?> />  
			</div> 
	        <input class="form form-control input-sm" id="posmsg" readonly="readonly" style="display:none;" />    
			<div class="tablecontainer">
			<table class="tablef" id="gcsales">
				<thead>
					<tr>
						<th class="barcodeth">GC Barcode #</th>
						<th class="typeth">Type</th>
						<th class="denomth">Denom</th>
						<th class="disctypeth">Disc. Type</th>
						<th class="discprcntth">Disc (%)</th>
						<th class="discamtth">Disc Amt</th>
						<th class="netamtth">Net Amt</th>
					</tr>
				</thead>
			</table>
			<div class="content">
				<div class="scrollable">
					<div class="item-list">
						<table class="tablef">
							<tbody class="_barcodes">
									<?php foreach ($gctemp as $key => $value): ?>
										<tr>
											<td class="btnsidetd"><button class="btnside" onclick="voidbyline(<?php echo $value['barcode']; ?>);">></button></td>
											<td class="barcodetd"><?php echo $value['barcode']; ?></td>
											<td class="typetd"><?php echo $value['type']; ?></td>
											<td class="denomtd"><?php echo number_format($value['denomination'],2); ?></td>
											<td class="disctypetd"><?php echo $value['disctype']; ?></td>
											<td class="discprcnttd"><?php echo $value['percent']; ?></td>
											<td class="discamttd"><?php echo $value['discamount']; ?></td>
											<td class="netamttd"><?php echo number_format($value['netamt'],2); ?></td>
										</tr>								
									<?php endforeach; ?>
									<?php for($x=0; $x<$tablerows; $x++): ?>
										<tr>
											<td class="btnsidetd"></td>
											<td class="barcodetd"></td>
											<td class="typetd"></td>
											<td class="denomtd"></td>
											<td class="disctypetd"></td>
											<td class="discprcnttd"></td>
											<td class="discamttd"></td>
											<td class="netamttd"></td>
										</tr>
									<?php endfor; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			</div>
			<div class="transacdetails">
				<div class="tdetials-right">
				<input type="text" class="response-msg msgsales" readonly="readonly" value="" tabIndex="-1">
				<h4 class="amt-due">Amount Due</h4>
				<input type="text" class="inp-amtdue _cashier_total" readonly="readonly" value="<?php echo $amtdue; ?>" tabIndex="-1">

				</div>
				<div class="tdetails-left">
					<input type="hidden" name="sbtotal" value="<?php echo $stotal; ?>">
					<input type="hidden" name="docdiscount" value="0.00">
					<input type="hidden" name="ocharge" value="0.00">
					<input type="hidden" name="tax" value="0.00">
					<input type="hidden" name="linediscount" value="0.00">
					<table class="tabledetails">
						<tbody>
							<tr>
								<td>Subtotal:. . . . . . . . .  </td>
								<td><input type="text" class="amts sbtotal" value="<?php echo number_format($subtot,2); ?>" readonly="readonly" tabIndex="-1"></td>
							</tr>
							<tr>
								<td>Line Discount:. . . . </td>
								<td><input type="text" class="amts linediscount" value="<?php echo $linedisc; ?>" readonly="readonly" tabIndex="-1"></td>
							</tr>
							<tr>
								<td>Subtotal Discount:.. </td>
								<td><input type="text" class="amts docdiscount" value="<?php echo $docdiscount; ?>" readonly="readonly" tabIndex="-1"></td>
							</tr>
							<tr>
								<td>Customer Disc:. . . . .  </td>
								<td><input type="text" class="amts cdisc" value="0.00" readonly="readonly" tabIndex="-1"></td>
							</tr>
							<tr>
								<td>Tax:. . . . . . . . . . . . .  </td>
								<td><input type="text" class="amts tax" value="0.00" readonly="readonly" tabIndex="-1"></td>
							</tr>
							<tr>
								<td>No. of Items:. . . . . .  </td>
								<td><input type="text" class="amts amts-b noitems" value="<?php echo $noitems; ?>" readonly="readonly" tabIndex="-1"></td>
							</tr>
						</tbody>				
					</table>
				</div>
			</div>
		</div>
		<div class="content-revalidate">
			<div class="containerscan">
				<label class="labelscan">GC Barcode #</label><input type="text" data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '','allowMinus':false" class="input-lg scan" name="revalidategc" id="numOnlyreval" autocomplete="off" maxlength="13" autofocus />  
			</div> 
			<table class="tablef" id="gcsales">
				<thead>
					<tr>
						<th class="barcodethrev">GC Barcode #</th>
						<th class="typethrev">Type</th>
						<th class="denomth">Denom</th>
						<th class="soldrelrev">Sold / Released</th>
						<th class="daterevth">Date Verified</th>
						<th class="vefpaymentth">Charge</th>
					</tr>
				</thead>
			</table>
			<div class="content">
				<div class="scrollable">
					<div class="item-list">
						<table class="tablef">
							<tbody class="_barcodesreval">								
								<?php
									$revalrows = 10; 
									for($x=0; $x<$revalrows; $x++): ?>
									<tr>
										<td class="btnsidetdrev"></td>
										<td class="barcodetdrev"></td>
										<td class="typetdrev"></td>
										<td class="denomtdrev"></td>
										<td class="soldrelrevtdrev"></td>
										<td class="vefpaymenttdrev"></td>
										<td class="paymentrevtd"></td>
									</tr>
								<?php endfor; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="transacdetails">
				<div class="tdetials-right">
					<input type="text" class="response-msg msgreval" readonly="readonly" value="Change: " tabIndex="-1">
					<h4 class="amt-due">Total Charge</h4>
					<input type="text" class="inp-amtdue _cashier_totalreval" readonly="readonly" value="0.00" tabIndex="-1">				
				</div>
				<div class="tdetails-left">
					<h4 class="revaltitle">GC Revalidation</h4>
					<div class="alert alert-info alert-reval">
						Note: Revalidation fee is <?php echo $rpayment; ?> per GC.
					</div>
					<table class="tabledetails">
						<tbody>
							<tr>
								<td>
									<td>No. of Items:. . . . . .  </td>
									<td><input type="text" class="amts amts-b noitemsreval" value="0" readonly="readonly" tabIndex="-1"></td>									
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="content-returngc">
			<div class="containerscan">
				<label class="labelscan">GC Barcode #</label><input type="text" data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '','allowMinus':false" class="input-lg scan" name="inprefundgc" id="numOnlyreturn" autocomplete="off" maxlength="13" autofocus />  
			</div> 
			<table class="tablef" id="gcsales">
				<thead>
					<tr>
						<th class="barcodethref">GC Barcode #</th>
						<th class="typethref">Type</th>
						<th class="denomthref">Denom</th>
						<th class="soldrelref">Line Disc.</th>
						<th class="netamtth">Sub Disc.</th>
					</tr>
				</thead>
			</table>
			<div class="content">
				<div class="scrollable">
					<div class="item-list">
						<table class="tablef">
							<tbody class="_barcodesrefund">								
								<?php
									$revalrows = 10; 
									for($x=0; $x<$revalrows; $x++): ?>
									<tr>
										<td class="btnsidetdref"></td>
										<td class="barcodetdref"></td>
										<td class="typetdref"></td>
										<td class="denomtdref"></td>
										<td class="linediscref"></td>
										<td class="subdiscref"></td>
									</tr>
								<?php endfor; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="transacdetails">
				<div class="tdetials-right">
					<input type="text" class="response-msg msgrefund" readonly="readonly" value="" tabIndex="-1">
					<h4 class="amt-due">Total Refund</h4>
					<input type="text" class="inp-amtdue _cashier_totalrefund" readonly="readonly" value="0.00" tabIndex="-1">				
				</div>
				<div class="tdetails-left">
					<h4 class="revaltitle">GC Refund</h4>
					<table class="tabledetails">
						<tbody>
							<tr>
								<td>
									<td>Total Denom:. . . . . .  </td>
									<td><input type="text" class="amts amts totdenomref" value="0" readonly="readonly" tabIndex="-1"></td>									
								</td>
							</tr>
							<tr>
								<td>
									<td>Total Sub Disc:. . . .</td>
									<td><input type="text" class="amts amts totsubdiscref" value="0" readonly="readonly" tabIndex="-1"></td>									
								</td>
							</tr>
							<tr>
								<td>
									<td>Total Line Disc:. . . .</td>
									<td><input type="text" class="amts amts totlinedisref" value="0" readonly="readonly" tabIndex="-1"></td>									
								</td>
							</tr>
							<tr <?php if($sc_setting=='off'){ echo "style=display:none"; } ?>>
								<td>
									<td>Service Charge:. . . .</td>
									<td><input type="text" class="amts serviceref" value="0" readonly="readonly" tabIndex="-1"></td>									
								</td>
							</tr>
							<tr>
								<td>
									<td>No. of Items:. . . . . .  </td>
									<td><input type="text" class="amts amts amts-b noitemsref" value="0" readonly="readonly" tabIndex="-1"></td>									
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="footerpanel">
			<table class="tablefooter">
				<tr>
					<td class="store_footer1"></td>
					<td class="storename_footer1"></td>
					<td class="cashier_footer"></td>
					<td class="cashiername_footer1"></td>
					<td class="datetime"><?php echo _dateFormat($todays_date); ?> <span id="time"></span></td>
				</tr>
			</table>
			<table class="tablefooter">
				<tr>
					<td class="store_footer">Store:</td>
					<td class="storename_footer">
					<?php 
					    $store = $_SESSION['gccashier_store'];
					    echo getField($link,'store_name','stores','store_id',$store); 
					?>							
					</td>
					<td class="cashier_footer">Cashier:</td>
					<td class="cashiername_footer"><?php echo ucwords($_SESSION['gccashier_fullname']); ?></td>
					<td class="datetime">Supervisor Key <input id="managerkey" type="checkbox" disabled="" <?php echo isset($_SESSION['gc_super_id']) ? 'checked="checked"' : '' ?> /></td>
				</tr>
			</table>
		</div>
	</div>

</div>
<script src="../assets/js/jquery-1.10.2.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
<script src="../assets/js/bootstrap-modalb.js"></script>
<script src="../assets/js/jquery.inputmask.bundle.min.js"></script>
<script src="../assets/js/jquery.dataTables.js"></script>
<script src="../assets/js/shortcut.js"></script>
<!-- <script src="../assets/js/cashier-main.js"></script> -->
<script src="../assets/js/bootstrap-datepicker1.min.js"></script>
<script src="../assets/js/cashier-main1.js"></script>
</body>
</html>	