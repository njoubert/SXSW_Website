<?

require_once('twitteroauth.php');
require_once('config.php');
require_once('sqlquery.php');

function get_user($user_id) {
	$DB = new SQLQuery();
	$DB->chooseTable(DB_USERS_TABLE);
	$user = $DB->select($user_id);
	if (empty($user)) {
		return NULL;
	} else {
		return $user[0]["users"];
	}
}

function record_vote($user_id, $song_id) {
	$DB = new SQLQuery();
	$DB->chooseTable(DB_VOTES_TABLE);
	
	
}

function post_on_behalf_of($user, $message) {
	if (!empty($user['tw_oauth_token']) && !empty($user['tw_oauth_token_secret'])) {
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $user['tw_oauth_token'], $user['tw_oauth_token_secret']);
		$resp = $connection->post('statuses/update', array('status' => $message));
		return (200 == $connection->http_code);
	}
}

?>