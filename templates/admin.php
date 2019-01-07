<script src="../assets/js/funct.js"></script>
<?php
session_start();
include '../function.php';

if(isset($_GET['page']))
{
    $page = $_GET['page'];

    if($page=='verifiedgcnotranx')
    {
        _verifiedgcnotranx($link);
    }
    elseif($page=='setasusegc')
    {
        if(!isset($_GET['gcnum']) && $_GET['gcnum']!='')
        {
            exit();
        }

        $gcnum = $_GET['gcnum'];

        _setAsUsedGC($link, $gcnum);
    }
    elseif ($page=='verifygcmanual') 
    {
        //echo 'yeah';
        _verifygcmanual($todays_date,$link);
    }
    elseif ($page=='createtextfile')
    {
        _createtextfile($todays_date,$link);
    }
    elseif ($page=='eodtextfilecheck') 
    {
        _eodtextfilecheck($todays_date,$link);
    }
    elseif ($page=='viewverifiedgc') 
    {
        _viewverifiedgc($link);
    }
    else 
    {
        //last
        echo 'Something went wrong.';
    }
}

function _viewverifiedgc($link)
{
    ?>
        <div class="row form-container">
            <div class="col-md-12">
                <div class="panel with-nav-tabs panel-info">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Verified GC</a></li>
                        </ul>                       
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab1default">
                                <div class="row">
                                    <div class="col-xs-12 cardsalesload">
                                        <div class="box-content">
                                            <table class="table" id="_verifiedGC">
                                            <thead>
                                                <tr>
                                                    <th>Barcode</th>
                                                    <th>Denomination</th>
                                                    <th>GC Type</th>    
                                                    <th>Date Sold / Released</th>
                                                    <th>Store</th>
                                                    <th>Verified Customer</th>
                                                    <th class="center">GC Details</th>
                                                </tr>
                                            </thead>
                                                <tbody class="cus-tbody">
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>                                                        
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>                          
                                    </div>
                                </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">

            $.extend( $.fn.dataTableExt.oStdClasses, {    
                "sLengthSelect": "selectsup"
            });

            $('#_verifiedGC').DataTable( {
                "processing": true,
                "serverSide": true,
                "ajax":{
                    url :"../ajax.php?action=getAllVerifiedStoreGC", // json datasource
                    type: "post",  // method  , by default get
                    error: function(data){  // error handling
                        console.log(data);
                        $(".employee-grid-error").html("");
                        $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#employee-grid_processing").css("display","none");               
                    }
                }
            });

            function verifiedGCInfo(barcode)
            {
                BootstrapDialog.show({
                    title: '<i class="fa fa-fa fa-info"></i> GC Barcode #'+barcode,
                    closable: true,
                    closeByBackdrop: false,
                    closeByKeyboard: false,
                    cssClass: 'verifiedmodal',
                    message: function(dialog) {
                        var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
                        var pageToLoad = dialog.getData('pageToLoad');
                        setTimeout(function(){
                        $message.load(pageToLoad);
                        },1000);
                        return $message;
                    },
                    data: {
                        'pageToLoad': '../dialogs/storeverificationinfo.php?action=dateverified&barcode='+barcode
                    },
                    onshown: function(dialog) {
                    },
                    buttons: [{
                        icon: 'glyphicon glyphicon-remove-sign',
                        label: 'Close',
                        cssClass: 'btn-default',
                        action:function(dialogItself){
                            dialogItself.close();
                        }
                    }]
                });
            }

            function reverificationInfo(barcode)
            {
                BootstrapDialog.show({
                    title: '<i class="fa fa-th-large"></i> GC Barcode #'+barcode,
                    closable: true,
                    closeByBackdrop: false,
                    closeByKeyboard: false,
                    cssClass: 'verifiedmodal',
                    message: function(dialog) {
                        var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
                        var pageToLoad = dialog.getData('pageToLoad');
                        setTimeout(function(){
                        $message.load(pageToLoad);
                        },1000);
                        return $message;
                    },
                    data: {
                        'pageToLoad': '../dialogs/storeverificationinfo.php?action=reverification&barcode='+barcode
                    },
                    onshown: function(dialog) {
                    },
                    buttons: [{
                        icon: 'glyphicon glyphicon-remove-sign',
                        label: 'Close',
                        cssClass: 'btn-default',
                        action:function(dialogItself){
                            dialogItself.close();
                        }
                    }]
                });
            }


            function revalidationGCInfo(barcode)
            {
                BootstrapDialog.show({
                    title: '<i class="fa fa-th-large"></i> GC Barcode #'+barcode,
                    closable: true,
                    closeByBackdrop: false,
                    closeByKeyboard: false,
                    cssClass: 'verifiedmodal',
                    message: function(dialog) {
                        var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
                        var pageToLoad = dialog.getData('pageToLoad');
                        setTimeout(function(){
                        $message.load(pageToLoad);
                        },1000);
                        return $message;
                    },
                    data: {
                        'pageToLoad': '../dialogs/storeverificationinfo.php?action=daterevalidated&barcode='+barcode
                    },
                    onshown: function(dialog) {
                    },
                    buttons: [{
                        icon: 'glyphicon glyphicon-remove-sign',
                        label: 'Close',
                        cssClass: 'btn-default',
                        action:function(dialogItself){
                            dialogItself.close();
                        }
                    }]
                });
            }

            function textfiletranx(barcode)
            {
                BootstrapDialog.show({
                    title: '<i class="fa fa-bars"></i>GC Navision POS Transactions',
                    cssClass: 'nav-trax',
                    closable: true,
                    closeByBackdrop: false,
                    closeByKeyboard: true,
                    message: function(dialog) {
                        var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
                        var pageToLoad = dialog.getData('pageToLoad');
                        setTimeout(function(){
                        $message.load(pageToLoad);
                        },1000);
                        return $message;
                    },
                    data: {
                        'pageToLoad': '../dialogs/postransactions.php?barcode='+barcode
                    },
                    onshown: function(dialogRef){               
                    },
                    buttons: [{
                        icon: 'glyphicon glyphicon-remove-sign',
                        label: 'Close',
                        action: function(dialogItself){
                            dialogItself.close();
                        }
                    }]
                });
            }


        </script>

    <?php
}

function _eodtextfilecheck($todays_date,$link)
{
    $stores = getStores($link);

    ?>
        <div class="row form-container">
            <div class="col-md-12">
                <div class="panel with-nav-tabs panel-info">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">EOD GC Textfile Checker</a></li>
                        </ul>                       
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab1default">
                                <div class="row form-container">
                                    <form action="../ajax.php?action=eodtextfilecheck" method="POST" id="eodtextfilecheck" enctype="multipart/form-data">                

                                        <div class="col-sm-4">
                                            <?php foreach ($stores as $s): ?>
                                                <div class="form-group">
                                                    <label>
                                                        <input type="checkbox" class="storecheckbox" name="stores[]" value="<?php echo $s->store_id; ?>"> <?php echo ucwords($s->store_name); ?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                            <button class="btn btn-primary btn-block" id="btn-check" type="submit">Check Textfile</button>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="response-form">

                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $('.form-container').on('submit','form#eodtextfilecheck',function(event){
                var formURL = $(this).attr('action'), formData = $(this).serialize();
                var hasCheck = false;
                event.preventDefault();
                $('.response-form').html(''); 

                $('.storecheckbox').each(function(){
                    if($(this).is(':checked'))
                    {
                        hasCheck = true;
                    }
                });

                if(!hasCheck)
                {
                    $('.response-form').html('<div class="alert alert-danger">Please check store checkbox.</div>'); 
                    return  false;
                }

                $('button#btn-check').prop('disabled',true);
                $.ajax({
                    url:formURL,
                    data:formData,
                    type:'POST',
                    beforeSend:function(){
                        $('#processing-modal').modal('show');
                    },
                    success:function(data){
                        $('#processing-modal').modal('hide');
                        console.log(data);
                        var data = JSON.parse(data);

                        if(data['st'])
                        {
                            $('.response-form').html('<div class="alert alert-success" id="danger-x">Were Good!</div>');
                            $('button#btn-check').prop('disabled',false);
                        }
                        else 
                        {
                            $('.response-form').html('<div class="alert alert-danger" id="danger-x">'+data['msg']+'</div>');
                            $('button#btn-check').prop('disabled',false);
                        }
                    }
                });  

                return false;
            });
        </script>

    <?php
}


function _createtextfile($todays_date,$link)
{
    ?>
        <div class="row form-container">
            <div class="col-md-12">
                <div class="panel with-nav-tabs panel-info">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">GC Textfile Creator</a></li>
                        </ul>                       
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab1default">
                                <div class="row form-container">
                                    <form action="../ajax.php?action=createtextfile" method="POST" id="createtextfile" enctype="multipart/form-data">                  
                                        <div class="col-sm-12">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label class="nobot">Date Created:</label>
                                                    <input type="text" class="form form-control inptxt input-sm ro bot-6" readonly="readonly" required value="<?php echo _dateFormat($todays_date); ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label class="nobot">Textfile #:</label>
                                                    <input type="text" class="form form-control inptxt input-sm ro bot-6" readonly="readonly" value="<?php echo getManualNumberGC($link,'textfile'); ?>" required>
                                                </div> 
                                            </div>
                                            <div class="col-sm-4">                                    

                                                <div class="form-group">
                                                    <label class="nobot">Remarks:</label>
                                                    <textarea name="remarks" id="remarks" class="form form-control inptxt input-sm" required></textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label class="nobot">GC Barcode #:</label>
                                                    <input type="text" name="barcode" id="barcode" class="form form-control inptxt input-lg ro bot-6" required maxlength="13" style="font-size: x-large;" autocomplete="off">
                                                </div>

                                                <div class="response-form">
                                                </div>

                                                <input type="hidden" name="submitstat" id="submitstat" value="1">
                                                <input type="hidden" name="barcodenum" id="barcodenum" value="0">

                                                <div class="form-group">
                                                    <div class="col-sm-5">
                                                        <button type="submit" class="btn btn-block btn-primary" id="btn-search"> <span class="glyphicon glyphicon-share" aria-hidden="true"></span> Search</button>
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <button type="submit" class="btn btn-block btn-primary" id="btn-create" disabled="disabled"> <span class="glyphicon glyphicon-share" aria-hidden="true"></span> Create Textfile</button>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-5">

                                                <?php                                                    
                                                    if(!file_exists("\\\\172.16.161.205\\CFS_Txt\\Giftcheck")):?>
                                                <div class="alert alert-danger">Cannot connect to textfile server.</div>
                                                <?php endif; ?>
                                                <div class="customerdetails form-horinzontal">
                                                    <i class="fa fa-user"></i>
                                                    Customer Details
                                                </div>
                                                <div class="customerdetails-container form-horizontal">
                                                    <input type="hidden" name="cusid" value="" id="cid">
                                                    <div class="form-group">
                                                        <label class="col-xs-5 control-label">BU:</label>
                                                        <div class="col-xs-7">
                                                            <input type="text" class="form-control inptxt input-xs" id="store" readonly="readonly">                      
                                                        </div>
                                                    </div><!-- end of form-group -->
                                                    <div class="form-group">
                                                        <label class="col-xs-5 control-label">Barcode #:</label>
                                                        <div class="col-xs-7">
                                                            <input type="text" class="form-control inptxt input-xs" id="barcoded" readonly="readonly">                      
                                                        </div>
                                                    </div><!-- end of form-group -->
                                                    <div class="form-group">
                                                        <label class="col-xs-5 control-label">Denomination:</label>
                                                        <div class="col-xs-7">
                                                            <input type="text" class="form-control inptxt input-xs" id="denomination" readonly="readonly">                      
                                                        </div>
                                                    </div><!-- end of form-group -->
                                                    <div class="form-group">
                                                        <label class="col-xs-5 control-label">First Name:</label>
                                                        <div class="col-xs-7">
                                                            <input type="text" class="form-control inptxt input-xs" id="fname" readonly="readonly">                      
                                                        </div>
                                                    </div><!-- end of form-group -->
                                                    <div class="form-group">
                                                        <label class="col-xs-5 control-label">Last Name:</label>
                                                        <div class="col-xs-7">
                                                        <input type="text" class="form-control inptxt input-xs" id="lname" readonly="readonly">                      
                                                    </div>
                                                    </div><!-- end of form-group -->
                                                    <div class="form-group">
                                                        <label class="col-xs-5 control-label">Middle Name:</label>
                                                        <div class="col-xs-7">
                                                            <input type="text" class="form-control inptxt input-xs" id="mname" readonly="readonly">                      
                                                        </div>
                                                    </div><!-- end of form-group -->
                                                    <div class="form-group">
                                                        <label class="col-xs-5 control-label">Name Ext:</label>
                                                        <div class="col-xs-7">
                                                            <input type="text" class="form-control inptxt input-xs" id="next" readonly="readonly">                      
                                                        </div>
                                                    </div><!-- end of form-group -->        
                                                </div>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
        
            $('.form-container').on('submit','form#createtextfile',function(event){
                var formURL = $(this).attr('action'), formData = $(this).serialize();

                event.preventDefault();
                $('.response-form').html('');      

                if($('#remarks').val()=='' || $('#barcode').val()=='') 
                {
                    $('.response-form').html('<div class="alert alert-danger" id="danger-x">Please input remarks and barcode #.</div>');
                    return false;
                }

                var stat = $('#submitstat').val();


                // check if GC exist
                if(stat==1)
                {
                    $('button#btn-search').prop('disabled',true);
                    $.ajax({
                        url:'../ajax.php?action=createtextfilesearch',
                        data:formData,
                        type:'POST',
                        beforeSend:function(){
                        },
                        success:function(data){
                            console.log(data);
                            var data = JSON.parse(data);

                            if(data['st'])
                            {
                                $('#store').val(data['store']);
                                $('#lname').val(data['lname']);
                                $('#fname').val(data['fname']);
                                $('#mname').val(data['mname']);
                                $('#denomination').val(data['denom']);
                                $('#barcoded').val($('#barcode').val());
                                $('#barcodenum').val($('#barcode').val());
                                $('button#btn-create').prop('disabled',false);
                                $('input#barcode').prop('disabled',true);

                                $('#submitstat').val(2);
                            }
                            else 
                            {
                                $('.response-form').html('<div class="alert alert-danger" id="danger-x">'+data['msg']+'</div>');
                                 $('button#btn-search').prop('disabled',false);
                            }
                        }
                    });                 
                    return false;
                }

                if(stat==2)
                {

                    BootstrapDialog.show({
                        title: 'Confirmation',
                        message: 'Create Textfile?',
                        closable: true,
                        closeByBackdrop: false,
                        closeByKeyboard: true,
                        onshow: function(dialog) {
                            // dialog.getButton('button-c').disable();
                            $('button#btn-create').prop('disabled',true);
                        },
                        onhidden:function(dialog){
                             $('button#btn-create').prop('disabled',false);
                        },
                        buttons: [{
                            icon: 'glyphicon glyphicon-ok-sign',
                            label: 'Yes',
                            cssClass: 'btn-primary',
                            hotkey: 13,
                            action:function(dialogItself){
                                $buttons = this;
                                $buttons.disable();                 
                                dialogItself.close();
                                    $.ajax({
                                        url:'../ajax.php?action=createtextfile',
                                        data:formData,
                                        type:'POST',
                                        beforeSend:function(){
                                        },
                                        success:function(data){
                                            console.log(data);
                                            var data = JSON.parse(data);

                                            if(data['st'])
                                            {
                                                dialogItself.close();
                                                var dialog = new BootstrapDialog({
                                                message: function(dialogRef){
                                                var $message = $('<div>Textfile Successfully Created.</div>');                   
                                                    return $message;
                                                },
                                                closable: false
                                                });
                                                dialog.realize();
                                                dialog.getModalHeader().hide();
                                                dialog.getModalFooter().hide();
                                                dialog.getModalBody().css('background-color', '#0088cc');
                                                dialog.getModalBody().css('color', '#fff');
                                                dialog.open();
                                                setTimeout(function(){
                                                    dialog.close();
                                                }, 1500);
                                                setTimeout(function(){
                                                    window.location.reload();
                                                }, 1700); 
                                            }
                                            else 
                                            {
                                                $('.response-form').html('<div class="alert alert-danger" id="danger-x">'+data['msg']+'</div>');
                                                $('button#btn-search').prop('disabled',false);
                                                dialogItself.close();
                                            }
                                        }
                                    });
                            }
                        }, {
                            icon: 'glyphicon glyphicon-remove-sign',
                            label: 'No',
                            action: function(dialogItself){
                                dialogItself.close();
                            }
                        }]
                    });                   
               
                    return false;
                }




                return false;
            });
        </script>

    <?php
}

function _verifygcmanual($todays_date,$link)
{
    $stores = getStores($link);

    ?>
        <div class="row form-container">
            <div class="col-md-12">
                <div class="panel with-nav-tabs panel-info">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Verify GC (Manual)</a></li>
                        </ul>                       
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab1default">
                                <div class="row form-container">
                                    <form action="../ajax.php?action=manualverifygc" method="POST" id="manualverify" enctype="multipart/form-data">                  
                                        <div class="col-sm-12">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label class="nobot">Manual Date:</label>
                                                    <input type="text" class="form form-control inptxt input-sm ro bot-6" readonly="readonly" required value="<?php echo _dateFormat($todays_date); ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label class="nobot">Verify GC Manual #:</label>
                                                    <input type="text" class="form form-control inptxt input-sm ro bot-6" readonly="readonly" value="<?php echo getManualNumberGC($link,'verification'); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="nobot">Date Verified:</label>
                                                    <input type="text" name="dateverified" id="dateverified" class="form form-control inptxt input-sm ro toverifieddate" id="" data-date-format="MM dd, yyyy" readonly="readonly" required>              
                                                </div>
                                                <div class="form-group">
                                                    <label class="nobot">Verification Mode:</label>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="verifymode" id="verifymode1" value="verifymodecurrent">
                                                            Current Date
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="verifymode" id="verifymode2" value="verifymodedatewoutr" checked="checked">
                                                            Date Verified
                                                        </label>
                                                    </div> 
                                                </div>   
                                                <div class="form-group">
                                                    <label class="nobot">Balance:</label>
                                                    <input type="text" id="balance" autocomplete="off" value="0.00" name="balance" class="form form-control inptxt"  data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false">
                                                </div> 
                                            </div>
                                            <div class="col-sm-4">

                                                <div class="form-group">
                                                    <label class="nobot">BU:</label>
                                                    <select class="form form-control inptxt input-sm" name="bu" id="bu" required>
                                                        <option value="">-Select-</option>
                                                        <?php foreach ($stores as $st): ?>
                                                             <option value="<?php echo $st->store_id; ?>"><?php echo $st->store_name; ?></option>
                                                        <?php endforeach; ?>                                                       
                                                    </select>
                                                </div>   

                                                <div class="form-group">
                                                    <label class="nobot">Textfile Folder:</label>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="txtfolder" id="txtfolder1" value="txtarchive">
                                                            Archive
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="txtfolder" id="txtfolder2" value="txtstore" checked="checked">
                                                            Store Textfile Folder
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="txtfolder" id="txtfolder2" value="txtcustom" checked="checked">
                                                            Custom Folder (Server Folder)
                                                        </label>
                                                    </div>
                                                </div>                                      

                                                <div class="form-group">
                                                    <label class="nobot">Remarks:</label>
                                                    <textarea name="remarks" id="remarks" class="form form-control inptxt input-sm" required></textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label class="nobot">GC Barcode #:</label>
                                                    <input type="text" name="barcode" id="barcode" class="form form-control inptxt input-lg ro bot-6" required maxlength="13" style="font-size: x-large;" autocomplete="off">
                                                </div>

                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-block btn-default" onclick="addnewcustomer();"><i class="fa fa-user-plus"></i> Add New Customer</button>
                                                </div>
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-block btn-default" onclick="lookupcustomer();"><i class="fa fa-search"></i> Customer Lookup</button>
                                                </div>
                                                <div class="customerdetails form-horinzontal">
                                                    <i class="fa fa-user"></i>
                                                    Customer Details
                                                </div>
                                                <div class="customerdetails-container form-horizontal">
                                                    <input type="hidden" name="cusid" value="" id="cid">
                                                    <div class="form-group">
                                                        <label class="col-xs-5 control-label">First Name:</label>
                                                    <div class="col-xs-7">
                                                        <input type="text" class="form-control inptxt input-xs" id="fname" readonly="readonly">                      
                                                        </div>
                                                    </div><!-- end of form-group -->
                                                    <div class="form-group">
                                                        <label class="col-xs-5 control-label">Last Name:</label>
                                                        <div class="col-xs-7">
                                                        <input type="text" class="form-control inptxt input-xs" id="lname" readonly="readonly">                      
                                                    </div>
                                                    </div><!-- end of form-group -->
                                                    <div class="form-group">
                                                        <label class="col-xs-5 control-label">Middle Name:</label>
                                                        <div class="col-xs-7">
                                                            <input type="text" class="form-control inptxt input-xs" id="mname" readonly="readonly">                      
                                                        </div>
                                                    </div><!-- end of form-group -->
                                                    <div class="form-group">
                                                        <label class="col-xs-5 control-label">Name Ext:</label>
                                                        <div class="col-xs-7">
                                                            <input type="text" class="form-control inptxt input-xs" id="next" readonly="readonly">                      
                                                        </div>
                                                    </div><!-- end of form-group -->        
                                                </div>
                                                <div class="response-form">
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-sm-offset-5 col-sm-7">
                                                        <button type="submit" class="btn btn-block btn-primary" id="btn"> <span class="glyphicon glyphicon-share" aria-hidden="true"></span> Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(".toverifieddate").datepicker({
                format: 'MM d, yyyy',
                autoclose: true,
            });

            $('#verifymode').click(function(){
                alert($(this).val());
            });

            $('#balance').inputmask();

            $(".radio input[name='verifymode']").click(function(){
                if($('input:radio[name=verifymode]:checked').val() == "verifymodecurrent")
                {
                    $('#balance').prop('readonly','readonly');   
                    $('#balance').val('0.00');                 
                }
                else 
                {
                    $('#balance').prop('readonly','');
                }
            });

            $('.form-container').on('submit','form#manualverify',function(event){
                var formURL = $(this).attr('action'), formData = $(this).serialize();

                event.preventDefault();
                $('.response-form').html('');
                // alert(formData);
                // alert(formURL);

                if($('#dateverified').val().trim()=='')
                {
                    $('.response-form').html('<div class="alert alert-danger" id="danger-x">Please select date verified.</div>');
                    return false;
                }

                if($('#remarks').val().trim()=='')
                {
                    $('.response-form').html('<div class="alert alert-danger" id="danger-x">Please input remarks.</div>');
                    return false;
                }

                if($('#barcode').val().trim()=='')
                {
                    $('.response-form').html('<div class="alert alert-danger" id="danger-x">Please input GC Barcode #.</div>');
                    return false;
                }

                if($('#cid').val().trim()=='')
                {
                    $('.response-form').html('<div class="alert alert-danger" id="danger-x">Please select customer.</div>');
                    return false;
                }       

                if($('#bu').val().trim()=='')
                {
                    $('.response-form').html('<div class="alert alert-danger" id="danger-x">Please select Business Unit.</div>');
                    return false;                    
                }

                BootstrapDialog.show({
                    title: 'Confirmation',
                    message: 'Proceed?',
                    closable: true,
                    closeByBackdrop: false,
                    closeByKeyboard: true,
                    onshow: function(dialog) {
                        // dialog.getButton('button-c').disable();
                        $('#btn').prop("disabled",true);
                    },
                    onhidden:function(dialog){
                        $('#btn').prop("disabled",false);
                    },
                    buttons: [{
                        icon: 'glyphicon glyphicon-ok-sign',
                        label: 'Yes',
                        cssClass: 'btn-primary',
                        hotkey: 13,
                        action:function(dialogItself){
                            $buttons = this;
                            $buttons.disable();                 
                            dialogItself.close();
                                $.ajax({
                                    url:formURL,
                                    data:formData,
                                    type:'POST',
                                    beforeSend:function(){
                                    },
                                    success:function(data){
                                        console.log(data);
                                        var data = JSON.parse(data);

                                        if(data['st'])
                                        {
                                            dialogItself.close();
                                            var dialog = new BootstrapDialog({
                                            message: function(dialogRef){
                                            var $message = $('<div>Manual Verification Saved.</div>');                   
                                                return $message;
                                            },
                                            closable: false
                                            });
                                            dialog.realize();
                                            dialog.getModalHeader().hide();
                                            dialog.getModalFooter().hide();
                                            dialog.getModalBody().css('background-color', '#0088cc');
                                            dialog.getModalBody().css('color', '#fff');
                                            dialog.open();
                                            setTimeout(function(){
                                                dialog.close();
                                            }, 1500);
                                            setTimeout(function(){
                                                $('#barcode').val("").focus();

                                            }, 1700);                                           
                                        }
                                        else 
                                        {
                                            $('.response-form').html('<div class="alert alert-danger" id="danger-x">'+data['msg']+'</div>');
                                            $buttons.enable();
                                        }
                                    }
                                });
                        }
                    }, {
                        icon: 'glyphicon glyphicon-remove-sign',
                        label: 'No',
                        action: function(dialogItself){
                            dialogItself.close();
                        }
                    }]
                });



                return false;
            });

            function lookupcustomer()
            {
                BootstrapDialog.show({
                    title: '<i class="fa fa-user"></i> Customer List',
                    cssClass: 'customer-details',
                    closable: true,
                    closeByBackdrop: false,
                    closeByKeyboard: true,
                    message: function(dialog) {
                        var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
                        var pageToLoad = dialog.getData('pageToLoad');
                        setTimeout(function(){
                        $message.load(pageToLoad);
                        },1000);
                        return $message;
                    },
                    data: {
                        'pageToLoad': '../dialogs/customerdetails.php'
                    },
                    onshown: function(dialogRef){
                    },
                    buttons: [{
                        icon: 'glyphicon glyphicon-remove-sign',
                        label: 'Close',
                        action: function(dialogItself){
                            dialogItself.close();
                        }
                    }]
                }); 
            }

            function addnewcustomer()
            {
                BootstrapDialog.show({
                    title: '<i class="fa fa-user"></i> Customer Form',
                    cssClass: 'add-newuser',
                    closable: true,
                    closeByBackdrop: false,
                    closeByKeyboard: false,
                    message: function(dialog) {
                        var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
                        var pageToLoad = dialog.getData('pageToLoad');
                        setTimeout(function(){
                        $message.load(pageToLoad);
                        },1000);
                        return $message;
                    },
                    data: {
                        'pageToLoad': '../dialogs/addnewcustomer.php'
                    },
                    onshown: function(dialogRef){               
                        setTimeout(function(){
                        },1010);
                    },
                    buttons: [{
                        icon: 'glyphicon glyphicon-ok-sign',
                        label: 'Submit',
                        cssClass: 'btn-primary',
                        hotkey: 13,
                        action:function(dialogItself){
                            $buttons = this;
                            $buttons.disable();
                            //addnewcustomer
                            var formUrl = $('.form-container form#customer-info').attr('action');
                            var formData = $('.form-container form#customer-info').serialize();
                            var noError = true;
                            var errormsg = [];
                            $('.reqfield').each(function(){
                                if($(this).val().trim()=='')                         
                                {
                                    noError = false;
                                    errormsg.push('Please fill form.');
                                    return false;
                                }
                            });

                            if($('#dob').val().trim()!='')
                            {

                                if(!validateDOB($('#dob').val()))
                                {
                                    noError = false;
                                    errormsg.push('Date of Birth is invalid.');

                                }                   
                            }

                            if($('input[name=exist]').val()==1)
                            {
                                noError = false;
                                errormsg.push($('#cusfname').val()+' '+$('#mname').val()+' '+$('#lname').val()+' already exist.');  
                    
                            } 

                            if(noError)
                            {
                                BootstrapDialog.show({
                                    title: 'Confirmation',
                                    message: 'Are you sure you want add this customer?',
                                    closable: true,
                                    closeByBackdrop: false,
                                    closeByKeyboard: true,
                                    onshow: function(dialog) {
                                        // dialog.getButton('button-c').disable();
                                    },
                                    buttons: [{
                                        icon: 'glyphicon glyphicon-ok-sign',
                                        label: 'Yes',
                                        cssClass: 'btn-primary',
                                        hotkey: 13,
                                        action:function(dialogItself){ 
                                            var $buttons1 = this;
                                            $buttons1.disable();                
                                            $('.response').html('');
                                            $.ajax({
                                                url:formUrl,
                                                type:'POST',
                                                data:formData,
                                                beforeSend:function(){

                                                },
                                                success:function(response){
                                                    console.log(response);
                                                    var res = response.trim();
                                                    if(res=='success'){
                                                        BootstrapDialog.closeAll();
                                                        var dialog = new BootstrapDialog({
                                                        message: function(dialogRef){
                                                        var $message = $('<div>Customer successfully added.</div>');                    
                                                            return $message;
                                                        },
                                                        closable: false
                                                        });
                                                        dialog.realize();
                                                        dialog.getModalHeader().hide();
                                                        dialog.getModalFooter().hide();
                                                        dialog.getModalBody().css('background-color', '#0088cc');
                                                        dialog.getModalBody().css('color', '#fff');
                                                        dialog.open();
                                                        setTimeout(function(){
                                                            dialog.close();
                                                        }, 1500);                     
                                                    } else {
                                                        alert(res);
                                                    }
                                                }
                                            });
                                        }
                                    }, {
                                        icon: 'glyphicon glyphicon-remove-sign',
                                        label: 'No',
                                        action: function(dialogItself){
                                            dialogItself.close();
                                            $buttons.enable();
                                        }
                                    }]
                                });
                            }
                            else 
                            {
                                var erromsg = '';
                                for(i=0; i<(errormsg.length); i++)
                                {
                                    // erromsg+'<li>'+errormsg[i]+'</li>';
                                   erromsg += '<li class="leftpad0">'+errormsg[i]+'</li>';
                                }
                                $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot"><ul class="ulleftpad14">'+erromsg+'</ul></div>');                                                                  
                                $('#cusfname').focus();
                                $buttons.enable();
                            }
                        }
                    }, {
                        icon: 'glyphicon glyphicon-remove-sign',
                        label: 'Close',
                        action: function(dialogItself){
                            dialogItself.close();
                        }
                    }]

                });
            }




        </script>

    <?php
}


function _setAsUsedGC($link, $gc)
{
    $den = getField($link,'vs_tf_denomination','store_verification','vs_barcode',$gc);

    ?>
        <div class="row row-nobot">
            <div class="col-md-12 form-container">
                <form class="form-horizontal" action="../ajax.php?action=setasredeemedsgc" id="_setasusegc">
                    <div class="form-group">
                        <label class="col-sm-5 control-label">GC Barcode #:</label>
                        <div class="col-sm-7">
                            <input type="hidden" value="<?php echo $gc; ?>" name="barcode">
                            <input type="text" class="form-control formbot reqfield input-sm" value="<?php echo $gc; ?>" readonly="readonly">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label">Denomination:</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control formbot reqfield input-sm" value="<?php echo $den; ?>" readonly="readonly">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label"><span class="requiredf">*</span>Balance:</label>
                        <div class="col-sm-4">
                            <input type="text" id="balance" name="balance" autocomplete="off" data-inputmask="'alias': 'numeric','digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0','allowMinus':false" class="form form-control inptxt reqfield" autofocus maxlength="13" value="0.00">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label"><span class="requiredf">*</span>Remarks:</label>
                        <div class="col-sm-7">
                            <textarea type="text" id="note" name="note" autocomplete="off" class="form form-control inptxt reqfield"></textarea>
                        </div>
                    </div>

                    <div class="response">
                    </div>
                </form>
            </div>
        </div>
        <script>
            $('#balance').inputmask();            
            
        </script>
    <?php
}

function _verifiedgcnotranx($link)
{
    $table = 'store_verification';
    $select = 'store_verification.vs_tf_denomination,
        store_verification.vs_barcode,
        store_verification.vs_date,
        store_verification.vs_tf_balance,
        stores.store_name';
    $where = "store_verification.vs_tf_balance!='0.00'
        AND
            store_verification.vs_tf_eod!=''
        ORDER BY
            store_verification.vs_id
        DESC";
    $join = 'INNER JOIN
            stores
        ON
            stores.store_id = store_verification.vs_store';
    $limit='';
    $data = getAllData($link,$table,$select,$where,$join,$limit);

    ?>
        <div class="row form-container">
            <div class="col-md-12">
                <div class="panel with-nav-tabs panel-info">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Verified GC (No Transaction)</a></li>
                        </ul>                       
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab1default">
                                <div class="row form-container"> 
                                    <div class="col-sm-8">
                                        <table class="table" id="vergc">
                                            <thead>
                                                <th>Barcode</th>
                                                <th>Denomination</th>
                                                <th>Date Verified</th>
                                                <th>Store Verified</th>
                                                <th>Balance</th>
                                                <th>Action</th>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($data as $d): 
                                                    if($d->vs_tf_balance===$d->vs_tf_denomination):
                                                ?>

                                                    <tr>
                                                        <td><?php echo $d->vs_barcode; ?></td>
                                                        <td><?php echo $d->vs_tf_denomination; ?></td>
                                                        <td><?php echo _dateFormat($d->vs_date); ?></td>
                                                        <td><?php echo $d->store_name; ?></td>
                                                        <td><?php echo $d->vs_tf_balance; ?></td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                                    <i class="fa fa-cog" aria-hidden="true"></i>
                                                                    <span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu " aria-labelledby="dropdownMenu1">
                                                                    <li class="sused" data-barcode="<?php echo $d->vs_barcode; ?>"><a href="#"><i class="fa fa-tasks" aria-hidden="true"></i> Set as Redeemed</a></li>
                                                                    <li class="strans" data-barcode="<?php echo $d->vs_barcode; ?>"><a href="#"><i class="fa fa-tags" aria-hidden="true"></i> Set as Redeemed with transaction</a></li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php 
                                                    endif;
                                                    endforeach; 
                                                ?>
                                            </tbody>
                                        </table>
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">

            $.extend( $.fn.dataTableExt.oStdClasses, {    
                "sLengthSelect": "selectsup"
            });
            $('#vergc').dataTable( {
                "pagingType": "full_numbers",
                "ordering": false,
                "processing": true,
                "bProcessing":true
            });

            $('div.form-container').on('click','div.dropdown ul.dropdown-menu li.sused',function(e){
                //$(this).dropdown("toggle");
                $(this).parent().parent().find('button.dropdown-toggle').dropdown("toggle");
                var barcode = $(this).attr('data-barcode');
                BootstrapDialog.show({
                    title: '<i class="fa fa-tasks" aria-hidden="true"></i> Set GC as Redeemed',
                    closable: true,
                    closeByBackdrop: false,
                    closeByKeyboard: false,
                    cssClass: 'store-staff-dialog',
                    message: function(dialog) {
                        var $message = $("<div><img src='../assets/images/ajax.gif'> <small class='text-danger'>please wait...</small></div>");
                        var pageToLoad = dialog.getData('pageToLoad');
                        setTimeout(function(){
                        $message.load(pageToLoad);
                        },1000);
                        return $message;
                    },
                    data: {
                        'pageToLoad': '../templates/admin.php?page=setasusegc&gcnum='+barcode
                    },
                    onshow: function(dialog) {
                        // dialog.getButton('button-c').disable();
                    },
                    onshown: function(dialog){
                        //pendinggc
                        setTimeout(function(){
                            $('#balance').select();
                        },1200);
                    },
                    buttons: [{
                        icon: 'glyphicon glyphicon-ok-sign',
                        label: 'Save',
                        cssClass: 'btn btn-primary',
                        hotkey: 13,
                        action:function(dialogIt){
                            var $buttons = this;
                            $buttons.disable();
                            $('.response').html('');
                            if($('input[name=balance]').val()!=undefined)
                            {
                                if($('textarea[name=note]').val().trim()=='')
                                {
                                    $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">Please fill in all fields.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
                                    $buttons.enable();  
                                    return false;
                                }
                                    var formData = $('form#_setasusegc').serialize(), formURL = $('form#_setasusegc').attr('action');
                                    BootstrapDialog.show({
                                        title: 'Confirmation',
                                        message: 'Set as Redeemed?',
                                        closable: true,
                                        closeByBackdrop: false,
                                        closeByKeyboard: true,
                                        onshow: function(dialog) {                            
                                           
                                        },
                                        buttons: [{
                                            icon: 'glyphicon glyphicon-ok-sign',
                                            label: 'Yes',
                                            cssClass: 'btn-primary',
                                            hotkey: 13,
                                            action:function(dialogItself){
                                                $buttonconfirm = this;
                                                $buttonconfirm.disable();
                                                $.ajax({
                                                    url:formURL,
                                                    data:formData,
                                                    type:'POST',
                                                    success:function(data)
                                                    {
                                                        console.log(data);
                                                        var data = JSON.parse(data);
                                                        if(data['st'])
                                                        {
                                                            BootstrapDialog.closeAll();
                                                            var dialog = new BootstrapDialog({
                                                            message: function(dialogRef){
                                                            var $message = $('<div>'+barcode+' Successfully Set.</div>');                  
                                                                return $message;
                                                            },
                                                            closable: false
                                                            });
                                                            dialog.realize();
                                                            dialog.getModalHeader().hide();
                                                            dialog.getModalFooter().hide();
                                                            dialog.getModalBody().css('background-color', '#0088cc');
                                                            dialog.getModalBody().css('color', '#fff');
                                                            dialog.open();

                                                            setTimeout(function(){
                                                                window.location.reload();
                                                            }, 1700);                                                                
                                                        }
                                                        else 
                                                        {
                                                            dialogItself.close();
                                                            $buttons.enable();
                                                            $('.response').html('<div class="alert alert-danger alert-dismissable alert-no-bot">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');
                                                            $buttons.enable();   
                                                        }

                                                    }
                                                });
                                            }                                        
                                        }, {
                                                icon: 'glyphicon glyphicon-remove-sign',
                                                label: 'No',
                                                action: function(dialogItself){
                                                    dialogItself.close();
                                                    $buttons.enable();
                                            }
                                        }]
                                    });                                         


                            }  
                            else
                            {
                                $buttons.enable();
                            }
                                         
                        }
                    }, {
                        icon: 'glyphicon glyphicon-remove-sign',
                        label: 'Close',
                        action: function(dialogItself){
                            dialogItself.close();
                        }
                    }]

                });          

                e.stopPropagation();
                e.preventDefault();
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
