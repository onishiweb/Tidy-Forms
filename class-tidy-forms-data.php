<?php
/**
 * Tidy Forms data class
 *
 * @package     Tidy Forms
 * @author      Adam Onishi <onishiweb@gmail.com>
 * @license     GPL2
 * @copyright   2016 Adam Onishi
 */

class Tidy_Forms_Data {
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
				'post_type'    => 'tidy_form_entry',
				'post_content' => $content,
			);

		// Create new post
		$post = wp_insert_post( $args, true );

		// Check is not an error
		if( ! is_wp_error( $post ) ) {
			// Store form ID as postmeta
			add_post_meta( $post, '_tidy_form_id', $id, true );

			// Update entry count
			$count = get_post_meta( $id, '_tidy_form_entry_count', true );
			if( !empty($count) ) {
				$count++;
			} else {
				$count = 1;
			}

			update_post_meta( $id, '_tidy_form_entry_count', $count, $count - 1 );

			// Loop through fields to create content
			foreach( $fields as $field ) {
				$name = $field['name'];
				$value = $field['value'];

				if( is_array($value) ) {
					$value = implode(', ', $field['value']);
				}

				add_post_meta( $post, '_tidy_'.$name, $value, true );
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

		tidy_get_view('email-template', $email_args);

		$message = ob_get_contents();
		ob_end_clean();

		$headers = "From: {$site_name} <donotreply@{$domain_name}> \r\n" .
					"MIME-Version: 1.0\r\n" .
					"Content-Type: text/html; charset=ISO-8859-1\r\n";

		wp_mail( $to_email, $subject, $message, $headers );
	}

	public static function export_form() {

		if( ! is_user_logged_in() || ! current_user_can( 'edit_others_posts' ) ) {
			wp_die( 'You do not have permission to perform this action', 'Export error' );
		}

    if ( ! isset( $_POST['tidy_form_export_nonce'] ) || ! wp_verify_nonce( $_POST['tidy_form_export_nonce'], 'tidy_form_entries_export' ) ) {
      wp_die( 'Invalid nonce', 'Export error' );
    }

    $form_id = intval( $_POST['tidy_export_form'], 10);
    $forms = new WP_Query( array('post_type' => 'tidy_form', 'posts_per_page' => '-1', 'fields' => 'ids' ) );

    if( ! is_numeric($form_id) || ! in_array($form_id, $forms->posts) ) {
      wp_die( 'Invalid form ID', 'Export error' );
    }

		$uploads = wp_upload_dir();
		$export_fields = array();
		$export_data = array();

		$args = array(
				'post_type'      => 'tidy_form_entry',
				'posts_per_page' => '-1',
				'meta_key'       => '_tidy_form_id',
				'meta_value'     => $form_id,
				'post_status'    => 'all',
			);

		$entries = new WP_Query($args);

		$form = get_post($form_id);
    $form_name = $form->post_title;

		$content = get_post_meta( $form_id, '_tidy_form_data', true );
		$fields = $content['fields'];

		foreach( $fields as $field ) {
			$export_fields[ $field['name'] ] = $field['label'];
		}

		foreach( $entries->posts as $entry ) {

			$post_id = $entry->ID;

			$post_data = array();

			foreach( $export_fields as $key => $title ) {
				$post_data[ $key ] = get_post_meta ($post_id, '_tidy_'.$key, true);
			}

			$export_data[] = $post_data;
		}

    $export_filename = 'export-' . strtolower( str_replace(' ', '-', $form_name) ) . '-' . date('Y-m-d');

    array_unshift($export_data, $export_fields);

		header('Content-Description: File Transfer');
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename=' . $export_filename);
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1
    header('Pragma: no-cache'); // HTTP 1.0
    header('Expires: 0');
		header('HTTP/1.0 200 OK', true, 200);

    ob_start(); // buffer the output ...

    $fp = fopen('php://output', 'w'); // this file actual writes to php output
    foreach($export_data as $row) {
      fputcsv($fp, $row);
    }

    header('Content-Length: ' . ob_get_length() );

    fclose($fp);

		die( ob_get_clean() );
	}
}
