<?php
/**
 * Events Widget
 *
 * Displays the upcoming events
 *
 * @author WolfThemes
 * @category Widgets
 * @package WolfEvents/Widgets
 * @version 1.2.2
 * @extends WP_Widget
 */

defined( 'ABSPATH' ) || exit;

class WE_Events_Widget extends WP_Widget {

	var $we_widget_cssclass;
	var $we_widget_description;
	var $we_widget_idbase;
	var $we_widget_name;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @see WP_Widget::construct()
	 */
	public function __construct() {
		/* Widget variable settings. */
		$this->we_widget_cssclass 	= 'we_events_widget';
		$this->we_widget_description = esc_html__( 'Displays the upcoming events with thumbnail.', 'wolf-events' );
		$this->we_widget_idbase 	= 'we_events_widget';
		$this->we_widget_name 	= esc_html__( 'Events', 'wolf-events' );

		/* Widget settings. */
		$widget_ops = array( 'classname' => $this->we_widget_cssclass, 'description' => $this->we_widget_description );

		/* Create the widget. */
		parent::__construct( 'we_events_widget', $this->we_widget_name, $widget_ops );
	}

	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	function widget( $args, $instance ) {

		extract( $args );

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		echo $before_widget;

		if ( $title )  {
			echo $before_title . $title . $after_title;
		}

		// do stuff

		echo $after_widget;
	}

	/**
	 * Form to modify widget instance settings.
	 *
	 * @since 1.0.0
	 *
	 * @param array $instance Current widget instance settings.
	 */
	public function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, array(
			'title'   => '',
		) );


		$title = wp_strip_all_tags( $instance['title'] );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'wolf-events' ); ?></label>
			<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ) ; ?>" value="<?php echo esc_attr( $title ); ?>" class="widefat">
		</p>
		<?php
	}

	/**
	 * Save widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @param array $new_instance New widget settings.
	 * @param array $old_instance Old widget settings.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = wp_parse_args( $new_instance, $old_instance );

		$instance['title'] = wp_strip_all_tags( $new_instance['title'] );

		return $instance;
	}
}
