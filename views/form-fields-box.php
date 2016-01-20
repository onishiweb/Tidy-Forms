<div class="tidy-fields-header">
	<table class="widefat tidy-fields-table">
		<thead>
			<tr>
				<th class="row-title">#</th>
				<th class="tidy-long-col">Field name</th>
				<th class="tidy-long-col">Field type</th>
				<th class="tidy-action"></th>
				<th class="tidy-action"></th>
			</tr>
		</thead>
	</table>

	<div class="tidy-fields-no-fields">
		<?php _e('There are no fields currently. Click on the button below to add a new field.', 'tidyforms'); ?>
	</div>
</div>

<div class="tidy-fields tidy-fields-sortable">
	<!-- Fields go here -->
	<?php
	if( ! empty($args) ):
		$count = count($args);
		foreach( $args as $field ):
			tidy_get_view('form-field', $field);
		endforeach;
	else:
		$count = 0;
	endif;
	?>
</div>

<input type="hidden" name="tidy_fields_count" id="tidy-fields-count" value="<?php echo $count; ?>">
<input type="button" class="button-secondary tidy-button-add-field" value="Add field" tidy-action-add-field>

<!-- Placeholder HTML to save building this all with JavaScript -->
<?php tidy_get_view('form-field'); ?>

<!-- For thickbox type selector -->
<div style="display:none">
	<?php tidy_get_view('form-field-type-selector'); ?>
</div>
