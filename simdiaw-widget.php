<?php
// Creating the widget
class simdiaw_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            // Base ID of simdia widget
            'simdiaw_widget',

            // Widget name will appear in UI
            __('Upcoming reminders', 'simple-diary'),

            // Widget description
            array( 'description' => __( 'Listing of the upcoming simple diary reminders.', 'simple-diary' ), )
        );
    }

    /* Creating widget front-end */
    public function widget( $args, $instance ) {
        $r_count = empty( $instance['number'] ) ? 3 : absint( $instance['number'] );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __('Upcoming reminders', 'simple-diary') : $instance['title'], $instance, $this->id_base );
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if ( ! empty( $title ) )
            echo $args['before_title'] . $title . $args['after_title'];

        // This is what the widget will print on front-end
        echo '<ul>'."\n";
        the_simdiaw_upcoming_reminders($r_count);
        echo '</ul>'."\n";

        // after widget arguments
        echo $args['after_widget'];

        wp_reset_postdata();
    }

    /* Widget Backend */
    public function form( $instance ) {
        $title  = empty( $instance['title'] ) ? '' : esc_attr( $instance['title'] );
        $number = empty( $instance['number'] ) ? 3 : absint( $instance['number'] );
        // Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p><label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Number of reminders to show:', 'simple-diary' ); ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" size="3"></p>
        <?php
    }

    /* Updating widget replacing old instances with new */
    public function update( $new_instance, $instance ) {
        $instance['title']  = strip_tags( $new_instance['title'] );
        $instance['number'] = empty( $new_instance['number'] ) ? 3 : absint( $new_instance['number'] );
        return $instance;
    }
} // Class simdiaw_widget ends here

// Register and load the widget
function simdiaw_load_widget() {
	register_widget( 'simdiaw_widget' );
}
add_action( 'widgets_init', 'simdiaw_load_widget' );
?>
