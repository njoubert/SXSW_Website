<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
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
	<script type="text/javascript">
		var myScroll;
		var a = 0;
		function loaded() {
			setHeight();	// Set the wrapper height.
		 
			myScroll = new iScroll('scroller', {desktopCompatibility:true});
		}
		 
		// Change wrapper height based on device orientation.
		function setHeight() {
			var headerH = document.getElementById('header').offsetHeight,
				wrapperH = window.innerHeight - headerH;
			document.getElementById('container').style.height = wrapperH + 'px';
		}
		 
		// Check screen size on orientation change
		window.addEventListener('onorientationchange' in window ? 'orientationchange' : 'resize', setHeight, false);
		 
		// Prevent the whole screen to scroll when dragging elements outside of the scroller (ie:header/footer).
		// If you want to use iScroll in a portion of the screen and still be able to use the native scrolling, do *not* preventDefault on touchmove.
		document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
		 
		// Load iScroll when DOM content is ready.
		document.addEventListener('DOMContentLoaded', loaded, false);
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
			<ul class="songs_list"><?php 
				for($i=1; $i < 451; $i++) {
					?>
					<li>
						<a class="vote-button" href="#">Vote</a>
						<div class="album-cover">
						</div>
						<div class="info">
							<p class="title">
							Title
							</p>
							<p class="artist">
							Artist Name
							</p>
						</div>
					</li>
					<?php
				}
			?></ul> 
		</div> 
	</div> 
</body>
</html>