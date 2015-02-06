<?php
/**
 * WP Form Architect renderer class
 *
 * @package 	WP Form Architect
 * @author 		Adam Onishi	<aonishi@wearearchitect.com>
 * @license 	GPL2
 * @copyright 	2015 Adam Onishi
 */

class Architect_Forms_Renderer {
	/**
	 * An instance of the class
	 *
	 * @var null
	 */
	protected static $instance = null;

	private function __construct() {

		add_action( 'init', array( $this, 'setup_shortcode') );

		add_filter( 'architect_form', array( $this, 'process_form'), 5, 1 );
		add_filter( 'architect_form', array( $this, 'render_form'), 10, 1 );

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
		add_shortcode( 'architect-form', array('Architect_Forms_Renderer', 'shortcode') );
	}

	public static function shortcode( $atts ) {
		$settings = shortcode_atts( array( 'id' => '' ), $atts, 'architect-form' );

		$args = array(
				'settings' => $settings,
			);

		do_action( 'architect_form', $args );
	}

	public static function process_form( $args ) {

		echo 'Process ' . $args['settings']['id'];
		echo '<br>';

	}

	public static function render_form( $args ) {

		echo 'Render ' . $args['settings']['id'];

	}
}
