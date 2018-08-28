<?php
// Create connection
$conn = new mysqli(SK_SERVERNAME, SK_USERNAME, SK_PASSWORD, SK_DBNAME);
// Check connection
if ($conn->connect_error) {
    Errored("0002",("Connection failed: " . $conn->connect_error));
}

$stmt = $conn->prepare("INSERT IGNORE INTO quest_events(name,description,tier,occurence,danger) VALUES(?,?,?,?,?)");
$stmt->bind_param("ssiss",$name,$desc,$tier,$occur,$danger);
$name = $_POST["name"];
$desc = $_POST["description"];
$tier = $_POST["tier"];
$occur = $_POST["occurence"];
$danger = $_POST["danger"];
if ($stmt->execute()){
	$stmt->store_result();
	$added_id = $stmt->insert_id;
	
	Success("Added event",["event"=>["id"=>$added_id]]);
}
Errored("0001",$stmt->error);
?>