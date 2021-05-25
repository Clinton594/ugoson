<?php
$appCurrency = "₦";
switch($post->case){
	case 'verifyBank': //Verify Bank account
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
	break;
	case "resendPin"://Resend Pin
		require_once("{$uri->backend}controllers/Messenger.php");
		$messenger= new Messenger($generic);
		$messenger->pinAction = empty($post->pinAction) ? "login" : $post->pinAction;
		$k = empty($k) ? $session->member_id : $k;
		$user = $generic->getFromTable("users", "id=$k", 1, 0)[0];
		$response = sendCode($messenger, $user);
	break;
	case "exChange":
		require_once("{$uri->backend}controllers/ForExchange.php");
		$post->local 		= strtoupper($post->local);
		$post->currency = strtoupper($post->currency);
		$post->amount = explode("-", $post->range);
		$exchange = new ForExchange;
		$usdRate  = $exchange->getRate($post->local);
		$btcRate  = $exchange->toBTC();
		$build = [$post->local=>$usdRate, "USD"=>1, "BTC"=>$btcRate];
		foreach ($build as $key => $value) {
			$response[$key] = [round($post->amount[0] * $value, 8), round($post->amount[1] * $value, 8), $value];
		}
		$response["rate"] = $usdRate;
		// Send token for withdrawal
		if(isset($post->action)){
			$responsee = setActionForCode($post);
			$response  = (object)array_merge((array)$responsee, (array)$response);
		}
	break;
	// Pay for store | create a new store | upgrade a store level
	case "fundAccount":
	 // Payment Channel description
		$channels = ["paystack"=>"{$company->name} Paystack Account"];
		// Merge card data with the post for web request
		if(!empty($session->paystack)){
			$post = (object)array_merge((array)$post, (array)$session->paystack);
		}
		if(!empty($post->reference)){
			$curl = curl_init();
			$apikey = $generic::$paystack[$generic::$paystack["DEFAULT"]];
			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($post->reference),
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HTTPHEADER => [
					"accept: application/json",
					"authorization: Bearer {$apikey}",
					"cache-control: no-cache"
					],
				));
			$response  = curl_exec($curl);
			$PResponse = $response = isJson($response);
			if(!empty($PResponse->data->status)){

				if($response->status){
					// Save Transactions
					$account = $generic->getFromTable("account", "user_id={$post->user_id}", 1, 1);
					$user		 = $generic->getFromTable("users", "id={$post->user_id}", 1, 1);
					$account = reset($account);
					$user = reset($user);
					$transaction = (object)[
						"user_id" => $post->user_id,
						"tx_no" => $post->reference,
						"amount" => $post->actual_amount,
						"status" => 1,
						"user_id" => $post->user_id,
						// "account" => json_encode($account),
						"description" => "Account funding with N{$post->actual_amount}"
					];
					$response = $generic->insert($transaction, "deposits");
				}
				if($response->status){
					// update wallet balance
					$update = $db->query("UPDATE account SET balance=balance+{$post->actual_amount} WHERE user_id='{$post->user_id}'");
					if($update && !empty($response->status)){
						unset($_SESSION["paystack"]);
						header("Location: {$uri->site}app");
						die();
					}else{
						$response->message = $db->error;
					}
				}
			}else $response->message = "Couldn't verify the transaction";
		}else $response->message = "No payment channel or reference number found";
	break;
	case "cronUpdate":
		$required = ["user_id"];
		foreach ($required as $key) {if(empty($post->{$key}))$empty[] = $key;}
		if(empty($empty)){
			$coin = $generic->getFromTable("coin", "id=1", 1, 1);
			$coin = reset($coin);

			$account = $generic->getFromTable("account", "user_id={$post->user_id}", 1, 1);
			$account = reset($account);

			//Active teams
			$active_teams = $generic->getFromTable("team", "status=0", 1, 2, ID_DESC);

			// buy team
			$buy_team = array_filter($active_teams, function($t) use ($coin){return $t->id == $coin->team_id;});
			$buy_team = reset($buy_team);

			// sel team
			$sell_team = array_filter($active_teams, function($t){return $t->type == "sell";});
			$sell_team = reset($sell_team);

			// All Active buy and sell contributions
			$contributions  = $generic->getFromTable("contributions", "team_id={$buy_team->id}, team_id={$sell_team->id}", 1, 0);
			$grouped_contributions = array_group($contributions, "team_id");

			// All buying contributions
			$contributionx = !empty($grouped_contributions[$buy_team->id]) ? $grouped_contributions[$buy_team->id] : [];

			// This User contributions
			$user_contrib  = array_filter($contributions, function($cont) use ($post){return ($cont->user_id == $post->user_id);});
			$user_contrib  = array_group($user_contrib, "team_id");

			// This User's grouped contributions
			$user_buy      = !empty($user_contrib[$buy_team->id]) ? $user_contrib[$buy_team->id] : [];
			$user_sell     = !empty($user_contrib[$sell_team->id]) ? $user_contrib[$sell_team->id] : [];

			$buy_amount = array_sum(array_column($user_buy, "amount"));

			// Sell - calculation
			$valuated_coin_sell_price = $sell_team->coin_price + get_percent_of($sell_team->rate, $sell_team->coin_price);
			$real_sell_amount = $charged_sell_amount = $sell_percent = $charges = 0;
			foreach($user_sell as $cont){
				$real_sell_amount += $cont->amount;
				$charges += get_percent_of($cont->charge, $cont->amount);
			}
			// Sell valuation
			$percentage_contribution = get_percent($real_sell_amount, $sell_team->coin_price);
			$charged_sell_amount = get_percent_of($percentage_contribution, $valuated_coin_sell_price) - $charges;

			$response = [
				"group_name" => $buy_team->name,
				"coin_price" => $coin->price,
				"contributed_amount" => array_sum(array_column($contributionx, "amount")),
				"group_members" => count($contributionx),
				"rate" => $coin->rate,
				"charge" => $coin->charge,
				"current_balance" => round($account->balance, 2),
				"referral_balance" => round($account->bonus, 2),
				"selling" => ["amount"=>round($charged_sell_amount, 2), "percent"=>round(get_percent($real_sell_amount, $sell_team->coin_price), 2)],
				"buying" => ["amount"=>round($buy_amount, 2), "percent"=>round(get_percent($buy_amount, $coin->price), 2)]
			];
		} else $response->message = implode(", ", $empty)." not found !!!";
	break;
	case "joinGroup":
		$required = ["user_id", "amount", "pin"];
		foreach ($required as $key) { if(empty($post->{$key}))$empty[] = $key;}
		if(empty($empty)){
			// Coin
			$coin = $generic->getFromTable("coin", "id=1", 1, 1);
			$coin = reset($coin);

			//Active groups
			$active_teams = $generic->getFromTable("team", "status=0", 1, 0, ID_DESC);

			// buy team
			$buy_team = array_filter($active_teams, function($t) use ($coin){return $t->id == $coin->team_id;});
			$buy_team = reset($buy_team);

			// sel team
			$sell_team = array_filter($active_teams, function($t){return $t->type == "sell";});
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

					// Authenticate user pin
					if($post->pin == $user->pin){
						// group is still available for contribution
						if(($contributed_amount + $post->amount) <= $coin->price){
							// Available balance for the user
							if($post->amount <= $account->balance){
								// Insert new contributor
								$db->query("INSERT INTO contributions SET user_id='{$user->id}', team_id='{$buy_team->id}', amount='{$post->amount}', charge='{$coin->charge}'");
								// Debit User's account
								$db->query("UPDATE account SET balance=balance-{$post->amount} WHERE user_id='{$user->id}'");
								// Log Debit Transaction
								$response = $generic->insert(object([
									"user_id" => $user->id,
									"tx_no" => uniqid($user->id),
									"amount" => $post->amount,
									"description" => "Purchase of {$appCurrency}{$post->amount} worth of {$company->name} at the rate of {$appCurrency}{$coin->price}.",
									"status" => 1,
								]), "debit");
								if($response->status){
									$response->messasge = "You have successfuly joined the group";
									// -------------------------------------------------------------------------------------------------------------------------------------------------
									// Check if the group is filled up to create new group
									if($coin->price <= ($contributed_amount + $post->amount)){
										// change buy group to sell
										$db->query("UPDATE team SET type='sell' WHERE id='{$buy_team->id}'");
										// Create a new group
										$name = random(6);
										$db->query("INSERT INTO team SET name='{$name}', coin_price='{$coin->price}', rate='{$coin->rate}'");
										// recalculate new price of coin
										$coin->price = ceil($coin->price + get_percent_of($coin->rate, $coin->price));
										// update the coin price and holder team id
										$db->query("UPDATE coin SET price='{$coin->price}', team_id='{$db->insert_id}', date=now() WHERE id='1'");

										// Credit all sellers
										foreach ($sell_contributions as $key => $contribution) {
											// credit this user
											$percentage_contribution = get_percent($contribution->amount, $sell_team->coin_price);
											$roi = get_percent_of($percentage_contribution, $coin->price);
											$charge = get_percent_of($contribution->charge, $roi);
											$new_roi = $roi - $charge;
											$db->query("UPDATE account SET balance=balance+{$new_roi} WHERE user_id='{$contribution->user_id}'");

											//  Log credit and charges transactions
											$transaction_number = uniqid($contribution->user_id);
											$response = $generic->insert(object([
												"user_id" => $contribution->user_id,
												"tx_no" => uniqid($contribution->user_id),
												"amount" => $charge,
												"description" => "Service charge for {$company->name} sale ({$transaction_number})",
												"status" => 1,
											]), "charges");

											$response = $generic->insert(object([
												"user_id" => $contribution->user_id,
												"tx_no" => $transaction_number,
												"amount" => $new_roi,
												"description" => "Sale of {$appCurrency}{$contribution->amount} worth of {$company->name} of {$appCurrency}{$sell_team->coin_price} at a new rate of {$appCurrency}{$coin->price}.",
												"status" => 1,
											]), "credit");
										}
										// Null existing Sell group
										$db->query("UPDATE team SET status='1' WHERE id='{$sell_team->id}'");
									}
								}
							}else $response->message = "Insufficient balance to join {$buy_team->name} group.";
						}else $response->message = "Group {$buy_team->name} is filled up !!!";
					} else {
						// Notify user of available trials
						$_SESSION["trial"] = empty($_SESSION["trial"]) ? 2 : $_SESSION["trial"]-1;
						$response->message = "Incorrect Pin. You have {$_SESSION["trial"]} trial left.";
						if($_SESSION["trial"]==0){
							$db->query("UPDATE users SET status='0' WHERE id='{$user->id}'");
							$response->message = "Your account has been suspended, contact support";
						}
					}
				} else $response->message = "You cannot perform this operation";
			} else $response->message = "Invalid User";
		} else $response->message = implode(", ", $empty)." not found !!!";
	break;

	default: return(false);
}
?>




























































<?php
require_once("../controllers/Messenger.php");
$appCurrency = $generic::$NAIRA_ENTITY;
switch ($post->case) {
    case "confirm-email"://Resend Pin
        if (!empty($session->{$post->pinAction})) {
            if ($session->{$post->pinAction} == $post->code) {
                unset($_SESSION[$post->pinAction]);
                $db->query("UPDATE users set status='1' WHERE id='{$session->member_id}'");
                $response->status = 1;
            } else {
                $response->message = "Incorrect token";
            }
        } else {
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
        foreach ($required as $key) {
            if (empty($post->{$key})) {
                $empty[] = $key;
            }
        }
        if (empty($empty)) {
            $coin = $generic->getFromTable("coin", "id=1");
            $coin = reset($coin);

            $account = $generic->getFromTable("account", "user_id={$post->user_id}", 1, 1);
            $account = reset($account);

            //Active teams
            $active_teams = $generic->getFromTable("team", "status=0", 1, 2, ID_DESC);

            // buy team
            $buy_team = array_filter($active_teams, function ($t) use ($coin) {
                return $t->id == $coin->team_id;
            });
            $buy_team = reset($buy_team);

            // sel team
            $sell_team = array_filter($active_teams, function ($t) {
                return $t->type == "sell";
            });
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

            // Sell - calculation
            $valuated_coin_sell_price = $sell_team->coin_price + get_percent_of($sell_team->rate, $sell_team->coin_price);
            $real_sell_amount = $charged_sell_amount = $sell_percent = $charges = 0;
            foreach ($user_sell as $cont) {
                $real_sell_amount += $cont->amount;
                $charges += get_percent_of($cont->charge, $cont->amount);
            }
            // Sell valuation
            $percentage_contribution = get_percent($real_sell_amount, $sell_team->coin_price);
            $charged_sell_amount = get_percent_of($percentage_contribution, $valuated_coin_sell_price) - $charges;

            $response->status = 1;
            $response->data = [
                "group_name" => $buy_team->name,
                "coin_price" => intval($coin->price),
                "contributed_amount" => array_sum(array_column($contributionx, "amount")),
                "group_members" => count($contributionx),
                "rate" => intval($coin->rate),
                "max" => $generic::$MAX_BUYCOIN_AMOUNT,
                "appCurrency" => $appCurrency,
                "charge" => intval($coin->charge),
                "current_balance" => round($account->balance, 2),
                "referral_balance" => round($account->bonus, 2),
                "selling" => ["amount"=>round($charged_sell_amount, 2), "percent"=>round(get_percent($real_sell_amount, $sell_team->coin_price), 2)],
                "buying" => ["amount"=>round($buy_amount, 2), "percent"=>round(get_percent($buy_amount, $coin->price), 2)]
            ];
        } else {
            $response->message = implode(", ", $empty)." not found !!!";
        }
    break;
    case "account-number":
         $response->status = 1;
         $response->data = $paramControl->load_sources("account-number");
    break;
    case "fundAccount":
        // for a token funding the parameters are
        // type = "token"
        // token = the token to fund with

        // for the transfer funding parameters
        // amount = the amount to fund , minimum 1000
        // proof = "url of the reciept
        if (!empty($session->member_id)) {
            if (!empty($post->type) && ($post->type == "token")) {
                // validate if the token is valid
                // reset the token
                // update the customer account with the amount
                // insert into the user transaction table for the user
                $token = $generic->getFromTable("cash_tokens", "status=0,token={$post->token}", 1, 1, ID_DESC);
                $token = reset($token);
                if (!empty($token)) {
                    $token->amount -= $generic::$DEPOSIT_CHARGES;
                    $formated_token_amount = number_format($token->amount, 2, ".", ",");
                    $db->query("UPDATE `cash_tokens` SET `status`='1',`user_id`={$session->member_id} WHERE id='{$token->id}'");
                    $db->query("UPDATE account SET `balance`=balance+{$token->amount} WHERE `user_id`='{$session->member_id}'");
                    $db->query("INSERT INTO  `transaction`(`user_id`,`tx_type`,`amount`,`description`,`status`)  VALUES('$session->member_id','deposit','$token->amount','Deposit of {$generic::$NAIRA_ENTITY}$formated_token_amount was successfull ','1')");
                    // check for referral
                    $referral = $generic->getFromTable("referral", "status=0,referred_id={$session->member_id}", 1, 1, ID_DESC);
                    $referral = reset($referral);
                    if (!empty($referral)) {
                        // reset the referral
                        // update the parent referral bonus amount
                        // add this to transaction history for the person that referred him
                        $formated_referral_amount = number_format($referral->amount, 2, ".", ",");
                        $db->query("UPDATE `referral` SET `status`='1' WHERE `referred_id`='{$session->member_id}'");
                        $db->query("UPDATE account SET `bonus`=bonus+{$referral->amount} WHERE `user_id`='{$referral->referral_id}'");
                        $db->query("INSERT INTO  `transaction`(`user_id`,`tx_type`,`amount`,`description`,`status`)  VALUES('$referral->referral_id','{$generic::$TRANSACTION_TYPE[0]}','$referral->amount','Referral Bonus of {$generic::$NAIRA_ENTITY}$formated_referral_amount was successfull added to your account ','1')");
                    }
                    $response->message = "Account Deposit was successfull";
                    $response->status = 1;
                } else {
                    $response->message = "Invalid Payment Token";
                    $response->status = 0;
                }
            } elseif (!empty($post->type) && ($post->type == "bank")) {
                if (!empty($post->amount) && !empty($post->evidence) && is_numeric($post->amount)) {
                    if ($post->amount >= $generic::$MINIMUM_DEPOSIT_AMOUNT) {
                        $formated_transfer_amount = number_format($post->amount, 2, ".", ",");
                        $bank_account = (array) $paramControl->load_sources("account-number");
                        $bank_account = implode(" ", $bank_account);
                        $db->query("INSERT INTO  `transaction`(`user_id`,`evidence`,`tx_type`,`amount`,`description`,`status`,`paid_into`)  VALUES('$session->member_id','$post->evidence','{$generic::$TRANSACTION_TYPE[0]}','$post->amount','Deposit Request of {$generic::$NAIRA_ENTITY}$formated_transfer_amount was successfully created ','2','$bank_account')");
                        $response->message = "Account Deposit Request Successfully Created";
                        $response->status = 1;
                    } else {
                        $response->message = "Minimum deposit amount is {$generic::$NAIRA_ENTITY}".number_format($generic::$MINIMUM_DEPOSIT_AMOUNT, 0, ".", ",");
                        $response->status = 0;
                    }
                } else {
                    $response->message = "Invalid Payment Data";
                    $response->status = 0;
                }
            } else {
                $response->message = "Unauthorized Request";
                $response->status = 0;
            }
        } else {
            $response->message = "Unauthorized User";
            $response->status = 0;
        }
     break;
    case "tokenCrud":
        // to generate token --- parameteres required
        // type == "generate
        // pin == four digit transaction pin
        // amount == min 1000

        // to delete a token --- parameteres
        // type == "delete"
      // pin == "four digit transaction pin;
        // token_id == the id of the token to be deleted

        if (!empty($session->member_id)) {
            if (!empty($post->type) && $post->type == "generate") {
                if (!empty($post->pin) && ($post->pin == $session->member_pin)) {
                    if (!empty($post->amount) && is_numeric($post->amount)) {
                        if ($post->amount >= $generic::$MINIMUM_DEPOSIT_AMOUNT) {
                            //  get this users account information
                            $userAccount = $generic->getFromTable("account", "user_id={$session->member_id}", 1, 1, ID_DESC);
                            $userAccount =  reset($userAccount);
                            if ($post->amount <= $userAccount->balance) {
                                // update the users account data
                                // generate the users token
                                // add to the transaction table
                                $token = strtoupper(getToken(8));
                                $formated_amount = number_format($post->amount, 0, ".", ",");
                                $db->query("UPDATE `account` SET `balance`=balance-{$post->amount} WHERE `user_id`='{$session->member_id}'");
                                $db->query("INSERT INTO `cash_tokens`(`creator_id`,`user_id`,`token`,`amount`,`status`) VALUES('$session->member_id','0','$token','$post->amount','0')");
                                $db->query("INSERT INTO  `transaction`(`user_id`,`tx_type`,`amount`,`description`,`status`)  VALUES('$session->member_id','{$generic::$TRANSACTION_TYPE[1]}','$post->amount','Token generation of {$generic::$NAIRA_ENTITY}$formated_amount was successfull','1')");
                                $response->message = "{$generic::$NAIRA_ENTITY}$formated_amount Token successfully generated";
                                $response->status = 1;
                            } else {
                                $response->message = "Insufficient balance to generate this token";
                                $response->status = 0;
                            }
                        } else {
                            $response->message = "Minimum amount required to generate a token is  {$generic::$NAIRA_ENTITY}".number_format($generic::$MINIMUM_DEPOSIT_AMOUNT, 0, ".", ",");
                            $response->status = 0;
                        }
                    } else {
                        $response->message = "Unsupported Token Amount";
                        $response->status = 0;
                    }
                } else {
                    $response->message = "Invalid Transaction Pin";
                    $response->status = 0;
                }
            } elseif (!empty($post->type) && $post->type == "delete") {
                if (!empty($post->pin)  && ($post->pin == $session->member_pin)) {
                    if (!empty($post->token_id)) {
                        $token = $generic->getFromTable("cash_tokens", "creator_id={$session->member_id},status=0,id={$post->token_id}", 1, 1, ID_DESC);
                        $token = reset($token);
                        if (!empty($token)) {
                            $result = $db->query("DELETE FROM `cash_tokens` WHERE `creator_id`='{$session->member_id}' AND `status`='0' AND `id`='{$post->token_id}'");
                            if ($result) {
                                // update account
                                // update transaction history
                                $formated_amount = number_format($token->amount);
                                $db->query("UPDATE `account` SET `balance`=balance+{$token->amount} WHERE `user_id`='{$session->member_id}'");
                                $db->query("INSERT INTO  `transaction`(`user_id`,`tx_type`,`amount`,`description`,`status`)  VALUES('$session->member_id','{$generic::$TRANSACTION_TYPE[0]}','$token->amount','{$generic::$NAIRA_ENTITY}$formated_amount  {$generic::$TRANSACTION_TYPE[0]} was successfull after token was cancled','1')");
                                $response->message = "Token successfully deleted";
                                $response->status = 1;
                            } else {
                                $response->message = "Token failed to delete, please try again";
                                $response->status = 0;
                            }
                        } else {
                            $response->message = "Token doesn't exist";
                            $response->status = 0;
                        }
                    } else {
                        $response->message = "Invalid Token Data";
                        $response->status = 0;
                    }
                } else {
                    $response->message = "Invalid Transaction PIN";
                    $response->status = 0;
                }
            } else {
                $response->message = "Invalid Token Data";
                $response->status = 0;
            }
        } else {
            $response->message = "Unauthorized User";
            $response->status = 0;
        }
     break;
     case "joinGroup":
         $required = ["amount", "pin"];
         $post->user_id = $session->member_id;
         foreach ($required as $key) {
             if (empty($post->{$key})) {
                 $empty[] = $key;
             }
         }
         if (empty($empty)) {
             // Coin
             $coin = $generic->getFromTable("coin", "id=1", 1, 1);
             $coin = reset($coin);

             //Active groups
             $active_teams = $generic->getFromTable("team", "status=0", 1, 0, ID_DESC);

             // buy team
             $buy_team = array_filter($active_teams, function ($t) use ($coin) {
                 return $t->id == $coin->team_id;
             });
             $buy_team = reset($buy_team);

             // sel team
             $sell_team = array_filter($active_teams, function ($t) {
                 return $t->type == "sell";
             });
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

                     // Authenticate user pin
                     if ($post->pin == $user->pin) {
                         // group is still available for contribution
                         if (($contributed_amount + $post->amount) <= $coin->price) {
                             // Available balance for the user
                             if ($post->amount <= $account->balance) {
                                 // Insert new contributor
                                 $db->query("INSERT INTO contributions SET user_id='{$user->id}', team_id='{$buy_team->id}', amount='{$post->amount}', charge='{$coin->charge}'");
                                 // Debit User's account
                                 $db->query("UPDATE account SET balance=balance-{$post->amount} WHERE user_id='{$user->id}'");
                                 // Log Debit Transaction
                                 $response = $generic->insert(object([
                                     "user_id" => $user->id,
                                     "tx_no" => uniqid($user->id),
                                     "amount" => $post->amount,
                                     "description" => "Purchase of {$appCurrency}{$post->amount} worth of {$company->name} at the rate of {$appCurrency}{$coin->price}.",
                                     "status" => 1,
                                 ]), "debit");
                                 if ($response->status) {
                                     $response->messasge = "You have successfuly joined the group";
                                     // -------------------------------------------------------------------------------------------------------------------------------------------------
                                     // Check if the group is filled up to create new group
                                     if ($coin->price <= ($contributed_amount + $post->amount)) {
                                         // change buy group to sell
                                         $db->query("UPDATE team SET type='sell' WHERE id='{$buy_team->id}'");
                                         // Create a new group
                                         $name = random(6);
                                         $db->query("INSERT INTO team SET name='{$name}', coin_price='{$coin->price}', rate='{$coin->rate}'");
                                         // recalculate new price of coin
                                         $coin->price = ceil($coin->price + get_percent_of($coin->rate, $coin->price));
                                         // update the coin price and holder team id
                                         $db->query("UPDATE coin SET price='{$coin->price}', team_id='{$db->insert_id}', date=now() WHERE id='1'");

                                         // Credit all sellers
                                         foreach ($sell_contributions as $key => $contribution) {
                                             // credit this user
                                             $percentage_contribution = get_percent($contribution->amount, $sell_team->coin_price);
                                             $roi = get_percent_of($percentage_contribution, $coin->price);
                                             $charge = get_percent_of($contribution->charge, $roi);
                                             $new_roi = $roi - $charge;
                                             $db->query("UPDATE account SET balance=balance+{$new_roi} WHERE user_id='{$contribution->user_id}'");

                                             //  Log credit and charges transactions
                                             $transaction_number = uniqid($contribution->user_id);
                                             $response = $generic->insert(object([
                                                 "user_id" => $contribution->user_id,
                                                 "tx_no" => uniqid($contribution->user_id),
                                                 "amount" => $charge,
                                                 "description" => "Service charge for {$company->name} sale ({$transaction_number})",
                                                 "status" => 1,
                                             ]), "charges");

                                             $response = $generic->insert(object([
                                                 "user_id" => $contribution->user_id,
                                                 "tx_no" => $transaction_number,
                                                 "amount" => $new_roi,
                                                 "description" => "Sale of {$appCurrency}{$contribution->amount} worth of {$company->name} of {$appCurrency}{$sell_team->coin_price} at a new rate of {$appCurrency}{$coin->price}.",
                                                 "status" => 1,
                                             ]), "credit");
                                         }
                                         // Null existing Sell group
                                         $db->query("UPDATE team SET status='1' WHERE id='{$sell_team->id}'");
                                     }
                                 }
                             } else {
                                 $response->message = "Insufficient balance to join {$buy_team->name} group.";
                             }
                         } else {
                             $response->message = "Group {$buy_team->name} is filled up !!!";
                         }
                     } else {
                         // Notify user of available trials
                         $_SESSION["trial"] = empty($_SESSION["trial"]) ? 2 : $_SESSION["trial"]-1;
                         $response->message = "Incorrect Pin. You have {$_SESSION["trial"]} trial left.";
                         if ($_SESSION["trial"]==0) {
                             $db->query("UPDATE users SET status='0' WHERE id='{$user->id}'");
                             $response->message = "Your account has been suspended, contact support";
                         }
                     }
                 } else {
                     $response->message = "You cannot perform this operation";
                 }
             } else {
                 $response->message = "Invalid User";
             }
         } else {
             $response->message = implode(", ", $empty)." not found !!!";
         }
     break;

    default: return(false);
}

