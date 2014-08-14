<<<<<<< HEAD
<?php
/**
 * The post-page template file.
 *
 * This is the most generic template file in a WordPress theme and one of the
 * two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage blank_bootstrap
 * @since Blank Theme with Bootstrap 1.0
 */
  // Gets header-other.php
  get_header();
  // Gets Wordpress loop
  if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<h1><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>		
	<?php the_content(); ?>
	<?php comments_template(); ?>
  <?php endwhile; else: ?>
	<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
  <?php endif; ?>
  <?php
  // Gets footer.php
  get_footer(); ?>
=======
<?php
/**
 * The post-page template file.
 *
 * This is the most generic template file in a WordPress theme and one of the
 * two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage blank_bootstrap
 * @since Blank Theme with Bootstrap 1.0
 */
  // Gets header-other.php
  get_header("other");
  // Gets Wordpress loop ?>
  <div class="col-md-8">
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<h1><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
        <p>Publicado o <?php the_time('d \d\e F \d\e Y') ?></p>
	<?php the_content(); ?>
	<?php comments_template(); ?>
  <?php endwhile; else: ?>
	<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
  <?php endif; ?>
  </div>
  <div class="col-md-4">
  <?php if ( is_active_sidebar( 'blog_right_1' ) ) : ?>
	<div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">
		<?php dynamic_sidebar( 'blog_right_1' ); ?>
	</div><!-- #primary-sidebar -->
	<?php endif; ?>
  </div>
  <?php
  // Gets footer.php
  get_footer(); ?>
>>>>>>> d25f5e9d1ef5c6c565ce79e12568c7322dc863bc
