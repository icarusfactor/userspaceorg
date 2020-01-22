<?php
/**
 * Plugin Name: Hack for turning off LOADING screen across all browsers.
 * Plugin URI: http://www.userspace.org
 * Description: Hack for turning off loading screen.
 * Version: 2.0.0
 * Author: Daniel Yount
 * Author URI: https://www.userspace.org
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: wp-loadingoff
 *
**/
if ( ! defined( 'WPINC' ) ) { die; }

/* Init Hook */
add_action( 'init', 'wp_loadingoff_init', 10 );

/**
 * Init Hook to Register Shortcode.
 * @since 1.0.0
 */
function wp_loadingoff_init(){

	/* Register Shortcode */
	add_shortcode( 'loadingoff', 'wp_loadingoff_shortcode_callback' );

}

function wp_loadingoff_shortcode_callback(){

	/* Output script to remove lading screen in 1.5 seconds */
	return "<script> $('#loading').delay(1000).modal('hide'); </script>";
}

