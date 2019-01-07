<?php 
session_start();
include_once '../function-cashier.php';
include_once '../function.php';
$table = 'pos_denoms';
$select = 'pos_did,pos_ddenom,pos_ddesc';
$where = "pos_dstat='active' ORDER BY pos_did ASC";
$join = '';
$limit = '';

$cash = getAllData($link,$table,$select,$where,$join,$limit);

$i = 1;
$flag = 0;

?>
<form class="form-horizontal" action="../ajax-cashier.php?request=shortageoverage" id="fshortageoverage">
	<div class="row">
		<div class="col-sm-12">
			
				<?php 
					foreach ($cash as $c): 
					if($i==1):
				?>
			        	<div class="form-group">
			   		<?php endif; ?>
						<label class="control-label col-sm-2 lblshtt"><?php echo $c->pos_ddenom; ?></label>
						<div class="col-sm-2">
							<input type="hidden" class="denosdrawer" value="<?php echo $c->pos_ddenom; ?>" data-id="d<?php echo $c->pos_did; ?>">
							<input type="text" class="form-control inpmed denset <?php if($flag===0){ $flag=1; echo "focustxt"; }?>" name="qty_<?php echo $c->pos_did; ?>" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false">
						</div>
					<?php if($i===3): ?>
			        	</div>
			    	<?php endif; ?>
			    	<?php 
			    		if($i===3)
			    		{
			    			$i=1;
			    		}
			    		else 
			    		{
			    			$i++;
			    		}
			    	?>
			    <?php endforeach; ?>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="form-group">
				<label class="control-label col-sm-5 lblshtt">Total</label>
				<div class="col-sm-5">					
					<input type="text" class="form-control inpmed" value="0" id="totsht" readonly="readonly">
				</div>
			</div>
		</div>
	</div>
	<div class="responseshortage" style="margin-top:10px;">
	</div>
</div>



<script>              
	$('input.inpmed').inputmask();
	$('.focustxt').select();     

	$('.row').on('keyup','.denset',function(){
		// var den = $(this).val();
		// var x = $(this).parent().find('.denosdrawer').val();
		var total = 0;
		$('.denset').each(function(){
			var sub = 0;
			var den = $(this).val();
			den = den.replace(/,/g , "");
			den  = isNaN(den) ? 0 : den;
			var x = $(this).parent().find('.denosdrawer').val();
			x = isNaN(x) ? 0 : x;
			sub = parseFloat(den * x);
			
			total = parseFloat(total) + parseFloat(sub);
		});

		$('#totsht').val(parseFloat(total).toFixed(2));		
	});
	// dscnt = isNaN(dscnt) ? 0 : dscnt;
	// var y = parseFloat(0.05);
	// var d = parseFloat(0.10);
	// alert(parseFloat(y+d).toFixed(2));

</script>