 <?php
    session_start();
    include '../function.php';
    require 'header.php';

    //$fadnew = "\\\\172.16.16.70\\kokoyz\\";

    if(getFADIPConnectionStatus($link))
    {
        $fadnew = getField($link,'app_settingvalue','app_settings','app_tablename','fad_server_ip_received_new');
    }
    else 
    {
        $fadnew = $dir.getField($link,'app_settingvalue','app_settings','app_tablename','localhost_received_new');
    }

    $arr_l = [];

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
                                    <a href="#tab1default" data-toggle="tab">GC Receiving</a>
                                </li>
                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="tab1default">
                                    <div class="row ">
                                      <div class="col-sm-12">
                                        <?php if(!file_exists($fadnew)): 
                                          // $fadnew = $fadnew2;
                                        ?>
                                          <div class='alert alert-danger'>Cannot connect to server folder.</div>
                                        <?php endif; ?>
                                        <?php 
                                          if (is_dir($fadnew)) {
                                              if ($dh = opendir($fadnew)) 
                                              {
                                                  $files = array();

                                                  while (($file = readdir($dh)) !== false) 
                                                  {
                                                      if (!is_dir($fadnew.'/'.$file)) 
                                                      {
                                                        $allowedExts = array("txt");
                                                        $temp = explode(".", $file);
                                                        $extension = end($temp);
                                                        if(in_array($extension, $allowedExts))
                                                          $files[] = $file;
                                                      }
                                                  }
                                                  closedir($dh);
                                                  $arr_l = [];
                                                  $reqnum ='';
                                                  $trannum = '';
                                                  $supplier = '';
                                                  for ($i=0; $i < count($files) ; $i++) { 
                                                    $r_f = fopen($fadnew.'/'.$files[$i],'r');                  
                                                      while(!feof($r_f)) 
                                                      {
                                                        $arr_f[] = fgets($r_f);
                                                      }
                                                      fclose($r_f);
                                                      for ($x=0; $x < count($arr_f); $x++) 
                                                      { 
                                                        $c = explode("|",$arr_f[$x]);
                                                        if(trim($c[0])=='GC E-REQUISITION NO')
                                                        {
                                                          $reqnum = $c[1];
                                                        }
                                                        if(trim($c[0])=='Transaction Date')
                                                        {
                                                          $transdate = $c[1];
                                                        }
                                                        if(trim($c[0])=='Supplier Name')
                                                        {
                                                          $supplier = $c[1];
                                                        }
                                                        if(trim($c[0])=='Receiving No')
                                                        {
                                                          $fadrec = $c[1];
                                                        }
                                                        if(trim($c[0])=='Purchase Order No')
                                                        {
                                                          $ponum = $c[1];
                                                        }


                                                      }
                                                    $arr_l[] =  array(
                                                      'reqnum' => $reqnum,
                                                      'transdate' => $transdate,
                                                      'supplier' => $supplier,
                                                      'txtfilename' => $files[$i],
                                                      'fadrec'  => $fadrec,
                                                      'ponum' => $ponum
                                                    );
                                                  }

                                              }
                                          }
                                        ?>
                                        <table class="table dataTable no-footer" id="gcrec">
                                          <thead>
                                            <tr>
                                              <th>FAD Rec. #</th>
                                              <th>E-Req. #</th>
                                              <th>Trans Date</th>
                                              <th>Supplier Name</th>
                                              <th>P.O. #</th>
                                              <th>Textfile Name</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                           <?php foreach ($arr_l as $key => $value): ?>
                                               <tr class="notclosed" onclick="document.location = 'txtfileinfo.php?txtfile=<?php echo $value['txtfilename']?>';">
                                                <td><?php echo $value['fadrec']; ?></td>
                                                <td><?php echo addZeroToStringZ($value['reqnum'],3); ?></td>
                                                <td><?php echo _dateFormat($value['transdate']); ?></td>
                                                <td><?php echo $value['supplier']; ?></td>
                                                <td><?php echo $value['ponum']; ?></td>
                                                <td><?php echo $value['txtfilename']; ?></td>
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

<?php include 'jscripts.php'; ?>
<script type="text/javascript" src="../assets/js/cus.js"></script>
<?php include 'footer.php' ?>