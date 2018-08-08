<?php
   /*
   Plugin Name: CPC People
   Description: Display CPC Mugboard, Faces of CPC and Greetings and Farewells widgets
   Version: 1.1

   Author: Tony Bird

   License: GNU General Public License v2 or later
   License URI: http://www.gnu.org/licenses/gpl-2.0.html

   */

   include( plugin_dir_path( __FILE__ ) . 'options-menu.php');
   $opts = get_option( 'cpc_people_options' );
   $peopledb = new wpdb($opts['username'], $opts['password'], $opts['table'], $opts['hostname']);

   include( plugin_dir_path( __FILE__ ) . 'widgets/faces-widget.php');
   include( plugin_dir_path( __FILE__ ) . 'widgets/greetings-widget.php');
   include( plugin_dir_path( __FILE__ ) . 'widgets/project-widget.php');

   include( plugin_dir_path( __FILE__ ) . 'shortcodes/mugboard.php');
   //Datatables demo shortcodes
   include( plugin_dir_path( __FILE__ ) . 'shortcodes/mugboard-dt.php');
   include( plugin_dir_path( __FILE__ ) . 'shortcodes/searchable-dt.php');


   include( plugin_dir_path( __FILE__ ) . 'shortcodes/new-db-test.php');

?>
