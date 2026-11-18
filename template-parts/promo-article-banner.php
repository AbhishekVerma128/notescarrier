<?php
/**
 * In-article Free Batches Callout / Recommended Courses on single posts.
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

$nc_lms_url = function_exists( "notescarrier_get_lms_url" ) ? notescarrier_get_lms_url() : "https://freebatches.lovable.app/";
?>

<div class="nc-article-promo-box" aria-labelledby="nc-article-promo-title">
	<div class="nc-article-promo-header">
		<div class="nc-article-promo-badge">
			<span class="nc-pulse-dot" aria-hidden="true"></span>
			<?php esc_html_e( "NotesCarrier LMS • 100% Free Batches", "notescarrier" ); ?>
		</div>
		<h2 id="nc-article-promo-title" class="nc-article-promo-title">
			<?php esc_html_e( "Preparing for 2026 Competitive Exams? Study 100% Free", "notescarrier" ); ?>
		</h2>
		<p class="nc-article-promo-desc">
			<?php esc_html_e( "Get direct access to our top 4 full-length foundation & practice batches. Video lectures, handwritten PDF notes & chapter tests included at ₹0.", "notescarrier" ); ?>
		</p>
	</div>

	<div class="nc-article-promo-grid">
		<?php foreach ( $nc_batches as $nc_batch ) : ?>
			<a class="nc-mini-batch-card" href="<?php echo esc_url( $nc_batch["url"] ); ?>" target="_blank" rel="noopener">
				<div class="nc-mini-batch-info">
					<div class="nc-mini-batch-head">
						<span class="nc-mini-batch-icon" aria-hidden="true"><?php echo esc_html( $nc_batch["icon"] ); ?></span>
						<span class="nc-mini-batch-title"><?php echo esc_html( $nc_batch["title"] ); ?></span>
					</div>
					<span class="nc-mini-batch-cat"><?php echo esc_html( $nc_batch["category"] ); ?></span>
				</div>
				<div class="nc-mini-batch-action">
					<span class="nc-mini-batch-tag"><?php echo esc_html( $nc_batch["badge"] ); ?></span>
					<span class="nc-mini-batch-btn">
						<span><?php esc_html_e( "Free (₹0)", "notescarrier" ); ?></span>
						<span aria-hidden="true">↗</span>
					</span>
				</div>
			</a>
		<?php endforeach; ?>
	</div>

	<div class="nc-article-promo-footer">
		<a class="nc-article-promo-all-btn" href="<?php echo esc_url( $nc_lms_url ); ?>" target="_blank" rel="noopener">
			<span><?php esc_html_e( "Open NotesCarrier LMS Portal — Browse All Free Courses", "notescarrier" ); ?></span>
			<span aria-hidden="true">↗</span>
		</a>
	</div>
</div>
