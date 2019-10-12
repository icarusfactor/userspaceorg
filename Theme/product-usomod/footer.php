</div><!-- /.row -->
</div><!-- /.container -->
</div><!-- /.content-wrapper -->
</div><!-- /.page-wrap -->
	
	<?php
		get_template_part('template-files/footer', 'widget');
	?>
    <footer class="site-footer">

    <?php if( function_exists('slbd_display_widgets') ) { echo slbd_display_widgets(); } ?>

<div id="copyleft"><span id="webauthor"> Page Design by Daniel Yount </span>:::
<span id="footerhosting"> Thanks To [<a href="https://x10hosting.com" >X10Hosting</a>] For Free Hosting </span></div>

    </footer><!-- /.site-footer -->
	
	<?php wp_footer(); ?> 


  </body>
 
</html>