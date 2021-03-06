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
		// Create a 'namespace' for our app
		var djdp = {};
		
		djdp.searchBarDiv = false;
		
		djdp.moveSearchBar = function() {
			if(djdp.searchBarDiv) {
				var translate = window.scrollY;
				djdp.searchBarDiv.style['-webkit-transform'] = 'translateY(' + translate + 'px)';
			}
		};
		
		// Assign on scroll listener that will reposition the search bar
		window.onscroll = function() {
			djdp.moveSearchBar();
		};
		
		$(document).ready(function() {
			// Init menuDiv
			djdp.searchBarDiv = $('#header')[0];
			
			// Assign on-click behaviour to vote button
			$('.vote-btn').bind('click', function() {
				var el = $(this);
				var song_id = el.attr('data-song');
				if(confirm('You are votin\' for ' + song_id)) {
					el.html('Voted');
				};
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