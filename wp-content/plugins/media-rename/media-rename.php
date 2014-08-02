<?php

/*
Plugin Name: Media Rename
Plugin URI: http://wordpress.org/extend/plugins/media-rename/
Description: The Media Rename plugin allows you to simply rename your media files, once uploaded. <strong>If you like this plugin, please consider a <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8DAVXJ35WQSRE" target="_blank">donation</a>.</strong>
Version: 3.2.3
Author: ShadowsDweller
Author URI: http://htmlexpert.net
License: GPL2
*/

defined('ABSPATH') or die();

include_once('class-media-rename.php');

add_action('plugins_loaded', 'media_rename_init');
function media_rename_init() {
	$mr = new Media_Rename;

	add_filter('manage_media_columns', array($mr, 'add_filename_column'));
	add_filter('attachment_fields_to_edit', array($mr, 'add_filename_field'), 10, 2); 

	add_action('load-upload.php', array($mr, 'handle_bulk_rename_form_submit'));
	add_action('admin_notices', array($mr, 'show_bulk_rename_success_notice'));
	add_action('manage_media_custom_column', array($mr, 'add_filename_column_content'), 10, 2);
	add_action('sanitize_file_name_chars', array($mr, 'add_special_chars'), 10, 1);
	add_action('admin_print_scripts', array($mr, 'print_js'));
	add_action('admin_print_styles', array($mr, 'print_css'));
	add_action('wp_ajax_media_rename', array($mr, 'ajax_rename'));
}