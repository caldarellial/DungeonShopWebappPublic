<?php
// Create connection
$conn = new mysqli(SK_SERVERNAME, SK_USERNAME, SK_PASSWORD, SK_DBNAME);
// Check connection
if ($conn->connect_error) {
    Errored("0002",("Connection failed: " . $conn->connect_error));
}

$stmt = $conn->prepare("INSERT IGNORE INTO quest_pool_events(quest_pool,quest_event,tier,occurrence) VALUES(?,?,?,?)");
$stmt->bind_param("iiis",$pool,$item,$tier,$occurrence);
$item = $_POST["quest_event"];
$pool = $_POST["quest_pool"];
$tier = $_POST["tier"];
$occurrence = $_POST["occurrence"];
if ($stmt->execute()){
	$stmt->store_result();
	$added_id = $stmt->insert_id;
	
	Success("Added quest_pool_event",["quest_pool_event"=>["id"=>$added_id]]);
}
Errored("0001",$stmt->error);
?>