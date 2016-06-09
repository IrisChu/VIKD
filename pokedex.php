<html>
<?php
$db_conn = OCILogon("ora_k7b8", "a73488090", "ug");

function printResult($result) {
	echo "<br>FROM TABLE TYPE:<br>";
	echo "<table>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr><td>" . $row['TYPENAME'] . "</td></tr>";
	}
	echo "</table>";
}

if ($db_conn) {
  $sqlStmt = "select * from type";
  $statement = OCIParse($db_conn, $sqlStmt); 
  OCIExecute($statement);
  printResult($statement);
}
OCILogoff($db_conn);

?>

</html>
