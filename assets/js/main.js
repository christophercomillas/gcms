restrictback = 0;
$(document).ready(function(){
	window.onbeforeunload = function() { if(restrictback==1){
		return "You work will be lost.";
	} };
	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

	var checkin = $('#dp1').datepicker({

	    beforeShowDay: function (date) {
	        return date.valueOf() >= now.valueOf();
	    },
	    autoclose: true

	});

	$('#amount').inputmask();
	$("input[name=dstart], input[name=dend]").inputmask("m/d/y").val('__/__/_____').prop('disabled', false);
	$("input[name=dstart], input[name=dend]").prop('disabled',true);
	$('.denfield').inputmask("integer", { allowMinus: false,autoGroup: true, groupSeparator: ",", groupSize: 3 });

	$.extend( $.fn.dataTableExt.oStdClasses, {	  
	    "sLengthSelect": "selectsup"
	});
    $('#appbudgetreq, #appprodreq,#pendgc, #storeRec, #storePendingRequest, #storeRequestList, #cancelledgcreq').dataTable( {
        "pagingType": "full_numbers",
        "ordering": false,
        "processing": true
    });

	$('.form-container').on('submit','form#budgetRequestForm',function(){
		var  formUrl = $(this).attr('action'), formData = new FormData($(this)[0]);
		$('.response').html('');
		if($('#dp1').val().length>0)
		{		
			var bud = $('input[name=requestBudget]').val();
			bud = bud.replace(/,/g , "");	
			bud = parseFloat(bud).toFixed(2);
			if(bud > 0)
			{
		        BootstrapDialog.show({
		        	title: 'Confirmation',
		            message: 'Are you sure you want to submit this budget request?',
		            closable: true,
		            closeByBackdrop: false,
		            closeByKeyboard: true,
		            onshow: function(dialog) {
		                // dialog.getButton('button-c').disable();
		                $('button#btn').prop('disabled',true);
		            },
		            onhidden:function(dialog){
		            	$('button#btn').prop('disabled',false);
		            },
		            buttons: [{
		                icon: 'glyphicon glyphicon-ok-sign',
		                label: 'Yes',
		                cssClass: 'btn-primary',
		                hotkey: 13,
		                action:function(dialogItself){   
		                	$button = this;
		                	$button.disable();             	
		                	dialogItself.close();
						    	$.ajax({
						    		url:formUrl,
						    		type:'POST',
									data: formData,
									enctype: 'multipart/form-data',
								    async: false,
								    cache: false,
								    contentType: false,
								    processData: false,
						    		beforeSend:function(){

						    		},
						    		success:function(data){			
						    			var data = JSON.parse(data);
						    			if(data['st'])
						    			{
											var dialog = new BootstrapDialog({
								            message: function(dialogRef){
								            var $message = $('<div>Budget Request Successfully Saved.</div>');			        
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
						                    	window.location.href ='index.php';
						               		}, 1700);
										} 
										else 
										{
											setTimeout(function(){
											    $('.response').html('<div class="alert alert-danger alert-dismissable">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
											}, 400);
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
			}
			else 
			{
				$('.response').html('<div class="alert alert-danger alert-dismissable">Invalid amount.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
				$('input[name=requestBudget').select();		
			}
		}
		else 
		{
			$('.response').html('<div class="alert alert-danger alert-dismissable">Date Needed field is required.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
			$('#dp1').focus();
		}		
		return false;
	});

	$('.form-container').on('submit','form#gcRequestStat',function(){
		var formUrl = $(this).attr('action'), formData = new FormData($(this)[0]);
		var reqId = $('#reqid').val();
		var emptyfield = false, x = $('#app-checkby').val(), y = $('#app-apprby').val();
		var status = $('#status').val();		
		if(status=='1'){
			if(x===''||y===''){
				$('.response').html('<div class="alert alert-danger danger-x">Please input all fields.</div>');
			} else {
		        BootstrapDialog.show({
		        	title: 'Confirmation',
		            message: 'Are you sure you want to approve gc request?',
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
								$.ajax({
						    		url:formUrl,
						    		type:'POST',
									data: formData,
									enctype: 'multipart/form-data',
								    async: false,
								    cache: false,
								    contentType: false,
								    processData: false,
									beforeSend:function(){

									},
									success:function(response){
										var res = response.trim();
										
										if(res=='success'){				
											var dialog = new BootstrapDialog({
								            message: function(dialogRef){
								            var $message = $('<div>GC Request Successfully Approved.</div>');			        
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

										} else {

											setTimeout(function(){
											    $('.response').html('<div class="alert alert-danger" id="danger-x">'+res+'</div>');
											}, 400);
											
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
			}
	    } else {
	        BootstrapDialog.show({
	        	title: 'Confirmation',
	            message: 'Are you sure you want to cancel gc request?',
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
	                	$.ajax({
	                		url:'../ajax.php?action=storegcreq',
	                		type:"POST",
	                		data:{reqId:reqId},
	                		beforeSend:function()
	                		{

	                		},
	                		success:function(data)
	                		{
						   		var res = data.trim();

						   		if(res=='success')
						   		{
						   			dialogItself.close();
									var dialog = new BootstrapDialog({
						            message: function(dialogRef){
						            var $message = $('<div>GC Request Cancelled.</div>');			        
						                return $message;
						            },
						            closable: false
							        });
							        dialog.realize();
							        dialog.getModalHeader().hide();
							        dialog.getModalFooter().hide();
							        dialog.getModalBody().css('background-color', '#86E2D5');
							        dialog.getModalBody().css('color', '#000');
							        dialog.open();
							        setTimeout(function(){
				                    	dialog.close();
				               		}, 1500);
				               		setTimeout(function(){
				                    	window.location.href = 'index.php';
				               		}, 1700);	        	
						   		}
						   		else 
						   		{
						   			alert(res);
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
	    }
		return false;
	});

	$('.form-container').on('submit','form#updateRequestForm',function(){
		var formUrl = $(this).attr('action'), formData = new FormData($(this)[0]);	
		$('.response').html('');	
		var bud = $('input[name=requestBudget]').val();
		bud = bud.replace(/,/g , "");	
		bud = parseFloat(bud).toFixed(2);
		if(bud>0)
		{
	        BootstrapDialog.show({
	        	title: 'Confirmation',
	            message: 'Are you sure you want to update budget request?',
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
				    	$.ajax({
				    		url:formUrl,
				    		type:'POST',
							data: formData,
							enctype: 'multipart/form-data',
						    async: false,
						    cache: false,
						    contentType: false,
						    processData: false,
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
						            var $message = $('<div>Budget Request Successfully Updated.</div>');			        
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
				               		$('.response').html('<div class="alert alert-danger alert-dismissable">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
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
		}
		else 
		{
			$('.response').html('<div class="alert alert-danger alert-dismissable">Invalid amount.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
		}

		return false;
	});

	$('.form-container').on('submit','form#prodEntryForm',function(){
		$('.response').html('');
		var formUrl = $(this).attr('action'), formData = new FormData($(this)[0]);
		var hasqty = false;

		//check first production request has already made.
		$.ajax({
			url:'../ajax.php?action=checkproductionrequestStatus',
			success:function(data)
			{
				console.log(data);
				var data = JSON.parse(data);
				if(data['st'])
				{
					var denfield='';
					$('.denfield').each(function(){
						denfield = $(this).val().trim();
						if(denfield!=0)
						{
							if(denfield.length!=0){
								hasqty = true;
								return;
							}
						}
					});

					if(hasqty)
					{	
						if($('#dp1').val().length>0)
						{
							var totprod = 0;
							var stotal = 0;
							$('input.qty').each(function(){
								var qty = $(this).val();
								qty = qty.replace(/,/g , "");
								var denval = $(this).siblings('input.denval').val();
								var stotal = qty * denval;
								totprod +=stotal;			
							});


							if(totprod <= $('#_budget').val())
							{					

						        BootstrapDialog.show({
						        	title: 'Confirmation',
						            message: 'Are you sure you want to submit this production request?',
						            closable: true,
						            closeByBackdrop: false,
						            closeByKeyboard: true,
						            onshow: function(dialog) {
						                // dialog.getButton('button-c').disable();
						            },
						            onshown: function(dialog){
						            	$('button#btn').prop('disabled',true);
						            },
						            onhidden: function(dialog){
						            	$('button#btn').prop('disabled',false);
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
									    		url:formUrl,
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
											            var $message = $('<div>GC Production Request Saved.</div>');			        
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
							}
							else 
							{
								$('.response').html('<div class="alert alert-danger" id="danger-x">Total Production Request is bigger than Budget.</div>');
							}
						}
						else 
						{
							$('.response').html('<div class="alert alert-danger" id="danger-x">Date Needed field is required.</div>');												
						}
					}
					else 
					{
						 $('.response').html('<div class="alert alert-danger" id="danger-x">Please input at least one denomination quantity field.</div>');
					}
				}
				else 
				{
					 $('.response').html('<div class="alert alert-danger" id="danger-x">You have pending production request.</div>');
				}
			}
		});   
		return false;  
	});

	$('.form-container').on('submit','form#updateProdEntryForm',function(){
		$('.response').html('');
		var formUrl = $(this).attr('action'), formData = new FormData($(this)[0]);
		var hasqty = false;
		var denfield='';
		$('.denfield').each(function(){
			denfield = $(this).val().trim();
			if(denfield!=0)
			{
				if(denfield.length!=0){
					hasqty = true;
					return;
				}
			}
		});

		if(hasqty)
		{
			var budget = $('#_budget').val();
			var sum = 0, sum1=0,mul=0;
			$('input.qty').each(function(){
				var qty = $(this).val();
				qty = qty.replace(/,/g , "");
				var denval = $(this).siblings('input.denval').val();
				var sum = qty * denval;
				sum1 +=sum;			
			});
			// for(var $x=1;$x<=5;$x++) {
			// 	var inputs = $("#num"+$x).val();
			// 	inputs = inputs.replace(/,/g , "");
			// 	sum = sum +inputs;
			// 	mul = inputs * $("#m"+$x).val();
			// 	sum1 = sum1 +mul;
			// }

			if(sum1<=budget)
			{
		        BootstrapDialog.show({
		        	title: 'Confirmation',
		            message: 'Are you sure you want to update production request?',
		            closable: true,
		            closeByBackdrop: false,
		            closeByKeyboard: true,
		            onshow: function(dialog) {
		                // dialog.getButton('button-c').disable();
		            },
		            onshown: function(dialog) {
		            	$('button#btn').prop('disabled',true);
		            },
		            onhidden: function(dialog){
		            	$('button#btn').prop('disabled',false);
		            },
		            buttons: [{
		                icon: 'glyphicon glyphicon-ok-sign',
		                label: 'Yes',
		                cssClass: 'btn-primary',
		                hotkey: 13,
		                action:function(dialogItself){ 
		                	dialogItself.close();                	
					    	$.ajax({
					    		url:formUrl,
					    		type:'POST',
								data: formData,
								enctype: 'multipart/form-data',
							    async: true,
							    cache: false,
							    contentType: false,
							    processData: false,
					    		beforeSend:function(){
					    			
					    		},
					    		success:function(data){
					    			console.log(data);
					    			var data = JSON.parse(data);
					    			if(data['st'])
					    			{
										var dialog = new BootstrapDialog({
							            message: function(dialogRef){
							            var $message = $('<div>Production Request Successfully Updated.</div>');			        
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
			}
	        else 
	        {
				$('.response').html('<div class="alert alert-danger" id="danger-x">Total production amount is greater than budget.</div>');
				$('.denfield').each(function(){
					$(this).val(0);
				})
	        }	
		}
		else 
		{
			$('.response').html('<div class="alert alert-danger" id="danger-x">Please input at least one quantity field.</div>');
		}
		return false;
	});

	$('table.table tbody.store-request-list').on('click','tr td button.app-gc-can',function(){
		var id = $(this).attr('app-id');
        BootstrapDialog.show({
        	title: 'Cancelled GC Request Details',
            message: $('<div></div>').load('../dialogs/view-cancelled-gc-request.php?id='+id),
     	    cssClass: 'modal-details',
	        closable: true,
	        closeByBackdrop: false,
	        closeByKeyboard: true,
	        onshow: function(dialog) {
	            // dialog.getButton('button-c').disable();
	        },
            onshown: function(dialogRef){

            },
	        buttons:[ {
	        	icon: 'glyphicon glyphicon-remove-sign',
	            label: 'Close',
	            action: function(dialogItself){
	                dialogItself.close();
	            }
	        }]
        });
	});

	$('.form-container').on('submit','form#salesreport',function(){	
		$('.response').html('');
		// form validation
		var dstart='', dend='', gcsales=false,reval=false,refund=false,trans='',denom = 0;
		if($("select[name=denom]").val()=='')
		{
			$('.response').html('Please select denomination.');
			return false;
		}

		denom = $("select[name=denom]").val();

		var type = $('input[name="reportype[]"]:checked').length;
		
		if(type==0)
		{
			$('.response').html('<div class="alert alert-danger">Please check at least one report type.</div>');
			return false;
		}

		$('input[name="reportype[]"]:checked').each(function(){
			if($(this).val()=='gcsales')
			{
				gcsales=true;
			}
			else if($(this).val()=='reval')
			{
				reval=true;
			}
			else if($(this).val()=='refund')
			{
				refund=true;
			}
			
		});

		if(!$('input[name=datetrans]').is(':checked'))
		{
			$('.response').html('<div class="alert alert-danger">Please check transaction.</div>');
			return false;
		}

		var trans = $('#datetrans:checked').val();
		if($('#datetrans:checked').val()=='range')

		{
			if(validDate1($('#dstart').val()) && validDate1($('#dend').val()))
			{
				$('.response').html('<div class="alert alert-danger">Date Start / Date End is invalid.</div>');
				return false;
			}

			if(!validateDate($('#dstart').val()) || !validateDate($('#dend').val()))
			{
				$('.response').html('<div class="alert alert-danger">Date Start / Date End is invalid.</div>');
				return false;
			}

			if(!validDate($('#dstart').val(),$('#dend').val()))
			{
				$('.response').html('<div class="alert alert-danger">Date Start is greater than Date End.</div>');
				return false;
			}

			dstart = $('#dstart').val();
			dend = $('#dend').val();
		}

		location.href='salesreport.php?denom='+denom+'&gcsales='+gcsales+'&reval='+reval+'&refund='+refund+'&trans='+trans+'&dstart='+dstart+'&dend='+dend;

		return false;
	});


	$('table.table tbody.store-request-list').on('click','tr td button.appstore-rec',function(){
		var id = $(this).closest('tr').attr('app-id');
        BootstrapDialog.show({
        	title: 'Confirmation',
            message: 'Tag as received?',
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
                	//$(this).closest('td').html('Received');
                	$.ajax({
                		url:'../ajax.php?action=receivedGCByStore',
                		data:{id:id},
                		type:'POST',
                		success:function(response){
                			var res = response.trim();
                			if(res=='success')
                			{
								$("tbody.store-request-list").find("tr").each(function() {
									 if($(this).attr('app-id')==id)
									 {
									 	$(this).find('td:nth-child(7)').html('Received');
									 }
								});
			                	dialogItself.close();
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

		// $("tbody.store-request-list").find("tr").each(function() { //get all rows in table
		//     if(id == $(this).find('td button.appstore-rec').attr('app-id'))
		//     {
		//     	alert($('td').text());
		//     } 
		//     //gets the text out of the rating td for this row

		// });
		
	});

    $('table#storeRequestList').on('click','tbody tr td i#reprintrelgc',function(){
    	var id = $(this).attr('data-trid');
    	window.location = 'gcreleasedpdf.php?id='+id;
    });

	$('table#pendgc tr').click(function(){
		var id = $(this).attr("strequest"), streqstat = $(this).attr('streqstat');
		if(id.trim()=='')
		{
			return false;
		}
		if(streqstat!=2)
		{
	        BootstrapDialog.show({
	        	title: 'Confirmation',
	            message: 'Are you sure you want to Create Releasing Entry?',
	            closable: true,
	            closeByBackdrop: false,
	            closeByKeyboard: true,
	            onshow: function(dialog) {
	                // dialog.getButton('button-c').disable();

	            },
	            buttons: [{
	                icon: 'glyphicon glyphicon-ok-sign',
	                label: 'Ok',
	                cssClass: 'btn-primary',
	                hotkey: 13,
	                action:function(dialogItself){
	                	dialogItself.close();
	                	$.ajax({
	                		url:'../ajax.php?action=truncateTempRel'	                		
	                	});
						BootstrapDialog.show({
							title: '<i class="fa fa-share"></i> Releasing Entry',
							closable: true,
				            closeByBackdrop: false,
				            closeByKeyboard: false,
							cssClass: 'modal-details-rel',
				            message: function(dialog) {
				                var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
				                var pageToLoad = dialog.getData('pageToLoad');
								setTimeout(function(){
				                $message.load(pageToLoad);
								},1000);
				                return $message;
				            },
				            data: {
				                'pageToLoad': '../dialogs/srs.php?id='+id
				            },
					        onshow: function(dialog) {
					            // dialog.getButton('button-c').disable();
					        },
				            onshown: function(dialog){	
				            	setTimeout(function(){
				            		$('input#remark').focus();
				            	},1000);			
				            	restrictback = 1;	    
				           	},
					        onhidden: function(dialog) {
					            restrictback = 0;
					        },				       
					        buttons: [{
					            icon: 'glyphicon glyphicon-ok-sign',
					            label: 'Save',
					            cssClass: 'btn-primary',
					            hotkey: 13,
					            action:function(dialogItself1){
					            	$('.response').html('');
                        			var relid = $('input[name=releasenumber]').val();
					            	var $button = this;
					            	$button.disable();
					            	// save release
					            	var formData = new FormData($('form#gc_srr')[0]), formUrl = $('form#gc_srr').attr('action');
					            	var notEmpty = true, haScanned =false;
					            	var ifjpeg = true;
					            	if($('#upload').val()!=''){
						            	var ext = $('#upload').val().split('.').pop().toLowerCase();
						            	if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
											$('.response').html('<div class="alert alert-danger alert-dismissable">Please upload only image file type.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
                               				$button.enable();
                               				return  false;
										}
									}
	                                $('.form-container .reqfield').each(function(){
	                                    if($(this).val()=='')
	                                    {
	                                        notEmpty = false;                          	                                        
	                                    }
	                                });	
	                                if(notEmpty)
	                                {

                                		$("[class^='scangcx']").each(function(){
                                			if($(this).text()!='0')
                                			{
                                				haScanned = true;
                                			}	                                			
                                		});

                                		if(haScanned)
                                		{

                                			$hasError = false;
                                			$errormsg = '';

                                			if($('#paymenttypeStores').val()=='cash')
                                			{
                                				var amountrec = $('#amountrec').val().trim().replace(/,/g , "");
                                				
                                				if(isNaN(amountrec))
                                				{
                                					$hasError = true;
                                					$errormsg = 'Amount is not a number.';
                                				}
                                				else if(!parseFloat(amountrec) > 0)
                                				{
                                					$hasError = true;
                                					$errormsg = 'Insufficient amount.';
                                				}                                 				
                                			}
                                			else if($('#paymenttypeStores').val()=='check')
                                			{
                                				var amountrec = $('#camountrec').val().trim().replace(/,/g , "");
                                				
                                				if($('#bankname').val().trim()=='')
                                				{
                                					$hasError = true;
                                					$errormsg = 'Bank name is required.';
                                				}
                                				else if($('#baccountnum').val().trim()=='')
                                				{
                                					$hasError = true;
                                					$errormsg = 'Bank Account Number is required.';                                					
                                				}
                                				else if($('#cnumber').val().trim()=='')
                                				{
                                					$hasError = true;
                                					$errormsg = 'Check Number is required.';                                 					
                                				}
                                				else if(isNaN(amountrec))
                                				{
                                					$hasError = true;
                                					$errormsg = 'Check amount is not a number.';
                                				}
                                				else if(!parseFloat(amountrec) > 0)
                                				{
                                					$hasError = true;
                                					$errormsg = 'Insufficient amount.';                                					
                                				}                               					
                                			}
                                			else if($('#paymenttypeStores').val()=='jv')
                                			{
												if($('#jvcust').val()=='')  
												{
													$hasError = true;
													$errormsg = 'Please select customer.';
												}               				
                                			}                                			

                                			if($hasError)
                                			{
												$('.response').html('<div class="alert alert-danger alert-dismissable">'+$errormsg+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
	                               				$button.enable();	                                			
                                			}   
                                			else 
                                			{
										        BootstrapDialog.show({
										        	title: 'Confirmation',
										            message: 'Are you sure you want to save transaction.',
										            closable: true,
										            closeByBackdrop: false,
										            closeByKeyboard: true,
										            onshow: function(dialog) {
										                // dialog.getButton('button-c').disable();										            
										            },
										            onhidden: function(dialog) {
										            	$button.enable();
										            },
										            buttons: [{
										                icon: 'glyphicon glyphicon-ok-sign',
										                label: 'Ok',
										                cssClass: 'btn-primary',
										                hotkey: 13,
										                action:function(dialogItself){
										                	$buttons = this;
										                	$buttons.disable();

										                	dialogItself.close();
						                                	$.ajax({
						                                		url:formUrl,
						                                		data:formData,
						                                		type:'POST',
															    contentType: false,
															    processData: false,	                           
						                                		success:function(data)
						                                		{
						                                			console.log(data);
						                                			var data = JSON.parse(data) 

						                                			if(data['st'])
						                                			{
						                                				dialogItself1.close();
						                                				restrictback = 0;
																		var dialog = new BootstrapDialog({
															            message: function(dialogRef){
															            var $message = $('<div>Transaction Saved.</div>');			        
															                return $message;
															            },
															            closable:false
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
													                    	window.location.href = 'gcreleasedpdf.php?id='+relid;
													               		}, 1700);												          
						                                			}
						                                			else
						                                			{
						                                				$('.response').html('<div class="alert alert-danger alert-dismissable">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
						                                				$button.enable();
						                                				timeoutmsg('.response');
						                                			}
						                                		}
						                                	});									       

										                }
										            },{
										            	icon: 'glyphicon glyphicon-remove-sign',
										                label: 'Cancel',
										                action: function(dialogItself){
										                	$button.enable();
										                    dialogItself.close();
										                }
										            }]
											    });	                                 				
                                			}                            		

  	                           
                                		}
                                		else
                                		{
											$('.response').html('<div class="alert alert-danger alert-dismissable">Please scan GC.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
                               				$button.enable();	                                			
                                		}
	                                }	
	                                else{
	                                	$('.response').html('<div class="alert alert-danger alert-dismissable">Please fill all <span class="requiredf">*</span>required fields.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
	                                	$button.enable();
	                                }

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
	            }, {
	            	icon: 'glyphicon glyphicon-remove-sign',
	                label: 'Cancel',
	                action: function(dialogItself){
	                    dialogItself.close();
	                }
	            }]
	        });
		}
	});// end

	$('table.table tbody.store-request-list').on('click','tr td button.app-gcreq',function(){
		var id = $(this).closest('tr').attr('app-id');	
        BootstrapDialog.show({
        	title: 'Approved GC Request Details',
            message: $('<div></div>').load('../dialogs/view-approved-gcrequest.php?id='+id),
     	    cssClass: 'modal-details-pro',
	        closable: true,
	        closeByBackdrop: false,
	        closeByKeyboard: true,
	        onshow: function(dialog) {
	            // dialog.getButton('button-c').disable();
	        },
            onshown: function(dialogRef){
				$('.row.gc-app-pro').on('click','.col-sm-12 button#btn-gc-app-pro',function(){
					var id1 = $(this).attr('app-gc-id');
			        BootstrapDialog.show({
			            title: 'GC Barcode Nos.',
			            message: $('<div></div>').load('../dialogs/view-barcode-byrequest.php?id='+id1),
			            cssClass: 'modal-allocated-gc',
			            closable: true,
			            closeByBackdrop: false,
			            closeByKeyboard: true,
			            onshow: function(dialog) {
			                // dialog.getButton('button-c').disable();
			            },
			            onshown: function(dialogRef){
			                    $('#allocated-gc').dataTable({
			                        "pagingType": "full_numbers",
			                        "ordering": false,
			                        "processing": true,
			                        "iDisplayLength": 5
			                    });

			                    $("#allocated-gc_length").css("display", "none");
			            },
			            buttons:[ {
			                icon: 'glyphicon glyphicon-remove-sign',
			                label: 'Close',
			                action: function(dialogItself){
			                    dialogItself.close();
			                }
			            }]
			        });					
				});
            },
	        buttons:[ {
	        	icon: 'glyphicon glyphicon-remove-sign',
	            label: 'Close',
	            action: function(dialogItself){
	                dialogItself.close();
	            }
	        }]
        });
	});	

	$('table#storeRec').on('click','tbody tr',function(){	
		var id = $(this).attr('relid'), storeid = $(this).attr('storeid'), rec = $(this).attr('rec');
		if(rec==0)
		{
	        BootstrapDialog.show({
	        	title: 'Confirmation',
	            message: 'Are you sure you want to Create Store Receiving Entry?',
	            closable: true,
	            closeByBackdrop: false,
	            closeByKeyboard: true,
	            onshown: function(dialog) {
	            },
	            buttons: [{
	                icon: 'glyphicon glyphicon-ok-sign',
	                label: 'Ok',
	                cssClass: 'btn-primary',
	                hotkey: 13,
	                action:function(dialogItself){
	                	var $confirm = this;
	                	$confirm.disable();
	                	dialogItself.close();
		            	$.ajax({
		            		url:'../ajax.php?action=truncatetempReceived',
		            		data:{storeid:storeid},
		            		type:'POST'
		            	});
						BootstrapDialog.show({
							title: 'Store GC Receiving Module',
							closable: true,
				            closeByBackdrop: false,
				            closeByKeyboard: false,
							cssClass: 'modal-details-strel',
				            message: function(dialog) {
				                var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
				                var pageToLoad = dialog.getData('pageToLoad');
								setTimeout(function(){
				                $message.load(pageToLoad);
								},1000);
				                return $message;
				            },
				            data: {
				                'pageToLoad': '../dialogs/srgc.php?id='+id+'&'+'storeid='+storeid,
				            },
					        onshow: function(dialog) {
					            // dialog.getButton('button-c').disable();
					        },
				            onshown: function(dialog){		
				            	setTimeout(function(){
				            		$('input[name=checkedby]').focus();
				            	},1000);			
				            	restrictback = 1;    
				           	},
				           	onhidden: function(dialog)
				           	{
				           		restrictback = 0;
				           	},
					        buttons: [{
					            icon: 'glyphicon glyphicon-ok-sign',
					            label: 'Save',
					            cssClass: 'btn-primary',
					            hotkey: 13,
					            action:function(dialogItself1){
					            	var $button = this;
					            	$button.disable();
					            	$('.response').html('');			        
					            	// received gc				            	
					            	var formURL = $('form#recGCStore').attr('action'), formData = $('form#recGCStore').serialize();
					            	var qty = [], scan = [],flag = true,hasEmpty = false;

		                    		$("[class^='scangc']").each(function(){
										scan.push($(this).text());                        			
		                    		});						            	

		                    		$("[class^='qty']").each(function(){
										qty.push($(this).text());                        			
		                    		});
	        
		                    		for (var i = 0; i < scan.length; i++) {
		                    			if(parseInt(qty[i])!=parseInt(scan[i]))
		                    			{
		                    				flag = false;
		                    				break;
		                    			}
		                    		}

		                    		if(flag)
		                    		{
			                    		$(".reqfield").each(function(){
										    if($(this).val().trim()=='')
										    {
										    	hasEmpty = true;									   
										    }
			                    		});	  

			                    		if(!hasEmpty)
			                    		{
										    BootstrapDialog.show({
										    	title: 'Confirmation',
										        message: 'Are you sure you want to save transaction?',
										        closable: true,
										        closeByBackdrop: false,
										        closeByKeyboard: true,
										        onshow: function(dialog) {
										            // dialog.getButton('button-c').disable();
										        },
										        onhidden:function(dialog) {
										        	$button.enable();
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
										            		data:formData,
										            		type:'POST',
										            		success:function(data)
										            		{
										            			console.log(data);
										            			var data = JSON.parse(data);
										            			if(data['st'])	
										            			{				
										            				dialogItself1.close();
										            				restrictback = 0;				            				
																	var dialog = new BootstrapDialog({
														            message: function(dialogRef){
														            var $message = $('<div>Transaction Saved.</div>');			        
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
										            				$('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
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
			                    		}
			                    		else 
			                    		{
			                    			$('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">Please fill all form.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
			                				$button.enable();	
			                    		}              

		
		                    		}
		                    		else
		                    		{
					            		$('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">GC scanned must equal GC qty.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
					            		$button.enable();	
		                    		}       

					        	}
					        }, {					        	
					        	cssClass: 'btn-manager',
					            label: '<i class="fa fa-key"></i> Manager Key',	
					            action: function(dialogItself){
									BootstrapDialog.show({
										title: '<i class="fa fa-user"></i></i> Login Manager',
										closable: true,
							            closeByBackdrop: false,
							            closeByKeyboard: false,
										cssClass: 'modal-managerkey',
							            message: function(dialog) {
							                var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
							                var pageToLoad = dialog.getData('pageToLoad');
											setTimeout(function(){
							                $message.load(pageToLoad);
											},1000);
							                return $message;
							            },
							            data: {
							                'pageToLoad': '../dialogs/custodianmanagerkey.php'
							            },
								        onshown: function(dialog) {
											setTimeout(function(){
							                	$('input[name=username]').focus();
											},1000);
								        },
								        buttons: [{
								            icon: 'glyphicon glyphicon-ok-sign',
								            label: 'Submit',
								            cssClass: 'btn-primary',
								            hotkey: 13,
								            action:function(dialogItself){
								            	notEmpty = true;
								            	var formData = $('form#custodianmanager').serialize(), formURL = $('form#custodianmanager').attr('action');	
						                        var errormsg = [];
						                        $('.reqfieldmk').each(function(){
						                            if($(this).val()=='')                         
						                            {
						                                notEmpty = false;
						                                errormsg.push('Please fill form.');
						                                return false;
						                            }
						                        });

						                        if(notEmpty)
						                        {
						                        	//todo
						                        	$.ajax({
						                        		url:formURL,
						                        		data:formData,
						                        		type:'POST',
						                        		success:function(data){
						                        			var data = JSON.parse(data);
						                        			if(data['stat'])
						                        			{
						                        				dialogItself.close();
						                        				alert('xx');
						                        			}
						                        			else 
						                        			{
						                        				 $('.responsemanager').html('<div class="alert alert-danger alert-dismissable alert-no-bot"><ul class="ulleftpad14"><li class="leftpad0">'+data['msg']+'</li></ul></div>');													
						                        			}
						                        		}
						                        	});
						                        }
						                        else 
						                        {
						                            var erromsg = '';
						                            for(i=0; i<(errormsg.length); i++)
						                            {
						                                // erromsg+'<li>'+errormsg[i]+'</li>';
						                               erromsg += '<li class="leftpad0">'+errormsg[i]+'</li>';
						                            }
						                            $('.responsemanager').html('<div class="alert alert-danger alert-dismissable alert-no-bot"><ul class="ulleftpad14">'+erromsg+'</ul></div>');													
						                        }	
						                        $('input[name=username]').focus();					            	
								            }
								        },{
								            icon: 'glyphicon glyphicon-remove-sign',
								            label: 'Close',
								            cssClass: 'btn-default',
								            action:function(dialogItself){
								            	dialogItself.close();
								            }
								        }]
							        });									
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
	            }, {
	            	icon: 'glyphicon glyphicon-remove-sign',
	                label: 'Cancel',
	                action: function(dialogItself){
	                    dialogItself.close();
	                }
	            }]
	        });
		}
	});

	// var budget = $('#_budget').val();
	// $("[name='n']").text(budget);
	// for(var $x=1;$x<=6;$x++) {
	// 	var a = budget / $("#m"+$x).val();
	// 	$("#n"+$x).text(addCommas(Math.floor(a)));
	// }

	var budget = $('#_budget').val();
	$("[name='n']").text(budget);
	var ttotal = 0;

	$('.denfield').each(function(){
		var a = $(this).parent('.col-sm-3').find('input.denval').val();
		var dnid = $(this).attr('id').slice(3);
		var a = $("#num"+dnid).val() * $("#m"+dnid).val();
		ttotal +=a;
	});

	// for(var $x=1;$x<=6;$x++) {
	// 	var a = $("#num"+$x).val() * $("#m"+$x).val();
		// var a = $(this).parent('.col-sm-3').find('input.denval').val();
		// var dnid = $(this).attr('id').slice(3);
		// var a = $("#num"+dnid).val() * $("#m"+dnid).val();
		// ttotal +=a;
	// 	$("#n"+$x).text(addCommas(Math.floor(a)));
	// }
	var newbudget = budget - ttotal;
	forS = parseInt(newbudget);
	forS = forS.toFixed(2);
	forS = addCommas(forS);
	$('#n').text(forS) 
	$('.denfield').each(function(){		
		var a = $(this).parent('.col-sm-3').find('input.denval').val();
		var dnid = $(this).attr('id').slice(3);
		var a = newbudget / $("#m"+dnid).val();
		$("#n"+dnid).text(addCommas(Math.floor(a)));
	});

	$("input[id^=num]").keyup(function(){
		var budget1 = $("#_budget").val();		
		var num = this.value;
		num = num.replace(/,/g , "");
		var lastnum = 0;
		if(num.length > 1)
		{
			lastnum = num.substring(0, num.length - 1);
		}
		var laststr = num.slice(-1);
		var id = this.id;
		var laststrid = id.slice(-1);
		var denom = $('#m'+laststrid).val();
		var sum1 = 0;
		var sum = 0;
		var mul = 0;
		var forN = 0;
		var forS = 0;
		//calculation
		numinp = $('.denfield').length;

		$('.denfield').each(function(){
			var inputs = $(this).val();
			inputs = inputs.replace(/,/g , "");
			sum = sum + inputs;
			var dnid = $(this).attr('id').slice(3);
			mul = inputs * $("#m"+dnid).val();
			sum1 = sum1 +mul;
		});

		//for output
		if(sum1 > budget){
				$("#"+id).focus();
				// $("#btn").attr("disabled",true);
				$('#'+id).val(lastnum);		
		}
		else {	
			$("#btn").attr("disabled",false);
			$('.denfield').each(function(){
				forS = budget - sum1;	
				var dnid = $(this).attr('id').slice(3);
				var a = forS / $("#m"+dnid).val()
				$("#n"+dnid).text(addCommas(Math.floor(a)));
				forS = parseInt(forS);
				forS = forS.toFixed(2);
				forS = addCommas(forS);
				$("#n").text(forS);
				if(forS <= 0)
				{
					$("#num"+dnid).attr("readonly",true);
					$("#"+id).attr("readonly",false);
				}
				else 
				{
					$("#num"+dnid).attr("readonly",false);
				}
				var t = $("#n"+dnid).text();
				if(t == 0)
				{
					$("#num"+dnid).attr("readonly",true);
					$("#"+id).attr("readonly",false);
				}
				var inputs = $("#num"+dnid).val();
				if(inputs != "") 
				{
					$("#num"+dnid).attr("readonly",false);
				}

			});
			// for(var $x=1;$x<=6;$x++) {			
			// 	forS = budget - sum1;
			// 	var a = forS / $("#m"+$x).val()
			// 	$("#n"+$x).text(addCommas(Math.floor(a)));
			// 	forS = parseInt(forS);
			// 	forS = forS.toFixed(2);
			// 	forS = addCommas(forS);
			// 	$("#n").text(forS);
			// 	if(forS <= 0){
			// 		$("#num"+$x).attr("readonly",true);
			// 		$("#"+id).attr("readonly",false);
			// 	}
			// 	else {
			// 		$("#num"+$x).attr("readonly",false);
			// 	}
			// 	var t = $("#n"+$x).text();
			// 	if(t == 0){
			// 		$("#num"+$x).attr("readonly",true);
			// 		$("#"+id).attr("readonly",false);
			// 	}
			// 	var inputs = $("#num"+$x).val();
			// 	if(inputs != "") {
			// 		$("#num"+$x).attr("readonly",false);
			// 	}
			// }
		}
		
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

	function timeoutmsg(div){
	    setTimeout(function(){
	    	$(div).html('');
	    }, 2000);
	}

	$('.form-container').on('submit','form#tresalesreport',function(){	
		$('.response').html('');
		// form validation
		var dstart='', dend='', gcsales=false,reval=false,refund=false,trans='';

		var type = $('input[name="reportype[]"]:checked').length;
		
		if(type==0)
		{
			$('.response').html('<div class="alert alert-danger">Please check at least one report type.</div>');
			return false;
		}

		$('input[name="reportype[]"]:checked').each(function(){
			if($(this).val()=='gcsales')
			{
				gcsales=true;
			}
			else if($(this).val()=='reval')
			{
				reval=true;
			}
			else if($(this).val()=='refund')
			{
				refund=true;
			}
			
		});

		if(!$('input[name=datetrans]').is(':checked'))
		{
			$('.response').html('<div class="alert alert-danger">Please check transaction.</div>');
			return false;
		}

		var trans = $('#datetrans:checked').val();

		if($('#datetrans:checked').val()=='range')
		{
			if(validDate1($('#dstart').val()) && validDate1($('#dend').val()))
			{
				$('.response').html('<div class="alert alert-danger">Date Start / Date End is invalid.</div>');
				return false;
			}

			if(!validateDate($('#dstart').val()) || !validateDate($('#dend').val()))
			{
				$('.response').html('<div class="alert alert-danger">Date Start / Date End is invalid.</div>');
				return false;
			}

			if(!validDate($('#dstart').val(),$('#dend').val()))
			{
				$('.response').html('<div class="alert alert-danger">Date Start is greater than Date End.</div>');
				return false;
			}

			dstart = $('#dstart').val();
			dend = $('#dend').val();
		}

		var store = $('#store').val();

		location.href='storesalesreportpdf.php?stores='+store+'&gcsales='+gcsales+'&reval='+reval+'&refund='+refund+'&trans='+trans+'&dstart='+dstart+'&dend='+dend;

		return false;
	});
});
//last

function validDate(dToday,dValue) {
  var result = true;
  console.log(dToday);
  dValue = dValue.split('/');
  dToday = dToday.split('/');

  if(dValue[2]<dToday[2])
  {
    return false;
  }

  if(dValue[2]==dToday[2])
  {
    if(dValue[0]<dToday[0])
    {
      return false;
    }
  }
  else 
  {
    return true;
  }

  if(dValue[0]==dToday[0])
  {
    if(dValue[1]<dToday[1])
    {
      return false;
    }
  }
  return result;
}

function validDate1(dValue)
{
  dValue = dValue.split('/');
  if(isNaN(dValue[0]) || isNaN(dValue[1]) || isNaN(dValue[2]))
  {
    return true;
  }
  else 
  {
    return false;
  }
}

function receivingGCStore(recid,storeid,denid)
{
	var qty = $('table#tablestyle tbody tr td.qty'+denid).text();
	var scan = $('table#tablestyle tbody tr td.scangc'+denid).text();
	var totalscan = $('.totalscan').text();
	if(parseInt(qty) > parseInt(scan))
	{
		BootstrapDialog.show({
	    	title: 'Validate GC for Receiving',
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
                'pageToLoad': '../dialogs/validatereceivegc.php?recid='+recid+'&storeid='+storeid+'&denid='+denid
            },
	        onshow: function(dialog) {
	            // dialog.getButton('button-c').disable();
	        },
	        onshown: function(dialogRef){
	        }, 
	        onhidden: function()
	        {
	        	$('input[name=checkedby]').focus();
	        },
	        buttons: [{
	            icon: 'glyphicon glyphicon-ok-sign',
	            label: 'Submit',
	            cssClass: 'btn-primary',
	            hotkey: 13,
	            action:function(dialogItself){
	            	var formURL = $('form#recvalidate').attr('action'),formData = $('form#recvalidate').serialize();
	            	formData +='&qty='+qty;
	            	var barcode = $('#gcbarcode').val();
					if(parseInt(qty) > parseInt(scan))
					{
		            	if(barcode!='')
		            	{
		            		if(barcode.length == 13)
		            		{
		            			$.ajax({
		            				url:formURL,
		            				data:formData,
		            				type:'POST',
		            				success:function(data){
		            					var data = JSON.parse(data);
		            					if(data['stat']==1)
		            					{
		            						$('.response-validate').html('<div class="alert alert-success">GC Barcode #<span class="'+randomColors()+'">'+data['msg']+'</span> successfully scanned for receiving.</div>');
		            						// totalscan++;
		            						// scan++;
		            						$('table#tablestyle tbody tr td.scangc'+denid).text(data['dencnt']);
		            						$('.totalscan').text(data['totcnt']);

		            					}
		            					else 
		            					{
		            						$('.response-validate').html('<div class="alert alert-danger alert-dismissable alert-no-bot">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
			      							timeoutmsg('.response-validate');
		            					}       					
		            				}
		            			});
		            		}
		            		else 
		            		{
			            		$('.response-validate').html('<div class="alert alert-danger alert-dismissable alert-no-bot">GC Barcode Number must be at least 13 character long.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
			            		timeoutmsg('.response-validate');		            			
		            		}

		            	}
		            	else 
		            	{
		            		$('.response-validate').html('<div class="alert alert-danger alert-dismissable alert-no-bot">Please scan GC first.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
		            		timeoutmsg('.response-validate');			            		
		            	}
		            }
		            else 
		            {
	            		$('.response-validate').html('<div class="alert alert-danger alert-dismissable alert-no-bot">Scanned GC has reach the maximum number to received.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
	            		timeoutmsg('.response-validate');			            	
		            }	
	            	$('#gcbarcode').select();
	            	$('#gcbarcode').focus();
	            }
	        }]

	    });
	}
}

// function requestReleaseCheckedBy()
// {
//     BootstrapDialog.show({
//     	title: 'Checked Tag..',
//         message: $('<div></div>').load('../dialogs/checkedstorerequest.php'),
//  	    cssClass: 'modal-checkedandapproved',
//         closable: true,
//         closeByBackdrop: false,
//         closeByKeyboard: true,
//         onshow: function(dialog) {
//             // dialog.getButton('button-c').disable();
//         },
//         onshown: function(dialogRef){

//         },
//         buttons: [{
//             icon: 'glyphicon glyphicon-ok-sign',
//             label: 'Confirm',
//             cssClass: 'btn-primary',
//             hotkey: 13,
//             action:function(dialogItself){
//             	var fn = $('#auth').val();
//             	$('#app-checkby').val(fn);
//             	dialogItself.close();

//             }
//         }, {
//         	icon: 'glyphicon glyphicon-remove-sign',
//             label: 'Close',
//             action: function(dialogItself){
//                 dialogItself.close();
//             }
//         }]
//     });
// }

// function requestReleaseApprovedBy()
// {
//     BootstrapDialog.show({
//     	title: 'Approve Tag..',
//         message: $('<div></div>').load('../dialogs/approvedstorerequest.php'),
//  	    cssClass: 'modal-checkedandapproved',
//         closable: true,
//         closeByBackdrop: false,
//         closeByKeyboard: true,
//         onshow: function(dialogItself) {
//             // dialog.getButton('button-c').disable();
//         },
//         onshown: function(dialogRef){

//         },
//         buttons: [{
//             icon: 'glyphicon glyphicon-ok-sign',
//             label: 'Confirm',
//             cssClass: 'btn-primary',
//             hotkey: 13,
//             action:function(dialogItself){
//             	var fn = $('#auth').val();
//             	$('#app-apprby').val(fn);
//             	dialogItself.close();
//             }
//         }, {
//         	icon: 'glyphicon glyphicon-remove-sign',
//             label: 'Close',
//             action: function(dialogItself){
//                 dialogItself.close();
//             }
//         }]
//     });
// }

function requestReleasedScannedGC()
{
	BootstrapDialog.show({
			closable: true,
            closeByBackdrop: false,
            closeByKeyboard: true,
			cssClass: 'modal-scanned-allocated-rel',
			onshown: function(dialog){							
			},
			onhidden: function(dialog){		
				$('input[name=received]').focus();					
			},			
			title: '<i class="glyphicon glyphicon-zoom-in"></i> '+$('.store-name').val()+' Scanned GC for Released </span>',
	        message: function(dialog) {
	            var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
	            var pageToLoad = dialog.getData('pageToLoad');
				setTimeout(function(){
	            $message.load(pageToLoad);
				},1000);
	            return $message;
	        },
	        data: {
	            'pageToLoad': '../dialogs/temp-scannedgc.php?id='+$('input[name=store_id]').val(),
	        },
			buttons: [{
				label: 'Close',
				cssClass: 'btn-danger btn-sm',
				icon: 'glyphicon glyphicon-remove',
				action: function(dialog){
					dialog.close();
				}
			}]
	});
}

function requestReleasedAllocatedGC()
{
	BootstrapDialog.show({
			closable: true,
            closeByBackdrop: false,
            closeByKeyboard: true,
			cssClass: 'modal-scanned-allocated-rel',
			onshown: function(dialog){
				
			},
			onhidden: function(dialog){
				$('input[name=received]').focus();			
			},
			title: '<i class="glyphicon glyphicon-zoom-in"></i> '+$('.store-name').val()+' Allocated GC </span>',
	        message: function(dialog) {
	            var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
	            var pageToLoad = dialog.getData('pageToLoad');
				setTimeout(function(){
	            $message.load(pageToLoad);
				},1000);
	            return $message;
	        },
	        data: {
	            'pageToLoad': '../dialogs/view-allocatedgcnotrel.php?id='+$('input[name=store_id]').val(),
	        },
			buttons: [{
				label: 'Close',
				cssClass: 'btn-danger btn-sm',
				icon: 'glyphicon glyphicon-remove',
				action: function(dialog){
					dialog.close();
				}
			}]
	}); 
}

function requestReleasedScanGC(denid)
{
	var relid = $('input[name=relno]').val();
	var store_id = $('input[name=store_id]').val();	
	var reqid = $('input[name=rid]').val();
	var remQty = $('.remain'+denid).text().trim();
    BootstrapDialog.show({
        title: 'Scan GC for Releasing',
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
            'pageToLoad': '../dialogs/scan_gcrel.php?id='+relid+'&denid='+denid+'&storeid='+store_id+'&action=releasestore'
        },
        onshown: function(dialogRef){

        },
        onhidden:function(dialogRef){
        	$('input[name=received]').focus();
        },        
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Submit',
            cssClass: 'btn-primary',
            hotkey: 13,
            action:function(dialogItself){								            	
            	var relno = $('input[name=relnum]').val(), denid = $('input[name=denid]').val();      	
            	var barcode = $('#gcbarcode').val(), formUrl = $('form#srsvalidate').attr('action');
            	var scangcx = $(".modal-details-rel table.table-req tbody tr td.scangcx"+denid).text();
            	if(barcode.trim()!=''){
	            	$.ajax({
	            		url:formUrl,
	            		data:{denid:denid,relno:relno,barcode:barcode,store_id:store_id,reqid:reqid},
	            		type:'POST',
	            		success:function(response){
	            			console.log(response)
	            			var data = JSON.parse(response);
	            			if(data['stat'])
	            			{
	            				// $('.modal-details-rel form#srsvalidate span.bcforrel').css({"backgroundColor": "black", "color": "white"});	            				
	            				$('.response-validate').html('<div class="alert alert-success">Barcode Number <span class="'+randomColors()+'">'+data['msg']+'</span> successfully scanned for release.</div>');

	            				scangcx= parseInt(scangcx) + 1;
	            				$(".modal-details-rel table.table-req tbody tr td.scangcx"+denid).text(scangcx);	
	            			}
	            			else 
	            			{
	            				$('.response-validate').html('<div class="alert alert-danger alert-dismissable">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>')
	            			} 
	            		}
	            	});
	            } 
	            else 
	            {
    				$('.response-validate').html('<div class="alert alert-danger alert-dismissable">Please input GC barcode number.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>')
    				timeoutmsg('.response-validate');									            	
	            }
            	$('#gcbarcode').select();		
            	$('#gcbarcode').focus();	
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

function scanRangeReleasedStore()
{
	var storeid = $('input[name="store_id"]').val();	
	var relid = $('input[name=relno]').val();
	var reqid = $('input[name=rid]').val();

    BootstrapDialog.show({
        title: 'Scan By GC Barcode # Range',
        cssClass: 'modal-validate',
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
            'pageToLoad': '../templates/regulargc.php?page=scanReleasedStoreGCByRange&storeid='+storeid+'&relid='+relid+'&reqid='+reqid
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
            	$('.responserange').html('');
            	$('.response').html('');

            	var bstart = $('.validateGCstart').val(), bend = $('.validateGCend').val();	
            	var formURL = $('form#scanrStoreGCRange').attr('action'), formData = $('form#scanrStoreGCRange').serialize();
            	var flag = $('input[name="flag"]').val();
            	if(flag==1)
            	{
	            	if(bstart=='')
	            	{
						$('.responserange').html('<div class="alert alert-danger">Plese scan gc first.</div>');
						$('.validateGCstart').select();	
	            	}
	            	else if(bstart.length!=13)
	            	{
						$('.responserange').html('<div class="alert alert-danger">GC barcode number must be 13 characters long.</div>');
						$('.validateGCstart').select();		            		
	            	}
	            	else
	            	{
	            		barcode = bstart;
        				$.ajax({
        					url:'../ajax.php?action=isValidGCRelStoreGCRangeBarcode',
        					//url:'../ajax.php?action=isValidGC',
        					data:{relid:relid,barcode:barcode,storeid:storeid,reqid:reqid},
        					type:'POST',
        					success:function(data)
        					{
        						console.log(data);
								var data = JSON.parse(data);
								if(data['stat'])
								{
									$('.validateGCstart').prop('readonly','readonly');	
									$('.validateGCend').prop('disabled',false);	
									$('input[name="dens"]').val(data['denid']);
									//denom = data['denom'];	
									$('.validateGCend').focus();
									$('.responserange').html('');		
									$('input[name="flag"]').val(2);	
									$('.responserange').html('<div class="alert alert-success">'+data['msg']+'</div>');															
								}
								else 
								{
									$('.responserange').html('<div class="alert alert-danger">'+data['msg']+'</div>');
									$('.validateGCstart').select();	
								}
        					}
        				});	            		
	            	}
	            }

	            if(flag==2)
	            {

	            	if(bend=='')
	            	{
						$('.responserange').html('<div class="alert alert-danger">Plese scan gc first.</div>');
						$('.validateGCend').select();	
	            	}
	            	else if(bstart.length!=13)
	            	{
						$('.responserange').html('<div class="alert alert-danger">GC barcode number must be 13 characters long.</div>');
						$('.validateGCend').select();		            		
	            	}
	            	else
	            	{
	            		barcode = bend;
        				$.ajax({
        					url:'../ajax.php?action=isValidGCRelStoreGCRangeBarcode',
        					//url:'../ajax.php?action=isValidGC',
        					data:{relid:relid,barcode:barcode,storeid:storeid,reqid:reqid},
        					type:'POST',
        					success:function(data)
        					{
        						console.log(data);
								var data = JSON.parse(data);
								if(!data['stat'])
								{
									$('.responserange').html('<div class="alert alert-danger">'+data['msg']+'</div>');
									$('.validateGCend').select();															
								}
								else 
								{
									var denstart = $('input[name="dens"]').val();
									var denend =  data['denid'];
									var denom = data['denom'];

									if(parseInt(denstart.trim())!=parseInt(denend.trim()))
									{
										$('.responserange').html('<div class="alert alert-danger">Invalid Denomination.</div>');
										$('.validateGCend').select();	
									}
									else if(bstart >= bend)
									{
										$('.responserange').html('<div class="alert alert-danger">Invalid GC Barcode # Range.</div>');
										$('.validateGCend').select();		
									}
									else 
									{

										var scanned = $('td.scangcx'+denstart).text();
										var remainreq = $('td.inptxt span.remain'+denstart).text();
										var gcrangetotal = bend - bstart +1;										

										var gctotalscanned = parseInt(scanned.trim()) + parseInt(gcrangetotal);

										if(gctotalscanned > remainreq)
										{
											$('.responserange').html('<div class="alert alert-danger">Total number of GC to scan is greater than the GC Received.</div>');
											$('.validateGCend').select();												
										}
										else 
										{
											var dialog = new BootstrapDialog({
								            message: function(dialogRef){													           
								            var $message = $('<div class="rangeval">'+
													'<div class="alert alert-info validate-flash" id="_adjust_alert">'+
													'<p class="bar-range"><img src="../assets/images/ajax.gif">Validating Barcode Number: </p>'+
													'<p class="br">'+bstart+' to '+bend+'</p>'+
													'<p class="den">Denomination:<span class="den-color"> &#8369 '+denom+'</span></p>'+                												          
						            				'</div>');			        
								                return $message;
								            },
								            closable: false
									        });
									        dialog.realize();
									        dialog.getModalHeader().hide();
									        dialog.getModalFooter().hide();
									        dialog.getModalContent().css('background-color','none');
									        dialog.getModalBody().css('color', '#fff');
									        dialog.open();
										    $.ajax({
										    	url:formURL,
										    	data:{bstart:bstart,bend:bend,storeid:storeid,relid:relid,reqid:reqid},
										    	type:'POST',
										    	success:function(data1)
										    	{
										    		console.log(data1);
										    		var data1 = JSON.parse(data1);
										    		if(data1['stat'])
										    		{
														dialog.close();
														totalscan = parseInt(scanned) + data1['total'];
														$('td.scangcx'+data1['denid']).text(totalscan);
														$('.validateGCstart').prop('readonly','');	
														$('.validateGCend').prop('disabled',true);	
														$('.validateGCend').val('');	
														$('input[name="dens"]').val('');	
														$('.validateGCstart').val('').focus();
														$('.responserange').html('');		
														$('input[name="flag"]').val(1);																		
											    		$('.responserange').html('<div class="alert alert-success">'+data1['msg']+'</div>');											    		
										    		}
										    		else 
										    		{
									    				dialog.close();
											    		//dialogItself.close();
											    		totalscan = parseInt(scanned) + data1['total'];

											    		$('td.scangcx'+data1['denid']).text(totalscan);

														$('.validateGCstart').prop('readonly','');	
														$('.validateGCend').prop('disabled',true);	
														$('.validateGCend').val('');	
														$('input[name="dens"]').val('');	
														$('.validateGCstart').val('').focus();
														$('.responserange').html('');		
														$('input[name="flag"]').val(1);		

											     		$('.responserange').html('<div class="alert alert-danger">'+data1['msg']+'</div>');
										    		}
										    	}
										    });	

										}
									}
									
								}
        					}
        				});	  
	            	}

            		$('.validateGCend').select();
	            }

            }
        },{
            icon: '',
            label: '<i class="fa fa-spinner"></i> Reset',
            cssClass: 'btn-default',
            action:function(dialogItself){
				$('.validateGCstart').prop('readonly','');	
				$('.validateGCend').val('').prop('disabled',true);
				$('input[name="dens"]').val('');	
				$('.validateGCstart').val('').focus();
				$('.responserange').html('');		
				$('input[name="flag"]').val(1);
            }
        },{
            icon: 'glyphicon glyphicon-remove-sign',
            label: 'Close',
            cssClass: 'btn-default',
            action:function(dialogItself){
            	dialogItself.close();
            }
        }]
    });
}

function scanRangex()
{
	BootstrapDialog.show({
    	title: '<i class="fa fa-bookmark"></i> Scan By GC Barcode # Range',
        message: $('<div></div>').load('../dialogs/validatecusgcbyrange.php?recid='+recid),
 	    cssClass: 'modal-validate',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: false,
        onshow: function(dialog) {
            // dialog.getButton('button-c').disable();
        },
        onshown: function(dialogRef){
        	$('.validateGCstart, .validateGCend').inputmask("integer", { allowMinus: false });
        	$('.validateGCstart').focus();
        }, 
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Submit',
            cssClass: 'btn-primary',
            hotkey: 13,
            action:function(dialogItself){
            	$('.responserange').html('');
            	$('.response').html('');
            	var ereq = $('input[name="requisid"]').val(),$button = this;
            	var bstart = $('.validateGCstart').val(), bend = $('.validateGCend').val();	
            	var formURL = $('form#srrvalidaterange').attr('action'), formData = $('form#srrvalidaterange').serialize();
            	var flag = $('input[name="flag"]').val();
	            formData +='&ereq='+ereq;
            	$("input[name^=den]").each(function(){
            		//$(this).val();
            		var dnid = $(this).attr('name').slice(3);
            		formData +='&den'+dnid+'='+$(this).val();
            	});
            	// formData +='&den1='+$('input[name=den1]').val();
            	// formData +='&den2='+$('input[name=den2]').val();
            	// formData +='&den3='+$('input[name=den3]').val();
            	// formData +='&den4='+$('input[name=den4]').val();
            	// formData +='&den5='+$('input[name=den5]').val();
            	// formData +='&den6='+$('input[name=den6]').val();
            	if(flag==1)
            	{
	            	if(bstart!='')
	            	{
	            		if(bstart.length==13)
	            		{
	        				//check gc production number
	        				$.ajax({
	        					url:'../ajax.php?action=isValidGC',
	        					data:{ereq:ereq,bstart:bstart},
	        					type:'POST',
	        					success:function(data)
	        					{
	        						console.log(data);
									var data = JSON.parse(data);
									if(data['stat'])
									{
										$('.validateGCstart').prop('readonly','readonly');	
										$('.validateGCend').prop('disabled',false);	
										$('input[name="dens"]').val(data['denid']);
										denom = data['denom'];	
										$('.validateGCend').focus();
										$('.responserange').html('');		
										$('input[name="flag"]').val(2);																
									}
									else 
									{
										$('.responserange').html('<div class="alert alert-danger">'+data['msg']+'</div>');
										$('.validateGCstart').select();	
									}
	        					}
	        				});

	            		}
	            		else 
	            		{
							$('.responserange').html('<div class="alert alert-danger">GC barcode number must be 13 characters long.</div>');
							$('.validateGCstart').select();			            			
	            		}
	            	}
	            	else 
	            	{
						$('.responserange').html('<div class="alert alert-danger">Plese scan gc first.</div>');
						$('.validateGCstart').select();	
					}	
            	}

            	if(flag==2)
            	{
	            	if(bend!='')
	            	{
	            		if(bend.length==13)
	            		{
	        				//check gc production number
	        				$.ajax({
	        					url:'../ajax.php?action=isValidGC',
	        					data:{ereq:ereq,bstart:bstart,bend:bend},
	        					type:'POST',
	        					success:function(data)
	        					{
									var data = JSON.parse(data);
									if(data['stat'])
									{
										denEnd = data['denid'];
										if(bstart!=bend)
										{
											denStart = $('input[name="dens"]').val();													

											if(parseInt(denStart.trim())==parseInt(denEnd.trim()))
											{
												if(bstart<bend)
												{
													toval = (bend-bstart)+1;
													denrec = $('.den'+denStart).val();
													denrec = denrec.replace(',','');
													ngc = $('.n'+denStart).val();
													totalval = parseInt(toval) + parseInt(ngc);
													if(totalval<=denrec)
													{

														var dialog = new BootstrapDialog({
											            message: function(dialogRef){													           
											            var $message = $('<div class="rangeval">'+
																'<div class="alert alert-info validate-flash" id="_adjust_alert">'+
																'<p class="bar-range"><img src="../assets/images/ajax.gif">Validating Barcode Number: </p>'+
																'<p class="br">'+bstart+' to '+bend+'</p>'+
																'<p class="den">Denomination:<span class="den-color"> &#8369 '+denom+'</span></p>'+                												          
									            				'</div>');			        
											                return $message;
											            },
											            closable: false
												        });
												        dialog.realize();
												        dialog.getModalHeader().hide();
												        dialog.getModalFooter().hide();
												        dialog.getModalContent().css('background-color','none');
												        dialog.getModalBody().css('color', '#fff');
												        dialog.open();
													    $.ajax({
													    	url:formURL,
													    	data:formData,
													    	type:'POST',
													    	success:function(data1)
													    	{
													    		console.log(data1);
													    		var data1 = JSON.parse(data1);
													    		if(data1['stat'])
													    		{
																	dialog.close();
																	$('.validateGCstart').prop('readonly','');	
																	$('.validateGCend').prop('disabled',true);	
																	$('.validateGCend').val('');	
																	$('input[name="dens"]').val('');	
																	$('.validateGCstart').val('').focus();
																	$('.responserange').html('');		
																	$('input[name="flag"]').val(1);																		
														    		$('.responserange').html('<div class="alert alert-success">'+data1['msg']+'</div>');
														    		$('input.n'+data1['denid']).val(data1['denqty']);
													    		}
													    		else 
													    		{
												    				dialog.close();
														    		//dialogItself.close();
																	$('.validateGCstart').prop('readonly','');	
																	$('.validateGCend').prop('disabled',true);	
																	$('.validateGCend').val('');	
																	$('input[name="dens"]').val('');	
																	$('.validateGCstart').val('').focus();
																	$('.responserange').html('');		
																	$('input[name="flag"]').val(1);																		
														    		$('.responserange').html('<div class="alert alert-danger">'+data1['msg']+'</div>');
													    		}
													    	}
													    });	
													        												        
													}				
													else
													{
														$('.responserange').html('<div class="alert alert-danger">Total number of GC to scan is greater than the GC Received.</div>');
														$('.validateGCend').select();																
													}																																								
												}
												else 
												{
													$('.responserange').html('<div class="alert alert-danger">Barcode start must be lesser than Barcode end.</div>');
													$('.validateGCend').select();																
												}
											}
											else 
											{
												$('.responserange').html('<div class="alert alert-danger">GC Denomination must be the same.</div>');
												$('.validateGCend').select();															
											}
										}
										else 
										{
											$('.responserange').html('<div class="alert alert-danger">GC Barcode number is invalid.</div>');
											$('.validateGCend').select();	
										}												
										// $('.validateGCstart').prop('disabled',true);	
										// $('.validateGCend').prop('disabled',false);	
										// $('.validateGCend').focus();	
										// $('input[name="flag"]').val(2);	
										// $('input[name="flag"]').val(2);						
									}
									else 
									{
										$('.responserange').html('<div class="alert alert-danger">'+data['msg']+'</div>');
										$('.validateGCend').select();	
									}
	        					}
	        				});

	            		}
	            		else 
	            		{
							$('.responserange').html('<div class="alert alert-danger">GC barcode number must be 13 characters long.</div>');
							$('.validateGCend').select();			            			
	            		}
	            	}
	            	else 
	            	{
						$('.responserange').html('<div class="alert alert-danger">Plese scan gc first.</div>');
						$('.validateGCend').select();	
					}			            		
            	}

				//            	
				// if((bstart.length == 13) && (bend.length ==  13))
				// {
				// 	$button.disable();
				// 	$.ajax({
				// 		url:formURL,
				// 		data:formData,
				// 		type:'POST',
				// 		success:function(data)
				// 		{
				// 			$('.responserange').html(data);
				// 			$('.validateGCstart').select();									
				// 		}
				// 	});

				// }
				// else 
				// {
				// 	$('.responserange').html('<div class="alert alert-danger">GC barcode number must be 13 characters long.</div>');
				// 	$('.validateGCstart').select();
				// }
				// $button.enable();	      
            }
        },{
            icon: '',
            label: '<i class="fa fa-spinner"></i> Reset',
            cssClass: 'btn-default',
            action:function(dialogItself){
				$('.validateGCstart').prop('readonly','');	
				$('.validateGCend').prop('disabled',true);	
				$('input[name="dens"]').val('');	
				$('.validateGCstart').val('').focus();
				$('.responserange').html('');		
				$('input[name="flag"]').val(1);
            }
        },{
            icon: 'glyphicon glyphicon-remove-sign',
            label: 'Close',
            cssClass: 'btn-default',
            action:function(dialogItself){
            	dialogItself.close();
            }
        }]

    });
}

function requestPromoReleasedScanGCByRange()
{
	var relnum = $('input#relid').val();
	var trid = $('input#trid').val();
    BootstrapDialog.show({
        title: 'Scan GC Range for Releasing (Promo)',
        cssClass: 'modal-validate',
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
            'pageToLoad': '../templates/regulargc.php?page=scanPromoGCByRangeForReleasing&relnum='+relnum+'&trid='+trid
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
            	$('.responserange').html('');
            	$('.response').html('');

            	var bstart = $('.validateGCstart').val(), bend = $('.validateGCend').val();	
            	var formURL = $('form#scanPromoGCRange').attr('action'), formData = $('form#scanPromoGCRange').serialize();
            	var flag = $('input[name="flag"]').val();

            	if(flag==1)
            	{
	            	if(bstart=='')
	            	{
						$('.responserange').html('<div class="alert alert-danger">Plese scan gc first.</div>');
						$('.validateGCstart').select();	
	            	}
	            	else if(bstart.length!=13)
	            	{
						$('.responserange').html('<div class="alert alert-danger">GC barcode number must be 13 characters long.</div>');
						$('.validateGCstart').select();		            		
	            	}
	            	else 
	            	{
	            		barcode = bstart;
        				$.ajax({
        					//gcreleasevalidationpromo

        					url:'../ajax.php?action=gcreleasevalidationpromoByRange',
        					//url:'../ajax.php?action=isValidGC',
        					data:{barcode:barcode,relnum:relnum,trid:trid},
        					type:'POST',
        					success:function(data)
        					{
        						console.log(data);
								var data = JSON.parse(data);
								if(data['stat'])
								{
									$('.validateGCstart').prop('readonly','readonly');	
									$('.validateGCend').prop('disabled',false);	
									$('input[name="dens"]').val(data['denid']);
									//denom = data['denom'];	
									$('.validateGCend').focus();
									$('.responserange').html('');		
									$('input[name="flag"]').val(2);	
									$('.responserange').html('<div class="alert alert-success">'+data['msg']+'</div>');															
								}
								else 
								{
									$('.responserange').html('<div class="alert alert-danger">'+data['msg']+'</div>');
									$('.validateGCstart').select();	
								}
        					}
        				});	            		

	            	}
	            }



	            if(flag==2)
	            {

	            	if(bend=='')
	            	{
						$('.responserange').html('<div class="alert alert-danger">Plese scan gc first.</div>');
						$('.validateGCend').select();	
	            	}
	            	else if(bstart.length!=13)
	            	{
						$('.responserange').html('<div class="alert alert-danger">GC barcode number must be 13 characters long.</div>');
						$('.validateGCend').select();		            		
	            	}
	            	else
	            	{            		

	            		barcode = bend;
        				$.ajax({
        					url:'../ajax.php?action=gcreleasevalidationpromoByRange',
							data:{barcode:barcode,relnum:relnum,trid:trid},
        					type:'POST',
        					success:function(data)
        					{
        						console.log(data);
								var data = JSON.parse(data);
								if(!data['stat'])
								{
									$('.responserange').html('<div class="alert alert-danger">'+data['msg']+'</div>');
									$('.validateGCend').select();															
								}
								else 
								{

									var denstart = $('input[name="dens"]').val();
									var denend =  data['denid'];
									var denom = data['denom'];

									if(parseInt(denstart.trim())!=parseInt(denend.trim()))
									{
										$('.responserange').html('<div class="alert alert-danger">Invalid Denomination.</div>');
										$('.validateGCend').select();	
									}
									else if(bstart >= bend)
									{
										$('.responserange').html('<div class="alert alert-danger">Invalid GC Barcode # Range.</div>');
										$('.validateGCend').select();		
									}
									else 
									{

										var scanned = $('td.scangcx'+denstart).text();
										var remainreq = $('td.inptxt span.remain'+denstart).text();
										var gcrangetotal = bend - bstart +1;	

										var gctotalscanned = parseInt(scanned.trim()) + parseInt(gcrangetotal);

										if(gctotalscanned > remainreq)
										{
											$('.responserange').html('<div class="alert alert-danger">Total number of GC to scan is greater than the GC Received.</div>');
											$('.validateGCend').select();												
										}
										else 
										{

											var dialog = new BootstrapDialog({
								            message: function(dialogRef){													           
								            var $message = $('<div class="rangeval">'+
													'<div class="alert alert-info validate-flash" id="_adjust_alert">'+
													'<p class="bar-range"><img src="../assets/images/ajax.gif">Validating Barcode Number: </p>'+
													'<p class="br">'+bstart+' to '+bend+'</p>'+
													'<p class="den">Denomination:<span class="den-color"> &#8369 '+denom+'</span></p>'+                												          
						            				'</div>');			        
								                return $message;
								            },
								            closable: false
									        });
									        dialog.realize();
									        dialog.getModalHeader().hide();
									        dialog.getModalFooter().hide();
									        dialog.getModalContent().css('background-color','none');
									        dialog.getModalBody().css('color', '#fff');
									        dialog.open();
										    $.ajax({
										    	url:formURL,
										    	data:{bstart:bstart,bend:bend,relnum:relnum,trid:trid},
										    	type:'POST',
										    	success:function(data1)
										    	{
										    		console.log(data1);
										    		var data1 = JSON.parse(data1);
										    		if(data1['stat'])
										    		{
														dialog.close();
														totalscan = parseInt(scanned) + data1['total'];
														$('td.scangcx'+data1['denid']).text(totalscan);
														$('.validateGCstart').prop('readonly','');	
														$('.validateGCend').prop('disabled',true);	
														$('.validateGCend').val('');	
														$('input[name="dens"]').val('');	
														$('.validateGCstart').val('').focus();
														$('.responserange').html('');		
														$('input[name="flag"]').val(1);																		
											    		$('.responserange').html('<div class="alert alert-success">'+data1['msg']+'</div>');											    		
										    		}
										    		else 
										    		{
									    				dialog.close();
											    		//dialogItself.close();
											    		totalscan = parseInt(scanned) + data1['total'];

											    		$('td.scangcx'+data1['denid']).text(totalscan);

														$('.validateGCstart').prop('readonly','');	
														$('.validateGCend').prop('disabled',true);	
														$('.validateGCend').val('');	
														$('input[name="dens"]').val('');	
														$('.validateGCstart').val('').focus();
														$('.responserange').html('');		
														$('input[name="flag"]').val(1);		

											     		$('.responserange').html('<div class="alert alert-danger">'+data1['msg']+'</div>');
										    		}
										    	}
										    });
										}
									}
									
								}
        					}
        				});	  
	            	}

            		$('.validateGCend').select();
	            }

            }
        },{
            icon: '',
            label: '<i class="fa fa-spinner"></i> Reset',
            cssClass: 'btn-default',
            action:function(dialogItself){
				$('.validateGCstart').prop('readonly','');	
				$('.validateGCend').val('').prop('disabled',true);
				$('input[name="dens"]').val('');	
				$('.validateGCstart').val('').focus();
				$('.responserange').html('');		
				$('input[name="flag"]').val(1);
            }
        },{
            icon: 'glyphicon glyphicon-remove-sign',
            label: 'Close',
            cssClass: 'btn-default',
            action:function(dialogItself){
            	dialogItself.close();
            }
        }]
    });
}

function requestPromoReleasedScanGC()
{
	var relnum = $('input#relid').val();
	var trid = $('input#trid').val();
    BootstrapDialog.show({
        title: 'Scan GC for Releasing (Promo)',
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
            'pageToLoad': '../dialogs/scan_gcrel.php?action=releasepromo&relnum='+relnum+'&trid='+trid
        },
        onshown: function(dialogRef){

        },
        onhidden:function(dialogRef){
        	$('input[name=received]').focus();
        },        
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Submit',
            cssClass: 'btn-primary',
            hotkey: 13,
            action:function(dialogItself){
            	$('.response-validate').html('');
            	var relnum = $('input[name=relid]').val(),trid = $('input[name=trid]').val();
            	var barcode = $('#gcbarcode').val(), formUrl = $('form#srsvalidatepromo').attr('action');
            	//var scangcx = $(".modal-details-rel table.table-req tbody tr td.scangcx"+denid).text();	
				if(barcode==undefined)
				{
					return false;
				}
            	if(barcode.trim()!='')
            	{
	            	$.ajax({
	            		url:formUrl,
	            		data:{relnum:relnum,barcode:barcode,trid:trid},
	            		type:'POST',
	            		success:function(response){
	            			console.log(response)
	            			var data = JSON.parse(response);
	            			if(data['st'])
	            			{
	            				// $('.modal-details-rel form#srsvalidate span.bcforrel').css({"backgroundColor": "black", "color": "white"});	            				
	            				$('.response-validate').html('<div class="alert alert-success">Barcode Number <span class="'+randomColors()+'">'+data['msg']+'</span> successfully scanned for release.</div>');
	            				$(".modal-details-rel table.table-req tbody tr td.scangcx"+data['denom']).text(data['scanned']);	
	            			}
	            			else 
	            			{
	            				$('.response-validate').html('<div class="alert alert-danger alert-dismissable">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>')
	            			} 
	            		}
	            	});
	            } 
	            else 
	            {
    				$('.response-validate').html('<div class="alert alert-danger alert-dismissable">Please input GC barcode number.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>')							            	
	            }
            	$('#gcbarcode').select();		
            	$('#gcbarcode').focus();
            	$('.response').html('');

            	
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

function viewapprovedgc(id)
{
    BootstrapDialog.show({
        title: '<i class="fa fa-user"></i> GC Details',
        cssClass: 'modal-details-reldetails',
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
            'pageToLoad': '../dialogs/viewgcreleased.php?relid='+id
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

function gcreleasedperdenom(den,relid,denom)
{
    BootstrapDialog.show({
    	// title: '<i class="fa fa-user"></i> GC Released No. '+zeroPad(relid,3)+' - Denomination: '+denom.toFixed(2),
        title: '<i class="fa fa-user"></i> GC - Denomination: '+denom.toFixed(2),
        cssClass: 'modal-details-relbarcodes',
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
            'pageToLoad': '../dialogs/viewreleasedbarcode.php?den='+den+'&relid='+relid
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

function approvedBudgetRequestDetails(reqid)
{
	BootstrapDialog.show({
        title: 'Budget Details',
        cssClass: 'modal-details',
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
            'pageToLoad': '../dialogs/view-approved-budget-request.php?id='+reqid
        },
	    onshow: function(dialog) {
	        // dialog.getButton('button-c').disable();
	    },
	    onshown: function(dialogRef){

	    },
	    buttons:[ {
	    	icon: 'glyphicon glyphicon-remove-sign',
	        label: 'Close',
	        action: function(dialogItself){
	            dialogItself.close();
	        }
	    }]
	});
}

function cancelledBudgetRequestDetails(id)
{
	BootstrapDialog.show({
        title: 'Cancelled Budget Request Details',
        cssClass: 'modal-details',
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
            'pageToLoad': '../dialogs/view-cancelled-budget-request.php?id='+id
        },
	    onshow: function(dialog) {
	        // dialog.getButton('button-c').disable();
	    },
	    onshown: function(dialogRef){

	    },
	    buttons:[ {
	    	icon: 'glyphicon glyphicon-remove-sign',
	        label: 'Close',
	        action: function(dialogItself){
	            dialogItself.close();
	        }
	    }]
	});
}

function zeroPad(x, y)
{
   y = Math.max(y-1,0);
   var n = (x / Math.pow(10,y)).toFixed(y);
   return n.replace('.','');  
}

function timeoutmsg(div){
    setTimeout(function(){
    	$(div).html('');
    }, 6000);
}

function approvedProductionRequest(prid)
{
	BootstrapDialog.show({
        title: 'Production Details',
        cssClass: 'modal-details-pro',
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
            'pageToLoad': '../dialogs/view-approved-production-request.php?id='+prid
        },
	    onshow: function(dialog) {

	    },
	    onshown: function(dialogRef){
	    },
	    onhidden: function(dialogRef){
	    },
	    buttons:[ {
	    	icon: 'glyphicon glyphicon-remove-sign',
	        label: 'Close',
	        action: function(dialogItself){
	            dialogItself.close();
	        }
	    }]
	});
}

function generateBarcode(peid,penum)
{
	BootstrapDialog.show({
		title: 'Confirmation',
	    message: 'Are you sure you want to generate GC Barcode for Production Entry # '+zeroPad(penum,4)+'?',
	    closable: true,
	    closeByBackdrop: false,
	    closeByKeyboard: true,
	    onshow: function(dialog) {
	        // dialog.getButton('button-c').disable();
	        $('button#gencode').prop('disabled',true);
	    },
	    onhide: function(dialog) {
	    	$('button#gencode').prop('disabled',false);
	    },
	    buttons: [{
	        icon: 'glyphicon glyphicon-ok-sign',
	        label: 'Yes',
	        cssClass: 'btn-primary',
	        hotkey: 13,
	        action:function(dialogItself){    
				$button = this;   
				$button.disable(); 	
	        	dialogItself.close();
				$.ajax({
					url:'../ajax.php?action=generatebarcode',
					type:'POST',
					data:{peid:peid},
					beforeSend:function(){
						$('#processing-modal').modal('show');
					},
					success:function(response){
						$('#processing-modal').modal('hide');	
						var res = response.trim();
						
						if(res=='success'){
							var dialog = new BootstrapDialog({
				            message: function(dialogRef){
				            var $message = $('<div>GC Bardcode Successfully Generated.</div>');			        
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
		               	} else {
		               		alert(res);
		               	}
					}
				});
				
				$button.enable();	
	        }
	    }, {
	    	icon: 'glyphicon glyphicon-remove-sign',
	        label: 'No',
	        action: function(dialogItself){
	            dialogItself.close();
	        }
	    }]
	});
}

function viewbarcodegen(id)
{
	BootstrapDialog.show({
        title: 'Generated Barcode',
        cssClass: 'gc-barcode-modal',
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
            'pageToLoad': '../dialogs/view-barcode-generated.php?id='+id
        },
	    onshow: function(dialog) {
	    },
	    onshown: function(dialogRef){

	    },
	    buttons:[ {
	    	icon: 'glyphicon glyphicon-remove-sign',
	        label: 'Close',
	        action: function(dialogItself){
	            dialogItself.close();
	        }
	    }]
	});

    // BootstrapDialog.show({
    // 	title: 'Generated Barcode',
    //     message: $('<div></div>').load('../dialogs/view-barcode-generated.php?id='+id),
 	  //   cssClass: 'gc-barcode-modal',
    //     closable: true,
    //     closeByBackdrop: false,
    //     closeByKeyboard: true,
    //     onshow: function(dialog) {
    //         // dialog.getButton('button-c').disable();
    //     },
    //     onshown: function(dialogRef){
    //     },
    //     buttons:[ {
    //     	icon: 'glyphicon glyphicon-remove-sign',
    //         label: 'Close',
    //         action: function(dialogItself){
    //             dialogItself.close();
    //         }
    //     }]
    // });
}

function viewrequisition(id)
{
	BootstrapDialog.show({
        title: 'Requisition Details',
        cssClass: 'view-requisition-modal',
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
            'pageToLoad': '../dialogs/view-production-requisition.php?id='+id
        },
	    onshow: function(dialog) {
	    },
	    onshown: function(dialogRef){

	    },
	    buttons:[ {
	    	icon: 'glyphicon glyphicon-remove-sign',
	        label: 'Close',
	        action: function(dialogItself){
	            dialogItself.close();
	        }
	    }]
	});

	// 			$('.form-horizontal').on('click','button#viewrequisition',function(){
	// 		        BootstrapDialog.show({
	// 		        	title: 'Requisition Details',
	// 		            message: $('<div></div>').load('../dialogs/view-production-requisition.php?id='+id),
	// 		     	    cssClass: 'view-requisition-modal',
	// 			        closable: true,
	// 			        closeByBackdrop: false,
	// 			        closeByKeyboard: true,
	// 			        onshow: function(dialog) {
	// 			            // dialog.getButton('button-c').disable();
	// 			        },
	// 		            onshown: function(dialogRef){
	// 		            },
	// 			        buttons:[ {
	// 			        	icon: 'glyphicon glyphicon-remove-sign',
	// 			            label: 'Close',
	// 			            action: function(dialogItself){
	// 			                dialogItself.close();
	// 			            }
	// 			        }]
	// 		        });
}

function viewCancelledProductionRequest(cid)
{
	BootstrapDialog.show({
        title: 'Cancelled Production Request Details',
        cssClass: 'cancelled-pro-req',
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
            'pageToLoad': '../dialogs/view-cancelled-production-request.php?id='+cid
        },
	    onshow: function(dialog) {
	    },
	    onshown: function(dialogRef){

	    },
	    buttons:[ {
	    	icon: 'glyphicon glyphicon-remove-sign',
	        label: 'Close',
	        action: function(dialogItself){
	            dialogItself.close();
	        }
	    }]
	});
	// $('table.table tbody.store-request-list').on('click','tr td button.app-pro-can',function(){
	// 	var id = $(this).attr('app-id');
 //        BootstrapDialog.show({
 //        	title: 'Cancelled Production Request Details',
 //            message: $('<div></div>').load('../dialogs/view-cancelled-production-request.php?id='+id),
 //     	    cssClass: 'modal-details',
	//         closable: true,
	//         closeByBackdrop: false,
	//         closeByKeyboard: true,
	//         onshow: function(dialog) {
	//             // dialog.getButton('button-c').disable();
	//         },
 //            onshown: function(dialogRef){

 //            },
	//         buttons:[ {
	//         	icon: 'glyphicon glyphicon-remove-sign',
	//             label: 'Close',
	//             action: function(dialogItself){
	//                 dialogItself.close();
	//             }
	//         }]
 //        });
	// });	
}



function requestAssig(dept,type)
{
	var header = '';
	if(type==1)
	{
		header = 'Checked Tag..';
	}
	else 
	{
		header = 'Approved Tag..'
	}
	BootstrapDialog.show({
		closable: true,
	    closeByBackdrop: false,
	    closeByKeyboard: true,
		cssClass: 'modal-checkedandapproved',
		onshown: function(dialog){							
		},
		title: header,
	    message: function(dialog) {
	        var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
	        var pageToLoad = dialog.getData('pageToLoad');
			setTimeout(function(){
	        $message.load(pageToLoad);
			},1000);
	        return $message;
	    },
	    data: {
	        'pageToLoad': '../dialogs/requestAssig.php?dept='+dept,
	    },
	    buttons: [{
	        icon: 'glyphicon glyphicon-ok-sign',
	        label: 'Confirm',
	        cssClass: 'btn-primay',
	        hotkey: 13,
	        action:function(dialogItself){
	        	var aid = $('#auth').val();

		    	$.ajax({
		    		url:'../ajax.php?action=getAssignatoriesDetails',
		    		type:'POST',
		    		data:{aid:aid},
		    		beforeSend:function(){

		    		},
		    		success:function(data){
		                console.log(data);
		                var data = JSON.parse(data);   

		    			if(data['st'])
		                {                    
				        	if(type==1)
				        	{
				        		$('#app-checkby').val(data['name']);
				        	}
				        	else 
				        	{
				        		$('#app-apprby').val(data['name']);
				        	}
				        	dialogItself.close();  							
		    			} 
		                else 
		                {
							console.log(data['msg']);
		    			}              
		    		}
		    	});
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

	// $('.form-generator').on('click','button.gencode',function(){
	// 	var id = $(this).attr('peid');
 //        BootstrapDialog.show({
 //        	title: 'Confirmation',
 //            message: 'Are you sure you want to generate GC Barcode?',
 //            closable: true,
 //            closeByBackdrop: false,
 //            closeByKeyboard: true,
 //            onshow: function(dialog) {
 //                // dialog.getButton('button-c').disable();
 //            },
 //            buttons: [{
 //                icon: 'glyphicon glyphicon-ok-sign',
 //                label: 'Yes',
 //                cssClass: 'btn-primary',
 //                hotkey: 13,
 //                action:function(dialogItself){                	
 //                	dialogItself.close();


 //                }
 //            }, {
 //            	icon: 'glyphicon glyphicon-remove-sign',
 //                label: 'No',
 //                action: function(dialogItself){
 //                    dialogItself.close();
 //                }
 //            }]
 //        });

	// });


	// $('table.table tbody.store-request-list').on('click','tr td button.app-pro',function(){
	// 	var id = $(this).attr('app-id');
 //        BootstrapDialog.show({
 //        	title: 'Production Details',
 //            message: $('<div></div>').load('../dialogs/view-approved-production-request.php?id='+id),
 //     	    cssClass: 'modal-details-pro',
	//         closable: true,
	//         closeByBackdrop: false,
	//         closeByKeyboard: true,
	//         onshow: function(dialog) {
	//             // dialog.getButton('button-c').disable();
	//         },
 //            onshown: function(dialogRef){
	// 			$('.form-horizontal').on('click','button#viewbarcodepro',function(){
	// 		        BootstrapDialog.show({
	// 		        	title: 'Generated Barcode',
	// 		            message: $('<div></div>').load('../dialogs/view-barcode-generated.php?id='+id),
	// 		     	    cssClass: 'gc-barcode-modal',
	// 			        closable: true,
	// 			        closeByBackdrop: false,
	// 			        closeByKeyboard: true,
	// 			        onshow: function(dialog) {
	// 			            // dialog.getButton('button-c').disable();
	// 			        },
	// 		            onshown: function(dialogRef){
	// 		            },
	// 			        buttons:[ {
	// 			        	icon: 'glyphicon glyphicon-remove-sign',
	// 			            label: 'Close',
	// 			            action: function(dialogItself){
	// 			                dialogItself.close();
	// 			            }
	// 			        }]
	// 		        });
	// 			});

	// 			$('.form-horizontal').on('click','button#viewrequisition',function(){
	// 		        BootstrapDialog.show({
	// 		        	title: 'Requisition Details',
	// 		            message: $('<div></div>').load('../dialogs/view-production-requisition.php?id='+id),
	// 		     	    cssClass: 'view-requisition-modal',
	// 			        closable: true,
	// 			        closeByBackdrop: false,
	// 			        closeByKeyboard: true,
	// 			        onshow: function(dialog) {
	// 			            // dialog.getButton('button-c').disable();
	// 			        },
	// 		            onshown: function(dialogRef){
	// 		            },
	// 			        buttons:[ {
	// 			        	icon: 'glyphicon glyphicon-remove-sign',
	// 			            label: 'Close',
	// 			            action: function(dialogItself){
	// 			                dialogItself.close();
	// 			            }
	// 			        }]
	// 		        });

	// 			});

 //            },
	//         buttons:[ {
	//         	icon: 'glyphicon glyphicon-remove-sign',
	//             label: 'Close',
	//             action: function(dialogItself){
	//                 dialogItself.close();
	//             }
	//         }]
 //        });
	// 	return false;
	// });

if(getUrlVars()['specialexternalreleasing']!=undefined)
{
	var id = getUrlVars()['specialexternalreleasing'];
    BootstrapDialog.show({
        title: 'Special External GC Releasing',
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
          'pageToLoad': '../dialogs/releasedgcreport.php?id='+id+'&type=specialexternalreleasing'
        },
        cssClass: 'modal-details-pro',           
        onshown: function(dialogRef){                   
        },
        onhidden: function(dialogRef){
        	window.location = 'index.php';                 
        },
        buttons: [{
          icon: 'glyphicon glyphicon-remove-sign',
          label: ' Close',
          cssClass: 'btn-default',
          action: function(dialogItself){
          	window.location = 'index.php';
          }
        }]
    });  
}


if(getUrlVars()['gcreleaseid']!=undefined)
{
	var id = getUrlVars()['gcreleaseid'];
    BootstrapDialog.show({
        title: 'GC Released No. '+id+' Report',
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
          'pageToLoad': '../dialogs/releasedgcreport.php?id='+id+'&type=regular'
        },
        cssClass: 'modal-details-pro',           
        onshown: function(dialogRef){                   
        },
        onhidden: function(dialogRef){
        	window.location = 'tran_release_gc.php';                 
        },
        buttons: [{
          icon: 'glyphicon glyphicon-remove-sign',
          label: ' Close',
          cssClass: 'btn-default',
          action: function(dialogItself){
          	window.location = 'tran_release_gc.php';
          }
        }]
    });         
}

if(getUrlVars()['specialgcpayment']!=undefined)
{
	var id = getUrlVars()['specialgcpayment'];
    BootstrapDialog.show({
        title: '',
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
          'pageToLoad': '../dialogs/releasedgcreport.php?id='+id+'&type=spgcpayment'
        },
        cssClass: 'modal-details-pro',           
        onshown: function(dialogRef){                   
        },
        onhidden: function(dialogRef){
        	window.location = 'index.php';                 
        },
        buttons: [{
          icon: 'glyphicon glyphicon-remove-sign',
          label: ' Close',
          cssClass: 'btn-default',
          action: function(dialogItself){
          	window.location = 'index.php';
          }
        }]
    });         
}


if(getUrlVars()['gcreleasedinst']!=undefined)
{
	var id = getUrlVars()['gcreleasedinst'];
    BootstrapDialog.show({
        title: '',
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
          'pageToLoad': '../dialogs/releasedgcreport.php?id='+id+'&type=inst'
        },
        cssClass: 'modal-details-pro',           
        onshown: function(dialogRef){                   
        },
        onhidden: function(dialogRef){
        	window.location = 'index.php';                 
        },
        buttons: [{
          icon: 'glyphicon glyphicon-remove-sign',
          label: ' Close',
          cssClass: 'btn-default',
          action: function(dialogItself){
          	window.location = 'index.php';
          }
        }]
    });         
}
if(getUrlVars()['gceod']!=undefined)
{

	var id = getUrlVars()['gceod'];

    BootstrapDialog.show({
        title: '',
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
          'pageToLoad': '../dialogs/releasedgcreport.php?id='+id+'&type=treseod'
        },
        cssClass: 'modal-details-pro',           
        onshown: function(dialogRef){                   
        },
        onhidden: function(dialogRef){
        	window.location = 'index.php';                 
        },
        buttons: [{
          icon: 'glyphicon glyphicon-remove-sign',
          label: ' Close',
          cssClass: 'btn-default',
          action: function(dialogItself){
          	window.location = 'index.php';
          }
        }]
    });  
}

if(getUrlVars()['gcreleaseidpromo']!=undefined)
{
	var id = getUrlVars()['gcreleaseidpromo'];
    BootstrapDialog.show({
        title: 'Promo GC Released No. '+id+' Report',
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
          'pageToLoad': '../dialogs/releasedgcreport.php?id='+id+'&type=promo'
        },
        cssClass: 'modal-details-pro',           
        onshown: function(dialogRef){                   
        },
        onhidden: function(dialogRef){
        	window.location = 'tran_release_promo.php';                 
        },
        buttons: [{
          icon: 'glyphicon glyphicon-remove-sign',
          label: ' Close',
          cssClass: 'btn-default',
          action: function(dialogItself){
          	window.location = 'tran_release_promo.php';
          }
        }]
    });  
}


function getUrlVars() {
  var vars = {};
  var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
  vars[key] = value;
  });
  return vars;
}
var b = '';
var r = '';
function randomColors()
{
	var colors = ["bcforrel1","bcforrel2","bcforrel3","bcforrel4","bcforrel5","bcforrel6","bcforrel7"];
	while(b==r)
	{
		r = Math.floor((Math.random() * colors.length));
	}
	b = r;
	return colors[b];
}

function pendingbudget(id)
{
	location.href = 'new_budget_request.php?id='+id;
}

function pendingproduction(id)
{
	location.href = 'new_production_request.php?id='+id;
}

function validatecurrency(cur)
{
	var regex  = /^\d+(?:\.\d{0,2})$/;
	if (regex.test(cur))
    {
    	return true;
    }
    else 
    {
    	return false;
    }

}

function viewscannedgcstorereceivedPromo()
{
	BootstrapDialog.show({
        title: 'Scanned GC',
        cssClass: 'gc-barcode-modal',
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
            'pageToLoad': '../dialogs/scan_gcrel.php?action=scannedpromogc'
        },
	    onshow: function(dialog) {
	    },
	    onshown: function(dialogRef){

	    },
	    buttons:[{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Remove GC',
            cssClass: 'btn-primary',
            hotkey: 13,
            action:function(dialogItself1){
            	$('.response-checkbox').html('');
            	var checked = document.querySelectorAll('input[type="checkbox"]:checked').length;
            	if(checked == 0)
            	{
					$('.response-checkbox').html('<div class="alert alert-danger alert-dismissable">Please check GC barcode checkbox.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');            		
            		return false;
            	}

		        BootstrapDialog.show({
		        	title: 'Confirmation',
		            message: 'Are you sure you want to removed selectd GC.',
		            closable: true,
		            closeByBackdrop: false,
		            closeByKeyboard: true,
		            onshow: function(dialog) {
		                // dialog.getButton('button-c').disable();										            
		            },
		            onhidden: function(dialog) {

		            },
		            buttons: [{
		                icon: 'glyphicon glyphicon-ok-sign',
		                label: 'Ok',
		                cssClass: 'btn-primary',
		                hotkey: 13,
		                action:function(dialogItself){
		                	$buttons = this;
		                	$buttons.disable();		                	
		                	var formData = $('form#scannedGCForm').serialize(), formUrl = '../ajax.php?action=removedScannedPromoGC';
		                	var trid = $('#trid').val();
		                	formData+="&transid="+trid;
		                	dialogItself.close();
                        	$.ajax({
                        		url:formUrl,
                        		data:formData,
                        		type:'POST',                          
                        		success:function(data1)
                        		{
                        			console.log(data1);
                        			var data1 = JSON.parse(data1) 

                        			if(data1['st'])
                        			{

										d = data1['rscanned'];
										for (var val in d) 
										{
										    var res = d[val].split("=");
										    $("td.scangcx"+res[0]).text(res[1]);
										}
										var dialog = new BootstrapDialog({
							            message: function(dialogRef){
							            var $message = $('<div>Selected GC successfully removed.</div>');			        
							                return $message;
							            },
							            closable:false
								        });
								        dialog.realize();
								        dialog.getModalHeader().hide();
								        dialog.getModalFooter().hide();
								        dialog.getModalBody().css('background-color', '#0088cc');
								        dialog.getModalBody().css('color', '#fff');
								        dialog.open();
								        setTimeout(function(){
					                    	dialog.close();
					                    	dialogItself1.close();
					               		}, 1500);
                        			}
                        			else
                        			{
                        				$('.response-checkbox').html('<div class="alert alert-danger alert-dismissable">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
                        				$button.enable();						                                				
                        			}
                        		}
                        	});									       

		                }
		            },{
		            	icon: 'glyphicon glyphicon-remove-sign',
		                label: 'Cancel',
		                action: function(dialogItself){
		                    dialogItself.close();
		                }
		            }]
			    });	 
            }

        },{
	    	icon: 'glyphicon glyphicon-remove-sign',
	        label: 'Close',
	        action: function(dialogItself1){
	            dialogItself1.close();
	        }
	    }]
	});
}

function viewscannedgcstorereceived()
{
	BootstrapDialog.show({
        title: 'Scanned GC',
        cssClass: 'gc-barcode-modal',
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
            'pageToLoad': '../dialogs/viewscannedgcbystore.php'
        },
	    onshow: function(dialog) {
	    },
	    onshown: function(dialogRef){

	    },
	    buttons:[ {
	    	icon: 'glyphicon glyphicon-remove-sign',
	        label: 'Close',
	        action: function(dialogItself){
	            dialogItself.close();
	        }
	    }]
	});
}

function allocationadjustment(id)
{
	BootstrapDialog.show({
        title: 'Allocation Adjustment Details',
        cssClass: 'gc-barcode-modal',
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
            'pageToLoad': '../dialogs/view-allocation-adjustment.php?id='+id
        },
	    onshow: function(dialog) {
	    },
	    onshown: function(dialogRef){

	    },
	    buttons:[ {
	    	icon: 'glyphicon glyphicon-remove-sign',
	        label: 'Close',
	        action: function(dialogItself){
	            dialogItself.close();
	        }
	    }]
	});
}

function salesReportType(val)
{
	if(val=='all' || val=='today' || val=='curmonth' || val=='yesterday' || val=='thisweek')
	{
		$('input[name=dstart]').prop('disabled',true);
		if($('input[name=dstart]').val()!='')
		$('input[name=dstart]').val('mm/dd/yyyy');
		$('input[name=dend]').prop('disabled',true);
		if($('input[name=dend]').val()!='')
		$('input[name=dend]').val('mm/dd/yyyy');
	}
	else 
	{
		$('input[name=dstart]').prop('disabled',false);
		$('input[name=dend]').prop('disabled',false);	
	}
}

function validateDate(dValue)
{
	var comp = dValue.split('/');

	var m = parseInt(comp[0], 10);
	var d = parseInt(comp[1], 10);
	var y = parseInt(comp[2], 10);
	var date = new Date(y,m-1,d);
	if (date.getFullYear() == y && date.getMonth() + 1 == m && date.getDate() == d) 
	{
	  return true
	} 
	else 
	{
	  return false;
	}
}

function releasePromoGC(id)
{
    BootstrapDialog.show({
    	title: 'Confirmation',
        message: 'Are you sure you want to Create Releasing Entry?',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: true,
        onshow: function(dialog) {
            // dialog.getButton('button-c').disable();

        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Ok',
            cssClass: 'btn-primary',
            hotkey: 13,
            action:function(dialogItself){
            	dialogItself.close();
            	$.ajax({
            		url:'../ajax.php?action=removeSessionPromo'
            	});
				BootstrapDialog.show({
					title: '<i class="fa fa-share"></i> Promo GC Releasing Entry',
					closable: true,
		            closeByBackdrop: false,
		            closeByKeyboard: false,
					cssClass: 'modal-details-rel',
		            message: function(dialog) {
		                var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
		                var pageToLoad = dialog.getData('pageToLoad');
						setTimeout(function(){
		                $message.load(pageToLoad);
						},1000);
		                return $message;
		            },
		            data: {
		                'pageToLoad': '../dialogs/promo.php?id='+id+'&action=promorelease'
		            },
			        onshow: function(dialog) {
			            // dialog.getButton('button-c').disable();
			        },
		            onshown: function(dialog){	
		            	setTimeout(function(){
		            		$('input#remark').focus();
		            	},1000);			
		            	restrictback = 1;  

		           	},
			        onhidden: function(dialog) {
			            restrictback = 0;
			        },				       
			        buttons: [{
			            icon: 'glyphicon glyphicon-ok-sign',
			            label: 'Save',
			            cssClass: 'btn-primary',
			            hotkey: 13,
			            action:function(dialogItselfs){
			            	$('.response').html('');
			            	var $button = this;
			            	var formData = new FormData($('form#gc_srrpromo')[0]), formUrl = $('form#gc_srrpromo').attr('action');
			            	var notEmpty = true, haScanned =false;
			            	var ifjpeg = true;
			            	if($('#upload').val()!=''){
				            	var ext = $('#upload').val().split('.').pop().toLowerCase();
				            	if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
									$('.response').html('<div class="alert alert-danger alert-dismissable">Please upload only image file type.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
                       				$button.enable();
                       				return  false;
								}
							}        
                            $('.form-container .reqfield').each(function(){
                                if($(this).val()=='')
                                {
                                    notEmpty = false;	                                        	                                        	                                        
                                }
                            });	 
                            if(notEmpty)
                            {

                        		$("[class^='scangcx']").each(function(){
                        			if($(this).text()!='0')
                        			{
                        				haScanned = true;
                        			}	                                			
                        		});

                        		if(haScanned)
                        		{	
                        			//check if session exist
                        			$.ajax({
                        				url:'../ajax.php?action=releaseGCpromoValidation',
                        				success:function(data)
                        				{
                        					console.log(data);
                        					var data = JSON.parse(data);
                        					if(data['st'])
                        					{
                        						$button.disable();
										        BootstrapDialog.show({
										        	title: 'Confirmation',
										            message: 'Are you sure you want to save transaction.',
										            closable: true,
										            closeByBackdrop: false,
										            closeByKeyboard: true,
										            onshow: function(dialog) {
										                // dialog.getButton('button-c').disable();										            
										            },
										            onhidden: function(dialog) {
										            	$button.enable();
										            },
										            buttons: [{
										                icon: 'glyphicon glyphicon-ok-sign',
										                label: 'Ok',
										                cssClass: 'btn-primary',
										                hotkey: 13,
										                action:function(dialogItself){
										                	$buttons = this;
										                	$buttons.disable();
										                	dialogItselfs.close();
										                	dialogItself.close();
						                                	$.ajax({
						                                		url:formUrl,
						                                		data:formData,
						                                		type:'POST',
															    contentType: false,
															    processData: false,	                           
						                                		success:function(data1)
						                                		{
						                                			console.log(data1);
						                                			var data1 = JSON.parse(data1) 

						                                			if(data1['st'])
						                                			{

						                                				restrictback = 0;
																		var dialog = new BootstrapDialog({
															            message: function(dialogRef){
															            var $message = $('<div>Transaction Saved.</div>');			        
															                return $message;
															            },
															            closable:false
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
													                    	window.location.href = 'gcreleasedpdfpromo.php?id='+data1['relnum'];
													               		}, 1700);								          
						                                			}
						                                			else
						                                			{
						                                				$('.response').html('<div class="alert alert-danger alert-dismissable">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
						                                				$button.enable();						                                				
						                                			}
						                                		}
						                                	});									       

										                }
										            },{
										            	icon: 'glyphicon glyphicon-remove-sign',
										                label: 'Cancel',
										                action: function(dialogItself){
										                	$button.enable();
										                    dialogItself.close();
										                }
										            }]
											    });	   	                                   					
											}
                        					else 
                        					{
                        						$button.enable();
                        						$('.response').html('<div class="alert alert-danger alert-dismissable">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
                        					}
                        				}
                        			})
                        		}	
                        		else 
                        		{
                        			$('.response').html('<div class="alert alert-danger alert-dismissable">Please scan GC.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
                        		}
                        	}
                        	else 
                        	{
                        		$('.response').html('<div class="alert alert-danger alert-dismissable">Please fill all <span class="requiredf">*</span>required fields.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
                        	}	

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
        }, {
        	icon: 'glyphicon glyphicon-remove-sign',
            label: 'Cancel',
            action: function(dialogItself){
                dialogItself.close();
            }
        }]
    });
}

function addTreasuryCustomer()
{
    BootstrapDialog.show({
        title: 'Add Customer',
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
            'pageToLoad': '../templates/setup.php?page=addtrescustomer'
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
            	$('.response-validate').html('');
            	if($('#company').val()==undefined)
            	{
            		$('.response').html('Please input customer.');
            		$('#company').focus();
            		return false;
            	}

            	if($('#company').val().trim()=='')
            	{
            		$('.response').html('<div class="alert alert-danger" id="danger-x">Please input customer.</div>');
            		$('#company').focus();
            		return false;
            	}

            	if($('#ctype').val().trim()=='')
            	{
            		$('.response').html('<div class="alert alert-danger" id="danger-x">Please select customer type.</div>');
            		$('#ctype').focus();
            		return false;
            	}

            	if($('#ctype').val().trim()=='internal' && $('#gctype').val().trim()=='')
            	{
            		$('.response').html('<div class="alert alert-danger" id="danger-x">Please select gc type.</div>');
            		$('#ctype').focus();
            		return false;
            	}

            	var formURL = $('form#addTresuryCustomer').attr('action'), formDATA = $('form#addTresuryCustomer').serialize();
            	dialogItself.enableButtons(false);
            	dialogItself.setClosable(false);
            	$.ajax({
            		url:'../ajax.php?action=addTresuryCustomer',
            		data:formDATA,
            		type:'POST',
            		success:function(data)
            		{
            			console.log(data);

            			var data = JSON.parse(data);
            			if(data['st'])
            			{
					        BootstrapDialog.show({
					        	title: 'Confirmation',
					            message: 'Add Customer?',
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
					                action:function(dialogItself1){
					                	dialogItself1.close();
						            	dialogItself1.enableButtons(false);
						            	dialogItself1.setClosable(false);	

						            	$.ajax({
						            		url:'../ajax.php?action=addTresuryCustomerSave',
						            		type:'POST',
						            		data: formDATA,
						            		beforeSend:function(){

						            		},
						            		success:function(data1){
						            			var data1 = JSON.parse(data1);					            			

						            			if(data1['st'])
						            			{
						            				dialogItself.close();
													var dialog = new BootstrapDialog({
										            message: function(dialogRef){
										            var $message = $('<div>Customer Successfully Saved.</div>');			        
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
						            				$('.response').html('<div class="alert alert-danger" id="danger-x">'+data1['msg']+'</div>');
									            	dialogItself.enableButtons(true);
									            	dialogItself.setClosable(true);
						            			}
						            		}
						            	});
					                }
					            }, {
					            	icon: 'glyphicon glyphicon-remove-sign',
					                label: 'No',
					                action: function(dialogItself1){
						            	dialogItself.enableButtons(true);
						            	dialogItself.setClosable(true);	
					                    dialogItself1.close();
					                }
					            }]
					        });	
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
            label: 'Close',
            action: function(dialogItself){
                dialogItself.close();
            }
        }]
    });
}












