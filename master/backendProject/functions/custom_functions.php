<?php

function get_percent($amount, $total){
	return empty($total) ? 0 : ($amount * 100) / $total;
}

function get_percent_of($percent, $amount){
	return ($amount * $percent) / 100;
}

function number_abbr__($number){
    $abbrevs = [12 => 'T', 9 => 'B', 6 => 'M', 3 => 'k', 0 => ''];

    foreach ($abbrevs as $exponent => $abbrev) {
        if (abs($number) >= pow(10, $exponent)) {
            $display = $number / pow(10, $exponent);
            $decimals = ($exponent >= 3 && round($display) < 100) ? 2 : 0;
            $number = number_format($display, $decimals).$abbrev;
            break;
        }
    }

    return $number;
}



function prepare_new_member($post) {
	$post = hash_password($post);
	$post->username = substr(explode("@", $post->email)[0], 0, 7).random(3);
	return $post;
}

function welcome_to_graphcoin($user){
	$response = graphcoin_login($user, "Registration");
	return $response;
}

function graphcoin_login($user, $action = 'Login'){
	global $generic;
	global $paramControl;
  $uri = $generic->getURIdata();
	require_once(absolute_filepath("{$uri->backend}/controllers/Messenger.php"));
	$response = new stdClass;
	$messenger = new Messenger($generic);
	$company = $generic->company();
	$messenger->pinAction = "login";

	if($action === "Registration"){

		if(!empty($user->primary_key))$user->id = $user->primary_key;
		// Create a wallet record
		$generic::$mydb->query("INSERT INTO account SET user_id='{$user->id}'");
		$charges = $paramControl->load_sources("fixed-charges");
		// Create a  referral record
		if (!empty($user->referral)) {
			$reff = $generic->getFromTable("users", "username={$user->referral}", 1, 1);
			if(count($reff)){
				$reff = reset($reff);
				$generic::$mydb->query("INSERT INTO referral SET referred_id='{$user->id}', referral_id='{$reff->id}', amount='$charges->referral_bonus}'");
			}
		}
		// Send Welcome Email
    $user->username = strtoupper($user->username);
		$welcome_mail = (object)[
			"subject" => "Welcome to {$company->name}",
			"body" => "hi",
			"to" => $user->email,
			"from" => $company->email,
			"from_name" => ucwords($company->name),
			"to_name" => "{$user->username}",
			"template" =>"registeration",
		];
		$messenger->sendMail($welcome_mail);
	}else{
		$_SESSION["loggedin"] = 1;
	}
	$_SESSION["member_id"] = $user->id;
	$_SESSION["username"] = $user->username;
	$_SESSION["email"] = $user->email;
	$response->status = 1;
  $desc = ["Login"=>"Welcome, {$user->username}", "Registration"=>"Welcome, your registration was successful."];
	$response->message = $desc[$action];
	$response->id = $user->id;
	return $response;
}

function toggle_transaction_status($post){
	global $generic;
	global $paramControl;
	$db = $generic::$mydb;
	// check for the status to know if the request is happening for the second time
	// update the users balance
	// check for referral and update the referral balance
	// add to the transaction table for the user and for the referral
	if($post->status == 1){
		$transaction_type = $paramControl->load_sources("transaction-types");
		$nairaEntity = $paramControl->load_sources("nairaEntity");
		$TRANSACTION_CHARGES = $paramControl->load_sources("fixed-charges");
		$debited = $post->amount - $TRANSACTION_CHARGES->deposit_charges;
		 $update = $db->query("UPDATE account SET balance=balance+{$debited} WHERE user_id='{$post->user_id}'");
		 if($update){
			 $company = $generic->company();
			 $fmn = new NumberFormatter("en", NumberFormatter::DECIMAL );
			 $formated_amount = $fmn->format($post->amount);
			 $uri = $generic->getURIdata();
			 require_once(absolute_filepath("{$uri->backend}/controllers/Messenger.php"));

			 $user = $generic->getFromTable("users", "id={$post->user_id}");
			 $user = reset($user);

			 // Insert a transaction log for the charges
			 $generic->insert(
				 object(["user_id"=>$post->user_id, "tx_no" => uniqid(time()), "amount" => $TRANSACTION_CHARGES->deposit_charges, "description" => "Service charge of {$appCurrency}{$TRANSACTION_CHARGES->deposit_charges} for Deposit", "status" => 1]),
				 "charges"
			 );

			 // Notify The user
			 $messenger = new Messenger($generic);
			 $mail = (object)[
				 "subject" => "Deposit Confirmation !!!",
				 "body" => "Congratulations dear {$user->username}, <br> Your deposit of {$nairaEntity}{$formated_amount} has been approved. You may proceed to purchase {$company->other} and start making your money. <br> <strong>The sky is your limit !!!</strong>",
				 "to" => $user->email,
				 "from" => $company->email,
				 "from_name" => ucwords($company->name),
				 "to_name" => "{$user->username}",
				 "template" =>"success",
			 ];
			 $messenger->sendMail($mail);
		 }
		//  check for referral
		 $referral = $generic->getFromTable("referral", "referred_id={$post->user_id}, status=0");
		 $referral = reset($referral);
		 if(!empty($referral)){
			$formated_referral_amount = number_format($referral->amount,2,".",",");
			$db->query("UPDATE referral SET status='1' WHERE referred_id='{$post->user_id}'");
			$db->query("UPDATE account SET bonus=bonus+{$referral->amount} WHERE user_id='{$referral->referral_id}'");
			// Log the referral bonus transaction
			$generic->insert(
				object(["user_id"=>$referral->referral_id, "tx_no"=>uniqid(time()), "amount"=>$referral->amount, "description" => "Referral Bonus of {$nairaEntity}$formated_referral_amount", "status" => 1]),
				"credit"
			);
		 }
	}
	return $post;
}


function sendCode($messenger, $user) {
	global $generic;
	$actions 		= [
		"login"=>"login",
		"code"=>"reset your password",
		"wallet"=>"modify your wallet",
		"update-email"=>"update your email",
		"changeWallet"=>"change wallet address",
		"withdrawal"=>"authenticate your withdrawal",
		"verify-email"=>"Verify your Email Address",
		"generateToken"=>"Generate token for your transaction",
	];
	$action 		= $messenger->pinAction;
	$title  		= $actions[$action];

	if(!empty($_SESSION[$action]))$loginCode = $_SESSION[$action];
	else $loginCode 	= rand(100000,999999);
	
	$company 		= $generic->company();
	$mail 			= (object)[
		'subject'		=>	"Token",
		'body'			=>	"Use this token to {$title}. \n $loginCode",
		'from'			=>	$company->email,
		'to'				=>	$user->email,
		'from_name'	=>	$company->name,
		'to_name'		=>	"{$user->username}",
		'template'	=>	"token",
		'token'	=>	$loginCode
	];
	$response 	= $messenger->sendMail($mail);
	$response->k=$user->id;
	$_SESSION[$action] = $loginCode;
	if(in_array($generic->getServer(), $generic->getLocalServers())){
		$response->{$action} = $loginCode;
	}
	return $response;
}

//Inserts an activity log into the database
function activity($post){
	$site 	 = new Generic;
	$db = $site->connect();
	$uri = $site->getURIdata();
	$response = $site->insert($post, "log");
	// $db->close();
	unset($db);
	return $response;
}


function paystack_reciepient($post){
	global $generic;
	$paystack = object($generic::$paystack);
	$paystack = $paystack->{$paystack->DEFAULT};
	$url = "https://api.paystack.co/transferrecipient";
  $fields = [
    "type" => "nuban",
    "name" => $post->account_name,
    // "description" => "Zombier",
    "account_number" => $post->account_no,
    "bank_code" => $post->bank_code,
    "currency" => "NGN"
  ];
	$fields_string = http_build_query($fields);
  //open connection
  $ch = curl_init();

  //set the url, number of POST vars, POST data
  curl_setopt($ch,CURLOPT_URL, $url);
  curl_setopt($ch,CURLOPT_POST, true);
  curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"accept: application/json",
		"authorization: Bearer {$paystack}",
		"cache-control: no-cache"
  ));

  //So that curl_exec returns the contents of the cURL; rather than echoing it
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

  //execute post
  $result = curl_exec($ch);
	$response = isJson($result);
	if($response->status){
		$post->recipient = $response->data->recipient_code;
	}else $post->error = $response->message;
	return $post;
}


function getToken($length, $type = null)
     {
         $token = "";
         $codeAlphabet = '';
         if ($type == "number") {
             $codeAlphabet = "0123456789";
         } elseif ($type == "letter") {
             $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
         } else {
             $codeAlphabet = random(10)."ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
         }
         $max = strlen($codeAlphabet); // edited

         for ($i=0; $i < $length; $i++) {
             $token .= $codeAlphabet[random_int(0, $max-1)];
         }

         return $token;
     }

		 function getFixedToken($length, $type = null)
     {
			  global $generic;
				$tokens = $generic->getFromTable("cash_tokens","",1,0,null,false,["token"]);
				$tokens  = array_column($tokens,"token");
	      $generatedToken =  strtoupper(getToken($length,$type));
				if(in_array($generatedToken,$tokens)){getFixedToken($length,$type);}
        return $generatedToken;
     }

		 function handleTrail($name){
			 	// Notify user of available trials
				 if(!empty($_SESSION[$name]) && $_SESSION[$name] == "suspended"){ return "Your account has been suspended, contact support";}
					$_SESSION[$name] = empty($_SESSION[$name]) ? 4 : $_SESSION[$name]-1;
					$count = $_SESSION[$name]-1;
					$message = "Incorrect Pin. You have {$count} trial left.";
					if($_SESSION[$name]== 1  ){
						global $generic;
						$db = $generic::$mydb;
						$user_id = $_SESSION['member_id'];
						$db->query("UPDATE users SET status='0' WHERE id='{$user_id}'");
						$message = "Your account has been suspended, contact support";
						$_SESSION[$name] = "suspended";
					}
					return $message;
		 }


?>
