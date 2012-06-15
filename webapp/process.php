<?php
ini_set('display_errors', 'On');
session_start();
require_once('config.php');
$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die ('Your DB connection is misconfigured. Enter the correct values and try again.');

if( isset($_POST['signup']) ){
	$password = $_POST['password'];
	$password_encrypt = md5(trim($password));

	$query = "INSERT INTO tavakoli.smile_person (username,password, date) VALUES (";
	$query .= "'".mysqli_real_escape_string($link, $_POST['username'])."',";
	$query .= "'".mysqli_real_escape_string($link, $password_encrypt)."',";			
	$query .= "'".date("Y-m-d H:i:s")."'";
	$query .= ");";	
	echo $query;
	$result = mysqli_query($link, $query);
	header("Location: index.php");
}
if( isset($_POST['login']) ){
	$query = "SELECT * FROM tavakoli.smile_person WHERE username = '".$_POST['username']."'";	
	$result = mysqli_query($link, $query);
	$result_fetch =  mysqli_fetch_assoc($result);
	$password_fromDb = $result_fetch['password'];
	$password_fromUser = md5(trim($_POST['password']));
	if($password_fromDb == $password_fromUser){
		$_SESSION["login"]["id"] = $result_fetch['per_id'];
		$_SESSION["login"]["username"] = $_POST['username'];
	}
	else{
		$_SESSION["error"] = "Username or password were incorrect!";
	}
	// echo "<pre>";
	// echo $_SESSION["error"];
	// echo "password_fromDb: ".$password_fromDb;
	// echo "password_fromUser: ".$password_fromUser;
	// echo "</pre>";
	header("Location: takepic/");
}

if( isset($_GET['emotion']) ){
	$result = 0;
	if(!isset($_SESSION["login"]["id"])) die("{'message':'authorization error'}");

	$query = "INSERT INTO tavakoli.smile_profile (username,happy, timestamp) VALUES (";
	$query .= "'".mysqli_real_escape_string($link, $_SESSION["login"]["username"])."',";
	if(trim($_GET['emotion'])=="happy")
	{
		$query .= "1,";	
	}
	else
	{
		$query .= "0,";	
	}	
	$query .= "'".date("Y-m-d H:i:s")."'";
	$query .= ");";	
	$result = mysqli_query($link, $query);
	if(!result) echo "{'message':'error with database'}";
}


if ( isset( $_GET['stats'] ) ) 
{
	$query = "SELECT COUNT(happy) as happy FROM `tavakoli`.`smile_profile` WHERE happy=1;";	
	$result = mysqli_query($link, $query);
	$result_fetch =  mysqli_fetch_assoc($result);
	$happy = $result_fetch['happy'];
	$query = "SELECT COUNT(happy) as happy FROM `tavakoli`.`smile_profile` WHERE happy=0;";	
	$result = mysqli_query($link, $query);
	$result_fetch =  mysqli_fetch_assoc($result);
	$sad = $result_fetch['happy'];
	echo "{\"happy\":\"$happy\",\"sad\":\"$sad\"}";
}


if (isset($_POST['profile_upload_pic'])) {
	if(!isset($_SESSION["login"]["id"]))
		header("Location: index.php");
	$file_destination = 'uploads/'.$_FILES['person_picture']['name'];
	
	$upload_path = pathinfo($file_destination);
	
	if (file_exists($file_destination)) {
	
		$counter = 0;
		
		while (file_exists($upload_path['dirname']."/".$upload_path['filename']."_".$counter.".".$upload_path['extension']) ) {
		
			$counter++;
		
		}
		
		$file_destination = $upload_path['dirname']."/".$upload_path['filename']."_".$counter.".".$upload_path['extension'];
	}

	move_uploaded_file($_FILES['person_picture']['tmp_name'], $file_destination);
	
	chmod($file_destination, 0755);		

	$query = "UPDATE a4_person SET ";
	$query .= " picture = '".mysqli_real_escape_string($link, $file_destination)."'";
	$query .= " WHERE per_id = ".mysqli_real_escape_string($link, $_SESSION["login"]["id"]);
	$result = mysqli_query($link, $query);				
	
	
	header ('Location: profile.php');

}
if ( isset( $_GET['profileid'] ) ) 
{
	if(!isset($_SESSION["login"]["id"]))
		header("Location: index.php");
	$query = "SELECT * FROM a4_person WHERE per_id = ".mysqli_real_escape_string($link, $_GET['profileid']);	
	$result = mysqli_query($link, $query);
	$result_fetch =  mysqli_fetch_assoc($result);
	unlink($result_fetch["picture"]);

	$query = "DELETE FROM a4_person WHERE per_id = ".mysqli_real_escape_string($link, $_GET['profileid']);
	$result = mysqli_query($link, $query);
	header ('Location: members.php');
}
if ( isset( $_POST['edit_fullname'] ) ) 
{
	if(!isset($_SESSION["login"]["id"]))
		header("Location: index.php");
	$query = "UPDATE a4_person SET ";
	$query .= " fullname = '".mysqli_real_escape_string($link, $_POST['fullname'])."'";
	$query .= " WHERE per_id = ".mysqli_real_escape_string($link, $_POST['user_id']);
	$result = mysqli_query($link, $query);				
	
	header ('Location: profile.php');
}
if ( isset($_GET['downloadmembers']) )
{
	$outputstreamfile = fopen('php://output', 'w');
	header('Content-type: text/csv');
	header('Cache-Control: no-store, no-cache');
	header('Content-disposition: attachment; filename="membersexport.csv"');

	$query = "SELECT email,password,date,picture,fullname,password_is_encrypted FROM a4_person";

	$result = mysqli_query($link, $query);
	if (!$result){
		die('Failed to get user data from the database: ');
	}

	fputcsv($outputstreamfile, array('email','password','date','picture','fullname','password_is_encrypted'), ',', '"');
	while($row = mysqli_fetch_assoc($result)){
		fputcsv($outputstreamfile, $row, ',');
	}
	fclose($outputstreamfile);
	  
}
if( isset($_POST['uploadmembers']) )
{
	$filecsv = fopen($_FILES['uploadfile']['tmp_name'], 'r');
	if (!$filecsv){
		$_SESSION["error"] = "The file can not be opened!";
		break;
	}

	$query  = 'INSERT INTO a4_person (email,password, date, picture, fullname, password_is_encrypted) ';
	$query .= 'VALUES (\'%s\', %s, now(), \'%s\', \'%s\', %s )';
	echo "<pre>";
	$count = 1;
	while (($data = fgetcsv($filecsv, 1000, ",")) !== FALSE) {
		if ($count++ == 1){
			continue;
		}
		$prepared_query = sprintf($query, 
				mysqli_real_escape_string($link, strtolower($data[0])),
				(($data[5] == 0) 
					 ? 'MD5(\''.mysqli_real_escape_string($link, $data[1]).'\')'
					 : '\''.mysqli_real_escape_string($link, $data[1]).'\'' ),
				mysqli_real_escape_string($link, $data[3]),				
				mysqli_real_escape_string($link, $data[4]),
				mysqli_real_escape_string($link, $data[5]));
	  $result = mysqli_query($link, $prepared_query);
	  echo $prepared_query."\n";
	}
	echo "</pre>";
	fclose($filecsv);
	header ('Location: members.php');
}
if( isset($_POST['blogpost']) )
{
	$query = "INSERT INTO a4_blog (title,blogcontent, email, date) VALUES (";
	$query .= "'".mysqli_real_escape_string($link, $_POST['title'])."',";
	$query .= "'".mysqli_real_escape_string($link, $_POST['blogcontent'])."',";			
	$query .= "'".mysqli_real_escape_string($link, $_POST['email'])."',";			
	$query .= "'".date("Y-m-d H:i:s")."'";
	$query .= ")";	
	$result = mysqli_query($link, $query);

	header ('Location: blog.php');
}
// function closeredirect($location){
// }
mysqli_close($link);
