<?php
/**
 * Tidy Forms main class file
 *
 * @package 	TidyForms
 * @author 		Adam Onishi	<aonishi@wearearchitect.com>
 * @license 	GPL2
 * @copyright 	2015 Adam Onishi
 */

class Tidy_Forms {
	/**
	 * An instance of the class
	 *
	 * @var null
	 */
	protected static $instance = null;

	private function __construct() {
		// Do nothing on construct - everythig in initialise()
	}

	public static function get_instance() {
		if( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public static function __callStatic($method, $args) {
		return call_user_func_array(array( static::get_instance(), $method ), $args);
	}

	public function initialise() {
		/**
		 * The main settings array for the plugin
		 *
		 * @var array
		 */
		$this->settings = array(
			// basic
			'name'               => __('Tidy Forms', 'tidyforms'),
			'version'            => '0.1.0',
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

		require_once('lib/helpers.php');

		if( is_admin() ) {
			tidy_include('class-tidy-admin.php');
			tidy_include('class-admin-init.php');

			$admin = Tidy_Forms_Admin_Init::get_instance();
		}		
	}
}
