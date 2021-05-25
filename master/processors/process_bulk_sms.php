<?php
require_once "../controllers/Controllers.php";
require_once "../functions/barcode_functions.php";
//Intstantiate the Generic class
$cookie		    = explode(",", $_COOKIE["siteData"]);
$generic 			= new Generic($cookie[1]);
$post    			= (object)$_POST;
$db						= $generic->connect();
$uri					= $generic->getURIdata();



?>
