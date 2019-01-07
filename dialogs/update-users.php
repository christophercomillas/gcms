<?php 
	
	session_start();
	include '../function.php';

	$id = $_GET['id'];

	$user = getUser($link,$id);
	//var_dump($user);
	$access = getAccessPage($link);
	$stores = getStores($link);
?>
<div class="row row-nobot">
	<div class="col-md-12 form-container">
		<form class="form-horizontal" action="../ajax.php?action=updateusers" id="_update_users">
			<?php foreach ($user as $key): ?>
			<input type="hidden" name="uid" value="<?php echo $key->user_id; ?>">
			<div class="form-group">
				<label class="col-sm-5 control-label">Username:</label>
				<div class="col-sm-7">
					<input type="text" class="form inptxt form-control reqfield" id="uname" name="uname" value="<?php echo $key->username; ?>" onkeyup="checkUsernameUpdate(this.value,<?php echo $key->user_id; ?>);" autocomplete="off">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Firstname:</label>
				<div class="col-sm-7">
					<input type="text" class="form inptxt form-control reqfield" name="fname" value="<?php echo $key->firstname; ?>" autocomplete="off">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Lastname:</label>
				<div class="col-sm-7">
					<input type="text" class="form inptxt form-control reqfield" name="lname" value="<?php echo $key->lastname; ?>" autocomplete="off">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Employee ID:</label>
				<div class="col-sm-7">
					<input type="text" class="form inptxt form-control reqfield" name="eid" value="<?php echo $key->emp_id; ?>" autocomplete="off">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">User Type:</label>
				<div class="col-sm-7">
					<select class="form form-control inptxt input-md reqfield" name="utype" id="utype">
						<option value="<?php echo $key->access_no; ?>"><?php echo ucfirst($key->title); ?></option>
						<?php foreach ($access as $a): ?>
							<?php if($key->access_no!==$a->access_no): ?>							
								<option value="<?php echo $a->access_no; ?>"><?php echo ucfirst($a->title); ?></option>
							<?php endif; ?>
						<?php endforeach ?>
					</select>
				</div>
			</div>

			<div class="form-group <?php echo $key->access_no==8 ? '' :'form-hide';?> group">
				<label class="col-sm-5 control-label">Retail Group:</label>
				<div class="col-sm-7">
					<select class="form form-control inptxt input-md" name="ugroupretail" id="ugroupretail">	
						<?php if($key->usergroup==''): ?>	
							<option value="">- Select Group -</option>
							<option value="1">Group 1</option>
							<option value="2">Group 2</option>							
						<?php endif; ?>				

						<?php if($key->usergroup=='1'): ?>							
							<option value="1">Group 1</option>							
							<option value="2">Group 2</option>
							<option value="">- Select Group -</option>	
						<?php endif; ?>

						<?php if($key->usergroup=='2'): ?>
							<option value="2">Group 2</option>							
							<option value="1">Group 1</option>
							<option value="">- Select Group -</option>	
						<?php endif; ?>	
					</select>
				</div>
			</div>

			<div class="form-group <?php echo $key->store_name!='' ? '' :'form-hide';?> store-ass">
				<label class="col-sm-5 control-label">Store Assigned:</label>
				<div class="col-sm-7">
					<select class="form form-control inptxt input-md" name="uassigned">
						<?php if($key->store_name==''): ?>
							<option value="">- Select Store -</option>
						<?php else: ?>
							<option value="<?php echo $key->store_id?>"><?php echo ucwords($key->store_name); ?></option>
							<option value="">- Select Store -</option>
						<?php endif; ?>
						<?php foreach ($stores as $s): ?>
							<?php if($key->store_id!=$s->store_id): ?>
								<option value="<?php echo $s->store_id?>"><?php echo ucwords($s->store_name); ?></option>
							<?php endif; ?>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Status:</label>
				<div class="col-sm-7">
					<select class="form form-control inptxt input-md reqfield" name="ustat">
						<option value="<?php echo $key->user_status; ?>"><?php echo ucfirst($key->user_status); ?></option>
						<?php if($key->user_status=='active'):?>
							<option value="inactive">Inactive</option>
						<?php else: ?>
							<option value="active">Active</option>
						<?php endif; ?>						
					</select>
				</div>
			</div>
			<div class="form-group<?php echo $key->usertype=='1' ? ' form-hide' :'';?> urole">
				<label class="col-sm-5 control-label">User Role:</label>
				<div class="col-sm-7">
					<select class="form form-control inptxt input-md reqfield" name="uroles" id="uroles">

						<option value="<?php 
							echo $key->user_role;
							if($key->user_role=='1')
							{	
								$utype = 'Dept. Admin';
							} 
							elseif($key->user_role=='0')
							{
								$utype = 'Dept. User';
							}
							elseif ($key->user_role=='2') 
							{
								$utype = 'Releasing Personnel';
							}
						?>">
						<?php echo ucfirst($utype); ?></option>
						<?php if($key->user_role==0):?>
							<option value="1">Dept. Admin</option>
							<option value="2">Releasing Personnel</option>
						<?php elseif($key->user_role==1): ?>
							<option value="0">Dept. User</option>
							<option value="2">Releasing Personnel</option>
						<?php elseif($key->user_role==2): ?>
							<option value="1">Dept. Admin</option>
							<option value="0">Dept. User</option>
						<?php endif; ?>						
					</select>
				</div>
			</div>
			<div class="response">
			</div>
			<?php endforeach ?>
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
            
            if(utype==7)
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