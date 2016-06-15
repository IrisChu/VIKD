<?php
ini_set('session.save_path', 'session');
session_start();

echo '
<script>window.twttr = (function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0],
    t = window.twttr || {};
  if (d.getElementById(id)) return t;
  js = d.createElement(s);
  js.id = id;
  js.src = "https://platform.twitter.com/widgets.js";
  fjs.parentNode.insertBefore(js, fjs);
 
  t._e = [];
  t.ready = function(f) {
    t._e.push(f);
  };
 
  return t;
}(document, "script", "twitter-wjs"));</script>';

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

  echo '<br><a class="twitter-share-button"
  href="https://twitter.com/intent/tweet?text=Check%20out%20the%20new%20Pokedex%20DB%20@"
  >
	Tweet</a>';





?>