<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main post-wrap" role="main">
		<?php 
			if ( have_posts() ) : while ( have_posts() ) : the_post();

				get_template_part( './template-files/content', 'page' );
				
				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile;
		?>
		
		<?php else : ?>

			<?php get_template_part( './template-files/content', 'none' ); ?>

		<?php endif; ?>
		</main> <!-- /main -->
	</div> <!-- /.post-wrap -->

<?php get_footer(); ?>
