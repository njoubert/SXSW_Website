<?php
session_start();
require_once('lib/include.php');

$user = array();
if (!empty($_SESSION['user_id'])) {
	$user = get_user($_SESSION['user_id']);
	if (empty($user)) {
		header('Location: ./clearsessions.php');
	}
}

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
//if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
if (empty($_SESSION['user_id'])) {

?>
<h1>Sign in to Auto DJ Madness</h1>
<p><a href="twitter.php"><img src="static/images/twitter_darker.png"></a></p>
<p>Facebook (in dev)</p>
<p>Custom (in dev)</p>
<?php
} else {
?>

<h1>WELCOME!</h1>
<p><a href="./clearsessions.php">Log out</a></p>

<p>Hello <?php echo $user->fname . " " . $user->lname; ?>, your twitter id is <?php echo $user->tw_id; ?></p>
<p>your avatar:<img src="<?php echo $user->avatar; ?>"/>

<?php
}
?>

</body>
</html>
