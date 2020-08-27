<?php
/**
 * Wolf Events Frontend class.
 *
 * @class WE_Admin
 * @author WolfThemes
 * @category Frontend
 * @package WolfEvents/Frontend
 * @version 1.2.2
 */

defined( 'ABSPATH' ) || exit;

/**
 * WE_Admin class.
 */
class WE_Frontend {

	/**
	 * @var object
	 */
	private $wpdb;

	/**
	 * WE_Frontend constructor
	 */
	public function __construct() {
		global $wpdb;
		$this->wpdb = &$wpdb;
	}

	/**
	 * Loop function
	 *
	 * Display the event posts
	 *
	 * @param array $args
	 */
	public function events( $args = array() ) {
		echo 'The event posts';
	}

	/**
	 * Loop function
	 *
	 * Display the events list
	 *
	 * @param array $args
	 */
	public function event_list( $args = array() ) {

		$args = wp_parse_args( $args, array(
			'count' => -1,
			//'past' => we_get_option( 'past_shows' ),
			'timeline' => 'future', // future or past
			'link' => we_get_option( 'single_page' ),
			'artist' => false,
			'widget' => false,
		) );

		$args = $this->sanitize_args( $args );

		$count = $args['count'];
		$timeline = $args['timeline'];
		$link = $args['link'];
		$artist = $args['artist'];
		$widget = $args['widget'];

		echo '<div class="wolf-events">';

		if ( 'past' === $timeline ) {
			$events = $this->past_events_query( $count, $artist );
		} else {
			$events = $this->future_events_query( $count, $artist );
		}

		if ( $events->have_posts() ) {

			$container_class = 'wolf-events-table wolf-upcoming-events-table';

			if ( 'past' === $timeline ) {
				$container_class .= ' wolf-past-events-table';
			} else {
				$container_class .= ' wolf-upcoming-events-table';
			}

			if ( $widget ) {
				$container_class .= ' wolf-events-widget-table';
			}

			// if ( $display_past_events ) {
			// 	echo '<h2 class="we-list-title">';
			// 	echo apply_filters( 'we_upcoming_events_text', we_get_option( 'upcoming_events_text', esc_html__( 'Upcoming events', 'wolf-events' ) ) );
			// 	echo '</h2>';
			// }

			echo '<div class="' . esc_attr( $container_class ) . '">';

			while ( $events->have_posts() ) : $events->the_post();

				$meta = $this->get_event_meta( get_the_ID() );
				$cancelled = $meta['cancelled'];

				if ( 'past' === $timeline ) {
					$meta['action'] = ''; // no buy ticket button
				}

				// don't display event in widget if cancelled
				if ( $widget && $cancelled ) {
					continue;
				}

				do_action( 'we_before_event_list', $args, $meta );

				extract( $meta );

				include( WE_DIR . '/templates/event-list-item.php' );

				do_action( 'we_after_event_list', $args, $meta );

			endwhile;

			echo '</div><!-- .wolf-upcoming-events -->';

		} else { // no events
			?><p><?php echo we_get_option( 'no_shows_text', esc_html__( 'No upcoming shows scheduled', 'wolf-events' ) ); ?></p><?php
		}
		wp_reset_postdata();

		echo '</div><!-- .wolf-events -->';

		// if ( $display_past_events && ! $widget ) {

		// 	$past_events = $this->past_events_query( $count, $artist );

		// 	if ( $past_events->have_posts() ) {

		// 		$container_class = 'wolf-events-table wolf-past-events-table';

		// 		echo '<h2 class="we-list-title">';
		// 		echo apply_filters( 'we_past_events_text', we_get_option( 'past_events_text', esc_html__( 'Past events', 'wolf-events' ) ) );
		// 		echo '</h2>';

		// 		echo '<div class="' . esc_attr( $container_class ) . '">';

		// 		while ( $past_events->have_posts() ) : $past_events->the_post();

		// 			$meta = $this->get_event_meta( get_the_ID() );

		// 			do_action( 'we_before_past_event_list', $args, $meta );

		// 			include( WE_DIR . '/templates/event-list-item.php' );

		// 			do_action( 'we_after_past_event_list', $args, $meta );

		// 		endwhile;

		// 		echo '</div><!-- .wolf-past-events -->';
		// 	}
		// } // end if display past events
	}

	/**
	 * Get all meta values from meta data and returns a nice formatted array
	 *
	 * @param int $post_id
	 * @return array $meta
	 */
	public function get_event_meta( $post_id ) {

		$meta = $this->set_default_event_meta();

		$meta['name'] = get_the_title();
		$meta['permalink'] = get_permalink();
		$meta['description'] = we_sample( get_the_content() );
		$meta['thumbnail_url'] = ( has_post_thumbnail() ) ? we_get_post_thumbnail_url( 'large' ) : '';

		// set class array
		$classes = array( 'we-list-event', 'we-table-row' );

		$meta['facebook_url'] = get_post_meta( $post_id, '_wolf_event_fb', true );
		$meta['bandsintown_url'] = get_post_meta( $post_id, '_wolf_event_bit', true );

		// time
		$time = get_post_meta( $post_id, '_wolf_event_time', true );
		$meta['time'] = ( $this->format_time( $time ) ) ? $time : '00:00';

		// start date
		$start_date = get_post_meta( $post_id, '_wolf_event_start_date', true );
		$meta['start_date'] = ( $start_date ) ? $start_date : '2020-01-01';
		//$meta['raw_start_date'] = $start_date . 'T' . $this->format_time( $time );
		$meta['raw_start_date'] = date_format( date_create( $start_date . 'T' . $this->format_time( $time ) ), DATE_ISO8601 );
		$meta['formatted_start_date'] = $this->format_date( $start_date, $this->format_time( $time ) );

		// end date
		$end_date = get_post_meta( $post_id, '_wolf_event_end_date', true );
		$meta['end_date'] = ( $end_date ) ? $end_date : '';
		//$meta['raw_end_date'] = ( $end_date ) ? $end_date . 'T' . $this->format_time( $time ) : '';
		$meta['raw_end_date'] = ( $end_date ) ? date_format( date_create( $end_date . 'T' . $this->format_time( $time ) ), DATE_ISO8601 ) : '';
		$meta['formatted_end_date'] = ( $end_date ) ? $this->format_date( $end_date, $this->format_time( $time ) ) : '';

		// place
		$meta['display_location'] = get_post_meta( $post_id, '_wolf_event_location', true );
		$meta['venue'] = get_post_meta( $post_id, '_wolf_event_venue', true );
		$meta['city'] = get_post_meta( $post_id, '_wolf_event_city', true );
		$meta['address'] = get_post_meta( $post_id, '_wolf_event_address', true );
		$meta['zipcode'] = get_post_meta( $post_id, '_wolf_event_zip', true );
		$meta['phone'] = get_post_meta( $post_id, '_wolf_event_phone', true );
		$meta['email'] = get_post_meta( $post_id, '_wolf_event_email', true );
		$meta['website'] = get_post_meta( $post_id, '_wolf_event_website', true );
		$meta['country'] = get_post_meta( $post_id, '_wolf_event_country_short', true );
		$meta['state'] = get_post_meta( $post_id, '_wolf_event_state', true );

		$artist = get_the_term_list( get_the_ID(), 'we_artist', '', ', ', '' );
		$meta['artist'] = ( $artist ) ? $artist : '';

		// action
		$action = '';
		$ticket_url = get_post_meta( $post_id, '_wolf_event_ticket', true );
		$cancelled = get_post_meta( $post_id, '_wolf_event_cancel', true );
		$soldout = get_post_meta( $post_id, '_wolf_event_soldout', true );
		$free = get_post_meta( $post_id, '_wolf_event_free', true );
		$meta['ticket_url'] = get_post_meta( $post_id, '_wolf_event_ticket', true );
		$meta['price'] = get_post_meta( $post_id, '_wolf_event_price', true );
		$meta['currency'] = get_post_meta( $post_id, '_wolf_event_currency', true );
		//$meta['ticket_url'] = get_post_meta( $post_id, '_wolf_event_ticket', true );
		$meta['cancelled'] = get_post_meta( $post_id, '_wolf_event_cancel', true );
		$meta['soldout'] = get_post_meta( $post_id, '_wolf_event_soldout', true );
		$meta['free'] = get_post_meta( $post_id, '_wolf_event_free', true );
		$meta['map'] = get_post_meta( $post_id, '_wolf_event_map', true );

		// Buy ticket links
		if ( ! $cancelled && ! $soldout && ! $free && $ticket_url ) {
			$action_text = apply_filters( 'we_ticket_link_text', we_get_option( 'ticket_text', esc_html__( 'Tickets', 'wolf-events' ) ) );
			$ticket_url_class = apply_filters( 'we_ticket_link_class', 'we-ticket-link' );
			$target = apply_filters( 'we_ticket_link_target', '_self' );
			$action = '<a target="' . esc_attr( $target ) . '" class="' . esc_attr( $ticket_url_class ) . '" href="' . esc_url( $ticket_url ) . '">' . sanitize_text_field( $action_text ) . '</a>';
		}

		if ( $free && ! $cancelled && ! $soldout ) {
			$action_text = apply_filters( 'we_free_text', esc_html__( 'Free', 'wolf-events' ) );
			$action  = '<span class="we-label we-label-free">' . sanitize_text_field( $action_text ) . '</span>';
		}

		if ( $cancelled ) {
			$link = false;
			$classes[] = 'we-cancelled';
			$action_text = apply_filters( 'we_cancelled_text', esc_html__( 'Cancelled', 'wolf-events' ) );
			$action  = '<span class="we-label we-label-cancelled">' . sanitize_text_field( $action_text ) . '</span>';
		}

		if ( $soldout ) {
			$classes[] = 'we-soldout';
			$action_text = apply_filters( 'we_soldout_text', esc_html__( 'Sold out!', 'wolf-events' ) );
			$action  = '<span class="we-label we-label-soldout">' . sanitize_text_field( $action_text ) . '</span>';
		}

		$meta['action'] = $action;

		$meta['classes'] = implode( ' ', array_filter( $classes ) );

		return $this->sanitize_meta_values( $meta );
	}

	/**
	 * Format time (AM PM to 24hrs format if needed)
	 */
	public function format_time( $time ) {

		if ( ! $time ) {
			return;
		}

		$time = trim( strtoupper( preg_replace( '/\s+/', '', $time ) ) );
		$time = preg_replace( '/[^AM|PM0-9:]+/', '', $time );

		if ( preg_match( '[AM|PM]', $time ) ) {

			return date( 'H:i', strtotime( $time ) );

		} elseif ( $time ) {
			return $time;
		}
	}

	/**
	 * Sanitize all meta values
	 *
	 * @param array $meta
	 * @return array $meta
	 */
	public function sanitize_meta_values( $meta = array() ) {

		$meta['name'] = sanitize_text_field( $meta['name'] );
		$meta['permalink'] = esc_url( $meta['permalink'] );
		$meta['description'] = sanitize_text_field( $meta['description'] );
		$meta['thumbnail_url'] = esc_url( $meta['thumbnail_url'] );
		$meta['facebook_url'] = esc_url( $meta['facebook_url'] );
		$meta['bandsintown_url'] = esc_url( $meta['bandsintown_url'] );
		$meta['classes'] = sanitize_text_field( $meta['classes'] );
		$meta['start_date'] = sanitize_text_field( $meta['start_date'] );
		$meta['end_date'] = sanitize_text_field( $meta['end_date'] );
		$meta['time'] = sanitize_text_field( $meta['time'] );
		$meta['raw_start_date'] = sanitize_text_field( $meta['raw_start_date'] );
		$meta['formatted_start_date'] = we_sanitize_date( $meta['formatted_start_date'] );
		$meta['raw_end_date'] = sanitize_text_field( $meta['raw_end_date'] );
		$meta['formatted_end_date'] = we_sanitize_date( $meta['formatted_end_date'] );
		$meta['display_location'] = sanitize_text_field( $meta['display_location'] );
		$meta['venue'] = sanitize_text_field( $meta['venue'] );
		$meta['city'] = sanitize_text_field( $meta['city'] );
		$meta['address'] = sanitize_text_field( $meta['address'] );
		$meta['zipcode'] = sanitize_text_field( $meta['zipcode'] );
		$meta['phone'] = sanitize_text_field( $meta['phone'] );
		$meta['email'] = sanitize_email( $meta['email'] );
		$meta['website'] = esc_url( $meta['website'] );
		$meta['country'] = sanitize_text_field( $meta['country'] );
		$meta['artist'] = wp_kses_post( $meta['artist'] );
		$meta['country_short'] = esc_attr( $meta['country_short'] );
		$meta['state'] = sanitize_text_field( $meta['state'] );
		$meta['ticket_url'] = esc_url( $meta['ticket_url'] );
		$meta['price'] = esc_attr( $meta['price'] );
		$meta['formatted_price'] = preg_replace( '/[^0-9-.,]/', '', $meta['price'] );
		$meta['currency'] = esc_attr( $meta['currency'] );
		$meta['cancelled'] = boolval( $meta['cancelled'] );
		$meta['soldout'] = boolval( $meta['soldout'] );
		$meta['free'] = boolval( $meta['free'] );
		$meta['action'] = we_sanitize_action( $meta['action'] );
		$meta['map'] = we_sanitize_iframe( $meta['map'] );

		return $meta;
	}

	/**
	 * Set a default meta array
	 *
	 * We will store all the data related to the post here
	 *
	 * @param array $meta
	 * @return array $meta
	 */
	public function set_default_event_meta() {
		return array(
			'classes' => '',
			'name' => '',
			'permalink' => '',
			'description' => '',
			'thumbnail_url' => '',
			'facebook_url' => '',
			'bandsintown_url' => '',
			'start_date' => '',
			'end_date' => '',
			'time' => '',
			'raw_start_date' => '',
			'raw_end_date' => '',
			'formatted_start_date' => '',
			'formatted_end_date' => '',
			'display_location' => '',
			'venue' => '',
			'city' => '',
			'address' => '',
			'zipcode' => '',
			'phone' => '',
			'email' => '',
			'artist' => '',
			'website' => '',
			'country' => '',
			'country_short' => '',
			'state' => '',
			'ticket_url' => '',
			'price' => '',
			'formatted_price' => '',
			'currency' => '',
			'cancelled' => '',
			'soldout' => '',
			'free' => '',
			'action' => '',
			'map' => '',
		);
	}

	/**
	 * Returns show date
	 *
	 * @param string $date, bool $custom
	 * @return string
	 */
	public function format_date( $date = null, $time = '00:00', $date_format = 'custom' ) {

		if ( ! $date ) {
			return;
		}

		$output = '';

		$date_format = apply_filters( 'we_date_format', $date_format );

		if ( 'custom' === $date_format ) {

			list( $day, $monthnbr, $year ) = explode( '-', $date );
			$search = array( '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12' );
			$replace = array( esc_html__( 'Jan', 'wolf-events' ), esc_html__( 'Feb', 'wolf-events' ), esc_html__( 'Mar', 'wolf-events' ), esc_html__( 'Apr', 'wolf-events' ), esc_html__( 'May', 'wolf-events' ), esc_html__( 'Jun', 'wolf-events' ), esc_html__( 'Jul', 'wolf-events' ), esc_html__( 'Aug', 'wolf-events' ), esc_html__( 'Sep', 'wolf-events' ), esc_html__( 'Oct', 'wolf-events' ), esc_html__( 'Nov', 'wolf-events' ), esc_html__( 'Dec', 'wolf-events' ) );
			$month = str_replace( $search, $replace, $monthnbr );

			$custom_date_class = apply_filters( 'we_custom_date_class', 'wvc-bigtext' );

			$output = '<span class="we-date-format-custom ' . esc_attr( $custom_date_class ) . '"><span class="we-month">' . $month . '</span><span class="we-day">' . $day . '</span></span>';

		} elseif ( 'default' === $date_format ) {

			$display = date_i18n( get_option( 'date_format' ), strtotime( $date ) );

			$output = '<span class="we-date-format-default">' . $display .  '</span>';

		}

		return apply_filters( 'we_formatted_date', $output, $date, $time );
	}

	/**
	 * Sanitize args and set default value from options if needed
	 *
	 * @param array $args
	 * @return array $args
	 */
	public function sanitize_args( $args ) {

		$args['count'] = intval( $args['count'] );
		$args['timeline'] = esc_attr( $args['timeline'] );
		$args['link'] = boolval( $args['link'] );
		$args['widget'] = boolval( $args['widget'] );
		$args['artist'] = esc_attr( $args['artist'] );

		return $args;
	}

	/**
	 * Custom SQL query for future events
	 *
	 * @param int $count
	 * @return object
	 */
	public function future_events_query( $count, $artist ) {

		add_filter( 'posts_orderby', 'we_order_by', 10, 1 );
		add_filter( 'posts_where', 'we_future_where', 10,  1 );

		$today = date( 'm-d-Y' );

		$args = array(
			'post_type' => 'event',
			'meta_key' => '_wolf_event_start_date',
			'orderby' => 'meta_value',
			'order' => 'ASC',
			'posts_per_page' => $count,
		);

		if ( $artist ) {
			$args['we_artist'] = $artist;
		}

		$query = new WP_Query( $args );

		remove_filter( 'posts_orderby', 'we_order_by' );
		remove_filter( 'posts_where', 'we_future_where' );

		return $query;
	}

	/**
	 * Custom SQL query for past events
	 *
	 * @param int $count
	 * @return object
	 */
	public function past_events_query( $count, $artist ) {
		add_filter( 'posts_orderby', 'we_order_by', 10, 1 );
		add_filter( 'posts_where', 'we_past_where', 10,  1 );

		$today = date( 'm-d-Y' );

		$args  = array(
			'post_type' => 'event',
			'meta_key' => '_wolf_event_start_date',
			'orderby' => 'meta_value',
			'order' => 'DESC',
			'posts_per_page' => $count,
		);

		if ( $artist ) {
			$args['we_artist'] = $artist;
		}

		$query = new WP_Query( $args );

		remove_filter( 'posts_orderby', 'we_order_by' );
		remove_filter( 'posts_where', 'we_past_where' );

		return $query;
	}
}
