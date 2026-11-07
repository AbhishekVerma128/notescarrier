<?php
/**
 * Accessible search form.
 *
 * @package NotesCarrier
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="site-search"><?php esc_html_e( 'Search the site', 'notescarrier' ); ?></label>
	<input type="search" id="site-search" class="search-field" placeholder="<?php esc_attr_e( 'Search notes, books…', 'notescarrier' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
	<button type="submit" class="search-submit" aria-label="<?php esc_attr_e( 'Search', 'notescarrier' ); ?>">
		<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="10.8" cy="10.8" r="6.7"></circle><path d="m16 16 4.5 4.5"></path></svg>
	</button>
</form>
