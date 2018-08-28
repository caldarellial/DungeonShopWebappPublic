<?php
// Create connection
$conn = new mysqli(SK_SERVERNAME, SK_USERNAME, SK_PASSWORD, SK_DBNAME);
// Check connection
if ($conn->connect_error) {
    Errored("0002",("Connection failed: " . $conn->connect_error));
}

$stmt = $conn->prepare("INSERT IGNORE INTO classes(class,description,tier,occurrence) VALUES(?,?,?,?)");
$stmt->bind_param("ssis",$name,$desc,$tier,$occur);
$name = $_POST["class"];
$desc = $_POST["description"];
$tier = $_POST["tier"];
$occur = $_POST["occurrence"];
if ($stmt->execute()){
	$stmt->store_result();
	$added_id = $stmt->insert_id;
	
	Success("Added class",["added_class"=>["id"=>$added_id]]);
}
Errored("0001",$stmt->error);
?>