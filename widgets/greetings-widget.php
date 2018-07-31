<?php
// Register and load the widget
function greetings_load_widget() {
    register_widget( 'greetings_widget' );
}
add_action( 'widgets_init', 'greetings_load_widget' );

// Creating the widget
class greetings_widget extends WP_Widget {

function __construct() {
parent::__construct(

// Base ID of your widget
'greetings_widget',

// Widget name will appear in UI
__('Greetings and Farewells', 'greetings_widget_domain'),

// Widget description
array( 'description' => __( 'Displays CPC greetings and farewells', 'greetings_widget_domain' ), )
);
}

// Creating widget front-end

public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );

// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

// This is where you run the code and display the output
echo "<small><p>The following individuals have recently joined or departed the CPC. If you know of anyone else who should be on this list or have other corrections, please contact <a href='mailto:cpcadmin@unc.edu'>CPC Administration</a></p>
</small>
<h4>Hello To</h4>
<ul>";
for ($i = 0; $i<2; $i++) {
  $name = "Firstname Lastname";
  $position = "Research Services (Web)";
  $location = "2148E 123 W Franklin St";
  echo "<li>$name - $position in $location</li>";
}
echo "</ul>
<h4>Goodbye To</h4>
<ul>";
for ($i = 0; $i<2; $i++) {
  $name = "Firstname Lastname";
  $position = "Research Services (Web)";
  $location = "2148E 123 W Franklin St";
  echo "<li>$name - former $position in $location</li>";
}
echo "</ul>";

echo $args['after_widget'];
}

// Widget Backend
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'Greetings and Farewells', 'greetings_widget_domain' );
}
// Widget admin form
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
} // Class greetings_widget ends here
