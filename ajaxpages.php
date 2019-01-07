<?php
session_start();
include 'function.php';

if(isset($_GET['page']))
{
	$page = $_GET['page'];
	
	if($page=='promo-gc-request-list-pending')
	{
	    $group = getField($link,'usergroup','users','user_id',$_SESSION['gc_id']);
	    
	    $table = 'promo_gc_request';
	    $select = "promo_gc_request.pgcreq_reqnum,
	        promo_gc_request.pgcreq_datereq,
	        promo_gc_request.pgcreq_id,
	        promo_gc_request.pgcreq_dateneeded,
	        promo_gc_request.pgcreq_total,
	        CONCAT(users.firstname,' ',users.lastname) as user";
	    $where = "promo_gc_request.pgcreq_group!=''
            AND
                promo_gc_request.pgcreq_tagged='1'
	        AND
	            (promo_gc_request.pgcreq_group_status=''
	        AND
	            promo_gc_request.pgcreq_status='pending')
	        OR 
	            (promo_gc_request.pgcreq_group_status='approved'
	        AND
	            promo_gc_request.pgcreq_status='pending')
	        ";
	    $join = 'INNER JOIN
	            users
	        ON
	            users.user_id = promo_gc_request.pgcreq_reqby';
	    $limit = 'ORDER BY pgcreq_id ASC';

	    $request = getAllData($link,$table,$select,$where,$join,$limit);

	    // if(!hasPageAccessView($link,1,$_SESSION['gc_id']))
	    // {
	    // 	echo 'Page not found.';
	    // 	exit();
	    // }
?>
	<div class="row form-container">
    	<div class="col-md-12">
            <div class="panel with-nav-tabs panel-info">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Pending Promo GC Request</a></li>
                        <!-- <li><a href="#tab2default" data-toggle="tab">Default 2</a></li> -->
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1default">
                        	<div class="row">
                                <div class="col-md-12">
                                    <table class="table" id="storeRequestList">
                                        <thead>
                                            <tr>
                                                <th>RFPROM #</th>
                                                <th>Date Requested</th>
                                                <th>Date Needed</th>
                                                <th>Total GC</th>
                                                <th>Requested by</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($request as $key): ?>
                                                <tr onclick="window.document.location='#/promo-request/<?php echo $key->pgcreq_id;  ?>'">
                                                    <td><?php echo $key->pgcreq_reqnum; ?></td>
                                                    <td><?php echo _dateFormat($key->pgcreq_datereq); ?></td>
                                                    <td><?php echo _dateFormat($key->pgcreq_dateneeded); ?></td>
                                                    <td><?php echo number_format($key->pgcreq_total,2); ?></td>
                                                    <td><?php echo ucwords($key->user); ?></td>
                                                </tr>
                                            <?php endforeach ?>
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
	<script type="text/javascript">
        $('#storeRequestList').DataTable( {
            "order": [[ 0, "desc" ]]
        } );
	</script>
<?php 
	}
	elseif($page=='promo-gc-request-viewupdate') 
	{
		if(!isset($_GET['reqId']) || isset($_GET['reqId'])=='')
		{
			exit();
		}

		$reqid = (int)$_GET['reqId'];

		// check if request id exist

		if(!checkIfExist($link,'pgcreq_id','promo_gc_request','pgcreq_id',$reqid))
		{
			echo 'Page not found.';
			exit();
		}
		
	    // if(!hasPageAccessView($link,1,$_SESSION['gc_id']))
	    // {
	    // 	echo 'Page not found.';
	    // 	exit();
	    // }
		$approved = false;
		$recom = false;

    	if(checkIfExist2($link,'pgcreq_status','promo_gc_request','pgcreq_status','pgcreq_id','approved',$reqid))
    	{
    		$approved = true;
    		$recom = true;
       	}
        else 
        {
            // get user tag
            $promotag = getField($link,'promo_tag','users','user_id',$_SESSION['gc_id']);
        }


        if(!$approved)
        {
            switch ($promotag) {
                case '1':
                    //check if promo request already recommended by retail group

                    if(checkIfExist2($link,'pgcreq_status','promo_gc_request','pgcreq_group_status','pgcreq_id','',$reqid))
                    {
                        displayPromoRequestforUpdate($link,$reqid);
                    }
                    else 
                    {
                        displayRecommendedPromoRequest($link,$reqid);
                    }
                    break;
                
                default:
                    echo 'Something went wrong. (Please Specify User Group Tag)';
                    break;
            }
        }
        else 
        {
            echo 'Request already approved.';
        }



    	// else 
    	// {
    	// 	if(checkIfExist2($link,'pgcreq_status','promo_gc_request','pgcreq_group_status','pgcreq_id','approved',$reqid))
    	// 	{
    	// 		$recom = true;
    	// 	}
    	// }

	    // if(!hasPageAccessUpdate($link,1,$_SESSION['gc_id']))
	    // {
	    // 	//check request if already recommended

	    // 	if($approved)
	    // 	{
	    // 		//display
	    // 		echo 'Request already approved.';
	    // 	}
	    // 	elseif($recom)
	    // 	{
	    // 		displayRecommendedPromoRequest($link,$reqid);
	    // 	}
	    // 	else 
	    // 	{
	    // 		displayPromoRequestforUpdate($link,$reqid);
	    // 	}
	    // }
	    // else 
	    // {
	    // 	echo 'display';
	    // }

	}
	elseif($page=='promogc-request-approved-list')
	{
        // if(!hasPageAccessView($link,1,$_SESSION['gc_id']))
        // {
        //     echo 'Page not foundx';
        //     exit();
        // }
		displayApprovedPromoRequestList($link);
	}
	elseif($page=='promogc-request-approved-view')
	{
		if(!isset($_GET['reqId']) || isset($_GET['reqId'])=='')
		{
			exit();
		}

		$reqid = (int)$_GET['reqId'];

        // if(!hasPageAccessView($link,1,$_SESSION['gc_id']))
        // {
        //     echo 'Page not found user dont have access';
        //     exit();
        // }

		displayApprovedPromoRequestById($link,$reqid);
    }
    elseif($page=='pending-budget-list')
    {

        if(!hasPageAccessView($link,4,$_SESSION['gc_id']))
        {
            echo 'Page not found';
            exit();
        }
        
        $table = 'budget_request';
        $select = "budget_request.br_request,
            budget_request.br_no,
            budget_request.br_id,
            CONCAT(users.firstname,' ',users.lastname) as prepby";
        $where = 'br_request_status=0';
        $join = 'INNER JOIN
                users
            ON
                users.user_id = budget_request.br_requested_by';
        $limit = '';
        $data = getAllData($link,$table,$select,$where,$join,$limit);
        ?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Dashboard
                <small>Pending Budget Request</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-8">
                    <div class="box">
                        <div class="box-body">
                            <table class="table" id="request">
                                <thead>
                                    <tr>
                                        <th>Budget Request #</th>
                                        <th>Budget Requested</th>
                                        <th>Requested By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data as $d): ?>
                                        <tr onclick="window.document.location='#/budget-pending-request/<?php echo $d->br_id; ?>'">
                                            <td><?php echo $d->br_no; ?></td>
                                            <td><?php echo number_format($d->br_request,2); ?></td>
                                            <td><?php echo ucwords($d->prepby); ?></td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box box-success box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Current Budget</h3>
                        <!-- /.box-tools -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <h2>&#8369 <?php echo number_format(currentBudget($link),2); ?></h2>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            </div>
        </section>
        <script type="text/javascript">
            $('#request').DataTable();
            $('ul.sidebar-menu li').removeClass('active');
            $('ul.sidebar-menu li:nth-child(2)').addClass('active');
            //$('ul.sidebar-menu li').addClass('active');

        </script>

        <?php
    }
    elseif($page=='pending-budget') 
    {

        if(!hasPageAccessApproval($link,4,$_SESSION['gc_id']))
        {
            echo '<section class="content-header">
                    <h1>
                       Page not found.
                    </h1>
                </section>';
            exit();            
        }

        if(!isset($_GET['reqid']) || isset($_GET['reqid'])=='')
        {
            echo '<section class="content-header">
                    <h1>
                       Page not found.
                    </h1>
                </section>';
            exit();  
        }

        $reqid = (int)$_GET['reqid'];
        $table = "budget_request";
        $select = "budget_request.br_no,
            budget_request.br_id,
            budget_request.br_request,
            budget_request.br_requested_at,
            budget_request.br_requested_needed,
            budget_request.br_remarks,
            budget_request.br_file_docno,
            CONCAT(users.firstname,' ',users.lastname) as prepby";
        $where = "budget_request.br_id='".$reqid."'
            AND
                budget_request.br_request_status='0'";
        $join = "INNER JOIN
                users
            ON
                users.user_id = budget_request.br_requested_by";
        $limit = "";

        $data = getSelectedData($link,$table,$select,$where,$join,$limit);
        ?>
        <section class="content">
            <div class="row">
                <div class="col-md-8">
                    <!-- Horizontal Form -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Budget Request # <?php echo $data->br_no; ?></h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form class="form-horizontal" id="approvedBudgetReq" action="../ajax.php?action=budgetApproval" method="POST">
                            <div class="box-body">
                                <input type="hidden" id="reqid" value="<?php echo $data->br_id; ?>">
                                <div class="form-group">
                                    <label for="dateRequested" class="col-sm-4 control-label">Date & Time Requested</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" value="<?php echo _dateFormat($data->br_requested_at).' '._timeFormat($data->br_requested_at); ?>" disabled>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="dateNeeded" class="col-sm-4 control-label">Date Needed</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" value="<?php echo _dateFormat($data->br_requested_needed); ?>" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dateNeeded" class="col-sm-4 control-label">Amount Requested</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control input-lg" id="amount" value="<?php echo number_format($data->br_request,2); ?>" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dateNeeded" class="col-sm-4 control-label">Amount in words</label>
                                    <div class="col-sm-5">
                                        <textarea class="form-control" disabled id="inwords"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dateNeeded" class="col-sm-4 control-label">Remarks</label>
                                    <div class="col-sm-5">
                                        <textarea class="form-control" disabled><?php echo $data->br_remarks; ?></textarea>
                                    </div>
                                </div>
                                <?php if(!empty($data->br_file_docno)): ?>
                                    <div class="form-group">
                                        <label for="dateNeeded" class="col-sm-4 control-label">Document Uploaded:</label>
                                        <div class="col-sm-5">
                                            <a class="btn btn-block btn-default" href='../assets/images/budgetRequestScanCopy/download.php?file=<?php echo $data->br_file_docno; ?>'><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download</a>
                                        </div>
<!--                                         <div class="input-group">
                                            <input name="checked" id="app-checkby" type="text" class="form-control input-sm inptxt reqfield" readonly="readonly" required="required">
                                            <span class="input-group-btn">
                                                <button class="btn btn-info input-sm" id="checkbud" onclick="requestAssig(2,1);" type="button">
                                                <span class="glyphicon glyphicon-search"></span>
                                                </button>
                                            </span>
                                        </div> -->
                                    </div>
                                <?php endif; ?>
                                <div class="form-group">
                                    <label for="dateNeeded" class="col-sm-4 control-label">Requested By</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" value="<?php echo  ucwords($data->prepby); ?>" disabled>
                                    </div>
                                </div>
                                <div class="response">

                                </div>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                                <button type="button" onclick="window.document.location='#/budget-pending-request/'" class="btn btn-default" onclick="">Back</button>
                                <button type="submit" class="btn btn-info pull-right" id="btn">Approved</button>
                            </div>
                        <!-- /.box-footer -->
                        </form>    
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box box-success box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Current Budget</h3>
                        <!-- /.box-tools -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <h2>&#8369 <?php echo number_format(currentBudget($link),2); ?></h2>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>  
        </section>
        <script type="text/javascript">
            $('ul.sidebar-menu li').removeClass('active');
            $('ul.sidebar-menu li:nth-child(3)').addClass('active');
            $('textarea#inwords').val(toWords($('input#amount').val()));

            $('section.content').on('submit','form#approvedBudgetReq',function(event){ 
                event.preventDefault();

                var formURL = $(this).attr('action');

                BootstrapDialog.show({
                    title: 'Confirmation',
                    message: 'Are you sure you want to approved Budget Request?',
                    closable: true,
                    closeByBackdrop: false,
                    closeByKeyboard: true,
                    onshow: function(dialog) {
                        $("button#btn").prop("disabled",true);
                    },
                    onhidden: function(dialog){
                        $("button#btn").prop("disabled",false);
                    },
                    buttons: [{
                        icon: 'glyphicon glyphicon-ok-sign',
                        label: 'Yes',
                        cssClass: 'btn-primary',
                        hotkey: 13,
                        action:function(dialogItself){  
                            $buttons = this;
                            $buttons.disable();     
                            dialogItself.close();  

                            var reqid = $('input#reqid').val();
                            if(reqid==undefined)
                            {
                                $("button#btn").prop("disabled",false);
                                return false;
                            }     

                            $.ajax({
                                url:formURL,
                                type:'POST',
                                data: {reqid:reqid},
                                beforeSend:function(){
                                },
                                success:function(data){
                                    console.log(data);
                                    var data = JSON.parse(data);
                                    if(data['st'])
                                    {
                                        var dialog = new BootstrapDialog({
                                        message: function(dialogRef){
                                        var $message = $('<div>GC Request Saved.</div>');                   
                                            return $message;
                                        },
                                        closable: false
                                        });
                                        dialog.realize();
                                        dialog.getModalHeader().hide();
                                        dialog.getModalFooter().hide();
                                        dialog.getModalBody().css('background-color', '#0088cc');
                                        dialog.getModalBody().css('color', '#fff');
                                        dialog.open();
                                        setTimeout(function(){
                                            dialog.close();
                                        }, 1500);
                                        setTimeout(function(){
                                            window.location = '#/budget-pending-request/';
                                        }, 1700);   
                                    }
                                    else 
                                    {
                                        $('.response').html('<div class="alert alert-danger">'+data['msg']+'</div>');
                                    }
                                }
                            });

                            //dialogItself.close();                          

                        }
                    }, {
                        icon: 'glyphicon glyphicon-remove-sign',
                        label: 'No',
                        action: function(dialogItself){
                            dialogItself.close();
                        }
                    }]
                });
            });
        </script>

        <?php 
    }
    elseif ($page=='approved-budget-request') 
    {

        if(!hasPageAccessView($link,4,$_SESSION['gc_id']))
        {
            echo 'Page not found';
            exit();
        }

        $table = 'budget_request';
        $select = "budget_request.br_request,
            budget_request.br_no,
            budget_request.br_id,
            approved_budget_request.abr_approved_by";
        $where = 'br_request_status=1';
        $join = 'LEFT JOIN
                approved_budget_request
            ON
                approved_budget_request.abr_budget_request_id = budget_request.br_id';
        $limit = '';
        $data = getAllData($link,$table,$select,$where,$join,$limit);
        
        ?>
        <section class="content-header">
            <h1>
                Approved Budget Request
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-8">
                    <div class="box">
                        <div class="box-body">
                            <table class="table" id="request">
                                <thead>
                                    <tr>
                                        <th>Budget Request #</th>
                                        <th>Budget Requested</th>
                                        <th>Approved By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data as $d): ?>
                                        <tr onclick="window.document.location='#/approved-budget-request/<?php echo $d->br_id; ?>'">
                                            <td><?php echo $d->br_no; ?></td>
                                            <td><?php echo number_format($d->br_request,2); ?></td>
                                            <td><?php echo ucwords($d->abr_approved_by); ?></td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box box-success box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Current Budget</h3>
                        <!-- /.box-tools -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <h2>&#8369 <?php echo number_format(currentBudget($link),2); ?></h2>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            </div>
        </section>
        <script type="text/javascript">
            $('#request').DataTable();
            $('ul.sidebar-menu li').removeClass('active');
            $('ul.sidebar-menu li:nth-child(3)').addClass('active');   
        </script>
        <?php
    }
    elseif ($page=='approved-budget-request-single') 
    {        
        if(!hasPageAccessApproval($link,4,$_SESSION['gc_id']))
        {
            echo '<section class="content-header">
                    <h1>
                       Page not found.
                    </h1>
                </section>';
            exit();            
        }

        if(!isset($_GET['reqid']) || isset($_GET['reqid'])=='')
        {
            echo '<section class="content-header">
                    <h1>
                       Page not found.
                    </h1>
                </section>';
            exit();  
        }

        $reqid = (int)$_GET['reqid'];

        $table = 'budget_request';
        $select = "budget_request.br_id,
            budget_request.br_request,
            budget_request.br_requested_at,
            budget_request.br_no,
            budget_request.br_file_docno,
            budget_request.br_requested_needed,
            budget_request.br_remarks,
            CONCAT(brequest.firstname,' ',brequest.lastname) as breq,
            CONCAT(prepby.firstname,' ',prepby.lastname) as preq,
            approved_budget_request.abr_approved_by,
            approved_budget_request.abr_approved_at,
            approved_budget_request.abr_file_doc_no,
            approved_budget_request.abr_checked_by,
            approved_budget_request.approved_budget_remark";

        $where = "budget_request.br_request_status = '1'
            AND
                budget_request.br_id='".$reqid."'";
        $join = 'INNER JOIN
                users as brequest
            ON
                brequest.user_id = budget_request.br_requested_by
            LEFT JOIN
                approved_budget_request
            ON
                approved_budget_request.abr_budget_request_id  = budget_request.br_id
            LEFT JOIN
                users as prepby
            ON
                prepby.user_id = approved_budget_request.abr_prepared_by';
        $limit = '';

        $data = getSelectedData($link,$table,$select,$where,$join,$limit);

        if(count($data)==0)
        {
            echo '<section class="content-header">
                    <h1>
                       Page not found.
                    </h1>
                </section>';
            exit();  
        }        

        ?>
        <section class="content-header">
            <h1>
                Approved Budget
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-8">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Budget Request # 001</h3>                   
                        </div>
                        <div class="box-body form-horizontal">
                            <div class="form-group">
                                <label for="dateRequested" class="col-sm-4 control-label">Date & Time Requested</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" value="<?php echo _dateFormat($data->br_requested_at).' '._timeFormat($data->br_requested_at); ?>" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="dateNeeded" class="col-sm-4 control-label">Date Needed</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" value="<?php echo _dateFormat($data->br_requested_needed); ?>" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="dateNeeded" class="col-sm-4 control-label">Amount Requested</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control input-lg" id="amount" value="<?php echo number_format($data->br_request,2); ?>" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="dateNeeded" class="col-sm-4 control-label">Amount in words</label>
                                <div class="col-sm-5">
                                    <textarea class="form-control" disabled id="inwords"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="dateNeeded" class="col-sm-4 control-label">Remarks</label>
                                <div class="col-sm-5">
                                    <textarea class="form-control" disabled><?php echo $data->br_remarks; ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="dateNeeded" class="col-sm-4 control-label">Requested By</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" value="<?php echo ucwords($data->breq); ?>" disabled>
                                </div>
                            </div>   
                            <div class="form-group">
                                <label for="dateNeeded" class="col-sm-4 control-label">Date Approved</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" value="<?php echo _dateFormat($data->abr_approved_at); ?>" disabled>
                                </div>
                            </div>  
                            <div class="form-group">
                                <label for="dateNeeded" class="col-sm-4 control-label">Approved By</label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="<?php echo ucwords($data->abr_approved_by); ?>" disabled>
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default btn-flat"><i class="ion-ios-paper"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>            
                        </div>
                        <div class="box-footer">
                            <button type="button" onclick="window.document.location='#/budget-pending-request/'" class="btn btn-default" onclick="">Back</button>
                        </div>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box box-success box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Current Budget</h3>
                        <!-- /.box-tools -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <h2>&#8369 <?php echo number_format(currentBudget($link),2); ?></h2>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">

            $('#inwords').val(toWords($('#amount').val()));
            $('ul.sidebar-menu li').removeClass('active');
            $('ul.sidebar-menu li:nth-child(3)').addClass('active');               
        </script>
        <script type="text/javascript" src="../assets/js/funct.js"></script>
        <?php
    }
    elseif ($page=='promo-gc-released-list') 
    {
        promoGCReleasedList($link);        
    }
    elseif ($page=='promo-gc-released-single')
    {        
        //promo-gc-released-list
        // if(!isset($_GET['relid']) || isset($_GET['relid'])=='')
        // {
        //     echo '<section class="content-header">
        //             <h1>
        //                Page not found.
        //             </h1>
        //         </section>';
        //     exit();  
        // }

        $relid = (int)$_GET['relid'];
        promoGCReleasedSingle($link,$relid);
    }
    elseif($page=='reviewed-special-external-request-list')
    {
        reviewedSpecialExternalRequestList($link);
    }
    elseif($page=='reviewed-special-external-request-single')
    {
        if(!isset($_GET['reqId']) || isset($_GET['reqId'])=='')
        {
            echo '<section class="content-header">
                    <h1>
                       Page not found.
                    </h1>
                </section>';
            exit();  
        }

        $reqId = (int)$_GET['reqId'];
        reviewedSpecialExternalRequestSingle($link,$reqId);
    }
    elseif($page=='sample')
    {
        header('Content-type:application/json');
        $table = 'special_external_gcrequest';
        $select = 'special_external_gcrequest.spexgc_id,
            special_external_gcrequest.spexgc_num,
            special_external_gcrequest.spexgc_datereq,
            special_external_gcrequest.spexgc_dateneed,
            approved_request.reqap_approvedby,
            approved_request.reqap_date,
            special_external_customer.spcus_companyname';
        $where = "special_external_gcrequest.spexgc_status='approved'
            AND
                approved_request.reqap_approvedtype = 'Special External GC Approved'";
        $join = 'INNER JOIN
                special_external_customer
            ON
                special_external_customer.spcus_id = special_external_gcrequest.spexgc_company
            LEFT JOIN
                approved_request
            ON
                approved_request.reqap_trid = special_external_gcrequest.spexgc_id';
        $limit ='';
        $data = getAllData($link,$table,$select,$where,$join,$limit);
        $rows = [];
        foreach ($data as $key) {
            $rows[] = $key;
        }

        //$new_row

        $rows[] = '12';
        echo json_encode($rows);

    }
	else 
	{
        //last
		echo 'Something went wrong.';
	}
}


function reviewedSpecialExternalRequestSingle($link,$reqid)
{
    $table = 'special_external_gcrequest';
    $select = "special_external_gcrequest.spexgc_id,
        special_external_gcrequest.spexgc_num,
        CONCAT(req.firstname,' ',req.lastname) as reqby,
        special_external_gcrequest.spexgc_datereq,
        special_external_gcrequest.spexgc_dateneed,
        special_external_gcrequest.spexgc_remarks,
        special_external_gcrequest.spexgc_payment,
        special_external_gcrequest.spexgc_paymentype,
        special_external_customer.spcus_companyname,
        special_external_bank_payment_info.spexgcbi_bankname,
        special_external_bank_payment_info.spexgcbi_bankaccountnum,
        special_external_bank_payment_info.spexgcbi_checknumber,
        approved_request.reqap_remarks,
        approved_request.reqap_doc,
        approved_request.reqap_checkedby,
        approved_request.reqap_approvedby,
        approved_request.reqap_preparedby,
        approved_request.reqap_date,
        CONCAT(prep.firstname,' ',prep.lastname) as prepby";
    $where = "special_external_gcrequest.spexgc_status='approved'
        AND
            special_external_gcrequest.spexgc_id = '".$reqid."'
        AND
            approved_request.reqap_approvedtype='Special External GC Approved'";
    $join = 'INNER JOIN
            users as req
        ON
            req.user_id = special_external_gcrequest.spexgc_reqby
        INNER JOIN
            special_external_customer
        ON
            special_external_customer.spcus_id = special_external_gcrequest.spexgc_company  
        LEFT JOIN
            special_external_bank_payment_info
        ON
            special_external_bank_payment_info.spexgcbi_trid = special_external_gcrequest.spexgc_id
        INNER JOIN
            approved_request
        ON
            approved_request.reqap_trid = special_external_gcrequest.spexgc_id
        INNER JOIN 
            users as prep
        ON
            prep.user_id=approved_request.reqap_preparedby';
    $limit = '';

    $data = getSelectedData($link,$table,$select,$where,$join,$limit);

    if(count($data)==0)
    {
        echo '<section class="content-header">
                <h1>
                   Page not found.
                </h1>
            </section>';
        exit();          
    }

    $table = 'documents';
    $select ='doc_fullpath';
    $where  = "doc_trid='".$reqid."'
        AND
            doc_type='Special External GC Request'";
    $join = '';
    $limit ='';

    $docs  = getAllData($link,$table,$select,$where,$join,$limit);

    $table = 'special_external_gcrequest_emp_assign';
    $select ='spexgcemp_trid,
        spexgcemp_denom,
        spexgcemp_fname,
        spexgcemp_lname,
        spexgcemp_mname,
        spexgcemp_extname,
        spexgcemp_barcode';
    $where  = "spexgcemp_trid='".$reqid."'";
    $join = '';
    $limit ='';

    $gcs = getAllData($link,$table,$select,$where,$join,$limit);

    //review details
    $table ='approved_request';
    $select = "approved_request.reqap_remarks,
        approved_request.reqap_date,
        CONCAT(users.firstname,' ',users.lastname) as rev";
    $where  = "approved_request.reqap_trid='".$reqid."'
        AND
            approved_request.reqap_approvedtype='special external gc review'";
    $join ='INNER JOIN
            users
        ON
            users.user_id = approved_request.reqap_preparedby';
    $limit ='';

    $revDetails = getSelectedData($link,$table,$select,$where,$join,$limit);

    ?>
    <div class="row form-container">
        <div class="col-md-12">
            <div class="panel with-nav-tabs panel-info">
                <div class="panel-heading">
                    <button class="btn pull-right" onclick="window.location='#/reviewed-special-external-request/'">Back</button>
                    <ul class="nav nav-tabs">
                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Special External GC Request #<?php echo $data->spexgc_num ?></a></li>
                        <li><a href="#tab2default" data-toggle="tab">GC Barcodes</a></li>
                    </ul>                    
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1default">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="row margin-bot-0">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="nobot">Date Requested</label>
                                                <input type="text" class="form-control inptxt bot-6" value="<?php echo _dateFormat($data->spexgc_datereq); ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="nobot">Date Needed</label>
                                                <input type="text" class="form-control inptxt bot-6" value="<?php echo _dateFormat($data->spexgc_dateneed); ?>" disabled>
                                            </div>
                                        </div>                                        
                                    </div>
                                    <div class="row margin-bot-0">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="nobot">Requested By</label>
                                                <input type="text" class="form-control inptxt bot-6" value="<?php echo ucwords($data->reqby); ?>" disabled>
                                            </div>
                                            <div class="form-group">
                                                <label class="nobot">Document(s)</label>
                                                <div>
                                                    <ul id="lightgallery" class="list-unstyled row" style="margin-bottom:0px;">
                                                        <?php foreach ($docs as $d): ?>
                                                        <li class="col-xs-6 col-sm-4 col-md-4" data-src="../assets/images/<?php echo $d->doc_fullpath;?>">
                                                            <a href="" class="thumbnail">
                                                            <img class="img-responsive theight" style="height:50px;" src="../assets/images/<?php echo $d->doc_fullpath; ?>">
                                                            </a>
                                                        </li>
                                                        <?php endforeach; ?>
                                                    </ul>                        
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="nobot">Remarks</label>
                                                <textarea class="form-control inptxt" disabled><?php echo $data->reqap_remarks; ?></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label class="nobot">Payment Type</label>
                                                <input type="text" class="form-control inptxt bot-6" value="<?php if($data->spexgc_paymentype==1){ echo 'Cash'; } else { echo 'Check'; } ?>" disabled>
                                            </div>
                                            <?php if($data->spexgc_paymentype==2): ?>
                                                <div class="form-group">
                                                    <label class="nobot">Bank Name</label>
                                                    <input type="text" class="form-control inptxt bot-6" value="<?php echo ucwords($data->spexgcbi_bankname); ?>" disabled>
                                                </div>
                                                <div class="form-group">
                                                    <label class="nobot">Check Number</label>
                                                    <input type="text" class="form-control inptxt bot-6" value="<?php echo ucwords($data->spexgcbi_checknumber); ?>" disabled>
                                                </div>
                                                <div class="form-group">
                                                    <label class="nobot">Check Amount</label>
                                                    <input type="text" class="form-control inptxt bot-6" value="<?php echo number_format($data->spexgc_payment,2); ?>" disabled>
                                                </div>
                                            <?php elseif($data->spexgc_paymentype==1): ?>
                                                <div class="form-group">
                                                    <label class="nobot">Amount</label>
                                                    <input type="text" class="form-control inptxt bot-6" value="<?php echo number_format($data->spexgc_payment,2); ?>" disabled>
                                                </div>
                                            <?php endif; ?>
                                        </div>                                        
                                    </div>
                                </div>
                                <div class="col-sm-6 margin-bot-0">
                                    <div class="approved-details" style="background-color: beige; padding:10px;">
                                        <div class="row margin-bot-0">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="nobot">Date Approved</label>
                                                    <input type="text" class="form-control inptxt bot-6" value="<?php echo _dateFormat($data->spexgc_datereq); ?>" disabled>
                                                </div>
                                            </div>                                      
                                        </div>

                                        <div class="row margin-bot-0">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="nobot">Document(s)</label>
                                                    <?php if(!empty($data->reqap_doc)):?>
                                                        <div class=""></div>
                                                    <?php else: ?>
                                                        <div class="">None</div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="nobot">Remarks</label>
                                                    <textarea class="form-control inptxt" disabled><?php echo $data->spexgc_remarks; ?></textarea>
                                                </div>
                                            </div>                                                                              
                                        </div>
                                        <div class="row margin-bot-0">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="nobot">Checked By</label>
                                                    <input type="text" class="form-control inptxt bot-6" value="<?php echo ucwords($data->reqap_checkedby); ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="nobot">Approved By</label>
                                                    <input type="text" class="form-control inptxt bot-6" value="<?php echo ucwords($data->reqap_approvedby); ?>" disabled>
                                                </div>
                                            </div>                                                                              
                                        </div>
                                        <div class="row margin-bot-0">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="nobot">Prepared By</label>
                                                    <input type="text" class="form-control inptxt bot-6" value="<?php echo ucwords($data->prepby); ?>" disabled>
                                                </div>
                                            </div>                                                                             
                                        </div>
                                    </div>
                                    <?php if(count($revDetails) > 0): ?>
                                    <div class="review-details" style="background-color: beige; padding:10px; margin-top:10px;">
                                        <div class="row margin-bot-0">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="nobot">Date Reviewed</label>
                                                    <input type="text" class="form-control inptxt bot-6" value="<?php echo _dateFormat($revDetails->reqap_date); ?>" disabled>
                                                </div>
                                            </div>                                      
                                        </div>
                                        <div class="row margin-bot-0">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="nobot">Remarks</label>
                                                    <textarea class="form-control inptxt" disabled><?php echo $revDetails->reqap_remarks; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="nobot">Reviewed by</label>
                                                    <input type="text" class="form-control inptxt bot-6" value="<?php echo ucwords($revDetails->rev); ?>" disabled>
                                                </div>
                                            </div>                                      
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab2default">
                            <table class="table" id="released">
                                <thead>
                                    <tr>
                                        <th>Barcode</th>
                                        <th>Denomination</th>
                                        <th>Lastname</th>
                                        <th>Firstname</th>
                                        <th>Middlename</th>
                                        <th>Name Ext.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($gcs as $gc): ?>
                                        <tr>
                                            <td><?php echo $gc->spexgcemp_barcode; ?></td>
                                            <td><?php echo number_format($gc->spexgcemp_denom,2); ?></td>
                                            <td><?php echo ucwords($gc->spexgcemp_lname); ?></td>
                                            <td><?php echo ucwords($gc->spexgcemp_fname); ?></td>
                                            <td><?php echo ucwords($gc->spexgcemp_mname); ?></td>
                                            <td><?php echo ucwords($gc->spexgcemp_extname); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $('#released').DataTable( {
            "order": [[ 0, "desc" ]]
        } );
        $('#lightgallery').lightGallery();
    </script>
<?php
}

function reviewedSpecialExternalRequestList($link)
{
    // if(!hasPageAccessView($link,3,$_SESSION['gc_id']))
    // {
    //     echo 'Page not found.';
    //     exit();
    // }
    $table = 'special_external_gcrequest';
    $select = 'special_external_gcrequest.spexgc_id,
        special_external_gcrequest.spexgc_num,
        special_external_gcrequest.spexgc_datereq,
        approved_request.reqap_approvedby,
        approved_request.reqap_date,
        special_external_customer.spcus_companyname';
    $where = "special_external_gcrequest.spexgc_reviewed = 'reviewed'
        AND
            approved_request.reqap_approvedtype = 'Special External GC Approved'";
    $join = 'INNER JOIN
            special_external_customer
        ON
            special_external_customer.spcus_id = special_external_gcrequest.spexgc_company
        LEFT JOIN
            approved_request
        ON
            approved_request.reqap_trid = special_external_gcrequest.spexgc_id';
    $limit ='';
    $data = getAllData($link,$table,$select,$where,$join,$limit);
?>
    <div class="row form-container">
        <div class="col-md-12">
            <div class="panel with-nav-tabs panel-info">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Reviewed Special External GC</a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1default">
                            <table class="table" id="released">
                                <thead>
                                    <tr>
                                        <th>RFSEGC #</th>
                                        <th>Date Requested</th>
                                        <th>Customer</th>
                                        <th>Date Approved</th>
                                        <th>Approved By</th>
                                        <th>Date Reviewed</th>
                                        <th>Reviewed By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data as $d): 
                                        $rev = getReviewed($link,$d->spexgc_id);
                                    ?>
                                       <tr class="clickable" onclick="window.location='#/reviewed-special-external-request/<?php echo $d->spexgc_id; ?>'">
                                            <td><?php echo $d->spexgc_num; ?></td>
                                            <td><?php echo _dateFormat($d->spexgc_datereq); ?></td>
                                            <td><?php echo ucwords($d->spcus_companyname); ?></td>
                                            <td><?php echo ucwords($d->reqap_approvedby); ?></td>
                                            <td><?php echo ucwords($d->reqap_approvedby); ?></td>
                                            <td><?php echo _dateFormat($rev->reqap_date); ?></td>
                                            <td><?php echo ucwords($rev->revby); ?></td>
                                        </tr>    
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $.extend( $.fn.dataTableExt.oStdClasses, {    
            "sLengthSelect": "selectsup"
        });
        $('#released').DataTable( {
            "order": [[ 0, "desc" ]]
        } );
    </script>
<?php
}

function promoGCReleasedSingle($link,$relid)
{

    // if(!hasPageAccessView($link,5,$_SESSION['gc_id']))
    // {
    //     echo 'Page not found.';
    //     exit();
    // }

    $table = 'promo_gc_release_to_details';
    $select = "promo_gc_release_to_details.prrelto_id,
        promo_gc_release_to_details.prrelto_relnumber,
        promo_gc_release_to_details.prrelto_trid,
        promo_gc_release_to_details.prrelto_docs,
        promo_gc_release_to_details.prrelto_checkedby,
        promo_gc_release_to_details.prrelto_approvedby,
        promo_gc_release_to_details.prrelto_date,
        promo_gc_release_to_details.prrelto_recby,
        promo_gc_release_to_details.prrelto_status,
        promo_gc_release_to_details.prrelto_remarks,
        promo_gc_release_to_details.prrelto_status,
        CONCAT(users.firstname,' ',users.lastname) as relby,
        promo_gc_request.pgcreq_reqnum";
    $where = "promo_gc_release_to_details.prrelto_id='".$relid."'";
    $join = 'INNER JOIN
            users
        ON
            users.user_id = promo_gc_release_to_details.prrelto_relby
        INNER JOIN
            promo_gc_request
        ON
            promo_gc_request.pgcreq_id = promo_gc_release_to_details.prrelto_trid';
    $limit = '';

    $data = getSelectedData($link,$table,$select,$where,$join,$limit);

    //var_dump($data);

    if(count($data)==0)
    {
        echo '<section class="content-header">
                <h1>
                   Page not found.
                </h1>
            </section>';
        exit();  
    }

    $table = 'promo_gc_release_to_items';
    $select ='promo_gc_release_to_items.prreltoi_barcode,
        denomination.denomination';
    $where = "promo_gc_release_to_items.prreltoi_relid='".$relid."'";
    $join = 'INNER JOIN
            gc
        ON
            gc.barcode_no = promo_gc_release_to_items.prreltoi_barcode
        INNER JOIN
            denomination
        ON
            denomination.denom_id = gc.denom_id';
    $limit = '';
    $gcs = getAllData($link,$table,$select,$where,$join,$limit);    

    $table = 'promo_gc_release_to_items';
    $select = 'SUM(denomination.denomination) as sum';
    $where = "promo_gc_release_to_items.prreltoi_relid='$relid'";
    $join = 'INNER JOIN
            gc
        ON
            gc.barcode_no = promo_gc_release_to_items.prreltoi_barcode
        INNER JOIN
            denomination
        ON
            denomination.denom_id = gc.denom_id';
    $limit = '';
    $totgc = getSelectedData($link,$table,$select,$where,$join,$limit);

    ?>

    <div class="row form-container">
        <div class="col-md-12">
            <div class="panel with-nav-tabs panel-info">
                <div class="panel-heading">
                    <button class="btn pull-right" onclick="window.location='#/promo-gc-released-list/'">Back</button>
                    <ul class="nav nav-tabs">
                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Promo GC Released #<?php echo sprintf("%03d", $data->prrelto_relnumber); ?> Details</a></li>
                    </ul>                    
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1default">
                            <div class="row">
                                <div class="col-sm-7">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="nobot">Date Released</label>
                                                <input type="text" class="form-control inptxt bot-6" value="<?php echo _dateFormat($data->prrelto_date); ?>" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="nobot">Remarks</label>
                                                <textarea class="form-control inptxt bot-6" disabled><?php echo $data->prrelto_remarks; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="nobot">Document Uploaded</label>
                                                <?php if(!empty($data->prrelto_docs)): ?>
                                                    <div>
                                                        <a class="btn btn-default" href='../assets/images/promoReleasedFile/download.php?file=<?php echo $data->prrelto_docs; ?>'><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download</a>
                                                    </div>
                                                <?php else: ?>
                                                    <div>None</div>
                                                <?php endif; ?>
                                            </div>
                                        </div>                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="nobot">Received By</label>
                                                <input type="text" class="form-control inptxt bot-6" value="<?php echo ucwords($data->prrelto_recby); ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="nobot">Released Type</label>
                                                <input type="text" class="form-control inptxt bot-6" value="<?php echo ucwords($data->prrelto_status); ?>" disabled>
                                            </div>
                                        </div>                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="nobot">Checked By</label>
                                                <input type="text" class="form-control inptxt bot-6" value="<?php echo ucwords($data->prrelto_checkedby); ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="nobot">Approved By</label>
                                                <input type="text" class="form-control inptxt bot-6" value="<?php echo ucwords($data->prrelto_approvedby); ?>" disabled>
                                            </div>
                                        </div>                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="nobot">Released By</label>
                                                <input type="text" class="form-control inptxt bot-6" value="<?php echo ucwords($data->relby); ?>" disabled>
                                            </div>
                                        </div>  
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="nobot">Total GC Amount</label>
                                                <input type="text" class="form-control inptxt bot-6" value="<?php echo number_format($totgc->sum,2); ?>" disabled>
                                            </div>
                                        </div>                                      
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <h4>Released GC's</h4>
                                    <table class="table" id="released">
                                        <thead>
                                            <tr>
                                                <th>Barcode</th>
                                                <th>Denomination</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($gcs as $gc): ?>
                                                <tr>
                                                    <td><?php echo $gc->prreltoi_barcode; ?></td>
                                                    <td><?php echo number_format($gc->denomination,2); ?></td>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $('#released').DataTable();
    </script>

    <?php 
}

function promoGCReleasedList($link)
{
    // if(!hasPageAccessView($link,5,$_SESSION['gc_id']))
    // {
    //     echo 'Page not found.';
    //     exit();
    // }

    $table = 'promo_gc_release_to_details';
    $select = "promo_gc_release_to_details.prrelto_id,
        promo_gc_release_to_details.prrelto_relnumber,
        promo_gc_release_to_details.prrelto_trid,
        promo_gc_release_to_details.prrelto_docs,
        promo_gc_release_to_details.prrelto_checkedby,
        promo_gc_release_to_details.prrelto_approvedby,
        promo_gc_release_to_details.prrelto_date,
        promo_gc_release_to_details.prrelto_recby,
        promo_gc_release_to_details.prrelto_status,
        CONCAT(users.firstname,' ',users.lastname) as relby,
        promo_gc_request.pgcreq_reqnum";
    $where = '1';
    $join = 'INNER JOIN
            users
        ON
            users.user_id = promo_gc_release_to_details.prrelto_relby
        INNER JOIN
            promo_gc_request
        ON
            promo_gc_request.pgcreq_id = promo_gc_release_to_details.prrelto_trid'; 
    $limit = 'ORDER BY
            promo_gc_release_to_details.prrelto_id
        DESC';
    $data = getAllData($link,$table,$select,$where,$join,$limit);
?>
    <div class="row form-container">
        <div class="col-md-12">
            <div class="panel with-nav-tabs panel-info">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Promo GC Released List</a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1default">
                            <table class="table" id="released">
                                <thead>
                                    <tr>
                                        <th>Released #</th>
                                        <th>Promo Request #</th>
                                        <th>Date Released</th>
                                        <th>Released By</th>
                                        <th>Received By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data as $d): ?>
                                        <tr class="clickable" onclick="window.location='#/promo-gc-released-list/<?php echo $d->prrelto_id; ?>'">
                                            <td><?php echo sprintf("%03d",$d->prrelto_relnumber); ?></td>
                                            <td><?php echo $d->pgcreq_reqnum; ?></td>
                                            <td><?php echo _dateFormat($d->prrelto_date); ?></td>
                                            <td><?php echo ucwords($d->relby); ?></td>
                                            <td><?php echo ucwords($d->prrelto_recby); ?></td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $('#released').DataTable( {
            "order": [[ 0, "desc" ]]
        } );
    </script>

<?php
}

function displayApprovedPromoRequestById($link,$reqid)
{
    $table = 'promo_gc_request';
    $select = "promo_gc_request.pgcreq_id,
        promo_gc_request.pgcreq_reqnum,
        promo_gc_request.pgcreq_datereq,
        promo_gc_request.pgcreq_dateneeded,
        promo_gc_request.pgcreq_doc,
        promo_gc_request.pgcreq_status,
        promo_gc_request.pgcreq_group,
        promo_gc_request.pgcreq_remarks,
        promo_gc_request.pgcreq_total,
        CONCAT(prep.firstname,' ',prep.lastname) as prepby,
        approved_request.reqap_remarks,
        approved_request.reqap_doc,
        approved_request.reqap_date,
        CONCAT(recom.firstname,' ',recom.lastname) as recomby";
    $where = "promo_gc_request.pgcreq_id='".$reqid."'
        AND
            promo_gc_request.pgcreq_status='approved'
        AND
            approved_request.reqap_approvedtype='promo gc preapproved'";
    $join = 'INNER JOIN
            users as prep
        ON
            prep.user_id = promo_gc_request.pgcreq_reqby
        INNER JOIN
            approved_request
        ON
            approved_request.reqap_trid = promo_gc_request.pgcreq_id
        INNER JOIN
            users as recom
        ON
            recom.user_id = approved_request.reqap_preparedby';
    $limit = '';
    $data = getSelectedData($link,$table,$select,$where,$join,$limit);

    if(count($data)>0){
    	?>

        	<div class="row form-container">
            	<div class="col-md-12">
                    <div class="panel with-nav-tabs panel-info">
                        <div class="panel-heading">
                            <button class="btn pull-right" onclick="window.location='#/promo-request-approved/'">Back</button>
                            <ul class="nav nav-tabs">
                                <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Promo GC Request # <?php echo $data->pgcreq_reqnum;?></a></li>
                                <li><a href="#tab2default" data-toggle="tab">Recommendation Details</a></li>
                                <li><a href="#tab3default" data-toggle="tab">Approved Details</a></li>
                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="tab1default">
                                	<div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="nobot">Date Requested</label> 
                                                <input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo _dateFormat($data->pgcreq_datereq); ?>" readonly="readonly">  
                                            </div>
                                            <div class="form-group">
                                                <label class="nobot">Date Needed</label> 
                                                <input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo _dateFormat($data->pgcreq_dateneeded); ?>" readonly="readonly">  
                                            </div>
                                            <?php if(!empty($data->pgcreq_doc)): ?>
                                                <div class="form-group">
                                                    <label class="nobot">Document</label> 
                                                    <a class="btn btn-block btn-default" href='../assets/images/promoRequestFile/download.php?file=<?php echo $request->pgcreq_doc; ?>'>Download</a>
                                                </div>
                                            <?php endif; ?>
                                            <div class="form-group">
                                                <label class="nobot">Promo Group</label> 
                                                <input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo $data->pgcreq_group; ?>" readonly="readonly">  
                                            </div>
                                            <div class="form-group">
                                                <label class="nobot">Remarks</label> 
                                                <textarea class="form-control bot-6 textareax" readonly="readonly"><?php echo $data->pgcreq_remarks; ?></textarea>                                    
                                            </div>
                                            <div class="form-group">
                                                <label class="nobot">Requested By</label> 
                                                <input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo ucwords($data->prepby); ?>" readonly="readonly">                               
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Denomination</th>
                                                        <th>Qty</th>
                                                        <th>Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <?php 
                                                    $table = 'promo_gc_request_items';
                                                    $select = "promo_gc_request_items.pgcreqi_qty, 
                                                        denomination.denomination";
                                                    $where = "promo_gc_request_items.pgcreqi_trid='".$reqid."'";
                                                    $join = 'INNER JOIN 
                                                            denomination 
                                                        ON 
                                                            denomination.denom_id = promo_gc_request_items.pgcreqi_denom';
                                                    $limit = '';
                                                    $request = getalldata($link,$table,$select,$where,$join,$limit);
                                                ?>
                                                <tbody>
                                                    <?php foreach ($request as $r): ?>                                                   
                                                        <tr>
                                                            <td><?php echo number_format($r->denomination,2); ?></td>
                                                            <td><?php echo number_format($r->pgcreqi_qty); ?></td>
                                                            <td><?php echo number_format($r->denomination * $r->pgcreqi_qty,2); ?></td>
                                                        </tr>
                                                    <?php endforeach ?>
                                                </tbody>
                                            </table>
                                            <div class="row form-horizontal">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-8">Total Denomination</label> 
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo number_format($data->pgcreq_total,2); ?>" readonly="readonly">  
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                
                                	</div>
                                </div>
                                <div class="tab-pane fade" id="tab2default">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="nobot">Date Recommended</label> 
                                                <input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo _dateFormat($data->reqap_date); ?>" readonly="readonly">  
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="nobot">Time Recommended</label> 
                                                <input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo _timeFormat($data->reqap_date); ?>" readonly="readonly">  
                                            </div>                                    
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="nobot">Remarks</label> 
                                                <textarea class="form form-control inptxt input-sm bot-6 textareax" readonly="readonly"><?php echo $data->reqap_remarks; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="nobot">Document</label> 
                                                <?php if(!empty($reqap_doc)): ?>
                                                    <a class="btn btn-block btn-default" href='../assets/images/promoRequestFile/download.php?file=<?php echo $request->reqap_doc; ?>'>Download</a>
                                                <?php else: ?>
                                                    <div class="">None</div>
                                                <?php endif; ?>
                                            </div>                                    
                                        </div>                           
                                    </div>
                                    <div class="row">
                                        <div class="col-md-offset-4 col-md-4">
                                            <div class="form-group">
                                                <label class="nobot">Recommended By</label> 
                                                <input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo ucwords($data->recomby); ?>" readonly="readonly">  
                                            </div>                                    
                                        </div>    
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tab3default">
                                    <?php 
                                        $table='approved_request';
                                        $select = "approved_request.reqap_remarks,
                                            approved_request.reqap_doc,
                                            approved_request.reqap_approvedby,
                                            approved_request.reqap_checkedby,
                                            approved_request.reqap_date,
                                            CONCAT(users.firstname,' ',users.lastname) as appby";
                                        $where = "approved_request.reqap_approvedtype='promo gc approved'
                                            AND
                                                approved_request.reqap_trid='".$reqid."'";
                                        $join = 'INNER JOIN
                                                users
                                            ON
                                                users.user_id = approved_request.reqap_preparedby';
                                        $limit = '';
                                        $app = getSelectedData($link,$table,$select,$where,$join,$limit);
                                    ?>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="nobot">Date Approved</label> 
                                                <input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo _dateFormat($app->reqap_date); ?>" readonly="readonly">  
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="nobot">Time Approved</label> 
                                                <input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo _timeFormat($app->reqap_date); ?>" readonly="readonly">  
                                            </div>
                                        </div>                                                                
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="nobot">Remarks</label> 
                                                <textarea class="form form-control inptxt input-sm bot-6 textareax" readonly="readonly"><?php echo $app->reqap_remarks; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="nobot">Document</label>
                                                <?php if(!empty($app->reqap_doc)): ?> 
                                                    <a class="btn btn-block btn-default" href='../assets/images/promoReleasedFile/download.php?file=<?php echo $request->reqap_doc; ?>'>Download</a>
                                                <?php else: ?>
                                                    <div class="">None</div>
                                                <?php endif; ?>

                                            </div>
                                        </div>                                   
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="nobot">Checked By</label> 
                                                <input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo ucwords($app->reqap_checkedby); ?>" readonly="readonly">  
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="nobot">Approved By</label> 
                                                <input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo ucwords($app->reqap_approvedby); ?>" readonly="readonly">  
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="nobot">Prepared By</label> 
                                                <input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo ucwords($app->appby); ?>" readonly="readonly">  
                                            </div>
                                        </div>                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        	</div>
    	<?php
    }
    else
    {    
        echo 'Page not found.';
    }
}
function displayApprovedPromoRequestList($link)
{
	$table = 'promo_gc_request';
	$select = "promo_gc_request.pgcreq_id,
		promo_gc_request.pgcreq_reqnum,
		promo_gc_request.pgcreq_datereq,
		promo_gc_request.pgcreq_dateneeded,
		promo_gc_request.pgcreq_total,
		promo_gc_request.pgcreq_group,
		CONCAT(prepby.firstname,' ',prepby.lastname) as prepby,
		CONCAT(recom.firstname,' ',recom.lastname) as recby	";
	$where = "promo_gc_request.pgcreq_status='approved'
		AND
			approved_request.reqap_approvedtype='promo gc preapproved'";
	$join = 'INNER JOIN
			approved_request
		ON
			approved_request.reqap_trid = promo_gc_request.pgcreq_id
		INNER JOIN
			users as prepby
		ON
			prepby.user_id = promo_gc_request.pgcreq_reqby
		INNER JOIN
			users as recom
		ON
			recom.user_id = approved_request.reqap_preparedby';
	$limit = '';

	$data = getAllData($link,$table,$select,$where,$join,$limit);
	?>
	<div class="row form-container">
    	<div class="col-md-12">
            <div class="panel with-nav-tabs panel-info">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Approved Promo GC</a></li>
                        <!-- <li><a href="#tab2default" data-toggle="tab">Default 2</a></li> -->
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1default">
                        	<div class="row">
                                <div class="col-md-12">
                                    <table class="table" id="storeRequestList">
                                        <thead>
                                            <tr>
                                                <th>RFPROM #</th>
                                                <th>Date Requested</th>
                                                <th>Date Needed</th>
                                                <th>Total GC</th>
                                                <th>Retail Group</th>
                                                <th>Requested By</th>
                                                <th>Recommended By</th>
                                                <th>Approved By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        	<?php foreach ($data as $d): ?>
                                        		<tr onclick="window.document.location='#/promo-request-approved/<?php echo $d->pgcreq_id;  ?>'">
                                        			<td><?php echo $d->pgcreq_reqnum; ?></td>
                                        			<td><?php echo _dateformat($d->pgcreq_datereq); ?></td>
                                        			<td><?php echo _dateformat($d->pgcreq_dateneeded); ?></td>
                                        			<td><?php echo number_format($d->pgcreq_total,2) ?></td>
                                        			<td><?php echo $d->pgcreq_group; ?></td>
                                        			<td><?php echo ucwords($d->prepby); ?></td>
                                        			<td><?php echo ucwords($d->recby); ?></td>
                                        			<td><?php echo ucwords(getApprovedBy($link,$d->pgcreq_id)); ?></td>
                                        		</tr>
                                        	<?php endforeach ?>
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
	<script type="text/javascript">
    
        $.extend( $.fn.dataTableExt.oStdClasses, {    
            "sLengthSelect": "selectsup"
        });

        $('#storeRequestList').DataTable( {
            "order": [[ 0, "desc" ]]
        } );
	</script>
	<?php
}

function displayRecommendedPromoRequest($link,$id)
{
    $table = 'promo_gc_request';
    $select = "promo_gc_request.pgcreq_reqnum,
        promo_gc_request.pgcreq_datereq,
        promo_gc_request.pgcreq_id,
        promo_gc_request.pgcreq_dateneeded,
        promo_gc_request.pgcreq_total,
        CONCAT(users.firstname,' ',users.lastname) as user,
        access_page.title,
        promo_gc_request.pgcreq_group,
        promo_gc_request.pgcreq_doc,
        promo_gc_request.pgcreq_remarks,
        promo_gc_request.pgcreq_total,
        promo_gc_request.pgcreq_group_status";
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
            access_page.access_no=users.usertype';
    $limit = 'ORDER BY pgcreq_id ASC';

    $request = getSelectedData($link,$table,$select,$where,$join,$limit);

// SELECT 
//     promo_gc_request.pgcreq_reqnum,
//     promo_gc_request.pgcreq_datereq,
//     promo_gc_request.pgcreq_id,
//     promo_gc_request.pgcreq_dateneeded,
//     promo_gc_request.pgcreq_total,
//     CONCAT(users.firstname,' ',users.lastname) as user,
//     access_page.title,
//     promo_gc_request.pgcreq_group,
//     promo_gc_request.pgcreq_doc,
//     promo_gc_request.pgcreq_remarks,
//     promo_gc_request.pgcreq_total,
//     promo_gc_request.pgcreq_group_status
// FROM 
//     promo_gc_request
// LEFT JOIN
//     users
// ON
//     users.user_id = promo_gc_request.pgcreq_reqby
// LEFT JOIN
//     access_page
// ON
//     access_page.access_no=users.user_id
// WHERE 
//     promo_gc_request.pgcreq_status='pending'
// AND
//     promo_gc_request.pgcreq_group_status='approved'
// AND 
//     promo_gc_request.pgcreq_id='2'
    $hasError = false;
    if(!count($request) > 0 )
    {
        $hasError = true;
    }

	?>
	<div class="row form-container">
    	<div class="col-md-12">
            <div class="panel with-nav-tabs panel-info">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Promo GC Request Recommendation Form</a></li>
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
                                    <div class="col-md-5">
                                        <form action="../ajax.php?action=retailgbudgetreq" method="POST" id="promoreqgroup" class="form-horizontal">                                            
                                            <input type="hidden" value="<?php echo $request->pgcreq_id; ?>" id="requestid" name="requestid">
                                            <?php if($request->pgcreq_group_status==''): ?>
                                                <div class="form-group form-container">
                                                    <label class="col-sm-6 control-label"><span class="requiredf">*</span>Request Status:</label>
                                                    <div class="col-sm-6">
                                                        <select class="form form-control input-sm inptxt" id="statusretail" name="status" required autofocus />
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
                                                        <label class="col-sm-6 control-label"><span class="requiredf">*</span>Remarks   :</label>
                                                        <div class="col-sm-6">
                                                            <textarea class="form form-control input-sm inptxt tarea" name="remark" id="remark" required></textarea>
                                                        </div>
                                                    </div><!-- end form group -->
                                                </div><!-- end hide-cancel -->
                                                <div class="form-group">
                                                    <label class="col-sm-6 control-label label-prepared"></label>
                                                    <div class="col-sm-6">
                                                        <input type="text" readonly="readonly" class="form form-control input-sm inptxt" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>" />
                                                    </div>
                                                </div><!-- end form group -->
                                                <div class="response">
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-offset-8  col-sm-4">
                                                        <button type="submit" class="btn btn-block btn-primary"><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span> Submit</button>
                                                    </div>
                                                </div><!-- end form group -->
                                            <?php else: ?>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label">Promo GC Status:</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form inptxt form-control" value="Approved" disabled>
                                                        <div class="alert alert-warning alertrecommedation">
                                                            <span class="requiredf">*</span> Promo GC already recommended and waiting for Finance Department approval.
                                                        </div>                  
                                                    </div>                                      
                                                </div>
                                                <?php
                                                    $table='approved_request';
                                                    $select = "approved_request.reqap_remarks,
                                                        approved_request.reqap_doc,
                                                        approved_request.reqap_date,
                                                        CONCAT(users.firstname,' ',users.lastname) as preparedby";
                                                    $where = "approved_request.reqap_trid='$id'
                                                        AND
                                                            approved_request.reqap_approvedtype ='promo gc preapproved'";
                                                    $join = 'INNER JOIN
                                                            users
                                                        ON
                                                            users.user_id = approved_request.reqap_preparedby';
                                                    $limit ='LIMIT 1';
                                                    $requestapp = getSelectedData($link,$table,$select,$where,$join,$limit);
                                                    
                                                ?>   
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label">Date Approved:</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form inptxt form-control" value="<?php echo _dateFormat($requestapp->reqap_date)?>" disabled>
                                                    </div>                                      
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label">Time Approved:</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form inptxt form-control" value="<?php echo _timeFormat($requestapp->reqap_date)?>" disabled>
                                                    </div>                                      
                                                </div>
                                                <?php if($requestapp->reqap_doc!=''): ?>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label">Document:</label>
                                                    <div class="col-sm-6">
                                                        <a class="btn btn-block btn-default" href='../assets/images/budgetRecommendation/download.php?file=<?php echo $requestapp->reqap_doc; ?>'><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download</a>
                                                    </div>                                      
                                                </div>
                                                <?php endif; ?>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label">Remarks:</label>
                                                    <div class="col-sm-6">
                                                        <textarea class="form inptxt form-control inptxt tarea" disabled><?php echo $requestapp->reqap_remarks; ?></textarea>
                                                    </div>                                      
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label">Approved By:</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form inptxt form-control" value="<?php echo ucwords($requestapp->preparedby);?>" disabled>
                                                    </div>                                      
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
                                                <input type="text" class="form-control input-sm inptxt" readonly="readonly" value="&#8369 <?php echo number_format($request->pgcreq_total,2); ?>" readonly="readonly">
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

	<?php
}

function displayPromoRequestforUpdate($link,$reqid)
{
	$table = 'promo_gc_request';
	$select = "promo_gc_request.pgcreq_reqnum,
		promo_gc_request.pgcreq_datereq,
		promo_gc_request.pgcreq_dateneeded,
		promo_gc_request.pgcreq_doc,
		promo_gc_request.pgcreq_status,
		promo_gc_request.pgcreq_group_status,
		promo_gc_request.pgcreq_total,
		promo_gc_request.pgcreq_group,
		promo_gc_request.pgcreq_remarks,
		promo_gc_request.pgcreq_id,
		CONCAT(users.firstname,' ',users.lastname) as prep";
	$where = "promo_gc_request.pgcreq_id = '".$reqid."'
		AND
		promo_gc_request.pgcreq_status='pending'
		AND
		promo_gc_request.pgcreq_group_status=''";
	$join = 'INNER JOIN
		users
		ON
		users.user_id = promo_gc_request.pgcreq_reqby';
	$limit = '';

	$promo = getSelectedData($link,$table,$select,$where,$join,$limit);

	$denoms = getAllDenomination($link);

	?>
	<div class="row form-container">
    	<div class="col-md-12">
            <div class="panel with-nav-tabs panel-info">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Update Promo GC Request</a></li>
                        <!-- <li><a href="#tab2default" data-toggle="tab">Default 2</a></li> -->
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1default">
                        	<div class="row">
                                <div class="col-md-8">
									<form class="form-horizontal" id="promoreqFormupdate" method='POST' action="../ajax.php?action=promoRequestupdate">
										<input type="hidden" name="reqid" id="reqid" value="<?php echo $promo->pgcreq_id; ?>"> 
										<input type="hidden" name="imgname" value="<?php echo $promo->pgcreq_doc; ?>">
										<input type="hidden" name="totpromoreq" id="totpromoreq" value="<?php echo $promo->pgcreq_total; ?>"> 
										<div class="form-group">
											<label class="col-sm-3 control-label">RFPROM No.</label>  
											<div class="col-sm-3">
												<input value="<?php echo  $promo->pgcreq_reqnum; ?>" name="preqnum" type="text" class="form-control inptxt input-sm" readonly="readonly">                
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">Date Requested:</label>  
											<div class="col-sm-4">
												<input value="<?php echo _dateformat($promo->pgcreq_datereq); ?>" type="text" class="form-control inptxt input-sm" readonly="readonly">         
											</div>
										</div>  
										<div class="form-group">
											<label class="col-sm-3 control-label"><span class="requiredf">*</span>Date Needed:</label>  
											<div class="col-sm-4">                  
												<input type="text" class="form form-control inptxt input-sm ro" id="dp1" data-date-format="MM dd, yyyy" name="date_needed" readonly="readonly" value="<?php echo _dateformat($promo->pgcreq_dateneeded); ?>" required>
											</div>
										</div>
										<?php if(trim($promo->pgcreq_doc !='')): ?>
											<div class="form-group">
												<label class="col-sm-3 control-label">Uploaded Copy:</label>  
												<div class="col-sm-4">
													<a class="btn btn-block btn-default" href='../assets/images/promoRequestFile/download.php?file=<?php echo $promo->pgcreq_doc; ?>'>Download</a>                 
												</div>                    
											</div>                  
										<?php endif; ?>
										<div class="form-group">
										<label class="col-sm-3 control-label">Upload Scan Copy:</label>  
											<div class="col-sm-4">
												<input id="pics" type="file" name="docs[]" accept="image/*" class="form-control inptxt input-sm" />
											</div> 
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label"><span class="requiredf">*</span>Remarks:</label>  
											<div class="col-sm-6">
												<input name="remarks" value="<?php echo $promo->pgcreq_remarks ?>" type="text" class="form-control inptxt input-sm" required autocomplete="off" autofocus>                
											</div> 
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label"><span class="requiredf">*</span>Promo Group:</label>  
											<div class="col-sm-4">                  
												<select class="form form-control inptxt input-sm promog" name="group" required>
												<?php if($promo->pgcreq_group == 1): ?>
													<option value="1">Group 1</option>
													<option value="2">Group 2</option>
													<?php else: ?>
													<option value="2">Group 2</option>
													<option value="1">Group 1</option>
												<?php endif; ?>
												</select>
											</div>                  
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">Denomination</label> 
											<label class="col-sm-3 control-label"><span class="requiredf">*</span>Quantity</label>              
										</div>
										<?php foreach ($denoms as $d): ?>
											<div class="form-group">
												<label class="col-sm-3 control-label">&#8369 <?php echo number_format($d->denomination,2); ?></label>  
												<div class="col-sm-3">
													<input type="hidden" id="m<?php echo $d->denom_id; ?>" value="<?php echo $d->denomination; ?>"/>
													<input class="form form-control inptxt denfield" id="num<?php echo $d->denom_id; ?>" value="<?php echo getRequestedQtyforPromoRequest($link,$promo->pgcreq_id,$d->denom_id); ?>" name="denoms<?php echo $d->denom_id; ?>" autocomplete="off" />
												</div>                   
											</div>                  
										<?php endforeach ?>
										<div class="form-group">
											<label class="col-sm-3 control-label">Updated by:</label>  
											<div class="col-sm-4">
												<input name="textinput" type="text" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>" class="form-control input-sm inptxt" readonly="readonly">                
											</div>                    
											<div class="col-sm-4">
												<button id="btn" type="submit" class="btn btn-block btn-primary"><span class="glyphicon glyphicon-log-in"></span> &nbsp;Update </button>
											</div> 
										</div>
									</form> 
									<div class="response">
									</div>                                   
                                </div>
                                <div class="col-md-4">
									<div class="box bot-margin">
										<div class="box-header"><h4><i class="fa fa-inbox"></i> Total Promo GC Request</h4></div>
										<div class="box-content">
											<h3 class="current-budget mbot">&#8369 <span id="totpromo"><?php echo number_format($promo->pgcreq_total,2); ?></span></h3>              
										</div>
									</div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="tab-pane fade" id="tab2default">Default 2</div> -->
                    </div>
                </div>
            </div>
        </div>
	</div>

	<script type="text/javascript">
		var nowTemp = new Date();
		var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate()+1, 0, 0, 0, 0);

		var checkin = $('#dp1').datepicker({

		    beforeShowDay: function (date) {
		        return date.valueOf() >= now.valueOf();
		    },
		    autoclose: true

		});

		$('.denfield').inputmask("integer", { allowMinus: false,autoGroup: true, groupSeparator: ",", groupSize: 3,placeholder:'0' });

		$('.form-container').on('submit','form#promoreqFormupdate',function(event)
		{
			event.preventDefault();
			$('.response').html('');
			var formURL = $(this).attr('action'), formData = new FormData($(this)[0]);	
			var hasqty = false;

			if($('#dp1').val().trim()=='')
			{
				$('.response').html('<div class="alert alert-danger" id="danger-x">Please select date needed.</div>');
				return false;
			}

			var comma = $('#dp1').val().trim().split( new RegExp( "," ) ).length-1;
			if(comma > 1)
			{
				$('.response').html('<div class="alert alert-danger" id="danger-x">Date needed is invalid.</div>');
				return false;			
			}

			var denfield='';
			$('.denfield').each(function(){
				denfield = $(this).val().trim();
				if(denfield!=0)
				{
					if(denfield.length!=0)
					{
						hasqty = true;
						return;
					}
				}
			});

			if(!hasqty)
			{
				$('.response').html('<div class="alert alert-danger" id="danger-x">Please input at least one denomination quantity field.</div>');
				$('#num1').focus();
				return false;
			}

			$('button.btn').prop('disabled',true);

	        BootstrapDialog.show({
	        	title: 'Confirmation',
	            message: 'Are you sure you want to update Promo GC request?',
	            closable: true,
	            closeByBackdrop: false,
	            closeByKeyboard: true,
	            onshow: function(dialog) {
	                // dialog.getButton('button-c').disable();
	            },
	            onhidden:function(dialog){
	            	$('button.btn').prop('disabled',false);
	            },
	            buttons: [{
	                icon: 'glyphicon glyphicon-ok-sign',
	                label: 'Yes',
	                cssClass: 'btn-primary',
	                hotkey: 13,
	                action:function(dialogItself){                	
	                	dialogItself.close();
	                	$buttons = this;
	                	$buttons.disable();
						$.ajax({
				    		url:formURL,
				    		type:'POST',
							data: formData,
							enctype: 'multipart/form-data',
						    async: true,
						    cache: false,
						    contentType: false,
						    processData: false,
							beforeSend:function(){
							},
							success:function(data1){
								console.log(data1);
								var data1 = JSON.parse(data1);											
								if(data1['st'])
								{
									var dialog = new BootstrapDialog({
						            message: function(dialogRef){
						            var $message = $('<div>Promo GC Request Saved.</div>');			        
						                return $message;
						            },
						            closable: false
							        });
							        dialog.realize();
							        dialog.getModalHeader().hide();
							        dialog.getModalFooter().hide();
							        dialog.getModalBody().css('background-color', '#0088cc');
							        dialog.getModalBody().css('color', '#fff');
							        dialog.open();
							        setTimeout(function(){
				                    	dialog.close();
				               		}, 1500);
				               		setTimeout(function(){
				                    	window.location.href='index.php';
				               		}, 1700);
								} 
								else 
								{
									$('.response').html('<div class="alert alert-danger" id="danger-x">'+data1['msg']+'</div>');												
									$buttons.enable();
								}
							}
						});

	                }
	            }, {
	            	icon: 'glyphicon glyphicon-remove-sign',
	                label: 'No',
	                action: function(dialogItself){
	                    dialogItself.close();
	                }
	            }]
	        });
		});

		$("input[id^=num]").keyup(function(){
			var sum = 0, sum1=0;
			$('.denfield').each(function(){
				var inputs = $(this).val();
				inputs = inputs.replace(/,/g , "");
				sum = sum + inputs;
				var dnid = $(this).attr('id').slice(3);
				mul = inputs * $("#m"+dnid).val();
				sum1 = sum1 +mul;
			});
			$('span#totpromo').text(addCommas(sum1)+".00");
			$('input#totpromoreq').val(sum1);
		});

	</script>
	<script type="text/javascript" src="../assets/js/funct.js"></script>

	<?php
}

?>