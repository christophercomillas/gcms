<?php 
    if($_SESSION['gc_usertype']!='8'){
        header('location:../index.php?action=logout');
    } else {
        $gcid = $_SESSION['gc_id'];
        $storeid = getField($link,'store_assigned','users','user_id',$gcid);
        if(!$storeid){
            echo $link->error;
        }
    } 
?>
<!DOCTYPE html>
<html>
  <head>
    <title>GC Monitoring System</title>
    <link rel="shortcut icon" href="../assets/images/favicon.ico" type="image/icon">   
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/jquery.dataTables.css" rel="stylesheet">     
    <link href="../assets/css/datepicker.min.css" rel="stylesheet">
    <link href="../assets/css/font-awesome.css" rel="stylesheet">
    <link href="../assets/css/bootstrap-dialog.min.css" rel="stylesheet">
    <link href="../assets/css/slate-theme.css" rel="stylesheet">
    <link href="../assets/css/slate-theme-override.css" rel="stylesheet">
    <link href="../assets/css/fileinput.css" rel="stylesheet">
    <link href="../assets/css/lightgallery/lightgallery.css" rel="stylesheet">
    <link href="../assets/css/slate-theme-override.css" rel="stylesheet">

        <style media="print" type="text/css">
            @media print
            {
                .main, 
                .navbar, 
                #sidebar-menu
                    * { visibility: hidden; }
                #print-receipt-verify * { visibility: visible; }
                #print-receipt-verify 
                {   
/*                    display:block; 
                    position: absolute; 
                    top: 130px; 
                    left: 0px; 
                    font-size: 9px;*/
                    display:block; 
                    position: absolute; 
                    top: 127px; 
/*                    left: 230px; */
                    left: 0px;
                    font-size: 10px;
                }                
            }
        </style>
  </head>
  <body>

 