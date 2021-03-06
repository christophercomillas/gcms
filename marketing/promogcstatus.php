<?php 
	session_start();
	include '../function.php';
	require 'header.php';
	require '../menu.php';
?>

<div class="main fluid">    
	<div class="row form-container">
    	<div class="col-md-12">
            <div class="panel with-nav-tabs panel-info">
                <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Promo GC Status</a></li>
                            <!-- <li><a href="#tab2default" data-toggle="tab">Default 2</a></li> -->
                        </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1default">
                        	<div class="row">
                        		<div class="col-xs-12">
                        			<table class="table" id="barcode-grid">
                        				<thead>
                        					<tr>
                        						<th>GC Barcode #</th>
                        						<th>Denom</th>
                        						<th>Retail Group</th>
                         						<th>Promo Name</th>
                        						<th>Status</th>
                        						<th>Date Released</th>
                        						<th>Released By</th>
                        					</tr>
                        				</thead>
                        				<tbody>
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
                        <!-- <div class="tab-pane fade" id="tab2default">Default 2</div> -->
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/marketing.js"></script>
<?php include 'footer.php' ?>