<?php
/**
 * Compact popular-post item.
 *
 * @package NotesCarrier
 */
$rank = isset( $args['rank'] ) ? (int) $args['rank'] : 1;
?>
<article class="popular-item">
	<a class="popular-item__image post-image" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Read %s', 'notescarrier' ), get_the_title() ) ); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'notescarrier-small' ); ?>
		<?php else : ?>
			<span class="image-placeholder image-placeholder--small" aria-hidden="true"><span><?php esc_html_e( 'NC', 'notescarrier' ); ?></span></span>
		<?php endif; ?>
	</a>
	<div class="popular-item__content">
		<span class="popular-item__rank"><?php echo esc_html( sprintf( '%02d', $rank ) ); ?></span>
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<span class="popular-item__date"><?php echo esc_html( get_the_date() ); ?></span>
	</div>
</article>
