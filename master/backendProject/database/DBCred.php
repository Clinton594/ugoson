<?php
class DBCred {
  static $local_db = "dgitcoin";
  static $local_password = "";
  static $local_server = "localhost";
  static $local_user = "root";
  static $online_db = "u262969779_dgitcoin";
  static $online_password = "Dgitcoin123$#@willpay";
  static $online_user ="u262969779_dgitcoinuser";
  static $server = "localhost";
  static $local_servers = ['localhost', 'localhost:8080', "127.0.0.1", "192.168.43.80"];
  public $backend = "master/";
  static $foreign_exchange = [
    "API_KEY" => ""
  ];

  static $twilio = [
    "SMS_DEFAULT"=>"",
    "ACCOUNT"=>"",
    "PHONE"=>"",
    "SECRET"=>"",
    "ACCOUNT_SID"=>"",
    "API_KEY"=>"",
    "AUTH_TOKEN"=>"",
    "services"=>[
      "verify"=>[
        "NAME"=>"",
        "SID"=>""
      ]
    ]
  ];

  static $paystack = [
    "DEFAULT"=>"SK_LIVE",
    "SK_LIVE"=>"sk_live_db7d6f5211998cab465a1578a465bd35476746ea",
    "PK_LIVE"=>"sk_live_db7d6f5211998cab465a1578a465bd35476746ea",
    "SK_TEST"=>"",
    "PK_TEST"=>"",
  ];

  static $recaptcha = [
    "PUBLIC_KEY"=>"6Le75kMaAAAAAH3Bev51t1Dmx8BVvh1JMCre6G7n",
    "SECKRET_KEY"=>"6Le75kMaAAAAAA9Gu9-pLRmBRcj_E2o6zNrcu1vd"
  ];

  static $exchange = [
    "API_KEY" => "3cd3572265af84592cdde1627551dce5f903"
  ];
  static $coinlib = [
    "API_KEY" => "8ef3daa47313c8e0"
  ];
  // static $MINIMUM_DEPOSIT_AMOUNT = 1000;
  // static $MINIMUM_WITHDRAWAL_AMOUNT = 1000;
  // static $MAX_BUYCOIN_AMOUNT = 100000;
  // static $DEPOSIT_CHARGES = 100;
  // static $NAIRA_ENTITY = "₦";
  // static $TRANSACTION_TYPE = ["deposit","generate_token","delete_token","withdraw"];
}




 ?>
