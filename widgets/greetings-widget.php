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


// print_r($peopledb);

// Creating widget front-end


public function widget( $args, $instance ) {

  global $peopledb;
  $greetings_query = "
SELECT
CONCAT(fname, ' ', lname) AS name,
IF(room <> '',CONCAT(room, ' ', building),'') AS office,
CASE statusId
     WHEN 1  THEN 'Fellow'
     WHEN 2  THEN 'Postdoctoral Scholar'
     WHEN 3  THEN 'Predoctoral Trainee'
     WHEN 4 THEN IF(dept='Spatial','Spatial Analysis Unit',IF(dept='Project','Project Staff',IF(dept='Training Program','Training Program',IF(dept='Proposal Services','Proposal Services',CONCAT(dept, ' Staff')))))
     WHEN 6 THEN 'Graduate Research Assistant'
     WHEN 10 THEN IF(dept='Spatial','Spatial Analysis Unit',IF(dept='Project','Project Staff',IF(dept='Training Program','Training Program',IF(dept='Proposal Services','Proposal Services',CONCAT(dept, ' Staff')))))
     WHEN 13 THEN 'Undergraduate Trainee'
     WHEN 16 THEN 'Faculty, Non-Fellow'
     WHEN 18 THEN 'Visitor'
     ELSE 'UNKNOWN'
     END AS affiliation,
lname,
fname
FROM telly
WHERE
archived = '0'
AND (startdate > DATE_SUB(NOW(), INTERVAL 14 DAY))
AND (startdate < NOW())


UNION


SELECT
CONCAT(fname, ' ', lname) AS name,
IF(room <> '',CONCAT(room, ' ',building),'') AS office,
IF(	(endCPC = '' OR endCPC IS NULL),
	IF(	(project = '' OR project IS NULL),
		IF(	(sponsor = '' OR sponsor IS NULL),
			'Visitor',
			CONCAT('Visiting with ',sponsor)
			),
		CONCAT('Visiting with ',project)
		),
	IF(	(project = '' OR project IS NULL),
		IF(	(sponsor = '' OR sponsor IS NULL),
			CONCAT('Visiting until ~', endCPC),
			CONCAT('Visiting with ',sponsor,' until ~', endCPC)
			),
		CONCAT('Visiting with ',project,' until ~', endCPC)
		)
) AS affiliation,
lname,
fname
FROM visitors
WHERE
archived = '0'
AND (DATE_SUB(NOW(), INTERVAL 14 DAY) < startCPC) AND (endCPC > NOW() OR endCPC = '' OR endCPC IS NULL)

ORDER BY lname, fname
";

$farewells_query = "
SELECT
CONCAT(fname, ' ', lname) AS name,
IF(room <> '',CONCAT(room, ' ', building),'') AS office,
CASE statusId
     WHEN 7  THEN 'Fellow'
     WHEN 8  THEN 'Postdoctoral Scholar'
     WHEN 9  THEN 'Predoctoral Trainee'
     WHEN 20 THEN IF(dept='Spatial','Spatial Analysis Unit',IF(dept='Project','Project Staff',IF(dept='Training Program','Training Program',IF(dept='Proposal Services','Proposal Services',CONCAT(dept, ' Staff')))))
     WHEN 15 THEN 'Graduate Research Assistant'
     WHEN 11 THEN IF(dept='Spatial','Spatial Analysis Unit',IF(dept='Project','Project Staff',IF(dept='Training Program','Training Program',IF(dept='Proposal Services','Proposal Services',CONCAT(dept, ' Staff')))))
     WHEN 14 THEN 'Undergraduate Trainee'
     WHEN 17 THEN 'Faculty, Non-Fellow'
     WHEN 18 THEN 'Visitor'
     ELSE 'UNKNOWN'
     END AS affiliation,
lname,
fname
FROM telly
WHERE archived = '1' AND (DATE_SUB(NOW(), INTERVAL 14 DAY) < archive)


UNION


SELECT
CONCAT(fname, ' ', lname) AS name,
IF(room <> '',CONCAT(room, ' ',building),'') AS office,
IF(	(endCPC = '' OR endCPC IS NULL),
	IF(	(project = '' OR project IS NULL),
		IF(	(sponsor = '' OR sponsor IS NULL),
			'Visitor',
			CONCAT('Visitor with ',sponsor)
			),
		CONCAT('Visitor with ',project)
		),
	IF(	(project = '' OR project IS NULL),
		IF(	(sponsor = '' OR sponsor IS NULL),
			CONCAT('Visitor until ~', endCPC),
			CONCAT('Visitor with ',sponsor,' until ~', endCPC)
			),
		CONCAT('Visitor with ',project,' until ~', endCPC)
		)
) AS affiliation,
lname,
fname
FROM visitors
WHERE archived = '1' AND endCPC != '' AND  endCPC IS NOT NULL AND (DATE_SUB(NOW(), INTERVAL 14 DAY) < endCPC)

ORDER BY lname, fname
";

$greetings = $peopledb->get_results( $greetings_query );
$farewells = $peopledb->get_results( $farewells_query );

$title = apply_filters( 'widget_title', $instance['title'] );

// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

// This is where you run the code and display the output
$awesome_boat_picture = plugin_dir_url( __FILE__ ) . 'img/boat.png';
echo "<img src='$awesome_boat_picture' border='0' style='float: left; padding: 1em;'><small>
<p>The following individuals have recently joined or departed the CPC. If you know of anyone else who should be on this list or have other corrections, please contact <a href='mailto:cpcadmin@unc.edu'>CPC Administration</a></p>
</small>";

if (!empty($greetings)) {
  echo "<h4>Hello To</h4>
  <ul>";
foreach ($greetings as $p) {
  $name = $p->name;
  $affiliation = $p->affiliation;
  $office = $p->office;
  echo "<li>$name";
  if ($affiliation) echo "- $affiliation";
  if ($office) echo " in $office";
  echo "</li>";
}
}
if (!empty($farewells)) {
echo "</ul>
<h4>Goodbye To</h4>
<ul>";
foreach ($farewells as $p) {
  $name = $p->name;
  $affiliation = $p->affiliation;
  $office = $p->office;
  echo "<li>$name";
  if ($affiliation) echo "- former $affiliation";
  if ($office) echo " in $office";
  echo "</li>";
}
echo "</ul>";
}

if (empty($farewells) && empty($greetings)) {
  echo "No one has recently joined or departed";
}
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
