<?php
/**
 * Tidy Forms admin helpers class
 *
 * @package 	TidyForms
 * @author 		Adam Onishi	<aonishi@wearearchitect.com>
 * @license 	GPL2
 * @copyright 	2015 Adam Onishi
 */

class Tidy_Forms_Admin {

	protected static $instance = null;

	private function __construct() {

	}

	public static function get_instance() {
		if( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	private function tidy_update_meta($post_id, $key, $value, $current = '' ) {
		// add/update record (both are taken care of by update_post_meta)
		if ( $value && '' == $current ) {
			add_post_meta( $post_id, $key, $value, true );
		} elseif ( $value && $value != $current ) {
			update_post_meta( $post_id, $key, $value );
		} elseif ( '' == $value && $current ) {
			delete_post_meta( $post_id, $key, $current );
		}
	}
}