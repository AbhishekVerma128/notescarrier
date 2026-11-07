<?php
/**
 * Related books and study resources for the current post.
 *
 * @package NotesCarrier
 */
$current_post_id = get_the_ID();
if ( ! $current_post_id ) {
	return;
}

$notification_category = get_category_by_slug( 'latest-notification' );
$excluded_categories   = $notification_category ? array( (int) $notification_category->term_id ) : array();
$related_category_ids  = wp_get_post_categories( $current_post_id );
if ( is_wp_error( $related_category_ids ) ) {
	$related_category_ids = array();
}
$related_category_ids = array_map( 'absint', $related_category_ids );
if ( ! empty( $excluded_categories ) ) {
	$related_category_ids = array_values( array_diff( $related_category_ids, $excluded_categories ) );
}

$related_books = array();
$excluded_ids  = array( (int) $current_post_id );
$related_args  = array(
	'numberposts'      => 5,
	'post_type'        => 'post',
	'post_status'      => 'publish',
	'category__not_in' => $excluded_categories,
	'post__not_in'     => $excluded_ids,
	'orderby'          => 'date',
	'order'            => 'DESC',
	'suppress_filters' => false,
);

if ( ! empty( $related_category_ids ) ) {
	$related_books = get_posts( array_merge( $related_args, array( 'category__in' => $related_category_ids ) ) );
	foreach ( $related_books as $related_book ) {
		$excluded_ids[] = (int) $related_book->ID;
	}
}

if ( count( $related_books ) < 5 ) {
	$tag_ids = wp_get_post_tags( $current_post_id, array( 'fields' => 'ids' ) );
	if ( ! is_wp_error( $tag_ids ) && ! empty( $tag_ids ) ) {
		$more_by_tag = get_posts(
			array_merge(
				$related_args,
				array(
					'numberposts' => 5 - count( $related_books ),
					'post__not_in' => $excluded_ids,
					'tag__in'      => array_map( 'absint', $tag_ids ),
				)
			)
		);
		foreach ( $more_by_tag as $related_book ) {
			$related_books[] = $related_book;
			$excluded_ids[]  = (int) $related_book->ID;
		}
	}
}

if ( count( $related_books ) < 5 ) {
	$more_posts = get_posts(
		array_merge(
			$related_args,
			array(
				'numberposts'  => 5 - count( $related_books ),
				'post__not_in' => $excluded_ids,
			)
		)
	);
	$related_books = array_merge( $related_books, $more_posts );
}

if ( empty( $related_books ) ) {
	return;
}
?>
<section class="related-books" aria-labelledby="related-books-title">
	<div class="related-books__heading">
		<p class="eyebrow"><?php esc_html_e( 'Keep exploring', 'notescarrier' ); ?></p>
		<h2 id="related-books-title"><?php esc_html_e( 'Related books &amp; study resources', 'notescarrier' ); ?></h2>
	</div>
	<div class="related-books__grid">
		<?php foreach ( array_slice( $related_books, 0, 5 ) as $related_book ) : ?>
			<article class="related-book">
				<a class="related-book__image" href="<?php echo esc_url( get_permalink( $related_book ) ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Read %s', 'notescarrier' ), get_the_title( $related_book ) ) ); ?>">
					<?php if ( has_post_thumbnail( $related_book ) ) : ?>
						<?php echo get_the_post_thumbnail( $related_book, 'notescarrier-card', array( 'loading' => 'lazy' ) ); ?>
					<?php else : ?>
						<span class="image-placeholder image-placeholder--related" aria-hidden="true"><span>NC</span></span>
					<?php endif; ?>
				</a>
				<div class="related-book__content">
					<span class="related-book__date"><?php echo esc_html( get_the_date( '', $related_book ) ); ?></span>
					<h3><a href="<?php echo esc_url( get_permalink( $related_book ) ); ?>"><?php echo esc_html( get_the_title( $related_book ) ); ?></a></h3>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
</section>
