<?php
/**
 * Wolf Events register metaboxes
 *
 * Register metaboxes for event posts
 *
 * @author WolfThemes
 * @category Core
 * @package WolfEvents/Admin
 * @version 1.0.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$metabox = array(

	'Event Details' => array(

		'title' => esc_html__( 'Event Details', '%TEXTDOMAIN%' ),
		'page' => array( 'event' ),
		'metafields' => array(

			array(
				'label'	=> esc_html__( 'Date', '%TEXTDOMAIN%' ),
				'id'	=> '_wolf_event_start_date',
				'type'	=> 'datepicker',
				'description' => esc_html__( 'Formatted like "dd-mm-yyyy"', '%TEXTDOMAIN%' )
			),

			array(
				'label'	=> esc_html__( 'End Date', '%TEXTDOMAIN%' ),
				'id'	=> '_wolf_event_end_date',
				'type'	=> 'datepicker',
			),

			array(
				'label'	=> esc_html__( 'Venue', '%TEXTDOMAIN%' ),
				'id'	=> '_wolf_event_venue',
				'type'	=> 'text',
			),

			array(
				'label'	=> esc_html__( 'Location', '%TEXTDOMAIN%' ),
				'id'	=> '_wolf_event_location',
				'type'	=> 'text',
				'description' => esc_html__( 'How you want to display the location name in the list (e.g: "Bruges, Belgium" or "	New Orleans, LA")', '%TEXTDOMAIN%' ),
			),

			array(
				'label'	=> esc_html__( 'City', '%TEXTDOMAIN%' ),
				'id'	=> '_wolf_event_city',
				'type'	=> 'text',
			),

			array(
				'label'	=> esc_html__( 'Country', '%TEXTDOMAIN%' ),
				'id'	=> '_wolf_event_country',
				'type'	=> 'text',
			),

			array(
				'label'	=> esc_html__( 'Country - short ( e.g : GER for Germany )', '%TEXTDOMAIN%' ),
				'id'	=> '_wolf_event_country_short',
				'type'	=> 'text',
			),

			array(
				'label'	=> esc_html__( 'State', '%TEXTDOMAIN%' ),
				'id'	=> '_wolf_event_state',
				'type'	=> 'text',
			),

			array(
				'label'	=> esc_html__( 'Time', '%TEXTDOMAIN%' ),
				'id'	=> '_wolf_event_time',
				'type'	=> 'text',
				'description'	=> esc_html__( 'e.g: 20:30 or 8:30PM', '%TEXTDOMAIN%' ),
			),

			array(
				'label'	=> esc_html__( 'Postal address', '%TEXTDOMAIN%' ),
				'id'	=> '_wolf_event_address',
				'type'	=> 'text',
			),

			array(
				'label'	=> esc_html__( 'Zip', '%TEXTDOMAIN%' ),
				'id'	=> '_wolf_event_zip',
				'type'	=> 'text',
			),


			array(
				'label'	=> esc_html__( 'Phone', '%TEXTDOMAIN%' ),
				'id'	=> '_wolf_event_phone',
				'type'	=> 'text',
			),

			array(
				'label'	=> esc_html__( 'Contact Email', '%TEXTDOMAIN%' ),
				'id'	=> '_wolf_event_email',
				'type'	=> 'text',
			),
			array(
				'label'	=> esc_html__( 'Contact Website', '%TEXTDOMAIN%' ),
				'id'	=> '_wolf_event_website',
				'type'	=> 'url',
			),

			array(
				'label'	=> esc_html__( 'Google map embed code', '%TEXTDOMAIN%' ),
				'desc'   => sprintf( __( '<a class="wolf-help-img" href="%s" target="_blank">Where to find it?</a>', '%TEXTDOMAIN%' ), WE_URI . '/assets/img/admin/google-map.jpg' ),
				'id'	=> '_wolf_event_map',
				'type'	=> 'textarea_html',
			),

			array(
				'label'	=> esc_html__( 'Facebook event page', '%TEXTDOMAIN%' ),
				'id'	=> '_wolf_event_fb',
				'type'	=> 'url',
			),

			array(
				'label'	=> esc_html__( 'Bandsinwown event page', '%TEXTDOMAIN%' ),
				'id'	=> '_wolf_event_bit',
				'type'	=> 'url',
			),

			array(
				'label'	=> esc_html__( 'Buy Ticket link', '%TEXTDOMAIN%' ),
				'id'	=> '_wolf_event_ticket',
				'desc'   => 'http://www.example.com',
				'type'	=> 'url',
			),

			array(
				'label'	=> esc_html__( 'Price (e.g : $15)', '%TEXTDOMAIN%' ),
				'id'	=> '_wolf_event_price',
				'type'	=> 'text',
			),

			array(
				'label'	=> esc_html__( 'Currency (e.g : USD)', '%TEXTDOMAIN%' ),
				'id'	=> '_wolf_event_currency',
				'type'	=> 'text',
			),

			array(
				'label'	=> esc_html__( 'Free', '%TEXTDOMAIN%' ),
				'id'	=> '_wolf_event_free',
				'type'	=> 'checkbox',
			),

			array(
				'label'	=> esc_html__( 'Sold Out', '%TEXTDOMAIN%' ),
				'id'	=> '_wolf_event_soldout',
				'type'	=> 'checkbox',
			),

			array(
				'label'	=> esc_html__( 'Cancelled', '%TEXTDOMAIN%' ),
				'id'	=> '_wolf_event_cancel',
				'type'	=> 'checkbox',
			),
		)
	),
);

new WE_Admin_Metabox( $metabox );
