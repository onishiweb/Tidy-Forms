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

		add_filter( 'architect_form', array( 'Architect_Forms_Validator', 'process_form'), 5, 1 );
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
		add_shortcode( 'architect-form', array($this, 'setup_form') );
	}

	public static function setup_form( $atts ) {
		$settings = shortcode_atts( arc_get_setting('setting_defaults'), $atts, 'architect-form' );

		$id = $settings['id'];

		// get form details
		$form = get_post( $id );
		$content = unserialize( $form->post_content );

		// Add the title to general settings
		$content['settings']['title'] = $form->post_title;

		$args = array(
				'id'       => $id, // ID of the form
				'settings' => $settings, // Config options for form
				'fields'   => $content['fields'], // Form fields
				'general'  => $content['settings'], // Form general settings
			);

		arc_get_view('form-container', $args );
	}

	public static function render_form( $args ) {

		// Setup the form details in the class ready to be used
		self::$form = $args; // This way it'll get the errors array...

		arc_get_view('form');

		return $args;

	}

	public static function get_form_id() {

		$id = self::$form['id'];

		// Check if a custom ID has been set

		$form_id = 'arc-form-' . $id;

		return esc_attr( $form_id );

	}

	public static function get_form_classes() {
		// Check for custom classes

		return esc_attr('arc-form');
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

		$output.= '<span class="arc-field-description">' . $field['description'] . '</span>';

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

		extract($args);

		$id = str_replace('_', '-', $name);

		if( $custom_id !== '' ) {
			$id = $custom_id;
		}

		$output = '<label for="arc-' . $id . '">' . $label . '</label>';
		$output.= '<input type="text" name="arc_' . $name . '" id="arc-' . $id . '" class="arc-form-text" value="" >';

		return $output;

	}

	private static function get_textarea_field( $args = array() ) {

		extract($args);

		$id = str_replace('_', '-', $name);

		if( $custom_id !== '' ) {
			$id = $custom_id;
		}

		$output = '<label for="arc-' . $id . '">' . $label . '</label>';
		$output.= '<textarea name="arc_' . $name . '" id="arc-' . $id . '" class="arc-form-textarea" cols="80" rows="5"></textarea>';

		return $output;
	}

	private static function get_select_field( $args = array() ) {
		extract($args);

		$options = explode("\n", $input_options);

		$id = str_replace('_', '-', $name);

		if( $custom_id !== '' ) {
			$id = $custom_id;
		}

		$output = '<label for="arc-' . $id . '">' . $label . '</label>';
		$output.= '<select name="arc_' . $name . '" id="arc-' . $id . '" class="arc-form-select">';

		foreach( $options as $opt ) {
			$values = explode(' : ', $opt);

			if( count($values) > 1 ) {
				$output.= '<option value="' . $values[0] . '">' . $values[1] . '</option>';
			} else {
				$output.= '<option value="' . $values[0] . '">' . $values[0] . '</option>';
			}
		}

		$output.= '</select>';

		return $output;
	}

	private static function get_radio_field( $args = array() ) {
		extract($args);

		$options = explode("\n", $input_options);

		$id = str_replace('_', '-', $name);

		if( $custom_id !== '' ) {
			$id = $custom_id;
		}

		$output = '<span class="arc-form-label">' . $label . '</span>';

		for($i=0; $i<count($options); $i++) {
			$values = explode(' : ', $options[$i]);

			if( count($values) > 1 ) {
				$output.= '<label for="arc-' . $id . '-' . $i . '">';
				$output.= '<input type="radio" name="arc_' . $name . '" id="arc-' . $id . '-' . $i . '" class="arc-form-radio" value="' . $values[0] . '">';
				$output.= '<span class="arc-form-radio-label">' . $values[1] . '</span></label>';
			} else {
				$output.= '<label for="arc-' . $id . '-' . $i . '">';
				$output.= '<input type="radio" name="arc_' . $name . '" id="arc-' . $id . '-' . $i . '" class="arc-form-radio" value="' . $values[0] . '">';
				$output.= '<span class="arc-form-radio-label">' . $values[0] . '</span></label>';
			}
		}

		return $output;
	}

	private static function get_checkbox_field( $args = array() ) {
		extract($args);

		$options = explode("\n", $input_options);

		$id = str_replace('_', '-', $name);

		if( $custom_id !== '' ) {
			$id = $custom_id;
		}

		$output = '<span class="arc-form-label">' . $label . '</span>';

		for($i=0; $i<count($options); $i++) {
			$values = explode(' : ', $options[$i]);

			if( count($values) > 1 ) {
				$output.= '<label for="arc-' . $id . '-' . $i . '">';
				$output.= '<input type="checkbox" name="arc_' . $name . '[]" id="arc-' . $id . '-' . $i . '" class="arc-form-checkbox" value="' . $values[0] . '">';
				$output.= '<span class="arc-form-checkbox-label">' . $values[1] . '</span></label>';
			} else {
				$output.= '<label for="arc-' . $id . '-' . $i . '">';
				$output.= '<input type="checkbox" name="arc_' . $name . '[]" id="arc-' . $id . '-' . $i . '" class="arc-form-checkbox" value="' . $values[0] . '">';
				$output.= '<span class="arc-form-checkbox-label">' . $values[0] . '</span></label>';
			}
		}

		return $output;
	}

	private static function get_title_field( $args = array() ) {
		extract($args);

		$output = '<span class="arc-form-title">' . $label . '</span>';

		return $output;
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
