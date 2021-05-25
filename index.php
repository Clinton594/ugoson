<?php
session_start();

require_once "master/controllers/Controllers.php";
require_once("master/controllers/ForExchange.php");
$session	=	(object)$_SESSION;
$generic  = new Generic;
$uri 			= $generic->getURIdata(true);
$db 			= $generic->connect();
$company 	= $generic->company();
$paramControl 	= new ParamControl($generic);
$appCurrency = $paramControl->load_sources("nairaEntity");

$ext 			= pathinfo($uri->page_source, PATHINFO_EXTENSION);

if(!empty($ext)){
  $url = $_SERVER["REQUEST_URI"];
  $url = str_replace(".{$ext}", "", $url);
  header("Location: {$url}");
}
$fmt = new NumberFormatter("en", NumberFormatter::CURRENCY );
$fmn = new NumberFormatter("en", NumberFormatter::DECIMAL );
$valid_pages 	= [
	"" 					=> "views/home.php",
  "about"     => "views/home.php",
  "resume"     => "views/resume.php",
  "blog"     => "views/blog.php",
  "portfolio"     => "views/portfolio.php",
  "contact"     => "views/contact.php",
  "post"     => "views/single-post.php",
];


$dashboard_pages = ["dashboard","profile"];
$cache_control = "?v=maiyc";
$blog = $coins = [];
$page_exists = isset($valid_pages[$uri->page_source]);
if($page_exists == true){
  require_once($valid_pages[$uri->page_source]);
}else{
  require_once("views/not-found.php");
}
$db->close();
?>
