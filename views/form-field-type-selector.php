<?php $field_types = arc_get_setting('field_types', true); ?>

<div id="arc-field-type-selector" class="arc-field-type-selector arc-clearfix">
	<h3><?php _e('Add a new field', 'arcforms'); ?></h3>


	<p><?php _e('Select a field type:', 'arcforms'); ?></p>

	<ul>
		<?php foreach( $field_types as $value => $label ): ?>
			<li>
				<button class="arc-field-type-choice arc-field-type-<?php echo $value; ?>" value="<?php echo $value; ?>">
					<span class="arc-field-choice-label"><?php echo $label; ?></span>
				</button>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
