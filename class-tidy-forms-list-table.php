<?php
// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class Tidy_List_Table extends WP_List_Table {

	public function prepare_items() {
		$columns = $this->get_columns();
		$hidden = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();

		$data = $this->table_data();

		// implement ordering by using WP_Query not like in Paul Und's tutorial!

		$perPage = 20;
		$currentPage = $this->get_pagenum();
		$totalItems = count($data);

		$this->set_pagination_args( array(
			'total_items' => $totalItems,
			'per_page'    => $perPage
		) );

		$data = array_slice( $data, (($currentPage-1)*$perPage), $perPage );

		$this->_column_headers = array($columns, $hidden, $sortable);
		$this->items = $data;
	}

	/**
	* Override the parent columns method. Defines the columns to use in your listing table
	*
	* @return Array
	*/
	public function get_columns() {
		// Columns of data for Forms...
		// ID
		// Title
		// Shortcode
		// Entries

		$columns = array(
			'id'        => 'ID',
			'title'     => 'Title',
			'shortcode' => 'Shortcode',
			'entries'   => 'Entries',
		);

		return $columns;
	}

	/**
	* Define which columns are hidden
	*
	* @return Array
	*/
	public function get_hidden_columns() {
		return array();
	}

	/**
	* Define the sortable columns
	*
	* @return Array
	*/
	public function get_sortable_columns() {
		// Sortable by:
		// Title
		// Entries...
		$sortable = array(
				'title' => array('title', false),
				'id' => array('id', false),
				'entries' => array('entries', false),
			);

		return $sortable;
	}

	/**
	 * Get the table data
	 *
	 * @return Array
	 */
	private function table_data() {
		$data = array();

		// Query for forms here
		$args = array(
				'post_type'   => 'tidy_form',
				'post_status' => 'any',
			);

		if( isset($_GET['orderby']) ) {
			$args['orderby'] = $_GET['orderby'];
			$args['order'] = $_GET['order'];
		}

		// Use $_POST['s'] to search
		if( isset($_POST['s']) ) {
			$args['s'] = $_POST['s'];
		}

		$forms = new WP_Query( $args );

		// Arrange information that you need to have output in an array of arrays... (fill up $data array)
		foreach( $forms->posts as $form ) {
			$data[] = array(
					'id'        => $form->ID,
					'title'     => $form->post_title,
					'shortcode' => '[tidy-form id="' . $form->ID . '"]',
					'entries'   => get_post_meta( $form->ID, '_tidy_form_entry_count', true ),
				);
		}

		return $data;
	}

	/**
	 * Define the data and actions to show in the title column
	 *
	 * @param  Array $item Data for the item being displayed
	 * @return string      Content for the table
	 */
	function column_title($item) {
		$edit_url = admin_url( 'post.php?post='. $item['id'] . '&action=edit');
		$entries_url = admin_url( 'edit.php?post_type=tidy_form_entry&tidy_form_id='. $item['id'] );

		$title = '<strong><a href="' . $entries_url . '" class="row-title">' . $item['title'] . '</a></strong>';

		$actions = array(
			'edit'    => sprintf('<a href="%s">Edit</a>',$edit_url),
			'entries' => sprintf('<a href="%s">View entries</a>',$entries_url),
		);

		return sprintf('%1$s %2$s', $title, $this->row_actions($actions) );
	}

	/**
	 * Define what data to show on each column of the table
	 *
	 * @param  Array $item        Data
	 * @param  String $column_name - Current column name
	 *
	 * @return Mixed
	 */
	public function column_default( $item, $column_name ) {
		switch( $column_name ) {
			case 'id':
			case 'shortcode':
			case 'entries':
				return $item[ $column_name ];
			default:
				return $item;
		}
	}

}
