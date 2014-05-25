<?php
header('Content-disposition: attachment; filename=backup.sql');
header('Content-type: text/plain');
readfile('backup.sql');
?>