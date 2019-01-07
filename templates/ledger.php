<?php
session_start();
include '../function.php';
if(isset($_GET['page']))
{
	$page = $_GET['page'];

	if($page == 'ledger_budget')
	{
		$ledger = getBudgetLedger($link);
		?>
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-bot">
					<div class="box-header">
						<span class="box-title-with-btn"><i class="fa fa-inbox"></i> Budger Ledger</span>
						<div class="col-sm-8 form-horizontal pull-right">
							<label class="col-sm-2 control-label">Start Date</label>
							<div class="col-sm-3">
								<input type="text" class="form-control" id="dp1" readonly="readonly">
							</div>
							<label class="col-sm-2 control-label">End Date</label>
							<div class="col-sm-3">
								<input type="text" class="form-control" id="dp2" readonly="readonly">
							</div>
							<div class="col-sm-2">
								<button class="btn btn-block btn-info">Submit</button>
							</div>
						</div>
					</div>
					<div class="box-content">
						<div class="row">
							<div class="col-xs-12">
								<table class="table dtablest dataTable" id="tledger">
									<thead>
										<tr>
										<th>Ledger No.</th>
										<th>Date</th>
										<th>Transaction</th>
										<th style="text-align:right;">Debit</th>
										<th style="text-align:right;">Credit</th>
										<th style="text-align:center">Info</th>
									</tr>                
									</thead>
									<tbody>
										<?php foreach ($ledger as $key): ?>
											<tr>
												<td><?php echo $key->bledger_no; ?></td>
												<td><?php echo _dateFormat($key->bledger_datetime); ?></td>
												<td><?php 
											        switch($key->bledger_type)
											        {
											         	case 'RFBR':
											            	echo 'Budget Entry';
											            	break;
											          	case 'RFGCP':
											            	echo 'GC Production';
											            	break;
											          	case 'RFGCSEGC':
											            	echo 'Special External GC Request';
											            	break;
											          	case 'RFGCPROM':
											            	echo 'Promo GC Request';
											            	break;
											          	case 'GCPR':
											            	echo 'Promo GC Releasing';
											            	break;
											          	case 'GCSR':
											            	$table = ' approved_gcrequest';
											            	$select = 'stores.store_name';
											            	$where = "approved_gcrequest.agcr_id = '".$key->bledger_trid."'";
											            	$join = 'INNER JOIN
											                	store_gcrequest
											               	ON
											                	store_gcrequest.sgc_id = approved_gcrequest.agcr_request_id
											                INNER JOIN
											                	stores
											                ON
											                	stores.store_id = store_gcrequest.sgc_store';
											            	$limit = '';
											            	$storename = getSelectedData($link,$table,$select,$where,$join,$limit);

											            		echo 'GC Releasing ('.$storename->store_name.')';
											            	break;
											          	case 'RFGCSEGCREL':
											            	echo 'Special External GC Releasing';
											            	break;
											          	case 'RC':
											            	echo 'Requisition Cancelled';
											            	break;
											          	case 'GCRELINS':
											            	echo 'Institution GC Releasing';
											            	break;
											          	default:
											            	echo '';
											        }
											      ?></td>
											  <td style="text-align:right;"><?php echo $key->bdebit_amt == 0 ? '' : '&#8369 '.number_format($key->bdebit_amt,2); ?></td>
											  <td style="text-align:right;"><?php echo $key->bcredit_amt == 0 ? '' : '&#8369 '.number_format($key->bcredit_amt,2); ?></td>                      
											  <td></td>
											</tr>
										<?php endforeach ?>                  
									</tbody> 
									<tfoot>
									<tr>
										<th></th>
										<th></th>
										<th></th>
										<th style="text-align:right;">Remaining Budget:</th>
										<th style="text-align:right;"><?php echo '&#8369 '.number_format(currentBudget($link),2); ?></th>
										<th></th>
									</tr>
									</tfoot>              
								</table>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-offset-10 col-xs-2">
								<div class="btn btn-info">
									<a href="ledger_excel.php">Export (Excel)</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			$(document).ready(function(){
				var nowTemp = new Date();
				var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

				var checkin = $('#dp1').datepicker({

				    beforeShowDay: function (date) {
				    	// return date.valueOf() >= now.valueOf();
				        return date.valueOf();
				    },
				    autoclose: true

				}).on('changeDate', function (ev) {
				    if (ev.date.valueOf() > checkout.datepicker("getDate").valueOf() || !checkout.datepicker("getDate").valueOf()) {

				        var newDate = new Date(ev.date);
				        newDate.setDate(newDate.getDate() + 1);
				        checkout.datepicker("update", newDate);
				    }
				    $('#dp2')[0].focus();
				});


				var checkout = $('#dp2').datepicker({
				    beforeShowDay: function (date) {
				        if (!checkin.datepicker("getDate").valueOf()) {
				            return date.valueOf() >= new Date().valueOf();
				        } else {
				            return date.valueOf() > checkin.datepicker("getDate").valueOf();
				        }
				    },
				    autoclose: true

				}).on('changeDate', function (ev) {});

				$.extend( $.fn.dataTableExt.oStdClasses, {	  
				    "sLengthSelect": "selectsup"
				});

			    $('#tledger').dataTable({
			        "pagingType": "full_numbers",
			        "ordering": false,
			        "processing": true
			    });
			});
		</script>
		<?php
	}

}