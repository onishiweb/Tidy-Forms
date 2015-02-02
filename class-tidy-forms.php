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

}
