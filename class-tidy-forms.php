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
	 * The slug of the plugin
	 *
	 * @var string
	 */
	protected static $plugin_slug = 'tidy-forms';


	private function __construct() {

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

	public function initialise() {

		// settings
		$this->settings = array(

			// basic
			'name'				=> __('Tidy Forms', 'tidyforms'),
			'version'			=> '0.0.1',
			'slug'              => 'tidy-forms',

			// urls
			'basename'			=> plugin_basename( __FILE__ ),
			'path'				=> plugin_dir_path( __FILE__ ),
			'dir'				=> plugin_dir_url( __FILE__ ),
		);

		require_once('inc/helpers.php');

		tidy_include('class-tidy-forms-renderer.php');

		Tidy_Forms_Renderer::get_instance();

		if( is_admin() ) {
			tidy_include('class-tidy-forms-admin.php');

			Tidy_Forms_Admin::get_instance();
		}

	}
}
