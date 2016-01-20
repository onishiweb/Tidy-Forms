<div class="wrap">
	<h2>Export entries</h2>

	<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post">
		<p>Select a form to export the entries from:</p>

		<ul>
			<li>
				<label for="tidy-select-form">Form</label><br>
				<select name="tidy_export_form" id="tidy-select-form">
					<?php foreach( $args['forms'] as $form ): ?>
						<option value="<?php echo $form->ID; ?>"><?php echo $form->post_title; ?></option>
					<?php endforeach; ?>
				</select>
			</li>
			<li>
				<?php wp_nonce_field( 'tidy_form_entries_export', 'tidy_form_export_nonce', false ); ?>
        <input type="hidden" name="action" value="tidy_export_entries">
				<input type="submit" class="button-primary" name="tidy_export_entries" value="Export">
			</li>
		</ul>
	</form>
</div>
