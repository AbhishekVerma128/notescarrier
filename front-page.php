<?php
/**
 * Homepage: a featured resource, recent posts, and useful discovery links.
 *
 * @package NotesCarrier
 */
get_header();
$notification_post = notescarrier_latest_notification_post();
$notification_category = get_category_by_slug( 'latest-notification' );
$excluded_categories = $notification_category ? array( (int) $notification_category->term_id ) : array();
$latest_page = max( 1, absint( get_query_var( 'paged' ) ), absint( get_query_var( 'page' ) ) );
?>

<main id="main-content" class="site-main">
	<section class="home-intro">
		<div class="container home-intro__inner">
			<div>
				<p class="eyebrow eyebrow--light"><?php esc_html_e( 'A little more clarity for your next big step', 'notescarrier' ); ?></p>
				<h1><?php esc_html_e( 'Study smarter. Go further.', 'notescarrier' ); ?></h1>
				<p class="home-intro__copy"><?php esc_html_e( 'Book reviews, study notes and exam resources — thoughtfully brought together to help you move forward.', 'notescarrier' ); ?></p>
			</div>
			<a class="button button--lime" href="#latest">
				<?php esc_html_e( 'Explore the latest', 'notescarrier' ); ?><span aria-hidden="true">↓</span>
			</a>
			<div class="home-intro__detail" aria-hidden="true">
				<span class="detail-orbit detail-orbit--one"></span>
				<span class="detail-orbit detail-orbit--two"></span>
				<span class="detail-spark">✳</span>
				<span class="detail-note">01<br><small>LEARN</small></span>
			</div>
		</div>
	</section>

	<div class="container home-layout">
		<div class="home-feed">

			<section class="latest-section" id="latest" aria-labelledby="latest-heading">
				<div class="section-heading">
					<div>
						<p class="eyebrow"><?php esc_html_e( 'Fresh from the blog', 'notescarrier' ); ?></p>
						<h2 id="latest-heading"><?php esc_html_e( 'Latest resources', 'notescarrier' ); ?></h2>
					</div>
					<a class="text-link" href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/' ) ); ?>">
						<?php esc_html_e( 'View all posts', 'notescarrier' ); ?><span aria-hidden="true">↗</span>
					</a>
				</div>

				<?php
				$latest_query = new WP_Query(
					array(
						'posts_per_page'      => 15,
						'paged'               => $latest_page,
						'post_status'         => 'publish',
						'category__not_in'    => $excluded_categories,
						'ignore_sticky_posts' => true,
					)
				);
				if ( $latest_query->have_posts() ) :
					while ( $latest_query->have_posts() ) :
						$latest_query->the_post();
						get_template_part( 'template-parts/content', 'card' );
					endwhile;
					wp_reset_postdata();
					if ( $latest_query->max_num_pages > 1 ) :
						$pagination_links = paginate_links(
							array(
								'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
								'current'   => $latest_page,
								'total'     => (int) $latest_query->max_num_pages,
								'mid_size'  => 1,
								'prev_text' => esc_html__( '← Previous', 'notescarrier' ),
								'next_text' => esc_html__( 'Next →', 'notescarrier' ),
								'type'      => 'plain',
							)
						);
						if ( $pagination_links ) :
							?>
							<nav class="navigation pagination" aria-label="<?php esc_attr_e( 'Latest resources pages', 'notescarrier' ); ?>">
								<div class="nav-links"><?php echo wp_kses_post( $pagination_links ); ?></div>
							</nav>
							<?php
						endif;
					endif;
				else :
					?>
					<div class="empty-state">
						<span class="empty-state__symbol" aria-hidden="true">✳</span>
						<h3><?php esc_html_e( 'Your next great read is on its way.', 'notescarrier' ); ?></h3>
						<p><?php esc_html_e( 'New study resources will appear here as soon as they are published.', 'notescarrier' ); ?></p>
					</div>
				<?php endif; ?>
			</section>
		</div>

		<?php get_sidebar(); ?>
	</div>
</main>

<?php
get_footer();
