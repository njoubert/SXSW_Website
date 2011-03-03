<?php
session_start();
require_once('lib/include.php');

if (!empty($_SESSION['user_id'])) {
	if (!empty($_REQUEST['vote_msg'])) {
		$vote_msh = $_REQUEST['vote_msg'];
		$user = get_user($_SESSION['user_id']);
		post_on_behalf_of($user, $vote_msg);
	}
} else {
	header('Location: ./clearsessions.php');
}
?>