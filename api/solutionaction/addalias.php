<?php
// Create connection
$conn = new mysqli(SK_SERVERNAME, SK_USERNAME, SK_PASSWORD, SK_DBNAME);
// Check connection
if ($conn->connect_error) {
    Errored("0002",("Connection failed: " . $conn->connect_error));
}

$stmt = $conn->prepare("INSERT IGNORE INTO solution_action_aliases(solution_action,action) VALUES(?,?)");
$stmt->bind_param("ii",$solution,$action);
$solution = $_POST["solution_action"];
$action = $_POST["action"];

if ($stmt->execute()){
	$stmt->store_result();
	$added_id = $stmt->insert_id;
	
	Success("Added solution action aliases",["solutionactionalias"=>["id"=>$added_id]]);
}
Errored("0001",$stmt->error);
?>