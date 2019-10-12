<?php


/*--- Banner background function for image, color, gradient ---*/

function product_banner_background() {	

	$fornt_img = get_theme_mod('banner_side_img','');
	$site_header_type = get_theme_mod('site_header_type', 'image');
	
	?>
	
	<div class="header-background background-wave style1">

<div class="marktopbanner" ></div>

		<div class="header-content">
   
			<div class="container">
                <div id="leftside-attraction" class="row">
                    <div class="bannericonone" ></div>
                </div>

				<div id="main-attraction" class="row align-center">


                 <div class="bannerpuzzle" ><?php echo do_shortcode('[wordsquest]'); ?></div>



				</div>
                <div id="rightside-attraction" class="row">
                     <div class="bannericontwo" ></div>
                     <div class="bannericonthree" ></div>
                </div>
			</div>		

		</div>
         </div> 
<div id="vanish">
<div class="markbottom" ></div>
<div id="button-container">
<a id="RAM"class="jaxslide" href="<?php echo site_url('/ram'); ?>" >RAM</a>
<a id="NEWS" class="jaxslide" href="<?php echo site_url('/newsfeeds'); ?>" >NEWS</a>
<a id="VIDEO" class="jaxslide" href="<?php echo site_url('/videocast'); ?>" >VIDEO</a>
<a id="AUDIO" class="jaxslide" href="<?php echo site_url('/podcast'); ?>" >AUDIO</a>
<a id="INFOSEC" class="jaxslide" href="<?php echo site_url('/infosec'); ?>" >INFOSEC</a>
</div>
</div>
	<?php
	
}


/*--- Banner background function for video ---*/

function product_banner_video() {

	if ( !function_exists('the_custom_header_markup') ) {
		return;
	}

	$banner_type 	= get_theme_mod( 'banner_type' );

	if ( get_theme_mod('banner_type') == 'video' && is_front_page() ) {
		the_custom_header_markup();
	}
}



