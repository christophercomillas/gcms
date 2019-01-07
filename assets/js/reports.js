$(document).ready(function(){
	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

	var checkin = $('#dp1').datepicker({

	    beforeShowDay: function (date) {
	    	// return date.valueOf() >= now.valueOf();
	        return date.valueOf();
	    },
	    autoclose: true

	}).on('changeDate', function (ev) {
	    if (ev.date.valueOf() >= checkout.datepicker("getDate").valueOf() || !checkout.datepicker("getDate").valueOf()) {

	        var newDate = new Date(ev.date);
	        newDate.setDate(newDate.getDate());
	        checkout.datepicker("update", newDate);
	    }
	    $('#dp2')[0].focus();
	});


	var checkout = $('#dp2').datepicker({
	    beforeShowDay: function (date) {
	        if (!checkin.datepicker("getDate").valueOf()) {
	            return date.valueOf() >= new Date().valueOf();
	        } else {
	            return date.valueOf() >= checkin.datepicker("getDate").valueOf();
	        }
	    },
	    autoclose: true

	}).on('changeDate', function (ev) {});

	$('.form-container').on('click','#mktgenpdf',function(){
		var formData = $(this).serialize(), formUrl = $(this).attr('action');
		var empty = false;
		$('input').each(function(){
			if($(this).val()=='')
			{
				empty = true;
			}
		});

		if(!empty){
			var store = $('select[name=store]').val();
			var start = $('input[name=datestart]').val();
			var end = $('input[name=dateend]').val();
			var den = $('select[name=denom]').val();			
			window.location.href="downloadpdf.php?store="+store+"&den="+den+"&start="+start+"&end="+end;
		} 
		else 
		{
			$('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">Please fill in all fields.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
			timeoutmsg(2000);
		}
		return false;
	});

	$('#strepview').click(function(){
		var formData = $(this).serialize();
		var storeid = $('input[name=storeid]').val();
		var denom = $('select[name=denom]').val(), start = $('input[name=datestart]').val(), end = $('input[name=dateend]').val();
		var empty = false;
		$('input').each(function(){
			if($(this).val()=='')
			{
				empty = true;
			}
		});
		if(!empty){
	       BootstrapDialog.show({
	        	title: '<i class="fa fa-gift"></i> Verified GC',
	            message: $('<div></div>').load('../dialogs/repstverifiedgc.php?storeid='+storeid+'&den='+denom+'&start='+start+'&end='+end),
	     	    cssClass: 'customer-details',
		        closable: true,
		        closeByBackdrop: false,
		        closeByKeyboard: true,
		        onshow: function(dialog) {
		            // dialog.getButton('button-c').disable();
		        },
	            onshown: function(dialogRef){
                    $('#verifiedgc-view').dataTable({
                        "pagingType": "full_numbers",
                        "ordering": false,
                        "processing": true,
                        "iDisplayLength": 5
                    });

                    $("#verifiedgc-view_length").css("display", "none");
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
		else 
		{
			$('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">Please fill in all fields.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
			timeoutmsg(2000);
		}
		return false;
	});


	function timeoutmsg(time)
	{
	    setTimeout(function(){
	    	$('.response').html('');
	    }, time);
	}
});