<?php 
  session_start();
  include '../function.php';
  require 'header.php';
  require '../menu.php'; 

  $promo = getPromo($link);
?>

<div class="main fluid">    
	<div class="row">
		<div class="col-sm-12">
			<div class="box box-bot">
				<div class="box-header">
					<span class="box-title-with-btn"><i class="fa fa-inbox">
					  </i> Promo List
					</span>			
				</div>
				<div class="box-content">
				<table class="table dataTable no-footer" id="gcrec">
					<thead>
						<tr>
							<th>Promo No.</th>
							<th>Promo Name</th>
							<th>Date Created</th>
							<th>Group</th>
							<th>Created By</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($promo as $p): ?>
							<tr>
								<td><?php echo threedigits($p->promo_id); ?></td>
								<td><?php echo ucwords($p->promo_name); ?></td>
								<td><?php echo _dateFormat($p->promo_date); ?></td>
								<td><?php echo $p->promo_group; ?></td>
								<td><?php echo ucwords($p->firstname.' '.$p->lastname);?></td>
								<td><i class="fa fa-fa fa-eye faeye" title="View" onclick="viewpromo(<?php echo $p->promo_id; ?>);"></i></td>
							</tr>
						<?php endforeach ?>						
					</tbody>
				</table>	
				</div>
			</div>
	  	</div>
	</div>
</div>


<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/marketing.js"></script>
<?php include 'footer.php' ?>