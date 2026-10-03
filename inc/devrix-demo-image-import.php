<?php
/**
 * One-time demo featured-image importer.
 * Place in theme/inc/demo-image-import.php and require it from functions.php.
 */
if (!defined('ABSPATH')) {
    exit;
}

function devrix_demo_image_map() {
    return array(
        'malaria-vaccine-to-be-tested-on-4800-children-uk-scientist' => 'featured.jpg',
        'breaking-news' => 'breaking.jpg',
        'et-ipsam-deleniti-mollitia-iure-et-incidunt-assumenda-expedita' => 'news4.jpg',
        'et-ipsam-deleniti-mollitia-iure' => 'news3.jpg',
        'asperiores-sapiente-alias-voluptas-quasi-ratione-quae-dolorem' => 'news2.jpg',
        'asperiores-sapiente-alias-voluptas-quasi-ratione-quae-dolorem-2' => 'news1.jpg',
        'at-doloremque-id-voluptatum-dignissimos-unde-est-voluptatem' => 'news5.jpg',
        'asperiores-sapiente-alias-voluptas-quasi-ratione-quae-dolorem-3' => 'sex3.jpg',
        'asperiores-sapiente-alias-voluptas-quasi-ratione-quae-dolorem-4' => 'sex2.jpg',
        'asperiores-sapiente-alias-voluptas-quasi-ratione-quae-dolorem-5' => 'sex1.jpg',
        'asperiores-sapiente-alias-voluptas-quasi-ratione-quae-dolorem-6' => 'tech1.jpg',
        'asperiores-sapiente-alias-voluptas-quasi-ratione-quae-dolorem-7' => 'tech2.jpg',
        'et-ipsam-deleniti-mollitia-iure-2' => 'tech3.jpg',
        'et-ipsam-deleniti-mollitia-iure-et-incidunt-assumenda-expedita-2' => 'tech4.jpg',
        'at-doloremque-id-voluptatum-dignissimos-unde-est-voluptatem-2' => 'tech5.jpg',
        'asperiores-sapiente-alias-voluptas-quasi-ratione-quae-dolorem-8' => 'sport4.jpg',
        'asperiores-sapiente-alias-voluptas-quasi-ratione-quae-dolorem-9' => 'sport3.jpg',
        'asperiores-sapiente-alias-voluptas-quasi-ratione-quae-dolorem-10' => 'sport2.jpg',
        'asperiores-sapiente-alias-voluptas-quasi-ratione-quae-dolorem-11' => 'sport1.jpg',
        'asperiores-sapiente-alias-voluptas-quasi-ratione-quae-dolorem-12' => 'health3.png',
        'asperiores-sapiente-alias-voluptas-quasi-ratione-quae-dolorem-13' => 'health2.png',
        'asperiores-sapiente-alias-voluptas-quasi-ratione-quae-dolorem-14' => 'health1.png',
    );
}

add_action('admin_menu', function () {
    add_management_page(
        'Import Demo Images',
        'Import Demo Images',
        'manage_options',
        'devrix-import-demo-images',
        'devrix_render_demo_image_import'
    );
});

function devrix_render_demo_image_import() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have permission to import images.');
    }
    echo '<div class="wrap"><h1>Import Demo Images</h1>';
    echo '<p>Run this after importing the WordPress demo XML. Images are loaded from demo-content/images in the active theme.</p>';

    if (isset($_POST['devrix_import_images'])) {
        check_admin_referer('devrix_import_demo_images');
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';

        $base = get_template_directory() . '/demo-content/images/';
        $created = 0;
        $linked = 0;
        $skipped = 0;
        $errors = array();

        foreach (devrix_demo_image_map() as $slug => $filename) {
            $posts = get_posts(array(
                'post_type' => 'article',
                'name' => $slug,
                'post_status' => 'any',
                'posts_per_page' => 2,
            ));
            if (count($posts) !== 1) {
                $errors[] = 'Article not found or ambiguous: ' . $slug;
                continue;
            }
            $post_id = $posts[0]->ID;
            $existing = get_post_thumbnail_id($post_id);
            if ($existing && get_attached_file($existing) && file_exists(get_attached_file($existing))) {
                $skipped++;
                continue;
            }
            $source = $base . $filename;
            if (!is_readable($source)) {
                $errors[] = 'Missing image: ' . $filename;
                continue;
            }
            // Reuse a previous import, even if another article uses the same image.
            $found = get_posts(array(
                'post_type' => 'attachment',
                'post_status' => 'inherit',
                'meta_key' => '_devrix_demo_filename',
                'meta_value' => $filename,
                'posts_per_page' => 1,
                'fields' => 'ids',
            ));
            $attachment_id = $found ? (int) $found[0] : 0;
            if ($attachment_id && (!get_attached_file($attachment_id) || !file_exists(get_attached_file($attachment_id)))) {
                $attachment_id = 0;
            }
            if (!$attachment_id) {
                $upload = wp_upload_bits($filename, null, file_get_contents($source));
                if (!empty($upload['error'])) {
                    $errors[] = $filename . ': ' . $upload['error'];
                    continue;
                }
                $filetype = wp_check_filetype($upload['file']);
                $attachment_id = wp_insert_attachment(array(
                    'post_mime_type' => $filetype['type'],
                    'post_title' => pathinfo($filename, PATHINFO_FILENAME),
                    'post_status' => 'inherit',
                ), $upload['file']);
                if (is_wp_error($attachment_id)) {
                    $errors[] = $filename . ': ' . $attachment_id->get_error_message();
                    continue;
                }
                wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $upload['file']));
                update_post_meta($attachment_id, '_devrix_demo_filename', $filename);
                $created++;
            }
            if (set_post_thumbnail($post_id, $attachment_id)) {
                $linked++;
            } else {
                $errors[] = 'Could not assign image to article: ' . $slug;
            }
        }
        echo '<div class="notice notice-info"><p>' . esc_html("Imported images: $created; linked articles: $linked; already linked: $skipped; errors: " . count($errors)) . '</p></div>';
        if ($errors) {
            echo '<div class="notice notice-warning"><ul>';
            foreach ($errors as $error) {
                echo '<li>' . esc_html($error) . '</li>';
            }
            echo '</ul></div>';
        }
    }
    echo '<form method="post">';
    wp_nonce_field('devrix_import_demo_images');
    submit_button('Import Demo Images', 'primary', 'devrix_import_images');
    echo '</form></div>';
}
