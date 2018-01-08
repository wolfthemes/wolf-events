<?php
/**
 * %NAME% register taxonomy
 *
 * Register event taxonomy
 *
 * @author %AUTHOR%
 * @category Core
 * @package %PACKAGENAME%/Admin
 * @version %VERSION%
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$labels = array(
	'name' => esc_html__( 'Artists', '%TEXTDOMAIN%' ),
	'singular_name' => esc_html__( 'Artist', '%TEXTDOMAIN%' ),
	'search_items' => esc_html__( 'Search Artists', '%TEXTDOMAIN%' ),
	'popular_items' => esc_html__( 'Popular Artists', '%TEXTDOMAIN%' ),
	'all_items' => esc_html__( 'All Artists', '%TEXTDOMAIN%' ),
	'parent_item' => esc_html__( 'Parent Artist', '%TEXTDOMAIN%' ),
	'parent_item_colon' => esc_html__( 'Parent Artist:', '%TEXTDOMAIN%' ),
	'edit_item' => esc_html__( 'Edit Artist', '%TEXTDOMAIN%' ),
	'update_item' => esc_html__( 'Update Artist', '%TEXTDOMAIN%' ),
	'add_new_item' => esc_html__( 'Add New Artist', '%TEXTDOMAIN%' ),
	'new_item_name' => esc_html__( 'New Artist', '%TEXTDOMAIN%' ),
	'separate_items_with_commas' => esc_html__( 'Separate artists with commas', '%TEXTDOMAIN%' ),
	'add_or_remove_items' => esc_html__( 'Add or remove artists', '%TEXTDOMAIN%' ),
	'choose_from_most_used' => esc_html__( 'Choose from the most used artists', '%TEXTDOMAIN%' ),
	'menu_name' => esc_html__( 'Artists', '%TEXTDOMAIN%' ),
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