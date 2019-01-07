<?php
session_start();
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
$name = 'Budget Ledger';
$query = $link->query("SELECT * FROM `ledger_budget`");

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
							 ->setTitle("Ledger Budget")
							 ->setSubject("Ledger Budget")
							 ->setDescription("Ledger Budget.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Ledget Budget");
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Ledger No.')
            ->setCellValue('B1', 'Date')
            ->setCellValue('C1', 'Transaction')
            ->setCellValue('D1', 'Debit')
            ->setCellValue('E1', 'Credit');
$rowCount = 3;
while($row = $query->fetch_object())
{
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$rowCount,$row->bledger_no);
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$rowCount,$row->bledger_datetime);
    $objPHPExcel->getActiveSheet()->setCellValue('C'.$rowCount,$row->bledger_type);
    $objPHPExcel->getActiveSheet()->setCellValue('D'.$rowCount,$row->bdebit_amt);
    $objPHPExcel->getActiveSheet()->setCellValue('E'.$rowCount,$row->bcredit_amt);
    $rowCount++;
}
// $objPHPExcel->setActiveSheetIndex(0)
//             ->setCellValue('A1', 'Hello')
//             ->setCellValue('B2', 'world!')
//             ->setCellValue('C1', 'Hello')
//             ->setCellValue('D2', 'world!');

// Miscellaneous glyphs, UTF-8
// $objPHPExcel->setActiveSheetIndex(0)
//             ->setCellValue('A4', 'Miscellaneous glyphs')
//             ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle($name);


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
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$name.'.csv"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
