<?php
/**
 * Comments template.
 *
 * @package NotesCarrier
 */
if ( post_password_required() ) {
	return;
}
?>
<section class="comments-area" id="comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			printf(
				/* translators: %s is the number of comments. */
				esc_html( _nx( '%s response', '%s responses', get_comments_number(), 'comments title', 'notescarrier' ) ),
				esc_html( number_format_i18n( get_comments_number() ) )
			);
			?>
		</h2>
		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size'=> 42,
				)
			);
			?>
		</ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>
	<?php
	comment_form(
		array(
			'title_reply'          => esc_html__( 'Leave a response', 'notescarrier' ),
			'class_form'           => 'comment-form',
			'class_submit'         => 'button button--dark',
			'label_submit'         => esc_html__( 'Post comment', 'notescarrier' ),
			'comment_notes_before' => '<p class="comment-notes">' . esc_html__( 'Your email address will not be published.', 'notescarrier' ) . '</p>',
		)
	);
	?>
</section>
