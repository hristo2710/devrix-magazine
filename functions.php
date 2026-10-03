<?php
// Include custom post types, taxonomies, and article meta fields.
require_once get_template_directory() . '/inc/post-types.php';
require_once get_template_directory() . '/inc/taxonomies.php';
require_once get_template_directory() . '/inc/article-meta.php';

// Theme setup, including support for title tag and post thumbnails.
function devrix_magazine_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menus( array(
        'primary' => 'Primary Navigation',
    ));
}

// Enqueue theme assets (CSS and JS) for the front-end.
function devrix_magazine_assets() {
    $base_css_path           = get_template_directory() . '/assets/css/base.css';
    $header_css_path         = get_template_directory() . '/assets/css/header.css';
    $footer_css_path         = get_template_directory() . '/assets/css/footer.css';
    $front_page_css_path     = get_template_directory() . '/assets/css/front-page.css';
    $single_article_css_path = get_template_directory() . '/assets/css/single-article.css';
    $main_js_path            = get_template_directory() . '/assets/js/main.js';

    wp_enqueue_style(
        'devrix-base-style',
        get_template_directory_uri() . '/assets/css/base.css',
        array(),
        file_exists( $base_css_path ) ? filemtime( $base_css_path ) : false
    );

    wp_enqueue_style(
        'devrix-header-style',
        get_template_directory_uri() . '/assets/css/header.css',
        array( 'devrix-base-style' ),
        file_exists( $header_css_path ) ? filemtime( $header_css_path ) : false
    );

    wp_enqueue_style(
        'devrix-footer-style',
        get_template_directory_uri() . '/assets/css/footer.css',
        array( 'devrix-base-style' ),
        file_exists( $footer_css_path ) ? filemtime( $footer_css_path ) : false
    );

    wp_enqueue_style(
        'devrix-google-fonts',
        'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700;800;900&display=swap',
        array(),
        null
    );

    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css',
        array(),
        '6.7.2'
    );

    if ( is_front_page() ) {
        wp_enqueue_style(
            'devrix-front-page-style',
            get_template_directory_uri() . '/assets/css/front-page.css',
            array( 'devrix-base-style' ),
            file_exists( $front_page_css_path ) ? filemtime( $front_page_css_path ) : false
        );
    }

    if (is_singular('article')) {
        wp_enqueue_style(
            'devrix-single-article',
            get_template_directory_uri() . '/assets/css/single-article.css',
            array(),
            file_exists( $single_article_css_path ) ? filemtime( $single_article_css_path ) : false
        );
    }

    wp_enqueue_script(
        'devrix-main-script',
        get_template_directory_uri() . '/assets/js/main.js',
        array(),
        file_exists( $main_js_path ) ? filemtime( $main_js_path ) : false,
        true
    );
}

// Enqueue the article settings script for the block editor when editing articles.
function devrix_enqueue_article_settings() {

    $screen = get_current_screen();

    if (
        ! $screen ||
        $screen->post_type !== 'article' ||
        $screen->base !== 'post'
    ) {
        return;
    }

    $article_settings_path = get_template_directory() . '/assets/js/article-settings.js';

    wp_enqueue_script(
        'devrix-article-settings',
        get_template_directory_uri() . '/assets/js/article-settings.js',
        array(
            'wp-plugins',
            'wp-edit-post',
            'wp-components',
            'wp-data',
            'wp-element',
        ),
        file_exists( $article_settings_path ) ? filemtime( $article_settings_path ) : false,
        true
    );
}

add_action('after_setup_theme', 'devrix_magazine_setup');
add_action('wp_enqueue_scripts', 'devrix_magazine_assets');
add_action('enqueue_block_editor_assets', 'devrix_enqueue_article_settings');

// Live search REST API endpoint.
add_action('rest_api_init', function () {
    register_rest_route('devrix/v1', '/search', [
        'methods'             => 'GET',
        'callback'            => 'devrix_live_search',
        'permission_callback' => '__return_true',
        'args'                => [
            'term' => [
                'sanitize_callback' => 'sanitize_text_field',
            ],
        ],
    ]);
});

function devrix_live_search($request) {
    $term = trim($request->get_param('term') ?? '');

    if (mb_strlen($term) < 2) {
        return rest_ensure_response([]);
    }

    $query = new WP_Query([
        'post_type'           => 'article',
        'post_status'         => 'publish',
        'posts_per_page'      => 5,
        's'                   => $term,
        'ignore_sticky_posts' => true,
    ]);

    $results = [];

    foreach ($query->posts as $article) {
        $categories = get_the_terms($article->ID, 'article_category');

        $results[] = [
            'title'    => get_the_title($article->ID),
            'url'      => get_permalink($article->ID),
            'image'    => get_the_post_thumbnail_url(
                $article->ID,
                'thumbnail'
            ) ?: '',
            'category' => (
                !is_wp_error($categories) && !empty($categories)
            ) ? $categories[0]->name : '',
        ];
    }

    return rest_ensure_response($results);
}

require_once get_template_directory() . '/inc/devrix-demo-image-import.php';