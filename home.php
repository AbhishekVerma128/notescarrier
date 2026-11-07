<?php
/**
 * Posts index.
 *
 * @package NotesCarrier
 */
get_header();
?>
<main id="main-content" class="site-main page-main">
	<div class="container archive-layout">
		<div class="archive-content">
			<header class="archive-header">
				<p class="eyebrow"><?php esc_html_e( 'From the NotesCarrier journal', 'notescarrier' ); ?></p>
				<h1><?php esc_html_e( 'All resources', 'notescarrier' ); ?></h1>
				<p><?php esc_html_e( 'Browse the latest book reviews, study notes and exam resources.', 'notescarrier' ); ?></p>
			</header>
			<?php if ( have_posts() ) : ?>
				<div class="post-list">
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/content', 'card' );
					endwhile;
					?>
				</div>
				<?php the_posts_pagination( array( 'mid_size' => 1, 'prev_text' => '←', 'next_text' => '→' ) ); ?>
			<?php else : ?>
				<?php get_template_part( 'template-parts/content', 'none' ); ?>
			<?php endif; ?>
		</div>
		<?php get_sidebar(); ?>
	</div>
</main>
<?php get_footer(); ?>
