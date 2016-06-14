<html>
<head>
<script src="script.js"></script>
<link href="style.css" rel="stylesheet">
<title>VIKD</title>
</head>
<body id="container">
<?php require('banner.php')?>
<h1 class="header"> Administration Page</h1>
  <form action="admin.php" method="post">
    <h3> Display Statistics </h3>
    Find trainers that own at least one pokemon of each type: <br>
    <input class="button" type="submit" value="Go!" name="hasalltypes"> <br>
              <!--These are just ideas, not necessary to do them all
              Display regions to find the most pokemon of a certain type <br>
              Display cities with more pokemon than trainers <br>
              Display regions that are most popular for pokemon training (most trainers born there) <br>-->
  </form>
<?php
$db_conn = OCILogon("ora_k7b8", "a73488090", "ug");
if ($db_conn) {
  if (array_key_exists('deletetrainer', $_POST)) {
      $tid = $_REQUEST['trainerid'];
      $deletetrainerStmt = "delete from TRAINERS where trainerId = '{$tid}'";
      executeSQL($deletetrainerStmt);
  }
  if (array_key_exists('deletespecies', $_POST)) {
      $sname = $_REQUEST['sname_r'];
      $deletespeciesStmt = "delete from SPECIES where sname='{$sname}'";
      executeSQL($deletespeciesStmt);
  }
  if (array_key_exists('deletepokemon', $_POST)) {
      $pid = $_REQUEST['pid_r'];
      $deletepokemonStmt = "delete from POKEMON where pokemonId='{$pid}'";
      executeSQL($deletepokemonStmt);
  }
  if (array_key_exists('addpokemon', $_POST)) {
      $pid = $_REQUEST['pid_add'];
      $species = $_REQUEST['pspecies_add'];
      $name = $_REQUEST['pname_add'];
      $gender = $_REQUEST['pgender_add'];
      $location = $_REQUEST['plocation_add'];
      $tid = $_REQUEST['ptrainer_add'];
      $addpokemonStmt = "insert into POKEMON values ('{$pid}', '{$species}', '{$gender}', '{$name}', '{$location}', '{$tid}')";
      executeSQL($addpokemonStmt);
  }
  if (array_key_exists('addtrainer', $_POST)) {
    //Add new Trainer
    $tid = $_REQUEST['tid'];
    $tname = $_REQUEST['tname'];
    $gender = $_REQUEST['gender'];
    $lname = $_REQUEST['birthplace'];
    $newtrainerStmt = "insert into TRAINERS values ('{$tid}', '{$tname}', '{$gender}', '{$lname}')";
    executeSQL($newtrainerStmt);
  }
  //add new species
  if (array_key_exists('addspecies', $_POST)) {
    $sname = $_REQUEST['sname'];
    $postEvo = $_REQUEST['postEvo'];
    $preEvo = $_REQUEST['preEvo'];
    $type = $_REQUEST['type'];
    $insertStmt = "insert into SPECIES values ('{$sname}', '{$postEvo}', '{$preEvo}', '{$type}')";
    executeSQL($insertStmt);
  }
 //update Pokemon information
 //TODO: add update constraint
  if (array_key_exists('updatepokemon', $_POST)) {
    $updatefield = $_REQUEST['updatefield'];
    $updatevalue = $_REQUEST['newvalue'];
    $pid = $_REQUEST['pid'];
    $updateStmt = "update Pokemon set {$updatefield}='{$updatevalue}' where pokemonId='{$pid}'";
    executeSQL($updateStmt);
  }
  $slistStmt = "select sname, typeName from species order by sname";
  $sstmt = executeSQL($slistStmt);
  echo '<h2>Species List</h2>';
  printResultAsTable2Columns($sstmt, 'SNAME', 'TYPENAME');
}
OCILogoff($db_conn);
OCICOMMIT($db_conn);
// helper functions
function executeSQL($cmdstring) {
  //TODO: add error checking
  global $db_conn;
  $statement = OCIParse($db_conn, $cmdstring);
  OCIExecute($statement);
  OCICommit($db_conn);
  return $statement;
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
<br><br><br>
<form id="search-container" action="admin.php" method="post">
  <h3> Add New Trainer </h3>
  Trainer Id:   <br>   <input type="number" name="tid" required min="0"> <br>
  Trainer Name: <br>   <input type="text" name="tname" maxlength="30"> <br>
  Birthplace:   <br>
    <select name="birthplace">
      <option value="Azure Bay"> Azure Bay </option> <br>
      <option value="Amity Square"> Amity Square </option> <br>
      <option value="Bell Tower"> Bell Tower </option> <br>
      <option value="Eterna City"> Eterna City </option> <br>
      <option value="Small Court"> Small Court </option> <br>
      <option value="Dreamyard"> Dreamyard </option> <br>
      <option value="Roshan City"> Roshan City </option> <br>
      <option value="Lagoon Town"> Lagoon Town </option> <br>
    </select> <br><br>
  Gender: <br>
    <input type="radio" name="gender" value="Male" checked> Male<br>
    <input type="radio" name="gender" value="Female"> Female<br>
    <input type="radio" name="gender" value="Other"> Other<br>
  <br>
<input class="button" type="submit" value="Add" name="addtrainer"> <br>
</form>

<form id="search-container" action="admin.php" method="post">
  <h3> Delete Trainer </h3>
  Trainer Id: <br> <input type="number" name="trainerid" required min="0"> <br>
  <p> Note: Once you delete a trainer, all their pokemon become wild pokemon </p><br>
  <input class="button" type="submit" value="Delete" name="deletetrainer">
</form>

<form id="search-container" action="admin.php" method="post">
    <h3> Add Species </h3>
    Species Name: <br>   <input type="text" name="sname" required maxlength="20"> <br>
    Evolves Into: <br>   <input type="text" name="postEvo" maxlength="20"> <br>
    Evolved From: <br>   <input type="text" name="preEvo" maxlength="20"><br>
    Type: <br>
      <select name="type">
        <option value="Fire"> Fire </option> <br>
        <option value="Water"> Water </option> <br>
        <option value="Grass"> Grass </option> <br>
        <option value="Psychic"> Psychic </option> <br>
        <option value="Rock"> Rock </option> <br>
        <option value="Electric"> Electric </option> <br>
      </select> <br> <br>
  <input class="button" type="submit" value="Add" name="addspecies"> <br>
  </form>

  <form id="search-container" action="admin.php" method="post">
    <h3> Delete Species</h3>
    Species Name: <br> <input type="text" name="sname_r" required maxlength="20"> <br><br>
    <input class="button" type="submit" value="Delete" name="deletespecies">
  </form>

  <form id="search-container" action="admin.php" method="post">
      <h3> Add Pokemon </h3>
      Pokemon Id:   <br> <input type="number" name="pid_add" required min="0"> <br>
      Species:      <br> <input type="text" name="pspecies_add" maxlength="20"> <br>
      Pokemon Name: <br> <input type="text" name="pname_add" maxlength="20"> <br>
      Birthplace:   <br>
        <select name="plocation_add" required>
          <option value="Azure Bay"> Azure Bay </option> <br>
          <option value="Amity Square"> Amity Square </option> <br>
          <option value="Bell Tower"> Bell Tower </option> <br>
          <option value="Eterna City"> Eterna City </option> <br>
          <option value="Small Court"> Small Court </option> <br>
          <option value="Dreamyard"> Dreamyard </option> <br>
          <option value="Roshan City"> Roshan City </option> <br>
          <option value="Lagoon Town"> Lagoon Town </option> <br>
        </select> <br><br>
      Trainer Id: <br>     <input type="number" name="ptrainer_add" min="0"><br>
      Gender: <br>
        <input type="radio" name="pgender_add" value="Male" checked> Male<br>
        <input type="radio" name="pgender_add" value="Female"> Female<br>
        <input type="radio" name="pgender_add" value="Other"> Other<br> <br>
    <input class="button" type="submit" value="Add" name="addpokemon"> <br>
    </form>

  <form id="search-container" action="admin.php" method="post">
    <h3> Update Pokemon </h3>
    Pokemon Id: <br> <input type="number" name="pid" required min="0"> <br>
    Field to Update:<br>
      <select name="updatefield">
        <option value="sname"> Species </option>
        <option value="pokemonName"> Name </option>
      </select> <br>
    Update to: <br> <input type="text" name="newvalue" required maxlength="20"> <br><br>
    <input class="button" type="submit" value="Update" name="updatepokemon">
  </form>

  <form id="search-container" action="admin.php" method="post">
    <h3> Delete Pokemon</h3>
    Pokemon Id: <br> <input type="number" name="pid_r" required min="0"> <br><br>
    <input class="button" type="submit" value="Delete" name="deletepokemon">
  </form>

</body>
</html>