<?php
/**
 * WP Form Architect validator class
 *
 * @package 	WP Form Architect
 * @author 		Adam Onishi	<aonishi@wearearchitect.com>
 * @license 	GPL2
 * @copyright 	2015 Adam Onishi
 */

class Architect_Forms_Validator {
	/**
	 * An instance of the class
	 *
	 * @var null
	 */
	protected static $instance = null;

	private function __construct() {

	}

	public static function get_instance() {
		if( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public static function process_form( $args ) {

		if( $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST) && isset($_POST['arc_form_submit']) ) {
			return self::validate_form( $args );
		}

		return $args;
	}

	private static function validate_form( $args ) {
		$fields = $args['fields'];
		$errors = array();

		foreach($fields as $field) {
			$type = $field['type'];
			$name = 'arc_' . $field['name'];

			if( $type !== 'title') {

				$value = $_POST[ $name ];

				if( isset($field['required']) && ! self::validate_required($value) ) {
					$errors[ $name ] = __('Error: This is a required field', 'arcforms');
				}
			}
		}

		if( ! empty($errors) ) {
			$args['errors'] = $errors;
		}

		return $args;
	}

	private static function validate_required( $value ) {
		if( empty($value ) ) {
			return false;
		}

		return true;
	}

	private static function validate_email( $value ) {
		if( ! is_email( $value ) ) {
			return false;
		}

		return true;
	}

}
