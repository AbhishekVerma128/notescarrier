<?php
/**
 * Not found page.
 *
 * @package NotesCarrier
 */
get_header();
?>
<main id="main-content" class="site-main page-main">
	<section class="container not-found">
		<p class="eyebrow"><?php esc_html_e( 'Page not found', 'notescarrier' ); ?></p>
		<h1><?php esc_html_e( 'Looks like this page took a different route.', 'notescarrier' ); ?></h1>
		<p><?php esc_html_e( 'Try a search or head back to the latest study resources.', 'notescarrier' ); ?></p>
		<?php get_search_form(); ?>
		<a class="button button--dark" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home', 'notescarrier' ); ?><span aria-hidden="true">↗</span></a>
	</section>
</main>
<?php get_footer(); ?>
