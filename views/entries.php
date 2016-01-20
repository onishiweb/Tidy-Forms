<div class="wrap">
	<h2><?php _e('Form entries', 'tidyforms'); ?></h2>

	<form method="post">
	    <?php $args->search_box('search', 'search_id'); ?>
	</form>

	<?php $args->display(); ?>
</div>
