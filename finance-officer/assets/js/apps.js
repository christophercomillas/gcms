$(document).ready(function(){
    
    crossroads.addRoute('/',function(){
        $('div.content-wrapper').load('../ajaxpages.php?page=pending-budget-list');
    });

    crossroads.addRoute('/budget-pending-request/',function(){
        $('div.content-wrapper').load('../ajaxpages.php?page=pending-budget-list');
    });

    crossroads.addRoute('/budget-pending-request/{reqId}',function(reqId){
        $('div.content-wrapper').load('../ajaxpages.php?page=pending-budget&reqid='+reqId);
    });

    crossroads.addRoute('/approved-budget-request/',function(){
        $('div.content-wrapper').load('../ajaxpages.php?page=approved-budget-request');
    });

    crossroads.addRoute('/approved-budget-request/{reqId}',function(reqId){
        $('div.content-wrapper').load('../ajaxpages.php?page=approved-budget-request-single&reqid='+reqId);
    });

    window.addEventListener("hashchange",function(){
        var route = '/';
        var hash = window.location.hash;
        if(hash.length > 0)
        {
            route = hash.split('#').pop();
        }
        crossroads.parse(route);
    });

    window.dispatchEvent(new CustomEvent("hashchange"));

    $('#profile').click(function(){
        userprofile(1);
    });

    function userprofile(id)
    {  
        BootstrapDialog.show({
            title: '<i class="fa fa-user"></i> User Profile',
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
                'pageToLoad': '../dialogs/user-profile.php?userid='+id
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
});

    function sample()
    {
        alert('x');
    }


    function changeusername(id,username){
        BootstrapDialog.show({
            title: '<i class="fa fa-user"></i> Change Username',
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
                'pageToLoad': '../dialogs/changeusername.php?userid='+id+'&username='+username
            },
            onshown: function(dialogRef){
            },
            buttons: [{
                icon: 'glyphicon glyphicon-ok-sign',
                cssClass: 'btn-primary subchange',
                hotkey: 13,
                label: 'Submit',
                action: function(dialogItself){
                    $('.responsechangepass').html('');
                    var $button = this;
                    $button.disable();     
                    var formURL = '../ajax.php?action=changeusername', formData = $('form#changeusername').serialize();  
                    if($('input[name=nusername]').val()!=undefined)
                    {
                        if($('input[name=nusername]').val().trim()!='')
                        {
                            if($('input[name=nusername]').val().trim().toLowerCase()!=$('input[name=username]').val())
                            {
                                if($('input[name=nusername]').val().trim().length > 4)
                                {
                                    $.ajax({
                                        url:'../ajax.php?action=checkusername',
                                        data:formData,
                                        type:'POST',
                                        success:function(data1)
                                        {
                                            var data1 = JSON.parse(data1);
                                            if(data1['st'])
                                            {
                                                BootstrapDialog.show({
                                                    title: 'Confirmation',
                                                    message: 'Are you sure you want to change your username?',
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
                                                                url:formURL,
                                                                data:formData,
                                                                type:'POST',
                                                                success:function(data)
                                                                {                                                                
                                                                    var data = JSON.parse(data);
                                                                    if(data['st'])
                                                                    {
                                                                        BootstrapDialog.closeAll();
                                                                        var dialog = new BootstrapDialog({
                                                                        message: function(dialogRef){
                                                                        var $message = $('<div>Username successfully changed, Logging out...</div>');                 
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
                                                                            window.location.href ='../index.php?action=logout';
                                                                        }, 1500);                                                                  
                                                                    }   
                                                                    else 
                                                                    {
                                                                        $('.responseusername').html('<div class="alert alert-danger alert-no-bot alertpad8">'+data['msg']+'</div>');
                                                                        $button.enable();
                                                                        dialogItself.close();
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
                                                $('.responseusername').html('<div class="alert alert-danger alert-no-bot alertpad8">'+data1['msg']+'</div>');
                                                $button.enable();                                      
                                            }
                                        }
                                    });
                                }
                                else 
                                {
                                    $('.responseusername').html('<div class="alert alert-danger alert-no-bot alertpad8">Usernames must be at least 5 characters long.</div>');
                                    $button.enable();
                                }
                            }
                            else 
                            {
                                $('.responseusername').html('<div class="alert alert-danger alert-no-bot alertpad8">Nothing to change.</div>');
                                $button.enable();
                            }
                        }
                        else 
                        {
                            $('.responseusername').html('<div class="alert alert-danger alert-no-bot alertpad8">Please input new username.</div>');
                            $button.enable();
                        }
                    }
                    else 
                    {
                        $button.enable();
                    }
                    $('input[name=nusername]').focus();


                }
            },{
                icon: 'glyphicon glyphicon-remove-sign',
                label: 'Close',
                action: function(dialogItself){
                    dialogItself.close();
                }
            }]
        });
    }

    function changepassword(id)
    {    
        BootstrapDialog.show({
            title: '<i class="fa fa-user"></i> Change Password',
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
                'pageToLoad': '../dialogs/changepassword.php?userid='+id
            },
            onshown: function(dialogRef){
            },
            buttons: [{
                icon: 'glyphicon glyphicon-ok-sign',
                cssClass: 'btn-primary subchange',
                hotkey: 13,
                label: 'Submit',
                action: function(dialogItself){
                    $('.responsechangepass').html('');
                    var $button = this;
                    var formURL = '../ajax.php?action=checkoldpass', formData = $('form#changepassword').serialize();  
                    var hasError = 0;
                    $('.reqfield').each(function(){
                        if($(this).val().trim()=='')
                        {
                            hasError=1;
                            return;
                        }
                    });

                    if(!hasError)
                    {
                        $.ajax({
                            url:formURL,
                            data:formData,
                            type:'POST',
                            success:function(data)
                            {
                                var data = JSON.parse(data);
                                if(data['st'])
                                {
                                    if($('input[name=opass]').val()!=$('input[name=npass]').val())
                                    {
                                        var re = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/;
                                        if(re.test($('input[name=npass]').val()))
                                        {
                                            if($('input[name=npass]').val()==$('input[name=rnpass]').val())
                                            {
                                               BootstrapDialog.show({
                                                    title: 'Confirmation',
                                                    message: 'Are you sure you want to change password?',
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
                                                                url:'../ajax.php?action=changepassword',
                                                                data:formData,
                                                                type:'POST',
                                                                success:function(data1)
                                                                {                                                                
                                                                    var data1 = JSON.parse(data1);
                                                                    if(data1['st'])
                                                                    {
                                                                        BootstrapDialog.closeAll();
                                                                        var dialog = new BootstrapDialog({
                                                                        message: function(dialogRef){
                                                                        var $message = $('<div>Password successfully changed. Logging out...</div>');                 
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
                                                                            window.location.href ='../index.php?action=logout';
                                                                        }, 1500);
                                                                    }
                                                                    else 
                                                                    {
                                                                        dialogItself.close();
                                                                        $('.responsechangepass').html('<div class="alert alert-danger alert-no-bot alertpad8">'+data1['msg']+'</div>');
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
                                                $('.responsechangepass').html('<div class="alert alert-danger alert-no-bot alertpad8">New Password don\'t match.</div>');
                                            }

                                        }
                                        else 
                                        {
                                            $('.responsechangepass').html('<div class="alert alert-danger alert-no-bot alertpad8">Password must contain at least 6 characters, including uppercase, lowercase and number.</div>');
                                        }
                                    }
                                    else 
                                    {
                                        $('.responsechangepass').html('<div class="alert alert-danger alert-no-bot alertpad8">Old password and new password must not be the same.</div>');
                                    }


                                }
                                else 
                                {
                                    $('.responsechangepass').html('<div class="alert alert-danger alert-no-bot alertpad8">'+data['msg']+'</div>');
                                }
                            }
                        });

                    }   
                    else 
                    {
                        $('.responsechangepass').html('<div class="alert alert-danger alert-no-bot alertpad8">Please fill in all fields</div>');
                    }           
                    // $.ajax({
                    //     url:formURL,
                    //     data:formData,
                    //     type:'POST',
                    //     success:function(data)
                    //     {
                    //         var data = JSON.parse(data);
                    //         if(data['st'])
                    //         {

                    //         }
                    //     }
                    // });
                }
            },{
                icon: 'glyphicon glyphicon-remove-sign',
                label: 'Close',
                action: function(dialogItself){
                    dialogItself.close();
                }
            }]
        })
    }