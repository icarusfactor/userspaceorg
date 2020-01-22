<?php
/**
 * @package RSS Raw Seed Feed AppLe 
 */
/*
Plugin Name: RSS RAW Seed Feed App 
Plugin URI: http://userspace.org
Description: This app gathers RSS feed data from selected site and proitizes sites has no presentation , just update cache and requires the AppLepie project plugin.  
Version: 0.42.0
Author: Daniel Yount IcarusFactor
Author URI: http://userspace.org
License: GPLv2 or later
Text Domain: rseedfeed-appLe
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

if ( !class_exists( 'rseedfeedAppLe' ) && class_exists( 'AppLePiePlugin' )  ) {

	class rseedfeedAppLe
	{

		public $plugin;

		function __construct() {
			$this->plugin = plugin_basename( __FILE__ );
		}

		function register() {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
                        //Hook our function , ap_create_rss_cache(), into the action ap_create_hourly_rss_cache
			add_action( 'ap_create_hourly_rss_cache', array( $this  , 'ap_create_rss_cache' ) );
                	//error_log("NOTICE: REGISTER ADD_ACTION"); 

		}

		function ap_create_rss_cache(){
		 //Run code to create backup.
                 //Launch page and put RAW RSS shortcode in it to update cache and update datetime. 
                 //PAGE - UPDATECACHE
                 //POST with all of 
                 // $result = empty( get_post( 2538 ) ); 
                 do_shortcode( '[rseedApp section="Linux News Feeds" media="APTEXT"]' );
                 do_shortcode( '[rseedApp section="Linux News Videos" media="APVIDEO"]' );
		 do_shortcode( '[rseedApp section="AUDIO FEED SOURCES" media="APAUDIO"]' );
                 do_shortcode( '[rseedApp section="INFOSEC SOURCES" media="APTEXT"]' );
                 //error_log("NOTICE: AP_CREATE_RSS_CACHE " ); 
                 return 1;
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
			rseedfeedAppActivate::activate();
                        

		}

                // Place modification scripts here for Applepie plugin. Hardcoded to first item only currently.


                 public static function ap_create_hourly_rss_caching_schedule(){
  			//Use wp_next_scheduled to check if the event is already scheduled
			$timestamp = wp_next_scheduled( 'ap_create_hourly_rss_cache' );

  			//If $timestamp == false schedule hourly rss caching since it hasn't been done previously
 			if( $timestamp == false ){
    			//Schedule the event for right now, then to repeat daily using the hook 'wi_create_daily_backup'
    			wp_schedule_event( time(), 'hourly', 'ap_create_hourly_rss_cache' );
                	//error_log("NOTICE: AP_CREATE_HOURLY_RSS_CACHE"); 
  			}

                	//error_log("NOTICE: AP_CREATE_HOURLY_RSS_CACHING_SCHEDULE"); 
			}

                 //Loop this function until it runs out. 
                 //Need to put this in its own class at some point.
                 function  priority_cast( $id , $section ) {
                           global $wpdb;                     
                           
                           $rqFEED="SELECT @rn := @rn+1 AS priority, id, section, site, rss, url, catagory, enable, last_post_date FROM ap_section_feeds CROSS JOIN (SELECT @rn := 0) AS v1 HAVING priority=".$id." AND section=\"".$section."\" ORDER BY last_post_date DESC;";  

                           $results=$wpdb->get_row( $rqFEED , ARRAY_A );
                           //error_log("PRIORITY_CAST INSIDE: VAR DUMP:".var_dump($results) ); 
                           return $results;
                 }
 
                 // When find that current date is changed to newer date update FEEd data.
                 function  update_timestamp( $id , $newtimestamp ) {
                           global $wpdb;
                           $fdate = strtotime( $newtimestamp );
                           $fmtdate = date('Y-m-d H:m:s', $fdate );
                           $udFEED="UPDATE `ap_section_feeds` SET `last_post_date` = '".$fmtdate."' WHERE id=".$id.";";
                           //$udFEED="UPDATE `ap_section_feeds` SET `last_post_date` = '".$newtimestamp."' WHERE id=".$id.";";
                           $result = $wpdb->query( $udFEED );
                           //error_log("UPDATE TIMESTAMP: ".$id.":".$newtimestamp." VAR DUMP:".var_dump($result) ); 
                           //error_log("UPDATE TIMESTAMP: ".$id.":  ".$newtimestamp." , ".$udFEED  ); 
                           }



		function  start_up( $atts ) {
                         $a2b=[[]];
                        // Working on APTEXT 
                        $a = shortcode_atts( array(
                         'section' => 'Linux News Feeds',
                         'media' => 'APTEXT'
                        ), $atts );

                        //error_log("START_UP DUMP:".print_r( $a2b , true  ));       
                        // Loop DB items , grab feed data to update cache and timestamp and loop check so it does not go wild.
                        $IDnotempty=1;
                        $loopID=1;  
                        while ( $IDnotempty == 1 || $loopID <= 100 ) {
                        //Grab RSS feed data and priority from the ID and section name.
                        // This will return one row with the id priority based on date.
                        $a2b=$this->priority_cast( $loopID , $a['section'] );
                        // Check variables and place them in error log.
                        //error_log("PRIORITY_CAST ".$loopID." ".$a['section']." DUMP:".print_r( $a2b , true  ));       
                        //Test if empty , if so end 
                        if( empty($a2b['rss']) ) { $IDnotempty=0;$loopID++;break;} 
                        //rss feed data exist , let continue.
			$ApplepiePlugin = new AppLePiePlugin();
			list( $permrss, $titlerss , $daterss , $contentrss ) = $ApplepiePlugin->feed_generate_process( $a2b['rss'] , 2 ,$a['media'] ,$a2b['id'] );
                        //error_log("APPLEPIE LIST: ".print_r($permrss,true)." ".print_r($titlerss,true)." ".print_r($daterss,true)." ".print_r($contentrss,true) ); 
		        //Error check return of feed data.
                        if( empty( $permrss ) || empty( $titlerss ) ){
                                $dat = array();
                                if( empty( $permrss )) { $dat[0] = 1; } 
                                if( empty( $titlerss )) { $dat[1] = 1; } 
                                if( empty( $daterss )) { $dat[2] = 1; } 
                                if( empty( $contentrss )) { $dat[3] = 1; } 
				error_log("WARNING:Some RSS data from ".$a2b['rss']." is not being retreived ".$dat[0].":".$dat[1].":".$dat[2].":".$dat[3] , 0);
                                $Content = "NO DATA FROM ".$a2b['site'];
				return $Content;
                                }
                         // No check, just update, so priority setting can be aligned with current one.                              
                         $this->update_timestamp( $a2b['id'] , $daterss[1] );
                         // Loop until content is empty
                         $loopID++; 
                         }
                         // If gotten to this point cache is filled for section and we can return.
                         return "RSS CACHE/DATE UPDATE FINISHED";
                        

		}  

	
	
	}

	$rseedfeedApp = new rseedfeedAppLe();
	$rseedfeedApp->register();

	// activation
	register_activation_hook( __FILE__, array( $rseedfeedApp, 'activate' ) );

        //On plugin activation schedule our hourly database and rss cache datetime update 
        register_activation_hook( __FILE__, array( $rseedfeedApp, 'ap_create_hourly_rss_caching_schedule' ) );

	// deactivation
	require_once plugin_dir_path( __FILE__ ) . 'inc/feed-app-deactivate.php';
	register_deactivation_hook( __FILE__, array( 'rseedfeedAppDeactivate', 'deactivate' ) );
  
	//Use hooks from parent plugin.  
        add_shortcode('rseedApp', array( $rseedfeedApp ,'start_up') );

}
