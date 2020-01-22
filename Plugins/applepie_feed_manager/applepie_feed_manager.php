<?php
/**
 * @package  Appleipie Feed Manger Plugin
 */
/*
Plugin Name: Applepie Feed Manager Plugin
Plugin URI: http://userspace.org
Description: Admin Plugin to pull tablepress tables data and parse and insert into ap feeds table that prioritize and display RSS feeds.
Version: 0.3.0
Author: Daniel Yount aka icarus[factor] factorf2@yahoo.com
Author URI: http://userfspace.org
License: GPLv2 or later
Text Domain: ap-feed-manager-plugin
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

if ( !class_exists( 'ApplepieFeedManagerPlugin' ) ) {

	class ApplepieFeedManagerPlugin
	{

		public $plugin;

		function __construct() {
			$this->plugin = plugin_basename( __FILE__ );
		}

		function register() {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

			add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );

			add_filter( "plugin_action_links_$this->plugin", array( $this, 'settings_link' ) );

                        add_action( 'wp_ajax_extractprocess', array( $this, 'extractprocess' ) );

		}

		public function settings_link( $links ) {
			$settings_link = '<a href="admin.php?page=applepiefeedmanager_plugin">Settings</a>';
			array_push( $links, $settings_link );
			return $links;
		}

		public function add_admin_pages() {
			add_menu_page( 'apfeedmanager', 'APFM', 'manage_options', 'applepiefeedmanager_plugin', array( $this, 'admin_index' ), 'dashicons-images-alt2', 110 );
		}

		public function admin_index() {
			require_once plugin_dir_path( __FILE__ ) . 'templates/admin.php';
		}

		function enqueue() {
			// enqueue all our scripts
			wp_enqueue_style( 'apfmpluginstyle', plugins_url( '/assets/apfmstyle.css', __FILE__ ) );

			wp_enqueue_style( 'apfmjqueryuicss', plugins_url( '/assets/jquery-ui.min.css', __FILE__ ) );
			wp_enqueue_style( 'apfmjqueryuithemecss', plugins_url( '/assets/jquery-ui.theme.min.css', __FILE__ ) );

			wp_enqueue_script( 'apfmjquery', plugins_url( '/assets/jquery.min.js', __FILE__ ) );
			wp_enqueue_script( 'apfmpopper', plugins_url( '/assets/popper.min.js', __FILE__ ) );
			wp_enqueue_script( 'apfmjqueryboot', plugins_url( '/assets/bootstrap.js', __FILE__ ) );
			wp_enqueue_script( 'apfmmodernizer', plugins_url( '/assets/modernizr-custom.js', __FILE__ ) );

			wp_enqueue_script(  'apfm-script5', plugins_url( '/assets/apfmscript5.js', __FILE__ ) );
                        wp_localize_script( 'apfm-script5', 'apfm_ajax', array( 'ajax_url' => admin_url('admin-ajax.php')) );
                        

		}

		function activate() {
			require_once plugin_dir_path( __FILE__ ) . 'inc/applepie-feed-manager-plugin-activate.php';
			ApplepieFeedManagerPluginActivate::activate();
		}


                function extractprocess() {
                         $btn_id = $_POST['post_id']; 
                         $sel = substr( $btn_id , 3, 1);
                         $act = substr( $btn_id , 4, 1);
                         $selected = intval($sel); 
                         $selected--;
                         // APFM rss ctrl class
			 require_once plugin_dir_path( __FILE__ ) . 'inc/class.apfm.php';

                         $apfmrc=new APFMrssctrl();
                         $section_names = $apfmrc->section_names();
                         $section_content = $apfmrc->section_content();
                         $section_items = $apfmrc->section_items( $section_content[$selected] );

                         //$Content .= $act;
                         //$Content .= "<BR>";
                         //$Content .= $section_names[$selected];
                         $Content = "<PRE>";
                         $size_it = count( $section_items );

                         if( $act == "v" ) { 

                           $Content .= "VIEWING ".$size_it." ITEMS OF ".$section_names[$selected];
                           $Content .= "<BR>";
                                                 
                           $cnt=0; 
                           while($cnt <= $size_it - 1)
                               { 
                             $Content .= $section_items[$cnt][0];
                             $Content .= $section_items[$cnt][1];
                             $Content .= $section_items[$cnt][3];
                             //$Content .= $section_items[$cnt][4];
                             $Content .= "<BR>";
                             $Content .= $section_items[$cnt][2];
                             $Content .= "<BR>";
                             $Content .= "<BR>";
                             $cnt++; 
                               }
                               } //End of if visual or not.

        if( $act == "p" ) { 
        $apfmrc->push_data( $section_items , $selected );
        //error_log( "push_data".$selected );
        $Content .= "PUSHING ".$size_it." ITEMS OF ".$section_names[$selected]." TO DATABASE";
                          } 

        if( $act == "d" ) { 
        $apfmrc->clear_push_data( $selected );
        //error_log( "clear_push_data".$selected );
        $Content .= "DELETING ".$size_it." ITEMS OF ".$section_names[$selected]." FROM DATABASE";
                          } 
                         $Content .= "</PRE>";

                         echo $Content;
                         // Always die in functions echoing Ajax content
                         die();
                } 

	}

	$applepiefeedmanagerPlugin = new ApplepieFeedManagerPlugin();
	$applepiefeedmanagerPlugin->register();

	// activation
	register_activation_hook( __FILE__, array( $applepiefeedmanagerPlugin, 'activate' ) );

	// deactivation
	require_once plugin_dir_path( __FILE__ ) . 'inc/applepie-feed-manager-plugin-deactivate.php';
	register_deactivation_hook( __FILE__, array( 'ApplepieFeedManagerPluginDeactivate', 'deactivate' ) );

}
