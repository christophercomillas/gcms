<?php
session_start();

if(isset($_GET['requis']))
{
    $requisnum = $_GET['requis'];
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
$name = 'gcrequisition'.$requisnum;
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

function _dateFormat($todays_date){
    $todays_date = date_create($todays_date);
    return date_format($todays_date, 'F d, Y');     
}

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

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
$objPHPExcel->getProperties()->setCreator($_SESSION['gc_fullname'])
							 ->setLastModifiedBy($_SESSION['gc_fullname'])
							 ->setTitle("GC Requisition")
							 ->setSubject("GC Requisition")
							 ->setDescription("GC Requisition")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("GC Requisition");
// Add some data

$select = 'requisition_entry.requis_erno,
            requisition_entry.requis_req,
            requisition_entry.requis_need,
            requisition_entry.requis_approved,
            requisition_entry.requis_checked,
            requisition_entry.repuis_pro_id,
            users.firstname,
            users.lastname,
            supplier.gcs_companyname,
            supplier.gcs_contactperson,
            supplier.gcs_contactnumber,
            supplier.gcs_address';
$where = 'requisition_entry.requis_erno='.$requisnum;
$join = 'INNER JOIN
            users
        ON
            users.user_id = requisition_entry.requis_req_by 
        INNER JOIN
            supplier
        ON
            supplier.gcs_id = requisition_entry.requis_supplierid';
$limit = 'LIMIT 1';
$requisdetails = getSelectedData($link,'requisition_entry',$select,$where,$join,$limit);

$objPHPExcel->getActiveSheet()->mergeCells('A1:N1');
$objPHPExcel->getActiveSheet()->mergeCells('A2:N2');
$objPHPExcel->getActiveSheet()->mergeCells('A3:N3');
$objPHPExcel->getActiveSheet()->mergeCells('A5:C5');
$objPHPExcel->getActiveSheet()->mergeCells('K5:L5');
$objPHPExcel->getActiveSheet()->mergeCells('M5:N5');
$objPHPExcel->getActiveSheet()->mergeCells('K6:L6');
$objPHPExcel->getActiveSheet()->mergeCells('M6:N6');
$objPHPExcel->getActiveSheet()->mergeCells('B8:M8');

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Marketing Department')
            ->setCellValue('A2', 'ALTURAS GROUP OF COMPANIES')
            ->setCellValue('A3', 'GC E-Requisition')
            ->setCellValue('A5', 'E-Req. No. '.$requisnum)
            ->setCellValue('K5', 'Date Requested: ')
            ->setCellValue('M5', _dateFormat($requisdetails->requis_req))
            ->setCellValue('K6', 'Date Needed: ')
            ->setCellValue('M6', _dateFormat($requisdetails->requis_need))
            ->setCellValue('B8', 'Request for gift cheque printing as per breakdown provided below.');

$objPHPExcel->getActiveSheet()->mergeCells('B9:C9');
$objPHPExcel->getActiveSheet()->mergeCells('D9:E9');
$objPHPExcel->getActiveSheet()->mergeCells('F9:G9');
$objPHPExcel->getActiveSheet()->mergeCells('H9:J9');
$objPHPExcel->getActiveSheet()->mergeCells('K9:M9');

$objPHPExcel->getActiveSheet()->setCellValue('B9','Denomination');
$objPHPExcel->getActiveSheet()->setCellValue('D9','Qty');
$objPHPExcel->getActiveSheet()->setCellValue('F9','Unit');
$objPHPExcel->getActiveSheet()->setCellValue('H9','Barcode No. Start');
$objPHPExcel->getActiveSheet()->setCellValue('K9','Barcode No. End');
$objPHPExcel->getActiveSheet()->getStyle('B9:C9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('D9:E9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('F9:G9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('H9:J9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('K9:M9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B9:C9')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('D9:E9')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('F9:G9')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('H9:J9')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('K9:M9')->applyFromArray($styleArray);

$select = 'denomination.denomination,
            production_request_items.pe_items_quantity,
            production_request_items.pe_items_denomination';
$where = 'pe_items_request_id='.$requisdetails->repuis_pro_id;
$join = 'INNER JOIN 
            denomination
        ON 
            production_request_items.pe_items_denomination = denomination.denom_id';
$limit = '';
$denoms = getAllData($link,'production_request_items',$select,$where,$join,$limit);

$row = 10;
foreach ($denoms as $key) 
{
    $select = 'barcode_no';
    $where =  'denom_id='.$key->pe_items_denomination.'
                AND
              pe_entry_gc='.$requisdetails->repuis_pro_id;
    $join = '';
    $limit = 'ORDER BY 
                barcode_no
             ASC 
             LIMIT 1';
    $start = getSelectedData($link,'gc',$select,$where,$join,$limit);

    $limit = 'ORDER BY 
                barcode_no
             DESC 
             LIMIT 1';
    $end = getSelectedData($link,'gc',$select,$where,$join,$limit);

    $objPHPExcel->getActiveSheet()->mergeCells('B'.$row.':C'.$row);
    $objPHPExcel->getActiveSheet()->mergeCells('D'.$row.':E'.$row);
    $objPHPExcel->getActiveSheet()->mergeCells('F'.$row.':G'.$row);
    $objPHPExcel->getActiveSheet()->mergeCells('H'.$row.':J'.$row);
    $objPHPExcel->getActiveSheet()->mergeCells('K'.$row.':M'.$row);
    $objPHPExcel->getActiveSheet()
        ->getStyle('H'.$row)
        ->getNumberFormat()
        ->setFormatCode(
            PHPExcel_Style_NumberFormat::FORMAT_TEXT
    );
    $objPHPExcel->getActiveSheet()
        ->getStyle('K'.$row)
        ->getNumberFormat()
        ->setFormatCode(
            PHPExcel_Style_NumberFormat::FORMAT_TEXT
    );
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, number_format($key->denomination,0));
    $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $key->pe_items_quantity);
    $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, 'pcs');
    $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, '- '.$start->barcode_no);
    $objPHPExcel->getActiveSheet()->setCellValue('K'.$row, '- '.$end->barcode_no);
    $objPHPExcel->getActiveSheet()->getStyle('B'.$row.':C'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('D'.$row.':E'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('F'.$row.':G'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('H'.$row.':J'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('K'.$row.':M'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('B'.$row.':C'.$row)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('D'.$row.':E'.$row)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('F'.$row.':G'.$row)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('H'.$row.':J'.$row)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('K'.$row.':M'.$row)->applyFromArray($styleArray);

    $row++;
}

$row++;

$objPHPExcel->getActiveSheet()->mergeCells('B'.$row.':M'.$row);
$objPHPExcel->getActiveSheet()->setCellValue('B'.$row,'Supplier Information');
$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->applyFromArray($normalbold);
$objPHPExcel->getActiveSheet()->getStyle('B'.$row.':M'.$row)->applyFromArray($styleArray);

$row++;
$objPHPExcel->getActiveSheet()
    ->getStyle('E'.$row)
    ->getAlignment()
    ->setWrapText(true);
$objPHPExcel->getActiveSheet()->mergeCells('B'.$row.':D'.$row);
$objPHPExcel->getActiveSheet()->setCellValue('B'.$row,'Company Name: ');
$objPHPExcel->getActiveSheet()->setCellValue('E'.$row,$requisdetails->gcs_companyname);
$objPHPExcel->getActiveSheet()->mergeCells('E'.$row.':M'.$row);
$objPHPExcel->getActiveSheet()->getStyle('B'.$row.':D'.$row)->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('E'.$row.':M'.$row)->applyFromArray($styleArray);
$row++;
$objPHPExcel->getActiveSheet()
    ->getStyle('E'.$row)
    ->getAlignment()
    ->setWrapText(true);
$objPHPExcel->getActiveSheet()->mergeCells('B'.$row.':D'.$row);
$objPHPExcel->getActiveSheet()->setCellValue('B'.$row,'Contact Person: ');
$objPHPExcel->getActiveSheet()->setCellValue('E'.$row,$requisdetails->gcs_contactperson);
$objPHPExcel->getActiveSheet()->mergeCells('E'.$row.':M'.$row);
$objPHPExcel->getActiveSheet()->getStyle('B'.$row.':D'.$row)->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('E'.$row.':M'.$row)->applyFromArray($styleArray);
$row++;
$objPHPExcel->getActiveSheet()
    ->getStyle('E'.$row)
    ->getAlignment()
    ->setWrapText(true);
$objPHPExcel->getActiveSheet()->mergeCells('B'.$row.':D'.$row);
$objPHPExcel->getActiveSheet()->setCellValue('B'.$row,'Contact #:');
$objPHPExcel->getActiveSheet()->setCellValue('E'.$row,$requisdetails->gcs_contactnumber);
$objPHPExcel->getActiveSheet()->mergeCells('E'.$row.':M'.$row);
$objPHPExcel->getActiveSheet()->getStyle('B'.$row.':D'.$row)->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('E'.$row.':M'.$row)->applyFromArray($styleArray);
$row++;
$objPHPExcel->getActiveSheet()
    ->getStyle('E'.$row)
    ->getAlignment()
    ->setWrapText(true);
$objPHPExcel->getActiveSheet()->mergeCells('B'.$row.':D'.$row);
$objPHPExcel->getActiveSheet()->setCellValue('B'.$row,'Address:');
$objPHPExcel->getActiveSheet()->setCellValue('E'.$row,$requisdetails->gcs_address);
$objPHPExcel->getActiveSheet()->mergeCells('E'.$row.':M'.$row);
$objPHPExcel->getActiveSheet()->getStyle('B'.$row.':D'.$row)->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('E'.$row.':M'.$row)->applyFromArray($styleArray);

$row = $row + 3;

$objPHPExcel->getActiveSheet()->mergeCells('A'.$row.':B'.$row);
$objPHPExcel->getActiveSheet()->mergeCells('J'.$row.':K'.$row);
$objPHPExcel->getActiveSheet()->setCellValue('A'.$row,'Checked By:');
$objPHPExcel->getActiveSheet()->setCellValue('J'.$row,'Approved By:');

$row = $row +2;
$objPHPExcel->getActiveSheet()->mergeCells('B'.$row.':D'.$row);
$objPHPExcel->getActiveSheet()->mergeCells('K'.$row.':M'.$row);
$objPHPExcel->getActiveSheet()->setCellValue('B'.$row,ucwords($requisdetails->requis_checked));
$objPHPExcel->getActiveSheet()->setCellValue('K'.$row,ucwords($requisdetails->firstname.' '.$requisdetails->lastname));
$objPHPExcel->getActiveSheet()->getStyle('B'.$row.':D'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->applyFromArray($normalbold);
$objPHPExcel->getActiveSheet()->getStyle('B'.$row.':D'.$row)
    ->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle('K'.$row.':M'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('K'.$row)->applyFromArray($normalbold);
$objPHPExcel->getActiveSheet()->getStyle('K'.$row.':M'.$row)
    ->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

$row++;
$objPHPExcel->getActiveSheet()->mergeCells('B'.$row.':D'.$row);
$objPHPExcel->getActiveSheet()->mergeCells('K'.$row.':M'.$row);
$objPHPExcel->getActiveSheet()->setCellValue('B'.$row,'(Signature over Printed Name)');
$objPHPExcel->getActiveSheet()->setCellValue('K'.$row,'(Signature over Printed Name)');
$objPHPExcel->getActiveSheet()->getStyle('B'.$row.':D'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('K'.$row.':M'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->applyFromArray($signature);
$objPHPExcel->getActiveSheet()->getStyle('K'.$row)->applyFromArray($signature);

// $row = $row +2;

// $objPHPExcel->getActiveSheet()->mergeCells('J'.$row.':K'.$row);
// $objPHPExcel->getActiveSheet()->setCellValue('J'.$row,'Approved By:');
// $row = $row + 2;
// $objPHPExcel->getActiveSheet()->mergeCells('K'.$row.':M'.$row);
// $objPHPExcel->getActiveSheet()->setCellValue('K'.$row,$requisdetails->requis_approved);
// $objPHPExcel->getActiveSheet()->getStyle('K'.$row.':M'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
// $objPHPExcel->getActiveSheet()->getStyle('K'.$row)->applyFromArray($normalbold);
// $objPHPExcel->getActiveSheet()->getStyle('K'.$row.':M'.$row)
//     ->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
// $row++;
// $objPHPExcel->getActiveSheet()->mergeCells('K'.$row.':M'.$row);
// $objPHPExcel->getActiveSheet()->setCellValue('K'.$row,'(Signature over Printed Name)');
// $objPHPExcel->getActiveSheet()->getStyle('K'.$row.':M'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
// $objPHPExcel->getActiveSheet()->getStyle('K'.$row)->applyFromArray($signature);

// ////
// $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

// $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($header);           

// $objPHPExcel->getActiveSheet()->getStyle('A2:N2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
// $objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($header);

// $objPHPExcel->getActiveSheet()->getStyle('A3:N3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
// $objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($subheader);

// $objPHPExcel->getActiveSheet()->getStyle('B8')->applyFromArray($normalbold);

// $objPHPExcel->getActiveSheet()->getStyle('B8:M8')->applyFromArray($styleArray);

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

header('Content-Type: application/openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$name.'.csv"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
