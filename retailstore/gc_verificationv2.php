<?php 
    session_start();

    //get store id
    include '../function.php';
    require 'header.php';
?>

<?php require '../menu.php'; ?>
    <div id="print-receipt-verify">       

    </div>
    <div class="main fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="col-md-12 pad0">
                    <div class="panel with-nav-tabs panel-info">
                        <div class="panel-heading">
                            <ul class="nav nav-tabs">
                                <li class="active" style="font-weight:bold">
                                    <a href="#tab1default" data-toggle="tab">GC Verification</a>
                                </li>

                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="tab1default">
                                    <div class="row form-container">
                                        <form class="form-horizontal" method="POST" action="../ajax.php?action=verification2" id="verifygc">
                                            <input type="hidden" name="isreprint" id="isreprint" value="0">
                                            <input type="hidden" name="isreverified" id="isreverified" value="0">
                                            <div class="col-xs-7">
                                                <div class="form-group bot16">
                                                    <label class="col-xs-4 control-label">Date:</label>
                                                    <div class="col-xs-8"><input type="text" class="form inptxt form-control" readonly="readonly" value="<?php echo _dateFormat($todays_date);?>"></div>
                                                </div><!-- end of form-group -->
                                                <div class="form-group bot16">
                                                    <div class="col-xs-offset-4 col-xs-8">
                                                        <button type="button" class="btn btn-block btn-info fordialog" onclick="addNewCustomerVerify();"><i class="fa fa-user-plus"></i>
                                                        Add New Customer </button>
                                                    </div>
                                                </div><!-- end of form-group -->
                                                <div class="form-group bot16">
                                                    <label class="col-sm-4 control-label">Pay to:</label>
                                                    <div class="col-xs-8">
                                                        <select class="form form-control inptxt input-md" name="payto" required>
                                                            <option value="">- Select -</option>
                                                            <option value="STORE DEPARTMENT">Store Department</option>
                                                            <option value="WHOLESALE">Wholesale</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group bot16">
                                                    <label class="col-sm-4 control-label">Customer Search:</label>
                                                    <div class="col-sm-8">
                                                        <div class="input-group">
                                                            <input name="checked" type="text" class="form-control inptxt" autocomplete="off" required="required" id="_vercussearch">
                                                            <span class="input-group-btn">
                                                                <button class="btn btn-info input-l btn-find" type="button">
                                                                    <span class="glyphicon glyphicon-search"></span>
                                                                </button>
                                                            </span>
                                                        </div><!-- input group -->
                                                        <span class="xcus">
                                                            <ul>
                                                                <li data-id="1">Sample 1w</li>
                                                                <li>Sample 2</li>
                                                                <li>Sample 43 wewe</li>
                                                                <li>Sam</li>
                                                            </ul>
                                                        </span>                               
                                                    </div>
                                                </div>
                                                <div class="form-group bot16">
                                                    <label class="col-xs-4 control-label control-label-lg">GC Barcode Number:</label>
                                                    <div class="col-xs-8">
                                                        <input data-inputmask="'alias': 'numeric', 'groupSeparator': '', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': ''" class="form form-control input-lg input-lg-o" id="gcbarcodever" name="gcbarcode" autocomplete="off" maxlength="13" autofocus required>
                                                    </div>
                                                </div><!-- end of form-group -->
                                                <div class="form-group bot16">
                                                    <label class="col-xs-offset-3 col-xs-4 control-label">Verified by:</label>
                                                    <div class="col-xs-5">
                                                        <input type="text" class="form inptxt form-control" readonly="readonly" value="<?php echo ucwords($_SESSION['gc_fullname']); ?>">
                                                    </div>
                                                </div><!-- end of form-group -->
                                                <div class="form-group">                  
                                                    <div class="col-xs-offset-8 col-xs-4">
                                                        <button type="submit" class="btn btn-block btn-primary verifybtn">
                                                            <span class="glyphicon glyphicon-share" aria-hidden="true"></span>
                                                            Submit
                                                        </button>
                                                    </div>
                                                </div><!-- end of form-group -->
                                                <div class="response">
                                                </div>
                                            </div>

                                            <div class="col-xs-5">
                                                <div class="customerdetails">
                                                    <i class="fa fa-user"></i>
                                                    Customer Details
                                                </div>
                                                <div class="customerdetails-container">
                                                    <input type="hidden" name="cus-id" value="" id="cid">
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
                                                <div class="form-group">
                                                    <div class="col-xs-offset-6 col-xs-6">
                                                        <button class="btn btn-block btn-danger" type="button" onclick="reprintVerification(<?php echo $_SESSION['gc_id']; ?>)"><i class="fa fa-key"></i> Manager Key</button>
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
        </div>
    </div>

<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/jQuery.print.js"></script>
<script type="text/javascript" src="../assets/js/store.js"></script>
<?php include 'footer.php' ?>