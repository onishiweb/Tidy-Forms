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
?>
<?php 

function tidy_add_form($args) {

	if( $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST) && isset($_POST['tidy_forms_submit']) ) {
		
		unset($_POST['tidy_forms_submit']);

		if($_POST['user_name'] == '' || $_POST['user_email'] === '' ) {
			echo '<p class="error">There was an error!</p>';
		} else {
			// Send email
			$sent = true;
		}
	}	
		
	if( isset($sent) ) {
		echo 'Form submitted';
	} else {
		?>
		<form action="" method="post">
			<label>Name</label>
			<input type="text" name="user_name" value="<?php if( isset($_POST['user_name']) ) { echo $_POST['user_name']; } ?>"><br>

			<label>Email</label>
			<input type="email" name="user_email" value="<?php if( isset($_POST['user_email']) ) { echo $_POST['user_email']; } ?>"><br>
			
			<input type="submit" name="tidy_forms_submit">
		</form>
		<?php
	}
}

add_action( 'tidy_forms', 'tidy_add_form' );

?>
