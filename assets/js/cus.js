
$(document).ready(function(){

	// var pathArray = window.location.pathname.split( '/' );
	// alert(pathArray[4]);

	// var sp = window.location.search.split('=')[1];

	// if(sp!=undefined)
	// {
	// 	BootstrapDialog.show({
	// 		title: '<i class="fa fa-archive"></i> Custodian Receiving Report',
	// 		closable: true,
 //            closeByBackdrop: false,
 //            closeByKeyboard: false,
	// 		cssClass: 'modal-details-pro',
 //            message: function(dialog) {
 //                var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
 //                var pageToLoad = dialog.getData('pageToLoad');
	// 			setTimeout(function(){
 //                $message.load(pageToLoad);
	// 			},1000);
 //                return $message;
 //            },
 //            data: {
 //                'pageToLoad': '../custodian/rep.php?id='+sp
 //            },
	//         onshow: function(dialog) {
	            
	//         },
	//         onhide: function(dialog){
	//         	window.location = 'index.php';
	//         }
	//     });
	// }

	if(getUrlVars()['reqreport']!=undefined)
	{
		var rep = getUrlVars()['reqreport'];
		BootstrapDialog.show({
			title: '<i class="fa fa-archive"></i> Custodian Receiving Report',
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
                'pageToLoad': '../custodian/rep.php?id='+rep
            },
	        onshow: function(dialog) {
	            
	        },
	        onhide: function(dialog){
	        	window.location = 'index.php';
	        }
	    });

	}

	function getUrlVars() {
	  var vars = {};
	  var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
	  vars[key] = value;
	  });
	  return vars;
	}

	$.extend( $.fn.dataTableExt.oStdClasses, {	  
	    "sLengthSelect": "selectsup"
	});
    $('#gcrec,#cusRec').dataTable( {
        "pagingType": "full_numbers",
        "ordering": false,
        "processing": true
    });

	$('#gcbarcode').inputmask();
	$('.form-container').on('submit','form#gcvalidation',function(){
		var formUrl = $(this).attr('action'), formData = $(this).serialize(),id = $('input[name=prod_id]').val();
		$.ajax({
			url:formUrl,
			type:'POST',
			data:formData,
			beforeSend:function(){

			},
			success:function(response){
				var response = JSON.parse(response);
            	$('.response').html(response['message']);
            	$('#gcbarcode').focus().val('');
            	$('div.form-container table.table.table-responsive.gcforvalidation-heading tbody.gcforvalidation').load('../ajax.php?action=validategcbarcode&id='+id);
			}
		});
		return false;
	});

	$('button.gcreports').click(function(){
		window.open('../custodian/gcreports.php', '_blank');
	});

	$('.form-container').on('submit','form#receivegc',function(){
		var formData = $(this).serialize(), formUrl = $(this).attr('action');
		var notEmpty = true, prod_id = $('#p_num').val(),id = $('input[name=prod_id]').val();

		$('.num').each(function(){
			if($(this).val()=='')
			{
				notEmpty = true;
			}
		});

		if(notEmpty){
	        BootstrapDialog.show({
	        	title: 'Confirmation',
	            message: 'Perform Action?',
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
							url:formUrl,
							type:'POST',
							data:formData,
							success:function(response)
							{
								var res = response.trim();
								if(res=='success')
								{
									var dialog = new BootstrapDialog({
						            message: function(dialogRef){
						            var $message = $('<div>GC Production Number '+prod_id+' successfully received.</div>');			        
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
				                    	window.location.href = 'gcvalidation.php?proid='+id;
				               		}, 1700);
								}
								else 
								{
									$('.response').html('<div class="alert alert-danger alert-dismissable">Something went wrong.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
								}
							}
						});
						dialogItself.close();				
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
		else 
		{
			$('.response').html('<div class="alert alert-danger alert-dismissable">Please fill all fields.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
		}
		return false;
	});

	$('i.gen_report').click(function(){
		var report_id = $(this).attr('report_id');
		window.open('gcreports.php?id='+report_id);
	});

	$('form#gctrack').on('submit',function(){	
		$('.response').html('');	
		var gc = $('#trackbarcode').val(), formURL = $(this).attr('action');
		$.ajax({
			url:formURL,
			data:{gc:gc},
			type:'POST',
			success:function(data)
			{
				var data = JSON.parse(data);
				if(data['st'])
				{
					$('.response').html('<div class="alert alert-success success1">'+data['msg']+'</div>');
				}
				else 
				{
					$('.response').html('<div class="alert alert-danger alert-dismissable">'+data['msg']+'</div>');
				}
			}
		});
		// $('.response').html('<div class="alert alert-danger alert-dismissable">Sample</div>');
		return false;
	});

	function timeoutmsg(div){
	    setTimeout(function(){
	    	$(div).html('');
	    }, 2000);
	}

	$('.form-container').on('submit','form#gc_srr',function(){
		$('button#srrbut').prop('disabled',true);
		$('.response').html('');
    	var hasEmpty = false, NotmptyDenom = true, hasScanned = true, denom = [],scanned = [];				         
    	if(hasEmpty)
    	{
    		$('.response').html('<div class="alert alert-danger alert-dismissable">Some fields are empty.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
    		$('button#srrbut').prop('disabled',false);
    		return false;
    	}
    	else 
    	{
        	$("input[name^=den]").each(function(){
        		//alert($(this).val());
				var den = $(this).val().trim();
				denom.push(den);
				if((den!="0") && (den!==""))
				{
					NotmptyDenom = false;										
				}

        	});

        	$("input[name^=scan]").each(function(){
				var scan = $(this).val().trim();
				scanned.push(scan);
				if((scan!="0") && (scan!==""))
				{
					hasScanned = false;										
				}
        	});

			// $('.den1,.den2,.den3,.den4,.den5,.den6').each(function(){
			// 	var den = $(this).val().trim();
			// 	denom.push(den);
			// 	if((den!="0") && (den!==""))
			// 	{
			// 		NotmptyDenom = false;										
			// 	}
			// });

			// $('.n1,.n2,.n3,.n4,.n5,.n6').each(function(){
			// 	var scan = $(this).val().trim();
			// 	scanned.push(scan);
			// 	if((scan!="0") && (scan!==""))
			// 	{
			// 		hasScanned = false;										
			// 	}
			// });
    	}

		if(NotmptyDenom)
		{
			$('.response').html('<div class="alert alert-danger alert-dismissable">Please fill in all fields.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
    		$('button#srrbut').prop('disabled',false);
    		return false;
		}
		else 
		{
			if(!hasScanned)
			{
				var coincide = true;
				// for (var i = 0; i < scanned.length; i++) {
				// 	if(scanned[i]!=denom[i])
				// 	{
				// 		coincide = false;
				// 		break;
				// 	}											
				// };

				// if(coincide)
				// {	
					//Validate GC
			       BootstrapDialog.show({
			        	title: 'Confirmation',
			            message: 'Are you sure you want to Save Transaction?',
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
			                	var $buttons = this;
			                	var recnump = $('input[name=gcrecno]').val();
				            	var formData = $('form#gc_srr').serialize();
				            	var formUrl = $('form#gc_srr').attr('action');
				            	dialogItself.close();
				            	$.ajax({
				            		url:formUrl,
				            		data:formData,
				            		type:'POST',
				            		beforeSend:function()
				            		{
				            			$('#processing-modal').modal('show');
				            		},
				            		success:function(data)
				            		{												
				            			console.log(data);
				            			$('#processing-modal').modal('hide');
				            			var data = JSON.parse(data);
				            			if(data['st'])
				            			{		
				            				restrictback = 0;
				            				$buttons.disable();
				            				BootstrapDialog.closeAll();
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
									        	window.location = 'gcrep.php?id='+data['srrid'];
						               		}, 1600);	
				            			}
				            			else 
				            			{
				            				$('.response').html('<div class="alert alert-danger alert-dismissable">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
				            				$('button#srrbut').prop('disabled',false);
    										return false;
				            				
				            			}
				            		}
				            	});
			                }
			            },{
			            	icon: 'glyphicon glyphicon-remove-sign',
			                label: 'Cancel',
			                action: function(dialogItself){
			                	$('button#srrbut').prop('disabled',false);
			                    dialogItself.close();
			                }
			            }]
			       });
				// }
				// else 
				// {
				// 	$('.response').html('<div class="alert alert-danger alert-dismissable">Validated GC must equal to Qty Received.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
    // 				$('button#srrbut').prop('disabled',false);
				// 	return false;
				// }
			}
			else
			{
				$('.response').html('<div class="alert alert-danger alert-dismissable">Please scan GC.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
				$('button#srrbut').prop('disabled',false);
			}
		}
		return false;
	});

});

function validateGCus(recid)
{
	if($('input[name=rectype]').val()=='')
	{
		alert('Please Upload FAD P.O. File first.');
	}
	else
	{
		BootstrapDialog.show({
        	title: '<i class="fa fa-bookmark"></i> Validate By GC Barcode',
            message: $('<div></div>').load('../dialogs/validatecusgc.php?recid='+recid),
     	    cssClass: 'modal-validate',
	        closable: true,
	        closeByBackdrop: false,
	        closeByKeyboard: true,
	        onshow: function(dialog) {
	            // dialog.getButton('button-c').disable();
	        },
            onshown: function(dialogRef){
            	$('#gcbarcode').inputmask();
            	$('#gcbarcode').focus();

            }, 
	        buttons: [{
	            icon: 'glyphicon glyphicon-ok-sign',
	            label: 'Submit',
	            cssClass: 'btn-primary',
	            hotkey: 13,
	            action:function(dialogItself){

	            	var $buttons = this,rectype = $('input[name=rectype]').val();
	            	var formUrl = $('.modal-validate .modal-dialog form#srrvalidate').attr('action');
	            	var formData = $('.modal-validate .modal-dialog form#srrvalidate').serialize();
	            	var ereq = $('input[name="requisid"]').val()
	            	var barcode = $('#gcbarcode').val();
	            	// formData +='&mnl_num='+mnl_num;
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
	            	if(rectype=='')
	            	{
	            		$('.response-validate').html('<div class="alert alert-danger alert-dismissable alert-no-bot">Please upload FAD P.O. first.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
	            	}
	            	else 
	            	{
		            	if(barcode!='')
		            	{
		            		if(barcode.length == 13)
		            		{
		            			$.ajax({
		            				url:formUrl,
		            				data:formData,
		            				type:'POST',
		            				success:function(data){
		            					console.log(data);
		            					var data = JSON.parse(data);
		            					if(data['stat']==1)
		            					{
		            						$('.response-validate').html(data['msg']);
		            						var qty = $('input.n'+data['den_id']).val();
		            						qty++;
		            						$('input.n'+data['den_id']).val(qty);
		            					}
		            					else 
		            					{
		            						$('.response-validate').html('<div class="alert alert-danger validate-flash" id="_adjust_alert">'+data['msg']+'</div>');
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
		            		$('.response-validate').html('<div class="alert alert-danger alert-dismissable alert-no-bot">Please scan GC.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
		            		timeoutmsg('.response-validate');			            		
		            	}
	            	}
	            	$('#gcbarcode').select();
	            	// $('#gcbarcode').val('');
	            	

								            								            	
	            	// if(barcode!='')
	            	// {
	            	// 	if(barcode.length == 13)
	            	// 	{
	            	// 		$.ajax({
	            	// 			url:formUrl,
	            	// 			data:formData,
	            	// 			type:'POST',
	            	// 			success:function(data){

	            	// 				var data = JSON.parse(data);
	            	// 				if(data['stat']==1)
	            	// 				{
	            	// 					$('.response-validate').html(data['msg']);
	            	// 					if(data['full']==1)
	            	// 					{
	            	// 						$('.inputGcbarcode').html('');
	            	// 						$('.response-full').html('<div class="row"><div class="col-offset-sm-8 col-sm-4"><button class="btn btn-default btn-block cusreport">Report</button></div></div>');																            							
	            	// 						$buttons.disable();
	            	// 					}
	            	// 				}
	            	// 				else 
	            	// 				{
	            	// 					$('.response-validate').html(data['msg']);
		            // 					// timeoutmsg('.response-validate');
	            	// 				}
	            	// 				// $('.response-validate').html(response);
	            	// 			}
	            	// 		});

	            	// 	}
	            	// 	else 
	            	// 	{
		            // 		$('.response-validate').html('<div class="alert alert-danger alert-dismissable alert-no-bot">GC Barcode Number must be at least 13 character long.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
		            // 		timeoutmsg('.response-validate');
	            	// 	}

	            	// }
	            	// else 
	            	// {
	            	// 	$('.response-validate').html('<div class="alert alert-danger alert-dismissable alert-no-bot">Please scan GC.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
	            	// 	timeoutmsg('.response-validate');
	            	// }
	            	// $('#gcbarcode').focus();
	            	// $('#gcbarcode').val('');

	            }
	        }]

        });
    }			
}

function validateGCusByRange(recid)
{
	if($('input[name=rectype]').val()=='')
	{
		alert('Please Upload FAD P.O. File first.');
	}
	else
	{
		BootstrapDialog.show({
        	title: '<i class="fa fa-bookmark"></i> Validate By GC Barcode # Range',
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

															// var dialog = new BootstrapDialog({
												   //          message: function(dialogRef){													           
												   //          var $message = $('<div class="rangeval"><img src="../assets/images/ajax.gif"></div>');			        
												   //              return $message;
												   //          },
												   //          closable: false
													  //       });
													  //       dialog.realize();
													  //       dialog.getModalHeader().hide();
													  //       dialog.getModalFooter().hide();
													  //       dialog.getModalContent().css('background-color','none');
													  //       dialog.getModalBody().css('color', '#fff');
													  //       dialog.open();
													  //       setTimeout(function(){
															// 		    $.ajax({
															// 		    	url:formURL,
															// 		    	data:formData,
															// 		    	type:'POST',
															// 		    	success:function(data)
															// 		    	{
															// 		    		var data = JSON.parse(data);
															// 		    		if(data['stat'])
															// 		    		{
															// 						$container.html('<div class="alert alert-info validate-flash" id="_adjust_alert">'+
															// 						'<p class="bar-range"><img src="../assets/images/ajax.gif">Validating Barcode Number: </p>'+
															// 						'<p class="br">'+bstart+'</p>'+
															// 						'<p class="den">Denomination:<span class="den-color"> &#8369 '+denom+'</span></p>');                    
															// 						setTimeout(step, 10); 
															// 						bstart++;
															// 						ngc++;
															// 						$('.n'+denStart).val(ngc);
															// 		    		}
															// 		    		else 
															// 		    		{
															// 	    				dialog.close();
															// 			    		//dialogItself.close();
															// 						$('.validateGCstart').prop('readonly','');	
															// 						$('.validateGCend').prop('disabled',true);	
															// 						$('.validateGCend').val('');	
															// 						$('input[name="dens"]').val('');	
															// 						$('.validateGCstart').val('').focus();
															// 						$('.responserange').html('');		
															// 						$('input[name="flag"]').val(1);																		
															// 			    		$('.responserange').html('<div class="alert alert-danger">'+data['msg']+'</div>');
															// 		    		}
															// 		    	}
															// 		    });	
													  //       },200);															        												        
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
}

function timeoutmsg(div){
    setTimeout(function(){
    	$(div).html('');
    }, 2000);
}

function receivingEntry(prid,requisid,req_stat)
{
	if(req_stat!=2)
	{
        BootstrapDialog.show({
        	title: 'Confirmation',
            message: 'Are you sure you want to create SRR?',
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
                	$button = this;
                	$button.disable();
                	dialogItself.close();
                	$.ajax({
                		url:'../ajax.php?action=truncateTempVal'	                		
                	});	                	
					BootstrapDialog.show({
						title: '<i class="fa fa-archive"></i> Receiving Entry',
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
			                'pageToLoad': '../dialogs/srr.php?id='+requisid
			            },
				        onshow: function(dialog) {
				            // dialog.getButton('button-c').disable();
				        },
			            onshown: function(dialogRef){
							$('.modal-details-pro').on('change','form#gc_srr input#poupload',function(){
								var file = this.files[0];
								var formData = new FormData();
								formData.append('formData', file);
								formData.append('ereqnum', requisid);
								formData.append('reqid', prid);
								$.ajax({
								    url: '../ajax.php?action=podata',  //Server script to process data
								    type: 'POST',
								    data: formData,
								    contentType: false,
								    processData: false,
								    //Ajax events
								    success: function(response)
								    {
								    	console.log(response);
								        var data = JSON.parse(response);
								        if(data['status'])
								        {		
								        	$('div.modal-details-pro div.row input.form-control.input-sm.fadrec').val(data['fadrec']);
								        	$('div.modal-details-pro div.row input.form-control.input-sm.trandate').val(data['trandate']);
								        	$('div.modal-details-pro div.row input.form-control.input-sm.refno').val(data['refno']);
								        	$('div.modal-details-pro div.row input.form-control.input-sm.purono').val(data['purono']);
								        	$('div.modal-details-pro div.row input.form-control.input-sm.purdate').val(data['purdate']);
								        	$('div.modal-details-pro div.row input.form-control.input-sm.refpono').val(data['refpono']);
								        	$('div.modal-details-pro div.row input.form-control.input-sm.payterms').val(data['payterms']);
								        	$('div.modal-details-pro div.row input.form-control.input-sm.locode').val(data['locode']);
								        	$('div.modal-details-pro div.row input.form-control.input-sm.deptcode').val(data['deptcode']);
								   			$('div.modal-details-pro div.row input.form-control.input-sm.supname').val(data['supname']);
								   			$('div.modal-details-pro div.row input.form-control.input-sm.modpay').val(data['modpay']);
								   			$('div.modal-details-pro div.row input.form-control.input-sm.remarks').val(data['remarks']);
								   			$('div.modal-details-pro div.row input.form-control.input-sm.prepby').val(data['prepby']);
								   			$('div.modal-details-pro div.row input.form-control.input-sm.checkby').val(data['checkby']);
								   			$('div.modal-details-pro div.row input.form-control.input-sm.srrtype').val(data['srrtype']);
								   			$('div.modal-details-pro div.row input.form-control.input-sm.den1').val(data['den1']);
								   			$('div.modal-details-pro div.row input.form-control.input-sm.den2').val(data['den2']);
								   			$('div.modal-details-pro div.row input.form-control.input-sm.den3').val(data['den3']);
								   			$('div.modal-details-pro div.row input.form-control.input-sm.den4').val(data['den4']);
								   			$('div.modal-details-pro div.row input.form-control.input-sm.den5').val(data['den5']);
								   			$('div.modal-details-pro div.row input.form-control.input-sm.den6').val(data['den6']);
								   			$('.group-wrap input#rnumgc').each(function(){
								   				if($(this).val().trim()=='')
								   				{
								   					$(this).val(0);
								   				}
								   			});
								        }
								        else 
								        {
								        	alert(data['error']);
								        	BootstrapDialog.closeAll();
								        }
								    }	
								});
							});
			            }, 
				        buttons: [{
				            icon: 'glyphicon glyphicon-ok-sign',
				            label: 'Save',
				            cssClass: 'btn-primary',
				            hotkey: 13,
				            action:function(dialogItself){
				            	$button = this;
				            	$button.disable();
				            	var hasEmpty = false, NotmptyDenom = true, hasScanned = true, denom = [],scanned = [];				         
				            	$('.reqfield , .sxs-int').each(function(){
				            		if($(this).val().trim()=='')
				            		{
				            			hasEmpty = true;
				            		}
				            	});

				            	if(hasEmpty)
				            	{
				            		$('.response').html('<div class="alert alert-danger alert-dismissable">Please fill in all fields.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
				            		timeoutmsg('.response');
				            	}
				            	else 
				            	{					            		
									$('.den1,.den2,.den3,.den4,.den5,.den6').each(function(){
										var den = $(this).val().trim();
										denom.push(den);
										if((den!="0") && (den!==""))
										{
											NotmptyDenom = false;										
										}
									});

									$('.n1,.n2,.n3,.n4,.n5,.n6').each(function(){
										var scan = $(this).val().trim();
										scanned.push(scan);
										if((scan!="0") && (scan!==""))
										{
											hasScanned = false;										
										}
									});

									if(NotmptyDenom)
									{
										$('.response').html('<div class="alert alert-danger alert-dismissable">Please fill in all fields.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
										timeoutmsg('.response');
									}
									else 
									{
										if(!hasScanned)
										{
											var coincide = true;
											for (var i = 0; i < scanned.length; i++) {
												if(scanned[i]!=denom[i])
												{
													coincide = false;
													break;
												}											
											};

											if(coincide)
											{	
												//Validate GC
										       BootstrapDialog.show({
										        	title: 'Confirmation',
										            message: 'Are you sure you want to Save Transaction?',
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
										                	var $buttons = this;
										                	var recnump = $('input[name=gcrecno]').val();
											            	var formData = $('.modal-details-pro form#gc_srr').serialize();
											            	var formUrl = $('.modal-details-pro form#gc_srr').attr('action');
											            	$.ajax({
											            		url:formUrl,
											            		data:formData,
											            		type:'POST',
											            		success:function(data)
											            		{												
											            			console.log(data);
											            			var data = JSON.parse(data);
											            			if(data['st'])
											            			{		
											            				$buttons.disable();
											            				BootstrapDialog.closeAll();
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
																        	window.location = 'gcrep.php?id='+data['srrid'];
													               		}, 1600);	
											            			}
											            			else 
											            			{
											            				$('.response').html('<div class="alert alert-danger alert-dismissable">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
											            				// timeoutmsg('.response');
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
												$('.response').html('<div class="alert alert-danger alert-dismissable">Quantity scanned must equal with received GC.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
												timeoutmsg('.response');
											}
										}
										else
										{
											$('.response').html('<div class="alert alert-danger alert-dismissable">Please scan GC.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
											timeoutmsg('.response');
										}
									}
				            	}
				            	$button.enable();
				            }
				        }, {
				        	icon: '',
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
							            	var formData = $('form#custodianmanager').serialize(), formURL = $('form#custodianmanager').attr('action');	
							            	console.log(formURL);
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
}

function custodianreceivedgc(id)
{
	BootstrapDialog.show({
        title: 'GC Received # '+id+' Details',
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
            'pageToLoad': '../dialogs/custodianreceivedgc.php?id='+id
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

function managerKeyValidateRange()
{
	BootstrapDialog.show({
		title: '<i class="fa fa-user"></i></i> Manager Login',
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
            'pageToLoad': '../dialogs/managerkey.php'
        },
        onshown: function(dialog) {
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Submit',
            cssClass: 'btn-primary',
            hotkey: 13,
            action:function(dialogItself){
            	$('.responsemanager').html('');
                dialogItself.enableButtons(false);
                dialogItself.setClosable(false);
                if($('input[name=username]').val()!=undefined)
                {
                	var formData = $('form#managerkey').serialize(), formURL = $('form#managerkey').attr('action');
                	if($('input[name=username]').val()!='' && $('input[name=password]').val()!='')
                	{        
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
                					alert(data['msg']);
                					dialogItself.close();
                					var recnum = $('input[name=gcrecno]').val();
									validateGCusByRange(recnum);
                				}
                				else 
                				{
                					$('.responsemanager').html('<div class="alert alert-danger">'+data['msg']+'</div>');
			                		dialogItself.enableButtons(true);
			                		dialogItself.setClosable(true);
                				}
                			}
                		});
                	}
                	else 
                	{
                		$('.responsemanager').html('<div class="alert alert-danger">Please input username/password.</div>');
                		dialogItself.enableButtons(true);
                		dialogItself.setClosable(true);
                	}
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

function managerKey()
{
	BootstrapDialog.show({
		title: '<i class="fa fa-user"></i></i> Manager Login',
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
            'pageToLoad': '../dialogs/managerkey.php'
        },
        onshown: function(dialog) {
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Submit',
            cssClass: 'btn-primary',
            hotkey: 13,
            action:function(dialogItself){
            	$('.responsemanager').html('');
                dialogItself.enableButtons(false);
                dialogItself.setClosable(false);
                if($('input[name=username]').val()!=undefined)
                {
                	var formData = $('form#managerkey').serialize(), formURL = $('form#managerkey').attr('action');
                	if($('input[name=username]').val()!='' && $('input[name=password]').val()!='')
                	{        
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
                					alert(data['msg']);
                					dialogItself.close();
                					var recnum = $('input[name=gcrecno]').val();
									validateGCusByRange(recnum);
                				}
                				else 
                				{
                					$('.responsemanager').html('<div class="alert alert-danger">'+data['msg']+'</div>');
                				}
                			}
                		});
                	}
                	else 
                	{
                		$('.responsemanager').html('<div class="alert alert-danger">Please input username/password.</div>');
                	}
                }
      			dialogItself.enableButtons(true);
                dialogItself.setClosable(true);
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

function scannedGCCus(recnum)
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
            'pageToLoad': '../dialogs/view-custodian-scanned.php?id='+recnum
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

function removescannedgc(barcode)
{
	var recno = $('input[name=gcrecno]').val();
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
            		url:'../ajax.php?action=removescannedgccustodian',
            		data:{barcode:barcode,recno:recno},
            		type:'POST',
            		success:function(data)
            		{
            			console.log(data);
            			var data = JSON.parse(data);
            			if(data['st'])
            			{
            				var scan = $('input.n'+data['denom']).val();
            				scan--;
            				$('input.n'+data['denom']).val(scan);
            				BootstrapDialog.closeAll();
            			}
            			else 
            			{
            				alert(data['msg']);
            				BootstrapDialog.closeAll();		
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

