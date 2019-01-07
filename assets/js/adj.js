$(document).ready(function()
{
	$('#amount').inputmask();
	$('#cbdis').inputmask();

	$('.denfield').inputmask();

    $('#adjust-list').dataTable( {
        "pagingType": "full_numbers",
        "ordering": false,
        "processing": true
    });

    $("#adjust-list_length").css("display", "none");

    $('table#adjust-list').on('click','tbody tr td button.btn',function(){
    	var id = $(this).attr('prod-id');
    	alert(id);
    });	

	$('form#allocateForm, form#adjallocateForm').on('change','#gctype',function(){
		var gctype = $(this).val();
		var store = $('#store-selected').val();

		if(store!='')
		{
			if(store!=''&&gctype!='')
			{
				disablenum();
			}
			else{
				enablenum();		
			}
			$("[id^=num]").val("0");
			changestore(store,gctype);
			// validatedgc();
		}
	});

	$('form#allocateForm, form#adjallocateForm').on('change','#store-selected',function(){
		var store = $(this).val();
		$("[id^=num]").val("0");
		var gctype = $('#gctype').val();
		if(store!='')
		{
			disablenum();
			changestore(store,gctype);
		}
		else{
			enablenum();
			$('.storesele').html('');
			$("[id^=num]").val("0");		
		}
		// validatedgc();
	});

	$('form#allocateForm, form#adjallocateForm').on('change','#adjtype',function(){
		var type = $(this).val();
		$('[class^=nhid]').each(function(){
			var c = $(this).attr('class');
			var n = c.substring(4,c.length);
			var q = $(this).val();
			$('span#n'+n).text(q);
		});
		if(type=='n')
		{
			$('.gc-type-hide').hide();
			$('.gc-type-hide select#gctype').prop('required',false);
		}
		else 
		{
			$('.gc-type-hide').show();
			$('.gc-type-hide select#gctype').prop('required',true);
		}
		var store = $('#store-selected').val();
		$("[id^=num]").val("0");
		// validatedgc();

	});

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
				},1000)
				
			}
		});
	}

	function validatedgc()
	{
		$.ajax({
			url:'../ajax.php?action=validatedGCList',
			type:'POST',
			beforeSend:function(){

			},
			success:function(response){
				$('.valgc-alloc').html(response);
			}
		});
	}

	function disablenum()
	{
		$("[id^=num]").prop('disabled',false);
	}

	function enablenum()
	{
		$("[id^=num]").prop('disabled',true);
	}

    $('.form-container').on('submit','form#adjallocateForm',function(){
    	$('.response').html('');
    	$('#btn').attr('disabled',true);
    	var formUrl = $(this).attr('action'), formData = $(this).serialize();
    	var NotEmpty = false;
		$('.num').each(function(){
			if($(this).val()!='0')
			{
				NotEmpty = true;
			}
		});
			if(NotEmpty)
			{
		        BootstrapDialog.show({
		        	title: 'Confirmation',
		            message: 'Save GC Allocation Adjustment?',
		            closable: true,
		            closeByBackdrop: false,
		            closeByKeyboard: true,
		            onshow: function(dialog) {
		                // dialog.getButton('button-c').disable();
		            },
		            onhidden:function(dialog){
		            	$('#btn').attr('disabled',false);
		            },
		            buttons: [{
		                icon: 'glyphicon glyphicon-ok-sign',
		                label: 'Ok',
		                cssClass: 'btn-primary',
		                hotkey: 13,
		                action:function(dialogItself){
		                	$button = this;
		                	$button.disable();
		                	BootstrapDialog.closeAll();
		                	$.ajax({
		                		url:formUrl,
		                		type:'POST',
		                		data:formData,
		                		beforeSend:function()
		                		{

		                		},
		                		success:function(data)
		                		{
		                			console.log(data);
		                			var data = JSON.parse(data);
		                			if(data['st'])
		                			{
										var dialog = new BootstrapDialog({
							            message: function(dialogRef){
							            var $message = $('<div>GC Allocation Adjustment Successfully Performed.</div>');			        
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
		                				$('.response').html('<div class="alert alert-danger alert-dismissable">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
		                			}

		        //         			var res = data.trim();
		        //         			if(res=='success')
		        //         			{
										// var dialog = new BootstrapDialog({
							   //          message: function(dialogRef){
							   //          var $message = $('<div>Allocation Successfully Adjusted.</div>');			        
							   //              return $message;
							   //          },
							   //          closable: false
								  //       });
								  //       dialog.realize();
								  //       dialog.getModalHeader().hide();
								  //       dialog.getModalFooter().hide();
								  //       dialog.getModalBody().css('background-color', '#0088cc');
								  //       dialog.getModalBody().css('color', '#fff');
								  //       dialog.open();
								  //       setTimeout(function(){
					     //                	dialog.close();
					     //           		}, 1500);
					     //           		setTimeout(function(){
					     //                	window.location.reload();
					     //           		}, 1700);

		        //         			}
		        //         			else
		        //         			{
		        //         				$('.response').html('<div class="alert alert-danger alert-dismissable">'+data+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
		        //         			}
		                		}
		                	});						
		                }
		            }, {
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
				$('.response').html('<div class="alert alert-danger alert-dismissable">Please input at least one quantity field.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
			}
        return false;
    });

    $(function(){
        $("[id^=num]").keyup(function(){
        	var adj = $('#adjtype').val();
        	var gctype = $('#gctype').val();
        	var store = $('#store-selected').val();

        	if(gctype!=''&& store!='')
        	{
	        	if(adj=='n')
	        	{
	        		var num = $(this).val();
	        		num = num.replace(/,/g , "");
	        		var nid = $(this).attr('id');
	        		var n = nid.substring(3,nid.length);
	        		var x = $('input[type=hidden]#x'+n).val();
	        		var t = 0;
	        		t = x - num;
	        		$('span#x'+n).text(t);
	        		if(t<0)
	        		{
	        			$("#btn").attr("disabled",true);
	        		}
	        		else 
	        		{
	        			$("#btn").attr("disabled",false);
	        		}
		        //     var num1 = $("[id='num1']").val();
		        //     num1 = num1.replace(/,/g , "");
		        //     var num2 = $("[id='num2']").val();
		        //     num2 = num2.replace(/,/g , "");
		        //     var num3 = $("[id='num3']").val();
		        //     num3 = num3.replace(/,/g , "");
		        //     var num4 = $("[id='num4']").val();
		        //     num4 = num4.replace(/,/g , "");
		        //     var num5 = $("[id='num5']").val();
		        //     num5 = num5.replace(/,/g , "");
		        //     var num6 = $("[id='num6']").val();
		        //     num6 = num6.replace(/,/g , "");
		        //     var aa = $("[id='x1']").val();
		        //     var bb = $("[id='x2']").val();
		        //     var cc = $("[id='x3']").val();
		        //     var dd = $("[id='x4']").val();
		        //     var ee = $("[id='x5']").val();      
		        //     var ff = $("[id='x6']").val();      
		        //     var sum1 = Number(num1);
		        //     var sum2 = Number(num2);
		        //     var sum3 = Number(num3);
		        //     var sum4 = Number(num4);
		        //     var sum5 = Number(num5);
		        //     var sum6 = Number(num6);
		        //     if(sum1 > aa || sum2 > bb || sum3 > cc || sum4 > dd || sum5 > ee || sum6 > ff){
		        //             $("#btn").attr("disabled",true);
		        //     }
		        //     else{
		        //         $("#btn").attr("disabled", false);
		        //     }
		        //     var d = aa - sum1;
		        //     var e = bb - sum2;
		        //     var f = cc - sum3;
		        //     var g = dd - sum4;
		        //     var h = ee - sum5;
		        //     var i = ff - sum6;
		        //     $("span[id=x1]").text(d);
		        //     $("span[id=x2]").text(e);
		        //     $("span[id=x3]").text(f);
		        //     $("span[id=x4]").text(g);
		        //     $("span[id=x5]").text(h);
		        //     $("span[id=x6]").text(i);
		        } 
		        else 
		        {
	        		var num = $(this).val();
	        		num = num.replace(/,/g , "");
	        		var nid = $(this).attr('id');
	        		var n = nid.substring(3,nid.length);
	        		var x = $('input[type=hidden]#n'+n).val();
	        		var t = 0;
	        		t = x - num;
	        		$('span#n'+n).text(t);
	        		if(t<0)
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
		            // var aa = $("[id='n1']").val();
		            // var bb = $("[id='n2']").val();
		            // var cc = $("[id='n3']").val();
		            // var dd = $("[id='n4']").val();
		            // var ee = $("[id='n5']").val();      
		            // var ff = $("[id='n6']").val();      
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
		        }
		    }
		    else 
		  	{
		  		alert('Please select Store and GC type.');
		  	}

        });
    });	

	var current_budget = $('#_curbud').val();

	$('form#gcAdjustEntry').on('change','#_denomAdj',function(){
		var denId = $(this).val();
		if(denId!='')
		{
			$.ajax({
				url:'../ajax.php?action=gcAdjustmentSelectDenom',
				type:'POST',
				data:{denId:denId},
				beforseSend:function(){

				},
				success:function(data){
					$('.row div.denom-details').html(data);
				}
			});
			$('input#remarks, input#cbdis').prop("disabled",false);
		} 
		else
		{
			$('.row div.denom-details').html('');
			$('input#remarks, input#cbdis').prop("disabled",true);			
		}
		$('form#gcAdjustEntry input#cbdis').val(0);

	});

	$('.denom-details').on('click','#_viewgc',function(){
		var denom = $(this).attr('denomid');

	});

	$('form#gcAdjustEntry').on('change','select#_adj_type',function(){
		$('form#gcAdjustEntry input#cbdis').val(0);
		r = parseInt($('.denom-details input#_gcforvhid').val());
		$('.denom-details input#_gcforv').val(r);
	});	

	$('form#gcAdjustEntry').on('keyup','input#cbdis',function(){
		var qty = $(this).val(),total=0;
		qty = parseInt(remove(qty))||0;

		var r = parseInt($('.denom-details input#_gcforvhid').val());
		if($('form#gcAdjustEntry select#_adj_type').val() =='p')
		{
			total = qty + r;			
		}
		else 
		{
			total = r - qty;
			if(qty > r)
			{
				total = r;
				total = $('form#gcAdjustEntry input#cbdis').val(total);
				total = 0;
			}
		}
		total = addCommas(total);
		$('.denom-details input#_gcforv').val(total);

	});	

	$('.form-container').on('submit','form#_budgetEntryAdj',function(){

        BootstrapDialog.show({
        	title: 'Confirmation',
            message: 'Perform Action?',
            closable: true,
            closeByBackdrop: false,
            closeByKeyboard: true,
            onshow: function(dialog) {
                // dialog.getButton('button-c').disable();
                $('#btn').prop('disabled',true);
            },
            onhidden:function(dialog){
            	$('#btn').prop('disabled',false);
            },
            buttons: [{
                icon: 'glyphicon glyphicon-ok-sign',
                label: 'Yes',
                cssClass: 'btn-primary',
                hotkey: 13,
                action:function(dialogItself)
                {
                	$button = this;
                	$button.disable();                	
                	var formUrl = $('form#_budgetEntryAdj').attr('action'), formData = $('form#_budgetEntryAdj').serialize();
                	$.ajax(
                	{
                		url:formUrl,
                		type:"POST",
                		data:formData,
                		beforseSend:function()
                		{

                		},
                		success:function(data)
                		{
                			dialogItself.close();
                			console.log(data);
                			var data = JSON.parse(data);
                			if(data['st'])
                			{
								var dialog = new BootstrapDialog({
					            message: function(dialogRef){
					            var $message = $('<div>Budget Successfully Adjusted.</div>');			        
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
                			}
                			else
                			{
            					$('.response').html('<div class="alert alert-danger">'+data['msg']+'</div>');
                			}


                			// dialogItself.close();
                			// var data = JSON.parse(data);
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

	$('#amount').keyup(function()
	{
		var amt = $(this).val();
		var adj = $('#_adj_type').val();
		var cb = $('#_cb').val();
		if(adj=='pos')
		{
			budgetAdjPos(amt,cb);
		}
		else if(adj=='neg') 
		{
			budgetAdjNeg(amt,cb);
		}
	});

	$('form#_budgetEntryAdj').on('change','#groupma',function(){
		var grp = $(this).val();
		$.ajax({
			url:'../ajax.php?action=marketingbudgetadj',
			type:'POST',
			data:{grp:grp},
			success:function(data)
			{
				var data = JSON.parse(data);
				var amt = addCommas(data['msg'].toFixed(2));

				$('input[type=hidden]#_cb').val(amt);
				$('#cbdis').val(data['msg']);
				$('#amount').val(0);
			}
		});
	});

	$('form#_budgetEntryAdj').on('change','#_adj_type',function(){
		$('#amount').val('0');
		var cb = $('#_cb').val();
		$('#cbdis').val(cb);	
	});

	function budgetAdjNeg(amt,cb)
	{
		amt = remove(amt);
		cb = remove(cb);
		var total = parseFloat(cb - amt);
		cb = parseFloat(cb);
		if(amt<=cb)
		{
			$('#cbdis').val(total.toFixed(2));	
		} else {
			$('#amount').val(cb.toFixed(2));
			$('#cbdis').val('0');	
		}
	}

	function budgetAdjPos(amt,cb)
	{
		amt = remove(amt);
		amt = parseFloat(amt)||0;
		cb = remove(cb);
		cb = parseFloat(cb);

		var total =cb + amt;
		if(total!='NaN')
		{			
			$('#cbdis').val(total.toFixed(2));	
		} 
		else 
		{
			$('#cbdis').val(cb);	
		}
	}

	function remove(amt)
	{
		amt = amt.replace(/,/g , "");			
		return amt;
	}

	function removefirst(amt)
	{
		amt = amt.substring(1, amt.length);
		return amt;		
	}

	function removelastthree(amt){
		amt = amt.substring(0, amt.length - 3);
		return amt;
	}

	function timeoutmsg(){
	    setTimeout(function(){
	    	$('.response').html('');
	    }, 4000);
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