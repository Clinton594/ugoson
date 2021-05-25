<?php 
function bitlyAccounts(){
  $bitly_accounts = [
     [
      "account" => "info@mylelojobs",
      "client_secrete" => "ef9595f0dd0481b1d880487c882afd939b86d0c9",
      "client_id" => "e6b70a6799502753a7f07f6d93dbdda2813fe910",
      "user_access_token" => "b8960f9fb864b0de1fe7b89b59e8c7043a0db338",
      "user_api_key" => "b8960f9fb864b0de1fe7b89b59e8c7043a0db338",
    ],[
      "account" => "ucmodulus91@gmail.com",
      "client_secrete" => "35cc5b77520f99b3f159a7582e8dcdc1056ead3d",
      "client_id" => "de988fb7d45e3918a2e7c3100750d17c685c929e",
      "user_access_token" => "ac90dee4b3df15f4cc76b457ee619d4f416a7b9d",
      "user_api_key" => "R_a1c1bc8dfffa434cacf1bcde1376133c",
    ],[
      "account" => "info@digitaldreamsng",
      "client_secrete" => "c1d81d00df2a353e486daa00a096deb1b9eb29a7",
      "client_id" => "e2bda6ef9bc38217cf3389e393a30ae27fe1d88d",
      "user_access_token" => "954f1ebbb56d97c027d0029b9a26dfcead78d33a",
      "user_api_key" => "fbf28d3fab3f575ab5bbb1d35d534fd394bbabf8",
    ]
  ];
  return $bitly_accounts;
}

function getBitlyAccount($acc_key = ""){
  $bitly_accounts = bitlyAccounts();
  $key = !is_numeric($acc_key) ? floor(date("d")/10) : $acc_key;
  $return = isset($bitly_accounts[$key]) ? $bitly_accounts[$key] : $bitly_accounts[0];
  $return = json_decode(json_encode($return));
  $return = $acc_key === true ? $key : $return;
  return $return;
}


?>
