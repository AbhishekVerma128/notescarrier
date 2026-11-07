<?php
/**
 * Theme header.
 *
 * @package NotesCarrier
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
	<meta name="google-adsense-account" content="ca-pub-4172398708042636">
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main-content"><?php esc_html_e( 'Skip to content', 'notescarrier' ); ?></a>

<div class="site-shell" id="top">
	<div class="topline">
		<div class="container topline__inner">
			<p><?php esc_html_e( 'Clear notes. Better preparation. Your next step starts here.', 'notescarrier' ); ?></p>
			<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/' ) ); ?>">
				<?php esc_html_e( 'Explore the latest', 'notescarrier' ); ?><span aria-hidden="true"> ↗</span>
			</a>
		</div>
	</div>

	<header class="site-header">
		<div class="container site-header__inner">
			<div class="brand">
				<?php if ( has_custom_logo() ) : ?>
					<div class="brand__image"><?php the_custom_logo(); ?></div>
				<?php else : ?>
					<a class="brand__link" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php bloginfo( 'name' ); ?>">
						<span class="brand__mark" aria-hidden="true">n</span>
						<span class="brand__words">
							<span class="brand__name"><?php bloginfo( 'name' ); ?></span>
							<span class="brand__tagline"><?php bloginfo( 'description' ); ?></span>
						</span>
					</a>
				<?php endif; ?>
			</div>

			<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-navigation">
				<span class="menu-toggle__icon" aria-hidden="true"><span></span><span></span></span>
				<span class="screen-reader-text"><?php esc_html_e( 'Open menu', 'notescarrier' ); ?></span>
			</button>

			<nav class="primary-navigation" id="primary-navigation" aria-label="<?php esc_attr_e( 'Primary navigation', 'notescarrier' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_class'     => 'primary-menu',
						'container'      => false,
						'fallback_cb'    => 'notescarrier_page_menu_fallback',
						'depth'          => 2,
					)
				);
				?>
			</nav>

			<div class="header-search">
				<?php get_search_form(); ?>
			</div>
		</div>
	</header>
	<?php get_template_part( 'template-parts/latest', 'notification' ); ?>
