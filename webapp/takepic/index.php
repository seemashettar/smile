<?php
session_start();

if(isset($_SESSION["error"])){
	echo "<pre><i class='icon-remove'></i>  {$_SESSION['error']}</pre>";
	unset($_SESSION["error"]);
}
if(!isset($_SESSION["login"])) header("Location: ../");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Smile Project</title>

<link rel="stylesheet" type="text/css" href="assets/css/styles.css" />
<link rel="stylesheet" type="text/css" href="assets/fancybox/jquery.fancybox-1.3.4.css" />

</head>
<body>

<div id="topBar">
    <h1>Smile Project</h1>
    <div style='float:right;margin-right:80px;margin-top:40px;font-size:30px;'>You are <span id="emotion"></span>!</div>
</div>

<div id="photos"></div>

<div id="camera">
	<span class="tooltip"></span>
	<span class="camTop"></span>
    
    <div id="screen"></div>
    <div id="buttons">
    	<div class="buttonPane">
        	<a id="shootButton" href="" class="blueButton">Shoot!</a>
        </div>
        <div class="buttonPane hidden">
        	<a id="cancelButton" href="" class="blueButton">Cancel</a> <a id="uploadButton" href="" class="greenButton">Upload!</a>
        </div>
    </div>
    
    <span class="settings"></span>
</div>
<!--
<div style="display:none;">
	<div id="inline2">
		<p>
			You are <span id="emotion"></span>! &nbsp;&nbsp; <a href="javascript:;" onclick="$.fancybox.close();">Close</a>
		</p>
	</div>
</div>
<div id="emotionmessage" class="fancybox" href="#inline2" style="display:none;"></div>
-->

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script src="assets/fancybox/jquery.easing-1.3.pack.js"></script>
<script src="assets/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script src="assets/webcam/webcam.js"></script>
<script src="assets/js/script.js"></script>
<!--
<script>
$('#emotionmessage').fancybox({
		'modal' : true,
	});
//$('#happy').click();
</script>
-->
</body>
</html>
