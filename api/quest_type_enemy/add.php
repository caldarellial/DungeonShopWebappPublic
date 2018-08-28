<?php
// Create connection
$conn = new mysqli(SK_SERVERNAME, SK_USERNAME, SK_PASSWORD, SK_DBNAME);
// Check connection
if ($conn->connect_error) {
    Errored("0002",("Connection failed: " . $conn->connect_error));
}

$stmt = $conn->prepare("INSERT IGNORE INTO quest_type_enemies(quest_type,enemy,occurrence) VALUES(?,?,?)");
$stmt->bind_param("iis",$quest_type,$enemy,$occurrence);
$quest_type = $_POST["quest_type"];
$enemy = $_POST["enemy"];
$occurrence = $_POST["occurrence"];
if ($stmt->execute()){
	$stmt->store_result();
	$added_id = $stmt->insert_id;
	
	Success("Added quest_type_enemies",["quest_type_enemy"=>["id"=>$added_id]]);
}
Errored("0001",$stmt->error);
?>