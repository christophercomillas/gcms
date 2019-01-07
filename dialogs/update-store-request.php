<?php 
	
	session_start();
	include '../function.php';

	$id = $_GET['id'];
	$storeid = $_GET['storeid'];

	$reqDetails = getRequestDetailsPending($link,$id);

	$denoms = getAllDenomination($link);
    // $query_r = $link->query("SELECT * FROM `store_gcrequest` WHERE `sgc_status` = '0' AND `sgc_store`='$store_id'");

    // if(!$query_r){
    //     echo $link->error;
    // } 

    // $row_r = $query_r->fetch_assoc();

?>
<div class="row">
	<div class="col-sm-12">
		<div class="row">
			<?php 
				if($reqDetails->sgc_type=='special internal'): 

				// get denom request

				$select = '
					store_request_items.sri_items_denomination,
					store_request_items.sri_items_quantity,
					store_request_items.sri_items_requestid,
					denomination.denomination,
					for_denom_set_up.fds_denom';
				$where = 'store_request_items.sri_items_requestid='.$reqDetails->sgc_id;
				$join = 'LEFT JOIN
						denomination
					ON
						denomination.denom_id = store_request_items.sri_items_denomination
					LEFT JOIN
						for_denom_set_up
					ON
						for_denom_set_up.fds_denom_reqid = store_request_items.sri_id';
				$limit = 'ORDER BY store_request_items.sri_id ASC';

				$reqgc = getAllData($link,'store_request_items',$select,$where,$join,$limit);
			?>
				<form class="form-horizontal" action="../ajax.php?action=updategcrequest" method="POST" id="storeRequestUpdate">
					<input type="hidden" value="specialinternal" name="requesttype" id="requesttype" />
					<input type="hidden" name="reqID" value="<?php echo $id; ?>">
					<div class="col-sm-3">
		                <div class="form-group">
		                  <label class="col-sm-6 control-label"><span class="requiredf">*</span>Denomination</label>
		                  <label class="col-sm-4 control-label"><span class="requiredf">*</span>Quantity</label>
		                </div><!-- end of form-group -->
						<div class="optionBox">

							<?php
								$total = 0;
								$stotal = 0;
								$index = 1; 
								foreach ($reqgc as $r): 
								$denomination = 0;
								if($r->sri_items_denomination!=0)
									$denomination = $r->denomination;								
								else 
									$denomination = $r->fds_denom;
								$stotal = $denomination * $r->sri_items_quantity;
								$total += $stotal;

							?>								
								<div class="form-group">
									<div class="col-sm-5">
										<input class="form form-control inptxt input-sm reqfield ninternalcusd1" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0'" id="ninternalcusd" autocomplete="off" placeholder="0" name="ninternalcusd[]" value="<?php echo $denomination; ?>" />
									</div>
									<div class="col-sm-5">
										<input class="form form-control inptxt input-sm reqfield ninternalcusq1" data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0'" id="ninternalcusq" autocomplete="off" placeholder="0" name="ninternalcusq[]" value="<?php echo $r->sri_items_quantity; ?>" />
									</div>
									<?php if($index>1): ?>
		        					<div class="col-sm-2" style="padding-left:0px;">
		          						<i class="fa fa-minus-square minus-denom removed" aria-hidden="true"></i>
		        					</div>
									<?php endif; ?>
								</div>

							<?php
								$index++; 
								endforeach; 
							?>
							<button class="btn btn-default" type="button" id="addenombut">Add Denomination</button>
						</div>
						<div class="labelinternaltot" style="margin-top:10px;">
                           	<input type="hidden" id="totolrequestinternal" value="<?php echo $total; ?>">                        
                            <label>Total: <span id="internaltot"><?php echo number_format($total,2); ?></span></label>
                        </div>
		            </div>
		            <div class="col-xs-6">
		                <div class="form-group">
		                  <label class="col-sm-4 control-label">GC Request No.:</label>
		                  <div class="col-sm-2">
		                    <input type="text" class="form form-control inptxt input-sm" readonly="readonly" value="<?php echo $reqDetails->sgc_num;  ?>" name="penum">      
		                  </div>
		                </div><!-- end of form-group -->
		                <div class="form-group">
		                  <label class="col-sm-4 control-label">Retail Store:</label>
		                  <div class="col-sm-6">
		                      <input type="text" class="form form-control inptxt input-sm" name="storename" readonly="readonly" value="<?php echo $reqDetails->store_name; ?>">
		                      <input type="hidden" name="storeid" value="<?php echo $storeid; ?>">
		                  </div>
		                </div><!-- end of form-group -->
		                <div class="form-group">
		                  <label class="col-sm-4 control-label">Date Requested:</label>
		                  <div class="col-sm-5">
		                      <input type="text" class="form form-control inptxt input-sm" readonly="readonly" value="<?php echo _dateFormat($reqDetails->sgc_date_request); ?>">
		                  </div>
		                </div><!-- end of form-group -->
		                <div class="form-group">
		                  <label class="col-sm-4 control-label">Date Needed:</label>
		                  <div class="col-sm-5">
		                      <input type="text" class="form form-control inptxt inptxt input-sm" id="dp1" data-date-format="MM dd, yyyy" name="date_needed" value="<?php echo _dateFormat($reqDetails->sgc_date_needed); ?>" readonly="readonly">
		                  </div>
		                </div><!-- end of form-group -->
		                <div class="form-group">
		                  <label class="col-sm-4 control-label">Upload Document:</label>
		                  <div class="col-sm-6">
		                      <input type="hidden" value="<?php echo $reqDetails->sgc_file_docno; ?>" name="docu">
		                      <input id="pics" type="file" name="pic[]" accept="image/*" class="form form-control input-sm" />
		                  </div>
		                </div><!-- end of form-group -->
		                <input type="hidden" name="imgname" value="<?php echo $reqDetails->sgc_file_docno; ?>">
		                <?php if(!empty($reqDetails->sgc_file_docno)): ?>
		                <div class="form-group">
		                  <label class="col-sm-4 control-label">Uploaded Document:</label>
		                  <div class="col-sm-6">
							<a class="btn btn-block btn-default" href='../assets/images/gcRequestStore/download.php?file=<?php echo $reqDetails->sgc_file_docno; ?>'><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download</a>
		                  </div>
		                </div><!-- end of form-group -->	
		                <?php endif; ?> 
		                <div class="form-group">
		                  <label class="col-sm-4 control-label">Company Req.:</label>
		                  <div class="col-sm-6">
		                  	<textarea class="form-control inptxt inptxt input-sm" name="reqby" id="reqby" autocomplete="off"><?php echo $reqDetails->spcus_customername; ?></textarea>
		                  </div>
		                </div><!-- end of form-group -->               
		                <div class="form-group">
		                  <label class="col-sm-4 control-label">Remarks:</label>
		                  <div class="col-sm-6">
		                  	<textarea class="form-control inptxt inptxt input-sm" name="remarks" id="remarks" autocomplete="off"><?php echo $reqDetails->sgc_remarks; ?></textarea>
		                  </div>
		                </div><!-- end of form-group -->
		                <div class="form-group">
		                  <label class="col-sm-4 control-label">Prepared by:</label>
		                  <div class="col-sm-4">
		                    <input type="text" readonly="readonly" class="form-control inptxt inptxt input-sm" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>">                     
		                  </div>
		                </div><!-- end of form-group -->
		                <div class="response">
		                </div>	           	           	         
		            </div>
					<div class="col-xs-3">
						<div class="forbox">
							<div class="box">
		                  		<div class="box-header"><h4><i class="fa fa-inbox"></i> Allocated GC</h4></div>
		                    	<div class="box-content form-container">
			                      <ul class="list-group">   
									<?php foreach ($denoms as $d): ?>
										<li class="list-group-item"><span class="badge" id="x<?php echo $d->denom_id; ?>"><?php echo getValidationNumRowsByStore($link,$storeid,$d->denom_id); ?></span> &#8369 <?php echo number_format($d->denomination,2); ?></li>          
									<?php endforeach ?>
			                      </ul>
		                    	</div>
		                    </div>
	                    </div>
					</div>
				</form>
			<?php else: ?>
				<form class="form-horizontal" action="../ajax.php?action=updategcrequest" method="POST" id="storeRequestUpdate">
					<input type="hidden" value="regularspecial" name="requesttype" id="requesttype" />
					<input type="hidden" name="reqID" value="<?php echo $id; ?>">
					<div class="col-xs-3">
		                <div class="form-group">
		                  <label class="col-sm-6 control-label"><span class="requiredf">*</span>Denomination</label>
		                  <label class="col-sm-4 control-label"><span class="requiredf">*</span>Quantity</label>
		                </div><!-- end of form-group -->
		                <?php 
		                	$select = 'denom_id,
								denomination';
		                	$where = "denom_status='active' AND
								denom_type='RSGC'";
		                	$order = 'ORDER BY 
									denomination
								ASC';
		                	$gcs = getAllData($link,'denomination',$select,$where,'',$order);
		                	$total = 0;
		                	foreach ($gcs as $gc):
		                ?>
			                <div class="form-group">
			                  <label class="col-xs-6 control-label">&#8369 <?php echo number_format($gc->denomination,2); ?></label>
			                  <div class="col-xs-5">
			                    <?php 
			                            $recitem = getGCrequestItems(
			                            $link,
			                            'sri_items_quantity',
			                            'store_request_items',                                            
			                            'sri_items_denomination',
			                            $gc->denom_id,
			                            'sri_items_requestid',
			                            $id
			                            );

			                            $stotal = $gc->denomination * $recitem;
			                            $total += $stotal;
			                    ?>
			                    <input type="hidden" class="denval" id="m<?php echo $gc->denom_id; ?>" value="<?php echo $gc->denomination; ?>"/>
			                    <input data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0'" class="form form-control inptxt input-sm reqden" id="num<?php echo $gc->denom_id; ?>" name="denom<?php echo $gc->denom_id; ?>" autocomplete="off" autofocus value="<?php echo $recitem; ?>"/>
			                  </div>
			                </div><!-- end of form-group -->					
		            	<?php endforeach; ?>

						<div class="labelinternaltot" style="margin-top:10px;">
                           	<input type="hidden" id="_totalReq" value="<?php echo $total; ?>">                        
                            <label>Total: <span id="internaltot" class="totalReq"><?php echo number_format($total,2); ?></span></label>
                        </div>
		            </div>
		            <div class="col-xs-6">
		                <div class="form-group">
		                  <label class="col-sm-4 control-label">GC Request No.:</label>
		                  <div class="col-sm-2">
		                    <input type="text" class="form form-control inptxt input-sm" readonly="readonly" value="<?php echo $reqDetails->sgc_num;  ?>" name="penum">      
		                  </div>
		                </div><!-- end of form-group -->
		                <div class="form-group">
		                  <label class="col-sm-4 control-label">Retail Store:</label>
		                  <div class="col-sm-6">
		                      <input type="text" class="form form-control inptxt input-sm" name="storename" readonly="readonly" value="<?php echo $reqDetails->store_name; ?>">
		                      <input type="hidden" name="storeid" value="<?php echo $storeid; ?>">
		                  </div>
		                </div><!-- end of form-group -->
		                <div class="form-group">
		                  <label class="col-sm-4 control-label">Date Requested:</label>
		                  <div class="col-sm-5">
		                      <input type="text" class="form form-control inptxt input-sm" readonly="readonly" value="<?php echo _dateFormat($reqDetails->sgc_date_request); ?>">
		                  </div>
		                </div><!-- end of form-group -->
		                <div class="form-group">
		                  <label class="col-sm-4 control-label">Date Needed:</label>
		                  <div class="col-sm-5">
		                      <input type="text" class="form form-control inptxt inptxt input-sm" id="dp1" data-date-format="MM dd, yyyy" name="date_needed" value="<?php echo _dateFormat($reqDetails->sgc_date_needed); ?>" readonly="readonly">
		                  </div>
		                </div><!-- end of form-group -->
		                <div class="form-group">
		                  <label class="col-sm-4 control-label">Upload Document:</label>
		                  <div class="col-sm-6">
		                      <input type="hidden" value="<?php echo $reqDetails->sgc_file_docno; ?>" name="docu">
		                      <input id="pics" type="file" name="pic[]" accept="image/*" class="form form-control input-sm" />
		                  </div>
		                </div><!-- end of form-group -->
		                <input type="hidden" name="imgname" value="<?php echo $reqDetails->sgc_file_docno; ?>">
		                <?php if(!empty($reqDetails->sgc_file_docno)): ?>
		                <div class="form-group">
		                  <label class="col-sm-4 control-label">Uploaded Document:</label>
		                  <div class="col-sm-6">
							<a class="btn btn-block btn-default" href='../assets/images/gcRequestStore/download.php?file=<?php echo $reqDetails->sgc_file_docno; ?>'><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download</a>
		                  </div>
		                </div><!-- end of form-group -->	
		                <?php endif; ?>                
		                <div class="form-group">
		                  <label class="col-sm-4 control-label">Remarks:</label>
		                  <div class="col-sm-6">
		                  	<textarea class="form-control inptxt inptxt input-sm" name="remarks" id="remarks" autocomplete="off"><?php echo $reqDetails->sgc_remarks; ?></textarea>
		                  </div>
		                </div><!-- end of form-group -->
		                <div class="form-group">
		                  <label class="col-sm-4 control-label">Prepared by:</label>
		                  <div class="col-sm-4">
		                    <input type="text" readonly="readonly" class="form-control inptxt inptxt input-sm" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>">                     
		                  </div>
		                </div><!-- end of form-group -->
		                <div class="response">
		                </div>	           	           	         
		            </div>
					<div class="col-xs-3">
						<div class="forbox">
							<div class="box">
		                  		<div class="box-header"><h4><i class="fa fa-inbox"></i> Allocated GC</h4></div>
		                    	<div class="box-content form-container">
			                      <ul class="list-group">   
									<?php foreach ($denoms as $d): ?>
										<li class="list-group-item"><span class="badge" id="x<?php echo $d->denom_id; ?>"><?php echo getValidationNumRowsByStore($link,$storeid,$d->denom_id); ?></span> &#8369 <?php echo number_format($d->denomination,2); ?></li>          
									<?php endforeach ?>
			                      </ul>
		                    	</div>
		                    </div>
	                    </div>
					</div>
				</form>
			<?php endif;?>
		</div>
	</div>
</div>

<script>
	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

	var checkin = $('#dp1').datepicker({
	    beforeShowDay: function (date) {
	        return date.valueOf() >= now.valueOf();
	    },
	    autoclose: true
	});  
	$('.reqden,.ninternalcusd1,.ninternalcusq1').inputmask();
	// $("input[id^=num]").keyup(function(){
	// 	var sum = 0;
	// 	for(var $x=1;$x<=6;$x++) {
	// 		var inputs = $("#num"+$x).val();
	// 		inputs = inputs.replace(/,/g , "");
	// 		mul = inputs * $("#m"+$x).val();
	// 		sum = sum + mul;
	// 	}		
	// 	$('span.totalReq').text(addCommas(sum)+".00");
	// });
	
	$("input[id^=num]").keyup(function(){
		var stotal = 0, total = 0;
		$('.reqden').each(function(){
			var qty = $(this).val();
			qty = qty.replace(/,/g , "");
			denom = $(this).parent().find('.denval').val();
			stotal = Number(qty) * Number(denom);
			total +=stotal;			
		});
		$('span.totalReq').text(addCommas(total)+".00");
	});

	$('#num1').focus();

    function addCommas(nStr)
    {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }

    var limit = 10;
    var dencnt = 2;

	$('button#addenombut').click(function(){
		if(dencnt <= limit)
		{			
	 		$(this).before('<div class="form-group">'+
	        '<div class="col-sm-5">'+
	          '<input class="form form-control inptxt input-sm reqfield ninternalcusd'+dencnt+'" name="ninternalcusd[]" id="ninternalcusd" value="0" placeholder="0" autocomplete="off" autofocus />'+
	        '</div>'+
	        '<div class="col-sm-5">'+
	          '<input class="form form-control inptxt input-sm reqfield ninternalcusq'+dencnt+'" name="ninternalcusq[]" id="ninternalcusq" value="0" placeholder="0" autocomplete="off" autofocus />'+
	        '</div>'+
	        '<div class="col-sm-2" style="padding-left:0px;">'+
	          '<i class="fa fa-minus-square minus-denom removed" aria-hidden="true"></i>'+
	        '</div>'+
	      '</div>');

	 		dencnt++;

	 		$('#ninternalcusd,#ninternalcusq').inputmask("integer", { allowMinus: false,autoGroup: true, groupSeparator: ",", groupSize: 3 });
	 	}
	});

    $(document).on('keyup','input#ninternalcusd, input#ninternalcusq',function() {
    	scanInternalInput();
    });

	$(document).on('click','.removed',function() {
	 	$(this).parent('div').parent('div').remove();
	 	dencnt--;
	 	scanInternalInput();
	});

	function scanInternalInput()
	{
		var subtotal = 0;
		var total = 0;
		var numinput = $('.optionBox #ninternalcusd').length;
		$('.optionBox #ninternalcusd').each(function(){

			deinternal = $(this).val();
			deinternal = deinternal.replace(/,/g , "");
			deinternal = isNaN(deinternal) ? 0 : deinternal;

			qtyinternal = $(this).closest('div.form-group').find('input#ninternalcusq').val();
			qtyinternal = qtyinternal.replace(/,/g , "");
			qtyinternal = isNaN(qtyinternal) ? 0 : qtyinternal;

			subtotal = deinternal * qtyinternal;

			total +=subtotal;
		});
		//alert(total);
		$('label span#internaltot').text(addCommas(total.toFixed(2)));
		$('input#totolrequestinternal').val(total);
	}

</script>
