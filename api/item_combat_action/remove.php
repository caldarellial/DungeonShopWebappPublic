<?php
// Create connection
$conn = new mysqli(SK_SERVERNAME, SK_USERNAME, SK_PASSWORD, SK_DBNAME);
// Check connection
if ($conn->connect_error) {
    Errored("0002",("Connection failed: " . $conn->connect_error));
}

$stmt = $conn->prepare("DELETE FROM item_combat_actions WHERE id = ?");
$stmt->bind_param("i",$id);
$id = $_POST["id"];
$stmt->execute();
$stmt->close();

Success("Removed item combat action");
?>