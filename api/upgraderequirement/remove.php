<?php
// Create connection
$conn = new mysqli(SK_SERVERNAME, SK_USERNAME, SK_PASSWORD, SK_DBNAME);
// Check connection
if ($conn->connect_error) {
    Errored("0002",("Connection failed: " . $conn->connect_error));
}

$stmt = $conn->prepare("DELETE FROM upgrade_requirements WHERE id = ? OR (upgrade = ? AND required = ?)");
$stmt->bind_param("iii",$id,$upgrade,$required);
$id = isset($_POST["id"])?$_POST["id"]:false;
$upgrade = isset($_POST["upgrade"])?$_POST["upgrade"]:false;
$required = isset($_POST["required"])?$_POST["required"]:false;
$stmt->execute();
$stmt->close();

Success("Removed upgrade requirement");
?>