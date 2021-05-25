<?php
$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : 0;
$user_al = isset($_SESSION["accesslevel"]) ? $_SESSION["accesslevel"] : 1;
$actLog = $user_al >= 2 ? "" : ", user_id=$user_id";
// see($_SESSIO
$param = [
  "admin_signin"=> [ //Signin Parameters
    "table" 			=> "users",
    "primary_key"	=> "id",
    "username_col"=> "",
    "password_col"=> "password",
    "name_col"  	=> "username",
    "email_col" 	=> "email",
    "image_col"		=> "picture_ref",
    "interface"		=> "signin1",
    "status_col"	=> "type",
    "status_val"	=> 1,
    "callback" 		=> "signin_log",
    "password_hash"	=> "password_hash",
  ],

  "organization" 	=> [//The current organization using the code
    "table"				=> "company_info",
    "primary_key"	=> "id",
    "key"	=> 1,
    "page_title"	=> "Settings",
    "form"=>[
      "sections"=>[
        [
          "position"=>"left",
          "section_title"=>"Basic Company Info",
          "section_elements"=>[
            [
              "column"=>"name",
              "description"=>"Company Name",
              "required"=>true,
              "type"=>"text",
              "class"=>"col s12 m3"
            ],
            [
              "column"=>"other",
              "description"=>"Product Name",
              "type"=>"text",
              "required"=>true,
              "class"=>"col s12 m3",
            ],
            [
              "column"=>"slogan",
              "description"=>"Business Description",
              "required"=>true,
              "type"=>"text",
              "class"=>"col s12 m6"
            ],
            [
              "column"=>"email",
              "description"=>"Email Address",
              "required"=>true,
              "type"=>"text",
              "class"=>"col s12 m12"
            ],[
              "column"=>"website",
              "description"=>"Website",
              "required"=>false,
              "type"=>"text",
              "class"=>"col s12 m12"
            ],[
              "column"=>"address",
              "description"=>"Address",
              "required"=>false,
              "type"=>"text",
              "class"=>"col s12 m12"
            ],
            [
              "column"=>"phone",
              "description"=>"Phone",
              "type"=>"text",
              "required"=>true,
              "class"=>"col s12 m12",
            ],

          ]
        ],[
          "position"=>"right",
          "section_title"=>"Company Logo",
          "section_elements"=>[
            [
              "column"=>"logo_ref",
              "description"=>"Logo",
              "required"=>true,
              "type"=>"items",
              "value"=>2,
              "class"=>"col s12 m12"
            ]
          ]
        ]
      ]
    ]
  ],//new page

  "role"=>[
    "table"=>"roles",
    "primary_key"=>"roleid",
    "page_title"=>"Roles",
    "display_fields"=>[
      [
        "column"=>"rolename",
        "description"=>"Role Name",
        "component"=>"span"
      ]
    ],
    "form"=>[
      "sections"=>[
        [
          "position"=>"center",
          "section_title"=>"Role Info",
          "section_elements"=>[
            [
              "column"=>"rolename",
              "description"=>"Role Name",
              "type"=>"text",
              "required"=>true,
              "class"=>"col s12 m12"
            ],[
              "column"=>"roledesc",
              "description"=>"Role Name",
              "required"=>true,
              "type"=>"roles",
              "class"=>"col s12 m12"
            ]
          ]
        ]
      ]
    ]
  ],

  "log"=>[
    "table"=>"activitylog",
    "primary_key"=>"id",
    "page_title"=>"log",
    "ModalForm"=>true,
    "listFAB"=>false,
    "retrieve_filter"=>"type='admin' $actLog",
    "sort_col"=>"id DESC",
    "display_fields"=>[
      [
        "column"=>"action",
        "description"=>"Action",
        "component"=>"span"
      ],[
        "column"=>"description",
        "description"=>"Description",
        "component"=>"span"
      ],[
        "column"=>"date",
        "description"=>"Time",
        "component"=>"span",
        "action"=>"datetime"
      ]
    ],
    "form"=>[
      "sections"=>[
        [
          "position"=>"center",
          "section_title"=>"Log Details",
          "section_elements"=>[
            [
              "column"=>"description",
              "description"=>"Description",
              "type"=>"textarea",
              "readonly"=>true,
              "class"=>"col s12"
            ]
          ]
        ]
      ]
    ]
  ],

  "users"=>[
    "table"=>"users",
    "primary_key"=>"id",
    "page_title"=>"Users",
    "fixed_values"=>"status=1",
    "retrieve_filter"=>"status=1, type=1",
    "display_fields"=>[
      [
        "column"=>"first_name",
        "description"=>"First Name",
        "component"=>"span"
      ],[
        "column"=>"last_name",
        "description"=>"Last Name",
        "component"=>"span"
      ]
      ,[
        "column"=>"gender",
        "source"=>"gender",
        "action"=>"select",
        "description"=>"Gender",
        "component"=>"span"
      ]
    ],
    "form"=>[
      "sections"=>[
        [
          "position"=>"left",
          "section_title"=>"User Info",
          "section_elements"=>[
            [
              "column"=>"first_name",
              "description"=>"First Name",
              "required"=>true,
              "type"=>"text",
              "class"=>"col s12 m6"
            ],[
              "column"=>"last_name",
              "description"=>"Last Name",
              "required"=>true,
              "type"=>"text",
              "class"=>"col s12 m6"
            ],[
              "column"=>"gender",
              "description"=>"Gender",
              "required"=>true,
              "type"=>"select",
              "source"=>"gender",
              "class"=>"col s12 m6"
            ],[
              "column"=>"dob",
              "description"=>"Date of Birth",
              "required"=>true,
              "type"=>"date",
              "class"=>"col s12 m6"
            ]
          ]
        ],[
          "position"=>"left",
          "section_title"=>"Contact Info",
          "section_elements"=>[
            [
              "column"=>"phone",
              "description"=>"Phone Number",
              "class"=>"col s12 m12",
              "type"=>"text"
            ],[
              "column"=>"email",
              "description"=>"Email",
              "class"=>"col s12 m12",
              "type"=>"text",
              "required"=>true,
            ],[
              "column"=>"address",
              "description"=>"Residential Address",
              "class"=>"col s12 m12",
              "type"=>"text"
            ]
          ]
        ],[
          "position"=>"right",
          "section_title"=>"Admin Picture",
          "section_elements"=>[
            [
              "column"=>"picture_ref",
              "description"=>"Logo",
              "type"=>"picture",
              "class"=>"col s12 m12"
            ]
          ]
        ],[
          "position"=>"right",
          "section_title"=>"Security Settings",
          "section_elements"=>[
            [
              "column"=>"type",
              "description"=>"Category",
              "class"=>"col s12 m4",
              "type"=>"select",
              "required"=>true,
              "source"=>"user-category",
              "value"=>"****************",
            ],
            [
              "column"=>"roleid",
              "description"=>"Role",
              "type"=>"select",
              "required"=>true,
              "class"=>"col s12 m4",
              "source"=>"role",
            ],
            [
              "column"=>"accesslevel",
              "description"=>"Access Level",
              "type"=>"select",
              "required"=>true,
              "class"=>"col s12 m4",
              "source"=>"accessLevel",
            ],
            [
              "column"=>"username",
              "description"=>"Username",
              "class"=>"col s12 m6",
              "type"=>"text",
              "required"=>true
            ],[
              "column"=>"password",
              "description"=>"Password",
              "type"=>"password",
              "required"=>true,
              "class"=>"col s12 m6"
            ],
          ]
        ],

      ]
    ]
  ],

  "members"=>[
    "table"=>"users",
    "primary_key"=>"id",
    "page_title"=>"Members",
    "sort"=>"date DESC",
    "listFAB"=>["refresh", "messaging", "delete"],
		"extension"=>["messaging", "user-transactions", "user-account"],
    "retrieve_filter"=>"type=2",
    "display_fields"=>[
      [
        "column"=>"username",
        "description"=>"User Name",
        "component"=>"span"
      ],
      [
        "column"=>"email",
        "description"=>"Email",
        "component"=>"span"
      ],
      [
        "column"=>"status",
        "description"=>"Status",
        "action"=>"select",
        "source"=>"active",
        "component"=>"span"
      ],
      [
        "column"=>"date",
        "description"=>"Registered on",
        "action"=>"datetime",
        "component"=>"span"
      ]
    ],
    "category"=>[
      [
        "name"=>"Filter By DAte",
        "column"=>"date",
        "type"=>"period",
      ],
      [
        "name"=>"Active",
        "column"=>"status",
        "type"=>"select",
        "value"=>"1",
      ],
      [
        "name"=>"Inactive",
        "column"=>"status",
        "type"=>"select",
        "value"=>"0",
      ],
    ],
    "form"=>[
      "form_view"=>"modal",
      "sections"=>[
        [
          "position"=>"left",
          "section_title"=>"Contact Info",
          "section_elements"=>[
            [
              "column"=>"username",
              "description"=>"User Name",
              "class"=>"col s12 m12",
              "type"=>"text"
            ],
            [
              "column"=>"email",
              "description"=>"Email",
              "class"=>"col s12 m12",
              "type"=>"text",
              "required"=>true,
            ],
            [
              "column"=>"date",
              "description"=>"Date",
              "class"=>"col s12 m12",
              "type"=>"date",
              "disabled"=>true,
            ]
          ]
        ],[
          "position"=>"right",
          "section_title"=>"Security Settings",
          "section_elements"=>[
            [
              "column"=>"type",
              "description"=>"Category",
              "class"=>"col s12",
              "type"=>"select",
              "required"=>true,
              "source"=>"user-category",
              "value"=>"****************",
            ],
            [
              "column"=>"roleid",
              "description"=>"Role",
              "type"=>"select",
              "required"=>true,
              "class"=>"col s12 m6",
              "source"=>"role",
            ],
            [
              "column"=>"accesslevel",
              "description"=>"Access Level",
              "type"=>"select",
              "required"=>true,
              "class"=>"col s12 m6",
              "source"=>"accessLevel",
            ],
            [
              "column"=>"status",
              "description"=>"Activation Status",
              "type"=>"switch",
              "class"=>"col s12",
              "source"=>"active",
            ],
          ]
        ],

      ]
    ]
  ],

  "subscribers"=>[
    "table"=>"subscribers",
    "primary_key"=>"id",
    "page_title"=>"Subscribers",
    "listFAB"=>["delete"],
    "display_fields"=>[
      [
        "column"=>"email",
        "description"=>"Email",
        "component"=>"span"
      ]
    ],
    "form"=>[
      "form_view"=>"modal",
      "sections"=>[
        [
          "position"=>"center",
          "section_title"=>"Subscriber Info",
          "section_elements"=>[
            [
              "column"=>"name",
              "description"=>"Subscriber Name",
              "type"=>"text",
              "required"=>true,
              "class"=>"col s12 m12"
            ],[
              "column"=>"email",
              "description"=>"Email",
              "type"=>"email",
              "required"=>true,
              "class"=>"col s12 m12"
            ]
          ]
        ]
      ]
    ]
  ],

  "accounts"=>[
    "table"=>"account",
    "primary_key"=>"id",
    "page_title"=>"Customer Wallets",
    "listFAB"=>["refresh","delete"],
    "category"=>[
      [
        "column"=>"date",
        "name"=>"Filter By DAte",
        "type"=>"period",
      ]
    ],
    "sort"=>"date desc",
    "display_fields"=>[
      [
        "column"=>"user_id",
        "component"=>"span",
        "description"=>"Customers",
        "action"=>"select",
        "source"=>[
          "pageType" =>"members",
          "column"=>["username", "email"]
        ],
        "class"=>"col s6 m3",
      ],
      [
        "column"=>"balance",
        "component"=>"span",
        "description"=>"Balance",
        "class"=>"col s6 m2",
      ],
      [
        "column"=>"bonus",
        "component"=>"span",
        "description"=>"Referral Bonus",
        "class"=>"col s6 m2",
      ],
      [
        "column"=>"date",
        "component"=>"span",
        "description"=>"Date",
        "action"=>"datetime",
        "class"=>"col s6 m2",
      ]
    ],
    "form"=>[
      "form_view"=>"modal",
      "form_submit"=>false,
      "sections"=>[
        [
          "position"=>"left",
          "section_title"=>"View Wallet",
          "section_elements"=>[
            [
              "column"=>"balance",
              "description"=>"Balance",
              "type"=>"number",
              "required"=>true,
              "class"=>"col s12"
            ],
            [
              "column"=>"bonus",
              "type"=>"number",
              "description"=>"Referral Bonus",
              "class"=>"col s12",
            ],
            [
              "column"=>"account_name",
              "type"=>"text",
              "description"=>"Acccount Name ",
              "class"=>"col s12",
            ],
            [
              "column"=>"account_no",
              "type"=>"text",
              "description"=>"Acccount Number",
              "class"=>"col s12",
            ],
          ]
        ]
      ]
    ]
  ],

  "teams"=>[
    "table"=>"team",
    "primary_key"=>"id",
    "page_title"=>"Coin Owner Groups",
    "listFAB"=>["refresh","delete"],
    "category"=>[
      [
        "column"=>"date",
        "name"=>"Filter By DAte",
        "type"=>"period",
      ]
    ],
    "sort"=>"date desc",
    "display_fields"=>[
      [
        "column"=>"name",
        "component"=>"span",
        "description"=>"Name",
        "class"=>"col s6 m2",
      ],
      [
        "column"=>"type",
        "component"=>"span",
        "description"=>"Transaction Type",
        "class"=>"col s6 m2",
      ],
      [
        "column"=>"coin_price",
        "component"=>"span",
        "description"=>"Coin Price",
        "class"=>"col s6 m2",
      ],
      [
        "column"=>"rate",
        "component"=>"span",
        "description"=>"Coin Growth Rate",
        "class"=>"col s6 m2",
      ],
      [
        "column"=>"status",
        "component"=>"span",
        "description"=>"Team Status",
        "class"=>"col s6 m2",
      ]
    ],
    "form"=>[
      "form_view"=>"modal",
      "form_submit"=>false,
      "sections"=>[
        [
          "position"=>"left",
          "section_title"=>"View Wallet",
          "section_elements"=>[
            [
              "column"=>"name",
              "description"=>"Name",
              "type"=>"text",
              "required"=>true,
              "class"=>"col s12"
            ],
            [
              "column"=>"type",
              "description"=>"Type",
              "type"=>"text",
              "required"=>true,
              "class"=>"col s12"
            ],
            [
              "column"=>"coin_price",
              "description"=>"Price",
              "type"=>"number",
              "required"=>true,
              "class"=>"col s12"
            ],
            [
              "column"=>"rate",
              "type"=>"number",
              "description"=>"Rate",
              "class"=>"col s12",
            ],
          ]
        ]
      ]
    ]
  ],

  "transactions"=>[
    "table"=>"transaction",
    "primary_key"=>"id",
    "page_title"=>"All Transactions",
    "listFAB"=>["refresh"],
    "category"=>[
      [
        "column"=>"date",
        "name"=>"Filter By DAte",
        "type"=>"period",
      ],
      [
        "name"=>"Withdrawal",
        "column"=>"tx_type",
        "type"=>"select",
        "value"=>"WTD",
      ],
      [
        "name"=>"Deposits",
        "column"=>"tx_type",
        "type"=>"select",
        "value"=>"DEP",
      ],
      [
        "name"=>"Credit",
        "column"=>"tx_type",
        "type"=>"select",
        "value"=>"CRD",
      ],
      [
        "name"=>"Debits",
        "column"=>"tx_type",
        "type"=>"select",
        "value"=>"DBT",
      ]
    ],
    "sort"=>"id desc",
    "display_fields"=>[
      [
        "column"=>"tx_no",
        "component"=>"span",
        "description"=>"Tranx No.",
        "class"=>"col s6 m2",
      ],
      [
        "column"=>"amount",
        "component"=>"span",
        "description"=>"Amt.",
        "class"=>"col s6 m1",
        "action"=>"number_abbr__",
      ],
      [
        "column"=>"user_id",
        "component"=>"span",
        "description"=>"User",
        "class"=>"col s6 m1",
        "action"=>"select",
        "source"=>[
          "pageType"=>"users",
          "column" =>"username"
        ],
      ],
      [
        "column"=>"description",
        "component"=>"span",
        "description"=>"Description",
        "class"=>"col s6 m4",
      ],
      [
        "column"=>"tx_type",
        "component"=>"span",
        "source"=>"transaction-types",
        "action"=>"select",
        "description"=>"Type",
        "class"=>"col s6 m1",
      ],
      [
        "column"=>"status",
        "component"=>"span",
        "source"=>"confirm",
        "action"=>"select",
        "description"=>"Status",
        "class"=>"col s6 m1",
      ],
      [
        "column"=>"date",
        "component"=>"span",
        "description"=>"Date",
        "action"=>"datetime",
        "class"=>"col s6 m2",
      ],
    ],
    "form"=>[
      "form_view"=>"modal",
      // "form_submit"=>false,
      "sections"=>[
        [
          "position"=>"left",
          "section_title"=>"View Withdrawal Request",
          "section_elements"=>[
            [
              "column"=>"tx_no",
              "description"=>"Transaction No",
              "required"=>true,
              "disabled"=>true,
              "type"=>"text",
              "class"=>"col s12"
            ],
            [
              "column"=>"account",
              "description"=>"Reciepient Account",
              "required"=>true,
              "disabled"=>true,
              "class"=>"col s12"
            ],
            [
              "column"=>"user_id",
              "description"=>"",
              "required"=>true,
              "type"=>"hidden",
              "class"=>"col s12"
            ],
            [
              "column"=>"amount",
              "description"=>"Withdrawal Amount",
              "required"=>true,
              "disabled"=>true,
              "class"=>"col s12"
            ],
            [
              "column"=>"description",
              "description"=>"Description",
              "type"=>"textarea",
              "required"=>true,
              "disabled"=>true,
              "class"=>"col s12"
            ],
          ]
        ],
        [
          "position"=>"left",
          "section_title"=>"View Transaction",
          "section_elements"=>[
            [
              "column"=>"status",
              "description"=>"Toggle Status",
              "source"=>"confirm",
              "type"=>"switch",
              "class"=>"col s12"
            ],
            [
              "column"=>"from_admin",
              "value"=>"1",
              "type"=>"hidden",
              "class"=>"col s12 hide"
            ],
            [
              "column"=>"return_values",
              "value"=>"1",
              "type"=>"hidden",
              "class"=>"col s12 hide"
            ]
          ]
        ],

      ]
    ]
  ],

  "withdrawals"=>[
    "table"=>"transaction",
    "primary_key"=>"id",
    "page_title"=>"Withdraw Transactions",
    "fixed_values"=>"tx_type=WTD",
    "retrieve_filter"=>"tx_type=WTD,status=0",
    // "post_submit_function"=>"toggle_transaction_status",
    "listFAB"=>["refresh"],
    "category"=>[
      [
        "column"=>"date",
        "name"=>"Filter By DAte",
        "type"=>"period",
      ]
    ],
    "sort"=>"date desc",
    "display_fields"=>[
      [
        "column"=>"tx_no",
        "component"=>"span",
        "description"=>"Tranx No.",
        "class"=>"col s6 m2",
      ],
      [
        "column"=>"amount",
        "component"=>"span",
        "description"=>"Amt.",
        "class"=>"col s6 m1",
        "action"=>"number_abbr__",
      ],
      [
        "column"=>"user_id",
        "component"=>"span",
        "description"=>"User",
        "class"=>"col s6 m1",
        "action"=>"select",
        "source"=>[
          "pageType"=>"users",
          "column" =>"username"
        ],
      ],
      [
        "column"=>"description",
        "component"=>"span",
        "description"=>"Description",
        "class"=>"col s6 m4",
      ],
      [
        "column"=>"tx_type",
        "component"=>"span",
        "source"=>"transaction-types",
        "action"=>"select",
        "description"=>"Type",
        "class"=>"col s6 m1",
      ],
      [
        "column"=>"status",
        "component"=>"span",
        "source"=>"confirm",
        "action"=>"select",
        "description"=>"Status",
        "class"=>"col s6 m1",
      ],
      [
        "column"=>"date",
        "component"=>"span",
        "description"=>"Date",
        "action"=>"datetime",
        "class"=>"col s6 m2",
      ],
    ],
    "form"=>[
      "form_view"=>"modal",
      // "form_submit"=>false,
      "sections"=>[
        [
          "position"=>"left",
          "section_title"=>"View Withdrawal Request",
          "section_elements"=>[
            [
              "column"=>"tx_no",
              "description"=>"Transaction No",
              "required"=>true,
              "disabled"=>true,
              "type"=>"text",
              "class"=>"col s12"
            ],
            [
              "column"=>"account",
              "description"=>"Reciepient Account",
              "required"=>true,
              "disabled"=>true,
              "class"=>"col s12"
            ],
            [
              "column"=>"user_id",
              "description"=>"",
              "required"=>true,
              "type"=>"hidden",
              "class"=>"col s12"
            ],
            [
              "column"=>"amount",
              "description"=>"Withdrawal Amount",
              "required"=>true,
              "disabled"=>true,
              "class"=>"col s12"
            ],
            [
              "column"=>"description",
              "description"=>"Description",
              "type"=>"textarea",
              "required"=>true,
              "disabled"=>true,
              "class"=>"col s12"
            ],
          ]
        ],
        [
          "position"=>"left",
          "section_title"=>"View Transaction",
          "section_elements"=>[
            [
              "column"=>"status",
              "description"=>"Toggle Status",
              "source"=>"confirm",
              "type"=>"switch",
              "class"=>"col s12"
            ],
            [
              "column"=>"from_admin",
              "value"=>"1",
              "type"=>"hidden",
              "class"=>"col s12 hide"
            ],
            [
              "column"=>"return_values",
              "value"=>"1",
              "type"=>"hidden",
              "class"=>"col s12 hide"
            ]
          ]
        ],

      ]
    ]
  ],

  "deposits"=>[
    "table"=>"transaction",
    "primary_key"=>"id",
    "page_title"=>"Deposit Transactions",
    "fixed_values"=>"tx_type=DEP",
    "retrieve_filter"=>"tx_type=DEP,status=0",
    "post_submit_function"=>"toggle_transaction_status",
    "listFAB"=>["refresh","delete"],
    "category"=>[
      [
        "column"=>"date",
        "name"=>"Filter By DAte",
        "type"=>"period",
      ]
    ],
    "sort"=>"date desc",
    "display_fields"=>[
      [
        "column"=>"tx_no",
        "component"=>"span",
        "description"=>"Tranx No.",
        "class"=>"col s6 m2",
      ],
      [
        "column"=>"amount",
        "component"=>"span",
        "description"=>"Amt.",
        "class"=>"col s6 m1",
        "action"=>"number_abbr__",
      ],
      [
        "column"=>"user_id",
        "component"=>"span",
        "description"=>"User",
        "class"=>"col s6 m1",
        "action"=>"select",
        "source"=>[
          "pageType"=>"users",
          "column" =>"username"
        ],
      ],
      [
        "column"=>"description",
        "component"=>"span",
        "description"=>"Description",
        "class"=>"col s6 m4",
      ],
      [
        "column"=>"tx_type",
        "component"=>"span",
        "source"=>"transaction-types",
        "action"=>"select",
        "description"=>"Type",
        "class"=>"col s6 m1",
      ],
      [
        "column"=>"status",
        "component"=>"span",
        "source"=>"confirm",
        "action"=>"select",
        "description"=>"Status",
        "class"=>"col s6 m1",
      ],
      [
        "column"=>"date",
        "component"=>"span",
        "description"=>"Date",
        "action"=>"datetime",
        "class"=>"col s6 m2",
      ],
    ],
    "form"=>[
      "form_view"=>"modal",
      // "form_submit"=>false,
      "sections"=>[
        [
          "position"=>"center",
          "section_title"=>"View Transaction",
          "section_elements"=>[
            [
              "column"=>"evidence",
              "description"=>"Evidence",
              "type"=>"displaypicture",
              "class"=>"col s12"
            ],
            [
              "column"=>"from_admin",
              "value"=>"1",
              "type"=>"hidden",
              "class"=>"col s12 hide"
            ],
            [
              "column"=>"return_values",
              "value"=>"1",
              "type"=>"hidden",
              "class"=>"col s12 hide"
            ]
          ]
        ],
        [
          "position"=>"left",
          "section_title"=>"View Transaction",
          "section_elements"=>[
            [
              "column"=>"tx_no",
              "description"=>"Transaction No",
              "required"=>true,
              "disabled"=>true,
              "type"=>"text",
              "class"=>"col s12"
            ],
            [
              "column"=>"user_id",
              "description"=>"User ID",
              "required"=>true,
              "type"=>"hidden",
              "class"=>"col s12"
            ],
            [
              "column"=>"amount",
              "description"=>"Amount Deposited",
              "required"=>true,
              "type"=>"hidden",
              "class"=>"col s12"
            ],
            [
              "column"=>"description",
              "description"=>"Description",
              "type"=>"textarea",
              "required"=>true,
              "disabled"=>true,
              "class"=>"col s12"
            ],
          ]
        ],
        [
          "position"=>"right",
          "section_title"=>"Approval",
          "section_elements"=>[
            [
              "column"=>"status",
              "description"=>"Toggle Status",
              "source"=>"confirm",
              "type"=>"switch",
              "class"=>"col s12"
            ]
          ]
        ],

      ]
    ]
  ],

  "debit"=>[
    "table"=>"transaction",
    "primary_key"=>"id",
    "page_title"=>"Debit Transactions",
    "fixed_values"=>"tx_type=DBT",
    "retrieve_filter"=>"tx_type=DBT,status=0",
    "listFAB"=>["refresh"],
    "category"=>[
      [
        "column"=>"date",
        "name"=>"Filter By DAte",
        "type"=>"period",
      ]
    ],
    "sort"=>"date desc",
    "display_fields"=>[
      [
        "column"=>"tx_no",
        "component"=>"span",
        "description"=>"Tranx No.",
        "class"=>"col s6 m2",
      ],
      [
        "column"=>"amount",
        "component"=>"span",
        "description"=>"Amt.",
        "class"=>"col s6 m1",
      ],
      [
        "column"=>"description",
        "component"=>"span",
        "description"=>"Description",
        "class"=>"col s6 m5",
      ],
      [
        "column"=>"status",
        "component"=>"span",
        "source"=>"confirm",
        "action"=>"select",
        "description"=>"Status",
        "class"=>"col s6 m2",
      ],
      [
        "column"=>"date",
        "component"=>"span",
        "description"=>"Date",
        "action"=>"datetime",
        "class"=>"col s6 m2",
      ],
    ],
    "form"=>[
      "form_view"=>"modal",
      // "form_submit"=>false,
      "sections"=>[
        [
          "position"=>"center",
          "section_title"=>"View Transaction",
          "section_elements"=>[
            [
              "column"=>"evidence",
              "description"=>"Evidence",
              "type"=>"displaypicture",
              "class"=>"col s12"
            ],
            [
              "column"=>"from_admin",
              "value"=>"1",
              "type"=>"hidden",
              "class"=>"col s12 hide"
            ],
            [
              "column"=>"return_values",
              "value"=>"1",
              "type"=>"hidden",
              "class"=>"col s12 hide"
            ]
          ]
        ],
        [
          "position"=>"left",
          "section_title"=>"View Transaction",
          "section_elements"=>[
            [
              "column"=>"tx_no",
              "description"=>"Transaction No",
              "required"=>true,
              "disabled"=>true,
              "type"=>"text",
              "class"=>"col s12"
            ],
            [
              "column"=>"user_id",
              "description"=>"User ID",
              "required"=>true,
              "type"=>"hidden",
              "class"=>"col s12"
            ],
            [
              "column"=>"amount",
              "description"=>"Amount Deposited",
              "required"=>true,
              "type"=>"hidden",
              "class"=>"col s12"
            ],
            [
              "column"=>"description",
              "description"=>"Description",
              "type"=>"textarea",
              "required"=>true,
              "disabled"=>true,
              "class"=>"col s12"
            ],
          ]
        ],
        [
          "position"=>"right",
          "section_title"=>"Approval",
          "section_elements"=>[
            [
              "column"=>"status",
              "description"=>"Toggle Status",
              "source"=>"confirm",
              "type"=>"switch",
              "class"=>"col s12"
            ]
          ]
        ],

      ]
    ]
  ],

  "credit"=>[
    "table"=>"transaction",
    "primary_key"=>"id",
    "page_title"=>"Credit Transactions",
    "fixed_values"=>"tx_type=CRD",
    "retrieve_filter"=>"tx_type=CRD",
    "listFAB"=>["refresh"],
    "category"=>[
      [
        "column"=>"date",
        "name"=>"Filter By DAte",
        "type"=>"period",
      ]
    ],
    "sort"=>"date desc",
    "display_fields"=>[
      [
        "column"=>"tx_no",
        "component"=>"span",
        "description"=>"Tranx No.",
        "class"=>"col s6 m2",
      ],
      [
        "column"=>"amount",
        "component"=>"span",
        "description"=>"Amt.",
        "class"=>"col s6 m1",
      ],
      [
        "column"=>"description",
        "component"=>"span",
        "description"=>"Description",
        "class"=>"col s6 m5",
      ],
      [
        "column"=>"status",
        "component"=>"span",
        "source"=>"confirm",
        "action"=>"select",
        "description"=>"Status",
        "class"=>"col s6 m2",
      ],
      [
        "column"=>"date",
        "component"=>"span",
        "description"=>"Date",
        "action"=>"datetime",
        "class"=>"col s6 m2",
      ],
    ],
    "form"=>[
      "form_view"=>"modal",
      // "form_submit"=>false,
      "sections"=>[
        [
          "position"=>"left",
          "section_title"=>"View Transaction",
          "section_elements"=>[
            [
              "column"=>"tx_no",
              "description"=>"Transaction No",
              "required"=>true,
              "disabled"=>true,
              "type"=>"text",
              "class"=>"col s12"
            ],
            [
              "column"=>"user_id",
              "description"=>"User ID",
              "required"=>true,
              "type"=>"hidden",
              "class"=>"col s12"
            ],
            [
              "column"=>"amount",
              "description"=>"Amount Deposited",
              "required"=>true,
              "type"=>"hidden",
              "class"=>"col s12"
            ],
            [
              "column"=>"description",
              "description"=>"Description",
              "type"=>"textarea",
              "required"=>true,
              "disabled"=>true,
              "class"=>"col s12"
            ],
          ]
        ],
        [
          "position"=>"left",
          "section_title"=>"View Transaction",
          "section_elements"=>[
            [
              "column"=>"status",
              "description"=>"Toggle Status",
              "source"=>"confirm",
              "type"=>"switch",
              "class"=>"col s12"
            ]
          ]
        ],
        [
          "position"=>"right",
          "section_title"=>"View Transaction",
          "section_elements"=>[
            [
              "column"=>"evidence",
              "description"=>"Evidence",
              "type"=>"displaypicture",
              "class"=>"col s12"
            ],
            [
              "column"=>"from_admin",
              "value"=>"1",
              "type"=>"hidden",
              "class"=>"col s12 hide"
            ],
            [
              "column"=>"return_values",
              "value"=>"1",
              "type"=>"hidden",
              "class"=>"col s12 hide"
            ]
          ]
        ]
      ]
    ]
  ],

  "charges"=>[
    "table"=>"transaction",
    "primary_key"=>"id",
    "page_title"=>"Charges Transactions",
    "fixed_values"=>"tx_type=DBT, paid_into=COMPANY",
    "retrieve_filter"=>"tx_type=DBT,paid_into=COMPANY",
    "listFAB"=>["refresh"],
    "category"=>[
      [
        "column"=>"date",
        "name"=>"Filter By DAte",
        "type"=>"period",
      ]
    ],
    "sort"=>"date desc",
    "display_fields"=>[
      [
        "column"=>"tx_no",
        "component"=>"span",
        "description"=>"Tranx No.",
        "class"=>"col s6 m2",
      ],
      [
        "column"=>"amount",
        "component"=>"span",
        "description"=>"Amt.",
        "class"=>"col s6 m1",
      ],
      [
        "column"=>"description",
        "component"=>"span",
        "description"=>"Description",
        "class"=>"col s6 m5",
      ],
      [
        "column"=>"status",
        "component"=>"span",
        "source"=>"confirm",
        "action"=>"select",
        "description"=>"Status",
        "class"=>"col s6 m2",
      ],
      [
        "column"=>"date",
        "component"=>"span",
        "description"=>"Date",
        "action"=>"datetime",
        "class"=>"col s6 m2",
      ],
    ],
    "form"=>[
      "form_view"=>"modal",
      // "form_submit"=>false,
      "sections"=>[
        [
          "position"=>"left",
          "section_title"=>"View Transaction",
          "section_elements"=>[
            [
              "column"=>"tx_no",
              "description"=>"Transaction No",
              "required"=>true,
              "disabled"=>true,
              "type"=>"text",
              "class"=>"col s12"
            ],
            [
              "column"=>"user_id",
              "description"=>"User ID",
              "required"=>true,
              "type"=>"hidden",
              "class"=>"col s12"
            ],
            [
              "column"=>"amount",
              "description"=>"Amount Deposited",
              "required"=>true,
              "type"=>"hidden",
              "class"=>"col s12"
            ],
            [
              "column"=>"description",
              "description"=>"Description",
              "type"=>"textarea",
              "required"=>true,
              "disabled"=>true,
              "class"=>"col s12"
            ],
          ]
        ],
        [
          "position"=>"left",
          "section_title"=>"View Transaction",
          "section_elements"=>[
            [
              "column"=>"status",
              "description"=>"Toggle Status",
              "source"=>"confirm",
              "type"=>"switch",
              "class"=>"col s12"
            ]
          ]
        ],
        [
          "position"=>"right",
          "section_title"=>"View Transaction",
          "section_elements"=>[
            [
              "column"=>"evidence",
              "description"=>"Evidence",
              "type"=>"displaypicture",
              "class"=>"col s12"
            ],
            [
              "column"=>"from_admin",
              "value"=>"1",
              "type"=>"hidden",
              "class"=>"col s12 hide"
            ],
            [
              "column"=>"return_values",
              "value"=>"1",
              "type"=>"hidden",
              "class"=>"col s12 hide"
            ]
          ]
        ]
      ]
    ]
  ],

  "posts"=>[
    "table"=>"content",
    "primary_key"=>"id",
    "page_title"=>"Blog Post",
    "fixed_values"=>"type='blog',no_of_views='0',user_id='$user_id'",
    "retrieve_filter"=>"type='blog'",
    "extra_values"=>"date_updated=now()",
    "pre_submit_function"=>"slug",
    "sort"=>"date_uploaded DESC",
    "display_fields"=>[
      [
        "column"=>"title",
        "description"=>"Title",
        "component"=>"span",
        "class"=>"col s5",
      ],[
        "column"=>"user_id",
        "description"=>"Uploaded By",
        "component"=>"span",
        "action"=>"select",
        "source"=>"users",
        "class"=>"col s2",
      ],[
        "column"=>"status",
        "description"=>"Published ?",
        "component"=>"span",
        "action"=>"select",
        "source"=>"bool",
        "class"=>"col s2",
      ],[
        "column"=>"date_uploaded",
        "description"=>"Date",
        "component"=>"span",
        "action"=>"datetime",
        "class"=>"col s3",
      ]
    ],
    "category"=>[
      [
        "column"=>"",
        "name"=>"All Blog Post",
        "type"=>"select",
      ],[
        "column"=>"status",
        "type"=>"select",
        "name"=>"Drafts",
        "value"=>"0"
      ],[
        "column"=>"status",
        "type"=>"select",
        "name"=>"Published",
        "value"=>"1"
      ],
      [
        "column"=>"date_uploaded",
        "type"=>"period",
        "name"=>"Date",
      ]
    ],

    "form"=>[
      "sections"=>[
        [
          "position"=>"left",
          "section_title"=>"",
          "section_elements"=>[
            [
              "column"=>"image",
              "type"=>"generic-slider",
              "class"=>"col s12 m12"
            ]
          ]
        ],
        [
          "position"=>"right",
          "section_title"=>"Blog Post Settings",
          "section_elements"=>[
            [
              "column"=>"title",
              "description"=>"Blog Post Title",
              "required"=>true,
              "placeholder"=>true,
              "type"=>"richtext-title",
              "class"=>"col s12 m12"
            ],
            [
              "column"=>"body",
              "description"=>"Blog Post content",
              "required"=>true,
              "type"=>"richtext-body",
              "class"=>"col s12 m12"
            ],
          ]
        ],
        [
          "position"=>"left",
          "section_title"=>"Blog Post Settings",
          "section_elements"=>[
            [
              "column"=>"author",
              "source"=>[
                "pageType"=>"users",
                "column"=>"first_name, last_name",
                "concat"=>true,
              ],
              "description"=>"Author",
              "required"=>true,
              "empty"=>true,
              "type"=>"select",
              "class"=>"col s12 m6"
            ],[
              "column"=>"status",
              "source"=>"publish",
              "type"=>"switch",
              "empty"=>false,
              "class"=>"col s12 m6"
            ]
          ]
        ]
      ]
    ]
  ]
];
