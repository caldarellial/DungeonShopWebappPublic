<?php
// Create connection
$conn = new mysqli(SK_SERVERNAME, SK_USERNAME, SK_PASSWORD, SK_DBNAME);
// Check connection
if ($conn->connect_error) {
    Errored("0002",("Connection failed: " . $conn->connect_error));
}

$stmt = $conn->prepare("DELETE FROM enemies WHERE id = ?");
$stmt->bind_param("i",$id);
$id = $_POST["id"];
$stmt->execute();
$stmt->close();

$stmt = $conn->prepare("DELETE FROM enemy_actions WHERE enemy = ?");
$stmt->bind_param("i",$id);
$id = $_POST["id"];
$stmt->execute();
$stmt->close();

$stmt = $conn->prepare("DELETE FROM enemy_item_pools WHERE enemy = ?");
$stmt->bind_param("i",$id);
$id = $_POST["id"];
$stmt->execute();
$stmt->close();

$stmt = $conn->prepare("DELETE FROM quest_type_enemies WHERE enemy = ?");
$stmt->bind_param("i",$id);
$id = $_POST["id"];
$stmt->execute();
$stmt->close();


Success("Removed enemy");
?>