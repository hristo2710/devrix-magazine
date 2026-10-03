<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <!-- First row -->
    <div class="header-top">
        <div class="header-container header-top-inner">
            <a class="site-logo"
               href="<?php echo esc_url( home_url( '/' ) ); ?>">
                My Logo
            </a>
            <div class="header-actions">
                <div class="social-links">
                    <a href="#" class="social-link" aria-label="Facebook">
                        <i class="fa-brands fa-facebook-f" aria-hidden="true"></i>
                    </a>
                    <a href="#" class="social-link" aria-label="X">
                        <i class="fa-brands fa-x-twitter" aria-hidden="true"></i>
                    </a>
                    <a href="#" class="social-link" aria-label="Instagram">
                        <i class="fa-brands fa-instagram" aria-hidden="true"></i>
                    </a>
                </div>
                <a href="#" class="subscribe-button">
                    Subscribe for more
                </a>
            </div>
        </div>
    </div>

    <!-- Second row -->
    <div class="header-bottom">
        <div class="header-container header-bottom-inner">
            <button
                class="menu-toggle"
                type="button"
                aria-label="Toggle navigation menu"
                aria-controls="site-main-navigation"
                aria-expanded="false"
            >
                <i class="fa-solid fa-bars" aria-hidden="true"></i>
            </button>
            <nav
                id="site-main-navigation"
                class="main-navigation"
                aria-label="Main navigation"
            >
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'header-menu',
                    'fallback_cb'    => false,
                ) );
                ?>
            </nav>
            <div class="header-utilities">
                <div class="header-weather">
                    <span class="weather-icon" aria-hidden="true">☁</span>
                    <span>28°, Sofia</span>
                </div>
                <button
                    class="search-toggle"
                    type="button"
                    aria-label="Open search"
                    aria-expanded="false"
                    aria-controls="live-search"
                >
                    <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                </button>
                <div class="live-search"
                    id="live-search"
                    data-search-url="<?php echo esc_url(rest_url('devrix/v1/search')); ?>"
                    hidden>
                    <div class="live-search-header">
                        <label for="live-search-input">Search articles</label>
                        <button type="button"
                                class="live-search-close"
                                aria-label="Close search">
                            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                        </button>
                    </div>
                    <input type="search"
                        id="live-search-input"
                        placeholder="Type at least 2 characters..."
                        autocomplete="off">
                    <div class="live-search-results"
                        id="live-search-results"
                        role="status"
                        aria-live="polite">
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>