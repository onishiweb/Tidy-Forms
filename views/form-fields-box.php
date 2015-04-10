<div class="arc-fields-header">
	<table class="widefat arc-fields-table">
		<thead>
			<tr>
				<th class="row-title">#</th>
				<th class="arc-long-col">Field name</th>
				<th class="arc-long-col">Field type</th>
				<th class="arc-action"></th>
				<th class="arc-action"></th>
			</tr>
		</thead>
	</table>

	<div class="arc-fields-no-fields">
		<?php _e('There are no fields currently. Click on the button below to add a new field.', 'arcforms'); ?>
	</div>
</div>

<div class="arc-fields arc-fields-sortable">
	<!-- Fields go here -->
	<?php
	if( ! empty($args) ):
		$count = count($args);
		foreach( $args as $field ):
			arc_get_view('form-field', $field);
		endforeach;
	else:
		$count = 0;
	endif;
	?>
</div>

<input type="hidden" name="arc_fields_count" id="arc-fields-count" value="<?php echo $count; ?>">
<input type="button" class="button-secondary arc-button-add-field" value="Add field" arc-action-add-field>

<!-- Placeholder HTML to save building this all with JavaScript -->
<?php arc_get_view('form-field'); ?>

<!-- For thickbox type selector -->
<div style="display:none">
	<?php arc_get_view('form-field-type-selector'); ?>
</div>
