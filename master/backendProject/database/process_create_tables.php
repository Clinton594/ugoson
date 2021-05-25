<?php
//Generic Database Modifier Created by Clinton 25/07/2019.

//NEXT VERSION PROSPECTS:
/*
	*Compare columns to know which ones to modify it's datatypes
	*Get callback function to run actions before droping non-existing columns (probably by setting parameters or something)
*/
$backend = absolute_filepath($uri->backend);
// Require pages that contain your queries
require_once "{$backend}backendProject/database/GBAcc_tables.php";
// require_once "{$uri->root}backendProject/database/GBTrans_tables.php";
// require_once "{$uri->root}backendProject/database/Project_tables.php";
require_once "{$backend}controllers/TableControl.php";

//Formats for passing queries to run
//$queries = ["query1","query2"];
// or
//$queries = "query1-query10";

$tableControl =  new TableControl($auth);
$response     =  $tableControl->run("query1-query11", function($response){
  // Write your callback actions here
  global $auth;
  global $tableControl;
  $row = $auth->getFromTable("company_info", "id=1", 1, 1);
  if(empty($row)){
    $update_response = $tableControl->update_generic_tables();
    $response = array_merge($response, $update_response);
    // insert default coin and groups
    $name = random(6);
    $response[] = $auth::$mydb->query("INSERT INTO team SET name='{$name}', id='1', status='0', date=now(), type='sell', coin_price='1000', rate='15'");
    $response[] = $auth::$mydb->query("INSERT INTO team SET name='{$name}x', id='2', status='0', date=now(), coin_price='1000', rate='15'");
    $response[] = $auth::$mydb->query("INSERT INTO coin SET id='1', price='1000',	rate='15', team_id='2', charge='2'");
    $response[] = "Added default coins";
  }
  return($response);
});
if(!empty($_GET["redir"])){
  header("Location: {$uri->backend}{$_GET["redir"]}");
}else {
  see($response);
}
?>
