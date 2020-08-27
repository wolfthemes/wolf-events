<?php
/**
 * Wolf Events register post type
 *
 * Register event post type
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
	'name' => esc_html__( 'Events', 'TEXTDOMAIN' ),
	'singular_name' => esc_html__( 'Event', 'TEXTDOMAIN' ),
	'add_new' => esc_html__( 'Add New', 'TEXTDOMAIN' ),
	'add_new_item' => esc_html__( 'Add New Event', 'TEXTDOMAIN' ),
	'all_items'  => esc_html__( 'All Events', 'TEXTDOMAIN' ),
	'edit_item' => esc_html__( 'Edit Event', 'TEXTDOMAIN' ),
	'new_item' => esc_html__( 'New Event', 'TEXTDOMAIN' ),
	'view_item' => esc_html__( 'View Event', 'TEXTDOMAIN' ),
	'search_items' => esc_html__( 'Search Events', 'TEXTDOMAIN' ),
	'not_found' => esc_html__( 'No event found', 'TEXTDOMAIN' ),
	'not_found_in_trash' => esc_html__( 'No event found in Trash', 'TEXTDOMAIN' ),
	'parent_item_colon' => '',
	'menu_name' => esc_html__( 'Events', 'TEXTDOMAIN' ),
);

$args = array(
	'labels' => $labels,
	'public' => true,
	'publicly_queryable' => true,
	'show_ui' => true,
	'show_in_menu' => true,
	'query_var' => false,
	'rewrite' => array( 'slug' => 'event' ),
	'capability_type' => 'post',
	'has_archive' => false,
	'hierarchical' => false,
	'menu_position' => 5,
	'taxonomies' => array(),
	'supports' => array( 'title', 'editor', 'thumbnail' ),
	'exclude_from_search' => false,
	'menu_icon' => 'dashicons-calendar-alt',
);

register_post_type( 'event', $args );
