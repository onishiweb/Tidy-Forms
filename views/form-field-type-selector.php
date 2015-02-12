<?php $field_types = arc_get_setting('field_types', true); ?>

<div id="arc-field-type-selector">
	<ul>
		<?php foreach( $field_types as $value => $label ): ?>
			<li><button class="arc-field-type-choice" value="<?php echo $value; ?>"><?php echo $label; ?></button></li>
		<?php endforeach; ?>
	</ul>
</div>
