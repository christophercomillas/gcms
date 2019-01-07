
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- /.search form -->
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        <li class="header">MAIN NAVIGATION</li>
                        <li><a href="#/" data-link="pending"><i class="fa fa-dashboard"></i> <span>Pending Budget</span></a></li>
                        <li><a href="#" onclick="window.document.location='#/approved-budget-request/'; return false;"><i class="fa fa-book"></i> <span>Approved Budget</span></a></li>
                        <li><a href="documentation/index.html"><i class="fa fa-book"></i> <span>Cancelled Budget</span></a></li>
                        <li onclick="changeusername('<?php echo $_SESSION['gc_id']; ?>','<?php echo $_SESSION['gc_user']; ?>'); return false;"><a href="#"><i class="fa fa-book"></i> <span>Change Username</span></a></li>
                        <li onclick="changepassword('<?php echo $_SESSION['gc_id']; ?>'); return false;"><a href="#"><i class="fa fa-book"></i> <span>Change Password</span></a></li>
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>
