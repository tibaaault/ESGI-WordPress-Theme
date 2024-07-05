<?php
/*
Plugin Name: Cinema Schedules
Description: Gérer les séances des films
Version: 1.0
Author: Thibault Roelstrate
*/

define('CINEMA_SCHEDULES_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Ajouter un menu dans le tableau de bord WordPress
function cinema_schedules_menu()
{
    add_menu_page(
        'Gestion des Séances',
        'Gestion des Séances',
        'manage_options',
        'cinema-schedules',
        'cinema_schedules_page',
        'dashicons-calendar-alt',
        20
    );
}
add_action('admin_menu', 'cinema_schedules_menu');
function cinema_schedules_page()
{
?>
    <div class="wrap">
        <h1>Gestion des Séances</h1>
        <div id="cinema-schedules-container">
            <form id="cinema-schedules-form" method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="save_cinema_schedules">
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Film</th>
                            <th>Jour et Heure</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="cinema-schedules-body">
                        <?php echo cinema_get_saved_schedules(); ?>
                    </tbody>
                </table>
                <p class="submit">
                    <button type="button" class="button button-primary" id="add-new-schedule">Ajouter un film</button>
                    <button type="submit" class="button button-primary">Enregistrer les modifications</button>
                </p>
            </form>
        </div>
    </div>
    <script>
        jQuery(document).ready(function($) {
            var rowTemplate = '<tr>\
    <td><select class="movie-select" name="movie_id[]"><?php echo cinema_get_movie_options(); ?></select></td>\
    <td class="schedule-times"><input type="text" class="schedule-time" name="schedule_time[]" placeholder="Lundi 10:00"></td>\
    <td><button type="button" class="button delete-schedule-row">Supprimer</button>\
    <button type="button" class="button add-schedule-row">Ajouter</button></td>\
    </tr>';

            $('#add-new-schedule').click(function() {
                $('#cinema-schedules-body').append(rowTemplate);
            });

            $('#cinema-schedules-body').on('click', '.add-schedule-row', function() {
                var $newRow = $(this).closest('tr').clone(); // Cloner la ligne existante
                $newRow.find('input.schedule-time').val(''); // Effacer la valeur de l'heure si nécessaire
                $(this).closest('tr').after($newRow); // Ajouter la nouvelle ligne après la ligne actuelle
            });

            $('#cinema-schedules-body').on('click', '.delete-schedule-row', function() {
                $(this).closest('tr').remove(); // Supprimer la ligne de tableau actuelle
            });
        });
    </script>
<?php
}


function cinema_get_movie_options($selected_id = 0)
{
    $args = array(
        'post_type' => 'movie',
        'posts_per_page' => -1,
    );

    $movies = get_posts($args);

    $options = '';
    foreach ($movies as $movie) {
        $selected = ($selected_id == $movie->ID) ? 'selected' : '';
        $options .= '<option value="' . $movie->ID . '" ' . $selected . '>' . esc_html($movie->post_title) . '</option>';
    }

    return $options;
}


// Créer une nouvelle table pour les séances de cinéma
function cinema_create_schedule_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'cinema_schedules';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        movie_id mediumint(9) NOT NULL,
        schedule_time varchar(100) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'cinema_create_schedule_table');


add_action('admin_post_save_cinema_schedules', 'save_cinema_schedules');
function save_cinema_schedules() {
    global $wpdb;

    if (!current_user_can('manage_options')) {
        wp_die('Access denied');
    }

    if (isset($_POST['movie_id']) && isset($_POST['schedule_time'])) {
        $table_name = $wpdb->prefix . 'cinema_schedules';

        $schedule_ids = isset($_POST['schedule_id']) ? $_POST['schedule_id'] : array();
        $movie_ids = $_POST['movie_id'];
        $schedule_times = $_POST['schedule_time'];
        $delete_schedule = isset($_POST['delete_schedule']) ? $_POST['delete_schedule'] : array();

        // Suppression des séances marquées pour suppression
        foreach ($delete_schedule as $delete_id) {
            $delete_id = absint($delete_id);
            $wpdb->delete($table_name, array('id' => $delete_id));
        }

        // Traitement des mises à jour et des insertions
        foreach ($movie_ids as $key => $movie_id) {
            $schedule_id = isset($schedule_ids[$key]) ? absint($schedule_ids[$key]) : 0;
            $movie_id = absint($movie_id);
            $schedule_time = sanitize_text_field($schedule_times[$key]);

            if (!empty($schedule_time)) {
                if ($schedule_id > 0) {
                    // Mise à jour de l'entrée existante
                    $updated = $wpdb->update(
                        $table_name,
                        array('schedule_time' => $schedule_time, 'movie_id' => $movie_id),
                        array('id' => $schedule_id)
                    );

                    if ($updated === false) {
                        error_log('Database update error: ' . $wpdb->last_error);
                    } else {
                        error_log('Schedule updated: ' . $schedule_time);
                    }
                } else {
                    // Insertion d'une nouvelle entrée
                    $inserted = $wpdb->insert($table_name, array(
                        'movie_id' => $movie_id,
                        'schedule_time' => $schedule_time,
                    ));

                    if ($inserted === false) {
                        error_log('Database insert error: ' . $wpdb->last_error);
                    } else {
                        error_log('Schedule inserted: ' . $schedule_time);
                    }
                }
            }
        }
    } else {
        error_log('No POST data received');
    }

    wp_redirect(admin_url('admin.php?page=cinema-schedules'));
    exit;
}





function cinema_get_saved_schedules() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'cinema_schedules';

    $query = "SELECT * FROM $table_name";
    $results = $wpdb->get_results($query);

    $html = '';
    foreach ($results as $schedule) {
        $movie_title = get_the_title($schedule->movie_id);
        $html .= '<tr>
            <input type="hidden" name="schedule_id[]" value="' . esc_attr($schedule->id) . '">
            <td><select class="movie-select" name="movie_id[]">' . cinema_get_movie_options($schedule->movie_id) . '</select></td>
            <td class="schedule-times"><input type="text" class="schedule-time" name="schedule_time[]" value="' . esc_attr($schedule->schedule_time) . '"></td>
            <td><input type="checkbox" class="delete-schedule-checkbox" name="delete_schedule[]" value="' . esc_attr($schedule->id) . '"> Supprimer</td>
        </tr>';
    }

    return $html;
}







// Suppression des métaboxes pour les films
function cinema_schedules_meta_box()
{
    remove_meta_box('postcustom', 'movie', 'normal');
}
add_action('add_meta_boxes_movie', 'cinema_schedules_meta_box');



// Fonction pour afficher et gérer les horaires de séances dans la métabox des films
function cinema_schedules_callback($post)
{
    $schedules = get_post_meta($post->ID, 'cinema_schedules', true);
?>
    <div id="cinema-schedules-container">
        <?php if ($schedules) : ?>
            <?php foreach ($schedules as $index => $schedule) : ?>
                <div class="cinema-schedule-row">
                    <input type="text" name="movie_schedule_time[<?php echo esc_attr($post->ID); ?>][]" value="<?php echo esc_attr($schedule['time']); ?>" placeholder="Lundi 10:00">
                    <button type="button" class="button delete-schedule-row">Supprimer</button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <button type="button" class="button" id="add-schedule-row">Ajouter Horaire</button>
<?php
}

?>