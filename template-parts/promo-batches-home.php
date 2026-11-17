<?php
/**
 * Promotional Free Batches section for NotesCarrier LMS on Homepage.
 *
 * @package NotesCarrier
 */

if ( ! function_exists( "notescarrier_get_lms_batches" ) ) {
	return;
}

$nc_batches = notescarrier_get_lms_batches();
if ( empty( $nc_batches ) ) {
	return;
}

$nc_lms_url = function_exists( "notescarrier_get_lms_url" ) ? notescarrier_get_lms_url() : "https://notescarrier.in/lms/";
?>

<section class="nc-lms-section" id="free-batches" aria-labelledby="nc-lms-heading">
	<div class="container nc-lms-container">
		<div class="nc-lms-header">
			<div class="nc-lms-header__left">
				<p class="eyebrow nc-pulse-eyebrow">
					<span class="nc-pulse-dot" aria-hidden="true"></span>
					<?php esc_html_e( "NotesCarrier LMS • 100% Free Access", "notescarrier" ); ?>
				</p>
				<h2 id="nc-lms-heading" class="nc-lms-heading">
					<?php esc_html_e( "Featured Exam Batches — Study Free in 2026", "notescarrier" ); ?>
				</h2>
				<p class="nc-lms-subheading">
					<?php esc_html_e( "Comprehensive video lectures, chapter-wise PDF notes, and full-length tests — completely free, no subscription required.", "notescarrier" ); ?>
				</p>
			</div>
			<div class="nc-lms-header__right">
				<a class="nc-lms-viewall" href="<?php echo esc_url( $nc_lms_url ); ?>" target="_blank" rel="noopener">
					<span><?php esc_html_e( "Explore All Batches", "notescarrier" ); ?></span>
					<span aria-hidden="true">↗</span>
				</a>
			</div>
		</div>

		<div class="nc-mobile-swipe-hint" aria-hidden="true">
			<span><?php esc_html_e( "👈 Swipe to see all 4 batches 👉", "notescarrier" ); ?></span>
		</div>

		<div class="nc-batches-track" role="region" aria-label="<?php esc_attr_e( "Featured free batches carousel", "notescarrier" ); ?>">
			<?php foreach ( $nc_batches as $nc_batch ) : ?>
				<article class="nc-batch-card" id="batch-<?php echo esc_attr( $nc_batch["id"] ); ?>">
					<div class="nc-batch-card__top">
						<span class="nc-batch-cat"><?php echo esc_html( $nc_batch["category"] ); ?></span>
						<span class="nc-batch-badge <?php echo esc_attr( $nc_batch["badge_class"] ); ?>">
							<span class="nc-badge-dot" aria-hidden="true"></span>
							<?php echo esc_html( $nc_batch["badge"] ); ?>
						</span>
					</div>

					<div class="nc-batch-card__body">
						<h3 class="nc-batch-title">
							<span class="nc-batch-icon" aria-hidden="true"><?php echo esc_html( $nc_batch["icon"] ); ?></span>
							<span><?php echo esc_html( $nc_batch["title"] ); ?></span>
						</h3>
						<p class="nc-batch-tagline"><?php echo esc_html( $nc_batch["tagline"] ); ?></p>

						<ul class="nc-batch-features" aria-label="<?php esc_attr_e( "Batch features", "notescarrier" ); ?>">
							<?php foreach ( $nc_batch["features"] as $nc_feature ) : ?>
								<li>
									<svg class="nc-check-icon" width="14" height="14" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
										<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
									</svg>
									<span><?php echo esc_html( $nc_feature ); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>

					<div class="nc-batch-card__footer">
						<div class="nc-batch-pricing">
							<div class="nc-price-wrap">
								<del class="nc-price-orig" aria-label="<?php esc_attr_e( "Original price", "notescarrier" ); ?>"><?php echo esc_html( $nc_batch["price_orig"] ); ?></del>
								<span class="nc-price-free" aria-label="<?php esc_attr_e( "Current price", "notescarrier" ); ?>"><?php echo esc_html( $nc_batch["price_now"] ); ?></span>
							</div>
							<span class="nc-discount-pill"><?php echo esc_html( $nc_batch["discount"] ); ?></span>
						</div>

						<a class="nc-batch-cta" href="<?php echo esc_url( $nc_batch["url"] ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( sprintf( __( "Enroll in %s for Free", "notescarrier" ), $nc_batch["title"] ) ); ?>">
							<span><?php esc_html_e( "Enroll For Free", "notescarrier" ); ?></span>
							<span class="nc-cta-arrow" aria-hidden="true">↗</span>
						</a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
