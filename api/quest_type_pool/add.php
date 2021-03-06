<?php
// Create connection
$conn = new mysqli(SK_SERVERNAME, SK_USERNAME, SK_PASSWORD, SK_DBNAME);
// Check connection
if ($conn->connect_error) {
    Errored("0002",("Connection failed: " . $conn->connect_error));
}

$stmt = $conn->prepare("INSERT IGNORE INTO quest_type_pools(quest_type,quest_pool,occurrence) VALUES(?,?,?)");
$stmt->bind_param("iis",$quest_type,$pool,$occurrence);
$quest_type = $_POST["quest_type"];
$pool = $_POST["quest_pool"];
$occurrence = $_POST["occurrence"];
if ($stmt->execute()){
	$stmt->store_result();
	$added_id = $stmt->insert_id;
	
	Success("Added quest_type_pool",["quest_type_pool"=>["id"=>$added_id]]);
}
Errored("0001",$stmt->error);
?>