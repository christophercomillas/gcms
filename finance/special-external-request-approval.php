<?php 
	session_start();
	include '../function.php';
	require 'header.php';
	require '../menu.php';
    $hasError = false;
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
        special_external_gcrequest.spexgc_type,
        CONCAT(users.firstname,' ',users.lastname) as prep,
        special_external_customer.spcus_companyname,
        special_external_gcrequest.spexgc_remarks,
        special_external_gcrequest.spexgc_payment,
        special_external_gcrequest.spexgc_paymentype,
        special_external_gcrequest.spexgc_payment_arnum,
        access_page.title";
    $where = "special_external_gcrequest.spexgc_status='pending'
        AND
            special_external_gcrequest.spexgc_id='".$id."'
        ";
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
            access_page.access_no = users.usertype';
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
                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Special External GC Request Approval Form</a></li>
                        <!-- <li><a href="#tab2default" data-toggle="tab">Default 2</a></li> -->
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
                                    <div class="col-md-6">                                       
                                        <form action="../ajax.php?action=specialgcfinanceapproval" method="POST" id="specialgcfinanceapproval" class="form-horizontal">
                                            <input type="hidden" value="<?php echo $id; ?>" id="requestid" name="requestid">
                                            <input type="hidden" value="<?php echo currentBudget($link); ?>" name="curbudget" id="curbudget">
                                            <div class="form-group form-container">
                                                <div class="col-md-offset-4 col-md-8">
                                                <div class="box bot-margin">
                                                    <div class="box-header"><h4><i class="fa fa-inbox"></i> Current Budget</h4></div>
                                                    <div class="box-content">
                                                        <h3 class="current-budget">&#8369 <span id="curbudget"><?php echo number_format(currentBudget($link),2); ?></span></h3>
                                                    </div>
                                                </div> 
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-6 control-label"><span class="requiredf">*</span>Request Status:</label>
                                                <div class="col-sm-6">
                                                    <select class="form form-control input-sm inptxt" name="status" id="status" required autofocus />
                                                        <option value="">-Select-</option>
                                                        <option value="1">Approved</option>
                                                        <option value="2">Cancel</option>
                                                    </select>
                                                </div>                      
                                            </div><!-- end form group -->
                                            <div class="form-group">
                                                <label class="col-sm-6 control-label newProdStatus">Date Approved/Cancel:</label>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control input-sm inptxt" value="<?php echo _dateFormat($todays_date); ?>" readonly="readonly">  
                                                </div>
                                            </div><!-- end form group -->
                                            <div class="form-group">
                                                <label class="col-sm-6 control-label">Upload Document:</label>
                                                <div class="col-sm-6">
                                                    <input type="file" id='upload' class="form-control input-sm" name="docs[]" accept="image/*" />                                   
                                                </div>
                                            </div><!-- end form group -->
                                            <div class="form-group">
                                                <label class="col-sm-6 control-label"><span class="requiredf">*</span>Remarks:</label>
                                                <div class="col-sm-6">
                                                    <textarea class="form form-control input-sm inptxt tarea1" name="remark" id="remark" required></textarea>
                                                </div>
                                            </div><!-- end form group -->
                                            <div class="form-group">
                                                <label class="col-sm-6 control-label"><span class="requiredf">*</span>Checked by:</label>
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <input name="checked" id="app-checkby" type="text" class="form-control input-sm inptxt" readonly="readonly" required="required">
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-info input-sm" id="checkbud" type="button" onclick="requestAssig(<?php echo $_SESSION['gc_usertype']; ?>,1);">
                                                                <span class="glyphicon glyphicon-search"></span>
                                                            </button>
                                                        </span>
                                                    </div><!-- input group -->
                                                </div>
                                            </div><!-- end form group -->
                                            <div class="form-group">
                                                <label class="col-sm-6 control-label"><span class="requiredf">*</span>Approved by:</label>
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <input name="approved" id="app-apprby" type="text" class="form-control input-sm inptxt" readonly="readonly" required="required">
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-info input-sm" id="approvedbud" type="button" onclick="requestAssig(<?php echo $_SESSION['gc_usertype']; ?>,2);">
                                                                <span class="glyphicon glyphicon-search"></span>
                                                            </button>
                                                        </span>
                                                    </div><!-- input group -->
                                                </div>
                                            </div><!-- end form group -->
                                            <div class="form-group">
                                                <label class="col-sm-6 control-label label-prepared">Prepared By:</label>
                                                <div class="col-sm-6">
                                                    <input type="text" readonly="readonly" class="form form-control input-sm inptxt" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>" />
                                                </div>
                                            </div><!-- end form group -->
                                            <div class="response">
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-7 col-sm-5">
                                                    <button type="submit" class="btn btn-block btn-primary" id="externalbtn"> <span class="glyphicon glyphicon-share" aria-hidden="true"></span> Submit</button>
                                                </div>
                                            </div>
                                        </form>                                        
                                    </div>
                                    <div class="col-md-6 form-horizontal">
                                        <h4 class="preqdetails">Special External GC Request Details</h4>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">RFSEGC #</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo $request->spexgc_num; ?>">
                                            </div>
                                        </div><!-- end form group -->
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Department:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo ucwords($request->title); ?>">
                                            </div>
                                        </div><!-- end form group -->
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Date Requested:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control input-sm input-display inptxt" readonly="readonly" value="<?php echo _dateFormat($request->spexgc_datereq); ?>">
                                            </div>
                                        </div><!-- end form group -->
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Time Requested:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo _timeFormat($request->spexgc_datereq); ?>">
                                            </div>
                                        </div><!-- end form group -->
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Date Needed:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo _dateFormat($request->spexgc_dateneed); ?>">
                                            </div>
                                        </div><!-- end form group -->
                                         <div class="form-group">
                                            <label class="col-sm-5 control-label">Customer:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control input-sm inptxt" id="totalgc" value="<?php echo ucwords($request->spcus_companyname); ?>" readonly="readonly">
                                            </div>
                                        </div><!-- end form group -->
                                         <div class="form-group">
                                            <label class="col-sm-5 control-label">Total Denomination:</label>
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
                                            <label class="col-sm-5 control-label">Payment Type</label>
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
                                            <label class="col-sm-5 control-label">Payment Amount:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control input-sm inptxt" id="totalgc" value="<?php echo number_format($request->spexgc_payment,2); ?>" readonly="readonly">
                                            </div>
                                        </div><!-- end form group -->  

                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">AR #:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control input-sm inptxt" id="arnum" value="<?php echo $request->spexgc_payment_arnum; ?>" readonly="readonly">
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
                                            <label class="col-sm-5 control-label">Documents:</label>
                                            <div class="col-sm-6">               
                                                <ul id="lightgallery" class="list-unstyled row" style="margin-bottom:0px;">
                                                    <?php foreach ($docs as $d): ?>
                                                    <li class="col-xs-6 col-sm-4 col-md-4" data-src="../assets/images/<?php echo $d->doc_fullpath;?>">
                                                        <a href="" class="thumbnail">
                                                        <img class="img-responsive theight  " src="../assets/images/<?php echo $d->doc_fullpath; ?>">
                                                        </a>
                                                    </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div><!-- end form group -->
                                        <?php endif; ?>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Remarks:</label>
                                            <div class="col-sm-6">
                                                <textarea class="form-control input-sm inptxt" readonly="readonly"><?php echo $request->spexgc_remarks; ?></textarea>
                                            </div>
                                        </div><!-- end form group -->
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Requested by:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control input-sm inptxt" value="<?php echo ucwords($request->prep); ?>" readonly="readonly">
                                            </div>
                                        </div><!-- end form group -->
                                    </div>
                                <?php endif; ?>
                        	</div>
                        </div>
                        <!-- <div class="tab-pane fade" id="tab2default">Default 2</div> -->
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/fin.js"></script>
<?php include 'footer.php' ?>