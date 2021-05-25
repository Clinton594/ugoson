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
  "about"     => "views/home.php",
  "resume"     => "views/resume.php",
  "blog"     => "views/blog.php",
  "portfolio"     => "views/portfolio.php",
  "contact"     => "views/contact.php",
  "post"     => "views/single-post.php",
];


$cache_control = "?v=maiyc";
$page_exists = isset($valid_pages[$uri->page_source]);
if($page_exists == true){
  require_once($valid_pages[$uri->page_source]);
}else{
  require_once("views/not-found.php");
}
?>
