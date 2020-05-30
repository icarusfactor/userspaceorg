<?php
  $TEMPLATE_PATH = parse_url(get_template_directory_uri(), PHP_URL_PATH);
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#000000">
   
    <link rel="pingback" href="http://47.217.123.141:8080/xmlrpc.php" />

    <?php wp_head(); ?>

    
</head>
    <body class="home page-template-default page page-id-53 animsition" >

        <!-- is_front_page check was here.  -->
<div id="topspot" class="crosshatch"  > 

<div id="vanish" >
<!-- HOME LOGO BUTTON  -->
<a href="http://47.217.123.141:8080" >
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

<div id="slider_cover" ><div class="marktop" ></div>
 <section id="content" class="page-wrap">
 <div class="content-wrapper halftonecircles ">                        
 <div class="container"> 
