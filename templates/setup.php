<?php
session_start();
include '../function.php';

if(isset($_GET['page']))
{
	$page = $_GET['page'];

	if($page=='setup-tres-customer')
	{
		$table = 'institut_customer';
		$select = "institut_customer.ins_name,
			institut_customer.ins_custype,
			CONCAT(users.firstname,' ',users.lastname) as crby,
			institut_customer.ins_date_created,
			gc_type.gctype";
		$where = "institut_customer.ins_status='active'";
		$join = 'INNER JOIN
				users
			ON
				users.user_id = institut_customer.ins_by
			LEFT JOIN
				gc_type
			ON
				gc_type.gc_type_id = institut_customer.ins_gctype';
		$limit = 'ORDER BY institut_customer.ins_id DESC';

		$data = getAllData($link,$table,$select,$where,$join,$limit)

			// SELECT 
			// 	institut_customer.ins_name,
			// 	institut_customer.ins_custype,
			// 	CONCAT(users.firstname,' ',users.lastname) as crby,
			// 	institut_customer.ins_date_created,
			//     gc_type.gctype
			// FROM 
			// 	institut_customer
			// INNER JOIN
			// 	users
			// ON
			// 	users.user_id = institut_customer.ins_by
			// LEFT JOIN
			// 	gc_type
			// ON
			// 	gc_type.gc_type_id = institut_customer.ins_gctype
			// WHERE 
			// 	institut_customer.ins_status='active'
			// ORDER BY 
			// 	institut_customer.ins_id 
			// DESC	

		?>
		<div class="row form-container">
	    	<div class="col-md-12">
	            <div class="panel with-nav-tabs panel-info">
	                <div class="panel-heading">

	                    <ul class="nav nav-tabs">
	                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Customer Setup</a></li>
	                        <button type="button" class="btn btn-info pull-right" onclick="addTreasuryCustomer();"><i class="fa fa-user-plus" aria-hidden="true"></i>
 								Add Customer
 							</button>
	                        <!-- <li><a href="#tab2default" data-toggle="tab">Default 2</a></li> -->
	                    </ul>	                    
	                </div>
	                <div class="panel-body">
	                    <div class="tab-content">
	                        <div class="tab-pane fade in active" id="tab1default">
	                        	<div class="row">
	                        		<div class="col-sm-12">
	                        			<table class="table" id="_customers">
	                        				<thead>
	                        					<tr>
	                        						<th>Customer Name</th>
	                        						<th>Customer Type</th>
	                        						<th>GC Type</th>
	                        						<th>Date Created</th>
	                        						<th>Created By</th>
	                        					</tr>
	                        				</thead>
	                        				<tbody>
	                        					<?php foreach ($data as $d): ?>
	                        						<tr>
	                        							<td><?php echo ucwords($d->ins_name); ?></td>
	                        							<td><?php echo ucwords($d->ins_custype); ?></td>
	                        							<td><?php echo ucwords($d->gctype); ?></td>
	                        							<td><?php echo _dateFormat($d->ins_date_created); ?></td>
	                        							<td><?php echo ucwords($d->crby); ?></td>
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
		<script type="text/javascript">
	        $('#_customers').DataTable( {
	            "order": [[ 1, "desc" ]]
	        } );
		</script>

		<?php
	}
	elseif($page=='addtrescustomer')
	{
		?>
			<div class="row form-container">
				<div class="col-md-12 form-horizontal">
					<form method="POST" action="../ajax.php?action=addTresuryCustomer" id="addTresuryCustomer">
						<div class="form-group">
							<label class="col-sm-5 control-label">Customer Name:</label>
							<div class="col-sm-7">
								<input type="text" class="form form-control" name="company" id="company" autofocus autocomplete="off">	
								<input type="text" class="form form-control" name="company1" id="company1" autofocus autocomplete="off" style="display:none">															
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label">Customer Type:</label>
							<div class="col-sm-7">
								<select class="form form-control inptxt input-md" name="ctype" id="ctype">
									<option value="">- Select -</option>
									<option value="internal">Internal</option>
									<option value="external">External</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label">GC Type:</label>
							<div class="col-sm-7">
								<select class="form form-control inptxt input-md" name="gctype" id="gctype">
									<option value="">- Select -</option>
									<option value="1">Regular</option>
									<option value="4">Promo</option>
								</select>							
							</div>
						</div>
<!-- 						<div class="form-group">
							<label class="col-sm-5 control-label">Payment Fund:</label>
							<div class="col-sm-7">
								<select class="form form-control inptxt input-md" name="pfund" id="pfund">
									<option value="">- Select -</option>
									<option value="1">P</option>
									<option value="2">Special External</option>
									<option value="3">Promo</option>
								</select>								
							</div>
						</div> -->
						<div class="response">
						</div>
					</form>
				</div>
			</div>
			<script type="text/javascript">
				$('#company').focus();

		    	$('form#addTresuryCustomer select#ctype').change(function(){
		    		var ctype = $(this).val();

		    		if(ctype=='external')
		    		{
		    			$("form#addTresuryCustomer select#gctype").val("");
		    			$("form#addTresuryCustomer select#gctype").prop('disabled',true);
		    		}
		    		else if(ctype=='internal')
		    		{
		    			$("form#addTresuryCustomer select#gctype").val("1");
		    			$("form#addTresuryCustomer select#gctype").prop('disabled',false);
		    		}
		    		else
		    		{
		    			$("form#addTresuryCustomer select#gctype").val("");
		    		}
		    		// if(utype!='retailstore'){
		    		// 	$('#stores-a').addClass('hide');
		    		// } else {
		    		// 	$('#$store-a').removeClass('hide');
		    		// }
		    	});
			</script>
		<?php
	}
	elseif ($page=='addnewpaymentfund') 
	{
		_addnewpaymentfund($link);
	}
	elseif($page=='setup-paymentfund')
	{
		_setupPaymentFund($link);
	}
	elseif ($page=='addNewCustomerDialog') 
	{
		_addNewCustomerDialog($link);
	}	
	elseif ($page=='setup-special-external') 
	{
		_setupSpecialExternalCustomer($link);
	}	
	else 
	{
		//last
		echo 'Something went wrong.';
	}	
}

function _setupSpecialExternalCustomer($link)
{
    $select = "special_external_customer.spcus_id, 
        special_external_customer.spcus_companyname, 
        special_external_customer.spcus_acctname,
        special_external_customer.spcus_address, 
        special_external_customer.spcus_cperson, 
        special_external_customer.spcus_cnumber, 
        special_external_customer.spcus_at,
        CONCAT(users.firstname,' ',users.lastname) as createdby";

    $where = '1';

    $join = 'INNER JOIN
        users
    ON
        users.user_id = special_external_customer.spcus_by';

    $limit ='ORDER BY spcus_id DESC';
    $cus = getAllData($link,'special_external_customer',$select,$where,$join,$limit);

	?>
		<div class="row">
			<div class="col-sm-12">
			  	<div class="col-md-12 pad0">
			    	<div class="panel with-nav-tabs panel-info">
			        	<div class="panel-heading">
			          		<ul class="nav nav-tabs">
			            		<li class="active" style="font-weight:bold">
			                		<a href="#tab1default" data-toggle="tab">Special External GC Customer Setup</a>
			            		</li>
			            		<button type="button" class="btn pull-right" onclick="addExternalCustomer();"><i class="fa fa-plus-square" aria-hidden="true"></i>
			            			Add Customer Info
			            		</button>
			         	 	</ul>
			        	</div>
				        <div class="panel-body">
				            <div class="tab-content">
								<div class="tab-pane fade in active" id="tab1default">
									<div class="row form-container">
										<div class="col-sm-12">
											<table class="table" id="sexcustomer">
												<thead>
													<tr>
														<th>Company Name / Person</th>
														<th>Account Name</th>
														<th>Address</th>
														<th>Contact Person</th>
														<th>Contact Number</th>
														<th>Created by</th>
														<th>Date Created</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($cus as $key): ?>
														<tr>
											  				<td><?php echo ucwords($key->spcus_companyname); ?></td>
											  				<td><?php echo $key->spcus_acctname; ?></td>
															<td><?php echo ucwords($key->spcus_address); ?></td>
															<td><?php echo ucwords($key->spcus_cperson); ?></td>
															<td><?php echo ucwords($key->spcus_cnumber); ?></td>
															<td><?php echo ucwords($key->createdby); ?></td>
															<td><?php echo _dateFormat($key->spcus_at); ?></td>
															<td></td>
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
			$.extend( $.fn.dataTableExt.oStdClasses, {	  
			    "sLengthSelect": "selectsup"
			});

		    $('#sexcustomer').dataTable( {
		        "pagingType": "full_numbers",
		        "ordering": false,
		        "processing": true
		    });

			function addExternalCustomer()
			{
				BootstrapDialog.show({
			    	title: 'Add Customer',
			 	    cssClass: 'customer-internal',
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
			            'pageToLoad': '../dialogs/extenalgc.php?action=addcompany'
			        },
			        onshow: function(dialog) {
			            // dialog.getButton('button-c').disable();
			        },
			        onshown: function(dialogRef){
			        	setTimeout(function(){
			        		$('#company').focus();
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
			            	$('.response').html('');
			            	$buttons = this;
			            	//$buttons.disable(); 

			            	if($('#company').val == undefined)
			            	{
			            		$('#company').focus();
			            		return false;
			            	}

			            	if($('#company').val().trim()=='')
			            	{
			            		$('#company').focus();
			            		$('.response').html('<div class="alert alert-danger" id="danger-x">Please input Company / Person name.</div>');
			            		return false;
			            	}

			            	var formURL = $('form#addexternalcustomer').attr('action'), formDATA = $('form#addexternalcustomer').serialize();
			            	$.ajax({
			            		url:'../ajax.php?action=addexternalcustomervalidate',
			            		data:formDATA,
			            		type:'POST',
			            		success:function(data)
			            		{
			            			console.log(data);
			            			var data = JSON.parse(data);
			            			if(data['st'])
			            			{
			            				$('.response').html('<div class="alert alert-danger" id="danger-x">Company / Person name already exist.</div>');
			            				return  false;
			            			}
			            			else 
			            			{
			            				$buttons.disable();
			            				$.ajax({
			            					url:formURL,
			            					data:formDATA,
			            					type:'POST', 
			            					success:function(datas)
			            					{
			            						console.log(datas);
			            						var datas = JSON.parse(datas);
			            						if(datas['st'])
			            						{
								    				BootstrapDialog.closeAll();
													var dialog = new BootstrapDialog({
										            message: function(dialogRef){
										            var $message = $('<div>Customer successfully added.</div>');			        
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
								                    	location.reload();
								               		}, 1700);
			            						}
			            						else 
			            						{
			            							$buttons.enable();
			            							$('.response').html('<div class="alert alert-danger" id="danger-x">'+data['msg']+'</div>');

			            						}
			            					}
			            				});
			            			}
			            		}
			            	});					            	

			            }
			    	},{
			            icon: 'glyphicon glyphicon-remove',
			            label: 'Close',
			            cssClass: 'btn-default',
			            action:function(dialogItself){
			            	dialogItself.close();
			            }
			        }]

			    });
			}

		</script>

	<?php
}

function _addNewCustomerDialog($link)
{
	?>
		<div class="form-container">  
			<form method="POST" action="../ajax.php?action=addNewCustomerVerification" id="_addcustomer">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label class="label-dialog"><span class="requiredf">*</span>First Name</label>
							<input type="text" class="form form-control inpmedx inptxt-l" id="fnamedialog" name="fname" autocomplete="off" autofocus>
						</div>
						<div class="form-group">
							<label class="label-dialog"><span class="requiredf">*</span>Last Name</label>
							<input type="text" class="form form-control inpmedx inptxt-l" id="lnamedialog" name="lname" autocomplete="off">
						</div>
						<div class="form-group">
							<label class="label-dialog"><span class="requiredf">*</span>Middle Name</label>
							<input type="text" class="form form-control inpmedx inptxt-l" id="mnamedialog" name="mname" autocomplete="off">
						</div>
						<div class="form-group">
							<label class="label-dialog">Name Ext. (ex. jr, sr, III)</label>
							<input type="text" class="form form-control inpmedx inptxt-l" id="extnamedialog" name="extname" autocomplete="off">
						</div>
						<div class="response-dialog">										
						</div>
					</div>
				</div>
			</form>
		</div>
		<script type="text/javascript">
			$('input#fnamedialog').select();
		</script>
	<?php
}

function _setupPaymentFund($link)
{
	$table = 'payment_fund';
	$select = "payment_fund.pay_id,
	    payment_fund.pay_desc,
	    payment_fund.pay_status,
	    payment_fund.pay_dateadded,
	    CONCAT(users.firstname,' ',users.lastname) as user";
	$where = "payment_fund.pay_status='active'";
	$join = 'INNER JOIN
			users
		ON
			users.user_id = payment_fund.pay_addby	';
	$limit = '';

	$payment = getAllData($link,$table,$select,$where,$join,$limit);

	?>
		<div class="row form-container">
	    	<div class="col-md-12">
	            <div class="panel with-nav-tabs panel-info">
	                <div class="panel-heading">
	                    <ul class="nav nav-tabs">
	                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Payment Fund Setup</a></li>
	                        <button type="button" class="btn btn-info pull-right" onclick="addPaymentFund();"><i class="fa fa-user-plus" aria-hidden="true"></i>
 									Add Payment</button>
	                        <!-- <li><a href="#tab2default" data-toggle="tab">Default 2</a></li> -->
	                    </ul>	                    
	                </div>
	                <div class="panel-body">
	                    <div class="tab-content">
	                        <div class="tab-pane fade in active" id="tab1default">
	                        	<div class="row">
	                        		<div class="col-sm-12">
	                        			<table class="table" id="_customers">
	                        				<thead>
	                        					<tr>
	                        						<th>Name</th>
	                        						<th>Date Created</th>
	                        						<th>Created By</th>
	                        					</tr>
	                        				</thead>
	                        				<tbody>
	                        					<?php foreach ($payment as $p): ?>
	                        						<tr>
	                        							<td><?php echo ucwords($p->pay_desc); ?></td>
	                        							<td><?php echo _dateFormat($p->pay_dateadded); ?></td>
	                        							<td><?php echo ucwords($p->user); ?></td>
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
		<script type="text/javascript">

	        $('#_customers').DataTable( {
	            "order": [[ 1, "desc" ]]
	        } );

			function addPaymentFund()
			{
			    BootstrapDialog.show({
			        title: 'Add Payment Fund',
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
			            'pageToLoad': '../templates/setup.php?page=addnewpaymentfund'
			        },
			        onshown: function(dialogRef){

			        },
			        onhidden:function(dialogRef){
			        },        
			        buttons: [{
			            icon: 'glyphicon glyphicon-ok-sign',
			            label: 'Submit',
			            cssClass: 'btn-primary',
			            hotkey: 13,
			            action:function(dialogItself){
			            	$('.response').html('');
			            	if($('#paymentname').val()==undefined)
			            	{
			            		$('.response').html('Please input Payment Fund Name.');
			            		$('#paymentname').focus();
			            		return false;
			            	}

			            	if($('#paymentname').val().trim()=='')
			            	{
			            		$('.response').html('<div class="alert alert-danger" id="danger-x">Please input Payment Fund.</div>');
			            		$('#paymentname').focus();
			            		return false;
			            	}
			            	var formURL = $('form#paymentfundForm').attr('action'), formDATA = $('form#paymentfundForm').serialize();
					        BootstrapDialog.show({
					        	title: 'Confirmation',
					            message: 'Add Payment Fund?',
					            closable: true,
					            closeByBackdrop: false,
					            closeByKeyboard: true,
					            onshow: function(dialog) {
					                // dialog.getButton('button-c').disable();
					            },
					            buttons: [{
					                icon: 'glyphicon glyphicon-ok-sign',
					                label: 'Yes',
					                cssClass: 'btn-primary',
					                hotkey: 13,
					                action:function(dialogItself){
					                	dialogItself.close();
						            	dialogItself.enableButtons(false);
						            	dialogItself.setClosable(false);	

						            	$.ajax({
						            		url:formURL,
						            		type:'POST',
						            		data: formDATA,
						            		beforeSend:function(){

						            		},
						            		success:function(data){
						            			var data = JSON.parse(data);					            			

						            			if(data['st'])
						            			{
						            				dialogItself.close();
													var dialog = new BootstrapDialog({
										            message: function(dialogRef){
										            var $message = $('<div>Payment Fund Successfully Saved.</div>');			        
										                return $message;
										            },
										            closable: false
											        });
											        dialog.realize();
											        dialog.getModalHeader().hide();
											        dialog.getModalFooter().hide();
											        dialog.getModalBody().css('background-color', '#0088cc');
											        dialog.getModalBody().css('color','#fff');
											        dialog.open();
								               		setTimeout(function(){
								                    	window.location.reload();
								               		}, 1700);
						            			}
						            			else 
						            			{
						            				$('.response').html('<div class="alert alert-danger" id="danger-x">'+data['msg']+'</div>');
									            	dialogItself.enableButtons(true);
									            	dialogItself.setClosable(true);
						            			}
						            		}
						            	});
					                }
					            }, {
					            	icon: 'glyphicon glyphicon-remove-sign',
					                label: 'No',
					                action: function(dialogItself){
						            	dialogItself.enableButtons(true);
						            	dialogItself.setClosable(true);	
					                    dialogItself.close();
					                }
					            }]
					        });		
			            	// dialogItself.enableButtons(false);
			            	// dialogItself.setClosable(false);
			            	// $.ajax({
			            	// 	url:formURL,
			            	// 	data:formDATA,
			            	// 	type:'POST',
			            	// 	success:function(data)
			            	// 	{
			            	// 		console.log(data);

			            	// 		var data = JSON.parse(data);
			            	// 		if(data['st'])
			            	// 		{			            				
			            				
			            	// 		}
			            	// 		else 
			            	// 		{
			            	// 			$('.response').html('<div class="alert alert-danger" id="danger-x">'+data['msg']+'</div>');
						          //   	dialogItself.enableButtons(true);
						          //   	dialogItself.setClosable(true);
			            	// 		}
			            	// 	}
			            	// });	
			        	}
			        }, {
			        	icon: 'glyphicon glyphicon-remove-sign',
			            label: 'Close',
			            action: function(dialogItself){
			                dialogItself.close();
			            }
			        }]
			    });
			}

		</script>

	<?php
}

function _addnewpaymentfund($link)
{
	?>
		<div class="row form-container">
			<div class="col-md-12 form-horizontal">
				<form method="POST" action="../ajax.php?action=addPaymentFund" id="paymentfundForm">
					<div class="form-group">
						<label class="col-sm-5 control-label">Payment Fund Name:</label>
						<div class="col-sm-7">
							<input type="text" class="form form-control" name="paymentname" id="paymentname" autofocus autocomplete="off">
							<input type="text" class="form form-control" name="company1" id="company1" style="display:none;">																
						</div>
						
					</div>
					<div class="response">
					</div>
				</form>
			</div>
		</div>
		<script type="text/javascript">
			$('#company').focus();

	    	$('form#addTresuryCustomer select#ctype').change(function(){
	    		var ctype = $(this).val();

	    		if(ctype=='external')
	    		{
	    			$("form#addTresuryCustomer select#gctype").val("");
	    			$("form#addTresuryCustomer select#gctype").prop('disabled',true);
	    		}
	    		else if(ctype=='internal')
	    		{
	    			$("form#addTresuryCustomer select#gctype").val("1");
	    			$("form#addTresuryCustomer select#gctype").prop('disabled',false);
	    		}
	    		else
	    		{
	    			$("form#addTresuryCustomer select#gctype").val("");
	    		}
	    		// if(utype!='retailstore'){
	    		// 	$('#stores-a').addClass('hide');
	    		// } else {
	    		// 	$('#$store-a').removeClass('hide');
	    		// }
	    	});



		</script>
	<?php
}
