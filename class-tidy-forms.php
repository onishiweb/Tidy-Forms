<?php
/**
 * Tidy Forms main class file
 *
 * @package 	TidyForms
 * @author 		Adam Onishi	<aonishi@wearearchitect.com>
 * @license 	GPL2
 * @copyright 	2015 Adam Onishi
 */

class Tidy_Forms {
	/**
	 * An instance of the class
	 *
	 * @var null
	 */
	protected static $instance = null;

	/**
	 * The current version number of the plugin
	 *
	 * @var string
	 */
	protected static $version = '0.0.1';

	/**
	 * The slug of the plugin
	 *
	 * @var string
	 */
	protected static $plugin_slug = 'tidy-forms';


	private function __construct() {
		// Include Stylesheet/scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts_styles') );

		// Create post type
		add_action( 'init', array( $this, 'setup_post_type') );
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

	}

	public static function setup_post_type() {
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
			'menu_position'       => 45,
			'menu_icon'           => 'dashicons-feedback',
			'supports'            => array( 'title', 'author', 'revisions', 'page-attributes' ),
		);

		register_post_type( 'tidy_form', $args );
	}

}
