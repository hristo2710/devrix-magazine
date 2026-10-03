<?php
if (!defined('ABSPATH')) {
    exit;
}

function devrix_demo_menu_admin_page() {
    add_management_page(
        'Setup Demo Menu',
        'Setup Demo Menu',
        'manage_options',
        'devrix-demo-menu',
        'devrix_demo_menu_render_page'
    );
}
add_action('admin_menu', 'devrix_demo_menu_admin_page');

function devrix_demo_menu_render_page() {
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('You do not have permission to access this page.'));
    }

    $message = '';
    if (isset($_POST['devrix_setup_menu'])) {
        check_admin_referer('devrix_setup_demo_menu');

        $menu = wp_get_nav_menu_object('Main Menu');
        if (!$menu) {
            $created = wp_create_nav_menu('Main Menu');
            if (is_wp_error($created)) {
                $message = $created->get_error_message();
            } else {
                $menu = wp_get_nav_menu_object($created);
            }
        }

        if ($menu && !$message) {
            $menu_id = (int) $menu->term_id;
            $desired = array(
                'News'       => 'news',
                'Sex'        => 'sex',
                'Technology' => 'technology',
                'Sport'      => 'sport',
                'Healthcare' => 'healthcare',
            );

            // Only replace items inside the dedicated demo menu.
            $items = wp_get_nav_menu_items($menu_id, array('post_status' => 'any'));
            if ($items) {
                foreach ($items as $item) {
                    wp_delete_post((int) $item->ID, true);
                }
            }

            $errors = array();
            foreach ($desired as $label => $anchor) {
                $result = wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title'  => $label,
                    'menu-item-url'    => home_url('/#' . $anchor),
                    'menu-item-status' => 'publish',
                    'menu-item-type'   => 'custom',
                ));
                if (is_wp_error($result)) {
                    $errors[] = $result->get_error_message();
                }
            }

            if (!$errors) {
                $locations = get_theme_mod('nav_menu_locations', array());
                $locations['primary'] = $menu_id;
                set_theme_mod('nav_menu_locations', $locations);
                $message = 'Demo menu configured successfully.';
            } else {
                $message = implode(' ', $errors);
            }
        }
    }
    ?>
    <div class="wrap">
        <h1>Setup Demo Menu</h1>
        <p>This replaces the items inside the menu named "Main Menu" with the five demo navigation links and assigns it to Primary Navigation. Other menus are not changed.</p>
        <?php if ($message) : ?>
            <div class="notice notice-info"><p><?php echo esc_html($message); ?></p></div>
        <?php endif; ?>
        <form method="post">
            <?php wp_nonce_field('devrix_setup_demo_menu'); ?>
            <?php submit_button('Setup Demo Menu', 'primary', 'devrix_setup_menu'); ?>
        </form>
    </div>
    <?php
}
