<?php 
    if($_SESSION['gc_usertype']!='13'){
        header('location:../index.php?action=logout');
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
    <link href="../assets/css/datepicker.min.css" rel="stylesheet">
    <link href="../assets/css/font-awesome.css" rel="stylesheet">
    <link href="../assets/css/bootstrap-dialog.min.css" rel="stylesheet">
    <link href="../assets/css/jquery.dataTables.css" rel="stylesheet">
    <link href="../assets/css/slate-theme.css" rel="stylesheet">
    <link href="../assets/css/fileinput.css" rel="stylesheet">
    <link href="../assets/css/lightgallery/lightgallery.css" rel="stylesheet">
    <link href="../assets/css/sweetalert.css" rel="stylesheet">
    <link href="../assets/css/slate-theme-override.css" rel="stylesheet">
  </head>
  <body>