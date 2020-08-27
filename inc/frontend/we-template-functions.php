<?php
/**
 * Wolf Events template functions
 *
 * Functions for the templating system.
 *
 * @author WolfThemes
 * @category Core
 * @package WolfEvents/Functions
 * @version 1.2.2
 */

defined( 'ABSPATH' ) || exit;

/**
 * Output generator tag to aid debugging.
 */
function we_generator_tag( $gen, $type ) {
	switch ( $type ) {
		case 'html':
			$gen .= "\n" . '<meta name="generator" content="WolfEvents ' . esc_attr( WE_VERSION ) . '">';
			break;
		case 'xhtml':
			$gen .= "\n" . '<meta name="generator" content="WolfEvents ' . esc_attr( WE_VERSION ) . '" />';
			break;
	}
	return $gen;
}

/**
 * Add body classes
 *
 * @param  array $classes
 * @return array
 */
function we_body_class( $classes ) {

	$classes = ( array ) $classes;

	$classes[] = 'wolf-events';
	$classes[] = sanitize_title_with_dashes( get_template() ); // theme slug

	if ( is_singular( 'event' ) ) {
		$classes[] = 'single-event';
	}

	return array_unique( $classes );
}

/** Global ****************************************************************/

if ( ! function_exists( 'we_output_content_wrapper' ) ) {

	/**
	 * Output the start of the page wrapper.
	 *
	 */
	function we_output_content_wrapper() {
		we_get_template( 'global/wrapper-start.php' );
	}
}

if ( ! function_exists( 'we_output_content_wrapper_end' ) ) {

	/**
	 * Output the end of the page wrapper.
	 *
	 */
	function we_output_content_wrapper_end() {
		we_get_template( 'global/wrapper-end.php' );
	}
}
