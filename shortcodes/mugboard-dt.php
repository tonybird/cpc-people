<?php

function display_mugboard_dt($atts) {

 global $projectsdb;
 $allprojects = $projectsdb->get_results("SELECT title, pi, url, image FROM projects");

 echo "<table id='projects' class='table'>
        <thead>
          <tr>
            <th>Image</th>
            <th>Name</th>
            <th>PI</th>
          </tr>
        </thead>
        <tbody>";

foreach ($allprojects as $proj) {
  echo "<tr><td><img src='http://devweb11.cpc.unc.edu/images/$proj->image' width='100px'></td><td><p><a href='$proj->url'>$proj->title</a></td><td>$proj->pi</td></tr>";
}
echo "</tbody></table>";

echo "
 <script type='text/javascript'>
 $('#projects').DataTable({});
 </script>
";

}

add_shortcode('mugboard-dt', 'display_mugboard_dt');

?>
