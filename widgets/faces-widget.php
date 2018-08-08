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


global $peopledb;
$query = "SELECT
userid,

CONCAT(fname, ' ', lname) as name,

alias as email,

IF(ISNULL(phone2) OR phone2 = '', phone, IF(ISNULL(phone) OR phone = '',  phone2, CONCAT(phone, ' / ', phone2))) as telephone,

IF( room2 != '',
     CONCAT( IF( room!= '', CONCAT(REPLACE(room,',','%2C'), ' ', REPLACE(building,',','%2C'), ' / '), ''),
                     CONCAT(REPLACE(room2,',','%2C'), ' ', REPLACE(building2,',','%2C'))),
     IF( room != '', CONCAT(REPLACE(room,',','%2C'), ' ', REPLACE(building,',','%2C')), '')
  ) as office,

CASE statusId
                            WHEN 1   THEN 'Fellow'
                            WHEN 2   THEN 'Postdoctoral Scholar'
                            WHEN 3   THEN 'Predoctoral Trainee'
                            WHEN 4   THEN IF(dept='Project','Project Personnel',dept)
                            WHEN 6   THEN 'Graduate Research Assistant'
                            WHEN 10 THEN IF(dept='Project','Project Staff',dept)
                            WHEN 12 THEN 'Fellow &amp; CPC Director'
                            WHEN 13 THEN 'Undergraduate Trainee'
                            WHEN 16 THEN 'Faculty, Non-Fellow'
                            WHEN 18 THEN 'Visitor'
                            WHEN 19 THEN 'Fax Machine'
                            WHEN 22 THEN 'Room Phone'
                            WHEN 23 THEN 'Other'
                            ELSE 'UNKNOWN'
            END as affiliation,

userID,

showPhoto,
lname as last_name,
fname as first_name

FROM `telly`

WHERE
archived = '0'
AND statusId in (1,2,3,4,6,10,12,13,16,18)



ORDER BY RAND() LIMIT 1;";

$results = $peopledb->get_results( $query );
$person = $results[0];

$img = "http://www.cpc.unc.edu/people/images/".$person->userid;
if (!getimagesize($img)) $img = "https://www.cpc.unc.edu/people/images/no-photo.jpg";
$name = $person->name;
$position = $person->affiliation;
$email = $person->email;
$location = $person->office;
$phone = $person->telephone;

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
