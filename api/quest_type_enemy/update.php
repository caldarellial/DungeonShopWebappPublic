<?php
// Create connection
$conn = new mysqli(SK_SERVERNAME, SK_USERNAME, SK_PASSWORD, SK_DBNAME);
// Check connection
if ($conn->connect_error) {
    Errored("0002",("Connection failed: " . $conn->connect_error));
}

$results = $conn->query("DESCRIBE quest_type_enemies");
$cols = [];
while($row = $results->fetch_assoc()){
	$cols[] = $row["Field"];
}

$ind = $_POST["index"];
if (array_search($ind,$cols) === false){
	Errored("4001","Index not found");
}

$stmt = $conn->prepare("UPDATE quest_type_enemies SET $ind = ? WHERE id = ?");
echo $conn->error;
$stmt->bind_param("si",$val,$id);
$val = $_POST["value"];
$id = $_POST["id"];
if ($stmt->execute()){
	Success("Updated quest_type_enemies");
}
Errored("0001",$stmt->error);
?>