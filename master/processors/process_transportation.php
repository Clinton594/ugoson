<?php
// Trans_types ["ORD"=>"Sales from the website", "PCH"=>"Purchase from vendor", "RCP"=>"Sales from backend"]
require_once "../controllers/Messenger.php";
$json					=	array();
$params 			= $paramControl->get_params();
$param 				= $params->{$post->pageType};
// Check if the transaction type is from paystack, this means it's from the front-end
$front_end_booking		= (!empty($post->posted_from)) ? 1 : 0;
// Start Journey, any failed checkpoint switches it to zero
$response->status = 1;
$post = (object)array_merge((array)$post, (array)$param->form_values);
// Check for callbefore functions
if(isset($param->pre_submit_function)){
	$post = call_user_func_array($param->pre_submit_function, [$post]);
	$post = gettype($post) === 'array' ? (object)$post : $post;
}
if(empty($post->error)){
	// Initialize Logged in user authentication class if not Ecommerce transaction
	if($front_end_booking === 0){
		$auth			= new Auth($session->user_id, "admin_signin");
		$user			= $auth->user();
		$created_by = $session->user_id;
		$updated_by = $session->user_id;
		$activity = "admin";
		if(!empty($post->{$param->primary_key."1"})):
			$post->submitType = "update";
		else:
			$post->submitType = "insert";
		endif;
	}else{
		$user			= new stdClass;
		$post->cid = 0;
		$user->name_col = $post->customer1;
		$user->accesslevel 	= 4;
		$created_by = $post->cid;
		$updated_by = $post->cid;
		$activity = "other";
		$post->submitType = "insert";
	}
	// Only users with modification accesslevel can proceed with transactions
	if(($user->accesslevel >= 2 && $post->submitType === "update") || ($user->accesslevel >= 1 && $post->submitType === "insert")){
		// Get the transaction number
		if(empty($post->trans_no))	{
			$post->trans_no = time().rand(10, 99);
		}

		// Get the transaction reference id
		if(empty($post->ref)){
			$post->ref = $post->trans_type.time();
		}

		// Begin Transaction
		// Check for the sign (- means sales, + means purchase)
		if(!empty($param->form_values->sign) && $response->status){
			//First of all start a transaction;
			$db->autocommit(FALSE);
			$db->begin_transaction();
			// Define the column fields in the transaction widget
			$post->required_fields = (object)["tid","it_id","quantity","email","customer","unit", "telephone","gender","rate","amount","address","customerpo","cref","status"];
			// Tag an account to the transaction
			if($response->status){
				$post->default_account=89000;
				if(!isset($post->default_account) && empty($post->account)){
					$response->status	 = 0;
					$response->message = "You must tag an account to this transaction or add default_account to param 'form_values' field";
				}else if(isset($post->default_account)){
					// getting default account for the transaction
					$select=["typeid as account_id", "account_name", "account_id as account", "account_type"];
					$account = $generic->getFromTable("account_chart", "account_id={$post->default_account}", 1, 1, null, false, $select)[0];
					if(!empty($account)){
						foreach ($account as $account_key => $account_value) {
							$post->{$account_key} = $account_value;
						}
					}
				}
			}
				// Recalculate the values from the front end just to be sure
			if($response->status){
				$count = $post->amount = 0;
				for ($i=1; $i <= $post->num_rows; $i++) {
					// if passenger n exists
					if(isset($post->{"tid".$i})){
						$post->{"rate".$i} = empty($post->{"rate".$i}) ? $post->rate: $post->{"rate".$i};
						$post->{"quantity".$i} = empty($post->{"quantity".$i}) ? $post->quantity: $post->{"quantity".$i};
						$post->{"amount".$i} = empty($post->{"amount".$i}) ? $post->amount: $post->{"amount".$i};
						$post->{"amount".$i} = $post->{"rate".$i} * $post->{"quantity".$i};
						$post->amount += $post->{"amount".$i};
						if(!empty($post->discount)){
							$post->{"discount".$i} = $post->discount;
							$post->{"amount".$i} = intval($post->{"amount".$i}) - intval($post->{"discount".$i});
						}
						$count++;
					}
				}
			}
				// Build the summation row
			if($response->status){
				$table_fields = $generic->getTableFields($param->table);
				$trip__row 		=  $bookn_row = new stdClass;
				// primary key
				$trip__row->tid = empty($post->tid) ? time().rand(1000, 9999) : $post->tid;
				$trip__row->date_due		= empty($post->date_due) ? "" : $post->date_due;
				$trip__row->sub = 0;
				$trip__row->quantity 		= $post->quantity;
				$trip__row->gl_quantity = ($post->quantity * $param->form_values->sign);
				$trip__row->net_due			= $post->amount;
				$trip__row->amount 			= $post->amount;
				$trip__row->gl_amount 	= ($post->amount * -$param->form_values->sign);
				$post->glaccount_id			=	$post->account_id;
				$post->glaccount_name		= $post->account_name;
				$post->glaccount_type		= $post->account_type;
				$post->glaccount				= $post->account;
				foreach($table_fields as $field) {
					if(isset($post->{$field})) {
						if(!isset($trip__row->{$field}))$trip__row->{$field} = $post->{$field};
						if(!isset($bookn_row->{$field}))$bookn_row->{$field} = $post->{$field};
					}
				}
				// Unset some values from the $trip__row when its a new trip request. These fields are for the drivers
				$remove_keys_from_new_trip = ["customer","cid","telephone","address","cref", "email", "customerpo", "gender", "ref"];
				$insert_new_trip = $trip__row;
				$insert_new_trip->status = 1; //Auto Approval for new trips
				if($front_end_booking === 1 || empty($post->tid)){
					$insert_new_trip = array_diff_key((array)$insert_new_trip, array_flip($remove_keys_from_new_trip));
				}
				// Check if this trip has already been initialized or not to exract some of it's unique values
				if(!empty($post->tid)){
					$existing_trip = $generic->getFromTable($param->table, "tid={$post->tid}", 1, 1);
					if(count($existing_trip)){
						$existing_trip = (object)array_filter((array)$existing_trip[0]);
						$trip__row->trans_no = $existing_trip->trans_no;
						$trip__row->tid = $existing_trip->tid;
						$trip__row->date_due = $existing_trip->date_due;
					}
				}
			}

			// Build the seat rows for this trip and save the customer
			if($response->status){
				$rows 		= array();
				$row_count= 1;

				for ($i=1; $i <= $post->num_rows; $i++) {

					if(isset($post->{"tid".$i})){
						// new row found
						// tid is set but usually empty for new transactions. It can't be found in {$required_fields} because it is
						//set by generic_transactions page and not in the param.
						$seat_row = new stdClass;
						$seat_row = (object)array_merge((array)$seat_row, (array)$bookn_row);

						// Dynamically populate the row with already existing values
						foreach ($post->required_fields as $key => $required_field) {
							if(isset($post->{$required_field.$i})){
								$seat_row->{$required_field} 	= $post->{$required_field.$i};
							}else if(isset($post->{$required_field})){
								$seat_row->{$required_field} 	= $post->{$required_field};
							}
						}
						// Continue assigning values
						$seat_row->tid 					= empty($post->{"tid".$i}) ? time().rand(1000, 9999): $post->{"tid".$i};
						$seat_row->sub 					= $row_count;
						$seat_row->parent 			= $trip__row->tid;
						$seat_row->gl_quantity 	= ($post->{"quantity".$i} * $post->sign);
						$seat_row->net_due			= $post->{"amount".$i};
						$seat_row->gl_amount 		= ($post->{"amount".$i} * $post->sign);
						if($front_end_booking === 1)$seat_row->status = 0;
						// Save current customer if not existing
						// Ecommerce guys must have been registered and logged in , so we skip auto creation
						if(!empty($seat_row->customer)){
							$find_customer = $generic->getFromTable("users", "phone={$seat_row->telephone}", 1, 1);
							if(empty($find_customer)){
								$names = array_range(explode(" ", $seat_row->customer), 2);
								$first_name = empty($names[0])? "" : $names[0];
								$last_name = empty($names[1])? "" : $names[1];
								$query="INSERT INTO users (first_name, last_name, address, other, username, password, phone, email, type, gender) VALUES('{$first_name}' ,'{$last_name}','{$seat_row->address}','{$seat_row->customerpo}','**********' ,'**********' ,'{$seat_row->telephone}','{$seat_row->email}','4', '{$seat_row->gender}')";
								if(!$db->query($query)){
									$response->status	 = 0;
									$response->message = "{$db->error} == >({$query})";
								}
								$seat_row->cid = $db->insert_id;
								if(empty($updated_by)){
									$created_by = $updated_by = $seat_row->cid;
								}
							}else{
								$seat_row->cid = $find_customer[0]->id;
								if(empty($updated_by)){
									$created_by = $updated_by = $seat_row->cid;
								}
							}
						}
						// Capture Next of kin details too as a customer
						if($row_count == 1 && !empty($seat_row->customerpo)){
							$find_customer = $generic->getFromTable("users", "phone={$seat_row->customerpo}", 1, 1);
							if(empty($find_customer)){
								$names = array_range(explode(" ", $seat_row->address), 2);
								$first_name = empty($names[0])? "" : $names[0];
								$last_name = empty($names[1])? "" : $names[1];
								$query="INSERT INTO users (first_name, last_name, address, other, username, password, phone, email, type, gender) VALUES('{$first_name}' ,'{$last_name}','','','**********' ,'**********' ,'{$seat_row->customerpo}','','5', '')";
								$db->query($query);
							}
						}
						$rows[] = $seat_row;
						$row_count++;
					}
				}
			}
			// Validate the seat's availability again
			if($response->status && empty($post->tid1)){
				if(!empty($post->tid))$seat_query = "parent='{$post->tid}'";
				else $seat_query = "date_due='{$post->date_due}' AND it_id='{$post->it_id}' AND trans_type='{$post->trans_type}' AND sub>'0'";
				$requested_seats = implode("','", array_column($rows, "unit"));
				$bookedseats		 = $db->query("SELECT unit FROM {$param->table} WHERE $seat_query AND unit IN ('{$requested_seats}')");
				if($bookedseats->num_rows){
					$unavailable_seats= [];
					while ($row_bookedseats = $bookedseats->fetch_object()) {
						$unavailable_seats[] = $row_bookedseats->unit;
					}
					$unavailable_seats = trim(implode(",", $unavailable_seats));
					$response->status = 0;
					$response->message = $bookedseats->num_rows > 1 ? "Seats ({$unavailable_seats}) are no longer available": "Seat {$unavailable_seats} is no longer available";
				}
			}

			// Insert | Update the $trip__row
			if($response->status){
				if(empty($existing_trip)){
					$insert_values = $generic->buildQuery($insert_new_trip);
					$insert_values .= ",created_by='$created_by', updated_by='$updated_by'";
					$trip_query="INSERT INTO {$param->table} set $insert_values";
				}else{
					$insert_values = $generic->buildQuery($existing_trip);
					$insert_values .= ",updated_by='$updated_by'";
					$trip_query="UPDATE {$param->table} set $insert_values WHERE {$param->primary_key}='{$existing_trip->tid}'";
				}
				if(!$db->query($trip_query)){
					$response->status	 = 0;
					$response->message = "{$db->error}==>({$insert_values})";
				}
			}

			// Insert | Update the $seat_rows
			if($response->status){
				// run multi query on all $seat_rows collected at once
				$build_query = [];
				foreach ($rows as $key => $seat_row) {
					$insert_values 					= $generic->buildQuery($seat_row);
					if(empty($post->tid1) || $front_end_booking){
						$insert_values .= ",created_by='$created_by',updated_by='$updated_by'";
						$query="INSERT INTO {$param->table} set $insert_values";
					}else{
						$insert_values .= ",updated_by='$updated_by'";
						$query="UPDATE {$param->table} set $insert_values WHERE {$param->primary_key}='{$seat_row->tid}'";
					}
					$build_query[] = $query;
				}
				$build_query = implode(";", $build_query);
				if($db->multi_query($build_query)){
					// Clear multi_query connection and results to be able to reuse the "db" class
					while($db->more_results()){ $db->next_result(); $db->use_result(); }
					// Create activity log
					$action = empty($post->tid) ? "Create" : "Update";
					$db->query(
						"INSERT INTO activitylog (user_id, action, type, location, location_id, description)
						VALUES
						('{$updated_by}', '$action', '$activity', '{$param->table}','{$trip__row->{$param->primary_key}}', '{$user->name_col} {$action}d {$post->ref} transaction in {$param->page_title}')"
					);
				}else{
					$response->status	 = 0;
					$response->message = "{$db->error} ==> ({$build_query})";
				}
			}

			// Recalculate the Trip seats and money generated so far
			if($response->status){
				$trip__row = $generic->getFromTable($param->table, "tid={$trip__row->tid}")[0];
				$trip_seats= $generic->getFromTable($param->table, "parent={$trip__row->tid}, sub>0", 1, 0);
				$__discounts = $__rates = [];
				foreach ($trip_seats as $key => $seat) {
					$__rates[] = $seat->rate;
					$__discounts[] = $seat->discount;
				}
				$trip__row->rate = array_sum($__rates);
				$trip__row->discount = array_sum($__discounts);
				$trip__row->amount = $trip__row->rate - $trip__row->discount;
				$response->status = $db->query("UPDATE {$param->table} SET rate='{$trip__row->rate}', discount='{$trip__row->discount}', amount='{$trip__row->amount}' WHERE tid='{$trip__row->tid}'");
			}

			  // Send Mails
				// Run a callback function if any is set
			if($response->status){
				if(isset($param->post_submit_function) && $front_end_booking == 0 && empty($post->tid1)){
					$response = call_user_func_array($param->post_submit_function, [$rows]);
				}
			}
		}else{
			$response->status  = 0;
			$response->message = "Set 'sign' as either -1 or +1 in the param form_values";
		}
	}else{
		$response->status  = 0;
		$response->message = "You don't have the required access to perfom this operation";
	}

	if($response->status){
		 $db->commit();
		 if($front_end_booking){
			 $response->booking_ids = $rows;
			 $response->amount = $generic->getFromTable("trip", "id={$rows[0]->it_id}")[0]->price * count($rows);
			 $response->email = $rows[0]->email;
			 $response->process_url = $post->process_url;
		 }
	}else{
		$db->rollback();
	}
}
?>
