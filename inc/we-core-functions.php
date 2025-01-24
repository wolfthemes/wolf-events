<?php
/**
 * Wolf Events core functions
 *
 * General core functions available on admin and frontend
 *
 * @author WolfThemes
 * @category Core
 * @package WolfEvents/Core
 * @version 1.2.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Event meta
 *
 * @param string $date mysql formatted date
 * @return string $date
 */
function we_nice_date( $date ) {
	return date_i18n( get_option( 'date_format' ), strtotime( $date ) );
}

/**
 * wolf_events page ID
 *
 * retrieve page id - used for the main albums page
 *
 *
 * @access public
 * @return int
 */
function wolf_events_get_page_id() {

	$page_id = -1;

	if ( -1 != get_option( '_wolf_events_page_id' ) && get_option( '_wolf_events_page_id' ) ) {

		$page_id = get_option( '_wolf_events_page_id' );

	}

	if ( -1 != $page_id ) {
		$page_id = apply_filters( 'wpml_object_id', absint( $page_id ), 'page', true ); // filter for WPML
	}

	return $page_id;
}

/**
 * Returns the URL of the events page
 */
function wolf_get_events_url() {

	$page_id = wolf_events_get_page_id();

	if ( -1 != $page_id ) {
		return get_permalink( $page_id );
	}
}

/**
 * Returns event date
 *
 * @param string $date, bool $custom
 * @return string
 */
function we_get_event_date( $date = null, $custom = null ) {

	if ( ! $date ) return;

	list( $month, $day, $year ) = explode( '-', $date );
	$sql_date = $year . '-' . $month . '-' . $day . ' 00:00:00';

	$format = $custom ? we_get_option( 'date_format' ) : get_option( 'date_format' );

	if ( $format == '\c\u\s\t\o\m' ) {
		$format = we_get_option( 'date_format_custom' );
	}

	if ( $date && $format != 'we_date' ) {

		return mysql2date( $format, $sql_date );


	} elseif ( $date ) {

		return we_custom_date_format( $date );
	}
}

/**
 * Get option
 *
 * @param string $value
 * @return string
 */
function we_get_option( $value = null, $default = null ) {

	global $options;

	$wolf_events_settings = get_option( 'wolf_events_settings' );

	if ( isset( $wolf_events_settings[ $value ] ) && '' != $wolf_events_settings[ $value ] ) {

		return $wolf_events_settings[ $value ];

	} elseif ( $default ) {

		return $default;
	}
}

/**
 * Check if an event date is past
 *
 * @param string $date
 * @return bool
 */
function we_is_past_show( $date = null ) {

	if ( $date ) {
		list( $day, $month, $year ) = explode( '-', $date );
		$sql_date = $year . '-' . $month . '-' . $day . ' 00:00:00';

		$interval = ( strtotime( date( 'Y-m-d H:i:s' ) ) - strtotime( $sql_date ) );

		return $interval > 0;
	}
}

/**
 * Get template part (for templates like the release-loop).
 *
 * @access public
 * @param mixed $slug
 * @param string $name (default: '')
 * @return void
 */
function we_get_template_part( $slug, $name = '' ) {

	$wolf_events = WE();
	$template = '';

	// Look in yourtheme/slug-name.php and yourtheme/wolf-albums/slug-name.php
	if ( $name )
		$template = locate_template( array( "{$slug}-{$name}.php", "{$wolf_events->template_url}{$slug}-{$name}.php" ) );

	// Get default slug-name.php
	if ( ! $template && $name && file_exists( $wolf_events->plugin_path() . "/templates/{$slug}-{$name}.php" ) )
		$template = $wolf_events->plugin_path() . "/templates/{$slug}-{$name}.php";

	// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/wolf-albums/slug.php
	if ( ! $template )
		$template = locate_template( array( "{$slug}.php", "{$wolf_events->template_url}{$slug}.php" ) );

	if ( $template )
		load_template( $template, false );
}

/**
 * Get other templates (e.g. ticket attributes) passing attributes and including the file.
 *
 * @access public
 * @param mixed $template_name
 * @param array $args (default: array())
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 * @return void
 */
function we_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {

	if ( $args && is_array($args) )
		extract( $args );

	$located = we_locate_template( $template_name, $template_path, $default_path );

	do_action( 'we_before_template_part', $template_name, $template_path, $located, $args );

	include( $located );

	do_action( 'we_after_template_part', $template_name, $template_path, $located, $args );
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 * yourtheme/$template_path/$template_name
 * yourtheme/$template_name
 * $default_path/$template_name
 *
 * @access public
 * @param mixed $template_name
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 * @return string
 */
function we_locate_template( $template_name, $template_path = '', $default_path = '' ) {

	if ( ! $template_path ) $template_path = WE()->template_url;
	if ( ! $default_path ) $default_path = WE()->plugin_path() . '/templates/';

	// Look within passed path within the theme - this is priority
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name
		)
	);

	// Get default template
	if ( ! $template )
		$template = $default_path . $template_name;

	// Return what we found
	return apply_filters( 'we_locate_template', $template, $template_name, $template_path );
}

/**
 * Create a formatted sample of any text
 *
 * Remove HTML and shortcode, sanitize and shorten a string
 *
 * @param string $text
 * @param int $num_words
 * @param string $more
 * @return string
 */
function we_sample( $text, $num_words  = 55, $more = '...' ) {
	return wp_trim_words( strip_shortcodes( $text ), $num_words, $more );
}
/**
 * Remove all double spaces
 *
 * This function is mainly used to clean up inline CSS
 *
 * @param string $css
 * @return string
 */
function we_clean_spaces( $string, $hard = true ) {

	return preg_replace( '/\s+/', ' ', $string );
}

/**
 * "order by" SQL filter
 *
 * @param string $orderby
 * @return string
 */
function we_order_by_bak( $orderby ) {
	global $wpdb;
	$meta = $wpdb->prefix . 'postmeta';
	$new_orderby = str_replace( "$meta.meta_value", "STR_TO_DATE( $meta.meta_value, '%d-%m-%Y' )", $orderby );

    return $new_orderby;
}

function we_order_by($orderby) {
    global $wpdb;

    // Order by start date unless it's in the past, then order by end date
    $orderby = "
        STR_TO_DATE(
            CASE
                -- If start date exists and is in the future, use start date
                WHEN EXISTS (
                    SELECT 1
                    FROM {$wpdb->postmeta} AS start_meta
                    WHERE start_meta.post_id = {$wpdb->posts}.ID
                      AND start_meta.meta_key = '_wolf_event_start_date'
                      AND STR_TO_DATE(start_meta.meta_value, '%d-%m-%Y') >= CURDATE()
                )
                THEN (
                    SELECT start_meta.meta_value
                    FROM {$wpdb->postmeta} AS start_meta
                    WHERE start_meta.post_id = {$wpdb->posts}.ID
                      AND start_meta.meta_key = '_wolf_event_start_date'
                    LIMIT 1
                )
                -- If start date is in the past, use the end date
                WHEN EXISTS (
                    SELECT 1
                    FROM {$wpdb->postmeta} AS end_meta
                    WHERE end_meta.post_id = {$wpdb->posts}.ID
                      AND end_meta.meta_key = '_wolf_event_end_date'
                )
                THEN (
                    SELECT end_meta.meta_value
                    FROM {$wpdb->postmeta} AS end_meta
                    WHERE end_meta.post_id = {$wpdb->posts}.ID
                      AND end_meta.meta_key = '_wolf_event_end_date'
                    LIMIT 1
                )
                -- Fallback to start date if no end date exists
                ELSE (
                    SELECT start_meta.meta_value
                    FROM {$wpdb->postmeta} AS start_meta
                    WHERE start_meta.post_id = {$wpdb->posts}.ID
                      AND start_meta.meta_key = '_wolf_event_start_date'
                    LIMIT 1
                )
            END, '%d-%m-%Y') ASC";

    return $orderby;
}

function we_order_by_past( $orderby ) {
	global $wpdb;

    // Order by the latest date (start or end) in descending order
    $orderby = "
        STR_TO_DATE(
            (SELECT start_meta.meta_value
             FROM {$wpdb->postmeta} AS start_meta
             WHERE start_meta.post_id = {$wpdb->posts}.ID
               AND start_meta.meta_key = '_wolf_event_start_date'
             LIMIT 1), '%d-%m-%Y') DESC";

    return $orderby;
}

/**
 * "where" SQL filter
 *
 * for future events
 *
 * @param string $where
 * @return string
 */
function we_future_where( $where ) { // future events
	global $wpdb;
	$meta = $wpdb->prefix . 'postmeta';

	//$where .= "AND (STR_TO_DATE( $meta.meta_value,'%d-%m-%Y' ) >= CURDATE())";

	// Add condition to use start date if end date is missing

    $where .= " AND (
        (
            EXISTS (
                SELECT 1
                FROM {$wpdb->postmeta} AS start_meta
                WHERE start_meta.post_id = {$wpdb->posts}.ID
                  AND start_meta.meta_key = '_wolf_event_start_date'
                  AND STR_TO_DATE(start_meta.meta_value, '%d-%m-%Y') >= CURDATE()
            )
        )
        OR (
            EXISTS (
                SELECT 1
                FROM {$wpdb->postmeta} AS end_meta
                WHERE end_meta.post_id = {$wpdb->posts}.ID
                  AND end_meta.meta_key = '_wolf_event_end_date'
                  AND STR_TO_DATE(end_meta.meta_value, '%d-%m-%Y') >= CURDATE()
            )
        )
    )";

	return $where;
}

/**
 * "where" SQL filter
 *
 * for past events
 *
 * @param string $where
 * @return string
 */
function we_past_where( $where ) { // past events
	global $wpdb;
	$meta = $wpdb->prefix . 'postmeta';

	// $where .= "AND STR_TO_DATE( $meta.meta_value,'%d-%m-%Y' ) < CURDATE()";

	$where .= " AND (
        (
            EXISTS (
                SELECT 1
                FROM {$wpdb->postmeta} AS start_meta
                WHERE start_meta.post_id = {$wpdb->posts}.ID
                  AND start_meta.meta_key = '_wolf_event_start_date'
                  AND STR_TO_DATE(start_meta.meta_value, '%d-%m-%Y') < CURDATE()
            )
        )
        AND (
            NOT EXISTS (
                SELECT 1
                FROM {$wpdb->postmeta} AS end_meta
                WHERE end_meta.post_id = {$wpdb->posts}.ID
                  AND end_meta.meta_key = '_wolf_event_end_date'
            )
            OR EXISTS (
                SELECT 1
                FROM {$wpdb->postmeta} AS end_meta
                WHERE end_meta.post_id = {$wpdb->posts}.ID
                  AND end_meta.meta_key = '_wolf_event_end_date'
                  AND STR_TO_DATE(end_meta.meta_value, '%d-%m-%Y') < CURDATE()
            )
        )
    )";

	return $where;
}

/**
 * Obsolete
 *
 * @param [type] $join
 * @return void
 */
function we_join( $join ) {

	global $wpdb;

	if ( strpos( $join, "{$wpdb->postmeta}" ) === false ) {
		$join .= " INNER JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id";
	}
	return $join;
}


/**
 * "where" SQL filter
 *
 * for current month events
 *
 * @param string $where
 * @return string
 */
function we_current_month_where_filter( $where ) {
	global $wpdb;
	$meta = $wpdb->prefix . 'postmeta';

	// Start and end dates for the current month
    $current_date = date("Y-m-d");
	$current_month_end = we_get_last_date_of_the_month( $current_date );

	$where .= " AND (
        (
            EXISTS (
                SELECT 1
                FROM {$wpdb->postmeta} AS start_meta
                WHERE start_meta.post_id = {$wpdb->posts}.ID
                  AND start_meta.meta_key = '_wolf_event_start_date'
                  AND STR_TO_DATE(start_meta.meta_value, '%d-%m-%Y') BETWEEN '$current_date' AND '$current_month_end'
            )
        )
        OR (
            EXISTS (
                SELECT 1
                FROM {$wpdb->postmeta} AS end_meta
                WHERE end_meta.post_id = {$wpdb->posts}.ID
                  AND end_meta.meta_key = '_wolf_event_end_date'
                  AND STR_TO_DATE(end_meta.meta_value, '%d-%m-%Y') BETWEEN '$current_date' AND '$current_month_end'
            )
        )
    )";

	return $where;
}

/**
 * "where" SQL filter
 *
 * for next month events
 *
 * @param string $where
 * @return string
 */
function we_next_month_where_filter( $where ) {
	global $wpdb;
	$meta = $wpdb->prefix . 'postmeta';

	// Start and end dates for the next month
    $next_month_start = date('Y-m-01', strtotime('first day of next month'));
    $next_month_end = date('Y-m-t', strtotime('last day of next month'));

	 $where .= " AND (
        (
            EXISTS (
                SELECT 1
                FROM {$wpdb->postmeta} AS start_meta
                WHERE start_meta.post_id = {$wpdb->posts}.ID
                  AND start_meta.meta_key = '_wolf_event_start_date'
                  AND STR_TO_DATE(start_meta.meta_value, '%d-%m-%Y') BETWEEN '$next_month_start' AND '$next_month_end'
            )
        )
        OR (
            EXISTS (
                SELECT 1
                FROM {$wpdb->postmeta} AS end_meta
                WHERE end_meta.post_id = {$wpdb->posts}.ID
                  AND end_meta.meta_key = '_wolf_event_end_date'
                  AND STR_TO_DATE(end_meta.meta_value, '%d-%m-%Y') BETWEEN '$next_month_start' AND '$next_month_end'
            )
        )
    )";

	return $where;
}

/**
 * "where" SQL filter
 *
 * for current month events
 *
 * @param string $where
 * @return string
 */
function we_date_range_where( $where, $date_start, $date_end ) {
	global $wpdb;

	// Ensure the parameters are provided
    if ( !$date_start || !$date_end) {
        return $where; // Return the original `WHERE` clause if parameters are missing
    }

	$where .= " AND (
        (
            EXISTS (
                SELECT 1
                FROM {$wpdb->postmeta} AS start_meta
                WHERE start_meta.post_id = {$wpdb->posts}.ID
                  AND start_meta.meta_key = '_wolf_event_start_date'
                  AND STR_TO_DATE(start_meta.meta_value, '%d-%m-%Y') BETWEEN '$date_start' AND '$date_end'
            )
        )
        OR (
            EXISTS (
                SELECT 1
                FROM {$wpdb->postmeta} AS end_meta
                WHERE end_meta.post_id = {$wpdb->posts}.ID
                  AND end_meta.meta_key = '_wolf_event_end_date'
                  AND STR_TO_DATE(end_meta.meta_value, '%d-%m-%Y') BETWEEN '$date_start' AND '$date_end'
            )
        )
    )";

	return $where;
}


function we_custom_orderby($orderby) {
    global $wpdb;

	$orderby = "
        STR_TO_DATE(
            CASE
                WHEN EXISTS (
                    SELECT 1
                    FROM {$wpdb->postmeta} AS end_meta
                    WHERE end_meta.post_id = {$wpdb->posts}.ID
                      AND end_meta.meta_key = '_wolf_event_end_date'
                )
                AND EXISTS (
                    SELECT 1
                    FROM {$wpdb->postmeta} AS start_meta
                    WHERE start_meta.post_id = {$wpdb->posts}.ID
                      AND start_meta.meta_key = '_wolf_event_start_date'
                )
                THEN LEAST(
                    (SELECT STR_TO_DATE(end_meta.meta_value, '%d-%m-%Y')
                     FROM {$wpdb->postmeta} AS end_meta
                     WHERE end_meta.post_id = {$wpdb->posts}.ID
                       AND end_meta.meta_key = '_wolf_event_end_date'
                     LIMIT 1),
                    (SELECT STR_TO_DATE(start_meta.meta_value, '%d-%m-%Y')
                     FROM {$wpdb->postmeta} AS start_meta
                     WHERE start_meta.post_id = {$wpdb->posts}.ID
                       AND start_meta.meta_key = '_wolf_event_start_date'
                     LIMIT 1)
                )
                ELSE STR_TO_DATE(
                    (SELECT start_meta.meta_value
                     FROM {$wpdb->postmeta} AS start_meta
                     WHERE start_meta.post_id = {$wpdb->posts}.ID
                       AND start_meta.meta_key = '_wolf_event_start_date'
                     LIMIT 1), '%d-%m-%Y')
            END, '%d-%m-%Y') ASC";

	return $orderby;
}

/**
 * Get last day of a month from any date in the month
 *
 *
 * @param [type] $date
 * @return void
 * @link https://sebhastian.com/php-get-last-day-of-month/
 */
function we_get_last_date_of_the_month( $date ) {
	// 👇 get the timestamp of the date
	//$date = strtotime("2nd January 2022");
	$date = strtotime( $date );
	// 👇 get the last date for that month
	$last_date = date("Y-m-t", $date);

	return  $last_date; // 2022-01-31
}
