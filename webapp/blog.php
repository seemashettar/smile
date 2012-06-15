<?php
ini_set('display_errors', 'On');
require_once('config.php');
$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die ('Your DB connection is misconfigured. Enter the correct values and try again.');
$title = "Blog";
include_once("top.php");
if(!isset($_SESSION["login"])){
	header("Location: index.php");
}
if(isset($_SESSION["error"])){
	echo "<pre><i class='icon-remove'></i>  {$_SESSION['error']}</pre>";
	unset($_SESSION["error"]);
}

if ( isset($_GET['user']) )
{
	$query = "SELECT * FROM a4_blog WHERE email = '".$_GET['user']."' ORDER BY id_blog DESC";	
	$result = mysqli_query($link, $query);
}
else{
	$query = "SELECT * FROM a4_blog ORDER BY id_blog DESC";	
	$result = mysqli_query($link, $query);
}

function parse_message($text)
{
	global $link;
	$emails = array();
	$result2 = mysqli_query($link, 'SELECT email FROM a4_person');
	while ($row = mysqli_fetch_assoc($result2)){
		$emails[] = strtolower($row['email']);
	}

	$regex_pageurl = '/(\b|^|\A|\<)(1(-| |\.?))|(-|\.?)(\()?(\d{3})(\)|-|\.| |\)-|\) )?(\d{3})(-| |\.)?(\d{4})(\b|$|\Z|\>)/';
	$replace_url_format = '<span style="font-style:italic; color: #FF0000;">$0</span>';
	$bodytext = htmlspecialchars($text);
	$bodytext = preg_replace('/(((f|ht){1}tps?:\/\/)[-a-zA-Z0-9@:%_\+.~#?&\/\/=;]+)/i', '<a href="\\1" rel="nofollow">\\1</a>', $bodytext);
	$bodytext = preg_replace('/([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_\+.~#?&\/\/=;]+)/i', '\\1<a href="http://\\2" rel="nofollow">\\2</a>', $bodytext);
	$bodytext = preg_replace($regex_pageurl, $replace_url_format, $bodytext);
	return preg_replace('/(\r\n)|(\n)/', '<br/>', $bodytext);	
}

?>
<div class="row">
		<div class="span2">
		</div>
		<div class="span10">
		<a data-toggle="modal" href="#myModal" class="btn btn-primary">Add a new post</a> </br> </br>
			<table class="table table-striped table-bordered table-condensed">
			  <tbody> 
<?php
			while($row = mysqli_fetch_assoc($result)) {
			$message = parse_message($row["blogcontent"]);
echo <<<EOD
			<tr>
				<td>
					<h3>{$row["title"]}</h3>
					<h6>{$row["date"]}<h6>
					<div>{$message}</div>
				</td>
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
	<h3>Add a new post</h3>
  </div>
	<form method="post" action="process.php" style="margin-bottom: 0px;">
	  <div class="modal-body">
		<p>Title: <input type="text" class="input-xlarge" name='title' placeholder="Title of the post ..." maxlength="50"></p>
		<p>Body: <textarea class="input-xlarge" id="textarea" name="blogcontent" rows="5"  placeholder="Body of the post ..." maxlength="400" ></textarea></p>
		<input type='hidden' name='email' value = "<?php echo $_SESSION["login"]["email"] ?> " />
		<input type='hidden' name='blogpost' />
	  </div>
	  <div class="modal-footer">
		<a href="javascript:$('#myModal').modal('hide')" class="btn">Close</a>
		<button type="submit" class="btn btn-primary">Save changes</button>
	  </div>
	</form> 
</div>
<?php include_once("down.php"); 
