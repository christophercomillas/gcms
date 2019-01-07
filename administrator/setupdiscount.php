<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  $users = getUsers($link);
?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
  	<div class="row row-nobot">
  		<div class="col-sm-8">
    		<div class="box box-bot">
      			<div class="box-header">
              <span class="box-title-with-btn"><i class="fa fa-inbox">
                  </i> Discount Set Up
              </span>
              <div class="col-sm-8 form-horizontal pull-right p-right">
                  <div class="col-sm-offset-7  col-sm-5">
                      <button class="btn btn-block btn-info" id="addnew"><i class="fa fa-user-plus"></i> Add New Discount</button>
                  </div>
              </div>   
            </div>
     			<div class="box-content form-container">

     			</div>
     		</div>
  		</div>
    </div>
  </div>
<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/admin.js"></script>
<?php include 'footer.php' ?>