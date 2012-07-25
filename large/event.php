<?php
// Set your return content type
//header('Content-type: application/xml');
$ini_array = parse_ini_file("../settings.ini");
$osgibroker_url = $ini_array['server_url'];

$daurl = "$osgibroker_url/osgibroker/event?topic=smile&clientID=smile&timeOut=1";
//echo $daurl;
// Get that website's content
$handle = fopen($daurl, "r");

// If there is something, read and return
if ($handle) {
    while (!feof($handle)) {
        $buffer = fgets($handle, 4096);
        echo $buffer;
    }
    fclose($handle);
}
?>
