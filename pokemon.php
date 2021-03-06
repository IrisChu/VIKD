<html>
<head>
<script src="resources/jquery-3.0.0.js"></script>
<script src="script.js"></script>
<link href="style.css" rel="stylesheet">
<title>VIKD</title>
</head>
<body id="container">
<?php require('banner.php')?>

<h1 class="header"> Pokemon Page</h1>

<div>
	<form id="search-container" action="pokemon.php" method="post">
		Pokemon ID: <input type="number" name="pokemonID" min="0">
		<input class="button" type="submit" value="Submit" name="pokemonIDsubmit">
	</form>


	<form id="showall-container" method="post" action="pokemon.php">
		<input class="button" type="submit" name="showallsubmit" value="Show All"></button>
	</form>

	<form id="sortby-container" method="post" action="pokemon.php">
		Sort By:
		<select name="sortbyoption">
			<option value="p.pokemonid"> ID </option>
			<option value="p.sname"> Pokemon </option>
			<option value="s.typename"> Type </option>
			<option value="p.gender"> Gender </option>
			<option value="p.locationname"> Location </option>
		</select>
		<input class="button" type="submit" value="Go!" name="sortbysubmit">
	</form>
</div>

<form method="post" action="pokemon.php">
	<input type="submit" class="typebutton" value="Fire" name="typename"></button>
	<input type="submit" class="typebutton" value="Water" name="typename"></button>
	<input type="submit" class="typebutton" value="Grass" name="typename"></button>
	<input type="submit" class="typebutton" value="Lightning" name="typename"></button>
	<input type="submit" class="typebutton" value="Rock" name="typename"></button>
	<input type="submit" class="typebutton" value="Psychic" name="typename"></button>
</form>


<?php
$db_conn = OCILogon("ora_k7b8", "a73488090", "ug");

if ($db_conn) {
	// Show All Pressed
	if (isset ($_POST['showallsubmit'])) {
		executeSQL("select p.pokemonid, p.sname, s.typename, p.gender, p.locationname 
  			from pokemon p, species s
  			where p.sname = s.sname
  			order by p.pokemonid asc", "printResult");
  		OCICommit($db_conn);
	}
	// Sort By Pressed
	else if (isset ($_POST['sortbysubmit'])) {

		$sortby = $_REQUEST['sortbyoption'];
		$sortbyquery = "select p.pokemonid, p.sname, s.typename, p.gender, p.locationname
		from pokemon p, species s
		where p.sname = s.sname
		order by {$sortby} asc";

		executeSQL($sortbyquery, "printResult");
		OCICommit($db_conn);
	}
	// Search ID Pressed
 	else if (isset($_POST['pokemonIDsubmit'])) {
		//Getting the values from user and insert data into the table
		
 		$pokemonID = $_REQUEST['pokemonID'];
 		$searchquery = "select p.pokemonid, p.sname, s.typename, p.gender, p.locationname
			from pokemon p, species s
			where p.sname = s.sname and pokemonid = {$pokemonID}";

		executeSQL($searchquery, $pokemonID);
		OCICommit($db_conn);
	}
	// Type Pressed
	else if (isset($_POST['typename'])) {

		$type = $_POST['typename'];

		executeSQL("select distinct s.sname, s.preevo, s.postevo, s.typename, t.strength, t.weakness
			from species s, type t
			where s.typename = t.typename
			and s.typename = '{$type}'", "printType");
		OCICommit($db_conn);
	}
	// Default Show All
	else {
		executeSQL("select p.pokemonid, p.sname, s.typename, p.gender, p.locationname 
  			from pokemon p, species s
  			where p.sname = s.sname
  			order by p.pokemonid asc", "printResult");
  		OCICommit($db_conn);
	}
}
OCILogoff($db_conn);

function printResult($result) {

	echo "<div><table>
		<tr>
			<th><b> ID </b></th>
			<th><b> Pokemon </b></th>
			<th><b> Type </b></th>
			<th><b> Gender </b></th>
			<th><b> Location </b></th>
		</tr>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr><td>" . $row['POKEMONID'] . "</td>
					<td>" . $row['SNAME'] . "</td>
					<td>" . $row['TYPENAME'] . "</td>
					<td>" . $row['GENDER'] . "</td>
					<td>" . $row['LOCATIONNAME'] . "</td></tr>";
	}
	echo "</table></div><br><br><br><br><br>";
}

function printPokemon($result, $pokemonID) {

	echo "<div><table>
		<tr>
			<th><b> ID </b></th>
			<th><b> Pokemon </b></th>
			<th><b> Type </b></th>
			<th><b> Gender </b></th>
			<th><b> Location </b></th>
		</tr>";

	$row = OCI_Fetch_Array($result, OCI_BOTH);
	$value = $row['POKEMONID'];

	if(isset($value)) {
		echo "<tr><td>" . $row['POKEMONID'] . "</td>
					<td>" . $row['SNAME'] . "</td>
					<td>" . $row['TYPENAME'] . "</td>
					<td>" . $row['GENDER'] . "</td>
					<td>" . $row['LOCATIONNAME'] . "</td></tr>";
		
	} else {
		echo '<script>alert("No pokemon exists with ID '. $pokemonID .'")</script>' ;
	}

	echo "</table></div><br><br><br><br><br>";

}

function printType($result) {
	echo "<div><table>
		<tr>
			<th><b> Pokemon </b></th>
			<th><b> Pre Evolution </b></th>
			<th><b> Post Evolution </b></th>
			<th><b> Type </b></th>
			<th><b> Strengths </b></th>
			<th><b> Weaknesses </b></th>
		</tr>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr><td>" . $row['SNAME'] . "</td>
					<td>" . $row['PREEVO'] . "</td>
					<td>" . $row['POSTEVO'] . "</td>
					<td>" . $row['TYPENAME'] . "</td>
					<td>" . $row['STRENGTH'] . "</td>
					<td>" . $row['WEAKNESS'] . "</td></tr>";
	}
	echo "</table></div><br><br><br><br><br>";
}

function executeSQL($cmdstr, $print) { //takes a plain (no bound variables) SQL command and executes it
	//echo "<br>running ".$cmdstr."<br>";
	global $db_conn, $success;
	$statement = OCIParse($db_conn, $cmdstr); //There is a set of comments at the end of the file that describe some of the OCI specific functions and how they work

	if (!$statement) {
		echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
		$e = OCI_Error($db_conn); // For OCIParse errors pass the       
		// connection handle
		echo htmlentities($e['message']);
		$success = False;
	}

	$r = OCIExecute($statement, OCI_DEFAULT);
	if (!$r) {
		echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
		$e = oci_error($statement); // For OCIExecute errors pass the statementhandle
		echo htmlentities($e['message']);
		$success = False;
	}

	if ($print == "printType"){
		printType($statement);
	} else if ($print == "printResult"){	
		printResult($statement);
	} else {
		printPokemon($statement, $print);
	}

}

?>

<!--
<p id="hideMe">Hide Me!</p>
<button class="button" onclick="clicked()">Hide</button>
-->

</body>
</html>