$(document).ready(function(){

    // if(window.location.href.indexOf("cardsales") > -1)
    // {
    //     alert('x');
    //     $('.cardsalesload').load('../ajax.php?action=loadcardsalesbycards');
    // }
    var url = window.location.href;
    var value = url.substring(url.lastIndexOf('/') + 1);
    if(value=='cardsales.php')
    {
        $('.cardsalesload').load('../ajax.php?action=loadcardsalesbycards');
    }
	$.extend( $.fn.dataTableExt.oStdClasses, {	  
	    "sLengthSelect": "selectsup"
	});
    $('#userlist, #stores, #backuplist').dataTable( {
        "pagingType": "full_numbers",
        "ordering": false,
        "processing": true,
    });

    $('input.onoffswitch-checkbox').click(function(){
        if($(this).is(":checked"))
        {
            $(this).prop('checked', true).attr('checked', 'checked');            
            checked = true;
        }
        else {
            $(this).prop('checked', false).removeAttr('checked');
            checked = false;
        }
        var storeid = $(this).attr('name');

        $.ajax({
            url:'../ajax.php?action=storereceipt',
            data:{storeid:storeid, checked:checked},
            type:'POST',
            success:function(data)
            {
                var data = JSON.parse(data);
                if(!data['st'])
                {
                    alert(data['msg']);
                    //alert(data['msg'])
                }
            }
        });
    });

    $('input.onoffswitch-checkbox.fadsys').click(function(){
        if($(this).is(":checked"))
        {
            $(this).prop('checked', true).attr('checked', 'checked');            
            checked = true;
        }
        else {
            $(this).prop('checked', false).removeAttr('checked');
            checked = false;
        }

        $.ajax({
            url:'../ajax.php?action=fadServerConnectionStatus',
            data:{checked:checked},
            type:'POST',
            success:function(data)
            {
                var data = JSON.parse(data);
                if(!data['st'])
                {
                    alert(data['msg']);
                    //alert(data['msg'])
                }
            }
        });

    });

    $('#addnew').click(function(){
        BootstrapDialog.show({
            title: '<i class="fa fa-user-plus"></i> Add New User',            
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
                'pageToLoad': '../dialogs/addnew-users.php'
            },
            onshow: function(dialog) {

            },
            onshown: function(dialogRef){
                $('input[name=uname]').focus();
            },
            buttons:[ {
                icon: 'fa fa-check-square-o',
                label: 'Add',
                cssClass: 'btn-primary',
                hotkey: 13,
                action: function(dialogItself){  
                    var $button1 = this;
                    $('.response').html('');
                    var formData = $('.form-container form#_add_users').serialize(), formUrl = $('.form-container form#_add_users').attr('action');
                        var notEmpty = true;
                        var errorcode =0;
                        hasGroup = true;
             
                        var errormsg = [];

                        $('.reqfield').each(function(){                            
                            if($(this).val()=='')                         
                            {                                      
                                notEmpty = false;
                                errormsg.push('Please fill form.');
                                return false;
                            }
                        });

                        if($('select[name=utype]').val()=='')
                        {
                            hasGroup = false;
                            notEmpty = false;
                            errormsg.push('Please select user type.');
                        }


                        if($('select[name=utype]').val()=='7' && $('select[name=uassigned]').val()=='')
                        {
                            notEmpty = false;
                            errormsg.push('Please select Subsidiary.');
                        }

                        if($('select[name=utype]').val()=='8' && $('select[name=ugroupretail]').val()=='')
                        {
                            notEmpty = false;
                            errormsg.push('Please select Retail Group.');                           
                        }

                        if($('select[name=utype]').val()!='1' && $('select[name=uroles]').val().trim()=='')
                        {
                            notEmpty = false;
                            errormsg.push('Please select User Role.');                                  
                        }

                        //alert($('select[name=uroles]').val());

                        // var ugroup  = $('select[name=ugroup]').val();
                        // alert(ugroup);
                        // return false;
                        // if(ugroup.trim()!='1' || ugroup.trim()=='')
                        // {
                        //     if(hasGroup)
                        //     {
                        //         if($('select[name=uroles]').val()=='')
                        //         {   
                        //             notEmpty = false;
                        //             errormsg.push('Please select user role.');                                    
                        //         }
                        //     }                      
                        // }
                  
                        if(notEmpty)
                        {
                            $('.response').html('');
                            BootstrapDialog.show({
                                title: 'Confirmation',
                                message: 'Add User?',
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
                                        var $button = this;
                                        $button.disable();                                        
                                        $.ajax({
                                            url:formUrl,
                                            data:formData,
                                            type:'POST',
                                            success:function(data){
                                                console.log(data);
                                                var data = JSON.parse(data);
                                                if(data['st'])
                                                {
                                                    BootstrapDialog.closeAll();
                                                    var dialog = new BootstrapDialog({
                                                    message: function(dialogRef){
                                                    var $message = $('<div>User successfully added.</div>');                   
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
                                                        location.reload();
                                                    }, 1700);
                                                }
                                                else 
                                                {
                                                    dialogItself.close();
                                                    $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
                                                    $('input[name=uname]').focus();
                                                    $button.enable();
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
                            var erromsg = '';
                            for(i=0; i<(errormsg.length); i++)
                            {
                                // erromsg+'<li>'+errormsg[i]+'</li>';
                               erromsg += '<li>'+errormsg[i]+'</li>';
                            }
                            $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot"><ul>'+erromsg+'</ul></div>');
                            $('input[name=uname]').focus();
                        }

                }

            },
            {
                id:'btn-cancel',
                icon: 'glyphicon glyphicon-remove-sign',
                label: 'Cancel',
                action: function(dialogItself){
                    dialogItself.close();
                }
            }]
        });

    });

    $('table#userlist tbody.tbody-userlist').on('click','tr td i.uusers',function(){
    	var id = $(this).closest('tr').attr('staffid');
        BootstrapDialog.show({
            title: '<i class="fa fa-pencil-square-o"></i> Edit User',
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
                'pageToLoad': '../dialogs/update-users.php?id='+id
            },
            onshow: function(dialog) {
            },
            onshown: function(dialogRef){

            },
            buttons:[ {
                icon: 'fa fa-check-square-o',
                label: 'Update',
                cssClass: 'btn-primary',
                hotkey: 13,
                action: function(dialogItself){  
                    var formData = $('.form-container form#_update_users').serialize(), formUrl = $('.form-container form#_update_users').attr('action');
                    if($('#uname').val()!=undefined)
                    {
                        var notEmpty = true;                
                        $('.form-container form#_update_users input[type=text],select[name=ugroup]').each(function(){                        	
                            if($(this).val()=='')                         
                            {    
                                notEmpty = false;
                            }
                        });
                        if($('select[name=ugroup]').val()=='7' && $('select[name=uassigned]').val()=='')
                        {
                        	notEmpty = false;
                        	$('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">Please select store.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
                        	timeoutmsg();
                        }
                        if(notEmpty)
                        {
                            BootstrapDialog.show({
                                title: 'Confirmation',
                                message: 'Update User?',
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
                                            url:formUrl,
                                            data:formData,
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
                                                    var $message = $('<div>User successfully updated.</div>');                   
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
                                                    dialogItself.close();
                                                    $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
                                                    $('input[name=uname]').focus();
                                                }
                                            }
                                        })
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
                            $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">Please fill in all fields.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
                        }
                    }
                }
            },
            {
                id:'btn-cancel',
                icon: 'glyphicon glyphicon-remove-sign',
                label: 'Cancel',
                action: function(dialogItself){
                    dialogItself.close();
                }
            }]
        });
    });

    $('table#userlist tbody.tbody-userlist').on('click','tr td i.sstatus',function(){
        var id = $(this).closest('tr').attr('staffid'), status = $(this).attr('status');
        var msg = '';
        if(status=='1')
        {
            msg = 'Deactivate ';
            msgs = 'deactivated';
        }
        else 
        {
            msg = 'Activate ';
            msgs = 'activated'
        }
        BootstrapDialog.show({
            title: 'Confirmation',
            message: msg+' <span id="_resetname"></span> account?',
            closable: true,
            closeByBackdrop: false,
            closeByKeyboard: true,
            onshow: function(dialog) {                            
               
            },
            onshown: function(dialog)
            {
                $.ajax({
                    url:"../ajax.php?action=getUsername",
                    type:"POST",
                    data:{id:id},
                    success:function(response)
                    {
                        $('#_resetname').html(response);
                    }
                });
                
            },
            buttons: [{
                icon: 'glyphicon glyphicon-ok-sign',
                label: 'Yes',
                cssClass: 'btn-primary',
                hotkey: 13,
                action:function(dialogItself){
                    $.ajax({
                        url:"../ajax.php?action=manageStatus",
                        data:{id:id,status:status},
                        type:'POST',
                        success:function(response){
                            var res  = response.trim();
                            if(res=='success')
                            {
                                BootstrapDialog.closeAll();
                                var dialog = new BootstrapDialog({
                                message: function(dialogRef){
                                var $message = $('<div>Account successfully '+msgs+'.</div>');                   
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
                                alert(res);
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
        
    });

    $('table#userlist tbody.tbody-userlist').on('click','tr td i.ssreset',function(){
    	var id = $(this).closest('tr').attr('staffid');

        BootstrapDialog.show({
            title: 'Confirmation',
            message: 'Reset <span id="_resetname"></span> password?',
            closable: true,
            closeByBackdrop: false,
            closeByKeyboard: true,
            onshow: function(dialog) {                            
               
            },
            onshown: function(dialog)
            {
                $.ajax({
                    url:"../ajax.php?action=getUsername",
                    type:"POST",
                    data:{id:id},
                    success:function(response)
                    {
                        $('#_resetname').html(response);
                    }
                });
                
            },
            buttons: [{
                icon: 'glyphicon glyphicon-ok-sign',
                label: 'Yes',
                cssClass: 'btn-primary',
                hotkey: 13,
                action:function(dialogItself){
                    $.ajax({
                        url:"../ajax.php?action=resetPassword",
                        data:{id:id},
                        type:'POST',
                        success:function(response){
                            var res  = response.trim();
                            if(res=='success')
                            {
                                BootstrapDialog.closeAll();
                                var dialog = new BootstrapDialog({
                                message: function(dialogRef){
                                var $message = $('<div>Password has been reset successfully.</div>');                   
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
    });

	$('.form-container').on('submit','form#rebuildForm',function(){
		var formUrl = $(this).attr('action'), formData = $(this).serialize();
        BootstrapDialog.show({
        	title: 'Confirmation',
            message: 'Are you sure you want to rebuild database?',
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
							data:formData,
							beforeSend:function(){
								$('#processing-modal').modal('show');
							},
							success:function(response){
								$('#processing-modal').modal('hide'); 
				    			var res = response.trim();
				    			if(res=='success'){
									var dialog = new BootstrapDialog({
						            message: function(dialogRef){
						            var $message = $('<div>Database Successfully Rebuild.</div>');			        
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

								} else {									
									$('.response').html('<div class="alert alert-danger alert-dismissable">'+res+'</div>');
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


    $('.form-container').on('submit','form#fadserverupdate',function(){
        var formURL = $(this).attr('action'), formDATA = $(this).serialize();
        $('.response').html('');
        hasEmpty = false;
        $('.inptxt').each(function(){
            if($(this).val().trim()=='')
            {
                hasEmpty = true;
            }
        });

        if(hasEmpty)
        {
            $('.response').html('<div class="alert alert-danger" id="danger-x">Please input all fields.</div>');
            return false;
        }

        $.ajax({
            url:formURL,
            data:formDATA,
            type:'POST',
            success:function(data)
            {
                console.log(data);
                var data = JSON.parse(data);
                if(data['st'])
                {

                }
                else 
                {
                    $('.response').html('<div class="alert alert-danger" id="danger-x">'+data['msg']+'</div>');
                }
            }
        });


        return false;
    });

	$('#user-add').click(function(){
		BootstrapDialog.show({
			title: '<i class="fa fa-user"></i> Fill New User Details',
		    message: $('<div></div>').load('../dialogs/addnewuser.php'),
			cssClass: 'update-supplier',
		    closable: true,
		    closeByBackdrop: false,
		    closeByKeyboard: true,
		    onshow: function(dialog) {
		        // dialog.getButton('button-c').disable();
		    },
		    onshown: function(dialogRef){
		    	$('form#addnewUser input#uname').focus();

		    	$('#utype').change(function(){
		    		var utype = $(this).val();
		    		if(utype=='retailstore'){
		    			$('#stores-a').removeClass('hide');
		    		} else {
		    			$('#stores-a').addClass('hide');
		    		}
		    	});

		    },
		    buttons: [{
		        icon: 'glyphicon glyphicon-ok-sign',
		        label: 'Submit',
		        cssClass: 'btn-success',
		        hotkey: 13,
		        action:function(dialogItself){
		        	var emptyfield = false;
		        	var selectstore = false;
				    $('form#addnewUser input').each(function() {
				        if(!$(this).val()){
				        	emptyfield=true;
				        }
				    });

				    var utype = $('form#addnewUser select#utype').val();

				    var uassigned = $('form#addnewUser select#uassigned').val();

			        if(!emptyfield){
			        	if(utype=='0'){
							$('.response').html('<div class="alert alert-danger danger-x">Please select department.</div>');
							$('form#addnewUser input#uname').focus();
							timeoutmsg();
			        	} else {
			        		if(utype=='retailstore' && uassigned=='0'){
									$('.response').html('<div class="alert alert-danger danger-x">Please select store.</div>');
									$('form#addnewUser input#uname').focus();
									timeoutmsg();
			        		} else {
						        BootstrapDialog.show({
						        	title: 'Confirmation',
						            message: 'Add New User?',
						            cssClass: 'confirm-adduser',
						            closable: true,
						            closeByBackdrop: false,
						            closeByKeyboard: true,
						            onshow: function(dialog) {
						                
						            },
						            buttons: [{
						                icon: 'glyphicon glyphicon-ok-sign',
						                label: 'Yes',
						                cssClass: 'btn-success',
						                hotkey: 13,
						                action:function(dialogItself){
						                	var formUrl = $('.form-container form#addnewUser').attr('action');
						                	var formData = $('.form-container form#addnewUser').serialize();
						                	$.ajax({
						                		url:formUrl,
						                		type:'POST',
						                		data:formData,
						                		beforeSend:function(){

						                		},
						                		success:function(response){
						                			var res = response.trim();
						                			if(res=='success'){
						                				$('table.customer tbody.tbody-users').load('../ajax.php?action=loadusers');
						                				BootstrapDialog.closeAll();
						                			} else {
						                				$('.response').html('<div class="alert alert-danger danger-x">'+res+'</div>');
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

		            	
			        } else {
						$('.response').html('<div class="alert alert-danger danger-x"> Some fields are empty.</div>');
						$('form#addnewUser input#uname').focus();
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

	$('table.customer tbody.tbody-users').on('click','tr td a.cus-update',function(){
		var id = $(this).attr('href');
		BootstrapDialog.show({
			title: '<i class="fa fa-user"></i> Update User',
		    message: $('<div></div>').load('../dialogs/updateuser.php?id='+id),
			cssClass: 'update-supplier',
		    closable: true,
		    closeByBackdrop: false,
		    closeByKeyboard: true,
		    onshow: function(dialog) {
		        // dialog.getButton('button-c').disable();
		    },
		    onshown: function(dialogRef){
		    	$('form#updateUser input#uname').focus();

		    	$('form#updateUser select#utype').change(function(){
		    		var utype = $(this).val();
		    		if(utype!='retailstore'){
		    			$('#stores-a').addClass('hide');
		    		} else {
		    			$('#$store-a').removeClass('hide');
		    		}
		    	});
		    },
		    buttons: [{
		        icon: 'glyphicon glyphicon-ok-sign',
		        label: 'Submit',
		        cssClass: 'btn-success',
		        hotkey: 13,
		        action:function(dialogItself){
		        	var emptyfield = false;
				    $('form#supplierinfoform input').each(function() {
				        if(!$(this).val()){
				        	emptyfield=true;
				        }
				    });
			        if(!emptyfield){
				        BootstrapDialog.show({
				        	title: 'Confirmation',
				            message: 'Update Supplier?',
				            closable: true,
				            closeByBackdrop: false,
				            closeByKeyboard: true,
				            onshow: function(dialog) {
				                
				            },
				            buttons: [{
				                icon: 'glyphicon glyphicon-ok-sign',
				                label: 'Yes',
				                cssClass: 'btn-success',
				                hotkey: 13,
				                action:function(dialogItself){
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
				                }
				            }, {
				            	icon: 'glyphicon glyphicon-remove-sign',
				                label: 'No',
				                action: function(dialogItself){
				                    dialogItself.close();
				                }
				            }]
				        });			      

		            	
			        } else {
						$('.response').html('<div class="alert alert-danger danger-x"> Some fields are empty.</div>');
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

	$('table.customer tbody.tbody-users').on('click','tr td a.cus-delete',function(){
		BootstrapDialog.show({
			title: '<i class="fa fa-user"></i> Update User',
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
		        cssClass: 'btn-success',
		        hotkey: 13,
		        action:function(dialogItself){
		        	var emptyfield = false;
				    $('form#supplierinfoform input').each(function() {
				        if(!$(this).val()){
				        	emptyfield=true;
				        }
				    });
			        if(!emptyfield){
				        BootstrapDialog.show({
				        	title: 'Confirmation',
				            message: 'Update Supplier?',
				            closable: true,
				            closeByBackdrop: false,
				            closeByKeyboard: true,
				            onshow: function(dialog) {
				                
				            },
				            buttons: [{
				                icon: 'glyphicon glyphicon-ok-sign',
				                label: 'Yes',
				                cssClass: 'btn-success',
				                hotkey: 13,
				                action:function(dialogItself){
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
				                }
				            }, {
				            	icon: 'glyphicon glyphicon-remove-sign',
				                label: 'No',
				                action: function(dialogItself){
				                    dialogItself.close();
				                }
				            }]
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

	function timeoutmsg(){
	    setTimeout(function(){
	    	$('.response').html('');
	    }, 2000);
	}
});


function addnewcustomer()
{
    BootstrapDialog.show({
        title: '<i class="fa fa-user"></i> Customer Form',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: false,
        cssClass: 'customer-internal',
        message: function(dialog) { 
            var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
            var pageToLoad = dialog.getData('pageToLoad');
            setTimeout(function(){
            $message.load(pageToLoad);
            },1000);
            return $message;
        },
        data: {
            'pageToLoad': '../dialogs/addnewcustomerinternal.php'
        },
        onshown: function(dialog) {
            setTimeout(function(){
                $('#cusfname').focus();
            },1200);
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Submit',
            cssClass: 'btn-primary',
            hotkey: 13,
            action:function(dialogItself){
                var formData = $('form#customer-internal').serialize(), formURL = $('form#customer-internal').attr('action');
                var hasEmpty = false;
                var errormsg = [];
                $('.reqfield').each(function(){
                   if($(this).val().trim()=='')
                   {
                        hasEmpty = true;
                        errormsg.push('Please fill form.');
                        return false;
                   }
                });

                if(!hasEmpty)
                {

                    BootstrapDialog.show({
                        title: 'Confirmation',
                        message: 'Are you sure you want to add customer?',
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
                                    url:formURL,
                                    data:formData,
                                    type:'POST',
                                    success:function(data)
                                    {
                                        console.log(data);
                                        var data = JSON.parse(data);
                                        if(data['stat'])
                                        {
                                            dialogItself.close();
                                            $('.response').html('');
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
                                            setTimeout(function(){
                                                dialog.close();
                                            }, 1500);
                                            setTimeout(function(){
                                                window.location.reload();
                                            }, 1700);
                                        }
                                        else 
                                        {
                                            errormsg.push(data['msg']);
                                            displayerrors(errormsg,'.response');
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
                    displayerrors(errormsg,'.response');
                }

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

function displayerrors(errormsg,div)
{
    var erromsg = '';
    for(i=0; i<(errormsg.length); i++)
    {
        // erromsg+'<li>'+errormsg[i]+'</li>';
       erromsg += '<li class="leftpad0">'+errormsg[i]+'</li>';
    }
    $(div).html('<div class="alert alert-danger alert-dismissable alert-no-bot"><ul class="ulleftpad14">'+erromsg+'</ul></div>');    
}


function discount(id)
{
    BootstrapDialog.show({
        title: '<i class="fa fa-user"></i></i> Discount Form',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: false,
        cssClass: 'customer-internal',
        message: function(dialog) { 
            var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
            var pageToLoad = dialog.getData('pageToLoad');
            setTimeout(function(){
            $message.load(pageToLoad);
            },1000);
            return $message;
        },
        data: {
            'pageToLoad': '../dialogs/adddiscount.php?customerid='+id
        },
        onshown: function(dialog) {
            setTimeout(function(){
                $('select[name=disctype]').focus();
                    $('.denoms').inputmask();
            },1200);
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Submit',
            cssClass: 'btn-primary',
            hotkey: 13,
            action:function(dialogItself){
                var formData = $('form#customer-internal').serialize(), formURL = $('form#customer-internal').attr('action');
                var hasEmpty = false, noAmount=true;
                var errormsg = [];
                $('.reqfield').each(function(){
                   if($(this).val().trim()=='')
                   {
                        hasEmpty = true;
                        errormsg.push('Please fill form.');
                        return false;
                   }
                });

                //check denoms
                $('.denoms').each(function(){
                    // if($(this).val().trim())
                    var den = parseFloat($(this).val().replace(/,/g , ""));
                    if(den>0)
                    {
                        noAmount = false;
                        return false;
                    }
                });

                if($('select[name=disctype]').val()>0 && noAmount)
                {
                    hasEmpty = true;
                    errormsg.push('Please input value.');
                }

                if(!hasEmpty)
                {
                    $('.response').html('');
                    BootstrapDialog.show({
                        title: 'Confirmation',
                        message: 'Update discount?',
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
                                    url:formURL,
                                    data:formData,
                                    type:'POST',
                                    success:function(data)
                                    {
                                        console.log(data);
                                        var data = JSON.parse(data);
                                        if(data['stat'])
                                        {
                                            BootstrapDialog.closeAll();
                                            var dialog = new BootstrapDialog({
                                            message: function(dialogRef){
                                            var $message = $('<div>Discount successfully updated.</div>');                  
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
                                            dialogItself.close();
                                            $('.response').html('<div class="alert alert-danger">'+data['msg']+'</div>');
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
                    displayerrors(errormsg,'.response');
                }

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

function discountchange(id)
{
    if(id=='0')
    {
        $('.denoms').prop('disabled',true);
        $('.denoms').val('0.00');
    }
    else 
    {
        $('.denoms').prop('disabled',false);
    }
}

function addStoreStaff()
{
    BootstrapDialog.show({
        title: '<i class="fa fa-user-plus"></i> Add New User',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: false,
        cssClass: 'store-staff-dialog',
        message: function(dialog) {
            var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
            var pageToLoad = dialog.getData('pageToLoad');
            setTimeout(function(){
            $message.load(pageToLoad);
            },1000);
            return $message;
        },
        data: {
            'pageToLoad': '../dialogs/add-store-user.php'
        },
        onshow: function(dialog) {
            // dialog.getButton('button-c').disable();
        },
        onshown: function(dialog){
            //pendinggc
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Save',
            cssClass: 'btn btn-primary',
            hotkey: 13,
            action:function(dialogIt){
                var $buttons = this;
                $buttons.disable();
                $('.response').html('');
                if($('input[name=uname]').val()!=undefined)
                {
                    var hasEmpty = false;
                    $('.reqfield').each(function(){
                        if($(this).val().trim()=='')
                        {
                            hasEmpty=true;
                        }
                    });

                    if(!hasEmpty)
                    {
                        var formData = $('form#_store-staff').serialize(), formURL = $('form#_store-staff').attr('action');
                        $.ajax({
                            url:'../ajax.php?action=checkstoreuser',
                            type:'POST',
                            data:formData,
                            success:function(data)
                            {
                                console.log(data);
                                var data = JSON.parse(data);
                                if(data['st'])
                                {
                                    BootstrapDialog.show({
                                        title: 'Confirmation',
                                        message: 'Add User?',
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
                                                $buttonconfirm = this;
                                                $buttonconfirm.disable();
                                                $.ajax({
                                                    url:formURL,
                                                    data:formData,
                                                    type:'POST',
                                                    success:function(data1)
                                                    {
                                                        console.log(data1);
                                                        var data1 = JSON.parse(data1);
                                                        if(data1['st'])
                                                        {
                                                            BootstrapDialog.closeAll();
                                                            var dialog = new BootstrapDialog({
                                                            message: function(dialogRef){
                                                            var $message = $('<div>'+$('input[name=uname]').val()+' Successfully Added.</div>');                  
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
                                                            dialogItself.close();
                                                            $buttons.enable();
                                                            $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">'+data1['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
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
                                                    $buttons.enable();
                                            }
                                        }]
                                    });                                         
                                }
                                else 
                                {
                                    $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
                                    $buttons.enable();   
                                }
                            }
                        });
                    } 
                    else 
                    {
                        $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">Please fill in all fields.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
                        $buttons.enable();   
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

function randompass()
{
    var num = Math.floor(Math.random() * 90000) + 10000;
    $('input[name=password]').val(num);
}

function checkstoreusername(uname)
{

}

function addNewBackup()
{
    BootstrapDialog.show({
        title: 'Confirmation',
        message: 'Are you sure you want to backup database?',
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
                    url:'../ajax.php?action=backupdb',
                    beforeSend:function()
                    {
                        $('#processing-modal').modal('show');
                    },
                    success:function(data)
                    {
                        $('#processing-modal').modal('hide');
                        var data = JSON.parse(data);
                        if(data['st'])
                        {
                            var dialog = new BootstrapDialog({
                            message: function(dialogRef){
                            var $message = $('<div>Backup successfully performed.</div>');                  
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
                                window.location.reload();
                            }, 1700);   
                        }
                    }
                });      
                    // $.ajax({
                    //     url:formUrl,
                    //     type:'POST',
                    //     data:formData,
                    //     beforeSend:function(){
                    //         $('#processing-modal').modal('show');
                    //     },
                    //     success:function(response){
                    //         $('#processing-modal').modal('hide');
                    //     }
                    // });

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

function updateStoreStaff(id)
{
    BootstrapDialog.show({
        title: '<i class="fa fa-user-plus"></i> Update User',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: false,
        cssClass: 'store-staff-dialog',
        message: function(dialog) {
            var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
            var pageToLoad = dialog.getData('pageToLoad');
            setTimeout(function(){
            $message.load(pageToLoad);
            },1000);
            return $message;
        },
        data: {
            'pageToLoad': '../dialogs/updatestoreuser.php?id='+id
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
            action:function(dialogIt){
                var $buttons = this;
                $buttons.disable();
                $('.response').html('');  
                if($('input[name=uname]').val()!=undefined)
                {
                    var hasEmpty = false;
                    $('.reqfield').each(function(){
                        if($(this).val().trim()=='')
                        {
                            hasEmpty=true;
                        }
                    });

                    if(!hasEmpty)
                    {
                        var formData = $('form#_store-staff').serialize(), formURL = $('form#_store-staff').attr('action');
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
                                    BootstrapDialog.closeAll();
                                    var dialog = new BootstrapDialog({
                                    message: function(dialogRef){
                                    var $message = $('<div>'+$('input[name=uname]').val()+' Successfully Updated.</div>');                  
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
                                    $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
                                    $buttons.enable();                                           
                                }
                            }
                        });
                    }
                    else 
                    {
                        $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">Please fill in all fields.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
                        $buttons.enable();                               
                    }                 
                }
                else 
                {
                    $buttons.enable(); 
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

function changestorestaffpassword(id,uname)
{
    BootstrapDialog.show({
        title: '<i class="fa fa-unlock-alt"></i> Change '+uname+' Password',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: false,
        cssClass: 'store-staff-dialog',
        message: function(dialog) {
            var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
            var pageToLoad = dialog.getData('pageToLoad');
            setTimeout(function(){
            $message.load(pageToLoad);
            },1000);
            return $message;
        },
        data: {
            'pageToLoad': '../dialogs/changestorestaffpassword.php?id='+id
        },
        onshow: function(dialog) {
            // dialog.getButton('button-c').disable();
        },
        onshown: function(dialog){
            //pendinggc

        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Change',
            cssClass: 'btn btn-primary',
            hotkey: 13,
            action:function(dialogIt){
                var $buttons = this;
                $buttons.disable();
                $('.response').html('');  
                if($('input[name=password]').val()!=undefined)
                {
                    if($('input[name=password]').val().trim()!='')
                    {
                        var formData = $('form#_store-staff').serialize(), formURL = $('form#_store-staff').attr('action');
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
                                    BootstrapDialog.closeAll();
                                    var dialog = new BootstrapDialog({
                                    message: function(dialogRef){
                                    var $message = $('<div>'+uname+' password successfully changed.</div>');                  
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
                                    $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
                                    $buttons.enable();                                           
                                }
                            }
                        });
                    }
                    else 
                    {
                        $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">Please input or generate password.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
                        $buttons.enable();
                    }
                }
                else 
                {
                    $buttons.enable();
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

function storeuserstatus(id,uname,status)
{
    BootstrapDialog.show({
        title: 'Confirmation',
        message: msg+' <span id="_resetname"></span> account?',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: true,
        onshow: function(dialog) {                            
           
        },
        onshown: function(dialog)
        {
            $.ajax({
                url:"../ajax.php?action=getUsername",
                type:"POST",
                data:{id:id},
                success:function(response)
                {
                    $('#_resetname').html(response);
                }
            });
            
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Yes',
            cssClass: 'btn-primary',
            hotkey: 13,
            action:function(dialogItself){
                $.ajax({
                    url:"../ajax.php?action=manageStatus",
                    data:{id:id,status:status},
                    type:'POST',
                    success:function(response){
                        var res  = response.trim();
                        if(res=='success')
                        {
                            BootstrapDialog.closeAll();
                            var dialog = new BootstrapDialog({
                            message: function(dialogRef){
                            var $message = $('<div>Account successfully '+msgs+'.</div>');                   
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
                            alert(res);
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
}

function checkUsernameUpdate(username,id)
{
    $.ajax({
        url:'../ajax.php?action=checkusername',
        type:'POST',
        data:{userid:id,nusername:username},
        success:function(data)
        {
            var data = JSON.parse(data)
            if(!data['st'])
            {
                $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">'+data['msg']+'</div>');
            }
            else
            {
                 $('.response').html('');
            }
        }
    });
}

function addDenomination()
{
    BootstrapDialog.show({
        title: '<i class="fa fa-credit-card"></i> Denomination Info',            
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
            'pageToLoad': '../dialogs/denomination.php?action=add'
        },
        onshow: function(dialog) {

        },
        onshown: function(dialogRef){
            $('input[name=uname]').focus();
        },
        buttons:[ {
            icon: 'fa fa-check-square-o',
            label: 'Add',
            cssClass: 'btn-primary',
            hotkey: 13,
            action: function(dialogItself){
                $('.response').html('');
                var $buttons = this;
                if($('input[name=denom]').val() != undefined)
                {
                    if($('input[name=denom]').val().trim()!='' && $('input[name=bstart]').val().trim()!='')
                    {
                        // check denomination and barcode # start
                        var formURL = $('form#denomform').attr('action'), formData = $('form#denomform').serialize();
                        $.ajax({
                            url:formURL,
                            type:'POST',
                            data:formData,
                            beforeSend:function(){

                            },
                            success:function(vdata){
                                var vdata = JSON.parse(vdata);

                                if(vdata['st']==1)
                                {
                                    $buttons.disable();
                                    BootstrapDialog.show({
                                        title: 'Confirmation',
                                        message: 'Add new denomination?',
                                        closable: true,
                                        closeByBackdrop: false,
                                        closeByKeyboard: true,
                                        onshow: function(dialog) {
                                            // dialog.getButton('button-c').disable();
                                        },
                                        onhidden: function(dialog){
                                            $buttons.enable();
                                        },  
                                        buttons: [{
                                            icon: 'glyphicon glyphicon-ok-sign',
                                            label: 'Yes',
                                            cssClass: 'btn-primary',
                                            hotkey: 13,
                                            action:function(dialogItself){                  
                                                dialogItself.close();
                                                $.ajax({
                                                    url:'../ajax.php?action=savenewdenom',
                                                    type:'POST',
                                                    data:formData,
                                                    beforeSend:function(){                                                
                                                    },
                                                    success:function(data){
                                                        console.log(data);

                                                        var data = JSON.parse(data);
                                                        if(data['st'])
                                                        {
                                                            BootstrapDialog.closeAll();
                                                            var dialog = new BootstrapDialog({
                                                            message: function(dialogRef){
                                                            var $message = $('<div>Denomination successfully added.</div>');                   
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
                                                            $('input[name=denom]').focus();
                                                            $buttons.enable();
                                                            $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
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
                                    $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">'+vdata['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>')
                                }
                            }
                        });
                    }
                    else 
                    {
                        $('input[name=denom]').focus();
                        $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">Please input denomination and Barcode # start.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
                    }
                }
            }
        },
        {
            id:'btn-cancel',
            icon: 'glyphicon glyphicon-remove-sign',
            label: 'Cancel',
            action: function(dialogItself){
                dialogItself.close();
            }
        }]
    });
}

function setupdenom(den)
{    
    BootstrapDialog.show({
        title: '<i class="fa fa-credit-card"></i> Denomination Setup',            
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
            'pageToLoad': '../dialogs/denomination.php?action=setup&denom='+den
        },
        onshow: function(dialog) {

        },
        onshown: function(dialogRef){
            $('input[name=uname]').focus();
        },
        buttons:[ {
            icon: 'fa fa-check-square-o',
            label: 'Add',
            cssClass: 'btn-primary',
            hotkey: 13,
            action: function(dialogItself){
                $('.response').html('');
                var $buttons = this;
                if($('input[name=denom]').val() != undefined)
                {
                    $buttons.disable();
                    if($('input[name=denom]').val().trim()!='' && $('input[name=bstart]').val().trim()!='')
                    {
                        var bstart = $('input[name=bstart]').val();
                        if(bstart.length == 13)
                        {

                            var formURL = $('form#setupdenomform').attr('action'), formData = $('form#setupdenomform').serialize();
                            $.ajax({
                                url:formURL,
                                type:'POST',
                                data:formData,
                                beforeSend:function(){

                                },
                                success:function(vdata){
                                    console.log(vdata);
                                    var vdata = JSON.parse(vdata);
                                    if(vdata['st'])
                                    {
                                        BootstrapDialog.show({
                                            title: 'Confirmation',
                                            message: 'Setup denomination?',
                                            closable: true,
                                            closeByBackdrop: false,
                                            closeByKeyboard: true,
                                            onshow: function(dialog) {
                                                // dialog.getButton('button-c').disable();
                                            },
                                            onhidden: function(dialog){
                                                $buttons.enable();
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
                                                        url:'../ajax.php?action=savesetupdenom',
                                                        type:'POST',
                                                        data:formData,
                                                        beforeSend:function(){                                                
                                                        },
                                                        success:function(data){
                                                            console.log(data);

                                                            var data = JSON.parse(data);
                                                            if(data['st'])
                                                            {
                                                                BootstrapDialog.closeAll();
                                                                var dialog = new BootstrapDialog({
                                                                message: function(dialogRef){
                                                                var $message = $('<div>Denomination successfully added.</div>');                   
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
                                                                $('input[name=denom]').focus();
                                                                $buttons.enable();
                                                                $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
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
                                        $('input[name=denom]').focus();                                    
                                        $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">'+vdata['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');                                    
                                    }

                                }
                            });
                        }
                        else 
                        {
                            $buttons.enable();
                            $('#bstart').focus();
                            $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">Barcode # start must be 13 characters long.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');                            
                        }
                    } 
                    else 
                    {
                        $buttons.enable();
                        $('#bstart').focus();
                        $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">Please input Barcode # start.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
                    }  
                }
                
            }
        },
        {
            id:'btn-cancel',
            icon: 'glyphicon glyphicon-remove-sign',
            label: 'Cancel',
            action: function(dialogItself){
                dialogItself.close();
            }
        }]
    });
}


function addccard()
{
    BootstrapDialog.show({
        title: '<i class="fa fa-credit-card"></i> Credit Card Info',            
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
            'pageToLoad': '../dialogs/addccard.php'
        },
        onshow: function(dialog) {

        },
        onshown: function(dialogRef){
            $('input[name=uname]').focus();
        },
        buttons:[ {
            icon: 'fa fa-check-square-o',
            label: 'Add',
            cssClass: 'btn-primary',
            hotkey: 13,
            action: function(dialogItself){
                $('.response').html('');
                var $this = this;
                if($('input[name=ccardname]').val() != undefined)
                {
                    if($('input[name=ccardname]').val().trim()!='')
                    {
                        $this.disable();
                        BootstrapDialog.show({
                            title: 'Confirmation',
                            message: 'Are you sure you want to add credit card?',
                            closable: true,
                            closeByBackdrop: false,
                            closeByKeyboard: true,
                            onshow: function(dialog) {
                                // dialog.getButton('button-c').disable();
                            },
                            onhidden: function(dialog){
                                $this.enable();
                            },  
                            buttons: [{
                                icon: 'glyphicon glyphicon-ok-sign',
                                label: 'Yes',
                                cssClass: 'btn-primary',
                                hotkey: 13,
                                action:function(dialogItself){                  
                                    dialogItself.close();
                                    var formURL = $('form#ccardform').attr('action'), formData = $('form#ccardform').serialize();
                                    $.ajax({
                                        url:formURL,
                                        type:'POST',
                                        data:formData,
                                        beforeSend:function(){                                                
                                        },
                                        success:function(data){
                                            console.log(data);
                                            var data = JSON.parse(data);
                                            if(data['st'])
                                            {
                                                BootstrapDialog.closeAll();
                                                var dialog = new BootstrapDialog({
                                                message: function(dialogRef){
                                                var $message = $('<div>Credit Card successfully added.</div>');                   
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
                                                $('input[name=ccardname]').focus();
                                                $this.enable();
                                                $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
                                            }
                                        }
                                    });
                                }
                            }, {
                                icon: 'glyphicon glyphicon-remove-sign',
                                label: 'No',
                                action: function(dialogItself){
                                    $this.enable();
                                    dialogItself.close();
                                }
                            }]
                        });
                    }
                    else 
                    {
                        $('input[name=ccardname]').focus();
                        $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">Please input credit card name.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
                    }
                }
            }
        },
        {
            id:'btn-cancel',
            icon: 'glyphicon glyphicon-remove-sign',
            label: 'Cancel',
            action: function(dialogItself){
                dialogItself.close();
            }
        }]
    });
}

function viewcardsales(trid)
{
    BootstrapDialog.show({
        title: '<i class="fa fa-credit-card"></i> Card Transaction Details',            
        cssClass: 'cardtransactiondetails',
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
            'pageToLoad': '../dialogs/cardtransactiondetails.php?trid='+trid
        },
        onshow: function(dialog) {

        },
        onshown: function(dialogRef){
            $('input[name=uname]').focus();
        },
        buttons:[{
            id:'btn-cancel',
            icon: 'glyphicon glyphicon-remove-sign',
            label: 'Close',
            action: function(dialogItself){
                dialogItself.close();
            }
        }]
    });
}

function viewARtransactionDetails(trid)
{
    BootstrapDialog.show({
        title: '<i class="fa fa-credit-card"></i> Transaction Details',            
        cssClass: 'cardtransactiondetails',
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
            'pageToLoad': '../dialogs/customerartransactiondetails.php?trid='+trid
        },
        onshow: function(dialog) {

        },
        onshown: function(dialogRef){
            $('input[name=uname]').focus();
        },
        buttons:[{
            id:'btn-cancel',
            icon: 'glyphicon glyphicon-remove-sign',
            label: 'Close',
            action: function(dialogItself){
                dialogItself.close();
            }
        }]
    });
}

function cardsales(val)
{
    if(val==2)
    {
        $('.cardsalesload').load('../ajax.php?action=loadcardsalesbystore');
    }
    else if(val==1)
    {
        $('.cardsalesload').load('../ajax.php?action=loadcardsalesbycards');
    }
}

function updateDenom(denomid)
{
    BootstrapDialog.show({
        title: '<i class="fa fa-credit-card"></i> Update Denomination Info',            
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
            'pageToLoad': '../dialogs/denomination.php?action=update&denomid='+denomid
        },
        onshow: function(dialog) {

        },
        onshown: function(dialogRef){
            $('#denocr').focus();
        },
        buttons:[ {
            icon: 'fa fa-check-square-o',
            label: 'Update',
            cssClass: 'btn-primary',
            hotkey: 13,
            action: function(dialogItself){
                $('.response').html('');
                var $buttons = this;
                if($('input[name=denom]').val() != undefined)
                {
                    if($('input[name=denom]').val().trim()!='' && $('input[name=bstart]').val().trim()!='')
                    {
                        // check denomination and barcode # start
                        var formURL = $('form#denomform').attr('action'), formData = $('form#denomform').serialize();
                        $.ajax({
                            url:formURL,
                            type:'POST',
                            data:formData,
                            beforeSend:function(){

                            },
                            success:function(vdata){
                                console.log(vdata);
                                var vdata = JSON.parse(vdata);

                                if(vdata['st']==1)
                                {
                                    $buttons.disable();
                                    BootstrapDialog.show({
                                        title: 'Confirmation',
                                        message: 'Update Denomination Info?',
                                        closable: true,
                                        closeByBackdrop: false,
                                        closeByKeyboard: true,
                                        onshow: function(dialog) {
                                            // dialog.getButton('button-c').disable();
                                        },
                                        onhidden: function(dialog){
                                            $buttons.enable();
                                        },  
                                        buttons: [{
                                            icon: 'glyphicon glyphicon-ok-sign',
                                            label: 'Yes',
                                            cssClass: 'btn-primary',
                                            hotkey: 13,
                                            action:function(dialogItself){                  
                                                dialogItself.close();
                                                $.ajax({
                                                    url:'../ajax.php?action=saveupdatedenom',
                                                    type:'POST',
                                                    data:formData,
                                                    beforeSend:function(){                                                
                                                    },
                                                    success:function(data){
                                                        console.log(data);

                                                        var data = JSON.parse(data);
                                                        if(data['st'])
                                                        {
                                                            BootstrapDialog.closeAll();
                                                            var dialog = new BootstrapDialog({
                                                            message: function(dialogRef){
                                                            var $message = $('<div>Denomination successfully updated.</div>');                   
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
                                                            $('input[name=denom]').focus();
                                                            $buttons.enable();
                                                            $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
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
                                    $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">'+vdata['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>')
                                }
                            }
                        });
                    }
                    else 
                    {
                        $('input[name=denom]').focus();
                        $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">Please input denomination and Barcode # start.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
                    }
                }
            }
        },
        {
            id:'btn-cancel',
            icon: 'glyphicon glyphicon-remove-sign',
            label: 'Cancel',
            action: function(dialogItself){
                dialogItself.close();
            }
        }]
    });
}







