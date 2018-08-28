<?php
// Create connection
$conn = new mysqli(SK_SERVERNAME, SK_USERNAME, SK_PASSWORD, SK_DBNAME);
// Check connection
if ($conn->connect_error) {
    Errored("0002",("Connection failed: " . $conn->connect_error));
}

$stmt = $conn->prepare("INSERT IGNORE INTO upgrade_requirements(upgrade,required) VALUES(?,?)");
$stmt->bind_param("ii",$upgrade,$required);
$upgrade = $_POST["upgrade"];
$required = $_POST["required"];

if ($stmt->execute()){
	$stmt->store_result();
	$added_id = $stmt->insert_id;
	
	Success("Added upgrade requirement",["upgraderequirement"=>["id"=>$added_id]]);
}
Errored("0001",$stmt->error);
?>