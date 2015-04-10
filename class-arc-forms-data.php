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

	public static function save_entry( $data ) {
		// Setup variables
		$id = $data['id'];
		$fields = $data['fields'];
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

	public static function email_entry( $data ) {
		// Setup variables
		$id = $data['id'];
		$form_title = get_the_title( $id );
		$fields = $data['fields'];
		$settings = $data['general'];

		if( ! isset( $settings['send_via_email'] ) ) {
			return;
		}

		$to_email = get_bloginfo( 'admin_email' );
		$site_name = get_bloginfo( 'name' );
		$subject = 'New Submission - Form: ' . $form_title;
		$domain_name = preg_replace('/^www\./','',$_SERVER['SERVER_NAME']);

		if( ! empty($settings['send_to_email']) ) {
			$to_email = $settings['send_to_email'];
		}

		$email_args = array(
				'subject' => $subject,
				'fields' => $fields,
			);

		ob_start();

		arc_get_view('email-template', $email_args);

		$message = ob_get_contents();
		ob_end_clean();

		$headers = "From: {$site_name} <donotreply@{$domain_name}> \r\n" .
					"MIME-Version: 1.0\r\n" .
					"Content-Type: text/html; charset=ISO-8859-1\r\n";

		wp_mail( $to_email, $subject, $message, $headers );
	}

	public static function export_form( $form_id ) {

		if( ! is_user_logged_in() || ! current_user_can( 'edit_others_posts' ) ) {
			return;
		}

		$uploads = wp_upload_dir();
		$export_fields = array();
		$export_data = array();

		$args = array(
				'post_type'      => 'arc_form_entry',
				'posts_per_page' => '-1',
				'meta_key'       => '_arc_form_id',
				'meta_value'     => $form_id,
				'post_status'    => 'all',
			);

		$entries = new WP_Query($args);

		$form = get_post($form_id);

		$content = unserialize( $form->post_content );
		$fields = $content['fields'];

		foreach( $fields as $field ) {
			$export_fields[ $field['name'] ] = $field['label'];
		}

		foreach( $entries->posts as $entry ) {

			$post_id = $entry->ID;

			$post_data = array();

			foreach( $export_fields as $key => $title ) {
				$post_data[ $key ] = get_post_meta ($post_id, '_arc_'.$key, true);
			}

			$export_data[] = $post_data;
		}

		$fullpath = $uploads['basedir'] . '/form-export-' . $form_id . '.csv'; //Full path of document
		$fh = fopen($fullpath, 'w');

		if( ! $fh ) {
			// Error
			die();
		}

		// Write headers
		$headers = implode('", "', $export_fields);
		fwrite( $fh, '"' . $headers . '"' );
		fwrite( $fh,  PHP_EOL );

		// Write entries
		foreach ( $export_data as $row ) {
			$entry = implode( '", "', $row);
			fwrite( $fh, '"' . $entry . '"' );
			fwrite( $fh,  PHP_EOL );
		}

		fclose($fh);

		if (file_exists($fullpath)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.basename($fullpath));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($fullpath));

			header('HTTP/1.0 200 OK', true, 200);
			ob_clean();
			flush();
			readfile($fullpath);
			die();
		} else {
			echo '<pre>';
			echo 'There is an error with the csv file'.PHP_EOL.$fullpath;
			echo '</pre>';
		}
	}
}
