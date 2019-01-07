restrictback = 0;
$(document).ready(function(){
	window.onbeforeunload = function() { if(restrictback==1){
		return "You work will be lost.";
	} };

	$('#gcstat,#gcbarcodever').inputmask();
	$('#promobarcode').inputmask("integer", { allowMinus: false});

	$('.denfield').inputmask("integer", { allowMinus: false,autoGroup: true, groupSeparator: ",", groupSize: 3,placeholder:'0' });
	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate()+1, 0, 0, 0, 0);

	var checkin = $('#dp1').datepicker({

	    beforeShowDay: function (date) {
	        return date.valueOf() >= now.valueOf();
	    },
	    autoclose: true

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

    $('#customer,#gcrec,#storeRequestList,#list').dataTable( {
        "pagingType": "full_numbers",
        "ordering": false,
        "processing": true
    });

    /////

    $('#gclistavail tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );

    var table = $('#gclistavail').DataTable();

    $('#gclistavail_filter').css('display','none');

    table.columns().every( function () {
        var that = this;
 
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );

    //////

    $('#rmanual').inputmask("integer", { allowMinus: false,rightAlign:false});

    $('.form-container').on('change','.promog',function(){
		$.ajax({
			url:'../ajax.php?action=deleteByIdTempPromo'
		});

		$('.width100').each(function(){
			$(this).val(0);
		});
    });

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
											data:formData,
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

	// $('.form-container').on('submit','form#promoreqFormupdate',function(event)
	// {
	// 	event.preventDefault();
	// 	$('.response').html('');
	// 	var formURL = $(this).attr('action'), formData = new FormData($(this)[0]);	
	// 	var hasqty = false;

	// 	if($('#dp1').val().trim()=='')
	// 	{
	// 		$('.response').html('<div class="alert alert-danger" id="danger-x">Please select date needed.</div>');
	// 		return false;
	// 	}

	// 	var comma = $('#dp1').val().trim().split( new RegExp( "," ) ).length-1;
	// 	if(comma > 1)
	// 	{
	// 		$('.response').html('<div class="alert alert-danger" id="danger-x">Date needed is invalid.</div>');
	// 		return false;			
	// 	}

	// 	var denfield='';
	// 	$('.denfield').each(function(){
	// 		denfield = $(this).val().trim();
	// 		if(denfield!=0)
	// 		{
	// 			if(denfield.length!=0)
	// 			{
	// 				hasqty = true;
	// 				return;
	// 			}
	// 		}
	// 	});

	// 	if(!hasqty)
	// 	{
	// 		$('.response').html('<div class="alert alert-danger" id="danger-x">Please input at least one denomination quantity field.</div>');
	// 		$('#num1').focus();
	// 		return false;
	// 	}

 //        BootstrapDialog.show({
 //        	title: 'Confirmation',
 //            message: 'Are you sure you want to update Promo GC request?',
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
 //                	$buttons = this;
 //                	$buttons.disable();
	// 				$.ajax({
	// 		    		url:formURL,
	// 		    		type:'POST',
	// 					data: formData,
	// 					enctype: 'multipart/form-data',
	// 				    async: true,
	// 				    cache: false,
	// 				    contentType: false,
	// 				    processData: false,
	// 					beforeSend:function(){
	// 					},
	// 					success:function(data1){
	// 						console.log(data1);
	// 						var data1 = JSON.parse(data1);											
	// 						if(data1['st'])
	// 						{
	// 							var dialog = new BootstrapDialog({
	// 				            message: function(dialogRef){
	// 				            var $message = $('<div>Promo GC Request Saved.</div>');			        
	// 				                return $message;
	// 				            },
	// 				            closable: false
	// 					        });
	// 					        dialog.realize();
	// 					        dialog.getModalHeader().hide();
	// 					        dialog.getModalFooter().hide();
	// 					        dialog.getModalBody().css('background-color', '#0088cc');
	// 					        dialog.getModalBody().css('color', '#fff');
	// 					        dialog.open();
	// 					        setTimeout(function(){
	// 		                    	dialog.close();
	// 		               		}, 1500);
	// 		               		setTimeout(function(){
	// 		                    	window.location.href='index.php';
	// 		               		}, 1700);
	// 						} 
	// 						else 
	// 						{
	// 							$('.response').html('<div class="alert alert-danger" id="danger-x">'+data1['msg']+'</div>');												
	// 							$buttons.enable();
	// 						}
	// 					}
	// 				});

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

	$('.form-container').on('submit','form#requisitionForm',function(){
		$('.response').html('');
		var  formUrl = $(this).attr('action'), formData = $(this).serialize();
		var emptyfield = false, checkby = $('#app-checkby').val();
		var status = $('form#requisitionForm #req-status').val();
		if(status=='1')
		{
			if(checkby==='')
			{
				$('.response').html('<div class="alert alert-danger danger-x">Please input all fields.</div>');
				return false;
			}

			//check requisition item setup denoms
			$.ajax({
				url:'../ajax.php?action=checkRequisiton',
				data:formData,
				type:'POST',
				success:function(datacheck)
				{
					console.log(datacheck);
					var datacheck = JSON.parse(datacheck);
					if(datacheck['st'])
					{
				        BootstrapDialog.show({
				        	title: 'Confirmation',
				            message: 'Are you sure you want to create requisition?',
				            closable: true,
				            closeByBackdrop: false,
				            closeByKeyboard: true,
				            onshow: function(dialog) {
				                // dialog.getButton('button-c').disable();
				                $('button#btn').prop('disabled',true);
				            },		
				            onhide: function(dialog) {
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
				                	$('.response').html();
							    	$.ajax({
							    		url:formUrl,
							    		type:'POST',
										data: formData,
							    		beforeSend:function(){
							    			$('#processing-modal').modal('show');
							    		},
							    		success:function(data){
							    			console.log(data);
							    			var data = JSON.parse(data);
							    			setTimeout(function(){
								    			$('#processing-modal').modal('hide');					        			
							        			if(data['st'])
							        			{
													var dialog = new BootstrapDialog({
										            message: function(dialogRef){
										            var $message = $('<div>Transaction Saved. Requisition textfile successfully created.</div>');			        
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
								                    	window.location.href = 'requisreport.php?id='+data['id'];
								               		}, 1700);	        			
							        				
							        			} else{
									    			$('.response').html('<div class="alert alert-danger dangerxx">'+data['msg']+'</div>');
									    		}
							    			},2000);

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
						$('.response').html('<div class="alert alert-danger danger-x">'+datacheck['msg']+'</div>');
					}
				}
			});

		}
		else 
		{
			if(status=='2')
			{
		        BootstrapDialog.show({
		        	title: 'Confirmation',
		            message: 'Disapproved requisition?',
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
					    		beforeSend:function(){

					    		},
					    		success:function(data){
				        			var data = JSON.parse(data);

				        			if(data['st'])
				        			{
										var dialog = new BootstrapDialog({
							            message: function(dialogRef){
							            var $message = $('<div>Requisition disapproved.</div>');			        
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
				        				
				        			} else{
						    			$('.response').html('<div class="alert alert-danger dangerxx">'+data['msg']+'</div>');
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
			else if(status=='3')
			{
		        BootstrapDialog.show({
		        	title: 'Confirmation',
		            message: 'Cancel requisition?',
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
					    		beforeSend:function(){

					    		},
					    		success:function(data){
					    			console.log(data);
				        			var data = JSON.parse(data);

				        			if(data['st']){
										var dialog = new BootstrapDialog({
							            message: function(dialogRef){
							            var $message = $('<div>Requisition cancelled.</div>');			        
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
				        				
				        			} else{
						    			$('.response').html('<div class="alert alert-danger dangerxx">'+data['msg']+'</div>');
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
			
		}
		return false;
	});

	$('#req-status').change(function(){
		var status = $(this).val();
		if(status=='1' || status=='')
		{
			$('.req-disapproved, .req-cancelled').hide();
			$('.request-info').fadeIn(500).show(600);
			$('form#requisitionForm').find('select,input').prop('required',true);
			$('.label-prepared').text('Approved by:');
			
			// $('.request-info#rmanual').prop('required',true);
			// $('.request-info#rremarks').prop('required',true);
			// $('.request-info#selectsupplier').prop('required',true);			
		}
		else 
		{
			if(status=='2')
			{
				$('.req-disapproved').fadeIn(500).show(600);
				$('.req-cancelled').hide();
			}
			else if(status=='3') 
			{
				$('.req-disapproved').hide();
				$('.req-cancelled').fadeIn(500).show(600);
				$('.label-prepared').text('Cancelled by:');

			}	
			$('.request-info').fadeOut(500).hide(600);
			$('form#requisitionForm').find('select,input').prop('required',false);			
			// $('.request-info#rmanual').prop('required',false);
			// $('.request-info#rremarks').prop('required',false);
			// $('.request-info#selectsupplier').prop('required',false);			
		}
	});

	// $('#checkbud').click(function(){
	// 	checked();		
	// });

	// $('#approvedbud').click(function(){
	// 	approved();
	// });

	$('form#requisitionForm').on('change','#selectsupplier',function(){
		var store = $(this).val();
			$.ajax({
				url:'../ajax.php?action=selectSupplier',
				type:'POST',
				data:{store:store},
				beforeSend:function(){

				},
				success:function(response){
					var response = JSON.parse(response);
					if(response['message']=='success'){
						$('#sup-cp').val(response['name']);
						$('#sup-adds').val(response['address']);
						$('#sup-con').val(response['mobile']);

					} else {
						$('#sup-cp').val('');
						$('#sup-adds').val('');
						$('#sup-con').val('');
					}
				}
			});
	});

	$('table#customer tbody.body-supplier').on('click','tr td a.cus-update',function(){
		var id = $(this).attr('href');
		BootstrapDialog.show({
			title: '<i class="fa fa-user"></i> Update Supplier',
		    message: $('<div></div>').load('../dialogs/updatesupplier.php?id='+id),
			    cssClass: 'add-supplier',
		    closable: true,
		    closeByBackdrop: false,
		    closeByKeyboard: true,
		    onshow: function(dialog) {
		        // dialog.getButton('button-c').disable();
		    },
		    onshown: function(dialogRef){
		    	$('form#supplierinfoform input#compname').focus();
		    },
		    buttons: [{
		        icon: 'glyphicon glyphicon-ok-sign',
		        label: 'Submit',
		        cssClass: 'btn-primary',
		        hotkey: 13,
		        action:function(dialogItself){
		        	var emptyfield = false;
				    $('form#supplierinfoform input').each(function() {
				        if(!$(this).val()){
				        	emptyfield=true;
				        }
				    });
			        if(!emptyfield){
	                	var formUrl = $('.form-container form#supplierinfoform').attr('action');
	                	var formData = $('.form-container form#supplierinfoform').serialize();
	                	$.ajax({
	                		url:formUrl,
	                		type:'POST',
	                		data:formData,
	                		beforeSend:function(){

	                		},
	                		success:function(response){
	                			var res = response.trim();

	                			if(res=='success'){
	                				$('table.customer tbody.body-supplier').load('../ajax.php?action=loadsupplier');
	                				BootstrapDialog.closeAll();
	                			} else {
	                				$('.response').html(res);
	                			}				                	
	                		}
	                	});		            	
			        } else {
						$('.response').html('<div class="alert alert-danger #danger-x"> Some fields are empty.</div>');
						timeoutmsg();
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
		return false;
	});
	
	$('table#customer tbody.body-supplier').on('click','tr td a.cus-delete',function(){
		var id = $(this).attr('href');
	        BootstrapDialog.show({
	        	title: 'Confirmation',
	            message: 'Delete Supplier?',
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
	                		url:'../ajax.php?action=supplier-sup',
	                		type:'POST',
	                		data:{id:id},
	                		beforeSend:function(){

	                		},
	                		success:function(response){
	                			var res = response.trim();
	                			if(res=='success'){
	                				$('table.customer tbody.body-supplier').load('../ajax.php?action=loadsupplier');                			
	                			}                			
	                			
	                		}
	                	});                	
	                	dialogItself.close();							
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

	$('.form-container').on('submit','form#locategcForm',function(){
		var formUrl = $(this).attr('action'), formData = $(this).serialize();

			$.ajax({
				url:formUrl,
				type:'POST',
				data:formData,
				beforeSend:function(){

				},
				success:function(response){
					$('.response').html(response);
					$('#gcstat').val('');
				}
			});

		return false;
	});

	function timeoutmsg(){
	    setTimeout(function(){
	    	$('.response').html('');
	    }, 4000);
	}

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

});

function newpromo()
{
		$.ajax({
			url:'../ajax.php?action=truncatepromotemp'
		});
        BootstrapDialog.show({
        	title: '<i class="fa fa-user"></i> New Promo Form',
     	    cssClass: 'newpromo',
			closable: true,
            closeByBackdrop: false,
            closeByKeyboard: false,
            message: function(dialog) {
                var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
                var pageToLoad = dialog.getData('pageToLoad');
				setTimeout(function(){
                $message.load(pageToLoad);
				},1000);
                return $message;
            },
            data: {
                'pageToLoad': '../dialogs/newpromo.php'
            },
            onshown: function(dialogRef){
            	setTimeout(function(){

					$('input[name=promoname]').focus();
					var nowTemp = new Date();
					var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

					var checkin = $('#dp1').datepicker({

					    beforeShowDay: function (date) {
					        return date.valueOf() >= now.valueOf();
					    },
					    autoclose: true

					}).on('changeDate', function (ev) {
					    if (ev.date.valueOf() > checkout.datepicker("getDate").valueOf() || !checkout.datepicker("getDate").valueOf()) {
					        var newDate = new Date(ev.date);
					        newDate.setDate(newDate.getDate() + 1);
					        checkout.datepicker("update", newDate);					        
					    }		    
					});

            	},1010);
            },
	        buttons: [{
	            icon: 'glyphicon glyphicon-ok-sign',
	            label: 'Submit',
	            cssClass: 'btn-primary',
	            hotkey: 13,
	            action:function(dialogItself){
	            	var $buttons = this;	            	
	            	var formURL = $('form#newpromo').attr('action'), formData = $('form#newpromo').serialize();
	            	var notEmpty = true, haScanned = false;
            		$(".reqfield").each(function(){
						 if($(this).val().trim()=='')
						 {
						 	notEmpty = false;
						 }              			
            		});

            		$('.width100').each(function(){
            			if($(this).val()!='0')
            			{
            				haScanned = true;
            			}                            			
            		});

            		if(notEmpty)
            		{
            			if(haScanned)
            			{
            				$buttons.disable();
            				$('.response').html('');
						        BootstrapDialog.show({
						        	title: 'Confirmation',
						            message: 'Are you sure you want to save GC Promo.',
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
						                		url:formURL,
						                		data:formData,
						                		type:'POST',
						                		success:function(data)
						                		{
						                			var data = JSON.parse(data);
						                			if(data['stat'])
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
						                				$('.response').html('<div class="alert alert-danger">Please scan GC.</div>');
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
            			else 
            			{
            				$('.response').html('<div class="alert alert-danger">Please scan GC.</div>');
            			}
            		}	
            		else 
            		{
            			$('.response').html('<div class="alert alert-danger">Please fill up form.</div>');
            		}
            		$buttons.enable();
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

function addPromoGC(id)
{
	var group = $('select.promog').val();
	if(group!='')
	{
			BootstrapDialog.show({
	        title: 'GC Promo Validation',
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

function viewpromo(id)
{
    BootstrapDialog.show({
    	title: '<i class="fa fa-user"></i> Promo Details',
 	    cssClass: 'promodetails',
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
            'pageToLoad': '../dialogs/promodetails.php?id='+id
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

function getUrlVars() {
  var vars = {};
  var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
  vars[key] = value;
  });
  return vars;
}

if(getUrlVars()['request']!=undefined)
{
	var pathArray = window.location.pathname.split( '/' );
	if(pathArray[3]=='index.php')
	{
		var req = getUrlVars()['request'];
		BootstrapDialog.show({
			title: '<i class="fa fa-archive"></i> E-Requisition Report',
			closable: true,
            closeByBackdrop: false,
            closeByKeyboard: false,
			cssClass: 'modal-details-pro',
            message: function(dialog) {
                var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
                var pageToLoad = dialog.getData('pageToLoad');
				setTimeout(function(){
                $message.load(pageToLoad);
				},1000);
                return $message;
            },
            data: {
                'pageToLoad': '../dialogs/requisrepmodal.php?id='+req
            },
	        onshow: function(dialog) {
	            
	        },
	        onhide: function(dialog){
	        	window.location = 'index.php';
	        }
	    });		
	}
}

function verifyDetails(barcode)
{
    BootstrapDialog.show({
    	title: '<i class="fa fa-user"></i> GC Verification Details',
 	    cssClass: 'verification-details',
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
            'pageToLoad': '../dialogs/verifydetails.php?barcode='+barcode
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

function addnewsupplier()
{
    BootstrapDialog.show({
    	title: '<i class="fa fa-user"></i> Add New Supplier',
 	    cssClass: 'add-supplier',
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
            'pageToLoad': '../dialogs/addsupplier.php'
        },
        onshown: function(dialogRef){
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Submit',
            cssClass: 'btn-primary',
            hotkey: 13,
            action:function(dialogItself){
            	$buttons = this;
            	$buttons.disable();
            	var emptyfield = false;
			    $('form#supplierinfoform input').each(function() {
			        if(!$(this).val()){
			        	emptyfield=true;
			        }
			    });
		        if(!emptyfield){
                	var formUrl = $('.form-container form#supplierinfoform').attr('action');
                	var formData = $('.form-container form#supplierinfoform').serialize();
                	$.ajax({
                		url:formUrl,
                		type:'POST',
                		data:formData,
                		beforeSend:function(){

                		},
                		success:function(data){
                			console.log(data);
                			var data  = JSON.parse(data);
                			if(data['st'])
                			{
                				var cname = $('input[name=cname]').val();
                				dialogItself.close();
								var dialog = new BootstrapDialog({
					            message: function(dialogRef){
					            var $message = $('<div>'+cname+' successfully added.</div>');			        
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
			                    	window.location.reload();
			               		}, 1700);	
                			} 
                			else 
                			{
                				$('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">'+data['msg']+'</div>');                				
                			}				                	
                		}
                	});	
	            	
		        } else {
					$('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot"> Please fill in all fields.</div>');
		        }
		        $('input[name=cname]').focus();
		        $buttons.enable();

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

function updateSupplierDetails(id)
{
   BootstrapDialog.show({
    	title: '<i class="fa fa-user"></i> Update Supplier Info',
 	    cssClass: 'add-supplier',
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
            'pageToLoad': '../dialogs/updatesupplier.php?id='+id
        },
        onshown: function(dialogRef){
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Update',
            cssClass: 'btn-primary',
            hotkey: 13,
            action:function(dialogItself){
            	$buttons = this;
            	$buttons.disable();
            	var emptyfield = false;
			    $('form#supplierinfoform input').each(function() {
			        if(!$(this).val()){
			        	emptyfield=true;
			        }
			    });
		        if(!emptyfield){
                	var formUrl = $('.form-container form#supplierinfoform').attr('action');
                	var formData = $('.form-container form#supplierinfoform').serialize();
                	$.ajax({
                		url:formUrl,
                		type:'POST',
                		data:formData,
                		beforeSend:function(){

                		},
                		success:function(data){
                			console.log(data);
                			var data  = JSON.parse(data);
                			if(data['st'])
                			{
                				var cname = $('input[name=cname]').val();
                				dialogItself.close();
								var dialog = new BootstrapDialog({
					            message: function(dialogRef){
					            var $message = $('<div>'+cname+' successfully updated.</div>');			        
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
			                    	window.location.reload();
			               		}, 1700);	
                			} 
                			else 
                			{
                				$('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">'+data['msg']+'</div>');                				
                			}				                	
                		}
                	});	
	            	
		        } else {
					$('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot"> Please fill in all fields.</div>');
		        }
		        $('input[name=cname]').focus();
		        $buttons.enable();
            }
        }, {
        	icon: 'glyphicon glyphicon-remove-sign',
            label: 'Close',
            action: function(dialogItself){
                dialogItself.close();
            }
        }]
    });
	// BootstrapDialog.show({
	// 	title: '<i class="fa fa-user"></i> Update Supplier',
	//     message: $('<div></div>').load('../dialogs/updatesupplier.php?id='+id),
	// 	    cssClass: 'add-supplier',
	//     closable: true,
	//     closeByBackdrop: false,
	//     closeByKeyboard: true,
	//     onshow: function(dialog) {
	//         // dialog.getButton('button-c').disable();
	//     },
	//     onshown: function(dialogRef){
	//     	$('form#supplierinfoform input#compname').focus();
	//     },
	//     buttons: [{
	//         icon: 'glyphicon glyphicon-ok-sign',
	//         label: 'Submit',
	//         cssClass: 'btn-primary',
	//         hotkey: 13,
	//         action:function(dialogItself){
	//         	var emptyfield = false;
	// 		    $('form#supplierinfoform input').each(function() {
	// 		        if(!$(this).val()){
	// 		        	emptyfield=true;
	// 		        }
	// 		    });
	// 	        if(!emptyfield){
 //                	var formUrl = $('.form-container form#supplierinfoform').attr('action');
 //                	var formData = $('.form-container form#supplierinfoform').serialize();
 //                	$.ajax({
 //                		url:formUrl,
 //                		type:'POST',
 //                		data:formData,
 //                		beforeSend:function(){

 //                		},
 //                		success:function(response){
 //                			var res = response.trim();

 //                			if(res=='success'){
 //                				$('table.customer tbody.body-supplier').load('../ajax.php?action=loadsupplier');
 //                				BootstrapDialog.closeAll();
 //                			} else {
 //                				$('.response').html(res);
 //                			}				                	
 //                		}
 //                	});		            	
	// 	        } else {
	// 				$('.response').html('<div class="alert alert-danger #danger-x"> Some fields are empty.</div>');
	// 				timeoutmsg();
	// 	        }
	//         }
	//     }, {
	//     	icon: 'glyphicon glyphicon-remove-sign',
	//         label: 'Close',
	//         action: function(dialogItself){
	//             dialogItself.close();
	//         }
	//     }]

	// });
}

function removescanpromogc(barcode)
{
    BootstrapDialog.show({
    	title: 'Confirmation',
        message: 'Are you sure you want to remove GC Barcode # '+barcode+'?',
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
            	$.ajax({
            		url:'../ajax.php?action=removebarcodefromassignedpromo',
            		data:{barcode:barcode},
            		type:'POST',
            		success:function(data)
            		{
            			console.log(data);
            			var data = JSON.parse(data);
            			if(data['st'])
            			{
            				var scan = parseInt($('input.sc'+data['denom']).val());
            				scan--;
            				$('input.sc'+data['denom']).val(scan);
            				BootstrapDialog.closeAll();
            			}
            			else 
            			{
            				// alert(data['msg']);
            				// BootstrapDialog.closeAll();		
            			}
            		}
            	});
            	// BootstrapDialog.closeAll();			
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



function pendingPromoGCRequestMarketing(id)
{
	location.href = 'promo-gc-request-update.php?id='+id;
}

function pendingPromoGCRequest(id)
{
	location.href = 'promo-gc-request-approval.php?id='+id;
}

function showGCforAllocation()
{
    BootstrapDialog.show({
        title: 'GC For Allocation',
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
          'pageToLoad': '../dialogs/view-gcforallocation.php'
        },
        cssClass: 'modal-allocated-gc',           
        onshown: function(dialogRef){                   
        },
        onhidden: function(dialogRef){
                
        },
        buttons: [{
          icon: 'glyphicon glyphicon-remove-sign',
          label: ' Close',
          cssClass: 'btn-default',
          action: function(dialogItself){
            dialogItself.close();
          }
        }]
    }); 
}

