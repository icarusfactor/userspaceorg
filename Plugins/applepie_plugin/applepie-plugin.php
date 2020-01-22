<?php
/**
 * @package  Applepie plugin
 */
/*
Plugin Name: AppLePie Plugin
Plugin URI: http://userspace.org
Description: Parent plugin for custom RSS feeds that use Simplepie and a custom css display box. 
Version: 0.9.6
Author: Daniel Yount aka Icarus[factor]
Author URI: http://userspace.org
License: GPLv2 or later
Text Domain: applepie-plugin
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

Copyright 2019 Daniel Yount aka Icarus[factor], Inc.
*/

defined( 'ABSPATH' ) or die( 'Hey, what are you doing here? You silly human!' );

if ( !class_exists( 'AppLePiePlugin' ) ) {


// Include SimplePie                    
//Test for older version of WP wont actualy do the once thing. 
if (!class_exists('SimplePie_Autoloader')) {
   include_once('inc/simplepie/autoloader.php');
}
if (!class_exists('idna_convert')) {
   include_once('inc/simplepie/idn/idna_convert.class.php');
}




	class AppLePiePlugin
	{

		public $plugin;

		function __construct() {
			$this->plugin = plugin_basename( __FILE__ );
		}

		function register() {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

			add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );

			add_filter( "plugin_action_links_$this->plugin", array( $this, 'settings_link' ) );
		}

		public function settings_link( $links ) {
			$settings_link = '<a href="admin.php?page=applepie_plugin">Settings</a>';
			array_push( $links, $settings_link );
			return $links;
		}

		public function add_admin_pages() {
			add_menu_page( 'App Le Pie Plugin', 'Applepie', 'manage_options', 'applepie_plugin', array( $this, 'admin_index' ), 'dashicons-store', 110 );
		}

		public function admin_index() {
			require_once plugin_dir_path( __FILE__ ) . 'templates/admin.php';
		}

		protected function create_post_type() {
			add_action( 'init', array( $this, 'custom_post_type' ) );
		}


		function enqueue() {
			wp_enqueue_style( 'applepiepluginstyle', plugins_url( '/assets/applepiestyle11.css', __FILE__ ) );
			//wp_enqueue_script( 'applepiepluginscript', plugins_url( '/assets/applepiescript.js', __FILE__ ) );
		}

		function activate() {
			require_once plugin_dir_path( __FILE__ ) . 'inc/applepie-plugin-activate.php';
			AppLePiePluginActivate::activate();
		}

                //Send RSS feed: Send Max Number of Items to get: Get Title : Get Date : Get Content
		function feed_generate_process( $rss_feed_url , $max_items=1 , $rss_media="APTEXT", $rss_id="TXTFEED" , $rss_height="260" ) {

                      $permrss = [];
                      $titlerss = [];
                      $daterss  = [];
                      $contentrss = [];
                      $i=0;
                      $atts= []; 
                      

                     // Create a new instance of the SimplePie object
                     $feed = new SimplePie();                     
                     // This needs to be overrode with child Applepie plugin SimplePie
                     $feed->set_feed_url( $rss_feed_url);
                     // Trigger force-feed
                     $feed->force_feed(true);
		     $feed->enable_cache(true); 
                     $feed->set_cache_location(plugin_dir_path( __FILE__ ).'cache');
		     $feed->set_cache_duration(10800); //3 hours  3600 seconds = 1 hour
                     $success = $feed->init();
                     $feed->handle_content_type();
		    
		      if( $rss_media == "APVIDEO") 
			      {
		               global $wp_embed;
			       $atts = shortcode_atts(
		               array( 'height' => $rss_height,   
                                    ), $atts, $rss_id ); 
		              }

		      //Only one url image exist in an RSS 2.0 stream , no need to loop more than 1 time, return after process.
		      //daterss will return an empty array since its not used. 
		      if( $rss_media == "APIMG") 
		              {
			              $permrss[$i]     = $feed->get_image_link();                         
				      $titlerss[$i]    = $feed->get_image_title();    
			              $contentrss[$i]  = $feed->get_image_url();
                              return array( $permrss , $titlerss , $daterss , $contentrss ); 
		              }


                     
                     if ($success): 
                       foreach($feed->get_items() as $item): 
                       $i++;if ($i >= $max_items ) {break;}                 

                       if ( !empty( $item->get_title() ) )
                       {                      
                        if ($item->get_permalink())
                         $permrss[$i]     = $item->get_permalink();
                         $titlerss[$i]    = $item->get_title();                        
			 $daterss[$i]     = $item->get_date('j M Y, g:i a');                         
			 if( $rss_media == "APSUMMARY" ) { $contentrss[$i]  = $item->get_description(); }
			 if( $rss_media == "APVIDEO") { $contentrss[$i]  = $wp_embed->shortcode( $atts , $item->get_permalink());}
			 if( $rss_media == "APTEXT" ) { $contentrss[$i]  = $item->get_content(); }
                       }                       
                      endforeach;                      
                      endif; 
 
                 return array( $permrss , $titlerss , $daterss , $contentrss ); 
                  }

		//get Header  
		function feed_generate_header() {
                     //For use with Product Theme 
                     //Need to make look based loosely style and more on css hooks
                     //so we can leave style for actual theme.  
                     $Content = "    <div id=\"widget-section\" style=\"padding-left: 5px;padding-right: 5px;border-radius: 5px;border: 6px solid lightblue;background-color: #add8e6;border-top: 12px solid lightblue;\" >";

                     $Content .= "   <div id=\"rssapp\">"; 
		     $Content .= "   <div id=\"rsshead\" >";     
                     return $Content;
		}

                function feed_generate_headtofoot( $rss_media="APTEXT" ) {
                     $Content = "</div><div id=\"rsscontent\" class=\"".$rss_media."\"  >";
                     return $Content;
                }

	        //get Footer	
		function feed_generate_footer() {
                     $Content = "</div></div>";
                     $Content .= "</div>"; //for wordpress theme Product box
                     return $Content;
		}

//Only for testing
            function feed_generate_applepie() {

                     // Include SimplePie                    
                     include_once('inc/simplepie/autoloader.php');
                     include_once('inc/simplepie/idn/idna_convert.class.php');

                     // Create a new instance of the SimplePie object
                    $feed = new SimplePie();

                     //$feed->force_fsockopen(true);

                     // This needs to be overrode with child Applepie plugin SimplePie
                     $feed->set_feed_url("http://distrowatch.com/news/dw.xml");

                     // Trigger force-feed
                     $feed->force_feed(true);

		     $feed->enable_cache(true); 
                     $feed->set_cache_location(plugin_dir_path( __FILE__ ).'cache');
		     $feed->set_cache_duration(18000); //5 hours  3600 seconds = 1 hour


                    // Initialize the whole SimplePie object.  Read the feed, process it, parse it, cache it, and
                     // all that other good stuff.  The feed's information will not be available to SimplePie before
                     // this is called.
                     $success = $feed->init();

                     // We'll make sure that the right content type and character encoding gets set automatically.
                     // This function will grab the proper character encoding, as well as set the content type to text/html.
                    $feed->handle_content_type();


                     //For use with Product Theme 
                     //Need to make look based loosely style and more on css hooks
                    //so we can leave style for actual theme.  
                    $Content = "    <div id=\"widget-section\" style=\"padding-left: 10px;padding-right: 10px;border-radius: 5px;border: 6px solid lightblue;background-color: #fafafa;border-top: 12px solid lightblue;\" >";

                     $Content .= "   <div id=\"content\">"; 
                     $Content .= "     <div id=\"sp_results\">";
                     //$Content .= "<!-- As long as the feed has data to work with... -->";
                     if ($success): 

                       $Content .= "<!-- Let's begin looping through each individual news item in the feed. Break if count 2 -->";
                       $i=0;
                       foreach($feed->get_items() as $item): 
                      $i++;if ($i >= 2 ) {break;}

                       $Content .= "<!-- Filter Starts Here -->";
                  
                       if ( !empty( $item->get_title() ) ) {                      
                        $Content .= "<div class=\"chunk\">";
                        $Content .= "<!-- If the item has a permalink back to the original post (which 99% of them do), link the item's title to it. -->";
                        $Content .='<h4><a href="http://www.distrowatch.com">Distrowatch:</a><hr class="titleline">';
                        if ($item->get_permalink())
                         $Content .= '<a href="'.$item->get_permalink().'">';

                        $Content .= $item->get_title();

                        if ($item->get_permalink())
                         $Content .= '</a>';

                        $Content .= '</h4><h5></br>';

                        $Content .= "<span class=\"footnote\">";
                        $Content .= $item->get_date('j M Y, g:i a');
                        $Content .= "</span></h5>";

			$Content .= "<!-- Display the item's primary content. .-->";
                        if ($item->get_content())
                         $Content .=  $item->get_content(); 

                        if ($item->get_description())
                         $Content .=  $item->get_description(); 

                       
                        $Content .= "</div>";
                       }
                       $Content .= "<!-- Filter Stops Here -->";
                       $Content .= "<!-- Stop looping through each item once we've gone through all of them. -->";
                       endforeach; 
                       $Content .= "<!-- From here on, we're no longer using data from the feed. -->";
                       endif;
                       $Content .= "</div>";
                       $Content .= "</div>";                     
                       $Content .= "</div>"; //for wordpress theme Product box

                     return $Content;
               } //end of applepie feed generate



	}

	$ApplepiePlugin = new AppLePiePlugin();
	$ApplepiePlugin->register();

	// activation
	register_activation_hook( __FILE__, array( $ApplepiePlugin, 'activate' ) );

	// deactivation
	require_once plugin_dir_path( __FILE__ ) . 'inc/applepie-plugin-deactivate.php';
	register_deactivation_hook( __FILE__, array( 'AppLePiePluginDeactivate', 'deactivate' ) );

        //Only for testing
        //add_shortcode('ApplepieNews', array( $ApplepiePlugin ,'feed_generate_applepie') );

}
