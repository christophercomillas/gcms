<?php

session_start();

//unset($_SESSION['empAssign']);
//var_dump($_SESSION['empAssign']);
//print_r($_SESSION['empAssign'][36]);


include '../function.php';
if(isset($_GET['action']))
{
	$action = $_GET['action'];
}
else 
{
	exit();
}
if($action=='assignemp'):
	if(isset($_GET['den']))
	{
		$den = $_GET['den'];
		$den = str_replace(',','',$den);
	}
	else 
	{
		exit();
	}

	if(isset($_GET['datanum']))
	{
		$datanum = $_GET['datanum'];
	}
	else 
	{
		exit();
	}

?>
<div class="row">
	<div class="col-sm-4 addEmpClass">
		<h5>GC Holder Name</h5>
		<form method="post" action="../ajax.php?action=addEmployee" id="addEmployeeForm">
			<input type="hidden" name="den" id="den" value="<?php echo $den; ?>">
			<input type="hidden" id="datanum" name="datanum" value="<?php echo $datanum; ?>"> 
			<div class="form-group">
				<label class="nobot"><span class="requiredf">*</span>Last Name</label>   
				<input type="text" class="form form-control inptxt input-sm bot-6 reqfieldx" name="lastname" autocomplete="off" autofocus>  
			</div>
			<div class="form-group">
				<label class="nobot"><span class="requiredf">*</span>First Name</label>   
				<input type="text" class="form form-control inptxt input-sm bot-6 reqfieldx" name="firstname" autocomplete="off" autofocus>  
			</div>
			<div class="form-group">
				<label class="nobot">Middle Name</label>   
				<input type="text" class="form form-control inptxt input-sm bot-6" name="middlename" autocomplete="off" autofocus>  
			</div>
			<div class="form-group">
				<label class="nobot">Name Ext.</label>   
				<input type="text" class="form form-control inptxt input-sm bot-6" name="nameext" autocomplete="off" autofocus>  
			</div>
			<div class="response2">

			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-primary">Assign</button>
				<button type="buttom" class="btn btn-danger">Clear</button>
			</div>
		</form>
	</div>
	<div class="col-sm-8">
		<h5>Denomination: <?php echo number_format($den,2); ?></h5>
		<table class="table" id="empDataTable">
			<thead>
				<th>Last Name</th>
				<th>First Name</th>
				<th>Middle Name</th>
				<th>Name Ext.</th>
				<th></th>
			</thead>
			<tbody>
				<?php if(isset($_SESSION['empAssign'])): ?>
					<?php foreach ($_SESSION['empAssign'] as $key => $value): ?>
						<?php if($value['denom']==$den):?>
						<tr>							
							<td><?php echo $value['lastname']; ?></td>
							<td><?php echo $value['firstname']; ?></td>
							<td><?php echo $value['middlename']; ?></td>
							<td><?php echo $value['extname']; ?></td>
							<td><input type="hidden" value="<?php echo $key; ?>" class="empkey"><i class="fa fa-times remove-employee" aria-hidden="true"></i></td>
						</tr>			
						<?php endif; ?>		
					<?php endforeach ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>
<?php elseif($action=='createdenom'): ?>
<div class="row">
	<div class="col-md-12 form-container">
		<form class="form-horizontal">
			<div class="form-group">
				<label class="col-sm-5 control-label">Denomination:</label>
				<div class="col-sm-7">
					<input type="text" id="denocr" autocomplete="off" data-inputmask="'alias': 'numeric','digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" class="form form-control inptxt reqfield" name="denom" autofocus maxlength="13">
				</div>
			</div>
			<div class="form-group hide">
				<label class="col-sm-5 control-label">Denomination:</label>
				<div class="col-sm-7">
					<input type="text" id="denocrx" data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '','allowMinus':false" class="form form-control inptxt reqfield" name="denomx" autofocus maxlength="13">
				</div>
			</div>
			<div class="responsecr"></div>		
		</form>
	</div>
</div>
<?php elseif($action=='addcompany'): ?>
<div class="row">
	<div class="col-md-12 form-horizontal">
		<form method="POST" action="../ajax.php?action=addexternalcustomer" id="addexternalcustomer">
			<div class="form-group">
				<label class="col-sm-5 control-label">Company / Person:</label>
				<div class="col-sm-7">
					<textarea class="form-control" name="company" id="company"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Account Name:</label>
				<div class="col-sm-7">
					<input type="text" class="form-control" name="accname" id="accname" autocomplete="off">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Address:</label>
				<div class="col-sm-7">
					<textarea class="form-control" name="address" id="address"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Contact Person:</label>
				<div class="col-sm-7">
					<input type="text" class="form-control" name="contactp" id="contactp" autocomplete="off">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Contact Number:</label>
				<div class="col-sm-7">
					<input type="text" class="form-control" name="contactn" id="contactn" autocomplete="off">
				</div>
			</div>
			<div class="response">
			</div>
		</form>
	</div>
</div>
<?php elseif ($action=='lookupcustomer'): 
  $select = "special_external_customer.spcus_id, 
    special_external_customer.spcus_companyname,
    special_external_customer.spcus_acctname,
    special_external_customer.spcus_address, 
    special_external_customer.spcus_cperson, 
    special_external_customer.spcus_cnumber";

  $where = '1';

  $join = 'INNER JOIN
      users
    ON
      users.user_id = special_external_customer.spcus_by';
  $limit ='ORDER BY spcus_id DESC';
  $cus = getAllData($link,'special_external_customer',$select,$where,$join,$limit);
?>

<div class="row">
	<div class="col-sm-12 lookupcust">
		<table class="table" id="lookupcus">
			<thead>
				<tr>
					<th>Company / Person Name</th>
					<th>Account Name</th>
					<th>Address</th>
					<th>Contact Person</th>
					<th>Contact Number</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($cus as $key): ?>
					<tr data-id="<?php echo $key->spcus_id; ?>">
						<td><?php echo ucwords($key->spcus_companyname); ?></td>
						<td><?php echo $key->spcus_acctname; ?></td>
						<td><?php echo ucwords($key->spcus_address); ?></td>
						<td><?php echo ucwords($key->spcus_cperson); ?></td>
						<td><?php echo ucwords($key->spcus_cnumber); ?></td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
	$.extend( $.fn.dataTableExt.oStdClasses, {	  
	    "sFilterInput": "searchcus"
	});
    $('#lookupcus').dataTable( {
        "pagingType": "full_numbers",
        "ordering": false,
        "processing": true,
        "bProcessing":true
    });

	$('.searchcus').focus();

	$('.lookupcust').on('click','table#lookupcus tbody tr',function(){
		var id = $(this).attr('data-id');
		var compname = $(this).find('td:first').text();
		var accname = $(this).find('td:nth-child(2)').text();		
		$('#companyid').val(id);
		$('#compname').val(compname);
		$('#accname').val(accname);
		BootstrapDialog.closeAll();
		$('#paymenttype').focus();
	});

</script>

<?php elseif ($action=='viewCustomerGC'): 
	if(!isset($_GET['id']))
		exit();

	$trid = $_GET['id'];

	if(empty($trid))
		exit();

	//check tr type

	$reqtype = getField($link,'spexgc_type','special_external_gcrequest','spexgc_id',$trid);

	if($reqtype==1):
		$table = 'special_external_gcrequest_items';
		$select = 'specit_denoms,
			specit_qty';
		$where = "specit_trid='$trid'";
		$join = '';
		$limit = '';
		$data = getAllData($link,$table,$select,$where,$join,$limit);


?>
	<table class="table">
		<thead>
			<tr>
				<th>Denomination</th>
				<th>Quantity</th>
				<th>Subtotal</th>
			</tr>			
		</thead>
		<tbody>
			<?php foreach ($data as $key): ?>
				<tr>
					<td><?php echo number_format($key->specit_denoms,2); ?></td>
					<td><?php echo $key->specit_qty; ?></td>
					<td><?php echo number_format($key->specit_denoms * $key->specit_qty,2); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

<?php else:?>
	<table class="table" id="list">
		<thead>
			<tr>
				<td>Lastname</td>
				<td>Firstname</td>
				<td>Middlename</td>
				<td>Name ext.</td>
				<td>Denomination</td>
			</tr>			
		</thead>
		<tbody>
			<?php
				$table = 'special_external_gcrequest_emp_assign';
				$select = "spexgcemp_denom,
					spexgcemp_fname,
					spexgcemp_lname,
					spexgcemp_mname,
					spexgcemp_extname";
				$where = "spexgcemp_trid = '".$trid."'
					ORDER BY
						spexgcemp_denom
					ASC";
				$join = '';
				$limit = '';
				$gc = getAllData($link,$table,$select,$where,$join,$limit);
				foreach ($gc as $g):
			?>
				<tr>
					<td><?php echo ucwords($g->spexgcemp_lname); ?></td>
					<td><?php echo ucwords($g->spexgcemp_fname); ?></td>
					<td><?php echo ucwords($g->spexgcemp_mname); ?></td>
					<td><?php echo ucwords($g->spexgcemp_extname); ?></td>
					<td><?php echo number_format($g->spexgcemp_denom,2); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>
<?php elseif ($action=='viewCheckInfo'): 
	if(!isset($_GET['id']))
		exit();
	$checkid = $_GET['id'];

	if(empty($checkid))
		exit();

	$table = 'institut_payment';
	$select = 'institut_bankname,
	    institut_bankaccountnum,
	   	institut_checknumber';
	$join ='';
	$limit = '';
	$where = "insp_trid='$checkid'
		AND
			insp_paymentcustomer='special external'";


	$data = getSelectedData($link,$table,$select,$where,$join,$limit);
?>
<div class="row form-horizontal">
    <div class="form-group">
        <label class="col-sm-5 control-label">Bank Name</label>
        <div class="col-sm-6">
            <input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo ucwords($data->institut_bankname); ?>">
        </div>
    </div><!-- end form group -->
	<div class="form-group">
		<label class="col-sm-5 control-label">Bank Account Number</label>
		<div class="col-sm-6">
			<input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo $data->institut_bankaccountnum; ?>">
		</div>
	</div><!-- end form group -->
	<div class="form-group">
		<label class="col-sm-5 control-label">Check Number</label>
		<div class="col-sm-6">
			<input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo $data->institut_checknumber; ?>">
		</div>
	</div><!-- end form group -->
</div>
<?php endif; ?>

<script>
	$('#denocr').inputmask();

    $('#empDataTable,#list').dataTable( {
        "pagingType": "full_numbers",
        "ordering": false,
        "processing": true
    });

    function assignX()
    {
    	$('.response2').html('');
    	var hasEmpty = false;
    	$('.reqfieldx').each(function(){
    		if($(this).val().trim()=='')
    		{
    			hasEmpty = true;
    			return false;
    		}
    	});

    	if(hasEmpty)
    	{
    		$('.response2').html('<div class="alert alert-danger">Please fill-up firstname and lastname.</div>');
    		return false;
    	}

    	var formURL = '../ajax.php?action=addEmployee', formData = $('#addEmployeeForm').serialize();
    	 $('#addEmployeeForm')[0].reset();
    	// $.ajax({
    	// 	url:formURL,
    	// 	data:formData,
    	// 	type:'POST',
    	// 	success:function(data){
    	// 		//$('._barcodesrefund').load('../ajax-cashier.php?request=loadrefund');
    	// 		console.log(data);
    	// 	}
    	// });
    }

     $('.addEmpClass').on('submit','#addEmployeeForm',function(event){
 		event.preventDefault();
	    var t = $('#empDataTable').DataTable();
	    var counter = 1;
    	$('.response2').html('');
    	var hasEmpty = false;
    	$('.reqfieldx').each(function(){
    		if($(this).val().trim()=='')
    		{
    			hasEmpty = true;
    			return false;
    		}
    	});

    	if(hasEmpty)
    	{
    		$('.response2').html('<div class="alert alert-danger">Please fill-up firstname and lastname.</div>');
    		return false;
    	}
    	var formURL = '../ajax.php?action=addEmployee', formData = $('#addEmployeeForm').serialize();    	
    	$.ajax({
    		url:formURL,
    		data:formData,
    		type:'POST',
    		success:function(data){
    			//$('._barcodesrefund').load('../ajax-cashier.php?request=loadrefund');
    			console.log(data);
    			var data = JSON.parse(data);
    			$('.ninternalcusq'+data['datanum']).val(data['qty']);
    			var counter = 1;
		        t.row.add( [		        	
		            data['lastname'],
		            data['firstname'],
		            data['middlename'],
		            data['nameext'],
		            '<input type="hidden" value="'+data['key']+'" class="empkey"><i class="fa fa-times remove-employee" aria-hidden="true"></i>',
		        ] ).draw( false );
		 		
		        counter++;
		        scanInternalInput();
     		}
    	});
    	$('#addEmployeeForm')[0].reset();
    	$('input[name=lastname]').focus();
    });

	$('table#empDataTable').on('click','.remove-employee',function(){
		var key = $(this).parents('tr').find('input.empkey').val();
		
		var den = $('#den').val();
		var r = confirm("Delete Employee?");
		if (r == true) {

			$.ajax({
				url:'../ajax.php?action=deleteAssignByKey',
				data:{key:key,den:den},
				type:'POST',
				success:function(data)
				{
					console.log(data);
					var data = JSON.parse(data);
					var datanum = $('#datanum').val();
					$('.ninternalcusq'+datanum).val(data['qty']);
					scanInternalInput();
				}
			});

			var table = $('#empDataTable').DataTable();
			table
			.row( $(this).parents('tr') )
			.remove()
			.draw();
		}
		
		$('input[name=lastname]').focus();
	});

</script>