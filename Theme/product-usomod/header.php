<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

	<?php wp_head(); ?>
  </head>

  <body <?php body_class(); ?>>
  <!-- this is for the page transition plugin -->   
  <div id="page-anim-preloader"></div>

  <div id="topspot">
  <?php do_action('product_before_site'); ?>
  	<?php 
	if ( function_exists( 'product_contact_info' ) ) {
		product_contact_info(); 
	}?>
	
    <header id="masthead"  class="site-header  float-header" role="banner">

                <a href="http://userspace.org"><div id="usohome" ></div></a>              
  
		<div class="head-wrap banner-background">
			<div class="container">
				<div class="row">
					<div class="col-md-4 col-sm-6 col-xs-12">
						<?php if ( get_theme_mod('custom_logo') ) : 
							$custom_logo_id = get_theme_mod( 'custom_logo' );
							$logo_src = wp_get_attachment_image_src( $custom_logo_id , 'full' );?>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr ( bloginfo('name') ); ?>"><img class="site-logo" src="<?php echo esc_url( $logo_src[0] ); ?>" /></a>
						<?php else : ?>
							<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php esc_html( bloginfo( 'name' ) ); ?></a></h1>
							<h5 class="site-description"><?php esc_html( bloginfo( 'description' ) ); ?></h5>	        
						<?php endif; ?>
					</div>
					<div class="col-md-8 col-sm-6 col-xs-12 btn-position">
						<div class="btn-menu"></div>
						<nav id="site-navigation" class="site-navigation" role="navigation">
							<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
						</nav><!-- #site-navigation -->
					</div>
				</div>
			</div>
		</div>
    </header>
	
	<?php 
	if ( function_exists( 'product_mobile_header' ) ) {
		//product_mobile_header(); 
	}?>

  </div>

        <?php if ( is_front_page() ) : ?> 
         
        <div id="slider_cover" ><div class="marktop" ></div><?php echo do_shortcode('[carousel_slide id="5"]'); ?></div>
	<div class="product-banner-area"> 

			<?php product_banner_background(); ?>


	<?php endif; ?>
	</div>
	
	<div id="content" class="page-wrap">
		<div class="content-wrapper">                        
			<div class="container">      
                  <span class="loadmedia">&nbsp;L&nbsp;O&nbsp;A&nbsp;D&nbsp;I&nbsp;N&nbsp;G&nbsp;</span>
