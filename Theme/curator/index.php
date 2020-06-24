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
<?php echo do_shortcode('
[hyperfetch class="works" initid=0 controlsid="button-container-work" containerid="works"  loadbr=5  submenuid="button-container-item"    ]
[hyperswitch tag="DISTRO" url="/distro" menu="10" ]
[hyperswitch tag="DESKTOP" url="/desktop" menu="10" ]
[hyperswitch tag="FOSS" url="/foss_utils" menu="11"  ]
[/hyperfetch]'); ?>
<?php echo do_shortcode('
[hyperfetch class="item" controlsid="button-container-item" containerid="works"  loadbr=5 ]
[hyperswitch tag="foss_utils" url="/foss_utils"  menu="1" img="http://userspace.org/wp-content/uploads/2020/02/USOutils_icon.svg"  ]
[hyperswitch tag="foss_sys" url="/foss_sys" menu="1" img="http://userspace.org/wp-content/uploads/2020/02/USOsystem_icon.svg" ]
[hyperswitch tag="foss_net" url="/foss_net" menu="1" img="http://userspace.org/wp-content/uploads/2020/02/USOinternet_icon.svg" ]
[hyperswitch tag="foss_graph" url="/foss_graph" menu="1" img="http://userspace.org/wp-content/uploads/2020/02/USOgraphics_icon.svg" ]
[hyperswitch tag="foss_sci" url="/foss_sci" menu="1" img="http://userspace.org/wp-content/uploads/2020/02/USOscience_icon.svg" ]
[hyperswitch tag="foss_dev" url="/foss_dev" menu="1" img="http://userspace.org/wp-content/uploads/2020/02/USOdevelopment_icon.svg" ]
[hyperswitch tag="foss_serv" url="/foss_serv"  menu="1" img="http://userspace.org/wp-content/uploads/2020/02/USOserver_icon.svg" ]
[hyperswitch tag="foss_media" url="/foss_media" menu="1" img="http://userspace.org/wp-content/uploads/2020/02/USOmultimedia_icon.svg" ]
[hyperswitch tag="foss_game" url="/foss_game" menu="1" img="http://userspace.org/wp-content/uploads/2020/02/USOgames_icon.svg" ]
[hyperswitch tag="foss_office" url="/foss_office" menu="1" img="http://userspace.org/wp-content/uploads/2020/02/USOoffice_icon.svg" ]
[/hyperfetch]'); ?>
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

 <!-- Carousel short code goes here.  -->
     <div id="works">
     <?php  echo str_repeat('&nbsp;</BR>', 6); //empty fill area  ?>
     </div>
 <!-- Carousel short code goes here.  -->

	<div class="product-banner-area"> 

<div class="product-banner-area"> 
				
	<div class="header-background">

<div id="vanish" >
<div class="marktopbanner" ></div>
<nav id="button-container-item">
<div style="position: relative;width: 0;height: 0;" ><div style="position: relative;left: 440px;top: 10px;" >[<a target="_blank" href="https://www.linuxlinks.com">Linux&nbsp;Links</a>]</div></div>
<?php
    //Collection Dynamic Menu List
    $menuParameters = array(
      'theme_location' => 'item',
      'container'       => false,
      'echo'            => false,
      'depth'           => 0,
    );
    // Convert menu string into only anchor tags.
    echo strip_tags(wp_nav_menu( $menuParameters ), '<a>' );
    
?>
</nav>
</div> 
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



<?php echo do_shortcode('
[hyperfetch class="collections" initid=0 controlsid="button-container" containerid="collections"  loadbr=30 ]
[hyperswitch tag="RAM" url="/ram" menu="1" ]
[hyperswitch tag="NEWS" url="/newsfeeds" menu="1" ]
[hyperswitch tag="VIDEO" url="/videocast" menu="1" ]
[hyperswitch tag="AUDIO" url="/podcast" menu="1" ]
[hyperswitch tag="INFOSEC" url="/infosec" menu="1" ]
[/hyperfetch]'); ?>

<?php echo do_shortcode("[slide-anything id='1712']"); ?>


     <div id="collections">
     <?php  echo str_repeat('&nbsp;</BR>', 20); //empty fill area  ?>
     </div>


<?php get_footer(); ?>

