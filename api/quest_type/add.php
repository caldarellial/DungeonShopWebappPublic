<?php
// Create connection
$conn = new mysqli(SK_SERVERNAME, SK_USERNAME, SK_PASSWORD, SK_DBNAME);
// Check connection
if ($conn->connect_error) {
    Errored("0002",("Connection failed: " . $conn->connect_error));
}

$stmt = $conn->prepare("INSERT IGNORE INTO quest_types(quest_type,description,tier,occurrence,enemy_saturation_lower,enemy_saturation_upper) VALUES(?,?,?,?,?,?)");
$stmt->bind_param("ssis",$quest_type,$desc,$tier,$occur,$lower,$upper);
$quest_type = $_POST["quest_type"];
$desc = $_POST["description"];
$tier = $_POST["tier"];
$occur = $_POST["occurrence"];
$lower = $_POST["enemy_saturation_lower"];
$upper = $_POST["enemy_saturation_upper"];
if ($stmt->execute()){
	$stmt->store_result();
	$added_id = $stmt->insert_id;
	
	Success("Added quest type",["quest_type"=>["id"=>$added_id]]);
}
Errored("0001",$stmt->error);
?>