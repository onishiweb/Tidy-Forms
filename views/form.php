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
<form action="#<?php architect_the_form_id(); ?>" method="post" class="<?php architect_the_form_class(); ?>">
	<!-- Form fields wrap -->
	<?php architect_the_form_fields_before(); ?>

		<?php if( architect_form_have_fields() ): foreach( architect_the_form_fields() as $field ): ?>

			<?php architect_the_form_field($field); ?>

		<?php endforeach; endif; ?>

		<?php architect_the_form_submit(); ?>

	<?php architect_the_form_fields_after(); ?>
</form>
