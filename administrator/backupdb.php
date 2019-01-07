<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  $where = '1';
  $select ="backup_records.br_filename, backup_records.br_date, CONCAT(users.firstname ,' ', users.lastname) as fullname";
  $join = "INNER JOIN users ON users.user_id = backup_records.br_by";


  $backups = getAllData($link,'backup_records',$select,$where,$join,'');

?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
  	<div class="row">
      <div class="col-sm-10">
        <div class="box box-bot">
            <div class="box-header">
              <span class="box-title-with-btn"><i class="fa fa-inbox">
                  </i> Backup DB
              </span>
              <div class="col-sm-8 form-horizontal pull-right p-right">
                  <div class="col-sm-offset-8 col-sm-4">
                      <button class="btn btn-block btn-info" onclick="addNewBackup()"><i class="fa fa-plus"></i> New Backup</button>
                  </div>
              </div>   
            </div>
          <div class="box-content">
            <table class="table" id="backuplist">
              <thead>
                <tr>
                  <th>Date Backup</th>
                  <th>Time</th>
                  <th>Backup By</th>
                  <th>Download</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($backups as $bk): ?>
                <tr>
                  <td><?php echo _dateFormat($bk->br_date); ?></td>
                  <td><?php echo _timeFormat($bk->br_date); ?></td>
                  <td><?php echo ucwords($bk->fullname); ?></td>
                  <td><i class="fa fa-download" aria-hidden="true"></i>
</td>
                </tr>
                <?php endforeach ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>    
    </div>
  </div>
  <div class="modal modal-static fade loadingstyle" id="processing-modal" role="dialog" aria-hidden="true" data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog loadingstyle">
        <div class="text-center">
            <img src="../assets/images/ring-alt.svg" class="icon" />
            <h4 class="loading">Backing up the database...Please wait...</h4>
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