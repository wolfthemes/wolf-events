<?php
/**
 * Event list Widget
 *
 * Displays an events list
 *
 * @author WolfThemes
 * @category Widgets
 * @package WolfEvents/Widgets
 * @version %VERSION%
 * @extends WP_Widget
 */

defined( 'ABSPATH' ) || exit;

class WE_Event_List_Widget extends WP_Widget {

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
		$this->we_widget_cssclass 	= 'we_event_list_widget';
		$this->we_widget_description = esc_html__( 'Displays the upcoming events in a list.', 'wolf-events' );
		$this->we_widget_idbase 	= 'we_event_list_widget';
		$this->we_widget_name 	= esc_html__( 'Event list', 'wolf-events' );

		/* Widget settings. */
		$widget_ops = array( 'classname' => $this->we_widget_cssclass, 'description' => $this->we_widget_description );

		/* Create the widget. */
		parent::__construct( 'we_event_list_widget', $this->we_widget_name, $widget_ops );
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

		$count = ( isset( $instance['count'] ) ) ? absint( $instance['count'] ) : 10;

		$args = array(
			'count' => $count,
			'widget' => true,
			'link' => true,
			'artist' => false,
		);

		wolf_event_list( $args );

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
			'title' => '',
			'count' => 10,
		) );


		$title = wp_strip_all_tags( $instance['title'] );
		$count = absint( $instance['count'] );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'wolf-events' ); ?></label>
			<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ) ; ?>" value="<?php echo esc_attr( $title ); ?>" class="widefat">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( 'Count:', 'wolf-events' ); ?></label>
			<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ) ; ?>" value="<?php echo esc_attr( $count ); ?>" class="widefat">
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
