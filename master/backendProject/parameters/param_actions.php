<?php
$actions = [
	"barcode"=>[
		'table'=>'item',
		'primary_key'=>'tid',
		'page_title'=>'Print Barcode',
		'icon'=>'print_black',
		//'fixed_values'=>"",
		//'extra_values'=>"last_updated=now()",
		//'retrieve_filter'=>"",
		'process_url'=>'process_barcode.php',
		'submit_type'=>'print',
		'actionCol'=>'upc',
		'form'=>[
			'sections'=>[
				[
					'position'=>'center',
					'section_title'=>'Print Barcode',
					'section_elements'=>[
						[
							'column'=>'message',
							'description'=>'Print Barcode of selected products',
							'type'=>'content',
							'class'=>'col s12 m12'
						]
					]
				]
			]
		]
	],

	"print"=>[
		"table"=>"item",
		"primary_key"=>"tid",
		"page_title"=>"Print Receipt",
		"icon"=>"print_black",
		"form"=>[
			"form_view"=>"print_Form"
		]
	],

	"user-account"=>[
		'table'=>'account',
		'primary_key'=>'id',
		'page_title'=>'User Wallet',
		'icon'=>'balance_wallet_black',
		'form'=>[
			"form_view"=>"modal",
			'sections'=>[
				[
					'position'=>'Left',
					'section_title'=>'Bank Details',
					'section_elements'=>[
						[
							'column'=>'account_name',
							'description'=>'Holder Name',
							'type'=>'text',
							'required'=>true,
							'class'=>'col s12 m12'
						],[
							'column'=>'account_name',
							'description'=>'account Number',
							'type'=>'text',
							'required'=>true,
							'class'=>'col s12 m12'
						]
					]
				]
			]
		]
	],

	"messaging" => [
		"table"=>"users",
		"primary_key"=>"id",
		"page_title"=>"Send Message",
		"icon"=>"message_black",
		"process_url"=>"{$uri->backend}process/custom?case=messaging",
		"form"=>[
			"form_view"=>"modal",
			"sections"=>[
				[
					"position"=>"center",
					"section_title"=>"Messaging Channels",
					"section_elements"=> [
						[
							"column"=>"email",
							"description"=>"Email",
							"type"=>"checkbox",
							"class"=>"col s12 m4"
						],
						[
							"column"=>"sms",
							"description"=>"SMS",
							"type"=>"checkbox",
							"class"=>"col s12 m4"
						],
						[
							"column"=>"notify",
							"description"=>"Notification",
							"type"=>"checkbox",
							"required"=>true,
							"class"=>"col s12 m4"
						],
						[
							"column"=>"demograph",
							"type"=>"hidden",
							"value"=>"selected"
						]
					]
				],
				[
					"position"=>"center",
					"section_title"=>"Message Body",
					"section_elements"=> [
						[
							"column"=>"message",
							"type"=>"textarea",
							"required"=>true,
							"class"=>"col s12 m12"
						],
					]
				]
			]
		]
	],

	"user-transactions" => [
		"table"=>"transaction",
		"primary_key"=>"id",
		"page_title"=>"User Transactions",
		"icon"=>"format-list-bulleted",
		"report_type"=>"basics",
		"noTrans"=>1,
		"sort"=>"date",
		"group"=>"",
		"filter_col"=>"user_id",
		"report_setup"=>[
			"datecol"=>"date",
			"display_all"=>true,
			"display_fields"=>[
        [
          "column"=>"tx_no",
          "name"=>"tx_no",
          "description"=>"Transaction Ref",
          "grouping"=>true,
          "checked"=>true,
          "type"=>"text",
        ],
        [
          "column"=>"tx_type",
          "name"=>"tx_type",
          "description"=>"Transaction Type",
          "grouping"=>true,
          "checked"=>true,
          "type"=>"select",
					"source"=>"transaction-types",
        ],
        [
          "column"=>"sum(amount)",
          "name"=>"amount",
          "description"=>"Amount Generated",
          "checked"=>true,
          "graph"=>true,
          "type"=>"number",
        ],
        [
          "column"=>"date(date)",
          "name"=>"date",
          "description"=>"Trip Date",
          "grouping"=>true,
          "type"=>"date",
        ],
      ],
		],
		"form"=>[
			"form_view"=>"extension"
		],
	]
];
?>
