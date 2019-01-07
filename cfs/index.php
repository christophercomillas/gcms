 <?php
    session_start();
    include '../function.php';
    require 'header.php';
?>

<?php require '../menu.php'; ?>
    <div class="main fluid">


    </div>
<?php include 'jscripts.php'; ?>
<!--<script type="text/javascript" src="../assets/js/cus.js"></script>-->
<script type="text/javascript">
    $('div.main.fluid').load('../templates/it.php?page=indexpage');
</script>
<?php include 'footer.php' ?>