<?php
	require_once 'config.php';
	function isLoggedIn()
	{
		if(isset($_SESSION['gc_user']) && !empty($_SESSION['gc_user']))
		{
			return true;
		}
		else 
		{
			header('location:../index.php');
		}
	}

	function isLoggedInCashier(){
		if(isset($_SESSION['gccashier_username'])&&!empty($_SESSION['gccashier_username'])){			
			return true;
		}else {
			header('location:login.php');
		}
	}

	function isLoggedInSupervisor()
	{
		if(isset($_SESSION['gc_super_id'])&&!empty($_SESSION['gc_super_id']))
		{
			return true;			
		} 
		else 
		{
			return false;
		}
	}

	function validateData($data){
		return trim(strip_tags($data));
	}

	function checkIfTableNotEmpty($link,$table){
		$query = $link->query("SELECT * FROM $table");
		$num_rows = $query->num_rows;
		if($num_rows>0)
		{
			return true;
		} 
		else 
		{
			return false;
		}
	}

	function checkIfHasRows($link,$field,$table,$row1,$var1,$row2,$var2){

		$query = $link->query("SELECT $field FROM $table WHERE $row1='$var1' AND $row2='$var2'");

		if($query){
			$n = $query->num_rows;
			if($n>0){
				return true;
			} else {
				return false;
			}
		}
	}

	function checkISNULL($link,$table,$select,$field,$field1,$var)
	{
		$query = $link->query(
			"SELECT
				$select
			FROM 
				$table 
			WHERE
				$field='$var'
			AND
				$field1 IS NULL
		");

		if($query)
		{
			if($query->num_rows > 0)
			{
				return true;
			}
			else 
				return false;
		}
		else 
		{
			die('Error check Null');
		}

	}

	function getOne($link,$field,$table,$row)
	{
		$query = $link->query(
			"SELECT 
				$field 
			FROM 
				$table 
			ORDER by 
				$row 
			DESC
		");
		$row = $query->fetch_assoc();
		return $row[$field];
	}

	function getOneByStore($link,$field,$table,$row,$storeid)
	{
		$query = $link->query(
			"SELECT 
				$field 
			FROM 
				$table 
			WHERE 
				`sgc_store` = $storeid
			ORDER by 
				$row 
			DESC
		");
		$row = $query->fetch_assoc();
		return $row[$field];
	}

	function getOneJoin($link,$var,$field){
		$query = $link->query("SELECT 
					$field,
					`denomination`.`denomination` 
				FROM 
					`gc`
				INNER JOIN
					`denomination`
				ON
					`gc`.`denom_id` = `denomination`.`denom_id`
				WHERE 
					`gc`.`barcode_no`='$var'
				");
		$row = $query->fetch_assoc();
		return $row['denomination'];
	}

	function checkIfExist($link,$field,$table,$row,$var)
	{
		$query = $link->query("SELECT $field FROM $table WHERE $row='$var'");
		$num_rows = $query->num_rows;
		if($num_rows>0)
		{
			return true;
		} 
		else 
		{
			return false;
		}
	}

	function checkifExist2($link,$select,$table,$field1,$field2,$var1,$var2)
	{
		$query = $link->query(
			"SELECT 
				$select
			FROM
				$table
			WHERE
				$field1 = '$var1'
			AND
				$field2 = '$var2'
		");

		$num_rows = $query->num_rows;
		if($num_rows > 0)
		{
			return true;
		}
		else 
		{
			echo $link->error;
			return false;
		}
	}

	function checkifHasPendingReqStore($link,$field,$table,$row,$var,$row1,$var1){
		$query = $link->query("SELECT $field FROM $table WHERE $row='$var' AND $row1='$var1'");
		$num_rows = $query->num_rows;
		if($num_rows>0){
			return true;
		} else {
			return false;
		}
	}

	function getField($link,$field,$table,$field2,$var)
	{
		$query = $link->query("SELECT $field FROM $table WHERE $field2='$var'");
		$row = $query->fetch_assoc();
		return $row[$field];
	}

	function getField2($link,$select,$table,$field1,$field2,$var1,$var2)
	{
		$query = $link->query(
			"SELECT 
				$select
			FROM
				$table
			WHERE
				$field1 = '$var1'
			AND
				$field2 = '$var2'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->$select;
		}
		else 
		{
			return $link->error;
		}
	}

	function getFieldOrderLimit($link,$field,$table,$field2,$var,$orderby,$order,$limit)
	{
		$query = $link->query("SELECT $field FROM $table WHERE $field2='$var' ORDER BY $orderby $order LIMIT $limit");
		$row = $query->fetch_assoc();
		return $row[$field];
	}

	function getFieldGCItems($link,$field,$table,$store,$var1,$status,$var2){
		$query = $link->query("SELECT $field FROM $table WHERE $store='$var1' AND $status='$var2'");
		$row = $query->fetch_assoc();
		return $row[$field];
	}


	function numRowsNoWhere($link,$table)
	{
		$query = $link->query("SELECT * FROM $table");
		$num_rows = $query->num_rows;
		return $num_rows;
	}

	function numRowsWhereTwo($link,$table,$select,$field1,$field2,$var1,$var2)
	{
		$query = $link->query(
			"SELECT 
				$select
			FROM 
				$table
			WHERE
				$field1= '$var1'
			AND
				$field2 = '$var2'
		");

		if($query)
		{	
			return $query->num_rows;
		}
		else 
		{
			return $link->error;
		}

	}

	function numRowsWhereThree($link,$table,$select,$field1,$field2,$field3,$var1,$var2,$var3)
	{
		$query = $link->query(
			"SELECT 
				$select
			FROM 
				$table
			WHERE
				$field1= '$var1'
			AND
				$field2 = '$var2'
			AND
				$field3 = '$var3'
		");

		if($query)
		{	
			return $query->num_rows;
		}
		else 
		{
			return $link->error;
		}
	}

	function numRows($link,$table,$row,$var)
	{
		$query = $link->query(
			"SELECT 
				*
			 FROM 
			 	$table 
			 WHERE 
			 	$row='$var'
		");
		$num_rows = $query->num_rows;
		return $num_rows;
	}

	function numRows2($link,$table,$row,$var)
	{
		$query = $link->query(
			"SELECT 
				$row
			 FROM 
			 	$table 
			 WHERE 
			 	$row='$var'
		");
		$num_rows = $query->num_rows;
		return $num_rows;
	}

	function numRowsWithSelect($link,$table,$select,$where)
	{
		$query = $link->query(
			"SELECT 
				$select
			FROM 
			 	$table 
			$where
		");
		$num_rows = $query->num_rows;
		return $num_rows;
	}


	function getNumRowsStoreRequest($link,$table,$row,$var,$row1,$var1)
	{
		$query = $link->query("SELECT * FROM $table WHERE $row='$var' AND $row1='$var1'");
		$num_rows = $query->num_rows;
		return $num_rows;		
	}


	function numRowsForValidation($link,$table,$row,$var,$row1,$var1){
		$query = $link->query("SELECT * FROM $table WHERE $row='$var' AND $row1='$var1' AND `gc_cancelled`=''" );
		$num_rows = $query->num_rows;
		return $num_rows;
	}

	function numRowsForValidationReceiving($link,$den_id,$id)
	{
		$query = $link->query(
			"SELECT 
				* 
			FROM 
				`gc` 
			INNER JOIN
				`production_request`
			ON
				`gc`.`pe_entry_gc` = `production_request`.`pe_id`
			INNER JOIN
				`approved_production_request`
			ON
				`approved_production_request`.`ape_pro_request_id` = `production_request`.`pe_id`

			WHERE
				`gc`.`pe_entry_gc`='$id'
			AND
				`production_request`.`pe_requisition`='1'
			AND
				`gc`.`gc_validated`=''
			AND
				`gc`.`denom_id`='$den_id'
			AND 
				`approved_production_request`.`ape_received`=''
		");

		if($query)
		{
			$n = $query->num_rows;
			return $n;
		}
		else 
		{
			return $link->error;
		}
	}

	function numRowsForValidationtres($link,$table,$row,$var,$row1,$var1){
		$query = $link->query("SELECT * FROM $table WHERE $row='$var' AND $row1='$var1'" );
		$num_rows = $query->num_rows;
		return $num_rows;
	}

	function getValidationNumRowsByStore($link,$store,$denom)
	{
		$query = $link->query("SELECT 
					* 
					FROM 
						`gc_location`
					INNER JOIN 
						`gc`
					ON
					`gc`.`barcode_no` = `gc_location`.`loc_barcode_no`
					WHERE 
						`loc_rel`='' 
					AND 
						`loc_store_id`='$store'
					AND `denom_id`= '$denom'
			");
		$num_rows = $query->num_rows;
		return $num_rows;		
	}

	function countAllocatedGCByStoreDenomAndGCType($link,$store,$denom,$gctype)
	{
		$query = $link->query(
			"SELECT
				gc_location.loc_barcode_no
			FROM 
				gc_location
			INNER JOIN
				gc
			ON
				gc.barcode_no = gc_location.loc_barcode_no
			INNER JOIN
				denomination
			ON
				denomination.denom_id = gc.denom_id
			WHERE
				gc_location.loc_store_id='$store'
			AND
				gc_location.loc_gc_type='$gctype'
			AND
						denomination.denom_id='$denom'
			AND			
				gc_location.loc_rel=''
		");

		if($query)
		{
			return $query->num_rows;
		}
		else
		{
			return $link->error;
		}
	}

	function countCustodianValidatedGCForStoreAllocation($link,$denom)
	{
		$query = $link->query(
			"SELECT 
				gc.barcode_no
			FROM
				gc
			WHERE 
				gc.gc_validated='*'
			AND
				gc.gc_allocated=''
			AND
				gc.gc_ispromo=''
			AND
				gc.denom_id='$denom'
		");

		if($query)
		{
			return $query->num_rows;
		}
		else 
		{
			return $link->error;
		}
	}

	function _timeFormat($todays_time){		
		$convertingtime = strtotime($todays_time);
		return date("g:i a", $convertingtime); 
	}

	function _dateFormat($todays_date){
		$todays_date = date_create($todays_date);
		return date_format($todays_date, 'F d, Y');		
	}

	function _dateFormatMonth($todays_date)
	{
		$todays_date = date_create($todays_date);
		return date_format($todays_date, 'F Y');				
	}

	function _dateFormatShort($todays_date)
	{
		$todays_date = date_create($todays_date);
		return date_format($todays_date, 'M d, Y');	
	}

	function _dateFormatDay($todays_date){
		$todays_date = date_create($todays_date);
		return date_format($todays_date, 'l, d F Y');
	}

	function _dateFormatoSql($date_to_format){
		$date_to_format = date_create($date_to_format);
		return date_format($date_to_format, 'Y-m-d');
	}

	function _dateFormatoSql2($date_to_format)
	{
		$ndate = DateTime::createFromFormat('d/m/Y', $date_to_format);
		$ndate = $ndate->format('Y-m-d');
		return $ndate;
	}

	function _dateFromSql($date_to_format)
	{
		$oDate = new DateTime($date_to_format);
		return $sDate = $oDate->format("m/d/y");
	}

	function _dateFromSqltoDOB($date_to_format)
	{
		$oDate = new DateTime($date_to_format);
		return $sDate = $oDate->format("d/m/Y");
	}

	function getDateTo($link,$tablename)
	{
		$query = $link->query(
			"SELECT 
				app_settingvalue
			FROM 
				app_settings 
			WHERE 
				app_tablename='$tablename'		
			LIMIT 1	
		");

		if($query)
		{
			$row = $query->fetch_object();	
			return $row->app_settingvalue;
		}
		else 
		{
			return $link->error;
		}
	}

	function currentBudget($link)
	{
		$query = "SELECT SUM(bdebit_amt),SUM(bcredit_amt) FROM ledger_budget";

		$query = $link->query($query) or die('unable to query');
		$budget_row		= $query->fetch_array();
		$debit 	= $budget_row['SUM(bdebit_amt)'];
		$credit = $budget_row['SUM(bcredit_amt)'];

		$budget = $debit - $credit;

		return $budget;
	}

	function currentBudgetByDept($link,$type)
	{
		$query = "SELECT 
			IFNULL(SUM(bdebit_amt),0.00) as debit,
			IFNULL(SUM(bcredit_amt),0.00) as credit 
		FROM 
			ledger_budget
		WHERE
			`bledger_typeid`='$type'
		";

		$query = $link->query($query) or die('unable to query');
		$budget_row	= $query->fetch_array();
		$debit 	= $budget_row['debit'];
		$credit = $budget_row['credit'];

		$budget = $debit - $credit;

		return $budget;
	}

	function currentBudgetByDeptByPromoGroup($link,$group)
	{
		$query = "SELECT 
			IFNULL(SUM(bdebit_amt),0.00) as debit,
			IFNULL(SUM(bcredit_amt),0.00) as credit 
		FROM 
			ledger_budget
		WHERE
			`bledger_typeid`='2'
		AND
			`bledger_group`='$group'
		";

		$query = $link->query($query) or die('unable to query');
		$budget_row	= $query->fetch_array();
		$debit 	= $budget_row['debit'];
		$credit = $budget_row['credit'];

		$budget = $debit - $credit;

		return $budget;
	}

	function generateGC($d_qty,$den_id,$str_den_start,$link,$todays_date,$todays_time,$dtotal,$peid)
	{
		// check if production requested is for promo or regular promo
		$petype = getField($link,'pe_type','production_request','pe_id',$peid);
		$promo='';
		if($petype==2)
		{
			$promo='*';
		}

		$flag = 0;
		$query = $link->query("SELECT * FROM `gc` WHERE `denom_id`='$den_id'");
		$num_rows = $query->num_rows;

		if($num_rows<1)
		{
			$uid = $_SESSION['gc_id'];
			$str_frm_db = $str_den_start;			
			if($link->query(
					"INSERT INTO 
						`gc`
					(
						`barcode_no`, 
						`denom_id`, 
						`date`, 
						`time`, 
						`pe_entry_gc`, 
						`gc_postedby`,
						`gc_ispromo`
					) 
					VALUES 
					(
						'$str_frm_db',
						'$den_id',
						NOW(),
						NOW(),
						'$peid',
						'$uid',
						'$promo'
					)
				"))
			{
				$barcode_no	= $str_frm_db;
				$d_qty = $d_qty -1;
				$flag = 1;

			} 
			else 
			{
				echo $link->error;
			}
		}

		if($num_rows>0)
		{
			$last_bc = "SELECT * FROM `gc` WHERE `denom_id`='$den_id' ORDER BY `barcode_no` DESC LIMIT 1";
			$query_last	= $link->query($last_bc);
			$last_row		= $query_last->fetch_array();
			if($last_row)
			{ 
				$barcode_no	= $last_row['barcode_no'];				 
			}
		}

		for($m=1 ; $m<=$d_qty ; $m++)
		{						
			$barcode_no++;				
			$o = $link->query(
				"INSERT INTO 
					`gc`
				(
					`barcode_no`, 
					`denom_id`, 
					`date`, 
					`time`, 
					`pe_entry_gc`, 
					`gc_postedby`,
					`gc_ispromo`

				) 
				VALUES 
				(
					'$barcode_no',
					'$den_id',
					NOW(),
					NOW(),
					'$peid',
					'".$_SESSION['gc_id']."',
					'$promo'
				)
			");
		}
	}

	function gen_barcode($str) {
		$str = $str;
		$str++;
		$final = $str;
		// $arr2 = str_split($str);

		// $a	= $arr2[0];
		// $b	= $arr2[1];
		// $c	= $arr2[2];
		// $d	= $arr2[3];
		// $e	= $arr2[4];
		// $f	= $arr2[5];
		// $g	= $arr2[6];
		// $h	= $arr2[7];
		// $i	= $arr2[8];
		// $j	= $arr2[9];
		// $k	= $arr2[10];
		// $l	= $arr2[11];

		// $step01	= ($l + $j + $h + $f + $d + $b);
		// $step02	= ($step01 * 3);
		// $step03	= ($k + $i + $g + $e + $c + $a);
		// $step04	= ($step02 + $step03);
		// $step05 = ($step04 / 10);
		// $step06	= 10 - ($step04 % 10) ;
		// if ($step06 == 10){
		// 	$step06 = 0;
		// }
		// $final = $str.$step06;
		return $final;
	}

	function allocateGC($link,$gc_type,$qty,$gc_id,$store_code,$todays_date,$todays_time){

		$get_gc = $link->query("SELECT * FROM gc WHERE gc_validated='*' AND denom_id='$gc_id' AND gc_allocated='' AND gc_ispromo='' AND gc_treasury_release='' ORDER BY gc_id ASC LIMIT $qty");
		while($row_gc = $get_gc->fetch_assoc()){
			$barcode = $row_gc['barcode_no'];
			$o = $link->query("INSERT INTO `gc_location` VALUES ('','$barcode','$store_code','$todays_date','$todays_time','$gc_type','','".$_SESSION['gc_id']."')"); 
			$link->query("UPDATE gc SET gc_allocated='*' WHERE barcode_no='$barcode'");
		}	

	}


	function allocateGCAdjPos($link,$gc_type,$qty,$gc_id,$store_code,$last_id)
	{
		$haserror = false;
		$get_gc = $link->query(
			"SELECT 
				gc.barcode_no
			FROM 
				gc 
			WHERE 
				gc.gc_validated='*' 
			AND 
				gc.denom_id='$gc_id' 
			AND 
				gc.gc_allocated='' 
			AND
				gc.gc_ispromo=''
			AND
				gc.gc_cancelled=''
			ORDER BY 
				gc.gc_id 
			ASC 
			LIMIT 
				$qty"
		);

		if($get_gc)
		{
			while($row_gc = $get_gc->fetch_object()){
				$barcode = $row_gc->barcode_no;			
				$adj_ins = $link->query(
					"INSERT INTO 
						allocation_adjustment_items
					(
						aadji_aadj_id, 
						aadji_barcode
					) 
					VALUES 
					(
						'$last_id',
						'$barcode'
					)
				");

				if(!$adj_ins)
				{
					$haserror = true;
					break;
				}

				$loc_ins = $link->query(
					"INSERT INTO 
						gc_location
					(
						loc_barcode_no, 
						loc_store_id,
						loc_date, 
						loc_time, 
						loc_gc_type,
						loc_by
					) 
					VALUES 
					(
						'$barcode',
						'$store_code',
						NOW(),
						NOW(),
						'$gc_type',
						'".$_SESSION['gc_id']."'
					)
				");

				if(!$loc_ins)
				{
					$haserror = true;
					break;
				}
				$gc_up =  $link->query("UPDATE gc SET gc_allocated='*' WHERE barcode_no='$barcode'");

				if(!$gc_up)
				{
					$haserror = true;
					break;
				}
			}

			if(!$haserror)
			{
				return true;
			}
			else 
			{
				return false;
			}
		}
		else 
		{
			return false;
		}
	}

	function allocateGCAdjNeg($link,$qty,$denom_id,$store,$last_id,$gctype)
	{
		$haserror = false;
		$get_gc = $link->query(
			"SELECT 
				gc.barcode_no,
				gc_location.loc_gc_type 
			FROM 
				gc
			INNER JOIN
				gc_location
			ON
				gc.barcode_no=gc_location.loc_barcode_no
			WHERE
				gc.gc_allocated='*'
			AND
				gc.gc_released=''
			AND
				gc_location.loc_store_id='$store'
			AND 
				gc.denom_id='$denom_id'
			AND
				gc_location.loc_gc_type='$gctype'
			ORDER BY 
				gc.barcode_no 
			DESC
				LIMIT $qty
		");

		if($get_gc)
		{
			while ($row = $get_gc->fetch_object()) 
			{
				$barcode = $row->barcode_no;
				$gc_ins = $link->query(
					"INSERT INTO 
						`allocation_adjustment_items`
					(
						`aadji_aadj_id`, 
						`aadji_barcode`
					) 
					VALUES 
					(
						'$last_id',
						'$barcode'
					)
				");

				if(!$gc_ins)
				{
					$haserror = true;
					break;
				}

				$adj_del = $link->query("DELETE FROM `gc_location` WHERE `loc_barcode_no`='$barcode'");
				if(!$adj_del)
				{
					$haserror = true;
					break;
				}

				$adj_up = $link->query("UPDATE gc SET gc_allocated='' WHERE `barcode_no`='$barcode'");	
				if(!$adj_up)
				{
					$haserror = true;
					break;					
				}
			}

			if(!$haserror)
			{
				return true;
			}
			else 
			{
				return false;
			}
		}
		else 
		{
			return false;
		}


	}


	function countGC($link,$table,$row,$var,$row1,$var1,$row2,$var2){
		$query = $link->query("SELECT * FROM $table WHERE $row='$var' AND $row1='$var1' AND $row2='$var2'");
		$num_rows = $query->num_rows;
		return $num_rows;
	}

	function truncateTB($link,$tablename){
		$query = $link->query("TRUNCATE TABLE $tablename");

		if($query){
			return true;
		} else {
			return false;
		}
	}

	function deleteDataWhereOne($link,$table,$field,$var)
	{
		$query = $link->query(
			"DELETE 
			FROM 
				$table 
			WHERE 
				$field='$var'
		");
	}

	function getRequestNo($link,$table,$field){
		$query = $link->query(
			"SELECT 
				$field 
			FROM 
				$table 
			ORDER by 
				$field 
			DESC
		");

		$n = $query->num_rows;
		if($n>0){
			$row = $query->fetch_assoc();
			$row = $row[$field];
			$row++;
			$row = sprintf("%04d", $row);
			return $row;

		} else {
			return '0001';
		}
	}


	function getPromoGCRequestNo($link)
	{
		//get user promo tag


		$promotag = getField($link,'promo_tag','users','user_id',$_SESSION['gc_id']);

		$query = $link->query(
			"SELECT 
				pgcreq_reqnum
			FROM 
				promo_gc_request 
			WHERE 
				pgcreq_tagged = '$promotag'
			ORDER BY 
				pgcreq_reqnum
			DESC
			LIMIT 1
		");

		if($query)
		{
			$n = $query->num_rows;
			if($n>0)
			{
				$row = $query->fetch_assoc();
				$row = $row['pgcreq_reqnum'];
				$row++;
				$row = sprintf("%03d", $row);
				return $row;
			}
			else 
			{
				return '001';
			}
		}
		else 
		{
			return $link->error;
		}


		//echo $_SESSION['gc_id'];
	}

	function getLastNumberPromoReleasing($link,$select,$table,$field1,$var1)
	{
		$query = $link->query(
			"SELECT 
				$select
			FROM
				$table
			WHERE
				$field1 = '$var1'
			ORDER BY
				$select
			DESC
			LIMIT 1
		");

		$n = $query->num_rows;
		if($n>0){
			$row = $query->fetch_object();
			$row = $row->$select;
			$row++;
			return $row;

		} else {
			return '1';
		}
	}

	function getRequestNoByStore($link,$table,$field,$storeid)
	{
		$query = $link->query(
			"SELECT 
				$field 
			FROM 
				$table
			WHERE
				`sgc_store` = '$storeid' 
			ORDER by 
				$field 
			DESC
		");

		$n = $query->num_rows;
		if($n>0){
			$row = $query->fetch_assoc();
			$row = $row[$field];
			$row++;
			$row = sprintf("%04d", $row);
			return $row;

		} else {
			return '0001';
		}
	}

	function getRequestNoByExternal($link)
	{
		$query = $link->query(
			"SELECT 
				spexgc_num 
			FROM 
				special_external_gcrequest 
			ORDER BY 
				spexgc_num
			DESC
			LIMIT 1
		");

		if($query)
		{
			$n = $query->num_rows;
			if($n>0)
			{
				$row = $query->fetch_assoc();
				$row = $row['spexgc_num'];
				$row++;
				$row = sprintf("%03d", $row);
				return $row;
			}
			else 
			{
				return '0001';				
			}
		}
		else 
		{
			die('Query Error');
		}
	}

	function addZeroToString($id)
	{
		return sprintf("%06d",$id);
	}

	function addZeroToStringZ($id,$zero)
	{
		return sprintf("%0".$zero."d",$id);
	}

	function insertDenomRequest(
				$link,
				$table,
				$pe_id,
				$pe_denom,
				$pe_qty,
				$pe_req_id,
				$denom,
				$qty,
				$requestID,
				$pe_fieldrem){
			$query = $link->query("INSERT INTO 
					$table
					(
						$pe_id,
						$pe_denom,
						$pe_qty,
						$pe_fieldrem,
						$pe_req_id
					) 
					VALUES 
					(
						'',
						'$denom',
						'$qty',
						'$qty',
						'$requestID'
					)
			");

			if($query){
				return true;
			} else {
				return false;
			}
	}

	function insertPromoRequestDenoms($link,$qty,$reqid,$denomid)
	{
		$query = $link->query(
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
			    '$reqid',
			  	'$denomid',
			   	'$qty',
			    '$qty'
			)
		");

		if($query)
			return true;
		else 
			return false;
	}

	function checkUserAndPass($link,$username,$password){
		$username = $link->real_escape_string($username);
		$password = $link->real_escape_string($password);
		$query = $link->query(
				"SELECT 
					`username`,
					`password`
				FROM
					`users`
				WHERE
					`username`='$username'
				AND
					`password`='$password'
				");

		$num = $query->num_rows;

		if($num>0)
		{
			return true;
		} 
		else 
		{
			return false;
		}
	}

	function checkUsertype($link,$username)
	{
		$query = $link->query(
			"SELECT 
				`usertype`
			FROM
				`users`
			WHERE
				`username`='$username'
			AND
				`usertype`='7'
		");

		if($query)
		{
			if(($query->num_rows)>0)
			{
				return true;
			}
			else 
			{
				return false;
			}
		}
		else 
		{
			return false;
		}	
	}

	function checkUserRole($link,$username)
	{
		$query = $link->query(
			"SELECT 
				`usertype`
			FROM
				`users`
			WHERE
				`username`='$username'
			AND
				`user_role`='1'
		");

		if($query)
		{
			if(($query->num_rows)>0)
			{
				return true;
			}
			else 
			{
				return false;
			}
		}
		else 
		{
			return false;
		}
	}

	function checkStatus($link,$username)
	{
		$query = $link->query(
			"SELECT 
				`usertype`
			FROM
				`users`
			WHERE
				`username`='$username'
			AND
				`user_status`='active'
		");

		if($query)
		{
			if(($query->num_rows)>0)
			{
				return true;
			}
			else 
			{
				return false;
			}
		}
		else 
		{
			return false;
		}		
	}

	function checkStore($link,$username)
	{
		$query = $link->query(
			"SELECT 
				`store_assigned`
			FROM
				`users`
			WHERE
				`username`='$username'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->store_assigned;
		}
		else 
		{
			return $link->error;
		}
	}

	function totalGCAmount($link,$id){
        $query = $link->query(
        "SELECT 
            * 
        FROM 
            `production_request_items`
        INNER JOIN 
            `denomination`
        ON  
            `production_request_items`.`pe_items_denomination`=`denomination`.`denom_id`
        WHERE 
            `production_request_items`.`pe_items_request_id`= '$id'
        ");

		$n = $query->num_rows;
		if($n>0){
			$total=0;
			while($row = $query->fetch_assoc()){
				$sub = $row['denomination'] * $row['pe_items_quantity'];
				$total = $total + $sub;
			}

			return $total;
		}

	}

	function updateOne($link,$table,$set,$set1,$row,$row1){
		$query = $link->query(
			"UPDATE 
				$table 
			SET
				$set='$set1'
 
			WHERE 
				$row = '$row1'");

		if($query){
			return true;
		} else {
			return false;
		}
	}

	function getGCrequestItems($link,$selected,$table,$denom,$d,$reqID,$rid){
		$query = $link->query(
			"SELECT 
				$selected 
			FROM 
				$table 
			WHERE 
				$denom='$d'
			AND 
				$reqID='$rid'
			");
		
		if($query){
			$n = $query->num_rows;

			if($n>0){
				$row = $query->fetch_assoc();
				return $row[$selected];
			} else {
				return '0';
			}

		} else {
			return $link->error;
		}	

	}

	function releaseGC($link,$todays_date,$todays_time,$qty,$store,$denom,$request_id){
		$get_loc_gc = $link->query("SELECT * FROM 
									`gc_location` 
									INNER JOIN
									`gc`
									ON
									gc_location.loc_barcode_no = gc.barcode_no
									WHERE
									loc_store_id ='$store' 
									AND
									denom_id='$denom'
									AND 
									loc_rel=''
									ORDER BY `loc_id` ASC LIMIT $qty"
							);

		while($row_loc = $get_loc_gc->fetch_assoc()){
			$barcode = $row_loc['barcode_no'];
			// $o = $link->query("INSERT INTO `gc_release` VALUES ('','$barcode','$store','$todays_date','$todays_time','".$_SESSION['gc_id']."','','')");
			$o = $link->query(
				"INSERT INTO 
					`gc_release`
				(
					`re_barcode_no`, 
					`rel_storegcreq_id`, 
					`rel_store_id`, 
					`rel_date`, 
					`rel_time`, 
					`rel_by`
				) 
				VALUES 
				(
					'$barcode',
					'$request_id',
					'$store',
					NOW(),
					NOW(),
					'".$_SESSION['gc_id']."'
				)
			");
			$link->query("UPDATE gc_location SET loc_rel='*' WHERE loc_barcode_no='$barcode'");
			$link->query("UPDATE gc SET gc_released='*' WHERE barcode_no='$barcode'");
		}
		$query = $link->query("SELECT `denomination` FROM `denomination` WHERE `denom_id`='$denom'");
		$row_d = $query->fetch_array();
		$s_total = $row_d['denomination']*$qty;
		$link->query("INSERT INTO `entry_store` VALUES ('','ES','$denom','$qty','$store','$s_total','$todays_date','$todays_time','".$_SESSION['gc_id']."','')");
		$query_en = $link->query("SELECT `es_no` FROM `entry_store` ORDER BY `es_no` DESC");
		$row_en = $query_en->fetch_array(); 
		$row_en = $row_en['es_no'];
		$link->query("INSERT INTO `ledger_store` VALUES ('','$row_en','ES','$store',$qty,'$denom','$s_total','','$todays_date','$todays_time')");
	}

	function updateGCDenomReqItems($link,$items_qty,$items_den,$req_id){

		$query = $link->query(
			"UPDATE 
				store_request_items
			SET 
				sri_items_quantity='$items_qty',
				sri_items_remain = '$items_qty' 
			WHERE 
				sri_items_denomination='$items_den'
			AND
				sri_items_requestid='$req_id'

		");

		if($query)
		{
			return true;
		} 
		else 
		{
			return false;	
		}
	}

	function updateGCDenomReqItemsByItemID($link,$qty,$item_id,$req_id)
	{
		$query = $link->query(
			"UPDATE 
				store_request_items
			SET 
				sri_items_quantity='$qty',
				sri_items_remain = '$qty' 
			WHERE 
				sri_id = '$item_id'
			AND
				sri_items_requestid='$req_id'
		");

		if($query)
		{
			return true;
		} 
		else 
		{
			return false;	
		}
	}

	function checkbeforeUpdatePendingGC($link,$items_den,$reqid){
		$query = $link->query(
			"SELECT 
				*
			FROM
				`production_request_items`
			WHERE 
				`pe_items_denomination`='$items_den'
			AND 
				`pe_items_request_id`='$reqid'
		");

		if($query){
			$n = $query->num_rows;
			if($n>0){
				return true;
			} else {
				return false;
			}
		}
	}

	function checkbeforeUpdateGCrequest($link,$items_den,$req_id){
		$query = $link->query(
			"SELECT
				*
			FROM	
				`store_request_items`
			WHERE
				`sri_items_denomination` = '$items_den'
			AND
				`sri_items_requestid` = '$req_id'
		");

		if($query){
			$n = $query->num_rows;
			if($n>0){
				return true;
			} else {
				return false;
			}

		}
	}

	function checkbeforeUpdateGCrequestSetupDenom($link,$denomination,$reqID)
	{
		$query = $link->query(
			"SELECT
				store_request_items.sri_id,
				COUNT(store_request_items.sri_id) as cnt
			FROM 
				store_request_items 
			INNER JOIN
				for_denom_set_up
			ON
				for_denom_set_up.fds_denom_reqid = store_request_items.sri_id
			WHERE 
				store_request_items.sri_items_requestid = '$reqID'
			AND
				for_denom_set_up.fds_denom = '$denomination'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row;
		}
	}

	function updateProductionRequest($link,$items_qty,$items_den,$reqid){
		$query = $link->query(
			"UPDATE 
				`production_request_items` 
			SET 				
				`pe_items_quantity`='$items_qty',
				`pe_items_remain`='$items_qty'				
			WHERE 
				`pe_items_denomination`='$items_den'
			AND 
				`pe_items_request_id`='$reqid'
		");

		if($query){
			return true;
		} else {
			return false;
		}
	}

	function updatePromoRequestDenoms($link,$qty,$reqid,$denom)
	{
		$query = $link->query(
			"UPDATE 
				promo_gc_request_items 
			SET 
				pgcreqi_qty='$qty',
				pgcreqi_remaining='$qty' 
			WHERE 
				pgcreqi_trid='$reqid'
			AND
				pgcreqi_denom='$denom'
		");

		if($query)
			return true;
		else 
			return false;
	}

	// function updateGCDenomReqItems($link,$items_qty,$items_den,$reqid){
	// 	$query = $link->query(
	// 		"UPDATE
	// 			`store_request_items`
	// 		SET
	// 			`sri_items_quantity` = '$items_qty'
	// 		WHERE
	// 			`sri_items_denomination` = '$items_den'
	// 		AND
	// 			`sri_items_requestid` = '$reqid'
	// 	");

	// 	if($query){
	// 		return true;
	// 	} else {
	// 		return false;
	// 	}
	// }

	function deleteProductionRequestItem($link,$reqid,$items_den){
		$query= $link->query(
			"DELETE 
			FROM 
				`production_request_items` 
			WHERE 
				`pe_items_denomination`='$items_den'
			AND
				`pe_items_request_id`='$reqid'
		");

		if($query){
			return true;
		} else {
			return false;
		}
	}

	function deletePromoRequestItem($link,$reqid,$denomid)
	{
		$query = $link->query(
			"DELETE FROM 
				promo_gc_request_items 
			WHERE 
				pgcreqi_trid='$reqid'
			AND
				pgcreqi_denom='$denomid'
		");

		if($query)
			return true;
		else
			return false;
	}

	function deleteGCStoreRequest($link,$reqid,$items_den){
		$query = $link->query(
			"DELETE
			FROM
				`store_request_items`
			WHERE
				`sri_items_denomination`='$items_den'
			AND
				`sri_items_requestid`='$reqid'
		");

		if($query)
		{
			return true;
		} 
		else 
		{
			return false;
		}
	}

	function deleteGCStoreRequestByItemID($link,$reqid,$reqitemid)
	{
		$query = $link->query(
			"DELETE
			FROM
				store_request_items
			WHERE
				sri_id='$reqitemid'
			AND
				sri_items_requestid='$reqid'
		");

		if($query)
		{
			return true;
		} 
		else 
		{
			return false;
		}
	}

	function checkGCIfVerfified($link,$barcode){

		$query = $link->query("SELECT `vs_barcode` FROM `store_verification` WHERE `vs_barcode`='$barcode'");		

		if($query){

			if($query->num_rows > 0){
				return true;
			} else {
				return false;
			}		
		} 			
	}

	function checkGCIfSoldOut($link,$barcode){
		$query = $link->query(
			"SELECT 
				`sales_barcode` 
			FROM 
				`transaction_sales`
			WHERE 
				`sales_barcode`='$barcode'
		");

		if($query){
			if($query->num_rows > 0){
				return true;
			} else {
				return false;
			}
		}
	}

	function checkGCIfAvailable($link,$barcode){
		$query = $link->query(
			"SELECT
				`re_barcode_no`
			FROM 
				`gc_release`
			WHERE 
				`re_barcode_no`='$barcode'
		");

		if($query){
			if($query->num_rows > 0){
				return true;
			} else {
				return false;
			}
		}
	}

	function getdenomid($link,$barcode){
		$query = $link->query(
			"SELECT 
				`denom_id` 
			FROM 
				`gc` 
			WHERE 
				`barcode_no`='$barcode'
			");

		$row = $query->fetch_object();
		return $row->denom_id;

	}

	function getLastTrans($link){

	}

	function getStoreReleased($link,$barcode){

		$query = $link->query(
			"SELECT 
				`stores`.`store_name`
			FROM 
				`gc_release`
			INNER JOIN 
				`stores`
			ON
				`gc_release`.`rel_store_id` = `stores`.`store_id`
			WHERE 
				`re_barcode_no`='$barcode'
		");

		$row = $query->fetch_object();
		return $row->store_name;

	}

	function getStoreAssignedByUsername($link,$username)
	{
		$query = $link->query(
			"SELECT 
				`stores`.`store_name`
			FROM 
				`users`
			INNER JOIN
				`stores`
			ON
				`users`.`store_assigned` = `stores`.`store_id`
			WHERE 
				`users`.`username`='$username'
		");

		if($query) 
		{
			$row = $query->fetch_object();
			return $row->store_name;
		}
		else
		{
			return $link->error;
		}
	}

	function getCurrentAvailableGCByStore($link,$store_code,$denom_id){
		
		$query = $link->query(
			"SELECT 
				`strec_barcode` 
			FROM 
				`store_received_gc` 
			WHERE 
				`strec_storeid`='$store_code'
			AND
				`strec_denom` = '$denom_id'
			AND
				`strec_sold` = ''	
			AND
				`strec_transfer_out`=''
			AND
				`strec_bng_tag`=''
		");

		if($query){
			$n = $query->num_rows;
			return $n;
		} else {
			return $link->error;
		}
	}

	function getReleasedGCByStore($link,$store_code,$denom_id){
		
		$query = $link->query(
			"SELECT 
			`gc_release`.`re_barcode_no`,
			`gc`.`denom_id`
			FROM 
				`gc_release`
			INNER JOIN 
				`gc`
			ON
				`gc_release`.`re_barcode_no`=`gc`.`barcode_no`
			WHERE 
				`gc_release`.`rel_store_id`='$store_code'
			AND
				`gc`.`denom_id`='$denom_id'
		");

		if($query){
			$n = $query->num_rows;
			return $n;
		} else {
			return $link->error;
		}
	}

	function getReleasedGCByGCRequestID($link,$relid,$reqid)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				`denomination`.`denom_id`,
				`denomination`.`denomination`,
				SUM(denomination.denomination) as tot,
				COUNT(denomination.denomination) as c				 
			FROM 
				`gc_release` 
			INNER JOIN 
				`gc` 
			ON 
				`gc`.`barcode_no` = `gc_release`.`re_barcode_no` 
			INNER JOIN 
				`denomination` 
			ON 
				`denomination`.`denom_id` = `gc`.`denom_id` 
			WHERE 
				`gc_release`.`rel_storegcreq_id`='$reqid' 
			AND 
				`gc_release`.`rel_num`='$relid' 
			GROUP BY 
				`denomination`.`denomination`
		");

		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $link->error;
		}
	}


	function getReleasedGCByGCRequestID2($link,$relid,$reqid)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				denomination.denom_id,
				denomination.denomination,
				SUM(denomination.denomination) as tot,
				COUNT(denomination.denomination) as c				 
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
				gc_release.rel_num='$relid' 
			GROUP BY 
				denomination.denomination
		");

		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $link->error;
		}
	}

	function getSoldGCPerStore($link,$store_id,$denom_id){

		$query = $link->query(
			"SELECT 
				`strec_barcode` 
			FROM 
				`store_received_gc` 
			WHERE 
				`strec_storeid`='$store_id'
			AND
				`strec_denom` = '$denom_id'
			AND
				`strec_sold` = '*'			
		");

		if($query){
			$n = $query->num_rows;
			return $n;
		} else {
			return $link->error;
		}
	}

	function getSupervisorFullname($link){
		$query = $link->query(
			"SELECT 
				`ss_firstname`,
				`ss_lastname` 
			FROM 
				`store_staff` 
			WHERE
				`ss_id`='".$_SESSION['gc_super_id']."' 
		");

		$row = $query->fetch_object();

		return $row->ss_firstname.' '.$row->ss_lastname;
	}

	function salesyreport($link,$todays_date){
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
				'".$_SESSION['gc_id']."',
				'".$_SESSION['gc_super_id']."',
				'".$_SESSION['gc_store']."',
				NOW()
			)
		");

		if($query_ins){
			$query = $link->query(
				"UPDATE 
					`transaction_stores` 
				SET
					`trans_yreport`='1' 
				WHERE
					`trans_cashier` = '".$_SESSION['gc_id']."'
				AND
					`trans_store` = '".$_SESSION['gc_store']."'
				AND
					`trans_datetime` LIKE '%$todays_date%'
				AND 
					`trans_yreport`='0' 
			");

			if($query){

				$link->commit();

			} else {
				echo $link->error;
			}
		} else {
			echo $link->error;
		}
	}

	function getGCReturned($link,$todays_date,$store,$cashier){

		$results = array();

		$query = $link->query(
			"SELECT 
			* 
			FROM 
			`gc_return` 
			INNER JOIN
			`denomination`
			ON
			`gc_return`.`rr_denom_id` = `denomination`.`denom_id` 
			WHERE
			`gc_return`.`rr_store`='$store'
			AND
			`gc_return`.`rr_cashier`='$cashier'
			AND
			`gc_return`.`rr_datetime` LIKE '%$todays_date%'

		");

		if($query)
		{
			while($row = $query->fetch_object())
			{
				$results[] = $row;
			}

			return $results;
		}
	}


	function getGCReturnedEOD($link,$todays_date,$store)
	{
		$results = array();

		$query = $link->query(
			"SELECT 
			* 
			FROM 
			`gc_return` 
			INNER JOIN
			`denomination`
			ON
			`gc_return`.`rr_denom_id` = `denomination`.`denom_id` 
			WHERE
			`gc_return`.`rr_store`='$store'
			AND
			`gc_return`.`rr_datetime` LIKE '%$todays_date%'

		");

		if($query)
		{
			while($row = $query->fetch_object())
			{
				$results[] = $row;
			}

			return $results;
		}

	}

	function getGCReturnedtest($link,$todays_date){

		$results = array();

		$query = $link->query(
			"SELECT 
			* 
			FROM 
			`gc_return` 
			INNER JOIN
			`denomination`
			ON
			`gc_return`.`rr_denom_id` = `denomination`.`denom_id` 
			WHERE
			`gc_return`.`rr_store`='3'
			AND
			`gc_return`.`rr_cashier`='5'
			AND
			`gc_return`.`rr_datetime` LIKE '%$todays_date%'

		");

		if($query)
		{
			while($row = $query->fetch_object())
			{
				$results[] = $row;
			}

			return $results;
		}
	}

	function getEndofDayTotal($link,$tender){

	}

	function refundGC($link,$todays_date){
		$query = $link->query(
			"SELECT 
				SUM(`denomination`.`denomination`) as totalsales  
			FROM 
				`transaction_sales`
			INNER JOIN
				`transaction_stores`
			ON
				`transaction_sales`.`sales_transaction_id` = `transaction_stores`.`trans_sid`
			INNER JOIN
				`denomination`
			ON
				`transaction_sales`.`sales_denomination` = `denomination`.`denom_id`
			WHERE	
				`transaction_stores`.`trans_datetime` LIKE '%$todays_date%'
			AND
				`transaction_stores`.`trans_cashier` = '".$_SESSION['gc_ id']."'
			AND	
				`transaction_stores`.`trans_store` = '".$_SESSION['gc_store']."'
			AND
				`transaction_stores`.`trans_status` = '0'
			AND 
				`transaction_sales`.`sales_item_status`='1'
		");
		if($query){
			$row = $query->fetch_object();
			return $row->totalsales;
		} else {
			return $link->error;
		}
	}

	function getonSales($link,$todays_date,$tender){
		$query = $link->query(
			"SELECT 
				SUM(`denomination`.`denomination`) as totalsales  
			FROM 
				`transaction_sales`
			INNER JOIN
				`transaction_stores`
			ON
				`transaction_sales`.`sales_transaction_id` = `transaction_stores`.`trans_sid`
			INNER JOIN
				`denomination`
			ON			
				`transaction_sales`.`sales_denomination` = `denomination`.`denom_id`
			INNER JOIN
				`transaction_payment`
			ON
				`transaction_stores`.`trans_sid` = `transaction_payment`.`payment_trans_num`
			WHERE	
				`transaction_stores`.`trans_datetime` LIKE '%$todays_date%'
			AND
				`transaction_stores`.`trans_cashier` = '".$_SESSION['gc_id']."'
			AND	
				`transaction_stores`.`trans_store` = '".$_SESSION['gc_store']."'
			AND
				`transaction_payment`.`payment_tender`='$tender'
		");
		if($query){
			$row = $query->fetch_object();
			return $row->totalsales;
		} else {
			return $link->error;
		}
	}

	function getonSalesEOD($link,$todays_date,$tender)
	{
		$query = $link->query(
			"SELECT 
				SUM(`denomination`.`denomination`) as totalsales  
			FROM 
				`transaction_sales`
			INNER JOIN
				`transaction_stores`
			ON
				`transaction_sales`.`sales_transaction_id` = `transaction_stores`.`trans_sid`
			INNER JOIN
				`denomination`
			ON			
				`transaction_sales`.`sales_denomination` = `denomination`.`denom_id`
			INNER JOIN
				`transaction_payment`
			ON
				`transaction_stores`.`trans_sid` = `transaction_payment`.`payment_trans_num`
			WHERE	
				`transaction_stores`.`trans_datetime` LIKE '%$todays_date%'
			AND	
				`transaction_stores`.`trans_store` = '".$_SESSION['gc_store']."'
			AND
				`transaction_payment`.`payment_tender`='$tender'
		");
		if($query){
			$row = $query->fetch_object();
			return $row->totalsales;
		} else {
			return $link->error;
		}
	}

	function endofDay($link,$todays_date){

		$link->autocommit(FALSE);

		$query = $link->query(
			"UPDATE 
				`transaction_stores` 
			SET 
				`trans_status`='1'
			WHERE 
				`trans_store`='".$_SESSION['gc_store']."'
			AND
				`trans_datetime` LIKE '%$todays_date%'
			AND
				`trans_status`='0'
		");

		if($query){
			$query_eod = $link->query(
				"INSERT INTO 
					`transaction_endofday`
				(
					`eod_id`, 
					`eod_store`, 
					`eod_supervisor_id`, 
					`eod_datetime`
				) 
				VALUES 
				(
					'',
					'".$_SESSION['gc_store']."',
					'".$_SESSION['gc_super_id']."',
					NOW()
				)
			");

			if($query_eod){
				$link->commit();
			}  else {
				echo $link->error;
			}
		} else {
			echo $link->error;
		} 
	
	}

	function getFullname($link,$id){
		$id = $link->real_escape_string($id);
		$query = $link->query(
			"SELECT 
				`firstname`,
				`lastname`
			FROM 
				`users` 
			WHERE 
				`user_id` = '$id'
		");

		$row = $query->fetch_object();

		return $row->firstname.' '.$row->lastname;
	}

	function checkRequest($link,$table,$field,$var){
		$query = $link->query(
			"SELECT 
				$field 
			FROM 
				$table 
			WHERE 
				$field='$var'
		");

		$n = $query->num_rows;

		return $n;
	}

	function promoRequestCount($link,$type)
	{

		//get user promotag and user type

		$promo_tag = getField($link,'promo_tag','users','user_id',$_SESSION['gc_id']);
		$usertype = getField($link,'usertype','users','user_id',$_SESSION['gc_id']);

		$count = 0;
		if($type=='pending')
		{

		}
		elseif($type=='approved')
		{

			if($usertype=='6')
			{
				$qfield = 'promo_gc_request.pgcreq_tagged';
				$qvar = '1';
			}
			elseif ($usertype=='8') 
			{
				//get user group
				$qfield = 'promo_gc_request.pgcreq_group';
				$qvar = getField($link,'usergroup','users','user_id',$_SESSION['gc_id']);
			}

			$query = $link->query(
				"SELECT 
					* 
				FROM 
					promo_gc_request 
				WHERE 
					$qfield='$qvar'
				AND
					promo_gc_request.pgcreq_status='approved'
			");

			if($query)
			{
				$count = $query->num_rows;
			}
			else 
			{
				$count = 0;
			}
		}
		elseif($type=='cancel')
		{

		}

		return $count;
	}

	function checkGCStoreRequest($link,$table,$field,$var,$var1)
	{
		$query = $link->query(
			"SELECT 
				$field 
			FROM 
				$table 
			WHERE 
				$field='$var'
		");

		$n = $query->num_rows;

		return $n;
	}

	function countStoresRequest($link)
	{
		$query = $link->query(
			"SELECT 
				sgc_id 
			FROM 
				store_gcrequest 
			WHERE 
				(sgc_status=0 OR sgc_status = 1) 
			AND sgc_cancel = ''
		");

		$n =  $query->num_rows;
		return $n;
	}

	function checkRequestStore($link,$table,$field1,$var1,$field2,$var2){
		$query = $link->query(
			"SELECT 
				$field1 
			FROM 
				$table 
			WHERE 
				$field1='$var1'
			AND
				$field2='$var2'
		");

		$n = $query->num_rows;

		return $n;
	}

	function countStoreGCRequestPending($link,$storeid)
	{
		$query = $link->query(
			"SELECT 
				`sgc_num` 
			FROM 
				`store_gcrequest`
			WHERE 
				`sgc_cancel` = ''
			AND
				`sgc_store`='$storeid'
			AND
				`sgc_status`='0'
		");

		if($query)
		{
			return $query->num_rows;
		}	
		else 
		{
			return $link->error;
		}
	}

	function GCReleasedAllStore($link)
	{
		$rows = [];
		$query = $link->query(
		"SELECT
			`approved_gcrequest`.`agcr_id`,
			`stores`.`store_name`,
			`approved_gcrequest`.`agcr_approved_at`,
			`approved_gcrequest`.`agcr_approvedby`,
			`approved_gcrequest`.`agcr_preparedby`,
			`approved_gcrequest`.`agcr_rec`,
			`approved_gcrequest`.`agcr_request_relnum`,
			`agcr_request_relnum`,
			`users`.`firstname`,
			`users`.`lastname`,
			`store_gcrequest`.`sgc_date_request`
		FROM
			`approved_gcrequest`
		INNER JOIN
			`store_gcrequest`
		ON
			`approved_gcrequest`.`agcr_request_id` = `store_gcrequest`.`sgc_id`
		INNER JOIN
			`stores`
		ON
			`store_gcrequest`.`sgc_store` = `stores`.`store_id`
		INNER JOIN
			`users`
		ON
			`approved_gcrequest`.`agcr_preparedby` = `users`.`user_id`
		ORDER BY
			`approved_gcrequest`.`agcr_id`
		DESC
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else
		{
			return $rows[] = $link->error;
		}
	}

	function countAllGCRequestCancelled($link)
	{
		$query = $link->query(
			"SELECT 
				`sgc_num` 
			FROM 
				`store_gcrequest` 
			WHERE 
				`sgc_status`=0
			AND
				`sgc_cancel`='*'
		");

		if($query)
		{
			return $query->num_rows;
		}
		else 
		{
			return $link->query;
		}
	}

	function countStoreGCRequestCancelled($link,$storeid)
	{
		$query = $link->query(
			"SELECT 
				`sgc_num` 
			FROM 
				`store_gcrequest`
			WHERE 
				`sgc_cancel` = '*'
			AND
				`sgc_store`='$storeid'
			AND
				`sgc_status`='0'
		");

		if($query)
		{
			return $query->num_rows;
		}	
		else 
		{
			return $link->error;
		}		
	}

	function getResults($link,$table,$order){
		$rows = [];
		$query = $link->query(
			"SELECT 
				* 
			FROM
				$table
			ORDER BY $order DESC			
		");

		if($query){
			$n = $query->num_rows;

			while($row = $query->fetch_array()){
				$rows[] = $row;
			}
			return $rows;
		} else {
			return $link->error;  
		}
	}

	function getBarcodeNumberReq($link,$denom,$id,$pos)
	{
		$query = $link->query(
		  "SELECT 
		      `barcode_no`
		  FROM 
		      `gc`
		  WHERE 
		      `denom_id`='$denom'
		  AND
		     `pe_entry_gc`='$id' 
		  ORDER BY 
		  `barcode_no`
		  $pos 
		  LIMIT 1"     
		);

		$row = $query->fetch_object();

		return $row->barcode_no;
	}

	function getAllItemsWithBarcode($link,$id)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
			  * 
			FROM
			  `production_request_items`
			INNER JOIN 
			  `denomination`
			ON 
			  `production_request_items`.`pe_items_denomination` = `denomination`.`denom_id`
			WHERE 
			  `pe_items_request_id`='$id'
		");

		if($query)
		{
			while($row = $query->fetch_object())
			{
				$rows[] = $row;	
			}
			return $rows;
		}
		else
		{
			return $rows[] = $link->error;
		}

	}

	function getDataForAdjustment($link,$table,$gc_id,$qty)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				* 
			FROM 
				`$table`
			WHERE 
				`denom_id`='$gc_id'
			AND
				`gc_validated`=''
			ORDER BY `barcode_no` DESC
			LIMIT $qty
		");

		if($query)
		{
			while($row = $query->fetch_array())
			{
				$rows[] = $row;
			}

			return $rows;
		}
		else 
		{
			return $link->error;
		}
	}

	function getApprovedBudgetRequest($link)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				* 
				FROM 
					`store_gcrequest`
				INNER JOIN
					`approved_gcrequest`
				ON
					`store_gcrequest`.`sgc_id` = `approved_gcrequest`.`agcr_request_id`
				INNER JOIN
					`stores`
				ON
					`store_gcrequest`.`sgc_store` = `stores`.`store_id`    
				WHERE 	
					`store_gcrequest`.`sgc_status`='1'
			");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		} 
		else 
		{
			return $link->error;
		}
	}

	function getReleasedGCRequestAllStore($link)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				* 
				FROM 
					`store_gcrequest`
				INNER JOIN
					`approved_gcrequest`
				ON
					`store_gcrequest`.`sgc_id` = `approved_gcrequest`.`agcr_request_id`
				INNER JOIN
					`stores`
				ON
					`store_gcrequest`.`sgc_store` = `stores`.`store_id`    
				WHERE 	
					`store_gcrequest`.`sgc_status`='2'
			");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		} 
		else 
		{
			return $link->error;
		}
	}

	function getApprovedBudgetRequestByStore($link,$storeid)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
			    `store_gcrequest`.`sgc_id`,
				`store_gcrequest`.`sgc_requested_by`,
			    `store_gcrequest`.`sgc_date_request`,
			    `stores`.`store_name`,
			    `approved_gcrequest`.`agcr_approved_at`,
			    `approved_gcrequest`.`agcr_approvedby`,
			    `store_gcrequest`.`sgc_num`,
			    `store_gcrequest`.`sgc_rec`
			FROM 
				`store_gcrequest`
			INNER JOIN
				`approved_gcrequest`
			ON
				`store_gcrequest`.`sgc_id` = `approved_gcrequest`.`agcr_request_id`
			INNER JOIN
				`stores`
			ON
				`store_gcrequest`.`sgc_store` = `stores`.`store_id`    
			WHERE 	
				`store_gcrequest`.`sgc_status`='1'
			AND	
				`store_gcrequest`.`sgc_store`='$storeid'
			ORDER BY 
				`store_gcrequest`.`sgc_id`
			DESC
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		} 
		else 
		{
			return $link->error;
		}
	}

	function getGCReleasedForStores($link,$storeid)
	{
		$rows = [];
		$query = $link->query(
		"SELECT
			`approved_gcrequest`.`agcr_id`,
			`stores`.`store_name`,
			`approved_gcrequest`.`agcr_request_relnum`,
			`approved_gcrequest`.`agcr_approved_at`,
			`approved_gcrequest`.`agcr_approvedby`,
			`approved_gcrequest`.`agcr_preparedby`,
			`approved_gcrequest`.`agcr_rec`,
			`users`.`firstname`,
			`users`.`lastname`,
			`store_gcrequest`.`sgc_date_request`
		FROM
			`approved_gcrequest`
		INNER JOIN
			`store_gcrequest`
		ON
			`approved_gcrequest`.`agcr_request_id` = `store_gcrequest`.`sgc_id`
		INNER JOIN
			`stores`
		ON
			`store_gcrequest`.`sgc_store` = `stores`.`store_id`
		INNER JOIN
			`users`
		ON
			`approved_gcrequest`.`agcr_preparedby` = `users`.`user_id`
		WHERE
			`store_gcrequest`.`sgc_store`='$storeid'
		ORDER BY
			`approved_gcrequest`.`agcr_id`
		DESC
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else
		{
			return $rows[] = $link->error;
		}
	}

	function getApprovedBudgetRequestById($link,$id)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				*
			FROM 
				`store_gcrequest`
			INNER JOIN
				`approved_gcrequest`
			ON
				`store_gcrequest`.`sgc_id` = `approved_gcrequest`.`agcr_request_id`
			INNER JOIN
				`stores`
			ON
				`store_gcrequest`.`sgc_store` = `stores`.`store_id`
            INNER JOIN
            	`users`
            ON
            	`approved_gcrequest`.`agcr_preparedby` = `users`.`user_id`    
			WHERE 	
				`store_gcrequest`.`sgc_status`='1'
			AND
				`store_gcrequest`.`sgc_id`='$id'
			");

		if($query)
		{
			while ($row = $query->fetch_assoc()) {
				$rows[] = $row;
			}
			return $rows;
		} 
		else 
		{
			return $link->error;
		}
	}

	function approvedGCrequestItems($link,$id)
	{
		$rows = [];

		$query = $link->query(
			"SELECT 
			* 
			FROM 
				`store_request_items`
			INNER JOIN
				`denomination`
			ON
				`store_request_items`.`sri_items_denomination` = `denomination`.`denom_id`
			WHERE
				`store_request_items`.`sri_items_requestid`='$id'
		");

		if($query)
		{
			while($row = $query->fetch_assoc()){
				$rows[] = $row;
			}

			return $rows;
		}	
		else
		{

		}
	}

	function getAllCancelledGCRequestStore($link)
	{
		$rows = [];

		$query = $link->query(
			"SELECT 
				`store_gcrequest`.`sgc_id`,
				`store_gcrequest`.`sgc_num`,
				`cancelled_store_gcrequest`.`csgr_by`,
				`cancelled_store_gcrequest`.`csgr_at`,
				`stores`.`store_name`,
				`store_gcrequest`.`sgc_requested_by`,
				`users`.`firstname`,
				`users`.`lastname`,
				`store_gcrequest`.`sgc_date_request`

			FROM 
				`store_gcrequest` 
			INNER JOIN
				`cancelled_store_gcrequest`
			ON
				`store_gcrequest`.`sgc_id` = `cancelled_store_gcrequest`.`csgr_gc_id`
			INNER JOIN 
				`stores`
			ON
				`store_gcrequest`.`sgc_store` = `stores`.`store_id`
			INNER JOIN
				`users`
			ON
				`store_gcrequest`.`sgc_requested_by` = `users`.`user_id`
			WHERE 
				`store_gcrequest`.`sgc_status`=0
			AND
				`store_gcrequest`.`sgc_cancel`='*'			
		");

		if($query)
		{
			while($row = $query->fetch_object())
			{
				$rows[] = $row;
			}

			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}

	}

	function getAllCancelledGCRequestStoreById($link,$id){

		$rows = [];

		$query = $link->query(
			"SELECT 
				* 
			FROM 
				store_gcrequest
			INNER JOIN
				cancelled_store_gcrequest
			ON
				store_gcrequest.sgc_id = cancelled_store_gcrequest.csgr_gc_id
			INNER JOIN
				users
			ON
				store_gcrequest.sgc_requested_by = users.user_id
			INNER JOIN 
				stores
			ON
				store_gcrequest.sgc_store = stores.store_id    
			WHERE
				store_gcrequest.sgc_id='$id'
		");

		if($query)
		{
			while($row = $query->fetch_object())
			{
				$rows[] = $row;
			}

			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function getAllCancelledGCRequestByStore($link,$storeid)
	{
		$rows = [];

		$query = $link->query(
			"SELECT 
				* 
			FROM 
				`store_gcrequest` 
			INNER JOIN
				cancelled_store_gcrequest
			ON
				store_gcrequest.sgc_id = cancelled_store_gcrequest.csgr_gc_id
			INNER JOIN 
				stores
			ON
				store_gcrequest.sgc_store = stores.store_id
			INNER JOIN
				users
			ON
				store_gcrequest.sgc_requested_by = users.user_id
			WHERE 
				store_gcrequest.sgc_status=0
			AND
				store_gcrequest.sgc_cancel='*'
			AND 
				store_gcrequest.sgc_store = '$storeid'
		");

		if($query)
		{
			while($row = $query->fetch_object())
			{
				$rows[] = $row;
			}

			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}

	}

	function getAllCancelledGCRequestStoreItemsById($link,$id)
	{
		$rows = [];

		$query = $link->query(
			"SELECT 
				* 
			FROM 
				`store_request_items`
			INNER JOIN
				`denomination`
			ON
				`store_request_items`.`sri_items_denomination` = `denomination`.`denom_id`
			WHERE 
				`store_request_items`.`sri_items_requestid`='$id'
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}

			return $rows; 
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function getRequisitionDetails($link,$id)
	{
		$rows = [];

		$query = $link->query(
			"SELECT 
				`requisition_entry`.`requis_erno`,
				`requisition_entry`.`requis_req`,
				`production_request`.`pe_date_needed`,
				`requisition_entry`.`requis_loc`,
				`requisition_entry`.`requis_dept`,
				`requisition_entry`.`requis_rem`,
				`requisition_entry`.`requis_checked`,
				`requisition_entry`.`requis_approved`,
				`supplier`.`gcs_companyname`,
				`supplier`.`gcs_contactperson`,
				`supplier`.`gcs_contactnumber`,
				`supplier`.`gcs_address`,
				`users`.`firstname`,
				`users`.`lastname`
			FROM 
				`requisition_entry` 
			INNER JOIN
				`production_request`
			ON
				`requisition_entry`.`repuis_pro_id`=`production_request`.`pe_id`
			INNER JOIN
				`supplier`
			ON	
				`requisition_entry`.`requis_supplierid`=`supplier`.`gcs_id`
			INNER JOIN
				`users`
			ON
				`users`.`user_id` = `requisition_entry`.`requis_req_by`
			WHERE 
				`requisition_entry`.`repuis_pro_id`='$id'
		");

		if($query)
		{
			while($row = $query->fetch_object())
			{
				$rows[] = $row;				
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function getAllCancelledGCRequest($link,$table,$join1,$on1,$on2,$join2,$on3,$on4,$where,$field)
	{
		$rows = [];

		$query = $link->query(
			"SELECT 
				* 
			FROM 
				$table
			INNER JOIN
				$join1
			ON
				$on1 = $on2
			INNER JOIN
				$join2
			ON
				$on3 = $on4
			WHERE
				$where=$field
		");

		if($query)
		{
			while($row = $query->fetch_object())
			{
				$rows[] = $row;
			}

			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function getAllCancelledProductionItems($link,$id)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				* 
			FROM 
				`production_request_items`
			INNER JOIN
				`denomination`
			ON
			 	`production_request_items`.`pe_items_denomination`=`denomination`.`denom_id`
			WHERE 
				`pe_items_request_id`='$id'
		");

		if($query)
		{
			while($row = $query->fetch_object())
			{
				$rows[] = $row; 
			}

			return $rows;
		}
		else 
		{
			return $row[] = $link->error;
		}
	}

	function insertToLedger($link,$bno,$amountAdj,$entry,$btype)
	{
		$query = $link->query(
			"INSERT INTO 
			`ledger_budget`
				(						
					`bledger_no`, 
					`bledger_datetime`, 
					`bledger_type`, 
					`$entry`
				) 
			VALUES 
				(
					'$bno',
					NOW(),
					'$btype',
					'$amountAdj'
				)
			");
		if($query)
		{
			return $link->insert_id;
		} 
		else 
		{
			echo $link->error;
		}
	}

	function countGCDenom($link,$id)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				* 
			FROM 
				`production_request_items`
			INNER JOIN
				`denomination`
			ON
				`production_request_items`.`pe_items_denomination` = `denomination`.`denom_id`
			WHERE 
				`production_request_items` .`pe_items_request_id` = '$id'
		");

		if($query)
		{
			while($row = $query->fetch_object())
			{
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function getGCReleasedItemsById($link,$id)
	{
		$row = [];
		$query = $link->query(
			"SELECT 
				* 
			FROM 
				`gc_release`
			INNER JOIN
				`gc`
			ON
			`gc_release`.`re_barcode_no` = `gc`.`barcode_no`
			INNER JOIN
				`denomination`
			ON
			`gc`.`denom_id` = `denomination`.`denom_id`
			WHERE 
				`rel_storegcreq_id`='$id'
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function getAllocatedGC($link,$store,$gctype)
	{
		$rows = [];

		$query = $link->query(
			"SELECT 
				gc_location.loc_barcode_no,
				gc_location.loc_date,
				users.firstname,
				users.lastname,
				gc_type.gctype,
				production_request.pe_num,
				denomination.denomination
			FROM 
				gc_location
			INNER JOIN
				gc
			ON
				gc_location.loc_barcode_no = gc.barcode_no
			INNER JOIN
				denomination
			ON
				gc.denom_id = denomination.denom_id
			INNER JOIN
				users
			ON	
				gc_location.loc_by=users.user_id
			INNER JOIN
				production_request
			ON
				gc.pe_entry_gc = production_request.pe_id 
			INNER JOIN
				gc_type
			ON
				gc_type.gc_type_id = gc_location.loc_gc_type
			WHERE 
				gc_location.loc_store_id='$store'
			AND
				gc_location.loc_rel=''
			AND
				gc_location.loc_gc_type='$gctype'
		");

		if($query)
		{
			while($row = $query->fetch_object())
			{
				$rows[] = $row;
			}

			return $rows;
		}
		else 
		{
			echo $rows[] = $link->error;
		}
	}

	function insertAdjustmentLedger($link,$last_id,$remark,$adj)
	{
		$query = $link->query(
			"INSERT INTO 
				`budget_adjustment`
			(
				`bud_ledger_id`,
				`bud_adj_type`, 
				`bud_remark`, 
				`bud_by`
			) 
			VALUES 
			(
				'$last_id',
				'$adj',
				'$remark',
				'".$_SESSION['gc_id']."'
			)");

		if($query)
		{
			return true;
		} 
		else 
		{
			return $link->error;
		}
	}

	function getGCBarcodeDetails($link,$id,$denom)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				* 
			FROM 
				`gc` 
			WHERE 
				`denom_id`='$denom'
			AND
				`pe_entry_gc`='$id'
		");

		if($query)
		{
			while($row = $query->fetch_object())
			{
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}

	}

	function getGCBarcodeForValidation($link,$id,$denom)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				gc.barcode_no,
				denomination.denomination
			FROM 
				gc 
			INNER JOIN
				denomination
			ON
				denomination.denom_id = gc.denom_id
			WHERE 
				gc.denom_id='$denom'
			AND
				gc.pe_entry_gc='$id'
			AND
				gc.gc_validated=''
			ORDER BY
				gc.barcode_no
			ASC
		");

		if($query)
		{
			while($row = $query->fetch_object())
			{
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}	
	}
	function getGCBarcodeValidated($link,$id,$denom)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				gc.barcode_no,
				denomination.denomination,
				custodian_srr.csrr_datetime
			FROM 
				gc 
			INNER JOIN
				denomination
			ON
				denomination.denom_id = gc.denom_id
			INNER JOIN
				custodian_srr_items
			ON
				custodian_srr_items.cssitem_barcode = gc.barcode_no
			INNER JOIN
				custodian_srr
			ON
				custodian_srr.csrr_id = custodian_srr_items.cssitem_recnum
			WHERE 
				gc.denom_id='$denom'
			AND
				gc.pe_entry_gc='$id'
			AND
				gc.gc_validated='*'
			ORDER BY
				gc.barcode_no
			ASC
		");

		if($query)
		{
			while($row = $query->fetch_object())
			{
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}			
	}

	function getBarcodeValidatedDenom($link,$id)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				denomination.denomination,
				gc.denom_id
			FROM 
				gc 
			INNER JOIN
				denomination
			ON
				denomination.denom_id = gc.denom_id
			WHERE 
				pe_entry_gc='$id'
			AND
				gc_validated='*'
			GROUP BY
				denomination.denomination
		");

		if($query)
		{
			while($row = $query->fetch_object())
			{
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}			
	}

	function getGCBarcodeForValidationDenomination($link,$id)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				denomination.denomination,
				gc.denom_id
			FROM 
				gc 
			INNER JOIN
				denomination
			ON
				denomination.denom_id = gc.denom_id
			WHERE 
				pe_entry_gc='$id'
			AND
				gc_validated=''
			GROUP BY
				denomination.denomination
		");

		if($query)
		{
			while($row = $query->fetch_object())
			{
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}	
	}

	function getGCBarcodeLastNumber($link,$denId)
	{
		$query = $link->query(
			"SELECT 
				`barcode_no` 
			FROM 
				`gc` 
			WHERE
				`denom_id`='$denId' 
			AND 
				`gc_validated`='' 
			ORDER BY 
				`barcode_no` 
			DESC LIMIT 1
		");

		if($query){
			$row = $query->fetch_object();
			return $row->barcode_no;
		} else {
			return $query->error;
		}		
	}

	function countGCForValidation($link,$denId)
	{
		$query = $link->query(
			"SELECT 
				`barcode_no` 
			FROM 
				`gc`
			INNER JOIN
				`production_request`
			ON
				`gc`.`pe_entry_gc` = `production_request`.`pe_id`
			WHERE
				`gc`.`denom_id`='$denId' 
			AND 
				`production_request`.`pe_requisition`='0'
			AND 
				`gc`.`gc_validated`=''
		");

		if($query)
		{
			$n = $query->num_rows;
			return $n;
		} 
		else 
		{
			$link->error;
		}
	}

	function availableGC($link,$storeid)
	{
		$rows = [];
		$query = $link->query(		
			"SELECT
				`store_received_gc`.`strec_barcode`,
				`gc_release`.`rel_storegcreq_id`,
				`denomination`.`denomination`,
				`store_received_gc`.`strec_return`,
				`transaction_refund`.`refund_trans_id`,
				`transaction_stores`.`trans_datetime`,
				`store_gcrequest`.`sgc_num`
			FROM 
				`store_received_gc`
			INNER JOIN
				`gc_release`
			ON
				`gc_release`.`re_barcode_no` = `store_received_gc`.`strec_barcode`
			INNER JOIN
				`store_gcrequest`
			ON
				`store_gcrequest`.`sgc_id` = `gc_release`.`rel_storegcreq_id`
			INNER JOIN
				`denomination`
			ON
				`store_received_gc`.`strec_denom` = `denomination`.`denom_id`
			LEFT JOIN
				`transaction_refund`
			ON
				`transaction_refund`.`refund_barcode` = `store_received_gc`.`strec_barcode`
			LEFT JOIN
				`transaction_stores`
			ON
				`transaction_stores`.`trans_sid` = `transaction_refund`.`refund_trans_id`
			WHERE 
				`store_received_gc`.`strec_sold`=''
			AND
				`store_received_gc`.`strec_storeid` = '$storeid'
			AND
				`store_received_gc`.`strec_transfer_out`=''
			AND
				`store_received_gc`.`strec_bng_tag`=''
			GROUP BY
				`store_received_gc`.`strec_barcode`
			ORDER BY 
				`transaction_refund`.`refund_id`
			DESC

		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else
		{
			return $rows = $link->error;
		}
	}

	function releasedGC($link,$storeid)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				* 
			FROM 
				`gc_release`
			INNER JOIN
				`gc_location`
			ON	 
				`gc_release`.`re_barcode_no` = `gc_location`.`loc_barcode_no`
			INNER JOIN
				`gc`
			ON
				`gc_release`.`re_barcode_no` = `gc`.`barcode_no`
			INNER JOIN
				`denomination`
			ON
				`gc`.`denom_id` = `denomination`.`denom_id`
			INNER JOIN
				`store_gcrequest`
			ON
				`gc_release`.`rel_storegcreq_id`=`store_gcrequest`.`sgc_id`
			WHERE 
				`gc_location`.`loc_store_id`='$storeid'

		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else
		{
			return $rows = $link->error;
		}

	}

	function soldGC($link,$storeid)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				DISTINCT
				`store_verification`.`vs_barcode`, 
				`store_received_gc`.`strec_barcode`,
				`denomination`.`denomination`,				
				`store_verification`.`vs_date`,
				`store_received_gc`.`strec_recnum`,
				`transaction_stores`.`trans_number`,
				`transaction_stores`.`trans_type`,
				`transaction_stores`.`trans_datetime`,
				`stores`.`store_name`
			FROM 
				`store_received_gc`
			INNER JOIN
				`denomination`
			ON
				`store_received_gc`.`strec_denom` = `denomination`.`denom_id`
			INNER JOIN
				`transaction_sales`
			ON
				`transaction_sales`.`sales_barcode` = `store_received_gc`.`strec_barcode`
			INNER JOIN
				`transaction_stores`
			ON
				`transaction_stores`.`trans_sid` = `transaction_sales`.`sales_transaction_id`
			LEFT JOIN
				`store_verification`
			ON
				`store_received_gc`.`strec_barcode` = `store_verification`.`vs_barcode`
			LEFT JOIN
				`stores`
			ON
				`stores`.`store_id` = `store_verification`.`vs_store`
			WHERE 
				`store_received_gc`.`strec_sold`='*'
			AND
				`store_received_gc`.`strec_return`=''
			AND
				`store_received_gc`.`strec_storeid`='$storeid'
			AND
				`transaction_sales`.`sales_item_status`='0'
			GROUP BY 
				`store_received_gc`.`strec_barcode`
			ORDER BY `transaction_stores`.`trans_datetime` DESC
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else
		{
			return $rows = $link->error;
		}
	}

	function statusReleased($link,$gc)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				* 
			FROM 
				`gc_release`
			INNER JOIN
				`users`
			ON
			`gc_release`.`rel_by` =`users`.`user_id`
			WHERE 
				`gc_release`.`re_barcode_no`='$gc'
			ORDER BY 
				`rel_id`
			DESC
			LIMIT 1
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}

			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function statusLocation($link,$gc)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				* 
			FROM 
				`gc_location` 
			INNER JOIN
				`stores`
			ON
				`gc_location`.`loc_store_id` = `stores`.`store_id`
			INNER JOIN
				`users`
			ON
				`gc_location`.`loc_by` = `users`.`user_id`
			WHERE 
				`loc_barcode_no`='$gc'
			ORDER BY 
				`loc_id`
			DESC
			LIMIT 1
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}

			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function statusSold($link,$gc)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				* 
			FROM 
				`transaction_sales`
			INNER JOIN
				`transaction_stores`
			ON
				`transaction_sales`.`sales_transaction_id` = `transaction_stores`.`trans_sid`
			WHERE
				`transaction_sales`.`sales_barcode`='$gc'
			ORDER BY
				`sales_id`
			DESC
			LIMIT 1
		");
		if($query)
		{
			while($row = $query->fetch_object())
			{
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows = $link->error;
		}
	}

	function statusGCGenerated($link,$gc)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				* 
			FROM 
				`gc`
			INNER JOIN
				`denomination`
			ON 
				`gc`.`denom_id` = `denomination`.`denom_id` 
			WHERE 
				`barcode_no`='$gc'
		");

		if($query)
		{
			while($row = $query->fetch_object())
			{
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows = $link->error;
		}
	}

	function getCustomerDetails($link)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				* 
			FROM 
				`customers`
			ORDER BY 
				`cus_id`
			DESC
		");

		if($query)
		{
			while($row = $query->fetch_object())
			{
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows = $link->error;
		}
	}

	function updateProductionStatus($link,$id)
	{
		$query = $link->query(
			"UPDATE 
				`production_request` 
			SET 
				`pe_status`='2' 
			WHERE 
				`pe_id`='$id'
		");

		if($query)
		{
			return true;
		}
	}

	function cancelRequisition($link,$id)
	{
		$query = $link->query(
			"INSERT INTO 
				`cancelled_production_request`
			(
				`cpr_pro_id`, 
				`cpr_at`, 
				`cpr_by`
			) 
			VALUES 
			(
				'$id',
				NOW(),
				'".$_SESSION['gc_id']."'
				
			)
		");

		if($query)
		{
			return true;
		}
	}

	function getProductionBudget($link,$id)
	{
		$total = 0;
		$query = $link->query(
			"SELECT 
				* 
			FROM 
				`production_request_items` 
			INNER JOIN
				`denomination`
			ON
				`production_request_items`.`pe_items_denomination`=`denomination`.`denom_id`
			WHERE 
				`pe_items_request_id`='$id'
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$sub = $row->pe_items_quantity * $row->denomination;
				$total = $total + $sub;
			}

			return $total;
		}
	}

	function deleteGCBarcode($link,$id)
	{
		$query = $link->query(
			"DELETE 
			FROM 
				`gc` 
			WHERE 
				`pe_entry_gc`='$id';
		");

		if($query)
		{
			return true;
		}
	}

	function tagCancelledGC($link,$id)
	{
		$query = $link->query(
			"UPDATE 
				`gc` 
			SET 
				`gc_cancelled`='*' 
			WHERE
				`pe_entry_gc`='$id' 
		");
		if($query)
		{
			return true;
		}
		else 
		{
			echo $link->error;
		}
	}

	function ledgerBudgetEntry($link,$id)
	{
		$query = $link->query(
			"INSERT INTO 
				`ledger_budget`
				(	
					`bledger_id`, 
					`bledger_no`, 
					`bledger_datetime`, 
					`bledger_type`, 
					`bdebit_amt`, 
					`bcredit_amt`, 
					`btag`
				) 
			VALUES 
				(
					[value-1],
					[value-2],
					[value-3],
					[value-4],
					[value-5],
					[value-6],
					[value-7]
				)
		");

	}

	function productionEntry($link,$budget)
	{
		$query = $link->query(
			"INSERT INTO 
				`entry_production`
			(
				`ep_title`,
				`ep_date`, 
				`ep_time`,
				`ep_type`, 
				`ep_amount` 
			) 
			VALUES 
			(
				'CE',
				NOW(),
				NOW(),
				'CE',
				'$budget'
			)
		");

		if($query)
		{
			return true;
		}
		else 
		{
			return $link->error;
		}
	}

	function getLastNo($link)
	{
		$query = $link->query(
			"SELECT 
				`ep_no` 
			FROM 
				`entry_production` 
			ORDER BY 
				`ep_no` 
			DESC LIMIT 1
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->ep_no;
		}
	}

	function budgetLedger($link,$lastno,$budget,$bledger_type,$cd)
	{
		$query = $link->query(
			"INSERT INTO 
				`ledger_budget`
			(
				`bledger_no`, 
				`bledger_datetime`, 
				`bledger_type`,  
				$cd
			) 
			VALUES 
			(
				'$lastno',
				NOW(),
				'$bledger_type',
				'$budget'
			)
		");

		if($query)
		{
			return true;
		}

	}

	function approvedProductionRequest($link)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				`production_request`.`pe_id`,
				`production_request`.`pe_num`,
				`production_request`.`pe_date_request`,
				`production_request`.`pe_date_needed`,
				`approved_production_request`.`ape_approved_at`,
				`approved_production_request`.`ape_approved_by`,
				`userequest`.`firstname`,
				`userequest`.`lastname`
			FROM 
				`production_request`
			INNER JOIN 
				`approved_production_request`
			ON 
				`production_request`.`pe_id` = `approved_production_request`.`ape_pro_request_id`
			INNER JOIN
				`users` as `userequest`
			ON
				`userequest`.`user_id` = `production_request`.`pe_requested_by`
			WHERE 
				`pe_status`='1'
			ORDER BY 
				`production_request`.`pe_id`
			DESC
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			$row = $link->error;
		}

	}

	function getBudgetLedger($link)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				ledger_budget.bledger_id,
				ledger_budget.bledger_no,
				ledger_budget.bledger_trid,
				ledger_budget.bledger_datetime,
				ledger_budget.bledger_type,
				ledger_budget.bdebit_amt,
				ledger_budget.bcredit_amt
			FROM 
				ledger_budget 
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else 
			return $rows[] = $link->error;
	}

	function getStoreStaff($link,$store)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				*
			FROM
				`store_staff`
			WHERE 
				`ss_store`='$store'
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else
		{
			return $rows[] = $link->error;
		}
	}

	function getProductionByDenom($link,$den_id)
	{
		$query = $link->query(
			"SELECT 
				*
			FROM
				`gc`
			WHERE 
				`denom_id`='$den_id'
			AND 
				`gc_validated`=''

		");

		if($query)
		{
			$n = $query->num_rows;
			return $n;
		} 
		else 
		{
			return $link->error;
		}
	}

	function adjInsertDeleteBarcode($link,$barcode,$den_id,$last_id){
		$query_insert = $link->query(
			"INSERT INTO 
				`gc_adjustment_items`
			(
				`gc_adji_ids`, 
				`gc_adji_den`,
				`gc_adji_barcode`
			) 
			VALUES 
			(
				'$last_id',
				'$den_id',
				'$barcode'
			)
		");

		if($query_insert)
		{
			$query_del = $link->query(
				"DELETE 
				FROM 
					`gc` 
				WHERE 
					`barcode_no`='$barcode' 
			");
		}	
	}

	function adjPositive($link,$den_id,$last_id,$qty)
	{
		$query_getgc = $link->query("SELECT * FROM `gc` WHERE `denom_id`='$den_id' ORDER BY `barcode_no` DESC LIMIT 1");
		$row = $query_getgc->fetch_object();
		$barcode_no = $row->barcode_no;
		$proid = $row->pe_entry_gc;	
		generateBarcodeNo($link,$barcode_no,$qty,$den_id,$last_id,$proid);	
	}

	function generateBarcodeNo($link,$barcode_no,$qty,$den_id,$last_id,$proid)
	{
		for($m=1 ; $m<=$qty ; $m++)
		{						
				$barcode_no++;
				adjInsertItems($link,$barcode_no,$den_id,$last_id);		
				$link->query(
					"INSERT INTO 
						`gc`
					(
						`barcode_no`, 
						`denom_id`, 
						`date`, 
						`time`, 
						`pe_entry_gc`,
						`gc_postedby`
					) 
					VALUES 
					(
						'$barcode_no',
						'$den_id',
						NOW(),
						NOW(),
						'$proid',
						'".$_SESSION['gc_id']."'
					)
				");
		}
	}

	function adjInsertItems($link,$barcode_no,$den_id,$last_id)
	{
		$link->query(
			"INSERT INTO 
				`gc_adjustment_items`
			(
				`gc_adji_ids`, 
				`gc_adji_den`, 
				`gc_adji_barcode`
			) 
			VALUES 
			(
				'$last_id',
				'$den_id',
				'$barcode_no'
			)
		");
	}



	function ledgerInsert($link,$last_insert,$ledgertype,$amt_field,$amt)
	{
		$query = $link->query(
			"INSERT INTO 
				`ledger_budget`
			(
				`bledger_no`, 
				`bledger_datetime`, 
				`bledger_type`, 
				$amt_field 
			) 
				VALUES 
			(
				'$last_insert',
				NOW(),
				'$ledgertype',
				'$amt'
			)
		");

		if($query)
		{
			return true;
		}
		else
		{
			return $link->error;
		}
	}

	function gcAdjustmentInsert($link,$adj,$remarks)
	{
			$query = $link->query(
				"INSERT INTO 
					`gc_adjustment`
				(
					`gc_adj_type`,
					`gc_adj_remarks`, 
					`gc_adj_datetime`, 
					`gc_adj_by`
				) 
				VALUES 
				(
					'$adj',
					'$remarks',
					NOW(),
					'".$_SESSION['gc_id']."'
				)
			");

			if($query)
			{
				return $last_insert = $link->insert_id;
			}
	}

	function countAdj($link,$table)
	{
		$query = $link->query(
			"SELECT 
				* 
			FROM 
				$table
		");

		if($query)
		{
			$n = $query->num_rows;
			return $n;
		}
	}

	function getAdjBudget($link)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				budget_adjustment.bud_adj_type,
				budget_adjustment.bud_remark,
				ledger_budget.bledger_datetime,
				ledger_budget.bdebit_amt,
				ledger_budget.bcredit_amt,
				CONCAT(users.firstname,' ',users.lastname) as prepby
			FROM 
				ledger_budget
			INNER JOIN
				budget_adjustment
			ON
				budget_adjustment.bud_id = ledger_budget.bledger_trid
			INNER JOIN
				users
			ON
				users.user_id = budget_adjustment.bud_by
			WHERE 
				ledger_budget.bledger_type='BA'
		");
		
		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else
		{
			return $rows[] = $link->error;
		}
	}

	function getStoreUser($link,$id)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				*
			FROM
				`store_staff`
			WHERE
				`ss_id`='$id'
			LIMIT 1
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row; 
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function checkUsername($link,$username,$store)
	{
		$query = $link->query(
			"SELECT 
				`ss_username`
			FROM
				`store_staff`
			WHERE
				`ss_username`='$username'
			AND
				`ss_store`='$store'
		");

		if($query)
		{
			if(($n = $query->num_rows)>0)
			{
				return false;
			}
			else 
			{
				return true;
			}
		}
	}

	function getDefaultPassword($link,$store)
	{
		$query = $link->query(
			"SELECT 
				`default_password`
			FROM
				`stores`
			WHERE
				`store_id`='$store'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->default_password;
		}
	}

	function usernameExistOnUpdate($link,$id,$uname)
	{
		$query = $link->query(
			"SELECT 
				`ss_username` 
			FROM 
				`store_staff` 
			WHERE 
				`ss_username`='$uname'
			AND
				`ss_id`!='$id'
		");

		if($query)
		{
			if(($n = $query->num_rows)>0)
			{
				return true;
			} 
			else 
			{
				return false;
			}
		}
		else
		{
			return $link->error;
		}
	}

	function getRequis($link)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				`production_request`.`pe_num`,
				`production_request`.`pe_date_request`,
				`requisition_entry`.`requis_rmno`,
				`requisition_entry`.`requis_status`,
				`production_request`.`pe_requisition`,
				`requisition_entry`.`requis_id`,
				`requisition_entry`.`requis_erno`,
				`supplier`.`gcs_companyname`
			FROM 
				`approved_production_request` 
			INNER JOIN
				`production_request`
			ON
				`approved_production_request`.`ape_pro_request_id` = `production_request`.`pe_id`
			INNER JOIN
				`requisition_entry`
			ON
				`production_request`.`pe_id` = `requisition_entry`.`repuis_pro_id`
			INNER JOIN
				`supplier`
			ON
				`supplier`.`gcs_id` = `requisition_entry`.`requis_supplierid`
			ORDER BY
				`approved_production_request`.`ape_id`
			DESC
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else
		{
			return $rows[] = $link->error;
		}
	}

	function checkProductionNo($link,$id)
	{
		if($id!='')
		{
			$n = numRows($link,'approved_production_request','ape_pro_request_id',$id);			
			if($n>0)
			{
				return true;
			}
			else 
			{
				return false;
			}
		} 
		else 
		{
			return false;
		}
	}

	function checkIfSRReceived($link,$id)
	{
		$query = $link->query(
			"SELECT 
				* 
			FROM 
				`approved_production_request` 
			INNER JOIN
				`production_request`
			ON
				`approved_production_request`.`ape_pro_request_id` = `production_request`.`pe_id`
			WHERE 
				`production_request`.`pe_requisition`='1'
			AND
				`approved_production_request`.`ape_received`=''
			AND 
				`approved_production_request`.`ape_pro_request_id`='$id'
		");

		if($query)
		{
			$n = $query->num_rows;
			if($n>0)
			{
				return true;
			}
			else 
			{
				return false;
			}
		}
	}

	function getProductionDetails($link,$id)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				* 
			FROM 
				`production_request` 
			INNER JOIN
				`requisition_entry`
			ON
				`production_request`.`pe_id` = `requisition_entry`.`repuis_pro_id`
			INNER JOIN
				`approved_production_request`
			ON
				`production_request`.`pe_id`=`approved_production_request`.`ape_pro_request_id`
			INNER JOIN
				`supplier`
			ON
				`requisition_entry`.`requis_supplierid` = `supplier`.`gcs_id`
			WHERE 
				`production_request`.`pe_id`='$id'
			LIMIT 1
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row; 
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}

	}

	function numRowsForGCValidation($link,$den_id,$id)
	{
		$query = $link->query(
			"SELECT 
				* 
			FROM 
				`gc` 
			WHERE 
				`gc_validated`=''
			AND
				`denom_id`='$den_id'
			AND
				`pe_entry_gc`='$id'
		");

		if($query)
		{
			$n = $query->num_rows;
			return $n;
		}
		else 
		{
			return $link->error;
		}
	}

	function getReceivedDetails($link,$proid)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				* 
			FROM
				`custodian_srr`
			INNER JOIN
				`production_request`
			ON
				`custodian_srr`.`csrr_pro_id` = `production_request`.`pe_id`
			WHERE
				`custodian_srr`.`csrr_pro_id`='$proid'
			LIMIT 1

		");

		if($query)
		{
			while($row = $query->fetch_object())
			{
				$rows[] = $row;				
			}
			return $rows;
		}
		else 
		{
			$rows[] = $link->query;
		}

	}

	function checkIfReceived($link,$id)
	{
		$query = $link->query(
			"SELECT * FROM `custodian_srr` WHERE `csrr_id`='$id'
		");

		if($query)
		{
			$n = $query->num_rows;
			return $n;
		}
	}

	function  getReportDetails($link,$id)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				* 
			FROM 
				`production_request` 
			INNER JOIN
				`requisition_entry`
			ON
				`production_request`.`pe_id` = `requisition_entry`.`repuis_pro_id`
			INNER JOIN
				`custodian_srr`
			ON
				`production_request`.`pe_id` = `custodian_srr`.`csrr_pro_id`
			INNER JOIN
				`supplier`
			ON
				`requisition_entry`.`requis_supplierid` = `supplier`.`gcs_id`
			INNER JOIN
				`users`
			ON
			`custodian_srr`.`csrr_prepared_by` = `users`.`user_id`
			WHERE 
				`custodian_srr`.`csrr_id`= '$id'
			LIMIT 1
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function getValidatedDenom($link,$id)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				`gc`.`denom_id`,
				`denomination`.`denomination`
			FROM 
				`gc` 
			INNER JOIN
				`denomination`
			ON
				`gc`.`denom_id` = `denomination`.`denom_id`
			WHERE
				`gc`.`pe_entry_gc`='$id'
			AND
				`gc`.`gc_validated`='*'
			GROUP BY
				`gc`.`denom_id`
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function getValidatedGC($link,$den,$id)
	{
		$rows = [];
		$query = $link->query(
			"SELECT
				`gc`.`barcode_no`
			FROM 
				`gc` 
			WHERE
				`pe_entry_gc`='$id'
			AND
				`gc_validated`='*'
			AND
				`denom_id`='$den'
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function getSRRForReport($link)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				* 
			FROM 
				`custodian_srr` 
			INNER JOIN
				`production_request`
			ON
				`custodian_srr`.`csrr_pro_id` = `production_request`.`pe_id`
			INNER JOIN
				`users`
			ON
				`custodian_srr`.`csrr_prepared_by` = `users`.`user_id`
			ORDER BY
				`csrr_id`
			Desc
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function getUsers($link)
	{
		$rows = array();

		$query = $link->query(
			"SELECT
                users.user_id,
                users.emp_id,
                users.username,
                users.firstname,
                users.lastname,
                users.login,
                users.user_status,
                users.date_created,
                access_page.title,              
                stores.store_name
            FROM
                users
            INNER JOIN
                access_page
            ON
                users.usertype = access_page.access_no
            LEFT JOIN
                stores
            ON
                users.store_assigned = stores.store_id 
            ORDER BY 
                users.user_id
			DESC
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else
		{
			return $rows[] = $link->error;
		}
	}

	function getUser($link,$id)
	{
		$rows = array();

		$query = $link->query(
			"SELECT
                users.user_id,
                users.emp_id,
                users.username,
                users.firstname,
                users.lastname,
                users.login,
                users.usertype,
                users.user_status,
                users.date_created,
                users.user_role,
                access_page.title,
                access_page.access_no,              
                stores.store_name,
                stores.store_id,
                users.usergroup
            FROM
                users
            INNER JOIN
                access_page
            ON
                users.usertype = access_page.access_no
            LEFT JOIN
                stores
            ON
                users.store_assigned = stores.store_id
            WHERE 
                users.user_id = '$id' 
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else
		{
			return $rows[] = $link->error;
		}

	}

	function getAccessPage($link)
	{
		$rows = [];

		$query = $link->query(
			"SELECT 
				`access_no`, 
				`title`
			FROM 
				`access_page`
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function getStores($link)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				store_id, 
				store_name,
				default_password,
				company_code,
				store_code,
				issuereceipt 
			FROM 
				stores
			WHERE
				store_status='active'
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->query;
		}
	}

	function getAppPasswordmd5($link)
	{
		$query = $link->query(
			"SELECT 
				app_settingvalue 
			FROM 
				app_settings 
			WHERE 
				app_tablename='system_default_pass'
			ORDER BY 
				app_id
			ASC
			LIMIT 1
		");

		if($query)
		{
			$row = $query->fetch_object();
			return md5($row->app_settingvalue);
		}
		else 
		{
			return $link->error;
		}
	}

	function getDenomination($link)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				`denom_id`,
				`denomination`
			FROM 
				`denomination`
			ORDER BY
				`denom_id`
			ASC
		");
		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}
		}	return $rows;
		// else 
		// {
		// 	return $rows[] = $link->error;
		// }		
	}

	function getStoreName($link,$store_id)
	{
		if($store_id=='0')
		{
			return 'All Stores';
		}
		else 
		{
			$query=$link->query(
				"SELECT 
					`store_name` 
				FROM 
					`stores` 
				WHERE 
					`store_id` = '$store_id'
			");

			$row = $query->fetch_object();

			return $row->store_name;
		}
	}

	function getReportData($link,$store,$den,$datestart,$dateend)
	{
		if($store==0)
		{
			$storeQuery = "";
		}
		else 
		{
			$storeQuery = "AND `transaction_stores`.`trans_store` = '".$store."'";
		}
		$datestart =  _dateFormatoSql($datestart).' 08:00:00';
		$dateend = _dateFormatoSql($dateend).' 17:00:00';
		$rows = [];
		$query = $link->query(
			"SELECT 
				`transaction_stores`.`trans_sid`,
				`transaction_stores`.`trans_number`
			FROM 
			`transaction_stores` 
			WHERE  
			(
			    `transaction_stores`.`trans_datetime` 
			    BETWEEN 
			    '$datestart' 
			    AND 
			    '$dateend'
			)
			".$storeQuery."
			ORDER BY 
				`transaction_stores`.`trans_datetime`
			DESC
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function getSalesFromStores($link,$store,$den,$datestart,$dateend)
	{
		if($store==0)
		{
			$storeQuery = "";
		}
		else 
		{
			$storeQuery = "AND `transaction_stores`.`trans_store` = '".$store."'";
		}

		if($den==0)
		{
			$denQuery = "";
		}
		else 
		{
			$denQuery = "AND `transaction_sales`.`sales_denomination`='".$den."'";
		}
		$datestart =  _dateFormatoSql($datestart).' 08:00:00';
		$dateend = _dateFormatoSql($dateend).' 17:00:00';
		$rows = [];
		$query = $link->query(
			"SELECT 
				`transaction_sales`.`sales_id`,
				`transaction_sales`.`sales_barcode`,
				`transaction_stores`.`trans_datetime`,
				`store_staff`.`ss_firstname`,
				`store_staff`.`ss_lastname`,
				`denomination`.`denomination`,
				`stores`.`store_name`
			FROM 
				`transaction_sales` 
			INNER JOIN
				`transaction_stores`
			ON
				`transaction_sales`.`sales_transaction_id` = `transaction_stores`.`trans_sid`
			INNER JOIN
				`store_staff`
			ON
				`transaction_stores`.`trans_cashier` = `store_staff`.`ss_id`
			INNER JOIN
				`stores`
			ON
				`transaction_stores`.`trans_store` = `stores`.`store_id`
			INNER JOIN
				`denomination`
			ON
				`transaction_sales`.`sales_denomination` = `denomination`.`denom_id`
			WHERE
			(
			    `transaction_stores`.`trans_datetime` 
			    BETWEEN 
			    '$datestart' 
			    AND 
			    '$dateend'
			)
			".$storeQuery.$denQuery."
			ORDER BY 
				`transaction_stores`.`trans_datetime`
			DESC
		");

		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function getDenominationForReports($link,$den)
	{
		if($den=='0')
		{
			return 'All Denominations';
		}
		else 
		{
			$query = $link->query(
				"SELECT 
					`denomination` 
				FROM 
					`denomination` 
				WHERE 
					`denom_id`='$den'
			");

			$row = $query->fetch_object();

			return number_format($row->denomination);
		}	
	}

	function getVerifiedGC($link,$storeid,$denom,$start,$end)
	{
		$start = _dateFormatoSql($start);
		$end = _dateFormatoSql($end);
		$rows = [];
		if($denom==0)
		{
			$denquery='';
		}
		else 
		{
			$denquery = "AND `denomination`.`denom_id`='".$denom."'";
		}
		
		$query = $link->query(
			"SELECT 
			    `store_verification`.`vs_barcode`,
			    `store_verification`.`vs_by`,
			    `store_verification`.`vs_date`,
			    `store_verification`.`vs_time`,
			    `gc_location`.`loc_store_id`,
			    `customers`.`cus_fname`,
			    `customers`.`cus_lname`,
			    `gc`.`denom_id`,
				`denomination`.`denomination`,
			    `users`.`firstname`,
			    `users`.`lastname`
			FROM 
				`store_verification`
			INNER JOIN
				`gc_location`
			ON
				`store_verification`.`vs_barcode` = `gc_location`.`loc_barcode_no`
			INNER JOIN
				`customers`
			ON
				`store_verification`.`vs_cn` = `customers`.`cus_id`
			INNER JOIN
				`gc`
			ON
				`store_verification`.`vs_barcode`=`gc`.`barcode_no`
			INNER JOIN
				`users`
			ON
				`store_verification`.`vs_by` = `users`.`user_id`
			INNER JOIN
				`stores`
			ON
				`gc_location`.`loc_store_id` = `stores`.`store_id`
			INNER JOIN
				`denomination`
			ON
				`gc`.`denom_id` = `denomination`.`denom_id`
			WHERE
				`gc_location`.`loc_store_id`='$storeid'
			AND
			(
			    `vs_date` 
			    BETWEEN 
			    '$start' 
			    AND 
			    '$end'
			)
			$denquery	
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}

			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function getUserData($link,$userid)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				`users`.`emp_id`,
				`users`.`username`,
				`users`.`firstname`,
				`users`.`lastname`,
				`users`.`date_created`,
				`access_page`.`title`,
				`stores`.`store_name`,
				`users`.`usertype`
			FROM 
				`users`
			INNER JOIN
				`access_page`
			ON
				`users`.`usertype` = `access_page`.`access_no`
			LEFT JOIN
				`stores`
			ON
				`users`.`store_assigned` = `stores`.`store_id`
			WHERE 
				`user_id`='$userid'
			LIMIT 1
		");

		if($query)
		{
			return $rows[] = $query->fetch_object();
		}
		else 
		{
			return $row[] = $link->error;
		}
	}

	function receivedDetails($link,$id)
	{
		$rows = [];
		$query = $link->query(
			"SELECT
				`store_received`.`srec_at`,
				`users`.`firstname`,
				`users`.`lastname`
			FROM 
				`store_received` 
			INNER JOIN
				`users`
			ON
				`store_received`.`srec_by` = `users`.`user_id`
			WHERE 
				`store_received`.`srec_request_id`='$id'
			LIMIT 1
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}			
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function checkFolder($folder)
	{
		if(file_exists($folder))
		return true;
	}

	function deleteImages($folder)
	{
		$extension = array('jpg','jpeg');
		$files = scandir($folder);
		foreach ($files as $file) 
		{
			if(in_array(pathinfo($file,PATHINFO_EXTENSION), $extension))
				unlink("{$folder}/{$file}");
		}
		// $images = scandir()($folder."/*.jpg");
		// foreach($images as $image){
		//      @unlink($image);
		// }
	}

	function deleteTexfiles($folder)
	{
		$extension = array('igc','egc');
		$files = scandir($folder);
		foreach ($files as $file) 
		{
			if(in_array(pathinfo($file,PATHINFO_EXTENSION), $extension))
				unlink("{$folder}/{$file}");
		}
	}

	function deleteReports($folder)
	{
		$extension = array('pdf');
		$files = scandir($folder);
		foreach ($files as $file) 
		{
			if(in_array(pathinfo($file,PATHINFO_EXTENSION), $extension))
				unlink("{$folder}/{$file}");
		}		
	}


	function insertStoreLedger($link,$id,$type)
	{
		if($type==1)
		{
			$totalGCAmount = $link->query(
				"SELECT 
					SUM(denomination.denomination) AS totalGC
				FROM 
					`store_request_items`
				INNER JOIN
					`denomination`
				ON
					`store_request_items`.`sri_items_denomination` = `denomination`.`denom_id`
				WHERE 
					`store_request_items`.`sri_items_requestid`='$id'
			");

			$row = $totalGCAmount->fetch_object();
			$totalGCAmount = $row->totalGC;
			$entryType = 'sledger_debit';
			$trans = "GCE";
			$desc = "Gift Check Entry";
			$user_id = $_SESSION['gc_id'];
			$store = getStoreAssigned($link,$user_id);
		}
		elseif ($type==2) 
		{
			$totalGCAmount = $link->query(
				"SELECT 
					SUM(`denomination`.`denomination`) AS amountDue 
				FROM 
					`transaction_sales` 
				INNER JOIN
					`denomination`
				ON
					`transaction_sales`.`sales_denomination` = `denomination`.`denom_id`
				WHERE
					`transaction_sales`.`sales_transaction_id`='$id'

			");
			$row = $totalGCAmount->fetch_object();
			$totalGCAmount = $row->amountDue;
			$entryType = 'sledger_credit';
			$trans = "GCS";
			$desc = "Gift Check Sales";
			$store = $_SESSION['gccashier_store'];
		}
		elseif($type==3)
		{
			$query = $link->query("SELECT 
				`denomination`.`denomination`
			FROM 
				`gc_return` 
			INNER JOIN
				`denomination`
			ON
				`gc_return`.`rr_denom_id` = `denomination`.`denom_id`
			WHERE 
				`gc_return`.`rr_id`='$id'				
			");

			$row = $query->fetch_object();
			$totalGCAmount = $row->denomination;
			$entryType = 'sledger_debit';
			$trans = "GCR";
			$desc = "Gift Check Return";
			$store = $_SESSION['gc_store'];	

		}

		$insertData = $link->query(
			"INSERT INTO 
				`ledger_store`
			(				
				`sledger_date`, 
				`sledger_ref`, 
				`sledger_trans`, 
				`sledger_desc`, 
				$entryType,
				`sledger_store` 
			) 
			VALUES 
			(
				NOW(),
				'$id',
				'$trans',
				'$desc',
				'$totalGCAmount',
				'$store'
			)
		");

		if($insertData)
		{

		}
		else 
		{
			echo $link->error;
		}
	}

	function getStoreAssigned($link,$id)
	{
		$query = $link->query(
			"SELECT 
				`store_assigned` 
			FROM 
				`users` 
			WHERE 
				`user_id`='$id'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->store_assigned;
		}
	}

	function getLedgerData($link,$storeid)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				ledger_store.sledger_trans, 
				ledger_store.sledger_desc,
				ledger_store.sledger_debit,
				ledger_store.sledger_credit,
				ledger_store.sledger_date,
				ledger_store.sledger_id,
				ledger_store.sledger_no,
				ledger_store.sledger_ref,
				ledger_store.sledger_trans_disc,
				ledger_store.sledger_ref,
				ledger_store.sledger_id
			FROM 
				ledger_store 
			WHERE
				ledger_store.sledger_store = '$storeid'
			AND
			(
				ledger_store.sledger_trans='GCE'
			OR
				ledger_store.sledger_trans='GCS'
			OR 
				ledger_store.sledger_trans='GCREF'
			OR 
				ledger_store.sledger_trans='GCTOUT'
			)
			ORDER BY
				ledger_store.sledger_id
			ASC				
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}

	}

	function storeReceivedDetails($link,$relid)
	{
		$query = $link->query(
			"SELECT
				store_received.srec_recid,
				store_received.srec_at,
				users.firstname,
				users.lastname
			FROM 
				store_received 
			INNER JOIN
				users
			ON
				users.user_id = store_received.srec_by
			WHERE 
				srec_rel_id='$relid'
		");

		if($query)
		{	
			$row = $query->fetch_object();
			return $row;
		}
		else 
		{
			return $link->error;
		}
	}

	function getManualNumber($link,$id)
	{
		$query = $link->query(
			"SELECT 
				`requis_rmno` 
			FROM 
				`requisition_entry` 
			WHERE 
				`requis_id` = '$id'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->requis_rmno;
		}
	}

	function getEReq($link,$id)
	{
		$query = $link->query(
			"SELECT 
				`requis_erno`,
				`requis_id`,
				`repuis_pro_id` 
			FROM 
				`requisition_entry` 
			WHERE 
				`requis_id` = '$id'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row;
		}
	}

	function getEreqID($link,$reqnum)
	{
		$query = $link->query(
			"SELECT 
				requis_erno,
				requis_id,
				repuis_pro_id 
			FROM 
				requisition_entry 
			WHERE 
				requis_erno = '$reqnum'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row;
		}		

	}

	function getReceivingNumber($link,$field,$table)
	{
		$query = $link->query(
			"SELECT 
				$field
			FROM 
				$table
			ORDER BY 
				$field
			DESC  
		");

		if($query)
		{
			
			if($query->num_rows>0)
			{
				$row = $query->fetch_object();
				return $row->$field+1;
			}
			else 
			{
				return "1";
			}
		}
	}

	function currentValidatedGC($link,$recnum,$denom){
		$query = $link->query(
			"SELECT 
				`gc`.`denom_id`
			FROM 
				`custodian_srr_items`
			INNER JOIN
				`gc`
			ON
				`gc`.`barcode_no` = `custodian_srr_items`.`cssitem_barcode`
			WHERE
				`custodian_srr_items`.`cssitem_recnum` = '$recnum'
			AND
				`gc`.`denom_id` = '$denom'
		");

		if($query)
		{
			return $query->num_rows;
		} 
		else 
		{
			return $link->error;
		}

	}

	function getNumberofGCReceive($link,$den,$recnum)
	{
		if($den==1)
		{
			$denomfield = 'srr_num_d1';
		}
		elseif ($den==2) {
			$denomfield = 'srr_num_d2';
		}
		elseif ($den==3) {
			$denomfield = 'srr_num_d3';
		}
		elseif ($den==4) {
			$denomfield = 'srr_num_d4';
		}
		elseif ($den==5) {
			$denomfield = 'srr_num_d5';
		}
		elseif ($den==6) {
			$denomfield = 'srr_num_d6';
		}
		$query = $link->query(
			"SELECT 
				$denomfield 
			FROM 
				`custodian_srr_numgc`
			WHERE
				 `srr_num_ref`='$recnum'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->$denomfield;
		}
	}

	function checkIFcoincides($link,$recnum)
	{
		$coincide = true;

		$denom = array('1','2','3','4','5','6');

		foreach ($denom as $d) {
			$gcvalidated = currentValidatedGC($link,$recnum,$d);
			$numgcreceived = getNumberofGCReceive($link,$d,$recnum);

			if($gcvalidated!=$numgcreceived)
			{
				$coincide = false;
				break;
			}
		}

		return $coincide;
	}

	function checkRecivedStat($link,$recnum)
	{
		$query = $link->query(
			"SELECT 
				`srr_num_ref` 
			FROM 
				`custodian_srr_numgc` 
			WHERE 
				`srr_num_ref` = '$recnum'
			AND 
				`srr_num_stat` = '1'
		");

		if($query)
		{
			if(($query->num_rows) > 0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}


	}

	function gcstorerequestList($link)
	{
		$rows = [];
	    $query = $link->query(
	        "SELECT
			    `store_gcrequest`.`sgc_id`,
			    `store_gcrequest`.`sgc_num`,
			    `store_gcrequest`.`sgc_date_needed`,
			    `store_gcrequest`.`sgc_date_request`,
			    `store_gcrequest`.`sgc_status`,
			    `stores`.`store_name`,
				`users`.`firstname`,
				`users`.`lastname`
			FROM 
				`store_gcrequest`
			INNER JOIN
				`stores`
			ON
				`store_gcrequest`.`sgc_store` = `stores`.`store_id`
			INNER JOIN
				`users`
			ON
				`users`.`user_id` = `store_gcrequest`.`sgc_requested_by`
			WHERE
				`store_gcrequest`.`sgc_status` = 1
			OR
				`store_gcrequest`.`sgc_status` = 0
			AND
				`store_gcrequest`.`sgc_cancel`=''
			ORDER BY
				`store_gcrequest`.`sgc_id`
			DESC
	    ");

	    if($query)
	    {
		    while ($row = $query->fetch_object()) {
		    	$rows[] = $row;
		    }
		    return $rows;
	    }
	    else 
	    {
	    	return $rows[] = $link->error;
	    }
	}

	function getStoreRequestDetails($link,$id)
	{
		$query = $link->query(
			"SELECT 
			    store_gcrequest.sgc_id,
			    store_gcrequest.sgc_num,
			    store_gcrequest.sgc_date_request,
			    store_gcrequest.sgc_date_needed,
			    store_gcrequest.sgc_file_docno,
			    store_gcrequest.sgc_remarks,
			    store_gcrequest.sgc_store,
			    store_gcrequest.sgc_type,
			    stores.store_name,
				users.firstname,
				users.lastname
			FROM
				store_gcrequest
			INNER JOIN 
				stores
			ON
				store_gcrequest.sgc_store = stores.store_id
			INNER JOIN
				users
			ON
				users.user_id = store_gcrequest.sgc_requested_by
			WHERE
				store_gcrequest.sgc_id='$id'
			LIMIT 1
			");
		if($query){
			$row = $query->fetch_object();
			return $row;
		} else {
			return $link->error;
		}
	}

	function checkIfExistNlocation($link,$barcode,$denomID,$storeid)
	{
		$query = $link->query(
			"SELECT 
				`loc_barcode_no` 
			FROM 
				`gc_location`
			INNER JOIN
				`gc`
			ON
				`gc`.`barcode_no` = `gc_location`.`loc_barcode_no`
			INNER JOIN
				`denomination`
			ON
				`denomination`.`denom_id` = `gc`.`denom_id`
			WHERE 
				`loc_barcode_no` = '$barcode'
			AND
				`gc`.`denom_id` = '$denomID'
			AND	
				`gc_location`.`loc_store_id` = '$storeid'
		");

		if($query)
		{
			if(($query->num_rows)>0)
			{
				return true;
			}
			else 
			{
				return false;
			}
		}
		else 
		{
			return $link->error;
		}

	}
	function getDenominationIDByBarcode($link,$barcode)
	{
		$query = $link->query(
			"SELECT 
				`denom_id` 
			FROM 
				`gc` 
			WHERE 
				`barcode_no` = '$barcode' 
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->denom_id;
		}
		else
		{
			return $link->error;
		}
	}

	function getRemainingGCtoRelease($link,$id)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
			  	store_request_items.sri_items_remain,
			  	store_request_items.sri_items_denomination,
			  	denomination.denomination
			FROM 
			  	store_request_items
			LEFT JOIN 
			  	denomination
			ON
			  	store_request_items.sri_items_denomination=denomination.denom_id
			WHERE      
			  	sri_items_requestid='$id'
			AND
				store_request_items.sri_items_remain != 0
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}

	}

	function getScannedGC($link,$id,$denom)
	{
		$query = $link->query(
		"SELECT 
			`temp_rbarcode`
		FROM 
			`temp_release` 
		WHERE 
			`temp_rdenom` = '$denom'
		AND
			`temp_relno` = '$id'
		");

		if($query)
		{
			return $query->num_rows;
		}
		else 
		{
			return $link->error;
		}
	}

	function getRemainingGCtoReleaseByDenom($link,$denid,$reqid)
	{
		$query = $link->query(
			"SELECT 
				`sri_items_remain` 
			FROM 
				`store_request_items` 
			WHERE 
				`sri_items_denomination` = '$denid'
			AND
				`sri_items_requestid` = '$reqid'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->sri_items_remain;
		}
		else 
		{
			return $link->error;
		}
	}

	function getRemainingPromoGC($link,$denid,$reqid)
	{
		$query = $link->query(
			"SELECT 
				pgcreqi_remaining 
			FROM 
				promo_gc_request_items 
			WHERE 
				pgcreqi_denom = '$denid'
			AND
				pgcreqi_trid = '$reqid'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->pgcreqi_remaining;
		}
		else 
		{
			return $link->error;
		}
	}

	function getRemainingGCToTransfer($link,$denid,$reqid)
	{
		$query = $link->query(
			"SELECT 
				tr_itemsqtyremain
			FROM 
				transfer_request_items 
			WHERE 
				tr_itemsreqid='".$reqid."'
			AND
				tr_itemsdenom='".$denid."'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->tr_itemsqtyremain;
		}
		else 
		{
			return $link->error;
		}
	}

	function getScannedGCByDenomAndReqID($link,$denid,$reqid)
	{
		$query = $link->query(
			"SELECT 
				`temp_rbarcode` 
			FROM 
				`temp_release` 
			WHERE 
				`temp_relno` = '$reqid'
			AND
				`temp_rdenom` = '$denid'
		");

		if($query)
		{
			return $query->num_rows;
		}
		else
		{
			return $link->error;
		}
	}

	function getTempReleaseData($link,$relno)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				`temp_rbarcode`, 
				`temp_rdenom`, 
				`temp_rdate`, 
				`temp_relno` 
			FROM 
				`temp_release` 
			WHERE 
				`temp_relno`='$relno'
			AND
				`temp_relby`='".$_SESSION['gc_id']."'
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}

			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function getTotalTempReleaseData($link,$relno)
	{
		$query = $link->query(
			"SELECT 
				IFNULL(SUM(denomination.denomination),0.00) as tot
			FROM 
				temp_release 
			INNER JOIN 
				denomination 
			ON 
				denomination.denom_id = temp_release.temp_rdenom
			WHERE 
				temp_relno='$relno'
			AND
				temp_relby='".$_SESSION['gc_id']."'			
		");

		if($query)
		{
			$row = $query->fetch_object();

			return $row->tot;
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}	

	function getTempReleaseDataSUM($link,$relno)
	{
		$query = $link->query(
			"SELECT 
				SUM(denomination.denomination) as total
			FROM 
				temp_release 
			INNER JOIN
				denomination
			ON
				denomination.denom_id = temp_release.temp_rdenom
			WHERE 
				temp_relno='$relno'
			AND
				temp_relby='".$_SESSION['gc_id']."'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->total;
		}
		else 
		{
			return $link->error;
		}

	}

	function getRemaining($link,$denomID,$reqID)
	{
		$query = $link->query(
			"SELECT 
				`sri_items_remain` 
			FROM 
				`store_request_items` 
			WHERE
				`sri_items_requestid`='$reqID'
			AND
				`sri_items_denomination`='$denomID'
		");

		$remain = $query->fetch_object();
		return $row = $remain->sri_items_remain;
	}

	function checkIfPartialWhole($link,$reqId)
	{
		$status = true;
		$query = $link->query(
			"SELECT 
				`sri_items_remain` 
			FROM 
				`store_request_items` 
			WHERE 
				`sri_items_remain`!='0'
			AND
				`sri_items_requestid`='$reqId'
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				if($row->sri_items_remain>0)
				{
					$status = false;
					break;
				}
			}
			if($status)
			{
				return true;
			}
			else 
			{
				return false;
			}
		}
		else 
		{
			echo $link->error;
		}
	}

	function checkIfPartialWholePromo($link,$reqID)
	{
		$isAllEqual = true;
		$query_getd = $link->query(
			"SELECT 
				pgcreqi_denom,
				pgcreqi_remaining
			FROM 
				promo_gc_request_items 
			WHERE
				pgcreqi_trid='$reqID'
		");

		if($query_getd)
		{
			while ($row = $query_getd->fetch_object()) 
			{
				$scannedPromo =0;
				if(isset($_SESSION['scannedPromo']))
				{
					foreach ($_SESSION['scannedPromo'] as $key => $value) {
						if($value['denomid']==$row->pgcreqi_denom)
						{
							$scannedPromo++;
						}
					}
				}
				if($scannedPromo!=$row->pgcreqi_remaining)
				{
					$isAllEqual = false;
					$break;
				}
			}

			if($isAllEqual)
			{
				return true;
			}
			else 
			{
				return false;
			}
		}
		else 
		{
			echo $link->error;
			die();
		}
	}

	function checkIFRequestHasReleased($link,$reqid)
	{
		$status = true;
		$query = $link->query(
			"SELECT 
				`rel_status` 
			FROM 
				`gc_release` 
			WHERE 
				`rel_storegcreq_id`='$reqid'
		");

		if($query)
		{
			if($query->num_rows > 0)
			{
				return true;
			}
			else 
			{
				return false;
			}
		}
		else
		{
			return $link->error;
		}
	}

	// function

	function getAllocatedGCNotReleased($link,$storeid)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				`gc_location` .`loc_barcode_no`,
				`gc_location` .`loc_gc_type`,
				`gc`.`pe_entry_gc`,
				`denomination`.`denomination`
			FROM 
				`gc_location`
			INNER JOIN
				`gc`
			ON
				`gc_location`.`loc_barcode_no` = `gc`.`barcode_no`
			INNER JOIN
				`denomination`
			ON
				`gc`.`denom_id` = `denomination`.`denom_id`
			WHERE 
				`gc_location` .`loc_store_id` = '$storeid'
			AND
				`gc_location` .`loc_rel`=''
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}

	}

	function  getTempScannedGCByLocation($link,$storeid)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				`temp_release`.`temp_rbarcode`,
				`gc_location`.`loc_gc_type`,
				`gc`.`pe_entry_gc`,
				`denomination`.`denomination`
			FROM 
				`temp_release` 
			INNER JOIN
				`gc_location`
			ON
				`gc_location`.`loc_barcode_no` = `temp_release`.`temp_rbarcode`
			INNER JOIN
				`gc`
			ON
				`gc`.`barcode_no` = `temp_release`.`temp_rbarcode`
			INNER JOIN
				`denomination`
			ON
				`temp_release`.`temp_rdenom` = `denomination`.`denom_id`
			WHERE
				`gc_location`.`loc_store_id`='$storeid'	
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;				
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}
	} 

	function checkifHAsReleased($link,$reqID)
	{
		$query = $link->query(
			"SELECT 
				`agcr_request_id` 
			FROM 
				`approved_gcrequest` 
			WHERE 
				`agcr_request_id`='$reqID'
			");

		if($query)
		{
			return $query->num_rows;
		}
		else 
		{
			return $link->error;
		}
	}

	function getDenominations($link)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				denom_id,
				denomination,
				denom_fad_item_number
			FROM 
				denomination
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function getTempValidated($link,$recno)
	{
		$rows = [];
		$query = $link->query(
		"SELECT 
			`tval_barcode`, 
			`tval_recnum`, 
			`tval_denom` 
		FROM 
			`temp_validation` 
		WHERE
			`tval_recnum`='$recno' 
		");

		if($query)
		{
			while ( $row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function tempRecTotal($link,$id)
	{
		$query = $link->query(
			"SELECT 
			IFNULL(SUM(denomination.denomination),0) as total
				FROM 
			`temp_validation`
				INNER JOIN
			`denomination`
				ON
			`denomination`.`denom_id` = `temp_validation`.`tval_denom`
				WHERE
			`tval_recnum`='$id' 
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->total;
		}
		else 
		{
			return $link->error;
		}
	}

	function getAllocatedGCNotReleasedByDenom($link,$store,$denom)
	{
		$query = $link->query(
			"SELECT	
				`gc_location`.`loc_barcode_no`
			FROM
				`gc_location`
			INNER JOIN
				`gc`
			ON
				`gc_location`.`loc_barcode_no` = `gc`.`barcode_no`
			WHERE 
				`gc_location`.`loc_store_id` = '$store'
			AND
				`gc_location`.`loc_rel`=''
			AND
				`gc`.`denom_id`='$denom'
		");

		if($query)
		{
			return $query->num_rows;
		}
		else
		{
			return $link->error;
		}

	}

	function threedigits($num)
	{
		return sprintf("%03d", $num);
	}

	function getReceivedNumByStore($link,$storeid)
	{
		$query = $link->query(
			"SELECT 
				`srec_recid` 
			FROM 
				`store_received` 
			WHERE 
				`srec_store_id` = '$storeid'
			AND
				`srec_receivingtype`='treasury releasing'
			ORDER BY
				`srec_recid`
			DESC
		");

		if($query)
		{
			if($query->num_rows > 0)
			{
				$row = $query->fetch_object();
				return $row->srec_recid+1;
			}
			else 
			{
				return 1;
			}
		}
		else 
		{
			return $link->error;
		}
	}

	function getDenominationList($link)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				`denomination`,
				`denom_id`
			FROM
				`denomination`
			ORDER BY
				`denom_id`
			ASC
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else
		{
			return $rows[] = $link->error;
		}
	}

	function getGCReceivablesByReleasedAndStore($link,$id)
	{
		$rows = []; 
		$query = $link->query(
			"SELECT 
				`denomination`.`denom_id`,
				`denomination`.`denomination`
			FROM 
				`gc_release`
			INNER JOIN
				`gc`
			ON
				`gc_release`.`re_barcode_no` = `gc`.`barcode_no`
			INNER JOIN
				`denomination`
			ON
				`gc`.`denom_id` = `denomination`.`denom_id`
			WHERE 
				`gc_release`.`rel_num`='$id'
			GROUP BY
				`gc`.`denom_id`
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else
		{
			return $rows[] = $link->error;
		}
	}

	function getNumReceived($link,$id,$denom)
	{
		$query = $link->query(
			"SELECT 
				`re_barcode_no` 
			FROM 
				`gc_release`
			INNER JOIN
				`gc`
			ON
				`gc`.`barcode_no` = `gc_release`.`re_barcode_no`
			WHERE 
				`gc_release`.`rel_num` = '$id'
			AND
				`gc`.`denom_id` = '$denom'	
		");

		if($query)
		{
			return $query->num_rows;
		}
		else 
		{
			return $link->error;
		}
	}

	function getRequestDetails($link,$id)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				`store_gcrequest`.`sgc_id`,
				`store_gcrequest`.`sgc_num`,
				`store_gcrequest`.`sgc_date_request`,
				`approved_gcrequest`.`agcr_id`,
				`approved_gcrequest`.`agcr_approved_at`,
				`users`.`firstname`,
				`users`.`lastname`,
				`approved_gcrequest`.`agcr_stat`
			FROM 
				`approved_gcrequest`
			INNER JOIN
				`store_gcrequest`
			ON
				`store_gcrequest`.`sgc_id` = `approved_gcrequest`.`agcr_request_id`
			INNER JOIN
				`users`
			ON
				`approved_gcrequest`.`agcr_preparedby` = `users`.`user_id`
			WHERE 
				`agcr_id`='$id'
		");

		if($query)
		{
			return $rows[] = $query->fetch_object();
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function gcStoreGCReleasedNumRows($link,$storeid)
	{
		$query = $link->query(
			"SELECT 
				`approved_gcrequest`.`agcr_id` 
			FROM 
				`approved_gcrequest` 
			INNER JOIN
				`store_gcrequest`
			ON
				`approved_gcrequest`.`agcr_request_id` = `store_gcrequest`.`sgc_id`
			WHERE 
				`store_gcrequest`.`sgc_store` ='$storeid'
		");

		if($query)
		{
			return $query->num_rows;
		}
		else
		{
			return $link->error;
		}
	}

	function getTempGCReceived($link,$recnum,$storeid)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				`trec_barcode`,
				`trec_recnum`,
				`trec_store`,
				`trec_denid`
			FROM 
				`temp_receivestore` 
			WHERE 
				`trec_recnum`='$recnum'
			AND
				`trec_store`='$storeid'
			AND
				`trec_by`='".$_SESSION['gc_id']."'
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function deleteReceivedGC($link,$storeid)
	{

		$query = $link->query(
			"DELETE 
			FROM 
				`temp_receivestore`
			WHERE 
				`trec_store`='$storeid'
		");

		if($query)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function getAllDenomination($link)
	{
		$rows = [];
		$query = $link->query(
			"SELECT
				*
			FROM 
				denomination
			WHERE 
				denom_type='RSGC'
			and
				denom_status='active'
			ORDER BY 
				denomination
			ASC
		");

		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}
			return $rows;
		}
		else
		{
			echo $link->error;
			return $rows[] = $link->error;
		}
	}

	function getPendingGCRequestStore($link,$storeid)
	{
		$rows = [];
		$query = $link->query(
			"SELECT
			    `store_gcrequest`.`sgc_id`,
			    `store_gcrequest`.`sgc_num`,
			    `users`.`firstname`,
			    `users`.`lastname`,
			    `store_gcrequest`.`sgc_status`,
			    `store_gcrequest`.`sgc_date_request`,
			    `store_gcrequest`.`sgc_date_needed`,
			    `stores`.`store_name`
			FROM 
				`store_gcrequest` 
			INNER JOIN
				`stores`
			ON
			`store_gcrequest`.`sgc_store` = `stores`.`store_id`
			INNER JOIN
				`users`
			ON
				`users`.`user_id` = `store_gcrequest`.`sgc_requested_by`
			WHERE
				(`store_gcrequest`.`sgc_status`=0
			OR	
				`store_gcrequest`.`sgc_status`=1)
			AND
				`store_gcrequest`.`sgc_store`='$storeid'
			AND
				`store_gcrequest`.`sgc_cancel`=''
			ORDER BY 
				`store_gcrequest`.`sgc_id`
			DESC
		");
		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function getDenominationByBarcode($link,$barcode)
	{
		$query = $link->query(
			"SELECT 
				denomination.denomination
			FROM 
				gc
			INNER JOIN
				denomination
			ON
				gc.denom_id = denomination.denom_id
			WHERE 
				gc.barcode_no = '$barcode'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->denomination;
		}
		else 
		{
			return $link->error;
		}
	}

	function getSpecialGCDenomination($link,$barcode)
	{
		$query = $link->query(
			"SELECT 
				spexgcemp_trid,
			    spexgcemp_denom
			FROM 
				special_external_gcrequest_emp_assign 
			WHERE 
				spexgcemp_barcode='$barcode'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->spexgcemp_denom;
		}
		else 
		{
			return $link->error;
		}
	}


	function getSpecialGCDenom($link,$barcode)
	{
		$query = $link->query(
			"SELECT 
				spexgcemp_denom 
			FROM 
				special_external_gcrequest_emp_assign 
			WHERE 
				spexgcemp_barcode='$barcode'
		");

		$row = $query->fetch_object();
		return $row->spexgcemp_denom;
	}

	function getDenomByID($link,$denomid)
	{
		$query = $link->query(
			"SELECT 
				`denomination` 
			FROM 
				`denomination` 
			WHERE 
				`denom_id`='$denomid'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->denomination;
		}
		else 
		{
			return $link->error;
		}
	}

	function getLastDateVerified($link,$gc)
	{
		$rows=[];
		$query = $link->query(
			"SELECT 
				`vs_store`,
				`vs_date`,
				`vs_barcode`,
				`vs_cn`
			FROM 
				`store_verification` 
			WHERE
				`vs_barcode`='$gc'
			ORDER BY
				`vs_id`
			DESC
			LIMIT 1
		");

		if($query)
		{
			return $rows[] = $query->fetch_object();
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function getLastVerificationDetails($link,$gc)
	{
		$rows=[];
		$query = $link->query(
			"SELECT 
				`store_verification`.`vs_date`,
				`store_verification`.`vs_barcode`,
				`store_verification`.`vs_time`,
				`stores`.`store_name`,
				`customers`.`cus_fname`,
				`customers`.`cus_lname`,
				`users`.`firstname`,
				`users`.`lastname`
			FROM 
				`store_verification`
			INNER JOIN
				`stores`
			ON
				`store_verification`.`vs_store` = `stores`.`store_id`
			INNER JOIN
				`customers`
			ON
				`store_verification`.`vs_cn` = `customers`.`cus_id`
			INNER JOIN
				`users`
			ON
				`store_verification`.`vs_by` = `users`.`user_id`
			WHERE
				`store_verification`.`vs_barcode`='$gc'
			ORDER BY
				`store_verification`.`vs_id`
			DESC
			LIMIT 1
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;

		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function countsql($link,$sql)
	{
		$query = $link->query($sql);
		if($query)
		{
			$row = $query->fetch_object();
			return $row->cnt;
		}
		else 
		{
			return $link->error;
		}

	}

	function getCustomerFullname($link,$cusid)
	{
		$query = $link->query(
			"SELECT 
				`cus_fname`,
				`cus_lname`
			FROM 
				`customers` 
			WHERE 
				`cus_id`='$cusid'
			LIMIT 1
		");

		if($query)
		{
			$row = $query->fetch_object();
			return ucwords($row->cus_fname.' '.$row->cus_lname);
		}
		else 
		{
			return $link->error;
		}
	}

	function getRequestDetailsPending($link,$id)
	{
		$query = $link->query(
			"SELECT 
				store_gcrequest.sgc_id,
				store_gcrequest.sgc_num,
				store_gcrequest.sgc_type,
				stores.store_name,
				store_gcrequest.sgc_date_request,
				store_gcrequest.sgc_date_needed,
				store_gcrequest.sgc_file_docno,
				store_gcrequest.sgc_remarks,				
				users.firstname,
				users.lastname		
			FROM 
				store_gcrequest
			INNER JOIN
				stores
			ON
				store_gcrequest.sgc_store = stores.store_id
			INNER JOIN
				users
			ON
				users.user_id = store_gcrequest.sgc_requested_by
			WHERE 
				store_gcrequest.sgc_id='$id'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row;
		}
		else 
		{
			return $link->error;
		}
	}

	function getGCStoreRequestNum($link,$id)
	{
		$query = $link->query(
			"SELECT 
				`sgc_num` 
			FROM 
				`store_gcrequest`
			WHERE 
				`sgc_id`='$id'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->sgc_num;
		}
		else 
		{
			return $link->error;
		}
	}

	function getProdReqRemainGC($link,$denomid,$reqid)
	{
		$query = $link->query(
			"SELECT 
				`pe_items_remain` 
			FROM 
				`production_request_items` 
			WHERE 
				`pe_items_denomination`='$denomid'
			AND
				`pe_items_request_id`='$reqid'	
		");

		if($query)
		{
			$row = $query->fetch_object();
			return is_null($row) ? 0 : $row->pe_items_remain;
		}
		else 
		{
			return $link->error;
		}
	}

	function getDenomIdByDenomination($link,$denom)
	{
		$query = $link->query(
			"SELECT 
				`denom_id` 
			FROM 
				`denomination` 
			WHERE 
				`denomination`='$denom'	
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->denom_id;
		}
		else 
		{
			return $link->error;
		}
	}

	function subtractRemaining($link,$reqid,$denom,$remain)
	{
		$query = $link->query(
			"UPDATE 
				`production_request_items` 
			SET 
				`pe_items_remain`='$remain'
				
			WHERE 
				`pe_items_request_id`='$reqid'
			AND
				`pe_items_denomination`='$denom'
		");
		if($query)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function checkProductionRequestForValidation($link,$ereq, $barcode)
	{
		$query = $link->query(
			"SELECT 
				`gc`.`barcode_no`
			FROM
				`requisition_entry`
			INNER JOIN
				`gc`
			ON
				`gc`.`pe_entry_gc` = `requisition_entry`.`repuis_pro_id`
			WHERE
				`requisition_entry`.`requis_erno`='$ereq'
			AND
				`gc`.`barcode_no`='$barcode'
		");

		if($query)
		{
			return $query->num_rows;
		}
		else 
		{
			return $link->error;
		}
	}

	function checkBarcodeRange($gcStart,$gcEnd)
	{
		if($gcStart < $gcEnd)
		{
			return true;
		}
		else 
		{
			return false;
		}
	}

	function getPromo($link)
	{
		$tag = getField($link,'promo_tag','users','user_id',$_SESSION['gc_id']);
		$rows = [];
		$query = $link->query(
			"SELECT 
				promo.promo_id,
				promo.promo_name,
				promo.promo_date,
				promo.promo_remarks,
				users.firstname,
				users.lastname,
				users.promo_tag,
				promo.promo_group
			FROM 
				promo
			INNER JOIN
				users
			ON
				users.user_id = promo.promo_valby
			WHERE 
				promo.promo_tag='$tag'			
			ORDER BY
				promo_id
			DESC
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			echo $rows[] = $link->error;
			exit();
		}
	}

	function getPromoByID($link,$id)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				promo.promo_id,
				promo.promo_name,
				promo.promo_num,				
				promo.promo_date,
				promo.promo_remarks,
				promo.promo_group,
				promo.promo_dateexpire,
				promo.promo_datenotified,
				promo.promo_drawdate,
				users.firstname,
				users.lastname
			FROM 
				promo
			INNER JOIN
				users
			ON
				users.user_id = promo.promo_valby
			WHERE
				promo.promo_id='$id'
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function getPromoGCByID($link,$id)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				`promo_gc`.`prom_barcode`,
				`denomination`.`denomination`,
				`gc_type`.`gctype`,
				`store_verification`.`vs_barcode`
			FROM 
				`promo_gc`
			INNER JOIN
				`denomination`
			ON
				`denomination`.`denom_id` = `promo_gc`.`prom_denom`
			INNER JOIN
				`gc_type`
			ON
				`gc_type`.`gc_type_id` = `promo_gc`.`prom_gctype`
			LEFT JOIN
				`store_verification`
			ON
				`store_verification`.`vs_barcode` = `promo_gc`.`prom_barcode`
			WHERE 
				`promo_gc`.`prom_promoid`='$id'
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function isGCValid($gcStart)
	{
		
	}

	function getCode($link)
	{
		if(numRowsNoWhere($link,'customer_internal')>0)
		{
			$code = getOne($link,'ci_code','customer_internal','ci_code');
			$code++;
			return $code;
		}
		else 
		{
			return 1;
		}
	}

	function getCustomersInternal($link)
	{
		$rows = [];
		$query = $link->query(
			"SELECT
				*
			FROM 
				`customer_internal`
			ORDER BY
				`ci_code`
			DESC
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function getCustomerDiscountInfo($link,$id)
	{
		$rows = [];
		$query = $link->query(
		"SELECT 
			`customer_internal`.`ci_name`,
			`customer_internal`.`ci_distype`
		FROM 
			`customer_internal` 
		WHERE 
			`ci_code`='$id'
		LIMIT 1
		");

		if($query)
		{
			return $rows[] = $query->fetch_object();
		}
		else 
		{
			return $rows[] = $link->query;
		}
	}

	function getCustomerDiscounts($link,$id)
	{
		$rows = [];
		$query = $link->query(
		"SELECT 
			`customer_discounts`.`cdis_dis`,
			`customer_discounts`.`cdis_denom_id`,
			`denomination`.`denomination`
		FROM 
			`customer_discounts`
		INNER JOIN
			`denomination`
		ON
			`denomination`.`denom_id` = `customer_discounts`.`cdis_denom_id`
		WHERE 
			`customer_discounts`.`cdis_cusid` = '$id'	
		");

		if($query)
		{
			while ($row = $query->fetch_object()) {
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function test(){
		return 'xxx';
	}

	function insertGCStoreGCEntry($link,$refid,$userid,$ledgernum,$desc,$entryType,$trans)
	{

		$totalGCAmount = $link->query(
			"SELECT 
				SUM(denomination.denomination) AS totalGC
			FROM 
				`temp_receivestore`
			INNER JOIN
				`denomination`
			ON
				`temp_receivestore`.`trec_denid` = `denomination`.`denom_id`
			WHERE 
				`temp_receivestore`.`trec_by`='$userid'
		");

		$row = $totalGCAmount->fetch_object();
		$totalGCAmount = $row->totalGC;
		$store = getStoreAssigned($link,$userid);

		$insertData = $link->query(
			"INSERT INTO 
				`ledger_store`
			(				
				`sledger_date`, 
				`sledger_trans`, 
				`sledger_desc`, 
				$entryType,
				`sledger_store`,
				`sledger_no`,
				`sledger_ref` 
			) 
			VALUES 
			(
				NOW(),
				'$trans',
				'$desc',
				'$totalGCAmount',
				'$store',
				'$ledgernum',
				'$refid'
			)
		");

		if($insertData)
		{
			return true;
		}
		else 
		{
			return false;
		}
	}

	function insertGCStoreGCEntryTransfer($link,$refid,$userid,$ledgernum,$desc,$entryType,$trans,$totamt)
	{
		$insertData = $link->query(
			"INSERT INTO 
				ledger_store
			(				
				sledger_date, 
				sledger_trans, 
				sledger_desc, 
				$entryType,
				sledger_store,
				sledger_no,
				sledger_ref 
			) 
			VALUES 
			(
				NOW(),
				'$trans',
				'$desc',
				'$totamt',
				'".$_SESSION['gc_store']."',
				'$ledgernum',
				'$refid'
			)
		");

		if($insertData)
		{
			return true;
		}
		else 
		{
			return false;
		}
	}

	function reportHeaderCustodianSRR($link,$id)
	{
		$query = $link->query(
			"SELECT 
				`custodian_srr`.`csrr_receivetype`,
				`custodian_srr`.`csrr_receivedas`,
				`custodian_srr`.`csrr_datetime`,
				`custodian_srr`.`csrr_checked_by`,
				`custodian_srr`.`csrr_prepared_by`,
				`requisition_entry`.`requis_erno`,
				`requisition_entry`.`requis_loc`,
				`supplier`.`gcs_companyname`,
				`users`.`firstname`,
				`users`.`lastname`
			FROM 
				`custodian_srr` 
			INNER JOIN
				`requisition_entry`
			ON
				`custodian_srr`.`csrr_requisition` = `requisition_entry`.`requis_id`
			INNER JOIN
				`supplier`
			ON
				`supplier`.`gcs_id` = `requisition_entry`.`requis_supplierid`
			INNER JOIN 
				`users`
			ON
				`users`.`user_id`=`custodian_srr`.`csrr_prepared_by`
			WHERE 
				`csrr_id`='$id'
			LIMIT 1
		");

		if($query)
		{
			return $row = $query->fetch_object();
		}
	}

	function groupDenomCustodianReport($link,$id)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				`custodian_srr_items`.`cssitem_barcode`,
				`denomination`.`denomination`,
				`denomination`.`denom_id`
			FROM 
				`custodian_srr_items`
			INNER JOIN
				`gc`
			ON
				`gc`.`barcode_no` = `custodian_srr_items`.`cssitem_barcode`
			INNER JOIN
				`denomination`
			ON
				`gc`.`denom_id` = `denomination`.`denom_id`
			WHERE 
				`cssitem_recnum`='$id'
			GROUP BY
				`denomination`.`denomination`
		");
		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}
			return $rows;
		}
	}

	function getDenomCustodianReportById($link,$recnum,$den_id)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				`custodian_srr_items`.`cssitem_barcode`,
				`denomination`.`denomination`,
				`denomination`.`denom_id`
			FROM 
				`custodian_srr_items`
			INNER JOIN
				`gc`
			ON
				`gc`.`barcode_no` = `custodian_srr_items`.`cssitem_barcode`
			INNER JOIN
				`denomination`
			ON
				`gc`.`denom_id` = `denomination`.`denom_id`
			WHERE 
				`cssitem_recnum`='$recnum'
			AND
				`denomination`.`denom_id`='$den_id'
		");
		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}
			return $rows;
		}
	}

	function checkIsAValidDate($myDateString){
	    return (bool)strtotime($myDateString);
	}

	function getAllReleasedGCDetails($link,$relid)
	{
		$rows = [];
		$query = $link->query(
			"SELECT
			    `approved_gcrequest`.`agcr_id`,
			    `approved_gcrequest`.`agcr_request_id`,
			    `approved_gcrequest`.`agcr_approvedby`,
			    `approved_gcrequest`.`agcr_approved_at`,
			    `approved_gcrequest`.`agcr_checkedby`,
			    `approved_gcrequest`.`agcr_recby`,
			    `approved_gcrequest`.`agcr_file_docno`,
			    `approved_gcrequest`.`agcr_stat`,
			    `approved_gcrequest`.`agcr_rec`,
			    `approved_gcrequest`.`agcr_remarks`,
			    `users`.`firstname`,
			    `users`.`lastname`,
				`store_gcrequest`.`sgc_store`,
				`stores`.`store_name`,
				`store_gcrequest`.`sgc_num`,
				`store_gcrequest`.`sgc_date_request`,
				`store_gcrequest`.`sgc_date_needed`,
				`store_gcrequest`.`sgc_remarks`,
				`store_gcrequest`.`sgc_file_docno`,
				`store_gcrequest`.`sgc_requested_by`
			FROM 
				`approved_gcrequest`
			INNER JOIN
			`users`
			ON
				`users`.`user_id` = `approved_gcrequest`.`agcr_preparedby`
			INNER JOIN
				`store_gcrequest`
			ON
				`store_gcrequest`.`sgc_id` = `approved_gcrequest`.`agcr_request_id`
			INNER JOIN
				`stores`
			ON
				`stores`.`store_id` = `store_gcrequest`.`sgc_store`
			WHERE
				`approved_gcrequest`.`agcr_id`='$relid'
		");

		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $rows[] = $link->error;
		}
	}

	function getBarcodesByDenomByRelId($link,$den,$relid)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				`gc_release`.`re_barcode_no`
			FROM 
				`gc_release`
			INNER JOIN
				`gc`
			ON
				`gc`.`barcode_no` = `gc_release`.`re_barcode_no`
			WHERE 
				`gc_release`.`rel_num`='$relid'
			AND
				`gc`.`denom_id`='$den'	
		");

		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $link->error;
		}
	}

	function getUserFirstnameAndLastnameById($link,$userid)
	{
		$query = $link->query(
			"SELECT 
				`lastname`,
				`firstname`
			FROM 
				`users` 
			WHERE 
				`user_id`='$userid'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return ucwords($row->firstname.' '.$row->lastname);
		}
		else 
		{
			return $link->error;
		}
	}

	function getRemainingGCRequest($link,$reqid)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				`store_request_items`.`sri_items_remain`,
				`store_request_items`.`sri_items_quantity`,
				`denomination`.`denomination`
			FROM 
				`store_request_items` 
			INNER JOIN
				`denomination`
			ON
				`denomination`.`denom_id` = `store_request_items`.`sri_items_denomination`
			WHERE 
				`store_request_items`.`sri_items_requestid`='$reqid'
	
		");

		if($query)
		{
			while($row = $query->fetch_object())
			{
				$rows[] =  $row;
			}
			return $rows;
		}
		else 
		{
			return $link->error;
		}
	}

	function getStoreGCRequest($link,$reqid)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				denomination.denom_id,
				store_request_items.sri_items_quantity,
				denomination.denomination,
				for_denom_set_up.fds_denom
			FROM 
				store_request_items 
			LEFT JOIN
				denomination
			ON
				denomination.denom_id = store_request_items.sri_items_denomination
			LEFT JOIN
				for_denom_set_up
			ON
				for_denom_set_up.fds_denom_reqid = store_request_items.sri_id
			WHERE 
				store_request_items.sri_items_requestid='$reqid'
	
		");

		if($query)
		{
			while($row = $query->fetch_object())
			{
				$rows[] =  $row;
			}
			return $rows;
		}
		else 
		{
			return $link->error;
		}
	}

	function getverifiedgcStorex($link,$storeid)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				store_verification.vs_barcode,
				store_verification.vs_tf_denomination,
				users.firstname,
				users.lastname, 
				store_verification.vs_time,
				store_verification.vs_date,
				store_verification.vs_tf_balance,
				store_verification.vs_reverifydate,
				store_verification.vs_reverifyby,
				customers.cus_fname,
				customers.cus_lname,
				gc_type.gctype
			FROM 
				store_verification
			INNER JOIN
				users
			ON
				users.user_id = store_verification.vs_by
			INNER JOIN
				customers
			ON
				customers.cus_id = store_verification.vs_cn
			INNER JOIN
				gc_type
			ON
				gc_type.gc_type_id = store_verification.vs_gctype
			WHERE 
				store_verification.vs_store='".$storeid."'
			AND
				(DATE(store_verification.vs_reverifydate) = CURDATE()
			OR 
				store_verification.vs_date <= CURDATE())
			AND
				store_verification.vs_tf_eod=''
			ORDER BY
				store_verification.vs_id
			DESC
		");
		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $link->error;
		}
	}

	function getverifiedgcStoreIT($link)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				store_verification.vs_barcode,
				store_verification.vs_tf_denomination,
				stores.store_name,
				users.firstname,
				users.lastname, 
				store_verification.vs_time,
				store_verification.vs_date,
				store_verification.vs_tf_balance,
				store_verification.vs_reverifydate,
				store_verification.vs_reverifyby,
				customers.cus_fname,
				customers.cus_lname,
				gc_type.gctype
			FROM 
				store_verification
			INNER JOIN
				users
			ON
				users.user_id = store_verification.vs_by
			INNER JOIN
				customers
			ON
				customers.cus_id = store_verification.vs_cn
			INNER JOIN
				gc_type
			ON
				gc_type.gc_type_id = store_verification.vs_gctype
			INNER JOIN
				stores
			ON
				stores.store_id = store_verification.vs_store
			WHERE 
				store_verification.vs_tf_used=''
			AND
				store_verification.vs_tf_eod=''
			AND
				(DATE(store_verification.vs_reverifydate) = CURDATE()
			OR 
				store_verification.vs_date <= CURDATE())
			ORDER BY
				store_verification.vs_id
			DESC
		");
		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $link->error;
		}
	}

	function getverifiedgcStore($link,$storeid)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				store_verification.vs_barcode,
				store_verification.vs_tf_denomination,
				users.firstname,
				users.lastname, 
				store_verification.vs_time,
				store_verification.vs_date,
				store_verification.vs_tf_balance,
				store_verification.vs_reverifydate,
				store_verification.vs_reverifyby,
				customers.cus_fname,
				customers.cus_lname,
				gc_type.gctype
			FROM 
				store_verification
			INNER JOIN
				users
			ON
				users.user_id = store_verification.vs_by
			INNER JOIN
				customers
			ON
				customers.cus_id = store_verification.vs_cn
			INNER JOIN
				gc_type
			ON
				gc_type.gc_type_id = store_verification.vs_gctype
			WHERE 
				store_verification.vs_store='".$storeid."'
			AND
				store_verification.vs_tf_used=''
			AND
				store_verification.vs_tf_eod=''
			AND
				(DATE(store_verification.vs_reverifydate) = CURDATE()
			OR 
				store_verification.vs_date <= CURDATE())
			ORDER BY
				store_verification.vs_id
			DESC
		");
		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $link->error;
		}
	}

	function getverifiedgcStoreEOD($link,$storeid,$id)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				store_verification.vs_barcode,
				store_verification.vs_tf_denomination,
				users.firstname,
				users.lastname,
				store_verification.vs_time,
				store_verification.vs_tf_balance,
				customers.cus_fname,
				customers.cus_lname
			FROM 
				store_verification 
			INNER JOIN
				users
			ON
				users.user_id = store_verification.vs_by
			INNER JOIN
				customers
			ON
				customers.cus_id = store_verification.vs_cn
			WHERE 
				store_verification.vs_store='$storeid'
			AND
				(store_verification.vs_tf_eod = '$id'
			OR
				store_verification.vs_tf_eod2 = '$id')	
			ORDER BY
				store_verification.vs_id
			DESC
		");
		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $link->error;
		}
	}

	function eodDisplayItems($link,$id)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				store_eod_items.st_eod_barcode,
				store_verification.vs_tf_denomination,
				store_verification.vs_tf_balance,
			    store_verification.vs_date,
			    store_verification.vs_time,
			    stores.store_name,
			    store_verification.vs_reverifydate,
			    CONCAT(users.firstname,' ',users.lastname) as verby,
                CONCAT(customers.cus_fname,' ',customers.cus_lname) as cus,
                gc_type.gctype
			FROM 
			 	store_eod_items 
			INNER JOIN
			  	store_verification
			ON
				store_verification.vs_barcode = store_eod_items.st_eod_barcode
			INNER JOIN
				customers
			ON
				customers.cus_id = store_verification.vs_cn
			INNER JOIN
				users
			ON
				users.user_id = store_verification.vs_by
            INNER JOIN
            	gc_type
            ON
            	gc_type.gc_type_id = store_verification.vs_gctype
            INNER JOIN
                stores
            ON
                stores.store_id = store_verification.vs_store
			WHERE 
			  st_eod_trid='".$id."'
		");

		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;       
			}
			return $rows;
		}
		else 
		{
			return $link->error;
		}
	
	}

	function getTextFileBalancesITv2($link,$last_insert,$verificationfolder,$archivefolder,$todays_time)
	{
		$allowedExts = [];

		// To Removed //

		$allowedExts[] = 'igc';
		$allowedExts[] = 'egc';

		// To Removed //

		$allowedExts[] = getGCTextfileExtension($link,'txtfile_extension_internal');
		$allowedExts[] = getGCTextfileExtension($link,'txtfile_extension_external');	

		$wholesaletime = date("H:i", strtotime($todays_time));
		$wholesaletime = str_replace(":", "", $wholesaletime); 

		$query_getxtfile = $link->query(
			"SELECT 
				users.username,
				store_verification.vs_tf,
				store_verification.vs_barcode,
				store_verification.vs_tf_denomination,
				store_verification.vs_tf_balance,
				store_verification.vs_tf_used,
				store_verification.vs_tf_eod,
				store_verification.vs_tf_eod2,
				store_verification.vs_store,
				store_verification.vs_payto
			FROM 
				store_verification 
			INNER JOIN
				users
			ON
				store_verification.vs_by = users.user_id
			WHERE 
				store_verification.vs_tf_used=''
			AND
				store_verification.vs_tf_eod=''
			AND
				(DATE(store_verification.vs_reverifydate) = CURDATE()
			OR 
				store_verification.vs_date <= CURDATE())
			ORDER BY
				store_verification.vs_id
			DESC				
		");

		if(!$query_getxtfile)
		{
			$msg = $link->error;
			$stat = false;
		}
		else
		{
			$haserror = false;
			$errquery = false;
			$archivefile = false;

			if($query_getxtfile->num_rows==0)
			{
				return $response=array(false,'No textfile exist.');
			}
			else 
			{
				// check if all textfiles exist
				$msg = '';
				$txtfiles_temp = [];
				$notfoundgc = [];

				while ($row = $query_getxtfile->fetch_object()) 
				{

					$ip = getField($link,'store_textfile_ip','stores','store_id',$row->vs_store);


						$file = $ip.'\\'.$row->vs_tf;
						if(file_exists($file))
						{
							//array_push($txtfiles_temp,$row->vs_tf);
							$txtfiles_temp[] =  array(
								'ver_barcode'		=> $row->vs_barcode,
								'ver_textfilename' 	=> $row->vs_tf,
								'ver_denom' 		=> $row->vs_tf_denomination,
								'ver_balance' 		=> $row->vs_tf_balance,
								'ver_used'			=> $row->vs_tf_used,
								'ver_eod1'			=> $row->vs_tf_eod,
								'txtfile_ip'		=> $ip,
								'payto'				=> $row->vs_payto
							);
						}
						else 
						{
							if($row->vs_payto == 'WHOLESALE')
							{
								$txtfiles_temp[] =  array(
									'ver_barcode'		=> $row->vs_barcode,
									'ver_textfilename' 	=> $row->vs_tf,
									'ver_denom' 		=> $row->vs_tf_denomination,
									'ver_balance' 		=> $row->vs_tf_balance,
									'ver_used'			=> $row->vs_tf_used,
									'ver_eod1'			=> $row->vs_tf_eod,
									'txtfile_ip'		=> $ip,
									'payto'				=> $row->vs_payto
								);
							}
							else
							{
								//$msg = 'GC Barcode # '.$row->vs_tf.' not found.';
								$notfoundgc[] = $row->vs_tf;
								$haserror = true;
								//break;		
							}				
						}
				}

				if($haserror)
				{
					$stat = false;

					$msg = '<h4 class="vernotfoundtitle">Error: GC not found.</h4>';
				    foreach ($notfoundgc as $n) 
				    {
				        $msg.='<p class="vernotfoundgc">'.$n.'</p>';
				    }
				}
				else 
				{
					// $msg = 'yeah';
					// $stat = false;

					$haserror = false;
					$cnt = 0;
					foreach ($txtfiles_temp as $key => $value) 
					{	
						$cnt++;

						// if wholesale save data
						// else save textfile data
						if($value['payto']=='WHOLESALE')
						{							
							$query_update_used = $link->query(
								"UPDATE 
									store_verification 
								SET 
									vs_tf_used='*',
							 		vs_tf_balance='0',
							 		vs_tf_purchasecredit='".$value['ver_denom']."',
							 		vs_tf_eod='1'
									
								WHERE 
									vs_barcode='".$value['ver_barcode']."'
							");

							if(!$query_update_used)
							{
								$errquery = true;
								$msg = $link->error;
								break;
							}		
							else
							{
								$query_trans = $link->query(
									"INSERT INTO 
										store_eod_textfile_transactions
									(
										seodtt_eod_id, 
										seodtt_barcode, 
										seodtt_line, 
										seodtt_creditlimit, 
										seodtt_credpuramt, 
										seodtt_addonamt,
										seodtt_balance, 
										seodtt_transno, 
										seodtt_timetrnx, 
										seodtt_bu, 
										seodtt_terminalno, 
										seodtt_ackslipno, 
										seodtt_crditpurchaseamt
									)
									VALUES 
									(
										'$last_insert', 
										'".$value['ver_barcode']."',
										'',
										'".$value['ver_denom']."',
										'".$value['ver_denom']."',
										'0',
										'0',
										'',
										'$wholesaletime',
										'',
										'WHOLESALE',
										'',
										'".$value['ver_denom']."'
									)
								");

								if(!$query_trans)
								{
									$msg = $link->error;
									$errquery = true;
									break;	
								}
								else 
								{
									$query_eod_ins = $link->query(
										"INSERT INTO 
											store_eod_items
										(
										    st_eod_barcode, 
										    st_eod_trid
										) 
										VALUES 
										(
										    '".$value['ver_barcode']."',
										    '$last_insert'
										)
									");

									if(!$query_eod_ins)
									{
										$msg = $link->error;
										$haserror = true;
										break;
									}
								}								
							}						
					
						}
						else 
						{
							//echo $value['ver_barcode'];	
							$arr_f = [];
							$file = $value['txtfile_ip'].'\\'.$value['ver_textfilename'];
							if(!checkFolder($file))
							{							
								$msg = 'GC Barcode # '.$value['ver_textfilename'].' missing.';
								$haserror = true;
								break;	
							}

				            $temp = explode(".", $file);
				            $extension = end($temp);	
				            if(!in_array($extension, $allowedExts))
				            {
								$msg = 'GC Barcode # '.$value['ver_textfilename'].' file extension is not allowed.';
								$haserror = true;
								break;	
				            }
				            else 
				            {
								$r_f = fopen($file,'r');
								while(!feof($r_f)) 
								{
									$arr_f[] = fgets($r_f);
								}
								fclose($r_f);

								for ($i=0; $i < count($arr_f); $i++) 
								{
									$used = false;
									if($arr_f[$i]==2)
									{
										$dpc = explode(",",$arr_f[$i]);
										$pc = $dpc[1];
									}	

									if($arr_f[$i]==3)
									{
										$dam = explode(",",$arr_f[$i]);
										$am = $dam[1];
									}

									if($arr_f[$i]==4)
									{
										$dpc = explode(",",$arr_f[$i]);
										$rem_amt = trim($dpc[1]);

										if($rem_amt<$value['ver_denom'])
										{
											$used = true;
										}

										if($used)
										{
											$query_update_used = $link->query(
												"UPDATE 
													store_verification 
												SET 
													vs_tf_used='*',
											 		vs_tf_balance='$rem_amt',
											 		vs_tf_purchasecredit='$pc',
													vs_tf_addon_amt='$am'
												WHERE 
													vs_barcode='".$value['ver_barcode']."'

											");

											if(!$query_update_used)
											{
												$errquery = true;
												$msg = $link->error;
												break;
											}										
										}			
									}

									if($arr_f[$i]>7)
									{
										if(trim($arr_f[$i])!='')
										{
											$t = explode(",",$arr_f[$i]);
											$query_trans = $link->query(
												"INSERT INTO 
													store_eod_textfile_transactions
												(
													seodtt_eod_id, 
													seodtt_barcode, 
													seodtt_line, 
													seodtt_creditlimit, 
													seodtt_credpuramt, 
													seodtt_addonamt,
													seodtt_balance, 
													seodtt_transno, 
													seodtt_timetrnx, 
													seodtt_bu, 
													seodtt_terminalno, 
													seodtt_ackslipno, 
													seodtt_crditpurchaseamt
												)
												VALUES 
												(
													'$last_insert',
													'".$value['ver_barcode']."',
													'".$t[0]."',
													'".$t[1]."',
													'".$t[2]."',
													'".$t[3]."',
													'".$t[4]."',
													'".$t[5]."',
													'".$t[6]."',
													'".$t[7]."',
													'".$t[8]."',
													'".$t[9]."',
													'".$t[10]."'
												)
											");

											if(!$query_trans)
											{
												$msg = $link->error;
												$errquery = true;
												break;	
											}
										}									
									} // $arr_f[$i]>7
								} // end for

								if($errquery)
								{
									$haserror = true;
									break;
								}
								else 
								{
									$query_eod_ins = $link->query(
										"INSERT INTO 
											store_eod_items
										(
										    st_eod_barcode, 
										    st_eod_trid
										) 
										VALUES 
										(
										    '".$value['ver_barcode']."',
										    '$last_insert'
										)
									");

									if(!$query_eod_ins)
									{
										$msg = $link->error;
										$haserror = true;
										break;
									}

									$query_update_vergc = $link->query(
										"UPDATE 
										store_verification 
									SET 
										vs_tf_eod='1' 
									WHERE 
										vs_barcode='".$value['ver_barcode']."'
									");

									if(!$query_update_vergc)
									{
										$msg = $link->error;
										$haserror = true;
										break;
									}

									if(file_exists($file.'.BAK'))
									{
										//$filebak = $file.'.BAK';
										if(copy($file,$archivefolder.'\\'.$value['ver_textfilename'].'.BAK'))
										{
											if (!unlink($value['txtfile_ip'] . '\\' .$value['ver_textfilename'].'.BAK')){
												$msg = 'Error deleting '.$value['ver_textfilename'].'.BAK texfile';
												$haserror = true;
												break;												
											}															
										}
										else 
										{
											$msg = 'Error copying '.$value['ver_textfilename'].' texfile';
											$haserror = true;
											break;	
										}
									}

									if(copy($file,$archivefolder.'\\'.$value['ver_textfilename']))
									{
										if (!unlink($value['txtfile_ip'] . '\\' .$value['ver_textfilename'])){
											$msg = 'Error deleting '.$value['ver_textfilename'].' texfile';
											$haserror = true;
											break;												
										}															
									}
									else 
									{
										$msg = 'Error copying '.$value['ver_textfilename'].' texfile';
										$haserror = true;
										break;	
									}
								}
				            }
				        }
					}

					if($haserror)
					{
						$stat = false;
					}
					else
					{
						$stat = true;
					}
				}
			}
		}

		// echo $msg;
		// echo $cnt;
		// exit();

		return array($stat,$msg);

	}

	function getTextFileBalancesIT($link,$last_insert,$verificationfolder,$archivefolder)
	{
		$allowedExts = [];
		$allowedExts = [];

		// To Removed //

		$allowedExts[] = 'igc';
		$allowedExts[] = 'egc';

		// To Removed //

		$allowedExts[] = getGCTextfileExtension($link,'txtfile_extension_internal');
		$allowedExts[] = getGCTextfileExtension($link,'txtfile_extension_external');	

		$query_getxtfile = $link->query(
			"SELECT 
				users.username,
				store_verification.vs_tf,
				store_verification.vs_barcode,
				store_verification.vs_tf_denomination,
				store_verification.vs_tf_balance,
				store_verification.vs_tf_used,
				store_verification.vs_tf_eod,
				store_verification.vs_tf_eod2,
				store_verification.vs_store
			FROM 
				store_verification 
			INNER JOIN
				users
			ON
				store_verification.vs_by = users.user_id
			WHERE 
				store_verification.vs_tf_used=''
			AND
				store_verification.vs_tf_eod=''
			AND
				(DATE(store_verification.vs_reverifydate) = CURDATE()
			OR 
				store_verification.vs_date <= CURDATE())
			ORDER BY
				store_verification.vs_id
			DESC				
		");

		if(!$query_getxtfile)
		{
			$msg = $link->error;
			$stat = false;
		}
		else
		{
			$haserror = false;
			$errquery = false;
			$archivefile = false;

			if($query_getxtfile->num_rows==0)
			{
				return $response=array(false,'No textfile exist.');
			}
			else 
			{
				// check if all textfiles exist
				$msg = '';
				$txtfiles_temp = [];
				$notfoundgc = [];
				while ($row = $query_getxtfile->fetch_object()) 
				{
					$ip = getField($link,'store_textfile_ip','stores','store_id',$row->vs_store);

					$file = $ip.'\\'.$row->vs_tf;

					//$file = $verificationfolder.'\\'.$row->vs_tf;
					if(file_exists($file))
					{
						//array_push($txtfiles_temp,$row->vs_tf);
						$txtfiles_temp[] =  array(
							'ver_barcode' => $row->vs_barcode,
							'ver_textfilename' => $row->vs_tf,
							'ver_denom' => $row->vs_tf_denomination,
							'ver_balance' => $row->vs_tf_balance,
							'ver_used'	=> $row->vs_tf_used,
							'ver_eod1'	=> $row->vs_tf_eod,
							'txtfile_ip'		=> $ip
						);
					}
					else 
					{
						//$msg = 'GC Barcode # '.$row->vs_tf.' not found.';
						$notfoundgc[] = $row->vs_tf;
						$haserror = true;
						//break;					
					}
				}

				if($haserror)
				{
					$stat = false;

					$msg = '<h4 class="vernotfoundtitle">Error: GC not found.</h4>';
				    foreach ($notfoundgc as $n) 
				    {
				        $msg.='<p class="vernotfoundgc">'.$n.'</p>';
				    }
				}
				else 
				{
					$haserror = false;

					foreach ($txtfiles_temp as $key => $value) 
					{
						//echo $value['ver_barcode'];
						$arr_f = [];
						$file = $value['txtfile_ip'].'\\'.$value['ver_textfilename'];
						if(!checkFolder($file))
						{							
							$msg = 'GC Barcode # '.$value['ver_textfilename'].' missing.';
							$haserror = true;
							break;	
						}

			            $temp = explode(".", $file);
			            $extension = end($temp);	
			            if(!in_array($extension, $allowedExts))
			            {
							$msg = 'GC Barcode # '.$value['ver_textfilename'].' file extension is not allowed.';
							$haserror = true;
							break;	
			            }
			            else 
			            {
							$r_f = fopen($file,'r');
							while(!feof($r_f)) 
							{
								$arr_f[] = fgets($r_f);
							}
							fclose($r_f);

							for ($i=0; $i < count($arr_f); $i++) 
							{
								$used = false;
								if($arr_f[$i]==2)
								{
									$dpc = explode(",",$arr_f[$i]);
									$pc = $dpc[1];
								}	

								if($arr_f[$i]==3)
								{
									$dam = explode(",",$arr_f[$i]);
									$am = $dam[1];
								}

								if($arr_f[$i]==4)
								{
									$dpc = explode(",",$arr_f[$i]);
									$rem_amt = trim($dpc[1]);

									if($rem_amt<$value['ver_denom'])
									{
										$used = true;
									}

									if($used)
									{
										$query_update_used = $link->query(
											"UPDATE 
												store_verification 
											SET 
												vs_tf_used='*',
										 		vs_tf_balance='$rem_amt',
										 		vs_tf_purchasecredit='$pc',
												vs_tf_addon_amt='$am'
											WHERE 
												vs_barcode='".$value['ver_barcode']."'

										");

										if(!$query_update_used)
										{
											$errquery = true;
											$msg = $link->error;
											break;
										}										
									}					

								}

								if($arr_f[$i]>7)
								{
									if(trim($arr_f[$i])!='')
									{
										$t = explode(",",$arr_f[$i]);
										$query_trans = $link->query(
											"INSERT INTO 
												store_eod_textfile_transactions
											(
												seodtt_eod_id, 
												seodtt_barcode, 
												seodtt_line, 
												seodtt_creditlimit, 
												seodtt_credpuramt, 
												seodtt_addonamt,
												seodtt_balance, 
												seodtt_transno, 
												seodtt_timetrnx, 
												seodtt_bu, 
												seodtt_terminalno, 
												seodtt_ackslipno, 
												seodtt_crditpurchaseamt
											)
											VALUES 
											(
												'$last_insert',
												'".$value['ver_barcode']."',
												'".$t[0]."',
												'".$t[1]."',
												'".$t[2]."',
												'".$t[3]."',
												'".$t[4]."',
												'".$t[5]."',
												'".$t[6]."',
												'".$t[7]."',
												'".$t[8]."',
												'".$t[9]."',
												'".$t[10]."'
											)
										");

										if(!$query_trans)
										{
											$msg = $link->error;
											$errquery = true;
											break;	
										}
									}									
								} // $arr_f[$i]>7
							} // end for

							if($errquery)
							{
								$haserror = true;
								break;
							}
							else 
							{
								$query_eod_ins = $link->query(
									"INSERT INTO 
										store_eod_items
									(
									    st_eod_barcode, 
									    st_eod_trid
									) 
									VALUES 
									(
									    '".$value['ver_barcode']."',
									    '$last_insert'
									)
								");

								if(!$query_eod_ins)
								{
									$msg = $link->error;
									$haserror = true;
									break;
								}

								$query_update_vergc = $link->query(
									"UPDATE 
									store_verification 
								SET 
									vs_tf_eod='1' 
								WHERE 
									vs_barcode='".$value['ver_barcode']."'
								");

								if(!$query_update_vergc)
								{
									$msg = $link->error;
									$haserror = true;
									break;
								}

								if(file_exists($file.'.BAK'))
								{
									//$filebak = $file.'.BAK';
									if(copy($file,$archivefolder.'\\'.$value['ver_textfilename'].'.BAK'))
									{
										if (!unlink($value['txtfile_ip'] . '\\' .$value['ver_textfilename'].'.BAK')){
											$msg = 'Error deleting '.$value['ver_textfilename'].'.BAK texfile';
											$haserror = true;
											break;												
										}															
									}
									else 
									{
										$msg = 'Error copying '.$value['ver_textfilename'].' texfile';
										$haserror = true;
										break;	
									}

								}

								if(copy($file,$archivefolder.'\\'.$value['ver_textfilename']))
								{
									if (!unlink($value['txtfile_ip'] . '\\' .$value['ver_textfilename'])){
										$msg = 'Error deleting '.$value['ver_textfilename'].' texfile';
										$haserror = true;
										break;												
									}															
								}
								else 
								{
									$msg = 'Error copying '.$value['ver_textfilename'].' texfile';
									$haserror = true;
									break;	
								}
							}

			            }
					}

					if($haserror)
					{
						$stat = false;
					}
					else
					{
						$stat = true;
					}
				}
			}
		}


		return array($stat,$msg);


	}

	function checkTextfiles($link,$verificationfolder,$stores)
	{
		$stsql = "";
		$cntst = count($stores);
		if(count($stores)==1)
		{
			$stsql = " AND store_verification.vs_store='".$stores[0]."' ";
		}
		else 
		{
			$stsql = " AND (";
			$lcnt = 0;
			foreach ($stores as $k) 
			{
				$lcnt++;
				if($lcnt<$cntst)
				{
					$stsql.="store_verification.vs_store='".$k."' OR ";
				}
				else 
				{
					$stsql.="store_verification.vs_store='".$k."' ) ";
				}

			}
		}

		$allowedExts = [];
		$allowedExts[] = "egc";
		$allowedExts[] = "igc";
		$allowedExts[] = getGCTextfileExtension($link,'txtfile_extension_internal');
		$allowedExts[] = getGCTextfileExtension($link,'txtfile_extension_external');	

		$query_getxtfile = $link->query(
			"SELECT 
				users.username,
				store_verification.vs_tf,
				store_verification.vs_barcode,
				store_verification.vs_tf_denomination,
				store_verification.vs_tf_balance,
				store_verification.vs_tf_used,
				store_verification.vs_tf_eod,
				store_verification.vs_tf_eod2,
				store_verification.vs_store
			FROM 
				store_verification 
			INNER JOIN
				users
			ON
				store_verification.vs_by = users.user_id
			WHERE 
				store_verification.vs_tf_used=''
			$stsql
			AND
				store_verification.vs_tf_eod=''
			AND
				(DATE(store_verification.vs_reverifydate) = CURDATE()
			OR 
				store_verification.vs_date <= CURDATE())
			ORDER BY
				store_verification.vs_id
			DESC				
		");

		if(!$query_getxtfile)
		{
			$msg = $link->error;
			$stat = false;
		}
		else
		{
			$haserror = false;
			$errquery = false;
			$archivefile = false;

			if($query_getxtfile->num_rows==0)
			{
				return $response=array(false,'Verification Query is empty');
			}
			else 
			{
				// check if all textfiles exist
				$msg = '';
				$txtfiles_temp = [];
				$notfoundgc = [];

				while ($row = $query_getxtfile->fetch_object()) 
				{
					$ip = getField($link,'store_textfile_ip','stores','store_id',$row->vs_store);

					$file = $ip.'\\'.$row->vs_tf;
					if(file_exists($file))
					{
						//array_push($txtfiles_temp,$row->vs_tf);
						$txtfiles_temp[] =  array(
							'ver_barcode'		=> $row->vs_barcode,
							'ver_textfilename' 	=> $row->vs_tf,
							'ver_denom' 		=> $row->vs_tf_denomination,
							'ver_balance' 		=> $row->vs_tf_balance,
							'ver_used'			=> $row->vs_tf_used,
							'ver_eod1'			=> $row->vs_tf_eod,
							'txtfile_ip'		=> $ip,
							'ver_store'			=> $row->vs_store
						);
					}
					else 
					{
						//$msg = 'GC Barcode # '.$row->vs_tf.' not found.';
						$notfoundgc[] = $row->vs_tf;
						$haserror = true;
						//break;						
					}
				}

				if($haserror)
				{
					$stat = false;

					$msg = '<h4 class="vernotfoundtitle">Error: GC Textfile not found.</h4>';
				    foreach ($notfoundgc as $n) 
				    {
				        $msg.='<p class="vernotfoundgc">'.$n.'</p>';
				    }
				}
				else 
				{
					$stat = true;
				}
			}
		}

		return array($stat,$msg);

	}

	function getTextFileBalancesx($link,$storeid,$userid,$last_insert,$verificationfolder,$archivefolder)
	{

		$allowedExts = [];
		$allowedExts[] = getGCTextfileExtension($link,'txtfile_extension_internal');
		$allowedExts[] = getGCTextfileExtension($link,'txtfile_extension_external');	

		$query_getxtfile = $link->query(
			"SELECT 
				store_verification.vs_tf,
				users.username,
				store_verification.vs_barcode,
				store_verification.vs_tf_denomination
			FROM 
				store_verification 
			INNER JOIN
				users
			ON
				store_verification.vs_by = users.user_id
			WHERE
				users.store_assigned='$storeid'
			AND
				(DATE(store_verification.vs_reverifydate) <= CURDATE()
			AND
                 store_verification.vs_tf_eod2=''
            OR
				store_verification.vs_date <= CURDATE()
			AND
				store_verification.vs_tf_eod='')			
				
		");

		if($query_getxtfile)
		{
			$haserror = false;
			$errquery = false;
			$archivefile = false;

			if($query_getxtfile->num_rows==0)
			{
				return $response=array(false,'No textfile exist.');
			}
			else 
			{
				// check if all textfiles exist
				$msg = '';
				$txtfiles_temp = array();
				while ($row = $query_getxtfile->fetch_object()) 
				{
					$file = $verificationfolder.'\\'.$row->vs_tf;
					if(file_exists($file))
					{
						array_push($txtfiles_temp,$row->vs_tf);
					}
					else 
					{
						$msg = 'GC Barcode # '.$row->vs_tf.' missing.'.$verificationfolder;
						$haserror = true;
						break;						
					}
				}

				if($haserror)
				{
					return $response=array(false,$msg);
				}
				else 
				{
					$haserror = false;
					for($x=0;$x<count($txtfiles_temp);$x++)
					{
						$hastexttrans = false;
						$table = 'store_verification';
						$select = 'vs_tf_balance,vs_barcode,vs_tf_used';
						$where = "vs_tf='".$txtfiles_temp[$x]."'";
						$join = '';
						$limit='';
						$gcdata = getSelectedData($link,$table,$select,$where,$join,$limit);

						//check if already have transactions saved
						$query_getline = $link->query(
							"SELECT 
								seodtt_line
							FROM 
								store_eod_textfile_transactions 
							WHERE	
								seodtt_barcode='".$gcdata->vs_barcode."'
							ORDER BY 
								seodtt_id
							DESC
						"); 

						if(!$query_getline)
						{
							$msg = $link->error;
							return $response=array(false,$msg);
						}

						$nums = $query_getline->num_rows;
						$readstart = 8;
						if($nums > 0)
						{
							$hastexttrans = true;
							$line = $query_getline->fetch_object();
							$readstart = (int)$line->seodtt_line;
							$readstart++;

						}

						$arr_f = [];
						$file = $verificationfolder.'\\'.$txtfiles_temp[$x];
						if(!checkFolder($file))
						{							
							$msg = 'GC Barcode # '.$txtfiles_temp[$x].' missing.';
							$haserror = true;
							break;	
						}
						else
						{
				            $temp = explode(".", $file);
				            $extension = end($temp);	
				            if(!in_array($extension, $allowedExts))
				            {
								$msg = 'GC Barcode # '.$txtfiles_temp[$x].' file extension is not allowed.';
								$haserror = true;
								break;	
				            }
				            else 
				            {
								$r_f = fopen($file,'r');
								while(!feof($r_f)) 
								{
									$arr_f[] = fgets($r_f);
								}
								fclose($r_f);	

								for ($i=0; $i < count($arr_f); $i++) 
								{

									if($arr_f[$i]==2)
									{
										$dpc = explode(",",$arr_f[$i]);
										$pc = $dpc[1];
									}

									if($arr_f[$i]==3)
									{
										$dam = explode(",",$arr_f[$i]);
										$am = $dam[1];
									}

									if($arr_f[$i]==4)
									{
										$dpc = explode(",",$arr_f[$i]);
										$rem_amt = trim($dpc[1]);


										if($rem_amt===$gcdata->vs_tf_balance)
										{
											break;
										}

										if(trim($gcdata->vs_tf_used)=='')
										{
											$query_update_used = $link->query(
												"UPDATE 
													store_verification 
												SET 
													vs_tf_used='*'
												WHERE 
													vs_barcode='$gcdata->vs_barcode'
	
											");

											if(!$query_update_used)
											{
												$errquery = true;
												$msg = $link->error;
												break;
											}
										}

										if($rem_amt==0)
										{
											$archivefile = true;
											$query_update_used = $link->query(
												"UPDATE 
													store_verification 
												SET 
													vs_tf_eod='$last_insert'
												WHERE 
													vs_barcode='$gcdata->vs_barcode'
	
											");

											if(!$query_update_used)
											{
												$errquery = true;
												$msg = $link->error;
												break;
											}											
										}

										$query_update_balance = $link->query(
											"UPDATE 
												store_verification 
											SET 
												vs_tf_balance='$rem_amt',
												vs_tf_purchasecredit='$pc',
												vs_tf_addon_amt='$am'
											WHERE 
												vs_barcode='$gcdata->vs_barcode'
										");

										if(!$query_update_balance)
										{
											$errquery = true;
											$msg = $link->error;
											break;
										}
									}


									if($arr_f[$i]>7)
									{
										if($hastexttrans)
										{
											$i = $readstart;
										}

										if(trim($arr_f[$i])!='')
										{
											$t = explode(",",$arr_f[$i]);
											$query_trans = $link->query(
												"INSERT INTO 
													store_eod_textfile_transactions
												(
													seodtt_eod_id, 
													seodtt_barcode, 
													seodtt_line, 
													seodtt_creditlimit, 
													seodtt_credpuramt, 
													seodtt_addonamt,
													seodtt_balance, 
													seodtt_transno, 
													seodtt_timetrnx, 
													seodtt_bu, 
													seodtt_terminalno, 
													seodtt_ackslipno, 
													seodtt_crditpurchaseamt
												)
												VALUES 
												(
													'$last_insert',
													'".substr($txtfiles_temp[$x],0,-3)."',
													'".$t[0]."',
													'".$t[1]."',
													'".$t[2]."',
													'".$t[3]."',
													'".$t[4]."',
													'".$t[5]."',
													'".$t[6]."',
													'".$t[7]."',
													'".$t[8]."',
													'".$t[9]."',
													'".$t[10]."'
												)
											");

											if(!$query_trans)
											{
												$msg = $link->error;
												$errquery = true;
												break;	
											}
										}
									}
								}

								if(!$errquery)
								{
									if($archivefile)
									{
										if(file_exists($file.'.BAK'))
										{
											//$filebak = $file.'.BAK';
											if(copy($file,$archivefolder.'\\'.$txtfiles_temp[$x].'.BAK'))
											{
												if (!unlink($verificationfolder . '\\' .$txtfiles_temp[$x].'.BAK')){
													$msg = 'Error deleting '.$txtfiles_temp[$x].'.BAK texfile';
													$haserror = true;
													break;												
												}															
											}
											else 
											{
												$msg = 'Error copying '.$txtfiles_temp[$x].' texfile';
												$haserror = true;
												break;	
											}

										}

										if(copy($file,$archivefolder.'\\'.$txtfiles_temp[$x]))
										{
											if (!unlink($verificationfolder . '\\' .$txtfiles_temp[$x])){
												$msg = 'Error deleting '.$txtfiles_temp[$x].' texfile';
												$haserror = true;
												break;												
											}															
										}
										else 
										{
											$msg = 'Error copying '.$txtfiles_temp[$x].' texfile';
											$haserror = true;
											break;	
										}										
									}

								}
								else 
								{
									$haserror = true;
									break;
								}

				            }
						}
					}

					if(!$haserror)
					{
						return $response=array(true,'');
					}
					else 
					{
						return $response=array(false,$msg);
					}
				}
			}
		}

	}

	function getTextFileBalances($link,$storeid,$userid,$last_insert,$verificationfolder,$archivefolder)
	{
		$allowedExts = [];
		$allowedExts[] = getGCTextfileExtension($link,'txtfile_extension_internal');
		$allowedExts[] = getGCTextfileExtension($link,'txtfile_extension_external');	

		$query_getxtfile = $link->query(
			"SELECT 
				users.username,
				store_verification.vs_tf,
				store_verification.vs_barcode,
				store_verification.vs_tf_denomination,
				store_verification.vs_tf_balance,
				store_verification.vs_tf_used,
				store_verification.vs_tf_eod,
				store_verification.vs_tf_eod2
			FROM 
				store_verification 
			INNER JOIN
				users
			ON
				store_verification.vs_by = users.user_id
			WHERE 
				store_verification.vs_store='".$storeid."'
			AND
				store_verification.vs_tf_used=''
			AND
				store_verification.vs_tf_eod=''
			AND
				(DATE(store_verification.vs_reverifydate) = CURDATE()
			OR 
				store_verification.vs_date <= CURDATE())
			ORDER BY
				store_verification.vs_id
			DESC				
		");

		if(!$query_getxtfile)
		{
			$msg = $link->error;
			$stat = false;
		}
		else
		{
			$haserror = false;
			$errquery = false;
			$archivefile = false;

			if($query_getxtfile->num_rows==0)
			{
				return $response=array(false,'No textfile exist.');
			}
			else 
			{
				// check if all textfiles exist
				$msg = '';
				$txtfiles_temp = [];
				while ($row = $query_getxtfile->fetch_object()) 
				{
					$file = $verificationfolder.'\\'.$row->vs_tf;
					if(file_exists($file))
					{
						//array_push($txtfiles_temp,$row->vs_tf);
						$txtfiles_temp[] =  array(
							'ver_barcode' => $row->vs_barcode,
							'ver_textfilename' => $row->vs_tf,
							'ver_denom' => $row->vs_tf_denomination,
							'ver_balance' => $row->vs_tf_balance,
							'ver_used'	=> $row->vs_tf_used,
							'ver_eod1'	=> $row->vs_tf_eod
						);
					}
					else 
					{
						$msg = 'GC Barcode # '.$row->vs_tf.' not found.';
						$haserror = true;
						break;						
					}
				}

				if($haserror)
				{
					$stat = false;
				}
				else 
				{
					$haserror = false;

					foreach ($txtfiles_temp as $key => $value) 
					{
						//echo $value['ver_barcode'];
						$arr_f = [];
						$file = $verificationfolder.'\\'.$value['ver_textfilename'];
						if(!checkFolder($file))
						{							
							$msg = 'GC Barcode # '.$value['ver_textfilename'].' missing.';
							$haserror = true;
							break;	
						}

			            $temp = explode(".", $file);
			            $extension = end($temp);	
			            if(!in_array($extension, $allowedExts))
			            {
							$msg = 'GC Barcode # '.$value['ver_textfilename'].' file extension is not allowed.';
							$haserror = true;
							break;	
			            }
			            else 
			            {
							$r_f = fopen($file,'r');
							while(!feof($r_f)) 
							{
								$arr_f[] = fgets($r_f);
							}
							fclose($r_f);

							for ($i=0; $i < count($arr_f); $i++) 
							{
								$used = false;
								if($arr_f[$i]==2)
								{
									$dpc = explode(",",$arr_f[$i]);
									$pc = $dpc[1];
								}	

								if($arr_f[$i]==3)
								{
									$dam = explode(",",$arr_f[$i]);
									$am = $dam[1];
								}

								if($arr_f[$i]==4)
								{
									$dpc = explode(",",$arr_f[$i]);
									$rem_amt = trim($dpc[1]);

									if($rem_amt<$value['ver_denom'])
									{
										$used = true;
									}

									if($used)
									{
										$query_update_used = $link->query(
											"UPDATE 
												store_verification 
											SET 
												vs_tf_used='*',
										 		vs_tf_balance='$rem_amt',
										 		vs_tf_purchasecredit='$pc',
												vs_tf_addon_amt='$am'
											WHERE 
												vs_barcode='".$value['ver_barcode']."'

										");

										if(!$query_update_used)
										{
											$errquery = true;
											$msg = $link->error;
											break;
										}										
									}					

								}

								if($arr_f[$i]>7)
								{
									if(trim($arr_f[$i])!='')
									{
										$t = explode(",",$arr_f[$i]);
										$query_trans = $link->query(
											"INSERT INTO 
												store_eod_textfile_transactions
											(
												seodtt_eod_id, 
												seodtt_barcode, 
												seodtt_line, 
												seodtt_creditlimit, 
												seodtt_credpuramt, 
												seodtt_addonamt,
												seodtt_balance, 
												seodtt_transno, 
												seodtt_timetrnx, 
												seodtt_bu, 
												seodtt_terminalno, 
												seodtt_ackslipno, 
												seodtt_crditpurchaseamt
											)
											VALUES 
											(
												'$last_insert',
												'".$value['ver_barcode']."',
												'".$t[0]."',
												'".$t[1]."',
												'".$t[2]."',
												'".$t[3]."',
												'".$t[4]."',
												'".$t[5]."',
												'".$t[6]."',
												'".$t[7]."',
												'".$t[8]."',
												'".$t[9]."',
												'".$t[10]."'
											)
										");

										if(!$query_trans)
										{
											$msg = $link->error;
											$errquery = true;
											break;	
										}
									}									
								} // $arr_f[$i]>7
							} // end for

							if($errquery)
							{
								$haserror = true;
								break;
							}
							else 
							{
								$query_eod_ins = $link->query(
									"INSERT INTO 
										store_eod_items
									(
									    st_eod_barcode, 
									    st_eod_trid
									) 
									VALUES 
									(
									    '".$value['ver_barcode']."',
									    '$last_insert'
									)
								");

								if(!$query_eod_ins)
								{
									$msg = $link->error;
									$haserror = true;
									break;
								}

								$query_update_vergc = $link->query(
									"UPDATE 
									store_verification 
								SET 
									vs_tf_eod='1' 
								WHERE 
									vs_barcode='".$value['ver_barcode']."'
								");

								if(!$query_update_vergc)
								{
									$msg = $link->error;
									$haserror = true;
									break;
								}

								if(file_exists($file.'.BAK'))
								{
									//$filebak = $file.'.BAK';
									if(copy($file,$archivefolder.'\\'.$value['ver_textfilename'].'.BAK'))
									{
										if (!unlink($verificationfolder . '\\' .$value['ver_textfilename'].'.BAK')){
											$msg = 'Error deleting '.$value['ver_textfilename'].'.BAK texfile';
											$haserror = true;
											break;												
										}															
									}
									else 
									{
										$msg = 'Error copying '.$value['ver_textfilename'].' texfile';
										$haserror = true;
										break;	
									}

								}

								if(copy($file,$archivefolder.'\\'.$value['ver_textfilename']))
								{
									if (!unlink($verificationfolder . '\\' .$value['ver_textfilename'])){
										$msg = 'Error deleting '.$value['ver_textfilename'].' texfile';
										$haserror = true;
										break;												
									}															
								}
								else 
								{
									$msg = 'Error copying '.$value['ver_textfilename'].' texfile';
									$haserror = true;
									break;	
								}
							}

			            }
					}

					if($haserror)
					{
						$stat = false;
					}
					else
					{
						$stat = true;
					}
				}
			}
		}


		return array($stat,$msg);

	}

	function getEODdetails($link,$storeid,$id)
	{
		$query = $link->query(
			"SELECT 
				`store_eod`.`steod_datetime`,
				`users`.`firstname`,
				`users`.`lastname`
			FROM 
				`store_eod` 
			INNER JOIN
				`users`
			ON
				`users`.`user_id` = `store_eod`.`steod_by`
			WHERE 
				`steod_id`='$id'
			AND
				`steod_storeid`='$storeid'
		");

		if($query)
		{
			return $row = $query->fetch_object();
		}
		else 
		{
			return $link->error;
		}
	}

	function getEODdetailsIT($link,$id)
	{
		$query = $link->query(
			"SELECT 
				`store_eod`.`steod_datetime`,
				`users`.`firstname`,
				`users`.`lastname`
			FROM 
				`store_eod` 
			INNER JOIN
				`users`
			ON
				`users`.`user_id` = `store_eod`.`steod_by`
			WHERE 
				`steod_id`='$id'
		");

		if($query)
		{
			return $row = $query->fetch_object();
		}
		else 
		{
			return $link->error;
		}
	}

	function verifiedAndUsedNumGC($link,$storeid,$id)
	{		
		$query = $link->query(
			"SELECT 
				IFNULL(COUNT(vs_tf_used),0) as cnt 
			FROM 
				store_verification 
			WHERE 
				vs_store = '$storeid'
			AND
				vs_tf_used = '*'
			AND
				(vs_tf_eod = '$id'
			OR
				vs_tf_eod2= '$id'
			)
					
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->cnt;
		}
		else 
		{
			return $link->error;
		}
	}

	function checkIfGCAlreadySold($link,$gc)
	{
		$query_sold = $link->query(
			"SELECT 
				`store_received_gc`.`strec_barcode`,
				`transaction_stores`.`trans_datetime`,
				`stores`.`store_name`
			FROM 
				`store_received_gc` 
			INNER JOIN
				`transaction_sales`
			ON
				`transaction_sales`.`sales_barcode` = `store_received_gc`.`strec_barcode`
			INNER JOIN
				`transaction_stores`
			ON
				`transaction_stores`.`trans_sid` = `transaction_sales`.`sales_transaction_id`
			INNER JOIN
				`stores`
			ON
				`stores`.`store_id` = `transaction_stores`.`trans_store`
			WHERE 
				`store_received_gc`.`strec_barcode` = '$gc'
			AND
				`store_received_gc`.`strec_sold`='*'
			AND
				`store_received_gc`.`strec_return`=''
			ORDER BY
				`transaction_sales`.`sales_id`
			LIMIT 1
		");

		if($query_sold)
		{
			$row = $query_sold->fetch_object();
			return $row;
		}
		else 
		{
			return $link->error;
		}
	}

	function checkIfGCisBeamAndGo($link,$gc)
	{
		$query_bng = $link->query(
			"SELECT 
				`store_received_gc`.`strec_barcode`,
				`store_received_gc`.`strec_storeid`,
				`stores`.`store_name`
			FROM 
				`store_received_gc`		
			INNER JOIN
				`stores`
			ON
				`stores`.`store_id` = `store_received_gc`.`strec_storeid`
			WHERE 
				`store_received_gc`.`strec_barcode` = '$gc'
			AND
				`store_received_gc`.`strec_sold`=''
			AND
				`store_received_gc`.`strec_return`=''
			AND
				`store_received_gc`.`strec_bng_tag`='*'
			ORDER BY
				`store_received_gc`.`strec_id`
			LIMIT 1
		");

		if($query_bng)
		{
			$row = $query_bng->fetch_object();
			return $row;
		}
		else 
		{
			return $link->error;
		}
	}

	function checkIFGCAlreadyVerified($link,$gc)
	{
		$query = $link->query(
			"SELECT 
				`store_verification`.`vs_barcode`,
				`store_verification`.`vs_tf_used`,
				`store_verification`.`vs_tf_balance`,
				`store_verification`.`vs_date`,
				`store_verification`.`vs_time`,
				`store_verification`.`vs_store`,
				`stores`.`store_name`,
				`users`.`firstname`,
				`users`.`lastname`,
				`customers`.`cus_fname`,
				`customers`.`cus_lname`,
				`store_verification`.`vs_cn`
			FROM 
				`store_verification` 
			INNER JOIN
				`stores`
			ON
				`stores`.`store_id`  = `store_verification`.`vs_store`
			INNER JOIN
				`users`
			ON
				`users`.`user_id` = `store_verification`.`vs_by`
			INNER JOIN
				`customers`
			ON
				`customers`.`cus_id` = `store_verification`.`vs_cn`
			WHERE 
				`store_verification`.`vs_barcode`='$gc'
			ORDER BY
				`store_verification`.`vs_id`
			DESC
			LIMIT 1
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row;
		}
		else 
		{
			return $link->error;
		}
	}

	function checkIfPromoGC($link,$gc)
	{
		$query = $link->query(
			"SELECT
				`promo_gc`.`prom_barcode`,
				DATE(`promo`.`promo_expire`) as expire
			FROM 
				`promo_gc` 
			INNER JOIN
				`promo`
			ON
				`promo`.`promo_id` = `promo_gc`.`prom_promoid`
			WHERE 
				`promo_gc`.`prom_barcode` = '$gc'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row;
		}
		else 
		{
			return $link->error;
		}
	}

	function getCustomerDetailsByID($link,$cusid)
	{
		$query = $link->query(
			"SELECT 
			    `cus_fname`,
			    `cus_mname`,
				`cus_lname`
			FROM
				`customers`
			WHERE 
				`cus_id` = '".$cusid."'
			LIMIT 1
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row;
		}
		else 
		{
			$response['msg'] = $link->error;
		}
	}

	function getCustomerInfoVerification($link,$barcode)
	{
		$query = $link->query(
			"SELECT 
				customers.cus_fname,
			    customers.cus_lname,
			    customers.cus_mname,
			    customers.cus_id,
			    stores.store_name,
			    store_verification.vs_tf_denomination,
			    store_verification.vs_store
			FROM 
				store_verification 
			LEFT JOIN
				customers
			ON
				customers.cus_id = store_verification.vs_cn
			LEFT JOIN
				stores
			ON
				stores.store_id = store_verification.vs_store
			WHERE 
				store_verification.vs_barcode='$barcode'	
			LIMIT 1
		");

		if($query)
		{
			return $row = $query->fetch_object();			
		}
		else 
		{
			return $link->error;
		}
	}

	function checkforRevalidated($link,$gc)
	{
		$query = $link->query(
			"SELECT
				transaction_revalidation.reval_id,
				transaction_stores.trans_store,
				transaction_stores.trans_datetime,
				transaction_revalidation.reval_revalidated,
				stores.store_name
			FROM 
				transaction_revalidation
			INNER JOIN
				transaction_stores
			ON
				transaction_stores.trans_sid = transaction_revalidation.reval_trans_id
			INNER JOIN
				stores
			ON
				stores.store_id = transaction_stores.trans_store
			WHERE
				transaction_revalidation.reval_barcode='$gc'
			AND
				DATE(transaction_stores.trans_datetime) = CURDATE()
			ORDER BY
				transaction_revalidation.reval_id
			DESC
			LIMIT 1
		");

		if($query)
		{	
			$row = $query->fetch_object();
			return $row;
		}
		else 
		{
			return $link->error;
		}
	}

	function readTextfile($filename)
	{
		// $filename1 = 'textfiles/validation/'.$gc.'.txt';
		// $filename2 = 'textfiles/gctextfile_archives/'.$gc.'.txt';
		$table = '';
		if (file_exists($filename)) 
		{
			//read texfile
			$arr_f = [];
			$arr_t = [];					
			$r_f = fopen($filename,'r');
			while(!feof($r_f)) 
			{	
				if(fgetc($r_f) !='')
				{		
					$arr_f[] = fgets($r_f);
				}
			}
			fclose($r_f);
			for ($i=0; $i < count($arr_f); $i++) { 									
				if($arr_f[$i]==0)
				{
					$c = explode(",",$arr_f[$i]);
					$txtfile_cusid = trim($c[1]);
				}

				if($arr_f[$i]==4)
				{
					$d = explode(",",$arr_f[$i]);
					$txtfile_balance = trim($d[1]);
				}

				if($arr_f[$i]>7)
				{
					if(trim($arr_f[$i])!='')
					{
						$t = explode(",",$arr_f[$i]);
						$arr_t[] =  array(
							'pur_amt' => $t[2],
							'add_amt' => $t[3],
							'remain_amt' => $t[4],
							'trans_num'  => $t[5],
							'time_credit'=> $t[6],
							'business_unit' => $t[7],
							'terminal_unit' => $t[8],
							'acslip_no' => $t[9]
						);
					}
				}
			}

			$trans= '';
			if(count($arr_t)>0)
			{
				foreach ($arr_t as $key => $value) {											
					$time = sprintf("%04d", $value['time_credit']);
					$arr = str_split($time, 2);
					$time = $arr[0].':'.$arr[1].':00';
					$time = date('h:i a', strtotime($time));
					$trans.= '<tr>';
					$trans.='<td>'.$value['trans_num'].'</td>';
					$trans.='<td>'.number_format($value['pur_amt'],2).'</td>';
					$trans.='<td>'.number_format($value['remain_amt'],2).'</td>';
					$trans.='<td>'.$value['business_unit'].'</td>';
					$trans.='<td>'.$time.'</td>';
					$trans.='</tr>';											
				}

				$table = '
				<table class="table">
					<thead>
						<tr>
							<th>Trans No.</th>
							<th>Purchase Amt</th>
							<th>Balance</th>
							<th>Bus Unit</th>
							<th>Time</th>
						<tr>												
					</thead>
					<tbody>'.$trans.'</tbody>
				</table>';
			}
			else 
			{
				$table ='';
			}	
		}
		return $table;
	}

	function checkIfTextfileExist($filename)
	{
		if (file_exists($filename)) 
		{
			return true;
		}
		else 
		{
			return false;
		}
	}

	function getTimestamp()
	{
		$date = new DateTime();
		$currentTime = $date->getTimestamp();
		return $currentTime;
	}

	function getBudgetRequestForUpdate($link)
	{		
		$query = $link->query(
			"SELECT 
				`budget_request`.`br_request`,
				`budget_request`.`br_no`,
				`budget_request`.`br_requested_by`,
				`users`.`firstname`,
				`users`.`lastname`,
				`budget_request`.`br_remarks`,
				`budget_request`.`br_file_docno`,
				`budget_request`.`br_id`,
				`budget_request`.`br_requested_at`,
				`budget_request`.`br_requested_needed`,
				`access_page`.`title`
			FROM 
				`budget_request`
			INNER JOIN
				`users`
			ON
				`users`.`user_id` = `budget_request`.`br_requested_by`
			INNER JOIN
				`access_page`
			ON
				`access_page`.`access_no` = `users`.`usertype`
			WHERE
				`budget_request`.`br_request_status`='0'
			ORDER BY
				`budget_request`.`br_id`
			LIMIT 1
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row;
		}
		else 
		{
			return $link->error;
		}
	}

	function getBudgetRequestForUpdateByDept($link,$dept)
	{
		if($dept==2)
		{
			$type=1;
		}
		elseif ($type=6)
		{
			$type=2;
		}

		$query = $link->query(
			"SELECT 
				`budget_request`.`br_request`,
				`budget_request`.`br_no`,
				`budget_request`.`br_requested_by`,
				`users`.`firstname`,
				`users`.`lastname`,
				`budget_request`.`br_remarks`,
				`budget_request`.`br_file_docno`,
				`budget_request`.`br_id`,
				`budget_request`.`br_requested_at`,
				`budget_request`.`br_requested_needed`,
				`access_page`.`title`,
				`budget_request`.`br_group`,
				`budget_request`.`br_preapprovedby`
			FROM 
				`budget_request`
			INNER JOIN
				`users`
			ON
				`users`.`user_id` = `budget_request`.`br_requested_by`
			INNER JOIN
				`access_page`
			ON
				`access_page`.`access_no` = `users`.`usertype`
			WHERE
				`budget_request`.`br_request_status`='0'
			AND
				`budget_request`.`br_type`='$type'
			ORDER BY
				`budget_request`.`br_id`
			LIMIT 1
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row;
		}
		else 
		{
			return $link->error;
		}		
	}

	function getAssignatories($link,$dept)
	{
		$rows = [];
		$query = $link->query(
			"SELECT
				`assig_position`,
				`assig_name`,
				`assig_id`
			FROM 
				`assignatories` 
			WHERE 
				`assig_dept`='$dept'
			OR
				`assig_dept`='1'
		");

		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;				
			}
			return $rows;
		}
		else 
		{
			return $link->error;
		}
	}

	function getAllApprovedBudgetRequest($link)
	{
	   $rows = [];
	   $query = $link->query(
	   	"SELECT 
			budget_request.br_id,
			budget_request.br_no,
			budget_request.br_request,
			budget_request.br_requested_at,
			budget_request.br_requested_by,
			approved_budget_request.abr_approved_by,
			approved_budget_request.abr_checked_by,
			approved_budget_request.abr_approved_at,
			approved_budget_request.abr_prepared_by,
			request_user.firstname as fnamerequest,
			request_user.lastname as lnamerequest,
			approved_by.firstname as fnameapproved,
			approved_by.lastname as lnameapproved,
			access_page.title
		FROM 
			budget_request
		INNER JOIN
			approved_budget_request
		ON
			approved_budget_request.abr_budget_request_id = budget_request.br_id
		INNER JOIN
			users as request_user
		ON
			request_user.user_id = budget_request.br_requested_by
		INNER JOIN
			users as approved_by
		ON
			approved_by.user_id = approved_budget_request.abr_prepared_by
		INNER JOIN
			users
		ON
			users.user_id = budget_request.br_requested_by
		INNER JOIN
			access_page
		ON
			access_page.access_no = users.usertype
		WHERE 
			budget_request.br_request_status='1'
		ORDER BY
			budget_request.br_id
		");

		if($query)
		{
			while ( $row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $link->error;
		}
	}

	function getApprovedBudgetRequestByIDDetails($link,$brid)
	{
	   $query = $link->query(
	   		"SELECT 
				`budget_request`.`br_id`,
				`budget_request`.`br_no`,
				`budget_request`.`br_request`,
				`budget_request`.`br_requested_at`,
				`budget_request`.`br_requested_by`,
				`budget_request`.`br_group`,
				`approved_budget_request`.`abr_approved_by`,
				`approved_budget_request`.`abr_checked_by`,
				`approved_budget_request`.`abr_approved_at`,
				`approved_budget_request`.`abr_prepared_by`,
				`request_user`.`firstname` as fnamerequest,
				`request_user`.`lastname` as lnamerequest,
				`approved_by`.`firstname` as fnameapproved,
				`approved_by`.`lastname` as lnameapproved,
				`budget_request`.`br_file_docno`,
				`budget_request`.`br_remarks`,
				`approved_budget_request`.`abr_file_doc_no`,
				`approved_budget_request`.`approved_budget_remark`,
				`access_page`.`title`
			FROM 
				`budget_request`
			INNER JOIN
				`approved_budget_request`
			ON
				`approved_budget_request`.`abr_budget_request_id` = `budget_request`.`br_id`
			INNER JOIN
				`users` as `request_user`
			ON
				`request_user`.`user_id` = `budget_request`.`br_requested_by`
			INNER JOIN
				`users` as `approved_by`
			ON
				`approved_by`.`user_id` = `approved_budget_request`.`abr_prepared_by`
			INNER JOIN
				`users`
			ON
				`users`.`user_id` = `budget_request`.`br_requested_by`
			INNER JOIN
				`access_page`
			ON
				`access_page`.`access_no` = `users`.`usertype`
			WHERE 
				`budget_request`.`br_request_status`='1'
			AND
				`budget_request`.`br_id` = '$brid'
	   	");

		if($query)
		{
			$row = $query->fetch_object();
			return $row;
		}
		else 
		{
			return $link->error;
		}
	}

	function getAllCancelledBudgetRequest($link)
	{
		$rows = [];
		$query = $link->query(
		"SELECT
			`budget_request`.`br_id`,
			`budget_request`.`br_no`,
			`budget_request`.`br_requested_at`,
			`budget_request`.`br_request`,
			`request_user`.`firstname` as fnamerequest,
			`request_user`.`lastname` as lnamerequest,
			`cancelled_budget_request`.`cdreq_at`,
			`cancelled_user`.`firstname` as fnamecancelled,
			`cancelled_user`.`lastname`	as lnamecancelled		
		FROM 
			`budget_request`
		INNER JOIN
			`cancelled_budget_request`
		ON
			`cancelled_budget_request`.`cdreq_req_id` = `budget_request`.`br_id`
		INNER JOIN
			`users` as `request_user`
		ON
			`request_user`.`user_id` = `budget_request`.`br_requested_by` 
		INNER JOIN
			`users` as `cancelled_user`
		ON
			`cancelled_user`.`user_id` = `cancelled_budget_request`.`cdreq_by`
		WHERE
			`budget_request`.`br_request_status`='2'
		");

		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $link->error;
		}
	}

	function getCancelledBudgetRequestByID($link,$id)
	{
		$query = $link->query(
		"SELECT
			`budget_request`.`br_id`,
			`budget_request`.`br_no`,
			`budget_request`.`br_requested_at`,
			`budget_request`.`br_request`,
			`budget_request`.`br_file_docno`,
			`budget_request`.`br_remarks`,
			`request_user`.`firstname` as fnamerequest,
			`request_user`.`lastname` as lnamerequest,
			`cancelled_budget_request`.`cdreq_at`,
			`cancelled_user`.`firstname` as fnamecancelled,
			`cancelled_user`.`lastname`	as lnamecancelled		
		FROM 
			`budget_request`
		INNER JOIN
			`cancelled_budget_request`
		ON
			`cancelled_budget_request`.`cdreq_req_id` = `budget_request`.`br_id`
		INNER JOIN
			`users` as `request_user`
		ON
			`request_user`.`user_id` = `budget_request`.`br_requested_by` 
		INNER JOIN
			`users` as `cancelled_user`
		ON
			`cancelled_user`.`user_id` = `cancelled_budget_request`.`cdreq_by`
		WHERE
			`budget_request`.`br_request_status`='2'
		AND
			`budget_request`.`br_id`='$id'
		LIMIT 1
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row;
		}
		else 
		{
			return $link->error;
		}
	}

	function getPendingProductionRequest($link)
	{
		$query = $link->query(
			"SELECT
				`production_request`.`pe_id`,
				`users`.`firstname`,
				`users`.`lastname`,
				`production_request`.`pe_file_docno`,
				`production_request`.`pe_date_needed`,
				`production_request`.`pe_remarks`,
				`production_request`.`pe_num`,
				`production_request`.`pe_date_request`
				
			FROM 
				`production_request`
			INNER JOIN
				`users`
			ON
				`users`.`user_id` = `production_request`.`pe_requested_by`
			WHERE 
				`pe_status`='0'
			ORDER BY 
				`pe_id`
			DESC
			LIMIT 1
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row;
		}
		else 
		{
			return $link->error;
		}
	}

	function getPendingProductionRequestByDept($link,$dept)
	{
		$query = $link->query(
			"SELECT
				`production_request`.`pe_id`,
				`users`.`firstname`,
				`users`.`lastname`,
				`production_request`.`pe_file_docno`,
				`production_request`.`pe_date_needed`,
				`production_request`.`pe_remarks`,
				`production_request`.`pe_num`,
				`production_request`.`pe_date_request`,
				`production_request`.`pe_group`
				
			FROM 
				`production_request`
			INNER JOIN
				`users`
			ON
				`users`.`user_id` = `production_request`.`pe_requested_by`
			WHERE 
				`production_request`.`pe_status`='0'
			AND
				`users`.`usertype`='$dept'
			ORDER BY 
				`pe_id`
			DESC
			LIMIT 1
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row;
		}
		else 
		{
			return $link->error;
		}
	}

	function getNumofGCRequestBYProdID($link,$pid)
	{
		$rows = [];
		$query = $link->query(
		"SELECT 
			`production_request_items`.`pe_items_quantity`,
			`denomination`.`denomination`
		FROM 
			`production_request_items`
		INNER JOIN 
			`denomination`
		ON  
			`production_request_items`.`pe_items_denomination`=`denomination`.`denom_id`
		WHERE 
			`production_request_items`.`pe_items_request_id`= '$pid'
		");
		if($query)
		{	
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $link->error;
		}
	}

	function ledgerNumber($link)
	{
		$query = $link->query(
		"SELECT 
			`bledger_no` 
		FROM 
			`ledger_budget` 
		ORDER BY 
			`bledger_id` 
		DESC LIMIT 1
		");

		if($query)
		{
			if(($query->num_rows) > 0)
			{
				$row = $query->fetch_object();
				return $row->bledger_no + 1;
			}
			else 
			{
				return 1;
			}
		}
		else 
		{
			return $link->error;
		}
	}

	function checkledgernumber($link)
	{
		$query = $link->query(
			"SELECT
				`cledger_no`
			FROM 
				`ledger_check` 
			ORDER BY
				`cledger_id`
			DESC
		");
		if($query)
		{
			if(($query->num_rows) > 0)
			{
				$row = $query->fetch_object();
				return $row->cledger_no + 1;
			}
			else 
			{
				return 1;
			}
		}
		else 
		{
			return $link->error;
		}
	}

	function ledgerCheckRequisitionApproval($link,$id)
	{
		$query = $link->query(
			"SELECT 
				IFNULL(SUM(`production_request_items`.`pe_items_quantity` * `denomination`.`denomination`),0) as total
			FROM 
				`production_request_items` 
			INNER JOIN
				`denomination`
			ON
				`denomination`.`denom_id` = `production_request_items`.`pe_items_denomination`
			WHERE 
				`production_request_items`.`pe_items_request_id`='$id'

		");
		if($query)
		{
			$row = $query->fetch_object();
			return $row->total;
		}
		else 
		{
			return $link->error;
		}
	}

	function approvedProductionRequestDetails($link,$prid)
	{
	    $query = $link->query(
	    "SELECT
			`production_request`.`pe_id`, 
			`production_request`.`pe_num`,
			`production_request`.`pe_requested_by`,
			`production_request`.`pe_date_request`,
			`production_request`.`pe_date_needed`,
			`production_request`.`pe_file_docno`,
			`production_request`.`pe_remarks`,
			`production_request`.`pe_generate_code`,
			`production_request`.`pe_requisition`,
			`approved_production_request`.`ape_approved_by`,
			`approved_production_request`.`ape_remarks`,
			`approved_production_request`.`ape_approved_at`,
			`approved_production_request`.`ape_preparedby`,
			`approved_production_request`.`ape_checked_by`,
			`approved_production_request`.`ape_file_doc_no`,
			`requestby`.`firstname` as frequest,
			`requestby`.`lastname` as lrequest,
			`approvedby`.`firstname` as fapproved,
			`approvedby`.`lastname` as lapproved,
			`production_request`.`pe_type`,
			`production_request`.`pe_group`
		FROM 
			`production_request` 
		INNER JOIN
			`approved_production_request`
		ON
			`production_request`.`pe_id` = `approved_production_request`.`ape_pro_request_id`
		INNER JOIN
			`users` as `requestby`
		ON
			`requestby`.`user_id` = `production_request`.`pe_requested_by`
		INNER JOIN
			`users` as `approvedby`
		ON
			`approvedby`.`user_id` = `approved_production_request`.`ape_preparedby`
		WHERE 
			`production_request`.`pe_id` = '$prid'
	    ");

	    if($query)
	    {
	        $row = $query->fetch_object();
	        return $row;
	    } 
	    else 
	    {
	        return $link->error;
	    }
	}

	function getAllCancelledProductionRequest($link)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				production_request.pe_id,
				production_request.pe_num,
				production_request.pe_date_request,
				production_request.pe_date_needed,
				lreq.firstname as lreqfname,
				lreq.lastname as lreqlname,
				cancelled_production_request.cpr_at,
				lcan.firstname as lcanfname,
				lcan.lastname as lcanlname
			FROM 
				cancelled_production_request
			INNER JOIN
				production_request
			ON
				production_request.pe_id = cancelled_production_request.cpr_pro_id
			INNER JOIN
				users as lreq
			ON
				lreq.user_id = production_request.pe_requested_by
			INNER JOIN
				users as lcan
			ON
				lcan.user_id = cancelled_production_request.cpr_by
			ORDER BY
				cancelled_production_request.cpr_id
			DESC
		");
		// $query = $link->query(
		// 	"SELECT 
		// 		`production_request`.`pe_id`,
		// 		`production_request`.`pe_num`,
		// 		`production_request`.`pe_date_request`,
		// 		`production_request`.`pe_date_needed`,
		// 		`lreq`.`firstname` as lreqfname,
		// 		`lreq`.`lastname` as lreqlname,
		// 		`cancelled_production_request`.`cpr_at`,
		// 		`lcan`.`firstname` as lcanfname,
		// 		`lcan`.`lastname` as lcanlname
		// 	FROM 
		// 		`cancelled_production_request` 
		// 	INNER JOIN
		// 		`production_request`
		// 	ON
		// 		`cancelled_production_request`.`cpr_pro_id`
		// 	INNER JOIN
		// 		`users` as lreq
		// 	ON
		// 		`lreq`.`user_id` = `production_request`.`pe_requested_by`
		// 	INNER JOIN
		// 		`users` as lcan
		// 	ON
		// 		`lcan`.`user_id` = `cancelled_production_request`.`cpr_by`
		// 	WHERE 
		// 		`pe_status`='2'	
		// ");

		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $link->error;
		}
	}

	function getAllCancelledProductionRequestByID($link,$id)
	{
		$query = $link->query(
			"SELECT 
				`production_request`.`pe_id`,
				`production_request`.`pe_num`,
				`production_request`.`pe_date_request`,
				`production_request`.`pe_date_needed`,
				`lreq`.`firstname` as lreqfname,
				`lreq`.`lastname` as lreqlname,
				`cancelled_production_request`.`cpr_at`,
				`lcan`.`firstname` as lcanfname,
				`lcan`.`lastname` as lcanlname,
				`production_request`.`pe_remarks`,
				`production_request`.`pe_file_docno`,
				`cancelled_production_request`.`cpr_isrequis_cancel`
			FROM 
				`production_request` 
			INNER JOIN
				`cancelled_production_request`
			ON
				`cancelled_production_request`.`cpr_pro_id`=`production_request`.`pe_id`
			INNER JOIN
				`users` as lreq
			ON
				`lreq`.`user_id` = `production_request`.`pe_requested_by`
			INNER JOIN
				`users` as lcan
			ON
				`lcan`.`user_id` = `cancelled_production_request`.`cpr_by`
			WHERE 
				`production_request`.`pe_status`='2'
			AND
				`production_request`.`pe_id`='$id'
			LIMIT 1	
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row;
		}
		else 
		{
			return $link->error;
		}
	}

	function getReceivedDetailsTotalAndCount($link,$id)
	{
		$query = $link->query(
		"SELECT
			IFNULL(SUM(`denomination`.`denomination`),0.00) as total,
			IFNULL(COUNT(`custodian_srr_items`.`cssitem_barcode`),0) as cnt
		FROM 
			`custodian_srr_items`
		INNER JOIN
			`gc`
		ON
			`gc`.`barcode_no` = `custodian_srr_items`.`cssitem_barcode`
		INNER JOIN
			`denomination`
		ON
			`denomination`.`denom_id` = `gc`.`denom_id`
		WHERE 
			`cssitem_recnum`='$id'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row;
		}
		else 
		{
			return $link->error;
		}
	}

	function getLedgerCheck($link)
	{
		$rows = [];
		$query = $link->query(
			"SELECT
				`ledger_check`.`cledger_no`,
				`ledger_check`.`cledger_datetime`,
				`ledger_check`.`cledger_type`,
				`ledger_check`.`cledger_desc`,
				`ledger_check`.`cdebit_amt`,
				`ledger_check`.`ccredit_amt`,
				`ledger_check`.`c_posted_by`,
				`users`.`firstname`,
				`users`.`lastname`
			FROM 
				`ledger_check`
			INNER JOIN
				`users`
			ON
				`users`.`user_id` = `ledger_check`.`c_posted_by`
			ORDER BY
				`ledger_check`.`cledger_id`
			ASC 
		");

		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $link->error;
		}
	}

	function releasedGCTotalAmount($link,$id)
	{
		$query = $link->query(
			"SELECT
				IFNULL(SUM(`denomination`.`denomination`),0) as total
			FROM 
				`temp_release` 
			INNER JOIN
				`denomination`
			ON
				`denomination`.`denom_id` = `temp_release`.`temp_rdenom`
			WHERE
				`temp_release`.`temp_relno`='$id'
		");
	}

	function deleteById($link,$table,$where,$id)
	{
		$query = $link->query(
			"DELETE FROM 
				$table
			WHERE 
				$where = '$id'
		");

		if($query)
		{
			return true;
		} 
		else 
		{
			return false;
		}
	}

	function getAllReleasedGCByID($link,$id)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				`denomination`.`denomination`,
				`gc_release`.`re_barcode_no`,
				`gc_release`.`rel_id`,
				`denomination`.`denom_id`
			FROM 
				`gc_release`
			INNER JOIN
				`gc`
			ON
				`gc`.`barcode_no` = `gc_release`.`re_barcode_no`
			INNER JOIN
				`denomination`
			ON
				`denomination`.`denom_id` = `gc`.`denom_id`
			WHERE 
				`gc_release`.`rel_num`='$id'
			GROUP BY
				`denomination`.`denomination`
			ORDER BY
				`denomination`.`denom_id`
		");

		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $link->error;
		}
	}

	function getAllReleasedGCByIDPromo($id,$link)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				IFNULL(COUNT(promo_gc_release_to_items.prreltoi_id),0) as cnt,
				IFNULL(SUM(denomination.denomination),0.00) as total,
				denomination.denomination,
				gc.denom_id
			FROM 
				promo_gc_release_to_items 
			INNER JOIN
				gc
			ON
				gc.barcode_no = promo_gc_release_to_items.prreltoi_barcode
			INNER JOIN
				denomination
			ON
				denomination.denom_id = gc.denom_id
			WHERE 
				promo_gc_release_to_items.prreltoi_relid='$id'
			GROUP BY
				denomination.denomination
			ORDER BY
				denomination.denomination
			ASC
		");

		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}

			return $rows;
		}
		else 
		{
			return $link->error;
		}
	}

	function getAllInsRel($link,$id)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				denomination.denomination,
				IFNULL(COUNT(denomination.denom_id),0) as cnt,
				IFNULL(SUM(denomination.denomination),0) as sumdenom,
				denomination.denom_id
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
				institut_transactions_items.instituttritems_trid = '".$id."'
			GROUP BY
				denomination.denomination
			ORDER BY
				denomination.denomination
			ASC
		");

		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}

			return $rows;
		}
		else 
		{
			return $link->error;
		}
	}

	function getDenomReleasingReportById($link,$relnum,$den_id)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				`gc_release`.`re_barcode_no`,
				`denomination`.`denomination`,
				`denomination`.`denom_id`
			FROM 
				`gc_release`
			INNER JOIN
				`gc`
			ON
				`gc`.`barcode_no` = `gc_release`.`re_barcode_no`
			INNER JOIN
				`denomination`
			ON
				`gc`.`denom_id` = `denomination`.`denom_id`
			WHERE 
				`gc_release`.`rel_num`='$relnum'
			AND
				`denomination`.`denom_id`='$den_id'
		");
		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}
			return $rows;
		}
	}

	function getDenomReleasingReportByIdPromo($link,$relid,$denid)
	{
		$rows = [];
		$query = $link->query(
			"SELECT
				promo_gc_release_to_items.prreltoi_barcode
				
			FROM 
				promo_gc_release_to_items
			INNER JOIN
				gc
			ON
				gc.barcode_no = promo_gc_release_to_items.prreltoi_barcode
			INNER JOIN
				denomination
			ON
				denomination.denom_id = gc.denom_id
			WHERE 
				promo_gc_release_to_items.prreltoi_relid='$relid'
			AND
				denomination.denom_id='$denid'
		");

		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}

			return $rows;
		}
		else 
		{
			die($link->error);
		}
	}

	function getReleasedBarcodeByDenom($link,$relid,$denid)
	{
		$rows = [];

		$query = $link->query(
			"SELECT 
				transfer_request_served_items.trs_barcode,
				denomination.denomination
			FROM 
				transfer_request_served_items 
			INNER JOIN
				gc
			ON
				gc.barcode_no = transfer_request_served_items.trs_barcode
			INNER JOIN
				denomination
			ON
				denomination.denom_id = gc.denom_id
			WHERE 
				transfer_request_served_items.trs_served='".$relid."'
			AND
				denomination.denom_id = '".$denid."'
		");

		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}

			return $rows;
		}
		else 
		{
			die($link->error);
		}
	}

	function getDenomReleasingReportByIdIns($link,$relid,$denid)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				institut_transactions_items.instituttritems_barcode
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
				institut_transactions_items.instituttritems_trid='".$relid."'
			AND
				denomination.denom_id = '".$denid."'	
		");

		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}

			return $rows;
		}
		else 
		{
			die($link->error);
		}
	}

	function reportHeaderTreasuryReleasedGC($link,$id)
	{
		$query = $link->query(
			"SELECT 
				`approved_gcrequest`.`agcr_request_id`,
				`approved_gcrequest`.`agcr_approved_at`,
				`approved_gcrequest`.`agcr_paymenttype`,
				`approved_gcrequest`.`agcr_recby`,
				`stores`.`store_name`,				
				`approved_gcrequest`.`agcr_stat`,
				`user_prepared`.`firstname` as `fnameprepared`,
				`user_prepared`.`lastname` as `lnameprepared`,
				`approved_gcrequest`.`agcr_approvedby`,
				`approved_gcrequest`.`agcr_checkedby`,
				`approved_gcrequest`.`agcr_stat`
			FROM 
				`approved_gcrequest` 
			INNER JOIN
				`store_gcrequest`
			ON
				`store_gcrequest`.`sgc_id` = `approved_gcrequest`.`agcr_request_id`
			INNER JOIN
				`stores`
			ON
				`stores`.`store_id` = `store_gcrequest`.`sgc_store`
			INNER JOIN
				`users` as `user_prepared`
			ON
				`user_prepared`.`user_id` = `approved_gcrequest`.`agcr_preparedby`
			WHERE 
				`approved_gcrequest`.`agcr_id`='$id'
		");

		if($query)
		{
			return $row = $query->fetch_object();
		}
	}

	function getStorePaymentDetails($link,$id)
	{
		$query = $link->query(
			"SELECT 
				insp_trid,
			    insp_paymentcustomer,
			    institut_bankname,
			    institut_bankaccountnum,
			    institut_checknumber,
			    institut_amountrec,
			    institut_jvcustomer
			FROM 
				institut_payment 
			WHERE 
				insp_trid = '$id'
			AND
				insp_paymentcustomer='stores'
		");

		if($query)
		{
			return $row = $query->fetch_object();
		}
	}

	function getReleasedDetailsTotalAndCount($link,$id)
	{
		$query = $link->query(
		"SELECT
			IFNULL(SUM(`denomination`.`denomination`),0.00) as total,
			IFNULL(COUNT(`gc_release`.`re_barcode_no`),0) as cnt
		FROM 
			`gc_release`
		INNER JOIN
			`gc`
		ON
			`gc`.`barcode_no` = `gc_release`.`re_barcode_no`
		INNER JOIN
			`denomination`
		ON
			`denomination`.`denom_id` = `gc`.`denom_id`
		WHERE 
			`gc_release`.`rel_num`='$id'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row;
		}
		else 
		{
			return $link->error;
		}
	}

	function totalGCReceivedByStore($link,$recnum,$storeid,$user)
	{
		$query = $link->query("SELECT 
			IFNULL(SUM(`denomination`.`denomination`),0.00) as total
		FROM 
			`temp_receivestore` 
		INNER JOIN
			`denomination`
		ON
			`denomination`.`denom_id` = `temp_receivestore`.`trec_denid`
		WHERE 
			`trec_recnum`='$recnum'
		AND
			`trec_store`='$storeid'
		AND
			`trec_by`='$user'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->total;
		}
		else 
		{
			return $link->error;
		}
	}

	function getLedgerStoreLastLedgerNumber($link,$storeid)
	{
		$query = $link->query(
			"SELECT 
				`sledger_no` 
			FROM 
				`ledger_store` 
			WHERE 
				`sledger_store`='$storeid'
			ORDER BY
				`sledger_id`
			DESC
			LIMIT 1
		");

		if($query)
		{
			if(($query->num_rows) > 0)
			{
				$row = $query->fetch_object();
				return $row->sledger_no + 1;
			}
			else 
			{
				return 1;
			}
		}
		else 
		{
			return $link->error;
		}
	}

	function getVerifiedGCWithTransaction($link,$storeid)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				`store_verification`.`vs_barcode`,
				`store_verification`.`vs_date`,
				`store_verification`.`vs_time`,
				`store_verification`.`vs_tf_balance`,
				`store_verification`.`vs_tf_eod`,
				`customers`.`cus_fname`,
				`customers`.`cus_lname`,
				`stores`.`store_name`,
				`users`.`firstname`,
				`users`.`lastname`
			FROM 
				`store_verification` 
			INNER JOIN
				`customers`
			ON
				`customers`.`cus_id` = `store_verification`.`vs_cn`
			INNER JOIN
				`stores`
			ON
				`stores`.`store_id` = `store_verification`.`vs_store`
			INNER JOIN
				`users`
			ON
				`users`.`user_id` = `store_verification`.`vs_by`
			WHERE 
				`vs_tf_used`='*'
			AND
				`vs_store`='$storeid'
			ORDER BY
				`vs_id`
			DESC
		");

		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row; 
			}
			return $rows;
		}
		else 
		{
			return $link->error;
		}
	}

	function getAllNavPOSTranx($link,$barcode)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				`seodtt_line`, 
				`seodtt_creditlimit`, 
				`seodtt_credpuramt`, 
				`seodtt_addonamt`, 
				`seodtt_balance`, 
				`seodtt_transno`, 
				`seodtt_timetrnx`, 
				`seodtt_bu`, 
				`seodtt_terminalno`, 
				`seodtt_ackslipno`, 
				`seodtt_crditpurchaseamt` 
			FROM 
				`store_eod_textfile_transactions` 
			WHERE 
				`seodtt_barcode`='$barcode'
			ORDER BY
				`seodtt_id`
			ASC
		");

		if($query)
		{
			while ( $row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $link->error;
		}
	}

	function getCustomerCodeLastVerification($link,$barcode)
	{
		$query = $link->query(
			"SELECT 
				`vs_cn` 
			FROM 
				`store_verification` 
			WHERE 
				`vs_barcode`='$barcode'
			ORDER BY
				`vs_id`
			DESC
			LIMIT 1
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->vs_cn;
		}
		else 
		{
			return $link->error;
		}
	}

	function get_ip_address() 
	{
	    // check for shared internet/ISP IP
	    if (!empty($_SERVER['HTTP_CLIENT_IP']) && validate_ip($_SERVER['HTTP_CLIENT_IP'])) {
	        return $_SERVER['HTTP_CLIENT_IP'];
	    }

	    // check for IPs passing through proxies
	    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	        // check if multiple ips exist in var
	        if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
	            $iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
	            foreach ($iplist as $ip) {
	                if (validate_ip($ip))
	                    return $ip;
	            }
	        } else {
	            if (validate_ip($_SERVER['HTTP_X_FORWARDED_FOR']))
	                return $_SERVER['HTTP_X_FORWARDED_FOR'];
	        }
	    }
	    if (!empty($_SERVER['HTTP_X_FORWARDED']) && validate_ip($_SERVER['HTTP_X_FORWARDED']))
	        return $_SERVER['HTTP_X_FORWARDED'];
	    if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && validate_ip($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
	        return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
	    if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && validate_ip($_SERVER['HTTP_FORWARDED_FOR']))
	        return $_SERVER['HTTP_FORWARDED_FOR'];
	    if (!empty($_SERVER['HTTP_FORWARDED']) && validate_ip($_SERVER['HTTP_FORWARDED']))
	        return $_SERVER['HTTP_FORWARDED'];

	    // return unreliable ip since all else failed
	    return $_SERVER['REMOTE_ADDR'];
	}

	/**
	 * Ensures an ip address is both a valid IP and does not fall within
	 * a private network range.
	 */
	function validate_ip($ip) 
	{
	    if (strtolower($ip) === 'unknown')
	        return false;

	    // generate ipv4 network address
	    $ip = ip2long($ip);

	    // if the ip is set and not equivalent to 255.255.255.255
	    if ($ip !== false && $ip !== -1) {
	        // make sure to get unsigned long representation of ip
	        // due to discrepancies between 32 and 64 bit OSes and
	        // signed numbers (ints default to signed in PHP)
	        $ip = sprintf('%u', $ip);
	        // do private network range checking
	        if ($ip >= 0 && $ip <= 50331647) return false;
	        if ($ip >= 167772160 && $ip <= 184549375) return false;
	        if ($ip >= 2130706432 && $ip <= 2147483647) return false;
	        if ($ip >= 2851995648 && $ip <= 2852061183) return false;
	        if ($ip >= 2886729728 && $ip <= 2887778303) return false;
	        if ($ip >= 3221225984 && $ip <= 3221226239) return false;
	        if ($ip >= 3232235520 && $ip <= 3232301055) return false;
	        if ($ip >= 4294967040) return false;
	    }
	    return true;
	}

	function get_client_ip() {
	    $ipaddress = '';
	    if (getenv('HTTP_CLIENT_IP'))
	        $ipaddress = getenv('HTTP_CLIENT_IP');
	    else if(getenv('HTTP_X_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	    else if(getenv('HTTP_X_FORWARDED'))
	        $ipaddress = getenv('HTTP_X_FORWARDED');
	    else if(getenv('HTTP_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_FORWARDED_FOR');
	    else if(getenv('HTTP_FORWARDED'))
	       $ipaddress = getenv('HTTP_FORWARDED');
	    else if(getenv('REMOTE_ADDR'))
	        $ipaddress = getenv('REMOTE_ADDR');
	    else
	        $ipaddress = 'UNKNOWN';
	    return $ipaddress;
	}

	function custodianreceivedgc($link)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				`custodian_srr`.`csrr_id`,
				`custodian_srr`.`csrr_receivetype`,
				`requisition_entry`.`requis_erno`,
				`custodian_srr`.`csrr_datetime`,
				`supplier`.`gcs_companyname`,
				`users`.`firstname`,
				`users`.`lastname`
			FROM 
				`custodian_srr` 
			INNER JOIN
				`requisition_entry`
			ON
				`requisition_entry`.`requis_id` = `custodian_srr`.`csrr_requisition`
			INNER JOIN
				`users`
			ON
				`users`.`user_id` = `custodian_srr`.`csrr_prepared_by`
			INNER JOIN
				`supplier`
			ON
				`supplier`.`gcs_id` = `requisition_entry`.`requis_supplierid`
			ORDER BY 
				`custodian_srr`.`csrr_id`
			DESC
		");

		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $link->error;
		}
	}

	function getSelectedData($link,$table,$select,$where,$join,$limit)
	{
		$query = $link->query(
			"SELECT
				".$select."
			FROM 
				".$table." 
			".$join."
			WHERE
				".$where."
			".$limit."		
		");
		if($query)
		{
			$row = $query->fetch_object();
			return $row;
		}
		else 
		{
			return $link->error;
		}
	}

	function getAllData($link,$table,$select,$where,$join,$limit)
	{
		$rows = [];
		$query = $link->query(
			"SELECT
				".$select."
			FROM 
				".$table." 
			".$join."
			WHERE
				".$where."
			".$limit."		
		");
		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}
			return $rows;
		}
		else 
		{
			return $link->error;
		}
	}

	function countRows($link,$table,$select,$where,$join,$limit)
	{
		$query = $link->query(
			"SELECT
				".$select."
			FROM 
				".$table." 
			".$join."
			WHERE
				".$where."
			".$limit."		
		");
		if($query)
		{
			$row = $query->fetch_object();
			return $row;
		}
		else 
		{
			return $link->error;
		}		
	}

	function countGCNotYetAllocatedandNotPromo($link,$den)
	{
		$query = $link->query(
			"SELECT 
				IFNULL(COUNT(barcode_no),0) as cnt 
			FROM 
				gc 
			WHERE 
				denom_id='".$den."'
			AND
				gc_ispromo=''
			AND
				gc_validated='*'
			AND
				gc_allocated=''
			AND
				gc_treasury_release=''
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->cnt;
		}
	}

	function deleteSelectedData($link,$table,$where)
	{
		$query = $link->query(
			"DELETE 
			FROM 
			$table
			WHERE 
			$where
		");

		if($query)
		{
			return true;
		}
		else 
		{
			return false;
		}
	}

	function getLastnumberOneWhere($link,$table,$select,$orderby)
	{
		$query = $link->query(
			"SELECT 
				$select
			FROM
				$table
			ORDER BY
				$orderby
			DESC
			LIMIT 1
		");

		if($query)
		{
			if($query->num_rows > 0)
			{
				$row = $query->fetch_object();
				$num = $row->$select;
				$num++;
				return $num;
			}
			else 
			{
				return 1;
			}
		}
	}

	function getLastnumberOneWhere1($link,$table,$select,$field,$var,$orderby)
	{
		$query = $link->query(
			"SELECT 
				$select
			FROM
				$table
			WHERE
				$field = '$var'
			ORDER BY
				$orderby
			DESC
			LIMIT 1
		");

		if($query)
		{
			if($query->num_rows > 0)
			{
				$row = $query->fetch_object();
				$num = $row->$select;
				$num++;
				return $num;
			}
			else 
			{
				return 1;
			}
		}		
	}

	function getAvailablePromoGC($link)
	{
		$rows =[];
		$query = $link->query(
			"SELECT 
			gc.barcode_no,
			production_request.pe_group
		FROM
			gc
		INNER JOIN
			production_request
		ON
			production_request.pe_id = gc.pe_entry_gc
		WHERE NOT EXISTS 
		(
		SELECT 
		    prom_barcode 
		FROM 
		    promo_gc 
		WHERE 
		    promo_gc.prom_barcode = gc.barcode_no
		)

		AND
			gc.gc_validated='*'
		AND
			gc.gc_ispromo = '*'
		");

		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}
			return $rows;
		}
	}

	function checkUsernameExist($link,$username)
	{
		$query = $link->query(
			"SELECT 
				username
			FROM 
				users
			WHERE
				username = '$username'
		");

		if($query)
		{
			if($query->num_rows > 0)
			{
				return true;
			}
			else 
			{
				return false;
			}
		}
		else 
		{
			return $link->query;
		}
	}

	function getCreditCardTotalPayable($link,$ccid)
	{
		$query = $link->query(
			"SELECT 
				IFNULL(SUM(ledger_creditcard.ccled_debit_amt),0.00) - IFNULL(SUM(ledger_creditcard.ccled_credit_amt),0.00) as total
			FROM 
				ledger_creditcard
			WHERE 
				ledger_creditcard.ccled_creditcardid='$ccid'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->total;
		}
		else 
		{
			die($link->error);
		}
	}

	function getCreditCardLastTransaction($link,$ccid)
	{
		$query = $link->query(
			"SELECT 
				ledger_creditcard.ccled_transid,
				transaction_stores.trans_datetime
			FROM 
				ledger_creditcard 
			INNER JOIN
				transaction_stores
			ON
				transaction_stores.trans_sid = ledger_creditcard.ccled_transid
			WHERE 
				ledger_creditcard.ccled_creditcardid='$ccid'
			ORDER BY
				ledger_creditcard.ccled_id
			DESC
			LIMIT 1
		");

		if($query)
		{
			$row = $query->fetch_object();			
			return $row;
		}
		else 
		{
			die($link->error);
		}
	}

	function countGCSalesByStore($link,$store,$month)
	{
		$query = $link->query(
			"SELECT 
				IFNULL(COUNT(transaction_sales.sales_barcode),0) as cnt 
			FROM 
				transaction_sales
			INNER JOIN
				transaction_stores
			ON
				transaction_stores.trans_sid  = transaction_sales.sales_transaction_id
				WHERE 
					transaction_stores.trans_store = '$store'
				AND
					YEAR(transaction_stores.trans_datetime) = YEAR(NOW())
				AND
					MONTH(transaction_stores.trans_datetime) = MONTH(DATE_ADD(Now(), INTERVAL- $month MONTH))
		");
		if($query)
		{
			$row = $query->fetch_object();
			return $row->cnt;
		}
	}

	function checkusernameifExists($link,$id,$nusername)
	{
		$query = $link->query(
			"SELECT 
				username
			FROM 
				users 
			WHERE 
				username='$nusername'
			AND
				user_id!='$id'
		");

		if($query)
		{
			if($query->num_rows > 0)
			{
				return true;
			}
			else 
			{
				return false;
			}
		}
		else 
		{
			return $link->error;
		}
	}

	function validate_alphanumeric_underscore($str) 
	{
	    return preg_match('/^[A-Za-z0-9_]+$/',$str);
	}

	function checkGCIfPromo($barcode,$isPromo,$link)
	{
		if($isPromo=='*')
		{
			return 'Promo GC';
		}
		else
		{
			$query = $link->query(
				"SELECT 
					stores.store_name
				FROM 
					store_received_gc
				INNER JOIN
					stores
				ON
					stores.store_id = store_received_gc.strec_storeid
				WHERE 
					store_received_gc.strec_barcode ='$barcode'
			");

			if($query)
			{
				$row = $query->fetch_object();
				return $row->store_name;
			}
			else
			{
				return $link->error;
			}
		}		
	}

	function getGCSoldOrReleased($barcode,$isPromo,$link)
	{
		if($isPromo=='*')
		{
			$query = $link->query(
				"SELECT 
					prgcrel_at 
				FROM 
					promogc_released 
				WHERE 
					prgcrel_barcode='$barcode'
			");

			if($query)
			{
				$row = $query->fetch_object();
				return _dateFormat($row->prgcrel_at);
			}
			else
			{
				return $link->error;
			}
		}
		else 
		{
			$query = $link->query(
				"SELECT 
					transaction_stores.trans_datetime
				FROM 
					transaction_sales 
				INNER JOIN
					transaction_stores
				ON
					transaction_stores.trans_sid = transaction_sales.sales_transaction_id
				WHERE 
					transaction_sales.sales_barcode = '$barcode'
				AND
					transaction_sales.sales_item_status='0'
			");

			if($query)
			{
				$row = $query->fetch_object();
				return _dateFormat($row->trans_datetime);
			}
			else 
			{
				return $link->error;
			}
		}
	}

	function clean($string)
	{
		preg_replace('/[^A-Za-z0-9\-]/', '', $string); //removes special character
	}

	function storeReceiptIssuance($link,$storeid,$checked)
	{
		$query = $link->query(
			"UPDATE 
				stores 
			SET 
				issuereceipt='".$checked."' 
			WHERE 
				store_id='".$storeid."'
		");

		if($query)
		{
			return true;
		}
		else 
		{
			return false;
		}
	}

	function get_ar_balance($link,$trid)
	{
		//get customerid
		$where = 'ar_trans_id='.$trid;
		$select = 'ar_cuscode';
		$cusid = getSelectedData($link,'customer_internal_ar',$select,$where,'','');

		$where = 'ar_cuscode='.$cusid->ar_cuscode;
		$select = 'ar_dbamt,
			ar_cramt,
			ar_trans_id';
		$limit ='ORDER BY 
			ar_id
				ASC';
		$data = getAllData($link,'customer_internal_ar',$select,$where,'',$limit);

		$dbt = 0;
		$cdt = 0;
		foreach ($data as $d) 
		{
			$dbt += $d->ar_dbamt;
			$cdt += $d->ar_cramt;

			if($d->ar_trans_id==$trid)
			{
				break;
			}
		}
		return $dbt - $cdt;
	}


	function getDepartmentLedger($link,$id,$trtype)
	{
		switch ($trtype) 
		{
			case 'BE':				
				
				$query = $link->query(
					"SELECT 
						access_page.title
					FROM 
						budget_request 
					INNER JOIN
						users
					ON
						users.user_id = budget_request.br_requested_by
					INNER JOIN
						access_page
					ON
						access_page.access_no = users.usertype
					WHERE 
						br_id = '$id'
				");

				if($query)
				{
					$row = $query->fetch_object();
					return $row->title;
				}


				break;
			case 'PE':
				$query = $link->query(
					"SELECT 
						access_page.title
					FROM 
						production_request 
					INNER JOIN
						users
					ON
						users.user_id = production_request.pe_requested_by
					INNER JOIN
						access_page
					ON
						access_page.access_no = users.usertype
					WHERE 
						production_request.pe_id = '$id'
				");

				if($query)
				{
					$row = $query->fetch_object();
					return $row->title;
				}

				break;
			default:
				return 'None';
				break;
		}
	}

	function cleanURL($string)
	{
		$string = str_replace('?','',$string);
		$string = urlencode(str_replace('#','',$string));
		return $string;
	}

	function getGCTextfileExtension($link,$extensionType)
	{
		$query = $link->query(
			"SELECT 
				app_settingvalue
			FROM 
				app_settings
			WHERE 
				app_tablename = '$extensionType'
			ORDER BY 
				app_id 
			ASC
			LIMIT 1	
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->app_settingvalue;
		}
	}

	function getReportTransactionDate($tdate,$d1,$d2,$stores,$gcsales,$reval,$refund,$link)
	{
		$trdate = '';
		switch ($tdate) 
		{
			case 'today':
				$trdate = _dateFormat(date("Y-m-d"));
				break;

			case 'yesterday':
				$trdate = _dateFormat(date('Y-m-d',strtotime("-1 days")));
				break;			

			case 'thisweek':
				$monday = strtotime("last monday");
				$monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;
				$this_week_sd = date("Y-m-d",$monday);

				$sunday = strtotime(date("Y-m-d",$monday)." +6 days");
				$this_week_ed = date("Y-m-d",$sunday);

				$trdate = _dateFormat($this_week_sd).' - '._dateFormat($this_week_ed);
				break;

			case 'curmonth':
				$trdate = _dateFormatMonth(date('M Y')); 
				break;

			case 'all':
				// get store transactions
				$flag = 0;
				$select = 'transaction_stores.trans_datetime';
				$where = "transaction_stores.trans_store='".$stores."'";

				if($gcsales=='true')
				{
					$flag = 1;
					$where.=" AND ( transaction_stores.trans_type='1'
					OR transaction_stores.trans_type='2'
					OR transaction_stores.trans_type='3'";
				}

				if($reval=='true')
				{
					if($flag)
					{
						$where.=" OR transaction_stores.trans_type='6'";
					}
					else
					{
						$where.=" AND ( transaction_stores.trans_type='6'";
					}
				}

				if($refund=='true')
				{
					if($flag)
					{
						$where.=" OR transaction_stores.trans_type='4'";
					}
					else
					{
						$where.=" AND ( transaction_stores.trans_type='4'";
					}
				}

				$where.=")";

				$limit = "ORDER BY transaction_stores.trans_datetime ASC LIMIT 1";

				$gc1 = getSelectedData($link,'transaction_stores',$select,$where,'',$limit);	

				$limit = "ORDER BY transaction_stores.trans_datetime DESC LIMIT 1";

				$gc2 = getSelectedData($link,'transaction_stores',$select,$where,'',$limit);	

				$trdate = $gc1==null ? "No Transaction" : _dateFormat($gc1->trans_datetime).' - '._dateFormat($gc2->trans_datetime);
				break;

			case 'range':
				$trdate = _dateFormat($d1).' - '._dateFormat($d2);
				break;
			default:
				# code...
				break;
		}
		return $trdate;
	}

	function getWhereTransaction($transdate,$d1,$d2)
	{
		if($transdate=='today')
		{
			$where =" AND DATE(transaction_stores.trans_datetime) = CURDATE()";
		}
		elseif($transdate=='yesterday')
		{
			$where =" AND DATE(transaction_stores.trans_datetime) = CURDATE() - INTERVAL 1 DAY";
		}
		elseif ($transdate=='thisweek') 
		{
			$where =" AND WEEKOFYEAR(transaction_stores.trans_datetime) = WEEKOFYEAR(NOW())";
		}
		elseif ($transdate=='curmonth')
		{
			$where =" AND MONTH(transaction_stores.trans_datetime) = MONTH(NOW()) AND YEAR(transaction_stores.trans_datetime) = YEAR(NOW())";
		}
		elseif ($transdate=='range') 
		{
			$where =" AND DATE(transaction_stores.trans_datetime) >= '"._dateFormatoSql($d1)."'
			AND  DATE(transaction_stores.trans_datetime) <= '"._dateFormatoSql($d2)."'";
		}
		else 
		{
			$where =" AND 1";
		}
		return $where; 
	}

	function checkifExistNotEqualtoID($link,$table,$select,$field,$fieldid,$var,$varid)
	{
		$query = $link->query(
			"SELECT 
				$select 
			FROM 
				$table 
			WHERE 
				$field='$var'
			AND
				$fieldid!='$varid'
		");
		
		$num_rows = $query->num_rows;

		if($num_rows>0)
		{
			return true;
		} 
		else 
		{
			return false;
		}
	}

	function getFADIPConnectionStatus($link)
	{
		$query = $link->query(
			"SELECT 
				app_settingvalue				 
			FROM 
				app_settings  
			WHERE
				app_tablename='fad_server_connection'
		");

		if($query)
		{
			$row = $query->fetch_object();
			if($row->app_settingvalue=='yes')
			{
				return true;
			}
			else 
			{
				return false;
			}
		}
		else 
		{
			return $link->error;
		}
	}
	
	function is_in_array($array, $key, $key_value)
	{
	    $within_array = false;
	    foreach( $array as $k=>$v )
	    {
	        if( is_array($v) )
	        {
	            $within_array = is_in_array($v, $key, $key_value);
	            if( $within_array == true )
	            {
	                break;
	            }
	        } 
	        else 
	        {
	            if( $v == $key_value && $k == $key )
	            {
	                $within_array = true;
	                break;
	            }
	        }
	   	}
	   	return $within_array;
	}

	function barcodeStartSuggestion($link)
	{
		$query_bstart = $link->query(
			"SELECT 
				denom_barcode_start
			FROM 
				denomination 
			ORDER BY 
				denom_barcode_start
			DESC
				LIMIT 1
		");

		if($query_bstart)
		{
			$row = $query_bstart->fetch_object();
			return $barcode = $row->denom_barcode_start + 100000000000;
			
		}
		else 
		{
			return $link->error;
		}
	}

	function search_array($valueToFind, $arrayToSearch) {
		if(in_array($valueToFind, $arrayToSearch)) {
			return true;
		}
		foreach($arrayToSearch as $element) {
			if(is_array($element) && search_array($valueToFind, $element))
			return true;
		}
		return false;
	}
	

	function checkDocuments($files)
	{
		$imagename = '';
		$allowedTypes = array('image/jpeg','image/png');

		$fileType = $files['docs']['type'][0];

		if(!in_array($fileType, $allowedTypes))
		{
			$imageError = 1;
		} 
		else 
		{
			$name = $files['docs']['name'][0];
			$expImg = explode(".",$name);
			$prodImg = $expImg[0];
			$imgType = $expImg[1];

			$imagename = $_SESSION['gc_id'].'-'.getTimestamp().'.'.$imgType;
			$imageError = 0;
		}

		return array($imageError,$imagename);
	}

	function externalDocumentFilename($origfilename,$num,$reqnum)
	{
		$expImg = explode(".",$origfilename);
		$prodImg = $expImg[0];
		$imgType = $expImg[1];
		$imagename = $reqnum.'-'.getTimestamp().'-'.$num.'.'.$imgType;
		return $imagename;
	}

	function checkDocumentsMutiple($files)
	{
		$imageError = 0;
		$allowedTypes = array('image/jpeg','image/png');

		for ($i=0; $i < count($_FILES['docs']['name']); $i++) 
		{
			if(!in_array($_FILES['docs']['type'][$i], $allowedTypes))
			{
				$imageError = 1;
				break;
			} 
		}

		return $imageError;
	}
	
	function countFiles($files)
	{
		$num = 0;
		$allowedTypes = array('image/jpeg','image/png');
		for ($i=0; $i < count($_FILES['docs']['name']); $i++) 
		{
			if(in_array($_FILES['docs']['type'][$i], $allowedTypes))
			{
				$num++;
			} 
		}

		return $num;
	}

	function totalGCPromoRequest($id,$link)
	{
        $table = 'promo_gc_request_items';
        $select = 'promo_gc_request_items.pgcreqi_qty,
            denomination.denomination';
        $where = "promo_gc_request_items.pgcreqi_trid='$id'";
        $join = 'INNER JOIN
                denomination
            ON
                denomination.denom_id = promo_gc_request_items.pgcreqi_denom';
        $limit ='ORDER BY denomination ASC';
        $denoms = getAllData($link,$table,$select,$where,$join,$limit);
        $total = 0;
        foreach ($denoms as $d)
        {
			$subtotal = 0;        	
            $subtotal = $d->denomination * $d->pgcreqi_qty;
            $total+=$subtotal;
        }
        return $total;
	}

	function getRequestedQtyforPromoRequest($link,$id,$denid)
	{
		$query = $link->query(
			"SELECT 
				pgcreqi_qty
			FROM 
				promo_gc_request_items
			WHERE
				pgcreqi_trid='".$id."'
			AND
				pgcreqi_denom='".$denid."'
		");

		if($query)
		{
			$num = $query->num_rows;

			if($num > 0)
			{
				$row = $query->fetch_object();
				return $row->pgcreqi_qty;
			}
			else 
			{
				return 0;
			}
		}
		else 
		{
			return $link->error;
		}
	}

	function totalExternalRequest($link,$trid)
	{
		//check type
		$total = 0;
		$count = 0;

		//get request type
		$reqtype = getField($link,'spexgc_type','special_external_gcrequest','spexgc_id',$trid);

		if($reqtype==1)
		{
			$query = $link->query(
				"SELECT 
					specit_denoms,
					specit_qty
				FROM 
					special_external_gcrequest_items
				WHERE 
					specit_trid='$trid'
			");

			if($query)
			{
				while ($row = $query->fetch_object()) 
				{
					$subtotal = 0;
					$subtotal = $row->specit_denoms * $row->specit_qty;
					$total += $subtotal;
					$count += $row->specit_qty;
				}
			}
			else 
			{
				die('Query Error.');
			}

			$total = array($total, $count);
		}	
		else 
		{
			$query = $link->query(
				"SELECT 
					IFNULL(SUM(special_external_gcrequest_emp_assign.spexgcemp_denom),0.00) as totaldenom,
					IFNULL(COUNT(special_external_gcrequest_emp_assign.spexgcemp_denom),0) as cnt
				FROM 
					special_external_gcrequest_emp_assign 
				WHERE 
					spexgcemp_trid='".$trid."'
			");

			if($query)		
			{
				$row = $query->fetch_object();																																												
				$total = array($row->totaldenom,$row->cnt);
			}								
			else
			{
				die('Query Error.');
			}			
		}	

		return $total;
	}

	function totalExternalRequestTresDept($link,$trid)
	{
		//check type
		$total = 0;
		$count = 0;

		//get request type
		$query = $link->query(
			"SELECT 
				specit_denoms,
				specit_qty
			FROM 
				special_external_gcrequest_items
			WHERE 
				specit_trid='$trid'
		");

		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$subtotal = 0;
				$subtotal = $row->specit_denoms * $row->specit_qty;
				$total += $subtotal;
				$count += $row->specit_qty;
			}
		}
		else 
		{
			die('Query Error.');
		}

		$total = array($total, $count);			

		return $total;
	}





	function generateSpecialGCBarcode($link)
	{
		$query = $link->query(
			"SELECT 
				spexgcemp_barcode
			FROM 
				special_external_gcrequest_emp_assign 
			WHERE 
				spexgcemp_barcode!='0'
			ORDER BY
				spexgcemp_barcode
			DESC
			LIMIT 1
		");

		if($query)
		{
			if($query->num_rows > 0)
			{
				$row = $query->fetch_object();
				$barcode = $row->spexgcemp_barcode;
				$barcode++;
			}
			else 
			{
				$barcode = getField($link,'app_settingvalue','app_settings ','app_tablename','app_special_external_barcode_start');
			}

			return $barcode;
		}
		else 
		{
			die('Query Error');
		}
	}

	function generateSpecialGCReleasingNo($link)
	{
		$query = $link->query(
			"SELECT 
				reqap_trnum
			FROM 
				approved_request
			WHERE 
				approved_request.reqap_approvedtype='special external releasing'
			ORDER BY
				approved_request.reqap_trnum
			DESC	
		");

		if($query)
		{
			if($query->num_rows > 0)
			{
				$row = $query->fetch_object();
				$row = $row->reqap_trnum;
				return $row++;
			}
			else
			{
				return 1;
			}
		}
		else 
		{
			die($link->error);
		}
	}

	function generatePromoNum($link)
	{
		$tag = getField($link,'promo_tag','users','user_id',$_SESSION['gc_id']);
		$query = $link->query(
			"SELECT 
				promo_num
			FROM 
				promo 
			WHERE 
				promo_tag='$tag'
			ORDER BY
				promo_id DESC 
			LIMIT 1
		");

		if($query)
		{
			if($query->num_rows > 0)
			{
				$row = $query->fetch_object();
				return $row->promo_num + 1;
			}
			else 
			{
				return 1;
			}
		}
		else 
		{
			return $link->error;
		}
		
	}

	function hasPageAccessView($link,$pageid,$userid)
	{
		$query = $link->query(
			"SELECT
				upages_pageid
			FROM 
				user_pages 
			WHERE 
				upages_pageid='".$pageid."'
			AND
				upages_userid='".$userid."'
			AND
				upages_view='1'
		");

		if($query)
		{
			$num = $query->num_rows;
			if($num > 0)
			{
				return true;
			}
			else 
			{
				return false;
			}
		}
	}

	function hasPageAccessUpdate($link,$pageid,$userid)
	{
		$query = $link->query(
			"SELECT
				upages_pageid
			FROM 
				user_pages 
			WHERE 
				upages_pageid='".$pageid."'
			AND
				upages_userid='".$userid."'
			AND
				upages_update='1'
		");

		if($query)
		{
			$num = $query->num_rows;
			if($num > 0)
			{
				return true;
			}
			else 
			{
				return false;
			}
		}		
	}

	function hasPageAccessApproval($link,$pageid,$userid)
	{
		$query = $link->query(
			"SELECT
				upages_pageid
			FROM 
				user_pages 
			WHERE 
				upages_pageid='".$pageid."'
			AND
				upages_userid='".$userid."'
			AND
				upages_approval='1'
		");

		if($query)
		{
			$num = $query->num_rows;
			if($num > 0)
			{
				return true;
			}
			else 
			{
				return false;
			}
		}		
	}



	function getRecBy($link,$recid)
	{
		$query = $link->query(
			"SELECT
				CONCAT(users.firstname,' ',users.lastname) as rec
			FROM 
				approved_request 
			INNER JOIN
				users
			ON
				users.user_id = approved_request.reqap_preparedby
			WHERE 
				reqap_trid='".$recid."'
			AND
				reqap_approvedtype='promo gc preapproved'
		");

		if($query)
		{
			$num = $query->num_rows;
			if($num > 0)
			{
				$row = $query->fetch_object();
				return $row->rec;
			}
			else 
			{
				return '';
			}
		}
		else 
		{
			return $link->error;
		}
	}

	function getApprovedBy($link,$reqid)
	{
		$query = $link->query(
			"SELECT 
				CONCAT(users.firstname,' ',users.lastname) as appby
			FROM 
				approved_request 
			INNER JOIN
				users
			ON
				users.user_id = approved_request.reqap_preparedby
			WHERE 
				approved_request.reqap_trid='".$reqid."'
			AND
				approved_request.reqap_approvedtype='promo gc approved'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->appby;
		}
		else 
		{
			return $link->error;
		}
	}

	function getReviewed($link,$reqid)
	{
		$query = $link->query(
			"SELECT
			approved_request.reqap_date,
			CONCAT(users.firstname,' ',users.lastname) as revby
		FROM 
			approved_request 
		INNER JOIN
			users
		ON
			users.user_id = approved_request.reqap_preparedby
		WHERE 
			approved_request.reqap_trid='".$reqid."'
		AND
			approved_request.reqap_approvedtype='special external gc review'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row;
		}
		else 
		{
			die('Error');
		}
	}

	function getUserFullname($link,$uid)
	{
		$query = $link->query(
			"SELECT 
				CONCAT(firstname,' ',lastname) as fullname
			FROM 
				users 
			WHERE 
				user_id ='$uid'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->fullname;
		}
	}

	function getTotalProductionRequest($link,$id)
	{
		$query = $link->query(
			"SELECT 
				SUM(production_request_items.pe_items_quantity * denomination) as total
			FROM 
				production_request_items
			INNER JOIN
				denomination
			ON
				denomination.denom_id = production_request_items.pe_items_denomination
			WHERE 
				pe_items_request_id='".$id."'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->total;
		}
		else 
		{
			die('Error');
		}
	}

	function getBarcodeDenomination($link,$barcode)
	{
		$query = $link->query(
			"SELECT 
				denomination.denomination
			FROM 
				gc
			INNER JOIN
				denomination
			ON
				denomination.denom_id = gc.denom_id
			WHERE 
				gc.barcode_no='$barcode'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->denomination;
		}
		else 
		{
			return null;
		}
	}

	function insertBudgetLedgers($link,$trid,$type,$dbfield,$amount)
	{
		$ln = $lnum = ledgerNumber($link);

		$query = $link->query(
			"INSERT INTO 
				ledger_budget
			(
			    bledger_no, 
			    bledger_trid, 
			    bledger_datetime, 
			    bledger_type,
			    $dbfield
			) 
			VALUES 
			(
			    '$ln',
			    '$trid',
			    NOW(),
			    '$type',
			    '$amount'
			)
		");

		if($query)
		{
			return true;
		}
		else 
		{
			return false;
		}
	}

	function getTransferRequestNumber($link,$storeid)
	{
		$query = $link->query(
			"SELECT 
				t_reqnum 
			FROM 
				transfer_request 
			WHERE 
				t_reqstoreby='$storeid'
			ORDER BY
				t_reqnum
			DESC
			LIMIT 1
		");

		if($query)
		{
			if($query->num_rows > 0)
			{
				$row = $query->fetch_object();
				$row = $row->t_reqnum+1;
				$row = sprintf("%03d", $row);
				return $row;
			}
			else 
			{
				return sprintf("%03d", 1);;
			}
		}
	}

	function getTransferReleasedNumber($link,$storeid)
	{
		$query = $link->query(
			"SELECT 
				transfer_request_served.tr_serverelnum 
			FROM 
				transfer_request_served 
			INNER JOIN
				transfer_request
			ON
				transfer_request.tr_reqid = transfer_request_served.tr_reqid
			WHERE 
				transfer_request.t_reqstoreto='$storeid'
			ORDER BY
				transfer_request_served.tr_serverelnum
			DESC
			LIMIT 1
		");

		if($query)
		{
			if($query->num_rows > 0)
			{
				$row = $query->fetch_object();
				$row = $row->tr_serverelnum+1;
				$row = sprintf("%03d", $row);
				return $row;
			}
			else 
			{
				return sprintf("%03d", 1);;
			}
		}
	}

	function getReceivingNumberTR($link,$storeid,$receivingtype)
	{
		$query = $link->query(
			"SELECT 
				srec_recid
			FROM 
				store_received
			WHERE 
				srec_receivingtype='".$receivingtype."'
			AND
				srec_store_id='".$storeid."'
			ORDER BY 
				srec_id
			DESC
		");

		if($query)
		{
			if($query->num_rows > 0)
			{
				$row = $query->fetch_object();
				$row = $row->srec_recid+1;
				$row = sprintf("%03d", $row);
				return $row;
			}
			else 
			{
				return sprintf("%03d", 1);
			}
		}
		else 
		{
			return $link->error;
		}

	}

	function getLostGCReportNum($link,$storeid)
	{
		$query = $link->query(
			"SELECT 
				lostgcd_repnum
			FROM 
				lost_gc_details 
			WHERE
				lostgcd_storeid='".$storeid."'
			ORDER BY
				lostgcd_repnum
			DESC	
			LIMIT 1
	
		");

		if($query)
		{
			if($query->num_rows > 0)
			{
				$row = $query->fetch_object();
				$row = $row->lostgcd_repnum+1;
				$row = sprintf("%03d", $row);
				return $row;
			}
			else 
			{
				return sprintf("%03d", 1);
			}
		}
	}

	function numOfGCtoRecTransfer($link,$servedid,$denid)
	{
		$query = $link->query(
			"SELECT 
				gc.denom_id, 
				transfer_request_served_items.trs_barcode, 
				denomination.denomination 
			FROM 
				transfer_request_served_items 
			LEFT JOIN 
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
				transfer_request_served.tr_servedid = transfer_request_served_items.trs_served 
			WHERE 
				denomination.denom_id='".$denid."'
			AND 
				transfer_request_served_items.trs_served='".$servedid."' 
			AND 
				transfer_request_served.tr_serve_store='".$_SESSION['gc_store']."'
		");

		if($query)
		{
			return $query->num_rows;
		}
		else 
		{
			return 0;
		}
	}

	function getBarcodeStartGCReceiving($link,$denom_id,$recid,$order)
	{
		$query = $link->query(
			"SELECT 
				denomination.denomination,
				custodian_srr_items.cssitem_barcode,
				COUNT(custodian_srr_items.cssitem_barcode) as cnt
			FROM 
				custodian_srr_items 
			INNER JOIN
				gc
			ON
				gc.barcode_no = custodian_srr_items.cssitem_barcode
			INNER JOIN
				denomination
			ON
				denomination.denom_id = gc.denom_id
			WHERE 
				denomination.denom_id = '".$denom_id."'
			AND
				custodian_srr_items.cssitem_recnum = '".$recid."'
			ORDER BY
				custodian_srr_items.cssitem_barcode
			$order
			LIMIT 1
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row;
		}
		else 
		{
			return $link->error;
		}
	}

	function getBarcodeEndGCReceiving($link,$denom_id,$recid,$order)
	{
		$query = $link->query(
			"SELECT 
				denomination.denomination,
				custodian_srr_items.cssitem_barcode
			FROM 
				custodian_srr_items 
			INNER JOIN
				gc
			ON
				gc.barcode_no = custodian_srr_items.cssitem_barcode
			INNER JOIN
				denomination
			ON
				denomination.denom_id = gc.denom_id
			WHERE 
				denomination.denom_id = '".$denom_id."'
			AND
				custodian_srr_items.cssitem_recnum = '".$recid."'
			ORDER BY
				custodian_srr_items.cssitem_barcode
			$order
			LIMIT 1
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row;
		}
		else 
		{
			return $link->error;
		}
	}

	function getSpecialExteranalByRequestNumber($link,$id)
	{
		$rows = [];
		//get requiest id
		$query = $link->query(
			"SELECT 
				spexgc_id
			FROM 
				special_external_gcrequest 
			WHERE 
				spexgc_num='$id'
		");

		if($query)
		{
			$row = $query->fetch_object();
			$rid = $row->spexgc_id;
		}

		$query = $link->query(
			"SELECT 
				special_external_gcrequest_emp_assign.spexgcemp_denom,
			    special_external_gcrequest_emp_assign.spexgcemp_fname,
			    special_external_gcrequest_emp_assign.spexgcemp_lname,
			    special_external_gcrequest_emp_assign.spexgcemp_mname,
			    special_external_gcrequest_emp_assign.spexgcemp_extname,
			    special_external_gcrequest_emp_assign.spexgcemp_barcode,
			    special_external_customer.spcus_acctname
			FROM 
				special_external_gcrequest_emp_assign 
			INNER JOIN
				special_external_gcrequest
			ON
				special_external_gcrequest.spexgc_id = special_external_gcrequest_emp_assign.spexgcemp_trid
			INNER JOIN
				special_external_customer
			ON
				special_external_customer.spcus_id = special_external_gcrequest.spexgc_company
			WHERE 
				special_external_gcrequest_emp_assign.spexgcemp_trid='$rid'
		");

		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}
			return $rows;
		}
	}

	function getSpecialExteranalByRange($link,$bstart,$bend)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				special_external_gcrequest_emp_assign.spexgcemp_denom,
			    special_external_gcrequest_emp_assign.spexgcemp_fname,
			    special_external_gcrequest_emp_assign.spexgcemp_lname,
			    special_external_gcrequest_emp_assign.spexgcemp_mname,
			    special_external_gcrequest_emp_assign.spexgcemp_extname,
			    special_external_gcrequest_emp_assign.spexgcemp_barcode,
			    special_external_customer.spcus_acctname
			FROM 
				special_external_gcrequest_emp_assign 
			INNER JOIN
				special_external_gcrequest
			ON
				special_external_gcrequest.spexgc_id = special_external_gcrequest_emp_assign.spexgcemp_trid
			INNER JOIN
				special_external_customer
			ON
				special_external_customer.spcus_id = special_external_gcrequest.spexgc_company
			WHERE 
				special_external_gcrequest_emp_assign.spexgcemp_barcode>='$bstart'
            AND
            	special_external_gcrequest_emp_assign.spexgcemp_barcode<='$bend'
		");

		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}
			return $rows;
		}		
	}

	function getSpecialExteranalByBarcode($link,$barcode)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				special_external_gcrequest_emp_assign.spexgcemp_denom,
			    special_external_gcrequest_emp_assign.spexgcemp_fname,
			    special_external_gcrequest_emp_assign.spexgcemp_lname,
			    special_external_gcrequest_emp_assign.spexgcemp_mname,
			    special_external_gcrequest_emp_assign.spexgcemp_extname,
			    special_external_gcrequest_emp_assign.spexgcemp_barcode,
			    special_external_customer.spcus_acctname
			FROM 
				special_external_gcrequest_emp_assign 
			INNER JOIN
				special_external_gcrequest
			ON
				special_external_gcrequest.spexgc_id = special_external_gcrequest_emp_assign.spexgcemp_trid
			INNER JOIN
				special_external_customer
			ON
				special_external_customer.spcus_id = special_external_gcrequest.spexgc_company
			WHERE 
				special_external_gcrequest_emp_assign.spexgcemp_barcode='$barcode'
		");


		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}
			return $rows;
		}
	}

	function getSpecialExteranalByOffset($link,$offset,$id)
	{
		$offset = $offset - 900;
		$rows = [];
		$query = $link->query(
			"SELECT 
				special_external_gcrequest_emp_assign.spexgcemp_denom,
			    special_external_gcrequest_emp_assign.spexgcemp_fname,
			    special_external_gcrequest_emp_assign.spexgcemp_lname,
			    special_external_gcrequest_emp_assign.spexgcemp_mname,
			    special_external_gcrequest_emp_assign.spexgcemp_extname,
			    special_external_gcrequest_emp_assign.spexgcemp_barcode,
			    special_external_customer.spcus_acctname
			FROM 
				special_external_gcrequest_emp_assign 
			INNER JOIN
				special_external_gcrequest
			ON
				special_external_gcrequest.spexgc_id = special_external_gcrequest_emp_assign.spexgcemp_trid
			INNER JOIN
				special_external_customer
			ON
				special_external_customer.spcus_id = special_external_gcrequest.spexgc_company
			WHERE 
				spexgcemp_trid='$id'
			LIMIT 
				$offset,4
		");


		if($query)
		{
			while ($row = $query->fetch_object()) 
			{
				$rows[] = $row;
			}
			return $rows;
		}
	}

	function numtowords($num)
	{ 
		$decones = array( 
		            '01' => "One", 
		            '02' => "Two", 
		            '03' => "Three", 
		            '04' => "Four", 
		            '05' => "Five", 
		            '06' => "Six", 
		            '07' => "Seven", 
		            '08' => "Eight", 
		            '09' => "Nine", 
		            '10' => "Ten", 
		            '11' => "Eleven", 
		            '12' => "Twelve", 
		            '13' => "Thirteen", 
		            '14' => "Fourteen", 
		            '15' => "Fifteen", 
		            '16' => "Sixteen", 
		            '17' => "Seventeen", 
		            '18' => "Eighteen", 
		            '19' => "Nineteen" 
		            );
		$ones = array( 
		            '0' => " ",
		            '1' => "One",     
		            '2' => "Two", 
		            '3' => "Three", 
		            '4' => "Four", 
		            '5' => "Five", 
		            '6' => "Six", 
		            '7' => "Seven", 
		            '8' => "Eight", 
		            '9' => "Nine", 
		            '10' => "Ten", 
		            '11' => "Eleven", 
		            '12' => "Twelve", 
		            '13' => "Thirteen", 
		            '14' => "Fourteen", 
		            '15' => "Fifteen", 
		            '16' => "Sixteen", 
		            '17' => "Seventeen", 
		            '18' => "Eighteen", 
		            '19' => "Nineteen" 
		            ); 
		$tens = array( 
		            '0' => "",
		            '2' => "Twenty", 
		            '3' => "Thirty", 
		            '4' => "Forty", 
		            '5' => "Fifty", 
		            '6' => "Sixty", 
		            '7' => "Seventy", 
		            '8' => "Eighty", 
		            '9' => "Ninety" 
		            ); 
		$hundreds = array( 
		            "Hundred", 
		            "Thousand", 
		            "Million", 
		            "Billion", 
		            "Trillion", 
		            "Quadrillion" 
		            ); //limit t quadrillion 
		$num = number_format($num,2,".",","); 
		$num_arr = explode(".",$num); 
		$wholenum = $num_arr[0]; 
		$decnum = $num_arr[1]; 
		$whole_arr = array_reverse(explode(",",$wholenum)); 
		krsort($whole_arr); 
		$rettxt = ""; 
		foreach($whole_arr as $key => $i){ 
		    if($i < 20){ 
		        $rettxt .= $ones[$i]; 
		    }
		    elseif($i < 100){ 
		        $rettxt .= $tens[substr($i,0,1)]; 
		        $rettxt .= " ".$ones[substr($i,1,1)]; 
		    }
		    else{ 
		        $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
		        $rettxt .= " ".$tens[substr($i,1,1)]; 
		        $rettxt .= " ".$ones[substr($i,2,1)]; 
		    } 
		    if($key > 0){ 
		        $rettxt .= " ".$hundreds[$key]." "; 
		    } 

		} 
		$rettxt = $rettxt." pesos";

		if($decnum > 0){ 
		    $rettxt .= " and "; 
		    if($decnum < 20){ 
		        $rettxt .= $decones[$decnum]; 
		    }
		    elseif($decnum < 100){ 
		        $rettxt .= $tens[substr($decnum,0,1)]; 
		        $rettxt .= " ".$ones[substr($decnum,1,1)]; 
		    }
		    $rettxt = $rettxt." centavos"; 
		} 
		return $rettxt.' only';
	} 

	function convert_number_to_words($number) 
	{
	   
	    $hyphen      = '-';
	    $conjunction = ' ';
	    $separator   = ' ';
	    $negative    = 'negative ';
	    $decimal     = ' and ';
	    $dictionary  = array(
	        0                   => 'Zero',
	        1                   => 'One',
	        2                   => 'Two',
	        3                   => 'Three',
	        4                   => 'Four',
	        5                   => 'Five',
	        6                   => 'Six',
	        7                   => 'Seven',
	        8                   => 'Eight',
	        9                   => 'Nine',
	        10                  => 'Ten',
	        11                  => 'Eleven',
	        12                  => 'Twelve',
	        13                  => 'Thirteen',
	        14                  => 'Fourteen',
	        15                  => 'Fifteen',
	        16                  => 'Sixteen',
	        17                  => 'Seventeen',
	        18                  => 'Eighteen',
	        19                  => 'Nineteen',
	        20                  => 'Twenty',
	        30                  => 'Thirty',
	        40                  => 'Forty',
	        50                  => 'Fifty',
	        60                  => 'Sixty',
	        70                  => 'Seventy',
	        80                  => 'Eighty',
	        90                  => 'Ninety',
	        100                 => 'Hundred',
	        1000                => 'Thousand',
	        1000000             => 'Million',
	        1000000000          => 'Billion',
	        1000000000000       => 'Trillion',
	        1000000000000000    => 'Quadrillion',
	        1000000000000000000 => 'Quintillion'
	    );
	   
	    if (!is_numeric($number)) {
	        return false;
	    }
	   
	    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
	        // overflow
	        trigger_error(
	            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
	            E_USER_WARNING
	        );
	        return false;
	    }

	    if ($number < 0) {
	        return $negative . convert_number_to_words(abs($number));
	    }
	   
	    $string = $fraction = null;
	   
	    if (strpos($number, '.') !== false) {
	        list($number, $fraction) = explode('.', $number);
	    }
	   
	    switch (true) {
	        case $number < 21:
	            $string = $dictionary[$number];
	            break;
	        case $number < 100:
	            $tens   = ((int) ($number / 10)) * 10;
	            $units  = $number % 10;
	            $string = $dictionary[$tens];
	            if ($units) {
	                $string .= $hyphen . $dictionary[$units];
	            }
	            break;
	        case $number < 1000:
	            $hundreds  = $number / 100;
	            $remainder = $number % 100;
	            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
	            if ($remainder) {
	                $string .= $conjunction . convert_number_to_words($remainder);
	            }
	            break;
	        default:
	            $baseUnit = pow(1000, floor(log($number, 1000)));
	            $numBaseUnits = (int) ($number / $baseUnit);
	            $remainder = $number % $baseUnit;
	            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
	            if ($remainder) {
	                $string .= $remainder < 100 ? $conjunction : $separator;
	                $string .= convert_number_to_words($remainder);
	            }
	            break;
	    }
	   
	    if (null !== $fraction && is_numeric($fraction)) {
	        $string .= $decimal;
	        $words = array();
	        foreach (str_split((string) $fraction) as $number) {
	            $words[] = $dictionary[$number];
	        }
	        $string .= implode(' ', $words);
	    }
	   
	    return $string;
	}

	function getUserDepartment($id)
	{
		
	}

	function getTransactionsForExport($link,$dtype,$stcus,$start,$end)
	{
		$arr_data = [];

		if($dtype=='all')
		{
			// get all customer by type
			$query_all = $link->query(
				"SELECT 
					insp_paymentcustomer 
				FROM 
					institut_payment 
				GROUP BY
					insp_paymentcustomer	
			");

			if($query_all)
			{
				while ($row_all = $query_all->fetch_object()) 
				{
					if($row_all->insp_paymentcustomer=='institution')
					{

						$query_ins = $link->query(
							"SELECT 
								institut_payment.insp_id,
								institut_transactions_items.instituttritems_barcode,
							    denomination.denomination,
							    institut_transactions.institutr_date,
							    institut_customer.ins_name,
							    institut_transactions.institutr_paymenttype
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
							INNER JOIN
								institut_transactions
							ON
								institut_transactions.institutr_id = institut_transactions_items.instituttritems_trid
							INNER JOIN
								institut_customer
							ON
								institut_customer.ins_id = institut_transactions.institutr_cusid
							INNER JOIN
								institut_payment
							ON
								institut_payment.insp_trid = institut_transactions.institutr_id 
							WHERE 
								institut_payment.insp_paymentcustomer='institution'
							AND
								(DATE(institut_transactions.institutr_date) >= '$start'
							AND
								DATE(institut_transactions.institutr_date) <= '$end')
						");
						

						if($query_ins)
						{
							while ($row_ins = $query_ins->fetch_object())
							{
								$arr_data[] = array(
									"inspay_id"		=>	$row_ins->insp_id,
									"barcode"		=>	$row_ins->instituttritems_barcode,
									"date"			=>	$row_ins->institutr_date,
									"customer"		=>	$row_ins->ins_name,
									"paymenttype"	=>	$row_ins->institutr_paymenttype,
									"gctype"		=> 	"Institution GC"
								);
							} 
						}
					}
					elseif($row_all->insp_paymentcustomer=='stores')
					{
						// SELECT 
						// 	institut_payment.insp_id,
						// 	re_barcode_no,
						//     denomination.denomination,
						//     approved_gcrequest.agcr_approved_at,
						//     stores.store_name,
						//     approved_gcrequest.agcr_paymenttype
						// FROM 
						// 	gc_release 
						// INNER JOIN
						// 	gc
						// ON
						// 	gc.barcode_no = gc_release.re_barcode_no
						// INNER JOIN
						// 	denomination
						// ON
						// 	denomination.denom_id = gc.denom_id
						// INNER JOIN
						// 	approved_gcrequest
						// ON
						// 	approved_gcrequest.agcr_request_relnum = gc_release.rel_num
						// INNER JOIN
						// 	institut_payment
						// ON
						// 	institut_payment.insp_trid = approved_gcrequest.agcr_id
						// INNER JOIN
						//     store_gcrequest
						// ON
						//     store_gcrequest.sgc_id = approved_gcrequest.agcr_request_id
						// INNER JOIN
						//     stores
						// ON
						//     stores.store_id = store_gcrequest.sgc_store
						// WHERE 
						// 	institut_payment.insp_paymentcustomer='stores'
						// AND
						// 	(DATE(approved_gcrequest.agcr_approved_at) >= '2017-08-25'
						// AND
						// 	DATE(approved_gcrequest.agcr_approved_at) <= '2017-08-25')
	
	
						$query_st = $link->query(
							"SELECT 
								institut_payment.insp_id,
								re_barcode_no,
							    denomination.denomination,
							    approved_gcrequest.agcr_approved_at,
							    gc_release.stores.store_name,
							    approved_gcrequest.agcr_paymenttype
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
							INNER JOIN
								approved_gcrequest
							ON
								approved_gcrequest.agcr_request_relnum = gc_release.rel_num
							INNER JOIN
								institut_payment
							ON
								institut_payment.insp_trid = approved_gcrequest.agcr_id
							INNER JOIN
							    store_gcrequest
							ON
							    store_gcrequest.sgc_id = approved_gcrequest.agcr_request_id
							INNER JOIN
							    stores
							ON
							    stores.store_id = store_gcrequest.sgc_store
							WHERE 
								institut_payment.insp_paymentcustomer='stores'
							AND
								(DATE(approved_gcrequest.agcr_approved_at) >= '$start'
							AND
								DATE(approved_gcrequest.agcr_approved_at) <= '$end')
	
						");


						if($query_ins)
						{
							while ($row_ins = $query_ins->fetch_object())
							{
								$arr_data[] = array(
									"inspay_id"		=>	$row_ins->insp_id,
									"barcode"		=>	$row_ins->instituttritems_barcode,
									"date"			=>	$row_ins->institutr_date,
									"customer"		=>	$row_ins->ins_name,
									"paymenttype"	=>	$row_ins->institutr_paymenttype,
									"gctype"		=> 	"Regular GC"
								);
							} 
						}
					}
					elseif ($row_all->insp_paymentcustomer=='special external') 
					{
						// SELECT 
						// 	institut_payment.insp_id,
						//     special_external_gcrequest_emp_assign.spexgcemp_barcode,
						//     special_external_gcrequest_emp_assign.spexgcemp_denom,
						//     special_external_gcrequest.spexgc_datereq,
						//     special_external_customer.spcus_companyname,
						//     IF(special_external_gcrequest.spexgc_paymentype = '1', 'Cash', special_external_gcrequest.spexgc_paymentype)
						//     paytype,
						//     IF(special_external_gcrequest.spexgc_paymentype = '2', 'check', special_external_gcrequest.spexgc_paymentype) 	paytype 
						// FROM 
						//     special_external_gcrequest_emp_assign 
						// INNER JOIN
						//     special_external_gcrequest
						// ON
						//     special_external_gcrequest.spexgc_id = special_external_gcrequest_emp_assign.spexgcemp_trid
						// INNER JOIN
						//     institut_payment
						// ON
						//     institut_payment.insp_trid = special_external_gcrequest.spexgc_id
						// INNER JOIN
						// 	special_external_customer
						// ON
						// 	special_external_customer.spcus_id = special_external_gcrequest.spexgc_company
						// WHERE 
						//     institut_payment.insp_paymentcustomer='special external'
						// AND
						//     (DATE(special_external_gcrequest.spexgc_datereq) >= '2017-08-29'
						// AND
						//     DATE(special_external_gcrequest.spexgc_datereq) <= '2017-08-29')

						$query_sp = $link->query(
							"SELECT 
								institut_payment.insp_id,
							    special_external_gcrequest_emp_assign.spexgcemp_barcode,
							    special_external_gcrequest_emp_assign.spexgcemp_denom,
							    special_external_gcrequest.spexgc_datereq,
							    special_external_customer.spcus_companyname,
							    IF(special_external_gcrequest.spexgc_paymentype = '1', 'Cash', special_external_gcrequest.spexgc_paymentype) paytype,
							    IF(special_external_gcrequest.spexgc_paymentype = '2', 'check', special_external_gcrequest.spexgc_paymentype) paytype 
							FROM 
							    special_external_gcrequest_emp_assign 
							INNER JOIN
							    special_external_gcrequest
							ON
							    special_external_gcrequest.spexgc_id = special_external_gcrequest_emp_assign.spexgcemp_trid
							INNER JOIN
							    institut_payment
							ON
							    institut_payment.insp_trid = special_external_gcrequest.spexgc_id
							INNER JOIN
								special_external_customer
							ON
								special_external_customer.spcus_id = special_external_gcrequest.spexgc_company
							WHERE 
							    institut_payment.insp_paymentcustomer='special external'
							AND
							    (DATE(special_external_gcrequest.spexgc_datereq) >= '$start'
							AND
							    DATE(special_external_gcrequest.spexgc_datereq) <= '$end')
						");

						if($query_sp)
						{
							while ($row_sp = $query_sp->fetch_object())
							{
								$arr_data[] = array(
									"inspay_id"		=>	$row_sp->insp_id,
									"barcode"		=>	$row_sp->spexgcemp_barcode,
									"date"			=>	$row_sp->spexgc_datereq,
									"customer"		=>	$row_sp->spcus_companyname,
									"paymenttype"	=>	$row_sp->paytype,
									"gctype"		=> 	"Special External GC"
								);
							} 
						}
	
					}
				}
			}		

			return $arr_data;

		}
	}

	function getManualNumberGC($link,$type)
	{
		//dri
		$query = $link->query(
			"SELECT 
				mgc_manualnum 
			FROM 
				manual_setgc 
			WHERE 
				mgc_manualtype='$type' 
			ORDER BY 
				mgc_manualnum 
			DESC LIMIT 1
		");

		$n = $query->num_rows;
		if($n>0)
		{
			$row = $query->fetch_assoc();
			$row = $row['mgc_manualnum'];
			$row++;
			$row = sprintf("%03d", $row);
			return $row;

		} else 
		{
			return '001';
		}
	}

	function getVerifiedDateByMonthAndYear($link,$month,$year,$bu)
	{
		$rows = [];
		$query = $link->query(
				"SELECT 
					* 
				FROM 
					store_verification 
				WHERE 
					((YEAR(vs_date) = '$year' 
				AND 
					MONTH(vs_date) = '$month')
				OR
					(YEAR(vs_reverifydate) = '$year' 
				AND 
					MONTH(vs_reverifydate) = '$month'))
				AND
					vs_store='$bu' 
		");

		while ($row = $query->fetch_object()) 
		{
			$rows[] = $row;
		}

		return $rows;

		//var_dump($query);
	}

	function getBeamAndGoTRNum($link,$storeid)
	{
		$bngtrnumberstart = "BNG-1000001";
		$query = $link->query(
			"SELECT 
				bngver_trnum
			FROM 
				beamandgo_transaction
			WHERE 				
				bngver_storeid = '$storeid'
			ORDER BY 
				beamandgo_transaction.bngver_trnum
			DESC
				LIMIT 1
		");

		if(!$query)
		{
			return 0;
		}
		else 
		{
			//dri
			if($query->num_rows > 0)
			{
				$data = $query->fetch_object();

				$tr = $data->bngver_trnum;
				$trnumarr = explode("-", $tr);
				$trnum = end($trnumarr);
				$trnum++;
				return 'BNG-'.$trnum;

			}
			else 
			{
				return $bngtrnumberstart;
			}			
		}
	}

	function storeLedgers($link,$tn,$type,$amt,$transcode,$desc,$store,$discount)
	{
		$ln = getLedgerStoreLastLedgerNumber($link,$store);
		if($type==1)
		{
			$debit = 0;
			$credit = $amt;
		}
		else
		{ 
			$debit = $amt;
			$credit = 0;
		}
		$query = $link->query(
			"INSERT INTO 
				ledger_store
			(
				sledger_date, 
				sledger_ref, 
				sledger_trans, 
				sledger_desc, 
				sledger_debit, 
				sledger_credit, 
				sledger_store,
				sledger_no,
				sledger_trans_disc
			) 
			VALUES 
			(
				NOW(),
				'$tn',
				'$transcode',
				'$desc',
				'$debit',
				'$credit',
				'$store',
				'$ln',
				'$discount'
			)
		");

		if($query)
		{
			return true;
		}
		else 
		{
			return false;
		}
	}



//SELECT * FROM gc WHERE gc.barcode_no NOT IN (SELECT gc_location.loc_barcode_no FROM gc_location)

//SELECT barcode_no FROM gc WHERE barcode_no NOT IN (SELECT bcheck_barcode FROM barcode_checker)
?>


