<?php



class WPOT_Frontend {

    public function __construct() {
        add_shortcode('wpot_scoreboard', [$this, 'render_scoreboard']);
        add_action('wp_enqueue_scripts', [$this, 'assets']);
    }

    public function assets(){
        wp_enqueue_style(
            'wpot-css',
            WPOT_URL.'assets/css/scoreboard.css',
            [],
            time() // dev: bust cache on each reload
        );

        wp_enqueue_script(
            'wpot-js',
            WPOT_URL.'assets/js/scoreboard.js',
            ['jquery'],
            time(),
            true
        );

        wp_localize_script('wpot-js', 'WPOT_API', [
            'ajax'      => admin_url('admin-ajax.php'),
            'interval'  => get_option('wpot_refresh_interval', 30),
        ]);
    }


    public function render_scoreboard($atts){

        $date = $atts['date'] ?? 'today';
        $cache_key = 'wpot_matches_' . $date;

        $matches = get_transient($cache_key);

        // debug
        // echo '<pre>';
        // print_r(WPOT_Crawler::fetch());
        // echo '</pre>';
        // die;


        ob_start();
        include WPOT_DIR . 'templates/scoreboard.php';
        return ob_get_clean();
    }

}
