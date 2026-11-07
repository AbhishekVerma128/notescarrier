<?php
/**
 * NotesCarrier theme setup and helpers.
 *
 * @package NotesCarrier
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NOTESCARRIER_VERSION', '1.2.1' );

/**
 * Set up theme defaults and register support for WordPress features.
 */
function notescarrier_setup() {
	load_theme_textdomain( 'notescarrier', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support(
		'html5',
		array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
			'search-form',
		)
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 64,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary Menu', 'notescarrier' ),
			'footer'  => esc_html__( 'Footer Menu', 'notescarrier' ),
		)
	);

	add_image_size( 'notescarrier-featured', 1200, 780, true );
	add_image_size( 'notescarrier-card', 520, 360, true );
	add_image_size( 'notescarrier-small', 160, 120, true );
}
add_action( 'after_setup_theme', 'notescarrier_setup' );

/**
 * Load the theme stylesheet and small menu script.
 */
function notescarrier_enqueue_assets() {
	$theme_version = wp_get_theme()->get( 'Version' );
	wp_enqueue_style(
		'notescarrier-theme',
		get_template_directory_uri() . '/assets/css/theme.css',
		array(),
		$theme_version ? $theme_version : NOTESCARRIER_VERSION
	);
	wp_enqueue_script(
		'notescarrier-theme',
		get_template_directory_uri() . '/assets/js/theme.js',
		array(),
		$theme_version ? $theme_version : NOTESCARRIER_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'notescarrier_enqueue_assets' );

/**
 * Add a restrained editor palette matching the front end.
 */
function notescarrier_editor_styles() {
	add_editor_style( 'assets/css/theme.css' );
}
add_action( 'admin_init', 'notescarrier_editor_styles' );

/**
 * Return the first category for compact card labels.
 *
 * @param int|null $post_id Post ID.
 * @return WP_Term|false
 */
function notescarrier_primary_category( $post_id = null ) {
	$categories = get_the_category( $post_id );
	return ! empty( $categories ) ? $categories[0] : false;
}

/**
 * Return a readable estimate based on the post's word count.
 *
 * @param int|null $post_id Post ID.
 * @return int
 */
function notescarrier_reading_time( $post_id = null ) {
	$post = get_post( $post_id );
	if ( ! $post ) {
		return 1;
	}

	$word_count = str_word_count( wp_strip_all_tags( $post->post_content ) );
	return max( 1, (int) ceil( $word_count / 220 ) );
}

/**
 * Add a modest excerpt length for archive cards.
 *
 * @return int
 */
function notescarrier_excerpt_length() {
	return 24;
}
add_filter( 'excerpt_length', 'notescarrier_excerpt_length', 999 );

/**
 * Use a trailing ellipsis on excerpts.
 *
 * @return string
 */
function notescarrier_excerpt_more() {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'notescarrier_excerpt_more' );

/**
 * Add a body class for the home feed layout.
 *
 * @param array $classes Existing body classes.
 * @return array
 */
function notescarrier_body_classes( $classes ) {
	if ( is_front_page() || is_home() ) {
		$classes[] = 'notescarrier-home';
	}
	return $classes;
}
add_filter( 'body_class', 'notescarrier_body_classes' );

/**
 * Render pasted WordPress download-button HTML when it is inside a Code block.
 *
 * Normal code samples remain code. Only code blocks containing the standard
 * WordPress button wrapper are converted, and the decoded markup is sanitized.
 *
 * @param string $block_content Rendered block HTML.
 * @param array  $block         Parsed block data.
 * @return string
 */
function notescarrier_render_download_button_code_block( $block_content, $block = array() ) {
	if ( empty( $block['blockName'] ) || 'core/code' !== $block['blockName'] ) {
		return $block_content;
	}

	if ( ! preg_match( '~<code\b[^>]*>(.*?)</code>~is', $block_content, $matches ) ) {
		return $block_content;
	}

	$decoded_markup = trim( html_entity_decode( $matches[1], ENT_QUOTES | ENT_HTML5, 'UTF-8' ) );
	if (
		false === stripos( $decoded_markup, 'wp-block-buttons' )
		|| false === stripos( $decoded_markup, 'wp-block-button__link' )
	) {
		return $block_content;
	}

	$rendered_markup = wp_kses( $decoded_markup, wp_kses_allowed_html( 'post' ) );
	if ( false === stripos( $rendered_markup, 'wp-block-button__link' ) ) {
		return $block_content;
	}

	return '<div class="wp-block-html-button-converted">' . $rendered_markup . '</div>';
}
add_filter( 'render_block', 'notescarrier_render_download_button_code_block', 10, 2 );

/**
 * Render a useful page menu until a custom menu has been assigned.
 *
 * @param array $args Menu arguments.
 * @return string
 */
function notescarrier_page_menu_fallback( $args ) {
	if ( is_object( $args ) ) {
		$args = (array) $args;
	}
	$menu_class = isset( $args['menu_class'] ) ? $args['menu_class'] : 'primary-menu';
	$pages      = get_pages(
		array(
			'parent'      => 0,
			'sort_column' => 'menu_order,post_title',
			'post_status' => 'publish',
		)
	);
	$items = '<li class="menu-item menu-item-home"><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'notescarrier' ) . '</a></li>';

	foreach ( $pages as $page ) {
		if ( (int) get_option( 'page_on_front' ) === (int) $page->ID ) {
			continue;
		}
		$items .= '<li class="menu-item page_item"><a href="' . esc_url( get_permalink( $page ) ) . '">' . esc_html( get_the_title( $page ) ) . '</a></li>';
	}

	return '<ul class="' . esc_attr( $menu_class ) . '">' . $items . '</ul>';
}

/**
 * Get a permalink for the Latest Notification category.
 *
 * @return string
 */
function notescarrier_latest_notification_url() {
	$category = get_category_by_slug( 'latest-notification' );
	return $category ? get_category_link( $category->term_id ) : home_url( '/category/latest-notification/' );
}

/**
 * Return the five newest posts in the Latest Notification category.
 *
 * @return WP_Post[]
 */
function notescarrier_latest_notification_posts() {
	static $latest_posts = null;
	if ( null !== $latest_posts ) {
		return $latest_posts;
	}
	$category    = get_category_by_slug( 'latest-notification' );
	if ( ! $category ) {
		$latest_posts = array();
		return $latest_posts;
	}

	$latest_posts = get_posts(
		array(
			'numberposts'      => 5,
			'category'         => (int) $category->term_id,
			'post_type'        => 'post',
			'post_status'      => 'publish',
			'orderby'          => 'date',
			'order'            => 'DESC',
			'suppress_filters' => false,
		)
	);

	return $latest_posts;
}

/**
 * Return the newest post in the Latest Notification category.
 *
 * @return WP_Post|null
 */
function notescarrier_latest_notification_post() {
	$posts = notescarrier_latest_notification_posts();
	return ! empty( $posts ) ? $posts[0] : null;
}

/**
 * Add configurable community popup options.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function notescarrier_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'notescarrier_community_popup',
		array(
			'title'       => esc_html__( 'NotesCarrier Community Popup', 'notescarrier' ),
			'description' => esc_html__( 'Set the social links and display timing for the community popup.', 'notescarrier' ),
			'priority'    => 160,
		)
	);

	$wp_customize->add_setting(
		'notescarrier_popup_enabled',
		array(
			'default'           => true,
			'sanitize_callback' => 'wp_validate_boolean',
		)
	);
	$wp_customize->add_control(
		'notescarrier_popup_enabled',
		array(
			'label'   => esc_html__( 'Show the community popup', 'notescarrier' ),
			'section' => 'notescarrier_community_popup',
			'type'    => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'notescarrier_popup_delay',
		array(
			'default'           => 6,
			'sanitize_callback' => 'notescarrier_sanitize_popup_delay',
		)
	);
	$wp_customize->add_control(
		'notescarrier_popup_delay',
		array(
			'label'       => esc_html__( 'Popup delay in seconds', 'notescarrier' ),
			'description' => esc_html__( 'Choose a delay from 0 to 60 seconds.', 'notescarrier' ),
			'section'     => 'notescarrier_community_popup',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 60,
				'step' => 1,
			),
		)
	);

	$channels = array(
		'notescarrier_telegram_url' => array(
			'label'   => esc_html__( 'Telegram channel URL', 'notescarrier' ),
			'default' => 'https://t.me/notescarrier3',
		),
		'notescarrier_youtube_url'  => array(
			'label'   => esc_html__( 'YouTube channel URL', 'notescarrier' ),
			'default' => '',
		),
	);
	foreach ( $channels as $setting_id => $channel ) {
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => $channel['default'],
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			$setting_id,
			array(
				'label'   => $channel['label'],
				'section' => 'notescarrier_community_popup',
				'type'    => 'url',
			)
		);
	}
}
add_action( 'customize_register', 'notescarrier_customize_register' );

/**
 * Clamp a user-selected popup delay to a safe range.
 *
 * @param mixed $value Delay in seconds.
 * @return int
 */
function notescarrier_sanitize_popup_delay( $value ) {
	return min( 60, max( 0, absint( $value ) ) );
}

/**
 * Check whether a common SEO plugin already owns meta and social tags.
 *
 * @return bool
 */
function notescarrier_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' )
		|| defined( 'RANK_MATH_VERSION' )
		|| defined( 'AIOSEO_VERSION' )
		|| defined( 'SEOPRESS_VERSION' )
		|| defined( 'THE_SEO_FRAMEWORK_VERSION' );
}

/**
 * Build a concise, page-specific meta description.
 *
 * @return string
 */
function notescarrier_meta_description() {
	if ( is_singular() ) {
		$post = get_queried_object();
		if ( $post instanceof WP_Post ) {
			$content = has_excerpt( $post ) ? get_the_excerpt( $post ) : $post->post_content;
			$content = strip_shortcodes( $content );
			$content = wp_strip_all_tags( $content );
			$content = preg_replace( '/\s+/', ' ', $content );
			return trim( wp_html_excerpt( $content, 158, '…' ) );
		}
	}

	if ( is_category() || is_tag() || is_tax() ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term && ! empty( $term->description ) ) {
			return trim( wp_html_excerpt( wp_strip_all_tags( $term->description ), 158, '…' ) );
		}
		if ( $term instanceof WP_Term ) {
			return sprintf(
				/* translators: %s is the archive topic. */
				esc_html__( 'Browse study notes, book reviews, and exam resources about %s on NotesCarrier.', 'notescarrier' ),
				$term->name
			);
		}
	}

	if ( is_search() ) {
		return sprintf(
			/* translators: %s is the search phrase. */
			esc_html__( 'Search NotesCarrier study notes, book reviews, and exam resources for %s.', 'notescarrier' ),
			get_search_query()
		);
	}

	$description = get_bloginfo( 'description', 'display' );
	if ( ! $description || 'Just another WordPress site' === $description ) {
		$description = esc_html__( 'Study notes, book reviews, and exam resources to help you prepare with clarity and confidence.', 'notescarrier' );
	}
	return trim( wp_html_excerpt( wp_strip_all_tags( $description ), 158, '…' ) );
}

/**
 * Return the canonical display URL for the current view.
 *
 * @return string
 */
function notescarrier_current_view_url() {
	if ( is_singular() ) {
		return get_permalink( get_queried_object() );
	}
	if ( is_category() ) {
		return get_category_link( get_queried_object_id() );
	}
	if ( is_tag() ) {
		return get_tag_link( get_queried_object_id() );
	}
	if ( is_tax() ) {
		$term_url = get_term_link( get_queried_object() );
		return is_wp_error( $term_url ) ? home_url( '/' ) : $term_url;
	}
	if ( is_author() ) {
		return get_author_posts_url( get_queried_object_id() );
	}
	if ( is_search() ) {
		return add_query_arg( 's', get_search_query(), home_url( '/' ) );
	}
	if ( is_paged() ) {
		return get_pagenum_link( max( 1, (int) get_query_var( 'paged' ) ) );
	}
	if ( is_home() && get_option( 'page_for_posts' ) ) {
		return get_permalink( get_option( 'page_for_posts' ) );
	}
	return home_url( '/' );
}

/**
 * Print lightweight SEO and social metadata when an SEO plugin is not active.
 */
function notescarrier_print_seo_metadata() {
	if ( notescarrier_seo_plugin_active() ) {
		return;
	}

	$title       = wp_get_document_title();
	$description = notescarrier_meta_description();
	$post        = is_singular() ? get_queried_object() : null;
	$image_url   = '';

	if ( $post instanceof WP_Post && has_post_thumbnail( $post ) ) {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post ), 'full' );
		$image_url = $image ? $image[0] : '';
	} elseif ( has_custom_logo() ) {
		$logo_id  = get_theme_mod( 'custom_logo' );
		$logo_src = wp_get_attachment_image_src( $logo_id, 'full' );
		$image_url = $logo_src ? $logo_src[0] : '';
	}

	$page_url = notescarrier_current_view_url();
	?>
	<?php if ( ! is_singular() && ! is_404() && ! is_search() ) : ?>
		<link rel="canonical" href="<?php echo esc_url( $page_url ); ?>">
	<?php endif; ?>
	<meta name="description" content="<?php echo esc_attr( $description ); ?>">
	<meta property="og:title" content="<?php echo esc_attr( $title ); ?>">
	<meta property="og:description" content="<?php echo esc_attr( $description ); ?>">
	<meta property="og:type" content="<?php echo is_singular( 'post' ) ? 'article' : 'website'; ?>">
	<meta property="og:url" content="<?php echo esc_url( $page_url ); ?>">
	<meta property="og:site_name" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
	<?php if ( $image_url ) : ?>
		<meta property="og:image" content="<?php echo esc_url( $image_url ); ?>">
		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:image" content="<?php echo esc_url( $image_url ); ?>">
	<?php else : ?>
		<meta name="twitter:card" content="summary">
	<?php endif; ?>
	<meta name="twitter:title" content="<?php echo esc_attr( $title ); ?>">
	<meta name="twitter:description" content="<?php echo esc_attr( $description ); ?>">
	<?php
	if ( is_singular( 'post' ) && $post instanceof WP_Post ) {
		$structured_data = array(
			'@context'         => 'https://schema.org',
			'@type'            => 'BlogPosting',
			'headline'         => get_the_title( $post ),
			'description'      => $description,
			'datePublished'    => get_post_time( DATE_W3C, true, $post ),
			'dateModified'     => get_post_modified_time( DATE_W3C, true, $post ),
			'mainEntityOfPage' => array(
				'@type' => 'WebPage',
				'@id'   => $page_url,
			),
			'author'           => array(
				'@type' => 'Person',
				'name'  => get_the_author_meta( 'display_name', $post->post_author ),
			),
			'publisher'        => array(
				'@type' => 'Organization',
				'name'  => get_bloginfo( 'name' ),
			),
		);
		if ( $image_url ) {
			$structured_data['image'] = array( $image_url );
		}
		?>
		<script type="application/ld+json"><?php echo wp_json_encode( $structured_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT ); ?></script>
		<?php
	} elseif ( is_front_page() || is_home() ) {
		$blog_data = array(
			'@context'    => 'https://schema.org',
			'@type'       => 'Blog',
			'name'        => get_bloginfo( 'name' ),
			'url'         => home_url( '/' ),
			'description' => $description,
			'publisher'   => array(
				'@type' => 'Organization',
				'name'  => get_bloginfo( 'name' ),
			),
		);
		?>
		<script type="application/ld+json"><?php echo wp_json_encode( $blog_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT ); ?></script>
		<?php
	}
	?>
	<?php
}
add_action( 'wp_head', 'notescarrier_print_seo_metadata', 2 );

// Exclude post tags from WordPress's built-in sitemap.
function notescarrier_exclude_post_tags_from_sitemap( $taxonomies ) {
	unset( $taxonomies['post_tag'] );
	return $taxonomies;
}
add_filter( 'wp_sitemaps_taxonomies', 'notescarrier_exclude_post_tags_from_sitemap' );

// Keep tag archive pages out of search results, but allow crawlers to follow links.
function notescarrier_noindex_tag_archives( $robots ) {
	if ( is_tag() ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}

	return $robots;
}
add_filter( 'wp_robots', 'notescarrier_noindex_tag_archives' );
