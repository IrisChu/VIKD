<html>
<head>
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
		<input class="button" type="submit" value="Show All" name="showallsubmit">
	</form>

	<form id="sortby-container" method="post" action="pokemon.php">
		<select>
			<option name="sortoption" value="pokemonid"> ID </option>
			<option name="sortoption" value="sname"> Pokemon </option>
		</select>
		<input class="button" type="submit" value="Sort" name="sortby"
	</form>

</div>


<?php
$db_conn = OCILogon("ora_k7b8", "a73488090", "ug");

function printResult($result) {
	echo "<div><table border='1' style='width:100%'>
		<tr>
			<td><b> ID </b></td>
			<td><b> Pokemon </b></td>
			<td><b> Type </b></td>
			<td><b> Gender </b></td>
			<td><b> Location </b></td>
		</tr>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr><td>" . $row['POKEMONID'] . "</td>
					<td>" . $row['SNAME'] . "</td>
					<td>" . $row['TYPENAME'] . "</td>
					<td>" . $row['GENDER'] . "</td>
					<td>" . $row['LOCATIONNAME'] . "</td></tr>";
	}
	echo "</table></div>";
}

function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
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

	printResult($statement);

}

function executeBoundSQL($cmdstr, $list) {
	/* Sometimes a same statement will be excuted for severl times, only
	 the value of variables need to be changed.
	 In this case you don't need to create the statement several times; 
	 using bind variables can make the statement be shared and just 
	 parsed once. This is also very useful in protecting against SQL injection. See example code below for       how this functions is used */

	global $db_conn, $success;
	$statement = OCIParse($db_conn, $cmdstr);

	if (!$statement) {
		echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
		$e = OCI_Error($db_conn);
		echo htmlentities($e['message']);
		$success = False;
	}

	foreach ($list as $tuple) {
		foreach ($tuple as $bind => $val) {
			//echo $val;
			//echo "<br>".$bind."<br>";
			OCIBindByName($statement, $bind, $val);
			unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype

		}
		$r = OCIExecute($statement, OCI_DEFAULT);
		if (!$r) {
			echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
			$e = OCI_Error($statement); // For OCIExecute errors pass the statementhandle
			echo htmlentities($e['message']);
			echo "<br>";
			$success = False;
		}

		printResult($statement);
	}

}

if ($db_conn) {
	if (array_key_exists('showallsubmit', $_POST)) {
		executePlainSQL("select p.pokemonid, p.sname, s.typename, p.gender, p.locationname 
  			from pokemon p, species s
  			where p.sname = s.sname
  			order by p.pokemonid asc");
  		OCICommit($db_conn);
	}
	else if (array_key_exists('sortby', $_POST)) {

		$tuple = array (
			":bind1" => $_POST['sortoption']
		);
		$alltuples = array (
			$tuple
		);

		executeBoundSQL("select p.pokemonid, p.sname, s.typename, p.gender, p.locationname 
  			from pokemon p, species s
  			where p.sname = s.sname
  			order by :bind1 asc", $alltuples);
		OCICommit($db_conn);
	}
 	else if (array_key_exists('pokemonIDsubmit', $_POST)) {
		//Getting the values from user and insert data into the table
		$tuple = array (
			":bind1" => $_POST['pokemonID']
		);
		$alltuples = array (
			$tuple
		);
		executeBoundSQL("select p.pokemonid, p.sname, s.typename, p.gender, p.locationname
			from pokemon p, species s
			where p.sname = s.sname and pokemonid = :bind1", $alltuples);
		OCICommit($db_conn);

		echo "<p>The pokemon id submitted was: " . $_REQUEST['pokemonID'];
	}
}
OCILogoff($db_conn);

?>

<!--
<p id="hideMe">Hide Me!</p>
<button class="button" onclick="clicked()">Hide</button>
-->

</body>
</html>