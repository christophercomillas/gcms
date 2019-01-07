<?php 
  session_start();
  include '../function.php';
  require 'header.php';

  $select = "special_external_customer.spcus_id, 
    special_external_customer.spcus_companyname, 
    special_external_customer.spcus_acctname,
    special_external_customer.spcus_address, 
    special_external_customer.spcus_cperson, 
    special_external_customer.spcus_cnumber, 
    special_external_customer.spcus_at,
    CONCAT(users.firstname,' ',users.lastname) as createdby";


  $where = '1';

  $join = 'INNER JOIN
      users
    ON
      users.user_id = special_external_customer.spcus_by';
  $limit ='ORDER BY spcus_id DESC';
  $cus = getAllData($link,'special_external_customer',$select,$where,$join,$limit);

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
                    <a href="#tab1default" data-toggle="tab">Special External GC Customer Setup</a>
                </li>
                <button type="button" class="btn pull-right" onclick="addExternalCustomer();"><i class="fa fa-plus-square" aria-hidden="true"></i>
 Add Customer Info</button>
              </ul>
            </div>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="tab1default">
                      <div class="row form-container">
                        <div class="col-sm-12">
                          <table class="table" id="sexcustomer">
                            <thead>
                              <tr>
                                <th>Company Name / Person</th>
                                <th>Account Name</th>
                                <th>Address</th>
                                <th>Contact Person</th>
                                <th>Contact Number</th>
                                <th>Created by</th>
                                <th>Date Created</th>
                                <th></th>
                              </tr>
                            </thead>
                            <tbody>
                               <?php foreach ($cus as $key): ?>
                                <tr>
                                  <td><?php echo ucwords($key->spcus_companyname); ?></td>
                                  <td><?php echo $key->spcus_acctname; ?></td>
                                  <td><?php echo ucwords($key->spcus_address); ?></td>
                                  <td><?php echo ucwords($key->spcus_cperson); ?></td>
                                  <td><?php echo ucwords($key->spcus_cnumber); ?></td>
                                  <td><?php echo ucwords($key->createdby); ?></td>
                                  <td><?php echo _dateFormat($key->spcus_at); ?></td>
                                  <td></td>
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
  </div>
<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/store.js"></script>
<?php include 'footer.php' ?>