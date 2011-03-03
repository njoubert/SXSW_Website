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
	
	if (200 == $connection->http_code && !empty($content->id)) {
		//TODO(njoubert): Here we should pass it off to an "insert into local database and set up our own session" thingy
		//if the user exists, update. if not, create.
		$DB = new SQLQuery();
		$DB->chooseTable(DB_USERS_TABLE);
		$DB->toggleDebug();
		$dataInsert = array();
		$fullname = explode(" ", $content->name);
		$fname = "Britney";
		$lname = "Spears";
		if (count($fullname) > 0) { $fname = $fullname[0]; }
		if (count($fullname) > 1) { $lname = $fullname[1]; }
		$dataInsert['tw_id'] = $content->id;
		$dataInsert['fname'] = $fname;
		$dataInsert['lname'] = $lname;
		$dataInsert['tw_name'] = $content->screen_name;
		$dataInsert['avatar'] = $content->profile_image_url;
		$dataInsert['tw_oauth_token'] = $access_token['oauth_token'];
		$dataInsert['tw_oauth_token_secret'] = $access_token['oauth_token_secret'];


		$user = $DB->selectWhatWhere("*", "tw_id = " . $content->id);
		echo "<p>YES</p><p>";
		if (empty($user)) { 
			$user = $DB->addItemsArray($dataInsert);
		} else {
			$user_id = $user[0]["users"]["id"];
			echo "current user " . $user_id . "</p><p>";

			$user = $DB->updateWhatWhereArray2($dataInsert, "id = " . $user_id);
		}
		if (empty($user)) {
			$_SESSION['status'] = 'error';
			$_SESSION['twitter_uid'] = $content->id;
			$_SESSION['user_id'] = $user->id;
		} else {
			$_SESSION['status'] = 'verified';
		}
		echo "</p>";
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
			$url = $connection->getAuthorizeURL($token);
			header('Location: ' . $url); 
			break;
		default:
	    	echo 'Could not connect to Twitter. Refresh the page or try again later.';
	}

}
?>