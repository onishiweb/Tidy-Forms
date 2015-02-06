<?php
/**
 * Helpers
 *
 * @package  TidyForms
 */

/**
 *  arc_get_setting
 *
 *  This function will return a value from the settings array in the main class object
 *
 *  @param	[string] $name the setting name to return
 *  @return	[mixed]
 */

function arc_get_setting( $name, $allow_filter = true ) {

	// vars
	$r = null;
	$arc = Architect_Forms::get_instance();


	// load from ACF if available
	if( isset( $arc->settings[ $name ] ) ) {

		$r = $arc->settings[ $name ];

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
 * arc_get_dir
 *
 * Returns the url to a file within the plugin folder
 *
 * @param  [string] $path the relative path from the root of the plugin folder
 * @return [string]
 */
function arc_get_dir( $path ) {

	return arc_get_setting('dir') . $path;

}

/**
 * arc_get_path
 *
 * Returns the path to a file within the plugin directory
 *
 * @param  [string] $path The relative path from the root of the plugin folder
 * @return [string]
 */
function arc_get_path( $path ) {

	return arc_get_setting('path') . $path;

}

/**
 * arc_include
 *
 * Includes file after checking whether the file exists
 * - based on arc_include
 *
 * @param  [string] $file path to the file to include
 */
function arc_include( $file ) {

	$path = arc_get_path( $file );

	if( file_exists($path) ) {

		include_once( $path );

	}

}

/**
 *  arc_get_view
 *
 *  This function will load in a file from the views folder and allow variables to be passed through
 *
 *  @param	[string] $view_name Name of the view to load
 *  @param	[array] $args Variable for us in the view
 */

function arc_get_view( $view_name = '', $args = array() ) {

	// vars
	$path = arc_get_path("views/{$view_name}.php");

	if( file_exists($path) ) {

		include( $path );

	}

}

/**
 * arc_isset_echo
 *
 * This function checks whether an index is set in an array and if so prints it out
 *
 * @param  [array] $arr Array of data
 * @param  [string] $var Index to search for
 */
function arc_isset_echo( $arr, $var ) {

	if( isset( $arr[ $var ] ) ) {
		echo $arr[ $var ];
	}

}
