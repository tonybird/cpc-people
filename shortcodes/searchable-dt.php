<?php
//https://www.jqueryscript.net/table/Inline-Table-Editing-jQuery-Tabledit.html
function display_searchable_dt($atts) {
 global $projectsdb;
 $wpproj= $projectsdb->get_results("SELECT title, pi, url, image FROM wpprojects");

 echo "<link rel='stylesheet' type='text/css' href='https://cdn.datatables.net/v/bs4/jq-3.2.1/dt-1.10.16/datatables.min.css'/>

<script type='text/javascript' src='https://cdn.datatables.net/v/bs4/jq-3.2.1/dt-1.10.16/datatables.min.js'></script>

";
 echo "<table id='projects' class='table'>
        <thead>
          <tr>
            <th>Name</th>
            <th>PI</th>
            <th>URL</th>
          </tr>
        </thead>
        <tbody>";
foreach ($wpproj as $proj) {
  echo "<tr>
  <td>$proj->title</td>
  <td>$proj->pi</td>
  <td>$proj->url</td>
  </tr>";
}
echo "</tbody>
<tfoot>
  <tr>
    <th>Name</th>
    <th>PI</th>
    <th>URL</th>
  </tr>
</tfoot>
</table>

<script type='text/javascript'>
// Setup - add a text input to each footer cell
    $('#projects tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type=\"text\" placeholder=\"Search '+title+'\" />' );
    } );

    // DataTable
    var table = $('#projects').DataTable({
    });

    // Apply the search
    table.columns().every( function () {
        var that = this;

        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );

    $('#projects tfoot tr').appendTo('#projects thead');


</script>

";

}

add_shortcode('searchable-dt', 'display_searchable_dt');

?>
