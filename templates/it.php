<script src="../assets/js/funct.js"></script>
<?php
session_start();
include '../function.php';

if(isset($_GET['page']))
{
	$page = $_GET['page'];

	if($page=='itstoreeod')
	{
		_itstoreeod($link);
	}
	elseif ($page=='itstoreeodconfirmation') 
	{
		_itstoreeodconfirmation($link);
	}
	elseif ($page=='itstoreeodres') 
	{
		$id = $_GET['id'];

		_itstoreeodres($link,$id);
	}
	elseif ($page=='indexpage') 
	{
		_indexpage($link);
	}
	else 
	{
		//last
		echo 'Something went wrong.';
	}		
}

function _indexpage($link)
{
	$table = 'store_eod';
	$select = "store_eod.steod_id,
	    stores.store_name,
		CONCAT(users.firstname,' ',users.lastname) as eod_by,
	    store_eod.steod_datetime";
	$where = '1 ORDER BY store_eod.steod_datetime DESC';
	$join = 'store_eod 
		LEFT JOIN
			stores
		ON
			stores.store_id = store_eod.steod_storeid
		LEFT JOIN
			users
		ON
			users.user_id = store_eod.steod_by';
	$limit = '';

	$data = getAllData($link,$table,$select,$where,$join,$limit);


	?>
        <div class="row">
            <div class="col-sm-12">
                <div class="col-md-12 pad0">
                    <div class="panel with-nav-tabs panel-info">
                        <div class="panel-heading">
                            <ul class="nav nav-tabs">
                                <li class="active" style="font-weight:bold">
                                    <a href="#tab1default" data-toggle="tab">EOD List</a>
                                </li>
                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="tab1default">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="response">
                                            </div>
                                            <table class="table rows-adjust" id="storeod">
                                                <thead>
                                                    <tr>
                                                        <th>Stores</th>
                                                        <th>EOD By</th>
                                                        <th>Date / Time</th>
                                                        <th>View</th>
                                                    </tr>
                                                </thead>                
                                                <tbody>
                                                	<?php foreach ($data as $d): ?>
                                                		<tr>
                                                			<td><?php 
                                                					if($d->store_name==''):
                                                						echo 'All Stores';
                                                					else:
                                                						echo ucwords($d->store_name);
                                                					endif;
                                                				?>
                                                			</td>
                                                			<td><?php echo ucwords($d->eod_by); ?></td>
                                                			<td><?php echo _dateFormat($d->steod_datetime).' / '._timeFormat($d->steod_datetime); ?></td>
                                                			<td><a href="#/#/itstoreeod/<?php echo $d->steod_id; ?>"<i class="fa fa-fa fa-eye faeye" title="View"></i></a></td>
                                                		</tr>
                                                	<?php endforeach; ?>   	
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
        </div>
        <script type="text/javascript">
			$.extend( $.fn.dataTableExt.oStdClasses, {	  
			    "sLengthSelect": "selectsup"
			});
		    $('#storeod').dataTable( {
		        "pagingType": "full_numbers",
		        "ordering": false,
		        "processing": true,
		        "bProcessing":true
		    });
        </script>
	<?php
}

function _itstoreeodres($link,$id)
{

	//check id exist
	if(!checkIfExist($link,'steod_id','store_eod','steod_id',$id))
	{
		echo 'Page not found';
		exit();
	}

    $getverifiedgc = eodDisplayItems($link,$id);
    // get row           
    $eodDetails = getEODdetailsIT($link,$id);
    //$used = verifiedAndUsedNumGC($link,$storeid,$id);

	?>

    <div class="row">
        <div class="col-sm-12">
            <div class="col-md-12 pad0">
                <div class="panel with-nav-tabs panel-info">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active" style="font-weight:bold">
                                <a href="#tab1default" data-toggle="tab">EOD Date (<?php echo _dateFormat($eodDetails->steod_datetime); ?>)</a>
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
                                    <div class="col-xs-12">
                                        <div class="response">
                                        </div>
                                            <table class="table rows-adjust" id="storeod">
                                                <thead>
                                                    <tr>
                                                        <th>Barcode #</th>
                                                        <th>Denomination</th>
                                                        <th>Date / Time Verified</th>
                                                        <th>Verified By</th>
                                                        <th>Customer Name</th>
                                                        <th>Store</th>
                                                        <th>Balance</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>                
                                                <tbody>
                                                    <?php 
                                                        foreach ($getverifiedgc as $v): 
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $v->st_eod_barcode; ?></td>
                                                            <td><?php echo number_format($v->vs_tf_denomination,2);?></td>
                                                            <td><?php echo is_null($v->vs_reverifydate) ? _dateFormat($v->vs_date).' / '._timeFormat($v->vs_time): _timeFormat($v->vs_reverifydate); ?></td>
                                                            <td><?php echo ucwords($v->verby); ?></td>
                                                            <td><?php echo ucwords($v->cus); ?></td>
                                                            <td><?php echo $v->store_name; ?></td>
                                                            <td><?php echo number_format($v->vs_tf_balance,2); ?></td>
                                                            <td><i class="fa fa fa-search falink sstaff" onclick="textfiletranx(<?php echo $v->st_eod_barcode; ?>)"></i></td>
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
            </div>
        </div>
    </div>
	<script type="text/javascript">
		$('.modal-backdrop.in').hide();
		$('#processing-modal').modal('hide');  

		$.extend( $.fn.dataTableExt.oStdClasses, {	  
		    "sLengthSelect": "selectsup"
		});
	    $('#storeod').dataTable( {
	        "pagingType": "full_numbers",
	        "ordering": false,
	        "processing": true,
	        "bProcessing":true
	    });

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

function _itstoreeodconfirmation($link)
{
	?>

	<div class="row rownobot">
		<div class="col-xs-12">
			<form class="form-horizontal" action="../ajax.php?action=iteodstore" id="eodconfirm">
				<div class="form-group">
					<label class="col-xs-4 control-label">Password:</label>
					<div class="col-xs-6">
						<input type="password" class="form-control input-xs reqfieldmk fontsizexs" name="password" required autocomplete="off">
						<input type="password" style="display:none" class="form-control input-xs reqfieldmk fontsizexs" name="password1" required autocomplete="off">	
					</div>
				</div>
				<div class="responsepass">
				</div>
			</form>
		</div>
	</div>
	<script>
		$('input[name=password]').focus();
	</script>

	<?php
}


function _itstoreeod($link)
{
    $getverifiedgc = getverifiedgcStoreIT($link);

	?>
        <div class="row">
            <div class="col-sm-12">
                <div class="col-md-12 pad0">
                    <div class="panel with-nav-tabs panel-info">
                        <div class="panel-heading">
                            <ul class="nav nav-tabs">
                                <li class="active" style="font-weight:bold">
                                    <a href="#tab1default" data-toggle="tab">Verified GC (For EOD)</a>
                                </li>
                                <span class="pull-right">
                                    <?php if(count($getverifiedgc) > 0):  ?>
                                        <button class="btn btn-info" <?php echo count($getverifiedgc)>0 ? '':'';?> onclick="eodstoreit();"><i class="fa fa-cog"></i> Process EOD</button>
                                    <?php endif; ?>
                                </span>
                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="tab1default">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="response">
                                            </div>
                                            <table class="table" id="storeod">
                                                <thead>
                                                    <tr>
                                                        <th>Barcode #</th>
                                                        <th>Denomination</th>
                                                        <th>Store</th>
                                                        <th>GC Type</th>
                                                        <th>Date</th>
                                                        <th>Time</th>
                                                        <th>Verified By</th>
                                                        <th>Customer Name</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>                
                                                <tbody>
                                                    <?php foreach ($getverifiedgc as $v): ?>
                                                        <tr>
                                                            <td><?php echo $v->vs_barcode; ?></td>
                                                            <td><?php echo number_format($v->vs_tf_denomination,2);?></td>
                                                            <td><?php echo ucwords($v->store_name); ?></td>
                                                            <td><?php echo ucwords($v->gctype); ?></td>
                                                            <td><?php echo is_null($v->vs_reverifydate) ? $v->vs_date : $v->vs_reverifydate;  ?>
                                                            </td>
                                                            <td><?php echo is_null($v->vs_reverifydate) ? _timeFormat($v->vs_time): _timeFormat($v->vs_reverifydate); ?></td>
                                                            <td><?php echo ucwords($v->firstname.' '.$v->lastname); ?></td>
                                                            <td><?php echo ucwords($v->cus_fname.' '.$v->cus_lname); ?></td>
                                                            <td><?php echo is_null($v->vs_reverifydate) ? '<span class="label label-success">verified</span>' : '<span class="label label-primary">reverified</span>';  ?></td>
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
                </div>
            </div>
        </div>

        <script type="text/javascript">
			$.extend( $.fn.dataTableExt.oStdClasses, {	  
			    "sLengthSelect": "selectsup"
			});
		    $('#storeod').dataTable( {
		        "pagingType": "full_numbers",
		        "ordering": false,
		        "processing": true,
		        "bProcessing":true
		    });


			// function eodstoreit()
			// {
			// 	BootstrapDialog.show({
			// 	  title: 'Confirmation',
			// 	  message:  '<div class="row">'+
			// 	            '<div class="col-md-12">'+
			// 	            'Are you sure you want to process EOD?'+
			// 	            '</div>'+                                                                       
			// 	            '</div>',
			// 	  cssClass: 'confirmation',    
			// 	  closable: true,
			// 	  closeByBackdrop: false,
			// 	  closeByKeyboard: true,
			// 	  buttons: [{
			// 	      icon: 'glyphicon glyphicon-ok-sign',
			// 	      label: 'Yes',
			// 	      cssClass: 'btn-success',
			// 	      hotkey: 13,
			// 	      action:function(dialogItself){
			// 	      	$buttons = this;
			// 	      	$buttons.disable();
			// 	      	dialogItself.close();
			// 	      	$('div.response').html('');
			// 	      	$.ajax({
			// 	      		url:'../ajax.php?action=eodstoreit',
			// 	      		data:{storeid:storeid,userid:userid},
			// 	      		type:'POST',
			// 	      		beforeSend:function(data)
			// 	      		{
			// 	      			$('#processing-modal').modal('show');
			// 	      			$('div.box-content').html('<i class="fa fa-cog fa-spin"></i>');
			// 	      		},
			// 	      		success:function(data)
			// 	      		{	      					
			// 	      			setTimeout(function(){
			// 	      				$('#processing-modal').modal('hide'); 	      				
			// 	      			},1000);

				      			
			// 	      			console.log(data);
			// 	      			var data = JSON.parse(data);
			// 	      			if(data['st'])
			// 	      			{
			// 	      				window.location = 'store-eod.php?eod='+data['id'];
			// 	      			}
			// 	      			else 
			// 	      			{
			// 	      				$('div.response').html('<div class="alert alert-danger">'+data['msg']+'</div>');
			// 	      			}

			// 	      		}
			// 	      	});	        
			// 	      }
			// 	  }, {
			// 	    icon: 'glyphicon glyphicon-remove-sign',
			// 	      label: 'No',
			// 	      action: function(dialogItself){
			// 	          dialogItself.close();                     
			// 	      }
			// 	  }]
			// 	});
			// }
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
