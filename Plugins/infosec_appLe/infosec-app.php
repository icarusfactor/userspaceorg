<?php
/**
 * @package Infosec Vulnerability Database  AppLe 
 */
/*
Plugin Name: Infosec Vulnerability RSS Feed App 
Plugin URI: http://userspace.org
Description: This app gathers RSS feed data for software vulnerabilities and requires the AppLepie project plugin.  
Version: 0.8.15
Author: Daniel Yount IcarusFactor
Author URI: http://userspace.org
License: GPLv2 or later
Text Domain: infosec-appLe

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

if ( !class_exists( 'infosecAppLe' ) && class_exists( 'AppLePiePlugin' )  ) {

	class infosecAppLe
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
			//wp_enqueue_style( 'infosecappstyle', plugins_url( '/assets/infosecappstyle.css', __FILE__ ) );
			//wp_enqueue_script( 'infosecappscript', plugins_url( '/assets/infosecappscript.js', __FILE__ ) );
		}

		function activate() {


                        // Require parent plugin
                        if ( ! is_plugin_active( 'applepie_plugin/applepie-plugin.php' ) and current_user_can( 'activate_plugins' ) )
                        {
                        // Stop activation redirect and show error
                        wp_die('Sorry, but this plugin requires the Parent Plugin to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
                        }

			require_once plugin_dir_path( __FILE__ ) . 'inc/infosec-app-activate.php';
			infosecAppActivate::activate();
		}

                // Place modification scripts here for Applepie plugin. Hardcoded to first item only currently.

		function  start_up( $atts) {

		         $a = shortcode_atts( array(
                         'name' => 'Wordpress',
                         'url' => 'http://www.wordpress.com',
                         'rss' => 'https://en.blog.wordpress.com/feed/',
                         'count' => '5',
                         'media' => 'APTEXT',
                         'id'    => 'none',
                        ), $atts );
	 


                   
		        $ApplepiePlugin = new AppLePiePlugin();
                        $Content = "<!-- Filter Starts Here -->";
                        $Content .= $ApplepiePlugin->feed_generate_header();
                        //$Content .= "<div style=\"position: relative; width: 0; height: 0\"><div id=\"content-new-packages\"></div></div>";

                        list( $permrss, $titlerss , $daterss , $contentrss ) = $ApplepiePlugin->feed_generate_process($a['rss'] , $a['count'] );

                        $Content .= "<span style=\"font-size: 13px\">".$a['name']."</span></br></br>"; 

                          $Content .= $ApplepiePlugin->feed_generate_headtofoot();

                          $Content .= '<style type="text/css">';
                          $Content .= '.tg  {border-collapse:collapse;border-spacing:0;border-color:#aabcfe;}';
                          $Content .= '.tg td{font-family:Arial, sans-serif;font-size:14px;padding:9px 9px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-top-width:1px;border-bottom-width:1px;border-color:#aabcfe;color:#669;background-color:#e8edff;}';
                          $Content .= '.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:9px 9px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-top-width:1px;border-bottom-width:1px;border-color:#aabcfe;color:#039;background-color:#b9c9fe;}';
                          $Content .= '.tg .tg-hmp3{background-color:#D2E4FC;text-align:left;vertical-align:top}';
                          $Content .= '.tg .tg-0pky{border-color:inherit;text-align:left;vertical-align:top}';
                          $Content .= '.tg .tg-0lax{text-align:left;vertical-align:top}';
                          $Content .= '</style>';

                          $Content .= '<table class="tg">';


                        $Content .= "<!-- Let's begin looping through each individual package item in the feed. -->";
                        $i=0;
                        foreach($titlerss as $item): 

                        $i++;if ($i >= 7 ) {break;}

                        $Content .= "<!-- Filter Starts Here -->";

                           if($i&1){
                                    $Content .= '<tr><td class="tg-hmp3">';
                                   }else {
                                    $Content .= '<tr><td class="tg-0lax">';
                                   }

                          if ( !empty( $titlerss[$i] ) ) {                      
                                   $Content .= "<div class=\"chunk\">";
                                   $Content .= "<!-- If the item has a permalink back to the original post (which 99% of them do) -->";

                          if ($permrss[$i])
                                   $Content .= '<a6><a href="'.$permrss[$i].'">';
                                   $Content .= $titlerss[$i];

                          if ($permrss[$i])
                                   $Content .= '</a></BR>';
                                   $Content .= "</h6>";
                                   $Content .= "<!-- Display the item's primary content. .-->";
                                   $Content .=  $contentrss[$i]; 
                                   $Content .= "</div>";
                                   $Content .= '</td></tr>';
                                                           }
                                   $Content .= "<!-- Filter Stops Here -->";

                                   $Content .= "<!-- Stop looping through each item once we've gone through all of them. -->";
                     endforeach; 
                                  $Content .= "</table>";
                                  $Content .= "<!-- From here on, we're no longer using data from the feed. -->";

                                  $Content .= $ApplepiePlugin->feed_generate_footer();

                    return $Content;



		}  

	
	
	}

	$infosecApp = new infosecAppLe();
	$infosecApp->register();

	// activation
	register_activation_hook( __FILE__, array( $infosecApp, 'activate' ) );

	// deactivation
	require_once plugin_dir_path( __FILE__ ) . 'inc/infosec-app-deactivate.php';
	register_deactivation_hook( __FILE__, array( 'infosecAppDeactivate', 'deactivate' ) );
  
	//Use hooks from parent plugin.  
        add_shortcode('infosecApp', array( $infosecApp ,'start_up') );

}
