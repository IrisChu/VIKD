<?php

	session_start();

	$username = "admin";
	$password = "admin";

	if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true){
		$_SESSION['logged_in'] = true;
		header("Location: home.php");
	}

	if (isset ($_POST['username']) && isset($_POST['password'])) {
		if ($_POST['username'] == $username && $_POST['password'] == $password){
			$_SESSION['logged_in'] = true;
			header("Location: home.php");
		}
	}
	
?>
<html>
<head>
<script src="script.js"></script>
<link href="style.css" rel="stylesheet">
<title>VIKD</title>
</head>
<body id="container">
<?php require('banner.php')?>



<h1 class="header"> Administration Login Page</h1>

		<form method="post" action="login.php">
			Username:<br/>
			<input type="text" name="username"><br/>
			Password:<br/>
			<input type="password" name="password"><br/>
			<input type="submit" value="Login">
		</form>
	</body>
</html>
