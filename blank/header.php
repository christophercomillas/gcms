<?php 
    if($_SESSION['gc_usertype']!='treasurydept'){
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
    <link href="../assets/css/bootstrap.css" rel="stylesheet">    
    <link href="../assets/css/datepicker.min.css" rel="stylesheet">
    <link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../assets/css/bootstrap-dialog.css" rel="stylesheet">
    <link href="../assets/css/slate-theme.css" rel="stylesheet">
    <link href="../assets/css/slate-theme-override.css" rel="stylesheet">
  </head>
  <body>

 