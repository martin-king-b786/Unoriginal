<?php
/**
 * @package Unoriginal
 * @version 2.0
 */
/*
Plugin Name: Unoriginal
Description: A carbon copy of Hello Dolly, but with different lyrics. Also; FIRST PLUGIN WOOOOO!
Author: Martin King
Version: 2.0
*/

function hog_get_lyric() {
    $directory = plugin_dir_path( __FILE__ )."lyrics";
    $filecount = 0;
    $files = array_diff(scandir($directory), array('..', '.'));
    $files = array_values($files);
    $filecount = count($files);
    $chosenFile = $files[mt_rand( 0, $filecount - 1 )];
    $chosenSong = ucfirst(str_replace(".txt","",str_replace("-"," ",$chosenFile)));
    $chosen = file_get_contents($directory."/".$chosenFile);
    
    $lyrics = $chosen;
    
    $lyrics = explode(",", nl2br($lyrics));
    $title = array_slice($lyrics,0);
    $lyrics = array_splice($lyrics,1);
    return wptexturize( "<strong>".$chosenSong." - ".$title[0]."</strong>".$lyrics[ mt_rand( 0, count( $lyrics ) - 1 ) ] );
}

// This just echoes the chosen line, we'll position it later
function hog() {
	$chosen = hog_get_lyric();
	echo "<p id='lyric'>$chosen</p>";
}

// Now we set that function up to execute when the admin_notices action is called
add_action( 'admin_notices', 'hog' );

// We need some CSS to position the paragraph
function hog_css() {
	// This makes sure that the positioning is also good for right-to-left languages
	$x = is_rtl() ? 'left' : 'right';

	echo "
	<style type='text/css'>
	#dolly {
		float: $x;
		padding-$x: 15px;
		padding-top: 5px;		
		margin: 0;
		font-size: 11px;
	}
	</style>
	";
}

add_action( 'admin_head', 'hog_css' );

?>
