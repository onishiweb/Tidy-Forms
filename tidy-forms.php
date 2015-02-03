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

// If file is called directly, abort
if( ! defined( 'WPINC' ) ) {
	die;
}

if( ! class_exists('Tidy_Forms') ) :

	// Load the plugin class file
	require_once( plugin_dir_path( __FILE__ ) . 'class-tidy-forms.php' );

	// Load the plugin (not sure if this is really needed unless we're running the functionality)
	add_action( 'plugins_loaded', array( 'Tidy_Forms', 'get_instance' ) );

endif;
