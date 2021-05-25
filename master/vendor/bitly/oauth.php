<?php

	$code = $_REQUEST['code'];

	// Enter your Client ID, Client Secret and Redirect URI
	$client_id ="de988fb7d45e3918a2e7c3100750d17c685c929e";
	$client_secret = "35cc5b77520f99b3f159a7582e8dcdc1056ead3d";
	$redirect_uri = "https://mylelojobs.com/admin/vendor/bitly/oauth.php"; //The redirect_uri is just pointing back to the location of this file

	$uri = "https://api-ssl.bitly.com/oauth/access_token";

	//POST to the bitly authentication endpoint
	$params = array();
    	$params['client_id'] = $client_id;
    	$params['client_secret'] = $client_secret;
    	$params['code'] = $code;
    	$params['redirect_uri'] = $redirect_uri;

	$output = "";
  	$params_string = "";
  	foreach ($params as $key=>$value) { $params_string .= $key.'='.$value.'&'; }
  		rtrim($params_string,'&');
  	try {
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $uri);
		curl_setopt($ch,CURLOPT_POST, count($params));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $params_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $output = curl_exec($ch);
  	} catch (Exception $e) {
  	}
	// Returns a URL encoded string in the format of "access_token=%s&login=%s&apiKey=%s"

	// parse_str() creates variables $access_token, $login, $apiKey (http://php.net/manual/en/function.parse-str.php)
	parse_str($output);

	//the OAuth access token for specified user
	echo "OAuth user access token= > ". $access_token."\n";

	//the end-user's bitly username
	echo "Login = > ". $login."\n";

	echo "API key => ". $apiKey."\n";
	//API keys are deprecated. This value will be removed in the future.

?>
