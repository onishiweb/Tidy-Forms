<?php
/**
 * Tidy Forms admin init class
 *
 * @package 	TidyForms
 * @author 		Adam Onishi	<aonishi@wearearchitect.com>
 * @license 	GPL2
 * @copyright 	2015 Adam Onishi
 */

class Tidy_Forms_Admin_Init extends Tidy_Forms_Admin {
	

	private function __construct() {
		// Create post types
		add_action( 'init', array( $this, 'setup_form_post_type') );
		add_action( 'init', array( $this, 'setup_entry_post_type') );
	}

	public static function setup_form_post_type() {

		/**
		 * Custom form post type
		 */
		 $labels = array(
			'name'               => __('Tidy Forms', 'tidyforms'),
			'singular_name'      => __('Form', 'tidyforms'),
			'add_new'            => __('Add New', 'tidyforms'),
			'add_new_item'       => __('Add New Form', 'tidyforms'),
			'edit_item'          => __('Edit Form', 'tidyforms'),
			'new_item'           => __('New Form', 'tidyforms'),
			'all_items'          => __('All Forms', 'tidyforms'),
			'view_item'          => __('View Form', 'tidyforms'),
			'search_items'       => __('Search Forms', 'tidyforms'),
			'not_found'          => __('No forms found', 'tidyforms'), // USE THIS ADAM!
			'not_found_in_trash' => __('No forms found in Trash', 'tidyforms'),
			'parent_item_colon'  => '',
			'menu_name'          => __('Tidy Forms', 'tidyforms'),
		);

		$args = array(
			'labels'              => $labels,
			'description'         => 'Tidy Forms admin section',
			'public'              => true,
			'capability_type'     => 'post',
			'has_archive'         => false,
			'hierarchical'        => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'menu_position'       => 100,
			'menu_icon'           => 'dashicons-feedback',
			'supports'            => array( 'title', 'author', 'revisions' ),
		);

		register_post_type( 'tidy_form', $args );
	}

	public static function setup_entry_post_type() {

		/**
		 * Entries post type
		 */
		$labels = array(
			'name'               => __('Entries', 'tidyforms'),
			'singular_name'      => __('Entry', 'tidyforms'),
			'add_new'            => __('Add New', 'tidyforms'),
			'add_new_item'       => __('Add New Entry', 'tidyforms'),
			'edit_item'          => __('Edit Entry', 'tidyforms'),
			'new_item'           => __('New Entry', 'tidyforms'),
			'all_items'          => __('All Entries', 'tidyforms'),
			'view_item'          => __('View Entry', 'tidyforms'),
			'search_items'       => __('Search Entries', 'tidyforms'),
			'not_found'          => __('No entries found', 'tidyforms'), // USE THIS ADAM!
			'not_found_in_trash' => __('No entries found in Trash', 'tidyforms'),
			'parent_item_colon'  => '',
			'menu_name'          => __('Entries', 'tidyforms'),
		);

		$args = array(
			'labels'              => $labels,
			'description'         => 'Tidy Form entries',
			'public'              => true,
			'capability_type'     => 'post',
			'has_archive'         => false,
			'hierarchical'        => false,
			'exclude_from_search' => true,
			'show_in_menu'        => false,
			'publicly_queryable'  => false,
			'supports'            => false,
		);

		register_post_type( 'tidy_form_entry', $args );

	}


	public function setup_form_meta_boxes() {
		add_meta_box(
			'arc-form-fields',
			'Fields',
			array( $this, 'render_fields_meta' ),
			'arc_form',
			'normal',
			'default'
		);

		add_meta_box(
			'arc-form-settings',
			'Form settings',
			array( $this, 'render_settings_meta' ),
			'arc_form',
			'normal',
			'default'
		);

		add_meta_box(
			'arc-form-entries',
			'Form entries',
			array( $this, 'render_entries_meta' ),
			'arc_form',
			'side',
			'default'
		);
	}

	public function setup_entry_meta_boxes() {

		add_meta_box(
			'arc-entry-data',
			'Form entry',
			array( $this, 'render_entry_content' ),
			'arc_form_entry',
			'normal',
			'default'
		);

		add_meta_box(
			'arc-entry-notes',
			'Notes',
			array( $this, 'render_entry_notes' ),
			'arc_form_entry',
			'normal',
			'default'
		);

	}

	public function render_fields_meta( $post ) {
		// Get fields data
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'arc_fields_nonce' );

		// Get settings data
		$content = get_post_meta( $post->ID, '_arc_form_data', true );
		$fields = $content['fields'];

		// Include fields meta view
		arc_get_view('form-fields-box', $fields);
	}

	public function render_settings_meta( $post ) {
		// No nonce here, already set in fields meta box

		// Get settings data
		$content = get_post_meta( $post->ID, '_arc_form_data', true );
		$settings = $content['settings'];

		// Give the submit text a default setting if none set
		if( empty($settings['submit_text']) ) {
			$settings['submit_text'] = 'Submit';
		}

		// Include settings meta view
		arc_get_view('form-settings', $settings);
	}

	public function render_entries_meta( $post ) {

		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'arc_entries_export_nonce' );

		// Get entries count
		$entry_count = get_post_meta( $post->ID, '_arc_form_entry_count', true );

		// Setup args
		$args = array(
				'form_id'		 => $post->ID,
				'entry_count' => $entry_count,
				'entry_link'	=> admin_url( 'edit.php?post_type=arc_form_entry&arc_form_id='. $post->ID ),
				'export_url'	=> plugins_url( 'export.php', __FILE__ ),
			);

		arc_get_view('form-entries-sidebar', $args);

	}

	public function render_entry_content( $post ) {
		$entry_id = $post->ID;
		$form_id = get_post_meta( $entry_id, '_arc_form_id', true );

		$content = get_post_meta( $form_id, '_arc_form_data', true );
		$fields = $content['fields'];

		$data = array();

		foreach( $fields as $field ) {
			$data[] = array(
					'label' => $field['label'],
					'entry' => get_post_meta( $entry_id, '_arc_' . $field['name'], true),
				);
		}

		arc_get_view('entry-data', $data);
	}

	public function render_entry_notes( $post ) {
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'arc_entry_nonce' );

		$notes = get_post_meta( $post->ID, '_arc_entry_note', true );

		$data = array(
				'notes' => $notes,
			);

		arc_get_view('entry-notes', $data);
	}

	public function save_form_data( $post_id ) {

		// verify if this is an auto save routine.
		// If it is the post has not been updated, so we don’t want to do anything
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// verify this came from the screen and with proper authorization,
		// because save_post can be triggered at other times
		if ( !isset( $_POST['arc_fields_nonce'] ) || !wp_verify_nonce( $_POST['arc_fields_nonce'], plugin_basename( __FILE__ ) ) ) {
			return $post_id;
		}

		// Get the post type object.
		global $post;
		$post_type = get_post_type_object( $post->post_type );

		// Check if the current user has permission to edit the post.
		if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return $post_id;
		}

		if ( wp_is_post_revision( $post_id ) ) {
			return $post_id;
		}

		// unhook this function so it doesn't loop infinitely
		remove_action('save_post', array( $this, 'save_form_data' ) );

		// Get fields array
		$fields = array();
		$field_count = $_POST['arc_fields_count'];

		for( $i=1; $i<=$field_count; $i++) {
			$field_order = $_POST['arc_field_' . $i]['order'];
			$fields[ $field_order ] = $_POST['arc_field_' . $i];
		}

		// Get settings array
		$settings = $_POST['arc_settings'];

		$all_data = array(
				'fields'   => $fields,
				'settings' => $settings,
			);

		$key = '_arc_form_data';

		$current = get_post_meta( $post_id, $key, true );
		$value = $all_data;

		// add/update record (both are taken care of by update_post_meta)
		if ( $value && '' == $current ) {
			add_post_meta( $post_id, $key, $value, true );
		} elseif ( $value && $value != $current ) {
			update_post_meta( $post_id, $key, $value );
		} elseif ( '' == $value && $current ) {
			delete_post_meta( $post_id, $key, $current );
		}

		add_action('save_post', array( $this, 'save_form_data' ) );
	}

	public function save_entry_data( $post_id ) {
		// verify if this is an auto save routine.
		// If it is the post has not been updated, so we don’t want to do anything
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// verify this came from the screen and with proper authorization,
		// because save_post can be triggered at other times
		if ( !isset( $_POST['arc_entry_nonce'] ) || !wp_verify_nonce( $_POST['arc_entry_nonce'], plugin_basename( __FILE__ ) ) ) {
			return $post_id;
		}

		// Get the post type object.
		global $post;
		$post_type = get_post_type_object( $post->post_type );

		// Check if the current user has permission to edit the post.
		if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return $post_id;
		}

		if ( wp_is_post_revision( $post_id ) ) {
			return $post_id;
		}

		$key = '_arc_entry_note';

		$current = get_post_meta( $post_id, $key, true );
		$value = $_POST['arc_entry_notes'];

		tidy_update_meta($post_id, $key, $value);
	}
}

