<?php
/**
 * Standard horizontal post card.
 *
 * @package NotesCarrier
 */
$category = notescarrier_primary_category();
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
	<a class="post-card__image post-image" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Read %s', 'notescarrier' ), get_the_title() ) ); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'notescarrier-card' ); ?>
		<?php else : ?>
			<span class="image-placeholder" aria-hidden="true"><span><?php esc_html_e( 'NC', 'notescarrier' ); ?></span></span>
		<?php endif; ?>
	</a>
	<div class="post-card__content">
		<div class="post-categories">
			<?php if ( $category ) : ?>
				<a class="category-pill" href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
			<?php endif; ?>
			<span class="reading-time"><?php echo esc_html( sprintf( _n( '%s min', '%s min', notescarrier_reading_time(), 'notescarrier' ), number_format_i18n( notescarrier_reading_time() ) ) ); ?></span>
		</div>
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<div class="post-excerpt"><?php the_excerpt(); ?></div>
		<div class="post-card__footer">
			<span class="post-date"><?php echo esc_html( get_the_date() ); ?></span>
			<a class="read-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'notescarrier' ); ?><span aria-hidden="true">↗</span></a>
		</div>
	</div>
</article>
