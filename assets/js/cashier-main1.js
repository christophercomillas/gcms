flag=0;
confirm = 0;
marketing=0;    
mode=0;
scan=0;
payment = 0;
reval=0;
customer=0;
search =0;
group=0;
modemanager=0;
servicecharge = 0;
refund = 0;
currf=""

checksession();

if(getUrlVars()['gcoftheday']!=undefined)
{
  var rep = getUrlVars()['gcoftheday'];
  BootstrapDialog.show({
      title: 'GC of the Day Report',
      closable: true,
      closeByBackdrop: false,
      closeByKeyboard: true,
      message: function(dialog) {
        var $message = $("<div><img src='../assets/images/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
        var pageToLoad = dialog.getData('pageToLoad');
      setTimeout(function(){
        $message.load(pageToLoad); 
      },1000);
      return $message;
      },
      data: {
        'pageToLoad': '../dialogcashier/posreports.php?type=gcoftheday&id='+rep,
      },
      cssClass: 'pdfshow',           
      onshown: function(dialogRef){                   
      },
      onhidden: function(dialogRef){ 
        var url = document.URL;
        var to = url.lastIndexOf('/') +1;
        x =  url.substring(0,to);
        window.location.replace(x);

      },
      buttons: [{
        icon: 'glyphicon glyphicon-print',
        label: ' Print',
        cssClass: 'btn-default printbut',
        action: function(dialogItself){
          callPrint('iframeId');
        }
      },{
        icon: 'glyphicon glyphicon-remove-sign',
        label: ' Close',
        cssClass: 'btn-default',
        action: function(dialogItself){
        var url = document.URL;
        var to = url.lastIndexOf('/') +1;
        x =  url.substring(0,to);
        window.location.replace(x); 
          // dialogItself.close();
          // window.location = '../cashiering';
        }
      }]
  });  

}

if(getUrlVars()['gcreport']!=undefined)
{
  var rep = getUrlVars()['gcreport'];
  BootstrapDialog.show({
      title: 'GC Items Report',
      closable: true,
      closeByBackdrop: false,
      closeByKeyboard: true,
      message: function(dialog) {
        var $message = $("<div><img src='../assets/images/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
        var pageToLoad = dialog.getData('pageToLoad');
      setTimeout(function(){
        $message.load(pageToLoad); 
      },1000);
      return $message;
      },
      data: {
        'pageToLoad': '../dialogcashier/gcreportpdf.php',
      },
      cssClass: 'pdfshow',           
      onshown: function(dialogRef){                   
      },
      onhidden: function(dialogRef){ 
        var url = document.URL;
        var to = url.lastIndexOf('/') +1;
        x =  url.substring(0,to);
        window.location.replace(x);

      },
      buttons: [{
        icon: 'glyphicon glyphicon-print',
        label: ' Print',
        cssClass: 'btn-default printbut',
        action: function(dialogItself){
          callPrint('iframeId');
        }
      },{
        icon: 'glyphicon glyphicon-remove-sign',
        label: ' Close',
        cssClass: 'btn-default',
        action: function(dialogItself){
        var url = document.URL;
        var to = url.lastIndexOf('/') +1;
        x =  url.substring(0,to);
        window.location.replace(x); 
          // dialogItself.close();
          // window.location = '../cashiering';
        }
      }]
  });  

}

if(getUrlVars()['posreport']!=undefined)
{
  var rep = getUrlVars()['posreport'];
  BootstrapDialog.show({
      title: 'POS Report',
      closable: true,
      closeByBackdrop: false,
      closeByKeyboard: true,
      message: function(dialog) {
        var $message = $("<div><img src='../assets/images/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
        var pageToLoad = dialog.getData('pageToLoad');
      setTimeout(function(){
        $message.load(pageToLoad); 
      },1000);
      return $message;
      },
      data: {
        'pageToLoad': '../dialogcashier/posreportpdf.php',
      },
      cssClass: 'pdfshow',           
      onshown: function(dialogRef){                   
      },
      onhidden: function(dialogRef){ 
        var url = document.URL;
        var to = url.lastIndexOf('/') +1;
        x =  url.substring(0,to);
        window.location.replace(x);                 
      },
      buttons: [{
        icon: 'glyphicon glyphicon-print',
        label: ' Print',
        cssClass: 'btn-default printbut',
        action: function(dialogItself){
          callPrint('iframeId');
        }
      },{
        icon: 'glyphicon glyphicon-remove-sign',
        label: ' Close',
        cssClass: 'btn-default',
        action: function(dialogItself){
        var url = document.URL;
        var to = url.lastIndexOf('/') +1;
        x =  url.substring(0,to);
        window.location.replace(x); 
          // dialogItself.close();
          // window.location = '../cashiering';
        }
      }]
  });         
}

if(getUrlVars()['eosreport']!=undefined)
{
  var rep = getUrlVars()['eosreport'];
  BootstrapDialog.show({
      title: 'End of Shift Report',
      closable: true,
      closeByBackdrop: false,
      closeByKeyboard: true,
      message: function(dialog) {
        var $message = $("<div><img src='../assets/images/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
        var pageToLoad = dialog.getData('pageToLoad');
      setTimeout(function(){
        $message.load(pageToLoad); 
      },1000);
      return $message;
      },
      data: {
        'pageToLoad': '../dialogcashier/eosreportpdf.php',
      },
      cssClass: 'pdfshow',           
      onshown: function(dialogRef){                   
      },
      onhidden: function(dialogRef){                 
      },
      buttons: [{
        icon: 'glyphicon glyphicon-print',
        label: ' Print',
        cssClass: 'btn-default printbut',
        action: function(dialogItself){
          callPrint('iframeId');
        }
      },{
        icon: 'glyphicon glyphicon-remove-sign',
        label: ' Close',
        cssClass: 'btn-default',
        action: function(dialogItself){
        var url = document.URL;
        var to = url.lastIndexOf('/') +1;
        x =  url.substring(0,to);
        window.location.replace(x); 
          // dialogItself.close();
          // window.location = '../cashiering';

        }
      }]
  });
}

if(getUrlVars()['eodreport']!=undefined)
{
  var rep = getUrlVars()['eodreport'];
  BootstrapDialog.show({
      title: 'End of day Report',
      closable: true,
      closeByBackdrop: false,
      closeByKeyboard: true,
      message: function(dialog) {
        var $message = $("<div><img src='../assets/images/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
        var pageToLoad = dialog.getData('pageToLoad');
      setTimeout(function(){
        $message.load(pageToLoad); 
      },1000);
      return $message;
      },
      data: {
        'pageToLoad': '../dialogcashier/eodreportpdf.php',
      },
      cssClass: 'pdfshow',           
      onshown: function(dialogRef){                   
      },
      onhidden: function(dialogRef){   
        var url = document.URL;
        var to = url.lastIndexOf('/') +1;
        x =  url.substring(0,to);
        window.location.replace(x);        
      },
      buttons: [{
        icon: 'glyphicon glyphicon-print',
        label: ' Print',
        cssClass: 'btn-default printbut',
        action: function(dialogItself){
          callPrint('iframeId');
        }
      },{
        icon: 'glyphicon glyphicon-remove-sign',
        label: ' Close',
        cssClass: 'btn-default',
        action: function(dialogItself){
        var url = document.URL;
        var to = url.lastIndexOf('/') +1;
        x =  url.substring(0,to);
        window.location.replace(x); 
          // dialogItself.close();
          // window.location = '../cashiering';

        }
      }]
  });
}

if(getUrlVars()['shortageoveragereport']!=undefined)
{
  var rep = getUrlVars()['shortageoveragereport'];
  BootstrapDialog.show({
      title: '',
      closable: true,
      closeByBackdrop: false,
      closeByKeyboard: true,
      message: function(dialog) {
        var $message = $("<div><img src='../assets/images/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
        var pageToLoad = dialog.getData('pageToLoad');
      setTimeout(function(){
        $message.load(pageToLoad); 
      },1000);
      return $message;
      },
      data: {
        'pageToLoad': '../dialogcashier/shortageoveragedialog.php',
      },
      cssClass: 'pdfshow',           
      onshown: function(dialogRef){                   
      },
      onhidden: function(dialogRef){ 
        var url = document.URL;
        var to = url.lastIndexOf('/') +1;
        x =  url.substring(0,to);
        window.location.replace(x);

      },
      buttons: [{
        icon: 'glyphicon glyphicon-print',
        label: ' Print',
        cssClass: 'btn-default printbut',
        action: function(dialogItself){
          callPrint('iframeId');
        }
      },{
        icon: 'glyphicon glyphicon-remove-sign',
        label: ' Close',
        cssClass: 'btn-default',
        action: function(dialogItself){
        var url = document.URL;
        var to = url.lastIndexOf('/') +1;
        x =  url.substring(0,to);
        window.location.replace(x); 
          // dialogItself.close();
          // window.location = '../cashiering';
        }
      }]
  });  

}

$(function(){

  idleTime = 0;

  //Increment the idle time counter every second.
  // var idleInterval = setInterval(timerIncrement, 40000);

  // function timerIncrement()
  // {
  //   $.ajax({
  //     url:'../ajax-cashier.php?request=checksession',
  //     success:function(data)
  //     {
  //       var data = JSON.parse(data);
  //       if(data['st'])
  //       {
  //         window.location.href ='login.php';
  //       }
  //     }
  //   });
  // }

  //Zero the idle timer on mouse movement.
  $(this).mousemove(function(e){
    idleTime = 0;
  });

  $('table tbody._barcodes tr td button').blur(
      function(){
         $(this).closest('tr').css('background-color','white');
    }).focus(function() {
    $(this).closest('tr').css('background-color','yellow');
  });

  $('table tbody._barcodesreval tr td button').blur(
      function(){
         $(this).closest('tr').css('background-color','white');
    }).focus(function() {
    $(this).closest('tr').css('background-color','yellow');
  });

	$('#numOnly, #numOnlyreval').inputmask();
    $("[name='data']").on('keypress', function (event) {
      if(event.which === 13){
        $('input.msgsales').val("");
        var value = this.value;
        $.ajax({
          type : "POST",
          url  : "../ajax-cashier.php?request=check",
          data : { value : value },
          beforeSend:function(){          
          },
          success : function(data){
            var data = JSON.parse(data);
            if(data['st'])
            {
              $("[name='data']").select();       
              $('div.items .receipt-items').load('../ajax-cashier.php?request=receipt');    
              $('._barcodes').load('../ajax-cashier.php?request=load');              
              $.ajax({
                type:"POST",
                url:"../ajax-cashier.php?request=totals",
                success:function(data)
                {
                  console.log(data);
                  var data = JSON.parse(data);
                  $('.sbtotal').val(data['sbtotal']);
                  $('._cashier_total').val(data['amtdue']);
                  $('.linediscount').val(data['linedisc']);
                  $('.docdiscount').val(data['docdiscount']);
                  $('.noitems').val(data['noitems']); 
                  $('.cdisc').val('0.00');
                }
              });
            }
            else 
            {
              flag=1;
              BootstrapDialog.show({
                  title:'Warning',
                  message: '<div class="dialog-alert">'+data['msg']+'</div>',
                  onhidden: function(dialogRef){ 
                    flag = 0;                
                    $("[name='data']").focus();         
                  }
              });
              $("[name='data']").val('');
            }
          } 
        });  
       }
    });

    $("[name='revalidategc']").on('keypress', function (event) {   
       if(event.which === 13){        
          $('.msgreval').val('');
          var value = this.value;
          $.ajax({
            type : "POST",
            url  : "../ajax-cashier.php?request=scanrevalidate",
            data : { value : value },
            beforeSend:function(){          
            },
            success:function(data){
              console.log(data)
              var data = JSON.parse(data);
              if(data['st'])
              {
                $("[name='revalidategc']").val('');
                $('._barcodesreval').load('../ajax-cashier.php?request=loadreval');
                $('input.inp-amtdue._cashier_totalreval').val(data['total']);
                $('input.noitemsreval').val(data['count']);
              }
              else 
              {
                flag=1;
                BootstrapDialog.show({
                    title:'Warning',
                    message: data['msg'],
                    onhidden: function(dialogRef){ 
                      flag = 0;                
                      $("[name='revalidategc']").focus();         
                    }
                });
                $("[name='revalidategc']").val('');
              }
            }
          });
       }

    });

    $("[name='inprefundgc']").on('keypress', function (event) {
      if(event.which === 13)
      {        
        $('.msgrefund').val('');
        var value = this.value;
        $.ajax({
            type : "POST",
            url  : "../ajax-cashier.php?request=scanrefund",
            data : { value : value },
            beforeSend:function(){          
            },
            success:function(data){
              console.log(data)
              var data = JSON.parse(data);
              if(data['st'])
              {                
                $("[name='inprefundgc']").val('');
                $('._barcodesrefund').load('../ajax-cashier.php?request=loadrefund');
                $('input.totdenomref').val(addCommas(data['reftotdenom']));
                $('input.totsubdiscref').val(data['refsub']);
                $('input.totlinedisref').val(data['refline']);
                $('input.noitemsref').val(data['refcnt']);
                $('input.serviceref').val(data['scharge']);
                
                $('input._cashier_totalrefund').val(addCommas(data['refamtdue'].toFixed(2)));
                //$('input.inp-amtdue._cashier_totalreval').val(data['total']);
                // $('input.noitemsreval').val(data['count']);
              }
              else 
              {
                flag=1;
                BootstrapDialog.show({
                    title:'Warning',
                    message: data['msg'],
                    onhidden: function(dialogRef){ 
                      flag = 0;                
                      $("[name='inprefundgc']").focus();         
                    }
                });
                $("[name='inprefundgc']").val('');
              }
            }
        });
      }
    });
});

function init() {
  // var flag=0;
  shortcut.add("DOWN",function() {
    if ($("div.item-list table.tablef tr td button.btnside").is(":focus") || $("div.item-list table tbody._barcodesreval tr td button.btnside").is(":focus")) 
    {
      c=""
      c = currf.closest('tr').next().find('button.btnside:eq(' + currf.index() + ')');
      // If we didn't hit a boundary, update the current cell
      if (c.length > 0) {
          currf = c;
          currf.focus();
      }
    }


  });

  shortcut.add("UP",function() {
    if ($("div.item-list table.tablef tr td button.btnside").is(":focus") || $("div.item-list table tbody._barcodesreval tr td button.btnside").is(":focus")) 
    {
      c=""
      c = currf.closest('tr').prev().find('button.btnside:eq(' + currf.index() + ')');
      // If we didn't hit a boundary, update the current cell
      if (c.length > 0) {
          currf = c;
          currf.focus();
      }
    }    
  });

  shortcut.add("ESC",function() {
    if ($("div.item-list table.tablef tr td button.btnside").is(":focus")) 
    {
      $('input#numOnly').focus();
    }

    if($("div.item-list table tbody._barcodesreval tr td button.btnside").is(":focus"))
    {
      $('input#numOnlyreval').focus();
    }

    if($("div.item-list table tbody._barcodesrefund tr td button.btnside").is(":focus"))
    {
      $('input#numOnlyreturn').focus();
    }

  });
  shortcut.add("F1",function() {
    f1();       
  });
  shortcut.add("F2",function() {
    f2();
  });
  shortcut.add("F3",function() {
    f3(); 
  });
  shortcut.add("F4",function() {
    f4();
  });
  shortcut.add("F5",function() {
    f5();
  });
  shortcut.add("F6",function() {
    f6();
  });
  shortcut.add("F7",function() {
    f7();
  });
  shortcut.add("F8",function() {
    f8();
  });
  shortcut.add("F9",function() {
    var win = window.open('', '_self');
    win.close();
  });
  shortcut.add("F10",function() {
    var time = $('span#time').text();
    alert(time);
  });
}

window.onload=init; 

function cashrevalpayment()
{
  var revpayment = parseFloat($('#payment').val());
  if(revpayment>0)
  {
    if(reval==0)
    {
      var cashpaymentype=2;
      reval=1;
      BootstrapDialog.show({
        title: 'Revalidation Payment',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: true,
        cssClass: 'cash-payment',
        message: function(dialog) {
          var $message = $("<div></div>");
          var pageToLoad = dialog.getData('pageToLoad');
          $message.load(pageToLoad);
          return $message;
        },
        data: {
          'pageToLoad': '../dialogcashier/revalidationpayment.php',
        },
        onshow: function(dialog) {
        // dialog.getButton('button-c').disable();
        },
        onshown: function(dialog){
          // setTimeout(function(){                    
          //   $('#paymentcash').focus();                    
          //   $('#paymentcash').inputmask("integer", { allowMinus: false,autoGroup: true, groupSeparator: ",", groupSize: 3 }); 
          //   var amt = $('#payment').val();
          //   $('.amtdue').val(amt);                    
          // }, 400);
        },
        onhidden: function(dialog){
          $('#rbarcode').select();
          reval=0;  
        },  
        buttons: [{
            icon: 'glyphicon glyphicon-ok ',
            label: ' Submit',
            cssClass: 'btn-success',
            hotkey: 13, 
            action: function(dialogRef) {
              var $buttons  = this;
              $buttons.disable();
              var cash = parseFloat($('#paymentcash').val()), amtdue = parseFloat($('.amtdue').val());
              if(cash =='')
              {
                $('.responsecash').html('<div class="alert alert-danger danger-o" id="_emp_cash">Please input amount.</div>');
                $buttons.enable();
                $('#paymentcash').select(); 
              } 
              else if(cash<amtdue)
              {
                $('.responsecash').html('<div class="alert alert-danger danger-o" id="_emp_cash">Insufficient amount.</div>');
                $buttons.enable();
                $('#paymentcash').select(); 
              }
              else
              {
                cashis = removecomma($('#paymentcash').val());
                $.ajax({
                  url:'../ajax-cashier.php?request=gcrevalidationpayment',
                  data:{cashis:cashis,amtdue:amtdue},
                  type:'POST',
                  success:function(data)
                  {
                    console.log(data);
                    var data = JSON.parse(data);
                    if(data['stat'])
                    {
                      var total = addCommas(parseFloat(data['total']).toFixed(2));
                      $('p.gctitle').html('GC Revalidation Payment');
                      $('.receipt-items').html(data['items']); 
                      $('h3.transactnum span').html(zeroPad(data['transactnum'],4));
                      $('.receipt-footer').html('<table class="table tablefooterrec">'+
                        '<tr>'+
                          '<td>Total PHP</td>'+
                          '<td class="mright"><b>₱'+total+'</b></td>'+
                        '</tr>'+
                        '<tr>'+
                          '<td>No. of Items:</td>'+
                          '<td class="mright"><b>'+data['numitems']+'</b></td>'+
                        '</tr>'+
                      '</table>');
                      BootstrapDialog.closeAll();
                      var dialog = new BootstrapDialog({
                        message: function(dialogRef){
                        var $message = $('<div>Transaction successfully performed...</div>');             
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
                        window.print();
                        window.onafterprint = function(){
                           console.log("Printing completed...");
                           alert('xxx');
                        }
                        window.location.reload();                                                          
                      }, 1700);
                    }
                    else 
                    {
                      $('.responsecash').html('<div class="alert alert-danger danger-o" id="_emp_cash">'+data['msg']+'</div>');
                      $('#paymentcash').focus(); 
                    }
                  }
                });                
              }

            }
        }, {
            icon: 'glyphicon glyphicon-remove-sign',
            label: ' Cancel',
            cssClass: 'btn-primary',
            action: function(dialogItself){
                dialogItself.close();
            }
        }]
      });       
    }
  }
  else 
  {
    if(reval==0)
    {
      reval=1;
      BootstrapDialog.show({
      title:'Warning',
           message: 'Total Payment is 0.00',
           cssClass: 'login-dialog',  
           onshow: function(dialogRef){
                
          },
          onshown: function(dialogRef){                 
            
          },
          onhide: function(dialogRef){
                
          },
          onhidden: function(dialogRef){                 
            $('#rbarcode').select();
            reval=0;
          }            
      });
    }
  }
  
}


function revalidateGC()
{
  BootstrapDialog.show({
      title: 'GC Revalidation Payment Module',
      closable: true,
      closeByBackdrop: false,
      closeByKeyboard: true,
      message: function(dialog) {
        var $message = $("<div><img src='../assets/images/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
        var pageToLoad = dialog.getData('pageToLoad');
      setTimeout(function(){
        $message.load(pageToLoad); 
      },1000);
      return $message;
      },
      data: {
        'pageToLoad': '../dialogcashier/revalidategc.php',
      },
      cssClass: 'gcrevalidate',             
      onshown: function(dialogRef){
        setTimeout(function(){
          $('#rbarcode').inputmask("integer", { allowMinus: false});
          $('#rbarcode').focus();
        },1200);                             
      },
      onhidden: function(dialogRef){                  
          flag=0;
          payment = 0;
      },
      buttons: [{
          icon: 'glyphicon glyphicon-ok ',
          label: '  Scan GC',
          cssClass: 'btn-primary',
          hotkey: 13, // Enter.
          action: function(dialogItself) {
            var formURL = $('form#gcrevalidate').attr('action'), formData = $('form#gcrevalidate').serialize();
            if($('#rbarcode').val()!='')
            {
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
                    $('.response').html('');
                    var price = $('input[name=validateprice]').val();
                    var payment = $('#payment').val();
                    var total = 0;
                    total = parseFloat(price) + parseFloat(payment);
                    $('#payment').val(total.toFixed(2));
                    var denom = parseInt(data['denom']);
                    $('tbody.tbodyreval').prepend('<tr><td>'+data['barcode']+'</td><td>'+data['gctype']+
                      '</td><td>'+denom.toFixed(2)+'</td><td>'+data['datesold']+'</td><td>'+data['datevalidated']+'</td></tr>');
                  }
                  else 
                  {
                    $('.response').html('<div class="alert alert-danger alertmod">'+data['msg']+'</div>');                     
                  }
                }
              });
            }
            else 
            {
              $('.response').html('<div class="alert alert-danger alertmod">Please input GC Barcode #.</div>');
            }
            $('#rbarcode').select();
          }            
      }, {
        icon: 'glyphicon glyphicon-remove-sign',
        label: ' Cancel',
        cssClass: 'btn-default',
        action: function(dialogItself){
            dialogItself.close();
        }
      }]
  });
}

function lookup()
{
  if(flag==0)
  {
    flag=1;
    BootstrapDialog.show({
      title: 'GC Lookup',
      closable: true,
      closeByBackdrop: false,
      closeByKeyboard: true,
      cssClass: 'lookup',
      message: function(dialog) {
        var $message = $("<div><img src='../assets/images/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
        var pageToLoad = dialog.getData('pageToLoad');
      setTimeout(function(){
        $message.load(pageToLoad); 
      },1000);
      return $message;
      },
      data: {
        'pageToLoad': '../dialogcashier/lookup.php', 
      },
      onshow: function(dialog) {  
      // dialog.getButton('button-c').disable();
      },
      onshown: function(dialog){                  
      },
      onhidden: function(dialog){
          $('#numOnly').focus();    
          flag = 0;
      },
      buttons: [{
          icon: 'glyphicon glyphicon-ok ',
          label: ' Submit',
          cssClass: 'btn-success',
          hotkey: 13, 
          action: function(dialogRef) {
            var $button = this;
            $button.disable();
            $('.response-lookup').html('');
            if($('.gclookup').val()==undefined)
            {
              $button.enable();
            }
            else 
            {
              if($('.gclookup').val()!='')
              {
                var barcode = $('.gclookup').val().trim();          

                $.ajax({
                  url:'../ajax-cashier.php?request=storelookup',
                  data:{barcode:barcode},
                  type:"POST",
                  beforeSend:function()
                  {
                     $('.response-lookup').html('<img src="../assets/images/ajax-loader.gif">');
                  },
                  success:function(data)
                  {
                    console.log(data);
                    var data = JSON.parse(data);
                    if(data['st'])
                    {
                      $('.response-lookup').html(data['msg']);
                    }
                    else 
                    {
                      $('.response-lookup').html('<div class="alert alert-danger alert-med">'+data['msg']+'</div>');
                    }
                  }
                });
                $button.enable();
                $('.gclookup').select();    
              }
              else 
              {
                $('.response').html('<div class="alert alert-danger danger-o">Please input field.</div>');
                $button.enable();      
                $('.gclookup').focus();          
              }
            }

          }
      }, {
          icon: 'glyphicon glyphicon-remove-sign',
          label: ' Cancel',
          cssClass: 'btn-primary',
          action: function(dialogItself){
              dialogItself.close();
          }
      }]
    });    
  }
}


function cash()
{
  $.ajax({
    url:'../ajax-cashier.php?request=getAmtDueAndDiscount',
    success:function(payable)
    {
      var payable = JSON.parse(payable);
      if(payable['amtdue']>0)
      {
        if(flag==0){
          flag=1;
            //cash payment
            BootstrapDialog.show({
              title: 'Cash Payment',
              closable: true,
              closeByBackdrop: false,
              closeByKeyboard: true,
              cssClass: 'cash-payment',
              message: function(dialog) {
                var $message = $("<div><img src='../assets/images/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
                var pageToLoad = dialog.getData('pageToLoad');
              setTimeout(function(){
                $message.load(pageToLoad); 
              },1000);
              return $message;
              },
              data: {
                'pageToLoad': '../dialogcashier/cash.php',
              },
              onshow: function(dialog) {  
              // dialog.getButton('button-c').disable();
              },
              onshown: function(dialog){                  
              },
              onhidden: function(dialog){
                  $('#numOnly').focus();    
                  flag = 0;
              },
              buttons: [{
                  icon: 'glyphicon glyphicon-ok ',
                  label: ' Submit',
                  cssClass: 'btn-success',
                  hotkey: 13, 
                  action: function(dialogRef) {
                    var $button = this;
                    $button.disable();
                    $('.responsecash').html('');
                    var formURL = $('form#fpaymentcash').attr('action');
                    var cash = removecomma($('#paymentcash').val());
                    var total_charge = parseFloat(payable['amtdue']);
                    if(cash>0)
                    {       
                      if(cash =='')
                      {
                        $('.responsecash').html('<div class="alert alert-danger danger-o" id="_emp_cash">Please input amount.</div>');
                        $button.enable();
                        $('#paymentcash').select(); 
                      } 
                      else if(cash<total_charge)
                      {
                        $('.responsecash').html('<div class="alert alert-danger danger-o" id="_emp_cash">Insufficient amount.</div>');
                        $('#paymentcash').select(); 
                        $button.enable();
                      }
                      else
                      {
                        cash = removecomma(cash);
                        var change = parseFloat(cash - total_charge);                         
                        $.ajax({
                            type:'POST',
                            url:formURL,
                            data:{cash:cash,total_charge:total_charge},
                            beforeSend:function(){

                            },
                            success:function(data){
                              console.log(data);
                              var response = JSON.parse(data);

                              if(response['stat'])
                              {
                                dialogRef.close();
                                var total = parseFloat(response['amt_due']), cashrec = parseFloat(response['cash']);
                                var changerec = parseFloat(response['change']);
                                var linedisc = parseFloat(response['linedisc']);
                                var docdisc = parseFloat(response['docdisc']);
                                var stotal = parseFloat(response['stotal']);
                                linedisc = addCommas(linedisc.toFixed(2));
                                docdisc = addCommas(docdisc.toFixed(2));
                                cashrec = addCommas(cashrec.toFixed(2));
                                changerec = addCommas(changerec.toFixed(2));
                                total = addCommas(total.toFixed(2));
                                stotal = addCommas(stotal.toFixed(2));

                                if(response['receipt']=='yes')
                                {
                                  $('h3.transactnum span').html(response['transactnum']);
                                  $('.receipt-footer').html('<table class="table tablefooterrec">'+
                                    '<tr>'+
                                      '<td>Subtotal</td>'+
                                      '<td class="mright"><b>₱ '+stotal+'</b></td>'+
                                    '</tr>'+
                                    '<tr>'+
                                    '<tr>'+
                                      '<td>Line Discount</td>'+
                                      '<td class="mright"><b>₱ '+linedisc+'</b></td>'+
                                    '</tr>'+
                                    '<tr>'+
                                      '<td>Subtotal Discount</td>'+
                                      '<td class="mright mrightdis"><b>₱ '+docdisc+'</b></td>'+
                                    '</tr>'+
                                    '<tr>'+
                                      '<td>Total PHP</td>'+
                                      '<td class="mright "><b>₱ '+total+'</b></td>'+
                                    '</tr>'+
                                    '<tr>'+
                                      '<td>Cash</td>'+
                                      '<td class="mright"><b>₱ '+cashrec+'</b></td>'+
                                    '</tr>'+
                                    '<tr>'+
                                      '<td>Change</td>'+
                                      '<td class="mright"><b>₱ '+changerec+'</b></td>'+
                                    '</tr>'+
                                    '<tr>'+
                                      '<td>No. of Items:</td> '+
                                      '<td class="mright"><b> '+response['numitems']+'</b></td>'+
                                    '</tr>'+
                                  '</table>');                                
                                  window.print();
                                }
                                $('._barcodes').load('../ajax-cashier.php?request=load');
                                //$('.receipt-items').load('../ajax-cashier.php?request=receipt');
                                $('.cashier-mode').show();
                                $('.payment-mode').hide();
                                $('input#numOnly').prop("disabled",false);
                                $('input.msgsales').val("Change: "+changerec);
                                mode = 0;
                                setTimeout(function(){
                                  $('input#numOnly').focus();
                                },800);
                                // window.location.reload();
                              }
                              else 
                              {
                                $('.responsecash').html('<div class="alert alert-danger danger-o" id="_emp_cash">'+response['msg']+'</div>');
                                $button.enable();
                              }                               
                            }
                        });
                      }
                    }
                    else 
                    {
                        $('.responsecash').html('<div class="alert alert-danger danger-o" id="_emp_cash">Invalid Amount.</div>');
                        $button.enable();
                        $('#paymentcash').select();                       
                    }
                  }
              }, {
                  icon: 'glyphicon glyphicon-remove-sign',
                  label: ' Cancel',
                  cssClass: 'btn-primary',
                  action: function(dialogItself){
                      dialogItself.close();
                  }
              }]
            });
        }
      }
      else 
      {
        if(payable['amtdue']==0)
        {
          if(flag==0){
            flag=1;
             BootstrapDialog.show({
             title:'Warning',
                   message: 'No GC to charge',
                   cssClass: 'login-dialog',  
                   onshow: function(dialogRef){
                        
                  },
                  onshown: function(dialogRef){                 
                    
                  },
                  onhide: function(dialogRef){
                      
                  },
                  onhidden: function(dialogRef){
                      $('#numOnly').focus();                 
                      flag=0;
                  }            
              });
          }
        }
        else 
        {
          if(flag==0){
            flag=1;
             BootstrapDialog.show({
             title:'Warning',
                   message: 'Amount Due is negative.',
                   cssClass: 'login-dialog',  
                   onshow: function(dialogRef){
                        
                  },
                  onshown: function(dialogRef){                 
                    
                  },
                  onhide: function(dialogRef){
                      
                  },
                  onhidden: function(dialogRef){
                      $('#numOnly').focus();                 
                      flag=0;
                  }            
              });
          }
        }
      }
    }

  });
}

function linediscount()
{
  $.ajax({
    url:'../ajax-cashier.php?request=getAmtDueAndDiscount',
    success:function(payable)
    {
      var payable = JSON.parse(payable);
      if(payable['amtdue']>0)
      {
        BootstrapDialog.closeAll();
        flag = 0;
        if(flag==0)
        {
          flag=1;
          BootstrapDialog.show({
              title: 'Line Discount',
              closable: true,
              closeByBackdrop: false,
              closeByKeyboard: true,
              message: function(dialog) {
              var $message = $("<div><img src='../assets/images/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
              var pageToLoad = dialog.getData('pageToLoad');
              setTimeout(function(){
                $message.load(pageToLoad); 
              },1000);
              return $message;
              },
              data: {
                'pageToLoad': '../dialogcashier/linediscount.php',
              },
              cssClass: 'gcdiscount',             
              onshown: function(dialogRef){                 
              },
              onhidden: function(dialogRef){   
                $('#numOnly').focus();                   
                flag=0;

              },
              buttons: [{
                  icon: 'glyphicon glyphicon-ok ',
                  label: '  Submit',
                  cssClass: 'btn-primary',
                  hotkey: 13, // Enter.
                  action: function(dialogItself) {
                    var distype = $('input[name=distypec]').val();
                    var bcode = $('input[name=barcodefordis]').val();
                    var flaglinedis = $('#flaglinedis').val();
                    if(bcode!=undefined)
                    {
                      if(flaglinedis==0)
                      {
                        if(bcode.trim()!='')
                        {
                          $.ajax({
                            url:'../ajax-cashier.php?request=checkIFhasTempSalesForDiscount',                    
                            data:{bcode:bcode},
                            type:'POST',
                            success:function(data)
                            {
                              console.log(data);
                              var data = JSON.parse(data);
                              if(data['stat'])
                              {
                                var denom = parseInt(data['den']);
                                $('.response').html('');
                                $('#den').val(denom);
                                $('#totval').val(denom);
                                $('input[name=denom],#tot').val(addCommas(denom.toFixed(2)));
                                $('select[name=discountype]').prop('disabled',false);
                                $('input[name=barcodefordis]').prop('readonly',true);
                                $('select[name=discountype]').focus();
                                $('#flaglinedis').val(1);                          
                              }
                              else 
                              {
                                $('input[name=barcodefordis]').select();
                                $('.response').html('<div class="alert alert-danger nomarginbot alert-font">'+data['msg']+'</div');
                              }
                            }
                          });                    
                        }
                        else 
                        {
                           $('.response').html('<div class="alert alert-danger nomarginbot alert-font">Please input GC Barcode #.</div');
                        }
                        $('input[name=barcodefordis]').focus();
                      }
                      else 
                      {
                        
                        if($('select[name=discountype]').val()!='')
                        {
                          if($('#den').val()!=$('#totval').val())
                          {
                            var barcode = $('input[name=barcodefordis]').val();
                            var discountype = $('select[name=discountype]').val();
                            var percent = $('input[name=conpercent]').val();
                            var amount = $('input[name=amount]').val();
                            $.ajax({
                              url:'../ajax-cashier.php?request=linediscountbarcode',
                              data:{barcode:barcode,discountype:discountype,percent:percent,amount:amount},
                              type:'POST',
                              success:function(data)
                              {
                                console.log(data);
                                var data = JSON.parse(data);
                                if(data['stat'])
                                {
                                  $('.response').html('<div class="alert alert-success nomarginbot alert-font">Line Discount Successfully Performed.</div');
                                  $('._barcodes').load('../ajax-cashier.php?request=load');                                  
                                  $('.receipt-items').load('../ajax-cashier.php?request=receipt');
                                  updateAll();

                                  $('#barcodefordis').val('').prop('readonly',false);
                                  $('#percent, #amount, #den, #totval, #denom, #flaglinedis, #conpercent').val(0);
                                  $('#tot').val('');                                  
                                  $("#discountype option[value='']").prop('selected', true);
                                   $("#discountype").prop('disabled',true);
                                  $('#percent, #amount').prop('readonly',true);
                                  $('#barcodefordis').focus();
                                }
                                else 
                                {
                                  $('.response').html('<div class="alert alert-danger nomarginbot alert-font">'+data['msg']+'</div');
                                }
                              }
                            });
                          }
                          else 
                          {
                            $('.response').html('<div class="alert alert-danger nomarginbot alert-font">Please input percent / amount.</div');
                            $('select[name=discountype]').focus();                      
                          }
                        }
                        else 
                        {
                          $('.response').html('<div class="alert alert-danger nomarginbot alert-font">Please select discount type.</div');
                          $('select[name=discountype]').focus();
                        }
                      }
                    }
                  }            
              }, {
                icon: 'glyphicon glyphicon-remove-sign',
                label: ' Cancel',
                cssClass: 'btn-default',
                action: function(dialogItself){
                    dialogItself.close();
                }
              }]
          });
        }
      }
      else
      {
        alert('No GC to discount.');
      }
    }
  });
}

function voidlinereval()
{
  $.ajax({
    url:'../ajax-cashier.php?request=checkrevalgc',
    success:function(data)
    {
      var data = JSON.parse(data);
      if(data['msg'] > 0)
      {
          $('table tbody._barcodesreval tr:first').css('background-color','yellow');
          $('table tbody._barcodesreval tr:first td:nth-child(1) button').focus();
          currf = $('table tbody._barcodesreval tr:first td:nth-child(1) button');
      }
      else 
      {
        if(reval==0)
        {
          reval=1;
          BootstrapDialog.show({
          title:'Warning',
               message: 'No GC to void.',
               cssClass: 'login-dialog',  
               onshow: function(dialogRef){
                    
              },
              onshown: function(dialogRef){                 
                
              },
              onhide: function(dialogRef){
                  
              },
              onhidden: function(dialogRef){
                $('#numOnlyreval').focus();
                reval=0;
              }            
          });
        }
      }
    }
  });
}

function voidlinerefund()
{
  if(flag==0)
  {  
    $.ajax({
      url:'../ajax-cashier.php?request=cntrefunditems',
      success:function(data)
      {
        var data = JSON.parse(data);
        if(data['cntref'] > 0)
        {
            $('table tbody._barcodesrefund tr:first').css('background-color','yellow');
            $('table tbody._barcodesrefund tr:first td:nth-child(1) button').focus();
            currf = $('table tbody._barcodesrefund tr:first td:nth-child(1) button');
        }
        else 
        {
          if(reval==0)
          {
            reval=1;
            BootstrapDialog.show({
            title:'Warning',
                 message: 'No GC to void.',
                 cssClass: 'login-dialog',  
                 onshow: function(dialogRef){
                      
                },
                onshown: function(dialogRef){                 
                  
                },
                onhide: function(dialogRef){
                    
                },
                onhidden: function(dialogRef){
                  $('#numOnlyreturn').focus();
                  reval=0;
                }            
            });
          }
        }
      }
    });
  }
}

function voidline()
{
    $.ajax({
      url:'../ajax-cashier.php?request=counttempsales',
      success:function(count)
      {
        var data = JSON.parse(count);
        if(data['cnt']>0)
        {
          // $('table tbody._barcodes tr td button').blur(
          //     function(){
          //        $(this).closest('tr').css('background-color','white');
          //   }).focus(function() {
          //   $(this).closest('tr').css('background-color','yellow');
          // });

          $('table tbody._barcodes tr:first').css('background-color','yellow');
          $('table tbody._barcodes tr:first td:nth-child(1) button').focus();
          currf = $('table tbody._barcodes tr:first td:nth-child(1) button');
          // if($('table tbody._barcodes tr:first td:nth-child(1) button').is(":focus"))
          // {
          //   alert('xxx');
          // }
          $('#numOnly').val('');
        }
        else 
        {
          if(flag==0)
          {
            flag=1;
            BootstrapDialog.show({
            title:'Warning',
                 message: 'No GC to void.',
                 cssClass: 'login-dialog',  
                 onshow: function(dialogRef){
                      
                },
                onshown: function(dialogRef){                 
                  
                },
                onhide: function(dialogRef){
                    
                },
                onhidden: function(dialogRef){
                  $('#numOnly').focus();
                  flag=0;
                }            
            });
          }
        }
      }
    });
}

function voidAll()
{
  if(flag==0)
  {
    $.ajax({
      url:'../ajax-cashier.php?request=counttempsales',
      success:function(count)
      {
        var data = JSON.parse(count);
        if(data['cnt']>0)
        {
          BootstrapDialog.show({
            title: 'Confirmation',
              message:  '<div class="row">'+
                        '<div class="col-md-12">'+
                        'Are you sure you want to void all items'+
                        '</div>'+                                                                       
                        '</div>',
              closable: true,
              closeByBackdrop: false,
              closeByKeyboard: true,
              onshow: function(dialog) {
                  // dialog.getButton('button-c').disable();
              },
              onshown: function(dialog) {
              },
              onhidden: function(dialogRef){
                  flag=0;                  
              },
              buttons: [{
                  icon: 'glyphicon glyphicon-ok-sign',
                  label: 'Yes',
                  cssClass: 'btn-success',
                  hotkey: 13,
                  action:function(dialogItself){                     
                      $.ajax({
                        url:'../ajax-cashier.php?request=voidall',
                        success:function(data){
                          console.log(data);
                          var data = JSON.parse(data);
                          if(data['stat'])
                          {
                            if(data['stat'])
                            {                              
                              $('._barcodes').load('../ajax-cashier.php?request=load');                             
                              $('.receipt-items').load('../ajax-cashier.php?request=receipt');                 
                              updateAll();
                              dialogItself.close();
                            }     
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
        }
        else 
        {
          if(flag==0)
          {
            flag=1;
            BootstrapDialog.show({
            title:'Warning',
                 message: 'No GC to void.',
                 cssClass: 'login-dialog',  
                 onshow: function(dialogRef){
                      
                },
                onshown: function(dialogRef){                 
                  
                },
                onhide: function(dialogRef){
                    
                },
                onhidden: function(dialogRef){                 
                    flag=0;
                }            
            });
          }
        }
      }
    });
  }
}

function trandis()
{
  $.ajax({
    url:'../ajax-cashier.php?request=getAmtDueAndDiscount',
    success:function(payable)
    {
      var payable = JSON.parse(payable);
      if(payable['amtdue']>0)
      {
        BootstrapDialog.closeAll();
        flag = 0;
        if(flag==0)
        {
          flag=1;
          BootstrapDialog.show({
              title: 'Subtotal Discount',
              closable: true,
              closeByBackdrop: false,
              closeByKeyboard: true,
              message: function(dialog) {
              var $message = $("<div><img src='../assets/images/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
              var pageToLoad = dialog.getData('pageToLoad');
              setTimeout(function(){
                $message.load(pageToLoad); 
              },1000);
              return $message;
              },
              data: {
                'pageToLoad': '../dialogcashier/transdiscount.php',
              },
              cssClass: 'gcdiscount',             
              onshown: function(dialogRef){                    
              },
              onhidden: function(dialogRef){                  
                flag=0;
              },
              buttons: [{
                  icon: 'glyphicon glyphicon-ok ',
                  label: '  Submit',
                  cssClass: 'btn-primary',
                  hotkey: 13, // Enter.
                  action: function(dialogItself) {
                    $('.response').html('');
                    var dt = $('#discountype').val();
                    var tv = $('#totval').val();
                    var p = $('#conprnt').val();
                    var a = $('#amount').val();
                    if(dt.trim()!='')
                    {
                      if(p!=0 || a!=0)
                      {    
                        $.ajax({
                          url:'../ajax-cashier.php?request=transactiondisc',
                          data:{dt:dt,tv:tv,p:p,a:a},
                          type:'POST',
                          success:function(data)
                          {
                            var data = JSON.parse(data);
                            if(data['stat'])
                            {
                              dialogItself.close();
                              updateAll();  
                              alert('Subtotal Successfully Discounted.');                            
                            }
                            else 
                            {
                              $('.response').html('<div class="alert alert-danger nomarginbot alert-font">'+data['msg']+'</div');
                              $('#discountype').focus();
                            }
                          }
                        })
                      }
                      else 
                      {
                        $('.response').html('<div class="alert alert-danger nomarginbot alert-font">Please input percent/amount.</div');
                        $('#discountype').focus();
                      }
                    }
                    else 
                    {
                      $('.response').html('<div class="alert alert-danger nomarginbot alert-font">Please select discount type.</div');
                      $('#discountype').focus();
                    }
                  }

              },{
                icon: 'glyphicon glyphicon-remove-sign',
                label: ' Cancel',
                cssClass: 'btn-default',
                action: function(dialogItself){
                    dialogItself.close();
                }
              }]
          });
        }
      }
      else
      {
        alert('No Transaction to discount.');
      }
    }

  });

}

function servicechargeshow()
{
  // get number of GC's to refund
    $.ajax({
    url:'../ajax-cashier.php?request=cntrefunditems',
    success:function(refunds)
    {
      var refunds = JSON.parse(refunds);
      if(refunds['cntref'] > 0)
      {
        if(flag==0){
          flag=1;
            BootstrapDialog.show({
              title: '&nbsp;',
              closable: true,
              closeByBackdrop: false,
              closeByKeyboard: true,
              cssClass: 'cash-payment',
              message: function(dialog) {
                var $message = $("<div><img src='../assets/images/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
                var pageToLoad = dialog.getData('pageToLoad');
              setTimeout(function(){
                $message.load(pageToLoad); 
              },1000);
              return $message;
              },
              data: {
                'pageToLoad': '../dialogcashier/servicechargedlg.php',
              },
              onshow: function(dialog) {  
              // dialog.getButton('button-c').disable();
              },
              onshown: function(dialog){                  
              },
              onhidden: function(dialog){
                  $('#numOnlyreturn').focus();    
                  flag = 0;
              },
              buttons: [{
                  icon: 'glyphicon glyphicon-ok ',
                  label: ' Submit',
                  cssClass: 'btn-success',
                  hotkey: 13, 
                  action: function(dialogRef) {
                    var $button = this;
                    $button.disable();
                    $('.response-sc').html('');
                    var cash = removecomma($('#paymentcash').val());
                    if(cash>0)
                    {       
                      $.ajax({
                        url:'../ajax-cashier.php?request=insertrefund',
                        type:'POST',
                        data:{cash:cash},
                        success:function(data)
                        {
                          var data = JSON.parse(data)
                          {
                            if(data['st'])
                            {
                              dialogRef.close();
                              $('input.serviceref').val("- "+data['scharge']);
                              $('input._cashier_totalrefund').val(addCommas(data['refamtdue'].toFixed(2)));
                            }
                            else 
                            {
                              $('.response-sc').html('<div class="alert alert-danger danger-o" id="_emp_cash">Invalid Amount.</div>');
                              $button.enable();
                              $('#paymentcash').select(); 
                            }
                          }
                        } 
                      });
                    }
                    else 
                    {
                        $('.response-sc').html('<div class="alert alert-danger danger-o" id="_emp_cash">Invalid Amount.</div>');
                        $button.enable();
                        $('#paymentcash').select();                       
                    }
                  }
              }, {
                  icon: 'glyphicon glyphicon-remove-sign',
                  label: ' Cancel',
                  cssClass: 'btn-primary',
                  action: function(dialogItself){
                      dialogItself.close();
                  }
              }]
            });
        }

      }
      else
      {
        if(flag==0){
          flag=1;
          BootstrapDialog.show({
          title:'Warning',
               message: 'Please scan GC first.',
               cssClass: 'login-dialog',  
               onshow: function(dialogRef){
                    
              },
              onshown: function(dialogRef){                 
                
              },
              onhide: function(dialogRef){
                  
              },
              onhidden: function(dialogRef){
                  $('#numOnlyreturn').focus();                 
                  flag=0;
              }            
          });
        }
      }
    }

    });
}

function gcrefund()
{
  if(flag==0)
  {
    flag=1;
    servicecharge=1;

    //check temp sales
    $.ajax({
      url:'../ajax-cashier.php?request=checkIFhasTempSales',
      success:function(data){
        console.log(data);
        var data = JSON.parse(data);
        if(data['st'])
        {
          BootstrapDialog.show({
              title: 'GC Refund Module',
              closable: true,
              closeByBackdrop: false,
              closeByKeyboard: true,
              message: function(dialog) {
                var $message = $("<div><img src='../assets/images/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
                var pageToLoad = dialog.getData('pageToLoad');
              setTimeout(function(){
                $message.load(pageToLoad);
              },1000);
              return $message;
              },
              data: {
                'pageToLoad': '../dialogcashier/returngc.php',
              },
              cssClass: 'gcreturn',             
              onshown: function(dialogRef){
                servicecharge = 1;
                $.ajax({
                  url:'../ajax-cashier.php?request=truncateGCTempRefundTable'
                });                             
              },
              onhidden: function(dialogRef){
                  $('#numOnly').focus();
                  flag=0;
                  servicecharge=0;
              },
              buttons: [{
                  icon: 'glyphicon glyphicon-ok ',
                  label: '  Submit',
                  cssClass: 'btn-primary',
                  hotkey: 13, // Enter.
                  action: function(dialogItself) {
                    var $button = this;                
                    var formData = $('form#gcreturn').serialize(), formURL = $('form#gcreturn').attr('action');
                    var modes = $('input[name=modes]').val(), transno = $('#transno').val();
                    if(modes==1)
                    {
                      if(transno!='')
                      {
                        $.ajax({
                          url:formURL,
                          data:{transno:transno},
                          type:'POST',
                          success:function(data){          
                            var data = JSON.parse(data);
                            if(data['stat'])
                            {
                              $button.html('<span class="bootstrap-dialog-button-icon glyphicon glyphicon-ok"></span> Scan GC');
                              $( "div.fgroup" ).removeClass( "showhide");
                              $('input#transno').prop('disabled',true);
                              $('label.translbl').text('Transaction No.');
                              $('input[name=modes]').val('2');
                              $('.response').html('');
                              $('#rbarcode').focus(); 
                              $('input[name=dot]').val(data['datetrans']);
                              $('input[name=store]').val(data['storename']);
                              $('input[name=cashier]').val(data['cashier']);
                              $('input[name=transid]').val(data['transid']);
                            }
                            else 
                            {
                              $('#transno').select();        
                              $('.response').html('<div class="alert alert-danger alertmod">'+data['msg']+'</div>');
                            }
                          }
                        });
                      }
                      else
                      {
                        $('#transno').select();  
                        $('.response').html('<div class="alert alert-danger alertmod">Please enter transaction no.</div>');
                      }                                     
                    }
                    else if(modes==2)
                    {
                      var barcode = $('#rbarcode').val();                      
                      if(barcode!='')
                      {
                        var transid = $('#transid').val(); 
                        $.ajax({
                          url:'../ajax-cashier.php?request=checkGCReturnBarcode',
                          data:{barcode:barcode,transno:transno,transid:transid},
                          type:'POST',
                          success:function(data)
                          {
                            var data = JSON.parse(data);
                            if(data['stat'])
                            {                              
                              $('.response').html('');
                              $('.gctoreturns').html("<img src='../assets/images/ajax-loader.gif'><small class='text-danger'>please wait...</small>");
                              $('#rbarcode').select();   
                              setTimeout(function(){
                                $.ajax({
                                  url:'../ajax-cashier.php?request=displayGCTempRefund',                                  
                                  type:'POST',
                                  data:{transno:transno,transid:transid},
                                  success:function(data1)
                                  {
                                    $('.gctoreturns').html(data1);
                                  }
                                });
                              },1000);
                            }
                            else 
                            {
                              $('.response').html('<div class="alert alert-danger alertmod">'+data['msg']+'</div>');
                              $('#rbarcode').select();
                            }
                          }
                        });
                      }
                      else 
                      {
                         $('.response').html('<div class="alert alert-danger alertmod">Please enter GC Barcode.</div>');
                         $('#rbarcode').select();
                      }
                    }
                  }                  
              }, {
                icon: 'glyphicon glyphicon-remove-sign',
                label: ' Cancel',
                cssClass: 'btn-default',
                action: function(dialogItself){
                    dialogItself.close();
                }
              }]
          });
        }
        else 
        {
           BootstrapDialog.show({
           title:'Warning',
                 message: data['msg'],
                 cssClass: 'login-dialog',  
                 onshow: function(dialogRef){
                      
                },
                onshown: function(dialogRef){                 
                  
                },
                onhide: function(dialogRef){
                    
                },
                onhidden: function(dialogRef){
                    $('#numOnly').focus();                 
                    flag=0;
                }            
            });
        }
      }
    });
  }
}


function headoffice()
{
  $.ajax({
    url:'../ajax-cashier.php?request=getAmtDueAndDiscount',
    success:function(payable)
    {
      var payable = JSON.parse(payable);
      if(payable['amtdue']>0)
      {
        if(flag==0){
          flag=1;
          customer=1;
          group = 1;  
            //cash payment
            BootstrapDialog.show({
              title: 'Head Office',
              closable: true,
              closeByBackdrop: false,
              closeByKeyboard: true,
              message: function(dialog) {
                var $message = $("<div><img src='../assets/images/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
                var pageToLoad = dialog.getData('pageToLoad');
              setTimeout(function(){
                $message.load(pageToLoad); 
              },1000);
              return $message;
              },
              data: {
                'pageToLoad': '../dialogcashier/headoffice.php?cusgroup=1',
              },
              cssClass: 'gcheadoffice',  
              onshow: function(dialog) {
              // dialog.getButton('button-c').disable();
              },
              onshown: function(dialog){
              },
              onhidden: function(dialog){
                  $('#numOnly').focus();    
                  flag = 0;
                  customer=0;
              },
              buttons: [{
                  icon: 'glyphicon glyphicon-ok ',
                  label: ' Submit',
                  cssClass: 'btn-success',
                  hotkey: 13, 
                  action: function(dialogRef) {
                    var $button = this;
                    $button.disable();
                    var formURL = '../ajax-cashier.php?request=ar_paymentheadoffice', formData = $('form#headoffice').serialize();
                    if($('#customercode').val().trim()!= '')
                    {
                      if(($('#customername').val().trim()!='') && ($('#customeraddress').val().trim()!='') && ($('#customertype').val().trim()!=''))
                      {
                        totaldis = removecomma($('#totaldis').val());
                        if(totaldis>0)
                        {
                          //lahos
                          BootstrapDialog.show({
                            title: 'Confirmation',
                              message: 'Would you like to proceed?',
                              closable: true,
                              closeByBackdrop: false,
                              closeByKeyboard: true,
                              cssClass: 'confirmationpayment',    
                              onshow: function(dialog) {
                                  // dialog.getButton('button-c').disable();
                                confirm = 1;
                              },
                              onhidden: function(dialogRef){
                                confirm = 0;
                                $('#customercode').focus();
                              },
                              buttons: [{
                                  icon: 'glyphicon glyphicon-ok-sign',
                                  label: 'Yes',
                                  cssClass: 'btn-success',
                                  hotkey: 13,
                                  action:function(dialogItself){
                                    var $buttoncon = this;
                                    $buttoncon.disable();
                                    $.ajax({
                                      url:formURL,
                                      data:formData,
                                      type:'POST',
                                      success:function(data){
                                        console.log(data);
                                        var data = JSON.parse(data);
                                        if(data['stat'])
                                        {
                                          BootstrapDialog.closeAll();
                                          var total = addCommas(parseFloat(data['total']).toFixed(2));
                                          var sub = addCommas(parseFloat(data['sub']).toFixed(2));
                                          var cusdiscount = addCommas(parseFloat(data['cusdiscount']).toFixed(2));
                                          var linedisc = addCommas(parseFloat(data['linedisc']).toFixed(2));
                                          var docdisc = addCommas(parseFloat(data['docdisc']).toFixed(2));
                                          var balance = addCommas(parseFloat(data['balance']).toFixed(2));
                                          // total = total.toFixed(2);
                                          // total = addCommas(total);                                
                                          items = addCommas(parseFloat(data['noitems']));
                                          if(data['receipt']=='yes')
                                          {
                                            $('h3.transactnum span').html(data['transactnum']);
                                            $('.receipt-footer').html('<table class="table tablefooterrec">'+
                                              '<tr>'+
                                                '<td>Subtotal </td>'+
                                                '<td class="mright"><b>₱ '+sub+'</b></td>'+
                                              '</tr>'+
                                              '<tr>'+
                                                '<td>Customer Discount </td>'+
                                                '<td class="mright"><b>₱ '+cusdiscount+'</b></td>'+
                                              '</tr>'+
                                              '<tr>'+
                                                '<td>Total Line Discount </td>'+
                                                '<td class="mright"><b>₱ '+linedisc+'</b></td>'+
                                              '</tr>'+
                                              '<tr>'+
                                                '<td>Transaction Discount </td>'+
                                                '<td class="mright mrightdis"><b>₱ '+docdisc+'</b></td>'+
                                              '</tr>'+
                                              '<tr>'+
                                                '<td>Total </td>'+
                                                '<td class="mright"><b>₱ '+total+'</b></td>'+
                                              '</tr>'+
                                              '<tr>'+
                                              '<tr>'+
                                                '<td>No. of Items:</td>'+
                                                '<td class="mright"><b>'+items+'</b></td>'+
                                              '</tr>'+
                                              '<tr>'+
                                                '<td>Head Office</td>'+
                                                '<td></td>'+
                                              '</tr>'+
                                              '<tr>'+
                                                '<td>Current Balance</td>'+
                                                '<td class="mright"><b>₱ '+balance+'</b></td>'+
                                              '</tr>'+
                                            '</table>'+
                                            '<div class="col-xs-12 cashiername">'+
                                              data['fullname']+
                                            '</div>'+
                                            '<div class="col-xs-12 cashiersig">'+
                                              '<span class="cashiersigspan">Customer Signature</span>'+
                                            '</div><br /><br />');
                                            window.print();
                                          }                                         
                                          $('.sbtotal').val(sub);
                                          $('._cashier_total').val(total);
                                          $('.linediscount').val(linedisc);
                                          $('.docdiscount').val(docdisc);
                                          $('.cdisc').val(cusdiscount);
                                          $('._barcodes').load('../ajax-cashier.php?request=load');
                                          // $('.sbtotal').load('../ajax-cashier.php?request=total');
                                          // $('#_cashier_total').load('../ajax-cashier.php?request=amtdue');
                                          // $('.linediscount').load('../ajax-cashier.php?request=linediscount');
                                          // $('.docdiscount').load('../ajax-cashier.php?request=docdiscount');
                                          //$('.receipt-items').load('../ajax-cashier.php?request=receipt');                 
                                          //$('.noitems').load('../ajax-cashier.php?request=totalitems');
                                          $('span.cashr').text('₱ 0.00');
                                          $('span.changer').text('₱ 0.00');
                                          // $('span.cashr').text('₱ 0.00');
                                          // $('span.changer').text('₱ 0.00');
                                          // $('._barcodes').load('../ajax-cashier.php?request=load');
                                          // $('.receipt-items').load('../ajax-cashier.php?request=receipt');
                                          $('.cashier-mode').show();
                                          $('.payment-mode').hide();
                                          $('input.msgsales').val("Last transaction was H.O.");
                                          $('input#numOnly').prop('disabled',false);
                                          setTimeout(function(){
                                            $('input#numOnly').focus();
                                          },1000);
                                          mode = 0;
                                        }
                                        else 
                                        {
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
                          $('.response').html('<div class="alert alert-danger">Invalid Amount.</div>');
                          alert($('#totaldis').val());
                        }
                      }
                      else 
                      {
                        $('.response').html('<div class="alert alert-danger">Customer code not found.</div>');
                      }
                    }
                    else 
                    {
                      $('.response').html('<div class="alert alert-danger">Please input customer code.</div>');
                    }
                    $('#customercode').focus();
                    $button.enable();

                  }
              }, {
                  icon: 'glyphicon glyphicon-remove-sign',
                  label: ' Cancel',
                  cssClass: 'btn-primary',
                  action: function(dialogItself){
                      dialogItself.close();
                  }
              }]
            });
        }
      }
      else 
      {
        if(payable['amtdue']==0)
        {
          if(flag==0){
            flag=1;
             BootstrapDialog.show({
             title:'Warning',
                   message: 'No GC to charge',
                   cssClass: 'login-dialog',  
                   onshow: function(dialogRef){
                        
                  },
                  onshown: function(dialogRef){                 
                    
                  },
                  onhide: function(dialogRef){
                      
                  },
                  onhidden: function(dialogRef){
                      $('#numOnly').focus();                 
                      flag=0;
                  }            
              });
          }
        }
        else 
        {
          if(flag==0){
            flag=1;
             BootstrapDialog.show({
             title:'Warning',
                   message: 'Amount Due is negative.',
                   cssClass: 'login-dialog',  
                   onshow: function(dialogRef){
                        
                  },
                  onshown: function(dialogRef){                 
                    
                  },
                  onhide: function(dialogRef){
                      
                  },
                  onhidden: function(dialogRef){
                      $('#numOnly').focus();                 
                      flag=0;
                  }            
              });
          }
        }
      }
    }
  });
}


function subsadmin()
{
  $.ajax({
    url:'../ajax-cashier.php?request=getAmtDueAndDiscount',
    success:function(payable)
    {
      var payable = JSON.parse(payable);
      if(payable['amtdue']>0)
      {
        if(flag==0)
        {
          group=2;
          flag=1;
          customer=1;   
            //subs. admin payment
            BootstrapDialog.show({
              title: 'Subs. Admin',
              closable: true,
              closeByBackdrop: false,
              closeByKeyboard: true,
              message: function(dialog) {
                var $message = $("<div><img src='../assets/images/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
                var pageToLoad = dialog.getData('pageToLoad');
              setTimeout(function(){
                $message.load(pageToLoad); 
              },1000);
              return $message;
              },
              data: {
                'pageToLoad': '../dialogcashier/headoffice.php?cusgroup=2',
              },
              cssClass: 'gcheadoffice',            
              onshow: function(dialog) {
              // dialog.getButton('button-c').disable();
              },
              onshown: function(dialog){
              },
              onhidden: function(dialog){
                  $('#numOnly').focus();    
                  flag = 0;
                  customer=0;
              },
              buttons: [{
                  icon: 'glyphicon glyphicon-ok ',
                  label: ' Submit',
                  cssClass: 'btn-success',
                  hotkey: 13, 
                  action: function(dialogRef) {
                    var $button = this;
                    $button.disable();
                    var formURL = '../ajax-cashier.php?request=ar_paymentheadoffice', formData = $('form#headoffice').serialize();
                    if($('#customercode').val().trim()!= '')
                    {
                      if(($('#customername').val().trim()!='') && ($('#customeraddress').val().trim()!='') && ($('#customertype').val().trim()!=''))
                      {
                        //lahos
                          BootstrapDialog.show({
                            title: 'Confirmation',
                              message: 'Would you like to proceed?',
                              closable: true,
                              closeByBackdrop: false,
                              closeByKeyboard: true,
                              cssClass: 'confirmationpayment',    
                              onshow: function(dialog) {
                                  // dialog.getButton('button-c').disable();
                                confirm = 1;
                              },
                              onhidden: function(dialogRef){
                                confirm = 0;
                                $('#customercode').focus();
                              },
                              buttons: [{
                                  icon: 'glyphicon glyphicon-ok-sign',
                                  label: 'Yes',
                                  cssClass: 'btn-success',
                                  hotkey: 13,
                                  action:function(dialogItself){                  
                                    var $buttoncon  = this;
                                    $buttoncon.disable();
                                    $.ajax({
                                      url:formURL,
                                      data:formData,
                                      type:'POST',
                                      success:function(data){
                                        console.log(data);
                                        var data = JSON.parse(data);
                                        if(data['stat'])
                                        {
                                          var total = addCommas(parseFloat(data['total']).toFixed(2));
                                          var sub = addCommas(parseFloat(data['sub']).toFixed(2));
                                          var cusdiscount = addCommas(parseFloat(data['cusdiscount']).toFixed(2));
                                          var linedisc = addCommas(parseFloat(data['linedisc']).toFixed(2));
                                          var docdisc = addCommas(parseFloat(data['docdisc']).toFixed(2));
                                          var balance = addCommas(parseFloat(data['balance']).toFixed(2));
                                          // total = total.toFixed(2);
                                          // total = addCommas(total);
                                          items = addCommas(parseInt(data['noitems']));
                                          if(data['receipt']=='yes')
                                          {
                                            $('h3.transactnum span').html(data['transactnum']);
                                            $('.receipt-footer').html('<table class="table tablefooterrec">'+
                                              '<tr>'+
                                                '<td>Subtotal </td>'+
                                                '<td class="mright"><b>₱ '+sub+'</b></td>'+
                                              '</tr>'+
                                              '<tr>'+
                                                '<td>Customer Discount </td>'+
                                                '<td class="mright"><b>₱ '+cusdiscount+'</b></td>'+
                                              '</tr>'+
                                              '<tr>'+
                                                '<td>Line Discount </td>'+
                                                '<td class="mright"><b>₱ '+linedisc+'</b></td>'+
                                              '</tr>'+
                                              '<tr>'+
                                                '<td>Transaction Discount </td>'+
                                                '<td class="mright mrightdis"><b>₱ '+docdisc+'</b></td>'+
                                              '</tr>'+
                                              '<tr>'+
                                                '<td>Total </td>'+
                                                '<td class="mright"><b>₱ '+total+'</b></td>'+
                                              '</tr>'+
                                              '<tr>'+
                                              '<tr>'+
                                                '<td>No. of Items:</td>'+
                                                '<td class="mright"><b>'+items+'</b></td>'+
                                              '</tr>'+
                                              '<tr>'+
                                                '<td>Subsidiary Admin</td>'+
                                                '<td></td>'+
                                              '</tr>'+
                                              '<tr>'+
                                                '<td>Current Balance</td>'+
                                                '<td class="mright"><b>₱ '+balance+'</b></td>'+
                                              '</tr>'+
                                            '</table>'+
                                            '<div class="col-xs-12 cashiername">'+
                                              data['fullname']+
                                            '</div>'+
                                            '<div class="col-xs-12 cashiersig">'+
                                              '<span class="cashiersigspan">Customer Signature</span>'+
                                            '</div><br /><br />');
                                            window.print();
                                          }
                                          BootstrapDialog.closeAll();
                                          $('.sbtotal').val(sub);
                                          $('._cashier_total').val(total);
                                          $('.linediscount').val(linedisc);
                                          $('.docdiscount').val(docdisc);
                                          $('.cdisc').val(cusdiscount);
                                          $('._barcodes').load('../ajax-cashier.php?request=load');
                                          // $('.sbtotal').load('../ajax-cashier.php?request=total');
                                          // $('#_cashier_total').load('../ajax-cashier.php?request=amtdue');
                                          // $('.linediscount').load('../ajax-cashier.php?request=linediscount');
                                          // $('.docdiscount').load('../ajax-cashier.php?request=docdiscount');
                                          //$('.receipt-items').load('../ajax-cashier.php?request=receipt');                 
                                          //$('.noitems').load('../ajax-cashier.php?request=totalitems');
                                          $('span.cashr').text('₱ 0.00');
                                          $('span.changer').text('₱ 0.00');
                                          // $('span.cashr').text('₱ 0.00');
                                          // $('span.changer').text('₱ 0.00');
                                          // $('._barcodes').load('../ajax-cashier.php?request=load');
                                          // $('.receipt-items').load('../ajax-cashier.php?request=receipt');
                                          $('.cashier-mode').show();
                                          $('.payment-mode').hide();
                                          $('input#numOnly').prop('disabled',false);
                                          $('input.msgsales').val('Last transaction was Sub Admin.');
                                          setTimeout(function(){
                                            $('input#numOnly').focus();
                                          },1000)
                                          mode = 0;
                                        }
                                        else 
                                        {
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
                        $('.response').html('<div class="alert alert-danger">Customer code not found.</div>');
                      }
                    }
                    else 
                    {
                      $('.response').html('<div class="alert alert-danger">Please input customer code.</div>');
                    }
                    $('#customercode').focus();
                    $button.enable();
                  }
              }, {
                  icon: 'glyphicon glyphicon-remove-sign',
                  label: ' Cancel',
                  cssClass: 'btn-primary',
                  action: function(dialogItself){
                      dialogItself.close();
                  }
              }]
            });
        }        
      }
      else 
      {
        if(payable['amtdue']==0)
        {
          if(flag==0){
            flag=1;
             BootstrapDialog.show({
             title:'Warning',
                   message: 'No GC to charge',
                   cssClass: 'login-dialog',  
                   onshow: function(dialogRef){
                        
                  },
                  onshown: function(dialogRef){                 
                    
                  },
                  onhide: function(dialogRef){
                      
                  },
                  onhidden: function(dialogRef){
                      $('#numOnly').focus();                 
                      flag=0;
                  }            
              });
          }
        }
        else 
        {
          if(flag==0){
            flag=1;
             BootstrapDialog.show({
             title:'Warning',
                   message: 'Amount Due is negative.',
                   cssClass: 'login-dialog',  
                   onshow: function(dialogRef){
                        
                  },
                  onshown: function(dialogRef){                 
                    
                  },
                  onhide: function(dialogRef){
                      
                  },
                  onhidden: function(dialogRef){
                      $('#numOnly').focus();                 
                      flag=0;
                  }            
              });
          }
        }
      }
    }
  });
}

function getCustomerInfo(code,group)
{
  var amtdue = $('input[name=amtduehid').val();  
  var total = 0;
  if(code.trim()!='')
  {
    $.ajax({
      url:'../ajax-cashier.php?request=getcustomerar&ar='+group,
      data:{code:code},
      type:'POST',
      success:function(data){
        $('.response').html('');
        var data = JSON.parse(data);
        if(data['stat'])
        {
          $('#customercodehide').val(data['code']);
          $('#customername').val(data['name']);
          $('#customeraddress').val(data['address']);
          $('#customertype').val(data['type']);
          $('#arbalance').val(addCommas(data['ar'].toFixed(2)));
          $('#discount').val(addCommas(data['discount'].toFixed(2)));          
          total = parseFloat(removecomma(amtdue)) - parseFloat(data['discount']);
          $('#totaldis').val(addCommas(total.toFixed(2)));
          $('textarea#remarks').val('');  
          $('textarea#remarks').prop("disabled", false);
        }
        else 
        {
          $('.response').html('<div class="alert alert-danger">'+data['msg']+'</div>');
          $('#customercodehide').val('');
          $('#customername').val('');
          $('#customeraddress').val('');
          $('#customertype').val('');
          $('#arbalance').val('');
          $('#discount').val('0.00');   
          $('textarea#remarks').val('');   
          $('textarea#remarks').prop("disabled", true);    
          amtdue = parseFloat(amtdue);
          $('#totaldis').val(addCommas(amtdue.toFixed(2)));
        }
      }
    });
  }
  else 
  {
    $('.response').html('');
    $('#customercodehide').val('');
    $('#customername').val('');
    $('#customeraddress').val('');
    $('#customertype').val('');
    $('#discount').val('0.00');
    amtdue = parseFloat(amtdue);
    $('#totaldis').val(addCommas(amtdue.toFixed(2)));
    $('#arbalance').val('');
  }
}

function searchCustomer()
{
  if(search==0)
  {
    search=1;
    BootstrapDialog.show({
      title: 'Customer Lookup',
      closable: true,
      closeByBackdrop: false,
      closeByKeyboard: true,
      message: function(dialog) {
        var $message = $("<div><img src='../assets/images/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
        var pageToLoad = dialog.getData('pageToLoad');
      setTimeout(function(){
        $message.load(pageToLoad);
      },1000);
      return $message;
      },
      data: {
        'pageToLoad': '../dialogcashier/searchcustomer.php?group='+group,
      },
      cssClass: 'seachcustomer',            
      onshow: function(dialog) {
      // dialog.getButton('button-c').disable();  
      },
      onshown: function(dialog){
      },
      onhidden: function(dialog){
          $('#customercode').select();
          search=0;   
      },
      buttons: [{          
          icon: 'glyphicon glyphicon-remove-sign',
          label: ' Close',
          cssClass: 'btn-primary closecusto',
          action: function(dialogItself){
              dialogItself.close();
          }
      }]
    });
  }
}

function supervisormode()
{
  if(flag==0){
    flag=1;
    $('#numOnly').val('');
    BootstrapDialog.show({              
        title: 'Supervisor Login',
          message: '<div class="row">'+
                      '<div class="col-lg-12">'+
                        '<label class="lbl-c">'+
                          'Username'+
                        '</label>'+
                      '</div>'+
                    '</div>'+
                    '<div class="row">'+
                      '<div class="col-lg-12">'+
                        '<input type="text" class="form-control supervisor-in inpmed alignleft" id="s-uname">'+
                      '</div>'+
                    '</div>'+
                    '<div class="row">'+
                      '<div class="col-lg-12">'+
                        '<label class="lbl-c">'+
                          'ID Number'+
                        '</label>'+
                      '</div>'+
                    '</div>'+
                    '<div class="row">'+
                      '<div class="col-lg-12">'+
                        '<input type="text" class="form-control inpmed alignleft" id="s-id" maxlength="13">'+
                      '</div>'+
                    '</div>'+
                    '<div class="row">'+
                      '<div class="col-lg-12">'+
                        '<label class="lbl-c">'+
                          'Manager Key'+
                        '</label>'+
                      '</div>'+
                    '</div>'+
                    '<div class="row">'+
                      '<div class="col-lg-12">'+
                        '<input type="password" class="form-control inpmed alignleft" id="s-key">'+
                      '</div>'+
                    '</div>'+
                    '<div class="row">'+
                      '<div class="col-lg-12 response">'+                                
                      '</div>'+
                    '</div>',
          cssClass: 'login-supervisor',             
          onshow: function(dialogRef){              
              
          },
          onshown: function(dialogRef){
            $('.supervisor-in').focus();
           
          },
          onhide: function(dialogRef){
              
          },
          onhidden: function(dialogRef){
            $('#numOnly').focus();
            flag=0;
            mode=0;
          },
          buttons: [{
              label: 'Submit',
              cssClass: 'btn-primary',
              hotkey: 13, // Enter.
              action: function(dialogItself) {
                var uname = $('#s-uname').val(), idnum = $('#s-id').val(), key=$('#s-key').val();
                  $.ajax({
                    url:'../ajax-cashier.php?request=supervisormode',
                    type:'POST',
                    data:{uname:uname,idnum:idnum,key:key},
                    beforeSend:function(){

                    },
                    success:function(response){
                      var res = response.trim();

                      if(res=='success'){
                        if($('#managerkey').is(':checked')){
                          $('#managerkey').prop('checked', false);
                          $('input#numOnly').prop('disabled',false);
                          $('.manager-mode').hide();
                          $('.cashier-mode').show();
                          $('input#numOnly').val('');
                        } else {
                          $('#managerkey').prop('checked', true);
                          $('input#numOnly').prop('disabled',true);
                          $('.cashier-mode').hide();
                          $('.manager-mode').show();
                          dialogItself.close();
                        }
                      } else {
                        $('.response').html('<div class="alert-danger danger-o">'+response+'</div>');
                        $('#s-uname').focus();
                        timeoutmsg();
                      }
                    }
                  });                     
              }
          }]
      });
  }
}

function f1()
{
  if($('#managerkey').is(':checked'))
  {
    if(modemanager==0)
    {
      if(flag==1 && servicecharge==1)
      {
        refundnow();
      }
      else 
      {
        lookup();  
      }
      // lookup(); 
    
    }
    else if(modemanager==2)
    {
      linediscount();
    }
    else if(modemanager==3)
    {
      posreport();
    }
    else if (modemanager==4) 
    {
      refundgcmod();
    }
  }
  else 
  {
    if(mode==5)
    {
      //check if table has gc to reval
      $.ajax({
        url:'../ajax-cashier.php?request=checkrevalgc',
        success:function(data)
        {
          console.log(data);
          var data = JSON.parse(data);
          if(data['msg'] > 0)
          {
            if(flag==0)
            {
              var cashpaymentype=2;
              flag=1;
              BootstrapDialog.show({
                title: 'GC Revalidation Charge',
                closable: true,
                closeByBackdrop: false,
                closeByKeyboard: true,
                cssClass: 'cash-payment',
                message: function(dialog) {
                  var $message = $("<div></div>");
                  var pageToLoad = dialog.getData('pageToLoad');
                  $message.load(pageToLoad);
                  return $message;
                },
                data: {
                  'pageToLoad': '../dialogcashier/revalidationpayment.php',
                },
                onshow: function(dialog) {
                // dialog.getButton('button-c').disable();
                },
                onshown: function(dialog){
                  // setTimeout(function(){                    
                  //   $('#paymentcash').focus();                    
                  //   $('#paymentcash').inputmask("integer", { allowMinus: false,autoGroup: true, groupSeparator: ",", groupSize: 3 }); 
                  //   var amt = $('#payment').val();
                  //   $('.amtdue').val(amt);                    
                  // }, 400);
                },
                onhidden: function(dialog){
                  $('#numOnlyreval').select();
                  flag=0;  
                },  
                buttons: [{
                    icon: 'glyphicon glyphicon-ok ',
                    label: ' Submit',
                    cssClass: 'btn-success',
                    hotkey: 13, 
                    action: function(dialogRef) {
                      var $buttons  = this;
                      $buttons.disable();
                      var cash = parseFloat(removecomma($('#paymentcash').val())), amtdue = parseFloat($('.amtdue').val());
                      if(cash =='')
                      {
                        $('.responsecash').html('<div class="alert alert-danger danger-o" id="_emp_cash">Please input amount.</div>');
                        $buttons.enable();
                        $('#paymentcash').select(); 
                      } 
                      else if(cash<amtdue)
                      {
                        $('.responsecash').html('<div class="alert alert-danger danger-o" id="_emp_cash">Insufficient amount.</div>');
                        $buttons.enable();
                        $('#paymentcash').select(); 
                      }
                      else
                      {
                        cashis = removecomma($('#paymentcash').val());
                        $.ajax({
                          url:'../ajax-cashier.php?request=gcrevalidationpayment',
                          data:{cashis:cashis,amtdue:amtdue},
                          type:'POST',
                          success:function(data)
                          {
                            console.log(data);  
                            var data = JSON.parse(data);
                            if(data['stat'])
                            {                              
                              var total = addCommas(parseFloat(data['total']).toFixed(2));
                              var change = cash - total;
                              // check issue receipt status
                              if(data['receipt'] == 'yes')
                              {
                                $('p.gctitle').html('GC Revalidation Payment');
                                $('.receipt-items').html(data['items']); 
                                $('h3.transactnum span').html(zeroPad(data['transactnum'],4));
                                $('.receipt-footer').html('<table class="table tablefooterrec">'+
                                  '<tr>'+
                                    '<td>Total PHP</td>'+
                                    '<td class="mright "><b>₱ '+addCommas(total)+'</b></td>'+
                                  '</tr>'+
                                  '<tr>'+
                                    '<td>Cash</td>'+
                                    '<td class="mright"><b>₱ '+addCommas(cash.toFixed(2))+'</b></td>'+
                                  '</tr>'+
                                  '<tr>'+
                                    '<td>Change</td>'+
                                    '<td class="mright"><b>₱ '+addCommas(change.toFixed(2))+'</b></td>'+
                                  '</tr>'+
                                  '<tr>'+
                                    '<td>No. of Items:</td>'+
                                    '<td class="mright"><b>'+data['numitems']+'</b></td>'+
                                  '</tr>'+
                                '</table>');
                              }
                              BootstrapDialog.closeAll();
                              var dialog = new BootstrapDialog({
                                message: function(dialogRef){
                                var $message = $('<div>GC Successfully Revalidated.</div>');             
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
                              if(data['receipt']=='yes')
                              {
                                setTimeout(function(){
                                  window.print();
                                  window.onafterprint = function(){
                                     console.log("Printing completed...");
                                     alert('xxx');
                                  }                                                                                        
                                }, 1700);
                              }
                              setTimeout(function(){
                                   $('#numOnlyreval').focus();
                              }, 1500);                             
                              $('.msgreval').val('Change: '+change.toFixed(2));
                              $('._barcodesreval').load('../ajax-cashier.php?request=loadreval');
                            }
                            else 
                            {
                              $('.responsecash').html('<div class="alert alert-danger danger-o" id="_emp_cash">'+data['msg']+'</div>');
                              $('#paymentcash').focus(); 
                            }
                          }
                        });                
                      }

                    }
                }, {
                    icon: 'glyphicon glyphicon-remove-sign',
                    label: ' Cancel',
                    cssClass: 'btn-primary',
                    action: function(dialogItself){
                        dialogItself.close();
                    }
                }]
              });       
            }
          }
          else 
          {
            if(flag==0)
            {
              flag = 1;
              BootstrapDialog.show({
              title:'Warning',
                     message: 'No GC to charge',
                     cssClass: 'login-dialog',  
                     onshow: function(dialogRef){
                          
                    },
                    onshown: function(dialogRef){                 
                      
                    },
                    onhide: function(dialogRef){
                        
                    },
                    onhidden: function(dialogRef){
                        $('input#numOnlyreval').focus();                 
                        flag=0;
                    }            
              });
            }            
          }
        }
      });

    } 
    else if(mode==0)
    {

      if(flag==0)
      {
        $('input#numOnly').focus();
        $('.cashier-mode').hide();
        $('.payment-mode').show();
        $('input#numOnly').prop("disabled", true);
        $('input#numOnly').val('');
        mode = 1;
        return false;
      }
    } 
    else if(mode==1)
    {
      if(flag==1 && customer==1)
      {
        //func searchcustomer
        if(confirm!=1)
        {
          searchCustomer();
          return false;
        }
      }
      else 
      {
        cash();
        return false;
      }
    } 
    else if(mode==3)
    {
      // if(payment==1)
      // {
      //   cashrevalpayment();
      // }
      // else 
      // {
        $.ajax({
          url:'../ajax-cashier.php?request=deletetempandchecktempsales',
          success:function(data)
          {
            var data = JSON.parse(data);
            if(data['stat'])
            {             
              if(flag==0)
              {
                $('.otherincome-mode').hide();
                $('.revalidation').show();  
                $('.content-sales').hide();
                $('.content-revalidate').show();
                $('input#numOnlyreval').focus();
                $('._barcodesreval').load('../ajax-cashier.php?request=loadreval');
                $('.msgreval').val('');
                $('._cashier_totalreval').val('0.00');
                $('.noitemsreval').val(0);
                mode = 5;
              }
            }
            else 
            {
              if(flag==0)
              {
                flag=1;
                BootstrapDialog.show({
                title:'Warning',
                     message: data['msg'],
                     cssClass: 'login-dialog',  
                     onshow: function(dialogRef){
                          
                    },
                    onshown: function(dialogRef){                 
                      
                    },
                    onhide: function(dialogRef){
                        
                    },
                    onhidden: function(dialogRef){
                        $('input#numOnly').focus();                 
                        flag=0;
                    }            
                });
              }
            }
          }
        });

      // }
      // return false;
    }
    else if(mode==4)
    {
      supervisorlogin(1);
    }
    else if(mode==6)
    {
      posreport();
    }
    // end mode==3       
  }
}


function f2()
{ 
  if($('#managerkey').is(':checked'))
  {
    if(modemanager==0)
    {
      voidAll();
    }
    else if (modemanager==3) 
    {
      cashierReport();
    }
    else if (modemanager==2) 
    {
      trandis();
    }
    else if (modemanager==4)
    {
      //servicechargeshow();
      voidlinerefund();
    }
    // if(report==0)
    // {
    //   voidAll();
    // }
    // else 
    // {
    //   alert('xxx');
    // }
  }
  else 
  {
    if(mode==0)
    {
      voidline();
    } 
    else if(mode==1)
    {
      creditcard();
    }
    else if(mode==3 && flag==0)
    {
      $('input#numOnly').prop("disabled",false);
      $('input#numOnly').focus();
      $('.otherincome-mode').hide();
      $('.cashier-mode').show();
      mode=0;          
    }
    else if(mode==4)
    {
      supervisorlogin(2);
    }
    else if(mode==5)
    {
      if(flag==0)
      {
        voidlinereval();
      }
    } 
    else if(mode==6)
    {
      cashierReport();
    }
  }
}

function f3()
{
  if($('#managerkey').is(':checked'))
  {
    if(modemanager==3)
    {
      gcitemsreport();
    }
    else if(modemanager==0)
    {
      //gcrefund();
      //alert(mode);

      if(flag==0)
      {
        $.ajax({
          url:'../ajax-cashier.php?request=checkIFhasTempSales',
          success:function(data)
          {
            var data = JSON.parse(data);
            if(data['st'])
            {
              $.ajax({
                url:'../ajax-cashier.php?request=removerefund',
              });
              $('.content-sales').hide();
              $('.manager-mode').hide();
              $('.returngc').show();
              $('.content-returngc').show();
              $('input#numOnlyreturn').focus();
              //remove scanned gc refund
              modemanager=4
            }
            else 
            {
              if(flag==0)
              {
                flag=1;
                BootstrapDialog.show({
                title:'Warning',
                     message: data['msg'],
                     cssClass: 'login-dialog',  
                     onshow: function(dialogRef){
                          
                    },
                    onshown: function(dialogRef){                 
                      
                    },
                    onhide: function(dialogRef){
                        
                    },
                    onhidden: function(dialogRef){
                        $('input#numOnly').focus();                 
                        flag=0;
                    }            
                });
              }
            }
          }

        });

      }

    }
    else if(modemanager==2)
    {
      removealldiscline();
    }
    else if(modemanager==4) 
    {
      //voidlinerefund();
      $.ajax({
        url:'../ajax-cashier.php?request=removerefund'
      });
      $('.returngc').hide();
      $('.content-returngc').hide();
      $('.content-sales').show();
      $('.manager-mode').show();
      $('input.totdenomref, input.totsubdiscref, input.totlinedisref, input.noitemsref, input.serviceref').val('0');
      $('input._cashier_totalrefund').val('0.00');
      $('table tbody._barcodesrefund').load('../ajax-cashier.php?request=refreshrevaltable');
      $('input#numOnlyreturn').val('');
      modemanager=0;

    }
    // if(report==0)
    // {
    //   gcrefund();
    // }
    // else 
    // {
    //   $('.manager-mode').show();
    //   $('.reports').hide();
    //   report=0;
    // }
  }
  else 
  {
    if(mode==0)
    {
      supervisormode();
      mode=1;
      // $('input#numOnly').prop("disabled",true);
      // $('.otherincome-mode').show();
      // $('.cashier-mode').hide();
      // $('input#numOnly').val('');
      // mode=3;          
    } 
    else if(mode==1)
    {
      headoffice();
    }
    else if(mode==4)
    {
      supervisorlogin(3);
    }
    else if(mode==5)
    {
      if(flag==0)
      {
        // $.ajax({
        //   url
        // });
        $('.otherincome-mode').show();
        $('.revalidation').hide();  
        $('.content-sales').show();
        $('.content-revalidate').hide();
        $('input#numOnly').focus();
        $('.msgreval').val('');   
        $('input#numOnlyreval ').val('');           
        mode = 3;
      }
    }
    else if(mode==6)
    {
      gcitemsreport();
    }
  } 
}

function f4()
{
  if($('#managerkey').is(':checked'))
  {
    if(modemanager==0)
    {
      if(flag==0)
      {
        $('.manager-mode').hide();
        $('.discounts').show();
        modemanager=2;      
      }
    }
    else if(modemanager==2)
    {
      removedocdisc();
    }
    else if(modemanager==4)
    {
      //gcrefund back
      // $.ajax({
      //   url:'../ajax-cashier.php?request=removerefund'
      // });
      // $('.returngc').hide();
      // $('.content-returngc').hide();
      // $('.content-sales').show();
      // $('.manager-mode').show();
      // $('input.totdenomref, input.totsubdiscref, input.totlinedisref, input.noitemsref, input.serviceref').val('0');
      // $('input._cashier_totalrefund').val('0.00');
      // $('table tbody._barcodesrefund').load('../ajax-cashier.php?request=refreshrevaltable');
      // $('input#numOnlyreturn').val('');
      // modemanager=0;
    }
    else if(modemanager==3)
    {
      $('.manager-mode').show();
      $('.reports').hide();  
      modemanager=0;  
    }
    // discount();
    // $('.manager-mode').hide();
    // $('.discounts').show();
    // discounts=1;
  }
  else 
  {
    if(mode==0)
    {
      // supervisormode();
      // mode=1;
      if(flag==0)
      {
        $('input#numOnly').val('');
        $('input#numOnly').prop("disabled",true);
        $('.cashier-mode').hide();
        $('.discounts').show();
        mode=4;
      }

    } 
    else if(mode==1)
    {
      subsadmin();
    }
    else if(mode==4)
    {
      supervisorlogin(4);
    }      
    else if(mode==6)
    {
      settlementandshortage();
    }
  }       
}


function f5()
{
  if($('#managerkey').is(':checked'))
  {
    if(modemanager==0)
    {
      if(flag==0)
      {
        $('.manager-mode').hide();
        $('.reports').show();
        modemanager=3;              
      }
    }
    else if(modemanager==2)
    {
      $('.manager-mode').show();
      $('.discounts').hide();
      modemanager=0;         
    }
    // xyreports();
    // if(discounts==0)
    // {
    //   $('.manager-mode').hide();
    //   $('.reports').show();
    //   report=1;
    // }
    // else 
    // {
    //   $('.manager-mode').show();
    //   $('.discounts').hide();
    //   report=0;      
    // }
  }
  else 
  {
    if(mode==0)
    {
      // if(flag==0)
      // {
      //   $('input#numOnly').val('');
      //   $('input#numOnly').prop("disabled",true);
      //   $('.cashier-mode').hide();
      //   $('.discounts').show();
      //   mode=4;
      // }
      $('input#numOnly').prop("disabled",true);
      $('.otherincome-mode').show();
      $('.cashier-mode').hide();
      $('input#numOnly').val('');
      mode=3;  
    } 
    else if(mode==1)
    {
      if(flag==0)
      { 
        $('input#numOnly').prop("disabled",false);
        $('input#numOnly').focus(); 
        $('.cashier-mode').show();
        $('.payment-mode').hide();
        mode=0;
      }
    } 
    else if(mode==4)
    {
      $('input#numOnly').prop("disabled",false);
      $('input#numOnly').focus(); 
      $('.cashier-mode').show();
      $('.discounts').hide();
      mode=0;      
    }  
    else if(mode==6)
    {      
      endofday();
    }         
  } 
}

function f6()
{
  if($('#managerkey').is(':checked'))
  {
    if(modemanager==0)
    {
      endofday();
    }
  }
  else
  {
    if(mode==0)
    {
      if(flag==0)
      {
        $('input#numOnly').focus();
        $('.cashier-mode').hide();
        $('.reports-cahshier').show();
        $('input#numOnly').prop("disabled", true);
        $('input#numOnly').val('');
        mode = 6;
        return false;
      }    
    }
    else if(mode==6)
    {      
      $('.cashier-mode').show();
      $('.reports-cahshier').hide();
      $('input#numOnly').prop("disabled", false);
      $('input#numOnly').val('');
      $('input#numOnly').focus();
      mode = 0;
      return false;      
    }

  }
}

function f7()
{
  if($('#managerkey').is(':checked'))
  {
    if(modemanager==0)
    {
      settlementandshortage();
    }
  }
  else 
  {
    
  }
}

function settlementandshortage()
{
  $.ajax({
    url:'../ajax-cashier.php?request=checkIFhasTempSales',
    success:function(data)
    {
      var data  = JSON.parse(data);
      if(data['st'])
      {
        $.ajax({
          url:'../ajax-cashier.php?request=checkeostrans',
          success:function(data1)
          {
            console.log(data1);
            var data1 = JSON.parse(data1);
            
            if(data1['st'])
            {
              if(flag==0){
                flag=1;
                // shortage and settlement
                BootstrapDialog.show({
                  title: 'Shortage / Overage of Settlement',
                  closable: true,
                  closeByBackdrop: false,
                  closeByKeyboard: true,
                  cssClass: 'cash-payment',
                  message: function(dialog) {
                    var $message = $("<div><img src='../assets/images/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
                    var pageToLoad = dialog.getData('pageToLoad');
                  setTimeout(function(){
                    $message.load(pageToLoad); 
                  },1000);
                  return $message;
                  },
                  data: {
                    'pageToLoad': '../dialogcashier/shortageandsettlement.php',
                  },
                  onshow: function(dialog) {  
                  // dialog.getButton('button-c').disable();
                  },
                  onshown: function(dialog){                  
                  },
                  onhidden: function(dialog){
                      $('#numOnly').focus();    
                      flag = 0;
                  },
                  buttons: [{
                      icon: 'glyphicon glyphicon-ok ',
                      label: ' Submit',
                      cssClass: 'btn-success',
                      hotkey: 13, 
                      action: function(dialogRef) {
                        var $button = this;
                        $button.disable();
                        $('.responseshortage').html('');
                        $hasQty = false;
                        var formURL = $('form#fshortageoverage').attr('action'), formDATA = $('form#fshortageoverage').serialize();
                        if($('#totsht').val()==undefined)
                        {
                          $button.enable();
                          $('.focustxt').select(); 
                          return false;
                        }
                        //check if user inputted value
                        $('.denset').each(function(){
                          var den = $(this).val();
                          den = den.replace(/,/g , "");
                          den  = isNaN(den) ? 0 : den;
                          if(den!=0)
                          {
                            $hasQty = true;
                          }
                        });

                        if(!$hasQty)
                        {
                          $('.responseshortage').html('<div class="row"><div class="col-sm-12"><div class="form-group"><div class="alert alert-danger danger-o">Please input denomination qty.</div></div></div></div>');
                          $button.enable();
                          $('.focustxt').select(); 
                          return false;
                        }
                        BootstrapDialog.show({
                          title: 'Confirmation',
                            message: 'Would you like to proceed?',
                            closable: true,
                            closeByBackdrop: false,
                            closeByKeyboard: true,
                            cssClass: 'confirmationpayment',    
                            onshow: function(dialog) {
                                // dialog.getButton('button-c').disable();
                            },
                            onhidden: function(dialogRef){
                              $('.focustxt').select(); 
                              $button.enable();

                            },
                            buttons: [{
                                icon: 'glyphicon glyphicon-ok-sign',
                                label: 'Yes',
                                cssClass: 'btn-success',
                                hotkey: 13,
                                action:function(dialogItself1){
                                  var $buttoncon = this;
                                  $buttoncon.disable();
                                    $.ajax({
                                      url:formURL,
                                      type:'POST',
                                      data:formDATA,
                                      beforeSend:function(){

                                      },
                                      success:function(data2){
                                        console.log(data2);

                                        var data2 = JSON.parse(data2);
                                        if(data2['st'])
                                        {
                                          BootstrapDialog.closeAll();
                                          var dialog = new BootstrapDialog({
                                            message: function(dialogRef){
                                            var $message = $('<div>Transaction successfully saved.</div>');             
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
                                            window.location.href = 'createshortageoveragereport.php?id='+data2['id'];                                                      
                                          }, 1700);
                                        }
                                        else 
                                        {
                                          dialogItself1.close();
                                          $('.focustxt').select(); 
                                          $button.enable();
                                          $('.responseshortage').html('<div class="row"><div class="col-sm-12"><div class="form-group"><div class="alert alert-danger danger-o">'+data2['msg']+'</div></div></div></div>');
                                        }
                                      }
                                    }); 

                                }
                            }, {
                              icon: 'glyphicon glyphicon-remove-sign',
                                label: 'No',
                                action: function(dialogItself){
                                    $('.focustxt').select(); 
                                    $button.enable();
                                    dialogItself.close();
                                }
                            }]
                        });  

                      }
                  }, {
                      icon: 'glyphicon glyphicon-remove-sign',
                      label: ' Cancel',
                      cssClass: 'btn-primary',
                      action: function(dialogItself){
                          dialogItself.close();
                      }
                  }]
                });
              }                
            }
            else 
            {
              if(flag==0)
              {
                flag=1;
                BootstrapDialog.show({
                title:'Warning',
                     message: '<div class="dialog-alert">'+data1['msg']+'</div>',
                     cssClass: 'login-dialog',  
                     onshow: function(dialogRef){
                          
                    },
                    onshown: function(dialogRef){                 
                      
                    },
                    onhide: function(dialogRef){
                        
                    },
                    onhidden: function(dialogRef){               
                        flag=0;
                    }            
                });          
              }
            }          
          }
        });
      }
      else 
      {
        if(flag==0)
        {
          flag=1;
           BootstrapDialog.show({
           title:'Warning',
                 message: '<div class="dialog-alert">'+data['msg']+'</div>',
                 cssClass: 'login-dialog',  
                 onshow: function(dialogRef){
                      
                },
                onshown: function(dialogRef){                 
                  
                },
                onhide: function(dialogRef){
                    
                },
                onhidden: function(dialogRef){               
                    flag=0;
                }            
            });          
        }
      }
    }
  });
}

function creditcard()
{
  $.ajax({
    url:'../ajax-cashier.php?request=getAmtDueAndDiscount',
    success:function(payable)
    {
      var payable = JSON.parse(payable);
      if(payable['amtdue']>0)
      {
        if(flag==0)
        {
          flag=1;
          BootstrapDialog.show({              
            title: 'Credit Card Payment',
            closable: true,
            closeByBackdrop: false,
            closeByKeyboard: true,
            cssClass: 'card-payment',
            message: function(dialog) {
              var $message = $("<div><img src='../assets/images/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
              var pageToLoad = dialog.getData('pageToLoad');
            setTimeout(function(){
              $message.load(pageToLoad); 
            },1000);
            return $message;
            },
            data: {
              'pageToLoad': '../dialogcashier/ccredit.php',
            },
            onshow: function(dialogRef){
                
            },
            onshown: function(dialogRef){
            },
            onhide: function(dialogRef){
                
            },
            onhidden: function(dialogRef){
                $('#numOnly').focus();
                flag=0;
            },
              buttons: [{
                  icon: 'glyphicon glyphicon-ok ',
                  label: ' Submit',
                  cssClass: 'btn-success',
                  hotkey: 13, // Enter.
                  action: function(dialogItself) {
                     $('.response').html('');
                    var $button = this;
                    $button.disable();
                    
                    var total_charge = parseFloat($('#_cashier_total > input#total_charge').val());
                    var charge = { 'charge' : payable['amtdue'] };                      

                    var formUrl= $('form#ccard').attr('action'), formData = $('form#ccard').serialize() + '&' + $.param(charge);
                    var emptyfield = false;                            
                    $("form#ccard :input").each(function() {
                      if($(this).val() === "")
                      emptyfield = true;
                    });        
                    if(!emptyfield){

                      if(validateDOB($('#cardexpired').val().trim()))
                      {
                        // if(dateToday() <= $('#cardexpired').val().trim())
                        if(validDate(dateToday(),$('#cardexpired').val().trim()))
                        {                       
                          $.ajax({
                            url:formUrl,
                            type:'POST',
                            data:formData,
                            beforeSend:function(){

                            },
                            success:function(data){
                              console.log(data);

                              var data = JSON.parse(data);

                              if(data['stat']){

                                //creditcardajax
                                dialogItself.close();
                                var total = parseFloat(data['amt_due']);
                                var linedisc = parseFloat(data['linedisc']);
                                var docdisc = parseFloat(data['docdisc']);
                                var stotal = parseFloat(data['stotal']);
                                linedisc = addCommas(linedisc.toFixed(2));
                                docdisc = addCommas(docdisc.toFixed(2));
                                total = addCommas(total.toFixed(2));
                                stotal = addCommas(stotal.toFixed(2));

                                if(data['receipt']=='yes')
                                {
                                  $('h3.transactnum span').html(data['transactnum']);
                                  $('.receipt-footer').html('<table class="table tablefooterrec">'+
                                  '<tr>'+
                                    '<td>Subtotal</td>'+
                                    '<td class="mright"><b>₱ '+stotal+'</b></td>'+
                                  '</tr>'+
                                  '<tr>'+
                                    '<td>Line Discount</td>'+
                                    '<td class="mright"><b>₱ '+linedisc+'</b></<td>'+
                                  '</tr>'+
                                  '<tr>'+
                                    '<td>Doc Discount</td>'+
                                    '<td class="mright mrightdis"><b>₱ '+docdisc+'</b></td>'+
                                  '</tr>'+
                                  '<tr>'+
                                    '<tr>'+
                                      '<td>Total PHP</td>'+ 
                                      '<td class="mright"><b>₱ '+total+'</b></td>'+
                                    '</tr>'+
                                    '<tr>'+
                                      '<td>Cards</td>'+
                                      '<td class="mright"><b>₱ '+data['cards']+'</b></td>'+
                                    '</tr>'+
                                    '<tr>'+
                                      '<td>'+data['creditcard']+'</td>'+
                                      '<td></td>'+
                                    '</tr>'+
                                    '<tr>'+
                                      '<td class="boldie">'+data['cardnumber']+'</td>'+
                                      '<td></td>'+
                                    '</tr>'+
                                    '<tr>'+
                                      '<td>No. of Items:</td>'+
                                      '<td class="mright"><b>'+data['numitems']+'</b></td>'+
                                    '</tr>'+
                                    '<tr>'+
                                  '</table>');
                                  window.print();
                                }
                                  $('span.cashr').text('₱ 0.00');
                                  $('span.changer').text('₱ 0.00');
                                  $('._barcodes').load('../ajax-cashier.php?request=load');
                                  //$('.receipt-items').load('../ajax-cashier.php?request=receipt');
                                  $('.cashier-mode').show();
                                  $('.payment-mode').hide();
                                  $('input.msgsales').val('Last Transaction was Credit Card.');
                                  $('input#numOnly').prop('disabled',false);
                                  setTimeout(function(){
                                    $('input#numOnly').focus();
                                  },1000)
                                  mode = 0;
                              } 
                              else 
                              {
                                $('.response').html('<div class="alert alert-danger danger-o">'+data['msg']+'</div>');
                                $( '.select-credit select#credit' ).focus();
                                $button.enable();
                              }
                            }
                          });                          
                        }
                        else 
                        {
                          $('.response').html('<div class="alert alert-danger danger-o">Card Already Expired.</div>');
                          $( '.select-credit select#credit' ).focus();
                          $button.enable();                       
                        }
                      }
                      else 
                      {
                        $('.response').html('<div class="alert alert-danger danger-o">Card Expiration Date is invalid.</div>');
                        $( '.select-credit select#credit' ).focus();
                        $button.enable();  
                      }                    

                    } else {
                      $('.response').html('<div class="alert alert-danger danger-o">Please select credit card / Fill-up all fields.</div>');
                      $( '.select-credit select#credit' ).focus();
                      $button.enable();
                    }                        
                  }
              }, {
                  icon: 'glyphicon glyphicon-remove-sign',
                  label: ' Cancel',
                  cssClass: 'btn-primary',
                  action: function(dialogItself){
                      dialogItself.close();
                  }
              }]
          });

        }
      }
      else 
      {
        if(payable['amtdue']==0)
        {
          if(flag==0){
            flag=1;
             BootstrapDialog.show({
             title:'Warning',
                   message: 'No GC to charge',
                   cssClass: 'login-dialog',  
                   onshow: function(dialogRef){
                        
                  },
                  onshown: function(dialogRef){                 
                    
                  },
                  onhide: function(dialogRef){
                      
                  },
                  onhidden: function(dialogRef){
                      $('#numOnly').focus();                 
                      flag=0;
                  }            
              });
          }
        }
        else 
        {
          if(flag==0){
            flag=1;
             BootstrapDialog.show({
             title:'Warning',
                   message: 'Amount Due is negative.',
                   cssClass: 'login-dialog',  
                   onshow: function(dialogRef){
                        
                  },
                  onshown: function(dialogRef){                 
                    
                  },
                  onhide: function(dialogRef){
                      
                  },
                  onhidden: function(dialogRef){
                      $('#numOnly').focus();                 
                      flag=0;
                  }            
              });
          }
        }
      }
    }
  }); 
}

function endofday()
{
  $.ajax({
    url:'../ajax-cashier.php?request=checkIFhasTempSales',
    success:function(data)
    {
      var data  = JSON.parse(data);
      if(data['st'])
      {
        $.ajax({
          url:'../ajax-cashier.php?request=checkeodtrans',
          success:function(data1)
          {
            var data1 = JSON.parse(data1);

            if(data1['st'])
            {
              if(flag==0){
                flag=1;
                  //end of day
                  BootstrapDialog.show({
                    title: 'Confirmation',
                      message: '<div class="dialog-alert">Perform end of day?</div>',
                      closable: true,
                      closeByBackdrop: false,
                      closeByKeyboard: true,
                      onshow: function(dialog) {
                          // dialog.getButton('button-c').disable();
                      },

                      onhidden: function(dialog){
                        flag=0;
                      },
                      buttons: [{
                          icon: 'glyphicon glyphicon-ok-sign',
                          label: 'Yes',
                          cssClass: 'btn-success',
                          hotkey: 13,
                          action:function(dialogItself){                  
                            dialogItself.close();
                            $.ajax({
                              url:'../ajax-cashier.php?request=endofdaypos',
                              beforeSend:function(){

                              },
                              success:function(data2){
                                window.location.href = 'createodreport.php';                    
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
              if(flag==0)
              {
                flag=1;
                BootstrapDialog.show({
                title:'Warning',
                     message: '<div class="dialog-alert">'+data1['msg']+'</div>',
                     cssClass: 'login-dialog',  
                     onshow: function(dialogRef){
                          
                    },
                    onshown: function(dialogRef){                 
                      
                    },
                    onhide: function(dialogRef){
                        
                    },
                    onhidden: function(dialogRef){               
                        flag=0;
                    }            
                });          
              }
            }

          
          }
        });
      }
      else 
      {
        if(flag==0)
        {
          flag=1;
           BootstrapDialog.show({
           title:'Warning',
                 message: '<div class="dialog-alert">'+data['msg']+'</div>',
                 cssClass: 'login-dialog',  
                 onshow: function(dialogRef){
                      
                },
                onshown: function(dialogRef){                 
                  
                },
                onhide: function(dialogRef){
                    
                },
                onhidden: function(dialogRef){               
                    flag=0;
                }            
            });          
        }
      }
    }
  });
}


function f8()
{
  if($('#managerkey').is(':checked'))
  {
    if(modemanager==0)
    {
      supervisorlogout();
    }
    //return  false;
  }
  else 
  {
    if(mode==0)
    {
      logoutuser();
    } 
  }
}

function logoutuser()
{
  if(flag==0){
    flag=1;
    $('#numOnly').val('');
    BootstrapDialog.show({
      title: 'Confirmation',
        message: 'Are you sure you want to logout?',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: true,
        onshow: function(dialog) {
            // dialog.getButton('button-c').disable();
        },
        onhidden: function(dialogRef){
          $('#numOnly').focus();
          flag=0;
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Yes',
            cssClass: 'btn-success',
            hotkey: 13,
            action:function(dialogItself){                  
              dialogItself.close();
              window.location='login.php?action=logout';
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

function updateTime() {
var today=new Date();
    var hh=today.getHours();
    var mm=today.getMinutes();
    var ss=today.getSeconds();
    // h = h % 12;
    // h= h ? h : 12; // the hour '0' should be '12'
    // var ampm=h >= 12 ? 'pm' : 'am';
    // m = m < 10 ? '0'+m : m;
    // s = s <10 ? '0'+s: s;
        var ampm;
    // This line gives you 12-hour (not 24) time
    if (hh >= 12) { hh = hh - 12; ampm = "PM"; } else { ampm = "AM"; }
    // These lines ensure you have two-digits
    if (hh < 10) { hh = "0" + hh; }
    if (mm < 10) { mm = "0" + mm; }
    if (ss < 10) { ss = "0" + ss; }
    //if (ss < 10) {ss = "0"+ss;}
    // This formats your string to HH:MM:SS
    (hh == "00") ? hh = "12" : hh;
    var time = hh + ":" + mm + ":" + ss + " " + ampm;
// add a zero in front of numbers<10

setTimeout("updateTime()",1000);
document.getElementById('time').innerHTML= time;
}
updateTime();

function removecomma(nStr){
  return nStr.replace(/,/g , "");
} 

function d(cash)
{
  var payment = removecomma($('#paymentcash').val());
  if(payment.trim() =='')
  {
    payment = 0;
  }
  var total = parseFloat(cash) + parseFloat(payment);

  $('#paymentcash').val(total); 
  $('#paymentcash').focus(); 
}

function clearcash()
{
  $('#paymentcash').val(0.00); 
  $('#paymentcash').select(); 
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

function timeoutmsg(){
    setTimeout(function(){
      $('.response').html('');
    }, 4000);
}

function supervisorlogout()
{
  if(flag==0){
    flag=1;
    $.ajax({
      url:'../ajax-cashier.php?request=supervisorlogout',
      success:function(response){
        var res = response.trim();
        if(res=='success'){
          $('#managerkey').prop('checked', false);
          $('.manager-mode').hide();
          $('.cashier-mode').show();
          $('input#numOnly').prop('disabled',false);
          $('input#numOnly').focus();
        }
      }
    });
    flag=0;
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

  // if(dValue[1]<dToday[1])
  // {
  //   return false;
  // }

  return result;

  // var pattern = /^\d{2}$/;

  // if (dValue[0] < 1 || dValue[0] > 12)
  //     result = true;

  // if (!pattern.test(dValue[0]) || !pattern.test(dValue[1]))
  //     result = true;

  // if (dValue[2])
  //     result = true;  
}

function validDate1(dValue)
{
  dValue = dValue.split('/');
  // if(!isNaN(dValue[0]) && !isNaN(dValue[1]) && !isNaN(dValue[2]))
  // {
  //   return true;
  // }
  // else 
  // {

  // }
  if(isNaN(dValue[0]) || isNaN(dValue[1]) || isNaN(dValue[2]))
  {
    return true;
  }
  else 
  {
    return false;
  }
}

function dateToday()
{
  var today = new Date();
  var dd = today.getDate();
  var mm = today.getMonth() + 1; //January is 0!
  var yyyy = today.getFullYear();

  if(dd < 10) {
      dd = '0' + dd
  } 

  if(mm < 10) {
      mm = '0' + mm
  } 

  today = mm + '/' + dd + '/' + yyyy;
  return today;

}

function discountype(val)
{
  var den = parseInt($('#den').val());
  if(val=='')
  {
     $('input[name=percent]').prop('readonly','readonly');
     $('input[name=amount]').prop('readonly','readonly');
     $('input[name=percent]').val('0');
     $('input[name=amount]').val('0');
     $('select[name=discountype]').focus();
  }
  else if(val==1)
  {
    $('input[name=percent]').prop('readonly','');
    $('input[name=amount]').prop('readonly','readonly');
    $('input[name=percent]').val('0');
    $('input[name=amount]').val('0');
    $('input[name=percent]').select();
  }
  else if(val==2)
  {
    $('input[name=percent]').prop('readonly','readonly');
    $('input[name=amount]').prop('readonly','');
    $('input[name=percent]').val('0');
    $('input[name=amount]').val('0');
    $('input[name=amount]').select();
  }
  $('#tot').val(addCommas(den.toFixed(2)))
  $('#totval').val(den);

}

function linedispercent(dscnt)
{
  
  var den = $('#den').val();
  var dscnt = removecomma(dscnt);
  var prnt = 0;
  if(dscnt<100)
  {
    prnt = dscnt / 100;
    to = den * prnt;
    to = addCommas(to.toFixed(2));
    $('#amount').val(to);
    l = parseFloat(den - to);
    $('#totval').val(l);
    $('#tot').val(addCommas(l.toFixed(2)));
    $('#conpercent').val(prnt); 
  }
  else 
  {
    $('#percent').val(0);
    $('#amount').val(0.00);
    $('#conpercent').val(0);
  }
}

function lineamount(dscnt)
{
  var dscnt = removecomma(dscnt);
  var den = parseInt($('#den').val());

  if(dscnt>=den)
  {
    $('#amount').val(0);
  }
  else 
  {
    l = parseFloat(den - dscnt);
    $('#totval').val(l);
    $('#tot').val(addCommas(l.toFixed(2)));   
  }
}

function discountypedoc(val)
{
  var amt = parseFloat($('#amtdue').val());
  if(val=='')
  {
     $('input[name=percent]').prop('readonly','readonly');
     $('input[name=amount]').prop('readonly','readonly');
     $('input[name=percent]').val('0');
     $('input[name=amount]').val('0');
     $('select[name=discountype]').focus();
  }
  else if(val==1)
  {
    $('input[name=percent]').prop('readonly','');
    $('input[name=amount]').prop('readonly','readonly');
    $('input[name=percent]').val('0');
    $('input[name=amount]').val('0');
    $('input[name=percent]').select();
  }
  else if(val==2)
  {
    $('input[name=percent]').prop('readonly','readonly');
    $('input[name=amount]').prop('readonly','');
    $('input[name=percent]').val('0');
    $('input[name=amount]').val('0');
    $('input[name=amount]').select();
  }
  $('#tot').val(addCommas(amt.toFixed(2)))
  $('#totval').val(amt);
}

function docdispercent(dscnt)
{
  var amt = $('#amtdue').val();
  dscnt = removecomma(dscnt);
  var prnt = 0;
  if(dscnt<100)
  {
    prnt = dscnt / 100; 
    cprnt = amt * prnt;
    cprnt = addCommas(cprnt.toFixed(2));
    $('#amount').val(cprnt);
    l = parseFloat(amt - cprnt);
    $('#totval').val(l);
    $('#tot').val(addCommas(l.toFixed(2)));
    $('#conprnt').val(prnt);
  }
  else 
  {
    $('#percent').val(0);
    $('#amount').val(0.00);
    $('#conprnt').val(0);
  }
  // var amt = $('#amtdue').val();
  // t = parseFloat(amt*dscnt);
  // if(t >= amt)
  // {
  //   $('#percent').val(0);
  // }
  // else 
  // {
  //   to = addCommas(t.toFixed(2));
  //   $('#amount').val(to);
  //   l = parseFloat(amt - t);
  //   $('#totval').val(l);
  //   $('#tot').val(addCommas(l.toFixed(2)));   
  // }
}

function docamount(dscnt)
{
  var dscnt = parseFloat(removecomma(dscnt));
  var amt = parseFloat(removecomma($('#amtdue').val()));
  if(dscnt>=amt)
  {
    $('#amount').val(0);
    $('#totval').val(amt);
    $('#tot').val(addCommas(amt.toFixed(2)));
  }
  else 
  {
    dscnt = parseFloat(dscnt);
    dscnt = isNaN(dscnt) ? 0 : dscnt;
    l = parseFloat(amt - dscnt);
    $('#totval').val(l);
    $('#tot').val(addCommas(l.toFixed(2))); 
  }
}

function removealldiscline()
{
  $.ajax({
    url:'../ajax-cashier.php?request=checkifhaslinedisc',
    success:function(datac)
    {
      var datac = JSON.parse(datac);
      if(datac['stat'])
      {
        BootstrapDialog.show({
            title: 'Confirmation',
            closable: true,
            closeByBackdrop: false,
            closeByKeyboard: true,
            message: function(dialog) {
            var $message = $("<div><img src='../assets/images/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
            var pageToLoad = dialog.getData('pageToLoad');
            setTimeout(function(){
              $message.load(pageToLoad); 
            },1000);
            return $message;
            },
            data: {
              'pageToLoad': '../dialogcashier/confirmation.php?msg=2',
            },
            cssClass: 'confirmation',           
            onshown: function(dialogRef){                   
            },
            onhidden: function(dialogRef){
               $('#linedis').focus();                  
            },
            buttons: [{
                icon: 'glyphicon glyphicon-ok ',
                label: '  Submit',
                cssClass: 'btn-primary',
                hotkey: 13, // Enter.
                action: function(dialogItself) { 
                  var $button = this;    
                  var flag = $('.flag').val();
                  if(flag!=undefined)
                  {
                    BootstrapDialog.closeAll();  
                    $.ajax({
                      url:'../ajax-cashier.php?request=removealldiscline',
                      success:function(data)
                      {                                                                 
                        var data = JSON.parse(data);
                        if(data['stat'])
                        {                     
                          $button.disable();
                          updateAll();
                          $('._barcodes').load('../ajax-cashier.php?request=load');                          
                          alert('All Line Discount Successfully Removed.');
                        }
                        else 
                        {
                          alert(data['msg']);
                        }
                      }
                    });
                  }
                }            
            },{
              icon: 'glyphicon glyphicon-remove-sign',
              label: ' Cancel',
              cssClass: 'btn-default',
              action: function(dialogItself){
                  dialogItself.close();
              }
            }]
        }); 
      }
      else 
      {
        alert(datac['msg']);
      }
    }
  });

}

function removedocdisc()
{
  //check first if this cashier has document discount
  $.ajax({
    url:'../ajax-cashier.php?request=checkifhasdocdisc',
    success:function(datac)
    {
      var datac = JSON.parse(datac);
      if(datac['stat'])
      {
        BootstrapDialog.show({
            title: 'Confirmation',
            closable: true,
            closeByBackdrop: false,
            closeByKeyboard: true,
            message: function(dialog) {
            var $message = $("<div><img src='../assets/images/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
            var pageToLoad = dialog.getData('pageToLoad');
            setTimeout(function(){
              $message.load(pageToLoad); 
            },1000);
            return $message;
            },
            data: {
              'pageToLoad': '../dialogcashier/confirmation.php?msg=1',
            },
            cssClass: 'confirmation',           
            onshown: function(dialogRef){                   
            },
            onhidden: function(dialogRef){
               $('#linedis').focus();                  
            },
            buttons: [{
                icon: 'glyphicon glyphicon-ok ',
                label: '  Submit',
                cssClass: 'btn-primary',
                hotkey: 13, // Enter.
                action: function(dialogItself) { 
                  var $button = this;    
                  var flag = $('.flag').val();
                  if(flag!=undefined)
                  {
                    BootstrapDialog.closeAll();  
                    $.ajax({
                      url:'../ajax-cashier.php?request=removetransactiondisc',
                      success:function(data)
                      {                                                                 
                        var data = JSON.parse(data);
                        if(data['stat'])
                        {                     
                          $button.disable();    
                          updateAll();
                          alert('Subtotal Discount Successfully Removed.');                      
                        }
                        else 
                        {
                          alert(data['msg']);
                        }
                      }
                    });
                  }
                }            
            },{
              icon: 'glyphicon glyphicon-remove-sign',
              label: ' Cancel',
              cssClass: 'btn-default',
              action: function(dialogItself){
                  dialogItself.close();
              }
            }]
        });     
      }
      else 
      {
        alert(datac['msg']);
      }
    }
  });
}

function updateAll()
{
  $.ajax({
    type:"POST",
    url:"../ajax-cashier.php?request=totals",
    success:function(data)
    {
      var data = JSON.parse(data);
      $('.sbtotal').val(data['sbtotal']);
      $('._cashier_total').val(data['amtdue']);
      $('.linediscount').val(data['linedisc']);
      $('.docdiscount').val(data['docdiscount']);
      $('.noitems').val(data['noitems']);
    }
  });
}

function posreport()
{
  $.ajax({
    url:'../ajax-cashier.php?request=getAmtDueAndDiscount',
    success:function(payable)
      {
        var payable = JSON.parse(payable);
        if(!payable['amtdue']>0)
        {
          if(flag==0)
          {
            flag=1;
            BootstrapDialog.show({              
              title: 'Generate Terminal Report',
              closable: true,
              closeByBackdrop: false,
              closeByKeyboard: true,
              cssClass: 'posreport',
              message: function(dialog) {
                var $message = $("<div><img src='../assets/images/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
                var pageToLoad = dialog.getData('pageToLoad');
              setTimeout(function(){
                $message.load(pageToLoad); 
              },1000);
              return $message;
              },
              data: {
                'pageToLoad': '../dialogcashier/posreport.php',
              },
              onshow: function(dialogRef){ 
              },
              onshown: function(dialogRef){
              },
              onhidden: function(dialogRef){
                  $('#numOnly').focus();
                  flag=0;
              },
                buttons: [{
                    icon: 'glyphicon glyphicon-ok ',
                    label: ' Submit',
                    cssClass: 'btn-success',
                    hotkey: 13, // Enter.
                    action: function(dialogItself) {
                       $('.response').html('');
                      var $button = this;
                      $button.disable();

                      if($('input[name=start]').val()!=undefined)
                      {
                        var d1 = $('input[name=start]').val();
                        var d2 = $('input[name=end]').val();
                        var trans = $('select[name=trans]').val();    
                        if(!validDate1(d1) && !validDate1(d2))
                        {
                          if(validDate(d1,d2))
                          {
                            $.ajax({
                              url:'../ajax-cashier.php?request=posreport',
                              data:{d1:d1,d2:d2,trans:trans},
                              type:'POST',
                              success:function(data)
                              {
                                console.log(data);
                                var data = JSON.parse(data);                            
                                if(data['stat'])
                                {
                                  var root = document.location.hostname;
                                  var port = document.location.port;
                                  var pathArray = window.location.pathname.split( '/' );
                                  var path = pathArray[1];
                                  var proto = window.location.protocol;
                                  if(port.trim().length > 0)
                                  {
                                    var siteurl = proto+'//'+root+':'+port+'/'+path+'/cashier/createposreport.php?d1='+d1+'&'+'d2='+d2+'&trans='+trans;
                                  }
                                  else 
                                  {
                                    var siteurl = proto+'//'+root+'/'+path+'/cashier/createposreport.php?d1='+d1+'&'+'d2='+d2+'&trans='+trans;
                                  }

                                  window.location.href = siteurl;
                                }
                                else 
                                {
                                  $('.response').html('<div class="alert alert-danger alert-med">'+data['msg']+'</div>'); 
                                  $('input[name=start]').focus();
                                  $button.enable();   
                                }
                              }
                            });
                          }
                          else 
                          {
                            $('.response').html('<div class="alert alert-danger alert-med">Date End is Lesser than Date Start!</div>');
                            $('input[name=start]').focus();
                            $button.enable();                            
                          }                            
                        }
                        else 
                        {
                          $('.response').html('<div class="alert alert-danger alert-med">Date Start / Date End is invalid.</div>');
                          $('input[name=start]').focus();
                          $button.enable();
                        }                        
                      }
                      else 
                      {
                        $('input[name=start]').focus();
                        $button.enable();                       
                      }
                    }
                }, {
                    icon: 'glyphicon glyphicon-remove-sign',
                    label: ' Cancel',
                    cssClass: 'btn-primary',
                    action: function(dialogItself){
                        dialogItself.close();
                    }
                }]
            });
          }
        }
        else 
        {
          if(flag==0)
          {
            flag=1;
             BootstrapDialog.show({
             title:'Warning',
                   message: 'Please void GC',
                   cssClass: 'login-dialog',  
                   onshow: function(dialogRef){
                        
                  },
                  onshown: function(dialogRef){                 
                    
                  },
                  onhide: function(dialogRef){
                      
                  },
                  onhidden: function(dialogRef){               
                      flag=0;
                  }            
              });
          }
        }
      }
  });
}

function gcitemsreport()
{
  if(flag==0)
  {
    flag=1;
    window.location.href="creategcofthedayreport.php";
  }
}

function cashierReport()
{
  $.ajax({
    url:'../ajax-cashier.php?request=getAmtDueAndDiscount',
    success:function(payable)
      {
        var payable = JSON.parse(payable);
        if(!payable['amtdue']>0)
        {
          if(flag==0)
          {
            flag=1;           
            BootstrapDialog.show({              
              title: 'Cashier End of Shift Report',
              closable: true,
              closeByBackdrop: false,
              closeByKeyboard: true,
              cssClass: 'posreport',
              message: function(dialog) {
                var $message = $("<div><img src='../assets/images/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
                var pageToLoad = dialog.getData('pageToLoad');
              setTimeout(function(){
                $message.load(pageToLoad);
              },1000);
              return $message;
              },
              data: {
                'pageToLoad': '../dialogcashier/cashiereport.php',
              },
              onshow: function(dialogRef){
              },
              onshown: function(dialogRef){
              },
              onhidden: function(dialogRef){
                  $('#numOnly').focus();
                  flag=0;
              },
                buttons: [{
                    icon: 'glyphicon glyphicon-ok ',
                    label: ' Create Report',
                    cssClass: 'btn-success',
                    hotkey: 13, // Enter.
                    action: function(dialogItself) {
                       $('.response').html('');
                      var $button = this;
                      $button.disable();

                      $.ajax({
                        url:'../ajax-cashier.php?request=eoschecktrans',      
                        success:function(data)
                        {
                          var data = JSON.parse(data);
                          if(data['st'])
                          {
                            BootstrapDialog.show({
                              title: 'Confirmation',
                              message:  '<div class="row">'+
                                        '<div class="col-md-12">'+
                                        '<div class="dialog-alert">Are you sure you want to create cashier end of shift report?</div>'+
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
                                    window.location.href = 'createosreport.php';
                                  }
                              }, {
                                icon: 'glyphicon glyphicon-remove-sign',
                                  label: 'No',
                                  action: function(dialogItself){
                                      dialogItself.close();
                                      $button.enable();                     
                                  }
                              }]
                            });
                          }
                          else 
                          {
                            $('.response').html('<div class="alert alert-danger alert-med">'+data['msg']+'</div>');
                            $button.enable();
                            $('button.btn.btn-primary').focus();
                          }

                        }
                      });

                      
                    }
                }, {
                    icon: 'glyphicon glyphicon-remove-sign',
                    label: ' Cancel',
                    cssClass: 'btn-primary',
                    action: function(dialogItself){
                        dialogItself.close();
                    }
                }]
            });            
          }
        }
        else 
        {
          if(flag==0)
          {
            flag=1;
             BootstrapDialog.show({
             title:'Warning',
                   message: 'Please void GC',
                   cssClass: 'login-dialog',  
                   onshow: function(dialogRef){
                        
                  },
                  onshown: function(dialogRef){                 
                    
                  },
                  onhide: function(dialogRef){
                      
                  },
                  onhidden: function(dialogRef){               
                      flag=0;
                  }            
              });
          }
        }
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

function refundnow()
{
  if($('.gctoreturns input[name=refundtotal]').length)
  {
    var reftotal = removecomma($('.gctoreturns input[name=refundtotal]').val());
    if(reftotal > 0)
    {
      if(refund==0)
      {
        refund=1;
        BootstrapDialog.show({
            title: 'Service Charge',
            closable: true,
            closeByBackdrop: false,
            closeByKeyboard: true,
            message: function(dialog) {
            var $message = $("<div><img src='../assets/images/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
            var pageToLoad = dialog.getData('pageToLoad');
            setTimeout(function(){
              $message.load(pageToLoad); 
            },1000);
            return $message;
            },
            data: {
              'pageToLoad': '../dialogcashier/servicecharge.php',
            },
            cssClass: 'scharge',           
            onshown: function(dialogRef){
                     
            },
            onhidden: function(dialogRef){
              $('#rbarcode').select();  
              // servicecharge = 0; 
              refund = 0;    
            },
            buttons: [{
                icon: 'glyphicon glyphicon-ok ',
                label: '  Submit',
                cssClass: 'btn-primary',
                hotkey: 13, // Enter.
                action: function(dialogItself) { 
                  var $button = this;    
                  $button.disable();
                  $('.response-sc').html('');
                  var flag = $('.flag').val();
                  if(flag!=undefined)
                  {
                    var transid = $('#transid').val();
                    var cash = parseFloat(removecomma($('#paymentcash').val()));
                    var totden = removecomma($('#tdenom').val());
                    var totlinedisc = removecomma($('#tldisc').val());
                    var totsubdisc = removecomma($('#tsdisc').val());
                    var totrefamt = removecomma($('#tramt').val());
                    alert(cash);

                    // if(cash > 0 && cash!='')
                    // {
                    //   if(cash < totrefamt)
                    //   {
                    //     var trefund = parseFloat(totrefamt) - parseFloat(cash);
                    //     BootstrapDialog.show({
                    //       title: 'Confirmation',
                    //       message:  '<div class="row">'+
                    //                 '<div class="col-md-12">'+
                    //                 'Total Refund: '+parseFloat(trefund).toFixed(2)+' .Are you sure you want to process refund transaction?'+
                    //                 '</div>'+                                                                       
                    //                 '</div>',
                    //       cssClass: 'confirmation',    
                    //       closable: true,
                    //       closeByBackdrop: false,
                    //       closeByKeyboard: true,
                    //       onshown: function(dialogRef){                   
                    //       },
                    //       onhidden: function(dialogRef){
                    //         $('#paymentcash').select();       
                    //       },
                    //       buttons: [{
                    //           icon: 'glyphicon glyphicon-ok-sign',
                    //           label: 'Yes',
                    //           cssClass: 'btn-success',
                    //           hotkey: 13,
                    //           action:function(dialogItself){
                    //             $.ajax({
                    //               url:'../ajax-cashier.php?request=refundnow',
                    //               type:'POST',
                    //               data:{transid:transid,cash:cash},
                    //               success:function(data)
                    //               {
                    //                 var data = JSON.parse(data);
                    //                 if(data['stat'])
                    //                 {
                    //                   BootstrapDialog.closeAll();
                    //                   $('p.gctitle').html('GC Refund');
                    //                   $('.receipt-items').html(data['items']); 
                    //                   $('h3.transactnum span').html(zeroPad(data['transactnum'],10));
                    //                   $('.receipt-footer').html('<table class="table tablefooterrec">'+
                    //                   '<tr>'+
                    //                     '<td>Total GC Amount</td>'+
                    //                     '<td class="mright"><b>₱ '+data['total']+'</b></td>'+
                    //                   '</tr>'+
                    //                   '<tr>'+
                    //                     '<td>Total Line Discount</td>'+
                    //                     '<td class="mright"><b>₱ '+data['linedis']+'</b></td>'+
                    //                   '</tr>'+  
                    //                   '<tr>'+
                    //                     '<td>Total Sub Discount</td>'+
                    //                     '<td class="mright"><b>₱ '+data['subdis']+'</b></td>'+
                    //                   '</tr>'+   
                    //                   '<tr>'+
                    //                     '<td>Service Charge</td>'+
                    //                     '<td class="mright"><b>₱ '+data['scharge']+'</b></td>'+
                    //                   '</tr>'+   
                    //                   '<tr>'+
                    //                     '<td>Total Refund</td>'+
                    //                     '<td class="mright"><b>₱ '+data['totalrefund']+'</b></td>'+
                    //                   '</tr>'+                                                                             
                    //                   '<tr>'+
                    //                     '<td>No. of Items:</td>'+
                    //                     '<td class="mright"><b>'+data['noitems']+'</b></td>'+
                    //                   '</tr>'+
                    //                   '</table>'+
                    //                   '<br />'+
                    //                   '<div class="customer"><center>__________________________<br / >Customer\'s signature over printed name </center></div><br />');
                    //                   BootstrapDialog.closeAll();
                    //                   var dialog = new BootstrapDialog({
                    //                     message: function(dialogRef){
                    //                     var $message = $('<div>Refund Transaction successfully processed.</div>');             
                    //                         return $message;
                    //                     },
                    //                     closable: false
                    //                   });
                    //                   dialog.realize();
                    //                   dialog.getModalHeader().hide();
                    //                   dialog.getModalFooter().hide();
                    //                   dialog.getModalBody().css('background-color', '#86E2D5');
                    //                   dialog.getModalBody().css('color', '#000');
                    //                   dialog.open();
                    //                   setTimeout(function(){
                    //                           dialog.close();

                    //                       }, 1500);
                    //                   setTimeout(function(){
                    //                     window.print();
                    //                     window.location.reload();                                                          
                    //                   }, 1700);

                    //                 }
                    //                 else 
                    //                 {
                    //                    $('.response-sc').html('<div class="alert alert-danger">'+data['msg']+'</div>');
                    //                    dialogItself.close();
                    //                 }
                    //               }
                    //             });             
                    //           }
                    //       }, {
                    //         icon: 'glyphicon glyphicon-remove-sign',
                    //           label: 'No',
                    //           action: function(dialogItself){
                    //               dialogItself.close();                      
                    //           }
                    //       }]
                    //     });                    
                    //   }
                    //   else 
                    //   {
                    //     $('.response-sc').html('<div class="alert alert-danger">Service Charge Amount is larger than Refund Amount.</div>');
                    //   }                  
                    // }
                    // else 
                    // {
                    //   $('.response-sc').html('<div class="alert alert-danger">Please input service charge <amount></amount>.</div>');
                    // }                
                  }

                  $('#paymentcash').select();
                  $button.enable();

                }            
            },{
              icon: 'glyphicon glyphicon-remove-sign',
              label: ' Cancel',
              cssClass: 'btn-default',
              action: function(dialogItself){
                  dialogItself.close();
              }
            }]
        });
      }
    }
    else 
    {
      alert('Refund Amount is negative.');
    } 
  }
  else 
  {
    alert('Please scan gc first');
  }
}

function zeroPad(num, places) 
{
  var zero = places - num.toString().length + 1;
  return Array(+(zero > 0 && zero)).join("0") + num;
}

function endofday()
{
  $.ajax({
    url:'../ajax-cashier.php?request=checkIFhasTempSales',
    success:function(data)
    {
      var data  = JSON.parse(data);
      if(data['st'])
      {
        $.ajax({
          url:'../ajax-cashier.php?request=checkeodtrans',
          success:function(data1)
          {
            var data1 = JSON.parse(data1);

            if(data1['st'])
            {
              if(flag==0){
                flag=1;
                  //end of day
                  BootstrapDialog.show({
                    title: 'Confirmation',
                      message: '<div class="dialog-alert">Perform end of day?</div>',
                      closable: true,
                      closeByBackdrop: false,
                      closeByKeyboard: true,
                      onshow: function(dialog) {
                          // dialog.getButton('button-c').disable();
                      },

                      onhidden: function(dialog){
                        flag=0;
                      },
                      buttons: [{
                          icon: 'glyphicon glyphicon-ok-sign',
                          label: 'Yes',
                          cssClass: 'btn-success',
                          hotkey: 13,
                          action:function(dialogItself){                  
                            dialogItself.close();
                            $.ajax({
                              url:'../ajax-cashier.php?request=endofdaypos',
                              beforeSend:function(){

                              },
                              success:function(data2){
                                window.location.href = 'createodreport.php';                    
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
              if(flag==0)
              {
                flag=1;
                BootstrapDialog.show({
                title:'Warning',
                     message: '<div class="dialog-alert">'+data1['msg']+'</div>',
                     cssClass: 'login-dialog',  
                     onshow: function(dialogRef){
                          
                    },
                    onshown: function(dialogRef){                 
                      
                    },
                    onhide: function(dialogRef){
                        
                    },
                    onhidden: function(dialogRef){               
                        flag=0;
                    }            
                });          
              }
            }

          
          }
        });
      }
      else 
      {
        if(flag==0)
        {
          flag=1;
           BootstrapDialog.show({
           title:'Warning',
                 message: '<div class="dialog-alert">'+data['msg']+'</div>',
                 cssClass: 'login-dialog',  
                 onshow: function(dialogRef){
                      
                },
                onshown: function(dialogRef){                 
                  
                },
                onhide: function(dialogRef){
                    
                },
                onhidden: function(dialogRef){               
                    flag=0;
                }            
            });          
        }
      }
    }
  });
}

function supervisorlogin(dis)
{
  if(flag==0)
  {
    flag=1;           
    BootstrapDialog.show({              
      title: 'Supervisor Login',
      closable: true,
      closeByBackdrop: false,
      closeByKeyboard: true,
      cssClass: 'login-supervisor',
      message: function(dialog) {
        var $message = $("<div><img src='../assets/images/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
        var pageToLoad = dialog.getData('pageToLoad');
      setTimeout(function(){
        $message.load(pageToLoad);
      },1000);
      return $message;
      },
      data: {
        'pageToLoad': '../dialogcashier/supervisorlogin.php',
      },
      onshow: function(dialogRef){ 
      },
      onshown: function(dialogRef){
      },
      onhidden: function(dialogRef){
          $('#numOnly').focus();
          flag=0;
      },
        buttons: [{
            icon: 'glyphicon glyphicon-ok ',
            label: ' Submit',
            cssClass: 'btn-success', 
            hotkey: 13, // Enter.
            action: function(dialogItself) {
              var $button = this;
              $button.disable();
              var hasEmpty = false;
              $('.respose').html('');

              if($('#s-uname').val()!=undefined)
              {
                var uname = $('#s-uname').val(), uid = $('#s-id').val(), upass = $('#s-key').val();
                $('.inpmed').each(function(){
                  if($(this).val()=='')
                  {
                    hasEmpty = true;
                    return false;
                  }
                });

                if(!hasEmpty)
                {
                  $.ajax({
                    url:'../ajax-cashier.php?request=supervisorlogin',
                    data:{uname:uname,uid:uid,upass:upass},
                    type:'POST',
                    success:function(data)
                    {
                      console.log(data);
                      var data = JSON.parse(data);
                      if(data['st'])
                      {
                        dialogItself.close();
                        if(dis==1)
                        {
                          linediscount();
                        }
                        else if(dis==2)
                        {
                          trandis();
                        }
                        else if(dis==3)
                        {
                          removealldiscline();
                        }
                        else if(dis==4)
                        {
                          removedocdisc();
                        }
                      } 
                      else 
                      {
                        $('.response').html('<div class="alert alert-danger danger-o mb">'+data['msg']+'</div>');
                        $button.enable();
                        $('.supervisor-in').focus();
                      }               
                    }
                  });                  
                }
                else 
                {
                  $('.response').html('<div class="alert alert-danger danger-o mb">Please fill all fields.</div>');
                  $button.enable();
                  $('.supervisor-in').focus();
                }

              }
              else 
              {
                $button.enable();
              }
            }
        }]
    });            
  }
}

function voidbyline(barcodenum)
{
  if(flag==0)
  {
    flag=1;           
    BootstrapDialog.show({
      title: 'Confirmation',
        message:  '<div class="row">'+
                  '<div class="col-md-12">'+
                  '<input type="hidden" value="0" id="stat">'+
                  '<div class="dialog-alert">Are you sure you want to void GC Barcode # '+barcodenum+'?</div>'+
                  '</div>'+                                                                       
                  '</div>',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: true,
        onshow: function(dialog) {
            // dialog.getButton('button-c').disable();
        },
        onshown: function(dialog) {
        },
        onhidden: function(dialogRef){
            flag=0;  
            $('.scan').focus();                
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Yes',
            cssClass: 'btn-success',
            hotkey: 13,
            action:function(dialogItself){
              var $button = this;
              if($('#stat').val()==0)
              {
                $('#stat').val('1');
              }
              else 
              {
                $button.disable();
                $.ajax({
                  url:'../ajax-cashier.php?request=voidline',
                  type:'POST',
                  data:{barcodenum:barcodenum},
                  success:function(data)
                  {
                    var data = JSON.parse(data);
                    if(data['stat'])
                    {

                      $('.receipt-items').load('../ajax-cashier.php?request=receipt');    
                      $('._barcodes').load('../ajax-cashier.php?request=load');  
                      updateAll();
                      dialogItself.close();
                    }
                    else 
                    {
                      dialogItself.close();
                      alert(data['msg']);
                    }
                  }
                });
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
}

function voidbylinereval(barcode)
{
  if(flag==0)
  {
    flag=1;
    BootstrapDialog.show({
      title: 'Confirmation',
        message:  '<div class="row">'+
                  '<div class="col-md-12">'+
                  '<input type="hidden" value="0" id="stat">'+
                  'Are you sure you want to void GC Barcode # '+barcode+'?'+
                  '</div>'+                                                                       
                  '</div>',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: true,
        onshow: function(dialog) {
            // dialog.getButton('button-c').disable();
        },
        onshown: function(dialog) {
        },
        onhidden: function(dialogRef){
            flag=0;  
            $('#numOnlyreval').focus();                
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Yes',
            cssClass: 'btn-success',
            hotkey: 13,
            action:function(dialogItself){
              var $button = this;
              if($('#stat').val()==0)
              {
                $('#stat').val('1');
              }
              else 
              {
                $button.disable();
                $.ajax({
                   url:'../ajax-cashier.php?request=voidlinereval',
                   data:{barcode:barcode},
                   type:'POST',
                   success:function(data){
                    console.log(data);
                    var data = JSON.parse(data);
                    if(data['st'])
                    {
                      $('._barcodesreval').load('../ajax-cashier.php?request=loadreval');
                      $('input.inp-amtdue._cashier_totalreval').val(data['total']);
                      $('input.noitemsreval').val(data['count']);    
                      $('#numOnlyreval').focus();
                      dialogItself.close();                 
                    }
                    else 
                    {
                      alert(data['msg']);
                      BootstrapDialog.closeAll();
                      flag=0;  
                      $('#numOnlyreval').focus();                
                    }
                   }
                });              

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

}

function voidbylinerefund(barcode)
{
  if(flag==0)
  {
    flag=1;
    BootstrapDialog.show({
      title: 'Confirmation',
        message:  '<div class="row">'+
                  '<div class="col-md-12">'+
                  '<input type="hidden" value="0" id="stat">'+
                  'Are you sure you want to void GC Barcode # '+barcode+'?'+
                  '</div>'+                                                                       
                  '</div>',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: true,
        onshow: function(dialog) {
            // dialog.getButton('button-c').disable();
        },
        onshown: function(dialog) {
        },
        onhidden: function(dialogRef){
            flag=0;  
            $('input#numOnlyreturn').focus();                
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Yes',
            cssClass: 'btn-success',
            hotkey: 13,
            action:function(dialogItself){
              var $button = this;
              if($('#stat').val()==0)
              {
                $('#stat').val('1');
              }
              else 
              {
                $button.disable();
                $.ajax({
                   url:'../ajax-cashier.php?request=voidlinerefund',
                   data:{barcode:barcode},
                   type:'POST',
                   success:function(data){
                    console.log(data);
                    var data = JSON.parse(data);
                    if(data['st'])
                    {          
                      dialogItself.close();
                      $("[name='inprefundgc']").val('');
                      $('._barcodesrefund').load('../ajax-cashier.php?request=loadrefund');
                      $('input.totdenomref').val(addCommas(data['reftotdenom']));
                      $('input.totsubdiscref').val(data['refsub']);
                      $('input.totlinedisref').val(data['refline']);
                      $('input.noitemsref').val(data['refcnt']);
                      $('input.serviceref').val(data['scharge']);
                      $('input._cashier_totalrefund').val(addCommas(data['refamtdue'].toFixed(2)));

                    }
                    else 
                    {
                      alert(data['msg']);
                      BootstrapDialog.closeAll();
                      flag=0;  
                      $('#numOnlyreval').focus();                
                    }
                   }
                });              

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
}

function refundgcmod()
{

  //check 
  if(flag==0)
  {
    $.ajax({
      url:'../ajax-cashier.php?request=cntrefunditems',
      success:function(data)
      {
        var data = JSON.parse(data);
        if(data['cntref'] > 0)
        {
          // check service charge
          flag = 1;
          BootstrapDialog.show({
              title: 'Are you sure you want to refund GC?',
              closable: true,
              closeByBackdrop: false,
              closeByKeyboard: true,
              message: function(dialog) {
              var $message = $("<div><img src='../assets/images/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
              var pageToLoad = dialog.getData('pageToLoad');
              setTimeout(function(){
                $message.load(pageToLoad); 
              },1000);
              return $message;
              },
              data: {
                'pageToLoad': '../dialogcashier/refundgc.php',
              },
              cssClass: 'scharge',           
              onshown: function(dialogRef){
                       
              },
              onhidden: function(dialogRef){
                flag=0;
                $('#numOnlyreturn').select();
              },
              buttons: [{
                  icon: 'glyphicon glyphicon-ok ',
                  label: '  Submit',
                  cssClass: 'btn-primary',
                  hotkey: 13, // Enter.
                  action: function(dialogItself) { 
                    var $button = this;
                    $button.disable();

                    $.ajax({
                      url:'../ajax-cashier.php?request=refundgc',
                      success:function(data2)
                      {
                        console.log(data2);
                        var data2 = JSON.parse(data2);
                        if(data2['st'])
                        {
                           BootstrapDialog.closeAll();
                           if(data2['receipt'] == 'yes')
                           {
                            if(data2['showscharge'])
                            {
                              $('p.gctitle').html('GC Refund');
                              $('.receipt-items').html(data2['items']); 
                              $('h3.transactnum span').html(zeroPad(data2['transactnum'],10));
                              $('.receipt-footer').html('<table class="table tablefooterrec">'+
                              '<tr>'+
                                '<td>Total GC Amount</td>'+
                                '<td class="mright"><b>₱ '+data2['total']+'</b></td>'+
                              '</tr>'+
                              '<tr>'+
                                '<td>Total Line Discount</td>'+
                                '<td class="mright"><b>₱ '+data2['linedis']+'</b></td>'+
                              '</tr>'+  
                              '<tr>'+
                                '<td>Total Sub Discount</td>'+
                                '<td class="mright"><b>₱ '+data2['subdis']+'</b></td>'+
                              '</tr>'+   
                              '<tr>'+
                                '<td>Service Charge</td>'+
                                '<td class="mright"><b>₱ '+data2['scharge']+'</b></td>'+
                              '</tr>'+   
                              '<tr>'+
                                '<td>Total Refund</td>'+
                                '<td class="mright"><b>₱ '+data2['totalrefund'].toFixed(2)+'</b></td>'+
                              '</tr>'+                                                                             
                              '<tr>'+
                                '<td>No. of Items:</td>'+
                                '<td class="mright"><b>'+data2['noitems']+'</b></td>'+
                              '</tr>'+
                              '</table>'+
                              '<br />'+
                              '<div class="customer"><center>__________________________<br / >Customer\'s signature over printed name </center></div><br />');
                            }
                            else 
                            {
                              $('p.gctitle').html('GC Refund');
                              $('.receipt-items').html(data2['items']); 
                              $('h3.transactnum span').html(zeroPad(data2['transactnum'],10));
                              $('.receipt-footer').html('<table class="table tablefooterrec">'+
                              '<tr>'+
                                '<td>Total GC Amount</td>'+
                                '<td class="mright"><b>₱ '+data2['total']+'</b></td>'+
                              '</tr>'+
                              '<tr>'+
                                '<td>Total Line Discount</td>'+
                                '<td class="mright"><b>₱ '+data2['linedis']+'</b></td>'+
                              '</tr>'+  
                              '<tr>'+
                                '<td>Total Sub Discount</td>'+
                                '<td class="mright"><b>₱ '+data2['subdis']+'</b></td>'+
                              '</tr>'+   
                              '<tr>'+
                                '<td>Total Refund</td>'+
                                '<td class="mright"><b>₱ '+data2['totalrefund'].toFixed(2)+'</b></td>'+
                              '</tr>'+                                                                             
                              '<tr>'+
                                '<td>No. of Items:</td>'+
                                '<td class="mright"><b>'+data2['noitems']+'</b></td>'+
                              '</tr>'+
                              '</table>'+
                              '<br />'+
                              '<div class="customer"><center>__________________________<br / >Customer\'s signature over printed name </center></div><br />');                              
                            }
                          }
                            var dialog = new BootstrapDialog({
                              message: function(dialogRef){
                              var $message = $('<div>Refund Transaction successfully processed.</div>');             
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
                              if(data2['receipt'] == 'yes')
                              {
                                window.print();
                              }
                              $('table tbody._barcodesrefund').load('../ajax-cashier.php?request=refreshrevaltable'); 
                              $('input#numOnlyreturn').focus();                                              
                            }, 1700);
                        }
                        else 
                        {
                          $('.response-sc').html('<div class="alert alert-danger danger-o" id="_emp_cash">'+data2['msg']+'</div>');
                          $button.enable();
                        }
                      }
                    });
                  }            
              },{
                icon: 'glyphicon glyphicon-remove-sign',
                label: ' Cancel',
                cssClass: 'btn-default',
                action: function(dialogItself){
                    dialogItself.close();
                }
              }]
          });
        }
        else 
        {
          alert("Please scan gc first.");
        }
      }
    });
  }
}


function checksession()
{
    setInterval(function() {
        $.ajax({
            url:'../ajax-cashier.php?request=checksession',
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
                        window.location.href ='login.php';
                    }, 5500);    
                    //$('.responsechangepass').html('<div class="alert alert-danger alert-no-bot alertpad8">'+data['msg']+'</div>');
                }
            }


        });

    },40000); // 60000 milliseconds = one minute

    
}



