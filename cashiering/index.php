<?php 
  
  session_start();
  require_once('../function.php');
  if(!isLoggedInCashier()){
    header('location:login.php');
  }
?>
<!DOCTYPE html>
<html lang="en">
<!-- oncontextmenu="return false" -->
<head>
	<meta charset="UTF-8">
	<title>Cashier</title>
	<link rel="stylesheet" type="text/css" href="../assets/css/bootstrap-yeti.css">
  <link href="../assets/css/jquery.dataTables.css" rel="stylesheet">
  <link href="../assets/css/bootstrap-datepicker.min.css" rel="stylesheet">  
	<link rel="stylesheet" type="text/css" href="../assets/css/cashiering.css">
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
    <h4 class="official">Document Received</h4>
    <h5>Date: <?php echo _dateFormat($todays_date); ?></h5>
    <h5>Time: <?php echo _timeFormat($todays_time); ?></h5>
    <h3 class="transactnum">Transaction No. <span></span></h3>
    <div class="row">
      <div class="col-xs-12 receipt-items">         
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

<div class="container">
	<div class="row adjust-top">
		<div class="col-xs-8">
			<div class="form-group has-warning">
          <input data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': ''" class="form form-control input-lg scan" name="data" id="numOnly" autocomplete="off" maxlength="13" autofocus <?php echo isset($_SESSION['gc_super_id']) ? 'disabled="disabled"' : ''; ?> />   
          <input class="form form-control input-sm" id="posmsg" readonly="readonly" style="display:none;" />                	  				
  				<!-- <input class="form-control input-lg scan" id="inputWarning" maxlength="13" type="text" autofocus> -->          
  				<div class="row">
  					<div class="col-xs-12">              
  						<table class="table items">
  							<tr>
                  <td style="width:15px;">&nbsp; </td>
  								<td style="width:136px;px;">Barcode Number</td>  							
  								<td style="width:80px;">GC Type</td>
  								<td style="width:120px;">Denomination</td>
                  <td style="width:100px;">Disc. Type</td>
                  <td style="width:90px;">Dis (%)</td>
                  <td style="width:90px;">Dis Amt</td>
                  <td style="width:90px; text-align:center">Net Amt</td> 								
  							</tr>
  						</table>
  					</div>
  				</div>
  				<div class="row item-list">
  					<div class="col-xs-12">
  						<table class="table items-list-table">
                <tbody class="_barcodes">
                </tbody>
  						</table>
  					</div>
  				</div>
  				<div class="row adjust-top">
  					<div class="col-xs-12 payment-details">
              <input type="hidden" name="sbtotal" value="0.00">
              <input type="hidden" name="docdiscount" value="0.00">
              <input type="hidden" name="ocharge" value="0.00">
              <input type="hidden" name="tax" value="0.00">
              <input type="hidden" name="linediscount" value="0.00">
  						<div class="row">
  						  <div class="col-xs-6">
                  <div class="row">
                    <div class="col-xs-4">
                      Line Disc: 
                    </div>
                    <div class="col-xs-6">
                      <span class="linediscount">0.00</span>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-xs-4">
                      Sub-total:
                    </div>
                    <div class="col-xs-6">
                      <span class="sbtotal">0.00</span>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-xs-4">
                      Doc Disc: 
                    </div>
                    <div class="col-xs-6">
                      <span class="docdiscount">0.00</span>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-xs-4">
                      Other Charge: 
                    </div>
                    <div class="col-xs-6">
                      <span class="ocharge">0.00</span>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-xs-4">
                      Tax: 
                    </div>
                    <div class="col-xs-6">
                      <span class="tax">0.00</span>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-xs-4">
                      No. of items: 
                    </div>
                    <div class="col-xs-6 noitems">
                      0 
                    </div>
                  </div>
                </div>
                <div class="col-xs-6">
                  <div class="row">
                    <div class="col-xs-3 total-text">
                      Amt Due:
                    </div>
                    <div class="col-xs-9">
                      <span class="total" id="_cashier_total"></span>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-xs-3">
                      Cash: 
                    </div>
                    <div class="col-xs-9">
                      <span class="cashr"> ₱ 0.00
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-xs-3">
                      Change: 
                    </div>
                    <div class="col-xs-9">
                      <span class="changer"> ₱ 0.00</span>
                    </div>                 
                  </div>
                   
                </div>
  						</div>
  					</div>
  				</div>
			</div>
		</div>
		<div class="col-xs-4">
			<div class="row">
				<div class="col-xs-12">          
           			<div class="panel status panel-info">
                <div class="panel-heading">
                    <h1 class="panel-title text-center">GC</h1>
                </div>
                <div class="panel-body text-center">                        
                    <strong>Cashiering System v2</strong>
                </div>        
        </div>	
      </div>
      <div class="row btn-row">
        <div class="col-xs-12">
            
            <div class="cashier-mode <?php echo isset($_SESSION['gc_super_id']) ? 'hidediv' : ''; ?>">
        			<button class="btn btn-block btn-md btn-default btn-c" id="f1" onclick="f1();"></span> <span class="btn-side"><span class="hotkeyc">[F1]</span> Payment</span></button>
<!--         			<button class="btn btn-block btn-md btn-default btn-c" id="f2"></span> <span class="btn-side"><span class="hotkeyc">[F2]</span> Credit Card</span></button> -->
              <button class="btn btn-block btn-md btn-default btn-c" id="f2" onclick="f2();"></span> <span class="btn-side"><span class="hotkeyc">[F2]</span> Void Line</span></button>
              <button class="btn btn-block btn-md btn-default btn-c" id="f3" onclick="f3();"></span> <span class="btn-side"><span class="hotkeyc">[F3]</span> Other Income</span></button>
              <button class="btn btn-block btn-md btn-default btn-c" id="f4" onclick="f4();"></span> <span class="btn-side"><span class="hotkeyc">[F4]</span> Supervisor Menu</span></button>
        			<button class="btn btn-block btn-md btn-default btn-c" id="f7" onclick="f7();"></span> <span class="btn-side"><span class="hotkeyc">[F7]</span> Logout</span></button>                
            </div>
         
            <div class="manager-mode <?php echo isset($_SESSION['gc_super_id']) ? '' : 'hidediv'; ?>">
              <button class="btn btn-block btn-md btn-default btn-c" id="f1" onclick="f1();"></span> <span class="btn-side"><span class="hotkeyc">[F1]</span> Lookup</span></button>              
              <button class="btn btn-block btn-md btn-default btn-c" id="f2" onclick="f2();"></span> <span class="btn-side"><span class="hotkeyc">[F2]</span> Void All</span></button>
              <button class="btn btn-block btn-md btn-default btn-c" id="f3" onclick="f3();"></span> <span class="btn-side"><span class="hotkeyc">[F3]</span> GC Refund</span></button>
              <button class="btn btn-block btn-md btn-default btn-c" id="f4" onclick="f4();"></span> <span class="btn-side"><span class="hotkeyc">[F4]</span> Discount</span></button>
              <button class="btn btn-block btn-md btn-default btn-c" id="f5" onclick="f5();"></span> <span class="btn-side"><span class="hotkeyc">[F5]</span> Reports</span></button>
              <button class="btn btn-block btn-md btn-default btn-c" id="f6" onclick="f6();"></span> <span class="btn-side"><span class="hotkeyc">[F6]</span> End of Day</span></button>                           
              <button class="btn btn-block btn-md btn-default btn-c" id="f7" onclick="f7();"></span> <span class="btn-side"><span class="hotkeyc">[F7]</span> Supervisor Logout</span></button>              
            </div>

            <div class="payment-mode">
              <button class="btn btn-block btn-md btn-default btn-c" id="f1" onclick="f1();"></span> <span class="btn-side"><span class="hotkeyc">[F1]</span> Cash</span></button>
              <button class="btn btn-block btn-md btn-default btn-c" id="f2" onclick="f2();"></span> <span class="btn-side"><span class="hotkeyc">[F2]</span> Credit Card</span></button>
              <button class="btn btn-block btn-md btn-default btn-c" id="f3" onclick="f3();"></span> <span class="btn-side"><span class="hotkeyc">[F3]</span> H.O. (JV)</span></button>
              <button class="btn btn-block btn-md btn-default btn-c" id="f4" onclick="f4();"></span> <span class="btn-side"><span class="hotkeyc">[F4]</span> Subs. Admin</span></button>
              <button class="btn btn-block btn-md btn-default btn-c" id="f5" onclick="f5();"></span> <span class="btn-side"><span class="hotkeyc">[F5]</span> Back</span></button>                
            </div>

            <div class="otherincome-mode">
              <button class="btn btn-block btn-md btn-default btn-c" id="f1" onclick="f1();"></span> <span class="btn-side"><span class="hotkeyc">[F1]</span> Revalidation Payment</span></button>
              <button class="btn btn-block btn-md btn-default btn-c" id="f2" onclick="f2();"></span> <span class="btn-side"><span class="hotkeyc">[F2]</span> Back</span></button>
            </div>

            <div class="reports">
              <button class="btn btn-block btn-md btn-default btn-c" id="f1" onclick="f1();"></span> <span class="btn-side"><span class="hotkeyc">[F1]</span> POS Report</span></button>
              <button class="btn btn-block btn-md btn-default btn-c" id="f2" onclick="f2();"></span> <span class="btn-side"><span class="hotkeyc">[F2]</span> Cashier Report</span></button>
              <button class="btn btn-block btn-md btn-default btn-c" id="f2" onclick="f3();"></span> <span class="btn-side"><span class="hotkeyc">[F3]</span> Back </span></button>
            </div>

            <div class="discounts">
              <button class="btn btn-block btn-md btn-default btn-c" id="f1" onclick="f1();"></span> <span class="btn-side"><span class="hotkeyc">[F1]</span> Line Disc</span></button>
              <button class="btn btn-block btn-md btn-default btn-c" id="f2" onclick="f2();"></span> <span class="btn-side"><span class="hotkeyc">[F2]</span> Document Disc</span></button>
              <button class="btn btn-block btn-md btn-default btn-c" id="f3" onclick="f3();"></span> <span class="btn-side"><span class="hotkeyc">[F3]</span> Remove Line Disc</span></button>
              <button class="btn btn-block btn-md btn-default btn-c" id="f4" onclick="f4();"></span> <span class="btn-side"><span class="hotkeyc">[F4]</span> Remove Document Disc</span></button>
               <button class="btn btn-block btn-md btn-default btn-c" id="5" onclick="f5();"></span> <span class="btn-side"><span class="hotkeyc">[F5]</span> Back</span></button>
            </div>
        </div>
      </div>
		</div>
	</div>
</div>
<footer class="footer">
  <div class="container">
    <div class="row footer-info">
      <div class="col-xs-3">        
        <span id="date-footer"><?php echo _dateFormat($todays_date); ?></span> | <span id="time"></span>
      </div>
      <div class="col-xs-3">
        <span class="date-cas">Cashier:</span> <span class="cashiername"><?php echo ucwords($_SESSION['gccashier_fullname']); ?></span>
      </div> 
      <div class="col-xs-3">
        <span class="date-cas">Store:</span>
        <span class="cashiername">
        <?php 
          $store = $_SESSION['gccashier_store'];
          echo getField($link,'store_name','stores','store_id',$store); 
        ?>
        </span>
      </div>
      <div class="col-xs-3">
        <span class="supkey">Supervisor Key</span> <input id="managerkey" type="checkbox" disabled="" <?php echo isset($_SESSION['gc_super_id']) ? 'checked="checked"' : '' ?>/>
         <!-- checked="checked" -->
      </div>
    </div>
  </div>
</footer>
	
<script src="../assets/js/jquery-1.10.2.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
<script src="../assets/js/bootstrap-modalb.js"></script>
<script src="../assets/js/jquery.inputmask.bundle.min.js"></script>
<script src="../assets/js/jquery.dataTables.js"></script>
<script src="../assets/js/shortcut.js"></script>
<script src="../assets/js/bootstrap-datepicker1.min.js"></script>
<script src="../assets/js/cashier-main.js"></script>
</body>
</html>