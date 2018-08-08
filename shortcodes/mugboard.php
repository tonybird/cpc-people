<?php

function display_mugboard($atts) {

  wp_register_style('mugboard', plugins_url('css/mugboard.css',__FILE__ ));;
  wp_enqueue_style('mugboard');

 global $peopledb;
 $search = $_GET['search'];
 $affil = $_GET['affil'];

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
 ";

if ($search != "") {
  $query = $query . "
  AND CONCAT(fname, ' ', lname) LIKE '%$search%'
  ";
}

if ($affil != "") {
  $query = $query . "
  AND statusID = '$affil'
  ";
}


 $query = $query . "
ORDER BY last_name;
 ";
 $people = $peopledb->get_results($query);

 //$wrapped = $allprojects->get_results("SELECT @rownum:=@rownum+1 row, title, pi, url, image FROM projects, (SELECT @rownum:=0) r;");

$affiliations = array(
  '' => '--ANY--',
  '1' => 'Fellow',
  '2' => 'Postdoctoral Scholar',
  '3' => 'Predoctoral Trainee',
  '4' => 'Project Personnel',
  '6' => 'Graduate Research Assistant',
  '10' => 'Project','Project Staff',
  '12' => 'Fellow &amp; CPC Director',
  '13' => 'Undergraduate Trainee',
  '16' => 'Faculty, Non-Fellow',
  '18' => 'Visitor',
  '19' => 'Fax Machine',
  '22' => 'Room Phone',
  '23' => 'Other'
);
?>
<form>
<input name="search" type="text" placeholder="Search" value="<?php echo $_GET['search']?>">

<div class="searchrow">
  <div><label for="affil">CPC Affiliation: </label><select id="affil" name="affil">
    <?php

    foreach ($affiliations as $num => $name) {
      echo "<option value='$num'";
      if ($affil == $num) echo " selected ";
      echo ">$name</option>";
    } ?>
</select></div>
<button type="submit" value="Search">Search</button>
</div>
</form>
</br>

<?php
echo "<div class='mugboard'>";
 foreach ($people as $person) {
   echo "<div class='mugboard-entry'>";
   $img = "http://www.cpc.unc.edu/people/images/".$person->userid;
   if (!getimagesize($img)) $img = "https://www.cpc.unc.edu/people/images/no-photo.jpg";

   echo "<img src='$img' width='150px'><p><b>$person->name</b>";
   if ($person->affiliation) echo "</br>$person->affiliation";
   if ($person->email) echo "</br><a href='mailto:$person->email'>$person->email</a>";
   if ($person->office) echo "</br>$person->office";
   if ($person->telephone) echo "</br>$person->telephone";
   echo "</p>";
   echo "</div>";
 }
}
echo "</div>";

add_shortcode('mugboard', 'display_mugboard');

?>
