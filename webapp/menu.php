          <div class="nav-collapse">
            <ul class="nav">
              <li class="active"><a href="index.php">Home</a></li>
         <!-- <li><a href="profile.php">Profile</a></li>
              <li><a href="members.php">Members</a></li>
			  <li><a href="blog.php">Blog</a></li> -->
<?php
if(isset($_SESSION["login"]["id"]))
	echo '<li><a href="logout.php">Logout</a></li>';
else
	echo '<li><a href="index.php">Login</a></li>';
 ?>
            </ul>
          </div><!--/.nav-collapse -->
