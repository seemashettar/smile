<?php

// Parse without sections
$ini_array = parse_ini_file("../settings.ini");
//print_r($ini_array);

$db_host = $ini_array['db_host'];
$db_user = $ini_array['db_user'];					
$db_pass = $ini_array['db_pass'];					
$db_name = $ini_array['db_name'];   				


?>
