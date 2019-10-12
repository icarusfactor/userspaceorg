<?php
/**
 * @package New FOSS Packages AppLe 
 */
/*
Plugin Name: New FOSS Packages RSS Feed App 
Plugin URI: http://userspace.org
Description: This app gathers RSS feed data for New FOSS Packages and requires the AppLepie project plugin.  
Version: 0.8.12
Author: Daniel Yount IcarusFactor
Author URI: http://userspace.org
License: GPLv2 or later
Text Domain: newpackages-appLe

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

if ( !class_exists( 'newpackagesAppLe' ) && class_exists( 'AppLePiePlugin' )  ) {

	class newpackagesAppLe
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
			//wp_enqueue_style( 'newpackagesappstyle', plugins_url( '/assets/newpackagesappstyle.css', __FILE__ ) );
			//wp_enqueue_script( 'newpackagesappscript', plugins_url( '/assets/newpackagesappscript.js', __FILE__ ) );
		}

		function activate() {


                        // Require parent plugin
                        if ( ! is_plugin_active( 'applepie_plugin/applepie-plugin.php' ) and current_user_can( 'activate_plugins' ) )
                        {
                        // Stop activation redirect and show error
                        wp_die('Sorry, but this plugin requires the Parent Plugin to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
                        }

			require_once plugin_dir_path( __FILE__ ) . 'inc/newpackages-app-activate.php';
			newpackagesAppActivate::activate();
		}

                // Place modification scripts here for Applepie plugin. Hardcoded to first item only currently.

		function  start_up() {
			$ApplepiePlugin = new AppLePiePlugin();
			$Content = "<!-- Filter Starts Here -->";
			$Content .= $ApplepiePlugin->feed_generate_header();
			$Content .= "<div style=\"position: relative; width: 0; height: 0\"><div id=\"content-new-packages\"></div></div>";

			list( $permrsslibc, $titlersslibc , $datersslibc , $contentrsslibc ) = $ApplepiePlugin->feed_generate_process("https://savannah.gnu.org/news/atom.php?group=libc", 2 );
			list( $permrss, $titlerss , $daterss , $contentrss ) = $ApplepiePlugin->feed_generate_process("https://distrowatch.com/news/dwp.xml", 6 );

			$Content .= "<span style=\"font-size: 20px\">USERSPACE</span></br></br>"; 
			$Content .= '<span style="font-size: 13px;line-height: 95%;"><a href="'.$permrsslibc[1].'" >['.$titlersslibc[1].']</a></span></br>'; 

			$Content .= '<span style="font-size: 13px;line-height: 95%;"><a href="http://www.linuxfromscratch.org" >[Build Your Own Distro]</a></span>'; 

			  $Content .= $ApplepiePlugin->feed_generate_headtofoot();

			  $Content .= '<style type="text/css">';
			  $Content .= '.tg  {border-collapse:collapse;border-spacing:0;border-color:#aabcfe;}';
			  $Content .= '.tg td{font-size:14px;padding:9px 9px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-top-width:1px;border-bottom-width:1px;border-color:#aabcfe;color:#669;background-color:#e8edff;}';
			  $Content .= '.tg th{font-size:14px;font-weight:normal;padding:9px 9px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-top-width:1px;border-bottom-width:1px;border-color:#aabcfe;color:#039;background-color:#b9c9fe;}';
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

	$newpackagesApp = new newpackagesAppLe();
	$newpackagesApp->register();

	// activation
	register_activation_hook( __FILE__, array( $newpackagesApp, 'activate' ) );

	// deactivation
	require_once plugin_dir_path( __FILE__ ) . 'inc/newpackages-app-deactivate.php';
	register_deactivation_hook( __FILE__, array( 'newpackagesAppDeactivate', 'deactivate' ) );
  
	//Use hooks from parent plugin.  
        add_shortcode('NewpackagesApp', array( $newpackagesApp ,'start_up') );

}
