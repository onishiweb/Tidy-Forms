<?php
/**
 * Tidy Forms renderer class
 *
 * @package 	TidyForms
 * @author 		Adam Onishi	<aonishi@wearearchitect.com>
 * @license 	GPL2
 * @copyright 	2015 Adam Onishi
 */

class Tidy_Forms_Renderer {
	/**
	 * An instance of the class
	 *
	 * @var null
	 */
	protected static $instance = null;

	private function __construct() {

		add_action( 'init', array( $this, 'setup_shortcode') );
		add_action( 'tidy_form', array( $this, 'render_form'), 10, 1 );

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

	public function setup_shortcode() {
		add_shortcode( 'tidy-form', array('Tidy_Forms_Renderer', 'render_shortcode') );
	}

	public static function render_shortcode( $atts ) {
		$settings = shortcode_atts( array( 'id' => '' ), $atts, 'tidy_form' );

		do_action( 'tidy_form', $settings );
	}

	public static function render_form( $settings ) {


	}
}
