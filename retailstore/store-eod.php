<?php 
    session_start();
    include '../function.php';
    require 'header.php';

    //check url
    $url = 0;
    if(isset($_GET['eod']))
    {

        $id = $_GET['eod'];
        // check if eod id exist in location
        $eod = numRowsWhereTwo($link,'store_eod','steod_id','steod_storeid','steod_id',$storeid,$id);    
        if((is_numeric($id) && !is_null($id)) && $eod > 0)
        {
            $url=1;
            $getverifiedgc = eodDisplayItems($link,$id);
            // get row           
            $eodDetails = getEODdetails($link,$storeid,$id);
            $used = verifiedAndUsedNumGC($link,$storeid,$id);
        }
        else 
        {
            header('Location: store-eod.php');
        }
    }
    else 
    {
        $getverifiedgc = getverifiedgcStore($link,$storeid);

    }  
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
                                    <a href="#tab1default" data-toggle="tab"><?php echo $url==1 ? 'EOD Date:'._dateFormat($eodDetails->steod_datetime) : 'Verified GC (For EOD)' ?></a>
                                </li>

                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="tab1default">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <?php if($url):?>
                                                <table class="table rows-adjust" id="storeod">
                                                    <thead>
                                                        <tr>
                                                            <th>Barcode #</th>
                                                            <th>Denomination</th>
                                                            <th>Time Verified</th>
                                                            <th>Verified By</th>
                                                            <th>Customer Name</th>
                                                            <th>Balance</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>                
                                                    <tbody>
                                                        <?php 
                                                            foreach ($getverifiedgc as $v): 
                                                        ?>
                                                            <tr>
                                                                <td><?php echo $v->st_eod_barcode; ?></td>
                                                                <td><?php echo number_format($v->vs_tf_denomination,2);?></td>
                                                                <td><?php echo is_null($v->vs_reverifydate) ? _timeFormat($v->vs_time): _timeFormat($v->vs_reverifydate); ?></td>
                                                                <td><?php echo ucwords($v->verby); ?></td>
                                                                <td><?php echo ucwords($v->cus); ?></td>
                                                                <td><?php echo number_format($v->vs_tf_balance,2); ?></td>
                                                                <td><i class="fa fa fa-search falink sstaff" onclick="textfiletranx(<?php echo $v->st_eod_barcode; ?>)"></i></td>
                                                            </tr>
                                                        <?php endforeach ?>
                                                    </tbody>
                                                </table>
                                            <?php else: ?>
                                                <div class="response">
                                                </div>
                                                <table class="table" id="storeod">
                                                    <thead>
                                                        <tr>
                                                            <th>Barcode #</th>
                                                            <th>Denomination</th>
                                                            <th>GC Type</th>
                                                            <th>Date</th>
                                                            <th>Time</th>
                                                            <th>Verified By</th>
                                                            <th>Customer Name</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>                
                                                    <tbody>
                                                        <?php foreach ($getverifiedgc as $v): ?>
                                                            <tr>
                                                                <td><?php echo $v->vs_barcode; ?></td>
                                                                <td><?php echo number_format($v->vs_tf_denomination,2);?></td>
                                                                <td><?php echo ucwords($v->gctype); ?></td>
                                                                <td><?php echo is_null($v->vs_reverifydate) ? _dateFormat($v->vs_date) : _dateFormat($v->vs_reverifydate);  ?>
                                                                </td>
                                                                <td><?php echo is_null($v->vs_reverifydate) ? _timeFormat($v->vs_time): _timeFormat($v->vs_reverifydate); ?></td>
                                                                <td><?php echo ucwords($v->firstname.' '.$v->lastname); ?></td>
                                                                <td><?php echo ucwords($v->cus_fname.' '.$v->cus_lname); ?></td>
                                                                <td><?php echo is_null($v->vs_reverifydate) ? '<span class="label label-success">verified</span>' : '<span class="label label-primary">reverified</span>';  ?></td>
                                                            </tr>
                                                        <?php endforeach ?>
                                                    </tbody>
                                                </table>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<div class="modal modal-static fade loadingstyle" id="processing-modal" role="dialog" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog loadingstyle">
      <div class="text-center">
          <img src="../assets/images/ring-alt.svg" class="icon" />
          <h4 class="loading">Processing...Please wait...</h4>
      </div>
    </div>
</div>


<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/store.js"></script>
<?php include 'footer.php' ?>