var app = angular.module('SKClient',['ngSanitize','ngMaterial','ui.bootstrap.contextMenu'],function($httpProvider) {
	// Use x-www-form-urlencoded Content-Type
	$httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
  
	/**
	 * The workhorse; converts an object to x-www-form-urlencoded serialization.
	 * @param {Object} obj
	 * @return {String}
	 */ 
	var param = function(obj) {
	  var query = '', name, value, fullSubName, subName, subValue, innerObj, i;
  
	  for(name in obj) {
		value = obj[name];
  
		if(value instanceof Array) {
		  for(i=0; i<value.length; ++i) {
			subValue = value[i];
			fullSubName = name + '[' + i + ']';
			innerObj = {};
			innerObj[fullSubName] = subValue;
			query += param(innerObj) + '&';
		  }
		}
		else if(value instanceof Object) {
		  for(subName in value) {
			subValue = value[subName];
			fullSubName = name + '[' + subName + ']';
			innerObj = {};
			innerObj[fullSubName] = subValue;
			query += param(innerObj) + '&';
		  }
		}
		else if(value !== undefined && value !== null)
		  query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
	  }
  
	  return query.length ? query.substr(0, query.length - 1) : query;
	};
  
	// Override $http service's default transformRequest
	$httpProvider.defaults.transformRequest = [function(data) {
	  return angular.isObject(data) && String(data) !== '[object File]' ? param(data) : data;
	}];
})
.config( ['$provide', function ($provide){
	$provide.decorator('$browser', ['$delegate', function ($delegate) {
		$delegate.onUrlChange = function () {};
		$delegate.url = function () { return "";};
		return $delegate;
	}]);
}])
.controller('SKController', ['$scope','$http','$location',function ($scope,$http,$location) {
	$scope.version = currentVersion;
	
	//Util
	$scope.truncateString = function(str){
		
	};
	
	$scope.checkClickedForEditing = false;
	$scope.doubleClickForEditing = function(obj,ind,$event){
		$event.stopPropagation();
		if ($scope.checkClickedForEditing){
			if ($scope.checkClickedForEditing.obj == obj && $scope.checkClickedForEditing.ind == ind){
				$scope.setEditing(obj,ind);
				console.log($scope.clickedForEditing);
			}
			else{$scope.checkClickedForEditing = {obj:obj,ind:ind}; return;}
		}else{
			$scope.checkClickedForEditing = {obj:obj,ind:ind}; return;
		}
		setTimeout(function(){
			if ($scope.checkClickedForEditing.obj == obj && $scope.checkClickedForEditing.ind == ind){$scope.checkClickedForEditing = false;}
		},50);
	};
	
	$scope.setEditing = function(obj,ind,$event){
		$event = $event||false;
		if ($event){$event.stopPropagation();}
		$scope.clickedForEditing = {
			obj:obj,
			ind:ind,
			val:obj[ind]
		};
	};
	
	$scope.isEditing = function(obj,ind){
		if (!$scope.clickedForEditing){return false;}
		return $scope.clickedForEditing.obj == obj && $scope.clickedForEditing.ind == ind;
	};
	
	$scope.stopEditing = function($event){
		$event.stopPropagation();
		
		$scope.clickedForEditing = false;
	};
	
	$scope.sortBy = 'id';
	$scope.reverseSort = false;
	$scope.toggleSort = function(sort){
		if ($scope.sortBy == sort){$scope.reverseSort = !$scope.reverseSort;}
		$scope.sortBy = sort;
	};
	
	$scope.exportTable = function(table){
		let csvContent = "data:text/csv;charset=utf-8,";
		
		if (!$scope.totalContent){return false;}
		var rows = false;
		var cols = false;
		$.each($scope.totalContent,function(key,val){
			if (key == table){
				rows = val.rows;
				cols = val.cols;
				return false;
			}
		});
		if (!cols){return false;}
		
		csvContent += CSV.serialize({
			fields:cols.map(function(curVal){return {id:(curVal=="id"?"db_id":curVal)};}),
			records:rows
		});

		var encodedUri = encodeURI(csvContent);
		var link = document.createElement("a");
		link.setAttribute("href", encodedUri);
		link.setAttribute("download", table+".csv");
		document.body.appendChild(link);
		
		link.click();
	};
	
	$scope.exportSchemas = function(){
		$scope.fetchSchemas(function(){
			let fileContent = "data:text/json;charset=utf-8,";
		
			if (!$scope.tableSchemas){return false;}
			fileContent += JSON.stringify($scope.tableSchemas);
			var encodedUri = encodeURI(fileContent);
			var link = document.createElement("a");
			link.setAttribute("href", encodedUri);
			link.setAttribute("download","schemas.json");
			document.body.appendChild(link);
			
			link.click();
		});
	};
	//Util End
	
	$scope.activeTab = 'summary';
	$scope.activeObj = false;
	$scope.setActiveTab = function(tab){
		$scope.activeTab = tab;
		$scope.activeObj = false;
		$scope.addingObj = false;
		$scope.innerAdding = false;
		$scope.activeTree = [];
		$scope.sortBy = 'id';
		$scope.reverseSort = false;
	};
	
	//**Fetchers**//
	$scope.fetchEvents = function(){
		$.post(apiUrl+"event/fetch",{},function(data){
			console.log(data);
			$scope.questEvents = data.success;
			$scope.$apply();
		},"json");
	};
	$scope.fetchEvents();
	
	$scope.fetchItems = function(){
		$.post(apiUrl+"item/fetch",{},function(data){
			console.log(data);
			$scope.items = data.success;
			$scope.$apply();
		},"json");
	};
	$scope.fetchItems();
	
	$scope.fetchActions = function(){
		$.post(apiUrl+"action/fetch",{},function(data){
			console.log(data);
			$scope.actions = data.success;
			$scope.$apply();
		},"json");
	};
	$scope.fetchActions();
	
	$scope.fetchUpgrades = function(){
		$.post(apiUrl+"upgrade/fetch",{},function(data){
			console.log(data);
			$scope.upgrades = data.success;
			$scope.$apply();
		},"json");
	};
	$scope.fetchUpgrades();
	
	$scope.fetchItemPools = function(){
		$.post(apiUrl+"item_pool/fetch",{},function(data){
			console.log(data);
			$scope.item_pools = data.success;
			$scope.$apply();
		},"json");
	};
	$scope.fetchItemPools();
	
	$scope.fetchClasses = function(){
		$.post(apiUrl+"class/fetch",{},function(data){
			console.log(data);
			$scope.classes = data.success;
			$scope.$apply();
		},"json");
	};
	$scope.fetchClasses();
	
	$scope.fetchQuestTypes = function(){
		$.post(apiUrl+"quest_type/fetch",{},function(data){
			console.log(data);
			$scope.quest_types = data.success;
			$scope.$apply();
		},"json");
	};
	$scope.fetchQuestTypes();
	
	$scope.fetchQuestPools = function(){
		$.post(apiUrl+"quest_pool/fetch",{},function(data){
			console.log(data);
			$scope.quest_pools = data.success;
			$scope.$apply();
		},"json");
	};
	$scope.fetchQuestPools();
	
	$scope.fetchClasses = function(){
		$.post(apiUrl+"class/fetch",{},function(data){
			console.log(data);
			$scope.classes = data.success;
			$scope.$apply();
		},"json");
	};
	$scope.fetchClasses();
	
	$scope.fetchEnemies = function(){
		$.post(apiUrl+"enemy/fetch",{},function(data){
			console.log(data);
			$scope.enemies = data.success;
			$scope.$apply();
		},"json");
	};
	$scope.fetchEnemies();
	
	$scope.totalContent = false;
	$scope.totalCols = [];
	$scope.fetchTotalData = function(callback){
		callback = callback||function(){return false;};
		$.post(apiUrl+"util/getcontent",{},function(data){
			console.log(data);
			$scope.totalContent = data.success;
			$scope.totalCols = ['*all*'];
			$.each($scope.totalContent,function(key,val){
				$scope.totalCols.push(key);
			});
			$scope.$apply();
			callback();
		},"json");
	};
	$scope.fetchTotalData();
	
	$scope.fetchSchemas = function(callback){
		callback = callback||function(){return false;};
		$.post(apiUrl+"util/getschemas",{},function(data){
			console.log(data);
			$scope.tableSchemas = data.success;
			$scope.$apply();
			callback();
		},"json");
	};
	//**Fetchers End**//
	
	$scope.itemTypes = [
		{title:'Reusable',val:'reusable'},
		{title:'Consumable',val:'consumable'},
		{title:'Equipment, Head',val:'equip_head'},
		{title:'Equipment, Body',val:'equip_body'},
		{title:'Equipment, Legs',val:'equip_legs'},
		{title:'Equipment, Ring',val:'equip_ring'},
		{title:'Weapon',val:'weapon'},
		{title:'Ingredient',val:'ingredient'},
		{title:'Enchantment',val:'enchantment'},
		{title:'Shop Consumeable',val:'shop_consumeable'},
		{title:'Shop Equipment',val:'shop_equip'},
		{title:'Shop Reusable',val:'shop_reuseable'}
	];
	$scope.itemTypeWithVal = function(type){
		for (var i=0,len=$scope.itemTypes.length; i<len; i++){
			var check = $scope.itemTypes[i];
			if (check.val == type){return check.title;}
		}
	};
	
	$scope.actionWithId = function(actionid){
		var filtered = $scope.actions.filter(function(curVal){return curVal.id == actionid;});
		return (filtered.length>0?filtered[0]:false);
	};
	
	$scope.itemWithId = function(itemid){
		var filtered = $scope.items.filter(function(curVal){return curVal.id == itemid;});
		return (filtered.length>0?filtered[0]:false);
	};
	
	$scope.upgradeWithId = function(upgradeid){
		var filtered = $scope.upgrades.filter(function(curVal){return curVal.id == upgradeid;});
		return (filtered.length>0?filtered[0]:false);
	};
	
	$scope.questEventWithId = function(eventid){
		var filtered = $scope.questEvents.filter(function(curVal){return curVal.id == eventid;});
		return (filtered.length>0?filtered[0]:false);
	};
	
	$scope.questPoolWithId = function(eventid){
		var filtered = $scope.quest_pools.filter(function(curVal){return curVal.id == eventid;});
		return (filtered.length>0?filtered[0]:false);
	};
	
	$scope.questTypeWithId = function(eventid){
		var filtered = $scope.quest_types.filter(function(curVal){return curVal.id == eventid;});
		return (filtered.length>0?filtered[0]:false);
	};
	
	$scope.enemyWithId = function(enemyid){
		var filtered = $scope.enemies.filter(function(curVal){return curVal.id == enemyid;});
		return (filtered.length>0?filtered[0]:false);
	};
	
	$scope.itemPoolWithId = function(itemid){
		var filtered = $scope.item_pools.filter(function(curVal){return curVal.id == itemid;});
		return (filtered.length>0?filtered[0]:false);
	};
	
	$scope.enemyTypes = [
		'human',
		'skeleton',
		'troll',
		'beast'
	];
	
	$scope.enemyPoolTypes = [
		'equipment',
		'weapons',
		'loot'
	];
	
	$scope.getRequiredRecipeElement = function(recipe){
		switch(recipe.requirement){
			case "item":
				var item_obj = $scope.itemWithId(recipe.requirementid);
				if (!item_obj){return false;}else{return item_obj.name;}
				break;
			case "upgrade":
				var upgrade_obj = $scope.upgradeWithId(recipe.requirementid);
				if (!upgrade_obj){return false;}else{return upgrade_obj.name;}
				break;
			default:
				return false;
		}
	};
	
	$scope.getObjectType = function(obj){
		function checkIndExists(obj,index){return (typeof obj[index] !== "undefined");}
		var objTypes = [
			{type:'item',api:'item',inds:['type','uses']},
			{type:'recipe',api:'recipe',inds:['item','requirement','requirementid','visible_before']},
			{type:'ingredient',api:'ingredient',inds:['recipe','item','item_num']},
			{type:'itemaction',api:'itemaction',inds:['item','action']},
			{type:'item_pool_item',api:'item_pool_item',inds:['item','pool']},
			{type:'enemy',api:'enemy',inds:['enemy_name']},
			{type:'enemy_action',api:'enemy_action',inds:['enemy','action']},
			{type:'enemy_item_pool',api:'enemy_item_pool',inds:['enemy','item_pool']},
			{type:'class',api:'class',inds:['class','description','tier','occurrence']},
			{type:'class_action',api:'class_action',inds:['class','action']},
			{type:'class_item_pool',api:'class_item_pool',inds:['class','pool']},
			{type:'item_pool',api:'item_pool',inds:['pool']},
			{type:'event',api:'event',inds:['tier','occurence','danger']},
			{type:'solution',api:'solution',inds:['event','success','failure']},
			{type:'solutionaction',api:'solutionaction',inds:['solution','action']},
			{type:'upgrade',api:'upgrade',inds:['name','description','cost','type']},
			{type:'upgraderequirement',api:'upgraderequirement',inds:['upgrade','required']},
			{type:'class_quest_type',api:'class_quest_type',inds:['quest_type','class']},
			{type:'quest_type_pool',api:'quest_type_pool',inds:['quest_type','quest_pool']},
			{type:'quest_type_enemy',api:'quest_type_enemy',inds:['quest_type','enemy']},
			{type:'quest_type',api:'quest_type',inds:['quest_type']},
			{type:'quest_pool_event',api:'quest_pool_event',inds:['quest_pool','quest_event']},
			{type:'quest_pool',api:'quest_pool',inds:['quest_pool']},
			{type:'action',api:'action',inds:['action']}
		];
		for(var i=0,len=objTypes.length; i<len; i++){
			var type = objTypes[i];
			var isType = true;
			for (var h=0,lenh=type.inds.length; h<lenh; h++){
				if (!checkIndExists(obj,type.inds[h])){
					isType = false;
					break;
				}
			}
			if (isType){return type;}
		}
		return false;
	};
	
	$scope.innerAdding = false;
	$scope.toggleAddingInner = function(type,outer){
		$scope.innerAddingOuter = outer;
		$scope.innerAddingVals = {};
		if ($scope.innerAdding == type){
			$scope.innerAdding = false;
			return;
		}
		$scope.innerAdding = type;
		
		switch(type){
			case "ingredient":
				$scope.innerAddingVals = {
					item_num:1,
					recipe:outer.id
				};
				break;
			default:
				break;
		}
	};
	
	$scope.submitEdits = function(obj,ind,newval,$event){
		$event.stopPropagation();
	
		var objtype = $scope.getObjectType(obj); if (!objtype){return false;}
		
		obj[ind] = newval;
		
		if ($scope.clickedForEditing.action_name){
			obj.action_name = $scope.clickedForEditing.action_name;
		}
		if ($scope.clickedForEditing.upgrade_name){
			obj.upgrade_name = $scope.clickedForEditing.upgrade_name;
		}
		if ($scope.clickedForEditing.item_name){
			obj.item_name = $scope.clickedForEditing.item_name;
		}
		if ($scope.clickedForEditing.pool_name){
			obj.pool_name = $scope.clickedForEditing.pool_name;
		}
		
		if ($scope.clickedForEditing && $scope.clickedForEditing.obj == obj && $scope.clickedForEditing.ind == ind){$scope.clickedForEditing = false;}
		
		$.post(apiUrl+objtype.api+"/update",{index:ind,value:newval,id:obj.id},function(data){
			console.log(data);
		});
	};
	
	$scope.submitAdd = function(vals,$event){
		$event.stopPropagation();
		var objtype = $scope.getObjectType(vals); if (!objtype){return false;}
		
		$scope.submittingItem = true;
		var activeAtTime = $scope.activeObj;
		$scope.addingObj = false;
		$.post(apiUrl+objtype.api+"/add",vals,function(data){
			$scope.submittingItem = false;
			console.log(data);
			switch(objtype.type){
				case "item":
					vals.id = data.item.id;
					vals.actions = [];
					vals.recipes = [];
					vals.combat_actions = [];
					$scope.items.push(vals);
					break;
				case "action":
					vals.id = data.action.id;
					$scope.actions.push(vals);
					break;
				case "itemaction":
					if (!activeAtTime){break;}
					vals.id = data.itemaction.id;
					vals.action_name = vals.actionObj.action;
					activeAtTime.actions.push(vals);
					break;
				case "recipe":
					if (!activeAtTime){break;}
					vals.id = data.recipe.id;
					vals.ingredients = [];
					activeAtTime.recipes.push(vals);
					break;
				case "ingredient":
					if (!activeAtTime){break;}
					vals.id = data.ingredient.id;
					activeAtTime.ingredients.push(vals);
					break;
				case "event":
					vals.id = data.event.id;
					vals.solutions = [];
					$scope.questEvents.push(vals);
					break;
				case "solution":
					if (!activeAtTime){break;}
					vals.id = data.solution.id;
					vals.actions = [];
					activeAtTime.solutions.push(vals);
					break;
				case "solutionaction":
					if (!activeAtTime){break;}
					vals.id = data.solutionaction.id;
					vals.action_name = vals.actionObj.action;
					vals.aliases = [];
					activeAtTime.actions.push(vals);
					break;
				case "upgrade":
					vals.id = data.upgrade.id;
					vals.requirements = [];
					$scope.upgrades.push(vals);
					break;
				case "upgraderequirement":
					if (!activeAtTime){break;}
					vals.id = data.upgraderequirement.id;
					vals.upgrade_name = vals.requiredObj.name;
					activeAtTime.requirements.push(vals);
					break;
				case "item_pool":
					vals.id = data.item_pool.id;
					vals.items = [];
					$scope.item_pools.push(vals);
					break;
				case "item_pool_item":
					if (!activeAtTime){break;}
					vals.id = data.item_pool_item.id;
					vals.item_name = vals.itemObj.name;
					activeAtTime.items.push(vals);
					break;
				case "class":
					vals.id = data.added_class.id;
					vals.actions = [];
					vals.item_pools = [];
					$scope.classes.push(vals);
					break;
				case "class_action":
					if (!activeAtTime){break;}
					vals.id = data.class_action.id;
					vals.action_name = vals.actionObj.action;
					activeAtTime.actions.push(vals);
					break;
				case "class_item_pool":
					if (!activeAtTime){break;}
					vals.id = data.class_item_pool.id;
					vals.pool_name = vals.poolObj.pool;
					activeAtTime.item_pools.push(vals);
					break;
				case "quest_type":
					vals.id = data.quest_type.id;
					vals.quest_type_pools = [];
					vals.quest_type_enemies = [];
					$scope.quest_types.push(vals);
					break;
				case "quest_type_event":
					vals.id = data.quest_type_event.id;
					activeAtTime.quest_type_enemies.push(vals);
					break;
				case "quest_type_pool":
					vals.id = data.quest_type_pool.id;
					activeAtTime.quest_type_pools.push(vals);
					break;
				case "quest_pool":
					vals.id = data.quest_pool.id;
					vals.quest_events = [];
					$scope.quest_pools.push(vals);
					break;
				case "quest_pool_event":
					vals.id = data.quest_pool_event.id;
					activeAtTime.quest_events.push(vals);
					break;
				case "class_quest_type":
					vals.id = data.class_quest_type.id;
					activeAtTime.quest_types.push(vals);
					break;
				case "enemy":
					vals.id = data.enemy.id;
					vals.actions = [];
					vals.item_pools = [];
					$scope.enemies.push(vals);
					break;
				case "enemy_action":
					vals.id = data.enemy_action.id;
					activeAtTime.actions.push(vals);
					break;
				case "enemy_item_pool":
					vals.id = data.enemy_item_pool.id;
					activeAtTime.item_pools.push(vals);
					break;
				default:
					break;
			}
			$scope.$apply();
		},"json");
	};
	
	$scope.submitAddInner = function(outer,vals,$event){
		$event.stopPropagation();
		var objtype = $scope.getObjectType(vals); if (!objtype){return false;}
		
		$scope.submittingItem = true;
		$scope.innerAdding = false;
		$.post(apiUrl+objtype.api+"/add",vals,function(data){
			$scope.submittingItem = false;
			console.log(data);
			switch(objtype.type){
				case "ingredient":
					vals.id = data.ingredient.id;
					outer.ingredients.push(vals);
					break;
				default:
					break;
			}
			$scope.$apply();
		},"json");
	};
	
	$scope.removeObj = function(active,obj,$event){
		active = active||$scope.activeObj;
		
		$event.stopPropagation();
		console.log(obj);
		var objtype = $scope.getObjectType(obj); if (!objtype){return false;}
		switch(objtype.type){
			case "item":
				$scope.items.splice($scope.items.indexOf(obj),1);
				break;
			case "action":
				$scope.actions.splice($scope.actions.indexOf(obj),1);
				break;
			case "event":
				$scope.questEvents.splice($scope.questEvents.indexOf(obj),1);
				break;
			case "upgrade":
				$scope.upgrades.splice($scope.upgrades.indexOf(obj),1);
				break;
			case "item_pool":
				$scope.item_pools.splice($scope.item_pools.indexOf(obj),1);
				break;
			case "upgraderequirement":
				if (!$scope.activeObj){return;}
				$scope.activeObj.requirements.splice($scope.activeObj.requirements.indexOf(obj),1);
				break;
			case "itemaction":
				if (!$scope.activeObj){return;}
				$scope.activeObj.actions.splice($scope.activeObj.actions.indexOf(obj),1);
				break;
			case "recipe":
				if (!$scope.activeObj){return;}
				$scope.activeObj.recipes.splice($scope.activeObj.recipes.indexOf(obj),1);
				break;
			case "ingredient":
				if (!$scope.activeObj){return;}
				active.ingredients.splice(active.ingredients.indexOf(obj),1);
				break;
			case "solution":
				if (!$scope.activeObj){return;}
				$scope.activeObj.solutions.splice($scope.activeObj.solutions.indexOf(obj),1);
				break;
			case "solutionaction":
				if (!$scope.activeObj){return;}
				$scope.activeObj.actions.splice($scope.activeObj.actions.indexOf(obj),1);
				break;
			case "item_pool_item":
				if (!$scope.activeObj){return;}
				$scope.activeObj.items.splice($scope.activeObj.items.indexOf(obj),1);
				break;
			case "class":
				$scope.classes.splice($scope.classes.indexOf(obj),1);
				break;
			case "class_action":
				if (!$scope.activeObj){return;}
				$scope.activeObj.actions.splice($scope.activeObj.actions.indexOf(obj),1);
				break;
			case "class_item_pool":
				if (!$scope.activeObj){return;}
				$scope.activeObj.item_pools.splice($scope.activeObj.item_pools.indexOf(obj),1);
				break;
			case "class_quest_type":
				if (!$scope.activeObj){return;}
				$scope.activeObj.quest_types.splice($scope.activeObj.quest_types.indexOf(obj),1);
				break;
			case "quest_pool_event":
				if (!$scope.activeObj){return;}
				$scope.activeObj.quest_events.splice($scope.activeObj.quest_events.indexOf(obj),1);
				break;
			case "quest_type_pool":
				if (!$scope.activeObj){return;}
				$scope.activeObj.quest_type_pools.splice($scope.activeObj.quest_type_pools.indexOf(obj),1);
				break;
			case "quest_type":
				$scope.quest_types.splice($scope.quest_types.indexOf(obj),1);
				break;
			case "quest_pool":
				$scope.quest_pools.splice($scope.quest_pools.indexOf(obj),1);
				break;
			case "enemy":
				$scope.enemies.splice($scope.enemies.indexOf(obj),1);
				break;
			case "quest_type_enemy":
				if (!$scope.activeObj){return;}
				$scope.activeObj.quest_type_enemies.splice($scope.activeObj.quest_type_enemies.indexOf(obj),1);
				break;
			case "enemy_action":
				if (!$scope.activeObj){return;}
				$scope.activeObj.actions.splice($scope.activeObj.actions.indexOf(obj),1);
				break;
			case "enemy_item_pool":
				if (!$scope.activeObj){return;}
				$scope.activeObj.item_pools.splice($scope.activeObj.item_pools.indexOf(obj),1);
				break;
			default:
				break;
		}
		
		$.post(apiUrl+objtype.api+"/remove",{id:obj.id},function(data){
			console.log(data);
		});
	};
	
	$scope.submitUpdateAliases = function(obj,aliasesAdd,aliasesRemove,$event){
		$event.stopPropagation();
		
		var aliasesAddFiltered = aliasesAdd.filter(function(curVal){return obj.aliases.indexOf(curVal)<0;});
		var aliasesRemoveFiltered = aliasesRemove.filter(function(curVal){return obj.aliases.indexOf(curVal)>=0;});
		
		for(var i=0,len=aliasesAddFiltered.length; i<len; i++){
			var thisAlias = aliasesAddFiltered[i];
			$.post(apiUrl+"solutionaction/addalias",{solution_action:obj.id,action:thisAlias.id},function(data){
				console.log(data);
				thisAlias.id = data.solutionactionalias.id;
				obj.aliases.push(thisAlias);
				$scope.$apply();
			},"json");
		}
		
		for(var i=aliasesRemoveFiltered.length-1; i>=0; i--){
			$.post(apiUrl+"solutionaction/removealias",{solution_action:obj.id,action:aliasesRemoveFiltered[i].action},function(data){
				console.log(data);
			});
			obj.aliases.splice(aliasesRemoveFiltered[i],1);
		}
		
		$scope.clickedForEditing = false;
	};
	
	$scope.activeTree = [];
	$scope.setActiveObj = function(item,forceroot){
		forceroot = forceroot||false;
		if (forceroot){$scope.activeTree = []; $scope.activeObj = false; return;}
		if (item === false){
			$scope.activeTree.splice($scope.activeTree.length-1,1);
			if ($scope.activeTree.length == 0){$scope.activeObj = false; return;}else{$scope.activeObj = $scope.activeTree[$scope.activeTree.length-1]; return;}
		}
		if ($scope.activeTree.indexOf(item) >= 0){
			$scope.activeTree.splice($scope.activeTree.indexOf(item),1);
			if ($scope.activeTree.length == 0){$scope.activeObj = false; return;}else{$scope.activeObj = $scope.activeTree[$scope.activeTree.length-1]; return;}
		}
		$scope.activeObj = item;
		$scope.activeTree.push($scope.activeObj);
	};
	$scope.toggleAddingObj = function(type){
		$scope.sortBy = 'id';
		$scope.reverseSort = false;
		$scope.addingVals = {};
		if ($scope.addingObj == type){
			$scope.addingObj = false;
			return;
		}
		$scope.addingObj = type;
		switch(type){
			case "item":
				$scope.addingVals = {
					value:100,
					uses:1,
					cooldown:0,
					consumable:0
				};
				break;
			case "itemaction":
				$scope.addingVals = {
					value:1,
					frequency:'choice',
					target:'self',
					crit_chance:0,
					chance:1,
					length:1,
					item:$scope.activeObj.id,
					mana_cost:0,
					stamina_cost:0
				};
				break;
			case "recipe":
				$scope.addingVals = {
					yield:1,
					requirement:"none",
					requirementid:0,
					visible_before:1,
					item:$scope.activeObj.id
				};
				break;
			case "ingredient":
				$scope.addingVals = {
					item_num:0,
					item:0,
					recipe:$scope.activeObj.id
				};
				break;
			case "item_pool_items":
				$scope.addingVals = {
					pool:$scope.activeObj.id,
					occurrence:1,
					tier:1
				};
				break;
			case "solution":
				$scope.addingVals = {
					probability:1,
					success:"",
					failure:"",
					event:$scope.activeObj.id
				};
				break;
			case "solutionaction":
				$scope.addingVals = {
					solution:$scope.activeObj.id
				};
				break;
			case "upgraderequirement":
				$scope.addingVals = {
					upgrade:$scope.activeObj.id
				};
				break;
			case "class":
				$scope.addingVals = {
					occurrence:1,
					tier:1
				};
				break;
			case "class_action":
				$scope.addingVals = {
					class:$scope.activeObj.id,
					value:1,
					spread:0,
					chance:1
				};
				break;
			case "class_item_pool":
				$scope.addingVals = {
					class:$scope.activeObj.id,
					chance:1,
					mod_occurrence:1
				};
				break;
			case "class_quest_type":
				$scope.addingVals = {
					class:$scope.activeObj.id,
					mod_occurrence:1
				};
				break;
			case "quest_type":
				$scope.addingVals = {
					tier:0,
					occurrence:1
				};
				break;
			case "quest_type_pool":
				$scope.addingVals = {
					quest_type:$scope.activeObj.id,
					occurrence:1
				};
				break;
			case "quest_pool_event":
				$scope.addingVals = {
					quest_pool:$scope.activeObj.id,
					tier:0,
					occurrence:1
				};
				break;
			case "enemy":
				$scope.addingVals = {
					tier:0,
					occurrence:1
				};
				break;
			case "enemy_action":
				$scope.addingVals = {
					enemy:$scope.activeObj.id
				};
				break;
			case "enemy_item_pool":
				$scope.addingVals = {
					enemy:$scope.activeObj.id,
					occurrence:1
				};
				break;
			case "quest_type_enemy":
				$scope.addingVals = {
					quest_type:$scope.activeObj.id,
					occurrence:1
				};
				break;
			default:
				break;
		}
	};
	
	$scope.submitExportTable = function(col){
		if (col == "*all*"){
			$scope.fetchTotalData(function(){
				for (var i=1,len=$scope.totalCols.length; i<len; i++){
					$scope.exportTable($scope.totalCols[i]);
				}
			});
			return;
		}
		$scope.fetchTotalData(function(){
			$scope.exportTable(col);
		});
	};
}])
.filter('reverse', function() {
	return function(items) {
		return items.slice().reverse();
	};
});