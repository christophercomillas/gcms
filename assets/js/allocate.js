$(document).ready(function(){
	$('.denfield').inputmask();

    disable_qty();
	$('form#allocateForm, form#adjallocateForm').on('change','#store-selected',function(){
        disable_qty();
         restoreValidatedGC();
        $('.qty_a').each(function(){
            $(this).val(0);                
        });
		var store = $(this).val(), gctype = $('#gctype').val();
         if(store!=''){
            //enable_qty();            
            changestore(store,gctype);
        } 
        else 
        {
           $('.storesele').html('');
           disable_qty();
        }
	});

    function disable_qty()
    {
        $('.qty_a').each(function()
        {
            $(this).prop('disabled',true);               
        });
    }

    function restoreValidatedGC()
    {
        // alert('gc');
        // var n1 = $('input#n^').val();
        // $('span#n1').text(n1);
        $('input[id^=nx]').each(function(){
            var res = $(this).attr('id').split('x');
            $('span#n'+res[1]).text($(this).val());
            //$('span#n'+res[1]).text($(this).val());
        });

    }

    function enable_qty()
    {
        $('.qty_a').each(function()
        {
            $(this).prop('disabled',false);               
        });
    }

    $('.form-container').on('submit','form#adjallocateForm',function(){

        return false;
    });	

	$('.form-container').on('submit','form#allocateForm',function()
    {
        $('.response').html('');       
		var formUrl = $(this).attr('action'), formData = $(this).serialize();
        var hasqty = false;
        var qtyx = 0;
        $('.qty_a').each(function(){
            var text_value=$(this).val();
             if(text_value!='0' && text_value!='')
            {
                hasqty = true;
                return false;
            }
        });
        if($('#store-selected').val()!='' &&
            $('#gctype').val()!=''
            )
        {
            if(!hasqty)
            {
                $('.response').html('<div class="alert alert-danger" id="danger-x">Please input at least 1 quantity field.</div>');
            }
            else 
            {
                $("button#btn").prop("disabled",true);
                BootstrapDialog.show({
                    title: 'Confirmation',
                    message: 'Are you sure you want to allocate gc?',
                    closable: true,
                    closeByBackdrop: false,
                    closeByKeyboard: true,
                    onshown: function(dialog) {
                        // dialog.getButton('button-c').disable();
                    },
                    onhidden: function() {
                        $("button#btn").prop("disabled",false);
                    },
                    buttons: [{
                        icon: 'glyphicon glyphicon-ok-sign',
                        label: 'Yes',
                        cssClass: 'btn-primary',
                        hotkey: 13,
                        action:function(dialogItself){
                            $buttons = this;
                            $buttons.disable();                  
                            BootstrapDialog.closeAll();
                            $.ajax({
                                url:formUrl,
                                type:'POST',
                                data:formData,
                                beforeSend:function(){
                                    $('#processing-modal').modal('show');
                                },
                                success:function(response){
                                    $('#processing-modal').modal('hide'); 
                                    console.log(response);
                                    var res = response.trim();
                                    
                                    if(res=='success'){                        
                                        var dialog = new BootstrapDialog({
                                        message: function(dialogRef){
                                        var $message = $('<div>GC Successfully Allocated.</div>');                  
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

                                    }else {                 
                                        $('.response').html('<div class="alert alert-danger" id="danger-x">'+response+'</div>');
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
        else
        {
            $('.response').html('<div class="alert alert-danger" id="danger-x">Please select retail store and GC Type</div>');
            timeoutmsg();
        }

		return false;
	});

    $(function(){
        $("[id^=num]").keyup(function(){
            var hasNegative = false;
            $('.denfield').each(function(){
                var numx = $(this).val();
                numx = numx.replace(/,/g , "");
                var numid = $(this).attr('id').slice(3);
                var nhid = $("#nx"+numid).val();
                if(Number(numx) > Number(nhid))
                {
                    hasNegative = true;
                }

                $("span[id=n"+numid+"]").text(nhid - numx);
            });

            if(hasNegative)
            {
                $("#btn").attr("disabled",true);
            }
            else 
            {
                $("#btn").attr("disabled",false);
            }

            // var num1 = $("[id='num1']").val();
            // num1 = num1.replace(/,/g , "");
            // var num2 = $("[id='num2']").val();
            // num2 = num2.replace(/,/g , "");
            // var num3 = $("[id='num3']").val();
            // num3 = num3.replace(/,/g , "");
            // var num4 = $("[id='num4']").val();
            // num4 = num4.replace(/,/g , "");
            // var num5 = $("[id='num5']").val();
            // num5 = num5.replace(/,/g , "");
            // var num6 = $("[id='num6']").val();
            // num6 = num6.replace(/,/g , "");
            // var aa = $("[id='nx1']").val();
            // var bb = $("[id='nx2']").val();
            // var cc = $("[id='nx3']").val();
            // var dd = $("[id='nx4']").val();
            // var ee = $("[id='nx5']").val();      
            // var ff = $("[id='nx6']").val();      
            // var sum1 = Number(num1);
            // var sum2 = Number(num2);
            // var sum3 = Number(num3);
            // var sum4 = Number(num4);
            // var sum5 = Number(num5);
            // var sum6 = Number(num6);
            // if(sum1 > aa || sum2 > bb || sum3 > cc || sum4 > dd || sum5 > ee || sum6 > ff){
            //         $("#btn").attr("disabled",true);
            // }
            // else{
            //     $("#btn").attr("disabled", false);
            // }
            // var d = aa - sum1;
            // var e = bb - sum2;
            // var f = cc - sum3;
            // var g = dd - sum4;
            // var h = ee - sum5;
            // var i = ff - sum6;
            // $("span[id=n1]").text(d);
            // $("span[id=n2]").text(e);
            // $("span[id=n3]").text(f);
            // $("span[id=n4]").text(g);
            // $("span[id=n5]").text(h);
            // $("span[id=n6]").text(i);
        });
    });
    
    // $('.box').on('click','button#view-allocated-gc',function(){
    //     var id = $(this).attr('storeid');
    //     BootstrapDialog.show({
    //         title: 'Allocated GC',
    //         message: $('<div></div>').load('../dialogs/view-allocated-gc.php?id='+id),
    //         cssClass: 'modal-allocated-gc',
    //         closable: true,
    //         closeByBackdrop: false,
    //         closeByKeyboard: true,
    //         onshow: function(dialog) {
    //             // dialog.getButton('button-c').disable();
    //         },
    //         onshown: function(dialogRef){
    //                 $('#allocated-gc').dataTable({
    //                     "pagingType": "full_numbers",
    //                     "ordering": false,
    //                     "processing": true
    //                 });

    //                 $("#allocated-gc_length").css("display", "none");
    //         },
    //         buttons:[ {
    //             icon: 'glyphicon glyphicon-remove-sign',
    //             label: 'Close',
    //             action: function(dialogItself){
    //                 dialogItself.close();
    //             }
    //         }]
    //     });
    // });

    function timeoutmsg(){
        setTimeout(function(){
            $('.response').html('');
        }, 4000);
    }
});

function showAllocatedGC(store,gctype)
{
    BootstrapDialog.show({
        title: 'Allocated GC',
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
            'pageToLoad': '../dialogs/view-allocated-gc.php?store='+store+'&gctype='+gctype
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

function changeGCType(gctype)
{
    var store = $('#store-selected').val();
    if(store!='')
    {
        changestore(store,gctype);
    }
}

function changestore(store,gctype)
{
    $.ajax({
        url:'../ajax.php?action=checkStoreForAllocate',
        type:'POST',
        data:{store:store,gctype:gctype},
        beforeSend:function(){
            $('.storesele').html("<img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
        },
        success:function(response){
            setTimeout(function(){
                $('.storesele').html(response);
                setTimeout(function(){
                    $('.qty_a').each(function()
                    {
                        $(this).prop('disabled',false);               
                    });
                },100);
            },1000);
            
        }
    });
}