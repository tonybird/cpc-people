<?php

function new_db_test($atts) {

  $newdb = new wpdb('web', 'cpc4dm!n', 'phone', 'db.cpc.unc.edu');

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


  $results=$newdb->get_results($query);
  print_r($results);


}

add_shortcode('newdbtest', 'new_db_test');

?>
