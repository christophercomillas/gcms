<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  $gcRequest= GCReleasedAllStore($link);
?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
  	<div class="row">
  		<div class="col-sm-12">
  			<div class="box box-bot">
        	<div class="box-header"><h4><i class="fa fa-inbox"></i> Released GC</div>
        	<div class="box-content">
            <div class="row">
              <div class="col-sm-12"> 
                <table class="table" id="storeRequestList">
                  <thead>
                    <th>Released No.</th>
                    <th>Date Requested</th>
                    <th>Retail Store</th>
                    <th>Released By</th>
                    <th>Date Released</th>
                    <th>Approved By</th>
                    <th>Reprint</th>
                  </thead>
                  <tbody>
                    <?php
                      foreach ($gcRequest as $key):

                    ?>
                        <tr onclick="viewapprovedgc(<?php echo $key->agcr_request_relnum;?>)">
                          <td><?php echo threedigits($key->agcr_request_relnum);?></td>
                          <td><?php echo _dateFormat($key->sgc_date_request);?></td>
                          <td><?php echo $key->store_name;?></td>
                          <td><?php echo ucwords($key->firstname.' '.$key->lastname);?></td>
                          <td><?php echo _dateFormat($key->agcr_approved_at);?></td>
                          <td><?php echo $key->agcr_approvedby;?></td>  
                          <td><i class="fa fa-fa fa-print faeye" title="View" data-trid="<?php echo $key->agcr_id; ?>" id="reprintrelgc"></i></td>                                              
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