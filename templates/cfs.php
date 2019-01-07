<script src="../assets/js/funct.js"></script>
<?php
session_start();
include '../function.php';

if(isset($_GET['page']))
{
    $page = $_GET['page'];

    if($page=='saleslisttreasury')
    {
        _saleslisttreasury($link);
    }
    elseif ($page=='viewtressales') 
    {

        if(!isset($_GET['trid']) && $_GET['trid']!='')
        {
            exit();
        }

        if(!isset($_GET['pag']) && $_GET['pag']!='')
        {
            exit();
        }

        $trid = $_GET['trid'];
        $page = $_GET['pag'];

        _viewtressales($link,$trid,$page);
    }
    elseif ($page=='viewgctransact') 
    {

        if(!isset($_GET['barcode']) && $_GET['barcode']!='')
        {
            exit();
        }

        if(!isset($_GET['trid']) && $_GET['trid']!='')
        {
            exit();
        }

        if(!isset($_GET['url']) && $_GET['url']!='')
        {
            exit();
        }

        if(!isset($_GET['pag']) && $_GET['pag']!='')
        {
            exit();
        }

        $barcode = $_GET['barcode'];
        $trid = $_GET['trid'];
        $url = $_GET['url'];
        $page = $_GET['pag'];


        _viewgctransact($link,$barcode,$trid,$url,$page);
    }
    elseif ($page=='viewstoresales') 
    {
        _viewstoresales($link);
    }
    elseif ($page=='viewgcsalespertransac') 
    {

        if(!isset($_GET['trid']) && $_GET['trid']!='')
        {
            exit();
        }

        if(!isset($_GET['pag']) && $_GET['pag']!='')
        {
            exit();
        }



        $trid = $_GET['trid'];
        $page = $_GET['pag'];

        _viewgcsalespertransac($link,$trid,$page);
    }
    elseif ($page=='verifiedgcperstore') 
    {
        if(!isset($_GET['id']) && $_GET['id']!='')
        {
            exit();
        }

        if(!isset($_GET['pag']) && $_GET['pag']!='')
        {
            exit();
        }

        $id = $_GET['id'];
        $page = $_GET['pag'];
        _verifiedgcperstore($link,$id,$page);
    }
    elseif ($page=='viewgctransactstores') 
    {

        if(!isset($_GET['barcode']) && $_GET['barcode']!='')
        {
            exit();
        }

        if(!isset($_GET['trid']) && $_GET['trid']!='')
        {
            exit();
        }

        if(!isset($_GET['url']) && $_GET['url']!='')
        {
            exit();
        }

        if(!isset($_GET['pag']) && $_GET['pag']!='')
        {
            exit();
        }

        if(!isset($_GET['store']) && $_GET['store']!='')
        {
            exit();
        }

        $barcode = $_GET['barcode'];
        $trid = $_GET['trid'];
        $url = $_GET['url'];
        $page = $_GET['pag'];
        $store = $_GET['store'];

        _viewgctransactstores($link,$barcode,$trid,$url,$page,$store);

    }
    elseif ($page=='exportdata') 
    {
        _exportdata($link);
    }
    else 
    {
        //last
        echo 'Something went wrong.';
    }   
}

function _exportdata($link)
{
    ?>
        <div class="row form-container">
            <div class="col-md-12">
                <div class="panel with-nav-tabs panel-info">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active" style="font-weight:bold">
                                <a href="#tab1default" data-toggle="tab">Export Data</a>
                            </li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <form method="POST" id="exportdatacfs" action="../ajax.php?action=exportdatacfs" >
                                <div class="tab-pane fade in active" id="tab1default">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="nobot">Data Type</label>  
                                                <select class="form form-control inptxt input-sm bot-6" name="dtype" id="dtype" required>
                                                    <option value="">- Select -</option>
                                                    <option value="all">Treasury & Stores Sales</option>
                                                    <option value="ts">Treasury Sales</option>
                                                    <option value="ss">Store Sales</option>
                                                    <option value="vgc">Verified GC</option>
                                                </select> 
                                                <!-- <input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="">  -->
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="nobot">Store / Customer</label>  
                                                <select class="form form-control inptxt input-sm bot-6" name="stselect" id="stselect" disabled="true" style="color:gainsboro;">
                                                    <option value="">- Select -</option>
                                                </select> 
                                                <!-- <input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="">  -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="nobot">Month</label>  
                                            <select name="month" id="month" size='1' class="form form-control inptxt input-sm bot-6" required>
                                                <option value=''>- Select -</option>
                                                <?php
                                                for ($i = 0; $i < 12; $i++) {
                                                    $time = strtotime(sprintf('%d months', $i));   
                                                    $label = date('F', $time);   
                                                    $value = date('n', $time);
                                                    echo "<option value='$value'>$label</option>";
                                                }
                                                ?>
                                            </select>                                            
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="nobot">Year</label>  
                                            <select name="year" id="year" size='1' class="form form-control inptxt input-sm bot-6" required>
                                                <option value=''>- Select -</option>
                                                <option value='2017'>2017</option>
                                                <option value='2018'>2018</option>
                                            </select>                                           
                                        </div>
                                    </div>
                                </div>
                                <!---
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="nobot">Date Start</label>  
                                            <input type="text" class="form form-control inptxt input-sm bot-6" name="start" id="start" style="text-align:right">
                                            
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="nobot">Date End</label>  
                                            <input type="text" class="form form-control inptxt input-sm bot-6" name="end" id="end" style="text-align:right">
                                            
                                        </div>
                                        <div class="response">

                                        </div>
                                    </div>
                                </div>
                                -->
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="response">
                                        </div>                                        
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <div class="col-sm-offset-5 col-sm-7">
                                                <button type="submit" class="btn btn-block btn-primary" id="btn"> <span class="glyphicon glyphicon-share" aria-hidden="true"></span> Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="tab-pane fade" id="tab2default">Default 2</div> -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $("#start, #end").inputmask("m/d/y",{ "placeholder": "mm/dd/yyyy" });
            $.extend( $.fn.dataTableExt.oStdClasses, {    
                "sLengthSelect": "selectsup"
            });
            $('#eodins').dataTable( {
                "pagingType": "full_numbers",
                "ordering": false,
                "processing": true,
                "bProcessing":true
            });

            $('.form-group').on('change','select#dtype',function(){

                if($(this).val().trim()=='' || $(this).val().trim()=='all')
                {
                    $('#stselect').prop("disabled",true);
                    $("#stselect").css("color", "gainsboro");
                    $('#stselect')
                    .empty()
                    .append('<option selected="selected" value="">- Select -</option>');
                }
                else 
                {
                    $('#stselect').prop("disabled",false);
                    $("#stselect").css("color", "");

                    if($(this).val().trim()=='ts')
                    {
                        $.ajax({
                            url:'../ajax.php?action=getAllCustomer',
                            beforeSend:function(){

                            },
                            success:function(data){         
                                console.log(data);
                                var data = JSON.parse(data);
                                if(data['st'])
                                {
                                    $('#stselect')
                                    .empty();

                                    var options = "";
                                    options+='<option value="">- Select -</option>';
                                    for (var i = 0; i < data['customer'].length; i++) {
                                     options += '<option value="'+data['customer'][i]['val']+'">' + data['customer'][i]['name'] + '</option>';
                                    }                                    

                                    $("#stselect").html(options);
                                } 
                                else 
                                {

                                }
                            }
                        });
                    }
                    else if($(this).val().trim()=='ss' || $(this).val().trim()=='vgc')
                    {
                        $.ajax({
                            url:'../ajax.php?action=getAllStores',
                            beforeSend:function(){

                            },
                            success:function(data){         
                                console.log(data);
                                var data = JSON.parse(data);
                                if(data['st'])
                                {
                                    $('#stselect')
                                    .empty();

                                    var options = "";
                                    options+='<option value="">- Select -</option>';
                                    for (var i = 0; i < data['customer'].length; i++) {
                                     options += '<option value="'+data['customer'][i]['val']+'">' + data['customer'][i]['name'] + '</option>';
                                    }                                    

                                    $("#stselect").html(options);
                                } 
                                else 
                                {
                                    
                                }
                            }
                        });
                    }
                }
            });

            $('.form-container').on('submit','form#exportdatacfs',function(event){
                event.preventDefault();

                var formData = $(this).serialize(), formURL = $(this).attr('action');

                $('.response').html('');

                if($('#dtype').val().trim()=="")
                {
                    $('.response').html('<div class="alert alert-danger" id="danger-x">Please select data type.</div>');
                    return false;
                }

                if($('#stselect').val().trim()=='')
                {
                    $('.response').html('<div class="alert alert-danger" id="danger-x">Please select store / customer.</div>');                    
                    return false;                    
                }

                if($('#month').val().trim()=="")
                {
                    $('.response').html('<div class="alert alert-danger" id="danger-x">Please select month.</div>');
                    return false;                    
                }                

                if($('#year').val().trim()=="")
                {
                    $('.response').html('<div class="alert alert-danger" id="danger-x">Please select year.</div>');
                    return false;                    
                }    

                if($('#dtype').val().trim()!='vgc')
                {                    
                    $('.response').html('<div class="alert alert-danger" id="danger-x">No report exist.</div>');
                    return false;
                }
                // if($('#start').val().trim()=="" || $('#end').val().trim()=="")
                // {
                //     $('.response').html('<div class="alert alert-danger" id="danger-x">Please input date start / date end.</div>');
                //     return false;                      
                // }
               
                // if(!validateDate($('#start').val().trim()) && !validateDate($('#end').val().trim()))
                // {
                //     $('.response').html('<div class="alert alert-danger" id="danger-x">Date is invalid.</div>');
                //     return false;                       
                // }

                // if($('#start').val().trim() > $('#end').val().trim())
                // {
                //     $('.response').html('<div class="alert alert-danger" id="danger-x">Date Start must be lesser than Date End.</div>');
                //     return false;                      
                // }

                //check

                $.ajax({
                    url:formURL,
                    type:'POST',
                    data:formData,
                    beforeSend:function(){

                    },
                    success:function(data1){   
                        console.log(data1);      
                        var data1 = JSON.parse(data1);
                        if(data1['st'])
                        {
                            window.location.href='gcexceldata1.php?'+formData;
                        }
                        else 
                        {
                            $('.response').html('<div class="alert alert-danger" id="danger-x">'+data1['msg']+'</div>');
                        }
                    }
                });

                //alert($('#stselect').val().trim());
            });

            

        </script>

    <?php
}


function _verifiedgcperstore($link,$id,$page)
{
    // SELECT 
    //     store_verification.vs_barcode,
    //     store_verification.vs_tf_denomination,
    //     CONCAT(customers.cus_fname,' ',customers.cus_lname) as customer,
    //     CONCAT(verby.firstname,' ',verby.lastname) as verby,
    //     CONCAT(revby.firstname,' ',revby.lastname) as revby,
    //     store_verification.vs_tf_used,
    //     store_verification.vs_tf_balance,
    //     store_verification.vs_date,
    //     store_verification.vs_time,
    //     store_verification.vs_reverifydate,
    //     store_verification.vs_reverifyby
    // FROM 
    //     store_verification 
    // LEFT JOIN
    //     stores
    // ON
    //     stores.store_id = store_verification.vs_store
    // LEFT JOIN
    //     users as revby
    // ON
    //     store_verification.vs_reverifyby = revby.user_id
    // LEFT JOIN
    //     users as verby
    // ON
    //     store_verification.vs_by = verby.user_id
    // LEFT JOIN
    //     customers
    // ON
    //     customers.cus_id = store_verification.vs_cn
    // WHERE 
    //     store_verification.vs_store='1'

    $where = "store_verification.vs_store='$id'";
    $select = "store_verification.vs_barcode,
        store_verification.vs_tf_denomination,
        CONCAT(customers.cus_fname,' ',customers.cus_lname) as customer,
        CONCAT(verby.firstname,' ',verby.lastname) as verby,
        CONCAT(revby.firstname,' ',revby.lastname) as revby,
        store_verification.vs_tf_used,
        store_verification.vs_tf_balance,
        store_verification.vs_date,
        store_verification.vs_time,
        store_verification.vs_reverifydate";
    $join = "LEFT JOIN
            stores
        ON
            stores.store_id = store_verification.vs_store
        LEFT JOIN
            users as revby
        ON
            store_verification.vs_reverifyby = revby.user_id
        LEFT JOIN
            users as verby
        ON
            store_verification.vs_by = verby.user_id
        LEFT JOIN
            customers
        ON
            customers.cus_id = store_verification.vs_cn";
    $limit ='';
    $data = getAllData($link,'store_verification',$select,$where,$join,$limit); 
    
    $store = getStoreName($link,$id);    

    ?>
        <div class="row">
            <div class="col-md-12">
                <div class="panel with-nav-tabs panel-info">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active" style="font-weight:bold">
                                <a href="#tab1default" data-toggle="tab"><?php echo $store; ?> - Verified GC</a>
                            </li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab1default">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table" id="eodins">
                                            <thead>
                                                <tr>
                                                    <th>Barcode #</th>
                                                    <th>Denomination</th>
                                                    <th>Date Verified/Reverified</th>
                                                    <th>Verified/Reverified By</th>
                                                    <th>Customer</th>
                                                    <th>Balance</th>
                                                    <th>View</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($data as $d): ?> 
                                                    <tr>
                                                        <td><?php echo $d->vs_barcode; ?></td>
                                                        <td><?php echo number_format($d->vs_tf_denomination,2); ?></td>
                                                        <td>
                                                            <?php 
                                                                echo $d->vs_date;
                                                                if(!empty($d->vs_reverifydate))
                                                                {
                                                                    echo '/'.$d->vs_reverifydate;
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php 
                                                                echo ucwords($d->verby);
                                                                if(!empty($d->vs_reverifydate))
                                                                {
                                                                    echo '/'.ucwords($d->revby);
                                                                }
                                                            ?>
                                                        </td>
                                                        <td><?php echo ucwords($d->customer); ?></td>
                                                        <td>
                                                            <?php echo number_format($d->vs_tf_balance,2); ?>
                                                        </td>
                                                        <td>
                                                            <?php if($d->vs_tf_used=='*'): ?>
                                                                <i class="fa fa-fa fa-eye faeye viewinstr" title="View" id="viewinstr" data-id="<?php echo $id; ?>" data-barcode="<?php echo $d->vs_barcode; ?>"></i>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>   
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="tab-pane fade" id="tab2default">Default 2</div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $.extend( $.fn.dataTableExt.oStdClasses, {    
                "sLengthSelect": "selectsup"
            });
            var x = $('#eodins').dataTable( {
                "pagingType": "full_numbers",
                "ordering": false,
                "processing": true,
                "bProcessing":true
            });

            $('div.row div.col-sm-12').on('click','table#eodins tbody tr td i.viewinstr',function(){
                var cur = $('a.current').text();
                var id = $(this).attr('data-id');
                var barcode = $(this).attr('data-barcode');
                cur  = parseInt(cur);
                if(cur!==0)
                {
                    cur = cur -= 1;
                }
                window.location.href="#/viewgctransact/"+barcode+"/"+id+"/verifiedgcperstore/"+cur+"/";
            });
            x.fnPageChange(<?php echo $page; ?>);

        </script>

    <?php
}

function _viewgcsalespertransac($link,$trid,$page)
{
    $where = "transaction_stores.trans_sid='$trid'";
    $select = "transaction_stores.trans_sid,
        transaction_stores.trans_number,
        stores.store_name";
    $join = "INNER JOIN
            stores
        ON
            stores.store_id = transaction_stores.trans_store";
    $limit ='';
    $tr = getSelectedData($link,'transaction_stores',$select,$where,$join,$limit); 
    if(count($tr)==0)
    {
        exit();
    }

    // SELECT 
    //     transaction_sales.sales_barcode,
    //     denomination.denomination,
    //     CONCAT(users.firstname,' ',users.lastname) as verby,
    //     CONCAT(customers.cus_fname,' ',customers.cus_lname) as customer,
    //     store_verification.vs_tf_used,
    //     store_verification.vs_reverifydate,
    //     store_verification.vs_reverifyby,
    //     store_verification.vs_tf_balance
    // FROM 
    //     transaction_sales 
    // INNER JOIN
    //     denomination
    // ON
    //     denomination.denom_id = transaction_sales.sales_denomination
    // LEFT JOIN
    //     store_verification
    // ON
    //     store_verification.vs_barcode = transaction_sales.sales_barcode
    // LEFT JOIN
    //     stores
    // ON
    //     stores.store_id = store_verification.vs_store
    // LEFT JOIN
    //     users
    // ON
    //     store_verification.vs_by = users.user_id
    // LEFT JOIN
    //     customers
    // ON
    //     customers.cus_id = store_verification.vs_cn
    // WHERE 
    //     transaction_sales.sales_transaction_id='1'

    $where = "transaction_sales.sales_transaction_id='$trid'";
    $select = "transaction_sales.sales_barcode,
        denomination.denomination,
        stores.store_name,
        CONCAT(users.firstname,' ',users.lastname) as verby,
        CONCAT(customers.cus_fname,' ',customers.cus_lname) as customer,
        store_verification.vs_date,
        store_verification.vs_tf_used,
        store_verification.vs_reverifydate,
        store_verification.vs_reverifyby,
        store_verification.vs_tf_balance";
    $join = "INNER JOIN
            denomination
        ON
            denomination.denom_id = transaction_sales.sales_denomination
        LEFT JOIN
            store_verification
        ON
            store_verification.vs_barcode = transaction_sales.sales_barcode
        LEFT JOIN
            stores
        ON
            stores.store_id = store_verification.vs_store
        LEFT JOIN
            users
        ON
            store_verification.vs_by = users.user_id
        LEFT JOIN
            customers
        ON
            customers.cus_id = store_verification.vs_cn";
    $limit ='';
    $data = getAllData($link,'transaction_sales',$select,$where,$join,$limit);

    ?>
        <div class="row">
            <div class="col-md-12">
                <div class="panel with-nav-tabs panel-info">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active" style="font-weight:bold">
                                <a href="#tab1default" data-toggle="tab"><?php echo ucwords($tr->store_name); ?> - Transaction #<?php echo $tr->trans_number; ?></a>
                            </li>
                            <a href="#/viewstoresales">
                                <span class="btn pull-right">
                                    <i class="fa fa-backward" aria-hidden="true"></i>
                                    Back
                                </span>
                            </a>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab1default">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table" id="eodins">
                                            <thead>
                                                <tr>
                                                    <th>Barcode #</th>
                                                    <th>Denomination</th>
                                                    <th>Store Verified</th>
                                                    <th>Date Verified</th>
                                                    <th>Verified By</th>
                                                    <th>Customer</th>
                                                    <th>Balance</th>
                                                    <th>View</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($data as $d): ?> 
                                                    <tr>
                                                        <td><?php echo $d->sales_barcode; ?></td>
                                                        <td><?php echo number_format($d->denomination,2); ?></td>
                                                        <td><?php echo $d->store_name; ?></td>
                                                        <td><?php echo $d->vs_date; ?></td>
                                                        <td><?php echo ucwords($d->verby); ?></td>
                                                        <td><?php echo ucwords($d->customer); ?></td>
                                                        <td><?php echo $d->vs_tf_balance; ?></td>
                                                        <td>
                                                            <?php if($d->vs_tf_used=='*'): ?>
                                                                <i class="fa fa-fa fa-eye faeye viewinstr" title="View" id="viewinstr" data-id="<?php echo $trid; ?>" data-barcode="<?php echo $d->sales_barcode; ?>"></i>                                                                
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="tab-pane fade" id="tab2default">Default 2</div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $.extend( $.fn.dataTableExt.oStdClasses, {    
                "sLengthSelect": "selectsup"
            });
            var x = $('#eodins').dataTable( {
                "pagingType": "full_numbers",
                "ordering": false,
                "processing": true,
                "bProcessing":true
            });

            $('div.row div.col-sm-12').on('click','table#eodins tbody tr td i.viewinstr',function(){
                var cur = $('a.current').text();
                var id = $(this).attr('data-id');
                var barcode = $(this).attr('data-barcode');
                cur  = parseInt(cur);
                if(cur!==0)
                {
                    cur = cur -= 1;
                }
                window.location.href="#/viewgctransact/"+barcode+"/"+id+"/viewgcsalespertransac/"+cur+"/";
            });
            x.fnPageChange(<?php echo $page; ?>);
        </script>

    <?php
}

function _viewstoresales($link)
{
    $where = "trans_type='1'
        OR 
            trans_type='2'
        OR 
            trans_type='3'";
    $select = "transaction_stores.trans_sid,
        transaction_stores.trans_number,
        stores.store_name,
        CONCAT(store_staff.ss_firstname,' ',store_staff.ss_lastname) as cashier,
        transaction_stores.trans_datetime,
        transaction_stores.trans_type";
    $join = "INNER JOIN
            stores
        ON
            stores.store_id = transaction_stores.trans_store
        INNER JOIN
            store_staff
        ON
            store_staff.ss_id = transaction_stores.trans_cashier";
    $limit ='';
    $data = getAllData($link,'transaction_stores',$select,$where,$join,$limit);

    ?>
        <div class="row">
            <div class="col-md-12">
                <div class="panel with-nav-tabs panel-info">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active" style="font-weight:bold">
                                <a href="#tab1default" data-toggle="tab">Store Sales</a>
                            </li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab1default">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table" id="eodins">
                                            <thead>
                                                <tr>
                                                    <th>Transaction #</th>
                                                    <th>Store</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th>GC pc(s)</th>
                                                    <th>Total Denom</th>
                                                    <th>Payment Type</th>
                                                    <th>View</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($data as $d): ?>
                                                    <tr>
                                                        <td><?php echo $d->trans_number; ?></td>
                                                        <td><?php echo $d->store_name; ?></td>
                                                        <td><?php echo _dateFormat($d->trans_datetime); ?></td>
                                                        <td><?php echo _timeFormat($d->trans_datetime); ?></td>
                                                        <td>
                                                            <?php 
                                                                $totcnt = 0;
                                                                $totamt = 0;
                                                                $query_tot = $link->query(
                                                                    "SELECT    
                                                                            IFNULL(COUNT(transaction_sales.sales_barcode),0) as cnt,                   
                                                                            IFNULL(SUM(denomination.denomination),0) as totamt    
                                                                    FROM 
                                                                        transaction_sales 
                                                                    INNER JOIN
                                                                        gc
                                                                    ON
                                                                        gc.barcode_no = transaction_sales.sales_barcode
                                                                    INNER JOIN
                                                                        denomination
                                                                    ON
                                                                        denomination.denom_id = gc.denom_id
                                                                    WHERE 
                                                                        transaction_sales.sales_transaction_id='$d->trans_sid'
                                                                ");

                                                                if($query_tot)
                                                                {
                                                                    $row_tot = $query_tot->fetch_object(); 
                                                                    $totcnt = $row_tot->cnt;
                                                                    $totamt = $row_tot->totamt;
                                                                }
                                                                else 
                                                                {
                                                                    echo $link->error;
                                                                }

                                                                echo number_format($totcnt);                                                        
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php 
                                                                echo number_format($totamt,2);
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php 
                                                                if($d->trans_type=='1')
                                                                {
                                                                    echo 'Cash';
                                                                }
                                                                elseif($d->trans_type=='2') 
                                                                {
                                                                    echo 'Credit Card';
                                                                }
                                                                elseif ($d->trans_type=='3') 
                                                                {
                                                                    echo 'AR';
                                                                }
                                                            ?>

                                                        </td>
                                                        <td>
                                                            <a href="#/viewgcsalespertransac/<?php echo $d->trans_sid; ?>/0"><i class="fa fa-fa fa-eye faeye" title="View" id="viewinstr"></i></a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="tab-pane fade" id="tab2default">Default 2</div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $.extend( $.fn.dataTableExt.oStdClasses, {    
                "sLengthSelect": "selectsup"
            });
            $('#eodins').dataTable( {
                "pagingType": "full_numbers",
                "ordering": false,
                "processing": true,
                "bProcessing":true
            });
        </script> 
    <?php 
}

function _viewgctransact($link,$barcode,$trid,$url,$page)
{

    $tr = getAllNavPOSTranx($link,$barcode);
    
    ?>
        <div class="row">
            <div class="col-md-12">
                <div class="panel with-nav-tabs panel-info">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active" style="font-weight:bold">
                                <a href="#tab1default" data-toggle="tab">GC Barcode #<?php echo $barcode; ?> POS Transaction</a>
                            </li>
                            <a href="#/<?php echo $url; ?>/<?php echo $trid; ?>/<?php echo $page; ?>">
                                <span class="btn pull-right">
                                    <i class="fa fa-backward" aria-hidden="true"></i>
                                    Back
                                </span>
                            </a>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab1default">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table" id="eodins">
                                            <thead>
                                                <tr>
                                                    <th>Textfile Line</th>
                                                    <th>Credit Limit</th>
                                                    <th>Cred. Pur. Amt + Add-on</th>
                                                    <th>Add-on Amt</th>
                                                    <th>Remaining Balance</th>
                                                    <th>Transaction #</th>
                                                    <th>Time of Cred Tranx</th>
                                                    <th>Bus. Unit</th>
                                                    <th>Terminal #</th>
                                                    <th>Ackslip #</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($tr as $t): ?>
                                                    <tr>
                                                        <td><?php echo $t->seodtt_line; ?></td>
                                                        <td><?php echo $t->seodtt_creditlimit; ?></td>
                                                        <td><?php echo $t->seodtt_credpuramt; ?></td>
                                                        <td><?php echo $t->seodtt_addonamt; ?></td>
                                                        <td><?php echo $t->seodtt_balance; ?></td>
                                                        <td><?php echo $t->seodtt_transno; ?></td>
                                                        <td>
                                                            <?php 
                                                                $ti = $t->seodtt_timetrnx;
                                                                if(strlen($t->seodtt_timetrnx)===3)
                                                                {
                                                                    $ti = "0".$ti;
                                                                }

                                                                $date = new DateTime($ti);
                                                                echo $date->format('h:i a');
                                                            ?>
                                                        </td>
                                                        <td><?php echo $t->seodtt_bu; ?></td>
                                                        <td><?php echo $t->seodtt_terminalno; ?></td>
                                                        <td><?php echo $t->seodtt_ackslipno; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="tab-pane fade" id="tab2default">Default 2</div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $.extend( $.fn.dataTableExt.oStdClasses, {    
                "sLengthSelect": "selectsup"
            });
            $('#eodins').dataTable( {
                "pagingType": "full_numbers",
                "ordering": false,
                "processing": true,
                "bProcessing":true
            });
        </script>  

    <?php
}

function _viewgctransactstores($link,$barcode,$trid,$url,$page,$store)
{

}

function _viewtressales($link,$trid,$page)
{
    $table = 'institut_payment';
    $select = 'insp_id,
        insp_trid,
        insp_paymentcustomer,
        institut_bankname,
        institut_bankaccountnum,
        institut_checknumber,
        institut_amountrec,
        insp_paymentnum,
        institut_eodid';
    $where = "insp_id='$trid'";
    $join = '';
    $limit = 'ORDER BY insp_paymentnum DESC';

    $payments = getSelectedData($link,$table,$select,$where,$join,$limit);

    $customername = "";
    if($payments->insp_paymentcustomer=='institution')
    {

        $query = $link->query(
            "SELECT
                institut_customer.ins_name
            FROM 
                institut_transactions 
            INNER JOIN
                institut_customer
            ON
                institut_customer.ins_id = institut_transactions.institutr_cusid
            WHERE 
                institut_transactions.institutr_id = '$payments->insp_trid'
        ");

        if($query)
        {
            $row = $query->fetch_object();
            $customername = $row->ins_name;

        }
        else 
        {
            echo $link->error;
        }

    }
    elseif($payments->insp_paymentcustomer=='stores') 
    {
        $query = $link->query(
            "SELECT
                stores.store_name
            FROM 
                approved_gcrequest
            INNER JOIN
                store_gcrequest
            ON
                store_gcrequest.sgc_id = approved_gcrequest.agcr_request_id
            INNER JOIN
                stores
            ON
                stores.store_id = store_gcrequest.sgc_store
            WHERE 
                approved_gcrequest.agcr_id = '$payments->insp_trid'    
        ");

        if($query)
        {
            $row = $query->fetch_object();
            $customername = $row->store_name;
        }
    }
    elseif ($payments->insp_paymentcustomer=='special external') 
    {
        $query = $link->query(
            "SELECT 
                special_external_customer.spcus_companyname
            FROM 
                special_external_gcrequest
            INNER JOIN
                special_external_customer
            ON
                special_external_customer.spcus_id = special_external_gcrequest.spexgc_company
            WHERE 
                special_external_gcrequest.spexgc_id='$payments->insp_trid'    
        ");

        if($query)
        {

            $row = $query->fetch_object();
            $customername = $row->spcus_companyname;
        }
    }



    if(count($payments)==0)
    {
        echo 'Page Not Found.';
        exit();
    }

    if($payments->insp_paymentcustomer=='institution')
    {
        $query = $link->query(
            "SELECT 
                institut_transactions.institutr_id,
                institut_transactions.institutr_trnum,
                institut_transactions.institutr_paymenttype,
                institut_transactions.institutr_date,
                institut_customer.ins_name
            FROM 
                institut_transactions 
            INNER JOIN
                institut_customer
            ON
                institut_customer.ins_id = institut_transactions.institutr_cusid
            WHERE 
                institut_transactions.institutr_id = '$payments->insp_trid'
        ");

        if($query)
        {

            // SELECT 
            //     institut_transactions_items.instituttritems_barcode,
            //     denomination.denomination,
                // stores.store_name,
                // store_verification.vs_date,
                // CONCAT(users.firstname,' ',users.lastname) as verby,
                // CONCAT(customers.cus_fname,' ',customers.cus_lname) as customer,
                // store_verification.vs_tf_used,
                // store_verification.vs_reverifydate,
                // store_verification.vs_reverifyby,
                // store_verification.vs_tf_balance  

            // FROM 
            //     institut_transactions_items
            // INNER JOIN
            //     gc
            // ON
            //     gc.barcode_no = institut_transactions_items.instituttritems_barcode
            // INNER JOIN
            //     denomination
            // ON
            //     denomination.denom_id = gc.denom_id
            // LEFT JOIN
            //     store_verification
            // ON
            //     store_verification.vs_barcode = institut_transactions_items.instituttritems_barcode
            // LEFT JOIN
            //     stores
            // ON
            //     stores.store_id = store_verification.vs_store
            // LEFT JOIN
            //     users
            // ON
            //     store_verification.vs_by = users.user_id
            // LEFT JOIN
            //     customers
            // ON
            //     customers.cus_id = store_verification.vs_cn
            // WHERE 
            //     instituttritems_trid='1'

            $where = "instituttritems_trid='$payments->insp_trid'";
            $select = "institut_transactions_items.instituttritems_barcode,
                denomination.denomination,
                stores.store_name,
                store_verification.vs_date,
                CONCAT(users.firstname,' ',users.lastname) as verby,
                CONCAT(customers.cus_fname,' ',customers.cus_lname) as customer,
                store_verification.vs_tf_used,
                store_verification.vs_reverifydate,
                store_verification.vs_reverifyby,
                store_verification.vs_tf_balance ";
            $join = "INNER JOIN
                    gc
                ON
                    gc.barcode_no = institut_transactions_items.instituttritems_barcode
                INNER JOIN
                    denomination
                ON
                    denomination.denom_id = gc.denom_id
                LEFT JOIN
                    store_verification
                ON
                    store_verification.vs_barcode = institut_transactions_items.instituttritems_barcode
                LEFT JOIN
                    stores
                ON
                    stores.store_id = store_verification.vs_store
                LEFT JOIN
                    users
                ON
                    store_verification.vs_by = users.user_id
                LEFT JOIN
                    customers
                ON
                    customers.cus_id = store_verification.vs_cn";
            $limit ='';
            $data = getAllData($link,'institut_transactions_items',$select,$where,$join,$limit);


            foreach ($data as $d) 
            {
                $arr_barcodesinfo[] =  array(
                    'barcode'       => $d->instituttritems_barcode,
                    'denomination'  => $d->denomination,
                    'store'         => $d->store_name,
                    'dateverify'    => $d->vs_date,
                    'verifyby'      => $d->verby,
                    'rdateverify'   => $d->vs_reverifydate,
                    'rverifyby'     => $d->vs_reverifyby, 
                    'customer'      => $d->customer,
                    'used'          => $d->vs_tf_used, 
                    'balance'       => $d->vs_tf_balance,
                    'type'          => 'Institution GC'
                );
            }            
        }
    }
    elseif($payments->insp_paymentcustomer=='stores') 
    {


        // SELECT 
        //     gc_release.re_barcode_no,
        //     denomination.denomination,
        //     stores.store_name,
        //     store_verification.vs_date,
        //     CONCAT(users.firstname,' ',users.lastname) as verby,
        //     CONCAT(customers.cus_fname,' ',customers.cus_lname) as customer,
        //     store_verification.vs_tf_used,
        //     store_verification.vs_reverifydate,
        //     store_verification.vs_reverifyby,
        //     store_verification.vs_tf_balance 
        // FROM 
        //         gc_release 
        // INNER JOIN
        //     gc
        // ON
        //     gc.barcode_no = gc_release.re_barcode_no
        // INNER JOIN
        //     denomination
        // ON
        //     denomination.denom_id = gc.denom_id
        // LEFT JOIN
        //     store_verification
        // ON
        //     store_verification.vs_barcode = gc_release.re_barcode_no
        // LEFT JOIN
        //     stores
        // ON
        //     stores.store_id = store_verification.vs_store
        // LEFT JOIN
        //     users
        // ON
        //     store_verification.vs_by = users.user_id
        // LEFT JOIN
        //     customers
        // ON
        //     customers.cus_id = store_verification.vs_cn
        // WHERE 
        //     gc_release.rel_num='1'

        $query = $link->query(
            "SELECT 
                approved_gcrequest.agcr_request_id,
                approved_gcrequest.agcr_request_relnum,
                approved_gcrequest.agcr_approved_at,
                approved_gcrequest.agcr_paymenttype,
                stores.store_name
            FROM 
                approved_gcrequest
            INNER JOIN
                store_gcrequest
            ON
                store_gcrequest.sgc_id = approved_gcrequest.agcr_request_id
            INNER JOIN
                stores
            ON
                stores.store_id = store_gcrequest.sgc_store
            WHERE 
                approved_gcrequest.agcr_id = '$payments->insp_trid'    
        ");

        if($query)
        {
            $row_details = $query->fetch_object();

            $where = "gc_release.rel_num='$row_details->agcr_request_relnum'";
            $select = "gc_release.re_barcode_no,
                denomination.denomination,
                stores.store_name,
                store_verification.vs_date,
                CONCAT(users.firstname,' ',users.lastname) as verby,
                CONCAT(customers.cus_fname,' ',customers.cus_lname) as customer,
                store_verification.vs_tf_used,
                store_verification.vs_reverifydate,
                store_verification.vs_reverifyby,
                store_verification.vs_tf_balance ";
            $join = "INNER JOIN
                    gc
                ON
                    gc.barcode_no = gc_release.re_barcode_no
                INNER JOIN
                    denomination
                ON
                    denomination.denom_id = gc.denom_id
                LEFT JOIN
                    store_verification
                ON
                    store_verification.vs_barcode = gc_release.re_barcode_no
                LEFT JOIN
                    stores
                ON
                    stores.store_id = store_verification.vs_store
                LEFT JOIN
                    users
                ON
                    store_verification.vs_by = users.user_id
                LEFT JOIN
                    customers
                ON
                    customers.cus_id = store_verification.vs_cn";
            $limit ='';
            $data = getAllData($link,'gc_release',$select,$where,$join,$limit);

            foreach ($data as $d) 
            {
                $arr_barcodesinfo[] =  array(
                    'barcode'       => $d->re_barcode_no,
                    'denomination'  => $d->denomination,
                    'store'         => $d->store_name,
                    'dateverify'    => $d->vs_date,
                    'verifyby'      => $d->verby,
                    'rdateverify'   => $d->vs_reverifydate,
                    'rverifyby'     => $d->vs_reverifyby, 
                    'customer'      => $d->customer,
                    'used'          => $d->vs_tf_used, 
                    'balance'       => $d->vs_tf_balance,
                    'type'          => 'Regular GC'
                );
            }  


        }


    }
    elseif ($payments->insp_paymentcustomer=='special external') 
    {
        // SELECT 
        //     special_external_gcrequest_emp_assign.spexgcemp_barcode,
        //     special_external_gcrequest_emp_assign.spexgcemp_denom,
        //     store_verification.vs_date,
        //     CONCAT(users.firstname,' ',users.lastname) as verby,
        //     CONCAT(customers.cus_fname,' ',customers.cus_lname) as customer,
        //     store_verification.vs_tf_used,
        //     store_verification.vs_reverifydate,
        //     store_verification.vs_reverifyby,
        //     store_verification.vs_tf_balance  
        // FROM 
        //     special_external_gcrequest_emp_assign 
        // LEFT JOIN
        //     store_verification
        // ON
        //     store_verification.vs_barcode = special_external_gcrequest_emp_assign.spexgcemp_barcode
        // LEFT JOIN
        //     stores
        // ON
        //     stores.store_id = store_verification.vs_store
        // LEFT JOIN
        //     users
        // ON
        //     store_verification.vs_by = users.user_id
        // LEFT JOIN
        //     customers
        // ON
        //     customers.cus_id = store_verification.vs_cn
        // WHERE 
        //     spexgcemp_trid='1'

        $query = $link->query(
            "SELECT 
                special_external_gcrequest.spexgc_id,
                special_external_gcrequest.spexgc_datereq,
                special_external_customer.spcus_companyname,
                special_external_gcrequest.spexgc_paymentype
            FROM 
                special_external_gcrequest
            INNER JOIN
                special_external_customer
            ON
                special_external_customer.spcus_id = special_external_gcrequest.spexgc_company
            WHERE 
                special_external_gcrequest.spexgc_id='$payments->insp_trid'    
        ");

        if($query)
        {
            $where = "spexgcemp_trid='$payments->insp_trid'";
            $select = "special_external_gcrequest_emp_assign.spexgcemp_barcode,
                special_external_gcrequest_emp_assign.spexgcemp_denom,
                stores.store_name,
                store_verification.vs_date,
                CONCAT(users.firstname,' ',users.lastname) as verby,
                CONCAT(customers.cus_fname,' ',customers.cus_lname) as customer,
                store_verification.vs_tf_used,
                store_verification.vs_reverifydate,
                store_verification.vs_reverifyby,
                store_verification.vs_tf_balance ";
            $join = "LEFT JOIN
                    store_verification
                ON
                    store_verification.vs_barcode = special_external_gcrequest_emp_assign.spexgcemp_barcode
                LEFT JOIN
                    stores
                ON
                    stores.store_id = store_verification.vs_store
                LEFT JOIN
                    users
                ON
                    store_verification.vs_by = users.user_id
                LEFT JOIN
                    customers
                ON
                    customers.cus_id = store_verification.vs_cn";
            $limit ='';
            $data = getAllData($link,'special_external_gcrequest_emp_assign',$select,$where,$join,$limit);

            foreach ($data as $d) 
            {
                $arr_barcodesinfo[] =  array(
                    'barcode'       => $d->spexgcemp_barcode,
                    'denomination'  => $d->spexgcemp_denom,
                    'store'         => $d->store_name,
                    'dateverify'    => $d->vs_date,
                    'verifyby'      => $d->verby,
                    'rdateverify'   => $d->vs_reverifydate,
                    'rverifyby'     => $d->vs_reverifyby, 
                    'customer'      => $d->customer,
                    'used'          => $d->vs_tf_used, 
                    'balance'       => $d->vs_tf_balance,
                    'type'          => 'Special External GC'
                );
            }   

        }
    }

    ?>
        <div class="row">
            <div class="col-md-12">
                <div class="panel with-nav-tabs panel-info">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active" style="font-weight:bold">
                                <a href="#tab1default" data-toggle="tab">Customer : <?php echo ucwords($customername); ?></a>
                            </li>
                            <a href="#/saleslisttreasury">
                                <span class="btn pull-right">
                                    <i class="fa fa-backward" aria-hidden="true"></i>
                                    Back
                                </span>
                            </a>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab1default">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table" id="eodins">
                                            <thead>
                                                <tr>
                                                    <th>Barcode #</th>
                                                    <th>GC Type</th>
                                                    <th>Denomination</th>
                                                    <th>Date Verified</th>
                                                    <th>Store Verified</th>
                                                    <th>Verified By</th>
                                                    <th>Customer Name</th>
                                                    <th>Balance</th>
                                                    <th>View</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($arr_barcodesinfo as $d): ?>
                                                    <tr>
                                                        <td><?php echo $d['barcode']; ?></td>
                                                        <td><?php echo $d['type']; ?></td>
                                                        <td><?php echo number_format($d['denomination'],2); ?></td>
                                                        <td><?php echo $d['dateverify']; ?></td>
                                                        <td><?php echo ucwords($d['store']); ?></td>
                                                        <td><?php echo ucwords($d['verifyby']); ?></td>
                                                        <td><?php echo ucwords($d['customer']); ?></td>
                                                        <td><?php echo ucwords($d['balance']); ?></td>
                                                        <td>
                                                            <?php if($d['used']=='*'): ?>
                                                                <i class="fa fa-fa fa-eye faeye viewinstr" title="View" id="viewinstr" data-id="<?php echo $trid; ?>" data-barcode="<?php echo $d['barcode']; ?>"></i>                                                                                                                               
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="tab-pane fade" id="tab2default">Default 2</div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $.extend( $.fn.dataTableExt.oStdClasses, {    
                "sLengthSelect": "selectsup"
            });
            var x = $('#eodins').dataTable( {
                "pagingType": "full_numbers",
                "ordering": false,
                "processing": true,
                "bProcessing":true
            });
            //dri

            $('div.row div.col-sm-12').on('click','table#eodins tbody tr td i.viewinstr',function(){
                var cur = $('a.current').text();
                var id = $(this).attr('data-id');
                var barcode = $(this).attr('data-barcode');
                cur  = parseInt(cur);
                if(cur!==0)
                {
                    cur = cur -= 1;
                }
                window.location.href="#/viewgctransact/"+barcode+"/"+id+"/viewtressales/"+cur+"/";
            });
            x.fnPageChange(<?php echo $page; ?>);
        </script>        

    <?php
}

function _saleslisttreasury($link)
{

    $table = 'institut_payment';
    $select = 'insp_id,
        insp_trid,
        insp_paymentcustomer,
        institut_bankname,
        institut_bankaccountnum,
        institut_checknumber,
        institut_amountrec,
        insp_paymentnum,
        institut_eodid';
    $where = "1";
    $join = '';
    $limit = 'ORDER BY insp_paymentnum DESC';

    $payments = getAllData($link,$table,$select,$where,$join,$limit);

    ?>
        <div class="row">
            <div class="col-md-12">
                <div class="panel with-nav-tabs panel-info">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active" style="font-weight:bold">
                                <a href="#tab1default" data-toggle="tab">Treasury Sales</a>
                            </li>
                            <a href="index.php">
                                <span class="btn pull-right">
                                    <i class="fa fa-backward" aria-hidden="true"></i>
                                    Back
                                </span>
                            </a>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab1default">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table" id="eodins">
                                            <thead>
                                                <tr>
                                                    <th>Transaction #</th>
                                                    <th>GC Type</th>
                                                    <th>Customer</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th>GC pc(s)</th>
                                                    <th>Total Denom</th>
                                                    <th>Payment Type</th>
                                                    <th>View</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    foreach ($payments as $p): 
                                                    $datetr = '';
                                                    $totgccnt = '';
                                                    $totdenom = '';
                                                    $customer = '';
                                                    $paymenttype = '';

                                                    if($p->insp_paymentcustomer=='institution')
                                                    {

                                                        $query = $link->query(
                                                            "SELECT 
                                                                institut_transactions.institutr_id,
                                                                institut_transactions.institutr_trnum,
                                                                institut_transactions.institutr_paymenttype,
                                                                institut_transactions.institutr_date,
                                                                institut_customer.ins_name
                                                            FROM 
                                                                institut_transactions 
                                                            INNER JOIN
                                                                institut_customer
                                                            ON
                                                                institut_customer.ins_id = institut_transactions.institutr_cusid
                                                            WHERE 
                                                                institut_transactions.institutr_id = '$p->insp_trid'
                                                        ");

                                                        if($query)
                                                        {
                                                            $row = $query->fetch_object();

                                                            $paymenttype = $row->institutr_paymenttype;

                                                            $customer = $row->ins_name;
                                                            $datetr = $row->institutr_date;

                                                            $query_gcs = $link->query(
                                                                "SELECT 
                                                                    IFNULL(COUNT(institut_transactions_items.instituttritems_barcode),0) as cnt,
                                                                    IFNULL(SUM(denomination.denomination),0) as totamt   
                                                                    
                                                                FROM 
                                                                    institut_transactions_items
                                                                INNER JOIN
                                                                    gc
                                                                ON
                                                                    gc.barcode_no = institut_transactions_items.instituttritems_barcode
                                                                INNER JOIN
                                                                    denomination
                                                                ON
                                                                    denomination.denom_id = gc.denom_id
                                                                WHERE 
                                                                    instituttritems_trid = '$p->insp_trid'
                                                            ");

                                                            if($query_gcs)
                                                            {
                                                                $row = $query_gcs->fetch_object();

                                                                $totgccnt = $row->cnt;
                                                                $totdenom = $row->totamt;
                                                            }
                                                        }

                                                    }
                                                    elseif($p->insp_paymentcustomer=='stores') 
                                                    {
                                                        $query = $link->query(
                                                            "SELECT 
                                                                approved_gcrequest.agcr_request_id,
                                                                approved_gcrequest.agcr_request_relnum,
                                                                approved_gcrequest.agcr_approved_at,
                                                                approved_gcrequest.agcr_paymenttype,
                                                                stores.store_name
                                                            FROM 
                                                                approved_gcrequest
                                                            INNER JOIN
                                                                store_gcrequest
                                                            ON
                                                                store_gcrequest.sgc_id = approved_gcrequest.agcr_request_id
                                                            INNER JOIN
                                                                stores
                                                            ON
                                                                stores.store_id = store_gcrequest.sgc_store
                                                            WHERE 
                                                                approved_gcrequest.agcr_id = '$p->insp_trid'    
                                                        ");

                                                        if($query)
                                                        {
                                                            $row = $query->fetch_object();
                                                            $customer = $row->store_name;
                                                            $datetr = $row->agcr_approved_at;

                                                            $paymenttype = $row->agcr_paymenttype;

                                                            $query_gcs = $link->query(
                                                                "SELECT     
                                                                    IFNULL(COUNT(gc_release.re_barcode_no),0) as cnt,                                                                               
                                                                    IFNULL(SUM(denomination.denomination),0) as totamt  
                                                                FROM 
                                                                    gc_release 
                                                                INNER JOIN
                                                                    gc
                                                                ON
                                                                    gc.barcode_no = gc_release.re_barcode_no
                                                                INNER JOIN
                                                                    denomination
                                                                ON
                                                                    denomination.denom_id = gc.denom_id
                                                                WHERE 
                                                                    rel_num='$row->agcr_request_relnum'
                                                            ");

                                                            if($query_gcs)
                                                            {
                                                                $row = $query_gcs->fetch_object();

                                                                $totgccnt = $row->cnt;
                                                                $totdenom = $row->totamt;
                                                            }
                                                        }
                                                    }
                                                    elseif ($p->insp_paymentcustomer=='special external') 
                                                    {
                                                        $query = $link->query(
                                                            "SELECT 
                                                                special_external_gcrequest.spexgc_id,
                                                                special_external_gcrequest.spexgc_datereq,
                                                                special_external_customer.spcus_companyname,
                                                                special_external_gcrequest.spexgc_paymentype,
                                                                special_external_gcrequest.spexgc_addemp
                                                            FROM 
                                                                special_external_gcrequest
                                                            INNER JOIN
                                                                special_external_customer
                                                            ON
                                                                special_external_customer.spcus_id = special_external_gcrequest.spexgc_company
                                                            WHERE 
                                                                special_external_gcrequest.spexgc_id='$p->insp_trid'    
                                                        ");

                                                        if($query)
                                                        {

                                                            $row = $query->fetch_object();
                                                            if($row->spexgc_addemp=='pending')
                                                            {
                                                                continue;
                                                            }

                                                            $customer = $row->spcus_companyname;
                                                            $datetr = $row->spexgc_datereq;

                                                            if($row->spexgc_paymentype=='1')
                                                            {
                                                                $paymenttype = 'cash';
                                                            }
                                                            else 
                                                            {
                                                                $paymenttype = 'check';
                                                            }

                                                            $query_gcs = $link->query(
                                                                "SELECT 
                                                                    IFNULL(SUM(special_external_gcrequest_items.specit_qty),0) as cnt,
                                                                    IFNULL(SUM(special_external_gcrequest_items.specit_denoms * special_external_gcrequest_items.specit_qty),0) as totamt
                                                                FROM 
                                                                    special_external_gcrequest_items
                                                                WHERE 
                                                                    specit_trid='$p->insp_trid'
        
                                                            ");

                                                            if($query_gcs)
                                                            {
                                                                $row = $query_gcs->fetch_object();

                                                                $totgccnt = $row->cnt;
                                                                $totdenom = $row->totamt;                                                           
                                                            }
                                                        }
                                                    }
                                                ?>
                                                    <tr>
                                                        <td><?php echo  sprintf("%03d", $p->insp_paymentnum); ?></td>
                                                        <td>
                                                            <?php 
                                                                if($p->insp_paymentcustomer=='stores')
                                                                {
                                                                    echo 'Regular GC';
                                                                }
                                                                elseif ($p->insp_paymentcustomer=='institution') {
                                                                    echo 'Institution GC';
                                                                }
                                                                elseif($p->insp_paymentcustomer=='special external')
                                                                {
                                                                    echo 'Special External GC';
                                                                }

                                                            ?>
                                                        </td>
                                                        <td><?php echo $customer; ?></td>
                                                        <td><?php echo _dateFormat($datetr); ?></td>
                                                        <td><?php echo _timeFormat($datetr); ?></td>
                                                        <td>
                                                            <?php
                                                                echo $totgccnt;
                                                            ?>

                                                        </td>
                                                        <td>
                                                            <?php 
                                                                echo number_format($totdenom,2);
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php if($paymenttype=='cashcheck'): 
                                                                echo 'Check and Cash';
                                                            ?>
                                                            <?php else: ?>
                                                                <?php echo ucwords($paymenttype); ?>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><a href="#/viewtressales/<?php echo $p->insp_id; ?>/0"><i class="fa fa-fa fa-eye faeye" title="View" id="viewinstr"></i></a></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="tab-pane fade" id="tab2default">Default 2</div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $.extend( $.fn.dataTableExt.oStdClasses, {    
                "sLengthSelect": "selectsup"
            });
            $('#eodins').dataTable( {
                "pagingType": "full_numbers",
                "ordering": false,
                "processing": true,
                "bProcessing":true
            });
        </script>

    <?php
}


?>

<div class="modal modal-static fade loadingstyle" id="processing-modal" role="dialog" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog loadingstyle">
      <div class="text-center">
          <img src="../assets/images/ring-alt.svg" class="icon" />
          <h4 class="loading">Processing...Please wait...</h4>
      </div>
    </div>
</div>
