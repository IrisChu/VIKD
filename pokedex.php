<html>
<?php
$db_conn = OCILogon("ora_w6g0b", "a22262142", "ug");
$success = True; //keep track of errors so it redirects the page only if there are no errors

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
	} else {

	}
	return $statement;

}

function printResult($result) { //prints results from a select statement
	echo "<br>Got data from table TYPE:<br>";
	echo "<table>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr><td>" . $row['typeName'] . "</td></tr>";
	}
	echo "</table>";

if ($db_conn) {

  $x = executePlainSQL("select * from type");
  echo isset($x);
  printResult($x);
  //printResult($x);
OCICommit($db_conn);

}
OCILogoff($db_conn);

?>

</html>
