<?php
/**
 * Template tags
 *
 * @package  WP Form Architect
 * @author   Adam Onishi <aonishi@wearearchitect.com>
 */

/**
 * wp_architect_form
 *
 * Template tag for Architect forms plugin
 *
 * @param  [int] $id ID of the form to load
 */
function wp_architect_form( $id, $atts = array() ) {
    $atts['id'] = $id;

    $renderer = Architect_Forms_Renderer::get_instance();
    $renderer->setup_action( $atts );
}

