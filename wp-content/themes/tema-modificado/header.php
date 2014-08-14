<?php 
/**
 * Default Header
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage blank_bootstrap
 * @since Blank Theme with Bootstrap 1.0
 *
 */?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php wp_title(''); ?></title>
  <link rel="profile" href="http://gmpg.org/xfn/11">
  <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
  <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />
  <?php 
  // Fires the 'wp_head' action and gets all the scripts included by wordpress, wordpress plugins or functions.php 
  // using wp_enqueue_script if it has $in_footer set to false (which is the default)
  wp_head(); ?>
</head>
<body <?php if (is_front_page()) body_class('home','page'); body_class(); ?>>
      <header role="banner" <?php if (is_front_page()) { echo 'class="front-page-header jumbotron"'; } ?>>
         <?php if (is_front_page()) { echo '<div class="container">
		<h1>MAREA ATLANTICA</h1>
			<p>E ti, tamén ventas a marea?<br/>
			Asina para que suba de vez.</p>
			<a title="Asina" href="#asina" class="btn btn-primary">Asina</a>
	</div>'; } ?>
   <?php if ( get_header_image() ) : ?>
         <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php header_image(); ?>" class="header-image, img-responsive" alt="" /></a>
   <?php endif; ?>
         <hgroup>
            <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
	    <h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
         </hgroup>

      </header>

       <nav id="navbar" class="navbar navbar-default navbar-fixed-top" role="navigation"

		<?php 
			// Fix menu overlap bug..
			if ( is_admin_bar_showing() ) echo '<div style="padding-top: 28px; min-height: 28px;"></div>'; 
		?>
         <!-- Mobile display -->
            <div class="navbar-header">
	       <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
		   <span class="sr-only">Toggle navigation</span>
		   <span class="icon-bar"></span>
		   <span class="icon-bar"></span>
		   <span class="icon-bar"></span>
	       </button>
	       <a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
	    </div>
      <!-- Collect the nav links for toggling -->
   <?php // Loading WordPress Custom Menu
      wp_nav_menu( array(
	'container_class' => 'collapse navbar-collapse navbar-ex1-collapse',
        'menu_class'      => 'nav navbar-nav',
	'menu'		  => 'Menú principal',
        'theme-location'  => 'main-menu',
	'fallback_cb'	  => 'wp_bootstrap_navwalker::fallback',
        'walker'          => new wp_bootstrap_navwalker()
      ) );
   ?>
       </nav>
       <div class="container">
