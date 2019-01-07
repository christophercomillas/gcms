<script src="../assets/js/funct.js"></script>
<?php
session_start();
include '../function.php';

if(isset($_GET['page']))
{
	$page = $_GET['page'];

	if($page=='release-gc-customer')
	{
		if(isset($_SESSION['scanForReleasedCustomerGC']))
			unset($_SESSION['scanForReleasedCustomerGC']);
		$trnumber = getLastnumberOneWhere1($link,'institut_transactions','institutr_trnum','institutr_trtype','sales','institutr_trnum');
		?>
				<div class="row form-container">
			    	<div class="col-md-12">
			            <div class="panel with-nav-tabs panel-info">
			                <div class="panel-heading">
			                    <ul class="nav nav-tabs">
			                        <li class="active" style="font-weight:bold">
			                        	<a href="#tab1default" data-toggle="tab">	
			                        		Institution GC Sales
			                        	</a>
			                        </li>
			                        <!-- <li><a href="#tab2default" data-toggle="tab">Default 2</a></li> -->
			                    </ul>	                    
			                </div>
			                <div class="panel-body">
			                    <div class="tab-content">
			                        <div class="tab-pane fade in active" id="tab1default">
			                        	<div class="row">
											<form action="../ajax.php?action=releaseTreasuryCustomer" method="POST" id="releaseTreasuryCustomer" enctype="multipart/form-data">                  
		                          				<div class="col-sm-12">
		                              				<div class="col-sm-3">
		                                				<div class="form-group">
		                                  					<label class="nobot">GC Releasing #</label>   
		                                  					<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo sprintf("%03d", $trnumber); ?>" name="reqnum" id="reqnum">  
		                                				</div>
						                                <div class="form-group">
															<label class="nobot">Date Released</label> 
															<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo _dateFormat($todays_date); ?>">                   
						                                </div>
						                                <div class="form-group">
															<label class="nobot"><span class="requiredf">*</span>Received By:</label> 
															<input type="text" class="form form-control inptxt input-sm bot-6" value="" id="recby" name="recby" required autocomplete="off">                   
						                                </div>
														<div class="form-group">
															<label class="nobot"><span class="requiredf">*</span>Checked by:</label>
															<div class="input-group">
																<input name="checked" id="app-checkby" type="text" class="form-control input-sm inptxt reqfield" readonly="readonly" required="required">
																<span class="input-group-btn">
																	<button class="btn btn-info input-sm" id="checkbud" onclick="requestAssigInstitut(<?php echo $_SESSION['gc_usertype']; ?>,1);" type="button">
																		<span class="glyphicon glyphicon-search"></span>
																	</button>
																</span>
															</div><!-- input group -->
														</div>
						                                <div class="form-group">
															<label class="nobot">Remarks:</label> 
															<input type="text" class="form form-control inptxt input-sm bot-6" value="" id="remarks" name="remarks" autocomplete="off">                   
						                                </div>
						                                <div class="form-group">
															<label class="nobot">Upload Document</label> 
															<input id="input-file" class="file" type="file" name="docs[]" multiple>
						                                </div>
		                                			</div>

		                                			<div class="col-sm-4">
														<div class="form-group">
															<label class="nobot"><span class="requiredf">*</span>Customer</label>
															<input type="text" class="form form-control inptxt" readonly="readonly" name="cusname" id="cusname">
															<input type="hidden" name="cusid" value="" id="cusid">
														</div>       
														<div class="form-group">
															<button type="button" class="btn btn-info fordialog" onclick="lookupCustomerInstitGC();"><i class="fa fa-search-plus" aria-hidden="true"></i>
															Customer Lookup</button>
														</div>
														<div class="form-group">
															<label class="nobot"><span class="requiredf">*</span>Payment Fund</label>
															<input type="text" class="form form-control inptxt" readonly="readonly" name="payfund" id="payfund">
															<input type="hidden" name="payfundid" value="" id="payfundid">
														</div>       
														<div class="form-group">
															<button type="button" class="btn btn-info fordialog" onclick="lookupPaymentFund();"><i class="fa fa-search-plus" aria-hidden="true"></i>
															Payment Fund Lookup</button>
														</div>
														<div class="form-group">
															<label class="nobot"><span class="requiredf">*</span>Total Denomination</label> 
															<input type="text" class="form form-control inptxt amts" readonly="readonly" name="denocr" id="denocr" value="0.00" style="text-align:right;">
														</div>                          
														<div class="form-group">
															<label class="nobot"><span class="requiredf">*</span>Payment/Transaction Type</label>   
															<select class="form form-control input-sm inptxt" name="paymenttype" id="paymenttype" required>
																<option value="">- Select -</option>
																<option value="cash">Cash</option>
																<option value="check">Check</option>
																<option value="cashcheck">Check and Cash</option>
																<option value="gad">Giveaways & Donations</option>
															</select>
														</div>

														<div class="paymenttypediv" style="display:none">
															<div class="cashpayment">												
																<div class="form-group">
																	<label class="nobot"><span class="requiredf">*</span>Amount Received</label>
																	<input type="text" class="form form-control inptxt input-sm bot-6 amts" name="cashonly" id="cashonly" autocomplete="off" data-inputmask="'alias': 'numeric','groupSeparator': ',','autoGroup': true,'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" required>
																</div>	
															</div>

															<div class="checkpayment">
																<div class="form-group">
																	<label class="nobot"><span class="requiredf">*</span>Bank Name</label>
																	<input type="text" class="form form-control inptxt input-sm bot-6 amts" name="banknamecheckonly" id="banknamecheckonly" autocomplete="off">
																</div>
																<div class="searchkeyup">
																	<ul>
																		<li class="lisearch"><span>Sample</span></li>
																		<li class="lisearch"><span>SAmple 2</span></li>
																	</ul>														
																</div>
																<div class="form-group">
																	<label class="nobot"><span class="requiredf">*</span>Bank Account Number</label>
																	<input type="text" class="form form-control inptxt input-sm bot-6 amts" name="baccountnumcheckonly" id="baccountnumcheckonly" autocomplete="off">
																</div>
																<div class="form-group">
																	<label class="nobot"><span class="requiredf">*</span>Check Number</label>
																	<input type="text" class="form form-control inptxt input-sm bot-6 amts" name="cnumbercheckonly" id="cnumbercheckonly" autocomplete="off">
																</div>
																<div class="form-group">
																	<label class="nobot"><span class="requiredf">*</span>Check Amount</label>
																	<input type="text" class="form form-control inptxt input-sm bot-6 amts" name="checkonly" id="checkonly" autocomplete="off" data-inputmask="'alias': 'numeric','groupSeparator': ',','autoGroup': true,'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" required>
																</div>									
															</div>

															<div class="cashcheck">
																<div class="form-group">
																	<label class="nobot"><span class="requiredf">*</span>Bank Name:</label>
																	<input type="text" class="form form-control inptxt input-sm bot-6 amts" name="ccbankname" id="ccbankname" autocomplete="off">
																</div>
																<div class="form-group">
																	<label class="nobot"><span class="requiredf">*</span>Bank Account Number</label>
																	<input type="text" class="form form-control inptxt input-sm bot-6 amts" name="ccbaccountnum" id="ccbaccountnum" autocomplete="off">
																</div>
																<div class="form-group">
																	<label class="nobot"><span class="requiredf">*</span>Check Number</label>
																	<input type="text" class="form form-control inptxt input-sm bot-6 amts" name="cchecknumber" id="cchecknumber" autocomplete="off">
																</div>
																<div class="form-group">
																	<label class="nobot"><span class="requiredf">*</span>Check Amount</label>
																	<input type="text" class="form form-control inptxt input-sm bot-6 amts" name="ccheck" id="ccheck" autocomplete="off" data-inputmask="'alias': 'numeric','groupSeparator': ',','autoGroup': true,'digits':2,'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" required>
																</div>	
																<div class="form-group">
																	<label class="nobot"><span class="requiredf">*</span>Cash</label>
																	<input type="text" class="form form-control inptxt input-sm bot-6 amts" name="ccash" id="ccash" autocomplete="off" data-inputmask="'alias': 'numeric','digits':2,'groupSeparator': ',','autoGroup': true,'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" required>
																</div>
																<div class="form-group">
																	<label class="nobot"><span class="requiredf">*</span>Total Amount Received</label>
																	<input type="text" class="form form-control inptxt input-sm bot-6 amts" readonly="readonly" name="cctotal" id="cctotal" autocomplete="off"  style="text-align:right" value="0.00">
																</div>																
															</div>

															<div class="gad">
																<div class="form-group">
																	<label class="nobot"><span class="requiredf">*</span>Supporting Document:</label>
																	<input type="text" class="form form-control inptxt input-sm bot-6 amts" name="gadocu" id="gadocu" autocomplete="off">
																</div>															
															</div>

															<div class="form-group changedx">
																<label class="nobot"><span class="requiredf">*</span>Change</label>
																<input type="text" class="form form-control inptxt input-sm bot-6" name="paymentchange" id="paymentchange" autocomplete="off" style="text-align:right" readonly="readonly">
															</div>	
														</div>
		                                			</div>

		                                			<div class="col-sm-5">
														<div class="form-group">
															<button type="button" class="btn btn-info fordialog pull-right" onclick="scanGCForReleasing();"><i class="fa fa-plus" aria-hidden="true"></i>
															 Scan GC By Barcode</button>

															<button type="button" class="btn btn-info fordialog pull-right" onclick="scanGCRangeForReleasingInstitution();"><i class="fa fa-plus" aria-hidden="true"></i>
															 Scan GC By Range</button>															 
														</div>   
		                                				<table class="table" id="scanGCForCustomerReleasing">
		                                					<thead>
		                                						<tr>
		                                							<th>Denomination</th>
		                                							<th>Barcode</th>
		                                							<th>Remove</th>
		                                						</tr>
		                                					</thead>
		                                				</table>
		                                				<div class="response">

		                                				</div>
														<div class="form-group">
															<div class="col-sm-offset-5 col-sm-7">
																<button type="submit" class="btn btn-block btn-primary" id="tresCustomerbtn"> <span class="glyphicon glyphicon-share" aria-hidden="true"></span> Submit</button>
															</div>
														</div>
		                                			</div>

		                                		</div>

		                                	</form>

			                        	</div>
			                        </div>
			                        <!-- <div class="tab-pane fade" id="tab2default">Default 2</div> -->
			                    </div>
			                </div>
			            </div>
			        </div>
				</div>
				<div class="modal modal-static fade loadingstyle" id="processing-modal" role="dialog" aria-hidden="true" data-keyboard="false" data-backdrop="static">
				    <div class="modal-dialog loadingstyle">
				      <div class="text-center">
				          <img src="../assets/images/ring-alt.svg" class="icon" />
				          <h4 class="loading">Saving Data...</h4>
				      </div>
				    </div>
				</div>
				<script type="text/javascript">

					$.extend( $.fn.dataTableExt.oStdClasses, {	  
					    "sFilterInput": "searchcus"
					});
				    // $('#scanGCForCustomerReleasing	').dataTable( {
				    //     "pagingType": "full_numbers",
				    //     "ordering": false,
				    //     "processing": true,
				    //     "bProcessing":true
				    // });

			        $('#scanGCForCustomerReleasing').DataTable( {
			            "order": [[ 0, "desc" ]]
			        } );

					$('#cashonly,#checkonly,#ccash,#ccheck').inputmask();
				    $('input#input-file').fileinput({
				      'allowedFileExtensions' : ['jpg','png','jpeg']
				    });

				    $('#banknamecheckonly').bind({
				    	keyup: function(){
				    		//$('.searchkeyup').show();
				    	},
				    	blur: function(){
				    		//$('.searchkeyup,').hide();
				    	}
				    	
				    });

				    $('li.lisearch').click(function(){
				    	$('#banknamecheckonly').val($(this).find('span').text());
				    	$('.searchkeyup').hide();
				    });

					$('input#cashonly,input#checkonly,input#ccash,input#ccheck').keyup(function(){

						if($('#paymenttype').val()=='cash' || $('#paymenttype').val()=='check')
						{							
							var amount = $(this).val();
							calculateChange(amount);
						}

						if($('#paymenttype').val()=='cashcheck')
						{
							calculateChangeCC();
						}
                    });
                    
                    function requestAssigInstitut(dept,type)
                    {
                        var header = '';
                        if(type==1)
                        {
                            header = 'Checked Tag..';
                        }
                        else 
                        {
                            header = 'Approved Tag..'
                        }
                        BootstrapDialog.show({
                            closable: true,
                            closeByBackdrop: false,
                            closeByKeyboard: true,
                            cssClass: 'modal-checkedandapproved',
                            onshown: function(dialog){							
                            },
                            title: header,
                            message: function(dialog) {
                                var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
                                var pageToLoad = dialog.getData('pageToLoad');
                                setTimeout(function(){
                                $message.load(pageToLoad);
                                },1000);
                                return $message;
                            },
                            data: {
                                'pageToLoad': '../dialogs/requestAssig.php?dept='+dept,
                            },
                            buttons: [{
                                icon: 'glyphicon glyphicon-ok-sign',
                                label: 'Confirm',
                                cssClass: 'btn-primay',
                                hotkey: 13,
                                action:function(dialogItself){
                                    var aid = $('#auth').val();

                                    $.ajax({
                                        url:'../ajax.php?action=getAssignatoriesDetails',
                                        type:'POST',
                                        data:{aid:aid},
                                        beforeSend:function(){

                                        },
                                        success:function(data){
                                            console.log(data);
                                            var data = JSON.parse(data);   

                                            if(data['st'])
                                            {                    
                                                if(type==1)
                                                {
                                                    $('#app-checkby').val(data['name']);
                                                }
                                                else 
                                                {
                                                    $('#app-apprby').val(data['name']);
                                                }
                                                dialogItself.close();  							
                                            } 
                                            else 
                                            {
                                                console.log(data['msg']);
                                            }              
                                        }
                                    });
                                }
                            }, {
                                icon: 'glyphicon glyphicon-remove-sign',
                                label: 'Close',
                                action: function(dialogItself){
                                    dialogItself.close();
                                }
                            }]
                        });
                    }

					function calculateChangeCC()
					{
						var total = 0;
						var checkamt = 0;
						var cashamt = 0;
						checkamt = $('#ccheck').val().replace(/,/g , "");
						checkamt = isNaN(checkamt) ? 0.00 : checkamt;

						cashamt = $('#ccash').val().replace(/,/g , "");
						cashamt = isNaN(cashamt) ? 0.00 : cashamt;

						total = parseFloat(checkamt) + parseFloat(cashamt);

						total = isNaN(total) ? 0.00 : total;
						total = total === 0 ? 0.00 : total;

						$('#cctotal').val(addCommas(parseFloat(total).toFixed(2)));

						var totdenom = $('#denocr').val();
						totdenom = totdenom.replace(/,/g , "");
						totdenom = isNaN(totdenom) ? 0.00 : totdenom;

						if(totdenom!="0.00")
						{
							var change = 0;
							change = parseFloat(total - totdenom);

							if(change > 0)
							{
								//addCommas(parseFloat(data1['total']).toFixed(2));
								change = addCommas(parseFloat(change).toFixed(2));
								$('#paymentchange').val(change);
							}
							else 
							{
								$('#paymentchange').val('0.00');
							}
						}	
					}

					function calculateChange(amount)
					{
						amount = amount.replace(/,/g , "");
						amount = isNaN(amount) ? 0.00 : amount;

						var totdenom = $('#denocr').val();
						totdenom = totdenom.replace(/,/g , "");
						totdenom = isNaN(totdenom) ? 0.00 : totdenom;

						if(totdenom!="0.00")
						{

							var change = 0;
							change = parseFloat(amount - totdenom);

							if(change > 0)
							{
								//addCommas(parseFloat(data1['total']).toFixed(2));
								change = addCommas(parseFloat(change).toFixed(2));
								$('#paymentchange').val(change);
							}
							else 
							{
								$('#paymentchange').val('0.00');
							}
						}

						// if(totdenom > 0 && amount > totdenom)
						// {
						// 	change = parseFloat(amount - totdenom);
						// 	if(change > 0)
						// 	{
						// 		change = parseFloat(change).toFixed(2);
						// 	}
						// 	$('#paymentchange').val(change);
						// }
						// else 
						// {
						// 	$('#paymentchange').val('0.00');
						// }
					}

					$('.form-container').on('submit','form#releaseTreasuryCustomer',function(e){
						e.preventDefault();
						$('.response').html('');

						var formURL = $(this).attr('action'), formData = new FormData($(this)[0]);	

						if($('#cusid').val().trim()=='')
						{
							$('.response').html('<div class="alert alert-danger">Please select customer.</div>');
							return false;
						}

						var totaldenom = $('#denocr').val().trim();
						totaldenom = totaldenom.replace(/,/g , "");
						totaldenom = isNaN(totaldenom) ? 0.00 : totaldenom;

						if(totaldenom==0.00)
						{
							$('.response').html('<div class="alert alert-danger">Please scan GC.</div>');
							return false;
						}

						if($('#recby').val().trim()=='')
						{
							$('.response').html('<div class="alert alert-danger">Please fill in Received by field.</div>');
							return false;							
						}

						if($('#app-checkby').val().trim()=='')
						{
							$('.response').html('<div class="alert alert-danger">Please fill in Checked by field.</div>');
							return false;								
						}

						if($('#cusname').val().trim()=='')
						{
							$('.response').html('<div class="alert alert-danger">Please select customer.</div>');
							return false;							
						}

						if($('#payfund').val().trim()=='')
						{
							$('.response').html('<div class="alert alert-danger">Please select payment fund.</div>');
							return false;								
						}

						if($('#paymenttype').val().trim()=='')
						{
							$('.response').html('<div class="alert alert-danger">Please select payment type.</div>');
							return false;							
						}

						if($('#paymenttype').val().trim()=='cash')
						{
							var total = $('#cashonly').val().trim();
							total = total.replace(/,/g , "");
							total = isNaN(total) ? 0.00 : total;
							total = parseFloat(total);
						}

						if($('#paymenttype').val().trim()=='check')
						{
							if($('#banknamecheckonly').val().trim()=='' || $('#baccountnumcheckonly').val().trim()=='' || $('#cnumbercheckonly').val().trim()=='')
							{
								$('.response').html('<div class="alert alert-danger">Please fill check info.</div>');
								return false;
							}

							var total = $('#checkonly').val().trim();
							total = total.replace(/,/g , "");
							total = isNaN(total) ? 0.00 : total;
							total = parseFloat(total);
						}

						if($('#paymenttype').val().trim()=='cashcheck')
						{
							if($('#ccbankname').val().trim()=='' || $('#ccbaccountnum').val().trim()=='' || $('#cchecknumber').val().trim()=='')
							{
								$('.response').html('<div class="alert alert-danger">Please fill check info.</div>');
								return false;
							}

							var check = $('#ccheck').val().trim();
							check = check.replace(/,/g , "");
							check = isNaN(check) ? 0.00 : check;

							var cash = $('#ccash').val().trim();
							cash = cash.replace(/,/g , "");
							cash = isNaN(cash) ? 0.00 : cash;	
							
							total = parseFloat(check) + parseFloat(cash);						

							total = parseFloat(total);

							if(check==0.00 || cash==0.00)
							{
								$('.response').html('<div class="alert alert-danger">Please input valid check amount or cash.</div>');
								return false;
							}
						}

						if($('#paymenttype').val().trim()=='gad')
						{
							if($('#gadocu').val()=='')
							{
								$('.response').html('<div class="alert alert-danger">Please input supporting document #.</div>');
							}
							
						}

						if(totaldenom > total)
						{
							$('.response').html('<div class="alert alert-danger">Total Denomination is greater than amount received.</div>');
							return false;
						}

				        BootstrapDialog.show({
				        	title: 'Confirmation',
				            message: 'Release GC?',
				            closable: true,
				            closeByBackdrop: false,
				            closeByKeyboard: true,
				            onshow: function(dialog) {
				               	$("button#tresCustomerbtn").prop("disabled",true);
				            },
				            onhidden: function(dialog){
				            	$("button#tresCustomerbtn").prop("disabled",false);
				            },
				            buttons: [{
				                icon: 'glyphicon glyphicon-ok-sign',
				                label: 'Yes',
				                cssClass: 'btn-primary',
				                hotkey: 13,
				                action:function(dialogItself){             	
				                	dialogItself.close();

									$.ajax({
							    		url:formURL,
							    		type:'POST',
										data: formData,
										enctype: 'multipart/form-data',
									    async: true,
									    cache: false,
									    contentType: false,
									    processData: false,
										beforeSend:function(){
											$('#processing-modal').modal('show');
										},
										success:function(data)
										{
											$('#processing-modal').modal('hide');
											console.log(data);
											var data = JSON.parse(data);
											if(data['st'])
											{
												var dialog = new BootstrapDialog({
									            message: function(dialogRef){
									            var $message = $('<div>GC Releasing Successfully Saved.</div>');			        
									                return $message;
									            },
									            closable: false
										        });
										        dialog.realize();
										        dialog.getModalHeader().hide();
										        dialog.getModalFooter().hide();
										        dialog.getModalBody().css('background-color', '#0088cc');
										        dialog.getModalBody().css('color', '#fff');
										        dialog.open();
										        setTimeout(function(){
							                    	dialog.close();
							               		}, 1500);
							               		setTimeout(function(){
							                    	window.location.href = 'gcreleased-institutions-pdf.php?id='+data['id'];
							               		}, 1700);	
											}
											else 
											{
												$('.response').html('<div class="alert alert-danger" id="danger-x">'+data['msg']+'</div>');
											}
										}
									});		

				                }
				            }, {
				            	icon: 'glyphicon glyphicon-remove-sign',
				                label: 'No',
				                action: function(dialogItself){
				                    dialogItself.close();
				                }
				            }]
				        });
					});

					$('#paymenttype').change(function(){
						var type = $(this).val();

						$('#paymentchange').val("0.00");

						$('#cashonly').val("0.00");

						$('#banknamecheckonly').val("");
						$('#baccountnumcheckonly').val("");
						$('#cnumbercheckonly').val("");
						$('#checkonly').val("0.00");

						$('#ccbankname').val("");
						$('#ccbaccountnum').val("");
						$('#cchecknumber').val("");
						$('#ccheck').val("0.00");
						$('#ccash').val("0.00");
						$('#cctotal').val("0.00");

						if(type=='')
						{
							$('.paymenttypediv').hide();
						}
						else if(type=='cash')
						{
							$('.paymenttypediv').show();
							$('.cashcheck').hide();
							$('.checkpayment').hide();
							$('.gad').hide();	
							$('.cashpayment').fadeIn(500).show(600);
							$('.changedx').fadeIn(500).show(600);
							

							$('#cashonly').prop('required',true);

							$('#banknamecheckonly').prop('required',false);
							$('#baccountnumcheckonly').prop('required',false);
							$('#cnumbercheckonly').prop('required',false);
							$('#checkonly').prop('required',false);

							$('#ccbankname').prop('required',false);
							$('#ccbaccountnum').prop('required',false);
							$('#cchecknumber').prop('required',false);
							$('#ccheck').prop('required',false);
							$('#ccash').prop('required',false);
							$('#cctotal').prop('required',false);

							$('#gadocu').prop('required',false);
						}
						else if(type=='check')
						{
							$('.paymenttypediv').show();
							$('.cashpayment').hide();
							$('.cashcheck').hide();
							$('.gad').hide();	
							$('.checkpayment').fadeIn(500).show(600);
							$('.changedx').fadeIn(500).show(600);

							$('#cashonly').prop('required',false);

							$('#banknamecheckonly').prop('required',true);
							$('#baccountnumcheckonly').prop('required',true);
							$('#cnumbercheckonly').prop('required',true);
							$('#checkonly').prop('required',true);

							$('#ccbankname').prop('required',false);
							$('#ccbaccountnum').prop('required',false);
							$('#cchecknumber').prop('required',false);
							$('#ccheck').prop('required',false);
							$('#ccash').prop('required',false);
							$('#cctotal').prop('required',false);

							$('#gadocu').prop('required',false);
						}
						else if(type=='cashcheck')
						{
							$('.paymenttypediv').show();
							$('.cashpayment').hide();
							$('.checkpayment').hide();
							$('.gad').hide();	
							$('.cashcheck').fadeIn(500).show(600);
							$('.changedx').fadeIn(500).show(600);

							$('#cashonly').prop('required',false);

							$('#banknamecheckonly').prop('required',false);
							$('#baccountnumcheckonly').prop('required',false);
							$('#cnumbercheckonly').prop('required',false);
							$('#checkonly').prop('required',false);

							$('#ccbankname').prop('required',true);
							$('#ccbaccountnum').prop('required',true);
							$('#cchecknumber').prop('required',true);
							$('#ccheck').prop('required',true);
							$('#ccash').prop('required',true);
							$('#cctotal').prop('required',true);

							$('#gadocu').prop('required',false);
						}
						else if(type=='gad')
						{
							$('.paymenttypediv').show();
							$('.cashcheck').hide();
							$('.checkpayment').hide();
							$('.cashpayment').hide();
							$('.changedx').hide();
							$('.gad').fadeIn(500).show(600);							

							$('#cashonly').prop('required',false);

							$('#banknamecheckonly').prop('required',false);
							$('#baccountnumcheckonly').prop('required',false);
							$('#cnumbercheckonly').prop('required',false);
							$('#checkonly').prop('required',false);

							$('#ccbankname').prop('required',false);
							$('#ccbaccountnum').prop('required',false);
							$('#cchecknumber').prop('required',false);
							$('#ccheck').prop('required',false);
							$('#ccash').prop('required',false);
							$('#cctotal').prop('required',false);

							$('#gadocu').prop('required',true);
						}
					});

					$('table#scanGCForCustomerReleasing').on('click','.remove-employee',function(){
						var key = $(this).parents('tr').find('input.denoms').val();
						var r = confirm("Remove Barcode?");
						if (r == true) {

							$.ajax({
								//url:'../ajax.php?action=deleteAssignByKey',
								url:'../ajax.php?action=removeByBarcodeTresRelByCustomer',
								data:{key:key},
								type:'POST',
								success:function(data)
								{
									console.log(data);
									var data = JSON.parse(data);
									if(data['st'])
									{
										var total = data['total'];
										total = addCommas(parseFloat(total).toFixed(2));
										$('#denocr').val(total);
									}
								}
							});

							var table = $('#scanGCForCustomerReleasing').DataTable();
							table
							.row( $(this).parents('tr') )
							.remove()
							.draw();
						}
						
						$('input[name=lastname]').focus();
					});

					flag = 0;

					function scanGCRangeForReleasingInstitution()
					{
						//dri
					    BootstrapDialog.show({
					        title: 'Scan By GC Barcode # Range',
					        cssClass: 'customer-internal',
					        closable: true,
					        closeByBackdrop: false,
					        closeByKeyboard: true,
					        message: function(dialog) {
					            var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
					            var pageToLoad = dialog.getData('pageToLoad');
					            setTimeout(function(){
					            $message.load(pageToLoad);
					            },1000);
					            return $message;
					        },
					        data: {
					            'pageToLoad': '../templates/regulargc.php?page=scanGCRangeForReleasingInstitution'
					        },
					        onshown: function(dialogRef){

					        },
					        onhidden:function(dialogRef){
					        },        
					        buttons: [{
					            icon: 'glyphicon glyphicon-ok-sign',
					            label: 'Submit',
					            cssClass: 'btn-primary',
					            hotkey: 13,
					            action:function(dialogItself){
					            	$('.responserange').html('');
                					$('.response').html('');
					            	var t = $('#scanGCForCustomerReleasing').DataTable();
						    		var counter = 1;

						    		var reqnum = $('#reqnum').val();

					                var bstart = $('.validateGCstart').val(), bend = $('.validateGCend').val(); 
					                var formURL = $('form#scanrStoreGCRangeInstitution').attr('action'), formData = $('form#scanrStoreGCRangeInstitution').serialize();
					                var flag = $('input[name="flag"]').val();

					                if(flag==1)
					                {
					                    if(bstart=='')
					                    {
					                        $('.responserange').html('<div class="alert alert-danger">Plese scan gc first.</div>');
					                        $('.validateGCstart').select(); 
					                    }
					                    else if(bstart.length!=13)
					                    {
					                        $('.responserange').html('<div class="alert alert-danger">GC barcode number must be 13 characters long.</div>');
					                        $('.validateGCstart').select();                         
					                    }
					                    else 
					                    {
					                        barcode = bstart;
					                        $.ajax({
					                            //gcreleasevalidationpromo

					                            url:'../ajax.php?action=scanGCRangeForCustomerReleasingBstart',
					                            //url:'../ajax.php?action=isValidGC',
					                            data:{barcode:barcode,reqnum:reqnum},
					                            type:'POST',
					                            success:function(data)
					                            {
					                                console.log(data);
					                                var data = JSON.parse(data);
					                                if(data['stat'])
					                                {
					                                    $('.validateGCstart').prop('readonly','readonly');  
					                                    $('.validateGCend').prop('disabled',false); 
					                                    $('input[name="dens"]').val(data['denid']);
					                                    //denom = data['denom'];    
					                                    $('.validateGCend').focus();
					                                    $('.responserange').html('');       
					                                    $('input[name="flag"]').val(2); 
					                                    $('.responserange').html('<div class="alert alert-success">'+data['msg']+'</div>');                                                         
					                                }
					                                else 
					                                {
					                                    $('.responserange').html('<div class="alert alert-danger">'+data['msg']+'</div>');
					                                    $('.validateGCstart').select(); 
					                                }
					                            }
					                        });                     

					                    }
					                }

					                if(flag==2)
					                {

					                    if(bend=='')
					                    {
					                        $('.responserange').html('<div class="alert alert-danger">Plese scan gc first.</div>');
					                        $('.validateGCend').select();   
					                    }
					                    else if(bstart.length!=13)
					                    {
					                        $('.responserange').html('<div class="alert alert-danger">GC barcode number must be 13 characters long.</div>');
					                        $('.validateGCend').select();                           
					                    }
					                    else
					                    {                   

					                        barcode = bend;					                        

					                        $.ajax({
					                            url:'../ajax.php?action=scanGCRangeForCustomerReleasingBstart',
					                            data:{barcode:barcode,reqnum:reqnum},
					                            type:'POST',
					                            success:function(data)
					                            {
					                                console.log(data);
					                                var data = JSON.parse(data);
					                                if(!data['stat'])
					                                {
					                                    $('.responserange').html('<div class="alert alert-danger">'+data['msg']+'</div>');
					                                    $('.validateGCend').select();                                                           
					                                }
					                                else 
					                                {

					                                    var denstart = $('input[name="dens"]').val();
					                                    var denend =  data['denid'];
					                                    var denom = data['denom'];

					                                    if(parseInt(denstart.trim())!=parseInt(denend.trim()))
					                                    {
					                                        $('.responserange').html('<div class="alert alert-danger">Invalid Denomination.</div>');
					                                        $('.validateGCend').select();   
					                                    }
					                                    else if(bstart >= bend)
					                                    {
					                                        $('.responserange').html('<div class="alert alert-danger">Invalid GC Barcode # Range.</div>');
					                                        $('.validateGCend').select();       
					                                    }
					                                    else 
					                                    {


				                                            var dialog = new BootstrapDialog({
				                                            message: function(dialogRef){                                                              
				                                            var $message = $('<div class="rangeval">'+
				                                                    '<div class="alert alert-info validate-flash" id="_adjust_alert">'+
				                                                    '<p class="bar-range"><img src="../assets/images/ajax.gif">Validating Barcode Number: </p>'+
				                                                    '<p class="br">'+bstart+' to '+bend+'</p>'+
				                                                    '<p class="den">Denomination:<span class="den-color"> &#8369 '+denom+'</span></p>'+                                                                       
				                                                    '</div>');                  
				                                                return $message;
				                                            },
				                                            closable: false
				                                            });
				                                            dialog.realize();
				                                            dialog.getModalHeader().hide();
				                                            dialog.getModalFooter().hide();
				                                            dialog.getModalContent().css('background-color','none');
				                                            dialog.getModalBody().css('color', '#fff');
				                                            dialog.open();

				                                            barcode = bstart;		                                            

															appendBarcodes(bstart,bend,barcode,dialog);                       	

					                                    }
					                                    
					                                }
					                            }
					                        });   
					                    }

					                    //$('.validateGCend').select();
					                }

					        	}
								},{
								icon: '',
								label: '<i class="fa fa-spinner"></i> Reset',
								cssClass: 'btn-default',
								action:function(dialogItself){
									$('.validateGCstart').prop('readonly','');	
									$('.validateGCend').val('').prop('disabled',true);
									$('input[name="dens"]').val('');	
									$('.validateGCstart').val('').focus();
									$('.responserange').html('');		
									$('input[name="flag"]').val(1);
								}
								},{
					        	icon: 'glyphicon glyphicon-remove-sign',
					            label: 'Close',
					            action: function(dialogItself){
					                dialogItself.close();
					            }
					        }]
					    });
					}

					function appendBarcodes(bstart,bend,barcode,dialog)
					{
						barcode = bstart -1;
						flag = 0;
		            	var t = $('#scanGCForCustomerReleasing').DataTable();
			    		var counter = 1;

						var timerId = 0;
					    timerId = setInterval(function(){
					    	barcode++;

                            $.ajax({
                                url:'../ajax.php?action=scanGCRangeForCustomerReleasing',
                                data:{bstart:bstart,bend:bend,barcode:barcode},
                                type:'POST',					                                                
                                success:function(data1)
                                {						                                         
                                    console.log(data1);
                                    var data1 = JSON.parse(data1);
                                    if(data1['st']==0)
                                    {
                                        //dialogItself.close();

                                        $('.validateGCstart').prop('readonly','');  
                                        $('.validateGCend').prop('disabled',true);  
                                        $('.validateGCend').val('');    
                                        $('input[name="dens"]').val('');    
                                        $('.validateGCstart').val('').focus();
                                        $('.responserange').html('');       
                                        $('input[name="flag"]').val(1);    

                                        $('.responserange').html('<div class="alert alert-danger">'+data1['msg']+'</div>');

                                    	clearInterval(timerId);	
                                    	dialog.close();
                                    }


                                    if(data1['st']==1)
                                    {
						    			var counter = 1;
								        t.row.add( [		        	
								            data1['denomination'],
								            data1['barcode'],
								            '<input type="hidden" value="'+data1['key']+'" class="denoms"><i class="fa fa-times remove-employee" aria-hidden="true"></i>'
								        ] ).draw( false );



								        var total = addCommas(parseFloat(data1['total']).toFixed(2));

								        //total = parseFloat(total).toFixed(2);

								        $('#denocr').val(total);

								        $('.br').text(barcode);

								        if(barcode==bend)
								        {
								        	clearInterval(timerId);
								        	dialog.close();
	                                        $('.validateGCstart').prop('readonly','');  
	                                        $('.validateGCend').prop('disabled',true);  
	                                        $('.validateGCend').val('');   
	                                        $('.validateGCstart').val(''); 

	                                        $('input[name="dens"]').val('');    
	                                        
	                                        $('.responserange').html('');       
	                                        $('input[name="flag"]').val(1);                                                                     
	                                        $('.responserange').html('<div class="alert alert-success">Barcode Range successfully scanned.</div>'); 
	                                        $('.validateGCstart').focus();
								        }								        							        
                                    }
                                }                                
                            });

					    },800);
					}

					function scanGCForReleasing()
					{
					    BootstrapDialog.show({
					        title: 'Scan GC',
					        cssClass: 'customer-internal',
					        closable: true,
					        closeByBackdrop: false,
					        closeByKeyboard: true,
					        message: function(dialog) {
					            var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
					            var pageToLoad = dialog.getData('pageToLoad');
					            setTimeout(function(){
					            $message.load(pageToLoad);
					            },1000);
					            return $message;
					        },
					        data: {
					            'pageToLoad': '../templates/regulargc.php?page=scanGCForCustomerReleasing'
					        },
					        onshown: function(dialogRef){

					        },
					        onhidden:function(dialogRef){
					        },        
					        buttons: [{
					            icon: 'glyphicon glyphicon-ok-sign',
					            label: 'Submit',
					            cssClass: 'btn-primary',
					            hotkey: 13,
					            action:function(dialogItself){
					            	$('.response-validate').html('');
					            	var t = $('#scanGCForCustomerReleasing').DataTable();
						    		var counter = 1;
					             	var barcode = $('#gcbarcode').val(), formUrl = $('form#scanGCForCustomerReleasing').attr('action');
									if(barcode==undefined)
									{
										return false;
									}

									if(barcode.trim()=='')
									{
					    				$('.response-validate').html('<div class="alert alert-danger alert-dismissable">Please input GC barcode number.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');		
					    				$('#gcbarcode').select();
					    				return false;
									}
									$.ajax({
										url:formUrl,
										data:{barcode:barcode},
										type:'POST',
										success:function(data){
											console.log(data);
											var data = JSON.parse(data);
											if(data['st'])
											{
								    			var counter = 1;
										        t.row.add( [		        	
										            data['denomination'],
										            data['barcode'],
										            '<input type="hidden" value="'+data['key']+'" class="denoms"><i class="fa fa-times remove-employee" aria-hidden="true"></i>'
										        ] ).draw( false );
										 		
										        counter++;

										        var total = parseFloat(data['total']).toFixed(2);

										        $('#denocr').val(addCommas(total));

												if($('#paymenttype').val()=='cash')
												{							
													var amount = $("#cashonly").val();
													calculateChange(amount);
												}

												if($('#paymenttype').val()=='check')
												{							
													var amount = $('#checkonly').val();
													calculateChange(amount);
												}												

												if($('#paymenttype').val()=='cashcheck')
												{
													calculateChangeCC();
												}

												$('.response-validate').html('<div class="alert alert-success alert-dismissable">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');	
												$('#gcbarcode').select();
											}
											else 
											{
												$('.response-validate').html('<div class="alert alert-danger alert-dismissable">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');	
												$('#gcbarcode').select();								
											}
										}
									});       	
					        	}
					        }, {
					        	icon: 'glyphicon glyphicon-remove-sign',
					            label: 'Close',
					            action: function(dialogItself){
					                dialogItself.close();
					            }
					        }]
					    });
					}
				</script>
		<?php
	}
	elseif($page=='lookupcustomerinst')
	{

		$select = "	ins_name,
			ins_date_created,
			ins_id";

		$where = "institut_customer.ins_status='active'";

		$join = '';
		$limit ='ORDER BY 
				institut_customer.ins_date_created
			DESC';
		$cus = getAllData($link,'institut_customer',$select,$where,$join,$limit);

		?>

		<div class="row">
			<div class="col-sm-12 lookupcust">
				<table class="table" id="lookupcus">
					<thead>
						<tr>
							<th>Name</th>
							<th>Date Created</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($cus as $c): ?>
							<tr data-id="<?php echo $c->ins_id; ?>">
								<td><?php echo ucwords($c->ins_name); ?></td>
								<td><?php echo _dateFormat($c->ins_date_created); ?></td>
							</tr>
						<?php endforeach; ?>
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
				$('#cusid').val(id);
				$('#cusname').val(compname);
				//var accname = $(this).find('td:nth-child(2)').text();		
				BootstrapDialog.closeAll();
			});

		</script>


		<?php
	}
	elseif($page=='lookuppaymentfundins')
	{
		$select = " pay_id,
		    pay_desc,
		    pay_dateadded";

		$where = "payment_fund.pay_status='active'";

		$join = '';
		$limit ='ORDER BY 
				payment_fund.pay_dateadded
			DESC';
		$cus = getAllData($link,'payment_fund',$select,$where,$join,$limit);

		?>

		<div class="row">
			<div class="col-sm-12 lookupcust">
				<table class="table" id="lookupcus">
					<thead>
						<tr>
							<th>Name</th>
							<th>Date Created</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($cus as $c): ?>
							<tr data-id="<?php echo $c->pay_id; ?>">
								<td><?php echo ucwords($c->pay_desc); ?></td>
								<td><?php echo _dateFormat($c->pay_dateadded); ?></td>
							</tr>
						<?php endforeach; ?>
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
				$('#payfundid').val(id);
				$('#payfund').val(compname);
				//var accname = $(this).find('td:nth-child(2)').text();		
				BootstrapDialog.closeAll();
			});

		</script>


		<?php
	}
	elseif($page=='scanGCForCustomerReleasing')
	{
		// if(isset($_SESSION['scanGCForTransferReceiving']))
		// 	var_dump($_SESSION['scanGCForTransferReceiving'])
		?>
			<div class="row">
				<div class="col-xs-12 form-horizontal">
					<form method="post" action="../ajax.php?action=scanGCForCustomerReleasing" id="scanGCForCustomerReleasing">
						<div class="form-group inputGcbarcode">
							<div class="col-xs-12">
								<input data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': ''" class="form-control input-lg input-validation" id="gcbarcode" name="gcbarcode" autocomplete="off" maxlength="13" />
							</div>
						</div>
						<div class="form-group" style="display:none;">
							<div class="col-xs-12">
								<input data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': ''" class="form-control input-lg input-validation" id="gcbarcodexx" name="gcbarcodexxxx" autocomplete="off" maxlength="13" />
							</div>
						</div>
					</form>
					<div class="response-validate">
					</div>
				</div>
			</div>
			<script type="text/javascript">
				$('#gcbarcode').inputmask();
				$('#gcbarcode').focus();
			</script>
		<?php
	}
	elseif ($page=='scanGCRangeForReleasingInstitution') 
	{
		?>
	        <div class="row">
	            <div class="col-xs-12">
	                <form method="post" class="form-horizontal" action="../ajax.php?action=scanGCRangeForCustomerReleasing" id="scanrStoreGCRangeInstitution">
	                    <input type="hidden" name="flag" value="1">
	                    <input type="hidden" name="dens" value="">
	                    <input type="hidden" name="denoms" value="">


	                    <div class="rangeWrapper">
	                        <div class="form-group">
	                            <label class="col-xs-6 control-label cnt-right">Barcode Start</label>
	                            <label class="col-xs-6 control-label cnt-right">Barcode End</label>
	                        </div>
	                        <div class="form-group inputGcbarcode">
	            <!--                <div class="col-xs-12">
	                                <input data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': ''" class="form-control input-lg input-validation" id="gcbarcode" name="gcbarcode" autocomplete="off" maxlength="13" />
	                            </div> -->
	                            <div class="col-xs-6">
	                                <input type="text" name="gcStart" class="form form-control validateGCstart" maxlength="13" autocomplete="off"> 
	                            </div>
	                            <div class="col-xs-6">
	                                <input type="text" name="gcEnd" class="form form-control validateGCend" maxlength="13" disabled autocomplete="off">
	                            </div>
	                        </div>
	                    </div>
	                </form>
	                <div class="responserange">
	                </div>
	            </div>
	        </div>

	        <script type="text/javascript">
	            $('.validateGCstart').focus();
	        </script>

		<?php
	}
	elseif ($page=='eod') 
	{
		institutioneod($link);
	}
	elseif ($page=='institution-gc-sales') 
	{
		institutionGCSales($link);
	}
	elseif ($page=='displayInstitutionTransaction') 
	{
		if(!isset($_GET['trid']) && $_GET['trid']!='')
		{
			exit();
		}

		$trid = $_GET['trid'];

		//check if transaction exist
		if(numRows($link,'institut_transactions','institutr_id',$trid)==0)
		{
			exit();
		}
		displayInstitutionTransaction($link,$trid);
	}
	elseif ($page=='gctransfer') 
	{	
		$active = $_GET['active'];
		_gcTransfer($link,$todays_date,$active);
	}
	elseif ($page=='gctransfer-pending') 
	{
        if(!isset($_GET['reqid']) || isset($_GET['reqid'])=='')
        {
        	echo 'Page not found.';
            exit();  
        }		

         $reqid = (int)$_GET['reqid'];

        if(numRowsWhereTwo($link,'transfer_request','tr_reqid','tr_reqid','t_reqstoreby',$reqid,$_SESSION['gc_store'])==0)
        {
        	echo 'Page not found.';
            exit();          	
        }

        _updateGCTransfer($link,$reqid,$todays_date);

	}
	elseif ($page=='servedGCTransfer') 
	{
        if(!isset($_GET['reqid']) || isset($_GET['reqid'])=='')
        {
        	echo 'Page not found.';
            exit();  
        }

        $reqid = (int)$_GET['reqid'];

        if(numRowsWhereTwo($link,'transfer_request','tr_reqid','tr_reqid','t_reqstoreto',$reqid,$_SESSION['gc_store'])==0)
        {
        	echo 'Page not found.';
            exit();          	
        }

		_servedGCTransfer($link,$reqid,$todays_date);
	}
	elseif ($page=='servedGCTransferView') 
	{
        if(!isset($_GET['reqid']) || isset($_GET['reqid'])=='')
        {
        	echo 'Page not found.';
            exit();  
        }

        $reqid = (int)$_GET['reqid'];

        if(numRowsWhereThree($link,'transfer_request','tr_reqid','tr_reqid','t_reqstoreto','t_reqstatus',$reqid,$_SESSION['gc_store'],'closed')==0)
        {
        	echo 'Page not found.';
        	exit();
        }

        _servedGCTransferView($link,$reqid,$todays_date);

  //       if(numRowsWhereTwo($link,'transfer_request','tr_reqid','tr_reqid','t_reqstoreto',$reqid,$_SESSION['gc_store'])==0)
  //       {
  //       	echo 'Page not found.';
  //           exit();          	
  //       }

		// _servedGCTransfer($link,$reqid,$todays_date);
	}
	elseif ($page=='transfereceving') 
	{

        if(!isset($_GET['reqid']) || isset($_GET['reqid'])=='')
        {
        	echo 'Page not found.';
            exit();  
        }

		$reqid = (int)$_GET['reqid'];

        if(numRowsWhereTwo($link,'transfer_request_served','tr_servedid','tr_servedid','tr_serve_store',$reqid,$_SESSION['gc_store'])==0)
        {
        	echo 'Page not found.';
            exit();          	
        }

        _receivedTransfer($link,$reqid,$todays_date);

	}
	elseif ($page=='transferview')
	{
        if(!isset($_GET['reqid']) || isset($_GET['reqid'])=='')
        {
        	echo 'Page not found.';
            exit();  
        }

        $reqid = (int)$_GET['reqid'];

        if(numRowsWhereTwo($link,'transfer_request_served','tr_servedid','tr_servedid','tr_serve_store',$reqid,$_SESSION['gc_store'])==0)
        {
        	echo 'Page not found.';
            exit();          	
        }

        _receivedTransferIN($link,$reqid,$todays_date);
	}
	elseif ($page=='gclost') 
	{
		_gcLost($link,$todays_date);
	}
	elseif ($page=='pending-production-request-list') 
	{
		_pendingProductionRequestList($link);
	}
	elseif($page=='pending-production-request')
	{
        if(!isset($_GET['reqid']) || isset($_GET['reqid'])=='')
        {
        	echo 'Page not found.';
            exit();  
        }

		$reqid = (int)$_GET['reqid'];

		_pendingProductionRequest($link,$reqid,$todays_date);
	}
	elseif ($page=='approved-production-request-list') 
	{
		_approvedProductionRequestList($link);
	}
	elseif ($page=='cancelled-production-request-list') 
	{
		_cancelledProductionRequestList($link);
	}
	elseif ($page=='treasuryAudit') {
		_treasuryAudit();
	}
	elseif ($page=='refundInstitutionGC') {
		_refundInstitutionGC($link,$todays_date);
	}
	elseif ($page=='barcodechecker')
	{
		_barcodechecker($link,$todays_date);
	}
	elseif ($page=='scanReleasedStoreGCByRange')
	{
        if(!isset($_GET['storeid']) || isset($_GET['storeid'])=='')
        {
        	echo 'Page not found.';
            exit();  
        }		

        if(!isset($_GET['relid']) || isset($_GET['relid'])=='')
        {
        	echo 'Page not found.';
            exit();  
        }

        if(!isset($_GET['reqid']) || isset($_GET['reqid'])=='')
        {
        	echo 'Page not found.';
            exit();  
        }

        $relid = $_GET['relid'];
        $reqid = $_GET['reqid'];
        $storeid = $_GET['storeid'];
		_scanReleasedStoreGCByRange($link,$storeid,$relid,$reqid);
	}
	elseif ($page=='scanPromoGCByRangeForReleasing') 
	{

        if(!isset($_GET['relnum']) || isset($_GET['relnum'])=='')
        {
        	echo 'Page not found.';
            exit();  
        }		

        if(!isset($_GET['trid']) || isset($_GET['trid'])=='')
        {
        	echo 'Page not found.';
            exit();  
        }


        $relnum = $_GET['relnum'];
        $trid = $_GET['trid'];

		_scanPromoGCByRangeForReleasing($link,$relnum,$trid);
	}
	elseif ($page=='verifiedgcreport') 
	{
		_verifiedgcreport();
	}
	elseif ($page=='eodlist') 
	{
		_eodlist($link);
	}
	else 
	{
		//last
		echo 'Something went wrong.';
	}	
}

function _eodlist($link)
{
    $where = "1";
    $select = "	institut_eod.ieod_id,
	    institut_eod.ieod_num,
	    institut_eod.ieod_date,
	    CONCAT(users.firstname,' ',users.lastname) as eodby";
    $join = "INNER JOIN
			users
		ON
			users.user_id = institut_eod.ieod_by";
    $limit ='ORDER BY institut_eod.ieod_date DESC';
    $data = getAllData($link,'institut_eod',$select,$where,$join,$limit); 

    //var_dump($data);

	?>
		<div class="row form-container">
	    	<div class="col-md-12">
	            <div class="panel with-nav-tabs panel-info">
	                <div class="panel-heading">
	                    <ul class="nav nav-tabs">
	                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">EOD List</a></li>
	                    </ul>	                    
	                </div>
	                <div class="panel-body">
	                    <div class="tab-content">
	                        <div class="tab-pane fade in active" id="tab1default">
	                        	<div class="row"> 
	                        		<div class="col-sm-8">
	                        			<table class="table" id="tlist">
	                        				<thead>
	                        					<tr>
	                        						<th>Date</th>
	                        						<th>EOD Number</th>
	                        						<th>EOD By</th>
	                        						<th>Action</th>
	                        					</tr>
	                        				</thead>
	                        				<tbody>
	                        					<?php foreach ($data as $d): ?>
	                        						<tr>
	                        							<td><?php echo _dateFormat($d->ieod_date); ?></td>
	                        							<td><?php echo $d->ieod_num; ?></td>
	                        							<td><?php echo ucwords($d->eodby); ?></td>
	                        							<td><i class="fa fa-fa fa-eye faeye" title="View" data-trid="1" id=""></i><i class="fa fa-fa fa-print faeye" data-eodid="<?php echo $d->ieod_id;?>" title="View" data-trid="1" id="reprinteod"></td>
	                        						</tr>
	                        					<?php endforeach; ?>
	                        				</tbody>
	                        			</table>
	                        		</div>
	                        	</div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
		</div>
		<script type="text/javascript">
			$.extend( $.fn.dataTableExt.oStdClasses, {	  
			    "sLengthSelect": "selectsup"
			});

		    $('#tlist').dataTable({
		        "pagingType": "full_numbers",
		        "ordering": false,
		        "processing": true
		    });

		    $('table#tlist').on('click','tbody tr td i#reprinteod',function(){
		    	var id = $(this).attr('data-eodid');
		    	window.location = 'index.php?gceod='+id;
		    });
		</script>		

	<?php 
}

function _verifiedgcreport()
{
	?>
		<div class="row form-container">
	    	<div class="col-md-12">
	            <div class="panel with-nav-tabs panel-info">
	                <div class="panel-heading">
	                    <ul class="nav nav-tabs">
	                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Generate Verified GC Report</a></li>
	                    </ul>	                    
	                </div>
	                <div class="panel-body">
	                    <div class="tab-content">
	                        <div class="tab-pane fade in active" id="tab1default">
	                        	<div class="row"> 
	                        		<div class="col-sm-4">
		                        		<div class="form-group">
			                                <label>Date range:</label>
			                                <div class="input-group">
			                                    <div class="input-group-addon">
			                                        <i class="fa fa-calendar"></i>
			                                    </div>
			                                    <input type="text" class="form-control pull-right" name="querytrdate" id="querytrdate">
			                                </div>
			                                <!-- /.input group -->
			                            </div>
			                            <div class="form-group">
			                            	<div class="col-sm-12">
			                            		<button type="button" id="verbtn" class="btn btn-default btn-block"><i class="fa fa-list" aria-hidden="true"></i> Submit</button>
			                            	</div>
			                            </div>
			                            <div class="response">
			                            </div>
	                        		</div>
	                        	</div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
		</div>
		<script type="text/javascript">
			$('#querytrdate').daterangepicker();

			$('#verbtn').click(function(){
				var range = $('#querytrdate').val();
				if(range.trim()=="")
				{
					$('.response').html('<div class="alert alert-danger">Please select date.</div>');
					return false;
				}

				location.href="verifiedgcreport.php?daterange="+range;
			});

		</script>

	<?php
}

function _scanPromoGCByRangeForReleasing($link,$relnum,$trid)
{
	?>
		<div class="row">
			<div class="col-xs-12">
				<form method="post" class="form-horizontal" action="../ajax.php?action=scanreleasePromoGCByRange" id="scanPromoGCRange">
					<input type="hidden" name="relnum" value="<?php echo $relnum; ?>">
					<input type="hidden" name="trid" value="<?php echo $trid; ?>">
					<input type="hidden" name="flag" value="1">
					<input type="hidden" name="dens" value="">

					<div class="rangeWrapper">
						<div class="form-group">
							<label class="col-xs-6 control-label cnt-right">Barcode Start</label>
							<label class="col-xs-6 control-label cnt-right">Barcode End</label>
						</div>
						<div class="form-group inputGcbarcode">
			<!-- 				<div class="col-xs-12">
								<input data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': ''" class="form-control input-lg input-validation" id="gcbarcode" name="gcbarcode" autocomplete="off" maxlength="13" />
							</div> -->
							<div class="col-xs-6">
								<input type="text" name="gcStart" class="form form-control validateGCstart" maxlength="13" autocomplete="off"> 
							</div>
							<div class="col-xs-6">
								<input type="text" name="gcEnd" class="form form-control validateGCend" maxlength="13" disabled autocomplete="off">
							</div>
						</div>
					</div>
				</form>
				<div class="responserange">
				</div>
			</div>
		</div>

		<script type="text/javascript">
			$('.validateGCstart').focus();
		</script>

	<?php
}

function _scanReleasedStoreGCByRange($link,$storeid,$relid,$reqid)
{
	?>
		<div class="row">
			<div class="col-xs-12">
				<form method="post" class="form-horizontal" action="../ajax.php?action=scanreleaseStoreGCByRange" id="scanrStoreGCRange">
					<input type="hidden" name="relid" value="<?php $relid; ?>">
					<input type="hidden" name="reqid" value="<?php $reqid; ?>">
					<input type="hidden" name="storeid" value="<?php echo $storeid; ?>">
					<input type="hidden" name="flag" value="1">
					<input type="hidden" name="dens" value="">

					<div class="rangeWrapper">
						<div class="form-group">
							<label class="col-xs-6 control-label cnt-right">Barcode Start</label>
							<label class="col-xs-6 control-label cnt-right">Barcode End</label>
						</div>
						<div class="form-group inputGcbarcode">
			<!-- 				<div class="col-xs-12">
								<input data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': ''" class="form-control input-lg input-validation" id="gcbarcode" name="gcbarcode" autocomplete="off" maxlength="13" />
							</div> -->
							<div class="col-xs-6">
								<input type="text" name="gcStart" class="form form-control validateGCstart" maxlength="13" autocomplete="off"> 
							</div>
							<div class="col-xs-6">
								<input type="text" name="gcEnd" class="form form-control validateGCend" maxlength="13" disabled autocomplete="off">
							</div>
						</div>
					</div>
				</form>
				<div class="responserange">
				</div>
			</div>
		</div>

		<script type="text/javascript">
			$('.validateGCstart').focus();
		</script>

	<?php
}


function _barcodechecker($link,$todays_date)
{
	?>
		<div class="row form-container">
	    	<div class="col-md-12">
	            <div class="panel with-nav-tabs panel-info">
	                <div class="panel-heading">
	                    <ul class="nav nav-tabs">
	                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Barcode Checker</a></li>
	                    </ul>	                    
	                </div>
	                <div class="panel-body">
	                    <div class="tab-content">
	                        <div class="tab-pane fade in active" id="tab1default">
	                        	<div class="row form-container"> 
	                        		<div class="form-group">
			                        	<div class="row">
											<form action="../ajax.php?action=barcodechecker" method="POST" id="barcodecheckerfrm" enctype="multipart/form-data">                  
												<div class="col-sm-12">
		                              				<div class="col-sm-4">
						                                <div class="form-group">
															<label class="nobot">Date Scanned</label> 
															<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo _dateFormat($todays_date); ?>">                   
						                                </div>
						                                <div class="form-group">
															<label class="nobot">GC Barcode #</label> 
															<input type="text" class="form form-control inptxt input-sm bot-6" id="barcode" name="barcode" maxlength="13" value="" autofocus="on" autocomplete="off">                   
						                                </div>

						                                <div class="form-group">
															<label class="nobot">Scanned By</label> 
															<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>">                   
						                                </div>
						                                <div class="response">
						                                </div>
														<div class="form-group">
															<div class="col-sm-offset-5 col-sm-7">
																<button type="submit" class="btn btn-block btn-primary" id="tresCustomerbtn"> <span class="glyphicon glyphicon-share" aria-hidden="true"></span> Submit</button>
															</div>
														</div>
		                                			</div>
		                                			<div class="col-sm-8 divcheckedbarcode">
		                                				<table class="table" class="checkedbarcode">
		                                					<thead>
		                                						<tr>
		                                							<th>GC Barcode #</th>
		                                							<th>Denomination</th>
		                                							<th>Date Scanned</th>
		                                							<th>Scanned By</th>
		                                						</tr>		                  
		                                					</thead>
		                                					<tbody class="_checkedbarcode">

		                                					</tbody>
		                                				</table>
		                                				<div class="row">
		                                					<div class="col-sm-12">
		                                						<div class="span pull-right">
		                                							<?php 
																		$sqlreg = "SELECT 
																			Count(barcode_checker.bcheck_barcode) as cnt
																		FROM 
																			barcode_checker
																		INNER JOIN
																			gc
																		ON
																			gc.barcode_no = barcode_checker.bcheck_barcode";

																		$sqlspec = "SELECT 
																			    Count(barcode_checker.bcheck_barcode) as cnt
																			FROM 
																			    barcode_checker
																			INNER JOIN
																			    special_external_gcrequest_emp_assign
																			ON
																			    special_external_gcrequest_emp_assign.spexgcemp_barcode = barcode_checker.bcheck_barcode
																		";

																		$sqlall = "SELECT 
																			    Count(barcode_checker.bcheck_barcode) as cnt
																			FROM 
																			    barcode_checker";

																		$sqltod = "SELECT 
																			    Count(barcode_checker.bcheck_barcode) as cnt
																			FROM 
																			    barcode_checker
																			WHERE 
																				DATE(barcode_checker.bcheck_date) = CURDATE()";
		                                							?> 	                                							

		                                							Regular GC Scanned Count: <span class="badge badge-primary" id="totreg"><?php echo countsql($link,$sqlreg); ?></span>
		                                						</div>
		                                					</div>
		                                				</div>
		                                				<div class="row">
		                                					<div class="col-sm-12">
		                                						<div class="span pull-right">
		                                							Special External GC Scanned Count: <span class="badge badge-primary" id="totspec"><?php echo countsql($link,$sqlspec); ?></span>
		                                						</div>
		                                					</div>
		                                				</div>
		                                				<div class="row">
		                                					<div class="col-sm-12">
		                                						<div class="span pull-right">
		                                							Total GC Scanned: <span class="badge badge-primary" id="totall"><?php echo countsql($link,$sqlall); ?></span>
		                                						</div>
		                                					</div>
		                                				</div>
		                                				<div class="row">
		                                					<div class="col-sm-12">
		                                						<div class="span pull-right">
		                                							Number of GC Scanned Today: <span class="badge badge-primary" id="tottod"><?php echo countsql($link,$sqltod); ?></span>
		                                						</div>
		                                					</div>
		                                				</div>		                           
		                                			</div>													
												</div>
		                                	</form>
			                        	</div>
	                        		</div> 
	                        	</div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
		</div>
		<script type="text/javascript">
			//alert("x");
			$('tbody._checkedbarcode').load('../ajax.php?action=barcodeCheckLoad');  

		   	$('.form-container').on('submit','form#barcodecheckerfrm',function(event){
				event.preventDefault();
				$('.response').html('');			

				var formURL = $(this).attr('action'), formData = new FormData($(this)[0]);

				barcode = $('#barcode').val();

				if(barcode.trim()=='')
				{
					$('#barcode').val("").focus();
					$('.response').html('<div class="alert alert-danger alert-dismissable">Please input GC barcode number.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
					return false;
				}

				$.ajax({
					url:formURL,
					data:{barcode:barcode},
					type:'POST',
					success:function(data){
						console.log(data);
						var data = JSON.parse(data);
						if(data['st'])
						{


							var totreg = $('span#totreg').text();
							var totspec = $('span#totspec').text();
							var totall = $('span#totall').text();
							var tottod = $('span#tottod').text();

							totreg = isNaN(totreg) ? 0 : totreg;
							totspec = isNaN(totspec) ? 0 : totspec;
							totall = isNaN(totall) ? 0 : totall;
							tottod = isNaN(tottod) ? 0 : tottod;

							if(data['gctype']=='regular')
							{
								totreg++;
								$('span#totreg').text(totreg);
							}
							else if(data['gctype']=='special external')
							{
								totspec++;
								$('span#totspec').text(totspec);
							}

							totall++;
							$('span#totall').text(totall);
							tottod++;
							$('span#tottod').text(tottod);

							$('.response').html('<div class="alert alert-success success1 bot-margin">'+data['msg']+'</div>');	
							$('tbody._checkedbarcode').load('../ajax.php?action=barcodeCheckLoad');  
						}
						else 
						{
							$('.response').html('<div class="alert alert-danger alert-dismissable">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');								
						}
					}
				}); 

				$('#barcode').val("").focus();

				return false;
			});


		</script>


	<?php
}


function _refundInstitutionGC($link,$todays_date)
{

	//dri
		if(isset($_SESSION['scanForRefund']))
			unset($_SESSION['scanForRefund']);
		$trnumber = getLastnumberOneWhere1($link,'institut_transactions','institutr_trnum','institutr_trtype','refund','institutr_trnum');

	?>
		<div class="row form-container">
	    	<div class="col-md-12">
	            <div class="panel with-nav-tabs panel-info">
	                <div class="panel-heading">
	                    <ul class="nav nav-tabs">
	                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Institution GC Refund</a></li>
	                    </ul>	                    
	                </div>
	                <div class="panel-body">
	                    <div class="tab-content">
	                        <div class="tab-pane fade in active" id="tab1default">
	                        	<div class="row form-container"> 
	                        		<div class="form-group">
			                        	<div class="row">
											<form action="../ajax.php?action=refundInstGC" method="POST" id="refundInstGC" enctype="multipart/form-data">                  
		                          				<div class="col-sm-12">
		                              				<div class="col-sm-3">
		                                				<div class="form-group">
		                                  					<label class="nobot">GC Refund #</label>   
		                                  					<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo sprintf("%03d",1); ?>" name="reqnum" id="reqnum">  
		                                				</div>
						                                <div class="form-group">
															<label class="nobot">Date Refund</label> 
															<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo _dateFormat($todays_date); ?>">                   
						                                </div>
						                                <div class="form-group">
															<label class="nobot"><span class="requiredf">*</span>Refund Received By:</label> 
															<input type="text" class="form form-control inptxt input-sm bot-6" value="" id="recby" name="recby" required autocomplete="off">                   
						                                </div>
						                                <div class="form-group">
															<label class="nobot">Remarks:</label> 
															<input type="text" class="form form-control inptxt input-sm bot-6" value="" id="remarks" name="remarks" autocomplete="off">                   
						                                </div>
						                                <div class="form-group">
															<label class="nobot">Upload Document</label> 
															<input id="input-file" class="file" type="file" name="docs[]" multiple>
						                                </div>
		                                			</div>

		                                			<div class="col-sm-4">
														<div class="form-group">
															<label class="nobot"><span class="requiredf">*</span>Customer</label>
															<input type="text" class="form form-control inptxt" readonly="readonly" name="cusname" id="cusname">
															<input type="hidden" name="cusid" value="" id="cusid">
														</div>       
														<div class="form-group">
															<button type="button" class="btn btn-info fordialog" onclick="lookupCustomerInstitGC();"><i class="fa fa-search-plus" aria-hidden="true"></i>
															Customer Lookup</button>
														</div>
														<div class="form-group">
															<label class="nobot"><span class="requiredf">*</span>Total Denomination</label> 
															<input type="text" class="form form-control inptxt" readonly="readonly" name="denocr" id="denocr" value="0.00" style="text-align:right;">
														</div>

														<div class="paymenttypediv" style="display:none">
															<div class="cashpayment">												
																<div class="form-group">
																	<label class="nobot"><span class="requiredf">*</span>Amount Received</label>
																	<input type="text" class="form form-control inptxt input-sm bot-6" name="amountrec" id="amountrec" autocomplete="off" data-inputmask="'alias': 'numeric','digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" required>
																</div>	
																<div class="form-group">
																	<label class="nobot"><span class="requiredf">*</span>Change</label>
																	<input type="text" class="form form-control inptxt input-sm bot-6" name="paymentchange" id="paymentchange" autocomplete="off" style="text-align:right" value="0.00" readonly="readonly">
																</div>															
															</div>
															<div class="checkpayment">
																<div class="form-group">
																	<label class="nobot"><span class="requiredf">*</span>Bank Name</label>
																	<input type="text" class="form form-control inptxt input-sm bot-6" name="bankname" id="bankname" autocomplete="off">
																</div>
																<div class="form-group">
																	<label class="nobot"><span class="requiredf">*</span>Bank Account Number</label>
																	<input type="text" class="form form-control inptxt input-sm bot-6 " name="baccountnum" id="baccountnum" autocomplete="off">
																</div>
																<div class="form-group">
																	<label class="nobot"><span class="requiredf">*</span>Check Number</label>
																	<input type="text" class="form form-control inptxt input-sm bot-6" name="cnumber" id="cnumber" autocomplete="off">
																</div>
															</div>
														</div>
		                                			</div>

		                                			<div class="col-sm-5">
														<div class="form-group">
															<button type="button" class="btn btn-info fordialog pull-right" onclick="scanGCForRefund();"><i class="fa fa-plus" aria-hidden="true"></i>
															 Scan GC</button>
														</div>   
		                                				<table class="table" id="scanGCForCustomerReleasing">
		                                					<thead>
		                                						<tr>
		                                							<th>Denomination</th>
		                                							<th>Barcode</th>
		                                							<th>Remove</th>
		                                						</tr>
		                                					</thead>
		                                				</table>
		                                				<div class="response">

		                                				</div>
														<div class="form-group">
															<div class="col-sm-offset-5 col-sm-7">
																<button type="submit" class="btn btn-block btn-primary" id="tresCustomerbtn"> <span class="glyphicon glyphicon-share" aria-hidden="true"></span> Submit</button>
															</div>
														</div>
		                                			</div>

		                                		</div>

		                                	</form>

			                        	</div>
	                        		</div> 
	                        	</div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
		</div>
		<script type="text/javascript">

			$('#denocr,#amountrec').inputmask();
		    $('input#input-file').fileinput({
		      'allowedFileExtensions' : ['jpg','png','jpeg']
		    });

	        $('#scanGCForCustomerReleasing').DataTable( {
	            "order": [[ 0, "desc" ]]
	        } );

			function scanGCForRefund()
			{
			    BootstrapDialog.show({
			        title: 'Scan GC',
			        cssClass: 'customer-internal',
			        closable: true,
			        closeByBackdrop: false,
			        closeByKeyboard: true,
			        message: function(dialog) {
			            var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
			            var pageToLoad = dialog.getData('pageToLoad');
			            setTimeout(function(){
			            $message.load(pageToLoad);
			            },1000);
			            return $message;
			        },
			        data: {
			            'pageToLoad': '../templates/regulargc.php?page=scanGCForCustomerReleasing'
			        },
			        onshown: function(dialogRef){

			        },
			        onhidden:function(dialogRef){
			        },        
			        buttons: [{
			            icon: 'glyphicon glyphicon-ok-sign',
			            label: 'Submit',
			            cssClass: 'btn-primary',
			            hotkey: 13,
			            action:function(dialogItself){
			            	$('.response-validate').html('');
			            	var t = $('#scanGCForCustomerReleasing').DataTable();
				    		var counter = 1;
			             	var barcode = $('#gcbarcode').val(), formUrl = '../ajax.php?action=scanInsGCForRefund';
			             	var cusid = $('#cusid').val();			             	

							if(barcode==undefined)
							{
								return false;
							}

							if(barcode.trim()=='')
							{
			    				$('.response-validate').html('<div class="alert alert-danger alert-dismissable">Please input GC barcode number.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');		
			    				$('#gcbarcode').select();
			    				return false;
							}

							if(cusid.trim()=='')
							{
			    				$('.response-validate').html('<div class="alert alert-danger alert-dismissable">Please select customer first.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');		
			    				$('#gcbarcode').select();
			    				return false;								
							}

							$.ajax({
								url:formUrl,
								data:{barcode:barcode,cusid,cusid},
								type:'POST',
								success:function(data){
									console.log(data);
									var data = JSON.parse(data);
									if(data['st'])
									{
						    			var counter = 1;
								        t.row.add( [		        	
								            data['denomination'],
								            data['barcode'],
								            '<input type="hidden" value="'+data['key']+'" class="denoms"><i class="fa fa-times remove-employee" aria-hidden="true"></i>'
								        ] ).draw( false );
								 		
								        counter++;

								        var total = parseFloat(data['total']).toFixed(2);

								        $('#denocr').val(total);

								        var amountrec = $('#amountrec').val();

								        calculateChange(amountrec);

										$('.response-validate').html('<div class="alert alert-success alert-dismissable">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');	
										$('#gcbarcode').select();
									}
									else 
									{
										$('.response-validate').html('<div class="alert alert-danger alert-dismissable">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');	
										$('#gcbarcode').select();								
									}
								}
							});       	
			        	}
			        }, {
			        	icon: 'glyphicon glyphicon-remove-sign',
			            label: 'Close',
			            action: function(dialogItself){
			                dialogItself.close();
			            }
			        }]
			    });
			}
		</script>	
	<?php
}

function _treasuryAudit()
{
	?>
		<div class="row form-container">
	    	<div class="col-md-12">
	            <div class="panel with-nav-tabs panel-info">
	                <div class="panel-heading">
	                    <ul class="nav nav-tabs">
	                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">GC Monthly Audit (Treasury)</a></li>
	                    </ul>	                    
	                </div>
	                <div class="panel-body">
	                    <div class="tab-content">
	                        <div class="tab-pane fade in active" id="tab1default">
	                        	<div class="row form-container"> 
									<div class="col-sm-4">

									</div> 
	                        	</div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
		</div>
		<script type="text/javascript">

		</script>

	<?php
}

function _receivedTransfer($link,$servedID,$todays_date)
{

	if(isset($_SESSION['scanGCForTransferReceiving']))
	unset($_SESSION['scanGCForTransferReceiving']);

	$table='transfer_request_served';
	$where = "transfer_request_served.tr_servedid = '".$servedID."'
		AND
			transfer_request_served.tr_serveRecStatus='pending'";
	$select = "transfer_request_served.tr_servedid,
		transfer_request_served.tr_serverelnum,
		transfer_request_served.tr_serveremarks,
		transfer_request_served.tr_serveCheckedBy,
		transfer_request_served.tr_serveReceivedBy,
		transfer_request_served.tr_servedate,
		transfer_request_served.tr_serveStatus,
		transfer_request_served.tr_serveRecStatus,
		stores.store_name,
		CONCAT(users.firstname,' ',users.lastname) as servedby";
	$join = 'LEFT JOIN
			users
		ON
			users.user_id = transfer_request_served.tr_serveby
		LEFT JOIN
			transfer_request
		ON
			transfer_request.tr_reqid = transfer_request_served.tr_reqid
		LEFT JOIN
			stores
		ON
			stores.store_id = transfer_request.t_reqstoreto';
	$limit = '';

	$served = getSelectedData($link,$table,$select,$where,$join,$limit);

	$table = 'documents';
	$select = 'doc_fullpath';
	$where = "doc_trid='".$servedID."'
		AND
			doc_type='Served Transfer Request'";
	$join = '';
	$limit = '';

	$docs = getAllData($link,$table,$select,$where,$join,$limit);	

	$table='transfer_request_served_items';
	$select ="IFNULL(COUNT(denomination.denomination),0) as cnt,
		denomination.denomination,
		gc.denom_id";
	$where = "transfer_request_served_items.trs_served='".$servedID."'
		GROUP BY
			denomination.denomination";
	$join = 'INNER JOIN
			gc
		ON
			gc.barcode_no = transfer_request_served_items.trs_barcode
		INNER JOIN
			denomination
		ON
			denomination.denom_id = gc.denom_id';
	$limit = '';
	$denoms = getAllData($link,$table,$select,$where,$join,$limit);	

	?>
	<div class="row form-container">
    	<div class="col-md-12">
            <div class="panel with-nav-tabs panel-info">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">GC Transfer (IN)</a></li>
                    </ul>	                    
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1default">
                        	<div class="row form-container">   
                        		<form action="../ajax.php?action=serveTransferReceiving" method="POST" id="serveTransferReceiving" enctype="multipart/form-data">   
                        			<div class="col-sm-12">
                        				<div class="col-sm-4">
                        					<div class="header">Transfer Details</div>
											<div class="form-groupwrap">
				                                <div class="form-group">
													<label class="nobot">GC Transfer (Out)#</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly"value="<?php echo $served->tr_serverelnum; ?>">                          				
												</div>

				                                <div class="form-group">
													<label class="nobot">Date Released</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly"value="<?php echo _dateFormat($served->tr_servedate); ?>">                                 				
												</div>

				                                <div class="form-group">
													<label class="nobot">From Location</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly"value="<?php echo ucwords($served->store_name); ?>">                                 				
												</div>

				                                <div class="form-group">
													<label class="nobot">Releasing Type</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly"value="<?php echo ucwords($served->tr_serveStatus); ?>">                                 				
												</div>

				                                <div class="form-group">
													<label class="nobot">Remarks</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly"value="<?php echo $served->tr_serveremarks; ?>">                                 				
												</div>

				                                <div class="form-group">
													<label class="nobot">Checked By</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly"value="<?php echo ucwords($served->tr_serveCheckedBy); ?>">                                 				
												</div>

				                                <div class="form-group">
													<label class="nobot">Received By</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly"value="<?php echo ucwords($served->tr_serveReceivedBy); ?>">                                 				
												</div>

				                                <div class="form-group">
													<label class="nobot">Released By</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly"value="<?php echo ucwords($served->servedby); ?>">                       				
												</div>

				                                <?php if(count($docs) > 0): ?>
													<div class="form-group">
	                                                	<label class="nobot">Document(s)</label>

	                                                    <ul id="lightgallery" class="list-unstyled row" style="margin-bottom:0px;">
	                                                        <?php foreach ($docs as $d): ?>
	                                                        <li class="col-xs-6 col-sm-4 col-md-4" data-src="../assets/images/<?php echo $d->doc_fullpath;?>">
	                                                            <a href="" class="thumbnail">
	                                                            <img class="img-responsive theight" style="height:50px;" src="../assets/images/<?php echo $d->doc_fullpath; ?>">
	                                                            </a>
	                                                        </li>
	                                                        <?php endforeach; ?>
	                                                    </ul>
	                                                </div>
				                                <?php endif; ?>


				                            </div>            
				                        </div>
		                        		<div class="col-sm-3">
			                            	<div class="form-group">
			                            		<input type="hidden" name="servedid" id="servedid" value="<?php echo $served->tr_servedid; ?>">
			                              		<label class="nobot">Receiving #</label>   
			                              		<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo getReceivingNumberTR($link,$_SESSION['gc_store'],'store transfer'); ?>" name="relnum" id="recnum">  
			                            	</div>          
			                                <div class="form-group">
												<label class="nobot">Date Received</label> 
												<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo _dateFormat($todays_date); ?>">                                 
			                                </div>

			                                <div class="form-group">
												<label class="nobot">Remarks</label> 
												<input type="text" class="form form-control inptxt input-sm bot-6" name="remarks" id="remarks" value="" autocomplete="off">                                 
			                                </div>

			                                <div class="form-group">
												<label class="nobot"><span class="requiredf">*</span>Checked By:</label> 
												<input type="text" class="form form-control inptxt input-sm bot-6" name="checkby" id="checkby" value="" autocomplete="off">                                 
			                                </div>

			                                <div class="form-group">
												<label class="nobot">Received By:</label> 
												<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>">                                 
			                                </div>	

			                                <div class="form-group">
			                                	<label class="nobot">Upload Document</label> 
			                                	<input id="input-file" class="file" type="file" name="docs[]" multiple>
			                                </div>	
	                            	                                    
		                        		</div> 
										<div class="col-sm-5">
											<div class="form-horizontal denomsbox">
												<div class="form-group">
													<label class="col-sm-4"><span class="requiredf">*</span>Denomination</label>
													<label class="col-sm-4"><span class="requiredf">*</span>Qty</label>	
													<label class="col-sm-4"><span class="requiredf">*</span>Scanned</label>			
												</div>
												<?php foreach ($denoms as $d): ?>
													<div class="form-group">
														<label class="col-sm-4">&#8369 <?php echo number_format($d->denomination,2); ?></label>
														<div class="col-sm-4">
															<input type="hidden" id="m<?php echo $d->denom_id; ?>" value="<?php echo $d->denomination; ?>"/>
															<input class="form form-control inptxt" id="num<?php echo $d->denom_id; ?>" value="<?php echo $d->cnt; ?>" readonly="readonly" name="denoms<?php echo $d->denom_id; ?>" autocomplete="off" style="text-align:right">
														</div>
														<div class="col-sm-4">
															<input type="hidden" id="m<?php echo $d->denom_id; ?>" value="<?php echo $d->denom_id; ?>"/>
															<input class="form form-control inptxt denfield" id="scangc<?php echo $d->denom_id; ?>" value="0" readonly="readonly" name="denoms<?php echo $d->denom_id; ?>" autocomplete="off" style="text-align:right">
														</div>
													</div>
												<?php endforeach; ?>
												<div class="form-group">
													<div class="col-sm-6">
														<button class="btn btn-block btn-primary" type="button" onclick="scanGCForTransfer()"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Scan GC</button>
													</div>
													<div class="col-sm-6">
														<button class="btn btn-block btn-primary" type="button" onclick="viewscannedgcsForTransferRec()"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> View Scanned GC</button>
													</div>
												</div>

											</div>
											<div class="labelinternaltot">
												<input type="hidden" name="totalAmt" id="totalAmt" value="0">                        
												<label>Total Amount: <span id="totalAmtLbl">0.00</span></label>
											</div>										

											<div class="response">
											</div>

											<div class="form-group">
												<div class="col-sm-offset-5 col-sm-7">
													<button type="submit" class="btn btn-block btn-primary" id="btn"> <span class="glyphicon glyphicon-share" aria-hidden="true"></span> Submit</button>
												</div>
											</div>
										</div>	
                        			</div>
                        		</form>

                        	</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
	<script type="text/javascript">
	    $('input#input-file').fileinput({
	      'allowedFileExtensions' : ['jpg','png','jpeg']
	    });

	   	$('.form-container').on('submit','form#serveTransferReceiving',function(event){
			event.preventDefault();
			$('.response').html('');			

			var formURL = $(this).attr('action'), formData = new FormData($(this)[0]);

			var hasDenom = false;

			$('.denfield').each(function(){
				if($(this).val()!=0)
				{
					hasDenom = true;
					return false;
				}
			});

			if($('#servedid').val().trim()=="")
			{
				$('.response').html('<div class="alert alert-danger" id="danger-x">Releasing number missing.</div>');
				return;
			}

			if($('#checkby').val().trim()=="")
			{
				$('.response').html('<div class="alert alert-danger" id="danger-x">Please fill in required fields.</div>');
				return;				
			}

			if(!hasDenom)
			{
				$('.response').html('<div class="alert alert-danger" id="danger-x">Please scan GC first.</div>');
				return;
			}

	        BootstrapDialog.show({
	        	title: 'Confirmation',
	            message: 'Are you sure you want to Recevied GC?',
	            closable: true,
	            closeByBackdrop: false,
	            closeByKeyboard: true,
	            onshow: function(dialog) {
	               	$("button#btn").prop("disabled",true);
	            },
	            onhidden: function(dialog){
	            	$("button#btn").prop("disabled",false);
	            },
	            buttons: [{
	                icon: 'glyphicon glyphicon-ok-sign',
	                label: 'Yes',
	                cssClass: 'btn-primary',
	                hotkey: 13,
	                action:function(dialogItself){	
	                	$buttons = this;
	                	$buttons.disable();                	

						$.ajax({
				    		url:formURL,
				    		type:'POST',
							data: formData,
							enctype: 'multipart/form-data',
						    async: true,
						    cache: false,
						    contentType: false,
						    processData: false,
							beforeSend:function(){
								
							},
							success:function(data)
							{
								console.log(data);
								var data = JSON.parse(data);
								if(data['st'])
								{
									dialogItself.close();
									var dialog = new BootstrapDialog({
						            message: function(dialogRef){
						            var $message = $('<div>Receiving Successfully Saved.</div>');			        
						                return $message;
						            },
						            closable: false
							        });
							        dialog.realize();
							        dialog.getModalHeader().hide();
							        dialog.getModalFooter().hide();
							        dialog.getModalBody().css('background-color', '#0088cc');
							        dialog.getModalBody().css('color', '#fff');
							        dialog.open();
							        setTimeout(function(){
				                    	dialog.close();
				               		}, 1500);
				               		setTimeout(function(){
				                    	window.location.href="index.php";
				               		}, 1700);	
								}
								else 
								{
									$('.response').html('<div class="alert alert-danger" id="danger-x">'+data['msg']+'</div>');
								}															
							}
						});

	                }
	            }, {
	            	icon: 'glyphicon glyphicon-remove-sign',
	                label: 'No',
	                action: function(dialogItself){
	                    dialogItself.close();
	                }
	            }]
	        });		

			return false;
		});

	    $('#lightgallery').lightGallery();

		function scanGCForTransfer()
		{
		    BootstrapDialog.show({
		        title: 'Scan GC',
		        cssClass: 'customer-internal',
		        closable: true,
		        closeByBackdrop: false,
		        closeByKeyboard: true,
		        message: function(dialog) {
		            var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
		            var pageToLoad = dialog.getData('pageToLoad');
		            setTimeout(function(){
		            $message.load(pageToLoad);
		            },1000);
		            return $message;
		        },
		        data: {
		            'pageToLoad': '../templates/regulargc.php?page=scanGCForCustomerReleasing'
		        },
		        onshown: function(dialogRef){

		        },
		        onhidden:function(dialogRef){
		        },        
		        buttons: [{
		            icon: 'glyphicon glyphicon-ok-sign',
		            label: 'Submit',
		            cssClass: 'btn-primary',
		            hotkey: 13,
		            action:function(dialogItself){
		            	$('.response-validate').html('');
		             	var barcode = $('#gcbarcode').val(), formUrl = '../ajax.php?action=receivedTransferRequest', servedid = $('input#servedid').val();
						if(barcode==undefined)
						{
							return false;
						}

						if(barcode.trim()=='')
						{
		    				$('.response-validate').html('<div class="alert alert-danger alert-dismissable">Please input GC barcode number.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');		
		    				$('#gcbarcode').select();
		    				return false;
						}

						$.ajax({
							url:formUrl,
							data:{barcode:barcode,servedid:servedid},
							type:'POST',
							success:function(data){
								console.log(data);
								var data = JSON.parse(data);
								if(data['st'])
								{

									var total = $('#totalAmt').val();
									total = parseFloat(total) + parseFloat(data['denomination']);
									$('#totalAmt').val(total);
									$('span#totalAmtLbl').text(total.toFixed(2));


									$('input#scangc'+data['denom']).val(data['scanned']);
									$('.response-validate').html('<div class="alert alert-success alert-dismissable">GC successfully scanned for Receiving.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');	
									$('#gcbarcode').select();
								}
								else 
								{
									$('.response-validate').html('<div class="alert alert-danger alert-dismissable">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');	
									$('#gcbarcode').select();								
								}
							}
						});       	
		        	}
		        }, {
		        	icon: 'glyphicon glyphicon-remove-sign',
		            label: 'Close',
		            action: function(dialogItself){
		                dialogItself.close();
		            }
		        }]
		    });
		}

	    function viewscannedgcsForTransferRec()
	    {
			BootstrapDialog.show({
		        title: 'Scanned GC',
		        cssClass: 'gc-barcode-modal',
		        closable: true,
		        closeByBackdrop: false,
		        closeByKeyboard: true,
		        message: function(dialog) {
		            var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
		            var pageToLoad = dialog.getData('pageToLoad');
		            setTimeout(function(){
		            $message.load(pageToLoad);
		            },1000);
		            return $message;
		        },
		        data: {
		            'pageToLoad': '../dialogs/scan_gcrel.php?action=scannedGCForTransferReceiving'
		        },
			    onshow: function(dialog) {
			    },
			    onshown: function(dialogRef){

			    },
			    buttons:[{
	            icon: 'glyphicon glyphicon-ok-sign',
	            label: 'Remove GC',
	            cssClass: 'btn-primary',
	            hotkey: 13,
	            action:function(dialogItself1){
	            	$('.response-checkbox').html('');
	            	var checked = document.querySelectorAll('input[type="checkbox"]:checked').length;
	            	if(checked == 0)
	            	{
						$('.response-checkbox').html('<div class="alert alert-danger alert-dismissable">Please check GC barcode checkbox.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');            		
	            		return false;
	            	}

			        BootstrapDialog.show({
			        	title: 'Confirmation',
			            message: 'Are you sure you want to removed selectd GC.',
			            closable: true,
			            closeByBackdrop: false,
			            closeByKeyboard: true,
			            onshow: function(dialog) {
			                // dialog.getButton('button-c').disable();										            
			            },
			            onhidden: function(dialog) {

			            },
			            buttons: [{
			                icon: 'glyphicon glyphicon-ok-sign',
			                label: 'Ok',
			                cssClass: 'btn-primary',
			                hotkey: 13,
			                action:function(dialogItself){
			                	$buttons = this;
			                	$buttons.disable();		                	
			                	var formData = $('form#scannedGCForm').serialize(), formUrl = '../ajax.php?action=removeScannedTransferGCRec';
			                	var servedid = $('#servedid').val();
			                	formData+="&servedid="+servedid;
			                	dialogItself.close();
	                        	$.ajax({
	                        		url:formUrl,
	                        		data:formData,
	                        		type:'POST',                          
	                        		success:function(data1)
	                        		{
	                        			console.log(data1);
	                        			var data1 = JSON.parse(data1) 

	                        			if(data1['st'])
	                        			{
											d = data1['rscanned'];
											for (var val in d) 
											{
											    var res = d[val].split("=");
											    //alert($("input#num"+res[0]).val());
											    $("input#scangc"+res[0]).val(res[1]);
										
											}

											$('#totalAmt').val(data1['total']);
											$('span#totalAmtLbl').text(parseFloat(data1['total']).toFixed(2));

											var dialog = new BootstrapDialog({
								            message: function(dialogRef){
								            var $message = $('<div>Selected GC successfully removed.</div>');			        
								                return $message;
								            },
								            closable:false
									        });
									        dialog.realize();
									        dialog.getModalHeader().hide();
									        dialog.getModalFooter().hide();
									        dialog.getModalBody().css('background-color', '#0088cc');
									        dialog.getModalBody().css('color', '#fff');
									        dialog.open();
									        setTimeout(function(){
						                    	dialog.close();
						                    	dialogItself1.close();
						               		}, 1500);
	                        			}
	                        			else
	                        			{
	                        				$('.response-checkbox').html('<div class="alert alert-danger alert-dismissable">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
	                        				$button.enable();						                                				
	                        			}
	                        		}
	                        	});									       

			                }
			            },{
			            	icon: 'glyphicon glyphicon-remove-sign',
			                label: 'Cancel',
			                action: function(dialogItself){
			                    dialogItself.close();
			                }
			            }]
				    });	 
	            }

        		},{
			    	icon: 'glyphicon glyphicon-remove-sign',
			        label: 'Close',
			        action: function(dialogItself){
			            dialogItself.close();
			        }
			    }]
			});
	    }
	</script>

	<?php

}

function _receivedTransferIN($link,$relid,$todays_date)
{

	$table='transfer_request_served';
	$where = "transfer_request_served.tr_servedid = '".$relid."'
		AND
			transfer_request_served.tr_serveRecStatus='received'";
	$select = "transfer_request_served.tr_servedid,
		transfer_request_served.tr_serverelnum,
		transfer_request_served.tr_serveremarks,
		transfer_request_served.tr_serveCheckedBy,
		transfer_request_served.tr_serveReceivedBy,
		transfer_request_served.tr_servedate,
		transfer_request_served.tr_serveStatus,
		transfer_request_served.tr_serveRecStatus,
		stores.store_name,
		CONCAT(users.firstname,' ',users.lastname) as servedby";
	$join = 'LEFT JOIN
			users
		ON
			users.user_id = transfer_request_served.tr_serveby
		LEFT JOIN
			transfer_request
		ON
			transfer_request.tr_reqid = transfer_request_served.tr_reqid
		LEFT JOIN
			stores
		ON
			stores.store_id = transfer_request.t_reqstoreto';
	$limit = '';

	$served = getSelectedData($link,$table,$select,$where,$join,$limit);

	if(count($served)==0)
	{
		echo 'Page not found.';
		exit();
	}

	$table = 'documents';
	$select = 'doc_fullpath';
	$where = "doc_trid='".$relid."'
		AND
			doc_type='Served Transfer Request'";
	$join = '';
	$limit = '';

	$docs = getAllData($link,$table,$select,$where,$join,$limit);	


	$table = 'documents';
	$select = 'doc_fullpath';
	$where = "doc_trid='".$relid."'
		AND
			doc_type='Served Transfer Request'";
	$join = '';
	$limit = '';

	$rec = getAllData($link,$table,$select,$where,$join,$limit);	


	?>
	<div class="row form-container">
    	<div class="col-md-12">
            <div class="panel with-nav-tabs panel-info">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">GC Transfer (IN)</a></li>
                    </ul>	                    
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1default">
                        	<div class="row form-container">   
                        		<form action="../ajax.php?action=serveTransferReceiving" method="POST" id="serveTransferReceiving" enctype="multipart/form-data">   
                        			<div class="col-sm-12">
                        				<div class="col-sm-4">
                        					<div class="header">Transfer Details</div>
											<div class="form-groupwrap">
				                                <div class="form-group">
													<label class="nobot">GC Transfer (Out)#</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly"value="<?php echo $served->tr_serverelnum; ?>">                          				
												</div>

				                                <div class="form-group">
													<label class="nobot">Date Released</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly"value="<?php echo _dateFormat($served->tr_servedate); ?>">                                 				
												</div>

				                                <div class="form-group">
													<label class="nobot">From Location</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly"value="<?php echo ucwords($served->store_name); ?>">                                 				
												</div>

				                                <div class="form-group">
													<label class="nobot">Releasing Type</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly"value="<?php echo ucwords($served->tr_serveStatus); ?>">                                 				
												</div>

				                                <div class="form-group">
													<label class="nobot">Remarks</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly"value="<?php echo $served->tr_serveremarks; ?>">                                 				
												</div>

				                                <div class="form-group">
													<label class="nobot">Checked By</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly"value="<?php echo ucwords($served->tr_serveCheckedBy); ?>">                                 				
												</div>

				                                <div class="form-group">
													<label class="nobot">Received By</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly"value="<?php echo ucwords($served->tr_serveReceivedBy); ?>">                                 				
												</div>

				                                <div class="form-group">
													<label class="nobot">Released By</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly"value="<?php echo ucwords($served->servedby); ?>">                       				
												</div>

				                                <?php if(count($docs) > 0): ?>
													<div class="form-group">
													
	                                                	<label class="nobot">
	                                                		<?php if(count($docs) > 1): ?>
	                                                			Documents
	                                                		<?php else: ?>
	                                                			Document
	                                                		<?php endif; ?>	                                         
	                                                	</label>

	                                                    <ul id="lightgallery" class="list-unstyled row" style="margin-bottom:0px;">
	                                                        <?php foreach ($docs as $d): ?>
	                                                        <li class="col-xs-6 col-sm-4 col-md-4" data-src="../assets/images/<?php echo $d->doc_fullpath;?>">
	                                                            <a href="" class="thumbnail">
	                                                            <img class="img-responsive theight" style="height:50px;" src="../assets/images/<?php echo $d->doc_fullpath; ?>">
	                                                            </a>
	                                                        </li>
	                                                        <?php endforeach; ?>
	                                                    </ul>
	                                                </div>
				                                <?php endif; ?>
				                            </div>            
				                        </div>
		                        		<div class="col-sm-4">
		                        			<?php 
		                        				
												$table='store_received';
												$select = "store_received.srec_recid,
													store_received.srec_id,
													store_received.srec_at,
													store_received.srec_checkedby,
													transfer_request_served.tr_receiveremarks,
													CONCAT(users.firstname,' ',users.lastname) as recby";
												$where = "store_received.srec_receivingtype='store transfer'
													AND
														store_received.srec_store_id='".$_SESSION['gc_store']."'
													AND
														store_received.srec_rel_id='".$relid."'";
												$join = 'INNER JOIN
														users
													ON
														users.user_id = store_received.srec_by
													LEFT JOIN
														transfer_request_served
													ON
														transfer_request_served.tr_servedid = store_received.srec_rel_id';
												$limit = '';

												$recs = getSelectedData($link,$table,$select,$where,$join,$limit);

		                        			?>

                        					<div class="header">Transfer (IN) Details</div>
											<div class="form-groupwrap">
				                                <div class="form-group">
													<label class="nobot">GC Transfer (IN)#</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly"value="<?php echo $recs->srec_recid; ?>">                          				
												</div>

				                                <div class="form-group">
													<label class="nobot">Date Received</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly"value="<?php echo _dateFormat($recs->srec_at); ?>">                                 				
												</div>


				                                <div class="form-group">
													<label class="nobot">Remarks</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly"value="<?php echo $recs->tr_receiveremarks; ?>">                                 				
												</div>

				                                <div class="form-group">
													<label class="nobot">Checked By</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly"value="<?php echo ucwords($recs->srec_checkedby); ?>">                                 				
												</div>

				                                <div class="form-group">
													<label class="nobot">Received By</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly"value="<?php echo ucwords($recs->recby); ?>">                                				
												</div>

				                                <?php 

													$table = 'documents';
													$select = 'doc_fullpath';
													$where = "doc_trid='".$recs->srec_id."'
														AND
															doc_type='Received Transfer GC'";
													$join = '';
													$limit = '';

													$docsrev = getAllData($link,$table,$select,$where,$join,$limit);	

				                                	if(count($docsrev) > 0): ?>

														<div class="form-group">
		                                                	<label class="nobot">
		                                                		<?php if(count($docsrev) > 1): ?>
		                                                			Documents
		                                                		<?php else: ?>
		                                                			Document
		                                                		<?php endif; ?>
		                                                	</label>

		                                                    <ul id="lightgallery" class="list-unstyled row" style="margin-bottom:0px;">
		                                                        <?php foreach ($docsrev as $d): ?>
		                                                        <li class="col-xs-6 col-sm-4 col-md-4" data-src="../assets/images/<?php echo $d->doc_fullpath;?>">
		                                                            <a href="" class="thumbnail">
		                                                            <img class="img-responsive theight" style="height:50px;" src="../assets/images/<?php echo $d->doc_fullpath; ?>">
		                                                            </a>
		                                                        </li>
		                                                        <?php endforeach; ?>
		                                                    </ul>
		                                                </div>
				                                <?php endif; ?>
				                            </div>  
	                            	                                    
		                        		</div> 
										<div class="col-sm-4">
											<?php 

												$table = 'store_received_gc';
												$select = 'store_received_gc.strec_barcode,
													denomination.denomination';
												$where = "store_received_gc.strec_recnum='".$recs->srec_id."'";
												$join = 'INNER JOIN
														denomination
													ON
														denomination.denom_id = store_received_gc.strec_recnum';
												$limit = '';

												$barcodes = getAllData($link,$table,$select,$where,$join,$limit);	
											?>
											<table class="table" id="recBarcodes">
												<thead>
													<tr>
														<th>Barcode</th>
														<th>Denomination</th>
													</tr>
												</thead>		
												<tbody>
													<?php foreach ($barcodes as $b): ?>
														<tr>
															<td><?php echo $b->strec_barcode; ?></td>
															<td><?php echo number_format($b->denomination,2); ?></td>
														</tr>
													<?php endforeach; ?>
												</tbody>										
											</table>
										</div>	
                        			</div>
                        		</form>

                        	</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
	<script type="text/javascript">
		$('#lightgallery,#lightgallery1').lightGallery();

			$.extend( $.fn.dataTableExt.oStdClasses, {	  
			    "sFilterInput": "searchcus"
			});
		    $('#recBarcodes').dataTable( {
		        "pagingType": "full_numbers",
		        "ordering": false,
		        "processing": true,
		        "bProcessing":true
		    });
	</script>

	<?php

}


function _servedGCTransfer($link,$reqid,$todays_date)
{
	if(isset($_SESSION['scanGCForTransfer']))
	unset($_SESSION['scanGCForTransfer']);
	$table = 'transfer_request';
	$select = "	transfer_request.tr_reqid,
		transfer_request.t_reqnum,
		transfer_request.t_reqdatereq,
		transfer_request.t_reqdateneed,
		transfer_request.t_reqremarks,
		transfer_request.t_reqstatus,
		stores.store_name,
		CONCAT(users.firstname,' ',users.lastname) as reqby";
	$where = "transfer_request.tr_reqid='".$reqid."'
		AND
			transfer_request.t_reqstoreto='".$_SESSION['gc_store']."'
		AND
			transfer_request.t_reqstatus!='closed'";
	$join = 'LEFT JOIN
			stores
		ON
			stores.store_id = transfer_request.t_reqstoreby
		LEFT JOIN
			users
		ON
			users.user_id = transfer_request.t_reqby';
	$limit = '';

	$details = getSelectedData($link,$table,$select,$where,$join,$limit);	

	//$count()

	$table = 'documents';
	$select = 'doc_fullpath';
	$where = "doc_trid='".$reqid."'
		AND
			doc_type='Transfer Request'";
	$join = '';
	$limit = '';

	$docs = getAllData($link,$table,$select,$where,$join,$limit);	

	$table = 'transfer_request_items';
	$select = 'transfer_request_items.tr_itemsdenom,
		transfer_request_items.tr_itemsqty,
		transfer_request_items.tr_itemsqtyremain,
		denomination.denomination';
	$where = "transfer_request_items.tr_itemsreqid='".$reqid."'";
	$join = 'LEFT JOIN
			denomination
		ON
			denomination.denom_id = transfer_request_items.tr_itemsdenom';
	$limit = "";

	$reqitems = getAllData($link,$table,$select,$where,$join,$limit);	


	$table = 'transfer_request_items';
	$select = 'transfer_request_items.tr_itemsdenom,
		transfer_request_items.tr_itemsqtyremain,
		denomination.denomination';
	$where = "transfer_request_items.tr_itemsreqid='".$reqid."'";
	$join = 'LEFT JOIN
			denomination
		ON
			denomination.denom_id = transfer_request_items.tr_itemsdenom';
	$limit = '';

	$denoms = getAllData($link,$table,$select,$where,$join,$limit);	

	?>
	<div class="row form-container">
    	<div class="col-md-12">
            <div class="panel with-nav-tabs panel-info">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Served Transfer Request</a></li>
                    </ul>	                    
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1default">
                        	<div class="row form-container">
                        		<form action="../ajax.php?action=serveTransferRequest" method="POST" id="servedgcTransferForm" enctype="multipart/form-data">   
                        			<div class="col-sm-12">
										<div class="col-sm-4">
											<div class="header">Request Details</div>
											<div class="form-groupwrap">
				                                <div class="form-group">
													<label class="nobot">Transfer Request #</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo $details->t_reqnum; ?>">                                 
				                                </div>
				                                <div class="form-group">
													<label class="nobot">Date Requested</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo _dateFormat($details->t_reqdatereq); ?>">   
												</div>                              
				                                <div class="form-group">
													<label class="nobot">Date Needed</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo _dateFormat($details->t_reqdateneed); ?>">                                 
				                                </div>
				                                <div class="form-group">
													<label class="nobot">From Location</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo ucwords($details->store_name); ?>">                                 
				                                </div>
				                                <div class="form-group">
													<label class="nobot">Request Remarks</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo $details->t_reqremarks; ?>">                                 
				                                </div>
				                                <div class="form-group">
													<label class="nobot">Requested By</label> 
													<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo ucwords($details->reqby); ?>">                                 
				                                </div>
				                                <?php if(count($docs) > 0): ?>
													<div class="form-group">
	                                                	<label class="nobot">Document(s)</label>

	                                                    <ul id="lightgallery" class="list-unstyled row" style="margin-bottom:0px;">
	                                                        <?php foreach ($docs as $d): ?>
	                                                        <li class="col-xs-6 col-sm-4 col-md-4" data-src="../assets/images/<?php echo $d->doc_fullpath;?>">
	                                                            <a href="" class="thumbnail">
	                                                            <img class="img-responsive theight" style="height:50px;" src="../assets/images/<?php echo $d->doc_fullpath; ?>">
	                                                            </a>
	                                                        </li>
	                                                        <?php endforeach; ?>
	                                                    </ul>
	                                                </div>
				                                <?php endif; ?>
				                            </div>
										</div>	
		                        		<div class="col-sm-3">
			                            	<div class="form-group">
			                            		<input type="hidden" name="reqid" id="reqid" value="<?php echo $details->tr_reqid; ?>">
			                              		<label class="nobot">Transfer Releasing #</label>   
			                              		<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo getTransferReleasedNumber($link,$_SESSION['gc_store']); ?>" name="relnum" id="relnum">  
			                            	</div>          
			                                <div class="form-group">
												<label class="nobot">Date Released</label> 
												<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo _dateFormat($todays_date); ?>">                                 
			                                </div>

			                                <div class="form-group">
												<label class="nobot">Remarks</label> 
												<input type="text" class="form form-control inptxt input-sm bot-6" name="remarks" id="remarks" value="" autocomplete="off">                                 
			                                </div>

			                                <div class="form-group">
												<label class="nobot"><span class="requiredf">*</span>Checked By:</label> 
												<input type="text" class="form form-control inptxt input-sm bot-6" name="checkby" id="checkby" value="" autocomplete="off">                                 
			                                </div>

			                                <div class="form-group">
												<label class="nobot"><span class="requiredf">*</span>Received By:</label> 
												<input type="text" class="form form-control inptxt input-sm bot-6" name="recby" id="recby" value="" autocomplete="off">                                 
			                                </div>	

			                                <div class="form-group">
			                                	<label class="nobot">Upload Document</label> 
			                                	<input id="input-file" class="file" type="file" name="docs[]" multiple>
			                                </div>	

			                                <div class="form-group">
												<label class="nobot">Released By:</label> 
												<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>">                                 
			                                </div>	
	                            	                                    
		                        		</div> 

										<div class="col-sm-5">
											<div class="form-horizontal denomsbox">
												<div class="form-group">
													<label class="col-sm-4"><span class="requiredf">*</span>Denomination</label>
													<label class="col-sm-4"><span class="requiredf">*</span>Request</label>	
													<label class="col-sm-4"><span class="requiredf">*</span>Scanned</label>			
												</div>
												<?php 
													foreach ($denoms as $d): 
														if($d->tr_itemsqtyremain!=0):
												?>

													<div class="form-group">
														<label class="col-sm-4">&#8369 <?php echo number_format($d->denomination,2); ?></label>
														<div class="col-sm-4">
															<input type="hidden" id="m<?php echo $d->tr_itemsdenom; ?>" value="<?php echo $d->denomination; ?>"/>
															<input class="form form-control inptxt" id="num<?php echo $d->tr_itemsdenom; ?>" value="<?php echo $d->tr_itemsqtyremain; ?>" readonly="readonly" name="denoms<?php echo $d->tr_itemsdenom; ?>" autocomplete="off" style="text-align:right">
														</div>
														<div class="col-sm-4">
															<input type="hidden" id="m<?php echo $d->tr_itemsdenom; ?>" value="<?php echo $d->denomination; ?>"/>
															<input class="form form-control inptxt denfield" id="scangc<?php echo $d->tr_itemsdenom; ?>" value="0" readonly="readonly" name="denoms<?php echo $d->tr_itemsdenom; ?>" autocomplete="off" style="text-align:right">
														</div>
													</div>
												<?php 
													endif;
													endforeach; 
												?>
												<div class="form-group">
													<div class="col-sm-6">
														<button class="btn btn-block btn-primary" type="button" onclick="scanGCForTransfer()"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Scan GC</button>
													</div>
													<div class="col-sm-6">
														<button class="btn btn-block btn-primary" type="button" onclick="viewscannedgcsForTransfer()"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> View Scanned GC</button>
													</div>
												</div>

											</div>
											<div class="labelinternaltot">
												<input type="hidden" name="totalAmt" id="totalAmt" value="0">                        
												<label>Total Amount: <span id="totalAmtLbl">0.00</span></label>
											</div>										

											<div class="response">
											</div>

											<div class="form-group">
												<div class="col-sm-offset-5 col-sm-7">
													<button type="submit" class="btn btn-block btn-primary" id="btn"> <span class="glyphicon glyphicon-share" aria-hidden="true"></span> Submit</button>
												</div>
											</div>
										</div>	                  
		                        	</div>  
                        		</form>                  		
                        	</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
	<script type="text/javascript">
	    $('input#input-file').fileinput({
	      'allowedFileExtensions' : ['jpg','png','jpeg']
	    });

	    $('#lightgallery').lightGallery();

	   	$('.form-container').on('submit','form#servedgcTransferForm',function(event){
			event.preventDefault();
			$('.response').html('');

			var formURL = $(this).attr('action'), formData = new FormData($(this)[0]);

			var hasDenom = false;

			$('.denfield').each(function(){
				if($(this).val()!=0)
				{
					hasDenom = true;
					return false;
				}
			});

			if($('#relnum').val().trim()=="")
			{
				$('.response').html('<div class="alert alert-danger" id="danger-x">Releasing number missing.</div>');
				return;
			}

			if($('#checkby').val().trim()=="" || $('#recby').val().trim()=="")
			{
				$('.response').html('<div class="alert alert-danger" id="danger-x">Please fill in required fields.</div>');
				return;				
			}

			if(!hasDenom)
			{
				$('.response').html('<div class="alert alert-danger" id="danger-x">Please scan GC first.</div>');
				return;
			}

	        BootstrapDialog.show({
	        	title: 'Confirmation',
	            message: 'Are you sure you want to Transfer GC?',
	            closable: true,
	            closeByBackdrop: false,
	            closeByKeyboard: true,
	            onshow: function(dialog) {
	               	$("button#btn").prop("disabled",true);
	            },
	            onhidden: function(dialog){
	            	$("button#btn").prop("disabled",false);
	            },
	            buttons: [{
	                icon: 'glyphicon glyphicon-ok-sign',
	                label: 'Yes',
	                cssClass: 'btn-primary',
	                hotkey: 13,
	                action:function(dialogItself){	
	                	$buttons = this;
	                	$buttons.disable();                	

						$.ajax({
				    		url:formURL,
				    		type:'POST',
							data: formData,
							enctype: 'multipart/form-data',
						    async: true,
						    cache: false,
						    contentType: false,
						    processData: false,
							beforeSend:function(){
								
							},
							success:function(data)
							{
								console.log(data);
								var data = JSON.parse(data);
								if(data['st'])
								{
									dialogItself.close();
									var dialog = new BootstrapDialog({
						            message: function(dialogRef){
						            var $message = $('<div>Transaction Successfully Saved.</div>');			        
						                return $message;
						            },
						            closable: false
							        });
							        dialog.realize();
							        dialog.getModalHeader().hide();
							        dialog.getModalFooter().hide();
							        dialog.getModalBody().css('background-color', '#0088cc');
							        dialog.getModalBody().css('color', '#fff');
							        dialog.open();
				               		setTimeout(function(){
				                    	window.location.href="reportTransferOut.php?repoutid="+data['id'];
				               		}, 1700);	
								}
								else 
								{
									$('.response').html('<div class="alert alert-danger" id="danger-x">'+data['msg']+'</div>');
								}															
							}
						});

	                }
	            }, {
	            	icon: 'glyphicon glyphicon-remove-sign',
	                label: 'No',
	                action: function(dialogItself){
	                    dialogItself.close();
	                }
	            }]
	        });
			

			return false;


		});

	    function viewscannedgcsForTransfer()
	    {
			BootstrapDialog.show({
		        title: 'Scanned GC',
		        cssClass: 'gc-barcode-modal',
		        closable: true,
		        closeByBackdrop: false,
		        closeByKeyboard: true,
		        message: function(dialog) {
		            var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
		            var pageToLoad = dialog.getData('pageToLoad');
		            setTimeout(function(){
		            $message.load(pageToLoad);
		            },1000);
		            return $message;
		        },
		        data: {
		            'pageToLoad': '../dialogs/scan_gcrel.php?action=scannedGCForTransfer'
		        },
			    onshow: function(dialog) {
			    },
			    onshown: function(dialogRef){

			    },
			    buttons:[{
	            icon: 'glyphicon glyphicon-ok-sign',
	            label: 'Remove GC',
	            cssClass: 'btn-primary',
	            hotkey: 13,
	            action:function(dialogItself1){
	            	$('.response-checkbox').html('');
	            	var checked = document.querySelectorAll('input[type="checkbox"]:checked').length;
	            	if(checked == 0)
	            	{
						$('.response-checkbox').html('<div class="alert alert-danger alert-dismissable">Please check GC barcode checkbox.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');            		
	            		return false;
	            	}

			        BootstrapDialog.show({
			        	title: 'Confirmation',
			            message: 'Are you sure you want to removed selectd GC.',
			            closable: true,
			            closeByBackdrop: false,
			            closeByKeyboard: true,
			            onshow: function(dialog) {
			                // dialog.getButton('button-c').disable();										            
			            },
			            onhidden: function(dialog) {

			            },
			            buttons: [{
			                icon: 'glyphicon glyphicon-ok-sign',
			                label: 'Ok',
			                cssClass: 'btn-primary',
			                hotkey: 13,
			                action:function(dialogItself){
			                	$buttons = this;
			                	$buttons.disable();		                	
			                	var formData = $('form#scannedGCForm').serialize(), formUrl = '../ajax.php?action=removeScannedTransferGC';
			                	var reqid = $('#reqid').val();
			                	formData+="&reqid="+reqid;
			                	dialogItself.close();
	                        	$.ajax({
	                        		url:formUrl,
	                        		data:formData,
	                        		type:'POST',                          
	                        		success:function(data1)
	                        		{
	                        			console.log(data1);
	                        			var data1 = JSON.parse(data1) 

	                        			if(data1['st'])
	                        			{
											d = data1['rscanned'];
											for (var val in d) 
											{
											    var res = d[val].split("=");
											    //alert($("input#num"+res[0]).val());
											    $("input#scangc"+res[0]).val(res[1]);
										
											}

											$('#totalAmt').val(data1['total']);
											$('span#totalAmtLbl').text(parseFloat(data1['total']).toFixed(2));

											var dialog = new BootstrapDialog({
								            message: function(dialogRef){
								            var $message = $('<div>Selected GC successfully removed.</div>');			        
								                return $message;
								            },
								            closable:false
									        });
									        dialog.realize();
									        dialog.getModalHeader().hide();
									        dialog.getModalFooter().hide();
									        dialog.getModalBody().css('background-color', '#0088cc');
									        dialog.getModalBody().css('color', '#fff');
									        dialog.open();
									        setTimeout(function(){
						                    	dialog.close();
						                    	dialogItself1.close();
						               		}, 1500);
	                        			}
	                        			else
	                        			{
	                        				$('.response-checkbox').html('<div class="alert alert-danger alert-dismissable">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
	                        				$button.enable();						                                				
	                        			}
	                        		}
	                        	});									       

			                }
			            },{
			            	icon: 'glyphicon glyphicon-remove-sign',
			                label: 'Cancel',
			                action: function(dialogItself){
			                    dialogItself.close();
			                }
			            }]
				    });	 
	            }

        		},{
			    	icon: 'glyphicon glyphicon-remove-sign',
			        label: 'Close',
			        action: function(dialogItself){
			            dialogItself.close();
			        }
			    }]
			});
	    }

		function scanGCForTransfer()
		{
		    BootstrapDialog.show({
		        title: 'Scan GC',
		        cssClass: 'customer-internal',
		        closable: true,
		        closeByBackdrop: false,
		        closeByKeyboard: true,
		        message: function(dialog) {
		            var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
		            var pageToLoad = dialog.getData('pageToLoad');
		            setTimeout(function(){
		            $message.load(pageToLoad);
		            },1000);
		            return $message;
		        },
		        data: {
		            'pageToLoad': '../templates/regulargc.php?page=scanGCForCustomerReleasing'
		        },
		        onshown: function(dialogRef){

		        },
		        onhidden:function(dialogRef){
		        },        
		        buttons: [{
		            icon: 'glyphicon glyphicon-ok-sign',
		            label: 'Submit',
		            cssClass: 'btn-primary',
		            hotkey: 13,
		            action:function(dialogItself){
		            	$('.response-validate').html('');
		             	var barcode = $('#gcbarcode').val(), formUrl = '../ajax.php?action=servedTransferRequest', reqid = $('input#reqid').val();
						if(barcode==undefined)
						{
							return false;
						}

						if(barcode.trim()=='')
						{
		    				$('.response-validate').html('<div class="alert alert-danger alert-dismissable">Please input GC barcode number.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');		
		    				$('#gcbarcode').select();
		    				return false;
						}

						$.ajax({
							url:formUrl,
							data:{barcode:barcode,reqid:reqid},
							type:'POST',
							success:function(data){
								console.log(data);
								var data = JSON.parse(data);
								if(data['st'])
								{

									var total = $('#totalAmt').val();
									total = parseFloat(total) + parseFloat(data['denomination']);
									$('#totalAmt').val(total);
									$('span#totalAmtLbl').text(total.toFixed(2));


									$('input#scangc'+data['denom']).val(data['scanned']);
									$('.response-validate').html('<div class="alert alert-success alert-dismissable">GC successfully scanned for Transfer.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');	
									$('#gcbarcode').select();
								}
								else 
								{
									$('.response-validate').html('<div class="alert alert-danger alert-dismissable">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');	
									$('#gcbarcode').select();								
								}
							}
						});       	
		        	}
		        }, {
		        	icon: 'glyphicon glyphicon-remove-sign',
		            label: 'Close',
		            action: function(dialogItself){
		                dialogItself.close();
		            }
		        }]
		    });
		}

	</script>

	<?php
}

function _servedGCTransferView($link,$reqid,$todays_date)
{
	?>
		<div class="row form-container">
	    	<div class="col-md-12">
	            <div class="panel with-nav-tabs panel-info">
	                <div class="panel-heading">
	                		<button class="btn pull-right" onclick="window.location='#/gctransferList'">Back</button>
	                    <ul class="nav nav-tabs">
	                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">View Served Transfer</a></li>
	                    </ul>	                    
	                </div>
	                <div class="panel-body">
	                    <div class="tab-content">
	                        <div class="tab-pane fade in active" id="tab1default">
	                        	<div class="row form-container">
                  					
	                        	</div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
		</div>
	<?php
}


function displayInstitutionTransaction($link,$trid)
{

	$table = 'institut_transactions';
	$select = "institut_transactions.institutr_trnum,
		institut_transactions.institutr_receivedby,
		institut_transactions.institutr_date,
		institut_transactions.institutr_remarks,
		institut_customer.ins_name,
		institut_transactions.institutr_paymenttype,
		institut_payment.institut_bankname,
		institut_payment.institut_bankaccountnum,
		institut_payment.institut_checknumber,
		institut_payment.institut_amountrec,
		CONCAT(users.firstname,' ',users.lastname) as relby";
	$where = "institut_transactions.institutr_id='".$trid."'";
	$join = 'LEFT JOIN
			institut_customer
		ON
			institut_customer.ins_id = institut_transactions.institutr_cusid
		LEFT JOIN
			institut_payment
		ON
			institut_payment.insp_trid = institut_transactions.institutr_id
		LEFT JOIN
			users
		ON
			users.user_id = institut_transactions.institutr_trby';
	$limit = '';

	$data = getSelectedData($link,$table,$select,$where,$join,$limit);

	?>
		<div class="row">
			<div class="col-sm-3">
				<div class="form-group">
					<label class="nobot">Released #</label>   
					<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo sprintf("%03d", $data->institutr_trnum); ?>">  
				</div>
				<div class="form-group">
					<label class="nobot">Date Released</label>   
					<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo _dateFormat($data->institutr_date); ?>">  
				</div>
				<div class="form-group">
					<label class="nobot">Remarks</label>   
					<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo $data->institutr_remarks; ?>">  
				</div>
                <div class="form-group">
                    <label class="nobot">Document(s) Uploaded</label> 
                    <?php 
                        $table = 'documents';
                        $select = 'doc_fullpath';
                        $where = "doc_trid='".$trid."'
                            AND
                                doc_type='Institution GC'";
                        $join = '';
                        $limit = '';
                        $docs = getAllData($link,$table,$select,$where,$join,$limit);   
         
                    ?>

                    <table class="table docstable" id="lightgallery">
                        <thead>
                            <tr>
                                <th></th>
                            </tr>
                        </thead>
                        <?php foreach ($docs as $d): ?>
                        <tr >
                            <td class="selector"  data-src="../assets/images/<?php echo $d->doc_fullpath;?>">
                                <a href="" class="thumbnail">
                                <img class="img-responsive img-table-display" src="../assets/images/<?php echo $d->doc_fullpath; ?>">
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label class="nobot">Customer</label>   
					<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo ucwords($data->ins_name); ?>">  
				</div>
				<div class="form-group">
					<label class="nobot">Received By</label>   
					<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo ucwords($data->institutr_receivedby); ?>">  
				</div>
				<div class="form-group">
					<label class="nobot">Total Denomination</label>   
					<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" 
					value="<?php  	                        		
							$table = 'institut_transactions_items';
							$select = "IFNULL(SUM(denomination.denomination),0) as sum";
							$where = "institut_transactions_items.instituttritems_trid='$trid'";
							$join = 'INNER JOIN
									gc
								ON	
									gc.barcode_no = institut_transactions_items.instituttritems_barcode
								INNER JOIN
									denomination
								ON
									denomination.denom_id = gc.denom_id
							';
							$limit = '';
							$sum = getSelectedData($link,$table,$select,$where,$join,$limit);
							echo number_format($sum->sum,2); ?>
					">  
				</div>
				<div class="form-group">
					<label class="nobot">Payment Type</label>   
					<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo ucwords($data->institutr_paymenttype); ?>">  
				</div>

				<?php if($data->institutr_paymenttype=='check'): ?>
					<div class="form-group">
						<label class="nobot">Bank Name</label>   
						<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo $data->institut_bankaccountnum; ?>">  
					</div>
					<div class="form-group">
						<label class="nobot">Bank Account Number</label>   
						<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo $data->institut_bankaccountnum; ?>">  
					</div>
					<div class="form-group">
						<label class="nobot">Check Number</label>   
						<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo $data->institut_checknumber; ?>">  
					</div>
				<?php endif;?>
			</div>
			<div class="col-sm-6">
				<table class="table" id="barcodesins">
					<thead>
						<tr>
							<th>GC Barcode #</th>
							<th>Denomination</th>
						</tr>
					</thead>
					<tbody>
						<?php 

							$table = 'institut_transactions_items';
							$select = 'institut_transactions_items.instituttritems_barcode,
								denomination.denomination';
							$where = "institut_transactions_items.instituttritems_trid='".$trid."'";
							$join = 'INNER JOIN
									gc
								ON
									gc.barcode_no = institut_transactions_items.instituttritems_barcode
								INNER JOIN
									denomination
								ON
									denomination.denom_id = gc.denom_id';
							$limit = '';
							$gcs = getAllData($link,$table,$select,$where,$join,$limit);

						foreach ($gcs as $gc):
						?>

						<tr>
							<td><?php echo $gc->instituttritems_barcode; ?></td>
							<td><?php echo number_format($gc->denomination,2); ?></td>
						</tr>

						<?php endforeach; ?>


					</tbody>
				</table>
			</div>
		</div>
		<script type="text/javascript">

            $('#lightgallery').lightGallery({
                selector:'.selector'
            });

			$.extend( $.fn.dataTableExt.oStdClasses, {	  
			    "sFilterInput": "searchcus"
			});
		    $('#barcodesins').dataTable( {
		        "pagingType": "full_numbers",
		        "ordering": false,
		        "processing": true,
		        "bProcessing":true
		    });
		</script>
	<?php
}

function institutioneod($link)
{

	$table = 'institut_payment';
	$select = 'insp_id,
	    insp_trid,
	    insp_paymentcustomer,
	    institut_bankname,
	    institut_bankaccountnum,
	    institut_checknumber,
	    institut_amountrec,
	    insp_paymentnum,
	    institut_eodid';
	$where = "institut_eodid='0'";
	$join = '';
	$limit = 'ORDER BY insp_paymentnum DESC';

	$payments = getAllData($link,$table,$select,$where,$join,$limit);

	?>
	<div class="row form-container">
    	<div class="col-md-12">
            <div class="panel with-nav-tabs panel-info">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Payment Transactions (EOD)</a></li>
                        <?php if(count($payments) > 0): ?>
                        	<button type="button" id="btn" class="btn btn-info pull-right" onclick="ins_eod();"><i class="fa fa-exchange" aria-hidden="true"></i> End of Day</button>
                        <?php endif; ?>
                        <!-- <li><a href="#tab2default" data-toggle="tab">Default 2</a></li> -->
                    </ul>	                    
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1default">
                        	<div class="row">
                        		<div class="col-sm-12">
	                        		<table class="table" id="eodins">
	                        			<thead>
	                        				<tr>
	                        					<th>Transaction #</th>
	                        					<th>Customer</th>
	                        					<th>Date</th>
	                        					<th>Time</th>
	                        					<th>GC pc(s)</th>
	                        					<th>Total Denom</th>
	                        					<th>Payment Type</th>
	                        					<th>View</th>
	                        				</tr>
	                        			</thead>
	                        			<tbody>
	                        				<?php 
	                        					foreach ($payments as $p): 
	                        					$datetr = '';
	                        					$totgccnt = '';
	                        					$totdenom = '';
	                        					$customer = '';
	                        					$paymenttype = '';

	                        					if($p->insp_paymentcustomer=='institution')
	                        					{

	                        						$query = $link->query(
	                        							"SELECT 
															institut_transactions.institutr_id,
														    institut_transactions.institutr_trnum,
														    institut_transactions.institutr_paymenttype,
														    institut_transactions.institutr_date,
														    institut_customer.ins_name
														FROM 
															institut_transactions 
														INNER JOIN
															institut_customer
														ON
															institut_customer.ins_id = institut_transactions.institutr_cusid
														WHERE 
															institut_transactions.institutr_id = '$p->insp_trid'
	                        						");

	                        						if($query)
	                        						{
	                        							$row = $query->fetch_object();

	                        							$paymenttype = $row->institutr_paymenttype;

	                        							$customer = $row->ins_name;
	                        							$datetr = $row->institutr_date;

	                        							$query_gcs = $link->query(
	                        								"SELECT 
																IFNULL(COUNT(institut_transactions_items.instituttritems_barcode),0) as cnt,
															    IFNULL(SUM(denomination.denomination),0) as totamt   
															    
															FROM 
																institut_transactions_items
															INNER JOIN
																gc
															ON
																gc.barcode_no = institut_transactions_items.instituttritems_barcode
															INNER JOIN
																denomination
															ON
																denomination.denom_id = gc.denom_id
															WHERE 
																instituttritems_trid = '$p->insp_trid'
	                        							");

	                        							if($query_gcs)
	                        							{
	                        								$row = $query_gcs->fetch_object();

	                        								$totgccnt = $row->cnt;
	                        								$totdenom = $row->totamt;
	                        							}
	                        						}

	                        					}
	                        					elseif($p->insp_paymentcustomer=='stores') 
	                        					{
													$query = $link->query(
														"SELECT 
															approved_gcrequest.agcr_request_id,
															approved_gcrequest.agcr_request_relnum,
														    approved_gcrequest.agcr_approved_at,
														    approved_gcrequest.agcr_paymenttype,
														    stores.store_name
														FROM 
															approved_gcrequest
														INNER JOIN
															store_gcrequest
														ON
															store_gcrequest.sgc_id = approved_gcrequest.agcr_request_id
														INNER JOIN
															stores
														ON
															stores.store_id = store_gcrequest.sgc_store
														WHERE 
															approved_gcrequest.agcr_id = '$p->insp_trid'	
													");

													if($query)
													{
														$row = $query->fetch_object();
														$customer = $row->store_name;
														$datetr = $row->agcr_approved_at;

														$paymenttype = $row->agcr_paymenttype;

														$query_gcs = $link->query(
															"SELECT		
																IFNULL(COUNT(gc_release.re_barcode_no),0) as cnt,																		  		
																IFNULL(SUM(denomination.denomination),0) as totamt  
															FROM 
																gc_release 
															INNER JOIN
																gc
															ON
																gc.barcode_no = gc_release.re_barcode_no
															INNER JOIN
																denomination
															ON
																denomination.denom_id = gc.denom_id
															WHERE 
																rel_num='$row->agcr_request_relnum'
														");

														if($query_gcs)
														{
															$row = $query_gcs->fetch_object();

	                        								$totgccnt = $row->cnt;
	                        								$totdenom = $row->totamt;
														}

													}


	                        					}
	                        					elseif ($p->insp_paymentcustomer=='special external') 
	                        					{
	                        						$query = $link->query(
	                        							"SELECT 
															special_external_gcrequest.spexgc_id,
														    special_external_gcrequest.spexgc_datereq,
														    special_external_customer.spcus_companyname,
														    special_external_gcrequest.spexgc_paymentype
														FROM 
															special_external_gcrequest
														INNER JOIN
															special_external_customer
														ON
															special_external_customer.spcus_id = special_external_gcrequest.spexgc_company
														WHERE 
															special_external_gcrequest.spexgc_id='$p->insp_trid'	
	                        						");

	                        						if($query)
	                        						{
	                        							$row = $query->fetch_object();

	                        							$customer = $row->spcus_companyname;
	                        							$datetr = $row->spexgc_datereq;

	                        							if($row->spexgc_paymentype=='1')
	                        							{
	                        								$paymenttype = 'cash';
	                        							}
	                        							else 
	                        							{
	                        								$paymenttype = 'check';
	                        							}

	                        							$query_gcs = $link->query(
	                        								"SELECT 
																IFNULL(SUM(special_external_gcrequest_items.specit_qty),0) as cnt,
								    							IFNULL(SUM(special_external_gcrequest_items.specit_denoms * special_external_gcrequest_items.specit_qty),0) as totamt
															FROM 
																special_external_gcrequest_items
															WHERE 
																specit_trid='$p->insp_trid'
	
	                        							");

	                        							if($query_gcs)
	                        							{
															$row = $query_gcs->fetch_object();

	                        								$totgccnt = $row->cnt;
	                        								$totdenom = $row->totamt;	                        								
	                        							}

	                        						}
	                        					}

	                        				?>
	                        					<tr>
	                        						<td><?php echo  sprintf("%03d", $p->insp_paymentnum); ?></td>
	                        						<td><?php echo $customer; ?></td>
	                        						<td><?php echo _dateFormat($datetr); ?></td>
	                        						<td><?php echo _timeFormat($datetr); ?></td>
	                        						<td>
	                        							<?php
	                        								echo $totgccnt;
	                        							?>

	                        						</td>
	                        						<td>
	                        							<?php 
	                        								echo number_format($totdenom,2);
	                        							?>
	                        						</td>
	                        						<td>
	                        							<?php if($paymenttype=='cashcheck'): 
	                        								echo 'Check and Cash';
	                        							elseif($paymenttype=='gad'):
	                        								echo 'Giveaways and Donations'; ?>
	                        							<?php else: ?>
	                        								<?php echo strtoupper($paymenttype); ?>
	                        							<?php endif; ?>
	                        						</td>
	                        						<td><i class="fa fa-fa fa-eye faeye" title="View" data-trid="<?php echo $p->insp_trid; ?>,<?php echo $p->insp_paymentcustomer; ?>" id="viewinstr"></i></td>
	                        					</tr>
	                        				<?php endforeach; ?>
	                        			</tbody>
	                        		</table>
                        		</div>
                        	</div>
                        </div>
                        <!-- <div class="tab-pane fade" id="tab2default">Default 2</div> -->
                    </div>
                </div>
            </div>
        </div>
	</div>
	<div class="modal modal-static fade loadingstyle" id="processing-modal" role="dialog" aria-hidden="true" data-keyboard="false" data-backdrop="static">
	    <div class="modal-dialog loadingstyle">
	      <div class="text-center">
	          <img src="../assets/images/ring-alt.svg" class="icon" />
	          <h4 class="loading">Saving Data...</h4>
	      </div>
	    </div>
	</div>
	<script type="text/javascript">

		$.extend( $.fn.dataTableExt.oStdClasses, {	  
		    "sFilterInput": "selectsup"
		});

		
	    $('#eodins').dataTable( {
	        "pagingType": "full_numbers",
	        "ordering": false,
	        "processing": true,
	        "bProcessing":true
	    });

	    $('table#eodins tbody tr td').on('click','i#viewinstr',function(){
	    	var trid = $(this).attr('data-trid');
		    BootstrapDialog.show({
		        title: '',
		        cssClass: 'modal-details-strel',
		        closable: true,
		        closeByBackdrop: false,
		        closeByKeyboard: true,
		        message: function(dialog) {
		            var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
		            var pageToLoad = dialog.getData('pageToLoad');
		            setTimeout(function(){
		            $message.load(pageToLoad);
		            },1000);
		            return $message;
		        },
		        data: {
		            'pageToLoad': '../templates/regulargc.php?page=displayInstitutionTransaction&trid='+trid
		        },
		        onshown: function(dialogRef){

		        },
		        onhidden:function(dialogRef){
		        },        
		        buttons: [{
		        	icon: 'glyphicon glyphicon-remove-sign',
		            label: 'Close',
		            action: function(dialogItself){
		                dialogItself.close();
		            }
		        }]
		    });

	    });

	    function ins_eod()
	    {
			BootstrapDialog.show({
			  title: 'Confirmation',
			  message:  '<div class="row">'+
			            '<div class="col-md-12">'+
			            'Are you sure you want to process EOD?'+
			            '</div>'+                                                                       
			            '</div>',
			  cssClass: 'confirmation',    
			  closable: true,
			  closeByBackdrop: false,
			  closeByKeyboard: true,
			  onshow: function(){
			  	$('#btn').prop('disabled',true);
			  },
			  onhidden: function(){
			  	$('#btn').prop('disabled',false);
			  },
			  buttons: [{
			      icon: 'glyphicon glyphicon-ok-sign',
			      label: 'Yes',
			      cssClass: 'btn-success',
			      hotkey: 13,
			      action:function(dialogItself){
			      	$buttons = this;
			      	$buttons.disable();
			      	dialogItself.close();
			      	$.ajax({
			      		url:'../ajax.php?action=eodtreasury',
			      		beforeSend:function(data)
			      		{
			      			$('#processing-modal').modal('show');
			      			$('div.box-content').html('<i class="fa fa-cog fa-spin"></i>');
			      		},
			      		success:function(data)
			      		{	      					
			      			setTimeout(function(){
			      				$('#processing-modal').modal('hide'); 	      				
			      			},1000);

			      			console.log(data);
			      			var data = JSON.parse(data);
			      			if(data['st'])
			      			{
			      				var id = data['id'];
			      				window.location = 'institutiongc-eodpdf.php?id='+id;
			      			}
			      			else 
			      			{
			      				alert(data['msg']);
			      			}
			      		}
			      	});	        
			      }
			  }, {
			    icon: 'glyphicon glyphicon-remove-sign',
			      label: 'No',
			      action: function(dialogItself){
			          dialogItself.close();                     
			      }
			  }]
			});
	    }
	</script>
<?php
}
function institutionGCSales($link)
{

	$table = 'institut_transactions';
	$select = 'institut_transactions.institutr_id,
		institut_transactions.institutr_trnum,
		institut_transactions.institutr_paymenttype,
		institut_transactions.institutr_receivedby,
		institut_transactions.institutr_date,
		institut_customer.ins_name';
	$where = "1";
	$join = 'LEFT JOIN
			institut_customer
		ON
			institut_customer.ins_id = institut_transactions.institutr_cusid';
	$limit = '';

	$transactions = getAllData($link,$table,$select,$where,$join,$limit);

	?>
		<div class="row form-container">
	    	<div class="col-md-12">
	            <div class="panel with-nav-tabs panel-info">
	                <div class="panel-heading">
	                    <ul class="nav nav-tabs">
	                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Institution GC Sales</a></li>
	                        <!-- <li><a href="#tab2default" data-toggle="tab">Default 2</a></li> -->
	                    </ul>	                    
	                </div>
	                <div class="panel-body">
	                    <div class="tab-content">
	                        <div class="tab-pane fade in active" id="tab1default">
	                        	<div class="row">
	                        		<div class="col-sm-12">
	                        			<table class="table" id="instr">
	                        				<thead>
	                        					<tr>
	                        						<th>Transaction #</th>
	                        						<th>Customer</th>
	                        						<th>Date</th>
	                        						<th>Time</th>
	                        						<th>GC (pcs)</th>
	                        						<th>Total Denom</th>
	                        						<th>Payment Type</th>
	                        						<th>View</th>
	                        					</tr>
	                        				</thead>
	                        				<tbody>
	                        					<?php foreach ($transactions as $tr): ?>
	                        						<tr>
	                        							<td><?php echo  sprintf("%03d", $tr->institutr_trnum); ?></td>
	                        							<td><?php echo ucwords($tr->ins_name); ?></td>
	                        							<td><?php echo _dateFormat($tr->institutr_date); ?></td>
	                        							<td><?php echo _timeFormat($tr->institutr_date); ?></td>
	                        							<td>
		                        							<?php
		                        								echo numRows($link,'institut_transactions_items','instituttritems_trid',$tr->institutr_id);
		                        							?>
	                        							</td>
	                        							<td>
		                        							<?php 
		                        								$table = 'institut_transactions_items';
		                        								$select = "IFNULL(SUM(denomination.denomination),0) as sum";
		                        								$where = "institut_transactions_items.instituttritems_trid='$tr->institutr_id'";
		                        								$join = 'INNER JOIN
																		gc
																	ON	
																		gc.barcode_no = institut_transactions_items.instituttritems_barcode
																	INNER JOIN
																		denomination
																	ON
																		denomination.denom_id = gc.denom_id
		                        								';
		                        								$limit = '';
																$sum = getSelectedData($link,$table,$select,$where,$join,$limit);
		                        								echo number_format($sum->sum,2);

		                        							?>	                        								
	                        							</td>
	                        							<td><?php echo ucwords($tr->institutr_paymenttype); ?></td>
	                        							<td><i class="fa fa-fa fa-eye faeye" title="View" data-trid="<?php echo $tr->institutr_id; ?>" id="viewinstr"></i></td>
	                        						</tr>
	                        					<?php endforeach; ?>
	                        				</tbody>
	                        			</table>
	                        		</div>
	                        	</div>
	                        </div>
	                        <!-- <div class="tab-pane fade" id="tab2default">Default 2</div> -->
	                    </div>
	                </div>
	            </div>
	        </div>
		</div>

		<script type="text/javascript">
			$.extend( $.fn.dataTableExt.oStdClasses, {	  
			    "sFilterInput": "searchcus"
			});
		    $('#instr').dataTable( {
		        "pagingType": "full_numbers",
		        "ordering": false,
		        "processing": true,
		        "bProcessing":true
		    });

		    $('table#instr tbody tr td').on('click','i#viewinstr',function(){
		    	var trid = $(this).attr('data-trid');
			    BootstrapDialog.show({
			        title: '',
			        cssClass: 'modal-details-strel',
			        closable: true,
			        closeByBackdrop: false,
			        closeByKeyboard: true,
			        message: function(dialog) {
			            var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
			            var pageToLoad = dialog.getData('pageToLoad');
			            setTimeout(function(){
			            $message.load(pageToLoad);
			            },1000);
			            return $message;
			        },
			        data: {
			            'pageToLoad': '../templates/regulargc.php?page=displayInstitutionTransaction&trid='+trid
			        },
			        onshown: function(dialogRef){

			        },
			        onhidden:function(dialogRef){
			        },        
			        buttons: [{
			        	icon: 'glyphicon glyphicon-remove-sign',
			            label: 'Close',
			            action: function(dialogItself){
			                dialogItself.close();
			            }
			        }]
			    });

		    });

		</script>

	<?php
}

function _gcLost($link,$todays_date)
{
	if(isset($_SESSION['scanGCForLostGCReport']))
	{
		unset($_SESSION['scanGCForLostGCReport']);
	}
	?>
	<div class="row form-container">
    	<div class="col-md-12">
            <div class="panel with-nav-tabs panel-info">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Report Lost GC</a></li>
                    </ul>	                    
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1default">
                        	<div class="row form-container">
                        		<form method="POST" id="gcLostReport" enctype="multipart/form-data" action="../ajax.php?action=gcLostReport" >
                              	<div class="col-sm-12">
                                	<div class="col-sm-4">
                                    	<div class="form-group">
                                        	<label class="nobot">Lost Report #</label>   
                                        	<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo getLostGCReportNum($link,$_SESSION['gc_store']); ?>" name="repnum" id="repnum">  
                                        </div>
                                    	<div class="form-group">
                                        	<label class="nobot">Date Reported</label>   
                                        	<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo _dateFormat($todays_date); ?>">   
                                        </div>
                                    	<div class="form-group">
                                        	<label class="nobot"><span class="requiredf">*</span>Date Lost</label>   
                                        	<input type="text" class="form form-control inptxt input-sm ro bot-6" id="dp1" data-date-format="MM dd, yyyy" name="date_lost" readonly="readonly" required>
                                        </div>
                                    	<div class="form-group">
                                        	<label class="nobot"><span class="requiredf">*</span>Owners Name</label>   
                                        	<input type="text" class="form form-control inptxt input-sm bot-6" value="" name="ownersname" id="ownersname" required>   
                                        </div>
                                    	<div class="form-group">
                                        	<label class="nobot"><span class="requiredf">*</span>Address</label>   
                                        	<textarea class="form form-control inptxt input-sm bot-6" value="" name="address" id="address" required></textarea>                                        	
                                        </div>
                                    	<div class="form-group">
                                        	<label class="nobot"><span class="requiredf">*</span>Contact #</label>   
                                        	<input type="text" class="form form-control inptxt input-sm bot-6" value="" name="contactnum" id="contactnum" required>  
                                        </div>
                                    	<div class="form-group">
                                        	<label class="nobot">Prepared By</label>   
                                        	<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>" name="" id="">  
                                        </div>
                                    </div> 
                                    <div class="col-sm-5">
                                    	<div class="form-horizontal">
	                                    	<div class="form-group">
	                                    		<div class="col-sm-offset-4 col-sm-8"> 
	                                        		<input type="text" class="form form-control inptxt input-sm bot-6" maxlength="13" autocomplete="off" placeholder="Type Barcode #" name="lostbarcode" id="lostbarcode">  
	                                        	</div>
	                                        </div> 
	                                    </div>
	                                    <div class="response-barcode-scan">
	                                    </div>                              
                                    	<table class="table" id="lostGCList">
                                    		<thead>
                                    			<tr>
                                    				<th>Barcode #</th>
                                    				<th>Denomination</th>
                                    				<th></th> 
                                    			</tr>
                                    		</thead>
                                    		<tbody class="lostGCListtbody">                                    			
                                    		</tbody>
                                    	</table>
                                    </div>
                                    <div class="col-sm-3">
                                    	<div class="form-group">
                                        	<label class="nobot">Remarks</label>   
                                        	<textarea class="form form-control inptxt input-sm bot-6" name="remarks" id="remarks" ></textarea>  
                                        </div>

										<div class="response">
										</div>

										<div class="form-group">
											<div class="col-sm-12">
												<button type="button" class="btn btn-block btn-primary btnlostgc" id="btn"> <span class="glyphicon glyphicon-share" aria-hidden="true"></span> Submit</button>
											</div>
										</div>
                                    </div>
                        		</form>
                        	</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
	<script type="text/javascript">
		
		var nowTemp = new Date();
		var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

		var checkin = $('#dp1').datepicker({
		    autoclose: true
		});

		$.extend( $.fn.dataTableExt.oStdClasses, {	  
		    "sLengthSelect": "selectsup"
		});

	    $('#lostGCList').dataTable( {
	        "pagingType": "full_numbers",
	        "ordering": false,
	        "processing": true,
	        "iDisplayLength": 5
	    });

		$( "#lostbarcode" ).bind({
			keypress: function() {
				if(event.which === 13)
				{
					$('.response-barcode-scan').html('');
					var barcode = $(this).val();
					if(barcode.trim().length == 0 )
					{
						$('.response-barcode-scan').html('<div class="alert alert-danger">Please input GC Barcode number.</div>');
						return false;
					}

					$.ajax({
						url:'../ajax.php?action=checklostgc',
						data:{barcode:barcode},
						type:'POST',
						success:function(data)
						{
							console.log(data);
							var data = JSON.parse(data);

							if(data['st'])
							{
								denom = parseFloat(data['denom']).toFixed(2);
								var t = $('#lostGCList').DataTable();
				    			var counter = 1;
						        t.row.add( [		        	
						           	data['barcode'],
						            denom,
						            '<i class="fa fa-times fa-times-removed remscanlostgc" title="remove" data-gc="'+data['barcode']+'"></i>'
						        ] ).draw( false );
						 		
						        counter++;

								$('input#lostbarcode').select();
								$('.response-barcode-scan').html('');

							}
							else 
							{
								$('.response-barcode-scan').html('<div class="alert alert-danger lostgc-alert">'+data['msg']+'</div>');
								$('#lostbarcode').select();
							}
						
						}
					});

					// var t = $('#lostGCList').DataTable();
     //      			var counter = 1;

	    // 			var counter = 1;
			  //       t.row.add( [		        	
			  //          	'z',
			  //           'y',
			  //           '<i class="fa fa-times fa-times-removed" title="Update"></i>'
			  //       ] ).draw( false );
			 		
			  //       counter++;

					// $('input#lostbarcode').select();
					// $('.response-barcode-scan').html('xxxx');
				}
			},
			blur:function() {
				$('.response-barcode-scan').html('');
				$('input#lostbarcode').val('');
			}
		});

		var table = $('#lostGCList').DataTable();

		$('#lostGCList tbody').on( 'click', 'i.fa', function () {
			var st = "no";
			var gc = $(this).attr('data-gc');

			var r = confirm("Remove GC # "+gc+"?");
			if (r == true) 
			{				
				$.ajax({
					url:'../ajax.php?action=removedscannedLostGC',
					data:{gc:gc},
					type:'POST',
					beforeSend:function(data)
					{
					},
					success:function(data)
					{
						console.log(data);
						var data = JSON.parse(data);
						
						// if(data['st'])
						// {
							
						// 	st = "yo!";
						// 	alert(st);
						// }					
					}
				});

			    table
			        .row( $(this).parents('tr') )
			        .remove()
			        .draw();
			}
		});

		$('.btnlostgc').click(function(){
			$('.response').html('');
			var formDATA = $('#gcLostReport').serialize(), formURL = $('#gcLostReport').attr('action');
			var comma = $('#dp1').val().trim().split( new RegExp( "," ) ).length-1;

			var hasError = false;
			if($('input[name=date_lost]').val().trim()=='')
			{
				$('.response').html("<div class='alert alert-danger'>Please input date lost.</div>");
				return false;
			}
			var datelost = $('input[name=date_lost]').val().trim();
			var cnt = (datelost.match(/,/g) || []).length;
			if(cnt > 1)
			{
				$('.response').html("<div class='alert alert-danger'>Invalid date lost.</div>");
				return false;				
			}

			if($('input[name=ownersname]').val().trim()=='')
			{
				$('.response').html("<div class='alert alert-danger'>Please input owner's name.</div>");
				return false;
			}

			if($('textarea[name=address]').val().trim()=='')
			{
				$('.response').html("<div class='alert alert-danger'>Please input owner's address.</div>");
				return false;
			}

			if($('input[name=contactnum]').val().trim()=='')
			{
				$('.response').html("<div class='alert alert-danger'>Please input owner's contact number.</div>");
				return false;
			}	

			//var trlen = $('tbody.lostGCListtbody tr').length;
			var countTableGC = table.data().count();

			if(countTableGC==0)
			{
				$('.response').html("<div class='alert alert-danger'>Table is empty.</div>");
				return false;				
			}

			if(comma > 1)
			{
				$('.response').html("<div class='alert alert-danger'>Invalid date Lost.</div>");
				return false;				
			}


	        BootstrapDialog.show({
	        	title: 'Confirmation',
	            message: 'Are you sure you want to save data?',
	            closable: true,
	            closeByBackdrop: false,
	            closeByKeyboard: true,
	            onshow: function(dialog) {
	               	$("button#btn").prop("disabled",true);
	            },
	            onhidden: function(dialog){
	            	$("button#btn").prop("disabled",false);
	            },
	            buttons: [{
	                icon: 'glyphicon glyphicon-ok-sign',
	                label: 'Yes',
	                cssClass: 'btn-primary',
	                hotkey: 13,
	                action:function(dialogItself){	
	                	$buttons = this;
	                	$buttons.disable();                	
	                	dialogItself.close();

							//alert(datelost)
						$.ajax({
							url:formURL,
							data:formDATA,
							type:'POST',
							success:function(data)
							{
								console.log(data);		
								var data = JSON.parse(data);

								if(data['st'])
								{
									var dialog = new BootstrapDialog({
						            message: function(dialogRef){
						            var $message = $('<div>Data Successfully Saved.</div>');			        
						                return $message;
						            },
						            closable: false
							        });
							        dialog.realize();
							        dialog.getModalHeader().hide();
							        dialog.getModalFooter().hide();
							        dialog.getModalBody().css('background-color', '#0088cc');
							        dialog.getModalBody().css('color', '#fff');
							        dialog.open();
							        // setTimeout(function(){
				           //          	dialog.close();
				           //     		}, 1500);
				               		setTimeout(function(){
				                    	window.location.href="index.php";
				               		}, 1500);

								}	
								else
								{
									$('.response').html("<div class='alert alert-danger'>"+data['msg']+"</div>");
								}		

							}
						});
	                }
	            }, {
	            	icon: 'glyphicon glyphicon-remove-sign',
	                label: 'No',
	                action: function(dialogItself){
	                    dialogItself.close();
	                }
	            }]
	        });


		});

		$('#lostbarcode').inputmask("integer", { allowMinus: false,autoGroup: true, groupSeparator: "", placeholder:'' });
		
	</script>
	<?php
}


function _gcTransfer($link,$todays_date,$active)
{
	//get locations

	$locs = getStores($link);
	$denoms = getAllDenomination($link);

	$table = 'transfer_request';
	$select = "transfer_request.tr_reqid,
		transfer_request.t_reqnum,
		transfer_request.t_reqdatereq,
		transfer_request.t_reqstatus,
		stores.store_name,
		CONCAT(users.firstname,' ',users.lastname) as prepby";
	$where = "transfer_request.t_reqstoreby='".$_SESSION['gc_store']."'";
	$join = 'LEFT JOIN
			stores
		ON
			stores.store_id = transfer_request.t_reqstoreto
		LEFT JOIN
			users
		ON
			users.user_id = transfer_request.t_reqby';
	$limit = 'ORDER BY tr_reqid DESC';

	$requestList = getAllData($link,$table,$select,$where,$join,$limit);

	$table = 'transfer_request';
	$select = "transfer_request.tr_reqid,
		transfer_request.t_reqnum,
		transfer_request.t_reqdatereq,
		transfer_request.t_reqstatus,
		stores.store_name,
		CONCAT(users.firstname,' ',users.lastname) as prepby";
	$where = "transfer_request.t_reqstoreto='".$_SESSION['gc_store']."'";
	$join = 'LEFT JOIN
			stores
		ON
			stores.store_id = transfer_request.t_reqstoreby
		LEFT JOIN
			users
		ON
			users.user_id = transfer_request.t_reqby';
	$limit = '';

	$storerequest = getAllData($link,$table,$select,$where,$join,$limit);

	?>
	<div class="row form-container">
    	<div class="col-md-12">
            <div class="panel with-nav-tabs panel-info">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li <?php if($active=='list'): ?>class="active" <?php endif; ?> style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Transfer Request List</a></li>
                        <li <?php if($active=='create'): ?>class="active" <?php endif; ?> style="font-weight:bold"><a href="#tab2default" data-toggle="tab">GC Transfer Request</a></li>
                        <li <?php if($active=='served'): ?>class="active" <?php endif; ?> style="font-weight:bold"><a href="#tab3default" data-toggle="tab">Served Transfer Request(Out)</a></li>
                        <li <?php if($active=='receive'): ?>class="active" <?php endif; ?> style="font-weight:bold"><a href="#tab4default" data-toggle="tab">GC Transfer Receiving(In)</a></li>

                    </ul>	                    
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1default">
                        	<table class="table" id="list">
                        		<thead>
	                        		<tr>
	                        			<th>Request #</th>
	                        			<th>To Location</th>
	                        			<th>Date Requested</th>
	                        			<th>Total Amount</th>
	                        			<th>Requested by</th>
	                        			<th>Status</th>
	                        			<th></th>
	                        		</tr>                        			
                        		</thead>
                        		<tbody>
                        			<?php foreach ($requestList as $d): ?> 
                        				<tr onclick="window.location='#/gctransferList/<?php echo $d->tr_reqid; ?>'">
	                        				<td><?php echo $d->t_reqnum; ?></td>
	                        				<td><?php echo ucwords($d->store_name); ?></td>
	                        				<td><?php echo _dateFormat($d->t_reqdatereq); ?></td>
	                        				<td>
	                        					<?php
	                        						//echo $d->tr_reqid;

	                        						$totamt = 0;
	                        						$table = 'transfer_request_items';
	                        						$select = 'IFNULL(SUM(transfer_request_items.tr_itemsqty * denomination.denomination),0.00) as totamt';
	                        						$where = "transfer_request_items.tr_itemsreqid = '".$d->tr_reqid."'
														GROUP BY
															denomination.denomination";
	                        						$join = 'INNER JOIN
															denomination
														ON
															denomination.denom_id = transfer_request_items.tr_itemsdenom';
	                        						$limit = '';

	                        						$tot = getAllData($link,$table,$select,$where,$join,$limit);
	                        						
	                        						foreach ($tot as $t) 
	                        						{
	                        							$totamt += $t->totamt;	                        							
	                        						}

	                        						echo number_format($totamt,2);
	                        					?>
	                        				</td>
	                        				<td><?php echo ucwords($d->prepby); ?></td>
	                        				<td><?php echo ucwords($d->t_reqstatus); ?></td>
	                        				<td></td>
                        				</tr>
                        			<?php endforeach; ?>
                        		</tbody>
                        	</table>
                        </div>
                        <div class="tab-pane fade" id="tab2default">
                        	<div class="row form-container">
                        		<form action="../ajax.php?action=transfergcrequest" method="POST" id="gcTransferForm" enctype="multipart/form-data">   
                        			<div class="col-sm-12">
		                        		<div class="col-sm-3">
			                            	<div class="form-group">
			                            		<input type="hidden" name="reqtype" value="1">
			                              		<label class="nobot">Transfer Request #</label>   
			                              		<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo getTransferRequestNumber($link,$_SESSION['gc_store']); ?>" name="reqnum" id="reqnum">  
			                            	</div>          
			                                <div class="form-group">
												<label class="nobot">Date Requested</label> 
												<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo _dateFormat($todays_date); ?>">                                 
			                                </div>

			                                <div class="form-group">
			                                	<label class="nobot"><span class="requiredf">*</span>Date Needed:</label>
			                                	<input type="text" class="form form-control inptxt input-sm ro bot-6" id="dp1" data-date-format="MM dd, yyyy" name="date_needed" readonly="readonly" required>
			                                </div>		
			                                <div class="form-group">
			                                	<label class="nobot">Upload Document</label> 
			                                	<input id="input-file" class="file" type="file" name="docs[]" multiple>
			                                </div>		                            	                                    
		                        		</div> 
										<div class="col-sm-4">
											<div class="form-group">												
												<label class="nobot"><span class="requiredf">*</span>To Location</label>   
												<select class="form-control input-sm inptxt" id="store-selected" name="storeallo" autofocus="" required="">
                									<option value="">--Select--</option>
                									<?php foreach ($locs as $l): ?>
                										<?php if($l->store_id!=$_SESSION['gc_store']): ?>
                											<option value="<?php echo $l->store_id;  ?>"><?php echo ucwords($l->store_name); ?></option>
                										<?php endif; ?>
                									<?php endforeach; ?>     
	                                            </select>
											</div>  
											<div class="form-group">
												<label class="nobot"><span class="requiredf">*</span>Remarks</label> 
												<input type="text" class="form-control inptxt input-sm" name="remarks" autocomplete="off" required="">
											</div>
										</div>	
										<div class="col-sm-4">
											<div class="form-horizontal denomsbox">
												<div class="form-group">
													<label class="col-sm-6"><span class="requiredf">*</span>Denomination</label>
													<label class="col-sm-6"><span class="requiredf">*</span>Qty</label>			
												</div>
												<?php foreach ($denoms as $d): ?>
													<div class="form-group">
														<label class="col-sm-6">&#8369 <?php echo number_format($d->denomination,2); ?></label>
														<div class="col-sm-6">
															<input type="hidden" id="m<?php echo $d->denom_id; ?>" value="<?php echo $d->denomination; ?>"/>
															<input class="form form-control inptxt denfield" id="num<?php echo $d->denom_id; ?>" value="" name="denoms<?php echo $d->denom_id; ?>" autocomplete="off">
														</div>
													</div>
												<?php endforeach; ?>
											</div>
											<div class="labelinternaltot">
												<input type="hidden" name="totalAmt" id="totalAmt" value="0">                        
												<label>Total: <span id="totalAmtLbl">0.00</span></label>
											</div>
											<div class="form-group">
												<label class="nobot">Prepared By:</label> 
												<input type="text" readonly="readonly" class="form-control inptxt input-sm" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>"> 
											</div>

											<div class="response">
											</div>

											<div class="form-group">
												<div class="col-sm-offset-5 col-sm-7">
													<button type="submit" class="btn btn-block btn-primary" id="btn"> <span class="glyphicon glyphicon-share" aria-hidden="true"></span> Submit</button>
												</div>
											</div>
										</div>	                  
		                        	</div>  
                        		</form>                  		
                        	</div>
                        </div>
                        <div class="tab-pane fade" id="tab3default">

                        	<!-- List of Request GC to serve -->

                        	<?php 

								// $table = 'transfer_request';
								// $select = "transfer_request.tr_reqid,
								// 	transfer_request.t_reqnum,
								// 	transfer_request.t_reqdatereq,
								// 	transfer_request.t_reqstatus,
								// 	stores.store_name,
								// 	CONCAT(users.firstname,' ',users.lastname) as prepby";
								// $where = "transfer_request.t_reqstoreto='".$_SESSION['gc_store']."'";
								// $join = 'LEFT JOIN
								// 		stores
								// 	ON
								// 		stores.store_id = transfer_request.t_reqstoreby
								// 	LEFT JOIN
								// 		users
								// 	ON
								// 		users.user_id = transfer_request.t_reqby';
								// $limit = '';

								// $listToserve = getAllData($link,$table,$select,$where,$join,$limit);

                        	?>

                        	<table class="table" id="listreq">
                        		<thead>
	                        		<tr>
	                        			<th>Request #</th>
	                        			<th>From Location</th>
	                        			<th>Date Requested</th>
	                        			<th>Total Amount</th>
	                        			<th>Requested by</th>
	                        			<th>Status</th>
	                        		</tr>                        			
                        		</thead>
                        		<tbody>
                        			<?php foreach ($storerequest as $d): 

                        				if($d->t_reqstatus=='closed'):
                        				?>
	                        				<tr onclick="window.location='#/transfer-served-view/<?php echo $d->tr_reqid; ?>'">
	                        			<?php else: ?>
	                        				<tr onclick="window.location='#/transfer-served/<?php echo $d->tr_reqid; ?>'">
	                        			<?php endif; ?>
		                        				<td><?php echo $d->t_reqnum; ?></td>
		                        				<td><?php echo ucwords($d->store_name); ?></td>
		                        				<td><?php echo _dateFormat($d->t_reqdatereq); ?></td>
		                        				<td>
		                        					<?php
		                        						$totamt = 0;
		                        						$table = 'transfer_request_items';
		                        						$select = 'IFNULL(SUM(transfer_request_items.tr_itemsqty * denomination.denomination),0.00) as totamt';
		                        						$where = "transfer_request_items.tr_itemsreqid = '".$d->tr_reqid."'
															GROUP BY
																denomination.denomination";
		                        						$join = 'INNER JOIN
																denomination
															ON
																denomination.denom_id = transfer_request_items.tr_itemsdenom';
		                        						$limit = '';

		                        						$tot = getAllData($link,$table,$select,$where,$join,$limit);
		                        						
		                        						foreach ($tot as $t) 
		                        						{
		                        							$totamt += $t->totamt;	                        							
		                        						}

		                        						echo number_format($totamt,2);
		                        					?>	                        					
		                        				</td>
		                        				<td><?php echo ucwords($d->prepby); ?></td>
		                        				<td><?php echo ucwords($d->t_reqstatus); ?></td>
	                        				</tr>
                        			<?php endforeach; ?>
                        		</tbody>
                        	</table>
                        </div>
                        <div class="tab-pane fade" id="tab4default">
                     		<?php 
                     			$table = 'transfer_request_served';
                     			$select = "transfer_request.t_reqdatereq,
                     				transfer_request_served.tr_servedid,
									transfer_request_served.tr_serverelnum,
									transfer_request_served.tr_serveremarks,
									transfer_request_served.tr_serveCheckedBy,
									transfer_request_served.tr_serveReceivedBy,
									transfer_request_served.tr_servedate,
									transfer_request_served.tr_serveStatus,
									transfer_request_served.tr_serveRecStatus,
									CONCAT(users.firstname,' ',users.lastname) as servedby,
									stores.store_name";
                     			$where = " transfer_request_served.tr_serve_store = '".$_SESSION['gc_store']."'
									ORDER BY
										transfer_request_served.tr_servedid DESC";
                     			$join = 'LEFT JOIN
										users
									ON
										users.user_id = transfer_request_served.tr_serveby
									LEFT JOIN
										transfer_request
									ON
										transfer_request.tr_reqid = transfer_request_served.tr_reqid
									LEFT JOIN
										stores
									ON
										stores.store_id = transfer_request.t_reqstoreto';
                     			$limit = '';
                     			$recs = getAllData($link,$table,$select,$where,$join,$limit);	
                     		?>
                        	<table class="table" id="recreq">
                        		<thead>
	                        		<tr>
	                        			<th>Released #</th>	                        			
	                        			<th>Date Requested</th>
	                        			<th>From Location</th>
	                        			<th>Date Released</th>
	                        			<th>Total Amount</th>
	                        			<th>Released by</th>
	                        			<th>Released Type</th>
	                        			<th>Status</th>
	                        		</tr>                        			
                        		</thead>
                        		<tbody>
                        			<?php foreach ($recs as $r): ?> 
                        				<tr
                        					<?php if($r->tr_serveRecStatus=='received'): ?>
                        						onclick="window.location='#/transfer-view/<?php echo $r->tr_servedid; ?>'"			
                        					<?php else: ?>
                        						onclick="window.location='#/transfer-receiving/<?php echo $r->tr_servedid; ?>'"
                        					<?php endif; ?>
                        				>
	                        				<td><?php echo $r->tr_serverelnum; ?></td>
	                        				<td><?php echo _dateFormat($r->t_reqdatereq); ?></td>
	                        				<td><?php echo ucwords($r->store_name); ?></td>
	                        				<td><?php echo _dateFormat($r->tr_servedate); ?></td>
	                        				<td>
	                        					<?php 
	                        						$table = 'transfer_request_served_items ';
	                        						$select = 'IFNULL(SUM(denomination.denomination),0.00) as totamt';
	                        						$where = "transfer_request_served_items.trs_served='".$r->tr_servedid."'";
	                        						$join = 'LEFT JOIN
															gc
														ON
															gc.barcode_no = transfer_request_served_items.trs_barcode
														LEFT JOIN
															denomination
														ON
															denomination.denom_id = gc.denom_id';
	                        						$limit = '';
	                        						$totamt = getSelectedData($link,$table,$select,$where,$join,$limit);

	                        						echo number_format($totamt->totamt,2);

	                        					?>
	                        				</td>
	                        				<td><?php echo ucwords($r->servedby); ?></td>
	                        				<td><?php echo ucwords($r->tr_serveStatus); ?></td>
	                        				<td><?php echo ucwords($r->tr_serveRecStatus); ?></td>
                        				</tr>
                        			<?php endforeach; ?>
                        		</tbody>
                        	</table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
	<script type="text/javascript">
		var nowTemp = new Date();
		var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

		var checkin = $('#dp1').datepicker({

		    beforeShowDay: function (date) {
		        return date.valueOf() >= now.valueOf();
		    },
		    autoclose: true

		});
		$('.denfield').inputmask("integer", { allowMinus: false,autoGroup: true, groupSeparator: ",", groupSize: 3,placeholder:'0' });
	    $('input#input-file').fileinput({
	      'allowedFileExtensions' : ['jpg','png','jpeg']
	    });

		$.extend( $.fn.dataTableExt.oStdClasses, {	  
		    "sLengthSelect": "selectsup"
		});
	    $('#list,#listreq,#recreq').dataTable( {
	        "pagingType": "full_numbers",
	        "ordering": false,
	        "processing": true
	    });

		$("input[id^=num]").keyup(function(){
			var sum = 0, sum1=0;
			$('.denfield').each(function(){
				var inputs = $(this).val();
				inputs = inputs.replace(/,/g , "");
				sum = sum + inputs;
				var dnid = $(this).attr('id').slice(3);
				mul = inputs * $("#m"+dnid).val();
				sum1 = sum1 +mul;
			});
			$('span#totalAmtLbl').text(addCommas(sum1)+".00");
			$('input#totalAmt').val(sum1);
		});

		$('.form-container').on('submit','form#gcTransferForm',function(event){
			event.preventDefault();
			$('.response').html('');
			var hasDenom = false;
			var formURL = $(this).attr('action'), formData = new FormData($(this)[0]);	

			$('.denfield').each(function(){
				if($(this).val()!=0)
				{
					hasDenom = true;
					return false;
				}
			});

			if(!hasDenom)
			{
				$('.response').html('<div class="alert alert-danger" id="danger-x">Please input denomination quantity field.</div>');
				return;
			}

			if($('#reqnum').val().trim()=="")
			{
				$('.response').html('<div class="alert alert-danger" id="danger-x">Request number missing.</div>');
				return;
			}

			if($('#dp1').val().trim()=="")
			{
				$('.response').html('<div class="alert alert-danger" id="danger-x">Please fillup required fields.</div>');
				return;				
			}

	        BootstrapDialog.show({
	        	title: 'Confirmation',
	            message: 'Are you sure you want to submit GC Request?',
	            closable: true,
	            closeByBackdrop: false,
	            closeByKeyboard: true,
	            onshow: function(dialog) {
	               	$("button#btn").prop("disabled",true);
	            },
	            onhidden: function(dialog){
	            	$("button#btn").prop("disabled",false);
	            },
	            buttons: [{
	                icon: 'glyphicon glyphicon-ok-sign',
	                label: 'Yes',
	                cssClass: 'btn-primary',
	                hotkey: 13,
	                action:function(dialogItself){	
	                	$buttons = this;
	                	$buttons.disable();                	
	                	dialogItself.close();

						$.ajax({
				    		url:formURL,
				    		type:'POST',
							data: formData,
							enctype: 'multipart/form-data',
						    async: true,
						    cache: false,
						    contentType: false,
						    processData: false,
							beforeSend:function(){
								$('#processing-modal').modal('show');
							},
							success:function(data)
							{
								$('#processing-modal').modal('hide');
								console.log(data);
								var data = JSON.parse(data);
								if(data['st'])
								{
									var dialog = new BootstrapDialog({
						            message: function(dialogRef){
						            var $message = $('<div>Request Successfully Saved.</div>');			        
						                return $message;
						            },
						            closable: false
							        });
							        dialog.realize();
							        dialog.getModalHeader().hide();
							        dialog.getModalFooter().hide();
							        dialog.getModalBody().css('background-color', '#0088cc');
							        dialog.getModalBody().css('color', '#fff');
							        dialog.open();
							        setTimeout(function(){
				                    	dialog.close();
				               		}, 1500);
				               		setTimeout(function(){
				                    	window.location.href="index.php";
				               		}, 1700);	
								}
								else 
								{
									$('.response').html('<div class="alert alert-danger" id="danger-x">'+data['msg']+'</div>');
								}															
							}
						});

	                }
	            }, {
	            	icon: 'glyphicon glyphicon-remove-sign',
	                label: 'No',
	                action: function(dialogItself){
	                    dialogItself.close();
	                }
	            }]
	        });

			return false;
		});

	</script>

	<?php
}

function _updateGCTransfer($link,$reqid,$todays_date)
{

	$locs = getStores($link);

	$denoms = getAllDenomination($link);

	$table = 'transfer_request';
	$select = '	transfer_request.tr_reqid,
		transfer_request.t_reqnum,
		transfer_request.t_reqstoreto,
		transfer_request.t_reqdatereq,
		transfer_request.t_reqdateneed,
		transfer_request.t_reqremarks,
		transfer_request.t_reqby,
		stores.store_name';
	$where = "transfer_request.tr_reqid='".$reqid."'";
	$join = 'INNER JOIN
			stores
		ON
			stores.store_id = transfer_request.t_reqstoreto';
	$limit = '';

	$request = getSelectedData($link,$table,$select,$where,$join,$limit);

	$table = 'documents';
	$select = '*';
	$where = "doc_type = 'Transfer Request'
		AND
			doc_trid = '".$reqid."'";
	$join = '';
	$limit = '';

	$docs = getAllData($link,$table,$select,$where,$join,$limit);

	?>
		<div class="row form-container">
	    	<div class="col-md-12">
	            <div class="panel with-nav-tabs panel-info">
	                <div class="panel-heading">
	                		<button class="btn pull-right" onclick="window.location='#/gctransferList'">Back</button>
	                    <ul class="nav nav-tabs">
	                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Update Transfer Request</a></li>
	                    </ul>	                    
	                </div>
	                <div class="panel-body">
	                    <div class="tab-content">
	                        <div class="tab-pane fade in active" id="tab1default">
	                        	<div class="row form-container">	                        		             	
                        	<div class="row form-container">
                        		<form action="../ajax.php?action=updatetransfergcrequest" method="POST" id="updategcTransferForm" enctype="multipart/form-data">   
                        			<div class="col-sm-12">
		                        		<div class="col-sm-3">
			                            	<div class="form-group">
			                            		<input type="hidden" name="reqtype" value="1">
			                            		<input type="hidden" id="reqid" name="reqid" value="<?php echo $request->tr_reqid; ?>">
			                              		<label class="nobot">Transfer Request #</label>   
			                              		<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo $request->t_reqnum; ?>" id="reqnum" name="reqnum">  
			                            	</div>          
			                                <div class="form-group">
												<label class="nobot">Date Requested</label> 
												<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo _dateFormat($request->t_reqdatereq); ?>">                        
			                                </div>

			                                <div class="form-group">
												<label class="nobot">Date Updated</label> 
												<input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo _dateFormat($todays_date); ?>">                                 
			                                </div>

			                                <div class="form-group">
			                                	<label class="nobot"><span class="requiredf">*</span>Date Needed:</label>
			                                	<input type="text" class="form form-control inptxt input-sm ro bot-6" id="dp1" data-date-format="MM dd, yyyy" name="date_needed" readonly="readonly" required value="<?php echo _dateFormat($request->t_reqdateneed); ?>">
			                                </div>	

			                                <?php if(count($docs) > 0): ?>
                                                <div class="form-group">
                                                    <label class="nobot">Document(s) Uploaded</label>
                                                    <table class="table docstable" id="lightgallery">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>Remove</th>
                                                            </tr>
                                                        </thead>
                                                        <?php foreach ($docs as $d): ?>
                                                        <tr >
                                                            <td class="selector"  data-src="../assets/images/<?php echo $d->doc_fullpath;?>">
                                                                <a href="" class="thumbnail">
                                                                <img class="img-responsive img-table-display" src="../assets/images/<?php echo $d->doc_fullpath; ?>">
                                                                </a>
                                                            </td>
                                                            <td class="padleft">
                                                                <input type="checkbox" name="images[]" value="<?php echo $d->doc_fullpath; ?>">
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    </table>
                                                </div>
                                            <?php endif; ?>

			                                <div class="form-group">
			                                	<label class="nobot">Upload Document</label> 
			                                	<input id="input-file" class="file" type="file" name="docs[]" multiple>
			                                </div>		                            	                                    
		                        		</div> 
										<div class="col-sm-4">
											<div class="form-group">												
												<label class="nobot"><span class="requiredf">*</span>To Location</label>   
												<select class="form-control input-sm inptxt" id="store-selected" name="storeallo" autofocus="" required="">
                									<option value="<?php echo $request->t_reqstoreto; ?>"><?php echo ucwords($request->store_name); ?></option>
                									<?php foreach ($locs as $l): ?>
                										<?php if($request->t_reqstoreto!=$l->store_id): ?>
	                										<?php if($l->store_id!=$_SESSION['gc_store']): ?>
	                											<option value="<?php echo $l->store_id;  ?>"><?php echo ucwords($l->store_name); ?></option>
	                										<?php endif; ?>
	                									<?php endif; ?>
                									<?php endforeach; ?>     
	                                            </select>
											</div>  
											<div class="form-group">
												<label class="nobot"><span class="requiredf">*</span>Remarks</label> 
												<input type="text" class="form-control inptxt input-sm" name="remarks" autocomplete="off" required="" value="<?php echo $request->t_reqremarks; ?>">
											</div>
										</div>	
										<div class="col-sm-4">
											<div class="form-horizontal denomsbox">
												<div class="form-group">
													<label class="col-sm-6"><span class="requiredf">*</span>Denomination</label>
													<label class="col-sm-6"><span class="requiredf">*</span>Qty</label>			
												</div>
												<?php
													$reqtotal = 0; 
													foreach ($denoms as $d):					
													$qty = 0;
													$table = 'transfer_request_items';
													$select = 'tr_itemsqty';
													$where = "tr_itemsdenom='".$d->denom_id."'
														AND
															tr_itemsreqid='".$reqid."'";
													$join = '';
													$limit = '';		
													$qty_sel = getSelectedData($link,$table,$select,$where,$join,$limit);
													if(count($qty_sel) > 0)
													{
														$qty = $qty_sel->tr_itemsqty;
														$sub = $qty * $d->denomination;
														$reqtotal+=$sub;
													}
												?>
													<div class="form-group">
														<label class="col-sm-6">&#8369 <?php echo number_format($d->denomination,2); ?></label>
														<div class="col-sm-6">
															<input type="hidden" id="m<?php echo $d->denom_id; ?>" value="<?php echo $d->denomination; ?>"/>
															<input class="form form-control inptxt denfield" id="num<?php echo $d->denom_id; ?>" value="<?php echo $qty; ?>" name="denoms<?php echo $d->denom_id; ?>" autocomplete="off">
														</div>
													</div>
												<?php endforeach; ?>
											</div>
											<div class="labelinternaltot">
												<input type="hidden" name="totalAmt" id="totalAmt" value="0">                        
												<label>Total: <span id="totalAmtLbl"><?php echo number_format($reqtotal,2); ?></span></label>
											</div>
											<div class="form-group">
												<label class="nobot">Updated By:</label> 
												<input type="text" readonly="readonly" class="form-control inptxt input-sm" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>"> 
											</div>

											<div class="response">
											</div>

											<div class="form-group">
												<div class="col-sm-offset-5 col-sm-7">
													<button type="submit" class="btn btn-block btn-primary" id="btn"> <span class="glyphicon glyphicon-share" aria-hidden="true"></span> Submit</button>
												</div>
											</div>
										</div>	                  
		                        	</div>  
                        		</form>                  		
                        	</div>                  					
	                        	</div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
		</div>	

		<script type="text/javascript">
			var nowTemp = new Date();
			var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

			var checkin = $('#dp1').datepicker({

			    beforeShowDay: function (date) {
			        return date.valueOf() >= now.valueOf();
			    },
			    autoclose: true

			});
			$('.denfield').inputmask("integer", { allowMinus: false,autoGroup: true, groupSeparator: ",", groupSize: 3,placeholder:'0' });
		    $('input#input-file').fileinput({
		      'allowedFileExtensions' : ['jpg','png','jpeg']
		    });

            $('#lightgallery').lightGallery({
                selector:'.selector'
            });

			$("input[id^=num]").keyup(function(){
				var sum = 0, sum1=0;
				$('.denfield').each(function(){
					var inputs = $(this).val();
					inputs = inputs.replace(/,/g , "");
					sum = sum + inputs;
					var dnid = $(this).attr('id').slice(3);
					mul = inputs * $("#m"+dnid).val();
					sum1 = sum1 +mul;
				});
				$('span#totalAmtLbl').text(addCommas(sum1)+".00");
				$('input#totalAmt').val(sum1);
			});

            $('.form-container').on('submit','#updategcTransferForm',function(event){
                event.preventDefault();
				var hasDenom = false;             
                $('.response').html('');
                var formURL = $(this).attr('action'), formData = new FormData($(this)[0]);  

                if($('#reqid').val().trim()=='')
                {
                    $('.response').html('<div class="alert alert-danger" id="danger-x">Invalid Request ID.</div>');
                    return false;
                }

				$('.denfield').each(function(){
					if($(this).val()!=0)
					{
						hasDenom = true;
						return false;
					}
				});

				if(!hasDenom)
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Please input denomination quantity field.</div>');
					return;
				}

				if(!hasDenom)
				{
					$('.response').html('<div class="alert alert-danger" id="danger-x">Please input denomination quantity field.</div>');
					return;
				}

		        BootstrapDialog.show({
		        	title: 'Confirmation',
		            message: 'Are you sure you want to update GC Request?',
		            closable: true,
		            closeByBackdrop: false,
		            closeByKeyboard: true,
		            onshow: function(dialog) {
		               	$("button#btn").prop("disabled",true);
		            },
		            onhidden: function(dialog){
		            	$("button#btn").prop("disabled",false);
		            },
		            buttons: [{
		                icon: 'glyphicon glyphicon-ok-sign',
		                label: 'Yes',
		                cssClass: 'btn-primary',
		                hotkey: 13,
		                action:function(dialogItself){	
		                	$buttons = this;
		                	$buttons.disable();                	
		                	dialogItself.close();

							$.ajax({
					    		url:formURL,
					    		type:'POST',
								data: formData,
								enctype: 'multipart/form-data',
							    async: true,
							    cache: false,
							    contentType: false,
							    processData: false,
								beforeSend:function(){
									$('#processing-modal').modal('show');
								},
								success:function(data)
								{
									$('#processing-modal').modal('hide');
									console.log(data);
									var data = JSON.parse(data);
									if(data['st'])
									{
										var dialog = new BootstrapDialog({
							            message: function(dialogRef){
							            var $message = $('<div>Request Successfully updated.</div>');			        
							                return $message;
							            },
							            closable: false
								        });
								        dialog.realize();
								        dialog.getModalHeader().hide();
								        dialog.getModalFooter().hide();
								        dialog.getModalBody().css('background-color', '#0088cc');
								        dialog.getModalBody().css('color', '#fff');
								        dialog.open();
								        setTimeout(function(){
					                    	dialog.close();
					               		}, 1500);
					               		setTimeout(function(){
					                    	window.location.href="index.php";
					               		}, 1700);	
									}
									else 
									{
										$('.response').html('<div class="alert alert-danger" id="danger-x">'+data['msg']+'</div>');
									}															
								}
							});

		                }
		            }, {
		            	icon: 'glyphicon glyphicon-remove-sign',
		                label: 'No',
		                action: function(dialogItself){
		                    dialogItself.close();
		                }
		            }]
		        });

                return false;
             });
		</script>
	<?php
}

function _pendingProductionRequestList($link)
{
	// $approved = approvedProductionRequest($link);
	$select = 'production_request.pe_id,
	    production_request.pe_num,
	    users.firstname,
	    users.lastname,
	    access_page.title,
	    production_request.pe_date_request,
	    production_request.pe_date_needed
	';
	$where = 'production_request.pe_status=0';
	$join = 'INNER JOIN
	          users
	        ON
	          users.user_id = production_request.pe_requested_by
	        INNER JOIN
	          access_page
	        ON
	          access_page.access_no = users.usertype';
	$limit = '';
	$gc = getAllData($link,'production_request',$select,$where,$join,$limit);

	?>
		<div class="row form-container">
	    	<div class="col-md-12">
	            <div class="panel with-nav-tabs panel-info">
	                <div class="panel-heading">
	                    <ul class="nav nav-tabs">
	                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Pending Production Request</a></li>
	                    </ul>	                    
	                </div>
	                <div class="panel-body">
	                    <div class="tab-content">
	                        <div class="tab-pane fade in active" id="tab1default">
	                        	<table class="table" id="storeRequestList">
	                        		<thead>
		                        		<tr>
						                    <th>PR No.</th>
						                    <th>Date Request</th>  
						                    <th>Total Amount</th>       
						                    <th>Date Needed</th>
						                    <th>Requested By</th>
						                    <th>Department</th>
		                        		</tr>                        			
	                        		</thead>
	                        		<tbody>
										<?php foreach ($gc as $key): ?>    
											<tr onclick="window.location='#/pending-production-request-list/<?php echo $key->pe_id; ?>'">
												<td><?= $key->pe_num; ?></td>
												<td><?= _dateFormat($key->pe_date_request); ?></td>
												<td>
													<?php
														$select = 'SUM(denomination.denomination * production_request_items.pe_items_quantity) as total';
														$where = 'production_request_items.pe_items_request_id='.$key->pe_id;
														$join = 'INNER JOIN
															denomination
														ON
															denomination.denom_id = production_request_items.pe_items_denomination';
														$tot = getSelectedData($link,'production_request_items',$select,$where,$join,'');
														echo number_format($tot->total,2);														
													?>
												</td>   
												<td><?= _dateFormat($key->pe_date_needed); ?></td>
												<td><?= ucwords($key->firstname.' '.$key->lastname); ?></td>
												<td><?= $key->title; ?></td>
											</tr>
										<?php endforeach; ?>  	                        		
	                        		</tbody>
	                        	</table>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
		</div>
		<script type="text/javascript">
		    $('#storeRequestList').dataTable( {
		        "pagingType": "full_numbers",
		        "ordering": false,
		        "processing": true
		    });		
		</script>
	<?php
}

function _pendingProductionRequest($link,$id,$todays_date)
{

	$select = 'production_request.pe_id,
		users.firstname,
		users.lastname,
		production_request.pe_file_docno,
		production_request.pe_date_needed,
		production_request.pe_remarks,
		production_request.pe_num,
		production_request.pe_date_request,
		production_request.pe_type,
		production_request.pe_group,
		access_page.title';
	$where = 'production_request.pe_id='.$id.'
		AND
		production_request.pe_status=0';
	$join = 'INNER JOIN
		users
		ON
		users.user_id = production_request.pe_requested_by
		INNER JOIN
		access_page
		ON
		access_page.access_no = users.usertype';
	$limit = 'ORDER BY 
		production_request.pe_id
		DESC
		LIMIT 1';
	$pr = getSelectedData($link,'production_request',$select,$where,$join,$limit);

	if(!count($pr) > 0)
	{
		exit();
	}
	
	$ngc = getNumofGCRequestBYProdID($link,$pr->pe_id);
	?>

    <div class="row">
    	<div class="col-sm-5">
	      	<div class="box box-bot">
				<div class="box-header"><h4><i class="fa fa-inbox"></i> Production Request Approval Form</h4></div>
				<div class="box-content form-container">
					<form action="../ajax.php?action=productionStat" method="POST" id="prodRequestFin" class="form-horizontal">
						<input type="hidden" value="<?php echo $pr->pe_id; ?>" name="prodId" id="prodId">
						<input type="hidden" value="<?php echo $pr->pe_type; ?>" name="protype" id="protype">
						<input type="hidden" value="<?php echo $pr->pe_group; ?>" name="progroup" id="progroup">
						<div class="form-group">
							<label class="col-sm-5 control-label"><span class="requiredf">*</span>Request Status:</label>
							<div class="col-sm-7">
	                            <select id="status" class="form form-control input-sm reqfield inptxt" name="status" required autofocus>
	                                <option value="">-Select-</option>
	                                <option value="1">Approved</option>
	                                <option value="2">Cancel</option>
	                            </select>  
							</div>
						</div><!-- end form group -->
						
						<div class="form-group">
							<label class="col-sm-5 control-label newProdStatus">Date Appr./Cancel:</label>
							<div class="col-sm-7">
								<input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo _dateFormat($todays_date); ?>">
							</div>
						</div><!-- end form group -->
						<div class="hide-cancel">
							<div class="form-group">
								<label class="col-sm-5 control-label">Upload Document:</label>
								<div class="col-sm-7">
									<input type="file" id="upload" class="form-control input-sm" name="pic[]" accept="image/*" />
								</div>
							</div><!-- end form group -->	
							<div class="form-group">
								<label class="col-sm-5 control-label"><span class="requiredf">*</span>Remarks:</label>
								<div class="col-sm-7">									
									<textarea class="form form-control input-sm inptxt" name="remark" id="remark" required></textarea> 
								</div>
							</div><!-- end form group -->
							<div class="form-group">
								<label class="col-sm-5 control-label"><span class="requiredf">*</span>Checked by:</label>
								<div class="col-sm-7">
									<div class="input-group">
										<input name="checked" id="app-checkby" type="text" class="form-control input-sm reqfield inptxt" readonly="readonly" required="required">
	                                    <span class="input-group-btn">
	                                    	<button class="btn btn-info input-sm" id="checkbud" type="button" onclick="requestAssig(<?php echo $_SESSION['gc_usertype']; ?>,1);">
	                                    		<span class="glyphicon glyphicon-search"></span>
	                                        </button>
	                                    </span>
									</div><!-- input group -->
								</div>
							</div><!-- end form group -->	
							<div class="form-group">
								<label class="col-sm-5 control-label"><span class="requiredf">*</span>Approved by:</label>
								<div class="col-sm-7">
									<div class="input-group">
										<input name="approved" id="app-apprby" type="text" class="form-control input-sm reqfield inptxt" readonly="readonly" required="required">
	                                    <span class="input-group-btn">
	                                    	<button class="btn btn-info input-sm" id="approvedbud" type="button" onclick="requestAssig(<?php echo $_SESSION['gc_usertype']; ?>,2);">
	                                    		<span class="glyphicon glyphicon-search"></span>
	                                        </button>
	                                    </span>
									</div><!-- input group -->
								</div>
							</div><!-- end form group -->
						</div>
							<div class="form-group">
								<label class="col-sm-5 control-label label-prepared">Prepared by:</label>
								<div class="col-sm-7">
									<input typ="text" readonly="readonly" class="form form-control input-sm inptxt" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>" />
								</div>
							</div><!-- end form group -->
						<div class="form-group">
							<div class="col-sm-offset-8 col-sm-4">
								<button id="btn" class="btn btn-block btn-primary"><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span> Submit</button>
							</div>
						</div><!-- end form group -->			
					</form>
					<div class="response">
					</div>
				</div>
			</div>
		</div>
    	<div class="col-sm-7">
	      	<div class="box box-bot">
				<div class="box-header"><h4><i class="fa fa-inbox"></i> Production Request Details</h4></div>
				<div class="box-content form-container">
					<div class="row">
						<div class="col-sm-9">
							<div class="form-horizontal">
								<div class="form-group">
									<label class="col-sm-6 control-label">PE no.:</label>
									<div class="col-sm-6">
										<input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo $pr->pe_num; ?>">
									</div>
								</div><!-- end form group -->
								<div class="form-group">
									<label class="col-sm-6 control-label">Department:</label>
									<div class="col-sm-6">
										<input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo $pr->title; ?>">
									</div>
								</div><!-- end form group -->
								<?php if($pr->pe_group !=0):?>
								<div class="form-group">
									<label class="col-sm-6 control-label">Promo Group:</label>
									<div class="col-sm-6">
										<input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo 'Group '.$pr->pe_group; ?>" readonly="readonly">
									</div>
								</div><!-- end form group -->
								<?php endif;?>
								<div class="form-group">
									<label class="col-sm-6 control-label">Date Requested:</label>
									<div class="col-sm-6">
										<input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo _dateFormat($pr->pe_date_request); ?>">
									</div>
								</div><!-- end form group -->
								<div class="form-group">
									<label class="col-sm-6 control-label">Time Requested:</label>
									<div class="col-sm-6">
										<input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo _timeFormat($pr->pe_date_request); ?>">
									</div>
								</div><!-- end form group -->
								<div class="form-group">
									<label class="col-sm-6 control-label">Date Needed:</label>
									<div class="col-sm-6">
										<input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo _dateFormat($pr->pe_date_needed); ?>">
									</div>
								</div><!-- end form group -->
								<?php if(!empty($pr->pe_file_docno)): ?>
								<div class="form-group">
									<label class="col-sm-6 control-label">Request Document:</label>
									<div class="col-sm-6">
										<a class="btn btn-block btn-default" href='../assets/images/productionRequestFile/download.php?file=<?php echo $pr->pe_file_docno; ?>'><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download</a>
									</div>
								</div><!-- end form group -->
								<?php endif; ?>
								<div class="form-group">
									<label class="col-sm-6 control-label">Remarks:</label>
									<div class="col-sm-6">
										<input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo ucwords($pr->pe_remarks); ?>">
									</div>
								</div><!-- end form group -->
								<div class="form-group">
									<label class="col-sm-6 control-label">Requested by:</label>
									<div class="col-sm-6">
										<input type="text" class="form-control input-sm inptxt" readonly="readonly" value="<?php echo ucwords($pr->firstname.' '.$pr->lastname); ?>">
									</div>
								</div><!-- end form group -->
								<div class="col-sm-offset-2 col-sm-10">

				                    <table class="table table-responsive table-production-request">
				                    	<thead>
				                    		<th>Denomination</th>
				                    		<th>Quantity</th>
				                    		<th></th>
				                    	</thead>
				                    	<tbody>
			                            <?php
			                                $total = 0; 
			                                foreach ($ngc as $gc):?>
			                                <tr>
			                                    <?php
			                                        $subtotal = $gc->denomination * $gc->pe_items_quantity; 
			                                        $total = $total + $subtotal;
			                                    ?>
			                                    <td><label>&#8369 <?php echo number_format($gc->denomination,2); ?></label></td>
			                                    <td><?php echo $gc->pe_items_quantity; ?></td>
			                                    <td>&#8369 <?php echo number_format($subtotal,2); ?></td>
			                                </tr>
			                            <?php endforeach; ?>
			                                <tr>
			                                    <td></td>
			                                    <td><label>Total</label></td>
			                                    <td>&#8369 <?php echo  number_format($total,2); ?></td>
			                                </tr>
				                    	</tbody>
				                    </table>   

								</div>
							</div>
						</div><!-- end of details -->
					</div>
				</div>
			</div>
		</div>
    </div><!-- end row -->

    <script type="text/javascript">

		$('.form-container').on('change','select#status',function(){
			var status = $(this).val();		
			if(status==2)
			{
	 			$('.hide-cancel').hide();
				// $('.hide-cancel input#upload').prop('required',false);
				$('.hide-cancel input#remark, textarea#remark').prop('required',false);
				$('.label-prepared').text('Cancelled By:');
			}
			else 
			{
				$('.hide-cancel').show();
				// $('.hide-cancel input#upload').prop('required',true);
				$('.hide-cancel input#remark').prop('required',true);
				$('.label-prepared').text('Prepared By:')
			}

			if(status==0)
			{
				$('.newProdStatus').text('Date Approved/Cancel:');
			}
			else if(status==1)
			{
				$('.newProdStatus').text('Date Approved:');
			}
			else if(status==2)
			{
				$('.newProdStatus').text('Date Cancelled:');
			}
		});	

		$('.form-container').on('submit','form#prodRequestFin',function(){	
			$('.response').html('');
			var formUrl = $(this).attr('action'), formData = new FormData($(this)[0]);
			var hasEmpty = false;
			var confirmmsg = '', prid = $('#prodId').val();
			var status = $('#status').val();
			if(status==1)
			{
				$('.reqfield').each(function(){
					var fld = $(this).val().trim();
					if(fld=='')
					{
						hasEmpty = true;
						return;
					}
				});

				if(!hasEmpty)
				{
					confirmmsg = 'approved production request?';
				}
				else
				{
					$('.response').html('<div class="alert alert-danger danger-x">Please fill out all <span class="requiredf">*</span>required fields.</div>');
					return false;
				}
			}
			else if(status==2)
			{
				confirmmsg = 'cancel production request?';
			}

	        BootstrapDialog.show({
	        	title: 'Confirmation',
	            message: 'Are you sure you want to '+confirmmsg,
	            closable: true,
	            closeByBackdrop: false,
	            closeByKeyboard: true,
	            onshow: function(dialog) {
	                // dialog.getButton('button-c').disable();
	            },
	            onshown: function(dialog) {
	            	$('button#btn').prop('disabled',true);
	            },
	            onhidden: function(dialog) {
	            	$('button#btn').prop('disabled',false);
	            },
	            buttons: [{
	                icon: 'glyphicon glyphicon-ok-sign',
	                label: 'Yes',
	                cssClass: 'btn-primary',
	                action:function(dialogItself){  
	                	$buttons = this;
	                	$buttons.disable();
	                	dialogItself.close();
					    	$.ajax({
					    		url:formUrl,
					    		type:'POST',
								data: formData,
								enctype: 'multipart/form-data',
							    async: true,
							    cache: false,
							    contentType: false,
							    processData: false,
					    		beforeSend:function(){

					    		},
					    		success:function(data){
					    			console.log(data);
				        			var data = JSON.parse(data);
				        			if(data['st'])
				        			{
										var dialog = new BootstrapDialog({
							            message: function(dialogRef){
							            var $message = $('<div>'+data['msg']+'</div>');			        
							                return $message;
							            },
							            closable: false
								        });
								        dialog.realize();
								        dialog.getModalHeader().hide();
								        dialog.getModalFooter().hide();
								        dialog.getModalBody().css('background-color', '#86E2D5');
								        dialog.getModalBody().css('color', '#000');
								        dialog.open();
								        setTimeout(function(){
					                    	dialog.close();
					               		}, 1500);
					               		setTimeout(function(){
					                    	window.location.href = 'index.php';
					               		}, 1700);			        				
				        			} 
				        			else
				        			{
						    			$('.response').html('<div class="alert alert-danger dangerxx">'+data['msg']+'</div>');
						    			timeoutmsg();
						    		}
					    		}
					    	});
	                }
	            }, {
	            	icon: 'glyphicon glyphicon-remove-sign',
	                label: 'No',
	                action: function(dialogItself){
	                    dialogItself.close();
	                }
	            }]
	        });

			return false;
		});
    </script>

	<?php
}

function _approvedProductionRequestList($link)
{
	$approved = approvedProductionRequest($link);
	?>
		<div class="row form-container">
	    	<div class="col-md-12">
	            <div class="panel with-nav-tabs panel-info">
	                <div class="panel-heading">
	                    <ul class="nav nav-tabs">
	                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Approved Production Request</a></li>
	                    </ul>	                    
	                </div>
	                <div class="panel-body">
	                    <div class="tab-content">
	                        <div class="tab-pane fade in active" id="tab1default">
								<table class="table table-adjust" id="appprodreq">
									<thead>
										<tr>
											<th>PR No.</th>
											<th>Date Request</th>         
											<th>Date Needed</th>
											<th>Requested By</th>
											<th>Date Approved</th>
											<th>Approved By</th>
											<th></th>   
										</tr>
									</thead>
									<tbody class="store-request-list">
										<?php foreach ($approved as $key): ?>                
										<tr>
											<td><?= $key->pe_num; ?></td>
											<td><?= _dateFormat($key->pe_date_request); ?></td>
											<td><?= _dateFormat($key->pe_date_needed); ?></td>
											<td><?= ucwords($key->firstname.' '.$key->lastname); ?></td>
											<td><?= _dateFormat($key->ape_approved_at); ?></td>
											<td><?= ucwords($key->ape_approved_by); ?></td>
											<td><button type="button" onclick="approvedProductionRequest(<?php echo $key->pe_id; ?>);" class="btn btn-warning btn-warning-o app-pro"><span class="glyphicon glyphicon-search"></span> View</button>
										</tr>
										<?php endforeach; ?>                  
									</tbody>                
								</table>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
		</div>

		<script type="text/javascript">
		    $('#appprodreq').dataTable( {
		        "pagingType": "full_numbers",
		        "ordering": false,
		        "processing": true
		    });
		</script>
	<?php
}

function _cancelledProductionRequestList($link)
{
	$gcan = getAllCancelledProductionRequest($link);
	?>
		<div class="row form-container">
	    	<div class="col-md-12">
	            <div class="panel with-nav-tabs panel-info">
	                <div class="panel-heading">
	                    <ul class="nav nav-tabs">
	                        <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Cancelled Production Request</a></li>
	                    </ul>	                    
	                </div>
	                <div class="panel-body">
	                    <div class="tab-content">
	                        <div class="tab-pane fade in active" id="tab1default">
								<table class="table table-adjust" id="appbudgetreq">
									<thead>
										<tr>
											<th>PR No.</th>
											<th>Date Requested</th>
											<th>Date Needed</th>                                         
											<th>Prepared By</th>
											<th>Date Cancelled</th>
											<th>Cancelled By</th>
											<th></th>                
										</tr>
									</thead>
									<tbody class="store-request-list">
										<?php foreach ($gcan as $key): ?>             
											<tr>
												<td><?php echo $key->pe_num; ?></td>
												<td><?php echo _dateFormat($key->pe_date_request); ?></td>
												<td><?php echo _dateFormat($key->pe_date_needed); ?></td>
												<td><?php echo ucwords($key->lreqfname.' '.$key->lreqlname); ?></td>
												<td><?php echo _dateFormat($key->cpr_at); ?></td>
												<td><?php echo ucwords($key->lcanfname.' '.$key->lcanlname); ?></td>
												<td><button type="button" onclick="viewCancelledProductionRequest(<?php echo $key->pe_id; ?>)" class="btn btn-warning btn-warning-o app-pro-can"><span class="glyphicon glyphicon-search"></span> View</button></td>
											</tr>
										<?php endforeach ?>
									</tbody>
								</table>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
		</div>

		<script type="text/javascript">
		    $('#appbudgetreq').dataTable( {
		        "pagingType": "full_numbers",
		        "ordering": false,
		        "processing": true
		    });
		</script>

	<?php
}

?>
<script type="text/javascript">

	function lookupPaymentFund()
	{
		BootstrapDialog.show({
	    	title: 'Payment Fund Lookup',
	 	    cssClass: 'customer-internal',
	        closable: true,
	        closeByBackdrop: false,
	        closeByKeyboard: true,
	        message: function(dialog) {
	            var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
	            var pageToLoad = dialog.getData('pageToLoad');
				setTimeout(function(){
	            $message.load(pageToLoad);
				},1000);
	            return $message;
	        },
	        data: {
	            'pageToLoad': '../templates/regulargc.php?page=lookuppaymentfundins'
	        },
	        onshow: function(dialog) {
	            // dialog.getButton('button-c').disable();
	        },
	        onshown: function(dialogRef){
	        	setTimeout(function(){
	        		$('#company').focus();
	        	},1200);
	        	
	        }, 
	        onhidden: function()
	        {	        	       	
	        },
	        buttons: [{
	            icon: 'glyphicon glyphicon-remove',
	            label: 'Close',
	            cssClass: 'btn-default',
	            action:function(dialogItself){
	            	dialogItself.close();
	            }
	        }]

	    });
	}

	function lookupCustomerInstitGC()
	{
		BootstrapDialog.show({
	    	title: 'Customer Lookup',
	 	    cssClass: 'customer-internal',
	        closable: true,
	        closeByBackdrop: false,
	        closeByKeyboard: true,
	        message: function(dialog) {
	            var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
	            var pageToLoad = dialog.getData('pageToLoad');
				setTimeout(function(){
	            $message.load(pageToLoad);
				},1000);
	            return $message;
	        },
	        data: {
	            'pageToLoad': '../templates/regulargc.php?page=lookupcustomerinst'
	        },
	        onshow: function(dialog) {
	            // dialog.getButton('button-c').disable();
	        },
	        onshown: function(dialogRef){
	        	setTimeout(function(){
	        		$('#company').focus();
	        	},1200);
	        	
	        }, 
	        onhidden: function()
	        {	        	       	
	        },
	        buttons: [{
	            icon: 'glyphicon glyphicon-remove',
	            label: 'Close',
	            cssClass: 'btn-default',
	            action:function(dialogItself){
	            	dialogItself.close();
	            }
	        }]

	    });
	}	

</script>

<div class="modal modal-static fade loadingstyle" id="processing-modal" role="dialog" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog loadingstyle">
      <div class="text-center">
          <img src="../assets/images/ring-alt.svg" class="icon" />
          <h4 class="loading">Saving Data...</h4>
      </div>
    </div>
</div>