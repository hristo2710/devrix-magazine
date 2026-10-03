<?php

function register_article() {
    register_post_type('article', array(
        'labels' => array(
            'name' => 'Articles',
            'singular_name' => 'Article'
        ),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => array(
            'title',
            'editor',
            'thumbnail',
            'custom-fields'
        )
    ));
}

add_action('init', 'register_article');