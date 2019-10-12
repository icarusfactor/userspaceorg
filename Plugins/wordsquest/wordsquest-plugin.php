<?php
/**
 * @package  WordsQuestPlugin
 */
/*
Plugin Name: Words Quest Plugin
Plugin URI: http://userspace.org
Description: Words Quest is a basic HTML5/CSS3 Word Search program.
Version: 0.9.5
Author: Daniel Yount
Author URI: http://userspace.org
License: GPLv3 or later
Text Domain: wordsquest-plugin
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

Copyleft 2019 Daniel Yount aka [Icarus]factor
*/

defined( 'ABSPATH' ) or die( 'Hey, what are you doing here? You silly human!' );

if ( !class_exists( 'WordsQuestPlugin' ) ) {

	class WordsQuestPlugin
	{

		public $plugin;

		function __construct() {
			$this->plugin = plugin_basename( __FILE__ );
		}

		function register() {
			add_action( 'wp_enqueue_scripts', array( $this, 'theme_enqueue' ) );

			add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );

			add_filter( "plugin_action_links_$this->plugin", array( $this, 'settings_link' ) );
		}

		public function settings_link( $links ) {
			$settings_link = '<a href="admin.php?page=wordsquest_plugin">Settings</a>';
			array_push( $links, $settings_link );
			return $links;
		}

		public function add_admin_pages() {
			add_menu_page( 'WordsQuest Plugin', 'WordsQuest', 'manage_options', 'wordsquest_plugin', array( $this, 'admin_index' ), 'dashicons-store', 110 );
		}

		public function admin_index() {
			require_once plugin_dir_path( __FILE__ ) . 'templates/admin.php';
		}


		function theme_enqueue() {
			// enqueue all our scripts
			wp_enqueue_style( 'wq_pluginstyle', plugins_url( 'assets/wqgc_1.0.1.css', __FILE__ ), null, '1.0.1' );
			wp_enqueue_script( 'wq_pluginscript_footer', plugins_url( 'assets/clickcheck.js', __FILE__ ),null,'0.9.0', true );
		}

		function activate() {
			require_once plugin_dir_path( __FILE__ ) . 'inc/wordsquest-plugin-activate.php';
			WordsQuestPluginActivate::activate();
		}


		function thegame() {

                        //Words Quest word class
	                require_once plugin_dir_path( __FILE__ ) . 'inc/class.word.php';
	                // Words Quest Grid class
	                require_once plugin_dir_path( __FILE__ ) . 'inc/wordsquest-plugin-grid.php';
			//The main wordsearch layout and search items will be generated here.  
	                $grid=new Grid();
			$grid->gen();
			//original for standalone
			//$wordbox_start="<DIV STYLE=\"padding: 4px;font-family: monospace;font-weight: 600;font-size: 1.0em;background-image: linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));position: fixed;top: 120;left: 350;width: 155px;border: 3px solid #5555FF;\">";

			//$wordbox_start="<DIV style=\"padding: 4px;font-family: monospace;font-weight: 600;font-size: 1.0em;background-image: linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));position: relative;top: -340px;left: 5px;width: 190px;border: 3px solid #5555FF;\" >";
			$wordbox_start="<DIV id=\"wq-wordbox\" style=\"padding: 4px;font-weight: 600;font-size: 1.0em;background-image: linear-gradient(rgba(255,255,255,.5), rgba(255,255,255,0));position: relative;top: -340px;left: 5px;width: 190px;border: 3px solid #5555FF;\" >";
                        $wordbox_end="</DIV>";

			$gridtop="<DIV ID=\"thegame\" STYLE=\"width: 500px;height: 350px;\">";
			$gridbottom="</DIV>";

                        //start rendering game here.
			$Content = $gridtop;
			$Content .= $grid->render();
			$Content .= $gridbottom;
			$Content .= $wordbox_start;
			//$Content .= "Words to find (".$grid->getNbWords().")<BR/>\n";
			$Content .= "<span style=\"color: #5555E5;\"  >■Words to find■</span><BR/>\n";
			$Content .= $grid->renderWordsList("<BR/>\n");
			$Content .= $wordbox_end;
			$Content .= $grid->answerList();
			
                        return $Content;
		}
  	  }

	$wordsquestPlugin = new WordsQuestPlugin();
	$wordsquestPlugin->register();

	// activation
	register_activation_hook( __FILE__, array( $wordsquestPlugin, 'activate' ) );

	// deactivation
	require_once plugin_dir_path( __FILE__ ) . 'inc/wordsquest-plugin-deactivate.php';
	register_deactivation_hook( __FILE__, array( 'WordsQuestPluginDeactivate', 'deactivate' ) );

        add_shortcode( "wordsquest"  , array( $wordsquestPlugin , 'thegame' ) );

}
