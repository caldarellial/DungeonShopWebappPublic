<?php
// Create connection
$conn = new mysqli(SK_SERVERNAME, SK_USERNAME, SK_PASSWORD, SK_DBNAME);
// Check connection
if ($conn->connect_error) {
    Errored("0002",("Connection failed: " . $conn->connect_error));
}

$stmt = $conn->prepare("INSERT IGNORE INTO class_quest_types(class,quest_type,mod_occurrence) VALUES(?,?,?)");
$stmt->bind_param("iis",$class,$questtype,$mod_occurrence);
$class = $_POST["class"];
$questtype = $_POST["quest_type"];
$mod_occurrence = $_POST["mod_occurrence"];
if ($stmt->execute()){
	$stmt->store_result();
	$added_id = $stmt->insert_id;
	
	Success("Added class_quest_type",["class_quest_type"=>["id"=>$added_id]]);
}
Errored("0001",$stmt->error);
?>