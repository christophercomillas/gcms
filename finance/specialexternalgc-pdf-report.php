<?php
	require('reports.php');

    if(isset($_GET['startDate']) && isset($_GET['endDate']))
    {
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
    }
    else
    {
        die("data type error!");
    }

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

	$pdf = new REPORTS();
	
	$pdf->AliasNbPages();
	$pdf->AddPage("P","Letter");
    $pdf->SetFont('Arial','B',16);

    $pdf->setReportName('Special External GC Report');
    
	$text = 'Period: '.$periodcover;
    $pdf->setFooterText($text);
    $period = "Period: ";
    $pdf->docheaderSPGC($periodcover);
    $pdf->subheaderSPGC($todays_date);

	$table = 'special_external_gcrequest_emp_assign';
	$select = "IFNULL(SUM(special_external_gcrequest_emp_assign.spexgcemp_denom),0.00) as totdenom,
        special_external_gcrequest.spexgc_num,    
        DATE_FORMAT(special_external_gcrequest.spexgc_datereq, '%m/%d/%Y') as datereq,
        DATE_FORMAT(approved_request.reqap_date, '%m/%d/%Y') as daterel,
        CONCAT(reqby.firstname,' ',reqby.lastname) as trby,
        special_external_customer.spcus_companyname";
	$where = "approved_request.reqap_approvedtype = 'special external releasing' 
        AND
            (DATE_FORMAT(special_external_gcrequest.spexgc_datereq,'%Y-%m-%d') >= '{$startDate}'
        AND
            DATE_FORMAT(special_external_gcrequest.spexgc_datereq,'%Y-%m-%d') <= '{$endDate}')
        GROUP BY
            special_external_gcrequest.spexgc_num
        ORDER BY
            special_external_gcrequest.spexgc_datereq
        ASC";
	$join = 'INNER JOIN
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
            special_external_customer.spcus_id = special_external_gcrequest.spexgc_company';
	$limit = '';

    $datacus = getAllData($link,$table,$select,$where,$join,$limit);

	$table = 'special_external_gcrequest_emp_assign';
	$select = "special_external_gcrequest_emp_assign.spexgcemp_denom,
        special_external_gcrequest_emp_assign.spexgcemp_fname,
        special_external_gcrequest_emp_assign.spexgcemp_lname,
        special_external_gcrequest_emp_assign.spexgcemp_mname,
        special_external_gcrequest_emp_assign.spexgcemp_extname,
        special_external_gcrequest_emp_assign.spexgcemp_barcode,
        special_external_gcrequest.spexgc_num,    
        DATE_FORMAT(special_external_gcrequest.spexgc_datereq, '%m/%d/%Y') as datereq,
        DATE_FORMAT(approved_request.reqap_date, '%m/%d/%Y') as daterel";
	$where = "approved_request.reqap_approvedtype = 'special external releasing' 
        AND
            (DATE_FORMAT(special_external_gcrequest.spexgc_datereq,'%Y-%m-%d') >= '{$startDate}'
        AND
            DATE_FORMAT(special_external_gcrequest.spexgc_datereq,'%Y-%m-%d') <= '{$endDate}')
        ORDER BY
            special_external_gcrequest_emp_assign.spexgcemp_barcode
        ASC";
	$join = 'INNER JOIN
            special_external_gcrequest
        ON
            special_external_gcrequest.spexgc_id = special_external_gcrequest_emp_assign.spexgcemp_trid
        INNER JOIN
            approved_request
        ON
            approved_request.reqap_trid = special_external_gcrequest.spexgc_id';
	$limit = '';

    $databar = getAllData($link,$table,$select,$where,$join,$limit);

    //var_dump($data);
  
    $pdf->dataSPGC($datacus,$databar);
    
    //$pdf->Output();
	$pdf->Output('../reports/externalReport/gcrspecialpdf.pdf','F');
?>
<script>
	window.location = "<?php echo 'index.php?specialexternalreport=1'; ?>";
</script>