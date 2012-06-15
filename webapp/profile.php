<?php
ini_set('display_errors', 'On');
require_once('config.php');
$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die ('Your DB connection is misconfigured. Enter the correct values and try again.');
$title = "Profile";
include_once("top.php");
if(!isset($_SESSION["login"])){
	header("Location: index.php");
}
if(isset($_SESSION["error"])){
	echo "<pre><i class='icon-remove'></i>  {$_SESSION['error']}</pre>";
	unset($_SESSION["error"]);
}

	$query = "SELECT * FROM a4_person WHERE per_id =".$_SESSION["login"]["id"];	
	$result = mysqli_query($link, $query);
	$result_fetch =  mysqli_fetch_assoc($result);

?>
<div class="row">
		<div class="span5">
			<p>Email: <?php echo $result_fetch["email"] ?></p>
			<p>Full Name: <?php echo $result_fetch["fullname"] ?> <a class="btn" data-toggle="modal" href="#myModal" >Edit Name</a></p> 
			<p>Joined on:  <?php echo $result_fetch["date"] ?></p>
			<ul class="thumbnails">
				<li class="span2">
					<a href="#" class="thumbnail">
						<img src="<?php echo $result_fetch["picture"] ?>" height="120px" width="160px" alt="">
					</a>
				</li>
			</ul>
		</div>
		<div class="span5">
			<form action="process.php" method="post" id="add_form" enctype="multipart/form-data">
				<p>
					<input type="file" name="person_picture" id="person_picture" />
				</p>
				<input type='hidden' name='profile_upload_pic' />
				<button type="submit" class="btn">Change Profile Picture</button>
			</form>
		</div>
</div>

<div class="modal fade" id="myModal">
	
  <div class="modal-header">
	<a class="close" data-dismiss="modal">X</a>
	<h3>Edit Full Name</h3>
  </div>
	<form method="post" action="process.php" style="margin-bottom: 0px;">
	  <div class="modal-body">
		<p>Fullname: <input type="text" class="input-large" value="<?php echo $result_fetch["fullname"] ?>" name='fullname'></p>
		<input type='hidden' name='user_id' value = "<?php echo $result_fetch["per_id"] ?> " />
		<input type='hidden' name='edit_fullname' />
	  </div>
	  <div class="modal-footer">
		<a href="javascript:$('#myModal').modal('hide')" class="btn">Close</a>
		<button type="submit" class="btn btn-primary">Save changes</button>
	  </div>
	</form> 
</div>
<?php include_once("down.php"); 
