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
$trainerID = $_REQUEST['pokemonID'];
$db_conn = OCILogon("ora_k7b8", "a73488090", "ug");

if ($db_conn) {
  //get trainer info
  $sqlStmt = "select * from trainers where TRAINERID = " . $trainerID;
  $trainerStatement = OCIParse($db_conn, $sqlStmt); 
  OCIExecute($trainerStatement);
  
  $columnName = 'TRAINERNAME';
  printSingleResult($trainerStatement, $columnName);
  
  //get item info
  $itemName = 'ITEMNAME';
  $itemCount = 'ITEMCOUNT';
  $itemSqlStmt = "select {$itemName}, count(*) as {$itemCount} from trainers natural inner join items where TRAINERID = {$trainerID} group by {$itemName}";
  $itemStatement = OCIParse($db_conn, $itemSqlStmt); 
  OCIExecute($itemStatement);
 
  echo '<h2>Item List</h2>';
  printResultAsTable2Columns($itemStatement, $itemName, $itemCount);
  
  //get pokemon info
  $pokemonSqlStmt = "select * from trainers, pokemon where trainers.TRAINERID = pokemon.TRAINERID and trainers.TRAINERID = " . $trainerID;
  $pokemonStatement = OCIParse($db_conn, $pokemonSqlStmt); 
  OCIExecute($pokemonStatement);
  
  $pokemonName = 'POKEMONNAME';
  echo '<h2>Pokemon List</h2>';
  printResultAsTable1Column($pokemonStatement, $pokemonName);
}
OCILogoff($db_conn);

//helper functions
function printSingleResult($result, $columnName) {
	$row = OCI_Fetch_Array($result, OCI_BOTH);
	$value = $row[$columnName];
	if(isset($value)) {
		echo 'Welcome, Trainer ' . $value . '!';
	} else {
		echo '<script>alert("NO '.$columnName. ' EXISTS FOR THAT ID")</script>' ;
	}
}

function printResultAsTable1Column($result, $columnName) {
	echo "<table>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr><td>" . $row[$columnName] . "</td></tr>";
	}
	echo "</table>";
}

function printResultAsTable2Columns($result, $columnName1, $columnName2) {
	echo "<table>";
	echo "<tr><th>" . $columnName1 . '</th><th>' . $columnName2 ."</th></tr>";
	
	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo '<tr><td>' . $row[$columnName1] . '</td><td>'. $row[$columnName2] .'</td></tr>';
	}
	echo "</table>";
}
?>


</body>
</html>