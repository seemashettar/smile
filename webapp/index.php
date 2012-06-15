<?php
$title = "Home";
include("top.php");

if(isset($_SESSION["error"])){
	echo "<pre><i class='icon-remove'></i>  {$_SESSION['error']}</pre>";
	unset($_SESSION["error"]);
}
if(!isset($_SESSION["login"])){
?>


<div class="row">
        <div class="span5">
		<div class="hero-unit">
			<h1>Welcome!</h1>
			<p>This is Yourbook social network!</p>
			<p><a class="btn btn-primary btn-large">Go to Profile »</a></p>
		</div>
        </div>
        <div class="span5">
		<form method="post" action="process.php" class="well">
		  Email: <input type="text" class="input-medium" placeholder="username" name='username'><br/>
		  Password: <input type="password" class="input-small" placeholder="Password" name='password'><br/>
		  <input type='hidden' name='login' />
		  <button type="submit" class="btn">Login</button>  <a href="signup.php">Create a new account!</a>
		</form>
        </div>
</div>
 

<?php
}
else{ 
?>
		<div class="hero-unit">
			<h1>Welcome <?php echo $_SESSION["login"]["username"] ?>!</h1>
			<!-- <p>This is Yourbook social network!</p>
			<p><a class="btn btn-primary btn-large" href="profile.php">Go to Profile »</a></p> -->
		</div>

<?php
}

include("down.php"); 
