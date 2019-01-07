<?php 
	
	session_start();
	include '../function.php';

	$access = getAccessPage($link);
	$stores = getStores($link);
?>
<div class="row row-nobot">
	<div class="col-md-12 form-container">
		<form class="form-horizontal" action="../ajax.php?action=addnewusers" id="_add_users">
			<div class="form-group">
				<label class="col-sm-5 control-label">Username:</label>
				<div class="col-sm-7">
					<input type="text" class="form form-control inptxt reqfield" name="uname" autofocus autocomplete="off">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Firstname:</label>
				<div class="col-sm-7">
					<input type="text" class="form form-control inptxt reqfield" name="fname" autocomplete="off">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Lastname:</label>
				<div class="col-sm-7">
					<input type="text" class="form form-control inptxt reqfield" name="lname" autocomplete="off">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Employee ID:</label>
				<div class="col-sm-7">
					<input type="text" class="form form-control inptxt reqfield" name="eid" autocomplete="off">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">User Type:</label>
				<div class="col-sm-7">
					<select class="form form-control inptxt input-md" name="utype" id="utype">
						<option value="">- Select User Type -</option>
						<?php foreach ($access as $a): ?>
							<option value="<?php echo $a->access_no; ?>"><?php echo ucwords($a->title); ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>	
			<div class="form-group form-hide group">
				<label class="col-sm-5 control-label">Retail Group:</label>
				<div class="col-sm-7">
					<select class="form form-control inptxt input-md" name="ugroupretail" id="ugroupretail">						
						<option value="">- Select Group -</option>
						<option value="1">Group 1</option>
						<option value="2">Group 2</option>
					</select>
				</div>
			</div>
			<div class="form-group form-hide store-ass">
				<label class="col-sm-5 control-label">Store Assigned:</label>
				<div class="col-sm-7">
					<select class="form form-control inptxt input-md" name="uassigned">						
						<option value="">- Select Store -</option>
						<?php foreach ($stores as $s): ?>
							<option value="<?php echo $s->store_id; ?>"><?php echo ucwords($s->store_name); ?></option>	
						<?php endforeach ?>
					</select>
				</div>
			</div>
<!-- 			<div class="form-group form-hide user-group">
				<label class="col-sm-5 control-label">User Type:</label>
				<div class="col-sm-7">
					<select class="form form-control inptxt input-md" name="ugroupretail" id="ugroupretail">						
						<option value="">- Select Group -</option>
						<option value="1">1</option>
						<option value="2">2</option>
					</select>
				</div>
			</div>	 -->		
			<div class="form-group form-hide user-role">
				<label class="col-sm-5 control-label">User Role:</label>
				<div class="col-sm-7">
					<select class="form form-control inptxt input-md" name="uroles" id="uroles">						
						<option value="">- Select Role -</option>
						<option value="1">Dept. Manager</option>
						<option value="0">Dept. User</option>
					</select>
				</div>
			</div>
			<div class="response">
			</div>
		</form>
	</div>
</div>
<script>
    $('input[name=uname]').focus();
    $('div.form-container').on('change','#utype',function(){
		var utype = $(this).val();
        var utype = utype.trim();

        	$('select#uroles').val("");
        	$('select#ugroupretail').val("");

            if(utype=="" || utype==1)
            {
                $('div.form-container div.user-role').addClass('form-hide');
                $('div.form-container select[name=uroles]').val(0);
            }
            else
            {
                $('div.form-container div.user-role').removeClass('form-hide');
            }
            
            if(utype==7 || utype==14)
            {
                $('div.form-container div.store-ass').removeClass('form-hide');
            }
            else 
            {
                $('div.form-container div.store-ass').addClass('form-hide');
                $('div.form-container select[name=ugroupretail]').val(0);
            }

            if(utype==8)
            {
            	$('div.form-container div.group').removeClass('form-hide');
            }
            else 
            {
            	$('div.form-container div.group').addClass('form-hide');
            	$('div.form-container select[name=uassigned]').val(0);
            }


        	var sel = document.getElementById('uroles');
        	var opt = document.createElement('option');
            if(utype==6)
            {
            	opt.appendChild( document.createTextNode('Releasing Personnel') );
            	opt.value = '2'; 
            	sel.appendChild(opt);
            }
            else 
            {
            	var hasReleasing = false;
				for (i = 0; i < document.getElementById("uroles").length; ++i){
				    if (document.getElementById("uroles").options[i].value == "2")
				    {
				    	hasReleasing = true; 
				    }
				}

				if(hasReleasing)
				{
					sel.removeChild( sel.options[3] );
				}        		
            }
	});
	$('input[name=uname]').keyup(function(){
		var username = $(this).val();
		$.ajax({
			url:'../ajax.php?action=checkuserifexist',
			type:'POST',
			data:{username:username},
			success:function(response){
				var res = response.trim();
				if(res!='')
				{
					$('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">'+response+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
				} 
				else 
				{
					$('.response').html('');
				}
			}
		});
	});
</script>