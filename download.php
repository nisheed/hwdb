<?php
$file = '/nfslocation/hwdb/hwdb.csv'; 
if (file_exists($file)) { 
    header('Content-type: application/force-download'); 
    header('Content-Transfer-Encoding: Binary'); 
    header('Content-length: ' . filesize($file)); 
    header('Content-disposition: attachment; filename=' . basename($file)); 
    readfile($file); 
} 
?>
