<?php get_header(); ?>

<div class="container-fluid" style="background-color: whitesmoke;">
    <div class="col-xl-10 mx-auto py-1">
        <h1>Catégories de Films</h1>
    </div>
</div>
<div class="container-fluid py-2" >
    <div class="category-list owl-carousel owl-theme">
        <?php
        // Récupérer les catégories de films
        $categories = get_categories(array(
            'taxonomy' => 'category',
            'hide_empty' => false,
        )); ?>

        <div class="row">
            <?php
            // Parcourir les catégories
            foreach ($categories as $category) :
            ?>
                <div class="col-xl col-sm">
                    <div class="item">
                        <a href="<?php echo get_category_link($category->term_id); ?>" class="category-item text-center">
                            <h5><?php echo $category->name; ?></h5>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<div class="container-fluid " style="background-color : grey;">
    <div class="d-flex" style="height: 50px;"></div>
    <div class="col-xl-11 mx-auto text-center bg-light ">
        <div class="d-flex" style="height: 50px;"></div>
        <h2>Films au Hasard</h2>
        <div class="d-flex" style="height: 50px;"></div>
        <div class="row">
            <?php
            // Query pour récupérer des articles de type 'movie' au hasard
            $random_movies = new WP_Query(array(
                'post_type' => 'movie',
                'orderby' => 'rand',
                'posts_per_page' => 6,
            ));

            // Afficher les films au hasard
            while ($random_movies->have_posts()) : $random_movies->the_post();
            ?>
                <div class="col-md-4 pb-3">
                    <div class="movie-item card p-4 shadows shadow-lg">
                        <h3><?php the_title(); ?></h3>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="movie-thumbnail">
                                <?php the_post_thumbnail('medium', array('class' => 'img-fluid')); ?>
                            </div>
                        <?php endif; ?>
                        <p><?php the_excerpt(); ?></p>
                        <a href="<?php the_permalink(); ?>" class="btn btn-primary">Voir le film</a>
                    </div>
                    <div class="d-flewx" style="height: 50px;"></div>
                </div>
            <?php endwhile; ?>
            <div class="d-flex" style="height:100px;"></div>
            <?php wp_reset_postdata(); ?>
        </div>
    </div>
    <div class="d-flex" style="height:100px;"></div>

</div>

<script src="https://cdn.jsdelivr.net/npm/owl.carousel@2.3.4/dist/owl.carousel.min.js"></script>
<?php
 get_footer(); 
?>