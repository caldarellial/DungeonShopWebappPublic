<?php
// Create connection
$conn = new mysqli(SK_SERVERNAME, SK_USERNAME, SK_PASSWORD, SK_DBNAME);
// Check connection
if ($conn->connect_error) {
    Errored("0002",("Connection failed: " . $conn->connect_error));
}

$stmt = $conn->prepare("INSERT IGNORE INTO item_pools(pool) VALUES(?)");
$stmt->bind_param("s",$name);
$name = $_POST["pool"];
if ($stmt->execute()){
	$stmt->store_result();
	$added_id = $stmt->insert_id;
	
	Success("Added item pool",["item_pool"=>["id"=>$added_id]]);
}
Errored("0001",$stmt->error);
?>