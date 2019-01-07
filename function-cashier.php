<?php
// SELECT 
// 	barcode_no,	
// 	CASE 
// 		denom_id
//     WHEN 1 THEN 'Net due in 5 days'

//     WHEN 2 THEN 'Net due in 10 days'

//     WHEN 3 THEN 'Net due in 30 days'

//     WHEN 5 THEN 'Net due in 50 days'

//     ELSE 'Illegal'
// 	END AS 'Status'
// FROM 
// 	gc 
	require_once 'config.php';
			// $query_rel = $link->query(
			// 	"SELECT 
			// 		`gc_release`.`re_barcode_no`,
			// 		`gc_release`.`rel_store_id`,
			// 		`gc_release`.`rel_sold`
			// 	FROM 
			// 		`gc_release` 
			// 	INNER JOIN
			// 		`store_gcrequest`
			// 	ON
			// 		`gc_release`.`rel_storegcreq_id` = `store_gcrequest`.`sgc_id`
			// 	WHERE 
			// 		`gc_release`.`re_barcode_no`='$barcode_no'
			// 	AND
			// 		`gc_release`.`rel_store_id`='".$_SESSION['gc_store']."'
			// 	AND
			// 		`store_gcrequest`.`sgc_rec`='*'		
			// ");

	function checkIFSold($link,$barcode_no)
	{
		$query = $link->query(
			"SELECT 
				`strec_barcode` 
			FROM 
				`store_received_gc`
			WHERE
				`strec_barcode` = '$barcode_no'
			AND
				`strec_sold`=''
			AND
				`strec_storeid`='".$_SESSION['gc_store']."'
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

	function getCreditCardList($link)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				* 
			FROM 
				credit_cards
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

	function getTransactionTenderType($link,$tranum,$store,$mode)
	{
		$query = $link->query(
			"SELECT 				
				`transaction_payment`.`payment_tender`
			FROM 
				`transaction_stores` 
			INNER JOIN
				`transaction_payment`
			ON
				`transaction_payment`.`payment_trans_num` = `transaction_stores`.`trans_sid`
			WHERE
				`transaction_stores`.`trans_number`='$tranum'
			AND
				`transaction_stores`.`trans_store`='$store'
		");

		if($query)
		{
			if($mode==1)
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
			elseif ($mode==2) 
			{
				$row = $query->fetch_object();
				return $row->payment_tender;
			}
		}
		else 
		{
			return $link->error;		
		}
	}

	function checkIFBarcodeExistInTransaction($link,$trans,$barcode)
	{
		$query = $link->query(
			"SELECT 
				`transaction_sales`.`sales_barcode`,
				`transaction_stores`.`trans_number`
			FROM 
				`transaction_sales`
			INNER JOIN
				`transaction_stores`
			ON
				`transaction_sales`.`sales_transaction_id` = `transaction_stores`.`trans_sid`
			WHERE 
				`transaction_sales`.`sales_barcode`='$barcode'
			AND
				`transaction_stores`.`trans_number`='$trans'
			AND
				`transaction_stores`.`trans_store`='".$_SESSION['gccashier_store']."'
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

	function checkCurrentStatus($link,$barcode)
	{
		$query = $link->query(
			"SELECT 
				`strec_barcode` 
			FROM 
				`store_received_gc` 
			WHERE 
				`strec_barcode`='$barcode'
			AND
				`strec_return`='*'
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
		else 
		{
			return $link->error;
		}
	}

	function getTransactionDetails($link,$transnum,$store)
	{
		$query = $link->query(
			"SELECT
				`transaction_stores`.`trans_datetime`,
				`stores`.`store_name`,
				`transaction_stores`.`trans_sid`,
				`store_staff`.`ss_firstname`,
				`store_staff`.`ss_lastname`
			FROM 
				`transaction_stores`
			INNER JOIN
			`stores`
			ON
				`transaction_stores`.`trans_store` = `stores`.`store_id`
			INNER JOIN
				`store_staff`
			ON
				`transaction_stores`.`trans_cashier` = `store_staff`.`ss_id`
			WHERE 
				`transaction_stores`.`trans_store`='$store'
			AND
				`transaction_stores`.`trans_number`='$transnum'
		");

		if($query)
		{
			$rows = $query->fetch_object();
			return $rows;
		}
		else 
		{
			return $rows = $query->error;
		}
	}

	function getLastTransactionNumber($link,$cashier,$store)
	{
		$query = $link->query(
			"SELECT
				`trans_number`
			FROM 
				`transaction_stores` 
			WHERE 
				`trans_cashier`='$cashier'
			AND
				`trans_store`='$store'
			ORDER BY
				`trans_number`
			DESC
			LIMIT 1
		");

		if($query)
		{
			if($query->num_rows>0)
				$row = $query->fetch_object();
				return $row->trans_number;				
			}
		else
		{
			return $link->error;
		}
	}

	function getTempRefund($link,$store,$cashier)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				`temp_refund`.`trfund_barcode`,
				`denomination`.`denomination`,
				`denomination`.`denom_id`,
				`temp_refund`.`trfund_subdisc`,
				`temp_refund`.`trfund_linedisc`
			FROM 
				`temp_refund`
			INNER JOIN
				`gc`
			ON
				`gc`.`barcode_no` = `temp_refund`.`trfund_barcode`
			INNER JOIN
				`denomination`
			ON
				`gc`.`denom_id` = `denomination`.`denom_id`
			WHERE 
				`temp_refund`.`trfund_store`='$store'
			AND
				`temp_refund`.`trfund_by`='$cashier'
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

	function getTempRefundTotal($link,$store,$cashier)
	{
		$query = $link->query(
			"SELECT 
				SUM(`denomination`.`denomination`) as totalrefund
				
			FROM 
				`temp_refund`
			INNER JOIN
				`gc`
			ON
				`gc`.`barcode_no` = `temp_refund`.`trfund_barcode`
			INNER JOIN
				`denomination`
			ON
				`gc`.`denom_id` = `denomination`.`denom_id`
			WHERE 
				`temp_refund`.`trfund_store`='$store'
			AND
				`temp_refund`.`trfund_by`='$cashier'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->totalrefund;
		}
		else 
		{
			return $link->error;
		}
	}

	function move_tfiles($link,$dir,$desdir){
		
		$query = $link->query("SELECT * FROM 
								`validation_store` 
							INNER JOIN
								`gc_location`
							ON
								validation_store.vs_barcode=gc_location.loc_barcode_no
							INNER JOIN
								store
							ON 
								gc_location.loc_store_id = store.store_id
							WHERE `vs_tf_used`=''
							AND 
							`store_code`='".$_SESSION['gccashier_store_code']."'");
		$n = $query->num_rows;
		while($row = $query->fetch_array()){

			$textfile = $row['vs_tf'];		

			rename($dir.$textfile, $desdir.$textfile);

			$link->query("UPDATE `validation_store` SET `vs_tf_used`='*'");
		}

		if($n>0){
			return true;	
		} else {
			return false;
		}
	}

	function move_textfiles($srcDir,$destDir){
		if (file_exists($destDir)) {
		  if (is_dir($destDir)) {
		    if (is_writable($destDir)) {
		      if ($handle = opendir($srcDir)) {
		        while (false !== ($file = readdir($handle))) {
		          if (is_file($srcDir . '/' . $file)) {
		            rename($srcDir . '/' . $file, $destDir . '/' . $file);
		          }
		        }	        
		        closedir($handle);	        
		      } else {
		        echo "$srcDir could not be opened.\n";
		      }
		    } else {
		      echo "$destDir is not writable!\n";
		    }
		  } else {
		    echo "$destDir is not a directory!\n";
		  }
		} else {
		  echo "$destDir does not exist\n";
		}

	}


	function is_dir_empty($dir) {
	  if (!is_readable($dir)) return NULL; 
		$handle = opendir($dir);
		while (false !== ($entry = readdir($handle))) {
		    if ($entry != "." && $entry != "..") {
		      return FALSE;
		    }
	 	}
	  	return TRUE;
	}

	function checkTotalwithoutLineDiscount($link)
	{
		$query = $link->query(
			"SELECT 
				SUM(denomination) 
			FROM 
				`temp_sales` 
			INNER JOIN 
				gc 
			ON 
				temp_sales.ts_barcode_no = gc.barcode_no 
			INNER JOIN 
				denomination
			ON
				gc.denom_id = denomination.denom_id
			INNER JOIN
				gc_location
			ON
				temp_sales.ts_barcode_no = gc_location.loc_barcode_no
			WHERE 
				`ts_cashier_id`='".$_SESSION['gccashier_id']."'
		");
		$total = $query->fetch_array();
		$total =  is_null($total['SUM(denomination)']) ? 0 : $total['SUM(denomination)'];
		return $total;
	}

	function checkTotal($link){
		$query = $link->query(
			"SELECT 
				SUM(denomination) 
			FROM 
				`temp_sales` 
			INNER JOIN 
				gc 
			ON 
				temp_sales.ts_barcode_no = gc.barcode_no 
			INNER JOIN 
				denomination
			ON
				gc.denom_id = denomination.denom_id
			INNER JOIN
				gc_location
			ON
				temp_sales.ts_barcode_no = gc_location.loc_barcode_no
			WHERE 
				`ts_cashier_id`='".$_SESSION['gccashier_id']."'
		");
		$total = $query->fetch_array();
		$total =  is_null($total['SUM(denomination)']) ? 0 : $total['SUM(denomination)'];
		$total = $total - linediscountTotal($link);
		return $total;
	}
	
	function hiddenTotal($link){
		$query = $link->query(
			"SELECT 
				SUM(denomination) 
			FROM 
				`temp_sales` 
			INNER JOIN 
				gc 
			ON 
				temp_sales.ts_barcode_no = gc.barcode_no 
			INNER JOIN 
				denomination
			ON
				gc.denom_id = denomination.denom_id
			INNER JOIN
				gc_location
			ON
				temp_sales.ts_barcode_no = gc_location.loc_barcode_no
			WHERE 
				`ts_cashier_id`='".$_SESSION['gccashier_id']."'

		");
		$total = $query->fetch_array();
		$total =  $total['SUM(denomination)'];
		if($total==''){
			return '0';
			
		} else {
			return $total;
		}
	}

	function getOneField($link,$field,$table)
	{
		$query = $link->query(
			"SELECT 
				$field
			FROM 
				$table
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->$field;
		}
		else 
		{
			return $link->error;
		}
	}

	function checkDateIfLesserThanCurdate($link,$barcode)
	{
		$query = $link->query(
			"SELECT 
				`vs_barcode` 
			FROM 
				`store_verification` 
			WHERE 
				`vs_barcode`='$barcode'
			AND
				`vs_date` < CURDATE()
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

	function checkifValidatedToday($link,$barcode)
	{
		$query = $link->query(
			"SELECT 
				`vs_barcode` 
			FROM 
				`store_verification` 
			WHERE 
				`vs_barcode`='$barcode'
			AND
				`vs_date` = CURDATE()
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

	function checkIfReverifiedToday($link,$barcode)
	{
		$query = $link->query(
			"SELECT 
				store_verification.vs_barcode
			FROM 
				store_verification 
			WHERE 
				store_verification.vs_barcode = '".$barcode."'
			AND
				DATE(store_verification.vs_reverifydate) = CURDATE()
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

	function getBarcodeForRevalDetails($link,$barcode)
	{		
		$query = $link->query(
			"SELECT 
				`store_verification`.`vs_barcode`,
				`store_verification`.`vs_date`,
				`store_verification`.`vs_time`,
				`denomination`.`denomination`,
				`gc_type`.`gctype`,
				`transaction_stores`.`trans_datetime`
			FROM 
				`store_verification` 
			INNER JOIN
				`gc`
			ON
				`gc`.`barcode_no` = `store_verification`.`vs_barcode`
			INNER JOIN
				`denomination`
			ON
				`gc`.`denom_id` = `denomination`.`denom_id`
			INNER JOIN
				`gc_location`
			ON
				`store_verification`.`vs_barcode` = `gc_location`.`loc_barcode_no`
			INNER JOIN
				`gc_type`
			ON
				`gc_location`.`loc_gc_type` = `gc_type`.`gc_type_id`
			INNER JOIN
				`transaction_sales`
			ON
				`store_verification`.`vs_barcode` = `transaction_sales`.`sales_barcode`
			INNER JOIN
				`transaction_stores`
			ON
				`transaction_sales`.`sales_transaction_id` = `transaction_stores`.`trans_sid`
			WHERE 
				`store_verification`.`vs_barcode` = '$barcode'
			AND
				`transaction_stores`.`trans_type` = 1
			OR
				`transaction_stores`.`trans_type` = 2
			OR
				`transaction_stores`.`trans_type` = 3
			OR
				`transaction_stores`.`trans_type` = 4
			ORDER BY
				`transaction_stores`.`trans_datetime`
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

	function getBarcodeForRevalDetailsPromo($link,$barcode)
	{
		$query = $link->query(
			"SELECT 
				`store_verification`.`vs_barcode`,
				`store_verification`.`vs_date`,
				`store_verification`.`vs_time`,
				`denomination`.`denomination`,
				`gc_type`.`gctype`
			FROM 
				`store_verification` 
			INNER JOIN
				`gc`
			ON
				`gc`.`barcode_no` = `store_verification`.`vs_barcode`
			INNER JOIN
				`denomination`
			ON
				`gc`.`denom_id` = `denomination`.`denom_id`
			INNER JOIN
				`promo_gc`
			ON
				`promo_gc`.`prom_barcode` = `store_verification`.`vs_barcode`
			INNER JOIN
				`gc_type`
			ON
				`gc_type`.`gc_type_id` = `promo_gc`.`prom_gctype`
			WHERE
				`vs_barcode`='$barcode'
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

	function totalPaymentForRevalidation($link)
	{
		$n = numberOfItemsForRevalidationPerCashierAndStore($link,$_SESSION['gccashier_id'],$_SESSION['gccashier_store']);
		$price =  getRevalidationPayment($link);
		return number_format($n * $price,2);
	}

	function numberOfItemsForRevalidationPerCashierAndStore($link,$cashier,$store)
	{
		$query= $link->query("SELECT 
			`treval_barcode` 
		FROM 
			`temp_reval` 
		WHERE 
			`treval_by`='$cashier'
		AND
			`treval_store`='$store'
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

	function getRevalidationPayment($link)
	{
		$query = $link->query(
			"SELECT 
				`revalidate_price` 
			FROM 
				`cashiering_options`
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->revalidate_price;
		}
	}

	function getLastTransnumByStore($link)
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
			LIMIT 1
		");

		$n = $query->num_rows;
		if($n>0){
			$row = $query->fetch_assoc();
			$row = $row['trans_number'];
			$row++;
			$tn = sprintf("%010d", $row);
		} else {             
		  	$tn = '0000000001';         
		}

		return $tn;
	}

	function getTempReval($link)
	{
		$rows = [];
    	$query = $link->query(
			"SELECT 
				temp_reval.treval_barcode,
				store_verification.vs_tf_denomination,
				temp_reval.treval_charge
			FROM 
				temp_reval
			INNER JOIN
				store_verification
			ON
				store_verification.vs_barcode = temp_reval.treval_barcode
			WHERE 
				temp_reval.treval_by = '".$_SESSION['gccashier_id']."'
			AND
				temp_reval.treval_store = '".$_SESSION['gccashier_store']."'
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

	function getARBalance($link,$code,$field)
	{
		$query = $link->query(
			"SELECT 
				IFNULL(SUM($field),0) as total 
			FROM 
				customer_internal_ar
			WHERE 
				ar_cuscode='$code'
			AND
				ar_type='1'
		");

		if($query)
		{
			if($query->num_rows>0)
			{
				$row = $query->fetch_object();
				if(!is_null($row->total))
				{
					return $row->total;
				}
				else 
				{
					return 0;
				}
			}
			else 
			{
				return '0';
			}
		}
		else 
		{
			return $link->error;
		}
	}

	function getInternalCustomers($link,$group)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				`ci_code`,
				`ci_name`,
				`ci_type`,
				`ci_address`,
				`ci_cstatus`
			FROM 
				`customer_internal` 
			WHERE 
				`ci_group`='$group'
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

	function tempSalesTotal($link,$cashier_id)
	{
		$query = $link->query(
			"SELECT 
				SUM(`denomination`.`denomination`) as total
			FROM
				`temp_sales`
			INNER JOIN
				`gc`
			ON
				`gc`.`barcode_no` = `temp_sales`.`ts_barcode_no`
			INNER JOIN
				`denomination`
			ON
				`gc`.`denom_id` = `denomination`.`denom_id`
			WHERE
				`ts_cashier_id` = '$cashier_id'
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

	function storeLedger($link,$tn,$type,$amt,$transcode,$desc,$store,$discount)
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

	function getInternalCustomerName($link,$code)
	{

	}

	function getTotalDiscount($link,$id,$cid)
	{
		//get temp sales
		$query_temp = $link->query(
			"SELECT 	
				`temp_sales`.`ts_barcode_no`,
				`denomination`.`denomination`,
				`denomination`.`denom_id`
			FROM 
				`temp_sales`
			INNER JOIN
				`gc`
			ON
				`gc`.`barcode_no` = `temp_sales`.`ts_barcode_no`
			INNER JOIN
				`denomination`
			ON
				`denomination`.`denom_id` = `gc`.`denom_id`	
			WHERE 
				`temp_sales`.`ts_cashier_id`='$cid'
		");

		if($query_temp)
		{
			//get customer discount type
			$qerror=0;
			$distype = getCustomerDiscountType($link,$id);
			$totaldis = 0;
			$sub = 0;
			if($distype!=0)
			{
				while($row = $query_temp->fetch_object())
				{
					if($distype==1)
					{
						$query_gd = $link->query(
							"SELECT 
								`cdis_dis` 
							FROM 
								`customer_discounts` 
							WHERE 
								`cdis_cusid`='$id'
							AND
								`cdis_denom_id`='$row->denom_id'
						");

						if($query_gd)
						{
							$row_dis = $query_gd->fetch_object();
							$totaldis +=$row_dis->cdis_dis;
						}
						else 
						{
							$qerror = 1;
							break;
						}
					}
					else 
					{
						$query_gd = $link->query(
							"SELECT 
								`cdis_dis` 
							FROM 
								`customer_discounts` 
							WHERE 
								`cdis_cusid`='$id'
							AND
								`cdis_denom_id`='$row->denom_id'
						");

						if($query_gd)
						{
							$row_dis = $query_gd->fetch_object();
							$sub = $row->denomination * $row_dis->cdis_dis;
							$totaldis +=$sub;
						}
						else 
						{
							$qerror = 1;
							break;
						}
					}
				}

				if($qerror)
				{
					return $link->error;
				}	
				else 
				{
					return $totaldis;
				}			
			}
			else 
			{
				return 0;
			}

		}
		else 
		{
			echo $link->error;
		}
	}

	function getCustomerDiscountType($link,$id)
	{
		$query = $link->query(
			"SELECT 
				`ci_distype` 
			FROM 
				`customer_internal` 
			WHERE 
				`ci_code` = '$id'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->ci_distype;
		}
	}

	function linediscountTotal($link)
	{
		$query = $link->query(
			"SELECT
				SUM(`tsd_disc_amt`) as linedisc
			FROM
				`temp_sales_discountby` 
			WHERE 
				`tsd_cashier` ='".$_SESSION['gccashier_id']."'			
		");

		if($query)
		{
			$row = $query->fetch_object();
			$total = $row->linedisc;
			return $total = is_null($total) ? 0 : $total;
		}
	}

	function docdiscount($link)
	{
		$t =  checkTotal($link);
		// $linedisc = is_null(linediscountTotal($link)) ? 0 : linediscountTotal($link);

		// $t = $subtotal - $linedisc;

		$query = $link->query(
			"SELECT 
				`docdis_discountype`,
				`docdis_pecentage`,
				`docdis_amt`

			FROM 
				`temp_sales_docdiscount` 
			WHERE 
				`docdis_cashierid`='".$_SESSION['gccashier_id']."'
		");

		if($query)
		{
			if($query->num_rows > 0 )
			{
				$row = $query->fetch_object();

				$dt = $row->docdis_discountype;
				if($dt==1)
				{
					return $tot = $t * $row->docdis_pecentage;
				}
				elseif($dt==2) 
				{
					return $row->docdis_amt;
				}

			}
			else 
			{
				return 0;
			}
		}
	}

	function totalwithlinedisc($link)
	{		
		$t = checkTotal($link) - linediscountTotal($link);
		return $t;
		// if(checkifhasdocdiscount($link)>0)
		// {
		// 	return getRow($link,'docdis_discountype','temp_sales_docdiscount','docdis_cashierid',$_SESSION['gccashier_id']);
		// }
		// else 
		// {
		// 	return $t;
		// }
	}

	function checkifhasdocdiscount($link)
	{
		$query = $link->query(
			"SELECT 
				`docdis_id` 
			FROM 
				`temp_sales_docdiscount` 
			WHERE 
				`docdis_cashierid`='".$_SESSION['gccashier_id']."'
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

	function getRow($link,$select,$table,$row,$field)
	{
		$query = $link->query(
			"SELECT 
				$select
			FROM
				$table
			WHERE
				$row = $field
		");

		if($query)
		{
			$r = $query->fetch_object();
			return $r->$select;
		}
		else 
		{
			return $link->error;
		}
	}

	function getAllLineDiscByManagerAndCashier($link,$super,$cashier)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				`tsd_barcode` 
			FROM 
				`temp_sales_discountby` 
			WHERE 
				`tsd_cashier`='$cashier'
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

	function getTempSales($link,$cashierid)
	{
		$rows = [];
		$query = $link->query(
			"SELECT
				`temp_sales`.`ts_barcode_no`,
				`denomination`.`denom_id`,
				`gc_location`.`loc_gc_type`
			FROM
				`temp_sales`
			INNER JOIN
				`gc`
			ON
				`temp_sales`.`ts_barcode_no`=`gc`.`barcode_no`	
			INNER JOIN
				`denomination`
			ON
				`gc`.`denom_id` = `denomination`.`denom_id`
			INNER JOIN 
				`gc_location`
			ON
				`temp_sales`.`ts_barcode_no`=`gc_location`.`loc_barcode_no` 
			WHERE 
				`ts_cashier_id`='$cashierid'	
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

	function getTempDiscountBy($link,$cashierid)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				`docdis_cashierid`,
				`docdis_superid`,
				`docdis_discountype`,
				`docdis_pecentage`,
				`docdis_amt`
			FROM 
				`temp_sales_docdiscount` 
			WHERE 
				`docdis_cashierid`='$cashierid'
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
			$rows[] = $link->error;
		}
	}

	function getStoreSales($link,$cashierid)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				denomination.denom_id,count(*) as counter,
				SUM(denomination) as sums
			FROM 
				`temp_sales`
			INNER JOIN 
				gc
			ON
				temp_sales.ts_barcode_no = gc.barcode_no
			INNER JOIN 
				denomination
			ON
				gc.denom_id = denomination.denom_id
			WHERE `ts_cashier_id` = '$cashierid'				
			GROUP BY 
			`denom_id`
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
			$rows[] = $link->error;
		}
	}

	function deleteData($link,$table,$where)
	{
		$query = $link->query(
			"DELETE FROM 
				$table 
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

	function convertDateToSqlDate($date)
	{
		$dateAr = explode("/", $date);
		return $dateAr[2].'-'.$dateAr[0].'-'.$dateAr[1];
	}

	function getFullnameStoreStaff($link, $id)
	{
		$query = $link->query(
			"SELECT 
				`ss_firstname`,
				`ss_lastname`
			FROM 
				`store_staff` 
			WHERE 
				`ss_id`='$id'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return ucwords($row->ss_firstname.' '.$row->ss_lastname);
		}
		else 
		{
			return $link->error;
		}
	}

	function getNumberofTrans($link,$d1,$d2,$cashier,$store,$trans)
	{
		$rows = [];

		if($trans==1)
		{
			$qt = "AND `transaction_stores`.`trans_cashier`='$cashier'";
		}
		elseif ($trans==2) 
		{
			$qt = "";
		}

		$query = $link->query(
			"SELECT 
				* 
			FROM 
				`transaction_stores` 
			WHERE				
				`transaction_stores`.`trans_store`='$store'
			AND
				`transaction_stores`.`trans_datetime` >= '$d1'
			AND
				`transaction_stores`.`trans_datetime` <= '$d2'
			$qt
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

	function getTransactionsByModeAndStoreTotal($link,$store,$cashier,$mode,$d1,$d2,$trans)
	{
		if($trans==1)
		{
			$qt = "AND `transaction_stores`.`trans_cashier`='$cashier'";
		}
		elseif ($trans==2) 
		{
			$qt = "";
		}

		$query = $link->query(
			"SELECT 
				IFNULL(COUNT(`transaction_payment`.`payment_amountdue`),0) as cnt,
				IFNULL(SUM(`transaction_payment`.`payment_amountdue`),0.00) as cash
			FROM 
				`transaction_stores` 
			INNER JOIN
				`transaction_payment`
			ON
				`transaction_payment`.`payment_trans_num` = `transaction_stores`.`trans_sid`
			WHERE
				`transaction_stores`.`trans_store`='$store'
			AND
				`transaction_stores`.`trans_datetime` >= '$d1'
			AND
				`transaction_stores`.`trans_datetime` <= '$d2'
			AND
				`transaction_stores`.`trans_type`='$mode'
			$qt
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

	function itemsSoldPOS($link,$store,$cashier,$d1,$d2,$trans)
	{

		if($trans==1)
		{
			$qt = "AND `transaction_stores`.`trans_cashier`='$cashier'";
		}
		elseif ($trans==2) 
		{
			$qt = "";
		}		

		$query = $link->query(
			"SELECT 
				IFNULL(COUNT(`sales_id`),0) as cnt 
			FROM 
				`transaction_sales`
			INNER JOIN
				`transaction_stores`
			ON
				`transaction_stores`.`trans_sid` = `transaction_sales`.`sales_transaction_id`
			WHERE 
				`transaction_stores`.`trans_datetime` >= '$d1'
			AND
				`transaction_stores`.`trans_datetime` <= '$d2'
			AND
				`transaction_stores`.`trans_store`='$store'
			$qt
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

	function getServiceCharges($link,$store,$cashier,$d1,$d2,$trans)
	{
		if($trans==1)
		{
			$qt = "AND transaction_stores.trans_cashier='$cashier'";
		}
		elseif ($trans==2) 
		{
			$qt = "";
		}	

		$query = $link->query(
			"SELECT 
				IFNULL(SUM(transaction_refund_details.trefundd_servicecharge),0.00) as scharge,
				COUNT(transaction_stores.trans_sid) as scount
			FROM 
				transaction_stores
			INNER JOIN
				transaction_refund_details
			ON
				transaction_refund_details.trefundd_trstoresid = transaction_stores.trans_sid
			WHERE 
				transaction_stores.trans_datetime >= '$d1'
			AND
				transaction_stores.trans_datetime <= '$d2'
			AND
				transaction_stores.trans_store='$store'
			$qt
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

	function getServiceChargesEOS($link,$store,$cashier)
	{
		$query = $link->query(
			"SELECT
		    IFNULL(SUM(transaction_refund_details.trefundd_servicecharge),0.00) as scharge,
		    COUNT(transaction_stores.trans_sid) as scount
		FROM 
			transaction_stores
		INNER JOIN
			transaction_refund_details
		ON
			transaction_refund_details.trefundd_trstoresid = transaction_stores.trans_sid
		WHERE
			DATE(transaction_stores.trans_datetime) <= CURDATE()
		AND
			transaction_stores.trans_store='$store'
		AND
			transaction_stores.trans_cashier='$cashier'
		AND
			transaction_stores.trans_eos=''
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

	function getServiceChargesEOD($link,$store)
	{
		$query = $link->query(
			"SELECT
		    IFNULL(SUM(transaction_refund_details.trefundd_servicecharge),0.00) as scharge,
		    COUNT(transaction_stores.trans_sid) as scount
		FROM 
			transaction_stores
		INNER JOIN
			transaction_refund_details
		ON
			transaction_refund_details.trefundd_trstoresid = transaction_stores.trans_sid
		WHERE
			DATE(`transaction_stores`.`trans_datetime`) <= CURDATE()
		AND
			`transaction_stores`.`trans_store`='$store'
		AND
			`transaction_stores`.`trans_yreport`='0'
		AND
			`transaction_stores`.`trans_eos`!=''
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

	function numPayingCustomersPOS($link,$store,$cashier,$d1,$d2,$trans)
	{
		if($trans==1)
		{
			$qt = "AND `transaction_stores`.`trans_cashier`='$cashier'";
		}
		elseif ($trans==2) 
		{
			$qt = "";
		}

		$query = $link->query(
			"SELECT 
				IFNULL(COUNT(`transaction_stores`.`trans_sid`),0) as cnt
			FROM
				`transaction_stores`

			WHERE
				`transaction_stores`.`trans_store`='$store'
			AND
				`transaction_stores`.`trans_datetime` >= '$d1'
			AND
				`transaction_stores`.`trans_datetime` <= '$d2'
			$qt
		");		

		if($query)
		{	
			$row = $query->fetch_object();
			return $row->cnt;
		}
		else 
		{
			return  $link->error;
		}

	}

	function numTransactionsPOS($link,$store,$cashier,$d1,$d2,$trans)
	{
		if($trans==1)
		{
			$qt = "AND `transaction_stores`.`trans_cashier`='$cashier'";
		}
		elseif ($trans==2) 
		{
			$qt = "";
		}

		$query = $link->query(
			"SELECT 
				IFNULL(COUNT(`transaction_stores`.`trans_sid`),0) as cnt
			FROM
				`transaction_stores`

			WHERE
				`transaction_stores`.`trans_store`='$store'
			AND
				`transaction_stores`.`trans_datetime` >= '$d1'
			AND
				`transaction_stores`.`trans_datetime` <= '$d2'
			$qt
		");		

		if($query)
		{	
			$row = $query->fetch_object();
			return $row->cnt;
		}
		else 
		{
			return  $link->error;
		}		
	}

	function voidItemsPOS($link,$store,$cashier,$d1,$d2,$trans)
	{
		if($trans==1)
		{
			$qt = "AND `store_void_items`.`svi_cashier`='$cashier'";
		}
		elseif ($trans==2) 
		{
			$qt = "";
		}

		$query = $link->query(
			"SELECT 
				IFNULL(COUNT(`store_void_items`.`svi_id`),0) as cnt, 
				IFNULL(SUM(`denomination`.`denomination`),0.00) as total
			FROM 
				`store_void_items` 
			INNER JOIN
				`gc`
			ON
				`gc`.`barcode_no` = `store_void_items`.`svi_barcodes`
			INNER JOIN
				`denomination`
			ON
				`denomination`.`denom_id` = `gc`.`denom_id`
			WHERE
				`store_void_items`.`svi_store`='$store'
			AND
				`store_void_items`.`svi_datetime` >= '$d1'
			AND
				`store_void_items`.`svi_datetime` <= '$d2'
			$qt
		");		

		if($query)
		{	
			$row = $query->fetch_object();
			return $row;
		}
		else 
		{
			return  $link->error;
		}		
	}

	function getTransactionsGroupByCreditCard($link,$store,$cashier,$mode,$d1,$d2,$trans)
	{
		if($trans==1)
		{
			$qt = "AND `transaction_stores`.`trans_cashier`='$cashier'";
		}
		elseif ($trans==2) 
		{
			$qt = "";
		}

		$rows = [];
		$query = $link->query(
			"SELECT 
				`transaction_stores`.`trans_sid`,
				`credit_cards`.`ccard_name`,
				SUM(`transaction_payment`.`payment_amountdue`) as amount,
				COUNT(`transaction_stores`.`trans_sid`) as n
			FROM
				`transaction_stores`
			INNER JOIN
				`transaction_payment`
			ON
				`transaction_payment`.`payment_trans_num` = `transaction_stores`.`trans_sid`
			INNER JOIN
				`creditcard_payment`
			ON
				`creditcard_payment`.`cctrans_transid` = `transaction_stores`.`trans_sid`
			INNER JOIN
				`credit_cards`
			ON
				`credit_cards`.`ccard_id` = `creditcard_payment`.`cc_creaditcard`
			WHERE
				`transaction_stores`.`trans_store`='$store'
			AND
				`transaction_stores`.`trans_datetime` >= '$d1'
			AND
				`transaction_stores`.`trans_datetime` <= '$d2'
			AND
				`transaction_stores`.`trans_type`='$mode'
			$qt
			GROUP BY
				`credit_cards`.`ccard_id`
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
			return  $link->error;
		}
	}

	function getAR($link,$store,$cashier,$mode,$d1,$d2,$trans)
	{

		if($trans==1)
		{
			$qt = "AND `transaction_stores`.`trans_cashier`='$cashier'";
		}
		elseif ($trans==2) 
		{
			$qt = "";
		}

		$rows = [];

		$query = $link->query(
		"SELECT 
			`transaction_stores`.`trans_sid`,
			`customer_internal`.`ci_group`,
			SUM(`transaction_payment`.`payment_amountdue`) as amount,
			COUNT(`transaction_stores`.`trans_sid`) as c
		FROM
			`transaction_stores`
		INNER JOIN
			`transaction_payment`
		ON
			`transaction_payment`.`payment_trans_num` = `transaction_stores`.`trans_sid`
		INNER JOIN
			`customer_internal_ar`
		ON
			`customer_internal_ar`.`ar_trans_id` = `transaction_stores`.`trans_sid`
		INNER JOIN
			`customer_internal`
		ON
			`customer_internal`.`ci_code` = `customer_internal_ar`.`ar_cuscode`
		WHERE
			`transaction_stores`.`trans_store`='$store'
		AND
			`transaction_stores`.`trans_datetime` >= '$d1'
		AND
			`transaction_stores`.`trans_datetime` <= '$d2'
		AND
			`transaction_stores`.`trans_type`='$mode'
		$qt
		GROUP BY
			`customer_internal`.`ci_group`
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

	function getDocDiscountBYStoreAndDate($link,$store,$cashier,$d1,$d2,$trans)
	{
		if($trans==1)
		{
			$qt = "AND `transaction_stores`.`trans_cashier`='$cashier'";
		}
		elseif ($trans==2) 
		{
			$qt = "";
		}

		$rows = [];

		$query = $link->query(
			"SELECT 
				COUNT(`transaction_stores`.`trans_sid`) as cnt,
				SUM(`transaction_payment`.`payment_docdisc`) as totaldocdisc
			FROM
				`transaction_stores`
			INNER JOIN
				`transaction_payment`
			ON
				`transaction_payment`.`payment_trans_num` = `transaction_stores`.`trans_sid`
			WHERE
				`transaction_payment`.`payment_docdisc` > 0.00
			AND
				`transaction_stores`.`trans_store`='$store'
			AND
				`transaction_stores`.`trans_datetime` >= '$d1'
			AND
				`transaction_stores`.`trans_datetime` <= '$d2'
			$qt
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

	function totalLineDiscount($link,$store,$cashier,$d1,$d2,$trans)
	{

		if($trans==1)
		{
			$qt = "AND `transaction_stores`.`trans_cashier`='$cashier'";
		}
		elseif ($trans==2) 
		{
			$qt = "";
		}

		$rows = [];		
		$query = $link->query(
			"SELECT 
				COUNT(`transaction_payment`.`payment_linediscount`) as cnt,
				SUM(`transaction_payment`.`payment_linediscount`) as totallinedisc
			FROM
				`transaction_stores`
			INNER JOIN
				`transaction_payment`
			ON
			`transaction_payment`.`payment_trans_num` = `transaction_stores`.`trans_sid`
			WHERE
				`transaction_payment`.`payment_linediscount` > 0.00
			AND
				`transaction_stores`.`trans_store`='$store'
			AND
				`transaction_stores`.`trans_datetime` >= '$d1'
			AND
				`transaction_stores`.`trans_datetime` <= '$d2'
			$qt
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

	function customerARDiscount($link,$store,$cashier,$d1,$d2,$trans)
	{

		if($trans==1)
		{
			$qt = "AND `transaction_stores`.`trans_cashier`='$cashier'";
		}
		elseif ($trans==2) 
		{
			$qt = "";
		}

		$query = $link->query(
			"SELECT 
				COUNT(`transaction_payment`.`payment_internal_discount`) as cnt,
				SUM(`transaction_payment`.`payment_internal_discount`) as totalcusdisc
			FROM
				`transaction_stores`
			INNER JOIN
				`transaction_payment`
			ON
			`transaction_payment`.`payment_trans_num` = `transaction_stores`.`trans_sid`
			WHERE
				`transaction_payment`.`payment_internal_discount` > 0.00
			AND
				`transaction_stores`.`trans_store`='$store'
			AND
				`transaction_stores`.`trans_datetime` >= '$d1'
			AND
				`transaction_stores`.`trans_datetime` <= '$d2'
			$qt
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

	function getGCRefundByDateAndCashier($link,$store,$cashier,$mode,$d1,$d2,$trans)
	{
		if($trans==1)
		{
			$qt = "AND transaction_stores.trans_cashier='$cashier'";
		}
		elseif ($trans==2) 
		{
			$qt = "";
		}

		$rows = [];

		$query = $link->query(
			"SELECT 
				denomination.denomination,
				IFNULL(SUM(denomination.denomination),0.00) as denomi,
				IFNULL(SUM(transaction_refund.refund_linedisc),0.00) as linediscref,
				IFNULL(SUM(transaction_refund.refund_sdisc),0.00) as subsref,
				COUNT(denomination.denomination) as cnt
			FROM
				transaction_stores
			INNER JOIN
				transaction_refund
			ON
				transaction_refund.refund_trans_id = transaction_stores.trans_sid
			INNER JOIN
				gc
			ON
				gc.barcode_no = transaction_refund.refund_barcode
			INNER JOIN
				denomination
			ON
				denomination.denom_id = gc.denom_id
			WHERE
				transaction_stores.trans_datetime >= '$d1'
			AND
				transaction_stores.trans_datetime <= '$d2'
			AND
				transaction_stores.trans_store = '$store'
			AND
				transaction_stores.trans_type = '$mode'
			$qt
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

	function getGCRefundLineAndSubByDateAndCashier($link,$store,$cashier,$d1,$d2,$trans)
	{
		if($trans==1)
		{
			$qt = "AND transaction_stores.trans_cashier='$cashier'";
		}
		elseif ($trans==2) 
		{
			$qt = "";
		}	

		$query = $link->query(
			"SELECT 
				IFNULL(SUM(transaction_refund_details.trefundd_total_linedisc),0) as linedisc,
				IFNULL(SUM(transaction_refund_details.trefundd_subtotal_disc),0) as subdisc
			FROM 
				transaction_stores 
			INNER JOIN
				transaction_refund_details
			ON
				transaction_refund_details.trefundd_trstoresid = transaction_stores.trans_sid
			WHERE 
				`transaction_stores`.`trans_datetime` >= '$d1'
			AND
				`transaction_stores`.`trans_datetime` <= '$d2'
			AND
				`transaction_stores`.`trans_store`='$store'
			$qt
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

	function getGCRefundLineAndSubByDateAndCashierEOS($link,$store,$cashier)
	{
		$query = $link->query(
			"SELECT 
				IFNULL(SUM(transaction_refund_details.trefundd_total_linedisc),0) as linedisc,
				IFNULL(SUM(transaction_refund_details.trefundd_subtotal_disc),0) as subdisc
			FROM 
				transaction_stores 
			INNER JOIN
				transaction_refund_details
			ON
				transaction_refund_details.trefundd_trstoresid = transaction_stores.trans_sid
			WHERE
				DATE(transaction_stores.trans_datetime) <= CURDATE()
			AND
				transaction_stores.trans_cashier = '$cashier'
			AND
				transaction_stores.trans_store = '$store'
			AND
				transaction_stores.trans_eos = ''
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

	function getuserlogs($link,$userid)
	{
		$query = $link->query(
			"SELECT
				`logs_datetime`
			FROM 
				`userlogs` 
			WHERE 
				`logs_userid`='$userid'
			ORDER BY
				`logs_id`
			DESC
				LIMIT 1
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->logs_datetime;
		}
		else 
		{
			return $link->error;
		}
	}

	function getEOSTrans($link,$store,$cashier)
	{
		$rows = [];

		$query = $link->query(
			"SELECT 
				`trans_sid` 
			FROM 
				`transaction_stores` 
			WHERE 
				`trans_cashier`='$cashier'
			AND
				`trans_store`='$store'
			AND
				`trans_eos`=''
			AND
				DATE(`trans_datetime`) <= CURDATE()
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
			return $link->error;
		}
	}

	function getTransactionsByModeAndStoreTotalEOS($link,$store,$cashier,$mode)
	{
		$query = $link->query(
			"SELECT 
				transaction_stores.trans_datetime,
				SUM(transaction_payment.payment_amountdue) as cash,
				COUNT(transaction_stores.trans_sid) as cnt
			FROM 
				transaction_stores 
			INNER JOIN
				transaction_payment
			ON
				transaction_payment.payment_trans_num = transaction_stores.trans_sid
			WHERE
				transaction_stores.trans_cashier='$cashier'
			AND
				transaction_stores.trans_store='$store'
			AND
				DATE(transaction_stores.trans_datetime) <= CURDATE()
			AND
				transaction_stores.trans_type='$mode'
			AND
				transaction_stores.trans_eos=''
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

	function getTransactionsGroupByCreditCardEOS($link,$store,$cashier,$mode)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				`transaction_stores`.`trans_sid`,
				`credit_cards`.`ccard_name`,
				SUM(`transaction_payment`.`payment_amountdue`) as amount,
				COUNT(`transaction_stores`.`trans_sid`) as n
			FROM
				`transaction_stores`
			INNER JOIN
				`transaction_payment`
			ON
				`transaction_payment`.`payment_trans_num` = `transaction_stores`.`trans_sid`
			INNER JOIN
				`creditcard_payment`
			ON
				`creditcard_payment`.`cctrans_transid` = `transaction_stores`.`trans_sid`
			INNER JOIN
				`credit_cards`
			ON
				`credit_cards`.`ccard_id` = `creditcard_payment`.`cc_creaditcard`
			WHERE
				`transaction_stores`.`trans_cashier`='$cashier'
			AND
				`transaction_stores`.`trans_store`='$store'
			AND
				DATE(`transaction_stores`.`trans_datetime`) <= CURDATE()
			AND
				`transaction_stores`.`trans_type`='$mode'
			AND
				`transaction_stores`.`trans_eos`=''
			GROUP BY
				`credit_cards`.`ccard_id`
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
			return  $link->error;
		}
	}

	function getAREOS($link,$store,$cashier,$mode)
	{
		$rows = [];
			$query = $link->query(
			"SELECT 
				`transaction_stores`.`trans_sid`,
				`customer_internal`.`ci_group`,
				SUM(`transaction_payment`.`payment_amountdue`) as amount,
				COUNT(`transaction_stores`.`trans_sid`) as c
			FROM
				`transaction_stores`
			INNER JOIN
				`transaction_payment`
			ON
				`transaction_payment`.`payment_trans_num` = `transaction_stores`.`trans_sid`
			INNER JOIN
				`customer_internal_ar`
			ON
				`customer_internal_ar`.`ar_trans_id` = `transaction_stores`.`trans_sid`
			INNER JOIN
				`customer_internal`
			ON
				`customer_internal`.`ci_code` = `customer_internal_ar`.`ar_cuscode`
			WHERE
				`transaction_stores`.`trans_cashier`='$cashier'
			AND
				`transaction_stores`.`trans_store`='$store'
			AND
				DATE(`transaction_stores`.`trans_datetime`) <= CURDATE()
			AND
				`transaction_stores`.`trans_type`='$mode'
			AND
				`transaction_stores`.`trans_eos`=''
			GROUP BY
				`customer_internal`.`ci_group`
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

	function getDocDiscountBYStoreAndDateEOS($link,$store,$cashier)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				IFNULL(COUNT(`transaction_stores`.`trans_sid`),0) as cnt,
				IFNULL(SUM(`transaction_payment`.`payment_docdisc`),0.00) as totaldocdisc
			FROM
				`transaction_stores`
			INNER JOIN
				`transaction_payment`
			ON
				`transaction_payment`.`payment_trans_num` = `transaction_stores`.`trans_sid`
			WHERE
				`transaction_payment`.`payment_docdisc` > 0.00
			AND
				`transaction_stores`.`trans_cashier`='$cashier'
			AND
				`transaction_stores`.`trans_store`='$store'
			AND
				`transaction_stores`.`trans_eos`=''
			AND
				DATE(`transaction_stores`.`trans_datetime`) <= CURDATE()
		");

		if($query)
		{
			return $rows[] = $query->fetch_object();
		}
		else
		{
			return $link->error;
		}
	}

	function totalLineDiscountEOS($link,$store,$cashier)
	{
		$rows = [];		
		$query = $link->query(
			"SELECT 
				IFNULL(COUNT(`transaction_payment`.`payment_linediscount`),0) as cnt,
				IFNULL(SUM(`transaction_payment`.`payment_linediscount`),0.00) as totallinedisc
			FROM
				`transaction_stores`
			INNER JOIN
				`transaction_payment`
			ON
			`transaction_payment`.`payment_trans_num` = `transaction_stores`.`trans_sid`
			WHERE
				`transaction_payment`.`payment_linediscount` > 0.00
			AND
				`transaction_stores`.`trans_cashier`='$cashier'
			AND
				`transaction_stores`.`trans_store`='$store'
			AND
				`transaction_stores`.`trans_eos`=''
			AND
				DATE(`transaction_stores`.`trans_datetime`) <= CURDATE()
		");

		if($query)
		{
			return $rows[] = $query->fetch_object();
		}
		else
		{
			return $link->error;
		}		
	}

	function customerARDiscountEOS($link,$store,$cashier)
	{
		$rows = [];		
		$query = $link->query(
			"SELECT 
				IFNULL(COUNT(`transaction_payment`.`payment_internal_discount`),0) as cnt,
				IFNULL(SUM(`transaction_payment`.`payment_internal_discount`),0.00) as totalcusdisc
			FROM
				`transaction_stores`
			INNER JOIN
				`transaction_payment`
			ON
			`transaction_payment`.`payment_trans_num` = `transaction_stores`.`trans_sid`
			WHERE
				`transaction_payment`.`payment_internal_discount` > 0.00
			AND
				`transaction_stores`.`trans_cashier`='$cashier'
			AND
				`transaction_stores`.`trans_store`='$store'
			AND
				`transaction_stores`.`trans_eos`=''
			AND
				DATE(`transaction_stores`.`trans_datetime`) <= CURDATE();
		");

		if($query)
		{
			return $rows[] = $query->fetch_object();
		}
		else
		{
			return $link->error;
		}
	}

	function getGCRefundByDateAndCashierEOS($link,$store,$cashier,$mode)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				denomination.denomination,
				IFNULL(SUM(denomination.denomination),0.00) as denomi,
				IFNULL(SUM(transaction_refund.refund_linedisc),0.00) as linediscref,
				IFNULL(SUM(transaction_refund.refund_sdisc),0.00) as subsref,
				COUNT(denomination.denomination) as cnt
			FROM
				transaction_stores
			INNER JOIN
				transaction_refund
			ON
				transaction_refund.refund_trans_id = transaction_stores.trans_sid
			INNER JOIN
				gc
			ON
				gc.barcode_no = transaction_refund.refund_barcode
			INNER JOIN
				denomination
			ON
				denomination.denom_id = gc.denom_id
			WHERE
				DATE(transaction_stores.trans_datetime) <= CURDATE()
			AND
				transaction_stores.trans_cashier = '$cashier'
			AND
				transaction_stores.trans_store = '$store'
			AND
				transaction_stores.trans_type = '$mode'
			AND
				transaction_stores.trans_eos = ''
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

	function itemsSoldEOS($link,$store,$cashier)
	{
		$query = $link->query(
			"SELECT 
				IFNULL(COUNT(`sales_id`),0) as cnt 
			FROM 
				`transaction_sales`
			INNER JOIN
				`transaction_stores`
			ON
				`transaction_stores`.`trans_sid` = `transaction_sales`.`sales_transaction_id`
			WHERE 
				DATE(`transaction_stores`.`trans_datetime`) <= CURDATE()
			AND
				`transaction_stores`.`trans_store`='$store'
			AND
				`transaction_stores`.`trans_cashier`='$cashier'
			AND
				`transaction_stores`.`trans_eos`=''
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

	function numPayingCustomersEOS($link,$store,$cashier)
	{
		$query = $link->query(
			"SELECT 
				IFNULL(COUNT(`transaction_stores`.`trans_sid`),0) as cnt
			FROM
				`transaction_stores`
			WHERE 
				DATE(`transaction_stores`.`trans_datetime`) <= CURDATE()
			AND
				`transaction_stores`.`trans_store`='$store'
			AND
				`transaction_stores`.`trans_cashier`='$cashier'
			AND
				`transaction_stores`.`trans_eos`=''				
		");		

		if($query)
		{	
			$row = $query->fetch_object();
			return $row->cnt;
		}
		else 
		{
			return  $link->error;
		}
	}

	function numTransactionsEOS($link,$store,$cashier)
	{
		$query = $link->query(
			"SELECT 
				IFNULL(COUNT(`transaction_stores`.`trans_sid`),0) as cnt
			FROM
				`transaction_stores`
			WHERE 
				DATE(`transaction_stores`.`trans_datetime`) <= CURDATE()
			AND
				`transaction_stores`.`trans_store`='$store'
			AND
				`transaction_stores`.`trans_cashier`='$cashier'
			AND
				`transaction_stores`.`trans_eos`=''				
		");		

		if($query)
		{	
			$row = $query->fetch_object();
			return $row->cnt;
		}
		else 
		{
			return  $link->error;
		}
	}

	function voidItemsEOS($link,$store,$cashier)
	{
		$query = $link->query(
			"SELECT 
				IFNULL(COUNT(`store_void_items`.`svi_id`),0) as cnt, 
				IFNULL(SUM(`denomination`.`denomination`),0.00) as total
			FROM 
				`store_void_items` 
			INNER JOIN
				`gc`
			ON
				`gc`.`barcode_no` = `store_void_items`.`svi_barcodes`
			INNER JOIN
				`denomination`
			ON
				`denomination`.`denom_id` = `gc`.`denom_id`
			WHERE 
				DATE(`store_void_items`.`svi_datetime`) = CURDATE()
			AND
				`store_void_items`.`svi_store`='$store'
			AND
				`store_void_items`.`svi_cashier` = '$cashier'
		");

		if($query)
		{	
			$row = $query->fetch_object();
			return $row;
		}
		else 
		{
			return  $link->error;
		}		
	}

	function transactionStartEnd($link,$store,$cashier,$ordertype)
	{
		$query = $link->query(
			"SELECT 
				`trans_number`,
				`trans_sid` 
			FROM 
				`transaction_stores` 
			WHERE 
				`trans_cashier`='$cashier'
			AND
				`trans_store`='$store'
			AND
				`trans_eos`=''
			AND
				DATE(`trans_datetime`) <= CURDATE()
			ORDER BY
				`trans_number`
			$ordertype
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

	function transactionStartEndPOS($link,$store,$cashier,$d1,$d2,$trans,$ordertype)
	{
		if($trans==1)
		{
			$qt = "AND `transaction_stores`.`trans_cashier`='$cashier'";
		}
		elseif ($trans==2) 
		{
			$qt = "";
		}

		$query = $link->query(
			"SELECT 
				`trans_number`,
				`trans_sid` 
			FROM 
				`transaction_stores` 
			WHERE 
				`trans_store`='$store'
			AND
				`transaction_stores`.`trans_datetime` >= '$d1'
			AND
				`transaction_stores`.`trans_datetime` <= '$d2'
			$qt
			ORDER BY
				`trans_number`
			$ordertype
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

	function getTextFileBalancesxx($link,$todays_date)
	{
		$haserror = 0;
		$query_getxtfile = $link->query(
			"SELECT 
				`store_verification`.`vs_tf`,
				`users`.`username`,
				`store_verification`.`vs_barcode`
			FROM 
				`store_verification` 
			INNER JOIN
				`users`
			ON
				`store_verification`.`vs_by` = `users`.`user_id`
			WHERE
				`users`.`store_assigned`='".$_SESSION['gccashier_store']."'
			AND
				`store_verification`.`vs_date` = '$todays_date'
		");

		if($query_getxtfile)
		{
			//$x=0;
			$folder = 'textfiles/validation';
			if($query_getxtfile->num_rows>0)
			{
				while ($row = $query_getxtfile->fetch_object()) {
					$arr_f = [];
					$file = $folder.'/'.$row->vs_tf;
					if(checkFolder($folder)){
						$allowedExts = array("txt");
						$temp = explode(".", $file);
						$extension = end($temp);
						if((mime_content_type($file)=="text/plain")&&
						in_array($extension, $allowedExts))
						{
							$r_f = fopen($file,'r');
								while(!feof($r_f)) 
								{
									$arr_f[] = fgets($r_f);
								}
							fclose($r_f);
							$c = explode(",",$arr_f[4]);
							$c = $c[1];
							$ins = $link->query(
								"UPDATE 
									`store_verification` 
								SET 
									`vs_tf_used`='*',
									`vs_tf_balance`='$c' 
								WHERE 
									`vs_barcode`='$row->vs_barcode'													
							");

							if(!$ins)
							{
								$haserror = 1;
								break;
							}
						}
						else 
						{
							$haserror = 1;
							break;
						}
					}
					else 
					{
						$haserror = 1;
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
				return true;
			}
		}
		else 
		{
			return false;
		}
	}

	// function getTextFileBalances($link,$todays_date)
	// {
	// 	$query_getxtfile = $link->query(
	// 		"SELECT 
	// 			`store_verification`.`vs_tf`,
	// 			`users`.`username`,
	// 			`store_verification`.`vs_barcode`
	// 		FROM 
	// 			`store_verification` 
	// 		INNER JOIN
	// 			`users`
	// 		ON
	// 			`store_verification`.`vs_by` = `users`.`user_id`
	// 		WHERE
	// 			`users`.`store_assigned`='".$_SESSION['gc_store']."'
	// 		AND
	// 			`store_verification`.`vs_date` = '$todays_date'
	// 		AND 
	// 			`store_verification`.`vs_tf_balance` = ''
	// 	");

	// 	if($query_getxtfile)
	// 	{
	// 		//$x=0;
	// 		$folder = 'textfiles/validation';
	// 		while ($row = $query_getxtfile->fetch_object()) {
	// 			$arr_f = [];
	// 			$file = $folder.'/'.$row->vs_tf;
	// 			if(checkFolder($folder)){
	// 				$allowedExts = array("txt");
	// 				$temp = explode(".", $file);
	// 				$extension = end($temp);
	// 				if((mime_content_type($file)=="text/plain")&&
	// 				in_array($extension, $allowedExts))
	// 				{
	// 					$r_f = fopen($file,'r');
	// 						while(!feof($r_f)) 
	// 						{
	// 							$arr_f[] = fgets($r_f);
	// 						}
	// 					fclose($r_f);
	// 					$c = explode(",",$arr_f[4]);
	// 					$c = $c[1];
	// 					$ins = $link->query(
	// 						"UPDATE 
	// 							`store_verification` 
	// 						SET 
	// 							`vs_tf_used`='*',
	// 							`vs_tf_balance`='$c' 
	// 						WHERE 
	// 							`vs_barcode`='$row->vs_barcode'													
	// 					");
	// 				}
	// 			}
	// 		}
	// 	}
	// 	else 
	// 	{
	// 		return false;
	// 	}
	// }

	function getGrossSalesEOD($link,$store)
	{
		$query = $link->query(
			"SELECT 
				IFNULL(SUM(`transaction_payment`.`payment_stotal`),0.00) as grosssales		
			FROM 
				`transaction_stores` 
			INNER JOIN
				`transaction_payment`
			ON
				`transaction_payment`.`payment_trans_num` = `transaction_stores`.`trans_sid`
			WHERE 
				`trans_store`='$store'
			AND	
				`trans_yreport`='0'
			AND
				`trans_eos`!=''
			AND
				DATE(`trans_datetime`) <= CURDATE()
			AND
				(`trans_type`='1'
			OR 
				`trans_type`='2'
			OR
				`trans_type`='3')			
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->grosssales;
		}
		else 
		{
			return $link->error;
		}
	}

	function getBNGSalesEOD($link,$store)
	{


		$query = $link->query(
			"SELECT 
				IFNULL(SUM(beamandgo_barcodes.bngbar_value),0.00) as bngsales		 
			FROM 
				beamandgo_transaction 
			INNER JOIN
				beamandgo_barcodes
			ON
				beamandgo_barcodes.bngbar_trid = beamandgo_transaction.bngver_id 	
			WHERE 
				beamandgo_transaction.bngver_storeid='$store'
			AND
				beamandgo_transaction.bngver_eod IS NULL	
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->bngsales;
		}
		else 
		{
			return $link->error;
		}
	}

	function getTotalRevalPaymentEOD($link,$store)
	{
		$query = $link->query(
			"SELECT 
				IFNULL(SUM(transaction_payment.payment_amountdue),0.00) as cash
			FROM 
				transaction_stores
			INNER JOIN
				transaction_payment
			ON
				transaction_payment.payment_trans_num = transaction_stores.trans_sid
			WHERE
				transaction_stores.trans_store='$store'
			AND	
				transaction_stores.trans_yreport='0'
			AND
				transaction_stores.trans_eos!=''
			AND
				DATE(transaction_stores.trans_datetime) <= CURDATE()
			AND
				transaction_stores.trans_type='6'"
		);

		if($query)
		{
			$row = $query->fetch_object();
			return $row->cash;
		}
		else 
		{
			return $link->error;
		}
	}

	function getDocDiscountEOD($link,$store)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				IFNULL(COUNT(`transaction_stores`.`trans_sid`),0) as cnt,
				IFNULL(SUM(`transaction_payment`.`payment_docdisc`),0.00) as totaldocdisc
			FROM
				`transaction_stores`
			INNER JOIN
				`transaction_payment`
			ON
				`transaction_payment`.`payment_trans_num` = `transaction_stores`.`trans_sid`
			WHERE
				`transaction_payment`.`payment_docdisc` > 0.00
			AND
				`transaction_stores`.`trans_store`='$store'
			AND
				DATE(`transaction_stores`.`trans_datetime`) <= CURDATE()
			AND
				`trans_yreport`='0'
			AND
				`trans_eos`!=''
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

	function getLineDiscountEOD($link,$store)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				IFNULL(COUNT(`transaction_stores`.`trans_sid`),0) as cnt,
				IFNULL(SUM(`transaction_payment`.`payment_linediscount`),0.00) as totallinedisc
			FROM
				`transaction_stores`
			INNER JOIN
				`transaction_payment`
			ON
				`transaction_payment`.`payment_trans_num` = `transaction_stores`.`trans_sid`
			WHERE
				`transaction_payment`.`payment_linediscount` > 0.00
			AND
				`transaction_stores`.`trans_store`='$store'
			AND
				DATE(`transaction_stores`.`trans_datetime`) <= CURDATE()
			AND
				`trans_yreport`='0'
			AND
				`trans_eos`!=''
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

	function getARDiscountEOD($link,$store)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				IFNULL(COUNT(`transaction_stores`.`trans_sid`),0) as cnt,
				IFNULL(SUM(`transaction_payment`.`payment_internal_discount`),0.00) as totalardisc
			FROM
				`transaction_stores`
			INNER JOIN
				`transaction_payment`
			ON
				`transaction_payment`.`payment_trans_num` = `transaction_stores`.`trans_sid`
			WHERE
				`transaction_payment`.`payment_internal_discount` > 0.00
			AND
				`transaction_stores`.`trans_store`='$store'
			AND
				DATE(`transaction_stores`.`trans_datetime`) <= CURDATE()
			AND
				`trans_yreport`='0'
			AND
				`trans_eos`!=''
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

	function getGCRefundByDateAndCashierEOD($link,$store,$mode)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				denomination.denomination,
				IFNULL(SUM(denomination.denomination),0.00) as denomi,
				IFNULL(SUM(transaction_refund.refund_linedisc),0.00) as linediscref,
				IFNULL(SUM(transaction_refund.refund_sdisc),0.00) as subsref,
				COUNT(denomination.denomination) as cnt
			FROM
				transaction_stores
			INNER JOIN
				transaction_refund
			ON
				transaction_refund.refund_trans_id = transaction_stores.trans_sid
			INNER JOIN
				`gc`
			ON
				`gc`.`barcode_no` = `transaction_refund`.`refund_barcode`
			INNER JOIN
				`denomination`
			ON
				`denomination`.`denom_id` = `gc`.`denom_id`
			WHERE
				DATE(`transaction_stores`.`trans_datetime`) <= CURDATE()
			AND
				`transaction_stores`.`trans_store` = '$store'
			AND
				`transaction_stores`.`trans_type` = '$mode'
			AND
				`transaction_stores`.`trans_yreport`='0'
			AND
				`transaction_stores`.`trans_eos`!=''
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

	function numPayingCustomersEOD($link,$store)
	{
		$query = $link->query(
			"SELECT 
				IFNULL(COUNT(`transaction_stores`.`trans_sid`),0) as cnt
			FROM
				`transaction_stores`
			WHERE 
				DATE(`transaction_stores`.`trans_datetime`) <= CURDATE()
			AND
				`transaction_stores`.`trans_store`='$store'
			AND
				`transaction_stores`.`trans_yreport`='0'
			AND
				`transaction_stores`.`trans_eos`!=''				
		");		

		if($query)
		{	
			$row = $query->fetch_object();
			return $row->cnt;
		}
		else 
		{
			return  $link->error;
		}
	}

	function numTransactionsEOD($link,$store)
	{
		$query = $link->query(
			"SELECT 
				IFNULL(COUNT(`transaction_stores`.`trans_sid`),0) as cnt
			FROM
				`transaction_stores`
			WHERE 
				DATE(`transaction_stores`.`trans_datetime`) <= CURDATE()
			AND
				`transaction_stores`.`trans_store`='$store'
			AND
				`transaction_stores`.`trans_yreport`='0'
			AND
				`transaction_stores`.`trans_eos`!=''				
		");		

		if($query)
		{	
			$row = $query->fetch_object();
			return $row->cnt;
		}
		else 
		{
			return  $link->error;
		}
	}

	function voidItemsEOD($link,$store)
	{
		$query = $link->query(
			"SELECT 
				IFNULL(COUNT(`store_void_items`.`svi_id`),0) as cnt, 
				IFNULL(SUM(`denomination`.`denomination`),0.00) as total
			FROM 
				`store_void_items` 
			INNER JOIN
				`gc`
			ON
				`gc`.`barcode_no` = `store_void_items`.`svi_barcodes`
			INNER JOIN
				`denomination`
			ON
				`denomination`.`denom_id` = `gc`.`denom_id`
			WHERE 
				DATE(`store_void_items`.`svi_datetime`) <= CURDATE()
			AND
				`store_void_items`.`svi_store`='$store'
			AND 
				`store_void_items`.`svi_eod`='0'

		");

		if($query)
		{	
			$row = $query->fetch_object();
			return $row;
		}
		else 
		{
			return  $link->error;
		}	
	}

	function itemsSoldEOD($link,$store)
	{
		$query = $link->query(
			"SELECT 
				IFNULL(COUNT(`sales_id`),0) as cnt 
			FROM 
				`transaction_sales`
			INNER JOIN
				`transaction_stores`
			ON
				`transaction_stores`.`trans_sid` = `transaction_sales`.`sales_transaction_id`
			WHERE 
				DATE(`transaction_stores`.`trans_datetime`) <= CURDATE()
			AND
				`transaction_stores`.`trans_store`='$store'
			AND
				`transaction_stores`.`trans_yreport`='0'
			AND
				`transaction_stores`.`trans_eos`!=''
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

	function getStartEndTransactionsEOD($link,$store,$order)
	{
		$query = $link->query(
			"SELECT 
				`transaction_stores`.`trans_sid`,
				`transaction_stores`.`trans_number`
			FROM
				`transaction_stores`
			WHERE
				`transaction_stores`.`trans_store`='$store'
			AND
				DATE(`transaction_stores`.`trans_datetime`) <= CURDATE()
			AND
				`trans_yreport`='0'
			AND
				`trans_eos`!=''
			ORDER BY
				`trans_sid`
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

	function getTransactionsEOD($link,$store)
	{
		$query = $link->query(
			"SELECT 
				`transaction_stores`.`trans_sid`
			FROM
				`transaction_stores`
			WHERE
				`transaction_stores`.`trans_store`='$store'
			AND
				DATE(`transaction_stores`.`trans_datetime`) <= CURDATE()
			AND
				`trans_yreport`='0'
			AND
				`trans_eos`!=''
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

	function getTransactionBNG($link,$store)
	{
		$query = $link->query(
			"SELECT 
				* 
			FROM 
				beamandgo_transaction 
			WHERE 
				bngver_storeid='$store'
			AND
				bngver_eod IS NULL
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


	function getTempBarcodesByCashier($link,$cashierid)
	{
		$arr = [];
		$query = "SELECT 
			`temp_sales`.`ts_id`,
			`temp_sales`.`ts_barcode_no`,
			`gc_type`.`gctype`,
			`denomination`.`denomination`
		FROM 
			`temp_sales` 
		INNER JOIN 
			`gc` 
		ON 
			`temp_sales`.`ts_barcode_no` = `gc`.`barcode_no` 
		INNER JOIN 
			`denomination`
		ON
			`gc`.`denom_id` = `denomination`.`denom_id`
		INNER JOIN
			`gc_location`
		ON
			`temp_sales`.`ts_barcode_no` = `gc_location`.`loc_barcode_no`
		INNER JOIN 
			`gc_type`
		ON
			`gc_location`.`loc_gc_type` = `gc_type`.`gc_type_id`
		WHERE 
			`temp_sales`.`ts_cashier_id`='$cashierid'
		ORDER BY 
			`temp_sales`.`ts_id` 
		DESC
		";

		$query = $link->query($query);

		while ($rows = $query->fetch_object()) 
		{
			$query_dis = $link->query(
				"SELECT 
					`tsd_barcode`,
					`tsd_disc_type`,
					`tsd_disc_percent`,
					`tsd_disc_amt`
				FROM 
					`temp_sales_discountby` 
				WHERE 
					`tsd_barcode` = '$rows->ts_barcode_no'
			");

			if($query_dis)
			{
				if($query_dis->num_rows > 0)
				{
					while ($rowsdis = $query_dis->fetch_object()) 
					{
						$distype = $rowsdis->tsd_disc_type;
						$percent = $rowsdis->tsd_disc_percent;
						$amount = $rowsdis->tsd_disc_amt;
					}
				}
				else 
				{
					$distype = 0;
					$percent = '0.00000';
					$amount = 0;			
				}
			}

			switch ($distype) 
			{
				case '1':
					$distype = 'percent';
					break;
				case '2':
					$distype = 'amount';
					break;				
				default:
					$distype = '';
					break;
			}

			$tot = $rows->denomination - $amount;
			$arr[] =  array(
			'barcode' => $rows->ts_barcode_no,
			'type' => $rows->gctype,
			'denomination' => $rows->denomination,
			'disctype' => $distype,
			'percent' => $percent,
			'discamount' => $amount,
			'netamt' => $tot
			);
		}

		return $arr;
	}

	function receiptItemsByCashier($link,$cashier)
	{
		$rows = [];
		$query = $link->query(
			"SELECT 
				`temp_sales`.`ts_barcode_no`,
				`denomination`.`denomination`
			FROM 
				`temp_sales` 
			INNER JOIN 
				gc 
			ON 
				temp_sales.ts_barcode_no = gc.barcode_no 
			INNER JOIN 
				denomination
			ON
				gc.denom_id = denomination.denom_id
			INNER JOIN
				gc_location
			ON
				temp_sales.ts_barcode_no = gc_location.loc_barcode_no
			INNER JOIN 
				gc_type
			ON
				gc_location.loc_gc_type = gc_type.gc_type_id
			WHERE 
				`ts_cashier_id`='$cashier'
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

	function countTempGC($link,$cashier)
	{
		$query = $link->query(
			"SELECT 
				IFNULL(COUNT(`ts_barcode_no`),0) as cnt
			FROM 
				`temp_sales`
			WHERE 
				`ts_cashier_id`='$cashier'
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->cnt;
		}
	}

	function checkStoreLoginCredential($link,$uname,$uid,$upass,$type,$store)
	{
		$query = $link->query(
			"SELECT 
				`ss_username`
			FROM 
				`store_staff`
			WHERE 
				`ss_username`='$uname'
			AND 
				`ss_password`='$upass'
			AND 
				`ss_idnumber` = '$uid'
			AND
				`ss_store` = '$store'	
			AND
				`ss_usertype`='$type'			
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
		else 
		{
			return false;
		}
	}

	function checkStoreLoginErrors($link,$uname,$uid,$upass,$type,$store)
	{

	}

	function getTransactionSubtotalDiscount($link,$transid)
	{
		$query_subt = $link->query(
			"SELECT 
				`trdocdisc_amnt` 
			FROM 
				`transaction_docdiscount` 
			WHERE 
				`trdocdisc_trid`='$transid'
		");

		if($query_subt)
		{
			if(($query_subt->num_rows) > 0)
			{
				$row = $query_subt->fetch_object();
				return $row->trdocdisc_amnt;
			}
			else 
			{
				$subtotaldis = 0;
			}
		}
		else 
		{
			return $link->error;
		}
	}

	function countData($link,$table,$select,$where,$field)
	{
		$query = $link->query(
			"SELECT 
				$select
			FROM 
				$table 
			WHERE 
				$where
		");

		if($query)
		{
			$row = $query->fetch_object();
			return $row->$field;
		}
	}

	function getTotalRefund($link,$store,$cashier)
	{
		$query = $link->query(
			"SELECT
				IFNULL(COUNT(temp_refund.trfund_barcode),0) as cnt,
				IFNULL(SUM(denomination.denomination),0.00) as denom,
				IFNULL(SUM(temp_refund.trfund_linedisc),0.00) as totlinedisc,
				IFNULL(SUM(temp_refund.trfund_subdisc),0.00) as subdisc,
				IFNULL(SUM(denomination.denomination),0.00) - (IFNULL(SUM(temp_refund.trfund_linedisc),0.00) + IFNULL(SUM(temp_refund.trfund_subdisc),0.00)) as rfundtot
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
				temp_refund.trfund_store='$store'
			AND
				temp_refund.trfund_by='$cashier'
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

	function checkifEODperformed($link,$store,$ip)
	{
		$query = $link->query(
			"SELECT 
				transaction_stores.trans_store
			FROM
				transaction_stores
			WHERE
				transaction_stores.trans_datetime < CURDATE()
			AND
				transaction_stores.trans_store='$store'
			AND
				transaction_stores.trans_ip_address='$ip'
			AND
				transaction_stores.trans_yreport='0'
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

	function getServiceCharge($link,$store,$cashier)
	{
		$query_ser = $link->query(
			"SELECT 
				IFNULL(SUM(sc_charge),0) as charge 
			FROM 
				service_charge 
			WHERE 
				sc_store='$store' 
			AND 
				sc_by='$cashier'
		");

		if($query_ser)
		{
			$row = $query_ser->fetch_object();
			return $row->charge;
		}
	}

	function getstorereceiptstatus($link,$store)
	{
		$query_rec = $link->query(
			"SELECT 
				issuereceipt 
			FROM 
				stores 
			WHERE 
				store_id='$store'
		");

		if($query_rec)
		{
			$row = $query_rec->fetch_object();
			return $row->issuereceipt;
		}
	}

	function insertBudgetLedger($link,$trid,$type,$dbfield,$amount)
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

?>