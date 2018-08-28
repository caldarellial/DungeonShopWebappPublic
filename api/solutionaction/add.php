<?php
// Create connection
$conn = new mysqli(SK_SERVERNAME, SK_USERNAME, SK_PASSWORD, SK_DBNAME);
// Check connection
if ($conn->connect_error) {
    Errored("0002",("Connection failed: " . $conn->connect_error));
}

$stmt = $conn->prepare("INSERT IGNORE INTO solution_actions(solution,action) VALUES(?,?)");
$stmt->bind_param("ii",$solution,$action);
$solution = $_POST["solution"];
$action = $_POST["action"];

if ($stmt->execute()){
	$stmt->store_result();
	$added_id = $stmt->insert_id;
	
	Success("Added solution action",["solutionaction"=>["id"=>$added_id]]);
}
Errored("0001",$stmt->error);
?>