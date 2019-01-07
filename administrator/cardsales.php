<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  $select = "ccard_name, ccard_id";
  $where ="1";
  $cards = getAllData($link,'credit_cards',$select,$where,'','');

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
                  <a href="#tab1default" data-toggle="tab">
                    <select class="form-control" onchange="cardsales(this.value)">
                      <option value="1">Card Sales</option>
                      <option value="2">Card Sales By Store</option>
                      <option value="3">All Card Sales</option>
                    </select>
                  </a>
                </li>
              </ul>
            </div>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="tab1default">
                      <div class="row">
                        <div class="col-xs-12 cardsalesload">
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
<script type="text/javascript" src="../assets/js/admin.js"></script>
<?php include 'footer.php' ?>