<?php 
	include '../config.php';
	include '../function.php';

	if(isset($_GET['dept']))
	{
		$type = $_GET['dept'];
	}

	$assignatories = getAssignatories($link,$type);

?>
<div class="row">
	<form method="POST" id="checkbyform" class="form-horizontal" action="../ajax-cashier.php"> 
	<div class="col-md-12">		
		<div class="form-group">
			<div class="col-md-12">
				<select class="form-control inptxt input-md" name="auth" id="auth">
					<?php foreach ($assignatories as $a): ?>
						<option value="<?php echo $a->assig_id; ?>"><?php echo $a->assig_name; ?></option>
					<?php endforeach; ?>					
				</select>
			</div>
		</div>		
	</div>
	</form>
</div>