<dl class="tidy-entry-data-list">
	<?php if( ! empty($args) ): foreach( $args as $field ): ?>
		<dt><?php echo $field['label']; ?></dt>
		<dd><?php echo $field['entry']; ?></dd>
	<?php endforeach; endif; ?>
</dl>
