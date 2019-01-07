<?php 
	include '../config.php';
	include '../function.php';

	$assignatories = getAssignatories($link,2);

?>
<div class="row">
	<form method="POST" id="checkbyform" class="form-horizontal" action="../ajax-cashier.php"> 
	<div class="col-md-12">		
		<div class="form-group">
			<div class="col-md-12">
				<select class="form-control input-md" name="auth" id="auth">
					<?php foreach ($assignatories as $a): ?>
						<option><?php echo $a->assig_position; ?></option>
					<?php endforeach; ?>					
				</select>
			</div>
		</div>		
	</div>
	</form>
</div>