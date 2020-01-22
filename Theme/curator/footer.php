      </div><!-- /.container -->
      </div><!-- /.content-wrapper -->
      </section><!-- /.page-wrap -->


    <footer class="site-footer crosshatch ">
<div class="footbreak" ></div>

    <!-- START: Appearance -> Widgets -->
    <?php if( function_exists('slbd_display_widgets') ) { echo slbd_display_widgets(); } ?>
    <!-- END:   Appearance -> Widgets  -->
    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer Citation") ) : ?><?php endif;?>

    <?php get_template_part('./template-files/footer', 'widget');  ?>

    <?php wp_footer(); ?>
    </footer><!-- /.site-footer -->



<!-- General Loading Modal -->
      <section id="loading" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" >
        <div class="modal-dialog modal-sm">
          <span class="loadmedia">&nbsp;L&nbsp;O&nbsp;A&nbsp;D&nbsp;I&nbsp;N&nbsp;G&nbsp;</span>  
        </div>
     </section>


<!-- General Email Modal -->
<section class="modal fade" id="GenEmailModal" tabindex="-1" role="dialog" aria-labelledby="EmailModalTitle" aria-hidden="true">



<div id="widget-section" style="max-width:50%;padding-left: 10px;padding-right: 10px;border-radius: 5px;border: 6px solid lightblue;background-color: #fafafa;border-top: 12px solid lightblue;" >
<div class="chunk">
<div id="content">
<div id="sp_results">
</div>
</div>


  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content modal-lg">
      <div class="modal-header modal-lg">
        <h5 class="modal-title" id="EmailModalTitle">General Contact Email</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body modal-lg">
        <?php echo do_shortcode( '[contact-form-7 id="2744" title="General Contact "]' );  ?>
      </div>
    </div>
  </div>

</div></div>

</section>

    </body>

</html>
