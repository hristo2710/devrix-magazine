<?php
// Register custom meta fields for the 'article' post type
function devrix_register_article_meta() {
    $meta_args = array(
        'type' => 'boolean',
        'single' => true,
        'default' => false,
        'sanitize_callback' => 'rest_sanitize_boolean',
        'show_in_rest' => array(
            'schema' => array(
                'type' => 'boolean',
                'default' => false,
            ),
        ),
    );

    register_post_meta( 'article', 'is_featured', $meta_args );
    register_post_meta( 'article', 'is_breaking', $meta_args );
}

add_action('init', 'devrix_register_article_meta');