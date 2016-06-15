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

if ($db_conn && isset($trainerID)) {
  //get trainer info
  $sqlStmt = "select * from trainers where TRAINERID = " . $trainerID;
  $trainerStatement = OCIParse($db_conn, $sqlStmt); 
  OCIExecute($trainerStatement);
  
  $columnName = 'TRAINERNAME';
  $trainerExists = printTrainerInfo($trainerStatement, $columnName, $trainerID);
  
  if($trainerExists) {
	//get item info
	$itemName = 'ITEMNAME';
	$itemCount = 'ITEMCOUNT';
	$itemCost = 'COST';
	$itemTotalCost = 'TOTALCOST';
	$trainerTable = 'trainers';
	$itemsTable = 'items';
	$itemSqlStmt = "select {$itemName}, count(*) as {$itemCount} from {$trainerTable} natural inner join {$itemsTable} where TRAINERID = {$trainerID} group by {$itemName}";
	$itemStatement = OCIParse($db_conn, $itemSqlStmt); 
	OCIExecute($itemStatement);

	echo '<h2>Item List</h2>';
	printResultAsTable2Columns($itemStatement, $itemName, $itemCount);
	  
	$itemCostSqlStmt = "
		select unique {$itemName}, {$itemCost}
		from {$trainerTable} natural inner join {$itemsTable} 
		where TRAINERID = {$trainerID} 
		and {$itemCost} >= ALL(select {$itemCost} from {$itemsTable})
	";
	
	$itemCostStatement = OCIParse($db_conn, $itemCostSqlStmt); 
	OCIExecute($itemCostStatement);
	  
	echo '<br> Your most expensive item is: ';
	printResultAsTable2Columns($itemCostStatement, $itemName, $itemCost);
	
	$itemTotalCostSqlStmt = "
		select sum({$itemCost}) AS {$itemTotalCost}
		from {$trainerTable} natural inner join {$itemsTable}
		where TRAINERID = {$trainerID}
	";
	$itemTotalCostStatement = OCIParse($db_conn, $itemTotalCostSqlStmt); 
	OCIExecute($itemTotalCostStatement);
	  
	echo '<br> Total cost of all items: ';
	printResultAsTable1Column($itemTotalCostStatement, $itemTotalCost);

	//get pokemon info
	$pokemonSqlStmt = "select * from {$trainerTable}, pokemon where {$trainerTable}.TRAINERID = pokemon.TRAINERID and {$trainerTable}.TRAINERID = " . $trainerID;
	$pokemonStatement = OCIParse($db_conn, $pokemonSqlStmt); 
	OCIExecute($pokemonStatement);
	  
	$pokemonName = 'POKEMONNAME';
	echo '<h2>Pokemon List</h2>';
	printResultAsTable1Column($pokemonStatement, $pokemonName);
  }
}
OCILogoff($db_conn);

//helper functions
function printTrainerInfo($result, $columnName, $trainerID) {
	$row = OCI_Fetch_Array($result, OCI_BOTH);
	$value = $row[$columnName];
	if(isset($value)) {
		echo 'Welcome, Trainer ' . $value . '!';
	} else {
		echo '<script>alert("No trainer exists with ID '. $trainerID .'")</script>' ;
		return false;
	}
	return true;
}

function printResultAsTable1Column($result, $columnName) {
	echo "<table>";
	echo "<tr><th>" . $columnName . '</th></tr>';
	
	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr><td>" . $row[$columnName] . "</td></tr>";
	}
	echo "</table>";
}

function printResultAsTable2Columns($result, $columnName1, $columnName2) {
	echo "<table>";
	echo "<tr><th>" . $columnName1 . '</th><th>' . $columnName2 ."</th></tr>";
	
	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo '<tr><td class="twoCol">' . $row[$columnName1] . '</td><td class="twoCol">'. $row[$columnName2] .'</td></tr>';
	}
	echo "</table>";
}
?>


</body>
</html>