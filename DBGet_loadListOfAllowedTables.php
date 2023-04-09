<?PHP
include ("settings.php"); //NOTE:is in same dir, reference the main settings.php file later

global $conn;


if ($result_allTables = $conn -> query("show tables")) {
	$tablesList = array();
	while ($tableName = $result_allTables ->  fetch_row()) {
		array_push($tablesList, $tableName[0]);
	}
	//echo $tablesList;
	//echo "['" . implode("','", $tablesList) . "']";
	echo implode(",", $tablesList);
	/*foreach ($tablesList as $thisTable) {
		echo $thisTable;
	}*/
}




?>