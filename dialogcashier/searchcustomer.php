<?php 
session_start();
include_once '../function-cashier.php';
$group = $_GET['group'];
if(is_null($group))
exit();
$customers = getInternalCustomers($link,$group);
 $type = array('','Supplier','Customer','V.I.P.');
?>
<div class="row">
	<div class="col-xs-12">
		<table class="table searchtable" id="searchTable">
			<thead>
				<tr>
					<th>Code</th>
					<th>Name</th>
					<th>Address</th>
					<th>Marital Status</th>
					<th>Type</th>
				</tr>
			</thead>
			<tbody class="searchtablebody">
				<?php foreach ($customers as $c): ?>
				<tr cuscode="<?php echo $c->ci_code; ?>">
					<td><?php echo $c->ci_code; ?></td>
					<td><?php echo ucwords($c->ci_name); ?></td>
					<td><?php echo $c->ci_address; ?></td>
					<td><?php echo ucwords($c->ci_cstatus); ?></td>
					<td><?php echo ucwords($type[$c->ci_type]); ?></td>
				</tr>						
				<?php endforeach; ?>
			
			</tbody>
		</table>
	</div>
</div>
<script>
	$('table tbody.searchtablebody tr:first').css('background-color','yellow');
	$('#searchTable').dataTable( {
	  "pagingType": "full_numbers",
	  "ordering": false,
	  "processing": true
	});
	
	$(".dataTables_filter input").addClass('searchcus');

	$('#searchTable_filter input').focus();

	$('table#searchTable tr').click(function(){
		var cuscode = $(this).attr('cuscode');
		$('#customercode').val(cuscode);
		getCustomerInfo(cuscode,group);
	});

	$("#searchTable_filter input").on('keypress', function (event) {
		$('table tbody.searchtablebody tr:first').css('background-color','yellow');
		if(event.which === 13)
		{
			var cc = $('.searchcus').val();
			$('#customercode').val(cc);
			$('.closecusto').click();
		}  
	});
</script>