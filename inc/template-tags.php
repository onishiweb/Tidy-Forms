<?php
/**
 * Template tags
 *
 * @package     Tidy Forms
 * @author      Adam Onishi <onishiweb@gmail.com>
 * @license     GPL2
 * @copyright   2016 Adam Onishi
 */

/**
 * wp_tidy_form
 *
 * Template tag for Tidy Forms plugin
 *
 * @param  int 		$id 	ID of the form to load
 * @param  array 	$args 	Arguments for the form construction
 */
function tidy_the_form( $id, $args = array() ) {
    $args['id'] = $id;

    $renderer = Tidy_Forms_Renderer::get_instance();
    $renderer->setup_form( $args );
}

/**
 * tidy_the_form_action
 *
 * Function wrapper for the apply_filters function
 *
 * @param  array 	$args Array of arguments
 */
function tidy_the_form_action( $args ) {
	apply_filters( 'tidy_form', $args );
}

/**
 * tidy_the_form_id
 *
 * Output the HTML ID of the form - to go on the form container element
 *
 * @param  boolean $echo Whether to echo or just return
 *
 * @return string        The ID for the form
 */
function tidy_the_form_id( $echo = true ) {

	$id = Tidy_Forms_Renderer::get_form_id();

	if( $echo ) {
		echo $id;
	} else {
		return $id;
	}

}

/**
 * tidy_the_form_class
 *
 * Output the HTML classes of the form - to go on the form element
 *
 * @param  boolean $echo Whether to echo or just return
 *
 * @return string        The classes for the form
 */
function tidy_the_form_class( $echo = true ) {

	$classes = Tidy_Forms_Renderer::get_form_classes();

	if( $echo ) {
		echo $classes;
	} else {
		return $classes;
	}

}

/**
 * tidy_the_form_title
 *
 * Output the form title if set to in the form settings
 *
 * @param  boolean 	$echo 	Whether to echo or just return the title
 *
 * @return string       	The title of the form
 */
function tidy_the_form_title( $echo = true ) {

	$title = Tidy_Forms_Renderer::get_form_title();

	if( $echo ) {
		echo $title;
	} else {
		return $title;
	}
}

/**
 * tidy_the_form_output_title
 *
 * Checks the setting for whether the form title should be output
 *
 * @return boolean 			True if form title should be output
 */
function tidy_should_output_title() {
	// Setup setting for title output

	return true;
}

/**
 * tidy_the_form_intro
 *
 * Output the form introduction if there is one
 *
 * @param  boolean 	$echo 	Whether to echo or just return
 *
 * @return string        	The introduction text for the form
 */
function tidy_the_form_intro( $echo = true ) {
	$intro = Tidy_Forms_Renderer::get_form_intro();

	if( $echo ) {
		echo $intro;
	} else {
		return $intro;
	}
}

/**
 * tidy_form_have_fields
 *
 * Checks whether there are any fields to output
 *
 * @return boolean 			True if there are fields to output
 */
function tidy_form_have_fields() {
	return Tidy_Forms_Renderer::have_fields();
}

/**
 * tidy_the_form_fields
 *
 * Gets and returns an array of the available form fields
 *
 * @return array 			array of form fields
 */
function tidy_the_form_fields() {
	return Tidy_Forms_Renderer::get_fields();
}

/**
 * tidy_the_form_field
 *
 * Output a form field
 *
 * @param  array   $field 	Array of data for the field to be output
 * @param  boolean $args 	Whether to output the field or return
 */
function tidy_the_form_field( $field = array(), $echo = true ) {
	$field_html = Tidy_Forms_Renderer::get_form_field( $field );

	if( $echo ) {
		echo $field_html;
	} else {
		return $field_html;
	}
}

/**
 * tidy_the_form_submit
 *
 * Output the submit button for the form with the submit text setting
 *
 * @param  boolean 	$echo 	Whether to echo or just return
 *
 * @return string     		The html submit button
 */
function tidy_the_form_submit( $echo = true ) {
	$submit = Tidy_Forms_Renderer::get_form_submit();

	if( $echo ) {
		echo $submit;
	} else {
		return $submit;
	}
}

/**
 * tidy_the_form_confirmation
 *
 * Output the confirmation text of the form
 *
 * @param  boolean $echo Whether to echo or return
 *
 * @return string        The confirmation message from the form settings
 */
function tidy_the_form_confirmation( $echo = true ) {
	$confirmation = Tidy_Forms_Renderer::get_form_confirmation();

	if( $echo ) {
		echo $confirmation;
	} else {
		return $confirmation;
	}
}

/**
 * tidy_the_form_fields_before
 *
 * Output the opening tag of the element at the start of the form
 *
 * @return string 		HTML to go before the form fields
 */
function tidy_the_form_fields_before() {
	do_action('tidy_form_before_fields');

	echo '<ul class="tidy-form-wrap">';
}

/**
 * tidy_the_form_fields_after
 *
 * Output the closing tag of the element at the end of the form
 *
 * @return string 		HTML to go after the form fields
 */
function tidy_the_form_fields_after() {

	echo '</ul>';

	do_action('tidy_form_after_fields');
}

