<p><strong>Entries:</strong> <?php echo $args['entry_count']; ?></p>

<p><a href="<?php echo $args['entry_link']; ?>"><?php _e('View entries', 'tidyforms'); ?></a></p>

<p><a href="<?php echo $args['export_url'] . '?tidy_export_form=' . $args['form_id']; ?>" class="button-secondary tidy-export-button"><?php _e('Export entries', 'tidyforms'); ?></a></p>
