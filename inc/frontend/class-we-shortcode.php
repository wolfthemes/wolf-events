<?php
/**
 * Wolf Events Shortcode.
 *
 * @class WE_Shortcode
 * @author WolfThemes
 * @category Core
 * @package WolfPageBuilder/Shortcode
 * @version 1.2.2
 * @since 1.2.6
 */

defined( 'ABSPATH' ) || exit;

/**
 * WE_Shortcode class.
 */
class WE_Shortcode {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_shortcode( 'wolf_event_list', array( $this, 'shortcode' ) );
		add_shortcode( 'wolf_events', array( $this, 'shortcode' ) );
	}

	/**
	 * Render shortcode
	 */
	public function shortcode( $atts = array() ) {
		$atts = shortcode_atts(
			array(
				'count' => -1,
				'timeline' => 'future',
				'link' => false,
				'artist' => false,
				'widget' => false,
			),
			$atts
		);

		$atts['count'] = intval( $atts['count'] );
		$atts['timeline'] = esc_attr( $atts['timeline'] );
		$atts['link'] = $this->shortcode_bool( $atts['link'] );
		$atts['artist'] = esc_attr( $atts['artist'] );
		$atts['widget'] = $this->shortcode_bool( $atts['widget'] );

		ob_start();
		wolf_event_list( $atts );
		return ob_get_clean();
	}

	/**
	 * Helper method to determine if a shortcode attribute is true or false.
	 *
	 * @since 1.0.0
	 *
	 * @param string|int|bool $var Attribute value.
	 * @return bool
	 */
	protected function shortcode_bool( $var ) {
		$falsey = array( 'false', '0', 'no', 'n' );
		return ( ! $var || in_array( strtolower( $var ), $falsey, true ) ) ? false : true;
	}
}

return new WE_Shortcode();
