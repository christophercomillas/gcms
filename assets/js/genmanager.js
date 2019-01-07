$(document).ready(function(){
	$('table.approveReq').on('click','.approveProd',function(){
        BootstrapDialog.show({
        	title:'Approve Budget Request',
            message:'<div class="addvariant">'+
            		'<form method="POST" action="../ajax.php?action=approveProd" id="approveProdForm">'+
	            		'<table class="table table-variant">'+
	            			'<tr>'+
	            				'<td><label>Remarks: </label></td>'+
	            				'<td><textarea class="form form-control remark" name="remark"></textarea></td>'+
	            			'</tr>'+
	            			'<tr>'+
	            				'<td><label>Password </label></td>'+
	            				'<td><input type="password" class="form form-control" name="password" /></td>'+
	            			'</tr>'+
	            			'<tr style="display:none;">'+
	            				'<td><label>Password </label></td>'+
	            				'<td><input type="password" class="form form-control" /></td>'+
	            			'</tr>'+          			
	            		'</table>'+
	            		'<div class="response">'+
	            		'</div>'+
            		'</form>'+
            		'</div>',
            onshow: function(dialogRef){                
            },
            onshown: function(dialogRef){
            	$('.remark').focus();
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
					var formUrl = $('form#approveProdForm').attr('action'), formData = $('form#approveProdForm').serialize();
				    	$.ajax({
				    		url:formUrl,
				    		type:'POST',
							data: formData,
				    		beforeSend:function(){

				    		},
				    		success:function(response){
	                			var res = response.trim();

	                			if(res == 'success'){
									var dialog = new BootstrapDialog({
						            message: function(dialogRef){
						            var $message = $('<div>Production Request Successfully Approved.</div>');			        
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

	$('.form-container').on('submit','form#prodRequestFin',function(){	
		var formUrl = $(this).attr('action'), formData = new FormData($(this)[0]);
		var emptyfield = false, x = $('#app-checkby').val(), y = $('#app-apprby').val();
		var status = $('#status').val();
		if(status=='1'){
			if(x==='' || y===''){
				$('.response').html('<div class="alert alert-danger danger-x">Please input all fields.</div>');
				timeoutmsg();
			} else { 

		        BootstrapDialog.show({
		        	title: 'Confirmation',
		            message: 'Are you sure you want to approve production request?',
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

					        			if(res == 'success'){
											var dialog = new BootstrapDialog({
								            message: function(dialogRef){
								            var $message = $('<div>Production Request Successfully Approved.</div>');			        
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
							    			$('.response').html('<div class="alert alert-danger dangerxx">'+res+'</div>');
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
		 } else {
	        BootstrapDialog.show({
	        	title: 'Confirmation',
	            message: 'Are you sure you want to cancel production request?',
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
	                	alert('cancelled');
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

	$('#checkbud').click(function(){	
        BootstrapDialog.show({
        	title: 'Checked Tag..',
            message: $('<div></div>').load('../dialogs/checkprorequest.php'),
     	    cssClass: 'add-customer',
	        closable: true,
	        closeByBackdrop: false,
	        closeByKeyboard: true,
	        onshow: function(dialog) {
	            // dialog.getButton('button-c').disable();
	        },
            onshown: function(dialogRef){

            },
	        buttons: [{
	            icon: 'glyphicon glyphicon-ok-sign',
	            label: 'Confirm',
	            cssClass: 'btn-primary',
	            hotkey: 13,
	            action:function(dialogItself){

	            	var status = $('input[name=status]:checked' ).val()
	            	if(status==1){
		            	var fn = $('#auth').val();
		            	$('#app-checkby').val(fn);
		            	dialogItself.close();	            		 
	            	} else {
	            		$('#app-checkby').val('');	            		 
	            		dialogItself.close();
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
	});

	$('#approvedbud').click(function(){		
        BootstrapDialog.show({
        	title: 'Approve Tag..',
            message: $('<div></div>').load('../dialogs/approvedbudgetrequest.php'),
     	    cssClass: 'add-customer',
	        closable: true,
	        closeByBackdrop: false,
	        closeByKeyboard: true,
	        onshow: function(dialog) {
	            // dialog.getButton('button-c').disable();
	        },
            onshown: function(dialogRef){

            },
	        buttons: [{
	            icon: 'glyphicon glyphicon-ok-sign',
	            label: 'Confirm',
	            cssClass: 'btn-primary',
	            hotkey: 13,
	            action:function(dialogItself){

	            	var status = $('input[name=status]:checked' ).val()
	            	if(status==1){
		            	var fn = $('#auth').val();
		            	$('#app-apprby').val(fn);
		            	dialogItself.close();	            		 
	            	} else {
	            		$('#app-apprby').val('');	            		 
	            		dialogItself.close();
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
	});

	function timeoutmsg(){
	    setTimeout(function(){
	    	$('.response').html('');
	    }, 100000);
	}


});