<!-- Form title -->
<?php if( tidy_should_output_title() ): ?>
	<h2><?php tidy_the_form_title(); ?></h2>
<?php endif; ?>

<!-- Form introduction -->
<?php tidy_the_form_intro(); ?>

<!-- Form -->
<form action="#<?php tidy_the_form_id(); ?>" method="post" class="<?php tidy_the_form_class(); ?>">
	<!-- Form fields wrap -->
	<?php tidy_the_form_fields_before(); ?>

		<?php if( tidy_form_have_fields() ): foreach( tidy_the_form_fields() as $field ): ?>

			<?php tidy_the_form_field($field); ?>

		<?php endforeach; endif; ?>

		<?php tidy_the_form_submit(); ?>

	<?php tidy_the_form_fields_after(); ?>
</form>
