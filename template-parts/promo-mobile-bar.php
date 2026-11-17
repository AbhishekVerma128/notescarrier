<?php
/**
 * Mobile sticky bottom promo bar for free LMS batches.
 *
 * @package NotesCarrier
 */

$nc_lms_url = function_exists( "notescarrier_get_lms_url" ) ? notescarrier_get_lms_url() : "https://notescarrier.in/lms/";
?>
<div id="nc-mobile-lms-bar" class="nc-mobile-bar" role="complementary" aria-label="<?php esc_attr_e( "Free LMS Batches Announcement", "notescarrier" ); ?>" style="display:none;">
	<div class="nc-mobile-bar__inner">
		<div class="nc-mobile-bar__text">
			<span class="nc-mobile-bar__spark" aria-hidden="true">🔥</span>
			<div class="nc-mobile-bar__info">
				<strong><?php esc_html_e( "Free LMS Batches", "notescarrier" ); ?></strong>
				<span><?php esc_html_e( "Science Nirman, SSC Pratham & more at ₹0", "notescarrier" ); ?></span>
			</div>
		</div>
		<div class="nc-mobile-bar__actions">
			<a class="nc-mobile-bar__btn" href="<?php echo esc_url( $nc_lms_url ); ?>" target="_blank" rel="noopener">
				<?php esc_html_e( "Enroll Free", "notescarrier" ); ?>
				<span aria-hidden="true">↗</span>
			</a>
			<button type="button" class="nc-mobile-bar__close" id="nc-close-mobile-bar" aria-label="<?php esc_attr_e( "Close free batch announcement", "notescarrier" ); ?>">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	</div>
</div>
<script>
(function() {
	if (typeof window === "undefined" || typeof sessionStorage === "undefined") return;
	if (!sessionStorage.getItem("nc_lms_bar_dismissed")) {
		var bar = document.getElementById("nc-mobile-lms-bar");
		if (bar && window.innerWidth <= 768) {
			bar.style.display = "block";
		}
		var closeBtn = document.getElementById("nc-close-mobile-bar");
		if (closeBtn && bar) {
			closeBtn.addEventListener("click", function() {
				bar.style.display = "none";
				sessionStorage.setItem("nc_lms_bar_dismissed", "1");
			});
		}
	}
})();
</script>
