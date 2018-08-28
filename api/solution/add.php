<?php
// Create connection
$conn = new mysqli(SK_SERVERNAME, SK_USERNAME, SK_PASSWORD, SK_DBNAME);
// Check connection
if ($conn->connect_error) {
    Errored("0002",("Connection failed: " . $conn->connect_error));
}

$stmt = $conn->prepare("INSERT IGNORE INTO solutions(event,probability,success,failure) VALUES(?,?,?,?)");
$stmt->bind_param("isss",$event,$probability,$success,$failure);
$event = $_POST["event"];
$probability = $_POST["probability"];
$success = $_POST["success"];
$failure = $_POST["failure"];

if ($stmt->execute()){
	$stmt->store_result();
	$added_id = $stmt->insert_id;
	
	Success("Added solution",["solution"=>["id"=>$added_id]]);
}
Errored("0001",$stmt->error);
?>