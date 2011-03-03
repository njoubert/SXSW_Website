<?php
session_start();
require_once('lib/include.php');

if (isset($_REQUEST['oauth_token'])) {
	
	if ($_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
		$_SESSION['oauth_status'] = 'oldtoken';
		header('Location: ./clearsessions.php');
	}
	
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
	$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
	$_SESSION['access_token'] = $access_token;
	unset($_SESSION['oauth_token']);
	unset($_SESSION['oauth_token_secret']);

	$authedconn = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
	$content = $authedconn->get('account/verify_credentials');
	
	if (200 == $connection->http_code) {
		//TODO(njoubert): Here we should pass it off to an "insert into local database and set up our own session" thingy
		//if the user exists, update. if not, create.
		$DB = new SQLQuery();
		$DB->chooseTable(DB_USERS_TABLE);
		$a = "`tw_id` = `" . $content->id . "`";
		echo "<pre>" . $a . "</pre>";
		$user = $DB->selectWhatWhere("*", $a);
		
		
		print_r($user);
		
		$_SESSION['status'] = 'verified';
		$_SESSION['twitter_uid'] = $content->id;
		//header('Location: ./index.php');
	} else {
		header('Location: ./clearsessions.php');
	}
	
} else {
	
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
	$request_token = $connection->getRequestToken(OAUTH_CALLBACK);
	$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
	$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
	switch ($connection->http_code) {
		case 200:
		/* Build authorize URL and redirect user to Twitter. */
			$url = $connection->getAuthorizeURL($token);
			header('Location: ' . $url); 
			break;
		default:
	    	/* Show notification if something went wrong. */
	    	echo 'Could not connect to Twitter. Refresh the page or try again later.';
	}

}
?>