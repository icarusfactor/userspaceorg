<?php
  $TEMPLATE_PATH = parse_url(get_template_directory_uri(), PHP_URL_PATH);
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#000000">
   
    <link rel="pingback" href="http://userspace.org/xmlrpc.php" />

    <?php wp_head(); ?>


</head>
    <body class="home page-template-default page page-id-53 animsition" >       

        <!-- is_front_page check was here.  -->
<div id="topspot" class="crosshatch"  > 

<div id="vanish" >
<!-- HOME LOGO BUTTON  -->
<a href="http://userspace.org" >
<div id="usohome" ></div>
</a>
</div>

<header id="masthead" class="site-header"  role="banner"  >        

<div class="btn-menu"><?php wp_nav_menu( array( 'theme_location' => 'social' ) ); ?></div>

<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Masthead Text") ) : ?><?php endif;?>

  <div class="bannerbox" >
  <div class="bannertext"></div>
  </div>

</header>
</div>


	<div id="slider_cover" >
        <div style="height: 25px;width: 100%;">
        <div id="vanish"><div class="marktop" ></div>

<nav id="button-container-work">

<?php
    //Work Dynamic Menu List
    $menuParameters = array(
      'theme_location' => 'work',
      'container'       => false,
      'echo'            => false,
      'depth'           => 0,
    );  
    // Convert menu string into only anchor tags.
    echo strip_tags(wp_nav_menu( $menuParameters ), '<a>' );
?>

</nav>
</div>
</div>

<script src="http://userspace.org/wp-content/themes/curator/js/init_works.js"></script>
 <!-- Carousel short code goes here.  -->
     <div id="works">
     <?php  echo str_repeat('&nbsp;</BR>', 6); //empty fill area  ?>
     </div>
 <!-- Carousel short code goes here.  -->

	<div class="product-banner-area"> 

<div class="product-banner-area"> 
				
	<div class="header-background">

<div class="marktopbanner" ></div>

		<section class="header-content">
   
			<div class="container">
		<section id="leftside-attraction" class="row">
                    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Leftside Attraction") ) : ?><?php endif;?>
                </section>

				<section id="main-attraction" class="row align-center">

                                <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Main Attraction") ) : ?><?php endif;?>
                             
                   		</section>

		<section id="rightside-attraction" class="row">
                    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Rightside Attraction") ) : ?><?php endif;?>
                </section>

		</div>
         </section> 
<div id="vanish">
<div class="markbottom" ></div>
<nav id="button-container">

<?php
    //Collection Dynamic Menu List
    $menuParameters = array(
      'theme_location' => 'collection',
      'container'       => false,
      'echo'            => false,
      'depth'           => 0,
    );
    // Convert menu string into only anchor tags.
    echo strip_tags(wp_nav_menu( $menuParameters ), '<a>' );
    
?>

</nav>
</div>
</div>
    <!-- End of if front page -->

	<section id="content" class="page-wrap">
		<div class="content-wrapper halftonecircles ">                        
			<div class="container">    


      

<!-- HACK:NEEDS FUTURE FIX: Init run to setup default page to show and to load slide anything jquery script so it works properly  -->
<script src="http://userspace.org/wp-content/themes/curator/js/init4.js"></script>
<?php echo do_shortcode("[slide-anything id='1712']"); ?>


     <div id="collections">
     <?php  echo str_repeat('&nbsp;</BR>', 20); //empty fill area  ?>
     </div>


<?php get_footer(); ?>
