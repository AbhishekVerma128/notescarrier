<?php
/**
 * Single post template.
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
				$category = notescarrier_primary_category();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'article' ); ?>>
					<header class="article-header">
						<?php if ( $category ) : ?>
							<a class="eyebrow article-category" href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
						<?php endif; ?>
						<h1><?php the_title(); ?></h1>
						<div class="article-meta">
							<span><?php echo esc_html( get_the_date() ); ?></span>
							<span class="meta-dot" aria-hidden="true"></span>
							<span><?php echo esc_html( sprintf( _n( '%s min read', '%s min read', notescarrier_reading_time(), 'notescarrier' ), number_format_i18n( notescarrier_reading_time() ) ) ); ?></span>
							<span class="meta-dot" aria-hidden="true"></span>
							<span><?php the_author_posts_link(); ?></span>
						</div>
					</header>
					<?php if ( has_post_thumbnail() ) : ?>
						<figure class="article-image"><?php the_post_thumbnail( 'notescarrier-featured' ); ?></figure>
					<?php endif; ?>
					<div class="article-content entry-content">
						<?php
						the_content();
						wp_link_pages(
							array(
								'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'notescarrier' ),
								'after'  => '</div>',
							)
						);
						?>
					</div>
					<footer class="article-footer">
						<?php the_tags( '<div class="article-tags"><span>' . esc_html__( 'Filed under', 'notescarrier' ) . '</span>', '', '</div>' ); ?>
						<div class="article-share">
							<span><?php esc_html_e( 'Enjoyed this resource?', 'notescarrier' ); ?></span>
							<a href="<?php echo esc_url( get_permalink() ); ?>"><?php esc_html_e( 'Share the link', 'notescarrier' ); ?><span aria-hidden="true">↗</span></a>
						</div>
					</footer>
				</article>
				<?php get_template_part( 'template-parts/related', 'books' ); ?>
				<?php
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
			endwhile;
			?>
		</div>
		<?php get_sidebar(); ?>
	</div>
</main>
<?php get_footer(); ?>
