<?php
/**
 * @package RSS Organized Seed Feed AppLe 
 */
/*
Plugin Name: RSS Organized Seed Feed App 
Plugin URI: http://userspace.org
Description: This app gathers RSS feed data from selected site and proitizes sites and requires the AppLepie project plugin.  
Version: 0.23.1
Author: Daniel Yount IcarusFactor
Author URI: http://userspace.org
License: GPLv2 or later
Text Domain: oseedfeed-appLe
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

if ( !class_exists( 'oseedfeedAppLe' ) && class_exists( 'AppLePiePlugin' )  ) {

	class oseedfeedAppLe
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
			//wp_enqueue_style( 'seedfeedappstyle', plugins_url( '/assets/feedappstyle.css', __FILE__ ) );
			//wp_enqueue_script( 'seedfeedappscript', plugins_url( '/assets/feedappscript.js', __FILE__ ) );
		}

		function activate() {


                        // Require parent plugin
                        if ( ! is_plugin_active( 'applepie_plugin/applepie-plugin.php' ) and current_user_can( 'activate_plugins' ) )
                        {
                        // Stop activation redirect and show error
                        wp_die('Sorry, but this plugin requires the Parent Plugin to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
                        }

			require_once plugin_dir_path( __FILE__ ) . 'inc/feed-app-activate.php';
			oseedfeedAppActivate::activate();
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
                           //error_log("PRIORITY_CAST INSIDE: ".$rqFEED." VAR DUMP:".var_dump($results) , 3 , "/home/userspa2/logs/userspaceorg_errors.log"); 
                           return $results;
                 }
 
                 // When find that current date is changed to newer date update FEED data.
                 function  update_timestamp( $id , $newtimestamp ) {
                           global $wpdb;
                           $udFEED="UPDATE `ap_section_feeds` SET `last_post_date` = '".$newtimestamp."' WHERE id=".$id    .";";
                           }



		function  start_up( $atts ) {
                         $a2b=[[]];
                        // Working on APTEXT 
                        $a = shortcode_atts( array(
                         'id' => '1',
                         'section' => 'Linux News Feeds',
                         'media' => 'APTEXT'
                        ), $atts );

                        //Grab RSS feed data and priority from the ID and section name.
                        // This will return one row with the id priority based on date.
                        $a2b=$this->priority_cast( $a['id'] , $a['section'] );
                        // Check variables and place them in error log.
                        //error_log("PRIORITY_CAST ".$a['id']." ".$a['section']." DUMP:".print_r( $a2b , true  ) , 3, "/home/userspa2/logs/userspaceorg_errors.log");       
			//error_log("CHECK:RSS data ".$a2b['priority'].":".$a2b['id'].":".$a2b['section'].":".$a2b['site'].":".$a2b['rss'].":".$a2b['url'].":".$a2b['catagory'].":".$a2b['enable'].":".$a2b['last_post_date'] , 3, "/home/userspa2/logs/userspaceorg_errors.log");
			//error_log("CHECK:RSS data ".$a2b['rss'], 0);
                        //each column and places each variable. 
                         
			$ApplepiePlugin = new AppLePiePlugin();
			//list( $permrss, $titlerss , $daterss , $contentrss ) = $ApplepiePlugin->feed_generate_process( $a2b['rss']  , 2 , $a['media'],'id'] );
			//list( $permrss, $titlerss , $daterss , $contentrss ) = $ApplepiePlugin->feed_generate_process('https://www.linuxtoday.com/biglt.rss', 2 ,$a['media'] ,$a2b['id'] );
			list( $permrss, $titlerss , $daterss , $contentrss ) = $ApplepiePlugin->feed_generate_process( $a2b['rss'] , 2 ,$a['media'] ,$a2b['id'] );

                        //error_log("APPLEPIE LIST: ".print_r($permrss,true)." ".print_r($titlerss,true)." ".print_r($daterss,true)." ".print_r($contentrss,true) );       
		        //Error check
                        if( empty( $permrss ) || empty( $titlerss ) ){
                                $dat = array();
                                if( empty( $permrss )) { $dat[0] = 1; } 
                                if( empty( $titlerss )) { $dat[1] = 1; } 
                                if( empty( $daterss )) { $dat[2] = 1; } 
                                if( empty( $contentrss )) { $dat[3] = 1; } 
				//error_log("WARNING:Some RSS data from ".$a2b['rss']." is not being retreived ".$dat[0].":".$dat[1].":".$dat[2].":".$dat[3] , 0);
                                $Content = "NO DATA FROM ".$a2b['site'];
				//$Content .= $ApplepiePlugin->feed_generate_headtofoot( $a['media'] );
			        $Content .= $ApplepiePlugin->feed_generate_footer();
				return $Content;
                                }
	

			$Content = $ApplepiePlugin->feed_generate_header();
                        //the output only uses one item, will make this loop to count in future. 
 
			$Content .= " <span ><a  href=\"".$permrss[1]."\" >".$titlerss[1]."</a></span><br>"; 
			$Content .= " <span style=\"font-size: 9px;text-decoration: underline overline; \" >/// ".$daterss[1]." ///</span>"; 
			$Content .= "<a style=\"font-size: 9px;text-decoration: underline overline; \" href=\"".$a2b['url']."\" >/// ".$a2b['site']." ///</a><br>"; 

			$Content .= $ApplepiePlugin->feed_generate_headtofoot( $a['media'] );

			$Content .= "<span style=\"font-size: 12px;\" >".$contentrss[1]."</span>"; 

			$Content .= $ApplepiePlugin->feed_generate_footer();

			return $Content;
		}  

	
	
	}

	$oseedfeedApp = new oseedfeedAppLe();
	$oseedfeedApp->register();

	// activation
	register_activation_hook( __FILE__, array( $oseedfeedApp, 'activate' ) );

	// deactivation
	require_once plugin_dir_path( __FILE__ ) . 'inc/feed-app-deactivate.php';
	register_deactivation_hook( __FILE__, array( 'oseedfeedAppDeactivate', 'deactivate' ) );
  
	//Use hooks from parent plugin.  
        add_shortcode('oseedApp', array( $oseedfeedApp ,'start_up') );

}
