<?php
// Create connection
$conn = new mysqli(SK_SERVERNAME, SK_USERNAME, SK_PASSWORD, SK_DBNAME);
// Check connection
if ($conn->connect_error) {
    Errored("0002",("Connection failed: " . $conn->connect_error));
}

$stmt = $conn->prepare("INSERT IGNORE INTO enemy_actions(enemy,action,value) VALUES(?,?,?)");
$stmt->bind_param("iis",$enemy,$action,$value);
$enemy = $_POST["enemy"];
$action = $_POST["action"];
$value = $_POST["value"];
if ($stmt->execute()){
	$stmt->store_result();
	$added_id = $stmt->insert_id;
	
	Success("Added enemy action",["enemy_action"=>["id"=>$added_id]]);
}
Errored("0001",$stmt->error);
?>