$(document).ready(function(){

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
			$('span#adj-sign').text('');
		}
		else 
		{
			$('.num,  #_remarks').prop('disabled',false).val('');
			if(adj=='n')
			{
				$('span#adj-sign').text('- ');
			}
			else
			{
				$('span#adj-sign').text('+ ');
			}
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
		$('span#adj-budget').text('0.00');
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
				$('#n'+dnum).val(addCommas(total));
			}
		}
		else 
		{			
			total = parseInt(gc) + parseInt(qty);
			$('#n'+dnum).val(total);			
		}
		budgetAdj(adj);		
	});

	function budgetAdj(adj)
	{
		var budget = $('input#current-budget').val();
    	var perdenom = 0;
    	var totaladj = 0;
		for(var $x=1;$x<=6;$x++) {
			var qty = $("#num"+$x).val();
			var denom = $('#denom'+$x).val();
			qty = qty.replace(/,/g , "");
			perdenom = qty*denom;
			totaladj = totaladj + perdenom;
		}
		$('span#adj-budget').text(addCommas(totaladj.toFixed(2)));
		if(adj=='p')
		{
			if(totaladj>budget)
			{
                $("#btn").attr("disabled",true);
            }

            else{
                $("#btn").attr("disabled", false);            
			}
		}
	}

  //   $("[id^=num]").keyup(function(){
  //   	var perdenom = 0;
  //   	var totaladj = 0;
		// for(var $x=1;$x<=6;$x++) {
		// 	var qty = $("#num"+$x).val();
		// 	var denom = $('#denom'+$x).val();
		// 	qty = qty.replace(/,/g , "");
		// 	perdenom = qty*denom;
		// 	totaladj = totaladj + perdenom;
		// }

		// $('span#adj-budget').text(totaladj);
  //   });

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

});