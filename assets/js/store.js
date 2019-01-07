$(document).ready(function(){
	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

	var checkin = $('#dp1').datepicker({

	    beforeShowDay: function (date) {
	        return date.valueOf() >= now.valueOf();
	    },
	    autoclose: true

	});

	 $('[data-toggle="tooltip"]').tooltip();


	$.extend( $.fn.dataTableExt.oStdClasses, {	  
	    "sLengthSelect": "selectsup"
	});

    $('#customer, #storePendingRequest, #ledgertable, #storeod, #stores,#sexcustomer,#storeRequestList').dataTable( {
        "pagingType": "full_numbers",
        "ordering": false,
        "processing": true
    });

	$("input[name=dstart], input[name=dend]").inputmask("m/d/y").val('__/__/_____').prop('disabled', false);
	$("input[name=dstart], input[name=dend]").prop('disabled',true);

    // $("div#ledgertable_wrapper input[type=search]").keyup(function(){
    // 	$('div#ledgertable_wrapper tfoot tr th.totdeb').html(addCommas(getTotalRows(5)));
    // 	$('div#ledgertable_wrapper tfoot tr th.totcred').html(addCommas(getTotalRows(6)));
    // });

    // $("div#ledgertable_wrapper select.selectsup").change(function(){
    // 	$('div#ledgertable_wrapper tfoot tr th.totdeb').html(addCommas(getTotalRows(5)));
    // 	$('div#ledgertable_wrapper tfoot tr th.totcred').html(addCommas(getTotalRows(6)));
    // });
    // $('div#ledgertable_wrapper tfoot tr th.totdeb').html(addCommas(getTotalRows(5)));
    // $('div#ledgertable_wrapper tfoot tr th.totcred').html(addCommas(getTotalRows(6)));

	$('#num1,#num2,#num3,#num4,#num5,#num6,#gcbarcodever,#numinternald,#ninternalcusd,#ninternalcusq,.amount-external').inputmask();

	$( "#_vercussearch" ).bind({
		keyup: function() {
			var cust = $(this).val().trim();
			if(cust.length > 2)
			{
				$('span.xcus').show();
				$('span.xcus').html("<img src='../assets/images/ring-alt.svg' class='loadver'> Processing Please Wait..");
				$.ajax({
		    		url:'../ajax.php?action=searchCustomerVerification',
		    		data:{cust:cust},
		    		type:'POST',
					beforeSend:function(){
						
					},
					success:function(data){
						console.log(data);
						var data = JSON.parse(data);
						if(data['st'])
						{
							$('span.xcus').html(data['msg']);	
						}
						else 
						{
							$('span.xcus').html(data['msg']);
						}
					}
				});
			}
			else 
			{
				$('span.xcus').hide();
			}
		},
	});

	$('div.col-sm-8').on('click','span.xcus ul li.vernames',function(){
		var name = $(this).text();
		var id = $(this).attr('data-id');
		var fname = $(this).attr('data-fname');
		var mname = $(this).attr('data-mname');
		var lname = $(this).attr('data-lname');
		var next = $(this).attr('data-namext');
		$('span.xcus').hide();
		$('span.xcus').html('');
		$('#_vercussearch').val(name);
		$('#cid').val(id);
		$('#fname').val(fname);
		$('#lname').val(lname);
		$('#mname').val(mname);
		$('#next').val(next);
		$('#gcbarcodever').focus();
	});	

	$('#_customermanage').DataTable( {
		"processing": true,
		"serverSide": true,
		"ajax":{
			url :"../ajax.php?action=getAllVerifiedCustomer", // json datasource
			type: "post",  // method  , by default get
			error: function(data){  // error handling
				console.log(data);
				$(".employee-grid-error").html("");
				$("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
				$("#employee-grid_processing").css("display","none");				
			}
		}
	} );	

	$('#_verifiedGC').DataTable( {
		"processing": true,
		"serverSide": true,
		"ajax":{
			url :"../ajax.php?action=getAllVerifiedGC", // json datasource
			type: "post",  // method  , by default get
			error: function(data){  // error handling
				console.log(data);
				$(".employee-grid-error").html("");
				$("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
				$("#employee-grid_processing").css("display","none");				
			}
		}
	} );	

	$('#_soldGCList').DataTable( {
		"processing": true,
		"serverSide": true,
		"ajax":{
			url :"../ajax.php?action=getAllSoldGCList", // json datasource
			type: "post",  // method  , by default get
			error: function(data){  // error handling
				console.log(data);
				$(".employee-grid-error").html("");
				$("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
				$("#employee-grid_processing").css("display","none");				
			}
		}
	});	

	$('.form-container').on('submit','#storeRequest',function(){	
		$('.response').html('');	
		var hasqty = false;
		var formUrl = $(this).attr('action'), formData = new FormData($(this)[0]);

		$('.reqfield').each(function(){
			var fl = $(this).val().trim();
			if(fl!=0)
			{
				hasqty = true;
				return; 
			}
		});

		if(hasqty)
		{
			if($('#dp1').val().trim()!='')
			{
		        BootstrapDialog.show({
		        	title: 'Confirmation',
		            message: 'Are you sure you want to submit GC Request?',
		            closable: true,
		            closeByBackdrop: false,
		            closeByKeyboard: true,
		            onshow: function(dialog) {
		                // dialog.getButton('button-c').disable();
		                $('#btnSubmit').prop("disabled",true);
		            },
		            onhidden:function(dialog){
		            	$('#btnSubmit').prop("disabled",false);
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
								    async: false,
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
											dialogItself.close();
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
						                    	window.location = 'index.php';
						               		}, 1700);											
										}
										else 
										{
											$('.response').html('<div class="alert alert-danger" id="danger-x">'+data['msg']+'</div>');
											$buttons.enable();
										}
										
										// if(res=='success'){				
										// 	var dialog = new BootstrapDialog({
								  //           message: function(dialogRef){
								  //           var $message = $('<div>GC Request Saved.</div>');			        
								  //               return $message;
								  //           },
								  //           closable: false
									 //        });
									 //        dialog.realize();
									 //        dialog.getModalHeader().hide();
									 //        dialog.getModalFooter().hide();
									 //        dialog.getModalBody().css('background-color', '#0088cc');
									 //        dialog.getModalBody().css('color', '#fff');
									 //        dialog.open();
									 //        setTimeout(function(){
						    //                 	dialog.close();
						    //            		}, 1500);
						    //            		setTimeout(function(){
						    //                 	window.location = 'index.php';
						    //            		}, 1700);

										// } else {

										// 	setTimeout(function(){
										// 	    $('.response').html('<div class="alert alert-danger" id="danger-x">'+res+'</div>');
										// 	}, 400);
										// 	timeoutmsg();
											
										// }

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
				$('.response').html('<div class="alert alert-danger" id="danger-x">Please select date needed.</div>');
			}
		}
		else 
		{
			$('.response').html('<div class="alert alert-danger" id="danger-x">Please input at least one denomination qty.</div>');
		}
		return false;		
	});

	$('#addcus').click(function(){
        BootstrapDialog.show({
        	title: '<i class="fa fa-user"></i> Customer Form',
     	    cssClass: 'add-newuser',
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
                'pageToLoad': '../dialogs/addnewcustomer.php'
            },
            onshown: function(dialogRef){            	
            	setTimeout(function(){
            	},1010);
            },
	        buttons: [{
	            icon: 'glyphicon glyphicon-ok-sign',
	            label: 'Submit',
	            cssClass: 'btn-primary',
	            hotkey: 13,
	            action:function(dialogItself){
	            	$buttons = this;
	            	$buttons.disable();
	            	//addnewcustomer
                	var formUrl = $('.form-container form#customer-info').attr('action');
                	var formData = $('.form-container form#customer-info').serialize();
	            	var noError = true;
                    var errormsg = [];
                    $('.reqfield').each(function(){
                        if($(this).val().trim()=='')                         
                        {
                            noError = false;
                            errormsg.push('Please fill form.');
                            return false;
                        }
                    });

                    if($('#dob').val().trim()!='')
                    {

		            	if(!validateDOB($('#dob').val()))
						{
							noError = false;
							errormsg.push('Date of Birth is invalid.');

						}					
					}

					if($('input[name=exist]').val()==1)
					{
						noError = false;
						errormsg.push($('#cusfname').val()+' '+$('#mname').val()+' '+$('#lname').val()+' already exist.');	
			
					} 

                    if(noError)
                    {
				        BootstrapDialog.show({
				        	title: 'Confirmation',
				            message: 'Are you sure you want add this customer?',
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
				                	var $buttons1 = this;
				                	$buttons1.disable();               	
			                    	$('.response').html('');
									$.ajax({
										url:formUrl,
										type:'POST',
										data:formData,
										beforeSend:function(){

										},
										success:function(response){
											console.log(response);
							    			var res = response.trim();
							    			if(res=='success'){
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
							    			} else {
							    				$('#cusfname').focus();
							    				timeoutmsg();
							    			}
										}
									});
				                }
				            }, {
				            	icon: 'glyphicon glyphicon-remove-sign',
				                label: 'No',
				                action: function(dialogItself){
				                    dialogItself.close();
				                    $buttons.enable();
				                }
				            }]
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
                        $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot"><ul class="ulleftpad14">'+erromsg+'</ul></div>');													               
                        $('#cusfname').focus();
                        $buttons.enable();
                    }
				  //   $('form#customer-info reqfield').each(function() {
				  //       if(!$(this).val()){
				  //       	emptyfield=true;
				  //       }
				  //   });
			   //      if(!emptyfield){

	     //            	var formUrl = $('.form-container form#customer-info').attr('action');
	     //            	var formData = $('.form-container form#customer-info').serialize();
						// $.ajax({
						// 	url:formUrl,
						// 	type:'POST',
						// 	data:formData,
						// 	beforeSend:function(){

						// 	},
						// 	success:function(response){
				  //   			var res = response.trim();
				  //   			if(res=='success'){
						// 			$('table tbody.cus-tbody').load('../ajax.php?action=reloadcustomertable');				    			
				  //   				BootstrapDialog.closeAll();
				  //   			} else {
						// 			$('.response').html('<div class="alert alert-danger">Some fields are empty.</div>');
				  //   				$('#cusfname').focus();
				  //   				timeoutmsg();
				  //   			}
						// 	}
						// });
		            	
			   //      } else {
						// $('.response').html('<div class="alert alert-danger #danger-x"> Some fields are empty.</div>');
						// $('#cusfname').focus();
						// timeoutmsg();
			   //      }
	            }
	        }, {
	        	icon: 'glyphicon glyphicon-remove-sign',
	            label: 'Close',
	            action: function(dialogItself){
	                dialogItself.close();
	            }
	        }]

        });

	});
	
	$('table#customer tbody.cus-tbody').on('click','tr td a.cus-update',function(){
		var id = $(this).attr('href');
		BootstrapDialog.show({
			title: '<i class="fa fa-user"></i> Update Customer',
		    message: $('<div></div>').load('../dialogs/updatecustomer.php?id='+id),
			    cssClass: 'add-supplier',
		    closable: true,
		    closeByBackdrop: false,
		    closeByKeyboard: true,
		    onshow: function(dialog) {
		        // dialog.getButton('button-c').disable();
		    },
		    onshown: function(dialogRef){
		    	$('#cusfname').focus();
		    },
		    buttons: [{
		        icon: 'glyphicon glyphicon-ok-sign',
		        label: 'Update',
		        cssClass: 'btn-primary',
		        hotkey: 13,
		        action:function(dialogItself){
		        	
		    //     	var emptyfield = false;
				  //   $('form#customer-info input').each(function() {
				  //       if(!$(this).val()){
				  //       	emptyfield=true;
				  //       }
				  //   });
			   //      if(!emptyfield){
	     //            	var formUrl = $('.form-container form#customer-info').attr('action');
	     //            	var formData = $('.form-container form#customer-info').serialize();				                	
	     //            	$.ajax({
	     //            		url:formUrl,
	     //            		type:'POST',
	     //            		data:formData,
	     //            		beforeSend:function(){

	     //            		},
	     //            		success:function(response){
	     //            			var res = response.trim();				                			
	                			
	     //            			if(res=='success'){
	     //            				$('table.customer tbody.cus-tbody').load('../ajax.php?action=reloadcustomertable');
	     //            				BootstrapDialog.closeAll();
	     //            			} else {
	     //            				$('.response').html('<div class="alert alert-danger #danger-x">'+res+'</div>');
	     //            				dialogItself.close();
	     //            			}				                	
	     //            		}
	     //            	});
		            	
			   //      } else {
						// $('.response').html('<div class="alert alert-danger #danger-x"> Some fields are empty.</div>');
						// //timeoutmsg();
			   //      }
		        }
		    }, {
		    	icon: 'glyphicon glyphicon-remove-sign',
		        label: 'Cancel',
		        action: function(dialogItself){
		            dialogItself.close();
		        }
		    }]

		});
		return false;
	});

	$('table#customer tbody.cus-tbody').on('click','tr td a.cus-delete',function(){
		var id = $(this).attr('href');
	        BootstrapDialog.show({
	        	title: 'Confirmation',
	            message: 'Delete Customer?',
	            closable: true,
	            closeByBackdrop: false,
	            closeByKeyboard: true,
	            onshow: function(dialog) {
	                
	            },
	            buttons: [{
	                icon: 'glyphicon glyphicon-ok-sign',
	                label: 'Yes',
	                cssClass: 'btn-primary',
	                hotkey: 13,
	                action:function(dialogItself){
	                	$.ajax({
	                		url:'../ajax.php?action=deletecustomer',
	                		type:'POST',
	                		data:{id:id},
	                		beforeSend:function(){

	                		},
	                		success:function(response){
	                			var res = response.trim();
	                			if(res=='success'){
	                				$('table tbody.cus-tbody').load('../ajax.php?action=reloadcustomertable');				    			
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

	$('.form-container').on('click','button#look',function(){
       BootstrapDialog.show({
        	title: '<i class="fa fa-user"></i> Customer Info',
            message: $('<div></div>').load('../dialogs/customerdetails.php'),
     	    cssClass: 'customer-details',
	        closable: true,
	        closeByBackdrop: false,
	        closeByKeyboard: true,
	        onshow: function(dialog) {
	            // dialog.getButton('button-c').disable();
	        },
            onshown: function(dialogRef){
            	$('._cusdetails').focus();
				$('._cusdetails').keyup(function(){
					var search = $(this).val();
					if(search.length > 3){
						$.ajax({
							url:'../ajax.php?action=searchcustomer',
							type:'POST',
							data:{search:search},
							beforeSend:function(){

							},
							success:function(response){
								$('#cusdetails').html(response);								
							}
						});

					} else {
						$('#cusdetails').html('');	
					}
				});

				$('#cusdetails').on('click','table.table tbody tr',function(){
					var cusid = $(this).attr('cusid');
					var cusfname = $(this).attr('cusfname');
					var cuslname = $(this).attr('cuslname');
					var cusidv = $(this).attr('cusidv');
					var cusmnumber = $(this).attr('cusmnumber');
					var cusaddress = $(this).attr('cusaddress');
					$('#cid').val(cusid);
					$('#fname').val(cusfname);
					$('#lname').val(cuslname);
					$('#cusid').val(cusidv);
					$('#mnum').val(cusmnumber);
					$('#address').val(cusaddress);

					BootstrapDialog.closeAll();
					$('#gcbarcodever').focus();
				});
            },
	        buttons: [{
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

		location.href='storereportpdf.php?gcsales='+gcsales+'&reval='+reval+'&refund='+refund+'&trans='+trans+'&dstart='+dstart+'&dend='+dend;

		return false;
	});
	$('.form-container').on('submit','form#verifygc',function(){
		$('.response').html('');
		var formUrl = $(this).attr('action'), formData = $(this).serialize();
		$('button.verifybtn').attr('disabled','disabled');		
		$.ajax({
			url:formUrl,
			type:'POST',
			data:formData,
			beforeSend:function(){

			},
			success:function(data){
				console.log(data);
				var data = JSON.parse(data);

				if(data['st'])
				{
					$('.response').html('<div class="alert alert-success">'+data['msg']+'</div>');
					if(data['reval'])
					{
						$('#print-receipt-verify').html(data['barcode']+' '+data['customer']+' '+data['date']+' '+data['time']+' '+data['storename']).css('left','230px');
					}
					else 
					{
						$('#print-receipt-verify').html(data['barcode']+' '+data['customer']+' '+data['date']+' '+data['time']+' '+data['storename']);
					}
					jQuery('#print-receipt-verify').print();
					var dialog = new BootstrapDialog({
		            message: function(dialogRef){
		            var $message = $('<div>'+data['flashmsg']+'</div>');			        
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
						$('#gcbarcodever').select();
						$('#print-receipt').html('');
               		}, 1700);
				}
				else 
				{
					$('.response').html('<div class="alert alert-danger">'+data['msg']+'</div>');
					$('#gcbarcodever').select();					
				}
				$('span.verifyreprint').html('');
				$('#isreprint').val(0);
			}
		});
		$('button.verifybtn').removeAttr('disabled');
		$('#gcbarcodever').select();	
		return false;
	});

	$('table#storePendingRequest tbody.store-request-list').on('click','tr td button.btn-cancel',function(){
		var id = $(this).closest('tr').attr('requestid');
        BootstrapDialog.show({
        	title: 'Confirmation',
            message: 'Are you sure you want to cancel GC Request.',
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
                		url:'../ajax.php?action=cancelgcrequestbystore',
                		data:{id:id},
                		type:'POST',
                		success:function(response){
                			var data = JSON.parse(response);
                			if(data['stat'])
                			{
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
			                    	window.location = 'index.php';
			               		}, 1700);              				
                			}
                			else 
                			{
                				alert(data['msg']);
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

	function timeoutmsg(){
	    setTimeout(function(){
	    	$('.response').html('');
	    }, 5000);
	}

	$("input[id^=num]").keyup(function(){
		var sum = 0;
		$('input[id^=num]').each(function(){
			// var a = $(this).parent('.col-sm-3').find('input.denval').val();
			var dnid = $(this).attr('id').slice(3);
			var a = $("#num"+dnid).val() * $("#m"+dnid).val();
			sum +=a;
		});
		// for(var $x=1;$x<=5;$x++) {
		// 	var inputs = $("#num"+$x).val();
		// 	inputs = inputs.replace(/,/g , "");
		// 	mul = inputs * $("#m"+$x).val();
		// 	sum = sum + mul;
		// }		
		$('#totalReq').val(addCommas(sum)+".00");
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

    $(document).on('change','input#ninternalcusd, input#ninternalcusq',function() {
    	scanInternalInput();
    });

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

	$('.form-container').on('submit','#storeRequestInternal',function(){
		$('.response').html('');
		var formURL = $(this).attr('action'), formData = new FormData($(this)[0]);
		if($('#dp1').val().trim()!='')
		{
			//if denomination input is empty/ zero
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
				$('.response').html('<div class="alert alert-danger" id="danger-x">Please input valid qty value.</div>');
				return false;
			}

	        BootstrapDialog.show({
	        	title: 'Confirmation',
	            message: 'Are you sure you want to submit GC Request?',
	            closable: true,
	            closeByBackdrop: false,
	            closeByKeyboard: true,
	            onshow: function(dialog) {
	               	$("button#internalbtn").prop("disabled",true);
	            },
	            onhidden: function(dialog){
	            	$("button#internalbtn").prop("disabled",false);
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
						    async: false,
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
			$('.response').html('<div class="alert alert-danger" id="danger-x">Please select date needed.</div>');
		}

		return false;
	});

	$('.amount-external').keyup(function(){
		var inputs = $(this).val();
		inputs = inputs.replace(/,/g , "");
		$('.amtinwords').val(toWords(inputs));
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
});


// American Numbering System
var th = ['', 'thousand', 'million', 'billion', 'trillion'];
// uncomment this line for English Number System
// var th = ['','thousand','million', 'milliard','billion'];

var dg = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
var tn = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];
var tw = ['twenty-', 'thirty-', 'forty-', 'fifty-', 'sixty-', 'seventy-', 'eighty-', 'ninety-'];

function toWords(s) {
    if(s=='0' || s=='0.00')
    {
      return "";
    }
    s = s.toString();
    s = s.replace(/[\, ]/g, '');
    if (s != parseFloat(s)) return 'not a number';
    var x = s.indexOf('.');
    if (x == -1) x = s.length;
    if (x > 15) return 'too big';
    var n = s.split('');
    var str = '';
    var sk = 0;
    for (var i = 0; i < x; i++) {
        if ((x - i) % 3 == 2) {
            if (n[i] == '1') {
                str += tn[Number(n[i + 1])] + ' ';
                i++;
                sk = 1;
            } else if (n[i] != 0) {
                str += tw[n[i] - 2] + '';
                sk = 1;
            }
        } else if (n[i] != 0) {
            str += dg[n[i]] + ' ';
            if ((x - i) % 3 == 0) str += 'hundred ';
            sk = 1;
        }
        if ((x - i) % 3 == 1) {
            if (sk) str += th[(x - i - 1) / 3] + ' ';
            sk = 0;
        }
    }	
    // if (x != s.length) {
    //     var y = s.length;
    //     str += 'and ';
    //     str +='11'+'/100';
    //     alert(s);
    // }
 	var last2 = s.slice(-2);
 	if(last2 != '00')
 	{
 		str += 'and ';
 		str += last2+'/100';
 	}

    return str.replace(/\s+/g, ' ');
}

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

function viewStoreRequest(reqid)
{
	BootstrapDialog.show({
		title: 'Store GC Request',
		closable: true,
        closeByBackdrop: false,
        closeByKeyboard: false,
		cssClass: 'modal-remaingc',
        message: function(dialog) {
            var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
            var pageToLoad = dialog.getData('pageToLoad');
			setTimeout(function(){
            $message.load(pageToLoad);
			},1000);
            return $message;
        },
        data: {
            'pageToLoad': '../dialogs/viewgcrequeststore.php?reqid='+reqid
        },
        onshow: function(dialog) {
            // dialog.getButton('button-c').disable();
        },
        onshown: function(dialog){
        	//pendinggc

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

function viewRemainingGC(reqid)
{	
	BootstrapDialog.show({
		title: 'Remaining Request',
		closable: true,
        closeByBackdrop: false,
        closeByKeyboard: false,
		cssClass: 'modal-remaingc',
        message: function(dialog) {
            var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
            var pageToLoad = dialog.getData('pageToLoad');
			setTimeout(function(){
            $message.load(pageToLoad);
			},1000);
            return $message;
        },
        data: {
            'pageToLoad': '../dialogs/viewremaninggcrequest.php?reqid='+reqid
        },
        onshow: function(dialog) {
            // dialog.getButton('button-c').disable();
        },
        onshown: function(dialog){
        	//pendinggc

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

function updateCustomerDetails(id)
{
    BootstrapDialog.show({
    	title: '<i class="fa fa-user"></i> Update Customer Information',
 	    cssClass: 'add-newuser',
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
            'pageToLoad': '../dialogs/updatecustomer.php?id='+id
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
            	$('.response').html('');
            	var formUrl = $('.form-container form#customer-info').attr('action');
            	var formData = $('.form-container form#customer-info').serialize();
            	var noError = true;
                var errormsg = [];

                if($('#dob').val()===undefined)
                {
                	$buttons.enable();
                	return false;
                }

                $('.reqfield').each(function(){
                    if($(this).val().trim()=='')                         
                    {
                        noError = false;
                        errormsg.push('Please fill form.');
                        return false;
                    }
                });

                if($('#dob').val().trim()!='')
                {

	            	if(!validateDOB($('#dob').val()))
					{
						noError = false;
						errormsg.push('Date of Birth is invalid.');

					}					
				}

				if($('input[name=exist]').val()==1)
				{
					noError = false;
					errormsg.push($('#cusfname').val()+' '+$('#mname').val()+' '+$('#lname').val()+' already exist.');						
				} 
                    if(noError)
                    {
				        BootstrapDialog.show({
				        	title: 'Confirmation',
				            message: 'Are you sure you want update customer?',
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
				                	$buttonconfirm = this;
				                	$buttonconfirm.disable();         	
			                    	$('.response').html('');
									$.ajax({
										url:formUrl,
										type:'POST',
										data:formData,
										beforeSend:function(){

										},
										success:function(data){											
											console.log(data);
							    			var data = JSON.parse(data);
							    			if(data['st']){
							    				BootstrapDialog.closeAll();
												var dialog = new BootstrapDialog({
									            message: function(dialogRef){
									            var $message = $('<div>Customer successfully updated.</div>');			        
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
							    				dialogItself.close();
							    				$buttons.enable();
							    			}
										}
									});
				                }
				            }, {
				            	icon: 'glyphicon glyphicon-remove-sign',
				                label: 'No',
				                action: function(dialogItself){
				                	$buttons.enable();
				                    dialogItself.close();				                   
				                }
				            }]
				        });
                    }
                    else 
                    {
                    	$buttons.enable();
                        var erromsg = '';
                        for(i=0; i<(errormsg.length); i++)
                        {
                            // erromsg+'<li>'+errormsg[i]+'</li>';
                           erromsg += '<li class="leftpad0">'+errormsg[i]+'</li>';
                        }
                        $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot"><ul class="ulleftpad14">'+erromsg+'</ul></div>');													               
                        $('#cusfname').focus();
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

	// BootstrapDialog.show({
	// 	title: '<i class="fa fa-user"></i> Update Customer',
	//     message: $('<div></div>').load('../dialogs/updatecustomer.php?id='+id),
	// 	cssClass: 'add-supplier',
	//     closable: true,
	//     closeByBackdrop: false,
	//     closeByKeyboard: true,
	//     onshow: function(dialog) {
	//         // dialog.getButton('button-c').disable();
	//     },
	//     onshown: function(dialogRef){
	//     	$('#cusfname').focus();
	//     },
	//     buttons: [{
	//         icon: 'glyphicon glyphicon-ok-sign',
	//         label: 'Update',
	//         cssClass: 'btn-primary',
	//         hotkey: 13,
	//         action:function(dialogItself){
	//         	var emptyfield = false;
	// 		    $('form#customer-info input').each(function() {
	// 		        if(!$(this).val()){
	// 		        	emptyfield=true;
	// 		        }
	// 		    });
	// 	        if(!emptyfield){
 //                	var formUrl = $('.form-container form#customer-info').attr('action');
 //                	var formData = $('.form-container form#customer-info').serialize();				                	
 //                	$.ajax({
 //                		url:formUrl,
 //                		type:'POST',
 //                		data:formData,
 //                		beforeSend:function(){

 //                		},
 //                		success:function(response){
 //                			var res = response.trim();				                			
                			
 //                			if(res=='success'){
 //                				$('table.customer tbody.cus-tbody').load('../ajax.php?action=reloadcustomertable');
 //                				BootstrapDialog.closeAll();
 //                				window.location.dialog();
 //                			} else {
 //                				$('.response').html('<div class="alert alert-danger #danger-x">'+res+'</div>');
 //                				dialogItself.close();
 //                			}				                	
 //                		}
 //                	});
	            	
	// 	        } else {
	// 				$('.response').html('<div class="alert alert-danger #danger-x"> Some fields are empty.</div>');
	// 				//timeoutmsg();
	// 	        }
	//         }
	//     }, {
	//     	icon: 'glyphicon glyphicon-remove-sign',
	//         label: 'Cancel',
	//         action: function(dialogItself){
	//             dialogItself.close();
	//         }
	//     }]

	// });
}

function checkCustomerDetails()
{
	var lname = $('input#lname').val();
	var fname = $('input#cusfname').val();
	var mname = $('input#mname').val();
	var extname = $('input#extname').val();
	var llen = lname.length;
	var flen = fname.length;
	var mlen = mname.length;
	if((llen > 0) && (flen > 0) && (mlen>0))
	{
		$.ajax({
			url:'../ajax.php?action=checkCustomerNames',
			data:{lname:lname,fname:fname,mname:mname,extname:extname},
			type:'POST',
			success:function(data){
				var data = JSON.parse(data);
				if(data['stat'])
				{
					$('.response').html('<div class="alert alert-danger alert-no-bot">'+data['msg']+'</div>');
					$('input[name=exist]').val(1);
				}
				else
				{
					$('input[name=exist]').val(0);
					$('.response').html('');
				}
			}
		});
	}
}

function checkCustomerDetailsUpdate(cusid)
{
	var lname = $('input#lname').val();
	var fname = $('input#cusfname').val();
	var mname = $('input#mname').val();
	var extname = $('input#extname').val();
	var llen = lname.length;
	var flen = fname.length;
	var mlen = mname.length;
	if((llen > 0) && (flen > 0) && (mlen>0))
	{
		$.ajax({
			url:'../ajax.php?action=checkCustomerNamesUpdate',
			data:{lname:lname,fname:fname,mname:mname,cusid:cusid,extname:extname},
			type:'POST',
			success:function(data){
				var data = JSON.parse(data);
				if(data['stat'])
				{
					$('.response').html('<div class="alert alert-danger alert-no-bot">'+data['msg']+'</div>');
					$('input[name=exist]').val(1);
				}
				else
				{
					$('input[name=exist]').val(0);
					$('.response').html('');
				}
			}
		});
	}
}

function validateDOB(dob)
{   
	var flag = true;
	var n = 0;
    var data = dob.split("/");
    // using ISO 8601 Date String
    function parseData()
    {
    	if(n<3)
    	{
		   	if(isNaN(data[n]))
		   	{
		   		flag = false;
			}
		   	n++;
		   	parseData(); 
    	} 	
    }
    parseData();
	return flag;
}

function eodstore(storeid,userid)
{
	BootstrapDialog.show({
	  title: 'Confirmation',
	  message:  '<div class="row">'+
	            '<div class="col-md-12">'+
	            'Are you sure you want to process EOD?'+
	            '</div>'+                                                                       
	            '</div>',
	  cssClass: 'confirmation',    
	  closable: true,
	  closeByBackdrop: false,
	  closeByKeyboard: true,
	  buttons: [{
	      icon: 'glyphicon glyphicon-ok-sign',
	      label: 'Yes',
	      cssClass: 'btn-success',
	      hotkey: 13,
	      action:function(dialogItself){
	      	$buttons = this;
	      	$buttons.disable();
	      	dialogItself.close();
	      	$('div.response').html('');
	      	$.ajax({
	      		url:'../ajax.php?action=eodstore',
	      		data:{storeid:storeid,userid:userid},
	      		type:'POST',
	      		beforeSend:function(data)
	      		{
	      			$('#processing-modal').modal('show');
	      			$('div.box-content').html('<i class="fa fa-cog fa-spin"></i>');
	      		},
	      		success:function(data)
	      		{	      					
	      			setTimeout(function(){
	      				$('#processing-modal').modal('hide'); 	      				
	      			},1000);

	      			
	      			console.log(data);
	      			var data = JSON.parse(data);
	      			if(data['st'])
	      			{
	      				window.location = 'store-eod.php?eod='+data['id'];
	      			}
	      			else 
	      			{
	      				$('div.response').html('<div class="alert alert-danger">'+data['msg']+'</div>');
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

function availableGC(stid)
{
    BootstrapDialog.show({
        title: '<i class="fa fa-user"></i> Available GC',
        cssClass: 'modal-allocated-gc',
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
            'pageToLoad': '../dialogs/view-avail-gc.php?stid='+stid
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

function soldGC(stid)
{
    BootstrapDialog.show({
        title: '<i class="fa fa-user"></i> Sold GC',
        cssClass: 'sold-gc',
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
            'pageToLoad': '../dialogs/view-sold-gc.php?stid='+stid
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

function lookupcustomer()
{
    BootstrapDialog.show({
        title: '<i class="fa fa-user"></i> Customer List',
        cssClass: 'customer-details',
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
            'pageToLoad': '../dialogs/customerdetails.php'
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

function updateStoreRequest(reqid,storeid)
{
	BootstrapDialog.show({
		title: 'Update GC Request',
		closable: true,
        closeByBackdrop: false,
        closeByKeyboard: false,
		cssClass: 'modal-pending-gc',
        message: function(dialog) {
            var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
            var pageToLoad = dialog.getData('pageToLoad');
			setTimeout(function(){
            $message.load(pageToLoad);
			},1000);
            return $message;
        },
        data: {
            'pageToLoad': '../dialogs/update-store-request.php?id='+reqid+'&storeid='+storeid
        },
        onshow: function(dialog) {
            // dialog.getButton('button-c').disable();
        },
        onshown: function(dialog){
        	//pendinggc

        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Update',
            cssClass: 'btn btn-primary',
            hotkey: 13,
            action:function(dialogItself){
            	$('.response').html('');
            	var notEmpty = false;
            	var formURL = $('form#storeRequestUpdate').attr('action'), formData = new FormData($('form#storeRequestUpdate')[0]);
            	var rtype = $('#requesttype').val();
            	if(rtype!=undefined)
            	{
            		if(rtype=='specialinternal')
            		{
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
							$('.response').html('<div class="alert alert-danger" id="danger-x">Please input valid qty value.</div>');
								return false;
						}
						notEmpty = true;
            		}
            		else 
            		{
		     	        $(".reqden").each(function(){
		                	if($(this).val()!=0)
		        			{
		        				notEmpty = true;
		        			}	                                			
		        		});
		        	}

	        		if(notEmpty)
	        		{
	
	        			if($('#dp1').val().trim()=='')
	        			{
	        				$('.response').html('<div class="alert alert-danger">Please select date needed.</div>');  
	        				return false;

					    }

					    if($('#remarks').val().trim()=='')
					    {
	        				$('.response').html('<div class="alert alert-danger">Please input remarks.</div>');  
	        				return false;
					    }

					    if(rtype=='specialinternal')
					    {
					    	if($('#reqby').val().trim()=='')
					    	{
		        				$('.response').html('<div class="alert alert-danger">Please input Company / Person.</div>');  
		        				return false;
					    	}
					    }

				        BootstrapDialog.show({
				        	title: 'Confirmation',
				            message: 'Are you sure you want to update GC Request.',
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
				                	//updatestorerequest
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
										success:function(data)
										{											
											console.log(data);
											var data = JSON.parse(data);
											if(data['st'])
											{
												var dialog = new BootstrapDialog({
									            message: function(dialogRef){
									            var $message = $('<div>GC Request Successfully Updated.</div>');			        
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
				                    $('#num1').focus();
				                }
				            }]
				        }); 
	        		}
	        		else 
	        		{
	        			$('.response').html('<div class="alert alert-danger">Please input at least one quantity field.</div>');                			
	        		}
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

function textfiletranx(barcode)
{
    BootstrapDialog.show({
    	title: '<i class="fa fa-bars"></i>GC Navision POS Transactions',
 	    cssClass: 'nav-trax',
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
            'pageToLoad': '../dialogs/postransactions.php?barcode='+barcode
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

function getTotalRows(row)
{
	// var row = 5;//
	// var totalSUM = 0;
	// $("table#ledgertable tbody tr").each(function () 
	// 	{
	// 	    var getValue = $(this).find("td:eq("+row+")").html().replace("$", "");
	// 	    var filteresValue = getValue.replace(/\,/g, '');
	// 	    totalSUM += Number(filteresValue);
	// 	    console.log(filteresValue);
	// 	}
	// );
	// return totalSUM.toFixed(2);
}

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

function reprintVerification()
{
	BootstrapDialog.show({
		title: '<i class="fa fa-user"></i></i> Manager Login',
		closable: true,
        closeByBackdrop: false,
        closeByKeyboard: false,
		cssClass: 'info-verification',
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
                					dialogItself.close();
                					alert(data['msg']);
                					$('span.verifyreprint').html('[Reprint]');
                					$('#isreprint').val(1);
                					$('#gcbarcodever').select();
                					$('.response').html('');
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


function verifiedGCInfo(barcode)
{
	BootstrapDialog.show({
		title: '<i class="fa fa-fa fa-info"></i> GC Barcode #'+barcode,
		closable: true,
        closeByBackdrop: false,
        closeByKeyboard: false,
		cssClass: 'verifiedmodal',
        message: function(dialog) {
            var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
            var pageToLoad = dialog.getData('pageToLoad');
			setTimeout(function(){
            $message.load(pageToLoad);
			},1000);
            return $message;
        },
        data: {
            'pageToLoad': '../dialogs/storeverificationinfo.php?action=dateverified&barcode='+barcode
        },
        onshown: function(dialog) {
        },
        buttons: [{
            icon: 'glyphicon glyphicon-remove-sign',
            label: 'Close',
            cssClass: 'btn-default',
            action:function(dialogItself){
            	dialogItself.close();
            }
        }]
    });
}

function reverificationInfo(barcode)
{
	BootstrapDialog.show({
		title: '<i class="fa fa-th-large"></i> GC Barcode #'+barcode,
		closable: true,
        closeByBackdrop: false,
        closeByKeyboard: false,
		cssClass: 'verifiedmodal',
        message: function(dialog) {
            var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
            var pageToLoad = dialog.getData('pageToLoad');
			setTimeout(function(){
            $message.load(pageToLoad);
			},1000);
            return $message;
        },
        data: {
            'pageToLoad': '../dialogs/storeverificationinfo.php?action=reverification&barcode='+barcode
        },
        onshown: function(dialog) {
        },
        buttons: [{
            icon: 'glyphicon glyphicon-remove-sign',
            label: 'Close',
            cssClass: 'btn-default',
            action:function(dialogItself){
            	dialogItself.close();
            }
        }]
    });
}


function revalidationGCInfo(barcode)
{
	BootstrapDialog.show({
		title: '<i class="fa fa-th-large"></i> GC Barcode #'+barcode,
		closable: true,
        closeByBackdrop: false,
        closeByKeyboard: false,
		cssClass: 'verifiedmodal',
        message: function(dialog) {
            var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
            var pageToLoad = dialog.getData('pageToLoad');
			setTimeout(function(){
            $message.load(pageToLoad);
			},1000);
            return $message;
        },
        data: {
            'pageToLoad': '../dialogs/storeverificationinfo.php?action=daterevalidated&barcode='+barcode
        },
        onshown: function(dialog) {
        },
        buttons: [{
            icon: 'glyphicon glyphicon-remove-sign',
            label: 'Close',
            cssClass: 'btn-default',
            action:function(dialogItself){
            	dialogItself.close();
            }
        }]
    });
}

function storeLedgerDialog(trid,trtype)
{
	var hTitle = "";
	if(trtype===1)
	{
		hTitle = "GC Entry Details";
		dClass = "gcStoreEntry";
	}
	else if(trtype===2)
	{
		hTitle = "GC Sales Details";
		dClass = "cardtransactiondetails";
	}
	else if(trtype===3)
	{
		hTitle = "GC Revalidation";
		dClass = "gcrevalidation";		
	}
	else if(trtype===4)
	{
		hTitle = "GC Refund";
		dClass = "cardtransactiondetails";	
	}

	BootstrapDialog.show({
		title: hTitle,
		closable: true,
        closeByBackdrop: false,
        closeByKeyboard: false,
		cssClass: dClass,
        message: function(dialog) {
            var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
            var pageToLoad = dialog.getData('pageToLoad');
			setTimeout(function(){
            $message.load(pageToLoad);
			},1000);
            return $message;
        },
        data: {
            'pageToLoad': '../dialogs/storeLedgerDialog.php?trid='+trid+'&trtype='+trtype
        },
        onshown: function(dialog) {
        },
        buttons: [{
            icon: 'glyphicon glyphicon-remove-sign',
            label: 'Close',
            cssClass: 'btn-default',
            action:function(dialogItself){
            	dialogItself.close();
            }
        }]
    });
}

function addNewCustomerVerify()
{
    BootstrapDialog.show({
        title: 'Add New Customer',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: true,
        message: function(dialog) {
            var $message = $("<div><img src='../assets/images/ajax.gif'><small class='text-danger'>please wait...</small></div>");
            var pageToLoad = dialog.getData('pageToLoad');
            setTimeout(function(){
                $message.load(pageToLoad); 
            },1000);
          return $message;
        },
        data: {
            'pageToLoad': '../templates/setup.php?page=addNewCustomerDialog',
        },
        cssClass: 'changeaccountpass',
        onshow: function(dialog) {
            // dialog.getButton('button-c').disable();
        },
        onhidden: function(dialogRef){
            $('#numOnly').focus();
            flag=0;
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Add',
            cssClass: 'btn-success',
            hotkey: 13,
            action:function(dialogItself){   
                $('.response-dialog').html('');
                var $button = this;
                $button.disable();
                var postData = $('#_addcustomer').serialize(), formURL = $('#_addcustomer').attr("action");
                var fname = $('form#_addcustomer input#fnamedialog').val(), lname = $('form#_addcustomer input#lnamedialog').val();

                if(fname.trim()=='' || lname.trim()=='')
                {
                	$('.response-dialog').html('<div class="alert alert-danger alert-danger-dialog">Please input required fields.</div>');
                	$('#fnamedialog').select();
                	$button.enable();
                	return false;
                }

                BootstrapDialog.show({
                    title: 'Confirmation',
                    message: 'Add New Customer?',
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
                            var $button1 = this;
                            $button1.disable();
                            $.ajax({
                                url:formURL,
                                data:postData,
                                type:'POST',
                                success:function(data)
                                {
                                    console.log(data);
                                    var data = JSON.parse(data);
                                    if(data['st'])
                                    {
                                        BootstrapDialog.closeAll();
                                        var dialog = new BootstrapDialog({
                                        message: function(dialogRef){
                                        var $message = $('<div>Customer Successfully Added.</div>');                 
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

                                        $('#cid').val(data['cid']);

                                        $('#fname').val(data['fname']);
                                        $('#lname').val(data['lname']);
                                        $('#mname').val(data['mname']);
                                        $('#next').val(data['next']);

                                        $('#_vercussearch').val(data['fullname']);

                                        setTimeout(function(){
                                        	$('#gcbarcodever').focus();
                                            dialog.close(); 
                                        }, 1500);
                                    }
                                    else 
                                    {
                                        $('.response-dialog').html('<div class="alert alert-danger alert-danger-dialog">'+data['msg']+'</div>');
                                        $('form#_addcustomer input#fname').focus();
                                        dialogItself.close();
                                        $button.enable();   
                                        return false;
                                    }
                                }
                            });                                                    
                        }
                    },{
                        icon: 'glyphicon glyphicon-remove-sign',
                        label: 'Cancel',
                        action: function(dialogItself){
                            dialogItself.close();
                            $button.enable();  
                        }
                    }]
                }); 

                return false;

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

function pendingSpecialExternalGCforRelease(id)
{
	location.href = 'special-external-gc-releasing.php?id='+id;
}

function getUrlVars() {
	var vars = {};
	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
	vars[key] = value;
	});
	return vars;
}

if(getUrlVars()['gcreport']!=undefined)
{
	var id = getUrlVars()['gcreport'];
    BootstrapDialog.show({
        title: 'GC Report',
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
          'pageToLoad': '../dialogs/releasedgcreport.php?id='+id+'&type=gcreport'
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

if(getUrlVars()['transferReleasing']!=undefined)
{
	var id = getUrlVars()['transferReleasing'];
    BootstrapDialog.show({
        title: 'Transfer GC (Out)',
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
          'pageToLoad': '../dialogs/releasedgcreport.php?id='+id+'&type=transferReleasing'
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


