<?php 
  session_start();
  include '../function.php';
  include 'header.php';

  $stores = getStores($link);
?>

<?php require '../menu.php'; ?>

    <div class="main fluid">    
    	<div class="row">
    		<div class="col-md-12">
		        <div class="panel with-nav-tabs panel-info">
		            <div class="panel-heading">
		              	<ul class="nav nav-tabs">
		                	<li class="active" style="font-weight:bold">
		                  		<a href="#tab1default" data-toggle="tab">GC Report</a>
		                	</li>
		              	</ul>
		            </div>
		            <div class="panel-body">
		                <div class="tab-content">
		                    <div class="tab-pane fade in active" id="tab1default">
		                     	<div class="row form-container">
		                     		<form class="form-horizontal" id="tresalesreport">

			                        	<div class="col-xs-5">
						              		<div class="form-group">
						              			<label class="col-xs-5 control-label">Store</label>
						              			<div class="col-xs-7">
						              				<select class="form-control formbot reqfield input-sm" id="store" autofocus required>
						              					<option value="">- Select -</option>
						              					<?php foreach ($stores as $s): ?>
						              						<option value="<?php echo $s->store_id; ?>"><?php echo $s->store_name; ?></option>
						              					<?php endforeach ?>
						              					<option value="all">All Store</option>
						              				</select>
						              			</div>
						              		</div>											

				                            <fieldset>
				                            	<legend class="mid">Report Type:</legend>
				                              	<div class="form-group">
				                                	<div class="col-xs-offset-3 col-xs-9">
				                                  		<div class="input-group">
				                                    		<span class="input-group-addon">
				                                    			<input type="checkbox" class="rad" id="reval" name="reportype[]" value="gcsales">
				                                    		</span>
				                                    		<input type="text" class="form-control" disabled="" value="GC Sales">
				                                  		</div><!-- /input-group -->  
				                                	</div>            
				                              	</div> 
				                              	<div class="form-group">
				                                	<div class="col-xs-offset-3 col-xs-9">
				                                  		<div class="input-group">
				                                    		<span class="input-group-addon">
				                                    			<input type="checkbox" class="rad" id="reval" name="reportype[]" value="reval">
				                                    		</span>
				                                    		<input type="text" class="form-control" disabled="" value="GC Revalidation">
				                                  		</div><!-- /input-group -->  
				                                	</div>            
				                              	</div>  
				                              	<div class="form-group">
				                                	<div class="col-xs-offset-3 col-xs-9">
				                                  		<div class="input-group">
				                                    		<span class="input-group-addon">
				                                    			<input type="checkbox" class="rad" id="refund" name="reportype[]" value="refund">
				                                    		</span>
				                                    		<input type="text" class="form-control" disabled="" value="GC Refund">
				                                  		</div><!-- /input-group -->  
				                                	</div>            
				                              	</div>    
				                            </fieldset>
			                            </div>			                        		

			                        	<div class="col-xs-5">
				                            <fieldset>    
												<legend class="mid">Transaction Date</legend>  
												<div class="form-group">
													<div class="col-xs-offset-3 col-xs-9">
														<div class="input-group">
															<span class="input-group-addon">
																<input type="radio" class="rad" id="datetrans" name="datetrans" value="today" onclick="salesReportType(this.value);" required>
															</span>
															<input type="text" class="form-control" disabled="" value="Today">
														</div><!-- /input-group -->  
													</div>            
												</div>   
												<div class="form-group">
													<div class="col-xs-offset-3 col-xs-9">
														<div class="input-group">
															<span class="input-group-addon">
																<input type="radio" class="rad" id="datetrans" name="datetrans" value="yesterday" onclick="salesReportType(this.value);" required>
															</span>
															<input type="text" class="form-control" disabled="" value="Yesterday">
														</div><!-- /input-group -->  
													</div>            
												</div>     
												<div class="form-group">
													<div class="col-xs-offset-3 col-xs-9">
														<div class="input-group">
															<span class="input-group-addon">
																<input type="radio" class="rad" id="datetrans" name="datetrans" value="thisweek" onclick="salesReportType(this.value);" required>
															</span>
															<input type="text" class="form-control" disabled="" value="This week">
														</div><!-- /input-group -->  
													</div>            
												</div>  
												<div class="form-group">
													<div class="col-xs-offset-3 col-xs-9">
														<div class="input-group">
															<span class="input-group-addon">
																<input type="radio" class="rad" id="datetrans" name="datetrans" value="curmonth" onclick="salesReportType(this.value);" required>
															</span>
															<input type="text" class="form-control" disabled="" value="Current Month">
													</div><!-- /input-group -->  
													</div>            
												</div>       
												<div class="form-group">
													<div class="col-xs-offset-3 col-xs-9">
														<div class="input-group">
															<span class="input-group-addon">
																<input type="radio" class="rad" id="datetrans" name="datetrans" value="all" onclick="salesReportType(this.value);" required>
															</span>
															<input type="text" class="form-control" disabled="" value="All Transactions">
														</div><!-- /input-group -->  
													</div>            
												</div>
												<div class="form-group">
													<div class="col-xs-offset-3 col-xs-9">
														<div class="input-group">
															<span class="input-group-addon">
																<input type="radio" class="rad" id="datetrans" name="datetrans" value="range" onclick="salesReportType(this.value);" required>
															</span>
															<input type="text" class="form-control" disabled="" value="Date Range">
														</div><!-- /input-group -->  
													</div>            
												</div>
												<div class="form-group">
													<label class="col-sm-offset-2 col-sm-5 control-label">Date Start</label>
													<div class="col-sm-5">
														<input type="text" class="form-control formbot input-sm" id="dstart" name="dstart" required disabled>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-offset-2 col-sm-5 control-label">Date End</label>
													<div class="col-sm-5">
													<input type="text" class="form-control formbot input-sm" id="dend" name="dend" required disabled>
													</div>
												</div>
				                            </fieldset>
											<div class="response">
											</div>
											<div class="form-group">
												<div class="col-sm-offset-7 col-sm-5">
													<button type="submit" class="btn btn-block btn-primary"><i class="fa fa-download"></i>  Generate</button>
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
    </div><!-- end fluid div -->
<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/main.js"></script>
<script type="text/javascript">
	$('input#dstart').prop('disabled',true);
</script>
<?php include 'footer.php' ?>