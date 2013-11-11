<?php
/*
Plugin Name: Tidy Forms
Plugin URI: http://wp-tidyforms.com
Description: Tidy Forms is a new forms generator for WordPress, combining simple form creation and great markup.
Version: 0.0.1
Author: Adam Onishi
Author URI: http://adamonishi.com
License: GPL2
*/

/*  Copyright 2013 Adam Onishi (email: onishiweb at gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
?>
<?php 

function tidy_add_form($args) {

	if( $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST) && isset($_POST['tidy_forms_submit']) ) {
		
		unset($_POST['tidy_forms_submit']);

		if($_POST['user_name'] == '' || $_POST['user_email'] === '' ) {
			echo '<p class="error">There was an error!</p>';
		} else {
			// Send email
			$sent = true;
		}
	}	
		
	if( isset($sent) ) {
		echo 'Form submitted';
	} else {
		tidy_contact_form();
	}
}

add_action( 'tidy_forms', 'tidy_add_form' );

// TODO:
// - Shortcode (contact form only)
// - Create form function
// - Process form function
// - Create/send email function
// - Template tag (process arguments)
// - Create all functions as class 

function tidy_contact_form($args = null, $data = null) {

	// Defaults - extend these
	$defaults = array(
			'form_before'  => '<h2>Contact us</h2>',
			'form_after'   => '', 
			'field_before' => '<div class="tidy-field">',
			'field_after'  => '</div>',
			'submit_text'  => 'Send message',
			'form_action'  => '',
		);

	// Parse arguments with defaults
	$args = wp_parse_args( $args, $defaults );

	tidy_create_form($args, $data);
}

function tidy_shortcodes() {

}

function tidy_create_form($args, $data) {
	// Extract the arguments into variables
	extract($args);

	// Build form:
	echo $form_before;
?>
	<form action="<?php echo $form_action; ?>" method="post">
		<?php echo $field_before; ?>
			<label for="contact-name">Name:</label>
			<input type="text" id="contact-name" name="contact_name" class="tidy-text">
		<?php echo $field_after; ?>

		<?php echo $field_before; ?>
			<label for="contact-email">Email:</label>
			<input type="text" id="contact-email" name="contact_email" class="tidy-text tidy-email">
		<?php echo $field_after; ?>

		<?php echo $field_before; ?>
			<label for="contact-message">Message:</label>
			<textarea name="contact_message" id="contact-message" cols="30" rows="10" class="tidy-textarea"></textarea>
		<?php echo $field_after; ?>

		<?php echo $field_before; ?>
			<input type="submit" id="contact-submit" name="contact_submit" value="<?php echo $submit_text; ?>" class="tidy-submit">
		<?php echo $field_after; ?>
	</form>
<?php
	echo $form_after;

}

function tidy_process_form() {

}

function tidy_send_email() {

}

?>
