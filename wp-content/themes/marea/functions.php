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
require( get_template_directory() . '/../marea/inc/custom-header.php' );
  // Includes Twitter Bootstrap.
function wp_bootstrap_scripts_styles() {
  // Loads Bootstrap minified JavaScript file.
  wp_enqueue_script('bootstrapjs', get_template_directory_uri() . '/../marea/js/bootstrap.min.js', array('jquery'),'3.0.0', true );
  // Loads Bootstrap minified CSS file.
  wp_enqueue_style('bootstrapwp', get_template_directory_uri() . '/../marea/css/bootstrap.min.css', array( ), '3.0.0', false );
  // Loads holder.js.
  wp_enqueue_script('holder', get_template_directory_uri() . '/../marea/js/holder.js', array ('jquery'), null, true );
}
add_action('wp_enqueue_scripts', 'wp_bootstrap_scripts_styles');

  // Register Custom Navigation Walker
require_once('wp_bootstrap_navwalker.php'); ?>
