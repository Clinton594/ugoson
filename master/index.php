<?php
session_start();
require_once "controllers/Controllers.php";
require_once "controllers/ParamControl.php";
$session	=	(object)$_SESSION;
$URI      = explode("/", $_SERVER["REQUEST_URI"]);
$reset    = end($URI);
$user_id  = (isset($session->user_id)) ? $session->user_id : null;
if($reset == "reset"){
  $auth   = new Generic;
}else{
  $auth   = new Auth($user_id);
}
$uri 			= $auth->getURIdata();
$param  	= new ParamControl($auth);
$login		=	$param->get_params("admin_signin");
if(!empty($uri->backend)){
  $uri->page_source = $uri->content_id;
}
if($uri->page_source !== "run_tables" && $uri->page_source !== "reset"){
  $db 			= $auth->connect();
  $company 	= $auth->company();
  $ext 			= pathinfo($uri->page_source, PATHINFO_EXTENSION);
  $_color   = array("green","red","blue","cyan darken-3","teal darken-4","orange","light-green darken-3","lime darken-1","pink darken-4","purple darken-4", "purple accent-4");
  shuffle($_color);
  if(!empty($ext)){
    $url = $_SERVER["REQUEST_URI"];
    $url = str_replace(".${ext}","",$url);
    header("Location: ${url}");
  }
  if(empty($login->interface)){
    die("Go to 'admin_signin' section of param_generic and set the 'interface' paramter as (signin1 or signin2) ");
  }
}
$valid_pages 	= [
	"" 					=> "views/default.php",
	"home" 			=> "views/default.php",
	"index" 		=> "views/default.php",
	"login" 		=> "views/{$login->interface}.php",
	"logout" 		=> "processors/process_logout.php",
	"reset"     => "processors/process_prepare.php",
  "run_tables"=> "backendProject/database/process_create_tables.php",
  "cron-job"  => "backendProject/functions/cronjob.php",
];
$browser_caching = "?v=".random(8);
$page_exists = isset($valid_pages[$uri->page_source]);
if($page_exists == true){
  require_once($valid_pages[$uri->page_source]);
}else{
  die("Htaccess Caught up in admin");
}
if($uri->page_source !== "run_tables"){
  $db->close();
}
?>
