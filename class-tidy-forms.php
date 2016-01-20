<?php
/**
 * Tidy Forms main class file
 *
 * @package     Tidy Forms
 * @author      Adam Onishi <onishiweb@gmail.com>
 * @license     GPL2
 * @copyright   2016 Adam Onishi
 */

class Tidy_Forms {
	/**
	 * An instance of the class
	 *
	 * @var null
	 */
	protected static $instance = null;

	/**
	 * The slug of the plugin
	 *
	 * @var string
	 */
	protected static $plugin_slug = 'tidy-forms';


	private function __construct() {
		// Nothing to see here
	}

	public static function get_instance() {
		if( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function initialise() {

		// settings
		$this->settings = array(

			// basic
			'name'               => __('Tidy Forms', 'tidyforms'),
			'version'            => '1.0.0',
			'slug'               => 'tidy-forms',

			// urls
			'basename'           => plugin_basename( __FILE__ ),
			'path'               => plugin_dir_path( __FILE__ ),
			'dir'                => plugin_dir_url( __FILE__ ),

			'field_types'        => array(
				'title'    => 'Title',
				'text'     => 'Text',
				'textarea' => 'Textarea',
				'radio'    => 'Radio button(s)',
				'checkbox' => 'Checkbox(es)',
				'select'   => 'Drop down',
			),

			'validation_methods' => array(
				'email'  => 'Email',
				'url'    => 'URL',
				'number' => 'Number',
				'date'   => 'Date',
			),

			'setting_defaults'   => array(
				'id'               => 0,
				'all_fields_wrap'  => 'ul', // ul, div, or ''
				'all_fields_class' => 'tidy-form-wrap',
				'field_wrap'       => 'li', // li, div, p, or ''
				'field_class'      => 'tidy-form-field',
				'field_prefix'     => 'tidy_',
			),
		);

		require_once('inc/helpers.php');

		tidy_include('class-tidy-forms-data.php');

		Tidy_Forms_Data::get_instance();

		if( ! is_admin() ) {
			// TODO: Check if them customiser still works with these only being included when not in the admin
			tidy_include('inc/template-tags.php');
			tidy_include('class-tidy-forms-renderer.php');
			tidy_include('class-tidy-forms-validator.php');

			Tidy_Forms_Renderer::get_instance();
			Tidy_Forms_Validator::get_instance();
		}

		if( is_admin() ) {
			tidy_include('class-tidy-list-table.php');
			tidy_include('class-tidy-forms-admin.php');

			Tidy_Forms_Admin::get_instance();
		}
	}
}
