<?php
ini_set('display_errors', 'On');
require_once('config.php');
$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die ('Your DB connection is misconfigured. Enter the correct values and try again.');
$title = "Members";
include_once("top.php");
if(!isset($_SESSION["login"])){
	header("Location: index.php");
}
if(isset($_SESSION["error"])){
	echo "<pre><i class='icon-remove'></i>  {$_SESSION['error']}</pre>";
	unset($_SESSION["error"]);
}

	$query = "SELECT * FROM a4_person";	
	$result = mysqli_query($link, $query);
	//$result_fetch =  mysqli_fetch_assoc($result);

?>
<div class="row">
		<div class="span2">

		</div>
		<div class="span10">
		<a href="process.php?downloadmembers" class="btn btn-primary">Download members</a>
		<a data-toggle="modal" href="#myModal" class="btn btn-inverse">Upload members</a> </br> </br>
			<table class="table table-striped table-bordered table-condensed">
			  <thead>
			    <tr>
			      <th>Email</th>
				  <th>Full Name</th>
			      <th>Date</th>
			      <th>Profile Pic</th>
			      <th>Delete</th>	
			    </tr>
			  </thead>
			  <tbody> 
<?php
			while($row = mysqli_fetch_assoc($result)) {
			$delete = "";
			if($row["per_id"] != $_SESSION["login"]["id"])
				$delete = "<a href=\"process.php?profileid=".$row["per_id"]."\">Delete</a>";
echo <<<EOD
			<tr>
				<td><a href="blog.php?user={$row["email"]}">{$row["email"]}</a></td>
				<td>{$row["fullname"]}</td>
				<td>{$row["date"]}</td>
				<td><img src="{$row["picture"]}" width="50px" height="50px"/></td>
				<td>$delete</td>
			</tr>
EOD;
			}
?>
			  </tbody>
			</table>
		</div>
</div>

<div class="modal fade" id="myModal">
	
  <div class="modal-header">
	<a class="close" data-dismiss="modal">X</a>
	<h3>Upload User members</h3>
  </div>
	<form action="process.php" method="post" id="add_form" enctype="multipart/form-data" style="margin-bottom: 0px;">
		<div class="modal-body">
			<p>
				<input type="file" name="uploadfile" />
			</p>
		</div>
		<input type='hidden' name='uploadmembers' />
		<div class="modal-footer">
			<a href="javascript:$('#myModal').modal('hide')" class="btn">Close</a>
			<button type="submit" class="btn btn-primary">Upload members</button>
		</div>
	</form>
	
</div>
<?php include_once("down.php"); 
