<?php

include 'lib/include.php';

$oauth = NOAuth::get_for_twitter();

if (isset($_GET['oauth_token'])) {
	
	//step 3: receive an authorized token back
	$tok = $_GET['oauth_token'];
	$ver = $_GET['oauth_verifier'];

	//step 4: exchange request token for access token
	$access_token = $oauth->get_access_token(r, rts, $ver);
	
	echo "THANK YOU FOR LOGGING IN WITH TWITTER.";
	print_r($access_token)
	
} else {
	
	//step 1: get a token
	$request_token = $oauth->get_request_token();
	print_r($request_token);
	if (!empty($request_token)) {
		//now, save the request_token and request_token_secret, we need is in step 4
		$_SESSION['r'] = $request_token['oauth_token'];
		$_SESSION['rts'] = $request_token['oauth_secret'];
		//step 2: get the user to authorize the usage of this token
		$oauth->redirect_to_authorize($request_token['oauth_token']);
	} else {
		echo "Twitter login disabled, could not get request token.";
	}

}
?>