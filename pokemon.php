<html>
<head>
<script src="script.js"></script>
<link href="style.css" rel="stylesheet">
<title>VIKD</title>
</head>
<body id="container">
<?php require('banner.php')?>

<h1 class="header"> Pokemon Page</h1>

<form id="search-container" action="pokemon.php" method="post">
  Pokemon ID: <input type="number" name="pokemonID" min="0"/>
  <input class="button" type="submit" value="Submit"/>
</form> 

<?php
echo "<p>The pokemon id submitted was: " . $_REQUEST['pokemonID'];
?>

<!--
<p id="hideMe">Hide Me!</p>
<button class="button" onclick="clicked()">Hide</button>
-->

</body>
</html>