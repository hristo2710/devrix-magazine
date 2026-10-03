<footer class="site-footer">
    <div class="footer-container">

        <a
            href="<?php echo esc_url( home_url( '/' ) ); ?>"
            class="footer-logo"
            aria-label="Home"
        >
            L
        </a>

        <nav class="footer-navigation" aria-label="Footer navigation">
            <?php
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'footer-menu',
                'fallback_cb'    => false,
            ) );
            ?>
        </nav>

        <div class="footer-right">
            <span class="footer-copyright">
                Project © <?php echo esc_html( gmdate( 'Y' ) ); ?>
            </span>

            <div class="footer-socials">
                <a href="#" aria-label="Facebook">
                    <i class="fa-brands fa-facebook-f" aria-hidden="true"></i>
                </a>

                <a href="#" aria-label="X">
                    <i class="fa-brands fa-x-twitter" aria-hidden="true"></i>
                </a>

                <a href="#" aria-label="Instagram">
                    <i class="fa-brands fa-instagram" aria-hidden="true"></i>
                </a>
            </div>
        </div>

    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>