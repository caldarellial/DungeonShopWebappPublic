<?php
// Create connection
$conn = new mysqli(SK_SERVERNAME, SK_USERNAME, SK_PASSWORD, SK_DBNAME);
// Check connection
if ($conn->connect_error) {
    Errored("0002",("Connection failed: " . $conn->connect_error));
}

$stmt = $conn->prepare("INSERT IGNORE INTO ingredients(recipe,item,item_num) VALUES(?,?,?)");
$stmt->bind_param("iii",$recipe,$item,$item_num);
$item = $_POST["item"];
$item_num = $_POST["item_num"];
$recipe = $_POST["recipe"];
if ($stmt->execute()){
	$stmt->store_result();
	$added_id = $stmt->insert_id;
	
	Success("Added ingredient",["ingredient"=>["id"=>$added_id]]);
}
Errored("0001",$stmt->error);
?>