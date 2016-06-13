<?php
session_start();
echo '
<a class="redirect" href="home.php"><img id="banner" src="resources/bannerDesign.png"></a>


<ul id="navbar">
  <li><a href="home.php">Home</a></li>
  <li><a href="pokemon.php">Pokemon</a></li>
  <li><a href="trainer.php">Trainer</a></li>
  ';?>
  <?php
  if($_SESSION['logged_in'] == false) {
  	echo '
  <li><a href="admin.php">Admin</a></li>';}?>
 <?php
 echo '
  <li><a href="login.php">Admin Login</a></li>
</ul>
	

';
?>