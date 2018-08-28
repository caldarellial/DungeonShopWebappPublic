<?php
// Create connection
$conn = new mysqli(SK_SERVERNAME, SK_USERNAME, SK_PASSWORD, SK_DBNAME);
// Check connection
if ($conn->connect_error) {
    Errored("0002",("Connection failed: " . $conn->connect_error));
}

$stmt = $conn->prepare("INSERT IGNORE INTO enemy_item_pools(enemy,item_pool,type,occurrence) VALUES(?,?,?,?)");
$stmt->bind_param("iiss",$enemy,$pool,$type,$occurrence);
$enemy = $_POST["enemy"];
$pool = $_POST["item_pool"];
$type = $_POST["type"];
$occurrence = $_POST["occurrence"];
if ($stmt->execute()){
	$stmt->store_result();
	$added_id = $stmt->insert_id;
	
	Success("Added enemy_item_pool",["enemy_item_pool"=>["id"=>$added_id]]);
}
Errored("0001",$stmt->error);
?>