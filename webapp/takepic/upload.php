<?php
error_reporting(E_ALL); 
ini_set("display_errors", 1); 
ini_set("log_errors",1);
ini_set('error_log','my_file.log');

/*
	This file receives the JPEG snapshot
	from webcam.swf as a POST request.
*/

// We only need to handle POST requests:
if(strtolower($_SERVER['REQUEST_METHOD']) != 'post'){
	exit;
}

$folder = 'uploads/';
$filename = md5($_SERVER['REMOTE_ADDR'].rand()).'.jpg';

$original = $folder.$filename;

// The JPEG snapshot is sent as raw input:
$input = file_get_contents('php://input');

if(md5($input) == '7d4df9cc423720b7f1f3d672b89362be'){
	// Blank image. We don't need this one.
	exit;
}

$result = file_put_contents($original, $input);
if (!$result) {
	echo '{
		"error"		: 1,
		"message"	: "Failed save the image. Make sure you chmod the uploads folder and its subfolders to 777."
	}';
	exit;
}

$info = getimagesize($original);
if($info['mime'] != 'image/jpeg'){
	unlink($original);
	exit;
}

// Moving the temporary file to the originals folder:
rename($original,'uploads/original/'.$filename);
$original = 'uploads/original/'.$filename;

chmod($original, 0755);	

// Using the GD library to resize 
// the image into a thumbnail:

$origImage	= imagecreatefromjpeg($original);
$newImage	= imagecreatetruecolor(154,110);
imagecopyresampled($newImage,$origImage,0,0,0,0,154,110,520,370); 

imagejpeg($newImage,'uploads/thumbs/'.$filename);
chmod('uploads/thumbs/'.$filename, 0755);	

echo '{"status":1,"message":"Success!","filename":"'.$filename.'", "original":"'.getcurrentaddr().$original.'"}';



function getcurrentaddr(){
	$pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
	if ($_SERVER["SERVER_PORT"] != "80")
	{
	    $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} 
	else 
	{
	    $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
$pageURL = str_replace("upload.php","",$pageURL);
	return $pageURL;
}
?>
