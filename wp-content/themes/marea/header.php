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
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>                          
<!--<![endif]-->

	<?php 
		//the '__before_body' hook is used by TC_header_main::$instance->tc_head_display()
		do_action( '__before_body' ); 
	?>

	<body <?php body_class(); ?> <?php echo tc__f('tc_body_attributes' , 'itemscope itemtype="http://schema.org/WebPage"') ?>>
        <!-- Menú fixo superior -->


        <nav id="navbar" class="navbar navbar-default navbar-fixed-top" role="navigation">
				<div class="container">

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
	  </div>
       </nav> 
		
		<?php do_action( '__before_header' ); ?>

	   	<header class="<?php echo tc__f('tc_header_classes', 'tc-header clearfix row-fluid') ?>" role="banner">
			
			<?php 
			//the '__header' hook is used by (ordered by priorities) : TC_header_main::$instance->tc_logo_title_display(), TC_header_main::$instance->tc_tagline_display(), TC_header_main::$instance->tc_navbar_display()
			//desactiva el menu superior de Customizr				do_action( '__header' ); 
			?>

		</header>

		<?php 
		 	//This hook is filtered with the slider : TC_slider::$instance->tc_slider_display()
			do_action ( '__after_header' )
		?>    
       
       <div class="container">
