<?php
$title = "Signup";
include_once("top.php");

if(isset($_SESSION["Result"]))
{
	echo $_SESSION["Result"];
}

if( isset($_POST['signup']) ){
	echo "<pre>";
	print_r($_POST);
	echo "</pre>";
}
?>


<div class="row">
        <div class="span4">
.
        </div>
        <div class="span4">
		<form method="post" action="process.php" class="well form-inline">
			Username: <input class="input-large" placeholder="username..." type="text" name='username'> </br></br>
			Password: <input type="password"  class="input-large" name='password'></br>
			<input type='hidden' name='signup' />
			<button type="submit" class="btn">Signup</button>
		</form>
        </div>
</div>


<?php include_once("down.php"); ?>
