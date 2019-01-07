<?php
session_start();
include_once "../function-cashier.php";
$creditcards = getCreditCardList($link);
//$subtotal = checkTotal($link);
$subtotal = checkTotalwithoutLineDiscount($link);
$linedisc = linediscountTotal($link);
$docdisc = docdiscount($link);
$amtdue = $subtotal - ($docdisc + $linedisc);
// $amtdue = $subtotal - $discount;
?>
<form id="ccard" action="../ajax-cashier.php?request=ccardpayment">
	<div class="row">
    <div class="col-xs-6 form-horizontal">
      <div class="form-group fg-nobot">
        <label class="control-label col-xs-5 lbl-c amtduelbl">Subtotal</label>
        <div class="col-xs-7">
          <input type="text" class="form form-control inpmed stotal" readonly="readonly" tabindex="-1" value="<?php echo number_format($subtotal,2); ?>">
        </div>
      </div>
      <div class="form-group fg-nobot">
        <label class="control-label col-xs-5 lbl-c amtduelbl">Line Disc</label>
        <div class="col-xs-7">
          <input type="text" class="form form-control inpmed ldisc" readonly="readonly" tabindex="-1" value="<?php echo number_format($linedisc,2); ?>">
        </div>
      </div>
      <div class="form-group fg-nobot">
        <label class="control-label col-xs-5 lbl-c inpmed amtduelbl">Subtotal Disc</label>
        <div class="col-xs-7">
          <input type="text" class="form form-control inpmed docdisc" readonly="readonly" tabindex="-1" value="<?php echo number_format($docdisc,2); ?>">
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-xs-5 lbl-c amtduelbl">Amount Due:</label>
        <div class="col-xs-7">
          <input type="text" class="form form-control inpmed inpred amtdue" readonly="readonly" tabindex="-1" value="<?php echo number_format($amtdue,2); ?>">
        </div>
      </div>
    </div>
		<div class="col-xs-6 ">
<!--       <div class="form-group">
        <label class="control-label lbl-c col-xs-5 amtduelbl ccamoutdue">Amt. Due</label>
        <div class="col-xs-7">
          <input type="text" class="form form-control amtdue" readonly="readonly">
        </div>
      </div> -->
			<div class="form-group">
				<label class="control-label lbl-c lbl-top col-xs-12">Credit Card</label>
			</div>
			<div class="form-group select-credit">
				<select name="credit" class="form-control inplar" id="credit">
					<option class="alloptions" value="">- SELECT -</option>
					<?php foreach ($creditcards as $key): ?>
						<option class="alloptions" value="<?php echo $key->ccard_id; ?>"><?php echo $key->ccard_name; ?></option>
					<?php endforeach ?>
				</select>
			</div>
			<div class="form-group">
				<label class="control-label lbl-c lbl-top col-xs-12">Card Number</label>
			</div>
			<div class="form-group select-credit">
				<input type="text" name="cardnumber" class="form-control inplar" id="cardnumber">
			</div>
			<div class="form-group">
				<label class="control-label lbl-c lbl-top col-xs-12">Card Expiration Date</label>
			</div>
			<div class="form-group select-credit">
				<input type="text" name="cardexpired" class="form form-control inplar" id="cardexpired">
			</div>
			<div class="form-group">
				<label class="control-label lbl-c lbl-top col-xs-12">Auth Code</label>
			</div>
			<div class="form-group select-credit">
				<input type="password" name="authcode" class="form form-control inplar" id="authcode">
			</div>
			<div class="response">
			</div>
		</div>
	</div>
</form>
<script>
  $('div.select-credit select#credit' ).focus();
  $('input[name=cardnumber]').inputmask("integer", { allowMinus: false});
  $("#cardexpired").inputmask("m/d/y",{ "placeholder": "mm/dd/yyyy" });
</script>