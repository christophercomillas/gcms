<?php 
  session_start();
  include '../function.php';
  require 'header.php';
?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
  	<div class="row">
      <div class="col-sm-5">
        <div class="box box-bot">
          <div class="box-header"><h4><i class="fa fa-inbox"></i> Rebuild Database</h4></div>
          <div class="box-content form-container">
            <form method="POST" action="../ajax.php?action=rebuild" id="rebuildForm" class="form-horizontal">
              <div class="form-group">
                <label class="col-sm-5 control-label">Admin Password: </label>
                <div class="col-sm-7">
                  <input type="password" class="form-control input-sm" name="adminpass" required autofocus>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-8 col-md-4">
                   <button type="submit" class="btn btn-block btn-primary" id="btn">
                    <span class="glyphicon glyphicon-share" aria-hidden="true"></span> 
                    Submit
                  </button>
                </div>
              </div>
              <div class="response">
              </div>
            </form>              
          </div>
        </div>
      </div>    
    </div>
  </div>
  <div class="modal modal-static fade loadingstyle" id="processing-modal" role="dialog" aria-hidden="true" data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog loadingstyle">
        <div class="text-center">
            <img src="../assets/images/ring-alt.svg" class="icon" />
            <h4 class="loading">Rebuilding...Please wait...</h4>
        </div>
      </div>
  </div>
<!--   <div class="modal modal-static fade" id="processing-modal" role="dialog" aria-hidden="true" data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog">
          <div class="modal-content modal-content-x">
              <div class="modal-body">
                  <div class="text-center">
                      <img src="../assets/images/loading.gif" class="icon" />
                      <h4>Processing...</h4>
                  </div>
              </div>
          </div>
      </div>
  </div> -->
<?php include 'jscripts.php'; ?>          
<script type="text/javascript" src="../assets/js/admin.js"></script>
<?php include 'footer.php' ?>