<?php
session_start();

if(isset($_GET['dtype']))
{
    $dtype = $_GET['dtype'];
}
else
{
    die("data type error!");
}

// if(isset($_GET['start']))
// {
//     $start = $_GET['start'];
// }
// else 
// {
//     die("start date error.");
// }

// if(isset($_GET['end']))
// {
//     $end = $_GET['end'];
// }
// else 
// {
//     die("end date error.");
// }

if(trim($dtype)=='')
{
    die("error");
}

$stcus = "";

if(isset($_GET['stselect']))
{
    $stcus = $_GET['stselect'];
}

$name = 'verifiedgc';

if(isset($_GET['month']))
{
    $month = $_GET['month'];
}
else 
{
    die('select month');
}

if(isset($_GET['year']))
{
    $year = $_GET['year'];
}
else 
{
    die('select year');
}

// $startn = str_replace("/", "-", $start);
// $endn = str_replace("/", "-", $end);
// $name = "";
// if($startn===$endn)
// {
//     $name = $startn;
// }
// else 
// {
//     $name = $startn."-".$endn;

// }
// $timezone = "Asia/Manila";
// if(function_exists('date_default_timezone_set')){ date_default_timezone_set($timezone);}

// $todays_date = date("Y-m-d");
// $todays_time = date("G:i:s");

// $host = 'localhost';
// $user = 'root';
// $pass = 'root321';
// $db   = 'gc';

// $link = new mysqli($host, $user, $pass, $db);
// mysqli_set_charset($link, 'utf8');

// /* check connection */
// if ($link->connect_error) {
// 	printf("Connect failed: %s\n", $link->connect_error);
// 	exit();
// }
require_once  dirname(__FILE__) .'/../config.php';

$monthName = date('F', mktime(0, 0, 0, $month, 10)); // March

$name = 'cfsreport'.$name.$monthName.$year;
//$query = $link->query("SELECT * FROM `ledger_budget`");

/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2014 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.8.0, 2014-03-02
 */

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once  dirname(__FILE__) . '/../Classes/PHPExcel.php';
//require_once  dirname(__FILE__) .'/../function.php';

function _dateFormat($todays_date){
    $todays_date = date_create($todays_date);
    return date_format($todays_date, 'F d, Y');     
}

function _getGCTextfileTR($link,$barcode)
{
    $rows = [];
    $query = $link->query(
        "SELECT 
            seodtt_bu,
            seodtt_terminalno,
            seodtt_credpuramt

        FROM 
            store_eod_textfile_transactions 
        WHERE 
            seodtt_barcode='$barcode'
    ");

    while ($row = $query->fetch_object()) 
    {
        $rows[] = $row;
    }

    return $rows;
}

function getStoreNamebyID($link,$buid)
{
    $query = $link->query(
        "SELECT 
            store_name
        FROM 
            stores 
        WHERE 
            store_id='".$buid."'
    ");

    if($query)
    {
        $row = $query->fetch_object();
        return $row->store_name;
    }
}

// Create new PHPExcel object
$object = new PHPExcel();

$header = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '000000'),
        'size'  => 12,
        'name'  => 'Verdana'
    )
);
$subheader = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '000000'),
        'size'  => 11,
        'name'  => 'Verdana'
    )
);
$normalbold = array(
    'font'  => array(
        'bold'  => true
    )
);

$styleArray = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);

$signature = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '000000'),
        'size'  => 7,
        'name'  => 'Verdana'
    )
);

// Set document properties
$object->getProperties()->setCreator($_SESSION['gc_fullname'])
							 ->setLastModifiedBy($_SESSION['gc_fullname'])
							 ->setTitle("GC Report")
							 ->setSubject("GC Report")
							 ->setDescription("GC Report")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("GC Report");


$object->getActiveSheet()->getColumnDimension('A')->setWidth(12);   //DATE VERIFIED / REVERIFIED
$object->getActiveSheet()->getColumnDimension('B')->setWidth(16);   //BARCODE   
$object->getActiveSheet()->getColumnDimension('C')->setWidth(18);   //DENOMINATION
$object->getActiveSheet()->getColumnDimension('D')->setWidth(18);   //AMOUNT REDEEM
$object->getActiveSheet()->getColumnDimension('E')->setWidth(38);   //CUSTOMER NAME
$object->getActiveSheet()->getColumnDimension('F')->setWidth(12);   //BALANCE
$object->getActiveSheet()->getColumnDimension('G')->setWidth(18);   //BUSINESS UNIT
$object->getActiveSheet()->getColumnDimension('H')->setWidth(16);   //TERMINAL #
$object->getActiveSheet()->getColumnDimension('I')->setWidth(16);   //VALIDATION
$object->getActiveSheet()->getColumnDimension('J')->setWidth(20);   //GC TYPE


$table_columns = array("DATE", "BARCODE", "DENOMINATION", "AMOUNT REDEEM", "CUSTOMER NAME","BALANCE","BUSINESS UNIT","TERMINAL #","VALIDATION","GC TYPE");

$column = 0;

foreach($table_columns as $field)
{
    $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
    $column++;
}
$object->getActiveSheet()->getStyle("A1:J1")->getFont()->setBold( true );
$object->getActiveSheet()->freezePane('A2');

$excel_row = 2;

// $query = $link->query(
//         "SELECT 
//             DATE(store_verification.vs_date) as datever,
//             store_verification.vs_barcode,
//             store_verification.vs_tf_denomination,
//             store_verification.vs_tf_purchasecredit,
//             customers.cus_fname,
//             customers.cus_lname,
//             customers.cus_mname,
//             customers.cus_namext,
//             store_verification.vs_tf_balance
//         FROM 
//             store_verification 
//         LEFT JOIN
//             customers
//         ON
//             customers.cus_id = store_verification.vs_cn
//         WHERE 
//             ((YEAR(vs_date) = '$year' 
//         AND 
//             MONTH(vs_date) = '$month')
//         OR
//             (YEAR(vs_reverifydate) = '$year' 
//         AND 
//             MONTH(vs_reverifydate) = '$month'))
//         AND
//             vs_store='$stcus'
//         ORDER BY vs_id ASC 
// ");

// SELECT 
//     store_verification.vs_date,
//     IFNULL(SUM(store_verification.vs_tf_denomination),00.0) as sumver,
//     IFNULL(SUM(store_verification.vs_tf_balance),00.0) as balance,
//     IFNULL(SUM(store_verification.vs_tf_purchasecredit),00.0) as redeem
// FROM 
//     store_verification 
// LEFT JOIN
//     customers
// ON
//     customers.cus_id = store_verification.vs_cn
// WHERE 
//     ((YEAR(vs_date) = '2017' 
// AND 
//     MONTH(vs_date) = '12')
// OR
//     (YEAR(vs_reverifydate) = '2017' 
// AND 
//     MONTH(vs_reverifydate) = '12'))
// AND
//     vs_store='1'
// GROUP BY vs_date

$query = $link->query(
        "SELECT 
            DATE(store_verification.vs_date) as datever,
            DATE(store_verification.vs_reverifydate) as daterev,
            store_verification.vs_barcode,
            store_verification.vs_tf_denomination,
            store_verification.vs_tf_purchasecredit,
            store_verification.vs_payto,
            customers.cus_fname,
            customers.cus_lname,
            customers.cus_mname,
            customers.cus_namext,
            store_verification.vs_tf_balance,
            store_verification.vs_gctype
        FROM 
            store_verification 
        LEFT JOIN
            customers
        ON
            customers.cus_id = store_verification.vs_cn
        WHERE 
            YEAR(vs_date) = '$year' 
        AND 
            MONTH(vs_date) = '$month'
        AND
            vs_store='$stcus'
        ORDER BY vs_id ASC 
");

$storename = getStoreNamebyID($link,$stcus);

$arr_ver = [];

while ($row = $query->fetch_object()) 
{
    $gctype = "";
    $purchasecred = 0;
    $balance = 0;
    if($row->vs_gctype=='1')
    {
        $gctype = 'REGULAR';
    }
    elseif($row->vs_gctype=='2')
    {
        $gctype = 'SPECIAL REGULAR';
    }
    elseif($row->vs_gctype=='3')
    {
        $gctype = 'SPECIAL EXTERNAL';
    }
    elseif($row->vs_gctype=='4')
    {
        $gctype = 'PROMO';
    }
    elseif($row->vs_gctype=='5')
    {
        $gctype = 'SUPPLIER GC';
    }
    elseif($row->vs_gctype=='6')
    {
        $gctype = 'BEAM AND GO';
    }

    $bus = "";
    $tnum = "";
    $puramt = "";
    if($row->daterev!='')
    {
        $purchasecred = 0;
        $balance = $row->vs_tf_denomination;

    }
    else 
    {

        $bdata = _getGCTextfileTR($link,$row->vs_barcode);
        if(count($bdata)> 0)
        {
            if(count($bdata)==1)
            {
                foreach ($bdata as $d) 
                {                    
                    $puramt.= $d->seodtt_credpuramt;
                    $bus.= $d->seodtt_bu;
                    $tnum.= $d->seodtt_terminalno;
                }
            }
            else 
            {
                $bdatalength = count($bdata);
                $bcnt = 0;
                foreach ($bdata as $d) 
                {
                    if($bcnt!==$bdatalength -1)
                    {
                        $puramt.= $d->seodtt_credpuramt.', ';
                        $bus.= $d->seodtt_bu.', ';
                        $tnum.= $d->seodtt_terminalno.', ';                    
                    }
                    else 
                    {
                        $puramt.= $d->seodtt_credpuramt;
                        $bus.= $d->seodtt_bu;
                        $tnum.= $d->seodtt_terminalno;                      
                    }
                    $bcnt++;
                }
            }
        }   
        $purchasecred = $row->vs_tf_purchasecredit;
        $balance = $row->vs_tf_balance;
    }

    $arr_ver[] =  array(
        'date'          =>  $row->datever,
        'barcode'       =>  $row->vs_barcode,
        'denomination'  =>  $row->vs_tf_denomination,
        'purchasecred'  =>  $purchasecred,
        'cus_fname'     =>  $row->cus_fname,
        'cus_lname'     =>  $row->cus_lname,
        'cus_mname'     =>  $row->cus_mname,
        'cus_namext'    =>  $row->cus_namext,
        'balance'       =>  $balance,
        'valid_type'    =>  'VERIFIED',
        'gc_type'       =>  $gctype,
        'businessunit'  =>  $bus,
        'terminalno'    =>  $tnum,
        'purchaseamt'   =>  $puramt,
        'payto'         =>  $row->vs_payto
    );

}

$query = $link->query(
        "SELECT 
            DATE(store_verification.vs_reverifydate) as datever,
            store_verification.vs_barcode,
            store_verification.vs_tf_denomination,
            store_verification.vs_tf_purchasecredit,
            store_verification.vs_payto,
            customers.cus_fname,
            customers.cus_lname,
            customers.cus_mname,
            customers.cus_namext,
            store_verification.vs_tf_balance,
            store_verification.vs_gctype
        FROM 
            store_verification 
        LEFT JOIN
            customers
        ON
            customers.cus_id = store_verification.vs_cn
        WHERE 
            YEAR(vs_reverifydate) = '$year' 
        AND 
            MONTH(vs_reverifydate) = '$month'
        AND
            vs_store='$stcus'
        ORDER BY vs_id ASC 
");

while ($row = $query->fetch_object()) 
{    
    $gctype = "";

    if($row->vs_gctype=='1')
    {
        $gctype = 'REGULAR';
    }
    elseif($row->vs_gctype=='2')
    {
        $gctype = 'SPECIAL REGULAR';
    }
    elseif($row->vs_gctype=='3')
    {
        $gctype = 'SPECIAL EXTERNAL';
    }
    elseif($row->vs_gctype=='4')
    {
        $gctype = 'PROMO';
    }
    elseif($row->vs_gctype=='5')
    {
        $gctype = 'SUPPLIER GC';
    }
    elseif($row->vs_gctype=='6')
    {
        $gctype = 'BEAM AND GO';
    }

    $bus ="";
    $tnum="";
    $puramt = "";
    $bdata = _getGCTextfileTR($link,$row->vs_barcode);
    if(count($bdata)> 0)
    {
        if(count($bdata)==1)
        {
            foreach ($bdata as $d) 
            {
                $puramt.= $d->seodtt_credpuramt;
                $bus.= $d->seodtt_bu;
                $tnum.= $d->seodtt_terminalno;
            }
        }
        else 
        {
            $bdatalength = count($bdata);
            $bcnt = 0;
            foreach ($bdata as $d) 
            {
                if($bcnt!==$bdatalength -1)
                {
                    $puramt.= $d->seodtt_credpuramt.', ';
                    $bus.= $d->seodtt_bu.', ';
                    $tnum.= $d->seodtt_terminalno.', ';                    
                }
                else 
                {
                    $puramt.= $d->seodtt_credpuramt;
                    $bus.= $d->seodtt_bu;
                    $tnum.= $d->seodtt_terminalno;                      
                }
                $bcnt++;
            }
        }
    }   
    $purchasecred = $row->vs_tf_purchasecredit;
    $balance = $row->vs_tf_balance;
    $arr_ver[] =  array(
        'date'          =>  $row->datever,
        'barcode'       =>  $row->vs_barcode,
        'denomination'  =>  $row->vs_tf_denomination,
        'purchasecred'  =>  $purchasecred,
        'cus_fname'     =>  $row->cus_fname,
        'cus_lname'     =>  $row->cus_lname,
        'cus_mname'     =>  $row->cus_mname,
        'cus_namext'    =>  $row->cus_namext,
        'balance'       =>  $balance,
        'valid_type'    =>  'REVERIFIED',
        'gc_type'       =>  $gctype,
        'businessunit'  =>  $bus,
        'terminalno'    =>  $tnum,
        'purchaseamt'   =>  $puramt,
        'payto'         =>  $row->vs_payto
    );
}

$sortArray = array(); 

foreach($arr_ver as $arr){ 
    foreach($arr as $key=>$value){ 
        if(!isset($sortArray[$key])){ 
            $sortArray[$key] = array(); 
        } 
        $sortArray[$key][] = $value; 
    } 
}

$orderby = "date";

array_multisort($sortArray[$orderby],SORT_ASC,$arr_ver); 

foreach ($arr_ver as $arr)
{
    $fullname = "";    
    $fullname.= $arr['cus_lname'].' ,';
    $fullname.= $arr['cus_fname'];
    if($arr['cus_mname']!='')
    {
        $fullname.= ' '.$arr['cus_mname'];
    }
    if($arr['cus_namext']!='')
    {
        $fullname.= ' '.$arr['cus_namext'];
    }

    $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $arr['date']);   //DATE VERIFIED / REVERIFIED

    $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $arr['barcode']); //BARCODE       
    $object->getActiveSheet()->getStyle('B'.$excel_row)->getNumberFormat()->setFormatCode('0');

    $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $arr['denomination']);
    $object->getActiveSheet()->getStyle('C'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');    
    $object->getActiveSheet()->getStyle('C'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //DENOMINATION

    $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $arr['purchasecred']);
    //AMOUNT REDEEM
    $object->getActiveSheet()->getStyle('D'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
    $object->getActiveSheet()->getStyle('D'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //AMOUNT REDEEM

    $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, strtoupper($fullname)); //CUSTOMER NAME

    $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $arr['balance']);
    $object->getActiveSheet()->getStyle('F'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
    $object->getActiveSheet()->getStyle('F'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);  //BALANCE
    $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row,$arr['businessunit']); //TERMINAL #
    $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $arr['terminalno']); //TERMINAL #
    $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $arr['valid_type']); //TYPE
    $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $arr['gc_type']); //GC TYPE
    $excel_row++;
}

// get verified gc by month per day
//$data = getVerifiedDateByMonthAndYear($link,$month,$year,$stcus);

//var_dump($data);

//die();

// Rename worksheet
$object->getActiveSheet()->setTitle("per day");

$objWorkSheet = $object->createSheet(1);
$object->setActiveSheetIndex(1);

//
$a_date = $year."-".$month."-1";
$monthfull = date('F', strtotime($a_date));
$lastday =  date("t", strtotime($a_date));
$period = 'PERIOD COVER: '.strtoupper($monthfull).' 1 - '.$lastday.', '.$year;

$object->getActiveSheet()->getColumnDimension('A')->setWidth(20);   
$object->getActiveSheet()->getColumnDimension('B')->setWidth(24);    
$object->getActiveSheet()->getColumnDimension('C')->setWidth(28);   
$object->getActiveSheet()->getColumnDimension('D')->setWidth(16);   
$object->getActiveSheet()->getColumnDimension('E')->setWidth(34);   
$object->getActiveSheet()->getColumnDimension('F')->setWidth(20);   
$object->getActiveSheet()->getColumnDimension('G')->setWidth(18);    

$object->getActiveSheet()->mergeCells('A1:G1')->getStyle("A1:G1")->getFont()->setBold( true );
$object->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->mergeCells('A2:G2')->getStyle("A2:G2")->getFont()->setBold( true );
$object->getActiveSheet()->getStyle('A2:G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->mergeCells('A3:G3')->getStyle("A3:G3")->getFont()->setBold( true );
$object->getActiveSheet()->getStyle('A3:G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->mergeCells('A4:G4')->getStyle("A4:G4")->getFont()->setBold( true );
$object->getActiveSheet()->getStyle('A4:G4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->mergeCells('A6:G6')->getStyle("A6:G6")->getFont()->setBold( true );
$object->getActiveSheet()->getStyle('A6:G6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$object->setActiveSheetIndex(1)
    ->setCellValue('A1','ALTURAS GROUP OF COMPANIES')
    ->setCellValue('A2','CUSTOMER FINANCIAL SERVICES CORP')
    ->setCellValue('A3','MONTHLY REPORT ON GIFT CHECK (GC)')
    ->setCellValue('A4',$period)
    ->setCellValue('A6','BUSINESS UNITS: '.strtoupper($storename));

$table_columns = array("DATE", "TOTAL GC VERIFIED", "TOTAL GC AMOUNT REDEEM", "BALANCES", "TOTAL GC PURCHASE BASED ON POS","VARIANCE","REMARKS");

$excel_row = 8;
$column = 0;
foreach($table_columns as $field)
{
    $object->getActiveSheet()->setCellValueByColumnAndRow($column, $excel_row, $field);
    $column++;
}
$object->getActiveSheet()->getStyle("A8:H8")->getFont()->setBold( true );
$excel_row++;
$query = $link->query(
        "SELECT 
            DATE(store_verification.vs_date) as datever,
            IFNULL(SUM(store_verification.vs_tf_denomination),00.0) as totverifiedgc,
            IFNULL(SUM(store_verification.vs_tf_balance),00.0) as balance,
            IFNULL(SUM(store_verification.vs_tf_purchasecredit),00.0) as redeem
        FROM 
            store_verification 
        LEFT JOIN
            customers
        ON
            customers.cus_id = store_verification.vs_cn
        WHERE 
            ((YEAR(vs_date) = '$year' 
        AND 
            MONTH(vs_date) = '$month')
        OR
            (YEAR(vs_reverifydate) = '$year' 
        AND 
            MONTH(vs_reverifydate) = '$month'))
        AND
            vs_store='$stcus'
        GROUP BY vs_date
");

$arr_monthver = [];

while ($row = $query->fetch_object()) 
{
    $arr_monthver[] =  array(
        'date'          =>  $row->datever,
        'totverifiedgc' =>  $row->totverifiedgc,
        'balance'       =>  $row->balance,
        'redeem'        =>  $row->redeem
    );
}

$query = $link->query(
        "SELECT 
            DATE(store_verification.vs_reverifydate) as datever,
            IFNULL(SUM(store_verification.vs_tf_denomination),00.0) as sumver,
            IFNULL(SUM(store_verification.vs_tf_balance),00.0) as balance,
            IFNULL(SUM(store_verification.vs_tf_purchasecredit),00.0) as redeem,
            store_verification.vs_payto
        FROM 
            store_verification 
        LEFT JOIN
            customers
        ON
            customers.cus_id = store_verification.vs_cn
        WHERE 
            YEAR(vs_reverifydate) = '$year' 
        AND 
            MONTH(vs_reverifydate) = '$month'
        AND
            vs_store='$stcus'
        GROUP BY vs_date
");

while ($row = $query->fetch_object()) 
{    
    foreach ($arr_monthver as $key => $value) 
    {
        $totalver = 0;
        $totalbal = 0;
        $totalred = 0;
        if($row->datever == $value['date'])
        {
            $totalver = floatval($row->sumver) + floatval($value['totverifiedgc']);
            $totalbal = floatval($row->balance) + floatval($value['balance']);
            $totalred = floatval($row->redeem) + floatval($value['redeem']);
            $arr_monthver[$key]['totverifiedgc'] = $totalver;
            $arr_monthver[$key]['balance'] = $totalbal;
            $arr_monthver[$key]['redeem'] = $totalred;
            break;
        }
    }
}

foreach ($arr_monthver as $key) 
{
    $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $key['date']);   
    $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $key['totverifiedgc']);
    $object->getActiveSheet()->getStyle('B'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
    //$object->getActiveSheet()->getStyle('C'.$excel_row)->getNumberFormat()->setFormatCode('0');
    $object->getActiveSheet()->getStyle('B'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $key['redeem']);
    $object->getActiveSheet()->getStyle('C'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
    $object->getActiveSheet()->getStyle('C'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $key['balance']);
    $object->getActiveSheet()->getStyle('D'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
    $object->getActiveSheet()->getStyle('D'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $excel_row++;
}


$object->getActiveSheet()->setTitle("By month summary perday");

$objWorkSheet = $object->createSheet(2);
$object->setActiveSheetIndex(2);

$object->getActiveSheet()->getColumnDimension('A')->setWidth(20);   
$object->getActiveSheet()->getColumnDimension('B')->setWidth(28);    
$object->getActiveSheet()->getColumnDimension('C')->setWidth(20);   
$object->getActiveSheet()->getColumnDimension('D')->setWidth(20);   
$object->getActiveSheet()->getColumnDimension('E')->setWidth(20);   
$object->getActiveSheet()->getColumnDimension('F')->setWidth(20);   
$object->getActiveSheet()->getColumnDimension('G')->setWidth(20);  
$object->getActiveSheet()->getColumnDimension('H')->setWidth(20); 
$object->getActiveSheet()->getColumnDimension('I')->setWidth(20);   

$object->getActiveSheet()->mergeCells('A1:H1')->getStyle("A1:G1")->getFont()->setBold( true );
$object->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->mergeCells('A2:H2')->getStyle("A2:G2")->getFont()->setBold( true );
$object->getActiveSheet()->getStyle('A2:H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->mergeCells('A3:H3')->getStyle("A3:G3")->getFont()->setBold( true );
$object->getActiveSheet()->getStyle('A3:H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->mergeCells('A4:H4')->getStyle("A4:G4")->getFont()->setBold( true );
$object->getActiveSheet()->getStyle('A4:H4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$object->getActiveSheet()->mergeCells('A6:H6')->getStyle("A6:G6")->getFont()->setBold( true );
$object->getActiveSheet()->getStyle('A6:H6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$object->setActiveSheetIndex(2)
    ->setCellValue('A1','ALTURAS GROUP OF COMPANIES')
    ->setCellValue('A2','CUSTOMER FINANCIAL SERVICES CORP')
    ->setCellValue('A3','MONTHLY REPORT ON GIFT CHECK (Per GC Type & BU)')
    ->setCellValue('A4',$period)
    ->setCellValue('A6','BUSINESS UNITS: '.strtoupper($storename));

$table_columns = array("DATE", "GC Type", "Amount", "SM", "HF","MP","FR","SOD","WS");

$excel_row = 8;
$column = 0;
foreach($table_columns as $field)
{
    $object->getActiveSheet()->setCellValueByColumnAndRow($column, $excel_row, $field);
    $column++;
}

$object->getActiveSheet()->getStyle("A8:I8")->getFont()->setBold( true );
    // $arr_ver[] =  array(
    //     'date'          =>  $row->datever,
    //     'barcode'       =>  $row->vs_barcode,
    //     'denomination'  =>  $row->vs_tf_denomination,
    //     'purchasecred'  =>  $purchasecred,
    //     'cus_fname'     =>  $row->cus_fname,
    //     'cus_lname'     =>  $row->cus_lname,
    //     'cus_mname'     =>  $row->cus_mname,
    //     'cus_namext'    =>  $row->cus_namext,
    //     'balance'       =>  $balance,
    //     'valid_type'    =>  'VERIFIED',
    //     'gc_type'       =>  $gctype,
    //     'businessunit'  =>  $bus,
    //     'terminalno'    =>  $tnum,
    //     'purchaseamt'   =>  $puramt
    // );


$excel_row++;
$datedisplay = "";

// foreach ($arr_ver as $arr)
// {
//     if($datedisplay!=$arr['date'])
//     {
//         $datedisplay = $arr['date'];
//         $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $arr['date']);   //DATE VERIFIED / REVERIFIED
//     }
//     $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $arr['gc_type']);
    
//     $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $arr['purchasecred']);
//     $object->getActiveSheet()->getStyle('C'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');    
//     $object->getActiveSheet()->getStyle('C'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //AMOUNT
//     $terminal = [];
//     $purchase = [];

//     if(floatval($arr['purchasecred']) > 0)
//     {
        
//         $terminal = explode(",",$arr['terminalno']);
//         $purchase = explode(",",$arr['purchaseamt']);

//         $hasSM = false;
//         $hasHF = false;
//         $hasMP = false;
//         $hasFR = false;
//         $hasSOD = false;

//         $amtSM = 0;
//         $amtHF = 0;
//         $amtMP = 0;
//         $amtFR = 0;
//         $amtSOD = 0;

//         for ($i=0; $i < count($terminal); $i++) 
//         {   
//             $term = explode("-", $terminal[$i]);
//             //echo $term[0];

//             if(trim($term[0])==='SM')
//             {
//                 $hasSM = true;
//                 $amtSM += $purchase[$i];                   
//             }

//             if(trim($term[0])==='HF')
//             {
//                 $hasHF = true;
//                 $amtHF += $purchase[$i];                   
//             }            

//             if(trim($term[0])==='MP')
//             {
//                 $hasMP = true;
//                 $amtMP += $purchase[$i];                    
//             }            

//             if(trim($term[0])==='FR')
//             {
//                 $hasFR = true;
//                 $amtFR += $purchase[$i];                   
//             }  

//             if(trim($term[0])==='SOD')
//             {
//                 $hasSOD = true;
//                 $amtSOD += $purchase[$i];                    
//             }  
//         }

//         //echo '<br>';

//         if($hasSM)
//         {
//             $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $amtSM);
//             $object->getActiveSheet()->getStyle('D'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');    
//             $object->getActiveSheet()->getStyle('D'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //DENOMINATION
//         }

//         if($hasHF)
//         {
//             $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $amtHF);
//             $object->getActiveSheet()->getStyle('E'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');    
//             $object->getActiveSheet()->getStyle('E'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //DENOMINATION   
//         }

//         if($hasMP)
//         {
//             $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $amtMP);
//             $object->getActiveSheet()->getStyle('F'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');    
//             $object->getActiveSheet()->getStyle('F'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //DENOMINATION      
//         }

//         if($hasFR)
//         {
//             $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $amtFR);
//             $object->getActiveSheet()->getStyle('G'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');    
//             $object->getActiveSheet()->getStyle('G'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //DENOMINATION         
//         }

//         if($hasSOD)
//         {
//             $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $amtSOD);
//             $object->getActiveSheet()->getStyle('H'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');    
//             $object->getActiveSheet()->getStyle('H'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //DENOMINATION            
//         }


//         // check if SM exist

//     }

//     $excel_row++;
// }

$arr_perdate = [];

$specialgc = 0;
$regulargc = 0;
$bng = 0;

$arr_special = [];
$arr_regular = [];
$arr_terbng = [];

$hasSM = false;
$hasHF = false;
$hasMP = false;
$hasFR = false;
$hasSOD = false;
$hasWS = false;

$amtSM = 0;
$amtHF = 0;
$amtMP = 0;
$amtFR = 0;
$amtSOD = 0;
$amtWS = 0;

$arr_terspecial[] =  array(
    'amtSM'   =>    0,
    'amtHF'   =>    0,
    'amtMP'   =>    0,
    'amtFR'   =>    0,
    'amtSOD'  =>    0,
    'amtWS'   =>    0
);  

$arr_terregular[] =  array(
    'amtSM'   =>    0,
    'amtHF'   =>    0,
    'amtMP'   =>    0,
    'amtFR'   =>    0,
    'amtSOD'  =>    0,
    'amtWS'   =>    0
);  

$arr_terbng[] =  array(
    'amtSM'   =>    0,
    'amtHF'   =>    0,
    'amtMP'   =>    0,
    'amtFR'   =>    0,
    'amtSOD'  =>    0,
    'amtWS'   =>    0
); 

$cntarr = count($arr_ver);
$cnter = 0;

foreach ($arr_ver as $arr)
{

    if($arr['payto']=='WHOLESALE')
    {
        $hasWS = true;
        //$arr_terspecial[0]['amtSM'] += $purchase[$i];
        $amtWS += $arr['purchaseamt'];
    }
    else 
    {
        if(floatval($arr['purchasecred']) > 0)
        {
            $terminal = explode(",",$arr['terminalno']);
            $purchase = explode(",",$arr['purchaseamt']);

            for ($i=0; $i < count($terminal); $i++) 
            {   
                $term = explode("-", $terminal[$i]);
                //echo $term[0];

                if(trim($term[0])==='SM')
                {
                    $hasSM = true;
                    //$arr_terspecial[0]['amtSM'] += $purchase[$i];
                    $amtSM += $purchase[$i];                   
                }

                if(trim($term[0])==='HF')
                {
                    $hasHF = true;
                    //$arr_terspecial[0]['amtHF'] += $purchase[$i];
                    $amtHF += $purchase[$i];                   
                }            

                if(trim($term[0])==='MP')
                {
                    $hasMP = true;
                    //$arr_terspecial[0]['amtMP'] += $purchase[$i];
                    $amtMP += $purchase[$i];                    
                }            

                if(trim($term[0])==='FR')
                {
                    $hasFR = true;
                    //$arr_terspecial[0]['amtFR'] += $purchase[$i];
                    $amtFR += $purchase[$i];                   
                }  

                if(trim($term[0])==='SOD')
                {
                    $hasSOD = true;
                    //$arr_terspecial[0]['amtSOD'] += $purchase[$i];
                    $amtSOD += $purchase[$i];                    
                }  
            }
        }        
    }

    if($datedisplay!=$arr['date'])
    {
        if($cnter===1)
        {
            $datedisplay = $arr['date'];
            //echo '--';

            if($arr['gc_type']=='SPECIAL EXTERNAL')
            {
                $arr_terspecial[0]['amtSM'] += $amtSM;
                $arr_terspecial[0]['amtHF'] += $amtHF;
                $arr_terspecial[0]['amtMP'] += $amtMP;
                $arr_terspecial[0]['amtFR'] += $amtFR;
                $arr_terspecial[0]['amtSOD'] += $amtSOD;
                $arr_terspecial[0]['amtWS'] += $amtWS;
                $specialgc+=$arr['purchasecred'];
            }

            if($arr['gc_type']=='REGULAR')
            {
                $arr_terregular[0]['amtSM'] += $amtSM;
                $arr_terregular[0]['amtHF'] += $amtHF;
                $arr_terregular[0]['amtMP'] += $amtMP;
                $arr_terregular[0]['amtFR'] += $amtFR;
                $arr_terregular[0]['amtSOD'] += $amtSOD;
                $arr_terregular[0]['amtWS'] += $amtWS;
                $regulargc+=$arr['purchasecred'];
            }

            if($arr['gc_type']=='BEAM AND GO')
            {
                $arr_terbng[0]['amtSM'] += $amtSM;
                $arr_terbng[0]['amtHF'] += $amtHF;
                $arr_terbng[0]['amtMP'] += $amtMP;
                $arr_terbng[0]['amtFR'] += $amtFR;
                $arr_terbng[0]['amtSOD'] += $amtSOD;
                $arr_terbng[0]['amtWS'] += $amtWS;
                $bng+=$arr['purchasecred'];

            }

            //echo $arr_terspecial[0]['amtSM'].'<br/>';
        }
        else 
        {
            $arr_perdate[] =  array(
                'arr_perdate'   =>  $datedisplay,
                'regular'       =>  $regulargc,
                'special'       =>  $specialgc,
                'bng'           =>  $bng,
                'terminalreg'   =>  $arr_terregular,
                'terminalspec'  =>  $arr_terspecial,
                'terminalbng'  =>   $arr_terbng
            );    


            $arr_terspecial[0]['amtSM'] = 0;
            $arr_terspecial[0]['amtHF'] = 0;
            $arr_terspecial[0]['amtMP'] = 0;
            $arr_terspecial[0]['amtFR'] = 0;
            $arr_terspecial[0]['amtSOD'] = 0;
            $arr_terspecial[0]['amtWS'] = 0;

            $arr_terregular[0]['amtSM'] = 0;
            $arr_terregular[0]['amtHF'] = 0;
            $arr_terregular[0]['amtMP'] = 0;
            $arr_terregular[0]['amtFR'] = 0;
            $arr_terregular[0]['amtSOD'] = 0;
            $arr_terregular[0]['amtWS'] = 0;

            $arr_terbng[0]['amtSM'] = 0;
            $arr_terbng[0]['amtHF'] = 0;
            $arr_terbng[0]['amtMP'] = 0;
            $arr_terbng[0]['amtFR'] = 0;
            $arr_terbng[0]['amtSOD'] = 0;
            $arr_terbng[0]['amtWS'] = 0;

            $datedisplay = $arr['date'];

            $specialgc = 0;
            $regulargc = 0;
            $bng = 0;

            if($arr['gc_type']=='SPECIAL EXTERNAL')
            {
                $arr_terspecial[0]['amtSM'] += $amtSM;
                $arr_terspecial[0]['amtHF'] += $amtHF;
                $arr_terspecial[0]['amtMP'] += $amtMP;
                $arr_terspecial[0]['amtFR'] += $amtFR;
                $arr_terspecial[0]['amtSOD'] += $amtSOD;
                $arr_terspecial[0]['amtWS'] += $amtWS;
                $specialgc+=$arr['purchasecred'];
            }

            if($arr['gc_type']=='REGULAR')
            {
                $arr_terregular[0]['amtSM'] += $amtSM;
                $arr_terregular[0]['amtHF'] += $amtHF;
                $arr_terregular[0]['amtMP'] += $amtMP;
                $arr_terregular[0]['amtFR'] += $amtFR;
                $arr_terregular[0]['amtSOD'] += $amtSOD;
                $arr_terregular[0]['amtWS'] += $amtWS;
                $regulargc+=$arr['purchasecred'];
            }

            if($arr['gc_type']=='BEAM AND GO')
            {
                $arr_terbng[0]['amtSM'] += $amtSM;
                $arr_terbng[0]['amtHF'] += $amtHF;
                $arr_terbng[0]['amtMP'] += $amtMP;
                $arr_terbng[0]['amtFR'] += $amtFR;
                $arr_terbng[0]['amtSOD'] += $amtSOD;
                $arr_terbng[0]['amtWS'] += $amtWS;
                $bng+=$arr['purchasecred'];
            }
        }
    }

    else 
    {
        if($arr['gc_type']=='SPECIAL EXTERNAL')
        {
            $arr_terspecial[0]['amtSM'] += $amtSM;
            $arr_terspecial[0]['amtHF'] += $amtHF;
            $arr_terspecial[0]['amtMP'] += $amtMP;
            $arr_terspecial[0]['amtFR'] += $amtFR;
            $arr_terspecial[0]['amtSOD'] += $amtSOD;
            $arr_terspecial[0]['amtWS'] += $amtWS;
            $specialgc+=$arr['purchasecred'];
        }

        if($arr['gc_type']=='REGULAR')
        {
            $arr_terregular[0]['amtSM'] += $amtSM;
            $arr_terregular[0]['amtHF'] += $amtHF;
            $arr_terregular[0]['amtMP'] += $amtMP;
            $arr_terregular[0]['amtFR'] += $amtFR;
            $arr_terregular[0]['amtSOD'] += $amtSOD;
            $arr_terregular[0]['amtWS'] += $amtWS;
            $regulargc+=$arr['purchasecred'];
        }

        if($arr['gc_type']=='BEAM AND GO')
        {
            $arr_terbng[0]['amtSM'] += $amtSM;
            $arr_terbng[0]['amtHF'] += $amtHF;
            $arr_terbng[0]['amtMP'] += $amtMP;
            $arr_terbng[0]['amtFR'] += $amtFR;
            $arr_terbng[0]['amtSOD'] += $amtSOD;
            $arr_terbng[0]['amtWS'] += $amtWS;
            $bng+=$arr['purchasecred'];
        }
        // echo $arr_terregular[0]['amtSM'].'reg<br />';
        // echo $amtSM.'amt<br />';
    }



    $amtSM = 0;
    $amtHF = 0;
    $amtMP = 0;
    $amtFR = 0;
    $amtSOD = 0;
    $amtWS = 0;
    $cnter++;

    if($cntarr === $cnter)
    {
        $arr_perdate[] =  array(
            'arr_perdate'   =>  $datedisplay,
            'regular'       =>  $regulargc,
            'special'       =>  $specialgc,
            'bng'           =>  $bng,
            'terminalreg'   =>  $arr_terregular,
            'terminalspec'  =>  $arr_terspecial,
            'terminalbng'   =>  $arr_terbng
        ); 
    }

    //echo $cnter.'<br>';

}


// echo '<pre>';
// print_r($arr_ver);
// echo '</pre>';

// exit();

foreach ($arr_perdate as $perdate) 
{
    $datedisplay = $arr['date'];
    $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $perdate['arr_perdate']);   //DATE
    $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, 'REGULAR');
    $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $perdate['regular']);
    $object->getActiveSheet()->getStyle('C'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');    
    $object->getActiveSheet()->getStyle('C'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //AMOUNT

    $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $perdate['terminalreg'][0]['amtSM']);
    $object->getActiveSheet()->getStyle('D'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');    
    $object->getActiveSheet()->getStyle('D'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //AMOUNT

    $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $perdate['terminalreg'][0]['amtHF']);
    $object->getActiveSheet()->getStyle('E'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');    
    $object->getActiveSheet()->getStyle('E'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //AMOUNT

    $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $perdate['terminalreg'][0]['amtMP']);
    $object->getActiveSheet()->getStyle('F'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');    
    $object->getActiveSheet()->getStyle('F'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //AMOUNT

    $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $perdate['terminalreg'][0]['amtFR']);
    $object->getActiveSheet()->getStyle('G'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');    
    $object->getActiveSheet()->getStyle('G'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //AMOUNT    

    $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $perdate['terminalreg'][0]['amtSOD']);
    $object->getActiveSheet()->getStyle('H'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');    
    $object->getActiveSheet()->getStyle('H'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //AMOUNT        

    $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $perdate['terminalreg'][0]['amtWS']);
    $object->getActiveSheet()->getStyle('I'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');    
    $object->getActiveSheet()->getStyle('I'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //AMOUNT  

    $excel_row++;
    $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, 'SPECIAL EXTERNAL');
    $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $perdate['special']);
    $object->getActiveSheet()->getStyle('C'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');    
    $object->getActiveSheet()->getStyle('C'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //AMOUNT

    $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $perdate['terminalspec'][0]['amtSM']);
    $object->getActiveSheet()->getStyle('D'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');    
    $object->getActiveSheet()->getStyle('D'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //AMOUNT

    $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $perdate['terminalspec'][0]['amtHF']);
    $object->getActiveSheet()->getStyle('E'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');    
    $object->getActiveSheet()->getStyle('E'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //AMOUNT

    $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $perdate['terminalspec'][0]['amtMP']);
    $object->getActiveSheet()->getStyle('F'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');    
    $object->getActiveSheet()->getStyle('F'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //AMOUNT

    $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $perdate['terminalspec'][0]['amtFR']);
    $object->getActiveSheet()->getStyle('G'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');    
    $object->getActiveSheet()->getStyle('G'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //AMOUNT    

    $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $perdate['terminalspec'][0]['amtSOD']);
    $object->getActiveSheet()->getStyle('H'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');    
    $object->getActiveSheet()->getStyle('H'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //AMOUNT        

    $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $perdate['terminalspec'][0]['amtWS']);
    $object->getActiveSheet()->getStyle('I'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');    
    $object->getActiveSheet()->getStyle('I'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //AMOUNT 
    
    $excel_row++;
    $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, 'BEAM AND GO');
    $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $perdate['bng']);
    $object->getActiveSheet()->getStyle('C'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');    
    $object->getActiveSheet()->getStyle('C'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //AMOUNT


    $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $perdate['terminalbng'][0]['amtSM']);
    $object->getActiveSheet()->getStyle('D'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');    
    $object->getActiveSheet()->getStyle('D'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //AMOUNT

    $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $perdate['terminalbng'][0]['amtHF']);
    $object->getActiveSheet()->getStyle('E'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');    
    $object->getActiveSheet()->getStyle('E'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //AMOUNT

    $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $perdate['terminalbng'][0]['amtMP']);
    $object->getActiveSheet()->getStyle('F'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');    
    $object->getActiveSheet()->getStyle('F'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //AMOUNT

    $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $perdate['terminalbng'][0]['amtFR']);
    $object->getActiveSheet()->getStyle('G'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');    
    $object->getActiveSheet()->getStyle('G'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //AMOUNT    

    $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $perdate['terminalbng'][0]['amtSOD']);
    $object->getActiveSheet()->getStyle('H'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');    
    $object->getActiveSheet()->getStyle('H'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //AMOUNT 

    $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $perdate['terminalbng'][0]['amtWS']);
    $object->getActiveSheet()->getStyle('I'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');    
    $object->getActiveSheet()->getStyle('I'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //AMOUNT 


    $excel_row++;    
}

// echo '<pre>';
// print_r($arr_perdate);
// echo '</pre>';
// exit();
$object->getActiveSheet()->setTitle("Per GC Type & BU");

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$object->setActiveSheetIndex(0);


//var_dump($arr_ver);


// Redirect output to a client’s web browser (Excel2007)
// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
// header('Content-Disposition: attachment;filename="'.$name.'.xlsx"');
// header('Cache-Control: max-age=0');
// // If you're serving to IE 9, then the following may be needed
// header('Cache-Control: max-age=1');

// // If you're serving to IE over SSL, then the following may be needed
// header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
// header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
// header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
// header ('Pragma: public'); // HTTP/1.0

// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

header('Content-Type: application/openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$name.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
$objWriter->save('php://output');
exit;
