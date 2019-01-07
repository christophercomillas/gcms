<?php 
  session_start();
  include '../function.php';
  require 'header.php';
?>

<?php require '../menu.php'; ?>

  <div class="main fluid">    
    <div class="row">
    	<div class="col-sm-12">
	    	<div class="box box-bot">
	      		<div class="box-header"><h4><i class="fa fa-inbox"></i> GC Status</h4></div>
	      			<div class="box-content">
			            <div class="row row-adjust-top adjust-top2">
			                <div class="col-xs-5 col-xs-offset-3 form-container">
			                    <form method="post" action="../ajax.php?action=locategc" id="locategcForm">
			                        <div class="input-group custom-search-form">
			                           <input data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': ''" class="form form-control input-lg" id="gcstat" name="gcstat" autocomplete="off" maxlength="13" name="gcstat" autofocus />
			                                <span class="input-group-btn">
			                                <button class="btn btn-info input-lg gc-status" type="submit">
			                                    <span class="glyphicon glyphicon-search"></span>
			                                </button>
			                            </span>
			                        </div>
			                    </form>
			                </div>
			            </div>

			            <div class="row row-adjust-top adjust-top2">
			                <div class="col-xs-5 col-xs-offset-3">
			                    <div class="response">
			                    </div>
			                </div>
			            </div>
	      			</div>
	      		</div>
	      	</div>
      	</div>
    </div>
  </div>


<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/marketing.js"></script>
<?php include 'footer.php' ?>