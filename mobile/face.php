<?php
// Set your return content type
//header('Content-type: application/xml');


define ("API_URL", 'http://api.face.com/faces/detect.json');
define ("API_KEY", '65d1f286b1aef896852b7ffbdbeedcbb');
define ("API_SECRET", 'd39d306a5c9bc99690bd47b5761726c8');
define ("API_DETECTOR", 'Aggressive');
define ("API_ATTRIBUTE", 'all');


$daurl = sprintf("%s?api_key=%s&api_secret=%s&detector=%s&attributes=%s&urls=%s", API_URL, API_KEY, API_SECRET, API_DETECTOR,API_ATTRIBUTE,$_GET['urls']);


// Get that website's content
$handle = fopen($daurl, "r");

// If there is something, read and return
if ($handle) {
	echo $_GET['jsoncallback']."(";
	while (!feof($handle)) {
		$buffer = fgets($handle, 4096);
		echo $buffer;
	}
	echo ")";
	fclose($handle);
}
?>
