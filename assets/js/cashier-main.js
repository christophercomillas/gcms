  flag=0;    
  mode=0;
  scan=0;
  payment = 0;
  reval=0;
  customer=0;
  search =0;
  group=0;
  // report=0;
  // discounts=0;
  // linedisc=0;
  modemanager=0;

  /// mode = 0 - main cashier
  /// mode = 1 - mode of payment
  /// mode = 2 - supervisor
  /// mode = 3 - other income


      $(window).bind("load", function () {
        var footer = $(".footer");
        var pos = footer.position();
        var height = $(window).height();
        height = height - pos.top;
        height = height - footer.height();
        height = height - 8; // optional ra   
        if (height > 0) {
            footer.css({
                'margin-top': height + 'px'
            });
        }
      });

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
            },
            buttons: [{
              icon: 'glyphicon glyphicon-print',
              label: ' Print',
              cssClass: 'btn-default',
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
              cssClass: 'btn-default',
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
            },
            buttons: [{
              icon: 'glyphicon glyphicon-print',
              label: ' Print',
              cssClass: 'btn-default',
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



      // if(getUrlVars()['d1'].trim()!='' && getUrlVars()['d2'].trim()!='')
      // {
      //   BootstrapDialog.show({
      //       title: 'POS Report',
      //       closable: true,
      //       closeByBackdrop: false,
      //       closeByKeyboard: true,
      //       message: function(dialog) {
      //       var $message = $("<div><img src='../assets/images/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
      //       var pageToLoad = dialog.getData('pageToLoad');
      //       setTimeout(function(){
      //         $message.load(pageToLoad); 
      //       },1000);
      //       return $message;
      //       },
      //       data: {
      //         'pageToLoad': '../dialogcashier/posreportpdf.php',
      //       },
      //       cssClass: 'pdfshow',           
      //       onshown: function(dialogRef){                   
      //       },
      //       onhidden: function(dialogRef){                 
      //       },
      //       buttons: [{
      //         icon: 'glyphicon glyphicon-remove-sign',
      //         label: ' Close',
      //         cssClass: 'btn-default',
      //         action: function(dialogItself){
      //             dialogItself.close();
      //             window.location = '../cashiering';
      //         }
      //       }]
      //   }); 
      // }    

  $(function(){
         /*  disable right click */
        // var message = 'Disabled.';
        // function clickIE4(){
        // if (event.button==2){
        // alert('Disabled.');
        // return false;
        // }
        // }

        // function clickNS4(e){
        // if (document.layers||document.getElementById&&!document.all){
        // if (e.which==2||e.which==3){
        // alert('Disabled');
        // return false;
        // }
        // }
        // }

        // if (document.layers){
        // document.captureEvents(Event.MOUSEDOWN);
        // document.onmousedown=clickNS4;
        // }
        // else if (document.all&&!document.getElementById){
        // document.onmousedown=clickIE4;
        // }

        // document.oncontextmenu=new Function("return false");
        /*  disable right click */

        $('#numOnly').inputmask();

        $('._barcodes').load('../ajax-cashier.php?request=load');       
        $('.sbtotal').load('../ajax-cashier.php?request=total');
        $('#_cashier_total').load('../ajax-cashier.php?request=amtdue');
        $('.linediscount').load('../ajax-cashier.php?request=linediscount');
        $('.docdiscount').load('../ajax-cashier.php?request=docdiscount');
        $('.receipt-items').load('../ajax-cashier.php?request=receipt');                 
        $('.noitems').load('../ajax-cashier.php?request=totalitems');

        $("[name='data']").on('keypress', function (event) {
          if(event.which === 13){
            var suc=0;
            var value = this.value;
            $.ajax({
              type : "POST",
              url  : "../ajax-cashier.php?request=check",
              data : { value : value },
              beforeSend:function(){          
              },
              success : function(data){
                var data = data.trim();
                if(data=='success'){
                  suc = 1;
                  $("[name='data']").val('');                   
                } else {
                  // $("#response").html('GC '+value+' '+data);
                  flag=1;
                  BootstrapDialog.show({
                      title:'Warning',
                      message: 'GC Barcode Number '+value+' '+data,
                      onhidden: function(dialogRef){ 
                        flag = 0;                
                        $("[name='data']").focus();         
                      }
                  });
                  $("[name='data']").val('');
                }

                if(suc==1){
                  $('._barcodes').load('../ajax-cashier.php?request=load');
                  $('.sbtotal').load('../ajax-cashier.php?request=total');
                  $('#_cashier_total').load('../ajax-cashier.php?request=amtdue');
                  $('.linediscount').load('../ajax-cashier.php?request=linediscount');
                  $('.docdiscount').load('../ajax-cashier.php?request=docdiscount');
                  $('.receipt-items').load('../ajax-cashier.php?request=receipt');                 
                  $('.noitems').load('../ajax-cashier.php?request=totalitems');
                  $('span.cashr').text('₱ 0.00');
                  $('span.changer').text('₱ 0.00');
                  // $('#_cashier_total').load('../ajax-cashier.php?request=total');
                  // $('._barcodes').load('../ajax-cashier.php?request=load');
                  // $('.receipt-items').load('../ajax-cashier.php?request=receipt');                           
                  // $('.noitems').load('../ajax-cashier.php?request=totalitems');                 
                }
              } 
            });  
           }
        });

        $('tbody._barcodes').on('click','a.remove',function(){
          var b = $(this).attr('href');
          BootstrapDialog.show({
            title: 'Confirmation',
              onshow: function(dialog) {
                  // dialog.getButton('button-c').disable();
              },
              onshown: function(dialog) {
                  $('#vlusername').focus();
              },
              onhidden: function(dialogRef){
                  $('#numOnly').focus();   
                  $('table tbody._barcodes tr:first').css('background-color','none');                                    
                  flag=0;
              },
              cssClass: 'login-supervisor',    
              message:  '<div class="row">'+
                        '<div class="col-md-12">'+
                        'Void GC Barcode '+b+' ?<br />'+
                        '</div>'+
                        '</div>'+
                        '<div class="row">'+
                        '<div class="col-lg-12">'+
                        'Supervisor Username'+
                        '</div>'+                                               
                        '</div>'+
                        '<div class="row">'+
                        '<div class="col-lg-12">'+
                        '<input type="text" class="form-control input-sm" id="vlusername">'+
                        '</div>'+                                               
                        '</div>'+
                        '<div class="row">'+
                        '<div class="col-lg-12">'+
                        'Supervisor Key'+
                        '</div>'+                                               
                        '</div>'+
                        '<div class="row">'+
                        '<div class="col-lg-12">'+
                        '<input type="password" class="form-control input-sm" id="vlkey">'+
                        '</div>'+                                               
                        '</div>',
              closable: true,
              closeByBackdrop: false,
              closeByKeyboard: true,
              buttons: [{
                  icon: 'glyphicon glyphicon-ok-sign',
                  label: 'Yes',
                  cssClass: 'btn-success',
                  hotkey: 13,
                  action:function(dialogItself){                  
                    //dialogItself.close();
                    var uname = $('#vlusername').val(), pword = $('#vlkey').val();
                    if((uname!='') && (pword!=''))
                      {
                        $.ajax({
                          url:'../ajax-cashier.php?request=voidline',
                          type:"POST",
                          data:{b:b,uname:uname,pword:pword},
                          beforeSend:function(){

                          },
                          success:function(response){
                            var res = response.trim();
                            if(res=='success'){
                              $('._barcodes').load('../ajax-cashier.php?request=load');
                              $('#_cashier_total').load('../ajax-cashier.php?request=total');        
                              $('.receipt-items').load('../ajax-cashier.php?request=receipt');                 
                              $('.noitems').load('../ajax-cashier.php?request=totalitems');
                              dialogItself.close();
                            } else {
                              alert(response);
                              $('#vlusername').focus();
                            }
                          }
                        });
                      } else {
                        $('#vlusername').focus();
                      }
                  }
              }, {
                icon: 'glyphicon glyphicon-remove-sign',
                  label: 'No',
                  action: function(dialogItself){
                      dialogItself.close();
                      $('table tbody._barcodes tr:first').css('background-color','none');      
                  }
              }]
          });
 
          return false;

  }); 

  function init() {
    // var flag=0;
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
    shortcut.add("F10",function() {
      var time = $('span#time').text();
      alert(time);
    });
  }

  window.onload=init; 

  $('#cash').keypress(function(){
    alert('xxx');
  });

  $('.ts_gc').on('click', '.remove', function() {
    var barcode = $(this).attr("href");
    BootstrapDialog.show({
            title: 'Please input password to remove item.',
            message: '<div class="row">'+
                  '<div class="col-lg-12">'+                                
                    '<input type="password" class="form-control userpass" />'+                    
                  '</div>'+
                '</div>'+
                '<div class="row">'+
                  '<div class="col-lg-12 response-remove">'+                    
                  '</div>'+
                '</div>',
            cssClass: 'remove',
            onshow: function(dialogRef){

            },
            onshown: function(dialogRef){
                $('.userpass').focus();
            },
            onhide: function(dialogRef){
                
            },
            onhidden: function(dialogRef){
                
            },
            buttons: [{
                label: 'Submit',
                cssClass: 'btn-primary',
                hotkey: 13,
                action: function(dialog){
                    var userpass = $('.userpass').val();
                    var userpass = userpass.trim();
                    if(userpass==''){
                      $('.response-remove').html('<div class="alert alert-danger" id="danger-x">Password is empty.</div>');
                      $('.userpass').focus();
                    } else {
                      $.ajax({
                        url:'cashier_ajax.php?request=remove-item',
                        type:'POST',
                        data:{userpass:userpass,barcode:barcode},
                        beforeSend:function(){                          
                        },
                        success:function(response){
                          var res = response.trim();
                          if(res=='success'){
                            $('._barcodes').load('cashier_ajax.php?request=load');
                            $('#_cashier_total').load('cashier_ajax.php?request=total');
                            $('.receipt-items').load('cashier_ajax.php?request=receipt');
                            dialog.close();
                            $("[name='data']").focus();
                          } else {
                            $('.response-remove').html('<div class="alert alert-danger" id="danger-x">'+response+'</div>');
                            $('.userpass').focus();
                          }
                        }
                      });                     
                    }                
                }
            }]          
        });
    return false;
      
    }); 
  });

// $('.manager-mode button#f1,#f1').click(function(){
//   f1();
// });

// $('.manager-mode button#f2,#f2').click(function(){
//   f2();
// });

// $('.manager-mode button#f3,#f3').click(function(){
//   f3();
// });

// $('.manager-mode button#f4,#f4').click(function(){
//   f4();
// });

// $('.manager-mode button#f5,#f5').click(function(){
//   f5();
// });

// $('.manager-mode button#f6').click(function(){
//   f6();
// });

// $('#f7').click(function(){
//   f7();
// });

//function shortcut

/// mode = 0 - main cashier
/// mode = 1 - mode of payment
/// mode = 2 - supervisor
/// mode = 3 - other income

function f1()
{

  if($('#managerkey').is(':checked'))
  {
    if(modemanager==0)
    {
      lookup();      
    }
    else if(modemanager==2)
    {
      linediscount();
    }
    else if(modemanager==3)
    {
      posreport();
    }
  }
  else 
  {
    if(mode==0)
    {
      $('.cashier-mode').hide();
      $('.payment-mode').show();
      mode = 1;
      return false;
    } 
    if(mode==1)
    {
      if(flag==1 && customer==1)
      {
        searchCustomer();
        return false;
      }
      else 
      {
        cash();
        return false;
      }
    }
    if(mode==3)
    {
      if(payment==1)
      {
        cashrevalpayment();
      }
      else 
      {
        $.ajax({
          url:'../ajax-cashier.php?request=deletetempandchecktempsales',
          success:function(data)
          {
            var data = JSON.parse(data);
            if(data['stat'])
            {             
              if(flag==0)
              {
                flag=1;
                revalidateGC();
                payment=1;
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
                        $('#numOnly').focus();                 
                        flag=0;
                    }            
                });
              }

            }
          }
        });

      }
      return false;
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
      $('.otherincome-mode').hide();
      $('.cashier-mode').show();
      mode=0;          
    }       
  }
}

function f3()
{
  if($('#managerkey').is(':checked'))
  {
    if(modemanager==3)
    {
      $('.manager-mode').show();
      $('.reports').hide();  
      modemanager=0;    
    }
    else if(modemanager==0)
    {
      gcrefund();
    }
    else if(modemanager==2)
    {
      removealldiscline();
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
      $('.otherincome-mode').show();
      $('.cashier-mode').hide();
      mode=3;          
    } 
    else if(mode==1)
    {
      headoffice();
    }     
  } 
}

function f4()
{
  if($('#managerkey').is(':checked'))
  {
    if(modemanager==0)
    {
      $('.manager-mode').hide();
      $('.discounts').show();
      modemanager=2;      
    }
    else if(modemanager==2)
    {
      removedocdisc();
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
      supervisormode();
      mode=1;
    } 
    else if(mode==1)
    {
      subsadmin();
    }      
  }       
}

function f5()
{
  if($('#managerkey').is(':checked'))
  {
    if(modemanager==0)
    {
      $('.manager-mode').hide();
      $('.reports').show();
      modemanager=3;              
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
      reloadpage();
    } 
    else if(mode==1)
    {
      if(flag==0)
      {  
        $('.cashier-mode').show();
        $('.payment-mode').hide();
        mode=0;
      }
    }           
  } 
}

function f6()
{
  if($('#managerkey').is(':checked'))
  {
    endofday();
  }
}

function f7()
{
  if($('#managerkey').is(':checked'))
  {
    supervisorlogout();
  }
  else 
  {
    logoutuser(); 
  }
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

function removecomma(nStr){
  return nStr.replace(/,/g , "");
} 

function timeoutmsg(){
    setTimeout(function(){
      $('.response').html('');
    }, 4000);
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
    //if (ss < 10) {ss = "0"+ss;}
    // This formats your string to HH:MM:SS
    (hh == "00") ? hh = "12" : hh;
    var time = hh + ":" + mm + ":" + ss + " " + ampm;
// add a zero in front of numbers<10

setTimeout("updateTime()",1000);
document.getElementById('time').innerHTML= time;
}
updateTime();


// function cash(){
//   alert('xxx');
// }

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
  $('#paymentcash').focus(); 
}

function zeroPad(num, places) {
  var zero = places - num.toString().length + 1;
  return Array(+(zero > 0 && zero)).join("0") + num;
}

function refundnow()
{
  BootstrapDialog.show({
    title: 'Confirmation',
    message:  '<div class="row">'+
              '<div class="col-md-12">'+
              'Are you sure you want process this transaction?'+
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

          $.ajax({
            url:'../ajax-cashier.php?request=refundnow',
            success:function(data)
            {
              console.log(data);
              var data = JSON.parse(data);
              if(data['stat'])
              {
                $('p.gctitle').html('GC Refund');
                $('.receipt-items').html(data['items']); 
                $('h3.transactnum span').html(zeroPad(data['transactnum'],4));
                $('.receipt-footer').html(data['total']);
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
                  window.location.reload();                                                          
                }, 1700);
              }
              else 
              {
                BootstrapDialog.closeAll();
                alert(data['msg']);                     
              }
            }
          });

          // var dialog = new BootstrapDialog({
          //   message: function(dialogRef){
          //   var $message = $('<div>Success</div>');             
          //       return $message;
          //   },
          //   closable: false
          // });
          // dialog.realize();
          // dialog.getModalHeader().hide();
          // dialog.getModalFooter().hide();
          // dialog.getModalBody().css('background-color', '#86E2D5');
          // dialog.getModalBody().css('color', '#000');
          // dialog.open();
          // setTimeout(function(){
          //         dialog.close();
          //     }, 2000);
          // setTimeout(function(){                                    
          // }, 1700);                     
          
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

function lookup()
{
  if(flag==0)
    {
    flag=1;
    BootstrapDialog.show({              
      title: 'GC Lookup',
        message: '<div class="row">'+
                    '<div class="col-md-12">'+ 
                    '<p class="p-credit">'+
                      'GC Barcode Number'+
                    '</p>'+
                    '</div>'+
                  '</div>'+
                  '<div class="row">'+
                    '<div class="col-lg-12">'+
                      '<input type="text" class="form-control" id="gc-barcode">'+
                    '</div>'+
                  '</div>'+
                  '<div class="row">'+
                    '<div class="col-lg-12 response">'+                                
                    '</div>'+
                  '</div>',
        cssClass: 'payment-cus',             
        onshow: function(dialogRef){
            
        },
        onshown: function(dialogRef){                    
          $("#gc-barcode").keydown(function (e) {
          // Allow: backspace, delete, tab, escape, enter and .
          if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
               // Allow: Ctrl+A
              (e.keyCode == 65 && e.ctrlKey === true) || 
               // Allow: home, end, left, right
              (e.keyCode >= 35 && e.keyCode <= 39)) {
                   // let it happen, don't do anything
                   return;
              }
              // Ensure that it is a number and stop the keypress
              if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                  e.preventDefault();
              }
          });               
          $('#gc-barcode').focus();                              
        },
        onhide: function(dialogRef){
            
        },
        onhidden: function(dialogRef){
            $('#numOnly').focus();
            flag=0;
        },
        buttons: [{
            label: 'Submit',
            cssClass: 'btn-primary',
            hotkey: 13, // Enter.
            action: function(dialogItself) {
              var barcode = $('#gc-barcode').val();

              $.ajax({
                url:'../ajax-cashier.php?request=lookup',
                type:'POST',
                data:{barcode:barcode},
                beforeSend:function(){

                },
                success:function(response){
                  $('.response').html(response);
                  $('#gc-barcode').val('').focus();                              
                }
              });


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
                        $('.responsecash').html('<div class="alert alert-danger danger-o" id="_emp_cash">Cash is not enough.</div>');
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
                                linedisc = addCommas(linedisc.toFixed(2));
                                docdisc = addCommas(docdisc.toFixed(2));
                                cashrec = addCommas(cashrec.toFixed(2));
                                changerec = addCommas(changerec.toFixed(2));
                                total = addCommas(total.toFixed(2));

                                $('h3.transactnum span').html(response['transactnum']);
                                $('.receipt-footer').html('<table class="table tablefooterrec">'+
                                  '<tr>'+
                                    '<td>Line Discount</td>'+
                                    '<td class="mright"><b>₱ '+linedisc+'</b></td>'+
                                  '</tr>'+
                                  '<tr>'+
                                    '<td>Doc Discount</td>'+
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
                                $('span.cashr').text('₱ '+cashrec);
                                $('span.changer').text('₱ '+changerec);
                                $('._barcodes').load('../ajax-cashier.php?request=load');
                                $('.receipt-items').load('../ajax-cashier.php?request=receipt');
                                $('.cashier-mode').show();
                                $('.payment-mode').hide();
                                mode = 0;
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

function voidAll()
{
  if(flag==0)
  {
    var rowCount = $('table.table tbody._barcodes tr').length;
    if(rowCount>0){
      flag=1;                      
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
                          $('.sbtotal').load('../ajax-cashier.php?request=total');
                          $('#_cashier_total').load('../ajax-cashier.php?request=amtdue');
                          $('.linediscount').load('../ajax-cashier.php?request=linediscount');
                          $('.docdiscount').load('../ajax-cashier.php?request=docdiscount');
                          $('.receipt-items').load('../ajax-cashier.php?request=receipt');                 
                          $('.noitems').load('../ajax-cashier.php?request=totalitems');
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
    } else {
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
                    
                    var total_charge = parseInt($('#_cashier_total > input#total_charge').val());
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
                                linedisc = addCommas(linedisc.toFixed(2));
                                docdisc = addCommas(docdisc.toFixed(2));
                                total = addCommas(total.toFixed(2));

                                  $('h3.transactnum span').html(data['transactnum']);
                                  $('.receipt-footer').html('<table class="table tablefooterrec">'+
                                  '<tr>'+
                                    '<td>Line Discount</td>'+
                                    '<td class="mright"><b>₱ '+linedisc+'</b></td>'+
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
                                      '<td>No. of Items:</td>'+
                                      '<td class="mright"><b>'+data['numitems']+'</b></td>'+
                                    '</tr>'+
                                    '<tr>'+
                                  '</table>');
                                  window.print();
                                  $('span.cashr').text('₱ 0.00');
                                  $('span.changer').text('₱ 0.00');
                                  $('._barcodes').load('../ajax-cashier.php?request=load');
                                  $('.receipt-items').load('../ajax-cashier.php?request=receipt');
                                  $('.cashier-mode').show();
                                  $('.payment-mode').hide();
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

function gcrefund()
{
  if(flag==0)
  {
    flag=1;

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
                $.ajax({
                  url:'../ajax-cashier.php?request=truncateGCTempRefundTable'
                });                
                setTimeout(function(){
                  $('#transno,#rbarcode').inputmask("integer", { allowMinus: false});          
                  $('#transno').focus(); 
                },1200);
                             
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
                    else if(modes==2){
                      var barcode = $('#rbarcode').val();                      
                      if(barcode!='')
                      {
                        $.ajax({
                          url:'../ajax-cashier.php?request=checkGCReturnBarcode',
                          data:{barcode:barcode,transno,transno},
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
                    // var barcode = $('#gc-barcode').val();
                    // $.ajax({
                    //   url:'../ajax-cashier.php?request=returngc',
                    //   type:'POST',
                    //   data:{barcode:barcode},
                    //   beforeSend:function(){

                    //   },
                    //   success:function(response){
                    //     var response = JSON.parse(response);
                    //     if(response['message']=='success'){

                    //       BootstrapDialog.show({
                    //         title: 'Confirmation',
                    //           message: 'Are you sure you want to return gc barcode number '+barcode+'?',
                    //           closable: true,
                    //           closeByBackdrop: false,
                    //           closeByKeyboard: true,
                    //           onshow: function(dialog) {
                    //               // dialog.getButton('button-c').disable();
                    //           },
                    //           onhidden:function(dialog){
                    //             $('#gc-barcode').focus();
                    //           },
                    //           buttons: [{
                    //               icon: 'glyphicon glyphicon-ok-sign',
                    //               label: 'Yes',
                    //               cssClass: 'btn-success',
                    //               hotkey: 13,
                    //               action:function(dialogItself){                  
                    //                 dialogItself.close();

                    //                 $.ajax({
                    //                   url:'../ajax-cashier.php?request=confirmreturngc',
                    //                   type:'POST',
                    //                   data:{barcode:barcode},
                    //                   beforeSend:function(){

                    //                   },
                    //                   success:function(response){
                    //                     BootstrapDialog.closeAll();
                    //                     var dialog = new BootstrapDialog({
                    //                       message: function(dialogRef){
                    //                       var $message = $('<div>GC return Successfully Performed.</div>');             
                    //                           return $message;
                    //                       },
                    //                       closable: false
                    //                     });
                    //                     dialog.realize();
                    //                     dialog.getModalHeader().hide();
                    //                     dialog.getModalFooter().hide();
                    //                     dialog.getModalBody().css('background-color', '#86E2D5');
                    //                     dialog.getModalBody().css('color', '#000');
                    //                     dialog.open();
                    //                     setTimeout(function(){
                    //                             dialog.close();
                    //                         }, 1500);
                    //                         setTimeout(function(){
                    //                            BootstrapDialog.closeAll();
                    //                     }, 1700);
                    //                   }
                    //                 });
                    //               }
                    //           }, {
                    //             icon: 'glyphicon glyphicon-remove-sign',
                    //               label: 'No',
                    //               action: function(dialogItself){
                    //                   dialogItself.close();
                    //                   $('#gc-barcode').focus(); 
                    //               }
                    //           }]
                    //       });

                      //   } else {
                      //     $('.response').html('<div class="alert alert-danger danger-o">'+response['message']+'</div>');
                      //     $('#gc-barcode').focus();  
                      //     timeoutmsg();
                      //   }
                    //   }
                    // });
                  
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

    // BootstrapDialog.show({
    //     title: 'GC Refund Module',
    //     closable: true,
    //     closeByBackdrop: false,
    //     closeByKeyboard: true,
    //     message: function(dialog) {
    //       var $message = $("<div><img src='../assets/images/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
    //       var pageToLoad = dialog.getData('pageToLoad');
    //     setTimeout(function(){
    //       $message.load(pageToLoad);
    //     },1000);
    //     return $message;
    //     },
    //     data: {
    //       'pageToLoad': '../dialogcashier/returngc.php',
    //     },
    //     cssClass: 'gcreturn',             
    //     onshown: function(dialogRef){
    //       $.ajax({
    //         url:'../ajax-cashier.php?request=truncateGCTempRefundTable'
    //       });                
    //       setTimeout(function(){
    //         $('#transno,#rbarcode').inputmask("integer", { allowMinus: false});          
    //         $('#transno').focus(); 
    //       },1200);
                       
    //     },
    //     onhidden: function(dialogRef){
    //         $('#numOnly').focus();
    //         flag=0;
    //     },
    //     buttons: [{
    //         icon: 'glyphicon glyphicon-ok ',
    //         label: '  Submit',
    //         cssClass: 'btn-primary',
    //         hotkey: 13, // Enter.
    //         action: function(dialogItself) {
    //           var $button = this;                
    //           var formData = $('form#gcreturn').serialize(), formURL = $('form#gcreturn').attr('action');
    //           var mode = $('input[name=mode]').val(), transno = $('#transno').val();
    //           if(mode==1)
    //           {
    //             if(transno!='')
    //             {
    //               $.ajax({
    //                 url:formURL,
    //                 data:{transno:transno},
    //                 type:'POST',
    //                 success:function(data){                            
    //                   var data = JSON.parse(data);
    //                   if(data['stat'])
    //                   {
    //                     $button.html('<span class="bootstrap-dialog-button-icon glyphicon glyphicon-ok"></span> Scan GC');
    //                     $( "div.fgroup" ).removeClass( "showhide");
    //                     $('input#transno').prop('disabled',true);
    //                     $('label.translbl').text('Transaction No.');
    //                     $('input[name=mode]').val('2');
    //                     $('.response').html('');
    //                     $('#rbarcode').focus(); 
    //                     $('input[name=dot]').val(data['datetrans']);
    //                     $('input[name=store]').val(data['storename']);
    //                     $('input[name=cashier]').val(data['cashier']);
    //                   }
    //                   else 
    //                   {
    //                     $('#transno').select();        
    //                     $('.response').html('<div class="alert alert-danger alertmod">'+data['msg']+'</div>');
    //                   }
    //                 }
    //               });
    //             }
    //             else
    //             {
    //               $('#transno').select();  
    //               $('.response').html('<div class="alert alert-danger alertmod">Please enter transaction no.</div>');
    //             }
                               
    //           }
    //           else if(mode==2){
    //             var barcode = $('#rbarcode').val();                      
    //             if(barcode!='')
    //             {
    //               $.ajax({
    //                 url:'../ajax-cashier.php?request=checkGCReturnBarcode',
    //                 data:{barcode:barcode,transno,transno},
    //                 type:'POST',
    //                 success:function(data)
    //                 {
    //                   var data = JSON.parse(data);
    //                   if(data['stat'])
    //                   { 
    //                     $('.response').html('');
    //                     $('.gctoreturns').html("<img src='../assets/images/ajax-loader.gif'><small class='text-danger'>please wait...</small>");
    //                     $('#rbarcode').select();   
    //                     setTimeout(function(){
    //                       $.ajax({
    //                         url:'../ajax-cashier.php?request=displayGCTempRefund',
    //                         success:function(data1)
    //                         {
    //                           $('.gctoreturns').html(data1);
    //                         }
    //                       });
    //                     },1000);
    //                   }
    //                   else 
    //                   {
    //                     $('.response').html('<div class="alert alert-danger alertmod">'+data['msg']+'</div>');
    //                     $('#rbarcode').select();
    //                   }
    //                 }
    //               });
    //             }
    //             else 
    //             {
    //                $('.response').html('<div class="alert alert-danger alertmod">Please enter GC Barcode.</div>');
    //                $('#rbarcode').select();
    //             }
    //           }
    //         } 
    //           // var barcode = $('#gc-barcode').val();
    //           // $.ajax({
    //           //   url:'../ajax-cashier.php?request=returngc',
    //           //   type:'POST',
    //           //   data:{barcode:barcode},
    //           //   beforeSend:function(){

    //           //   },
    //           //   success:function(response){
    //           //     var response = JSON.parse(response);
    //           //     if(response['message']=='success'){

    //           //       BootstrapDialog.show({
    //           //         title: 'Confirmation',
    //           //           message: 'Are you sure you want to return gc barcode number '+barcode+'?',
    //           //           closable: true,
    //           //           closeByBackdrop: false,
    //           //           closeByKeyboard: true,
    //           //           onshow: function(dialog) {
    //           //               // dialog.getButton('button-c').disable();
    //           //           },
    //           //           onhidden:function(dialog){
    //           //             $('#gc-barcode').focus();
    //           //           },
    //           //           buttons: [{
    //           //               icon: 'glyphicon glyphicon-ok-sign',
    //           //               label: 'Yes',
    //           //               cssClass: 'btn-success',
    //           //               hotkey: 13,
    //           //               action:function(dialogItself){                  
    //           //                 dialogItself.close();

    //           //                 $.ajax({
    //           //                   url:'../ajax-cashier.php?request=confirmreturngc',
    //           //                   type:'POST',
    //           //                   data:{barcode:barcode},
    //           //                   beforeSend:function(){

    //           //                   },
    //           //                   success:function(response){
    //           //                     BootstrapDialog.closeAll();
    //           //                     var dialog = new BootstrapDialog({
    //           //                       message: function(dialogRef){
    //           //                       var $message = $('<div>GC return Successfully Performed.</div>');             
    //           //                           return $message;
    //           //                       },
    //           //                       closable: false
    //           //                     });
    //           //                     dialog.realize();
    //           //                     dialog.getModalHeader().hide();
    //           //                     dialog.getModalFooter().hide();
    //           //                     dialog.getModalBody().css('background-color', '#86E2D5');
    //           //                     dialog.getModalBody().css('color', '#000');
    //           //                     dialog.open();
    //           //                     setTimeout(function(){
    //           //                             dialog.close();
    //           //                         }, 1500);
    //           //                         setTimeout(function(){
    //           //                            BootstrapDialog.closeAll();
    //           //                     }, 1700);
    //           //                   }
    //           //                 });
    //           //               }
    //           //           }, {
    //           //             icon: 'glyphicon glyphicon-remove-sign',
    //           //               label: 'No',
    //           //               action: function(dialogItself){
    //           //                   dialogItself.close();
    //           //                   $('#gc-barcode').focus(); 
    //           //               }
    //           //           }]
    //           //       });

    //             //   } else {
    //             //     $('.response').html('<div class="alert alert-danger danger-o">'+response['message']+'</div>');
    //             //     $('#gc-barcode').focus();  
    //             //     timeoutmsg();
    //             //   }
    //           //   }
    //           // });
            
    //     }, {
    //       icon: 'glyphicon glyphicon-remove-sign',
    //       label: ' Cancel',
    //       cssClass: 'btn-default',
    //       action: function(dialogItself){
    //           dialogItself.close();
    //       }
    //     }]
    // });
  }
}

function voidline()
{
  var rowCount = $('.items-list-table tr').length;
  if(rowCount > 0)
  {
    if(flag==0)
    {
      flag =1;
      $('table tbody._barcodes tr:first').css('background-color','yellow');
      var barcodenum = $('table tbody._barcodes tr:first td:nth-child(2)').text().trim();
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
              'pageToLoad': '../dialogcashier/confirmation.php?msg=3&barcodenum='+barcodenum,
            },
            cssClass: 'confirmation',           
            onshown: function(dialogRef){                   
            },
            onhidden: function(dialogRef){
              flag = 0;
              $('table tbody._barcodes tr:first').css('background-color','#F2F1EF');                           
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
                    url:'../ajax-cashier.php?request=voidline',
                    data:{barcodenum:barcodenum},
                    type:'POST',
                    success:function(data)
                    {
                      console.log(data);
                      var data = JSON.parse(data);
                      if(data['stat'])
                      {
                        dialogItself.close();
                        $('._barcodes').load('../ajax-cashier.php?request=load');
                        $('.sbtotal').load('../ajax-cashier.php?request=total');
                        $('#_cashier_total').load('../ajax-cashier.php?request=amtdue');
                        $('.linediscount').load('../ajax-cashier.php?request=linediscount');
                        $('.docdiscount').load('../ajax-cashier.php?request=docdiscount');
                        $('.receipt-items').load('../ajax-cashier.php?request=receipt');                 
                        $('.noitems').load('../ajax-cashier.php?request=totalitems');
                        alert('GC Barcode # '+barcodenum+' successfully void.');
                      }
                      else 
                      {
                        alert(data['msg']);
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

  // var total_charge = parseInt($('#_cashier_total > input#total_charge').val());
  // if(total_charge>0)
  // {
  //   if(flag==0)
  //   {
  //     $('a.remove:first').focus();
  //     $('table tbody._barcodes tr:first').css('background-color','yellow');
  //   }
  // }
  // else
  // {
  //   if(flag==0)
  //   {
  //     flag=1;
  //     BootstrapDialog.show({
  //     title:'Warning',
  //          message: 'No GC to void.',
  //          cssClass: 'login-dialog',  
  //          onshow: function(dialogRef){
                
  //         },
  //         onshown: function(dialogRef){                 
            
  //         },
  //         onhide: function(dialogRef){
                
  //         },
  //         onhidden: function(dialogRef){                 
  //           $('#numOnly').focus();
  //           flag=0;
  //         }            
  //     });
  //   }
  // }
}

function xyreports()
{
  if(flag==0){
    flag=1;
      BootstrapDialog.show({
        title: 'Print Reports',
          message:  '<div class="row">'+
                    '<div class="col-xs-6">'+
                    '<button id="xreport" class="btn btn-block btn-warning"><span class="glyphicon glyphicon-print"></span> X Report</a></button>'+
                    '</div>'+
                    '<div class="col-xs-6">'+
                    '<button id="yreport" class="btn btn-block btn-warning"><span class="glyphicon glyphicon-print"></span> Y Report</a></button>'+
                    '</div>'+
                    '</div>',
          cssClass: 'print-report',
          closable: true,
          closeByBackdrop: false,
          closeByKeyboard: true,
          onshow: function(dialog) {
              // dialog.getButton('button-c').disable();
          },
          onshown: function(dialog) {
            $('button#xreport').focus();
            $('button#xreport').click(function(){               
              window.open('../cashiering/reports/xreport.php', '_blank');
            });
            $('button#yreport').click(function(){
              $.ajax({
                url:'../ajax-cashier.php?request=checktransstatusy',
                success:function(response){
                  var response = response.trim();
                  if(response=='success'){
                    BootstrapDialog.show({
                      title: 'Confirmation',
                        message: 'Perform Y Report?',
                        closable: true,
                        closeByBackdrop: false,
                        closeByKeyboard: true,
                        onshow: function(dialog) {
                            // dialog.getButton('button-c').disable();
                        },
                        onhidden:function(dialog){
                          $('button#xreport').focus();
                        },
                        buttons: [{
                            icon: 'glyphicon glyphicon-ok-sign',
                            label: 'Yes',
                            cssClass: 'btn-success',
                            hotkey: 13,
                            action:function(dialogItself){                                                      
                              // $.ajax({
                              //   url:'../ajax-cashier.php?request=confirmyperform',
                              //   success:function(response){
                              //     var response = response.trim();
                              //     if(response=='') 
                              //   }
                              // });
                              BootstrapDialog.closeAll();
                              window.open('../cashiering/reports/yreport.php', '_blank');

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
                    var dialog = new BootstrapDialog({
                      message: function(dialogRef){
                      var $message = $('<div>There is no transaction to print.</div>');             
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
                          $('button#xreport').focus();
                    }, 1700);

                  }
                }
              });
            });

          },
          onhidden: function(dialogRef){
              flag=0;                  
          },
          buttons: [{
              icon: 'glyphicon glyphicon-remove-sign',
              label: ' Close',
              cssClass: 'btn-info',                    
              action:function(dialogItself){ 
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
    BootstrapDialog.show({              
        title: 'Supervisor Login',
          message: '<div class="row">'+
                      '<div class="col-lg-12">'+
                        '<p class="p-credit">'+
                          'Username'+
                        '</p>'+
                      '</div>'+
                    '</div>'+
                    '<div class="row">'+
                      '<div class="col-lg-12">'+
                        '<input type="text" class="form-control supervisor-in" id="s-uname">'+
                      '</div>'+
                    '</div>'+
                    '<div class="row">'+
                      '<div class="col-lg-12">'+
                        '<p class="p-credit">'+
                          'ID Number'+
                        '</p>'+
                      '</div>'+
                    '</div>'+
                    '<div class="row">'+
                      '<div class="col-lg-12">'+
                        '<input type="text" class="form-control" id="s-id" maxlength="8">'+
                      '</div>'+
                    '</div>'+
                    '<div class="row">'+
                      '<div class="col-lg-12">'+
                        '<p class="p-credit">'+
                          'Manager Key'+
                        '</p>'+
                      '</div>'+
                    '</div>'+
                    '<div class="row">'+
                      '<div class="col-lg-12">'+
                        '<input type="password" class="form-control" id="s-key">'+
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
                          $('div.has-warning input#numOnly').prop('disabled',false);
                          $('.manager-mode').hide();
                          $('.cashier-mode').show();
                        } else {
                          $('#managerkey').prop('checked', true);
                          $('div.has-warning input#numOnly').prop('disabled',true);
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

/*
  End of day process
  1. Check first if the cashier has temp sales
  2. Get current date transactions 
  3. Check if transaction exist.
  3. if 0 transaction show warning dialog
  4. else check if eos already performed
  6. update endofday table
  7. show report
*/

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
                      message: 'Perform end of day ?',
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
                                // if(response=='success')
                                // {
                                //   window.open('../cashiering/reports/zreport.php', '_blank');
                                // } else {
                                //   var dialog = new BootstrapDialog({
                                //     message: function(dialogRef){
                                //     var $message = $('<div>'+response+'</div>');             
                                //         return $message;
                                //     },
                                //     closable: false
                                //   });
                                //   dialog.realize();
                                //   dialog.getModalHeader().hide();
                                //   dialog.getModalFooter().hide();
                                //   dialog.getModalBody().css('background-color', '#86E2D5');
                                //   dialog.getModalBody().css('color', '#000');
                                //   dialog.open();
                                //   setTimeout(function(){
                                //           dialog.close();
                                //       }, 2000);
                                //   setTimeout(function(){                                    
                                //   }, 1700);
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
            }
            else 
            {
              if(flag==0)
              {
                flag=1;
                BootstrapDialog.show({
                title:'Warning',
                     message: data1['msg'],
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
                 message: data['msg'],
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

function reloadpage()
{
  window.location='index.php';
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
          $('div.has-warning input#numOnly').prop('disabled',false);
          $('div.has-warning input#numOnly').focus();
        }
      }
    });
    flag=0;
  }
}

function logoutuser()
{
  if(flag==0){
    flag=1;
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
                    total = parseInt(price) + parseInt(payment);
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

function cashrevalpayment()
{
  var revpayment = parseInt($('#payment').val());
  if(revpayment>0)
  {
    if(reval==0)
    {
      var cashpaymentype=2;
      reval=1;
      BootstrapDialog.show({
        title: 'Revalidation Cash Payment',
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
                $('.responsecash').html('<div class="alert alert-danger danger-o" id="_emp_cash">Cash is not enough.</div>');
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
                      var total = addCommas(parseInt(data['total']).toFixed(2));
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
                          $.ajax({
                            url:formURL,
                            data:formData,
                            type:'POST',
                            success:function(data){
                              console.log(data);
                              var data = JSON.parse(data);
                              if(data['stat'])
                              {
                                dialogRef.close();
                                var total = addCommas(parseInt(data['total']).toFixed(2));
                                var sub = addCommas(parseInt(data['sub']).toFixed(2));
                                var cusdiscount = addCommas(parseInt(data['discount']).toFixed(2));
                                var linedisc = addCommas(parseInt(data['linedisc']).toFixed(2));
                                var docdisc = addCommas(parseInt(data['docdisc']).toFixed(2));
                                // total = total.toFixed(2);
                                // total = addCommas(total);
                                //dri
                                items = addCommas(parseInt(data['noitems']));
                                $('h3.transactnum span').html(data['transactnum']);
                                $('.receipt-footer').html('<table class="table tablefooterrec">'+
                                  '<tr>'+
                                    '<td>Sub-total </td>'+
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
                                '</table>'+
                                '<div class="col-xs-12 cashiername">'+
                                  data['fullname']+
                                '</div>'+
                                '<div class="col-xs-12 cashiersig">'+
                                  '<span class="cashiersigspan">Customer Signature</span>'+
                                '</div><br /><br />');
                                window.print();
                                //dri
                                $('._barcodes').load('../ajax-cashier.php?request=load');
                                $('.sbtotal').load('../ajax-cashier.php?request=total');
                                $('#_cashier_total').load('../ajax-cashier.php?request=amtdue');
                                $('.linediscount').load('../ajax-cashier.php?request=linediscount');
                                $('.docdiscount').load('../ajax-cashier.php?request=docdiscount');
                                $('.receipt-items').load('../ajax-cashier.php?request=receipt');                 
                                $('.noitems').load('../ajax-cashier.php?request=totalitems');
                                $('span.cashr').text('₱ 0.00');
                                $('span.changer').text('₱ 0.00');
                                // $('span.cashr').text('₱ 0.00');
                                // $('span.changer').text('₱ 0.00');
                                // $('._barcodes').load('../ajax-cashier.php?request=load');
                                // $('.receipt-items').load('../ajax-cashier.php?request=receipt');
                                $('.cashier-mode').show();
                                $('.payment-mode').hide();
                                mode = 0;
                              }
                              else 
                              {
                                $('.response').html('<div class="alert alert-danger">'+data['msg']+'</div>');
                              }
                            }
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
          cssClass: 'btn-primary',
          action: function(dialogItself){
              dialogItself.close();
          }
      }]
    });
  }
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
                        $.ajax({
                          url:formURL,
                          data:formData,
                          type:'POST',
                          success:function(data){
                            console.log(data);
                            var data = JSON.parse(data);
                            if(data['stat'])
                            {
                              var total = addCommas(parseInt(data['total']).toFixed(2));
                              var sub = addCommas(parseInt(data['sub']).toFixed(2));
                              var cusdiscount = addCommas(parseInt(data['discount']).toFixed(2));
                              var linedisc = addCommas(parseInt(data['linedisc']).toFixed(2));
                              var docdisc = addCommas(parseInt(data['docdisc']).toFixed(2));
                              // total = total.toFixed(2);
                              // total = addCommas(total);
                              //dri
                              items = addCommas(parseInt(data['noitems']));
                              $('h3.transactnum span').html(data['transactnum']);
                              $('.receipt-footer').html('<table class="table tablefooterrec">'+
                                '<tr>'+
                                  '<td>Sub-total </td>'+
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
                                  '<td>Head Office</td>'+
                                  '<td></td>'+
                                '</tr>'+
                              '</table>'+
                              '<div class="col-xs-12 cashiername">'+
                                data['fullname']+
                              '</div>'+
                              '<div class="col-xs-12 cashiersig">'+
                                '<span class="cashiersigspan">Customer Signature</span>'+
                              '</div><br /><br />');
                              dialogRef.close();
                              window.print();
                              $('._barcodes').load('../ajax-cashier.php?request=load');
                              $('.sbtotal').load('../ajax-cashier.php?request=total');
                              $('#_cashier_total').load('../ajax-cashier.php?request=amtdue');
                              $('.linediscount').load('../ajax-cashier.php?request=linediscount');
                              $('.docdiscount').load('../ajax-cashier.php?request=docdiscount');
                              $('.receipt-items').load('../ajax-cashier.php?request=receipt');                 
                              $('.noitems').load('../ajax-cashier.php?request=totalitems');
                              $('span.cashr').text('₱ 0.00');
                              $('span.changer').text('₱ 0.00');
                              // $('span.cashr').text('₱ 0.00');
                              // $('span.changer').text('₱ 0.00');
                              // $('._barcodes').load('../ajax-cashier.php?request=load');
                              // $('.receipt-items').load('../ajax-cashier.php?request=receipt');
                              $('.cashier-mode').show();
                              $('.payment-mode').hide();
                              mode = 0;
                            }
                            else 
                            {
                              $('.response').html('<div class="alert alert-danger">'+data['msg']+'</div>');
                            }
                          }
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

function discount()
{
  if(flag==0)
  {
    flag=1;
    BootstrapDialog.show({
        title: 'Discount Module',
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
          'pageToLoad': '../dialogcashier/discount.php',
        },
        cssClass: 'gcdiscount',             
        onshown: function(dialogRef){
          setTimeout(function(){
            $('input[name=percent],input[name=amount],input[name=barcodefordis]').inputmask();
            $('#linedis').focus();
          },1120);                       
        },
        onhidden: function(dialogRef){                  
          flag=0;
        }
    });
  }
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
                        //goooo
                        if($('select[name=discountype]').val()!='')
                        {
                          if($('#den').val()!=$('#totval').val())
                          {
                            var barcode = $('input[name=barcodefordis]').val();
                            var discountype = $('select[name=discountype]').val();
                            var percent = $('input[name=percent]').val();
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
                                  $('.sbtotal').load('../ajax-cashier.php?request=total');
                                  $('#_cashier_total').load('../ajax-cashier.php?request=amtdue');
                                  $('.linediscount').load('../ajax-cashier.php?request=linediscount');            
                                  $('.receipt-items').load('../ajax-cashier.php?request=receipt');

                                  $('#barcodefordis').val('').prop('readonly',false);
                                  $('#percent, #amount, #den, #totval, #denom, #flaglinedis').val(0);
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
        alert('No Transaction to discount.');
      }
    }

  });
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
              title: 'Transaction Discount',
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
                    var p = $('#percent').val();
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
                              $('.sbtotal').load('../ajax-cashier.php?request=total');
                              $('#_cashier_total').load('../ajax-cashier.php?request=amtdue');
                              $('.linediscount').load('../ajax-cashier.php?request=linediscount');
                              $('.docdiscount').load('../ajax-cashier.php?request=docdiscount');
                              alert('Transaction Successfully Discounted.');
                            }
                            else 
                            {
                              $('.response').html('<div class="alert alert-danger nomarginbot alert-font">'+data['msg']+'</div');
                            }
                          }
                        })
                      }
                      else 
                      {
                        $('.response').html('<div class="alert alert-danger nomarginbot alert-font">Please input percent/amount.</div');
                      }
                    }
                    else 
                    {
                      $('.response').html('<div class="alert alert-danger nomarginbot alert-font">Please select discount type.</div');
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
                          $('.sbtotal').load('../ajax-cashier.php?request=total');    
                          $('._barcodes').load('../ajax-cashier.php?request=load');
                          $('#_cashier_total').load('../ajax-cashier.php?request=amtdue');
                          $('.linediscount').load('../ajax-cashier.php?request=linediscount');                        
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
                          $('#_cashier_total').load('../ajax-cashier.php?request=amtdue');
                          $('.docdiscount').load('../ajax-cashier.php?request=docdiscount');
                        
                          alert('Transaction Discount Successfully Removed.');
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

function linedis()
{
  $('.line-buts').show();
  $('.dis-buts').hide();
  $('input[name=barcodefordis]').focus();
  $('input[name=distypec]').val(1);
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

function linedispercent(dscnt)
{
  var den = $('#den').val();
  t = parseFloat(den*dscnt);
  if(t >= den)
  {
    $('#percent').val(0);
  }
  else 
  {
    to = addCommas(t.toFixed(2));
    $('#amount').val(to);
    l = parseFloat(den - t);
    $('#totval').val(l);
    $('#tot').val(addCommas(l.toFixed(2)));
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
  t = parseFloat(amt*dscnt);
  if(t >= amt)
  {
    $('#percent').val(0);
  }
  else 
  {
    to = addCommas(t.toFixed(2));
    $('#amount').val(to);
    l = parseFloat(amt - t);
    $('#totval').val(l);
    $('#tot').val(addCommas(l.toFixed(2)));   
  }
}

function docamount(dscnt)
{
  var dscnt = parseFloat(removecomma(dscnt));
  var amt = parseFloat($('#amtdue').val());
  if(dscnt>=amt)
  {
    $('#amount').val(0);
    $('#totval').val(amt);
    $('#tot').val(addCommas(amt.toFixed(2)));
  }
  else 
  {
    l = parseFloat(amt - dscnt);
    $('#totval').val(l);
    $('#tot').val(addCommas(l.toFixed(2)));   
  }

}

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
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
              title: 'Generate POS Report',
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
                      // $button.disable();

                      var d1 = $('input[name=start]').val();
                      var d2 = $('input[name=end]').val();
                      var trans = $('select[name=trans]').val();                      
                      if(d1!=undefined)
                      {
                        if(d1!='' && d2!='')
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
                                var pathArray = window.location.pathname.split( '/' );
                                var path = pathArray[1];
                                var proto = window.location.protocol;

                                var siteurl = proto+'//'+root+'/'+path+'/cashiering/createposreport.php?d1='+d1+'&'+'d2='+d2+'&trans='+trans;

                                window.location.href = siteurl;
                              }
                              else 
                              {
                                $('.response').html('<div class="alert alert-danger alert-med">'+data['msg']+'</div>'); 
                              }
                            }
                          });
                        }
                        else 
                        {
                          $('.response').html('<div class="alert alert-danger alert-med">Please fill-up date.</div>');
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
              title: 'Cashier End Shift Report',
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
                                        'Are you sure you want to create cashier end of shift report?'+
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