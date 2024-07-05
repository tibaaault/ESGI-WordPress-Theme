<?php

function cinema_theme_enqueue_assets() {
    // Enqueue Bootstrap
    wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
    wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', array('jquery'), null, true);

    // Enqueue Owl Carousel
    wp_enqueue_script('owl-carousel', 'https://cdn.jsdelivr.net/npm/owl.carousel@2.3.4/dist/owl.carousel.min.js', array('jquery'), '2.3.4', true);

    // Enqueue main stylesheet
    wp_enqueue_style('main-styles', get_stylesheet_uri());
    

    // Enqueue custom script
    wp_enqueue_script('custom-script', get_template_directory_uri() . '/js/custom.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'cinema_theme_enqueue_assets');

function cinema_theme_setup() {
    // Register nav menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'cinema-theme'),
    ));

    // Add theme supports
    add_theme_support('widgets');
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'cinema_theme_setup');

function create_movie_post_type() {
    register_post_type('movie', array(
        'labels' => array(
            'name' => __('Films'),
            'singular_name' => __('Film')
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'taxonomies' => array('category'),
    ));
}
add_action('init', 'create_movie_post_type');

function add_thumbnail_support_for_movies() {
    add_post_type_support('movie', 'thumbnail');
}
add_action('init', 'add_thumbnail_support_for_movies');

function add_movie_meta_box() {
    add_meta_box(
        'movie_details',
        __('Détails du Film', 'cinema-theme'),
        'movie_details_callback',
        'movie',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_movie_meta_box');

function movie_details_callback($post) {
    $year = get_post_meta($post->ID, 'year', true);
    $rating = get_post_meta($post->ID, 'rating', true);
    $trailer_url = get_post_meta($post->ID, 'trailer_url', true);

    echo '<label for="movie_year">' . __('Année de sortie', 'cinema-theme') . '</label>';
    echo '<input type="text" id="movie_year" name="movie_year" value="' . esc_attr($year) . '"><br>';

    echo '<label for="movie_rating">' . __('Note', 'cinema-theme') . '</label>';
    echo '<input type="text" id="movie_rating" name="movie_rating" value="' . esc_attr($rating) . '"><br>';

    echo '<label for="movie_trailer_url">' . __('URL de la Bande-annonce', 'cinema-theme') . '</label>';
    echo '<input type="text" id="movie_trailer_url" name="movie_trailer_url" value="' . esc_url($trailer_url) . '">';
}

function save_movie_meta_box($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['movie_year'])) {
        update_post_meta($post_id, 'year', sanitize_text_field($_POST['movie_year']));
    }
    if (isset($_POST['movie_rating'])) {
        update_post_meta($post_id, 'rating', sanitize_text_field($_POST['movie_rating']));
    }
    if (isset($_POST['movie_trailer_url'])) {
        update_post_meta($post_id, 'trailer_url', esc_url_raw($_POST['movie_trailer_url']));
    }
}
add_action('save_post', 'save_movie_meta_box');

function set_category_banner() {
    if (is_category()) {
        $category = get_queried_object();
        $category_slug = $category->slug;

        $banner_file_path = get_template_directory() . '/images/' . $category_slug . '-banner.jpg';

        if (file_exists($banner_file_path)) {
            return 'style="
            background-image: url(\'' . get_template_directory_uri() . '/images/' . $category_slug . '-banner.jpg\');
            background-size: contain;
            background-color: #333;
            background-position: center;
            background-repeat: no-repeat;
            width: 100%;
        "';
        }
    }
}
add_action('wp_head', 'set_category_banner');

