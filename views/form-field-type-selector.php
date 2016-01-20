<?php $field_types = tidy_get_setting('field_types', true); ?>

<div id="tidy-field-type-selector" class="tidy-field-type-selector tidy-clearfix">
	<h3><?php _e('Add a new field', 'tidyforms'); ?></h3>


	<p><?php _e('Select a field type:', 'tidyforms'); ?></p>

	<ul>
		<?php foreach( $field_types as $value => $label ): ?>
			<li>
				<button class="tidy-field-type-choice tidy-field-type-<?php echo $value; ?>" value="<?php echo $value; ?>">
					<span class="tidy-field-choice-label"><?php echo $label; ?></span>
				</button>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
