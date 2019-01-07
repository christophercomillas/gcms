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

<html>
  <head>
  <title>GC POS </title>
  <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0">
  <meta charset="utf-8">
  <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
  <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="../assets/css/cashierstyle.css" rel="stylesheet" />
<style>
.container {
  width: 100%;
  max-width: 100%;
}
.container.padded > .row {
  margin: 0;
}
.padded {
  padding: 15px;
}
.separate-sections {
  margin: 0;
  list-style: none;
  padding-bottom: 5px;
}
.separate-sections > li, .separate-sections > div {
  margin-bottom: 15px !important;
}
.separate-sections > li:last-child, .separate-sections > div:last-child {
  margin-bottom: 0px;
}
i {
  margin: 0 10px;
}
</style>

</head>

  <body>
<div class="container">
    <div class="col-md-4 col-md-offset-4" id="cashier-login">
      <div class="padded" style="text-align:center;margin-top: 40px;">
          <div class="panel panel-warning form-login" style="margin-top: 20px;">
          <div class="panel-heading">
              <label class="login_type">Cashier Login</label></div>
          <div class="panel-body" style="padding-bottom:0;">
              <div class="response">
              </div>                
              <form action="../ajax-cashier.php?action=logincashier" method="post" accept-charset="utf-8" class="separate-sections" id="cashierLogin">          
                <div class="input-group"> 
                  <span class="input-group-addon">
                    <i class="glyphicon glyphicon-user"></i>
                  </span> 
                  <input name="username" value="" id="username" class="form-control" placeholder="Username" type="text" autocomplete="off" required> 
                </div>
                <div class="input-group"> 
                  <span class="input-group-addon">
                    <i class="glyphicon glyphicon-tags"></i>
                  </span>
                  <input name="idnum" value="" maxlength="8" id="idnum" class="form-control" placeholder="Employee ID Number" type="text" autocomplete="off" required> 
                </div>
                <div class="input-group"> 
                  <span class="input-group-addon">
                    <i class="glyphicon glyphicon-lock"></i>
                  </span> 
                  <input name="password" value="" id="password" class="form-control" placeholder="Password" type="password" required> 
                </div>
                    <div class="row">            
                <div class="col-md-12">
                  <button type="submit" class="btn btn-success btn-block">Login<i class="glyphicon glyphicon-log-in"></i> </button>
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
                  <button type="submit" class="btn btn-success btn-block">Login<i class="glyphicon glyphicon-log-in"></i> </button>
                </div>
              </div>
            </form>                  
        </div>      

        </div>
        <div class="row">
          <div class="col-md-8 col-md-offset-2">© 2015 GC POS</div>
      </div>
    </div>

  </div>
</div>
<script src="http:../assets/js/jquery-1.10.2.js"></script> 
<script src="http:../assets/js/cashier.js"></script> 
</body>
</html>