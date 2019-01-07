<?php
session_start();

if(isset($_GET['daterange']))
{
    $daterange = $_GET['daterange'];
    $drange = explode('-', $daterange);
    $drange1 = $drange[0];
    $drange2 = $drange[1];

    $drange1 = _dateFormatoSql($drange1);
    $drange2 = _dateFormatoSql($drange2);

}
else 
{

}

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
$storeid = getField($link,'store_assigned','users','user_id',$_SESSION['gc_id']);
$rname = '';
if($drange1==$drange2)
{
    $rname = _dateFormatReportName($drange1);
}
else 
{
    $rname = _dateFormatReportName($drange1).'to'._dateFormatReportName($drange2);
}

$name = 'verifiedgcreport'.$rname;

function getField($link,$field,$table,$field2,$var)
{
    $query = $link->query("SELECT $field FROM $table WHERE $field2='$var'");
    $row = $query->fetch_assoc();
    return $row[$field];
}

function _dateFormatoSql($date_to_format){
    $date_to_format = date_create($date_to_format);
    return date_format($date_to_format, 'Y-m-d');
}

function _dateFormatReportName($date_to_format)
{
    $date_to_format = date_create($date_to_format);
    return date_format($date_to_format, 'd-m-Y');
}

function verifiedGC($link,$storeid,$drange1,$drange2)
{
    $rows = [];
    $query = $link->query(
        "SELECT 
            store_verification.vs_barcode,
            store_verification.vs_date,
            store_verification.vs_tf_denomination,
            store_verification.vs_tf_balance,
            store_verification.vs_reverifydate,
            stores.store_name,
            CONCAT(customers.cus_lname,', ',customers.cus_fname,' ',customers.cus_mname,' ',customers.cus_namext) as cus,
            CONCAT(users.lastname,', ',users.firstname) as verby
        FROM 
            store_verification
        LEFT JOIN
            stores
        ON
            stores.store_id = store_verification.vs_store
        LEFT JOIN
            customers
        ON
            customers.cus_id = store_verification.vs_cn
        LEFT JOIN
            users
        ON
            users.user_id = store_verification.vs_by
        WHERE 
            store_verification.vs_store='".$storeid."'
        AND
            (DATE_FORMAT(store_verification.vs_date,'%Y-%m-%d') >= '$drange1'
        AND
            DATE_FORMAT(store_verification.vs_date,'%Y-%m-%d') <='$drange2')
        ORDER BY
            store_verification.vs_date
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

function reverifiedgc($link,$storeid,$drange1,$drange2)
{
    $rows = [];
    $query = $link->query(
        "SELECT 
            store_verification.vs_barcode,
            store_verification.vs_tf_denomination,
            store_verification.vs_tf_balance,
            DATE_FORMAT(store_verification.vs_reverifydate,'%Y-%m-%d') as reverdate,
            stores.store_name,
            CONCAT(customers.cus_lname,', ',customers.cus_fname,' ',customers.cus_mname,' ',customers.cus_namext) as cus,
            CONCAT(users.lastname,', ',users.firstname) as reverby
        FROM 
            store_verification
        LEFT JOIN
            stores
        ON
            stores.store_id = store_verification.vs_store
        LEFT JOIN
            customers
        ON
            customers.cus_id = store_verification.vs_cn
        LEFT JOIN
            users
        ON
            users.user_id = store_verification.vs_reverifyby
        WHERE 
            store_verification.vs_store='".$storeid."'
        AND
            (DATE_FORMAT(store_verification.vs_reverifydate,'%Y-%m-%d') >= '$drange1'
        AND
            DATE_FORMAT(store_verification.vs_reverifydate,'%Y-%m-%d') <='$drange2')
        ORDER BY
            store_verification.vs_date
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

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();


// Set document properties
$objPHPExcel->getProperties()->setCreator($_SESSION['gc_fullname'])
							 ->setLastModifiedBy($_SESSION['gc_fullname'])
							 ->setTitle("GC Report")
							 ->setSubject("GC Report")
							 ->setDescription("GC Report")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("GC Report");


$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(45);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(45);

$table_columns = array("DATE VERIFIED", "BARCODE", "DENOMINATION","BALANCE","STORE VERIFIED", "CUSTOMER NAME","VERIFIED BY");

$column = 0;

foreach($table_columns as $field)
{
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
    $column++;
}

$objPHPExcel->getActiveSheet()->getStyle("A1:G1")->getFont()->setBold( true );
$objPHPExcel->getActiveSheet()->freezePane('A2');

$excel_row = 2;

$vergc = verifiedGC($link,$storeid,$drange1,$drange2);

//var_dump($vergc);

//exit();

foreach($vergc as $s)
{
    $balance=0;
    if($s->vs_reverifydate=="")
    {
        $balance = $s->vs_tf_balance;
    }
    else 
    {
        $balance = $s->vs_tf_denomination;
    }

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $s->vs_date);
    $objPHPExcel->getActiveSheet()->getStyle('B'.$excel_row)->getNumberFormat()->setFormatCode('0');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $s->vs_barcode);
    $objPHPExcel->getActiveSheet()->getStyle('C'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $s->vs_tf_denomination);
    $objPHPExcel->getActiveSheet()->getStyle('D'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $balance);
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, strtoupper($s->store_name));
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, strtoupper($s->cus));
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, strtoupper($s->verby));
    // $objPHPExcel->getActiveSheet()->getStyle('B'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
    // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $item->si_srp);
    // $objPHPExcel->getActiveSheet()->getStyle('C'.$excel_row)->getNumberFormat()->setFormatCode('0');
    // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $item->sld_mobilenum);
    // $objPHPExcel->getActiveSheet()->getStyle('D'.$excel_row)->getNumberFormat()->setFormatCode('0');
    // $objPHPExcel->getActiveSheet()->getStyle('D'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $item->sld_refnum);
    // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, "");
    // $objPHPExcel->getActiveSheet()->getStyle('F'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
    // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $item->si_netprice);
    // $objPHPExcel->getActiveSheet()->getStyle('G'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
    // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $sub);
    $excel_row++;
}

$excel_row++;

$revergc = reverifiedgc($link,$storeid,$drange1,$drange2);

foreach($revergc as $s)
{
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $s->reverdate);
    $objPHPExcel->getActiveSheet()->getStyle('B'.$excel_row)->getNumberFormat()->setFormatCode('0');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $s->vs_barcode);
    $objPHPExcel->getActiveSheet()->getStyle('C'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $s->vs_tf_denomination);
    $objPHPExcel->getActiveSheet()->getStyle('D'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $s->vs_tf_balance);
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, strtoupper($s->store_name));
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, strtoupper($s->cus));
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, strtoupper($s->reverby));
    // $objPHPExcel->getActiveSheet()->getStyle('B'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
    // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $item->si_srp);
    // $objPHPExcel->getActiveSheet()->getStyle('C'.$excel_row)->getNumberFormat()->setFormatCode('0');
    // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $item->sld_mobilenum);
    // $objPHPExcel->getActiveSheet()->getStyle('D'.$excel_row)->getNumberFormat()->setFormatCode('0');
    // $objPHPExcel->getActiveSheet()->getStyle('D'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $item->sld_refnum);
    // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, "");
    // $objPHPExcel->getActiveSheet()->getStyle('F'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
    // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $item->si_netprice);
    // $objPHPExcel->getActiveSheet()->getStyle('G'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00');
    // $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $sub);
    $excel_row++;
}

// $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, "1010000000001");
// $objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getNumberFormat()->setFormatCode('0');
//$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getNumberFormat()->setFormatCode('#,##0.00');




// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle("GC Report");


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


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
header('Content-Disposition: attachment;filename="'.$name.'.xls"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
