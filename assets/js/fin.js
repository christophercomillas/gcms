$(document).ready(function(){

	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

	var checkin = $('#dp1').datepicker({

	    beforeShowDay: function (date) {
	        return date.valueOf() >= now.valueOf();
	    },
	    autoclose: true

	});

	$.extend( $.fn.dataTableExt.oStdClasses, {	  
	    "sLengthSelect": "selectsup"
	});

    $('#storeRequestList').dataTable( {
        "pagingType": "full_numbers",
        "ordering": false,
        "processing": true
    });


	$('#num1,#num2,#num3,#num4,#num5,#num6').inputmask("integer", { allowMinus: false,autoGroup: true, groupSeparator: ",", groupSize: 3,placeholder:'0' });

	$('table.approveReq').on('click','.approveBut',function(){
        BootstrapDialog.show({
        	title:'Approve Budget Request',
            message:'<div class="addvariant">'+
            		'<form method="POST" action="../ajax.php?action=approveBudget" id="approveBudgetForm">'+
	            		'<table class="table table-variant">'+
	            			'<tr>'+
	            				'<td><label>Upload Scan Copy: </label></td>'+
	            				'<td><input type="file" class="form-control input-sm" name="pic[]" accept="image/*"  /></td>'+
	            			'</tr>'+
	            			'<tr>'+
	            				'<td><label>Remarks: </label></td>'+
	            				'<td><textarea class="form form-control" name="remark"></textarea></td>'+
	            			'</tr>'+
	            		'</table>'+
	            		'<div class="response">'+
	            		'</div>'+
            		'</form>'+
            		'</div>',
            onshow: function(dialogRef){                
            },
            onshown: function(dialogRef){
            },
            onhide: function(dialogRef){                
            },
            onhidden: function(dialogRef){                
            },
            cssClass: 'addvariant-dialog',
            buttons: [{
                label: 'Submit',
                cssClass: 'btn-primary',
                hotkey: 13,
                action: function(dialogRef){
					var formUrl = $('form#approveBudgetForm').attr('action'), formData = new FormData($('form#approveBudgetForm')[0]);
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

	                			if(res == 'success'){
	                				window.location = 'index.php';
	                			} else {

					    			$('.response').html('<div class="alert alert-danger dangerxx">'+res+'</div>');
					    			timeoutmsg();

					    		}
				    		}
				    	});				
                }
            }, {
                label: 'Cancel',
                action: function(dialogcolor){
                    dialogcolor.close();
                }
            }]
        });
	});

	$('.form-container').on('submit','form#gcreview',function(event){
		event.preventDefault();
		$('.response').html('');
		var formUrl = $(this).attr('action'), formData = new FormData($(this)[0]), remark = $('#remarks').val(), trid = $('#trid').val();
	

		//check session
		$.ajax({
			url:'../ajax.php?action=gcreviewCheckScanned',
			data:{remark:remark,trid:trid},
			type:'POST',
			success:function(data)
			{
				console.log(data);
				var data = JSON.parse(data);
				if(data['st'])
				{
			        BootstrapDialog.show({
			        	title: 'Confirmation',
			            message: 'Are you sure you want to submit?',
			            closable: true,
			            closeByBackdrop: false,
			            closeByKeyboard: true,
			            onshow: function(dialog) {
			                // dialog.getButton('button-c').disable();
			            },
			            onshown: function(dialog){
			            	$('#gcreviewbut').prop('disabled','disabled');
			            },
			            onhidden: function(dialog){
			            	$('#gcreviewbut').prop('disabled','');
			            },
			            buttons: [{
			                icon: 'glyphicon glyphicon-ok-sign',
			                label: 'Yes',
			                cssClass: 'btn-primary',
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
							    		success:function(data){
							    			console.log(data);
						        			var data = JSON.parse(data);
						        			if(data['st'])
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
								    			$('.response').html('<div class="alert alert-danger dangerxx">'+data['msg']+'</div>');					    			
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
			                }
			            }]
			        });
					

				}
				else
				{
					$('.response').html('<div class="alert alert-danger danger-x">'+data['msg']+'</div>');
				}
			}
		});

		$('#remarks').focus();		
	});

	$('.form-container').on('submit','form#budgetFinance',function(){
		var confirmmsg = '';
		$('.response').html('');
		var formUrl = $(this).attr('action'), formData = new FormData($(this)[0]);
		var budgetid = $('#budgetid').val();
		var emptyfield = false, x = $('#app-checkby').val(), y = $('#app-apprby').val();
		var status = $('#status').val();
		if(status=='1')
		{
			if(x==='' || y==='')
			{
				$('.response').html('<div class="alert alert-danger danger-x">Please fill out all <span class="requiredf">*</span>required fields.</div>');
				return false;		
			}
			else 
			{
				confirmmsg = 'approved budget request?';
			}
		}
		else if(status=='2') 
		{
			confirmmsg = 'cancel budget request?';
		}

        BootstrapDialog.show({
        	title: 'Confirmation',
            message: 'Are you sure you want to '+confirmmsg,
            closable: true,
            closeByBackdrop: false,
            closeByKeyboard: true,
            onshow: function(dialog) {
                // dialog.getButton('button-c').disable();
                $('.btn').prop("disabled",true);
            },
            onhidden: function(dialog){
            	$('.btn').prop("disabled",false);
            },
            buttons: [{
                icon: 'glyphicon glyphicon-ok-sign',
                label: 'Yes',
                cssClass: 'btn-primary',
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
				    		success:function(data){
				    			console.log(data);
			        			var data = JSON.parse(data);
			        			if(data['st'])
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
					    			$('.response').html('<div class="alert alert-danger dangerxx">'+data['msg']+'</div>');					    			
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
                }
            }]
        });
		return false;
	});

	$('.form-container').on('submit','form#promoreqgroup',function(){
		$('.response').html('');		
		var confirmmsg = '';
		var formUrl = $(this).attr('action'), formData = new FormData($(this)[0]);
		var promoreqid = $('#budgetid').val();		
		var status = $('#statusretail').val();
		if(status=='1')
		{
			confirmmsg = 'approved budget request?';
		}
		else if(status=='2') 
		{
			confirmmsg = 'cancel budget request?';
		}

        BootstrapDialog.show({
        	title: 'Confirmation',
            message: 'Are you sure you want to '+confirmmsg,
            closable: true,
            closeByBackdrop: false,
            closeByKeyboard: true,
            onshow: function(dialog) {
                // dialog.getButton('button-c').disable();
                $('#btn').prop("disabled",true);
            },
            onhidden: function(dialog)
            {
            	$('#btn').prop("disabled",false);
            },
            buttons: [{
                icon: 'glyphicon glyphicon-ok-sign',
                label: 'Yes',
                cssClass: 'btn-primary',
                action:function(dialogItself){ 
                	$button = this; 
                	$button.disable();
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
						            var $message = $('<div>'+data['msg']+'</div>');			        
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
		return false;
	});

	$('#checkbu').click(function()
	{
	    BootstrapDialog.show({
	    	title: 'Checked by Tag',
	        message: '<form method="POST" action="../ajax.php" id="finance-budget-approved">'+
	        		'<div class="row">'+
	        		'<div class="col-md-12">'+
						'<div class="form-group">'+
			  				'<label for="radios-0">'+
	      						'<input name="radios" id="radios-0" value="1" checked="checked" type="radio">'+
	      							' Approved'+
	    					'</label>'+
						'</div>'+
						'<div class="form-group">'+
			  				'<label for="radios-1">'+
	      						'<input name="radios" id="radios-1" value="2" type="radio">'+
	      							' Cancel'+
	    					'</label>'+
						'</div>'+
	        		'</div>'+	        		
	        		'</div>',
	        cssClass: 'add-customer',
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
	            label: 'Confirm',
	            cssClass: 'btn-primary',
	            hotkey: 13,
	            action:function(dialogItself){
	            	var fn = $('#cusfname').val();
	            	$('#app-checkby').val(fn);
	            	dialogItself.close();
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

	$('.form-container').on('submit','form#prodRequestFin',function(){	
		$('.response').html('');
		var formUrl = $(this).attr('action'), formData = new FormData($(this)[0]);
		var hasEmpty = false;
		var confirmmsg = '', prid = $('#prodId').val();
		var status = $('#status').val();
		if(status==1)
		{
			$('.reqfield').each(function(){
				var fld = $(this).val().trim();
				if(fld=='')
				{
					hasEmpty = true;
					return;
				}
			});

			if(!hasEmpty)
			{
				confirmmsg = 'approved production request?';
			}
			else
			{
				$('.response').html('<div class="alert alert-danger danger-x">Please fill out all <span class="requiredf">*</span>required fields.</div>');
				return false;
			}
		}
		else if(status==2)
		{
			confirmmsg = 'cancel production request?';
		}

        BootstrapDialog.show({
        	title: 'Confirmation',
            message: 'Are you sure you want to '+confirmmsg,
            closable: true,
            closeByBackdrop: false,
            closeByKeyboard: true,
            onshow: function(dialog) {
                // dialog.getButton('button-c').disable();
            },
            onshown: function(dialog) {
            	$('button#btn').prop('disabled',true);
            },
            onhidden: function(dialog) {
            	$('button#btn').prop('disabled',false);
            },
            buttons: [{
                icon: 'glyphicon glyphicon-ok-sign',
                label: 'Yes',
                cssClass: 'btn-primary',
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
				    		success:function(data){
				    			console.log(data);
			        			var data = JSON.parse(data);
			        			if(data['st'])
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
					    			$('.response').html('<div class="alert alert-danger dangerxx">'+data['msg']+'</div>');
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
                }
            }]
        });

		// var prodId = $('#prodId').val();
		// var emptyfield = false, x = $('#app-checkby').val(), y = $('#app-apprby').val();
		// var status = $('#status').val();
		// if(status=='1'){
		// 	if(x==='' || y===''){
		// 		$('.response').html('<div class="alert alert-danger danger-x">Please input all fields.</div>');
		// 		timeoutmsg();
		// 	} else { 

		//         BootstrapDialog.show({
		//         	title: 'Confirmation',
		//             message: 'Are you sure you want to approve production request?',
		//             closable: true,
		//             closeByBackdrop: false,
		//             closeByKeyboard: true,
		//             onshow: function(dialog) {
		//                 // dialog.getButton('button-c').disable();
		//             },
		//             buttons: [{
		//                 icon: 'glyphicon glyphicon-ok-sign',
		//                 label: 'Yes',
		//                 cssClass: 'btn-primary',
		//                 hotkey: 13,
		//                 action:function(dialogItself){                	
		//                 	dialogItself.close();
		// 				    	$.ajax({
		// 				    		url:formUrl,
		// 				    		type:'POST',
		// 							data: formData,
		// 							enctype: 'multipart/form-data',
		// 						    async: false,
		// 						    cache: false,
		// 						    contentType: false,
		// 						    processData: false,
		// 				    		beforeSend:function(){

		// 				    		},
		// 				    		success:function(response){
		// 			        			var res = response.trim();

		// 			        			if(res == 'success'){
		// 									var dialog = new BootstrapDialog({
		// 						            message: function(dialogRef){
		// 						            var $message = $('<div>Production Request Successfully Approved.</div>');			        
		// 						                return $message;
		// 						            },
		// 						            closable: false
		// 							        });
		// 							        dialog.realize();
		// 							        dialog.getModalHeader().hide();
		// 							        dialog.getModalFooter().hide();
		// 							        dialog.getModalBody().css('background-color', '#86E2D5');
		// 							        dialog.getModalBody().css('color', '#000');
		// 							        dialog.open();
		// 							        setTimeout(function(){
		// 				                    	dialog.close();
		// 				               		}, 1500);
		// 				               		setTimeout(function(){
		// 				                    	window.location.href = 'index.php';
		// 				               		}, 1700);	        				
		// 			        			} else{
		// 					    			$('.response').html('<div class="alert alert-danger dangerxx">'+res+'</div>');
		// 					    			timeoutmsg();
		// 					    		}
		// 				    		}
		// 				    	});	

		//                 }
		//             }, {
		//             	icon: 'glyphicon glyphicon-remove-sign',
		//                 label: 'No',
		//                 action: function(dialogItself){
		//                     dialogItself.close();
		//                 }
		//             }]
		//         });
		// 	}
		//  } else {
	 //        BootstrapDialog.show({
	 //        	title: 'Confirmation',
	 //            message: 'Are you sure you want to cancel production request?',
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
	 //                	$.ajax({
	 //                		url:'../ajax.php?action=cancelProductionReq',
	 //                		type:'POST',
	 //                		data:{prodId:prodId},
	 //                		beforeSend:function()
	 //                		{

	 //                		},
	 //                		complete:function()
	 //                		{

	 //                		},
	 //                		success:function(data)
	 //                		{
		// 				   		var res = data.trim();

		// 				   		if(res=='success')
		// 				   		{
		// 				   			dialogItself.close();
		// 							var dialog = new BootstrapDialog({
		// 				            message: function(dialogRef){
		// 				            var $message = $('<div>Production Request Cancelled.</div>');			        
		// 				                return $message;
		// 				            },
		// 				            closable: false
		// 					        });
		// 					        dialog.realize();
		// 					        dialog.getModalHeader().hide();
		// 					        dialog.getModalFooter().hide();
		// 					        dialog.getModalBody().css('background-color', '#86E2D5');
		// 					        dialog.getModalBody().css('color', '#000');
		// 					        dialog.open();
		// 					        setTimeout(function(){
		// 		                    	dialog.close();
		// 		               		}, 1500);
		// 		               		setTimeout(function(){
		// 		                    	window.location.href = 'index.php';
		// 		               		}, 1700);	        	
		// 				   		}
		// 				   		else 
		// 				   		{
		// 				   			alert(res);
		// 				   		}	                		}
	 //                	});
	 //                }
	 //            }, {
	 //            	icon: 'glyphicon glyphicon-remove-sign',
	 //                label: 'No',
	 //                action: function(dialogItself){
	 //                    dialogItself.close();
	 //                }
	 //            }]
	 //        });
		//  }	
		return false;
	});

	$('.form-container').on('change','select#status',function(){
		var status = $(this).val();		
		if(status==2)
		{
 			$('.hide-cancel').hide();
			// $('.hide-cancel input#upload').prop('required',false);
			$('.hide-cancel input#remark, textarea#remark').prop('required',false);
			$('.label-prepared').text('Cancelled By:');
		}
		else 
		{
			$('.hide-cancel').show();
			// $('.hide-cancel input#upload').prop('required',true);
			$('.hide-cancel input#remark').prop('required',true);
			$('.label-prepared').text('Prepared By:')
		}

		if(status==0)
		{
			$('.newProdStatus').text('Date Approved/Cancel:');
		}
		else if(status==1)
		{
			$('.newProdStatus').text('Date Approved:');
		}
		else if(status==2)
		{
			$('.newProdStatus').text('Date Cancelled:');
		}
	});	

	$('.form-container').on('change','select#statusretail',function(){
		var status = $(this).val();
		if(status==2)
		{
			// $('.hide-cancel input#upload').prop('required',false);
			$('.label-prepared').text('Cancelled By:');
		}
		else 
		{
			// $('.hide-cancel input#upload').prop('required',true);
			$('.label-prepared').text('Approved By:')
		}

		if(status==0)
		{
			$('.newProdStatus').text('Date Approved/Cancel:');
		}
		else if(status==1)
		{
			$('.newProdStatus').text('Date Approved:');
		}
		else if(status==2)
		{
			$('.newProdStatus').text('Date Cancelled:');
		}
	});	

	function timeoutmsg()
	{
	    setTimeout(function(){
	    	$('.response').html('');
	    }, 6000);
	}

	$('.form-container').on('submit','form#promogcfinanceapproval',function(event){
		event.preventDefault();
		var confirmmsg = '';
		var formUrl = $(this).attr('action'), formData = new FormData($(this)[0]);
		$('.response').html('');
		var budgetid = $('#budgetid').val();
		var emptyfield = false, x = $('#app-checkby').val(), y = $('#app-apprby').val();
		var status = $('#status').val();
		if(status=='1')
		{
			if(x==='' || y==='')
			{
				$('.response').html('<div class="alert alert-danger danger-x">Please fill-up all <span class="requiredf">*</span>required fields.</div>');
				return false;		
			}
			else 
			{
				var curbudget = $('span#curbudget').text();
				curbudget = parseFloat(curbudget.replace(/,/g , ""));
				var totalgc = $('#totalgc').val();
				totalgc = parseFloat(totalgc.replace(/,/g , ""));
				if(totalgc > curbudget)
				{
					$('.response').html('<div class="alert alert-danger danger-x">Total GC requested is bigger than current budget.</div>');
					return false;
				}
				confirmmsg = 'approved budget request?';
			}
		}
		else if(status=='2') 
		{
			confirmmsg = 'cancel budget request?';
		}

        BootstrapDialog.show({
        	title: 'Confirmation',
            message: 'Are you sure you want to '+confirmmsg,
            closable: true,
            closeByBackdrop: false,
            closeByKeyboard: true,
            onshow: function(dialog){
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
				    		success:function(data){
				    			console.log(data);
			        			var data = JSON.parse(data);
			        			if(data['st'])
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
					    			$('.response').html('<div class="alert alert-danger dangerxx">'+data['msg']+'</div>');					    			
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
                }
            }]
        });

	});

	$('.form-container').on('submit','form#specialgcfinanceapproval',function(event){
		event.preventDefault();
		var confirmmsg = '';
		var formUrl = $(this).attr('action'), formData = new FormData($(this)[0]);
		$('.response').html('');
		var curbudget = $('#curbudget').val();
		var emptyfield = false, x = $('#app-checkby').val(), y = $('#app-apprby').val();
		var status = $('#status').val();
		if(status=='1')
		{
			if(x==='' || y==='')
			{
				$('.response').html('<div class="alert alert-danger danger-x">Please fill-up all <span class="requiredf">*</span>required fields.</div>');
				return false;		
			}
			else 
			{
				var totalgc = $('#totdenom').val();
				totalgc = parseFloat(totalgc.replace(/,/g , ""));
				if(totalgc > curbudget)
				{
					$('.response').html('<div class="alert alert-danger danger-x">Total Denomination requested is bigger than current budget.</div>');
					return false;
				}
				confirmmsg = 'approved budget request?';
			}
		}
		else if(status=='2') 
		{
			confirmmsg = 'cancel budget request?';
		}

        BootstrapDialog.show({
        	title: 'Confirmation',
            message: 'Are you sure you want to '+confirmmsg,
            closable: true,
            closeByBackdrop: false,
            closeByKeyboard: true,
            onshow: function(dialog) {
                // dialog.getButton('button-c').disable();
            },
            onshown: function(dialog){
            	$('button#externalbtn').prop('disabled',true);
            },
            onhide:function(dialog){
            	$('button#externalbtn').prop('disabled',false);
            },
            buttons: [{
                icon: 'glyphicon glyphicon-ok-sign',
                label: 'Yes',
                cssClass: 'btn-primary',
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
				    		success:function(data){
				    			console.log(data);
			        			var data = JSON.parse(data);
			        			if(data['st'])
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
							        dialog.getModalBody().css('background-color', '#86E2D5');
							        dialog.getModalBody().css('color', '#000');
							        dialog.open();
							        setTimeout(function(){
				                    	dialog.close();
				               		}, 1500);
				               		setTimeout(function(){
				                    	window.location.href = 'special-externalgc-approved-pdf.php?id='+data['reqid'];
				               		}, 1700);		        				
			        			} 
			        			else
			        			{
					    			$('.response').html('<div class="alert alert-danger dangerxx">'+data['msg']+'</div>');					    			
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
                }
            }]
        });
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
							var budget = $('#_budget').val();
							var sum = 0, sum1=0,mul=0;
							for(var $x=1;$x<=6;$x++) {
								var inputs = $("#num"+$x).val();
								inputs = inputs.replace(/,/g , "");
								sum = sum +inputs;
								mul = inputs * $("#m"+$x).val();
								sum1 = sum1 +mul;
							}
							if(sum1<=budget)
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
							$('.response').html('<div class="alert alert-danger" id="danger-x">Date Needed field is required.</div>');
							$('#dp1').focus();												
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

	$('.form-container').on('change','select.promogupdate',function(){
		var gr = $('input[name=pe_group]').val();
		if($(this).val()!='')
		{
			$('input.denfield').prop('disabled',false).each(function(){
				$(this).val(0);
			});
			if($(this).val()==1)
			{
				$.ajax({
					url:'../ajax.php?action=promobudget',
					success:function(data)
					{
						console.log(data);
						var data = JSON.parse(data);
						var g1 = data['g1'];
						$('span.groupname').text('Group 1');
						$('div.grouppromo input#_budget,div.upgrouppromo input#_budget').val(g1);
						$('.grouppromo').show();	
						calculateGCProduction();		
					}
				});
			}
			else 
			{
				$.ajax({
					url:'../ajax.php?action=promobudget',
					success:function(data)
					{
						console.log(data);
						var data = JSON.parse(data);
						var g2 = data['g2'];
						$('span.groupname').text('Group 2');
						$('div.grouppromo input#_budget, div.upgrouppromo input#_budget').val(g2);
						$('.grouppromo').show();	
						calculateGCProduction();	
					}
				});
			}
			$('#num1').select();		
			if($(this).val()==gr)
			{
				for (var i = 1; i <= 6 ; i++)
				{
					var x = $('#o'+i).val();
					$('#num'+i).val(x);		
				}				
			}
		}
		else 
		{
			$('input.denfield').prop('disabled',true);
			$('.grouppromo').hide();
			$('input.denfield').each(function(){
				$(this).val(0);
				$('span.groupname').text('');				
			});
			$('div.grouppromo input#_budget').val(0);
			calculateGCProduction();	
		}
	});
	calculateGCProduction();
	function calculateGCProduction()
	{
		var budget = $('input#_budget').val() 
		if(budget==undefined)
		{
			budget=0;
		}
		$("[name='n']").text(budget);
		var ttotal = 0;
		for(var $x=1;$x<=6;$x++) 
		{
			var a = $("#num"+$x).val() * $("#m"+$x).val();
			ttotal +=a;
			// $("#n"+$x).text(addCommas(Math.floor(a)));
		}
		var newbudget = budget - ttotal;
		forS = parseInt(newbudget);
		forS = forS.toFixed(2);
		forS = addCommas(forS);
		$('#n').text(forS) 
		for(var $x=1;$x<=6;$x++) {
			var a = newbudget / $("#m"+$x).val();
			$("#n"+$x).text(addCommas(Math.floor(a)));
		}
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

}); /*** close**/

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
		for(var $x=1;$x<=6;$x++) {
			var inputs = $("#num"+$x).val();
			inputs = inputs.replace(/,/g , "");
			sum = sum +inputs;
			mul = inputs * $("#m"+$x).val();
			sum1 = sum1 +mul;
		}

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
				    		success:function(data){
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

function pendingPromoGCRequest(id)
{
	location.href = 'promo-gc-request-approval.php?id='+id;
}

function pendingSpecialExternalGC(id)
{
	location.href = 'special-external-request-approval.php?id='+id;
}

function reviewgc(id)
{
	location.href = 'reviewspecialgc.php?id='+id;
}

function viewCustomerGC(id)
{	
	if(id!='')
	{
		BootstrapDialog.show({
	    	title: 'Customer Requested GC',
	    	cssClass: 'gcrevalidation',
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
	            'pageToLoad': '../dialogs/extenalgc.php?action=viewCustomerGC&id='+id
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
	            label: 'Close',
	            cssClass: 'btn-primary',
	            hotkey: 13,
	            action:function(dialogItself){
	            	dialogItself.close();	            	
	            }
	        }]

	    });
	}
}

function viewCheckInfo(id)
{
	if(id!='')
	{
		BootstrapDialog.show({
	    	title: 'Check Details',
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
	            'pageToLoad': '../dialogs/extenalgc.php?action=viewCheckInfo&id='+id
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
	            label: 'Close',
	            cssClass: 'btn-primary',
	            hotkey: 13,
	            action:function(dialogItself){
	            	dialogItself.close();	            	
	            }
	        }]

	    });
	}
}

function getUrlVars() {
  var vars = {};
  var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
  vars[key] = value;
  });
  return vars;
}


if(getUrlVars()['specialexternal']!=undefined)
{
	var id = getUrlVars()['specialexternal'];
    BootstrapDialog.show({
        title: 'Special External GC Approval Report.',
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
          'pageToLoad': '../dialogs/releasedgcreport.php?id='+id+'&type=special'
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

if(getUrlVars()['specialexternalreport']!=undefined)
{
    var id = getUrlVars()['specialexternalreport'];
    BootstrapDialog.show({
        title: 'Special External GC Report.',
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
          'pageToLoad': '../dialogs/releasedgcreport.php?id='+id+'&type=specialexternalgcreport'
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

function recomby(rid)
{
    BootstrapDialog.show({
        title: 'Promo GC Recommendation Details',
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
          'pageToLoad': '../dialogs/promo.php?action=recomdetails&id='+rid
        },
        cssClass: 'store-staff-dialog',           
        onshown: function(dialogRef){                   
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

function scanspecialGC(id)
{
    BootstrapDialog.show({
        title: 'Scan GC',
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
            'pageToLoad': '../dialogs/scan_gcrel.php?action=scanspecialGC&id='+id
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
           		var t = $('#storeRequestList').DataTable();
	    		var counter = 1;
            	var trid = $('input[name=trid]').val();
            	var barcode = $('#gcbarcode').val(), formUrl = $('form#gcreviewscangc').attr('action');
				if(barcode==undefined)
				{
					return false;
				}
            	if(barcode.trim()!='')
            	{
            		$.ajax({
            			url:formUrl,
            			data:{barcode:barcode,trid:trid},
            			type:'POST',
            			success:function(data)
            			{
            				console.log(data);
            				var data = JSON.parse(data);
            				if(data['st'])
            				{
				    			var counter = 1;
						        t.row.add( [		        	
						            data['lastname'],
						            data['firstname'],
						            data['middlename'],
						            data['nameext'],
						            data['denomination'],
						            data['barcode']
						        ] ).draw( false );
						 		
						        counter++;
						       	
						        var total = parseFloat(total).toFixed(2);
						        total = addCommasz(data['total']);

						        $('#scannedgc').val(data['gccount']);
						        $('#totdenomsca').val(total);
						        $('.response-validate').html('<div class="alert alert-success alert-dismissable">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');							            	
            				}
            				else 
            				{
            					$('.response-validate').html('<div class="alert alert-danger alert-dismissable">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');							            	
            				}
            			}
            		});
	            } 
	            else 
	            {
    				$('.response-validate').html('<div class="alert alert-danger alert-dismissable">Please input GC barcode number.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');							     
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

function addCommasz(nStr)
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