<?php
/**
 * Theme footer.
 *
 * @package NotesCarrier
 */
?>
	<footer class="site-footer">
		<div class="container site-footer__main">
			<div class="footer-brand">
				<a class="brand__link brand__link--footer" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<span class="brand__mark" aria-hidden="true">n</span>
					<span class="brand__words">
						<span class="brand__name"><?php bloginfo( 'name' ); ?></span>
						<span class="brand__tagline"><?php esc_html_e( 'Learn with clarity.', 'notescarrier' ); ?></span>
					</span>
				</a>
				<p><?php esc_html_e( 'Thoughtful study resources for every step of your learning journey.', 'notescarrier' ); ?></p>
				<a class="footer-latest-link" href="<?php echo esc_url( notescarrier_latest_notification_url() ); ?>">
					<span class="footer-latest-link__dot" aria-hidden="true"></span>
					<?php esc_html_e( 'See latest exam notifications', 'notescarrier' ); ?>
				</a>
			</div>
			<div class="footer-links footer-links--topics">
				<span class="footer-heading"><?php esc_html_e( 'Browse topics', 'notescarrier' ); ?></span>
				<ul class="footer-menu">
					<?php
					$footer_categories = get_categories(
						array(
							'number'     => 4,
							'hide_empty' => true,
							'orderby'    => 'count',
							'order'      => 'DESC',
						)
					);
					if ( $footer_categories ) :
						foreach ( $footer_categories as $footer_category ) :
							?>
							<li><a href="<?php echo esc_url( get_category_link( $footer_category->term_id ) ); ?>"><?php echo esc_html( $footer_category->name ); ?></a></li>
							<?php
						endforeach;
					else :
						?>
						<li><a href="<?php echo esc_url( notescarrier_latest_notification_url() ); ?>"><?php esc_html_e( 'Latest Notification', 'notescarrier' ); ?></a></li>
					<?php endif; ?>
				</ul>
			</div>
			<div class="footer-links footer-links--pages">
				<span class="footer-heading"><?php esc_html_e( 'Quick links', 'notescarrier' ); ?></span>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'menu_class'     => 'footer-menu',
						'container'      => false,
						'fallback_cb'    => 'notescarrier_page_menu_fallback',
						'depth'          => 1,
					)
				);
				?>
			</div>
			<a class="back-to-top" href="#top" aria-label="<?php esc_attr_e( 'Back to top', 'notescarrier' ); ?>">
				<span aria-hidden="true">↑</span><?php esc_html_e( 'Top', 'notescarrier' ); ?>
			</a>
		</div>
		<div class="container site-footer__bottom">
			<span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></span>
			<span><?php esc_html_e( 'Made for curious minds.', 'notescarrier' ); ?></span>
		</div>
	</footer>
</div>
<?php get_template_part( 'template-parts/community', 'popup' ); ?>
<?php wp_footer(); ?>
</body>
</html>
