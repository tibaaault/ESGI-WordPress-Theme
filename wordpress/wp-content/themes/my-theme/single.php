<?php get_header(); ?>

<div class="container-fluid h-100" style="background-color : #b3d4fc;">
    <div class="d-flex" style="height: 50px;"></div>
    <?php
    $categories = get_the_category();
    if (!empty($categories)) {
        echo '<p class="h3">Ce film appartient à la catégorie : ';
        foreach ($categories as $index => $category) {
            echo '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . $category->name . '</a>';
            // Ajouter une virgule sauf pour la dernière catégorie
            if ($index !== count($categories) - 1) {
                echo ', ';
            }
        }
        echo '</p>';
    }
    ?>
    <div class="d-flex" style="height: 50px;"></div>
    <div class="col-xl-9 bg-light mx-auto rounded rounded-5 px-3">
        <div class="d-flex" style="height: 50px;"></div>
        <?php
        if (have_posts()) :
            while (have_posts()) : the_post(); ?>
                <h1 class="single-movie-title text-center"><?php the_title(); ?></h1>
                <hr>
                <div class="col-xl-8 mx-auto">
                    <?php
                    $trailer_url = get_post_meta(get_the_ID(), 'trailer_url', true);
                    if ($trailer_url) {
                        // Formater l'URL de la bande-annonce pour YouTube
                        preg_match('/(youtu\.be\/|v=)([^&]+)/', $trailer_url, $matches);
                        if (isset($matches[2])) {
                            $video_embed_url = 'https://www.youtube.com/embed/' . $matches[2];
                        } else {
                            $video_embed_url = '';
                        }
                        if ($video_embed_url) : ?>
                            <p><strong>Bande-annonce :</strong></p>
                            <div class=" video-frame mx-auto">
                                <iframe width="400" height="225" src="<?php echo esc_url($video_embed_url); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        <?php else : ?>
                            <p><strong>Bande-annonce :</strong> <a href="<?php echo esc_url($trailer_url); ?>" target="_blank">Voir la bande-annonce</a></p>
                        <?php endif;
                    } else { ?>
                        <p><strong>Bande-annonce :</strong> Non disponible</p>
                    <?php
                    }
                    ?>
                </div>
                <hr>
                <div class="row mx-auto">
                    <div class="col-xl-4 col-sm mx-auto text-center">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="single-movie-thumbnail">
                                <?php the_post_thumbnail('medium'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-xl-7 col-sm">
                        <div class="single-movie-details pt-4">
                            <p><strong>Année de sortie :</strong> <?php echo esc_html(get_post_meta(get_the_ID(), 'year', true)); ?></p>
                            <p><strong>Note :
                                    <?php
                                    $rating = get_post_meta(get_the_ID(), 'rating', true);
                                    if (!empty($rating)) {
                                        echo '<div class="post-rating">';
                                        for ($i = 0; $i < $rating; $i++) {
                                            echo '⭐️';
                                        }
                                        echo '(' . esc_html(get_post_meta(get_the_ID(), 'rating', true)) . ')';
                                        echo '</div>';
                                    }
                                    ?>
                            </p>
                        </div>
                        <div class="col-xl-10 col-sm mx-auto">
                            <h3><u>Horaires des Séances</u></h3>
                            <?php
                            global $wpdb;
                            $movie_id = get_the_ID();
                            $table_name = $wpdb->prefix . 'cinema_schedules';

                            $query = $wpdb->prepare(
                                "SELECT * FROM $table_name WHERE movie_id = %d",
                                $movie_id
                            );

                            $schedules = $wpdb->get_results($query);

                            if ($schedules) {
                                echo '<ul>';
                                foreach ($schedules as $schedule) {
                                    echo '<li>' . esc_html($schedule->schedule_time) . '</li>';
                                }
                                echo '</ul>';
                            } else {
                                echo '<p>Aucun horaire disponible pour le moment.</p>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="col-xl-10 col-sm mx-auto">
                    <h3><u>Synopsis</u></h3>
                </div>
                <div class="col-xl-8 col-sm pt-3 mx-auto">
                    <div class="pb-3">
                        <p class="lead"><?= the_content(); ?></p>
                    </div>
                </div>
        <?php endwhile;
        endif; ?>
    </div>
    <div class="d-flex" style="height:100px;"></div>

</div>
<?php
get_footer();
?>