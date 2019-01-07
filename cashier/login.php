<?php
session_start();
require_once('../function.php');

if(isset($_GET['action'])){
  if($_GET['action']=='logout'){
    unset($_SESSION['gccashier_username']);
    unset($_SESSION['gccashier_id']);
    unset($_SESSION['gccashier_fullname']);
    unset($_SESSION['gccashier_idnumber']);
    unset($_SESSION['gccashier_store']);
    unset($_SESSION['gccashier_store_code']);
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>GC Login</title>
	<link rel="shortcut icon" href="../assets/images/favicon.ico" type="image/icon">
	<link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css" rel="stylesheet" />
	<style type="text/css">
		*{
			padding: 0px;
			margin: 0px;
		}
		body,html,label{
			font-family: tahoma;
			font-size: 12px;
			margin: 0px;
			padding: 0px;
		}
		div.container86{
			width: 800px;
			height: 600px;
		    background-color: red;
		    background: url(../assets/images/cashierB.jpg) no-repeat center center fixed; 
		    -webkit-background-size: cover;
		    -moz-background-size: cover;
		    -o-background-size: cover;
		    background-size: cover;
			margin: auto;
			max-height: 600px;
			min-height: 600px !important;
			overflow: hidden;
		}
		div.login-contain{
			background-color: red;
			padding: auto;
			margin: auto;
			width: 300px;
			margin-top: 100px;
			background-color: #fff;
		}
		div.lbl-container{
			text-align: center;
			background-color: #0A61B0;
			color: #fff;
			font-weight: bold;
			border-top: 1px solid #0FAFF3;
			border-bottom: 1px solid #0FAFF3;
		}

		label.login_type{
			padding: 6px 6px;
			font-size: 14px;
		}

		span.input-group-addon,input[type='text'],button[type='submit'],input[type='password']{
			border-radius: 0px;
		}
		input[type='text'],input[type='password']{
			font-weight: bold;
		}

		form#managerLogin{
			display: none;
		}

		div.alert-danger{
			border-radius: 0px;
    		padding: 8px;
		}
	</style>
</head>

<body>
	<div class="container86">
		<div class="login-contain ">
			<div class="lbl-container">
				<label class="login_type">Cashier Login</label>
			</div>
			<div class="form-login">
				<form action="../ajax-cashier.php?action=logincashier" method="post" accept-charset="utf-8" class="separate-sections" id="cashierLogin">          
		            <div class="input-group"> 
		              <span class="input-group-addon">
		                <i class="glyphicon glyphicon-user"></i>
		              </span> 
		              <input name="username" value="" id="username" class="form-control" placeholder="Username" type="text" autocomplete="off" required=""> 
		            </div>
		            <div class="input-group"> 
		              <span class="input-group-addon">
		                <i class="glyphicon glyphicon-tags"></i>
		              </span>
		              <input name="idnum" value="" maxlength="13" id="idnum" class="form-control" placeholder="Employee ID Number" type="text" autocomplete="off" required=""> 
		            </div>
		            <div class="input-group"> 
		              <span class="input-group-addon">
		                <i class="glyphicon glyphicon-lock"></i>
		              </span> 
		              <input name="password" value="" id="password" class="form-control" placeholder="Password" type="password" required=""> 
		            </div>
		                <div class="row">            
		            <div class="col-md-12">
		              <button type="submit" class="btn btn-success btn-block">Login <i class="glyphicon glyphicon-log-in"></i> </button>
		            </div>
					</div>
	            </form>
	            <form action="../ajax-cashier.php?action=loginmanager" method="post" accept-charset="utf-8" class="separate-sections" id="managerLogin">          
	                <input type="hidden" name="cashier" id="cashier" value="">
	                <input type="hidden" name="store" id="store" value="">

	                <div class="input-group"> 
	                  <span class="input-group-addon">
	                    <i class="glyphicon glyphicon-user"></i>
	                  </span> 
	                  <input name="username" value="" id="username" class="form-control" placeholder="Manager Username" type="text" autocomplete="off" required> 
	                </div>
	                <div class="input-group"> 
	                  <span class="input-group-addon">
	                    <i class="glyphicon glyphicon-tags"></i>
	                  </span> 
	                  <input name="managerkey" value="" id="managerkey" class="form-control" placeholder="Manager's Key" type="password" required> 
	                </div>
	                    <div class="row">            
	                <div class="col-md-12">
	                  <button type="submit" class="btn btn-success btn-block">Login <i class="glyphicon glyphicon-log-in"></i> </button>
	                </div>
	              </div>
	            </form>
	            <div class="response">
	            </div>
            </div>
		</div>
	</div>
</body>
<script src="http:../assets/js/jquery-1.10.2.js"></script> 
<script src="http:../assets/js/cashier.js"></script> 
</html>