<?php
/**
 * Sidebar with popular posts and topic discovery.
 *
 * @package NotesCarrier
 */
?>
<aside class="sidebar" aria-label="<?php esc_attr_e( 'More resources', 'notescarrier' ); ?>">
	<section class="sidebar-panel sidebar-panel--popular">
		<div class="sidebar-heading">
			<p class="eyebrow"><?php esc_html_e( 'Reader favorites', 'notescarrier' ); ?></p>
			<h2><?php esc_html_e( 'Popular posts', 'notescarrier' ); ?></h2>
		</div>
		<?php
		$popular_query = new WP_Query(
			array(
				'posts_per_page'      => 5,
				'post_status'         => 'publish',
				'orderby'             => 'comment_count',
				'order'               => 'DESC',
				'ignore_sticky_posts' => true,
			)
		);
		if ( $popular_query->have_posts() ) :
			?>
			<div class="popular-list">
				<?php
				$rank = 1;
				while ( $popular_query->have_posts() ) :
					$popular_query->the_post();
					get_template_part( 'template-parts/content', 'popular', array( 'rank' => $rank ) );
					$rank++;
				endwhile;
				?>
			</div>
			<?php
			wp_reset_postdata();
		else :
			?>
			<p class="sidebar-empty"><?php esc_html_e( 'New posts will show up here as they are published.', 'notescarrier' ); ?></p>
		<?php endif; ?>
	</section>

	<?php if ( function_exists( 'notescarrier_get_ad_unit' ) ) : ?>
		<?php echo notescarrier_get_ad_unit( 'sidebar' ); ?>
	<?php endif; ?>

		<section class="sidebar-panel sidebar-panel--lms-batches">
		<div class="sidebar-heading">
			<p class="eyebrow nc-pulse-eyebrow">
				<span class="nc-pulse-dot" aria-hidden="true"></span>
				<?php esc_html_e( '100% Free Batches', 'notescarrier' ); ?>
			</p>
			<h2><?php esc_html_e( 'NotesCarrier LMS', 'notescarrier' ); ?></h2>
		</div>
		<div class="nc-sidebar-batches">
			<?php
			if ( function_exists( 'notescarrier_get_lms_batches' ) ) :
				$nc_batches = notescarrier_get_lms_batches();
				foreach ( $nc_batches as $nc_batch ) :
					?>
					<a class="nc-sidebar-batch-card" href="<?php echo esc_url( $nc_batch['url'] ); ?>" target="_blank" rel="noopener">
						<div class="nc-sidebar-batch-top">
							<span class="nc-sidebar-batch-badge <?php echo esc_attr( $nc_batch['badge_class'] ); ?>"><?php echo esc_html( $nc_batch['badge'] ); ?></span>
							<span class="nc-sidebar-batch-free"><?php esc_html_e( 'FREE', 'notescarrier' ); ?></span>
						</div>
						<h3 class="nc-sidebar-batch-title">
							<span aria-hidden="true"><?php echo esc_html( $nc_batch['icon'] ); ?></span>
							<span><?php echo esc_html( $nc_batch['title'] ); ?></span>
						</h3>
						<span class="nc-sidebar-batch-cat"><?php echo esc_html( $nc_batch['category'] ); ?></span>
						<div class="nc-sidebar-batch-btn">
							<span><?php esc_html_e( 'Enroll Free', 'notescarrier' ); ?></span>
							<span aria-hidden="true">↗</span>
						</div>
					</a>
					<?php
				endforeach;
			endif;
			?>
		</div>
		<a class="nc-sidebar-lms-all" href="<?php echo esc_url( function_exists( 'notescarrier_get_lms_url' ) ? notescarrier_get_lms_url() : 'https://notescarrier.in/lms/' ); ?>" target="_blank" rel="noopener">
			<?php esc_html_e( 'View All Free Batches on LMS', 'notescarrier' ); ?> <span aria-hidden="true">↗</span>
		</a>
	</section>

	<section class="sidebar-panel sidebar-panel--topics">
		<div class="sidebar-heading">
			<p class="eyebrow"><?php esc_html_e( 'Find your focus', 'notescarrier' ); ?></p>
			<h2><?php esc_html_e( 'Browse topics', 'notescarrier' ); ?></h2>
		</div>
		<ul class="topic-list">
			<?php
			$topics = get_categories(
				array(
					'hide_empty' => true,
					'orderby'    => 'count',
					'order'      => 'DESC',
				)
			);
			if ( $topics ) :
				foreach ( $topics as $topic ) :
					?>
					<li>
						<a href="<?php echo esc_url( get_category_link( $topic->term_id ) ); ?>">
							<span><?php echo esc_html( $topic->name ); ?></span>
							<span class="topic-count"><?php echo esc_html( number_format_i18n( $topic->count ) ); ?></span>
						</a>
					</li>
					<?php
				endforeach;
			else :
				?>
				<li class="topic-empty"><?php esc_html_e( 'Add categories to your posts to show topics here.', 'notescarrier' ); ?></li>
			<?php endif; ?>
		</ul>
	</section>

	<div class="sidebar-note">
		<span class="sidebar-note__spark" aria-hidden="true">✳</span>
		<p class="eyebrow"><?php esc_html_e( 'One step at a time', 'notescarrier' ); ?></p>
		<p><?php esc_html_e( 'Good preparation begins with the right resource.', 'notescarrier' ); ?></p>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>#latest"><?php esc_html_e( 'Find your next read', 'notescarrier' ); ?><span aria-hidden="true">↗</span></a>
	</div>
</aside>
