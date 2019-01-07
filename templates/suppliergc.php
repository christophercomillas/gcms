<?php
session_start();
include '../function.php';

if(isset($_GET['page']))
{
	$page = $_GET['page'];

	if($page=='supplierverification')
	{
		supplierGCVerification($link,$todays_date);
	}
	elseif ($page=='sgccompanysetup') 
	{
		sgccompanysetup($link);
	}
	elseif ($page=='sgcitemsetup') 
	{
		sgcitemsetup($link,$todays_date);
	}
	else
	{
		echo 'Page not found.';
	}
}

function sgccompanysetup($link)
{

	$table = 'suppliergc';
	$select = "suppliergc.suppgc_id,
	    suppliergc.suppgc_compname,
	    suppliergc.suppgc_datecreated,
	    CONCAT(users.firstname,' ',users.lastname) as fullname,
	    suppliergc.suppgc_dateupdated,
	    suppliergc.suppgc_updatedby";
	$where = '1';
	$join = 'INNER JOIN
			users
		ON
			users.user_id = suppliergc.suppgc_createdby';
	$limit = '';
	$data = getAllData($link,$table,$select,$where,$join,$limit);

	?>
		<div class="row form-container">
	    	<div class="col-md-12">
	            <div class="panel with-nav-tabs panel-info">
	                <div class="panel-heading">
	                    <ul class="nav nav-tabs">
	                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Supplier GC Company Setup
	                        </a>
	                        </li>	                        
	                        <!-- <li><a href="#tab2default" data-toggle="tab">Default 2</a></li> -->
	                        <button class="btn btn-info pull-right" id="addcus" onclick="addNewSgcCompany()"><i class="fa fa-user-plus"></i> Add New Company</button>
	                    </ul>	                    
	                </div>
	                <div class="panel-body">
	                    <div class="tab-content">
	                        <div class="tab-pane fade in active" id="tab1default">
	                        	<div class="row">
	                        		<div class="col-sm-12">
	                        			<table class="table" id="userlist">
	                        				<thead>
	                        					<tr>
	                        						<th>Company Name</th>
	                        						<th>Created By</th>
	                        						<th>Date Created</th>
	                        					</tr>
	                        				</thead>
	                        				<tbody>
	                        					<?php foreach ($data as $d): ?>
	                        						<tr>
	                        							<td><?php echo $d->suppgc_compname; ?></td>
	                        							<td><?php echo _dateFormat($d->suppgc_datecreated); ?></td>
	                        							<td><?php echo ucwords($d->fullname); ?></td>
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
			$.extend( $.fn.dataTableExt.oStdClasses, {	  
			    "sLengthSelect": "selectsup"
			});
		    $('#userlist').dataTable( {
		        "pagingType": "full_numbers",
		        "ordering": false,
		        "processing": true
		    });

		    function addNewSgcCompany()
		    {
		    	alert('x');
		    }


		</script>
	<?php
}

function sgcitemsetup($link,$todays_date)
{
	$table = 'suppliergc';
	$select = "suppliergc.suppgc_id,
	    suppliergc.suppgc_compname,
	    suppliergc.suppgc_datecreated,
	    CONCAT(users.firstname,' ',users.lastname) as fullname,
	    suppliergc.suppgc_dateupdated,
	    suppliergc.suppgc_updatedby";
	$where = '1';
	$join = 'INNER JOIN
			users
		ON
			users.user_id = suppliergc.suppgc_createdby';
	$limit = '';
	$data = getAllData($link,$table,$select,$where,$join,$limit);

	?>
		<div class="row form-container">
	    	<div class="col-md-12">
	            <div class="panel with-nav-tabs panel-info">
	                <div class="panel-heading">
	                    <ul class="nav nav-tabs">
	                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Supplier GC Product Setup
	                        </a>
	                        </li>	                        
	                        <!-- <li><a href="#tab2default" data-toggle="tab">Default 2</a></li> -->
	                        <button class="btn btn-info pull-right" id="addcus"><i class="fa fa-user-plus"></i> Add New Product</button>
	                    </ul>	                    
	                </div>
	                <div class="panel-body">
	                    <div class="tab-content">
	                        <div class="tab-pane fade in active" id="tab1default">
	                        	<div class="row">
	                        		<div class="col-sm-12">
	                        			<table class="table" id="userlist">
	                        				<thead>
	                        					<tr>
	                        						<th>Product Name</th>
	                        						<th>GC # / Ctrl # </th>
	                        						<th>Description</th>
	                        						<th>GC Worth</th>
	                        						<th>Company Name</th>
	                        						<th>Created By</th>
	                        						<th>Date Created</th>
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
			$.extend( $.fn.dataTableExt.oStdClasses, {	  
			    "sLengthSelect": "selectsup"
			});
		    $('#userlist').dataTable( {
		        "pagingType": "full_numbers",
		        "ordering": false,
		        "processing": true
		    });
		</script>
	<?php
}

function supplierGCVerification($link,$todays_date)
{
	?>
		<div class="row form-container">
	    	<div class="col-md-12">
	            <div class="panel with-nav-tabs panel-info">
	                <div class="panel-heading">
	                    <ul class="nav nav-tabs">
	                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Supplier GC Verification
	                        </a>
	                        </li>
	                        <!-- <li><a href="#tab2default" data-toggle="tab">Default 2</a></li> -->
	                    </ul>	                    
	                </div>
	                <div class="panel-body">
	                    <div class="tab-content">
	                        <div class="tab-pane fade in active" id="tab1default">
	                        	<div class="row">
									<form action="../ajax.php?action=releaseTreasuryCustomer" method="POST" id="releaseTreasuryCustomer" enctype="multipart/form-data">                  
                          				<div class="col-sm-12">
                              				<div class="col-sm-3">
				                                <div class="form-group">
													<label class="nobot">Date Verified</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo _dateFormat($todays_date); ?>">             
				                                </div>
				                                <div class="form-group">
													<label class="nobot"><span class="requiredf">*</span>Verified By:</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>" id="recby" name="recby" required autocomplete="off">                   
				                                </div>
				                                <div class="form-group">
													<label class="nobot">Upload Document</label> 
													<input id="input-file" class="file" type="file" name="docs[]" multiple>
				                                </div>
				                            </div>
				                            <div class="col-sm-4">
                                				<div class="form-group">
                                  					<label class="nobot">GC Control # / Barcode #</label>   
                                  					<input type="text" class="form form-control inptxt input-sm bot-6" value="" style="text-align:right; font-weight:bold;" autofocus autocomplete="off"> 
                                				</div>
						<!-- 					                            	<div class="form-group">
													<button type="button" class="btn btn-block btn-default" onclick="supplierGCList();"><i class="fa fa-search"></i> Supplier Lookup</button>
												</div> -->
                                				<div class="form-group">
                                  					<!-- <label class="nobot">GC Name</label>   -->
                                  					<select class="form form-control inptxt input-sm bot-6">
                                  						<option value="">
                                  							- Select GC Name-
                                  						</option>
                                  					</select> 
                                  					<!-- <input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="">  -->
                                				</div>
                                				<div class="form-group">
                                  					<label class="nobot">Company Name</label>   
                                  					<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value=""> 
                                				</div>
                                				<div class="form-group">
                                  					<!-- <label class="nobot">GC Name</label>   -->
                                  					<select class="form form-control inptxt input-sm bot-6">
                                  						<option value="">
                                  							- Select Item Description -
                                  						</option>
                                  					</select> 
                                  					<!-- <input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="">  -->
                                				</div>

				                                <div class="form-group">
													<label class="nobot">Remarks:</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" value="" id="remarks" name="remarks" autocomplete="off">                   
				                                </div>
								<!-- 					                            	<div class="form-group">
													<button type="button" class="btn btn-block btn-default" onclick="supplierGCItemList();"><i class="fa fa-search"></i> Item Lookup</button>
												</div> -->
								<!-- 	                                				<div class="form-group">
                                  					<label class="nobot">Item Description</label>   
                                  					<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value=""> 
                                				</div> -->
				                            </div>
				                            <div class="col-sm-5">
				                            	<div class="form-group">
													<button type="button" class="btn btn-block btn-default" onclick="lookupcustomer();"><i class="fa fa-search"></i> Customer Lookup</button>
												</div>
												<div class="customerdetails form-horinzontal">
													<i class="fa fa-user"></i>
													Customer Details
												</div>
												<div class="customerdetails-container form-horizontal">
													<input type="hidden" name="cus-id" value="" id="cid">
													<div class="form-group">
														<label class="col-xs-5 control-label">First Name:</label>
													<div class="col-xs-7">
														<input type="text" class="form-control inptxt input-xs" id="fname" readonly="readonly">                      
														</div>
													</div><!-- end of form-group -->
													<div class="form-group">
														<label class="col-xs-5 control-label">Last Name:</label>
														<div class="col-xs-7">
														<input type="text" class="form-control inptxt input-xs" id="lname" readonly="readonly">                      
													</div>
													</div><!-- end of form-group -->
													<div class="form-group">
													<label class="col-xs-5 control-label">Middle Name:</label>
													<div class="col-xs-7">
													<input type="text" class="form-control inptxt input-xs" id="mname" readonly="readonly">                      
													</div>
													</div><!-- end of form-group -->
													<div class="form-group">
													<label class="col-xs-5 control-label">Name Ext:</label>
													<div class="col-xs-7">
													<input type="text" class="form-control inptxt input-xs" id="next" readonly="readonly">                      
													</div>
													</div><!-- end of form-group -->        
												</div>
                                				<div class="response">

                                				</div>
												<div class="form-group">
													<div class="col-sm-offset-5 col-sm-7">
														<button type="submit" class="btn btn-block btn-primary" id="btn"> <span class="glyphicon glyphicon-share" aria-hidden="true"></span> Submit</button>
													</div>
												</div>
				                            </div>
				                        </div>
				                    </form>
	                        	</div>
	                        </div>
	                        <!-- <div class="tab-pane fade" id="tab2default">Default 2</div> -->
	                    </div>
	                </div>
	            </div>
	        </div>
		</div>

		<script type="text/javascript">
		    $('input#input-file').fileinput({
		      'allowedFileExtensions' : ['jpg','png','jpeg']
		    });
		</script>
	<?php
}

?>

<script src="../assets/js/funct.js"></script>


