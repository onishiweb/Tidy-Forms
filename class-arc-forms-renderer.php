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

	public static $form; // The static instance of all form settings

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

	public static function call_static($method, $args) {
		return call_user_func_array(array( static::get_instance(), $method ), $args);
	}

	public function setup_shortcode() {
		add_shortcode( 'architect-form', array('Architect_Forms_Renderer', 'setup_form') );
	}

	public static function setup_form( $atts ) {
		$settings = shortcode_atts( arc_get_setting('setting_defaults'), $atts, 'architect-form' );

		$id = $settings['id'];

		// get form details
		$form = get_post( $id );
		$content = unserialize( $form->post_content );

		// Add the title to general settings
		$content['settings']['title'] = $form->post_title;

		// Setup the form details in the class ready to be used
		self::$form = array(
				'id'       => $id, // ID of the form
				'settings' => $settings, // Config options for form
				'fields'   => $content['fields'], // Form fields
				'general'  => $content['settings'], // Form general settings
			);

		arc_get_view('form-container');
	}

	public static function process_form() {

		echo 'Process ' . self::$form['id'];
		echo '<br>';

	}

	public static function render_form() {

		arc_get_view('form');

	}

	public static function get_form_title() {

		// if title output setting = false, return

		$title = self::$form['general']['title'];

		return $title;
	}

	public static function get_form_intro() {

		$title = self::$form['general']['intro_text'];

		if( $title !== '' ) {
			return apply_filters( 'the_content', $title );
		} else {
			return;
		}
	}

	public static function get_form_confirmation() {
		$thanks = self::$form['general']['thank_you_text'];

		if( $thanks !== '' ) {
			return apply_filters( 'the_content', $thanks );
		} else {
			return;
		}
	}

	public static function have_fields() {
		$fields = self::$form['fields'];

		if( count($fields) > 0 ) {
			return true;
		} else {
			return false;
		}
	}

	public static function get_fields() {
		$fields = self::$form['fields'];

		return $fields;
	}

	public static function get_form_field( $field = array() ) {

		if( empty($field) ) {
			return;
		}

		$field_method = 'get_' . $field['type'] . '_field';

		$output = self::field_before( $field['classes'] );

		$output.= self::$field_method($field);

		$output.= '<p class="description">' . $field['description'] . '</p>';

		$output.= self::field_after();

		return $output;

	}

	private static function field_before( $classes ) {
		$before = self::$form['settings']['field_wrap'];
		$classes.= ' ' . self::$form['settings']['field_class'];
		$output = '';

		if( ! empty($before) ) {
			$output = '<' . $before . ' class="' . $classes . '">';
		}

		return apply_filters( 'architect_form_field_before', $output );
	}

	private static function field_after() {
		$after = self::$form['settings']['field_wrap'];
		$output = '';

		if( ! empty($after) ) {
			$output = '</' . $after . '>';
		}

		return apply_filters( 'architect_form_field_after', $output );
	}

	private static function get_text_field( $args = array() ) {

		// 	<label for=""></label>
		// 	<input type="text">

	}

	private static function get_textarea_field( $args = array() ) {

	}

	private static function get_select_field( $args = array() ) {

	}

	private static function get_radio_field( $args = array() ) {

	}

	private static function get_checkbox_field( $args = array() ) {

	}

	private static function get_title_field( $args = array() ) {

	}

	public static function get_form_submit() {
		$text = self::$form['general']['submit_text'];

		$hidden_fields = '';

		$output = self::field_before( 'arc-form-field-submit');

		$output.= '<input type="submit" name="arc_form_submit" class="arc-form-submit" value="' . $text . '">';
		$output.= apply_filters( 'architect_form_hidden_fields', $hidden_fields );

		$output.= self::field_after();

		return $output;
	}
}
