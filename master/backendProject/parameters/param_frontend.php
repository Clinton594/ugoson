<?php

$front = [
  "changePassword"=>[
    "table"=>"users",
    "pre_submit_function"=>"changePassword",
    "primary_key"=>"id",
  ],

	"bankAccount"=>[
		"table"=>"account",
		"primary_key"=>"id",
		"page_title"=>"Accounts",
    "pre_submit_function"=>"paystack_reciepient",
		"display_fields"=>[
			[
				"column"=>"bank_code",
				"description"=>"Bank",
				"component"=>"span",
				"action"=>"select",
				"source"=>"banks",
				"class" =>"col s6 l3"
			],
      [
				"column"=>"account_no",
				"description"=>"Account No.",
				"component"=>"span",
				"class" =>"col s6 l3"
			],
      [
				"column"=>"account_name",
				"description"=>"Account Name",
				"component"=>"span",
				"class" =>"col s6 l3"
			],
		],
	],

  "loginMembers"=> [ //Signin Parameters
    "table" 			=> "users",
    "primary_key"	=> "id",
    "username_col"=> "username",
    "password_col"=> "password",
    "name_col"  	=> "first_name",
    "email_col" 	=> "email",
    "image_col"		=> "picture_ref",
    "phone_col"		=> "phone",
    "status_col"	=> "type",
    "password_hash"=> "password_hash",
    "status_val"	=> 2,
    "unique_key" 	=>"email",
    "pre_submit_function" => "prepare_new_member",
    "post_submit_function" => "welcome_to_graphcoin",
    "fixed_values" => "status=0, type=2",
    "callback" 		=> "graphcoin_login",
  ],
];

?>
