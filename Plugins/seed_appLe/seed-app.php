<?php
/**
 * @package RSS Seed Feed AppLe 
 */
/*
Plugin Name: RSS Seed Feed App 
Plugin URI: http://userspace.org
Description: This app gathers RSS feed data from selected site and requires the AppLepie project plugin.  
Version: 0.9.11
Author: Daniel Yount IcarusFactor
Author URI: http://userspace.org
License: GPLv2 or later
Text Domain: seedfeed-appLe
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

if ( !class_exists( 'seedfeedAppLe' ) && class_exists( 'AppLePiePlugin' )  ) {

	class seedfeedAppLe
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
			wp_enqueue_style( 'seedfeedappstyle', plugins_url( '/assets/feedappstyle.css', __FILE__ ) );
			wp_enqueue_script( 'seedfeedappscript', plugins_url( '/assets/feedappscript.js', __FILE__ ) );
		}

		function activate() {


                        // Require parent plugin
                        if ( ! is_plugin_active( 'applepie_plugin/applepie-plugin.php' ) and current_user_can( 'activate_plugins' ) )
                        {
                        // Stop activation redirect and show error
                        wp_die('Sorry, but this plugin requires the Parent Plugin to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
                        }

			require_once plugin_dir_path( __FILE__ ) . 'inc/feed-app-activate.php';
			seedfeedAppActivate::activate();
		}

                // Place modification scripts here for Applepie plugin. Hardcoded to first item only currently.

		function  start_up( $atts ) {

                        $a = shortcode_atts( array(
                         'name' => 'Wordpress',
                         'url' => 'http://www.wordpress.com',
		         'rss' => 'https://en.blog.wordpress.com/feed/',
			 'count' => '1',
                         'media' => 'APTEXT',
			 'id'    => 'none',
                        ), $atts );


			$ApplepiePlugin = new AppLePiePlugin();
			$Content = $ApplepiePlugin->feed_generate_header();
			list( $permrss, $titlerss , $daterss , $contentrss ) = $ApplepiePlugin->feed_generate_process( $a['rss']  ,intval($a['count']), $a['media'],$a['id'] );
                       
			//Error check
			if( empty( $permrss ) || empty( $titlerss ) || empty( $daterss ) || empty( $contentrss )  ){
                                $dat = array();
				if( empty( $permrss ) { $dat[0] = 1 } 
				if( empty( $titlerss ) { $dat[1] = 1 } 
				if( empty( $daterss ) { $dat[2] = 1 } 
				if( empty( $contentrss ) { $dat[3] = 1 } 
				error_log("WARNING:Some RSS data from ".$a['rss']." is not being retreived ".$dat[0].":".$dat[1].":".$dat[2].":".$dat[3] , 0);	
			        }


                        //the output only uses one item, will make this loop to count in future. 
 
			$Content .= " <span ><a  href=\"".$permrss[1]."\" >".$titlerss[1]."</a></span></br>"; 
			$Content .= " <span style=\"font-size: 9px;text-decoration: underline overline; \" >/// ".$daterss[1]." ///</span>"; 
			$Content .= "<a style=\"font-size: 9px;text-decoration: underline overline; \" href=\"".$a['url']."\" >/// ".$a['name']." ///</a></br>"; 

			$Content .= $ApplepiePlugin->feed_generate_headtofoot( $a['media'] );

			$Content .= "<span style=\"font-size: 12px;\" >".$contentrss[1]."</span>"; 

			$Content .= $ApplepiePlugin->feed_generate_footer();


			return $Content;
		}  

	
	
	}

	$seedfeedApp = new seedfeedAppLe();
	$seedfeedApp->register();

	// activation
	register_activation_hook( __FILE__, array( $seedfeedApp, 'activate' ) );

	// deactivation
	require_once plugin_dir_path( __FILE__ ) . 'inc/feed-app-deactivate.php';
	register_deactivation_hook( __FILE__, array( 'seedfeedAppDeactivate', 'deactivate' ) );
  
	//Use hooks from parent plugin.  
        add_shortcode('seedApp', array( $seedfeedApp ,'start_up') );

}
