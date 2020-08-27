<?php
/**
 * Wolf Events register metaboxes
 *
 * Register metaboxes for event posts
 *
 * @author WolfThemes
 * @category Core
 * @package WolfEvents/Admin
 * @version 1.2.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$metabox = array(

	'Event Details' => array(

		'title' => esc_html__( 'Event Details', 'wolf-events' ),
		'page' => array( 'event' ),
		'metafields' => array(

			array(
				'label'	=> esc_html__( 'Date', 'wolf-events' ),
				'id'	=> '_wolf_event_start_date',
				'type'	=> 'datepicker',
				'description' => esc_html__( 'Formatted like "dd-mm-yyyy"', 'wolf-events' )
			),

			array(
				'label'	=> esc_html__( 'End Date', 'wolf-events' ),
				'id'	=> '_wolf_event_end_date',
				'type'	=> 'datepicker',
			),

			array(
				'label'	=> esc_html__( 'Venue', 'wolf-events' ),
				'id'	=> '_wolf_event_venue',
				'type'	=> 'text',
			),

			array(
				'label'	=> esc_html__( 'Location', 'wolf-events' ),
				'id'	=> '_wolf_event_location',
				'type'	=> 'text',
				'description' => esc_html__( 'How you want to display the location name in the list (e.g: "Bruges, Belgium" or "	New Orleans, LA")', 'wolf-events' ),
			),

			array(
				'label'	=> esc_html__( 'City', 'wolf-events' ),
				'id'	=> '_wolf_event_city',
				'type'	=> 'text',
			),

			array(
				'label'	=> esc_html__( 'Country', 'wolf-events' ),
				'id'	=> '_wolf_event_country',
				'type'	=> 'text',
			),

			array(
				'label'	=> esc_html__( 'Country - short ( e.g : GER for Germany )', 'wolf-events' ),
				'id'	=> '_wolf_event_country_short',
				'type'	=> 'text',
			),

			array(
				'label'	=> esc_html__( 'State', 'wolf-events' ),
				'id'	=> '_wolf_event_state',
				'type'	=> 'text',
			),

			array(
				'label'	=> esc_html__( 'Time', 'wolf-events' ),
				'id'	=> '_wolf_event_time',
				'type'	=> 'text',
				'description'	=> esc_html__( 'e.g: 20:30 or 8:30PM', 'wolf-events' ),
			),

			array(
				'label'	=> esc_html__( 'Postal address', 'wolf-events' ),
				'id'	=> '_wolf_event_address',
				'type'	=> 'text',
			),

			array(
				'label'	=> esc_html__( 'Zip', 'wolf-events' ),
				'id'	=> '_wolf_event_zip',
				'type'	=> 'text',
			),


			array(
				'label'	=> esc_html__( 'Phone', 'wolf-events' ),
				'id'	=> '_wolf_event_phone',
				'type'	=> 'text',
			),

			array(
				'label'	=> esc_html__( 'Contact Email', 'wolf-events' ),
				'id'	=> '_wolf_event_email',
				'type'	=> 'text',
			),
			array(
				'label'	=> esc_html__( 'Contact Website', 'wolf-events' ),
				'id'	=> '_wolf_event_website',
				'type'	=> 'url',
			),

			array(
				'label'	=> esc_html__( 'Google map embed code', 'wolf-events' ),
				'desc'   => sprintf( __( '<a class="wolf-help-img" href="%s" target="_blank">Where to find it?</a>', 'wolf-events' ), WE_URI . '/assets/img/admin/google-map.jpg' ),
				'id'	=> '_wolf_event_map',
				'type'	=> 'textarea_html',
			),

			array(
				'label'	=> esc_html__( 'Facebook event page', 'wolf-events' ),
				'id'	=> '_wolf_event_fb',
				'type'	=> 'url',
			),

			array(
				'label'	=> esc_html__( 'Bandsinwown event page', 'wolf-events' ),
				'id'	=> '_wolf_event_bit',
				'type'	=> 'url',
			),

			array(
				'label'	=> esc_html__( 'Buy Ticket link', 'wolf-events' ),
				'id'	=> '_wolf_event_ticket',
				'desc'   => 'http://www.example.com',
				'type'	=> 'url',
			),

			array(
				'label'	=> esc_html__( 'Price (e.g : $15)', 'wolf-events' ),
				'id'	=> '_wolf_event_price',
				'type'	=> 'text',
			),

			array(
				'label'	=> esc_html__( 'Currency (e.g : USD)', 'wolf-events' ),
				'id'	=> '_wolf_event_currency',
				'type'	=> 'text',
			),

			array(
				'label'	=> esc_html__( 'Free', 'wolf-events' ),
				'id'	=> '_wolf_event_free',
				'type'	=> 'checkbox',
			),

			array(
				'label'	=> esc_html__( 'Sold Out', 'wolf-events' ),
				'id'	=> '_wolf_event_soldout',
				'type'	=> 'checkbox',
			),

			array(
				'label'	=> esc_html__( 'Cancelled', 'wolf-events' ),
				'id'	=> '_wolf_event_cancel',
				'type'	=> 'checkbox',
			),
		)
	),
);

new WE_Admin_Metabox( $metabox );
