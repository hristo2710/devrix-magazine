<?php get_header(); ?>

<main>
    <?php
    
    $featured_query = new WP_Query(
        array(
            'post_type'      => 'article',
            'posts_per_page' => 1,
            'meta_query'     => array(
                array(
                    'key'   => 'is_featured',
                    'value' => '1',
                ),
            ),
        )
    );

    $featured_id = ! empty( $featured_query->posts )
        ? $featured_query->posts[0]->ID
        : 0;

    $latest_query = new WP_Query(
        array(
            'post_type'      => 'article',
            'posts_per_page' => 4,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'post__not_in'   => $featured_id ? array($featured_id) : array(),
        )
    );

    $breaking_query = new WP_Query(
        array(
            'post_type'      => 'article',
            'posts_per_page' => 5,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'meta_query'     => array(
                array(
                    'key'   => 'is_breaking',
                    'value' => '1',
                ),
            ),
        )
    );

    $news_articles_query = new WP_Query(
        array(
            'post_type' => 'article',
            'posts_per_page' => 5,
            'orderby' => 'date',
            'order' => 'DESC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'article_category',
                    'field' => 'slug',
                    'terms' => 'news',
                ),
            ),
        )
    );

    $sex_articles_query = new WP_Query(
        array(
            'post_type' => 'article',
            'posts_per_page' => 3,
            'orderby' => 'date',
            'order' => 'DESC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'article_category',
                    'field' => 'slug',
                    'terms' => 'sex',
                ),
            ),
        )
    );

    $technology_articles_query = new WP_Query(
        array(
            'post_type' => 'article',
            'posts_per_page' => 5,
            'orderby' => 'date',
            'order' => 'DESC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'article_category',
                    'field' => 'slug',
                    'terms' => 'technology',
                ),
            ),
        )
    );

    $sport_articles_query = new WP_Query(
        array(
            'post_type' => 'article',
            'posts_per_page' => 4,
            'tax_query' => array(
                array(
                    'taxonomy' => 'article_category',
                    'field' => 'slug',
                    'terms' => 'sport',
                ),
            ),
        )
    );

    $healthcare_articles_query = new WP_Query(
        array(
            'post_type' => 'article',
            'posts_per_page' => 3,
            'tax_query' => array(
                array(
                    'taxonomy' => 'article_category',
                    'field' => 'slug',
                    'terms' => 'healthcare',
                ),
            ),
        )
    );
    ?>

    
    <section class="hero-section">
        <div class="hero-container">
            <div class="hero-featured">
                <?php if ($featured_query->have_posts()) : ?>
                    <?php while ($featured_query->have_posts()) : ?>
                        <?php $featured_query->the_post(); ?>

                        <article class="featured-card">
                            <a class="featured-image-link" href="<?php the_permalink(); ?>">
                                <?php
                                if (has_post_thumbnail()) {
                                    the_post_thumbnail(
                                        'large',
                                        array('class' => 'featured-image')
                                    );
                                }
                                ?>
                                <span class="featured-label">FEATURED</span>
                            </a>

                            <h1>
                                <a class="featured-title-link" href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h1>

                            <p class="featured-description">
                                <?php echo esc_html(wp_trim_words(get_the_excerpt(), 30)); ?>
                            </p>

                            <a class="featured-read-more" href="<?php the_permalink(); ?>">
                                Read more
                            </a>
                        </article>

                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                <?php endif; ?>
            </div>

            <aside class="latest-news">
                <h2>Latest News</h2>
                <!-- Featured article as the first latest news card -->
                <?php if ($featured_id) : ?>
                    <article
                        class="latest-news-card"
                        data-title="<?php echo esc_attr(get_the_title($featured_id)); ?>"
                        data-description="<?php echo esc_attr(wp_trim_words(get_the_excerpt($featured_id), 30)); ?>"
                        data-image="<?php echo esc_url(get_the_post_thumbnail_url($featured_id, 'large') ?: ''); ?>"
                        data-url="<?php echo esc_url(get_permalink($featured_id)); ?>"
                    >
                        <h3>
                            <a href="<?php echo esc_url(get_permalink($featured_id)); ?>">
                                <?php echo esc_html(get_the_title($featured_id)); ?>
                            </a>
                        </h3>
                        <p>
                            <?php echo esc_html(get_the_author_meta('display_name', get_post_field('post_author', $featured_id))); ?>
                            -
                            <?php echo esc_html(get_the_date('M j, Y', $featured_id)); ?>
                        </p>
                    </article>
                <?php endif; ?>
                <!-- Other latest news cards -->
                <?php while ($latest_query->have_posts()) : ?>
                    <?php $latest_query->the_post(); ?>

                    <article
                        class="latest-news-card"
                        data-title="<?php echo esc_attr(get_the_title()); ?>"
                        data-description="<?php echo esc_attr(wp_trim_words(get_the_excerpt(), 30)); ?>"
                        data-image="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large') ?: ''); ?>"
                        data-url="<?php echo esc_url(get_permalink()); ?>"
                    >
                        <h3>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h3>

                        <p>
                            <?php echo esc_html(get_the_author()); ?>
                            -
                            <?php echo esc_html(get_the_date('M j, Y')); ?>
                        </p>
                    </article>

                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>

                <a
                    class="latest-news-more"
                    href="<?php echo esc_url(get_post_type_archive_link('article')); ?>"
                >
                    <span class="latest-news-more-text">
                        See all latest news
                    </span>
                    <i class="fa-solid fa-arrow-right-long" aria-hidden="true"></i>
                </a>
            </aside>
        </div>
    </section>


    <?php if ( $breaking_query->have_posts() ) : ?>

    <?php $breaking_articles = $breaking_query->posts; ?>

        <section class="breaking-section" id="breaking">
            <div class="breaking-container">

                <div class="breaking-small-grid">

                    <?php foreach ( array_slice( $breaking_articles, 1, 4 ) as $article ) : ?>

                        <article class="breaking-small-card">
                            <h3>
                                <a href="<?php echo esc_url( get_permalink( $article->ID ) ); ?>">
                                    <?php echo esc_html( get_the_title( $article->ID ) ); ?>
                                </a>
                            </h3>

                            <p>
                                <?php
                                echo esc_html(
                                    get_the_author_meta(
                                        'display_name',
                                        $article->post_author
                                    )
                                );
                                ?>
                                -
                                <?php echo esc_html( get_the_date( 'M j, Y', $article->ID ) ); ?>
                            </p>
                        </article>

                    <?php endforeach; ?>

                </div>

                <?php $main_breaking = $breaking_articles[0]; ?>

                <article class="breaking-main-card">
                    <a href="<?php echo esc_url( get_permalink( $main_breaking->ID ) ); ?>">

                        <?php
                        echo get_the_post_thumbnail(
                            $main_breaking->ID,
                            'large',
                            array( 'class' => 'breaking-main-image' )
                        );
                        ?>

                        <span class="breaking-label">BREAKING</span>

                        <h2>
                            <?php echo esc_html( get_the_title( $main_breaking->ID ) ); ?>
                        </h2>
                    </a>
                </article>

            </div>
        </section>

    <?php endif; ?>

    <section id="news" class="news-section" aria-labelledby="news-section-title">
        <div class="news-section-container">
            <h2 id="news-section-title" class="news-section-title">News</h2>

            <?php if ( $news_articles_query->have_posts() ) : ?>
                <div class="technology-layout technology-layout--reversed">
                    <?php $news_post_index = 0; ?>
                    <?php while ( $news_articles_query->have_posts() ) : ?>
                        <?php $news_articles_query->the_post(); ?>

                        <?php if ( 0 === $news_post_index ) : ?>
                            <article class="technology-featured-card">
                                <a class="technology-featured-image-link" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <?php the_post_thumbnail( 'large', array( 'class' => 'technology-featured-image' ) ); ?>
                                    <?php else : ?>
                                        <div class="technology-featured-image technology-featured-image-placeholder" aria-hidden="true"></div>
                                    <?php endif; ?>
                                </a>

                                <p class="technology-featured-meta">
                                    <?php echo esc_html( get_the_author() ); ?> - <?php echo esc_html( get_the_date( 'M j, Y' ) ); ?>
                                </p>

                                <h3 class="technology-featured-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>

                                <p class="technology-featured-content"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>

                                <a class="technology-featured-read-more" href="<?php the_permalink(); ?>">
                                    Continue reading this article
                                    <i class="fa-solid fa-arrow-right-long" aria-hidden="true"></i>
                                </a>
                            </article>
                            <div class="technology-side-grid">
                        <?php else : ?>
                            <article class="technology-side-card">
                                <a class="technology-side-image-link" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <?php the_post_thumbnail( 'large', array( 'class' => 'technology-side-image' ) ); ?>
                                    <?php else : ?>
                                        <div class="technology-side-image technology-side-image-placeholder" aria-hidden="true"></div>
                                    <?php endif; ?>
                                </a>

                                <h3 class="technology-side-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>

                                <p class="technology-side-date"><?php echo esc_html( get_the_date( 'M j, Y' ) ); ?></p>
                            </article>
                        <?php endif; ?>

                        <?php $news_post_index++; ?>
                    <?php endwhile; ?>
                    </div>
                </div>

                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <p class="technology-empty-message">No news articles found.</p>
            <?php endif; ?>
        </div>
    </section>

    <section id="sex" class="sex-section" aria-labelledby="sex-section-title">
        <div class="sex-section-container">
            <h2 id="sex-section-title" class="sex-section-title">Sex</h2>

            <?php if ( $sex_articles_query->have_posts() ) : ?>
                <div class="sex-grid">
                    <?php while ( $sex_articles_query->have_posts() ) : ?>
                        <?php $sex_articles_query->the_post(); ?>

                        <article class="sex-card">
                            <a class="sex-card-image-link" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'large', array( 'class' => 'sex-card-image' ) ); ?>
                                <?php else : ?>
                                    <div class="sex-card-image sex-card-image-placeholder" aria-hidden="true"></div>
                                <?php endif; ?>
                            </a>

                            <h3 class="sex-card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>

                            <p class="sex-card-date"><?php echo esc_html( get_the_date( 'M j, Y' ) ); ?></p>
                        </article>
                    <?php endwhile; ?>
                </div>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <p class="sex-empty-message">No sex articles found.</p>
            <?php endif; ?>
        </div>
    </section>

    <section id="technology" class="technology-section" aria-labelledby="technology-section-title">
        <div class="technology-section-container">
            <h2 id="technology-section-title" class="technology-section-title">Technology</h2>

            <?php if ( $technology_articles_query->have_posts() ) : ?>
                <div class="technology-layout">
                    <?php $technology_post_index = 0; ?>
                    <?php while ( $technology_articles_query->have_posts() ) : ?>
                        <?php $technology_articles_query->the_post(); ?>

                        <?php if ( 0 === $technology_post_index ) : ?>
                            <article class="technology-featured-card">
                                <a class="technology-featured-image-link" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <?php the_post_thumbnail( 'large', array( 'class' => 'technology-featured-image' ) ); ?>
                                    <?php else : ?>
                                        <div class="technology-featured-image technology-featured-image-placeholder" aria-hidden="true"></div>
                                    <?php endif; ?>
                                </a>

                                <p class="technology-featured-meta">
                                    <?php echo esc_html( get_the_author() ); ?> - <?php echo esc_html( get_the_date( 'M j, Y' ) ); ?>
                                </p>

                                <h3 class="technology-featured-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>

                                <p class="technology-featured-content"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>

                                <a class="technology-featured-read-more" href="<?php the_permalink(); ?>">
                                    Continue reading this article
                                    <i class="fa-solid fa-arrow-right-long" aria-hidden="true"></i>
                                </a>
                            </article>
                            <div class="technology-side-grid">
                        <?php else : ?>
                            <article class="technology-side-card">
                                <a class="technology-side-image-link" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <?php the_post_thumbnail( 'large', array( 'class' => 'technology-side-image' ) ); ?>
                                    <?php else : ?>
                                        <div class="technology-side-image technology-side-image-placeholder" aria-hidden="true"></div>
                                    <?php endif; ?>
                                </a>

                                <h3 class="technology-side-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>

                                <p class="technology-side-date"><?php echo esc_html( get_the_date( 'M j, Y' ) ); ?></p>
                            </article>
                        <?php endif; ?>

                        <?php $technology_post_index++; ?>
                    <?php endwhile; ?>
                    </div>
                </div>

                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <p class="technology-empty-message">No technology articles found.</p>
            <?php endif; ?>
        </div>
    </section>

    <section id="sport" class="sport-section" aria-labelledby="sport-section-title">
        <div class="sport-section-container">
            <h2 id="sport-section-title" class="sport-section-title">Sport</h2>

            <?php if ( $sport_articles_query->have_posts() ) : ?>
                <div class="sport-grid">
                    <?php while ( $sport_articles_query->have_posts() ) : ?>
                        <?php $sport_articles_query->the_post(); ?>

                        <article class="sport-card">
                            <a class="sport-card-image-link" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'large', array( 'class' => 'sport-card-image' ) ); ?>
                                <?php else : ?>
                                    <div class="sport-card-image sport-card-image-placeholder" aria-hidden="true"></div>
                                <?php endif; ?>
                            </a>

                            <h3 class="sport-card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>

                            <p class="sport-card-date"><?php echo esc_html( get_the_date( 'M j, Y' ) ); ?></p>
                        </article>
                    <?php endwhile; ?>
                </div>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <p class="sport-empty-message">No sport articles found.</p>
            <?php endif; ?>
        </div>
    </section>

    <section id="healthcare" class="healthcare-section" aria-labelledby="healthcare-section-title">
        <div class="healthcare-section-container">
            <h2 id="healthcare-section-title" class="healthcare-section-title">Healthcare</h2>

            <?php if ( $healthcare_articles_query->have_posts() ) : ?>
                <div class="healthcare-grid">
                    <?php while ( $healthcare_articles_query->have_posts() ) : ?>
                        <?php $healthcare_articles_query->the_post(); ?>

                        <article class="healthcare-card">
                            <a class="healthcare-card-image-link" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'large', array( 'class' => 'healthcare-card-image' ) ); ?>
                                <?php else : ?>
                                    <div class="healthcare-card-image healthcare-card-image-placeholder" aria-hidden="true"></div>
                                <?php endif; ?>
                            </a>

                            <h3 class="healthcare-card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>

                            <p class="healthcare-card-date"><?php echo esc_html( get_the_date( 'M j, Y' ) ); ?></p>
                        </article>
                    <?php endwhile; ?>
                </div>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <p class="healthcare-empty-message">No healthcare articles found.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>