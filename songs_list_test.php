<?php
	require_once('lib/include.php');
	// Getting list of songs
	$DB = new SQLQuery();
	$DB->chooseTable(DB_SONGS_TABLE);
	$songs = $DB->selectAllOrderBy("artist ASC, album ASC, title ASC");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head> 
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" /> 
	<meta name="apple-mobile-web-app-capable" content="yes" /> 
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
	
	<link rel="stylesheet" type="text/css" href="static/css/reset.css">
	<link rel="stylesheet" type="text/css" href="static/css/songs_list.css">
	
	<title>Auto DJ</title>
	
	<script type="text/javascript" src="static/js/iscroll-min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">
		var menuDiv;
		var menuOffset = -50;
		
		// Called when it's time for the floaty bar to move
		var moveSearchBar = function() {
			if(menuDiv) {
				 var translate = window.scrollY;
				 menuDiv.style['-webkit-transform'] = 'translateY(' + translate + 'px)';
			 } else {
			 	console.log('not ready to scroll yet');
			 }
		};
		
		// Listen for scroll events on the top level window
		/*window.onscroll = function() {
			moveSearchBar();
		};*/
		
		$(document).ready(function() {
			menuDiv = $('#header')[0];
			$('.vote-btn').bind('click', function() {
				var song_id = $(this).attr('data-song');
				$(this).html(song_id);
				//alert('You are votin\' for ' + song_id);
			});
		});
	</script>
</head> 
<body>
	<div id="header">
		<form>
			<input type="text" hint="Filter songs by artist or title" />
		</form>
	</div> 
	<div id="container"> 
		<div id="scroller"> 
			<ul class="songs-list"><?php 
				if (is_array($songs)) {
					$cur_artist = "";
					foreach($songs as $key => $song) {
						if($song['songs']['artist'] != $cur_artist) {
							$cur_artist = $song['songs']['artist'];
							?>
							<li class="separator"><?php echo $song['songs']['artist']; ?></li>
							<?php
						}
						?>
						<li id="song_<?php echo $song['songs']['id']; ?>">
							<div class="vote-btn" href="#" data-song="<?php echo $song['songs']['id']; ?>">Vote</div>
							<div class="album-cover">
							</div>
							<div class="info">
								<p class="title"><?php echo $song['songs']['title']; ?></p>
								<p class="artist"><?php echo $song['songs']['artist']; ?></p>
							</div>
						</li>
						<?php
					}
				}
			?></ul> 
		</div> 
	</div> 
</body>
</html>