<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main Screen Class.
 *
 * @package BuddyDrive Component
 * @subpackage Screens
 * @since 1.2.0
 */
class BuddyDrive_Screens {

	/**
	 * The constructor
	 *
	 * @package BuddyDrive Component
	 * @subpackage Screens
	 * @since 1.2.0
	 */
	public function __construct() {
		$this->setup_globals();
		$this->setup_filters();
		$this->setup_actions();
	}

	/**
	 * Starts the screens class
	 * 
	 * @package BuddyDrive Component
	 * @subpackage Screens
	 * @since 1.2.0
	 */
	public static function manage_screens() {
		$buddydrive = buddydrive();

		if ( empty( $buddydrive->screens ) ) {
			$buddydrive->screens = new self;
		}

		return $buddydrive->screens;
	}

	/**
	 * Set some globals
	 *
	 * @package BuddyDrive Component
	 * @subpackage Screens
	 * @since 1.2.0
	 */
	public function setup_globals() {

		$this->template       = '';
		$this->current_screen = '';

		// Is the current theme BP Default or a child theme of BP Default ?
		$this->is_bp_default = in_array( 'bp-default', array( get_template(), get_stylesheet() ) );

		// Path to the component templates
		$this->template_dir  = buddydrive_get_plugin_dir() . '/templates';
	}

	/**
	 * Set filters
	 *
	 * @package BuddyDrive Component
	 * @subpackage Screens
	 * @since 1.2.0
	 */
	private function setup_filters() {
		if ( bp_is_current_component( 'buddydrive' ) || buddydrive_is_group() ) {
			add_filter( 'bp_located_template',   array( $this, 'template_filter' ), 20, 2 );
			add_filter( 'bp_get_template_stack', array( $this, 'add_to_template_stack' ), 10, 1 );
		}
	}

	/**
	 * Filter the located template
	 * 
	 * @package BuddyDrive Component
	 * @subpackage Screens
	 * @since 1.2.0
	 */
	public function template_filter( $found_template = '', $templates = array() ) {
		$bp = buddypress();

		// Bail if theme has it's own template for content.
		if ( ! empty( $found_template ) )
			return $found_template;

		/**
		 * Current theme do use theme compat, no need to carry on
		 * Retuning false will fire bp_setup_theme_compat action
		 */ 
		if ( $bp->theme_compat->use_with_current_theme )
			return false;

		/**
		 * Current theme is BP Default or a chilf theme of it
		 * 
		 * Let's handle BP Default theme (or child themes)
		 * This theme helped BuddyPress growth, so it desearves
		 * support ;) 
		 */ 
		if ( $this->is_bp_default && bp_is_directory() ) {
			
			foreach ( $templates as $template ) {
				$bp_default_template = $template;

				if ( 'buddydrive.php' == $template ){
					$bp_default_template = str_replace( '.php', '-default.php', $template );
				}

				if ( file_exists( $this->template_dir . '/' . $bp_default_template ) ){
					return $this->template_dir . '/' . $bp_default_template;
				}
			}
		}
		/**
		 * If we're here this means we're probably on the directory in
		 * a Theme that is using it's own BuddyPress support.
		 */  
		if ( bp_is_directory() && ! $this->is_bp_default ) {

			// This happens to work in some BuddyPress standalone theme !!
			bp_theme_compat_reset_post( array(
				'ID'             => $bp->pages->buddydrive->id,
				'post_title'     => $bp->pages->buddydrive->title,
				'post_author'    => 0,
				'post_date'      => 0,
				'post_content'   => '',
				'is_page'        => true,
				'comment_status' => 'closed'
			) );
			
			add_filter( 'the_content', array( $this, 'directory_content' ) );
		}

		return apply_filters( 'buddydrive_load_template_filter', $found_template );
	}

	/**
	 * Add the plugin's template to the end of the BuddyPress templates stack
	 * 
	 * @package BuddyDrive Component
	 * @subpackage Screens
	 * @since 1.2.0
	 */
	public function add_to_template_stack( $templates = array() ) {
		$templates = array_merge( $templates, array( trailingslashit( $this->template_dir ) ) );

		return $templates;
	}

	/**
	 * User's files
	 * 
	 * @package BuddyDrive Component
	 * @subpackage Screens
	 * @since 1.2.0
	 */
	public static function user_files() {

		do_action( 'buddydrive_user_files' );

		// No template file provided as we'll use default members/single/plugins.php
		// for the BuddyDrive explorer
		self::load_template( '', 'user_files' );
	}

	/**
	 * Shared by friends screen
	 * 
	 * @package BuddyDrive Component
	 * @subpackage Screens
	 * @since 1.2.0
	 */
	public static function friends_files() {

		do_action( 'buddydrive_friends_files' );

		// We'll only use members/single/plugins
		self::load_template( '', 'friends_files' );
	}

	/**
	 * load_template()
	 *
	 * Choose the best way to load your plugin's content
	 * 
	 * @package BuddyDrive Component
	 * @subpackage Screens
	 * @since 1.2.0
	 */
	public static function load_template( $template = '', $screen = '' ) {
		$buddydrive = buddydrive();
		/****
		 * Displaying Content
		 */
		$buddydrive->screens->template       = $template;
		$buddydrive->screens->current_screen = $screen;
		
		if ( buddypress()->theme_compat->use_with_current_theme && ! empty( $template ) ) {
			add_filter( 'bp_get_template_part', array( __CLASS__, 'template_part' ), 10, 3 );
		} else {
			// You can only use this method for users profile pages
			if ( ! bp_is_directory() ) {
				
				$buddydrive->screens->template = 'members/single/plugins';
				add_action( 'bp_template_title',   "buddydrive_{$screen}_title"   );
				add_action( 'bp_template_content', "buddydrive_{$screen}_content" );
			}
		}

		bp_core_load_template( apply_filters( "buddydrive_template_{$screen}", $buddydrive->screens->template ) );
	}

	/**
	 * Filter the templates part for user's screens
	 * 
	 * @package BuddyDrive Component
	 * @subpackage Screens
	 * @since 1.2.0
	 */
	public static function template_part( $templates, $slug, $name ) {
		if ( $slug != 'members/single/plugins' ) {
	        return $templates;
		}

		$templates = array( buddydrive()->screens->template . '.php' );

	    return $templates;
	}

	/**
	 * Manage the directory page
	 * 
	 * @package BuddyDrive Component
	 * @subpackage Screens
	 * @since 1.2.0
	 */
	private function setup_actions() {
		add_action( 'bp_screens',            array( $this, 'directory_setup' ) );
		add_action( 'bp_setup_theme_compat', array( $this, 'use_theme_compat' ) );
	}

	/**
	 * Set up BuddyDrive directory page
	 *
	 * @package BuddyDrive Component
	 * @subpackage Screens
	 * @since 1.2.0
	 */
	public function directory_setup() {
		if ( bp_is_current_component( 'buddydrive' ) && ! bp_displayed_user_id() ) {
			// This wrapper function sets the $bp->is_directory flag to true, which help other
			// content to display content properly on your directory.
			bp_update_is_directory( true, 'buddydrive' );

			// Add an action so that plugins can add content or modify behavior
			do_action( 'buddydrive_screen_index' );

			self::load_template( 'buddydrive', 'directory' );
		}
	}

	/**
	 * Theme compat is used
	 * 
	 * @package BuddyDrive Component
	 * @subpackage Screens
	 * @since 1.2.0
	 */
	public function use_theme_compat() {
		if ( ! bp_displayed_user_id() && bp_is_current_component( 'buddydrive' ) ) {
			add_action( 'bp_template_include_reset_dummy_post_data', array( $this, 'directory_dummy_post' ) );
			add_filter( 'bp_replace_the_content',                    array( $this, 'directory_content'    ) );
		}
	}

	/** Directory *************************************************************/

	/**
	 * Update the global $post with directory data
	 *
	 * @package BuddyDrive Component
	 * @subpackage Screens
	 * @since 1.2.0
	 *
	 * @uses bp_theme_compat_reset_post() to reset the post data
	 */
	public function directory_dummy_post() {

		bp_theme_compat_reset_post( array(
			'ID'             => 0,
			'post_title'     => buddydrive_get_name(),
			'post_author'    => 0,
			'post_date'      => 0,
			'post_content'   => '',
			'post_type'      => 'buddydrive_dir',
			'post_status'    => 'publish',
			'is_page'        => true,
			'comment_status' => 'closed'
		) );
	}

	/**
	 * Filter the_content with the Example directory template part
	 *
	 * @package BuddyDrive Component
	 * @subpackage Screens
	 * @since 1.2.0
	 *
	 * @uses bp_buffer_template_part()
	 */
	public function directory_content() {
		bp_buffer_template_part( apply_filters( 'buddydrive_directory_template', 'buddydrive' ) );
	}
}
add_action( 'bp_init', array( 'BuddyDrive_Screens', 'manage_screens' ) );


function buddydrive_user_files_title() {
	buddydrive_item_nav();
}

function buddydrive_user_files_content() {
	?>
	<div id="buddydrive-forms">
		<div class="buddydrive-crumbs"><a href="<?php buddydrive_component_home_url();?>" name="home" id="buddydrive-home"><i class="icon bd-icon-root"></i> <span id="folder-0" class="buddytree current"><?php _e( 'Root folder', 'buddydrive' );?></span></a></div>		
		
		<?php if ( buddydrive_is_user_buddydrive() ):?>
		
			<div id="buddydrive-file-uploader" class="hide">
				<?php buddydrive_upload_form();?>
			</div>
			<div id="buddydrive-folder-editor" class="hide">
				<?php buddydrive_folder_form()?>
			</div>
			<div id="buddydrive-edit-item" class="hide"></div>
		
		<?php endif;?>
		
	</div>

	<?php do_action( 'buddydrive_after_member_upload_form' ); ?>
	<?php do_action( 'buddydrive_before_member_body' );?>

	<div class="buddydrive single-member" role="main">
		<?php bp_get_template_part( 'buddydrive-loop' );?>
	</div><!-- .buddydrive.single-member -->
	
	<?php do_action( 'buddydrive_after_member_body' );
}

function buddydrive_friends_files_title() {
	buddydrive_item_nav();
}

function buddydrive_friends_files_content() {
	?>
	<div id="buddydrive-forms">
		<div class="buddydrive-crumbs"><a href="<?php buddydrive_component_home_url();?>" name="home" id="buddydrive-home"><i class="icon bd-icon-root"></i> <span id="folder-0" class="buddytree current"><?php _e( 'Root folder', 'buddydrive' );?></span></a></div>
	</div>

	<?php do_action( 'buddydrive_after_member_upload_form' ); ?>
	<?php do_action( 'buddydrive_before_member_body' );?>

	<div class="buddydrive single-member" role="main">
		<?php bp_get_template_part( 'buddydrive-loop' );?>
	</div><!-- .buddydrive.single-member -->
	
	<?php do_action( 'buddydrive_after_member_body' );
}

/**
 * Checks if the active theme is  BP Default or a child or a standalone
 *
 * @uses get_stylesheet() to check for BP Default
 * @uses get_template() to check for a Child Theme of BP Default
 * @uses current_theme_supports() to check for a standalone BuddyPress theme
 * @return boolean true or false
 */
function buddydrive_is_bp_default() {
	if ( in_array( 'bp-default', array( get_stylesheet(), get_template() ) ) )
        return true;

    if ( current_theme_supports( 'buddypress') )
    	return true;

    else
        return false;
}


/**
 * Chooses the best way to load BuddyDrive templates
 * 
 * @param string $template the template needed
 * @param boolean $require_once if we need to load it only once or more
 * @uses buddydrive_is_bp_default() to check for BP Default
 * @uses load_template()
 * @uses bp_get_template_part()
 */
function buddydrive_get_template( $template = false, $require_once = true ) {
	if ( empty( $template ) )
		return false;
	
	if ( buddydrive_is_bp_default() ) {

		$template = $template . '.php';

		if ( file_exists( STYLESHEETPATH . '/' . $template ) )
			$filtered_templates = STYLESHEETPATH . '/' . $template;
		else
			$filtered_templates = buddydrive_get_plugin_dir() . '/templates/' . $template;
		
		load_template( apply_filters( 'buddydrive_get_template', $filtered_templates ),  $require_once);
		
	} else {
		bp_get_template_part( $template );
	}
}
