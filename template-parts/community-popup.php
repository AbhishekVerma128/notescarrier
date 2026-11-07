<?php
/**
 * Dismissible community popup with administrator-configurable channel links.
 *
 * @package NotesCarrier
 */
if ( ! get_theme_mod( 'notescarrier_popup_enabled', true ) ) {
	return;
}

$telegram_url = trim( (string) get_theme_mod( 'notescarrier_telegram_url', 'https://t.me/notescarrier3' ) );
if ( '' === $telegram_url ) {
	$telegram_url = 'https://t.me/notescarrier3';
}
$youtube_url  = trim( (string) get_theme_mod( 'notescarrier_youtube_url', '' ) );
$delay        = min( 60, max( 0, absint( get_theme_mod( 'notescarrier_popup_delay', 6 ) ) ) );
$notification_url = notescarrier_latest_notification_url();
?>
<div class="community-popup" data-community-popup data-delay="<?php echo esc_attr( (string) $delay ); ?>" hidden>
	<div class="community-popup__dialog" role="dialog" aria-modal="true" aria-labelledby="community-popup-title" aria-describedby="community-popup-description" tabindex="-1">
		<button class="community-popup__close" type="button" data-popup-close aria-label="<?php esc_attr_e( 'Close community popup', 'notescarrier' ); ?>">
			<span aria-hidden="true">&times;</span>
		</button>
		<div class="community-popup__symbol" aria-hidden="true">
			<span class="community-popup__symbol-orbit"></span>
			<span class="community-popup__symbol-mark">n</span>
		</div>
		<p class="eyebrow"><?php esc_html_e( 'Stay in the loop', 'notescarrier' ); ?></p>
		<h2 id="community-popup-title"><?php esc_html_e( 'Join the NotesCarrier community', 'notescarrier' ); ?></h2>
		<p class="community-popup__description" id="community-popup-description"><?php esc_html_e( 'Get free notes, PDFs, and exam updates.', 'notescarrier' ); ?></p>
		<div class="community-popup__actions">
			<?php if ( $telegram_url ) : ?>
				<a class="community-popup__action community-popup__action--telegram" href="<?php echo esc_url( $telegram_url ); ?>" target="_blank" rel="noopener noreferrer" data-popup-action>
					<span class="community-popup__action-icon" aria-hidden="true">↗</span>
					<?php esc_html_e( 'Join Telegram Channel', 'notescarrier' ); ?>
				</a>
			<?php endif; ?>
			<?php if ( $youtube_url ) : ?>
				<a class="community-popup__action community-popup__action--youtube" href="<?php echo esc_url( $youtube_url ); ?>" target="_blank" rel="noopener noreferrer" data-popup-action>
					<span class="community-popup__action-icon community-popup__action-icon--play" aria-hidden="true">▶</span>
					<?php esc_html_e( 'Subscribe on YouTube', 'notescarrier' ); ?>
				</a>
			<?php endif; ?>
			<?php if ( ! $telegram_url && ! $youtube_url ) : ?>
				<a class="community-popup__action community-popup__action--telegram" href="<?php echo esc_url( $notification_url ); ?>" data-popup-action>
					<span class="community-popup__action-icon" aria-hidden="true">↗</span>
					<?php esc_html_e( 'See Latest Notifications', 'notescarrier' ); ?>
				</a>
			<?php endif; ?>
		</div>
		<p class="community-popup__note" lang="hi"><?php esc_html_e( 'दोस्तों! आपको बेहतरीन कंटेंट मिलता रहे, इसलिए हमने वेबसाइट पर कुछ Ads लगाए हैं। अगर वेबसाइट यूज़ करते समय कोई नया टैब ओपन हो जाए, तो परेशान न हों—बस उसे क्लोज़ करके इस पेज पर वापस आ जाएँ। अपना प्यार और support बनाए रखें!', 'notescarrier' ); ?></p>
	</div>
</div>
