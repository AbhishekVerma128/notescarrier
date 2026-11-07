<?php
/**
 * Standard page template.
 *
 * @package NotesCarrier
 */
get_header();
?>
<main id="main-content" class="site-main page-main">
	<div class="container article-layout">
		<div class="article-column">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'article' ); ?>>
					<header class="article-header">
						<p class="eyebrow"><?php esc_html_e( 'NotesCarrier', 'notescarrier' ); ?></p>
						<h1><?php the_title(); ?></h1>
					</header>
					<?php if ( has_post_thumbnail() ) : ?>
						<figure class="article-image"><?php the_post_thumbnail( 'notescarrier-featured' ); ?></figure>
					<?php endif; ?>
					<div class="article-content entry-content"><?php the_content(); ?></div>
				</article>
				<?php
			endwhile;
			?>
		</div>
		<?php get_sidebar(); ?>
	</div>
</main>
<?php get_footer(); ?>
