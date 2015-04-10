<?php

require('../../../wp-load.php');

// If file is called directly, abort
if( ! defined( 'WPINC' ) ) {
	die;
}

if( ! class_exists('Architect_Forms_Data') ) :
	// Load the plugin class file
	require_once( plugin_dir_path( __FILE__ ) . 'class-arc-forms-data.php' );
endif;

if( isset($_GET['arc_export_form']) ) {
	Architect_Forms_Data::export_form( $_GET['arc_export_form'] );
} else {
	wp_die( 'Sorry you have made an error in coming here' );
}
