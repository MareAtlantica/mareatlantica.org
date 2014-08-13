<?php
/**
 * The front page template file.
 *
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage blank_bootstrap
 * @since Blank Theme with Bootstrap 1.0
 */
  // Gets header.php
  get_header(); ?>
  <?php
  // Gets Wordpress loop
  if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<?php the_content(); ?>
	<?php comments_template(); ?>
  <?php endwhile; else: ?>
	<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
  <?php endif; ?>
  <?php
  // Gets footer.php
  get_footer(); ?>

