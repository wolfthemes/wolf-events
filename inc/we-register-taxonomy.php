<?php
/**
 * Wolf Events register taxonomy
 *
 * Register event taxonomy
 *
 * @author WolfThemes
 * @category Core
 * @package WolfEvents/Admin
 * @version 1.2.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$labels = array(
	'name' => esc_html__( 'Artists', 'wolf-events' ),
	'singular_name' => esc_html__( 'Artist', 'wolf-events' ),
	'search_items' => esc_html__( 'Search Artists', 'wolf-events' ),
	'popular_items' => esc_html__( 'Popular Artists', 'wolf-events' ),
	'all_items' => esc_html__( 'All Artists', 'wolf-events' ),
	'parent_item' => esc_html__( 'Parent Artist', 'wolf-events' ),
	'parent_item_colon' => esc_html__( 'Parent Artist:', 'wolf-events' ),
	'edit_item' => esc_html__( 'Edit Artist', 'wolf-events' ),
	'update_item' => esc_html__( 'Update Artist', 'wolf-events' ),
	'add_new_item' => esc_html__( 'Add New Artist', 'wolf-events' ),
	'new_item_name' => esc_html__( 'New Artist', 'wolf-events' ),
	'separate_items_with_commas' => esc_html__( 'Separate artists with commas', 'wolf-events' ),
	'add_or_remove_items' => esc_html__( 'Add or remove artists', 'wolf-events' ),
	'choose_from_most_used' => esc_html__( 'Choose from the most used artists', 'wolf-events' ),
	'not_found' => esc_html__( 'No artists found', 'wolf-events' ),
	'menu_name' => esc_html__( 'Artists', 'wolf-events' ),
);

$args = array(
	'labels' => $labels,
	'hierarchical' => false,
	'public' => true,
	'show_ui' => true,
	'query_var' => true,
	'update_count_callback' => '_update_post_term_count',
	'rewrite' => array( 'slug' => 'event-artist', 'with_front' => false),
);

register_taxonomy( 'we_artist', array( 'event' ), $args );
