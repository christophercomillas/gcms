<?php 
	include '../config.php';

	$id = $_GET['id'];

	$query = $link->query(
		"SELECT 
			* 
		FROM 
			`supplier`
		WHERE 
			`gcs_id`='$id'
	");

	$row = $query->fetch_object();
?>
<div class="row">
	<div class="col-md-12 form-container">
		<form class="form-horizontal" id="supplierinfoform" action="../ajax.php?action=updatesupplier">
			<input type="hidden" value="<?php echo $row->gcs_id; ?>" name="cid">
			<div class="form-group">
				<label class="col-md-4 control-label" for="passwordinput">Company Name
				</label>
					<div class="col-md-8">
						<input id="compname" name="cname" class="form-control inptxt input-md" value="<?php echo $row->gcs_companyname; ?>" type="text">
					</div>
			</div>
			<div class="form-group">
				<label class="col-md-4 control-label">Account Name
				</label>  
				<div class="col-md-8">
					<input id="textinput" name="aname" class="form-control inptxt input-md" type="text"> 
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-4 control-label" for="textinput">Contact Person
				</label>  
				<div class="col-md-8">
					<input id="textinput" name="cperson" class="form-control inptxt input-md" value="<?php echo $row->gcs_contactperson; ?>" type="text"> 
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-4 control-label" for="textinput">Contact Number
				</label>  
				<div class="col-md-8">
					<input id="textinput" name="cnumber" class="form-control inptxt input-md" value="<?php echo $row->gcs_contactnumber; ?>" type="text"> 
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-4 control-label" for="textinput">Address
				</label>  
				<div class="col-md-8">
					<input id="textinput" name="caddress" class="form-control inptxt input-md" value="<?php echo $row->gcs_address; ?>" type="text"> 
				</div>
			</div>

				<div class="response">
				</div>

			</div>
		</form>
	</div>
</div>