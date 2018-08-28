<?php
include_once("datafetch.php");

//Example data, the real API is hosted on a web server
define('SK_SERVERNAME',"example.com");
define('SK_USERNAME',"example");
define('SK_PASSWORD',"example");
define('SK_DBNAME',"example");

function Errored($code=0,$message="",$extraargs=[],$request="",$arguments=""){
	$errorarr = ["error"=>$message,"errorcode"=>$code];
	foreach ($extraargs as $key=>$val){
		$errorarr[$key] = $val;
	}
	die(json_encode($errorarr));
}

function Success($return,$extrareturns=[]){
	$fullreturn = ["success"=>$return];
	
	foreach ($extrareturns as $key=>$val){
		$fullreturn[$key] = $val;
	}
	
	die(json_encode($fullreturn));
}
?>