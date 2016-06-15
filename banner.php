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
}(document, "script", "twitter-wjs"));</script>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6";
  fjs.parentNode.insertBefore(js, fjs);
}(document, "script", "facebook-jssdk"));</script>';

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
	Tweet</a>

  <div class="fb-share-button" data-href="http://www.ugrad.cs.ubc.ca/~w4k8/home.php" data-layout="button_count" data-mobile-iframe="false"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fwww.ugrad.cs.ubc.ca%2F%7Ew4k8%2Fhome.php&amp;src=sdkpreparse">Share</a></div>';





?>