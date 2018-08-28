<?php
// Create connection
$conn = new mysqli(SK_SERVERNAME, SK_USERNAME, SK_PASSWORD, SK_DBNAME);
// Check connection
if ($conn->connect_error) {
    Errored("0002",("Connection failed: " . $conn->connect_error));
}

$stmt = $conn->prepare("INSERT IGNORE INTO class_item_pools(class,pool,mod_occurrence,chance) VALUES(?,?,?,?)");
$stmt->bind_param("iiss",$class,$pool,$mod_occurrence,$chance);
$class = $_POST["class"];
$pool = $_POST["pool"];
$mod_occurrence = $_POST["mod_occurrence"];
$chance = $_POST["chance"];
if ($stmt->execute()){
	$stmt->store_result();
	$added_id = $stmt->insert_id;
	
	Success("Added class_item_pool",["class_item_pool"=>["id"=>$added_id]]);
}
Errored("0001",$stmt->error);
?>