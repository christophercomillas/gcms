
<?php
	set_time_limit(0);
	session_start();	
	include 'function.php';

	/** Include path **/
	set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');

	/** PHPExcel_IOFactory */
	include 'PHPExcel/IOFactory.php';


if(isset($_GET['action'])){
	
	$action = $_GET['action'];
	
	if($action=='login'){
		$username = validateData($_POST['username']);
		$password = validateData($_POST['password']);

		$password = md5($password);

		if(!empty($username)&&!empty($password))
		{
			// check is system is active			
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
						$query = $link->query(
							"SELECT 
								*
							FROM 
								users 
							WHERE 
								username='$username' 
							AND 
								password='$password'
						");

						$num_rows = $query->num_rows;
						if($num_rows>0)
						{
						 	$row = $query->fetch_array();

						 	if($row['user_status']=='active')
						 	{
								//Start session upon success login
								if($row['usertype']==7)
								{
									$_SESSION['gc_store'] = $row['store_assigned'];
								}
								$_SESSION['gc_id'] = $row['user_id'];		
								$_SESSION['gc_user']  = $row['username'];
								$_SESSION['gc_fullname'] = $row['firstname'].' '.$row['lastname'];
								$_SESSION['gc_usertype'] = $row['usertype'];
								$_SESSION['gc_uroles'] = $row['user_role'];
						 		$usertype = $row['usertype'];

						 		$query_url = $link->query("SELECT `url`,`title` FROM `access_page` WHERE `access_no`='$usertype'");

						 		$num_rows_link = $query_url->num_rows;

						 		if($num_rows_link>0)
						 		{
						 			$row = $query_url->fetch_array();
						 			$_SESSION['gc_title'] = $row['title'];
						 			$url = $row['url'];
									$response['status'] = 'success';
									$response['url'] = $url;
									echo json_encode($response);
						 		}
								// logs($link,$todays_date,$todays_time);
						 	} 
						 	else 
						 	{
						 		$response['status'] =  'User Status is inactive please contact Administrator.';
						 		echo json_encode($response);
						 	}
						} 
						else 
						{
							$response['status'] =  'Incorrect username or password';
							echo json_encode($response);
						}
					}
					else 
					{
						$response['status'] = 'System currently undergoing maintenance.';
						echo json_encode($response);						
					}
				}
				else 
				{
					$response['status'] = 'System currently undergoing maintenance.';
					echo json_encode($response);
				}
			}
			else 
			{
				$response['status'] = $link->error;
				echo json_encode($response);
			}

		} 
		else 
		{
			$response['status'] =  'Please fill username and password.';
			echo json_encode($response);
		}
	} 
	elseif ($action=='requestBudget') 
	{
		$response['st'] = 0;
		$imageError = 0;
		$imagename = '';
		$type = 0;
		$group = isset($_POST['group']) ? $_POST['group'] : 0;
		// get usertype
		$dept = getField($link,'usertype','users','user_id',$_SESSION['gc_id']);

		$table = 'budget_request';
		$select = 'budget_request.br_id';
		$where = 'users.usertype='.$dept.'
				AND
			budget_request.br_request_status=0';
		$join = 'INNER JOIN
				users
			ON
				users.user_id = budget_request.br_requested_by';
		$limit='';
		$request = getAllData($link,$table,$select,$where,$join,$limit);
		if(!isset($_SESSION['gc_user']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif(count($request)>0)
		{
			$response['msg'] = 'You have pending budget request.';
		}
		else 
		{
			// get user department			
			if($dept==2)
			{
				// treasury department
				$type=1;
			}
			elseif($dept==6)
			{
				//marketing department
				$type=2;
			}
			$haspic = true;
			$date_needed =  _dateFormatoSql($link->real_escape_string($_POST['date_needed']));
			$br_num =  $link->real_escape_string($_POST['br_req_num']);
			$request_budget = $link->real_escape_string($_POST['requestBudget']);
			$request_budget = trim(str_replace( ',', '', $link->real_escape_string($request_budget)));
			$remarks = $_POST['remarks'];

			if($_FILES['pic']['error'][0]==4){
				$haspic = false;
			}

			if($haspic)
			{
				$allowedTypes = array('image/jpeg');

				$fileType = $_FILES['pic']['type'][0];

				if(!in_array($fileType, $allowedTypes))
				{
					$imageError = 1;
				} 
				else 
				{
					$name = $_FILES['pic']['name'][0];
					$expImg = explode(".",$name);
					$prodImg = $expImg[0];
					$imgType = $expImg[1];

					$imagename = $_SESSION['gc_id'].'-'.getTimestamp().'.'.$imgType;
					$imageError = 0;
				}
			}

			if(!empty($request_budget) &&
				!empty($remarks)&&
				!empty($br_num)&&
				!empty($date_needed)){

				if(!$imageError)
				{
					$link->autocommit(FALSE);
					$query = $link->query(
					"INSERT INTO 
						`budget_request`
					(
						`br_request`,
						`br_no`, 
						`br_requested_by`, 
						`br_requested_at`,
						`br_requested_needed`,										 
						`br_file_docno`,
						`br_remarks`, 
						`br_request_status`,
						`br_type`,
						`br_group`
					) 
					VALUES 
					(
						'$request_budget',
						'$br_num',
						'".$_SESSION['gc_id']."',									
						NOW(),
						'$date_needed',
						'$imagename',
						'$remarks',
						'0',
						'$type',
						'$group'
					)
					");

					if($query)
					{						
						if($haspic)
						{
							if(move_uploaded_file($_FILES['pic']['tmp_name'][0], "assets/images/budgetRequestScanCopy/" . $imagename))
							{
								$response['st'] = 1;
								$link->commit();
							}
							else 
							{
								$response['msg'] = 'Error Uploading image.';
							}
						}
						else 
						{
							$response['st'] = 1;
							$link->commit();
						}
					}
					else
					{
						$response['msg'] = $link->error;
					}
				}
				else 
				{
					$response['msg'] = 'Upload file type not allowed.';
				}
			}
			else 
			{
				$response['msg'] = 'Please fill out form.';
			}
		}
		echo json_encode($response);

	} 
	else if($action=='budgetStatusFin') 
	{
		$response['st'] = 0;
		$imageError = 0;
		$imagename = '';
		$haspic = true;
		$approvedby = trim($_POST['approved']);
		$checkedby = trim($_POST['checked']);
		$status = trim($_POST['status']);
		$remarks = trim($_POST['remark']);
		$brid = trim($_POST['budgetid']);
		$budget = trim($_POST['budgetrequested']);
		$btype = trim($_POST['budgettype']);
		$bgroup = trim($_POST['bgroup']);
		$recapproved = trim($_POST['recapproved']);
		$approved = true;

		if($_FILES['pic']['error'][0]==4)
		{
			$haspic = false;
		}

		if(!empty($status))
		{			
			if($status=='1')
			{
				if($bgroup==1)
				{
					if($recapproved!=1)
					{
						$approved = false;
					}
				}
				if($approved)
				{
					if($haspic)
					{
						$allowedTypes = array('image/jpeg');

						$fileType = $_FILES['pic']['type'][0];

						if(!in_array($fileType, $allowedTypes))
						{
							$imageError = 1;
						} 
						else 
						{
							$name = $_FILES['pic']['name'][0];
							$expImg = explode(".",$name);
							$prodImg = $expImg[0];
							$imgType = $expImg[1];

							$imagename = $_SESSION['gc_id'].'-'.getTimestamp().'.'.$imgType;
							$imageError = 0;
						}
					}

					if(!empty($approvedby)&&
						!empty($checkedby)&&
						!empty($remarks)&&
						!empty($brid))
					{
						$link->autocommit(FALSE);
						if(!$imageError)
						{
							$lnum = ledgerNumber($link);

							$query_ledger = $link->query(
								"INSERT INTO 
									`ledger_budget`
								(
								    `bledger_no`, 
								    `bledger_trid`,
								    `bledger_datetime`, 
								    `bledger_type`, 
								    `bdebit_amt`,
								    `bledger_typeid`,
								    `bledger_group`
								) 
								VALUES 
								(
								    '$lnum',
								    '$brid',
								    NOW(),
								    'RFBR',
								    '$budget',
								    '$btype',
								    '$bgroup'
								)							
							");

							if($query_ledger)
							{
								$query_approved = $link->query(
									"INSERT INTO 
										`approved_budget_request`
									(
										`abr_budget_request_id`, 
										`abr_approved_by`,
										`abr_checked_by`,
										`approved_budget_remark`,
										`abr_approved_at`, 
										`abr_file_doc_no`,
										`abr_prepared_by`,
										`abr_ledgerefnum`
									) 
									VALUES 
									(
										'$brid',
										'$approvedby',
										'$checkedby',
										'$remarks',
										NOW(),
										'$imagename',
										'".$_SESSION['gc_id']."', '$lnum'

									)
								");

								if($query_approved)
								{
									if(getField($link,'br_request_status','budget_request','br_id',$brid)==0)
									{
										$query_update_app = $link->query(
											"UPDATE 
												`budget_request` 
											SET 
												`br_request_status`='$status' 
											WHERE 
												`br_id` = '$brid'
											AND
												`br_request_status`='0'
										");

										if($query_update_app)
										{
											if($link->affected_rows >0)
											{
												if($haspic)
												{
													if(move_uploaded_file($_FILES['pic']['tmp_name'][0], "assets/images/approvedBudgetRequest/" . $imagename))
													{
														$response['st'] = 1;
														$response['msg'] = 'Budget Request Successfully Approved!';
														$link->commit();
													}
													else 
													{
														$response['msg'] = 'Error Uploading image.';
													}

												}
												else 
												{
													$response['st'] = 1;
													$response['msg'] = 'Budget Request Successfully Approved!';
													$link->commit();
												}	
											}
											else 
											{
												$response['msg'] = 'Budget already approved/cancelled.';
											}
										}	
										else 
										{
											$response['msg'] = $link->error;
										}
									}
									else 
									{
										$response['msg'] = 'Budget Reqeust already approved/cancelled';
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

							///imageError;
						}
						else 
						{
							$response['msg'] = 'Error Uploading image.';
						}
					}
					else 
					{
						$response['msg'] = 'Please fill all <span class="requiredf">*</span>required fields.';
					}
				}
				else 
				{
					$response['msg'] = 'Budget Request Needs Recommendation Approval from Retail Group '.$bgroup.'.';
				}


			}
			else 
			{
				$link->autocommit(FALSE);

				$query_update = $link->query(
					"UPDATE 
						`budget_request` 
					SET 
						`br_request_status`='$status'
					WHERE 
						`br_id`='$brid'
					AND
						`br_request_status`='0'
				"); 

				if($query_update)
				{
					if($link->affected_rows > 0)
					{
						$query_insert = $link->query(
							"INSERT INTO 
								`cancelled_budget_request`
							(
								`cdreq_req_id`, 
								`cdreq_at`, 
								`cdreq_by`
							) 
							VALUES 
							(
								'$brid',
								NOW(),
								'".$_SESSION['gc_id']."'
							)
						");

						if($query_insert)
						{
							$link->commit();
							$response['st'] = 1;
							$response['msg'] = 'Budget request cancelled.';					
						}
						else 
						{
							$response['msg'] = $link->error;
						}						
					}
					else 
					{
						$response['msg'] = 'Budget already approved/cancelled.';
					}
				}
				else 
				{
					$response['msg'] = $link->error;
				}
			}
		}
		else 
		{
			$response['msg'] ='Please select request status.';
		}
		echo json_encode($response);
	} 
	elseif ($action=='retailgbudgetreq') 
	{
		$response['st'] = 0;
		$imageError = 0;
		$imagename = '';
		$haspic = true;
		$status = trim($_POST['status']);
		$remarks = trim($_POST['remark']);
		$id = trim($_POST['requestid']);

		if($_FILES['docs']['error'][0]==4)
		{
			$haspic = false;
		}


		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif(!empty($status))
		{			
			if($status=='1')
			{				
				if($haspic)
				{
					$image = checkDocuments($_FILES);
					$imageError = $image[0];
					$imagename = $image[1];
				}

				if(empty($remarks)&&
					empty($brid))
				{
					$response['msg'] = 'Please fill all <span class="requiredf">*</span>required fields.';
				}
				elseif($imageError==1)
				{
					$response['msg'] = 'Error Uploading image.';
				}
				else 
				{
					$link->autocommit(FALSE);
					$update_req = $link->query(
						"UPDATE 
							promo_gc_request 
						SET 
							pgcreq_group_status='approved' 
						WHERE 
							pgcreq_status='pending'
						AND
							pgcreq_group_status=''
						AND
							pgcreq_id='".$id."'
					");

					if($update_req)
					{
						if($link->affected_rows > 0)
						{
							$query_ins = $link->query(
								"INSERT INTO 
									approved_request
								(
								    reqap_trid, 
								    reqap_approvedtype, 
								    reqap_remarks, 
								    reqap_doc, 
								    reqap_preparedby, 
								    reqap_date
								) 
								VALUES 
								(
								    '$id',
								    'promo gc preapproved',
								    '$remarks',
								    '$imagename',
								    '".$_SESSION['gc_id']."',
								    NOW()
								)
							");


							if($query_ins)
							{

								//get request total amount
								$query_get_amount = $link->query(
									"SELECT 
										IFNULL(SUM(denomination.denomination * promo_gc_request_items.pgcreqi_qty),0) as sum
									FROM 
										promo_gc_request
									INNER JOIN
										promo_gc_request_items
									ON
										promo_gc_request_items.pgcreqi_trid = promo_gc_request.pgcreq_id
										
									INNER JOIN
										denomination
									ON
										denomination.denom_id = promo_gc_request_items.pgcreqi_denom

									WHERE 
										promo_gc_request.pgcreq_id='".$id."'
								");

								if(!$query_get_amount)
								{
									$response['msg'] = $link->error;
								}
								else 
								{
									$row_tot = $query_get_amount->fetch_object();


									$query_inspr = $link->query(
										"INSERT INTO 
											promo_ledger
										(
										    promled_desc, 
										    promled_debit, 
										    promled_trid
										) 
										VALUES 
										(
										    'promo request approval',
										    '$row_tot->sum',
										    '$id'
										)
									");


									if(!$query_inspr)
									{
										$response['msg'] = $link->error;
									}
									else 
									{
										if($haspic)
										{
											if(move_uploaded_file($_FILES['docs']['tmp_name'][0], "assets/images/budgetRecommendation/". $imagename))
											{
												$response['st'] = 1;
												$response['msg'] = 'Promo GC Successfully Approved for Recommendation.';
												$link->commit();
											}
											else 
											{
												$response['msg'] = 'Error Uploading image.';
											}
										}
										else 
										{
											$response['st'] = 1;
											$response['msg'] = 'Promo GC Successfully Approved for Recommendation.';
											$link->commit();
										}	
									}
								}
							}
							else 
							{
								$response['msg'] = $link->error;
							}
						}
						else 
						{
							$response['msg'] = 'Request already approved/cancelled.';
						}
					}
					else 
					{
						$response['msg'] = $link->error;
					}
				}

			}
			else 
			{
				if($haspic)
				{
					$image = checkDocuments($_FILES);
					$imageError = $image[0];
					$imagename = $image[1];
				}

				if($imageError==1)
				{
					$response['msg'] = 'Error Uploading image.';
				}
				else 
				{
					$link->autocommit(FALSE);
					$update_req = $link->query(
						"UPDATE 
							promo_gc_request 
						SET 
							pgcreq_group_status='cancelled' 
						WHERE 
							pgcreq_status='pending'
						AND
							pgcreq_group_status=''
						AND
							pgcreq_id='".$id."'
					");

					if($update_req)
					{
						if($link->affected_rows > 0)
						{
							$query_ins = $link->query(
								"INSERT INTO 
									cancelled_request
								(
								    reqcan_trid, 
								    reqcan_canceltype, 
								    reqcan_remarks, 
								    reqcan_doc, 
								    reqcan_preparedby, 
								    reqcan_date
								) 
								VALUES 
								(
								    '$id',
								    'promo gc preapproved',
								    '$remarks',
								    '$imagename',
								    '".$_SESSION['gc_id']."',
								    NOW()
								)
							");

							if($query_ins)
							{
									if($haspic)
									{
										if(move_uploaded_file($_FILES['docs']['tmp_name'][0], "assets/images/budgetRecommendation/". $imagename))
										{
											$response['st'] = 1;
											$response['msg'] = 'Promo GC Successfully Approved for Recommendation.';
											$link->commit();
										}
										else 
										{
											$response['msg'] = 'Error Uploading image.';
										}
									}
									else 
									{
										$response['st'] = 1;
										$response['msg'] = 'Promo GC Successfully Approved for Recommendation.';
										$link->commit();
									}	
							}
							else 
							{
								$response['msg'] = $link->error;
							}
						}
						else 
						{
							$response['msg'] = 'Request already approved/cancelled.';
						}
					}
					else 
					{
						$response['msg'] = $link->error;
					}
				}
			}
		}
		else 
		{
			$response['msg'] ='Please select request status.';
		}
		echo json_encode($response);
	}
	elseif($action=='prodEntry')
	{
		$d100 = $_POST['d100'];
		$d200 = $_POST['d200'];
		$d500 = $_POST['d500'];
		$d1000 = $_POST['d1000'];
		$d2000 = $_POST['d2000'];
		$d5000 = $_POST['d5000'];

		if(
			!empty($d100)||
			!empty($d200)||
			!empty($d500)||
			!empty($d1000)||
			!empty($d2000)||
			!empty($d5000)			
		)
		{
			$d100t = $d100*100;
			$d200t = $d200*200;
			$d500t = $d500*500;
			$d1000t = $d1000*1000;
			$d2000t = $d2000*2000;
			$d5000t = $d5000*5000;

			$total = $d100t+$d200t+$d500t+$d1000t+$d2000t+$d5000t;
			$currentBudget = currentBudget($link);

			$u=0;
			if($d100 != NULL){
				$u = $u + 1;
			}
			if($d200 != NULL){
				$u = $u + 1;
			}
			if($d500 != NULL){
				$u = $u + 1;
			}
			if($d1000 != NULL){
				$u = $u + 1;
			}
			if($d2000 != NULL){
				$u = $u + 1;
			}
			if($d5000 != NULL){
				$u = $u + 1;
			}

			if($total<=$currentBudget){
				
				if(!empty($d100)){
					generateGC($d100,'1','1010000000000',$link,$todays_date,$todays_time,$d100t);
				}

				if(!empty($d200)){
					generateGC($d200,'2','1110000000000',$link,$todays_date,$todays_time,$d200t);
				}	

				if(!empty($d500)){
					generateGC($d500,'3','1210000000000',$link,$todays_date,$todays_time,$d500t);
				}

				if(!empty($d1000)){
					generateGC($d1000,'4','1310000000000',$link,$todays_date,$todays_time,$d1000t);
				}

				if(!empty($d2000)){
					generateGC($d2000,'5','1410000000000',$link,$todays_date,$todays_time,$d2000t);
				}

				if(!empty($d5000)){
					generateGC($d5000,'6','1510000000000',$link,$todays_date,$todays_time,$d5000t);
				}


				if($link->query("INSERT INTO `entry_production` VALUES ('GE','','$todays_date','$todays_time','GE','$total','')")){
					if($query = $link->query("SELECT * FROM entry_production ORDER BY ep_no DESC")){
						$row = $query->fetch_array();
						if($row){
							$ep_title	= $row['ep_title'];
							$ep_no	= $row['ep_no'];
							$ep_date= $row['ep_date'];
							$ep_time= $row['ep_time'];
							$ep_type= $row['ep_type'];							

							if($link->query("INSERT INTO `ledger_budget` VALUES ('','$ep_no','NOW()','$ep_type','','$total','')")){
								
								if($query2 = $link->query("(SELECT * FROM `entry_check_request` ORDER BY cr_no DESC limit $u ) ORDER BY cr_no ASC")){

									while($row2	= $query2->fetch_array()){
										$cr_no 	= $row2['cr_no'];
										$cr_title = $row2['cr_title'];
										$cr_date= $row2['cr_date'];
										$cr_type= $row2['cr_type'];
										$cr_time= $row2['cr_time'];
										$cr_amount = $row2['cr_amount'];
										$cr_qty	= $row2['cr_qty'];
										$cr_den	= $row2['cr_denomination'];

										$queryLC = $link->query("SELECT * FROM ledger_check");
										$num_rows = $queryLC->num_rows;
										if($num_rows>0){
											$link->query("INSERT INTO `ledger_check` VALUES ('','$cr_no','$todays_date','$todays_time','$cr_type','$cr_qty','','$cr_den','$cr_amount','".$_SESSION['gc_id']."','')") or die('unable to insert 1');
										} else {
											$link->query("INSERT INTO `ledger_check` VALUES ('1000000000001','$cr_no','$todays_date','$todays_time','$cr_type','$cr_qty','','$cr_den','$cr_amount','".$_SESSION['gc_id']."','')") or die ('unable to insert 2');											
										}
									}
									echo 'success';
								}

							} else {
								$link->error;
							}
						}
					} else {
						echo $link->error;
					}
				} else {
					echo $link->error;
				}		

			} else {
				echo 'Not Enough Budget to Generate GC.';
			}

									
		} else {
			echo 'Please input at least one denomination';
		}

	}elseif($action=='validategc'){
		$gc = $link->real_escape_string($_POST['gcbarcode']);
		$id = $_POST['prod_id'];

		if(!empty($gc)){
				$query = $link->query(
					"SELECT 
						`gc`.`barcode_no`	
					FROM 
						`gc` 
					LEFT JOIN
						`production_request`
					ON
						`gc`.`pe_entry_gc` = `production_request`.`pe_id`
					LEFT JOIN
						`approved_production_request`
					ON
						`gc`.`pe_entry_gc` = `approved_production_request`.`ape_pro_request_id`
						
					WHERE 
						`gc`.`barcode_no` = '$gc'
					AND
						`production_request`.`pe_requisition`='1'
					AND
						`approved_production_request`.`ape_received`='*'
					AND
						`gc`.`pe_entry_gc`='$id'
				");
				$num_rows = $query->num_rows;

				if($num_rows>0){
					$query = $link->query("SELECT * FROM `validation_corp` WHERE `vc_barcode_no`='$gc'");
					$num_rows = $query->num_rows;
					if($num_rows<1){
						$link->query("UPDATE `gc` SET `gc_validated`='*' WHERE `barcode_no` = '$gc'");
						$link->query("INSERT INTO `validation_corp` VALUES ('','$gc','$todays_date','$todays_time','".$_SESSION['gc_id']."','')");
						$denom = getOneJoin($link,$gc,'gc.barcode_no');
						$response['status'] = 'success';
						$response['message'] = '<div class="alert alert-info validate-flash" id="_adjust_alert">
													<h4>GC Successfully Validated.</h4>
													<p class="bar">Barcode Number: </p>
													<p class="br">'.$gc.'</p>
													<p class="den">Denomination: &#8369 '.number_format($denom,2).'</p> 
												</div>';
					} else {

						$response['status'] = '';
						$response['message'] = '<div class="alert alert-danger validate-flash" id="_adjust_alert">GC Barcode Number '.$gc.' already Validated.</div>';
					
					}
				} else {
					$response['status'] ='';
					$response['message'] = '<div class="alert alert-danger validate-flash" id="_adjust_alert">GC Barcode Number '.$gc.' not found.</div>';
				}

		} else {
			$response['status'] ='';
			$response['message'] =  '<div class="alert alert-danger validate-flash" id="_adjust_alert">Please input gc barcode number.</div>';
		}

		echo json_encode($response);
	} elseif ($action=='validategcbarcode') {
		if(isset($_GET['id']))
		{
			$proid = $_GET['id'];
			?>
        <tr>
            <td>&#8369 100.00</td>
            <td><?php echo numRowsForGCValidation($link,1,$proid); ?></td>
        </tr>
        <tr>
            <td>&#8369 200.00</td>
            <td><?php echo numRowsForGCValidation($link,2,$proid); ?></td>
        </tr>
        <tr>
            <td>&#8369 500.00</td>
            <td><?php echo numRowsForGCValidation($link,3,$proid); ?></td>
        </tr>
        <tr>
            <td>&#8369 1000.00</td>
            <td><?php echo numRowsForGCValidation($link,4,$proid); ?></td>
        </tr>
        <tr>
            <td>&#8369 2000.00</td>
            <td><?php echo numRowsForGCValidation($link,5,$proid); ?></td>
        </tr>
        <tr>
            <td>&#8369 5000.00</td>
            <td><?php echo numRowsForGCValidation($link,6,$proid); ?></td>
        </tr>

			<?php 
		}

	} elseif($action=='disgcforvalidation'){
		?>
        <tr>
            <td>&#8369 100.00</td>
            <td><?php echo numRowsForGCValidation($link,1,1); ?></td>
        </tr>
        <tr>
            <td>&#8369 200.00</td>
            <td><?php echo numRowsForGCValidation($link,2,1); ?></td>
        </tr>
        <tr>
            <td>&#8369 500.00</td>
            <td><?php echo numRowsForGCValidation($link,3,1); ?></td>
        </tr>
        <tr>
            <td>&#8369 1000.00</td>
            <td><?php echo numRowsForGCValidation($link,4,1); ?></td>
        </tr>
        <tr>
            <td>&#8369 2000.00</td>
            <td><?php echo numRowsForGCValidation($link,5,1); ?></td>
        </tr>
        <tr>
            <td>&#8369 5000.00</td>
            <td><?php echo numRowsForGCValidation($link,6,1); ?></td>
        </tr>
		<?php 
	} 
	elseif($action=='checkStoreForAllocate')
	{
		$store = $_POST['store'];	
		$gctype = $_POST['gctype'];		
		if(!empty($store)){
			$store_name = getField($link,'store_name','stores','store_id',$store);
			$denom = getAllDenomination($link);
			?>
			<div class="box">
      			<div class="box-header"><h4><i class="fa fa-inbox"></i> <?php echo $store_name; ?> (Allocated GC)</h4></div>
      			<div class="box-content">
					<ul class="list-group bld">
					<?php foreach ($denom as $d): ?>
						<?php $n =  countAllocatedGCByStoreDenomAndGCType($link,$store,$d->denom_id,$gctype); ?>
						<input type="hidden" value="<?php echo $n; ?>" id="x<?php echo $d->denom_id; ?>">        
						<li class="list-group-item"><span class="badge" id="x<?php echo $d->denom_id; ?>"><?php echo $n; ?></span> &#8369 <?php echo number_format($d->denomination,2); ?></li>          
					<?php endforeach; ?>
					</ul>
					<button type="button" class="btn btn-info pull-right" id="view-allocated-gc" onclick="showAllocatedGC(<?php echo $store.','.$gctype ?>)">View Allocated GC</button>
      			</div>
      		</div>
			<?php
		}else {
			echo '';
		}

	} 
	elseif($action=='allocate')
	{
		$store = $_POST['storeallo'];
		$gctype = $_POST['gctype'];

		// $qty_1 = str_replace(',','',$_POST['qty_1']);
		// $qty_2 = str_replace(',','',$_POST['qty_2']);
		// $qty_3 = str_replace(',','',$_POST['qty_3']);
		// $qty_4 = str_replace(',','',$_POST['qty_4']);
		// $qty_5 = str_replace(',','',$_POST['qty_5']);
		// $qty_6 = str_replace(',','',$_POST['qty_6']);
		if(!isset($_SESSION['gc_id']))
		{
			echo 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif(!empty($store)){
			
			if(!empty($gctype)){
				
				$link->autocommit(FALSE);

				foreach ($_POST as $key => $value) 
				{
					if (strpos($key, 'qty_') !== false)
					{
						$denom = $value == '' ? 0 : str_replace(',','',$value);
						$denom_ids = substr($key, 4);
						if(!empty($denom))
						{
							//echo $denom;							
							allocateGC($link,$gctype,$denom,$denom_ids,$store,$todays_date,$todays_time);
						}
					} 							
				}

				$link->commit();
				echo 'success';

			} else {
				echo 'Please select gc type.';
			}

		} else {
			echo 'Please select a store.';
		}
	} elseif($action=='rebuild'){
		 $pass = $_POST['adminpass'];

		$user = $_SESSION['gc_user'];
		$getpass = getField($link,'password','users','username',$user);
		if($getpass == md5($pass)){

			truncateTB($link,'allocation_adjustment');
			truncateTB($link,'allocation_adjustment_items');
			truncateTB($link,'approved_budget_request');
			truncateTB($link,'approved_gcrequest');
			truncateTB($link,'approved_production_request');
			truncateTB($link,'approved_request');
			truncateTB($link,'backup_records');
			truncateTB($link,'budget_adjustment');
			truncateTB($link,'budget_request');
			truncateTB($link,'cancelled_budget_request');
			truncateTB($link,'cancelled_production_request');
			truncateTB($link,'cancelled_request');
			truncateTB($link,'cancelled_store_gcrequest');
			truncateTB($link,'creditcard_payment');
			truncateTB($link,'custodian_srr');
			truncateTB($link,'custodian_srr_items');
			truncateTB($link,'customer_internal_ar');
			truncateTB($link,'documents');
			truncateTB($link,'end_of_shift_pos_details');
			truncateTB($link,'custodian_srr_numgc');
			truncateTB($link,'credit_payment');
			truncateTB($link,'entry_check_request');
			truncateTB($link,'entry_production');
			truncateTB($link,'entry_store');
			truncateTB($link,'entry_store_sales');
			truncateTB($link,'for_denom_set_up');
			truncateTB($link,'gcbarcodegenerate');
			truncateTB($link,'gc');
			truncateTB($link,'gc_adjustment');
			truncateTB($link,'gc_adjustment_items');
			truncateTB($link,'gc_location');
			truncateTB($link,'gc_release');
			truncateTB($link,'gc_return');			
			truncateTB($link,'gc_verification_reprint_details');
			truncateTB($link,'institut_eod');
			truncateTB($link,'institut_payment');
			truncateTB($link,'institut_transactions');
			truncateTB($link,'institut_transactions_items');
			truncateTB($link,'ledger_budget');
			truncateTB($link,'ledger_check');
			truncateTB($link,'ledger_creditcard');			
			truncateTB($link,'ledger_store');
			truncateTB($link,'lost_gc_barcodes');
			truncateTB($link,'lost_gc_details');
			truncateTB($link,'parked_scanned');
			truncateTB($link,'pos_shortageoverage');			
			truncateTB($link,'production_request');
			truncateTB($link,'production_request_items');
			truncateTB($link,'promo');			
			truncateTB($link,'promogc_preapproved');
			truncateTB($link,'promogc_released');
			truncateTB($link,'promo_gc');
			truncateTB($link,'promo_gc_release_to_details');
			truncateTB($link,'promo_gc_release_to_items');
			truncateTB($link,'purchase_orderdetails');			
			truncateTB($link,'promo_gc_request');
			truncateTB($link,'promo_gc_request_items');		
			truncateTB($link,'promo_ledger');						
			truncateTB($link,'requisition_entry');
			truncateTB($link,'sales_yreport');			
			truncateTB($link,'special_external_bank_payment_info');
			truncateTB($link,'special_external_customer');
			truncateTB($link,'special_external_gcrequest');
			truncateTB($link,'special_external_gcrequest_emp_assign');	
			truncateTB($link,'special_external_gcrequest_items');	
			truncateTB($link,'storegc_sales');
			truncateTB($link,'store_eod');
			truncateTB($link,'store_eod_textfile_transactions');			
			truncateTB($link,'store_gcrequest');
			truncateTB($link,'store_received');
			truncateTB($link,'store_received_gc');
			truncateTB($link,'store_request_items');
			truncateTB($link,'store_verification');
			truncateTB($link,'store_void_items');
			truncateTB($link,'temp_promo');
			truncateTB($link,'temp_receivestore');
			truncateTB($link,'temp_refund');
			truncateTB($link,'temp_release');	
			truncateTB($link,'temp_reval');					
			truncateTB($link,'temp_sales');
			truncateTB($link,'temp_sales_discountby');
			truncateTB($link,'temp_sales_docdiscount');
			truncateTB($link,'temp_validation');			
			truncateTB($link,'transaction_docdiscount');
			truncateTB($link,'transaction_endofday');	
			truncateTB($link,'transaction_linediscount');		
			truncateTB($link,'transaction_payment');
			truncateTB($link,'transaction_refund');
			truncateTB($link,'transaction_refund_details');
			truncateTB($link,'transaction_revalidation');
			truncateTB($link,'transaction_sales');
			truncateTB($link,'transaction_stores');
			truncateTB($link,'transfer_request');
			truncateTB($link,'transfer_request_items');
			truncateTB($link,'transfer_request_served');
			truncateTB($link,'transfer_request_served_items');
			truncateTB($link,'userlogs');
			truncateTB($link,'validation_corp');
			$path = 'assets/images/';
			$folders = array(
				'approvedBudgetRequest',
				'approvedGCRequest',
				'approvedProductionRequest',
				'budgetRequestScanCopy',
				'gcRequestStore',
				'productionRequestFile',
				'promoReleasedFile',
				'budgetRecommendation',
				'promoRequestFile',
				'externalDocs',
				'transferDocs'
				);
			foreach ($folders as $f) {
				$file = $path.$f;
				if(checkFolder($file))
				{
					deleteImages($file);
				}
			}

			$path2 = $dir.'\\gc_textfiles\\';
			$texfiles = array(
				'requisition',
				'verification',
				'archives'
			);

			foreach ($texfiles as $t) {
				$file1 = $path2.$t;
				if(checkFolder($file1))
				{
					deleteTexfiles($file1);
				}
				else 
				{
					echo $file1;
					exit();					
				}
			}

			$report_path = 'reports/';
			$reports = array(
				'custodian_receiving',
				'pos',
				'marketing',
				'treasury_releasing',
				'treasury',
				'externalReport',
				'treasury_releasingpromo'
			);

			foreach ($reports as $r) {
				$file2 = $report_path.$r;
				if(checkFolder($file2))
				{
					deleteReports($file2);
				}
				else 
				{
					echo $file2;
					exit();					
				}
			}
			echo 'success';
		} 
		else 
		{
			echo 'Password is incorrect.';
		}
	} 
	elseif($action=="productionRequest")
	{
		$response['st'] = 0;
		$imageError = 0;
		$imagename = '';
		$hasdenom=false;
		$haspic = true;
		$dateneed = _dateFormatoSql($_POST['date_needed']);
		$penum = $_POST['penum'];
		$remarks = $_POST['remarks'];
		$pe_group = isset($_POST['group']) ? $_POST['group'] : 0; 
		$dept = getField($link,'usertype','users','user_id',$_SESSION['gc_id']);
		$type=0;
		if($dept==2)
		{
			$type=1;
		}
		elseif($dept==6)
		{
			$type=2;
		}

	    if($_FILES['pic']['error'][0]==4){
	      $haspic = false;
	    }

		if($haspic)
		{
			$allowedTypes = array('image/jpeg');

			$fileType = $_FILES['pic']['type'][0];

			if(!in_array($fileType, $allowedTypes))
			{
				$imageError = 1;
			} 
			else 
			{
				$name = $_FILES['pic']['name'][0];
				$expImg = explode(".",$name);
				$prodImg = $expImg[0];
				$imgType = $expImg[1];

				$imagename = $_SESSION['gc_id'].'-'.getTimestamp().'.'.$imgType;
				$imageError = 0;
			}
		}

		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
	    elseif($dateneed &&
	      $penum &&
	      $remarks)
	    {
  
	        if(!$imageError)
	        {
				$link->autocommit(FALSE);
				$query_inspr = $link->query(
					"INSERT INTO 
						production_request
					(
						pe_num, 
						pe_requested_by, 
						pe_date_request, 
						pe_date_needed, 
						pe_file_docno, 
						pe_remarks,
						pe_type,
						pe_group
					) 
					VALUES 
					(
						'$penum',
						'".$_SESSION['gc_id']."',
						NOW(),
						'$dateneed',
						'$imagename',
						'$remarks',
						'$type',
						'$pe_group'
					)"
				);

				if($query_inspr)
				{
					$errorDenom = false;
					$last_insert = $link->insert_id;
					foreach ($_POST as $key => $value) {
						if (strpos($key, 'denoms') !== false)
						{
							$denom = $value == '' ? 0 : str_replace(',','',$value);
							$denom_ids = substr($key, 6);
							if(!empty($denom))
							{
								if(!insertDenomRequest(
									$link,
									'production_request_items',
									'pe_items_id',
									'pe_items_denomination',
									'pe_items_quantity',
									'pe_items_request_id',
									$denom_ids,
									$denom,
									$last_insert,
									'pe_items_remain'
								))
								{
									$errorDenom = true;
									break;
								}		 						
							}

						} 
						//echo 'Key =>'.substr($key, 6).' Value =>'.$value;
					}

					if(!$errorDenom)
					{
						if($haspic)
						{
							if(move_uploaded_file($_FILES['pic']['tmp_name'][0], "assets/images/productionRequestFile/" . $imagename))
							{
								$response['st'] = 1;
								$link->commit();
							}
							else 
							{
								$response['msg'] = 'Error Uploading image.';
							}
						}
						else 
						{
							$response['st'] = 1;
							$link->commit();
						}
					}
					else 
					{
						$response['msg'] = 'Error Inserting denom request.';
					}
				}
				else 
				{
					$response['msg'] = $link->error;
				}
	        }
	        else 
	        {
	        	$response['msg'] = 'Upload file type not allowed.';
	        }
 	
	    }
	    else 
	    {
	      $response['msg'] = 'Please fill all required fields.';
	    }

		echo json_encode($response);
	}
	elseif($action=="approveProd")
	{

		//last approve
		$remark = validateData($_POST['remark']);
		$pass = $_POST['password'];

		if(!empty($remark)&&
			!empty($pass)){

				$pass = md5(validateData($pass));
				$username = $_SESSION['gc_user'];
				if(checkUserAndPass($link,$username,$pass)){

					$id = getField($link,'pe_id','production_request','pe_status','0');
					$link->autocommit(false);
					$query = $link->query(
								"INSERT INTO 
									`approved_production_request`
								(
									`ape_id`, 
									`ape_pro_request_id`, 
									`ape_approved_by`, 
									`ape_remarks`, 
									`ape_approved_at`
								) 
								VALUES (
									'',
									'$id',
									'".$_SESSION['gc_fullname']."',
									'$remark',
									NOW()
								)");
					if($query){
						$query_update = $link->query(
								"UPDATE 
									`production_request` 
								SET 
									`pe_status`='1' 
								WHERE 
									`pe_id`='$id'"
								);

						if($query_update){
							$total = totalGCAmount($link,$id);
							
							if($link->query("INSERT INTO `entry_production` VALUES ('GE','','$todays_date','$todays_time','GE','$total','')")){

								$query = $link->query("SELECT `ep_no`,`ep_type` FROM `entry_production` ORDER by `ep_no` DESC");

								$row = $query->fetch_assoc();
								if($row){
									$ep_no	= $row['ep_no'];
									$ep_type= $row['ep_type'];
								}								

									$query_ledger = $link->query(
											"INSERT INTO 
												`ledger_budget` 
											VALUES 
												(
													'',
													'$ep_no',
													'NOW()',
													'$ep_type',
													'',
													'$total',
													''
												)");

									if($query_ledger){

										$link->commit();
										echo 'success';
									}


							} else {
								echo $link->error;
							}

						} else {
							$link->error;
						}
					} else {
						$link->error;
					}
				} else {
					echo 'Password is incorrect.';
				}

		} else {
			echo 'Please fill-up form.';
		}

	} elseif($action=='generatebarcode'){
		$id = $_POST['peid'];
		$link->autocommit(false);
		$query = $link->query(
			"SELECT 
				production_request_items.pe_items_denomination,
				production_request_items.pe_items_quantity,
				denomination.denom_barcode_start
			FROM 
				production_request_items
			INNER JOIN
				denomination
			ON
				denomination.denom_id = production_request_items.pe_items_denomination
			WHERE 
				pe_items_request_id='$id';
		");

		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Login to Continue.';
		}
		elseif($query){

			$n = $query->num_rows;

			if($n>0)
			{
				while($row = $query->fetch_object())
				{
					$denom = $row->pe_items_denomination;
					$qty = $row->pe_items_quantity;
					$prefix = $row->denom_barcode_start;

					//$dtotal = $row['pe_items_denomination'] * $qty;

					generateGC($qty,$denom,$prefix,$link,$todays_date,$todays_time,0,$id);
								
				}

				if(updateOne($link,'production_request','pe_generate_code','1','pe_id',$id))
				{
					$query_insgen = $link->query(
						"INSERT INTO 
							`gcbarcodegenerate`
						(
						    `gbcg_pro_id`,
						    `gbcg_by`, 
						    `gbcg_at`
						) 
							VALUES 
						(
						    '$id',
						    '".$_SESSION['gc_id']."',
						    NOW()
						)
					");

					if($query_insgen)
					{
						$link->commit();
						echo 'success';						
					}
					else 
					{
						$link->error;
					}
				} else {
					echo 'Something went wrong';
				}

			} else {
				echo 'Something went wrong';
			}				

		} else {
			echo $link->error;
		}
	}
	elseif($action=='productionStat')
	{
		$response['st'] = 0;
		$imageError = 0;
		$imagename = '';
		$haspic = true;
		$approvedby = trim($_POST['approved']);
		$checkedby = trim($_POST['checked']);
		$status = trim($_POST['status']);
		$remarks = trim($_POST['remark']);
		$prid = trim($_POST['prodId']);
		$protype = trim($_POST['protype']);
		$pegroup = trim($_POST['progroup']);

		if($_FILES['pic']['error'][0]==4)
		{
			$haspic = false;
		}

		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif(!empty($status))
		{
			if($status=='1')
			{
				if($haspic)
				{
					$allowedTypes = array('image/jpeg');

					$fileType = $_FILES['pic']['type'][0];

					if(!in_array($fileType, $allowedTypes))
					{
						$imageError = 1;
					} 
					else 
					{
						$name = $_FILES['pic']['name'][0];
						$expImg = explode(".",$name);
						$prodImg = $expImg[0];
						$imgType = $expImg[1];

						$imagename = $_SESSION['gc_id'].'-'.getTimestamp().'.'.$imgType;
						$imageError = 0;
					}
				}

				if(!empty($approvedby)&&
					!empty($checkedby)&&
					!empty($remarks)&&
					!empty($prid))
				{
					$link->autocommit(FALSE);
					if(!$imageError)
					{
						$total = totalGCAmount($link,$prid);
						$lnum = ledgerNumber($link);								

						$query_ledger = $link->query(
							"INSERT INTO 
								ledger_budget
							(
								bledger_no, 
								bledger_trid,
								bledger_datetime, 
								bledger_type, 
								bcredit_amt,
								bledger_typeid,
								bledger_group 
							) 
							VALUES 
							(
								'$lnum',
								'$prid',
								NOW(),
								'RFGCP',
								'$total',	
								'$protype',
								'$pegroup'
							)"
						);

						if($query_ledger)
						{
							$query_ins = $link->query(
								"INSERT INTO 
									`approved_production_request`
								(
									`ape_pro_request_id`,														
									`ape_approved_by`, 
									`ape_checked_by`,
									`ape_remarks`, 
									`ape_approved_at`,
									`ape_file_doc_no`,
									`ape_preparedby`,
									`ape_ledgernum`
								) 
								VALUES (
									'$prid',
									'$approvedby',														
									'$checkedby',
									'$remarks',
									NOW(),
									'$imagename',
									'".$_SESSION['gc_id']."',
									'$lnum'
								)"
							);

							if($query_ins)
							{
								if(getField($link,'pe_status','production_request','pe_id',$prid)==0)
								{
									$query_update = $link->query(
										"UPDATE 
											`production_request` 
										SET 
											`pe_status`='$status' 
										WHERE 
											`pe_id`='$prid'
										AND
											`pe_status`='0'
									");

									if($query_update)
									{
										if($haspic)
										{
											if(move_uploaded_file($_FILES['pic']['tmp_name'][0], "assets/images/approvedProductionRequest/" . $imagename))
											{
												$response['st'] = 1;
												$response['msg'] = 'Production Request Successfully Approved!';
												$link->commit();
											}
											else 
											{
												$response['msg'] = 'Error Uploading image.';
											}
										}
										else 
										{
											$response['st'] = 1;
											$response['msg'] = 'Production Request Successfully Approved!';
											$link->commit();
										}	
									}
									else 
									{
										$response['msg'] = $link->error;
									}
								}
								else 
								{
									$response['msg'] = 'Production request already approved/cancelled.';
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
						$response['msg'] = 'Error Uploading image.';
					}
				}
				else 
				{
					$response['msg'] = 'Please fill out all <span class="requiredf">*</span>required fields.';
				}
			}
			elseif($status=='2') 
			{
				if(getField($link,'pe_status','production_request','pe_id',$prid)==0)
				{
					$query_update = $link->query(
						"UPDATE 
							`production_request` 
						SET 
							`pe_status`='$status' 
						WHERE 
							`pe_id`='$prid'
						AND
							`pe_status`='0'
					");

					if($query_update)
					{
						$query_cancel = $link->query(
							"INSERT INTO 
							`cancelled_production_request`
						(
						    `cpr_pro_id`, 
						    `cpr_at`, 
						    `cpr_by`
						) 
						VALUES 
						(
						    '$prid',
						    NOW(),
						    '".$_SESSION['gc_id']."'
						)
						");

						if($query_cancel)
						{
							$response['st'] = 1;
							$response['msg'] = 'Production request cancelled.';
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
					$response['msg'] = 'Production request already approved/cancelled.';
				}
			}
		}
		else 
		{
			$response['msg'] ='Please select request status.';
		}

		echo json_encode($response);
	} 
	elseif ($action=='gcrequest') 
	{
		$response['st'] = 0;
		$imageError = 0;
		$imagename = '';
		$hasdenom=false;
		$haspic = true;
		$dateneed = _dateFormatoSql($_POST['date_needed']);
		$penum = $_POST['penum'];
		// get requestnumber
		$store_id = $_SESSION['gc_store'];
		$penum = getFieldOrderLimit($link,'sgc_num','store_gcrequest','sgc_store',$store_id,'sgc_id','DESC',1);
		$penum+=1;
		$remarks = $_POST['remarks'];

        foreach ($_POST as $key => $value) {
            if (strpos($key, 'denoms') !== false)
            {
                $denom = $value == '' ? 0 : str_replace(',','',$value);
                //$denom_ids = substr($key, 6);

                if($denom > 0)
                {
                	$hasdenom = true;
                }
            }
        }

		if($_FILES['pic']['error'][0]==4)
		{
			$haspic = false;
		}

		if($haspic)
		{

			$allowedTypes = array('image/jpeg');

			$fileType = $_FILES['pic']['type'][0];

			if(!in_array($fileType, $allowedTypes))
			{
				$imageError = 1;
			} 
			else 
			{
				$name = $_FILES['pic']['name'][0];
				$expImg = explode(".",$name);
				$prodImg = $expImg[0];
				$imgType = $expImg[1];

				$imagename = $_SESSION['gc_id'].'-'.getTimestamp().'.'.$imgType;
				$imageError = 0;
			}
		}

		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif(!empty($penum)&&
			!empty($dateneed)&&
			!empty($remarks))
		{
				if(!$imageError)
				{
					$link->autocommit(FALSE);
					$query = $link->query(
					"INSERT INTO 
						`store_gcrequest`
						(
							`sgc_num`, 
							`sgc_requested_by`, 
							`sgc_date_request`, 
							`sgc_date_needed`, 
							`sgc_file_docno`, 
							`sgc_remarks`, 
							`sgc_status`, 
							`sgc_store`,
							`sgc_type`
						) 
						VALUES 
						(
							'$penum',
							'".$_SESSION['gc_id']."',
							NOW(),
							'$dateneed',
							'$imagename',
							'$remarks',
							'0',
							'$store_id',
							'regular'											
					)");

					$last_insert = $link->insert_id;

					if($query)
					{

	                    foreach ($_POST as $key => $value) {
	                        if (strpos($key, 'denoms') !== false)
	                        {
	                            $denom_qty = $value == '' ? 0 : str_replace(',','',$value);
	                            $denom_ids = substr($key, 6);
	                            if(!empty($denom_qty))
	                            {
                              		insertDenomRequest($link,'store_request_items','sri_id','sri_items_denomination', 'sri_items_quantity', 'sri_items_requestid',$denom_ids,$denom_qty,$last_insert,'sri_items_remain',$denom_qty);
	                            }
	                            else 
	                            {
	                            	insertDenomRequest($link,'store_request_items','sri_id','sri_items_denomination', '0', 'sri_items_requestid',$denom_ids,$denom_qty,$last_insert,'sri_items_remain','0');
	                            }
	                        } 
	                    }

						// if(!empty($d100))
						// {
						// 	insertDenomRequest($link,'store_request_items','sri_id','sri_items_denomination', 'sri_items_quantity', 'sri_items_requestid','1',$d100,$last_insert,'sri_items_remain',$d100);
						// } else {
						// 	insertDenomRequest($link,'store_request_items','sri_id','sri_items_denomination', '0', 'sri_items_requestid','1',$d100,$last_insert,'sri_items_remain','0');
						// }

						// if(!empty($d200)){
						// 	insertDenomRequest($link,'store_request_items','sri_id','sri_items_denomination', 'sri_items_quantity', 'sri_items_requestid','2',$d200,$last_insert,'sri_items_remain',$d200);
						// } else {
						// 	insertDenomRequest($link,'store_request_items','sri_id','sri_items_denomination', '0', 'sri_items_requestid','2',$d200,$last_insert,'sri_items_remain','0');
						// }

						// if(!empty($d500)){
						// 	insertDenomRequest($link,'store_request_items','sri_id','sri_items_denomination', 'sri_items_quantity', 'sri_items_requestid','3',$d500,$last_insert,'sri_items_remain',$d500);
						// } else {
						// 	insertDenomRequest($link,'store_request_items','sri_id','sri_items_denomination', '0', 'sri_items_requestid','3',$d500,$last_insert,'sri_items_remain','0');
						// }

						// if(!empty($d1000)){	
						// 	insertDenomRequest($link,'store_request_items','sri_id','sri_items_denomination', 'sri_items_quantity', 'sri_items_requestid','4',$d1000,$last_insert,'sri_items_remain',$d1000);
						// } else {
						// 	insertDenomRequest($link,'store_request_items','sri_id','sri_items_denomination', '0', 'sri_items_requestid','4',$d1000,$last_insert,'sri_items_remain','0');
						// }

						// if(!empty($d2000)){
						// 	insertDenomRequest($link,'store_request_items','sri_id','sri_items_denomination', 'sri_items_quantity', 'sri_items_requestid','5',$d2000,$last_insert,'sri_items_remain',$d2000);
						// } else {
						// 	insertDenomRequest($link,'store_request_items','sri_id','sri_items_denomination', '0', 'sri_items_requestid','5',$d2000,$last_insert,'sri_items_remain','0');
						// }

						// if(!empty($d5000)){
						// 	insertDenomRequest($link,'store_request_items','sri_id','sri_items_denomination', 'sri_items_quantity', 'sri_items_requestid','6',$d5000,$last_insert,'sri_items_remain',$d5000);
						// } else {
						// 	insertDenomRequest($link,'store_request_items','sri_id','sri_items_denomination', '0', 'sri_items_requestid','5',$d2000,$last_insert,'sri_items_remain','0');
						// }

						if($haspic)
						{
							if(move_uploaded_file($_FILES['pic']['tmp_name'][0], "assets/images/gcRequestStore/" . $imagename))
							{
								$response['st'] = 1;
								$link->commit();
							}
							else 
							{
								$response['msg'] = 'Error Uploading image.';
							}
						}
						else 
						{
							$response['st'] = 1;
							$link->commit();
						}	
					}
					else 
					{
						$response['msg'] = $link->error;
					}
				}
				else 
				{
					$response['msg'] = 'Upload file type not allowed.';
				}
		}
		else 
		{
			$response['msg'] = 'Please fill all required fields.';
		}

		echo json_encode($response);
	} 
	elseif ($action=='updategcrequest')
	{
		$reqby = NULL;
		$hasdenom = false;
		$response['st'] = 0;
		$imageError = 0;
		$oldpic = true;
		$imagename = '';	
		$reqID = $_POST['reqID'];
		$hasdenom=false;
		$haspic = true;
		$dateneed = _dateFormatoSql($_POST['date_needed']);		
		$remarks = $_POST['remarks'];
		$imagenameold = $_POST['imgname'];
		$imagename = $_POST['imgname'];

		if(isset($_POST['reqby']))
		{
			$reqby = $_POST['reqby'];
		}

		$requesttype = $_POST['requesttype'];

		if($requesttype=='regularspecial')
		{
			foreach ($_POST as $key => $value) 
			{
				if (strpos($key, 'denom') !== false) 
				{
					$qty = $value == '' ? 0 : str_replace(',','',$value);
					if(!empty($qty))
					{
						$hasdenom = true;
					}
				}
			}
		}
		else 
		{
			foreach ($_POST['ninternalcusd'] as $key => $value  ) 
			{
				$denoms = str_replace(',', '', $value);
				$qty = str_replace(',', '',$_POST['ninternalcusq'][$key]);
				if(empty($denoms) || empty($qty))
				{
					break;
				}
				$hasdenom = true;				
			}
		}


		if(trim($imagename)=='')
		{
			$oldpic = false;
		}

		if($_FILES['pic']['error'][0]==4)
		{
			$haspic = false;
		}

		if($haspic)
		{
			$allowedTypes = array('image/jpeg');

			$fileType = $_FILES['pic']['type'][0];

			if(!in_array($fileType, $allowedTypes))
			{
				$imageError = 1;
			} 
			else 
			{
				$name = $_FILES['pic']['name'][0];
				$expImg = explode(".",$name);
				$prodImg = $expImg[0];
				$imgType = $expImg[1];

				$imagename = $_SESSION['gc_id'].'-'.getTimestamp().'.'.$imgType;
				$imageError = 0;
			}
		}

		if(!empty($dateneed)&&
			!empty($remarks)&&
			!empty($reqID))
		{
			if($hasdenom)
			{
				if(!$imageError)
				{
					$link->autocommit(FALSE);
					$query = $link->query(
						"UPDATE 
							store_gcrequest 
						SET 
							sgc_requested_by='".$_SESSION['gc_id']."',							
							sgc_date_needed='$dateneed',							
							sgc_remarks='$remarks',
							sgc_file_docno='$imagename'							
						WHERE 
							sgc_id='$reqID'
						AND	
							sgc_status = 0 
						AND
							sgc_cancel = ''
					");

					if($query)
					{
						if($requesttype=='regularspecial')
						{
							foreach ($_POST as $key => $value) 
							{
								if (strpos($key, 'denom') !== false) 
								{
									$qty = $value == '' ? 0 : str_replace(',','',$value);
									$denom_ids = substr($key, 5);
									if(!empty($qty))
									{
										if(checkbeforeUpdateGCrequest($link,$denom_ids,$reqID))
										{
											updateGCDenomReqItems($link,$qty,$denom_ids,$reqID);								
										} 
										else 
										{								
											insertDenomRequest($link,'store_request_items','sri_id', 'sri_items_denomination', 'sri_items_quantity', 'sri_items_requestid',$denom_ids,$qty,$reqID,'sri_items_remain',$qty);																	
										}	
									} 
									else 
									{
										if(checkbeforeUpdateGCrequest($link,$denom_ids,$reqID))
										{
											deleteGCStoreRequest($link,$reqID,$denom_ids);
										}
									}
								}
							}
						}
						else 
						{
							// update company / person req
							$query_compreq = $link->query(
								"UPDATE 
									special_internal_customer 
								SET 
									spcus_customername='$reqby' 
								WHERE 
									spcus_id='$reqID'
							");

							if($query_compreq)
							{
								// get all request 
								$select = 'store_request_items.sri_id,
									denomination.denomination,
									for_denom_set_up.fds_denom,
									store_request_items.sri_items_denomination';
								$where = 'sri_items_requestid ='.$reqID;
								$join = 'LEFT JOIN
										denomination
									ON
										denomination.denom_id = store_request_items.sri_items_denomination
									LEFT JOIN
										for_denom_set_up
									ON
										for_denom_set_up.fds_denom_reqid = store_request_items.sri_id';

								$items_arr = getAllData($link,'store_request_items',$select,$where,$join,$limit = NULL);
								$denoms_arr = [];
								foreach ($items_arr as $key_d) 
								{
									if($key_d->sri_items_denomination!=0)
									{
										$denoms_arr[] = array('did'	=>	$key_d->sri_id, 'ddenom' => $key_d->denomination);
									}	
									else 
									{
										$denoms_arr[] = array('did'	=>	$key_d->sri_id, 'ddenom' => $key_d->fds_denom);
									}
								}							

								$denoms_new = [];
								foreach ($_POST['ninternalcusd'] as $key => $value  ) 
								{
									$d_exist = false;
									$denoms = str_replace(',', '', $value);
									$qty = str_replace(',', '',$_POST['ninternalcusq'][$key]);
									$denom_id = 0;

									//check if denom exist
									if(checkIfExist($link,'denom_id','denomination','denomination',$denoms))
									{
										// get denom
										$denom_id = getField($link,'denom_id','denomination','denomination',$denoms);
										
										//check if exist in request item
										if(checkbeforeUpdateGCrequest($link,$denom_id,$reqID))
										{
											updateGCDenomReqItems($link,$qty,$denom_id,$reqID);																	
										} 
										else 
										{								
											insertDenomRequest($link,'store_request_items','sri_id', 'sri_items_denomination', 'sri_items_quantity', 'sri_items_requestid',$denom_id,$qty,$reqID,'sri_items_remain',$qty);																												
										}
									}
									else 
									{
										$r_id = checkbeforeUpdateGCrequestSetupDenom($link,$denoms,$reqID);
										if($r_id->cnt> 0)
										{
											updateGCDenomReqItemsByItemID($link,$qty,$r_id->sri_id,$reqID);	
										}
										else 
										{
											insertDenomRequest($link,'store_request_items','sri_id','sri_items_denomination', 'sri_items_quantity', 'sri_items_requestid','0',$qty,$reqID,'sri_items_remain',$qty);
											$last_insert_denom = $link->insert_id;
											$query_setup = $link->query(
												"INSERT INTO 
													for_denom_set_up
												(
												    fds_denom_reqid,
												    fds_denom,	
												    fds_status
												) 
												VALUES 
												(
												    '$last_insert_denom',
												    '$denoms',
												    'pending'
												)
											");
											if(!$query_setup)
											{
												$hasError = true;
												break;
											}
										}
									}

									$denoms_new[] = $denoms;
								}

								//delete 
								foreach ($denoms_arr as $d => $value) 
								{
									if(!in_array($value['ddenom'], $denoms_new)) 
									{
										deleteGCStoreRequestByItemID($link,$reqID,$value['did']);
										$link->query("
											DELETE 
											FROM 
												for_denom_set_up 
											WHERE 
												fds_denom_reqid='".$value['did']."'
										");
									}
								}
							}
							else 
							{
								$response['msg'] = $link->error;
								echo json_encode($response);
								exit();
							}
						}

						//exit();

						if($haspic)
						{
							if(move_uploaded_file($_FILES['pic']['tmp_name'][0], "assets/images/gcRequestStore/".$imagename))
							{
								if($oldpic)
								{
									unlink('assets/images/gcRequestStore/'.$imagenameold);
								}
								$response['st'] = 1;
								$link->commit();
							}
							else 
							{
								$response['msg'] = 'Error Uploading image.';
							}
						}
						else 
						{
							$response['st'] = 1;
							$link->commit();
						}							

					}
					else 
					{
						$response['msg'] = $link->error;
					}
				}
				else 
				{
					$response['msg'] = 'Upload file type not allowed';
				}
			}	
			else 
			{
				$response['msg'] = 'Please input at least one quantity field.';
			}	
		}
		else 
		{
			$response['msg'] = 'Please fill all <span class="requiredf">*</span>required fields.';
		}

		echo json_encode($response);

	}
	elseif ($action=='gcRequestStat')
	{
		$store_id = $_POST['store_id'];
		$reqid = $_POST['reqid'];
		$dataproc = $_POST['proc'];
		$approvedby = $_POST['approved'];
		$checkedby = $_POST['checked'];			
		$haspic = true;
		$status = $_POST['status'];						
		$remarks = $_POST['remark'];
		if($_FILES['pic']['error'][0]==4){
			$haspic = false;
		}

		if(!empty($reqid)){
			if($status=='1'){
				if(!empty($approvedby)){
					if(!empty($remarks)){
						if($haspic){

							$query = $link->query("SELECT * FROM `store_request_items` WHERE `sri_items_requestid`='$reqid'");

							if($query){

								$checkalloc = true;

								$n = $query->num_rows;
								
								// if($n>0){
								// 	while($row = $query->fetch_object()){
								// 		$qty =  $row->sri_items_quantity;
								// 		$den = $row->sri_items_denomination;										
								// 		$alloc = getValidationNumRowsByStore($link,$store_id,$den); 
								// 		if($qty>$alloc){
								// 			$checkalloc=false;											
								// 		}										
								// 	}
								// 	if($checkalloc){

								// 		$allowedTypes = array('image/jpeg');

								// 		$fileType = $_FILES['pic']['type'][0];
								// 		if(!in_array($fileType, $allowedTypes)){
								// 			echo "Upload file type not allowed.";			
								// 		} else {
								// 			$name = $_FILES['pic']['name'][0];
								// 			$expImg = explode(".",$name);
								// 			$prodImg = $expImg[0];
								// 			$imgType = $expImg[1];

								// 			if(checkIfTableNotEmpty($link,'approved_gcrequest')){
								// 				$name = getOne($link,'sgc_file_docno','store_gcrequest','sgc_id');
								// 					$name++;
								// 					$name = sprintf("%08d", $name);									
								// 			} else {
								// 				$name = '00000001';									
								// 			}		

								// 				$link->autocommit(false);

								// 				$query = $link->query("SELECT * FROM `store_request_items` WHERE `sri_items_requestid`='$reqid'");
								// 				while($row = $query->fetch_object()){
								// 					$qty =  $row->sri_items_quantity;
								// 					$den = $row->sri_items_denomination;
								// 					$request_id = $row->sri_items_requestid;
								// 					if($qty>0){
								// 						$alloc = getValidationNumRowsByStore($link,$reqid,$den);
								// 						releaseGC($link,$todays_date,$todays_time,$qty,$store_id,$den,$request_id); 																	
														
								// 					}											
								// 				}

								// 				$query_gc_req = $link->query(
								// 					"INSERT INTO 
								// 						`approved_gcrequest`
								// 					(
								// 						`agcr_id`, 
								// 						`agcr_request_id`, 
								// 						`agcr_approvedby`, 
								// 						`agcr_checkedby`, 
								// 						`agcr_remarks`, 
								// 						`agcr_approved_at`, 
								// 						`agcr_file_docno`,
								// 						`agcr_preparedby`
								// 					) 
								// 					VALUES 
								// 					(
								// 						'',
								// 						'$reqid',
								// 						'$approvedby',
								// 						'$checkedby',
								// 						'$remarks',
								// 						NOW(),
								// 						'$name',
								// 						'".$_SESSION['gc_id']."'

								// 					)");

								// 				if($query_gc_req){

								// 					if($link->query(
								// 						"UPDATE 
								// 							`store_gcrequest` 
								// 						SET 
								// 							`sgc_status`='1' 
								// 						WHERE
								// 							`sgc_id`='$reqid';
								// 						")){

								// 						$name = $name.'.'.$imgType;
								// 						if(move_uploaded_file($_FILES['pic']['tmp_name'][0], "assets/images/approvedGCRequest/".$name)){
								// 							$link->commit();
								// 							echo 'success';
								// 						} else {
								// 							echo 'Something went wrong try again later.';
								// 						}
								// 					}

								// 				} else {
								// 					echo $link->error;
								// 				}
								// 		}


								// 	}else {
								// 		echo 'Allocation is not enough';
								// 	}
								// }

							} else {
								echo $link->error; 
							}
						} else {
							echo 'Please choose document.';
						}
					} else {
						echo 'Please input Remark field';
					}
				} else {
					echo 'Please input Approved input field.';
				}
			} else {
				echo 'Reqeust Cancelled';
			}
		} else {
			echo 'Please select request status.';
		}
		
	} 
	elseif ($action=='requisition') 
	{
		$response['st'] = 0;
		$status = $_POST['status'];
		$id = trim($_POST['id']);

		if(getFADIPConnectionStatus($link))
		{
			$fadrequis = getField($link,'app_settingvalue','app_settings','app_tablename','fad_server_ip_requis_new');
		}
		else 
		{
			$fadrequis = $dir.getField($link,'app_settingvalue','app_settings','app_tablename','localhost_requisition_new');
		}

		if(file_exists($fadrequis))
		{
			$folder = $fadrequis;
			$foldersaved = 'FAD Folder';
		}
		else 
		{
			$folder = $dir.getOne($link,'localhost_requisition_new','system_cred','cred_id');
			$foldersaved = 'GC Folder';
		}


		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif($status=='1')
		{
			$erquestno = trim($_POST['erquestno']);
			// $manualno = trim($_POST['manualno']);
			$dateneed = trim(_dateFormatoSql($_POST['date_needed']));
			$remarks = trim($_POST['remarks']);
			//$approved = trim($_POST['approved']);
			$checked = trim($_POST['checked']);
			$supplier = trim($_POST['supplier']);
			$dept = trim($_POST['dept']);
			$loc = trim($_POST['loc']);
			// add ledger check gc total

			$approved = $_SESSION['gc_fullname'];



			// get ledger check number
			$lnumber = checkledgernumber($link);
			$reqtotal = ledgerCheckRequisitionApproval($link,$id);
			// get production request total
			// $reqtotal = 
			$link->autocommit(FALSE);
			$query = $link->query(
				"INSERT INTO 
					ledger_check
				(
				    cledger_no, 
				    cledger_datetime, 
				    cledger_type, 
				    cledger_desc, 
				    cdebit_amt, 
				    c_posted_by 
				) 
				VALUES 
				(
				    '$lnumber',
				    NOW(),
				    'GCRA',
				    'GC Requisition Approved',
				    '$reqtotal',
				    '".$_SESSION['gc_id']."'
				)
			");

			if($query)
			{

				$query_ins = $link->query(
					"INSERT INTO 
						requisition_entry
					(
						requis_erno, 
						requis_req, 
						requis_need, 
						requis_loc, 
						requis_dept, 
						requis_rem, 
						repuis_pro_id, 
						requis_req_by, 
						requis_checked,
						requis_supplierid,
						requis_ledgeref,
						requis_foldersaved
					)
					VALUES 
					(
						'$erquestno',
						NOW(),
						'$dateneed',
						'$loc',
						'$dept',
						'$remarks',
						'$id',
						'".$_SESSION['gc_id']."',
						'$checked',
						'$supplier',
						'$lnumber',
						'$foldersaved'
					)		
				");

				if($query_ins)
				{
					$query_update = $link->query(
						"UPDATE 
							`production_request` 
						SET 
							`pe_requisition`='1' 
						WHERE 
							`pe_id` = '$id'
					");

					if($query_update){

				        $query_sup = $link->query("SELECT * FROM `supplier` WHERE `gcs_id`='$supplier'");

				        if($query_sup){
				        	$row_sup = $query_sup->fetch_object();
				        	$companyname = $row_sup->gcs_companyname;
				        	$person = $row_sup->gcs_contactperson;
				        	$contact = $row_sup->gcs_contactnumber;
				        	$address = $row_sup->gcs_address;
				        }

				        $date = new DateTime($todays_date);
						$datereq = $date->format('m-d-Y');

						$date = new DateTime($dateneed);
						$daten = $date->format('m-d-Y');

						$sd='';

						$f = $folder.'req'.$erquestno.'.txt';	

						if($fh = fopen($f, 'w'))
						{
							$sd.="HEADER:".
							"\r\n".
					        "GC E-REQUISION NO|".ltrim($erquestno, '0').
					        "\r\n".
					        "DATE_REQUESTED|".$datereq.
					        "\r\n".
					        "DATE_NEEDED|".$daten.
					        "\r\n".
					        "APPROVED_BY|".$approved.
					        "\r\n".
					        "CHECKED_BY|".$checked.
					       	"\r\n".
					        "REMARKS|".$remarks.
					       	"\r\n".
					        "DETAILS:".					     
					        "\r\n";
					        $select = 'denomination.denom_fad_item_number,
										production_request_items.pe_items_quantity';
							$join = 'INNER JOIN
										denomination
									ON 
										denomination.denom_id = production_request_items.pe_items_denomination';
							$where = 'production_request_items.pe_items_request_id='.$id;
					        $details = getAllData($link,'production_request_items',$select,$where,$join,'');

					        foreach ($details as $key) 
					        {					        	      	
					        	$sd.=$key->denom_fad_item_number.'|'.$key->pe_items_quantity.'|pcs'.
					        	"\r\n";					        	
					        }
							fwrite($fh, $sd);					
							fclose($fh);
							$link->commit();
							$response['st'] = 1;
							$response['id'] = $id;
							$response['saved'] = $foldersaved;
						}
						else 
						{
							$response['msg'] = 'Error Saving Textfile';
						}


					} else {
						$response['msg'] = $link->error;
					}
				} else {
					$response['msg'] = $link->error;		
				}
			}
			else 
			{
				$response['msg'] = $link->error;
			}
		}
		elseif($status=='2')
		{

		}
		elseif ($status=='3') 
		{
			$total = 0;
			$lnum = ledgerNumber($link);
			// get production details
			$query = $link->query(
				"SELECT
					production_request.pe_type,
					production_request.pe_group
				FROM 
					production_request 
				WHERE 
					production_request.pe_id = '".$id."'
			");

			if($query)
			{
				$row = $query->fetch_object();

				$ptype = $row->pe_type;
				$pgroup = $row->pe_group;

				$link->autocommit(FALSE);
				if(updateProductionStatus($link,$id))
				{
					$amount = getProductionBudget($link,$id);
					if(tagCancelledGC($link,$id))
					{
						$q_ins = $link->query(
							"INSERT INTO 
								ledger_budget
							(
							    bledger_no, 
							    bledger_datetime,
							    bledger_type,
							    bledger_typeid,
							    bledger_group,
							    bdebit_amt
							)
							VALUES 
							(
							    '".$lnum."',
							    NOW(),
							    'RC',
							    '$ptype',
							    '$pgroup',
							    '$amount'
							)
						");

						if($q_ins)
						{
							$last_id = $link->insert_id;
							$query_insc = $link->query(
								"INSERT INTO 
									cancelled_production_request
								(
									cpr_pro_id, 
									cpr_isrequis_cancel,
									cpr_ldgerid,
									cpr_at, 
									cpr_by
								) 
								VALUES 
								(
									'$id',
									'1',
									'$last_id',
									NOW(),
									'".$_SESSION['gc_id']."'
								)
							");
							
							if($query_insc)
							{
								$link->commit();
								$response['st'] = 1;
							}
						}
						else 
						{
							$response['msg'] = $link->error;
						}
					} 
				}
			}
			else 
			{
				$response['msg'] = $link->error;
			}
		}

		echo json_encode($response);
	} 
	elseif ($action=='updateRequestBudget') 
	{
		$response['st'] = 0;
		$imageError = 0;
		$haspic = true;
		$oldpic = true;
		$imagename = '';
		$date_needed =  _dateFormatoSql($_POST['date_needed']);
		$br_num =  $_POST['br_req_num'];
		$request_budget = $_POST['requestBudget'];
		$request_budget = trim(str_replace( ',', '', $request_budget));
		$remarks = $_POST['remarks'];
		$reqID = $_POST['reqid'];
		$imagenameold = $_POST['imgname'];
		$imagename = $_POST['imgname'];

		$group = isset($_POST['group']) ? $_POST['group'] : 0;

		if(trim($imagename)=='')
		{
			$oldpic = false;
		}

		if($_FILES['pic']['error'][0]==4){
			$haspic = false;
		}

		if($haspic)
		{
			$allowedTypes = array('image/jpeg');

			$fileType = $_FILES['pic']['type'][0];

			if(!in_array($fileType, $allowedTypes))
			{
				$imageError = 1;
			} 
			else 
			{
				$name = $_FILES['pic']['name'][0];
				$expImg = explode(".",$name);
				$prodImg = $expImg[0];
				$imgType = $expImg[1];

				$imagename = $_SESSION['gc_id'].'-'.getTimestamp().'.'.$imgType;
				$imageError = 0;
			}
		}

		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif(!empty($request_budget) &&
			!empty($remarks)&&
			!empty($br_num)&&
			!empty($date_needed))
		{
			if(!$imageError)
			{
				if(getField($link,'br_request_status','budget_request','br_id',$reqID)==0)
				{
					$query_update = $link->query(
						"UPDATE 
							`budget_request` 
						SET 						
							`br_request`='$request_budget',						
							`br_requested_by`='".$_SESSION['gc_id']."',												
							`br_remarks`='$remarks',
							`br_requested_needed`='$date_needed ',
							`br_file_docno`='$imagename',
							`br_group`='$group'					
						WHERE 
							`br_id`='$reqID'
						AND
							`br_request_status`=0
					");

					if($query_update)
					{
						if($haspic)
						{
							if(move_uploaded_file($_FILES['pic']['tmp_name'][0], "assets/images/budgetRequestScanCopy/" . $imagename))
							{
								if($oldpic)
								{
									unlink('assets/images/budgetRequestScanCopy/'.$imagenameold);
								}
								$response['st'] = 1;
								$link->commit();
							}
							else 
							{
								$response['msg'] = 'Error Uploading image.';
							}

						}
						else 
						{
							$response['st'] = 1;
							$link->commit();
						}	
					}
					else 
					{
						$response['msg'] = $link->error;
					}
				}
				else 
				{
					$response['msg'] = 'Budget request already approved/cancelled.';
				}

			}
			else 
			{
				$response['msg'] = 'Upload file type not allowed.';
			} 

		}
		else 
		{
			$response['msg'] = 'Please fill up form.';
		}

		echo json_encode($response);


	} 
	elseif ($action=='updateProductionRequest')
	{
		$response['st'] = 0;
		$imageError = 0;
		$oldpic = true;
		$imagename = '';
		$hasdenom=false;
		$haspic = true;
		$dateneed = _dateFormatoSql($_POST['date_needed']);
		$penum = $_POST['penum'];
		$remarks = $_POST['remarks'];
		$imagenameold = $_POST['imgname'];
		$imagename = $_POST['imgname'];
		$reqid = $_POST['reqid'];
		$pegroup = isset($_POST['group']) ? $_POST['group'] : 0; 

		if(trim($imagename)=='')
		{
			$oldpic = false;
		}

		if($_FILES['pic']['error'][0]==4){
			$haspic = false;
		}

		if($haspic)
		{
			$allowedTypes = array('image/jpeg');

			$fileType = $_FILES['pic']['type'][0];

			if(!in_array($fileType, $allowedTypes))
			{
				$imageError = 1;
			} 
			else 
			{
				$name = $_FILES['pic']['name'][0];
				$expImg = explode(".",$name);
				$prodImg = $expImg[0];
				$imgType = $expImg[1];

				$imagename = $_SESSION['gc_id'].'-'.getTimestamp().'.'.$imgType;
				$imageError = 0;
			}
		}


		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif(!empty($remarks)&& 
			!empty($dateneed))
		{
			if(!$imageError)
			{
				$link->autocommit(FALSE);
				$query_update = $link->query(
					"UPDATE 
						production_request 
					SET 								
						pe_requested_by='".$_SESSION['gc_id']."',								
						pe_remarks='$remarks',
						pe_date_needed='$dateneed',
						pe_file_docno='$imagename',
						pe_group = '$pegroup'							
					WHERE 
						pe_id='$reqid'
					AND
						pe_status='0'
				");

				if($query_update)
				{

					if(getOne($link,'pe_status','production_request','pe_id') == 0)
					{
						foreach ($_POST as $key => $value) {
							if (strpos($key, 'denoms') !== false)
							{
								$denom = $value == '' ? 0 : str_replace(',','',$value);
								$denom_ids = substr($key, 6);
								if(!empty($denom))
								{
					                if(checkbeforeUpdatePendingGC($link,$denom_ids,$reqid))
					                {
					                	updateProductionRequest($link,$value,$denom_ids,$reqid);                  
					                } 
					                else 
					                {                  
					                	insertDenomRequest($link,'production_request_items','pe_items_id', 'pe_items_denomination', 'pe_items_quantity', 'pe_items_request_id',$denom_ids,$value,$reqid,'pe_items_remain');                 
					                }            
								}
								else 
								{
					                if(checkbeforeUpdatePendingGC($link,$denom_ids,$reqid)){
					                  deleteProductionRequestItem($link,$reqid,$denom_ids);  
					                }
								}

							} 
							//echo 'Key =>'.substr($key, 6).' Value =>'.$value;

						}

						if($haspic)
						{
							if(move_uploaded_file($_FILES['pic']['tmp_name'][0], "assets/images/productionRequestFile/" . $imagename))
							{
								if($oldpic)
								{
									unlink('assets/images/productionRequestFile/'.$imagenameold);
								}
								$response['st'] = 1;
								$link->commit();
							}
							else 
							{
								$response['msg'] = 'Error Uploading image.';
							}

						}
						else 
						{
							$response['st'] = 1;
							$link->commit();
						}							
					}
					else 
					{
						$response['msg'] = 'Production request already approved/cancelled.';
					}
				}
				else 
				{
					$response['msg'] = $link->error;
				}									
			}
			else 
			{
				$response['msg'] = 'Upload file type not allowed';
			}
		}
		else 
		{
			$response['msg'] = 'Please fill all <span class="requiredf">*</span>required fields.';
		}

		echo json_encode($response);

	} 
	elseif($action=='selectSupplier')
	{
		$storeid = trim($_POST['store']);
		if(!empty($storeid)){

			$query = $link->query("SELECT * FROM `supplier` WHERE gcs_id='$storeid'");

			$row = $query->fetch_object();

			$response['message'] = 'success';
			$response['name'] = $row->gcs_contactperson;
			$response['mobile'] = $row->gcs_contactnumber;
			$response['address'] = $row->gcs_address;
			echo json_encode($response);


		} else {
			$response['message'] = 'failed';
			echo json_encode($response);
		}
	} elseif($action=='locategc'){
		$gc =  trim($_POST['gcstat']);
		
		$gcGenerated = statusGCGenerated($link,$gc);

		if(count($gcGenerated)>0):?>
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4>GC Barcode Number Found.</h4>
                        </div>
                        <div class="panel-body text-center">
                            <p>
                                <h2><strong><?php echo $gc; ?></strong></h2>
                            </p>
                            <?php foreach ($gcGenerated as $key): ?>
								<p>Denomination: &#8369 <?php echo number_format($key->denomination,2); ?></p>
                            <?php endforeach; ?>                            
                            <?php if (count($allocated = statusLocation($link,$gc))>0): ?>
								<table class="table">
									<thead>
										<tr>
											<td>Location</td>
											<td>Date Allocated</td>
											<td>Allocated by</td>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($allocated as $key): ?>
											<tr>
												<td><?php echo $key->store_name; ?></td>
												<td><?php echo _dateFormat($key->loc_date); ?></td>
												<td><?php echo ucwords($key->firstname.' '.$key->lastname); ?></td>
											</tr>
										<?php endforeach ?>
									</tbody>									
								</table>
                            <?php endif; ?>
							<?php if (count($released = statusReleased($link,$gc))>0): ?>
								<table class="table">
									<thead>
										<tr>
											<td>Date Released</td>
											<td>Released by</td>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($released as $key): ?>
											<tr>
												<td><?php echo _dateFormat($key->rel_date); ?></td>
												<td><?php echo ucwords($key->firstname.' '.$key->lastname); ?></td>
											</tr>
										<?php endforeach ?>
									</tbody>									
								</table>
							<?php endif; ?>
							<?php if (count($sold = statusSold($link,$gc))>0): ?>
								<table class="table">
									<thead>
										<tr>
											<td>Date Sold</td>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($sold as $key): ?>
											<tr>
												<td><?php echo _dateFormat($key->trans_datetime); ?></td>
											</tr>
										<?php endforeach ?>
									</tbody>									
								</table>
							<?php endif; ?>
                        </div>
                    </div>
		<?php else: ?>
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h4>GC Barcode Number Not Found.</h4>
                    </div>
                    <div class="panel-body text-center">
                        <p>
                            <h2><strong><?php echo $gc; ?></strong></h2>
                        </p>
                    </div>
                </div>						
		<?php endif;
	} elseif ($action=='addnewcustomer') {
		$fname = $link->real_escape_string(trim(strtolower($_POST['fname'])));
		$lname = $link->real_escape_string(trim(strtolower($_POST['lname'])));

		$mname = !empty($_POST['mname']) ? $link->real_escape_string(trim(strtolower($_POST['mname']))) : "";
		if(isset($_POST['extname']))
		{
			$extname = $link->real_escape_string(trim(strtolower($_POST['extname'])));
		}
		else 
		{
			$extname = '';
		}

		$dob = !empty($_POST['dob']) ? _dateFormatoSql($_POST['dob']) : ''; 
		$sex = $_POST['sex'];
		$cstatus = $_POST['cstatus'];
		$valid = $_POST['valid'];
		$address = $_POST['address'];
		$mobnum = $_POST['mobnum'];

		$dob = !empty($dob) ? "$dob" : "NULL";
		$sex = !empty($sex) ? $link->real_escape_string(trim($sex)) : "";
		$cstatus = !empty($cstatus) ? $link->real_escape_string(trim($cstatus)) : "";
		$valid = !empty($valid) ? $link->real_escape_string(trim($valid)) : "";
		$address = !empty($address) ? $link->real_escape_string(trim(strtolower($address))) : "";
		$mobnum = !empty($mobnum) ? $link->real_escape_string(trim(strtolower($mobnum))) : "";


		$userid = $_SESSION['gc_id'];
        $store_id = getField($link,'store_assigned','users','user_id',$userid);

		if(!isset($_SESSION['gc_id']))
		{
			echo 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif(!empty($fname)&&
			!empty($lname)){

			$query_ins = $link->query(
				"INSERT INTO 
					customers
				(
					cus_id, 
					cus_fname, 
					cus_lname, 
					cus_idnumber, 
					cus_address, 
					cus_mobile, 
					cus_store_register, 
					cus_register_at, 
					cus_register_by,
					cus_mname,
					cus_namext,
					cus_dob,
					cus_sex,
					cus_cstatus
				) 
				VALUES 
				(
					'',
					'$fname',
					'$lname',
					'$valid',
					'$address',
					'$mobnum',
					'$store_id',
					NOW(),
					'".$_SESSION['gc_id']."',
					'$mname',
					'$extname',
					'$dob',
					'$sex',
					'$cstatus'
				)
			");

			if($query_ins){
				echo 'success';
			} else {
				echo $link->error;
			}
		}
	} elseif ($action=='reloadcustomertable') {
		$query = $link->query("SELECT * FROM customers");
		while($row = $query->fetch_object()): ?>
            <tr>
                <td><?php echo ucfirst($row->cus_fname); ?></td>
                <td><?php echo ucfirst($row->cus_lname); ?></td>
                <td><?php echo $row->cus_idnumber; ?></td>
                <td><?php echo ucwords($row->cus_address); ?></td>
                <td><?php echo $row->cus_mobile; ?></td>
                <td>
                    <a href="<?php echo $row->cus_id; ?>" class="cus-update" alt="update">
                        <i class="fa fa-cogs"></i>
                    </a>
                    <a href="<?php echo $row->cus_id; ?>" class="cus-delete" alt="update">
                        <i class="fa fa-minus-square">                                        
                        </i>
                    </a>
                </td>
            </tr>	
		<?php
		endwhile;	
	} 
	elseif ($action=='verification')
	{
		$isreprint = $link->real_escape_string(trim($_POST['isreprint']));
		$response['st'] = 0;
		$isFound = false;
		$isVerified = false;
		$verifyGC = false;
		$gctype = 0;
		$isRevalidateGC = false;
		$gc =  $link->real_escape_string(trim($_POST['gcbarcode']));
		$cusid = $link->real_escape_string(trim($_POST['cus-id']));		
		$storeid = $link->real_escape_string(trim($_SESSION['gc_store']));
		$storename = getStoreName($link,$storeid);
		$mid_initial = "";
		$bngGC = false;

		//check if gc is regular/promo/special
		if(checkIfExist($link,'barcode_no','gc','barcode_no',$gc))
		{

			//check if gc is institution 
			if(numRows2($link,'institut_transactions_items','instituttritems_barcode',$gc) > 0)
			{
				$isFound = true;
				$gctype = 1;
			}

			//check if gc already sold
			$sold_info = checkIfGCAlreadySold($link,$gc);
			if(!is_null($sold_info))
			{
				$isFound = true;
				$gctype = 1;
			}

			//check if beam and go
			$bngInfo = checkIfGCisBeamAndGo($link,$gc);
			if(!is_null($bngInfo))
			{
				$isFound = true;
				$gctype = 6;
				$bngGC= true;
			}

			if(numRows2($link,'promogc_released','prgcrel_barcode',$gc))
			{
				$isFound = true;
				$gctype = 4;
			}

			if($isFound)
			{			
				// // To removed
				// if($_SESSION['gc_store'] == 4 || $_SESSION['gc_store']== 1)
				// {
				// 	$tfilext = '.igc';
				// }
				// else
				// {
				// 	$tfilext = '.'.getGCTextfileExtension($link,'txtfile_extension_internal');
				// }
				// // end To Removed

				$tfilext = '.'.getGCTextfileExtension($link,'txtfile_extension_internal');
				$barcodetf = $gc.$tfilext;
			    $denom = getDenominationByBarcode($link,$gc);
			}
		}
		elseif(checkIfExist($link,'spexgcemp_barcode','special_external_gcrequest_emp_assign','spexgcemp_barcode',$gc)) 
		{
			$table = 'special_external_gcrequest_emp_assign';
			$select = 'spexgcemp_denom';
			$where = "special_external_gcrequest_emp_assign.spexgcemp_barcode='".$gc."'
				AND
					approved_request.reqap_approvedtype='special external releasing'";
			$join = 'INNER JOIN
				approved_request
				ON
				approved_request.reqap_trid = special_external_gcrequest_emp_assign.spexgcemp_trid';
			$limit = '';
			$special = getSelectedData($link,$table,$select,$where,$join,$limit);

			if(count($special) > 0)
			{		

				// // To removed
				// if($_SESSION['gc_store'] == 4 || $_SESSION['gc_store']== 1)
				// {
				// 	$tfilext = '.egc';
				// }
				// else
				// {
				// 	$tfilext = '.'.getGCTextfileExtension($link,'txtfile_extension_internal');
				// }
				// // end To Removed	
				
				$tfilext = '.'.getGCTextfileExtension($link,'txtfile_extension_external');
				$denom = getSpecialGCDenom($link,$gc);
				$barcodetf = $gc.$tfilext;
				$isFound = true;
				$gctype=3;
			}
		}

		//get store id
		if(isset($_SESSION['gc_store']))
		{
			//get store textfile folder
			$ip = getField($link,'store_textfile_ip','stores','store_id',$_SESSION['gc_store']);

			if(!empty($ip) && file_exists($ip))
			{
				$verificationfolder = $ip;
			}
		}

		if(!isset($_SESSION['gc_id']) || !isset($_SESSION['gc_store']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif(!$isFound)
		{
			$response['msg'] = 'GC Barcode # '.$gc.' not found.';
		}
		elseif ($bngGC==true && $_SESSION['gc_store']!=$bngInfo->strec_storeid) 
		{
			$response['msg'] = 'Invalid Store. <br /> Store Purchased: '.$bngInfo->store_name;
		}
		elseif ($bngGC==true && $payto!='STORE DEPARTMENT') 
		{
			$response['msg'] = 'BEAM AND GO GC are only allowed to redeemed at Store Department.';
		}
		elseif(empty($cusid))
		{
			$response['msg'] = 'Please select customer.';
		}
		elseif(!checkIfExist($link,'cus_id','customers','cus_id',$cusid))
		{
			$response['msg'] = 'Customer not found.';
		}
		else 
		{			
	 		$customerdetails =  getCustomerDetailsByID($link,$cusid);

	 		if(trim($customerdetails->cus_mname)!="")
	 		{
	 			$mid_initial =  strtoupper(substr($customerdetails->cus_mname,0,1)).'.';
	 		}

	 		//$mid_initial = is_null($customerdetails->cus_mname)? '': strtoupper(substr($customerdetails->cus_mname,0,1)).'.';

	 		//check  if gc already verified and used or gc is revalidated
			$verifiedGCDetails = checkIFGCAlreadyVerified($link,$gc);
			if(is_null($verifiedGCDetails))
			{
				$isVerified = false; 
			}
			else 
			{
				$isVerified = true;
			}

			if($isreprint)
			{
				$vreprint = true;
				$vvrefy = true;
				if($isVerified)
				{
					if($cusid!=$verifiedGCDetails->vs_cn)
					{
						$msg = 'Invalid Customer</br>
						Verified Customer: '.ucwords($verifiedGCDetails->cus_fname.' '.$verifiedGCDetails->cus_lname);
						$vreprint = false;
					}
					elseif ($_SESSION['gc_store']!=$verifiedGCDetails->vs_store) 
					{
						$msg = 'Invalid Store</br>
						Store Verified: '.$verifiedGCDetails->store_name;
						$vreprint = false;
					}
					else 
					{
						//check first if gc is revalidated.
					 	$revalidated = checkforRevalidated($link,$gc);
					 	if(is_null($revalidated))
					 	{
					 		if($verifiedGCDetails->vs_date == $todays_date)
					 		{
					 			$vreprint = true;
					 			$vvrefy = false;
					 		}
					 		else
					 		{
					 			$msg = 'GC Barcode # '.$gc.' was verified '._dateFormat($verifiedGCDetails->vs_date).' reprint is only valid on the day GC verified.'; 
					 			$vreprint = false;
					 		}
					 	}
					 	else 
					 	{
					 		$msg = 'GC Barcode # '.$gc.' is already reverified.';
					 		$vreprint = false;
					 	}
					}
				}
				else 
				{
					$msg = 'GC Barcode # '.$gc.' not yet verified.';
					$vreprint = false;
				}

				if($vreprint)
				{
		 			$query = $link->query(
		 				"INSERT INTO 
	 					gc_verification_reprint_details
	 					(
	 						gcvrep_barcode, 
	 						gcvrep_datetime, 
	 						gcvrep_by
	 					) 
		 				VALUES 
		 				(
		 					'$gc',
		 					NOW(),
		 					'".$_SESSION['gc_id']."'
		 				)
		 			");
		 			if($query)
		 			{
		 				$response['st'] = 1;
			 			$response['barcode'] = $gc;
			 			$response['customer'] = strtoupper($customerdetails->cus_fname.' '.$mid_initial.' '.$customerdetails->cus_lname);
			 			$response['date'] = $todays_date;
			 			$response['time'] = $todays_time;
			 			$response['storename'] = $verifiedGCDetails->store_name;
			 			if($vvrefy)
			 			{
			 				$response['reval'] = 1;
			 			}
			 			else 
			 			{
			 				$response['reval'] = 0;
			 			}
					    $response['flashmsg'] = 'GC Barcode # '.$gc.' verification successfully reprinted.';
						$response['msg'] = '<div class="verifygcbar">GC Barcode: <span class="verifyx">'.$gc.'</span></div>
						<div class="verifygcdenom">Denomination: <span class="verifyx">'.number_format($denom,2).'</span></div>';
				  	}
				}
				else 
				{
					$response['msg'] = $msg;
				}

			}
			else 
			{
				if($isVerified)
				{
					if($verifiedGCDetails->vs_date <= $todays_date && $verifiedGCDetails->vs_tf_used=='*')
					{
						$response['msg'] = 'GC Barcode # '.$gc.' is already verified and used.';
					}
					elseif ($verifiedGCDetails->vs_date<=$todays_date && $verifiedGCDetails->vs_tf_used=='') 
					{
					 	$revalidated = checkforRevalidated($link,$gc);

					 	// var_dump($revalidated);
					 	// exit();
					 	//var_dump($revalidated);
					 	if(is_null($revalidated))
					 	{
					 		$response['msg'] = 'GC Barcode # '.$gc.' is already verified.';
					 	}
					 	elseif ($revalidated->reval_revalidated != '0') 
					 	{
					 		$response['msg'] = 'GC Barcode # '.$gc.' is already reverified.';
					 	}
					 	elseif ($revalidated->trans_store != $storeid) 
					 	{
							$response['msg'] = 'GC Revalidated at '.$revalidated->store_name.'<br>
							Date Revalidated: '._dateFormat($revalidated->trans_datetime).'<br />';
					 	}
					 	else 
					 	{
			 				$recent_cc = getCustomerCodeLastVerification($link,$gc);
							if($cusid==$recent_cc)
							{
								if(_dateFormatoSql($revalidated->trans_datetime)==$todays_date)
								{
									$verifyGC = true;
									$isRevalidateGC = true;
								}
								else 
								{
									$response['msg'] = 'GC Barcode # '.$gc.' already verified. <br />
									Revalidation Info <br />
									Store Revalidated: '.$revalidated->store_name.'<br>
									Date Revalidated: '._dateFormat($revalidated->trans_datetime).'<br />';
								}
							}
							else 
							{										  	
								$fullname = getCustomerFullname($link,$recent_cc);
								$response['msg'] = 'Invalid Customer Information</br>
								Verification Info<br />
								Store Validated: '.$revalidated->store_name.'<br>
								Date: '._dateFormat($revalidated->trans_datetime).'<br />
								Time:'._timeFormat($revalidated->trans_datetime).'<br />										  
								Customer Name: '.ucwords($fullname);            
							}
					 	}
					}
				}
				else 
				{
					$verifyGC = true;
				}

				$promo_gcexpired = false; 

				if($gctype==4)
				{
					//get date gc released from marketing
					$date_rel = getField($link,'prgcrel_at','promogc_released','prgcrel_barcode',$gc);

					$days = getDateTo($link,'promotional_gc_verification_expiration');

					$end_date = date('Y-m-d', strtotime("+".$days,strtotime($date_rel)));
					
					if(_dateFormatoSql($end_date) < $todays_date)
					{
						$promo_gcexpired = true;
					}
				}

				if($promo_gcexpired)
				{
					$response['msg'] = 'Promotional GC Barcode #'.$gc.' already expired.';
				}
				else
				{
					// check if gc was reported lost
					$table = 'lost_gc_barcodes';
					$select = 'lost_gc_barcodes.lostgcb_denom,
						lost_gc_barcodes.lostgcb_status,
						stores.store_name,
						lost_gc_details.lostgcd_owname,
						lost_gc_details.lostgcd_address,
						lost_gc_details.lostgcd_contactnum,
						lost_gc_details.lostgcd_datereported,
						lost_gc_details.lostgcd_datelost';
					$where = "lost_gc_barcodes.lostgcb_barcode='".$gc."'";
					$join = 'INNER JOIN
							lost_gc_details
						ON
							lost_gc_details.lostgcd_id = lost_gc_barcodes.lostgcb_repid
						INNER JOIN
							stores
						ON
							stores.store_id = lost_gc_details.lostgcd_storeid';
					$limit = '';

					$lost = getSelectedData($link,$table,$select,$where,$join,$limit);


					if(count($lost) > 0 && empty($lost->lostgcb_status))
					{
						$response['msg'] = "GC Barcode # ".$gc." reported lost.<br / >
						Date Reported: <span class='tit'>"._dateFormat($lost->lostgcd_datereported)."</span><br / >
						Owner's Name: <span class='tit'>".ucwords($lost->lostgcd_owname)."</span><br / >
						Address: <span class='tit'>".$lost->lostgcd_address."</span><br / >
						Contact #: <span class='tit'>".$lost->lostgcd_contactnum."</span><br / >";

					}
					else 
					{
						if($verifyGC)
						{
							$link->autocommit(FALSE);
						    if($isRevalidateGC)
						    {
						    	$query_update = $link->query(
						    		"UPDATE 
										store_verification 
									SET 
										vs_reverifydate=NOW(),
										vs_reverifyby='".$_SESSION['gc_id']."',
										vs_tf_eod=''
									WHERE 
										vs_barcode='$gc'

						    	"); 

						    	if(!$query_update)
						    	{
						    		$response['msg'] = $link->error;
						    	}
						    }
						    else 
						    {
							    $query_ins = $link->query(
							      "INSERT INTO 
							        store_verification
							      (
							        vs_barcode, 
							        vs_cn, 
							        vs_by, 
							        vs_date, 
							        vs_time, 
							        vs_tf, 
							        vs_store,
							        vs_tf_balance,
							        vs_gctype,
							        vs_tf_denomination
							      ) 
							        VALUES 
							      (
							        '$gc',
							        '$cusid',
							        '".$_SESSION['gc_id']."',
							        '$todays_date',
							        '$todays_time',
							        '$barcodetf',
							        '$storeid',
							        '$denom',
							        '$gctype',
							        '$denom'
							      )
							    ");

							    if(!$query_ins)
							    {
							      $response['msg'] = $link->error;
							    }
							    else 
							    {
							      $lastid = $link->insert_id;     
							    }
						    }
						    $denom = number_format($denom,2);
						    $denomstext = str_replace(",", "", $denom);
						    $sd='';						    
						    $f = $verificationfolder.'/'.$gc.$tfilext;
						    $fh = fopen($f, 'w') or die("cant open file");
						    $sd.="000,".$cusid.",0,".strtoupper($customerdetails->cus_fname.' '.$mid_initial.' '.$customerdetails->cus_lname)." ".
						    "\r\n".
						    "001,".$denomstext.
						    "\r\n".
						    "002,0".
						    "\r\n".
						    "003,0".
						    "\r\n".
						    "004,".$denomstext.
						    "\r\n".
						    "005,0".
						    "\r\n".
						    "006,0".
						    "\r\n".
						    "007,0";
						    fwrite($fh, $sd);         
						    fclose($fh);

						    if($isRevalidateGC)
						    {
						      $query_updateValidate = $link->query(
						        "UPDATE 
						          `transaction_revalidation` 
						        SET 
						          `reval_revalidated`='1' 
						        WHERE
						          `reval_barcode` = '$gc'
						      ");
						      if($query_updateValidate)
						      {
						        $flashmsg = 'GC Barcode #'.$gc.' successfully reverified.';
						      }
						    }
						    else 
						    {
						      $flashmsg = 'GC Barcode #'.$gc.' successfully verified.';
						    }
						    $mid_initial = empty($customerdetails->cus_mname)? '': strtoupper(substr($customerdetails->cus_mname,0,1)).'.';
						    $link->commit();
						    if($isRevalidateGC)
						    {
						    	$response['reval'] = 1;
						    }
						    else
						    {
						    	$response['reval'] = 0;
						    }
						    $response['st'] = 1;
						    $response['barcode'] = $gc;
						    $response['customer'] = strtoupper($customerdetails->cus_fname.' '.$mid_initial.' '.$customerdetails->cus_lname);
						    $response['date'] = $todays_date;
						    $response['time'] = $todays_time;
						    $response['storename'] = $storename;

						    $response['flashmsg'] = $flashmsg;
						    $response['msg'] = '<div class="verifygcbar">GC Barcode: <span class="verifyx">'.$gc.'</span></div>
						      <div class="verifygcdenom">Denomination: <span class="verifyx">'.$denom.'</span></div>';

						}
					}


				}
				// end is verified
				
			}

			// end verifiedGCDetails
		}
		echo json_encode($response);
	} 
	elseif ($action=='verification2')
	{	
		$isreprint = $link->real_escape_string(trim($_POST['isreprint']));
		$response['st'] = 0;
		$isFound = false;
		$isVerified = false;
		$verifyGC = false;
		$gctype = 0;
		$isRevalidateGC = false;
		$gc =  $link->real_escape_string(trim($_POST['gcbarcode']));
		$payto = $link->real_escape_string(trim($_POST['payto']));
		$cusid = $link->real_escape_string(trim($_POST['cus-id']));		
		$storeid = $link->real_escape_string(trim($_SESSION['gc_store']));
		$storename = getStoreName($link,$storeid);
		$mid_initial = "";
		$bngGC = false;

		//check if gc is regular/promo/special
		if(checkIfExist($link,'barcode_no','gc','barcode_no',$gc))
		{

			//check if gc is institution 
			if(numRows2($link,'institut_transactions_items','instituttritems_barcode',$gc) > 0)
			{
				$isFound = true;
				$gctype = 1;
			}

			//check if gc already sold
			$sold_info = checkIfGCAlreadySold($link,$gc);
			if(!is_null($sold_info))
			{
				$isFound = true;
				$gctype = 1;
			}

			//check if beam and go
			$bngInfo = checkIfGCisBeamAndGo($link,$gc);
			if(!is_null($bngInfo))
			{
				$isFound = true;
				$gctype = 6;
				$bngGC= true;
			}

			if(numRows2($link,'promogc_released','prgcrel_barcode',$gc))
			{
				$isFound = true;
				$gctype = 4;
			}

			if($isFound)
			{	

				$tfilext = '.'.getGCTextfileExtension($link,'txtfile_extension_internal');
				$barcodetf = $gc.$tfilext;
			    $denom = getDenominationByBarcode($link,$gc);
			}
		}
		elseif(checkIfExist($link,'spexgcemp_barcode','special_external_gcrequest_emp_assign','spexgcemp_barcode',$gc)) 
		{
			$table = 'special_external_gcrequest_emp_assign';
			$select = 'spexgcemp_denom';
			$where = "special_external_gcrequest_emp_assign.spexgcemp_barcode='".$gc."'
				AND
					approved_request.reqap_approvedtype='special external releasing'";
			$join = 'INNER JOIN
				approved_request
				ON
				approved_request.reqap_trid = special_external_gcrequest_emp_assign.spexgcemp_trid';
			$limit = '';
			$special = getSelectedData($link,$table,$select,$where,$join,$limit);

			if(count($special) > 0)
			{		

				$tfilext = '.'.getGCTextfileExtension($link,'txtfile_extension_external');
				$denom = getSpecialGCDenom($link,$gc);
				$barcodetf = $gc.$tfilext;
				$isFound = true;
				$gctype=3;
			}
		}

		//echo $tfilext;

		//exit();
		//get store id
		if(isset($_SESSION['gc_store']))
		{
			//get store textfile folder
			$ip = getField($link,'store_textfile_ip','stores','store_id',$_SESSION['gc_store']);

			if(!empty($ip) && file_exists($ip))
			{
				$verificationfolder = $ip;
			}
		}

		if(!isset($_SESSION['gc_id']) || !isset($_SESSION['gc_store']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif(!$isFound)
		{
			$response['msg'] = 'GC Barcode # '.$gc.' not found.';
		}
		elseif ($bngGC==true && $_SESSION['gc_store']!=$bngInfo->strec_storeid) 
		{
			$response['msg'] = 'Invalid Store. <br /> Store Purchased: '.$bngInfo->store_name;
		}
		elseif ($bngGC==true && $payto!='STORE DEPARTMENT') 
		{
			$response['msg'] = 'BEAM AND GO GC are only allowed to redeemed at Store Department.';
		}
		elseif(empty($cusid))
		{
			$response['msg'] = 'Please select customer.';
		}
		elseif(!checkIfExist($link,'cus_id','customers','cus_id',$cusid))
		{
			$response['msg'] = 'Customer not found.';
		}
		else 
		{			
	 		$customerdetails =  getCustomerDetailsByID($link,$cusid);

	 		if(trim($customerdetails->cus_mname)!="")
	 		{
	 			$mid_initial =  strtoupper(substr($customerdetails->cus_mname,0,1)).'.';
	 		}

	 		//$mid_initial = is_null(trim($customerdetails->cus_mname)) ? '': strtoupper(substr($customerdetails->cus_mname,0,1)).'.';

	 		//check  if gc already verified and used or gc is revalidated
			$verifiedGCDetails = checkIFGCAlreadyVerified($link,$gc);
			if(is_null($verifiedGCDetails))
			{
				$isVerified = false; 
			}
			else 
			{
				$isVerified = true;
			}

			if($isreprint)
			{
				$vreprint = true;
				$vvrefy = true;
				if($isVerified)
				{
					if($cusid!=$verifiedGCDetails->vs_cn)
					{
						$msg = 'Invalid Customer</br>
						Verified Customer: '.ucwords($verifiedGCDetails->cus_fname.' '.$verifiedGCDetails->cus_lname);
						$vreprint = false;
					}
					elseif ($_SESSION['gc_store']!=$verifiedGCDetails->vs_store) 
					{
						$msg = 'Invalid Store</br>
						Store Verified: '.$verifiedGCDetails->store_name;
						$vreprint = false;
					}
					else 
					{
						//check first if gc is revalidated.
					 	$revalidated = checkforRevalidated($link,$gc);
					 	if(is_null($revalidated))
					 	{
					 		if($verifiedGCDetails->vs_date == $todays_date)
					 		{
					 			$vreprint = true;
					 			$vvrefy = false;
					 		}
					 		else
					 		{
					 			$msg = 'GC Barcode # '.$gc.' was verified '._dateFormat($verifiedGCDetails->vs_date).' reprint is only valid on the day GC verified.'; 
					 			$vreprint = false;
					 		}
					 	}
					 	else 
					 	{
					 		$msg = 'GC Barcode # '.$gc.' is already reverified.';
					 		$vreprint = false;
					 	}
					}
				}
				else 
				{
					$msg = 'GC Barcode # '.$gc.' not yet verified.';
					$vreprint = false;
				}

				if($vreprint)
				{
		 			$query = $link->query(
		 				"INSERT INTO 
	 					gc_verification_reprint_details
	 					(
	 						gcvrep_barcode, 
	 						gcvrep_datetime, 
	 						gcvrep_by
	 					) 
		 				VALUES 
		 				(
		 					'$gc',
		 					NOW(),
		 					'".$_SESSION['gc_id']."'
		 				)
		 			");
		 			if($query)
		 			{
		 				$response['st'] = 1;
			 			$response['barcode'] = $gc;
			 			$response['customer'] = strtoupper($customerdetails->cus_fname.' '.$mid_initial.' '.$customerdetails->cus_lname);
			 			$response['date'] = $todays_date;
			 			$response['time'] = $todays_time;
			 			$response['storename'] = $verifiedGCDetails->store_name;
			 			if($vvrefy)
			 			{
			 				$response['reval'] = 1;
			 			}
			 			else 
			 			{
			 				$response['reval'] = 0;
			 			}
					    $response['flashmsg'] = 'GC Barcode # '.$gc.' verification successfully reprinted.';
						$response['msg'] = '<div class="verifygcbar">GC Barcode: <span class="verifyx">'.$gc.'</span></div>
						<div class="verifygcdenom">Denomination: <span class="verifyx">'.number_format($denom,2).'</span></div>';
				  	}
				}
				else 
				{
					$response['msg'] = $msg;
				}

			}
			else 
			{
				if($isVerified)
				{
					if($verifiedGCDetails->vs_date <= $todays_date && $verifiedGCDetails->vs_tf_used=='*')
					{
						$response['msg'] = 'GC Barcode # '.$gc.' is already verified and used.';
					}
					elseif ($verifiedGCDetails->vs_date<=$todays_date && $verifiedGCDetails->vs_tf_used=='') 
					{
					 	$revalidated = checkforRevalidated($link,$gc);

					 	// var_dump($revalidated);
					 	// exit();
					 	//var_dump($revalidated);
					 	if(is_null($revalidated))
					 	{
					 		$response['msg'] = 'GC Barcode # '.$gc.' is already verified.';
					 	}
					 	elseif ($revalidated->reval_revalidated != '0') 
					 	{
					 		$response['msg'] = 'GC Barcode # '.$gc.' is already reverified.';
					 	}
					 	elseif ($revalidated->trans_store != $storeid) 
					 	{
							$response['msg'] = 'GC Revalidated at '.$revalidated->store_name.'<br>
							Date Revalidated: '._dateFormat($revalidated->trans_datetime).'<br />';
					 	}
					 	else 
					 	{
			 				$recent_cc = getCustomerCodeLastVerification($link,$gc);
							if($cusid==$recent_cc)
							{
								if(_dateFormatoSql($revalidated->trans_datetime)==$todays_date)
								{
									$verifyGC = true;
									$isRevalidateGC = true;
								}
								else 
								{
									$response['msg'] = 'GC Barcode # '.$gc.' already verified. <br />
									Revalidation Info <br />
									Store Revalidated: '.$revalidated->store_name.'<br>
									Date Revalidated: '._dateFormat($revalidated->trans_datetime).'<br />';
								}
							}
							else 
							{										  	
								$fullname = getCustomerFullname($link,$recent_cc);
								$response['msg'] = 'Invalid Customer Information</br>
								Verification Info<br />
								Store Validated: '.$revalidated->store_name.'<br>
								Date: '._dateFormat($revalidated->trans_datetime).'<br />
								Time:'._timeFormat($revalidated->trans_datetime).'<br />										  
								Customer Name: '.ucwords($fullname);            
							}
					 	}
					}
				}
				else 
				{
					$verifyGC = true;
				}

				$promo_gcexpired = false; 

				if($gctype==4)
				{
					//get date gc released from marketing
					$date_rel = getField($link,'prgcrel_at','promogc_released','prgcrel_barcode',$gc);

					$days = getDateTo($link,'promotional_gc_verification_expiration');

					$end_date = date('Y-m-d', strtotime("+".$days,strtotime($date_rel)));
					
					if(_dateFormatoSql($end_date) < $todays_date)
					{
						$promo_gcexpired = true;
					}
				}

				if($promo_gcexpired)
				{
					$response['msg'] = 'Promotional GC Barcode #'.$gc.' already expired.';
				}
				else
				{
					// check if gc was reported lost
					$table = 'lost_gc_barcodes';
					$select = 'lost_gc_barcodes.lostgcb_denom,
						lost_gc_barcodes.lostgcb_status,
						stores.store_name,
						lost_gc_details.lostgcd_owname,
						lost_gc_details.lostgcd_address,
						lost_gc_details.lostgcd_contactnum,
						lost_gc_details.lostgcd_datereported,
						lost_gc_details.lostgcd_datelost';
					$where = "lost_gc_barcodes.lostgcb_barcode='".$gc."'";
					$join = 'INNER JOIN
							lost_gc_details
						ON
							lost_gc_details.lostgcd_id = lost_gc_barcodes.lostgcb_repid
						INNER JOIN
							stores
						ON
							stores.store_id = lost_gc_details.lostgcd_storeid';
					$limit = '';

					$lost = getSelectedData($link,$table,$select,$where,$join,$limit);


					if(count($lost) > 0 && empty($lost->lostgcb_status))
					{
						$response['msg'] = "GC Barcode # ".$gc." reported lost.<br / >
						Date Reported: <span class='tit'>"._dateFormat($lost->lostgcd_datereported)."</span><br / >
						Owner's Name: <span class='tit'>".ucwords($lost->lostgcd_owname)."</span><br / >
						Address: <span class='tit'>".$lost->lostgcd_address."</span><br / >
						Contact #: <span class='tit'>".$lost->lostgcd_contactnum."</span><br / >";

					}
					else 
					{
						if($verifyGC)
						{
							$link->autocommit(FALSE);
						    if($isRevalidateGC)
						    {
						    	$query_update = $link->query(
						    		"UPDATE 
										store_verification 
									SET 
										vs_reverifydate=NOW(),
										vs_reverifyby='".$_SESSION['gc_id']."',
										vs_tf_eod=''
									WHERE 
										vs_barcode='$gc'

						    	"); 

						    	if(!$query_update)
						    	{
						    		$response['msg'] = $link->error;
						    	}
						    }
						    else 
						    {
							    $query_ins = $link->query(
							      "INSERT INTO 
							        store_verification
							      (
							        vs_barcode, 
							        vs_cn, 
							        vs_by, 
							        vs_date, 
							        vs_time, 
							        vs_tf, 
							        vs_store,
							        vs_tf_balance,
							        vs_gctype,
							        vs_tf_denomination,
							        vs_payto
							      ) 
							        VALUES 
							      (
							        '$gc',
							        '$cusid',
							        '".$_SESSION['gc_id']."',
							        '$todays_date',
							        '$todays_time',
							        '$barcodetf',
							        '$storeid',
							        '$denom',
							        '$gctype',
							        '$denom',
							        '$payto'
							      )
							    ");

							    if(!$query_ins)
							    {
							      $response['msg'] = $link->error;
							    }
							    else 
							    {
							      $lastid = $link->insert_id;     
							    }
						    }


						    $denom = number_format($denom,2);
						    $denomstext = str_replace(",", "", $denom);

						    if($payto!='WHOLESALE')
						    {
							    $sd='';						    
							    $f = $verificationfolder.'/'.$gc.$tfilext;
							    $fh = fopen($f, 'w') or die("cant open file");
							    $sd.="000,".$cusid.",0,".strtoupper($customerdetails->cus_fname.' '.$mid_initial.' '.$customerdetails->cus_lname)." ".
							    "\r\n".
							    "001,".$denomstext.
							    "\r\n".
							    "002,0".
							    "\r\n".
							    "003,0".
							    "\r\n".
							    "004,".$denomstext.
							    "\r\n".
							    "005,0".
							    "\r\n".
							    "006,0".
							    "\r\n".
							    "007,0";
							    fwrite($fh, $sd);         
							    fclose($fh);
							}

						    if($isRevalidateGC)
						    {
						      $query_updateValidate = $link->query(
						        "UPDATE 
						          `transaction_revalidation` 
						        SET 
						          `reval_revalidated`='1' 
						        WHERE
						          `reval_barcode` = '$gc'
						      ");
						      if($query_updateValidate)
						      {
						        $flashmsg = 'GC Barcode #'.$gc.' successfully reverified.';
						      }
						    }
						    else 
						    {
						      $flashmsg = 'GC Barcode #'.$gc.' successfully verified.';
						    }
						    $mid_initial = empty($customerdetails->cus_mname)? '': strtoupper(substr($customerdetails->cus_mname,0,1)).'.';
						    $link->commit();
						    if($isRevalidateGC)
						    {
						    	$response['reval'] = 1;
						    }
						    else
						    {
						    	$response['reval'] = 0;
						    }
						    $response['st'] = 1;
						    $response['barcode'] = $gc;
						    $response['customer'] = strtoupper($customerdetails->cus_fname.' '.$mid_initial.' '.$customerdetails->cus_lname);
						    $response['date'] = $todays_date;
						    $response['time'] = $todays_time;
						    $response['storename'] = $storename;

						    $response['flashmsg'] = $flashmsg;
						    $response['msg'] = '<div class="verifygcbar">GC Barcode: <span class="verifyx">'.$gc.'</span></div>
						      <div class="verifygcdenom">Denomination: <span class="verifyx">'.$denom.'</span></div>';

						}
					}


				}
				// end is verified
				
			}

			// end verifiedGCDetails
		}
		echo json_encode($response);
	}
	elseif($action=='addsupplier')
	{
		$response['st'] = 0;
		$cname = $link->real_escape_string($_POST['cname']);
		$cperson = $link->real_escape_string($_POST['cperson']);
		$cnumber = $link->real_escape_string($_POST['cnumber']);
		$caddress = $link->real_escape_string($_POST['caddress']);
		$aname = $link->real_escape_string($_POST['aname']);
		
		$query_ins = $link->query(
			"INSERT INTO 
				supplier
			(
				gcs_companyname, 
				gcs_contactperson, 
				gcs_contactnumber, 
				gcs_address,
				gcs_accountname
			) 
			VALUES 
			(
				'$cname',
				'$cperson',
				'$cnumber',
				'$caddress',
				'$aname'
			)
		");

		if($query_ins)
		{
			$response['st'] = 1;
		} 
		else 
		{
			$response['msg'] = $link->error;
		}

		echo json_encode($response);

	}
	elseif ($action=='supplier-sup')
	{
		$id = $_POST['id'];

		$query = $link->query(
			"DELETE 
			FROM 
				`supplier` 
			WHERE 
				`gcs_id`= '$id'
		");

		if($query){
			echo 'success';
		} else {
			echo $link->error;
		}
	} 
	elseif ($action=='updatesupplier') 
	{
		$response['st'] = 0;
		$cid =  $link->real_escape_string($_POST['cid']);
		$cname =   $link->real_escape_string($_POST['cname']);
		$cperson =  $link->real_escape_string($_POST['cperson']);
		$cnumber =  $link->real_escape_string($_POST['cnumber']);
		$caddress =  $link->real_escape_string($_POST['caddress']);
		$aname = $link->real_escape_string($_POST['aname']);

		$query = $link->query(
			"UPDATE 
				supplier
			SET 
			
				gcs_companyname='$cname',
				gcs_contactperson='$cperson',
				gcs_contactnumber='$cnumber',
				gcs_address='$caddress',
				gcs_accountname='$aname' 

			WHERE 
				`gcs_id`='$cid'
		");

		if($query)
		{
			$response['st'] = 1;
		} 
		else 
		{
			$response['msg'] = $link->error;
		}

		echo json_encode($response);

	} elseif($action=='deletecustomer'){
		$id = $_POST['id'];

		$query = $link->query(
			"DELETE 
			FROM 
				`customers` 
			WHERE 
				`cus_id`= '$id'
		");

		if($query){
			echo 'success';
		} else {
			echo $link->error;
		}
	} 
	elseif($action=='updatecustomer')
	{
		$response['st'] = 0;
		$fname = $link->real_escape_string(trim(strtolower($_POST['fname'])));
		$lname = $link->real_escape_string(trim(strtolower($_POST['lname'])));
		$mname = $link->real_escape_string(trim(strtolower($_POST['mname'])));
		$cusid = $link->real_escape_string(trim($_POST['cusid']));
		if(isset($_POST['extname']))
		{
			$extname = $link->real_escape_string(trim(strtolower($_POST['extname'])));
		}
		else 
		{
			$extname = '';
		}

		$dob = !empty($_POST['dob']) ? _dateFormatoSql($_POST['dob']) : ''; 
		$dob = !empty($dob) ? "'$dob'" : "NULL";

		$sex = $link->real_escape_string(trim($_POST['sex']));
		$cstatus = $link->real_escape_string(trim($_POST['cstatus']));
		$valid = $link->real_escape_string(trim($_POST['valid']));
		$address = $link->real_escape_string(trim(strtolower($_POST['address'])));
		$mobnum = $link->real_escape_string(trim(strtolower($_POST['mobnum'])));

		if(!empty($fname)&&
			!empty($lname)&&
			!empty($cusid))
		{
			$userid = $_SESSION['gc_id'];
	        $store_id = getField($link,'store_assigned','users','user_id',$userid);

	        $query = $link->query(
	        	"UPDATE 
	        		customers 
	        	SET 
	        		cus_fname='$fname',
	        		cus_lname='$lname',
	        		cus_mname='$mname',
	        		cus_namext='$extname',
	        		cus_dob='$dob',
	        		cus_sex='$sex',
	        		cus_cstatus='$cstatus',
	        		cus_idnumber='$valid',
	        		cus_address='$address',
	        		cus_mobile='$mobnum',
	        		cus_store_updated='$store_id',
	        		cus_updated_at=NOW(),
	        		cus_updated_by='$userid' 
	        	WHERE 
					cus_id='$cusid'
	        ");

	        if($query)
	        {
	     		if($link->affected_rows > 0)
	     		{
	     			$response['st'] = 1;
	     		}   
	     		else
	     		{
	     			$response['msg'] = 'Nothing to change.';
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

		// $id = $_POST['id'];
		// $fname = $_POST['fname'];
		// $lname = $_POST['lname'];
		// $valid = $_POST['valid'];
		// $address = $_POST['address'];
		// $mobnum = $_POST['mobnum'];

		// $query = $link->query(
		// 	"UPDATE 
		// 		`customers` 
		// 	SET 
		// 		`cus_fname`='$fname',
		// 		`cus_lname`='$lname',
		// 		`cus_idnumber`='$valid',
		// 		`cus_address`='$address',
		// 		`cus_mobile`='$mobnum'
		// 	WHERE 
		// 		`cus_id` = '$id'
		// ");

		// if($query){
		// 	echo 'success';
		// } else {
		// 	echo $link->error;
		// }
	} 
	elseif ($action=='addnewuser') 
	{

		$uname = $link->real_escape_string($_POST['username']);
		$fname = $link->real_escape_string($_POST['firstname']);
		$lname = $link->real_escape_string($_POST['lastname']);
		$empid = $link->real_escape_string($_POST['empid']);
		$dept = $link->real_escape_string($_POST['department']);
		$store = $link->real_escape_string($_POST['assigned']);

		$pass = md5('GC2015');

		if($dept!='retailstore'){
			$store = '0';
		}

		$query = $link->query(
			"INSERT INTO 
				`users`
			(
				`user_id`, 
				`emp_id`, 
				`username`, 
				`password`, 
				`firstname`, 
				`lastname`, 
				`usertype`, 
				`user_status`, 
				`login`, 
				`store_assigned`, 
				`date_created`, 
				`date_updated`
			) 
			VALUES 
			(
					'',
					'$empid',
					'$uname',
					'$pass',
					'$fname',
					'$lname',					
					'$dept',
					'active',
					'no',
					'$store',
					NOW(),
					NOW()
			)
		");

		if($query){
			echo 'success';
		} else {
			echo $link->error;
		}
	} elseif ($action=='loadusers') {
	    $query = $link->query(
	        "SELECT 
	            `users`.`user_id`,
	            `users`.`username`,
	            `users`.`firstname`,
	            `users`.`lastname`,
	            `users`.`usertype`,
	            `users`.`user_status`,
	            `users`.`login`,
	            `users`.`emp_id`,
	            `users`.`date_created`,
	            `users`.`date_updated`,
	            `stores`.`store_name`
	            
	        FROM 
	            `users`
	        LEFT JOIN
	            `stores`
	        ON
	            `users`.`store_assigned` = `stores`.`store_id`
	        ORDER BY 
	            `user_id` 
	        DESC
	    ");

	    if($query){
	    	$n = $query->num_rows;
	    }

	    ?>
	       <?php if($n<0): ?>
	            <tr>
	                <td colspan="9">No user to display.</td>
	            </tr>
	        <?php else: ?>
	            <?php while($row = $query->fetch_object()): ?>
	                <tr> 
	                    <td><?= ucwords($row->username); ?></td>              
	                    <td><?= ucwords($row->firstname); ?></td>
	                    <td><?= ucwords($row->lastname); ?></td>               
	                    <td><?= ucwords($row->emp_id); ?></td>
	                    <td><?= ucwords($row->usertype); ?></td>
	                    <td><?= ucwords($row->user_status); ?></td>
	                    <td><?= ucwords($row->store_name); ?></td>
	                    <td><?= _dateFormat($row->date_created); ?></td>
	                    <td><?= _dateFormat($row->date_updated); ?></td>
	                    <td>
	                        <a href="<?php echo $row->user_id; ?>" class="cus-update" alt="update"><i class="fa fa-cogs"></i>
	                        </a>
	                        <a href="<?php echo $row->user_id; ?>" class="cus-delete" alt="update"><i class="fa fa-minus-square"></i>
	                        </a>
	                    </td>
	                </tr>
	            <?php endwhile; ?>
	        <?php endif; ?>
	    <?php		
	} elseif ($action=='searchcustomer') {
		$search = $_POST['search'];

		$query = $link->query(
			"SELECT 
				* 
			FROM 
				`customers`
			WHERE 
				`cus_fname` LIKE '%".$search."%' 
		");

		$n = $query->num_rows;		

		if($n>0)
		{
		?>			
			<table class="table">
				<thead>
					<tr>
						<th>Firstname</th>
						<th>Lastname</th>
						<th>Valid ID Number</th>
						<th>Mobile Number</th>
						<th>Address</th>						
					</tr>
				</thead>
				<tbody>
				<?php while ($row = $query->fetch_object()): ?>
					<tr cusid="<?php echo $row->cus_id; ?>" 
						cusfname="<?php echo $row->cus_fname; ?>"
						cuslname="<?php echo $row->cus_lname; ?>"
						cusidv="<?php echo $row->cus_idnumber; ?>"
						cusmnumber="<?php echo $row->cus_mobile; ?>"
						cusaddress="<?php echo $row->cus_address; ?>"
					>
						<td><?php echo $row->cus_fname; ?></td>
						<td><?php echo $row->cus_lname; ?></td>
						<td><?php echo $row->cus_idnumber; ?></td>
						<td><?php echo $row->cus_mobile; ?></td>
						<td><?php echo $row->cus_address; ?></td>

					</tr>
				<?php endwhile; ?>					
				</tbody>
			</table>		
		<?php
		
		} else {
			echo '';
		}
	}
	elseif($action=='budget_adjustment') 
	{
		$hasError = false;
		$response['st'] = 0;
		$amountAdj = $_POST['adj'];
		$adjtype = $_POST['adj_type'];
		$remark = $_POST['remarks'];
		$amountAdj = trim(str_replace(array(','), '',$amountAdj));
		$group = isset($_POST['groupma']) ? $_POST['groupma'] : 0;
		$typeid = $_POST['typeid'];

		$lnum = ledgerNumber($link);


		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif($adjtype=='neg')
		{
			$entry = 'bcredit_amt';
			$adj = 'negative';
			if($amountAdj > currentBudget($link))
			{
				$hasError = true;
			}
		} 
		else 
		{
			$entry = 'bdebit_amt';
			$adj = 'positive';
		}

		if($hasError)
		{
			$response['msg'] = 'Amount is greater than current budget.';
		}
		else 
		{
			$btype = 'BA';
			$link->autocommit(FALSE);

			$query = $link->query(
				"INSERT INTO 
					budget_adjustment
				(
					bud_adj_type, 
					bud_remark, 
					bud_by
				) 
				VALUES 
				(
					'$adj',
					'$remark',
					'".$_SESSION['gc_id']."'
				)");

			if($query)
			{
				$last_id = $link->insert_id;
				$query_ledger = $link->query(
					"INSERT INTO 
						ledger_budget
					(
						bledger_no, 
						bledger_datetime, 
						bledger_type, 
						bledger_group,
						$entry,
						bledger_trid 
					) 
					VALUES 
					(
						'$lnum',
						NOW(),
						'$btype',
						'$group',
						'$amountAdj',
						'$last_id'
					)
				");

				if($query_ledger)
				{
					$link->commit();
					$response['st'] = 1;
				}
				else 
				{
					$response['msg'] = $link->error;
				}

			} 
			else 
			{
				return $link->error;
			}		
		}



		echo json_encode($response);
	}
	elseif($action=='gcAdjustmentSelectDenom')
	{
		$denId = $_POST['denId'];
		if($denId!=''){

			if(checkIfTableNotEmpty($link,'gc'))
			{

				$denom = getField($link,'denomination','denomination','denom_id',$denId);
				$lastbarcode = getGCBarcodeLastNumber($link,$denId);
				$n = countGCForValidation($link,$denId);
				if($denom!=''){
				?>
		          <div class="box box-bot">
		            <div class="box-header"><h4><i class="fa fa-inbox"></i> Barcode Details</h4></div>
		            <div class="box-content">
		              <div class="col-sm-12">
		              	<div class="form-horizontal">
							<!-- begin form-group -->
							<div class="form-group">
							<label class="col-sm-5 control-label">GC Last Number: </label>
								<div class="col-sm-7">
									<input class="form form-control" value="<?php echo $lastbarcode; ?>" readonly="readonly">
								</div>
							</div>
							<!-- end form-group -->
							<div class="form-group">
							<label class="col-sm-5 control-label">GC for Validation: </label>
								<div class="col-sm-7">
									<input type="hidden" value="<?php echo $n; ?>" id="_gcforvhid">
									<input class="form form-control" id="_gcforv" value="<?php echo $n; ?>" readonly="readonly">
								</div>
							</div>
							<!-- end form-group -->
		              	</div>
		              	<button class="btn btn-default pull-right" id="_viewgc" denomid="<?php echo $denId; ?>">View GC </button>
		              </div>
		          </div>
				<?php
				} 
				else 
				{
					echo '';
				}
			}
			else 
			{
				echo '';
			}
		}
		else 
		{
			echo '';
		}

	} 
	elseif($action=='adjustGCEntry')
	{
		$remarks = $_POST['remarks'];
		$adj = $_POST['adj_type'];
		$qty1 = str_replace(',','',$_POST['qty1']);
		$qty2 = str_replace(',','',$_POST['qty2']);
		$qty3 = str_replace(',','',$_POST['qty3']);
		$qty4 = str_replace(',','',$_POST['qty4']);
		$qty5 = str_replace(',','',$_POST['qty5']);
		$qty6 = str_replace(',','',$_POST['qty6']);

		$s1 = $qty1 * 100;
		$s2 = $qty2 * 200;
		$s3 = $qty3 * 500;
		$s4 = $qty4 * 1000;
		$s5 = $qty5 * 2000;
		$s6 = $qty6 * 5000;

		$total = $s1 + $s2 + $s3 + $s4 + $s5 + $s6;

		$link->autocommit(FALSE);

		$last_insert = gcAdjustmentInsert($link,$adj,$remarks);
				
		$ledgertype = 'AG';



		if($adj=='n')
		{
			$db_field = 'bdebit_amt';
			ledgerInsert($link,$last_insert,$ledgertype,$db_field,$total);
			if($qty6!='0')
			{
				$den_id = 6;
				$data = getDataForAdjustment($link,'gc',$den_id,$qty6);
				foreach ($data as $key) {
					$barcode = $key['barcode_no'];				
					adjInsertDeleteBarcode($link,$barcode,$den_id,$last_insert);					
				}
			}	
			if($qty5!='0')
			{
				$den_id = 5;
				$data = getDataForAdjustment($link,'gc',$den_id,$qty5);
				foreach ($data as $key) {
					$barcode = $key['barcode_no'];				
					adjInsertDeleteBarcode($link,$barcode,$den_id,$last_insert);					
				}
			}
			if($qty4!='0')
			{
				$den_id = 4;
				$data = getDataForAdjustment($link,'gc',$den_id,$qty4);
				foreach ($data as $key) {
					$barcode = $key['barcode_no'];				
					adjInsertDeleteBarcode($link,$barcode,$den_id,$last_insert);					
				}
			}
			if($qty3!='0')
			{
				$den_id = 3;
				$data = getDataForAdjustment($link,'gc',$den_id,$qty3);
				foreach ($data as $key) {
					$barcode = $key['barcode_no'];				
					adjInsertDeleteBarcode($link,$barcode,$den_id,$last_insert);					
				}
			}
			if($qty2!='0')
			{
				$den_id = 2;
				$data = getDataForAdjustment($link,'gc',$den_id,$qty2);
				foreach ($data as $key) {
					$barcode = $key['barcode_no'];				
					adjInsertDeleteBarcode($link,$barcode,$den_id,$last_insert);					
				}
			}
			if($qty1!='0')
			{
				$den_id = 1;
				$data = getDataForAdjustment($link,'gc',$den_id,$qty1);
				foreach ($data as $key) {
					$barcode = $key['barcode_no'];				
					adjInsertDeleteBarcode($link,$barcode,$den_id,$last_insert);					
				}
			}		
		}
		else
		{
			$db_field = 'bcredit_amt';
			ledgerInsert($link,$last_insert,$ledgertype,$db_field,$total);
			if($qty6!='0')
			{
				$denom_id = 6;
				echo adjPositive($link,$denom_id,$last_insert,$qty6);
			}

			if($qty5!='0')
			{
				$denom_id = 5;
				echo adjPositive($link,$denom_id,$last_insert,$qty5);
			}

			if($qty4!='0')
			{
				$denom_id = 4;
				echo adjPositive($link,$denom_id,$last_insert,$qty4);				
			}

			if($qty3!='0')
			{
				$denom_id = 3;
				echo adjPositive($link,$denom_id,$last_insert,$qty3);				
			}

			if($qty2!='0')
			{
				$denom_id = 2;
				echo adjPositive($link,$denom_id,$last_insert,$qty2);				
			}

			if($qty1!='0')
			{
				$denom_id = 1;
				echo adjPositive($link,$denom_id,$last_insert,$qty1);
			}
		}

		$link->commit();

		echo 'success';

	} 
	elseif($action=='cancelProductionReq')
	{
		$prodId = $_POST['prodId'];
		$link->autocommit(FALSE);
		$query_update = $link->query(
			"UPDATE 
				`production_request` 
			SET 
				`pe_status`='2' 
			WHERE 
				`pe_id`='$prodId'
		");

		if($query_update)
		{
			$query_insert = $link->query(
				"INSERT INTO 
					`cancelled_production_request`
				(
					`cpr_pro_id`, 
					`cpr_at`, 
					`cpr_by`
				) 
				VALUES 
				(
					'$prodId',
					NOW(),
					'".$_SESSION['gc_id']."'
				)
			");

			if($query_insert)
			{
				$link->commit();
				echo 'success';
			}
			else 
			{
				echo $link->error;
			}
		}
		else
		{
			echo $link->error;
		}
	} 
	elseif($action=='storegcreq')
	{
		$reqId = $_POST['reqId'];

		$link->autocommit(FALSE);

		$query_update = $link->query(
			"UPDATE 
				`store_gcrequest`
			SET 
				`sgc_status`='2'
			WHERE 
				`sgc_id`='$reqId'
		");

		if($query_update)
		{
			$query_insert = $link->query(
				"INSERT INTO 
					`cancelled_store_gcrequest`
				(
					`csgr_gc_id`, 
					`csgr_at`, 
					`csgr_by`) 
				VALUES 
				(
					'$reqId',
					NOW(),
					'".$_SESSION['gc_id']."'
				);
			");

			if($query_insert)
			{
				$link->commit();
				echo 'success';
			}
			else 
			{
				echo $link->error;
			}
		}
		else 
		{
			echo $link->error;
		}

	} 
	elseif($action=='allocateadj')
	{
		$allEmpty = true;
		$hasError = false;
		$response['st'] = 0;
		$store = $_POST['storeallo'];
		$adj = $_POST['adjtype'];
		$remarks = $_POST['remarks'];
		$gctype = $_POST['gctype'];

		$denom = getDenomination($link);

		foreach ($denom as $d) {
			 ${'qty_'.$d->denom_id} = str_replace(',','',$_POST['qty_'.$d->denom_id]);
			 if(!empty(${'qty_'.$d->denom_id}))
			 {
			 	$allEmpty = false;
			 }
		}

		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif(!empty($store))
		{
			if(!$allEmpty)
			{
				$link->autocommit(FALSE);
				$query = $link->query(
					"INSERT INTO 
						allocation_adjustment
					(
						aadj_type,
						aadj_by,
						aadj_datetime,
						aadj_remark,
						aadj_loc,
						aadj_gctype
					) 
					VALUES (
						'$adj',
						'".$_SESSION['gc_id']."',
						NOW(),
						'$remarks',
						'$store',
						'$gctype'
				)");

				if($query)
				{
					$last_id = $link->insert_id;
					if($adj=='n')
					{
						foreach ($denom as $d) 
						{
							if(!empty(${'qty_'.$d->denom_id}))
							{
								if(!allocateGCAdjNeg($link,${'qty_'.$d->denom_id},$d->denom_id,$store,$last_id,$gctype))
								{
									$hasError = true;
									break;
								}
							}
						}
					}
					else 
					{
						foreach ($denom as $d) 
						{
							if(!empty(${'qty_'.$d->denom_id}))
							{
								if(!allocateGCAdjPos($link,$gctype,${'qty_'.$d->denom_id},$d->denom_id,$store,$last_id))
								{
									$hasError = true;
									break;
								}
							}		
						}
					}

					if(!$hasError)
					{
						$link->commit();
						$response['st'] = 1;
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
				$response['msg'] = 'Please input at least one quantity field.';
			}
		}
		else 
		{
			$response['msg'] = 'Please select a store.';
		}

		// $qty_1 = str_replace(',','',$_POST['qty_1']);
		// $qty_2 = str_replace(',','',$_POST['qty_2']);
		// $qty_3 = str_replace(',','',$_POST['qty_3']);
		// $qty_4 = str_replace(',','',$_POST['qty_4']);
		// $qty_5 = str_replace(',','',$_POST['qty_5']);
		// $qty_6 = str_replace(',','',$_POST['qty_6']);

		// if(!empty($store))
		// {				
		// 	if( !empty($qty_1)||
		// 		!empty($qty_2)||
		// 		!empty($qty_3)||
		// 		!empty($qty_4)||
		// 		!empty($qty_5)||
		// 		!empty($qty_6)
		// 	) 
		// 	{

		// 		$query = $link->query(
		// 			"INSERT INTO 
		// 				`allocation_adjustment`
		// 			(
		// 				`aadj_type`,
		// 				`aadj_by`,
		// 				`aadj_datetime`,
		// 				`aadj_remark`
		// 			) 
		// 			VALUES (
		// 				'$adj',
		// 				'".$_SESSION['gc_id']."',
		// 				NOW(),
		// 				'$remarks'
		// 			)");

		// 		$last_id = $link->insert_id;

		// 		if($adj=='n')
		// 		{
		// 			$link->autocommit(FALSE);
		// 			if(!empty($qty_1)){
		// 				allocateGCAdjNeg($link,$qty_1,'1',$store,$last_id);
		// 			}

		// 			if(!empty($qty_2)){
		// 				allocateGCAdjNeg($link,$qty_2,'2',$store,$last_id);
		// 			}

		// 			if(!empty($qty_3)){
		// 				allocateGCAdjNeg($link,$qty_3,'3',$store,$last_id);
		// 			}

		// 			if(!empty($qty_4)){
		// 				allocateGCAdjNeg($link,$qty_4,'4',$store,$last_id);
		// 			}

		// 			if(!empty($qty_5)){
		// 				allocateGCAdjNeg($link,$qty_5,'5',$store,$last_id);
		// 			}

		// 			if(!empty($qty_6)){
		// 				allocateGCAdjNeg($link,$qty_6,'6',$store,$last_id);
		// 			}
		// 			$link->commit();
		// 			echo "success";
		// 		}
		// 		else
		// 		{
		// 			$gctype = $_POST['gctype'];
		// 			$link->autocommit(FALSE);
		// 			if(!empty($qty_1)){						
		// 				allocateGCAdjPos($link,$gctype,$qty_1,'1',$store,$last_id);
		// 			}

		// 			if(!empty($qty_2)){						
		// 				allocateGCAdjPos($link,$gctype,$qty_2,'2',$store,$last_id);
		// 			}

		// 			if(!empty($qty_3)){						
		// 				allocateGCAdjPos($link,$gctype,$qty_3,'3',$store,$last_id);
		// 			}

		// 			if(!empty($qty_4)){						
		// 				allocateGCAdjPos($link,$gctype,$qty_4,'4',$store,$last_id);
		// 			}

		// 			if(!empty($qty_5)){						
		// 				allocateGCAdjPos($link,$gctype,$qty_5,'5',$store,$last_id);
		// 			}

		// 			if(!empty($qty_6)){						
		// 				allocateGCAdjPos($link,$gctype,$qty_6,'6',$store,$last_id);
		// 			}
		// 			$link->commit();
		// 			echo 'success';
		// 		}
		// 	} 
		// 	else 
		// 	{
		// 		echo 'Please input at least one quantity field.';
		// 	}
		// } 
		// else 
		// {
		// 	echo 'Please select a store.';
		// }
		echo json_encode($response);

	}
	elseif($action=='validatedGCList')
	{
		?>
  	<div class="box">
	  	<div class="box-header"><h4><i class="fa fa-inbox"></i> Validated GC for Allocation</h4></div>
		    <div class="box-content form-container">
		      <input type="hidden" id="n1"  value="<?php echo countGC($link,'gc','gc_validated','*','gc_allocated','','denom_id','1'); ?>"/>
		      <input type="hidden" id="n2"  value="<?php echo countGC($link,'gc','gc_validated','*','gc_allocated','','denom_id','2'); ?>"/>
		      <input type="hidden" id="n3"  value="<?php echo countGC($link,'gc','gc_validated','*','gc_allocated','','denom_id','3'); ?>"/>
		      <input type="hidden" id="n4"  value="<?php echo countGC($link,'gc','gc_validated','*','gc_allocated','','denom_id','4'); ?>"/>
		      <input type="hidden" id="n5"  value="<?php echo countGC($link,'gc','gc_validated','*','gc_allocated','','denom_id','5'); ?>"/>
		      <input type="hidden" id="n6"  value="<?php echo countGC($link,'gc','gc_validated','*','gc_allocated','','denom_id','6'); ?>"/>
		          <ul class="list-group">                            
		              <li class="list-group-item"><span class="badge" id="n1"><?php echo countGC($link,'gc','gc_validated','*','gc_allocated','','denom_id','1')?></span> &#8369 100.00</li>          
		              <li class="list-group-item"><span class="badge" id="n2"><?php echo countGC($link,'gc','gc_validated','*','gc_allocated','','denom_id','2')?></span> &#8369 200.00</li>          
		              <li class="list-group-item"><span class="badge" id="n3"><?php echo countGC($link,'gc','gc_validated','*','gc_allocated','','denom_id','3')?></span> &#8369 500.00</li>          
		              <li class="list-group-item"><span class="badge" id="n4"><?php echo countGC($link,'gc','gc_validated','*','gc_allocated','','denom_id','4')?></span> &#8369 1000.00</li>          
		              <li class="list-group-item"><span class="badge" id="n5"><?php echo countGC($link,'gc','gc_validated','*','gc_allocated','','denom_id','5')?></span>&#8369 2000.00</li>          
		              <li class="list-group-item"><span class="badge" id="n6"><?php echo countGC($link,'gc','gc_validated','*','gc_allocated','','denom_id','6')?></span>&#8369 5000.00</li>
		          </ul> 
		    </div>
	 	</div>
	</div>

		<?php
	} 
	elseif ($action=='addstoreuser') 
	{
		$response['st'] = 0;
		$storeid = $_POST['uassigned'];
		$uname = $_POST['uname'];
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$empid = $_POST['eid'];
		$utype = $_POST['utype'];
		$pass = $_POST['password'];

		if(!empty($storeid)&&
			!empty($uname)&&
			!empty($fname)&&
			!empty($lname)&&
			!empty($empid)&&
			!empty($utype)){
				$password = md5($pass);

				$managerkey = $password;

				$query_insert = $link->query(
					"INSERT INTO 
						`store_staff`
					(						
						`ss_firstname`, 
						`ss_lastname`, 
						`ss_status`, 
						`ss_username`, 
						`ss_password`, 
						`ss_idnumber`, 
						`ss_usertype`, 
						`ss_store`, 
						`ss_manager_key`, 
						`ss_date_created`,
						`ss_by`
					) 
					VALUES 
					(
						'$fname',
						'$lname',
						'active',
						'$uname',
						'$password',
						'$empid',
						'$utype',
						'$storeid',
						'$managerkey',
						NOW(),
						'".$_SESSION['gc_id']."'						
					)
				");

				if($query_insert)
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
			$response['msg'] = 'Please fill in all fields.';
		}
		echo json_encode($response);
	} 
	elseif ($action == 'updatestoreuser') 
	{
		$response['st'] = 0;
		$uid = validateData($_POST['userid']);
		$uname = validateData($_POST['uname']);
		$fname = validateData($_POST['fname']);
		$lname = validateData($_POST['lname']);
		$empid = validateData($_POST['eid']);
		$utype = validateData($_POST['utype']);
		$ustore = validateData($_POST['uassigned']);

		if(!empty($uid)&&
			!empty($uname)&&
			!empty($fname)&&
			!empty($lname)&&
			!empty($empid)&&
			!empty($utype))
		{
			if(!usernameExistOnUpdate($link,$uid,$uname))
			{
				$query_update = $link->query(
					"UPDATE 
						store_staff 
					SET 
						ss_firstname='$fname',
						ss_lastname='$lname',
						ss_username='$uname',
						ss_idnumber='$empid',
						ss_usertype='$utype',
						ss_store='$ustore'						
					WHERE 
						ss_id = '$uid'
				");

				if($query_update)
				{
					if($link->affected_rows > 0)
					{
						$response['st'] = 1;
					}
					else 
					{
						$response['msg'] = 'Nothing to update.';
					}
				}
				else
				{
					$response['msg'] = $link->error;
				}
			}
			else
			{
				$response['msg'] = 'Username '.$uname.' already exist.';
			}
		}
		else
		{
			$response['msg'] = 'Please fill in all fields.';
		}
		echo json_encode($response);
	} 
	elseif ($action=='receivegc') 
	{
		$proid = $_POST['prod_id'];
		$remarks = $_POST['remarks'];
		$checkedby = $_POST['checked'];

		$link->autocommit(FALSE);

		$query = $link->query(
			"INSERT INTO 
				`custodian_srr`
			(				
				`csrr_pro_id`, 
				`csrr_remarks`, 
				`csrr_datetime`, 
				`csrr_checked_by`, 
				`csrr_prepared_by`) 
			VALUES 
			(				
				'$proid',
				'$remarks',
				NOW(),
				'$checkedby',
				'".$_SESSION['gc_id']."'
			)"
		);

		if($query)
		{
			$query_update = $link->query(
				"UPDATE 
					`approved_production_request` 
				SET 
					`ape_received`='*' 
				WHERE 
					`ape_pro_request_id`='$proid'
			");

			if($query_update)
			{
				$link->commit();
				echo 'success';
			}
			else 
			{
				echo $link->error;
			}
		} 
		else 
		{
			echo $link->error;
		}
	}
	elseif ($action=='updateusers') 
	{

        $usergroup = "";
        $uroles = "";
        $response['st'] = false;
        $user_id = $_POST['uid'];
        $username = $_POST['uname'];
        $firstname = $_POST['fname'];
        $lastname = $_POST['lname'];
        $emp_id = $_POST['eid'];
        $usertype = $_POST['utype'];
        $password = getAppPasswordmd5($link);
        $status = $_POST['ustat'];
        $usergroup = 0;
        if($usertype!='' && $usertype==7)
            $uassigned = $_POST['uassigned'];
        else 
            $uassigned = '0';

        if($usertype!='' && $usertype !=1)
            $uroles = $_POST['uroles'];
        else
            $uroles = 1;    

        if($usertype!='' && $usertype ==8)
            $usergroup = $_POST['ugroupretail'];
        else 
            $usergroup = "";

        if(!validate_alphanumeric_underscore($username))
        {
        	$response['msg'] = 'Username only accepts alphanumeric and underscore.';
        }
        else 
        {
            if(checkusernameifExists($link,$user_id,$username))
            {
            	$response['msg'] = $username.' already exist.';
            }
            else 
            {
                $query = $link->query(
                    "UPDATE 
                        users
                    SET 
                        emp_id='$emp_id',
                        username='$username',
                        firstname='$firstname',
                        lastname='$lastname',
                        usertype='$usertype',
                        user_status='$status',
                        store_assigned='$uassigned',
                        user_role='$uroles',
                        usergroup='$usergroup'
                    WHERE
                        user_id='$user_id'
                ");

                if(!$query)
                {
                	$response['msg'] = $link->error;
                } 
                else 
                {            
                	if($link->affected_rows > 0)
                	{
                    	$response['st'] = 1;
                    }
                    else 
                    {
                    	$response['msg'] = 'Nothing to update.';
                    }
                }
            }            
        }
		echo json_encode($response);
	} 
	elseif ($action=='addnewusers') 
	{
		$usergroup = "";
		$response['st'] = false;
		$username = $_POST['uname'];
		$firstname = $_POST['fname'];
		$lastname = $_POST['lname'];
		$emp_id = $_POST['eid'];
		$usertype = $_POST['utype'];
		$password = getAppPasswordmd5($link);
		$usergroup = 0;
        if(($usertype!='' && $usertype==7) || $usertype!='' && $usertype==14)
        {
            $uassigned = $_POST['uassigned'];
        }
        else
        { 
			$uassigned = '0';
        }
		//if($use =)

		if($usertype!='' && $usertype !=1)
			$uroles = $_POST['uroles'];
		else
			$uroles = 1;	

		if($usertype!='' && $usertype ==8)
			$usergroup = $_POST['ugroupretail'];
		else 
			$usergroup = "";

		$promotag = 0;

		if($usertype==6)
		{
			$promotag = 1;
		}

		if($usertype==8)
		{
			if($usergroup==1)
			{
				$promotag = 2;
			}
			elseif ($usergroup==2) 
			{
				$promotag = 3;
			}
			elseif ($usergroup==3) 
			{
				$promotag = 4;
			}
		}

		if(!empty($username)&&
			!empty($firstname)&&
			!empty($lastname)&&
			!empty($emp_id))
		{
			$query_check = $link->query(
				"SELECT 
					username 
				FROM 
					users
				WHERE 
					username='$username'
			");

			if(($query_check->num_rows)>0)
			{
				$response['msg'] = $username.' already exist.';
			} 
			else
			{
                $link->autocommit(FALSE);
				$query = $link->query(
					"INSERT INTO 
						users
					(
						emp_id, 
						username, 
						password, 
						firstname, 
						lastname, 
						usertype, 
						user_status, 
						login, 
						store_assigned, 
						date_created,
						user_role,
						user_addby,
						usergroup,
						promo_tag
					) 
					VALUES 
					(
						'$emp_id',
						'$username',
						'$password',
						'$firstname',
						'$lastname',
						'$usertype',
						'active',
						'no',
						'$uassigned',
						NOW(),
						'$uroles',
						'".$_SESSION['gc_id']."',
						'$usergroup',
						'$promotag'
					)
				");

				if($query)
				{                    
                    $last_insert = $link->insert_id;
                    if($usertype==7)
                    {
                        //check for local server
        
                        //$_SESSION['gc_store']        
                        $table = 'store_local_server';
                        $select = 'stlocser_ip,stlocser_username,stlocser_password,stlocser_db';
                        $where = "stlocser_storeid='".$uassigned."'";
                        $join = '';
                        $limit = '';
                        $lserver = getSelectedData($link,$table,$select,$where,$join,$limit);
                        if(count($lserver)>0)
                        {
                            //test connect
                            $lsercon = @localserver_connect($lserver->stlocser_ip,$lserver->stlocser_username,$lserver->stlocser_password,$lserver->stlocser_db);

                            if(is_array($lsercon))
                            {
                                $query_local = $lsercon[0]->query(
                                    "INSERT INTO 
                                        users
                                    (
                                        user_id,
                                        emp_id, 
                                        username, 
                                        password, 
                                        firstname, 
                                        lastname, 
                                        usertype, 
                                        user_status, 
                                        login, 
                                        store_assigned, 
                                        date_created,
                                        user_role,
                                        user_addby,
                                        usergroup,
                                        promo_tag
                                    ) 
                                    VALUES 
                                    (
                                        '$last_insert',
                                        '$emp_id',
                                        '$username',
                                        '$password',
                                        '$firstname',
                                        '$lastname',
                                        '$usertype',
                                        'active',
                                        'no',
                                        '$uassigned',
                                        NOW(),
                                        '$uroles',
                                        '".$_SESSION['gc_id']."',
                                        '$usergroup',
                                        '$promotag'
                                    )
                                ");

                                if($query_local)
                                {
                                    $link->commit();
                                    $response['st'] = true;
                                }
                            }
                            else 
                            {
                                $response['msg'] = "Cant connect to local server.";
                            }

                        }
                        else
                        {
                            $response['st'] = true;
                            $link->commit();
                        }
                    }
                    else 
                    {
                        $link->commit();
                        $response['st'] = true;
                    }
					
				}
				else 
				{
					$response['msg'] = $link->error;					
				}
			}
		}
		else 
		{
			$response['msg'] = 'Please fill up forms.';
		}

		echo json_encode($response);

	} 
	elseif ($action=='checkuserifexist') 
	{
		$username = $_POST['username'];	
		$query = $link->query(
			"SELECT 
				`username` 
			FROM 
				`users`
			WHERE 
				`username`='$username'
		");
		if(($query->num_rows)>0)
		{
			echo $username.' already exist.';
		}			
	} elseif ($action=='getUsername') {
		$id = $_POST['id'];
		$query = $link->query(
			"SELECT 
				`username`
			FROM
				`users`
			WHERE 
				`user_id`='$id'
		");

		if($query)
		{
			$row = $query->fetch_object();
			echo $row->username;
		}
		else 
		{
			echo $link->error;
		}
	} elseif ($action=='resetPassword') {
		$id = $_POST['id'];
		$newpassword = getAppPasswordmd5($link);

		$query = $link->query(
			"UPDATE 
				`users` 
			SET 
				`password`='$newpassword' 
			WHERE 
				`user_id`='$id'
		");

		if($query)
		{
			echo 'success';
		}
		else 
		{
			echo $link->error;
		}		
	} elseif ($action=='manageStatus') {
		$id = $_POST['id'];
		$status = $_POST['status'];
		if($status==1)
		{
			$status = 'inactive';
		}
		else 
		{
			$status = 'active';
		}

		$query = $link->query(
			"UPDATE 
				`users` 
			SET 
				`user_status`='$status' 
			WHERE 
				`user_id`='$id'
		");

		if($query)
		{
			echo 'success';
		}
		else 
		{
			echo $link->error;
		}
	} 
	elseif ($action=='gcSalesReportMarketing') 
	{
		$store = $_POST['store'];
		$denom = $_POST['denom'];
		$start = $_POST['datestart'];
		$end = $_POST['dateend'];
		
	} 
	elseif ($action=='receivedGCByStore') 
	{
		$id = $_POST['id'];

		$link->autocommit(FALSE);

		insertStoreLedger($link,$id,1);

		$update_rec = $link->query(
			"UPDATE 
				`store_gcrequest` 
			SET 
				`sgc_rec`='*' 
			WHERE 
				`sgc_id`='$id'
		");

		if($update_rec)
		{
			$insert_data = $link->query(
				"INSERT INTO 
					`store_received`
				(					
					`srec_request_id`, 
					`srec_at`, 
					`srec_by`
				) 
				VALUES 
				(					
					'$id',
					NOW(),
					'".$_SESSION['gc_id']."'
				)
			");

			if($insert_data)
			{
				$link->commit();
				echo 'success';
			}
		}

	} 
	elseif ($action=='podata') 
	{
		$hasfile = true;
		$hasdenomqty = false;
		if($_FILES['formData']['error'][0]==4){
			$hasfile = false;
		}		
		$ereqnum = $_POST['ereqnum'];
		$reqid = $_POST['reqid'];

		if($hasfile)
		{
			$allowedExts = array("txt");
			$temp = explode(".", $_FILES["formData"]["name"]);
			$extension = end($temp);
			$flag = 0;
			if ((($_FILES["formData"]["type"] == "text/plain")
							&& in_array($extension, $allowedExts)))
				{
					$arr_f = [];					
					$r_f = fopen($_FILES['formData']['tmp_name'],'r');
						while(!feof($r_f)) 
						{							
							$arr_f[] = fgets($r_f);
						}
					fclose($r_f);
					$arr_size = count($arr_f);
					if(count($arr_f)>0)
					{
						for($x=0;$x<$arr_size;$x++)
						{
							if(trim($arr_f[$x])!='')
							{
								if($x==0)
								{
									$title = trim($arr_f[$x]);

									if($title==trim("FAD Purchase Order Details"))
									{
										$flag = 1;
									}
									else 
									{
										$response['status'] = 0;
										$response['error'] = 'Not a valid Text file.';
										break;
									}
									// echo $arr_f[$x];
								}

								if($flag && $x>0)
								{					
												
									$c = explode("|",$arr_f[$x]);
									
									if(trim($c[0])=='GC E-REQUISION NO')
									{
										if(trim($c[1])!=$ereqnum)
										{
											$response['status'] = 0;
											$response['error'] = 'Text file has different Requisition Number.';
											break;
										}

									}

									if(trim($c[0])=='Receiving No')	
									{
										$response['fadrec'] =  $c[1];																	
									}

									if(trim($c[0])=='Transaction Date')	
									{
										$response['trandate'] =  $c[1];																
									}	

									if(trim($c[0])=='Reference No')	
									{
										$response['refno'] =  $c[1];																
									}

									if(trim($c[0])=='Purchase Order No')	
									{
										$response['purono'] =  $c[1];																
									}

									if(trim($c[0])=='Purchase Date')	
									{
										$response['purdate'] =  $c[1];																
									}

									if(trim($c[0])=='Reference PO No')	
									{
										$response['refpono'] =  $c[1];																
									}

									if(trim($c[0])=='Payment Terms')	
									{
										$response['payterms'] =  $c[1];																
									}

									if(trim($c[0])=='Location Code')	
									{
										$response['locode'] =  $c[1];																
									}

									if(trim($c[0])=='Department Code')	
									{
										$response['deptcode'] =  $c[1];																
									}

									if(trim($c[0])=='Supplier Name')	
									{
										$response['supname'] =  $c[1];																
									}
									
									if(trim($c[0])=='Mode of Payment')	
									{
										$response['modpay'] =  $c[1];																
									}

									if(trim($c[0])=='Remarks')	
									{
										$response['remarks'] =  $c[1];																
									}

									if(trim($c[0])=='Prepared By')	
									{
										$response['prepby'] =  $c[1];																
									}

									if(trim($c[0])=='Checked By')	
									{
										$response['checkby'] =  $c[1];																
									}

									if(trim($c[0])=='SRR Type')	
									{
										$response['srrtype'] =  $c[1];																
									}

									// $response['den1'] = 0;
									// $response['den2'] = 0;
									// $response['den3'] = 0;
									// $response['den4'] = 0;
									// $response['den5'] = 0;
									// $response['den6'] = 0;
									$table = 'denomination';
									$select = 'denom_id';
									if(trim($c[0])==='00002000')	
									{
										if(trim($c[1])>0)
										{
											$where = 'denom_fad_item_number='.$c[0];
											$gcid = getSelectedData($link,$table,$select,$where,'','');
											$denom = $gcid->denom_id;
											// $denom = getDenomIdByDenomination($link,$c[0]);
											if((int)getProdReqRemainGC($link,$denom,$reqid)>=(int)$c[1])
											{
												$response['den1'] =  $c[1];
											} 
											else
											{
												$response['status'] = 0;
												$response['error'] = 'Remaining GC is lesser than qty received!';
												break;
											}
										}
										else 
										{
											$response['den1'] =  $c[1];
										}
										$hasdenomqty= true;
									}

									if(trim($c[0])=='00002001')	
									{
										if(trim($c[1])>0)
										{
											$where = 'denom_fad_item_number='.$c[0];
											$gcid = getSelectedData($link,$table,$select,$where,'','');
											$denom = $gcid->denom_id;
											// $denom = getDenomIdByDenomination($link,$c[0]);
											if((int)getProdReqRemainGC($link,$denom,$reqid)>=(int)$c[1])
											{
												$response['den2'] =  $c[1];
											} 
											else
											{
												$response['status'] = 0;
												$response['error'] = 'Remaining GC is lesser than qty received!';												
												break;
											}
										}
										else 
										{
											$response['den2'] =  $c[1];
										}	
										$hasdenomqty = true;											
									}

									if(trim($c[0])=='00002002')	
									{
										if(trim($c[1])>0)
										{
											$where = 'denom_fad_item_number='.$c[0];
											$gcid = getSelectedData($link,$table,$select,$where,'','');
											$denom = $gcid->denom_id;
											// $denom = getDenomIdByDenomination($link,$c[0]);
											if((int)getProdReqRemainGC($link,$denom,$reqid)>=(int)$c[1])
											{
												$response['den3'] =  $c[1];
											} 
											else
											{
												$response['status'] = 0;
												$response['error'] = 'Remaining GC is lesser than qty received!';
												break;
											}
										}
										else
										{
											$response['den3'] =  $c[1];
										}		
										$hasdenomqty = true;									
									}

									if(trim($c[0])=='00002003')	
									{
										if(trim($c[1])>0)
										{
											$where = 'denom_fad_item_number='.$c[0];
											$gcid = getSelectedData($link,$table,$select,$where,'','');
											$denom = $gcid->denom_id;
											// $denom = getDenomIdByDenomination($link,$c[0]);
											if((int)getProdReqRemainGC($link,$denom,$reqid)>=(int)$c[1])
											{
												$response['den4'] =  $c[1];
											} 
											else
											{
												$response['status'] = 0;
												$response['error'] = 'Remaining GC is lesser than qty received!';
												break;
											}
										}
										else 
										{
											$response['den4'] =  $c[1];
										}	
										$hasdenomqty = true;												
									}

									if(trim($c[0])=='00002004')	
									{
										if(trim($c[1])>0)
										{
											$where = 'denom_fad_item_number='.$c[0];
											$gcid = getSelectedData($link,$table,$select,$where,'','');
											$denom = $gcid->denom_id;
											// $denom = getDenomIdByDenomination($link,$c[0]);
											if((int)getProdReqRemainGC($link,$denom,$reqid)>=(int)$c[1])
											{
												$response['den5'] =  $c[1];
											} 
											else
											{
												$response['status'] = 0;
												$response['error'] = 'Remaining GC is lesser than qty received!';
												break;
											}
										}
										else 
										{
											$response['den5'] =  $c[1];										
										}
										$hasdenomqty = true;
									}

									if(trim($c[0])=='00002005')	
									{
										if(trim($c[1])>0)
										{
											$where = 'denom_fad_item_number='.$c[0];
											$gcid = getSelectedData($link,$table,$select,$where,'','');
											$denom = $gcid->denom_id;
											// $denom = getDenomIdByDenomination($link,$c[0]);
											if((int)getProdReqRemainGC($link,$denom,$reqid)>=(int)$c[1])
											{
												$response['den6'] =  $c[1];
											} 
											else
											{
												$response['status'] = 0;
												$response['error'] = 'Remaining GC is lesser than qty received!';
												break;
											}
										}
										else
										{
											$response['den6'] =  $c[1];
										}	
										$hasdenomqty = true;														
									}

									$response['status'] = 1;
								}								
								
							}
						}	
						// print_r($arr_f);
					}
					else 
					{
						$response['status'] = 0;
						$response['error'] = 'Textfile has no data!';
					}
				}
		}
		else
		{
			$response['status'] = 0;
			$response['error'] = 'Please Choose Textfile / Please upload textfile format.';			
		}
		echo json_encode($response);

	} 
	elseif($action=='validategccustodian')
	{
		$barcode = $_POST['gcbarcode'];
		$recnum = $_POST['recnum'];
		$ereq = $_POST['ereq'];

		$flag = 0;

		//$value = str_replace(',', '', $value);

		$link->autocommit(FALSE);

		//check first if barcode exist

		$query = $link->query(
			"SELECT 
				barcode_no 
			FROM 
				gc 
			WHERE 
				barcode_no='$barcode'
		"); 



		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif($query)
		{

			if($query->num_rows>0)
			{
				//get denom

				$denom_id = getdenomid($link,$barcode);

				foreach ($_POST as $key => $value) 
				{
					if (strpos($key, 'den') !== false)
					{
						$qty = $value == '' ? 0 : str_replace(',','',$value);
						$denom_ids = substr($key, 3);
						if($denom_id==$denom_ids)
						{
							if($qty>0)
							{
								if($qty > numRowsWhereTwo($link,'temp_validation','tval_barcode','tval_recnum','tval_denom',$recnum,$denom_id))
								{
									$flag = 1;
									break;
								}
								else 
								{
									$response['stat'] 	= 0;
									$response['msg'] =  'GC scanned has reached the maximum count of GC Received.';
									break;	
								}
							}
							else
							{
								$response['stat'] 	= 0;
								$response['msg'] =  'No GC Received for this denomination.';
								break;								
							}
						}
					} 
					//echo 'Key =>'.substr($key, 6).' Value =>'.$value;
				}

				// $arr = array($den1,$den2,$den3,$den4,$den5,$den6);
				// $arr2 = array(1,2,3,4,5,6);

				// $arrsize = count($arr);

				// for ($i=0; $i <= $arrsize-1; $i++) {
				// 	if($denom_id==$arr2[$i])
				// 	{
				// 		if($arr[$i]>0)
				// 		{
				// 			//check temp_validation
							// if($arr[$i] > numRowsWhereTwo($link,'temp_validation','tval_barcode','tval_recnum','tval_denom',$recnum,$denom_id))
							// {
							// 	$flag = 1;
							// 	break;
							// }
							// else 
							// {
							// 	$response['stat'] 	= 0;
							// 	$response['msg'] =  'GC scanned has reached the maximum count of GC Received.';
							// 	break;	
							// }
				// 			// $response['stat'] = 0;
				// 			// $response['msg'] =  numRowsWhereTwo($link,'temp_validation','tval_barcode','tval_recnum','tval_denom',$denom_id,$recnum);
				// 		}
						// else 
						// {
						// 	$response['stat'] 	= 0;
						// 	$response['msg'] =  'No GC Received for this denomination.';
						// 	break;					
						// }
				// 	}
				// }



				if($flag)
				{
					//check gc production number

					if(checkProductionRequestForValidation($link,$ereq, $barcode)>0)
					{					
						//check if barcode already scanned
						if(!numRows($link,'temp_validation','tval_barcode',$barcode)>0)
						{
							//check if barcode already validated
							if(!numRows($link,'custodian_srr_items','cssitem_barcode',$barcode)>0)
							{
								$query_ins = $link->query(
									"INSERT INTO 
										`temp_validation`
									(
										`tval_barcode`, 
										`tval_recnum`, 
										`tval_denom`
									) 
									VALUES 
									(
										'$barcode',
										'$recnum',
										'$denom_id'
									)
								");

								if($query_ins)
								{
									$link->commit();
									$getDenom = getField($link,'denomination','denomination','denom_id',$denom_id);
									$response['stat'] = 1;
									$response['msg'] = '<div class="alert alert-info validate-flash" id="_adjust_alert">
										<h4>GC Successfully Validated.</h4>
										<p class="bar">Barcode Number: </p>
										<p class="br">'.$barcode.'</p>
										<p class="den">Denomination:<span class="den-color"> &#8369 '.number_format($getDenom,2).'</span></p> 
									</div>';
									$response['den_id'] = $denom_id;						
								}
							}
							else 
							{
								$response['stat'] = 0;
								$response['msg'] =  'GC Barcode Number '.$barcode.' already Validated.';						
							}
						}
						else 
						{
							$response['stat'] = 0;
							$response['msg'] =  'GC Barcode Number '.$barcode.' already Scanned.';						
						}
					}
					else 
					{
						$response['stat'] = 0;
						$response['msg'] =  'GC Barcode Number '.$barcode.' not found.';							
					}
				}


			}
			else 
			{
				$response['stat'] = 0;
				$response['msg'] =  'GC Barcode Number'.$barcode.' don\'t exist.';				
			}


		}
		else 
		{
			$response['stat'] = 0;
			$response['msg'] =  $link->error;
		}
		echo json_encode($response);

	} 
	elseif ($action=='custodianrec') 
	{
		$response['st'] = 0;
		$hasdenom = false;
		$recno = $link->real_escape_string($_POST['gcrecno']);
		$rectype = $link->real_escape_string($_POST['rectype']);
		$reqid = $link->real_escape_string($_POST['requisid']);
		$prid = $link->real_escape_string($_POST['prid']);

		$txtfile = $link->real_escape_string($_POST['tfile']);
		$fadrecno = $link->real_escape_string($_POST['fadrec']);
		$trandate = $link->real_escape_string($_POST['trandate']);
		$refno = $link->real_escape_string($_POST['refno']);
		$purono = $link->real_escape_string($_POST['purono']);
		$purdate = $link->real_escape_string($_POST['purdate']);
		$refpono = $link->real_escape_string($_POST['refpono']);
		$payterms = $link->real_escape_string($_POST['payterms']);
		$locode = $link->real_escape_string($_POST['locode']);
		$deptcode = $link->real_escape_string($_POST['deptcode']);
		$supname = $link->real_escape_string($_POST['supname']);
		$modpay = $link->real_escape_string($_POST['modpay']);
		$remarks = $link->real_escape_string($_POST['remarks']);
		$prepby = $link->real_escape_string($_POST['prepby']);
		$checkby = $link->real_escape_string($_POST['checkby']);
		$recas = $link->real_escape_string($_POST['recas']);

        foreach ($_POST as $key => $value) {
            if (strpos($key, 'scan') !== false)
            {
                $denom = $value == '' ? 0 : str_replace(',','',$value);
                //$denom_ids = substr($key, 4);

                if($denom > 0)
                {
                    $hasdenom = true;
                }
            }
        }

		// $den1 = $_POST['den1'];
		// $den2 = $_POST['den2'];
		// $den3 = $_POST['den3'];
		// $den4 = $_POST['den4'];
		// $den5 = $_POST['den5'];


		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif($hasdenom)
		{
			$trandate = _dateFormatoSql($trandate);
			$purdate = _dateFormatoSql($purdate);

			$link->autocommit(FALSE);
			$query_ins = $link->query(
				"INSERT INTO 
					custodian_srr
				(
					csrr_id,
					csrr_requisition, 
					csrr_receivetype,
					csrr_datetime, 
					csrr_prepared_by,
					csrr_receivedas
				) 
					VALUES 
				(
					'$recno',
					'$reqid',
					'$rectype',
					NOW(),
					'".$_SESSION['gc_id']."',
					'$recas'
				)
			");

			// $srrid = $link->insert_id;

			if($query_ins)
			{
				$last_insert = $link->insert_id;
				$query_purchasedetails = $link->query(
					"INSERT INTO 
						purchase_orderdetails
					(
						purchorderdet_ref, 
						purchorderdet_mnlno, 
						purchorderdet_fadrecno, 
						purchorderdet_trandate, 
						purchorderdet_refno, 
						purchorderdet_purono, 
						purchorderdet_purdate, 
						purchorderdet_refpono, 
						purchorderdet_payterms, 
						purchorderdet_locode, 
						purchorderdet_deptcode, 
						purchorderdet_supname, 
						purchorderdet_modpay, 
						purchorderdet_remarks, 
						purchorderdet_prepby, 
						purchorderdet_checkby
					) 
					VALUES 
					(
						'$recno',
						'$reqid',
						'$fadrecno',
						'$trandate',
						'$refno',
						'$purono',
						'$purdate',
						'$refpono',
						'$payterms',
						'$locode',
						'$deptcode',
						'$supname',
						'$modpay',
						'$remarks',
						'$prepby',
						'$checkby'
					)
				");

				if($query_purchasedetails)
				{
					// get temp validation
					$tempval = getTempValidated($link,$recno);

					//insert to receive
					$hasError = false;
					foreach ($tempval as $t) 
					{

						$remainGC = getProdReqRemainGC($link,$t->tval_denom,$prid);
						$remainGC--;
						if(subtractRemaining($link,$prid,$t->tval_denom,$remainGC))
						{
							$query_insert = $link->query(
								"INSERT INTO 
									custodian_srr_items
								(
									cssitem_barcode, 
									cssitem_recnum
								) 
								VALUES 
								(
									'$t->tval_barcode',
									'$t->tval_recnum'
								)
							");

							if($query_insert)
							{
								$query_updateGC = $link->query(
									"UPDATE 
										gc 
									SET 
										gc_validated='*'
									WHERE 
										`barcode_no` = '$t->tval_barcode'
								");

								if(!$query_updateGC)
								{
									$hasError = true;
									break;	
								}
							}
							else
							{
								$hasError = true;
								break;						
							}
						}
						else
						{
							$hasError = true;
							break;
						}			
					}

					if(!$hasError)
					{
						$rectype = strtoupper($rectype);
						if($rectype=='PARTIAL')
						{
							$request_status = 1;
						}
						elseif ($rectype=='WHOLE') {
							$request_status = 2;
						}
						elseif ($rectype=='FINAL') {
							$request_status = 2;
						}

						$query_update_req = $link->query(
							"UPDATE 
								`requisition_entry` 
							SET 
								`requis_status`='$request_status' 
							WHERE 
								`requis_erno`='$reqid'
						");

						if($query_update_req)
						{

							if(getFADIPConnectionStatus($link))
							{
								$fadnew = getField($link,'app_settingvalue','app_settings','app_tablename','fad_server_ip_received_new');
								$fadused = getField($link,'app_settingvalue','app_settings','app_tablename','fad_server_ip_received_used');
							}
							else 
							{
								$fadnew = $dir.getField($link,'app_settingvalue','app_settings','app_tablename','localhost_received_new');
								$fadused = $dir.getField($link,'app_settingvalue','app_settings','app_tablename','localhost_received_used');
							}

							$errTxtfile = false;

							if($recas=='whole' || $recas=='final')
							{
								if(!rename($fadnew.$txtfile, $fadused.$txtfile))
								{
									$errTxtfile = true;
								}	
							}		

							if($errTxtfile)
							{
								$response['msg'] = 'Problem moving textfile!';
							}
							else 
							{
								if(truncateTB($link,'temp_validation'))
								{

									$link->commit();
									$response['st'] = true;
									$response['srrid'] = $recno;
									$response['msg'] = 'Transaction Saved!';
								}									
							}		

						}
						else 
						{
							$response['msg'] =  $link->error;									
						}

					}
					else
					{
						$response['msg'] =  $link->error;					
					}
				}
				else 
				{
					$response['msg'] =  $link->error;
					
				}			
			}
			else 
			{
				$response['msg'] = $link->error;
			}
		}
		else 
		{
			$response['msg'] = 'Please scan GC.';
		}


		echo json_encode($response);
	} 
	elseif ($action=='isValidGCRelStoreGCRangeBarcode') 
	{
		$rel_no = $_POST['relid'];
		$barcode = $_POST['barcode'];
		$storeid = $_POST['storeid'];
		$reqid = $_POST['reqid'];
		$response['stat'] = 0;

		if(!checkIfExist($link,'barcode_no','gc','barcode_no',$barcode))
		{
			$response['msg'] = 'Barcode Number '.$barcode.' not found.';
		}
		else
		{
			$denid = getDenominationIDByBarcode($link,$barcode);
			$remainGC = getRemainingGCtoReleaseByDenom($link,$denid,$reqid);
			$scannedGC = getScannedGCByDenomAndReqID($link,$denid,$rel_no);	

			if(!$remainGC > ($scannedGC))
			{
				$response['msg'] = 'Number of GC Scanned has reached the maximum number to received.';
			}
			elseif (!checkIfExistNlocation($link,$barcode,$denid,$storeid)) 
			{
				$response['msg'] = 'Barcode Number '.$barcode.' not found in this location.';
			}
			elseif(checkIfExist($link,'re_barcode_no','gc_release','re_barcode_no',$barcode))
			{
				$response['msg'] = 'Barcode Number '.$barcode.' already released.';
			}
			else 
			{
				$gctype = getField($link,'loc_gc_type','gc_location','loc_gc_type',$barcode);

				$denom = getField($link,'denomination','denomination','denom_id',$denid);

				//check if it is already released 
				if(checkIfExist($link,'re_barcode_no','gc_release','re_barcode_no',$barcode))
				{
					$response['msg'] = 'Barcode Number '.$barcode.' already released.';
				}
				//check if gc already scanned 
				elseif (checkIfExist($link,'temp_rbarcode','temp_release','temp_rbarcode',$barcode)) 
				{
					$response['msg'] = 'Barcode Number '.$barcode.' already scanned for released. ';	
				}
				else 
				{
					$response['denid'] = $denid;
					$response['barcodescan'] = $barcode;
					$response['stat'] = 1;
					$response['msg'] = 'GC Barcode #'.$barcode.' validated.';
					$response['denom'] = $denom;
				}
			}
		}

		echo json_encode($response);
	}
	elseif($action=='scanreleaseStoreGCByRange')
	{
		$bstart = $_POST['bstart'];
		$bend = $_POST['bend'];
		$storeid = $_POST['storeid'];
		$rel_no = $_POST['relid'];
		$reqid = $_POST['reqid'];

		$response['stat'] = false;

		$gctotal = $bend - $bstart + 1;

		$denid = getDenominationIDByBarcode($link,$bstart);
		$remainGC = getRemainingGCtoReleaseByDenom($link,$denid,$reqid);
		$scannedGC = getScannedGCByDenomAndReqID($link,$denid,$rel_no);	

		$gctotal = $gctotal + $scannedGC;
		$nums = 0;

		if($gctotal > $remainGC)
		{
			$response['msg'] = 'Number of GC Scanned has reached the maximum number to received.';
		}
		else 
		{

			for ($x = $bstart; $x <= $bend; $x++) {

				if(!checkIfExist($link,'barcode_no','gc','barcode_no',$x))
				{
					$response['msg'] = 'Barcode Number '.$x.' not found.';
					break;
				}
				elseif (!checkIfExistNlocation($link,$x,$denid,$storeid)) 
				{
					$response['msg'] = 'Barcode Number '.$x.' not found in this location.';
					break;
				}
				elseif(checkIfExist($link,'re_barcode_no','gc_release','re_barcode_no',$x))
				{
					$response['msg'] = 'Barcode Number '.$x.' already released.';
					break;
				}
				else 
				{
					//check if it is already released 
					if(checkIfExist($link,'re_barcode_no','gc_release','re_barcode_no',$x))
					{
						$response['msg'] = 'Barcode Number '.$x.' already released.';
						break;
					}
					//check if gc already scanned 
					elseif (checkIfExist($link,'temp_rbarcode','temp_release','temp_rbarcode',$x)) 
					{
						$response['msg'] = 'Barcode Number '.$x.' already scanned for released. ';
						break;	
					}
					else 
					{

						$query = $link->query(
							"INSERT INTO 
								`temp_release`
							(
							    `temp_rbarcode`, 
							    `temp_rdenom`, 
							    `temp_rdate`, 
							    `temp_relno`,
							    `temp_relby`
							) 
							VALUES 
							(
							    '$x',
							    '$denid',
							    NOW(),
							    '$rel_no',
							    '".$_SESSION['gc_id']."'
							)
						");

						if(!$query)
						{
							$response['msg'] = $link->error;
							break;
						}
						else 
						{
							$nums++;
						}
					}
				}

			} 


			$response['stat'] = true;
			$response['msg'] = 'GC Barcode #'.$bstart.' to '.$bend.' successfully validated.';
		}

		$response['denid'] = $denid;
		$response['total'] = $nums;

		echo json_encode($response);
	}
	elseif ($action=='gcreleasevalidation') 
	{
		$rel_no = $_POST['relno'];
		$barcode = $_POST['barcode'];
		$denid = $_POST['denid'];
		$store_id = $_POST['store_id'];
		$reqid = $_POST['reqid'];
		//get denomination
		$denom = getField($link,'denomination','denomination','denom_id',$denid);

		$remainGC = getRemainingGCtoReleaseByDenom($link,$denid,$reqid);
		$scannedGC = getScannedGCByDenomAndReqID($link,$denid,$rel_no);	

		//check if barcode exist.
		if($remainGC > ($scannedGC))
		{
			if(checkIfExist($link,'barcode_no','gc','barcode_no',$barcode))
			{
				//check denomination

				if(getDenominationIDByBarcode($link,$barcode)==$denid)
				{
					//check if allocated to this store
					if(checkIfExistNlocation($link,$barcode,$denid,$store_id))
					{
						//check gc type
						$gctype = getField($link,'loc_gc_type','gc_location','loc_gc_type',$barcode);

						//check if it is already released 
						if(!checkIfExist($link,'re_barcode_no','gc_release','re_barcode_no',$barcode))
						{

							//check if gc already scanned 
							if(!checkIfExist($link,'temp_rbarcode','temp_release','temp_rbarcode',$barcode))
							{
								$query = $link->query(
									"INSERT INTO 
										`temp_release`
									(
									    `temp_rbarcode`, 
									    `temp_rdenom`, 
									    `temp_rdate`, 
									    `temp_relno`,
									    `temp_relby`
									) 
									VALUES 
									(
									    '$barcode',
									    '$denid',
									    NOW(),
									    '$rel_no',
									    '".$_SESSION['gc_id']."'
									)
								");

								if($query)
								{								
									$response['stat'] = 1;
									$response['msg'] = $barcode;
									// $response['msg'] = 'Barcode Number '.$barcode.' successfully scanned for release.';
								}
								else
								{
									$response['stat'] = 0;
									$response['msg'] = $link->query;
								}
							}
							else 
							{
								$response['stat'] = 0;
								$response['msg'] = 'Barcode Number '.$barcode.' already scanned for released. ';							
							}
						}
						else 
						{
							$response['stat'] = 0;
							$response['msg'] = 'Barcode Number '.$barcode.' already released.';
						}

					}
					else 
					{
						$response['stat'] = 0;
						$response['msg'] = 'Barcode Number '.$barcode.' not found in this location.';
					}
				}
				else 
				{
					$response['stat'] = 0;
					$response['msg'] = 'Please scan only '.number_format($denom,2).' denomination.';	
				}
			}
			else 
			{
				$response['stat'] = 0;
				$response['msg'] = 'Barcode Number '.$barcode.' not found.';
			}
		}
		else 
		{
			$response['stat'] = 0;
			//$response['msg'] = 'The qty of GC scanned has reached the maximum number to received.';
			$response['msg'] = 'Number of GC Scanned has reached the maximum number to received.';
		}
		echo json_encode($response);
	}
	elseif ($action=='scanreleasePromoGCByRange') 
	{
		$response['stat'] = false;
		$bstart = $_POST['bstart'];
		$bend = $_POST['bend'];
		$relnum = $_POST['relnum'];
		$reqid = $_POST['trid'];

		$nums = 0;


		$gctotal = $bend - $bstart + 1;

		$denid = getdenomid($link,$bend);

		$remainGC = getRemainingPromoGC($link,$denid,$reqid);		

		$scannedPromo = 0;
		if(isset($_SESSION['scannedPromo']))
		{
			foreach ($_SESSION['scannedPromo'] as $key => $value) {
				if($value['denomid']==$denid)
				{
					$scannedPromo++;
				}
			}
		}

		$gctotal = $gctotal + $scannedPromo;

		$nums = $scannedPromo;

		if($gctotal > $remainGC)
		{
			$response['msg'] = 'Number of GC Scanned has reached the maximum number to received.';
		}
		else 
		{
			for ($x = $bstart; $x <= $bend; $x++) 
			{

				if(!checkIfExist($link,'barcode_no','gc','barcode_no',$x))
				{
					$response['msg'] = 'GC Barcode # '.$x.' not found.';
					break;
				}
				elseif (!checkIfExist($link,'cssitem_barcode','custodian_srr_items','cssitem_barcode',$x)) 
				{
					$response['msg'] = 'GC Barcode # '.$x.' not yet registered.';
					break;
				}
				elseif(checkIfExist($link,'loc_id','gc_location','loc_barcode_no',$x))
				{
					$response['msg'] = 'GC Barcode # '.$x.' already allocated.';
					break;
				}
				elseif(checkIfExist($link,'prreltoi_barcode','promo_gc_release_to_items','prreltoi_barcode',$x))
				{
					$response['msg'] = 'GC Barcode # '.$x.' already received.';
					break;
				}
				elseif(checkifExist2($link,'gc_treasury_release','gc','barcode_no','gc_treasury_release',$x,'*'))
				{
					$response['msg'] = 'GC Barcode # '.$x.' released as Institution GC.';
					break;
				}
				else 
				{
					//get denom id
					$denid = getdenomid($link,$x);
					//check if has request for this denomination
					if(!numRowsWhereTwo($link,'promo_gc_request_items','pgcreqi_trid','pgcreqi_trid','pgcreqi_denom',$reqid,$denid) > 0 )
					{
						$response['msg'] = 'There is no request for this denomination.';
						break;

					}
					elseif (numRowsWhereThree($link,'promo_gc_request_items','pgcreqi_trid','pgcreqi_trid','pgcreqi_denom','pgcreqi_remaining',$reqid,$denid,0) > 0 ) 
					{
						//check if remaining if already 0 
						$response['msg'] = 'There is no request for this denomination.';
						break;
					}
					else 
					{
						if($nums > $remainGC)
						{
							$response['msg'] = 'Number of GC Scanned has reached the maximum number to received.';
						}
						else 
						{

							$table = 'gc';
							$select = 'gc.barcode_no,
								denomination.denomination,
								gc.pe_entry_gc';
							$where = "gc.barcode_no = '".$x."'";
							$join ='INNER JOIN
									denomination
								ON
									denomination.denom_id = gc.denom_id';
							
							$barcodearr = getSelectedData($link,$table,$select,$where,$join,$limit=NULL);

							$scannedPromo++;
							$response['st'] = 1;
							$response['denom'] = $denid;
							$response['scanned'] = $scannedPromo;
							$response['msg'] = $x;
							$_SESSION['scannedPromo'][] = array(
								"barcode"=>$x,
								"denomid"=>$denid,
								"relid"=>$relnum,
								"reqid"=>$reqid,
								"productionnum" => $barcodearr->pe_entry_gc,
								"denomination" => $barcodearr->denomination,
								"promo"	=> "Promo GC"
							);

							$nums++;
						}

					}
				}
			}

			$response['stat'] = true;
			$response['msg'] = 'GC Barcode #'.$bstart.' to '.$bend.' successfully validated.';
		}


		$response['total'] = $nums;
		$response['denid'] = $denid;
		

		echo json_encode($response);

		// $bstart = $_POST['bstart'];
		// $bend = $_POST['bend'];
		// $storeid = $_POST['storeid'];
		// $rel_no = $_POST['relid'];
		// $reqid = $_POST['reqid'];

		// $response['stat'] = false;

		// $gctotal = $bend - $bstart + 1;

		// $denid = getDenominationIDByBarcode($link,$bstart);
		// $remainGC = getRemainingGCtoReleaseByDenom($link,$denid,$reqid);
		// $scannedGC = getScannedGCByDenomAndReqID($link,$denid,$rel_no);	

		// $gctotal = $gctotal + $scannedGC;
		// $nums = 0;

		// if($remainGC <= $gctotal)
		// {
		// 	$response['msg'] = 'Number of GC Scanned has reached the maximum number to received.';
		// }
		// else 
		// {

		// 	for ($x = $bstart; $x <= $bend; $x++) {

		// 		if(!checkIfExist($link,'barcode_no','gc','barcode_no',$x))
		// 		{
		// 			$response['msg'] = 'Barcode Number '.$x.' not found.';
		// 			break;
		// 		}
		// 		elseif (!checkIfExistNlocation($link,$x,$denid,$storeid)) 
		// 		{
		// 			$response['msg'] = 'Barcode Number '.$x.' not found in this location.';
		// 			break;
		// 		}
		// 		elseif(checkIfExist($link,'re_barcode_no','gc_release','re_barcode_no',$x))
		// 		{
		// 			$response['msg'] = 'Barcode Number '.$x.' already released.';
		// 			break;
		// 		}
		// 		else 
		// 		{
		// 			//check if it is already released 
		// 			if(checkIfExist($link,'re_barcode_no','gc_release','re_barcode_no',$x))
		// 			{
		// 				$response['msg'] = 'Barcode Number '.$x.' already released.';
		// 				break;
		// 			}
		// 			//check if gc already scanned 
		// 			elseif (checkIfExist($link,'temp_rbarcode','temp_release','temp_rbarcode',$x)) 
		// 			{
		// 				$response['msg'] = 'Barcode Number '.$x.' already scanned for released. ';
		// 				break;	
		// 			}
		// 			else 
		// 			{

		// 				$query = $link->query(
		// 					"INSERT INTO 
		// 						`temp_release`
		// 					(
		// 					    `temp_rbarcode`, 
		// 					    `temp_rdenom`, 
		// 					    `temp_rdate`, 
		// 					    `temp_relno`,
		// 					    `temp_relby`
		// 					) 
		// 					VALUES 
		// 					(
		// 					    '$x',
		// 					    '$denid',
		// 					    NOW(),
		// 					    '$rel_no',
		// 					    '".$_SESSION['gc_id']."'
		// 					)
		// 				");

		// 				if(!$query)
		// 				{
		// 					$response['msg'] = $link->error;
		// 					break;
		// 				}
		// 				else 
		// 				{
		// 					$nums++;
		// 				}
		// 			}
		// 		}

		// 	} 


		// 	$response['stat'] = true;
		// 	$response['msg'] = 'GC Barcode #'.$bstart.' to '.$bend.' successfully validated.';
		// }

		// $response['denid'] = $denid;
		// $response['total'] = $nums;

		// echo json_encode($response);




	}
	elseif ($action=='gcreleasevalidationpromoByRange') 
	{
		$response['stat'] = false;
		$barcode = $_POST['barcode'];
		$relnum = $_POST['relnum'];
		$reqid = $_POST['trid'];

		if(!checkIfExist($link,'barcode_no','gc','barcode_no',$barcode))
		{
			$response['msg'] = 'GC Barcode # '.$barcode.' not found.';
		}
		elseif (!checkIfExist($link,'cssitem_barcode','custodian_srr_items','cssitem_barcode',$barcode)) 
		{
			$response['msg'] = 'GC Barcode # '.$barcode.' not yet registered.';
		}
		elseif(checkIfExist($link,'loc_id','gc_location','loc_barcode_no',$barcode))
		{
			$response['msg'] = 'GC Barcode # '.$barcode.' already allocated.';
		}
		elseif(checkIfExist($link,'prreltoi_barcode','promo_gc_release_to_items','prreltoi_barcode',$barcode))
		{
			$response['msg'] = 'GC Barcode # '.$barcode.' already received as promo GC.';
		}
		elseif(checkifExist2($link,'gc_treasury_release','gc','barcode_no','gc_treasury_release',$barcode,'*'))
		{
			$response['msg'] = 'GC Barcode # '.$barcode.' released as Institution GC.';
		}
		else 
		{
			//get denom id
			$denid = getdenomid($link,$barcode);
			//check if has request for this denomination
			if(!numRowsWhereTwo($link,'promo_gc_request_items','pgcreqi_trid','pgcreqi_trid','pgcreqi_denom',$reqid,$denid) > 0 )
			{
				$response['msg'] = 'There is no request for this denomination.';

			}
			elseif (numRowsWhereThree($link,'promo_gc_request_items','pgcreqi_trid','pgcreqi_trid','pgcreqi_denom','pgcreqi_remaining',$reqid,$denid,0) > 0 ) 
			{
				//check if remaining if already 0 
				$response['msg'] = 'There is no request for this denomination.';
			}
			else 
			{
				$remainGC = getRemainingPromoGC($link,$denid,$reqid);
				$barcodeExist = false;

				if(isset($_SESSION['scannedPromo']))
				{
					if(is_in_array($_SESSION['scannedPromo'], 'barcode', $barcode))
					{
						$barcodeExist = true;
					}
				}

				if($barcodeExist)
				{
					$response['msg'] = 'GC Barcode # '.$barcode.' already scanned.';
				}
				else 
				{
					// number of gc scanned
					$scannedPromo = 0;
					if(isset($_SESSION['scannedPromo']))
					{
						foreach ($_SESSION['scannedPromo'] as $key => $value) {
							if($value['denomid']==$denid)
							{
								$scannedPromo++;
							}
						}
					}

					if($scannedPromo>=$remainGC)
					{
						$response['msg'] = 'Number of GC Scanned has reached the maximum number to received.';
					}
					else 
					{
						$denom = getDenominationByBarcode($link,$barcode);

						$response['stat'] = true;
						$response['denid'] = $denid;	
						$response['denom'] = $denom;
						$response['msg'] = 'GC Barcode #'.$barcode.' validated.';



						// $scannedPromo++;
						// $response['st'] = 1;
						// $response['denom'] = $denid;
						// $response['scanned'] = $scannedPromo;
						// $response['msg'] = $barcode;
						// $_SESSION['scannedPromo'][] = array(
						// 	"barcode"=>$barcode,
						// 	"denomid"=>$denid,
						// 	"relid"=>$relnum,
						// 	"reqid"=>$reqid,
						// 	"productionnum" => $barcodearr->pe_entry_gc,
						// 	"denomination" => $barcodearr->denomination,
						// 	"promo"	=> "Promo GC"
						// );
					}				
				}
			}			

		}


		echo json_encode($response);
	} 
	elseif($action == 'gcreleasevalidationpromo')
	{
		$response['st'] = 0;
		$relnum = $_POST['relnum'];
		$barcode = $_POST['barcode'];
		$reqid = $_POST['trid'];

		//check if gc exist 
		if(!checkIfExist($link,'barcode_no','gc','barcode_no',$barcode))
		{
			$response['msg'] = 'GC Barcode # '.$barcode.' not found.';
		}
		elseif (!checkIfExist($link,'cssitem_barcode','custodian_srr_items','cssitem_barcode',$barcode)) 
		{
			$response['msg'] = 'GC Barcode # '.$barcode.' not yet registered.';
		}
		elseif(checkIfExist($link,'loc_id','gc_location','loc_barcode_no',$barcode))
		{
			$response['msg'] = 'GC Barcode # '.$barcode.' already allocated.';
		}
		elseif(checkIfExist($link,'prreltoi_barcode','promo_gc_release_to_items','prreltoi_barcode',$barcode))
		{
			$response['msg'] = 'GC Barcode # '.$barcode.' already received.';
		}
		else 
		{
			//get denom id
			$denid = getdenomid($link,$barcode);
			//check if has request for this denomination
			if(!numRowsWhereTwo($link,'promo_gc_request_items','pgcreqi_trid','pgcreqi_trid','pgcreqi_denom',$reqid,$denid) > 0 )
			{
				$response['msg'] = 'There is no request for this denomination.';

			}
			elseif (numRowsWhereThree($link,'promo_gc_request_items','pgcreqi_trid','pgcreqi_trid','pgcreqi_denom','pgcreqi_remaining',$reqid,$denid,0) > 0 ) 
			{
				//check if remaining if already 0 
				$response['msg'] = 'There is no request for this denomination.';
			}
			else 
			{
				

				$remainGC = getRemainingPromoGC($link,$denid,$reqid);
				$barcodeExist = false;

				if(isset($_SESSION['scannedPromo']))
				{
					if(is_in_array($_SESSION['scannedPromo'], 'barcode', $barcode))
					{
						$barcodeExist = true;
					}
				}

				if($barcodeExist)
				{
					$response['msg'] = 'GC Barcode # '.$barcode.' already scanned.';
				}
				else 
				{
					// number of gc scanned
					$scannedPromo = 0;
					if(isset($_SESSION['scannedPromo']))
					{
						foreach ($_SESSION['scannedPromo'] as $key => $value) {
							if($value['denomid']==$denid)
							{
								$scannedPromo++;
							}
						}
					}

					if($scannedPromo>=$remainGC)
					{
						$response['msg'] = 'Number of GC Scanned has reached the maximum number to received.';
					}
					else 
					{	
						// get barcode details

						$table = 'gc';
						$select = 'gc.barcode_no,
							denomination.denomination,
							gc.pe_entry_gc';
						$where = "gc.barcode_no = '".$barcode."'";
						$join ='INNER JOIN
								denomination
							ON
								denomination.denom_id = gc.denom_id';
						
						$barcodearr = getSelectedData($link,$table,$select,$where,$join,$limit=NULL);

						$scannedPromo++;
						$response['st'] = 1;
						$response['denom'] = $denid;
						$response['scanned'] = $scannedPromo;
						$response['msg'] = $barcode;
						$_SESSION['scannedPromo'][] = array(
							"barcode"=>$barcode,
							"denomid"=>$denid,
							"relid"=>$relnum,
							"reqid"=>$reqid,
							"productionnum" => $barcodearr->pe_entry_gc,
							"denomination" => $barcodearr->denomination,
							"promo"	=> "Promo GC"
						);
					}				
				}				
			}


		}

		echo json_encode($response);

		// $remainGC = getRemainingPromoGC($link,$denid,$reqid);
		// echo $remainGC;
	}
	elseif($action=='releaseGC')
	{
		$response['st'] = 0;
		$haspic = true;
		$imageError = 0;
		$imagename = '';
		$reqId = $link->real_escape_string($_POST['rid']);
		$relid = getReceivingNumber($link,'agcr_request_relnum','approved_gcrequest');
		//$relid = $link->real_escape_string($_POST['relno']);
		$remark = $link->real_escape_string($_POST['remark']);
		$checkby = $link->real_escape_string($_POST['checked']);
		$approved = '';
		$released = $link->real_escape_string($_POST['released']);
		$received = $link->real_escape_string($_POST['received']);
		$storeid = $link->real_escape_string($_POST['store_id']);

		$paymentType = $link->real_escape_string($_POST['paymenttypeStores']);

		$jvcust = "";
		$amount = 0;
		$bankname = '';
		$bankAccountNum = '';
		$checkNum = '';


		if($paymentType=='cash')
		{
			if(isset($_POST['amountrec']))
			{
				$amount = $_POST['amountrec'];
				$amount = str_replace(',', '', $amount);
			}
		}

		$queryError = false;

		if($paymentType=='check')
		{
			if(isset($_POST['bankname']))
			{
				$bankname = $_POST['bankname'];
			}

			if(isset($_POST['baccountnum']))
			{
				$bankAccountNum = $_POST['baccountnum'];
			}

			if(isset($_POST['cnumber']))
			{
				$checkNum = $_POST['cnumber'];
			}

			if(isset($_POST['camountrec']))
			{
				$amount = $_POST['camountrec'];
				$amount = str_replace(',', '', $amount);
			}
		}

		if($paymentType=='jv')
		{
			$amount = getTotalTempReleaseData($link,$relid);
			if(isset($_POST['jvcust']))
			{
				$jvcust = $_POST['jvcust'];
			}
		}

		if($_FILES['pic']['error'][0]==4){
			$haspic = false;
		}

		if($haspic)
		{
			$allowedTypes = array('image/jpeg');

			$fileType = $_FILES['pic']['type'][0];

			if(!in_array($fileType, $allowedTypes))
			{
				$imageError = 1;
			} 
			else 
			{
				$name = $_FILES['pic']['name'][0];
				$expImg = explode(".",$name);
				$prodImg = $expImg[0];
				$imgType = $expImg[1];

				$imagename = $_SESSION['gc_id'].'-'.getTimestamp().'.'.$imgType;
				$imageError = 0;
			}
		}

		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif(!empty($relid)&&
			!empty($reqId)&&
			!empty($remark)&&
			!empty($checkby)&&
			!empty($released)&&
			!empty($received))
		{
			if(!$imageError)
			{
				if(checkIfExist($link,'temp_rbarcode','temp_release','temp_relno',$relid))
				{
					$link->autocommit(FALSE);
					//get temp_release data
					$temp = getTempReleaseData($link,$relid);

					foreach ($temp as $t) {
						$query = $link->query(
							"INSERT INTO 
								gc_release
							(
								re_barcode_no, 
								rel_storegcreq_id, 
								rel_store_id, 
								rel_num,
								rel_date, 
								rel_by
							) 
							VALUES 
							(
								'$t->temp_rbarcode',
								'$reqId',
								'$storeid',
								'$relid',
								'$t->temp_rdate',
								'".$_SESSION['gc_id']."'
							)
						");

						if(!$query)
						{							
							$queryError	= true;
							break;
						}
						else 
						{
							$remain = getRemaining($link,$t->temp_rdenom,$reqId);
							$remain--;
							$query_uprequest = $link->query(
								"UPDATE 
									store_request_items
								SET 
									sri_items_remain='$remain'
									
								WHERE 
									sri_items_denomination ='$t->temp_rdenom'
								AND
									sri_items_requestid='$reqId'
							");

							if($query_uprequest)
							{
								$query_upallocation = $link->query(
									"UPDATE 
										gc_location 
									SET 
										loc_rel='*'
									WHERE 
										loc_barcode_no= '$t->temp_rbarcode'
								");

								if(!$query_upallocation)
								{
									$queryError = true;
									break;
								}
							}
							else 
							{								
								$queryError = true;
								break;
							}
						}					
					}
					//0 - none
					//1 - partial
					//2 - whole
					//3 - final

					if($queryError)
					{
						$response['msg'] = $link->error;
					}
					else 
					{	
						if(checkIfPartialWhole($link,$reqId))
						{
							$relstat = 2;
							if(checkifHAsReleased($link,$reqId) > 0 )
							{	
								$appreq = 3;
							}
							else 
							{
								$appreq = 2;
							}
						} 
						else
						{ 
							$relstat = 1;
							$appreq = 1;
						}

						$link->query(
							"UPDATE
								store_gcrequest 
							SET 
								sgc_status='$relstat' 
							WHERE 
								sgc_id='$reqId'
						");

						$query = $link->query(
							"INSERT INTO 
								approved_gcrequest
							(
								agcr_request_id, 
								agcr_approvedby, 
								agcr_checkedby, 
								agcr_remarks, 
								agcr_approved_at, 
								agcr_preparedby, 
								agcr_recby, 
								agcr_file_docno,
								agcr_stat,
								agcr_paymenttype,
								agcr_request_relnum
							) 
							VALUES 
							(
								'$reqId',
								'$approved',
								'$checkby',
								'$remark',
								NOW(),
								'".$_SESSION['gc_id']."',
								'$received',
								'$imagename',
								'$appreq',
								'$paymentType',
								'$relid'
							)
						");

						if($query)
						{

							$lastid = $link->insert_id;				

							if($paymentType!='')
							{
								$paynum = getReceivingNumber($link,'insp_paymentnum','institut_payment');

								$query_payment = $link->query(
									"INSERT INTO 
										institut_payment
									(
										insp_trid,
									    insp_paymentcustomer, 
									    institut_bankname, 
									    institut_bankaccountnum, 
									    institut_checknumber, 
									    institut_amountrec,
									    insp_paymentnum,
									    institut_jvcustomer
									)
									VALUES 
									(
										'$lastid',
									    'stores',
									    '$bankname',
									    '$bankAccountNum',
									    '$checkNum',
									    '$amount',
									    '$paynum',
									    '$jvcust'
									)
								");

								if(!$query_payment)
								{
									$queryError = true;
								}
							}

							if($queryError)
							{
								$response['msg'] = $link->error;
							}
							else 
							{
								if($haspic)
								{
									if(move_uploaded_file($_FILES['pic']['tmp_name'][0], "assets/images/approvedGCRequest/".$imagename))
									{
										if(deleteById($link,'temp_release','temp_relby',$_SESSION['gc_id']))
										{
											$response['st'] = 1;
											$link->commit();		
										}
										else 
										{
											$response['msg'] = $link->error;
										}
									}
									else 
									{
										$response['msg'] = 'Error Uploading image.';
									}
								}
								else 
								{
									if(deleteById($link,'temp_release','temp_relby',$_SESSION['gc_id']))
									{
										$response['st'] = 1;
										$link->commit();		
									}
									else 
									{
										$response['msg'] = 'Error Deleting Temp release GC.';
									}
								}									
							}						
						}
						else
						{
							$response['msg'] = $link->error;
						}			
					}	

				}
				else 
				{
					$response['msg'] = 'Please scan gc barcode first.';
				}	
			}
			else 
			{
				$response['msg'] = 'Upload file type not allowed.';
			}
		}
		else 
		{
			$response['msg'] =  'Please fill all <span class="requiredf">*</span>required fields.';
		}

		echo json_encode($response);
	}
	elseif ($action=='releaseGCpromoValidation') 
	{
		$response['st'] = 0;
		if(isset($_SESSION['scannedPromo']))
		{
			if(count($_SESSION['scannedPromo'])>0)
			{
				$response['st'] = 1;
			}
			else 
			{
				$response['msg'] = 'Please scan GC.';
			}
		}

		echo json_encode($response);
	}
	elseif ($action=='releaseGCpromo') 
	{
		$response['st'] = 0;
		$haspic = true;
		$imageError = 0;
		$imagename = '';
		$reqID = $_POST['trid'];
		$relnum = getRequestNo($link,'promo_gc_release_to_details','prrelto_relnumber'); 
		$remark = $_POST['remark'];
		$checked = $_POST['checked'];
		$approved = $_POST['approved'];
		$received = $_POST['received'];

		if($_FILES['docs']['error'][0]==4){
			$haspic = false;
		}

		if($haspic)
		{
			$image = checkDocuments($_FILES);
			$imageError = $image[0];
			$imagename = $image[1];
		}


		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif(empty($reqID) ||
			empty($remark) ||
			empty($checked) ||
			empty($approved) ||
			empty($received))
		{
			$response['msg'] =  'Please fill all <span class="requiredf">*</span>required fields.';	
		}
		elseif ($imageError) 
		{
			$response['msg'] = 'Upload file type not allowed.';
		}
		elseif (!count($_SESSION['scannedPromo']) > 0) 
		{
			$response['msg'] = 'Please scan gc barcode first.';
		}
		else 
		{

			//check status
			$status = 'partial';
			if(checkIfPartialWholePromo($link,$reqID))
			{
				$status = 'whole';
				//check released status
				if(numRows($link,'promo_gc_release_to_details','prrelto_trid',$reqID) > 0)
				{
					$status = 'final';
				}
			}

			$link->autocommit(FALSE);
			$query = $link->query(
				"INSERT INTO 
					promo_gc_release_to_details
				(
					prrelto_trid, 
					prrelto_relnumber,
					prrelto_docs, 
					prrelto_checkedby,
					prrelto_approvedby, 
					prrelto_relby, 
					prrelto_date, 
					prrelto_recby,
					prrelto_status,
					prrelto_remarks
				) 
				VALUES 
				(
					'$reqID',
					'$relnum',
					'$imagename',
					'$checked',
					'$approved',
					'".$_SESSION['gc_id']."',
					NOW(),
					'$received',
					'$status',
					'$remark'
				)
			");

			if($query)
			{
				//$link->commit();			
				$hasQueryError = false;
				$lastid = $link->insert_id;
				if(isset($_SESSION['scannedPromo']))
				{
					foreach ($_SESSION['scannedPromo'] as $key => $value) {
						$query_ins = $link->query(
							"INSERT INTO 
								promo_gc_release_to_items
							(
							    prreltoi_barcode, 
							    prreltoi_relid
							) 
							VALUES 
							(
							    '".$value['barcode']."',
							    '$lastid'
							)
						");

						if(!$query_ins)
						{
							$hasQueryError = true;
							break;
						}

						$query_updategc = $link->query(
							"UPDATE 
								gc 
							SET 
								gc_ispromo='*' 
							WHERE 
								barcode_no='".$value['barcode']."'
						");

						if(!$query_updategc)
						{
							$hasQueryError = true;
							break;
						}

						$remain = getRemainingPromoGC($link,$value['denomid'],$reqID);
						$remain--;
						$query_updateRem = $link->query(
							"UPDATE 
								promo_gc_request_items
							SET 
								pgcreqi_remaining='$remain' 
							WHERE 
								pgcreqi_trid='$reqID'
							AND
								pgcreqi_denom='".$value['denomid']."'
						");

						if(!$query_updateRem)
						{
							$hasQueryError = true;
							break;
						}
					}

					if(!$hasQueryError)
					{
						//$link->commit();
						if($status=='partial')
						{
							$rstatus = 'partial';
						}
						else 
						{
							$rstatus = 'closed';
						}

						$query_uprequest = $link->query(
							"UPDATE 
								promo_gc_request 
							SET 
								pgcreq_relstatus='$rstatus'
							WHERE 
								pgcreq_id='$reqID'
						");

						if($query_uprequest)
						{							
							$errorupload = false;
							if($haspic)
							{
								if(!move_uploaded_file($_FILES['docs']['tmp_name'][0], "assets/images/promoReleasedFile/".$imagename))
								{
									$errorupload = true;
								}
							}

							if($errorupload)
							{
								$response['msg'] = 'Error Uploading image.';
							}
							else 
							{
								$response['st'] = 1;
								$response['relnum'] = $relnum;
								if(isset($_SESSION['scannedPromo']))
								{
									unset($_SESSION['scannedPromo']);

									$link->commit();
								}
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
					$response['msg'] = 'No GC Scan.';
				}

			}
			else 
			{
				$response['msg'] = $link->error;
			}

			// if(isset($_SESSION['scannedPromo']))
			// {
			// 	foreach ($_SESSION['scannedPromo'] as $key => $value) {
			// 		if($value['denom']==$denid)
			// 		{
			// 			$scannedPromo++;
			// 		}
			// 	}
			// }
		}


		echo json_encode($response);
	}
	elseif($action=='truncateTempRel')
	{
		truncateTB($link,'temp_release');
	}
	elseif ($action=='truncateTempVal') 
	{
		truncateTB($link,'temp_validation');
	}
	elseif ($action=='truncatetempReceived') 
	{
		// truncateTB($link,'temp_receivestore');
		$storeid = $_POST['storeid'];
		deleteReceivedGC($link,$storeid);

	}
	elseif ($action=='validatereceivegc') 
	{
		$storeid = $_POST['storeid'];
		$barcode = $_POST['gcbarcode'];
		$recnum = $_POST['recnum'];
		$denid = $_POST['denid'];
		$qty = $_POST['qty'];

		
		//check if the number of gc scanned has reach the number of gc need to received
		// numRowsWhereThree($link,$table,$select,$field1,$field2,$var1,$var2,$field3,$var3)
		// $scanned = numRowsWhereThree($link,'temp_receivestore','trec_barcode',$field1,$field2,$field3,$var1,$var2,$var3)
		// if()

		//check if barcode already exist.
		if(numRows($link,'gc','barcode_no',$barcode) > 0)
		{
			$denomid = getDenominationIDByBarcode($link,$barcode);

			//check if correct denomination
			if($denomid==$denid)
			{
				//check if already released in the specific store.
				if(numRowsWhereTwo($link,'gc_release','re_barcode_no','re_barcode_no','rel_store_id',$barcode,$storeid) > 0)
				{
					//check if barcode is already in temp_received.
					if(!checkIfHasRows($link,'trec_barcode','temp_receivestore','trec_barcode',$barcode,'trec_recnum',$recnum))
					{
						//check if barcode is already received
						if(!numRows($link,'store_received_gc','strec_barcode',$barcode) > 0)
						{
							$denomid = getDenominationIDByBarcode($link,$barcode);
							$dencnt = 0;
							$totcnt = 0;
								$query = $link->query(
									"INSERT INTO 
										temp_receivestore
									(
										trec_barcode, 
										trec_recnum, 
										trec_store,
										trec_denid,
										trec_by
									) 
									VALUES 
									(
										'$barcode',
										'$recnum',
										'$storeid',
										'$denomid',
										'".$_SESSION['gc_id']."'
									)
								");			

								if(!$query)
								{
									$response['msg'] = $link->error;
								}
								else 
								{

									// get denom count
									$query_dencnt = $link->query(
										"SELECT 
											IFNULL(COUNT(trec_denid),0) as dencnt
										FROM 
											temp_receivestore 
										WHERE 
											trec_denid='".$denid."'
										AND
											trec_store='".$storeid."'
										AND 
											 trec_by='".$_SESSION['gc_id']."'
									");

									if(!$query_dencnt)
									{

										$response['msg'] = $link->error;
									}
									else 
									{
										$row = $query_dencnt->fetch_object();
										$dencnt = $row->dencnt;

										$query_totcnt = $link->query(
											"SELECT 
												IFNULL(COUNT(trec_denid),0) as totcnt
											FROM 
												temp_receivestore 
											WHERE 
												trec_store='".$storeid."'
											AND 
												 trec_by='".$_SESSION['gc_id']."'
										");

										if(!$query_totcnt)
										{
											$response['msg'] = $link->error;
										}
										else 
										{
											$row = $query_totcnt->fetch_object();
											$totcnt = $row->totcnt;

											$response['dencnt'] = $dencnt;
											$response['totcnt'] = $totcnt;

											$response['stat'] = 1;
											$response['msg'] = $barcode;	
										}

									}
						
								}				
						}
						else 
						{
							$response['stat'] = 0;
							$response['msg'] = 'GC Barcode #'.$barcode.' already received.';
						}
					}
					else{
						$response['stat'] = 0;
						$response['msg'] = 'GC Barcode # '.$barcode.' already scanned.';
					}
				}
				else 
				{
					$response['stat'] = 0;
					$response['msg'] = 'GC Barcode # '.$barcode.' not found in this location.';					
				}
			}
			else
			{
				$denom = getField($link,'denomination','denomination','denom_id',$denid);
				$response['stat'] = 0;
				$response['msg'] = 'Please scan only '.number_format($denom,2).' denomination.';
			}
		}
		else 
		{
			$response['stat'] = 0;
			$response['msg'] = 'Barcode no.'.$barcode.' does not exist.';
		}

		echo json_encode($response);
	} 
	elseif ($action=='recGCStore') 
	{
		$response['st'] = 0;
		$recnum = $_POST['receivednum'];
		$storeid = $_POST['storeid'];
		$relnum = $_POST['relnum'];
		$checkby = $_POST['checkedby'];
		$hasError = false;

		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		else 
		{

			$link->autocommit(FALSE);
			// transfer barcode from temp db to received store
			$gcs = getTempGCReceived($link,$recnum,$storeid);
			$totrel = numRowsWhereTwo($link,'gc_release','re_barcode_no','rel_num','rel_store_id',$relnum,$storeid);

			$total = totalGCReceivedByStore($link,$recnum,$storeid,$_SESSION['gc_id']);

			//insert ledger check GC Received Store
			$lnumber = checkledgernumber($link);

			$storeledNumber = getLedgerStoreLastLedgerNumber($link,$storeid);



			$storename = getStoreName($link,$storeid);

			$ledgercheckdesc = 'Store GC Received ('.$storename.')';


			if(count($gcs)!==$totrel)
			{
				$response['msg'] = 'Something went wrong.';
			}
			else 
			{

				$query_ledgercheck = $link->query(
					"INSERT INTO 
						ledger_check
					(
					    cledger_no, 
					    cledger_datetime, 
					    cledger_type, 
					    cledger_desc, 
					    ccredit_amt, 
					    c_posted_by 
					) 
					VALUES 
					(
					    '$lnumber',
					    NOW(),
					    'SGCR',
					    '$ledgercheckdesc',
					    '$total',
					    '".$_SESSION['gc_id']."'
					)
				");

				if($query_ledgercheck)
				{
			        $query_rec = $link->query(
						"INSERT INTO 
							store_received
						(
							srec_recid, 
							srec_rel_id, 
							srec_store_id, 
							srec_at,
							srec_checkedby, 
							srec_by,
							srec_ledgercheckref,
							srec_receivingtype
						) 
						VALUES 
						(
							'$recnum',
							'$relnum',
							'$storeid',
							NOW(),
							'$checkby',
							'".$_SESSION['gc_id']."',
							'$lnumber',
							'treasury releasing'
						)
			        ");

			        if(!$query_rec)
			        {
			        	$response['msg'] = $link->error;
			        }
			        else 
			        {
			        	$st_recid = $link->insert_id;
						foreach ($gcs as $g) {
							$query_ins = $link->query(
								"INSERT INTO 
									store_received_gc
								(
									strec_barcode, 
									strec_storeid, 
									strec_recnum, 
									strec_denom
								) 
								VALUES 
								(
									'$g->trec_barcode',
									'$g->trec_store',
									'$st_recid',
									'$g->trec_denid'
								)
							");

							if(!$query_ins)
							{
								$haserror = true;
								break;
							}
						}

						if($hasError)
						{
							$response['msg'] = $link->error;
						}
						else 
						{
							if(insertGCStoreGCEntry($link,$st_recid,$_SESSION['gc_id'],$storeledNumber,'Gift Check Entry','sledger_debit','GCE'))
							{
								if(deleteReceivedGC($link,$storeid))
								{
									if(updateOne($link,'approved_gcrequest','agcr_rec',1,'agcr_id',$relnum))
									{
										$response['st'] = 1;
										$link->commit();
									}
									else 
									{
										$response['msg'] = 'Something went wrong.';
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
			        }

				}
				else 
				{
					$response['msg'] = $link->error;
				}
			}
		}

		echo json_encode($response);	

		// if(!isset($_SESSION['gc_id']))
		// {
		// 	$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		// }
		// else 
		// {
		// 	$link->autocommit(FALSE);
		// 	// transfer barcode from temp db to received store
		// 	$gcs = getTempGCReceived($link,$recnum,$storeid);

		// 	$total = totalGCReceivedByStore($link,$recnum,$storeid,$_SESSION['gc_id']);

		// 	//insert ledger check GC Received Store
		// 	$lnumber = checkledgernumber($link);

		// 	$storeledNumber = getLedgerStoreLastLedgerNumber($link,$storeid);

		// 	$storename = getStoreName($link,$storeid);

		// 	$ledgercheckdesc = 'Store GC Received ('.$storename.')';

		// 	$query_ledgercheck = $link->query(
		// 		"INSERT INTO 
		// 			ledger_check
		// 		(
		// 		    cledger_no, 
		// 		    cledger_datetime, 
		// 		    cledger_type, 
		// 		    cledger_desc, 
		// 		    ccredit_amt, 
		// 		    c_posted_by 
		// 		) 
		// 		VALUES 
		// 		(
		// 		    '$lnumber',
		// 		    NOW(),
		// 		    'SGCR',
		// 		    '$ledgercheckdesc',
		// 		    '$total',
		// 		    '".$_SESSION['gc_id']."'
		// 		)
		// 	");

		// 	if($query_ledgercheck)
		// 	{
		//         $query_rec = $link->query(
		// 			"INSERT INTO 
		// 				store_received
		// 			(
		// 				srec_recid, 
		// 				srec_rel_id, 
		// 				srec_store_id, 
		// 				srec_at,
		// 				srec_checkedby, 
		// 				srec_by,
		// 				srec_ledgercheckref,
		// 				srec_receivingtype
		// 			) 
		// 			VALUES 
		// 			(
		// 				'$recnum',
		// 				'$relnum',
		// 				'$storeid',
		// 				NOW(),
		// 				'$checkby',
		// 				'".$_SESSION['gc_id']."',
		// 				'$lnumber',
		// 				'treasury releasing'
		// 			)
		//         ");

		//         if(!$query_rec)
		//         {
		//         	$response['msg'] = $link->error;
		//         }
		//         else 
		//         {
		//         	$st_recid = $link->insert_id;
		// 			foreach ($gcs as $g) {
		// 				$query_ins = $link->query(
		// 					"INSERT INTO 
		// 						store_received_gc
		// 					(
		// 						strec_barcode, 
		// 						strec_storeid, 
		// 						strec_recnum, 
		// 						strec_denom
		// 					) 
		// 					VALUES 
		// 					(
		// 						'$g->trec_barcode',
		// 						'$g->trec_store',
		// 						'$st_recid',
		// 						'$g->trec_denid'
		// 					)
		// 				");

		// 				if(!$query_ins)
		// 				{
		// 					$haserror = true;
		// 					break;
		// 				}
		// 			}

		// 			if($hasError)
		// 			{
		// 				$response['msg'] = $link->error;
		// 			}
		// 			else 
		// 			{
		// 				if(insertGCStoreGCEntry($link,$st_recid,$_SESSION['gc_id'],$storeledNumber,'Gift Check Entry','sledger_debit','GCE'))
		// 				{
		// 					if(deleteReceivedGC($link,$storeid))
		// 					{
		// 						if(updateOne($link,'approved_gcrequest','agcr_rec',1,'agcr_id',$relnum))
		// 						{
		// 							$response['st'] = 1;
		// 							$link->commit();
		// 						}
		// 						else 
		// 						{
		// 							$response['msg'] = 'Something went wrong.';
		// 						}
		// 					}
		// 					else
		// 					{
		// 						$response['msg'] = $link->error;
		// 					}
		// 				}
		// 				else 
		// 				{
		// 					$response['msg'] = $link->error;
		// 				}  
		// 			}
		//         }

		// 	}
		// 	else 
		// 	{
		// 		$response['msg'] = $link->error;
		// 	}
		// }
		
		// echo json_encode($response);		
	}
	elseif ($action=='cancelgcrequestbystore') 
	{
		$reqid = $_POST['id'];
		$response['msg'] = 0;
		$requestnumber = getGCStoreRequestNum($link,$reqid);
		if(!empty($reqid))
		{
			$link->autocommit(FALSE);
			$query_update = $link->query(
				"UPDATE 
					`store_gcrequest`
				SET 
					`sgc_cancel`='*'
				WHERE 
					`sgc_id` = '$reqid'
				AND
					`sgc_status`='0'
			");

			if($query_update)
			{
				$query_cancel = $link->query(
					"INSERT INTO 
						`cancelled_store_gcrequest`
					(
						`csgr_gc_id`, 
						`csgr_at`, 
						`csgr_by`
					) 
					VALUES 
					(
						'$reqid',
						NOW(),
						'".$_SESSION['gc_id']."'
					)
				");

				if($query_cancel)
				{
					$link->commit();
					$response['stat'] = 1;
					$response['msg'] = 'GC Request '.$requestnumber.' cancelled.';	
				}
				else 
				{
					$response['msg'] = $link->error;
				}
			
			}
		}
		else 
		{
			$response['msg'] = 'GC Request ID Missing';
		}

		echo json_encode($response);
	}
	elseif ($action=='checkCustomerNames') 
	{
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$mname = $_POST['mname'];
		$extname = $_POST['extname'];

		$query = $link->query(
			"SELECT 
				`cus_fname`,
				`cus_lname`,
				`cus_mname`	
			FROM 
				`customers` 
			WHERE 
				`cus_fname`='$fname'
			AND
				`cus_lname`='$lname'
			AND
				`cus_mname`='$mname'
			AND	
				`cus_namext`='$extname'
				
		");

		if($query)
		{
			if($query->num_rows)
			{
				$response['stat'] = 1;
				$response['msg'] = ucwords($fname.' '.$mname.' '.$lname.' '.$extname).' already exist.';	
			}
			else 
			{
				$response['stat'] = 0;
				$response['msg'] = '';	
			}
		}	
		else 
		{
			$response['stat'] = 1;
			$response['msg'] = $link->error;			
		}	

		echo json_encode($response);
	} 
	elseif ($action=='checkCustomerNamesUpdate') 
	{
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$mname = $_POST['mname'];
		$cusid = $_POST['cusid'];
		$extname = $_POST['extname'];

		$query = $link->query(
			"SELECT 
				`cus_fname`,
				`cus_lname`,
				`cus_mname`	
			FROM 
				`customers` 
			WHERE 
				`cus_fname`='$fname'
			AND
				`cus_lname`='$lname'
			AND
				`cus_mname`='$mname'
			AND	
				`cus_namext`='$extname'
			AND
				`cus_id`!='$cusid'
		");			

		if($query)
		{
			if($query->num_rows)
			{
				$response['stat'] = 1;
				$response['msg'] = ucwords($fname.' '.$mname.' '.$lname.' '.$extname).' already exist.';	
			}
			else 
			{
				$response['stat'] = 0;
				$response['msg'] = '';	
			}
		}	
		else 
		{
			$response['stat'] = 1;
			$response['msg'] = $link->error;			
		}	

		echo json_encode($response);

	}
	elseif ($action=='validategccustodianrange')
	{
		$barcodeStart = $_POST['gcStart'];
		$barcodeEnd = $_POST['gcEnd'];
		$recnum = $_POST['recnum'];
		//$denom = $_POST['bardenom'];
		$ereq = $_POST['ereq'];
		$queryError = false;

		$response['st'] = false;


		$response['stat'] = $barcodeStart;

		$denid = getdenomid($link,$barcodeEnd);
		$denom = getDenomByID($link,$denid);
		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		else
		{

			for($i=$barcodeStart; $i<=$barcodeEnd; $i++)
			{
				$query = $link->query(
					"INSERT INTO 
						temp_validation
					(
						tval_barcode,
						tval_recnum,
						tval_denom
					) 
					VALUES 
					(
						'$i',
						'$recnum',
						'$denid'
					)
				");

				if(!$query)
				{
					$queryError = true;
					break;
				}
			}



			if($queryError)
			{
				$response['msg'] = $link->error;
			}
			else 
			{
				$query_cnt = $link->query(
					"SELECT 
						COUNT(tval_barcode) as cnt 
					FROM 
						temp_validation 
					WHERE 
						tval_recnum = '".$recnum."'
					AND 
						tval_denom = '".$denid."'				
				");

				if(!$query_cnt)
				{
					$response['msg'] = $link->error;
				}
				else 
				{
					$cnt = $query_cnt->fetch_object();
					$response['st'] = true;
					$response['denid'] = $denid;
					$response['denqty'] = $cnt->cnt; 
					$response['msg'] = 'GC Barcode # '.$barcodeStart.' to '.$barcodeEnd.' successfully validated.';
				}
			}
		}
		
		// if(!empty($barcode)&&
		// 	!empty($recnum)&&
		// 	!empty($denom))
		// {

		// 	if(checkProductionRequestForValidation($link,$ereq, $barcode)>0)
		// 	{
		// 		if(!numRows($link,'temp_validation','tval_barcode',$barcode)>0)
		// 		{
		// 			//check if barcode already validated
		// 			if(!numRows($link,'custodian_srr_items','cssitem_barcode',$barcode)>0)
		// 			{
						// $query = $link->query(
						// 	"INSERT INTO 
						// 		`temp_validation`
						// 	(
						// 		`tval_barcode`,
						// 		`tval_recnum`,
						// 		`tval_denom`
						// 	) 
						// 	VALUES 
						// 	(
						// 		'$barcode',
						// 		'$recnum',
						// 		'$denom'
						// 	)
						// ");
		// 				if($query)
		// 				{
		// 					$denid = getdenomid($link,$barcode);
		// 					$response['stat'] = 1;
		// 					$response['denid'] = $denid;
		// 				}
		// 				else 
		// 				{
		// 					echo $link->error;
		// 				}
		// 			}
		// 			else 
		// 			{
		// 				$response['stat'] = 0;
		// 				$response['msg'] =  'GC Barcode Number '.$barcode.' already Validated.';
		// 			}
		// 		}
		// 		else 
		// 		{
		// 			$response['stat'] = 0;
		// 			$response['msg'] =  'GC Barcode Number '.$barcode.' already Scanned.';
		// 		}
		// 	}
		// 	else 
		// 	{
		// 		$response['stat'] = 0;
		// 		$response['msg'] = 'GC Barcode Number '.$barcode.' not found.';
		// 	}
		// }
		echo json_encode($response);

	}
	elseif ($action=='isValidGC') 
	{
		$ereq = $_POST['ereq'];
		$barcode = $_POST['bstart'];

		if(checkProductionRequestForValidation($link,$ereq, $barcode)>0)
		{
			if(!numRows($link,'temp_validation','tval_barcode',$barcode)>0)
			{
				//check if barcode already validated
				if(!numRows($link,'custodian_srr_items','cssitem_barcode',$barcode)>0)
				{
					$denid = getdenomid($link,$barcode);
					$denom = getDenomByID($link,$denid);
					$response['stat'] = 1;
					$response['denid'] = $denid;
					$response['denom'] = number_format($denom,2);
				}
				else 
				{
					$response['stat'] = 0;
					$response['msg'] =  'GC Barcode Number '.$barcode.' already Validated.';
				}
			}
			else 
			{
				$response['stat'] = 0;
				$response['msg'] =  'GC Barcode Number '.$barcode.' already Scanned.';
			}
		}
		else 
		{
			$response['stat'] = 0;
			$response['msg'] = 'GC Barcode number '.$barcode.' not found.';
		}

		echo json_encode($response);
	}
	elseif ($action=='isValidGCRange')
	{

		$ereq = $_POST['ereq'];
		$barcodeStart = $_POST['bstart'];
		$barcodeEnd = $_POST['bend'];

		if(checkProductionRequestForValidation($link,$ereq, $barcodeEnd)>0)
		{
			if(!numRows($link,'temp_validation','tval_barcode',$barcodeEnd)>0)
			{
				//check if barcode already validated
				if(numRows($link,'custodian_srr_items','cssitem_barcode',$barcodeEnd)>0)
				{

					$response['stat'] = 0;
					$response['msg'] =  'GC Barcode Number '.$barcodeEnd.' already Validated.';
				}
				else 
				{
					$query_check = $link->query(
						"SELECT 
							t_barcode
						FROM
							custodian_srr_items
						WHERE 
							t_barcode>='".$barcodeStart."'
						AND
							t_barcode<='".$barcodeEnd."'
					");

					if(!$query_check)
					{
						$response['msg'] = $link;
					}
					else 
					{
						if($query_check->num_rows > 0)
						{
							$response['msg'] = "GC within the range already validated.";
						}
						else 
						{
							$query_check2 = $link->query(
								"SELECT 
									tval_barcode
								FROM
									temp_validation
								WHERE 
									tval_barcode>='".$barcodeStart."'
								AND
									tval_barcode<='".$barcodeEnd."'
							");

							if(!$query_check2)
							{
								$response['msg'] = $link->error;
							}
							else
							{
								if($query_check2->num_rows > 0)
								{
									$response['msg'] = "GC within the range already scanned.";
								}
								else 
								{
									$denid = getdenomid($link,$barcodeEnd);
									$denom = getDenomByID($link,$denid);
									$response['stat'] = 1;
									$response['denid'] = $denid;
									$response['denom'] = number_format($denom,2);
								}
							}
						}
					}
				}
			}
			else 
			{
				$response['stat'] = 0;
				$response['msg'] =  'GC Barcode Number '.$barcodeEnd.' already Scanned.';
			}
		}
		else 
		{
			$response['stat'] = 0;
			$response['msg'] = 'GC Barcode number '.$barcodeEnd.' not found.';
		}

		echo json_encode($response);


// SELECT 
// 	t_barcode
// FROM
// 	test
// WHERE 
// 	t_barcode>='1010000000004'
// AND
// 	t_barcode<='1010000000010'

	}
	elseif ($action=='custodianmanager') 
	{
		$username = $_POST['username'];
		$password = md5($_POST['password']);
		$response['stat'] = 0;

		// if(checkUserAndPass($link,$username,$password))
		// {
		// 	if(checkUsertype($link,$username))
		// 	{
		// 		//get Store Name
		// 		$store_name = getStoreAssignedByUsername($link,$username);
		// 		if(checkStatus($link,$username))
		// 		{
		// 			if(checkStore($link,$username)==checkStore($link,$_SESSION['gc_user']))
		// 			{
		// 				if(checkUserRole($link,$username))
		// 				{
		// 					$response['stat'] = 1;							
		// 				}
		// 				else 
		// 				{
		// 					$response['msg']='Sorry, But you are not allowed to access this module. For '.$store_name.' manager only.'; 				
		// 				}	
		// 			}
		// 			else 
		// 			{
		// 				$response['msg']='Sorry, But you are not allowed to access this module. For '.$store_name.' users only.';		
		// 			}
			
		// 		}
		// 		else
		// 		{
		// 			$response['msg']='Your user account is inactive please contact system admin.';	
		// 		}
		// 	}
		// 	else 
		// 	{
		// 		$response['msg']='Sorry, this module is for Retail Store users only.';	
		// 	}
		// }
		// else 
		// {
		// 	$response['msg']='Username/Password is incorrect.';
		// }
		// echo json_encode($response);
	} 
	elseif ($action=='gcpromovalidation') 
	{
		$barcode = $_POST['gcbarcode'];
		$promoid = $_POST['promoid'];
		$group = $_POST['group'];
		$gctype=1;
		$response['stat'] = 0;
		if(!numRows($link,'gc','barcode_no',$barcode)>0)
		{
			$response['msg'] = 'GC Barcode #'.$barcode.' not found.';
		}
		elseif (numRows($link,'promo_gc','prom_barcode',$barcode)>0) 
		{
			$response['msg'] = 'GC Barcode #'.$barcode.' already validated for promo.';
		}
		elseif (numRows($link,'temp_promo','tp_barcode',$barcode)>0) 
		{
			$response['msg'] = 'GC Barcode #'.$barcode.' already scanned for promo validation.';
		}
		else 
		{

			$tag = getField($link,'promo_tag','users','user_id',$_SESSION['gc_id']);

			$denom = getField($link,'denom_id','gc','barcode_no',$barcode);
			$table = 'promo_gc_release_to_items';
			$select = '	promo_gc_release_to_items.prreltoi_barcode,
				gc.gc_validated,
				gc.gc_ispromo,
				gc.denom_id,
				promo_gc_request.pgcreq_group,
				promo_gc_request.pgcreq_tagged';
			$where = "promo_gc_release_to_items.prreltoi_barcode='".$barcode."'";
			$join = 'INNER JOIN
					gc
				ON
					gc.barcode_no = promo_gc_release_to_items.prreltoi_barcode
				LEFT JOIN
					promo_gc_release_to_details
				ON
					promo_gc_release_to_details.prrelto_id = promo_gc_release_to_items.prreltoi_relid
				LEFT JOIN
					promo_gc_request
				ON
					promo_gc_request.pgcreq_id = promo_gc_release_to_details.prrelto_trid';
			$limit = '';
			$promo = getSelectedData($link,$table,$select,$where,$join,$limit);

			if(!count($promo) > 0)
			{
				$response['msg'] = 'GC Barcode #'.$barcode.' not found.';
			}
			elseif($promo->gc_validated=='' || $promo->gc_ispromo=='')
			{
				$response['msg'] = 'GC Barcode #'.$barcode.' is not for Promo.';
			}
			elseif($promo->pgcreq_group!=$group)
			{
				$response['msg'] = 'GC Barcode #'.$barcode.' does not belong to Group '.$group.'.';
			}
			elseif($promo->pgcreq_tagged!=$tag)
			{
				$response['msg'] = 'GC Barcode #'.$barcode.' not found.';
			}
			else
			{
				$query_ins = $link->query(
					"INSERT INTO 
						temp_promo
					(
						tp_barcode, 
						tp_den, 
						tp_promoid,
						tp_by,
						tp_gctype
					) 
					VALUES 
					(
						'$barcode',
						'$denom',
						'$promoid',
						'".$_SESSION['gc_id']."',
						'$gctype'
					)
				");

				if($query_ins)
				{
					$response['stat'] = 1;
					$response['msg'] = 'GC Barcode '.$barcode.' successfully validated for Group '.$group.' promo.';
					$response['den'] = $denom;
				}
				else 
				{
					$response['msg'] = $link->error;
				}
			}
		}


		echo json_encode($response);
	}
	elseif ($action=='truncatepromotemp') 
	{
		 truncateTB($link,'temp_promo');
	}
	elseif ($action=='newpromo') 
	{
		// $date_exp = isset($_POST['date_expired']) ? _dateFormatoSql($_POST['date_expired']) : '0000-00-00 00:00:00';

		//$countdateneed = substr_count($_POST['date_needed'], ',');
		$tag = getField($link,'promo_tag','users','user_id',$_SESSION['gc_id']);

		$drawdate = $_POST['draw_date'];
		$datenotified = $_POST['datenoted'];

	
		$promoname = $_POST['promoname'];
		$notes = $_POST['notes'];

        if($_SESSION['gc_usertype']=='8')
        {
           $group = getField($link,'usergroup','users','user_id',$_SESSION['gc_id']);
        }
        else 
        {
        	$group = $_POST['group'];
        }

		$response['st'] = 0;
		$error = false;

		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif(empty($notes) ||
			empty($promoname) ||
			empty($group) ||
			empty($drawdate)||
			empty($datenotified))
		{
			$response['msg'] = 'Please input required fileds.';
		}
		elseif (substr_count($drawdate, ',') > 1 || substr_count($datenotified, ',') > 1) 
		{
			$response['msg'] = 'Invalid draw date / notification date.';
		}			
		else
		{
			//check temp table is not empty
			$cnt = numRows($link,'temp_promo','tp_by',$_SESSION['gc_id']);
			if($cnt>0)
			{

				$notes = $link->real_escape_string($notes);
				$promoname = $link->real_escape_string($promoname);
				$group = $link->real_escape_string($group);

				$drawdate = _dateFormatoSql($drawdate);
				$datenotified = _dateFormatoSql($datenotified);

				$drawdate = $link->real_escape_string($drawdate);
				$datenotified = $link->real_escape_string($datenotified);

				$days = getDateTo($link,'promotional_gc_claim_expiration');
				$expiration_date = date('Y-m-d', strtotime("+".$days,strtotime($datenotified)));

				$num = generatePromoNum($link);
				$link->autocommit(FALSE);				
				$q_inspromo = $link->query(
					"INSERT INTO 
						promo
					(
						promo_num, 
						promo_name, 
						promo_group,
						promo_tag, 
						promo_date, 
						promo_remarks, 
						promo_valby,
						promo_dateexpire,
						promo_datenotified,
						promo_drawdate
					) 
					VALUES 
					(
						'$num',
						'$promoname',
						'$group',
						'$tag',
						NOW(),
						'$notes',
						'".$_SESSION['gc_id']."',
						'$expiration_date',
						'$datenotified',
						'$drawdate'
					)
				");

				if($q_inspromo)
				{
					$lastid = $link->insert_id;
					$query_get = $link->query(
						"SELECT 
							tp_barcode, 
							tp_den,
							tp_gctype 
						FROM 
							temp_promo 
						WHERE 
							tp_by='".$_SESSION['gc_id']."'
					");

					if($query_get)
					{
						$numrows = $query_get->num_rows;
						if($numrows> 0)
						{
							while ($row = $query_get->fetch_object()) 
							{
								$query_insgc = $link->query(
									"INSERT INTO 
										promo_gc
									(
									    prom_promoid, 
									    prom_barcode, 
									    prom_denom, 
									    prom_gctype
									) 
									VALUES 
									(
									    '$lastid',
									    '$row->tp_barcode',
									    '$row->tp_den',
									    '$row->tp_gctype'
									)
								");

								if(!$query_insgc)
								{
									$error = true;
									break;
								}
							}

							if(!$error)
							{
								$link->commit();
								$response['msg'] = 'Promo successfully saved.';
								$response['st'] = 1;
							}
							else 
							{
								$response['msg'] = $link->error;
							}
						}
						else 
						{
							$response['msg'] = 'empty';
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
				$response['msg'] = 'Please scan gc first.';
			}
		}
		echo json_encode($response);
	}
	elseif ($action=='addnewcustomerinternal') 
	{
		$code = $_POST['code'];	
		$name = $_POST['name'];
		$cstatus = $_POST['cstatus'];
		$address = $_POST['address'];
		$type = $_POST['type'];
		$group = $_POST['group'];
		$response['stat'] = 0;
		$no_error = 1;

		$link->autocommit(FALSE);
		$query = $link->query(
			"INSERT INTO 
				`customer_internal`
			(
				`ci_code`, 
				`ci_name`, 
				`ci_type`, 
				`ci_group`, 
				`ci_address`, 
				`ci_cstatus`,
				`ci_datecreated`,
				`ci_createdby`
			) 
			VALUES 
			(
				'$code',
				'$name',
				'$type',
				'$group',
				'$address',
				'$cstatus',
				NOW(),
				'".$_SESSION['gc_id']."'
			)
		");

		if($query)
		{
			// $query_discount = $link->query(
			// 	"INSERT INTO 
			// 		`customer_discount`
			// 	(
			// 		`disc_customerid` 
			// 	) 
			// 	VALUES 
			// 	(
			// 		'$code'
			// 	)
			// ");
			$den = getDenomination($link);

			foreach ($den as $d) {
				$query_discount = $link->query(
					"INSERT INTO 
						`customer_discounts`
						(
							`cdis_cusid`, 
							`cdis_denom_id` 
						) 
						VALUES 
						(
							'$code',
							'$d->denom_id'							
						)
				");

				if(!$query_discount)
				{
					$no_error = 0;
					break;
				}
			}

			if($no_error)
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

		echo json_encode($response);	
	} 
	elseif ($action=='updateCustomerDiscount') 
	{
		$response['stat'] = 0;
		$cusid =  $_POST['cusid'];
		$disctype = $_POST['disctype'];
		$error = false;		

		$dis = getCustomerDiscounts($link,$cusid);

		$link->autocommit(FALSE);

		$query = $link->query(
			"UPDATE 
				`customer_internal` 
			SET 
				`ci_distype`='$disctype'
			WHERE 
				`ci_code`='$cusid'
		");

		if($query)
		{

			if($disctype==0)
			{
				$query_ins = $link->query(
					"UPDATE 
						`customer_discounts` 
					SET 
						`cdis_dis`='0.00'
					WHERE
						`cdis_cusid`='$cusid' 
				");

				if(!$query_ins)
				{
					$error = true;				
				}				
			}
			else 
			{
				foreach ($dis as $d) 
				{
					$query_ins = $link->query(
						"UPDATE 
							`customer_discounts` 
						SET 
							`cdis_dis`= '".$_POST['d'.$d->cdis_denom_id]."'
						WHERE
							`cdis_cusid`='$cusid'
						AND
							`cdis_denom_id` = '$d->cdis_denom_id' 
					");

					if(!$query_ins)
					{
						$error = true;				
					}				
				}
			}

			if(!$error)
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
			return $response['msg'] = $link->error; 
		}
		// $query = $link->query(
		// 	"UPDATE 
		// 		`customer_discount` 
		// 	SET 
		// 		`disc_type`='$disctype',
		// 		`disc_amtpcnt`='$amt',
		// 		`disc_updatedby`='".$_SESSION['gc_id']."',
		// 		`disc_updatedat`= NOW()
		// 	WHERE 
		// 		`disc_customerid` = '$cusid'
		// ");

		// if($query)
		// {
		// 	$response['stat'] = 1;
		// }
		// else 
		// {
		// 	$response['msg'] = $link->error;
		// }

		echo json_encode($response);
	}
	elseif($action=='checkoldpass') 
	{
		$response['st'] = 0;
		$userid = $_POST['userid'];
		$oldpassword = $_POST['opass'];

		$query = $link->query(
			"SELECT 
				`user_id` 
			FROM 
				`users`
			WHERE
				`password`='".md5($oldpassword)."'
		");

		if($query)
		{
			if($query->num_rows > 0)
			{
				$response['st'] = 1;
			}
			else 
			{
				$response['msg'] = 'Old Password is incorrect.';
			}
		}
		else 
		{
			 $response['msg'] = $link->error;
		}
		// $response['msg'] = $userid;

		echo json_encode($response);
	}
	elseif ($action=='changepassword') 
	{
		$response['st'] = 0;
		$userid = $_POST['userid'];
		$newpassword = $_POST['npass'];
		$link->autocommit(FALSE);
		$query = $link->query(
			"UPDATE 
				users 
			SET 
				password='".md5($newpassword)."'
			WHERE 
				user_id='".$_SESSION['gc_id']."'
		");

		if($query)
		{	
			if($_SESSION['gc_usertype']=='7')
			{
                //$_SESSION['gc_store']        
                $table = 'store_local_server';
                $select = 'stlocser_ip,stlocser_username,stlocser_password,stlocser_db';
                $where = "stlocser_storeid='".$_SESSION['gc_store']."'";
                $join = '';
                $limit = '';
                $lserver = getSelectedData($link,$table,$select,$where,$join,$limit);
                if(count($lserver)>0)
                {
                    //test connect
                    $lsercon = @localserver_connect($lserver->stlocser_ip,$lserver->stlocser_username,$lserver->stlocser_password,$lserver->stlocser_db);

                    if(is_array($lsercon))
                    {
                    	$query_uploc = $lsercon[0]->query(
							"UPDATE 
								users 
							SET 
								password='".md5($newpassword)."'
							WHERE 
								user_id='".$_SESSION['gc_id']."'
                    	");

                    	if($query_uploc)
                    	{
		                    $response['st'] = true;
		                    $link->commit();
                    	}
                    	else 
                    	{
                    		$response['msg'] = $lsercon[0]->error;
                    	}
                    }
                    else 
                    {
                        $response['msg'] = "Cant connect to local server.";
                    }

                }
                else
                {
                    $response['st'] = true;
                    $link->commit();
                }
			}
			else 
			{
				$response['st'] = 1;
				$link->commit();
			}
		}
		else 
		{
			$response['msg'] = $link->error;
		}

		echo json_encode($response);
	}
	elseif($action=='eodstore')
	{
		$storeid = $_SESSION['gc_store'];
		$userid = $_SESSION['gc_id'];
		$response['st'] = 0;

		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Login to Continue.';
		}
		else 
		{

			$link->autocommit(FALSE);

			$query = $link->query(
				"INSERT INTO 
					store_eod
				(
				    steod_storeid, 
				    steod_by, 
				    steod_datetime
				) 
					VALUES 
				(
				    '$storeid',
				    '$userid',
				    NOW()
				)
			");

			if(!$query)
			{
				$response['msg'] = $link->error;
			}
			else 
			{
				$last_insert = $link->insert_id;
				$res = getTextFileBalances($link,$storeid,$userid,$last_insert,$verificationfolder,$archivefolder);		
				
				if($res[0])
				{
					$link->commit();
					$response['id'] = $last_insert;
					$response['st'] = 1;
				}
				else 
				{
					$response['msg'] = $res[1];		
				}		
			}		
		}

		// if(!isset($_SESSION['gc_id']))
		// {
		// 	$response['msg'] = 'Your Session has Expired! Please Login to Continue.';
		// }
		// elseif($query)
		// {
		// 	$last_insert = $link->insert_id;
		// 	$res = getTextFileBalances($link,$storeid,$userid,$last_insert,$verificationfolder,$archivefolder);
			
		// 	if($res[0])
		// 	{
		// 		$link->commit();
		// 		$response['id'] = $last_insert;
		// 		$response['st'] = 1;
		// 	}
		// 	else 
		// 	{
		// 		$response['msg'] = $res[1];		
		// 	}
		// }
		// else 
		// {
		// 	$response['msg'] = $link->error;
		// }
		echo json_encode($response);
	}
	elseif($action=='checkproductionrequestStatus') 
	{
		$response['st'] = 0;
		$dept = getField($link,'usertype','users','user_id',$_SESSION['gc_id']);
		$table = 'production_request';
		$select = 'production_request.pe_id';
		$where = 'users.usertype = '.$dept.'
					AND
				production_request.pe_status=0';
		$join = 'INNER JOIN
					users
				ON
					users.user_id = production_request.pe_requested_by';
		$limit='';
		$request = getAllData($link,$table,$select,$where,$join,$limit);
		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif(!count($request)>0)
		{
			$response['st']=1;
		} 

		echo json_encode($response);
	}
	elseif ($action=='gctrack') 
	{
		$gc = $_POST['gc'];
		$msg = '';
		$response['st'] = 0;
		$select = 'gc.barcode_no,
					denomination.denomination,
					gcbarcodegenerate.gbcg_at,
					gc.pe_entry_gc';
		$join = 'INNER JOIN	
					denomination
				ON
					denomination.denom_id = gc.denom_id
				INNER JOIN
					gcbarcodegenerate
				ON
					gcbarcodegenerate.gbcg_pro_id = gc.pe_entry_gc';
		$where = 'barcode_no='.$gc;
		$limit = 'LIMIT 1';
		$gcgen = getSelectedData($link,'gc',$select,$where,$join,$limit);
		if(count($gcgen)>0)
		{
			$response['st'] = 1;
			$msg.='<label class="control-label zpaddingtop">GC Barcode # <span class="black">'.$gc.'</span> found.</label><br />
			<label class="control-label zpaddingtop black">Date Generated: '._dateFormat($gcgen->gbcg_at).'</label><br />			
			<label class="control-label zpaddingtop black">Denomination: '.number_format($gcgen->denomination,2).'</label><br />
			<label class="control-label zpaddingtop black">Production #<span id=""></span>: '.threedigits($gcgen->pe_entry_gc).'</label>
			';
			// get gc info

			$select = 'custodian_srr_items.cssitem_recnum,
						custodian_srr.csrr_datetime,
						purchase_orderdetails.purchorderdet_purono,
						custodian_srr.csrr_receivetype,
						users.firstname,
						users.lastname';	
			$join = 'INNER JOIN
						custodian_srr
					ON
						custodian_srr.csrr_id = custodian_srr_items.cssitem_recnum
					INNER JOIN
						purchase_orderdetails
					ON
						purchase_orderdetails.purchorderdet_ref = custodian_srr.csrr_id
					INNER JOIN 
						users
					ON
						users.user_id=custodian_srr.csrr_prepared_by';
			$where = 'custodian_srr_items.cssitem_barcode ='.$gc;
			$limit ='LIMIT 1';

			$gcsrr = getSelectedData($link,'custodian_srr_items',$select,$where,$join,$limit);
			if(count($gcsrr))
			{
				$msg.='<br /><label class="control-label zpaddingtop black">Date Received: <span id=""></span> '._dateFormat($gcsrr->csrr_datetime).' </label><br />
				<label class="control-label zpaddingtop black">GC Receiving #: <span id=""></span> '.threedigits($gcsrr->cssitem_recnum).' </label><br />
				<label class="control-label zpaddingtop black">P.O. #: <span id=""></span> '.$gcsrr->purchorderdet_purono.' </label><br />
				<label class="control-label zpaddingtop black">Received By: <span id=""></span> '.ucwords($gcsrr->firstname.' '.$gcsrr->lastname).' </label><br />
				<label class="control-label zpaddingtop black">Received Type: <span id=""></span> '.$gcsrr->csrr_receivetype.' </label>';
			}
		}
		else 
		{
			$msg.='GC Barcode # '.$gc.' not found.';
		}
		// if(numRows($link,'custodian_srr_items','custodian_srr_items',$var))
		$response['msg'] = $msg;
		echo json_encode($response);		
	}
	elseif($action=='promobudget')
	{
		$response['st'] = 0;
		
		$response['g1'] = currentBudgetByDeptByPromoGroup($link,1);
		$response['g2'] = currentBudgetByDeptByPromoGroup($link,2);

		echo json_encode($response);
	}
	elseif ($action=='deleteByIdTempPromo') 
	{
		deleteById($link,'temp_promo','tp_by',$_SESSION['gc_id']);
	}
	elseif ($action=='marketingbudgetadj') 
	{
		$grp = $_POST['grp'];
		$response['st'] = 0;
		$response['msg'] = currentBudgetByDeptByPromoGroup($link,$grp);
		echo json_encode($response);
	}
	elseif ($action=='managerkeydept') 
	{
		$response['st'] = 0;
		$uname = $_POST['username'];
		$password = $_POST['password'];
		$password = md5($password);

		if(checkUsernameExist($link,$uname))
		{
			$query_u = $link->query(
				"SELECT 
					username,
					user_role,
					user_status,
					store_assigned,
					usertype
				FROM 
					users 
				WHERE 
					username = '$uname'
				AND
					password = '$password'	
			");

			if($query_u)
			{
				if($query_u->num_rows > 0)
				{
					$row = $query_u->fetch_object();
					if($row->user_status=='active')
					{
						if($_SESSION['gc_usertype'] == '7')
						{
							if($_SESSION['gc_store']==$row->store_assigned)
							{
								if($row->user_role)
								{
									$response['st'] = 1;
									$response['msg'] = 'Access Granted.';
								}
								else 
								{
									$response['msg'] = 'User account is not authorized.';
								}						
							}
							else 
							{
								$response['msg'] = 'User account is not authorized.';
							}
						}
						else 
						{
							if($row->usertype==$_SESSION['gc_usertype'])
							{
								if($row->user_role)
								{
									$response['st'] = 1;
									$response['msg'] = 'Access Granted.';
								}
								else 
								{
									$response['msg'] = 'User account is not authorized.';
								}								
							}
							else 
							{
								$response['msg'] = 'User account is not authorized.';
							}							
						}
					}
					else 
					{
						$response['msg'] = 'User account is inactive.';
					}
				}
				else 
				{
					$response['msg'] = 'Incorrect password.';
				}
			}
			else 
			{
				$response['msg'] = $link->query;
			}

		}
		else 
		{
			$response['msg'] = 'User does not exist.';
		}

		echo json_encode($response);
	}
	elseif ($action=='checkstoreuser') 
	{
		$response['st'] = 0;
		$uname = $_POST['uname'];
		if(!checkIfExist($link,'ss_username','store_staff','ss_username',$uname))
		{
			$response['st'] = 1;
		}
		else 
		{
			$response['msg'] = $uname.' already exist.';
		}
		echo json_encode($response);
	}
	elseif ($action=='removescannedgccustodian') 
	{
		$response['st'] = 0;
		$barcode = $_POST['barcode'];
		$recno = $_POST['recno'];

		$query = $link->query(
			"SELECT 
				tval_barcode,
				tval_recnum,
				tval_denom
			FROM 
				temp_validation 
			WHERE
				tval_barcode='$barcode'
			AND
				tval_recnum='$recno'
		");

		if($query)
		{
			if($query->num_rows > 0)
			{
				$row = $query->fetch_object();

				$query_d = $link->query(
					"DELETE 
					FROM 
						temp_validation 
					WHERE 
						tval_barcode = '$barcode'
					AND
						tval_recnum = '$recno'	
				");

				if($query_d)
				{
					$response['denom'] = $row->tval_denom;
					$response['st'] = 1;
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
		}
		else 
		{
			$response['msg'] = $link->error;
		}
		
		echo json_encode($response);
	}
	elseif ($action=='backupdb') 
	{
		$response['st'] = 0;
		//get all of the tables
		$return = '';
		$tables = '*';
		if($tables == '*')
		{
			$tables = array();
			$result = $link->query('SHOW TABLES');
			while($row = $result->fetch_row())
			{
				$tables[] = $row[0];
			}
		}
		else
		{
			$tables = is_array($tables) ? $tables : explode(',',$tables);
		}

		foreach ($tables as $table) 
		{
			$result = $link->query('SELECT * FROM '.$table);
			$num_fields = $result->field_count;

			$return.= 'DROP TABLE '.$table.';';
			$row2 = $link->query('SHOW CREATE TABLE '.$table);
			$row2 = $row2->fetch_row();
			$return.= "\n\n".$row2[1].";\n\n";
		    for ($i = 0; $i < $num_fields; $i++) 
		    {
		      while($row = $result->fetch_row())
		      {
		        $return.= 'INSERT INTO '.$table.' VALUES(';
		        for($j=0; $j < $num_fields; $j++) 
		        {
		          $row[$j] = addslashes($row[$j]);
		          $row[$j] = preg_replace("/\n/","\\n",$row[$j]);
		          if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
		          if ($j < ($num_fields-1)) { $return.= ','; }
		        }
		        $return.= ");\n";
		      }
		    }
		    $return.="\n\n\n";
		}
		$backupfilename = $backupfolder.'db-backup-'.time().'-'.(md5(implode(',',$tables)));
		if($handle = fopen($backupfilename.'.sql','w+'))
		{
		  	fwrite($handle,$return);
		  	fclose($handle);

		  	$querybk= $link->query("INSERT INTO backup_records(br_filename, br_date, br_by) VALUES ('".$backupfilename."',NOW(),'".$_SESSION['gc_id']."')");

		  	if($querybk)
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
			$response['msg'] = 'Problem saving file.';
		}
		echo json_encode($response);
	}
	elseif ($action=='changestorestaffpassword') 
	{
		$response['st'] = 0;
		$uid = $_POST['userid'];
		$password = $_POST['password'];
		$password = md5($password);
		if(!empty($password))
		{
			$query = $link->query(
				"UPDATE 
					store_staff 
				SET 
					ss_password='$password' 
				WHERE 
					ss_id='$uid'
			");

			if($query)
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
			$response['msg'] = 'Please input password.';
		}
		echo json_encode($response);
	}
	elseif ($action=='changeusername') 
	{
		$response['st'] = 0;
		$userid = validateData($_SESSION['gc_id']);
		$nusername = strtolower(validateData($_POST['nusername']));

		if(!empty($nusername))
		{
			$link->autocommit(FALSE);
			$query = $link->query(
				"UPDATE 
					users 
				SET 
					username='$nusername' 
				WHERE 
					user_id = '$userid'
			");

			if($query)
			{
				if($link->affected_rows > 0)
				{

					if($_SESSION['gc_usertype']=='7')
					{
		                //$_SESSION['gc_store']        
		                $table = 'store_local_server';
		                $select = 'stlocser_ip,stlocser_username,stlocser_password,stlocser_db';
		                $where = "stlocser_storeid='".$_SESSION['gc_store']."'";
		                $join = '';
		                $limit = '';
		                $lserver = getSelectedData($link,$table,$select,$where,$join,$limit);
		                if(count($lserver)>0)
		                {
		                    //test connect
		                    $lsercon = @localserver_connect($lserver->stlocser_ip,$lserver->stlocser_username,$lserver->stlocser_password,$lserver->stlocser_db);

		                    if(is_array($lsercon))
		                    {
		                    	$query_uploc = $lsercon[0]->query(
									"UPDATE 
										users 
									SET 
										username='$nusername' 
									WHERE 
										user_id = '$userid'
		                    	");

		                    	if($query_uploc)
		                    	{
				                    $response['st'] = true;
				                    $link->commit();
		                    	}
		                    	else 
		                    	{
		                    		$response['msg'] = $lsercon[0]->error;
		                    	}
		                    }
		                    else 
		                    {
		                        $response['msg'] = "Cant connect to local server.";
		                    }

		                }
		                else
		                {
		                    $response['st'] = true;
		                    $link->commit();
		                }
					}
					else 
					{
						$response['st'] = 1;
						$link->commit();
					}

				}
				else
				{
					$response['msg'] = 'Nothing to change.';
				}
			}
			else 
			{
				$response['msg'] = $link->error;
			}
		}
		else 
		{
			$response['msg'] = 'Please input new unsername.';
		}

		echo json_encode($response);
	}
	elseif ($action=='checkusername') 
	{
		$response['st'] = 0;
		$userid = validateData($_POST['userid']);
		$nusername = strtolower(validateData($_POST['nusername']));
		if(validate_alphanumeric_underscore($nusername))
		{
			if(!checkusernameifExists($link,$userid,$nusername))
			{
				$response['st'] = 1;
			}
			else 
			{
				$response['msg'] = $nusername.' already exist.';
			}
		}
		else 
		{
			$response['msg'] = 'Username only accepts alphanumeric and underscore.';
		}
		echo json_encode($response);
	}
	elseif ($action=='checkgcpromo') 
	{
		$response['st'] = 0;
		$gc = $_POST['gc'];

		$tag = getField($link,'promo_tag','users','user_id',$_SESSION['gc_id']);

		// check gc if available

		$query_avail = $link->query(
			"SELECT
			    gc.gc_ispromo,
			    gc.gc_validated,
			    promo_gc.prom_promoid,
			    promo.promo_name,
			    promo_gc.pr_stat,
			    promo.promo_datenotified,
			    promo.promo_dateexpire,
			    promo.promo_drawdate,
			    promo_gc_release_to_items.prreltoi_barcode,
			    promogc_released.prgcrel_at,
				promo_gc_request.pgcreq_tagged
			FROM 
				gc 
			LEFT JOIN
				promo_gc
			ON
				promo_gc.prom_barcode = gc.barcode_no
			LEFT JOIN
				promo
			ON
				promo.promo_id = promo_gc.prom_promoid
			LEFT JOIN
				promogc_released
			ON
				promogc_released.prgcrel_barcode = gc.barcode_no
			LEFT JOIN
				promo_gc_release_to_items
			ON
				promo_gc_release_to_items.prreltoi_barcode = gc.barcode_no
			LEFT JOIN
				promo_gc_release_to_details
			ON
				promo_gc_release_to_details.prrelto_id = promo_gc_release_to_items.prreltoi_relid
			LEFT JOIN
				promo_gc_request
			ON
				promo_gc_request.pgcreq_id = promo_gc_release_to_details.prrelto_trid
			WHERE
				gc.barcode_no='$gc'
			AND
				promo_gc_request.pgcreq_tagged='$tag'
		");


		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif(!$query_avail)
		{
			$response['msg'] = $link->error;
		}
		else
		{
			if($query_avail->num_rows==0)
			{
				$response['msg'] = 'GC Barcode # '.$gc.' not found.';
			}
			else 
			{
				$row = $query_avail->fetch_object();

				if($row->gc_ispromo=='')
				{
					$response['msg'] = 'GC Barcode #'.$gc.' not found.';
				}
				elseif(is_null($row->prom_promoid))
				{
					$response['msg'] = 'Please assign promo first before releasing.';
				}
				elseif($row->pr_stat==1)
				{
					$response['msg'] = 'Promo GC Barcode #'.$gc.' already released. Date Released:'._dateFormat($row->prgcrel_at);
				}
				elseif($row->promo_dateexpire < _dateFormatoSql($todays_date))
				{
					$response['msg'] = 'Promo GC Barcode #'.$gc.' already expired. Date expired: '._dateFormat($row->promo_dateexpire);
				}
				else 
				{
					$response['st'] = 1;
				}

			}
		}
		echo json_encode($response);		
	}
	elseif ($action=='gcpromoreleased') 
	{
		$response['st'] = 0;		
		$gc = $_POST['gcbarcode'];	
		$claimant = $_POST['claimant'];
		$address = $_POST['address'];

		//get promo gc details
		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		else
		{
			$query_get = $link->query(
				"SELECT
					promo_gc.prom_barcode,
					promo.promo_name,
					promo.promo_group
				FROM 
					promo_gc 
				INNER JOIN
					promo
				ON
					promo.promo_id = promo_gc.prom_promoid
				WHERE 
					promo_gc.prom_barcode='$gc'
			");

			if($query_get)
			{
				$row = $query_get->fetch_object();
				$promoname = ucwords($row->promo_name);
				$promogroup = ucwords($row->promo_group);
				$link->autocommit(FALSE);
				$query_rel = $link->query(
					"INSERT INTO 
						promogc_released
					(
						prgcrel_barcode, 
						prgcrel_at,
						prgcrel_by,
						prgcrel_claimant,
						prgcrel_address
					) 
					VALUES 
					(
						'$gc',
						NOW(),
						'".$_SESSION['gc_id']."',
						'$claimant',
						'$address'
					)
				");

				if($query_rel)
				{

					$last_insert = $link->insert_id;

					$denoms = getBarcodeDenomination($link,$gc);

					if(insertBudgetLedgers($link,$last_insert,'PROMOGCRELEASING','bdebit_amt',$denoms))
					{

						$query_up = $link->query(
							"UPDATE 
								promo_gc 
							SET 
								pr_stat='1'
							WHERE 
								prom_barcode='$gc'
						");

						if($query_up)
						{					
							$link->commit();					
							$response['st'] = 1;
							$response['promo'] = $promoname;
							$response['group'] = $promogroup;
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
		echo json_encode($response);
	}
	elseif ($action=='promogcstatuslist') 
	{
		//get user tag 
		$tag = getField($link,'promo_tag','users','user_id',$_SESSION['gc_id']);

		// storing  request (ie, get/post) global array to a variable  
		$requestData= $_REQUEST;

		$columns = array( 
		// datatable column index  => database column lastname
			0 => 'barcode_no', 
			1 => 'denomination',	
			2 => 'pgcreq_group',
			3 => 'promo_name',
			4 => '',
			5 => 'prgcrel_at',
			6 => ''
		);

		// getting total number records without any search
		$sql = "SELECT 
			gc.barcode_no,
			promo_gc_request.pgcreq_group,
			promo_gc_request.pgcreq_tagged,
			denomination.denomination,
			promo.promo_name,
			users.firstname,
			users.lastname,
			promogc_released.prgcrel_at
		";
		$sql.="FROM 
				gc 
			INNER JOIN
				denomination
			ON
				denomination.denom_id = gc.denom_id
			LEFT JOIN
				promo_gc_release_to_items
			ON
				promo_gc_release_to_items.prreltoi_barcode = gc.barcode_no
			LEFT JOIN
				promo_gc_release_to_details
			ON
				promo_gc_release_to_details.prrelto_id = promo_gc_release_to_items.prreltoi_relid
			LEFT JOIN
				promo_gc_request
			ON
				promo_gc_request.pgcreq_id = promo_gc_release_to_details.prrelto_trid
			LEFT JOIN
				promo_gc
			ON
				promo_gc.prom_barcode = gc.barcode_no
			LEFT JOIN
				promo
			ON
				promo.promo_id = promo_gc.prom_promoid
			LEFT JOIN
				promogc_released
			ON
				promogc_released.prgcrel_barcode=gc.barcode_no
			LEFT JOIN
				users
			ON
				users.user_id = promogc_released.prgcrel_by
			WHERE
				gc.gc_ispromo='*'
			AND
				gc.gc_validated='*'
			AND
				promo_gc_request.pgcreq_tagged='$tag'
		";
		$query=$link->query($sql) or die($link->error);
		$totalData = $query->num_rows;
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

		$pending = 'pending';
		$available = 'available';
		$released = 'released';

		$sql = "SELECT 
			gc.barcode_no,
			promo_gc_request.pgcreq_group,
			promo_gc_request.pgcreq_tagged,
			denomination.denomination,
			promo.promo_name,
			users.firstname,
			users.lastname,
			DATE_FORMAT(promogc_released.prgcrel_at,'%b %d %Y %h:%i %p') as relat	 		
			";
		$sql.="FROM 
				gc 
			INNER JOIN
				denomination
			ON
				denomination.denom_id = gc.denom_id
			LEFT JOIN
				promo_gc_release_to_items
			ON
				promo_gc_release_to_items.prreltoi_barcode = gc.barcode_no
			LEFT JOIN
				promo_gc_release_to_details
			ON
				promo_gc_release_to_details.prrelto_id = promo_gc_release_to_items.prreltoi_relid
			LEFT JOIN
				promo_gc_request
			ON
				promo_gc_request.pgcreq_id = promo_gc_release_to_details.prrelto_trid
			LEFT JOIN
				promo_gc
			ON
				promo_gc.prom_barcode = gc.barcode_no
			LEFT JOIN
				promo
			ON
				promo.promo_id = promo_gc.prom_promoid
			LEFT JOIN
				promogc_released
			ON
				promogc_released.prgcrel_barcode=gc.barcode_no
			LEFT JOIN
				users
			ON
				users.user_id = promogc_released.prgcrel_by
			WHERE
				gc.gc_ispromo='*'
			AND
				gc.gc_validated='*'
			AND
				promo_gc_request.pgcreq_tagged='$tag'
			AND
		 		1=1";
		if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			// if(stripos($released,$requestData['search']['value']) !== false)
			// {				
			// 	$sql.=" AND ( gc.barcode_no LIKE '".$requestData['search']['value']."%' ";    
			// 	$sql.=" OR denomination.denomination LIKE '".$requestData['search']['value']."%' ";
			// 	$sql.=" OR users.firstname LIKE '%".$requestData['search']['value']."%' ";
			// 	$sql.=" OR users.lastname LIKE '%".$requestData['search']['value']."%' ";
			// 	$sql.=" OR promo_gc.pr_stat ='1'";
			// 	$sql.=" OR promo.promo_name LIKE '%".$requestData['search']['value']."%' )";				
			// }
			// else 
			// {
				$sql.=" AND ( gc.barcode_no LIKE '".$requestData['search']['value']."%' ";    
				$sql.=" OR denomination.denomination LIKE '".$requestData['search']['value']."%' ";
				$sql.=" OR users.firstname LIKE '%".$requestData['search']['value']."%' ";
				$sql.=" OR users.lastname LIKE '%".$requestData['search']['value']."%' ";
				if(stripos($released,$requestData['search']['value']) !== false)
				{
					$sql.=" OR promo_gc.pr_stat ='1'";
				}
				if(stripos($pending,$requestData['search']['value']) !== false)
				{
					$sql.=" OR promo_gc.pr_stat ='0'";
				}
				if(stripos($available,$requestData['search']['value']) !== false)
				{
					$sql.=" OR promo.promo_name IS NULL";
				}
				$sql.=" OR promo.promo_name LIKE '%".$requestData['search']['value']."%' )";
			// }
		}

		$query=$link->query($sql) or die($link->error.'q');
		$totalFiltered = $query->num_rows; // when there is a search parameter then we have to modify total number filtered rows as per search result. 
		$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
		/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
		$query=$link->query($sql) or die($link->error.'r');

		$data = array();
		while( $row=$query->fetch_assoc() ) {  // preparing an array
			$nestedData=array(); 
			$rel = is_null($row["relat"]) ? '' : $row["relat"];
			$relby = is_null($row['firstname']) ? '' : ucwords($row['firstname'].' '.$row['lastname']);
			$stat = '';
			if(is_null($row["promo_name"]))
			{
				$stat = 'Available';
			}
			else if(!is_null($row["promo_name"])&& is_null($row["relat"]))
			{
				$stat = 'Pending';
			}
			else 
			{
				$stat = 'Released';
			}
			$nestedData[] = $row["barcode_no"];
			$nestedData[] = number_format($row["denomination"],2);
			$nestedData[] = $row["pgcreq_group"];
			$nestedData[] = $row["promo_name"];
			$nestedData[] = $stat;
			$nestedData[] = $rel;
			$nestedData[] = $relby;		
			$data[] = $nestedData;
		}

		$json_data = array(
					"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
					"recordsTotal"    => intval( $totalData ),  // total number of records
					"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
					"data"            => $data   // total data array
					);

		echo json_encode($json_data);  // send data as json format
	}
	elseif ($action=='removebarcodefromassignedpromo') 
	{
		$response['st'] = 0;
		$barcode = $_POST['barcode'];
		$query_sel = $link->query(
			"SELECT 
				tp_barcode,
				tp_den
			FROM 
				temp_promo 
			WHERE 
				tp_barcode='$barcode'
			AND
				tp_by='".$_SESSION['gc_id']."'

		"); 

		if($query_sel)
		{
			if($query_sel->num_rows >  0)
			{
				
				$row = $query_sel->fetch_object();
				$response['denom'] = $row->tp_den;
				$query_del = $link->query(
					"DELETE 
					FROM 
						temp_promo 
					WHERE 
						tp_barcode='$barcode'
					AND
						tp_by='".$_SESSION['gc_id']."'
				");

				if($query_del)
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
				$response['msg'] = 'Something went wrong'; 
			}
		}
		else 
		{
			$response['msg'] = $link->error;
		}

		echo json_encode($response);
	}
	elseif ($action=='addccard') 
	{
		$response['st'] = 0;
		$cname = $_POST['ccardname'];
		if(!is_null($cname))
		{
			// credit card if already exist.
			$query = $link->query(
				"SELECT 
					ccard_name
				FROM
					credit_cards
				WHERE
					LOWER(ccard_name) ='".strtolower($cname)."'
			");

			if($query)
			{
				if($query->num_rows > 0 )
				{
					$response['msg'] = $cname.' already exist.';
				}
				else
				{
					$query_ins = $link->query(
						"INSERT INTO 
							credit_cards
						(
						 	ccard_name, 
						    ccard_status, 
						    ccard_created, 
						    ccard_by
						) 
						VALUES 
						(
						    '$cname',
						    '1',
							NOW(),
						    '".$_SESSION['gc_id']."'
						)
					");

					if($query_ins)
					{
						$response['st'] = 1;
					}
					else 
					{
						$response['msg'] = $link->error;
					}
				}
			}
			else 
			{
				$response['msg'] = $link->error;
			}

		}
		else 
		{
			$response['msg'] = 'Please input credit card name.';
		}
		echo json_encode($response);		
	}
	elseif ($action=='loadbudgetstatus') 
	{
		//$bud = countAdj($link,'budget_adjustment');
        $dept = getField($link,'usertype','users','user_id',$_SESSION['gc_id']);
        $table = 'budget_request';
        $select = 'budget_request.br_id';
        $where = 'users.usertype='.$dept.'
            AND
          budget_request.br_request_status=0';
        $join = 'INNER JOIN
            users
          ON
            users.user_id = budget_request.br_requested_by';
        $limit='';
        $budPenReq = getAllData($link,$table,$select,$where,$join,$limit);
        ?>
          <?php echo count($budPenReq)>0 ? '<a href="pending_budget_request.php">':''; ?>
      		<div class="slate-colorbox red red-x bot">      			
      				<i class="fa fa-exclamation-triangle fa-pad"></i>
      				<div class="slate-colorbox-label">Pending Request</div>
              <span class="badge badge-count red-b"><?php echo count($budPenReq); ?></span>      				  		  
    			</div>
          <?php echo count($budPenReq)>0 ? '</a>':''; ?>

          <?php 
            $budAppReq = checkRequest($link,'budget_request','br_request_status','1'); 
          ?>
          <?php echo $budAppReq>0 ?'<a href="approved-budget-request.php">':''; ?>
    			<div class="slate-colorbox blue blue-x bot">
        			<i class="fa fa-check-square-o fa-pad"></i>
        			<div class="slate-colorbox-label">Approved Request</div>
       				<span class="badge badge-count blue-b"><?php echo $budAppReq; ?></span>
    			</div>
          <?php echo $budAppReq>0 ?'</a>':''; ?>

          <?php 
            $budCanReq = checkRequest($link,'budget_request','br_request_status','2'); 
          ?>
          <?php echo $budCanReq>0 ?'<a href="cancelled-budget-request.php">':''; ?>
          <div class="slate-colorbox gray gray-x">
              <i class="fa fa-times fa-pad"></i>
              <div class="slate-colorbox-label">Cancelled Request</div>
              <span class="badge badge-count black-b"><?php echo $budCanReq; ?></span>  
          </div>
          <?php echo $budCanReq>0 ?'</a>':''; ?>   

        <?php
	}
	elseif($action=='loadgcrequest')
	{
		$dept = getField($link,'usertype','users','user_id',$_SESSION['gc_id']);
       	$table = 'production_request';
        $select = 'production_request.pe_id';
        $where = 'users.usertype='.$dept.'
          AND
            production_request.pe_status=0';
        $join = 'INNER JOIN
            users
          ON
            users.user_id = production_request.pe_requested_by';
        $limit='';
        $proPenReq = getAllData($link,$table,$select,$where,$join,$limit);
          ?>
          <?php  
            echo count($proPenReq) > 0 ? '<a href="pending_production_request.php">':'';
          ?>
      		<div class="slate-colorbox red red-x bot">
      				<i class="fa fa-exclamation-triangle fa-pad"></i>
      				<div class="slate-colorbox-label">Pending Request</div>
      				<span class="badge badge-count red-b"><?php echo count($proPenReq); ?></span>
    			</div>
          <?php echo count($proPenReq) > 0 ? '</a>':''; ?>

           <?php  
            $proAppReq = checkRequest($link,'production_request','pe_status','1');
            echo $proAppReq > 0 ? '<a href="approved-production-request.php">':'';
          ?>         
    			<div class="slate-colorbox blue blue-x bot">
        			<i class="fa fa-check-square-o fa-pad"></i>
        			<div class="slate-colorbox-label">Approved Request</div>
       				<span class="badge badge-count blue-b"><?php echo $proAppReq; ?></span>
    			</div>
          <?php echo $proAppReq > 0 ? '</a>':''; ?>

           <?php  
            $proCanReq = checkRequest($link,'production_request','pe_status','2');
            echo $proCanReq > 0 ? '<a href="cancelled-production-request.php">':'';
          ?>            
          <div class="slate-colorbox gray gray-x">
              <i class="fa fa-times fa-pad"></i>
              <div class="slate-colorbox-label">Cancelled Request</div>
              <span class="badge badge-count black-b"><?php echo $proCanReq; ?></span>
          </div>
          <?php echo $proCanReq > 0 ? '</a>':''; ?>      	
    <?php
	}
	elseif ($action=='loadstorerequest') 
	{

		$storePenReq = countStoresRequest($link);//checkGCStoreRequest($link,'store_gcrequest','sgc_status',1,0);
		echo $storePenReq > 0 ? '<a href="tran_release_gc.php">' : '';
		?>
			<div class="slate-colorbox red red-x bot">
					<i class="fa fa-exclamation-triangle fa-pad"></i>
					<div class="slate-colorbox-label">Pending Request</div>
					 <span class="badge badge-count red-b"><?php echo $storePenReq; ?></span>
			</div>
		<?php echo $storePenReq > 0 ? '</a>' : ''; ?>

		<?php 
		$storeAppReq = count(GCReleasedAllStore($link));
		echo $storeAppReq > 0 ? '<a href="approved-gc-request.php">':'';
		?>
			<div class="slate-colorbox blue blue-x bot">
				<i class="fa fa-check-square-o fa-pad"></i>
				<div class="slate-colorbox-label">Released GC</div>
					<span class="badge badge-count blue-b"><?php echo $storeAppReq; ?></span>
			</div>
		<?php echo $storeAppReq > 0 ? '</a>':''?>

		<?php 
		$storeCanReq = countAllGCRequestCancelled($link); 
		echo $storeCanReq > 0 ? '<a href="cancelled-gc-request.php">':''; 
		?>
		<div class="slate-colorbox gray gray-x">
		  <i class="fa fa-times fa-pad"></i>
		  <div class="slate-colorbox-label">Cancelled Request</div>
		  <span class="badge badge-count black-b"><?php echo $storeCanReq; ?></span>
		</div>    
		<?php echo $storeCanReq > 0 ? '</a>':''; ?>   
    <?php
	}
	elseif ($action=='loadbudgetstatusfinance') 
	{
          $budPenReq = checkRequest($link,'budget_request','br_request_status','0'); 
        ?>
        <?php echo $budPenReq> 0 ? '<a href="pending_gcrequest.php">':''; ?>
        <div class="slate-colorbox red red-x bot">            
            <i class="fa fa-exclamation-triangle fa-pad"></i>
            <div class="slate-colorbox-label">Pending Request</div>
            <span class="badge badge-count red-b"><?php echo $budPenReq; ?></span>                      
        </div>
        <?php echo $budPenReq>0 ? '</a>':''; ?>

        <?php 
          $budAppReq = checkRequest($link,'budget_request','br_request_status','1'); 
        ?>
        <?php echo $budAppReq>0 ?'<a href="approved-budget-request.php">':''; ?>
        <div class="slate-colorbox blue blue-x bot">
            <i class="fa fa-check-square-o fa-pad"></i>
            <div class="slate-colorbox-label">Approved Request</div>
            <span class="badge badge-count blue-b"><?php echo $budAppReq; ?></span>
        </div>
        <?php echo $budAppReq>0 ?'</a>':''; ?>


        <?php 
          $budCanReq = checkRequest($link,'budget_request','br_request_status','2'); 
        ?>
        <?php echo $budCanReq>0 ?'<a href="cancelled-budget-request.php">':''; ?>
        <div class="slate-colorbox gray gray-x">
            <i class="fa fa-times fa-pad"></i>
            <div class="slate-colorbox-label">Cancelled Request</div>
            <span class="badge badge-count black-b"><?php echo $budCanReq; ?></span>  
        </div>
        <?php echo $budCanReq>0 ?'</a>':''; ?>    

    <?php
	}
	elseif ($action=='loadgcrequestfinance') 
	{
	      $proPenReq = checkRequest($link,'production_request','pe_status','0');
	      echo $proPenReq > 0 ? '<a href="pending_productionreq.php">':'';
	    ?>
	    <div class="slate-colorbox red red-x bot">
	        <i class="fa fa-exclamation-triangle fa-pad"></i>
	        <div class="slate-colorbox-label">Pending Request</div>
	        <span class="badge badge-count red-b"><?php echo $proPenReq; ?></span>
	    </div>
	    <?php echo $proPenReq > 0 ? '</a>':''; ?>

	    <?php  
	      $proAppReq = checkRequest($link,'production_request','pe_status','1');
	      echo $proAppReq > 0 ? '<a href="approved-production-request.php">':'';
	    ?>         
	    <div class="slate-colorbox blue blue-x bot">
	        <i class="fa fa-check-square-o fa-pad"></i>
	        <div class="slate-colorbox-label">Approved Request</div>
	        <span class="badge badge-count blue-b"><?php echo $proAppReq; ?></span>
	    </div>
	    <?php echo $proAppReq > 0 ? '</a>':''; ?>

	    <?php  
	      $proCanReq = checkRequest($link,'production_request','pe_status','2');
	      echo $proCanReq > 0 ? '<a href="cancelled-production-request.php">':'';
	    ?>            
	    <div class="slate-colorbox gray gray-x">
	        <i class="fa fa-times fa-pad"></i>
	        <div class="slate-colorbox-label">Cancelled Request</div>
	        <span class="badge badge-count black-b"><?php echo $proCanReq; ?></span>
	    </div>
	    <?php echo $proCanReq > 0 ? '</a>':''; ?>    
	<?php
	}
	elseif ($action=='loadbudgetstatusretail') 
	{
	     $table = 'budget_request';
	      $select = 'budget_request.br_id';
	      $where = "budget_request.br_request_status='0'
	        AND
	          budget_request.br_group='1'";
	      $join = '';
	      $limit='';
	      $budPenReq = getAllData($link,$table,$select,$where,$join,$limit);
	    ?>
	    <?php echo count($budPenReq)> 0 ? '<a href="new_budget_request.php">':''; ?>
	    <div class="slate-colorbox red red-x bot">            
	        <i class="fa fa-exclamation-triangle fa-pad"></i>
	        <div class="slate-colorbox-label">Pending Request</div>
	        <span class="badge badge-count red-b"><?php echo count($budPenReq); ?></span>                      
	    </div>
	    <?php echo count($budPenReq)>0 ? '</a>':''; ?>

	    <?php 
	      $budAppReq = checkRequest($link,'budget_request','br_request_status','1'); 
	    ?>
	    <?php echo $budAppReq>0 ?'<a href="approved-budget-request.php">':''; ?>
	    <div class="slate-colorbox blue blue-x bot">
	        <i class="fa fa-check-square-o fa-pad"></i>
	        <div class="slate-colorbox-label">Approved Request</div>
	        <span class="badge badge-count blue-b"><?php echo $budAppReq; ?></span>
	    </div>
	    <?php echo $budAppReq>0 ?'</a>':''; ?>


	    <?php 
	      $budCanReq = checkRequest($link,'budget_request','br_request_status','2'); 
	    ?>
	    <?php echo $budCanReq>0 ?'<a href="cancelled-budget-request.php">':''; ?>
	    <div class="slate-colorbox gray gray-x">
	        <i class="fa fa-times fa-pad"></i>
	        <div class="slate-colorbox-label">Cancelled Request</div>
	        <span class="badge badge-count black-b"><?php echo $budCanReq; ?></span>  
	    </div>
	    <?php echo $budCanReq>0 ?'</a>':''; ?>    
	<?php
	}
	elseif ($action=='loadgcrequestretail') 
	{
	      $proAppReq = checkRequest($link,'production_request','pe_status','1');
	      echo $proAppReq > 0 ? '<a href="approved-production-request.php">':'';
	    ?>         
	    <div class="slate-colorbox blue blue-x bot">
	        <i class="fa fa-check-square-o fa-pad"></i>
	        <div class="slate-colorbox-label">Approved Request</div>
	        <span class="badge badge-count blue-b"><?php echo $proAppReq; ?></span>
	    </div>
	    <?php echo $proAppReq > 0 ? '</a>':''; ?>

	    <?php  
	      $proCanReq = checkRequest($link,'production_request','pe_status','2');
	      echo $proCanReq > 0 ? '<a href="cancelled-production-request.php">':'';
	    ?>            
	    <div class="slate-colorbox gray gray-x">
	        <i class="fa fa-times fa-pad"></i>
	        <div class="slate-colorbox-label">Cancelled Request</div>
	        <span class="badge badge-count black-b"><?php echo $proCanReq; ?></span>
	    </div>
	    <?php echo $proCanReq > 0 ? '</a>':''; ?>  
	<?php
	}
	elseif($action=='loadstorerequeststatus')
	{
		$penReq = count(getPendingGCRequestStore($link,$_SESSION['gc_store']));
		echo $penReq > 0 ? '<a href="pending-store-gcrequest.php">':'';
		?>
		<div class="slate-colorbox red red-x bot">
		  	<i class="fa fa-exclamation-triangle fa-pad"></i>
		 	<div class="slate-colorbox-label">Pending GC Request</div>
		  	<span class="badge badge-count red-b"><?php echo $penReq; ?></span>
		</div>
		<?php echo $penReq > 0 ? '</a>':''; ?>

		<?php 
			$appReq = gcStoreGCReleasedNumRows($link,$_SESSION['gc_store']);
		?>
		<?php echo $appReq > 0 ? '<a href="approved-gc-request.php">':''; ?>
		<div class="slate-colorbox blue blue-x bot">
		  	<i class="fa fa-check-square-o fa-pad"></i>
		  	<div class="slate-colorbox-label">Released GC</div>
		  	<span class="badge badge-count blue-b"><?php echo $appReq; ?></span>
		</div>   
		<?php echo $appReq > 0 ? '</a>':''; ?>

		<?php 
			$canReq = countStoreGCRequestCancelled($link,$_SESSION['gc_store']);
		?>
		<?php echo $canReq > 0 ? '<a href="cancelled-gc-request.php">':''; ?>
		<div class="slate-colorbox gray gray-x">            
			<i class="fa fa-times fa-pad"></i>
			<div class="slate-colorbox-label">Cancelled Request</div>
			<span class="badge badge-count black-b"><?php echo $canReq; ?></span>        
		</div>
		<?php echo $canReq > 0 ? '</a>':''; ?>

		<?php
	}
	elseif ($action=='loadavailgcperstore') 
	{
		$denom = getAllDenomination($link);
		?>
            <?php foreach ($denom as $key): ?>
              <li class="list-group-item">
                <span class="badge badge-sold" did="<?php echo $key->denom_id; ?>" dst="<?php echo $_SESSION['gc_store']; ?>"> 
                  <?php echo getCurrentAvailableGCByStore($link,$_SESSION['gc_store'],$key->denom_id); ?>
                </span>
                &#8369 <?php echo number_format($key->denomination,2); ?></li>      
            <?php endforeach ?>
		<?php
	}
	elseif ($action=='loadsoldgcperstore') 
	{
		$denom = getAllDenomination($link);
		?>
            <?php foreach ($denom as $key): ?>
              <li class="list-group-item">
                <span class="badge badge-sold" did="<?php echo $key->denom_id; ?>" dst="<?php echo $_SESSION['gc_store']; ?>"> 
                  <?php echo getSoldGCPerStore($link,$_SESSION['gc_store'],$key->denom_id); ?>
                </span>
                &#8369 <?php echo number_format($key->denomination,2); ?></li>      
            <?php endforeach ?>
        <?php
	}
	elseif ($action=='storereceipt') 
	{
		$response['st'] = 0;
		$storeid = $_POST['storeid'];
		$checked = $_POST['checked'];

		if($checked=='true')
		{
			$cstatus = 'yes';
		}
		else 
		{
			$cstatus = 'no';
		}

		if(storeReceiptIssuance($link,$storeid,$cstatus))
		{
			$response['st'] = 1;
		}
		else 
		{
			$response['msg'] = $link->error;
		}

		//$response['msg'] = $cstatus;	

		echo json_encode($response);
	}
	elseif ($action=='loadcardsalesbystore')
	{
		$select = "store_name,store_id";
		$where ="1";
		$stores = getAllData($link,'stores',$select,$where,'','');
		?>
			<table class="table table-adj" id="stores">
				<thead>
					<tr>
						<th>Store</th>
						<th>Total Sales</th>
						<th>View Transactions</th>
					</tr>                              
				</thead>
				<tbody>
					<?php foreach ($stores as $s): ?>
						<tr>
							<td><?php echo ucwords($s->store_name); ?></td>
							<td>
								<?php
									$where = "transaction_stores.trans_store='".$s->store_id."' AND customer_internal_ar.ar_type=2";
									$select = "IFNULL(SUM(customer_internal_ar.ar_dbamt),0.00) as storesale";
									$join = "INNER JOIN
												customer_internal_ar
											ON
												customer_internal_ar.ar_trans_id = transaction_stores.trans_sid";
									$db = getSelectedData($link,'transaction_stores',$select,$where,$join,''); 
									echo '&#8369 '.number_format($db->storesale,2);   
								?>
							</td>
							<td></td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>

			<script type="text/javascript">
				$.extend( $.fn.dataTableExt.oStdClasses, {	  
				    "sLengthSelect": "selectsup"
				});
			    $('#stores').dataTable( {
			        "pagingType": "full_numbers",
			        "ordering": false,
			        "processing": true,
			    });
			</script>
		<?php
	}
	elseif ($action=='loadcardsalesbycards') 
	{
		$select = "ccard_name, ccard_id";
		$where ="1";
		$cards = getAllData($link,'credit_cards',$select,$where,'','');
		?>
          <table class="table table-adj" id="stores">
            <thead>
                <tr>
                  <th>Card Name</th>
                  <th>Total Sales</th>
                  <th style="text-align:center">View Transactions</th>
                </tr>                              
            </thead>
            <tbody>
              <?php foreach ($cards as $c): ?>
                <tr>
                  <td><?php echo ucwords($c->ccard_name); ?></td>
                  <td>
                    <span>
                    <?php 
                      $where = 'creditcard_payment.cc_creaditcard='.$c->ccard_id.' AND ar_type =2';
                      $select = 'IFNULL(SUM(customer_internal_ar.ar_dbamt),0.00) as totdb';
                      $join = 'INNER JOIN
                        customer_internal_ar
                          ON
                        creditcard_payment.cctrans_transid = customer_internal_ar.ar_trans_id';

                      $db = getSelectedData($link,'creditcard_payment',$select,$where,$join,'');                      
                      echo '&#8369 '.number_format($db->totdb,2);
                    ?>
                  </td>
                  </span>
                  <td style="text-align:center"><a href="cardsalestransactions.php?card=<?php echo $c->ccard_id; ?>"><i class="fa fa-fa fa-eye faeye" title="View"></i></a></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
			<script type="text/javascript">
				$.extend( $.fn.dataTableExt.oStdClasses, {	  
				    "sLengthSelect": "selectsup"
				});
			    $('#stores').dataTable( {
			        "pagingType": "full_numbers",
			        "ordering": false,
			        "processing": true,
			    });
			</script>
		<?php
	}
	elseif($action=='adddenom')
	{
		$response['st'] = 0;
		$denom = $link->real_escape_string(trim($_POST['denom']));
		$bstart = $link->real_escape_string(trim($_POST['bstart']));
		
		//check if denomination already exist
		if(checkIfExist($link,'denomination','denomination','denomination',$denom))
		{
			$response['msg'] = $denom.' already exist.';
		}
		else 
		{
			if(checkIfExist($link,'denom_barcode_start','denomination','denom_barcode_start',$bstart))
			{
				$response['msg'] = $bstart.' already exist.';
			}
			else 
			{
				if(strlen($bstart) == 13)
				{
					$response['st'] = 1;
				}
				else 
				{
					$response['msg'] = 'Barcode start must be 13 characters long.';
				}
			}
		}

		echo json_encode($response);
	}
	elseif ($action=='savenewdenom') 
	{
		$response['st'] = 0;
		$denom = $link->real_escape_string(trim($_POST['denom']));
		$bstart = $link->real_escape_string(trim($_POST['bstart']));

		$denomcode = substr($bstart,0,2);
		$query = $link->query(
			"INSERT INTO 
				denomination
			(
				denom_code,
			    denomination,
			    denom_barcode_start,
			    denom_status,
			    denom_createdby,
			    denom_datecreated
			) 
			VALUES 
			(
			    '$denomcode',
			    '$denom',
			    '$bstart',
			    'active',
			    '".$_SESSION['gc_id']."',
			    NOW()
			)
		");

		if($query)
		{
			$response['st'] = 1;
		}
		else 
		{
			$response['msg'] = $link->error;
		}
		echo json_encode($response);
	}
	elseif($action=='updatedenom')
	{
		$response['st'] = 0;
		
		$denomid = $_POST['denomid'];
		$denom = $_POST['denom'];
		$bstart = $_POST['bstart'];
		$faditemnum = $_POST['faditem'];
		$hastrans = $_POST['hastrans'];

		//check if denom already exist
		if(checkifExistNotEqualtoID($link,'denomination','denomination','denomination','denom_id',$denom,$denomid))
		{
			$response['msg'] = $denom.' already exist.';
		}	
		else 
		{
			if(checkifExistNotEqualtoID($link,'denomination','denomination','denom_barcode_start','denom_id',$bstart,$denomid))
			{
				$response['msg'] = $bstart.' already exist.';
			}
			else 
			{

				if($faditemnum=='00000000')
				{
					$response['st'] = 1;
				}
				else 
				{
					if(checkifExistNotEqualtoID($link,'denomination','denomination','denom_fad_item_number','denom_id',$faditemnum,$denomid))
					{
						$response['msg'] = $faditemnum.' already exist.';
					}
					else 
					{
						$response['st'] = 1;
					}
				}
			}
		}	

		echo json_encode($response);
	}
	elseif ($action=='saveupdatedenom') 
	{
		$response['st'] = 0;
		
		$denomid = $_POST['denomid'];
		$denoms = $_POST['denoms'];
		$denom = $_POST['denom'];
		$bstart = $_POST['bstart'];
		$faditemnum = $_POST['faditem'];
		$hastrans = $_POST['hastrans'];

		if($hastrans)
		{
			$denom = $denoms;
		}

		$denomcode = substr($bstart,0,2);

		$query_up = $link->query(
			"UPDATE 
				denomination 
			SET	
				denom_code='$denomcode',
				denomination='$denom',
				denom_fad_item_number='$faditemnum',
				denom_barcode_start='$bstart',
				denom_updatedby='".$_SESSION['gc_id']."',
				denom_dateupdated=NOW() 
			WHERE 
				denom_id='$denomid'
		");

		if($query_up)
		{
			if($link->affected_rows >0)
			{
				$response['st'] = 1;
			}
			else 
			{
				$response['msg'] = 'No data affected';
			}
		}
		else 
		{
			$response['msg'] = $link->error;
		}

		echo json_encode($response);
	}
	elseif ($action=='gcrequstinternal') 
	{
		// foreach ($_POST['ninternalcusd'] as $key => $value  ) {
		// 	echo 'Denom = '.$value.' Qty'.$_POST['ninternalcusq'][$key];
		// }

		//echo $_SESSION['gc_store'];
		$response['st'] = 0;
		$imageError = 0;
		$imagename = '';
		$haspic = true;
		$dateneed = $link->real_escape_string(_dateFormatoSql($_POST['date_needed']));
		//$penum = $link->real_escape_string(trim($_POST['penum']));
		// get requestnumber
		$store_id = $_SESSION['gc_store'];
		$penum = getFieldOrderLimit($link,'sgc_num','store_gcrequest','sgc_store',$store_id,'sgc_id','DESC',1);
		$penum+=1;
		$remarks = $link->real_escape_string($_POST['remarks']);
		$requestedby = $link->real_escape_string(trim($_POST['requestedby']));

		if($_FILES['pic']['error'][0]==4)
		{
			$haspic = false;
		}

		if($haspic)
		{

			$allowedTypes = array('image/jpeg');

			$fileType = $_FILES['pic']['type'][0];

			if(!in_array($fileType, $allowedTypes))
			{
				$imageError = 1;
			} 
			else 
			{
				$name = $_FILES['pic']['name'][0];
				$expImg = explode(".",$name);
				$prodImg = $expImg[0];
				$imgType = $expImg[1];

				$imagename = $_SESSION['gc_id'].'-'.getTimestamp().'.'.$imgType;
				$imageError = 0;
			}
		}

		if(!empty($penum)&&
			!empty($dateneed)&&
			!empty($remarks)&&
			!empty($requestedby))
		{
			if(!$imageError)
			{
				$link->autocommit(FALSE);
				$query = $link->query(
				"INSERT INTO 
					store_gcrequest
					(
						sgc_num, 
						sgc_requested_by, 
						sgc_date_request, 
						sgc_date_needed, 
						sgc_file_docno, 
						sgc_remarks, 
						sgc_status, 
						sgc_store,
						sgc_type

					) 
					VALUES 
					(
						'$penum',
						'".$_SESSION['gc_id']."',
						NOW(),
						'$dateneed',
						'$imagename',
						'$remarks',
						'0',
						'$store_id',
						'special internal'
				)");

				$last_insert_request = $link->insert_id;

				if($query)
				{
					$query_spc = $link->query(
						"INSERT INTO 
							special_internal_customer
						(
							spcus_id, 
							spcus_customername
						) 
						VALUES 
						(
							'$last_insert_request',
							'$requestedby'
						)
					");

					if($query_spc)
					{
						$hasError = false;
						$exist = [];
						foreach ($_POST['ninternalcusd'] as $key => $value  ) 
						{
							$value = str_replace(',', '', $value);
							$qty = str_replace(',', '',$_POST['ninternalcusq'][$key]);
							if(checkIfExist($link,'denomination','denomination','denomination',$value))
							{	
								//get denom first
								$denom_id = getField($link,'denom_id','denomination','denomination',$value);
								insertDenomRequest($link,'store_request_items','sri_id','sri_items_denomination', 'sri_items_quantity', 'sri_items_requestid',$denom_id,$qty,$last_insert_request,'sri_items_remain',$qty);
							}
							else 
							{
								insertDenomRequest($link,'store_request_items','sri_id','sri_items_denomination', 'sri_items_quantity', 'sri_items_requestid','0',$qty,$last_insert_request,'sri_items_remain',$qty);
								$last_insert_denom = $link->insert_id;
								$query_setup = $link->query(
									"INSERT INTO 
										for_denom_set_up
									(
									    fds_denom_reqid,
									    fds_denom,
									    fds_status
									) 
									VALUES 
									(
									    '$last_insert_denom',
									    '$value',
									    'pending'
									)
								");
								if(!$query_setup)
								{
									$hasError = true;
									break;
								}
							}
						}

						if(!$hasError)
						{
							if($haspic)
							{
								if(move_uploaded_file($_FILES['pic']['tmp_name'][0], "assets/images/gcRequestStore/" . $imagename))
								{
									$response['st'] = 1;
									$link->commit();
								}
								else 
								{
									$response['msg'] = 'Error Uploading image.';
								}
							}
							else 
							{
								$response['st'] = 1;
								$link->commit();
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
				$response['msg'] = 'Upload file type not allowed.';
			}
		}
		else 
		{
			$response['msg'] = 'Please fill all required fields.';
		}
		
		echo json_encode($response);
	}
	elseif ($action=='specialExternalGCRequest') 
	{
		$response['st'] = 0;
		$imageError = 0;
		$haspic = true;
		$countdateneed = substr_count($_POST['date_needed'], ',');
		$companyid = $link->real_escape_string($_POST['companyid']);
		$payment = $link->real_escape_string($_POST['paymenttype']);
		$amount = $link->real_escape_string(str_replace(',','',$_POST['amount']));
		$dateneed = $link->real_escape_string(_dateFormatoSql($_POST['date_needed']));
		$remarks = $link->real_escape_string($_POST['remarks']);

		$bankname = $link->real_escape_string($_POST['bankname']);
		$cnumber = $link->real_escape_string($_POST['cnumber']);

		$reqnum = getRequestNoByExternal($link);

		//echo $countdateneed;

		//echo $bankname;

		//check if docs have errors

		if(count($_FILES['docs']['name'])==0)
		{
			$haspic = false;
		}

		if($haspic)
		{
			$imageError = checkDocumentsMutiple($_FILES);
		}

		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif(!checkIfExist($link,'spcus_id','special_external_customer','spcus_id',$companyid))
		{
			$response['msg'] = 'Company dont exist.';
		}
		elseif (count($_POST['ninternalcusd']) == 0) 
		{
			$response['msg'] = 'Please add denomination.';
		}
		elseif (count($_SESSION['empAssign'])==0) 
		{
			$response['msg'] = 'Please assign customer employee.';
		}
		elseif($countdateneed > 1)
		{
			$response['msg'] = 'Invalid Date Needed.';
		}
		elseif (empty($dateneed) || empty($companyid) || empty($payment) || empty($remarks)) 
		{
			$response['msg'] = 'Please fill all required fields.';
		}
		else 
		{
			$link->autocommit(FALSE);
			$query = $link->query(
				"INSERT INTO 
					special_external_gcrequest
				(
				 	spexgc_num, 
				    spexgc_reqby, 
				    spexgc_datereq, 
				    spexgc_dateneed, 
				    spexgc_remarks, 
				    spexgc_company, 
				    spexgc_payment, 
				    spexgc_paymentype,
				    spexgc_status,
				    spexgc_type

				) 
				VALUES 
				(
				    '$reqnum',
				    '".$_SESSION['gc_id']."',
				   	NOW(),
				    '$dateneed',
				    '$remarks',
				    '$companyid',
				    '$amount',
				    '$payment',
				    'pending',
				    2
				)
			");

			if($query)
			{
				$lastid = $link->insert_id;
				$queryError = false;
				if($payment==2)
				{
					$query_bank = $link->query(
						"INSERT INTO 
							special_external_bank_payment_info
						(
						    spexgcbi_trid, 
						    spexgcbi_bankname, 
						    spexgcbi_checknumber
						) 
						VALUES 
						(
						    '$lastid',
						    '$bankname',
						    '$cnumber'
						)
					");

					if(!$query_bank)
					{
						$queryError = true;
					}
				}

				if(!$queryError)
				{
					if(isset($_SESSION['empAssign']))
					{
						foreach ($_SESSION['empAssign'] as $key => $value) 
						{
														
							$query_emp = $link->query(
								"INSERT INTO 
									special_external_gcrequest_emp_assign
								(
								    spexgcemp_trid, 
								    spexgcemp_denom, 
								    spexgcemp_fname, 
								    spexgcemp_lname, 
								    spexgcemp_mname, 
								    spexgcemp_extname 
								) 
								VALUES 
								(
								    '$lastid',
								    '".$link->real_escape_string($value['denom'])."',
								    '".$link->real_escape_string($value['firstname'])."',
								    '".$link->real_escape_string($value['lastname'])."',
								    '".$link->real_escape_string($value['middlename'])."',
								    '".$link->real_escape_string($value['extname'])."'								    
								)
							");

							if(!$query_emp)
							{	
								$queryError = true;
							}	
						}

						if(!$queryError)
						{
							$errorUpload = false;
							if($haspic && !$imageError)
							{
								$pathfolder = 'externalDocs';
								for($i=0; $i < count($_FILES['docs']['name']); $i++) 
								{
									$imagename = externalDocumentFilename($external = $_FILES['docs']['name'][$i],$i,$reqnum);
									if(move_uploaded_file($_FILES['docs']['tmp_name'][$i], "assets/images/".$pathfolder."/".$imagename))
									{
										$fullpathfolder = $pathfolder.'/'.$imagename;
										$query_files = $link->query(
											"INSERT INTO 
												documents
											(
											    doc_trid, 
											    doc_type, 
											    doc_fullpath
											) 
											VALUES 
											(
											    '$lastid',
											    'Special External GC Request',
											    '$fullpathfolder'
											)
										");

										if(!$query_files)
										{
											$queryError = true;
										}
									}
									else 
									{
										$errorUpload = true;
										break;
									}
								}

								if($errorUpload)
								{
									$response['msg'] = 'Error Uploading Files.';
								}
								elseif($queryError)
								{
									$response['msg'] = $link->error;
								}
								else
								{
									$link->commit();
									$response['st'] = 1;
								}
							}
						}
						else 
						{
							$response['msg'] = $link->error;
						}

					}
					else 
					{
						$response['msg'] = 'Please assign employee.';
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

		echo json_encode($response);
	}
	elseif ($action=='specialExternalGCPayment') 
	{

		$response['st'] = 0;
		$imageError = 0;
		$haspic = true;
		$countdateneed = substr_count($_POST['date_needed'], ',');
		$companyid = $link->real_escape_string($_POST['companyid']);
		$payment = $link->real_escape_string($_POST['paymenttype']);
		$amount = $link->real_escape_string(str_replace(',','',$_POST['amount']));
		$dateneed = $link->real_escape_string(_dateFormatoSql($_POST['date_needed']));
		$remarks = $link->real_escape_string($_POST['remarks']);
		$arnumber = $link->real_escape_string($_POST['arnumber']);

		$bankname = '';
		$bankaccount = '';
		$cnumber = '';


		if(isset($_POST['bankname']))
		{
			$bankname = $link->real_escape_string($_POST['bankname']);
		}

		if(isset($_POST['baccountnum']))
		{
			$bankaccount = $link->real_escape_string($_POST['baccountnum']);
		}

		if(isset($_POST['cnumber']))
		{
			$cnumber = $link->real_escape_string($_POST['cnumber']);
		}

		$reqnum = getRequestNoByExternal($link);

		//check if docs have errors

		if(count($_FILES['docs']['name'])==0)
		{
			$haspic = false;
		}

		if($haspic)
		{
			$imageError = checkDocumentsMutiple($_FILES);
		}


		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif(!checkIfExist($link,'spcus_id','special_external_customer','spcus_id',$companyid))
		{
			$response['msg'] = 'Company dont exist.';
		}
		elseif (count($_POST['ninternalcusd']) == 0) 
		{
			$response['msg'] = 'Please add denomination.';
		}
		elseif($countdateneed > 1)
		{
			$response['msg'] = 'Invalid Date Needed.';
		}
		elseif (empty($dateneed) || empty($companyid) || empty($payment) || empty($remarks)) 
		{
			$response['msg'] = 'Please fill all required fields1.';
		}
		else 
		{
			$link->autocommit(FALSE);
			$query = $link->query(
				"INSERT INTO 
					special_external_gcrequest
				(
				 	spexgc_num, 
				    spexgc_reqby, 
				    spexgc_datereq, 
				    spexgc_dateneed, 
				    spexgc_remarks, 
				    spexgc_company, 
				    spexgc_payment, 
				    spexgc_paymentype,
				    spexgc_status,
				    spexgc_type,
				    spexgc_payment_stat,
				    spexgc_addemp,
				    spexgc_payment_arnum

				) 
				VALUES 
				(
				    '$reqnum',
				    '".$_SESSION['gc_id']."',
				   	NOW(),
				    '$dateneed',
				    '$remarks',
				    '$companyid',
				    '$amount',
				    '$payment',
				    'pending',
				    2,
				    'paid',
				    'pending',
				    '$arnumber'
				)
			");

			if($query)
			{
				$lastid = $link->insert_id;
				$queryError = false;
				$paynum = getReceivingNumber($link,'insp_paymentnum','institut_payment');
				$query_payment = $link->query(
					"INSERT INTO 
						institut_payment
					(
					    insp_trid, 
					    insp_paymentcustomer, 
					    institut_bankname, 
					    institut_bankaccountnum, 
					    institut_checknumber, 
					    institut_amountrec,
					    insp_paymentnum
					) 
					VALUES 
					(
					    '$lastid',
					    'special external',
					    '$bankname',
					    '$bankaccount',
					    '$cnumber',
					    '$amount',
					    '$paynum'
					)
				");

				if(!$query_payment)
				{
					$response['msg'] = $link->error;
				}
				else 
				{					
					if(!isset($_POST['ninternalcusd'])||!isset($_POST['ninternalcusq']))
					{
						$response['msg'] = 'Please add denomination/quantity.';
					}
					elseif(count($_POST['ninternalcusd'])==0)
					{
						$response['msg'] = 'Please add denomination/quantity.';
					}
					else 
					{
						$queryError = false;
						$index = 0;
						foreach ($_POST['ninternalcusd'] as $key) 
						{
							$denom = str_replace(',', '', $key);
							$qty = str_replace(',', '', $_POST['ninternalcusq'][$index]);

							$query_denom = $link->query(
								"INSERT INTO 
									special_external_gcrequest_items
								(
								    specit_denoms, 
								    specit_qty, 
								    specit_trid
								) 
								VALUES 
								(
								    '$denom',
								    '$qty',
								    '$lastid'
								)
							");

							if(!$query_denom)
							{
								$queryError = true;
								break;
							}

							// echo $key.'<br />';
							// echo $_POST['ninternalcusq'][$index];
							// $index++;
							$index++;
						}		

						if($queryError)
						{
							$response['msg'] = $link->error;
						}			
						else 
						{
							$errorUpload = false;
							if($haspic && !$imageError)
							{
								$pathfolder = 'externalDocs';
								for($i=0; $i < count($_FILES['docs']['name']); $i++) 
								{
									$imagename = externalDocumentFilename($external = $_FILES['docs']['name'][$i],$i,$reqnum);
									if(move_uploaded_file($_FILES['docs']['tmp_name'][$i], "assets/images/".$pathfolder."/".$imagename))
									{
										$fullpathfolder = $pathfolder.'/'.$imagename;
										$query_files = $link->query(
											"INSERT INTO 
												documents
											(
											    doc_trid, 
											    doc_type, 
											    doc_fullpath
											) 
											VALUES 
											(
											    '$lastid',
											    'Special External GC Request',
											    '$fullpathfolder'
											)
										");

										if(!$query_files)
										{
											$queryError = true;
										}
									}
									else 
									{
										$errorUpload = true;
										break;
									}
								}


							}

							if($errorUpload)
							{
								$response['msg'] = 'Error Uploading Files.';
							}
							elseif($queryError)
							{
								$response['msg'] = $link->error;
							}
							else
							{
								$link->commit();
								$response['id'] = $lastid;
								$response['st'] = 1;
							}
						}	
					}
				}

			}
			else 
			{
				$response['msg'] = $link->error;
			}

		}

		echo json_encode($response);

	}
	elseif ($action=='specialExternalGCRequestNew') 
	{
		$response['st'] = 0;
		$imageError = 0;
		$haspic = true;
		$countdateneed = substr_count($_POST['date_needed'], ',');
		$companyid = $link->real_escape_string($_POST['companyid']);
		$payment = $link->real_escape_string($_POST['paymenttype']);
		$amount = $link->real_escape_string(str_replace(',','',$_POST['amount']));
		$dateneed = $link->real_escape_string(_dateFormatoSql($_POST['date_needed']));
		$remarks = $link->real_escape_string($_POST['remarks']);

		$bankname = $link->real_escape_string($_POST['bankname']);
		$bankaccount = $link->real_escape_string($_POST['baccountnum']);
		$cnumber = $link->real_escape_string($_POST['cnumber']);

		$reqnum = getRequestNoByExternal($link);

		//check if docs have errors

		if(count($_FILES['docs']['name'])==0)
		{
			$haspic = false;
		}

		if($haspic)
		{
			$imageError = checkDocumentsMutiple($_FILES);
		}


		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif(!checkIfExist($link,'spcus_id','special_external_customer','spcus_id',$companyid))
		{
			$response['msg'] = 'Company dont exist.';
		}
		elseif (count($_POST['ninternalcusd']) == 0) 
		{
			$response['msg'] = 'Please add denomination.';
		}
		elseif($countdateneed > 1)
		{
			$response['msg'] = 'Invalid Date Needed.';
		}
		elseif (empty($dateneed) || empty($companyid) || empty($payment) || empty($remarks)) 
		{
			$response['msg'] = 'Please fill all required fields1.';
		}
		else 
		{
			$link->autocommit(FALSE);
			$query = $link->query(
				"INSERT INTO 
					special_external_gcrequest
				(
				 	spexgc_num, 
				    spexgc_reqby, 
				    spexgc_datereq, 
				    spexgc_dateneed, 
				    spexgc_remarks, 
				    spexgc_company, 
				    spexgc_payment, 
				    spexgc_paymentype,
				    spexgc_status,
				    spexgc_type

				) 
				VALUES 
				(
				    '$reqnum',
				    '".$_SESSION['gc_id']."',
				   	NOW(),
				    '$dateneed',
				    '$remarks',
				    '$companyid',
				    '$amount',
				    '$payment',
				    'pending',
				    1
				)
			");

			if($query)
			{
				$lastid = $link->insert_id;
				$queryError = false;
				if($payment==2)
				{
					$query_bank = $link->query(
						"INSERT INTO 
							special_external_bank_payment_info
						(
						    spexgcbi_trid, 
						    spexgcbi_bankname, 
						    spexgcbi_bankaccountnum, 
						    spexgcbi_checknumber
						) 
						VALUES 
						(
						    '$lastid',
						    '$bankname',
						    '$bankaccount',
						    '$cnumber'
						)
					");

					if(!$query_bank)
					{
						$queryError = true;
					}
				}

				if(!$queryError)
				{
					if(!isset($_POST['ninternalcusd'])|| !isset($_POST['ninternalcusq']))
					{
						$response['msg'] = 'Please add denomination/quantity.';
					}
					elseif(count($_POST['ninternalcusd'])==0)
					{
						$response['msg'] = 'Please add denomination/quantity.';
					}
					else 
					{
						$queryError = false;
						$index = 0;
						foreach ($_POST['ninternalcusd'] as $key) 
						{
							$denom = str_replace(',', '', $key);
							$qty = str_replace(',', '', $_POST['ninternalcusq'][$index]);

							$query_denom = $link->query(
								"INSERT INTO 
									special_external_gcrequest_items
								(
								    specit_denoms, 
								    specit_qty, 
								    specit_trid
								) 
								VALUES 
								(
								    '$denom',
								    '$qty',
								    '$lastid'
								)
							");

							if(!$query_denom)
							{
								$queryError = true;
								break;
							}

							// echo $key.'<br />';
							// echo $_POST['ninternalcusq'][$index];
							// $index++;
							$index++;
						}		

						if($queryError)
						{
							$response['msg'] = $link->error;
						}			
						else 
						{
							$errorUpload = false;
							if($haspic && !$imageError)
							{
								$pathfolder = 'externalDocs';
								for($i=0; $i < count($_FILES['docs']['name']); $i++) 
								{
									$imagename = externalDocumentFilename($external = $_FILES['docs']['name'][$i],$i,$reqnum);
									if(move_uploaded_file($_FILES['docs']['tmp_name'][$i], "assets/images/".$pathfolder."/".$imagename))
									{
										$fullpathfolder = $pathfolder.'/'.$imagename;
										$query_files = $link->query(
											"INSERT INTO 
												documents
											(
											    doc_trid, 
											    doc_type, 
											    doc_fullpath
											) 
											VALUES 
											(
											    '$lastid',
											    'Special External GC Request',
											    '$fullpathfolder'
											)
										");

										if(!$query_files)
										{
											$queryError = true;
										}
									}
									else 
									{
										$errorUpload = true;
										break;
									}
								}

								if($errorUpload)
								{
									$response['msg'] = 'Error Uploading Files.';
								}
								elseif($queryError)
								{
									$response['msg'] = $link->error;
								}
								else
								{
									$link->commit();
									$response['st'] = 1;
								}
							}
						}	
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

		echo json_encode($response);
	}
	elseif ($action=='specialExternalGCRequestSaveData') 
	{
		$response['st'] = 0;
		$imageError = 0;
		$imagename = '';
		$haspic = true;
		$companyid = $_POST['companyid'];
		$amount = str_replace(',','',$_POST['amount']);
		if(isset($_POST['baccount']))
		{
			$bankaccount = $_POST['baccount'];
		}

		if(isset($_POST['baccountname']))

		$dateneed = $link->real_escape_string(_dateFormatoSql($_POST['date_needed']));
		$reqnum = getRequestNoByExternal($link);

		$remarks = $link->real_escape_string($_POST['remarks']);

		if($_FILES['pic']['error'][0]==4)
		{
			$haspic = false;
		}

		if($haspic)
		{
			$image = checkDocuments($_FILES);

			$imageError = $image[0];
			$imagename = $image[1];
		}

		// if(!empty($dateneed)&&
		// 	!empty($remarks)&&)
		// {
		// 	if(!$imageError)
		// 	{
		// 		$link->autocommit(FALSE);
		// 		$query = $link->query(
		// 			"INSERT INTO 
		// 				special_external_gcrequest
		// 			(	
		// 				spexgc_num, 
		// 				spexgc_reqby, 
		// 				spexgc_datereq,
		// 				spexgc_dateneed,
		// 				spexgc_filedoc,
		// 				spexgc_remarks,
		// 				spexgc_status,
		// 				spexgc_company,
		// 				spexgc_payment,
		// 				spexgc_paymentype
		// 			) 
		// 			VALUES 
		// 			(
		// 				'$reqnum',
		// 				'".$_SESSION['gc_id']."',
		// 				NOW(),
		// 				'$dateneed',
		// 				'$imagename',
		// 				'$remarks',
		// 				'pending',
		// 				'$companyid',
		// 				'',
		// 				[value-11]
		// 			)
		// 		");

		// 		if($query)
		// 		{

		// 		}
		// 	}
		// 	else 
		// 	{
		// 		$response['msg'] = 'Invalid document file type.';
		// 	}
		// }
		// else 
		// {
		// 	$response['msg'] = '';
		// }

		$response['msg'] = $bankaccount;
		echo json_encode($response);
	}
	elseif ($action=='fadServerConnectionStatus') 
	{
		$response['st'] = 0;
		$status = $_POST['checked'];

		if($status===true)
			$status = 'yes';
		else 
			$status = 'no';

		$query = $link->query(
			"UPDATE 
				app_settings 
			SET 
				app_settingvalue='$status'
			WHERE 
				app_tablename='fad_server_connection'
		");

		if($query)
		{
			$response['st'] = 1;
		}
		else 
		{
			$response['msg'] = $link->error;
		}

		echo json_encode($response);
	}
	elseif ($action=='fadserverupdate') 
	{
		$response['st'] = 0;

		$folder = [];
		$error = false;
		$error_array = [];

		$fadrequisnew = $link->real_escape_string($_POST['requisnew']);
		$folder[] = $fadrequisnew;
		$fadrequisused = $link->real_escape_string($_POST['requisused']);
		$folder[] = $fadrequisused;
		$fadrecnew = $link->real_escape_string($_POST['receivednew']);
		$folder[] = $fadrecnew;
		$fadrecused = $link->real_escape_string($_POST['receivedused']);
		$folder[] = $fadrecused;
		$localrequisnew = $link->real_escape_string($_POST['localrequisnew']);
		$localrequisused = $link->real_escape_string($_POST['localrequisused']);
		$localrecnew = $link->real_escape_string($_POST['localreceivednew']);
		$localrecused = $link->real_escape_string($_POST['localreceivedused']);

		$localreceivednew = $dir.$localrequisnew;
		$folder[] = $localreceivednew;
		$localrequisused = $dir.$localrequisused;
		$folder[] = $localrequisused;
		$localrecnew = $dir.$localrecnew;
		$folder[] = $localrecnew;
		$localrecused = $dir.$localrecused;
		$folder[] = $localrecused;

		for ($i=0; $i < count($folder); $i++) 
		{ 
			if(!file_exists($folder[$i]))
			{
				$error = true;
				$error_array[] = "<li>".$folder[$i]."</li>";
			}
		}

		if($error)
		{
			$errorHTML = '<h4>Folder Not Found.</h4>';
			$errorHTML.="<ul>";
			foreach ($error_array as $key => $value) 
			{
				$errorHTML.=$value;
			}
			$errorHTML.="</ul>";
			$response['msg'] = $errorHTML;
		}

		echo json_encode($response);
	}
	elseif($action=='setupdenom')
	{
		$response['st'] = 0;
		$faditemexist = false;
		$denom = $link->real_escape_string(trim($_POST['denom']));
		$bstart = $link->real_escape_string(trim($_POST['bstart']));
		if(isset($_POST['faditem']))
		{
			$faditem = $link->real_escape_string(trim($_POST['faditem']));
		}
		else 
		{
			$faditem = '';
		}

		// check denom already exist

		//$denomcode = substr($bstart,0,2);
		if(!checkIfExist($link,'denomination','denomination','denomination',$denom))
		{
			//checkIfExist($link,$field,$table,$row,$var)
			if(!checkIfExist($link,'denom_barcode_start','denomination','denom_barcode_start',$bstart))
			{
				if(!empty($faditem))
				{
					if(checkIfExist($link,'denom_fad_item_number','denomination','denom_fad_item_number',$faditem))
					{
						$faditemexist = true;
					}
				}

				if(!$faditemexist)
				{
					$link->autocommit(false);
			        $denomcode = substr($bstart,0,2);
			        $query = $link->query(
			            "INSERT INTO 
			                denomination
			            (
			                denom_code,
			                denomination,
			                denom_barcode_start,
			                denom_status,
			                denom_createdby,
			                denom_datecreated,
			                denom_type,
			                denom_fad_item_number
			            ) 
			            VALUES 
			            (
			                '$denomcode',
			                '$denom',
			                '$bstart',
			                'active',
			                '".$_SESSION['gc_id']."',
			                NOW(),
			                'SIGC',
			                '$faditem'
			            )
			        ");

			        if($query)
			        {
			        	$last_insert = $link->insert_id;

			        	// get all gc request denomination id

			        	$query_getRequest = $link->query(
			        		"SELECT 
								fds_denom_reqid 
							FROM 
								for_denom_set_up 
							WHERE
								fds_denom='$denom'
			        	");

			        	if($query_getRequest)
			        	{
			        		$reqid = [];
			        		while ($row = $query_getRequest->fetch_object()) 
			        		{
			        			$reqid[] = $row->fds_denom_reqid;
			        		}

			        		$query_update = $link->query(
			        			"UPDATE 
									for_denom_set_up 
								SET 
									fds_status='complete'
								WHERE 
									fds_denom='$denom'	
			        		");

			        		if($query_update)
			        		{
			        			$errorUpdate = false;
			        			foreach ($reqid as $key => $value) {
			        				$query_update_denid = $link->query(
			        					"UPDATE	
											store_request_items 
										SET 
											sri_items_denomination='$last_insert'
										WHERE 
											sri_id='$value'
										AND
											sri_items_denomination='0'	
			        				");

			        				if(!$query_update_denid)
			        				{
			        					$errorUpdate = true;
			        					break;
			        				}
			        			}

			        			if(!$errorUpdate)
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
					$response['msg'] = 'FAD item # already exist.';
				}
			}
			else 
			{
				$response['msg'] = 'Barcode # start already exist.';
			}
		}
		else 
		{
			$response['msg'] = 'Denomination already exist.';
		}

		echo json_encode($response);
	}
	elseif($action=='savesetupdenom')
	{
		$response['st'] = 0;
		$denom = $link->real_escape_string(trim($_POST['denom']));
		$bstart = $link->real_escape_string(trim($_POST['bstart']));
		if(isset($_POST['faditem']))
		{
			$faditem = $link->real_escape_string(trim($_POST['faditem']));
		}
		else 
		{
			$faditem = '';
		}

		$link->autocommit(false);
        $denomcode = substr($bstart,0,2);
        $query = $link->query(
            "INSERT INTO 
                denomination
            (
                denom_code,
                denomination,
                denom_barcode_start,
                denom_status,
                denom_createdby,
                denom_datecreated,
                denom_type,
                denom_fad_item_number

            ) 
            VALUES 
            (
                '$denomcode',
                '$denom',
                '$bstart',
                'active',
                '".$_SESSION['gc_id']."',
                NOW(),
                'SIGC',
                '$faditem'
            )
        ");

        if($query)
        {
        	$last_insert = $link->insert_id;

        	// get all gc request denomination id

        	$query_getRequest = $link->query(
        		"SELECT 
					fds_denom_reqid 
				FROM 
					for_denom_set_up 
				WHERE
					fds_denom='$denom'
        	");

        	if($query_getRequest)
        	{
        		$reqid = [];
        		while ($row = $query_getRequest->fetch_object()) 
        		{
        			$reqid[] = $row->fds_denom_reqid;
        		}

        		$query_update = $link->query(
        			"UPDATE 
						for_denom_set_up 
					SET 
						fds_status='complete'
					WHERE 
						fds_denom='$denom'	
        		");

        		if($query_update)
        		{
        			$errorUpdate = false;
        			foreach ($reqid as $key => $value) {
        				$query_update_denid = $link->query(
        					"UPDATE	
								store_request_items 
							SET 
								sri_items_denomination='$last_insert'
							WHERE 
								sri_id='$value'
							AND
								sri_items_denomination='0'	
        				");

        				if(!$query_update_denid)
        				{
        					$errorUpdate = true;
        					break;
        				}
        			}

        			if(!$errorUpdate)
        			{
        				$link->commit();
        				$response['st'] = 1;
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
	elseif ($action=='checkRequisiton') 
	{
		$response['st']	= 0;	
		
		$id = trim($_POST['id']);

		$select = 'denomination.denomination';
		$where = "production_request_items.pe_items_request_id='".$id."'
			AND
				denomination.denom_fad_item_number='00000000'";
		$join = 'INNER JOIN
				denomination
			ON
				denomination.denom_id = production_request_items.pe_items_denomination'; 
		$gcs = getAllData($link,'production_request_items',$select,$where,$join,$limit = NULL);
		if(!empty($gcs))
		{
			$gchtml = '';
			foreach ($gcs as $gc) 
			{
				$gchtml.=number_format($gc->denomination,2).' ';
			}
			$response['msg'] = 'Contact Admin for '.$gchtml.'Denomination FAD Item Setup.';
		}
		else 
		{
			$response['st'] = 1;
		}

		echo json_encode($response);	
	}
	elseif ($action=='addEmployee') 
	{
		$response['st'] = false;
		$denom = $_POST['den'];
		$lastname = $_POST['lastname'];		
		$firstname = $_POST['firstname'];
		$middlename = $_POST['middlename'];
		$extname = $_POST['nameext'];
		$denid = $_POST['denid'];
		$reqid = $_POST['reqid'];
		$cnt = 0;

		$reqidExist = checkifExist2($link,'spexgc_num','special_external_gcrequest','spexgc_id','spexgc_status',$reqid,'pending');



		if(!$reqidExist)
		{
			$response['msg'] = 'Invalid request ID.';
		}
		else 
		{

			//check if request id and denom request
			$query = $link->query(
				"SELECT 
					specit_qty,
					specit_denoms
				FROM 
					special_external_gcrequest_items 
				WHERE 
					specit_trid='$reqid'
				AND
					specit_denoms='$denom'
			");

			if(!$query)
			{
				$response['msg'] = $link->error;
			}
			else 
			{
				$row = $query->fetch_object();

				$cnt = $row->specit_qty;
				$qty = 0;
				if(isset($_SESSION['empAssign']))
				{
					foreach ($_SESSION['empAssign'] as $key => $value) {
						if($value['denom']==$denom)
						{
							$qty++;
						}
					}

				}

				if($qty < $cnt)
				{
					if(isset($_SESSION['empAssign']))
					{
						$_SESSION['empAssign'][] = array("lastname"=>$lastname,"firstname"=>$firstname,"middlename"=>$middlename,"extname"=>$extname,"denom"=>$denom);
					}
					else 
					{			
						$_SESSION['empAssign'][] = array("lastname"=>$lastname,"firstname"=>$firstname,"middlename"=>$middlename,"extname"=>$extname,"denom"=>$denom);
					}

					$qty++;

					end($_SESSION['empAssign']);
					$key = key($_SESSION['empAssign']);

					$response['lastname'] = $lastname;
					$response['firstname']	= $firstname;
					$response['middlename'] = $middlename;
					$response['nameext'] = $extname;
					$response['key'] = $key;
					$response['denid'] = $denid;
					$response['qty'] = $qty; 
					$response['st'] = true;
					$response['cnt'] = $cnt;
				}
				else
				{
					$response['msg'] = 'Max';
				}
			}
		
		}

		echo json_encode($response);
	}
	elseif ($action=='deleteAssignByKey') 
	{
		$denom = $_POST['den'];
		$key = $_POST['key'];
		$response['st'] = false;
		unset($_SESSION['empAssign'][$key]);
		$qty = 0;
		foreach ($_SESSION['empAssign'] as $key => $value) {
			if($value['denom']==$denom)
			{
				$qty++;
			}
		}
		$response['qty'] = $qty;
		$response['st'] = true;

		echo json_encode($response);
	}
	elseif ($action=='deleteSessionKeyByDen') 
	{
		$response['st'] = 0;
		$den = $_POST['den'];
		if(isset($_SESSION['empAssign']))
		{
			foreach ($_SESSION['empAssign'] as $key => $value) 
			{
				if($value['denom'] == $den)
				{
					unset($_SESSION['empAssign'][$key]);
				}
			}
		}
		$response = 1;
		echo json_encode($response);
	}
	elseif ($action=='addexternalcustomervalidate') 
	{
		$response['st'] = 0;
		$company = $_POST['company'];

		//check if company already exist

		if(checkIfExist($link,'spcus_companyname','special_external_customer','spcus_companyname',$company))
		{
			$response['st'] = 1;
		}

		echo json_encode($company);
	}
	elseif ($action=='addexternalcustomer') 
	{
		$response['st'] = 0;
		$company = $link->real_escape_string($_POST['company']);
		$acctname = $link->real_escape_string($_POST['accname']);
		$address = $link->real_escape_string($_POST['address']);
		$person = $link->real_escape_string($_POST['contactp']);
		$contact = $link->real_escape_string($_POST['contactn']);

		$query = $link->query(
			"INSERT INTO 
				special_external_customer
			(
				spcus_companyname,
				spcus_acctname,
				spcus_address,
				spcus_cperson,
				spcus_cnumber,
				spcus_at,
				spcus_by
			) 
			VALUES 
			(
				'$company',
				'$acctname',
				'$address',
				'$person',
				'$contact',
				NOW(),
				'".$_SESSION['gc_id']."'
			)
		");

		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif($query)
		{
			$response['st'] = 1;
		}
		else 
		{
			$response['msg'] = $link->error;
		}

		echo json_encode($response);		
	}
	elseif($action=='promoRequest') 
	{
		$response['st'] = 0;
		$imageError = 0;
		$imagename = '';
		$hasdenom=false;
		$haspic = true;
		$dateneed = _dateFormatoSql($_POST['date_needed']);
		$prreq = getPromoGCRequestNo($link);
		$remarks = $_POST['remarks'];
		$totalrequest = $_POST['totpromoreq'];

		//get user promo tag

		$promo_tag = getField($link,'promo_tag','users','user_id',$_SESSION['gc_id']);

        if($_SESSION['gc_usertype']=='8')
        {
           $group = getField($link,'usergroup','users','user_id',$_SESSION['gc_id']);
        }
        else 
        {
        	$group = $_POST['group'];
        }

		foreach ($_POST as $key => $value) {
			if (strpos($key, 'denoms') !== false)
			{
				$qty = $value == '' ? 0 : str_replace(',','',$value);
				$denom_ids = substr($key, 6);
				if($qty>0)
				{
					$hasdenom = true;
				}
			} 
			//echo 'Key =>'.substr($key, 6).' Value =>'.$value;

		}

	    if($_FILES['docs']['error'][0]==4){
	      $haspic = false;
	    }

		if($haspic)
		{
			$image = checkDocuments($_FILES);

			$imageError = $image[0];
			$imagename = $image[1];
		}

		if($imageError)
		{
			$response['msg'] = 'Upload file type not allowed.';
		}
		elseif (empty($dateneed) || empty($prreq) || empty($remarks) || empty($totalrequest)) 
		{
			$response['msg'] = 'Please fill all required fields.';
		}
		elseif($hasdenom==false) 
		{
			$response['msg'] = 'Please fill at least one denomination quantity field.';
		}
		else 
		{
			$link->autocommit(FALSE);
			$query = $link->query(
				"INSERT INTO 
					promo_gc_request
				(
				    pgcreq_reqnum, 
				    pgcreq_reqby, 
				    pgcreq_datereq, 
				    pgcreq_dateneeded, 
				    pgcreq_doc, 
				    pgcreq_status, 
				    pgcreq_remarks, 
				    pgcreq_total,
				    pgcreq_group,
				    pgcreq_tagged
				) 
				VALUES 
				(
				    '$prreq',
				    '".$_SESSION['gc_id']."',
				    NOW(),
				    '$dateneed',
				    '$imagename',
				    'pending',
				    '$remarks',
				    '$totalrequest',
				    '$group',
				    '$promo_tag'
				)
			");

			if($query)
			{
				$errorDenom = false;
				$last_insert = $link->insert_id;
				foreach ($_POST as $key => $value) {
					if (strpos($key, 'denoms') !== false)
					{
						$qty = $value == '' ? 0 : str_replace(',','',$value);
						$denom_ids = substr($key, 6);
						if(!empty($qty))
						{
							$query_items = $link->query(
								"INSERT INTO 
									promo_gc_request_items
								(
								    pgcreqi_trid, 
								    pgcreqi_denom, 
								    pgcreqi_qty, 
								    pgcreqi_remaining
								) 
								VALUES 
								(
								    '$last_insert',
								    '$denom_ids',
								    '$qty',
								    '$qty'
								)
							");

							if(!$query_items)
							{
								$errorDenom = true;
								break;								
							} 						
						}

					} 
					//echo 'Key =>'.substr($key, 6).' Value =>'.$value;
				}


				if(!$errorDenom)
				{
					if($haspic)
					{
						if(move_uploaded_file($_FILES['docs']['tmp_name'][0], "assets/images/promoRequestFile/" . $imagename))
						{
							$response['st'] = 1;
							$link->commit();
						}
						else 
						{
							$response['msg'] = 'Error Uploading image.';
						}
					}
					else 
					{
						$response['st'] = 1;
						$link->commit();
					}
				}
				else 
				{
					$response['msg'] = 'Error Inserting denom request.';
				}

			}
			else 
			{
				$response['msg'] = $link->error;
			}
		}

		echo json_encode($response);
	}
	elseif ($action=='promoRequestupdate') 
	{
		$response['st'] = 0;
		$imageError = 0;
		$oldpic = true;
		$imagename = '';
		$hasdenom=false;
		$haspic = true;
		$totalrequest = $_POST['totpromoreq'];
		$countdateneed = substr_count($_POST['date_needed'], ',');
		$dateneed = _dateFormatoSql($_POST['date_needed']);
		$remarks = $_POST['remarks'];
		$imagenameold = $_POST['imgname'];
		$imagename = $_POST['imgname'];
		$reqid = $_POST['reqid'];
		$pegroup = isset($_POST['group']) ? $_POST['group'] : 0; 

		if(trim($imagename)=='')
		{
			$oldpic = false;
		}

		if($_FILES['docs']['error'][0]==4){
			$haspic = false;
		}

		if($haspic)
		{
			$img_arr = checkDocuments($_FILES);
			$imageError = $img_arr[0];
			$imagename = $img_arr[1];
		}

		if($countdateneed > 1)
		{
			$response['msg'] = 'Please input valid date needed.';
		}
		elseif (empty($remarks) || empty($dateneed) || empty($reqid) || empty($pegroup)) 
		{
			$response['msg'] = 'Please fill-up all <span class="requiredf">*</span> required fields.';
		}
		elseif ($imageError) 
		{
			$response['msg'] = 'Document file type not allowed';
		}
		else
		{
			$link->autocommit(FALSE);
			$query_up = $link->query(
				"UPDATE 
					promo_gc_request 
				SET 
					pgcreq_dateneeded='$dateneed',
					pgcreq_doc='$imagename',
					pgcreq_remarks='$remarks',
					pgcreq_total='$totalrequest',
					pgcreq_group='$pegroup',
					pgcreq_updateby='".$_SESSION['gc_id']."',
					pgcreq_updatedate=NOW()
				WHERE 
					pgcreq_id='".$reqid."'
				AND
					pgcreq_group_status=''
			");

			if(!$query_up)
			{
				$response['msg'] = $link->error;
			}
			elseif($link->affected_rows === 0)
			{
				$response['msg'] = 'Promo GC Request already approved/cancelled.';
			}
			else 
			{
				$hasErrorQuery = false;
				foreach ($_POST as $key => $value) {
					if (strpos($key, 'denoms') !== false)
					{
						$qty = $value == '' ? 0 : str_replace(',','',$value);
						$denom_ids = substr($key, 6);
						if(!empty($qty))
						{
							$hasdenom = true;
							if(checkifExist2($link,'pgcreqi_trid','promo_gc_request_items','pgcreqi_trid','pgcreqi_denom',$reqid,$denom_ids))
							{	
								if(!updatePromoRequestDenoms($link,$qty,$reqid,$denom_ids))
								{
									$hasErrorQuery = true;
									break;
								}
							}
							else 
							{
								if(!insertPromoRequestDenoms($link,$qty,$reqid,$denom_ids))
								{
									$hasErrorQuery = true;
									break;
								}
							}
						}
						else 
						{
							if(checkifExist2($link,'pgcreqi_trid','promo_gc_request_items','pgcreqi_trid','pgcreqi_denom',$reqid,$denom_ids))
							{
								if(deletePromoRequestItem($link,$reqid,$denom_ids))
								{
									$hasErrorQuery = true;
									break;
								}
							}
						}
					}
				}

				if(!$hasdenom)
				{
					$response['msg'] = 'Please input at least one denomination quantity field.';
				}
				elseif ($hasErrorQuery) 
				{
					$response['msg'] = $link->error;
				}
				else 
				{
					$errorUpload = false;
					$errorDelete = false;
					if($haspic)
					{
						if($oldpic)
						{
							if(!unlink('assets/images/productionRequestFile/'.$imagenameold))
							{
								$errorUpload = true;
							}
							else 
							{
								if(!move_uploaded_file($_FILES['docs']['tmp_name'][0], "assets/images/productionRequestFile/" . $imagename))
								{
									$errorUpload = true;
								}
							}
						}
					}

					if($errorDelete)
					{
						$response['msg'] = 'Problem deleting old file';
					}
					elseif($errorUpload)
					{
						$response['msg'] = 'File upload error.';
					}
					else 
					{
						$link->commit();
						$response['st'] = 1;
					}
				}
			}
		}



		echo json_encode($response);
	}
	elseif ($action=='promogcfinanceapproval') 
	{
		$response['st'] = 0;
		$imageError = 0;
		$imagename = '';
		$haspic = true;
		$approvedby = trim($_POST['approved']);
		$checkedby = trim($_POST['checked']);
		$status = trim($_POST['status']);
		$remarks = trim($_POST['remark']);
		$requestid = $_POST['requestid'];
 		$isApproved = numRowsWhereTwo($link,'promo_gc_request','pgcreq_id','pgcreq_id','pgcreq_group_status',$requestid,'approved');

		if($_FILES['docs']['error'][0]==4)
		{
			$haspic = false;
		}

		if($haspic)
		{
			$image = checkDocuments($_FILES);
			$imageError = $image[0];
			$imagename = $image[1];
		}

		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif(!$isApproved===1)
		{
			$response['msg'] ='Something went wrong.';
		}
		elseif(empty($status))
		{
			$response['msg'] ='Please select request status.';
		}
		else 
		{
			if($status=='1')
			{
				$currentbudget = currentBudget($link);
				$totalgc = totalGCPromoRequest($requestid,$link);
				if(!$currentbudget > $totalgc)
				{
					$response['msg'] = 'Total GC requested is bigger than current budget.';
				}	
				elseif(empty($remarks) || empty($checkedby) || empty($requestid)) 
				{
					$response['msg'] = 'Please fill-up all <span class="requiredf">*</span>required fields.';
				}			
				elseif ($imageError) 
				{
					$response['msg'] = 'Error Uploading image.';
				}
				else 
				{
					$link->autocommit(FALSE);
					$query_up = $link->query(
						"UPDATE 
							promo_gc_request 
						SET
							pgcreq_status = 'approved'
						WHERE
							pgcreq_id='$requestid'
						AND
							pgcreq_status = 'pending'
						AND
							pgcreq_group_status = 'approved'
					");

					if($query_up)
					{
						if($link->affected_rows >0)
						{
							$query_ins = $link->query(
								"INSERT INTO 
									approved_request
								(
								    reqap_trid, 
								    reqap_approvedtype, 
								    reqap_remarks, 
								    reqap_doc, 
								    reqap_checkedby,
								    reqap_approvedby, 
								    reqap_preparedby, 
								    reqap_date
								) 
								VALUES 
								(
								    '$requestid',
								    'promo gc approved',
								    '$remarks',
								    '$imagename',
								    '$checkedby',
								    '$approvedby',
								   	'".$_SESSION['gc_id']."',
								    NOW()
								)
							");

							if($query_ins)
							{
								$lnum = ledgerNumber($link);								

								$query_ledger = $link->query(
									"INSERT INTO 
										ledger_budget
									(
										bledger_no, 
										bledger_trid,
										bledger_datetime, 
										bledger_type, 
										bcredit_amt
									) 
									VALUES 
									(
										'$lnum',
										'$requestid',
										NOW(),
										'RFGCPROM',
										'$totalgc'
									)"
								);

								if($query_ledger)
								{
									if($haspic)
									{
										if(move_uploaded_file($_FILES['docs']['tmp_name'][0], "assets/images/promoRequestFile/" . $imagename))
										{
											$response['st'] = 1;
											$response['msg'] = 'Promo GC request Successfully Approved!';
											$link->commit();
										}
										else 
										{
											$response['msg'] = 'Error Uploading image.';
										}

									}
									else 
									{
										$response['st'] = 1;
										$response['msg'] = 'Promo GC request Successfully Approved!';
										$link->commit();
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
							$response['msg'] = 'Promo GC request already approved.';
						}
					}
					else 
					{
						$response['msg'] = $link->error;
					}
				}
				// get current budget

			}
			elseif ($status=='2') 
			{
			 	# code...
			}
		}
		echo json_encode($response);
	}
	elseif ($action=='removeSessionPromo') 
	{
		if(isset($_SESSION['scannedPromo']))
		unset($_SESSION['scannedPromo']);
	}
	elseif ($action=='specialgcfinanceapproval')
	{
		// check 
		$reqid = $_POST['requestid'];

		// get request type
		$reqtype = getField($link,'spexgc_type','special_external_gcrequest','spexgc_id',$reqid);

		$response['st'] = 0;
		$imageError = 0;
		$imagename = '';
		$haspic = true;
		$approvedby = $link->real_escape_string(trim($_POST['approved']));
		$checkedby = $link->real_escape_string(trim($_POST['checked']));
		$status = $link->real_escape_string(trim($_POST['status']));
		$remarks = $link->real_escape_string(trim($_POST['remark']));

		if($_FILES['docs']['error'][0]==4)
		{
			$haspic = false;
		}

		if($haspic)
		{
			$image = checkDocuments($_FILES);
			$imageError = $image[0];
			$imagename = $image[1];
		}


		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif(empty($status))
		{
			$response['msg'] ='Please select request status.';
		}
		elseif($status==1)
		{
			$currentbudget = currentBudget($link);
			$totaldenom = totalExternalRequest($link,$reqid)[0];
			if($totaldenom > $currentbudget)
			{
				$response['msg'] = 'Total Denomination requested is bigger than current budget.';
			}
			elseif(empty($remarks) || empty($checkedby) || empty($reqid) || empty($approvedby)) 
			{
				$response['msg'] = 'Please fill-up all <span class="requiredf">*</span>required fields.';
			}
			elseif ($imageError) 
			{
				$response['msg'] = 'Error Uploading image.';
			}
			else 
			{
				$link->autocommit(FALSE);
				$query_update = $link->query(
					"UPDATE 
						special_external_gcrequest 
					SET 
						spexgc_status='approved'
					WHERE 
						spexgc_id='".$reqid."'
					AND
						spexgc_status='pending'
				");

				if($query_update)
				{
					if($link->affected_rows >0)
					{
						$query_ins = $link->query(
							"INSERT INTO 
								approved_request
							(
							    reqap_trid, 
							    reqap_approvedtype, 
							    reqap_remarks, 
							    reqap_doc, 
							    reqap_checkedby, 
							    reqap_approvedby, 
							    reqap_preparedby, 
							    reqap_date
							) 
							VALUES 
							(
							    '$reqid',
							    'Special External GC Approved',
							    '$remarks',
							    '$imagename',
							    '$checkedby',
							    '$approvedby',
							    '".$_SESSION['gc_id']."',
							    NOW()
							)
						");

						if($query_ins)
						{
							$lnum = ledgerNumber($link);								

							$query_ledger = $link->query(
								"INSERT INTO 
									ledger_budget
								(
									bledger_no, 
									bledger_trid,
									bledger_datetime, 
									bledger_type, 
									bcredit_amt
								) 
								VALUES 
								(
									'$lnum',
									'$reqid',
									NOW(),
									'RFGCSEGC',
									'$totaldenom'
								)"
							);

							if($query_ledger)
							{
								// create barcode							

								//get all
								$queryError = false;

								$barcode = generateSpecialGCBarcode($link);
								$flag = false;

								if($reqtype==2)
								{
									$table = 'special_external_gcrequest_emp_assign';
									$select = 'spexgcemp_id';
									$where = "spexgcemp_trid='".$reqid."'
										AND
											spexgcemp_barcode='0'";
									$join = '';
									$limit = 'ORDER BY
											spexgcemp_id
										ASC';
									$special = getAllData($link,$table,$select,$where,$join,$limit);

									if(count($special) > 0)
									{
										$queryError = false;
										foreach ($special as $s) 
										{			
											$query_upbar = $link->query(
												"UPDATE 
													special_external_gcrequest_emp_assign 
												SET 
													spexgcemp_barcode='$barcode'
												WHERE 
													spexgcemp_trid='$reqid'
												AND
													spexgcemp_id='$s->spexgcemp_id'
											");

											if(!$query_upbar)
											{
												$queryError = true;
												break;
											}

											$barcode++;
										}
									}
									else
									{
										$msg = 'No GC to setup Barcode.';
										$flag = true;
									}
								}
								elseif($reqtype==1)
								{
									$table = 'special_external_gcrequest_items';
									$select = 'specit_denoms,
										specit_qty';
									$where = "specit_trid='".$reqid."'";
									$join = '';
									$limit = 'ORDER BY
											specit_id
										ASC';
									$denoms = getAllData($link,$table,$select,$where,$join,$limit);		

									foreach ($denoms as $d) 
									{
										for ($i=1; $i<=$d->specit_qty ; $i++) 
										{ 
											$query_insert = $link->query(
												"INSERT INTO 
													special_external_gcrequest_emp_assign
												(
												    spexgcemp_trid, 
												    spexgcemp_denom,
												    spexgcemp_barcode 
												) 
												VALUES 
												(
												    '$reqid',
												    '$d->specit_denoms',
												    '$barcode'
												)
											");

											if(!$query_insert)
											{
												$queryError = true;
												break;
											}

											$barcode++;

										}
									}							
								}
								else 
								{
									$msg = 'Request Type not found.';
									$flag = true;
								}

								if($queryError) 
								{
									$response['msg'] = $link->error;
								}
								elseif($flag)
								{
									$response['msg'] = $msg;
								}
								else 
								{
									$errorUpload = false;
									//echo $imageError;
									if($haspic && !$imageError)
									{
										if(!move_uploaded_file($_FILES['docs']['tmp_name'][0], "assets/images/externalDocs/" . $imagename))
										{
											$errorUpload = true;
										}
									}

									if($errorUpload)
									{
										$response['msg'] = 'Error Uploading Image.';
									}
									else 
									{
										$link->commit();
										$response['st'] = 1;
										$response['reqid'] = $reqid;
										$response['msg'] = 'Request Successfully Approved.';
									}
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
						$response['msg'] = 'Request already approved/cancelled.';
					}
				}
				else
				{
					$response['msg'] = $link->error;
				}
			}
		}
		elseif ($status===2) 
		{
		}
		echo json_encode($response);
	}
	elseif($action=='gcreviewscangc')
	{
		$response['st'] = 0;
		$trid = $_POST['trid'];
		$barcode = $_POST['barcode'];

		$table ='special_external_gcrequest_emp_assign';
		$select = 'special_external_gcrequest_emp_assign.spexgcemp_denom,
			special_external_gcrequest_emp_assign.spexgcemp_fname,
			special_external_gcrequest_emp_assign.spexgcemp_lname,
			special_external_gcrequest_emp_assign.spexgcemp_mname,
			special_external_gcrequest_emp_assign.spexgcemp_extname,
			special_external_gcrequest_emp_assign.spexgcemp_barcode,
			special_external_gcrequest_emp_assign.spexgcemp_review,
			special_external_gcrequest_emp_assign.spexgcemp_id,
			special_external_gcrequest.spexgc_status,
			special_external_gcrequest.spexgc_id';
		$where = "special_external_gcrequest_emp_assign.spexgcemp_trid='".$trid."'
			AND
				special_external_gcrequest_emp_assign.spexgcemp_review=''
			AND
				special_external_gcrequest_emp_assign.spexgcemp_barcode='".$barcode."'
			AND
				special_external_gcrequest.spexgc_status='approved'";
		$join = 'INNER JOIN
				special_external_gcrequest
			ON
				special_external_gcrequest.spexgc_id = special_external_gcrequest_emp_assign.spexgcemp_trid';
		$limit = '';

		$gc = getSelectedData($link,$table,$select,$where,$join,$limit);

		if(!count($gc)> 0)
		{
			$response['msg'] = 'GC Barcode # '.$barcode.' not found.';
		}
		elseif (!empty($gc->spexgcemp_review)) 
		{
			$response['msg'] = 'GC Barcode # '.$barcode.' already review.';
		}
		elseif ($gc->spexgc_status!='approved')	
		{
			$response['msg'] = 'GC Barcode # '.$barcode.' GC request is still pending.';
		}
		else 
		{
			$alreadyScanned = false;
			if(isset($_SESSION['scanReviewGC']))
			{
				foreach ($_SESSION['scanReviewGC'] as $key => $value) 
				{
					if($value['barcode']==$barcode)
					{
						$alreadyScanned = true;
						break;
					}
				}
			}

			if(!$alreadyScanned)
			{
				//check if session exist			
				if(isset($_SESSION['scanReviewGC']))
				{
					$_SESSION['scanReviewGC'][] = array("lastname"=>$gc->spexgcemp_lname,"firstname"=>$gc->spexgcemp_fname,"middlename"=>$gc->spexgcemp_mname,"extname"=>$gc->spexgcemp_extname,"denom"=>$gc->spexgcemp_denom,"barcode"=>$gc->spexgcemp_barcode,"trid"=>$trid,"gcid"=>$gc->spexgcemp_id);
				}
				else
				{
					$_SESSION['scanReviewGC'][] = array("lastname"=>$gc->spexgcemp_lname,"firstname"=>$gc->spexgcemp_fname,"middlename"=>$gc->spexgcemp_mname,"extname"=>$gc->spexgcemp_extname,"denom"=>$gc->spexgcemp_denom,"barcode"=>$gc->spexgcemp_barcode,"trid"=>$trid,"gcid"=>$gc->spexgcemp_id);
				}
				$total = 0;
				foreach ($_SESSION['scanReviewGC'] as $key => $value) 
				{
					$total+=$value['denom'];
				}
				$response['gccount'] = count($_SESSION['scanReviewGC']);
				$response['total'] = $total;
				$response['st'] = 1;
				$response['firstname'] = $gc->spexgcemp_fname;
				$response['lastname'] = $gc->spexgcemp_lname;
				$response['middlename'] = $gc->spexgcemp_mname;
				$response['nameext'] = $gc->spexgcemp_extname;
				$response['barcode'] = $gc->spexgcemp_barcode;
				$response['denomination'] = $gc->spexgcemp_denom;
				$response['msg'] = 'Successfully Scanned.';
			}
			else 
			{
				$response['msg'] = 'GC Barcode # '.$barcode.' already scanned.';
			}
		}
		echo json_encode($response);
	}
	elseif ($action=='gcreviewCheckScanned') 
	{
		$response['st']=0;
		$trid = $_POST['trid'];
		$gcCount = 0;
		if(isset($_SESSION['scanReviewGC']))
		{
			$gcCount = count($_SESSION['scanReviewGC']);
		}

		//check if trid exist

		if(empty($trid))
		{
			$response['msg'] = 'GC Request not found.';
		}
		elseif (numRows($link,'special_external_gcrequest','spexgc_id',$trid)==0) 
		{
			$response['msg'] = 'GC Request not found.';
		}
		elseif ($gcCount > 0)
		{			
			//$response['st'] = 1;
			$gc = numRows($link,'special_external_gcrequest_emp_assign','spexgcemp_trid',$trid);
			if($gc == $gcCount)
			{
				$response['st'] = 1;
			}
			else 
			{
				$response['msg'] = 'Please scan all GC.';
			}
		}
		else 
		{
			//
			$response['msg'] = 'Please scan GC.';
		} 

		echo json_encode($response);
	}
	elseif ($action=='gcreview') 
	{
		$response['st'] = 0;
		$trid = $_POST['trid'];
		$remarks = $_POST['remarks'];
		//check if already reviewed

		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif(numRowsWhereTwo($link,'approved_request','reqap_id','reqap_trid','reqap_approvedtype',$trid,'special external gc review')>0)
		{
			$response['msg'] = 'GC Request already reviewed.';
		}
		else 
		{
			if(isset($_SESSION['scanReviewGC']))
			{
				$link->autocommit(FALSE);

				$query_updatereq = $link->query(
					"UPDATE 
						special_external_gcrequest 
					SET 
						spexgc_reviewed='reviewed' 
					WHERE 
						spexgc_id='$trid'
				");

				if($query_updatereq)
				{
					if($link->affected_rows >0)
					{
						$query = $link->query(
							"INSERT INTO 
								approved_request
							(
							    reqap_trid, 
							    reqap_remarks,
							    reqap_approvedtype,
							    reqap_date,
								reqap_preparedby
							) 
							VALUES 
							(
							    '$trid',
							    '$remarks',
							    'special external gc review',
							    NOW(),
							    '".$_SESSION['gc_id']."'

							)
						");

						if($query)
						{
							$errorUpdate = false;
							foreach ($_SESSION['scanReviewGC'] as $key => $value) 
							{
								if($value['trid']==$trid)
								{
									$query_up = $link->query(
										"UPDATE 
											special_external_gcrequest_emp_assign 
										SET 
											spexgcemp_review='*'
										WHERE 
											spexgcemp_trid='".$value['trid']."'
										AND
											spexgcemp_id='".$value['gcid']."'
									");

									if(!$query_up)
									{
										$errorUpdate = true;
										break;
									}
								}
							}

							if($errorUpdate)
							{
								$response['msg'] = $link->error;
							}
							else 
							{
								$link->commit();
								$response['st'] = 1;
								$response['msg'] = 'Request successfully reviewed.';
							}
						}
						else 
						{
							$response['msg'] = $link->error;
						}
					}
					else 
					{
						$response['msg'] = 'Request already reviewed.';
					}
				}
				else 
				{
					$response['msg'] = $link->error;
				}
			}
			else 
			{
				$response['msg'] = 'Please scan GC.';
			}
		}
		echo json_encode($response);
	}
	elseif ($action=='specialgcreleasing') 
	{
		$response['st'] = 0;
		$receivedby = $_POST['receiver'];
		$trid = $_POST['trid'];
		$remarks = $_POST['remarks'];
		$checkedby = $_POST['checked'];

		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif(empty($receivedby))
		{
			$response['msg'] = 'Please input received by textbox.';
		}
		elseif(empty($checkedby))
		{
			$response['msg'] = 'Please select checked by.';
		}
		else 
		{
			$link->autocommit(FALSE);
			$query_up = $link->query(
				"UPDATE 
					special_external_gcrequest 
				SET 
					spexgc_released='released',
					spexgc_receviedby='$receivedby' 
				WHERE 
					spexgc_id='$trid'
				AND
					spexgc_released=''	
			");

			if($query_up)
			{
				if($link->affected_rows >0)
				{

					// check for released number

					$relid = generateSpecialGCReleasingNo($link);

					$query_ins = $link->query(
						"INSERT INTO 
							approved_request
						(
						    reqap_trid, 
						    reqap_approvedtype, 
						    reqap_remarks, 
						    reqap_preparedby, 
						    reqap_date,
						    reqap_trnum,
						    reqap_checkedby
						) 
						VALUES 
						(
						    '$trid',
						    'special external releasing',
						    '$remarks',
						    '".$_SESSION['gc_id']."',
						    NOW(),
						    '$relid',
						    '$checkedby'
						)
					");

					if($query_ins)
					{
						$totaldenom = totalExternalRequest($link,$trid)[0];
						$lnum = ledgerNumber($link);

						$query_insled = $link->query(
							"INSERT INTO 
								ledger_budget
							(
							    bledger_no, 
							    bledger_trid, 
							    bledger_datetime, 
							    bledger_type, 
							    bdebit_amt
							) 
							VALUES 
							(
							    '$lnum',
							    '$trid',
							    NOW(),
							    'RFGCSEGCREL',
							    '$totaldenom'
							)
						");

						if($query_insled)
						{
							$link->commit();
							$response['st'] = 1;
							$response['trid'] = $trid;
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
					$response['msg'] = "Special External GC Already Released.";
				}
			}
			else 
			{
				$response['msg'] = $link->error;
			}
		}
		echo json_encode($response);
	}
	elseif ($action=='specialExternalGCRequestUpdate') 
	{
		$response['st'] = 0;
		$imageError = 0;
		$haspic = true;
		$toRemoved = false;
		$reqid = $link->real_escape_string($_POST['reqid']);
		$countdateneed = substr_count($_POST['date_needed'], ',');
		$companyid = $link->real_escape_string($_POST['companyid']);
		$payment = $link->real_escape_string($_POST['paymenttype']);
		$amount = $link->real_escape_string(str_replace(',','',$_POST['amount']));
		$dateneed = $link->real_escape_string(_dateFormatoSql($_POST['date_needed']));
		$remarks = $link->real_escape_string($_POST['remarks']);
		$reqnum = $_POST['reqnum'];

		$bankname = $link->real_escape_string($_POST['bankname']);
		$cnumber = $link->real_escape_string($_POST['cnumber']);
		$cnt = 0;

		$reqtype = $link->real_escape_string($_POST['reqtype']);


		foreach ($_FILES['docs']['name'] as $key) {
			if($key!='')
			{
				$cnt++;
			}
		}

		//$response['msg'] = print_r($_FILES['docs']['name']);
		if($cnt == 0)
		{
			$haspic = false;
		}

		if($haspic)
		{
			$imageError = checkDocumentsMutiple($_FILES);
		}

		if(isset($_POST['images']))
		{
			$toRemoved = true;
			// foreach ($_POST['images'] as $i) {
			// 	echo $i;
			// }			
		}

		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif($reqtype==2)
		{

			if(!checkIfExist($link,'spcus_id','special_external_customer','spcus_id',$companyid))
			{
				$response['msg'] = 'Company dont exist.';
			}
			elseif (count($_POST['ninternalcusd']) == 0) 
			{
				$response['msg'] = 'Please add denomination.';
			}
			elseif (count($_SESSION['empAssign'])==0) 
			{
				$response['msg'] = 'Please assign customer employee.';
			}
			elseif($countdateneed > 1)
			{
				$response['msg'] = 'Invalid Date Needed.';
			}
			elseif (empty($dateneed) || empty($companyid) || empty($payment) || empty($remarks)) 
			{
				$response['msg'] = 'Please fill all required fields.';
			}
			else 
			{
				$link->autocommit(FALSE);
				$query_update = $link->query(
					"UPDATE 
						special_external_gcrequest 
					SET 
						spexgc_dateneed='$dateneed',
						spexgc_remarks='$remarks',
						spexgc_company='$companyid',
						spexgc_payment='$amount',
						spexgc_paymentype='$payment',
						spexgc_updatedby='".$_SESSION['gc_id']."',
						spexgc_updated_at=NOW()
					WHERE 
						spexgc_id='$reqid'
					AND
						spexgc_status='pending'
				");
				
				if($query_update)
				{
					if($link->affected_rows >0)
					{
						$queryError = false;
						if($payment==1)
						{
							// check last request payment type
							$query_del = $link->query(
								"DELETE 
								FROM 
									special_external_bank_payment_info 
								WHERE 
									spexgcbi_trid='$reqid'
							");

							if(!$query_del)
							{
								$queryError = true;
							}
						}
						elseif($payment==2)
						{
							// check last request payment type
							$query_check_ba = $link->query(
								"SELECT 
									spexgcbi_trid 
								FROM 
									special_external_bank_payment_info 
								WHERE 
									spexgcbi_trid = '".$reqid."'
							");

							if($query_check_ba)
							{
								if($query_check_ba->num_rows > 0)
								{
									$query_update_ba = $link->query(
										"UPDATE 
											special_external_bank_payment_info
										SET 
											spexgcbi_bankname='$bankname',
											spexgcbi_checknumber='$cnumber'
										WHERE 
											spexgcbi_trid='$reqid'
									");

									if(!$query_update_ba)
									{
										$queryError = true;
									}
								}
								else 
								{
									$query_ins_ba = $link->query(
										"INSERT INTO 
											special_external_bank_payment_info
										(
											spexgcbi_trid,
										    spexgcbi_bankname, 
										    spexgcbi_bankaccountnum, 
										    spexgcbi_checknumber
										) 
										VALUES 
										(
											'$reqid',
										    '$bankname',
										    '$bankaccount',
										    '$cnumber'
										)
									");

									if(!$query_ins_ba)
									{
										$queryError = true;
									}
								}
							}
							else 
							{
								$queryError = true;
							}
						}

						if($queryError)
						{
							$response['msg'] = $link->error;
						}
						else 
						{
							if(!isset($_SESSION['empAssign']))
							{
								$response['msg'] = 'Request denomination/customer is empty.';
							}
							else 
							{
								//delete first
								$query_del = $link->query(
									"DELETE FROM 
										special_external_gcrequest_emp_assign 
									WHERE 
										spexgcemp_trid='$reqid'
								");

								if(!$query_del)
								{
									$queryError = true;
								}
								else 
								{
									foreach ($_SESSION['empAssign'] as $key => $value) 
									{
																	
										$query_emp = $link->query(
											"INSERT INTO 
												special_external_gcrequest_emp_assign
											(
											    spexgcemp_trid, 
											    spexgcemp_denom, 
											    spexgcemp_fname, 
											    spexgcemp_lname, 
											    spexgcemp_mname, 
											    spexgcemp_extname 
											) 
											VALUES 
											(
											    '$reqid',
											    '".$link->real_escape_string($value['denom'])."',
											    '".$link->real_escape_string($value['firstname'])."',
											    '".$link->real_escape_string($value['lastname'])."',
											    '".$link->real_escape_string($value['middlename'])."',
											    '".$link->real_escape_string($value['extname'])."'								    
											)
										");

										if(!$query_emp)
										{	
											$queryError = true;
											break;
										}	
									}
								}
							}


						}

						if($queryError)
						{
							$response['msg'] = $link->error;
						}
						else 
						{
							if($imageError)
							{
								$response['msg'] = 'Invalid documents.';
							}
							else
							{
								$errorUpload = false;
								if($haspic)
								{
									$pathfolder = 'externalDocs';
									for($i=0; $i < count($_FILES['docs']['name']); $i++) 
									{
										$imagename = externalDocumentFilename($external = $_FILES['docs']['name'][$i],$i,$reqnum);
										if(move_uploaded_file($_FILES['docs']['tmp_name'][$i], "assets/images/".$pathfolder."/".$imagename))
										{
											$fullpathfolder = $pathfolder.'/'.$imagename;
											$query_files = $link->query(
												"INSERT INTO 
													documents
												(
												    doc_trid, 
												    doc_type, 
												    doc_fullpath
												) 
												VALUES 
												(
												    '$reqid',
												    'Special External GC Request',
												    '$fullpathfolder'
												)
											");

											if(!$query_files)
											{
												$queryError = true;
											}
										}
										else 
										{
											$errorUpload = true;
											break;
										}
									}

								} // end haspic

								if($errorUpload)
								{
									$response['msg'] = 'Error Uploading Files.';
								}
								elseif($queryError)
								{
									$response['msg'] = $link->error;
								}
								else
								{
									$errorDelete = false;
									if($toRemoved)
									{
										foreach ($_POST['images'] as $i) {
											$query_del = $link->query(
												"DELETE FROM 
													documents 
												WHERE 
													doc_fullpath='$i'
												AND
													doc_type='Special External GC Request'
											");

											if($query_del)
											{

												if(!unlink('assets/images/'.$i))
												{
													$errorDelete = true;
													break;
												}
											}
											else 
											{
												$queryError = true;
												break;
											}

										}

									}

									if($errorDelete)
									{
										$response['msg'] = 'Error Deleting Files.';
									}
									elseif($queryError)
									{
										$response['msg'] = $link->error;
									}
									else
									{
										$link->commit();
										$response['st'] = 1;
									}
								}
							}
						}
						
					}
					else 
					{
						$response['msg'] = 'Request already Approved/Cancelled.';
					}
				}
				else 
				{
					$response['msg'] = $link->error;
				}
			}
		}
		elseif ($reqtype==1) 
		{
			if(!checkIfExist($link,'spcus_id','special_external_customer','spcus_id',$companyid))
			{
				$response['msg'] = 'Company dont exist.';
			}
			elseif (count($_POST['ninternalcusd']) == 0) 
			{
				$response['msg'] = 'Please add denomination.';
			}
			elseif($countdateneed > 1)
			{
				$response['msg'] = 'Invalid Date Needed.';
			}
			elseif (empty($dateneed) || empty($companyid) || empty($payment) || empty($remarks)) 
			{
				$response['msg'] = 'Please fill all required fields.';
			}
			elseif (!isset($_POST['ninternalcusd'])|| !isset($_POST['ninternalcusq'])) 
			{
				$response['msg'] = 'Please add denomination/quantity.';
			}
			else 
			{

				$link->autocommit(FALSE);
				$query_update = $link->query(
					"UPDATE 
						special_external_gcrequest 
					SET 
						spexgc_dateneed='$dateneed',
						spexgc_remarks='$remarks',
						spexgc_company='$companyid',
						spexgc_payment='$amount',
						spexgc_paymentype='$payment',
						spexgc_updatedby='".$_SESSION['gc_id']."',
						spexgc_updated_at=NOW()
					WHERE 
						spexgc_id='$reqid'
					AND
						spexgc_status='pending'
				");
								
				if($query_update)
				{
					if($link->affected_rows >0)
					{
						$queryError = false;
						if($payment==1)
						{
							// check last request payment type
							$query_del = $link->query(
								"DELETE 
								FROM 
									special_external_bank_payment_info 
								WHERE 
									spexgcbi_trid='$reqid'
							");

							if(!$query_del)
							{
								$queryError = true;
							}
						}
						elseif($payment==2)
						{
							// check last request payment type
							$query_check_ba = $link->query(
								"SELECT 
									spexgcbi_trid 
								FROM 
									special_external_bank_payment_info 
								WHERE 
									spexgcbi_trid = '".$reqid."'
							");

							if($query_check_ba)
							{
								if($query_check_ba->num_rows > 0)
								{
									$query_update_ba = $link->query(
										"UPDATE 
											special_external_bank_payment_info
										SET 
											spexgcbi_bankname='$bankname',
											spexgcbi_bankaccountnum='$bankaccount',
											spexgcbi_checknumber='$cnumber'
										WHERE 
											spexgcbi_trid='$reqid'
									");

									if(!$query_update_ba)
									{
										$queryError = true;
									}
								}
								else 
								{
									$query_ins_ba = $link->query(
										"INSERT INTO 
											special_external_bank_payment_info
										(
											spexgcbi_trid,
										    spexgcbi_bankname, 
										    spexgcbi_bankaccountnum, 
										    spexgcbi_checknumber
										) 
										VALUES 
										(
											'$reqid',
										    '$bankname',
										    '$bankaccount',
										    '$cnumber'
										)
									");

									if(!$query_ins_ba)
									{
										$queryError = true;
									}
								}
							}
							else 
							{
								$queryError = true;
							}
						}

						// delete all items 

						$query_del = $link->query(
							"DELETE FROM 
								special_external_gcrequest_items 
							WHERE 
								specit_trid='$reqid'
						");

						if(!$query_del)
						{
							$response['msg'] = $link->error;
						}
						else 
						{
							$queryError = false;
							$index = 0;
							foreach ($_POST['ninternalcusd'] as $key) 
							{
								$denom = str_replace(',', '', $key);
								$qty = str_replace(',', '', $_POST['ninternalcusq'][$index]);
								$query_ins = $link->query(
									"INSERT INTO 
										special_external_gcrequest_items
									(
									    specit_denoms, 
									    specit_qty, 
									    specit_trid
									) 
									VALUES 
									(
									    '$denom',
									    '$qty',
									    '$reqid'
									)
								");

								if(!$query_ins)
								{
									$queryError = true;
									break;
								}

								$index++;
							}

							if($queryError)
							{
								$response['msg'] = $link->error;
							}
							else 
							{
								if($imageError)
								{
									$response['msg'] = 'Invalid documents.';
								}
								else
								{
									$errorUpload = false;
									if($haspic)
									{
										$pathfolder = 'externalDocs';
										for($i=0; $i < count($_FILES['docs']['name']); $i++) 
										{
											$imagename = externalDocumentFilename($external = $_FILES['docs']['name'][$i],$i,$reqnum);
											if(move_uploaded_file($_FILES['docs']['tmp_name'][$i], "assets/images/".$pathfolder."/".$imagename))
											{
												$fullpathfolder = $pathfolder.'/'.$imagename;
												$query_files = $link->query(
													"INSERT INTO 
														documents
													(
													    doc_trid, 
													    doc_type, 
													    doc_fullpath
													) 
													VALUES 
													(
													    '$reqid',
													    'Special External GC Request',
													    '$fullpathfolder'
													)
												");

												if(!$query_files)
												{
													$queryError = true;
												}
											}
											else 
											{
												$errorUpload = true;
												break;
											}
										}

									} // end haspic

									if($errorUpload)
									{
										$response['msg'] = 'Error Uploading Files.';
									}
									elseif($queryError)
									{
										$response['msg'] = $link->error;
									}
									else
									{
										$errorDelete = false;
										if($toRemoved)
										{
											foreach ($_POST['images'] as $i) {
												$query_del = $link->query(
													"DELETE FROM 
														documents 
													WHERE 
														doc_fullpath='$i'
													AND
														doc_type='Special External GC Request'
												");

												if($query_del)
												{

													if(!unlink('assets/images/'.$i))
													{
														$errorDelete = true;
														break;
													}
												}
												else 
												{
													$queryError = true;
													break;
												}

											}

										}

										if($errorDelete)
										{
											$response['msg'] = 'Error Deleting Files.';
										}
										elseif($queryError)
										{
											$response['msg'] = $link->error;
										}
										else
										{
											$link->commit();
											$response['st'] = 1;
										}
									}
								}								
							}

						}

					}
					else 
					{
						$response['msg'] = 'Request already Approved/Cancelled.';
					}
				}
				else 
				{
					$response['msg'] = $link->error;
				}
			}
		}
		else 
		{
			$response['msg'] = 'Request type is invalid.';
		} 

		echo json_encode($response);
	}
	elseif ($action=='budgetApproval') 
	{
		$response['st'] = 0;
		$reqid = $_POST['reqid'];
		if(empty($reqid))
		{
			$response['msg'] = 'Something went wrong. Request id is empty.'; 
		}
		else 
		{
			//get budget request first
			$query_select = $link->query(
				"SELECT 
					br_request 
				FROM 
					budget_request 
				WHERE 
					br_id='".$reqid."'
				AND
					br_request_status='0'
			");

			if($query_select)
			{
				if($query_select->num_rows > 0)
				{
					$link->autocommit(FALSE);

					$row = $query_select->fetch_object();
					$budget = $row->br_request;

					$lnum = ledgerNumber($link);
					$query_ledger = $link->query(
						"INSERT INTO 
							ledger_budget
						(
						    bledger_no, 
						    bledger_trid,
						    bledger_datetime, 
						    bledger_type, 
						    bdebit_amt
						) 
						VALUES 
						(
						    '$lnum',
						    '$reqid',
						    NOW(),
						    'RFBR',
						    '$budget'
						)							
					");

					if($query_ledger)
					{

						$approvedby = getUserFullname($link,$_SESSION['gc_id']);

						$query_approved = $link->query(
							"INSERT INTO 
								approved_budget_request
							(
								abr_budget_request_id, 
								abr_approved_by,
								abr_approved_at, 
								abr_ledgerefnum
							) 
							VALUES 
							(
								'$reqid',
								'$approvedby',
								NOW(),
								'$lnum'
							)
						");

						if($query_approved)
						{
							$query_update_app = $link->query(
								"UPDATE 
									`budget_request` 
								SET 
									`br_request_status`='1' 
								WHERE 
									`br_id` = '$reqid'
								AND
									`br_request_status`='0'
							");

							if($query_update_app)
							{
								if($link->affected_rows >0)
								{
										$response['st'] = 1;
										$response['msg'] = 'Budget Request Successfully Approved!';
										$link->commit();
								}
								else 
								{
									$response['msg'] = 'Budget already approved/cancelled.';
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
				}
				else 
				{
					$response['msg'] = 'Budget Request already approved/cancelled.';
				}

			}
			else
			{
				$response['msg'] = $link->error;
			}
		}
		echo json_encode($response);
	}
	elseif($action=='addTresuryCustomer')
	{
		$response['st'] = 0;
		$companyname = $_POST['company'];
		$customertype = $_POST['ctype'];		

		if(isset($_POST['gctype']))
		{
			$gctype = $_POST['gctype'];
		}
		else 
		{
			$gctype = '';
		}

		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Session already expired. Please reload to login.';
		}
		else 
		{
			if(!empty($companyname) && !empty($customertype))
			{
				// check if company already exist
				if(!checkIfExist($link,'ins_name','institut_customer','ins_name',$companyname))
				{
					$response['st'] = 1;
				}
				else 
				{
					$response['msg'] = 'Customer already Exist.';
				}

			}
			else 
			{
				$response['msg'] = 'Please input customer.';
			}			
		}



		echo json_encode($response);
	}
	elseif($action=='addTresuryCustomerSave') 
	{
		$response['st'] = 0;

		$company = $_POST['company'];
		$customertype = $_POST['ctype'];		

		if(isset($_POST['gctype']))
		{
			$gctype = $_POST['gctype'];
		}
		else 
		{
			$gctype = '';
		}

		$query = $link->query(
			"INSERT INTO 
				institut_customer
			(
			    ins_name, 
			    ins_status, 
			    ins_date_created, 
			    ins_by,
			    ins_custype,
			    ins_gctype
			) 
			VALUES 
			(
			    '$company',
			   	'active',
			    NOW(),
			    '".$_SESSION['gc_id']."',
			    '$customertype',
			    '$gctype'
			)
		");
		
		if($query)
		{
			$response['st'] = 1;
		}
		else 
		{
			$response['msg'] = $link->error;
		}
		echo json_encode($response);
	}
	elseif ($action=='scanGCForCustomerReleasing') 
	{
		$response['st'] = 0;

		$barcode = trim($_POST['barcode']);

		$table = 'gc';
		$select = 'gc.barcode_no,
			gc.gc_ispromo,
			custodian_srr_items.cssitem_barcode,
			promo_gc_release_to_items.prreltoi_barcode,
			gc_location.loc_barcode_no,
			institut_transactions_items.instituttritems_barcode,
			denomination.denomination';
		$where = "gc.barcode_no = '$barcode'";
		$join = 'LEFT JOIN
				custodian_srr_items
			ON
				custodian_srr_items.cssitem_barcode = gc.barcode_no
			LEFT JOIN
				promo_gc_release_to_items
			ON
				promo_gc_release_to_items.prreltoi_barcode = gc.barcode_no
			LEFT JOIN
				gc_location
			ON
				gc_location.loc_barcode_no = gc.barcode_no
			LEFT JOIN
				institut_transactions_items
			ON
				institut_transactions_items.instituttritems_barcode = gc.barcode_no
			LEFT JOIN
				denomination
			ON
				denomination.denom_id = gc.denom_id';
		$limit = '';

		$gc = getSelectedData($link,$table,$select,$where,$join,$limit);

		//var_dump($gc);

		if(count($gc)==0)
		{
			$response['msg'] = 'Barcode # '.$barcode.' not found.';
		}
		elseif(empty($gc->cssitem_barcode))
		{
			$response['msg'] = 'Barcode # '.$barcode.' needs validation.';
		}
		elseif(!empty($gc->loc_barcode_no))
		{
			$response['msg'] = 'Barcode # '.$barcode.' already allocated.';
		}
		elseif(!empty($gc->prreltoi_barcode)) 
		{
			$response['msg'] = 'Barcode # '.$barcode.' already released for promotional GC.';
		}
		elseif(!empty($gc->instituttritems_barcode))
		{
			$response['msg'] = 'Barcode # '.$barcode.' already released to treasury customer.';
		}
		else
		{
			$alreadyScanned = false;

			if(isset($_SESSION['scanForReleasedCustomerGC']))
			{
				//check gc if already scanned
				if(isset($_SESSION['scanForReleasedCustomerGC']))
				{
					foreach ($_SESSION['scanForReleasedCustomerGC'] as $key => $value) 
					{
						if($value['barcode']==$barcode)
						{
							$alreadyScanned = true;
							break;
						}
					}
				}
			}

			if(!$alreadyScanned)
			{
				//check if session exist
				if(isset($_SESSION['scanForReleasedCustomerGC']))
				{
					$_SESSION['scanForReleasedCustomerGC'][] = array("barcode"=>$barcode,"denomination"=>$gc->denomination);
				}
				else 
				{	
					$_SESSION['scanForReleasedCustomerGC'][] = array("barcode"=>$barcode,"denomination"=>$gc->denomination);
				}

				$total = 0;
				foreach ($_SESSION['scanForReleasedCustomerGC'] as $key => $value) 
				{
					$total+=$value['denomination'];
				}

				end($_SESSION['scanForReleasedCustomerGC']);
				$key = key($_SESSION['scanForReleasedCustomerGC']);


				$response['key'] = $key;
				$response['total'] = $total;
				$response['st'] = 1;
				$response['barcode'] = $barcode;
				$response['denomination'] = $gc->denomination;

				$response['msg'] = 'Succesfully Scanned for Releasing.';

			}
			else 
			{
				$response['msg'] = 'GC Barcode # '.$barcode.' already scanned.';
			}
		}

	// elseif ($action=='addEmployee') 
	// {
	// 	$denom = $_POST['den'];
	// 	$lastname = $_POST['lastname'];		
	// 	$firstname = $_POST['firstname'];
	// 	$middlename = $_POST['middlename'];
	// 	$extname = $_POST['nameext'];
	// 	$datanum = $_POST['datanum'];
	// 	if(isset($_SESSION['empAssign']))
	// 	{
	// 		$_SESSION['empAssign'][] = array("lastname"=>$lastname,"firstname"=>$firstname,"middlename"=>$middlename,"extname"=>$extname,"denom"=>$denom);
	// 	}
	// 	else 
	// 	{			
	// 		$_SESSION['empAssign'][] = array("lastname"=>$lastname,"firstname"=>$firstname,"middlename"=>$middlename,"extname"=>$extname,"denom"=>$denom);
	// 	}
	// 	$qty = 0;
	// 	foreach ($_SESSION['empAssign'] as $key => $value) {
	// 		if($value['denom']==$denom)
	// 		{
	// 			$qty++;
	// 		}
	// 	}

	// 	end($_SESSION['empAssign']);
	// 	$key = key($_SESSION['empAssign']);

	// 	$response['lastname'] = $lastname;
	// 	$response['firstname']	= $firstname;
	// 	$response['middlename'] = $middlename;
	// 	$response['nameext'] = $extname;
	// 	$response['key'] = $key;
	// 	$response['datanum'] = $datanum;
	// 	$response['qty'] = $qty; 
	// 	echo json_encode($response);
	// }
	// elseif ($action=='deleteAssignByKey') 
	// {
	// 	$denom = $_POST['den'];
	// 	$key = $_POST['key'];
	// 	$response['st'] = false;
	// 	unset($_SESSION['empAssign'][$key]);
	// 	$qty = 0;
	// 	foreach ($_SESSION['empAssign'] as $key => $value) {
	// 		if($value['denom']==$denom)
	// 		{
	// 			$qty++;
	// 		}
	// 	}
	// 	$response['qty'] = $qty;
	// 	$response['st'] = true;

	// 	echo json_encode($response);
	// }
		echo json_encode($response);
	}
	elseif($action == 'scanGCRangeForCustomerReleasing')
	{
        $response['st'] = 0;

        $bstart = trim($_POST['bstart']);
        $bend = trim($_POST['bend']);
        $barcode = trim($_POST['barcode']);

	    $table = 'gc';
        $select = 'gc.barcode_no,
            gc.gc_ispromo,
            custodian_srr_items.cssitem_barcode,
            promo_gc_release_to_items.prreltoi_barcode,
            gc_location.loc_barcode_no,
            institut_transactions_items.instituttritems_barcode,
            denomination.denomination';
        $where = "gc.barcode_no = '$barcode'";
        $join = 'LEFT JOIN
                custodian_srr_items
            ON
                custodian_srr_items.cssitem_barcode = gc.barcode_no
            LEFT JOIN
                promo_gc_release_to_items
            ON
                promo_gc_release_to_items.prreltoi_barcode = gc.barcode_no
            LEFT JOIN
                gc_location
            ON
                gc_location.loc_barcode_no = gc.barcode_no
            LEFT JOIN
                institut_transactions_items
            ON
                institut_transactions_items.instituttritems_barcode = gc.barcode_no
            LEFT JOIN
                denomination
            ON
                denomination.denom_id = gc.denom_id';
        $limit = '';

        $gc = getSelectedData($link,$table,$select,$where,$join,$limit);

        //var_dump($gc);

        if(count($gc)==0)
        {
            $response['msg'] = 'Barcode # '.$barcode.' not found.';
        }
        elseif(empty($gc->cssitem_barcode))
        {
            $response['msg'] = 'Barcode # '.$barcode.' needs validation.';
        }
        elseif(!empty($gc->loc_barcode_no))
        {
            $response['msg'] = 'Barcode # '.$barcode.' already allocated.';
        }
        elseif(!empty($gc->prreltoi_barcode)) 
        {
            $response['msg'] = 'Barcode # '.$barcode.' already released for promotional GC.';
        }
        elseif(!empty($gc->instituttritems_barcode))
        {
            $response['msg'] = 'Barcode # '.$barcode.' already released to treasury customer.';
        }
        else
        {
            $alreadyScanned = false;

            if(isset($_SESSION['scanForReleasedCustomerGC']))
            {
                //check gc if already scanned
                if(isset($_SESSION['scanForReleasedCustomerGC']))
                {
                    foreach ($_SESSION['scanForReleasedCustomerGC'] as $key => $value) 
                    {
                        if($value['barcode']==$barcode)
                        {
                            $alreadyScanned = true;
                            break;
                        }
                    }
                }
            }

            if(!$alreadyScanned)
            {

                if(isset($_SESSION['scanForReleasedCustomerGC']))
                {
                    $_SESSION['scanForReleasedCustomerGC'][] = array("barcode"=>$barcode,"denomination"=>$gc->denomination);
                }
                else 
                {   
                    $_SESSION['scanForReleasedCustomerGC'][] = array("barcode"=>$barcode,"denomination"=>$gc->denomination);
                }

                $total = 0;
                foreach ($_SESSION['scanForReleasedCustomerGC'] as $key => $value) 
                {
                    $total+=$value['denomination'];
                }

                end($_SESSION['scanForReleasedCustomerGC']);
                $key = key($_SESSION['scanForReleasedCustomerGC']);

                // if($barcode===$bend)
                // {
                // 	$response['st'] = 2;
                // 	$response['total'] = $total;
	               //  $response['barcode'] = $barcode;
	               //  $response['denomination'] = $gc->denomination;
	               //  $response['key'] = $key;
	               // 	$response['msg'] = 'Succesfully Scanned for Releasing.';
                // }
                // else 
                // {
	                $response['key'] = $key;
	                $response['total'] = $total;
	                $response['st'] = 1;
	                $response['barcode'] = $barcode;
	                $response['denomination'] = $gc->denomination;

                // }

                // $response['stat'] =1;

                // $denom = getDenominationByBarcode($link,$barcode);

                // $denid = getdenomid($link,$barcode);

                // $response['denom'] = $denom;
                // $response['denid'] = $denid;

                // $response['msg'] = 'Succesfully Scanned for Releasing.';

            }
            else 
            {
                $response['msg'] = 'GC Barcode # '.$barcode.' already scanned.';
            }
        }

		echo json_encode($response);
		
	}
	elseif($action== 'scanGCRangeForCustomerReleasingBstart')
	{
        $response['st'] = 0;

        $barcode = trim($_POST['barcode']);
		
        $table = 'gc';
        $select = 'gc.barcode_no,
            gc.gc_ispromo,
            custodian_srr_items.cssitem_barcode,
            promo_gc_release_to_items.prreltoi_barcode,
            gc_location.loc_barcode_no,
            institut_transactions_items.instituttritems_barcode,
            denomination.denomination';
        $where = "gc.barcode_no = '$barcode'";
        $join = 'LEFT JOIN
                custodian_srr_items
            ON
                custodian_srr_items.cssitem_barcode = gc.barcode_no
            LEFT JOIN
                promo_gc_release_to_items
            ON
                promo_gc_release_to_items.prreltoi_barcode = gc.barcode_no
            LEFT JOIN
                gc_location
            ON
                gc_location.loc_barcode_no = gc.barcode_no
            LEFT JOIN
                institut_transactions_items
            ON
                institut_transactions_items.instituttritems_barcode = gc.barcode_no
            LEFT JOIN
                denomination
            ON
                denomination.denom_id = gc.denom_id';
        $limit = '';

        $gc = getSelectedData($link,$table,$select,$where,$join,$limit);

        //var_dump($gc);

        if(count($gc)==0)
        {
            $response['msg'] = 'Barcode # '.$barcode.' not found.';
        }
        elseif(empty($gc->cssitem_barcode))
        {
            $response['msg'] = 'Barcode # '.$barcode.' needs validation.';
        }
        elseif(!empty($gc->loc_barcode_no))
        {
            $response['msg'] = 'Barcode # '.$barcode.' already allocated.';
        }
        elseif(!empty($gc->prreltoi_barcode)) 
        {
            $response['msg'] = 'Barcode # '.$barcode.' already released for promotional GC.';
        }
        elseif(!empty($gc->instituttritems_barcode))
        {
            $response['msg'] = 'Barcode # '.$barcode.' already released to treasury customer.';
        }
        else
        {
            $alreadyScanned = false;

            if(isset($_SESSION['scanForReleasedCustomerGC']))
            {
                //check gc if already scanned
                if(isset($_SESSION['scanForReleasedCustomerGC']))
                {
                    foreach ($_SESSION['scanForReleasedCustomerGC'] as $key => $value) 
                    {
                        if($value['barcode']==$barcode)
                        {
                            $alreadyScanned = true;
                            break;
                        }
                    }
                }
            }

            if(!$alreadyScanned)
            {
                //check if session exist
                // if(isset($_SESSION['scanForReleasedCustomerGC']))
                // {
                //     $_SESSION['scanForReleasedCustomerGC'][] = array("barcode"=>$barcode,"denomination"=>$gc->denomination);
                // }
                // else 
                // {   
                //     $_SESSION['scanForReleasedCustomerGC'][] = array("barcode"=>$barcode,"denomination"=>$gc->denomination);
                // }

                // $total = 0;
                // foreach ($_SESSION['scanForReleasedCustomerGC'] as $key => $value) 
                // {
                //     $total+=$value['denomination'];
                // }

                // end($_SESSION['scanForReleasedCustomerGC']);
                // $key = key($_SESSION['scanForReleasedCustomerGC']);


                // $response['key'] = $key;
                // $response['total'] = $total;
                // $response['st'] = 1;
                // $response['barcode'] = $barcode;
                // $response['denomination'] = $gc->denomination;

                $response['stat'] =1;

                $denom = getDenominationByBarcode($link,$barcode);

                $denid = getdenomid($link,$barcode);

                $response['denom'] = $denom;
                $response['denid'] = $denid;

                $response['msg'] = 'Succesfully Scanned for Releasing.';

            }
            else 
            {
                $response['msg'] = 'GC Barcode # '.$barcode.' already scanned.';
            }
        }



		echo json_encode($response);
	}	
	elseif($action=='removeByBarcodeTresRelByCustomer')
	{
		$response['st'] = false;
		$key = $_POST['key'];
		unset($_SESSION['scanForReleasedCustomerGC'][$key]);

		$total = 0;

		foreach ($_SESSION['scanForReleasedCustomerGC'] as $key => $value) 
		{
			$total+=$value['denomination'];
		}

		$response['st'] = true;
		$response['total'] = $total;

		echo json_encode($response);
	}
	elseif($action=='releaseTreasuryCustomer')
	{
		$response['st'] = false;
		$imageError = 0;
		$haspic = true;

		$relnum = getLastnumberOneWhere1($link,'institut_transactions','institutr_trnum','institutr_trtype','sales','institutr_trnum');
		$checkedby = $link->real_escape_string($_POST['checked']);
		$receivedby = $link->real_escape_string($_POST['recby']);
		$remarks = $link->real_escape_string($_POST['remarks']);
		$payfundid = $link->real_escape_string($_POST['payfundid']);
		$customerid = $link->real_escape_string($_POST['cusid']);
		$paymentamount = $link->real_escape_string($_POST['denocr']);
		$paymenttype = $link->real_escape_string($_POST['paymenttype']);
		$bankname = '';
		$baccountnum = '';
		$cnumber = '';
		$docname = '';
		$cash= 0;
		$checkamt = 0;
		$totalamtrec = 0;

		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		else
		{
			$totalDenom = 0;
			
			foreach ($_SESSION['scanForReleasedCustomerGC'] as $key => $value) 
			{
				$totalDenom+=$value['denomination'];
			}

			if($paymenttype=='cash')
			{
				if(isset($_POST['cashonly']))
				{
					$cash = $link->real_escape_string($_POST['cashonly']);
					$cash = str_replace(',', '', $cash);
				}				

				$totalamtrec = $cash;
				$totalamtrec = str_replace(',', '', $totalamtrec);
			}

			if($paymenttype=='check')
			{
				if(isset($_POST['banknamecheckonly']))
				{
					$bankname = $link->real_escape_string($_POST['banknamecheckonly']);
				}

				if(isset($_POST['baccountnumcheckonly']))
				{
					$baccountnum = $link->real_escape_string($_POST['baccountnumcheckonly']);
				}	

				if(isset($_POST['cnumbercheckonly']))
				{
					$cnumber = $link->real_escape_string($_POST['cnumbercheckonly']);
				}

				if(isset($_POST['checkonly']))
				{
					$checkamt = $link->real_escape_string($_POST['checkonly']);
					$checkamt = str_replace(',', '', $checkamt);
				}

				$totalamtrec = $checkamt;
				$totalamtrec = str_replace(',', '', $totalamtrec);
			}

			if($paymenttype=='cashcheck')
			{
				if(isset($_POST['ccbankname']))
				{
					$bankname = $link->real_escape_string($_POST['ccbankname']);
				}

				if(isset($_POST['ccbaccountnum']))
				{
					$baccountnum = $link->real_escape_string($_POST['ccbaccountnum']);
				}	

				if(isset($_POST['cchecknumber']))
				{
					$cnumber = $link->real_escape_string($_POST['cchecknumber']);
				}

				if(isset($_POST['ccheck']))
				{
					$checkamt = $link->real_escape_string($_POST['ccheck']);
					$checkamt = str_replace(',', '', $checkamt);
				}

				if(isset($_POST['ccash']))
				{
					$cash = $link->real_escape_string($_POST['ccash']);
					$cash = str_replace(',', '', $cash);
				}
				$totalamtrec = floatval($checkamt) + floatval($cash);
			}

			if($paymenttype=='gad')
			{
				if(isset($_POST['gadocu']))
				{
					$docname = $link->real_escape_string($_POST['gadocu']);					
				}				
			}

			if(count($_FILES['docs']['name'])==0)
			{
				$haspic = false;
			}

			if($haspic)
			{
				$imageError = checkDocumentsMutiple($_FILES);
			}

			if(!checkIfExist($link,'ins_id','institut_customer','ins_id',$customerid))
			{
				$response['msg'] = 'Customer dont exist.';
			}
			elseif(empty($receivedby))
			{
				$response['msg'] = 'Please input Received by field.';
			}
			elseif(empty($checkedby))
			{
				$response['msg'] = 'Please input Checked by field.';
			}
			elseif(empty($customerid))
			{
				$response['msg'] = 'Please select customer.';
			}
			elseif(empty($paymenttype))
			{
				$response['msg'] = 'Please select payment type.';
			}
			elseif ($totalDenom > $totalamtrec && $paymenttype!='gad') 
			{
				$response['msg'] = 'Total GC Denomination is greater than Amount Received.';
			}
			else 
			{
				$change = 0;

				if($paymenttype!='gad')
				{
					$change = floatval($totalamtrec) - floatval($totalDenom);
				}				

				$change = number_format($change,2);

				$hasScanned = false;
				if(isset($_SESSION['scanForReleasedCustomerGC']))
				{
					if(count($_SESSION['scanForReleasedCustomerGC']) > 0)
					{
						$hasScanned = true;
					}
				}

				if(!$hasScanned)
				{
					$response['msg'] = 'Please scan GC.';
				}
				else 
				{
					$link->autocommit(FALSE);
					$query = $link->query(
						"INSERT INTO 
							institut_transactions
						(
						    institutr_trnum, 
						    institutr_cusid, 
						    institutr_paymenttype, 
						    institutr_trby,
						    institutr_remarks, 
						    institutr_date,
						    institutr_receivedby,
						    institutr_trtype,
						    institutr_payfundid,
						    institutr_checkedby,
						    institutr_totamtpayable,
						    institutr_amtchange,
						    institutr_checkamt,
						    institutr_cashamt,
						    institutr_totamtrec,
						    institutr_docname

						) 
						VALUES
						(
						    '$relnum',
						    '$customerid',
						    '$paymenttype',
						    '".$_SESSION['gc_id']."',
						    '$remarks',
						    NOW(),
						    '$receivedby',
						    'sales',
						    '$payfundid',
						    '$checkedby',
						    '$totalDenom',
						    '$change',
						    '$checkamt',
						    '$cash',
						    '$totalamtrec',
						    '$docname'
						)
					");

					if($query)
					{
						$lastid = $link->insert_id;
						$paynum = getReceivingNumber($link,'insp_paymentnum','institut_payment');

						$query_payment = $link->query(
							"INSERT INTO 
									institut_payment
								(
								    insp_trid, 
								    institut_bankname, 
								    institut_bankaccountnum, 
								    institut_checknumber, 
								    institut_amountrec,
								    insp_paymentcustomer,
								    insp_paymentnum
								) 
								VALUES 
								(
								    '$lastid',
								    '$bankname',
								    '$baccountnum',
								    '$cnumber',
								    '$totalDenom',
								    'institution',
								    '$paynum'
								)
						");		

						if($query_payment)
						{
							$totalScanned = 0;
							$queryError = false;
							foreach ($_SESSION['scanForReleasedCustomerGC'] as $key => $value) 
							{
								$totalScanned+=$value['denomination'];
								$query_insertbarcode = $link->query(
									"INSERT INTO 
											institut_transactions_items
										(
										    instituttritems_barcode, 
										    instituttritems_trid
										) 
										VALUES 
										(
										    '".$value['barcode']."',
										    '$lastid'
										)
								");

								if(!$query_insertbarcode)
								{
									$queryError = true;
									break;
								}

								//update

								$query_update = $link->query(
									"UPDATE 
									gc 
								SET
									gc_treasury_release='*'
								WHERE 
									barcode_no='".$value['barcode']."'
								");

								if(!$query_update)
								{
									$queryError = true;
									break;
								}
							}

							//$totalScanned = 

							if($queryError)
							{
								$response['msg'] = $link->error;
							}
							else 
							{

								$lnum = ledgerNumber($link);
								$query_ledger = $link->query(
									"INSERT INTO 
										ledger_budget
									(
									    bledger_no, 
									    bledger_trid,
									    bledger_datetime, 
									    bledger_type, 
									    bdebit_amt
									) 
									VALUES 
									(
									    '$lnum',
									    '$lastid',
									    NOW(),
									    'GCRELINS',
									    '$totalDenom'
									)							
								");

								if(!$query_ledger)
								{
									$response['msg'] = $link->error;
								}
								else 
								{
									$errorUpload = false;
									if($haspic && !$imageError)
									{
										$pathfolder = 'institutionDocs';
										for($i=0; $i < count($_FILES['docs']['name']); $i++) 
										{
											$imagename = externalDocumentFilename($external = $_FILES['docs']['name'][$i],$i,$relnum);
											if(move_uploaded_file($_FILES['docs']['tmp_name'][$i], "assets/images/".$pathfolder."/".$imagename))
											{
												$fullpathfolder = $pathfolder.'/'.$imagename;
												$query_files = $link->query(
													"INSERT INTO 
														documents
													(
													    doc_trid, 
													    doc_type, 
													    doc_fullpath
													) 
													VALUES 
													(
													    '$lastid',
													    'Institution GC ',
													    '$fullpathfolder'
													)
												");

												if(!$query_files)
												{
													$queryError = true;
												}
											}
											else 
											{
												$errorUpload = true;
												break;
											}
										}
									}

									if($errorUpload)
									{
										$response['msg'] = 'Error Uploading Files.';
									}
									elseif($queryError)
									{
										$response['msg'] = $link->error;
									}
									else
									{
										unset($_SESSION['scanForReleasedCustomerGC']);
										$link->commit();
										$response['id'] = $lastid;
										$response['st'] = 1;
									}
								}
							}
						}	
						else 
						{
							$response['msg'] = $link->error;
						}	
					}
					else 
					{
						$response['msg'] = $link->error.'x';
					}
				}
				
				// if($paymenttype=='cash')
				// {
				// 	echo 'yeah';
				// }
				// elseif ($paymenttype=='check') 
				// {
				// 	echo 'check';
				// }	
			}
		}

		echo json_encode($response);
	}
	elseif($action=='getpromoexpirationdate')
	{
		$response['msg'] = 0;

		$datenotified = $_POST['datenotified']; 
		$datenotified =_dateFormatoSql($datenotified);

		// get claim expiration day
		$days = getDateTo($link,'promotional_gc_claim_expiration');
		$end_date = date('Y-m-d', strtotime("+".$days,strtotime($datenotified)));
		$end_date = _dateFormat($end_date);
		// $end_date = date('Y-m-d', strtotime("+".$days));

		$response['msg'] = $end_date;
		// $timestamp = strtotime('+'.$days);
		// $dateto = date('Y-m-d', $timestamp);
		// echo $dateto;


		echo json_encode($response);
	}
	elseif ($action=='eodtreasury') 
	{
		$response['st'] = 0;

		// count number of transaction to eod

		$table = 'institut_payment';
		$select = 'insp_id';
		$where = "institut_eodid = '0'";
		$join = '';
		$limit = '';

		$gcs = getAllData($link,$table,$select,$where,$join,$limit);

		if(count($gcs) > 0)
		{
			$link->autocommit(FALSE);

			$eodnum = getLastnumberOneWhere($link,'institut_eod','ieod_num','ieod_id');

			$query_ins = $link->query(
				"INSERT INTO 
					institut_eod
				(
				    ieod_date, 
				    ieod_by,
				    ieod_num
				) 
				VALUES 
				(
				    NOW(),
				    '".$_SESSION['gc_id']."',
				    '$eodnum'
				)
			");

			if(!$query_ins)
			{
				$response['msg'] = $link->error;
			}
			else 
			{
				$errQuery = false;
				$last_insert = $link->insert_id;
				foreach ($gcs as $trid) 
				{
					$query_update = $link->query(
						"UPDATE 
							institut_payment
						SET 
							institut_eodid='$last_insert = $link->insert_id'
						WHERE 
							insp_id='$trid->insp_id'
					"); 

					if(!$query_update)
					{
						$errQuery = true;
						break;
					}
				}

				if($errQuery)
				{
					$response['msg'] = $link->error;
				}
				else 
				{
					$link->commit();
					$response['id'] = $last_insert;
					$response['st'] = 1;
				}
			}
		}
		else 
		{
			$response['msg'] = 'There is no transaction to process EOD.';
		}


		echo json_encode($response);
	}
	elseif ($action=='transfergcrequest') 
	{
		$response['st'] = 0;
		$imageError = 0;
		$haspic = false;
		$hasdenom = false;
		$countdateneed = substr_count($_POST['date_needed'], ',');	
		$location = $link->real_escape_string($_POST['storeallo']);
		$dateneed = $link->real_escape_string(_dateFormatoSql($_POST['date_needed']));
		$remarks = $link->real_escape_string($_POST['remarks']);
		
		$requestid = getTransferRequestNumber($link,$_SESSION['gc_store']);
		//gc_store

		foreach ($_POST as $key => $value) 
		{
			if (strpos($key, 'denoms') !== false)
			{
				$qty = $value == '' ? 0 : str_replace(',','',$value);
				$denom_ids = substr($key, 6);
				if($qty>0)
				{
					$hasdenom = true;
				}
			} 
		}

		if(countFiles($_FILES)>0)
		{
			$haspic = true;
		}

		if($haspic)
		{
			$imageError = checkDocumentsMutiple($_FILES);
		}

		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif(empty($dateneed) || empty($location))
		{
			$response['msg'] = 'Please fill all required fields.';
		}
		elseif($countdateneed > 1)
		{
			$response['msg'] = 'Invalid Date Needed.';
		}
		elseif(!$hasdenom)
		{
			$response['msg'] = 'Please input denomination qty.';
		}
		elseif ($imageError) 
		{
			$response['msg'] = 'Only "jpg, png, jpeg" files are supported.';
		}
		else 
		{

			$link->autocommit(FALSE);
			$query = $link->query(
				"INSERT INTO 
					transfer_request
				(
				    t_reqnum, 
				    t_reqstoreby, 
				    t_reqstoreto, 
				    t_reqdatereq, 
				    t_reqdateneed, 
				    t_reqremarks, 
				    t_reqby,
				    t_reqstatus
				) 
				VALUES 
				(
				    '$requestid',
				    '".$_SESSION['gc_store']."',
				    '$location',
				    NOW(),
				    '$dateneed',
				    '$remarks',
				    '".$_SESSION['gc_id']."',
				    'pending'
				)
			");

			if(!$query)
			{
				$response['msg'] = $link->error;
			}
			else 
			{
				$last_id = $link->insert_id;
				$errorDenom = false;
				foreach ($_POST as $key => $value) {
					if (strpos($key, 'denoms') !== false)
					{
						$qty = $value == '' ? 0 : str_replace(',','',$value);
						$denom_ids = substr($key, 6);
						if(!empty($qty))
						{
							$query_items = $link->query(
								"INSERT INTO 
									transfer_request_items
								(
								    tr_itemsdenom, 
								    tr_itemsqty, 
								    tr_itemsqtyremain, 
								    tr_itemsreqid
								) 
								VALUES 
								(
								    '$denom_ids',
								    '$qty',
								    '$qty',
								    '$last_id'
								)
							");

							if(!$query_items)
							{
								$errorDenom = true;
								break;								
							} 						
						}

					} 
					//echo 'Key =>'.substr($key, 6).' Value =>'.$value;
				}

				if($errorDenom)
				{
					$response['msg'] = $link->error;
				}
				else
				{
					$errorUpload = false;
					$queryError = false;
					if($haspic && !$imageError)
					{
						$pathfolder = 'transferDocs';
						for($i=0; $i < count($_FILES['docs']['name']); $i++) 
						{
							$imagename = externalDocumentFilename($external = $_FILES['docs']['name'][$i],$i,$requestid);
							if(move_uploaded_file($_FILES['docs']['tmp_name'][$i], "assets/images/".$pathfolder."/".$imagename))
							{
								$fullpathfolder = $pathfolder.'/'.$imagename;
								$query_files = $link->query(
									"INSERT INTO 
										documents
									(
									    doc_trid, 
									    doc_type, 
									    doc_fullpath
									) 
									VALUES 
									(
									    '$last_id',
									    'Transfer Request',
									    '$fullpathfolder'
									)
								");

								if(!$query_files)
								{
									$queryError = true;
								}
							}
							else 
							{
								$errorUpload = true;
								break;
							}
						}

					}

					if($errorUpload)
					{
						$response['msg'] = 'Error Uploading Files.';
					}
					elseif($queryError)
					{
						$response['msg'] = $link->error;
					}
					else
					{
						$link->commit();
						$response['st'] = 1;
					}
				}
			}

		}

		echo json_encode($response);
	}
	elseif ($action=='updatetransfergcrequest') 
	{
		$response['st'] = 0;
		$imageError = 0;
		$haspic = false;
		$hasdenom = false;
		$toRemoved = false;
		$countdateneed = substr_count($_POST['date_needed'], ',');	
		$location = $link->real_escape_string($_POST['storeallo']);
		$dateneed = $link->real_escape_string(_dateFormatoSql($_POST['date_needed']));
		$remarks = $link->real_escape_string($_POST['remarks']);
		$reqid = $link->real_escape_string($_POST['reqid']);
		$reqnum = $link->real_escape_string($_POST['reqnum']);

		//check if valid request id
		$reqidcnt = numRowsWhereTwo($link,'transfer_request','tr_reqid','tr_reqid','t_reqstoreby',$reqid,$_SESSION['gc_store']);

		foreach ($_POST as $key => $value) 
		{
			if (strpos($key, 'denoms') !== false)
			{
				$qty = $value == '' ? 0 : str_replace(',','',$value);
				$denom_ids = substr($key, 6);
				if($qty>0)
				{
					$hasdenom = true;
				}
			} 
		}

		if(countFiles($_FILES)>0)
		{
			$haspic = true;
		}

		if($haspic)
		{
			$imageError = checkDocumentsMutiple($_FILES);
		}

		if(isset($_POST['images']))
		{
			$toRemoved = true;		
		}

		if(empty($dateneed) || empty($location))
		{
			$response['msg'] = 'Please fill all required fields.';
		}
		elseif($countdateneed > 1)
		{
			$response['msg'] = 'Invalid Date Needed.';
		}
		elseif(!$hasdenom)
		{
			$response['msg'] = 'Please input denomination qty.';
		}
		elseif ($imageError) 
		{
			$response['msg'] = 'Only "jpg, png, jpeg" files are supported.';
		}
		elseif ($reqidcnt == 0) 
		{
			$response['msg'] = 'Invalid Request ID.';
		}
		else 
		{

			$link->autocommit(FALSE);
			$query_update = $link->query(
				"UPDATE 
					transfer_request 
				SET 
					t_reqstoreto='$location',
					t_reqdateneed='$dateneed',
					t_reqremarks='$remarks',
					t_dateupdated= NOW(),
					t_updatedby='".$_SESSION['gc_id']."' 
				WHERE 
					tr_reqid = '$reqid'
				AND
					t_reqstoreby = '".$_SESSION['gc_store']."'
				AND
					t_reqstatus = 'pending'		
			");

			if(!$query_update)
			{
				$response['msg'] = $link->error;
			}
			else 
			{
				if($link->affected_rows == 0)
				{
					$response['msg'] = 'Request already Approved/Cancelled.';
				}
				else 
				{
					$query_delitems = $link->query(
						"DELETE 
						FROM 
							transfer_request_items 
						WHERE 
							tr_itemsreqid = '$reqid'
	
					");

					if(!$query_delitems)
					{
						$response['msg'] = $link->error;
					}
					else 
					{
						$errorDenom = false;
						foreach ($_POST as $key => $value) {
							if (strpos($key, 'denoms') !== false)
							{
								$qty = $value == '' ? 0 : str_replace(',','',$value);
								$denom_ids = substr($key, 6);
								if(!empty($qty))
								{
									$query_items = $link->query(
										"INSERT INTO 
											transfer_request_items
										(
										    tr_itemsdenom, 
										    tr_itemsqty, 
										    tr_itemsqtyremain, 
										    tr_itemsreqid
										) 
										VALUES 
										(
										    '$denom_ids',
										    '$qty',
										    '$qty',
										    '$reqid'
										)
									");

									if(!$query_items)
									{
										$errorDenom = true;
										break;								
									} 						
								}

							} 
							//echo 'Key =>'.substr($key, 6).' Value =>'.$value;
						} // foreach

						if($errorDenom)
						{
							$response['msg'] = $link->error;
						}
						else 
						{
								$queryError = false;
								$errorUpload = false;
								if($haspic)
								{
									$pathfolder = 'transferDocs';
									for($i=0; $i < count($_FILES['docs']['name']); $i++) 
									{
										$imagename = externalDocumentFilename($external = $_FILES['docs']['name'][$i],$i,$reqnum);
										if(move_uploaded_file($_FILES['docs']['tmp_name'][$i], "assets/images/".$pathfolder."/".$imagename))
										{
											$fullpathfolder = $pathfolder.'/'.$imagename;
											$query_files = $link->query(
												"INSERT INTO 
													documents
												(
												    doc_trid, 
												    doc_type, 
												    doc_fullpath
												) 
												VALUES 
												(
												    '$reqid',
												    'Transfer Request',
												    '$fullpathfolder'
												)
											");

											if(!$query_files)
											{
												$queryError = true;
											}
										}
										else 
										{
											$errorUpload = true;
											break;
										}
									}

								} // end haspic

								if($errorUpload)
								{
									$response['msg'] = 'Error Uploading Files.';
								}
								elseif($queryError)
								{
									$response['msg'] = $link->error;
								}
								else
								{
									$errorDelete = false;
									if($toRemoved)
									{
										foreach ($_POST['images'] as $i) {
											$query_del = $link->query(
												"DELETE FROM 
													documents 
												WHERE 
													doc_fullpath='$i'
												AND
													doc_type='Transfer Request'
											");

											if($query_del)
											{

												if(!unlink('assets/images/'.$i))
												{
													$errorDelete = true;
													break;
												}
											}
											else 
											{
												$queryError = true;
												break;
											}

										}

									}

									if($errorDelete)
									{
										$response['msg'] = 'Error Deleting Files.';
									}
									elseif($queryError)
									{
										$response['msg'] = $link->error;
									}
									else
									{
										$link->commit();
										$response['st'] = 1;
									}
								}
						}
					}

				} // affected rows
			} // query_update
		}
		echo json_encode($response);	
	}
	elseif ($action=='servedTransferRequest') 
	{
		$response['st'] = 0;
		$barcode = $_POST['barcode'];
		$reqid = $_POST['reqid'];

		$table = 'store_received_gc';
		$select = 'store_received_gc.strec_storeid,
			store_received_gc.strec_denom,
			store_received_gc.strec_sold,
			store_received_gc.strec_transfer_out,
			store_received_gc.strec_return';
		$where = "store_received_gc.strec_barcode='".$barcode."'
			AND
				store_received_gc.strec_storeid='".$_SESSION['gc_store']."'";
		$join = '';
		$limit = '';
		$gc = getSelectedData($link,$table,$select,$where,$join,$limit);

		if(!count($gc) > 0)
		{
			$response['msg'] = 'GC Barcode # '.$barcode.' not found.';
		}
		elseif($gc->strec_sold=='*')
		{
			$response['msg'] = 'GC Barcode # '.$barcode.' already sold.';
		}
		elseif ($gc->strec_transfer_out=="*") 
		{
			$response['msg'] = 'GC Barcode # '.$barcode.' already transfered out.';
		}
		else
		{

			$denid = getdenomid($link,$barcode);
			//check if request exist;
			if(!checkifExist2($link,'t_reqnum','transfer_request','tr_reqid','t_reqstoreto',$reqid,$_SESSION['gc_store']))
			{
				$response['msg'] = 'Transfer Request dont exist.';
			}
			elseif(!numRowsWhereTwo($link,'transfer_request_items','tr_itemsreqid','tr_itemsreqid','tr_itemsdenom',$reqid,$denid) > 0 )
			{
				$response['msg'] = 'There is no transfer request for this denomination.';
			}
			elseif(numRowsWhereThree($link,'transfer_request_items','tr_itemsreqid','tr_itemsreqid','tr_itemsdenom','tr_itemsqtyremain',$reqid,$denid,0) > 0)
			{
				//check if remaining if already 0 
				$response['msg'] = 'Remaining request for this denomination is 0.';
			}
			else 
			{
				$remaingqty = getRemainingGCToTransfer($link,$denid,$reqid);
				$barcodeExist = false;

				if(isset($_SESSION['scanGCForTransfer']))
				{
					if(is_in_array($_SESSION['scanGCForTransfer'], 'barcode', $barcode))
					{
						$barcodeExist = true;
					}
				}

				if($barcodeExist)
				{
					$response['msg'] = 'GC Barcode # '.$barcode.' already scanned.';
				}
				else 
				{
					// number of gc scanned
					$scannedGC = 0;
					if(isset($_SESSION['scanGCForTransfer']))
					{
						foreach ($_SESSION['scanGCForTransfer'] as $key => $value) {
							if($value['denomid']==$denid)
							{
								$scannedGC++;
							}
						}
					}

					if($scannedGC>=$remaingqty)
					{
						$response['msg'] = 'Number of GC Scanned has reached the maximum number to serve.';
					}
					else 
					{
						// get barcode details

						$table = 'gc';
						$select = 'gc.barcode_no,
							denomination.denomination,
							gc.pe_entry_gc';
						$where = "gc.barcode_no = '".$barcode."'";
						$join ='INNER JOIN
								denomination
							ON
								denomination.denom_id = gc.denom_id';
						
						$barcodearr = getSelectedData($link,$table,$select,$where,$join,$limit=NULL);

						$scannedGC++;
						$response['st'] = 1;
						$response['denom'] = $denid;
						$response['denomination'] = $barcodearr->denomination;
						$response['scanned'] = $scannedGC;
						$response['msg'] = $barcode;
						$_SESSION['scanGCForTransfer'][] = array(
							"barcode"=>$barcode,
							"denomid"=>$denid,
							"reqid"=>$reqid,
							"denomination" => $barcodearr->denomination,
							"promo"	=> "Transfer GC"
						);
					}
				}

			}
		}
		
		echo json_encode($response);
	}
	elseif ($action=='removedScannedPromoGC') 
	{
		$response['st'] = 0;
		$transid = $_POST['transid'];
		if(isset($_POST['checkboxpromo']))
		{
			if (is_array($_POST['checkboxpromo'])) 
		  	{
		    	foreach($_POST['checkboxpromo'] as $valuecheckbox)
		    	{
					foreach ($_SESSION['scannedPromo'] as $key => $value) 
					{
						if($value['barcode'] == $valuecheckbox)
						{
							unset($_SESSION['scannedPromo'][$key]);
						}
					}
		    	}
		    }
		}

		//get remain request

		$table = 'promo_gc_request_items';
		$select = 'pgcreqi_denom';
		$where = "pgcreqi_trid='".$transid."' AND pgcreqi_remaining!='0'";
		$join = '';
		$limit = '';

		$data = getAllData($link,$table,$select,$where,$join,$limit);

		$scanned = [];

		foreach ($data as $d) 
		{
			$nums = 0;
			foreach ($_SESSION['scannedPromo'] as $key => $value)
			{
				if($d->pgcreqi_denom==$value['denomid'])
				{
					$nums++;
				}
			}
			$scanned[] = $d->pgcreqi_denom.'='.$nums;
		}

		$response['rscanned'] = $scanned;

		$response['st'] = 1;

		echo json_encode($response);
	}
	elseif ($action=='removeScannedTransferGC') 
	{
		$response['st'] = 0;

		$reqid = $_POST['reqid'];

		if(isset($_POST['checkboxtransfer']))
		{
			if (is_array($_POST['checkboxtransfer'])) 
		  	{
		    	foreach($_POST['checkboxtransfer'] as $valuecheckbox)
		    	{
					foreach ($_SESSION['scanGCForTransfer'] as $key => $value) 
					{
						if($value['barcode'] == $valuecheckbox)
						{
							unset($_SESSION['scanGCForTransfer'][$key]);
						}
					}
		    	}
		    }
		}

		//get remain request

		$table = 'transfer_request_items';
		$select = 'transfer_request_items.tr_itemsdenom,denomination.denomination';
		$where = "tr_itemsreqid='".$reqid."' AND tr_itemsqtyremain!='0'";
		$join = 'INNER JOIN denomination ON	denomination.denom_id = transfer_request_items.tr_itemsdenom';
		$limit = '';

		$data = getAllData($link,$table,$select,$where,$join,$limit);

		$scanned = [];
		$total = 0;
		foreach ($data as $d) 
		{
			$nums = 0;
			foreach ($_SESSION['scanGCForTransfer'] as $key => $value)
			{
				if($d->tr_itemsdenom==$value['denomid'])
				{
					$nums++;
					$total += $d->denomination;
				}
			}
			$scanned[] = $d->tr_itemsdenom.'='.$nums;
		}

		$response['rscanned'] = $scanned;
		$response['total'] = $total;

		$response['st'] = 1;

		echo json_encode($response);

	}
	elseif ($action=='serveTransferRequest') 
	{ 
		$response['st'] = 0;

		$trnum = getTransferReleasedNumber($link,$_SESSION['gc_store']);

		$imageError = 0;
		$haspic = false;
		$reqid = $link->real_escape_string(trim($_POST['reqid']));
		$remarks = $link->real_escape_string(trim($_POST['remarks']));
		$checkby = $link->real_escape_string(trim($_POST['checkby']));
		$recby = $link->real_escape_string(trim($_POST['recby']));

		//get store id 
		$storeid = getField($link,'t_reqstoreby','transfer_request','tr_reqid',$reqid);

		if(countFiles($_FILES)>0)
		{
			$haspic = true;
		}

		if($haspic)
		{
			$imageError = checkDocumentsMutiple($_FILES);
		}

		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif(count($_SESSION['scanGCForTransfer'])==0)
		{
			$response['msg'] = "Please scan GC first.";
		}
		elseif($checkby=='' || $recby=='')
		{
			$response['msg'] = "Please fill in required fields.";
		}
		else
		{
			//check if already served
			$hastrans = false;
			if(numRows($link,'transfer_request_served','tr_reqid',$reqid)>0)
			{
				$hastrans = true;
			}

			//check if partial / whole or final release 
			$table = 'transfer_request_items';
			$select = 'transfer_request_items.tr_itemsdenom,
				transfer_request_items.tr_itemsqtyremain';
			$where = "transfer_request_items.tr_itemsreqid='".$reqid."'";
			$join = '';
			$limit = '';
			$denoms = getAllData($link,$table,$select,$where,$join,$limit);

			foreach ($denoms as $d) 
			{
				$equal = true;
				$nums = 0;
				foreach ($_SESSION['scanGCForTransfer'] as $key => $value)
				{
					if($d->tr_itemsdenom==$value['denomid'])
					{
						$nums++;						
					}
				}

				if($d->tr_itemsqtyremain!=$nums)
				{
					$equal = false;
					break;
				}
			}

			if($hastrans)
			{
				if(!$equal)
				{
					$status = "partial";
				}
				else 
				{
					$status = "final";
				}
			}
			else 
			{
				if(!$equal)
				{
					$status = "partial";
				}
				else 
				{
					$status = "whole";
				}
			}

			$link->autocommit(FALSE);

			$query_ins = $link->query(
				"INSERT INTO 
					transfer_request_served
				(
				    tr_serverelnum, 
				    tr_serveremarks, 
				    tr_serveCheckedBy, 
				    tr_serveReceivedBy, 
				    tr_servedate, 
				    tr_serveby, 
				    tr_serveStatus, 
				    tr_serveRecStatus,
				    tr_reqid,
				    tr_serve_store
				) 
				VALUES 
				(
				    '".$trnum."',
				    '".$remarks."',
				    '".$checkby."',
				    '".$recby."',
				    NOW(),
				    '".$_SESSION['gc_id']."',
				    '".$status."',
				    'pending',
				    '".$reqid."',
				    '$storeid'
				)
			");

			if(!$query_ins)
			{
				$response['msg'] = $link->error;
			}
			else 
			{
				$lastid = $link->insert_id;
				$reqstatus = '';
				if($status=='partial')
				{
					$reqstatus = 'partial'; 
				}
				else 
				{
					$reqstatus = 'closed';
				}
				$query_update_request = $link->query(
					"UPDATE 
						transfer_request 
					SET 
						t_reqstatus='$reqstatus' 
					WHERE 
						tr_reqid='$reqid'
				");

				if(!$query_update_request)
				{
					$response['msg'] = $link->error;
				}
				else 
				{					

					$queryError = false;

					$totalamt = 0;

					foreach ($_SESSION['scanGCForTransfer'] as $key => $value)
					{
						$totalamt+=$value['denomination'];
						$query_ins_barcode = $link->query(
							"INSERT INTO 
								transfer_request_served_items
							(
							    trs_barcode, 
							    trs_served
							)
							VALUES 
							(
							    '".$value['barcode']."',
							    '$lastid'
							)
						");

						if(!$query_ins_barcode)
						{
							$queryError = true;
							break;
						}

						$query_update_received = $link->query(
							"UPDATE 
								store_received_gc 
							SET 
								strec_transfer_out='*' 
							WHERE 
								strec_barcode='".$value['barcode']."'
						");

						if(!$query_update_received)
						{
							$queryError = true;
							break;
						}

						// get remaining
						$remain = getField2($link,'tr_itemsqtyremain','transfer_request_items','tr_itemsdenom','tr_itemsreqid',$value['denomid'],$reqid);
						$remain--;

						$update_remain = $link->query(
							"UPDATE 
								transfer_request_items
							SET 
								tr_itemsqtyremain='".$remain."'
							WHERE 
								tr_itemsdenom='".$value['denomid']."'
							AND
								tr_itemsreqid='".$reqid."'	
						");

						if(!$update_remain)
						{
							$queryError = true;
							break;
						}
					}

					if($queryError)
					{
						$response['msg'] = $link->error;
					}
					else 
					{
						$storeid = $_SESSION['gc_store'];
						$storeledNumber = getLedgerStoreLastLedgerNumber($link,$storeid);
						if(!insertGCStoreGCEntryTransfer($link,$lastid,$_SESSION['gc_id'],$storeledNumber,'Gift Check Transfer Out','sledger_credit','GCTOUT',$totalamt))
						{
							$response['msg'] = $link->error;
						}
						else 
						{
							$errorUpload = false;
							if($haspic && !$imageError)
							{
								$pathfolder = 'transferDocs';
								for($i=0; $i < count($_FILES['docs']['name']); $i++) 
								{
									$imagename = externalDocumentFilename($external = $_FILES['docs']['name'][$i],$i,$lastid);
									if(move_uploaded_file($_FILES['docs']['tmp_name'][$i], "assets/images/".$pathfolder."/".$imagename))
									{
										$fullpathfolder = $pathfolder.'/'.$imagename;
										$query_files = $link->query(
											"INSERT INTO 
												documents
											(
											    doc_trid, 
											    doc_type, 
											    doc_fullpath
											) 
											VALUES 
											(
											    '$lastid',
											    'Served Transfer Request',
											    '$fullpathfolder'
											)
										");

										if(!$query_files)
										{
											$queryError = true;
										}
									}
									else 
									{
										$errorUpload = true;
										break;
									}
								}

							}

							if($errorUpload)
							{
								$response['msg'] = 'Error Uploading Files.';
							}
							elseif($queryError)
							{
								$response['msg'] = $link->error;
							}
							else
							{
								$link->commit();
								$response['id'] = $lastid;
								$response['st'] = 1;
							}
						}
					}
				}

			}
			
		}

		echo json_encode($response);
	}
	elseif ($action=='receivedTransferRequest') 
	{
		$response['st'] = 0;

		$servedid = (int)$_POST['servedid'];
		$barcode = $_POST['barcode'];

		$table = 'transfer_request_served_items';
		$select = 'gc.denom_id,
			transfer_request_served_items.trs_barcode,
			denomination.denomination';
		$where = "transfer_request_served_items.trs_barcode='".$barcode."'
			AND
				transfer_request_served_items.trs_served='".$servedid."'
			AND
				transfer_request_served.tr_serve_store='".$_SESSION['gc_store']."'";
		$join = 'LEFT JOIN
				gc
			ON
				gc.barcode_no = transfer_request_served_items.trs_barcode
			LEFT JOIN
				denomination
			ON
				denomination.denom_id = gc.denom_id
			LEFT JOIN
				transfer_request_served
			ON
				transfer_request_served.tr_servedid = transfer_request_served_items.trs_served';
		$limit = '';

		$gc = getSelectedData($link,$table,$select,$where,$join,$limit);

		if(!count($gc) > 0)
		{
			$response['msg'] = 'GC Barcode # '.$barcode.' not found.';
		}
		else
		{
			$denid = getdenomid($link,$barcode);
			$numGCtoRec = numOfGCtoRecTransfer($link,$servedid,$denid);

			if($numGCtoRec==0)
			{
				$response['msg'] = 'Something went wrong no denomination to received.';
			}
			else 
			{
				$barcodeExist = false;

				if(isset($_SESSION['scanGCForTransferReceiving']))
				{
					if(is_in_array($_SESSION['scanGCForTransferReceiving'], 'barcode', $barcode))
					{
						$barcodeExist = true;
					}
				}

				if($barcodeExist)
				{
					$response['msg'] = 'GC Barcode # '.$barcode.' already scanned.';
				}	
				else 
				{
					// number of gc scanned
					$scannedGC = 0;
					if(isset($_SESSION['scanGCForTransferReceiving']))
					{
						foreach ($_SESSION['scanGCForTransferReceiving'] as $key => $value) {
							if($value['denomid']==$denid)
							{
								$scannedGC++;
							}
						}
					}

					if($scannedGC>=$numGCtoRec)
					{
						$response['msg'] = 'Number of GC Scanned has reached the maximum number to receive.';
					}
					else 
					{
						// get barcode details

						$table = 'gc';
						$select = 'gc.barcode_no,
							denomination.denomination,
							gc.pe_entry_gc';
						$where = "gc.barcode_no = '".$barcode."'";
						$join ='INNER JOIN
								denomination
							ON
								denomination.denom_id = gc.denom_id';
						
						$barcodearr = getSelectedData($link,$table,$select,$where,$join,$limit=NULL);

						$scannedGC++;
						$response['st'] = 1;
						$response['denom'] = $denid;
						$response['denomination'] = $barcodearr->denomination;
						$response['scanned'] = $scannedGC;
						$response['msg'] = $barcode;
						$_SESSION['scanGCForTransferReceiving'][] = array(
							"barcode"=>$barcode,
							"denomid"=>$denid,
							"serveid"=>$servedid,
							"denomination" => $barcodearr->denomination
						);
					}
				}				
			}

		}
		
		echo json_encode($response);
	}
	elseif($action=='removeScannedTransferGCRec')
	{
		$response['st'] = 0;

		$servedid = (int)$_POST['servedid'];

		if(isset($_POST['checkboxtransfer']))
		{
			if (is_array($_POST['checkboxtransfer'])) 
		  	{
		    	foreach($_POST['checkboxtransfer'] as $valuecheckbox)
		    	{
					foreach ($_SESSION['scanGCForTransferReceiving'] as $key => $value) 
					{
						if($value['barcode'] == $valuecheckbox)
						{
							unset($_SESSION['scanGCForTransferReceiving'][$key]);
						}
					}
		    	}
		    }
		}

		//get number of gc receivable 
		$table='transfer_request_served_items';
		$select ="IFNULL(COUNT(denomination.denomination),0) as cnt,
			denomination.denomination,
			denomination.denom_id,
			gc.denom_id";
		$where = "transfer_request_served_items.trs_served='".$servedid."'
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

		$data = getAllData($link,$table,$select,$where,$join,$limit);

		$scanned = [];
		$total = 0;
		foreach ($data as $d) 
		{
			$nums = 0;
			foreach ($_SESSION['scanGCForTransferReceiving'] as $key => $value)
			{
				if($d->denim_id==$value['denomid'])
				{
					$nums++;
					$total += $d->denomination;
				}
			}
			$scanned[] = $d->denom_id.'='.$nums;
		}

		$response['rscanned'] = $scanned;
		$response['total'] = $total;

		$response['st'] = 1;

		echo json_encode($response);

	}
	elseif($action=='serveTransferReceiving')
	{
		$response['st'] = 0;

		$recnum = getReceivingNumberTR($link,$_SESSION['gc_store'],'store transfer');

		$imageError = 0;
		$haspic = false;
		$servedid = $link->real_escape_string(trim($_POST['servedid']));
		$remarks = $link->real_escape_string(trim($_POST['remarks']));
		$checkby = $link->real_escape_string(trim($_POST['checkby']));

		if(countFiles($_FILES)>0)
		{
			$haspic = true;
		}

		if($haspic)
		{
			$imageError = checkDocumentsMutiple($_FILES);
		}

		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif(count($_SESSION['scanGCForTransferReceiving'])==0)
		{
			$response['msg'] = "Please scan GC first.";
		}
		elseif($checkby=='')
		{
			$response['msg'] = "Please fill in required fields.";
		}
		else
		{
			$link->autocommit(FALSE);

			$query_ins = $link->query(
				"INSERT INTO 
					store_received
				(
				    srec_recid, 
				    srec_rel_id, 
				    srec_store_id, 
				    srec_receivingtype, 
				    srec_at, 
				    srec_checkedby, 
				    srec_by
				) 
				VALUES 
				(
				    '".$recnum."',
				    '".$servedid."',
				   	'".$_SESSION['gc_store']."',
				    'store transfer',
				    NOW(),
				    '".$checkby."',
				    '".$_SESSION['gc_id']."'
				)
			");

			if(!$query_ins)
			{
				$response['msg'] = $link->error;
			}
			else 
			{
				$lastid = $link->insert_id;

				$query_update_request = $link->query(
					"UPDATE 
						transfer_request_served 
					SET 
						tr_serveRecStatus='received',
						tr_receiveremarks='$remarks'
					WHERE 
						tr_servedid='".$servedid."'
					AND
						tr_serveRecStatus='pending'
				");

				if(!$query_update_request)
				{
					$response['msg'] = $link->error;
				}
				else 
				{
					$queryError = false;

					foreach ($_SESSION['scanGCForTransferReceiving'] as $key => $value)
					{
						$query_ins_barcode = $link->query(
							"INSERT INTO 
								store_received_gc
							(
							    strec_barcode, 
							    strec_storeid, 
							    strec_recnum, 
							    strec_denom
							) 
							VALUES 
							(
							    '".$value['barcode']."',
							    '".$_SESSION['gc_store']."',
							    '".$lastid."',
							    '".$value['denomid']."'
							)
						");

						if(!$query_ins_barcode)
						{
							$queryError = true;
							break;
						}
					}

					if($queryError)
					{
						$response['msg'] = $link->error;
					}
					else 
					{
						$lnumber = checkledgernumber($link);

						$storeledNumber = getLedgerStoreLastLedgerNumber($link,$_SESSION['gc_store']);

						// if(!insertGCStoreGCEntryTransfer($link,$lastid,$_SESSION['gc_id'],$storeledNumber,'Gift Check Transfer In','sledger_credit','GCTIN',$totalamt))
						// {
						//   $response['msg'] = $link->error;
						// }

						$errorUpload = false;
						if($haspic && !$imageError)
						{
							$pathfolder = 'transferDocs';
							for($i=0; $i < count($_FILES['docs']['name']); $i++) 
							{
								$imagename = externalDocumentFilename($external = $_FILES['docs']['name'][$i],$i,$lastid);
								if(move_uploaded_file($_FILES['docs']['tmp_name'][$i], "assets/images/".$pathfolder."/".$imagename))
								{
									$fullpathfolder = $pathfolder.'/'.$imagename;
									$query_files = $link->query(
										"INSERT INTO 
											documents
										(
										    doc_trid, 
										    doc_type, 
										    doc_fullpath
										) 
										VALUES 
										(
										    '$lastid',
										    'Received Transfer GC',
										    '$fullpathfolder'
										)
									");

									if(!$query_files)
									{
										$queryError = true;
									}
								}
								else 
								{
									$errorUpload = true;
									break;
								}
							}

						}

						if($errorUpload)
						{
							$response['msg'] = 'Error Uploading Files.';
						}
						elseif($queryError)
						{
							$response['msg'] = $link->error;
						}
						else
						{
							$link->commit();
							$response['st'] = 1;
						}

					}
				}

			}
			
		}

		echo json_encode($response);
	}
	elseif ($action=='checklostgc') 
	{
		$response['st'] = 0;
		$barcodeExist = false;
		$gc = $_POST['barcode'];
		$isFound = false;

		if(isset($_SESSION['scanGCForLostGCReport']))
		{
			if(is_in_array($_SESSION['scanGCForLostGCReport'], 'barcode', $gc))
			{
				$barcodeExist = true;
			}
		}

		if($barcodeExist)
		{
			$response['msg'] = 'GC Barcode # '.$gc.' already scanned.';
		}
		else
		{

			//check if gc is regular/promo/special
			if(checkIfExist($link,'barcode_no','gc','barcode_no',$gc))
			{

				//check if gc is institution 
				if(numRows2($link,'institut_transactions_items','instituttritems_barcode',$gc) > 0)
				{
					$isFound = true;
					$gctype = 1;
					$gctypename = 'Regular GC (Institution)';
				}


				//check if gc already sold
				$sold_info = checkIfGCAlreadySold($link,$gc);
				if(!is_null($sold_info))
				{
					$isFound = true;
					$gctype = 1;
					$gctypename = 'Regular GC';
				}

				if(numRows2($link,'promogc_released','prgcrel_barcode',$gc))
				{
					$isFound = true;
					$gctype = 4;
					$gctypename = 'Promo GC';
				}

				if($isFound)
				{				
				    $denom = getDenominationByBarcode($link,$gc);
				}
			}
			elseif(checkIfExist($link,'spexgcemp_barcode','special_external_gcrequest_emp_assign','spexgcemp_barcode',$gc)) 
			{
				$table = 'special_external_gcrequest_emp_assign';
				$select = 'spexgcemp_denom';
				$where = "special_external_gcrequest_emp_assign.spexgcemp_barcode='".$gc."'
					AND
						approved_request.reqap_approvedtype='special external releasing'";
				$join = 'INNER JOIN
					approved_request
					ON
					approved_request.reqap_trid = special_external_gcrequest_emp_assign.spexgcemp_trid';
				$limit = '';
				$special = getSelectedData($link,$table,$select,$where,$join,$limit);

				if(count($special) > 0)
				{		

					$denom = getSpecialGCDenom($link,$gc);
					$isFound = true;
					$gctype=3;
					$gctypename = 'Special External GC';
				}
			}

			if(!$isFound)
			{
				$response['msg'] = 'GC Barcode # '.$gc.' not found.';
			}
			else
			{
				$verifiedGCDetails = checkIFGCAlreadyVerified($link,$gc);
				if(!is_null($verifiedGCDetails))
				{
					//$isVerified = false; 
					$response['msg'] = 'GC Barcode #'.$gc.' is already verified.<br />Customer Name: <span class="tit">'.ucwords($verifiedGCDetails->cus_fname).' '.$verifiedGCDetails->cus_lname.'</span><br />
						Store Verified: <span class="tit">'.$verifiedGCDetails->store_name.'</span>';
				}
				else 
				{
					//check if session exist
					if(isset($_SESSION['scanGCForLostGCReport']))
					{
						$_SESSION['scanGCForLostGCReport'][] = array("barcode"=>$gc,"denomination"=>$denom);
					}
					else 
					{	
						$_SESSION['scanGCForLostGCReport'][] = array("barcode"=>$gc,"denomination"=>$denom);
					}

					$response['barcode'] = $gc;
					$response['denom'] = $denom;
					$response['st'] = 1;
					//var_dump($_SESSION['scanGCForLostGCReport']);
				}
				
			}
		}

		echo json_encode($response);
	}
	elseif ($action=='removedscannedLostGC') 
	{
		$response['st'] = false;
		$barcode = $_POST['gc'];

		if(isset($_SESSION['scanGCForLostGCReport']))
		{
			foreach ($_SESSION['scanGCForLostGCReport'] as $key => $value) 
			{
				if($value['barcode'] == $barcode)
				{
					unset($_SESSION['scanGCForLostGCReport'][$key]);
				}
			}			
		}

		echo json_encode($response);
	}
	elseif ($action=='gcLostReport') 
	{

		$response['st'] = false;
		$qryError = false;
		$countdateLost= substr_count($_POST['date_lost'], ',');
		$lostnum = getLostGCReportNum($link,$_SESSION['gc_store']);

		$dateLost = $link->real_escape_string($_POST['date_lost']);
		$ownersName = $link->real_escape_string($_POST['ownersname']);
		$address = $link->real_escape_string($_POST['address']);
		$remarks = $link->real_escape_string($_POST['remarks']);
		$contactnum = $link->real_escape_string($_POST['contactnum']);

		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif(empty($dateLost)|| empty($ownersName) || empty($address))
		{
			$response['msg'] = 'Please input required fields.';
		}
		elseif($countdateLost > 1)
		{
			$response['msg'] = 'Invalid date lost.';
		}
		elseif(count($_SESSION['scanGCForLostGCReport'])==0)
		{
			$response['msg'] = 'Table is empty.';
		}
		else 
		{

			$dateLost = _dateFormatoSql($dateLost);

			$link->autocommit(FALSE);
			$queury_ins = $link->query(
				"INSERT INTO 
					lost_gc_details
				(
					lostgcd_repnum, 
					lostgcd_storeid, 
					lostgcd_owname, 
					lostgcd_address, 
					lostgcd_contactnum, 
					lostgcd_datereported, 
					lostgcd_datelost, 
					lostgcd_prepby
				) 
				VALUES 
				(
					'$lostnum',
					'".$_SESSION['gc_store']."',
					'".$ownersName."',
					'".$address."',
					'".$contactnum."',
					NOW(),
					'".$dateLost."',
					'".$_SESSION['gc_id']."'
				)
			");

			if(!$queury_ins)
			{
				$response['msg'] = $link->error;
			}
			else 
			{

				$lastid = $link->insert_id;

				foreach ($_SESSION['scanGCForLostGCReport'] as $key => $value) 
				{
					//$value['barcode']
					$query_insb = $link->query(
						"INSERT INTO 
							lost_gc_barcodes
						(
							lostgcb_barcode, 
							lostgcb_repid,
							lostgcb_denom
						) 
						VALUES 
						(
							'".$value['barcode']."',
							'$lastid',
							'".$value['denomination']."'
						)
					");

					if(!$query_insb)
					{
						$qryError = true;
						break;
					}
				}

				if($qryError)
				{
					$response['msg'] = $link->error.'xxx';
				}
				else 
				{
					$link->commit();
					$response['st'] = true;
				}

				// $query_insb = $link->query(
				// 	"INSERT INTO 
				// 		lost_gc_barcodes
				// 	(
				// 		lostgcb_barcode, 
				// 		lostgcb_repid, 
				// 		lostgcb_status
				// 	) 
				// 	VALUES 
				// 	(
				// 		'',
				// 		'',
				// 		''
				// 	)
				// ");
			}


		}		
		echo json_encode($response);
	}
	elseif ($action=='checksession') 
	{
		$response['st'] = true;
		if(!isset($_SESSION['gc_id']))
		{
			$response['st'] = false;
		}
		echo json_encode($response);
	}
	elseif ($action=='getAssignatoriesDetails') 
	{
		$id = $_POST['aid'];

		$response['st'] = false;
		
		$query = $link->query(
			"SELECT 
				assig_id,
			    assig_dept,
			    assig_position,
			    assig_name
			FROM 
				assignatories 
			WHERE 
				assig_id='$id'
		");

		if($query)
		{
			$row = $query->fetch_object();
			$response['st'] = true;
			$response['name'] = $row->assig_name;
			$response['position'] = $row->assig_position;
		}
		else 
		{
			$response['msg'] = $link->error;
		}

		echo json_encode($response);
	}
	elseif($action=='scanInsGCForRefund')
	{
		$barcode = $_POST['barcode'];
		$cusid = $_POST['cusid'];
		$response['st'] = false;

		$table = 'gc';
		$select = "gc_treasury_release,
		    gc_allocated,
		    gc_released,
		    gc_cancelled,
		    gc_ispromo";
		$where = "barcode_no='".$barcode."'";
		$join = '';
		$limit = '';

		$gc = getSelectedData($link,$table,$select,$where,$join,$limit);

		if(count($gc) == 0)
		{
			$response['msg'] = 'GC Barcode #'.$barcode.' not found.';
		}
		else 
		{
			if($gc->gc_treasury_release=='')
			{
				$response['msg'] = 'GC Barcode #'.$barcode.' not yet sold.';
			}
			else 
			{
				//check if gc already verified

				$table = 'store_verification';
				$select = 'vs_barcode';
				$where = "vs_barcode='".$barcode."'";
				$join = '';
				$limit = '';

				$verify = getSelectedData($link,$table,$select,$where,$join,$limit);

				if(count($verify)>1)
				{
					$response['msg'] = 'GC Barcode #'.$barcode.' already verified.';
				}
				else 
				{
					//check customer	
					$table = 'institut_transactions_items';
					$select = 'institutr_trtype,institutr_cusid';
					$where = "institut_transactions_items.instituttritems_barcode='".$barcode."'";
					$join = 'INNER JOIN
						institut_transactions
						ON
							institut_transactions.institutr_id = institut_transactions_items.instituttritems_trid';
					$limit = 'ORDER BY
							institut_transactions_items.instituttritems_id
						DESC
						LIMIT 1';

					$instr = getSelectedData($link,$table,$select,$where,$join,$limit);

					if(count($instr)==0)
					{
						$response['msg'] = 'GC Barcode #'.$barcode.' not found.';
					}
					else 
					{
						if($instr->institutr_cusid!=$cusid)
						{
							$response['msg'] = 'GC Barcode #'.$barcode.' has invalid customer name.';
						}
						else 
						{
							
						}
					}

				}

			}
		}

		echo json_encode($response);
	}
	elseif ($action=='addPaymentFund') 
	{
		$response['st'] = false;

		$paymentname = $_POST['paymentname'];

		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Session already expired. Please reload to login.';
		}
		else 
		{

			if(!empty($paymentname))
			{
				// check if payment fund already exist
				if(checkIfExist($link,'pay_desc','payment_fund','pay_desc',$paymentname))
				{
					$response['msg'] = 'Payment Fund Name already Exist.';
				}
				else 
				{
					$query = $link->query(
						"INSERT INTO 
							payment_fund
						(
						    pay_desc, 
						    pay_status, 
						    pay_dateadded, 
						    pay_addby
						) 
						VALUES 
						(
						    '$paymentname',
						    'active',
						    NOW(),
						    '".$_SESSION['gc_id']."'
						)
					");

					if(!$query)
					{
						$response['msg'] = $link->error;
					}
					else
					{
						$response['st'] = true;
					}
					
				}

			}
			else 
			{
				$response['msg'] = 'Please input customer.';
			}			
		}

		echo json_encode($response);
	}
	elseif ($action=='barcodeCheckLoad') 
	{
		$query = $link->query("
			SELECT 
				barcode_checker.bcheck_barcode,
			    barcode_checker.bcheck_date,
			    CONCAT(users.firstname,' ',users.lastname) as scanby
			FROM 
				barcode_checker 
			INNER JOIN
				users
			ON
				users.user_id = barcode_checker.bcheck_checkby
			ORDER BY
				barcode_checker.bcheck_date
			DESC
			LIMIT 5
		");

		if(!$query)
		{
			echo $link->error;
			exit();
		}

		while ($row = $query->fetch_object()): 
			$denom = 0;
			$foundreg = false;
			$foundspec = false;

			$query_existreg = $link->query(
				"SELECT 
					barcode_no 
				FROM 
					gc 
				WHERE 
					barcode_no='$row->bcheck_barcode'
			");

			if(!$query_existreg)
			{
				$denom = 0;
			}
			else 
			{
				if($query_existreg->num_rows > 0)
				{
					$foundreg = true;

					$denom = getDenominationByBarcode($link,$row->bcheck_barcode);
				}

			}

			$query_existspec = $link->query(
				"SELECT 
					spexgcemp_barcode 
				FROM 
					special_external_gcrequest_emp_assign 
				WHERE 
					spexgcemp_barcode='$row->bcheck_barcode'
			");

			if(!$query_existspec)
			{
				$denom = 0;
			}
			else 
			{
				if($query_existspec->num_rows > 0)
				{
					$foundspec = true;

					$denom = getSpecialGCDenomination($link,$row->bcheck_barcode);
				}
			}


		?>
			<tr>
				<td><?php echo $row->bcheck_barcode; ?></td>
				<td><?php echo number_format($denom,2); ?></td>
				<td><?php echo _dateFormat($row->bcheck_date); ?></td>
				<td><?php echo ucwords($row->scanby); ?></td>
			</tr>
		<?php
			endwhile;

	}
	elseif($action=='barcodechecker')
	{
		$response['st'] = false;
		$barcode = $_POST['barcode'];
		$denom = 0;
		$gctype = '';

		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Login to Continue.';
		}
		else 
		{
			$query = $link->query(
				"SELECT 
					barcode_checker.bcheck_barcode,
				    barcode_checker.bcheck_date,
				    CONCAT(users.firstname,' ',users.lastname) as scanby
				FROM 
					barcode_checker 
				INNER JOIN
					users
				ON
					users.user_id = barcode_checker.bcheck_checkby
				WHERE 
					barcode_checker.bcheck_barcode = '".$barcode."'
			");

			if(!$query)
			{
				$response['msg'] = $link->error;
			}
			else 
			{
				if($query->num_rows > 0)
				{
					$row = $query->fetch_object();
					$response['msg'] = 'GC Barcode #'.$barcode.' already scanned by '.ucwords($row->scanby).'. Date:'._dateFormat($row->bcheck_date); 
				}
				else 
				{
					//check barcode exist

					$foundreg = false;
					$foundspec = false;
					$error = false;
					$errormsg = '';

					$query_existreg = $link->query(
						"SELECT 
							barcode_no 
						FROM 
							gc 
						WHERE 
							barcode_no='$barcode'
					");

					if(!$query_existreg)
					{
						$response['msg'] = $link->error;
					}
					else 
					{
						if($query_existreg->num_rows > 0)
						{
							$foundreg = true;
							$gctype = 'regular';
							$denom = getDenominationByBarcode($link,$barcode);
						}

					}

					$query_existspec = $link->query(
						"SELECT 
							spexgcemp_barcode 
						FROM 
							special_external_gcrequest_emp_assign 
						WHERE 
							spexgcemp_barcode='$barcode'
					");

					if(!$query_existspec)
					{
						$response['msg'] = $link->error;
					}
					else 
					{
						if($query_existspec->num_rows > 0)
						{
							$foundspec = true;
							$gctype = 'special external';
							$denom = getSpecialGCDenomination($link,$barcode);
						}
					}

					if($foundspec || $foundreg)
					{
						$query_ins = $link->query(
							"INSERT INTO 
								barcode_checker
							(
								bcheck_barcode, 
								bcheck_checkby, 
								bcheck_date
							) 
							VALUES 
							(
							    '$barcode',
							    '".$_SESSION['gc_id']."',
							    NOW()
							)
						");

						if(!$query_ins)
						{
							$response['msg'] = $link->error;
						}
						else 
						{
							$response['gctype'] = $gctype;
							$response['st'] = true;
							$response['msg'] = 'GC Barcode #'.$barcode.' checked.<br /> Denomination: '.number_format($denom,2);
						}
					}
					else 
					{
						$response['msg'] = 'GC Barcode #'.$barcode.' not found.';
					}

				}
			}	
		}
		echo json_encode($response);
	}
	elseif ($action=='specialExternalGCAddEmp') 
	{
		$response['st'] = false;	   

		$response['st'] = 0;
		$imageError = 0;
		$haspic = true;

		$reqid = $_POST['reqid'];

		$isreqIDExist = checkifExist2($link,'spexgc_id','special_external_gcrequest','spexgc_id','spexgc_addemp',$reqid,'pending');
		$isreqIDExistItems = checkIfExist($link,'specit_id','special_external_gcrequest_items','specit_trid',$reqid);
		$isDsame = true;


	    if(count($_FILES['docs']['name'])==0)
	    {
	      $haspic = false;
	    }

	    if($haspic)
	    {
	      $imageError = checkDocumentsMutiple($_FILES);
	    }

	    if(!isset($_SESSION['gc_id']))
	    {
	      $response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
	    }
	    elseif(!$isreqIDExist)
	    {
	    	$response['msg'] = 'Request ID not found.';
	    }
        elseif(!isset($_SESSION['empAssign']))
        {
        	$response['msg'] = 'Please add GC Holder.';
        }
        elseif(!$isreqIDExistItems)
        {
        	$response['msg'] = 'Request ID is invalid.';
        }
        else 
        {
        	$query = $link->query(
        		"SELECT 
        			* 
        		FROM 
        			special_external_gcrequest_items 
        		WHERE 
        			specit_trid='$reqid'
        	");

        	if(!$query)
        	{
        		$response['msg'] = $link->error;
        	}
        	else 
        	{
        		$rowdemons	 = [];
        		while ($row = $query->fetch_object()) 
        		{
	        		$qtydb = 0;
	        		$qtySess = 0;

        			$qtydb = $row->specit_qty;        			
					if(isset($_SESSION['empAssign']))
					{
						foreach ($_SESSION['empAssign'] as $key => $value) {
							if($value['denom']==$row->specit_denoms)
							{
								$qtySess++;
							}
						}
					}

					if(intval($qtydb)!=intval($qtySess))
					{
						$isDsame = false;
						break;
					}

					$rowdenoms[] = $row->specit_denoms;
        			
        		}

        		if(!$isDsame)
        		{
        			$response['msg'] = 'Denom Qty and Emp Scanned must equal.';
        		}
        		else 
        		{
        			$link->autocommit(false);
        			$queryError = false;
					for($x=0;$x<count($rowdenoms);$x++)
					{
						if(isset($_SESSION['empAssign']))
						{
							foreach ($_SESSION['empAssign'] as $key => $value) 
							{

								if($value['denom']===$rowdenoms[$x])
								{
									$query_emp = $link->query(
										"INSERT INTO 
										special_external_gcrequest_emp_assign
										(
											spexgcemp_trid, 
											spexgcemp_denom, 
											spexgcemp_fname, 
											spexgcemp_lname, 
											spexgcemp_mname, 																																																																					
											spexgcemp_extname 
										) 
										VALUES 
										(
											'$reqid',
											'".$link->real_escape_string($value['denom'])."',
											'".$link->real_escape_string(htmlentities($value['firstname']))."',
											'".$link->real_escape_string(htmlentities($value['lastname']))."',
											'".$link->real_escape_string(htmlentities($value['middlename']))."',
											'".$link->real_escape_string(htmlentities($value['extname']))."'                    
										)
									");

									if(!$query_emp)
									{ 
										$queryError = true;
									}									
								}

							}
						}
					}

					if($queryError)
					{
						$response['msg'] = $link->error;
					}
					else 
					{
						$query_up = $link->query(
							"UPDATE 
								special_external_gcrequest 
							SET 
								spexgc_addempaddby='".$_SESSION['gc_id']."',
							    spexgc_addempdate=NOW(),
							    spexgc_addemp='done'
							WHERE 
								spexgc_id='$reqid'
							AND
								spexgc_status='pending'
							AND
								spexgc_addemp='pending'
   
						");

						if(!$query_up)
						{
							$response['msg'] = $link->error;
						}
						else 
						{
							$queryError = false;
							$errorUpload = false;
							if($haspic && !$imageError)
							{
					            $pathfolder = 'externalDocs';
					            for($i=0; $i < count($_FILES['docs']['name']); $i++) 
					            {

									$imagename = externalDocumentFilename($external = $_FILES['docs']['name'][$i],$i,$reqnum);
									if(move_uploaded_file($_FILES['docs']['tmp_name'][$i], "assets/images/".$pathfolder."/".$imagename))
									{
										$fullpathfolder = $pathfolder.'/'.$imagename;
										$query_files = $link->query(
											"INSERT INTO 
											documents
											(
												doc_trid, 
												doc_type, 
												doc_fullpath
											) 
											VALUES 
											(
												'$reqid',
												'Special External GC Request',
												'$fullpathfolder'
											)
										");

										if(!$query_files)
										{
											$queryError = true;
										}
									}
									else 
									{
										$errorUpload = true;
										break;
									}
					            }
							}

							if($queryError)
							{
								$response['msg'] = $link->error;
							}
							elseif($errorUpload)
							{
								$response['msg'] = 'Error uploading files.';
							}
							else 
							{
								$link->commit();
								$response['st'] = true;
							}
						}
					}

        			//dri
        		}
        	}
        }




		//$response['msg'] = $reqid;


		echo json_encode($response);
	}
	elseif ($action=='iteodstore') 
	{

		$password = $link->real_escape_string(trim($_POST['password']));
		$response['st'] = false;

		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Login to Continue.';
		}
		else 
		{
			$password = md5($password);
			if(!checkifExist2($link,'username','users','user_id','password',$_SESSION['gc_id'],$password))
			{
				$response['msg'] = 'Incorrect Password.';
			}
			else 
			{
				$response['st'] = true;
			}

		}

		//$response['msg'] = $_SESSION['gc_id'];

		echo json_encode($response);
	}
	elseif ($action=='iteodstoreprocess') 
	{
		$response['st'] = false;

		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Login to Continue.';
		}
		else 
		{

			$link->autocommit(FALSE);

			$query = $link->query(
				"INSERT INTO 
					store_eod
				(
				    steod_by, 
				    steod_datetime
				) 
					VALUES 
				(
				    '".$_SESSION['gc_id']."',
				    NOW()
				)
			");

			if(!$query)
			{
				$response['msg'] = $link->error;
			}
			else 
			{
				$last_insert = $link->insert_id;
				//$res = getTextFileBalancesIT($link,$last_insert,$verificationfolder,$archivefolder);		
				$res = getTextFileBalancesITv2($link,$last_insert,$verificationfolder,$archivefolder,$todays_time);	
				
				if($res[0])
				{
					$link->commit();
					$response['id'] = $last_insert;
					$response['st'] = true;
				}
				else 
				{
					$response['msg'] = $res[1];		
				}		
			}		
		}
		echo json_encode($response);
	}
	elseif ($action=='setasredeemedsgc') 
	{
		$response['st'] = false;

		$balance = 0;
		$purchase = 0;

		$barcode = $link->real_escape_string(trim($_POST['barcode']));		
		$note = $link->real_escape_string(trim($_POST['note']));

		if(isset($_POST['balance']))
		{
			$balance = $link->real_escape_string(trim($_POST['balance']));
		}

		$balance = floatval($balance);

		//check barcode if already verified

		$rows = numRowsWhereTwo($link,'store_verification','vs_barcode','vs_barcode','vs_tf_used',$barcode,'');

		//get denomination

		$denom = getField($link,'vs_tf_denomination','store_verification','vs_barcode',$barcode);

		$purchase = floatval($denom) - $balance;

		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Login to Continue.';
		}
		elseif(empty($note) || empty($barcode))
		{
			$response['msg'] = 'Please fill in all required fields.';
		}
		elseif($rows==1)
		{
			$link->autocommit(FALSE);
			$query_ins1 = $link->query(
				"INSERT INTO 
					manual_setgc
				 (
					mgc_manualtype,
					mgc_note,
					mgc_by, 
					mgc_date
				 ) 
				 VALUES 
				 (
					'Redeemed',
					'$note',
					'".$_SESSION['gc_id']."',
					NOW()
				 )
			");

			if(!$query_ins1)
			{
				$response['msg'] = $link->error;
			}
			else 
			{
				$last_id = $link->insert_id;
				$query_ins2 = $link->query(
					"UPDATE 
						store_verification 
					SET 
					    vs_tf_used='*',
					    vs_tf_balance='$balance',
					    vs_tf_purchasecredit='$purchase',
					    vs_tf_addon_amt='0.00',
					    vs_trans_manualid='$last_id'
					WHERE 
					 	vs_barcode='$barcode'
				");

				if(!$query_ins2)
				{

					$response['msg'] = $link->error;
				}
				else 
				{
					$link->commit();
					$response['st'] = true;
				}
			}
		}


		//$response['msg'] = 'sample';


		echo json_encode($response);
	}
	elseif ($action=='getAllCustomer') 
	{
		$response['st'] = false;

		$arr_data = [];


		$arr_data[] = array(
			'val'	=> "1",
			'name'	=> "All Customers"
		);

		//get all institution customer
		$query_ins = $link->query(
			"SELECT 
				ins_id,
				ins_name    
			FROM 
				institut_customer			 	
		");

		if($query_ins)
		{
			while ($row_ins = $query_ins->fetch_object()) 
			{
				$arr_data[] = array(
					'val'	=> "1|".$row_ins->ins_id,
					'name'	=> $row_ins->ins_name
				);
			}
		}
		
		//get all stores

		$query_st = $link->query(
			"SELECT 
				store_id,
				store_name
			FROM 
				stores 
			WHERE 
				store_status='active'
		");

		if($query_st)
		{
			while ($row_st = $query_st->fetch_object()) 
			{
				$arr_data[] = array(
					'val'	=> "2|".$row_st->store_id,
					'name'	=> $row_st->store_name
				);
			}			
		}

		// get all special external

		$query_sp = $link->query(
			"SELECT 
				spcus_id,
			    spcus_companyname    
			FROM 
				special_external_customer
		");

		if($query_sp)
		{
			while ($row_sp = $query_sp->fetch_object()) 
			{
				$arr_data[] = array(
					'val'	=> "3|".$row_sp->spcus_id,
					'name'	=> $row_sp->spcus_companyname
				);
			}	
		}

		$response['customer'] = $arr_data;
		$response['st'] = true;
		echo json_encode($response);
	}
	elseif ($action=="getAllStores") 
	{
		$response['st'] = false;

		$arr_data = [];

		$query_st = $link->query(
			"SELECT 
				store_id,
				store_name
			FROM 
				stores 
			WHERE 
				store_status='active'
		");

		if($query_st)
		{
			while ($row_st = $query_st->fetch_object()) 
			{
				$arr_data[] = array(
					'val'	=> $row_st->store_id,
					'name'	=> $row_st->store_name
				);
			}			
		}

		$response['customer'] = $arr_data;
		$response['st'] = true;
		echo json_encode($response);
	}
	elseif ($action=='exportdatacfs') 
	{
		$response['st'] = false;

		$hasrows = false;

		$datatype = $_POST['dtype'];
		$stcus = $_POST['stselect'];

		$month = $_POST['month'];
		$year = $_POST['year'];

		//SELECT * FROM store_verification WHERE YEAR(vs_date) = '2017' AND MONTH(vs_date) = '12'

		// 		SELECT 
		// 	* 
		// FROM 
		// 	store_verification 
		// WHERE 
		// 	(YEAR(vs_date) = '2017' 
		// AND 
		// 	MONTH(vs_date) = '12')
		// OR
		// 	(YEAR(vs_reverifydate) = '2017' 
		// AND 
		// 	MONTH(vs_reverifydate) = '12')

		if($datatype=='vgc')
		{
			$data = getVerifiedDateByMonthAndYear($link,$month,$year,$stcus);
		}
		if(count($data)>0)
		{
			$response['st'] = true;
		}
		else 
		{
			$response['msg'] = "No result found.";
		}
		echo json_encode($response);
	}
	elseif ($action=='manualverifygc') 
	{
		// echo $verificationfolder.'<br />';
		// echo $archivefolder;

		$tfilefolderexist = false;

		$response['st'] = false;
		$balance = 0;
		$hasError = false;
		$isRevalidateGC = false;

		$dateVerify = $_POST['dateverified'];
		$verifymode = $_POST['verifymode'];

		$dateToVerify = _dateFormatoSql($dateVerify);

		if(isset($_POST['balance']))
		{
			$balance = str_replace(",", "", $_POST['balance']);
		}

		$store = $_POST['bu'];
		$txtfolder = $_POST['txtfolder'];
		$remarks = $_POST['remarks'];
		$gc = $_POST['barcode'];
		$cusid = $_POST['cusid'];
		$gctype = 0;
		$isFound = false;

		$isVerified = false;
		$verifyGC = false;
		$isRevalidateGC = false;

		$trnum = getManualNumberGC($link,'verification');

        //check if gc is regular/promo/special
        if(checkIfExist($link,'barcode_no','gc','barcode_no',$gc))
        {
            //check if gc is institution 
            if(numRows2($link,'institut_transactions_items','instituttritems_barcode',$gc) > 0)
            {
                $isFound = true;
                $gctype = 1;
            }

            //check if gc already sold
            $sold_info = checkIfGCAlreadySold($link,$gc);
            if(!is_null($sold_info))
            {
                $isFound = true;
                $gctype = 1;
            }

            if(numRows2($link,'promogc_released','prgcrel_barcode',$gc))
            {
                $isFound = true;
                $gctype = 4;
            }

            if($isFound)
            {               
                $tfilext = '.'.getGCTextfileExtension($link,'txtfile_extension_internal');
                $barcodetf = $gc.$tfilext;
                $denom = getDenominationByBarcode($link,$gc);
            }
        }
        elseif(checkIfExist($link,'spexgcemp_barcode','special_external_gcrequest_emp_assign','spexgcemp_barcode',$gc)) 
        {
            $table = 'special_external_gcrequest_emp_assign';
            $select = 'spexgcemp_denom';
            $where = "special_external_gcrequest_emp_assign.spexgcemp_barcode='".$gc."'
                AND
                    approved_request.reqap_approvedtype='special external releasing'";
            $join = 'INNER JOIN
                approved_request
                ON
                approved_request.reqap_trid = special_external_gcrequest_emp_assign.spexgcemp_trid';
            $limit = '';
            $special = getSelectedData($link,$table,$select,$where,$join,$limit);

            if(count($special) > 0)
            {       

                $denom = getSpecialGCDenom($link,$gc);
                $tfilext = '.'.getGCTextfileExtension($link,'txtfile_extension_external');
                $barcodetf = $gc.$tfilext;
                $isFound = true;
                $gctype=3;
            }
        }
        
        //get store textfile folder
        $iptxtfile = "";
        if($txtfolder=='txtcustom')
        {
        	$iptxtfile = $verificationfolder;
        }
        elseif($txtfolder=='txtarchive')
        {
        	$iptxtfile = $archivefolder;
        }
        elseif ($txtfolder=='txtstore') 
        {
        	$iptxtfile = getField($link,'store_textfile_ip','stores','store_id',$store);
        }        

        if(file_exists($iptxtfile))
        {
            $tfilefolderexist = true;
        }

        if(!isset($_SESSION['gc_id']))
        {
            $response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
        }
        elseif (!$tfilefolderexist) 
        {
        	$response['msg'] = 'Can\'t access '.$iptxtfile;
        }
        elseif(!$isFound)
        {
            $response['msg'] = 'GC Barcode # '.$gc.' not found.';
        }
        elseif(empty($cusid))
        {
            $response['msg'] = 'Please select customer.';
        }
        elseif(!checkIfExist($link,'cus_id','customers','cus_id',$cusid))
        {
            $response['msg'] = 'Customer not found.';
        }
        elseif ($dateToVerify > _dateFormatoSql($todays_date)) 
        {
        	$response['msg'] = 'Invalid Verification date.';
        }
        else
        {
            $customerdetails =  getCustomerDetailsByID($link,$cusid);
            $mid_initial = is_null($customerdetails->cus_mname)? '': strtoupper(substr($customerdetails->cus_mname,0,1)).'.';

            //check  if gc already verified and used or gc is revalidated
            $verifiedGCDetails = checkIFGCAlreadyVerified($link,$gc);
            if(is_null($verifiedGCDetails))
            {
                $isVerified = false; 
            }
            else 
            {
                $isVerified = true;
            }

            if($isVerified)
            {
                if($verifiedGCDetails->vs_tf_used=='*')
                {
                    $response['msg'] = 'GC Barcode # '.$gc.' is already verified and used.';
                }
                elseif ($verifiedGCDetails->vs_date<=$dateToVerify && $verifiedGCDetails->vs_tf_used=='') 
                {
                    $revalidated = checkforRevalidated($link,$gc);

                    // var_dump($revalidated);
                    // exit();
                    //var_dump($revalidated);
                    if(is_null($revalidated))
                    {
                        $response['msg'] = 'GC Barcode # '.$gc.' is already verified.';
                    }
                    elseif ($revalidated->reval_revalidated != '0') 
                    {
                        $response['msg'] = 'GC Barcode # '.$gc.' is already reverified.';
                    }
                    elseif ($revalidated->trans_store != $storeid) 
                    {
                        $response['msg'] = 'GC Revalidated at '.$revalidated->store_name.'<br>
                        Date Revalidated: '._dateFormat($revalidated->trans_datetime).'<br />';
                    }
                    else 
                    {
                        $recent_cc = getCustomerCodeLastVerification($link,$gc);
                        if($cusid==$recent_cc)
                        {
                            if(_dateFormatoSql($revalidated->trans_datetime)==$todays_date)
                            {
                                $verifyGC = true;
                                $isRevalidateGC = true;
                            }
                            else 
                            {
                                $response['msg'] = 'GC Barcode # '.$gc.' already verified. <br />
                                Revalidation Info <br />
                                Store Revalidated: '.$revalidated->store_name.'<br>
                                Date Revalidated: '._dateFormat($revalidated->trans_datetime).'<br />';
                            }
                        }
                        else 
                        {                                           
                            $fullname = getCustomerFullname($link,$recent_cc);
                            $response['msg'] = 'Invalid Customer Information</br>
                            Verification Info<br />
                            Store Validated: '.$revalidated->store_name.'<br>
                            Date: '._dateFormat($revalidated->trans_datetime).'<br />
                            Time:'._timeFormat($revalidated->trans_datetime).'<br />                                          
                            Customer Name: '.ucwords($fullname);            
                        }
                    }
                }
            }
            else 
            {
                $verifyGC = true;
            }

            $promo_gcexpired = false; 

            if($gctype==4)
            {
                //get date gc released from marketing
                $date_rel = getField($link,'prgcrel_at','promogc_released','prgcrel_barcode',$gc);

                $days = getDateTo($link,'promotional_gc_verification_expiration');

                $end_date = date('Y-m-d', strtotime("+".$days,strtotime($date_rel)));
                
                if(_dateFormatoSql($end_date) < $dateToVerify)
                {
                    $promo_gcexpired = true;
                }
            }

            if($promo_gcexpired)
            {
                $response['msg'] = 'Promotional GC Barcode #'.$gc.' already expired.';
            }
            else
            {
                // check if gc was reported lost
                $table = 'lost_gc_barcodes';
                $select = 'lost_gc_barcodes.lostgcb_denom,
                    lost_gc_barcodes.lostgcb_status,
                    stores.store_name,
                    lost_gc_details.lostgcd_owname,
                    lost_gc_details.lostgcd_address,
                    lost_gc_details.lostgcd_contactnum,
                    lost_gc_details.lostgcd_datereported,
                    lost_gc_details.lostgcd_datelost';
                $where = "lost_gc_barcodes.lostgcb_barcode='".$gc."'";
                $join = 'INNER JOIN
                        lost_gc_details
                    ON
                        lost_gc_details.lostgcd_id = lost_gc_barcodes.lostgcb_repid
                    INNER JOIN
                        stores
                    ON
                        stores.store_id = lost_gc_details.lostgcd_storeid';
                $limit = '';

                $lost = getSelectedData($link,$table,$select,$where,$join,$limit);


                if(count($lost) > 0 && empty($lost->lostgcb_status))
                {
                    $response['msg'] = "GC Barcode # ".$gc." reported lost.<br / >
                    Date Reported: <span class='tit'>"._dateFormat($lost->lostgcd_datereported)."</span><br / >
                    Owner's Name: <span class='tit'>".ucwords($lost->lostgcd_owname)."</span><br / >
                    Address: <span class='tit'>".$lost->lostgcd_address."</span><br / >
                    Contact #: <span class='tit'>".$lost->lostgcd_contactnum."</span><br / >";

                }
                else 
                {
                	//dri
                	$link->autocommit(FALSE);
                	$msg = "";

                    if($verifyGC)
                    {                	                     

                        $query_insm = $link->query(
                        	"INSERT INTO 
									manual_setgc
								(
								    mgc_manualnum, 
								    mgc_manualtype, 
								    mgc_note, 
								    mgc_by, 
								    mgc_date
								)
								VALUES 
								(
								    '$trnum',
								    'verification',
								    '$remarks',
								    '".$_SESSION['gc_id']."',
								    NOW()
								)
                        ");

                        if(!$query_insm)
                        {
                        	$msg = $link->error;
                        	$hasError = true;
                        }
                        else 
                        {
                        	$lastidmanver = $link->insert_id;

                        	//dri
	                        if($verifymode=='verifymodecurrent')
	                        {
	                            if($isRevalidateGC)
	                            {
	                                $query_update = $link->query(
	                                    "UPDATE 
	                                        store_verification 
	                                    SET 
	                                        vs_reverifydate=NOW(),
	                                        vs_reverifyby='".$_SESSION['gc_id']."',
	                                        vs_tf_eod=''
	                                    WHERE 
	                                        vs_barcode='$gc'

	                                "); 

	                                if(!$query_update)
	                                {
	                                    $msg = $link->error;
	                                    $hasError = true;
	                                }
	                            }
	                            else 
	                            {
	                                $query_ins = $link->query(
	                                  "INSERT INTO 
	                                    store_verification
	                                  (
	                                    vs_barcode, 
	                                    vs_cn, 
	                                    vs_by, 
	                                    vs_date, 
	                                    vs_time, 
	                                    vs_tf, 
	                                    vs_store,
	                                    vs_tf_balance,
	                                    vs_gctype,
	                                    vs_tf_denomination,
	                                    vs_trans_manualid
	                                  ) 
	                                    VALUES 
	                                  (
	                                    '$gc',
	                                    '$cusid',
	                                    '".$_SESSION['gc_id']."',
	                                    '$todays_date',
	                                    '$todays_time',
	                                    '$barcodetf',
	                                    '$store',
	                                    '$denom',
	                                    '$gctype',
	                                    '$denom',
	                                    '$lastidmanver'
	                                  )
	                                ");

	                                if(!$query_ins)
	                                {
	                                  $response['msg'] = $link->error;
	                                  $hasError = true;
	                                }
	                            }                        
	                        }
	                        elseif ($verifymode=='verifymodedatewoutr') 
	                        {

								// check eod 

								//                  	$query_eod_check = $link->query(
								//                  		"SELECT 
								// 	vs_id 
								// FROM 
								// 	store_verification 
								// WHERE 
								// 	vs_date = '$dateToVerify'
								// LIMIT 1
								//                  	"); 

								//                  	if(!$query_eod_check)
								//                  	{
								//                  		$msg = $link->error;
								//                  		$hasError = true;
								//                  	}
								//                  	else 
								//                  	{
								//                  		if($query_eod_check->num_rows > 0)
								//                  		{

								//                  		}



								//                  	}

		                    	$purchasamt = 0;

		                    	$purchasamt = floatval($denom) - floatval($balance);

		                    	//if($purchasamt > )

		                    	if($purchasamt < 0)
		                    	{
		                    		$msg = "Balance must be valid.";
		                    		$hasError = true;
		                    	}
		                    	else 
		                    	{
		                            if($isRevalidateGC)
		                            {
		                                $query_update = $link->query(
		                                    "UPDATE 
		                                        store_verification 
		                                    SET 
		                                        vs_reverifydate=NOW(),
		                                        vs_reverifyby='".$_SESSION['gc_id']."',
		                                        vs_tf_eod=''
		                                    WHERE 
		                                        vs_barcode='$gc'

		                                "); 

		                                if(!$query_update)
		                                {
		                                    $response['msg'] = $link->error;
		                                    $hasError = true;
		                                }
		                            }
		                            else 
		                            {

		                                $query_ins = $link->query(
		                                  "INSERT INTO 
		                                    store_verification
		                                  (
		                                    vs_barcode, 
		                                    vs_cn, 
		                                    vs_by, 
		                                    vs_date, 
		                                    vs_time, 
		                                    vs_tf, 
		                                    vs_tf_used,
		                                    vs_store,
		                                    vs_tf_balance,
		                                    vs_tf_purchasecredit,
		                                    vs_tf_eod,
		                                    vs_gctype,
		                                    vs_tf_denomination,
		                                    vs_trans_manualid
		                                  ) 
		                                    VALUES 
		                                  (
		                                    '$gc',
		                                    '$cusid',
		                                    '".$_SESSION['gc_id']."',
		                                    '$dateToVerify',
		                                    '$todays_time',
		                                    '$barcodetf',
		                                    '*',
		                                    '$store',
		                                    '$balance',
		                                    '$purchasamt',		                                    
		                                    '1',
		                                    '$gctype',
		                                    '$denom',
		                                    '$lastidmanver'
		                                  )
		                                ");

		                                if(!$query_ins)
		                                {
		                                  $response['msg'] = $link->error;
		                                  $hasError = true;
		                                }
		                            }
		                    	}
	                        }

	                    }


	                    if($hasError)
	                    {
	                    	$response['msg'] = $msg;
	                    }
	                    else 
	                    {
	                        $denom = number_format($denom,2);
	                        $denomstext = str_replace(",", "", $denom);
	                        $sd='';                         
	                        $f = $iptxtfile.'/'.$gc.$tfilext;
	                        $fh = fopen($f, 'w') or die("cant open file");
	                        $sd.="000,".$cusid.",0,".strtoupper($customerdetails->cus_fname.' '.$mid_initial.' '.$customerdetails->cus_lname)." ".
	                        "\r\n".
	                        "001,".$denomstext.
	                        "\r\n".
	                        "002,0".
	                        "\r\n".
	                        "003,0".
	                        "\r\n".
	                        "004,".$denomstext.
	                        "\r\n".
	                        "005,0".
	                        "\r\n".
	                        "006,0".
	                        "\r\n".
	                        "007,0";
	                        fwrite($fh, $sd);         
	                        fclose($fh);


	                    	$link->commit();
	                    	$response['st'] = true;
	                    }

                    }
                }
            }
        }

		//$response['msg'] = $txtfolder;
		echo json_encode($response);
	}
	elseif ($action=='createtextfilesearch')
	{
		$response['st'] = false;

		$stat = $_POST['submitstat'];
		$barcode = $_POST['barcode'];
		$remarks = $_POST['remarks'];

        if(!isset($_SESSION['gc_id']))
        {
            $response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
        }
		elseif($stat!=1)
		{
			$response['msg'] = "Invalid submit status please refresh first.";
		}
		elseif(empty($remarks) || empty($barcode) || empty($stat))
		{
			$response['msg'] = "Please input required fields.";
		}
		else 
		{

			$verifiedGCDetails = checkIFGCAlreadyVerified($link,$barcode);

            if(is_null($verifiedGCDetails))
            {
                $isVerified = false; 
            }
            else 
            {
                $isVerified = true;
            }

            if(!$isVerified)
            {
            	$response['msg'] = "Please verify GC first.";
            }
            else 
            {
            	$cus = getCustomerInfoVerification($link,$barcode);

            	$response['fname'] = $cus->cus_fname;
            	$response['mname'] = $cus->cus_lname;
            	$response['lname'] = $cus->cus_mname;
            	$response['store'] = $cus->store_name;
            	$response['denom'] = $cus->vs_tf_denomination;

            	$response['st'] = true;
            }

		}
		//$response['msg'] = $barcode;

		echo json_encode($response);
	}
	elseif($action=='createtextfile')
	{
		$response['st'] = false;

		$stat = $_POST['submitstat'];
		$barcode = $_POST['barcodenum'];
		$remarks = $_POST['remarks'];

		$trnum = getManualNumberGC($link,'textfile');

        if(!isset($_SESSION['gc_id']))
        {
            $response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
        }
		elseif($stat!=2)
		{
			$response['msg'] = "Invalid submit status please refresh first.";
		}
		elseif(empty($remarks) || empty($barcode) || empty($stat))
		{
			$response['msg'] = "Please input required fields.";
		}
		else 
		{

			$verifiedGCDetails = checkIFGCAlreadyVerified($link,$barcode);

            if(is_null($verifiedGCDetails))
            {
                $isVerified = false; 
            }
            else 
            {
                $isVerified = true;
            }

            if(!$isVerified)
            {
            	$response['msg'] = "Please verify GC first.";
            }
            else 
            {
            	$recent_cc = getCustomerCodeLastVerification($link,$barcode);
            	$customerdetails =  getCustomerDetailsByID($link,$recent_cc);

            	$cus = getCustomerInfoVerification($link,$barcode);

				$ip = getField($link,'store_textfile_ip','stores','store_id',$cus->vs_store);

				$file = $ip.'\\'.$barcode.'.gc';

				if(file_exists($file))
				{
					$response['msg'] = 'Textfile with file name '.$barcode.'.gc already exist.';
				}
				elseif (!file_exists($ip)) 
				{
					$response['msg'] = 'Cannot connect to '.$ip;
				}
				else 
				{
					$link->autocommit(FALSE);
                    $query_insm = $link->query(
                    	"INSERT INTO 
								manual_setgc
							(
							    mgc_manualnum, 
							    mgc_manualtype, 
							    mgc_note, 
							    mgc_by, 
							    mgc_date
							)
							VALUES 
							(
							    '$trnum',
							    'textfile',
							    '$remarks',
							    '".$_SESSION['gc_id']."',
							    NOW()
							)
                    ");

                    if(!$query_insm)
                    {
                    	$response['msg'] = $link->error;

                    }
                    else 
                    {
                    	$mid_initial = is_null($customerdetails->cus_mname)? '': strtoupper(substr($customerdetails->cus_mname,0,1)).'.';    
                    	$denom = getField($link,'vs_tf_denomination','store_verification','vs_barcode',$barcode);
                        $denom = number_format($denom,2);
                        $denomstext = str_replace(",", "", $denom);
                        $sd='';                         
                        $f = $ip.'/'.$barcode.'.gc';
                        $fh = fopen($f, 'w') or die("cant open file");
                        $sd.="000,".$cus->cus_id.",0,".strtoupper($customerdetails->cus_fname.' '.$mid_initial.' '.$customerdetails->cus_lname)." ".
                        "\r\n".
                        "001,".$denomstext.
                        "\r\n".
                        "002,0".
                        "\r\n".
                        "003,0".
                        "\r\n".
                        "004,".$denomstext.
                        "\r\n".
                        "005,0".
                        "\r\n".
                        "006,0".
                        "\r\n".
                        "007,0";
                        fwrite($fh, $sd);         
                        fclose($fh);

                    	$link->commit();
                    	$response['st'] = true;
                    }          	
				}
            }

		}
		//$response['msg'] = $barcode;

		echo json_encode($response);
	}
	elseif($action=='eodtextfilecheck')
	{
		$response['st'] = false;

		$stores = [];

		foreach($_POST['stores'] as $st)         
		{

			$stores[] = $st;

		}

		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Login to Continue.';
		}
		else 
		{

			$res = checkTextfiles($link,$verificationfolder,$stores);		
			
			if($res[0])
			{
				$response['st'] = true;
			}
			else 
			{
				$response['msg'] = $res[1];		
			}		
		}
		//$response['msg'] = 'yow!';

		echo json_encode($response);
	}
	elseif($action=='getAllVerifiedCustomer')
	{
        // storing  request (ie, get/post) global array to a variable  
        $requestData= $_REQUEST;

        $columns = array( 
        // datatable column index  => database column lastname
            0 => 'cus_fname', 
            1 => 'cus_lname',    
            2 => 'cus_mname',
            3 => 'cus_idnumber',
            4 => 'cus_address',
            5 => 'cus_mobile'
        );

        // getting total number records without any search
        $sql = "SELECT 
			cus_id,
			cus_fname,
		    cus_lname,
		    cus_mname,
		    cus_idnumber,
		    cus_mobile,
		    cus_address
        ";
        $sql.="FROM 
        	customers
        ";
        $query=$link->query($sql) or die($link->error);
        $totalData = $query->num_rows;
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        $sql = "SELECT 
			cus_id,
			cus_fname,
		    cus_lname,
		    cus_mname,
		    cus_idnumber,
		    cus_mobile,
		    cus_address           
            ";
        $sql.="FROM 
                customers 
            WHERE
                1=1";
        if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            // if(stripos($released,$requestData['search']['value']) !== false)
            // {                
            //  $sql.=" AND ( gc.barcode_no LIKE '".$requestData['search']['value']."%' ";    
            //  $sql.=" OR denomination.denomination LIKE '".$requestData['search']['value']."%' ";
            //  $sql.=" OR users.firstname LIKE '%".$requestData['search']['value']."%' ";
            //  $sql.=" OR users.lastname LIKE '%".$requestData['search']['value']."%' ";
            //  $sql.=" OR promo_gc.pr_stat ='1'";
            //  $sql.=" OR promo.promo_name LIKE '%".$requestData['search']['value']."%' )";                
            // }
            // else 
            // {
                $sql.=" AND ( cus_fname LIKE '".$requestData['search']['value']."%' ";    
                $sql.=" OR cus_lname LIKE '".$requestData['search']['value']."%' ";
                $sql.=" OR cus_idnumber LIKE '".$requestData['search']['value']."%' ";
                $sql.=" OR cus_mobile LIKE '".$requestData['search']['value']."%' ";
                $sql.=" OR cus_address LIKE '".$requestData['search']['value']."%' ";
                $sql.=" OR cus_mname LIKE '%".$requestData['search']['value']."%' )";
            // }
        }

        $query=$link->query($sql) or die($link->error.'q');
        $totalFiltered = $query->num_rows; // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        /* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */    
        $query=$link->query($sql) or die($link->error.'r');

        $data = array();
        while( $row=$query->fetch_object() ) {  // preparing an array
            $nestedData=array(); 

            $nestedData[] = $row->cus_fname;
            $nestedData[] = $row->cus_lname;
            $nestedData[] = $row->cus_mname;
            $nestedData[] = $row->cus_idnumber;
            $nestedData[] = $row->cus_address;
			$nestedData[] = $row->cus_mobile;
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
            "recordsTotal"    => intval( $totalData ),  // total number of records
            "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data"            => $data   // total data array
        );

        echo json_encode($json_data);  // send data as json format
	}
	elseif ($action=='getAllVerifiedGC') 
	{
        // storing  request (ie, get/post) global array to a variable  
        $requestData= $_REQUEST;

        $columns = array( 
        // datatable column index  => database column lastname
            0 => 'vs_barcode', 
            1 => 'vs_tf_denomination',    
            2 => '',
            3 => '',
            4 => '',
            5 => '',
            6 => '',
        );

        // getting total number records without any search

        $sql = "SELECT 
            store_verification.vs_barcode,
		    store_verification.vs_tf_used,
		    store_verification.vs_tf_denomination,
            store_verification.vs_payto,
		    store_verification.vs_reverifydate,
		    customers.cus_fname,
		    gc.gc_treasury_release,
		    gc_type.gctype,
		    store_verification.vs_gctype,
		    customers.cus_lname,
		    transaction_stores.trans_datetime
        ";
        $sql.="FROM 
                store_verification 
			INNER JOIN
			 	customers
			ON
			 	customers.cus_id = store_verification.vs_cn
			INNER JOIN
			 	gc_type
			ON
			 	gc_type.gc_type_id = store_verification.vs_gctype
			LEFT JOIN
			 	gc
			ON
			 	gc.barcode_no = store_verification.vs_barcode
			LEFT JOIN
			 	transaction_revalidation
			ON
			 	transaction_revalidation.reval_barcode = store_verification.vs_barcode
			LEFT JOIN
			 	transaction_stores
			ON
			 	transaction_stores.trans_sid = transaction_revalidation.reval_trans_id
            WHERE
				store_verification.vs_store = '".$_SESSION['gc_store']."'
      	";
        $query=$link->query($sql) or die($link->error);
        $totalData = $query->num_rows;
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        $regular = 'Regular GC';
        $specialexternal = 'Special External GC';
        $institutional = 'Institutional GC(Regular)';   

        $sql = "SELECT 
            store_verification.vs_barcode,
		    store_verification.vs_tf_used,
		    store_verification.vs_tf_denomination,
		    store_verification.vs_reverifydate,
            store_verification.vs_payto,
		    customers.cus_fname,
		    gc.gc_treasury_release,
		    gc_type.gctype,
		    store_verification.vs_gctype,
		    customers.cus_lname,
		    DATE_FORMAT(transaction_stores.trans_datetime,'%b %d %Y %h:%i %p') as timever          
            ";
        $sql.="FROM 
                store_verification 
			INNER JOIN
			 	customers
			ON
			 	customers.cus_id = store_verification.vs_cn
			INNER JOIN
			 	gc_type
			ON
			 	gc_type.gc_type_id = store_verification.vs_gctype
			LEFT JOIN
			 	gc
			ON
			 	gc.barcode_no = store_verification.vs_barcode
			LEFT JOIN
			 	transaction_revalidation
			ON
			 	transaction_revalidation.reval_barcode = store_verification.vs_barcode
			LEFT JOIN
			 	transaction_stores
			ON
			 	transaction_stores.trans_sid = transaction_revalidation.reval_trans_id
            WHERE
				store_verification.vs_store = '".$_SESSION['gc_store']."'
            AND
                1=1";
        if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            // if(stripos($released,$requestData['search']['value']) !== false)
            // {                
            //  $sql.=" AND ( gc.barcode_no LIKE '".$requestData['search']['value']."%' ";    
            //  $sql.=" OR denomination.denomination LIKE '".$requestData['search']['value']."%' ";
            //  $sql.=" OR users.firstname LIKE '%".$requestData['search']['value']."%' ";
            //  $sql.=" OR users.lastname LIKE '%".$requestData['search']['value']."%' ";
            //  $sql.=" OR promo_gc.pr_stat ='1'";
            //  $sql.=" OR promo.promo_name LIKE '%".$requestData['search']['value']."%' )";                
            // }
            // else 
            // {
                $sql.=" AND ( store_verification.vs_barcode LIKE '".$requestData['search']['value']."%' ";    
                $sql.=" OR store_verification.vs_tf_denomination LIKE '".$requestData['search']['value']."%' ";
                $sql.=" OR store_verification.vs_payto LIKE '".$requestData['search']['value']."%' ";
                $sql.=" OR customers.cus_fname LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR customers.cus_lname LIKE '%".$requestData['search']['value']."%' ";
                if(stripos($institutional,$requestData['search']['value']) !== false)
                {
                    $sql.=" OR gc.gc_treasury_release ='*'";
                }
                $sql.=" OR gc_type.gctype LIKE '%".$requestData['search']['value']."%' )";
            // }
        }

        $query=$link->query($sql) or die($link->error.'q');
        $totalFiltered = $query->num_rows; // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        $sql.=" ORDER BY store_verification.vs_id DESC LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        /* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */    
        $query=$link->query($sql) or die($link->error.'r');

        $data = array();
        while( $row=$query->fetch_object() ) {  // preparing an array
            $nestedData=array(); 

            $datesi = "";
            $payto = $row->vs_payto;
            $html = '';

            // $rel = is_null($row["relat"]) ? '' : $row["relat"];
            // $relby = is_null($row['firstname']) ? '' : ucwords($row['firstname'].' '.$row['lastname']);
            // $stat = '';
            // if(is_null($row["promo_name"]))
            // {
            //     $stat = 'Available';
            // }
            // else if(!is_null($row["promo_name"])&& is_null($row["relat"]))
            // {
            //     $stat = 'Pending';
            // }
            // else 
            // {
            //     $stat = 'Released';
            // }
            $nestedData[] = $row->vs_barcode;
            $nestedData[] = number_format($row->vs_tf_denomination,2);
            $nestedData[] = $row->gc_treasury_release == '' ? ucwords($row->gctype) : ucwords($row->gctype)." (Institutional GC)";

            if($row->vs_gctype=='1' || $row->vs_gctype=='2')
            {
				if($row->gc_treasury_release=='*')
				{
					$select = "institut_transactions.institutr_date";
					$where ="instituttritems_barcode='".$row->vs_barcode."'";
					$join ="LEFT JOIN institut_transactions ON institut_transactions.institutr_id = institut_transactions_items.instituttritems_trid";
					$order = "";
					$daterel = getSelectedData($link,'institut_transactions_items',$select,$where,$join,$order);
					$datesi =  _dateFormat($daterel->institutr_date);                                              
				}
				else 
				{
	                $select = "transaction_stores.trans_datetime";
	                $where ="transaction_sales.sales_barcode='".$row->vs_barcode."' AND transaction_sales.sales_item_status=0";
	                $join ="INNER JOIN transaction_stores ON transaction_stores.trans_sid = transaction_sales.sales_transaction_id";
	                $order = "ORDER BY transaction_sales.sales_id DESC";
	                $daterel = getSelectedData($link,'transaction_sales',$select,$where,$join,$order);
	                $datesi =  _dateFormat($daterel->trans_datetime); 
				}
            }
            elseif ($row->vs_gctype=='3') 
            {
				$select = 'approved_request.reqap_date';
				$where = "spexgcemp_barcode='".$row->vs_barcode."'
				AND
				  approved_request.reqap_approvedtype='special external releasing'";
				$join = 'INNER JOIN
				  approved_request
				ON
				  approved_request.reqap_trid = special_external_gcrequest_emp_assign.spexgcemp_trid';
				$order = '';
				$daterel = getSelectedData($link,'special_external_gcrequest_emp_assign',$select,$where,$join,$order);
				$datesi = _dateFormat($daterel->reqap_date);
			}
            elseif ($row->vs_gctype=='4')
            {
				// promo
				$select  = 'promogc_released.prgcrel_at';
				$where = 'promogc_released.prgcrel_barcode='.$row->vs_barcode;
				$daterel = getSelectedData($link,'promogc_released',$select,$where,'','');
				$datesi = _dateFormat($daterel->prgcrel_at); 
            }

            $html = "<i class='fa fa-fa fa-info faeye' onclick='verifiedGCInfo(".$row->vs_barcode.");' data-toggle='tooltip' data-placement='bottom' title='Verification Info'></i>";
            if($row->timever!=NULL)
            {
            	$html.="<i class='fa fa-th-large fa-reval' onclick='revalidationGCInfo(".$row->vs_barcode.");' aria-hidden='true' data-toggle='tooltip' data-placement='bottom' title='Revalidation Info'></i>";
            }       

            if($row->vs_reverifydate!=NULL)                            
            {
            	$html.="<i class='fa fa-th fa-rever' onclick='reverificationInfo(".$row->vs_barcode.");' aria-hidden='true' data-toggle='tooltip' data-placement='bottom' title='Reverification Info'></i>";            	              
            }

            if($row->vs_tf_used=='*')
            {
            	$html.="<i class='fa fa fa-search falink sstaff' onclick='textfiletranx(".$row->vs_barcode.");' data-toggle='tooltip' data-placement='bottom' title='Transactions'></i>";
            }                                      
            $nestedData[] = $payto;
            $nestedData[] = $datesi;
            $nestedData[] = ucwords($row->cus_fname.' '.$row->cus_lname);
            $nestedData[] = $html;
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
            "recordsTotal"    => intval( $totalData ),  // total number of records
            "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data"            => $data   // total data array
        );

        echo json_encode($json_data);  // send data as json format
	}
	elseif ($action=='getAllVerifiedStoreGC') 
	{
        // storing  request (ie, get/post) global array to a variable  
        $requestData= $_REQUEST;

        $columns = array( 
        // datatable column index  => database column lastname
            0 => 'vs_barcode', 
            1 => 'vs_tf_denomination',    
            2 => '',
            3 => '',
            4 => '',
            5 => '',
            6 => '',
        );

        // getting total number records without any search

        $sql = "SELECT 
		    store_verification.vs_barcode,
		    store_verification.vs_tf_used,
		    store_verification.vs_tf_denomination,
		    store_verification.vs_reverifydate,
		    customers.cus_fname,
		    gc.gc_treasury_release,
		    gc_type.gctype,
		    store_verification.vs_gctype,
		    customers.cus_lname,
		    transaction_stores.trans_datetime,
		    stores.store_name
        ";
        $sql.="FROM 
			    store_verification 
			INNER JOIN
			    customers
			ON
			    customers.cus_id = store_verification.vs_cn
			INNER JOIN
			    stores
			ON
			    stores.store_id = store_verification.vs_store
			INNER JOIN
			    gc_type
			ON
			    gc_type.gc_type_id = store_verification.vs_gctype
			LEFT JOIN
			    gc
			ON
			    gc.barcode_no = store_verification.vs_barcode
			LEFT JOIN
			    transaction_revalidation
			ON
			    transaction_revalidation.reval_barcode = store_verification.vs_barcode
			LEFT JOIN
			    transaction_stores
			ON
			    transaction_stores.trans_sid = transaction_revalidation.reval_trans_id
      	";
        $query=$link->query($sql) or die($link->error);
        $totalData = $query->num_rows;
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        $regular = 'Regular GC';
        $specialexternal = 'Special External GC';
        $institutional = 'Institutional GC(Regular)';   

        $sql = "SELECT 
            store_verification.vs_barcode,
		    store_verification.vs_tf_used,
		    store_verification.vs_tf_denomination,
		    store_verification.vs_reverifydate,
		    customers.cus_fname,
		    gc.gc_treasury_release,
		    gc_type.gctype,
		    store_verification.vs_gctype,
		    customers.cus_lname,
		    DATE_FORMAT(transaction_stores.trans_datetime,'%b %d %Y %h:%i %p') as timever,
		    stores.store_name
            ";
        $sql.="FROM 
                store_verification 
			INNER JOIN
			 	customers
			ON
			 	customers.cus_id = store_verification.vs_cn
			INNER JOIN
			    stores
			ON
			    stores.store_id = store_verification.vs_store
			INNER JOIN
			 	gc_type
			ON
			 	gc_type.gc_type_id = store_verification.vs_gctype
			LEFT JOIN
			 	gc
			ON
			 	gc.barcode_no = store_verification.vs_barcode
			LEFT JOIN
			 	transaction_revalidation
			ON
			 	transaction_revalidation.reval_barcode = store_verification.vs_barcode
			LEFT JOIN
			 	transaction_stores
			ON
			 	transaction_stores.trans_sid = transaction_revalidation.reval_trans_id
            WHERE
                1=1";
        if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            // if(stripos($released,$requestData['search']['value']) !== false)
            // {                
            //  $sql.=" AND ( gc.barcode_no LIKE '".$requestData['search']['value']."%' ";    
            //  $sql.=" OR denomination.denomination LIKE '".$requestData['search']['value']."%' ";
            //  $sql.=" OR users.firstname LIKE '%".$requestData['search']['value']."%' ";
            //  $sql.=" OR users.lastname LIKE '%".$requestData['search']['value']."%' ";
            //  $sql.=" OR promo_gc.pr_stat ='1'";
            //  $sql.=" OR promo.promo_name LIKE '%".$requestData['search']['value']."%' )";                
            // }
            // else 
            // {
                $sql.=" AND ( store_verification.vs_barcode LIKE '".$requestData['search']['value']."%' ";
                $sql.=" OR stores.store_name LIKE '".$requestData['search']['value']."%' ";    
                $sql.=" OR store_verification.vs_tf_denomination LIKE '".$requestData['search']['value']."%' ";
                $sql.=" OR customers.cus_fname LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR customers.cus_lname LIKE '%".$requestData['search']['value']."%' ";
                if(stripos($institutional,$requestData['search']['value']) !== false)
                {
                    $sql.=" OR gc.gc_treasury_release ='*'";
                }
                $sql.=" OR gc_type.gctype LIKE '%".$requestData['search']['value']."%' )";
            // }
        }

        $query=$link->query($sql) or die($link->error.'q');
        $totalFiltered = $query->num_rows; // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        $sql.=" ORDER BY store_verification.vs_id DESC LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        /* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */    
        $query=$link->query($sql) or die($link->error.'r');

        $data = array();
        while( $row=$query->fetch_object() ) {  // preparing an array
            $nestedData=array(); 

            $datesi = "";

            $html = '';

            // $rel = is_null($row["relat"]) ? '' : $row["relat"];
            // $relby = is_null($row['firstname']) ? '' : ucwords($row['firstname'].' '.$row['lastname']);
            // $stat = '';
            // if(is_null($row["promo_name"]))
            // {
            //     $stat = 'Available';
            // }
            // else if(!is_null($row["promo_name"])&& is_null($row["relat"]))
            // {
            //     $stat = 'Pending';
            // }
            // else 
            // {
            //     $stat = 'Released';
            // }
            $nestedData[] = $row->vs_barcode;
            $nestedData[] = number_format($row->vs_tf_denomination,2);
            $nestedData[] = $row->gc_treasury_release == '' ? ucwords($row->gctype) : ucwords($row->gctype)." (Institutional GC)";

            if($row->vs_gctype=='1' || $row->vs_gctype=='2')
            {
				if($row->gc_treasury_release=='*')
				{
					$select = "institut_transactions.institutr_date";
					$where ="instituttritems_barcode='".$row->vs_barcode."'";
					$join ="LEFT JOIN institut_transactions ON institut_transactions.institutr_id = institut_transactions_items.instituttritems_trid";
					$order = "";
					$daterel = getSelectedData($link,'institut_transactions_items',$select,$where,$join,$order);
					$datesi =  _dateFormat($daterel->institutr_date);                                              
				}
				else 
				{
	                $select = "transaction_stores.trans_datetime";
	                $where ="transaction_sales.sales_barcode='".$row->vs_barcode."' AND transaction_sales.sales_item_status=0";
	                $join ="INNER JOIN transaction_stores ON transaction_stores.trans_sid = transaction_sales.sales_transaction_id";
	                $order = "ORDER BY transaction_sales.sales_id DESC";
	                $daterel = getSelectedData($link,'transaction_sales',$select,$where,$join,$order);
	                $datesi =  _dateFormat($daterel->trans_datetime); 
				}
            }
            elseif ($row->vs_gctype=='3') 
            {
				$select = 'approved_request.reqap_date';
				$where = "spexgcemp_barcode='".$row->vs_barcode."'
				AND
				  approved_request.reqap_approvedtype='special external releasing'";
				$join = 'INNER JOIN
				  approved_request
				ON
				  approved_request.reqap_trid = special_external_gcrequest_emp_assign.spexgcemp_trid';
				$order = '';
				$daterel = getSelectedData($link,'special_external_gcrequest_emp_assign',$select,$where,$join,$order);
				$datesi = _dateFormat($daterel->reqap_date);
			}
            elseif ($row->vs_gctype=='4')
            {
				// promo
				$select  = 'promogc_released.prgcrel_at';
				$where = 'promogc_released.prgcrel_barcode='.$row->vs_barcode;
				$daterel = getSelectedData($link,'promogc_released',$select,$where,'','');
				$datesi = _dateFormat($daterel->prgcrel_at); 
            }

            $html = "<i class='fa fa-fa fa-info faeye' onclick='verifiedGCInfo(".$row->vs_barcode.");' data-toggle='tooltip' data-placement='bottom' title='Verification Info'></i>";
            if($row->timever!=NULL)
            {
            	$html.="<i class='fa fa-th-large fa-reval' onclick='revalidationGCInfo(".$row->vs_barcode.");' aria-hidden='true' data-toggle='tooltip' data-placement='bottom' title='Revalidation Info'></i>";
            }       

            if($row->vs_reverifydate!=NULL)                            
            {
            	$html.="<i class='fa fa-th fa-rever' onclick='reverificationInfo(".$row->vs_barcode.");' aria-hidden='true' data-toggle='tooltip' data-placement='bottom' title='Reverification Info'></i>";            	              
            }

            if($row->vs_tf_used=='*')
            {
            	$html.="<i class='fa fa fa-search falink sstaff' onclick='textfiletranx(".$row->vs_barcode.");' data-toggle='tooltip' data-placement='bottom' title='Transactions'></i>";
            }                                      

            $nestedData[] = $datesi;
            $nestedData[] = ucwords($row->store_name);
            $nestedData[] = ucwords($row->cus_fname.' '.$row->cus_lname);
            $nestedData[] = $html;
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
            "recordsTotal"    => intval( $totalData ),  // total number of records
            "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data"            => $data   // total data array
        );

        echo json_encode($json_data);  // send data as json format
	}
	elseif ($action=='getAllSoldGCList') 
	{
       // storing  request (ie, get/post) global array to a variable  
        $requestData= $_REQUEST;

        $columns = array( 
        // datatable column index  => database column lastname
            0 => 'vs_barcode', 
            1 => 'denomination',    
            2 => 'strec_recnum',
            3 => '',
            4 => '',
            5 => '',
            6 => '',
        );

        // getting total number records without any search

        $sql = "SELECT 
			DISTINCT
			store_verification.vs_barcode, 
			store_received_gc.strec_barcode,
			denomination.denomination,        
			store_verification.vs_date,
			store_received_gc.strec_recnum,
			transaction_stores.trans_number,
			transaction_stores.trans_type,
			transaction_stores.trans_datetime,
			stores.store_name
        ";
        $sql.="FROM 
			    store_received_gc
			INNER JOIN
			    denomination
			ON
			    store_received_gc.strec_denom = denomination.denom_id
			INNER JOIN
			    transaction_sales
			ON
			    transaction_sales.sales_barcode = store_received_gc.strec_barcode
			INNER JOIN
			    transaction_stores
			ON
			    transaction_stores.trans_sid = transaction_sales.sales_transaction_id
			LEFT JOIN
			    store_verification
			ON
			    store_received_gc.strec_barcode = store_verification.vs_barcode
			LEFT JOIN
			    stores
			ON
			    stores.store_id = store_verification.vs_store
			WHERE 
			    store_received_gc.strec_sold='*'
			AND
			    store_received_gc.strec_return=''
			AND
			    store_received_gc.strec_storeid='".$_SESSION['gc_store']."'
			AND
			    transaction_sales.sales_item_status='0'
			GROUP BY 
			    store_received_gc.strec_barcode
			ORDER BY 
			    transaction_stores.trans_datetime 
			DESC
      	";
        $query=$link->query($sql) or die($link->error);
        $totalData = $query->num_rows;
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        $cash = 'cash';
        $ccard = 'credit card';
        $arpayment = 'AR Payment';

        $sql = "SELECT 
			DISTINCT
			store_verification.vs_barcode, 
			store_received_gc.strec_barcode,
			denomination.denomination,        
			store_verification.vs_date,
			store_received_gc.strec_recnum,
			transaction_stores.trans_number,
			transaction_stores.trans_type,
			transaction_stores.trans_datetime,
			stores.store_name     
            ";
        $sql.="FROM 
			    store_received_gc
			INNER JOIN
			    denomination
			ON
			    store_received_gc.strec_denom = denomination.denom_id
			INNER JOIN
			    transaction_sales
			ON
			    transaction_sales.sales_barcode = store_received_gc.strec_barcode
			INNER JOIN
			    transaction_stores
			ON
			    transaction_stores.trans_sid = transaction_sales.sales_transaction_id
			LEFT JOIN
			    store_verification
			ON
			    store_received_gc.strec_barcode = store_verification.vs_barcode
			LEFT JOIN
			    stores
			ON
			    stores.store_id = store_verification.vs_store
			WHERE 
			    store_received_gc.strec_sold='*'
			AND
			    store_received_gc.strec_return=''
			AND
			    store_received_gc.strec_storeid='".$_SESSION['gc_store']."'
			AND
			    transaction_sales.sales_item_status='0'
            AND
                1=1";

		// 1 = cash

		// 2  = credit card

		// 3 = ar payment

		// 5 = refund

		// 6 = revalidation 

        if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter

            $sql.=" AND ( store_received_gc.strec_barcode LIKE '".$requestData['search']['value']."%' ";  
            $sql.=" OR stores.store_name LIKE '".$requestData['search']['value']."%' ";  
            $sql.=" OR transaction_stores.trans_number LIKE '".$requestData['search']['value']."%' ";  
            $sql.=" OR transaction_stores.trans_datetime LIKE '".$requestData['search']['value']."%' ";
            if(stripos($cash,$requestData['search']['value']) !== false)
            {
                $sql.=" OR transaction_stores.trans_type ='1'";
            }
            if(stripos($ccard,$requestData['search']['value']) !== false)
            {
                $sql.=" OR transaction_stores.trans_type ='2'";
            }
            if(stripos($arpayment,$requestData['search']['value']) !== false)
            {
                $sql.=" OR transaction_stores.trans_type ='3'";
            }
            $sql.=" OR store_received_gc.strec_recnum LIKE '".$requestData['search']['value']."%' )";
        }

        $query=$link->query($sql) or die($link->error.'q');
        $totalFiltered = $query->num_rows; // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        $sql.=" GROUP BY store_received_gc.strec_barcode ORDER BY transaction_stores.trans_datetime DESC LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        /* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */    
        $query=$link->query($sql) or die($link->error.'r');

	    $data = array();
        while( $row=$query->fetch_object() ) {  // preparing an array
            $nestedData=array(); 
            $trtype = '';
            switch ($row->trans_type) {
            	case 1:
            		$trtype = 'Cash'; 
            		break;
            	
            	case 2:
            		$trtype = 'Credit Card';
            		break;
            	case 3:
            		$trtype = 'AR Payment';
            		break;
            	default:
            		$trtype = '';
            		break;
            }

            $nestedData[] = $row->strec_barcode;
            $nestedData[] = number_format($row->denomination,2);
            $nestedData[] = threedigits($row->strec_recnum);
            $nestedData[] = _dateFormatoSql($row->trans_datetime);
            $nestedData[] = $row->trans_number;
            $nestedData[] = $trtype;
            $nestedData[] = $row->store_name;
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
            "recordsTotal"    => intval( $totalData ),  // total number of records
            "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data"            => $data   // total data array
        );

        echo json_encode($json_data);  // send data as json format
	}
	elseif ($action=='searchCustomerVerification') 
	{
		$cust = $_POST['cust'];

		$response['st'] = false;


		$query =  $link->query("SELECT 
			CONCAT(cus_fname,' ',cus_mname,' ',cus_lname,' ',cus_namext) as name,
			cus_id,
			cus_fname,
			cus_mname,
			cus_lname,
			cus_namext 
		FROM 
			customers 
		WHERE 
			CONCAT(cus_fname,' ', cus_lname) LIKE '%".$cust."%' OR CONCAT(cus_fname,' ',cus_mname,' ', cus_lname) LIKE '%".$cust."%' LIMIT 8
		");

		if($query->num_rows > 0)
		{
			$html = "<ul>";
			while ($row = $query->fetch_object()) 
			{
				$fullname = "";
				$fullname.= $row->cus_fname;
				if(!empty($row->cus_mname))
				{

					$fullname.= ' '.$row->cus_mname;
				}

				$fullname.= ' '.$row->cus_lname;
				if(!empty($row->cus_namext))
				{
					$fullname.= ' '.$row->cus_namext;
				}
				$html.= "<li class='vernames' data-id='".$row->cus_id."' data-fname='".$row->cus_fname."' data-mname='".$row->cus_mname."' data-lname='".$row->cus_lname."' data-namext='".$row->cus_namext."'>".$fullname."</li>";
			}
			$html.="</ul>";
			$response['st'] = true;
			$response['msg'] = $html;
		}
		else 
		{
			$response['msg'] = '<div class="_emptyresajax">No Result Found.<div>';
		}
		echo json_encode($response);
	}	
	elseif ($action=='addNewCustomerVerification') 
	{
		$response['st'] = false;		

		$fname = $link->real_escape_string(trim($_POST['fname']));
		$lname = $link->real_escape_string(trim($_POST['lname']));
		$mname = $link->real_escape_string(trim($_POST['mname']));
		$next = $link->real_escape_string(trim($_POST['extname']));

		if(!isset($_SESSION['gc_id']))
		{
			$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
		}
		elseif(empty($fname)||empty($lname))
		{
			$response['msg'] = 'Please input required fields.';
		}
		else 
		{
			$fullname = $fname.' '.$mname.' '.$lname.' '.$next;
			$query =  $link->query("SELECT 
					CONCAT(cus_fname,' ',cus_mname,' ',cus_lname,' ',cus_namext) as name				
				FROM 
					customers 
				WHERE 
					cus_fname='".$fname."'
				AND
					cus_lname='".$lname."'
				AND
					cus_mname='".$mname."'
				AND
					cus_namext='".$next."'
			");

			if($query->num_rows > 0)
			{
					$response['msg'] = $fullname.' already exist.';
			}
			else 
			{
				$query_ins = $link->query(
					"INSERT INTO 
						customers
					(
						cus_fname, 
						cus_lname, 
						cus_mname,
						cus_namext,
						cus_register_by,
						cus_register_at,
						cus_store_register
					) 
					VALUES 
					(
						'$fname',
						'$lname',
						'$mname',
						'$next',
						'".$_SESSION['gc_id']."',
						NOW(),
						'".$_SESSION['gc_store']."'
					)
				");

				if(!$query_ins)
				{
					$response['msg'] = $link->error;
				}
				else 
				{

					$fullname = $fname;
					if(!empty($mname))
					{
						$fullname = $fullname.' '.$mname;
					}			
					$fullname = $fullname.' '.$lname;
					if(!empty($next))
					{
						$fullname = $fullname.' '.$next;
					}
					

					$last_insert = $link->insert_id;

					$response['cid'] = $last_insert;
					$response['fname'] = $fname;
					$response['lname'] = $lname;
					$response['mname'] = $mname;
					$response['next'] = $next;

					$response['fullname'] = $fullname;

                                        // $('#fname').val(data['fname']);
                                        // $('#lname').val(data['lname']);
                                        // $('#mname').val(data['mname']);
                                        // $('#next').val(data['next']);

                                        // $('#_vercussearch').val(data['fullname']);
					$response['st'] = true;
				}			
			}
		}
		echo json_encode($response);
	}
	elseif($action=='scanGCForBNGCustomer')
	{
		$response['st'] = false;
		//var_dump($_POST);
		$barcode = $link->real_escape_string(trim($_POST['barcode']));		

		$totgcamt = 0;		
		$nobarcode = 0;
		$gcscan = 0;

		//var_dump($_SESSION);		

		if(empty($barcode))
		{
			$response['msg'] = 'Please input GC Barcode #';
		}
		else 
		{
			$table = "store_received_gc";
			$select = "store_received_gc.strec_barcode,
				store_received_gc.strec_storeid,
				store_received_gc.strec_denom,
				store_received_gc.strec_sold,
				store_received_gc.strec_transfer_out,
				store_received_gc.strec_bng_tag,
				denomination.denomination,
				store_verification.vs_barcode ";
			$where = "strec_barcode='$barcode'
				ORDER BY
					strec_id";
			$join = "INNER JOIN
					denomination
				ON
					denomination.denom_id = store_received_gc.strec_denom
				LEFT JOIN
					store_verification
				ON
					store_verification.vs_barcode = store_received_gc.strec_barcode";
			$limit = "";
			$data = getSelectedData($link,$table,$select,$where,$join,$limit);

			//var_dump($data);

			//echo $data->denomination;

			if(count($data)==0)
			{
				$response['msg'] = 'GC Barcode #'.$barcode.' not found.';				
			}
			elseif(trim($data->vs_barcode) !='')
			{
				$response['msg'] = 'GC Barcode #'.$barcode.' already verified.';
			}
			elseif($data->strec_storeid!=$_SESSION['gc_store'])
			{
				$response['msg'] = 'GC Barcode #'.$barcode.' not found in this location.';
			}
			elseif($data->strec_storeid == $_SESSION['gc_store'] && $data->strec_transfer_out=='*')
			{
				$response['msg'] = 'GC Barcode #'.$barcode.' already transfer out.';
			}
			elseif($data->strec_sold=='*')
			{
				$response['msg'] = 'GC Barcode #'.$barcode.' already sold.';
			}
			elseif($data->denomination!=='500')
			{
				$response['msg'] = 'Invalid denomination.';
			}
			elseif($data->strec_bng_tag==='*')
			{
				$response['msg'] = 'GC Barcode # already tagged as Beam and Go GC.';
			}
			else
			{
	            $alreadyScanned = false;

	            //echo $data->denomination;

                //check gc if already scanned
                if(isset($_SESSION['scanForBNGCustomerGC']))
                {
                	// check if already scanned and number of gc to scanned

                    foreach ($_SESSION['scanForBNGCustomerGC'] as $key => $value) 
                    {

                    	if($value['barcode']=='')
                    	{
                    		$nobarcode++;
                    	}

                        if($value['barcode']==$barcode)
                        {
                            $alreadyScanned = true;	                            
                        }

                        $totgcamt += $value['value'];
                    }

                    $totgcamt += $data->denomination;
                   
                }
                else 
                {
                	$totgcamt = $data->denomination;                	
                }


	            if($alreadyScanned)
	            {
	            	$response['msg'] = 'GC Barcode # '.$barcode.' already scanned.';
	            }
	            elseif($nobarcode==0)
	            {
	            	$response['msg'] = 'GC to Scan is 0.';
	            }
	            else 
	            {
	            	$nobarcode--;

	            	$gcscan = count($_SESSION['scanForBNGCustomerGC']) - $nobarcode;
	            	
					foreach($_SESSION['scanForBNGCustomerGC'] as $key => $value)
					{
                    	if($value['barcode']=='')
                    	{
                    		$_SESSION['scanForBNGCustomerGC'][$key]['barcode'] = $barcode;
                    		break;
                    	}
						
					}
					//var_dump($_SESSION['scanForBNGCustomerGC']);

	                //check if session exist
	                // if(isset($_SESSION['scanForBNGCustomerGC']))
	                // {
	                //     $_SESSION['scanForBNGCustomerGC'][] = array("barcode"=>$barcode,"denomination"=>$data->denomination);
	                // }
	                // else 
	                // {   
	                //     $_SESSION['scanForBNGCustomerGC'][] = array("barcode"=>$barcode,"denomination"=>$data->denomination);
	                // }

	                // $total = 0;
	                // foreach ($_SESSION['scanForBNGCustomerGC'] as $key => $value) 
	                // {
	                //     $total+=$value['denomination'];
	                // }

	                // end($_SESSION['scanForBNGCustomerGC']);
	                // $key = key($_SESSION['scanForBNGCustomerGC']);

	                // $cnt  = count($_SESSION['scanForBNGCustomerGC']);

	                // $response['bngamt'] = $bngamt;
	                // $response['key'] = $key;
	                // $response['total'] = number_format($totgcamt,2);
	                // $response['count'] = $cnt;
	                // $response['st'] = true;
	                // $response['barcode'] = $barcode;
	                // $response['denomination'] = $data->denomination;

	                $response['msg'] = 'Succesfully Scanned for Beam and Go Customer.';
					$response['gcscan'] = $gcscan;
	            	$response['st'] = true;
	                $response['nobarcode'] = $nobarcode;

	            }
	        }

		}

		// foreach($data as $key => $value)
		// {
		//   $data[$key]['transaction_date'] = date('d/m/Y',$value['transaction_date']);
		// }

		echo json_encode($response);
	}
	elseif ($action=='getbngexceldata') 
	{		
		$response['st'] = false;
		$inputFileName = $_FILES['file']['name'];
		$fileType = $_FILES['file']['type'];
		$fileError = $_FILES['file']['error'];
		$file = $_FILES['file']['tmp_name'];
		$hasError = false;
		$errormsg = "";
		$serialExist = 0;
		$textfileEmpty = true;

		$totamt = 0;

        if(isset($_SESSION['scanForBNGCustomerGC']))
        {
        	unset($_SESSION['scanForBNGCustomerGC']);
        }

		// $inputFileType = PHPExcel_IOFactory::identify($file);
		// $objReader = PHPExcel_IOFactory::createReader($inputFileType);
		// $objReader->setReadDataOnly(false);

		// $objPHPExcel = $objReader->load($file);

		// $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		// var_dump($sheetData);

		$inputFileType = PHPExcel_IOFactory::identify($file);

		//echo $inputFileType;
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($file);

		$sheet = $objPHPExcel->getActiveSheet(0);

		$highestRow = $sheet->getHighestRow();

		$highestColumn = $sheet->getHighestColumn();

		//echo $highestRow;

		$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		$data = [];
		//echo $highestRow;
		for ($i=$highestRow; $i >= 2 ; $i--) 
		{ 
			if(strlen($sheetData[$i]['C'])!=12)
			{
				//echo strlen($sheetData[$i]['C']);
				$hasError = true;
				$errormsg = "Serial Number is invalid.";
				break;
			}
			$isExist = false;
			// check serial number

			if(!checkIfExist($link,'bngbar_serialnum','beamandgo_barcodes','bngbar_serialnum',trim($sheetData[$i]['C'])) && trim($sheetData[$i]['C'])!=='')
			{				
                if(isset($_SESSION['scanForBNGCustomerGC']))
                {
                    foreach ($_SESSION['scanForBNGCustomerGC'] as $key => $value) 
                    {
                        if($value['sernum']==$sheetData[$i]['C'])
                        {
                            $isExist = true;	                            
                        }                    
                    }                  
                   
                }

                if(!$isExist)
                {

					$amt = explode(" ", $sheetData[$i]['G']);

					$totamt+=trim(end($amt));
					// $data[] = array(
					// 	'refnum' 		=>	$sheetData[$i]['A'],
					// 	'trdate'		=>	$sheetData[$i]['B'],
					// 	'redeemdate'	=>	$sheetData[$i]['C'],
					// 	'sendername'	=>	$sheetData[$i]['D'],
					// 	'benefname'		=>	$sheetData[$i]['E'],
					// 	'benefmobile'	=>	$sheetData[$i]['F'],
					// 	'value'			=>	end($amt),
					// 	'barcode'		=>	'',
					// 	'note'			=>	$sheetData[$i]['H']

					// );

	                if(isset($_SESSION['scanForBNGCustomerGC']))
	                {
	                    $_SESSION['scanForBNGCustomerGC'][] = array(	                    	
							'refnum' 		=>	trim($sheetData[$i]['A']),
							'trdate'		=>	trim($sheetData[$i]['B']),
							'sernum' 		=>	trim($sheetData[$i]['C']),
							'sendername'	=>	trim($sheetData[$i]['D']),
							'benefname'		=>	trim($sheetData[$i]['E']),
							'benefmobile'	=>	trim($sheetData[$i]['F']),
							'value'			=>	trim(end($amt)),
							'barcode'		=>	'',
							'branchname'	=>	trim($sheetData[$i]['H']),
							'status'		=>	trim($sheetData[$i]['I']),
							'note'			=>	trim($sheetData[$i]['J'])
	                    );
	                }
	                else 
	                {   
	                    $_SESSION['scanForBNGCustomerGC'][] = array(	                    	
							'refnum' 		=>	trim($sheetData[$i]['A']),
							'trdate'		=>	trim($sheetData[$i]['B']),
							'sernum' 		=>	trim($sheetData[$i]['C']),
							'sendername'	=>	trim($sheetData[$i]['D']),
							'benefname'		=>	trim($sheetData[$i]['E']),
							'benefmobile'	=>	trim($sheetData[$i]['F']),
							'value'			=>	trim(end($amt)),
							'barcode'		=>	'',
							'branchname'	=>	trim($sheetData[$i]['H']),
							'status'		=>	trim($sheetData[$i]['I']),
							'note'			=>	trim($sheetData[$i]['J'])
	                    );
	                }
                }
			}
			else 
			{
				$serialExist++;
			}
			//$response['msg'] = $sheetData[$i]['A'];
		}

		//$_SESSION['scanForBNGCustomerGC'][] = $_SESSION['scanForBNGCustomerGC'];

		//var_dump($_SESSION['scanForBNGCustomerGC']);
		$response['serialexist'] = $serialExist;
		if($highestRow == 1)
		{
			$response['msg'] = 'Excel file is empty.';
		}
		elseif($hasError)
		{
			$response['msg'] = $errormsg;
		}
		elseif(isset($_SESSION['scanForBNGCustomerGC']))
		{
			$response['st'] = true;
			$response['data'] = $_SESSION['scanForBNGCustomerGC'];
			$response['totamt'] = number_format($totamt,2);
		}		
		else
		{
			if($serialExist > 0)
			{
				$response['msg'] = 'Serial number already exist / Already scanned.';
			}			
		}
		
		//$response['status'] = 'yeah';
		//var_dump($sheetData);

		// foreach ($sheetData as $key) 
		// {
		// 	var_dump($key);
		// }

		//var_dump($highestRow);

		// echo '<hr />';

		// $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		// var_dump($sheetData);

		echo json_encode($response);
	}
	elseif($action=='checkGCToSCanBNG')
	{
		$response['st'] = false;
		$gctoscan = 0;
		$totgcamt = 0;

        if(isset($_SESSION['scanForBNGCustomerGC']))
        {
            foreach ($_SESSION['scanForBNGCustomerGC'] as $key => $value) 
            {
                if($value['barcode']=='')
                {
                    $gctoscan++;                            
                }      

                $totgcamt+=$value['value'];              
            }           
           
        }

        $response['gctoscan'] = $gctoscan;

		echo json_encode($response);
	}
	elseif ($action=='getBNGScanBarcode') 
	{
		# code...
		$response['data'] = '';
		$data = [];
		if(isset($_SESSION['scanForBNGCustomerGC']))
		{
			$data = $_SESSION['scanForBNGCustomerGC'];

			$_SESSION['scanForBNGCustomerGC'] = [];

			foreach ($data as $key => $value) 
			{
				$sernum  = 	$value['sernum'];
				$refnum 	= 	$value['refnum'];
				$trdate 	=	$value['trdate'];				
				$sendername = 	$value['sendername'];
				$benefname  = 	$value['benefname'];
				$benefmobile = 	$value['benefmobile'];
				$valuephp 		= 	$value['value'];
				$barcode 	= 	$value['barcode'];
				$branchname = 	$value['branchname'];
				$status     =   $value['status'];
				$note 		=	$value['note'];

				$_SESSION['scanForBNGCustomerGC'][] = array(
					'sernum' 		=>	$sernum,
					'refnum' 		=>	$refnum,
					'trdate'		=>	$trdate,
					'sendername'	=>	$sendername,
					'benefname'		=>	$benefname,
					'benefmobile'	=>	$benefmobile,
					'value'			=>	$valuephp,
					'barcode'		=>	$barcode,
					'branchname'	=>	$branchname,
					'status'		=>	$status,
					'note'			=>	$note
                );
			}

			$response['data'] = $_SESSION['scanForBNGCustomerGC'];

		}
		echo json_encode($response);
	}
	elseif ($action=='savebngTransaction') 
	{
		$response['msg'] = false;
		$totalamt = 0;
		$gctoscan = 0;
		$hasError = false;
		$updateError = false;

        foreach ($_SESSION['scanForBNGCustomerGC'] as $key => $value) 
        {
            if($value['barcode']=='')
            {
                $gctoscan++;                            
            }            
            $totalamt+=$value['value'];        
        }		

		//check if session exist
        if(!isset($_SESSION['gc_id']))
        {
        	$response['msg'] = 'Your Session has Expired! Please Click <a href="../index.php">Here</a> to Login and Continue.';
        }
		elseif(!isset($_SESSION['scanForBNGCustomerGC']))
		{
			$response['msg'] = 'Please upload file.';
		}
		elseif (count($_SESSION['scanForBNGCustomerGC'])==0) 
		{
			$response['msg'] = 'Please upload file.';
		}
		elseif ($gctoscan>0) 
		{
			$response['msg'] = 'GC to scan '.$gctoscan.' pc(s).';
		}
		else 
		{
			$trnum = getBeamAndGoTRNum($link,$_SESSION['gc_store']);
			$link->autocommit(FALSE);

			$query = $link->query(
				"INSERT INTO 
					beamandgo_transaction
				(
				    bngver_storeid, 
				    bngver_trnum, 
				    bngver_amt,
				    bngver_datetime,
				    bngver_by
				) 
					VALUES 
				(
				    '".$_SESSION['gc_store']."',
				    '$trnum',
				    '$totalamt',		    
				    NOW(),
				    '".$_SESSION['gc_id']."'
				)
			");

			if(!$query)
			{
				$response['msg'] = $link->error;
			}
			else 
			{
				$last_insert = $link->insert_id;					

	            foreach ($_SESSION['scanForBNGCustomerGC'] as $key => $value) 
	            {
					$query = $link->query(
						"INSERT INTO 
							beamandgo_barcodes
						(
						    bngbar_barcode, 
						    bngbar_trid,
						    bngbar_serialnum,
						    bngbar_refnum,
						    bngbar_transdate,
						    bngbar_sendername,
						    bngbar_beneficiaryname,
						    bngbar_beneficiarymobile,
						    bngbar_value,
						    bngbar_branchname,
						    bngbar_status,
						    bngbar_note
						) 
							VALUES 
						(
						    '".$value['barcode']."',
						    '$last_insert',
						    '".$value['sernum']."',
						    '".$value['refnum']."',
						    '".$value['trdate']."',
						    '".$value['sendername']."',
						    '".$value['benefname']."',
						    '".$value['benefmobile']."',
						    '".$value['value']."',
						    '".$value['branchname']."',
						    '".$value['status']."',
						    '".$link->real_escape_string($value['note'])."'
						)
					");

					if(!$query)
					{
						$hasError = true;
						break;
					}

					$query_update = $link->query(
						"UPDATE 
							store_received_gc 
						SET 
							strec_bng_tag='*'
						WHERE 
							strec_barcode='".$value['barcode']."'
						AND
							strec_storeid='".$_SESSION['gc_store']."'
						AND
							strec_sold=''
						AND
							strec_sold=''
						AND
							strec_bng_tag=''
	
					");

					if(!$query)
					{
						$hasError = true;
						break;
					}
					else 
					{
						if($link->affected_rows == 0)
						{
							$updateError = true;
							break;
						}
					}
					//$link->affected_rows >0
	            }

	            if($hasError)
	            {
	            	$response['msg'] = $link->error;
	            }
	            elseif($updateError)
	            {
	            	$response['msg'] = 'Error updating store received table.';
	            }
	            else 
	            {
	            	if(insertBudgetLedgers($link,$last_insert,'BEAMANDGO','bdebit_amt',$totalamt))
	            	{
	            		if(storeLedgers($link,$last_insert,1,$totalamt,'BNG','BEAMANDGO',$_SESSION['gc_store'],0))
	            		{
	            			$link->commit();
			            	$response['st'] = true;
						    if(isset($_SESSION['scanForBNGCustomerGC']))
						        unset($_SESSION['scanForBNGCustomerGC']);
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
			}	
		}
		echo json_encode($response);
	}
	elseif($action=='removeBySerialNumber')
	{
		$response['st'] = false;
		$serial = $_POST['serial'];

		$total = 0;
		$count = 0;
		$k = 0;
		

		if(isset($_SESSION['scanForBNGCustomerGC']))
		{			
			$count = count($_SESSION['scanForBNGCustomerGC']);
			foreach ($_SESSION['scanForBNGCustomerGC'] as $key => $value) 
			{
				if($value['sernum']==$serial)
				{
					$k = $key;					
					$response['st'] = true;					
				}
				else
				{
					$total += $value['value'];	
				}			
				
			}
		}
		unset($_SESSION['scanForBNGCustomerGC'][$k]);
		//var_dump($_SESSION['scanForBNGCustomerGC']);
		$response['count'] = $count;
		$response['total'] = number_format($total,2);

		echo json_encode($response);
	}
}

//$link->real_escape_string
?>

