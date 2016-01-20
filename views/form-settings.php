<fieldset class="tidy-fieldset">
	<label for="tidy-intro-text">Form introduction text (optional)</label>
	<textarea name="tidy_settings[intro_text]" id="tidy-intro-text" class="large-text" cols="80" rows="5"><?php tidy_isset_echo( $args, 'intro_text' ); ?></textarea>
	<p class="description">Displays at the top of the form, and provides a brief introduction / instruction for the user.</p>
</fieldset>

<fieldset class="tidy-fieldset">
	<label for="tidy-submit-text">Submit button text</label>
	<input type="text" name="tidy_settings[submit_text]" id="tidy-submit-text" class="large-text" placeholder="Submit" value="<?php echo $args['submit_text']; ?>">
	<p class="description">Text that displays on the submit button at the end of the form.</p>
</fieldset>

<fieldset class="tidy-fieldset">
	<label for="tidy-thank-you-text">Thank you text</label>
	<textarea name="tidy_settings[thank_you_text]" id="tidy-thank-you-text" class="large-text" cols="80" rows="5"><?php tidy_isset_echo( $args, 'thank_you_text' ); ?></textarea>
	<p class="description">Displays as a confirmation when the user has completed the form.</p>
</fieldset>

<fieldset class="tidy-fieldset">
	<?php
	$via_email = '';
	if( isset( $args['send_via_email'] ) ) {
		$via_email = $args['send_via_email'];
	}
	?>
	<label for="tidy-send-via-email">
		<input name="tidy_settings[send_via_email]" type="checkbox" id="tidy-send-via-email" <?php checked( $via_email, 'on' ); ?>>
		Send entries via email?
	</label>
	<p class="description">Check if you would like the entries of the form sent to an email address.</p>

	<div class="tidy-field-extra-options tidy-form-to-email">
		<label for="tidy-form-to-email">Email to:</label>
		<input type="text" name="tidy_settings[send_to_email]" id="tidy-form-to-email" class="large-text" value="<?php tidy_isset_echo( $args, 'send_to_email' ); ?>">
		<p class="description">Enter the email entries should go to - leave blank to use WordPress admin email address.</p>
	</div>
</fieldset>
