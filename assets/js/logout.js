$(document).ready(function(){
	$('a#logout-modal').click(function(){
        BootstrapDialog.show({
        	title: 'Logout',
            message: 'Are you sure you want to log out?',
            closable: true,
            closeByBackdrop: false,
            closeByKeyboard: true,
            onshow: function(dialog) {
                // dialog.getButton('button-c').disable();
            },
            onshown:function(dialog){
                restrictback=0;
            },
            buttons: [{
                icon: 'glyphicon glyphicon-ok-sign',
                label: 'Yes, Please',
                cssClass: 'btn-primary',
                hotkey: 13,
                action:function(dialogItself){
                	window.location.href ='../index.php?action=logout';
                }
            }, {
            	icon: 'glyphicon glyphicon-remove-sign',
                label: 'No Thanks',
                action: function(dialogItself){
                    dialogItself.close();
                }
            }]
        });
	});

    checksession();

    // $('span._refreshpage').click(function(){
    //     location.reload();
    // });

    // $('.main').on('click','a.ajaxpages',function(event){
    //     event.preventDefault();
    //     var link = $(this).attr('href');
    //     $('div.main').load('../ajaxpages.php?page=promo-gc-request-list-pending').show();
    // });
    
    //alert(countInstances(path, '/'));

    crossroads.addRoute('/promo-request/',function(){        
        $('div.main').load('../ajaxpages.php?page=promo-gc-request-list-pending');
    });

    crossroads.addRoute('/promo-request/{reqId}',function(reqId){
        $('div.main').load('../ajaxpages.php?page=promo-gc-request-viewupdate&reqId='+reqId);
    });

    crossroads.addRoute('/promo-request-approved/',function(){
        $('div.main').load('../ajaxpages.php?page=promogc-request-approved-list');
    });

    crossroads.addRoute('/promo-request-approved/{redId}',function(reqId){
        $('div.main').load('../ajaxpages.php?page=promogc-request-approved-view&reqId='+reqId);
    });

    crossroads.addRoute('/promo-gc-released-list/',function(reqId){
        $('div.main').load('../ajaxpages.php?page=promo-gc-released-list');
    });

    crossroads.addRoute('/promo-gc-released-list/{relId}',function(relId){
        $('div.main').load('../ajaxpages.php?page=promo-gc-released-single&relid='+relId);
    });

    crossroads.addRoute('/reviewed-special-external-request/',function(){
        $('div.main').load('../ajaxpages.php?page=reviewed-special-external-request-list');
    });

    crossroads.addRoute('/reviewed-special-external-request/{reqId}',function(reqId){
        $('div.main').load('../ajaxpages.php?page=reviewed-special-external-request-single&reqId='+reqId);
    });

    crossroads.addRoute('/cancelled-special-external-request/',function(){
        $('div.main').load('../templates/special-external-gc.php?page=cancelled-special-external-request');           
    });

    crossroads.addRoute('/budget_ledger/',function(){
        $('div.main').load('../templates/ledger.php?page=ledger_budget');           
    });

    crossroads.addRoute('/release-gc-customer/',function(){
        $('div.main').load('../templates/regulargc.php?page=release-gc-customer');           
    });

    crossroads.addRoute('/eod/',function(){
        $('div.main').load('../templates/regulargc.php?page=eod');           
    });

    crossroads.addRoute('/setup-tres-customer/',function(){
        $('div.main').load('../templates/setup.php?page=setup-tres-customer');      
    });

    crossroads.addRoute('/setup-paymentfund/',function(){
        $('div.main').load('../templates/setup.php?page=setup-paymentfund');      
    });

    // special external GC
    crossroads.addRoute('/request-external-gc/',function(){
        $('div.main').load('../templates/special-external-gc.php?page=request-special-gc');   
    });    

    crossroads.addRoute('/request-external-gcwitholder/',function(){
        $('div.main').load('../templates/special-external-gc.php?page=request-special-gcwithholder');   
    });

    crossroads.addRoute('/special-external-request/',function(){
        $('div.main').load('../templates/special-external-gc.php?page=special-external-request-list');
    });

    crossroads.addRoute('/special-external-request/{redId}',function(reqId){
        $('div.main').load('../templates/special-external-gc.php?page=special-external-request-approve-update&reqId='+reqId);
    });

    crossroads.addRoute('/special-external-request-approved/',function(){
        $('div.main').load('../templates/special-external-gc.php?page=special-external-request-approved-list');
    });

    crossroads.addRoute('/special-external-request-approved/{reqId}',function(reqId){
        $('div.main').load('../templates/special-external-gc.php?page=special-external-request-approved-single&reqId='+reqId);
    });

    crossroads.addRoute('/special-external-gc-reviewed/',function(){
        $('div.main').load('../templates/special-external-gc.php?page=special-external-gc-reviewed');
    });

    crossroads.addRoute('/special-external-gc-reviewed/{reqid}',function(reqid){
        $('div.main').load('../templates/special-external-gc.php?page=special-external-gc-reviewed-single&reqid='+reqid);
    });

    crossroads.addRoute('/released-special-external-request/',function(){
        $('div.main').load('../templates/special-external-gc.php?page=view-released-special-gc');           
    });

    crossroads.addRoute('/released-special-external-request/{reqid}',function(reqid){
        $('div.main').load('../templates/special-external-gc.php?page=view-released-special-single&reqid='+reqid);           
    });

    // end of special external GC route

    crossroads.addRoute('/institution-gc-sales/',function(){
        $('div.main').load('../templates/regulargc.php?page=institution-gc-sales');   
    });

    crossroads.addRoute('/promogcrequest/',function(){
        $('div.main').load('../templates/promo-gc.php?page=promogcrequest');   
    });

    crossroads.addRoute('/addnewpromo/',function(){
        $('div.main').load('../templates/promo-gc.php?page=addnewpromo');   
    });
    
    crossroads.addRoute('/promolist/',function(){
        $('div.main').load('../templates/promo-gc.php?page=promolist');   
    });

    crossroads.addRoute('/releasedpromogc/',function(){
        $('div.main').load('../templates/promo-gc.php?page=releasedpromogc');   
    });

    crossroads.addRoute('/promogcstatus/',function(){
        $('div.main').load('../templates/promo-gc.php?page=promogcstatus');   
    });

    crossroads.addRoute('/gctransferList/',function(){
        $('div.main').load('../templates/regulargc.php?page=gctransfer&active=list');   
    });

    crossroads.addRoute('/gctransferList/{reqid}',function(reqid){
        $('div.main').load('../templates/regulargc.php?page=gctransfer-pending&reqid='+reqid);   
    });

    crossroads.addRoute('/gcLost/',function(){
        $('div.main').load('../templates/regulargc.php?page=gclost');   
    });

    crossroads.addRoute('/gctransfeRequest/',function(){
        $('div.main').load('../templates/regulargc.php?page=gctransfer&active=create');   
    });

    crossroads.addRoute('/transfer-served/{reqid}',function(reqid){
        $('div.main').load('../templates/regulargc.php?page=servedGCTransfer&reqid='+reqid);    
    });

    crossroads.addRoute('/transfer-served-view/{reqid}',function(reqid){
        $('div.main').load('../templates/regulargc.php?page=servedGCTransferView&reqid='+reqid);    
    });

    crossroads.addRoute('/transfer-view/{reqid}',function(reqid){
        $('div.main').load('../templates/regulargc.php?page=transferview&reqid='+reqid);    
    });

    crossroads.addRoute('/transfer-receiving/{reqid}',function(reqid){
        $('div.main').load('../templates/regulargc.php?page=transfereceving&reqid='+reqid);    
    });

    crossroads.addRoute('/reviewed-gc-for-releasing/',function(){
        $('div.main').load('../templates/special-external-gc.php?page=reviewed-gc-for-releasing');    
    });

    crossroads.addRoute('/reviewed-gc-for-releasing/{reqid}',function(reqid){
        $('div.main').load('../templates/special-external-gc.php?page=reviewedgc&reqid='+reqid);    
    });

    crossroads.addRoute('/pending-production-request-list/',function(){
        $('div.main').load('../templates/regulargc.php?page=pending-production-request-list');    
    });

    crossroads.addRoute('/pending-production-request-list/{reqid}',function(reqid){
        $('div.main').load('../templates/regulargc.php?page=pending-production-request&reqid='+reqid);    
    });
    
    crossroads.addRoute('/approved-production-request-list/',function(){
        $('div.main').load('../templates/regulargc.php?page=approved-production-request-list');    
    });

    crossroads.addRoute('/cancelled-production-request-list/',function(){
        $('div.main').load('../templates/regulargc.php?page=cancelled-production-request-list');    
    });    

    crossroads.addRoute('/ledger-promo/',function(){
        $('div.main').load('../templates/promo-gc.php?page=promoledger');  
    });  

    crossroads.addRoute('/suppliergc/',function(){
        $('div.main').load('../templates/suppliergc.php?page=supplierverification');  
    });      

    crossroads.addRoute('/sgcitemsetup/',function(){
        $('div.main').load('../templates/suppliergc.php?page=sgcitemsetup');  
    });

    crossroads.addRoute('/sgccompanysetup/',function(){
        $('div.main').load('../templates/suppliergc.php?page=sgccompanysetup');  
    });

    crossroads.addRoute('/treasury-audit/',function(){
        $('div.main').load('../templates/regulargc.php?page=treasuryAudit');  
    });

    crossroads.addRoute('/refund-institution-gc/',function(){
        $('div.main').load('../templates/regulargc.php?page=refundInstitutionGC');  
    });    

    crossroads.addRoute('/barcodechecker/',function(){
        $('div.main').load('../templates/regulargc.php?page=barcodechecker');  
    });  
    
    crossroads.addRoute('/specialgcpayment/',function(){
        $('div.main').load('../templates/special-external-gc.php?page=specialgcpayment');  
    }); 

    crossroads.addRoute('/special-external-gcholderentrylist/',function(){
        $('div.main').load('../templates/special-external-gc.php?page=specialexternalgcholderentrylist');  
    });   

    crossroads.addRoute('/specialexternalgcholderentry/{reqid}',function(reqid){
        $('div.main').load('../templates/special-external-gc.php?page=specialexternalgcholderentry&reqid='+reqid);  
    });    

    crossroads.addRoute('/itstoreeod/',function(){
        $('div.main').load('../templates/it.php?page=itstoreeod');  
    });

    crossroads.addRoute('/itstoreeod/{reqid}',function(reqid){
        $('div.main').load('../templates/it.php?page=itstoreeodres&id='+reqid);  
    });

    crossroads.addRoute('/verifiedgcnotranx/',function(){
        $('div.main').load('../templates/admin.php?page=verifiedgcnotranx');  
    });   

    crossroads.addRoute('/saleslisttreasury/',function(){
        $('div.main').load('../templates/cfs.php?page=saleslisttreasury');  
    });   

    crossroads.addRoute('/viewtressales/{trid}/{pag}',function(trid,pag){
        $('div.main').load('../templates/cfs.php?page=viewtressales&trid='+trid+'&pag='+pag);  
    }); 

    crossroads.addRoute('/viewgctransact/{barcode}/{trid}/{url}/{pag}',function(barcode,trid,url,pag){
        $('div.main').load('../templates/cfs.php?page=viewgctransact&barcode='+barcode+'&trid='+trid+'&url='+url+'&pag='+pag);  
    }); 

    crossroads.addRoute('/viewgctransact/{barcode}/{trid}/{url}/{pag}',function(barcode,trid,url,pag){
        $('div.main').load('../templates/cfs.php?page=viewgctransact&barcode='+barcode+'&trid='+trid+'&url='+url+'&pag='+pag);  
    }); 

    crossroads.addRoute('/viewstoresales/',function(){
        $('div.main').load('../templates/cfs.php?page=viewstoresales');  
    }); 

    crossroads.addRoute('/viewgcsalespertransac/{trid}/{pag}',function(trid,pag){
        $('div.main').load('../templates/cfs.php?page=viewgcsalespertransac&trid='+trid+'&pag='+pag);  
    });

    crossroads.addRoute('/verifiedgcperstore/{id}/{pag}',function(id,pag){
        $('div.main').load('../templates/cfs.php?page=verifiedgcperstore&id='+id+'&pag='+pag);  
    });
    
    crossroads.addRoute('/exportdata/',function(){
        $('div.main').load('../templates/cfs.php?page=exportdata');  
    });

    crossroads.addRoute('/verifygcmanual/',function(){
        $('div.main').load('../templates/admin.php?page=verifygcmanual');  
    });

    crossroads.addRoute('/createtextfile/',function(){
        $('div.main').load('../templates/admin.php?page=createtextfile');  
    });  

    crossroads.addRoute('/eodtextfilecheck/',function(){
        $('div.main').load('../templates/admin.php?page=eodtextfilecheck');  
    });  

    crossroads.addRoute('/verifiedgcreport/',function(){
        $('div.main').load('../templates/regulargc.php?page=verifiedgcreport');  
    }); 

    crossroads.addRoute('/setup-special-external/',function(){
        $('div.main').load('../templates/setup.php?page=setup-special-external');      
    });

    crossroads.addRoute('/soldgcreportexcel/',function(){
        $('div.main').load('../templates/reports.php?page=soldgcreportexcel');      
    });

    crossroads.addRoute('/verifiedgcreportexcel/',function(){
        $('div.main').load('../templates/reports.php?page=verifiedgcreportexcel');      
    });

    crossroads.addRoute('/eod-list/',function(){
        $('div.main').load('../templates/regulargc.php?page=eodlist');      
    });

    crossroads.addRoute('/viewverifiedgc/',function(){
        $('div.main').load('../templates/admin.php?page=viewverifiedgc');      
    });

    crossroads.addRoute('/beamandgoconversion/',function(){
        $('div.main').load('../templates/beamandgo.php?page=beamandgoconversion');      
    });

    crossroads.addRoute('/specialex_reports/',function(){
        $('div.main').load('../templates/special-external-gc.php?page=specialexreports');      
    });

    // crossroads.addRoute('/promo-gc-approved/',function(){
    //     $('div.main').load('../templates/promo-gc.php?page=view-approved-promo-gc');           
    // });

    // crossroads.addRoute('/reviewed-special-external-request/',function(){
    //     alert('x');
    //     $('div.main').load('../ajaxpages.php?page=special-external-request-approved-list');
    // });

    crossroads.bypassed.add(function(request){

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
});

function cutUrl(){
    // var url = document.URL;
    // alert(url);
    // var to = url.lastIndexOf('/') +1;
    // alert(to);
    // x =  url.substring(0,to);
    // window.location.replace(x); 
    var url = window.location.href;
    var n = 5;
    var newurl = url.split('/').slice(0,n).join('/');
    window.location.replace(newurl); 
}


function countInstances(string, word) 
{
   var substrings = string.split(word);
   return substrings.length - 1;
}

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

function checksession()
{
    setInterval(function() {
        $.ajax({
            url:'../ajax.php?action=checksession',
            success:function(data)
            {
                var data = JSON.parse(data);
                if(!data['st'])
                {
                    BootstrapDialog.closeAll();
                    var dialog = new BootstrapDialog({
                    message: function(dialogRef){
                    var $message = $('<div>Session already expired, Logging out...</div>');                 
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
                    //$('.responsechangepass').html('<div class="alert alert-danger alert-no-bot alertpad8">'+data['msg']+'</div>');
                }
            }
        });

    },60000); // 60000 milliseconds = one minute
}
