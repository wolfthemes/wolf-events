<?php
/**
 * Wolf Events admin functions
 *
 * General  functions available on admin.
 *
 * @author WolfThemes
 * @category Core
 * @package WolfEvents/Admin
 * @version 1.2.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reorder post by custom date
 */
function we_set_custom_post_types_admin_order( $wp_query ) {

	global $wp_query;

	if ( isset( $wp_query->query['post_type'] ) ) {

		$post_type = $wp_query->query['post_type'];

		if ( 'event' === $post_type ) {

			add_filter( 'posts_orderby', 'we_order_by', 10, 1 );

			// 'orderby' value can be any column name
			$wp_query->set( 'meta_key', '_wolf_event_start_date' );
			$wp_query->set( 'orderby', 'meta_value' );
			$wp_query->set( 'order', 'DESC' );
		}
	}
}
add_filter( 'pre_get_posts', 'we_set_custom_post_types_admin_order' );

/**
 * Display archive page state
 *
 * @param array $states
 * @param object $post
 * @return array $states
 */
function we_custom_post_states( $states, $post ) {

	if ( 'page' == get_post_type( $post->ID ) && absint( $post->ID ) === wolf_events_get_page_id() ) {

		$states[] = esc_html__( 'Events Page' );
	}

	return $states;
}
add_filter( 'display_post_states', 'we_custom_post_states', 10, 2 );
