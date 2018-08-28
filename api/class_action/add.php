<?php
// Create connection
$conn = new mysqli(SK_SERVERNAME, SK_USERNAME, SK_PASSWORD, SK_DBNAME);
// Check connection
if ($conn->connect_error) {
    Errored("0002",("Connection failed: " . $conn->connect_error));
}

$stmt = $conn->prepare("INSERT IGNORE INTO class_actions(class,action,value,spread,chance) VALUES(?,?,?,?,?)");
$stmt->bind_param("iisss",$class,$action,$value,$spread,$chance);
$class = $_POST["class"];
$action = $_POST["action"];
$value = $_POST["value"];
$spread = $_POST["spread"];
$chance = $_POST["chance"];
if ($stmt->execute()){
	$stmt->store_result();
	$added_id = $stmt->insert_id;
	
	Success("Added class action",["class_action"=>["id"=>$added_id]]);
}
Errored("0001",$stmt->error);
?>