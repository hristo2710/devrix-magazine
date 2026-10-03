
<?php
get_header();
?>
<main class="single-article">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article class="single-article-container">
                <header class="single-article-header">
                    <div class="single-article-categories">
                        <?php the_terms(get_the_ID(), 'article_category', '', ', '); ?>
                    </div>
                    <h1 class="single-article-title"><?php the_title(); ?></h1>
                    <time class="single-article-date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                        <?php echo esc_html(get_the_date()); ?>
                    </time>
                </header>
                <?php if (has_post_thumbnail()) : ?>
                    <div class="single-article-image">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>
                <div class="single-article-content">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    <?php endif; ?>
</main>
<?php
get_footer();
