<fieldset>
	<label for="arc-intro-text">Form introduction text (optional)</label><br>
	<textarea name="arc_settings[intro_text]" id="arc-intro-text" class="large-text" cols="80" rows="5"><?php arc_isset_echo( $args, 'intro_text' ); ?></textarea>
	<p class="description">Displays at the top of the form, and provides a brief introduction / instruction for the user.</p>
</fieldset>

<fieldset>
	<label for="arc-submit-text">Submit button text</label>
	<input type="text" name="arc_settings[submit_text]" id="arc-submit-text" class="large-text" placeholder="Submit" value="<?php echo $args['submit_text']; ?>">
	<p class="description">Text that displays on the submit button at the end of the form.</p>
</fieldset>

<fieldset>
	<label for="arc-thank-you-text">Thank you text</label>
	<textarea name="arc_settings[thank_you_text]" id="arc-thank-you-text" class="large-text" cols="80" rows="5"><?php arc_isset_echo( $args, 'thank_you_text' ); ?></textarea>
	<p class="description">Displays as a confirmation when the user has completed the form.</p>
</fieldset>
