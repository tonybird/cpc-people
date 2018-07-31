<?php

function display_mugboard($atts) {

 global $projectsdb;
 $search = $_GET['search'];
 $projects_addrownum = $projectsdb->get_results("SELECT @rownum:=@rownum+1 row, title, pi, url, image FROM projects, (SELECT @rownum:=0 ) r WHERE title LIKE '%$search%'; ");

 //$wrapped = $allprojects->get_results("SELECT @rownum:=@rownum+1 row, title, pi, url, image FROM projects, (SELECT @rownum:=0) r;");

?>
<form>
<input name="search" type="text" placeholder="Search" value="<?php echo $_GET['search']?>">

<div style="text-align:center; padding-top:20px">
  CPC Affiliation:  <select name="dept">
  <option value="">--ANY--</option>
  <option value="admin">Administrative Services</option>
 <option value="fellow">Fellow</option>
  <option value="research">Research Services</option>
</select>
</div>
</form>
</br>

<?php
echo "<table><tr>";
 foreach ($projects_addrownum as $proj) {
   echo "<td><img src='http://devweb11.cpc.unc.edu/images/$proj->image' width='150px'><p><a href='$proj->url'>$proj->title</a></br>$proj->pi</p></td>";
   if ($proj->row % 3 == 0) echo "</tr><tr>";
 }
 echo "</tr></table>";
}

add_shortcode('mugboard', 'display_mugboard');

?>
