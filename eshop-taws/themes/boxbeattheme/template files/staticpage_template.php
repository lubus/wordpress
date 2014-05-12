<?php /*Template Name:Static Pages1 */ ?>

<?php get_header(); ?>


<div class="myacc_content1">
<div class="acc_menuleft"><?php dynamic_sidebar('sidebar-10') ?></div>
<div class="static_contentcover"><div class="static_content">
<div id="primary" class="site-content">



		<div id="content" role="main">



			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        <div class="entry-content">
         <h3 class="cls_stahea"><?php the_title(); ?></h3>


        

        

        

	        <?php the_content(); ?>

			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'floworld' ), 'after' => '</div>' ) ); ?>

            <div class="clear"></div>


		</div><!-- .entry-content -->

		<footer class="entry-meta">

			<?php edit_post_link( __( 'Edit', 'floworld' ), '<span class="edit-link">', '</span>' ); ?>

		</footer><!-- .entry-meta -->

	</article><!-- #post -->

    

    

				<?php comments_template( '', true ); ?>

			<?php endwhile; // end of the loop. ?>



		</div><!-- #content -->

	</div>





</div></div>


<div class="clear"></div>

</div>



<?php get_footer(); ?>