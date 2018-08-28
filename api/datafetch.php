<?php
	function getObjectInfo($type,$typeid,$preventloop=false){
		$conn = new mysqli(SK_SERVERNAME, SK_USERNAME, SK_PASSWORD, SK_DBNAME);
		
        switch ($type){
            case "event":
				$stmt = $conn->prepare("SELECT id, name, description, tier, occurence, danger FROM quest_events WHERE id = ? || ? = 0 ORDER BY id");
				$stmt->bind_param("ii",$typeid,$typeid);
				$stmt->bind_result($rId,$rName,$rDescription,$rTier,$rOccurence,$rDanger);
				$stmt->execute();
				$stmt->store_result();
				
				$retobj = [];
				while($stmt->fetch()){
					$event = [
						"id"=>$rId,
						"name"=>$rName,
						"description"=>$rDescription,
						"tier"=>$rTier,
						"occurence"=>$rOccurence,
						"danger"=>$rDanger,
						"solutions"=>[]
					];
					
					$result = $conn->query("
						SELECT
						*
						FROM solutions
						WHERE event = $rId
						ORDER BY id
					");
					
					while($solution = $result->fetch_assoc()){
						$solution["actions"] = [];
						$solId = $solution["id"];
						$result2 = $conn->query("
							SELECT
							solution_actions.*,
							actions.action AS action_name
							FROM solution_actions
							INNER JOIN actions ON actions.id = solution_actions.action
							WHERE solution_actions.solution = $solId
							ORDER BY solution_actions.id
						");
						
						while($solution_action = $result2->fetch_assoc()){
							$solActId = $solution_action["id"];
							
							$result3 = $conn->query("
								SELECT
								solution_action_aliases.*,
								actions.action AS action_name
								FROM solution_action_aliases
								INNER JOIN actions ON actions.id = solution_action_aliases.action
								WHERE solution_action_aliases.solution_action = $solActId
								ORDER BY solution_action_aliases.id
							");
							
							$solution_action["aliases"] = [];
							while($solution_alias = $result3->fetch_assoc()){
								$solution_action["aliases"][] = $solution_alias;
							}
							
							$solution["actions"][] = $solution_action;
						}
						
						$event["solutions"][] = $solution;
					}
					
					$retobj[] = $event;
				}
				break;
			case "action":
				$stmt = $conn->prepare("SELECT id, action FROM actions WHERE id = ? || ? = 0 ORDER BY id");
				$stmt->bind_param("ii",$typeid,$typeid);
				$stmt->bind_result($rId,$rAction);
				$stmt->execute();
				
				$retobj = [];
				while($stmt->fetch()){
					$action = [
						"id"=>$rId,
						"action"=>$rAction
					];
					$retobj[] = $action;
				}
				break;
			case "item":
				$stmt = $conn->prepare("SELECT id, name, description, value, type, uses, cooldown FROM items WHERE id = ? || ? = 0 ORDER BY id");
				$stmt->bind_param("ii",$typeid,$typeid);
				$stmt->bind_result($rId,$rName,$rDescription,$rValue,$rType,$rUses,$rCooldown);
				$stmt->execute();
				$stmt->store_result();
				
				$retobj = [];
				while($stmt->fetch()){
					$item = [
						"id"=>$rId,
						"name"=>$rName,
						"description"=>$rDescription,
						"value"=>$rValue,
						"type"=>$rType,
						"uses"=>$rUses,
						"cooldown"=>$rCooldown,
						"actions"=>[],
						"recipes"=>[]
					];
					
					$result = $conn->query("
						SELECT
						item_actions.id,
						actions.id AS action,
						actions.action AS action_name,
						item_actions.item,
						item_actions.value,
						item_actions.frequency,
						item_actions.target,
						item_actions.length,
						item_actions.crit_chance,
						item_actions.chance,
						item_actions.stamina_cost,
						item_actions.mana_cost
						FROM item_actions
						INNER JOIN actions ON actions.id = item_actions.action
						WHERE item_actions.item = $rId
						ORDER BY item_actions.id
					");
					
					while($row = $result->fetch_assoc()){
						$item["actions"][] = $row;
					}
					
					$result = $conn->query("
						SELECT
						id,
						item,
						yield,
						requirement,
						requirementid,
						visible_before
						FROM recipes
						WHERE item = $rId
					");
					
					while($row = $result->fetch_assoc()){
						$row["yield"] = (int)$row["yield"];
						$row["ingredients"] = [];
						$recipeid = $row["id"];
						$ingredientResult = $conn->query("
							SELECT
							id,
							item,
							item_num,
							recipe
							FROM ingredients
							WHERE recipe = $recipeid
						");
						while($ingredientRow = $ingredientResult->fetch_assoc()){
							$row["ingredients"][] = $ingredientRow;
						}
						$item["recipes"][] = $row;
					}
					
					$retobj[] = $item;
				}
				break;
			case "upgrade":
				$stmt = $conn->prepare("SELECT id,name,description,cost,type FROM upgrades WHERE id = ? || ? = 0");
				$stmt->bind_param("ii",$typeid,$typeid);
				$stmt->bind_result($rId,$rName,$rDescription,$rCost,$rType);
				$stmt->execute();
				$stmt->store_result();
				
				$retobj = [];
				while($stmt->fetch()){
					$upgrade = [
						"id"=>$rId,
						"name"=>$rName,
						"description"=>$rDescription,
						"cost"=>$rCost,
						"type"=>$rType,
						"requirements"=>[]
					];
					
					$result = $conn->query("
						SELECT
						upgrade_requirements.id,
						upgrade_requirements.upgrade,
						upgrades.id AS required,
						upgrades.name AS upgrade_name
						FROM upgrade_requirements
						INNER JOIN upgrades ON upgrades.id = upgrade_requirements.required
						WHERE upgrade_requirements.upgrade = $rId
						ORDER BY upgrade_requirements.id
					");

					while($row = $result->fetch_assoc()){
						$upgrade["requirements"][] = $row;
					}
					
					$retobj[] = $upgrade;
				}
				break;
			case "item_pool":
				$stmt = $conn->prepare("SELECT id, pool FROM item_pools WHERE id = ? || ? = 0 ORDER BY id");
				$stmt->bind_param("ii",$typeid,$typeid);
				$stmt->bind_result($rId,$rName);
				$stmt->execute();
				$stmt->store_result();
				
				$retobj = [];
				while($stmt->fetch()){
					$pool = [
						"id"=>$rId,
						"pool"=>$rName,
						"items"=>[]
					];
					
					$result = $conn->query("
						SELECT
						item_pool_items.id,
						item_pool_items.pool,
						items.id AS item,
						items.name AS item_name,
						item_pool_items.tier,
						item_pool_items.occurrence
						FROM item_pool_items
						INNER JOIN items ON items.id = item_pool_items.item
						WHERE item_pool_items.pool = $rId
						ORDER BY item_pool_items.id
					");
					echo $conn->error;
					while($row = $result->fetch_assoc()){
						$row["tier"] = (int)$row["tier"];
						$pool["items"][] = $row;
					}
					
					$retobj[] = $pool;
				}
				break;
			case "class":
				$stmt = $conn->prepare("SELECT id, class, description, tier, occurrence FROM classes WHERE id = ? || ? = 0 ORDER BY id");
				$stmt->bind_param("ii",$typeid,$typeid);
				$stmt->bind_result($rId,$rName,$rDescription,$rTier,$rOccurrence);
				$stmt->execute();
				$stmt->store_result();
				
				$retobj = [];
				while($stmt->fetch()){
					$class = [
						"id"=>$rId,
						"class"=>$rName,
						"description"=>$rDescription,
						"tier"=>$rTier,
						"occurrence"=>$rOccurrence,
						"actions"=>[],
						"item_pools"=>[],
						"quest_types"=>[]
					];
					
					$result = $conn->query("
						SELECT
						class_actions.id,
						actions.id AS action,
						actions.action AS action_name,
						class_actions.class,
						class_actions.value,
						class_actions.spread,
						class_actions.chance
						FROM class_actions
						INNER JOIN actions ON actions.id = class_actions.action
						WHERE class_actions.class = $rId
						ORDER BY class_actions.id
					");

					while($row = $result->fetch_assoc()){
						$class["actions"][] = $row;
					}
					
					$result = $conn->query("
						SELECT
						class_item_pools.id,
						item_pools.id AS pool,
						item_pools.pool AS pool_name,
						class_item_pools.mod_occurrence,
						class_item_pools.chance,
						class_item_pools.class
						FROM class_item_pools
						INNER JOIN item_pools ON item_pools.id = class_item_pools.pool
						WHERE class_item_pools.class = $rId
						ORDER BY class_item_pools.id
					");
					
					while($row = $result->fetch_assoc()){
						$class["item_pools"][] = $row;
					}
					
					$result = $conn->query("
						SELECT
						class_quest_types.id,
						class_quest_types.quest_type,
						class_quest_types.mod_occurrence,
						class_quest_types.class
						FROM class_quest_types
						INNER JOIN quest_types ON quest_types.id = class_quest_types.quest_type
						WHERE class_quest_types.class = $rId
						ORDER BY class_quest_types.id
					");
					echo $conn->error;
					while($row = $result->fetch_assoc()){
						$class["quest_types"][] = $row;
					}
					
					$retobj[] = $class;
				}
				break;
			case "quest_type":
				$stmt = $conn->prepare("SELECT id, quest_type, description, tier, occurrence,enemy_saturation_lower,enemy_saturation_upper FROM quest_types WHERE id = ? || ? = 0 ORDER BY id");
				$stmt->bind_param("ii",$typeid,$typeid);
				$stmt->bind_result($rId,$rQuestType,$rDescription,$rTier,$rOccurrence,$rLower,$rUpper);
				$stmt->execute();
				$stmt->store_result();
				echo $conn->error;
				$retobj = [];
				while($stmt->fetch()){
					$quest_type = [
						"id"=>$rId,
						"quest_type"=>$rQuestType,
						"description"=>$rDescription,
						"tier"=>$rTier,
						"occurrence"=>$rOccurrence,
						"enemy_saturation_lower"=>$rLower,
						"enemy_saturation_upper"=>$rUpper,
						"quest_type_pools"=>[],
						"quest_type_enemies"=>[]
					];
					
					$result = $conn->query("
						SELECT
							id,
							quest_type,
							quest_pool,
							occurrence
							FROM quest_type_pools
							WHERE quest_type = $rId
					");
					echo $conn->error;
					while($row = $result->fetch_assoc()){
						$row["quest_pool_info"] = getObjectInfo("quest_pool",$row["quest_pool"]);
						$quest_type["quest_type_pools"][] = $row;
					}
					
					$result = $conn->query("
						SELECT
							id,
							quest_type,
							enemy,
							occurrence
							FROM quest_type_enemies
							WHERE quest_type = $rId
					");
					echo $conn->error;
					while($row = $result->fetch_assoc()){
						$row["quest_enemy_info"] = getObjectInfo("enemy",$row["enemy"]);
						$quest_type["quest_type_enemies"][] = $row;
					}
					
					$retobj[] = $quest_type;
				}
				break;
			case "quest_pool":
				$stmt = $conn->prepare("SELECT id, quest_pool FROM quest_pools WHERE id = ? || ? = 0 ORDER BY id");
				$stmt->bind_param("ii",$typeid,$typeid);
				$stmt->bind_result($rId,$rPool);
				$stmt->execute();
				$stmt->store_result();
				
				$retobj = [];
				while($stmt->fetch()){
					$quest_pool = [
						"id"=>$rId,
						"quest_pool"=>$rPool,
						"quest_events"=>[]
					];
					
					$result = $conn->query("
						SELECT
							id,
							quest_pool,
							quest_event,
							tier,
							occurrence
							FROM quest_pool_events
							WHERE quest_pool = $rId
					");
					
					while($row = $result->fetch_assoc()){
						$row["quest_event_info"] = getObjectInfo("event",$row["quest_event"]);
						$quest_pool["quest_events"][] = $row;
					}
					
					$retobj[] = $quest_pool;
				}
				break;
			case "enemy":
				$stmt = $conn->prepare("SELECT id, enemy_name, description, tier, type FROM enemies WHERE id = ? || ? = 0 ORDER BY id");
				$stmt->bind_param("ii",$typeid,$typeid);
				$stmt->bind_result($rId,$rName,$rDescription,$rTier,$rType);
				$stmt->execute();
				$stmt->store_result();
				
				$retobj = [];
				while($stmt->fetch()){
					$enemy = [
						"id"=>$rId,
						"enemy_name"=>$rName,
						"description"=>$rDescription,
						"tier"=>$rTier,
						"type"=>$rType,
						"actions"=>[],
						"item_pools"=>[]
					];
					
					$result = $conn->query("
						SELECT
							id,
							enemy,
							item_pool,
							type,
							occurrence
							FROM enemy_item_pools
							WHERE enemy = $rId
					");
					
					while($row = $result->fetch_assoc()){
						$enemy["item_pools"][] = $row;
					}
					
					$result = $conn->query("
						SELECT
							id,
							enemy,
							action,
							value
							FROM enemy_actions
							WHERE enemy = $rId
					");
					
					while($row = $result->fetch_assoc()){
						$enemy["actions"][] = $row;
					}
					
					$retobj[] = $enemy;
				}
				break;
            default:
                $retobj=false;
                break;
        }
        
        return isset($retobj)?$retobj:false;
    }
?>