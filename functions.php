<?php

/**

 * NotesCarrier theme setup and helpers.

 *

 * @package NotesCarrier

 */



if ( ! defined( 'ABSPATH' ) ) {

    exit;

}



define( 'NOTESCARRIER_VERSION', '1.3.1' );



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

 * Send front-end page views to the NotesCarrier Google Analytics property.

 *

 * If another plugin already inserts this Google Analytics tag, disable one

 * installation to avoid duplicate page views.

 */

function notescarrier_google_analytics_tag() {

    if ( is_admin() ) {

        return;

    }

    ?>

    <!-- Google tag for NotesCarrier Analytics -->

    <script async src="<?php echo esc_url( 'https://www.googletagmanager.com/gtag/js?id=GT-P3HVX3TS' ); ?>"></script>

    <script>

        window.dataLayer = window.dataLayer || [];

        function gtag(){dataLayer.push(arguments);}

        gtag('js', new Date());

        gtag('config', 'G-MW52KY707N');

    </script>

    <?php

}

add_action( 'wp_head', 'notescarrier_google_analytics_tag', 1 );



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

 * Check whether the current view is the site's main homepage.

 *

 * @return bool

 */

function notescarrier_is_homepage_view() {

    return is_front_page() || ( is_home() && ! get_option( 'page_on_front' ) );

}



/**

 * Return the homepage title used in search results.

 *

 * @return string

 */

function notescarrier_homepage_seo_title() {

    return __( 'Free Exam PDFs for SSC, Railway, Banking & UPSSSC | NotesCarrier', 'notescarrier' );

}



/**

 * Use a specific homepage title in WordPress and supported SEO plugins.

 *

 * @param string $title Existing document title.

 * @return string

 */

function notescarrier_filter_homepage_seo_title( $title ) {

    return notescarrier_is_homepage_view() ? notescarrier_homepage_seo_title() : $title;

}

add_filter( 'pre_get_document_title', 'notescarrier_filter_homepage_seo_title', 20 );

add_filter( 'rank_math/frontend/title', 'notescarrier_filter_homepage_seo_title', 20 );

add_filter( 'wpseo_title', 'notescarrier_filter_homepage_seo_title', 20 );



/**

 * Use a descriptive homepage snippet in supported SEO plugins.

 *

 * @param string $description Existing meta description.

 * @return string

 */

function notescarrier_filter_homepage_seo_description( $description ) {

    if ( notescarrier_is_homepage_view() ) {

        return __( 'Free exam notes, book PDFs and study guides for SSC, Railway, Banking and UPSSSC exams. Read preparation tips and get the latest exam updates on NotesCarrier.', 'notescarrier' );

    }

    return $description;

}

add_filter( 'rank_math/frontend/description', 'notescarrier_filter_homepage_seo_description', 20 );

add_filter( 'wpseo_metadesc', 'notescarrier_filter_homepage_seo_description', 20 );



/**

 * Build a concise, page-specific meta description.

 *

 * @return string

 */

function notescarrier_meta_description() {

    if ( notescarrier_is_homepage_view() ) {

        return notescarrier_filter_homepage_seo_description( '' );

    }



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



    $title       = notescarrier_is_homepage_view() ? notescarrier_homepage_seo_title() : wp_get_document_title();

    $description = notescarrier_meta_description();

    $post        = is_singular() ? get_queried_object() : null;

    $image_url   = '';

    $image_alt   = '';

    $image_width = 0;

    $image_height = 0;

    $image_id    = 0;

    $share_post  = null;



    if ( $post instanceof WP_Post && has_post_thumbnail( $post ) ) {

        $image_id = get_post_thumbnail_id( $post );

    } elseif ( notescarrier_is_homepage_view() ) {

        $share_post = notescarrier_latest_notification_post();

        if ( $share_post && has_post_thumbnail( $share_post ) ) {

            $image_id = get_post_thumbnail_id( $share_post );

        }

    }

    if ( ! $image_id && has_custom_logo() ) {

        $logo_id  = get_theme_mod( 'custom_logo' );

        $image_id = absint( $logo_id );

    }

    if ( $image_id ) {

        $image = wp_get_attachment_image_src( $image_id, 'full' );

        if ( $image ) {

            $image_url    = $image[0];

            $image_width  = (int) $image[1];

            $image_height = (int) $image[2];

            $image_alt    = trim( (string) get_post_meta( $image_id, '_wp_attachment_image_alt', true ) );

        }

    }

    if ( '' === $image_alt ) {

        if ( $post instanceof WP_Post ) {

            $image_alt = get_the_title( $post );

        } elseif ( $share_post instanceof WP_Post ) {

            $image_alt = get_the_title( $share_post );

        } else {

            $image_alt = get_bloginfo( 'name' );

        }

    }



    $page_url = notescarrier_current_view_url();

    $locale   = 'hi_IN';

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

    <meta property="og:locale" content="<?php echo esc_attr( $locale ); ?>">

    <?php if ( $image_url ) : ?>

        <meta property="og:image" content="<?php echo esc_url( $image_url ); ?>">

        <meta property="og:image:alt" content="<?php echo esc_attr( $image_alt ); ?>">

        <?php if ( $image_width && $image_height ) : ?>

            <meta property="og:image:width" content="<?php echo esc_attr( (string) $image_width ); ?>">

            <meta property="og:image:height" content="<?php echo esc_attr( (string) $image_height ); ?>">

        <?php endif; ?>

        <meta name="twitter:card" content="summary_large_image">

        <meta name="twitter:image" content="<?php echo esc_url( $image_url ); ?>">

    <?php else : ?>

        <meta name="twitter:card" content="summary">

    <?php endif; ?>

    <meta name="twitter:title" content="<?php echo esc_attr( $title ); ?>">

    <meta name="twitter:description" content="<?php echo esc_attr( $description ); ?>">

    <?php

    if ( is_singular( 'post' ) && $post instanceof WP_Post ) {

        $post_url        = get_permalink( $post );
        $organization_id = home_url( '/#organization' );
        $article_id      = $post_url . '#article';

        // Organization entity shared by article and book schema.
        $organization = array(
            '@type' => 'Organization',
            '@id'   => $organization_id,
            'name'  => get_bloginfo( 'name' ),
            'url'   => home_url( '/' ),
        );

        if ( has_custom_logo() ) {
            $logo_id  = get_theme_mod( 'custom_logo' );
            $logo_url = wp_get_attachment_image_url( $logo_id, 'full' );

            if ( $logo_url ) {
                $organization['logo'] = array(
                    '@type' => 'ImageObject',
                    'url'   => $logo_url,
                );
            }
        }

        // BlogPosting schema for every standard WordPress post.
        $article = array(
            '@type'            => 'BlogPosting',
            '@id'              => $article_id,
            'headline'         => html_entity_decode( get_the_title( $post ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ),
            'description'      => html_entity_decode( wp_strip_all_tags( $description ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ),
            'datePublished'    => get_post_time( DATE_W3C, true, $post ),
            'dateModified'     => get_post_modified_time( DATE_W3C, true, $post ),
            'mainEntityOfPage' => array(
                '@type' => 'WebPage',
                '@id'   => $post_url,
            ),
            'author'           => array(
                '@type' => 'Person',
                'name'  => get_the_author_meta( 'display_name', $post->post_author ),
            ),
            'publisher'        => array(
                '@id' => $organization_id,
            ),
        );

        if ( $image_url ) {
            $article['image'] = array(
                '@type' => 'ImageObject',
                'url'   => $image_url,
            );
        }

        $schema_graph = array(
            $organization,
            $article,
        );

        // Add Book schema only to posts in the books-pdf category.
        if ( has_category( 'books-pdf', $post->ID ) ) {
            $book_id = $post_url . '#book';

            $book = array(
                '@type'                 => 'Book',
                '@id'                   => $book_id,
                'name'                  => html_entity_decode( get_the_title( $post ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ),
                'url'                   => $post_url,
                'description'           => html_entity_decode( wp_strip_all_tags( $description ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ),
                'bookFormat'            => 'https://schema.org/EBook',
                'learningResourceType'  => 'Study Guide',
                'isAccessibleForFree'  => true,
				'inLanguage'           => 'hi',
                'publisher'             => array(
                    '@id' => $organization_id,
                ),
                'offers'                => array(
                    '@type'         => 'Offer',
                    'url'           => $post_url,
                    'price'         => '0',
                    'priceCurrency' => 'INR',
                    'availability'  => 'https://schema.org/InStock',
                ),
            );

            if ( $image_url ) {
                $book['image'] = $image_url;
            }

            // Extract every Google Drive PDF link from this post's content.
            // Supports links used in Gutenberg Button blocks and ordinary anchors.
            $pdf_media_objects = array();
            $post_content      = (string) get_post_field( 'post_content', $post->ID );

            if ( preg_match_all( "~href\\s*=\\s*([\"'])(.*?)\\1~is", $post_content, $href_matches ) ) {
                $seen_drive_urls = array();

                foreach ( $href_matches[2] as $raw_href ) {
                    $drive_url = html_entity_decode(
                        trim( $raw_href ),
                        ENT_QUOTES | ENT_HTML5,
                        'UTF-8'
                    );

                    $drive_host = wp_parse_url( $drive_url, PHP_URL_HOST );
                    $drive_path = wp_parse_url( $drive_url, PHP_URL_PATH );
                    $drive_query = wp_parse_url( $drive_url, PHP_URL_QUERY );

                    if ( ! $drive_host || 'drive.google.com' !== strtolower( $drive_host ) ) {
                        continue;
                    }

                    // Only accept recognizable Google Drive file URLs.
                    $drive_params = array();
                    if ( $drive_query ) {
                        parse_str( $drive_query, $drive_params );
                    }

                    $has_file_id = ! empty( $drive_params['id'] )
                        || ( $drive_path && preg_match( '~^/file/d/[^/]+~', $drive_path ) );

                    if ( ! $has_file_id || isset( $seen_drive_urls[ $drive_url ] ) ) {
                        continue;
                    }

                    $seen_drive_urls[ $drive_url ] = true;

                    $pdf_media_objects[] = array(
                        '@type'          => 'MediaObject',
                        'contentUrl'     => esc_url_raw( $drive_url ),
                        'encodingFormat' => 'application/pdf',
                    );
                }
            }

            if ( ! empty( $pdf_media_objects ) ) {
                $book['encoding'] = $pdf_media_objects;
            }

            // Connect the article to the book described by this page.
            $article['about'] = array(
                '@id' => $book_id,
            );

            $schema_graph[1] = $article;
            $schema_graph[]  = $book;
        }

        $structured_data = array(
            '@context' => 'https://schema.org',
            '@graph'   => $schema_graph,
        );
        ?>
        <script type="application/ld+json"><?php echo wp_json_encode( $structured_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT ); ?></script>
        <?php
    } elseif ( is_front_page() || is_home() ) {

        $organization_id = home_url( '/#organization' );

        $website_id      = home_url( '/#website' );

        $blog_data       = array(

            '@context' => 'https://schema.org',

            '@graph'   => array(

                array(

                    '@type' => 'Organization',

                    '@id'   => $organization_id,

                    'name'  => get_bloginfo( 'name' ),

                    'url'   => home_url( '/' ),

                ),

                array(

                    '@type'      => 'WebSite',

                    '@id'        => $website_id,

                    'url'        => home_url( '/' ),

                    'name'       => get_bloginfo( 'name' ),

                    'inLanguage' => get_bloginfo( 'language' ),

                    'publisher'  => array( '@id' => $organization_id ),

                ),

                array(

                    '@type'       => 'Blog',

                    '@id'         => home_url( '/#blog' ),

                    'name'        => get_bloginfo( 'name' ),

                    'url'         => home_url( '/' ),

                    'description' => $description,

                    'inLanguage'  => get_bloginfo( 'language' ),

                    'isPartOf'    => array( '@id' => $website_id ),

                    'publisher'   => array( '@id' => $organization_id ),

                ),

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

// Remove WordPress's users sitemap provider.

function notescarrier_remove_users_sitemap( $provider, $name ) {

    if ( 'users' === $name ) {

        return false;

    }



    return $provider;

}

add_filter( 'wp_sitemaps_add_provider', 'notescarrier_remove_users_sitemap', 10, 2 );

/**
 * Return Google AdSense responsive ad units for NotesCarrier.
 *
 * @param string  Type of ad slot: header, article_top, article_mid1, article_mid2, article_bottom, feed, sidebar.
 * @return string HTML markup for the ad unit.
 */
function notescarrier_get_ad_unit(  = 'article_mid1' ) {
	 = 'ca-pub-4172398708042636';

	switch (  ) {
		case 'header':
			return '<div class="nc-ad-wrapper nc-ad-header-banner"><span class="nc-ad-label">' . esc_html__( 'Advertisement', 'notescarrier' ) . '</span><!-- header_468x60_as --><ins class="adsbygoogle" style="display:inline-block;width:468px;height:60px" data-ad-client="' . esc_attr(  ) . '" data-ad-slot="2240870246"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script></div>';

		case 'article_top':
			return '<div class="nc-ad-wrapper nc-ad-in-article nc-ad-top"><span class="nc-ad-label">' . esc_html__( 'Advertisement', 'notescarrier' ) . '</span><ins class="adsbygoogle" style="display:block" data-ad-client="' . esc_attr(  ) . '" data-ad-slot="6450798201" data-ad-format="auto" data-full-width-responsive="true"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script></div>';

		case 'article_mid1':
			return '<div class="nc-ad-wrapper nc-ad-in-article nc-ad-mid1"><span class="nc-ad-label">' . esc_html__( 'Advertisement', 'notescarrier' ) . '</span><ins class="adsbygoogle" style="display:block" data-ad-client="' . esc_attr(  ) . '" data-ad-slot="6450798201" data-ad-format="auto" data-full-width-responsive="true"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script></div>';

		case 'article_mid2':
			return '<div class="nc-ad-wrapper nc-ad-in-article nc-ad-mid2"><span class="nc-ad-label">' . esc_html__( 'Advertisement', 'notescarrier' ) . '</span><ins class="adsbygoogle" style="display:block" data-ad-client="' . esc_attr(  ) . '" data-ad-slot="1109212273" data-ad-format="auto" data-full-width-responsive="true"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script></div>';

		case 'article_bottom':
			return '<div class="nc-ad-wrapper nc-ad-multiplex nc-ad-bottom"><span class="nc-ad-label">' . esc_html__( 'Sponsored Resources', 'notescarrier' ) . '</span><ins class="adsbygoogle" style="display:block" data-ad-format="autorelaxed" data-ad-client="' . esc_attr(  ) . '" data-ad-slot="3760795920"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script></div>';

		case 'feed':
			return '<div class="nc-ad-wrapper nc-ad-feed"><span class="nc-ad-label">' . esc_html__( 'Advertisement', 'notescarrier' ) . '</span><ins class="adsbygoogle" style="display:block" data-ad-client="' . esc_attr(  ) . '" data-ad-slot="6450798201" data-ad-format="auto" data-full-width-responsive="true"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script></div>';

		case 'sidebar':
			return '<div class="nc-ad-wrapper nc-ad-sidebar"><span class="nc-ad-label">' . esc_html__( 'Advertisement', 'notescarrier' ) . '</span><ins class="adsbygoogle" style="display:block" data-ad-client="' . esc_attr(  ) . '" data-ad-slot="1109212273" data-ad-format="auto" data-full-width-responsive="true"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script></div>';

		default:
			return '';
	}
}

/**
 * Automatically insert in-article ads into single post content.
 *
 * @param string  Post content.
 * @return string Modified content with responsive ads.
 */
function notescarrier_insert_article_ads(  ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return ;
	}

	 = explode( '</p>',  );
	    = count(  ) - 1;

	   = notescarrier_get_ad_unit( 'article_mid1' );
	   = notescarrier_get_ad_unit( 'article_mid2' );
	 = notescarrier_get_ad_unit( 'article_bottom' );

	if (  <= 1 ) {
		return  . ;
	}

	 = 2; // after 2nd paragraph
	 = (  >= 6 ) ? (int) floor(  * 0.65 ) : ( (  >= 4 ) ? 4 : 0 );

	 = '';
	foreach (  as  =>  ) {
		   =  + 1;
		 .= ;
		if (  <  ) {
			 .= '</p>';
		}

		if (  ===  ) {
			 .= ;
		} elseif (  > 0 &&  ===  ) {
			 .= ;
		}
	}

	 .= ;

	return ;
}
add_filter( 'the_content', 'notescarrier_insert_article_ads', 20 );
