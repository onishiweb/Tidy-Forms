<?php
/**
 * Tidy Forms admin class file
 *
 * @package 	Tidy Forms
 * @author 		Adam Onishi	<onishiweb@gmail.com>
 * @license 	GPL2
 * @copyright 	2016 Adam Onishi
 */

class Tidy_Forms_Admin {
	/**
	 * An instance of the class
	 *
	 * @var null
	 */
	protected static $instance = null;

	protected static $screen = null;

	private function __construct() {

		// Include Stylesheet/scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts_styles') );

		// Create post type
		add_action( 'init', array( $this, 'setup_post_type') );

		// Setup submenu for entries
		add_action( 'admin_menu', array( $this, 'register_submenu_page') );

		// Add meta boxes for form creation
		add_action( 'add_meta_boxes_tidy_form', array( $this, 'setup_form_meta_boxes' ) );
		add_action( 'add_meta_boxes_tidy_form_entry', array( $this, 'setup_entry_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_form_data' ) );
		add_action( 'save_post', array( $this, 'save_entry_data' ) );

        add_action( 'post_updated_messages', array( $this, 'update_notices') );

		// Edit post columns for entries post type
		add_filter( 'manage_tidy_form_entry_posts_columns', array( $this, 'entries_table_columns' ) );
		add_action( 'manage_tidy_form_entry_posts_custom_column', array( $this, 'entries_table_content' ), 10, 2 );

		add_filter( 'pre_get_posts', array( $this, 'entries_table_filter' ) );

		// Add shortcode
		add_action( 'edit_form_before_permalink', array( $this, 'form_shortcode_helper' ) );
		// add new buttons
		add_filter('mce_buttons', array( $this, 'register_tinymce_buttons' ) );
		// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
		add_filter('mce_external_plugins', array( $this, 'register_tinymce_javascript' ) );
		// Return the form names and IDs via Ajax
		add_action('wp_ajax_tidy_get_form_values', array( $this, 'get_tinymce_form_data' ) );

    // Export action
    add_action( 'admin_post_tidy_export_entries', array('Tidy_Forms_Data', 'export_form'), 1 );

	}

	public static function get_instance() {
		if( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public static function admin_scripts_styles() {

		wp_enqueue_style( 'tidy-forms-tinymce', tidy_get_dir('css/tinymce.css'), false, tidy_get_setting('version'), 'screen' );

		$screen = get_current_screen()->id;

		$tidy_form_screens = array(
				'tidy_form',
				'tidy_form_entry',
				'edit-tidy_form_entry',
				'tidy_form_page_tidy-form-entries',
			);

		if ( in_array($screen, $tidy_form_screens) ) {
			wp_enqueue_style( 'tidy-forms-admin', tidy_get_dir('css/admin.css'), false, tidy_get_setting('version'), 'screen' );

			wp_enqueue_script( 'tidy-forms-admin-js', tidy_get_dir('js/admin.js'), array('jquery', 'jquery-ui-core', 'jquery-ui-sortable'), tidy_get_setting('version'), true );
		}
	}

	public function setup_post_type() {

		/**
		 * Custom form post type
		 */
		 $labels = array(
			'name'                => __('Tidy Forms', 'dirtisgood'),
			'singular_name'       => __('Form', 'dirtisgood'),
			'add_new'             => __('Add New', 'dirtisgood'),
			'add_new_item'        => __('Add New Form', 'dirtisgood'),
			'edit_item'           => __('Edit Form', 'dirtisgood'),
			'new_item'            => __('New Form', 'dirtisgood'),
			'all_items'           => __('All Forms', 'dirtisgood'),
			'view_item'           => __('View Form', 'dirtisgood'),
			'search_items'        => __('Search Forms', 'dirtisgood'),
			'not_found'           => __('No forms found', 'dirtisgood'), // USE THIS ADAM!
			'not_found_in_trash'  => __('No forms found in Trash', 'dirtisgood'),
			'parent_item_colon'   => '',
			'menu_name'           => __('Tidy Forms', 'dirtisgood'),
		);

		$args = array(
			'labels'              => $labels,
			'description'         => 'Tidy Forms admin',
			'public'              => true,
			'capability_type'     => 'post',
			'has_archive'         => false,
			'hierarchical'        => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'menu_position'       => 100,
			'menu_icon'           => 'dashicons-feedback',
			'supports'            => array( 'title', 'author', 'revisions' ),
		);

		register_post_type( 'tidy_form', $args );

		/**
		 * Entries post type
		 */
		$labels = array(
			'name'                => __('Entries', 'dirtisgood'),
			'singular_name'       => __('Entry', 'dirtisgood'),
			'add_new'             => __('Add New', 'dirtisgood'),
			'add_new_item'        => __('Add New Entry', 'dirtisgood'),
			'edit_item'           => __('Edit Entry', 'dirtisgood'),
			'new_item'            => __('New Entry', 'dirtisgood'),
			'all_items'           => __('All Entries', 'dirtisgood'),
			'view_item'           => __('View Entry', 'dirtisgood'),
			'search_items'        => __('Search Entries', 'dirtisgood'),
			'not_found'           => __('No entries found', 'dirtisgood'), // USE THIS ADAM!
			'not_found_in_trash'  => __('No entries found in Trash', 'dirtisgood'),
			'parent_item_colon'   => '',
			'menu_name'           => __('Entries', 'dirtisgood'),
		);

		$args = array(
			'labels'              => $labels,
			'description'         => 'Tidy Form entries',
			'public'              => true,
			'capability_type'     => 'post',
			'has_archive'         => false,
			'hierarchical'        => false,
			'exclude_from_search' => true,
			'show_in_menu'        => false,
			'publicly_queryable'  => false,
			'supports'            => false,
		);

		register_post_type( 'tidy_form_entry', $args );
	}

	public static function entries_table_columns( $defaults ) {

		if( isset($_GET['tidy_form_id']) ) {
			$form = get_post($_GET['tidy_form_id']);

			$content = get_post_meta( $post_id, '_tidy_form_data', true );
			$fields = $content['fields'];
			$max = max(3, count($fields));

			$defaults = array(
					'cb'     => '<input type="checkbox" />',
					'id'     => 'ID',
				);

			for( $i=0; $i<$max; $i++) {
				if( 'title' !== $fields[$i]['type'] ) {
					$defaults[ $fields[$i]['name'] ] = $fields[$i]['label'];
				}
			}

			$defaults['date'] = 'Date';

		}

		return $defaults;
	}

	public static function entries_table_content( $column_name, $post_id ) {
		if( 'id' === $column_name ) {
			$edit_url = admin_url( 'post.php?post='. $post_id . '&action=edit');

			$title = '<strong><a href="' . $edit_url . '" class="row-title">' . $post_id . '</a></strong>';

			$actions = sprintf('<div class="row-actions"><span class="edit"><a href="%s">View entry</a></span></div>',$edit_url);

			echo sprintf('%1$s %2$s', $title, $actions );
		} else {
			// Get content from post meta
			$meta = '_tidy_' . $column_name;
			echo get_post_meta( $post_id, $meta, true );
		}
	}

	public static function entries_table_filter( $query ) {

    if( ! get_current_screen() ) {
      return $query;
    }

		if( is_admin() && 'edit-tidy_form_entry' === get_current_screen()->id && isset( $_GET['tidy_form_id'] ) ) {
			$query->set( 'meta_key', '_tidy_form_id' );
			$query->set( 'meta_value', $_GET['tidy_form_id'] );
		}

		return $query;
	}

	public static function register_submenu_page() {
		add_submenu_page( 'edit.php?post_type=tidy_form', 'Entries', 'Entries', 'edit_theme_options', 'tidy-form-entries', array( 'Tidy_Forms_Admin', 'render_entries_submenu' ) );
		add_submenu_page( 'edit.php?post_type=tidy_form', 'Export entries', 'Export', 'edit_theme_options', 'tidy-form-export', array( 'Tidy_Forms_Admin', 'render_export_submenu' ) );
	}

	public static function render_entries_submenu() {
		$data_table = new Tidy_List_Table();
		$data_table->prepare_items();

		tidy_get_view('entries', $data_table);
	}

	public static function render_export_submenu() {
		$forms = new WP_Query( array('post_type' => 'tidy_form', 'posts_per_page' => '-1') );

		$args = array(
				'forms'      => $forms->posts,
				'export_url' => plugins_url( 'export.php', __FILE__ ),
			);

		tidy_get_view('export', $args);
	}

	public function setup_form_meta_boxes() {
		add_meta_box(
			'tidy-form-fields',
			'Fields',
			array( $this, 'render_fields_meta' ),
			'tidy_form',
			'normal',
			'default'
		);

		add_meta_box(
			'tidy-form-settings',
			'Form settings',
			array( $this, 'render_settings_meta' ),
			'tidy_form',
			'normal',
			'default'
		);

		add_meta_box(
			'tidy-form-entries',
			'Form entries',
			array( $this, 'render_entries_meta' ),
			'tidy_form',
			'side',
			'default'
		);
	}

	public function setup_entry_meta_boxes() {

		add_meta_box(
			'tidy-entry-data',
			'Form entry',
			array( $this, 'render_entry_content' ),
			'tidy_form_entry',
			'normal',
			'default'
		);

		add_meta_box(
			'tidy-entry-notes',
			'Notes',
			array( $this, 'render_entry_notes' ),
			'tidy_form_entry',
			'normal',
			'default'
		);

	}

	public function render_fields_meta( $post ) {
		// Get fields data
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'tidy_fields_nonce' );

		// Get settings data
		$content = get_post_meta( $post->ID, '_tidy_form_data', true );
		$fields = $content['fields'];

		// Include fields meta view
		if( isset($content['fields']) ) {
            $fields = $content['fields'];
            tidy_get_view('form-fields-box', $fields);
        } else {
            tidy_get_view('form-fields-box');
        }
	}

	public function render_settings_meta( $post ) {
		// No nonce here, already set in fields meta box

		// Get settings data
		$content = get_post_meta( $post->ID, '_tidy_form_data', true );

        if( isset($content['settings']) ) {
            $settings = $content['settings'];
        } else {
            $settings = array();
        }

		// Give the submit text a default setting if none set
		if( empty($settings['submit_text']) ) {
			$settings['submit_text'] = 'Submit';
		}

		// Include settings meta view
		tidy_get_view('form-settings', $settings);
	}

	public function render_entries_meta( $post ) {

		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'tidy_entries_export_nonce' );

		// Get entries count
		$entry_count = get_post_meta( $post->ID, '_tidy_form_entry_count', true );

		// Setup args
		$args = array(
				'form_id'     => $post->ID,
				'entry_count' => $entry_count,
				'entry_link'  => admin_url( 'edit.php?post_type=tidy_form_entry&tidy_form_id='. $post->ID ),
				'export_url'  => plugins_url( 'export.php', __FILE__ ),
			);

		tidy_get_view('form-entries-sidebar', $args);

	}

	public function render_entry_content( $post ) {
		$entry_id = $post->ID;
		$form_id = get_post_meta( $entry_id, '_tidy_form_id', true );

		$content = get_post_meta( $form_id, '_tidy_form_data', true );
		$fields = $content['fields'];

		$data = array();

		foreach( $fields as $field ) {
			$data[] = array(
					'label' => $field['label'],
					'entry' => get_post_meta( $entry_id, '_tidy_' . $field['name'], true),
				);
		}

		tidy_get_view('entry-data', $data);
	}

	public function render_entry_notes( $post ) {
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'tidy_entry_nonce' );

		$notes = get_post_meta( $post->ID, '_tidy_entry_note', true );

		$data = array(
				'notes' => $notes,
			);

		tidy_get_view('entry-notes', $data);
	}

	public function save_form_data( $post_id ) {

		// verify if this is an auto save routine.
		// If it is the post has not been updated, so we don’t want to do anything
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// verify this came from the screen and with proper authorization,
		// because save_post can be triggered at other times
		if ( !isset( $_POST['tidy_fields_nonce'] ) || !wp_verify_nonce( $_POST['tidy_fields_nonce'], plugin_basename( __FILE__ ) ) ) {
			return $post_id;
		}

		// Get the post type object.
		global $post;
		$post_type = get_post_type_object( $post->post_type );

		// Check if the current user has permission to edit the post.
		if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return $post_id;
		}

		if ( wp_is_post_revision( $post_id ) ) {
			return $post_id;
		}

		// unhook this function so it doesn't loop infinitely
		remove_action('save_post', array( $this, 'save_form_data' ) );

		// Get fields array
		$fields = array();
		$field_count = $_POST['tidy_fields_count'];

		for( $i=1; $i<=$field_count; $i++) {
			$field_order = $_POST['tidy_field_' . $i]['order'];
			$fields[ $field_order ] = $_POST['tidy_field_' . $i];
		}

		// Get settings array
		$settings = $_POST['tidy_settings'];

		$all_data = array(
				'fields'   => $fields,
				'settings' => $settings,
			);

    $key = '_tidy_form_data';

    $current = get_post_meta( $post_id, $key, true );
    $value = $all_data;

    // add/update record (both are taken care of by update_post_meta)
    if ( $value && '' == $current ) {
      add_post_meta( $post_id, $key, $value, true );
    } elseif ( $value && $value != $current ) {
      update_post_meta( $post_id, $key, $value );
    } elseif ( '' == $value && $current ) {
      delete_post_meta( $post_id, $key, $current );
    }

		add_action('save_post', array( $this, 'save_form_data' ) );
	}

	public function save_entry_data( $post_id ) {
		// verify if this is an auto save routine.
		// If it is the post has not been updated, so we don’t want to do anything
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// verify this came from the screen and with proper authorization,
		// because save_post can be triggered at other times
		if ( !isset( $_POST['tidy_entry_nonce'] ) || !wp_verify_nonce( $_POST['tidy_entry_nonce'], plugin_basename( __FILE__ ) ) ) {
			return $post_id;
		}

		// Get the post type object.
		global $post;
		$post_type = get_post_type_object( $post->post_type );

		// Check if the current user has permission to edit the post.
		if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return $post_id;
		}

		if ( wp_is_post_revision( $post_id ) ) {
			return $post_id;
		}

		$key = '_tidy_entry_note';

		$current = get_post_meta( $post_id, $key, true );
		$value = $_POST['tidy_entry_notes'];

		// add/update record (both are taken care of by update_post_meta)
		if ( $value && '' == $current ) {
			add_post_meta( $post_id, $key, $value, true );
		} elseif ( $value && $value != $current ) {
			update_post_meta( $post_id, $key, $value );
		} elseif ( '' == $value && $current ) {
			delete_post_meta( $post_id, $key, $current );
		}

	}

  public static function update_notices( $messages ) {
    $messages['tidy_form'] = array(
      0  => '',
      1  => 'Form updated.',
      2  => 'Form updated.',
      3  => 'Form updated.',
      4  => 'Form updated.',
      5  => '',
      6  => 'Form created.',
      7  => 'Form saved.',
      8  => 'Form updated.',
      9  => 'Form scheduled.',
      10 => 'Form draft updated.',
    );

    return $messages;
  }

	public function form_shortcode_helper( $post ) {

		// Include shortcode view if on the tidy_form admin screen
		if( 'tidy_form' === get_current_screen()->id ) {
			tidy_get_view('form-shortcode', $post);
		}

	}

	public static function register_tinymce_buttons($buttons) {
	   array_push($buttons, 'separator', 'Tidy_Forms');

	   return $buttons;
	}

	public static function register_tinymce_javascript($plugin_array) {

	   $plugin_array['Tidy_Forms'] = tidy_get_dir('js/tinymce.js');

	   return $plugin_array;
	}

	public static function get_tinymce_form_data() {
		$form_data = array();
		$args = array(
				'post_type'      => 'tidy_form',
				'posts_per_page' => '-1',
				'post_status'    => 'publish',
			);

		$forms = new WP_Query( $args );

		if( $forms->have_posts() ) {

			foreach( $forms->posts as $form ) {
				$form_data[] = array(
						'text'  => $form->post_title,
						'value' => $form->ID,
					);
			}

		} else {
			$form_data[] = array(
				'text'  => 'No forms created',
				'value' => '',
			);
		}

		$response = json_encode( $form_data );

		die( $response );
	}
}
