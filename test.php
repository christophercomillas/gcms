<?php

	error_reporting(E_ALL);
	ini_set("display_errors", 1);

	session_start();
	require 'config.php';
	include 'function-cashier.php';
    include 'function.php';	
    
    $table = 'store_local_server';
    $select = 'stlocser_ip,stlocser_username,stlocser_password,stlocser_db';
    $where = "stlocser_storeid='5'";
    $join = '';
    $limit = '';
    $lserver = getSelectedData($link,$table,$select,$where,$join,$limit);

    //var_dump($lserver);

    localserver_connect($lserver->stlocser_ip,$lserver->stlocser_username,$lserver->stlocser_password,$lserver->stlocser_db);

	// $filepath = "/GiftCheck/1010000001.txt";

	// if(file_exists($filepath))
	// {
	// 	echo 'yeah';
	// }

 	//$thepath = "\\\\172.16.161.205\\CFS_Txt\\GiftCheck";
 	//$thepath = "\\\\172.16.161.35\\fad_stores\\FAD_STORE_DATABASE\\HO_DATA\\GCRECEIVING\\NEW\\";

	// $thepath = "\\\\172.16.161.35\\fad_stores\\FAD_STORE_DATABASE\\FAD_DIRECTORY\\";

	// //$dh = opendir($thepath);

	// if ($handle = opendir($thepath)) 
	// {
	//     while (false !== ($entry = readdir($handle))) 
	//     {
	// 		echo "$entry<br />";
	//     } 
	//     closedir($handle);
	// }

	// $path = "\\\\172.16.161.35\\fad_stores";

	// $user = "server161-35//public";
	// $pass = "public";
	// $drive_letter = "Q";

	// system("net use ".$drive_letter.": \"".$path."\" ".$pass." /user:".$user." /persistent:no>nul 2>&1");

	// echo "net use ".$drive_letter.": \"".$path."\" ".$pass." /user:".$user." /persistent:no>nul 2>&1";
	// $location = $drive_letter."://fad_stores";

	// if ($handle = opendir($location)) 
	// {
	//     while (false !== ($entry = readdir($handle))) 
	//     {
	// 		echo "$entry";
	//     } 
	//     closedir($handle);
	// }

	// $location = "\\\\172.16.161.35\\fad_stores";
	// $user = "server161-35\\public";
	// $pass = "public";
	// $letter = "Y";

	// // Map the drive
	// if(system("net use ".$letter.": \"".$location."\" ".$pass." /user:".$user." /persistent:no>nul 2>&1"))
	// {
	// 	echo 'yeah';
	// }
	// else 
	// {
	// 	echo 'nah';
	// }


	// Open the directory
	//$dir = opendir("//172.16.161.205/CFS_Textfiles/GiftCheck");

// $file_path = "\\\\172.16.161.205\\CFS_Textfiles\\GiftCheck";
// $filesize = ($file_path); 

// echo "FILE SIZE IS $filesize";





	//echo '<br />';

	//$open = opendir('\\\\172.16.161.205\\CFS_Textfiles\\GiftCheck');


	//$fad = '\\\\172.16.161.205\\CFS_Textfiles\\GiftCheck';

	// //$fad = '\\\\172.16.161.35\\fad_stores';
	// $fad = '\\\\172.16.161.17\\fad';
	// //$fad = '\\\\172.16.16.73\\share';

	// echo $fad;

	// if(file_exists($fad))
	// {
	// 	$dh  = opendir($fad);
	// 	while (false !== ($filename = readdir($dh))) {
	// 	    $files[] = $filename;	
	// 	}

	// 	echo '<pre>';
	// 	print_r($files);
	// 	echo '</pre>';
	// }
	// else 
	// {
	// 	echo 'nah';
	// }


	// function ntowords($num)
	// { 
	// 	$decones = array( 
	// 	            '01' => "One", 
	// 	            '02' => "Two", 
	// 	            '03' => "Three", 
	// 	            '04' => "Four", 
	// 	            '05' => "Five", 
	// 	            '06' => "Six", 
	// 	            '07' => "Seven", 
	// 	            '08' => "Eight", 
	// 	            '09' => "Nine", 
	// 	            '10' => "Ten", 
	// 	            '11' => "Eleven", 
	// 	            '12' => "Twelve", 
	// 	            '13' => "Thirteen", 
	// 	            '14' => "Fourteen", 
	// 	            '15' => "Fifteen", 
	// 	            '16' => "Sixteen", 
	// 	            '17' => "Seventeen", 
	// 	            '18' => "Eighteen", 
	// 	            '19' => "Nineteen" 
	// 	            );
	// 	$ones = array( 
	// 	            '0' => " ",
	// 	            '1' => "One",     
	// 	            '2' => "Two", 
	// 	            '3' => "Three", 
	// 	            '4' => "Four", 
	// 	            '5' => "Five", 
	// 	            '6' => "Six", 
	// 	            '7' => "Seven", 
	// 	            '8' => "Eight", 
	// 	            '9' => "Nine", 
	// 	            '10' => "Ten", 
	// 	            '11' => "Eleven", 
	// 	            '12' => "Twelve", 
	// 	            '13' => "Thirteen", 
	// 	            '14' => "Fourteen", 
	// 	            '15' => "Fifteen", 
	// 	            '16' => "Sixteen", 
	// 	            '17' => "Seventeen", 
	// 	            '18' => "Eighteen", 
	// 	            '19' => "Nineteen" 
	// 	            ); 
	// 	$tens = array( 
	// 	            '0' => "",
	// 	            '2' => "Twenty", 
	// 	            '3' => "Thirty", 
	// 	            '4' => "Forty", 
	// 	            '5' => "Fifty", 
	// 	            '6' => "Sixty", 
	// 	            '7' => "Seventy", 
	// 	            '8' => "Eighty", 
	// 	            '9' => "Ninety" 
	// 	            ); 
	// 	$hundreds = array( 
	// 	            "Hundred", 
	// 	            "Thousand", 
	// 	            "Million", 
	// 	            "Billion", 
	// 	            "Trillion", 
	// 	            "Quadrillion" 
	// 	            ); //limit t quadrillion 
	// 	$num = number_format($num,2,".",","); 
	// 	$num_arr = explode(".",$num); 
	// 	$wholenum = $num_arr[0]; 
	// 	$decnum = $num_arr[1]; 
	// 	$whole_arr = array_reverse(explode(",",$wholenum)); 
	// 	krsort($whole_arr); 
	// 	$rettxt = ""; 
	// 	foreach($whole_arr as $key => $i){ 
	// 	    if($i < 20){ 
	// 	        $rettxt .= $ones[$i]; 
	// 	    }
	// 	    elseif($i < 100){ 
	// 	        $rettxt .= $tens[substr($i,0,1)]; 
	// 	        $rettxt .= " ".$ones[substr($i,1,1)]; 
	// 	    }
	// 	    else{ 
	// 	        $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
	// 	        $rettxt .= " ".$tens[substr($i,1,1)]; 
	// 	        $rettxt .= " ".$ones[substr($i,2,1)]; 
	// 	    } 
	// 	    if($key > 0){ 
	// 	        $rettxt .= " ".$hundreds[$key]." "; 
	// 	    } 

	// 	} 
	// 	$rettxt = $rettxt." pesos";

	// 	if($decnum > 0){ 
	// 	    $rettxt .= " and "; 
	// 	    if($decnum < 20){ 
	// 	        $rettxt .= $decones[$decnum]; 
	// 	    }
	// 	    elseif($decnum < 100){ 
	// 	        $rettxt .= $tens[substr($decnum,0,1)]; 
	// 	        $rettxt .= " ".$ones[substr($decnum,1,1)]; 
	// 	    }
	// 	    $rettxt = $rettxt." centavos"; 
	// 	} 
	// 	return $rettxt.' only';
	// } 

	// //echo translateToWords(1045.10);

	// function translateToWords($number) 
	// {
	// /*****
	//      * A recursive function to turn digits into words
	//      * Numbers must be integers from -999,999,999,999 to 999,999,999,999 inclussive.    
	//      *
	//      *  (C) 2010 Peter Ajtai
	//      *    This program is free software: you can redistribute it and/or modify
	//      *    it under the terms of the GNU General Public License as published by
	//      *    the Free Software Foundation, either version 3 of the License, or
	//      *    (at your option) any later version.
	//      *
	//      *    This program is distributed in the hope that it will be useful,
	//      *    but WITHOUT ANY WARRANTY; without even the implied warranty of
	//      *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	//      *    GNU General Public License for more details.
	//      *
	//      *    See the GNU General Public License: <http://www.gnu.org/licenses/>.
	//      *
	//      */
	//     // zero is a special case, it cause problems even with typecasting if we don't deal with it here
	//     $max_size = pow(10,18);
	//     if (!$number) return "zero";
	//     if (is_int($number) && $number < abs($max_size)) 
	//     {            
	//         switch ($number) 
	//         {
	//             // set up some rules for converting digits to words
	//             case $number < 0:
	//                 $prefix = "negative";
	//                 $suffix = translateToWords(-1*$number);
	//                 $string = $prefix . " " . $suffix;
	//                 break;
	//             case 1:
	//                 $string = "one";
	//                 break;
	//             case 2:
	//                 $string = "two";
	//                 break;
	//             case 3:
	//                 $string = "three";
	//                 break;
	//             case 4: 
	//                 $string = "four";
	//                 break;
	//             case 5:
	//                 $string = "five";
	//                 break;
	//             case 6:
	//                 $string = "six";
	//                 break;
	//             case 7:
	//                 $string = "seven";
	//                 break;
	//             case 8:
	//                 $string = "eight";
	//                 break;
	//             case 9:
	//                 $string = "nine";
	//                 break;                
	//             case 10:
	//                 $string = "ten";
	//                 break;            
	//             case 11:
	//                 $string = "eleven";
	//                 break;            
	//             case 12:
	//                 $string = "twelve";
	//                 break;            
	//             case 13:
	//                 $string = "thirteen";
	//                 break;            
	//             // fourteen handled later
	//             case 15:
	//                 $string = "fifteen";
	//                 break;            
	//             case $number < 20:
	//                 $string = translateToWords($number%10);
	//                 // eighteen only has one "t"
	//                 if ($number == 18)
	//                 {
	//                 $suffix = "een";
	//                 } else 
	//                 {
	//                 $suffix = "teen";
	//                 }
	//                 $string .= $suffix;
	//                 break;            
	//             case 20:
	//                 $string = "twenty";
	//                 break;            
	//             case 30:
	//                 $string = "thirty";
	//                 break;            
	//             case 40:
	//                 $string = "forty";
	//                 break;            
	//             case 50:
	//                 $string = "fifty";
	//                 break;            
	//             case 60:
	//                 $string = "sixty";
	//                 break;            
	//             case 70:
	//                 $string = "seventy";
	//                 break;            
	//             case 80:
	//                 $string = "eighty";
	//                 break;            
	//             case 90:
	//                 $string = "ninety";
	//                 break;                
	//             case $number < 100:
	//                 $prefix = translateToWords($number-$number%10);
	//                 $suffix = translateToWords($number%10);
	//                 $string = $prefix . "-" . $suffix;
	//                 break;
	//             // handles all number 100 to 999
	//             case $number < pow(10,3):                    
	//                 // floor return a float not an integer
	//                 $prefix = translateToWords(intval(floor($number/pow(10,2)))) . " hundred";
	//                 if ($number%pow(10,2)) $suffix = " and " . translateToWords($number%pow(10,2));
	//                 $string = $prefix . $suffix;
	//                 break;
	//             case $number < pow(10,6):
	//                 // floor return a float not an integer
	//                 $prefix = translateToWords(intval(floor($number/pow(10,3)))) . " thousand";
	//                 if ($number%pow(10,3)) $suffix = translateToWords($number%pow(10,3));
	//                 $string = $prefix . " " . $suffix;
	//                 break;
	//             case $number < pow(10,9):
	//                 // floor return a float not an integer
	//                 $prefix = translateToWords(intval(floor($number/pow(10,6)))) . " million";
	//                 if ($number%pow(10,6)) $suffix = translateToWords($number%pow(10,6));
	//                 $string = $prefix . " " . $suffix;
	//                 break;                    
	//             case $number < pow(10,12):
	//                 // floor return a float not an integer
	//                 $prefix = translateToWords(intval(floor($number/pow(10,9)))) . " billion";
	//                 if ($number%pow(10,9)) $suffix = translateToWords($number%pow(10,9));
	//                 $string = $prefix . " " . $suffix;    
	//                 break;
	//             case $number < pow(10,15):
	//                 // floor return a float not an integer
	//                 $prefix = translateToWords(intval(floor($number/pow(10,12)))) . " trillion";
	//                 if ($number%pow(10,12)) $suffix = translateToWords($number%pow(10,12));
	//                 $string = $prefix . " " . $suffix;    
	//                 break;        
	//             // Be careful not to pass default formatted numbers in the quadrillions+ into this function
	//             // Default formatting is float and causes errors
	//             case $number < pow(10,18):
	//                 // floor return a float not an integer
	//                 $prefix = translateToWords(intval(floor($number/pow(10,15)))) . " quadrillion";
	//                 if ($number%pow(10,15)) $suffix = translateToWords($number%pow(10,15));
	//                 $string = $prefix . " " . $suffix;    
	//                 break;                    
	//         }
	//     } else
	//     {
	//         echo "ERROR with - $number<br/> Number must be an integer between -" . number_format($max_size, 0, ".", ",") . " and " . number_format($max_size, 0, ".", ",") . " exclussive.";
	//     }
	//     return $string;    
	// }

	// function convert_number_to_words($number) {
	   
	//     $hyphen      = '-';
	//     $conjunction = ' ';
	//     $separator   = ' ';
	//     $negative    = 'negative ';
	//     $decimal     = ' and ';
	//     $dictionary  = array(
	//         0                   => 'Zero',
	//         1                   => 'One',
	//         2                   => 'Two',
	//         3                   => 'Three',
	//         4                   => 'Four',
	//         5                   => 'Five',
	//         6                   => 'Six',
	//         7                   => 'Seven',
	//         8                   => 'Eight',
	//         9                   => 'Nine',
	//         10                  => 'Ten',
	//         11                  => 'Eleven',
	//         12                  => 'Twelve',
	//         13                  => 'Thirteen',
	//         14                  => 'Fourteen',
	//         15                  => 'Fifteen',
	//         16                  => 'Sixteen',
	//         17                  => 'Seventeen',
	//         18                  => 'Eighteen',
	//         19                  => 'Nineteen',
	//         20                  => 'Twenty',
	//         30                  => 'Thirty',
	//         40                  => 'Forty',
	//         50                  => 'Fifty',
	//         60                  => 'Sixty',
	//         70                  => 'Seventy',
	//         80                  => 'Eighty',
	//         90                  => 'Ninety',
	//         100                 => 'Hundred',
	//         1000                => 'Thousand',
	//         1000000             => 'Million',
	//         1000000000          => 'Billion',
	//         1000000000000       => 'Trillion',
	//         1000000000000000    => 'Quadrillion',
	//         1000000000000000000 => 'Quintillion'
	//     );
	   
	//     if (!is_numeric($number)) {
	//         return false;
	//     }
	   
	//     if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
	//         // overflow
	//         trigger_error(
	//             'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
	//             E_USER_WARNING
	//         );
	//         return false;
	//     }

	//     if ($number < 0) {
	//         return $negative . convert_number_to_words(abs($number));
	//     }
	   
	//     $string = $fraction = null;
	   
	//     if (strpos($number, '.') !== false) {
	//         list($number, $fraction) = explode('.', $number);
	//     }
	   
	//     switch (true) {
	//         case $number < 21:
	//             $string = $dictionary[$number];
	//             break;
	//         case $number < 100:
	//             $tens   = ((int) ($number / 10)) * 10;
	//             $units  = $number % 10;
	//             $string = $dictionary[$tens];
	//             if ($units) {
	//                 $string .= $hyphen . $dictionary[$units];
	//             }
	//             break;
	//         case $number < 1000:
	//             $hundreds  = $number / 100;
	//             $remainder = $number % 100;
	//             $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
	//             if ($remainder) {
	//                 $string .= $conjunction . convert_number_to_words($remainder);
	//             }
	//             break;
	//         default:
	//             $baseUnit = pow(1000, floor(log($number, 1000)));
	//             $numBaseUnits = (int) ($number / $baseUnit);
	//             $remainder = $number % $baseUnit;
	//             $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
	//             if ($remainder) {
	//                 $string .= $remainder < 100 ? $conjunction : $separator;
	//                 $string .= convert_number_to_words($remainder);
	//             }
	//             break;
	//     }
	   
	//     if (null !== $fraction && is_numeric($fraction)) {
	//         $string .= $decimal;
	//         $words = array();
	//         foreach (str_split((string) $fraction) as $number) {
	//             $words[] = $dictionary[$number];
	//         }
	//         $string .= implode(' ', $words);
	//     }
	   
	//     return $string;
	// }

	// $amount = $_GET['amount'];

	// $amountexp = explode(".",$amount);
	// $amount1 = $amount[1];
	// $amt = array('Peso ','Pesos ','Centavo ','Centavos ');
	// if($amount != "0.00")
	// {
	//     if($amountexp[1] == 00  && $amountexp[0] > 0)
	//     {
	//     	$str = $amount[0] > 1 ? $amt[0] : $amt[1] ;
 // 	       	echo convert_number_to_words($amountexp[0]).' '.$str.' Only';
	//     }
	//     else 
	//     {	    	
	//     	$str = intval($amount[1]) > 1 ? $amt[2] : $amt[3] ;
	//     	echo convert_number_to_words($amountexp[0])." Pesos And ".convert_number_to_words(intval($amountexp[1]))." ".$str." Only";   
	//     }
	// }
	// else 
	// {
	//     echo "";
	// }


	// $nums = array('23232.3233232','3233223.333232,3232332.');
	// $var = '0';

	// // var_dump(isset($var));
	// // var_dump(empty($var));
	// // var_dump(is_null($var));
	// $string = "ELEMENTARY";

	// $array = str_split($string);

	// echo implode(array_filter($array, function($item){
	// 	return $item === 'E';
	// }));


	// $from = "1010000000001";
	// $to = "1010000010000";

	// $flag = false;
	// $link->autocommit(FALSE);
	// for ($i=$from; $i <= $to; $i++) 
	// { 
	// 	$query_select = $link->query(
	// 		"SELECT 
	// 			barcode_no
	// 		FROM 
	// 			gc 
	// 		WHERE 
	// 			barcode_no='$i'
	// 	");

	// 	if(!$query_select)
	// 	{
	// 		$flag = true;
	// 		break;
	// 	}

	// 	$query_ins = $link->query(
	// 		"INSERT INTO 
	// 			temp_validation
	// 		(
	// 			tval_barcode, 
	// 			tval_recnum, 
	// 			tval_denom
	// 		) 
	// 		VALUES 
	// 		(
	// 			'".$i."',
	// 			'1',
	// 			'1'
	// 		)
	// 	")

	// }

	// if($flag)
	// {
	// 	echo $link->error;
	// }
	// else 
	// {
	// 	$link->commit();
	// }


	//$g = "test12";

	// if(!insertBudgetLedger($link,1,'STORESALES','bdebit_amt','1000.00'))
	// {
	// 	echo $link->error;
	// }

	// $fad = '\\\\172.16.161.35\\fad_stores';
	// //$fad = '\\\\172.16.161.17\\fad';
	// //$fad = '\\\\172.16.16.73\\share';

	// if(file_exists($fad))
	// {
	// 	$dh  = opendir($fad);
	// 	while (false !== ($filename = readdir($dh))) {
	// 	    $files[] = $filename;	
	// 	}

	// 	echo '<pre>';
	// 	print_r($files);
	// 	echo '</pre>';
	// }
	// else 
	// {
	// 	echo 'nah';
	// }


// $location = "\\\\172.16.161.35";
// $user = "server161-35\public";
// $pass = "public";
// $letter = "V:";

// // Map the drive
// system("net use ".$letter.": \"".$location."\" ".$pass." /user:".$user." /persistent:no>nul 2>&1");

// // Open the directory
// opendir($letter.$location.'\\fad_stores');
	// echo base64_encode("136080");


	// echo base64_decode("MA==");
?>


<!-- 	<div class="sample">sa</div>
<button onclick="save()"></button>
<script src="assets/js/jquery-1.10.2.js"></script>
<script type="text/javascript">
	var str = "1=2";
	var res = str.split("=");
	console.log(res[0]);


	function save()
	{
		console.log('x');
	}
	var lastChar = 1;
	var x = $('.sample').text();
	console.log(x);
	$.ajax({
		url:'ajax.php?action=updatePromoScannedAfterRemoved',
		data:{lastChar:lastChar},
		type:'POST',    
		async: false,                      
		success:function(dataupdate)
		{
			var dataupdate = JSON.parse(dataupdate);
			console.log(dataupdate['remain']);
			console.log(dataupdate['remain'].length);
			d = dataupdate['remain'];
			for (var val in d) 
			{
			    console.log(d[val]);
			}
			//$('.scangcx'+lastChar).text(dataupdate['remain']);
		}
	});	
</script> -->