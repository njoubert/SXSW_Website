<?php
if (isset($_GET["oauth_token"])) {
	echo "THANK YOU FOR LOGGING IN WITH TWITTER.";
} else {
	echo "Why are you here?";
}
?>