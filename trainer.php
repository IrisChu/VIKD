<html>
<head>
<script src="script.js"></script>
<link href="style.css" rel="stylesheet">
<title>VIKD</title>
</head>
<body id="container">
<?php require('banner.php')?>

<h1 class="header"> Trainer Page</h1>

<form id="search-container" action="trainer.php" method="post">
  Your trainer ID: <input type="number" name="pokemonID" min="0"/>
  <input class="button" type="submit" value="Submit"/>
</form> 

<?php
echo "<p>Welcome: " . $_REQUEST['pokemonID'];
//TODO: get trainer data and display here
?>


</body>
</html>