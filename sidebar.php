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
