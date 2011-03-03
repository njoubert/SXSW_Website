<?php
session_start();
require_once('lib/include.php');

$access_token = $_SESSION['access_token'];
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

$resp = $connection->post('statuses/update', array('status' => 'I love the twitter api!'));

print_r($resp)

?>