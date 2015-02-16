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
		// Include validation libraries
		arc_include('inc/validate-date.php');
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

			switch ($type)  {
				case 'text':
					if( $field['text_validation'] !== '' ) {
						$validation = 'validate_' .$field['text_validation'];

						if( ! self::$validation( $_POST[ $name ] ) ) {
							$errors[ $name ] = __('Error: This is a required field', 'arcforms');
						}
					}
				case 'textarea':
				case 'select':
					if( isset($field['required']) && ! self::validate_required($_POST[ $name ]) ) {
						$errors[ $name ] = __('Error: This is a required field', 'arcforms');
					}

					break;
				case 'radio':
				case 'checkbox':
					if( isset($field['required']) && ! isset( $_POST[ $name ]) ) {
						$errors[ $name ] = __('Error: This is a required field, please select an option', 'arcforms');
					}

					break;
				default:
					break;
			}
		}

		if( ! empty($errors) ) {
			$args['errors'] = $errors;

			add_action( 'architect_form_before_fields', array('Architect_Forms_Validator', 'get_error_notification') );
		} else {
			// Save/send form data

			// Set thanks message
			$args['submitted'] = true;
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

	private static function validate_number( $value ) {

		if( ! is_int($value) ) {
			return false;
		}

		return true;
	}

	private static function validate_url( $value ) {

		if ( ! filter_var($value, FILTER_VALIDATE_URL) ) {
			return false;
		}

		return true;
	}

	private static function validate_date( $value ) {

		if( ! arc_validate_date($value, 'YYYY-MM-DD') ) {
			return false;
		}

		return true;
	}

	public static function get_error_notification() {
		// TODO: Add to general settings
		echo '<div class="arc-forms-notification arc-forms-error">';
		_e('Error: Sorry your form could not be submitted. Please check the fields below and submit again', 'arcforms');
		echo '</div>';
	}

}
