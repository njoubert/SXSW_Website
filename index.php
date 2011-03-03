<?php
session_start();
require_once('lib/include.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>DJDP3000</title>
	<meta name="generator" content="TextMate http://macromates.com/">
	<meta name="author" content="Niels Joubert, Gleb Denisov">
</head>
<body>

<?php
//LOG IN PAGE
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {

?>
<h1>Sign in to Auto DJ Madness</h1>
<a href="twitter.php"><img src="static/images/twitter_darker.png"></a>

<?php
} else {
?>

<?php
//ALREADY LOGGED IN PAGE
$access_token = $_SESSION['access_token'];
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
$content = $connection->get('account/verify_credentials');
?>
<h1>WELCOME!</h1>
<p><a href="./clearsessions.php">Log out</a></p>

<p><h1>Hello <?php echo $content->name; ?>, your twitter id is <?php echo $_SESSION['twitter_uid']?></h1></p>
<p><h1><a href="vote_announce.php">VOTE FOR THIS SHIT NOW! WE WILL TWEET IT LIKE A BOSS.</a></h1></p>
<?php

print_r($content);

}
?>

</body>
</html>
