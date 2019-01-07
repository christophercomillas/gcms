 <div class="navbar fluid">
      <div class="row">
        <div class="col-sm-12 no-padding">
          <button class="toggle-button" data-toggle="collapse" data-target="#sidebar-menu">
            <span class="fa fa-bars"></span>
          </button>
          <span class="title">
            <a href="index.php"><img src="../assets/images/headerlogo.png" class="headerlogo">
<small><span class="assigned">
                    [<?php echo $_SESSION['gc_title'];
                        $userid = $_SESSION['gc_id'];
                        if($_SESSION['gc_usertype']=='7')
                        {                            
                            $store_id = getField($link,'store_assigned','users','user_id',$userid);
                            $store_ass = getField($link,'store_name','stores','store_id',$store_id); 
                            $storebng = getField($link,'store_bng','stores','store_id',$store_id); 
                            echo ' - '.$store_ass;
                        }

                        if($_SESSION['gc_usertype']=='8')
                        {
                           $group = getField($link,'usergroup','users','user_id',$userid);
                           echo ' '.$group;
                        }

                    ?>]</span></small> <span class="assigned" style="color:red;">[LIVE]</span>

            </a>
          </span>
          <ul class="nav nav-pills slate-nav dropdown">
            <li>
              <a href="#" id="logout-modal">
              <!--<a href="#" data-toggle="modal" data-target="#logout-modal" id="#logout-modal">-->
                <i class="fa fa-power-off"></i>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
   <div class="sidebar-background"></div>
    
    <div class="global">
    
    <div class="sidebar in" id="sidebar-menu">
      <div class="slate-profile">
        <div class="avatar">
          <img src="../assets/images/photo.png" width="115" height="115" alt="avatar" />
        </div>
        <div class="slate-profile-text">
          Welcome, <?php echo ucwords($_SESSION['gc_fullname']); ?>
        </div>
        <div class="slate-profile-action">
          <div class="btn-group">
            <button type="button" class="btn btn-primary-dark ">
              <i class="fa fa-user"></i><span> My Account</span>
            </button>
            <button type="button" class="btn btn-primary-dark dropdown-toggle" data-toggle="dropdown">
              <span class="caret"></span>
              <span class="sr-only">Toggle Dropdown</span>  
            </button>
            <ul class="dropdown-menu" role="menu">
              <li><a href="javascript:void(0)" onclick="userprofile(<?php echo $_SESSION['gc_id']; ?>);" id="user-profile">My Profile</a></li>
              <li><a href="javascript:void(0)" onclick="changeusername(<?php echo $_SESSION['gc_id']; ?>,'<?php echo $_SESSION['gc_user']; ?>');" id="user-profile">Change Username</a></li>
              <li><a href="javascript:void(0)" onclick="changepassword(<?php echo $_SESSION['gc_id']; ?>);" id="update-password" >Change Password</a></li>
              <li><a href="#" id="logout-modal">Sign Out</a></li>
            </ul>
          </div>
        </div>
      </div>
      <ul class="nav nav-list">
        <li>
          <a href="index.php">
            <span class="fa fa-dashboard"></span>
            <span class="sidebar-label">Dashboard</span>
          </a>
        </li>
        <?php if($_SESSION['gc_usertype']=='2'): ?>
          <li>
            <a href="#" data-toggle="collapse" data-target="#menu-master">
              <span class="fa fa-archive"></span>
              <span class="sidebar-label">Masterfile</span>
              <span class="fa fa-chevron-down pull-right"></span>
            </a>
            <ul id="menu-master" class="submenu collapse nav nav-list">
              <li>
                <a href="#/setup-tres-customer">Customer Setup</a>
              </li>
              <li>
                <a href="#/setup-special-external">Special External Setup</a>
              </li>
              <li>
                <a href="#/setup-paymentfund">Payment Fund Setup</a>
              </li>
            </ul>
          </li>
          <li>
            <a href="#" data-toggle="collapse" data-target="#menu-trans">
              <span class="fa fa-archive"></span>
              <span class="sidebar-label">Transaction</span>
              <span class="fa fa-chevron-down pull-right"></span>
            </a>
            <ul id="menu-trans" class="submenu collapse nav nav-list">
              <li>
                <a href="tran_budget_request.php">Budget Request</a>
              </li>
              <li>
                <a href="tran_production_request.php">Production Request</a>
              </li>
              <li>
                <a href="tran_allocate.php">GC Allocation</a>
              </li>
              <li>
                <a href="tran_release_gc.php">GC Releasing (Retail Store)</a>
              </li>
              <li>
                <a href="tran_release_promo.php">Promo GC Releasing</a>
              </li>
              <li>
                <a href="#/release-gc-customer">Institution GC Sales</a>
              </li>
              <li>
                <a href="#/refund-institution-gc">Institution GC Refund</a>
              </li>
              <li>
                <a href="#/specialgcpayment">Special Ext. GC Payment</a>
              </li>
              <li>
                <a href="#/eod">GC Sales Report (EOD) </a>
              </li>
            </ul>
          </li>
          <li>
            <a href="#" data-toggle="collapse" data-target="#menu-adj">
              <span class="fa fa-exchange"></span>
              <span class="sidebar-label">Adjustment</span>
              <span class="fa fa-chevron-down pull-right"></span>
            </a>
            <ul id="menu-adj" class="submenu collapse nav nav-list">
              <li>
                <a href="adj-budget-entry.php">Budget Adjustment</a>
              </li>
<!--               <li>
                <a href="adj-gc-production.php">Production Adjustment Entry</a>
              </li> -->
              <li>
                <a href="adj-allocate-entry.php">Allocation Adjustment</a>
              </li>
            </ul>
          <li>
            <a href="ledger_budget.php">
              <span class="fa fa-money"></span>
              <span class="sidebar-label">Budget Ledger</span>
            </a>
          </li>
          <li>
            <a href="gccheckledger.php">
              <span class="fa fa-barcode"></span>
              <span class="sidebar-label">GC Ledger</span>
            </a>
          </li>
          <li>
            <a href="#" data-toggle="collapse" data-target="#menu-report">
              <span class="fa fa-archive"></span>
              <span class="sidebar-label">Reports</span>
              <span class="fa fa-chevron-down pull-right"></span>
            </a>
            <ul id="menu-report" class="submenu collapse nav nav-list">
              <li>
                <a href="storesalesreport.php">GC Report</a>
              </li>
            </ul>
          </li>
          <!--
          <li>
            <a href="#">
              <span class="fa fa-bar-chart-o"></span>
              <span class="sidebar-label">Reports</span>
            </a>
          </li>
          -->
        <?php endif; ?>
        <?php if($_SESSION['gc_usertype']=='3'): ?>
          <li>
            <a href="#/budget_ledger/">
              <span class="fa fa-money"></span>
              <span class="sidebar-label">Budget Ledger</span>
            </a>
          </li>
          <li>
            <a href="#/specialex_reports/">
              <span class="fa fa-money"></span>
              <span class="sidebar-label">Reports</span>
            </a>
          </li>
        <?php endif; ?>
        <?php if($_SESSION['gc_usertype']=='1'): ?>
          <li>
            <a href="#" data-toggle="collapse" data-target="#menu-trans">
              <span class="fa fa-desktop"></span>
              <span class="sidebar-label">Master File</span>
              <span class="fa fa-chevron-down pull-right"></span>
            </a>
            <ul id="menu-trans" class="submenu collapse nav nav-list">
              <li>
                <a href="setupusers.php">Setup Users</a>
              </li>
              <li>
                <a href="setupstorestaff.php">Setup Store Staff</a>
              </li>
              <li>
                <a href="setupcustomers.php">Setup Customers</a>
              </li>
              <li>
                <a href="setupstore.php">Setup Store</a>
              </li>
              <li>
                <a href="setupccard.php">Setup Credit Card</a>
              </li>
              <li>
                <a href="setupdenomination.php">Setup Denomination</a>
              </li>
            </ul>
          </li>
          <li>
            <a href="#" data-toggle="collapse" data-target="#menu-concerns">
              <span class="fa fa-desktop"></span>
              <span class="sidebar-label">Concerns</span>
              <span class="fa fa-chevron-down pull-right"></span>
            </a>
            <ul id="menu-concerns" class="submenu collapse nav nav-list">
              <li>
                <a href="#/verifiedgcnotranx/">Verified GC (No Trans)</a>
              </li>
              <li>
                <a href="#/verifygcmanual/">Verify GC (Manual)</a>
              </li>
              <li>
                <a href="#/createtextfile/">Create Textfile</a>
              </li>
              <li>
                <a href="#/eodtextfilecheck/">EOD Textfile Checker</a>
              </li>
            </ul>
          </li>
          <li>
            <a href="#" data-toggle="collapse" data-target="#menu-sales">
              <span class="fa fa-desktop"></span>
              <span class="sidebar-label">Sales</span>
              <span class="fa fa-chevron-down pull-right"></span>
            </a>
            <ul id="menu-sales" class="submenu collapse nav nav-list">
              <li>
                <a href="cashsales.php">Cash Sales</a>
              </li>
              <li>
                <a href="cardsales.php">Card Sales</a>
              </li>
              <li>
                <a href="arlist.php">AR (Customers)</a>
              </li>
            </ul>
          </li>
          <li>
            <a href="#" data-toggle="collapse" data-target="#menu-util">
              <span class="fa fa-cog"></span>
              <span class="sidebar-label">Utilities</span>
              <span class="fa fa-chevron-down pull-right"></span>
            </a>
            <ul id="menu-util" class="submenu collapse nav nav-list">
              <li>
                <a href="backupdb.php">Backup DB</a>
              </li>
              <li>
                <a href="tran_production_request.php">Archive</a>
              </li>
              <li>
                <a href="util_rebuilding.php">Rebuild Database</a>
              </li>
            </ul>
          </li>
          <li>
            <a href="#/viewverifiedgc">
              <span class="fa fa-file-text"></span>
              <span class="sidebar-label">Verified GC</span>
            </a>
          </li>
          <li>
            <a href="fadsystem.php">
              <span class="fa fa-file-text"></span>
              <span class="sidebar-label">FAD System Connection</span>
            </a>
          </li>
        <?php endif; ?>
        <?php if($_SESSION['gc_usertype']=='4'): ?>
<!--           <li>
            <a href="#" data-toggle="collapse" data-target="#menu-transiad">
              <span class="fa fa-archive"></span>
              <span class="sidebar-label">Transaction</span>
              <span class="fa fa-chevron-down pull-right"></span>
            </a>
            <ul id="menu-transiad" class="submenu collapse nav nav-list">
              <li>
                <a href="#/request-external-gc">Request GC</a>
              </li>
              <li>
                <a href="#/request-external-gcwitholder">Request GC (with names)</a>
              </li>
            </ul>
          </li> -->
          <li>
            <a href="#/barcodechecker">
              <span class="fa fa-cart-arrow-down"></span>
              <span class="sidebar-label">Barcode Checker</span>
            </a>
          </li>
          <li>
            <a href="gcreceived.php">
              <span class="fa fa-cart-arrow-down"></span>
              <span class="sidebar-label">Received GC</span>
            </a>
          </li>
          <li>
            <a href="gctrack.php">
              <span class="fa fa-file-text"></span>
              <span class="sidebar-label">GC Tracking</span>
            </a>
          </li>
          <!--
          <li class="active">
            <a href="c_reports.php">
              <span class="fa fa-desktop"></span>
              <span class="sidebar-label">Reports</span>
            </a>
          </li>
          -->
        <?php endif; ?>
        <?php if($_SESSION['gc_usertype']=='6'): ?>
          <?php if($_SESSION['gc_uroles']!=2): ?>
          <li>
            <a href="promogcrequest.php">
              <span class="fa fa-random"></span>
              <span class="sidebar-label">Promo GC Request</span>
            </a>
          </li>
          <li>
            <a href="#" data-toggle="collapse" data-target="#menu-promo">
              <span class="fa fa-archive"></span>
              <span class="sidebar-label">Promo</span>
              <span class="fa fa-chevron-down pull-right"></span>
            </a>
            <ul id="menu-promo" class="submenu collapse nav nav-list">
              <li>
                <a href="addpromo.php">Add New Promo</a>
              </li>
              <li>
                <a href="gcpromo.php">Promo List</a>
              </li>
<!--               <li>
                <a href="availablegcforpromo.php">Available GC</a>
              </li> -->
            </ul>
<!--             <a href="gcpromo.php">
              <span class="fa fa-desktop"></span>
              <span class="sidebar-label">GC Promo</span>
            </a> -->
          </li>
          <?php endif; ?>
          <li>
            <a href="releasedpromogc.php">
              <span class="fa fa-random"></span>
              <span class="sidebar-label">Released Promo GC</span>
            </a>
          </li>
          <li>
          <li>
            <a href="promogcstatus.php">
              <span class="fa fa-th-large"></span>
              <span class="sidebar-label">Promo GC Status</span>
            </a>
          </li>
          <?php if($_SESSION['gc_uroles']!=2): ?>
          <li>          
            <a href="managesupplier.php">
              <span class="fa fa-users"></span>
              <span class="sidebar-label">Manage Supplier</span>
            </a>
          </li>
          <li>
            <a href="#" data-toggle="collapse" data-target="#menu-cfssales">
              <span class="fa fa-desktop"></span>
              <span class="sidebar-label">Sales</span>
              <span class="fa fa-chevron-down pull-right"></span>
            </a>
            <ul id="menu-cfssales" class="submenu collapse nav nav-list">
              <li>
                <a href="#/saleslisttreasury">Treasury Sales</a>
              </li>
              <li>
                <a href="#/viewstoresales">Store Sales</a>
              </li>
            </ul>
          </li>
   
          <li>
            <a href="#" data-toggle="collapse" data-target="#menu-cfstores">
              <span class="fa fa-desktop"></span>
              <span class="sidebar-label">Verified GC Per Store</span>
              <span class="fa fa-chevron-down pull-right"></span>
            </a>
            <ul id="menu-cfstores" class="submenu collapse nav nav-list">
              <?php 
                $stores = getStores($link);

                foreach ($stores as $s): ?>
             
                <li>
                  <a href="#/verifiedgcperstore/<?php echo $s->store_id; ?>/0"><?php echo $s->store_name; ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
          </li>
          <?php endif; ?>
          <!--
          <li>
            <a href="marketingreports.php">
              <span class="fa fa-desktop"></span>
              <span class="sidebar-label">Reports</span>
            </a>
          </li>
          -->
        <?php endif; ?>
        <?php if($_SESSION['gc_usertype'] == '7'): ?>
          <li>
            <a href="#" data-toggle="collapse" data-target="#menu-storetrans">
              <span class="fa fa-desktop"></span>
              <span class="sidebar-label">Transactions</span>
              <span class="fa fa-chevron-down pull-right"></span>
            </a>
            <ul id="menu-storetrans" class="submenu collapse nav nav-list">
              <?php if($storebng!=''): ?>
                <li>
                  <a href="#beamandgoconversion">Beam And Go Conversion</a>
                </li>
              <?php endif; ?>
              <li>
                <a href="trans_requestgc.php">GC Request</a>
              </li>
              <?php 
                //$_SESSION['gc_store']        
                $table = 'store_local_server';
                $select = 'stlocser_ip,stlocser_username,stlocser_password,stlocser_db';
                $where = "stlocser_storeid='".$_SESSION['gc_store']."'";
                $join = '';
                $limit = '';
                $lserver = getSelectedData($link,$table,$select,$where,$join,$limit);
                if(count($lserver)==0):
              ?>
              <li>
                <a href="gc_verificationv2.php">GC Verification</a>
              </li>
              <?php 
                endif;
              ?>
              <li>
                <a href="#/gctransferList">GC Transfer</a>
              </li>
              <li>
                <a href="store-eod.php">Store EOD</a>
              </li>
              <li>
                <a href="#/gcLost">Lost GC</a>
              </li>
              <li>
                <a href="#/suppliergc">Supplier GC</a>
              </li>
            </ul>
          </li>
          <li>
            <a href="#" data-toggle="collapse" data-target="#menu-storesales">
              <span class="fa fa-desktop"></span>
              <span class="sidebar-label">Sales</span>
              <span class="fa fa-chevron-down pull-right"></span>
            </a>
            <ul id="menu-storesales" class="submenu collapse nav nav-list">
              <li>
                <a href="cashsales.php">Cash Sales</a>
              </li>
              <li>
                <a href="cardsales.php">Card Sales</a>
              </li>
              <li>
                <a href="arlist.php">AR (Customers)</a>
              </li>
              <li>
                <a href="salesreport.php">Report</a>
              </li>
            </ul>
          </li>
          <li>
            <a href="#" data-toggle="collapse" data-target="#menu-storesetup">
              <span class="fa fa-desktop"></span>
              <span class="sidebar-label">Masterfile</span>
              <span class="fa fa-chevron-down pull-right"></span>
            </a>
            <ul id="menu-storesetup" class="submenu collapse nav nav-list">
              <li>
                <a href="manage-customer.php">Customer Setup</a>
              </li>
              <li>
                <a href="#/sgccompanysetup">SGC Company Setup</a>
              </li>
              <li>
                <a href="#/sgcitemsetup">SGC Item Setup</a>
              </li>

            </ul>
          </li>
<!--           <li>
            <a href="#" data-toggle="collapse" data-target="#menu-trans">
              <span class="glyphicon glyphicon-list"></span>
              <span class="sidebar-label">Master File</span>
              <span class="fa fa-chevron-down pull-right"></span>
            </a>
            <ul id="menu-trans" class="submenu collapse nav nav-list">
              <li>
                <a href="manage-customer.php">Setup Customer</a>
              </li>
              <li>
                <a href="setupstorestaff.php">Setup Store Staff</a>
              </li>
            </ul>
          </li> -->
          <li>
              <a href="storeledger.php">
                <span class="fa fa-list-alt"></span>
                <span class="sidebar-label"> Store Ledger</span>
              </a>
          </li>

          <li>
              <a href="verifiedgclist.php">
                <span class="fa fa-list"></span>
                <span class="sidebar-label"> Verified GC</span>
              </a>            
          </li>
          <li>
            <a href="#" data-toggle="collapse" data-target="#menu-sreports">
              <span class="fa fa-desktop"></span>
              <span class="sidebar-label">Reports</span>
              <span class="fa fa-chevron-down pull-right"></span>
            </a>
            <ul id="menu-sreports" class="submenu collapse nav nav-list">
              <li>
                <a href="#/verifiedgcreport">Verified GC Report</a>
              </li>
            </ul>
          </li>
<!--           <li>
              <a href="transactedgc.php">
                <span class="fa fa-tasks"></span>
                <span class="sidebar-label"> Transacted GC</span>
              </a>
          </li> -->
          <!--
          <li>
              <a href="store-reports.php">
                <span class="fa fa-desktop"></span>
                <span class="sidebar-label"> Reports</span>
              </a>
          </li>
          -->
        <?php endif; ?>
        <?php if($_SESSION['gc_usertype']=='9'): ?>
          <li>
            <a href="#" data-toggle="collapse" data-target="#menu-transiad">
              <span class="fa fa-archive"></span>
              <span class="sidebar-label">Transaction</span>
              <span class="fa fa-chevron-down pull-right"></span>
            </a>
            <ul id="menu-transiad" class="submenu collapse nav nav-list">
              <li>
                <a href="#/request-external-gc">Request GC</a>
              </li>
              <li>
                <a href="#/request-external-gcwitholder">Request GC (with names)</a>
                <!-- <a href="trans_requestgc.php">Request GC (with names)</a> -->
              </li>
            </ul>
          </li>
          <li>
            <a href="companysetup.php">
              <span class="fa fa-info-circle"></span>
              <span class="sidebar-label">Customer Setup</span>
            </a>
          </li>
        <?php endif; ?>
        <?php if($_SESSION['gc_usertype']=='8'):

          // check if retail group allowed to request GC
          if(numRowsWhereThree($link,'user_roles','ur_userid','ur_userid','ur_roles','ur_status',$_SESSION['gc_id'],4,0) > 0):

        ?>
          <li>
            <a href="#/promogcrequest">
              <span class="fa fa-random"></span>
              <span class="sidebar-label">Promo GC Request</span>
            </a>
          </li>
          <li>
            <a href="#" data-toggle="collapse" data-target="#menu-promo">
              <span class="fa fa-archive"></span>
              <span class="sidebar-label">Promo</span>
              <span class="fa fa-chevron-down pull-right"></span>
            </a>
            <ul id="menu-promo" class="submenu collapse nav nav-list">
              <li>
                <a href="#/addnewpromo">Add New Promo</a>
              </li>
              <li>
                <a href="#/promolist">Promo List</a>
              </li>
            </ul>
          </li>
          <li>
            <a href="#/releasedpromogc">
              <span class="fa fa-random"></span>
              <span class="sidebar-label">Release Promo GC</span>
            </a>
          </li>
          <li>
          <li>
            <a href="#/promogcstatus">
              <span class="fa fa-th-large"></span>
              <span class="sidebar-label">Promo GC Status</span>
            </a>
          </li>
        <?php 
            endif;
        ?>
          <li class="ledgerPromo">
            <a href="#/ledger-promo/">
              <span class="fa fa-info-circle"></span>
              <span class="sidebar-label">Ledger</span>
            </a>
          </li>  
        <?php
          endif; 
        ?>

        <?php if($_SESSION['gc_usertype']=='10'): ?>
          <li>
            <a href="#/treasury-audit">
              <span class="fa fa-tasks" aria-hidden="true"></span>
              <span class="sidebar-label">Audit (Treasury)</span>
            </a>
          </li>
          <li>
            <a href="#/treasury-audit">
              <span class="fa fa-tasks" aria-hidden="true"></span>
              <span class="sidebar-label">Audit (Store)</span>
            </a>
          </li>
        <?php endif; ?>
        <?php if($_SESSION['gc_usertype']=='12'): ?>
          <li>
            <a href="#/itstoreeod">
              <span class="fa fa-tasks" aria-hidden="true"></span>
              <span class="sidebar-label">Stores EOD</span>
            </a>
          </li>          
        <?php endif; ?>
        <?php if($_SESSION['gc_usertype']=='13'): ?>
          <li>
            <a href="#" data-toggle="collapse" data-target="#menu-cfssales">
              <span class="fa fa-desktop"></span>
              <span class="sidebar-label">Sales</span>
              <span class="fa fa-chevron-down pull-right"></span>
            </a>
            <ul id="menu-cfssales" class="submenu collapse nav nav-list">
              <li>
                <a href="#/saleslisttreasury">Treasury Sales</a>
              </li>
              <li>
                <a href="#/viewstoresales">Store Sales</a>
              </li>
            </ul>
          </li>
   
          <li>
            <a href="#" data-toggle="collapse" data-target="#menu-cfstores">
              <span class="fa fa-desktop"></span>
              <span class="sidebar-label">Verified GC Per Store</span>
              <span class="fa fa-chevron-down pull-right"></span>
            </a>
            <ul id="menu-cfstores" class="submenu collapse nav nav-list">
              <?php 
                $stores = getStores($link);

                foreach ($stores as $s): ?>
             
                <li>
                  <a href="#/verifiedgcperstore/<?php echo $s->store_id; ?>/0"><?php echo $s->store_name; ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
          </li>
          <li>
            <a href="#/exportdata">
              <span class="fa fa-tasks" aria-hidden="true"></span>
              <span class="sidebar-label">Export Data</span>
            </a>
          </li>  
        <?php endif; ?>
        <?php if($_SESSION['gc_usertype']=='14'): ?>
          <li>
            <a href="#" data-toggle="collapse" data-target="#menu-reportacc">
              <span class="fa fa-archive"></span>
              <span class="sidebar-label">Reports</span>
              <span class="fa fa-chevron-down pull-right"></span>
            </a>
            <ul id="menu-reportacc" class="submenu collapse nav nav-list">
              <li>
                <a href="#/soldgcreportexcel">Sold GC</a>
              </li>
              <li>
                <a href="#/verifiedgcreportexcel">Verified GC</a>
              </li>
            </ul>
          </li>
        <?php endif; ?>
        
          <li class="about">
            <a href="aboutus.php">
              <span class="fa fa-info-circle"></span>
              <span class="sidebar-label">About Us</span>
            </a>
          </li>
      </ul>
      <hr/>
    </div>
