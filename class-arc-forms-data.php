<?php
/**
 * WP Form Architect data class
 *
 * @package 	WP Form Architect
 * @author 		Adam Onishi	<aonishi@wearearchitect.com>
 * @license 	GPL2
 * @copyright 	2015 Adam Onishi
 */

class Architect_Forms_Data {
	/**
	 * An instance of the class
	 *
	 * @var null
	 */
	protected static $instance = null;

	private function __construct() {
		// Nothing to see here
	}

	public static function get_instance() {
		if( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public static function save_entry( $args ) {
		// Setup variables
		$id = $args['id'];
		$fields = $args['fields'];
		$content = '';

		// Loop through fields to create content
		foreach( $fields as $field ) {
			$label = $field['label'];
			$value = $field['value'];

			if( is_array($value) ) {
				$value = implode(', ', $field['value']);
			}

			$content.= "$label: $value\n";
		}

		// Create new post type arguments
		$args = array(
				'post_type'    => 'arc_form_entry',
				'post_content' => $content,
			);

		// Create new post
		$post = wp_insert_post( $args, true );

		// Check is not an error
		if( ! is_wp_error( $post ) ) {
			// Store form ID as postmeta
			add_post_meta( $post, '_arc_form_id', $id, true );

			// Update entry count
			$count = get_post_meta( $id, '_arc_form_entry_count', true );
			if( !empty($count) ) {
				$count++;
			} else {
				$count = 1;
			}

			update_post_meta( $id, '_arc_form_entry_count', $count, $count - 1 );

			// Loop through fields to create content
			foreach( $fields as $field ) {
				$name = $field['name'];
				$value = $field['value'];

				if( is_array($value) ) {
					$value = implode(', ', $field['value']);
				}

				add_post_meta( $post, '_arc_'.$name, $value, true );
			}

		} else {
			// TODO: Handle error
		}
	}
}
