<?php
if(!empty($_GET["format"]) ){	header('Content-Type: application/json');}
session_start();
require_once("../controllers/Controllers.php");
require_once("../controllers/Ecommerce.php");
require_once("../controllers/Messenger.php");
extract($_POST, EXTR_OVERWRITE);
$post = array_merge($_POST, $_GET);
$post =  (object)$post;
$session =  (object)$_SESSION;
$response = new stdClass;
$response->status = 0;


$generic 	= new Ecommerce;
$paramControl = new ParamControl($generic);
$db 	= $generic->connect();
$uri 	= $generic->getURIdata();
$company = $generic->company();

if(isset($_GET["case"]))$case = $_GET["case"];
if(!isset($_COOKIE['u_vuser'])){setcookie("u_vuser", time(), time()+10*365*24*60*60, '/');}

switch($case){
	case 1:// Social Share Article
		$sel = $db->query("SELECT title, body, id, type, image from content WHERE id='$article_id'");
		while($row = $sel->fetch_assoc()){
			$data['image'] = json_decode($row['image'],true)[0]['src'];
			$data['body'] = explode('.',strip_tags($row['body']))[0];
			$data['title'] = ucwords($row['title']);
			$data['url'] = $generic->domain."$pageName/$article_id/".str_replace(' ','-', strtolower($row['title']));
		}
		$response = $data;
	break;
	case 1.2:// get captcha
		require_once("captcha.php");
		$response = $_captcha;
	break;
	case 1.3:// send Mail forgot password
		$post = $_POST;
		$auth = new Auth($post, $post["formName"]);
		$response = $auth->forgotPassword($post);
	break;
	case 1.35:// delete the code sent after 10 mins
		unset($_SESSION["code"]);
		$response = ["status"=>1];
	break;
	case 1.4://update password
		$post = $_POST;
		$auth = new Auth($post, $post["formName"]);
		$response = $auth->resetPassword($post);
	break;
	case 1.5://Update Cart
		$cart_data = $data;
		$validate_qty = empty($ignore) ? true : false;
		$response = $generic->updateCart($cart_data, $validate_qty);
	break;
	case 1.6://Login
		$post = $_POST;
		$auth = new Auth($post, $post["formName"]);
		$response = $auth->login($_POST);
	break;
	case 1.7://Submit any Form
		$response = $generic->submitForm($_POST);
	break;
	case 1.8://Manage Shipping Details of a user
		$action = isset($_POST['action']) ? $_POST['action'] : "";
		$response = $generic->manageShippingAddress($_POST, $action);
	break;


	case 4://Load comments
		$comments = $generic->getFromTable("conversation", "post_id=$post_id", 1, 8, TIME_DESC);
		$resort = [];
		$count = count($comments);
		foreach ($comments as $key => $value) {
			$timediff = new DateDifference($comments[$key]->time, date("Y-m-d H:i:s"));
			$comments[$key]->time = $timediff->smart();
			$resort[$count] = $comments[$key];
			$count--;
		}
		$response = $resort;
	break;
	case 4.2://Insert Comment
		$build = $response= [];
		$post = (object)$_POST;
		$table = "conversation";
		$post->type = "comment";
		$fields = $generic->getTableFields($table);
		$db =  $generic->connect();
		foreach ($post as $key => $value) {
			if(array_search(strtolower($key), $fields) !==false){
				$v=$db->real_escape_string($value);
				$build[] = "$key='{$v}'";
			}
		}
		$build = implode(",", $build);
		$sql = "INSERT INTO $table SET $build";
		$query = $db->query($sql);
		if(!empty($query)){
			$response["status"] = 1;
			$response["message"] = "Done";
		}else {
			$response["status"] = 0;
			$response["message"] = $db->error;
		}
	break;
	default: $response = ["error"=>"Incorrect 'case' parameter"];
}
$db->close();
echo json_encode($response);
?>
