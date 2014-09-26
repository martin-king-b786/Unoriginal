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
function plugin_head_files() {
    print "
        <script type='text/javascript' src='".plugin_dir_url(__FILE__)."/js/unoriginal.js'></script>
        <link rel='stylesheet' href='".plugin_dir_url(__FILE__)."/css/unoriginal.css' type='text/css' media='all'>       
    ";
}
add_action('admin_head', 'plugin_head_files');
add_action('wp_head', 'plugin_head_files');
function hog_get_lyric($widget) {
    $directory = plugin_dir_path( __FILE__ )."lyrics";
    $filecount = 0;
    $files = array_diff(scandir($directory), array('..', '.'));
    $files = array_values($files);
    $filecount = count($files);
    $chosenFile = $files[mt_rand( 0, $filecount - 1 )];
    $chosenSong = ucfirst(str_replace(".txt","",str_replace("-"," ",$chosenFile)));
    $chosen = file_get_contents($directory."/".$chosenFile);
    
    $lyrics = $chosen;
    $path = plugin_dir_url(__FILE__);
    $lyrics = explode(",", nl2br($lyrics));
    $title = array_slice($lyrics,0);
    $lyrics = array_splice($lyrics,1);
    $lyricCount= count($lyrics);
    $lineChosen = mt_rand( 0, count( $lyrics ) - 1 );
    
    echo "
        <div id='lyric' class='".$widget."'>
            <div id='lyric-data' data-path='".$path."' data-lyric-song='".$chosenFile."' data-lyric-current='".$lineChosen."' data-lyric-count='".$lyricCount."'></div>
            ".wptexturize( "<h3>".$chosenSong." - ".$title[0])."</h3>";
            if($lineChosen === 0) {}
            else {
                echo "<div class='lyric-prev'><img src='".$path."/img/prev-arrow.png'/></div>";
            }
            echo "<p class='lyric'>".$lyrics[ $lineChosen ]."</p>";
            if($lineChosen == $lyricCount-1) {}
            else {
                echo "<div class='lyric-next'><img src='".$path."/img/next-arrow.png'/></div>";
            }
        echo "</div>";
}

// Now we set that function up to execute when the admin_notices action is called
add_action( 'admin_notices', 'hog_get_lyric' );

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

/* Lyric Widget */
        
    class lyric_widget extends WP_Widget {

        function __construct() {
            parent::__construct(
            // Base ID of your widget
            'lyric_widget', 
            // Widget name will appear in UI
            __('Unoriginal Lyric Widget', 'lyric_widget_domain'), 

            // Widget description
            array( 'description' => __( 'Unoriginal Lyric Widget', 'lyric_widget_domain' ), ) 
            );
        }

        // Creating widget front-end
        // This is where the action happens
        public function widget( $args, $instance ) {
            $title = apply_filters( 'widget_title', $instance['title'] );
    // before and after widget arguments are defined by themes
            echo $args['before_widget'];
            if ( ! empty( $title ) )
            echo $args['before_title'] . $title . $args['after_title'];

            hog_get_lyric('widget');
            
            echo $args['after_widget'];
        }

        // Widget Backend 
        public function form( $instance ) {
            if ( isset( $instance[ 'title' ] ) ) {
                $title = $instance[ 'title' ];
            }
            else {
                $title = __( 'New title', 'lyric_widget_domain' );
            }
            
            // Widget admin form
            ?>
            <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>
            
            <?php 
        }

        // Updating widget replacing old instances with new
        public function update( $new_instance, $old_instance ) {
            $instance = array();
            $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
            return $instance;
        }
    } // Class wpb_widget ends here

        // Register and load the widget
        function lyric_load_widget() {
                register_widget( 'lyric_widget' );
        }
    add_action( 'widgets_init', 'lyric_load_widget' );
?>
