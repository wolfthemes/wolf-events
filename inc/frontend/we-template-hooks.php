<?php
/**
 * %NAME% Template Hooks
 *
 * Action/filter hooks used for %NAME% functions/templates
 *
 * @author %AUTHOR%
 * @category Core
 * @package %PACKAGENAME%/Templates
 * @version %VERSION%
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Body class
 *
 * @see  we_body_class()
 */
add_filter( 'body_class', 'we_body_class' );

/**
 * WP Header
 *
 * @see  we_generator_tag()
 */
add_action( 'get_the_generator_html', 'we_generator_tag', 10, 2 );
add_action( 'get_the_generator_xhtml', 'we_generator_tag', 10, 2 );

/**
 * Content Wrappers
 *
 * @see we_output_content_wrapper()
 * @see we_output_content_wrapper_end()
 */
add_action( 'we_before_main_content', 'we_output_content_wrapper', 10 );
add_action( 'we_after_main_content', 'we_output_content_wrapper_end', 10 );

/**
 * Template redirect
 *
 * @see  we_template_redirect()
 */
add_action( 'template_redirect', 'we_template_redirect', 40 );