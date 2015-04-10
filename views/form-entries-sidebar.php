<p><strong>Entries:</strong> <?php echo $args['entry_count']; ?></p>

<p><a href="<?php echo $args['entry_link']; ?>"><?php _e('View entries', 'arcforms'); ?></a></p>

<p><a href="<?php echo $args['export_url'] . '?arc_export_form=' . $args['form_id']; ?>" class="button-secondary arc-export-button"><?php _e('Export entries', 'arcforms'); ?></a></p>
