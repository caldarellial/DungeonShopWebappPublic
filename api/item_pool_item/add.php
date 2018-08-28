<?php
// Create connection
$conn = new mysqli(SK_SERVERNAME, SK_USERNAME, SK_PASSWORD, SK_DBNAME);
// Check connection
if ($conn->connect_error) {
    Errored("0002",("Connection failed: " . $conn->connect_error));
}

$stmt = $conn->prepare("INSERT IGNORE INTO item_pool_items(pool,item,tier,occurrence) VALUES(?,?,?,?)");
$stmt->bind_param("iiis",$pool,$item,$tier,$occurrence);
$item = $_POST["item"];
$pool = $_POST["pool"];
$tier = $_POST["tier"];
$occurrence = $_POST["occurrence"];
if ($stmt->execute()){
	$stmt->store_result();
	$added_id = $stmt->insert_id;
	
	Success("Added item pool item",["item_pool_item"=>["id"=>$added_id]]);
}
Errored("0001",$stmt->error);
?>