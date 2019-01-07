<?php 
	session_start();
	include '../function.php';
	require 'header.php';
	require '../menu.php';
    $hasError = false;
    if(isset($_SESSION['scanReviewGC']))
        unset($_SESSION['scanReviewGC']);

    if(!isset($_GET['id']))
    {
        exit();
        $hasError = true;
    }

    $id = (int)$_GET['id'];

    $table = 'special_external_gcrequest';
    $select = "special_external_gcrequest.spexgc_num,
        special_external_gcrequest.spexgc_dateneed,
        special_external_gcrequest.spexgc_id,
        special_external_gcrequest.spexgc_datereq,
        CONCAT(users.firstname,' ',users.lastname) as prep,
        CONCAT(approvedprep.firstname,' ',approvedprep.lastname) as apprep,
        special_external_customer.spcus_companyname,
        special_external_gcrequest.spexgc_remarks,
        special_external_gcrequest.spexgc_payment,
        special_external_gcrequest.spexgc_paymentype,
        access_page.title,
        approved_request.reqap_date,
        approved_request.reqap_remarks,
        approved_request.reqap_doc,
        approved_request.reqap_checkedby,
        approved_request.reqap_approvedby";
    $where = "special_external_gcrequest.spexgc_status='approved'
        AND
            special_external_gcrequest.spexgc_id='".$id."'
        AND
            approved_request.reqap_approvedtype='Special External GC Approved'
        AND 
            special_external_gcrequest.spexgc_reviewed='reviewed'";
    $join = 'INNER JOIN
            users
        ON
            users.user_id = special_external_gcrequest.spexgc_reqby
        INNER JOIN
            special_external_customer
        ON
            special_external_customer.spcus_id = special_external_gcrequest.spexgc_company
        INNER JOIN
            access_page
        ON
            access_page.access_no = users.usertype
        INNER JOIN
            approved_request
        ON
            approved_request.reqap_trid = special_external_gcrequest.spexgc_id
        INNER JOIN
            users as approvedprep
        ON
            approvedprep.user_id = approved_request.reqap_preparedby';
    $limit = '';
    $request = getSelectedData($link,$table,$select,$where,$join,$limit);

    if(!count($request) > 0 )
    {
        $hasError = true;
    }

?>

<div class="main fluid">    
    <div class="row form-container">
        <div class="col-md-12">
            <div class="panel with-nav-tabs panel-info">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Special External GC Releasing</a></li>
                        <!-- <li><a href="#tab2default" data-toggle="tab">Approved Details</a></li> -->
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1default">
                            <div class="row">
                                <?php if($hasError): 
                                ?>
                                    <div class="col-md-6">Something went wrong.</div>
                                <?php else: ?>
                                    <div class="col-md-5 form-horizontal">                                   
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label">RFSEGC #</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo $request->spexgc_num; ?>">
                                            </div>
                                        </div><!-- end form group -->
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label">Department:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo ucwords($request->title); ?>">
                                            </div>
                                        </div><!-- end form group -->
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label">Date Requested:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control input-sm input-display inptxt" readonly="readonly" value="<?php echo _dateFormat($request->spexgc_datereq); ?>">
                                            </div>
                                        </div><!-- end form group -->
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label">Time Requested:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo _timeFormat($request->spexgc_datereq); ?>">
                                            </div>
                                        </div><!-- end form group -->
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label">Date Needed:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo _dateFormat($request->spexgc_dateneed); ?>">
                                            </div>
                                        </div><!-- end form group -->
                                         <div class="form-group">
                                            <label class="col-sm-6 control-label">Customer:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control input-sm inptxt" id="totalgc" value="<?php echo ucwords($request->spcus_companyname); ?>" readonly="readonly">
                                            </div>
                                        </div><!-- end form group -->
                                         <div class="form-group">
                                            <label class="col-sm-6 control-label">Total Denomination:</label>
                                            <div class="col-sm-6">
                                                <div class="input-group">
                                                    <input name="approved" type="text" class="form-control input-sm inptxt" readonly="readonly" id="totdenom" value="<?php echo number_format(totalExternalRequest($link,$id)[0],2); ?>">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-info input-sm" id="approvedbud" type="button" onclick="viewCustomerGC(<?php echo $id; ?>);" title="View Details">
                                                            <span class="glyphicon glyphicon-search"></span>
                                                        </button>
                                                    </span>
                                                </div><!-- input group -->
                                            </div>
                                        </div><!-- end form group -->                                                          
                                        <div class="form-group">                                           
                                            <label class="col-sm-6 control-label">Payment Type</label>
                                            <?php if($request->spexgc_paymentype==1): ?>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control input-sm inptxt" id="totalgc" value="Cash" readonly="readonly">
                                                </div>    
                                            <?php else: ?>
                                            <div class="col-sm-6">
                                                <div class="input-group">
                                                    <input name="approved" id="app-apprby" type="text" class="form-control input-sm inptxt" readonly="readonly" value="Check">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-info input-sm" id="approvedbud" type="button" onclick="viewCheckInfo(<?php echo $id; ?>)" title="View Details">
                                                            <span class="glyphicon glyphicon-search"></span>
                                                        </button>
                                                    </span>
                                                </div><!-- input group -->
                                            </div>                                                
                                            <?php endif; ?>              

                                        </div><!-- end form group -->
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label">Payment Amount:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control input-sm inptxt" id="totalgc" value="<?php echo number_format($request->spexgc_payment); ?>" readonly="readonly">
                                            </div>
                                        </div><!-- end form group -->                                     

                                        <?php 
                                            $table = 'documents';
                                            $select = 'doc_fullpath';
                                            $where = "doc_trid='".$id."'
                                                AND
                                                    doc_type='Special External GC Request'";
                                            $join ='';
                                            $limit = '';
                                            $docs = getAllData($link,$table,$select,$where,$join,$limit);
                                        ?>

                                        <?php if(count($docs)>0): ?>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label">Documents:</label>
                                            <div class="col-sm-6">               
                                                <ul id="lightgallery" class="list-unstyled row" style="margin-bottom:0px;">
                                                    <?php foreach ($docs as $d): ?>
                                                    <li class="col-xs-6 col-sm-4 col-md-4" data-src="../assets/images/<?php echo $d->doc_fullpath;?>">
                                                        <a href="" class="thumbnail">
                                                        <img class="img-responsive" src="../assets/images/<?php echo $d->doc_fullpath; ?>">
                                                        </a>
                                                    </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div><!-- end form group -->
                                        <?php endif; ?>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label">Request Remarks:</label>
                                            <div class="col-sm-6">
                                                <textarea class="form-control input-sm inptxt" readonly="readonly"><?php echo $request->spexgc_remarks; ?></textarea>
                                            </div>
                                        </div><!-- end form group -->
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label">Requested by:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control input-sm inptxt" value="<?php echo ucwords($request->prep); ?>" readonly="readonly">
                                            </div>
                                        </div><!-- end form group -->
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label">Date Approved:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control input-sm inptxt" value="<?php echo _dateFormat($request->reqap_date); ?>" readonly="readonly">
                                            </div>
                                        </div><!-- end form group -->
                                        <?php if(!empty($request->reqap_doc)): ?>
                                            <div class="form-group">
                                                <label class="col-sm-6 control-label">Approved Document:</label>
                                                <div class="col-sm-6">
                                                    <ul id="lightgallery1" class="list-unstyled row" style="margin-bottom:0px;">  
                                                        <li class="col-xs-6 col-sm-4 col-md-4" data-src="../assets/images/externalDocs/<?php echo $request->reqap_doc; ?>">
                                                            <a href="" class="thumbnail">
                                                            <img class="img-responsive" src="../assets/images/externalDocs/<?php echo $request->reqap_doc; ?>">
                                                            </a>
                                                        </li>                                                        
                                                    </ul>
                                                </div>
                                            </div><!-- end form group -->                                            
                                        <?php endif; ?>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label">Approved Remarks:</label>
                                            <div class="col-sm-6">
                                                <textarea class="form-control input-sm inptxt" readonly="readonly"><?php echo $request->reqap_remarks; ?></textarea>
                                            </div>
                                        </div><!-- end form group -->
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label">Checked By:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control input-sm inptxt" value="<?php echo ucwords($request->reqap_checkedby); ?>" readonly="readonly">
                                            </div>
                                        </div><!-- end form group -->    
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label">Approved By:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control input-sm inptxt" value="<?php echo ucwords($request->reqap_approvedby); ?>" readonly="readonly">
                                            </div>
                                        </div><!-- end form group -->        
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label">Prepared By:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control input-sm inptxt" value="<?php echo ucwords($request->apprep); ?>" readonly="readonly">
                                            </div>
                                        </div><!-- end form group -->                                
                                    </div>
                                    <div class="col-md-7 form-horizontal">                                    
                                        <table class="table" id="storeRequestList">
                                            <thead>
                                                <tr>
                                                    <th>Lastname</th>
                                                    <th>Firstname</th>
                                                    <th>Middlename</th>
                                                    <th>Ext.</th>
                                                    <th>Denomination</th>
                                                    <th>Barcode</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 

                                                    $table="special_external_gcrequest_emp_assign";
                                                    $select = "special_external_gcrequest_emp_assign.spexgcemp_denom,
                                                            special_external_gcrequest_emp_assign.spexgcemp_fname,
                                                            special_external_gcrequest_emp_assign.spexgcemp_lname,
                                                            special_external_gcrequest_emp_assign.spexgcemp_mname,
                                                            special_external_gcrequest_emp_assign.spexgcemp_extname,
                                                            special_external_gcrequest_emp_assign.spexgcemp_barcode";
                                                    $where = "special_external_gcrequest_emp_assign.spexgcemp_trid='".$id."'";
                                                    $join = "";
                                                    $limit = "ORDER BY special_external_gcrequest_emp_assign.spexgcemp_id ASC";
                                                    $gcs = getAllData($link,$table,$select,$where,$join,$limit);                               
                                                    $total = 0;
                                                    foreach ($gcs as $key):
                                                    $total +=$key->spexgcemp_denom;
                                                ?>
                                                <tr>
                                                    <td><?php  echo ucwords($key->spexgcemp_lname); ?></td>
                                                    <td><?php  echo ucwords($key->spexgcemp_fname); ?></td>
                                                    <td><?php  echo ucwords($key->spexgcemp_mname); ?></td>
                                                    <td><?php  echo ucwords($key->spexgcemp_extname); ?></td>
                                                    <td><?php  echo number_format($key->spexgcemp_denom,2); ?></td>
                                                    <td><?php  echo $key->spexgcemp_barcode; ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                        <form action="../ajax.php?action=specialgcreleasing" method="POST" id="gcreleased">
                                            <input type="hidden" value="<?php echo $id; ?>" id="trid" name="trid">
                                            <div class="form-group">
                                                <label class="col-sm-6 control-label">Total GC:</label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control input-sm inptxt" value="<?php echo count($gcs)?>" readonly="readonly" id="scannedgc">
                                                </div>
                                            </div><!-- end form group -->  
                                            <div class="form-group">
                                                <label class="col-sm-6 control-label">Total Denomination:</label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control input-sm inptxt" value="<?php echo number_format($total,2); ?>" readonly="readonly" id="totdenomsca">
                                                </div>
                                                </div><!-- end form group -->  
                                            <div class="form-group">
                                                <label class="col-sm-6 control-label">Remarks:</label>
                                                <div class="col-sm-6">
                                                    <textarea class="form-control input-sm inptxt" name="remarks" autofocus></textarea>
                                                </div>
                                            </div><!-- end form group -->
                                            <div class="form-group">
                                                <label class="col-sm-6 control-label">Received By:</label>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control input-sm inptxt" name="receiver" id="receiver" autofocus required>
                                                </div>
                                            </div><!-- end form group --> 
                                            <div class="form-group">
                                                <label class="col-sm-6 control-label">Released By:</label>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control input-sm inptxt" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>" readonly="readonly">
                                                </div>
                                            </div><!-- end form group -->  
                                            <div class="response">
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-8 col-sm-4">
                                                    <button class="btn btn-primary btn-block" id="gcreleasedbut"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Submit</button>
                                                </div>
                                            </div><!-- end form group -->  
                                        </form>

                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- <div class="tab-pane fade" id="tab2default">Sample</div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/store.js"></script>
<?php include 'footer.php' ?>