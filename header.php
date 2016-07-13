<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package MG\Theme
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<?php 
add_filter( THEME_TEXT_DOMAIN . '-markup-indent', function() { return 1; }, 1 );
MG\Theme\Head\Render::head();
\remove_all_filters( THEME_TEXT_DOMAIN . '-markup-indent', 1 );
?>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<link rel="alternate" type="application/rss+xml" title="<?php echo get_bloginfo('sitename') ?> Feed" href="<?php echo get_bloginfo('rss2_url') ?>">
</head>

<body <?php body_class(); ?>>
	<header id="header" role="banner">
		<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', THEME_TEXT_DOMAIN ); ?></a>
		<div class="site-branding">
<?php	
if ( ( is_front_page() && is_home() ) || is_404() ) : 
?>
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
<?php 
else : 
?>
			<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
<?php
endif;
$description = get_bloginfo( 'description', 'display' );
if ( $description || is_customize_preview() ) : 
?>
			<p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
<?php 
endif; 
?>
		</div>
	</header>

	<div id="main-navigation" class="site-navigation" role="navigation">
		<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Site Menu', THEME_TEXT_DOMAIN ); ?></button>
<?php 
add_filter( THEME_TEXT_DOMAIN . '-markup-indent', function() { return 2; }, 2 );
wp_nav_menu( array( 'theme_location' => 'primary' ) ); 
\remove_all_filters( THEME_TEXT_DOMAIN . '-markup-indent', 2 );
?>

	</div>

	<main id="main" class="site-main" role="main">