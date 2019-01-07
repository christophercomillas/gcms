<script src="../assets/js/funct.js"></script>
<?php
session_start();
include '../function.php';

if(isset($_GET['page']))
{
	$page = $_GET['page'];

	if($page=='soldgcreportexcel')
	{
		_soldgcreportexcel();
	}
    elseif($page=='verifiedgcreportexcel')
    {
        _verifiedgcreportexcel();
    }
	else 
	{
		//last
		echo 'Something went wrong.';
	}	
}

function _verifiedgcreportexcel()
{
    ?>
        <div class="row form-container">
            <div class="col-md-12">
                <div class="panel with-nav-tabs panel-info">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Generate Verified GC Report (Excel)</a></li>
                        </ul>                       
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab1default">
                                <div class="row"> 
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Date range:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control pull-right" name="querytrdate" id="querytrdate">
                                            </div>
                                            <!-- /.input group -->
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <button type="button" id="verbtn" class="btn btn-default btn-block"><i class="fa fa-list" aria-hidden="true"></i> Submit</button>
                                            </div>
                                        </div>
                                        <div class="response">
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
            $('#querytrdate').daterangepicker();

            $('#verbtn').click(function(){
                var range = $('#querytrdate').val();
                if(range.trim()=="")
                {
                    $('.response').html('<div class="alert alert-danger">Please select date.</div>');
                    return false;
                }

                //location.href="verifiedgcreport.php?daterange="+range;
                location.href="verifiedgcreportexcel.php?daterange="+range;
            });

        </script>

    <?php
}

function _soldgcreportexcel()
{
    ?>
        <div class="row form-container">
            <div class="col-md-12">
                <div class="panel with-nav-tabs panel-info">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active" style="font-weight:bold"><a href="#tab1default" data-toggle="tab">Generate Sold GC Report (Excel)</a></li>
                        </ul>                       
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab1default">
                                <div class="row"> 
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Date range:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control pull-right" name="querytrdate" id="querytrdate">
                                            </div>
                                            <!-- /.input group -->
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <button type="button" id="verbtn" class="btn btn-default btn-block"><i class="fa fa-list" aria-hidden="true"></i> Submit</button>
                                            </div>
                                        </div>
                                        <div class="response">
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
            $('#querytrdate').daterangepicker();

            $('#verbtn').click(function(){
                var range = $('#querytrdate').val();
                if(range.trim()=="")
                {
                    $('.response').html('<div class="alert alert-danger">Please select date.</div>');
                    return false;
                }

                //location.href="verifiedgcreport.php?daterange="+range;
                location.href="soldgcreportexcel.php?daterange="+range;
            });

        </script>

    <?php 
}