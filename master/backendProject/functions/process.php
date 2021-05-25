<?php
require_once("../controllers/Messenger.php");
$TRANSACTION_CHARGES = $paramControl->load_sources("fixed-charges");
$appCurrency = $paramControl->load_sources("nairaEntity");
$fmn = new NumberFormatter("en", NumberFormatter::DECIMAL );

if(empty($session->member_id)){
	$response->status = 2;
	$response->message = "Authentication Failed";
	die(json_encode($response));
}
// see($post);
switch($post->case){
	case "confirm-email"://Resend Pin
		if(!empty($session->{$post->pinAction})){
			if ($session->{$post->pinAction} == $post->code) {
				unset($_SESSION[$post->pinAction]);
				$db->query("UPDATE users set status='1' WHERE id='{$session->member_id}'");
				$response->status = 1;
				$_SESSION["loggedin"] = 1;
			}else $response->message = "Incorrect token";
		}else{
			$response->message = "Please Resend Code";
		}
	break;
	case "resendPin"://Resend Pin
		$messenger= new Messenger($generic);
		$messenger->pinAction = empty($post->pinAction) ? "login" : $post->pinAction;
		$user = $generic->getFromTable("users", "id={$session->member_id}", 1, 0);
		$user = reset($user);
		$response = sendCode($messenger, $user);
	break;
	case "cronUpdate":
		$post->user_id = $session->member_id;
		$required = ["user_id"];
		foreach ($required as $key) {if(empty($post->{$key}))$empty[] = $key;}
		if(empty($empty)){
			$coin = $generic->getFromTable("coin", "id=1");
			$coin = reset($coin);

			$account = $generic->getFromTable("account", "user_id={$post->user_id}", 1, 1);
			$account = reset($account);

			//Active teams
			$active_teams = $generic->getFromTable("team", "status=0", 1, 2, ID_DESC);

			// buy team
			$buy_team = array_filter($active_teams, function ($t) use ($coin) { return $t->id == $coin->team_id;});
			$buy_team = reset($buy_team);

			// sel team
			$sell_team = array_filter($active_teams, function ($t) { return $t->type == "sell"; });
			$sell_team = reset($sell_team);

			// All Active buy and sell contributions
			$contributions  = $generic->getFromTable("contributions", "team_id={$buy_team->id}, team_id={$sell_team->id}", 1, 0);
			$grouped_contributions = array_group($contributions, "team_id");

			// All buying contributions
			$contributionx = !empty($grouped_contributions[$buy_team->id]) ? $grouped_contributions[$buy_team->id] : [];

			// This User contributions
			$user_contrib  = array_filter($contributions, function ($cont) use ($post) {
				return ($cont->user_id == $post->user_id);
			});
			$user_contrib  = array_group($user_contrib, "team_id");

			// This User's grouped contributions
			$user_buy      = !empty($user_contrib[$buy_team->id]) ? $user_contrib[$buy_team->id] : [];
			$user_sell     = !empty($user_contrib[$sell_team->id]) ? $user_contrib[$sell_team->id] : [];

			$buy_amount = array_sum(array_column($user_buy, "amount"));
			$sell_amount = array_sum(array_column($user_sell, "amount"));

			// Sell - calculation
			$percentage_contribution = get_percent($sell_amount, $sell_team->coin_price);

			$response->status = 1;
			$response->data = [
				"group_name" => $buy_team->name,
				"coin_price" => intval($coin->price),
				"contributed_amount" => array_sum(array_column($contributionx, "amount")),
				"group_members" => count($contributionx),
				"rate" => intval($coin->rate),
				"max" => $TRANSACTION_CHARGES->max_buy_coin_amount,
				"appCurrency" => $appCurrency,
				"appName" => $company->other,
				"charge" => intval($coin->charge),
				"current_balance" => round($account->balance, 2),
				"referral_balance" => round($account->bonus, 2),
				"buying" => ["amount"=>round($buy_amount, 2), "percent"=>round(get_percent($buy_amount, $coin->price), 2)],
				"selling" => ["amount"=>round(get_percent_of($percentage_contribution, $buy_team->coin_price), 2), "percent"=>round($percentage_contribution, 2), "evaluation"=>round($sell_amount, 2)]
			];
		} else {
			$response->message = implode(", ", $empty)." not found !!!";
		}
	break;
	case "account-number":
		$response->status = 1;
		$response->data = $paramControl->load_sources("account-number");
		$response->deposit_charges = $paramControl->load_sources("fixed-charges")->deposit_charges;
		$response->appCurrency = $appCurrency;
		$response->user_id = $session->member_id;
	break;
	case "myAccountBalance":
		$userAccount = $generic->getFromTable("account", "user_id={$session->member_id}");
		if(count($userAccount)){
			$response->status = 1;
			$account_name = $userAccount[0]->account_name;
			$account_code = $userAccount[0]->bank_code;
			$response->data = object(array_map("intval", arrray(reset($userAccount))));
			$response->data->banks  = $paramControl->load_sources("banks");
			$response->data->account_name  = $account_name;
			$response->data->bank_code  = $account_code;
			$response->data->currency  = $appCurrency;
		}
	break;
	case "fundAccount":
		$post->user_id = $session->member_id;
		$required = ["type","user_id"];
		foreach ($required as $key) { if(empty($post->{$key}))$empty[] = $key;}
		if(empty($empty)){
			if($post->type == "token"){
				$token = $generic->getFromTable("cash_tokens", "status=0, token={$post->token}");
				if(count($token)){
					$token = reset($token);
					// Deduct Deposit charges
					$debited = $token->amount - $TRANSACTION_CHARGES->deposit_charges;
					$formated_token_amount = $fmn->format($token->amount);
					// Update who used the token
					$db->query("UPDATE cash_tokens SET status='1', user_id={$session->member_id} WHERE id='{$token->id}'");
					// Credit the user's account
					$db->query("UPDATE account SET balance=balance+{$debited} WHERE user_id='{$session->member_id}'");
					// Insert a transaction log for the deposit
					$generic->insert(
						object(["user_id" => $session->member_id, "tx_no" => $token->token, "amount" => $token->amount, "description" => "Deposit of {$appCurrency}$formated_token_amount via token", "status" => 1]),
						"deposits"
					);
					// Insert a transaction log for the charges
					$generic->insert(
						object(["user_id" => $session->member_id, "tx_no" => $token->token, "amount" => $TRANSACTION_CHARGES->deposit_charges, "description" => "Service charge of {$appCurrency}{$TRANSACTION_CHARGES->deposit_charges} for Deposit", "status" => 1]),
						"charges"
					);
					// check for referral
					$referral = $generic->getFromTable("referral", "status=0, referred_id={$session->member_id}");
					if(count($referral)){
						$referral = reset($referral);
						$formated_referral_amount = $fmn->format($referral->amount);
						$db->query("UPDATE referral SET status='1' WHERE referred_id='{$session->member_id}'");
						$db->query("UPDATE account SET bonus=bonus+{$referral->amount} WHERE user_id='{$referral->referral_id}'");
						// Insert a transaction log for the referral bonus
						$generic->insert(
							object(["user_id"=>$referral->referral_id, "tx_no"=>uniqid($referral->referral_id), "amount"=>$referral->amount, "description" => "Referral Bonus of {$appCurrency}$formated_referral_amount", "status" => 1]),
							"credit"
						);
					}
					$response->message = "Account Deposit was successfull";
					$response->status = 1;
				}else $response->message = "Invalid Payment Token";
			}else if($post->type == "bank"){
				if(!empty($post->amount) && !empty($post->evidence) && is_numeric($post->amount)){
					if($post->amount >= $TRANSACTION_CHARGES->min_deposit_amount){
						$formated_transfer_amount = $fmn->format($post->amount);
						$bank_account = (array) $paramControl->load_sources("account-number");
						$bank_account = implode(" ", $bank_account);

						// Insert a transaction log for the charges
						$response = $generic->insert(
							object(["user_id" => $session->member_id, "tx_no" => uniqid($session->member_id), "amount" => $post->amount, "description" => "Deposit of {$appCurrency}$formated_transfer_amount via bank transfer", "evidence"=>$post->evidence, "status" => 0, "paid_into"=>$bank_account]),
							"deposits"
						);
					}else $response->message = "Minimum deposit amount is {$appCurrency}".$fmn->format($TRANSACTION_CHARGES->min_deposit_amount);
				}else $response->message = "Invalid Payment Data";
			}else $response->message = "Unauthorized Request";
		}else $response->message = implode(", ", $empty)." not found !!!";
	break;
	// Withdrawal by token
	case "tokenCrud":
		$post->user_id = $session->member_id;
		$required = ["type","user_id", "pin"];
		foreach ($required as $key) { if(empty($post->{$key}))$empty[] = $key;}
		if(empty($empty)){
			$message = handleTrail("generateToken_trial");
			if($post->type == "generate"){
				$amount = $fmn->format($post->amount);
				if($post->pin == $session->generateToken && $_SESSION["generateToken_trial"] > 1 ){
					if(!empty($post->amount) && is_numeric($post->amount)){
						if($post->amount >= $TRANSACTION_CHARGES->min_deposit_amount){
							//  get this users account information
							$userAccount = $generic->getFromTable("account", "user_id={$post->user_id}");
							$userAccount = reset($userAccount);
							if($post->amount <= $userAccount->balance){

								// Generate token
								$token = strtoupper(getFixedToken(8));
								// Minus the amount from the person's account
								$db->query("UPDATE `account` SET `balance`=balance-{$post->amount} WHERE `user_id`='{$session->member_id}'");
								// Insert the token into the database
								$db->query("INSERT INTO `cash_tokens`(`creator_id`,`user_id`,`token`,`amount`,`status`) VALUES('{$session->member_id}','0','{$token}','{$post->amount}','0')");
								// Create record for the transaction
								$db->query("INSERT INTO  `transaction`(`user_id`,`tx_no`,`tx_type`,`amount`,`description`,`status`)
								VALUES('$session->member_id','{$token}','WTD','{$post->amount}','{$appCurrency}$post->amount was withdrawn via Token','1')");

								// Notify the user via email
								$messenger = new Messenger($generic);
								$mail = (object)[
									"subject" => "Withdrawal Alert !!!",
									"body" => "{$appCurrency}$amount was withdrawn via Token.",
									"to" => $session->email,
									"from" => $company->email,
									"from_name" => ucwords($company->name),
									"to_name" => "{$session->username}",
									"template" =>"notify",
								];
								$messenger->sendMail($mail);

								$response->message = "{$appCurrency}$formated_amount Token successfully generated";
								$response->status = 1;

								if($response->status) unset($_SESSION["generateToken"]);
								if($response->status && !empty($_SESSION["generateToken_trial"])){ unset($_SESSION["generateToken_trial"]); }
								$response->token = $token;
							}else	$response->message = "Insufficient balance to generate this token";
						}else $response->message = "Minimum amount required to generate a token is  {$appCurrency}".number_format($TRANSACTION_CHARGES->min_deposit_amount,0,".",",");
					}else $response->message = "Unsupported Token Amount";
				}else $response->message = $message;
			}else if($post->type == "delete"){
				if($post->pin == $session->generateToken && $_SESSION["generateToken_trial"] > 1){
					if(!empty($post->token)){
						$token = $generic->getFromTable("cash_tokens", "creator_id={$session->member_id}, status=0, token={$post->token}");
						if(count($token)){
							$token = reset($token);
							$result = $db->query("DELETE FROM `cash_tokens` WHERE id='{$token->id}'");
							if($result){
								// update account
								$db->query("UPDATE `account` SET `balance`=balance+{$token->amount} WHERE `user_id`='{$session->member_id}'");
								// update transaction history
								$amount = $fmn->format($token->amount);
								$token->amount = -1 * $token->amount;
								$db->query("INSERT INTO  `transaction`(`user_id`,`tx_no`,`tx_type`,`amount`,`description`,`status`)  VALUES('$session->member_id','{$token->token}','CRD','$token->amount','{$appCurrency}$amount was refunded back to your account.','1')");
								$response->message = "Token successfully deleted";
								$response->status = 1;
							}else $response->message = "Token failed to delete, please try again";
						}else $response->message = "Token doesn't exist";
					}else $response->message = "Invalid Token Data";
				}else $response->message = $message;
			} else $response->message = "Invalid Transaction Type";
		}else $response->message = implode(", ", $empty)." not found !!!";
	break;
	case "joinGroup":
		$post->user_id = $session->member_id;
		$required = ["amount","user_id"];
		foreach ($required as $key) { if(empty($post->{$key}))$empty[] = $key;}
		if(empty($empty)){
			// Coin
			$coin = $generic->getFromTable("coin", "id=1", 1, 1);
			$coin = reset($coin);

			//Active groups
			$active_teams = $generic->getFromTable("team", "status=0", 1, 0, ID_DESC);

			// buy team
			$buy_team = array_filter($active_teams, function ($t) use ($coin) {return $t->id == $coin->team_id;});
			$buy_team = reset($buy_team);

			// sell team
			$sell_team = array_filter($active_teams, function ($t) {return $t->type == "sell";});
			$sell_team = reset($sell_team);

			// contributions
			$contributions = $generic->getFromTable("contributions", "team_id={$buy_team->id}, team_id={$sell_team->id}", 1, 0);
			$contributions = array_group($contributions, "team_id");
			$buy_contributions  = !empty($contributions[$buy_team->id]) ? $contributions[$buy_team->id] : [];
			$sell_contributions = !empty($contributions[$sell_team->id]) ? $contributions[$sell_team->id] : [];

			$contributed_amount = array_sum(array_column($buy_contributions, "amount"));

			// user
			$user = $generic->getFromTable("users", "id={$post->user_id}", 1, 1);
			if (count($user)) {
				$user = reset($user);
				if (!empty($user->status)) {
					// user_>account
					$account = $generic->getFromTable("account", "user_id={$user->id}", 1, 1);
					$account = reset($account);

					// START THE ALGORITHM
					// If current buy team can still accept contributions
					if(($contributed_amount + $post->amount) <= $coin->price){
						// Available balance for the user
						if($post->amount <= $account->balance){
							// Update the buy team name to the first donor
							if(count($buy_contributions) == 0)$db->query("UPDATE team SET name='{$user->username}' WHERE id='{$buy_team->id}'");
							// Insert new contributor
							$db->query("INSERT INTO contributions SET user_id='{$user->id}', team_id='{$buy_team->id}', amount='{$post->amount}', charge='{$coin->charge}'");
							// Debit User's account
							$db->query("UPDATE account SET balance=balance-{$post->amount} WHERE user_id='{$user->id}'");
							// Log Debit Transaction
							$response = $generic->insert(object([
								"user_id" => $user->id,
								"tx_no" => uniqid($user->id),
								"amount" => $post->amount,
								"description" => "Purchase of &#8358;{$post->amount} worth of {$company->other} at the rate of &#8358;{$coin->price}.",
								"status" => 1,
							]), "debit");
							if($response->status){
								$response->messasge = "You have successfuly joined the group";
								// -------------------------------------------------------------------------------------------------
								// Check if the group is filled up
								if($coin->price <= ($contributed_amount + $post->amount)){
									// change buy group to sell
									$db->query("UPDATE team SET type='sell' WHERE id='{$buy_team->id}'");
									// recalculate new price of coin
									$coin->price = ceil($coin->price + get_percent_of($coin->rate, $coin->price));
									// Create a new group
									$db->query("INSERT INTO team SET name='{$user->username}', coin_price='{$coin->price}', rate='{$coin->rate}'");
									// update the coin price and holder team id
									$db->query("UPDATE coin SET price='{$coin->price}', team_id='{$db->insert_id}', date=now() WHERE id='1'");

									// Credit all sellers
									foreach ($sell_contributions as $key => $contribution) {
										// credit this user
										// What percentage of the coin price did this user contribute
										$percentage_contribution = get_percent($contribution->amount, $sell_team->coin_price);
										// What amount did his old contribution percent result to in the new coin price
										$roi = get_percent_of($percentage_contribution, $buy_team->coin_price);
										// What is the charge on his interest
										$charge = get_percent_of($contribution->charge, ($roi - $contribution->amount));

										$roi -= $charge;
										$db->query("UPDATE account SET balance=balance+{$roi} WHERE user_id='{$contribution->user_id}'");

										//  Log credit and charges transactions
										$transaction_number = uniqid($contribution->user_id);
										$response = $generic->insert(
											object([ "user_id" => $contribution->user_id, "tx_no" => $transaction_number, "amount" => $charge, "description" => "Service charge for {$company->other} sale ", "status" => 1]),
											"charges"
										);

										$response = $generic->insert(
											object(["user_id" => $contribution->user_id, "tx_no" => $transaction_number, "amount" => $roi, "description" => "Sale of &#8358;{$contribution->amount} worth of {$company->other} at a new rate of &#8358;{$roi}.", "status" => 1 ]),
											"credit"
										);
									}
									// Null existing Sell group
									$db->query("UPDATE team SET status='1' WHERE id='{$sell_team->id}'");
								}
							}
						}else $response->message = "Insufficient balance to join {$buy_team->name} group.";
					}else $response->message = "Group {$buy_team->name} is filled up !!!";
				} else $response->message = "You cannot perform this operation";
			} else $response->message = "Invalid User";
		} else $response->message = implode(", ", $empty)." not found !!!";
	break;
	case "withdrawRequest":
		// parameters for withdrawal
		// amount === minimum is 1000;
		// withdrawal can only be placed in weekdays
		// account to withdraw from == deposit or referral
			if(!empty($post->amount) && !empty($post->account) && is_numeric($post->amount)){
				// validate if the request is less than minimum
				// check if the has up to that amount in his account
				// check if he has added account number info
				// deduct from his account
				// update the transaction history
				$formated_amount = $fmn->format($post->amount);
				if($post->amount >= $TRANSACTION_CHARGES->min_withdrawal_amount){
					$userAccount = $generic->getFromTable("account","user_id={$session->member_id}");
					if(count($userAccount)){
						$userAccount  = reset($userAccount);
						if($post->amount <= $userAccount->{$post->account}){
							if(!empty($userAccount->bank_code)){
								$db->query("UPDATE `account` SET `{$post->account}`={$post->account}-{$post->amount} WHERE `user_id`='{$session->member_id}'");
								// withdrawal charges for bank transfers (when it's not bonus)
								if($post->account == "balance"){
									$post->amount -= $TRANSACTION_CHARGES->withdrawal_charges;
									// Insert a transaction log for the charges
									$generic->insert(
										object(["user_id" => $session->member_id, "tx_no" => uniqid($session->member_id), "amount" => $TRANSACTION_CHARGES->withdrawal_charges, "description" => "Service charge of {$appCurrency}{$TRANSACTION_CHARGES->deposit_charges} for Withdrawal", "status" => 1]),
										"charges"
									);
								}
								$banks = $paramControl->load_sources("banks");
								// Insert a transaction log for the withdrawal
								$response = $generic->insert(
									object(["user_id" => $session->member_id, "tx_no" => uniqid($session->member_id), "amount" => $post->amount, "description" => "Withdrawal of {$appCurrency}{$formated_amount}", "status" => 0,
									"paid_into"=>"{$banks->{$userAccount->bank_code}}--{$userAccount->account_no}--{$userAccount->account_name}--{$userAccount->bank_code}"]),
									"withdrawals"
								);
							}else $response->message = "Please enter your bank account detail";
						}else $response->message = "Insufficient Balance To Complete this Request";
					}else $response->message = "User Account cannot be resolved";
				}else $response->message = "Minimum withdrawal amount is {$appCurrency}".$fmn->format($TRANSACTION_CHARGES->min_withdrawal_amount);
			}else $response->message = "Invalid Withdrawal Request Data, Make Sure You have Added A Bank Information";
	break;
	case 'processBank':
		//Verify Bank account
		// to add a bank detail
		// send in bank_code,account_no,account_name,type [addBank, verifyBank]
		$post->user_id = $session->member_id;
		$required = ["bank_code", "account_no", "type", "user_id"];
		foreach ($required as $key) { if(empty($post->{$key}))$empty[] = $key;}
		if(empty($empty)){
			if($post->type == "addBank"){
				$message = handleTrail("addBank_trial");
				if(!empty($session->generateToken) && !empty($post->pin) && $post->pin == $session->generateToken && $_SESSION['addBank_trial'] > 1){
					unset($_SESSION["generateToken"]);
					$db->query("UPDATE `account` SET `account_no`='$post->account_no',`account_name`='$post->account_name',`bank_code`='$post->bank_code' WHERE `user_id`='{$session->member_id}'") or die($db->error);
					$response->message = "Bank Details Added Successfully";
					$response->status = 1;
					if($response->status && !empty($_SESSION["addBank_trial"])){ unset($_SESSION["addBank_trial"]); }
				}else $response->message = $message;
			}else if($post->type == "verifyBank"){
				$paystack = object($generic::$paystack);
				$paystack = $paystack->{$paystack->DEFAULT};
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_URL => "https://api.paystack.co/bank/resolve?account_number={$post->account_no}&bank_code={$post->bank_code}",
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_HTTPHEADER => [
						"accept: application/json",
						"authorization: Bearer {$paystack}",
						"cache-control: no-cache"
					],
				));
				$data  = curl_exec($curl);
				$response = isJson($data);
			}else $response->message = "Invalid Request Type";
		}else $response->message = implode(", ", $empty)." not found !!!";
	break;
	default: return(false);
}
