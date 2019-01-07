function eodstoreit()
{
    BootstrapDialog.show({
        title: '<i class="fa fa-user"></i></i> Re-enter your password',
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
            'pageToLoad': '../templates/it.php?page=itstoreeodconfirmation'
        },
        onshown: function(dialog) {
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Submit',
            cssClass: 'btn-primary',
            hotkey: 13,
            action:function(dialogItself){
                $('.responsepass').html('');
                dialogItself.enableButtons(false);
                dialogItself.setClosable(false);
                if($('input[name=password]').val()!=undefined)
                {
                    var formData = $('form#eodconfirm').serialize(), formURL = $('form#eodconfirm').attr('action');
                    if($('input[name=password]').val()!='')
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
                                    
                                    $.ajax({
                                        url:'../ajax.php?action=iteodstoreprocess',
                                        type:'POST',
                                        beforeSend:function(data1)
                                        {
                                            dialogItself.close();
                                            $('#processing-modal').modal('show');
                                            /*$('div.box-content').html('<i class="fa fa-cog fa-spin"></i>');*/
                                        },
                                        success:function(data1)
                                        {                           
                                            // setTimeout(function(){
                                                //$('#processing-modal').modal('hide');                       
                                            // },1000);

                                            console.log(data1);
                                            var data1 = JSON.parse(data1);
                                            if(data1['st'])
                                            {
                                                $('#processing-modal').modal('hide');
                                                swal({
                                                    title: "EOD Success",
                                                    type: "success",
                                                    showCancelButton: false,
                                                    confirmButtonColor: "#DD6B55",
                                                    confirmButtonText: "OK",
                                                    closeOnConfirm: true
                                                },
                                                function(isConfirm)
                                                {
                                                    if (isConfirm) 
                                                    {
                                                        window.location = '#/itstoreeod/'+data1['id'];  
                                                    }
                                                });                                                                                                                  
                                            }
                                            else 
                                            {
                                                $('#processing-modal').modal('hide');
                                                $('div.response').html('<div class="alert alert-danger">'+data1['msg']+'</div>');
                                            }

                                        }
                                    });         

                                }
                                else 
                                {
                                    $('.responsepass').html('<div class="alert alert-danger">'+data['msg']+'</div>');
                                }
                            }
                        });
                    }
                    else 
                    {
                        $('.responsepass').html('<div class="alert alert-danger">Please input password.</div>');
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

function lookupCustomerExternal()
{
    BootstrapDialog.show({
        title: 'Customer Lookup',
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
            'pageToLoad': '../dialogs/extenalgc.php?action=lookupcustomer'
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
            icon: 'glyphicon glyphicon-remove',
            label: 'Close',
            cssClass: 'btn-default',
            action:function(dialogItself){
                dialogItself.close();
            }
        }]

    });
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


function supplierGCList()
{
    alert('xxx');
}

function supplierGCItemList()
{

}

