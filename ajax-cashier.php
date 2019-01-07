<?php

//type of transactions 

// 1 = cash

// 2  = credit card

// 3 = ar payment

// 5 = refund

// 6 = revalidation 

session_start();
include 'function.php';
include 'function-cashier.php';

if(isset($_GET['action'])){
	
	$action = $_GET['action'];

	if($action=='logincashier'){
		$response['st'] = 0;
		$username = $link->real_escape_string(trim($_POST['username']));
		$idnum = $link->real_escape_string(trim($_POST['idnum']));
		$password = $link->real_escape_string(trim($_POST['password']));

		$query_active = $link->query(
			"SELECT 
				app_settingvalue 
			FROM 
				app_settings 
			WHERE 
				app_tablename='system_status'
		");

		if($query_active)
		{
			if($query_active->num_rows > 0)
			{
				$row_status = $query_active->fetch_object();
				if($row_status->app_settingvalue == 'active')
				{
					if(!empty($username)&&
						!empty($idnum)&&
						!empty($password)){

						$username = trim($username);
						$idnum = trim($idnum);
						$password = md5(trim($password));

						$query = $link->query(
							"SELECT 
								ss_username,
								ss_id,
								ss_firstname,
								ss_lastname,
								ss_idnumber,
								ss_store,
								ss_status
							FROM 
								store_staff 
							WHERE 
								ss_username='$username' 
							AND 
								ss_password='$password' 
							AND 
								ss_idnumber='$idnum'
							AND
								ss_usertype='cashier'
						");

						if($query)
						{
							$n = $query->num_rows;
							if($n>0)
							{

								$row = $query->fetch_object();
								// if($row->ss)
								if($row->ss_status=='active')
								{
									$_SESSION['gccashier_username'] = $row->ss_username;
									$_SESSION['gccashier_id'] = $row->ss_id;
									$_SESSION['gccashier_fullname'] = $row->ss_firstname.' '.$row->ss_lastname;
									$_SESSION['gccashier_idnumber'] = $row->ss_idnumber; 
									$_SESSION['gccashier_store'] = $row->ss_store;
									$ip = get_ip_address();
									$query_logs = $link->query(
										"INSERT INTO 
											userlogs
										(
											logs_userid, 
											logs_datetime, 
											logs_usertype,
											logs_ip_address
										) 
										VALUES 
										(
											'".$row->ss_id."',
											NOW(),
											'2',
											'$ip'
										)
									");

									if($query_logs)
									{
										$response['st'] = 1;
									}
									else 
									{
										$response['msg'] = $link->error;
									}
									// $response['st'] = 1;
									// $response['cashier'] = $username;
									// $response['store'] = $row->ss_store;
								} 
								else 
								{
									$response['msg'] = 'Account is inactive please contact System Administrator.';
								}				
							} 
							else 
							{
								$response['msg'] = 'Invalid Login Credentials.';
							}
			 
						} 
						else 
						{
							$response['msg'] = $link->error;
						}
					}
					else 
					{
						$response['msg'] = 'Please fill all fields.';
					}
				}
				else 
				{
					$response['msg'] = 'System currently undergoing maintenance.';
				}
			}
			else 
			{
				$response['msg'] = 'System currently undergoing maintenance.';
			}
		}
		else 
		{
			$response['msg'] = $link->error;
		}

		echo json_encode($response);
	} 
	elseif($action=='loginmanager')
	{
		$response['st'] = 0;
		$username = $link->real_escape_string(trim($_POST['username']));
		$key = $link->real_escape_string(trim($_POST['managerkey']));
		$cashier = $_POST['cashier'];
		$store = $_POST['store'];

		if(!empty($username)&&
			!empty($key)&&
			!empty($cashier)&&
			!empty($store))
		{
			$key = md5($key);
			$query = $link->query(
					"SELECT 
						ss_username, 
						ss_status,
						ss_store
					FROM 
						store_staff 
					WHERE 
						ss_username='$username'
					AND 
						`ss_password`='$key'
					AND
						`ss_usertype`='manager'
			");

			if($query)
			{
				$n = $query->num_rows;
				if($n>0)
				{
					$row_m = $query->fetch_object();
					$managerstore = $row_m->ss_store;
					if ($managerstore == $store)
					{
						if($row_m->ss_status=='active')
						{
							$query_c = $link->query(
								"SELECT 
									ss_username,
									ss_id,
									ss_firstname,
									ss_lastname,
									ss_idnumber,
									ss_store 
								FROM 
									store_staff 
								WHERE 
									ss_username='$cashier'
							");

							if($query_c)
							{
								if($query_c->num_rows > 0)
								{
									$row = $query_c->fetch_object();
									$_SESSION['gccashier_username'] = $row->ss_username;
									$_SESSION['gccashier_id'] = $row->ss_id;
									$_SESSION['gccashier_fullname'] = $row->ss_firstname.' '.$row->ss_lastname;
									$_SESSION['gccashier_idnumber'] = $row->ss_idnumber; 
									$_SESSION['gccashier_store'] = $row->ss_store;
									$ip = get_ip_address();
									$query_logs = $link->query(
										"INSERT INTO 
											userlogs
										(
											logs_userid, 
											logs_datetime, 
											logs_usertype,
											logs_ip_address
										) 
										VALUES 
										(
											'".$row->ss_id."',
											NOW(),
											'2',
											'$ip'
										)
									");

									if($query_logs)
									{
										$response['st'] = 1;
									}
									else 
									{
										$response['msg'] = $link->error;
									}
								}
								else 
								{
									$response['msg'] = 'Cashier Not found.';
								}
							}
							else 
							{
								$response['msg'] = $link->error;
							}
						}
						else 
						{
							$response['msg'] = 'Account is inactive please contact System Administrator.';
						}
					}
					else 
					{
						$response['msg'] = 'Invalid Store Assigned.';
					}
				}
				else 
				{
					$response['msg'] = 'Invalid Login Credentials.';
				}
			}
			else 
			{
				$response['msg'] = $link->error;
			}
		} 
		else 
		{
			$response['msg'] = 'Please fill all fields.';
		}
		echo json_encode($response);		
	}
}

if(isset($_GET['request']))
{
	$request = $_GET['request'];
	if($request=='check')
	{
		$response['st'] = 0;
		$barcode_no = $_POST['value'];
	    $store = $_SESSION['gccashier_store'];
	    $ip = get_ip_address();
	    if(checkifEODperformed($link,$store,$ip))
	    {
	      $response['msg'] = 'Please perform End of Day first.';
	    }
	    else
	    {
	      //check if gc already scan
	      $query = $link->query(
	        "SELECT 
	          `ts_barcode_no` 
	        FROM 
	          `temp_sales` 
	        WHERE 
	          `ts_barcode_no`='$barcode_no' 
	      ");

	      $num_rows = $query->num_rows;
	      if($num_rows<1){
	        // check if gc already sold
	        $query_rel = $link->query(
	          "SELECT 
	            `strec_barcode`,
	            `strec_sold`,
	            `strec_transfer_out` 
	          FROM 
	            `store_received_gc`
	          WHERE
	            `strec_barcode` = '$barcode_no'
	          AND
	            `strec_storeid`='".$_SESSION['gccashier_store']."'  
	          ");

	        if($query_rel)
	        {
	          $n = $query_rel->num_rows;
	          if($n>0)
	          {
	            $row = $query_rel->fetch_object();
	            if($row->strec_sold =='')
	            {
	            	if($row->strec_transfer_out=='')
	            	{
		              $query_inst = $link->query(
		                "INSERT INTO 
		                  `temp_sales`
		                (
		                  `ts_barcode_no`, 
		                  `ts_date`, 
		                  `ts_time`, 
		                  `ts_cashier_id`
		                ) 
		                VALUES 
		                (
		                  '$barcode_no',
		                  NOW(),
		                  NOW(),
		                  '".$_SESSION['gccashier_id']."'
		                )
		              ");

		              if($query_inst)
		              {
		                $response['st'] = 1;
		              }
		              else 
		              {
		                $response['msg'] = $link->error;
		              }
		            }
		            else
		            {
		            	$response['msg'] = 'GC Barcode # '.$barcode_no.' already transfered.';
		            }             
	            }
	            else 
	            {
	              $response['msg'] = 'GC Barcode # '.$barcode_no.' already sold out.';
	            }
	          }
	          else 
	          {
	            $response['msg'] = 'GC Barcode # '.$barcode_no.' not found.';
	          }
	        }
	        else 
	        {
	          $response['msg'] = $link->error;
	        }
	      } else {
	        $response['msg'] = 'Duplicate Entry GC Barcode # '.$barcode_no;
	      }     
	    }
	    echo json_encode($response);
	} 
	elseif($request=='load')
	{
		$tablerows = 10;
		$gctemp = getTempBarcodesByCashier($link,$_SESSION['gccashier_id']);
		$gctemp_numrows = count($gctemp);
		if($gctemp_numrows > 10)
		{
			$tablerows = 0;
		}
		else 
		{
			$tablerows = $tablerows - $gctemp_numrows;
		}
		foreach ($gctemp as $key => $value): ?>
			<tr>
				<td class="btnsidetd"><button onclick="voidbyline(<?php echo $value['barcode']; ?>);" class="btnside">></button></td>
				<td class="barcodetd"><?php echo $value['barcode']; ?></td>
				<td class="typetd"><?php echo $value['type']; ?></td>
				<td class="denomtd"><?php echo number_format($value['denomination'],2); ?></td>
				<td class="disctypetd"><?php echo $value['disctype']; ?></td>
				<td class="discprcnttd"><?php echo $value['percent']; ?></td>
				<td class="discamttd"><?php echo $value['discamount']; ?></td>
				<td class="netamttd"><?php echo number_format($value['netamt'],2); ?></td>
			</tr>			
		<?php endforeach; ?>
		<?php for($x=0; $x<$tablerows; $x++): ?>
			<tr>
				<td class="btnsidetd"></td>
				<td class="barcodetd"></td>
				<td class="typetd"></td>
				<td class="denomtd"></td>
				<td class="disctypetd"></td>
				<td class="discprcnttd"></td>
				<td class="discamttd"></td>
				<td class="netamttd"></td>
			</tr>
		<?php endfor; ?>
			<script>
				  $('table tbody._barcodes tr td button').blur(
				      function(){
				         $(this).closest('tr').css('background-color','white');
				    }).focus(function() {
				    $(this).closest('tr').css('background-color','yellow');
				  });
			</script>
		<?php
	} 
	elseif ($request=='total') 
	{
		$total = checkTotal($link);
		if(is_null($total))
		{						
			echo '₱ 0.00';
		} else {
			echo '₱ '.number_format($total,2);
		}

	} 
	elseif($request=='cashpayment')
	{
		$cash = $_POST['cash'];
		$total_charge = $_POST['total_charge'];
		$change = $cash - $total_charge;
		$response['stat'] = 0;

		$ip = get_ip_address();

		$query = $link->query(
			"SELECT 
				* 
			FROM 
				`transaction_stores` 
			WHERE 
				`trans_store`='".$_SESSION['gccashier_store']."'
			ORDER BY 
				`trans_sid`
			DESC
		");

		$link->autocommit(false);

		$n = $query->num_rows;
		if($n>0){
			$row = $query->fetch_assoc();
			$row = $row['trans_number'];
			$row++;
			$tn = sprintf("%010d", $row);
        } else {             
        	$tn = '0000000001';         
        }

        $query_ins = $link->query(
        	"INSERT INTO 
        		`transaction_stores`
        	(
        		`trans_sid`, 
        		`trans_number`, 
        		`trans_cashier`, 
        		`trans_store`, 
        		`trans_datetime`,
        		`trans_type`,
        		`trans_ip_address`
        	) 
        	VALUES 
        	(
        		'',
        		'$tn',
        		'".$_SESSION['gccashier_id']."',
        		'".$_SESSION['gccashier_store']."',
        		NOW(),
        		1,
        		'$ip'
        	)
        ");
        if($query_ins)
        {
        	$id =  $link->insert_id;
        	$hasError = 0;

        	// get temp sales
        	$temp_sales = getTempSales($link,$_SESSION['gccashier_id']);
        	foreach ($temp_sales as $ts) 
        	{
				$query_ins_sales = $link->query(
					"INSERT INTO 
						`transaction_sales`
					(
						`sales_transaction_id`, 
						`sales_barcode`, 
						`sales_denomination`, 
						`sales_gc_type`
					) 
					VALUES 
					(
						'$id',
						'$ts->ts_barcode_no',
						'$ts->denom_id',
						'$ts->loc_gc_type'
					)	
				");

				if($query_ins_sales)
				{
					$query_tag = $link->query(
						"UPDATE 
							`store_received_gc` 
						SET 
							`strec_sold`='*', 
							`strec_return` =''
						WHERE 
							`strec_barcode` ='$ts->ts_barcode_no'        					 
					");

					if($query_tag)
					{
						//check if barcode has line discount
						$query_linedisc = $link->query(
							"SELECT
								`tsd_barcode`,
								`tsd_disc_type`,
								`tsd_disc_percent`,
								`tsd_disc_amt`,
								`tsd_cashier`,
								`tsd_discountby`
							FROM 
								`temp_sales_discountby` 
							WHERE 
								`tsd_barcode`='$ts->ts_barcode_no'
						");

						if($query_linedisc)
						{
							if($query_linedisc->num_rows > 0)
							{
								while ($row_disc = $query_linedisc->fetch_object()) 
								{
									$query_ins_disc = $link->query(
										"INSERT INTO 
											`transaction_linediscount`
										(
										    `trlinedis_sid`, 
										    `trlinedis_barcode`, 
										    `trlinedis_disctype`, 
										    `trlinedis_discpercent`, 
										    `trlinedis_discamt`, 
										    `trlinedis_by`
										) 
										VALUES 
										(
										    '$id',
										    '$ts->ts_barcode_no',
										    '$row_disc->tsd_disc_type',
										    '$row_disc->tsd_disc_percent',
										    '$row_disc->tsd_disc_amt',
										    '$row_disc->tsd_discountby'
										)
									");

									if(!$query_ins_disc)
									{
										$hasError = 1;
										break;
									}
								}

							}
						}
						else 
						{
							$hasError = 1;
							break; 						
						}										
					}
					else 
					{
						$hasError = 1;
						break; 							
					}
				}
				else 
				{
					$hasError = 1;
					break; 
				}        		
        	}

        	if(!$hasError)
        	{				  		
				$items = numRows($link,'temp_sales','ts_cashier_id',$_SESSION["gccashier_id"]);
				$stotal = checkTotalwithoutLineDiscount($link);
				$docdisc = docdiscount($link);
				$linedisc =  linediscountTotal($link);

				$query_pay = $link->query(
					"INSERT INTO 
						`transaction_payment`
					(
					    `payment_trans_num`, 
					    `payment_items`, 
					    `payment_stotal`, 
					    `payment_amountdue`, 
					    `payment_cash`, 
					    `payment_change`, 
					    `payment_docdisc`, 
					    `payment_linediscount`, 
					    `payment_tender`
					) 
					VALUES 
					(
					    '$id',
					    '$items',
					    '$stotal',
					    '$total_charge',
					    '$cash',
					    '$change',
					    '$docdisc',
					    '$linedisc',
					    '1'
					)
				");

				if($query_pay)
				{
					$docdiscby = getTempDiscountBy($link,$_SESSION['gccashier_id']);
					if(count($docdiscby)>0)
					{
						foreach ($docdiscby as $ddby) 
						{
							$query_docdiscby = $link->query(
								"INSERT INTO 
									`transaction_docdiscount`
								(
									`trdocdisc_trid`, 
									`trdocdisc_disctype`, 
									`trdocdisc_prcnt`,
									`trdocdisc_amnt`, 
									`trdocdisc_superby`
								) 
								VALUES 
								(
									'$id',
									'$ddby->docdis_discountype',
									'$ddby->docdis_pecentage',
									'$ddby->docdis_amt',
									'$ddby->docdis_superid'
								);
							");

							if(!$query_docdiscby)
							{
								$hasError = 1;
								break;
							}
						}
					}

					if(!$hasError)
					{
						//entry store sales
						$storesales = getStoreSales($link,$_SESSION['gccashier_id']);

						foreach ($storesales as $st) 
						{
							$query_entry = $link->query(
								"INSERT INTO 
									`entry_store_sales`
								(
									`ess_type`, 
									`ss_ref_id`, 
									`ess_denom`, 
									`ess_scode`, 
									`ess_pcs`, 
									`ess_amount`
								) 
								VALUES 
								(
									'SS',
									'$id',
									'$st->denom_id',
									'".$_SESSION['gccashier_store']."',
									'$st->counter',
									'$st->sums'
								)
							");

							if(!$query_entry)
							{
								$hasError = 1;
								break;
							}
						}

						if(!$hasError)
						{
							if(insertBudgetLedger($link,$id,'STORESALES','bdebit_amt',$stotal))
							{
								if($total_charge == $stotal - ($docdisc + $linedisc))
								{
									//delete temp sales

									$table = "temp_sales";
									$where = "WHERE `ts_cashier_id`=".$_SESSION['gccashier_id']."";
									if(deleteData($link,$table,$where))
									{
										//delete doc discount
										$table = 'temp_sales_discountby';
										$where = "WHERE `tsd_cashier`=".$_SESSION['gccashier_id']."";
										if(deleteData($link,$table,$where))
										{
											//delete all line disc 
											$table = 'temp_sales_docdiscount';
											$where = "WHERE `docdis_cashierid`=".$_SESSION['gccashier_id']."";
											if(deleteData($link,$table,$where))
											{
												$total = $stotal - ($docdisc+$linedisc);
												$discount = $docdisc+$linedisc;
												storeLedger($link,$id,1,$total,'GCS','GC Sales (Cash)',$_SESSION['gccashier_store'],$discount);

												$link->commit();

												$response['receipt'] = getstorereceiptstatus($link,$_SESSION['gccashier_store']);
												$response['stat'] = 1;
												$response['numitems'] = $items;
												$response['transactnum'] = $tn;										
												$response['amt_due'] = $total;
												$response['linedisc'] = $linedisc;
												$response['docdisc'] =  $docdisc;
												$response['cash'] = $cash;
												$response['change'] = $change;		
												$response['stotal'] = $stotal;							
											}
											else 
											{
												$response['msg'] = $link->error; 
											}
										}
										else 
										{
											$response['msg'] = $link->error; 
										}

									}
									else 
									{
										$response['msg'] = $link->error; 
									}
								}
								else
								{
									$response['msg'] = 'Wrong Calculation...';
								}
							}
							else 
							{
								$response['msg'] = $link->error;
							}
							

						}
						else 
						{
							$response['msg'] = $link->error; 
						}
					}
					else 
					{
						$response['msg'] = $link->error;
					}

				}
				else 
				{
					$response['msg'] = $link->error;
				}
        	}
        	else 
        	{
        		$response['msg'] = $link->error;
        	}
        }
        else 
        {
        	$response['msg'] = $link->error;
        }
        echo json_encode($response);

	} 
	elseif($request=='login')
	{
		$username = $_POST['username'];
		$password = $_POST['password'];
		
		$query = $link->query("SELECT * FROM users WHERE username='$username' AND password='$password'");
		$num_rows = $query->num_rows;

		if($num_rows>0){
			$row = $query->fetch_array();
			$row_userlevel =  $row['user_level'];
			$row_status = $row['status'];
			if($row_userlevel == '4' && $row_status=='1'){
				$_SESSION['id'] = $row['user_id'];		
				$_SESSION['user']  = $row['username'];
				$_SESSION['user_level'] = $row['user_level'];
				$_SESSION['fullname'] = $row['fullname'];
				$_SESSION['status'] = $row['status'];
				$_SESSION['gccashier_store_code'] = $row['assigned_store'];
				echo 'success';		
			} else {
				echo 'User Not Authorized '.$row_status.' '.$row_status;
			}
		} else {
			echo 'Username or password is incorrect.';
		}
		
	} elseif ($request == 'remove-item') {
		$barcode = $_POST['barcode'];
		$password = $_POST['userpass'];
		$query_user = "SELECT * FROM `users` WHERE username='".$_SESSION['gc_user']."' AND password='$password'";
		if($query= $link->query($query_user)){
			$num_rows = $query->num_rows;
			if($num_rows>0)
			{
				$query = "DELETE FROM `temp_sales` WHERE `ts_barcode_no`='$barcode'";
				if($link->query($query))
				{
					echo 'success';					
				}				
			} 
			else
			{
				echo 'Password is incorrect.';
			}
		} 
		else 
		{
			echo $link->error;
		}
	} 
	elseif($request == 'receipt')
	{
		// $query = "SELECT * FROM `temp_sales` 
		// 			INNER JOIN 
		// 			gc 
		// 			ON 
		// 			temp_sales.ts_barcode_no = gc.barcode_no 
		// 			INNER JOIN 
		// 			denomination
		// 			ON
		// 			gc.denom_id = denomination.denom_id
		// 			INNER JOIN
		// 			gc_location
		// 			ON
		// 			temp_sales.ts_barcode_no = gc_location.loc_barcode_no
		// 			INNER JOIN 
		// 			gc_type
		// 			ON
		// 			gc_location.loc_gc_type = gc_type.gc_type_id
		// 			WHERE 
		// 			`ts_cashier_id`='".$_SESSION['gccashier_id']."'

		// ";

		$gc = receiptItemsByCashier($link,$_SESSION['gccashier_id']);

		if(count($gc)>0)
		{
			echo '<table class="table resibo">';
			echo '<thead>';
				echo '<th>Barcode No </th>';				
				echo '<th class="receipt_items">Price</th>';
			echo '</thead>';
			echo '<tbody>';
			foreach ($gc as $key) 
			{
				echo '<tr>';
					echo '<td>'.$key->ts_barcode_no.'</td>';
					echo '<td class="receipt_items">₱ '.number_format($key->denomination,2).'</td>';
				echo '</tr>';
			}			
			echo '</tbody>';
			echo '</table>';
		}
	} 
	else if($request=='checkeodtrans')
	{
		$response['st'] = false;
		$emptyTrans = true;
		$error = false;
		$eos = true;
		$eod = false;
		$haseod = false;
		$hasbngtrans = false;

		//check beam and go transaction
		$querybng = $link->query(
			"SELECT 
				* 
			FROM 
				beamandgo_transaction 
			WHERE 
				bngver_storeid='".$_SESSION['gccashier_store']."'
			AND
				bngver_eod IS NULL
		");

		if($querybng)
		{
			if($querybng->num_rows>0)
			{
				//$response['st'] = 1;
				$hasbngtrans = true;
			}
		}
		else 
		{
			$error = true;
		}

		//check if has transaction
		$query_checkifhastransaction = $link->query(
			"SELECT 
				`trans_sid` 
			FROM 
				`transaction_stores` 
			WHERE 
				`trans_store` = '".$_SESSION['gccashier_store']."'
			AND
				DATE(`trans_datetime`) <= CURDATE()
		");

		if($query_checkifhastransaction)
		{
			if($query_checkifhastransaction->num_rows>0 || $hasbngtrans)
			{
				$emptyTrans = false;
			}		
		}
		else 
		{
			$error = true;			
		}

		// check if eos already processed
		$query = $link->query(
			"SELECT 
				`trans_sid` 
			FROM 
				`transaction_stores` 
			WHERE 
				`trans_store` = '".$_SESSION['gccashier_store']."'
			AND
				DATE(`trans_datetime`) <= CURDATE()
			AND
				`trans_yreport`='0'
			AND
				`trans_eos`=''
		");

		if($query)
		{
			if($query->num_rows>0)
			{
				//$response['msg'] = 'Please process end of shift first.';
				$eos = false;

			}
		}
		else 
		{			
			$error = true;
		}	

		$query = $link->query(
			"SELECT 
				`trans_sid` 
			FROM 
				`transaction_stores` 
			WHERE 
				`trans_store` = '".$_SESSION['gccashier_store']."'
			AND
				DATE(`trans_datetime`) <= CURDATE()
			AND
				`trans_yreport`='0'
			AND
				`trans_eos`!=''
		");

		if($query)
		{
			if($query->num_rows==0 && !$hasbngtrans)
			{
				//$response['msg'] = 'End of day already performed.';
				$eod = true;	
			}
		}
		else 
		{			
			$error = true;
		}	

		if($error)
		{	
			$response['msg'] = $link->error;
		}
		elseif($emptyTrans)
		{
			$response['msg'] = 'No Transaction Exist.';
		}
		elseif (!$eos) 
		{
			$response['msg'] = 'Please process end of shift first.';
		}
		elseif ($eod) 
		{
			$response['msg'] = 'End of day already performed.';
		}
		else 
		{
			$response['st'] = true;
		}
		echo json_encode($response);
	}
	elseif($request=='endofdaypos')
	{
		$response['st'] = 0;
		// $link->autocommit(FALSE);
		// if(!getTextFileBalances($link,$todays_date))
		// {
		// 	$response['msg'] = $link->error;
		// 	goto endofdaystop;
		// }

		// $response['msg'] = 'yeah';


		// $query = $link->query(
		// 	"UPDATE 
		// 		`transaction_stores` 
		// 	SET 
		// 		`trans_yreport`=1
		// 	WHERE 
		// 		`trans_store` = '".$_SESSION['gccashier_store']."'
		// 	AND
		// 		`trans_eos` = '*'
		// 	AND
		// 		`trans_datetime` LIKE '%$todays_date%'
		// 	AND
		// 		`trans_yreport`='0'
		// ");

		// $query = $link->query(
		// 	"SELECT 
		// 		* 
		// 	FROM 
		// 		`transaction_stores` 
		// 	WHERE 
		// 		`trans_store` = '".$_SESSION['gccashier_store']."'
		// 	AND
		// 		`trans_cashier` = '".$_SESSION['gccashier_id']."'
		// 	AND
		// 		`trans_datetime` LIKE '%$todays_date%'
		// 	AND
		// 		`trans_yreport`='0'
		// ");

		// if($query)
		// {
		// 	$n = $query->num_rows;

		// 	if($n==0){

		// 		$query_status = $link->query(
		// 			"SELECT 
		// 				* 
		// 			FROM 
		// 				`transaction_stores` 
		// 			WHERE 
		// 				`trans_store` = '".$_SESSION['gccashier_store']."'
		// 			AND
		// 				`trans_cashier` = '".$_SESSION['gccashier_id']."'
		// 			AND
		// 				`trans_datetime` LIKE '%$todays_date%'
		// 			AND
		// 				`trans_status`='0'
		// 		");	

		// 		if($query_status)
		// 		{

		// 			$s = $query_status->num_rows;

		// 			if($s>0)
		// 			{						
		// 				$query_eod = $link->query(
		// 					"INSERT INTO 
		// 						`transaction_endofday`
		// 					(
		// 						`eod_id`, 
		// 						`eod_store`, 
		// 						`eod_supervisor_id`, 
		// 						`eod_datetime`
		// 					)
		// 					VALUES 
		// 					(
		// 						'',
		// 						'".$_SESSION['gccashier_store']."',
		// 						'".$_SESSION['gc_super_id']."',
		// 						NOW()
		// 					)
		// 				");
		// 				if($query_eod)
		// 				{
		// 					$query_getxtfile = $link->query(
		// 						"SELECT 
		// 							`store_verification`.`vs_tf`,
		// 							`users`.`username`,
		// 							`store_verification`.`vs_barcode`
		// 						FROM 
		// 							`store_verification` 
		// 						INNER JOIN
		// 							`users`
		// 						ON
		// 							`store_verification`.`vs_by` = `users`.`user_id`
		// 						WHERE
		// 							`users`.`store_assigned`='".$_SESSION['gccashier_store']."'
		// 						AND
		// 							`store_verification`.`vs_date` = '$todays_date'
		// 					");

		// 					if($query_getxtfile)
		// 					{
		// 						//$x=0;
		// 					// 	$folder = 'textfiles/validation';
		// 					// 	while ($row = $query_getxtfile->fetch_object()) {
		// 					// 		$arr_f = [];
		// 					// 		$file = $folder.'/'.$row->vs_tf;
		// 					// 		if(checkFolder($folder)){
		// 					// 			$allowedExts = array("txt");
		// 					// 			$temp = explode(".", $file);
		// 					// 			$extension = end($temp);
		// 					// 			if((mime_content_type($file)=="text/plain")&&
		// 					// 			in_array($extension, $allowedExts))
		// 					// 			{
		// 					// 				$r_f = fopen($file,'r');
		// 					// 					while(!feof($r_f)) 
		// 					// 					{
		// 					// 						$arr_f[] = fgets($r_f);
		// 					// 					}
		// 					// 				fclose($r_f);
		// 					// 				$c = explode(",",$arr_f[4]);
		// 					// 				$c = $c[1];
		// 					// 				$ins = $link->query(
		// 					// 					"UPDATE 
		// 					// 						`store_verification` 
		// 					// 					SET 
		// 					// 						`vs_tf_used`='*',
		// 					// 						`vs_tf_balance`='$c' 
		// 					// 					WHERE 
		// 					// 						`vs_barcode`='$row->vs_barcode'													
		// 					// 				");
		// 					// 				$link->commit();
		// 					// 			}	
		// 					// 		}
		// 					// 	}
		// 						$link->commit();
		// 						echo 'success';		
		// 					}

		// 				} else {
		// 					echo $link->error;
		// 				}
		// 			}
		// 			else 
		// 			{
		// 				echo 'There is no transaction to perform end of day.';
		// 			}
		// 		}

		// 	} else {
		// 		echo 'Please print Y Report first.';
		// 	}

		// } 
		// else 
		// {
		// 	echo $link->error;
		// }
		endofdaystop:
		echo json_encode($response);
	} 
	elseif ($request=='totalitems') 
	{
		echo numRows($link,'temp_sales','ts_cashier_id',$_SESSION["gccashier_id"]);
	} 
	elseif ($request=='creditlist')
	{

		$query = $link->query("SELECT * FROM credit_cards");

		?>
			
			<select name="credit" class="form-control" id="credit">
				<option value="">-Select-</option>
				<?php while($row = $query->fetch_object()): ?>
					<option value="<?php echo $row->ccard_id; ?>"><?php echo ucwords($row->ccard_name); ?></option>
				<?php endwhile; ?>
			</select>

		<?php	

	} 
	elseif ($request=='supervisormode') 
	{
		$username = $link->real_escape_string(trim($_POST['uname']));
		$idnum = $link->real_escape_string(trim($_POST['idnum']));
		$key = trim($_POST['key']);
		if(!empty($username)&&
			!empty($idnum)&&
			!empty($key))
		{

			$key = md5($key);
			$query = $link->query(
				"SELECT 
					*
				FROM 
					`store_staff`
				WHERE 
					`ss_username`='$username'
				AND 
					`ss_password`='$key'
				AND 
					`ss_idnumber` = '$idnum'
				AND
					`ss_store` = '".$_SESSION['gccashier_store']."'
				AND 
					`ss_usertype`='manager'				
			");


			if($query)
			{

				$n = $query->num_rows;

				if($n>0)
				{
					$row = $query->fetch_object();
					$_SESSION['gc_super_id'] = $row->ss_id;					
					$_SESSION['gc_super'] = $username;
					echo 'success';
				} 
				else 
				{
					echo 'Username / ID Number / Password is incorrect.';
				}

			} else {
				echo $link->error;
			}

		} else {
			echo 'Please input all fields';
		}
	} 
	elseif ($request=='lookup') 
	{

		$search = true;

		$barcode = $_POST['barcode'];
		//check Gc if Verified
		if(checkGCIfVerfified($link,$barcode)){
			
			?>
				<div class="alert-success for-modal-found">GC Found</div>
				<div class="alert-success for-modal-found">
					Barcode Number: <span><?php echo $barcode; ?></span> already verified.
				</div>
			<?php

		} else {			
			$search = false;
		}

		if(!$search){
			//check GC if sold
			if(checkGCIfSoldOut($link,$barcode)){

				?>
				<div class="alert-success for-modal-found">GC Found</div>
				<div class="alert-success for-modal-found">
					Barcode Number: <span><?php echo $barcode; ?></span> already sold out.
				</div>
				<?php

				$search = true;
			}
		}

		if(!$search){
			//check GC if Available
			if(checkGCIfAvailable($link,$barcode)){

				$store = getStoreReleased($link,$barcode);

				?>
				<div class="alert-success for-modal-found">
					Barcode Number: <span><?php echo $barcode; ?></span> is available.
				</div>

				<?php
				echo 'Store: '.ucwords($store);
				$search = true;
			} else {
				?>
				<div class="alert-danger for-modal">
					Barcode Number: <span><?php echo $barcode; ?></span> Not Found.
				</div>

				<?php
			}
		}
	} 
	elseif($request=='voidline')
	{
		$response['stat'] = 0;
		$barcode = $_POST['barcodenum'];

		//get denom 
		$denom_id = getField($link,'denom_id','gc','barcode_no',$barcode);

		//check if barcode exist in temp sales
		$num = numRowsWhereTwo($link,'temp_sales','ts_barcode_no','ts_barcode_no','ts_cashier_id',$barcode,$_SESSION['gccashier_id']);

		if($num>0)
		{
			$link->autocommit(FALSE);
			$query_d = $link->query(
				"DELETE FROM 
					`temp_sales` 
				WHERE 
					`ts_barcode_no`='$barcode' 
				AND 
					`ts_cashier_id`='".$_SESSION['gccashier_id']."'
			");

			if($query_d)
			{
				$query_dd = $link->query(
					"DELETE FROM 
						`temp_sales_discountby` 
					WHERE 
						`tsd_barcode` = '$barcode'
				");

				if($query_dd)
				{
					$query_ins = $link->query(
						"INSERT INTO 
							`store_void_items`
						(
						    `svi_barcodes`, 
						    `svi_store`, 
						    `svi_cashier`, 
						    `svi_datetime`,
						    `svi_denom`
						) 
						VALUES 
						(
						    '$barcode',
						    '".$_SESSION['gccashier_store']."',
						    '".$_SESSION['gccashier_id']."',
						    NOW(),
						    '$denom_id'
						)
					");

					if($query_ins)
					{
						$link->commit();
						$response['stat'] = 1;
					}
					else
					{
						$response['msg'] = $link->error;
					}
				}
				else 
				{
					$response['msg'] = $link->error;
				}
			}
			else 
			{
				$response['msg'] = $link->error;
			}
		}
		else 
		{
			$response['msg'] = 'GC Barcode # '.$barcode.' not found.';
		}	

		echo json_encode($response);

	} 
	elseif ($request=='voidlinereval') 
	{
		$response['st'] = 0;
		$barcode = $_POST['barcode'];	
		$num = numRowsWhereTwo($link,'temp_reval','treval_barcode','treval_barcode','treval_by',$barcode,$_SESSION['gccashier_id']);
		if($num>0)
		{
			$query_del = $link->query(
				"DELETE FROM 
					temp_reval 
				WHERE 
					treval_barcode='".$barcode."'
			");

			if($query_del)
			{
				$total = 0;
				$gccount = 0;
				//get validation payment
				// $revalpayment = getSelectedData($link,'cashiering_options','revalidate_price',1,'','');
				// $gccount = numRows($link,'temp_reval','treval_by',$_SESSION['gccashier_id']);
				// $total = $gccount * $revalpayment->revalidate_price;

				$query_tot = $link->query(
				"SELECT 
					SUM(temp_reval.treval_charge) as totcharge,
					COUNT(temp_reval.treval_charge) as gccount
				FROM 
					temp_reval 
				WHERE 
					temp_reval.treval_by='".$_SESSION['gccashier_id']."'
				AND
					temp_reval.treval_store='".$_SESSION['gccashier_store']."'
				");

				if($query_tot)
				{
					$row = $query_tot->fetch_object();
					$total = $row->totcharge;
					$gccount = $row->gccount;
				}

				$response['st'] = 1;
				$response['total'] = number_format($total,2);
				$response['count'] = $gccount;
			}
			else 
			{
				$response['msg'] = $link->error;
			}

		}
		else 
		{
			$response['msg'] = 'GC Barcode # '.$barcode.' not found.';
		}
		echo json_encode($response);
	}
	elseif ($request=='voidall')
	{
		$hasError = false;
		$response['stat'] = 0;
		if(isset($_SESSION['gc_super']))
		{
			// $uname = $_SESSION['gc_super'];
			// $sup_id = getField($link,'ss_id','store_staff','ss_username',$uname);
			$superid = $_SESSION['gc_super_id'];
			$link->autocommit(FALSE);
			$query_temp = $link->query(
				"SELECT 
					`temp_sales`.`ts_barcode_no`,
					`gc`.`denom_id`,
					`gc_location`.`loc_store_id`
				FROM 
					`temp_sales` 
				INNER JOIN
					`gc`
				ON
					`temp_sales`.`ts_barcode_no` = `gc`.`barcode_no`
				INNER JOIN
					`gc_location`
				ON
					`temp_sales`.`ts_barcode_no` = `gc_location`.`loc_barcode_no`
				WHERE 
					`ts_cashier_id`='".$_SESSION['gccashier_id']."' 
				");

			while($row = $query_temp->fetch_object())
			{
				$query_ins = $link->query(
					"INSERT INTO 
						`store_void_items`
					(
						`svi_id`, 
						`svi_barcodes`, 
						`svi_transaction`, 
						`svi_store`, 
						`svi_denom`, 
						`svi_cashier`, 
						`svi_supervisor`, 
						`svi_datetime`
					) 
						VALUES 
					(
						'',
						'$row->ts_barcode_no',
						'',
						'$row->loc_store_id',
						'$row->denom_id',
						'".$_SESSION['gccashier_id']."',
						'$superid',
						NOW()
					)	
				");

				if(!$query_ins)
				{
					$hasError = true;
					break;
				}
			}

			if(!$hasError)
			{			
				$query_rem = $link->query(
					"DELETE FROM 
						`temp_sales` 
					WHERE 
						`ts_cashier_id`='".$_SESSION['gccashier_id']."'
				");

				if($query_rem)
				{
					//delete all line discount from this cashier
					$query_dellinedisc = $link->query(
						"DELETE FROM 
							`temp_sales_discountby` 
						WHERE 
							`tsd_cashier`='".$_SESSION['gccashier_id']."'
					");

					if($query_dellinedisc)
					{
						$query_deltransdisc = $link->query(
							"DELETE FROM 
								`temp_sales_docdiscount` 
							WHERE 
								`docdis_cashierid` = '".$_SESSION['gccashier_id']."' 
						");

						if($query_deltransdisc)
						{
							$link->commit();
							$response['stat'] = 1;
						}
						else 
						{
							$response['msg'] = $link->error;
						}

					}
					else 
					{
						$response['msg'] = $link->error;
					}					

				} 
				else 
				{
					$response['msg'] = $link->error;
				}
			}
			else 
			{
				$response['msg'] = $link->error;
			}

		}
		else 
		{
			$response['msg'] = 'You are not allowed to perform this action?.';		
		}

		echo json_encode($response);

	} 
	elseif ($request=='supervisorlogout')
	{
		unset($_SESSION['gc_super_id']);
		unset($_SESSION['gc_super']);
		echo 'success';
	} 
	elseif ($request=='returngc')
	{
		// return gc
		$gc = $link->real_escape_string(trim($_POST['barcode']));

		$query_ver = $link->query(
			"SELECT 
				* 
			FROM
				`store_verification`
			WHERE 
				`vs_barcode`='$gc' 
		");

		if($query_ver)
		{
			$n = $query_ver->num_rows;
			if($n<1)
			{
				$query_sales = $link->query(
					"SELECT 
						`strec_barcode` 
					FROM 
						`store_received_gc` 
					WHERE 
						`strec_barcode` = '$gc'
					AND
						`strec_sold` = '*'
					AND
						`strec_storeid` = '".$_SESSION['gccashier_store']."'					
				");

				if($query_sales)
				{
					$n_sales = $query_sales->num_rows;
					if($n_sales>0)
					{
						$response['message'] = 'success';
					} else  
					{
						$response['message'] = 'GC barcode number '.$gc.' not found.';
					}
				}

			} 
			else 
			{

				$response['message'] = 'GC barcode number '.$gc.' already been validated.';
			}
		}
		echo json_encode($response);
	} 
	elseif ($request=='xreport') 
	{
		echo 'xxx';		
	} 
	elseif ($request=='ccardpayment')
	{
		// credit card payment
		$response['stat'] = 0;
		$total_charge = $link->real_escape_string($_POST['charge']);
		$creditcard = $link->real_escape_string($_POST['credit']);
		$cardnumber = $link->real_escape_string($_POST['cardnumber']);
		$cardexpired = $link->real_escape_string($_POST['cardexpired']);
		$cardexpired = convertDateToSqlDate($cardexpired);
		$authcode = $link->real_escape_string($_POST['authcode']);
		$authcode = md5($authcode);

		$ip = get_ip_address();

		$query_getCCard = $link->query(
			"SELECT 
				`ccard_name` 
			FROM 
				`credit_cards` 
			WHERE 
				`ccard_id` = '$creditcard'
			LIMIT 1 
		");

		$row_getcc = $query_getCCard->fetch_object();

		$query = $link->query(
			"SELECT 
				* 
			FROM
				`store_staff`
			WHERE 
				`ss_id`='".$_SESSION['gccashier_id']."'
			AND
				`ss_password`='$authcode' 
		");				

		if($query){
			$n = $query->num_rows;

			if($n>0)
			{
				$query = $link->query(
					"SELECT 
						* 
					FROM 
						`transaction_stores` 
					WHERE 
						`trans_store`='".$_SESSION['gccashier_store']."'
					ORDER BY 
						`trans_sid`
					DESC
				");

				$link->autocommit(false);

				$n = $query->num_rows;
				if($n>0){
					$row = $query->fetch_assoc();
					$row = $row['trans_number'];
					$row++;
					$tn = sprintf("%010d", $row);
					

		        } else {             
		        	$tn = '0000000001';         
		        }

				$query_ins = $link->query(
					"INSERT INTO 
						`transaction_stores`
					(
						`trans_sid`, 
						`trans_number`, 
						`trans_cashier`, 
						`trans_store`, 
						`trans_datetime`, 
						`trans_status`,
						`trans_type`,
						`trans_ip_address`
					) 
					VALUES 
					(
						'',
						'$tn',
						'".$_SESSION['gccashier_id']."',
						'".$_SESSION['gccashier_store']."',
						NOW(),
						0,
						2,
						'$ip' 
					)
				");

				//
		        if($query_ins)
		        {
		        	$id =  $link->insert_id;
		        	$hasError = 0;

		        	// get temp sales
		        	$temp_sales = getTempSales($link,$_SESSION['gccashier_id']);
		        	foreach ($temp_sales as $ts) 
		        	{
						$query_ins_sales = $link->query(
							"INSERT INTO 
								`transaction_sales`
							(
								`sales_transaction_id`, 
								`sales_barcode`, 
								`sales_denomination`, 
								`sales_gc_type`
							) 
							VALUES 
							(
								'$id',
								'$ts->ts_barcode_no',
								'$ts->denom_id',
								'$ts->loc_gc_type'
							)	
						");

						if($query_ins_sales)
						{
							$query_tag = $link->query(
								"UPDATE 
									`store_received_gc` 
								SET 
									`strec_sold`='*', 
									`strec_return` =''
								WHERE 
									`strec_barcode` ='$ts->ts_barcode_no'        					 
							");

							if($query_tag)
							{
								//check if barcode has line discount
								$query_linedisc = $link->query(
									"SELECT
										`tsd_barcode`,
										`tsd_disc_type`,
										`tsd_disc_percent`,
										`tsd_disc_amt`,
										`tsd_cashier`,
										`tsd_discountby`
									FROM 
										`temp_sales_discountby` 
									WHERE 
										`tsd_barcode`='$ts->ts_barcode_no'
								");

								if($query_linedisc)
								{
									if($query_linedisc->num_rows > 0)
									{
										while ($row_disc = $query_linedisc->fetch_object()) 
										{
											$query_ins_disc = $link->query(
												"INSERT INTO 
													`transaction_linediscount`
												(
												    `trlinedis_sid`, 
												    `trlinedis_barcode`, 
												    `trlinedis_disctype`, 
												    `trlinedis_discpercent`, 
												    `trlinedis_discamt`, 
												    `trlinedis_by`
												) 
												VALUES 
												(
												    '$id',
												    '$ts->ts_barcode_no',
												    '$row_disc->tsd_disc_type',
												    '$row_disc->tsd_disc_percent',
												    '$row_disc->tsd_disc_amt',
												    '$row_disc->tsd_discountby'
												)
											");

											if(!$query_ins_disc)
											{
												$hasError = 1;
												break;
											}
										}
									}
								}
								else 
								{
									$hasError = 1;
									break; 						
								}										
							}
							else 
							{
								$hasError = 1;
								break; 							
							}
						}
						else 
						{
							$hasError = 1;
							break; 
						}        		
		        	}

		        	if(!$hasError)
		        	{				  		
						$items = numRows($link,'temp_sales','ts_cashier_id',$_SESSION["gccashier_id"]);
						$stotal = checkTotalwithoutLineDiscount($link);
						$docdisc = docdiscount($link);
						$linedisc =  linediscountTotal($link);

						$query_pay = $link->query(
							"INSERT INTO 
								`transaction_payment`
							(
							    `payment_trans_num`, 
							    `payment_items`, 
							    `payment_stotal`, 
							    `payment_amountdue`, 
							    `payment_cash`, 
							    `payment_change`, 
							    `payment_docdisc`, 
							    `payment_linediscount`, 
							    `payment_tender`
							) 
							VALUES 
							(
							    '$id',
							    '$items',
							    '$stotal',
							    '$total_charge',
							    '0',
							    '0',
							    '$docdisc',
							    '$linedisc',
							    '2'
							)
						");

						if($query_pay)
						{
							$docdiscby = getTempDiscountBy($link,$_SESSION['gccashier_id']);
							if(count($docdiscby)>0)
							{
								foreach ($docdiscby as $ddby) 
								{
									$query_docdiscby = $link->query(
										"INSERT INTO 
											`transaction_docdiscount`
										(
											`trdocdisc_trid`, 
											`trdocdisc_disctype`, 
											`trdocdisc_prcnt`,
											`trdocdisc_amnt`, 
											`trdocdisc_superby`
										) 
										VALUES 
										(
											'$id',
											'$ddby->docdis_discountype',
											'$ddby->docdis_pecentage',
											'$ddby->docdis_amt',
											'$ddby->docdis_superid'
										);
									");

									if(!$query_docdiscby)
									{
										$hasError = 1;
										break;
									}
								}
							}

							if(!$hasError)
							{
								//entry store sales
								$query_ccledger = $link->query(
									"INSERT INTO 
											`ledger_creditcard`
										(
										    ccled_transid, 
										    ccled_transtype, 
										    ccled_creditcardid, 
										    ccled_debit_amt
										) 
										VALUES 
										(
										    '$id',
										    'GCS',
										    '$creditcard',
										    '$total_charge'										    
										)
								");

								if($query_ccledger)
								{
									$query_ins_cc = $link->query(
										"INSERT INTO 
											`creditcard_payment`
										(
											`ccpayment_id`, 
											`cctrans_transid`, 
											`cc_creaditcard`, 
											`cc_cardnumber`, 
											`cc_cardexpired`
										) 
											VALUES 
										(
											'',
											'$id',
											'$creditcard',
											'$cardnumber',
											'$cardexpired'
										)
									");

									if($query_ins_cc)
									{
										$totaldiscounts = $docdisc + $linedisc;
						        		$query_ar = $link->query(
						        			"INSERT INTO 
						        				`customer_internal_ar`
						        			(
						        				ar_cuscode, 
						        				ar_datetime, 
						        				ar_transno, 
						        				ar_dbamt,
						        				ar_trans_id,
						        				ar_adj,
						        				ar_type
						        			) 
						        			VALUES 
						        			(
						        				
						        				'$creditcard',
						        				NOW(),
						        				'$tn',
						        				'$total_charge',
						        				'$id',
						        				'$totaldiscounts',
						        				2
						        			)
						        		");

						        		if($query_ar)
						        		{
						        			if(insertBudgetLedger($link,$id,'STORESALES','bdebit_amt',$stotal))
						        			{
												//delete temp sales
												$table = "temp_sales";
												$where = "WHERE `ts_cashier_id`=".$_SESSION['gccashier_id']."";
												if(deleteData($link,$table,$where))
												{
													//delete doc discount
													$table = 'temp_sales_discountby';
													$where = "WHERE `tsd_cashier`=".$_SESSION['gccashier_id']."";
													if(deleteData($link,$table,$where))
													{
														//delete all line disc 
														$table = 'temp_sales_docdiscount';
														$where = "WHERE `docdis_cashierid`=".$_SESSION['gccashier_id']."";
														if(deleteData($link,$table,$where))
														{
															$total = $stotal - ($docdisc+$linedisc);
															$discount = $docdisc+$linedisc;
															storeLedger($link,$id,1,$total,'GCS','GC Sales (Credit Card)',$_SESSION['gccashier_store'],$discount);											
															$link->commit();

															$response['receipt'] = getstorereceiptstatus($link,$_SESSION['gccashier_store']);
															$response['stat'] = 1;
															$response['numitems'] = $items;
															$response['transactnum'] = $tn;
															$response['amt_due'] = $total_charge;
															$response['linedisc'] = $linedisc;
															$response['docdisc'] =  $docdisc;
															$response['cards'] = number_format($total_charge * -1,2);
															$response['creditcard'] = $row_getcc->ccard_name;
															$response['cardnumber'] = $cardnumber;		
															$response['stotal'] = $stotal;							
														}
														else 
														{
															$response['msg'] = $link->error; 
														}
													}
													else 
													{
														$response['msg'] = $link->error; 
													}

												}
												else 
												{
													$response['msg'] = $link->error; 
												}
						        			}
						        			else 
						        			{
						        				$response['msg'] = $link->error;
						        			}

						        		}
						        		else
						        		{
						        			$response['msg'] = $link->error;
						        		}
									}
									else 
									{
										$response['msg'] = $link->error;
									}
								}
								else 
								{
									$response['msg'] = $link->error; 
								}
							}
							else 
							{
								$response['msg'] = $link->error;
							}

						}
						else 
						{
							$response['msg'] = $link->error;
						}

		        	}
		        	else 
		        	{
		        		$response['msg'] = $link->error;
		        	}
		        }
		        else 
		        {
		        	$response['msg'] = $link->error;
		        }
			} 
			else 
			{
				$response['msg'] = 'Auth Code is incorrect.';
			}


			// if($n>0){

			// 	$query = $link->query(
			// 		"SELECT 
			// 			* 
			// 		FROM 
			// 			`transaction_stores` 
			// 		WHERE 
			// 			`trans_cashier`='".$_SESSION['gccashier_id']."' 
			// 		AND
			// 			`trans_store`='".$_SESSION['gccashier_store']."'
			// 		ORDER BY 
			// 			`trans_sid`
			// 		DESC
			// 	");

			// 	$link->autocommit(false);

			// 	$n = $query->num_rows;
			// 	if($n>0){
			// 		$row = $query->fetch_assoc();
			// 		$row = $row['trans_number'];
			// 		$row++;
			// 		$tn = sprintf("%010d", $row);
					

		 //        } else {             
		 //        	$tn = '0000000001';         
		 //        }

		 //        ///
		 //        $query = $link->query(
		 //        	"INSERT INTO 
		 //        		`transaction_stores`
		 //        	(
		 //        		`trans_sid`, 
		 //        		`trans_number`, 
		 //        		`trans_cashier`, 
		 //        		`trans_store`, 
		 //        		`trans_datetime`, 
		 //        		`trans_status`,
		 //        		`trans_type`
		 //        	) 
		 //        	VALUES 
		 //        	(
		 //        		'',
		 //        		'$tn',
		 //        		'".$_SESSION['gccashier_id']."',
		 //        		'".$_SESSION['gccashier_store']."',
		 //        		NOW(),
		 //        		0,
		 //        		2
		 //        	)
		 //        ");

		 //        if($query){
		 //        	$id =  $link->insert_id;

		 //        	$query_sel = $link->query(
			// 			"SELECT
			// 				`temp_sales`.`ts_barcode_no`,
			// 				`denomination`.`denom_id`,
			// 				`gc_location`.`loc_gc_type`
			// 			FROM
			// 				`temp_sales`
			// 			INNER JOIN
			// 				`gc`
			// 			ON
			// 				`temp_sales`.`ts_barcode_no`=`gc`.`barcode_no`	
			// 			INNER JOIN
			// 				`denomination`
			// 			ON
			// 				`gc`.`denom_id` = `denomination`.`denom_id`
			// 			INNER JOIN 
			// 				`gc_location`
			// 			ON
			// 				`temp_sales`.`ts_barcode_no`=`gc_location`.`loc_barcode_no` 
			// 			WHERE 
			// 				`ts_cashier_id`='".$_SESSION['gccashier_id']."'					
		 //        	");

		 //        	if($query_sel){
		 //        		while($row_sel = $query_sel->fetch_object()){
		 //        			$link->query(
		 //        				"INSERT INTO 
		 //        					`transaction_sales`
		 //        				(
		 //        					`sales_id`, 
		 //        					`sales_transaction_id`, 
		 //        					`sales_barcode`, 
		 //        					`sales_denomination`, 
		 //        					`sales_gc_type`
		 //        				) 
		 //        				VALUES 
		 //        				(
		 //        					'',
		 //        					'$id',
		 //        					'$row_sel->ts_barcode_no',
		 //        					'$row_sel->denom_id',
		 //        					'$row_sel->loc_gc_type'
		 //        				)	
		 //        			");

	  //       				$link->query(
	  //       					"UPDATE 
	  //       						`store_received_gc` 
	  //       					SET 
	  //       						`strec_sold`='*', 
	  //       						`strec_return` =''
	  //       					WHERE 
	  //       						`strec_barcode` ='$row_sel->ts_barcode_no'        					 
	  //       					");
		 //        		}
		 //        	}

		 //        	$items = numRows($link,'temp_sales','ts_cashier_id',$_SESSION["gccashier_id"]);

		 //        	$query_pay = $link->query(
		 //        		"INSERT INTO 
		 //        			`transaction_payment`
		 //        		(
		 //        			`payment_id`, 
		 //        			`payment_trans_num`, 
		 //        			`payment_receipt_no`, 
		 //        			`payment_items`, 
		 //        			`payment_amountdue`, 
		 //        			`payment_cash`, 
		 //        			`payment_change`, 
		 //        			`payment_tender`
		 //        		) 
		 //        		VALUES 
		 //        		(
		 //        			'',
		 //        			'$id',
		 //        			'10',
		 //        			'$items',
		 //        			'$total_charge',
		 //        			'0',
		 //        			'0',
		 //        			'2'
		 //        		)
		 //        	");

		 //        	if(!$query_pay){
		 //        		echo $link->error;
		 //        	}


			// 		$query_getd = $link->query(
			// 		"SELECT 
			// 			denomination.denom_id,count(*) as counter,
			// 			SUM(denomination) as sums
			// 		FROM 
			// 		`temp_sales`
			// 		INNER JOIN 
			// 		gc
			// 		ON
			// 		temp_sales.ts_barcode_no = gc.barcode_no
			// 		INNER JOIN 
			// 		denomination
			// 		ON
			// 		gc.denom_id = denomination.denom_id
			// 		WHERE `ts_cashier_id` = '".$_SESSION['gccashier_id']."'				
			// 		GROUP BY 
			// 		`denom_id`
			// 		");

			// 		if($query_getd){
			// 			while($row_getd = $query_getd->fetch_object()){

			// 				$query_entry = $link->query(
			// 					"INSERT INTO 
			// 						`entry_store_sales`
			// 					(
			// 						`ess_id`, 
			// 						`ess_type`, 
			// 						`ss_ref_id`, 
			// 						`ess_denom`, 
			// 						`ess_scode`, 
			// 						`ess_pcs`, 
			// 						`ess_amount`
			// 					) 
			// 					VALUES 
			// 					(
			// 						'',
			// 						'SS',
			// 						'$id',
			// 						'$row_getd->denom_id',
			// 						'".$_SESSION['gccashier_store']."',
			// 						'$row_getd->counter',
			// 						'$row_getd->sums'
			// 					)
			// 				");
			// 			}

			// 			$query_ins_cc = $link->query(
			// 				"INSERT INTO 
			// 					`creditcard_payment`
			// 				(
			// 					`ccpayment_id`, 
			// 					`cctrans_paymentid`, 
			// 					`cc_creaditcard`, 
			// 					`cc_cardnumber`, 
			// 					`cc_cardexpired`
			// 				) 
			// 					VALUES 
			// 				(
			// 					'',
			// 					'$id',
			// 					'$creditcard',
			// 					'$cardnumber',
			// 					'$cardexpired'
			// 				)
			// 			");

			// 			$query_sum = $link->query(
			// 				"SELECT 
			// 					SUM(`denomination`.`denomination`) as amt_due
			// 				FROM 
			// 					`temp_sales`
			// 				INNER JOIN
			// 					`gc`
			// 				ON
			// 					`temp_sales`.`ts_barcode_no` = `gc`.`barcode_no`
			// 				INNER JOIN
			// 					`denomination`
			// 				ON
			// 					`gc`.`denom_id` = `denomination`.`denom_id`
			// 			");

			// 			$row_sum = $query_sum->fetch_object();

			// 			if($query_ins_cc){
			// 				$items = numRows($link,'temp_sales','ts_cashier_id',$_SESSION["gccashier_id"]);
			// 				$query_del_temp = $link->query(
			// 					"DELETE FROM
			// 					`temp_sales`
			// 					WHERE `ts_cashier_id`='".$_SESSION['gccashier_id']."'
			// 				");

			// 				if($query_del_temp){

			// 					insertStoreLedger($link,$id,2);

			// 					$link->commit();
								
			// 					$response['numitems'] = $items;
			// 					$response['transactnum'] = $tn;
			// 					$response['message'] = 'success';
			// 					$response['amt_due'] = number_format($row_sum->amt_due,2);
			// 					$response['cards'] = number_format($row_sum->amt_due * -1,2);
			// 					$response['creditcard'] = $row_getcc->ccard_name;								
			// 				}
			// 			} else {
			// 				$response['message'] = $link->error;
			// 			}
			// 		}

		 //        } else {
		 //        	$response['message'] = $link->error;
		 //        }

			// } else {
			// 	$response['message'] = 'Auth Code is incorrect.';
			// }

		} else {
			$response['message'] = $link->error;
		}

		echo json_encode($response);

	} elseif($request=='confirmreturngc'){
		$barcode = $link->real_escape_string($_POST['barcode']);

		$query = $link->query(
			"SELECT 
				`transaction_stores`.`trans_number`,
				`transaction_sales`.`sales_denomination` 
			FROM 
				`transaction_sales`
			INNER JOIN
				`transaction_stores`
			ON
				`transaction_sales`.`sales_transaction_id`=`transaction_stores` .`trans_sid`
			INNER JOIN
				`transaction_payment`
			ON
				`transaction_sales`.`sales_transaction_id`=`transaction_payment`.`payment_trans_num`
			WHERE 
				`transaction_sales`.`sales_barcode`='$barcode'
			ORDER BY
				`sales_id`
			DESC
		");

		$row = $query->fetch_object();


		$link->autocommit(FALSE);		
		$query = $link->query(
			"INSERT INTO 
				`gc_return`
			(
				`rr_id`, 
				`rr_barcode_no`, 
				`rr_transaction_num`, 
				`rr_datetime`, 
				`rr_store`, 
				`rr_cashier`,
				`rr_supervisor`, 
				`rr_denom_id`
			) 
				VALUES 
			(
				'',
				'$barcode',
				'$row->trans_number',
				NOW(),
				'".$_SESSION['gccashier_store']."',
				'".$_SESSION['gccashier_id']."',
				'".$_SESSION['gc_super_id']."',
				'$row->sales_denomination'
			)
		");

		if($query){

			$id = $link->insert_id;

			$query_up = $link->query(
				"UPDATE 
					`transaction_sales` 
				SET 
					`sales_item_status`='1' 
				WHERE 
					`sales_barcode`='$barcode'
			");

			if($query_up){
				$query_update_release= $link->query(
					"UPDATE 
						`store_received_gc` 
					SET 
						`strec_sold`='',
						`strec_return`='*' 
					WHERE 
						`strec_barcode`='$barcode'
					AND
						`strec_storeid`='".$_SESSION['gccashier_store']."'
				");

				if($query_update_release)
				{
					insertStoreLedger($link,$id,3);
					$link->commit();
					echo 'success';
				}

			} else {
				echo $link->error;
			}
		} else {
			echo $link->error;
		}

	} elseif ($request=='checktransstatusy') {
		//check if transaction status is 0
		$query = $link->query(
			"SELECT 
				* 
			FROM 
				`transaction_stores` 
			WHERE 
				`trans_datetime` LIKE '%$todays_date%'
			AND
				`trans_cashier`='".$_SESSION['gccashier_id']."'
			AND 
				`trans_store`='".$_SESSION['gccashier_store']."'
			AND 
				`trans_yreport`='0'
		");

		if($query){
			$n = $query->num_rows;
			if($n>0){
				echo 'success';
			} else {
				echo 'failed';
			}
		}
	} elseif ($request=='confirmyperform') {

		$link->autocommit(FALSE);
		$query_ins = $link->query(
			"INSERT INTO 
				`sales_yreport`
			(
				`yrep_id`, 
				`yrep_cashier`, 
				`yrep_supervisor`, 
				`yrep_store`, 
				`yrep_datetime`
			) 
				VALUES 
			(
				'',
				'".$_SESSION['gccashier_id']."',
				'".$_SESSION['gc_super_id']."',
				'".$_SESSION['gccashier_store']."',
				NOW()
			);
		");

		if($query_ins){

			$query = $link->query(
				"UPDATE 
					`transaction_stores` 
				SET 
					`trans_yreport`='1'
				WHERE 
					`trans_datetime` LIKE '%$todays_date%'
				AND
					`trans_cashier`='".$_SESSION['gccashier_id']."'
				AND 
					`trans_store`='".$_SESSION['gccashier_store']."'
				AND 
					`trans_yreport`='0'
			");

			if($query){
				echo 'success';
			} else {
				echo $link->error;
			}
		} else {
			echo $link->error;
		}
	} 
	elseif ($request=='gcreturn') 
	{
		$transnum = $_POST['transno'];
		$storeid = $_SESSION['gccashier_store'];		
		$response['stat'] = 0;	
		if(getTransactionTenderType($link,$transnum,$storeid,1))
		{
			$tendertype = getTransactionTenderType($link,$transnum,$storeid,2);
			if($tendertype==1)
			{
				//get transaction details
				$store = $_SESSION['gccashier_store'];
				$gtd = getTransactionDetails($link,$transnum,$store);
				$response['transid'] = $gtd->trans_sid;
				$response['datetrans'] = _dateFormat($gtd->trans_datetime);
				$response['storename'] = $gtd->store_name;
				$response['cashier'] = ucwords($gtd->ss_firstname.' '.$gtd->ss_lastname); 
				$response['stat'] = 1;	
				$response['msg'] = $tendertype;
			}
			else 
			{
				$tendesc = array('','Cash','Credit Card','H.O.','Subs. Admin');
				$response['msg'] = 'Transaction is '.$tendesc[$tendertype];
			}
		}
		else 
		{
			$response['msg'] = 'Transaction number not found.';
		}
		echo json_encode($response);
	}
	elseif ($request=='checkGCReturnBarcode') 
	{
	 	$barcode = $_POST['barcode'];
	 	$transno = $_POST['transno'];
	 	$transid = $_POST['transid'];
	 	$response['stat'] = 0;

	 	// check if gc exist in transaction
	 	if(checkIFBarcodeExistInTransaction($link,$transno,$barcode))
	 	{
	 		//check gc status if returned or sold
	 		if(!checkCurrentStatus($link,$barcode))
	 		{

	 			// check if gc is already verified.
	 			if(!numRows($link,'store_verification','vs_barcode',$barcode)>0)
	 			{
	 				//check if gc is already scanned.
	 				if(!numRows($link,'temp_refund','trfund_barcode',$barcode)>0)
	 				{
	 					//Add to temp_refund

				$subtotaldisc = getTransactionSubtotalDiscount($link,$transid);
				//get number of items in a transaction
				$table = 'transaction_sales';
				$select = 'IFNULL(COUNT(sales_id),0) as cnt';
				$where = 'sales_transaction_id='.$transid;
				$field = 'cnt';
				$cnt = countData($link,$table,$select,$where,$field);

				$sdisc = $subtotaldisc / $cnt;

 					$select = 'transaction_sales.sales_barcode,
						transaction_linediscount.trlinedis_disctype,
						transaction_linediscount.trlinedis_discpercent,
						transaction_linediscount.trlinedis_discamt,
						store_received_gc.strec_storeid,
						denomination.denomination,
						gc_type.gctype';
 					$where = "
								store_received_gc.strec_sold='*'
							AND
								store_received_gc.strec_return=''
							AND
								transaction_sales.sales_transaction_id='".$transid."'
							AND
								transaction_sales.sales_barcode='".$barcode."'";
 					$join = 'transaction_sales
							INNER JOIN
								store_received_gc
							ON
								store_received_gc.strec_barcode = transaction_sales.sales_barcode
							INNER JOIN
								denomination
							ON
								denomination.denom_id = store_received_gc.strec_denom
							INNER JOIN
								gc_type
							ON
								gc_type.gc_type_id = transaction_sales.sales_gc_type
							LEFT JOIN
								transaction_linediscount
							ON
								transaction_linediscount.trlinedis_barcode = transaction_sales.sales_barcode';


	 					$gc = getSelectedData($link,'transaction_sales',$select,$where,$join,'');

	 					$query = $link->query("
	 						INSERT INTO 
	 							`temp_refund`
	 						(
 								`trfund_barcode`, 
 								`trfund_linedisc`, 
 								`trfund_subdisc`, 
 								`trfund_store`, 
 								`trfund_by`
	 						) 
	 						VALUES 
 							(
 								'$gc->sales_barcode',
 								'$gc->trlinedis_discamt',
 								'$sdisc',
								'".$_SESSION['gccashier_store']."',
								'".$_SESSION['gccashier_id']."'
 							)
	 					");
	 					// $query = $link->query(
	 					// 	"INSERT INTO 
	 					// 		`temp_refund`
	 					// 	(
	 					// 		`trfund_barcode`, 
	 					// 		`trfund_store`, 
	 					// 		`trfund_by`
	 					// 	) 
	 					// 	VALUES 
	 					// 	(
	 					// 		'$barcode',
	 					// 		'".$_SESSION['gccashier_store']."',
	 					// 		'".$_SESSION['gccashier_id']."'
	 					// 	)
	 					// ");

	 					if($query)
	 					{
	 						$response['stat'] = 1;
	 						$response['msg'] = 'GC Barcode '.$barcode.' added.';
	 					}
	 					else 
	 					{
	 						$response['msg'] = $link->error;
	 					}
	 				}
	 				else 
	 				{
	 					$response['msg'] = 'GC Barcode '.$barcode.' already scanned for refund.';
	 				}
	 			}
	 			else 
	 			{
	 				$response['msg'] = 'GC Barcode '.$barcode.' already verified.';
	 			}

	 		}
	 		else 
	 		{
	 			$response['msg'] = 'GC Barcode '.$barcode.' status is currently returned.';
	 		}
	 	}
	 	else 
	 	{
	 		$response['msg'] = 'GC Barcode '.$barcode.' not found.';
	 	}
	 	echo json_encode($response);
	} 
	elseif ($request=='truncateGCTempRefundTable') 
	{
		deleteDataWhereOne($link,'temp_refund','trfund_store',$_SESSION['gccashier_store']);
	}
	elseif ($request=='displayGCTempRefund') 
	{
		$transid = $_POST['transid'];
		// SELECT 
			// temp_refund.trfund_barcode,
			// temp_refund.trfund_linedisc,
			// temp_refund.trfund_subdisc,
			// denomination.denomination,
			// gc_type.gctype
		// FROM 
		// 	temp_refund 
		// INNER JOIN
		// 	gc 
		// ON
		// 	gc.barcode_no = temp_refund.trfund_barcode
		// INNER JOIN
		// 	denomination
		// ON
		// 	denomination.denom_id = gc.denom_id
		// INNER JOIN
		// 	gc_location
		// ON
		// 	gc_location.loc_barcode_no = temp_refund.trfund_barcode
		// INNER JOIN
		// 	gc_type
		// ON
		// 	gc_type.gc_type_id = gc_location.loc_gc_type
		// WHERE
		// 	temp_refund.trfund_store = '3'
		// AND
		// 	temp_refund.trfund_by = '5'

		$table = 'temp_refund';
		$select = 'temp_refund.trfund_barcode,
			temp_refund.trfund_linedisc,
			temp_refund.trfund_subdisc,
			denomination.denomination,
			gc_type.gctype';
		$where = "temp_refund.trfund_store = '".$_SESSION['gccashier_store']."'
					AND
					temp_refund.trfund_by = '".$_SESSION['gccashier_id']."'";
		$join = 'INNER JOIN
					gc 
				ON
					gc.barcode_no = temp_refund.trfund_barcode
				INNER JOIN
					denomination
				ON
					denomination.denom_id = gc.denom_id
				INNER JOIN
					gc_location
				ON
					gc_location.loc_barcode_no = temp_refund.trfund_barcode
				INNER JOIN
					gc_type
				ON
					gc_type.gc_type_id = gc_location.loc_gc_type';
		$limit ='';
		$gc = getAllData($link,$table,$select,$where,$join,$limit);

		// $query = $link->query(
		// 	"SELECT 
		// 		`temp_refund`.`trfund_barcode`,
		// 		`denomination`.`denomination`,
		// 		`stores`.`store_name`,
		// 		`gc_type`.`gctype`,
		// 		`transaction_linediscount`.`trlinedis_disctype`,
		// 		`transaction_linediscount`.`trlinedis_discpercent`,
		// 		`transaction_linediscount`.`trlinedis_discamt`
		// 	FROM 
		// 		`temp_refund` 
		// 	INNER JOIN
		// 		`gc`
		// 	ON
		// 		`gc`.`barcode_no` = `temp_refund`.`trfund_barcode`
		// 	INNER JOIN	
		// 		`denomination`
		// 	ON
		// 		`denomination`.`denom_id` = `gc`.`denom_id`
		// 	INNER JOIN
		// 		`stores`
		// 	ON
		// 		`stores`.`store_id` = `temp_refund`.`trfund_store`
		// 	INNER JOIN
		// 		`gc_location`
		// 	ON	
		// 		`temp_refund`.`trfund_barcode` = `gc_location`.`loc_barcode_no`
		// 	INNER JOIN
		// 		`gc_type`
		// 	ON
		// 		`gc_type`.`gc_type_id` = `gc_location`.`loc_gc_type`
		// 	INNER JOIN
		// 		`transaction_stores`
		// 	ON
		// 		`transaction_stores`.`trans_store` = `temp_refund`.`trfund_store`
		// 	LEFT JOIN
		// 		`transaction_linediscount`
		// 	ON
		// 		`transaction_linediscount`.`trlinedis_barcode` = `temp_refund`.`trfund_barcode`
		// 	WHERE 
		// 		`temp_refund`.`trfund_store`='".$_SESSION['gccashier_store']."'
		// 	AND
		// 	`transaction_stores`.`trans_sid`='$transid'			
		// ");

		if(count($gc) > 0)
		{

			// get subtotal discount
			//code here
			$subtotaldisc = getTransactionSubtotalDiscount($link,$transid);
			//get number of items in a transaction
			$table = 'transaction_sales';
			$select = 'IFNULL(COUNT(sales_id),0) as cnt';
			$where = 'sales_transaction_id='.$transid;
			$field = 'cnt';
			$cnt = countData($link,$table,$select,$where,$field);

			$sdisc = $subtotaldisc / $cnt;

			$total = 0; 
			$tsub = 0;


			?>	
				<table class="table reftable">
					<thead>
						<tr>
							<th>Barcode</th>
							<th>Denomination</th>
							<th>GC Type</th>
							<th>Line Dis.</th>
							<th>Sub. Disc</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($gc as $g): ?>
						<tr>
							<td><?php echo$g->trfund_barcode; ?></td>
							<td><?php echo number_format($g->denomination,2); ?></td>
							<td><?php echo ucwords($g->gctype); ?></td>
							<th>
							<?php
								echo $g->trfund_linedisc;
							?>
							</th>
							<th><?php
									$total+=$g->denomination;
									$total-=$g->trfund_linedisc;
									$total-=$g->trfund_subdisc;  
									$tsub+=$g->trfund_subdisc;
									echo number_format($g->trfund_subdisc,2); 
								?></th>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
				<div class="row">
					<div class="col-xs-5">
						<button class="btn btn-block btn-success" type="button" onclick="refundnow();">[F1]Service Charge</button>
					</div>
						<label class="col-xs-3 control-label lbl-c lblsm">Refund:</label>
					<div class="col-xs-4">
						<input class="form form-control input-xs inpmed inptxtmed" type="text" readonly="readonly" name="reftot" value="<?php echo number_format($total,2); ?>">
						<input type="hidden" name="refundtotal" id="refundtotal" value="<?php echo number_format($total,2); ?>">
						<input type="hidden" name="totsubdisc" id="totsubdisc" value="<?php echo $tsub; ?>">
						<!-- <input type="type" id="totref" class="form form-control input-xs inpmed inptxtmed" readonly="readonly" value="0.0"> -->
					</div>
				</div>					
			<?php
		}
		else 
		{
			echo 'no barcode exist.';
		}

	} 
	elseif ($request=='refundnow') 
	{
		$hasError = 0;
		$cashier = $_SESSION['gccashier_id'];
		$store = $_SESSION['gccashier_store'];
		$transid = $_POST['transid'];
		$cash = $_POST['cash'];
		$response['stat'] = 0;

		$ip = get_ip_address();

		$refund = getTotalRefund($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id']);		

		//get last transaction number
		$lasttransnum = getLastTransactionNumber($link,$cashier,$store);
		$lasttransnum++;		
		$link->autocommit(FALSE);
		$query = $link->query(
			"INSERT INTO 
				`transaction_stores`
			(			   
			    `trans_number`, 
			    `trans_cashier`, 
			    `trans_store`,
			    `trans_datetime`, 
			    `trans_status`, 
			    `trans_yreport`, 
			    `trans_type`,
			    `trans_ip_address`
			) 
			VALUES 
			(			   
			    '$lasttransnum',
			    '$cashier',
			    '$store',		
			    NOW(),
			    0,
			    0,
			    5,
			    '$ip'
			)	
		");

		if($query)
		{
			$last_insert = $link->insert_id;

			$totrefund = $refund->rfundtot - $cash;

			$query_details = $link->query(
				"INSERT INTO 
					`transaction_refund_details`
				(
					`trefundd_trstoresid`, 
					`trefundd_trid_refund`, 
					`trefundd_totgcrefund`, 
					`trefundd_total_linedisc`, 
					`trefundd_subtotal_disc`, 
					`trefundd_servicecharge`, 
					`trefundd_refundamt`
				) 
				VALUES 
				(
					'$last_insert',
					'$transid',
					'$refund->denom',
					'$refund->totlinedisc',
					'$refund->subdisc',
					'$cash',
					'$totrefund'
				)
			");

			if($query_details)
			{
				$trefund = getTempRefund($link,$store,$cashier);
				foreach ($trefund as $t) 
				{
					$query_ins = $link->query(
						"INSERT INTO 
							`transaction_refund`
						(
						    `refund_trans_id`, 
						    `refund_barcode`, 
						    `refund_denom`,
						    `refund_linedisc`,
						    `refund_sdisc`
						) 
						VALUES 
						(
						    '$last_insert',
						    '$t->trfund_barcode',
						    '$t->denom_id',
						    '$t->trfund_linedisc',
						    '$t->trfund_subdisc'
						)
					");

					if(!$query_ins)
					{
						$hasError =1;
						break;					
					}

					$query_up = $link->query(
						"UPDATE 
							`store_received_gc`
						SET 
							`strec_sold`='',
							`strec_return`='*' 
						WHERE
							`strec_barcode`='$t->trfund_barcode'
					");

					if(!$query_up)
					{
						$hasError = 1;
						break;
					}

					$query_sales = $link->query(
						"UPDATE 
							`transaction_sales` 
						SET 
							`sales_item_status`='1'
						WHERE 
							`sales_barcode`='$t->trfund_barcode'
						AND
							`sales_transaction_id`='$transid'
						ORDER BY
							`sales_id`
						DESC
						LIMIT 1
					");

					if(!$query_sales)
					{
						$hasError = 1;
						break;
					}	
				}

				if(!$hasError)
				{
					$link->commit();
					$totalref = getTempRefundTotal($link,$store,$cashier);
					$table = 'temp_refund';
					$where = 'trfund_store = "'.$_SESSION['gccashier_store'].'"
								AND
								trfund_by = "'.$_SESSION['gccashier_id'].'"';
					if(deleteSelectedData($link,$table,$where))
					{
						$response['stat'] = 1;
						$response['transactnum'] = $lasttransnum;
						$table = '<table class="table resibo"><thead><tr><th>Barcode No </th><th class="receipt_items">Price</th></thead></tr><tbody>';

						foreach ($trefund as $d) 
						{
							$table.='<tr><td>'.$d->trfund_barcode.'</td><td class="receipt_items">₱ '.number_format($d->denomination,2).'</td></tr>';
						}
						// $table.='<tr><td>Total: </td><td>₱ '.number_format($totalref,2).'</td></tr>';
						$table.='</tbody></table>';
						$response['items'] = $table;
						$response['noitems']  = count($trefund);
						$response['total'] = $refund->denom;
						$response['linedis'] = number_format($refund->totlinedisc,2);
						$response['subdis'] = $refund->subdisc;
						$response['scharge'] = number_format($cash,2);
						$response['totalrefund'] = $totrefund;
					}
					else 
					{
						$response['msg'] = 'Delete data error';					
					}
				}	
				else 
				{
					$response['msg'] = $link->error;
				}			
			}
			else 
			{
				$response['msg'] = $link->error;
			}
		}
		else 
		{
			$response['msg'] = $link->error;
		}

		echo json_encode($response);
	} 
	elseif($request=='checkIFhasTempSales')
	{
		$cashier = $_SESSION['gccashier_id'];
		$store = $_SESSION['gccashier_store'];
		$response['st'] = 0;
		if(!numRows($link,'temp_sales','ts_cashier_id',$cashier)>0)
		{
			$response['st'] = 1;
		}
		else 
		{
			$response['msg'] = 'Please void item/s first.';
		}
		echo json_encode($response);
	}
	elseif($request=='gcrevalidate')
	{
		$payment = $_POST['payment'];
		$barcode = $_POST['rbarcode'];
		$response['stat'] = 0;
		
		//check if barcode exist
		if(!numRows($link,'gc','barcode_no',$barcode)>0)
		{
			$response['msg'] = 'GC Barcode # '.$barcode.' not found.';
			goto stop;
		}

		//check if gc already sold
		// if(!numRowsWhereTwo($link,'store_received_gc','strec_barcode','strec_barcode','strec_sold',$barcode,'*')>0)
		// {
		// 	$response['msg'] = 'GC Barcode # '.$barcode.' is not yet sold.';
		// 	goto stop; 
		// }

		//check if gc already verified.
		if(!numRows($link,'store_verification','vs_barcode',$barcode)>0)
		{
			$response['msg'] = 'GC Barcode # '.$barcode.' is not yet verified.';
			goto stop; 
		}

		//check store validated
		// 		SELECT 
			// store_verification.vs_barcode,
			// stores.store_name
		// FROM 
		// 	store_verification 
		// INNER JOIN
		// 	stores
		// ON
		// 	stores.store_id = store_verification.vs_store
		// WHERE 
		// 	store_verification.vs_barcode='1210000000007'
		// AND
		// 	store_verification.vs_store='3'
		// ORDER BY
		// 	store_verification.vs_id
		// DESC
		$select = 'store_verification.vs_barcode,
			stores.store_name';
		$where = 'store_verification.vs_barcode="'.$barcode.'"
				AND
			store_verification.vs_store='.$_SESSION['gccashier_store'];
		$join = 'INNER JOIN
			stores
				ON
			stores.store_id = store_verification.vs_store';
		$limit = 'ORDER BY
			store_verification.vs_id
		DESC';
		$gcv = getSelectedData($link,'store_verification',$select,$where,$join,$limit);
		if(is_null($gcv))
		{
			$select = 'store_verification.vs_barcode,
				stores.store_name';
			$where = 'store_verification.vs_barcode='.$barcode;
			$join = 'INNER JOIN
				stores
					ON
				stores.store_id = store_verification.vs_store';
			$limit = 'ORDER BY
				store_verification.vs_id
			DESC';
			$st = getSelectedData($link,'store_verification',$select,$where,$join,$limit);
			$response['msg'] = 'GC Barcode # '.$barcode.' was verified at '.$st->store_name;
			goto stop;
		}

		//check if verified twice
		if(numRows2($link,'store_verification','vs_barcode',$barcode) > 1)
		{
			$response['msg'] = 'GC Barcode # '.$barcode.' already revalidated.';
			goto stop;			
		}

		//check if gc validated today
		if(checkifValidatedToday($link,$barcode))
		{
			$response['msg'] = 'GC Barcode # '.$barcode.' verified today.';
			goto stop;
		}

		//check if date validated is lesser than 
		if(!checkDateIfLesserThanCurdate($link,$barcode))
		{
			$response['msg'] = 'GC Barcode # '.$barcode.' has invalid date verified.';
			goto stop;
		}

		//check if gc already have transactions...
		if(numRowsWhereTwo($link,'store_verification','vs_barcode','vs_barcode','vs_tf_used',$barcode,'*')>0)
		{
			$response['msg'] = 'GC Barcode # '.$barcode.' already have transactions.';
			goto stop;
		}

		//check if gc already revalidated
		
		if(numRows($link,'transaction_revalidation','reval_barcode',$barcode)>0)
		{
			$response['msg'] = 'GC Barcode # '.$barcode.' already revalidated.';
			goto stop;
		}
		//check if in temp_rval table
		if(!numRows($link,'temp_reval','treval_barcode',$barcode)>0)
		{

			$link->autocommit(FALSE);
			$query = $link->query(
				"INSERT INTO 
					`temp_reval`
				(
					`treval_barcode`, 
					`treval_by`, 
					`treval_store`
				) 
				VALUES 
				(
					'$barcode',
					'".$_SESSION['gccashier_id']."',
					'".$_SESSION['gccashier_store']."'
				)
			");

			if($query)
			{
				$link->commit();
				$response['stat'] = 1;
				if(numRowsWhereTwo($link,'gc','barcode_no','barcode_no','gc_ispromo',$barcode,'*')>0)
				{
					$response['barcode'] = $barcode;
					$reval = getBarcodeForRevalDetailsPromo($link,$barcode);
					$response['gctype'] = ucwords($reval->gctype);
					$response['denom'] = $reval->denomination;
					$response['datesold'] = 'Promo GC';
					$validated = _dateFromSql($reval->vs_date);
					$response['datevalidated'] = $validated; 

				}
				else
				{
				$reval  = getBarcodeForRevalDetails($link,$barcode);
				$response['barcode'] = $barcode;
				$response['gctype'] = ucwords($reval->gctype);
				$response['denom'] = $reval->denomination;
				$sold = _dateFromSql($reval->trans_datetime);
				$response['datesold'] = $sold;
				$validated = _dateFromSql($reval->vs_date);
				$response['datevalidated'] = $validated; 
				}
			}
			else 
			{
				$response['msg'] = $link->error;
			}

		}
		else 
		{
			$response['msg'] = 'GC Barcode # '.$barcode.' already scanned for revalidation.';
		}

		stop:
		echo json_encode($response);
	}
	elseif ($request=='deletetempandchecktempsales') 
	{
		$response['stat'] = 0;
		$query = $link->query(
		"DELETE FROM 
			`temp_reval` 
		WHERE 
			`treval_by`='".$_SESSION['gccashier_id']."'
		AND
			`treval_store`='".$_SESSION['gccashier_store']."'
		");

		if($query)
		{
			if(!numRows($link,'temp_sales','ts_cashier_id',$_SESSION['gccashier_id'])>0)
			{
				$response['stat'] = 1;
			}
			else 
			{
				$response['msg'] = 'Please void item/s first.';
			}	
		}
		else 
		{
			$response['msg'] = $link->error;
		}

		echo json_encode($response);
	}
	elseif($request=='gcrevalidationpayment')
	{
		$cash = $_POST['cashis'];
		$amtdue = $_POST['amtdue'];
		$response['stat'] = 0;
		$noError = true; 

		$ip = get_ip_address();

		$total=0;
		$gccount=0;

		//$change = $cash - $amtdue;
		$query_tot = $link->query(
			"SELECT 
				SUM(temp_reval.treval_charge) as totcharge,
				COUNT(temp_reval.treval_charge) as gccount
			FROM 
			 	temp_reval 
			WHERE 
				temp_reval.treval_by='".$_SESSION['gccashier_id']."'
			AND
				temp_reval.treval_store='".$_SESSION['gccashier_store']."'
		");

		if($query_tot)
		{
			$row = $query_tot->fetch_object();
			$total = $row->totcharge;
			$gccount = $row->gccount;
		}

		if($gccount>0)
		{

			if($total <= $cash )
			{
				$change = $cash - $total;
				// get last transaction 
				$transnum = getLastTransnumByStore($link);
				$response['msg'] = $transnum;
				$link->autocommit(FALSE);

		        $query = $link->query(
		          "INSERT INTO 
		            transaction_stores
		          (
		            trans_sid, 
		            trans_number, 
		            trans_cashier, 
		            trans_store, 
		           	trans_datetime,
		            trans_type,
		            trans_ip_address
		  
		          ) 
		          VALUES 
		          (
		            '',
		            '$transnum',
		            '".$_SESSION['gccashier_id']."',
		            '".$_SESSION['gccashier_store']."',
		            NOW(),
		            6,
		            '$ip'
		          )
		        ");
		        if($query)
		        {
		        	$id =  $link->insert_id;
		        	//get temp val
		        	$tempval = getTempReval($link);

		        	$numitems = count($tempval);
		        	foreach ($tempval as $t) 
		        	{
	        			$query_ins = $link->query(
	        				"INSERT INTO 
								transaction_revalidation
							(
							    reval_trans_id, 
							    reval_barcode, 
							    reval_denom,
							    reval_charge
							) 
							VALUES 
							(
							    '$id',
							    '$t->treval_barcode',
							    '$t->vs_tf_denomination',
							    '$t->treval_charge'
							)
	        			");

	        			if(!$query_ins)
	        			{
	        				$noError = false;
	        				break;
	        			}
		        	}

		        	if($noError)
		        	{
        				$query_inspayment = $link->query(
        					"INSERT INTO 
								transaction_payment
							(
							    payment_trans_num, 
							    payment_items, 
							    payment_amountdue, 
							    payment_cash, 
							    payment_change, 
							    payment_tender,
							    payment_stotal
							) 
								VALUES 
							(
							    '$id',
							    '$gccount',
							    '$total',
							    '$cash',
							    '$change',
							    1,
							    '$total'
							)
        				");

        				if($query_inspayment)
        				{
        					if(storeLedger($link,$id,1,$total,'GCR','GC Revalidation',$_SESSION['gccashier_store'],0))
        					{
        						// delete temp
        						$query_delreval = $link->query(
        							"DELETE FROM 
										temp_reval 
									WHERE 
										treval_by='".$_SESSION['gccashier_id']."'
									AND
										treval_store='".$_SESSION['gccashier_store']."'
        						");

        						if($query_delreval)
        						{
									$link->commit();		
									$rec = getstorereceiptstatus($link,$_SESSION['gccashier_store']);	
									$response['receipt'] = $rec;
									$response['stat'] = 1;
									$response['transactnum'] = $transnum;
									$tableitems = '<table class="table resibo"><thead><tr><th>Barcode No </th><th>Denomination</th><th>Payment</th></thead></tr><tbody>';

									foreach ($tempval as $t) 
									{
									  $tableitems.='<tr><td>'.$t->treval_barcode.'</td><td>₱ '.number_format($t->vs_tf_denomination,2).'</td><td>₱ '.number_format($t->treval_charge,2).'</td></tr>';
									}
									$tableitems.='</tbody></table>';
									$response['items'] = $tableitems;
									$response['total'] = $total;
									$response['numitems'] = $numitems;
								}
								else 
								{
									$response['msg'] = $link->error;
								}
							}
							else 
							{
								$response['msg'] = $link->error;
							}
        				}
        				else 
        				{
        					$response['msg'] = $link->error;
        				}
		        	}
		        	else 
		        	{
		        		$response['msg'] = $link->error;
		        	}

		        }
		        else 
		        {
		        	//get transaction error
		        	$response['msg'] = $link->error;
		        }
			}
			else 
			{
				echo $response['msg'] = 'Insufficient amount.';
			}
		}
		else 
		{
			$response['msg'] = 'Error: revalidation item is empty.';
		}

		echo json_encode($response);
	}
	elseif ($request=='getcustomerar') 
	{
		$ar = $_GET['ar'];
		$code = $_POST['code'];
		$response['stat'] = 0;
		$query = $link->query(
			"SELECT
				`customer_internal`.`ci_code`,
				`customer_internal`.`ci_name`,
				`customer_internal`.`ci_type`,
				`customer_internal`.`ci_address`
			FROM 
				`customer_internal` 
			WHERE 
				`customer_internal`.`ci_code`='".$code."'
			AND
				`ci_group`='".$ar."'
		");

		if($query)
		{
			if($query->num_rows > 0)
			{
				$totaldis = getTotalDiscount($link,$code,$_SESSION['gccashier_id']);
				$subtotal = checkTotal($link);
				$docdisc = is_null(docdiscount($link)) ? 0 : docdiscount($link);
				$linedisc = linediscountTotal($link);
				$response['line'] = $linedisc;

				$response['stat'] = 1;
				$type = array('','Supplier','Customer','V.I.P.');
				while ($row = $query->fetch_object()) 
				{
					$response['code'] = $row->ci_code;
					$response['name'] = ucwords($row->ci_name);
					$response['type'] = $type[$row->ci_type];
					$response['address'] = $row->ci_address;
				}

				$debit = getARBalance($link,$code,'ar_dbamt');
				$credit = getARBalance($link,$code,'ar_cramt');
				$total = $debit - $credit;
				$response['ar'] = $total;
				$response['discount'] = $totaldis;
			}
			else 
			{
				$response['msg'] = 'Customer code not found.';
			}
		}
		else 
		{
			$response['msg'] = $link->error;
		}

		echo json_encode($response);
	}
	elseif ($request=='ar_paymentheadoffice') 
	{
		$arcode = $_POST['artype'];

		$remarks = $link->real_escape_string(trim($_POST['remarks']));

		$ip = get_ip_address();

		if($arcode==1)
		{
			$ardesc ='Head Office';
		}
		else 
		{
			$ardesc ='Subsidiary Admin';
		}

		$ccode = $_POST['customercodehide'];
		$response['stat'] = 0;

		$tn = getLastTransnumByStore($link);

		$link->autocommit(FALSE);

        $query = $link->query(
        	"INSERT INTO 
        		`transaction_stores`
        	(
        		`trans_sid`, 
        		`trans_number`, 
        		`trans_cashier`, 
        		`trans_store`, 
        		`trans_datetime`,
        		`trans_type`,
        		`trans_ip_address` 
	
        	) 
        	VALUES 
        	(
        		'',
        		'$tn',
        		'".$_SESSION['gccashier_id']."',
        		'".$_SESSION['gccashier_store']."',
        		NOW(),
        		3,
        		'$ip'
        	)
        ");

        if($query)
        {
        	$id =  $link->insert_id;
        	$hasError = 0;
        	// get temp sales
        	$temp_sales = getTempSales($link,$_SESSION['gccashier_id']);
        	foreach ($temp_sales as $ts) 
        	{
				$query_ins_sales = $link->query(
					"INSERT INTO 
						`transaction_sales`
					(
						`sales_transaction_id`, 
						`sales_barcode`, 
						`sales_denomination`, 
						`sales_gc_type`
					) 
					VALUES 
					(
						'$id',
						'$ts->ts_barcode_no',
						'$ts->denom_id',
						'$ts->loc_gc_type'
					)	
				");

				if($query_ins_sales)
				{
					$query_tag = $link->query(
						"UPDATE 
							`store_received_gc` 
						SET 
							`strec_sold`='*', 
							`strec_return` =''
						WHERE 
							`strec_barcode` ='$ts->ts_barcode_no'        					 
					");

					if($query_tag)
					{
						//check if barcode has line discount
						$query_linedisc = $link->query(
							"SELECT
								`tsd_barcode`,
								`tsd_disc_type`,
								`tsd_disc_percent`,
								`tsd_disc_amt`,
								`tsd_cashier`,
								`tsd_discountby`
							FROM 
								`temp_sales_discountby` 
							WHERE 
								`tsd_barcode`='$ts->ts_barcode_no'
						");

						if($query_linedisc)
						{
							if($query_linedisc->num_rows > 0)
							{
								while ($row_disc = $query_linedisc->fetch_object()) 
								{
									$query_ins_disc = $link->query(
										"INSERT INTO 
											`transaction_linediscount`
										(
										    `trlinedis_sid`, 
										    `trlinedis_barcode`, 
										    `trlinedis_disctype`, 
										    `trlinedis_discpercent`, 
										    `trlinedis_discamt`, 
										    `trlinedis_by`
										) 
										VALUES 
										(
										    '$id',
										    '$ts->ts_barcode_no',
										    '$row_disc->tsd_disc_type',
										    '$row_disc->tsd_disc_percent',
										    '$row_disc->tsd_disc_amt',
										    '$row_disc->tsd_discountby'
										)
									");

									if(!$query_ins_disc)
									{
										$hasError = 1;
										break;
									}
								}

							}
						}
						else 
						{
							$hasError = 1;
							break; 						
						}										
					}
					else 
					{
						$hasError = 1;
						break; 							
					}
				}
				else 
				{
					$hasError = 1;
					break; 
				}        		
        	}

        	if(!$hasError)
        	{	
				$items = numRows($link,'temp_sales','ts_cashier_id',$_SESSION["gccashier_id"]);
				$stotal = checkTotalwithoutLineDiscount($link);
				$docdisc = docdiscount($link);
				$linedisc =  linediscountTotal($link);
				$cusdiscount = getTotalDiscount($link,$ccode,$_SESSION['gccashier_id']);
				$totaldiscounts = $docdisc + $linedisc + $cusdiscount;
				$total_charge = $stotal - $totaldiscounts;

				$query_pay = $link->query(
					"INSERT INTO 
						`transaction_payment`
					(
					    `payment_trans_num`, 
					    `payment_items`, 
					    `payment_stotal`, 
					    `payment_amountdue`,
					    `payment_docdisc`, 
					    `payment_linediscount`, 
					    `payment_tender`,
					    `payment_internal_discount`

					) 
					VALUES 
					(
					    '$id',
					    '$items',
					    '$stotal',
					    '$total_charge',
					    '$docdisc',
					    '$linedisc',
					    '3',
					    '$cusdiscount'
					)
				");	

				if($query_pay)
				{
					$docdiscby = getTempDiscountBy($link,$_SESSION['gccashier_id']);
					if(count($docdiscby)>0)
					{
						foreach ($docdiscby as $ddby) 
						{
							$query_docdiscby = $link->query(
								"INSERT INTO 
									`transaction_docdiscount`
								(
									`trdocdisc_trid`, 
									`trdocdisc_disctype`, 
									`trdocdisc_prcnt`,
									`trdocdisc_amnt`, 
									`trdocdisc_superby`
								) 
								VALUES 
								(
									'$id',
									'$ddby->docdis_discountype',
									'$ddby->docdis_pecentage',
									'$ddby->docdis_amt',
									'$ddby->docdis_superid'
								);
							");

							if(!$query_docdiscby)
							{
								$hasError = 1;
								break;
							}
						}
					}

					if(!$hasError)
					{
						//insert internal customer ar
		        		$query_ar = $link->query(
		        			"INSERT INTO 
		        				`customer_internal_ar`
		        			(
		        				ar_cuscode, 
		        				ar_datetime, 
		        				ar_transno, 
		        				ar_dbamt,
		        				ar_trans_id,
		        				ar_adj,
		        				ar_type,
		        				ar_trans_remarks
		        			) 
		        			VALUES 
		        			(
		        				
		        				'$ccode',
		        				NOW(),
		        				'$tn',
		        				'$total_charge',
		        				'$id',
		        				'$totaldiscounts',
		        				1,
		        				'$remarks'

		        			)
		        		");

		        		if($query_ar)
		        		{
		        			$storesales = getStoreSales($link,$_SESSION['gccashier_id']);
							foreach ($storesales as $st) 
							{
								$query_entry = $link->query(
									"INSERT INTO 
										`entry_store_sales`
									(
										`ess_type`, 
										`ss_ref_id`, 
										`ess_denom`, 
										`ess_scode`, 
										`ess_pcs`, 
										`ess_amount`
									) 
									VALUES 
									(
										'SS',
										'$id',
										'$st->denom_id',
										'".$_SESSION['gccashier_store']."',
										'$st->counter',
										'$st->sums'
									)
								");

								if(!$query_entry)
								{
									$hasError = 1;
									break;
								}
							}

							if(!$hasError)
							{
								if(insertBudgetLedger($link,$id,'STORESALES','bdebit_amt',$stotal))
								{
									//delete temp sales
									$table = "temp_sales";
									$where = "WHERE `ts_cashier_id`=".$_SESSION['gccashier_id']."";
									if(deleteData($link,$table,$where))
									{
										//delete doc discount
										$table = 'temp_sales_discountby';
										$where = "WHERE `tsd_cashier`=".$_SESSION['gccashier_id']."";
										if(deleteData($link,$table,$where))
										{
											//delete all line disc 
											$table = 'temp_sales_docdiscount';
											$where = "WHERE `docdis_cashierid`=".$_SESSION['gccashier_id']."";
											if(deleteData($link,$table,$where))
											{
												$discount = $docdisc+$linedisc+$cusdiscount;
												storeLedger($link,$id,1,$total_charge,'GCS','GC Sales ('.$ardesc.')',$_SESSION['gccashier_store'],$discount);
												
												$link->commit();

												$debit = getARBalance($link,$ccode,'ar_dbamt');
												$credit = getARBalance($link,$ccode,'ar_cramt');
												$total = $debit - $credit;											

												$response['receipt'] = getstorereceiptstatus($link,$_SESSION['gccashier_store']);

												$response['stat'] = 1;
												$fullname = getField($link,'ci_name','customer_internal','ci_code',$ccode);
												$response['fullname'] = ucwords($fullname);
												$response['transactnum'] = $tn;
												$response['amtdue'] = $total_charge;
												$response['sub'] = $stotal;
												$response['linedisc'] = $linedisc;
												$response['docdisc'] =  $docdisc;	
												$response['total'] = $total_charge;
												$response['noitems'] = $items;
												$response['balance'] = $total;
												$response['cusdiscount'] = $cusdiscount;

												// $response['stat'] = 1;
												// $response['numitems'] = $items;
												// $response['transactnum'] = $tn;										
												// $response['amt_due'] = $total_charge;
												// $response['linedisc'] = $linedisc;
												// $response['docdisc'] =  $docdisc;							
											}
											else 
											{
												$response['msg'] = $link->error; 
											}
										}
										else 
										{
											$response['msg'] = $link->error; 
										}

									}
									else 
									{
										$response['msg'] = $link->error; 
									}									
								}
								else 
								{
									$response['msg'] = $link->error;
								}
							}
							else 
							{
								$response['msg'] = $link->error;
							}
		        		}
		        		else 
		        		{
		        			$response['msg'] = $link->error;
		        		}
					}
					else 
					{
						$response['msg'] = $link->error;
					}

				}
				else 
				{
					$response['msg'] = $link->error;
				}
        	}
        	else 
        	{
        		 $response['msg'] = $link->error; 
        	}			
        }
        else 
        {
        	$response['msg'] = $link->error;
        } 
		echo json_encode($response);
	}
	elseif($request=='linediscount')
	{
		$total = linediscountTotal($link);
		if(is_null($total))
		{						
			echo '₱ 0.00';
		} else {
			echo '₱ '.number_format($total,2);
		}
	}
	elseif($request=='checkIFhasTempSalesForDiscount')
	{
		$barcode = $_POST['bcode'];
		$response['stat'] = 0;

		if(numRowsWhereTwo($link,'temp_sales',$barcode,'ts_barcode_no','ts_cashier_id',$barcode,$_SESSION['gccashier_id'])>0)
		{
			//get denomination
			$denom = getDenominationByBarcode($link,$barcode);
			$response['stat'] = 1;
			$response['den'] = $denom;
		}
		else 
		{
			$response['msg'] = 'GC Barcode # '.$barcode. ' not found.';
		}

		echo json_encode($response);
	}
	elseif ($request=='linediscountbarcode') 
	{
		$barcode = $_POST['barcode'];
		$discountype = $_POST['discountype'];
		$percent = $_POST['percent'];
		$amount = $_POST['amount'];
		$amount = str_replace(',', '', $amount);
		$response['stat'] = 0;

		$super = isset($_SESSION['gc_super_id']) ? $_SESSION['gc_super_id'] : '';
		
		$link->autocommit(FALSE);
		$where = 'WHERE `tsd_barcode`="'.$barcode.'"';
		if(numRowsWithSelect($link,'temp_sales_discountby','tsd_barcode',$where)>0)
		{
			$query_discount = $link->query(
				"UPDATE 
					`temp_sales_discountby` 
				SET 
					`tsd_disc_type`='$discountype',
					`tsd_disc_percent`='$percent', 
					`tsd_disc_amt`='$amount',
					`tsd_cashier`='".$_SESSION['gccashier_id']."',
					`tsd_discountby`='$super'
				WHERE
					`tsd_barcode`='$barcode'
				AND
					`tsd_cashier`='".$_SESSION['gccashier_id']."'
			");
		}
		else 
		{
			$query_discount = $link->query(
				"INSERT INTO 
					`temp_sales_discountby`
				(
					`tsd_barcode`, 
					`tsd_disc_type`, 
					`tsd_disc_percent`, 
					`tsd_disc_amt`, 
					`tsd_cashier`, 
					`tsd_discountby`
				) 
				VALUES 
				(
					'$barcode',
					'$discountype',
					'$percent',
					'$amount',
					'".$_SESSION['gccashier_id']."',
					'$super'
				)
			");
		}
		$response['stat'] = 1;
		$link->commit();

		// $query_up = $link->query(
		// 	"UPDATE 
		// 		`temp_sales` 
		// 	SET 
		// 		`ts_disc_type`='$discountype',
		// 		`ts_disc_percent`='$percent',
		// 		`ts_disc_amt`='$amount'
		// 	WHERE 
		// 		`ts_barcode_no`='$barcode'
		// ");		
		// if($query_up)
		// {
		// 	//check if barcode exist in 
		// 	$where = 'WHERE `tsd_barcode`="'.$barcode.'"';
		// 	if(numRowsWithSelect($link,'temp_sales_discountby','tsd_barcode',$where)>0)
		// 	{
		// 		$query_discount = $link->query(
		// 			"UPDATE 
		// 				`temp_sales_discountby` 
		// 			SET 
		// 				`tsd_cashier`='".$_SESSION['gccashier_id']."',
		// 				`tsd_discountby`='".$_SESSION['gc_super_id']."'
		// 			WHERE
		// 				`tsd_barcode`='$barcode'
		// 			AND
		// 				`tsd_cashier`='".$_SESSION['gccashier_id']."'
		// 		");
		// 	}
		// 	else 
		// 	{
		// 		$query_discount = $link->query(
		// 			"INSERT INTO 
		// 				`temp_sales_discountby`
		// 			(
		// 				`tsd_barcode`, 
		// 				`tsd_cashier`, 
		// 				`tsd_discountby`
		// 			) 
		// 			VALUES 
		// 			(
		// 				'$barcode',
		// 				'".$_SESSION['gccashier_id']."',
		// 				'".$_SESSION['gc_super_id']."'
		// 			)
		// 		");
		// 	}
		// 	if($query_discount)
		// 	{
		// 		$link->commit();
		// 		$response['stat'] =1;				
		// 	}
		// 	else 
		// 	{
		// 		$response['msg'] = $link->error;
		// 	}
		// }
		// else 
		// {
		// 	$response['msg'] = $link->error;
		// }

		echo json_encode($response);
	}
	elseif($request=='amtdue')
	{
		$stotal = checkTotal($link);
		// $line = linediscountTotal($link);
		// $line = is_null($line) ? 0 : $line;
		$docdisc = docdiscount($link);
		$docdisc = is_null($docdisc) ? 0 : $docdisc;
		// $totaldisc = $line + $docdisc;
		$total = $stotal - $docdisc;
		if(is_null($total))
		{						
			echo '₱ .00';
		} 
		else 
		{
			echo '₱ '.number_format($total,2);
		}
	}
	elseif ($request=='getAmtDueAndDiscount') 
	{
		$stotal = checkTotal($link);
		$line = linediscountTotal($link);
		$line = is_null($line) ? 0 : $line;
		$docdisc = docdiscount($link);
		$docdisc = is_null($docdisc) ? 0 : $docdisc;
		$totaldisc = $line + $docdisc;
		$total = $stotal - $docdisc;
		$response['line'] = $line;
		$response['total'] = $stotal;
		$response['amtdue'] = $total;
		$response['docdisc'] = $docdisc;

		echo json_encode($response); 
	}
	elseif ($request=='docdiscount') 
	{
		$dd = docdiscount($link);
		echo is_null($dd) ? '₱ 0.00' : '₱ '.number_format($dd,2);
	}
	elseif ($request=='transactiondisc') 
	{
		$response['stat'] = 0;
		$dt = $_POST['dt'];
		$tv = $_POST['tv'];
		$p = $_POST['p'];
		$a = $_POST['a'];
		$a = str_replace( ',', '', $a );
		$super = isset($_SESSION['gc_super_id']) ? $_SESSION['gc_super_id'] : '';

		// if(isset($_SESSION['gc_super_id']))
		// {
			//check if this transaction already have doc discount

			if(checkifhasdocdiscount($link)>0)
			{
				$query_up = $link->query(
					"UPDATE 
						`temp_sales_docdiscount` 
					SET 
						`docdis_superid`='$super',
						`docdis_discountype`='$dt',
						`docdis_pecentage`='$p',
						`docdis_amt`='$a' 
					WHERE 
						`docdis_cashierid`='".$_SESSION['gccashier_id']."'
				");

				if($query_up)
				{
					$response['stat'] =1;
				}
				else 
				{
					$response['msg'] = $link->error; 
				}

			}
			else 
			{
				$query = $link->query(
					"INSERT INTO 
						`temp_sales_docdiscount`
					(
						`docdis_cashierid`, 
						`docdis_superid`, 
						`docdis_discountype`, 
						`docdis_pecentage`, 
						`docdis_amt`
					) 
					VALUES 
					(
						'".$_SESSION['gccashier_id']."',
						'$super',
						'$dt',
						'$p',
						'$a'
					)
				");	

				if($query)
				{
					$response['stat'] =1;
				}
				else 
				{
					$response['msg'] = $link->error; 
				}		
			}
		// }
		// else 
		// {
		// 	$response['msg'] = 'You need manager access to save this discount.';
		// }
		echo json_encode($response);
	}
	elseif ($request=='removealldiscline') 
	{
		$hasError = 0;
		$response['stat'] = 0;
		$super = isset($_SESSION['gc_super_id']) ? $_SESSION['gc_super_id'] : '';
		// if(isset($_SESSION['gc_super_id']))
		// {
			$link->autocommit(FALSE);
			$d = getAllLineDiscByManagerAndCashier($link,$super,$_SESSION['gccashier_id']);

			if(!$hasError)
			{
				$query_del = $link->query(
					"DELETE FROM 
						`temp_sales_discountby` 
					WHERE 
						`tsd_cashier`='".$_SESSION['gccashier_id']."'
				");

				if($query_del)
				{
					$link->commit();
					$response['stat'] = 1;
				}
				else 
				{
					$response['msg'] = $link->error;
				}
			}	
			else
			{
				$response['msg'] = $link->error;
			}		

		// }
		// else 
		// {
		// 	$response['msg'] = 'You need manager access to perform this action.';
		// }
		echo json_encode($response);
	}
	elseif ($request=='removetransactiondisc') 
	{
		$response['stat'] = 0;
		$link->autocommit(FALSE);
		$query_del = $link->query(
			"DELETE FROM 
				`temp_sales_docdiscount` 
			WHERE
				`docdis_cashierid`='".$_SESSION['gccashier_id']."'
		");

		if($query_del)
		{
			$link->commit();
			$response['stat'] = 1;
		}
		else 
		{
			$response['msg'] = $link->error;
		}
		echo json_encode($response);
	}
	elseif ($request=='checkifhasdocdisc') 
	{
		$response['stat'] = 0;
		$query = $link->query(
			"SELECT 
				`docdis_cashierid` 
			FROM 
				`temp_sales_docdiscount` 
			WHERE 
				`docdis_cashierid`='".$_SESSION['gccashier_id']."'
		");

		if($query)
		{
			if($query->num_rows > 0)
			{
				$response['stat'] = 1;
			}
			else 
			{
				$response['msg'] = 'There is no subtotal discount exist in this transaction.';
			}
		}
		else 
		{
			$response['msg'] = $link->error;
		}
		echo json_encode($response);
	}
	elseif ($request=='checkifhaslinedisc') 
	{
		$response['stat'] = 0;
		$super = isset($_SESSION['gc_super_id']) ? $_SESSION['gc_super_id'] : '';
		// if(isset($_SESSION['gc_super_id']))
		// {
		$d = getAllLineDiscByManagerAndCashier($link,$super,$_SESSION['gccashier_id']);
		if(count($d)>0)
		{
			$response['stat']=1;
		}
		else 
		{
			$response['msg'] = 'There is no Line discount exist in this transaction.';
		}
		// }
		// else 
		// {
		// 	$response['msg'] = 'You need manager access to perform this action.';
		// }

		echo json_encode($response);
	}
	elseif ($request=='posreport') 
	{
		$response['stat'] = 0;
		$d1 = _dateFormatoSql($_POST['d1']);
		$d2 = _dateFormat($_POST['d2']);
		$transtype = $_POST['trans'];
		$d1 = $d1.' 01:00:00';
		$d2 = $d2.' 24:00:00'; 
		if(strtotime($todays_date) >= strtotime(_dateFormatoSql($_POST['d1'])))
		{
			if(strtotime($d1) <= strtotime($d2))
			{
				//check if has rows
				// $numoftrans = count(getNumberofTrans($link,$d1,$d2,$_SESSION['gccashier_id'],$_SESSION['gccashier_store'],$transtype));
				// if($numoftrans>0)
				// {
					$response['stat']=1;
				// }
				// else 
				// {
				// 	$response['msg'] = 'No Transaction found.';
				// }
			}
			else 
			{
				$response['msg'] = 'Date Started is greater than date end.';	
			}
		}
		else 
		{
			$response['msg'] = "Invalid Date.";
		}

		echo json_encode($response);
	}
	elseif ($request=='eoschecktrans') 
	{
		$eos = getEOSTrans($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id']);
		$response['st'] = 0;
		if(is_array($eos))
		{
			if(count($eos)>0)
			{
				$response['st'] = 1;
			}
			else 
			{
				$response['msg'] = 'No Transaction exist.';
			}
		}
		else 
		{
			$response['msg'] = $eos;
		}

		echo json_encode($response);
	}
	elseif ($request=='totals') 
	{			
		$stotal = checkTotalwithoutLineDiscount($link);
		$line = linediscountTotal($link);
		$docdisc = docdiscount($link);
		$docdisc = is_null($docdisc) ? 0.00 : $docdisc;
		$total = $stotal - ($docdisc + $line);

  		$subtotal = is_null($stotal) ? 0.00 : number_format($stotal,2);
  		$amtdue = is_null($total) ? 0.00 : number_format($total,2);
  		$linedisc = is_null($line) ? 0.00 : number_format($line,2);
  		$no_items = numRows($link,'temp_sales','ts_cashier_id',$_SESSION["gccashier_id"]);

  		//check if transaction has docdiscount
  		$query = $link->query("SELECT docdis_id FROM temp_sales_docdiscount WHERE docdis_cashierid='".$_SESSION['gccashier_id']."'");
  		if($query->num_rows>0)
  		{
  			$link->query("UPDATE temp_sales_docdiscount SET docdis_amt='$docdisc' WHERE docdis_cashierid = '".$_SESSION['gccashier_id']."'");
  		}

  		$response['sbtotal'] = $subtotal;
  		$response['amtdue'] = $amtdue;
  		$response['linedisc'] = $linedisc;
  		$response['docdiscount'] = number_format($docdisc,2);
  		$response['noitems'] = $no_items;
  		echo json_encode($response);
	}
	elseif ($request=='counttempsales') 
	{
		$response['cnt'] = countTempGC($link,$_SESSION['gccashier_id']);
		echo json_encode($response);
	}
	elseif($request=='supervisorlogin') 
	{
		$response['st'] = 0;
		//uname,uid,upass
		$uname = $link->real_escape_string(trim($_POST['uname']));
		$uid = $link->real_escape_string(trim($_POST['uid']));
		$upass = trim($_POST['upass']);

		$upass = md5($upass);

		if(!empty($uname)&&
			!empty($uid)&&
			!empty($upass))
		{
			if(checkStoreLoginCredential($link,$uname,$uid,$upass,'manager',$_SESSION['gccashier_store']))
			{
				$response['st'] = 1;				
			}
			else  
			{
				$response['msg'] = 'Invalid user credentials.';
			}
		}
		else 
		{
			$response['msg'] = 'Please fill all fields.';
		}
		echo json_encode($response);
	}	
	elseif($request=='scanrevalidate')
	{
		$barcode = $_POST['value'];
		$response['st'] = 0;
	    $store = $_SESSION['gccashier_store'];
	    $isFound = false;
	    $ip = get_ip_address();
	    if(checkifEODperformed($link,$store,$ip))
	    {
	      $response['msg'] = 'Please perform End of Day first.';
	    }
	    else 
	    {
			if(checkIfExist($link,'barcode_no','gc','barcode_no',$barcode))
			{
				$isFound = true;

			}
			elseif(checkIfExist($link,'spexgcemp_barcode','special_external_gcrequest_emp_assign','spexgcemp_barcode',$barcode)) 
			{
				$isFound = true;
			}	    	

			if(!$isFound)
			{
				$response['msg'] = 'GC Barcode # '.$barcode.' not found.';
			}
			elseif(!numRows($link,'store_verification','vs_barcode',$barcode)>0)
			{
				$response['msg'] = 'GC Barcode # '.$barcode.' is not yet verified.';
			}
			else 
			{
				$select = 'store_verification.vs_barcode,
					stores.store_name';
				$where = 'store_verification.vs_barcode="'.$barcode.'"
						AND
					store_verification.vs_store='.$_SESSION['gccashier_store'];
				$join = 'INNER JOIN
					stores
						ON
					stores.store_id = store_verification.vs_store';
				$limit = 'ORDER BY
					store_verification.vs_id
				DESC';
				$gcv = getSelectedData($link,'store_verification',$select,$where,$join,$limit);

				if(is_null($gcv))
				{
					$select = 'store_verification.vs_barcode,
						stores.store_name';
					$where = 'store_verification.vs_barcode='.$barcode;
					$join = 'INNER JOIN
						stores
							ON
						stores.store_id = store_verification.vs_store';
					$limit = 'ORDER BY
						store_verification.vs_id
					DESC';	
					$st = getSelectedData($link,'store_verification',$select,$where,$join,$limit);
					$response['msg'] = 'GC Barcode # '.$barcode.' was verified at '.$st->store_name;
				}
				elseif(checkifValidatedToday($link,$barcode))
				{
					$response['msg'] = 'GC Barcode # '.$barcode.' verified today.';
				}
				elseif(checkIfReverifiedToday($link,$barcode)) 
				{
					$response['msg'] = 'GC Barcode # '.$barcode.' reverified today.';
				}
				elseif(!checkDateIfLesserThanCurdate($link,$barcode))
				{
					//check if date validated is lesser than 
					$response['msg'] = 'GC Barcode # '.$barcode.' has invalid verification date.';
				}
				elseif (numRowsWhereTwo($link,'store_verification','vs_barcode','vs_barcode','vs_tf_used',$barcode,'*')>0) 
				{
					//check if gc already have transactions...
					$response['msg'] = 'GC Barcode # '.$barcode.' has transactions.';
				}
				elseif(numRows($link,'temp_reval','treval_barcode',$barcode)>0)
				{
					$response['msg'] = 'GC Barcode # '.$barcode.' already scanned for revalidation.';
				}
				else
				{
					$gcFound = false;
					$hasError = false;

					$query_reval = $link->query(
						"SELECT 
							reval_barcode
						FROM 
							transaction_revalidation
						INNER JOIN
							transaction_stores
						ON
							transaction_stores.trans_sid = transaction_revalidation.reval_trans_id
						WHERE 
							transaction_revalidation.reval_barcode='".$barcode."'
						AND
							DATE(transaction_stores.trans_datetime) = CURDATE()
						ORDER BY
							reval_id
						DESC
						LIMIT 1
					");

					if(!$query_reval)
					{
						$response['msg'] = $link->error;
						$hasError = true;
					}
					else 
					{
						if($query_reval->num_rows > 0)
						{
							$gcFound = true;
							$response['msg'] = 'GC Barcode # '.$barcode.' revalidated today.';
						}
					}	

					if(!$hasError)
					{
						if(!$gcFound)
						{
							$denom = getField($link,'vs_tf_denomination','store_verification','vs_barcode',$barcode);

							$rpayment = getField($link,'app_settingvalue','app_settings','app_tablename','revalidation_charge');	

							$rpayment = $denom * $rpayment;	

							$link->autocommit(FALSE);
							$query = $link->query(
								"INSERT INTO 
									temp_reval
								(
									treval_barcode, 
									treval_by, 
									treval_store,
									treval_charge
								) 
								VALUES 
								(
									'$barcode',
									'".$_SESSION['gccashier_id']."',
									'".$_SESSION['gccashier_store']."',
									'$rpayment'
								)
							");

							if($query)
							{												
								$link->commit();
								$total = 0;
								$gccount = 0;

								$query_tot = $link->query(
									"SELECT 
										SUM(temp_reval.treval_charge) as totcharge,
										COUNT(temp_reval.treval_charge) as gccount
									FROM 
									 	temp_reval 
									WHERE 
										temp_reval.treval_by='".$_SESSION['gccashier_id']."'
									AND
										temp_reval.treval_store='".$_SESSION['gccashier_store']."'
								");

								if($query_tot)
								{
									$row = $query_tot->fetch_object();
									$total = $row->totcharge;
									$gccount = $row->gccount;
								}

								$response['st'] = 1;
								$response['total'] = number_format($total,2);
								$response['count'] = $gccount;
							}
							else 
							{
								$response['msg'] = $link->error;
							}							
						}

					}
				}
			}

		}
		echo json_encode($response);
	}
	elseif ($request=='loadreval') 
	{
		$tablerows = 10;
		$select = '	store_verification.vs_barcode,
			store_verification.vs_tf_denomination,
			gc_type.gctype,
			store_verification.vs_gctype,
			store_verification.vs_date,
			temp_reval.treval_barcode,
			temp_reval.treval_charge';
		$where ='treval_by ='.$_SESSION['gccashier_id'];
		$join = 'INNER JOIN
				store_verification
			ON
				store_verification.vs_barcode = temp_reval.treval_barcode
			INNER JOIN
				gc_type
			ON
				gc_type.gc_type_id = store_verification.vs_gctype';
		$orderby = ' ORDER BY treval_id DESC';
		$gc = getAllData($link,'temp_reval',$select,$where,$join,$orderby);
		$revaltemp = count($gc);
		if($revaltemp > 10)
		{
			$tablerows = 0;
		}
		else 
		{
			$tablerows = $tablerows - $revaltemp;
		}
		foreach ($gc as $g): ?>
			<tr>
				<td class="btnsidetdrev"><button onclick="voidbylinereval(<?php echo $g->treval_barcode; ?>);" class="btnside">></button></td>
				<td class="barcodetdrev"><?php echo $g->treval_barcode; ?></td>
				<td class="typetdrev"><?php echo ucwords(str_replace('special', '', $g->gctype)); ?></td>
				<td class="denomtdrev"><?php echo number_format($g->vs_tf_denomination,2); ?></td>
				<td class="soldrelrevtdrev">
					<?php 
						if($g->vs_gctype=='1' || $g->vs_gctype=='2')
						{
							// $select = "transaction_stores.trans_datetime";
							// $where ="transaction_sales.sales_barcode='".$g->treval_barcode."' AND transaction_sales.sales_item_status=0";
							// $join ="INNER JOIN transaction_stores ON transaction_stores.trans_sid = transaction_sales.sales_transaction_id";
							// $order = "ORDER BY transaction_sales.sales_id DESC";
							// $daterel = getSelectedData($link,'transaction_sales',$select,$where,$join,$order);
							// echo _dateFormat($daterel->trans_datetime);	

							//check if institution gc

							$datesold = '';

							$select = "institut_transactions.institutr_date ";
							$where = "gc.barcode_no='".$g->treval_barcode."'";
							$join = "INNER JOIN institut_transactions_items ON institut_transactions_items.instituttritems_barcode = gc.barcode_no
								INNER JOIN institut_transactions ON institut_transactions.institutr_id = institut_transactions_items.instituttritems_trid ";
							$order="";
							$daterel = getSelectedData($link,'gc',$select,$where,$join,$order);
							
							if(count($daterel) > 0)
							{
								$datesold = $daterel->institutr_date;
							}
							else 
							{
								$select = "transaction_stores.trans_datetime";
								$where ="transaction_sales.sales_barcode='".$g->treval_barcode."' AND transaction_sales.sales_item_status=0";
								$join ="INNER JOIN transaction_stores ON transaction_stores.trans_sid = transaction_sales.sales_transaction_id";
								$order = "ORDER BY transaction_sales.sales_id DESC";
								$daterel = getSelectedData($link,'transaction_sales',$select,$where,$join,$order);	
								$datesold = $daterel->trans_datetime;
							}

							echo _dateFormat($datesold);

						}
						elseif ($g->vs_gctype=='3') 
						{
							// special external
							$select = 'approved_request.reqap_date';
							$where = "spexgcemp_barcode='".$g->treval_barcode."'
								AND
									approved_request.reqap_approvedtype='special external releasing'";
							$join = 'INNER JOIN
									approved_request
								ON
									approved_request.reqap_trid = special_external_gcrequest_emp_assign.spexgcemp_trid';
							$order = '';
							$daterel = getSelectedData($link,'special_external_gcrequest_emp_assign',$select,$where,$join,$order);
							echo _dateFormat($daterel->reqap_date);

						}
						elseif ($g->vs_gctype=='4')
						{
							// promo
							$select  = 'promogc_released.prgcrel_at';
							$where = 'promogc_released.prgcrel_barcode='.$g->treval_barcode;
							$daterel = getSelectedData($link,'promogc_released',$select,$where,'','');
							echo _dateFormat($daterel->prgcrel_at);	
						}

					?>					
				</td>
				<td class="verifiedtdrev"><?php echo _dateFormat($g->vs_date); ?></td>
				<td class="paymentrevtd"><?php echo number_format($g->treval_charge,2); ?></td>
			</tr>
		<?php endforeach; ?>
		<?php for($x=0; $x<$tablerows; $x++): ?>
			<tr>
				<td class="btnsidetdrev"></td>
				<td class="barcodetdrev"></td>
				<td class="typetdrev"></td>
				<td class="denomtdrev"></td>
				<td class="soldrelrevtdrev"></td>
				<td class="verifiedtdrev"></td>
				<td class="paymentrevtd"></td>
			</tr>
		<?php endfor; ?>
			<script>
				  $('table tbody._barcodesreval tr td button').blur(
				      function(){
				         $(this).closest('tr').css('background-color','white');
				    }).focus(function() {
				    $(this).closest('tr').css('background-color','yellow');
				  });
			</script>
	<?php 
	}
	elseif ($request=='checkrevalgc') 
	{
		$response['msg'] = numRowsWhereTwo($link,'temp_reval','	treval_barcode','treval_by','treval_store',$_SESSION['gccashier_id'],$_SESSION['gccashier_store']);
		echo json_encode($response);
	}
	elseif ($request=='truncatetempreval') 
	{
		$query_del = $link->query(
			"
		");
	}
	elseif ($request=='scanrefund')
	{
		$barcode = $_POST['value'];
		$response['st'] = 0;
	    $store = $_SESSION['gccashier_store'];
	    $ip = get_ip_address();
	    if(checkifEODperformed($link,$store,$ip))
	    {
	      $response['msg'] = 'Please perform End of Day first.';
	    }
	    else 
	    {
	    	//check if gcbarcode sold in this store.
			$query_c = $link->query(
				"SELECT 
					transaction_stores.trans_type,
				    transaction_sales.sales_barcode, 
				    store_received_gc.strec_sold,
				    store_received_gc.strec_return,
					store_verification.vs_barcode,
					temp_refund.trfund_barcode,
					transaction_linediscount.trlinedis_discamt,
					transaction_sales.sales_transaction_id
				FROM 
					transaction_sales 
				INNER JOIN
					transaction_stores
				ON
					transaction_stores.trans_sid = transaction_sales.sales_transaction_id
				INNER JOIN
					store_received_gc
				ON
					store_received_gc.strec_barcode = transaction_sales.sales_barcode
				LEFT JOIN
					store_verification
				ON
					store_verification.vs_barcode = transaction_sales.sales_barcode
				LEFT JOIN
					temp_refund
				ON
					temp_refund.trfund_barcode = transaction_sales.sales_barcode
				LEFT JOIN
					transaction_linediscount
				ON
					transaction_linediscount.trlinedis_barcode = transaction_sales.sales_barcode
				WHERE
					transaction_sales.sales_barcode = '$barcode'
				AND
					transaction_stores.trans_store = '$store'
				ORDER BY 
					transaction_sales.sales_id
				DESC
				LIMIT 1
			");	    	

			if($n = $query_c->num_rows > 0)
			{
				$row = $query_c->fetch_object();
				if($row->strec_return == '')
				{
					if(is_null($row->vs_barcode))
					{
						if(is_null($row->trfund_barcode))
						{
							// get barcode number transaction number first
							if($row->trans_type=='1')
							{

								$subtotaldisc = getTransactionSubtotalDiscount($link,$row->sales_transaction_id);
								//get number of items in a transaction
								$table = 'transaction_sales';
								$select = 'IFNULL(COUNT(sales_id),0) as cnt';
								$where = 'sales_transaction_id='.$row->sales_transaction_id; 
								$field = 'cnt';
								$cnt = countData($link,$table,$select,$where,$field);

								$sdisc = $subtotaldisc / $cnt;

								$sdisc = round($sdisc,2);

								//$response['msg'] = $cnt;

								$query_ins = $link->query(
									"INSERT INTO 
										temp_refund
									(
										trfund_barcode,
										trfund_linedisc,
										trfund_subdisc,
										trfund_store,
										trfund_by
									) 
									VALUES 
									(
										'".$row->sales_barcode."',
										'".$row->trlinedis_discamt."',
										'$sdisc',
										'$store',
										'".$_SESSION['gccashier_id']."'
									)
								");

								if($query_ins)
								{
									//get per refund per piece
									$ref = getTotalRefund($link,$store,$_SESSION['gccashier_id']);

									$sc_setting = getField($link,'app_settingvalue','app_settings','app_tablename','pos_show_service_charge');
									$scharge = 0;
									if($sc_setting=='on')
									{
										$schargeperGC = getField($link,'app_settingvalue','app_settings','app_tablename','refund_charge');
										$scharge = $schargeperGC * $ref->cnt;
									}
									
									$response['refcnt'] = $ref->cnt;
									$response['refsub'] = $ref->subdisc = 0 ? $ref->subdisc : '- '.$ref->subdisc;
									$response['refline'] = $ref->totlinedisc = 0 ? $ref->totlinedisc : '- '.$ref->totlinedisc;
									$response['reftotdenom'] = $ref->denom;
									$response['refamtdue'] = $ref->rfundtot - $scharge;
									$response['scharge'] = $scharge;

									$response['st'] = 1;

								}
								else 
								{
									$response['msg'] = $link->error;
								}

							}
							else 
							{
								$response['msg'] = 'GC Barcode # '.$barcode.' payment is not cash.';
							}

						}
						else 
						{
							$response['msg'] = 'GC Barcode # '.$barcode.' already scanned for refund.';
						}
					}
					else 
					{
						$response['msg'] = 'GC Barcode # '.$barcode.' already verified.';
					}
				}
				else 
				{
					$response['msg'] = 'GC Barcode # '.$barcode.' status is currently returned.';
				}
			}
			else 
			{
				$response['msg'] = 'GC Barcode # '.$barcode.' not found.';
			}
	    }

	    echo json_encode($response);		
	}
	elseif ($request=='loadrefund') 
	{
		$tablerows = 10;
		$select = '	temp_refund.trfund_barcode,
		temp_refund.trfund_linedisc,
		temp_refund.trfund_subdisc,
		denomination.denomination,
		gc_type.gctype';
		//$where ='treval_by ='.$_SESSION['gccashier_id'];
		$where ='temp_refund.trfund_store = '.$_SESSION['gccashier_store'].'
		AND	temp_refund.trfund_by = '.$_SESSION['gccashier_id'];
		$join = 'INNER JOIN
			gc
		ON
			gc.barcode_no = temp_refund.trfund_barcode
		INNER JOIN
			gc_location
		ON
			gc_location.loc_barcode_no = gc.barcode_no
		INNER JOIN
			gc_type
		ON
			gc_type.gc_type_id = gc_location.loc_gc_type
		INNER JOIN
			denomination
		ON
			denomination.denom_id = gc.denom_id';
		$gc = getAllData($link,'temp_refund',$select,$where,$join,'ORDER BY temp_refund.trfund_id DESC');
		$refunditems = count($gc);
		if($refunditems > 10)
		{
			$tablerows = 0;
		}
		else 
		{
			$tablerows = $tablerows - $refunditems;
		}
		foreach ($gc as $g): ?>
			<tr>
				<td class="btnsidetdref"><button onclick="voidbylinerefund(<?php echo $g->trfund_barcode; ?>);" class="btnside">></button></td>
				<td class="barcodetdref"><?php echo $g->trfund_barcode; ?></td>
				<td class="typetdref"><?php echo $g->gctype; ?></td>
				<td class="denomtdref"><?php echo number_format($g->denomination,2); ?></td>
				<td class="linediscref"><?php echo number_format($g->trfund_linedisc,2); ?></td>
				<td class="subdiscref"><?php echo number_format($g->trfund_subdisc,2); ?></td>
			</tr>
		<?php endforeach; ?>
		<?php for($x=0; $x<$tablerows; $x++): ?>
			<tr>
				<td class="btnsidetdref"></td>
				<td class="barcodetdref"></td>
				<td class="typetdref"></td>
				<td class="denomtdref"></td>
				<td class="linediscref"></td>
				<td class="subdiscref"></td>
			</tr>
		<?php endfor; ?>
			<script>
				  $('table tbody._barcodesrefund tr td button').blur(
				      function(){
				         $(this).closest('tr').css('background-color','white');
				    }).focus(function() {
				    $(this).closest('tr').css('background-color','yellow');
				  });
			</script>
		<?php

	}
	elseif ($request=='removerefund') 
	{
		$link->query("DELETE FROM temp_refund WHERE trfund_by='".$_SESSION['gccashier_id']."'");
		$link->query("DELETE FROM service_charge WHERE sc_by = '".$_SESSION['gccashier_id']."'");
	}
	elseif ($request=='refreshrevaltable')
	{
		$tablerows = 10;
		?>
		<?php for($x=0; $x<$tablerows; $x++): ?>
			<tr>
				<td class="btnsidetdref"></td>
				<td class="barcodetdref"></td>
				<td class="typetdref"></td>
				<td class="denomtdref"></td>
				<td class="linediscref"></td>
				<td class="subdiscref"></td>
			</tr>
		<?php endfor;
	}
	elseif ($request=='cntrefunditems') {
		$response['cntref'] = numRowsWhereTwo($link,'temp_refund','trfund_barcode','trfund_by','trfund_store',$_SESSION['gccashier_id'],$_SESSION['gccashier_store']);
		echo json_encode($response);
	}
	elseif ($request=='cntservicechargeitems') 
	{
		$response['cntscharge'] = numRowsWhereTwo($link,'service_charge','sc_charge','sc_by','sc_store',$_SESSION['gccashier_id'],$_SESSION['gccashier_store']);
		echo json_encode($response);
	}
	elseif ($request=='voidlinerefund') 
	{
		$response['st'] = 0;
		$barcode = $_POST['barcode'];
		$query_del = $link->query("DELETE FROM temp_refund WHERE trfund_barcode='$barcode'");
		if($query_del)
		{
			$ref = getTotalRefund($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id']);

			$scharge = getField($link,'app_settingvalue','app_settings','app_tablename','refund_charge');
			//$scharge = getServiceCharge($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id']);
			$sc_setting = getField($link,'app_settingvalue','app_settings','app_tablename','pos_show_service_charge');

			$total = 0;
			$totscharge = 0;
			if($sc_setting=='off')
			{
				$total = $ref->rfundtot;
			}
			else 
			{
				$totscharge = $scharge * $ref->cnt;
				$total = $ref->rfundtot - $totscharge;
			}

			$response['refcnt'] = $ref->cnt;
			$response['refsub'] = $ref->subdisc = 0 ? $ref->subdisc : '- '.$ref->subdisc;
			$response['refline'] = $ref->totlinedisc = 0 ? $ref->totlinedisc : '- '.$ref->totlinedisc;
			$response['reftotdenom'] = $ref->denom;
			$response['scharge'] = $totscharge;
			$response['refamtdue'] = $total;	//$ref->rfundtot - $scharge;

			$response['st'] = 1;
		}
		else 
		{
			$response['msg'] = $link->error;
		}
		echo json_encode($response);
	}
	elseif ($request=='insertrefund') 
	{
		$response['st'] = 0;
		$hasError = false;
		$scharge = $_POST['cash'];
		$ref = getTotalRefund($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id']);
		if($scharge < $ref->rfundtot)
		{
			// check if service charge exist

			$query_sel = $link->query(
				"SELECT 
					sc_charge 
				FROM 
					service_charge 
				WHERE 
					sc_store='".$_SESSION['gccashier_store']."'
				AND
					sc_by = '".$_SESSION['gccashier_id']."'
			");

			if($query_sel->num_rows > 0)
			{
				$query_up = $link->query(
					"UPDATE 
						service_charge 
					SET 
						sc_charge='$scharge',
						sc_datetime= NOW()
					WHERE 
						sc_store = '".$_SESSION['gccashier_store']."'
					AND
						sc_by = '".$_SESSION['gccashier_id']."'
				");

				if(!$query_up)
				{
					$hasError = true;
				}
			}
			else 
			{
				$query_ins = $link->query(
					"INSERT INTO 
						service_charge
					(
					    sc_charge, 
					    sc_datetime, 
					    sc_store, 
					    sc_by
					) 
					VALUES 
					(
					    '$scharge',
					    NOW(),
					    '".$_SESSION['gccashier_store']."',
					    '".$_SESSION['gccashier_id']."'
					)
				");

				if(!$query_ins)
				{
					$hasError = true;
				}
			}

			if(!$hasError)
			{
				$response['st'] = 1;
				$response['scharge'] = $scharge;
				$amtdue = $ref->rfundtot - $scharge;
				$response['refamtdue'] = $amtdue;
			}
			else 
			{
				$response['msg'] = $link->error;
			}

		}
		else 
		{
			$response['msg'] = 'Invalid Amount.';
		}
		echo json_encode($response);
	}
	elseif ($request=='refundgc') 
	{
		$response['st'] = 0;
	    $hasError = 0;
	    $cashier = $_SESSION['gccashier_id'];
	    $store = $_SESSION['gccashier_store'];

	    $ip = get_ip_address();

	    $refund = getTotalRefund($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id']);   
	    //$scharge = getServiceCharge($link,$_SESSION['gccashier_store'],$_SESSION['gccashier_id']);

	    $sc_setting = getField($link,'app_settingvalue','app_settings','app_tablename','pos_show_service_charge');

	    $scharge = 0;
	    $showServiceCharge = false;
	    if($sc_setting=='on')
	    {
	    	$scharge = getField($link,'app_settingvalue','app_settings','app_tablename','refund_charge');
	    	$scharge = $scharge * $refund->cnt;	    	
	    	$showServiceCharge = true;
	    }

	    //get last transaction number
	    $lasttransnum = getLastTransactionNumber($link,$cashier,$store);
	    $lasttransnum++;    
	    $link->autocommit(FALSE);

	    $query = $link->query(
	      "INSERT INTO 
	        transaction_stores
	      (        
	          trans_number, 
	          trans_cashier, 
	          trans_store,
	          trans_datetime, 
	          trans_status, 
	          trans_yreport, 
	          trans_type,
	          trans_ip_address
	      ) 
	      VALUES 
	      (        
	          '$lasttransnum',
	          '$cashier',
	          '$store',   
	          NOW(),
	          0,
	          0,
	          5,
	          '$ip'
	      ) 
	    ");

	    if($query)
	    {
	      $last_insert = $link->insert_id;

	      $totrefund = $refund->rfundtot - $scharge;

	      $query_details = $link->query(
	        "INSERT INTO 
	          transaction_refund_details
	        (
	          trefundd_trstoresid, 
	          trefundd_totgcrefund, 
	          trefundd_total_linedisc, 
	          trefundd_subtotal_disc, 
	          trefundd_servicecharge, 
	          trefundd_refundamt
	        ) 
	        VALUES 
	        (
	          '$last_insert',
	          '$refund->denom',
	          '$refund->totlinedisc',
	          '$refund->subdisc',
	          '$scharge',
	          '$totrefund'
	        )
	      ");

	      if($query_details)
	      {
	        $trefund = getTempRefund($link,$store,$cashier);
	        foreach ($trefund as $t) 
	        {
	          $query_ins = $link->query(
	            "INSERT INTO 
	              transaction_refund
	            (
	                refund_trans_id, 
	                refund_barcode, 
	                refund_denom,
	                refund_linedisc,
	                refund_sdisc
	            ) 
	            VALUES 
	            (
	                '$last_insert',
	                '$t->trfund_barcode',
	                '$t->denom_id',
	                '$t->trfund_linedisc',
	                '$t->trfund_subdisc'
	            )
	          ");

	          if(!$query_ins)
	          {
	            $hasError =1;
	            break;          
	          }

	          $query_up = $link->query(
	            "UPDATE 
	              store_received_gc
	            SET 
	              strec_sold='',
	              strec_return='*' 
	            WHERE
	              strec_barcode='$t->trfund_barcode'
	          ");

	          if(!$query_up)
	          {
	            $hasError = 1;
	            break;
	          }

	          $query_sales = $link->query(
	            "UPDATE 
	              transaction_sales 
	            SET 
	              sales_item_status='1'
	            WHERE 
	              sales_barcode='$t->trfund_barcode'
	            ORDER BY
	              sales_id
	            DESC
	            LIMIT 1
	          ");

	          if(!$query_sales)
	          {
	            $hasError = 1;
	            break;
	          } 
	        }

	        if(!$hasError)
	        {	
	        	$query_getref = $link->query(
	        		"SELECT 
						SUM(denomination.denomination) as refund
					FROM 
						temp_refund
					INNER JOIN
						gc
					ON
						gc.barcode_no = temp_refund.trfund_barcode
					INNER JOIN
						denomination
					ON
						denomination.denom_id = gc.denom_id
					WHERE 
						temp_refund.trfund_store='".$_SESSION['gccashier_store']."'
					AND
						temp_refund.trfund_by='".$_SESSION['gccashier_id']."'	
	        	");

	        	if($query_getref)
	        	{
	        		$row_ref = $query_getref->fetch_object();

	        		storeLedger($link,$last_insert,2,$row_ref->refund,'GCREF','GC Refund',$_SESSION['gccashier_store'],0);
	        			

					$totalref = getTempRefundTotal($link,$store,$cashier);

					if(insertBudgetLedger($link,$last_insert,'STOREREFUND','bcredit_amt',$totalref))
					{
						$table = 'temp_refund';
						$where = 'trfund_store = "'.$_SESSION['gccashier_store'].'"
						    AND
						    trfund_by = "'.$_SESSION['gccashier_id'].'"';
						if(deleteSelectedData($link,$table,$where))
						{
							$link->commit();
							$response['receipt'] = getstorereceiptstatus($link,$_SESSION['gccashier_store']);
							$response['st'] = 1;
							$response['transactnum'] = $lasttransnum;
							$table = '<table class="table resibo"><thead><tr><th>Barcode No </th><th class="receipt_items">Price</th></thead></tr><tbody>';

						foreach ($trefund as $d) 
						{
						  	$table.='<tr><td>'.$d->trfund_barcode.'</td><td class="receipt_items">₱ '.number_format($d->denomination,2).'</td></tr>';
						}
						// $table.='<tr><td>Total: </td><td>₱ '.number_format($totalref,2).'</td></tr>';
							$table.='</tbody></table>';
							$response['items'] = $table;
							$response['noitems']  = count($trefund);
							$response['total'] = $refund->denom;
							$response['linedis'] = number_format($refund->totlinedisc,2);
							$response['subdis'] = $refund->subdisc;
							$response['scharge'] = number_format($scharge,2);
							$response['totalrefund'] = $totrefund;
							$response['showscharge'] = $showServiceCharge;
						}
						else 
						{
							$response['msg'] = 'Delete data error';         
						}							
					}
					else 
					{
						$response['msg'] = $link->error;
					}

				}
				else 
				{
					$response['msg'] = $link->error;
				}
	        } 
	        else 
	        {
	        	$response['msg'] = $link->error;
	        }     
	      }
	      else 
	      {
	      	$response['msg'] = $link->error;
	      }
	    }
	    else 
	    {
	      $response['msg'] = $link->error;
	    }
	    echo json_encode($response);
	}
	elseif ($request=='checksessionx') 
	{
		$response['st'] = 0;
		if(!isset($_SESSION['gccashier_idnumber']) && empty($_SESSION['gccashier_idnumber']))
		{
			$response['st'] = 1;
		}
		echo json_encode($response);
	}
	elseif ($request=='storelookup') 
	{
		$response['st'] = 0;

		$barcode = $_POST['barcode'];
		$barcode = $link->real_escape_string(trim($barcode));

		$query = $link->query(
			"SELECT 
				gc.barcode_no,
				gc_location.loc_barcode_no,
				transaction_sales.sales_barcode,
				store_verification.vs_barcode,
				gc.gc_ispromo,
				store_received_gc.strec_barcode,
				st.store_name as strec,
				store_received_gc.strec_sold,
				store_received_gc.strec_return,
				transaction_stores.trans_datetime,
				stver.store_name as stvers,
				store_verification.vs_date
			FROM 
				gc 
			LEFT JOIN
				gc_location
			ON
				gc_location.loc_barcode_no = gc.barcode_no
			LEFT JOIN
				transaction_sales
			ON
				transaction_sales.sales_barcode = gc.barcode_no
			LEFT JOIN
				store_verification
			ON
				store_verification.vs_barcode = gc.barcode_no
			LEFT JOIN
				store_received_gc
			ON
				store_received_gc.strec_barcode = gc.barcode_no
			LEFT JOIN
				store_received
			ON
				store_received.srec_id = store_received_gc.strec_recnum
			LEFT JOIN
				transaction_stores
			ON
				transaction_stores.trans_sid = transaction_sales.sales_transaction_id
			LEFT JOIN
				stores as stver
			ON
				stver.store_id = store_verification.vs_store
			LEFT JOIN 
				stores as st
			ON
				st.store_id = store_received.srec_store_id 
			WHERE 
				gc.barcode_no = '$barcode'
			ORDER BY
				transaction_sales.sales_transaction_id
			DESC
			LIMIT 1"
		);

		if($query)
		{
			if($query->num_rows > 0)
			{
				
				$row = $query->fetch_object();
				if(empty($row->gc_ispromo))
				{
					if(!empty($row->strec_barcode))
					{
						$htmldata = "<table class='table table-lookupres'>";
						$response['st'] = 1;
						$htmldata.='<tr><td>Store Allocated</td><td>'.$row->strec.'</td></tr>';
						if(!empty($row->sales_barcode))
						{
							if($row->strec_sold=='*')
								$htmldata.='<tr><td>Date Sold</td><td>'._dateFormat($row->trans_datetime).'</td></tr>';
							if($row->strec_return=='*')
								$htmldata.='<tr><td>Date Refund</td><td>'._dateFormat($row->trans_datetime).'</td></tr>';
							if(!empty($row->vs_barcode))
							{
								$htmldata.='<tr><td>Date Verified</td><td>'._dateFormat($row->vs_date).'</td></tr>';
								$htmldata.='<tr><td>Store Verified</td><td>'.$row->stvers.'</td></tr>';
							}
								
						}
						$htmldata .= '</table>';
						$response['msg'] = $htmldata;
					}
					else
					{
						$response['msg'] = 'GC Barcode #'.$barcode.' not found.';
					}

				}
				else 
				{
					$htmldata = '<div class="lookpromo"> GC Barcode #'.$barcode.' is promo GC.</div>';
					$response['st'] = 1;
					if(!empty($row->vs_barcode))
					{
						$htmldata.= "<table class='table table-lookupres'>";
						$htmldata.='<tr><td>Date Verified</td><td>'._dateFormat($row->vs_date).'</td></tr>';
						$htmldata.='<tr><td>Store Verified</td><td>'.$row->stvers.'</td></tr>';
						$htmldata.= '</table>';
					}

					$response['msg'] = $htmldata;
				}				
			}
			else
			{
				$response['msg'] = 'GC Barcode #'.$barcode.' not found.';
			}
		}
		else 
		{
			$response['msg'] = $link->error;
		}

		echo json_encode($response);		
	}
	elseif ($request=='checkGCSalesGCRefund') 
	{
		
	}
	elseif($request=='shortageoverage')
	{
		$response['st'] = 0;
		$hasdenoms = false;
		// var_dump($_POST);
		foreach ($_POST as $key => $value) 
		{
			if (strpos($key, 'qty_') !== false)
			{
				$denom = $value == '' ? 0 : str_replace(',','',$value);
				$denom_ids = substr($key, 4);
				if($denom!=0)
				{
					$hasdenoms = true;
				}
			} 							
		}

		if(!$hasdenoms)
		{
			$response['msg'] = 'Please input denomination quantity.';
		}
		else 
		{
			$query = $link->query(
				"SELECT
					end_of_shift_pos_details.eos_id,
					end_of_shift_pos_details.eostrans_shtagepveragetotal,
					transaction_stores.trans_yreport
				FROM 
					end_of_shift_pos_details 
				LEFT JOIN 
					transaction_stores
				ON
					transaction_stores.trans_eos = end_of_shift_pos_details.eos_id
				WHERE 
					eostrans_shtagepveragetotal
				IS 
					NULL
				AND
					eoscashier='".$_SESSION['gccashier_id']."'
				AND
					eosstore='".$_SESSION['gccashier_store']."'
				ORDER BY 
					eosdatetime
				DESC
				LIMIT 1
			");

			if(!$query)
			{
				$response['msg'] = $link->error;
			}
			else 
			{
				if(!$query->num_rows > 0 )
				{
					$response['msg'] = 'Please perform Cashier End of Shift first.';
				}
				else 
				{
					$row = $query->fetch_object();

					if($row->trans_yreport!=0)
					{
						$response['msg'] = 'End of day already performed.';
					}
					else 
					{
						$link->autocommit(FALSE);
						$queryError = false;
						$denomError = false;
						$eosID = $row->eos_id;
						$total = 0;
						foreach ($_POST as $key => $value) 
						{
							$sub = 0;
							if (strpos($key, 'qty_') !== false)
							{
								$qty = $value == '' ? 0 : str_replace(',','',$value);
								$denom_ids = substr($key, 4);

								if($qty!=0)
								{									
									$query_getden = $link->query(
										"SELECT 
											pos_ddenom 
										FROM 
											pos_denoms
										WHERE 
											pos_did = '".$denom_ids."'
									");

									if(!$query_getden)
									{
										$queryError = true;
										break;
									}
									else 
									{
										if($query_getden->num_rows == 0 )
										{
											$denomError = true;
											break;
										}
										else 
										{
											$row_d = $query_getden->fetch_object();
											$sub = $qty * $row_d->pos_ddenom;
										}
									}

									$query_ins = $link->query(
										"INSERT INTO 
											pos_shortageoverage
										(
										    stover_eosid, 
										    stover_denomid, 
										    stover_qty
										) 
										VALUES 
										(
										    '".$eosID."',
										    '".$denom_ids."',
										    '".$qty."'
										)
									");

									if(!$query_ins)
									{
										$queryError = true;
										break;
									}
								}
							}
							$total +=$sub; 							
						}

						if($queryError)
						{
							$response['msg'] = $link->error;
						}
						elseif ($denomError) 
						{
							$response['msg'] = 'Denomination not found.';
						}
						else 
						{
							//$response['msg'] = $eosID;
							$query_up = $link->query(
								"UPDATE 
									end_of_shift_pos_details 
								SET 
									eostrans_shtagepveragetotal='$total',
									eostrans_sht_datetime= NOW() 
								WHERE 
									eos_id='$eosID'
							");

							if(!$query_up)
							{
								$response['msg'] = $link->error;
							}	
							else 
							{
								if($link->affected_rows == 0)
								{
									$response['msg'] = 'Something went wrong.(Update problem.)';
								}
								else 
								{
									$link->commit();
									$response['st'] = 1;
									$response['id'] = $eosID;
								}
							}

							//$response['msg'] = $total;
						}
					}
				}


			}
		}

		echo json_encode($response);
	}
	elseif($request=='checkeostrans')
	{
		$response['st'] = 0;
		//var_dump($_SESSION);
		$query = $link->query(
			"SELECT
				eos_id,
				eostrans_shtagepveragetotal
			FROM 
				end_of_shift_pos_details 
			WHERE 
			-- 	eostrans_shtagepveragetotal
			-- IS 
			-- 	NULL
			-- AND
				eoscashier='".$_SESSION['gccashier_id']."'
			AND
				eosstore='".$_SESSION['gccashier_store']."'
			ORDER BY 
				eosdatetime
			DESC
			LIMIT 1
		");

		if(!$query)
		{
			$response['msg'] = $link->error;
		}
		else 
		{
			if($query->num_rows == 0 )
			{
				$response['msg'] = 'Please perform Cashier End of Shift first.';
			}
			else 
			{
				$row = $query->fetch_object();
				if(trim($row->eostrans_shtagepveragetotal)!='')
				{
					$response['msg'] = 'Please perform Cashier End of Shift first.';
				}
				else 
				{
					$response['st'] = 1;
				}
				
			}
		}

		echo json_encode($response);
	}
	elseif ($request=='checksession') 
	{
		$response['st'] = true;
		if(!isset($_SESSION['gccashier_id']))
		{
			$response['st'] = false;
		}
		echo json_encode($response);
	}
}



?>

