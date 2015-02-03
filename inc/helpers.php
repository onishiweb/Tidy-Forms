<?php
/**
 * Helpers
 *
 * @package  TidyForms
 */

/**
 *  tidy_get_setting
 *
 *  This function will return a value from the settings array in the main class object
 *
 *  @param	[string] $name the setting name to return
 *  @return	[mixed]
 */

function tidy_get_setting( $name, $allow_filter = true ) {

	// vars
	$r = null;
	$tidy = Tidy_Forms::get_instance();


	// load from ACF if available
	if( isset( $tidy->settings[ $name ] ) ) {

		$r = $tidy->settings[ $name ];

	}

	/* @todo: add in filters when a convention has been decided */
	// filter for 3rd party customization
	/*
	if( $allow_filter ) {

		$r = apply_filters( "acf/settings/{$name}", $r );

	}
	*/

	// return
	return $r;
}

/**
 * tidy_get_dir
 *
 * Returns the url to a file within the plugin folder
 *
 * @param  [string] $path the relative path from the root of the plugin folder
 * @return [string]
 */
function tidy_get_dir( $path ) {

	return tidy_get_setting('dir') . $path;

}

/**
 * tidy_get_path
 *
 * Returns the path to a file within the plugin directory
 *
 * @param  [string] $path The relative path from the root of the plugin folder
 * @return [string]
 */
function tidy_get_path( $path ) {

	return tidy_get_setting('path') . $path;

}

/**
 * tidy_include
 *
 * Includes file after checking whether the file exists
 * - based on acf_include
 *
 * @param  [string] $file path to the file to include
 */
function tidy_include( $file ) {

	$path = tidy_get_path( $file );

	if( file_exists($path) ) {

		include_once( $path );

	}

}
