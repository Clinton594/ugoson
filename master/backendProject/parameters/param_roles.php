<?php
$thisyear = date("Y");
$roles = [
  "Home" => [
    "icon"  => "home",
    "links" => [
      [
        "title" => "Dashboard",
        "url"   => "custom-view/dashboard-mini"
      ],
      [
        "title" => "News",
        "url"   => "content-view/posts"
      ]
    ]
  ],
  "Organization Setup" => [
    "icon"  => "settings",
    "links" => [
      [
        "title" => "Settings",
        "url"   => "form-view/organization"
      ],
      [
        "title" => "Role",
        "url"   => "content-view/role"
      ],
    ]
  ],
  "Users" => [
    "icon"  => "person",
    "links" => [
      [
        "title" => "Administrators",
        "url"   => "content-view/users"
      ],
      [
        "title" => "Members",
        "url"   => "content-view/members"
      ]
    ]
  ],
  "Wallets" => [
    "icon"  => "widgets",
    "links" => [
      [
        "title" => "Tokens",
        "url"  => "content-view/tokens"
      ],
      [
        "title" => "Groups",
        "url"  => "content-view/teams"
      ],
    ]
  ],
  "Transactions" => [
    "icon"  => "payment",
    "links" => [
      [
        "title" => "Deposit Transactions",
        "url"  => "content-view/deposits"
      ],
      [
        "title" => "Withdrawal Transactions",
        "url"  => "content-view/withdrawals"
      ],
      [
        "title" => "All Transactions",
        "url"  => "content-view/transactions"
      ]
    ]
  ],
];
?>
