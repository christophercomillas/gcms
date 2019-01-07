<?php
// .... before that, you should, well
// must check the file, the path,
// code injection...
header('Content-type: application/octet-stream');
header('Content-Disposition: attachment; filename='.$_GET['file']);
readfile($_GET["file"]);
?>