<?php
	$filename = "songs.json";
	include('lib/config.php');
	include('lib/sqlquery.php');
	
	// get contents of a file into a string
	$handle = fopen($filename, "r");
	$contents = fread($handle, filesize($filename));
	fclose($handle);
	
	if ($contents) {
		$songs = json_decode($contents, true);
		if (array_key_exists('songs', $songs) and is_array($songs['songs'])) {
			$DB = new SQLQuery();
			$DB->chooseTable(DB_SONGS_TABLE);
			foreach($songs['songs'] as $song) {
				$dataInsert = array();
				$dataInsert['id'] = $song['id'];
				$dataInsert['title'] = $song['title'];
				$dataInsert['artist'] = $song['artist'];
				$dataInsert['album'] = $song['album'];
				$dataInsert['length'] = $song['length'];
				$dataInsert['filename'] = $song['filename'];
				$DB->addItemsArray($dataInsert);
				echo "Added " . $song['title'] . " by " . $song['artist'] . " to the database.<br />";
			}
		}
	}
?>