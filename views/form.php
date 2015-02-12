<?php
// $args;
?>
<!-- Form title -->
<?php if( architect_should_output_title() ): ?>
	<h2><?php architect_the_form_title(); ?></h2>
<?php endif; ?>

<!-- Form introduction -->
<?php architect_the_form_intro(); ?>

<!-- Form -->
<form action="#arc-form" method="post" class="arc-form">
	<!-- Form fields wrap -->
	<ul class="arc-form-wrap">

		<!-- Field before -->
		<li class="arc-form-field">
			<label for=""></label>
			<input type="text">
			<p class="description"></p>
		<!-- Field after -->
		</li>

	</ul>
</form>
