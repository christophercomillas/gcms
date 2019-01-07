<?php	
	include '../function.php';
	if(isset($_GET['id'])){
		$id = $_GET['id'];
	}

    $gcRequest= getApprovedBudgetRequestById($link,$id);
    $gcItems = approvedGCrequestItems($link,$id);
    $details = receivedDetails($link,$id); 
?>

<div class="row">   
    <?php foreach ($gcRequest as $key): ?>
    <div class="col-sm-6">
        <div class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-6 control-label">Retail Store:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control input-sm" readonly="readonly" value="<?php echo $key['store_name']; ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">GCR No.:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control input-sm" readonly="readonly" value="<?php echo $key['sgc_num']; ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">Date Requested:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control input-sm" readonly="readonly" value="<?php echo _dateFormat($key['sgc_date_request']); ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">Date Needed:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control input-sm" readonly="readonly" value="<?php echo _dateFormat($key['sgc_date_needed']); ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">Request Remarks:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control input-sm" readonly="readonly" value="<?= ucwords($key['sgc_remarks']); ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">Request Document:</label>
                <div class="col-sm-6">
                    <a class="btn btn-default" href='../assets/images/gcRequestStore/download.php?file=<?php echo $key['sgc_file_docno']; ?>.jpg'><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download</a></td> 
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">Request Prepared by:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control input-sm" readonly="readonly" value="<?= ucwords($key['sgc_requested_by']); ?>">
                </div>
            </div>
            <hr></hr>
            <div class="form-group">
                <label class="col-sm-6 control-label">Date Approved:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control input-sm" readonly="readonly" value="<?= _dateFormat($key['agcr_approved_at']); ?>"); ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">Approved Remarks:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control input-sm" readonly="readonly" value="<?= $key['agcr_remarks']; ?>"); ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">Approved Document:</label>
                <div class="col-sm-6">
                    <a class="btn btn-default" href='../assets/images/approvedGCRequest/download.php?file=<?php echo $key['agcr_file_docno']; ?>.jpg'><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download</a></td> 
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">Approved by:</label>
                <div class="col-sm-6">
                <input type="text" class="form-control input-sm" readonly="readonly" value="<?= ucwords($key['agcr_approvedby']); ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">Checked by:</label>
                <div class="col-sm-6">
                <input type="text" class="form-control input-sm" readonly="readonly" value="<?= ucwords($key['agcr_checkedby']); ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">Prepared by:</label>
                <div class="col-sm-6">
                <input type="text" class="form-control input-sm" readonly="readonly" value="<?= ucwords($key['firstname'].' '.$key['lastname']); ?>">
                </div>
            </div>
        </div>
    </div>
     <?php endforeach; ?>
    <div class="col-sm-6">
        <table class="table">
            <thead>
                <tr>
                    <th>Denomination</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>                    
                </tr>            
            </thead>
            <tbody>
            <?php 
                $total = 0;
                foreach ($gcItems as $key): 
            ?>
                <tr>
                    <td>&#8369 <?php echo number_format($key['denomination'],2)?></td>
                    <td><?= $key['sri_items_quantity']; ?></td>
                    <?php 

                        $stotal = $key['denomination']*$key['sri_items_quantity'];

                        $total = $total+$stotal;

                    ?>
                    <td>&#8369 <?= number_format($stotal,2); ?></td>
                </tr>          
            <?php endforeach; ?>                
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td>Total:</td>
                    <td>&#8369 <?php echo number_format($total,2); ?></td>
                </tr>
            </tfoot>
        </table>
        <div class="row gc-app-pro">
            <div class="col-sm-12">
                <button class="btn btn-info" id="btn-gc-app-pro" app-gc-id="<?php echo $id; ?>">View GC Barcode No.</button>
            </div>
        </div>

        <?php if(count($details)>0): ?>
            <br />
            <br />
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-horizontal">
                        <?php foreach ($details as $rec): ?>
                            <div class="form-group">
                                <label class="col-sm-6 control-label">Date Received:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control input-sm" readonly="readonly" value="<?php echo _dateFormat($rec->srec_at); ?>">
                                </div>
                            </div> 
                            <div class="form-group">
                                <label class="col-sm-6 control-label">Received by:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control input-sm" readonly="readonly" value="<?php echo ucwords($rec->firstname.' '.$rec->lastname); ?>">
                                </div>
                            </div> 
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        <?php endif; ?> 

    </div>
   
</div>