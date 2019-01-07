$(document).ready(function(){
	$.extend( $.fn.dataTableExt.oStdClasses, {	  
	    "sLengthSelect": "selectsup"
	});
    $('#staff').dataTable( {
        "pagingType": "full_numbers",
        "ordering": false,
        "processing": true
    });	

    $('#addstaff').click(function(){
        var id = $(this).attr('store');
        BootstrapDialog.show({
            title: '<i class="fa fa-user-plus"></i> Add New User',
            message: $('<div></div>').load('../dialogs/add-store-user.php?id='+id),
            cssClass: 'store-staff-dialog',
            closable: true,
            closeByBackdrop: false,
            closeByKeyboard: true,
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
                    BootstrapDialog.show({
                        title: 'Confirmation',
                        message: 'Add new user?',
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
                                var formData = $('.form-container form#_store-staff').serialize(), formUrl = $('.form-container form#_store-staff').attr('action');
                                var notEmpty = true;                
                                $('.form-container form#_store-staff input,select').each(function(){
                                    if($(this).val()=='')
                                    {
                                        notEmpty = false;
                                    }
                                });
                                if(notEmpty)
                                {
                                    $.ajax({
                                        url:formUrl,
                                        data:formData,
                                        type:'POST',
                                        success:function(response){
                                            var res = response.trim();
                                            if(res=='success')
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
                                                    dialog.close();
                                                }, 1500);
                                                setTimeout(function(){
                                                    location.reload();
                                                }, 1700);
                                            }
                                            else 
                                            {
                                                dialogItself.close();
                                                $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">'+res+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
                                                $('input[name=uname]').focus();
                                            }
                                        }
                                    });
                                }   
                                else
                                {
                                    $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">Please fill in all fields.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
                                }  

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
            },
            {
                icon: 'glyphicon glyphicon-remove-sign',
                label: 'Cancel',
                action: function(dialogItself){
                    dialogItself.close();
                }
            }]
        });

    	return false;
    });

    $('#staff').on('click','tbody tr td i.ssreset',function(){
        var id = $(this).closest('i.sstaff').attr('staffid');
        alert(id);
    });

    $('#staff').on('click','tbody tr td i.sstaff',function(){        
        var id = $(this).attr('staffid');
        BootstrapDialog.show({
            title: '<i class="fa fa-pencil-square-o"></i> Edit User',
            message: $('<div></div>').load('../dialogs/update-store-user.php?id='+id),
            cssClass: 'store-staff-dialog',
            closable: true,
            closeByBackdrop: false,
            closeByKeyboard: true,
            onshow: function(dialog) {                
            },
            onshown: function(dialogRef){
                $('input[name=uname]').focus();
            },
            buttons:[ {
                icon: 'fa fa-check-square-o',
                label: 'Update',
                cssClass: 'btn-primary',
                hotkey: 13,
                action: function(dialogItself){                    
                    var formData = $('.form-container form#_store-staff').serialize(), formUrl = $('.form-container form#_store-staff').attr('action');
                        var notEmpty = true;                
                        $('.form-container form#_store-staff input,select').each(function(){
                            if($(this).val()=='')
                            {
                                notEmpty = false;
                            }
                        });
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
                                            success:function(response){
                                                var res  = response.trim();
                                                if(res=='success')
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
                                                    $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">'+res+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
                                                    $('input[name=uname]').focus();

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
                            $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">Please fill in all fields.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
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
});