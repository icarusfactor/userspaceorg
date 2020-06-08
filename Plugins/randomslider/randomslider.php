<?php
/**
 * Plugin Name: Hack to run random position on owl carousel.Hard coded. Will need to make variables later.
 * Plugin URI: http://www.userspace.org
 * Description: Hack for turning off loading screen.
 * Version: 1.0.3
 * Author: Daniel Yount
 * Author URI: https://www.userspace.org
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: wp-loadingoff
 *
**/
if ( ! defined( 'WPINC' ) ) { die; }

/* Init Hook */
add_action( 'init', 'wp_randslider', 10 );

/**
 * Init Hook to Register Shortcode.
 * @since 1.0.0
 */
function wp_randslider(){

	/* Register Shortcode */
	add_shortcode( 'randslider', 'wp_randslider_shortcode_callback' );

}

function wp_randslider_shortcode_callback(){
	/* Output script to remove lading screen in 1.5 seconds */
	return "<script>$( document ).ready(function() { var randnum = Math.floor(Math.random() * 89);$('#id-5').trigger('to.owl.carousel', randnum );console.log( 'Random Init '+randnum );});</script>";
}

