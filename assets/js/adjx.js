$(document).ready(function()
{
	$('#amount').inputmask();
	$('#cbdis').inputmask();

	$('#num1').inputmask();
	$('#num2').inputmask();
	$('#num3').inputmask();
	$('#num4').inputmask();
	$('#num5').inputmask();
	$('#num6').inputmask();

	$('.form-container').on('change','#proadj',function(){
		var adj = $(this).val();
		if(adj=='')
		{			
			$('.num, #_remarks').prop('disabled',true).val('');
		}
		else 
		{
			$('.num,  #_remarks').prop('disabled',false).val('');
		}
		var x1  = $('#x1').val();
		$('#n1').val(x1);
		var x2 = $('#x2').val();
		$('#n2').val(x2);
		var x3  = $('#x3').val();
		$('#n3').val(x3);
		var x4  = $('#x4').val();
		$('#n4').val(x4);
		var x5  = $('#x5').val();
		$('#n5').val(x5);
		var x6  = $('#x6').val();
		$('#n6').val(x6);
	});

	$('.form-container').on('keyup','input.num',function(){				
		var id = $(this).attr('id'), adj = $('#proadj').val();
		var cb = $('input#current-budget').val();
		dnum = id.substring(3, id.length);
		var gc = $('#x'+dnum).val();
		var qty = $(this).val(), total=0;
		qty = Number(qty.replace(/,/g , ""));		
		if(adj=='n')
		{
			total = gc - qty;
			if(qty >= gc)
			{
				$('#num'+dnum).val(gc);
				$('#n'+dnum).val(0);
			}
			else
			{
				$('#n'+dnum).val(total);
			}
		}
		else 
		{
			total = parseInt(gc) + parseInt(qty);
			$('#n'+dnum).val(total);
		}
	});

	$('form#allocateForm, form#adjallocateForm').on('change','#gctype',function(){
		var gctype = $(this).val();
		var store = $('#store-selected').val();
		if(store!=''&&gctype!='')
		{
			disablenum();
		}
		else{
			enablenum();		
		}
		$("[id^=num]").val("0");
		changestore(store);
		validatedgc();
	});

	$('form#allocateForm, form#adjallocateForm').on('change','#store-selected',function(){
		var store = $(this).val();
		var gctype = $('#gctype').val();


		if(store!='')
		{
			disablenum();
		}
		else{
			enablenum();		
		}
		$("[id^=num]").val("0");
		changestore(store);
		validatedgc();
	});

	$('form#allocateForm, form#adjallocateForm').on('change','#adjtype',function(){
		var type = $(this).val();
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
		changestore(store);
		validatedgc();

	});

	function changestore(store)
	{
		$.ajax({
			url:'../ajax.php?action=checkStoreForAllocate',
			type:'POST',
			data:{store:store},
			beforeSend:function(){

			},
			success:function(response){
				$('.storesele').html(response);
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
		$('#num1').prop('disabled',false);
		$('#num2').prop('disabled',false);
		$('#num3').prop('disabled',false);
		$('#num4').prop('disabled',false);
		$('#num5').prop('disabled',false);
		$('#num6').prop('disabled',false);
	}

	function enablenum()
	{
		$('#num1').prop('disabled',true);
		$('#num2').prop('disabled',true);
		$('#num3').prop('disabled',true);
		$('#num4').prop('disabled',true);
		$('#num5').prop('disabled',true);
		$('#num6').prop('disabled',true);
	}

    $('.form-container').on('submit','form#adjallocateForm',function(){
    	var formUrl = $(this).attr('action'), formData = $(this).serialize();
	        BootstrapDialog.show({
	        	title: 'Confirmation',
	            message: 'Perform GC Allocation Adjustment?',
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
	                	dialogItself.close();
	                	$.ajax({
	                		url:formUrl,
	                		type:'POST',
	                		data:formData,
	                		beforeSend:function()
	                		{

	                		},
	                		success:function(data)
	                		{
	                			var res = data.trim();
	                			if(res=='success')
	                			{
									var dialog = new BootstrapDialog({
						            message: function(dialogRef){
						            var $message = $('<div>Allocation Successfully Adjusted.</div>');			        
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
	                				$('.response').html('<div class="alert alert-danger alert-dismissable">'+data+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
	                			}

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
		            var num1 = $("[id='num1']").val();
		            num1 = num1.replace(/,/g , "");
		            var num2 = $("[id='num2']").val();
		            num2 = num2.replace(/,/g , "");
		            var num3 = $("[id='num3']").val();
		            num3 = num3.replace(/,/g , "");
		            var num4 = $("[id='num4']").val();
		            num4 = num4.replace(/,/g , "");
		            var num5 = $("[id='num5']").val();
		            num5 = num5.replace(/,/g , "");
		            var num6 = $("[id='num6']").val();
		            num6 = num6.replace(/,/g , "");
		            var aa = $("[id='x1']").val();
		            var bb = $("[id='x2']").val();
		            var cc = $("[id='x3']").val();
		            var dd = $("[id='x4']").val();
		            var ee = $("[id='x5']").val();      
		            var ff = $("[id='x6']").val();      
		            var sum1 = Number(num1);
		            var sum2 = Number(num2);
		            var sum3 = Number(num3);
		            var sum4 = Number(num4);
		            var sum5 = Number(num5);
		            var sum6 = Number(num6);
		            if(sum1 > aa || sum2 > bb || sum3 > cc || sum4 > dd || sum5 > ee || sum6 > ff){
		                    $("#btn").attr("disabled",true);
		            }
		            else{
		                $("#btn").attr("disabled", false);
		            }
		            var d = aa - sum1;
		            var e = bb - sum2;
		            var f = cc - sum3;
		            var g = dd - sum4;
		            var h = ee - sum5;
		            var i = ff - sum6;
		            $("span[id=x1]").text(d);
		            $("span[id=x2]").text(e);
		            $("span[id=x3]").text(f);
		            $("span[id=x4]").text(g);
		            $("span[id=x5]").text(h);
		            $("span[id=x6]").text(i);
		        } 
		        else 
		        {
		            var num1 = $("[id='num1']").val();
		            num1 = num1.replace(/,/g , "");
		            var num2 = $("[id='num2']").val();
		            num2 = num2.replace(/,/g , "");
		            var num3 = $("[id='num3']").val();
		            num3 = num3.replace(/,/g , "");
		            var num4 = $("[id='num4']").val();
		            num4 = num4.replace(/,/g , "");
		            var num5 = $("[id='num5']").val();
		            num5 = num5.replace(/,/g , "");
		            var num6 = $("[id='num6']").val();
		            num6 = num6.replace(/,/g , "");
		            var aa = $("[id='n1']").val();
		            var bb = $("[id='n2']").val();
		            var cc = $("[id='n3']").val();
		            var dd = $("[id='n4']").val();
		            var ee = $("[id='n5']").val();      
		            var ff = $("[id='n6']").val();      
		            var sum1 = Number(num1);
		            var sum2 = Number(num2);
		            var sum3 = Number(num3);
		            var sum4 = Number(num4);
		            var sum5 = Number(num5);
		            var sum6 = Number(num6);
		            if(sum1 > aa || sum2 > bb || sum3 > cc || sum4 > dd || sum5 > ee || sum6 > ff){
		                    $("#btn").attr("disabled",true);
		            }
		            else{
		                $("#btn").attr("disabled", false);
		            }
		            var d = aa - sum1;
		            var e = bb - sum2;
		            var f = cc - sum3;
		            var g = dd - sum4;
		            var h = ee - sum5;
		            var i = ff - sum6;
		            $("span[id=n1]").text(d);
		            $("span[id=n2]").text(e);
		            $("span[id=n3]").text(f);
		            $("span[id=n4]").text(g);
		            $("span[id=n5]").text(h);
		            $("span[id=n6]").text(i);
		        }
		    }
		    else 
		  	{
		  		alert('Please select Store and GC type.');
		  	}

        });
    });	

    $('.box').on('click','button#view-allocated-gc',function(){
        var id = $(this).attr('storeid');
        BootstrapDialog.show({
            title: 'Allocated GC',
            message: $('<div></div>').load('../dialogs/view-allocated-gc.php?id='+id),
            cssClass: 'modal-allocated-gc',
            closable: true,
            closeByBackdrop: false,
            closeByKeyboard: true,
            onshow: function(dialog) {
                // dialog.getButton('button-c').disable();
            },
            onshown: function(dialogRef){
                    $('#allocated-gc').dataTable({
                        "pagingType": "full_numbers",
                        "ordering": false,
                        "processing": true
                    });

                    $("#allocated-gc_length").css("display", "none");
            },
            buttons:[ {
                icon: 'glyphicon glyphicon-remove-sign',
                label: 'Close',
                action: function(dialogItself){
                    dialogItself.close();
                }
            }]
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

	$('.form-container').on('submit','form#gcAdjustEntry',function(){
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
	        	title: 'Confirm',
	            message: 'Perform Action?',
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
	                action:function(dialogItself)
	                {
	                	dialogItself.close();
						$.ajax({
							url:formUrl,
							data:formData,
							type:'POST',
							success:function(data){
								var res = data.trim();
								if(res=='success')
								{
									var dialog = new BootstrapDialog({
						            message: function(dialogRef){
						            var $message = $('<div>Production Successfully Adjusted.</div>');			        
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
									$('.response').html('<div class="alert alert-danger alert-dismissable">Error Occured.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
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
		else 
		{
			$('.response').html('<div class="alert alert-danger alert-dismissable">Please input at least 1 quantity field.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
			timeoutmsg();
		}
		return false;
	});

	

	$('.form-container').on('submit','form#_budgetEntryAdj',function(){
        BootstrapDialog.show({
        	title: 'Confirm',
            message: 'Perform Action?',
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
                action:function(dialogItself)
                {                	
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
                			var res = data.trim();
                			if(res=='success')
                			{
                				dialogItself.close();
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
			                    	window.location.reload();
			               		}, 1700);
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

	$('form#_budgetEntryAdj').on('change','#_adj_type',function(){
		$('#amount').val('0');
		var cb = $('#_cb').val();
		cb = removelastthree(cb);
		$('#cbdis').val(cb);	
	});

	function budgetAdjNeg(amt,cb)
	{
		amt = remove(amt);
		amt = removefirst(amt);
		cb = remove(cb);
		cb = removelastthree(cb);	
		var total = cb - amt;
		cb = parseInt(cb);
		if(amt<=cb)
		{
			$('#cbdis').val(total);	
		} else {
			$('#amount').val(cb);
			$('#cbdis').val('0');	
		}
	}

	function budgetAdjPos(amt,cb)
	{
		amt = remove(amt);
		amt = parseInt(removefirst(amt))||0;
		cb = remove(cb);
		cb = parseInt(removelastthree(cb));

		var total =cb + amt;
		if(total!='NaN')
		{			
			$('#cbdis').val(total);	
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