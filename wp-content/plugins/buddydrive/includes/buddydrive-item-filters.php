<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * filters wp_upload_dir to replace its datas by buddydrive ones
 * 
 * @param  array $upload_data  wp_upload dir datas
 * @uses   wp_parse_args() to merge datas
 * @return array  $r the filtered array
 */
function buddydrive_temporarly_filters_wp_upload_dir( $upload_data ) {
	$path = buddydrive()->upload_dir;
	$url  = buddydrive()->upload_url;
	
	$buddydrive_args = apply_filters( 'buddydrive_upload_datas', 
		array( 
			'path'    => $path,
			'url'     => $url,
			'subdir'  => false,
			'basedir' => $path,
			'baseurl' => $url,
		) );
	
	$r = wp_parse_args( $buddydrive_args, $upload_data );
	
	return $r;
}


/**
 * filters WordPress mime types
 * 
 * @param  array $allowed_file_types the WordPress mime types
 * @uses   buddydrive_allowed_file_types() to get the option defined by admin 
 * @return array mime types allowed by BuddyDrive
 */
function buddydrive_allowed_upload_mimes( $allowed_file_types ) {

	return buddydrive_allowed_file_types( $allowed_file_types );
}


/**
 * Checks file uploaded size upon user's space left and max upload size
 * 
 * @param  array $file $_FILE array
 * @uses   buddydrive_get_user_space_left() to get user's space left
 * @uses   buddydrive_max_upload_size() to get max upload size
 * @return array $file the $_FILE array with an eventual error
 */
function buddydrive_check_upload_size( $file ) {

	if ( $file['error'] != '0' ) // there's already an error
		return $file;

	// what's left in user's quota ?
	$space_left = buddydrive_get_user_space_left( 'diff' );

	$file_size = filesize( $file['tmp_name'] );

	if ( $space_left < $file_size )
		$file['error'] = sprintf( __( 'Not enough space to upload. %1$s KB needed.', 'buddydrive' ), number_format( ($file_size - $space_left) /1024 ) );
	if ( $file_size > buddydrive_max_upload_size( true ) )
		$file['error'] = sprintf( __('This file is too big. Files must be less than %1$s MB in size.', 'buddydrive' ), buddydrive_max_upload_size() );
	if ( $space_left <= 0 ) {
		$file['error'] = __( 'You have used your space quota. Please delete files before uploading.', 'buddydrive' );
	}

	return $file;
}


/**
 * temporarly filters buddydrive_get_user_space_left to only output the quota with no html tags
 * 
 * @param  string $output html
 * @param  string $quota  the space left without html tags
 * @return string $quota
 */
function buddydrive_filter_user_space_left( $output, $quota ) {
	return $quota;
}


/**
 * filters bp_get_message_get_recipient_usernames if needed
 * 
 * @param  string $recipients the message recipients
 * @uses   friends_get_friend_user_ids() to get the friends list
 * @uses   bp_loggedin_user_id() to get the current logged in user
 * @uses   bp_core_get_username() to get the usernames of the friends.
 * @return string list of the usernames of the friends of the loggedin users
 */
function buddydrive_add_friend_to_recipients( $recipients ) {
	
	if ( empty( $_REQUEST['buddyitem'] ) )
		return $recipients;
	
	$ids = friends_get_friend_user_ids( bp_loggedin_user_id() );
	
	$usernames = false;
	
	foreach ( $ids as $id ) {
		$usernames[] = bp_core_get_username( $id );
	}
	
	if ( is_array( $usernames ) )
		return implode( ' ', $usernames );
		
	else
		return $recipients;
	
}


/**
 * removes the BuddyDrive directory page from wp header menu
 * 
 * @param  array $args the menu args
 * @uses   buddydrive_get_slug() to get the slug of the BuddyDrive directory page
 * @uses   bp_core_get_directory_page_ids() to get an array of BP Components page ids
 * @return args  $args with a new arg to exclude BuddyDrive page.
 */
function buddydrive_hide_item( $args ) {

	$buddydrive_slug = buddydrive()->buddydrive_slug;
	
	$directory_pages = bp_core_get_directory_page_ids();
	
	if ( empty( $args['exclude'] ) )
		$args['exclude'] = $directory_pages[$buddydrive_slug];
	else
		$args['exclude'] .= ',' . $directory_pages[$buddydrive_slug];

	return $args;
}
add_filter( 'wp_page_menu_args', 'buddydrive_hide_item', 20, 1 );

/**
 * Prevent BuddyDrive directory page from showing in the Pages meta box of the Menu Administration screen.
 *
 * @since BuddyDrive (1.2.0)
 *
 * @uses bp_is_root_blog() checks if current blog is root blog.
 * @uses buddypress() gets BuddyPress main instance
 *
 * @param object $object The post type object used in the meta box
 * @return object The $object, with a query argument to remove register and activate pages id.
 */
function buddydrive_hide_from_nav_menu_admin( $object = null ) {

	// Bail if not the root blog
	if ( ! bp_is_root_blog() ) {
		return $object;
	}

	if ( 'page' != $object->name ) {
		return $object;
	}

	$bp = buddypress();

	if ( ! empty( $bp->pages->buddydrive ) ) {
		$object->_default_query['post__not_in'] = array( $bp->pages->buddydrive->id );
	}

	return $object;
}
add_filter( 'nav_menu_meta_box_object', 'buddydrive_hide_from_nav_menu_admin', 11, 1 );

/**
 * Adds buddydrive's slug to the groups forbidden names
 *
 * @since  version 1.1
 * 
 * @param  array  $names the groups forbidden names
 * @uses buddydrive_get_slug() to get the plugin's slug
 * @return array        the same names + buddydrive's slug.
 */
function buddydrive_add_to_group_forbidden_names( $names = array() ) {

	$names[] = buddydrive_get_slug();
	return $names;
}
add_filter( 'groups_forbidden_names', 'buddydrive_add_to_group_forbidden_names' );
