<?php
/**
 * Setup shorter variables
 */

// Get settings (filterable)
$field_types = tidy_get_setting('field_types', true);
$field_validation = tidy_get_setting('validation_methods', true);
// Defaults
$required = '';
$type = false;
$validation = false;
$order = '{#}';
$field_class = 'editing tidy-field-placeholder';
// Placeholder labels
$field_label = '<span>Field name</span>';
$type_label = '<span>Field type</span>';

if( ! empty($args) ) {
	$order = $args['order'];
	$field_class = '';
	$field_label = $args['label'];
	$type = $args['type'];
	$type_label = $field_types[ $type ];
	$validation = $args['text_validation'];

	if( isset($args['required']) ) {
		$required = $args['required'];
	}
}

$field_name = 'tidy_field_' . $order;

?>

<div class="tidy-field <?php echo $field_class; ?>">

	<table class="widefat tidy-fields-table">
		<tbody>
			<tr valign="top">
				<th class="row-title"><?php echo $order; ?></th>
				<td class="tidy-long-col tidy-field-info-label"><?php echo $field_label; ?></td>
				<td class="tidy-long-col tidy-field-info-type"><?php echo $type_label; ?></td>
				<td class="tidy-action"><input type="button" class="button-secondary" value="Edit" tidy-action-edit></td>
				<td class="tidy-action"><input type="button" class="tidy-button-delete" value="Delete" tidy-action-delete></td>
			</tr>
		</tbody>
	</table>

	<div class="tidy-field-settings">

		<fieldset class="tidy-fieldset">
			<label for="tidy-field-label-<?php echo $order; ?>">Field label</label>
			<input type="text" name="<?php echo $field_name; ?>[label]" id="tidy-field-label-<?php echo $order; ?>" class="large-text" placeholder="Name" value="<?php tidy_isset_echo( $args, 'label' ); ?>">
			<p class="description">Enter the label to display alongside the field.</p>
		</fieldset>

		<fieldset class="tidy-fieldset">
			<label for="tidy-field-type-<?php echo $order; ?>">Field type</label>
			<select name="<?php echo $field_name; ?>[type]" id="tidy-field-type-<?php echo $order; ?>" class="tidy-field-select tidy-field-type-select">
				<option <?php if( ! $type ) { echo 'selected="selected"'; } ?> value="">Select a field type</option>

				<?php foreach( $field_types as $value => $label ): ?>
					<option value="<?php echo $value; ?>" <?php selected($type, $value); ?>><?php echo $label; ?></option>
				<?php endforeach; ?>

			</select>

			<div class="tidy-field-extra-options">

				<div class="tidy-field-text-validation">
					<label for="tidy-field-text-validation-<?php echo $order; ?>">Validation method</label>
					<select name="<?php echo $field_name; ?>[text_validation]" id="tidy-field-text-validation-<?php echo $order; ?>" class="tidy-field-select">
						<option <?php if( ! $validation ) { echo 'selected="selected"'; } ?> value="">Any text</option>
						<?php foreach( $field_validation as $value => $label ): ?>
							<option value="<?php echo $value; ?>" <?php selected($validation, $value); ?>><?php echo $label; ?></option>
						<?php endforeach; ?>
					</select>
					<p class="description">Select validation to be applied to the text field (optional).</p>
				</div>

				<div class="tidy-field-options">
					<label for="tidy-field-type-options-<?php echo $order; ?>">Field input options</label>
					<textarea name="<?php echo $field_name; ?>[input_options]" id="tidy-field-type-options-<?php echo $order; ?>" class="large-text" cols="80" rows="5"><?php tidy_isset_echo( $args, 'input_options' ); ?></textarea>
					<p class="description">Enter options for dropdown, radio, or checkboxes (one per line)</p>
				</div>
			</div>

		</fieldset>

		<fieldset class="tidy-fieldset">
			<label for="tidy-field-description-<?php echo $order; ?>">Description/instructions</label>
			<textarea name="<?php echo $field_name; ?>[description]" id="tidy-field-description-<?php echo $order; ?>" class="large-text" cols="80" rows="5"><?php tidy_isset_echo( $args, 'description' ); ?></textarea>
			<p class="description">Add a description/instruction text to the field.</p>
		</fieldset>

		<fieldset class="tidy-fieldset">
			<label for="tidy-field-required-<?php echo $order; ?>">
				<input name="<?php echo $field_name; ?>[required]" type="checkbox" id="tidy-field-required-<?php echo $order; ?>" <?php checked( $required, 'on' ); ?>>
				Required?
			</label>
			<p class="description">Is this a required field?</p>
		</fieldset>

		<div class="tidy-field-advanced-options">
			<div class="tidy-fields-header">
				<h4>Advanced options</h4>
				<p class="description">Configure your form even further.</p>
			</div>

			<fieldset class="tidy-fieldset">
				<label for="tidy-field-name-<?php echo $order; ?>">Field name</label>
				<input type="text" name="<?php echo $field_name; ?>[name]" id="tidy-field-name-<?php echo $order; ?>" class="large-text" placeholder="name" value="<?php tidy_isset_echo( $args, 'name' ); ?>">
				<p class="description">Customise the name attribute used in the field.</p>
			</fieldset>

			<fieldset class="tidy-fieldset">
				<label for="tidy-field-classes-<?php echo $order; ?>">Custom HTML classes (space separated)</label>
				<input type="text" name="<?php echo $field_name; ?>[classes]" id="tidy-field-classes-<?php echo $order; ?>" class="large-text" value="<?php tidy_isset_echo( $args, 'classes' ); ?>">
				<p class="description">Customise the classes used for the label and form input.</p>
			</fieldset>

			<fieldset class="tidy-fieldset">
				<label for="tidy-field-id-<?php echo $order; ?>">Custom HTML ID</label>
				<input type="text" name="<?php echo $field_name; ?>[custom_id]" id="tidy-field-id-<?php echo $order; ?>" class="large-text" value="<?php tidy_isset_echo( $args, 'custom_id' ); ?>">
				<p class="description">Customise the ID used for the input field.</p>
			</fieldset>
		</div>

		<fieldset class="tidy-fieldset tidy-field-actions tidy-clearfix">
			<input type="hidden" name="<?php echo $field_name; ?>[order]" class="tidy-field-order" value="<?php echo $order; ?>">
			<button class="button-primary tidy-button" tidy-action-finished-editing>Done</button>
			<button class="button-secondary tidy-button" tidy-action-advanced-fields>Advanced field options</button>
		</fieldset>

	</div>
</div>
