<?php
// Create connection
$conn = new mysqli(SK_SERVERNAME, SK_USERNAME, SK_PASSWORD, SK_DBNAME);
// Check connection
if ($conn->connect_error) {
    Errored("0002",("Connection failed: " . $conn->connect_error));
}

$stmt = $conn->prepare("INSERT IGNORE INTO item_combat_actions(item,action,value,frequency,target,length,crit_chance,chance,stamina_cost,mana_cost) VALUES(?,?,?,?,?,?,?,?,?,?)");
$stmt->bind_param("iisssissii",$item,$action,$value,$frequency,$target,$length,$crit_chance,$chance,$stamina,$mana);
$item = $_POST["item"];
$action = $_POST["action"];
$value = $_POST["value"];
$frequency = $_POST["frequency"];
$target = $_POST["target"];
$length = $_POST["length"];
$crit_chance = $_POST["crit_chance"];
$chance = $_POST["chance"];
$stamina = $_POST["stamina_cost"];
$mana = $_POST["mana_cost"];
if ($stmt->execute()){
	$stmt->store_result();
	$added_id = $stmt->insert_id;
	
	Success("Added item_combat_action",["item_combat_action"=>["id"=>$added_id]]);
}
Errored("0001",$stmt->error);
?>