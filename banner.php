<?php
ini_set('session.save_path', 'session');
session_start();

echo '
	<a class="redirect" href="home.php"><img id="banner" src="resources/bannerDesign.png"></a>

	<ul id="navbar">
	  <li><a href="home.php">Home</a></li>
	  <li><a href="pokemon.php">Pokemon</a></li>
	  <li><a href="trainer.php">Trainer</a></li>
';
  
  if($_SESSION['logged_in'] == true) {
  	echo '<li><a href="admin.php">Admin</a></li>';
	echo '<li id="logout"><a href="login.php">Admin Logout</a></li>';
  } else {
	 echo '<li><a href="login.php">Admin Login</a></li>';
  }
  
  echo '
  </ul>
';
?>