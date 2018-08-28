<?php
    define("THROUGH_API",true);
    //$_POST["sandbox"]=true;
    if (isset($_GET["sandbox"])){
		$_POST["sandbox"] = true;
	}
    include_once("config.php");

    $section=$_GET["section"];
    $call=$_GET["call"];
    $withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $call);
	
	if (isset($_POST["sandbox"]) && file_exists("$section/sandbox/$withoutExt.php")){
		include_once($section."/sandbox/".$withoutExt.".php");
	}else if (file_exists($section."/".$withoutExt.".php")){
		include_once($section."/".$withoutExt.".php");
	}else{
		die(json_encode(["error"=>"Invalid API call"]));
	}
?>