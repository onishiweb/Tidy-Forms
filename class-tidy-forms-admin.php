<?php
/**
 * Tidy Forms admin class file
 *
 * @package 	TidyForms
 * @author 		Adam Onishi	<aonishi@wearearchitect.com>
 * @license 	GPL2
 * @copyright 	2015 Adam Onishi
 */

class Tidy_Forms_Admin {
	/**
	 * An instance of the class
	 *
	 * @var null
	 */
	protected static $instance = null;

	protected static $screen = null;

	private function __construct() {

		// Include Stylesheet/scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts_styles') );

		// Create post type
		add_action( 'init', array( $this, 'setup_post_type') );

		// Add meta boxes for form creation
		add_action( 'add_meta_boxes_tidy_form', array( $this, 'setup_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_form_data' ) );

		add_action( 'edit_form_before_permalink', array( $this, 'form_shortcode_helper' ) );
	}

	public static function get_instance() {
		if( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public static function __callStatic($method, $args) {
		return call_user_func_array(array( static::get_instance(), $method ), $args);
	}

	public static function admin_scripts_styles() {

		$screen = get_current_screen()->id;

	    if ( 'tidy_form' === $screen ) {
	    	wp_enqueue_style( 'tidy-forms-admin', tidy_get_dir('css/admin.css'), false, tidy_get_setting('version'), 'screen' );
	    }
	}

	public function setup_post_type() {
		 $labels = array(
			'name'                => __('Tidy Forms', 'dirtisgood'),
			'singular_name'       => __('Form', 'dirtisgood'),
			'add_new'             => __('Add New', 'dirtisgood'),
			'add_new_item'        => __('Add New Form', 'dirtisgood'),
			'edit_item'           => __('Edit Form', 'dirtisgood'),
			'new_item'            => __('New Form', 'dirtisgood'),
			'all_items'           => __('All Forms', 'dirtisgood'),
			'view_item'           => __('View Form', 'dirtisgood'),
			'search_items'        => __('Search Forms', 'dirtisgood'),
			'not_found'           => __('No forms found', 'dirtisgood'), // USE THIS ADAM!
			'not_found_in_trash'  => __('No forms found in Trash', 'dirtisgood'),
			'parent_item_colon'   => '',
			'menu_name'           => __('Tidy Forms', 'dirtisgood'),
		);

		$args = array(
			'labels'              => $labels,
			'description'         => 'Tidy forms admin',
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

	public function setup_meta_boxes() {
		add_meta_box(
			'tidy-form-fields',
			'Fields',
			array( $this, 'render_fields_meta' ),
			'tidy_form',
			'normal',
			'default'
		);

		add_meta_box(
			'tidy-form-settings',
			'Form settings',
			array( $this, 'render_settings_meta' ),
			'tidy_form',
			'normal',
			'default'
		);
	}

	public function render_fields_meta( $post ) {
		// Get fields data
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'tidy_fields_nonce' );




		// Include fields meta view
		tidy_get_view('form-fields');
	}

	public function render_settings_meta( $post ) {
		// No nonce here, already set in fields meta box

		// Get settings data
		$content = unserialize( $post->post_content );
		$settings = $content['settings'];

		// Give the submit text a default setting if none set
		if( empty($settings['submit_text']) ) {
			$settings['submit_text'] = 'Submit';
		}

		// Include settings meta view
		tidy_get_view('form-settings', $settings);
	}

	public function save_form_data( $post_id ) {

		// verify if this is an auto save routine.
		// If it is the post has not been updated, so we don’t want to do anything
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// verify this came from the screen and with proper authorization,
		// because save_post can be triggered at other times
		if ( !isset( $_POST['tidy_fields_nonce'] ) || !wp_verify_nonce( $_POST['tidy_fields_nonce'], plugin_basename( __FILE__ ) ) ) {
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
		$fields = $_POST['tidy_fields'];

		// Get settings array
		$settings = $_POST['tidy_settings'];

		$all_data = array(
				'fields'   => $fields,
				'settings' => $settings,
			);

		$post = array(
			'ID'           => $post_id,
			'post_content' => serialize($all_data),
			);

		$update = wp_update_post( $post, true );

		if( ! is_wp_error( $update ) ) {
			// re-hook this function
			add_action('save_post', array( $this, 'save_form_data' ) );
		}
	}

	public function form_shortcode_helper( $post ) {

		// Include shortcode view if on the tidy_form admin screen
		if( 'tidy_form' === get_current_screen()->id ) {
			tidy_get_view('form-shortcode');
		}

	}

}
