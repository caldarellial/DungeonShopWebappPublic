<?php
// Create connection
$conn = new mysqli(SK_SERVERNAME, SK_USERNAME, SK_PASSWORD, SK_DBNAME);
// Check connection
if ($conn->connect_error) {
    Errored("0002",("Connection failed: " . $conn->connect_error));
}

$stmt = $conn->prepare("INSERT IGNORE INTO recipes(item,requirement,requirementid,visible_before) VALUES(?,?,?,?)");
$stmt->bind_param("isii",$item,$requirement,$requirementid,$visible_before);
$item = $_POST["item"];
$requirement = $_POST["requirement"];
$requirementid = $_POST["requirementid"];
$visible_before = $_POST["visible_before"];
if ($stmt->execute()){
	$stmt->store_result();
	$added_id = $stmt->insert_id;
	
	Success("Added recipe",["recipe"=>["id"=>$added_id]]);
}
Errored("0001",$stmt->error);
?>