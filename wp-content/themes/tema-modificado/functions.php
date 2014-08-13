<?php 
/**
 * Enqueues scripts and styles for front end.
 *
 * @package WordPress
 * @subpackage blank_bootstrap
 * @since Blank Theme with Bootstrap 1.0
 *
 * @return void
 */
  // Register Theme Features
  // Loads Custom Header setup file (taken from twentytwelve theme).
require( get_template_directory() . '/inc/custom-header.php' );
  // Includes Twitter Bootstrap.
function wp_bootstrap_scripts_styles() {
  // Loads Bootstrap minified JavaScript file.
  wp_enqueue_script('bootstrapjs', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'),'3.0.0', true );
  // Loads Bootstrap minified CSS file.
  wp_enqueue_style('bootstrapwp', get_template_directory_uri() . '/css/bootstrap.min.css', array( ), '3.0.0', false );
  // Loads our main stylesheet.
  wp_enqueue_style('style', get_stylesheet_directory_uri() . '/style.css', array( ));
  // Loads holder.js.
  wp_enqueue_script('holder', get_template_directory_uri() . '/js/holder.js', array ('jquery'), null, true );
}
add_action('wp_enqueue_scripts', 'wp_bootstrap_scripts_styles');
  // Adds the menus
function register_my_menus() {
  register_nav_menus( array(
      'main-menu' => 'Páxina principal',
      'other-menu' => 'Resto das páxinas'
    ) );
}
add_action( 'init', 'register_my_menus' );
  // Register Custom Navigation Walker
require_once('wp_bootstrap_navwalker.php');

/**
 * Register our sidebars and widgetized areas.
 *
 */
function arphabet_widgets_init() {

	register_sidebar( array(
		'name' => 'Barra lateral blog',
		'id' => 'blog_right_1',
		'before_widget' => '<div>',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="rounded">',
		'after_title' => '</h2>',
	) );
}
add_action( 'widgets_init', 'arphabet_widgets_init' );

  //Add thumbnails support.
add_theme_support( 'post-thumbnails' );

  //Add read more link to excerpt.
function new_excerpt_more( $more ) {
	return ' <a class="read-more" href="'. get_permalink( get_the_ID() ) . '">' . __('Máis...', 'your-text-domain') . '</a>';
}
add_filter( 'excerpt_more', 'new_excerpt_more' ); ?>