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

$defaults = [
	["def"=>0,"types"=>["int","decimal","float"]],
];

$schemas = [];
while($row = $result->fetch_assoc()){
	if ($row["table_schema"] !== SK_DBNAME){continue;}
	$schemas[$row["table_name"]] = [];
	$result_cols = $conn->query("DESCRIBE ".$row["table_name"]);
	while($col = $result_cols->fetch_assoc()){
		$def = "";
		foreach($defaults as $check){
			$inCheck = array_search(explode('(',$col["Type"])[0],$check["types"]);
			if ($inCheck!==false){
				$def = $check["def"];
				break;
			}
		}
		$selCol = (($col["Field"] == "id")?"db_id":$col["Field"]);
		$schemas[$row["table_name"]][] = [$selCol=>$def];
	}
}

Success($schemas);
?>