$(document).ready(function() {
    if ($('#username').val()) 
        $('#password').focus();
          else 
        $('#username').focus();

    $('.form-login').on('submit','form#cashierLogin',function(){
    	var formURL = $(this).attr('action'), formData = $(this).serialize();
    	$.ajax({
    		url:formURL,
    		type:'POST',
    		data:formData,
    		beforeSend:function(){

    		},
    		success:function(data){
                console.log(data);
                var data = JSON.parse(data);   

    			if(data['st'])
                {                    
                    $('body').fadeOut(1000, function(){

                        window.location ="index.php";        
                    });     				
    			} 
                else 
                {
					$('.response').html('<div class="alert alert-danger">'+data['msg']+'</div>');
    			}              
    		}
    	});

    	return false;
    });

    $('.form-login').on('submit','form#managerLogin',function(){
		var formURL = $(this).attr('action'), formData = $(this).serialize();
        $('.response').html('');
    	$.ajax({
    		url:formURL,
    		type:'POST',
    		data:formData,
    		beforeSend:function(){

    		},
    		success:function(data){
                console.log(data);
                var data = JSON.parse(data);
    			if(data['st'])
                {
                    $('body').fadeOut(1000, function(){

                        window.location ="index.php";        
                    });                    		
    			} 
                else 
                {
                    $('.response').html('<div class="alert alert-danger">'+data['msg']+'</div>');
    			}
    		}
    	});

    	return false;
    });


});