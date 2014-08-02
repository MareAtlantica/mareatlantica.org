<?php

class Media_Rename {


	private $is_media_rename_page;
	private $nonce_printed;


	// Initializes the plugin
	function __construct() {
		$post = isset($_REQUEST['post']) ? get_post($_REQUEST['post']) : NULL;
		$is_media_edit_page = $post && $post->post_type == 'attachment' && $GLOBALS['pagenow'] == 'post.php';
		$is_media_listing_page = $GLOBALS['pagenow'] == 'upload.php';
		$this->is_media_rename_page = $is_media_edit_page || $is_media_listing_page;
	}


	// Adds the "Filename" column at the media posts listing page
	function add_filename_column($columns) {
		$columns['filename'] = 'Filename';
		return $columns;
	}


	// Adds the "Filename" column content at the media posts listing page
	function add_filename_column_content($column_name, $post_id) {
		if ($column_name == 'filename') {
			$file_parts = $this->get_file_parts($post_id);
		    echo $this->get_filename_field($post_id, $file_parts['filename'], $file_parts['extension']);
		}
	}


	// Add the "Filename" field to the Media form
	function add_filename_field($form_fields, $post) {
		if (isset($GLOBALS['post']) && $GLOBALS['post']->post_type=='attachment') {
			$file_parts=$this->get_file_parts($GLOBALS['post']->ID);
			$form_fields['mr_filename']=array(
				'label' => __('Filename'),
				'input' => 'html',
				'html' => $this->get_filename_field($GLOBALS['post']->ID, $file_parts['filename'], $file_parts['extension'])
			);
		}
		return $form_fields;
	}


	// Makes sure that the success message will be shown on bulk rename
	function handle_bulk_rename_form_submit() {
		if ( array_search('rename', $_REQUEST) !== FALSE || array_search('rename_retitle', $_REQUEST) !== FALSE ) {
			wp_redirect( add_query_arg( array('renamed' => 1), wp_get_referer() ) );
			exit;
		}
	}


	// Shows bulk rename success notice
	function show_bulk_rename_success_notice() {
		if( isset($_REQUEST['renamed']) ) {
			echo '<div class="updated"><p>Medias successfully renamed!</p></div>';
		}
	}


	// Print the JS code only on media.php and media-upload.php pages
	function print_js() {
		if ($this->is_media_rename_page) {
			wp_enqueue_script('media-rename', plugins_url('js/scripts.js', __FILE__), array('jquery'));

			?>

			<script type="text/javascript">
				MRSettings = {
					'labels': {
						'rename': '<?php echo  __("Rename") ?>',
						'rename_retitle': '<?php echo  __("Rename & Retitle") ?>'
					}
				};
			</script>

			<?php
		}
	}


	// Print the CSS styles only on media.php and media-upload.php pages
	function print_css() {
		if ($this->is_media_rename_page) {
			wp_enqueue_style('media-rename', plugins_url('css/style.css', __FILE__));
		}
	}


	// Prints the "Filename" textfield
	function get_filename_field($post_id, $filename, $extension) {
		if (!isset($this->nonce_printed)) $this->nonce_printed=0;
		ob_start(); ?>

			<div class="media-rename">
				<input type="text" class="text" value="<?php echo $filename ?>" title="<?php echo $filename ?>" data-post-id="<?php echo $post_id ?>" />
				<span class="file_ext">.<?php echo $extension ?></span>
				<span class="loader"></span>
				<span class="success"></span>
				<span class="error"></span>
				<?php if (!$this->nonce_printed) {
					wp_nonce_field('media_rename', '_mr_wp_nonce');
					$this->nonce_printed++;
				} ?>
			</div>

		<?php return ob_get_clean();
	}


	// Handles AJAX rename queries
	function ajax_rename() {
		if (check_ajax_referer('media_rename', '_wpnonce', 0)) {
			$retitle = $_REQUEST['type'] == 'rename_retitle';
			echo $this->do_rename($_REQUEST['post_id'], $_REQUEST['new_filename'], $retitle);
		}
		exit;
	}


	// Handles the actual rename process
	static function do_rename($attachment_id, $new_filename, $retitle = 0) {

		// Variables
		$post = get_post($attachment_id);
		$file_parts = self::get_file_parts($attachment_id);

		$file_abs_path = get_attached_file($post->ID);
		$file_abs_dir = dirname( $file_abs_path );
		$new_file_abs_path = preg_replace('~[^/]+$~', $new_filename . '.' . $file_parts['extension'], $file_abs_path);

		$file_rel_path = get_post_meta($post->ID, '_wp_attached_file', 1);
		$new_file_rel_path = preg_replace('~[^/]+$~', $new_filename . '.' . $file_parts['extension'], $file_rel_path);

		$uploads_path = wp_upload_dir();
		$uploads_path = $uploads_path['basedir'];

		$searches = self::get_attachment_urls($attachment_id);
		
		//Validations
		if (!$post) return __('Post with ID ' . $attachment_id . ' does not exist!');
		if ($post && $post->post_type != 'attachment') return __('Post with ID ' . $attachment_id . ' is not an attachment!');
		if (!$new_filename) return __('The field is empty!');
		if ( ($new_filename != sanitize_file_name($new_filename)) || preg_match('~[^\p{Common}\p{Latin}]~u', $new_filename) ) return __('Bad characters or invalid filename!');
		if (file_exists($new_file_abs_path)) return __('A file with that name already exists in the containing folder!');
		if (!is_writable($file_abs_dir)) return __('The media containing directory is not writable!');

		// Change the attachment post
		$post_changes['ID'] = $post->ID;
		$post_changes['guid'] = preg_replace('~[^/]+$~', $new_filename . '.' . $file_parts['extension'], $post->guid);
		$post_changes['post_title'] = ($retitle) ? self::filename_to_title($new_filename) : $post->post_title;
		$post_changes['post_name'] = wp_unique_post_slug($new_filename, $post->ID, $post->post_status, $post->post_type, $post->post_parent);
		wp_update_post($post_changes);

		// Change attachment post metas & rename files
		foreach (get_intermediate_image_sizes() as $size) {
			$size_data = image_get_intermediate_size($attachment_id, $size);
			
			@unlink( $uploads_path . DIRECTORY_SEPARATOR . $size_data['path'] );
		}

		if ( !@rename($file_abs_path, $new_file_abs_path) ) return __('File renaming error!');
		update_post_meta($attachment_id, '_wp_attached_file', $new_file_rel_path);
		wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $new_file_abs_path));

		// Replace the old with the new media link in the content of all posts and metas
		$replaces = self::get_attachment_urls($attachment_id);

		$i = 0;
		$post_types = get_post_types();
		unset( $post_types['attachment'] );
		
		while ( $posts = get_posts(array( 'post_type' => $post_types, 'post_status' => 'any', 'numberposts' => 100, 'offset' => $i * 100 )) ) {
			foreach ($posts as &$post) {
				// Updating post content if necessary
				$new_post = array( 'ID' => $post->ID );
				$new_post['post_content'] = str_replace($searches, $replaces, $post->post_content);
				if ($new_post['post_content'] != $post->post_content) wp_update_post($new_post);
				
				// Updating post metas if necessary
				$metas = get_post_meta($post->ID);
				foreach ($metas as $key => $meta) {
					$meta[0] = self::unserialize_deep($meta[0]);
					$new_meta = self::replace_media_urls($meta[0], $searches, $replaces);
					if ($new_meta != $meta[0]) update_post_meta($post->ID, $key, $new_meta, $meta[0]);
				}
			}

			$i++;
		}

		// Updating options if necessary
		$options = self::get_all_options();
		foreach ($options as $option) {
			$option['value'] = self::unserialize_deep($option['value']);
			$new_option = self::replace_media_urls($option['value'], $searches, $replaces);
			if ($new_option != $option['value']) update_option($option['name'], $new_option);
		}

		return 1;
	}


	// Extracts filename and extension form an attachment post
	static function get_file_parts($post_id) {
		preg_match('~([^/]+)\.([^\.]+)$~', get_attached_file($post_id), $file_parts); // extract current filename and extension
		return array(
			'filename' => $file_parts[1],
			'extension' => $file_parts[2]
		);
	}


	// Adds more problematic characters to the "sanitize_file_name_chars" filter
	static function add_special_chars($special_chars) {
		return array_merge($special_chars, array('%', '^'));
	}


	// Returns the attachment URL and sizes URLs, in case of an image
	static function get_attachment_urls($attachment_id) {
		$urls = array( wp_get_attachment_url($attachment_id) );
		if ( wp_attachment_is_image($attachment_id) ) {
			foreach (get_intermediate_image_sizes() as $size) {
				$image = wp_get_attachment_image_src($attachment_id, $size);
				$urls[] = $image[0];
			}
		}

		return array_unique($urls);
	}


	// Convert filename to post title
	static function filename_to_title($filename) {
		return ucwords( preg_replace('~[^a-zA-Z0-9]~', ' ', $filename) );
	}


	// Get all options
	static function get_all_options() {
		return $GLOBALS['wpdb']->get_results("SELECT option_name as name, option_value as value FROM {$GLOBALS['wpdb']->options}", ARRAY_A);
	}


	// Replace the media url and fix serialization if necessary
	static function replace_media_urls($subj, &$searches, &$replaces) {
		$subj = is_object($subj) ? clone $subj : $subj;

		if (!is_scalar($subj) && count($subj)) {
			foreach($subj as &$item) {
				$item = self::replace_media_urls($item, $searches, $replaces);
			}
		} else {
			$subj = is_string($subj) ? str_replace($searches, $replaces, $subj) : $subj;
		}
		
		return $subj;
	}


	// Unserializes a variable until reaching a non-serialized value
	static function unserialize_deep($var) {
		while ( is_serialized($var) ) {
			$var = unserialize($var);
		}

		return $var;
	}


}