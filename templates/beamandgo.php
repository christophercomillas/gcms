<script src="../assets/js/funct.js"></script>
<?php
session_start();
include '../function.php';

if(isset($_GET['page']))
{
	$page = $_GET['page'];

	if($page=='beamandgoconversion')
	{
		_beamandgoconversion($link,$todays_date);
	}
    elseif($page=='scanGCForCustomerBNG')
    {
        $gctoscan = 0;
        if(isset($_GET['gctoscan']))
        {
            $gctoscan = $_GET['gctoscan'];
        }
        _scanGCForCustomerBNG($link,$gctoscan);
    }                      
	else 
	{
		//last
		echo 'Something went wrong.';
	}
}

function _beamandgoconversion($link,$todays_date)
{
    //get all valid ID
    if(isset($_SESSION['scanForBNGCustomerGC']))
        unset($_SESSION['scanForBNGCustomerGC']);
        //var_dump($_SESSION['scanForBNGCustomerGC']);

    $table = 'valid_id';
    $select = 'validid_id, validid_name';
    $where = '1';
    $join = '';
    $limit = '';
    $idtype = getAllData($link,$table,$select,$where,$join,$limit);

    $trnum = getBeamAndGoTRNum($link,$_SESSION['gc_store']);

    //get all transactions

    $where = "beamandgo_transaction.bngver_storeid='".$_SESSION['gc_store']."'";
    $select = "     beamandgo_transaction.bngver_datetime,
        beamandgo_barcodes.bngbar_barcode,
        beamandgo_barcodes.bngbar_serialnum,
        beamandgo_transaction.bngver_trnum,
        beamandgo_barcodes.bngbar_refnum,
        beamandgo_barcodes.bngbar_value,
        beamandgo_barcodes.bngbar_beneficiaryname";
    $join = "INNER JOIN
            beamandgo_transaction
        ON
            beamandgo_transaction.bngver_id = beamandgo_barcodes.bngbar_trid";
    $limit ='ORDER BY beamandgo_barcodes.bngbar_id DESC';
    $data = getAllData($link,'beamandgo_barcodes',$select,$where,$join,$limit);    

    ?>
        <div class="row form-container">
            <div class="col-md-12">
                <div class="panel with-nav-tabs panel-info">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Beam And Go Conversion</a></li>
                            <li><a href="#tab2default" data-toggle="tab" style="font-weight:bold">Beam And Go Transactions</a></li>
                        </ul>                       
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab1default">                              
                                <div class="row form-container">
                                    <form method="POST" id="_bngTransaction" enctype="multipart/form-data" action="../ajax.php?action=savebngTransaction" >
                                        <div class="col-sm-12">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label class="nobot">Date</label>   
                                                    <input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo _dateFormat($todays_date); ?>">   
                                                </div>
                                                <div class="form-group">
                                                    <label class="nobot"><span class="requiredf">*</span>Transaction #</label>   
                                                    <input type="text" class="form inptxt form-control" readonly="readonly" value="<?php echo $trnum; ?>" id="trnum">
                                                </div>
                                                <div class="form-group">
                                                    <label class="nobot"><span class="requiredf">*</span>Total Amount</label>   
                                                    <input type="text" class="form inptxt form-control" readonly="readonly" value="0" id="totamt">
                                                </div>
                                                <div class="form-group">
                                                    <label class="nobot"><span class="requiredf">*</span>GC Scanned</label>   
                                                    <input type="text" class="form inptxt form-control" readonly="readonly" value="0" id="gcscanned">
                                                </div>
                                                <div class="form-group">
                                                    <label class="nobot">Prepared By</label>   
                                                    <input type="text" class="form form-control inptxt input-sm bot-6" readonly="readonly" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>" name="" id="">  
                                                </div>   
                                                <div class="form-group">
                                                    <button class="btn btn-block btn-info fordialog" id="_scangcbng" type="button"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Scan GC</button>
                                                </div>    
                                                <div class="form-group">
                                                    <div class="file-upload">
                                                        <label for="upload" class="file-upload__label"> <span class="glyphicon glyphicon-cloud-upload" aria-hidden="true"></span> Upload File</label>
                                                        <input id="upload" class="file-upload__input" type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" name="file-upload">
                                                    </div>
                                                </div>                                       
                                                <div class="form-group">
                                                    <button class="btn btn-primary btn-block" id="btnBNGSub"><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span> Submit</button>  
                                                </div>
                                                <div class="response">

                                                </div>

                                            </div> 
                                            <div class="col-sm-9">                             
                                                <table class="table" id="_bgnscangc">
                                                    <thead>
                                                        <tr>
                                                            <th>Serial #</th>
                                                            <th>Amount</th>
                                                            <th>Barcode</th> 
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>                                             
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab2default">
                                <table class="table" id="_bgnscangclist">
                                    <thead>
                                        <tr>
                                            <th>Serial #</th>
                                            <th>Ref #</th>
                                            <th>Beneficiary</th>
                                            <th>Barcode</th>
                                            <th>Amount</th>     
                                            <th>Date</th>                                        
                                        </tr>
                                    </thead>
                                    <tbody>     
                                        <?php foreach ($data as $key): ?>
                                            <tr>
                                                <td><?php echo $key->bngbar_serialnum; ?></td>
                                                <td><?php echo $key->bngbar_refnum; ?></td>
                                                <td><?php echo strtoupper($key->bngbar_beneficiaryname); ?></td>
                                                <td><?php echo $key->bngbar_barcode; ?></td>
                                                <td><?php echo number_format($key->bngbar_value,2); ?></td>
                                                <td><?php echo _dateFormat($key->bngver_datetime); ?></td>
                                            </tr>
                                        <?php endforeach ?>                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">       
             $.extend( $.fn.dataTableExt.oStdClasses, {    
                "sFilterInput": "searchcus"
            });

            $('#_bgnscangc, #_bgnscangclist').DataTable( {
                "order": [[ 0, "desc" ]]
            } ); 

            $('input#upload').change(function(e) {
                e.preventDefault(); 

                var t = $('#_bgnscangc').DataTable();
                var counter = 1;

                $('#totamt').val(0);   
                $('#totamt').val(0);   
                $('#gcscanned').val(0);


                t.clear().draw();

                $('.response').html('');

                var formData = new FormData();
                formData.append('file', $('#upload')[0].files[0]);

                var value = $(this).val();
                var allowedExtensions = ["xlsx"];
                file = value.toLowerCase(),
                extension = file.substring(file.lastIndexOf('.') + 1);
                if ($.inArray(extension, allowedExtensions) == -1) 
                {
                    $('.response').html('<div class="alert alert-danger">Invalid File.</div>');
                    return false;
                } 

                $.ajax({
                    url : '../ajax.php?action=getbngexceldata',
                    type : 'POST',
                    data : formData,
                    processData: false,  // tell jQuery not to process the data
                    contentType: false,  // tell jQuery not to set contentType
                    success : function(data) {
                        console.log(data);
                        var data = JSON.parse(data);
                        if(data['st'])
                        {
                            $('#totamt').val(data['totamt']);                        
                            for (var i = 0; i < data['data'].length; i++) {

                                var counter = 1;
                                t.row.add( [                    
                                    data['data'][i]['sernum'],
                                    data['data'][i]['value'],
                                    '',
                                    '<input type="hidden" value="'+data['data'][i]['sernum']+'" class="serial"><i class="fa fa-times remove-employee" aria-hidden="true"></i>'
                                ] ).draw( false );
                                
                                counter++;
                                //alert(data['data'][i]['refnum']);
                            };   
                        }
                        else 
                        {
                            $('.response').html('<div class="alert alert-danger alert-danger-dialog">'+data['msg']+'</div>');
                            $('#totamt').val(0);   
                        } 
                    }
                });

            });
            
            $('#_scangcbng').click(function(){
                $('.response').html('');
                var t = $('#_bgnscangc').DataTable();
                var counter = 1;

                $.ajax({
                    url : '../ajax.php?action=checkGCToSCanBNG',
                    processData: false,  // tell jQuery not to process the data
                    contentType: false,  // tell jQuery not to set contentType
                    success : function(data) {
                        console.log(data);
                        var data = JSON.parse(data);

                        if(parseInt(data['gctoscan'])> 0)
                        {
                            BootstrapDialog.show({
                                title: 'Scan GC',
                                cssClass: 'customer-internal',
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
                                    'pageToLoad': '../templates/beamandgo.php?page=scanGCForCustomerBNG&gctoscan='+data['gctoscan']
                                },
                                onshown: function(dialogRef){

                                },
                                onhidden:function(dialogRef){
                                    t.clear()
                                        .draw();  
                                    $.ajax({
                                        url:'../ajax.php?action=getBNGScanBarcode',
                                        success:function(data){
                                            //$('#gctoscan').val(0);
                                            console.log(data);
                                            var data = JSON.parse(data);                                            
                                            for (var i = 0; i < data['data'].length; i++) {

                                                var counter = 1;               
                                                t.row.add( [                    
                                                    data['data'][i]['sernum'],
                                                    data['data'][i]['value'],
                                                    data['data'][i]['barcode'],
                                                    '<input type="hidden" value="'+data['data'][i]['sernum']+'" class="serial"><i class="fa fa-times remove-employee" aria-hidden="true"></i>'
                                                ] ).draw( false );
                                                
                                                counter++;
                                                //alert(data['data'][i]['refnum']);
                                            }; 
                                        }
                                    });     

                                },        
                                buttons: [{
                                    icon: 'glyphicon glyphicon-ok-sign',
                                    label: 'Submit',
                                    cssClass: 'btn-primary',
                                    hotkey: 13,
                                    action:function(dialogItself){
                                        $('.response-validate').html('');
                                        var t = $('#bnggc').DataTable();
                                        var counter = 1;
                                        var barcode = $('#gcbarcode').val(), formUrl = $('form#scanGCForBNGCustomer').attr('action');
                                        if(barcode==undefined)
                                        {
                                            return false;
                                        }

                                        if(barcode.trim()=='')
                                        {
                                            $('.response-validate').html('<div class="alert alert-danger alert-dismissable">Please input GC barcode number.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');      
                                            $('#gcbarcode').select();
                                            return false;
                                        }
                                        $.ajax({
                                            url:formUrl,
                                            data:{barcode:barcode},
                                            type:'POST',
                                            success:function(data){
                                                //$('#gctoscan').val(0);
                                                console.log(data);
                                                var data = JSON.parse(data);
                                                if(data['st'])
                                                {
                                                    $('#gctoscan').val(data['nobarcode']);

                                                    $('.response-validate').html('<div class="alert alert-success alert-dismissable">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); 
                                                    $('#gcbarcode').select();


                                                    $('#gcscanned').val(data['gcscan']);
                                                    // $('#totden2').val(data['total']);

                                                }
                                                else 
                                                {
                                                    $('.response-validate').html('<div class="alert alert-danger alert-dismissable">'+data['msg']+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>');  
                                                    $('#gcbarcode').select();                               
                                                }
                                            }
                                        });         
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
                        else 
                        {
                            alert('Please upload file.');
                        }
                    }
                });
                
                // $('table#_bgnscangc tbody tr td span.p20180416-88910').text('xxx');
                // var table = $('#_bgnscangc').DataTable();  

                // table
                //     .clear()
                //     .draw();   




                // var temp = table.row(11).data();
                // temp[0] = 'Tom';
                // $('#_bgnscangc').dataTable().fnUpdate(temp,11,undefined,false);                
                // // table
                // //     .column( 0 )
                // //     .data()
                // //     .each( function ( value, index ) {
                // //         console.log( 'Data in index: '+index+' is: '+value );
                // // } );
            });

            $('.form-container').on('submit','#_bngTransaction',function(event){
                event.preventDefault();
                $('.response').html('');
                var formURL = $(this).attr('action'), formData = $(this).serialize();  
                $('#btnBNGSub').prop('disabled',true);

                if($('#totamt').val()==0 || $('#gcscanned').val()==0)
                {
                    $('.response').html('<div class="alert alert-danger alert-danger-dialog">Please upload file / Scan GC.</div>');     
                    $('#btnBNGSub').prop('disabled',false);
                    return false;
                }

                BootstrapDialog.show({
                    title: 'Confirmation',
                    message: 'Save Data?',
                    closable: true,
                    closeByBackdrop: false,
                    closeByKeyboard: true,
                    onshow: function(dialog) {
                        // dialog.getButton('button-c').disable();
                        $('button#btnBNGSub').prop('disabled',true);
                    },
                    onhide: function(dialog) {
                        $('button#btnBNGSub').prop('disabled',false);
                    },
                    buttons: [{
                        icon: 'glyphicon glyphicon-ok-sign',
                        label: 'Yes',
                        cssClass: 'btn-primary',
                        hotkey: 13,
                        action:function(dialogItself){    
                            $button = this;   
                            $button.disable();  
                            dialogItself.close();
                            $.ajax({
                                url:formURL,
                                beforeSend:function(){
                                    //$('#processing-modal').modal('show');
                                },
                                success:function(data){
                                    console.log(data);
                                    var data  = JSON.parse(data);

                                    if(data['st'])
                                    {
                                        var dialog = new BootstrapDialog({
                                        message: function(dialogRef){
                                        var $message = $('<div>Beam and Go Transaction successfully saved.</div>');                    
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
                                            location.reload();
                                        }, 1500);
                                    }
                                    else 
                                    {
                                        $('.response').html('<div class="alert alert-danger alert-danger-dialog">'+data['msg']+'</div>');
                                    }

                                }
                            });
                            
                            $button.enable();   
                        }
                    }, {
                        icon: 'glyphicon glyphicon-remove-sign',
                        label: 'No',
                        action: function(dialogItself){
                            dialogItself.close();
                            $('#btnBNGSub').prop('disabled',false);
                        }
                    }]
                });

                return false;
            });

            $('table#_bgnscangc').on('click','.remove-employee',function(){
               
                var serial = $(this).parents('tr').find('input.serial').val();
                var r = confirm("Remove Item?");
                if (r == true) {

                    $.ajax({
                        //url:'../ajax.php?action=deleteAssignByKey',
                        url:'../ajax.php?action=removeBySerialNumber',
                        data:{serial:serial},
                        type:'POST',
                        success:function(data)
                        {
                            console.log(data);
                            var data = JSON.parse(data);
                            if(data['st'])
                            {
                                $('#totamt').val(data['total']);
                            }
                            else 
                            {
                                $('.response').html('<div class="alert alert-danger alert-danger-dialog">Item not found.</div>');
                            }

                        }
                    });

                    var table = $('#_bgnscangc').DataTable();
                    table
                    .row( $(this).parents('tr') )
                    .remove()
                    .draw();                  

                }
                
                //$('input[name=lastname]').focus();
            });

        </script>
    <?php
}

function _scanGCForCustomerBNG($link,$gctoscan)
{
    ?>
        <div class="row">
            <div class="col-xs-12 form-horizontal">
                <form method="post" action="../ajax.php?action=scanGCForBNGCustomer" id="scanGCForBNGCustomer">
                    <div class="form-group bot16">
                        <label class="col-xs-5 control-label">Remaining GC to scan:</label>
                        <div class="col-xs-7">
                            <input type="text" class="form input-sm inptxt form-control" readonly="readonly" value="<?php echo $gctoscan; ?>" id="gctoscan">
                        </div>
                    </div><!-- end of form-group -->  
                    <div class="form-group inputGcbarcode">
                        <div class="col-xs-12">
                            <input data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': ''" class="form-control input-lg input-validation" id="gcbarcode" name="gcbarcode" autocomplete="off" maxlength="13" />
                        </div>
                    </div>
                    <div class="form-group" style="display:none;">
                        <div class="col-xs-12">
                            <input data-inputmask="'alias': 'numeric','digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': ''" class="form-control input-lg input-validation" id="gcbarcodexx" name="gcbarcodexxxx" autocomplete="off" maxlength="13" />
                        </div>
                    </div>
                </form>
                <div class="response-validate">
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $('#gcbarcode').inputmask();
            $('#gcbarcode').focus();
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
