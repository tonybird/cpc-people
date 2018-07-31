<?php
function faces_load_widget() {
    register_widget( 'faces_widget' );
}
add_action( 'widgets_init', 'faces_load_widget' );

class faces_widget extends WP_Widget {

function __construct() {
parent::__construct('faces_widget', __('Faces of CPC', 'faces_widget_domain'),
array( 'description' => __( 'Displays random Face of CPC', 'faces_widget_domain' ), )
);
}

// front-end
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );

echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

// temp content
$img = "https://www.cpc.unc.edu/people/images/no-photo.jpg";
$name = "Firstname Lastname";
$position = "Position";
$email = "email@unc.edu";
$location = "123 W Franklin Street";
$phone = "(919) 555-1234";

echo nl2br("<img src='$img' width='150 px' class='alignleft' style='margin-right: 10px;''>
<b>$name</b>
$position
<a href='mailto:$email'>$email</a>
$location
$phone");

echo $args['after_widget'];
}

// Backend
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'Faces of CPC', 'faces_widget_domain' );
}

// admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php
}

// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
}
