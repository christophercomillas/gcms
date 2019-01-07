<?php 
	require('reports.php');

    if(isset($_GET['gcsales']) &&
    isset($_GET['reval']) &&
    isset($_GET['refund']) &&
    isset($_GET['trans'])):
        $flag = 0;
        $gcsales = $_GET['gcsales'];
        $reval = $_GET['reval'];
        $refund = $_GET['refund'];
        $transdate = $_GET['trans'];
        $datestart = $_GET['dstart'];
        $dateend = $_GET['dend'];

        $select = 'transaction_stores.trans_number,
        ledger_store.sledger_desc,
        transaction_stores.trans_sid,
        transaction_stores.trans_type,
        transaction_stores.trans_datetime,
        transaction_stores.trans_number';

        $where = "transaction_stores.trans_store='".$_SESSION['gc_store']."'";

        $join = 'INNER JOIN 
              ledger_store 
            ON 
              ledger_store.sledger_ref = transaction_stores.trans_sid';

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
                $where.=" OR transaction_stores.trans_type='5'";
            }
            else
            {
                $where.=" AND ( transaction_stores.trans_type='5'";
            }
        }

        $where.=") AND ";

        if($transdate=='today')
        {
            $where.="DATE(transaction_stores.trans_datetime) = CURDATE()";
        }
        elseif($transdate=='yesterday')
        {
            $where.="DATE(transaction_stores.trans_datetime) = CURDATE() - INTERVAL 1 DAY";
        }
        elseif ($transdate=='thisweek') 
        {
            $where.="WEEKOFYEAR(transaction_stores.trans_datetime) = WEEKOFYEAR(NOW())";
        }
        elseif ($transdate=='curmonth')
        {
            $where.="MONTH(transaction_stores.trans_datetime) = MONTH(NOW()) AND YEAR(transaction_stores.trans_datetime) = YEAR(NOW())";
        }
        elseif ($transdate=='range') 
        {
            $where.="DATE(transaction_stores.trans_datetime) >= '"._dateFormatoSql($datestart)."'
          AND  DATE(transaction_stores.trans_datetime) <= '"._dateFormatoSql($dateend)."'";
        } 
        else 
        {
            $where.=' 1';
        }
        //echo $where;
        $limit = 'GROUP BY transaction_stores.trans_sid ORDER BY transaction_stores.trans_sid ASC';
        $gc = getAllData($link,'transaction_stores',$select,$where,$join,$limit);


    else:
    	echo 'wala';
    endif;                           

	$pdf = new REPORTS();
	$pdf->AliasNbPages();
	$pdf->AddPage("P","Letter");
	$pdf->SetFont('Arial','B',16);

	//$pdf->setFooter(true);
	//var_dump($gc);
    $pdf->setFooter(true);
    $pdf->setFooterText("GC Sales Report");
	$pdf->setReportName('Gift Check Sales Report');
	$pdf->docHeaderReport($link,$datestart,$dateend);
	$pdf->Ln();
	$pdf->subheaderSalesReport($todays_date);
	$pdf->showData($link,$gc);
    //$pdf->setFooter(true);

	//$pdf->Output();	

    //var_dump($_SESSION);

	$pdf->Output('../reports/gcsales/gcsales'.$_SESSION['gc_store'].'.pdf','F');

?>
<script>
	window.location = "<?php echo 'index.php?gcreport='.$_SESSION['gc_store']; ?>";
</script>

