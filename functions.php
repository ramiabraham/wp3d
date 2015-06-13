<?php
/**
 * wp3d functions and definitions
 *
 * @package wp3d
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'wp3d_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function wp3d_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on wp3d, use a find and replace
	 * to change 'wp3d' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'wp3d', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'wp3d' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'wp3d_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // wp3d_setup
add_action( 'after_setup_theme', 'wp3d_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function wp3d_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'wp3d' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'wp3d_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function wp3d_scripts() {
	wp_enqueue_style( 'wp3d-style', get_stylesheet_uri() );

	wp_enqueue_script( 'wp3d-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'wp3d-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'wp3d_scripts' );

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/*

DDDDDDDDDDDDD      EEEEEEEEEEEEEEEEEEEEEEMMMMMMMM               MMMMMMMM     OOOOOOOOO
D::::::::::::DDD   E::::::::::::::::::::EM:::::::M             M:::::::M   OO:::::::::OO
D:::::::::::::::DD E::::::::::::::::::::EM::::::::M           M::::::::M OO:::::::::::::OO
DDD:::::DDDDD:::::DEE::::::EEEEEEEEE::::EM:::::::::M         M:::::::::MO:::::::OOO:::::::O
  D:::::D    D:::::D E:::::E       EEEEEEM::::::::::M       M::::::::::MO::::::O   O::::::O
  D:::::D     D:::::DE:::::E             M:::::::::::M     M:::::::::::MO:::::O     O:::::O
  D:::::D     D:::::DE::::::EEEEEEEEEE   M:::::::M::::M   M::::M:::::::MO:::::O     O:::::O
  D:::::D     D:::::DE:::::::::::::::E   M::::::M M::::M M::::M M::::::MO:::::O     O:::::O
  D:::::D     D:::::DE:::::::::::::::E   M::::::M  M::::M::::M  M::::::MO:::::O     O:::::O
  D:::::D     D:::::DE::::::EEEEEEEEEE   M::::::M   M:::::::M   M::::::MO:::::O     O:::::O
  D:::::D     D:::::DE:::::E             M::::::M    M:::::M    M::::::MO:::::O     O:::::O
  D:::::D    D:::::D E:::::E       EEEEEEM::::::M     MMMMM     M::::::MO::::::O   O::::::O
DDD:::::DDDDD:::::DEE::::::EEEEEEEE:::::EM::::::M               M::::::MO:::::::OOO:::::::O
D:::::::::::::::DD E::::::::::::::::::::EM::::::M               M::::::M OO:::::::::::::OO
D::::::::::::DDD   E::::::::::::::::::::EM::::::M               M::::::M   OO:::::::::OO
DDDDDDDDDDDDD      EEEEEEEEEEEEEEEEEEEEEEMMMMMMMM               MMMMMMMM     OOOOOOOOO

   SSSSSSSSSSSSSSS TTTTTTTTTTTTTTTTTTTTTTTUUUUUUUU     UUUUUUUUFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF
 SS:::::::::::::::ST:::::::::::::::::::::TU::::::U     U::::::UF::::::::::::::::::::FF::::::::::::::::::::F
S:::::SSSSSS::::::ST:::::::::::::::::::::TU::::::U     U::::::UF::::::::::::::::::::FF::::::::::::::::::::F
S:::::S     SSSSSSST:::::TT:::::::TT:::::TUU:::::U     U:::::UUFF::::::FFFFFFFFF::::FFF::::::FFFFFFFFF::::F
S:::::S            TTTTTT  T:::::T  TTTTTT U:::::U     U:::::U   F:::::F       FFFFFF  F:::::F       FFFFFF
S:::::S                    T:::::T         U:::::D     D:::::U   F:::::F               F:::::F
 S::::SSSS                 T:::::T         U:::::D     D:::::U   F::::::FFFFFFFFFF     F::::::FFFFFFFFFF
  SS::::::SSSSS            T:::::T         U:::::D     D:::::U   F:::::::::::::::F     F:::::::::::::::F
    SSS::::::::SS          T:::::T         U:::::D     D:::::U   F:::::::::::::::F     F:::::::::::::::F
       SSSSSS::::S         T:::::T         U:::::D     D:::::U   F::::::FFFFFFFFFF     F::::::FFFFFFFFFF
            S:::::S        T:::::T         U:::::D     D:::::U   F:::::F               F:::::F
            S:::::S        T:::::T         U::::::U   U::::::U   F:::::F               F:::::F
SSSSSSS     S:::::S      TT:::::::TT       U:::::::UUU:::::::U FF:::::::FF           FF:::::::FF
S::::::SSSSSS:::::S      T:::::::::T        UU:::::::::::::UU  F::::::::FF           F::::::::FF
S:::::::::::::::SS       T:::::::::T          UU:::::::::UU    F::::::::FF           F::::::::FF
 SSSSSSSSSSSSSSS         TTTTTTTTTTT            UUUUUUUUU      FFFFFFFFFFF           FFFFFFFFFFF

*/


/**
 * Conditionally enqueues scripts and styles for various page templates
 * @return mixed
 */

add_action( 'wp_enqueue_scripts', 'ra_3d_demo_scripts' );

function ra_3d_demo_scripts() {

	/**
	 * Localize a few variables for use.
	 */

	// General site info ( wp3d_globals )

	$wp3d_theme_variables = array(
	    'wp3d_theme_dir' => get_template_directory_uri(),
	    'wp3d_site_name' => get_bloginfo( 'name' )
	);

	// Panorama fields ( panorama_globals )

	$wp3d_panorama_variables = array(
	    'exterior_panorama_image_map' 	=> get_field( 'exterior_panorama_image_map' ),
	    'modal_1_title' 			  	=> get_field( 'modal_1_title' ),
	    'modal_1_description'		  	=> get_field( 'modal_1_description' ),
	    'modal_1_image' 			  	=> get_field( 'modal_1_image' ),
	    'modal_2_title' 			  	=> get_field( 'modal_2_title' ),
	    'modal_2_description' 		  	=> get_field( 'modal_2_description' ),
	    'modal_2_image' 			  	=> get_field( 'modal_2_image' ),
	    'interior_panorama_image_map_1' => get_field( 'interior_panorama_image_map_1' ),
	    'interior_panorama_image_map_2' => get_field( 'interior_panorama_image_map_2' ),
	    'interior_modal_1_title' 		=> get_field( 'interior_modal_1_title' ),
	    'interior_modal_1_description' 	=> get_field( 'interior_modal_1_description' ),
	    'interior_modal_1_image' 		=> get_field( 'interior_modal_1_image' ),
	    'interior_modal_2_title' 		=> get_field( 'interior_modal_2_title' ),
	    'interior_modal_2_description' 	=> get_field( 'interior_modal_2_description' ),
	    'interior_modal_2_image' 		=> get_field( 'interior_modal_2_image' ),
	    'interior_modal_3_title' 		=> get_field( 'interior_modal_3_title' ),
	    'interior_modal_3_description' 	=> get_field( 'interior_modal_3_description' ),
	    'interior_modal_3_image' 		=> get_field( 'interior_modal_3_image' )
	);

	// Load the Earth template styles and scripts.

	$earth_dir 	  = get_template_directory_uri() . '/inc/earth/';
	$earth_js_dir = get_template_directory_uri() . '/inc/earth/js/';

	wp_register_style( 'earth-styles', 			$earth_dir . 'css/earth.css' );
	wp_register_style( 'earth-lightbox-styles',  $earth_dir . 'css/lightbox.css' );

	wp_register_script( 'three', 			$earth_js_dir . 'three.r69.min.js',  array( 'jquery' ), null, false );
	wp_register_script( 'jquery-easing', 'http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js', array( 'jquery' ), null, false );
	wp_register_script( 'orbit-controls', 	$earth_js_dir . 'orbit_controls.js', array( 'jquery' ), null, false );
	wp_register_script( 'label-manager', 	$earth_js_dir . 'label_manager.js',  array( 'jquery', 'three' ), null, false );
	wp_register_script( 'curve', 			$earth_js_dir . 'curve.js', 		 array( 'jquery', 'three' ), null, false );
	wp_register_script( 'globe-lightbox', 	$earth_js_dir . 'globe_lightbox.js', array( 'jquery', 'three' ), null, false );
	wp_register_script( 'globe', 			$earth_js_dir . 'globe.js', 		 array( 'jquery', 'three' ), null, false );

	if ( is_page_template( 'page-earth.php' ) ) {

		wp_enqueue_style( 'earth-styles' );
		wp_enqueue_style( 'earth-lightbox-styles' );

		wp_enqueue_script( 'jquery-easing' );

		wp_enqueue_script( 'three' );
		wp_enqueue_script( 'orbit-controls' );
		wp_enqueue_script( 'label-manager' );
		wp_enqueue_script( 'curve' );
		wp_enqueue_script( 'globe-lightbox' );
		wp_enqueue_script( 'globe' );

		wp_localize_script( 'globe', 'wp3d_globals', $wp3d_theme_variables );

	}

	// Load the Panorama template styles and scripts.

	$panorama_dir 	  = get_template_directory_uri() . '/inc/panorama/';
	$panorama_js_dir  = get_template_directory_uri() . '/inc/panorama/js/';

	wp_register_style( 'panorama-styles', $panorama_dir . 'css/style.css' );

	wp_register_script( 'three-panorama',  $panorama_js_dir . 'three.min.js',  				 array( 'jquery' ), null, false );
	wp_register_script( 'panorama-info',  $panorama_js_dir . 'info.js',  				 array( 'jquery' ), null, false );
	wp_register_script( 'detector', 	  $panorama_js_dir . 'detector.js',  			 array( 'jquery', 'three-panorama' ), null, false );
	wp_register_script( 'request-frame',  $panorama_js_dir . 'RequestAnimationFrame.js', array( 'jquery', 'three-panorama' ), null, false );
	wp_register_script( 'tween', 		  $panorama_js_dir . 'Tween.js', 				 array( 'jquery', 'three-panorama' ), null, false );
	wp_register_script( 'panorama-maps',  $panorama_js_dir . 'maps.js', 				 array( 'jquery', 'three-panorama' ), null, false );


	if ( is_page_template( 'page-panorama.php' ) ) {

		wp_enqueue_style( 'panorama-styles' );

		wp_enqueue_script( 'detector' );
		wp_enqueue_script( 'three-panorama' );
		wp_enqueue_script( 'panorama-info' );
		wp_enqueue_script( 'request-frame' );
		wp_enqueue_script( 'tween' );
		wp_enqueue_script( 'panorama-maps' );

		wp_localize_script( 'panorama-maps', 'panorama_globals', $wp3d_panorama_variables );

	}

	$front_page_dir 	  = get_template_directory_uri() . '/inc/front-page/';
	$front_page_js_dir    = get_template_directory_uri() . '/inc/front-page/js/';

	wp_register_style( 'panorama-styles', $front_page_dir . 'css/style.css' );

	// The front-page template styles and scripts.

	wp_register_script( 'front-page-font',  $front_page_js_dir . 'droid_serif_bold.typeface.js', array( 'jquery' ), null, false );
	wp_register_script( 'front-page',  		$front_page_js_dir . 'front-page.js', array( 'front-page-font' ), null, false );

	if ( is_front_page() ) {

		wp_enqueue_script( 'three' );
		wp_enqueue_script( 'front-page-font' );
		wp_enqueue_script( 'front-page' );

	}

}