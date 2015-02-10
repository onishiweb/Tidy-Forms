<div class="arc-field editing arc-field-placeholder">

	<table class="widefat arc-fields-table">
		<tbody>
			<tr valign="top">
				<th class="row-title">{#}</th>
				<td class="arc-long-col"><span>Field name</span></td>
				<td class="arc-long-col"><span>Field type</span></td>
				<td class="arc-action"><input type="button" class="button-secondary" value="Edit" arc-action-edit></td>
				<td class="arc-action"><input type="button" class="arc-button-delete" value="Delete" arc-action-delete></td>
			</tr>
		</tbody>
	</table>

	<div class="arc-field-settings">

		<fieldset>
			<label for="arc-field-label-{#}">Field label</label>
			<input type="text" name="arc_field_{#}[label]" id="arc-field-label-{#}" class="large-text" placeholder="Name" value="">
			<p class="description">Enter the label to display alongside the field.</p>
		</fieldset>

		<fieldset>
			<label for="arc-field-type-{#}">Field type</label>
			<select name="arc_field_{#}[type]" id="arc-field-type-{#}" class="arc-field-select">
				<option selected="selected" value="">Select a field type</option>
				<option value="text">Text</option>
				<option value="textarea">Textarea</option>
				<option value="select">Dropdown</option>
				<option value="radio">Radio button(s)</option>
				<option value="checkbox">Checkbox(es)</option>
			</select>
			<p class="description">Select the type of the field.</p>

			<div class="arc-field-type-options">
				<label for="arc-field-type-options-{#}">Field input options</label>
				<textarea name="arc_field_{#}[input_options]" id="arc-field-type-options-{#}" class="large-text" cols="80" rows="5"></textarea>
				<p class="description">Enter options for dropdown, radio, or checkboxes (one per line)</p>
			</div>
		</fieldset>

		<fieldset>
			<label for="arc-field-description-{#}">Description/instructions</label>
			<textarea name="arc_field_{#}[description]" id="arc-field-description-{#}" class="large-text" cols="80" rows="5"></textarea>
			<p class="description">Add a description/instruction text to the field.</p>
		</fieldset>

		<fieldset>
			<label for="arc-field-required-{#}">
				<input name="arc_field_{#}[required]" type="checkbox" id="arc-field-required-{#}">
				Required?
			</label>
		</fieldset>

		<div class="arc-field-advanced-options">
			<div class="arc-fields-header">
				<h4>Advanced options</h4>
				<p class="description">Configure your form even further.</p>
			</div>

			<fieldset>
				<label for="arc-field-name-{#}">Field name</label>
				<input type="text" name="arc_field_{#}[name]" id="arc-field-name-{#}" class="large-text" placeholder="name" value="">
				<p class="description">Customise the name attribute used in the field.</p>
			</fieldset>

			<fieldset>
				<label for="arc-field-classes-{#}">Custom HTML classes (space separated)</label>
				<input type="text" name="arc_field_{#}[classes]" id="arc-field-classes-{#}" class="large-text" value="">
				<p class="description">Customise the classes used for the label and form input.</p>
			</fieldset>

			<fieldset>
				<label for="arc-field-id-{#}">Custom HTML ID</label>
				<input type="text" name="arc_field_{#}[custom_id]" id="arc-field-id-{#}" class="large-text" value="">
				<p class="description">Customise the ID used for the input field.</p>
			</fieldset>
		</div>

		<fieldset class="arc-field-actions arc-clearfix">
			<input type="hidden" name="arc_field_{#}[order]" class="arc-field-order" value="{#}">
			<button class="button-primary arc-button" arc-action-finished-editing>Done</button>
			<button class="button-secondary arc-button" arc-action-advanced-fields>Advanced field options</button>
		</fieldset>

	</div>
</div>
