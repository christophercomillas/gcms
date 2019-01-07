<?php 
    session_start();
    include '../function.php';
    require 'header.php';

    $stores = getStores($link);
?>

<?php require '../menu.php'; ?>

    <div class="main fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="col-md-12 pad0">
                <div class="panel with-nav-tabs panel-info">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active" style="font-weight:bold">
                                <a href="#tab1default" data-toggle="tab">GC Reports</a>
                            </li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab1default">
                                <div class="row form-container">
                                    <div class="col-xs-12 cardsalesload">
                                        <?php 
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
                                        ?>

                                            <table class="table" id="stores">
                                                <thead>
                                                    <th>Transaction Date</th>
                                                    <th>Transaction Number</th>
                                                    <th>Transaction Type</th>
                                                    </thead>
                                                <tbody>
                                                    <?php foreach ($gc as $g): ?>
                                                    <tr>
                                                        <td><?php echo _dateFormat($g->trans_datetime); ?></td>
                                                        <td><?php echo $g->trans_number; ?></td>
                                                        <td><?php echo $g->trans_sid; ?></td>
                                                    </tr>                                
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>

                                        <?php else: ?>
                                            <form class="form-horizontal" id="salesreport">
                                                <div class="col-xs-5">
                                                    <fieldset>
                                                        <legend class="mid">Report Type:</legend>       
                                                        <div class="form-group">      
                                                            <div class="col-xs-offset-3 col-xs-9">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">
                                                                        <input type="checkbox" class="rad" id="reval" name="reportype[]" value="gcsales" checked>
                                                                    </span>
                                                                    <input type="text" class="form-control" disabled="" value="GC Sales">
                                                                </div><!-- /input-group -->  
                                                            </div>            
                                                        </div> 
                                                        <div class="form-group">
                                                            <div class="col-xs-offset-3 col-xs-9">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">
                                                                        <input type="checkbox" class="rad" id="reval" name="reportype[]" value="reval" disabled="disabled">
                                                                    </span>
                                                                    <input type="text" class="form-control" disabled="" value="GC Revalidation">
                                                                </div><!-- /input-group -->  
                                                            </div>            
                                                        </div>  
                                                        <div class="form-group">
                                                            <div class="col-xs-offset-3 col-xs-9">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">
                                                                        <input type="checkbox" class="rad" id="refund" name="reportype[]" value="refund" disabled="disabled">
                                                                    </span>
                                                                    <input type="text" class="form-control" disabled="" value="GC Refund">
                                                                </div><!-- /input-group -->  
                                                            </div>            
                                                        </div> 
                                                    </fieldset>
                                                </div>
                                                <div class="col-xs-5">
                                                    <fieldset>   
                                                        <legend class="mid">Transaction Date</legend> 
                                                            <div class="form-group">
                                                                <div class="col-xs-offset-3 col-xs-9">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">
                                                                            <input type="radio" class="rad" id="datetrans" name="datetrans" value="today" onclick="salesReportType(this.value);" required disabled="disabled">
                                                                        </span>
                                                                        <input type="text" class="form-control" value="Today" disabled="disabled">
                                                                    </div><!-- /input-group -->  
                                                                </div>            
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-xs-offset-3 col-xs-9">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">
                                                                            <input type="radio" class="rad" id="datetrans" name="datetrans" value="yesterday" onclick="salesReportType(this.value);" required disabled="disabled">
                                                                        </span>
                                                                        <input type="text" class="form-control" disabled="disabled" value="Yesterday">
                                                                    </div><!-- /input-group -->  
                                                                </div>            
                                                            </div>  
                                                            <div class="form-group">
                                                                <div class="col-xs-offset-3 col-xs-9">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">
                                                                            <input type="radio" class="rad" id="datetrans" name="datetrans" value="thisweek" onclick="salesReportType(this.value);" required disabled="disabled">
                                                                        </span>
                                                                        <input type="text" class="form-control" disabled="disabled" value="This week">
                                                                    </div><!-- /input-group -->  
                                                                </div>            
                                                            </div> 
                                                            <div class="form-group">
                                                                <div class="col-xs-offset-3 col-xs-9">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">
                                                                            <input type="radio" class="rad" id="datetrans" name="datetrans" value="curmonth" onclick="salesReportType(this.value);" required disabled="disabled">
                                                                        </span>
                                                                        <input type="text" class="form-control" disabled="disabled" value="Current Month">
                                                                    </div><!-- /input-group -->  
                                                                </div>            
                                                            </div>  
                                                            <div class="form-group">
                                                                <div class="col-xs-offset-3 col-xs-9">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">
                                                                            <input type="radio" class="rad" id="datetrans" name="datetrans" value="all" onclick="salesReportType(this.value);" required disabled="disabled">
                                                                        </span>
                                                                        <input type="text" class="form-control" disabled="disabled" value="All Transactions">
                                                                    </div><!-- /input-group -->  
                                                                </div>            
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-xs-offset-3 col-xs-9">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">
                                                                            <input type="radio" class="rad" id="datetrans" name="datetrans" value="range" onclick="salesReportType(this.value);" required>
                                                                        </span>
                                                                        <input type="text" class="form-control" disabled="" value="Date Range">
                                                                    </div><!-- /input-group -->  
                                                                </div>            
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-sm-offset-7 col-sm-5 control-label" style="text-align:left">mm/dd/yyyy</label>

                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-sm-offset-2 col-sm-5 control-label">Date Start</label>
                                                                <div class="col-sm-5">
                                                                    <input type="text" class="form-control formbot input-sm" id="dstart" name="dstart" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-sm-offset-2 col-sm-5 control-label">Date End</label>
                                                                <div class="col-sm-5">
                                                                    <input type="text" class="form-control formbot input-sm" id="dend" name="dend" required>
                                                                </div>
                                                            </div>
                                                    </fieldset>
                                                    <div class="response">
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-offset-7 col-sm-5">
                                                            <button type="submit" class="btn btn-block btn-primary"><i class="fa fa-download"></i>  Generate</button>
                                                        </div>
                                                    </div>            
                                                </div>  
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/store.js"></script>
<script type="text/javascript">
</script>
<?php include 'footer.php' ?>               