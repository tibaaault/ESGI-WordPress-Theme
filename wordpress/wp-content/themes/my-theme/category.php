<?php get_header(); ?>

<div class="container-fluid p-0 " style="background-color : #333;">
    <div class="category-banner" <?= set_category_banner() ?>>
        <h1 class="category-title" style="height: 400px;"></h1>
    </div>
    <div class="col-xl-10 mx-auto bg-light">
        <div class="d-flex" style="height : 50px;"></div>
        <h1 class="text-center">Catégorie <?php single_cat_title(); ?></h1>
        <div class="d-flex" style="height : 50px;"></div>

        <?php
        // Récupérer l'ID de la catégorie actuelle
        $current_cat_id = get_queried_object_id();

        // Query pour récupérer les films de la catégorie actuelle
        $category_movies = new WP_Query(array(
            'post_type' => 'movie',
            'cat' => $current_cat_id,
            'posts_per_page' => -1,
        ));

        // Afficher les films de la catégorie actuelle
        if ($category_movies->have_posts()) :
            while ($category_movies->have_posts()) : $category_movies->the_post(); ?>
                <div class="category-movie">
                    <div class="row">
                        <div class="col-xl-4 mx-auto text-center">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="category-movie-thumbnail">
                                    <?php the_post_thumbnail('medium'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-xl-8 pt-5">
                            <h2 class="category-movie-title text-center"><?php the_title(); ?></h2>
                            <div class="category-movie-content text-center mt-auto">
                                <?php the_excerpt(); ?>
                            </div>
                            <div class="category-movie-link text-center mt-3">
                                <a href="<?php the_permalink(); ?>" class="btn btn-primary">Voir le film</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex" style="height : 100px;"></div>
            <?php endwhile;
            wp_reset_postdata();
        else : ?>
            <p>Aucun film trouvé dans cette catégorie.</p>
        <?php endif; ?>
    </div>
    <div class="d-flex" style="height : 100px;"></div>

</div>
<?php
 get_footer(); 
?>