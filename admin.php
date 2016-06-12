<html>
<head>
<script src="script.js"></script>
<link href="style.css" rel="stylesheet">
<title>VIKD</title>
</head>
<body id="container">
<?php require('banner.php')?>

<h1 class="header"> Administration Page</h1>

<div>
  <form id="search-container" action="admin.php" method="post">
  <h3> Add Pokemon Species </h3>
    Species Name: <br>   <input type="text" name="sname" required> <br>
    Evolves Into: <br> <input type="text" name="postEvo"> <br>
    Evolved From: <br>  <input type="text" name="preEvo"> <br>
    <p> Type: </p>
      <input type="radio" name="type" value="Fire" checked> Fire <br>
      <input type="radio" name="type" value="Water"> Water <br>
      <input type="radio" name="type" value="Grass"> Grass <br>
      <input type="radio" name="type" value="Psychic"> Psychic <br>
      <input type="radio" name="type" value="Rock"> Rock <br>
      <input type="radio" name="type" value="Lightning"> Lightning <br><br>
  <input class="button" type="submit" value="Add">
  </form>
</div>

<?php
$sname = $_REQUEST['sname'];
$postEvo = $_REQUEST['postEvo'];
$preEvo = $_REQUEST['preEvo'];
$type = $_REQUEST['type'];
$db_conn = OCILogon("ora_k7b8", "a73488090", "ug");

if ($db_conn) {
  //add new species
  $insertStmt = "insert into SPECIES values ('{$sname}', '{$postEvo}', '{$preEvo}', '{$type}')";
  $addspecies = OCIParse($db_conn, $insertStmt);
  OCIExecute($addspecies);

  $slist = "select sname, typeName from species";
  $sstmt = OCIParse($db_conn, $slist);
  OCIExecute($sstmt);

  echo '<h2>Species List</h2>';
  printResultAsTable2Columns($sstmt, 'SNAME', 'TYPENAME');
}

OCILogoff($db_conn);
OCICOMMIT($db_conn);

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
