<?php
$user = $auth->user();
session_destroy();
if(isset($user->primary_key)){
	$name = $auth->name_col;
	$db->query("INSERT INTO activitylog (user_id, action, location, location_id, description) VALUES ('{$user->primary_key}', 'logout', 'users', '{$user->primary_key}', '{$user->name_col} signed out')") or die($db->error);
}
setcookie("GBid", '0', time()-36000,"/");
$msg = isset($_GET['msg']) ? '?msg='.$_GET['msg'] : '';
header("Location: {$uri->backend}login{$msg}");
?>
