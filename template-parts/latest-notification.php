<?php
/**
 * Carousel of the five newest posts in the Latest Notification category.
 *
 * @package NotesCarrier
 */
$notifications = notescarrier_latest_notification_posts();
if ( empty( $notifications ) ) {
	return;
}

$notification_category = get_category_by_slug( 'latest-notification' );
$notification_count    = count( $notifications );
?>
<section class="notification-strip" aria-label="<?php esc_attr_e( 'Latest notifications', 'notescarrier' ); ?>">
	<div class="container">
		<div class="notification-carousel" data-notification-carousel role="region" aria-roledescription="<?php esc_attr_e( 'carousel', 'notescarrier' ); ?>">
			<div class="notification-carousel__viewport">
				<div class="notification-carousel__track" data-notification-track>
					<?php foreach ( $notifications as $index => $notification ) : ?>
						<?php
						$notification_id      = (int) $notification->ID;
						$notification_url     = get_permalink( $notification );
						$notification_time    = get_post_time( 'U', true, $notification );
						$notification_age     = human_time_diff( $notification_time, current_time( 'timestamp', true ) );
						$notification_excerpt = wp_strip_all_tags( get_the_excerpt( $notification ) );
						$notification_excerpt = trim( wp_html_excerpt( $notification_excerpt, 190, '…' ) );
						$image_attributes     = array( 'loading' => 0 === $index ? 'eager' : 'lazy' );
						if ( 0 === $index ) {
							$image_attributes['fetchpriority'] = 'high';
						}
						?>
						<article
							class="notification-card"
							data-notification-slide
							role="group"
							aria-roledescription="<?php esc_attr_e( 'slide', 'notescarrier' ); ?>"
							aria-label="<?php echo esc_attr( sprintf( __( '%1$d of %2$d', 'notescarrier' ), $index + 1, $notification_count ) ); ?>"
							aria-hidden="<?php echo 0 === $index ? 'false' : 'true'; ?>"
							<?php if ( 0 !== $index ) : ?>inert<?php endif; ?>
						>
							<a class="notification-card__image" href="<?php echo esc_url( $notification_url ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Read %s', 'notescarrier' ), get_the_title( $notification ) ) ); ?>">
								<?php if ( has_post_thumbnail( $notification ) ) : ?>
									<?php echo get_the_post_thumbnail( $notification, 'notescarrier-card', $image_attributes ); ?>
								<?php else : ?>
									<span class="image-placeholder image-placeholder--notification" aria-hidden="true"><span>NC</span></span>
								<?php endif; ?>
							</a>
							<div class="notification-card__content">
								<div class="notification-card__meta">
									<?php if ( $notification_category ) : ?>
										<a class="notification-badge" href="<?php echo esc_url( get_category_link( $notification_category->term_id ) ); ?>">
											<span class="notification-badge__dot" aria-hidden="true"></span>
											<?php echo esc_html( $notification_category->name ); ?>
										</a>
									<?php else : ?>
										<span class="notification-badge">
											<span class="notification-badge__dot" aria-hidden="true"></span>
											<?php esc_html_e( 'Latest Notification', 'notescarrier' ); ?>
										</span>
									<?php endif; ?>
									<span class="notification-age"><?php echo esc_html( sprintf( __( '%s ago', 'notescarrier' ), $notification_age ) ); ?></span>
								</div>
								<h2><a href="<?php echo esc_url( $notification_url ); ?>"><?php echo esc_html( get_the_title( $notification ) ); ?></a></h2>
								<?php if ( $notification_excerpt ) : ?>
									<p class="notification-card__excerpt"><?php echo esc_html( $notification_excerpt ); ?></p>
								<?php endif; ?>
								<div class="notification-card__footer">
									<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C, $notification ) ); ?>"><?php echo esc_html( get_the_date( '', $notification ) ); ?></time>
									<a class="notification-read-more" href="<?php echo esc_url( $notification_url ); ?>">
										<?php esc_html_e( 'Read notification', 'notescarrier' ); ?><span aria-hidden="true">↗</span>
									</a>
								</div>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
			<?php if ( $notification_count > 1 ) : ?>
				<div class="notification-carousel__controls">
					<button class="notification-carousel__arrow" type="button" data-notification-prev aria-label="<?php esc_attr_e( 'Previous notification', 'notescarrier' ); ?>">
						<span aria-hidden="true">←</span>
					</button>
					<div class="notification-carousel__indicators" aria-label="<?php esc_attr_e( 'Choose a notification', 'notescarrier' ); ?>">
						<?php foreach ( $notifications as $index => $notification ) : ?>
							<button
								class="notification-carousel__indicator"
								type="button"
								data-notification-go="<?php echo esc_attr( (string) $index ); ?>"
								aria-label="<?php echo esc_attr( sprintf( __( 'Show notification %1$d of %2$d', 'notescarrier' ), $index + 1, $notification_count ) ); ?>"
								aria-pressed="<?php echo 0 === $index ? 'true' : 'false'; ?>"
							></button>
						<?php endforeach; ?>
					</div>
					<span class="notification-carousel__status" data-notification-status aria-live="off">1 / <?php echo esc_html( (string) $notification_count ); ?></span>
					<button class="notification-carousel__arrow notification-carousel__rotation" type="button" data-notification-rotation aria-label="<?php esc_attr_e( 'Pause automatic notifications', 'notescarrier' ); ?>" aria-pressed="false">
						<span aria-hidden="true">Ⅱ</span>
					</button>
					<button class="notification-carousel__arrow" type="button" data-notification-next aria-label="<?php esc_attr_e( 'Next notification', 'notescarrier' ); ?>">
						<span aria-hidden="true">→</span>
					</button>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
