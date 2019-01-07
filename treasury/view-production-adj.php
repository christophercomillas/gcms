<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  $productionadj = getAdj($link,'gc_adjustment','gc_adjustment.gc_adj_by');

?>

<?php require '../menu.php'; ?>

  <div class="main fluid">
  	<div class="row">
  		<div class="col-sm-12">
  			<div class="box box-bot">
        	<div class="box-header"><h4><i class="fa fa-inbox"></i> Production Adjustment</div>
        	<div class="box-content">
            <div class="row">
              <div class="col-sm-12">
                <?php if(count($productionadj)>0): ?> 
                <table id="adjust-list" class="table table-adjust">
                    <thead>
                        <tr>
                            <th>Adjustment Type</th>
                            <th>Date</th>         
                            <th>Remarks</th>
                            <th>Adjusted By</th>
                            <th></th>                  
                        </tr>
                    </thead>                    
                    <tbody>
                        <?php foreach ($productionadj as $key): ?>
                            <tr>
                                <td>
                                    <?php
                                        if($key->gc_adj_type=='n')
                                            echo $adj = "negative";
                                        else 
                                            echo $adj = "positive";
                                    ?>
                                </td>
                                <td><?= _dateFormat($key->gc_adj_datetime); ?></td>
                                <td><?= $key->gc_adj_remarks; ?></td>
                                <td><?= ucwords($key->firstname.' '.$key->lastname);?></td>
                                <td><button class="btn btn-info btn-info-o prod-adj" prod-id="<?php echo $key->gc_adj_id; ?>">View</button></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                <?php else: ?>
                <div class="alert alert-info">.</div>
                <?php endif; ?>
              </div>
            </div>
        </div>
    </div>
  </div>


<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/adj.js"></script>
<?php include 'footer.php' ?>