<?php

session_start();

if(isset($_GET['startDate']) && isset($_GET['endDate']))
{
    $startDate = $_GET['startDate'];
    $endDate = $_GET['endDate'];
}
else
{
    die("data type error!");
}

require_once  dirname(__FILE__) .'/../config.php';

$name = '';
$periodcover = "";

//convert to mysql date
$sDate = explode("/",$startDate);
$startDate = $sDate[2].'-'.$sDate[0].'-'.$sDate[1];
$eDate = explode("/",$endDate);
$endDate = $eDate[2].'-'.$eDate[0].'-'.$eDate[1];

if($startDate!=$endDate)
{
    // $name1 = str_replace("/","-",$startDate);
    // $name2 = str_replace("/","-",$endDate);
    // $name = $name1.'to'.$name2;

    // $psDate = explode("/",$startDate);
    // $peDate = explode("/",$endDate);

    $name = $startDate.'to'.$endDate;

    // $coverdate = date("F d, Y", strtotime($startDate));
    // $periodcover = "PERIOD COVER: ".$coverdate;

    if($sDate[2]==$eDate[2] && $sDate[0] == $eDate[0])
    {        
        $periodcover = date("F", strtotime($startDate));
        $periodcover.= ' '.$sDate[1].'-'.$eDate[1].', '.$sDate[2];
    }
    else 
    {
        $periodcover = date("F d, Y", strtotime($startDate)).' - '.date("F d, Y", strtotime($endDate));
    }
}
else 
{
    $name = $startDate;
    $periodcover = date("F d, Y", strtotime($startDate));
    // $coverdate = date("MM d, Y", strtotime($startDate));
    // $periodcover = "PERIOD COVER: ".$coverdate;
}
// echo $periodcover;
// //echo $name;
// // echo $periodcover;
// // echo date("F", strtotime('2016-05-17')); //May
// exit();


//set 2nd worksheet datecover

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

function _getGCByDate($startDate,$endDate,$link)
{
    $query = $link->query(
        "SELECT 
            special_external_gcrequest_emp_assign.spexgcemp_denom,
            special_external_gcrequest_emp_assign.spexgcemp_fname,
            special_external_gcrequest_emp_assign.spexgcemp_lname,
            special_external_gcrequest_emp_assign.spexgcemp_mname,
            special_external_gcrequest_emp_assign.spexgcemp_extname,
            special_external_gcrequest_emp_assign.spexgcemp_barcode,
            special_external_gcrequest.spexgc_num,    
            DATE_FORMAT(special_external_gcrequest.spexgc_datereq, '%M %d %Y') as datereq,
            DATE_FORMAT(approved_request.reqap_date, '%M %d %Y') as daterel
        FROM
            special_external_gcrequest_emp_assign
        INNER JOIN
            special_external_gcrequest
        ON
            special_external_gcrequest.spexgc_id = special_external_gcrequest_emp_assign.spexgcemp_trid
        INNER JOIN
            approved_request
        ON
            approved_request.reqap_trid = special_external_gcrequest.spexgc_id
        WHERE
            approved_request.reqap_approvedtype = 'special external releasing' 
        AND
            (DATE_FORMAT(special_external_gcrequest.spexgc_datereq,'%Y-%m-%d') >= '{$startDate}'
        AND
            DATE_FORMAT(special_external_gcrequest.spexgc_datereq,'%Y-%m-%d') <= '{$endDate}')
        ORDER BY
            special_external_gcrequest.spexgc_datereq
        ASC
    ");

    $sql = "SELECT 
	special_external_gcrequest.spexgc_num,
    CONCAT(reqby.firstname,' ',reqby.lastname) as reqby
FROM
	special_external_gcrequest
INNER JOIN
	approved_request
ON
	approved_request.reqap_trid = special_external_gcrequest.spexgc_id
INNER JOIN
	users as reqby
ON
	reqby.user_id = special_external_gcrequest.spexgc_reqby
WHERE
	approved_request.reqap_approvedtype = 'special external releasing' 
AND
	(DATE_FORMAT(special_external_gcrequest.spexgc_datereq,'%Y-%m-%d') >= '2017-10-10'
AND
	DATE_FORMAT(special_external_gcrequest.spexgc_datereq,'%Y-%m-%d') <= '2017-10-10')
";
}

// Create new PHPExcel object
$object = new PHPExcel();

// Set document properties
$object->getProperties()->setCreator($_SESSION['gc_fullname'])
        ->setLastModifiedBy($_SESSION['gc_fullname'])
        ->setTitle("GC Report")
        ->setSubject("GC Report")
        ->setDescription("GC Report")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("GC Report");

$object->getActiveSheet()->getColumnDimension('A')->setWidth(20);   //TRANSACTION DATE
$object->getActiveSheet()->getColumnDimension('B')->setWidth(16);   //BARCODE   
$object->getActiveSheet()->getColumnDimension('C')->setWidth(18);   //DENOMINATION
$object->getActiveSheet()->getColumnDimension('D')->setWidth(40);   //CUSTOMER
$object->getActiveSheet()->getColumnDimension('E')->setWidth(20);   //RELEASED NUMBER
$object->getActiveSheet()->getColumnDimension('F')->setWidth(20);   //DATE RELEASED

$table_columns = array(
    "TRANSACTION DATE", 
    "BARCODE", 
    "DENOMINATION", 
    "CUSTOMER", 
    "RELEASED #",
    "DATE RELEASED"
);

$column = 0;

foreach($table_columns as $field)
{
    $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
    $column++;
}
$object->getActiveSheet()->getStyle("A1:F1")->getFont()->setBold( true );
$object->getActiveSheet()->freezePane('A2');

$excel_row = 2;

$query = $link->query(
    "SELECT 
        special_external_gcrequest_emp_assign.spexgcemp_denom,
        special_external_gcrequest_emp_assign.spexgcemp_fname,
        special_external_gcrequest_emp_assign.spexgcemp_lname,
        special_external_gcrequest_emp_assign.spexgcemp_mname,
        special_external_gcrequest_emp_assign.spexgcemp_extname,
        special_external_gcrequest_emp_assign.spexgcemp_barcode,
        special_external_gcrequest.spexgc_num,    
        DATE_FORMAT(special_external_gcrequest.spexgc_datereq, '%m/%d/%Y') as datereq,
        DATE_FORMAT(approved_request.reqap_date, '%m/%d/%Y') as daterel
    FROM
        special_external_gcrequest_emp_assign
    INNER JOIN
        special_external_gcrequest
    ON
        special_external_gcrequest.spexgc_id = special_external_gcrequest_emp_assign.spexgcemp_trid
    INNER JOIN
        approved_request
    ON
        approved_request.reqap_trid = special_external_gcrequest.spexgc_id
    WHERE
        approved_request.reqap_approvedtype = 'special external releasing' 
    AND
        (DATE_FORMAT(special_external_gcrequest.spexgc_datereq,'%Y-%m-%d') >= '{$startDate}'
    AND
        DATE_FORMAT(special_external_gcrequest.spexgc_datereq,'%Y-%m-%d') <= '{$endDate}')
    ORDER BY
        special_external_gcrequest_emp_assign.spexgcemp_barcode
    ASC
");

if($query->num_rows > 0)
{
    while ($row = $query->fetch_object()) 
    {
        $customer = $row->spexgcemp_lname.', '.$row->spexgcemp_fname;
        if(trim($row->spexgcemp_mname)!='')
        {
            $customer.= $row->spexgcemp_mname;
        }

        $customer = html_entity_decode($customer,ENT_QUOTES,'UTF-8');

        $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, PHPExcel_Shared_Date::PHPToExcel( strtotime($row->datereq)));
        $object->getActiveSheet()->getStyle('A'.$excel_row)->getNumberFormat()->setFormatCode('mm/dd/yyyy');    

        $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->spexgcemp_barcode);
        $object->getActiveSheet()->getStyle('B'.$excel_row)->getNumberFormat()->setFormatCode(0); 

        $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->spexgcemp_denom);
        $object->getActiveSheet()->getStyle('C'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00'); 

        $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, strtoupper($customer));

        $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->spexgc_num);
        $object->getActiveSheet()->getStyle('E'.$excel_row)->getNumberFormat()->setFormatCode('#,##0'); 

        $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, PHPExcel_Shared_Date::PHPToExcel( strtotime($row->daterel)));    
        $object->getActiveSheet()->getStyle('F'.$excel_row)->getNumberFormat()->setFormatCode('mm/dd/yyyy'); 
        $excel_row++;
        
    }

    $object->getActiveSheet()->setTitle("PER BARCODE");

    $objWorkSheet = $object->createSheet(1);
    $object->setActiveSheetIndex(1);

    $object->getActiveSheet()->getColumnDimension('A')->setWidth(20);   
    $object->getActiveSheet()->getColumnDimension('B')->setWidth(40);    
    $object->getActiveSheet()->getColumnDimension('C')->setWidth(20);   
    $object->getActiveSheet()->getColumnDimension('D')->setWidth(20);   
    $object->getActiveSheet()->getColumnDimension('E')->setWidth(34);   
    $object->getActiveSheet()->getColumnDimension('F')->setWidth(20);   
    $object->getActiveSheet()->getColumnDimension('G')->setWidth(18);    

    $object->getActiveSheet()->mergeCells('A1:F1')->getStyle("A1:F1")->getFont()->setBold( true );
    $object->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $object->getActiveSheet()->mergeCells('A2:F2')->getStyle("A2:F2")->getFont()->setBold( true );
    $object->getActiveSheet()->getStyle('A2:F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $object->getActiveSheet()->mergeCells('A3:F3')->getStyle("A3:F3")->getFont()->setBold( true );
    $object->getActiveSheet()->getStyle('A3:F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $object->getActiveSheet()->mergeCells('A4:F4')->getStyle("A4:F4")->getFont()->setBold( true );
    $object->getActiveSheet()->getStyle('A4:F4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $object->setActiveSheetIndex(1)
        ->setCellValue('A1','ALTURAS GROUP OF COMPANIES')
        ->setCellValue('A2','HEAD OFFICE FINANCE DEPARTMENT')
        ->setCellValue('A3','SPECIAL EXTERNAL GC REPORT')
        ->setCellValue('A4',$periodcover);

    $table_columns = array("DATE", "COMPANY","RELEASING #", "TOTAL DENOM");

    $excel_row = 6;
    $column = 0;
    foreach($table_columns as $field)
    {
        $object->getActiveSheet()->setCellValueByColumnAndRow($column, $excel_row, $field);
        $column++;
    }
    $object->getActiveSheet()->getStyle("A6:H6")->getFont()->setBold( true );

    $excel_row++;

    $query_cus = $link->query(
        "SELECT 
            IFNULL(SUM(special_external_gcrequest_emp_assign.spexgcemp_denom),0.00) as totdenom,
            special_external_gcrequest.spexgc_num,    
            DATE_FORMAT(special_external_gcrequest.spexgc_datereq, '%m/%d/%Y') as datereq,
            DATE_FORMAT(approved_request.reqap_date, '%m/%d/%Y') as daterel,
            CONCAT(reqby.firstname,' ',reqby.lastname) as trby,
            special_external_customer.spcus_companyname
            
        FROM
            special_external_gcrequest_emp_assign
        INNER JOIN
            special_external_gcrequest
        ON
            special_external_gcrequest.spexgc_id = special_external_gcrequest_emp_assign.spexgcemp_trid
        INNER JOIN
            approved_request
        ON
            approved_request.reqap_trid = special_external_gcrequest.spexgc_id
        INNER JOIN
            users as reqby
        ON
            reqby.user_id = special_external_gcrequest.spexgc_reqby
        INNER JOIN
            special_external_customer
        ON
            special_external_customer.spcus_id = special_external_gcrequest.spexgc_company
        WHERE
            approved_request.reqap_approvedtype = 'special external releasing' 
        AND
            (DATE_FORMAT(special_external_gcrequest.spexgc_datereq,'%Y-%m-%d') >= '{$startDate}'
        AND
            DATE_FORMAT(special_external_gcrequest.spexgc_datereq,'%Y-%m-%d') <= '{$endDate}')
        GROUP BY
            special_external_gcrequest.spexgc_num
        ORDER BY
            special_external_gcrequest.spexgc_datereq
        ASC
    ");

    while ($row = $query_cus->fetch_object()) 
    {
        $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, PHPExcel_Shared_Date::PHPToExcel( strtotime($row->datereq)));
        $object->getActiveSheet()->getStyle('A'.$excel_row)->getNumberFormat()->setFormatCode('mm/dd/yyyy'); 
        
        $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, strtoupper($row->spcus_companyname));

        $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->spexgc_num);
        $object->getActiveSheet()->getStyle('C'.$excel_row)->getNumberFormat()->setFormatCode('#,##0'); 

        $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->totdenom);
        $object->getActiveSheet()->getStyle('D'.$excel_row)->getNumberFormat()->setFormatCode('#,##0.00'); 

        $excel_row++;
    }        
    $object->getActiveSheet()->setTitle("PER CUSTOMER");
}
$object->setActiveSheetIndex(0);


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