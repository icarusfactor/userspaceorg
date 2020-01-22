<?php
/**
 * @package RSS Organized Audio Peel AppLe 
 */
/*
Plugin Name: RSS Oraganized Audio Peel App 
Plugin URI: http://userspace.org
Description: This app gathers RSS feed data from selected site and prioritizes and requires the AppLepie project plugin.  
Version: 0.9.15
Author: Daniel Yount IcarusFactor
Author URI: http://userspace.org
License: GPLv2 or later
Text Domain: opeel-appLe
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

*/

defined( 'ABSPATH' ) or die( 'Hey, what are you doing here? You silly human!' );

if ( !class_exists( 'opeelAppLe' ) && class_exists( 'AppLePiePlugin' )  ) {

	class opeelAppLe
	{

		public $plugin;

		function __construct() {
			$this->plugin = plugin_basename( __FILE__ );
		}

		function register() {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
		}


		function enqueue() {
			// enqueue all our scripts
			//wp_enqueue_style( 'audiopeelappstyle', plugins_url( '/assets/audiopeelappstyle.css', __FILE__ ) );
			//wp_enqueue_script( 'audiopeelappscript', plugins_url( '/assets/audiopeelappscript.js', __FILE__ ) );
		}

		function activate() {


                        // Require parent plugin
                        if ( ! is_plugin_active( 'applepie_plugin/applepie-plugin.php' ) and current_user_can( 'activate_plugins' ) )
                        {
                        // Stop activation redirect and show error
                        wp_die('Sorry, but this plugin requires the Parent Plugin to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
                        }

			require_once plugin_dir_path( __FILE__ ) . 'inc/opeel-app-activate.php';
			opeelAppActivate::activate();
		}

                // Place modification scripts here for Applepie plugin. Hardcoded to first item only currently.

                 //Need to put this in its own class at some point.
                 function  priority_cast( $id , $section ) { 
                           global $wpdb;
                           //will need to add class for APFM , hard coded names for now. REQ: MYSQL >8
                           $wpdb->query( "SELECT @rownumber:=0;" );
                           $rqFEED="SELECT @rownumber := @rownumber+1 AS priority,id,section,site,rss,url,catagory,enable,last_post_date FROM ap_section_feeds HAVING `priority`=".$id." AND section=\"".$section."\" ORDER BY last_post_date DESC;";
                           //$rqFEED="SELECT * FROM ap_section_feeds WHERE section=\"Linux News Videos\" ORDER BY last_post_date DESC LIMIT 1;";  
                           $results=$wpdb->get_row( $rqFEED , ARRAY_A );
                           //error_log("PRIORITY_CAST INSIDE: ".$rqFEED." VAR DUMP:".var_dump($results) ); 
                           return $results;
                 }   
 
                 // When find that current date is changed to newer date update FEEd data.
                 function  update_timestamp( $id , $newtimestamp ) { 
                           global $wpdb;
                           $udFEED="UPDATE `ap_section_feeds` SET `last_post_date` = '".$newtimestamp."' WHERE id=".$id    .";";
                           }   



		function  start_up( $atts ) {

                       $a2b=[[]];
                        // Working on APTEXT 
                        $a = shortcode_atts( array(
                         'id' => '1',
                         'section' => 'AUDIO FEED SOURCES',
			 'count' => '5',
                         'media' => 'APAUDIO'
                        ), $atts );

                        //Grab RSS feed data and priority from the ID and section name.
                        // This will return one row with the id priority based on date.
                        $a2b=$this->priority_cast( $a['id'] , $a['section'] );


                        $ApplepiePlugin = new AppLePiePlugin();

                        $Content = $ApplepiePlugin->feed_generate_header();


                        //the output only uses one item, will make this loop to count in future. 
 
			//$Content .= "<span ><a href=\"".$a['url']."\" >\"".$a['name']."</a></span></br>"; 

			//$Content .= " <span style=\"font-size: 9px;text-decoration: underline overline; \" >/// ".$a['date']." ///</span>"; 
			//$Content .= "<a style=\"font-size: 9px;text-decoration: underline overline; \" href=\"".$a['url']."\" >/// ".$a['name']." ///</a></br>"; 

			$Content .= $ApplepiePlugin->feed_generate_headtofoot( $a['media'] );

			$Content .= "<span style=\"font-size: 12px;\" >";

                        $Content  .= do_shortcode( "[podcastplayer feed_url ='".$a2b['rss']."' number='".$a['count']."' hide_loadmore='true' hide_social='true' hide_content='true' hide_cover='true' hide_description='true' hide_search='true' hide_subscribe='false' hide_title='true' accent_color='#dcf3ff' hide_author='true' header_default='false'  hide_download=`true` ]" );


			$Content .= "</span>"; 

			$Content .= $ApplepiePlugin->feed_generate_footer();

			return $Content;
		}  

	
	
	}

	$opeelApp = new opeelAppLe();
	$opeelApp->register();

	// activation
	register_activation_hook( __FILE__, array( $opeelApp, 'activate' ) );

	// deactivation
	require_once plugin_dir_path( __FILE__ ) . 'inc/opeel-app-deactivate.php';
	register_deactivation_hook( __FILE__, array( 'opeelAppDeactivate', 'deactivate' ) );
  
	//Use hooks from parent plugin.  
        add_shortcode('opeelApp', array( $opeelApp ,'start_up') );

}
