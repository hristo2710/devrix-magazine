<?php
// Register the 'article_category' taxonomy for the 'article' post type 
function devrix_register_article_categories() {
    register_taxonomy('article_category', array('article'), array(
        'labels' => array(
            'name' => 'Article Categories',
            'singular_name' => 'Article Category'
        ),
        'public' => true,
        'hierarchical' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'rewrite' => array(
            'slug' => 'article-category'
        )
    ));
}

add_action('init', 'devrix_register_article_categories');