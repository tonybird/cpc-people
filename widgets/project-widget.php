<?php
function project_load_widget() {
    register_widget( 'project_widget' );
}
add_action( 'widgets_init', 'project_load_widget' );

class project_widget extends WP_Widget {

function __construct() {
parent::__construct('project_widget', __('CPC Projects', 'project_widget_domain'),
array( 'description' => __( 'Displays random CPC project', 'project_widget_domain' ), )
);
}

// front-end
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );

echo $args['before_widget'];

echo "<div class='project-widget'>";
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

global $peopledb;
$randproj = $peopledb->get_results( "SELECT title, pi, url, image FROM projects ORDER BY RAND() LIMIT 1" );

foreach ($randproj as $proj) {
  echo "<img src='http://devweb11.cpc.unc.edu/images/$proj->image' width='100px' class='alignleft' style='margin-right: 10px;'>";
  echo "<b><a href='$proj->url'>$proj->title</a></b>";
  echo "<p><i>$proj->pi</i></p>";
}

echo "</div>";
echo $args['after_widget'];
}

// Backend
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'project of CPC', 'project_widget_domain' );
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
