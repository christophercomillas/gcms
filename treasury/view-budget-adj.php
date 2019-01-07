<?php 
    session_start();
    include '../function.php';
    require 'header.php';

    $adjs = getAdjBudget($link);

?>

<?php require '../menu.php'; ?>

    <div class="main fluid">
        <div class="row form-container">
            <div class="col-md-12">
                <div class="panel with-nav-tabs panel-info">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Budget Adjustments</a></li>
                            <!-- <li><a href="#tab2default" data-toggle="tab">Default 2</a></li> -->
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab1default">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table id="adjust-list" class="table table-adjust">
                                            <thead>
                                                <tr>
                                                    <th>Adjustment Type</th>
                                                    <th>Date</th>
                                                    <th>Amount</th>         
                                                    <th>Remarks</th>
                                                    <th>Adjusted By</th>              
                                                </tr>
                                            </thead> 
                                            <tbody>
                                                <?php foreach ($adjs as $key): ?>
                                                    <tr>
                                                        <td><?php echo ucfirst(strtolower($key->bud_adj_type)); ?></td>
                                                        <td><?php echo _dateFormat($key->bledger_datetime); ?></td>
                                                        <td>
                                                            <?php 
                                                                if($key->bdebit_amt==0.00)
                                                                {
                                                                    echo number_format($key->bcredit_amt,2);
                                                                }
                                                                else 
                                                                {
                                                                    echo number_format($key->bdebit_amt,2);
                                                                }
                                                            ?>
                                                        </td>
                                                        <td><?php echo $key->bud_remark; ?></td>
                                                        <td><?php echo ucwords(strtolower($key->prepby)); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>                                                
                                            </tbody>                   
                                        </table>  

                                    </div>
                                </div>
                            </div>
                            <!-- <div class="tab-pane fade" id="tab2default">Default 2</div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>


<!--     <div class="row">
        <div class="col-sm-12">
            <div class="box box-bot">
            <div class="box-header"><h4><i class="fa fa-inbox"></i> Budget Adjustment</div>
            <div class="box-content">
            <div class="row">
              <div class="col-sm-12">
                <?php if(count($budget)>0): ?> 
                <table id="adjust-list" class="table table-adjust">
                    <thead>
                        <tr>
                            <th>Adjustment Type</th>
                            <th>Date</th>
                            <th>Amount</th>         
                            <th>Remarks</th>
                            <th>Adjusted By</th>              
                        </tr>
                    </thead>                    
                    <tbody>                        
                        <?php foreach ($budget as $key): ?>
                            <tr>
                                <td>
                                    <?php
                                        if($key->bud_adj_type=='n')
                                            echo $adj = "negative";
                                        else 
                                            echo $adj = "positive";
                                    ?>
                                </td>
                                <td><?= _dateFormat($key->bledger_datetime); ?></td>
                                <td>
                                    <?php 
                                        if($key->bdebit_amt==0)
                                            echo '&#8369 '.number_format($key->bcredit_amt,2);
                                        else 
                                            echo '&#8369 '.number_format($key->bdebit_amt,2);

                                    ?>
                                </td>
                                <td><?= $key->bud_remark; ?></td>
                                <td><?= ucwords($key->firstname.' '.$key->lastname);?></td>                                
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
    </div> -->
  </div>


<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/adj.js"></script>
<?php include 'footer.php' ?>