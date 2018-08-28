<?php
// Create connection
$conn = new mysqli(SK_SERVERNAME, SK_USERNAME, SK_PASSWORD, SK_DBNAME);
// Check connection
if ($conn->connect_error) {
    Errored("0002",("Connection failed: " . $conn->connect_error));
}

$stmt = $conn->prepare("INSERT IGNORE INTO items(name,description,value,type,uses,cooldown) VALUES(?,?,?,?,?,?)");
$stmt->bind_param("ssisii",$name,$desc,$value,$type,$uses,$cooldown);
$name = $_POST["name"];
$desc = $_POST["description"];
$value = $_POST["value"];
$type = $_POST["type"];
$uses = $_POST["uses"];
$cooldown = $_POST["cooldown"];
if ($stmt->execute()){
	$stmt->store_result();
	$added_id = $stmt->insert_id;
	
	Success("Added item",["item"=>["id"=>$added_id]]);
}
Errored("0001",$stmt->error);
?>