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
			'tidy-forms-settings',
			'Form settings',
			array( $this, 'render_settings_meta' ),
			'tidy_form',
			'normal',
			'default'
		);
	}

	public function render_settings_meta( $post ) {
		// Include settings meta view
	}

	public function save_form_data( $post_id ) {

	}

	public function form_shortcode_helper( $post ) {

		if( 'tidy_form' === get_current_screen()->id ) {
			// Change this to a view
			?>
			<div class="inside">
				<div id="tidy-form-shortcode-preview" class="shortcode-preview"><?php // to be filled with something useful ?></div>
			</div>
			<?php
		}

	}

}
