<?php
/**
 * Empty archive/search state.
 *
 * @package NotesCarrier
 */
?>
<div class="empty-state">
	<span class="empty-state__symbol" aria-hidden="true">✳</span>
	<h2><?php esc_html_e( 'Nothing here just yet.', 'notescarrier' ); ?></h2>
	<p><?php esc_html_e( 'Try a different search or explore the latest resources.', 'notescarrier' ); ?></p>
	<?php get_search_form(); ?>
</div>
