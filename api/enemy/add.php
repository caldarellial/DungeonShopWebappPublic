<?php
// Create connection
$conn = new mysqli(SK_SERVERNAME, SK_USERNAME, SK_PASSWORD, SK_DBNAME);
// Check connection
if ($conn->connect_error) {
    Errored("0002",("Connection failed: " . $conn->connect_error));
}

$stmt = $conn->prepare("INSERT IGNORE INTO enemies(enemy_name,description,tier,type) VALUES(?,?,?,?)");
$stmt->bind_param("ssis",$name,$desc,$tier,$type);
$name = $_POST["enemy_name"];
$desc = $_POST["description"];
$tier = $_POST["tier"];
$type = $_POST["type"];
if ($stmt->execute()){
	$stmt->store_result();
	$added_id = $stmt->insert_id;
	
	Success("Added enemy",["enemy"=>["id"=>$added_id]]);
}
Errored("0001",$stmt->error);
?>