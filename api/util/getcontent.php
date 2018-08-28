<?php
// Create connection
$conn = new mysqli(SK_SERVERNAME, SK_USERNAME, SK_PASSWORD, SK_DBNAME);
// Check connection
if ($conn->connect_error) {
    Errored("0002",("Connection failed: " . $conn->connect_error));
}

$result = $conn->query("SELECT table_catalog, table_schema, table_name, column_name
FROM INFORMATION_SCHEMA.columns
WHERE 1");

$totalinfo = [];
while($row = $result->fetch_assoc()){
	if ($row["table_schema"] !== SK_DBNAME){continue;}
	$totalinfo[$row["table_name"]] = ["rows"=>[],"cols"=>[]];
	$result_cols = $conn->query("DESCRIBE ".$row["table_name"]);
	while($cols = $result_cols->fetch_assoc()){
		$totalinfo[$row["table_name"]]["cols"][] = ($cols["Field"]=="id"?"db_id":$cols["Field"]);
	}
	$query = "SELECT * FROM ".$row["table_name"]." WHERE 1 ORDER BY id";
	$result_rows = $conn->query($query);
    while($roww = $result_rows->fetch_assoc()){
        $roww["db_id"] = $roww["id"];
        unset($roww['id']);
		$totalinfo[$row["table_name"]]["rows"][] = $roww;
	}
}

Success($totalinfo);
?>