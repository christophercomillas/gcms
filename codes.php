$response['receipt'] = getstorereceiptstatus($link,$_SESSION['gccashier_store']);

                                if(response['receipt']=='yes')
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
            //  "INSERT INTO 
            //    `temp_refund`
            //  (
            //    `trfund_barcode`, 
            //    `trfund_store`, 
            //    `trfund_by`
            //  ) 
            //  VALUES 
            //  (
            //    '$barcode',
            //    '".$_SESSION['gccashier_store']."',
            //    '".$_SESSION['gccashier_id']."'
            //  )
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


        $.ajax({
          url:'../ajax-cashier.php?request=deletetempandchecktempsales',
          success:function(data)
          {
            var data = JSON.parse(data);
            if(data['stat'])
            {             
              if(flag==0)
              {
                $('.otherincome-mode').hide();
                $('.revalidation').show();  
                $('.content-sales').hide();
                $('.content-revalidate').show();
                $('input#numOnlyreval').focus();
                $('._barcodesreval').load('../ajax-cashier.php?request=loadreval');
                $('.msgreval').val('');
                $('._cashier_totalreval').val('0.00');
                $('.noitemsreval').val(0);
                mode = 5;
              }
            }
            else 
            {
              if(flag==0)
              {
                flag=1;
                BootstrapDialog.show({
                title:'Warning',
                     message: data['msg'],
                     cssClass: 'login-dialog',  
                     onshow: function(dialogRef){
                          
                    },
                    onshown: function(dialogRef){                 
                      
                    },
                    onhide: function(dialogRef){
                        
                    },
                    onhidden: function(dialogRef){
                        $('input#numOnly').focus();                 
                        flag=0;
                    }            
                });
              }
            }

                $('.otherincome-mode').hide();
                $('.revalidation').show();  
                $('.content-sales').hide();
                $('.content-revalidate').show();
                $('input#numOnlyreval').focus();
                $('._barcodesreval').load('../ajax-cashier.php?request=loadreval');
                $('.msgreval').val('');
                $('._cashier_totalreval').val('0.00');
                $('.noitemsreval').val(0);
                mode = 5;



 <?php 
 $link->affected_rows
 updateusers

 
        $response['st'] = 0;
        $user_role = $_POST['usrole'];
        $user_id = $_POST['uid'];
        $username = $_POST['uname'];
        $firstname = $_POST['fname'];
        $lastname = $_POST['lname'];
        $emp_id = $_POST['eid'];
        $usergroup = $_POST['ugroup'];

        if($usergroup!='' && $usergroup==7)
            $uassigned = $_POST['uassigned'];
        else 
            $uassigned = '0';
        $status = $_POST['ustat'];  

        if($usergroup==1)
        {
            $user_role=0;
        }

        if(!empty($username)&&
            !empty($firstname)&&
            !empty($lastname)&&
            !empty($emp_id)&&
            !empty($usergroup)&&
            !empty($status))
        {
            if(validate_alphanumeric_underscore($username))
            {
                if(checkusernameifExists($link,$user_id,$username))
                {
                    $query = $link->query(
                        "UPDATE 
                            `users` 
                        SET 
                            `emp_id`='$emp_id',
                            `username`='$username',
                            `firstname`='$firstname',
                            `lastname`='$lastname',
                            `usertype`='$usergroup',
                            `user_status`='$status',
                            `store_assigned`='$uassigned',
                            `user_role`='$user_role'
                        WHERE
                            `user_id`='$user_id'
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
                    $response['msg'] = $nusername.' already exist.';
                }
            }
            else 
            {
                $response['msg'] = 'Username only accepts alphanumeric and underscore.';
            }
        }
        else 
        {
            $response['msg'] = 'Please fill all fields.';
        }

 /////////////////////////////////       



        while ($row = $query_getxtfile->fetch_object()) {
          $arr_f = [];
          $file = $verificationfolder.'\\'.$row->vs_tf;
          if(checkFolder($verificationfolder))
          {
            $allowedExts = array("txt");
            $temp = explode(".", $file);
            $extension = end($temp);
            if(in_array($extension, $allowedExts))
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
                  $d = explode(",",$arr_f[$i]);
                  if($pc > 0)
                  {                   
                    $ins = $link->query(
                      "UPDATE 
                        `store_verification` 
                      SET 
                        `vs_tf_used`='*',
                        `vs_tf_balance`='$d[1]',
                        `vs_tf_purchasecredit`='$pc',
                        `vs_tf_addon_amt`='$am',
                        `vs_tf_eod`='$last_insert'                         
                      WHERE 
                        `vs_barcode`='$row->vs_barcode' 
                      ORDER BY
                        `vs_id`
                      DESC
                      LIMIT 1                     
                    ");
                    if(!$ins)
                    {
                      $haserror = 1;
                      break;
                    }
                  }
                  else 
                  {
                    $ins = $link->query(
                      "UPDATE 
                        `store_verification` 
                      SET 
                        `vs_tf_eod` = '$last_insert' 
                      WHERE 
                        `vs_barcode`='$row->vs_barcode'                         
                    ");
                    if(!$ins)
                    {
                      $haserror = 1;
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
                        `store_eod_textfile_transactions`
                      (
                          `seodtt_eod_id`, 
                          `seodtt_barcode`, 
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
                      )
                      VALUES 
                      (
                          '$last_insert',
                          '$row->vs_barcode',
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
                      $haserror=1;
                      break;
                    }
                  }
                }
              }
              if(!copy($file,$archivefolder.'/'.$row->vs_tf))
              {
                $haserror = 1;
                break;
              }
              else 
              {
                 if (!unlink($verificationfolder . '/' .$row->vs_tf)){

                 }
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



















backup_tables('localhost','username','password','blog');

/* backup the db OR just a table */
function backup_tables($host,$user,$pass,$name,$tables = '*')
{
  
  $link = mysql_connect($host,$user,$pass);
  mysql_select_db($name,$link);
  
  //get all of the tables
  if($tables == '*')
  {
    $tables = array();
    $result = mysql_query('SHOW TABLES');
    while($row = mysql_fetch_row($result))
    {
      $tables[] = $row[0];
    }
  }
  else
  {
    $tables = is_array($tables) ? $tables : explode(',',$tables);
  }
  
  //cycle through
  foreach($tables as $table)
  {
    $result = mysql_query('SELECT * FROM '.$table);
    $num_fields = mysql_num_fields($result);
    
    $return.= 'DROP TABLE '.$table.';';
    $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
    $return.= "\n\n".$row2[1].";\n\n";
    
    for ($i = 0; $i < $num_fields; $i++) 
    {
      while($row = mysql_fetch_row($result))
      {
        $return.= 'INSERT INTO '.$table.' VALUES(';
        for($j=0; $j < $num_fields; $j++) 
        {
          $row[$j] = addslashes($row[$j]);
          $row[$j] = ereg_replace("\n","\\n",$row[$j]);
          if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
          if ($j < ($num_fields-1)) { $return.= ','; }
        }
        $return.= ");\n";
      }
    }
    $return.="\n\n\n";
  }
  
  //save file
  $handle = fopen('db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql','w+');
  fwrite($handle,$return);
  fclose($handle);
}


http://www.azanweb.com/en/using-php-to-backup-mysql-databases/


   <div style="background: transparent url(loading.gif) no-repeat">

    //storeverification
    $revalidatedGC = 0;
    $failed = 0;
    $response['st'] = 0;
    $gc =  $link->real_escape_string(trim($_POST['gcbarcode']));
    $cusid = $link->real_escape_string(trim($_POST['cus-id']));   
    $storeid = $link->real_escape_string(trim($_POST['storeid']));
    $storename = getStoreName($link,$storeid);
    $txtfl_exist= 0;
    $msg = '';
    if(empty($cusid))
    {
      $response['msg'] = 'Please select customer.';
      goto stop_verify;
    }

    if(empty($gc))
    {
      $response['msg'] = 'Please scan gc first.';
      goto stop_verify;
    }

    $cusids = addZeroToString($cusid);

    //check if gc already sold
    $sold_info = checkIfGCAlreadySold($link,$gc);
    if(is_null($sold_info))
    {
      $response['msg'] = 'GC Barcode # '.$gc.' not found.';
      goto stop_verify;
    }

    //check if gc is on promo list 
    $promo_details = checkIfPromoGC($link,$gc);

    if(!is_null($promo_details))
    {
      if($todays_date > $promo_details->expire)
      {
        $response['msg'] = 'Promo already expired, Promo Date - '._dateFormat($promo_details->expire).'.';
        goto stop_verify;
      }
    }

    $reval = '';
    //check  if gc already validated and used
    $verifiedGCDetails = checkIFGCAlreadyVerified($link,$gc);
    if(!is_null($verifiedGCDetails))
    {
      if($verifiedGCDetails->vs_date == $todays_date)
      {
        
        $filename = 'textfiles/validation/'.$gc.'.txt';
        $failed =1;
        // if(checkIfTextfileExist($filename))
        // {
        //    $textfiletrans = readTextfile($filename);
        //  $response['msg'] = 'GC Barcode # '.$gc.' already verified.<br /> Date: '._dateFormat($verifiedGCDetails->vs_date).'<br />
        //  Time: '._timeFormat($verifiedGCDetails->vs_time).'<br />
        //  Store: '.$verifiedGCDetails->store_name.'<br />'.$textfiletrans;
        //  goto stop_verify;           
        // }
        // else 
        // {
        //  $response['msg'] = 'GC Barcode # '.$gc.' already verified.<br /> Date: '._dateFormat($verifiedGCDetails->vs_date).'<br />
        //  Time: '._timeFormat($verifiedGCDetails->vs_time).'<br />
        //  Store: '.$verifiedGCDetails->store_name.'<br />
        //  Textfile not found.';
        //  goto stop_verify;
        // }

      }

      if($verifiedGCDetails->vs_tf_used =='*')
      {
         $filename = 'textfiles/gctextfile_archives/'.$gc.'.txt';
         $failed = 1;

         // if(checkIfTextfileExist($filename))
         // {
         //   $textfiletrans = readTextfile($filename);
          // $response['msg'] = 'GC Barcode # '.$gc.' already verified.<br /> Date: '._dateFormat($verifiedGCDetails->vs_date).'<br />
          // Time: '._timeFormat($verifiedGCDetails->vs_time).'<br />
          // Store: '.$verifiedGCDetails->store_name.'<br />'.$textfiletrans;
          // goto stop_verify;
         // }
         // else 
         // {
          // $response['msg'] = 'GC Barcode # '.$gc.' already verified.<br /> Date: '._dateFormat($verifiedGCDetails->vs_date).'<br />
          // Time: '._timeFormat($verifiedGCDetails->vs_time).'<br />
          // Store: '.$verifiedGCDetails->store_name.'<br />
          //  Textfile not found.';
          // goto stop_verify;          
         // }     
      }

      if($todays_date > $verifiedGCDetails->vs_date)
      {
        $revalidated = checkforRevalidated($link,$gc);
        if(is_null($revalidated))
        {
          $filename = 'textfiles/gctextfile_archives/'.$gc.'.txt';
          $failed = 1;          
        }
        else 
        {
          if($revalidated->reval_revalidated=='0')
          {
            if($revalidated->trans_store == $storeid)
            {
              //get customer code of recent verification 
              $recent_cc = getCustomerCodeLastVerification($link,$gc);
              if($cusid==$recent_cc)
              {
                if(_dateFormatoSql($revalidated->trans_datetime)==$todays_date)
                {
                  $revalidatedGC = 1;
                  $msg = 'Store Revalidated: '.$revalidated->store_name.'<br>
                  Date Revalidated: '._dateFormat($revalidated->trans_datetime).'<br />';
                }
                else 
                {
                  $reval = 'Store Revalidated: '.$revalidated->store_name.'<br>
                  Date Revalidated: '._dateFormat($revalidated->trans_datetime).'<br />';
                  $filename = 'textfiles/gctextfile_archives/'.$gc.'.txt';
                  $failed = 1;
                }
              }
              else 
              {
                  $reval = 'Invalid Customer Information</br>
                  Store Revalidated: '.$revalidated->store_name.'<br>
                  Date Revalidated: '._dateFormat($revalidated->trans_datetime).'<br />';
                  $filename = 'textfiles/gctextfile_archives/'.$gc.'.txt';
                  $failed = 1;                
              }
            }
            else 
            {
              $reval = 'GC Revalidated at '.$revalidated->store_name.'<br>
              Date Revalidated: '._dateFormat($revalidated->trans_datetime).'<br />';
              $filename = 'textfiles/gctextfile_archives/'.$gc.'.txt';
              $failed = 1;
            }
          }
          else 
          {
            // get store validation date
            $response['msg'] = $revalidated->reval_revalidated;
            goto stop_verify;           
          } 
        }
      }
    }

    if($failed)
    {
      if(checkIfTextfileExist($filename))
      {
        $textfiletrans = readTextfile($filename);
        $response['msg'] = $reval.'GC Barcode # '.$gc.' already verified.<br /> Date: '._dateFormat($verifiedGCDetails->vs_date).'<br />
        Time: '._timeFormat($verifiedGCDetails->vs_time).'<br />
        Store: '.$verifiedGCDetails->store_name.'<br />'.$textfiletrans;
        goto stop_verify;           
      }
      else 
      {
        $response['msg'] = $reval.'GC Barcode # '.$gc.' already verified.<br /> Date: '._dateFormat($verifiedGCDetails->vs_date).'<br />
        Time: '._timeFormat($verifiedGCDetails->vs_time).'<br />
        Store: '.$verifiedGCDetails->store_name.'<br />
        Textfile not found.';
        goto stop_verify;
      }
    }

    $link->autocommit(FALSE);
    $denom = getDenominationByBarcode($link,$gc);
    $barcodetf = $gc.'.txt';

    $query_ins = $link->query(
      "INSERT INTO 
        `store_verification`
      (
        `vs_barcode`, 
        `vs_cn`, 
        `vs_by`, 
        `vs_date`, 
        `vs_time`, 
        `vs_tf`, 
        `vs_store`,
        `vs_tf_balance`
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
        '$denom'
      )
    ");

    if(!$query_ins)
    {
      $response['msg'] = $link->error;
      goto stop_verify;
    }
    else 
    {
      $lastid = $link->insert_id;     
    }

    $customerdetails =  getCustomerDetailsByID($link,$cusid);
    if(is_null($customerdetails))
    {
      $response['msg'] = 'Customer Dont Exist.';
      goto stop_verify;
    }
    $mid_initial = is_null($customerdetails->cus_mname)? '': strtoupper(substr($customerdetails->cus_mname,0,1)).'.';


    $sd='';
    $dir = "textfiles/validation/";
    $f = $dir.$gc.'.txt';
    $fh = fopen($f, 'w') or die("cant open file");
    $sd.="000,".$cusid.",0,".strtoupper($customerdetails->cus_fname.' '.$mid_initial.' '.$customerdetails->cus_lname)." ".
    "\r\n".
    "001,".$denom.'.00'.
    "\r\n".
    "002,0".
    "\r\n".
    "003,0".
    "\r\n".
    "004,".$denom.'.00'.
    "\r\n".
    "005,0".
    "\r\n".
    "006,0".
    "\r\n".
    "007,0";
    fwrite($fh, $sd);         
    fclose($fh);

    if($revalidatedGC)
    {
      $query_updateValidate = $link->query(
        "UPDATE 
          `transaction_revalidation` 
        SET 
          `reval_revalidated`='$lastid' 
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
    $mid_initial = is_null($customerdetails->cus_mname)? '': strtoupper(substr($customerdetails->cus_mname,0,1)).'.';
    $link->commit();

    $response['st'] = 1;
    $response['stat'] = 0;
    $response['barcode'] = $gc;
    $response['customer'] = strtoupper($customerdetails->cus_fname.' '.$mid_initial.' '.$customerdetails->cus_lname);
    $response['date'] = $todays_date;
    $response['time'] = $todays_time;
    $response['storename'] = $storename;

    $response['flashmsg'] = $flashmsg;
    $response['msg'] = $msg.'<div class="verifygcbar">GC Barcode: <span class="verifyx">'.$gc.'</span></div>
      <div class="verifygcdenom">Denomination: <span class="verifyx">'.number_format($denom,2).'</span></div>';
      

    stop_verify:
    echo json_encode($response);