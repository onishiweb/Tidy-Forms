<?php
/**
 * Setup shorter variables
 */

// Get settings (filterable)
$field_types = arc_get_setting('field_types', true);
// Defaults
$required = '';
$type = false;
$order = '{#}';
$field_class = 'editing arc-field-placeholder';
// Placeholder labels
$field_label = '<span>Field name</span>';
$type_label = '<span>Field type</span>';

if( ! empty($args) ) {
	$order = $args['order'];
	$field_class = '';
	$field_label = $args['label'];
	$type = $args['type'];
	$type_label = $field_types[ $type ];

	if( isset($args['required']) ) {
		$required = $args['required'];
	}
}

$field_name = 'arc_field_' . $order;

?>

<div class="arc-field <?php echo $field_class; ?>">

	<table class="widefat arc-fields-table">
		<tbody>
			<tr valign="top">
				<th class="row-title"><?php echo $order; ?></th>
				<td class="arc-long-col arc-field-info-label"><?php echo $field_label; ?></td>
				<td class="arc-long-col arc-field-info-type"><?php echo $type_label; ?></td>
				<td class="arc-action"><input type="button" class="button-secondary" value="Edit" arc-action-edit></td>
				<td class="arc-action"><input type="button" class="arc-button-delete" value="Delete" arc-action-delete></td>
			</tr>
		</tbody>
	</table>

	<div class="arc-field-settings">

		<fieldset>
			<label for="arc-field-label-<?php echo $order; ?>">Field label</label>
			<input type="text" name="<?php echo $field_name; ?>[label]" id="arc-field-label-<?php echo $order; ?>" class="large-text" placeholder="Name" value="<?php arc_isset_echo( $args, 'label' ); ?>" required>
			<p class="description">Enter the label to display alongside the field.</p>
		</fieldset>

		<fieldset>
			<label for="arc-field-type-<?php echo $order; ?>">Field type</label>
			<select name="<?php echo $field_name; ?>[type]" id="arc-field-type-<?php echo $order; ?>" class="arc-field-select">
				<option <?php if( ! $type ) { echo 'selected="selected"'; } ?> value="">Select a field type</option>

				<?php foreach( $field_types as $value => $label ): ?>
					<option value="<?php echo $value; ?>" <?php selected($type, $value); ?>><?php echo $label; ?></option>
				<?php endforeach; ?>

			</select>
			<p class="description">Select the type of the field.</p>

			<div class="arc-field-type-options">
				<label for="arc-field-type-options-<?php echo $order; ?>">Field input options</label>
				<textarea name="<?php echo $field_name; ?>[input_options]" id="arc-field-type-options-<?php echo $order; ?>" class="large-text" cols="80" rows="5"><?php arc_isset_echo( $args, 'input_options' ); ?></textarea>
				<p class="description">Enter options for dropdown, radio, or checkboxes (one per line)</p>
			</div>
		</fieldset>

		<fieldset>
			<label for="arc-field-description-<?php echo $order; ?>">Description/instructions</label>
			<textarea name="<?php echo $field_name; ?>[description]" id="arc-field-description-<?php echo $order; ?>" class="large-text" cols="80" rows="5"><?php arc_isset_echo( $args, 'description' ); ?></textarea>
			<p class="description">Add a description/instruction text to the field.</p>
		</fieldset>

		<fieldset>
			<label for="arc-field-required-<?php echo $order; ?>">
				<input name="<?php echo $field_name; ?>[required]" type="checkbox" id="arc-field-required-<?php echo $order; ?>" <?php checked( $required ); ?>>
				Required?
			</label>
		</fieldset>

		<div class="arc-field-advanced-options">
			<div class="arc-fields-header">
				<h4>Advanced options</h4>
				<p class="description">Configure your form even further.</p>
			</div>

			<fieldset>
				<label for="arc-field-name-<?php echo $order; ?>">Field name</label>
				<input type="text" name="<?php echo $field_name; ?>[name]" id="arc-field-name-<?php echo $order; ?>" class="large-text" placeholder="name" value="<?php arc_isset_echo( $args, 'name' ); ?>">
				<p class="description">Customise the name attribute used in the field.</p>
			</fieldset>

			<fieldset>
				<label for="arc-field-classes-<?php echo $order; ?>">Custom HTML classes (space separated)</label>
				<input type="text" name="<?php echo $field_name; ?>[classes]" id="arc-field-classes-<?php echo $order; ?>" class="large-text" value="<?php arc_isset_echo( $args, 'classes' ); ?>">
				<p class="description">Customise the classes used for the label and form input.</p>
			</fieldset>

			<fieldset>
				<label for="arc-field-id-<?php echo $order; ?>">Custom HTML ID</label>
				<input type="text" name="<?php echo $field_name; ?>[custom_id]" id="arc-field-id-<?php echo $order; ?>" class="large-text" value="<?php arc_isset_echo( $args, 'custom_id' ); ?>">
				<p class="description">Customise the ID used for the input field.</p>
			</fieldset>
		</div>

		<fieldset class="arc-field-actions arc-clearfix">
			<input type="hidden" name="<?php echo $field_name; ?>[order]" class="arc-field-order" value="<?php echo $order; ?>">
			<button class="button-primary arc-button" arc-action-finished-editing>Done</button>
			<button class="button-secondary arc-button" arc-action-advanced-fields>Advanced field options</button>
		</fieldset>

	</div>
</div>
