<?php 
    session_start();
    include '../function.php';
    require 'header.php';
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
                                <a href="#tab1default" data-toggle="tab">Manage Customer</a>
                            </li>
                                <button class="btn btn-info pull-right"  id="addcus"><i class="fa fa-user-plus"></i> Add New Customer</button>
                                <!-- <a href="cardsales.php"><span class="btn pull-right"><i class="fa fa-backward" aria-hidden="true"></i>
                                    Back</span>
                                </a> -->
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab1default">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <table class="table dataTable no-footer" id="_customermanage">
                                            <thead>
                                                <tr>
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>Middle Name</th>
                                                    <th>Valid ID Number</th>
                                                    <th>Address</th>
                                                    <th>Mobile No.</th>
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
    </div>
  </div>

<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/store.js"></script>
<?php include 'footer.php' ?>