<?php 
  session_start();
  include '../function.php';
  require 'header.php';
?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="col-md-12 pad0">
        <div class="panel with-nav-tabs panel-info">
            <div class="panel-heading">
              <ul class="nav nav-tabs">
                <li class="active" style="font-weight:bold">
                  <a href="#tab1default" data-toggle="tab">FAD System Connection</a>
                </li>
              </ul>
            </div>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="tab1default">
                      <div class="row ">
                        <div class="col-sm-6">
                          Connect to FAD System Server?  
                          <div class="onoffswitch">
                              <input type="checkbox" name="" class="onoffswitch-checkbox fadsys" id="myonoffswitch" <?php echo getField($link,'app_settingvalue','app_settings','app_tablename','fad_server_connection') =='yes' ? "checked" : "" ?>>
                              <label class="onoffswitch-label" for="myonoffswitch">
                                  <span class="onoffswitch-inner"></span>
                                  <span class="onoffswitch-switch"></span>
                              </label>
                          </div>                          
                        </div>
                      </div>
                        <div class="row form-container">
                          <form id="fadserverupdate" method="POST" action="../ajax.php?action=fadserverupdate">
                          <div class="col-sm-10">
                            <h4>FAD Server IP Folder</h4>
                              <div class="form-group">
                                <label class="nobot">Requisition Folder NEW</label>   
                                <input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo getField($link,'app_settingvalue','app_settings','app_tablename','fad_server_ip_requis_new'); ?>" name="requisnew">  
                              </div>
                              <div class="form-group">
                                <label class="nobot">Requisition Folder USED</label>   
                                <input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo getField($link,'app_settingvalue','app_settings','app_tablename','fad_server_ip_requis_used'); ?>" name="requisused">  
                              </div>
                              <div class="form-group">
                                <label class="nobot">Receiving Folder NEW</label>   
                                <input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo getField($link,'app_settingvalue','app_settings','app_tablename','fad_server_ip_received_new'); ?>" name="receivednew">  
                              </div>
                              <div class="form-group mabot30">
                                <label class="nobot">Receiving Folder USED</label>   
                                <input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo getField($link,'app_settingvalue','app_settings','app_tablename','fad_server_ip_received_used'); ?>" name="receivedused">  
                              </div>
                            <h4>Localhost Folder</h4>
                              <div class="form-group">
                                <label class="nobot">Requisition Folder NEW</label>   
                                <input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo getField($link,'app_settingvalue','app_settings','app_tablename','localhost_requisition_new'); ?>" name="localrequisnew">  
                              </div>
                              <div class="form-group">
                                <label class="nobot">Requisition Folder USED</label>   
                                <input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo getField($link,'app_settingvalue','app_settings','app_tablename','localhost_requisition_used'); ?>" name="localrequisused">  
                              </div>
                              <div class="form-group">
                                <label class="nobot">Receiving Folder NEW</label>   
                                <input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo getField($link,'app_settingvalue','app_settings','app_tablename','localhost_received_new'); ?>" name="localreceivednew">  
                              </div>
                              <div class="form-group">
                                <label class="nobot">Receiving Folder USED</label>   
                                <input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo getField($link,'app_settingvalue','app_settings','app_tablename','localhost_received_used'); ?>" name="localreceivedused">  
                              </div>
                              <div class="response">
                              </div>
                              <button type="submit" class="btn btn-primary pull-right">Submit</button>
                          </div>
                          </form>                      
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
<script type="text/javascript" src="../assets/js/admin.js"></script>
<?php include 'footer.php' ?>