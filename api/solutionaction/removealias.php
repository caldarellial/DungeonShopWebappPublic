<?php
// Create connection
$conn = new mysqli(SK_SERVERNAME, SK_USERNAME, SK_PASSWORD, SK_DBNAME);
// Check connection
if ($conn->connect_error) {
    Errored("0002",("Connection failed: " . $conn->connect_error));
}

$stmt = $conn->prepare("DELETE FROM solution_action_aliases WHERE id = ? OR (solution_action = ? AND action = ?)");
$stmt->bind_param("iii",$id,$sol_ac,$action);
$id = isset($_POST["id"])?$_POST["id"]:false;
$sol_ac = isset($_POST["solution_action"])?$_POST["solution_action"]:false;
$action = isset($_POST["action"])?$_POST["action"]:false;
$stmt->execute();
$stmt->close();

Success("Removed solution action alias");
?>