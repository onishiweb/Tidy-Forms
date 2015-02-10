
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
</div>

<div class="arc-fields arc-fields-sortable">
	<!-- Fields go here -->
	<?php
	foreach( $args as $field ):
		arc_get_view('form-field', $field);
	endforeach;
	?>
</div>

<input type="hidden" name="arc_fields_count" id="arc-fields-count" value="0">
<input type="button" class="button-secondary arc-button-add-field" value="Add field" arc-action-add-field>

<!-- Placeholder HTML to save building this all with JavaScript -->
<?php arc_get_view('form-field'); ?>
