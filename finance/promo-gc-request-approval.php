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

    $group = getField($link,'usergroup','users','user_id',$_SESSION['gc_id']);
    
    $table = 'promo_gc_request';
    $select = "promo_gc_request.pgcreq_reqnum,
        promo_gc_request.pgcreq_datereq,
        promo_gc_request.pgcreq_id,
        promo_gc_request.pgcreq_dateneeded,
        promo_gc_request.pgcreq_total,
        CONCAT(users.firstname,' ',users.lastname) as user,
        access_page.title,
        promo_gc_request.pgcreq_group,
        promo_gc_request.pgcreq_tagged,
        promo_gc_request.pgcreq_doc,
        promo_gc_request.pgcreq_remarks,
        promo_gc_request.pgcreq_total,
        promo_gc_request.pgcreq_group_status,
        CONCAT(rec_user.firstname,' ',rec_user.lastname) as recby";
    $where = "promo_gc_request.pgcreq_status='pending'
        AND
            (promo_gc_request.pgcreq_group_status=''
        OR 
            promo_gc_request.pgcreq_group_status='approved'
        )
        AND 
            promo_gc_request.pgcreq_id='".$id."'";
    $join = 'INNER JOIN
            users
        ON
            users.user_id = promo_gc_request.pgcreq_reqby
            INNER JOIN
            access_page
        ON
            access_page.access_no=users.usertype
        LEFT JOIN
            approved_request
        ON
            approved_request.reqap_trid = promo_gc_request.pgcreq_id
        LEFT JOIN
            users as rec_user
        ON
            rec_user.user_id = approved_request.reqap_preparedby';
    $limit = 'ORDER BY pgcreq_id ASC';

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
                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Promo GC Request Approval Form</a></li>
                        <!-- <li><a href="#tab2default" data-toggle="tab">Default 2</a></li> -->
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1default">
                        	<div class="row">
                                <?php if($hasError): ?>
                                    <div class="col-md-6">Something went wrong.</div>
                                <?php else: ?>
                                    <div class="col-md-6">
                                        <div class="box bot-margin">
                                            <div class="box-header"><h4><i class="fa fa-inbox"></i> Current Budget</h4></div>
                                            <div class="box-content">
                                                <h3 class="current-budget">&#8369 <span id="curbudget"><?php echo number_format(currentBudget($link),2); ?></span></h3>
                                            </div>
                                        </div>
                                        <form action="../ajax.php?action=promogcfinanceapproval" method="POST" id="promogcfinanceapproval" class="form-horizontal">                                            
                                            <input type="hidden" value="<?php echo $request->pgcreq_id; ?>" id="requestid" name="requestid">
                                            <?php if($request->pgcreq_group_status=='approved'): ?>
                                                <div class="form-group form-container">
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
                                                <div class="hide-cancel">
                                                    <div class="form-group">
                                                        <label class="col-sm-6 control-label">Upload Document:</label>
                                                        <div class="col-sm-6">
                                                            <input type="file" id='upload' class="form-control input-sm" name="docs[]" accept="image/*" />                                   
                                                        </div>
                                                    </div><!-- end form group -->
                                                    <div class="form-group">
                                                        <label class="col-sm-6 control-label"><span class="requiredf">*</span>Remarks:</label>
                                                        <div class="col-sm-6">
                                                            <textarea class="form form-control input-sm inptxt tarea" name="remark" id="remark" required></textarea>
                                                        </div>
                                                    </div><!-- end form group -->
                                                </div><!-- end hide-cancel -->
                                                <div class="form-group">
                                                    <label class="col-sm-6 control-label"><span class="requiredf">*</span>Recommended by:</label>
                                                    <div class="col-sm-6">
                                                        <div class="input-group">
                                                            <input name="checked" type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo ucwords($request->recby); ?>" required="required">
                                                            <span class="input-group-btn">
                                                                <button class="btn btn-info input-sm" id="checkbud" type="button" onclick="recomby(<?php echo $request->pgcreq_id; ?>)">
                                                                    <span class="glyphicon glyphicon-eye-open"></span>
                                                                </button>
                                                            </span>
                                                        </div><!-- input group -->
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
                                                    <div class="col-sm-offset-8  col-sm-4">
                                                        <button id="btn" type="submit" class="btn btn-block btn-primary"><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span> Submit</button>
                                                    </div>
                                                </div><!-- end form group -->
                                            <?php else: ?>
                                                <div class="alert alert-warning alertrecommedation">
                                                    <span class="requiredf">*</span> Promo GC Request needs Retail Group <?php echo $request->pgcreq_group; ?> Approval.
                                                </div>  
                                            <?php endif; ?>
                                        </form>                                   
                                    </div>
                                    <div class="col-md-6 form-horizontal">
                                        <h4 class="preqdetails">Promo GC Request Details</h4>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">RFPROM #</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo $request->pgcreq_reqnum; ?>">
                                            </div>
                                        </div><!-- end form group -->
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Department:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo $request->title; ?>" readonly="readonly">
                                            </div>
                                        </div><!-- end form group -->
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Retail Group:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo 'Group '.$request->pgcreq_group; ?>" readonly="readonly">
                                            </div>
                                        </div><!-- end form group -->
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Date Requested:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control input-sm input-display inptxt" readonly="readonly" value="<?php echo _dateFormat($request->pgcreq_datereq); ?>">
                                            </div>
                                        </div><!-- end form group -->
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Time Requested:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo _timeFormat($request->pgcreq_datereq); ?>">
                                            </div>
                                        </div><!-- end form group -->
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Date Needed:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo _dateFormat($request->pgcreq_dateneeded); ?>" readonly="readonly">
                                            </div>
                                        </div><!-- end form group -->                   
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Total GC Budget:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control input-sm inptxt" id="totalgc" readonly="readonly" value="<?php echo number_format($request->pgcreq_total,2); ?>" readonly="readonly">
                                            </div>
                                        </div><!-- end form group -->
                                        <?php if($request->pgcreq_doc !=''): ?>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Request Document:</label>
                                            <div class="col-sm-6">
                                                <a class="btn btn-block btn-default" href='../assets/images/promoRequestFile/download.php?file=<?php echo $request->pgcreq_doc; ?>'><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download</a>
                                            </div>
                                        </div><!-- end form group -->
                                        <?php endif; ?>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Remarks:</label>
                                            <div class="col-sm-6">
                                                <textarea class="form-control input-sm inptxt" readonly="readonly"><?php echo $request->pgcreq_remarks; ?></textarea>
                                            </div>
                                        </div><!-- end form group -->
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Requested by:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control input-sm inptxt" value="<?php echo ucwords($request->user); ?>" readonly="readonly">
                                            </div>
                                        </div><!-- end form group -->
                                        
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Denomination</th>
                                                    <th>Quantity</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    $table = 'promo_gc_request_items';
                                                    $select = 'promo_gc_request_items.pgcreqi_qty,
                                                        denomination.denomination';
                                                    $where = "promo_gc_request_items.pgcreqi_trid='$id'";
                                                    $join = 'INNER JOIN
                                                            denomination
                                                        ON
                                                            denomination.denom_id = promo_gc_request_items.pgcreqi_denom';
                                                    $limit ='ORDER BY denomination ASC';
                                                    $denoms = getAllData($link,$table,$select,$where,$join,$limit);
                                                    $total = 0;
                                                    foreach ($denoms as $d):
                                                    $subtotal = 0;

                                                ?>  
                                                    <tr>
                                                        <td><?php echo number_format($d->denomination,2); ?></td>
                                                        <td>
                                                            <?php 
                                                                echo $d->pgcreqi_qty; 
                                                                $subtotal = $d->denomination * $d->pgcreqi_qty;
                                                                $total+=$subtotal;
                                                            ?>
                                                        </td>

                                                        <td><?php echo number_format($subtotal,2); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                    <tr>
                                                        <td></td>
                                                        <td>Total:</td>
                                                        <td><?php echo number_format($total,2); ?></td>
                                                    </tr>
                                            </tbody>
                                        </table>

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