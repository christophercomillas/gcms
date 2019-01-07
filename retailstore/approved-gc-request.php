<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  $gcRequest= getGCReleasedForStores($link,$storeid);
?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
  	<div class="row">
  		<div class="col-sm-12">
  			<div class="box box-bot">
        	<div class="box-header"><h4><i class="fa fa-inbox"></i> Released GC </div>
        	<div class="box-content">
            <div class="row">
              <div class="col-sm-12">          
                  <table class="table table-adjust" id="storeRec">
                      <thead>
                          <tr>
                              <th>Released No.</th>
                              <th>Date Requested</th>         
                              <th>Retail Store</th>
                              <th>Released By</th>
                              <th>Date Released</th>
                              <th>Approved By</th>
                              <th>Status</th>              
                          </tr>
                      </thead>
                      <tbody class="store-request-list">
                          <?php foreach ($gcRequest as $key): ?>
                              <tr relid="<?php echo $key->agcr_request_relnum; ?>" storeid="<?php echo $storeid; ?>" class="<?php echo $key->agcr_rec == '1' ? 'closed':'notclosed'; ?>" rec="<?php echo $key->agcr_rec; ?>">
                                <td><?php echo threedigits($key->agcr_request_relnum);?></td>
                                <td><?php echo _dateFormat($key->sgc_date_request);?></td>
                                <td><?php echo $key->store_name;?></td>
                                <td><?php echo ucwords($key->firstname.' '.$key->lastname);?></td>
                                <td><?php echo _dateFormat($key->agcr_approved_at);?></td>
                                <td><?php echo $key->agcr_approvedby;?></td>
                                <td class="closedbtn"><?php echo $key->agcr_rec == '1' ? '<button type="button" class="btn btn-closed" onclick="viewapprovedgc('.$key->agcr_id.');">Closed</button>':''; ?></td>                                 
                              </tr>
                          <?php endforeach ?>
                      </tbody>
                  </table>
              </div>
            </div>
        </div>
    </div>
  </div>

<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/main.js"></script>
<?php include 'footer.php' ?>