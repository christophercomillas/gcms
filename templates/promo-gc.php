<?php
session_start();
include '../function.php';

if(isset($_GET['page']))
{
	$page = $_GET['page'];

	if($page=='view-approved-promo-gc')
	{
		echo 'yah';
	}
	elseif($page=='view-cancelled-promo-gc')
	{
		echo 'nah';
	}
	elseif($page=='promogcrequest')
	{
		promogcrequest($link,$todays_date);
	}
	elseif ($page=='addnewpromo') 
	{
		addNewPromo($link,$todays_date);
	}
	elseif ($page=='promolist') 
	{
		promoList($link);
	}
	elseif ($page=='releasedpromogc') 
	{
		releasePromoGC($link,$todays_date);
	}
	elseif ($page=='promogcstatus') 
	{
		promoGCStatus($link);
	}
	elseif ($page=='promoledger') 
	{
		_promoLedger($link);
	}
	else 
	{
		//last
		echo 'Something went wrong.';
	}	
}

function promoGCStatus($link)
{
	?>
		<div class="row form-container">
	    	<div class="col-md-12">
	            <div class="panel with-nav-tabs panel-info">
	                <div class="panel-heading">
	                    <ul class="nav nav-tabs">
	                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Promo GC Status</a></li>
	                        <!-- <li><a href="#tab2default" data-toggle="tab">Default 2</a></li> -->
	                    </ul>	                    
	                </div>
	                <div class="panel-body">
	                    <div class="tab-content">
	                        <div class="tab-pane fade in active" id="tab1default">
	                        	<div class="row">
	                        		<div class="col-xs-12">
	                        			<table class="table" id="barcode-grid">
	                        				<thead>
	                        					<tr>
	                        						<th>GC Barcode #</th>
	                        						<th>Denom</th>
	                        						<th>Retail Group</th>
	                         						<th>Promo Name</th>
	                        						<th>Status</th>
	                        						<th>Date Released</th>
	                        						<th>Released By</th>
	                        					</tr>
	                        				</thead>
	                        				<tbody>
	                        					<tr>
	                        						<td></td>
	                        						<td></td>
	                        						<td></td>
	                        						<td></td>
	                        						<td></td>
	                        						<td></td>
	                        						<td></td>
	                        					</tr>
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
			var dataTable = $('#barcode-grid').DataTable( {
				"processing": true,
				"serverSide": true,
				"ajax":{
					url :"../ajax.php?action=promogcstatuslist", // json datasource
					type: "post",  // method  , by default get
					error: function(data){  // error handling
						console.log(data);
						$(".employee-grid-error").html("");
						$("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
						$("#employee-grid_processing").css("display","none");				
					}
				}
			} );
		</script>
	<?php
}

function releasePromoGC($link,$todays_date)
{
	?>
		<div class="row form-container">
	    	<div class="col-md-12">
	            <div class="panel with-nav-tabs panel-info">
	                <div class="panel-heading">
	                    <ul class="nav nav-tabs">
	                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Release Promo GC</a></li>
	                        <!-- <li><a href="#tab2default" data-toggle="tab">Default 2</a></li> -->
	                    </ul>	                    
	                </div>
	                <div class="panel-body">
	                    <div class="tab-content">
	                        <div class="tab-pane fade in active" id="tab1default">	                        	
	                        	<div class="row">
	                        		<div class="col-sm-6">
										<form class="form-horizontal" method="POST" action="../ajax.php?action=gcpromoreleased" id="gcpromoreleased">
											<div class="form-group">
												<label class="col-xs-4 control-label">Date Release: </label>
												<div class="col-xs-5">
													<input value="<?php echo _dateFormat($todays_date); ?>" type="text" class="form-control inptxt input-sm" readonly="readonly">                
												</div>						
											</div>	
											<div class="form-group">
												<label class="col-xs-4 control-label">Claimant: </label>
												<div class="col-xs-8">
													<input type="text" class="form-control inptxt input-sm" name="claimant" id="claimant" autocomplete="off">                
												</div>					
											</div>	
											<div class="form-group">
												<label class="col-xs-4 control-label lpromorel">GC Barcode #: </label>
												<div class="col-xs-8">
													<input type="text" class="form-control inptxt input-sm promorel" data-inputmask="'alias': 'numeric', 'groupSeparator': '', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': ''" id="gcbarcodever" autocomplete="off" name="gcbarcode" maxlength="13" autofocus>                
												</div>					
											</div>	
											<div class="form-group">
												<label class="col-xs-4 control-label">Release By:</label>
												<div class="col-xs-6">
													<input type="text" class="form-control inptxt input-sm" readonly="readonly" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>"> 
												</div>					
											</div>	
											<div class="form-group">
												<div class="col-xs-offset-4 col-xs-8">
													<div class="response">
													</div>
												</div>						
											</div>
											<div class="form-group">
												<div class="col-xs-offset-8 col-xs-4">
													<button type="submit" class="btn btn-block btn-primary releasedbtn">
						                        		<span class="glyphicon glyphicon-share" aria-hidden="true"></span>
						                         		Submit
						                      		</button>             
												</div>						
											</div>
										</form>
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
			$('#gcbarcodever').inputmask();

			$('.form-container').on('submit','form#gcpromoreleased',function(){
				var  formURL = $(this).attr('action'), formData = $(this).serialize();
				$('button.releasedbtn').prop('disabled',true);
				$('.response').html('');
				var gc = $('input#gcbarcodever').val().trim();
				var claimant = $('input#claimant').val().trim();

				// if(claimant.trim()=='')
				// {
				// 	$('.response').html('<div class="alert alert-danger danger-rel">Please input claimant fullname.</div>');
				// 	return false;
				// }

				if(gc.length > 0)
				{
					if(gc.length==13)
					{
						$.ajax({
							url:'../ajax.php?action=checkgcpromo',
							data:{gc:gc},
							type:'POST',
							success:function(data)
							{
								console.log(data);
								var data = JSON.parse(data);
								if(data['st'])
								{
							        BootstrapDialog.show({
							        	title: 'Confirmation',
							            message: 'Are you sure you want to release GC Barcode # '+gc+'?',
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
							                	$buttons = this;
							                	$buttons.disable();
							                	dialogItself.close();
						                    	$('.response').html('');
												$.ajax({
													url:formURL,
													type:'POST',
													data:{gc:gc,claimant:claimant},
													beforeSend:function(){

													},
													success:function(data1){		
														console.log(data1);										
										    			var data1 = JSON.parse(data1);
										    			if(data1['st'])
										    			{								    				
										    				BootstrapDialog.closeAll();
															var dialog = new BootstrapDialog({
												            message: function(dialogRef){
												            var $message = $('<div>GC Barcode # '+gc+' successfully released.</div><div>Promo: '+data1['promo']+'</div><div>Promo Group '+data1['group']+'</div>');			        
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
										                 		$('input#gcbarcodever').focus();
										               		}, 1600);								               
									                    	$('input#gcbarcodever').val('');
									                    	$('button.releasedbtn').prop('disabled',false);							             						                    	    				
										    			} 
										    			else 
										    			{
										    				$('.response').html('<div class="alert alert-danger danger-rel">'+data1['msg']+'</div>');
										    			}
													}
												});
							                }
							            }, {
							            	icon: 'glyphicon glyphicon-remove-sign',
							                label: 'No',
							                action: function(dialogItself){
							                	$('input#gcbarcodever').select();
							                	$('button.releasedbtn').prop('disabled',false);
							                    dialogItself.close();
							                }
							            }]
							        });
								}
								else 
								{
									$('.response').html('<div class="alert alert-danger danger-rel">'+data['msg']+'</div>');
									$('button.releasedbtn').prop('disabled',false);
								}
							}
						});
					}
					else 
					{
						$('.response').html('<div class="alert alert-danger danger-rel">GC Barcode number must be at least 13 characters long.</div>');
						$('button.releasedbtn').prop('disabled',false);
					}
				}
				else 
				{
					$('.response').html('<div class="alert alert-danger danger-rel">Please input GC Barcode #.</div>');
					$('button.releasedbtn').prop('disabled',false);
				}
				$('input#gcbarcodever').select();

				return false;
			});
		</script>

	<?php
}

function promoList($link)
{
	$promo = getPromo($link);

	?>
		<div class="row form-container">
	    	<div class="col-md-12">
	            <div class="panel with-nav-tabs panel-info">
	                <div class="panel-heading">
	                    <ul class="nav nav-tabs">
	                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Promo List</a></li>
	                        <!-- <li><a href="#tab2default" data-toggle="tab">Default 2</a></li> -->
	                    </ul>	                    
	                </div>
	                <div class="panel-body">
	                    <div class="tab-content">
	                        <div class="tab-pane fade in active" id="tab1default">
								<div class="row">
									<div class="col-sm-12">
										<table class="table dataTable no-footer" id="gcrec">
											<thead>
												<tr>
													<th>Promo No.</th>
													<th>Promo Name</th>
													<th>Date Created</th>
													<th>Group</th>
													<th>Created By</th>
													<th></th>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($promo as $p): ?>
													<tr>
														<td><?php echo threedigits($p->promo_id); ?></td>
														<td><?php echo ucwords($p->promo_name); ?></td>
														<td><?php echo _dateFormat($p->promo_date); ?></td>
														<td><?php echo $p->promo_group; ?></td>
														<td><?php echo ucwords($p->firstname.' '.$p->lastname);?></td>
														<td><i class="fa fa-fa fa-eye faeye" title="View" onclick="viewpromo(<?php echo $p->promo_id; ?>);"></i></td>
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

		    $('#gcrec').dataTable( {
		        "pagingType": "full_numbers",
		        "ordering": false,
		        "processing": true
		    });
		</script>

	<?php
}

function addNewPromo($link,$todays_date)
{
	$denom = getAllDenomination($link);
	$promoNum = generatePromoNum($link);

	?>
		<div class="row form-container">
	    	<div class="col-md-12">
	            <div class="panel with-nav-tabs panel-info">
	                <div class="panel-heading">
	                    <ul class="nav nav-tabs">
	                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Add New Promo GC</a></li>
	                        <!-- <li><a href="#tab2default" data-toggle="tab">Default 2</a></li> -->
	                    </ul>	                    
	                </div>
	                <div class="panel-body">
	                    <div class="tab-content">
	                        <div class="tab-pane fade in active" id="tab1default">
	                        	<form class="form-horizontal" action="../ajax.php?action=newpromo" id="newpromo">
		                        	<div class="row">
										<div class="col-xs-7">
											<div class="form-group">
												<label class="col-xs-4 control-label">Promo No:</label>
												<div class="col-xs-5">
											    	<input type="text" class="form-control formbot" name="promono" readonly="readonly" value="<?php echo threedigits($promoNum); ?>">
												</div>
											</div>	
											<div class="form-group">
												<label class="col-xs-4 control-label">Date Created</label>
												<div class="col-xs-6">
													<input value="<?php echo _dateFormat($todays_date); ?>" type="text" class="form-control formbot input-sm" readonly="readonly">                
												</div>
											</div>	
											<div class="form-group">
												<label class="col-xs-4 control-label"><span class="requiredf">*</span>Draw Date</label>
												<div class="col-xs-6">
													<input type="text" class="form form-control inptxt input-sm ro from_date" id="" data-date-format="MM dd, yyyy" name="draw_date" readonly="readonly" required>              
												</div>
											</div>	
											<div class="form-group">
												<label class="col-xs-4 control-label"><span class="requiredf">*</span>Date Notified (Winners)</label>
												<div class="col-xs-6">									
													<input type="text" class="form form-control inptxt input-sm ro to_date" id="datenoted" data-date-format="MM dd, yyyy" name="datenoted" readonly="readonly" required>                             
												</div>
											</div>				
											<div class="form-group">
												<label class="col-xs-4 control-label">Expiration Date</label>
												<div class="col-xs-6">									
													<input type="text" class="form form-control inptxt input-sm ro" id="expire" data-date-format="MM dd, yyyy" name="expire" readonly="readonly" required="">                             
												</div>
											</div>
											<div class="form-group">
												<label class="col-xs-4 control-label"><span class="requiredf">*</span>Promo Group</label>
												<div class="col-xs-4">
													<?php if($_SESSION['gc_usertype']=='8'): ?>
														<input name="textinput" type="text" value="<?php $group = getField($link,'usergroup','users','user_id',$_SESSION['gc_id']);
                                                            echo ' '.$group; ?>" class="form-control input-sm inptxt" readonly="readonly">  												
													<?php else: ?>
														<select class="form form-control inptxt input-sm promog" name="group" required>
															<option value="">-Select-</option>
															<option value="1">Group 1</option>
															<option value="2">Group 2</option>
														</select>
													<?php endif; ?>								
												</div>
											</div>
											<div class="form-group">
												<label class="col-xs-4 control-label"><span class="requiredf">*</span>Promo Name</label>
												<div class="col-xs-8">
													<input type="text" class="form-control formbot reqfield" name="promoname" id="promoname"  required autocomplete="off">
												</div>
											</div>
											<div class="form-group">
												<label class="col-xs-4 control-label"><span class="requiredf">*</span>Details</label>
												<div class="col-xs-8">
													<textarea class="form form-control formbot reqfield textareah" name="notes" required></textarea>
												</div>
											</div>	
											<div class="form-group">
												<label class="col-xs-4 control-label">Prepared By</label>
												<div class="col-xs-6">
													<input type="text" class="form-control formbot" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>" readonly="readonly">
												</div>
											</div>
											<div class="form-group">								
												<div class="col-xs-offset-8 col-xs-4">
												<button type="submit" class="btn btn-block btn-primary submitbut">
													<span class="glyphicon glyphicon-log-in"></span> &nbsp; Submit
												</button>
												</div>
											</div>
											<div class="response">
											</div>
										</div>
										<div class="col-xs-5">
											<table class="table tnewpromo" id="tablestyle">
												<thead>
													<tr>
														<th>Denomination</th>
														<th><span class="requiredf">*</span>Scanned GC</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($denom as $d): ?>
														<tr>
															<td><span class="dentd">&#8369 <?php echo number_format($d->denomination,2); ?></span></td>
															<td><input type="text" class="form-control formbot input-sm width100 sc<?php echo $d->denom_id; ?>" value="0" readonly="readonly"></td>
														</tr>						
													<?php endforeach ?>
												</tbody>
											</table>
											<div class="col-xs-12">
												<div class="row">
													<div class="col-xs-6">
														<button class="btn btn-default btn-block btn-info" type="button" onclick="addPromoGC(<?php echo $promoNum; ?>)"><span class="glyphicon glyphicon-plus"></span> &nbsp; Add GC</button>
													</div>
													<div class="col-xs-6">
														<button class="btn btn-default btn-block btn-danger" type="button" onclick="viewScannedGCForPromo()"><span class="fa fa-barcode"></span> &nbsp; Scanned GC</button>
													</div>
												</div>
											</div>
										</div>
		                        	</div>
	                        	</form>
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

			$.ajax({
				url:'../ajax.php?action=deleteByIdTempPromo'
			});

			$(".from_date").datepicker({
			    format: 'MM d, yyyy',
			    autoclose: true,
			}).on('changeDate', function (selected) {
				$(".to_date").val("");
				$("#expire").val("");
			    var startDate = new Date($(this).val());
			    var now = new Date(startDate.getFullYear(), nowTemp.getMonth(), nowTemp.getDate()+1, 0, 0, 0, 0);
			    $('.to_date').datepicker('setStartDate', startDate);
			}).on('clearDate', function (selected) {
			    $('.to_date').datepicker('setStartDate', null);
			});

			$(".to_date").datepicker({
			    format: 'MM d, yyyy',
			    autoclose: true,
			});

			var datenotenum = 0
			$('#datenoted').change(function(){		
				datenotenum++;
				if(datenotenum==1)
				{
					var datenotified = $(this).val();
					$.ajax({
						url:'../ajax.php?action=getpromoexpirationdate',
						data:{datenotified:datenotified},
						type:'POST',
						success:function(data)
						{
							console.log(data);
							var data = JSON.parse(data);
							$('#expire').val(data['msg']);

						}
					});
				}
				if(datenotenum==3)
				{
					datenotenum=0;
				}
			});

			function addPromoGC(id)
			{
				var group = $('select.promog').val();
				if(group!='')
				{
						BootstrapDialog.show({
				        title: 'Promo GC Validation',
				        cssClass: 'modal-validate-store',
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
				            'pageToLoad': '../dialogs/scangcpromo.php?id='+id+'&group='+group
				        },

				        onshown: function(dialogRef){
				        	$('#gcbarcode').inputmask();
				        	$('#gcbarcode').focus();
				        }, 
				        onhidden: function(dialog) {
							$('#promoname').focus();
				        },
				        buttons: [{
				            icon: 'glyphicon glyphicon-ok-sign',
				            label: 'Submit',
				            cssClass: 'btn-primary',
				            hotkey: 13,
				            action:function(dialogItself){
				            	$button = this;
				            	$button.disable();
				            	var formURL = $('form#promovalidate').attr('action'), formData = $('form#promovalidate').serialize();
				            	if($('#gcbarcode').val()==undefined)
				            	{
				            		$button.enable();
				            		return false;
				            	}
				            	if($('#gcbarcode').val().length == 13)
				            	{
				            		$.ajax({
				            			url:formURL,
				            			data:formData,
				            			type:'POST',
				            			success:function(data){
				            				console.log(data);
				            				var data = JSON.parse(data);
				            				if(data['stat'])
				            				{
				            					$('.response-validate').html('<div class="alert alert-success">'+data['msg']+'</div>');
				            					var qty = parseInt($('.sc'+data['den']).val());
				            					qty++;
				            					$('.sc'+data['den']).val(qty);
				            					if (restrictback ==0) restrictback = 1; 
				            				}	
				            				else 
				            				{
				            					$('.response-validate').html('<div class="alert alert-danger">'+data['msg']+'</div>');
				            				}            				
				            			}
				            		});
				            	}
				            	else 
				            	{
				            		$('.response-validate').html('<div class="alert alert-danger">GC Barcode must be 13 characters long.</div>');
				            	}
				            	$button.enable();
				            	$('#gcbarcode').select();
				            }
				        }]

				    });
				}
				else 
				{
					alert('Please select promo group first.');
				}
			}

			function viewScannedGCForPromo()
			{
			    BootstrapDialog.show({
			        title: 'GC List',
			        cssClass: 'modal-validate-store',
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
			            'pageToLoad': '../dialogs/view-scanned-gc-for-promo.php'
			        },
			        onshown: function(dialogRef){

			        },
			        buttons: [{
			        	icon: 'glyphicon glyphicon-remove-sign',
			            label: 'Close',
			            action: function(dialogItself){
			                dialogItself.close();
			            }
			        }]
			    });
			}

			$('.form-container').on('submit','form#newpromo',function(){
				var  formURL = $(this).attr('action'), formData = $(this).serialize();
				$('.response').html('');

				if($('input[name=draw_date]').val().trim()=='' || $('input[name=datenoted]').val().trim()=='')
				{
					$('.response').html('<div class="alert alert-danger">Please input draw date and date notified.</div>');
					return false;
				}				

				var notEmpty = true, haScanned=false;

				$('.width100').each(function(){
					if($(this).val()!='0')
					{
						haScanned = true;
					}
				});

				if(haScanned)
				{
					$('button.submitbut').prop('disabled',true);
			        BootstrapDialog.show({
			        	title: 'Confirmation',
			            message: 'Are you sure you want to create new promo?',
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
			                	// double check if has scanned gc
			                	$.ajax({
			                		url:formURL,
			                		data:formData,
			                		type:'POST',
			                		success:function(data)
			                		{
			                			console.log(data);
			                			var data = JSON.parse(data);
			                			if(data['st'])
			                			{				
			                				restrictback = 0;		      
											var dialog = new BootstrapDialog({
								            message: function(dialogRef){
								            var $message = $('<div>'+data['msg']+'</div>');			        
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
						                    	window.location = 'gcpromo.php';
						               		}, 1700);						          
			                			}
			                			else 
			                			{
			                				$('.response').html('<div class="alert alert-danger">'+data['msg']+'</div>');
			                				 $('button.submitbut').prop('disabled',false);
			                			}
			                		}
			                	});
			                }
			            }, {
			            	icon: 'glyphicon glyphicon-remove-sign',
			                label: 'No',
			                action: function(dialogItself){
			                    dialogItself.close();
			                    $('button.submitbut').prop('disabled',false);
			                }
			            }]
			        });			
				}
				else 
				{
					$('.response').html('<div class="alert alert-danger">Please scan gc for this promo.</div>');
					$('button.submitbut').prop('disabled',false);
				}

				return  false;
			});
		</script>
	<?php
}

function promogcrequest($link,$todays_date)
{

	$denoms = getAllDenomination($link);

	?>
		<div class="row form-container">
	    	<div class="col-md-12">
	            <div class="panel with-nav-tabs panel-info">
	                <div class="panel-heading">
	                    <ul class="nav nav-tabs">
	                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Promo GC Request Form</a></li>
	                        <!-- <li><a href="#tab2default" data-toggle="tab">Default 2</a></li> -->
	                    </ul>	                    
	                </div>
	                <div class="panel-body">
	                    <div class="tab-content">
	                        <div class="tab-pane fade in active" id="tab1default">
	                        	<div class="row">
							        <div class="col-sm-8">
							            <form class="form-horizontal" id="promoreqForm" method='POST' action="../ajax.php?action=promoRequest">
							                <input type="hidden" name="totpromoreq" id="totpromoreq" value="0"> 
							                <div class="form-group">
												<label class="col-sm-3 control-label">RFPROM No.</label>  
												<div class="col-sm-3">
													<input value="<?php echo getPromoGCRequestNo($link); ?>" name="preqnum" type="text" class="form-control inptxt input-sm" readonly="readonly">              
												</div>
							                </div>
							                <div class="form-group">
												<label class="col-sm-3 control-label">Date Requested:</label>  
												<div class="col-sm-4">
													<input value="<?php echo _dateformat($todays_date); ?>" type="text" class="form-control inptxt input-sm" readonly="readonly">         
												</div>
							                </div>          
							                <div class="form-group">
												<label class="col-sm-3 control-label"><span class="requiredf">*</span>Date Needed:</label>  
												<div class="col-sm-4">                  
													<input type="text" class="form form-control inptxt input-sm ro" id="dp1" data-date-format="MM dd, yyyy" name="date_needed" readonly="readonly" required>
												</div>
							                </div>      
							                <div class="form-group">
												<label class="col-sm-3 control-label">Upload Scan Copy:</label>  
												<div class="col-sm-4">
													<input id="pics" type="file" name="docs[]" accept="image/*" class="form-control inptxt input-sm" />
												</div> 
							                </div> 
							                <div class="form-group">
												<label class="col-sm-3 control-label"><span class="requiredf">*</span>Remarks:</label>  
												<div class="col-sm-6">
													<input name="remarks" type="text" class="form-control inptxt input-sm" required autocomplete="off" autofocus>                
												</div> 
							                </div>
							                <div class="form-group">
												<label class="col-sm-3 control-label"><span class="requiredf">*</span>Promo Group:</label>  
												<div class="col-sm-4">       
													<?php if($_SESSION['gc_usertype']=='8'): ?>
														<input name="textinput" type="text" value="<?php $group = getField($link,'usergroup','users','user_id',$_SESSION['gc_id']);
                                                            echo ' '.$group; ?>" class="form-control input-sm inptxt" readonly="readonly">  												
													<?php else: ?>
														<select class="form form-control inptxt input-sm promog" name="group" required>
															<option value="">-Select-</option>
															<option value="1">Group 1</option>
															<option value="2">Group 2</option>
														</select>
													<?php endif; ?>
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
													<input class="form form-control inptxt denfield" id="num<?php echo $d->denom_id; ?>" value="0" name="denoms<?php echo $d->denom_id; ?>" autocomplete="off" />
												</div>                   
											</div>                  
							                <?php endforeach ?>
											<div class="form-group">
												<label class="col-sm-3 control-label">Prepared by:</label>  
												<div class="col-sm-4">
													<input name="textinput" type="text" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>" class="form-control input-sm inptxt" readonly="readonly">                
												</div>                    
												<div class="col-sm-4">
													<button id="btn" type="submit" class="btn btn-block btn-primary"><span class="glyphicon glyphicon-log-in"></span> &nbsp;Submit </button>
												</div> 
											</div>
							            </form>
										<div class="response">
										</div>
									</div>
									<div class="col-sm-4">
										<div class="box bot-margin">
											<div class="box-header"><h4><i class="fa fa-inbox"></i> Total Promo GC Request</h4></div>
												<div class="box-content">
													<h3 class="current-budget mbot">&#8369 <span id="totpromo">0.00</span></h3>    
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
			var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

			var checkin = $('#dp1').datepicker({

			    beforeShowDay: function (date) {
			        return date.valueOf() >= now.valueOf();
			    },
			    autoclose: true

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

			$('.denfield').inputmask("integer", { allowMinus: false,autoGroup: true, groupSeparator: ",", groupSize: 3,placeholder:'0' });

			$('.form-container').on('submit','form#promoreqForm',function(event)
			{
				event.preventDefault()
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

		        BootstrapDialog.show({
		        	title: 'Confirmation',
		            message: 'Are you sure you want to submit Promo GC request?',
		            closable: true,
		            closeByBackdrop: false,
		            closeByKeyboard: true,
		            onshow: function(dialog) {
		                // dialog.getButton('button-c').disable();
		                $('#btn').prop("disabled",true);
		            },
		            onhidden: function(dialog){
		            	$('#btn').prop("disabled",false);
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

		    function addCommas(nStr)
		    {
		        nStr += '';
		        x = nStr.split('.');
		        x1 = x[0];
		        x2 = x.length > 1 ? '.' + x[1] : '';
		        var rgx = /(\d+)(\d{3})/;
		        while (rgx.test(x1)) {
		            x1 = x1.replace(rgx, '$1' + ',' + '$2');
		        }
		        return x1 + x2;
		    }
		</script>

	<?php 
}

function _promoLedger($link)
{		

	$promotag = getField($link,'usergroup','users','user_id',$_SESSION['gc_id']);	

// SELECT 
// 	SUM(denomination.denomination * promo_gc_request_items.pgcreqi_qty) as sum,
// 	CONCAT(users.firstname,' ',users.lastname) as reqby,
// 	promo_gc_request.pgcreq_datereq
// FROM 
// 	promo_gc_request
// INNER JOIN
// 	promo_gc_request_items
// ON
// 	promo_gc_request_items.pgcreqi_trid = promo_gc_request.pgcreq_id
	
// INNER JOIN
// 	denomination
// ON
// 	denomination.denom_id = promo_gc_request_items.pgcreqi_denom
// INNER JOIN
// 	users
// ON
// 	users.user_id = promo_gc_request.pgcreq_reqby
// WHERE 
// 	promo_gc_request.pgcreq_group_status = 'approved' 
// AND 
// 	promo_gc_request.pgcreq_group='1'

	$table = 'promo_ledger';
	$select = 'promo_ledger.promled_desc,
		promo_ledger.promled_debit,
		promo_ledger.promled_credit,
		promo_ledger.promled_trid';
	$where = "promo_gc_request.pgcreq_group = '".$promotag."'";
	$join = 'INNER JOIN
			promo_gc_request
		ON
			promo_gc_request.pgcreq_id = promo_ledger.promled_trid';
	$limit = '';

	$data = getAllData($link,$table,$select,$where,$join,$limit);

	?>

		<div class="row form-container">
	    	<div class="col-md-12">
	            <div class="panel with-nav-tabs panel-info">
	                <div class="panel-heading">
	                    <ul class="nav nav-tabs">
	                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Promo Ledger</a></li>
	                    </ul>	                    
	                </div>
	                <div class="panel-body">
	                    <div class="tab-content">
	                        <div class="tab-pane fade in active" id="tab1default">
								<table class="table table-adjust" id="promoLedger">
									<thead>
										<tr>
											<th>Ledger #</th>
											<th>Date</th>
											<th>Transaction</th>                                         
											<th>Debit</th>
											<th>Credit</th>
											<th>Balance</th>    
											<th></th>            
										</tr>
									</thead>
									<tbody class="store-request-list">
										<?php
											$ledgernum = 0; 
											$total = 0;
											foreach ($data as $key): 	
												$date = '';		
												$debit = 0;
												$credit = 0;	

											if($key->promled_desc=='promo request approval'):
												$query = $link->query(
													"SELECT 
														approved_request.reqap_date
													FROM 
														promo_gc_request 
													INNER JOIN
														approved_request
													ON
														approved_request.reqap_trid = promo_gc_request.pgcreq_id
													WHERE 
														promo_gc_request.pgcreq_id='$key->promled_trid'
													AND
														approved_request.reqap_approvedtype='promo gc preapproved'
												");

												if($query)
												{													
													$date = $query->fetch_object();
													$date = $date->reqap_date;
													$total += $key->promled_debit;
													if($key->promled_debit==0)
													{
														$debit = "";
													}
													else 
													{
														$debit = number_format($key->promled_debit,2);
													}
												}												
										?>
											<?php else: ?>

											<?php endif; ?> 

											<?php if(trim($date)!=''): ?>
												<tr>
													<td><?php echo sprintf("%03d", ++$ledgernum);?></td>
													<td><?php echo _dateFormat($date); ?></td>
													<td><?php echo ucwords($key->promled_desc); ?></td>
													<td><?php echo $debit; ?></td>
													<td></td>
													<td><?php echo number_format($total,2); ?></td>
													<td></td>
												</tr>
											<?php endif; ?>
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
			$.extend( $.fn.dataTableExt.oStdClasses, {	  
			    "sLengthSelect": "selectsup"
			});
		    $('#promoLedger').dataTable( {
		        "pagingType": "full_numbers",
		        "ordering": false,
		        "processing": true
		    });
		</script>
	<?php
}
