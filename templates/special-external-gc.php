<script src="../assets/js/funct.js"></script>
<?php
session_start();
include '../function.php';

if(isset($_GET['page']))
{
	$page = $_GET['page'];

	if($page=='view-released-special-gc')
	{
		viewReleasedSpecialGC($link);
	}
	elseif($page=='view-released-special-single')
	{
        if(!isset($_GET['reqid']) || isset($_GET['reqid'])=='')
        {
        	echo 'Page not found.';
            exit();  
        }

        $reqid = (int)$_GET['reqid'];
        viewReleasedSpecialSingle($link,$reqid);
	}
	elseif($page=='cancelled-special-external-request')
	{
		cancelledSpecialExternalRequest($link);
	}
	elseif($page=='special-external-request-list')
	{
        // if(!hasPageAccessView($link,3,$_SESSION['gc_id']))
        // {
        //     echo 'Page not found';
        //     exit();
        // }

		specialExternalRequestList($link);
	}
	elseif ($page=='special-external-request-approved-single') 
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

        specialExternalRequestApprovedSingle($link,$reqId);
	}
	elseif($page=='special-external-request-approve-update')
	{
        if(!isset($_GET['reqId']) || isset($_GET['reqId'])=='')
        {
            echo 'Page not found.';
            exit();
        }
        $id = $_GET['reqId'];

        // check user 

        displaySpecialExternalUpdate($link,$id);
	}
	elseif ($page=='special-external-request-approved-list') 
	{
		approvedSpecialExternalRequest($link);
	}
	elseif($page=='request-special-gc')
	{
		requestSpecialGC($link,$todays_date);
	}
	elseif ($page=='special-external-request') 
	{
		pendingSpecialExtertenalRequest($link,$todays_date);
	}
	elseif ($page=='request-special-gcwithholder') 
	{
		requestSpecialGCwithHolder($link,$todays_date);
	}
	elseif($page=='reviewed-gc-for-releasing')
	{
		reviewedGCForReleasing($link);
	}
	elseif ($page=='reviewedgc') 
	{
		if(!isset($_GET['reqid']) && $_GET['reqid']!='')
		{
			exit();
		}

		$reqid = $_GET['reqid'];

		//check if transaction exist
		if(numRowsWhereTwo($link,'special_external_gcrequest','spexgc_id','spexgc_id','spexgc_reviewed',$reqid,'reviewed')==0)
		{
			exit();
		}
		displayReviewdGC($link,$reqid);
	}
	elseif ($page=='special-external-gc-reviewed') 
	{
		specialExternalGCReviewed($link);
	}
	elseif ($page=='special-external-gc-reviewed-single') 
	{

		if(!isset($_GET['reqid']) && $_GET['reqid']!='')
		{
			exit();
		}

		$reqid = $_GET['reqid'];
		specialExternalGCReviewedSingle($link,$reqid);
	}
	elseif ($page=='specialgcpayment') 
	{
		_specialgcpayment($link,$todays_date);
	}
	elseif ($page=='specialexternalgcholderentrylist') 
	{
		_specialexternalgcholderentrylist($link,$todays_date);
	}
	elseif ($page=='specialexternalgcholderentry')
	{
		if(!isset($_GET['reqid']) && $_GET['reqid']!='')
		{
			exit();
		}
		$reqid = $_GET['reqid'];
		_specialexternalgcholderentry($link,$todays_date,$reqid);
	}
	elseif ($page=='addempperdenom') 
	{
		if(isset($_GET['den']))
		{
			$den = $_GET['den'];
			$den = str_replace(',','',$den);
		}
		else 
		{
			exit();
		}

		if(isset($_GET['denid']))
		{
			$denid = $_GET['denid'];
		}
		else 
		{
			exit();
		}

		if(isset($_GET['numscanemp']))
		{
			$numscanemp = $_GET['numscanemp'];
		}
		else 
		{
			exit();
		}

		if(isset($_GET['gcqty']))
		{
			$gcqty = $_GET['gcqty'];
		}
		else 
		{
			exit();
		}

		_addempperdenom($link,$den,$numscanemp,$gcqty,$denid);

	}
	elseif($page=='divbybarcode')
	{
		_divbybarcode();
	}
	elseif($page=='divbyrange')
	{
		_divbyrange();
	}
	elseif($page=='divbypage')
	{
		if(!isset($_GET['reqid']))
		{
			exit();
		}
		$reqid = $_GET['reqid'];
		_divbypage($link,$reqid);
	}
	elseif($page=='divbyrequestid')
	{
		if(!isset($_GET['reqid']))
		{
			exit();
		}
		$reqid = $_GET['reqid'];
		_divbyrequestid($reqid);
    }
    elseif($page=='specialexreports')
    {
        _specialexreports($link);
    }
	else 
	{
		//last
		echo 'Something went wrong.';
	}	
}

function _specialexreports($link)
{
    ?>
        <div class="row form-container">
            <div class="col-md-5">
                <div class="panel with-nav-tabs panel-info">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active" style="font-weight:bold">
                                <a href="#tab1default" data-toggle="tab">Export Data</a>
                            </li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <form method="POST" id="exportdataspgc">
                                <div class="tab-pane fade in active" id="tab1default">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="startDate">Start Date</label>
                                                    <input id="startDate" name="startDate" type="text" class="form-control mb10" autocomplete="off"> &nbsp;
                                                <label for="endDate">End Date</label>
                                                    <input id="endDate" name="endDate" type="text" class="form-control" autocomplete="off">
                                            </div> 
                                            <div class="form-group"> 
                                                <div class="pretty p-default p-round">
                                                    <input type="radio" name="state" class="upbox reporttype" value="pdf">
                                                    <div class="state p-primary">
                                                        <label>PDF</label>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="form-group"> 
                                                <div class="pretty p-default p-round">
                                                    <input type="radio" name="state" class="upbox reporttype" value="excel">
                                                    <div class="state p-primary">
                                                        <label>Excel</label>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="response"></div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <button type="submit" class="btn btn-block btn-primary" id="btn"> <span class="glyphicon glyphicon-share" aria-hidden="true"></span> Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>
                                <!-- <div class="tab-pane fade" id="tab2default">Default 2</div> -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            var bindDateRangeValidation = function (f, s, e) {
                if(!(f instanceof jQuery)){
                        console.log("Not passing a jQuery object");
                }
            
                var jqForm = f,
                    startDateId = s,
                    endDateId = e;
            
                var checkDateRange = function (startDate, endDate) {
                    var isValid = (startDate != "" && endDate != "") ? startDate <= endDate : true;
                    return isValid;
                }
            
                var bindValidator = function () {
                    var bstpValidate = jqForm.data('bootstrapValidator');
                    var validateFields = {
                        startDate: {
                            validators: {
                                notEmpty: { message: 'This field is required.' },
                                callback: {
                                    message: 'Start Date must less than or equal to End Date.',
                                    callback: function (startDate, validator, $field) {
                                        return checkDateRange(startDate, $('#' + endDateId).val())
                                    }
                                }
                            }
                        },
                        endDate: {
                            validators: {
                                notEmpty: { message: 'This field is required.' },
                                callback: {
                                    message: 'End Date must greater than or equal to Start Date.',
                                    callback: function (endDate, validator, $field) {
                                        return checkDateRange($('#' + startDateId).val(), endDate);
                                    }
                                }
                            }
                        },
                        customize: {
                            validators: {
                                customize: { message: 'customize.' }
                            }
                        }
                    }
                    if (!bstpValidate) {
                        jqForm.bootstrapValidator({
                            excluded: [':disabled'], 
                        })
                    }
                
                    jqForm.bootstrapValidator('addField', startDateId, validateFields.startDate);
                    jqForm.bootstrapValidator('addField', endDateId, validateFields.endDate);
                
                };
            
                var hookValidatorEvt = function () {
                    var dateBlur = function (e, bundleDateId, action) {
                        jqForm.bootstrapValidator('revalidateField', e.target.id);
                    }
            
                    $('#' + startDateId).on("dp.change dp.update blur", function (e) {
                        $('#' + endDateId).data("DateTimePicker").setMinDate(e.date);
                        dateBlur(e, endDateId);
                    });
            
                    $('#' + endDateId).on("dp.change dp.update blur", function (e) {
                        $('#' + startDateId).data("DateTimePicker").setMaxDate(e.date);
                        dateBlur(e, startDateId);
                    });
                }
            
                bindValidator();
                hookValidatorEvt();
            };
            
            
            $(function () {
                var sd = new Date(), ed = new Date();
            
                $('#startDate').datetimepicker({ 
                pickTime: false, 
                format: "MM/DD/YYYY", 
                defaultDate: sd, 
                maxDate: ed 
                });
            
                $('#endDate').datetimepicker({ 
                pickTime: false, 
                format: "MM/DD/YYYY", 
                defaultDate: ed, 
                minDate: sd 
                });
            
                //passing 1.jquery form object, 2.start date dom Id, 3.end date dom Id
                bindDateRangeValidation($("#form"), 'startDate', 'endDate');
            }); 
            $("#startDate, #endDate").inputmask("m/d/y",{ "placeholder": "mm/dd/yyyy" });
            
            $('.form-container').on('submit','form#exportdataspgc',function(event){
                event.preventDefault();

                var formData = $(this).serialize(), formURL = $(this).attr('action');

                var hasChecked = false;
                var reportype = null;

                $('.reporttype').each(function(){
                    if($(this).is(':checked')) 
                    {  
                        reportype = $(this).val();
                        hasChecked = true;
                    }
                });

                if(!hasChecked)
                {
                    $('.response').html("<div class='alert alert-danger mb-0'>Please select report type.</div>");
                    return false;
                }
                $('.response').html("");
                if(reportype=="pdf")
                {
                    window.location.href='specialexternalgc-pdf-report.php?'+formData;
                }
                else 
                {
                    window.location.href='specialgcexcelreport.php?'+formData;
                }
            });
        </script>
    <?php
}

function _divbyrequestid($id)
{
	?>
		<div class="col-md-12">
			<div class="form-group">
				<label class="nobot"><span class="requiredf">*</span>Bottom Margin Per GC</label>   
				<input type="text" class="form form-control inptxt input-sm bot-6" name="bmargin" id="bmargin" autocomplete="off" value="0" autofocus>  
			</div>	
			<div class="form-group">
				<button class="btn" id="subbyrequestid">Submit</button>
			</div>
		</div>
		<script type="text/javascript">
			$('button#subbyrequestid').click(function(){
				if($('#bmargin').val().trim()!='')
				{
					if(isNaN($('#bmargin').val().trim()))
					{
						alert("Margin only accept numeric value.");
						return false;
					}
				}

				var bmargin = $('#bmargin').val().trim();
				var txt = '';
				if(bmargin!='' && bmargin!="0")
				{
					txt = '&spacebot='+bmargin;
				}

				var id = $('input#reqidspecgc').val().trim();
				
				window.open('spgccus.php?type=byrequestid&id='+id+txt,'_blank');
			});
		</script>
	<?php
}

function _divbypage($link,$id)
{
		$cnt = 0;
		$fbar = 0;
		$query = $link->query("SELECT 
			COUNT(*) as cnt 
		FROM 
			special_external_gcrequest_emp_assign
		WHERE 
			spexgcemp_trid = '$id'
		");

		if($query)
		{
			$row = $query->fetch_object();
			$cnt = $row->cnt;
		}

		$limit = 4;

		$pages = ceil($cnt / $limit);
		$p = 1;

		//get first barcode number
		

		$query_fb = $link->query(
			"SELECT 
				spexgcemp_barcode 
			FROM 
				special_external_gcrequest_emp_assign 
			WHERE 
				spexgcemp_trid='$id' 
			ORDER BY 
				spexgcemp_barcode 
			ASC LIMIT 1
		");

		if($query_fb)
		{
			$row = $query_fb->fetch_object();
			$fbar = $row->spexgcemp_barcode;
		}

	?>
		<div class="col-md-12">
			<div class="form-group">
				<label class="nobot"><span class="requiredf">*</span>Bottom Margin Per GC</label>   
				<input type="text" class="form form-control inptxt input-sm bot-6" name="bmargin" id="bmargin" autocomplete="off" value="0" autofocus>  
			</div>	
			<div class="pages">
				<?php if($cnt > 0): ?>
					<ul>
						<?php while($pages>=$p): ?>

							<li><a data-id="<?php echo $p; ?>" data-offset="<?php echo $fbar; ?>" class="libypage" href="#"><?php echo 'Page '.$p; ?></a></li>
						<?php 
							$fbar = $fbar + 4;
							$p++;
							endwhile; ?>
					</ul>
				<?php endif; ?>
			</div>
		</div>
		<script type="text/javascript">
			$('a.libypage').click(function(){
				if($('#bmargin').val().trim()!='')
				{
					if(isNaN($('#bmargin').val().trim()))
					{
						alert("Margin only accept numeric value.");
						return false;
					}
				}

				var offset = $(this).attr('data-offset');

				var bmargin = $('#bmargin').val().trim();
				var txt = '';
				if(bmargin!='' && bmargin!="0")
				{
					txt = '&spacebot='+bmargin;
				}

				var id = $('input#reqidspecgc').val().trim();

				
				window.open('spgccus.php?type=bypage&offset='+offset+'&id='+id+txt,'_blank');
				return false;
			});
		</script>
	<?php
}

function _divbybarcode()
{
	?>
		<div class="col-md-12">
			<div class="form-group">
				<label class="nobot"><span class="requiredf">*</span>Barcode #</label>   
				<input type="text" class="form form-control inptxt input-sm bot-6" name="barcodespec" id="barcodespec" autocomplete="off" autofocus>  
			</div>		

			<div class="form-group">
				<button class="btn" id="subbybarcode">Submit</button>
			</div>
		</div>
		<script type="text/javascript">
			$('button#subbybarcode').click(function(){
				if($('#barcodespec').val().trim()=='')
				{
					return false;
				}
				var barcode = $('#barcodespec').val().trim();
				window.open('spgccus.php?type=bybarcode&barcode='+barcode,'_blank');
			});
		</script>
	<?php
}

function _divbyrange()
{
	?>
		<div class="col-md-12">
			<div class="form-group">
				<label class="nobot"><span class="requiredf">*</span>Barcode Start</label>   
				<input type="text" class="form form-control inptxt input-sm bot-6" name="bstart" id="bstart" autocomplete="off" autofocus>  
			</div>		
			<div class="form-group">
				<label class="nobot"><span class="requiredf">*</span>Barcode End</label>   
				<input type="text" class="form form-control inptxt input-sm bot-6" name="bend" id="bend" autocomplete="off" autofocus>  
			</div>	
			<div class="form-group">
				<label class="nobot"><span class="requiredf">*</span>Bottom Margin Per Page</label>   
				<input type="text" class="form form-control inptxt input-sm bot-6" name="bmargin" id="bmargin" autocomplete="off" value="0" autofocus>  
			</div>	
			<div class="form-group">
				<button class="btn" id="subbyrange">Submit</button>
			</div>
		</div>
		<script type="text/javascript">
			$('button#subbyrange').click(function(){
				if($('#bstart').val().trim()=='' || $('#bend').val().trim()=='')
				{
					alert("Please input barcode range.");
					return false;
				}

				if(isNaN($('#bstart').val().trim()) || isNaN($('#bend').val().trim()))
				{
					alert("Barcode only accept numeric value.");
					return false;
				}

				if($('#bmargin').val().trim()!='')
				{
					if(isNaN($('#bmargin').val().trim()))
					{
						alert("Margin only accept numeric value.");
						return false;
					}
				}

				var bstart = $('#bstart').val().trim();
				var bend = $('#bend').val().trim();
				var bmargin = $('#bmargin').val().trim();
				var txt = '';
				if(bmargin!='' && bmargin!="0")
				{
					txt = '&spacebot='+bmargin;
				}
				
				window.open('spgccus.php?type=byrange&bstart='+bstart+'&bend='+bend+txt,'_blank');
			});
		</script>
	<?php
}

function _addempperdenom($link,$den,$numscanemp,$gcqty,$denid)
{
	?>
		<div class="row">
			<div class="col-sm-4 addEmpClass">
				<h5>GC Holder Name</h5>
				<form method="post" action="../ajax.php?action=addEmployee" id="addEmployeeForm">
					<input type="hidden" name="den" id="den" value="<?php echo $den; ?>">
					<input type="hidden" id="denid" name="denid" value="<?php echo $denid; ?>"> 
					<input type="hidden" id="gcqty" name="gcqty" value="<?php echo $gcqty; ?>">	
					<input type="hidden" id="">			
					<div class="form-group">
						<label class="nobot"><span class="requiredf">*</span>Last Name</label>   
						<input type="text" class="form form-control inptxt input-sm bot-6 reqfieldx" name="lastname" autocomplete="off" autofocus>  
					</div>
					<div class="form-group">
						<label class="nobot"><span class="requiredf">*</span>First Name</label>   
						<input type="text" class="form form-control inptxt input-sm bot-6 reqfieldx" name="firstname" autocomplete="off" autofocus>  
					</div>
					<div class="form-group">
						<label class="nobot">Middle Name</label>   
						<input type="text" class="form form-control inptxt input-sm bot-6" name="middlename" autocomplete="off" autofocus>  
					</div>
					<div class="form-group">
						<label class="nobot">Name Ext.</label>   
						<input type="text" class="form form-control inptxt input-sm bot-6" name="nameext" autocomplete="off" autofocus>  
					</div>
					<div class="response2">

					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary">Assign</button>
						<button type="buttom" class="btn btn-danger">Clear</button>
					</div>
				</form>
			</div>
			<div class="col-sm-8">
				<h5>Denomination: <?php echo number_format($den,2); ?></h5>
				<table class="table" id="empDataTable">
					<thead>
						<th>Last Name</th>
						<th>First Name</th>
						<th>Middle Name</th>
						<th>Name Ext.</th>
						<th></th>
					</thead>
					<tbody>
						<?php if(isset($_SESSION['empAssign'])): ?>
							<?php foreach ($_SESSION['empAssign'] as $key => $value): ?>
								<?php if($value['denom']==$den):?>
								<tr>							
									<td><?php echo $value['lastname']; ?></td>
									<td><?php echo $value['firstname']; ?></td>
									<td><?php echo $value['middlename']; ?></td>
									<td><?php echo $value['extname']; ?></td>
									<td><input type="hidden" value="<?php echo $key; ?>" class="empkey"><i class="fa fa-times remove-employee" aria-hidden="true"></i></td>
								</tr>			
								<?php endif; ?>		
							<?php endforeach ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
		<script type="text/javascript">
		    $('#empDataTable,#list').dataTable( {
		        "pagingType": "full_numbers",
		        "ordering": false,
		        "processing": true
		    });

		     $('.addEmpClass').on('submit','#addEmployeeForm',function(event){
		 		event.preventDefault();
		 		var reqid = $('#reqid').val();
			    var t = $('#empDataTable').DataTable();
			    var counter = 1;
		    	$('.response2').html('');
		    	var hasEmpty = false;
		    	$('.reqfieldx').each(function(){
		    		if($(this).val().trim()=='')
		    		{
		    			hasEmpty = true;
		    			return false;
		    		}
		    	});

		    	if(hasEmpty)
		    	{
		    		$('.response2').html('<div class="alert alert-danger">Please fill-up firstname and lastname.</div>');
		    		return false;
		    	}
		    	var formURL = '../ajax.php?action=addEmployee', formData = $('#addEmployeeForm').serialize();   
				formData +='&reqid='+reqid;
		    	$.ajax({
		    		url:formURL,
		    		data:formData,
		    		type:'POST',
		    		success:function(data){
		    			//$('._barcodesrefund').load('../ajax-cashier.php?request=loadrefund');
		    			console.log(data);
		    			var data = JSON.parse(data);

		    			if(!data['st'])
		    			{
		    				alert(data['msg']);
					    }
					    else
					    {
			    			$('.ninternalcusq'+data['denid']).val(data['qty']);
			    			var counter = 1;
					        t.row.add( [		        	
					            data['lastname'],
					            data['firstname'],
					            data['middlename'],
					            data['nameext'],
					            '<input type="hidden" value="'+data['key']+'" class="empkey"><i class="fa fa-times remove-employee" aria-hidden="true"></i>',
					        ] ).draw( false );
					 		
					        counter++;
					        scanInternalInput();

					        $('#ninternalcusq'+data['denid']).val(data['qty']);


					    }
		     		}
		    	});
		    	$('#addEmployeeForm')[0].reset();
		    	$('input[name=lastname]').focus();
		    });

			$('table#empDataTable').on('click','.remove-employee',function(){
				var key = $(this).parents('tr').find('input.empkey').val();
				
				var den = $('#den').val();
				var r = confirm("Delete Employee?");
				if (r == true) {

					$.ajax({
						url:'../ajax.php?action=deleteAssignByKey',
						data:{key:key,den:den},
						type:'POST',
						success:function(data)
						{
							console.log(data);
							var data = JSON.parse(data);
							var denid = $('#denid').val();
							$('.ninternalcusq'+denid).val(data['qty']);
							scanInternalInput();
						}
					});

					var table = $('#empDataTable').DataTable();
					table
					.row( $(this).parents('tr') )
					.remove()
					.draw();
				}
				
				$('input[name=lastname]').focus();
			});

		</script>

	<?php
}

function _specialexternalgcholderentry($link,$todays_date,$reqid)
{	

    if(isset($_SESSION['empAssign']))
    {
        unset($_SESSION['empAssign']);
    }

// SELECT 
// 	special_external_gcrequest.spexgc_id,
//     special_external_gcrequest.spexgc_num,
//     special_external_gcrequest.spexgc_datereq,
//     special_external_gcrequest.spexgc_dateneed,
//     special_external_gcrequest.spexgc_remarks,
//     special_external_gcrequest.spexgc_payment,
//     special_external_gcrequest.spexgc_company,
//     special_external_gcrequest.spexgc_type,
//     special_external_gcrequest.spexgc_paymentype,
//     special_external_gcrequest.spexgc_company,
//     special_external_customer.spcus_companyname,
//     special_external_customer.spcus_acctname,
//     institut_payment.institut_bankname,
//     institut_payment.institut_bankaccountnum,
//     institut_payment.institut_checknumber
// FROM 
// 	special_external_gcrequest
// INNER JOIN
//     special_external_customer
// ON
//     special_external_customer.spcus_id = special_external_gcrequest.spexgc_company
// LEFT JOIN
// 	institut_payment
// ON
// 	institut_payment.insp_trid = special_external_gcrequest.spexgc_id
// WHERE 
// 	special_external_gcrequest.spexgc_id='13'
// AND
// 	special_external_gcrequest.spexgc_status='pending'
// AND
// 	institut_payment.insp_paymentcustomer = 'special external'


    $table ='special_external_gcrequest';
    $select =' special_external_gcrequest.spexgc_id,
	    special_external_gcrequest.spexgc_num,
	    special_external_gcrequest.spexgc_datereq,
	    special_external_gcrequest.spexgc_dateneed,
	    special_external_gcrequest.spexgc_remarks,
	    special_external_gcrequest.spexgc_payment,
	    special_external_gcrequest.spexgc_company,
	    special_external_gcrequest.spexgc_type,
	    special_external_gcrequest.spexgc_paymentype,
	    special_external_gcrequest.spexgc_company,
	   	special_external_gcrequest.spexgc_payment_arnum,
	    special_external_customer.spcus_companyname,
	    special_external_customer.spcus_acctname,
	    institut_payment.institut_bankname,
	    institut_payment.institut_bankaccountnum,
	    institut_payment.institut_checknumber';
    $where = "special_external_gcrequest.spexgc_id='$reqid'
		AND
			special_external_gcrequest.spexgc_status='pending'
		AND
			institut_payment.insp_paymentcustomer = 'special external'";
    $join = 'INNER JOIN
		    special_external_customer
		ON
		    special_external_customer.spcus_id = special_external_gcrequest.spexgc_company
		LEFT JOIN
			institut_payment
		ON
			institut_payment.insp_trid = special_external_gcrequest.spexgc_id';
    $limit = '';

    $data = getSelectedData($link,$table,$select,$where,$join,$limit);

    // var_dump($data);

    if(!count($data) > 0)
    {
        echo 'Page not found.';
        exit();
    }

    ?>
        <div class="row form-container">
            <div class="col-md-12">
                <div class="panel with-nav-tabs panel-info">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Pending Special External (GC Holder Entry)</a></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab1default">
                                <form action="../ajax.php?action=specialExternalGCAddEmp" method="POST" id="specialExternalGCAddEmp" enctype="multipart/form-data">
                                    <div class="row">
                                        <input type="hidden" name="reqid" id="reqid" value="<?php echo $data->spexgc_id; ?>">
                                        <input type="hidden" name="reqtype" id="reqtype" value="<?php echo $data->spexgc_type; ?>">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="nobot">GC Request #</label>   
                                                <input type="text" class="form form-control inptxt input-sm bot-6" name="reqnum" readonly="readonly" value="<?php echo $data->spexgc_num; ?>">  
                                            </div>
                                            <div class="form-group">
                                                <label class="nobot">Date Requested</label>   
                                                <input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo _dateFormat($data->spexgc_datereq); ?>">  
                                            </div>
                                            <div class="form-group">
                                                <label class="nobot">Date Needed:</label>
                                                <input type="text" class="form form-control inptxt input-sm ro bot-6" id="dp1" data-date-format="MM dd, yyyy" name="date_needed" readonly="readonly" value="<?php echo _dateFormat($data->spexgc_dateneed); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="nobot">Document(s) Uploaded</label> 
                                                <?php 
                                                    $table = 'documents';
                                                    $select = 'doc_fullpath';
                                                    $where = "doc_trid='".$reqid."'
                                                        AND
                                                            doc_type='Special External GC Request'";
                                                    $join = '';
                                                    $limit = '';
                                                    $docs = getAllData($link,$table,$select,$where,$join,$limit);
                                                    
                                                ?>

                                                <table class="table docstable" id="lightgallery">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>Remove</th>
                                                        </tr>
                                                    </thead>
                                                    <?php foreach ($docs as $d): ?>
                                                    <tr >
                                                        <td class="selector"  data-src="../assets/images/<?php echo $d->doc_fullpath;?>">
                                                            <a href="" class="thumbnail">
                                                            <img class="img-responsive img-table-display" src="../assets/images/<?php echo $d->doc_fullpath; ?>">
                                                            </a>
                                                        </td>
                                                        <td class="padleft">
                                                            <input type="checkbox" name="images[]" value="<?php echo $d->doc_fullpath; ?>">
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </table>
                                            </div>
                                            <div class="form-group">
                                              <label class="nobot">Upload Document</label> 
                                              <input id="input-file" class="file" type="file" name="docs[]" multiple>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <input type="hidden" name="companyid" id="companyid" value="<?php echo $data->spexgc_company; ?>">
                                                <label class="nobot">Company Name</label>   
                                                <textarea class="form form-control input-sm inptxt" readonly="readonly" name="compname" id="compname"><?php echo ucwords($data->spcus_companyname); ?></textarea>
                                            </div>  
                                            <div class="form-group">
                                                <label class="nobot">Account Name</label>   
                                                <input type="text" class="form form-control inptxt" readonly="readonly" name="accname" id="accname" value="<?php echo ucwords($data->spcus_acctname); ?>">
                                            </div>  
                                            <div class="form-group">
                                                <label class="nobot">AR #</label>
                                                <input type="text" class="form form-control inptxt" readonly="readonly" name="accname" id="accname" value="<?php echo $data->spexgc_payment_arnum; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label class="nobot">Payment Type</label>
                                                <input type="text" class="form form-control inptxt" readonly="readonly" name="accname" id="accname" value="<?php echo $data->spexgc_paymentype == 1 ? 'Cash' : 'Check'; ?>">
                                            </div>
                                            
                                            <div class="checkPayment" <?php echo $data->spexgc_paymentype==1 ? 'style="display: none;"' : ''; ?>>
                                                <div class="form-group">
                                                    <label class="nobot">Bank Name</label>
                                                    <input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo $data->institut_bankname; ?>" name="bankname" id="bankname" readonly="readonly" readonly="readonly">
                                                </div>
<!--                                                 <div class="form-group">
                                                    <label class="nobot">Bank Account Number</label>
                                                    <input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo $data->institut_bankaccountnum; ?>" name="baccountnum" id="baccountnum" readonly="readonly">
                                                </div> -->
                                                <div class="form-group">
                                                    <label class="nobot">Check Number</label>
                                                    <input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo $data->institut_checknumber; ?>" name="cnumber" id="cnumber" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="nobot">Amount Paid</label>
                                                <input type="text" class="form form-control inptxt input-sm bot-6 amount-external" readonly="readonly" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" name="amount" value="<?php echo $data->spexgc_payment; ?>" id="amount" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="nobot">Amount in words</label>
                                                <textarea class="form form-control input-sm inptxt amtinwords" id="amtinwords" readonly="readonly"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label class="nobot">Remarks</label> 
                                                <input type="text" class="form-control inptxt input-sm" name="remarks" value="<?php echo $data->spexgc_remarks; ?>" autocomplete="off" required readonly="readonly">
                                            </div>

                                            <div class="form-horizontal">
                                                <div class="form-group">
                                                    <label class="col-sm-4"><span class="requiredf">*</span>Denomination</label>
                                                    <label class="col-sm-4"><span class="requiredf">*</span>Qty</label>
                                                    <label class="col-sm-3"><span class="requiredf">*</span># Holder</label>
                                                </div>
                                                <div class="optionBox">
                                                    <?php
                                                        //SELECT spexgcemp_denom, COUNT(spexgcemp_id) as cnt FROM special_external_gcrequest_emp_assign WHERE spexgcemp_trid='1' GROUP BY spexgcemp_denom
                                                        $table = 'special_external_gcrequest_items';
                                                        $where = "special_external_gcrequest_items.specit_trid='".$reqid."'";
                                                        $select ='special_external_gcrequest_items.specit_qty,
                                                                special_external_gcrequest_items.specit_denoms';
                                                        $join = '';
                                                        $limit = '';
                                                        $denoms = getAllData($link,$table,$select,$where,$join,$limit);
                                                        $cnt = 1;
                                                        $total = 0;                                                        
                                                        foreach ($denoms as $den):
                                                            $stotal = 0;
                                                            $stotal = $den->specit_denoms * $den->specit_qty;
                                                            $total+=$stotal;
                                                    	?>
                                                            <div class="form-group">
                                                                <div class="col-sm-4">
                                                                    <input class="form form-control inptxt input-sm reqfield denfield ninternalcusd" name="ninternalcusd[]" id="ninternalcusd" value="<?php echo number_format($den->specit_denoms,2); ?>" placeholder="0" autocomplete="off" autofocus readonly="readonly" />
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <input class="form form-control inptxt input-sm reqfield ninternalcusqty" name="ninternalcusqty[]" id="ninternalcusqty" value="<?php echo $den->specit_qty; ?>" placeholder="0" autocomplete="off" readonly="readonly" />
                                                                </div>
                                                                <div class="col-sm-1" style="padding-left:0px;">
                                                                    <i class="fa fa-user add-employee" aria-hidden="true" id="addEmployee"></i>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <input class="form form-control inptxt input-sm reqfield ninternalcusq<?php echo $cnt;?>" name="ninternalcusq[]" data-num="<?php echo $cnt; ?>" id="ninternalcusq" value="0" placeholder="0" autocomplete="off" readonly="readonly" />
                                                                </div>
                                                            </div>
                                                    <?php 	
                                                    	$cnt++;
                                                        endforeach;
                                                    ?>
                                                    </div>                      
                                            </div>
<!--                                             <div class="labelinternaltot">
                                                <input type="hidden" name="totolrequestinternal" id="totolrequestinternal" value="0">                        
                                                <label>Total: <span id="internaltot"><?php echo number_format($total,2); ?></span></label>
                                            </div> -->
                                            <div class="labelinternaltot">
                                                <input type="hidden" name="totolrequestinternal" id="totolrequestinternal" value="0">                        
                                                <label>Total: <span id="internaltotx"><?php echo number_format($total,2); ?></span></label>
                                            </div>


                                            <div class="form-group">
                                                <label class="nobot">Entry By:</label> 
                                                <input type="text" readonly="readonly" class="form-control inptxt input-sm" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>"> 
                                            </div>

                                            <div class="response">
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-offset-5 col-sm-7">
                                                    <button type="submit" class="btn btn-block btn-primary" id="externalbtn"> <span class="glyphicon glyphicon-share" aria-hidden="true"></span> Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $('#lightgallery').lightGallery({
                selector:'.selector'
            });

 			$('.amtinwords').val(toWords($('.amount-external').val()));

            $('input#input-file').fileinput({
              'allowedFileExtensions' : ['jpg','png','jpeg']
            });

			$('.form-container').on('submit','form#specialExternalGCAddEmp',function(event){
				event.preventDefault();
				$('.response').html('');
				var formURL = $(this).attr('action'), formData = new FormData($(this)[0]);	

				var isNotDsame = false;
				var isZero = false;

				$('.ninternalcusqty').each(function(){
					var qty = $(this).val();
					var empscan = $(this).parent().parent().find('#ninternalcusq').val();
					qty = isNaN(qty) ? 0 : qty;
					empscan = isNaN(empscan) ? 0 : empscan;

					if(empscan == 0 || qty == 0)
					{			
						isZero = true;
						return false;			
					}

					if(empscan != qty)
					{
						isNotDsame = true;
						return true;
					}
				});
				if(isZero)
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Denom Qty or Emp Scanned must not be empty.</div');
					return false;					
				}

				if(isNotDsame)
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Denom Qty and Emp Scanned must equal.</div');
					return false;						
				}

		        BootstrapDialog.show({
		        	title: 'Confirmation',
		            message: 'Save Data?',
		            closable: true,
		            closeByBackdrop: false,
		            closeByKeyboard: true,
		            onshow: function(dialog) {
		               	$("button#externalbtn").prop("disabled",true);
		            },
		            onhidden: function(dialog){
		            	$("button#externalbtn").prop("disabled",false);
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
									$('#processing-modal').modal('show');
								},
								success:function(data)
								{
									$('#processing-modal').modal('hide');
									console.log(data);
									var data = JSON.parse(data);
									if(data['st'])
									{
										var dialog = new BootstrapDialog({
							            message: function(dialogRef){
							            var $message = $('<div>GC Holder Entry Successfully Saved.</div>');			        
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
					                    	window.location = 'index.php';
					               		}, 1700);	
									}
									else 
									{
										$('.response').html('<div class="alert alert-danger" id="danger-x">'+data['msg']+'</div>');
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

				return false;
			});            

            $('.optionBox').on('click','#addEmployee',function(){
                var den = $(this).parent().parent().find('.reqfield').val();
                var numscanemp = $(this).parent().parent().find('#ninternalcusq').val();
                var gcqty = $(this).parent().parent().find('#ninternalcusqty').val();
                var denid = $(this).parent().parent().find('#ninternalcusq').attr('data-num');

                var reqid = $('#reqid').val();
                
                den = den.replace(/,/g , "");
                
                den = isNaN(den) ? 0 : den;
                numscanemp = isNaN(numscanemp) ? 0 : numscanemp; // employee number
                gcqty = isNaN(gcqty) ? 0 : gcqty; //number of gc
                denid = isNaN(denid) ? 0 : denid;

                if(den==0)
                {
                    alert('Please input denomination.');
                    return false;
                }

                if(gcqty==0)
                {
                    alert('Invalid denomination quantity.');
                    return false;
                }
                // numscanemp++;
                // if(numscanemp > gcqty)
                // {
                // 	alert('max');
                // 	return false;
                // }

                BootstrapDialog.show({
                    title: 'Assign Customer Employee',
                    cssClass: 'modal-details-strel',
                    closable: true,
                    closeByBackdrop: false,
                    closeByKeyboard: true,
                    message: function(dialog) {
                        var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
                        var pageToLoad = dialog.getData('pageToLoad');
                        setTimeout(function(){
                        $message.load(pageToLoad);
                        },1000);
                        return $message;
                    },
                    data: {
                        'pageToLoad': '../templates/special-external-gc.php?page=addempperdenom&den='+den+'&numscanemp='+numscanemp+'&gcqty='+gcqty+'&denid='+denid+'&reqid='+reqid
                    },
                    onshow: function(dialog) {
                        // dialog.getButton('button-c').disable();
                    },
                    onshown: function(dialogRef){
                        setTimeout(function(){
                            $('input[name=lastname]').focus();   
                        },1200);
                        
                    }, 
                    onhidden: function()
                    {                       
                    },
                    buttons: [{
                        icon: 'glyphicon glyphicon-ok-sign',
                        label: 'Close',
                        cssClass: 'btn-primary',
                        action:function(dialogItself){
                            dialogItself.close();
                        }
                    }]

                });

            });

        </script>

    <?php

}

function _specialexternalgcholderentrylist($link,$todays_date)
{
    $table = 'special_external_gcrequest';
    $select = " special_external_gcrequest.spexgc_num,
        special_external_gcrequest.spexgc_dateneed,
        special_external_gcrequest.spexgc_id,
        special_external_gcrequest.spexgc_datereq,
        CONCAT(users.firstname,' ',users.lastname) as prep,
        special_external_customer.spcus_companyname";
    $where = "special_external_gcrequest.spexgc_status='pending'
    	AND
    		spexgc_addemp='pending'";
    $join = 'INNER JOIN
            users
        ON
            users.user_id = special_external_gcrequest.spexgc_reqby
        INNER JOIN
            special_external_customer
        ON
            special_external_customer.spcus_id = special_external_gcrequest.spexgc_company';
    $limit = 'ORDER BY special_external_gcrequest.spexgc_id ASC';

    $request = getAllData($link,$table,$select,$where,$join,$limit);
	?>
        <div class="row form-container">
            <div class="col-md-12">
                <div class="panel with-nav-tabs panel-info">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Pending Special External (GC Holder Entry) List</a></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab1default">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table" id="storeRequestList">
                                            <thead>
                                                <tr>
                                                    <th>RFSEGC #</th>
                                                    <th>Date Requested</th>
                                                    <th>Date Needed</th>
                                                    <th>Total Denomination</th>
                                                    <th>Customer</th>
                                                    <th>Requested By</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($request as $r): ?>
                                                <tr onclick="window.document.location='#/specialexternalgcholderentry/<?php echo $r->spexgc_id;  ?>'">
                                                    <td><?php echo $r->spexgc_num; ?></td>
                                                    <td><?php echo _dateFormat($r->spexgc_datereq); ?></td>
                                                    <td><?php echo _dateFormat($r->spexgc_dateneed); ?></td>
                                                    <td><?php echo number_format(totalExternalRequestTresDept($link,$r->spexgc_id)[0],2); ?></td>
                                                    <td><?php echo ucwords($r->spcus_companyname); ?></td>
                                                    <td><?php echo ucwords($r->prep); ?></td>
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
            $('#storeRequestList,#list').dataTable( {
                "pagingType": "full_numbers",
                "ordering": false,
                "processing": true
            });
        </script>

	<?php

}

function _specialgcpayment($link,$todays_date)
{
	?>
	    <div class="row form-container">
	        <div class="col-md-12">
	            <div class="panel with-nav-tabs panel-info">
	                <div class="panel-heading">
	                    <ul class="nav nav-tabs">
	                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Special External GC Payment</a></li>
	                        <!-- <li><a href="#tab2default" data-toggle="tab">Approved Details</a></li> -->
	                    </ul>
	                </div>
	                <div class="panel-body">
	                    <div class="tab-content">
	                        <div class="tab-pane fade in active" id="tab1default">
		                      	<div class="row form-container">
			                        <form action="../ajax.php?action=specialExternalGCPayment" method="POST" id="specialExternalGCRequestNew" enctype="multipart/form-data">                  
			                         	<div class="col-sm-12">
											<div class="col-sm-3">

												<div class="form-group">
													<input type="hidden" name="reqtype" value="2">
													<label class="nobot">Transaction #</label>   
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo getRequestNoByExternal($link); ?>" name="reqnum" id="reqnum">  
												</div>

												<div class="form-group">
													<label class="nobot">Payment Date</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo _dateFormat($todays_date); ?>">                                 
												</div>

												<div class="form-group">
													<label class="nobot"><span class="requiredf">*</span>Date Needed:</label>
													<input type="text" class="form form-control inptxt input-sm ro bot-6" id="dp1" data-date-format="MM dd, yyyy" name="date_needed" readonly="readonly" required>
												</div>

												<div class="form-group">
													<label class="nobot">Upload Document</label> 
													<input id="input-file" class="file" type="file" name="docs[]" multiple>
												</div>

											</div>

											<div class="col-sm-4">

												<div class="form-group">
													<input type="hidden" name="companyid" id="companyid" value="">
													<label class="nobot"><span class="requiredf">*</span>Company Name</label>   
													<textarea class="form form-control input-sm inptxt" readonly="readonly" name="compname" id="compname"></textarea>
												</div>  

												<div class="form-group">
													<label class="nobot"><span class="requiredf">*</span>Account Name</label>   
													<input type="text" class="form form-control inptxt" readonly="readonly" name="accname" id="accname">
												</div>      

												<div class="form-group">
													<button type="button" class="btn btn-info fordialog	" onclick="lookupCustomerExternal();"><i class="fa fa-search-plus" aria-hidden="true"></i>
													Lookup Customer</button>
												</div>      
												<div class="form-group">
													<label class="nobot"><span class="requiredf">*</span>AR Number</label>   
													<input type="text" class="form form-control inptxt" name="arnumber" id="arnumber">
												</div>
												<div class="form-group">
													<label class="nobot"><span class="requiredf">*</span>Payment Type</label>   
													<select class="form form-control input-sm inptxt" name="paymenttype" id="paymenttype" required>
													<option value="">- Select -</option>
													<option value="1">Cash</option>
													<option value="2">Check</option>
													</select>
												</div>

												<div class="paymenttypediv" style="display:none;">
													<div class="checkPayment">
														<div class="form-group">
															<label class="nobot"><span class="requiredf">*</span>Bank Name</label>
															<input type="text" class="form form-control inptxt input-sm bot-6 amts" name="bankname" id="bankname">
														</div>
														<div class="form-group">
															<label class="nobot"><span class="requiredf">*</span>Account Number</label>
															<input type="text" class="form form-control inptxt input-sm bot-6 amts" name="baccountnum" id="baccountnum">
														</div>
														<div class="form-group">
															<label class="nobot"><span class="requiredf">*</span>Check Number</label>
															<input type="text" class="form form-control inptxt input-sm bot-6 amts" name="cnumber" id="cnumber">
														</div>
													</div>													
													<div class="form-group">
														<label class="nobot"><span class="requiredf">*</span><span class="cashcheck"></span> Amount</label>
														<input type="text" class="form form-control inptxt input-sm bot-6 amount-external amts" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" name="amount" id="amount" required>
													</div>
													<div class="form-group">
														<label class="nobot"><span class="requiredf">*</span>Amount in words</label>
														<textarea class="form form-control input-sm inptxt amtinwords" id="amtinwords" readonly="readonly"></textarea>
													</div>
												</div>
											</div>

											<div class="col-sm-5">
												<div class="form-group">
													<label class="nobot"><span class="requiredf">*</span>Remarks</label> 
													<input type="text" class="form-control inptxt input-sm" name="remarks" autocomplete="off" required>
												</div>

												<div class="form-horizontal">
													<div class="form-group">
														<label class="col-sm-6"><span class="requiredf">*</span>Denomination</label>
														<label class="col-sm-6"><span class="requiredf">*</span>Qty</label>
													</div>

													<div class="optionBox">
														<button class="btn btn-info fordialog" type="button" id="addenombutnew"><i class="fa fa-plus-circle" aria-hidden="true"></i>
														Add Denomination</button>
														</div>
													</div>

													<!-- end form horizontal -->

													<div class="labelinternaltot">
														<input type="hidden" name="totolrequestinternal" id="totolrequestinternal" value="0">                        
														<label>Total: <span id="internaltot">0.00</span></label>
													</div>

													<div class="response">
													</div>

													<div class="form-group">
														<div class="col-sm-offset-5 col-sm-7">
														<button type="submit" class="btn btn-block btn-primary" id="externalbtn"> <span class="glyphicon glyphicon-share" aria-hidden="true"></span>Submit</button>
													</div>
												</div>


											</div>
			                           	</div>
			                        </form>
								</div>
	                        </div>
	                        <!-- <div class="tab-pane fade" id="tab2default">Sample</div> -->
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	    <script type="text/javascript">
			var nowTemp = new Date();
			var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

			var checkin = $('#dp1').datepicker({

			    beforeShowDay: function (date) {
			        return date.valueOf() >= now.valueOf();
			    },
			    autoclose: true

			});

			$('#amount').inputmask();
		    $('input#input-file').fileinput({
		      'allowedFileExtensions' : ['jpg','png','jpeg']
		    });	    
		    
			$('#paymenttype').change(function(){
				$('#amount').val(0.00);
				$('#amtinwords').val('');
				var type = $(this).val();
				if(type=='')
				{
					$('.paymenttypediv').hide();
				}
				else if(type=='1')
				{
					$('.paymenttypediv').fadeIn(500).show(600);
					$('.checkPayment').hide();
					$('.cashcheck').text('Cash');
					$('#bankname').prop('required',false);
					$('#baccountnum').prop('required',false);
					$('#cnumber').prop('required',false);
				}
				else if(type=='2')
				{
					$('.paymenttypediv').fadeIn(500).show(600);
					$('.checkPayment').fadeIn(500).show(600);
					$('.cashcheck').text('Check');
					$('#bankname').prop('required',true);
					$('#baccountnum').prop('required',true);
					$('#cnumber').prop('required',true);
				}
			});	

			$('.amount-external').keyup(function(){
				var inputs = $(this).val();
				inputs = inputs.replace(/,/g , "");
				$('.amtinwords').val(toWords(inputs));
			});

		    var limit = 20;
		    var dencnt = 1;

			$('div.optionBox button#addenombutnew').click(function(){
				BootstrapDialog.show({
			    	title: 'Add Denomination',
		        	cssClass: 'store-staff-dialog',
			        closable: true,
			        closeByBackdrop: false,
			        closeByKeyboard: true,
		            message: function(dialog) {
		                var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
		                var pageToLoad = dialog.getData('pageToLoad');
						setTimeout(function(){
		                $message.load(pageToLoad);
						},1000);
		                return $message;
		            },
		            data: {
		                'pageToLoad': '../dialogs/extenalgc.php?action=createdenom'
		            },
			        onshow: function(dialog) {
			            // dialog.getButton('button-c').disable();
			        },
			        onshown: function(dialog){
			        	setTimeout(function(){
			        		$('#denocr').select();
			        	},1200);
			        }, 
			        onhidden: function()
			        {	        	
			        },
			        buttons: [{
			            icon: 'glyphicon glyphicon-ok-sign',
			            label: 'Submit',
			            cssClass: 'btn-primary',
			            hotkey: 13,
			            action:function(dialogItself){
			            	var duplicate = false;
			            	$('#denocr').focus();
			            	$('.responsecr').html('');
			            	if($('#denocr').val() == undefined)
			            	{
			            		return false;
			            	}

			            	if($('#denocr').val()==0 || $('#denocr').val().trim()=='')
			            	{
			            		$('.responsecr').html('<div class="alert alert-danger" id="danger-x">Please input valid denomination.</div');
			            		return false;
			            	}

			            	var den = $('#denocr').val();

			            	if($('.optionBox input.denfield').length > 0)
							{
								$('.denfield').each(function(){
									if($(this).val()==den)
									{
										duplicate = true;
										return false;
									}
								});
							}

							if(duplicate)
							{
								$('.responsecr').html('<div class="alert alert-danger" id="danger-x">Denomination already exist.</div');
								return false;
							}

							if(dencnt <= limit)
							{			
						 		$('button#addenombutnew').before('<div class="form-group">'+
						        '<div class="col-sm-4">'+
						          '<input class="form form-control inptxt input-sm reqfield denfield ninternalcusd'+dencnt+'" name="ninternalcusd[]" id="ninternalcusd" value="'+den+'" placeholder="0" autocomplete="off" autofocus readonly="readonly" />'+
						        '</div>'+
						        '<div class="col-sm-4">'+
						          '<input class="form form-control inptxt input-sm reqfield denomqtyspc ninternalcusq'+dencnt+'" name="ninternalcusq[]" data-num="'+dencnt+'" id="ninternalcusq" value="0" placeholder="0" autocomplete="off" autofocus />'+
						        '</div>'+
						        '<div class="col-sm-4" style="padding-left:0px;">'+
						          	'<i class="fa fa-minus-square minus-denom removed" aria-hidden="true"></i>'+
						        '</div>'+
						      '</div>');

						 		dencnt++;

						 		$('#ninternalcusd,#ninternalcusq').inputmask("integer", { allowMinus: false,autoGroup: true, groupSeparator: ",", groupSize: 3,digits: 2 });
						 		dialogItself.close();
						 	}	
						 	else 
						 	{
			            		$('.responsecr').html('<div class="alert alert-danger" id="danger-x">Something went wrong.</div');
			            		return false;
						 	}

			            }
			        }]

			    });	

				return false;

			});		

	    // 		$("[class^='ninternalcusq']").each(function(){
					// alert($(this).val());                      			
	    // 		});	

			//dri

		    $(document).on('change','input#ninternalcusd, input#ninternalcusq',function() {
		    	scanInternalInput();
		    });

		    $(document).on('keyup','input#ninternalcusd, input#ninternalcusq',function() {
		    	scanInternalInput();
		    });

			$(document).on('click','.removed',function() {
				var den = $(this).parent().parent().find('.reqfield').val();
				var thisdiv = $(this);
				var r = confirm("Remove Denomination?");
				if (r == true) {
					$.ajax({
						url:'../ajax.php?action=deleteSessionKeyByDen',
						data:{den:den},
						type:'POST',
						success:function(data)
						{
							console.log(data);
							var data = JSON.parse(data);	
						 	thisdiv.parent('div').parent('div').remove();
						 	dencnt--;
						 	scanInternalInput();
						}
					})

				}
			});

			$('.form-container').on('submit','#specialExternalGCRequestNew',function(event){
				event.preventDefault();
				$('.response').html('');
				var formURL = $(this).attr('action'), formData = new FormData($(this)[0]);	

				// var files = document.getElementById("input-file").files;
				// if(!files.length > 0)
				// {
				// 	$('.response').html('<div class="alert alert-danger" id="danger-x">Only "jpg, png, jpeg" files are supported.</div>');
				// 	return false;
				// }	

				if($('#reqnum').val()=='')
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">GC Request # is empty.</div>');
					return false;
				}
				if($('#dp1').val().trim()=='')
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Please select date needed.</div>');
					return false;
				}

				if($('#companyid').val().trim()=='')
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Please select customer.</div>');
					return false;
				}

				if($('#amount').val().trim()=='0.00' || $('#amount').val().trim()=='')
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Please input valid amount.</div>');
					return false;			
				}

				var amount = $('#amount').val().trim();
				amount = amount.replace(/,/g , "");
				amount = isNaN(amount) ? 0 : amount;

				if(amount > 0)
				{
					
				}
				else 
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Please input valid amount.</div>');
					return false;
				}

				if($('div.optionBox input#ninternalcusd').length == 0)
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Please add denomination.</div>');
					return false;				
				}
				var denomZero = false;
				var denomDup = false;
				var denomArr = [];
				$('div.optionBox input#ninternalcusd').each(function(){
					if($(this).val().trim()==0)
					{
						denomZero = true;
					}

					if($.inArray($(this).val(), denomArr) !== -1)
					{
						denomDup = true;
					}

					denomArr.push($(this).val());

				});

				if(denomZero)
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Please input valid denomination value.</div>');
					return false;
				}

				if(denomDup)
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Duplicate denomination.</div>');
					return false;
				}

				var qtyZero = false;

				$('div.optionBox #ninternalcusq').each(function(){
					if($(this).val().trim()==0)
					{
						qtyZero = true;
					}
				});

				if(qtyZero)
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Please input quanity.</div>');
					return false;
				}

		        BootstrapDialog.show({
		        	title: 'Confirmation',
		            message: 'Are you sure you want to submit GC Transfer Request?',
		            closable: true,
		            closeByBackdrop: false,
		            closeByKeyboard: true,
		            onshow: function(dialog) {
		               	$("button#externalbtn").prop("disabled",true);
		            },
		            onhidden: function(dialog){
		            	$("button#externalbtn").prop("disabled",false);
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
									$('#processing-modal').modal('show');
								},
								success:function(data)
								{
									$('#processing-modal').modal('hide');
									console.log(data);
									var data = JSON.parse(data);
									if(data['st'])
									{
										var dialog = new BootstrapDialog({
							            message: function(dialogRef){
							            var $message = $('<div>Request Successfully Saved.</div>');			        
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
								        // setTimeout(function(){
					           //          	dialog.close();
					           //     		}, 1500);
					               		setTimeout(function(){
					                    	window.location.href = 'reportspecialexternalsales.php?id='+data['id'];
					               		}, 1700);	
									}
									else 
									{
										$('.response').html('<div class="alert alert-danger" id="danger-x">'+data['msg']+'</div>');
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

				return false;
			});


	    </script>
	<?php
}

function requestSpecialGCwithHolder($link,$todays_date)
{
	if(isset($_SESSION['empAssign']))
	{
		unset($_SESSION['empAssign']);
	}

	?>
		<div class="row">
			<div class="col-sm-12">
				<div class="panel with-nav-tabs panel-info">
					<div class="panel-heading">
						<ul class="nav nav-tabs">
							<li class="active" style="font-weight:bold">
								<a href="#tab1default" data-toggle="tab">Special External GC Request</a>
							</li>
						</ul>
					</div>
					<div class="panel-body">
		                <div class="tab-content">
		                    <div class="tab-pane fade in active" id="tab1default">
		                      	<div class="row form-container">
			                        <form action="../ajax.php?action=specialExternalGCRequest" method="POST" id="specialExternalGCRequest" enctype="multipart/form-data">                  
			                         	<div class="col-sm-12">
											<div class="col-sm-3">

												<div class="form-group">
													<input type="hidden" name="reqtype" value="2">
													<label class="nobot">GC Request #</label>   
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo getRequestNoByExternal($link); ?>" name="reqnum" id="reqnum">  
												</div>

												<div class="form-group">
													<label class="nobot">Date Requested</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo _dateFormat($todays_date); ?>">                                 
												</div>

												<div class="form-group">
													<label class="nobot"><span class="requiredf">*</span>Date Needed:</label>
													<input type="text" class="form form-control inptxt input-sm ro bot-6" id="dp1" data-date-format="MM dd, yyyy" name="date_needed" readonly="readonly" required>
												</div>

												<div class="form-group">
													<label class="nobot">Upload Document</label> 
													<input id="input-file" class="file" type="file" name="docs[]" multiple>
												</div>

											</div>

											<div class="col-sm-4">

												<div class="form-group">
													<input type="hidden" name="companyid" id="companyid" value="">
													<label class="nobot"><span class="requiredf">*</span>Company Name</label>   
													<textarea class="form form-control input-sm inptxt" readonly="readonly" name="compname" id="compname"></textarea>
												</div>  

												<div class="form-group">
													<label class="nobot"><span class="requiredf">*</span>Account Name</label>   
													<input type="text" class="form form-control inptxt" readonly="readonly" name="accname" id="accname">
												</div>      

												<div class="form-group">
													<button type="button" class="btn btn-default" onclick="lookupCustomerExternal();"><i class="fa fa-search-plus" aria-hidden="true"></i>
													Lookup Customer</button>
												</div>      

												<div class="form-group">
													<label class="nobot"><span class="requiredf">*</span>Payment Type</label>   
													<select class="form form-control input-sm inptxt" name="paymenttype" id="paymenttype" required>
													<option value="">- Select -</option>
													<option value="1">Cash</option>
													<option value="2">Check</option>
													</select>
												</div>

												<div class="paymenttypediv" style="display:none;">
													<div class="checkPayment">
														<div class="form-group">
															<label class="nobot"><span class="requiredf">*</span>Bank Name</label>
															<input type="text" class="form form-control inptxt input-sm bot-6" name="bankname" id="bankname">
														</div>
														<div class="form-group">
															<label class="nobot"><span class="requiredf">*</span>Check Number</label>
															<input type="text" class="form form-control inptxt input-sm bot-6" name="cnumber" id="cnumber">
														</div>
													</div>													
													<div class="form-group">
														<label class="nobot"><span class="requiredf">*</span><span class="cashcheck"></span> Amount</label>
														<input type="text" class="form form-control inptxt input-sm bot-6 amount-external" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" name="amount" id="amount" required>
													</div>
													<div class="form-group">
														<label class="nobot"><span class="requiredf">*</span>Amount in words</label>
														<textarea class="form form-control input-sm inptxt amtinwords" id="amtinwords" readonly="readonly"></textarea>
													</div>
												</div>
											</div>

											<div class="col-sm-5">
												<div class="form-group">
													<label class="nobot"><span class="requiredf">*</span>Remarks</label> 
													<input type="text" class="form-control inptxt input-sm" name="remarks" autocomplete="off" required>
												</div>
												<div class="form-horizontal">
													<div class="form-group">
														<label class="col-sm-6"><span class="requiredf">*</span>Denomination</label>
														<label class="col-sm-6"><span class="requiredf">*</span>Qty</label>
														</div>
														<div class="optionBox">
														<button class="btn btn-default" type="button" id="addenombut"><i class="fa fa-plus-circle" aria-hidden="true"></i>
														Add Denomination</button>
													</div>
												</div>
												<div class="labelinternaltot">
													<input type="hidden" name="totolrequestinternal" id="totolrequestinternal" value="0">                        
													<label>Total: <span id="internaltot">0.00</span></label>
												</div>
												<div class="form-group">
													<label class="nobot">Prepared By:</label> 
													<input type="text" readonly="readonly" class="form-control inptxt input-sm" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>"> 
												</div>

												<div class="response">
												</div>

												<div class="form-group">
													<div class="col-sm-offset-5 col-sm-7">
														<button type="submit" class="btn btn-block btn-primary" id="externalbtn"> <span class="glyphicon glyphicon-share" aria-hidden="true"></span>Submit</button>
													</div>
												</div>
											</div>
			                           	</div>
			                        </form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			var nowTemp = new Date();
			var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

			var checkin = $('#dp1').datepicker({

			    beforeShowDay: function (date) {
			        return date.valueOf() >= now.valueOf();
			    },
			    autoclose: true

			});

			$('#amount').inputmask();
		    $('input#input-file').fileinput({
		      'allowedFileExtensions' : ['jpg','png','jpeg']
		    });

			$('#paymenttype').change(function(){
				$('#amount').val(0.00);
				$('#amtinwords').val('');
				var type = $(this).val();
				if(type=='')
				{
					$('.paymenttypediv').hide();
				}
				else if(type=='1')
				{
					$('.paymenttypediv').fadeIn(500).show(600);
					$('.checkPayment').hide();
					$('.cashcheck').text('Cash');
					$('#bankname').prop('required',false);
					$('#baccountnum').prop('required',false);
					$('#cnumber').prop('required',false);
				}
				else if(type=='2')
				{
					$('.paymenttypediv').fadeIn(500).show(600);
					$('.checkPayment').fadeIn(500).show(600);
					$('.cashcheck').text('Check');
					$('#bankname').prop('required',true);
					$('#baccountnum').prop('required',true);
					$('#cnumber').prop('required',true);
				}
			});

		    $(document).on('change','input#ninternalcusd, input#ninternalcusq',function() {
		    	scanInternalInput();
		    });

		    // $(document).on('keyup','input#ninternalcusd, input#ninternalcusq',function() {
		    // 	scanInternalInput();
		    // });

		    var limit = 10;
		    var dencnt = 1;

			$('div.optionBox button#addenombut').click(function(){
				BootstrapDialog.show({
			    	title: 'Add Denomination',
		        	cssClass: 'store-staff-dialog',
			        closable: true,
			        closeByBackdrop: false,
			        closeByKeyboard: true,
		            message: function(dialog) {
		                var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
		                var pageToLoad = dialog.getData('pageToLoad');
						setTimeout(function(){
		                $message.load(pageToLoad);
						},1000);
		                return $message;
		            },
		            data: {
		                'pageToLoad': '../dialogs/extenalgc.php?action=createdenom'
		            },
			        onshow: function(dialog) {
			            // dialog.getButton('button-c').disable();
			        },
			        onshown: function(dialog){
			        	setTimeout(function(){
			        		$('#denocr').select();
			        	},1200);
			        }, 
			        onhidden: function()
			        {	        	
			        },
			        buttons: [{
			            icon: 'glyphicon glyphicon-ok-sign',
			            label: 'Submit',
			            cssClass: 'btn-primary',
			            hotkey: 13,
			            action:function(dialogItself){
			            	var duplicate = false;
			            	$('#denocr').focus();
			            	$('.responsecr').html('');
			            	if($('#denocr').val() == undefined)
			            	{
			            		return false;
			            	}

			            	if($('#denocr').val()==0 || $('#denocr').val().trim()=='')
			            	{
			            		$('.responsecr').html('<div class="alert alert-danger" id="danger-x">Please input valid denomination.</div');
			            		return false;
			            	}

			            	var den = $('#denocr').val();

			            	if($('.optionBox input.denfield').length > 0)
							{
								$('.denfield').each(function(){
									if($(this).val()==den)
									{
										duplicate = true;
										return false;
									}
								});
							}

							if(duplicate)
							{
								$('.responsecr').html('<div class="alert alert-danger" id="danger-x">Denomination already exist.</div');
								return false;
							}

							if(dencnt <= limit)
							{			
						 		$('button#addenombut').before('<div class="form-group">'+
						        '<div class="col-sm-4">'+
						          '<input class="form form-control inptxt input-sm reqfield denfield ninternalcusd'+dencnt+'" name="ninternalcusd[]" id="ninternalcusd" value="'+den+'" placeholder="0" autocomplete="off" autofocus readonly="readonly" />'+
						        '</div>'+
						        '<div class="col-sm-4">'+
						          '<input class="form form-control inptxt input-sm reqfield ninternalcusq'+dencnt+'" name="ninternalcusq[]" data-num="'+dencnt+'" id="ninternalcusq" value="0" placeholder="0" autocomplete="off" autofocus readonly="readonly" />'+
						        '</div>'+
						        '<div class="col-sm-4" style="padding-left:0px;">'+
						        	'<i class="fa fa-user add-employee" aria-hidden="true" id="addEmployee"></i>'+
						          	'<i class="fa fa-minus-square minus-denom removed" aria-hidden="true"></i>'+
						        '</div>'+
						      '</div>');

						 		dencnt++;

						 		$('#ninternalcusd,#ninternalcusq').inputmask("integer", { allowMinus: false,autoGroup: true, groupSeparator: ",", groupSize: 3,digits: 2 });
						 		dialogItself.close();
						 	}	
						 	else 
						 	{
			            		$('.responsecr').html('<div class="alert alert-danger" id="danger-x">Something went wrong.</div');
			            		return false;
						 	}

			            }
			        }]

			    });

				return false;

			});

			$(document).on('click','.removed',function() {
				var den = $(this).parent().parent().find('.reqfield').val();
				var thisdiv = $(this);
				var r = confirm("Remove Denomination?");
				if (r == true) {
					$.ajax({
						url:'../ajax.php?action=deleteSessionKeyByDen',
						data:{den:den},
						type:'POST',
						success:function(data)
						{
							console.log(data);
							var data = JSON.parse(data);	
						 	thisdiv.parent('div').parent('div').remove();
						 	dencnt--;
						 	scanInternalInput();
						}
					})

				}
			});

			$('.optionBox').on('click','#addEmployee',function(){
				var den = $(this).parent().parent().find('.reqfield').val();			
				var datanum = $(this).parent().parent().find('#ninternalcusq').attr('data-num');
				if(den.trim()==0)
				{
					alert('Please input denomination.');
					return false;
				}
				BootstrapDialog.show({
			    	title: 'Assign GC Holder',
			 	    cssClass: 'modal-details-strel',
			        closable: true,
			        closeByBackdrop: false,
			        closeByKeyboard: true,
		            message: function(dialog) {
		                var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
		                var pageToLoad = dialog.getData('pageToLoad');
						setTimeout(function(){
		                $message.load(pageToLoad);
						},1000);
		                return $message;
		            },
		            data: {
		                'pageToLoad': '../dialogs/extenalgc.php?action=assignemp&den='+den+'&datanum='+datanum
		            },
			        onshow: function(dialog) {
			            // dialog.getButton('button-c').disable();
			        },
			        onshown: function(dialogRef){
			        	setTimeout(function(){
			        		$('input[name=lastname]').focus();	 
			        	},1200);
			        	
			        }, 
			        onhidden: function()
			        {	        	       	
			        },
			        buttons: [{
			            icon: 'glyphicon glyphicon-ok-sign',
			            label: 'Close',
			            cssClass: 'btn-primary',
			            action:function(dialogItself){
			            	dialogItself.close();
			            }
			        }]

			    });

			});

			function scanInternalInput()
			{
				var subtotal = 0;
				var total = 0;
				var numinput = $('.optionBox #ninternalcusd').length;
				$('.optionBox #ninternalcusd').each(function(){

					deinternal = $(this).val();
					deinternal = deinternal.replace(/,/g , "");
					deinternal = isNaN(deinternal) ? 0 : deinternal;

					qtyinternal = $(this).closest('div.form-group').find('input#ninternalcusq').val();
					qtyinternal = qtyinternal.replace(/,/g , "");
					qtyinternal = isNaN(qtyinternal) ? 0 : qtyinternal;

					subtotal = deinternal * qtyinternal;

					total +=subtotal;
				});
				//alert(total);
				$('label span#internaltot').text(addCommas(total.toFixed(2)));
				$('input#totolrequestinternal').val(total);
			}

			$('.amount-external').keyup(function(){
				var inputs = $(this).val();
				inputs = inputs.replace(/,/g , "");
				$('.amtinwords').val(toWords(inputs));
			});

			$('.form-container').on('submit','#specialExternalGCRequest',function(event){
				event.preventDefault()
				$('.response').html('');
				var formURL = $(this).attr('action'), formData = new FormData($(this)[0]);	

				var files = document.getElementById("input-file").files;
				if(!files.length > 0)
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Only "jpg, png, jpeg" files are supported.</div>');
					return false;
				}	

				if($('#reqnum').val()=='')
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">GC Request # is empty.</div>');
					return false;
				}
				if($('#dp1').val().trim()=='')
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Please select date needed.</div>');
					return false;
				}

				if($('#companyid').val().trim()=='')
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Please select customer.</div>');
					return false;
				}

				if($('#amount').val().trim()=='0.00' || $('#amount').val().trim()=='')
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Please input valid amount.</div>');
					return false;			
				}

				var amount = $('#amount').val().trim();
				amount = amount.replace(/,/g , "");
				amount = isNaN(amount) ? 0 : amount;

				if(amount > 0)
				{
					
				}
				else 
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Please input valid amount.</div>');
					return false;
				}

				if($('div.optionBox input#ninternalcusd').length == 0)
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Please add denomination.</div>');
					return false;				
				}
				var denomZero = false;
				var denomDup = false;
				var denomArr = [];
				$('div.optionBox input#ninternalcusd').each(function(){
					if($(this).val().trim()==0)
					{
						denomZero = true;
					}

					if($.inArray($(this).val(), denomArr) !== -1)
					{
						denomDup = true;
					}

					denomArr.push($(this).val());

				});

				if(denomZero)
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Please input valid denomination value.</div>');
					return false;
				}

				if(denomDup)
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Duplicate denomination.</div>');
					return false;
				}

				var qtyZero = false;

				$('div.optionBox #ninternalcusq').each(function(){
					if($(this).val().trim()==0)
					{
						qtyZero = true;
					}
				});

				if(qtyZero)
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Please assign customer employee.</div>');
					return false;
				}

		        BootstrapDialog.show({
		        	title: 'Confirmation',
		            message: 'Are you sure you want to submit GC Request?',
		            closable: true,
		            closeByBackdrop: false,
		            closeByKeyboard: true,
		            onshow: function(dialog) {
		               	$("button#externalbtn").prop("disabled",true);
		            },
		            onhidden: function(dialog){
		            	$("button#externalbtn").prop("disabled",false);
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
									$('#processing-modal').modal('show');
								},
								success:function(data)
								{
									$('#processing-modal').modal('hide');
									console.log(data);
									var data = JSON.parse(data);
									if(data['st'])
									{
										var dialog = new BootstrapDialog({
							            message: function(dialogRef){
							            var $message = $('<div>Request Successfully Saved.</div>');			        
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
					                    	window.location = 'index.php';
					               		}, 1700);	
									}
									else 
									{
										$('.response').html('<div class="alert alert-danger" id="danger-x">'+data['msg']+'</div>');
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

		</script>

	<?php
}

function specialExternalGCReviewedSingle($link,$id)
{
    $hasError = false;
    if(isset($_SESSION['scanReviewGC']))
        unset($_SESSION['scanReviewGC']);


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
	                                                <input type="text" class="form-control input-sm inptxt" id="totalgc" value="<?php echo number_format($request->spexgc_payment,2); ?>" readonly="readonly">
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
	                                                    <ul id="lightgallery" class="list-unstyled row" style="margin-bottom:0px;">  
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
												    <label class="col-sm-6 control-label"><span class="requiredf">*</span>Checked by:</label>
												    <div class="col-sm-5">
													    <div class="input-group">
													        <input name="checked" id="app-checkby" type="text" class="form-control input-sm inptxt reqfield" readonly="readonly" required="required">
													        <span class="input-group-btn">
													            <button class="btn btn-info input-sm" id="checkbud" onclick="requestAssig(<?php echo $_SESSION['gc_usertype']; ?>,1);" type="button">
													                <span class="glyphicon glyphicon-search"></span>
													            </button>
													        </span>
													    </div><!-- input group -->
												    </div>
												</div>  	                                     
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

	    <script type="text/javascript">

	    	$('#lightgallery').lightGallery();

			$('.form-container').on('submit','#gcreleased',function(event){
				event.preventDefault();
				$('.response').html('');
				var formURL = $(this).attr('action'), formData = $(this).serialize();
				var receivedby = $('#receiver').val();
				var checkedby = $('#app-checkby').val();
				if(receivedby.trim()=='')
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Please input received by textbox.</div>');
					$('#receiver').focus();
					return false;
				}

				if(checkedby.trim()=='')
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Please select checked by.</div>');
					$('#receiver').focus();
					return false;
				}

		        BootstrapDialog.show({
		        	title: 'Confirmation',
		            message: 'Are you sure you want Release GC?',
		            closable: true,
		            closeByBackdrop: false,
		            closeByKeyboard: true,
		            onshow: function(dialog) {
		               	$("button#gcreleasedbut").prop("disabled",true);
		            },
		            onhidden: function(dialog){
		            	$("button#gcreleasedbut").prop("disabled",false);
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

							$.ajax({
					    		url:formURL,
					    		type:'POST',
								data: formData,
								beforeSend:function(){
								},
								success:function(data)
								{ 
									console.log(data);
									var data = JSON.parse(data);
									if(data['st'])
									{
										var dialog = new BootstrapDialog({
							            message: function(dialogRef){
							            var $message = $('<div>Special External GC Successfully Released.</div>');			        
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
					                    	window.location.href = 'special-external-gcreleasedpdf.php?id='+data['trid'];
					               		}, 1700);	
									}
									else 
									{
										$('.response').html('<div class="alert alert-danger" id="danger-x">'+data['msg']+'</div>');
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

			$.extend( $.fn.dataTableExt.oStdClasses, {	  
			    "sLengthSelect": "selectsup"
			});

		    $('#storeRequestList').dataTable( {
		        "pagingType": "full_numbers",
		        "ordering": false,
		        "processing": true
		    });
	    </script>
	<?php
}

function specialExternalGCReviewed($link)
{
    $table = 'special_external_gcrequest';
    $select = "special_external_gcrequest.spexgc_num,
        special_external_gcrequest.spexgc_dateneed,
        special_external_gcrequest.spexgc_id,
        special_external_gcrequest.spexgc_datereq,
        CONCAT(users.firstname,' ',users.lastname) as prep,
        special_external_customer.spcus_companyname,
        special_external_gcrequest.spexgc_id,
        approved_request.reqap_approvedby";
    $where = "special_external_gcrequest.spexgc_status='approved'
        AND
            special_external_gcrequest.spexgc_reviewed='reviewed'
        AND
            approved_request.reqap_approvedtype='Special External GC Approved'
        AND
            special_external_gcrequest.spexgc_released=''";
    $join = 'INNER JOIN
            users
        ON
            users.user_id = special_external_gcrequest.spexgc_reqby
        INNER JOIN
            special_external_customer
        ON
            special_external_customer.spcus_id = special_external_gcrequest.spexgc_company
        INNER JOIN
            approved_request
        ON
            approved_request.reqap_trid = special_external_gcrequest.spexgc_id';
    $limit = 'ORDER BY special_external_gcrequest.spexgc_id ASC';

    $request = getAllData($link,$table,$select,$where,$join,$limit);

    ?>

	    <div class="row form-container">
	        <div class="col-md-12">
	            <div class="panel with-nav-tabs panel-info">
	                <div class="panel-heading">
	                    <ul class="nav nav-tabs">
	                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Reviewed Special External GC</a></li>
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
	                                                <th>RFSEGC #</th>
	                                                <th>Date Requested</th>
	                                                <th>Date Needed</th>
	                                                <th>Total Denom</th>
	                                                <th>Customer</th>
	                                                <th>Requested by</th>
	                                                <th>Approved By</th>
	                                                <th>Reviewed By</th>
	                                            </tr>
	                                        </thead>
	                                        <tbody>
	                                            <?php foreach ($request as $r): ?>
	                                            	<tr class="clickable" onclick="window.location='#/special-external-gc-reviewed/<?php echo $r->spexgc_id; ?>'">
	                                               
	                                                    <td><?php echo $r->spexgc_num; ?></td>
	                                                    <td><?php echo _dateFormat($r->spexgc_datereq); ?></td>
	                                                    <td><?php echo _dateFormat($r->spexgc_dateneed); ?></td>
	                                                    <td><?php echo number_format(totalExternalRequest($link,$r->spexgc_id)[0],2); ?></td>
	                                                    <td><?php echo ucwords($r->spcus_companyname); ?></td>
	                                                    <td><?php echo ucwords($r->prep); ?></td>
	                                                    <td><?php echo ucwords($r->reqap_approvedby); ?></td>
	                                                    <td>
	                                                        <?php 
	                                                            $table = 'approved_request';
	                                                            $select ="CONCAT(users.firstname,' ',users.lastname) as reviewee";
	                                                            $where = '';
	                                                            $join = 'INNER JOIN
	                                                                    users
	                                                                ON
	                                                                    users.user_id = approved_request.reqap_preparedby';
	                                                            $limit = "approved_request.reqap_trid='".$r->spexgc_id."'
	                                                                AND
	                                                                    approved_request.reqap_approvedtype='special external gc review'";
	                                                            $gc = getSelectedData($link,$table,$select,$where,$join,$limit);
	                                                            echo ucwords($gc->reviewee);
	                                                        ?>
	                                                    </td>
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

		    $('#storeRequestList').dataTable( {
		        "pagingType": "full_numbers",
		        "ordering": false,
		        "processing": true
		    });
	    </script>

    <?php	
}

function specialExternalRequestApprovedSingle($link,$reqid)
{    
    // if(!hasPageAccessView($link,3,$_SESSION['gc_id']))
    // {
    //     echo 'Page not found.';
    //     exit();
    // }
    $table = 'special_external_gcrequest';
    $select = "special_external_gcrequest.spexgc_id,
	    special_external_gcrequest.spexgc_num,
	    CONCAT(req.firstname,' ',req.lastname) as reqby,
	    special_external_gcrequest.spexgc_datereq,
	    special_external_gcrequest.spexgc_dateneed,
	    special_external_gcrequest.spexgc_remarks,
	    special_external_gcrequest.spexgc_payment,
	    special_external_gcrequest.spexgc_paymentype,
	    special_external_gcrequest.spexgc_payment_arnum,
	    special_external_customer.spcus_companyname,
	    institut_payment.institut_bankname,
	    institut_payment.institut_bankaccountnum,
	    institut_payment.institut_checknumber,
	    institut_payment.institut_amountrec,
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
            approved_request.reqap_approvedtype='Special External GC Approved'
		AND
		    institut_payment.insp_paymentcustomer='special external'";
    $join = 'INNER JOIN
		    users as req
		ON
		    req.user_id = special_external_gcrequest.spexgc_reqby
		INNER JOIN
		    special_external_customer
		ON
		    special_external_customer.spcus_id = special_external_gcrequest.spexgc_company  
		INNER JOIN
		    institut_payment
		ON
		    institut_payment.insp_trid = special_external_gcrequest.spexgc_id
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
        echo 'Page not found.';
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

    ?>
    <div class="row form-container">
        <div class="col-md-12">
            <div class="panel with-nav-tabs panel-info">
                <div class="panel-heading">
                    <button class="btn pull-right" onclick="window.location='#/special-external-request-approved/'">Back</button>
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
                                                <label class="nobot">AR #</label>
                                                <input type="text" class="form-control inptxt bot-6" value="<?php echo $data->spexgc_payment_arnum; ?>" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label class="nobot">Payment Type</label>
                                                <input type="text" class="form-control inptxt bot-6" value="<?php if($data->spexgc_paymentype==1){ echo 'Cash'; } else { echo 'Check'; } ?>" disabled>
                                            </div>
                                            <?php if($data->spexgc_paymentype==2): ?>
                                                <div class="form-group">
                                                    <label class="nobot">Bank Name</label>
                                                    <input type="text" class="form-control inptxt bot-6" value="<?php echo ucwords($data->institut_bankname); ?>" disabled>
                                                </div>
                                                <div class="form-group">
                                                    <label class="nobot">Bank Account Number</label>
                                                    <input type="text" class="form-control inptxt bot-6" value="<?php echo ucwords($data->institut_bankaccountnum); ?>" disabled>
                                                </div>
                                                <div class="form-group">
                                                    <label class="nobot">Check Number</label>
                                                    <input type="text" class="form-control inptxt bot-6" value="<?php echo ucwords($data->institut_checknumber); ?>" disabled>
                                                </div>
                                                <div class="form-group">
                                                    <label class="nobot">Check Amount</label>
                                                    <input type="text" class="form-control inptxt bot-6" value="<?php echo number_format($data->institut_amountrec,2); ?>" disabled>
                                                </div>
                                            <?php elseif($data->spexgc_paymentype==1): ?>
                                                <div class="form-group">
                                                    <label class="nobot">Amount</label>
                                                    <input type="text" class="form-control inptxt bot-6" value="<?php echo number_format($data->institut_amountrec,2); ?>" disabled>
                                                </div>
                                            <?php endif; ?>
                                        </div>                                        
                                    </div>
                                </div>
                                <div class="col-sm-6 margin-bot-0" style="background-color: beige;">
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
                                    <?php if($_SESSION['gc_usertype']=='4'): ?>
	                                    <div class="row margin-bot-0">
	                                    	<h4 class="printspecgc">Special GC Printing</h4>                                    	
	                                    	<input type="hidden" id="reqidspecgc" value="<?php echo $reqid; ?>">
	                                        <div class="col-md-5 printbr">
			                            		<ul>
			                            			<li><a href="spgccus.php?type=blank" target="_blank">Print Test Size</a></li>
			                            			<li><a class="byrequestid" href="spgccus.php?type=byrequestid&id=<?php echo $reqid; ?>" target="_blank">Print By Request ID</a></li>
			                            			<li><a class="byrange" href="spgccus.php?type=byrequestid&id=<?php echo $reqid; ?>" target="_blank">Print By Range</a></li>
			                            			<li><a class="bybarcode" href="spgccus.php?type=byrequestid&id=<?php echo $reqid; ?>" target="_blank">Print By Barcode</a></li>
			                            			<li><a class="bypage" href="spgccus.php?type=byrequestid&id=<?php echo $reqid; ?>" target="_blank">Print By Page</a></li>
			                            		</ul> 
	                                        </div>  
	                                         <div class="col-md-6">
	                                         	<div class="row printspeccontent">

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

        $('a.byrequestid').click(function(){
        	$('div.printbr ul li').each(function(){
        		$(this).css({"background-color": "#000"});
        	});
        	var reqid = $('input#reqidspecgc').val().trim();
        	$('.printspeccontent').load("../templates/special-external-gc.php?page=divbyrequestid&reqid="+reqid);
			$(this).parent().css({"background-color": "gray"});
        	return false;
        });

        $('a.byrange').click(function(){
        	$('div.printbr ul li').each(function(){
        		$(this).css({"background-color": "#000"});
        	});

        	$('.printspeccontent').load("../templates/special-external-gc.php?page=divbyrange");
			$(this).parent().css({"background-color": "gray"});
        	return false;
        });

        $('a.bybarcode').click(function(){
        	$('div.printbr ul li').each(function(){
        		$(this).css({"background-color": "#000"});
        	});

        	$('.printspeccontent').load("../templates/special-external-gc.php?page=divbybarcode");
			$(this).parent().css({"background-color": "gray"});
        	return false;
        });

        $('a.bypage').click(function(){
        	$('div.printbr ul li').each(function(){
        		$(this).css({"background-color": "#000"});
        	});
        	var reqid = $('input#reqidspecgc').val().trim();
        	$('.printspeccontent').load("../templates/special-external-gc.php?page=divbypage&reqid="+reqid);
			$(this).parent().css({"background-color": "gray"});
        	return false;
        });


    </script>


    <?php
}

function approvedSpecialExternalRequest($link)
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

    ?>
    <div class="row form-container">
        <div class="col-md-12">
            <div class="panel with-nav-tabs panel-info">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Approved Special External GC</a></li>
                    </ul>                    
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1default">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table" id="released">
                                        <thead>
                                            <tr>
                                                <th>RFSEGC #</th>
                                                <th>Date Requested</th>
                                                <th>Date Needed</th>
                                                <th>Customer</th>
                                                <th>Date Approved</th>
                                                <th>Approved By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($data as $d): ?>
                                                <tr class="clickable" onclick="window.location='#/special-external-request-approved/<?php echo $d->spexgc_id; ?>'">
                                                    <td><?php echo $d->spexgc_num; ?></td>
                                                    <td><?php echo _dateFormat($d->spexgc_datereq); ?></td>
                                                    <td><?php echo _dateFormat($d->spexgc_dateneed); ?></td>
                                                    <td><?php echo ucwords($d->spcus_companyname); ?></td>
                                                    <td><?php echo _dateFormat($d->reqap_date); ?></td>
                                                    <td><?php echo ucwords($d->reqap_approvedby); ?></td>
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
        $('#released').DataTable( {
            "order": [[ 0, "desc" ]]
        } );
    </script>

    <?php
}

function displaySpecialExternalUpdate($link,$reqid)
{
    if(isset($_SESSION['empAssign']))
    {
        unset($_SESSION['empAssign']);
    }
    //if request is still pending

    $table ='special_external_gcrequest';
    $select ='special_external_gcrequest.spexgc_id,
        special_external_gcrequest.spexgc_num,
        special_external_gcrequest.spexgc_datereq,
        special_external_gcrequest.spexgc_dateneed,
        special_external_gcrequest.spexgc_remarks,
        special_external_gcrequest.spexgc_payment,
        special_external_gcrequest.spexgc_company,
        special_external_gcrequest.spexgc_type,
        special_external_gcrequest.spexgc_paymentype,
        special_external_gcrequest.spexgc_company,
        special_external_gcrequest.spexgc_payment_arnum,
        special_external_customer.spcus_companyname,
        special_external_customer.spcus_acctname,
        special_external_bank_payment_info.spexgcbi_bankname,
        special_external_bank_payment_info.spexgcbi_bankaccountnum,
        special_external_bank_payment_info.spexgcbi_checknumber';
    $where = "special_external_gcrequest.spexgc_id='".$reqid."'
        AND
            special_external_gcrequest.spexgc_status='pending'";
    $join = 'INNER JOIN
            special_external_customer
        ON
            special_external_customer.spcus_id = special_external_gcrequest.spexgc_company
        LEFT JOIN
            special_external_bank_payment_info
        ON
            special_external_bank_payment_info.spexgcbi_trid = special_external_gcrequest.spexgc_id';
    $limit = '';

    $data = getSelectedData($link,$table,$select,$where,$join,$limit);

    if(!count($data) > 0)
    {
        echo 'Page not found.';
        exit();
    }

    if($data->spexgc_type==2):

        $table = "special_external_gcrequest_emp_assign";
        $select = "special_external_gcrequest_emp_assign.spexgcemp_denom,
            special_external_gcrequest_emp_assign.spexgcemp_fname,
            special_external_gcrequest_emp_assign.spexgcemp_lname,
            special_external_gcrequest_emp_assign.spexgcemp_mname,
            special_external_gcrequest_emp_assign.spexgcemp_extname ";
        $where = "special_external_gcrequest_emp_assign.spexgcemp_trid='".$reqid."'";
        $join = "";
        $limit = "";
        $emps = getAllData($link,$table,$select,$where,$join,$limit);

        foreach ($emps as $emp) {
            if(isset($_SESSION['empAssign']))
            {
                $_SESSION['empAssign'][] = array("lastname"=>$emp->spexgcemp_lname,"firstname"=>$emp->spexgcemp_fname,"middlename"=>$emp->spexgcemp_mname,"extname"=>$emp->spexgcemp_extname,"denom"=>$emp->spexgcemp_denom);
            }
            else 
            {           
                $_SESSION['empAssign'][] = array("lastname"=>$emp->spexgcemp_lname,"firstname"=>$emp->spexgcemp_fname,"middlename"=>$emp->spexgcemp_mname,"extname"=>$emp->spexgcemp_extname,"denom"=>$emp->spexgcemp_denom);
            }
        }

        ?>
            <div class="row form-container">
                <div class="col-md-12">
                    <div class="panel with-nav-tabs panel-info">
                        <div class="panel-heading">
                            <ul class="nav nav-tabs">
                                <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Update Special External Request</a></li>
                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="tab1default">
                                    <form action="../ajax.php?action=specialExternalGCRequestUpdate" method="POST" id="specialExternalGCRequestUpdate" enctype="multipart/form-data">
                                        <div class="row">
                                            <input type="hidden" name="reqid" id="reqid" value="<?php echo $data->spexgc_id; ?>">
                                            <input type="hidden" name="reqtype" id="reqtype" value="<?php echo $data->spexgc_type; ?>">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label class="nobot">GC Request #</label>   
                                                    <input type="text" class="form form-control inptxt input-sm bot-6" name="reqnum" readonly="readonly" value="<?php echo $data->spexgc_num; ?>">  
                                                </div>
                                                <div class="form-group">
                                                    <label class="nobot">Date Requested</label>   
                                                    <input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo _dateFormat($data->spexgc_datereq); ?>">  
                                                </div>
                                                <div class="form-group">
                                                    <label class="nobot"><span class="requiredf">*</span>Date Needed:</label>
                                                    <input type="text" class="form form-control inptxt input-sm ro bot-6" id="dp1" data-date-format="MM dd, yyyy" name="date_needed" readonly="readonly" value="<?php echo _dateFormat($data->spexgc_dateneed); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="nobot">Document(s) Uploaded</label> 
                                                    <?php 
                                                        $table = 'documents';
                                                        $select = 'doc_fullpath';
                                                        $where = "doc_trid='".$reqid."'
                                                            AND
                                                                doc_type='Special External GC Request'";
                                                        $join = '';
                                                        $limit = '';
                                                        $docs = getAllData($link,$table,$select,$where,$join,$limit);
                                                        
                                                    ?>

                                                    <table class="table docstable" id="lightgallery">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>Remove</th>
                                                            </tr>
                                                        </thead>
                                                        <?php foreach ($docs as $d): ?>
                                                        <tr >
                                                            <td class="selector"  data-src="../assets/images/<?php echo $d->doc_fullpath;?>">
                                                                <a href="" class="thumbnail">
                                                                <img class="img-responsive img-table-display" src="../assets/images/<?php echo $d->doc_fullpath; ?>">
                                                                </a>
                                                            </td>
                                                            <td class="padleft">
                                                                <input type="checkbox" name="images[]" value="<?php echo $d->doc_fullpath; ?>">
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    </table>
                                                </div>
                                                <div class="form-group">
                                                  <label class="nobot">Upload Document</label> 
                                                  <input id="input-file" class="file" type="file" name="docs[]" multiple>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <input type="hidden" name="companyid" id="companyid" value="<?php echo $data->spexgc_company; ?>">
                                                    <label class="nobot"><span class="requiredf">*</span>Company Name</label>   
                                                    <textarea class="form form-control input-sm inptxt" readonly="readonly" name="compname" id="compname"><?php echo ucwords($data->spcus_companyname); ?></textarea>
                                                </div>  
                                                <div class="form-group">
                                                    <label class="nobot"><span class="requiredf">*</span>Account Name</label>   
                                                    <input type="text" class="form form-control inptxt" readonly="readonly" name="accname" id="accname" value="<?php echo ucwords($data->spcus_acctname); ?>">
                                                </div>  
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-default" onclick="lookupCustomerExternal();"><i class="fa fa-search-plus" aria-hidden="true"></i>
                                                    Lookup Customer</button>
                                                </div>
												<div class="form-group">
												    <label class="nobot"><span class="requiredf">*</span>AR Number</label>   
												    <input type="text" class="form form-control inptxt" name="arnumber" id="arnumber" value="<?php echo $data->spexgc_payment_arnum; ?>">
												</div>                                                
                                                <div class="form-group">
                                                    <label class="nobot"><span class="requiredf">*</span>Payment Type</label>   
                                                    <select class="form form-control input-sm inptxt" name="paymenttype" id="paymenttype" required>
                                                        <?php if($data->spexgc_paymentype==1):?>
                                                            <option value="1">Cash</option>
                                                            <option value="2">Check</option>
                                                        <?php else: ?>
                                                            <option value="2">Check</option>
                                                            <option value="1">Cash</option>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                                
                                                <div class="checkPayment" <?php echo $data->spexgc_paymentype==1 ? 'style="display: none;"' : ''; ?>>
                                                    <div class="form-group">
                                                        <label class="nobot"><span class="requiredf">*</span>Bank Name</label>
                                                        <input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo $data->spexgcbi_bankname; ?>" name="bankname" id="bankname">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="nobot"><span class="requiredf">*</span>Check Number</label>
                                                        <input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo $data->spexgcbi_checknumber; ?>" name="cnumber" id="cnumber">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="nobot"><span class="requiredf">*</span><span class="cashcheck"></span> Amount</label>
                                                    <input type="text" class="form form-control inptxt input-sm bot-6 amount-external" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" name="amount" value="<?php echo $data->spexgc_payment; ?>" id="amount" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="nobot"><span class="requiredf">*</span>Amount in words</label>
                                                    <textarea class="form form-control input-sm inptxt amtinwords" id="amtinwords" readonly="readonly"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-sm-5">
                                                <div class="form-group">
                                                    <label class="nobot"><span class="requiredf">*</span>Remarks</label> 
                                                    <input type="text" class="form-control inptxt input-sm" name="remarks" value="<?php echo $data->spexgc_remarks; ?>" autocomplete="off" required>
                                                </div>
                                                <div class="form-horizontal">
                                                    <div class="form-group">
                                                        <label class="col-sm-6"><span class="requiredf">*</span>Denomination</label>
                                                        <label class="col-sm-6"><span class="requiredf">*</span>Qty</label>
                                                    </div>
                                                    <div class="optionBox">
                                                        <?php
                                                            //SELECT spexgcemp_denom, COUNT(spexgcemp_id) as cnt FROM special_external_gcrequest_emp_assign WHERE spexgcemp_trid='1' GROUP BY spexgcemp_denom
                                                            $table = 'special_external_gcrequest_emp_assign';
                                                            $where = "spexgcemp_trid='".$reqid."' GROUP BY spexgcemp_denom";
                                                            $select ='spexgcemp_denom, COUNT(spexgcemp_id) as cnt';
                                                            $join = '';
                                                            $limit = '';
                                                            $denoms = getAllData($link,$table,$select,$where,$join,$limit);
                                                            $cnt = 1;
                                                            $total = 0;                                                        
                                                            foreach ($denoms as $den):
                                                                $stotal = 0;
                                                                $stotal = $den->spexgcemp_denom * $den->cnt;
                                                                $total+=$stotal;

                                                        ?>                                        
                                                                <div class="form-group">
                                                                    <div class="col-sm-4">
                                                                        <input class="form form-control inptxt input-sm reqfield denfield ninternalcusd<?php echo $cnt; ?>" name="ninternalcusd[]" id="ninternalcusd" value="<?php echo number_format($den->spexgcemp_denom,2); ?>" placeholder="0" autocomplete="off" readonly="readonly" style="text-align: right;" />
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <input class="form form-control inptxt input-sm reqfield ninternalcusq<?php echo $cnt; ?>" name="ninternalcusq[]" data-num="<?php echo $cnt; ?>" value="<?php echo $den->cnt; ?>" id="ninternalcusq" value="0" placeholder="0" autocomplete="off" readonly="readonly" style="text-align: right;" />
                                                                    </div>
                                                                    <div class="col-sm-4" style="padding-left:0px;">
                                                                        <i class="fa fa-user add-employee" aria-hidden="true" id="addEmployee"></i>
                                                                        <i class="fa fa-minus-square minus-denom removed" aria-hidden="true" style="margin-left: 12px;"></i>
                                                                    </div>
                                                                </div>
                                                        <?php
                                                            $cnt++; 
                                                            endforeach; 
                                                        ?>
                                                        <button class="btn btn-default" type="button" id="addenombut"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                                                    Add Denomination</button>
                                                    </div>
                                                </div>
                                                <div class="labelinternaltot">
                                                    <input type="hidden" name="totolrequestinternal" id="totolrequestinternal" value="0">                        
                                                    <label>Total: <span id="internaltot"><?php echo number_format($total,2); ?></span></label>
                                                </div>
                                                <div class="form-group">
                                                    <label class="nobot">Updated By:</label> 
                                                    <input type="text" readonly="readonly" class="form-control inptxt input-sm" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>"> 
                                                </div>
                                                <div class="response">
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-sm-offset-5 col-sm-7">
                                                        <button type="submit" class="btn btn-block btn-primary" id="externalbtn"> <span class="glyphicon glyphicon-share" aria-hidden="true"></span> Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <script src="../assets/js/funct.js"></script>

    <?php else: ?>

        <div class="row form-container">
            <div class="col-md-12">
                <div class="panel with-nav-tabs panel-info">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Update Special External Request</a></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab1default">
                                <form action="../ajax.php?action=specialExternalGCRequestUpdate" method="POST" id="specialExternalGCRequestUpdate" enctype="multipart/form-data">
                                    <div class="row">
                                        <input type="hidden" name="reqid" id="reqid" value="<?php echo $data->spexgc_id; ?>">
                                        <input type="hidden" name="reqtype" id="reqtype" value="<?php echo $data->spexgc_type; ?>">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="nobot">GC Request #</label>   
                                                <input type="text" class="form form-control inptxt input-sm bot-6" name="reqnum" readonly="readonly" value="<?php echo $data->spexgc_num; ?>">  
                                            </div>
                                            <div class="form-group">
                                                <label class="nobot">Date Requested</label>   
                                                <input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo _dateFormat($data->spexgc_datereq); ?>">  
                                            </div>
                                            <div class="form-group">
                                                <label class="nobot"><span class="requiredf">*</span>Date Needed:</label>
                                                <input type="text" class="form form-control inptxt input-sm ro bot-6" id="dp1" data-date-format="MM dd, yyyy" name="date_needed" readonly="readonly" value="<?php echo _dateFormat($data->spexgc_dateneed); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="nobot">Document(s) Uploaded</label> 
                                                <?php 
                                                    $table = 'documents';
                                                    $select = 'doc_fullpath';
                                                    $where = "doc_trid='".$reqid."'
                                                        AND
                                                            doc_type='Special External GC Request'";
                                                    $join = '';
                                                    $limit = '';
                                                    $docs = getAllData($link,$table,$select,$where,$join,$limit);
                                                    
                                                ?>

                                                <table class="table docstable" id="lightgallery">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>Remove</th>
                                                        </tr>
                                                    </thead>
                                                    <?php foreach ($docs as $d): ?>
                                                    <tr >
                                                        <td class="selector"  data-src="../assets/images/<?php echo $d->doc_fullpath;?>">
                                                            <a href="" class="thumbnail">
                                                            <img class="img-responsive img-table-display" src="../assets/images/<?php echo $d->doc_fullpath; ?>">
                                                            </a>
                                                        </td>
                                                        <td class="padleft">
                                                            <input type="checkbox" name="images[]" value="<?php echo $d->doc_fullpath; ?>">
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </table>
                                            </div>
                                            <div class="form-group">
                                              <label class="nobot">Upload Document</label> 
                                              <input id="input-file" class="file" type="file" name="docs[]" multiple>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <input type="hidden" name="companyid" id="companyid" value="<?php echo $data->spexgc_company; ?>">
                                                <label class="nobot"><span class="requiredf">*</span>Company Name</label>   
                                                <textarea class="form form-control input-sm inptxt" readonly="readonly" name="compname" id="compname"><?php echo ucwords($data->spcus_companyname); ?></textarea>
                                            </div>  
                                            <div class="form-group">
                                                <label class="nobot"><span class="requiredf">*</span>Account Name</label>   
                                                <input type="text" class="form form-control inptxt" readonly="readonly" name="accname" id="accname" value="<?php echo ucwords($data->spcus_acctname); ?>">
                                            </div>  
                                            <div class="form-group">
                                                <button type="button" class="btn btn-default" onclick="lookupCustomerExternal();"><i class="fa fa-search-plus" aria-hidden="true"></i>
                                                Lookup Customer</button>
                                            </div>
                                            <div class="form-group">
                                                <label class="nobot"><span class="requiredf">*</span>Payment Type</label>   
                                                <select class="form form-control input-sm inptxt" name="paymenttype" id="paymenttype" required>
                                                    <?php if($data->spexgc_paymentype==1):?>
                                                        <option value="1">Cash</option>
                                                        <option value="2">Check</option>
                                                    <?php else: ?>
                                                        <option value="2">Check</option>
                                                        <option value="1">Cash</option>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                            
                                            <div class="checkPayment" <?php echo $data->spexgc_paymentype==1 ? 'style="display: none;"' : ''; ?>>
                                                <div class="form-group">
                                                    <label class="nobot"><span class="requiredf">*</span>Bank Name</label>
                                                    <input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo $data->spexgcbi_bankname; ?>" name="bankname" id="bankname">
                                                </div>
                                                <div class="form-group">
                                                    <label class="nobot"><span class="requiredf">*</span>Bank Account Number</label>
                                                    <input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo $data->spexgcbi_bankaccountnum; ?>" name="baccountnum" id="baccountnum">
                                                </div>
                                                <div class="form-group">
                                                    <label class="nobot"><span class="requiredf">*</span>Check Number</label>
                                                    <input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo $data->spexgcbi_checknumber; ?>" name="cnumber" id="cnumber">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="nobot"><span class="requiredf">*</span><span class="cashcheck"></span> Amount</label>
                                                <input type="text" class="form form-control inptxt input-sm bot-6 amount-external" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" name="amount" value="<?php echo $data->spexgc_payment; ?>" id="amount" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="nobot"><span class="requiredf">*</span>Amount in words</label>
                                                <textarea class="form form-control input-sm inptxt amtinwords" id="amtinwords" readonly="readonly"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label class="nobot"><span class="requiredf">*</span>Remarks</label> 
                                                <input type="text" class="form-control inptxt input-sm" name="remarks" value="<?php echo $data->spexgc_remarks; ?>" autocomplete="off" required>
                                            </div>
                                            <div class="form-horizontal">
                                                <div class="form-group">
                                                    <label class="col-sm-6"><span class="requiredf">*</span>Denomination</label>
                                                    <label class="col-sm-6"><span class="requiredf">*</span>Qty</label>
                                                </div>
                                                <div class="optionBox">
                                                    <?php
                                                        //SELECT spexgcemp_denom, COUNT(spexgcemp_id) as cnt FROM special_external_gcrequest_emp_assign WHERE spexgcemp_trid='1' GROUP BY spexgcemp_denom
                                                        $table = 'special_external_gcrequest_items';
                                                        $where = "special_external_gcrequest_items.specit_trid='".$reqid."'";
                                                        $select ='special_external_gcrequest_items.specit_qty,
                                                                special_external_gcrequest_items.specit_denoms';
                                                        $join = '';
                                                        $limit = '';
                                                        $denoms = getAllData($link,$table,$select,$where,$join,$limit);
                                                        $cnt = 1;
                                                        $total = 0;                                                        
                                                        foreach ($denoms as $den):
                                                            $stotal = 0;
                                                            $stotal = $den->specit_denoms * $den->specit_qty;
                                                            $total+=$stotal;
                                                    ?>
                                                            <div class="form-group">
                                                                <div class="col-sm-4">
                                                                    <input class="form form-control inptxt input-sm reqfield denfield ninternalcusd" name="ninternalcusd[]" id="ninternalcusd" value="<?php echo number_format($den->specit_denoms,2); ?>" placeholder="0" autocomplete="off" autofocus readonly="readonly" />
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <input class="form form-control inptxt input-sm reqfield ninternalcusq" name="ninternalcusq[]" data-num="" id="ninternalcusq" value="<?php echo $den->specit_qty; ?>" placeholder="0" autocomplete="off" autofocus data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" />
                                                                </div>
                                                                <div class="col-sm-4" style="padding-left:0px;">
                                                                    <i class="fa fa-minus-square minus-denom removed" aria-hidden="true"></i>
                                                                </div>
                                                            </div>
                                                    <?php 
                                                        endforeach;
                                                    ?>                      
                                                    <button class="btn btn-default" type="button" id="addenombutnew"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                                                Add Denomination</button>
                                                </div>
                                            </div>
                                            <div class="labelinternaltot">
                                                <input type="hidden" name="totolrequestinternal" id="totolrequestinternal" value="0">                        
                                                <label>Total: <span id="internaltot"><?php echo number_format($total,2); ?></span></label>
                                            </div>
                                            <div class="form-group">
                                                <label class="nobot">Updated By:</label> 
                                                <input type="text" readonly="readonly" class="form-control inptxt input-sm" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>"> 
                                            </div>
                                            <div class="response">
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-offset-5 col-sm-7">
                                                    <button type="submit" class="btn btn-block btn-primary" id="externalbtn"> <span class="glyphicon glyphicon-share" aria-hidden="true"></span> Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php endif; ?>

        <script type="text/javascript">
            $('#lightgallery').lightGallery({
                selector:'.selector'
            });

            $('.denfield').inputmask();

            $('input#input-file').fileinput({
              'allowedFileExtensions' : ['jpg','png','jpeg']
            });
            var nowTemp = new Date();
            var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

            var checkin = $('#dp1').datepicker({

                beforeShowDay: function (date) {
                    return date.valueOf() >= now.valueOf();
                },
                autoclose: true
            });

            $('#paymenttype').change(function(){
                var type = $(this).val();
                if(type=='')
                {
                    $('.paymenttypediv').hide();
                }
                else if(type=='1')
                {
                    $('.paymenttypediv').fadeIn(500).show(600);
                    $('.checkPayment').hide();
                    $('.cashcheck').text('Cash');
                    $('#bankname').prop('required',false);
                    $('#baccountnum').prop('required',false);
                    $('#cnumber').prop('required',false);
                }
                else if(type=='2')
                {
                    $('.paymenttypediv').fadeIn(500).show(600);
                    $('.checkPayment').fadeIn(500).show(600);
                    $('.cashcheck').text('Check');
                    $('#bankname').prop('required',true);
                    $('#baccountnum').prop('required',true);
                    $('#cnumber').prop('required',true);
                }
            });

            $('.amount-external, #ninternalcusd, #ninternalcusq').inputmask();
            $('.amtinwords').val(toWords($('.amount-external').val()));

            $('.amount-external').keyup(function(){
                var inputs = $(this).val();
                inputs = inputs.replace(/,/g , "");
                $('.amtinwords').val(toWords(inputs));
            });

            var limit = 10;
            var dencnt = $('.denfield').length;

            $('button#addenombutnew').click(function(){
                BootstrapDialog.show({
                    title: 'Add Denomination',
                    cssClass: 'store-staff-dialog',
                    closable: true,
                    closeByBackdrop: false,
                    closeByKeyboard: true,
                    message: function(dialog) {
                        var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
                        var pageToLoad = dialog.getData('pageToLoad');
                        setTimeout(function(){
                        $message.load(pageToLoad);
                        },1000);
                        return $message;
                    },
                    data: {
                        'pageToLoad': '../dialogs/extenalgc.php?action=createdenom'
                    },
                    onshow: function(dialog) {
                        // dialog.getButton('button-c').disable();
                    },
                    onshown: function(dialog){
                        setTimeout(function(){
                            $('#denocr').select();
                        },1200);
                    }, 
                    onhidden: function()
                    {               
                    },
                    buttons: [{
                        icon: 'glyphicon glyphicon-ok-sign',
                        label: 'Submit',
                        cssClass: 'btn-primary',
                        hotkey: 13,
                        action:function(dialogItself){
                            var duplicate = false;
                            $('#denocr').focus();
                            $('.responsecr').html('');
                            if($('#denocr').val() == undefined)
                            {
                                return false;
                            }

                            if($('#denocr').val()==0 || $('#denocr').val().trim()=='')
                            {
                                $('.responsecr').html('<div class="alert alert-danger" id="danger-x">Please input valid denomination.</div');
                                return false;
                            }

                            var den = $('#denocr').val();

                            if($('.optionBox input.denfield').length > 0)
                            {
                                $('.denfield').each(function(){
                                    if($(this).val()==den)
                                    {
                                        duplicate = true;
                                        return false;
                                    }
                                });
                            }

                            if(duplicate)
                            {
                                $('.responsecr').html('<div class="alert alert-danger" id="danger-x">Denomination already exist.</div');
                                return false;
                            }

                            if(dencnt <= limit)
                            {           
                                $('button#addenombutnew').before('<div class="form-group">'+
                                '<div class="col-sm-4">'+
                                  '<input class="form form-control inptxt input-sm reqfield denfield ninternalcusd'+dencnt+'" name="ninternalcusd[]" id="ninternalcusd" value="'+den+'" placeholder="0" autocomplete="off" autofocus readonly="readonly" />'+
                                '</div>'+
                                '<div class="col-sm-4">'+
                                  '<input class="form form-control inptxt input-sm reqfield ninternalcusq'+dencnt+'" name="ninternalcusq[]" data-num="'+dencnt+'" id="ninternalcusq" value="0" placeholder="0" autocomplete="off" autofocus />'+
                                '</div>'+
                                '<div class="col-sm-4" style="padding-left:0px;">'+
                                    '<i class="fa fa-minus-square minus-denom removed" aria-hidden="true"></i>'+
                                '</div>'+
                              '</div>');

                                dencnt++;

                                $('#ninternalcusd,#ninternalcusq').inputmask("integer", { allowMinus: false,autoGroup: true, groupSeparator: ",", groupSize: 3,digits: 2 });
                                dialogItself.close();
                            }   
                            else 
                            {
                                $('.responsecr').html('<div class="alert alert-danger" id="danger-x">Something went wrong.</div');
                                return false;
                            }

                        }
                    }]

                });

                return false;
            });

            $('button#addenombut').click(function(){
            	var numden = $('.optionBox #ninternalcusd').length;
            	var large = 0;
            	$('.optionBox #ninternalcusq').each(function(){
	            	datan = $(this).attr('data-num');
            		if(datan > large)
            		{
            			large = datan;
            		}
            	});
            	large++;
                BootstrapDialog.show({
                    title: 'Add Denomination',
                    cssClass: 'store-staff-dialog',
                    closable: true,
                    closeByBackdrop: false,
                    closeByKeyboard: true,
                    message: function(dialog) {
                        var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
                        var pageToLoad = dialog.getData('pageToLoad');
                        setTimeout(function(){
                        $message.load(pageToLoad);
                        },1000);
                        return $message;
                    },
                    data: {
                        'pageToLoad': '../dialogs/extenalgc.php?action=createdenom'
                    },
                    onshow: function(dialog) {
                        // dialog.getButton('button-c').disable();
                    },
                    onshown: function(dialog){
                        setTimeout(function(){
                            $('#denocr').focus();
                        },1200);
                    }, 
                    onhidden: function()
                    {               
                    },
                    buttons: [{
                        icon: 'glyphicon glyphicon-ok-sign',
                        label: 'Submit',
                        cssClass: 'btn-primary',
                        hotkey: 13,
                        action:function(dialogItself){
                            var duplicate = false;
                            $('#denocr').focus();
                            $('.responsecr').html('');
                            if($('#denocr').val() == undefined)
                            {
                                return false;
                            }

                            if($('#denocr').val()==0 || $('#denocr').val().trim()=='')
                            {
                                $('.responsecr').html('<div class="alert alert-danger" id="danger-x">Please input valid denomination.</div');
                                return false;
                            }

                            var den = $('#denocr').val();

                            if($('.optionBox input.denfield').length > 0)
                            {
                                $('.denfield').each(function(){
                                    if($(this).val()==den)
                                    {
                                        duplicate = true;
                                        return false;
                                    }
                                });
                            }

                            if(duplicate)
                            {
                                $('.responsecr').html('<div class="alert alert-danger" id="danger-x">Denomination already exist.</div');
                                return false;
                            }

                            if(dencnt <= limit)
                            {           
                                 $('button#addenombut').before('<div class="form-group">'+
                                '<div class="col-sm-4">'+
                                  '<input class="form form-control inptxt input-sm reqfield denfield ninternalcusd'+large+'" name="ninternalcusd[]" id="ninternalcusd" value="'+den+'" placeholder="0" autocomplete="off" autofocus readonly="readonly" />'+
                                '</div>'+
                                '<div class="col-sm-4">'+
                                  '<input class="form form-control inptxt input-sm reqfield ninternalcusq'+large+'" name="ninternalcusq[]" data-num="'+large+'" id="ninternalcusq" value="0" placeholder="0" autocomplete="off" autofocus readonly="readonly" />'+
                                '</div>'+
                                '<div class="col-sm-4" style="padding-left:0px;">'+
                                    '<i class="fa fa-user add-employee" aria-hidden="true" id="addEmployee"></i>'+
                                    '<i class="fa fa-minus-square minus-denom removed" aria-hidden="true"></i>'+
                                '</div>'+
                              '</div>');

                                dencnt++;

                                $('#ninternalcusd,#ninternalcusq').inputmask("integer", { allowMinus: false,autoGroup: true, groupSeparator: ",", groupSize: 3,digits: 2 });
                                dialogItself.close();
                            }   
                            else 
                            {
                                $('.responsecr').html('<div class="alert alert-danger" id="danger-x">Something went wrong.</div');
                                return false;
                            }

                        }
                    }]

                });

                return false;

            });

			$(document).on('click','.removed',function() {
				var den = $(this).parent().parent().find('.reqfield').val();
				var thisdiv = $(this);
				var r = confirm("Remove Denomination?");
				if (r == true) {
					$.ajax({
						url:'../ajax.php?action=deleteSessionKeyByDen',
						data:{den:den},
						type:'POST',
						success:function(data)
						{
							console.log(data);
							var data = JSON.parse(data);	
						 	thisdiv.parent('div').parent('div').remove();
						 	dencnt--;
						 	scanInternalInput();
						}
					})

				}
			});

            $('.optionBox').on('click','#addEmployee',function(){
                var den = $(this).parent().parent().find('.reqfield').val();
                var datanum = $(this).parent().parent().find('#ninternalcusq').attr('data-num');
                if(den.trim()==0)
                {
                    alert('Please input denomination.');
                    return false;
                }
                BootstrapDialog.show({
                    title: 'Assign Customer Employee',
                    cssClass: 'modal-details-strel',
                    closable: true,
                    closeByBackdrop: false,
                    closeByKeyboard: true,
                    message: function(dialog) {
                        var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
                        var pageToLoad = dialog.getData('pageToLoad');
                        setTimeout(function(){
                        $message.load(pageToLoad);
                        },1000);
                        return $message;
                    },
                    data: {
                        'pageToLoad': '../dialogs/extenalgc.php?action=assignemp&den='+den+'&datanum='+datanum
                    },
                    onshow: function(dialog) {
                        // dialog.getButton('button-c').disable();
                    },
                    onshown: function(dialogRef){
                        setTimeout(function(){
                            $('input[name=lastname]').focus();   
                        },1200);
                        
                    }, 
                    onhidden: function()
                    {                       
                    },
                    buttons: [{
                        icon: 'glyphicon glyphicon-ok-sign',
                        label: 'Close',
                        cssClass: 'btn-primary',
                        action:function(dialogItself){
                            dialogItself.close();
                        }
                    }]

                });

            });

            $('.form-container').on('submit','#specialExternalGCRequestUpdate',function(event){
                event.preventDefault();
                $('.response').html('');
                var formURL = $(this).attr('action'), formData = new FormData($(this)[0]);  

                if($('#reqid').val().trim()=='')
                {
                    $('.response').html('<div class="alert alert-danger" id="danger-x">Invalid Request ID.</div>');
                    return false;
                }

                if($('#reqnum').val()=='')
                {
                    $('.response').html('<div class="alert alert-danger" id="danger-x">GC Request # is empty.</div>');
                    return false;
                }

                if($('#dp1').val().trim()=='')
                {
                    $('.response').html('<div class="alert alert-danger" id="danger-x">Please select date needed.</div>');
                    return false;
                }

                var d = $('#dp1').val();

                if((d.match(/,/g) || []).length > 1)
                {
                     $('.response').html('<div class="alert alert-danger" id="danger-x">Invalid date needed.</div>');
                     return false;
                }


                if($('#companyid').val().trim()=='')
                {

                    $('.response').html('<div class="alert alert-danger" id="danger-x">Please select customer.</div>');
                    return false;
                }

                if($('#amount').val().trim()=='0.00' || $('#amount').val().trim()=='')
                {   
                    $('.response').html('<div class="alert alert-danger" id="danger-x">Please input valid amount.</div>');
                    return false;           
                }

                var amount = $('#amount').val().trim();
                amount = amount.replace(/,/g , "");
                amount = isNaN(amount) ? 0 : amount;

                if(amount > 0)
                {
                    
                }
                else 
                {
                    $('.response').html('<div class="alert alert-danger" id="danger-x">Please input valid amount.</div>');
                    return false;
                }

                if($('div.optionBox input#ninternalcusd').length == 0)
                {
                    $('.response').html('<div class="alert alert-danger" id="danger-x">Please add denomination.</div>');
                    return false;               
                }
                var denomZero = false;
                var denomDup = false;
                var denomArr = [];
                $('div.optionBox input#ninternalcusd').each(function(){
                    if($(this).val().trim()==0)
                    {
                        denomZero = true;
                    }

                    if($.inArray($(this).val(), denomArr) !== -1)
                    {
                        denomDup = true;
                    }

                    denomArr.push($(this).val());
                });

                if(denomZero)
                {
                    $('.response').html('<div class="alert alert-danger" id="danger-x">Please input valid denomination value.</div>');
                    return false;
                }

                if(denomDup)
                {
                    $('.response').html('<div class="alert alert-danger" id="danger-x">Duplicate denomination.</div>');
                    return false;
                }

                var qtyZero = false;

                $('div.optionBox #ninternalcusq').each(function(){
                    if($(this).val().trim()==0)
                    {
                        qtyZero = true;
                    }
                });

                if(qtyZero)
                {

                    if($('#reqtype').val==2)
                    {
                        $('.response').html('<div class="alert alert-danger" id="danger-x">Please assign customer employee.</div>');
                    }
                    else 
                    {
                        $('.response').html('<div class="alert alert-danger" id="danger-x">Please input denomination quantity.</div>');
                    }
                    
                    return false;
                }

                BootstrapDialog.show({
                    title: 'Confirmation',
                    message: 'Are you sure you want to update GC Request?',
                    closable: true,
                    closeByBackdrop: false,
                    closeByKeyboard: true,
                    onshow: function(dialog) {
                        $("button#externalbtn").prop("disabled",true);
                    },
                    onhidden: function(dialog){
                        $("button#externalbtn").prop("disabled",false);
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
                                    $('#processing-modal').modal('show');
                                },
                                success:function(data)
                                {
                                    $('#processing-modal').modal('hide');
                                    console.log(data);
                                    var data = JSON.parse(data);
                                    if(data['st'])
                                    {
                                        var dialog = new BootstrapDialog({
                                        message: function(dialogRef){
                                        var $message = $('<div>Request Successfully Updated.</div>');                 
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
                                            window.location = 'index.php';
                                        }, 1700);   
                                    }
                                    else 
                                    {
                                        $('.response').html('<div class="alert alert-danger" id="danger-x">'+data['msg']+'</div>');
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

        </script>
        <script type="text/javascript" src="../assets/js/funct.js"></script>

    <?php
}

function specialExternalRequestList($link)
{
    $table = 'special_external_gcrequest';
    $select = " special_external_gcrequest.spexgc_num,
        special_external_gcrequest.spexgc_dateneed,
        special_external_gcrequest.spexgc_id,
        special_external_gcrequest.spexgc_datereq,
        CONCAT(users.firstname,' ',users.lastname) as prep,
        special_external_customer.spcus_companyname";
    $where = "special_external_gcrequest.spexgc_status='pending'";
    $join = 'INNER JOIN
            users
        ON
            users.user_id = special_external_gcrequest.spexgc_reqby
        INNER JOIN
            special_external_customer
        ON
            special_external_customer.spcus_id = special_external_gcrequest.spexgc_company';
    $limit = 'ORDER BY special_external_gcrequest.spexgc_id ASC';

    $request = getAllData($link,$table,$select,$where,$join,$limit);

    ?>
        <div class="row form-container">
            <div class="col-md-12">
                <div class="panel with-nav-tabs panel-info">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Pending Special External GC Request</a></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab1default">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table" id="storeRequestList">
                                            <thead>
                                                <tr>
                                                    <th>RFSEGC #</th>
                                                    <th>Date Requested</th>
                                                    <th>Date Needed</th>
                                                    <th>Total Denomination</th>
                                                    <th>Customer</th>
                                                    <th>Requested By</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($request as $r): ?>
                                                <tr onclick="window.document.location='#/special-external-request/<?php echo $r->spexgc_id;  ?>'">
                                                    <td><?php echo $r->spexgc_num; ?></td>
                                                    <td><?php echo _dateFormat($r->spexgc_datereq); ?></td>
                                                    <td><?php echo _dateFormat($r->spexgc_dateneed); ?></td>
                                                    <td><?php echo number_format(totalExternalRequestTresDept($link,$r->spexgc_id)[0],2); ?></td>
                                                    <td><?php echo ucwords($r->spcus_companyname); ?></td>
                                                    <td><?php echo ucwords($r->prep); ?></td>
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
            $('#storeRequestList,#list').dataTable( {
                "pagingType": "full_numbers",
                "ordering": false,
                "processing": true
            });
        </script>

    <?php
}

function displayReviewdGC($link,$id)
{
    $hasError = false;
    if(isset($_SESSION['scanReviewGC']))
        unset($_SESSION['scanReviewGC']);

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

	    <script type="text/javascript">

			$('#lightgallery').lightGallery();

			$.extend( $.fn.dataTableExt.oStdClasses, {	  
			    "sLengthSelect": "selectsup"
			});

		    $('#storeRequestList').dataTable( {
		        "pagingType": "full_numbers",
		        "ordering": false,
		        "processing": true
		    });

			$('.form-container').on('submit','#gcreleased',function(event){
				event.preventDefault();
				$('.response').html('');
				var formURL = $(this).attr('action'), formData = $(this).serialize();
				var receivedby = $('#receiver').val();
				if(receivedby.trim()=='')
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Please input received by textbox.</div>');
					$('#receiver').focus();
					return false;
				}

		        BootstrapDialog.show({
		        	title: 'Confirmation',
		            message: 'Are you sure you want Release GC?',
		            closable: true,
		            closeByBackdrop: false,
		            closeByKeyboard: true,
		            onshow: function(dialog) {
		               	$("button#gcreleasedbut").prop("disabled",true);
		            },
		            onhidden: function(dialog){
		            	$("button#gcreleasedbut").prop("disabled",false);
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

							$.ajax({
					    		url:formURL,
					    		type:'POST',
								data: formData,
								beforeSend:function(){
								},
								success:function(data)
								{ 
									console.log(data);
									var data = JSON.parse(data);
									if(data['st'])
									{
										var dialog = new BootstrapDialog({
							            message: function(dialogRef){
							            var $message = $('<div>Special External GC Successfully Released.</div>');			        
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
					                    	window.location.href = 'special-external-gcreleasedpdf.php?id='+data['trid'];
					               		}, 1700);	
									}
									else 
									{
										$('.response').html('<div class="alert alert-danger" id="danger-x">'+data['msg']+'</div>');
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

	    </script>

	<?php
}


function reviewedGCForReleasing($link)
{
    $table = 'special_external_gcrequest';
    $select = "special_external_gcrequest.spexgc_num,
        special_external_gcrequest.spexgc_dateneed,
        special_external_gcrequest.spexgc_id,
        special_external_gcrequest.spexgc_datereq,
        CONCAT(users.firstname,' ',users.lastname) as prep,
        special_external_customer.spcus_companyname,
        special_external_gcrequest.spexgc_id,
        approved_request.reqap_approvedby";
    $where = "special_external_gcrequest.spexgc_status='approved'
        AND
            special_external_gcrequest.spexgc_reviewed='reviewed'
        AND
            approved_request.reqap_approvedtype='Special External GC Approved'
        AND
            special_external_gcrequest.spexgc_released=''";
    $join = 'INNER JOIN
            users
        ON
            users.user_id = special_external_gcrequest.spexgc_reqby
        INNER JOIN
            special_external_customer
        ON
            special_external_customer.spcus_id = special_external_gcrequest.spexgc_company
        INNER JOIN
            approved_request
        ON
            approved_request.reqap_trid = special_external_gcrequest.spexgc_id';
    $limit = 'ORDER BY special_external_gcrequest.spexgc_id ASC';

    $request = getAllData($link,$table,$select,$where,$join,$limit);

	?>
	    <div class="row form-container">
	        <div class="col-md-12">
	            <div class="panel with-nav-tabs panel-info">
	                <div class="panel-heading">
	                    <ul class="nav nav-tabs">
	                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Reviewed Special External GC</a></li>
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
	                                                <th>RFSEGC #</th>
	                                                <th>Date Requested</th>
	                                                <th>Date Needed</th>
	                                                <th>Total Denom</th>
	                                                <th>Customer</th>
	                                                <th>Requested by</th>
	                                                <th>Approved By</th>
	                                                <th>Reviewed By</th>
	                                            </tr>
	                                        </thead>
	                                        <tbody>
	                                            <?php foreach ($request as $r): ?>
	                                                <tr onclick="window.location='#/reviewed-gc-for-releasing/<?php echo $r->spexgc_id; ?>'">
	                                                    <td><?php echo $r->spexgc_num; ?></td>
	                                                    <td><?php echo _dateFormat($r->spexgc_datereq); ?></td>
	                                                    <td><?php echo _dateFormat($r->spexgc_dateneed); ?></td>
	                                                    <td><?php echo number_format(totalExternalRequest($link,$r->spexgc_id)[0],2); ?></td>
	                                                    <td><?php echo ucwords($r->spcus_companyname); ?></td>
	                                                    <td><?php echo ucwords($r->prep); ?></td>
	                                                    <td><?php echo ucwords($r->reqap_approvedby); ?></td>
	                                                    <td>
	                                                        <?php 
	                                                            $table = 'approved_request';
	                                                            $select ="CONCAT(users.firstname,' ',users.lastname) as reviewee";
	                                                            $where = '';
	                                                            $join = 'INNER JOIN
	                                                                    users
	                                                                ON
	                                                                    users.user_id = approved_request.reqap_preparedby';
	                                                            $limit = "approved_request.reqap_trid='".$r->spexgc_id."'
	                                                                AND
	                                                                    approved_request.reqap_approvedtype='special external gc review'";
	                                                            $gc = getSelectedData($link,$table,$select,$where,$join,$limit);
	                                                            echo ucwords($gc->reviewee);
	                                                        ?>
	                                                    </td>
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


		    $('#storeRequestList').dataTable( {
		        "pagingType": "full_numbers",
		        "ordering": false,
		        "processing": true
		    });
	    </script>
	<?php
}

function requestSpecialGC($link,$todays_date)
{
	?>
        <div class="panel with-nav-tabs panel-info">
            <div class="panel-heading">
				<ul class="nav nav-tabs">
					<li class="active" style="font-weight:bold">
						<a href="#tab1default" data-toggle="tab">Special External GC Request</a>
					</li>
				</ul>
            </div>
            <div class="panel-body">
				<div class="tab-content">
	                <div class="tab-pane fade in active" id="tab1default">
	                  	<div class="row form-container">
		                    <form action="../ajax.php?action=specialExternalGCRequestNew" method="POST" id="specialExternalGCRequestNew" enctype="multipart/form-data">                  
		                      	<div class="col-sm-12">
			                        <div class=""> 
			                          	<div class="col-sm-3">
			                            	<div class="form-group">
			                            		<input type="hidden" name="reqtype" value="1">
			                              		<label class="nobot">GC Request #</label>   
			                              		<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo getRequestNoByExternal($link); ?>" name="reqnum" id="reqnum">  
			                            	</div>

			                                <div class="form-group">
												<label class="nobot">Date Requested</label> 
												<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo _dateFormat($todays_date); ?>">                                 
			                                </div>

			                                <div class="form-group">
			                                	<label class="nobot"><span class="requiredf">*</span>Date Needed:</label>
			                                	<input type="text" class="form form-control inptxt input-sm ro bot-6" id="dp1" data-date-format="MM dd, yyyy" name="date_needed" readonly="readonly" required>
			                                </div>

			                                <div class="form-group">
			                                	<label class="nobot">Upload Document</label> 
			                                	<input id="input-file" class="file" type="file" name="docs[]" multiple>
			                                </div>
			                            </div>

										<div class="col-sm-4">
											<div class="form-group">
												<input type="hidden" name="companyid" id="companyid" value="">
												<label class="nobot"><span class="requiredf">*</span>Company Name</label>   
												<textarea class="form form-control input-sm inptxt" readonly="readonly" name="compname" id="compname"></textarea>
											</div>  

											<div class="form-group">
												<label class="nobot"><span class="requiredf">*</span>Account Name</label>   
												<input type="text" class="form form-control inptxt" readonly="readonly" name="accname" id="accname">
											</div>       

											<div class="form-group">
												<button type="button" class="btn btn-default" onclick="lookupCustomerExternal();"><i class="fa fa-search-plus" aria-hidden="true"></i>
												Lookup Customer</button>
											</div>              

											<div class="form-group">
												<label class="nobot"><span class="requiredf">*</span>Payment Type</label>   
												<select class="form form-control input-sm inptxt" name="paymenttype" id="paymenttype" required>
													<option value="">- Select -</option>
													<option value="1">Cash</option>
													<option value="2">Check</option>
												</select>
											</div>
											<div class="paymenttypediv" style="display:none;">
												<div class="checkPayment">
													<div class="form-group">
														<label class="nobot"><span class="requiredf">*</span>Bank Name</label>
														<input type="text" class="form form-control inptxt input-sm bot-6" name="bankname" id="bankname" autocomplete="off">
													</div>
													<div class="form-group">
														<label class="nobot"><span class="requiredf">*</span>Bank Account Number</label>
														<input type="text" class="form form-control inptxt input-sm bot-6 " name="baccountnum" id="baccountnum" autocomplete="off">
													</div>
													<div class="form-group">
														<label class="nobot"><span class="requiredf">*</span>Check Number</label>
														<input type="text" class="form form-control inptxt input-sm bot-6" name="cnumber" id="cnumber" autocomplete="off">
													</div>
												</div>
												<div class="form-group">
													<label class="nobot"><span class="requiredf">*</span><span class="cashcheck"></span> Amount</label>
													<input type="text" class="form form-control inptxt input-sm bot-6 amount-external" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" name="amount" id="amount" required>
												</div>
												<div class="form-group">
													<label class="nobot"><span class="requiredf">*</span>Amount in words</label>
													<textarea class="form form-control input-sm inptxt amtinwords" id="amtinwords" readonly="readonly"></textarea>
												</div>
											</div>
										</div><!-- end of col-sm-4 -->

										<div class="col-sm-5">

											<div class="form-group">
												<label class="nobot"><span class="requiredf">*</span>Remarks</label> 
												<input type="text" class="form-control inptxt input-sm" name="remarks" autocomplete="off" required>
											</div>

											<div class="form-horizontal">
												<div class="form-group">
													<label class="col-sm-6"><span class="requiredf">*</span>Denomination</label>
													<label class="col-sm-6"><span class="requiredf">*</span>Qty</label>
												</div>

												<div class="optionBox">
													<button class="btn btn-default" type="button" id="addenombutnew"><i class="fa fa-plus-circle" aria-hidden="true"></i>
													Add Denomination</button>
													</div>
												</div>

												<!-- end form horizontal -->

												<div class="labelinternaltot">
													<input type="hidden" name="totolrequestinternal" id="totolrequestinternal" value="0">                        
													<label>Total: <span id="internaltot">0.00</span></label>
												</div>
												<div class="form-group">
													<label class="nobot">Prepared By:</label> 
													<input type="text" readonly="readonly" class="form-control inptxt input-sm" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>"> 
												</div>

												<div class="response">
												</div>

												<div class="form-group">
													<div class="col-sm-offset-5 col-sm-7">
													<button type="submit" class="btn btn-block btn-primary" id="externalbtn"> <span class="glyphicon glyphicon-share" aria-hidden="true"></span>Submit</button>
												</div>
											</div>
										</div>
			                        </div>
			                    </div>
			                </form>
	                    </div>
	                </div>
	            </div>
            </div>
        </div>
        <script type="text/javascript">
			var nowTemp = new Date();
			var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

			var checkin = $('#dp1').datepicker({

			    beforeShowDay: function (date) {
			        return date.valueOf() >= now.valueOf();
			    },
			    autoclose: true

			});
			$('#amount').inputmask();
		    $('input#input-file').fileinput({
		      'allowedFileExtensions' : ['jpg','png','jpeg']
		    });

		    var limit = 10;
		    var dencnt = 1;

			$('div.optionBox button#addenombutnew').click(function(){
				BootstrapDialog.show({
			    	title: 'Add Denomination',
		        	cssClass: 'store-staff-dialog',
			        closable: true,
			        closeByBackdrop: false,
			        closeByKeyboard: true,
		            message: function(dialog) {
		                var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
		                var pageToLoad = dialog.getData('pageToLoad');
						setTimeout(function(){
		                $message.load(pageToLoad);
						},1000);
		                return $message;
		            },
		            data: {
		                'pageToLoad': '../dialogs/extenalgc.php?action=createdenom'
		            },
			        onshow: function(dialog) {
			            // dialog.getButton('button-c').disable();
			        },
			        onshown: function(dialog){
			        	setTimeout(function(){
			        		$('#denocr').select();
			        	},1200);
			        }, 
			        onhidden: function()
			        {	        	
			        },
			        buttons: [{
			            icon: 'glyphicon glyphicon-ok-sign',
			            label: 'Submit',
			            cssClass: 'btn-primary',
			            hotkey: 13,
			            action:function(dialogItself){
			            	var duplicate = false;
			            	$('#denocr').focus();
			            	$('.responsecr').html('');
			            	if($('#denocr').val() == undefined)
			            	{
			            		return false;
			            	}

			            	if($('#denocr').val()==0 || $('#denocr').val().trim()=='')
			            	{
			            		$('.responsecr').html('<div class="alert alert-danger" id="danger-x">Please input valid denomination.</div');
			            		return false;
			            	}

			            	var den = $('#denocr').val();

			            	if($('.optionBox input.denfield').length > 0)
							{
								$('.denfield').each(function(){
									if($(this).val()==den)
									{
										duplicate = true;
										return false;
									}
								});
							}

							if(duplicate)
							{
								$('.responsecr').html('<div class="alert alert-danger" id="danger-x">Denomination already exist.</div');
								return false;
							}

							if(dencnt <= limit)
							{			
						 		$('button#addenombutnew').before('<div class="form-group">'+
						        '<div class="col-sm-4">'+
						          '<input class="form form-control inptxt input-sm reqfield denfield ninternalcusd'+dencnt+'" name="ninternalcusd[]" id="ninternalcusd" value="'+den+'" placeholder="0" autocomplete="off" autofocus readonly="readonly" />'+
						        '</div>'+
						        '<div class="col-sm-4">'+
						          '<input class="form form-control inptxt input-sm reqfield ninternalcusq'+dencnt+'" name="ninternalcusq[]" data-num="'+dencnt+'" id="ninternalcusq" value="0" placeholder="0" autocomplete="off" autofocus />'+
						        '</div>'+
						        '<div class="col-sm-4" style="padding-left:0px;">'+
						          	'<i class="fa fa-minus-square minus-denom removed" aria-hidden="true"></i>'+
						        '</div>'+
						      '</div>');

						 		dencnt++;

						 		$('#ninternalcusd,#ninternalcusq').inputmask("integer", { allowMinus: false,autoGroup: true, groupSeparator: ",", groupSize: 3,digits: 2 });
						 		dialogItself.close();
						 	}	
						 	else 
						 	{
			            		$('.responsecr').html('<div class="alert alert-danger" id="danger-x">Something went wrong.</div');
			            		return false;
						 	}

			            }
			        }]

			    });

				return false;

			});

			$('#paymenttype').change(function(){
				$('#amount').val(0.00);
				$('#amtinwords').val('');
				var type = $(this).val();
				if(type=='')
				{
					$('.paymenttypediv').hide();
				}
				else if(type=='1')
				{
					$('.paymenttypediv').fadeIn(500).show(600);
					$('.checkPayment').hide();
					$('.cashcheck').text('Cash');
					$('#bankname').prop('required',false);
					$('#baccountnum').prop('required',false);
					$('#cnumber').prop('required',false);
				}
				else if(type=='2')
				{
					$('.paymenttypediv').fadeIn(500).show(600);
					$('.checkPayment').fadeIn(500).show(600);
					$('.cashcheck').text('Check');
					$('#bankname').prop('required',true);
					$('#baccountnum').prop('required',true);
					$('#cnumber').prop('required',true);
				}
			});

			$('.amount-external').keyup(function(){
				var inputs = $(this).val();
				inputs = inputs.replace(/,/g , "");
				$('.amtinwords').val(toWords(inputs));
			});

		    $(document).on('change','input#ninternalcusd, input#ninternalcusq',function() {
		    	scanInternalInput();
		    });

		    $(document).on('keyup','input#ninternalcusd, input#ninternalcusq',function() {
		    	scanInternalInput();
		    });

			$('.form-container').on('submit','#specialExternalGCRequestNew',function(event){
				event.preventDefault();
				$('.response').html('');
				var formURL = $(this).attr('action'), formData = new FormData($(this)[0]);	

				var files = document.getElementById("input-file").files;
				if(!files.length > 0)
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Only "jpg, png, jpeg" files are supported.</div>');
					return false;
				}	

				if($('#reqnum').val()=='')
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">GC Request # is empty.</div>');
					return false;
				}
				if($('#dp1').val().trim()=='')
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Please select date needed.</div>');
					return false;
				}

				if($('#companyid').val().trim()=='')
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Please select customer.</div>');
					return false;
				}

				if($('#amount').val().trim()=='0.00' || $('#amount').val().trim()=='')
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Please input valid amount.</div>');
					return false;			
				}

				var amount = $('#amount').val().trim();
				amount = amount.replace(/,/g , "");
				amount = isNaN(amount) ? 0 : amount;

				if(amount > 0)
				{
					
				}
				else 
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Please input valid amount.</div>');
					return false;
				}

				if($('div.optionBox input#ninternalcusd').length == 0)
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Please add denomination.</div>');
					return false;				
				}
				var denomZero = false;
				var denomDup = false;
				var denomArr = [];
				$('div.optionBox input#ninternalcusd').each(function(){
					if($(this).val().trim()==0)
					{
						denomZero = true;
					}

					if($.inArray($(this).val(), denomArr) !== -1)
					{
						denomDup = true;
					}

					denomArr.push($(this).val());

				});

				if(denomZero)
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Please input valid denomination value.</div>');
					return false;
				}

				if(denomDup)
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Duplicate denomination.</div>');
					return false;
				}

				var qtyZero = false;

				$('div.optionBox #ninternalcusq').each(function(){
					if($(this).val().trim()==0)
					{
						qtyZero = true;
					}
				});

				if(qtyZero)
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Please input quanity.</div>');
					return false;
				}

		        BootstrapDialog.show({
		        	title: 'Confirmation',
		            message: 'Are you sure you want to submit GC Transfer Request?',
		            closable: true,
		            closeByBackdrop: false,
		            closeByKeyboard: true,
		            onshow: function(dialog) {
		               	$("button#externalbtn").prop("disabled",true);
		            },
		            onhidden: function(dialog){
		            	$("button#externalbtn").prop("disabled",false);
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
									$('#processing-modal').modal('show');
								},
								success:function(data)
								{
									$('#processing-modal').modal('hide');
									console.log(data);
									var data = JSON.parse(data);
									if(data['st'])
									{
										var dialog = new BootstrapDialog({
							            message: function(dialogRef){
							            var $message = $('<div>Request Successfully Saved.</div>');			        
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
					                    	window.location = 'index.php';
					               		}, 1700);	
									}
									else 
									{
										$('.response').html('<div class="alert alert-danger" id="danger-x">'+data['msg']+'</div>');
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

        </script>
	<?php
}

function pendingSpecialExtertenalRequest($link,$todays_date)
{
	?>
	

	<?php
}

function cancelledSpecialExternalRequest($link)
{
	?>
		<div class="row form-container">
	    	<div class="col-md-12">
	            <div class="panel with-nav-tabs panel-info">
	                <div class="panel-heading">
	                    <ul class="nav nav-tabs">
	                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Cancelled Special External GC</a></li>
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
	                                                <th>RFSEGC #</th>
	                                                <th>Date Requested</th>
	                                                <th>Date Needed</th>
	                                                <th>Requested by</th>
	                                                <th>Customer</th>
	                                                <th>Date Cancelled</th>
	                                                <th>Cancelled By</th>
	                                            </tr>
	                                        </thead>
	                                        <tbody>           

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

function viewReleasedSpecialSingle($link,$reqid)
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
        special_external_gcrequest.spexgc_receviedby,
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

    // released details
    $table = 'approved_request';
    $select = "approved_request.reqap_remarks,
		approved_request.reqap_date,
		CONCAT(users.firstname,' ',users.lastname) as relby";
    $where = "approved_request.reqap_trid='".$reqid."'
		AND
			approved_request.reqap_approvedtype='special external releasing'";
    $join = "INNER JOIN
			users
		ON
			users.user_id = approved_request.reqap_preparedby";
    $limit = '';
    $relDetails = getSelectedData($link,$table,$select,$where,$join,$limit);
    ?>
    <div class="row form-container">
        <div class="col-md-12">
            <div class="panel with-nav-tabs panel-info">
                <div class="panel-heading">
                    <button class="btn pull-right" onclick="window.location='#/released-special-external-request/'">Back</button>
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
                                                    <label class="nobot">Bank Account Number</label>
                                                    <input type="text" class="form-control inptxt bot-6" value="<?php echo ucwords($data->spexgcbi_bankaccountnum); ?>" disabled>
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

                                    <?php if(count($relDetails) > 0): ?>
	                                    <div class="review-details" style="background-color: beige; padding:10px; margin-top:10px;">
	                                        <div class="row margin-bot-0">
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <label class="nobot">Date Released</label>
	                                                    <input type="text" class="form-control inptxt bot-6" value="<?php echo _dateFormat($relDetails->reqap_date); ?>" disabled>
	                                                </div>
	                                            </div>   
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <label class="nobot">Received by</label>
	                                                    <input type="text" class="form-control inptxt bot-6" value="<?php echo $data->spexgc_receviedby; ?>" disabled>
	                                                </div>
	                                            </div> 	                                        
	                                        </div>
	                                        <div class="row margin-bot-0">
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <label class="nobot">Remarks</label>
	                                                    <textarea class="form-control inptxt" disabled><?php echo $relDetails->reqap_remarks; ?></textarea>
	                                                </div>
	                                            </div>
	                                            <div class="col-sm-6">
	                                                <div class="form-group">
	                                                    <label class="nobot">Released by</label>
	                                                    <input type="text" class="form-control inptxt bot-6" value="<?php echo ucwords($relDetails->relby); ?>" disabled>
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

function viewReleasedSpecialGC($link)
{
	// if(!hasPageAccessView($link,3,$_SESSION['gc_id']))
	// {
	// 	echo 'Page not found.';
	// 	exit();
	// }

	$table='special_external_gcrequest';
	$select ="special_external_gcrequest.spexgc_id,
	    special_external_gcrequest.spexgc_num,
	    CONCAT(req.firstname,' ',req.lastname) as reqby,
	    special_external_gcrequest.spexgc_datereq,
	    special_external_gcrequest.spexgc_dateneed,
	    special_external_customer.spcus_companyname,
	    approved_request.reqap_date,
		CONCAT(rev.firstname,' ',rev.lastname) as revby";
	$where = "special_external_gcrequest.spexgc_released='released'
		AND
			approved_request.reqap_approvedtype='special external releasing'";
	$join = 'INNER JOIN
			users as req
		ON
			req.user_id = special_external_gcrequest.spexgc_reqby
		INNER JOIN
			special_external_customer
		ON
			special_external_customer.spcus_id = special_external_gcrequest.spexgc_company  
		INNER JOIN
			approved_request
		ON
			approved_request.reqap_trid = special_external_gcrequest.spexgc_id
		INNER JOIN
			users as rev
		ON
			rev.user_id = approved_request.reqap_preparedby';
	$limit = '';

	$data = getAllData($link,$table,$select,$where,$join,$limit);
	?>
		<div class="row form-container">
	    	<div class="col-md-12">
	            <div class="panel with-nav-tabs panel-info">
	                <div class="panel-heading">
	                    <ul class="nav nav-tabs">
	                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Released Special External GC</a></li>
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
	                                                <th>RFSEGC #</th>
	                                                <th>Date Requested</th>
	                                                <th>Date Needed</th>
	                                                <th>Requested by</th>
	                                                <th>Customer</th>
	                                                <th>Date Released</th>
	                                                <th>Released By</th>
	                                            </tr>
	                                        </thead>
	                                        <tbody>           
		                                        <?php foreach ($data as $d): ?>
		                                        	<tr class="clickable" onclick="window.location='#/released-special-external-request/<?php echo $d->spexgc_id; ?>'">
		                                        		<td><?php echo $d->spexgc_num; ?></td>
		                                        		<td><?php echo _dateFormat($d->spexgc_datereq); ?></td>
		                                        		<td><?php echo _dateFormat($d->spexgc_dateneed); ?></td>
		                                        		<td><?php echo ucwords($d->reqby); ?></td>
		                                        		<td><?php echo ucwords($d->spcus_companyname); ?></td>
		                                        		<td><?php echo _dateFormat($d->reqap_date); ?></td>
		                                        		<td><?php echo ucwords($d->revby); ?></td>
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


?>
<div class="modal modal-static fade loadingstyle" id="processing-modal" role="dialog" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog loadingstyle">
      <div class="text-center">
          <img src="../assets/images/ring-alt.svg" class="icon" />
          <h4 class="loading">Saving Data...</h4>
      </div>
    </div>
</div>
