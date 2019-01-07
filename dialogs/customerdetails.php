<?php 
	include '../function.php';

	$customer = getCustomerDetails($link);
?>
<div class="row">
	<div class="col-md-12" id="cusdetails">
		<table class="table rows-adjust" id="customer-details">
			<thead>
				<tr>
					<th>First Name</th>		
					<th>Last Name</th>
					<th>Middle Name</th>
					<th>Name Ext.</th>
					<th>Mobile No.</th>
				</tr>				
			</thead>
			<tbody>
				<?php foreach ($customer as $key): ?>
				<tr cusid="<?php echo ucwords($key->cus_id); ?>" 
					cusfname="<?php echo ucwords($key->cus_fname); ?>"
					cuslname="<?php echo ucwords($key->cus_lname); ?>"
					cusmid="<?php echo $key->cus_mname; ?>"
					cusext="<?php echo $key->cus_namext; ?>">
					<td><?php echo ucwords($key->cus_fname); ?></td>
					<td><?php echo ucwords($key->cus_lname); ?></td>
					<td><?php echo $key->cus_mname; ?></td>
					<td><?php echo ucwords($key->cus_namext); ?></td>
					<td><?php echo $key->cus_mobile; ?></td>
				</tr>
				<?php endforeach ?>
			</tbody>			
		</table>
	</div>
</div>

<script>
	$.extend( $.fn.dataTableExt.oStdClasses, {	  
	    "sFilterInput": "searchcus"
	});
    $('#customer-details').dataTable( {
        "pagingType": "full_numbers",
        "ordering": false,
        "processing": true,
        "bProcessing":true
    });

    $("#customer-details_length").css("display", "none");

    $('.searchcus').focus();	

	$('#cusdetails').on('click','table#customer-details tbody tr',function(){
		var cusid = $(this).attr('cusid');
		var cusfname = $(this).attr('cusfname');
		var cuslname = $(this).attr('cuslname');
		var cusmid = $(this).attr('cusmid');
		var cusext = $(this).attr('cusext');
		$('#cid').val(cusid);
		$('#fname').val(cusfname);
		$('#lname').val(cuslname);
		$('#mname').val(cusmid);
		$('#next').val(cusext);

		BootstrapDialog.closeAll();
		$('#gcbarcodever').focus();
	});
</script>