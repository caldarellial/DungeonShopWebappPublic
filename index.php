<?php include_once("setup.php"); ?>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Roboto:300,400,500,700" rel="stylesheet">
	<link rel="stylesheet" href="css/bootstrap.css" type="text/css">
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
	<script src='js/moment.min.js'></script>
	<link href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="css/font-awesome-animation.min.css">
    <link rel="stylesheet" href="css/main.css?v=<?php echo $version; ?>" type="text/css">
    <link rel="stylesheet" href="css/style.css?v=<?php echo $version; ?>" type="text/css">
	
	</head>
	<body>
		<section ng-app="SKClient" ng-controller="SKController">
            <div class="content col-xs-12">
                <div class="tablist">
                    <div class="tab" ng-class="{'active':activeTab=='summary'}" ng-click="setActiveTab('summary');">
                        <div class="tabcontent">
                            Summary
                        </div>
                    </div>
                    <div class="tab" ng-class="{'active':activeTab=='items'}" ng-click="setActiveTab('items');">
                        <div class="tabcontent">
                            Items
                        </div>
                    </div>
                    <div class="tab" ng-class="{'active':activeTab=='item_pools'}" ng-click="setActiveTab('item_pools');">
                        <div class="tabcontent">
                            Item Pools
                        </div>
                    </div>
                    <div class="tab" ng-class="{'active':activeTab=='actions'}" ng-click="setActiveTab('actions');">
                        <div class="tabcontent">
                            Actions
                        </div>
                    </div>
                    <div class="tab" ng-class="{'active':activeTab=='upgrades'}" ng-click="setActiveTab('upgrades');">
                        <div class="tabcontent">
                            Upgrades
                        </div>
                    </div>
                    <div class="tab" ng-class="{'active':activeTab=='classes'}" ng-click="setActiveTab('classes');">
                        <div class="tabcontent">
                            Classes
                        </div>
                    </div>
                    <div class="tab" ng-class="{'active':activeTab=='enemies'}" ng-click="setActiveTab('enemies');">
                        <div class="tabcontent">
                            Enemies
                        </div>
                    </div>
                    <div class="tab" ng-class="{'active':activeTab=='qevents'}" ng-click="setActiveTab('qevents');">
                        <div class="tabcontent">
                            Quest Events
                        </div>
                    </div>
                    <div class="tab" ng-class="{'active':activeTab=='quest_pools'}" ng-click="setActiveTab('quest_pools');">
                        <div class="tabcontent">
                            Quest Event Pools
                        </div>
                    </div>
                    <div class="tab" ng-class="{'active':activeTab=='quest_types'}" ng-click="setActiveTab('quest_types');">
                        <div class="tabcontent">
                            Quest Types
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div ng-include="'tabcontent/'+activeTab+'.html?v={{version}}'"></div>
                </div>
            </div>
		</section>

		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.0rc1/angular-route.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular-sanitize.js" type="text/javascript"></script>
		
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-animate.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-aria.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-messages.min.js"></script>
		
	
		<script src="https://ajax.googleapis.com/ajax/libs/angular_material/1.1.4/angular-material.min.js"></script>
		
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>
		<script src="js/helpers.js"></script>
        <script src="js/csv.js"></script>
		
		<script>
			var apiUrl = "<?php echo API_LOCATION; ?>";
            var currentVersion = "<?php echo $version ?>";
		</script>
        
		<script src="js/contextMenu.js?v=<?php echo $version ?>"></script>
		
		<script src="js/app/util.js?v=<?php echo $version ?>"></script>
		<script src="js/app/app.js?v=<?php echo $version ?>"></script>
	</body>
</html>