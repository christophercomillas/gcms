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
                                <a href="#tab1default" data-toggle="tab">Verified GC</a>
                            </li>
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
                                                    <th>Pay To</th>
                                                    <th>Date Sold / Released</th>
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