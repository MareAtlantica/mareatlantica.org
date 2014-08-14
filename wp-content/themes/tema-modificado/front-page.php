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

			<div id="fb-root"></div>
			<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/es_ES/sdk.js#xfbml=1&version=v2.0";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
			
			<?php $recent_posts = new WP_Query( 'showposts=2' ); ?>
			<?php while ($recent_posts -> have_posts()) : $recent_posts -> the_post(); ?>



       		
				   	<div class="row">
				  			<div class="col-md-4 novas"><h2>Actualidade</h2>
				  				<ul>
				  					<li>										
					  					<?php the_post_thumbnail( 'full', array( 'class' => 'img-responsive' ) ); ?>			  						
					  					 	<h3><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
					  						<p><?php the_excerpt(); ?></p>
			  					

				  					</li>

					  					
				  				</ul>
				  			<?php endwhile;?>
				  			
				  			</div>
				  			<div class="col-md-4">

						  			<a class="twitter-timeline"  href="https://twitter.com/mareatlantica"  data-widget-id="499232560908472320">Chíos de @mareatlantica</a>
		    						<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
							</div>
				  			<div class="col-md-4"><div class="fb-like-box" data-href="https://www.facebook.com/mareatlantica" data-colorscheme="light" data-show-faces="true" data-header="false" data-stream="true" data-show-border="false"></div>
							</div>
						</div>
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
