<?php
/**
 * WP Form Architect main class file
 *
 * @package 	WP Form Architect
 * @author 		Adam Onishi	<aonishi@wearearchitect.com>
 * @license 	GPL2
 * @copyright 	2015 Adam Onishi
 */

class Architect_Forms {
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
	protected static $plugin_slug = 'arc-forms';


	private function __construct() {

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
			'name'             => __('WP Form Architect', 'arcforms'),
			'version'          => '0.0.1',
			'slug'             => 'arc-forms',

			// urls
			'basename'         => plugin_basename( __FILE__ ),
			'path'             => plugin_dir_path( __FILE__ ),
			'dir'              => plugin_dir_url( __FILE__ ),

			'field_types'      => array(
				'text'     => 'Text',
				'textarea' => 'Textarea',
				'select'   => 'Drop down',
				'radio'    => 'Radio button(s)',
				'checkbox' => 'Checkbox(es)',
				'title'    => 'Title',
			),

			'setting_defaults' => array(
				'id'               => 0,
				'all_fields_wrap'  => 'ul', // ul, div, or ''
				'all_fields_class' => 'arc-form-wrap',
				'field_wrap'       => 'li', // li, div, p, or ''
				'field_class'      => 'arc-form-field',
				'field_prefix'     => 'arc_',
			),
		);

		require_once('inc/helpers.php');



		if( ! is_admin() ) {
			arc_include('inc/template-tags.php');
			arc_include('class-arc-forms-renderer.php');
			arc_include('class-arc-forms-validator.php');

			Architect_Forms_Renderer::get_instance();
			Architect_Forms_Validator::get_instance();
		}


		if( is_admin() ) {
			arc_include('class-arc-forms-admin.php');

			Architect_Forms_Admin::get_instance();
		}

	}
}
