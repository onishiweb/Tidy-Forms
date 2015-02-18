<div class="wrap">
	<div id="icon-tools" class="icon32"></div>
	<h2><?php _e('Form entries', 'arcforms'); ?></h2>

	<form method="post">
	    <?php $args->search_box('search', 'search_id'); ?>
	</form>

	<?php $args->display(); ?>
</div>
