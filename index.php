<?php
session_start();
require_once "master/Generic.php";
$generic  = new Generic;
$uri 			= $generic->getURIdata();
$ext 			= pathinfo($uri->page_source, PATHINFO_EXTENSION);

if(!empty($ext)){
  $url = $_SERVER["REQUEST_URI"];
  $url = str_replace(".{$ext}", "", $url);
  header("Location: {$url}");
}

$valid_pages 	= [
	"" 					=> "views/home.php",
  "resume"     => "views/resume.php",
  "portfolio"     => "views/blog.php",
  "post"     => "views/single-post.php",
];


$cache_control = "?v=local-images";
$page_exists = isset($valid_pages[$uri->page_source]);
if($page_exists == true){
  require_once($valid_pages[$uri->page_source]);
}else{
  require_once("views/home.php");
}
?>
