<?php
// ----------------- Menus w/fallback for older WP versions --------------------
//
register_nav_menu( 'primary', __( 'Primary Menu', 'brilliante_layout' ) );
// Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
function brilliante_layout_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'brilliante_layout_page_menu_args' );
// ----------------- Widget-Ready Sidebar ---------------------------------------
//
if ( function_exists('register_sidebar') )
    register_sidebar(array(
				'name' => __( 'Sidebar', 'brilliante_layout' ),
				'id' => 'sidebar',
        'before_widget' => '<li class="sidebar-widget"><div class="sidebar-widget" id="%1$s">',
        'after_widget' => '</div></li>',
        'before_title' => '<h2 class="sidebar-widget"><span>',
        'after_title' => '</span></h2>',
    ));
// ----------------- Widget-Ready Footers ----------------------------------------
//
if ( function_exists('register_sidebar') )
    register_sidebars(3, array(
				'name'=>'Footer%d',
				'id' => 'footer %d',
        'before_widget' => '<div class="footer-widget">',
        'after_widget' => '</div>',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
    ));
// ----------------- Post Featured Images support -------------------------------
// Watch out for the array( 'post','slides' ) in here...
if ( function_exists( 'add_theme_support' ) ) { // Added in 2.9
	add_theme_support( 'post-thumbnails', array( 'post','slides' ) ); // Add featured images to posts
	set_post_thumbnail_size( 140, 140, true ); // Normal post thumbnails
	add_image_size( 'single-post-thumbnail', 542,220, true ); // Single Post thumbnail size
}
// ----------------- Disable the admin bar in 3.1 -------------------------------
//show_admin_bar( false );
?>
<?php
include 'lib/twitter-widget-pro.php';
include 'lib/html-bios.php';
include 'lib/author-bio-widget.php';
include 'lib/flickr-thumbs-widget.php';
?>
<?php
/*******************************
  THEME OPTIONS PAGE
********************************/

add_action('admin_menu', 'brilliante_layout_theme_page');
function brilliante_layout_theme_page ()
{
	if ( count($_POST) > 0 && isset($_POST['brilliante_layout_settings']) )
	{
		$options = array ('twitter_user','flickr_user');

		foreach ( $options as $opt )
		{
			delete_option ( 'brilliante_layout_'.$opt, $_POST[$opt] );
			add_option ( 'brilliante_layout_'.$opt, $_POST[$opt] );	
		}			

	}
	add_menu_page(__('Brilliante Options'), __('Brilliante Options'), 'edit_themes', basename(__FILE__), 'brilliante_layout_settings');
	add_submenu_page(__('Brilliante Options'), __('Brilliante Options'), 'edit_themes', basename(__FILE__), 'brilliante_layout_settings');
}
function brilliante_layout_settings()
{?>
<div class="wrap">
	<h2>Brilliante Layout Options Panel</h2>

<form method="post" action="">

	<fieldset style="border:1px solid #ddd; padding-bottom:20px; margin-top:20px;">
	<legend style="margin-left:5px; padding:0 5px; color:#2481C6;text-transform:uppercase;"><strong>Social Links</strong></legend>
		<table class="form-table">
		<tr valign="top">
			<th scope="row"><label for="twitter_user">Twitter Username (without @)</label></th>
			<td>
				<input name="twitter_user" type="text" id="twitter_user" value="<?php echo get_option('brilliante_layout_twitter_user'); ?>" class="regular-text" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="flickr_user">Flickr Username</label></th>
			<td>
				<input name="flickr_user" type="text" id="flickr_user" value="<?php echo get_option('brilliante_layout_flickr_user'); ?>" class="regular-text" />
			</td>
		</tr>
    </table>
    </fieldset>
		<p class="submit">
		<input type="submit" name="Submit" class="button-primary" value="Save Changes" />
		<input type="hidden" name="brilliante_layout_settings" value="save" style="display:none;" />
		</p>
</form>
</div>
<?php }
?>
<?php
// ----------------- Remove code from the <head> --------------------------------
//
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'wlwmanifest_link');
function hcwp_remove_version() {return '';}
add_filter('the_generator', 'hcwp_remove_version');




// ----------------- jQuery from CDN --------------------------------
//

if (!is_admin()) add_action("wp_enqueue_scripts", "my_jquery_enqueue", 11);
function my_jquery_enqueue() {
   wp_deregister_script('jquery');
   wp_register_script('jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js", false, null);
   wp_enqueue_script('jquery');
}

// ----------------- Register scripts --------------------------------
//

if ( ! function_exists( 'brillante_register_scripts' ) ) :

function brillante_register_scripts() {
	wp_register_script( 'slides', get_template_directory_uri() . '/js/slides.min.jquery.js', array( 'jquery' ), null );
	wp_register_script( 'prefixfree', get_template_directory_uri() . '/js/prefixfree.min.js', array( 'jquery' ), null );
	wp_register_script( 'func', get_template_directory_uri() . '/js/func.js', array( 'jquery' ), null );
}
endif;

add_action( 'init', 'brillante_register_scripts' );

// ----------------- Enqueue scripts --------------------------------
//

if ( ! function_exists( 'brillante_enqueue_scripts' ) ) :

function brillante_enqueue_scripts() {
	wp_enqueue_script( 'slides' );
	wp_enqueue_script( 'prefixfree' );
	wp_enqueue_script( 'func' );
}
endif;

add_action( 'wp_enqueue_scripts', 'brillante_enqueue_scripts' );



?>